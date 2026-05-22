<?php
/**
 * Component: Gallery Talent (nastro auto-scroll continuo con shuffle anti-duplicate)
 * FIX 2026-05-22c marco — la copia per il loop continuo viene shuffled per
 * minimizzare la probabilità che la stessa immagine appaia 2 volte nel viewport.
 *
 * NB con N=14 immagini e viewport che ne mostra 8, è matematicamente
 * impossibile garantire 0 duplicate. Shuffle minimizza statisticamente.
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

// Crea copia shuffled per il loop continuo (riduce duplicati adiacenti)
$shuffled_copy = $images;
shuffle($shuffled_copy);

// Forza il primo elemento della copia ad essere DIVERSO dall'ultimo dell'originale
if (count($shuffled_copy) > 1 && $shuffled_copy[0]['src'] === end($images)['src']) {
    list($shuffled_copy[0], $shuffled_copy[1]) = array($shuffled_copy[1], $shuffled_copy[0]);
}

$track = array_merge($images, $shuffled_copy);
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
