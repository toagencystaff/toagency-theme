<?php
/**
 * Template Name: Hostess & Steward
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Staff professionale per fiere, eventi e congressi.<br>Calcola il preventivo live in tempo reale &mdash; prezzi trasparenti, zero sorprese.',
        'en' => 'Professional staff for trade fairs, events and conferences.<br>Calculate your live quote in real time &mdash; transparent prices, no surprises.',
        'fr' => 'Personnel professionnel pour salons, &eacute;v&eacute;nements et congr&egrave;s.<br>Calculez votre devis en temps r&eacute;el &mdash; prix transparents, z&eacute;ro surprise.',
        'es' => 'Personal profesional para ferias, eventos y congresos.<br>Calcula tu presupuesto en vivo en tiempo real &mdash; precios transparentes, sin sorpresas.',
    ),
    'hero_cta' => array(
        'it' => 'Calcola il tuo preventivo live',
        'en' => 'Calculate your live quote',
        'fr' => 'Calculez votre devis en direct',
        'es' => 'Calcula tu presupuesto en vivo',
    ),
    'hero_price' => array(
        'it' => 'Prezzo in tempo reale, da &euro;119/persona/giorno',
        'en' => 'Real-time pricing, from &euro;119/person/day',
        'fr' => 'Prix en temps r&eacute;el, &agrave; partir de 119&euro;/personne/jour',
        'es' => 'Precio en tiempo real, desde 119&euro;/persona/d&iacute;a',
    ),
    'cta_heading' => array(
        'it' => 'Scegli come procedere',
        'en' => 'Choose how to proceed',
        'fr' => 'Choisissez comment proc&eacute;der',
        'es' => 'Elige c&oacute;mo proceder',
    ),
    'cta_prev' => array(
        'it' => 'Preventivo live immediato',
        'en' => 'Instant live quote',
        'fr' => 'Devis en direct imm&eacute;diat',
        'es' => 'Presupuesto en vivo inmediato',
    ),
    'cta_lavora' => array(
        'it' => 'Lavora come hostess/steward',
        'en' => 'Work as hostess/steward',
        'fr' => 'Travailler comme h&ocirc;tesse/steward',
        'es' => 'Trabaja como azafata/promotor',
    ),
    'how_step1' => array('it' => 'SELEZIONI I SERVIZI', 'en' => 'SELECT SERVICES', 'fr' => 'S&Eacute;LECTIONNEZ LES SERVICES', 'es' => 'SELECCIONA SERVICIOS'),
    'how_step2' => array('it' => 'VEDI IL PREZZO', 'en' => 'SEE THE PRICE', 'fr' => 'VOYEZ LE PRIX', 'es' => 'VE EL PRECIO'),
    'how_step3' => array('it' => 'CONFERMI', 'en' => 'CONFIRM', 'fr' => 'CONFIRMEZ', 'es' => 'CONFIRMA'),
    'how_step4' => array('it' => 'TI SEGUIAMO', 'en' => 'WE FOLLOW UP', 'fr' => 'NOUS VOUS SUIVONS', 'es' => 'TE SEGUIMOS'),
    'how_tagline' => array(
        'it' => 'Preventivo live immediato &bull; Listino prezzi trasparente &bull; Garanzia 100%',
        'en' => 'Instant live quote &bull; Transparent price list &bull; 100% guarantee',
        'fr' => 'Devis en direct imm&eacute;diat &bull; Tarifs transparents &bull; Garantie 100%',
        'es' => 'Presupuesto en vivo inmediato &bull; Lista de precios transparente &bull; Garant&iacute;a 100%',
    ),
    'feat_eyebrow' => array('it' => 'Garanzie', 'en' => 'Guarantees', 'fr' => 'Garanties', 'es' => 'Garant&iacute;as'),
    'feat_heading' => array(
        'it' => 'Servizio professionale garantito',
        'en' => 'Guaranteed professional service',
        'fr' => 'Service professionnel garanti',
        'es' => 'Servicio profesional garantizado',
    ),
    'feat1_title' => array('it' => 'Preventivo immediato', 'en' => 'Instant quote', 'fr' => 'Devis imm&eacute;diat', 'es' => 'Presupuesto inmediato'),
    'feat1_text' => array(
        'it' => 'Calcolo live del prezzo in tempo reale. Listino chiaro, nessun costo nascosto.',
        'en' => 'Live price calculation in real time. Clear price list, no hidden costs.',
        'fr' => 'Calcul du prix en temps r&eacute;el. Tarifs clairs, aucun co&ucirc;t cach&eacute;.',
        'es' => 'C&aacute;lculo del precio en vivo en tiempo real. Lista de precios clara, sin costes ocultos.',
    ),
    'feat2_title' => array('it' => 'Operatore dedicato', 'en' => 'Dedicated manager', 'fr' => 'R&eacute;f&eacute;rent d&eacute;di&eacute;', 'es' => 'Gestor dedicado'),
    'feat2_text' => array(
        'it' => 'Ti segue sempre lo stesso referente dall\'inizio alla fine dell\'evento.',
        'en' => 'The same contact person follows you from start to finish of the event.',
        'fr' => 'Le m&ecirc;me r&eacute;f&eacute;rent vous suit du d&eacute;but &agrave; la fin de l\'&eacute;v&eacute;nement.',
        'es' => 'El mismo referente te sigue desde el inicio hasta el final del evento.',
    ),
    'feat3_title' => array('it' => 'Garanzia 100%', 'en' => '100% guarantee', 'fr' => 'Garantie 100%', 'es' => 'Garant&iacute;a 100%'),
    'feat3_text' => array(
        'it' => 'Sostituzione immediata se serve. Nessun rischio per il tuo evento.',
        'en' => 'Immediate replacement if needed. No risk for your event.',
        'fr' => 'Remplacement imm&eacute;diat si n&eacute;cessaire. Aucun risque pour votre &eacute;v&eacute;nement.',
        'es' => 'Sustituci&oacute;n inmediata si es necesario. Ning&uacute;n riesgo para tu evento.',
    ),
    'feat4_title' => array('it' => 'Staff formato', 'en' => 'Trained staff', 'fr' => 'Personnel form&eacute;', 'es' => 'Personal formado'),
    'feat4_text' => array(
        'it' => 'Hostess multilingue, steward per congressi, promoter GDO, interpreti e standisti.',
        'en' => 'Multilingual hostesses, conference stewards, retail promoters, interpreters and booth staff.',
        'fr' => 'H&ocirc;tesses multilingues, stewards pour congr&egrave;s, promoteurs GMS, interpr&egrave;tes et standistes.',
        'es' => 'Azafatas multiling&uuml;es, promotores para congresos, promotores GDO, int&eacute;rpretes y standistas.',
    ),
    'banner_title' => array(
        'it' => 'Hai un evento in programma?',
        'en' => 'Do you have an event coming up?',
        'fr' => 'Vous avez un &eacute;v&eacute;nement &agrave; venir ?',
        'es' => '&iquest;Tienes un evento programado?',
    ),
    'banner_subtitle' => array(
        'it' => 'Configura il tuo team e ricevi il preventivo in tempo reale.',
        'en' => 'Configure your team and get a quote in real time.',
        'fr' => 'Configurez votre &eacute;quipe et recevez un devis en temps r&eacute;el.',
        'es' => 'Configura tu equipo y recibe el presupuesto en tiempo real.',
    ),
    'banner_cta' => array(
        'it' => 'Preventivo Live &rarr;',
        'en' => 'Live Quote &rarr;',
        'fr' => 'Devis en direct &rarr;',
        'es' => 'Presupuesto en vivo &rarr;',
    ),
    'cov_eyebrow' => array('it' => 'Dove operiamo', 'en' => 'Where we operate', 'fr' => 'O&ugrave; nous intervenons', 'es' => 'D&oacute;nde operamos'),
    'cov_heading' => array(
        'it' => 'Le principali fiere italiane',
        'en' => 'Major Italian trade fairs',
        'fr' => 'Les principaux salons italiens',
        'es' => 'Las principales ferias italianas',
    ),
);

toa_component('header');

$images = array(
    array('src' => '/wp-content/uploads/2025/09/hostess-38-yo-caucasian.jpg', 'alt' => 'Hostess professionale'),
    array('src' => '/wp-content/uploads/2025/09/steward-35-yo-caucasian.jpg', 'alt' => 'Steward evento'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-23-yo-latina.jpg', 'alt' => 'Hostess sport'),
    array('src' => '/wp-content/uploads/2025/09/hostess-29-yo-african.jpg', 'alt' => 'Hostess congresso'),
    array('src' => '/wp-content/uploads/2025/09/steward-26-yo-east-asian-1.jpg', 'alt' => 'Steward asiatico'),
    array('src' => '/wp-content/uploads/2025/09/hostess-32-yo-caucasian.jpg', 'alt' => 'Hostess fiera'),
    array('src' => '/wp-content/uploads/2025/09/steward-50-yo-african.jpg', 'alt' => 'Steward senior'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-25-yo-caucasian.jpg', 'alt' => 'Hostess sportiva'),
    array('src' => '/wp-content/uploads/2025/09/steward-31-yo-ispanic.jpg', 'alt' => 'Steward ispanico'),
    array('src' => '/wp-content/uploads/2025/09/hostess-26-yo-caucasian.jpg', 'alt' => 'Hostess evento'),
    array('src' => '/wp-content/uploads/2025/09/steward-40-yo-caucasian.jpg', 'alt' => 'Steward maturo'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-26-yo-african-2.jpg', 'alt' => 'Hostess africana'),
    array('src' => '/wp-content/uploads/2025/09/hostess-29-yo-east-asian.jpg', 'alt' => 'Hostess asiatica'),
    array('src' => '/wp-content/uploads/2025/09/steward-39-north-european.jpg', 'alt' => 'Steward nordico'),
    array('src' => '/wp-content/uploads/2025/09/hostess-31.5-yo-caucasian.jpg', 'alt' => 'Hostess professionale'),
    array('src' => '/wp-content/uploads/2025/09/steward-28-yo-african.jpg', 'alt' => 'Steward africano'),
);
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'HOSTESS & STEWARD',
    'title'      => 'Hostess & Steward.',
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Hero CTA -->
<div class="container" style="margin-top:-20px;margin-bottom:40px">
    <a href="<?php echo home_url('/hostess-live-form/'); ?>" class="btn-hero btn-hero-primary" style="display:inline-flex;align-items:center;gap:8px">
        <span><?php echo $_t($t['hero_cta']); ?></span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.8rem;color:var(--gray-4);margin-top:8px"><?php echo $_t($t['hero_price']); ?></p>
</div>

<?php toa_component('gallery-talent', array('images' => $images, 'columns' => 4)); ?>

<!-- Split CTA -->
<section class="split-cta">
    <div class="container">
        <h2 class="section-heading" style="text-align:center;margin:0 auto 16px"><?php echo $_t($t['cta_heading']); ?></h2>
        <div class="cta-buttons-row">
            <a href="<?php echo home_url('/hostess-live-form/'); ?>" class="btn-hero btn-hero-primary"><span><?php echo $_t($t['cta_prev']); ?></span></a>
            <a href="<?php echo home_url('/b-t-l/'); ?>" class="btn-hero btn-hero-secondary"><span><?php echo $_t($t['cta_lavora']); ?></span></a>
        </div>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "1'", 'label' => $_t($t['how_step1'])),
        array('time' => 'LIVE', 'label' => $_t($t['how_step2'])),
        array('time' => "5'", 'label' => $_t($t['how_step3'])),
        array('time' => 'H24', 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<!-- Features -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['feat_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['feat_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card"><div class="feature-number">01</div><h3 class="feature-title"><?php echo $_t($t['feat1_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat1_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">02</div><h3 class="feature-title"><?php echo $_t($t['feat2_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat2_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">03</div><h3 class="feature-title"><?php echo $_t($t['feat3_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat3_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">04</div><h3 class="feature-title"><?php echo $_t($t['feat4_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat4_text']); ?></p></div>
    </div>
</section>

<!-- CTA Banner Preventivo -->
<section style="padding:60px 0;text-align:center;background:var(--accent);color:var(--black)">
    <div class="container">
        <h2 style="font-family:var(--font-display);font-size:2rem;font-weight:900;margin-bottom:8px"><?php echo $_t($t['banner_title']); ?></h2>
        <p style="font-size:1rem;margin-bottom:24px;opacity:0.8"><?php echo $_t($t['banner_subtitle']); ?></p>
        <a href="<?php echo home_url('/hostess-live-form/'); ?>" style="display:inline-block;padding:14px 32px;background:var(--black);color:var(--accent);font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;transition:all 0.3s"><?php echo $_t($t['banner_cta']); ?></a>
    </div>
</section>

<!-- Dove operiamo - Fiere -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['cov_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:40px"><?php echo $_t($t['cov_heading']); ?></h2>
    </div>
    <div class="coverage-grid container">
        <div class="coverage-country"><h4>Milano</h4><p>Fiera Milano Rho, Salone del Mobile, Fashion Week</p></div>
        <div class="coverage-country"><h4>Bologna</h4><p>Bologna Fiere, Cosmoprof, Motor Show</p></div>
        <div class="coverage-country"><h4>Verona</h4><p>Veronafiere, Vinitaly, Marmomac</p></div>
        <div class="coverage-country"><h4>Torino</h4><p>Lingotto Fiere, Salone Auto, ATP Finals</p></div>
        <div class="coverage-country"><h4>Rimini</h4><p>Rimini Fiera, Sigep, TTG Travel</p></div>
        <div class="coverage-country"><h4>Roma</h4><p>Fiera di Roma, Maker Faire, Romics</p></div>
        <div class="coverage-country"><h4>Firenze</h4><p>Fortezza da Basso, Pitti Immagine, Taste</p></div>
        <div class="coverage-country"><h4>Genova</h4><p>Fiera di Genova, Salone Nautico, Euroflora</p></div>
    </div>
</section>

<?php toa_component('footer'); ?>
