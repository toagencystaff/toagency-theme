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
for U in "${URLS[@]}"; do
  CODE=$(curl -s "${CURL_RETRY[@]}" -o /dev/null -w "%{http_code}" -A "Mozilla/5.0 (toa-healthcheck)" "$U")
  if [[ "$CODE" =~ ^(200|301|302)$ ]]; then
    echo "  ✅ $CODE  $U"
  else
    echo "  ❌ ${CODE:-ERR}  $U"
    FAIL=$((FAIL+1))
  fi
done
# Verifica niente fatal PHP visibile sull'home
if curl -s "${CURL_RETRY[@]}" "https://toagency.it/" | grep -qiE "fatal error|parse error|warning:.*on line"; then
  echo "  ❌ PHP error visibile in homepage!"
  FAIL=$((FAIL+1))
fi
exit $((FAIL > 0 ? 1 : 0))
