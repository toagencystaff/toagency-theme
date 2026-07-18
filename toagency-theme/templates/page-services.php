<?php
/**
 * Template Name: Services B2B
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Servizi completi per aziende, agenzie, marchi e produzioni.<br>Talent con immagine: modelli, hostess, steward, attori e creator.',
        'en' => 'Complete services for companies, agencies, brands and productions.<br>Image talent: models, hostesses, stewards, actors and creators.',
        'fr' => 'Services complets pour entreprises, agences, marques et productions.<br>Talents avec image : mannequins, h&ocirc;tesses, stewards, acteurs et cr&eacute;ateurs.',
        'es' => 'Servicios completos para empresas, agencias, marcas y producciones.<br>Talento con imagen: modelos, azafatas, promotores, actores y creadores.',
    ),
    // === SEO intro B2B (aggiunta 2026-07-18, da ADSEO STEP 8) ===
    'svc_intro_h2' => array(
        'it' => 'Modelle, hostess e casting per aziende, campagne ed eventi',
        'en' => 'Models, hostesses and casting for companies, campaigns and events',
        'fr' => 'Mannequins, h&ocirc;tesses et casting pour entreprises, campagnes et &eacute;v&eacute;nements',
        'es' => 'Modelos, azafatas y casting para empresas, campa&ntilde;as y eventos',
    ),
    'svc_intro_p1' => array(
        'it' => 'TOAgency &egrave; l\'agenzia di casting e modelle dedicata alle aziende: selezioniamo modelle, modelli, hostess, steward e attori per campagne pubblicitarie, eventi e produzioni. Un unico partner per trovare i volti e il personale giusti, in Italia e nei principali mercati europei.',
        'en' => 'TOAgency is the casting and modelling agency dedicated to companies: we select models, hostesses, stewards and actors for advertising campaigns, events and productions. A single partner to find the right faces and staff, in Italy and across the main European markets.',
        'fr' => 'TOAgency est l\'agence de casting et de mannequins d&eacute;di&eacute;e aux entreprises : nous s&eacute;lectionnons mannequins, h&ocirc;tesses, stewards et acteurs pour campagnes publicitaires, &eacute;v&eacute;nements et productions. Un seul partenaire pour trouver les visages et le personnel adapt&eacute;s, en Italie et sur les principaux march&eacute;s europ&eacute;ens.',
        'es' => 'TOAgency es la agencia de casting y modelos dedicada a las empresas: seleccionamos modelos, azafatas, promotores y actores para campa&ntilde;as publicitarias, eventos y producciones. Un &uacute;nico partner para encontrar los rostros y el personal adecuados, en Italia y en los principales mercados europeos.',
    ),
    'svc_intro_p2' => array(
        'it' => 'Grazie a un database nazionale e internazionale di talent selezionati, gestiamo casting, logistica e contratti dalla richiesta al set: profili in linea con il brief e tempi certi, in Italia e nei principali mercati europei.',
        'en' => 'Thanks to a national and international database of selected talent, we manage casting, logistics and contracts from the request to the set: profiles in line with the brief and reliable timelines, in Italy and across the main European markets.',
        'fr' => 'Gr&acirc;ce &agrave; une base de donn&eacute;es nationale et internationale de talents s&eacute;lectionn&eacute;s, nous g&eacute;rons casting, logistique et contrats de la demande au plateau : des profils conformes au brief et des d&eacute;lais garantis, en Italie et sur les principaux march&eacute;s europ&eacute;ens.',
        'es' => 'Gracias a una base de datos nacional e internacional de talento seleccionado, gestionamos casting, log&iacute;stica y contratos desde la solicitud hasta el set: perfiles acordes con el brief y plazos garantizados, en Italia y en los principales mercados europeos.',
    ),
    'servizi_eyebrow' => array('it' => 'I nostri servizi', 'en' => 'Our services', 'fr' => 'Nos services', 'es' => 'Nuestros servicios'),
    'servizi_heading' => array('it' => 'Un unico partner per tutto', 'en' => 'One partner for everything', 'fr' => 'Un seul partenaire pour tout', 'es' => 'Un &uacute;nico partner para todo'),
    'serv1_title' => array('it' => 'Modelli e Hostess', 'en' => 'Models &amp; Hostesses', 'fr' => 'Mannequins &amp; H&ocirc;tesses', 'es' => 'Modelos y Azafatas'),
    'serv1_text' => array(
        'it' => 'Database nazionale e internazionale per campagne moda, e-commerce, editoriali, sfilate, eventi e fiere. Profili di tutte le et&agrave; attivi in Italia, Francia, Spagna, UK.',
        'en' => 'National and international database for fashion campaigns, e-commerce, editorials, fashion shows, events and trade fairs. Profiles of all ages active in Italy, France, Spain, UK.',
        'fr' => 'Base de donn&eacute;es nationale et internationale pour campagnes mode, e-commerce, &eacute;ditoriaux, d&eacute;fil&eacute;s, &eacute;v&eacute;nements et salons. Profils de tous &acirc;ges actifs en Italie, France, Espagne, UK.',
        'es' => 'Base de datos nacional e internacional para campa&ntilde;as de moda, e-commerce, editoriales, desfiles, eventos y ferias. Perfiles de todas las edades activos en Italia, Francia, Espa&ntilde;a, UK.',
    ),
    'serv2_title' => array('it' => 'Attori e Comparse', 'en' => 'Actors &amp; Extras', 'fr' => 'Acteurs &amp; Figurants', 'es' => 'Actores y Figurantes'),
    'serv2_text' => array(
        'it' => 'Selezione di attori professionisti, comparse e volti per spot pubblicitari, cinema, teatro, TV e contenuti social. Casting mirati per ogni tipo di scena.',
        'en' => 'Selection of professional actors, extras and faces for commercials, cinema, theatre, TV and social content. Targeted casting for every type of scene.',
        'fr' => 'S&eacute;lection d\'acteurs professionnels, figurants et visages pour spots publicitaires, cin&eacute;ma, th&eacute;&acirc;tre, TV et contenus sociaux. Castings cibl&eacute;s pour chaque type de sc&egrave;ne.',
        'es' => 'Selecci&oacute;n de actores profesionales, figurantes y rostros para spots publicitarios, cine, teatro, TV y contenidos sociales. Castings dirigidos para cada tipo de escena.',
    ),
    'serv3_title' => array('it' => 'Influencer e Creator', 'en' => 'Influencers &amp; Creators', 'fr' => 'Influenceurs &amp; Cr&eacute;ateurs', 'es' => 'Influencers y Creadores'),
    'serv3_text' => array(
        'it' => 'Campagne di influencer marketing con profili selezionati per settore, target e engagement rate. Collaborazioni su Instagram, TikTok, YouTube.',
        'en' => 'Influencer marketing campaigns with profiles selected by industry, target audience and engagement rate. Collaborations on Instagram, TikTok, YouTube.',
        'fr' => 'Campagnes d\'influencer marketing avec profils s&eacute;lectionn&eacute;s par secteur, cible et taux d\'engagement. Collaborations sur Instagram, TikTok, YouTube.',
        'es' => 'Campa&ntilde;as de influencer marketing con perfiles seleccionados por sector, target y engagement rate. Colaboraciones en Instagram, TikTok, YouTube.',
    ),
    'serv4_title' => array('it' => 'Produzione Foto e Video', 'en' => 'Photo &amp; Video Production', 'fr' => 'Production Photo &amp; Vid&eacute;o', 'es' => 'Producci&oacute;n Foto y V&iacute;deo'),
    'serv4_text' => array(
        'it' => 'Produzione completa per e-commerce, social media e campagne ADV. Location scouting, permessi, logistica e post-produzione inclusi.',
        'en' => 'Complete production for e-commerce, social media and ADV campaigns. Location scouting, permits, logistics and post-production included.',
        'fr' => 'Production compl&egrave;te pour e-commerce, r&eacute;seaux sociaux et campagnes ADV. Rep&eacute;rage de lieux, autorisations, logistique et post-production inclus.',
        'es' => 'Producci&oacute;n completa para e-commerce, redes sociales y campa&ntilde;as ADV. Localizaci&oacute;n, permisos, log&iacute;stica y postproducci&oacute;n incluidos.',
    ),
    'cta_title' => array('it' => 'Richiedi un preventivo', 'en' => 'Request a quote', 'fr' => 'Demander un devis', 'es' => 'Solicitar un presupuesto'),
    'cta_subtitle' => array(
        'it' => 'Raccontaci il tuo progetto &mdash; rispondiamo in 30 minuti con le proposte migliori.',
        'en' => 'Tell us about your project &mdash; we respond in 30 minutes with the best proposals.',
        'fr' => 'Parlez-nous de votre projet &mdash; nous r&eacute;pondons en 30 minutes avec les meilleures propositions.',
        'es' => 'Cu&eacute;ntanos tu proyecto &mdash; respondemos en 30 minutos con las mejores propuestas.',
    ),
    'cta_compila' => array('it' => 'Compila il form', 'en' => 'Fill out the form', 'fr' => 'Remplir le formulaire', 'es' => 'Rellenar el formulario'),
    'how_richiesta' => array('it' => 'RICHIESTA', 'en' => 'REQUEST', 'fr' => 'DEMANDE', 'es' => 'SOLICITUD'),
    'how_analisi' => array('it' => 'ANALISI', 'en' => 'ANALYSIS', 'fr' => 'ANALYSE', 'es' => 'AN&Aacute;LISIS'),
    'how_proposte' => array('it' => 'PROPOSTE', 'en' => 'PROPOSALS', 'fr' => 'PROPOSITIONS', 'es' => 'PROPUESTAS'),
    'how_gestione' => array('it' => 'GESTIONE', 'en' => 'MANAGEMENT', 'fr' => 'GESTION', 'es' => 'GESTI&Oacute;N'),
    'how_tagline' => array(
        'it' => '20.000+ professionisti &bull; Preventivi in 2 ore &bull; Gestione completa &bull; Fatturazione chiara',
        'en' => '20,000+ professionals &bull; Quotes in 2 hours &bull; Full management &bull; Clear invoicing',
        'fr' => '20 000+ professionnels &bull; Devis en 2 heures &bull; Gestion compl&egrave;te &bull; Facturation transparente',
        'es' => '20.000+ profesionales &bull; Presupuestos en 2 horas &bull; Gesti&oacute;n completa &bull; Facturaci&oacute;n clara',
    ),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'SERVICES',
    'title'      => _t_raw(array('it'=>'Servizi.','en'=>'Services.','fr'=>'Services.','es'=>'Servicios.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- SEO intro B2B (2026-07-18) -->
<section class="why-section services-intro">
    <div class="container">
        <h2 class="section-heading"><?php echo $_t($t['svc_intro_h2']); ?></h2>
        <p><?php echo $_t($t['svc_intro_p1']); ?></p>
        <p><?php echo $_t($t['svc_intro_p2']); ?></p>
    </div>
</section>

<!-- Service Categories -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['servizi_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['servizi_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-number">01</div>
            <h3 class="feature-title"><?php echo $_t($t['serv1_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['serv1_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">02</div>
            <h3 class="feature-title"><?php echo $_t($t['serv2_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['serv2_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">03</div>
            <h3 class="feature-title"><?php echo $_t($t['serv3_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['serv3_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">04</div>
            <h3 class="feature-title"><?php echo $_t($t['serv4_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['serv4_text']); ?></p>
        </div>
    </div>
</section>

<?php toa_component('cta-buttons', array(
    'title'    => $_t($t['cta_title']),
    'subtitle' => $_t($t['cta_subtitle']),
    'buttons'  => array(
        array('url' => home_url('/form-b2b/'), 'text' => $_t($t['cta_compila']), 'primary' => true),
        array('url' => 'https://wa.me/393517899225', 'text' => 'WhatsApp', 'target' => '_blank'),
        array('url' => 'mailto:business@toagency.it', 'text' => 'Email'),
    ),
)); ?>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "1'", 'label' => $_t($t['how_richiesta'])),
        array('time' => "5'", 'label' => $_t($t['how_analisi'])),
        array('time' => '2h', 'label' => $_t($t['how_proposte'])),
        array('time' => 'H24', 'label' => $_t($t['how_gestione'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<?php toa_component('footer'); ?>
