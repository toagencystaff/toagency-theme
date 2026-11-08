<?php
/**
 * Component: FAQ a tendina + dati strutturati FAQPage (TEMA GEO AI, 08/11/2026).
 * Usage: toa_component('toa-faq', array('toafaq_items' => $items));
 * $items = array( array('q' => array('it'=>..,'en'=>..,'fr'=>..,'es'=>..), 'a' => array(...)), ... )
 * Stampa le domande nella lingua corrente e lo stesso testo nel JSON-LD (Google e le AI leggono la stessa cosa che vede la persona).
 * Solo testo semplice nelle risposte (niente HTML).
 */
$toafaq_lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$toafaq_lang = in_array($toafaq_lang, array('it','en','fr','es'), true) ? $toafaq_lang : 'it';
$toafaq_tx = function($a) use ($toafaq_lang) { return isset($a[$toafaq_lang]) ? $a[$toafaq_lang] : $a['it']; };
$toafaq_ld = array('@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array());
foreach ((array) $toafaq_items as $toafaq_i) {
    $toafaq_ld['mainEntity'][] = array('@type' => 'Question', 'name' => $toafaq_tx($toafaq_i['q']), 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $toafaq_tx($toafaq_i['a'])));
}
?>
<style>
.toa-faq{max-width:820px;margin:20px 0 0}
.toa-faq details{border-bottom:1px solid #E6E6E1;padding:16px 0}
.toa-faq summary{cursor:pointer;font-weight:700;font-size:1.02rem;line-height:1.35;list-style:none;color:#08080A}
.toa-faq summary::-webkit-details-marker{display:none}
.toa-faq summary:after{content:"+";float:right;margin-left:12px;font-weight:800}
.toa-faq details[open] summary:after{content:"\2212"}
.toa-faq p{margin:10px 0 0;color:#3A3A36;font-size:.97rem;line-height:1.7}
</style>
<div class="toa-faq">
<?php foreach ((array) $toafaq_items as $toafaq_i): ?>
  <details><summary><?php echo esc_html($toafaq_tx($toafaq_i['q'])); ?></summary><p><?php echo esc_html($toafaq_tx($toafaq_i['a'])); ?></p></details>
<?php endforeach; ?>
</div>
<script type="application/ld+json"><?php echo wp_json_encode($toafaq_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>
