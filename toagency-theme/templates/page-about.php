<?php
/**
 * Template Name: About
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Agenzia internazionale di casting e produzione.<br>Dal 2009 al servizio dei talenti e delle aziende.',
        'en' => 'International casting and production agency.<br>Since 2009, serving talents and businesses.',
        'fr' => 'Agence internationale de casting et production.<br>Depuis 2009, au service des talents et des entreprises.',
        'es' => 'Agencia internacional de casting y producci&oacute;n.<br>Desde 2009, al servicio de los talentos y las empresas.',
    ),
    'stat_anni' => array('it' => 'Anni esperienza', 'en' => 'Years experience', 'fr' => 'Ans d\'exp&eacute;rience', 'es' => 'A&ntilde;os de experiencia'),
    'stat_professionisti' => array('it' => 'Professionisti', 'en' => 'Professionals', 'fr' => 'Professionnels', 'es' => 'Profesionales'),
    'stat_citta' => array('it' => 'Citt&agrave; coperte', 'en' => 'Cities covered', 'fr' => 'Villes couvertes', 'es' => 'Ciudades cubiertas'),
    'stat_progetti' => array('it' => 'Progetti realizzati', 'en' => 'Projects completed', 'fr' => 'Projets r&eacute;alis&eacute;s', 'es' => 'Proyectos realizados'),
    'storia_eyebrow' => array('it' => 'La nostra storia', 'en' => 'Our story', 'fr' => 'Notre histoire', 'es' => 'Nuestra historia'),
    'storia_heading' => array('it' => 'Dal 2009, cresciamo con i nostri talenti', 'en' => 'Since 2009, growing with our talents', 'fr' => 'Depuis 2009, nous grandissons avec nos talents', 'es' => 'Desde 2009, crecemos con nuestros talentos'),
    'timeline_2009' => array('it' => 'Fondazione dell\'agenzia a Torino, primi casting per eventi locali', 'en' => 'Agency founded in Turin, first castings for local events', 'fr' => 'Fondation de l\'agence &agrave; Turin, premiers castings pour &eacute;v&eacute;nements locaux', 'es' => 'Fundaci&oacute;n de la agencia en Tur&iacute;n, primeros castings para eventos locales'),
    'timeline_2015' => array('it' => 'Espansione nazionale, apertura collaborazioni Milano e Roma', 'en' => 'National expansion, partnerships opened in Milan and Rome', 'fr' => 'Expansion nationale, ouverture de collaborations &agrave; Milan et Rome', 'es' => 'Expansi&oacute;n nacional, apertura de colaboraciones en Mil&aacute;n y Roma'),
    'timeline_2021' => array('it' => 'Apertura sede operativa a Parigi, espansione internazionale', 'en' => 'Paris office opened, international expansion', 'fr' => 'Ouverture du bureau de Paris, expansion internationale', 'es' => 'Apertura de la oficina de Par&iacute;s, expansi&oacute;n internacional'),
    'timeline_2023' => array('it' => 'Lancio piattaforma digitale e servizi produzione e-commerce', 'en' => 'Digital platform launch and e-commerce production services', 'fr' => 'Lancement de la plateforme num&eacute;rique et services de production e-commerce', 'es' => 'Lanzamiento de la plataforma digital y servicios de producci&oacute;n e-commerce'),
    'timeline_2024' => array('it' => 'Apertura UK e Spagna, oltre 20.000 professionisti nel network', 'en' => 'UK and Spain expansion, over 20,000 professionals in the network', 'fr' => 'Ouverture UK et Espagne, plus de 20 000 professionnels dans le r&eacute;seau', 'es' => 'Apertura en UK y Espa&ntilde;a, m&aacute;s de 20.000 profesionales en la red'),
    'valori_eyebrow' => array('it' => 'I nostri valori', 'en' => 'Our values', 'fr' => 'Nos valeurs', 'es' => 'Nuestros valores'),
    'valori_heading' => array('it' => 'Cosa ci guida', 'en' => 'What drives us', 'fr' => 'Ce qui nous guide', 'es' => 'Lo que nos gu&iacute;a'),
    'valore1_title' => array('it' => 'Inclusivit&agrave;', 'en' => 'Inclusivity', 'fr' => 'Inclusivit&eacute;', 'es' => 'Inclusividad'),
    'valore1_text' => array('it' => 'Valorizziamo ogni tipo di bellezza, et&agrave;, etnia e stile. Il talento non ha stereotipi.', 'en' => 'We value every type of beauty, age, ethnicity and style. Talent has no stereotypes.', 'fr' => 'Nous valorisons chaque type de beaut&eacute;, &acirc;ge, ethnie et style. Le talent n\'a pas de st&eacute;r&eacute;otypes.', 'es' => 'Valoramos todo tipo de belleza, edad, etnia y estilo. El talento no tiene estereotipos.'),
    'valore2_title' => array('it' => 'Professionalit&agrave;', 'en' => 'Professionalism', 'fr' => 'Professionnalisme', 'es' => 'Profesionalidad'),
    'valore2_text' => array('it' => 'Standard elevati in ogni fase del processo. Contratti chiari, pagamenti puntuali.', 'en' => 'High standards at every stage of the process. Clear contracts, punctual payments.', 'fr' => 'Standards &eacute;lev&eacute;s &agrave; chaque &eacute;tape du processus. Contrats clairs, paiements ponctuels.', 'es' => 'Est&aacute;ndares elevados en cada fase del proceso. Contratos claros, pagos puntuales.'),
    'valore3_title' => array('it' => 'Innovazione', 'en' => 'Innovation', 'fr' => 'Innovation', 'es' => 'Innovaci&oacute;n'),
    'valore3_text' => array('it' => 'Tecnologia al servizio della creativit&agrave;. Piattaforma digitale, AI matching, processi digitalizzati.', 'en' => 'Technology at the service of creativity. Digital platform, AI matching, digitized processes.', 'fr' => 'La technologie au service de la cr&eacute;ativit&eacute;. Plateforme num&eacute;rique, AI matching, processus digitalis&eacute;s.', 'es' => 'Tecnolog&iacute;a al servicio de la creatividad. Plataforma digital, AI matching, procesos digitalizados.'),
    'valore4_title' => array('it' => 'Trasparenza', 'en' => 'Transparency', 'fr' => 'Transparence', 'es' => 'Transparencia'),
    'valore4_text' => array('it' => 'Comunicazione chiara, preventivi dettagliati, nessun costo nascosto. Mai.', 'en' => 'Clear communication, detailed quotes, no hidden costs. Ever.', 'fr' => 'Communication claire, devis d&eacute;taill&eacute;s, aucun co&ucirc;t cach&eacute;. Jamais.', 'es' => 'Comunicaci&oacute;n clara, presupuestos detallados, sin costes ocultos. Nunca.'),
    'sedi_eyebrow' => array('it' => 'Le nostre sedi', 'en' => 'Our offices', 'fr' => 'Nos bureaux', 'es' => 'Nuestras oficinas'),
    'sedi_heading' => array('it' => 'Presenti in 4 paesi', 'en' => 'Present in 4 countries', 'fr' => 'Pr&eacute;sents dans 4 pays', 'es' => 'Presentes en 4 pa&iacute;ses'),
    'italia' => array('it' => 'Italia', 'en' => 'Italy', 'fr' => 'Italie', 'es' => 'Italia'),
    'italia_citta' => array('it' => 'Milano, Roma, Torino, Firenze, Bologna, Napoli, Genova, Verona', 'en' => 'Milan, Rome, Turin, Florence, Bologna, Naples, Genoa, Verona', 'fr' => 'Milan, Rome, Turin, Florence, Bologne, Naples, G&ecirc;nes, V&eacute;rone', 'es' => 'Mil&aacute;n, Roma, Tur&iacute;n, Florencia, Bolonia, N&aacute;poles, G&eacute;nova, Verona'),
    'francia' => array('it' => 'Francia', 'en' => 'France', 'fr' => 'France', 'es' => 'Francia'),
    'francia_citta' => array('it' => 'Parigi, Lione, Marsiglia', 'en' => 'Paris, Lyon, Marseille', 'fr' => 'Paris, Lyon, Marseille', 'es' => 'Par&iacute;s, Lyon, Marsella'),
    'spagna' => array('it' => 'Spagna', 'en' => 'Spain', 'fr' => 'Espagne', 'es' => 'Espa&ntilde;a'),
    'spagna_citta' => array('it' => 'Madrid, Barcellona, Valencia', 'en' => 'Madrid, Barcelona, Valencia', 'fr' => 'Madrid, Barcelone, Valence', 'es' => 'Madrid, Barcelona, Valencia'),
    'uk_citta' => array('it' => 'Londra, Manchester', 'en' => 'London, Manchester', 'fr' => 'Londres, Manchester', 'es' => 'Londres, Manchester'),
    'cta_title' => array('it' => 'Inizia subito', 'en' => 'Get started now', 'fr' => 'Commencez maintenant', 'es' => 'Empieza ahora'),
    'cta_subtitle' => array(
        'it' => 'Che tu sia un\'azienda o un talento, abbiamo la soluzione giusta per te.',
        'en' => 'Whether you\'re a business or a talent, we have the right solution for you.',
        'fr' => 'Que vous soyez une entreprise ou un talent, nous avons la solution adapt&eacute;e pour vous.',
        'es' => 'Ya seas una empresa o un talento, tenemos la soluci&oacute;n adecuada para ti.',
    ),
    'cta_preventivo' => array('it' => 'Richiedi preventivo', 'en' => 'Request a quote', 'fr' => 'Demander un devis', 'es' => 'Solicitar presupuesto'),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => _t_raw(array('it'=>'CHI SIAMO','en'=>'ABOUT','fr'=>'À PROPOS','es'=>'QUIÉNES SOMOS')),
    'title'      => _t_raw(array('it'=>'Chi Siamo.','en'=>'About.','fr'=>'À Propos.','es'=>'Quiénes Somos.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Stats -->
<section class="stats-bar">
    <div class="stats-grid">
        <div class="stat-item"><div class="stat-number">15<span class="accent">+</span></div><div class="stat-label"><?php echo $_t($t['stat_anni']); ?></div></div>
        <div class="stat-item"><div class="stat-number">20<span class="accent">K</span>+</div><div class="stat-label"><?php echo $_t($t['stat_professionisti']); ?></div></div>
        <div class="stat-item"><div class="stat-number">50<span class="accent">+</span></div><div class="stat-label"><?php echo $_t($t['stat_citta']); ?></div></div>
        <div class="stat-item"><div class="stat-number">10<span class="accent">K</span>+</div><div class="stat-label"><?php echo $_t($t['stat_progetti']); ?></div></div>
    </div>
</section>

<!-- Storia -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['storia_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['storia_heading']); ?></h2>
        <div class="timeline">
            <div class="timeline-item"><div class="timeline-year">2009</div><p><?php echo $_t($t['timeline_2009']); ?></p></div>
            <div class="timeline-item"><div class="timeline-year">2015</div><p><?php echo $_t($t['timeline_2015']); ?></p></div>
            <div class="timeline-item"><div class="timeline-year">2021</div><p><?php echo $_t($t['timeline_2021']); ?></p></div>
            <div class="timeline-item"><div class="timeline-year">2023</div><p><?php echo $_t($t['timeline_2023']); ?></p></div>
            <div class="timeline-item"><div class="timeline-year">2024</div><p><?php echo $_t($t['timeline_2024']); ?></p></div>
        </div>
    </div>
</section>

<!-- Valori -->
<section class="services-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['valori_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['valori_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card"><div class="feature-number">01</div><h3 class="feature-title"><?php echo $_t($t['valore1_title']); ?></h3><p class="feature-text"><?php echo $_t($t['valore1_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">02</div><h3 class="feature-title"><?php echo $_t($t['valore2_title']); ?></h3><p class="feature-text"><?php echo $_t($t['valore2_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">03</div><h3 class="feature-title"><?php echo $_t($t['valore3_title']); ?></h3><p class="feature-text"><?php echo $_t($t['valore3_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">04</div><h3 class="feature-title"><?php echo $_t($t['valore4_title']); ?></h3><p class="feature-text"><?php echo $_t($t['valore4_text']); ?></p></div>
    </div>
</section>

<!-- Sedi -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['sedi_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:40px"><?php echo $_t($t['sedi_heading']); ?></h2>
    </div>
    <div class="coverage-grid container">
        <div class="coverage-country"><h4><?php echo $_t($t['italia']); ?></h4><p><?php echo $_t($t['italia_citta']); ?></p></div>
        <div class="coverage-country"><h4><?php echo $_t($t['francia']); ?></h4><p><?php echo $_t($t['francia_citta']); ?></p></div>
        <div class="coverage-country"><h4><?php echo $_t($t['spagna']); ?></h4><p><?php echo $_t($t['spagna_citta']); ?></p></div>
        <div class="coverage-country"><h4>UK</h4><p><?php echo $_t($t['uk_citta']); ?></p></div>
    </div>
</section>

<?php toa_component('cta-buttons', array(
    'title'    => $_t($t['cta_title']),
    'subtitle' => $_t($t['cta_subtitle']),
    'buttons'  => array(
        array('url' => home_url('/form-b2b/'), 'text' => $_t($t['cta_preventivo']), 'primary' => true),
        array('url' => 'https://wa.me/393517899225', 'text' => 'WhatsApp', 'target' => '_blank'),
    ),
)); ?>

<?php toa_component('footer'); ?>
