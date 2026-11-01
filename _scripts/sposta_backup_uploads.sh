#!/bin/bash
# TEMA Immagini automatiche — 01/11/2026. Sicurezza: la cartella di backup vecchia dentro wp-content/uploads
# (1657394903-…: plugins/, themes/, sql/ con .sql e .zip, 224 MB) e' raggiungibile dal web.
# La sposta FUORI da public_html (niente cancellato): /home/customer/_backup_vecchi/
# Prima e dopo controlla se un file dentro risponde da internet (prima atteso 200, dopo 404).
# Lancio: bash _scripts/sposta_backup_uploads.sh → esito in _out_sposta_backup.md (ultima riga ESITO: OK / FAIL)
REPO="/Users/marcoleonardi/Documents/Claude/Projects/Toagency Theme"
OUT="$REPO/_out_sposta_backup.md"
T=$(mktemp /tmp/spbk.XXXXXX) || exit 1
cat > "$T" <<'X'
U=/home/customer/www/toagency.it/public_html/wp-content/uploads
DEST=/home/customer/_backup_vecchi
cd "$U" || exit 1
D=$(ls -d 1657394903-* 2>/dev/null | head -1)
[ -n "$D" ] || { echo "Cartella gia spostata o assente"; echo "ESITO: OK"; exit 0; }
echo "Cartella: $D ($(du -sh "$D" | cut -f1))"
echo "Contenuto (prime righe):"; find "$D" -maxdepth 2 | head -15
F=$(find "$D" -type f \( -name '*.txt' -o -name '*.zip' -o -name '*.sql' \) | head -1)
echo "File di prova: $F"
echo "Da internet PRIMA: $(curl -s -o /dev/null -w '%{http_code} %{size_download} byte' -r 0-99 "https://toagency.it/wp-content/uploads/$F")"
mkdir -p "$DEST" && mv "$U/$D" "$DEST/uploads_$D" || { echo "ESITO: FAIL (spostamento)"; exit 1; }
echo "Spostata in: $DEST/uploads_$D"
C=$(curl -s -o /dev/null -w '%{http_code}' -r 0-99 "https://toagency.it/wp-content/uploads/$F?nc=$RANDOM")
echo "Da internet DOPO: $C"
echo "Home: $(curl -s -o /dev/null -w '%{http_code}' https://toagency.it/)"
[ "$C" != "200" ] && [ "$C" != "206" ] && echo "ESITO: OK" || { echo "ESITO: FAIL (risponde ancora, forse cache)"; exit 1; }
X
{ echo "# Sposta backup vecchio fuori da public_html - $(TZ=Europe/Rome date '+%Y-%m-%d %H:%M:%S')"; echo; } > "$OUT"
ssh toagency "bash -s" < "$T" >> "$OUT" 2>&1
RC=$?
rm -f "$T"
exit $RC
