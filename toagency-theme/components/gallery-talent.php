<?php
/**
 * Component: Gallery Talent
 * Usage: toa_component('gallery-talent', array('images' => [...], 'columns' => 4))
 * 
 * $images = array of arrays: ['src' => 'url', 'alt' => 'text']
 * $columns = 3 or 4 (default 4)
 */
$columns = isset($columns) ? (int)$columns : 4;
if (empty($images)) return;

// Remove duplicates by src
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
  <div class="gallery-grid cols-<?php echo $columns; ?>">
    <?php foreach ($images as $img) : ?>
    <div class="gallery-item">
      <img src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
    </div>
    <?php endforeach; ?>
  </div>
</section>
