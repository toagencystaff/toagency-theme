#!/bin/bash
# TEMA Immagini automatiche — 01/11/2026. Check 2, SOLA LETTURA sul server.
# 1) il server serve davvero i .webp gemelli? 2) file di uploads che consumano piu banda (ultimi 3 log)
# Scrive _out_immagini_check2.md nel repo. Lancio: bash _scripts/immagini_check2.sh
REPO="/Users/marcoleonardi/Documents/Claude/Projects/Toagency Theme"
OUT="$REPO/_out_immagini_check2.md"
T=$(mktemp /tmp/imgcheck2.XXXXXX) || exit 1
cat > "$T" <<'X'
WP=/home/customer/www/toagency.it/public_html
U=$WP/wp-content/uploads
echo "## Consegna webp (stesso URL, con e senza Accept webp)"
for f in 2026/03/Gemini_Generated_Image_xsv0x7xsv0x7xsv0.png 2026/04/TOA-LOGO-BLACK.png 2025/09/LogoToanew.png; do
  [ -f "$U/$f.webp" ] && W=si || W=no
  A=$(curl -s -o /dev/null -w '%{http_code} %{content_type} %{size_download}' -H 'Accept: image/avif,image/webp,*/*' "https://toagency.it/wp-content/uploads/$f?nc=$RANDOM")
  B=$(curl -s -o /dev/null -w '%{http_code} %{content_type} %{size_download}' -H 'Accept: image/png,*/*' "https://toagency.it/wp-content/uploads/$f?nc=$RANDOM")
  echo "$f | gemello webp: $W | con webp: $A | senza: $B"
done
J=$(cd "$U" && find 2026 -type f -iname '*.jpg.webp' | head -1); J=${J%.webp}
if [ -n "$J" ]; then
  A=$(curl -s -o /dev/null -w '%{http_code} %{content_type} %{size_download}' -H 'Accept: image/webp,*/*' "https://toagency.it/wp-content/uploads/$J?nc=$RANDOM")
  echo "$J | con webp: $A | file jpg: $(stat -c %s "$U/$J") | file webp: $(stat -c %s "$U/$J.webp")"
fi
echo
echo "## SG Optimizer"
cd $WP && wp plugin list --name=sg-cachepress --fields=name,status,version --format=csv 2>/dev/null
wp option get siteground_optimizer_webp_support 2>/dev/null
grep -rn -i 'webp' $WP/.htaccess $U/.htaccess 2>/dev/null | grep -vi shortpixel | head
php -m 2>/dev/null | grep -iE '^exif$' || echo "exif: NO"
echo
echo "## Form WPForms 7240 (cartella con 235 file pesanti)"
wp post get 7240 --field=post_title 2>/dev/null
ls -1 $U/wpforms/7240-dd4606baf99172732077a0642c91dea4 | head -3
echo
echo "## Top 40 file di uploads per banda (ultimi 3 log): MB, richieste, peso file, dimensioni, gemello webp"
cd /home/customer/www/toagency.it/logs || exit 1
L=$(mktemp /tmp/imgb.XXXXXX)
for f in $(ls -1 toagency.it-*.gz | tail -3); do zcat "$f"; done | awk '$7 ~ /^\/wp-content\/uploads\// {u=$7; sub(/\?.*/,"",u); b[u]+=$10; n[u]++} END {for (k in b) printf "%.1f\t%d\t%s\n", b[k]/1048576, n[k], k}' | sort -rn | head -40 > "$L"
while IFS="$(printf '\t')" read -r by nr url; do
  p="$WP$url"
  if [ -f "$p" ]; then s=$(stat -c %s "$p"); d=$(php -r '$i=@getimagesize($argv[1]); echo $i ? $i[0]."x".$i[1] : "-";' "$p"); else s=0; d="-"; fi
  [ -f "$p.webp" ] && w=si || w=no
  printf "%s MB\t%s\t%d KB\t%s\twebp:%s\t%s\n" "$by" "$nr" "$((s/1024))" "$d" "$w" "$url"
done < "$L"
echo
echo "Totale uploads ultimi 3 log: $(for f in $(ls -1 toagency.it-*.gz | tail -3); do zcat "$f"; done | awk '$7 ~ /^\/wp-content\/uploads\// {s+=$10} END {printf "%.0f MB", s/1048576}')"
echo "Totale tema ultimi 3 log: $(for f in $(ls -1 toagency.it-*.gz | tail -3); do zcat "$f"; done | awk '$7 ~ /^\/wp-content\/themes\// {s+=$10} END {printf "%.0f MB", s/1048576}')"
rm -f "$L"
X
{ echo "# Immagini check 2 - $(TZ=Europe/Rome date '+%Y-%m-%d %H:%M:%S')"; echo; } > "$OUT"
ssh toagency "bash -s" < "$T" >> "$OUT" 2>&1
RC=$?
rm -f "$T"
exit $RC
