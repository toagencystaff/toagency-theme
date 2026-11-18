#!/usr/bin/env bash
# Health check post-deploy. Exit 0 = OK, 1 = KO (trigger rollback).
# NB: niente 'set -e' — un curl che fallisce (es. SSL exit 35 transitorio) NON deve abortire
# lo script e provocare un rollback falso. Ogni curl ritenta per assorbire i transitori.
# --retry-all-errors serve perche' --retry da solo non ritenta sugli errori SSL di connessione.
CURL_RETRY=(--retry 2 --retry-delay 1 --retry-all-errors)
URLS=(
  "https://toagency.it/"
  "https://toagency.it/models/"
  "https://toagency.it/hostess-steward/"
  "https://toagency.it/actors/"
  "https://toagency.it/tnx/"
)
FAIL=0
CHALLENGE=0
for U in "${URLS[@]}"; do
  CODE=$(curl -s "${CURL_RETRY[@]}" -o /dev/null -w "%{http_code}" -A "Mozilla/5.0 (toa-healthcheck)" "$U")
  # 2026-11-18: HTTP 202 = sfida anti-bot di SiteGround (blocca curl dal Mac, NON e' un errore del sito:
  # ha causato un rollback falso). Si riprova dal server stesso; se resta 202 conta come "non verificabile".
  if [[ "$CODE" == "202" ]]; then
    CODE2=$(ssh toagency "curl -s -o /dev/null -w '%{http_code}' -A 'Mozilla/5.0 (toa-healthcheck)' '$U'" 2>/dev/null)
    if [[ "$CODE2" =~ ^(200|301|302)$ ]]; then
      CODE="$CODE2"
    else
      echo "  ⚠️  202 (sfida anti-bot SiteGround, non verificabile da qui)  $U"
      CHALLENGE=$((CHALLENGE+1))
      continue
    fi
  fi
  if [[ "$CODE" =~ ^(200|301|302)$ ]]; then
    echo "  ✅ $CODE  $U"
  else
    echo "  ❌ ${CODE:-ERR}  $U"
    FAIL=$((FAIL+1))
  fi
done
# Se qualche URL era dietro la sfida anti-bot: controllo di sicurezza lato server (WordPress parte col tema attivo?)
if [ "$CHALLENGE" -gt 0 ]; then
  BOOT=$(ssh toagency "cd /home/customer/www/toagency.it/public_html && wp eval 'echo \"BOOT_OK\";' 2>&1 | tail -1" 2>/dev/null)
  if [[ "$BOOT" == *BOOT_OK* ]]; then
    echo "  ✅ WordPress parte col tema attivo (controllo lato server, $CHALLENGE URL dietro anti-bot)"
  else
    echo "  ❌ WordPress NON parte lato server: $BOOT"
    FAIL=$((FAIL+1))
  fi
fi
# Verifica niente fatal PHP visibile sull'home
if [ "$CHALLENGE" -eq 0 ] && curl -s "${CURL_RETRY[@]}" "https://toagency.it/" | grep -qiE "fatal error|parse error|warning:.*on line"; then
  echo "  ❌ PHP error visibile in homepage!"
  FAIL=$((FAIL+1))
fi
exit $((FAIL > 0 ? 1 : 0))
