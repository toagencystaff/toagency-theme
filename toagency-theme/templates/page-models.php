<?php
/**
 * Template Name: Models
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Per aziende, brand e produzioni. Oltre 5.000 modelli e modelle attivi in Italia, Francia, Spagna e UK.<br>Da 6 a 80 anni, per ogni tipo di campagna. Disponibili entro 48 ore.',
        'en' => 'For companies, brands and productions. Over 5,000 active models in Italy, France, Spain and UK.<br>From 6 to 80 years old, for every type of campaign. Available within 48 hours.',
        'fr' => 'Pour entreprises, marques et productions. Plus de 5 000 mannequins actifs en Italie, France, Espagne et UK.<br>De 6 &agrave; 80 ans, pour tout type de campagne. Disponibles sous 48 heures.',
        'es' => 'Para empresas, marcas y producciones. M&aacute;s de 5.000 modelos activos en Italia, Francia, Espa&ntilde;a y UK.<br>De 6 a 80 a&ntilde;os, para todo tipo de campa&ntilde;a. Disponibles en 48 horas.',
    ),
    'cta_heading' => array(
        'it' => 'Scegli come procedere',
        'en' => 'Choose how to proceed',
        'fr' => 'Choisissez comment proc&eacute;der',
        'es' => 'Elige c&oacute;mo proceder',
    ),
    'cta_subtitle' => array(
        'it' => 'Richiedi un casting personalizzato o candidati per entrare nel team',
        'en' => 'Request a custom casting or apply to join the team',
        'fr' => 'Demandez un casting personnalis&eacute; ou postulez pour rejoindre l\'&eacute;quipe',
        'es' => 'Solicita un casting personalizado o postula para unirte al equipo',
    ),
    'cta_cerco' => array(
        'it' => 'Cerco modelli per il mio progetto',
        'en' => 'I\'m looking for models for my project',
        'fr' => 'Je cherche des mannequins pour mon projet',
        'es' => 'Busco modelos para mi proyecto',
    ),
    'cta_sono' => array(
        'it' => 'Sono un/a modello/a &mdash; cerco lavoro',
        'en' => 'I\'m a model &mdash; looking for work',
        'fr' => 'Je suis mannequin &mdash; je cherche du travail',
        'es' => 'Soy modelo &mdash; busco trabajo',
    ),
    'cta_esplora' => array(
        'it' => 'Esplora book e profili',
        'en' => 'Explore books &amp; profiles',
        'fr' => 'Explorer les books et profils',
        'es' => 'Explorar books y perfiles',
    ),
    'how_step1' => array(
        'it' => 'DESCRIVI IL PROGETTO',
        'en' => 'DESCRIBE THE PROJECT',
        'fr' => 'D&Eacute;CRIVEZ LE PROJET',
        'es' => 'DESCRIBE EL PROYECTO',
    ),
    'how_step2' => array(
        'it' => 'TI CHIAMIAMO',
        'en' => 'WE CALL YOU',
        'fr' => 'NOUS VOUS APPELONS',
        'es' => 'TE LLAMAMOS',
    ),
    'how_step3' => array(
        'it' => 'HAI I BOOK',
        'en' => 'GET THE BOOKS',
        'fr' => 'RECEVEZ LES BOOKS',
        'es' => 'RECIBE LOS BOOKS',
    ),
    'how_step4' => array(
        'it' => 'SUPPORTO CONTINUO',
        'en' => 'ONGOING SUPPORT',
        'fr' => 'SUPPORT CONTINU',
        'es' => 'SOPORTE CONTINUO',
    ),
    'how_tagline' => array(
        'it' => 'Book in 24 ore &bull; Misure certificate &bull; Contratti CCNL &bull; Fatturazione unica',
        'en' => 'Books in 24 hours &bull; Certified measurements &bull; CCNL contracts &bull; Single invoicing',
        'fr' => 'Books en 24 heures &bull; Mensurations certifi&eacute;es &bull; Contrats CCNL &bull; Facturation unique',
        'es' => 'Books en 24 horas &bull; Medidas certificadas &bull; Contratos CCNL &bull; Facturaci&oacute;n &uacute;nica',
    ),
    'feat_eyebrow' => array('it' => 'I nostri punti di forza', 'en' => 'Our strengths', 'fr' => 'Nos points forts', 'es' => 'Nuestros puntos fuertes'),
    'feat_heading' => array('it' => 'Qualit&agrave; e velocit&agrave;', 'en' => 'Quality and speed', 'fr' => 'Qualit&eacute; et rapidit&eacute;', 'es' => 'Calidad y rapidez'),
    'feat1_title' => array('it' => 'Database completo', 'en' => 'Complete database', 'fr' => 'Base de donn&eacute;es compl&egrave;te', 'es' => 'Base de datos completa'),
    'feat1_text' => array(
        'it' => 'Oltre 5.000 modelli verificati e sempre aggiornati. Bambini, ragazzi, adulti, senior, plus size, fitness.',
        'en' => 'Over 5,000 verified and always up-to-date models. Children, teens, adults, seniors, plus size, fitness.',
        'fr' => 'Plus de 5 000 mannequins v&eacute;rifi&eacute;s et toujours &agrave; jour. Enfants, ados, adultes, seniors, grande taille, fitness.',
        'es' => 'M&aacute;s de 5.000 modelos verificados y siempre actualizados. Ni&ntilde;os, adolescentes, adultos, seniors, tallas grandes, fitness.',
    ),
    'feat2_title' => array('it' => 'Diversit&agrave; e inclusione', 'en' => 'Diversity and inclusion', 'fr' => 'Diversit&eacute; et inclusion', 'es' => 'Diversidad e inclusi&oacute;n'),
    'feat2_text' => array(
        'it' => 'Tutte le etnie, ogni tipo di bellezza rappresentata. Casting mirati per ogni progetto e ogni brand.',
        'en' => 'All ethnicities, every type of beauty represented. Targeted casting for every project and brand.',
        'fr' => 'Toutes les ethnies, chaque type de beaut&eacute; repr&eacute;sent&eacute;. Castings cibl&eacute;s pour chaque projet et chaque marque.',
        'es' => 'Todas las etnias, cada tipo de belleza representada. Castings dirigidos para cada proyecto y marca.',
    ),
    'feat3_title' => array('it' => 'Casting veloce', 'en' => 'Fast casting', 'fr' => 'Casting rapide', 'es' => 'Casting r&aacute;pido'),
    'feat3_text' => array(
        'it' => 'Selezione e conferma in meno di 24 ore. Polaroid e self-tape professionali su richiesta.',
        'en' => 'Selection and confirmation in under 24 hours. Professional polaroids and self-tapes on request.',
        'fr' => 'S&eacute;lection et confirmation en moins de 24 heures. Polaroids et self-tapes professionnels sur demande.',
        'es' => 'Selecci&oacute;n y confirmaci&oacute;n en menos de 24 horas. Polaroids y self-tapes profesionales bajo demanda.',
    ),
    'feat4_title' => array('it' => 'Servizio completo', 'en' => 'Full service', 'fr' => 'Service complet', 'es' => 'Servicio completo'),
    'feat4_text' => array(
        'it' => 'Gestione contratti, diritti immagine, fatturazione. Un project manager per ogni progetto.',
        'en' => 'Contract management, image rights, invoicing. A dedicated project manager for every project.',
        'fr' => 'Gestion des contrats, droits &agrave; l\'image, facturation. Un chef de projet d&eacute;di&eacute; pour chaque projet.',
        'es' => 'Gesti&oacute;n de contratos, derechos de imagen, facturaci&oacute;n. Un project manager dedicado para cada proyecto.',
    ),
    'serv_eyebrow' => array('it' => 'Servizi', 'en' => 'Services', 'fr' => 'Services', 'es' => 'Servicios'),
    'serv_heading' => array('it' => 'Per ogni tipo di produzione', 'en' => 'For every type of production', 'fr' => 'Pour chaque type de production', 'es' => 'Para cada tipo de producci&oacute;n'),
    'serv_ecomm' => array('it' => 'Cataloghi online, marketplace, lookbook', 'en' => 'Online catalogues, marketplace, lookbook', 'fr' => 'Catalogues en ligne, marketplace, lookbook', 'es' => 'Cat&aacute;logos online, marketplace, lookbook'),
    'serv_fashion' => array('it' => 'Campagne, sfilate, editoriali', 'en' => 'Campaigns, fashion shows, editorials', 'fr' => 'Campagnes, d&eacute;fil&eacute;s, &eacute;ditoriaux', 'es' => 'Campa&ntilde;as, desfiles, editoriales'),
    'serv_adv' => array('it' => 'Spot TV, web ADV, billboard', 'en' => 'TV commercials, web ADV, billboards', 'fr' => 'Spots TV, publicit&eacute; web, affichage', 'es' => 'Spots TV, publicidad web, vallas publicitarias'),
    'serv_social' => array('it' => 'Content per Instagram, TikTok', 'en' => 'Content for Instagram, TikTok', 'fr' => 'Contenu pour Instagram, TikTok', 'es' => 'Contenido para Instagram, TikTok'),
    'serv_corp' => array('it' => 'Brochure aziendali, website', 'en' => 'Corporate brochures, websites', 'fr' => 'Brochures d\'entreprise, sites web', 'es' => 'Folletos corporativos, sitios web'),
    'serv_life' => array('it' => 'Food, travel, beauty, family', 'en' => 'Food, travel, beauty, family', 'fr' => 'Food, voyage, beaut&eacute;, famille', 'es' => 'Food, viajes, belleza, familia'),
);

toa_component('header');

$images = array(
    array('src' => '/wp-content/uploads/2025/09/female-white-7-yo.jpg', 'alt' => 'Bambina modella caucasica 7 anni — casting baby model TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/male-caucasian-35-yo.jpg', 'alt' => 'Modello uomo caucasico 35 anni per shooting e campagne — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/female-italian-22-yo.jpg', 'alt' => 'Modella italiana 22 anni per fashion ed e-commerce — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/male-black-24-yo.jpg', 'alt' => 'Modello uomo black 24 anni per campagne pubblicitarie — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/female-redhair-24.jpg', 'alt' => 'Modella capelli rossi 24 anni per beauty e moda — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/female-white-70-yo.jpg', 'alt' => 'Modella senior caucasica 70 anni per campagne lifestyle — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/male-white-23-yo.jpg', 'alt' => 'Modello uomo caucasico 23 anni per fashion e advertising — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/male-asian-6-yo.jpg', 'alt' => 'Bambino modello asiatico 6 anni — casting baby model TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/female-magrebina-25-yo.jpg', 'alt' => 'Modella nordafricana 25 anni per moda e campagne — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/female-mulatta-22-yo.jpg', 'alt' => 'Modella mixed-race 22 anni per e-commerce e shooting — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/male-brasiliano-24-yo.jpg', 'alt' => 'Modello brasiliano 24 anni per campagne e sfilate — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/male-white-55-yo.jpg', 'alt' => 'Modello uomo maturo 55 anni per campagne corporate — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/female-caucasian-38-yo.jpg', 'alt' => 'Modella caucasica 38 anni per advertising e cataloghi — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/female-afro-6-yo.jpg', 'alt' => 'Bambina modella afro 6 anni — casting baby model TOAgency'),
);
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'MODELS',
    'title'      => _t_raw(array('it'=>'Modelli.','en'=>'Models.','fr'=>'Mannequins.','es'=>'Modelos.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<?php toa_component('gallery-talent', array('images' => $images, 'columns' => 4)); ?>

<!-- Split CTA -->
<section class="split-cta">
    <div class="container">
        <h2 class="section-heading" style="text-align:center;margin:0 auto 16px"><?php echo $_t($t['cta_heading']); ?></h2>
        <p class="cta-subtitle"><?php echo $_t($t['cta_subtitle']); ?></p>
        <div class="cta-buttons-row">
            <a href="<?php echo home_url('/form-b2b/'); ?>" class="btn-hero btn-hero-primary"><span><?php echo $_t($t['cta_cerco']); ?></span></a>
            <a href="<?php echo home_url('/b-t-l/'); ?>" class="btn-hero btn-hero-secondary"><span><?php echo $_t($t['cta_sono']); ?></span></a>
            <a href="https://toadatabase.it/it/talent/" target="_blank" class="btn-hero btn-hero-secondary"><span><?php echo $_t($t['cta_esplora']); ?></span></a>
        </div>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "1'", 'label' => $_t($t['how_step1'])),
        array('time' => "5'", 'label' => $_t($t['how_step2'])),
        array('time' => '2h', 'label' => $_t($t['how_step3'])),
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
        <div class="feature-card">
            <div class="feature-number">01</div>
            <h3 class="feature-title"><?php echo $_t($t['feat1_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat1_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">02</div>
            <h3 class="feature-title"><?php echo $_t($t['feat2_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat2_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">03</div>
            <h3 class="feature-title"><?php echo $_t($t['feat3_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat3_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">04</div>
            <h3 class="feature-title"><?php echo $_t($t['feat4_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat4_text']); ?></p>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="services-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['serv_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['serv_heading']); ?></h2>
    </div>
    <div class="services-grid">
        <div class="service-card"><div class="service-name">E-Commerce</div><div class="service-desc"><?php echo $_t($t['serv_ecomm']); ?></div></div>
        <div class="service-card"><div class="service-name">Fashion</div><div class="service-desc"><?php echo $_t($t['serv_fashion']); ?></div></div>
        <div class="service-card"><div class="service-name">Advertising</div><div class="service-desc"><?php echo $_t($t['serv_adv']); ?></div></div>
        <div class="service-card"><div class="service-name">Social Media</div><div class="service-desc"><?php echo $_t($t['serv_social']); ?></div></div>
        <div class="service-card"><div class="service-name">Corporate</div><div class="service-desc"><?php echo $_t($t['serv_corp']); ?></div></div>
        <div class="service-card"><div class="service-name">Lifestyle</div><div class="service-desc"><?php echo $_t($t['serv_life']); ?></div></div>
    </div>
</section>

<?php toa_component('footer'); ?>
