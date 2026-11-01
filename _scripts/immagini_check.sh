#!/bin/bash
# TEMA Immagini automatiche — 01/11/2026. SOLA LETTURA sul server (non modifica nulla).
# Scrive nel repo: _out_immagini_check.md (riepilogo) + _out_immagini_lista.tsv (elenco completo).
# Lancio: bash _scripts/immagini_check.sh
REPO="/Users/marcoleonardi/Documents/Claude/Projects/Toagency Theme"
OUT="$REPO/_out_immagini_check.md"
LISTA="$REPO/_out_immagini_lista.tsv"
T=$(mktemp /tmp/imgcheck.XXXXXX) || exit 1
cat > "$T" <<'X'
WP=/home/customer/www/toagency.it/public_html
U=$WP/wp-content/uploads
cd "$U" || { echo "uploads non trovato"; exit 1; }
echo "## Strumenti sul server"
for t in cwebp convert identify jpegoptim optipng pngquant wp; do printf "%s: %s\n" "$t" "$(command -v $t || echo NO)"; done
php -v 2>/dev/null | head -1
php -m 2>/dev/null | grep -iE '^(gd|imagick)$' | tr '\n' ' '; echo
php -r 'if(class_exists("Imagick")){$v=Imagick::getVersion();echo "Imagick: ".$v["versionString"]."\n"; echo "WEBP in Imagick: ".(in_array("WEBP",Imagick::queryFormats())?"si":"no")."\n";} if(function_exists("imagewebp")) echo "GD imagewebp: si\n";'
echo
echo "## Spazio uploads"
du -sh "$U" 2>/dev/null
echo "Cartelle principali (MB):"
du -sm "$U"/* 2>/dev/null | sort -rn | head -15
echo
echo "## mu-plugins e .htaccess"
ls -la $WP/wp-content/mu-plugins 2>/dev/null
echo "-- righe webp/image nel .htaccess del sito:"
grep -niE 'webp|image/|uploads' $WP/.htaccess | head -30
echo "-- .htaccess dentro uploads:"
cat "$U/.htaccess" 2>/dev/null | head -30
echo "-- filtri immagini gia presenti (tema/mu-plugins):"
grep -rlnE 'big_image_size_threshold|wp_handle_upload|jpeg_quality|wp_editor_set_quality' $WP/wp-content/themes/toagency-theme $WP/wp-content/mu-plugins 2>/dev/null | head
echo "-- opzioni SG Optimizer:"
cd $WP && wp option list --search='siteground_optimizer_*' --fields=option_name,option_value --format=csv 2>/dev/null | grep -iE 'webp|compress|image|resize|quality' | head -20
echo "-- big_image_size_threshold attuale:"
wp eval 'echo apply_filters("big_image_size_threshold", 2560, array(0,0), "", 0), "\n";' 2>/dev/null
cd "$U"
echo
echo "## Immagini oltre 300 KB in uploads"
L=$(mktemp /tmp/imgl.XXXXXX)
find . -type f \( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' -o -iname '*.gif' -o -iname '*.webp' \) -size +300k -printf '%s\t%TY-%Tm-%Td\t%p\n' > "$L"
echo "Totale file: $(wc -l < "$L")  MB: $(awk -F'\t' '{s+=$1} END {printf "%.0f", s/1048576}' "$L")"
echo "Per estensione (file, MB):"
awk -F'\t' '{n=tolower($3); sub(/.*\./,"",n); c[n]++; s[n]+=$1} END {for (e in c) printf "  %s %d %.0f\n", e, c[e], s[e]/1048576}' "$L"
echo "Per tipo (originale / miniatura WP / -scaled), file e MB:"
awk -F'\t' '{t="originale"; if ($3 ~ /-[0-9]+x[0-9]+\.[A-Za-z]+$/) t="miniatura"; else if ($3 ~ /-scaled\.[A-Za-z]+$/) t="scaled"; c[t]++; s[t]+=$1} END {for (e in c) printf "  %s %d %.0f\n", e, c[e], s[e]/1048576}' "$L"
echo "Per cartella anno/mese (top 15, MB):"
awk -F'\t' '{split($3,p,"/"); k=p[2]"/"p[3]; s[k]+=$1; c[k]++} END {for (k in s) printf "%.0f\t%d\t%s\n", s[k]/1048576, c[k], k}' "$L" | sort -rn | head -15
echo
echo "## Analisi PNG/JPG (dimensioni, trasparenza vera, webp gemello)"
P=$(mktemp /tmp/imgp.XXXXXX)
cat > "$P" <<'PHPX'
<?php
$f = $argv[1];
$ok = 0; $tot = 0;
$h = fopen($f, 'r');
while (($r = fgets($h)) !== false) {
  $r = rtrim($r, "\n"); list($size, $date, $path) = explode("\t", $r, 3);
  $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
  $info = @getimagesize($path); $w = $info ? $info[0] : 0; $hh = $info ? $info[1] : 0;
  $alpha = '-';
  if ($ext === 'png') {
    $alpha = 'no';
    $fp = fopen($path, 'rb'); $head = fread($fp, 64); fclose($fp);
    $ct = ord($head[25]);
    $hasTrns = (strpos(file_get_contents($path, false, null, 0, 4096), 'tRNS') !== false);
    if ($ct == 4 || $ct == 6 || $hasTrns) {
      $alpha = 'canale';
      $im = @imagecreatefrompng($path);
      if ($im) {
        $sw = 200; $sh = max(1, (int)round($hh * 200 / max(1, $w)));
        $sm = imagecreatetruecolor($sw, $sh); imagealphablending($sm, false); imagesavealpha($sm, true);
        imagecopyresampled($sm, $im, 0, 0, 0, 0, $sw, $sh, $w, $hh);
        $tr = 0;
        for ($y = 0; $y < $sh && !$tr; $y++) for ($x = 0; $x < $sw; $x++) { if ((imagecolorat($sm, $x, $y) >> 24) & 0x7F) { $tr = 1; break; } }
        $alpha = $tr ? 'SI' : 'no(opaca)';
        imagedestroy($im); imagedestroy($sm);
      }
    }
  }
  $webp = file_exists($path . '.webp') ? 'si' : 'no';
  echo "$size\t$date\t{$w}x{$hh}\t$alpha\t$webp\t$path\n";
}
PHPX
php -d memory_limit=1024M "$P" "$L" > "$L.full" 2>/dev/null
echo "PNG con trasparenza vera: $(awk -F'\t' '$4=="SI"' "$L.full" | wc -l)  | PNG opachi (diventano JPEG): $(awk -F'\t' '$4=="no" || $4=="no(opaca)"' "$L.full" | wc -l)"
echo "Lato lungo oltre 2000 px: $(awk -F'\t' '{split($3,d,"x"); if (d[1]>2000 || d[2]>2000) n++} END {print n+0}' "$L.full")"
echo "Hanno gia il gemello .webp (naming SG nome.ext.webp): $(awk -F'\t' '$5=="si"' "$L.full" | wc -l)"
echo
echo "## Top 40 per peso (byte, data, dimensioni, trasparenza, webp, file)"
sort -rn "$L.full" | head -40
echo
echo "## webp gia presenti in uploads"
echo "File .webp totali: $(find . -type f -iname '*.webp' | wc -l)  | di cui naming SG (.jpg.webp/.png.webp): $(find . -type f \( -iname '*.jpg.webp' -o -iname '*.jpeg.webp' -o -iname '*.png.webp' \) | wc -l)"
echo "@@LISTA@@"
sort -rn "$L.full"
rm -f "$L" "$L.full" "$P"
X
{ echo "# Immagini check - $(TZ=Europe/Rome date '+%Y-%m-%d %H:%M:%S')"; echo; } > "$OUT"
ssh toagency "bash -s" < "$T" > "$OUT.tmp" 2>&1
RC=$?
rm -f "$T"
awk '/^@@LISTA@@$/{exit} {print}' "$OUT.tmp" >> "$OUT"
awk 'f{print} /^@@LISTA@@$/{f=1}' "$OUT.tmp" > "$LISTA"
rm -f "$OUT.tmp"
echo "Righe elenco: $(wc -l < "$LISTA")" >> "$OUT"
exit $RC
