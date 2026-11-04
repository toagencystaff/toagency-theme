<?php
/**
 * Component: piede del mini sito TOA Creators (ticket CRM #807, 04/11/2026, chat «TEMA Ticket 807»)
 * Usage: toa_component('creators-site-foot')   — va usato insieme a creators-site-head.
 * Secondo ritorno alla home di toagency.it (richiesta di Marco), link al resto del sito, dati legali, wp_footer.
 */
$tcf_lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$tcf_lang = in_array($tcf_lang, array('it','en','fr','es'), true) ? $tcf_lang : 'it';
$tcf_tx = function($a) use ($tcf_lang) { return isset($a[$tcf_lang]) ? $a[$tcf_lang] : $a['it']; };
$tcf_u  = function($path) use ($tcf_lang) { return apply_filters('wpml_permalink', untrailingslashit(get_option('home')) . $path, $tcf_lang); };
$tcf_home = apply_filters('wpml_home_url', untrailingslashit(get_option('home')) . '/');
$tcf_links = array(
    array('/models/',          array('it'=>'Modelli','en'=>'Models','fr'=>'Mannequins','es'=>'Modelos')),
    array('/actors/',          array('it'=>'Attori','en'=>'Actors','fr'=>'Acteurs','es'=>'Actores')),
    array('/hostess-steward/', array('it'=>'Hostess ed eventi','en'=>'Hostesses & events','fr'=>'Hôtesses et événements','es'=>'Azafatas y eventos')),
    array('/talent-database/', array('it'=>'Talent database','en'=>'Talent database','fr'=>'Base de talents','es'=>'Base de talentos')),
    array('/casting/',         array('it'=>'Casting','en'=>'Casting','fr'=>'Casting','es'=>'Casting')),
    array('/contact-us/',      array('it'=>'Contatti','en'=>'Contact','fr'=>'Contact','es'=>'Contacto')),
);
$tcf_privacy = function_exists('toa_privacy_url') ? toa_privacy_url() : 'https://toagency.it/privacy-policy-3/';
?>
<?php // lingue anche qui: sul telefono la barra in alto non ha spazio per le lingue
$tcf_langs = array();
if (function_exists('icl_get_languages')) { $ls = icl_get_languages('skip_missing=1&orderby=code'); if (is_array($ls)) foreach (array('it','en','fr','es') as $lc) if (isset($ls[$lc])) $tcf_langs[$lc] = $ls[$lc]['url']; }
if (count($tcf_langs) > 1): ?>
<div class="tcr-langs tcr-langs--foot">
  <?php foreach ($tcf_langs as $lc => $lu): ?><?php if ($lc === $tcf_lang): ?><span class="on"><?php echo strtoupper($lc); ?></span><?php else: ?><a href="<?php echo esc_url($lu); ?>" hreflang="<?php echo esc_attr($lc); ?>"><?php echo strtoupper($lc); ?></a><?php endif; ?><?php endforeach; ?>
</div>
<?php endif; ?>
<div class="tcr-back"><a class="tcr-home" href="<?php echo esc_url($tcf_home); ?>">&larr; <?php echo esc_html($tcf_tx(array('it'=>'Torna alla home di toagency.it','en'=>'Back to the toagency.it home page','fr'=>"Retour à l'accueil de toagency.it",'es'=>'Volver a la página de inicio de toagency.it'))); ?></a></div>
<footer class="tcr-foot">
  <div class="tcr-foot__in">
    <div class="tcr-foot__brand">
      <span class="tcr-logo">TO<b>A</b>GENCY <small>/ Creators</small></span>
      <p><?php echo esc_html($tcf_tx(array(
          'it'=>'TOA Creators è il servizio creator di TOAgency: dal 2009 talent, crew e produzioni in Italia e in Europa.',
          'en'=>"TOA Creators is TOAgency's creator service. Since 2009 we've been connecting brands with talent and crews across Italy and Europe.",
          'fr'=>"TOA Creators est le service créateurs de TOAgency. Depuis 2009, nous mettons en relation marques, talents et équipes en Italie et en Europe.",
          'es'=>'TOA Creators es el servicio de creadores de TOAgency. Desde 2009 conectamos marcas con talentos y equipos en Italia y en Europa.'))); ?></p>
    </div>
    <nav class="tcr-foot__links" aria-label="TOAgency">
      <?php foreach ($tcf_links as $l): ?><a href="<?php echo esc_url($tcf_u($l[0])); ?>"><?php echo esc_html($tcf_tx($l[1])); ?></a><?php endforeach; ?>
    </nav>
  </div>
  <div class="tcr-foot__legal">
    <span>TOAGENCY &egrave; un marchio di TOA di Leonardi Marco &mdash; Via Cavour 21, 10123 Torino (Italy) &mdash; P.IVA 11800210012</span>
    <span>
      <a href="<?php echo esc_url($tcf_privacy); ?>" target="_blank" rel="noopener">Privacy Policy</a> ·
      <a href="<?php echo esc_url(apply_filters('wpml_permalink', 'https://toagency.it/cookie-policy-ue/', $tcf_lang)); ?>" target="_blank" rel="noopener">Cookie Policy</a> ·
      <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>"><?php echo esc_html($tcf_tx(array('it'=>'Termini e condizioni','en'=>'Terms and conditions','fr'=>'Conditions générales','es'=>'Términos y condiciones'))); ?></a> ·
      <a href="#" onclick="if(window.toaOpenReportModal){toaOpenReportModal();}return false;"><?php echo esc_html($tcf_tx(array('it'=>'Segnala un problema','en'=>'Report a problem','fr'=>'Signaler un problème','es'=>'Informar de un problema'))); ?></a>
    </span>
  </div>
</footer>
<?php wp_footer(); ?>
<?php toa_component('report-problem-modal'); ?>
</body>
</html>
