#!/usr/bin/env bash
# crea-pagine-fiere.sh — crea le 8 pagine WordPress delle landing fiera
# TEMA LP-FIERE-QUALITY-SCORE — 2026-09-08 marco
#
# IDEMPOTENTE: si può rilanciare quante volte si vuole. Se una pagina esiste già
# non la ricrea, si limita a rimettere a posto template e meta. Nessun doppione.
#
# Cosa imposta su ogni pagina:
#   - genitore  = pagina "LP" (trovata per slug, non a numero fisso)
#   - template  = templates/page-landing-ads.php
#   - meta      = _toa_ads_key (la chiave vera: NON ci si appoggia allo slug)
#   - contenuto = vuoto (tutto il testo arriva dal template)
#
# Le pagine NON hanno traduzioni WPML, come le altre /lp/: le 4 lingue le fa il
# template con ?lang=. Verificato 08/09: hreflang solo "it" + "x-default".
#
# PRIMA di lanciarlo: deployare il tema (serve inc/lp-fiere.php sul server).

set -euo pipefail

ssh toagency 'bash -s' <<'REMOTE'
set -euo pipefail
cd /home/customer/www/toagency.it/public_html

PARENT=$(wp post list --post_type=page --name=lp --field=ID --posts_per_page=1 --post_status=any || true)
if [ -z "$PARENT" ]; then
  echo "ERRORE: non trovo la pagina genitore con slug 'lp'. Fermo qui."
  exit 1
fi
echo "Pagina genitore LP = ID $PARENT"
echo

crea_landing() {
  SLUG="$1"; TITOLO="$2"
  ID=$(wp post list --post_type=page --name="$SLUG" --field=ID --posts_per_page=1 --post_status=any || true)
  if [ -n "$ID" ]; then
    echo "=  esiste gia': /lp/$SLUG/  (ID $ID) - non la ricreo"
  else
    ID=$(wp post create --post_type=page --post_status=publish \
         --post_title="$TITOLO" --post_name="$SLUG" --post_parent="$PARENT" \
         --post_content="" --porcelain)
    echo "+  creata:      /lp/$SLUG/  (ID $ID)"
  fi
  wp post meta update "$ID" _wp_page_template "templates/page-landing-ads.php" >/dev/null
  wp post meta update "$ID" _toa_ads_key "$SLUG" >/dev/null
  echo "   template + _toa_ads_key=$SLUG"
}

crea_landing "marmomac-2026"            "Marmomac 2026 — Ads"
crea_landing "milano-fashion-week-2026" "Milano Fashion Week 2026 — Ads"
crea_landing "salone-nautico-2026"      "Salone Nautico 2026 — Ads"
crea_landing "ttg-rimini-2026"          "TTG Rimini 2026 — Ads"
crea_landing "cibus-tec-2026"           "Cibus Tec 2026 — Ads"
crea_landing "fieracavalli-2026"        "Fieracavalli 2026 — Ads"
crea_landing "eima-2026"                "EIMA 2026 — Ads"
crea_landing "atp-finals-torino-2026"   "ATP Finals Torino 2026 — Ads"

echo
echo "Svuoto le cache..."
wp sg purge 2>&1 | tail -2 || true
echo "Fatto."
REMOTE
