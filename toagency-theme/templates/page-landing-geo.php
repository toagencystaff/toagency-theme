<?php
/**
 * Template Name: Landing Geo/Servizio
 * Template Post Type: page
 *
 * Template riutilizzabile per pagine SEO geo+servizio.
 * Contenuto unico: WP title (→ H1) + the_content() + meta custom.
 * Meta campi: _toa_subtitle, _toa_service_type, _toa_local_events
 * FIX 2026-05-30 marco — landing-geo
 *
 * NB tema: header/footer via toa_component() (NON get_header/get_footer — non esistono header.php/footer.php root).
 *     _ht() definita qui (guardata) perché è locale a page-home.php, non globale → serve anche a form-b2b-inline incluso sotto.
 */

// _ht() globale-safe per questo template + i componenti inclusi (es. form-b2b-inline)
if (!function_exists('_ht')) {
    function _ht($strings) {
        $l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
        if (!in_array($l, ['it', 'en', 'fr', 'es'])) $l = 'it';
        return esc_html(isset($strings[$l]) ? $strings[$l] : $strings['it']);
    }
}
$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';

$post_id      = get_queried_object_id();
$subtitle     = get_post_meta($post_id, '_toa_subtitle',     true) ?: '';
$service_type = get_post_meta($post_id, '_toa_service_type', true) ?: '';
$local_events = get_post_meta($post_id, '_toa_local_events', true) ?: '';
$events_arr   = $local_events ? array_filter(array_map('trim', explode(',', $local_events))) : [];

toa_component('header');
if (have_posts()) the_post(); // setup postdata per the_title()/the_content()
?>

<main class="toa-landing-geo">

  <!-- HERO -->
  <section class="toa-landing-hero">
    <div class="toa-landing-hero__inner toa-container">
      <h1 class="toa-landing-hero__h1"><?php the_title(); ?></h1>
      <?php if ($subtitle): ?>
        <p class="toa-landing-hero__sub"><?php echo esc_html($subtitle); ?></p>
      <?php endif; ?>
      <a href="#toa-form-anchor" class="toa-btn toa-btn--cta">
        <?php echo _ht(['it'=>'Richiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demandez un devis gratuit','es'=>'Solicita presupuesto gratuito']); ?>
      </a>
    </div>
  </section>

  <!-- TRUST BAR -->
  <section class="toa-landing-trust">
    <div class="toa-container toa-landing-trust__inner">
      <span>20.000+ <?php echo _ht(['it'=>'profili','en'=>'profiles','fr'=>'profils','es'=>'perfiles']); ?></span>
      <span>15+ <?php echo _ht(['it'=>'anni di esperienza','en'=>'years of experience','fr'=>'ans d\'expérience','es'=>'años de experiencia']); ?></span>
      <span>4 <?php echo _ht(['it'=>'lingue','en'=>'languages','fr'=>'langues','es'=>'idiomas']); ?></span>
      <span><?php echo _ht(['it'=>'Risposta in 24h','en'=>'Response in 24h','fr'=>'Réponse en 24h','es'=>'Respuesta en 24h']); ?></span>
    </div>
  </section>

  <!-- CONTENUTO PRINCIPALE -->
  <section class="toa-landing-body">
    <div class="toa-container toa-landing-body__inner">
      <div class="toa-landing-body__text">
        <?php the_content(); ?>
      </div>

      <!-- SERVIZI CORRELATI -->
      <aside class="toa-landing-body__aside">
        <h3><?php echo _ht(['it'=>'I nostri servizi','en'=>'Our services','fr'=>'Nos services','es'=>'Nuestros servicios']); ?></h3>
        <ul class="toa-landing-links">
          <li><a href="<?php echo home_url('/models/'); ?>"><?php echo _ht(['it'=>'Modelli & Modelle','en'=>'Models','fr'=>'Mannequins','es'=>'Modelos']); ?></a></li>
          <li><a href="<?php echo home_url('/hostess-steward/'); ?>"><?php echo _ht(['it'=>'Hostess & Steward','en'=>'Hostess & Staff','fr'=>'Hôtesses & Stewards','es'=>'Azafatas & Personal']); ?></a></li>
          <li><a href="<?php echo home_url('/actors/'); ?>"><?php echo _ht(['it'=>'Attori','en'=>'Actors','fr'=>'Acteurs','es'=>'Actores']); ?></a></li>
          <li><a href="<?php echo home_url('/extras/'); ?>"><?php echo _ht(['it'=>'Comparse','en'=>'Extras','fr'=>'Figurants','es'=>'Extras']); ?></a></li>
          <li><a href="<?php echo home_url('/b2bservices/'); ?>"><?php echo _ht(['it'=>'Servizi B2B','en'=>'B2B Services','fr'=>'Services B2B','es'=>'Servicios B2B']); ?></a></li>
        </ul>

        <?php if (!empty($events_arr)): ?>
        <h3><?php echo _ht(['it'=>'Fiere ed eventi','en'=>'Fairs & events','fr'=>'Foires & événements','es'=>'Ferias y eventos']); ?></h3>
        <ul class="toa-landing-links">
          <?php foreach ($events_arr as $ev): ?>
          <li><?php echo esc_html($ev); ?></li>
          <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <div class="toa-landing-cta-box">
          <p><?php echo _ht(['it'=>'Hai un progetto? Scrivici.','en'=>'Got a project? Write to us.','fr'=>'Un projet ? Écrivez-nous.','es'=>'¿Tienes un proyecto? Escríbenos.']); ?></p>
          <a href="#toa-form-anchor" class="toa-btn toa-btn--secondary">
            <?php echo _ht(['it'=>'Contattaci','en'=>'Contact us','fr'=>'Nous contacter','es'=>'Contáctanos']); ?>
          </a>
        </div>
      </aside>
    </div>
  </section>

  <!-- FORM PREVENTIVO -->
  <section id="toa-form-anchor" class="toa-landing-form-section">
    <div class="toa-container">
      <h2 class="toa-landing-form-title">
        <?php echo _ht(['it'=>'Chiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demandez un devis gratuit','es'=>'Solicita presupuesto gratuito']); ?>
      </h2>
      <p class="toa-landing-form-sub">
        <?php echo _ht(['it'=>'Risposta entro 24 ore lavorative.','en'=>'Response within 24 business hours.','fr'=>'Réponse sous 24 heures ouvrées.','es'=>'Respuesta en 24 horas hábiles.']); ?>
      </p>
      <?php toa_component('form-b2b-inline'); ?>
    </div>
  </section>

</main>

<?php toa_component('footer'); ?>
