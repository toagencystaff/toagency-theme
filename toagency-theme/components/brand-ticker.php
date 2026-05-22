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
    'it'=>'Hanno scelto TOAgency',
    'en'=>'They chose TOAgency',
    'fr'=>'Ils ont choisi TOAgency',
    'es'=>'Eligieron TOAgency'
  ])); ?></div>
  <div class="ticker-row" id="tickerRow1"></div>
  <div style="height:10px"></div>
  <div class="ticker-row reverse" id="tickerRow2"></div>
</section>
