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
      <span><?php echo _ht(['it'=>'Un referente, una fattura','en'=>'One contact, one invoice','fr'=>'Un interlocuteur, une facture','es'=>'Un interlocutor, una factura']); ?></span><?php /* 26/11/2026 TEMA SEO AI EVENTI: niente promesse sui tempi di risposta (regola Marco 20/10) */ ?>
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

        <!-- ALTRI SERVIZI EVENTO — FIX 2026-09-10 marco (richiamo su tutte le landing fiera/geo) -->
        <p class="toa-landing-extra-services">
          <?php echo _ht([
            'it'=>"Oltre a hostess e interpreti, per il tuo stand possiamo fornire anche fotografi e videomaker, bartender e camerieri, DJ, runner e molte altre figure — e se serve ci occupiamo anche delle pratiche e dei permessi legati all'evento.",
            'en'=>"Beyond hostesses and interpreters, for your stand we can also provide photographers and videographers, bartenders and waiters, DJs, runners and many other roles — and if needed, we can also handle event-related permits and paperwork.",
            'fr'=>"Au-delà des hôtesses et interprètes, pour votre stand nous pouvons aussi fournir photographes et vidéastes, barmen et serveurs, DJ, runners et bien d'autres profils — et si besoin, nous nous occupons aussi des démarches et autorisations liées à l'événement.",
            'es'=>"Además de azafatas e intérpretes, para tu stand también podemos ofrecer fotógrafos y videógrafos, camareros y bartenders, DJ, runners y muchos otros perfiles — y si es necesario, también nos ocupamos de los trámites y permisos relacionados con el evento.",
          ]); ?>
          <a href="<?php echo esc_url( apply_filters('wpml_permalink', home_url('/hostess-steward/'), $__l) ); ?>">
            <?php echo _ht(['it'=>'Scopri tutti i servizi','en'=>'Discover all our services','fr'=>'Découvrez tous nos services','es'=>'Descubre todos nuestros servicios']); ?> &rarr;
          </a>
        </p>

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

  <?php // 26/11/2026 TEMA SEO AI EVENTI: rete TOA Events (città, fiere, staff e prezzi) — collega questa pagina alla struttura eventi
  toa_component('seo-rete'); ?>

  <!-- FORM PREVENTIVO -->
  <section id="toa-form-anchor" class="toa-landing-form-section">
    <div class="toa-container">
      <h2 class="toa-landing-form-title">
        <?php echo _ht(['it'=>'Chiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demandez un devis gratuit','es'=>'Solicita presupuesto gratuito']); ?>
      </h2>
      <p class="toa-landing-form-sub">
        <?php echo _ht(['it'=>'Se è urgente, rispondiamo subito.','en'=>'If it\'s urgent, we reply right away.','fr'=>'Si c\'est urgent, nous répondons tout de suite.','es'=>'Si es urgente, respondemos enseguida.']); ?>
      </p>
      <?php // 26/11/2026 ticket #1096: chi cerca «agenzia comparse / casting» e' spesso un talent: invito ben visibile sopra il form clienti ?>
      <div class="toa-landing-form-talent-note" style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:12px 20px;margin:0 0 24px;padding:16px 20px;border:2px solid #111;border-radius:8px;background:#f3f6f5">
        <strong style="font-size:17px;line-height:1.3"><?php echo _ht(['it'=>'Vuoi lavorare come comparsa, modello/a o hostess?','en'=>'Want to work as an extra, model or hostess?','fr'=>'Vous voulez travailler comme figurant, mannequin ou hôtesse ?','es'=>'¿Quieres trabajar como extra, modelo o azafata?']); ?></strong>
        <a href="<?php echo esc_url(home_url('/registrati-talent/')); ?>" style="display:inline-block;padding:12px 22px;background:#111;color:#fff;border-radius:6px;font-weight:700;text-decoration:none;white-space:nowrap"><?php echo _ht(['it'=>'Iscriviti gratis','en'=>'Join for free','fr'=>'Inscription gratuite','es'=>'Regístrate gratis']); ?> &rarr;</a>
      </div>
      <?php toa_component('form-b2b-inline'); ?>
    </div>
  </section>

</main>

<?php toa_component('footer'); ?>
