<?php
/**
 * Component: piede del mini sito TOA Events (ticket #811, 03/11/2026, chat TEMA-Events)
 * Usage: toa_component('events-site-foot', array('tev_wa' => false))
 * Link al resto di TOAgency + dati legali + wp_footer. Va usato insieme a events-site-head.
 * tev_wa=false: niente bottone WhatsApp fisso (es. Staff e prezzi, dove in basso c'e' la barra prezzo).
 */
$tevf_lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$tevf_lang = in_array($tevf_lang, array('it','en','fr','es'), true) ? $tevf_lang : 'it';
$tevf_tx = function($a) use ($tevf_lang) { return isset($a[$tevf_lang]) ? $a[$tevf_lang] : $a['it']; };
$tevf_u  = function($path) use ($tevf_lang) { return apply_filters('wpml_permalink', untrailingslashit(get_option('home')) . $path, $tevf_lang); };
$tevf_wa = isset($tev_wa) ? (bool) $tev_wa : true;

$tevf_links = array(
    array('/',                 array('it'=>'TOAgency','en'=>'TOAgency','fr'=>'TOAgency','es'=>'TOAgency')),
    array('/models/',          array('it'=>'Modelli','en'=>'Models','fr'=>'Mannequins','es'=>'Modelos')),
    array('/actors/',          array('it'=>'Attori','en'=>'Actors','fr'=>'Acteurs','es'=>'Actores')),
    array('/talent-database/', array('it'=>'Talent database','en'=>'Talent database','fr'=>'Base de talents','es'=>'Base de talentos')),
    array('/casting/',         array('it'=>'Casting','en'=>'Casting','fr'=>'Casting','es'=>'Casting')),
    array('/contact-us/',      array('it'=>'Contatti','en'=>'Contact','fr'=>'Contact','es'=>'Contacto')),
);
$tevf_privacy = function_exists('toa_privacy_url') ? toa_privacy_url() : 'https://toagency.it/privacy-policy-3/';
$tevf_terms   = array('it'=>'Termini e condizioni','en'=>'Terms and conditions','fr'=>'Conditions générales','es'=>'Términos y condiciones');
?>
<footer class="tev-foot">
  <div class="tev-foot__in">
    <div class="tev-foot__brand">
      <span class="tev-logo"><span class="tev-logo__toa">TOA</span><span class="tev-logo__ev">EVENTS</span></span>
      <p><?php echo esc_html($tevf_tx(array(
          'it'=>'TOA Events è la divisione eventi di TOAgency.',
          'en'=>'TOA Events is the events division of TOAgency.',
          'fr'=>'TOA Events est la division événementielle de TOAgency.',
          'es'=>'TOA Events es la división de eventos de TOAgency.'))); ?></p>
    </div>
    <nav class="tev-foot__links" aria-label="TOAgency">
      <?php foreach ($tevf_links as $l): ?>
      <a href="<?php echo esc_url($tevf_u($l[0])); ?>"><?php echo esc_html($tevf_tx($l[1])); ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
  <div class="tev-foot__legal">
    <span>TOAGENCY &egrave; un marchio di TOA di Leonardi Marco &mdash; Via Cavour 21, 10123 Torino (Italy) &mdash; P.IVA 11800210012</span>
    <span>
      <a href="<?php echo esc_url($tevf_privacy); ?>" target="_blank" rel="noopener">Privacy Policy</a> ·
      <a href="<?php echo esc_url(apply_filters('wpml_permalink', 'https://toagency.it/cookie-policy-ue/', $tevf_lang)); ?>" target="_blank" rel="noopener">Cookie Policy</a> ·
      <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>"><?php echo esc_html($tevf_tx($tevf_terms)); ?></a> ·
      <a href="#" onclick="if(window.toaOpenReportModal){toaOpenReportModal();}return false;"><?php echo esc_html($tevf_tx(array('it'=>'Segnala un problema','en'=>'Report a problem','fr'=>'Signaler un problème','es'=>'Informar de un problema'))); ?></a>
    </span>
  </div>
</footer>
<script>
(function(){
  var b=document.getElementById('tevBurger'), m=document.getElementById('tevMenu');
  if(!b||!m) return;
  b.addEventListener('click',function(){ var o=m.hasAttribute('hidden'); if(o){m.removeAttribute('hidden');}else{m.setAttribute('hidden','');} b.setAttribute('aria-expanded',o?'true':'false'); });
  m.addEventListener('click',function(e){ if(e.target.tagName==='A'){ m.setAttribute('hidden',''); b.setAttribute('aria-expanded','false'); } });
})();
</script>
<?php wp_footer(); ?>
<?php if ($tevf_wa) toa_component('whatsapp-button'); ?>
<?php toa_component('report-problem-modal'); ?>
</body>
</html>
