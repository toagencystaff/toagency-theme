<?php
/**
 * Template Name: Landing Ads (no chrome)
 * Template Post Type: page
 *
 * Landing dedicata alle campagne Google Ads. NOINDEX, niente header/menu, niente footer SEO.
 * Documento HTML autosufficiente: chiama wp_head()/wp_footer() (= tracking Ads/GTM/pixel + CSS tema)
 * ma NON include toa_component('header')/('footer') → zero nav, zero distrazioni.
 *
 * Quale landing: page meta `_toa_ads_key` (casting-torino | hostess-torino | casting-italia).
 * Lingua: ?lang=it|en|fr|es  →  fallback toa_current_lang()  →  'it'.
 * Form preventivo: riusa il componente form-b2b-inline (POST CRM → redirect /tnx/ = conversione Ads).
 *
 * FIX 2026-06-20 marco — nuova landing Ads (file nuovo, nessun file esistente toccato)
 */

// _ht() fallback solo se il tema non l'avesse già (il tema la definisce in functions.php e legge global $__l)
if (!function_exists('_ht')) {
    function _ht($strings) {
        global $__l;
        $l = (!empty($__l) && in_array($__l, ['it','en','fr','es'], true)) ? $__l : 'it';
        return esc_html(isset($strings[$l]) ? $strings[$l] : ($strings['it'] ?? ''));
    }
}

// --- Lingua: ?lang  →  toa_current_lang() (WPML)  →  'it' ---
$lang = isset($_GET['lang']) ? strtolower(trim($_GET['lang'])) : '';
if (!in_array($lang, ['it','en','fr','es'], true)) {
    $lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
}
if (!in_array($lang, ['it','en','fr','es'], true)) $lang = 'it';
// $__l pilota _ht() del tema (usata da form-b2b-inline) → form nella stessa lingua dell'hero
$__l = $lang;

// --- Quale landing ---
$post_id = get_queried_object_id();
$key     = get_post_meta($post_id, '_toa_ads_key', true) ?: 'casting-italia';

// --- Servizio pre-selezionato per landing ---
$default_service = [
    'casting-torino' => 'shooting',
    'hostess-torino' => 'fiera-salone',
    'casting-italia' => 'pubblicita',
][$key] ?? 'pubblicita';

// =================== COPY (3 landing x 4 lingue) ===================
$COPY = [
  'casting-torino' => [
    'h1'  => ['it'=>'Agenzia casting e modelli a Torino per aziende e produzioni','en'=>'Casting and model agency in Turin for companies and productions','fr'=>'Agence de casting et mannequins à Turin pour entreprises et productions','es'=>'Agencia de casting y modelos en Turín para empresas y producciones'],
    'sub' => ['it'=>'Modelli, hostess, attori e comparse selezionati per shooting, campagne ed eventi. Preventivo gratuito in 24 ore.','en'=>'Selected models, hostesses, actors and extras for shoots, campaigns and events. Free quote within 24 hours.','fr'=>'Mannequins, hôtesses, acteurs et figurants sélectionnés pour shootings, campagnes et événements. Devis gratuit sous 24 heures.','es'=>'Modelos, azafatas, actores y figurantes seleccionados para sesiones, campañas y eventos. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili nel database','15+ anni di esperienza, sede a Torino','Operativi a Torino e in tutto il Piemonte','Casting gestiti in 4 lingue'],
      'en'=>['20,000+ profiles in our database','15+ years of experience, based in Turin','Active across Turin and the Piedmont region','Casting managed in 4 languages'],
      'fr'=>['Plus de 20 000 profils en base','Plus de 15 ans d\'expérience, basés à Turin','Actifs à Turin et dans tout le Piémont','Casting géré en 4 langues'],
      'es'=>['Más de 20.000 perfiles en la base de datos','Más de 15 años de experiencia, sede en Turín','Operativos en Turín y en todo el Piamonte','Casting gestionado en 4 idiomas'],
    ],
  ],
  'hostess-torino' => [
    'h1'  => ['it'=>'Agenzia hostess e steward a Torino per fiere, eventi e congressi','en'=>'Hostess and event staff agency in Turin for trade fairs, events and congresses','fr'=>'Agence d\'hôtesses et stewards à Turin pour salons, événements et congrès','es'=>'Agencia de azafatas y stewards en Turín para ferias, eventos y congresos'],
    'sub' => ['it'=>'Personale qualificato e multilingue per fiere, congressi, stand e accoglienza. Preventivo gratuito in 24 ore.','en'=>'Qualified, multilingual hostesses and stewards for fairs, conferences, stands and reception. Free quote within 24 hours.','fr'=>'Personnel qualifié et multilingue pour salons, congrès, stands et accueil. Devis gratuit sous 24 heures.','es'=>'Personal cualificado y multilingüe para ferias, congresos, stands y recepción. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['Hostess e steward per fiere, saloni ed eventi','20.000+ profili nel database','15+ anni di esperienza, sede a Torino','Gestione in italiano, inglese, francese e spagnolo'],
      'en'=>['Hostesses and stewards for fairs, shows and events','20,000+ profiles in our database','15+ years of experience, based in Turin','Management in Italian, English, French and Spanish'],
      'fr'=>['Hôtesses et stewards pour salons et événements','Plus de 20 000 profils en base','Plus de 15 ans d\'expérience, basés à Turin','Gestion en italien, anglais, français et espagnol'],
      'es'=>['Azafatas y stewards para ferias, salones y eventos','Más de 20.000 perfiles en la base de datos','Más de 15 años de experiencia, sede en Turín','Gestión en italiano, inglés, francés y español'],
    ],
  ],
  'casting-italia' => [
    'h1'  => ['it'=>'Agenzia casting e modelli in Italia per aziende e produzioni','en'=>'Casting and model agency in Italy for companies and productions','fr'=>'Agence de casting et mannequins en Italie pour entreprises et productions','es'=>'Agencia de casting y modelos en Italia para empresas y producciones'],
    'sub' => ['it'=>'Modelli, attori, comparse e hostess per campagne, shooting e produzioni in tutta Italia. Preventivo gratuito in 24 ore.','en'=>'Models, actors, extras and hostesses for campaigns, shoots and productions across Italy. Free quote within 24 hours.','fr'=>'Mannequins, acteurs, figurants et hôtesses pour campagnes, shootings et productions partout en Italie. Devis gratuit sous 24 heures.','es'=>'Modelos, actores, figurantes y azafatas para campañas, sesiones y producciones en toda Italia. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili in tutte le regioni italiane','15+ anni di esperienza con brand, e-commerce e produzioni','Un solo contatto per casting a Milano, Roma, Torino e in tutta Italia','Gestione progetto in 4 lingue'],
      'en'=>['20,000+ profiles across every Italian region','15+ years of experience with brands, e-commerce and productions','Single point of contact for castings in Milan, Rome, Turin and all of Italy','Project management in 4 languages'],
      'fr'=>['Plus de 20 000 profils dans toutes les régions d\'Italie','Plus de 15 ans d\'expérience avec marques, e-commerce et productions','Un seul interlocuteur pour vos castings à Milan, Rome, Turin et dans toute l\'Italie','Gestion de projet en 4 langues'],
      'es'=>['Más de 20.000 perfiles en todas las regiones de Italia','Más de 15 años de experiencia con marcas, e-commerce y producciones','Un único contacto para castings en Milán, Roma, Turín y toda Italia','Gestión de proyectos en 4 idiomas'],
    ],
  ],
];

$c   = $COPY[$key] ?? $COPY['casting-italia'];
$h1  = $c['h1'][$lang]  ?? $c['h1']['it'];
$sub = $c['sub'][$lang] ?? $c['sub']['it'];
$bullets = $c['bul'][$lang] ?? $c['bul']['it'];
$cta_label = ['it'=>'Richiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demander un devis gratuit','es'=>'Solicitar presupuesto gratis'][$lang] ?? 'Richiedi un preventivo gratuito';
?><!DOCTYPE html>
<html lang="<?php echo esc_attr($lang); ?>">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow,noarchive">
<?php wp_head(); ?>
<style>
/* Landing Ads — layout no-chrome, scoped */
body.toa-ads-lp{margin:0;background:#fff;color:#16181d;-webkit-font-smoothing:antialiased}
.toa-ads-wrap{max-width:1080px;margin:0 auto;padding:28px 20px 48px}
.toa-ads-brand{font-weight:800;letter-spacing:.5px;font-size:20px;margin:0 0 22px}
.toa-ads-grid{display:grid;grid-template-columns:1.05fr 1fr;gap:40px;align-items:start}
.toa-ads-h1{font-size:34px;line-height:1.15;font-weight:800;margin:0 0 14px}
.toa-ads-sub{font-size:18px;line-height:1.5;color:#3a3f47;margin:0 0 22px}
.toa-ads-bullets{list-style:none;margin:0;padding:0}
.toa-ads-bullets li{position:relative;padding:9px 0 9px 30px;font-size:15.5px;border-bottom:1px solid #eee}
.toa-ads-bullets li:before{content:"";position:absolute;left:2px;top:13px;width:16px;height:9px;border-left:3px solid #16a34a;border-bottom:3px solid #16a34a;transform:rotate(-45deg)}
.toa-ads-cta-mobile{display:none}
/* il form (form-b2b-inline) è già stilato dal CSS del tema; lo incolliamo nella colonna destra */
.toa-ads-formcol .cta-section{padding:0!important;margin:0!important;background:transparent!important}
.toa-ads-formcol .container{padding:0!important;max-width:none!important}
@media(max-width:860px){
  .toa-ads-grid{grid-template-columns:1fr;gap:26px}
  .toa-ads-h1{font-size:27px}
  .toa-ads-sub{font-size:16px;margin-bottom:16px}
  .toa-ads-cta-mobile{display:inline-block;margin-top:6px;padding:13px 22px;background:#16181d;color:#fff;border-radius:8px;text-decoration:none;font-weight:700}
}
</style>
</head>
<body class="toa-ads-lp">
<div class="toa-ads-wrap">

  <div class="toa-ads-brand">TOAgency</div>

  <div class="toa-ads-grid">
    <!-- COLONNA SINISTRA: messaggio -->
    <div class="toa-ads-msgcol">
      <h1 class="toa-ads-h1"><?php echo esc_html($h1); ?></h1>
      <p class="toa-ads-sub"><?php echo esc_html($sub); ?></p>
      <ul class="toa-ads-bullets">
        <?php foreach ($bullets as $b): ?>
          <li><?php echo esc_html($b); ?></li>
        <?php endforeach; ?>
      </ul>
      <a href="#preventivo" class="toa-ads-cta-mobile"><?php echo esc_html($cta_label); ?></a>
    </div>

    <!-- COLONNA DESTRA: form preventivo (above the fold) -->
    <div class="toa-ads-formcol">
      <?php toa_component('form-b2b-inline'); ?>
    </div>
  </div>

</div>

<script>
/* pre-seleziona il servizio in base alla landing */
(function(){
  var sel = document.getElementById('hq_event_type');
  if (sel) {
    var v = <?php echo json_encode($default_service); ?>;
    if (v) { try { sel.value = v; } catch(e){} }
  }
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
