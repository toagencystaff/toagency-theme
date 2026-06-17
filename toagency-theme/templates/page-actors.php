<?php
/**
 * Template Name: Actors
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Per produzioni, registi e case di produzione. Oltre 3.000 attori professionisti con showreel aggiornati.<br>Protagonisti, character, comparse, figurazioni speciali.',
        'en' => 'For productions, directors and production companies. Over 3,000 professional actors with updated showreels.<br>Lead roles, character actors, extras, special appearances.',
        'fr' => 'Pour productions, r&eacute;alisateurs et soci&eacute;t&eacute;s de production. Plus de 3 000 acteurs professionnels avec showreels &agrave; jour.<br>R&ocirc;les principaux, seconds r&ocirc;les, figurants, figurations sp&eacute;ciales.',
        'es' => 'Para producciones, directores y productoras. M&aacute;s de 3.000 actores profesionales con showreels actualizados.<br>Protagonistas, actores de car&aacute;cter, figurantes, figuraciones especiales.',
    ),
    'cta_heading' => array(
        'it' => 'Scegli come procedere',
        'en' => 'Choose how to proceed',
        'fr' => 'Choisissez comment proc&eacute;der',
        'es' => 'Elige c&oacute;mo proceder',
    ),
    'cta_cerco' => array(
        'it' => 'Cerco attori per il mio progetto',
        'en' => 'I\'m looking for actors for my project',
        'fr' => 'Je cherche des acteurs pour mon projet',
        'es' => 'Busco actores para mi proyecto',
    ),
    'cta_sono' => array(
        'it' => 'Sono un attore &mdash; cerco ruoli',
        'en' => 'I\'m an actor &mdash; looking for roles',
        'fr' => 'Je suis acteur &mdash; je cherche des r&ocirc;les',
        'es' => 'Soy actor &mdash; busco papeles',
    ),
    'cta_esplora' => array(
        'it' => 'Esplora showreel e profili',
        'en' => 'Explore showreels &amp; profiles',
        'fr' => 'Explorer les showreels et profils',
        'es' => 'Explorar showreels y perfiles',
    ),
    'how_step1' => array(
        'it' => 'DESCRIVI IL RUOLO',
        'en' => 'DESCRIBE THE ROLE',
        'fr' => 'D&Eacute;CRIVEZ LE R&Ocirc;LE',
        'es' => 'DESCRIBE EL PAPEL',
    ),
    'how_step2' => array('it' => 'TI CHIAMIAMO', 'en' => 'WE CALL YOU', 'fr' => 'NOUS VOUS APPELONS', 'es' => 'TE LLAMAMOS'),
    'how_step3' => array(
        'it' => 'HAI I SELF-TAPE',
        'en' => 'GET THE SELF-TAPES',
        'fr' => 'RECEVEZ LES SELF-TAPES',
        'es' => 'RECIBE LOS SELF-TAPES',
    ),
    'how_step4' => array('it' => 'SUPPORTO CONTINUO', 'en' => 'ONGOING SUPPORT', 'fr' => 'SUPPORT CONTINU', 'es' => 'SOPORTE CONTINUO'),
    'how_tagline' => array(
        'it' => 'Self-tape in 24 ore &bull; Showreel professionali &bull; Contratti ENPALS',
        'en' => 'Self-tapes in 24 hours &bull; Professional showreels &bull; ENPALS contracts',
        'fr' => 'Self-tapes en 24 heures &bull; Showreels professionnels &bull; Contrats ENPALS',
        'es' => 'Self-tapes en 24 horas &bull; Showreels profesionales &bull; Contratos ENPALS',
    ),
    'feat_eyebrow' => array('it' => 'Il nostro cast', 'en' => 'Our cast', 'fr' => 'Notre casting', 'es' => 'Nuestro casting'),
    'feat_heading' => array('it' => 'Ogni ruolo, ogni produzione', 'en' => 'Every role, every production', 'fr' => 'Chaque r&ocirc;le, chaque production', 'es' => 'Cada papel, cada producci&oacute;n'),
    'feat1_title' => array('it' => 'Database completo', 'en' => 'Complete database', 'fr' => 'Base de donn&eacute;es compl&egrave;te', 'es' => 'Base de datos completa'),
    'feat1_text' => array(
        'it' => 'Oltre 3.000 attori professionisti con showreel aggiornati e formazione teatrale e cinematografica.',
        'en' => 'Over 3,000 professional actors with updated showreels and theatre and film training.',
        'fr' => 'Plus de 3 000 acteurs professionnels avec showreels &agrave; jour et formation th&eacute;&acirc;trale et cin&eacute;matographique.',
        'es' => 'M&aacute;s de 3.000 actores profesionales con showreels actualizados y formaci&oacute;n teatral y cinematogr&aacute;fica.',
    ),
    'feat2_title' => array('it' => 'Tutti i ruoli', 'en' => 'All roles', 'fr' => 'Tous les r&ocirc;les', 'es' => 'Todos los papeles'),
    'feat2_text' => array(
        'it' => 'Protagonisti, co-protagonisti, character, comparse, figurazioni speciali. Tutte le et&agrave; e etnie.',
        'en' => 'Lead roles, co-leads, character actors, extras, special appearances. All ages and ethnicities.',
        'fr' => 'R&ocirc;les principaux, seconds r&ocirc;les, personnages, figurants, figurations sp&eacute;ciales. Tous &acirc;ges et ethnies.',
        'es' => 'Protagonistas, coprotagonistas, actores de car&aacute;cter, figurantes, figuraciones especiales. Todas las edades y etnias.',
    ),
    'feat3_title' => array('it' => 'Self-tape rapidi', 'en' => 'Fast self-tapes', 'fr' => 'Self-tapes rapides', 'es' => 'Self-tapes r&aacute;pidos'),
    'feat3_text' => array(
        'it' => 'Provini video professionali consegnati in 24-48 ore. Callback organizzati.',
        'en' => 'Professional video auditions delivered in 24-48 hours. Callbacks organized.',
        'fr' => 'Auditions vid&eacute;o professionnelles livr&eacute;es en 24-48 heures. Callbacks organis&eacute;s.',
        'es' => 'Audiciones en v&iacute;deo profesionales entregadas en 24-48 horas. Callbacks organizados.',
    ),
    'feat4_title' => array('it' => 'Gestione completa', 'en' => 'Full management', 'fr' => 'Gestion compl&egrave;te', 'es' => 'Gesti&oacute;n completa'),
    'feat4_text' => array(
        'it' => 'Contratti ENPALS, diritti d\'uso immagine, assicurazioni, logistica e trasporti.',
        'en' => 'ENPALS contracts, image usage rights, insurance, logistics and transport.',
        'fr' => 'Contrats ENPALS, droits d\'utilisation d\'image, assurances, logistique et transports.',
        'es' => 'Contratos ENPALS, derechos de uso de imagen, seguros, log&iacute;stica y transportes.',
    ),
    'serv_eyebrow' => array('it' => 'Produzioni', 'en' => 'Productions', 'fr' => 'Productions', 'es' => 'Producciones'),
    'serv_heading' => array('it' => 'Per ogni tipo di produzione', 'en' => 'For every type of production', 'fr' => 'Pour chaque type de production', 'es' => 'Para cada tipo de producci&oacute;n'),
    'serv_cinema' => array('it' => 'Film, cortometraggi, documentari', 'en' => 'Films, short films, documentaries', 'fr' => 'Films, courts-m&eacute;trages, documentaires', 'es' => 'Pel&iacute;culas, cortometrajes, documentales'),
    'serv_tv' => array('it' => 'Fiction, serie TV, programmi', 'en' => 'Fiction, TV series, shows', 'fr' => 'Fictions, s&eacute;ries TV, &eacute;missions', 'es' => 'Ficci&oacute;n, series de TV, programas'),
    'serv_adv_label' => array('it' => 'Pubblicit&agrave;', 'en' => 'Advertising', 'fr' => 'Publicit&eacute;', 'es' => 'Publicidad'),
    'serv_adv' => array('it' => 'Spot TV, web commercial', 'en' => 'TV commercials, web ads', 'fr' => 'Spots TV, publicit&eacute;s web', 'es' => 'Spots TV, anuncios web'),
    'serv_web' => array('it' => 'Produzioni digitali, streaming', 'en' => 'Digital productions, streaming', 'fr' => 'Productions num&eacute;riques, streaming', 'es' => 'Producciones digitales, streaming'),
    'serv_teatro_label' => array('it' => 'Teatro', 'en' => 'Theatre', 'fr' => 'Th&eacute;&acirc;tre', 'es' => 'Teatro'),
    'serv_teatro' => array('it' => 'Spettacoli, musical, performance', 'en' => 'Shows, musicals, performances', 'fr' => 'Spectacles, com&eacute;dies musicales, performances', 'es' => 'Espect&aacute;culos, musicales, performances'),
    'serv_corp' => array('it' => 'Video aziendali, convention', 'en' => 'Corporate videos, conventions', 'fr' => 'Vid&eacute;os d\'entreprise, conventions', 'es' => 'V&iacute;deos corporativos, convenciones'),
    'serv_tv_label' => array('it' => 'Televisione', 'en' => 'Television', 'fr' => 'T&eacute;l&eacute;vision', 'es' => 'Televisi&oacute;n'),
);

toa_component('header');

$images = array(
    array('src' => '/wp-content/uploads/2025/09/22-yo.jpeg', 'alt' => 'Attrice giovane 22 anni per film e spot pubblicitari — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/70-yo-asian.jpg', 'alt' => 'Attore senior asiatico 70 anni per cinema e serie TV — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/30-yo-punk.jpeg', 'alt' => 'Attore look punk 30 anni per spot e videoclip — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/9-yo-asian.jpeg', 'alt' => 'Bambino attore asiatico 9 anni per film e pubblicità — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/35-yo-african.jpeg', 'alt' => 'Attore africano 35 anni per cinema e commercial — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/24-yo.jpeg', 'alt' => 'Attore giovane 24 anni per serie TV e spot — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/40-yo-mediterranean.jpeg', 'alt' => 'Attore mediterraneo 40 anni per film e fiction — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/8-yo.jpeg', 'alt' => 'Bambino attore 8 anni per pubblicità e cinema — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/30-yo-girl.jpeg', 'alt' => 'Attrice 30 anni per spot pubblicitari e serie TV — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/58-yo.jpeg', 'alt' => 'Attore senior 58 anni per cinema e fiction — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/24-yosize.jpg', 'alt' => 'Attore plus size 24 anni per campagne e spot — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/45-yo.jpeg', 'alt' => 'Attore caratterista 45 anni per film e serie TV — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/20-yo-ispanic.jpeg', 'alt' => 'Attore ispanico 20 anni per spot e videoclip — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/32-yo.jpeg', 'alt' => 'Attrice 32 anni per cinema e pubblicità — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/24-yo-trap.jpeg', 'alt' => 'Attore urban 24 anni per videoclip e spot — casting TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/37-yo.jpeg', 'alt' => 'Attore maturo 37 anni per fiction e commercial — casting TOAgency'),
);
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'ACTORS',
    'title'      => _t_raw(array('it'=>'Attori.','en'=>'Actors.','fr'=>'Acteurs.','es'=>'Actores.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<?php toa_component('gallery-talent', array('images' => $images, 'columns' => 4)); ?>

<section class="split-cta">
    <div class="container">
        <h2 class="section-heading" style="text-align:center;margin:0 auto 16px"><?php echo $_t($t['cta_heading']); ?></h2>
        <div class="cta-buttons-row">
            <a href="<?php echo home_url('/form-b2b/'); ?>" class="btn-hero btn-hero-primary"><span><?php echo $_t($t['cta_cerco']); ?></span></a>
            <a href="<?php echo home_url('/collabora/'); ?>" class="btn-hero btn-hero-secondary"><span><?php echo $_t($t['cta_sono']); ?></span></a>
            <a href="https://toadatabase.it/it/talent/" target="_blank" class="btn-hero btn-hero-secondary"><span><?php echo $_t($t['cta_esplora']); ?></span></a>
        </div>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "1'", 'label' => $_t($t['how_step1'])),
        array('time' => "5'", 'label' => $_t($t['how_step2'])),
        array('time' => '24h', 'label' => $_t($t['how_step3'])),
        array('time' => 'H24', 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

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

<section class="services-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['serv_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['serv_heading']); ?></h2>
    </div>
    <div class="services-grid">
        <div class="service-card"><div class="service-name">Cinema</div><div class="service-desc"><?php echo $_t($t['serv_cinema']); ?></div></div>
        <div class="service-card"><div class="service-name"><?php echo $_t($t['serv_tv_label']); ?></div><div class="service-desc"><?php echo $_t($t['serv_tv']); ?></div></div>
        <div class="service-card"><div class="service-name"><?php echo $_t($t['serv_adv_label']); ?></div><div class="service-desc"><?php echo $_t($t['serv_adv']); ?></div></div>
        <div class="service-card"><div class="service-name">Web Series</div><div class="service-desc"><?php echo $_t($t['serv_web']); ?></div></div>
        <div class="service-card"><div class="service-name"><?php echo $_t($t['serv_teatro_label']); ?></div><div class="service-desc"><?php echo $_t($t['serv_teatro']); ?></div></div>
        <div class="service-card"><div class="service-name">Corporate</div><div class="service-desc"><?php echo $_t($t['serv_corp']); ?></div></div>
    </div>
</section>

<?php toa_component('footer'); ?>
