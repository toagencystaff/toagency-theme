<?php
/**
 * Component: How It Works
 * Usage: toa_component('how-it-works', array('steps' => [...], 'tagline' => '...'))
 *
 * $steps = array of arrays: ['time' => '1\'', 'label' => 'COMPILI IL FORM']
 * $tagline = optional bottom text
 */
if (empty($steps)) return;
$__lang = isset($lang) ? $lang : 'it';
$__tr = function($a) use ($__lang) {
  return isset($a[$__lang]) ? $a[$__lang] : $a['it'];
};
?>
<section class="how-it-works">
  <div class="container">
    <div class="section-eyebrow"><?php echo esc_html($__tr([
      'it'=>'Come funziona',
      'en'=>'How it works',
      'fr'=>'Comment ça marche',
      'es'=>'Cómo funciona'
    ])); ?></div>
    <div class="steps-grid">
      <?php foreach ($steps as $i => $step) : ?>
      <div class="step-item">
        <div class="step-time"><?php echo esc_html($step['time']); ?></div>
        <div class="step-label"><?php echo esc_html($step['label']); ?></div>
      </div>
      <?php if ($i < count($steps) - 1) : ?>
      <div class="step-arrow">→</div>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <?php if (!empty($tagline)) : ?>
    <div class="steps-tagline"><?php echo esc_html($tagline); ?></div>
    <?php endif; ?>
  </div>
</section>
