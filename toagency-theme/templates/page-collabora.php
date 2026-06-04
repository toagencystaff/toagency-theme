<?php
/**
 * Template Name: Collabora (B2C)
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Scegli il tuo profilo e registrati.',
        'en' => 'Choose your profile and register.',
        'fr' => 'Choisissez votre profil et inscrivez-vous.',
        'es' => 'Elige tu perfil y reg&iacute;strate.',
    ),
    'path_eyebrow' => array('it' => 'Scegli il tuo percorso', 'en' => 'Choose your path', 'fr' => 'Choisissez votre parcours', 'es' => 'Elige tu camino'),
    'path_heading' => array('it' => 'Talent o Backstage?', 'en' => 'Talent or Backstage?', 'fr' => 'Talent ou Backstage ?', 'es' => '&iquest;Talento o Backstage?'),

    'talent_title' => array('it' => 'Lavorare come Talent', 'en' => 'Work as Talent', 'fr' => 'Travailler comme Talent', 'es' => 'Trabajar como Talento'),
    'talent_tag' => array(
        'it' => 'Appari davanti alla camera',
        'en' => 'You appear in front of the camera',
        'fr' => 'Vous &ecirc;tes devant la cam&eacute;ra',
        'es' => 'Apareces frente a la c&aacute;mara',
    ),
    'talent_roles' => array(
        'it' => 'Modello &middot; Hostess/Steward &middot; Attore &middot; Comparsa &middot; Influencer &middot; UGC Creator',
        'en' => 'Model &middot; Host/Hostess &middot; Actor &middot; Extra &middot; Influencer &middot; UGC Creator',
        'fr' => 'Mannequin &middot; H&ocirc;te/H&ocirc;tesse &middot; Acteur &middot; Figurant &middot; Influenceur &middot; Cr&eacute;ateur UGC',
        'es' => 'Modelo &middot; Azafato/Azafata &middot; Actor &middot; Figurante &middot; Influencer &middot; Creador UGC',
    ),

    'backstage_title' => array('it' => 'Lavorare nel Backstage', 'en' => 'Work in Backstage', 'fr' => 'Travailler en Backstage', 'es' => 'Trabajar en Backstage'),
    'backstage_tag' => array(
        'it' => 'Lavori dietro le quinte',
        'en' => 'You work behind the scenes',
        'fr' => 'Vous travaillez dans les coulisses',
        'es' => 'Trabajas detr&aacute;s de c&aacute;mara',
    ),
    'backstage_roles' => array(
        'it' => 'Fotografo &middot; Videomaker &middot; Content Creator &middot; Truccatore &middot; Hairstylist &middot; Stylist &middot; DJ &middot; Assistente di produzione &middot; Art Director',
        'en' => 'Photographer &middot; Videomaker &middot; Content Creator &middot; Makeup Artist &middot; Hairstylist &middot; Stylist &middot; DJ &middot; Production Assistant &middot; Art Director',
        'fr' => 'Photographe &middot; Vid&eacute;aste &middot; Cr&eacute;ateur de contenu &middot; Maquilleur &middot; Coiffeur &middot; Styliste &middot; DJ &middot; Assistant de production &middot; Directeur Artistique',
        'es' => 'Fot&oacute;grafo &middot; Vide&oacute;grafo &middot; Creador de contenido &middot; Maquillador &middot; Peluquero &middot; Estilista &middot; DJ &middot; Asistente de producci&oacute;n &middot; Director de Arte',
    ),

    'registrati' => array('it' => 'Registrati ora', 'en' => 'Register now', 'fr' => 'Inscrivez-vous', 'es' => 'Reg&iacute;strate ahora'),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'REGISTRATION',
    'title'      => _t_raw(array('it'=>'Iscrizione.','en'=>'Registration.','fr'=>'Inscription.','es'=>'Inscripción.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Two paths -->
<section class="services-section" style="padding-top:40px">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['path_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['path_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <h3 class="feature-title"><?php echo $_t($t['talent_title']); ?></h3>
            <p class="feature-text" style="opacity:.7;margin-bottom:16px"><?php echo $_t($t['talent_tag']); ?></p>
            <p class="feature-text"><?php echo $_t($t['talent_roles']); ?></p>
            <div style="margin-top:24px">
                <a href="<?php echo home_url('/registrati-talent/'); ?>" class="btn-hero btn-hero-primary" style="padding:12px 20px;font-size:0.75rem"><?php echo $_t($t['registrati']); ?></a>
            </div>
        </div>
        <div class="feature-card">
            <h3 class="feature-title"><?php echo $_t($t['backstage_title']); ?></h3>
            <p class="feature-text" style="opacity:.7;margin-bottom:16px"><?php echo $_t($t['backstage_tag']); ?></p>
            <p class="feature-text"><?php echo $_t($t['backstage_roles']); ?></p>
            <div style="margin-top:24px">
                <a href="<?php echo home_url('/registrati-crew/'); ?>" class="btn-hero btn-hero-primary" style="padding:12px 20px;font-size:0.75rem"><?php echo $_t($t['registrati']); ?></a>
            </div>
        </div>
    </div>
</section>

<?php toa_component('footer'); ?>
