<?php
/**
 * Template Name: Homepage
 * Page template for the TOAgency homepage
 */
toa_component('header');
?>

<!-- ═══════════ HERO ═══════════ -->
<section class="hero">
  <div class="hero-video">
    <video autoplay loop muted playsinline>
      <source src="/wp-content/uploads/2025/09/homepagenew.mov" type="video/quicktime">
      <source src="/wp-content/uploads/2025/09/homepagenew.mov" type="video/mp4">
    </video>
  </div>
  <div class="hero-content">
    <div class="hero-eyebrow"><?php _e('Dal 2009, Casting & Produzione', 'toagency-theme'); ?></div>
    <h1 class="hero-title">
      <?php _e('Seleziona', 'toagency-theme'); ?><br><span class="rotating-word" id="rotatingWord"><?php _e('Modelli', 'toagency-theme'); ?></span>
    </h1>
    <p class="hero-subtitle">
      <?php _e('Preventivo gratuito in 30 minuti. Un team dedicato gestisce', 'toagency-theme'); ?> 
      <?php _e('selezione, contratti e logistica — tu pensi al progetto.', 'toagency-theme'); ?>
    </p>
    <div class="hero-actions">
      <a href="<?php echo home_url('/form-b2b/'); ?>" class="btn-hero btn-hero-primary">
        <span><?php _e('Sono un\'azienda — cerco talent', 'toagency-theme'); ?></span>
      </a>
      <a href="<?php echo home_url('/b-t-l/'); ?>" class="btn-hero btn-hero-secondary">
        <span><?php _e('Sono un talent — cerco lavori', 'toagency-theme'); ?></span>
      </a>
    </div>
  </div>
</section>

<!-- ═══════════ BRAND TICKER ═══════════ -->
<?php toa_component('brand-ticker'); ?>

<!-- ═══════════ STATS ═══════════ -->
<section class="stats-bar">
  <div class="stats-grid">
    <div class="stat-item">
      <div class="stat-number">20<span class="accent">K</span>+</div>
      <div class="stat-label"><?php _e('Professionisti', 'toagency-theme'); ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-number">10<span class="accent">K</span>+</div>
      <div class="stat-label"><?php _e('Progetti', 'toagency-theme'); ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-number">50<span class="accent">+</span></div>
      <div class="stat-label"><?php _e('Città operative', 'toagency-theme'); ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-number">15<span class="accent">+</span></div>
      <div class="stat-label"><?php _e('Anni di esperienza', 'toagency-theme'); ?></div>
    </div>
  </div>
</section>

<!-- ═══════════ WHY SECTION ═══════════ -->
<section class="why-section">
  <div class="container">
    <div class="section-eyebrow"><?php _e('Perché sceglierci', 'toagency-theme'); ?></div>
    <h2 class="section-heading"><?php _e('Non siamo una piattaforma.', 'toagency-theme'); ?><br><?php _e('Siamo il tuo team.', 'toagency-theme'); ?></h2>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-number">01</div>
      <h3 class="feature-title"><?php _e('Team umano dedicato', 'toagency-theme'); ?></h3>
      <p class="feature-text"><?php _e('Un casting director assegnato al tuo progetto. Gestiamo brief, selezione, contratti e pagamenti. Niente algoritmi — persone che capiscono cosa serve.', 'toagency-theme'); ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-number">02</div>
      <h3 class="feature-title"><?php _e('Copertura internazionale', 'toagency-theme'); ?></h3>
      <p class="feature-text"><?php _e('Operativi in Italia, Francia, Spagna e UK. Dalle fiere di Milano e Rimini agli shooting a Parigi e Londra. Un unico referente, ovunque.', 'toagency-theme'); ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-number">03</div>
      <h3 class="feature-title"><?php _e('Database verificato', 'toagency-theme'); ?></h3>
      <p class="feature-text"><?php _e('20.000+ profili aggiornati ogni giorno. Polaroid e self-tape in 24 ore. Disponibilità in tempo reale, anche per casting urgenti.', 'toagency-theme'); ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-number">04</div>
      <h3 class="feature-title"><?php _e('Risposta in 30 minuti', 'toagency-theme'); ?></h3>
      <p class="feature-text"><?php _e('Compili il form e in mezz\'ora hai le prime proposte con preventivo trasparente. Contratti digitali. Zero sorprese.', 'toagency-theme'); ?></p>
    </div>
  </div>
</section>

<!-- ═══════════ SERVICES ═══════════ -->
<section class="services-section">
  <div class="container">
    <div class="section-eyebrow"><?php _e('Cosa facciamo', 'toagency-theme'); ?></div>
    <h2 class="section-heading"><?php _e('Ogni progetto ha il talento giusto', 'toagency-theme'); ?></h2>
  </div>
  <div class="services-grid">
    <a href="<?php echo home_url('/models/'); ?>" class="service-card">
      <div class="service-icon">◐</div>
      <div class="service-name"><?php _e('Modelli', 'toagency-theme'); ?></div>
      <div class="service-desc"><?php _e('Fashion, e-commerce, cataloghi, campagne', 'toagency-theme'); ?></div>
    </a>
    <a href="<?php echo home_url('/hostess-steward/'); ?>" class="service-card">
      <div class="service-icon">◈</div>
      <div class="service-name"><?php _e('Hostess & Steward', 'toagency-theme'); ?></div>
      <div class="service-desc"><?php _e('Fiere, eventi, congressi, attivazioni', 'toagency-theme'); ?></div>
    </a>
    <a href="<?php echo home_url('/actors/'); ?>" class="service-card">
      <div class="service-icon">◉</div>
      <div class="service-name"><?php _e('Attori & Comparse', 'toagency-theme'); ?></div>
      <div class="service-desc"><?php _e('Film, serie TV, spot pubblicitari', 'toagency-theme'); ?></div>
    </a>
    <a href="<?php echo home_url('/visuals/'); ?>" class="service-card">
      <div class="service-icon">◫</div>
      <div class="service-name"><?php _e('Fotografi & Videomaker', 'toagency-theme'); ?></div>
      <div class="service-desc"><?php _e('Shooting, produzioni video, contenuti social', 'toagency-theme'); ?></div>
    </a>
    <a href="<?php echo home_url('/b2bservices/'); ?>" class="service-card">
      <div class="service-icon">◧</div>
      <div class="service-name"><?php _e('MUA & Hair Stylist', 'toagency-theme'); ?></div>
      <div class="service-desc"><?php _e('Trucco e acconciature per ogni produzione', 'toagency-theme'); ?></div>
    </a>
    <a href="<?php echo home_url('/casting/'); ?>" class="service-card">
      <div class="service-icon">◎</div>
      <div class="service-name"><?php _e('Casting completo', 'toagency-theme'); ?></div>
      <div class="service-desc"><?php _e('Selezione, logistica, gestione integrale', 'toagency-theme'); ?></div>
    </a>
  </div>
</section>

<!-- ═══════════ CTA ═══════════ -->
<?php toa_component('cta-buttons', array(
    'title'    => __('Raccontaci il tuo progetto', 'toagency-theme'),
    'subtitle' => __('Compila il form e in 30 minuti ricevi le prime proposte con preventivo gratuito.', 'toagency-theme'),
    'buttons'  => array(
        array('url' => home_url('/form-b2b/'), 'text' => __('Richiedi preventivo gratuito', 'toagency-theme'), 'primary' => true),
        array('url' => home_url('/b-t-l/'), 'text' => __('Sei un talent? Registrati', 'toagency-theme')),
    ),
)); ?>

<!-- ═══════════ COVERAGE ═══════════ -->
<section class="coverage-section">
  <div class="container">
    <div class="section-eyebrow"><?php _e('Dove operiamo', 'toagency-theme'); ?></div>
    <h2 class="section-heading" style="margin-bottom:40px"><?php _e('4 paesi, 50+ città', 'toagency-theme'); ?></h2>
  </div>
  <div class="coverage-grid container">
    <div class="coverage-country">
      <h4><?php _e('Italia', 'toagency-theme'); ?></h4>
      <p><?php _e('Milano, Roma, Torino, Genova, Venezia, Verona, Bologna, Firenze, Napoli, Rimini, Bari, Palermo, Catania', 'toagency-theme'); ?></p>
    </div>
    <div class="coverage-country">
      <h4><?php _e('Francia', 'toagency-theme'); ?></h4>
      <p><?php _e('Parigi, Lione, Marsiglia', 'toagency-theme'); ?></p>
    </div>
    <div class="coverage-country">
      <h4><?php _e('Spagna', 'toagency-theme'); ?></h4>
      <p><?php _e('Madrid, Barcellona, Valencia', 'toagency-theme'); ?></p>
    </div>
    <div class="coverage-country">
      <h4><?php _e('UK', 'toagency-theme'); ?></h4>
      <p><?php _e('Londra', 'toagency-theme'); ?></p>
    </div>
  </div>
</section>

<?php toa_component('footer'); ?>
