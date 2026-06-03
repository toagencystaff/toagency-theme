#!/usr/bin/env bash
# Crea tar.gz del tema prod in ~/backups-frontend (sul server).
# Stampa SOLO il filename su stdout (per pipe), log su stderr.
set -e
LABEL="${1:-pre-deploy}"
TS=$(date +%Y%m%d-%H%M%S)
NAME="toa-theme-${LABEL}-${TS}.tar.gz"
ssh toagency "mkdir -p ~/backups-frontend && cd /home/customer/www/toagency.it/public_html/wp-content/themes && tar czf ~/backups-frontend/$NAME toagency-theme/ && ls -lh ~/backups-frontend/$NAME" 1>&2
echo "$NAME"
