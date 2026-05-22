<?php
/**
 * Component: Gallery Talent (nastro auto-scroll continuo)
 * Usage: toa_component('gallery-talent', array('images' => [...], 'columns' => 4))
 *
 * Effetto: foto piccole, scroll orizzontale automatico continuo, pausa su hover.
 * FIX 2026-05-22b marco — versione tape (sostituisce slider scrollabile).
 */
if (empty($images)) return;

// Dedup
$seen = array();
$unique = array();
foreach ($images as $img) {
    if (!in_array($img['src'], $seen)) {
        $seen[] = $img['src'];
        $unique[] = $img;
    }
}
$images = $unique;

// Duplica per loop continuo (l'animazione fa translateX(-50%) e riparte → continuo visivo)
$doubled = array_merge($images, $images);
?>
<section class="gallery-section">
  <div class="gallery-tape" role="list" aria-label="Gallery talent">
    <div class="gallery-track">
      <?php foreach ($doubled as $idx => $img) :
        $is_clone = ($idx >= count($images));
      ?>
      <div class="gallery-item" role="listitem"<?php echo $is_clone ? ' aria-hidden="true"' : ''; ?>>
        <img src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
