<?php
/**
 * Template Name: Presentazione TOAgency
 * Ticket #824 (05/11/2026, chat TEMA Ticket 824): pagina "company profile" B2B in 4 lingue + PDF scaricabile.
 * - Testi: inc/presentazione-testi.json (IT/EN/FR/ES, scritti per ogni lingua, non tradotti alla lettera)
 * - PDF statici: assets/pdf/toagency-presentazione-2026-{lingua}.pdf, generati da _scripts/render-presentazione-pdf.php (+ Chrome headless)
 *   (i vecchi assets/pdf/presentazione-{lingua}.pdf servono altre pagine e NON si toccano)
 * - Resta NOINDEX finche' Marco non da il via: opzione WP 'toa_presentazione_live' (1 = indicizzabile).
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';

$all    = json_decode(file_get_contents(get_theme_file_path('inc/presentazione-testi.json')), true);
$T      = $all[$lang];
$brands = json_decode(file_get_contents(get_theme_file_path('inc/presentazione-brand.json')), true);

// URL nella lingua corrente: se la pagina tradotta esiste WPML la restituisce, altrimenti resta quella italiana
$U = function($path) use ($lang) {
    $u = home_url($path);
    $t = apply_filters('wpml_permalink', $u, $lang);
    return $t ? $t : $u;
};
$form_url = $U('/form-b2b/');
$pdf_file = 'assets/pdf/toagency-presentazione-2026-' . $lang . '.pdf';
$pdf_url  = get_theme_file_uri($pdf_file);

// lingue: solo traduzioni WPML reali di questa pagina
$langs = array();
if (function_exists('icl_get_languages')) {
    $ls = icl_get_languages('skip_missing=1&orderby=code');
    if (is_array($ls)) {
        foreach (array('it','en','fr','es') as $lc) {
            if (isset($ls[$lc])) $langs[$lc] = $ls[$lc]['url'];
        }
    }
}

// noindex finche' non e' approvata
if (!get_option('toa_presentazione_live')) {
    add_filter('wp_robots', function($r) { $r['noindex'] = true; $r['nofollow'] = true; unset($r['index'], $r['follow']); return $r; }, 1000);
    add_filter('wpseo_robots', function() { return 'noindex, nofollow'; }, 1000);
    add_filter('wpseo_canonical', '__return_false', 1000);
}

wp_enqueue_style('toa-presentazione', get_theme_file_uri('assets/css/presentazione.css'), array(), filemtime(get_theme_file_path('assets/css/presentazione.css')));
wp_enqueue_script('toa-presentazione', get_theme_file_uri('assets/js/presentazione.js'), array(), filemtime(get_theme_file_path('assets/js/presentazione.js')), true);
wp_add_inline_script('toa-presentazione', 'window.TOA_PRES=' . wp_json_encode(array('lang' => $lang, 'brands' => $brands)) . ';', 'before');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="pr-html">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<meta name="theme-color" content="#08080A">
<title><?php echo esc_html($T['doc_title']); ?></title>
<meta name="description" content="<?php echo esc_attr($T['doc_desc']); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class('pr-page'); ?>>
<?php include get_theme_file_path('components/presentazione-web.php'); ?>
<?php wp_footer(); ?>
</body>
</html>
