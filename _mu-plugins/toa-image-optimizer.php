<?php
/**
 * Plugin Name: TOA Image Optimizer
 * Description: Ottimizza in automatico ogni immagine caricata nella libreria media (lato lungo max 2000 px, JPEG q82, PNG con trasparenza → PNG o WebP sotto ~300 KB) e crea i gemelli .webp (nome.ext.webp) che SiteGround serve allo stesso URL. Nessun passaggio manuale per lo staff.
 * Version: 1.0.0
 * Author: TOAgency (chat TEMA Immagini automatiche, 01/11/2026)
 *
 * Regole (Marco, ripetute più volte):
 *  - foto e PNG senza trasparenza  → JPEG qualità 82, lato lungo max 2000 px
 *  - PNG con trasparenza (loghi)   → PNG se resta sotto 300 KB, altrimenti WebP (trasparenza conservata)
 *  - risultato sempre sotto ~300 KB (se serve scende di qualità e poi di misura)
 *  - per originale e miniature crea nome.ext.webp: SiteGround lo manda allo stesso URL ai browser che accettano WebP
 *  - NON tocca: allegati WPForms (cartella wpforms/), file non immagine, GIF animate, immagini già piccole
 *  - in caso di qualunque errore lascia il file com'era (mai un upload bloccato)
 * Codice sorgente versionato nel repo Toagency Theme: _mu-plugins/toa-image-optimizer.php
 * Installazione/aggiornamento: _scripts/imgopt_installa.sh (copia in wp-content/mu-plugins + prova vera + chmod 644)
 */

if (!defined('ABSPATH')) {
    exit;
}

define('TOA_IMGOPT_MAX', 2000);       // lato lungo massimo in px
define('TOA_IMGOPT_Q', 82);           // qualità JPEG / WebP
define('TOA_IMGOPT_LIMIT', 307200);   // 300 KB
define('TOA_IMGOPT_WEBP_Q', 82);

// WordPress di suo crea un "-scaled" oltre 2560 px: con il nostro limite a 2000 non serve più.
add_filter('big_image_size_threshold', function () {
    return TOA_IMGOPT_MAX;
});

// Qualità delle miniature generate da WordPress.
add_filter('wp_editor_set_quality', function ($q, $mime = '') {
    return in_array($mime, array('image/jpeg', 'image/webp'), true) ? TOA_IMGOPT_Q : $q;
}, 10, 2);

// 1) Ottimizzazione del file caricato (vale per upload dalla libreria, REST, sideload/import da URL).
add_filter('wp_handle_upload', 'toa_imgopt_on_upload', 5, 2);

// 2) Gemelli .webp per originale + miniature, appena WordPress ha creato le miniature.
add_filter('wp_generate_attachment_metadata', 'toa_imgopt_make_webp_siblings', 20, 2);

// 3) Quando un'immagine viene cancellata dalla libreria, si cancella anche il suo gemello .webp.
add_filter('wp_delete_file', function ($file) {
    if (is_string($file) && preg_match('/\.(jpe?g|png)$/i', $file) && file_exists($file . '.webp')) {
        @unlink($file . '.webp');
    }
    return $file;
});

function toa_imgopt_on_upload($upload, $context = 'upload')
{
    if (!is_array($upload) || !empty($upload['error']) || empty($upload['file']) || !file_exists($upload['file'])) {
        return $upload;
    }
    $type = isset($upload['type']) ? $upload['type'] : '';
    if (!in_array($type, array('image/jpeg', 'image/png'), true)) {
        return $upload;
    }
    if (strpos(wp_normalize_path($upload['file']), '/wpforms/') !== false) {
        return $upload;
    }
    if (apply_filters('toa_imgopt_skip', false, $upload, $context)) {
        return $upload;
    }
    try {
        return toa_imgopt_process($upload);
    } catch (\Throwable $e) {
        error_log('[toa-imgopt] ' . $upload['file'] . ': ' . $e->getMessage());
        return $upload;
    }
}

function toa_imgopt_process($upload)
{
    $file = $upload['file'];
    $type = $upload['type'];
    $size = (int) filesize($file);
    $info = @getimagesize($file);
    if (!$info) {
        return $upload;
    }
    $w = (int) $info[0];
    $h = (int) $info[1];

    $orient = 1;
    if ($type === 'image/jpeg' && function_exists('exif_read_data')) {
        $exif = @exif_read_data($file);
        if (!empty($exif['Orientation'])) {
            $orient = (int) $exif['Orientation'];
        }
    }

    $alpha = ($type === 'image/png') ? toa_imgopt_png_has_alpha($file) : false;

    // Già a posto: JPEG leggero, non troppo grande, già dritto → non si tocca (niente doppia compressione).
    if ($type === 'image/jpeg' && $size <= TOA_IMGOPT_LIMIT && max($w, $h) <= TOA_IMGOPT_MAX && $orient <= 1) {
        return $upload;
    }
    // PNG con trasparenza già leggero e non troppo grande → resta com'è.
    if ($type === 'image/png' && $alpha && $size <= TOA_IMGOPT_LIMIT && max($w, $h) <= TOA_IMGOPT_MAX) {
        return $upload;
    }

    if (function_exists('wp_raise_memory_limit')) {
        wp_raise_memory_limit('image');
    }

    $dir  = dirname($file);
    $base = pathinfo($file, PATHINFO_FILENAME);

    if ($type === 'image/png' && $alpha) {
        // Prima prova: PNG ridimensionato. Se resta oltre 300 KB → WebP con trasparenza.
        $png = toa_imgopt_encode($file, 'image/png', $dir, $base, 'png', true);
        if ($png && filesize($png['path']) <= TOA_IMGOPT_LIMIT) {
            return toa_imgopt_swap($upload, $png);
        }
        if ($png && $png['path'] !== $file) {
            @unlink($png['path']);
        }
        $webp = toa_imgopt_encode($file, 'image/webp', $dir, $base, 'webp', false);
        return $webp ? toa_imgopt_swap($upload, $webp) : $upload;
    }

    // Foto JPEG e PNG senza trasparenza → JPEG.
    $jpg = toa_imgopt_encode($file, 'image/jpeg', $dir, $base, ($type === 'image/jpeg' ? pathinfo($file, PATHINFO_EXTENSION) : 'jpg'), false);
    if (!$jpg) {
        return $upload;
    }
    // Se il JPEG nuovo non è più leggero e non c'erano né ridimensionamento né rotazione né cambio formato, si tiene l'originale.
    if ($type === 'image/jpeg' && max($w, $h) <= TOA_IMGOPT_MAX && $orient <= 1 && filesize($jpg['path']) >= $size) {
        if ($jpg['path'] !== $file) {
            @unlink($jpg['path']);
        }
        return $upload;
    }
    return toa_imgopt_swap($upload, $jpg);
}

/**
 * Codifica $src nel formato $mime (lato lungo max 2000, qualità in discesa finché sta sotto 300 KB;
 * se non basta, scende a 1600 px). Scrive su un file temporaneo nella stessa cartella.
 * Ritorna array('path' => ..., 'mime' => ..., 'ext' => ...) oppure null.
 */
function toa_imgopt_encode($src, $mime, $dir, $base, $ext, $lossless_png)
{
    $tmp = trailingslashit($dir) . $base . '.toaimgopt-' . wp_generate_password(6, false) . '.' . $ext;
    $steps = $lossless_png ? array(array(TOA_IMGOPT_MAX, 90)) : array(
        array(TOA_IMGOPT_MAX, TOA_IMGOPT_Q),
        array(TOA_IMGOPT_MAX, 76),
        array(TOA_IMGOPT_MAX, 70),
        array(1600, 72),
        array(1280, 70),
    );
    foreach ($steps as $s) {
        $ed = wp_get_image_editor($src);
        if (is_wp_error($ed)) {
            return null;
        }
        if (method_exists($ed, 'maybe_exif_rotate')) {
            $ed->maybe_exif_rotate();
        }
        $sz = $ed->get_size();
        if (max($sz['width'], $sz['height']) > $s[0]) {
            $ed->resize($s[0], $s[0], false);
        }
        $ed->set_quality($s[1]);
        $res = $ed->save($tmp, $mime);
        if (is_wp_error($res) || empty($res['path']) || !file_exists($res['path'])) {
            return null;
        }
        $tmp = $res['path'];
        if (filesize($tmp) <= TOA_IMGOPT_LIMIT) {
            break;
        }
    }
    return array('path' => $tmp, 'mime' => $mime, 'ext' => $ext);
}

/** Mette il file ottimizzato al posto dell'originale e aggiorna path/url/type dell'upload. */
function toa_imgopt_swap($upload, $new)
{
    $old  = $upload['file'];
    $dir  = dirname($old);
    $base = pathinfo($old, PATHINFO_FILENAME);
    $oext = pathinfo($old, PATHINFO_EXTENSION);

    if (strtolower($new['ext']) === strtolower($oext)) {
        $final = $old;                                   // stesso nome: si sovrascrive
    } else {
        $name  = wp_unique_filename($dir, $base . '.' . $new['ext']);
        $final = trailingslashit($dir) . $name;
    }
    if (!@rename($new['path'], $final)) {
        @unlink($new['path']);
        return $upload;
    }
    @chmod($final, 0644);
    if ($final !== $old) {
        @unlink($old);
    }
    $upload['file'] = $final;
    $upload['url']  = trailingslashit(dirname($upload['url'])) . rawurlencode(basename($final));
    $upload['type'] = $new['mime'];
    return $upload;
}

/** Vero se il PNG ha almeno un pixel trasparente (controllo su una copia ridotta a 200 px). */
function toa_imgopt_png_has_alpha($file)
{
    $fp = @fopen($file, 'rb');
    if (!$fp) {
        return false;
    }
    $head = fread($fp, 4096);
    fclose($fp);
    $ct   = isset($head[25]) ? ord($head[25]) : 2;
    $trns = strpos($head, 'tRNS') !== false;
    if ($ct !== 4 && $ct !== 6 && !$trns) {
        return false;
    }
    if (!function_exists('imagecreatefrompng')) {
        return true;
    }
    $im = @imagecreatefrompng($file);
    if (!$im) {
        return true;
    }
    $w  = imagesx($im);
    $h  = imagesy($im);
    $sw = min(200, $w);
    $sh = max(1, (int) round($h * $sw / max(1, $w)));
    $sm = imagecreatetruecolor($sw, $sh);
    imagealphablending($sm, false);
    imagesavealpha($sm, true);
    imagecopyresampled($sm, $im, 0, 0, 0, 0, $sw, $sh, $w, $h);
    imagedestroy($im);
    $found = false;
    for ($y = 0; $y < $sh && !$found; $y++) {
        for ($x = 0; $x < $sw; $x++) {
            if (((imagecolorat($sm, $x, $y) >> 24) & 0x7F) > 0) {
                $found = true;
                break;
            }
        }
    }
    imagedestroy($sm);
    return $found;
}

/** Crea nome.ext.webp per l'originale e per ogni miniatura (solo se pesa almeno il 10% in meno). */
function toa_imgopt_make_webp_siblings($metadata, $attachment_id = 0)
{
    if (!is_array($metadata) || empty($metadata['file']) || !function_exists('imagewebp')) {
        return $metadata;
    }
    try {
        $uploads = wp_get_upload_dir();
        $main    = trailingslashit($uploads['basedir']) . $metadata['file'];
        if (strpos(wp_normalize_path($main), '/wpforms/') !== false) {
            return $metadata;
        }
        $dir   = dirname($main);
        $files = array($main);
        if (!empty($metadata['original_image'])) {
            $files[] = trailingslashit($dir) . $metadata['original_image'];
        }
        if (!empty($metadata['sizes']) && is_array($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $s) {
                if (!empty($s['file'])) {
                    $files[] = trailingslashit($dir) . $s['file'];
                }
            }
        }
        foreach (array_unique($files) as $f) {
            toa_imgopt_webp_sibling($f);
        }
    } catch (\Throwable $e) {
        error_log('[toa-imgopt] webp ' . $attachment_id . ': ' . $e->getMessage());
    }
    return $metadata;
}

function toa_imgopt_webp_sibling($f)
{
    if (!file_exists($f) || file_exists($f . '.webp') || !preg_match('/\.(jpe?g|png)$/i', $f)) {
        return;
    }
    $is_png = (bool) preg_match('/\.png$/i', $f);
    $im = $is_png ? @imagecreatefrompng($f) : @imagecreatefromjpeg($f);
    if (!$im) {
        return;
    }
    if (!$is_png && function_exists('exif_read_data')) {
        $exif = @exif_read_data($f);
        $o = !empty($exif['Orientation']) ? (int) $exif['Orientation'] : 1;
        $rot = array(3 => 180, 6 => -90, 8 => 90);
        if (isset($rot[$o])) {
            $r = imagerotate($im, $rot[$o], 0);
            if ($r) {
                imagedestroy($im);
                $im = $r;
            }
        }
    }
    if (function_exists('imagepalettetotruecolor')) {
        imagepalettetotruecolor($im);
    }
    imagealphablending($im, false);
    imagesavealpha($im, true);
    $tmp = $f . '.webp.tmp';
    $ok  = @imagewebp($im, $tmp, TOA_IMGOPT_WEBP_Q);
    imagedestroy($im);
    if (!$ok || !file_exists($tmp)) {
        @unlink($tmp);
        return;
    }
    if (filesize($tmp) < filesize($f) * 0.9) {
        @rename($tmp, $f . '.webp');
        @chmod($f . '.webp', 0644);
    } else {
        @unlink($tmp);
    }
}
