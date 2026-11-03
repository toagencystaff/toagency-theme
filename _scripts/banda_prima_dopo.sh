#!/bin/bash
# TEMA Immagini automatiche — 02/11/2026. SOLA LETTURA. Confronta i byte medi per richiesta PRIMA e DOPO
# la pulizia immagini (01/11 ore 16:20) per i file piu pesanti, dagli ultimi 2 log. Esito: _out_banda_prima_dopo.md
REPO="/Users/marcoleonardi/Documents/Claude/Projects/Toagency Theme"
OUT="$REPO/_out_banda_prima_dopo.md"
T=$(mktemp /tmp/bpd.XXXXXX) || exit 1
cat > "$T" <<'X'
cd /home/customer/www/toagency.it/logs || exit 1
L=$(mktemp /tmp/bpdl.XXXXXX)
for f in $(ls -1 toagency.it-*.gz | tail -2); do zcat "$f"; done > "$L"
echo "## Periodo coperto dagli ultimi 2 log"
head -1 "$L" | awk '{print "primo: " $4}'; tail -1 "$L" | awk '{print "ultimo: " $4}'
echo
echo "## GB per ora (01/11 e 02/11)"
awk '{split($4,a,":"); k=substr(a[1],2)" "a[2]"h"; s[k]+=$10} END {for (k in s) printf "%s\t%.2f GB\n", k, s[k]/1073741824}' "$L" | sort -t/ -k1,1n -k2,2M | sort
echo
echo "## Byte medi per richiesta: PRIMA (01/11 prima delle 16:20) / DOPO (dalle 16:30)"
awk '
{ u=$7; sub(/\?.*/,"",u); split($4,a,":"); d=substr(a[1],2); h=a[2]+0; m=a[3]+0;
  off=substr($5,1,3)+0; lim=920+off*60;
  t=h*60+m;
  if (d=="01/Nov/2026" && t < lim) { pb[u]+=$10; pn[u]++ }
  else if ((d=="01/Nov/2026" && t >= lim+10) || d=="02/Nov/2026") { db[u]+=$10; dn[u]++ } }
END { for (u in pb) if (pb[u] > 5*1048576) printf "%s\tprima %d KB x %d\tdopo %s\n", u, pb[u]/pn[u]/1024, pn[u], (dn[u] ? sprintf("%d KB x %d", db[u]/dn[u]/1024, dn[u]) : "nessuna richiesta") }' "$L" | sort | head -40
echo
echo "## DOPO: chi scarica i PNG pesanti e quanto pesa ogni risposta (browser = dovrebbe ricevere WebP)"
awk '{ split($4,a,":"); d=substr(a[1],2); h=a[2]+0; m=a[3]+0; off=substr($5,1,3)+0; lim=920+off*60;
  if (!((d=="01/Nov/2026" && h*60+m >= lim+10) || d=="02/Nov/2026")) next;
  if ($7 !~ /(Host-Elegante-in-Showroom-Moderno-2|capelli|apollonia|Soggiorno-caldo-tra-amici-e-piante|TOA-LOGO-BLACK)\.png/) next;
  n=split($0,q,"\""); ua=q[6]; gsub(/[0-9.]+/,"",ua); ua=substr(ua,1,60);
  k=$7" | "$9" | "int($10/1024)" KB | "ua; c[k]++ }
  END { for (k in c) printf "%d x %s\n", c[k], k }' "$L" | sort -rn | head -40
echo
echo "Fuso orario del log: $(head -1 "$L" | awk '{print $5}') (modifiche online dalle 16:20 ora italiana)"
rm -f "$L"
X
{ echo "# Banda prima/dopo - $(TZ=Europe/Rome date '+%Y-%m-%d %H:%M:%S')"; echo; } > "$OUT"
ssh toagency "bash -s" < "$T" >> "$OUT" 2>&1
RC=$?
rm -f "$T"
exit $RC
