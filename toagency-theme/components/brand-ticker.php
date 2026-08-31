<?php
/**
 * Component: Brand Ticker
 * Usage: toa_component('brand-ticker')
 * Shows scrolling brand names with custom fonts
 */
$__lang = isset($lang) ? $lang : 'it';
$__tr = function($a) use ($__lang) {
  return isset($a[$__lang]) ? $a[$__lang] : $a['it'];
};
?>
<section class="brand-section">
  <div class="brand-label"><?php echo esc_html($__tr([
    'it'=>'Abbiamo lavorato per',
    'en'=>"We've worked with",
    'fr'=>'Nous avons collaboré avec',
    'es'=>'Hemos colaborado con'
  ])); ?></div>
  <div class="ticker-row" id="tickerRow1"></div>
  <div class="ticker-row reverse" id="tickerRow2"></div>
</section>
