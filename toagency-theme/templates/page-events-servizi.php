<?php
/**
 * Template Name: TOA Events - Tutti i servizi
 * Mini sito TOA Events (ticket #811, 03/11/2026, chat TEMA-Events) — IT/EN/FR/ES.
 * I 6 gruppi coi SOLI ruoli del catalogo CRM (components/events-catalog.php). «Scopri» porta alla sottopagina servizio esistente
 * (opzione toa_events_pages, stessi URL di prima), altrimenti al modulo.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };
$tdir = get_template_directory();
wp_enqueue_style('toa-events-site', get_template_directory_uri() . '/assets/css/events-site.css', array(), (string) @filemtime($tdir . '/assets/css/events-site.css'));

$CAT = include $tdir . '/components/events-catalog.php';
$ev_pages = get_option('toa_events_pages', array());
$svc_url = function($k) use ($ev_pages, $lang) {
    return (isset($ev_pages[$k][$lang]) && get_post_status((int) $ev_pages[$k][$lang]) === 'publish') ? get_permalink((int) $ev_pages[$k][$lang]) : '';
};
$t = array(
    'h1'   => array('it'=>"Tutto quello che serve al tuo evento",'en'=>"Everything your event needs",'fr'=>"Tout ce dont votre événement a besoin",'es'=>"Todo lo que necesita tu evento"),
    'lead' => array('it'=>"Accoglienza, bar, intrattenimento, foto e video, tecnica, accessi e trasporti. Un solo referente, un solo preventivo.",
                    'en'=>"Welcome, bar, entertainment, photo and video, technical crew, access and transport. One contact, one quote.",
                    'fr'=>"Accueil, bar, animation, photo et vidéo, technique, accès et transports. Un seul interlocuteur, un seul devis.",
                    'es'=>"Recepción, bar, animación, foto y vídeo, técnica, accesos y transporte. Un solo interlocutor, un solo presupuesto."),
    'more' => array('it'=>"Scopri e chiedi un preventivo",'en'=>"Find out more and get a quote",'fr'=>"En savoir plus et demander un devis",'es'=>"Descubre y pide presupuesto"),
    'ask'  => array('it'=>"Chiedi un preventivo",'en'=>"Get a quote",'fr'=>"Demander un devis",'es'=>"Pide presupuesto"),
    'no_h' => array('it'=>"Non trovi quello che cerchi?",'en'=>"Can't find what you need?",'fr'=>"Vous ne trouvez pas ce qu'il vous faut ?",'es'=>"¿No encuentras lo que buscas?"),
    'no_p' => array('it'=>"Scrivici: ti diciamo come possiamo aiutarti.",'en'=>"Write to us and we'll tell you how we can help.",'fr'=>"Écrivez-nous : nous vous dirons comment vous aider.",'es'=>"Escríbenos y te diremos cómo podemos ayudarte."),
    'legal'=> array('it'=>"Mansioni tecniche e di controllo accessi solo con personale in regola con i requisiti di legge. Foto illustrative.",
                    'en'=>"Technical and access-control roles only with staff who meet the legal requirements. Illustrative photos.",
                    'fr'=>"Missions techniques et de contrôle d'accès uniquement avec du personnel en règle avec les exigences légales. Photos d'illustration.",
                    'es'=>"Tareas técnicas y de control de accesos solo con personal que cumple los requisitos legales. Fotos ilustrativas."),
);
$sdir = $tdir . '/assets/staff/';

toa_component('events-site-head', array('tev_active' => 'servizi', 'tev_cta_url' => '#preventivo'));
?>
<main class="tev-servizi">
  <section class="tev-sec tev-sec--grey tev-pagehd">
    <div class="tev-wrap">
      <h1 class="tev-h1 tev-h1--xl"><?php echo esc_html($_t($t['h1'])); ?></h1>
      <p class="tev-lead tev-lead--hero"><?php echo esc_html($_t($t['lead'])); ?></p>
    </div>
  </section>
  <section class="tev-sec">
    <div class="tev-wrap tev-svcs">
      <?php $i = 0; foreach ($CAT['groups'] as $gk => $g): $u = $svc_url($g['svc']); ?>
      <article class="tev-svc" id="<?php echo esc_attr($gk); ?>">
        <?php if ($g['p'] && file_exists($sdir . $g['p'] . '.jpg')): ?>
        <img class="tev-svc__ph" src="<?php echo esc_url(get_template_directory_uri() . '/assets/staff/' . $g['p'] . '.jpg'); ?>" alt="<?php echo esc_attr($_t($g['t'])); ?>" <?php echo $i > 1 ? 'loading="lazy" ' : ''; ?>decoding="async" width="600" height="338">
        <?php endif; ?>
        <div class="tev-svc__b">
          <h2 class="tev-svc__t"><?php echo esc_html($_t($g['t'])); ?></h2>
          <p class="tev-svc__d"><?php echo esc_html($_t($g['d'])); ?></p>
          <ul class="tev-svc__r">
            <?php foreach ($g['r'] as $rc): if (empty($CAT['roles'][$rc])) continue; ?>
            <li title="<?php echo esc_attr($_t($CAT['roles'][$rc]['d'])); ?>"><?php echo esc_html($_t($CAT['roles'][$rc]['n'])); ?></li>
            <?php endforeach; ?>
          </ul>
          <a class="tev-svc__a" href="<?php echo esc_url($u ? $u : '#preventivo'); ?>"><?php echo esc_html($_t($t['more'])); ?> &rarr;</a>
        </div>
      </article>
      <?php $i++; endforeach; ?>
    </div>
  </section>
  <section class="tev-cta-band">
    <div class="tev-wrap tev-cta-band__in">
      <div><h2 class="tev-h2 tev-h2--inv"><?php echo esc_html($_t($t['no_h'])); ?></h2><p><?php echo esc_html($_t($t['no_p'])); ?></p></div>
      <a class="tev-btn tev-btn--lime tev-btn--lg" href="#preventivo"><?php echo esc_html($_t($t['ask'])); ?></a>
    </div>
    <div class="tev-wrap tev-cta-band__legal"><?php echo esc_html($_t($t['legal'])); ?></div>
  </section>
</main>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events Tutti i servizi', 'extra' => true)); ?>

<div class="tev-bar tev-bar--cta" id="tevBar"><a class="tev-btn tev-btn--lime" href="#preventivo"><?php echo esc_html($_t($t['ask'])); ?></a></div>
<script>
(function(){ var bar=document.getElementById('tevBar'), form=document.getElementById('preventivo');
  if (bar && form && 'IntersectionObserver' in window) new IntersectionObserver(function(en){ en.forEach(function(x){ bar.classList.toggle('hide', x.isIntersecting); }); }, { threshold: 0 }).observe(form); })();
</script>
<?php toa_component('events-site-foot'); ?>
