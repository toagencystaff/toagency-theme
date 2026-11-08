<?php
/**
 * IndexNow — TOAgency
 * NUOVO 2026-11-08 — chat TEMA GEO AI
 *
 * Avvisa Bing (e gli altri motori IndexNow: Yandex, Seznam, Naver) appena una pagina o un articolo
 * viene pubblicato o aggiornato. ChatGPT cerca sul web tramite l'indice di Bing: senza questo
 * avviso le pagine nuove (casting, città TOA Events, creator) arrivano su Bing/ChatGPT dopo giorni.
 *
 * - /85a9804bdf1ffa7161a39859aaca8ca0.txt  → file chiave richiesto dal protocollo (servito virtualmente, come llms.txt)
 * - publish/aggiornamento di post e pagine → URL messo in coda (opzione toa_indexnow_coda)
 * - un evento WP-Cron 2 minuti dopo manda la coda in un'unica chiamata (max 1.000 URL)
 * - esito dell'ultimo invio in opzione toa_indexnow_ultimo (data, URL, codice HTTP)
 * Spegnere: togliere il require in functions.php.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'TOA_INDEXNOW_KEY', '85a9804bdf1ffa7161a39859aaca8ca0' );

// File chiave
add_action( 'init', function () {
	if ( trim( (string) parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) !== TOA_INDEXNOW_KEY . '.txt' ) return;
	header( 'Content-Type: text/plain; charset=utf-8' );
	echo TOA_INDEXNOW_KEY;
	exit;
}, 1 );

// Metti in coda l'URL quando un post/pagina pubblico viene pubblicato o aggiornato
add_action( 'transition_post_status', function ( $new, $old, $post ) {
	if ( $new !== 'publish' ) return;
	if ( ! in_array( $post->post_type, [ 'post', 'page' ], true ) ) return;
	if ( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) ) return;
	if ( ! empty( $post->post_password ) ) return;
	$url = get_permalink( $post );
	if ( ! $url || strpos( $url, home_url() ) !== 0 ) return;
	$coda = get_option( 'toa_indexnow_coda', [] );
	if ( ! is_array( $coda ) ) $coda = [];
	$coda[ $url ] = 1;
	update_option( 'toa_indexnow_coda', $coda, false );
	if ( ! wp_next_scheduled( 'toa_indexnow_invia' ) ) {
		wp_schedule_single_event( time() + 120, 'toa_indexnow_invia' );
	}
}, 20, 3 );

// Invio della coda
add_action( 'toa_indexnow_invia', function () {
	$coda = get_option( 'toa_indexnow_coda', [] );
	if ( ! is_array( $coda ) || ! $coda ) return;
	$urls = array_slice( array_keys( $coda ), 0, 1000 );
	$host = parse_url( home_url(), PHP_URL_HOST );
	$res  = wp_remote_post( 'https://api.indexnow.org/indexnow', [
		'timeout' => 10,
		'headers' => [ 'Content-Type' => 'application/json; charset=utf-8' ],
		'body'    => wp_json_encode( [
			'host'        => $host,
			'key'         => TOA_INDEXNOW_KEY,
			'keyLocation' => home_url( '/' . TOA_INDEXNOW_KEY . '.txt' ),
			'urlList'     => $urls,
		] ),
	] );
	$code = is_wp_error( $res ) ? $res->get_error_message() : wp_remote_retrieve_response_code( $res );
	// 200/202 = ricevuto: svuoto la coda. Errore: la tengo e riprovo al prossimo publish.
	if ( in_array( $code, [ 200, 202 ], true ) ) {
		update_option( 'toa_indexnow_coda', array_slice( $coda, 1000, null, true ), false );
	}
	update_option( 'toa_indexnow_ultimo', [
		'quando' => current_time( 'mysql' ),
		'url'    => count( $urls ),
		'esempio'=> $urls[0],
		'esito'  => $code,
	], false );
} );
