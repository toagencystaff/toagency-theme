<?php
/**
 * Template Name: Hostess & Steward
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Staff professionale per fiere, eventi e congressi.<br>Richiedi un preventivo gratuito &mdash; ti ricontattiamo subito, prezzi trasparenti.',
        'en' => 'Professional staff for trade fairs, events and conferences.<br>Request a free quote &mdash; we get back to you right away, transparent prices.',
        'fr' => 'Personnel professionnel pour salons, &eacute;v&eacute;nements et congr&egrave;s.<br>Demandez un devis gratuit &mdash; nous vous recontactons tout de suite, prix transparents.',
        'es' => 'Personal profesional para ferias, eventos y congresos.<br>Solicita un presupuesto gratuito &mdash; te contactamos enseguida, precios transparentes.',
    ),
    'hero_cta' => array(
        'it' => 'Richiedi un preventivo gratuito',
        'en' => 'Request a free quote',
        'fr' => 'Demandez un devis gratuit',
        'es' => 'Solicita un presupuesto gratuito',
    ),
    'hero_price' => array(
        'it' => 'Tariffe a partire da &euro;119/persona/giorno',
        'en' => 'Rates from &euro;119/person/day',
        'fr' => 'Tarifs &agrave; partir de 119&euro;/personne/jour',
        'es' => 'Tarifas desde 119&euro;/persona/d&iacute;a',
    ),
    'cta_heading' => array(
        'it' => 'Scegli come procedere',
        'en' => 'Choose how to proceed',
        'fr' => 'Choisissez comment proc&eacute;der',
        'es' => 'Elige c&oacute;mo proceder',
    ),
    'cta_prev' => array(
        'it' => 'Richiedi preventivo',
        'en' => 'Request a quote',
        'fr' => 'Demander un devis',
        'es' => 'Solicitar presupuesto',
    ),
    'cta_lavora' => array(
        'it' => 'Lavora come hostess/steward',
        'en' => 'Work as hostess/steward',
        'fr' => 'Travailler comme h&ocirc;tesse/steward',
        'es' => 'Trabaja como azafata/promotor',
    ),
    'how_step1' => array('it' => 'CI SCRIVI', 'en' => 'CONTACT US', 'fr' => 'CONTACTEZ-NOUS', 'es' => 'CONT&Aacute;CTANOS'),
    'how_step2' => array('it' => 'TI RISPONDIAMO', 'en' => 'WE REPLY', 'fr' => 'NOUS R&Eacute;PONDONS', 'es' => 'TE RESPONDEMOS'),
    'how_step3' => array('it' => 'PREVENTIVO SU MISURA', 'en' => 'TAILORED QUOTE', 'fr' => 'DEVIS SUR MESURE', 'es' => 'PRESUPUESTO A MEDIDA'),
    'how_step4' => array('it' => 'TI SEGUIAMO', 'en' => 'WE FOLLOW UP', 'fr' => 'NOUS VOUS SUIVONS', 'es' => 'TE SEGUIMOS'),
    'how_tagline' => array(
        'it' => 'Preventivo gratuito &bull; Risposta rapida &bull; Garanzia 100%',
        'en' => 'Free quote &bull; Fast response &bull; 100% guarantee',
        'fr' => 'Devis gratuit &bull; R&eacute;ponse rapide &bull; Garantie 100%',
        'es' => 'Presupuesto gratuito &bull; Respuesta r&aacute;pida &bull; Garant&iacute;a 100%',
    ),
    'feat_eyebrow' => array('it' => 'Garanzie', 'en' => 'Guarantees', 'fr' => 'Garanties', 'es' => 'Garant&iacute;as'),
    'feat_heading' => array(
        'it' => 'Servizio professionale garantito',
        'en' => 'Guaranteed professional service',
        'fr' => 'Service professionnel garanti',
        'es' => 'Servicio profesional garantizado',
    ),
    'feat1_title' => array('it' => 'Preventivo veloce', 'en' => 'Fast quote', 'fr' => 'Devis rapide', 'es' => 'Presupuesto r&aacute;pido'),
    'feat1_text' => array(
        'it' => 'Ti ricontattiamo subito con un preventivo chiaro, nessun costo nascosto.',
        'en' => 'We get back to you right away with a clear quote, no hidden costs.',
        'fr' => 'Nous vous recontactons tout de suite avec un devis clair, aucun co&ucirc;t cach&eacute;.',
        'es' => 'Te contactamos enseguida con un presupuesto claro, sin costes ocultos.',
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
        'it' => 'Raccontaci il tuo evento e ricevi subito un preventivo su misura.',
        'en' => 'Tell us about your event and get a tailored quote right away.',
        'fr' => 'Parlez-nous de votre &eacute;v&eacute;nement et recevez tout de suite un devis sur mesure.',
        'es' => 'Cu&eacute;ntanos tu evento y recibe enseguida un presupuesto a medida.',
    ),
    'banner_cta' => array(
        'it' => 'Richiedi preventivo &rarr;',
        'en' => 'Request a quote &rarr;',
        'fr' => 'Demander un devis &rarr;',
        'es' => 'Solicitar presupuesto &rarr;',
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
    array('src' => '/wp-content/uploads/2025/09/hostess-38-yo-caucasian.jpg', 'alt' => 'Hostess professionale 38 anni per fiere ed eventi aziendali — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-35-yo-caucasian.jpg', 'alt' => 'Steward 35 anni per eventi e congressi aziendali — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-23-yo-latina.jpg', 'alt' => 'Hostess sportiva 23 anni per eventi sport e promozioni — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-29-yo-african.jpg', 'alt' => 'Hostess 29 anni per congressi e fiere internazionali — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-26-yo-east-asian-1.jpg', 'alt' => 'Steward asiatico 26 anni per eventi e accoglienza — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-32-yo-caucasian.jpg', 'alt' => 'Hostess 32 anni per fiere e attivazioni di brand — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-50-yo-african.jpg', 'alt' => 'Steward senior 50 anni per eventi e congressi — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-25-yo-caucasian.jpg', 'alt' => 'Hostess sportiva 25 anni per eventi sport e promozioni — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-31-yo-ispanic.jpg', 'alt' => 'Steward ispanico 31 anni per eventi e accoglienza — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-26-yo-caucasian.jpg', 'alt' => 'Hostess 26 anni per eventi aziendali e fiere — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-40-yo-caucasian.jpg', 'alt' => 'Steward 40 anni per congressi ed eventi corporate — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-26-yo-african-2.jpg', 'alt' => 'Hostess sportiva africana 26 anni per eventi sport — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-29-yo-east-asian.jpg', 'alt' => 'Hostess asiatica 29 anni per fiere ed eventi — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-39-north-european.jpg', 'alt' => 'Steward nord-europeo 39 anni per eventi e congressi — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-31.5-yo-caucasian.jpg', 'alt' => 'Hostess professionale 31 anni per fiere ed eventi aziendali — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-28-yo-african.jpg', 'alt' => 'Steward africano 28 anni per eventi e accoglienza — TOAgency'),
);
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'HOSTESS & STEWARD',
    'title'      => _t_raw(array('it'=>'Hostess & Steward.','en'=>'Hostess & Steward.','fr'=>'Hôtesses & Stewards.','es'=>'Azafatas & Personal.')),
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
            <a href="<?php echo home_url('/collabora/'); ?>" class="btn-hero btn-hero-secondary"><span><?php echo $_t($t['cta_lavora']); ?></span></a>
        </div>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "1'", 'label' => $_t($t['how_step1'])),
        array('time' => 'SUBITO', 'label' => $_t($t['how_step2'])),
        array('time' => "✓", 'label' => $_t($t['how_step3'])),
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
