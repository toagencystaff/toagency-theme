#!/bin/bash
# TEMA Immagini automatiche — 01/11/2026. Pulizia immagini GIA presenti in wp-content/uploads.
# Uso:  bash _scripts/immagini_pulizia.sh prova     (SOLA LETTURA: elenco, numeri, MB risparmiati stimati)
#       bash _scripts/immagini_pulizia.sh applica   (crea i .webp gemelli + ricomprime i JPG pesanti)
# Cosa fa "applica" (gli URL NON cambiano mai):
#  A) per ogni JPG/PNG delle cartelle anno (20xx/) e della radice di uploads oltre 10 KB SENZA gemello:
#     crea nome.ext.webp (naming SiteGround, servito allo stesso URL ai browser che accettano WebP),
#     cwebp -q 82, lato lungo max 2000 px, orientamento EXIF corretto; tenuto solo se pesa almeno 10% in meno.
#     I .webp gia esistenti NON si toccano.
#  B) i JPG originali oltre 300 KB: ricompressi sul posto (stesso nome, stesse dimensioni) con jpegoptim -m82,
#     copia di sicurezza in ~/_backup_immagini_20261101/originali/ (FUORI da public_html).
#  Esclusi: wpforms/ (allegati candidature), elementor/, cartelle non-anno. Si puo rilanciare: salta cio che e gia fatto.
#  Annullare: cancellare i file elencati in ~/_backup_immagini_20261101/webp_creati.txt e ricopiare originali/.
MODO="$1"; [ "$MODO" = "prova" ] || [ "$MODO" = "applica" ] || { echo "uso: prova | applica"; exit 1; }
REPO="/Users/marcoleonardi/Documents/Claude/Projects/Toagency Theme"
OUT="$REPO/_out_immagini_pulizia_$MODO.md"
T=$(mktemp /tmp/imgpul.XXXXXX) || exit 1
echo "MODO=$MODO" > "$T"
cat >> "$T" <<'X'
WP=/home/customer/www/toagency.it/public_html
U=$WP/wp-content/uploads
BK=/home/customer/_backup_immagini_20261101
cd "$U" || exit 1
TMPD=$(mktemp -d /tmp/imgw.XXXXXX)
C=$TMPD/cand.txt; J=$TMPD/jpg.txt
# --- candidati A (webp gemelli)
: > "$C"
for d in 20[0-9][0-9]; do
  [ -d "$d" ] && find "$d" -type f \( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' \) -size +10k -printf '%s\t%p\n' >> "$C"
done
find . -maxdepth 1 -type f \( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' \) -size +10k -printf '%s\t%P\n' >> "$C"
awk -F'\t' '{print $2}' "$C" > "$TMPD/p.txt"
: > "$TMPD/ca.txt"
while IFS= read -r f; do [ -f "$f.webp" ] || echo "$f" >> "$TMPD/ca.txt"; done < "$TMPD/p.txt"
NA=$(wc -l < "$TMPD/ca.txt")
MBA=$(while IFS= read -r f; do stat -c %s "$f"; done < "$TMPD/ca.txt" | awk '{s+=$1} END {printf "%.0f", s/1048576}')
# --- candidati B (jpg originali > 300 KB, non miniature)
: > "$J"
for d in 20[0-9][0-9]; do
  [ -d "$d" ] && find "$d" -type f \( -iname '*.jpg' -o -iname '*.jpeg' \) -size +300k -printf '%p\n' >> "$J"
done
grep -vE -- '-[0-9]+x[0-9]+\.(jpe?g|JPE?G)$' "$J" > "$TMPD/j2.txt"; mv "$TMPD/j2.txt" "$J"
NB=$(wc -l < "$J")

mkwebp() { # $1 sorgente, $2 destinazione webp
  local f="$1" o="$2" r src a w h
  src="$f"
  r=$(identify -format '%[EXIF:Orientation]' "$f[0]" 2>/dev/null)
  case "$r" in 2|3|4|5|6|7|8) src="$TMPD/rot.png"; convert "$f[0]" -auto-orient "$src" 2>/dev/null || return 1 ;; esac
  set -- $(identify -format '%w %h' "$src[0]" 2>/dev/null)
  w=${1:-0}; h=${2:-0}; a=""
  if [ "$w" -gt 2000 ] || [ "$h" -gt 2000 ]; then
    if [ "$w" -ge "$h" ]; then a="-resize 2000 0"; else a="-resize 0 2000"; fi
  fi
  nice cwebp -quiet -q 82 -alpha_q 90 -m 4 -metadata none $a "$src" -o "$o" 2>/dev/null
}

echo "## Riepilogo ($MODO)"
echo "A) JPG/PNG senza gemello .webp (cartelle anno + radice, oltre 10 KB): $NA file, $MBA MB"
echo "B) JPG originali oltre 300 KB da ricomprimere sul posto: $NB file"
echo

if [ "$MODO" = "prova" ]; then
  echo "## Stima A su un campione (60 file: i 30 piu pesanti + 30 a caso), nessun file del sito toccato"
  while IFS= read -r f; do printf '%s\t%s\n' "$(stat -c %s "$f")" "$f"; done < "$TMPD/ca.txt" | sort -rn > "$TMPD/cas.txt"
  head -30 "$TMPD/cas.txt" > "$TMPD/camp.txt"
  tail -n +31 "$TMPD/cas.txt" | awk 'BEGIN{srand(11)} {print rand()"\t"$0}' | sort -n | head -30 | cut -f2- >> "$TMPD/camp.txt"
  S0=0; S1=0
  while IFS="$(printf '\t')" read -r s f; do
    mkwebp "$f" "$TMPD/t.webp" && n=$(stat -c %s "$TMPD/t.webp") || n=$s
    [ "$n" -ge $((s*9/10)) ] && n=$s
    S0=$((S0+s)); S1=$((S1+n))
    printf '%d KB -> %d KB\t%s\n' $((s/1024)) $((n/1024)) "$f"
    rm -f "$TMPD/t.webp"
  done < "$TMPD/camp.txt"
  echo "Campione: $((S0/1048576)) MB -> $((S1/1048576)) MB"
  echo "Stima su tutto A: $MBA MB -> circa $(awk -v a="$MBA" -v s0="$S0" -v s1="$S1" 'BEGIN{printf "%.0f", a*s1/s0}') MB per i browser che accettano WebP"
  echo
  echo "## B) JPG da ricomprimere (stima jpegoptim -m82, senza scrivere)"
  B0=0; B1=0
  while IFS= read -r f; do
    s=$(stat -c %s "$f"); cp "$f" "$TMPD/b.jpg"; jpegoptim -q -m82 --strip-none "$TMPD/b.jpg" >/dev/null 2>&1; n=$(stat -c %s "$TMPD/b.jpg")
    B0=$((B0+s)); B1=$((B1+n)); printf '%d KB -> %d KB\t%s\n' $((s/1024)) $((n/1024)) "$f"
  done < "$J"
  echo "Totale B: $((B0/1048576)) MB -> $((B1/1048576)) MB"
  echo
  echo "## Tema: file che consumano piu banda (ultimi 3 log)"
  cd /home/customer/www/toagency.it/logs && for f in $(ls -1 toagency.it-*.gz | tail -3); do zcat "$f"; done | awk '$7 ~ /^\/wp-content\/themes\// {u=$7; sub(/\?.*/,"",u); b[u]+=$10; n[u]++} END {for (k in b) printf "%.1f MB\t%d\t%s\n", b[k]/1048576, n[k], k}' | sort -rn | head -20
  echo
  echo "## Cartella 1657394903-* (224 MB): cosa contiene"
  ls "$U"/1657394903-* | head -5; find "$U"/1657394903-* -type f | sed 's/.*\.//' | sort | uniq -c | sort -rn | head -5
else
  mkdir -p "$BK/originali" || exit 1
  touch "$BK/webp_creati.txt"
  OK=0; SK=0; KO=0
  while IFS= read -r f; do
    [ -f "$f.webp" ] && continue
    if mkwebp "$f" "$TMPD/t.webp"; then
      s=$(stat -c %s "$f"); n=$(stat -c %s "$TMPD/t.webp")
      if [ "$n" -lt $((s*9/10)) ]; then cp "$TMPD/t.webp" "$f.webp" && chmod 644 "$f.webp" && echo "$U/$f.webp" >> "$BK/webp_creati.txt" && OK=$((OK+1))
      else SK=$((SK+1)); fi
    else KO=$((KO+1)); echo "ERRORE webp: $f"; fi
    rm -f "$TMPD/t.webp" "$TMPD/rot.png"
  done < "$TMPD/ca.txt"
  echo "A) webp creati: $OK | saltati (non convenienti): $SK | errori: $KO"
  B0=0; B1=0; NBF=0
  while IFS= read -r f; do
    mkdir -p "$BK/originali/$(dirname "$f")"
    [ -f "$BK/originali/$f" ] || cp -p "$f" "$BK/originali/$f" || continue
    s=$(stat -c %s "$f"); nice jpegoptim -q -m82 --strip-none "$f" >/dev/null 2>&1; chmod 644 "$f"; n=$(stat -c %s "$f")
    B0=$((B0+s)); B1=$((B1+n)); NBF=$((NBF+1))
  done < "$J"
  echo "B) JPG ricompressi: $NBF | $((B0/1048576)) MB -> $((B1/1048576)) MB"
  echo "Verifica URL (devono restare 200):"
  for f in $(head -3 "$J") $(tail -3 "$BK/webp_creati.txt" | sed "s#^$U/##; s#\.webp\$##"); do
    echo "$(curl -s -o /dev/null -w '%{http_code} %{content_type} %{size_download}' -H 'Accept: image/webp,*/*' "https://toagency.it/wp-content/uploads/$f?nc=$RANDOM") | $f"
  done
  echo "Copie di sicurezza: $BK (fuori da public_html)"
fi
rm -rf "$TMPD"
X
{ echo "# Immagini pulizia $MODO - $(TZ=Europe/Rome date '+%Y-%m-%d %H:%M:%S')"; echo; } > "$OUT"
ssh toagency "bash -s" < "$T" >> "$OUT" 2>&1
RC=$?
rm -f "$T"
echo "Fine: $(TZ=Europe/Rome date '+%H:%M:%S')" >> "$OUT"
exit $RC
