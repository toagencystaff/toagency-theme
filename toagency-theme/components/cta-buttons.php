<?php
/**
 * Component: CTA Buttons
 * Usage: toa_component('cta-buttons', array('title' => '...', 'subtitle' => '...', 'buttons' => [...]))
 * 
 * $buttons = array of arrays: ['url' => '...', 'text' => '...', 'primary' => true/false, 'sub' => '...']
 */
$title    = isset($title) ? $title : 'Raccontaci il tuo progetto';
$subtitle = isset($subtitle) ? $subtitle : '';
$buttons  = isset($buttons) ? $buttons : array();
?>
<section class="cta-section">
  <div class="section-eyebrow">Inizia ora</div>
  <h2 class="section-heading"><?php echo esc_html($title); ?></h2>
  <?php if ($subtitle) : ?>
  <p class="cta-subtitle"><?php echo esc_html($subtitle); ?></p>
  <?php endif; ?>
  <div class="cta-buttons-row">
    <?php foreach ($buttons as $btn) : ?>
    <a href="<?php echo esc_url($btn['url']); ?>" class="btn-hero <?php echo !empty($btn['primary']) ? 'btn-hero-primary' : 'btn-hero-secondary'; ?>" <?php echo !empty($btn['target']) ? 'target="_blank"' : ''; ?>>
      <span><?php echo esc_html($btn['text']); ?></span>
    </a>
    <?php endforeach; ?>
  </div>
</section>
