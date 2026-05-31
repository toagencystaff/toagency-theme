<?php
/**
 * Template Name: Landing Hub
 * Template Post Type: page
 *
 * Hub page auto-aggiornante: elenca tutte le pagine con template page-landing-geo.php
 * Raggruppate per _toa_service_type. WPML-aware (mostra solo lingua corrente).
 * FIX 2026-05-31 marco — landing-hub
 *
 * NB: _ht() è globale (functions.php). CSS in landing-geo.css (enqueue esteso a questo template).
 */

// Etichette gruppi per servizio
$group_labels = [
    'casting'        => _ht(['it'=>'Casting per città','en'=>'Casting by city','fr'=>'Casting par ville','es'=>'Casting por ciudad']),
    'modelle'        => _ht(['it'=>'Agenzia modelle','en'=>'Model agency','fr'=>'Agence de mannequins','es'=>'Agencia de modelos']),
    'modelle-mature' => _ht(['it'=>'Modelle mature e over 50','en'=>'Mature & 50+ models','fr'=>'Mannequins matures','es'=>'Modelos maduras']),
    'comparse'       => _ht(['it'=>'Comparse e figuranti','en'=>'Extras & background','fr'=>'Figurants','es'=>'Extras']),
    'hostess-fiere'  => _ht(['it'=>'Hostess per fiere','en'=>'Trade show hostess','fr'=>'Hôtesses foires','es'=>'Azafatas ferias']),
    'hostess-eventi' => _ht(['it'=>'Hostess per eventi specifici','en'=>'Event hostess','fr'=>'Hôtesses événements','es'=>'Azafatas eventos']),
    'model-agency'   => _ht(['it'=>'Agenzia internazionale','en'=>'International agency','fr'=>'Agence internationale','es'=>'Agencia internacional']),
];
$group_order = ['casting','modelle','modelle-mature','comparse','hostess-fiere','hostess-eventi','model-agency'];

// Query tutte le landing
$query = new WP_Query([
    'post_type'      => 'page',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_query'     => [['key'=>'_wp_page_template','value'=>'templates/page-landing-geo.php']],
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

// Raggruppa per service type
$groups = [];
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        $type     = get_post_meta(get_the_ID(), '_toa_service_type', true) ?: 'casting';
        $subtitle = get_post_meta(get_the_ID(), '_toa_subtitle', true) ?: '';
        $groups[$type][] = ['title'=>get_the_title(), 'url'=>get_permalink(), 'subtitle'=>$subtitle];
    }
    wp_reset_postdata();
}

toa_component('header');
?>

<main class="toa-hub">
  <section class="toa-landing-hero">
    <div class="toa-landing-hero__inner toa-container">
      <h1 class="toa-landing-hero__h1"><?php the_title(); ?></h1>
      <p class="toa-landing-hero__sub">
        <?php echo _ht(['it'=>'Selezione professionale di modelli, hostess e talent in Italia e in Europa.','en'=>'Professional casting for models, hostess and talent across Italy and Europe.','fr'=>'Casting professionnel pour mannequins, hôtesses et talent en Italie et en Europe.','es'=>'Selección profesional de modelos, azafatas y talent en Italia y Europa.']); ?>
      </p>
    </div>
  </section>

  <section class="toa-hub-body toa-container">
    <?php foreach ($group_order as $type): ?>
      <?php if (empty($groups[$type])) continue; ?>
      <div class="toa-hub-group">
        <h2 class="toa-hub-group__title">
          <?php echo isset($group_labels[$type]) ? $group_labels[$type] : esc_html($type); ?>
        </h2>
        <div class="toa-hub-grid">
          <?php foreach ($groups[$type] as $item): ?>
          <a href="<?php echo esc_url($item['url']); ?>" class="toa-hub-card">
            <span class="toa-hub-card__title"><?php echo esc_html($item['title']); ?></span>
            <?php if ($item['subtitle']): ?>
            <span class="toa-hub-card__sub"><?php echo esc_html($item['subtitle']); ?></span>
            <?php endif; ?>
            <span class="toa-hub-card__arrow">&rarr;</span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <?php // Gruppo "altro" per service type non mappati ?>
    <?php $mapped = array_keys($group_labels); ?>
    <?php foreach ($groups as $type => $items): ?>
      <?php if (in_array($type, $mapped)) continue; ?>
      <div class="toa-hub-group">
        <h2 class="toa-hub-group__title"><?php echo esc_html($type); ?></h2>
        <div class="toa-hub-grid">
          <?php foreach ($items as $item): ?>
          <a href="<?php echo esc_url($item['url']); ?>" class="toa-hub-card">
            <span class="toa-hub-card__title"><?php echo esc_html($item['title']); ?></span>
            <span class="toa-hub-card__arrow">&rarr;</span>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <section id="toa-form-anchor" class="toa-landing-form-section">
    <div class="toa-container">
      <h2 class="toa-landing-form-title">
        <?php echo _ht(['it'=>'Richiedi un preventivo','en'=>'Request a quote','fr'=>'Demandez un devis','es'=>'Solicita presupuesto']); ?>
      </h2>
      <?php toa_component('form-b2b-inline'); ?>
    </div>
  </section>
</main>

<?php toa_component('footer'); ?>
