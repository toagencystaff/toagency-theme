<?php
/**
 * Short-link dei filtri — chat TEMA LINK-FILTRI-CORTI, 25/08/2026
 *
 * PROBLEMA: su /talent-database/ un filtro con 13 province produce un URL da ~380
 * caratteri, impresentabile da mandare a un cliente via WhatsApp o email.
 *
 * SOLUZIONE: la querystring viene salvata in una option e sostituita da un codice corto:
 *   https://toagency.it/talent-database/?k=a7b3k9d   (~45 caratteri)
 * Chi apre il link viene rediretto (302) all'URL completo, quindi talent-database-v76.js
 * legge i filtri dall'URL esattamente come ha sempre fatto: ZERO modifiche alla logica
 * dei filtri, che è la parte fragile.
 *
 * SCELTE:
 * - Codice DETERMINISTICO (hash della querystring): lo stesso filtro dà sempre lo stesso
 *   codice, quindi cliccare dieci volte "Copia link" non crea dieci righe nel database.
 * - autoload='no' sulle option: non vengono caricate a ogni pagina del sito, peso zero.
 * - Nessun dato personale salvato: solo criteri di ricerca (province, taglie, altezza...).
 */

if (!defined('ABSPATH')) exit;

/** Prefisso delle option. Cambiarlo invalida tutti i link già inviati: non farlo. */
function toa_sl_prefix() {
    return 'toa_sl_';
}

/** Codice corto e stabile a partire da una querystring. */
function toa_sl_code($query) {
    return substr(md5('toa-sl|' . $query), 0, 7);
}

/** Salva la querystring (se non c'è già) e restituisce il codice. */
function toa_sl_save($query) {
    $code = toa_sl_code($query);
    $key  = toa_sl_prefix() . $code;
    if (get_option($key) === false) {
        add_option($key, $query, '', 'no');
    }
    return $code;
}

/** I soli template su cui lo short-link è attivo. */
function toa_sl_templates() {
    return array(
        'templates/page-talent-database.php',
        'templates/page-crew-database.php',
    );
}

/**
 * Endpoint AJAX: riceve la querystring, restituisce il codice corto.
 * Aperto anche ai non loggati perché la pagina è pubblica.
 */
function toa_sl_ajax() {
    $query = isset($_POST['q']) ? wp_unslash($_POST['q']) : '';
    $query = ltrim($query, '?');
    if ($query === '')            wp_send_json_error('empty');
    if (strlen($query) > 2000)    wp_send_json_error('too_long'); // paracadute anti-abuso
    wp_send_json_success(array('code' => toa_sl_save($query)));
}
add_action('wp_ajax_toa_shortlink', 'toa_sl_ajax');
add_action('wp_ajax_nopriv_toa_shortlink', 'toa_sl_ajax');

/**
 * ?k=CODICE → redirect 302 all'URL completo con tutti i filtri.
 * Se il codice non esiste la pagina si apre normalmente, senza filtri: mai un errore
 * in faccia al cliente.
 */
function toa_sl_redirect() {
    if (is_admin() || empty($_GET['k'])) return;

    $ok = false;
    foreach (toa_sl_templates() as $tpl) {
        if (is_page_template($tpl)) { $ok = true; break; }
    }
    if (!$ok) return;

    $code = preg_replace('/[^a-f0-9]/', '', (string) $_GET['k']);
    if ($code === '') return;

    $query = get_option(toa_sl_prefix() . $code);
    if ($query === false || $query === '') return;

    $path = strtok($_SERVER['REQUEST_URI'], '?');
    wp_safe_redirect($path . '?' . $query, 302);
    exit;
}
add_action('template_redirect', 'toa_sl_redirect');
