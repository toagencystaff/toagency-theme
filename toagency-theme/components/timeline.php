<?php
/**
 * Component: Timeline storia TOAgency
 * Usage:
 *   toa_component('timeline')                      → versione intera (5 milestone), per /about/
 *   toa_component('timeline', ['compact' => true]) → 3 milestone chiave + link a /about/, per home
 *
 * i18n via _t_raw() (helper globale in functions.php). Scroll-reveal via IntersectionObserver.
 */
$compact = isset($compact) ? (bool)$compact : false;

$milestones = array(
    '2009' => array('it'=>'Fondazione dell\'agenzia a Torino, primi casting per eventi locali','en'=>'Agency founded in Turin, first castings for local events','fr'=>'Fondation de l\'agence &agrave; Turin, premiers castings pour &eacute;v&eacute;nements locaux','es'=>'Fundaci&oacute;n de la agencia en Tur&iacute;n, primeros castings para eventos locales'),
    '2015' => array('it'=>'Espansione nazionale, apertura collaborazioni Milano e Roma','en'=>'National expansion, partnerships opened in Milan and Rome','fr'=>'Expansion nationale, ouverture de collaborations &agrave; Milan et Rome','es'=>'Expansi&oacute;n nacional, apertura de colaboraciones en Mil&aacute;n y Roma'),
    '2021' => array('it'=>'Apertura sede operativa a Parigi, espansione internazionale','en'=>'Paris office opened, international expansion','fr'=>'Ouverture du bureau de Paris, expansion internationale','es'=>'Apertura de la oficina de Par&iacute;s, expansi&oacute;n internacional'),
    '2023' => array('it'=>'Lancio piattaforma digitale e servizi produzione e-commerce','en'=>'Digital platform launch and e-commerce production services','fr'=>'Lancement de la plateforme num&eacute;rique et services de production e-commerce','es'=>'Lanzamiento de la plataforma digital y servicios de producci&oacute;n e-commerce'),
    '2024' => array('it'=>'Apertura UK e Spagna, oltre 20.000 professionisti nel network','en'=>'UK and Spain expansion, over 20,000 professionals in the network','fr'=>'Ouverture UK et Espagne, plus de 20 000 professionnels dans le r&eacute;seau','es'=>'Apertura en UK y Espa&ntilde;a, m&aacute;s de 20.000 profesionales en la red'),
);

$keys = $compact ? array('2009','2021','2024') : array_keys($milestones);

$eyebrow = array('it'=>'La nostra storia','en'=>'Our story','fr'=>'Notre histoire','es'=>'Nuestra historia');
$heading = array('it'=>'Dal 2009, cresciamo con i nostri talenti','en'=>'Since 2009, growing with our talents','fr'=>'Depuis 2009, nous grandissons avec nos talents','es'=>'Desde 2009, crecemos con nuestros talentos');
$cta = array('it'=>'La nostra storia completa','en'=>'Our full story','fr'=>'Notre histoire compl&egrave;te','es'=>'Nuestra historia completa');
?>
<section class="why-section timeline-section">
  <div class="container">
    <div class="section-eyebrow"><?php echo _t_raw($eyebrow); ?></div>
    <h2 class="section-heading"><?php echo _t_raw($heading); ?></h2>
    <div class="timeline">
      <?php foreach ($keys as $year) : ?>
      <div class="timeline-item timeline-reveal"><div class="timeline-year"><?php echo esc_html($year); ?></div><p><?php echo _t_raw($milestones[$year]); ?></p></div>
      <?php endforeach; ?>
    </div>
    <?php if ($compact) : ?>
    <a href="<?php echo home_url('/about/'); ?>" class="timeline-cta"><?php echo _t_raw($cta); ?> &rarr;</a>
    <?php endif; ?>
  </div>
</section>
<script>
(function(){
  var items = document.querySelectorAll('.timeline-reveal');
  if (!items.length) return;
  if (!('IntersectionObserver' in window)) {
    items.forEach(function(i){ i.classList.add('is-visible'); });
    return;
  }
  var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if (e.isIntersecting) { e.target.classList.add('is-visible'); obs.unobserve(e.target); }
    });
  }, { threshold: 0.2 });
  items.forEach(function(i){ obs.observe(i); });
})();
</script>
