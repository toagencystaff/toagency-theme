<?php
/**
 * Template Name: Collabora (B2C)
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Guida completa per registrarsi come talent o professionista.<br>Leggi i requisiti prima di procedere &mdash; supporto via WhatsApp.',
        'en' => 'Complete guide to registering as a talent or professional.<br>Read the requirements before proceeding &mdash; WhatsApp support available.',
        'fr' => 'Guide compl&egrave;te pour s\'inscrire en tant que talent ou professionnel.<br>Lisez les exigences avant de proc&eacute;der &mdash; support via WhatsApp.',
        'es' => 'Gu&iacute;a completa para registrarse como talento o profesional.<br>Lee los requisitos antes de continuar &mdash; soporte v&iacute;a WhatsApp.',
    ),
    'warning' => array(
        'it' => '<strong>&#9888;&#65039; IMPORTANTE:</strong> Se le foto non rispettano i requisiti o sono troppo pesanti, il modulo si blocca. Prepara tutto il materiale seguendo le indicazioni. Massimo 1MB per foto, formato verticale obbligatorio.',
        'en' => '<strong>&#9888;&#65039; IMPORTANT:</strong> If photos don\'t meet requirements or are too heavy, the form will freeze. Prepare all material following the guidelines. Maximum 1MB per photo, portrait format required.',
        'fr' => '<strong>&#9888;&#65039; IMPORTANT :</strong> Si les photos ne respectent pas les exigences ou sont trop lourdes, le formulaire se bloque. Pr&eacute;parez tout le mat&eacute;riel en suivant les indications. Maximum 1 Mo par photo, format portrait obligatoire.',
        'es' => '<strong>&#9888;&#65039; IMPORTANTE:</strong> Si las fotos no cumplen los requisitos o son demasiado pesadas, el formulario se bloquea. Prepara todo el material siguiendo las indicaciones. M&aacute;ximo 1MB por foto, formato vertical obligatorio.',
    ),
    'path_eyebrow' => array('it' => 'Scegli il tuo percorso', 'en' => 'Choose your path', 'fr' => 'Choisissez votre parcours', 'es' => 'Elige tu camino'),
    'path_heading' => array('it' => 'Talent o Backstage?', 'en' => 'Talent or Backstage?', 'fr' => 'Talent ou Backstage ?', 'es' => '&iquest;Talento o Backstage?'),
    'talent_title' => array('it' => 'Lavorare come Talent', 'en' => 'Work as Talent', 'fr' => 'Travailler comme Talent', 'es' => 'Trabajar como Talento'),
    'talent_text' => array(
        'it' => 'Per modelli, attori, influencer, creator, hostess, steward, comparse, bambini.',
        'en' => 'For models, actors, influencers, creators, hostesses, stewards, extras, children.',
        'fr' => 'Pour mannequins, acteurs, influenceurs, cr&eacute;ateurs, h&ocirc;tesses, stewards, figurants, enfants.',
        'es' => 'Para modelos, actores, influencers, creadores, azafatas, promotores, figurantes, ni&ntilde;os.',
    ),
    'talent_req' => array(
        'it' => '&bull; Foto verticali obbligatorie (max 1MB)<br>&bull; Senza watermark, bordi o loghi<br>&bull; Per minori: contatti del genitore<br>&bull; Self-tape consigliato',
        'en' => '&bull; Portrait photos required (max 1MB)<br>&bull; No watermarks, borders or logos<br>&bull; For minors: parent\'s contact info<br>&bull; Self-tape recommended',
        'fr' => '&bull; Photos portrait obligatoires (max 1 Mo)<br>&bull; Sans watermark, bordures ni logos<br>&bull; Pour les mineurs : coordonn&eacute;es du parent<br>&bull; Self-tape recommand&eacute;',
        'es' => '&bull; Fotos verticales obligatorias (m&aacute;x 1MB)<br>&bull; Sin marcas de agua, bordes ni logos<br>&bull; Para menores: contacto del padre/madre<br>&bull; Self-tape recomendado',
    ),
    'registrati' => array('it' => 'Registrati ora', 'en' => 'Register now', 'fr' => 'Inscrivez-vous', 'es' => 'Reg&iacute;strate ahora'),
    'video_tutorial' => array('it' => 'Video tutorial', 'en' => 'Video tutorial', 'fr' => 'Tutoriel vid&eacute;o', 'es' => 'Video tutorial'),
    'backstage_title' => array('it' => 'Lavorare nel Backstage', 'en' => 'Work in Backstage', 'fr' => 'Travailler en Backstage', 'es' => 'Trabajar en Backstage'),
    'backstage_text' => array(
        'it' => 'Per fotografi, videomaker, stylist, MUA, parrucchieri.',
        'en' => 'For photographers, videographers, stylists, MUAs, hairdressers.',
        'fr' => 'Pour photographes, vid&eacute;astes, stylistes, maquilleurs, coiffeurs.',
        'es' => 'Para fot&oacute;grafos, vide&oacute;grafos, estilistas, maquilladores, peluqueros.',
    ),
    'backstage_req' => array(
        'it' => '&bull; Compilazione rapida, senza impegno<br>&bull; Inserisci il tuo portfolio online<br>&bull; Opportunit&agrave; costanti in tutta Italia<br>&bull; Network professionale',
        'en' => '&bull; Quick registration, no commitment<br>&bull; Add your online portfolio<br>&bull; Constant opportunities across Italy<br>&bull; Professional network',
        'fr' => '&bull; Inscription rapide, sans engagement<br>&bull; Ajoutez votre portfolio en ligne<br>&bull; Opportunit&eacute;s constantes dans toute l\'Italie<br>&bull; R&eacute;seau professionnel',
        'es' => '&bull; Registro r&aacute;pido, sin compromiso<br>&bull; A&ntilde;ade tu portfolio online<br>&bull; Oportunidades constantes en toda Italia<br>&bull; Red profesional',
    ),
    'how_step1' => array('it' => 'PREPARA MATERIALE', 'en' => 'PREPARE MATERIAL', 'fr' => 'PR&Eacute;PAREZ LE MAT&Eacute;RIEL', 'es' => 'PREPARA MATERIAL'),
    'how_step2' => array('it' => 'COMPILA FORM', 'en' => 'FILL OUT FORM', 'fr' => 'REMPLISSEZ LE FORMULAIRE', 'es' => 'COMPLETA EL FORMULARIO'),
    'how_step3' => array('it' => 'CARICA FOTO', 'en' => 'UPLOAD PHOTOS', 'fr' => 'T&Eacute;L&Eacute;CHARGEZ LES PHOTOS', 'es' => 'SUBE FOTOS'),
    'how_step4' => array('it' => 'SEI ONLINE!', 'en' => 'YOU\'RE ONLINE!', 'fr' => 'VOUS &Ecirc;TES EN LIGNE !', 'es' => '&iexcl;EST&Aacute;S ONLINE!'),
    'how_tagline' => array(
        'it' => 'Registrazione gratuita &bull; Database internazionale &bull; Opportunit&agrave; verificate &bull; Pagamenti sicuri',
        'en' => 'Free registration &bull; International database &bull; Verified opportunities &bull; Secure payments',
        'fr' => 'Inscription gratuite &bull; Base de donn&eacute;es internationale &bull; Opportunit&eacute;s v&eacute;rifi&eacute;es &bull; Paiements s&eacute;curis&eacute;s',
        'es' => 'Registro gratuito &bull; Base de datos internacional &bull; Oportunidades verificadas &bull; Pagos seguros',
    ),
    'photo_eyebrow' => array('it' => 'Requisiti foto', 'en' => 'Photo requirements', 'fr' => 'Exigences photo', 'es' => 'Requisitos de fotos'),
    'photo_heading' => array('it' => 'Come preparare il materiale', 'en' => 'How to prepare your material', 'fr' => 'Comment pr&eacute;parer le mat&eacute;riel', 'es' => 'C&oacute;mo preparar el material'),
    'ph1_title' => array('it' => 'Formato verticale', 'en' => 'Portrait format', 'fr' => 'Format portrait', 'es' => 'Formato vertical'),
    'ph1_text' => array(
        'it' => 'Obbligatorio per tutte le foto. Le orizzontali vengono tagliate male nel database.',
        'en' => 'Required for all photos. Landscape images get badly cropped in the database.',
        'fr' => 'Obligatoire pour toutes les photos. Les horizontales sont mal recadr&eacute;es dans la base de donn&eacute;es.',
        'es' => 'Obligatorio para todas las fotos. Las horizontales se recortan mal en la base de datos.',
    ),
    'ph2_title' => array('it' => 'Peso max 1MB', 'en' => 'Max 1MB size', 'fr' => 'Poids max 1 Mo', 'es' => 'Peso m&aacute;x 1MB'),
    'ph2_text' => array(
        'it' => 'Invia le foto a te stesso via WhatsApp (come foto, non documento), poi scarica e carica.',
        'en' => 'Send photos to yourself via WhatsApp (as photo, not document), then download and upload.',
        'fr' => 'Envoyez les photos &agrave; vous-m&ecirc;me via WhatsApp (en photo, pas en document), puis t&eacute;l&eacute;chargez.',
        'es' => 'Env&iacute;ate las fotos por WhatsApp (como foto, no documento), luego descarga y sube.',
    ),
    'ph3_title' => array('it' => 'No watermark', 'en' => 'No watermark', 'fr' => 'Pas de watermark', 'es' => 'Sin marca de agua'),
    'ph3_text' => array(
        'it' => 'Senza bordi, scritte, loghi o misure sul corpo. Foto pulite.',
        'en' => 'No borders, text, logos or body measurements. Clean photos.',
        'fr' => 'Sans bordures, textes, logos ni mensurations. Photos nettes.',
        'es' => 'Sin bordes, textos, logos ni medidas corporales. Fotos limpias.',
    ),
    'ph4_title' => array('it' => 'Qualit&agrave; minima', 'en' => 'Minimum quality', 'fr' => 'Qualit&eacute; minimale', 'es' => 'Calidad m&iacute;nima'),
    'ph4_text' => array(
        'it' => 'Foto nitide, ben illuminate, professionali o semi-professionali.',
        'en' => 'Sharp, well-lit, professional or semi-professional photos.',
        'fr' => 'Photos nettes, bien &eacute;clair&eacute;es, professionnelles ou semi-professionnelles.',
        'es' => 'Fotos n&iacute;tidas, bien iluminadas, profesionales o semi-profesionales.',
    ),
    'comm_eyebrow' => array('it' => 'Community', 'en' => 'Community', 'fr' => 'Communaut&eacute;', 'es' => 'Comunidad'),
    'comm_heading' => array('it' => 'Unisciti alla community', 'en' => 'Join the community', 'fr' => 'Rejoignez la communaut&eacute;', 'es' => '&Uacute;nete a la comunidad'),
    'comm_whatsapp' => array('it' => 'Gruppi per zona', 'en' => 'Groups by area', 'fr' => 'Groupes par zone', 'es' => 'Grupos por zona'),
    'comm_facebook' => array('it' => 'Gruppo casting', 'en' => 'Casting group', 'fr' => 'Groupe casting', 'es' => 'Grupo de casting'),
    'comm_casting' => array('it' => 'Annunci attivi', 'en' => 'Active listings', 'fr' => 'Annonces actives', 'es' => 'Anuncios activos'),
    'comm_database' => array('it' => 'Esplora talenti', 'en' => 'Explore talents', 'fr' => 'Explorer les talents', 'es' => 'Explorar talentos'),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'REGISTRATION',
    'title'      => 'Registration.',
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Warning -->
<section class="why-section" style="padding-bottom:20px">
    <div class="container">
        <div class="alert-box">
            <?php echo $_t($t['warning']); ?>
        </div>
    </div>
</section>

<!-- Two paths -->
<section class="services-section" style="padding-top:20px">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['path_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['path_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <h3 class="feature-title"><?php echo $_t($t['talent_title']); ?></h3>
            <p class="feature-text" style="margin-bottom:20px"><?php echo $_t($t['talent_text']); ?></p>
            <p class="feature-text"><?php echo $_t($t['talent_req']); ?></p>
            <div style="margin-top:20px;display:flex;gap:8px;flex-wrap:wrap">
                <a href="https://toadatabase.it/it/collaborate/" target="_blank" class="btn-hero btn-hero-primary" style="padding:12px 20px;font-size:0.75rem"><?php echo $_t($t['registrati']); ?></a>
                <a href="https://youtube.com/shorts/z56PfLU8TAs" target="_blank" class="btn-hero btn-hero-secondary" style="padding:12px 20px;font-size:0.75rem"><?php echo $_t($t['video_tutorial']); ?></a>
            </div>
        </div>
        <div class="feature-card">
            <h3 class="feature-title"><?php echo $_t($t['backstage_title']); ?></h3>
            <p class="feature-text" style="margin-bottom:20px"><?php echo $_t($t['backstage_text']); ?></p>
            <p class="feature-text"><?php echo $_t($t['backstage_req']); ?></p>
            <div style="margin-top:20px;display:flex;gap:8px;flex-wrap:wrap">
                <a href="<?php echo home_url('/backstage/'); ?>" class="btn-hero btn-hero-primary" style="padding:12px 20px;font-size:0.75rem"><?php echo $_t($t['registrati']); ?></a>
            </div>
        </div>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'steps' => array(
        array('time' => '1', 'label' => $_t($t['how_step1'])),
        array('time' => '2', 'label' => $_t($t['how_step2'])),
        array('time' => '3', 'label' => $_t($t['how_step3'])),
        array('time' => '&#10003;', 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<!-- Photo requirements -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['photo_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['photo_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card"><div class="feature-number">01</div><h3 class="feature-title"><?php echo $_t($t['ph1_title']); ?></h3><p class="feature-text"><?php echo $_t($t['ph1_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">02</div><h3 class="feature-title"><?php echo $_t($t['ph2_title']); ?></h3><p class="feature-text"><?php echo $_t($t['ph2_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">03</div><h3 class="feature-title"><?php echo $_t($t['ph3_title']); ?></h3><p class="feature-text"><?php echo $_t($t['ph3_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">04</div><h3 class="feature-title"><?php echo $_t($t['ph4_title']); ?></h3><p class="feature-text"><?php echo $_t($t['ph4_text']); ?></p></div>
    </div>
</section>

<!-- Community -->
<section class="services-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['comm_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['comm_heading']); ?></h2>
    </div>
    <div class="services-grid">
        <a href="https://toagency.it/itacommunities/" target="_blank" class="service-card"><div class="service-icon">&#128172;</div><div class="service-name">WhatsApp</div><div class="service-desc"><?php echo $_t($t['comm_whatsapp']); ?></div></a>
        <a href="https://www.facebook.com/groups/hostessmodelscastingcalls" target="_blank" class="service-card"><div class="service-icon">&#128101;</div><div class="service-name">Facebook</div><div class="service-desc"><?php echo $_t($t['comm_facebook']); ?></div></a>
        <a href="<?php echo home_url('/casting/'); ?>" class="service-card"><div class="service-icon">&#127916;</div><div class="service-name">Casting</div><div class="service-desc"><?php echo $_t($t['comm_casting']); ?></div></a>
        <a href="https://tiktok.com/@toagency" target="_blank" class="service-card"><div class="service-icon">&#127925;</div><div class="service-name">TikTok</div><div class="service-desc">@toagency</div></a>
        <a href="https://toadatabase.it" target="_blank" class="service-card"><div class="service-icon">&#127760;</div><div class="service-name">Database</div><div class="service-desc"><?php echo $_t($t['comm_database']); ?></div></a>
    </div>
</section>

<?php toa_component('footer'); ?>
