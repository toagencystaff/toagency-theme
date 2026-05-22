<?php
/**
 * Component: Gallery Talent (slider orizzontale CSS-only)
 * Usage: toa_component('gallery-talent', array('images' => [...], 'columns' => 4))
 *
 * $columns = numero foto visibili contemporaneamente su desktop (default 4)
 * FIX 2026-05-22 marco — ripristino slider orizzontale (regressione)
 */
$columns = isset($columns) ? (int)$columns : 4;
if (empty($images)) return;

$seen = array();
$unique = array();
foreach ($images as $img) {
    if (!in_array($img['src'], $seen)) {
        $seen[] = $img['src'];
        $unique[] = $img;
    }
}
$images = $unique;
?>
<section class="gallery-section">
  <div class="gallery-slider" data-cols="<?php echo $columns; ?>" role="list" aria-label="Gallery talent">
    <?php foreach ($images as $img) : ?>
    <div class="gallery-item" role="listitem">
      <img src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
    </div>
    <?php endforeach; ?>
  </div>
</section>
