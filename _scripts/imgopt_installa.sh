#!/bin/bash
# TEMA Immagini automatiche — 01/11/2026. Installa/aggiorna il mu-plugin TOA Image Optimizer e lo PROVA davvero.
# Prova: carica 3 immagini di test con wp media import (PNG foto 3000x2000, PNG logo trasparente, JPG 4000x3000),
# controlla formato/peso/misure/gemelli .webp, poi le cancella (e controlla che i file spariscano).
# Se la prova fallisce il plugin viene tolto da solo (spostato in ~/_backup_immagini_20261101/).
# Lancio: bash _scripts/imgopt_installa.sh   → esito in _out_imgopt_installa.md (ultima riga ESITO: OK / FAIL)
REPO="/Users/marcoleonardi/Documents/Claude/Projects/Toagency Theme"
SRC="$REPO/_mu-plugins/toa-image-optimizer.php"
OUT="$REPO/_out_imgopt_installa.md"
BK=/home/customer/_backup_immagini_20261101
{ echo "# Installa TOA Image Optimizer - $(TZ=Europe/Rome date '+%Y-%m-%d %H:%M:%S')"; echo; } > "$OUT"
[ -f "$SRC" ] || { echo "manca $SRC" >> "$OUT"; exit 1; }
ssh toagency "mkdir -p $BK" && scp -q "$SRC" "toagency:$BK/toa-image-optimizer.php.new" || { echo "scp fallito" >> "$OUT"; exit 1; }
T=$(mktemp /tmp/imgopt.XXXXXX) || exit 1
cat > "$T" <<'X'
WP=/home/customer/www/toagency.it/public_html
MU=$WP/wp-content/mu-plugins
BK=/home/customer/_backup_immagini_20261101
P=$MU/toa-image-optimizer.php
php -l "$BK/toa-image-optimizer.php.new" || { echo "ESITO: FAIL (sintassi)"; exit 1; }
[ -f "$P" ] && cp -p "$P" "$BK/toa-image-optimizer.php.prima_$(date +%H%M%S)"
cp "$BK/toa-image-optimizer.php.new" "$P" && chmod 644 "$P" || { echo "ESITO: FAIL (copia)"; exit 1; }
echo "Plugin copiato: $(ls -la $P)"
cd $WP || exit 1
PFX=$(wp db prefix 2>/dev/null)
TD=$(mktemp -d /tmp/imgoptt.XXXXXX)
convert -size 3000x2000 plasma:fractal "$TD/toa-imgopt-test-foto.png"
convert -size 1200x1200 xc:none -fill black -draw "circle 600,600 600,150" "$TD/toa-imgopt-test-logo.png"
convert -size 4000x3000 plasma:fractal -quality 98 "$TD/toa-imgopt-test-grande.jpg"
echo "File di prova: $(ls -la $TD | awk 'NR>1{print $5, $9}' | tr '\n' ' ')"
FAIL=0
IDS=""
for f in toa-imgopt-test-foto.png toa-imgopt-test-logo.png toa-imgopt-test-grande.jpg; do
  ID=$(wp media import "$TD/$f" --porcelain 2>/dev/null)
  if [ -z "$ID" ]; then echo "$f: import fallito"; FAIL=1; continue; fi
  IDS="$IDS $ID"
  F=$(wp eval "echo get_attached_file($ID);" 2>/dev/null)
  M=$(wp post get $ID --field=post_mime_type 2>/dev/null)
  S=$(stat -c %s "$F" 2>/dev/null || echo 0)
  D=$(php -r '$i=@getimagesize($argv[1]); echo $i ? $i[0]."x".$i[1] : "-";' "$F")
  NW=$(ls "$(dirname "$F")"/"$(basename "${F%.*}")"*.webp 2>/dev/null | wc -l)
  echo "$f -> id $ID | $(basename "$F") | $M | $((S/1024)) KB | $D | gemelli webp: $NW"
  [ "$S" -gt 0 ] && [ "$S" -le 307200 ] || { echo "   ✗ peso oltre 300 KB"; FAIL=1; }
  W=${D%x*}; H=${D#*x}; [ "$W" -le 2000 ] 2>/dev/null && [ "$H" -le 2000 ] 2>/dev/null || { echo "   ✗ lato oltre 2000 px"; FAIL=1; }
  case "$f" in
    *foto.png) [ "$M" = "image/jpeg" ] || { echo "   ✗ doveva diventare JPEG"; FAIL=1; } ; [ "$NW" -ge 1 ] || { echo "   ✗ nessun gemello webp"; FAIL=1; } ;;
    *logo.png) case "$M" in image/png|image/webp) ;; *) echo "   ✗ il logo doveva restare PNG/WebP"; FAIL=1 ;; esac ;;
    *grande.jpg) [ "$M" = "image/jpeg" ] || { echo "   ✗ doveva restare JPEG"; FAIL=1; } ;;
  esac
  echo "$F" >> "$TD/files.txt"
done
echo "Pulizia prove (cancello anche eventuali copie create da WPML):"
ALL=$(wp db query "SELECT ID FROM ${PFX}posts WHERE post_type='attachment' AND post_title LIKE 'toa-imgopt-test-%'" --skip-column-names 2>/dev/null | tr '\n' ' ')
[ -n "$ALL" ] && wp post delete $ALL --force >/dev/null 2>&1
echo "  cancellati id: $ALL"
LEFT=0
while IFS= read -r F; do
  n=$(ls "$(dirname "$F")"/"$(basename "${F%.*}")"* 2>/dev/null | wc -l)
  LEFT=$((LEFT+n))
done < "$TD/files.txt"
echo "  file rimasti dopo la cancellazione: $LEFT"
[ "$LEFT" -eq 0 ] || { echo "   ✗ restano file (miniature o webp non cancellati)"; FAIL=1; }
rm -rf "$TD"
if [ "$FAIL" -eq 0 ]; then
  echo "ESITO: OK"
else
  mv "$P" "$BK/toa-image-optimizer.php.TOLTO_$(date +%H%M%S)"
  echo "Plugin TOLTO (prova fallita): il sito carica le immagini come prima."
  echo "ESITO: FAIL"
  exit 1
fi
X
ssh toagency "bash -s" < "$T" >> "$OUT" 2>&1
RC=$?
rm -f "$T"
exit $RC
