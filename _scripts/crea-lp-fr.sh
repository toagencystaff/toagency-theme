#!/usr/bin/env bash
# crea-lp-fr.sh — crea la traduzione francese WPML di /lp/ e /lp/hostess-eventi/
# TEMA LP-FRANCIA — 2026-10-03
#
# Perche': senza traduzione WPML l'indirizzo /fr/lp/hostess-eventi/ rimbalza sulla pagina italiana.
# Con la traduzione, WPML serve la pagina in francese: il template (page-landing-ads.php) legge
# la lingua da toa_current_lang() e ha gia' tutti i testi FR.
#
# USO:   ./_scripts/crea-lp-fr.sh check    -> SOLA LETTURA: dice cosa esiste, non scrive nulla
#        ./_scripts/crea-lp-fr.sh apply    -> crea quello che manca (idempotente: rilanciabile)
#
# Cosa crea (solo se manca): pagina FR "lp" (vuota, come l'italiana) + pagina FR "hostess-eventi"
# figlia, stesso template, stesse meta (noindex Yoast incluso), _toa_ads_key=hostess-eventi.
# NON tocca le pagine italiane. NON tocca tracciamento.

set -euo pipefail
MODE="${1:-check}"
case "$MODE" in check|apply) ;; *) echo "uso: $0 check|apply"; exit 1;; esac

ssh toagency "MODE=$MODE bash -s" <<'REMOTE'
set -euo pipefail
cd /home/customer/www/toagency.it/public_html

cat > /tmp/lp_fr.php <<'PHP'
<?php
$mode = getenv('MODE') ?: 'check';
echo "MODO: $mode\n";
if (!defined('ICL_LANGUAGE_CODE')) { echo "ERRORE: WPML non attivo sotto wp-cli. STOP.\n"; exit(1); }
echo "Lingua corrente wp-cli: " . ICL_LANGUAGE_CODE . "\n";

function lpfr_tr($id) { $t = apply_filters('wpml_object_id', $id, 'page', false, 'fr'); return $t ? (int)$t : 0; }
function lpfr_state($id) {
    if (!$id) return 'NON ESISTE';
    $p = get_post($id);
    return "ID $id status={$p->post_status} slug={$p->post_name} parent={$p->post_parent} url=" . get_permalink($id);
}
function lpfr_make($it_id, $fr_parent_id, $force_key = '') {
    global $wpdb;
    $it   = get_post($it_id);
    $trid = apply_filters('wpml_element_trid', null, $it_id, 'post_page');
    do_action('wpml_switch_language', 'fr');
    $new = wp_insert_post(array(
        'post_type' => 'page', 'post_status' => 'publish',
        'post_title' => $it->post_title, 'post_name' => $it->post_name,
        'post_parent' => $fr_parent_id, 'post_content' => $it->post_content,
        'menu_order' => $it->menu_order, 'post_author' => $it->post_author,
    ), true);
    if (is_wp_error($new)) { echo "ERRORE wp_insert_post: " . $new->get_error_message() . "\n"; do_action('wpml_switch_language', 'it'); return 0; }
    do_action('wpml_set_element_language_details', array(
        'element_id' => $new, 'element_type' => 'post_page', 'trid' => $trid,
        'language_code' => 'fr', 'source_language_code' => 'it',
    ));
    foreach (get_post_meta($it_id) as $k => $vals) {
        if (preg_match('/^(_edit_|_icl_|_wpml_|_wp_old_)/', $k)) continue;
        delete_post_meta($new, $k);
        foreach ($vals as $v) add_post_meta($new, $k, maybe_unserialize($v));
    }
    if ($force_key) update_post_meta($new, '_toa_ads_key', $force_key);
    $p = get_post($new);
    if ($p->post_name !== $it->post_name) { // WP puo' aver messo -2 per collisione con la pagina italiana
        $wpdb->update($wpdb->posts, array('post_name' => $it->post_name), array('ID' => $new));
        clean_post_cache($new);
    }
    do_action('wpml_switch_language', 'it');
    return $new;
}

$it_parent = get_page_by_path('lp', OBJECT, 'page');
$it_child  = get_page_by_path('lp/hostess-eventi', OBJECT, 'page');
if (!$it_parent || !$it_child) { echo "ERRORE: pagine italiane lp o lp/hostess-eventi non trovate. STOP.\n"; exit(1); }
echo "IT genitore : " . lpfr_state($it_parent->ID) . "\n";
echo "IT figlia   : " . lpfr_state($it_child->ID) . "\n";
$fr_parent = lpfr_tr($it_parent->ID);
$fr_child  = lpfr_tr($it_child->ID);
echo "FR genitore : " . lpfr_state($fr_parent) . "\n";
echo "FR figlia   : " . lpfr_state($fr_child) . "\n";

if ($mode !== 'apply') { echo "\n(check: non ho scritto nulla)\n"; exit(0); }

if (!$fr_parent) { $fr_parent = lpfr_make($it_parent->ID, 0); echo "+ creata FR genitore: " . lpfr_state($fr_parent) . "\n"; }
if ($fr_parent && !$fr_child) { $fr_child = lpfr_make($it_child->ID, $fr_parent, 'hostess-eventi'); echo "+ creata FR figlia: " . lpfr_state($fr_child) . "\n"; }
elseif ($fr_child) { update_post_meta($fr_child, '_toa_ads_key', 'hostess-eventi'); echo "= FR figlia gia' presente: sistemata solo la chiave\n"; }
echo "\nSTATO FINALE\n";
echo "FR genitore : " . lpfr_state($fr_parent) . "\n";
echo "FR figlia   : " . lpfr_state($fr_child) . "\n";
PHP

wp eval-file /tmp/lp_fr.php
rm -f /tmp/lp_fr.php

if [ "$MODE" = "apply" ]; then
  echo; echo "Svuoto le cache..."
  wp sg purge 2>&1 | tail -2 || true
  echo; echo "Verifica HTTP (senza seguire redirect):"
  curl -s -o /dev/null -w "/fr/lp/hostess-eventi/ -> HTTP %{http_code}  redirect=%{redirect_url}\n" https://toagency.it/fr/lp/hostess-eventi/ || true
  curl -s -o /dev/null -w "/lp/hostess-eventi/    -> HTTP %{http_code}\n" https://toagency.it/lp/hostess-eventi/ || true
fi
echo "Fatto."
REMOTE
