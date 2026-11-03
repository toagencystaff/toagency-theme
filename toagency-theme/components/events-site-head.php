<?php
/**
 * Component: testata del mini sito TOA Events (ticket #811, 03/11/2026, chat TEMA-Events)
 * Usage: toa_component('events-site-head', array('tev_active' => 'staff'))
 * Sostituisce components/header.php SOLO nei template del mini sito (base bianca, menu proprio).
 * Pagine del mini sito: opzione 'toa_events_site' = array(chiave => array(lingua => ID pagina)),
 * scritta dagli script di creazione pagine. Se una pagina non esiste ancora: ripiego sull'hub /events/.
 */
$tev_lang   = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$tev_lang   = in_array($tev_lang, array('it','en','fr','es'), true) ? $tev_lang : 'it';
$tev_active = isset($tev_active) ? $tev_active : '';
$tev_tx = function($a) use ($tev_lang) { return isset($a[$tev_lang]) ? $a[$tev_lang] : $a['it']; };

if (!function_exists('toa_events_site_url')) {
    // URL di una pagina del mini sito nella lingua corrente; '' se non pubblicata
    function toa_events_site_url($key, $lang) {
        $map = get_option('toa_events_site', array());
        if ($key === 'hub') {
            $hub = array('it' => 275542, 'en' => 275543, 'fr' => 275544, 'es' => 275545);
            $id = isset($hub[$lang]) ? $hub[$lang] : $hub['it'];
        } else {
            $id = isset($map[$key][$lang]) ? (int) $map[$key][$lang] : 0;
        }
        if (!$id) return '';
        $st = get_post_status($id);
        if ($st === 'publish' || ($st && current_user_can('edit_post', $id))) return get_permalink($id);
        return '';
    }
}
$tev_hub = toa_events_site_url('hub', $tev_lang);
$tev_url = function($key, $fallback) use ($tev_lang) { $u = toa_events_site_url($key, $tev_lang); return $u ? $u : $fallback; };

// stessi URL di registrazione di page-collabora.php / b2b-intent-gate.php
$tev_reg = array('it'=>'/registrati-talent/','en'=>'/en/register-talent/','fr'=>'/fr/inscription-talent/','es'=>'/es/registrate-como-talent/');
$tev_menu = array(
    'staff'   => array('url' => $tev_url('staff', $tev_hub . '#servizi'),
                       'n' => array('it'=>'Staff e prezzi','en'=>'Staff & prices','fr'=>'Personnel et tarifs','es'=>'Personal y precios')),
    'servizi' => array('url' => $tev_url('servizi', $tev_hub . '#servizi'),
                       'n' => array('it'=>'Tutti i servizi','en'=>'All services','fr'=>'Tous les services','es'=>'Todos los servicios')),
    'come'    => array('url' => $tev_hub . '#come-funziona',
                       'n' => array('it'=>'Come funziona','en'=>'How it works','fr'=>'Comment ça marche','es'=>'Cómo funciona')),
    'lavora'  => array('url' => $tev_url('lavora', untrailingslashit(get_option('home')) . $tev_reg[$tev_lang]),
                       'n' => array('it'=>'Lavora con noi','en'=>'Work with us','fr'=>'Travailler avec nous','es'=>'Trabaja con nosotros')),
);
$tev_cta_n   = array('it'=>'Richiedi una selezione','en'=>'Request a shortlist','fr'=>'Demander une sélection','es'=>'Pide una selección');
$tev_cta_url = isset($tev_cta_url) && $tev_cta_url ? $tev_cta_url : $tev_hub . '#preventivo';

// lingue: solo traduzioni WPML reali della pagina (mai URL costruiti a mano)
$tev_langs = array();
if (function_exists('icl_get_languages')) {
    $ls = icl_get_languages('skip_missing=1&orderby=code');
    if (is_array($ls)) {
        foreach (array('it','en','fr','es') as $lc) {
            if (isset($ls[$lc])) $tev_langs[$lc] = $ls[$lc]['url'];
        }
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="tev-html">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<meta name="theme-color" content="#FFFFFF">
<style>html.tev-html,html.tev-html body{background:#FFFFFF!important;color:#08080A!important}</style>
<?php wp_head(); ?>
</head>
<body <?php body_class('tev-site'); ?>>
<header class="tev-top" id="tevTop">
  <div class="tev-top__in">
    <a class="tev-logo" href="<?php echo esc_url($tev_hub); ?>" aria-label="TOA Events">
      <span class="tev-logo__toa">TOA</span><span class="tev-logo__ev">EVENTS</span>
    </a>
    <nav class="tev-nav" aria-label="TOA Events">
      <?php foreach ($tev_menu as $k => $m): ?>
      <a href="<?php echo esc_url($m['url']); ?>"<?php echo $k === $tev_active ? ' class="on" aria-current="page"' : ''; ?>><?php echo esc_html($tev_tx($m['n'])); ?></a>
      <?php endforeach; ?>
    </nav>
    <div class="tev-top__r">
      <?php if (count($tev_langs) > 1): ?>
      <div class="tev-langs">
        <?php foreach ($tev_langs as $lc => $lu): ?>
          <?php if ($lc === $tev_lang): ?><span class="on"><?php echo strtoupper($lc); ?></span>
          <?php else: ?><a href="<?php echo esc_url($lu); ?>" hreflang="<?php echo esc_attr($lc); ?>"><?php echo strtoupper($lc); ?></a><?php endif; ?>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
      <a class="tev-btn tev-btn--dark tev-top__cta" href="<?php echo esc_url($tev_cta_url); ?>"><?php echo esc_html($tev_tx($tev_cta_n)); ?></a>
      <button type="button" class="tev-burger" id="tevBurger" aria-label="Menu" aria-expanded="false" aria-controls="tevMenu">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </div>
  <div class="tev-menu" id="tevMenu" hidden>
    <?php foreach ($tev_menu as $k => $m): ?>
    <a href="<?php echo esc_url($m['url']); ?>"<?php echo $k === $tev_active ? ' class="on"' : ''; ?>><?php echo esc_html($tev_tx($m['n'])); ?></a>
    <?php endforeach; ?>
    <a class="tev-btn tev-btn--lime" href="<?php echo esc_url($tev_cta_url); ?>"><?php echo esc_html($tev_tx($tev_cta_n)); ?></a>
  </div>
</header>
