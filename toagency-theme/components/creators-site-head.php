<?php
/**
 * Component: testata del mini sito TOA Creators (ticket CRM #807, 04/11/2026, chat «TEMA Ticket 807»)
 * Usage: toa_component('creators-site-head', array('tcr_active' => 'home'|'diventa'))
 * Sostituisce components/header.php SOLO nei template creator (sfondo scuro, barra fissa propria).
 * Barra: logo TOAGENCY (-> home del sito nella lingua) + «/ Creators», menu interno, lingue WPML, «← Torna a toagency.it».
 * Pagine del mini sito: opzione 'toa_creators_site' = array('home'|'diventa' => array(lingua => ID)), scritta da _crea_creators_pagine_807.php.
 */
$tcr_lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$tcr_lang = in_array($tcr_lang, array('it','en','fr','es'), true) ? $tcr_lang : 'it';
$tcr_active = isset($tcr_active) ? $tcr_active : 'home';
$tcr_tx = function($a) use ($tcr_lang) { return isset($a[$tcr_lang]) ? $a[$tcr_lang] : $a['it']; };

if (!function_exists('toa_creators_url')) {
    // URL di una pagina del mini sito creator nella lingua chiesta; '' se non esiste o non e' visibile a chi guarda
    function toa_creators_url($key, $lang) {
        $map = get_option('toa_creators_site', array());
        $id = isset($map[$key][$lang]) ? (int) $map[$key][$lang] : 0;
        if (!$id) return '';
        $st = get_post_status($id);
        if ($st === 'publish') return get_permalink($id);
        // bozza vista da chi la sta controllando: ?page_id= non porta la lingua, quindi la si aggiunge (?lang=, toa_current_lang)
        if ($st && current_user_can('edit_post', $id)) return add_query_arg('lang', $lang, get_permalink($id));
        return '';
    }
}
$tcr_home_site = apply_filters('wpml_home_url', untrailingslashit(get_option('home')) . '/');
$tcr_main = toa_creators_url('home', $tcr_lang);
$tcr_pre  = ($tcr_active === 'home') ? '' : $tcr_main;   // nella pagina principale le voci sono ancore
$tcr_menu = array(
    'pacchetti' => array('#pacchetti', array('it'=>'Pacchetti','en'=>'Packages','fr'=>'Formules','es'=>'Paquetes')),
    'servizi'   => array('#servizi',   array('it'=>'Servizi','en'=>'Services','fr'=>'Services','es'=>'Servicios')),
    'vetrina'   => array('#vetrina',   array('it'=>'Creator','en'=>'Creators','fr'=>'Créateurs','es'=>'Creadores')),
    'come'      => array('#come',      array('it'=>'Come funziona','en'=>'How it works','fr'=>'Comment ça marche','es'=>'Cómo funciona')),
);
$tcr_cta = array('it'=>'Chiedi una selezione','en'=>'Get a shortlist','fr'=>'Demander une sélection','es'=>'Pide una selección');
$tcr_dv  = toa_creators_url('diventa', $tcr_lang);

// lingue: solo traduzioni WPML reali della pagina (mai URL costruiti a mano)
$tcr_langs = array();
if (function_exists('icl_get_languages')) {
    $ls = icl_get_languages('skip_missing=1&orderby=code');
    if (is_array($ls)) foreach (array('it','en','fr','es') as $lc) if (isset($ls[$lc])) $tcr_langs[$lc] = $ls[$lc]['url'];
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="tcr-html">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
<meta name="theme-color" content="#0b0b0d">
<style>html.tcr-html,html.tcr-html body{background:#0b0b0d!important;color:#f4f4f2!important}</style>
<?php wp_head(); ?>
</head>
<body <?php body_class('tcr-site'); ?>>
<?php /* 2026-11-05 ticket #789: in anteprima la barra nera del menu globale sopra la barra creator */ if (function_exists('toa_mg_attivo') && toa_mg_attivo()) toa_mg_barra('creator', ($tcr_main ? $tcr_main : '') . '#richiesta'); ?>
<header class="tcr-top" id="tcrTop">
  <div class="tcr-top__in">
    <a class="tcr-crumb" href="<?php echo esc_url($tcr_home_site); ?>" title="<?php echo esc_attr($tcr_tx(array('it'=>'Vai alla home di toagency.it','en'=>'Go to the toagency.it home page','fr'=>"Aller à l'accueil de toagency.it",'es'=>'Ir a la página de inicio de toagency.it'))); ?>">
      <span class="tcr-logo">TO<b>A</b>GENCY</span><span class="tcr-crumb__sep">/</span>
    </a>
    <a class="tcr-crumb__sub" href="<?php echo esc_url($tcr_main ? $tcr_main : $tcr_home_site); ?>">Creators</a>
    <nav class="tcr-nav" aria-label="TOA Creators">
      <?php foreach ($tcr_menu as $k => $m): ?>
      <a href="<?php echo esc_url($tcr_pre . $m[0]); ?>"><?php echo esc_html($tcr_tx($m[1])); ?></a>
      <?php endforeach; ?>
      <?php if ($tcr_dv): ?>
      <a href="<?php echo esc_url($tcr_dv); ?>"<?php echo $tcr_active === 'diventa' ? ' class="on" aria-current="page"' : ''; ?>><?php echo esc_html($tcr_tx(array('it'=>'Diventa creator','en'=>'Become a creator','fr'=>'Devenir créateur','es'=>'Hazte creador'))); ?></a>
      <?php endif; ?>
    </nav>
    <?php if (count($tcr_langs) > 1): ?>
    <div class="tcr-langs">
      <?php foreach ($tcr_langs as $lc => $lu): ?>
        <?php if ($lc === $tcr_lang): ?><span class="on"><?php echo strtoupper($lc); ?></span>
        <?php else: ?><a href="<?php echo esc_url($lu); ?>" hreflang="<?php echo esc_attr($lc); ?>"><?php echo strtoupper($lc); ?></a><?php endif; ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <a class="tcr-btn tcr-btn--p tcr-top__cta" href="<?php echo esc_url($tcr_pre . '#richiesta'); ?>"><?php echo esc_html($tcr_tx($tcr_cta)); ?></a>
    <a class="tcr-home" href="<?php echo esc_url($tcr_home_site); ?>">&larr; <span class="tcr-home__lg"><?php echo esc_html($tcr_tx(array('it'=>'Torna a ','en'=>'Back to ','fr'=>'Retour sur ','es'=>'Volver a '))); ?></span>toagency.it</a>
  </div>
</header>
