<?php
/**
 * Component: Gallery Talent (nastro auto-scroll continuo seamless)
 * FIX 2026-05-22d marco — copia identica all'originale per loop visivamente continuo.
 * Lo shuffle precedente causava jump visivo al reset dell'animazione.
 *
 * NB con N=14 immagini, qualche duplicato può apparire in viewport molto larghi.
 * Soluzione definitiva: caricare più foto (task #25).
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

// Copia IDENTICA all'originale per loop seamless
$track = array_merge($images, $images);
?>
<section class="gallery-section">
  <div class="gallery-tape" role="list" aria-label="Gallery talent">
    <div class="gallery-track">
      <?php foreach ($track as $idx => $img) :
        $is_clone = ($idx >= count($images));
      ?>
      <div class="gallery-item" role="listitem"<?php echo $is_clone ? ' aria-hidden="true"' : ''; ?>>
        <img src="<?php echo esc_url($img['src']); ?>" alt="<?php echo esc_attr($img['alt']); ?>" loading="lazy">
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
