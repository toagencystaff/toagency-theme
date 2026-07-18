<?php
/**
 * Component: Header / Navigation
 * Included via toa_component('header') in all page templates
 */
$current_lang = toa_current_lang();

// Menu translations
$menu_items = array(
    array('slug' => '/models/',          'it' => 'Modelli',    'en' => 'Models',     'fr' => 'Modèles',    'es' => 'Modelos'),
    array('slug' => '/hostess-steward/', 'it' => 'Hostess',    'en' => 'Hostess',    'fr' => 'Hôtesses',   'es' => 'Azafatas'),
    array('slug' => '/actors/',          'it' => 'Attori',     'en' => 'Actors',     'fr' => 'Acteurs',    'es' => 'Actores'),
    array('slug' => '/visuals/',         'it' => 'Visuals',    'en' => 'Visuals',    'fr' => 'Visuels',    'es' => 'Visuales'),
    array('slug' => '/b2bservices/',     'it' => 'Servizi',    'en' => 'Services',   'fr' => 'Services',   'es' => 'Servicios'),
    array('slug' => '/casting/',         'it' => 'Casting',    'en' => 'Casting',    'fr' => 'Casting',    'es' => 'Casting'),
    array('slug' => '/talent-database/', 'it' => 'TALENTS', 'en' => 'TALENTS', 'fr' => 'TALENTS', 'es' => 'TALENTS'),
    array('slug' => '/about/',           'it' => 'About',      'en' => 'About',      'fr' => 'À propos',   'es' => 'Nosotros'),
    array('slug' => '/collabora/',       'it' => 'Collabora',  'en' => 'Join Us',    'fr' => 'Rejoignez',  'es' => 'Colabora'),
    array('slug' => '/student-program/', 'it' => 'Student',    'en' => 'Student',    'fr' => 'Student',    'es' => 'Student'),
    array('slug' => '/contact-us/',      'it' => 'Contatti',   'en' => 'Contact',    'fr' => 'Contact',    'es' => 'Contacto'),
);
$cta_item = array('slug' => '/form-b2b/', 'it' => 'Preventivo', 'en' => 'Get a Quote', 'fr' => 'Devis', 'es' => 'Presupuesto');
$mobile_cta = array('it' => 'Richiedi Preventivo', 'en' => 'Request a Quote', 'fr' => 'Demander un Devis', 'es' => 'Solicitar Presupuesto');

$lang = in_array($current_lang, array('it','en','fr','es')) ? $current_lang : 'it';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
<style>
html,body{background:#08080a!important;color:#f5f5f3!important}
a{color:inherit}
/* ── Language Switcher Buttons ── */
.nav-lang{display:flex;align-items:center;gap:4px}
.nav-lang-item{
    font-size:11px;
    font-weight:700;
    letter-spacing:.1em;
    text-decoration:none;
    padding:5px 10px;
    border-radius:100px;
    border:1px solid rgba(255,255,255,.22);
    color:rgba(255,255,255,.55);
    background:transparent;
    transition:color .2s,border-color .2s;
    cursor:pointer;
}
.nav-lang-item:hover{
    color:#fff;
    border-color:rgba(255,255,255,.55);
}
.nav-lang-item.active{
    color:#C5FF00;
    border-color:#C5FF00;
    background:rgba(197,255,0,.08);
}
</style>
<?php
// TOA SEO 2026-07 — hreflang: versioni-lingua della pagina corrente (dati WPML, solo traduzioni reali) + x-default
if (function_exists('icl_get_languages')) {
    $toa_alts = icl_get_languages('skip_missing=1&orderby=code');
    if (!empty($toa_alts) && count($toa_alts) > 1) {
        foreach ($toa_alts as $toa_l) {
            if (!empty($toa_l['url']) && !empty($toa_l['language_code'])) {
                echo '<link rel="alternate" hreflang="'.esc_attr($toa_l['language_code']).'" href="'.esc_url($toa_l['url']).'" />'."\n";
            }
        }
        if (!empty($toa_alts['it']['url'])) {
            echo '<link rel="alternate" hreflang="x-default" href="'.esc_url($toa_alts['it']['url']).'" />'."\n";
        }
    }
}
?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> style="background:#08080a!important;color:#f5f5f3!important">

<!-- Navigation -->
<nav class="nav" id="mainNav">
    <a href="<?php echo home_url('/'); ?>" class="nav-logo">
        <img src="/wp-content/uploads/2025/09/LogoToanew.png" alt="TOAgency — Agenzia casting per eventi, moda e pubblicità in Italia ed Europa" class="logo-white">
        <img src="/wp-content/uploads/2025/09/LogoToanewBlack.png" alt="TOAgency — Agenzia casting per eventi, moda e pubblicità in Italia ed Europa" class="logo-black">
    </a>
    <div class="nav-links">
        <?php foreach ($menu_items as $item) : ?>
        <a href="<?php echo home_url($item['slug']); ?>"><?php echo esc_html($item[$lang]); ?></a>
        <?php endforeach; ?>
        <a href="<?php echo home_url($cta_item['slug']); ?>" class="nav-cta-btn"><?php echo esc_html($cta_item[$lang]); ?></a>
    </div>
    <!-- Language Switcher -->
    <?php if (function_exists('icl_get_languages')) :
        $langs = icl_get_languages('skip_missing=0&orderby=code');
        if (!empty($langs) && count($langs) > 1) : ?>
        <div class="nav-lang">
            <?php foreach ($langs as $l) : ?>
                <?php if ($l['active']) : ?>
                    <span class="nav-lang-item active"><?php echo strtoupper($l['language_code']); ?></span>
                <?php else : ?>
                    <a href="<?php echo esc_url($l['url']); ?>" class="nav-lang-item"><?php echo strtoupper($l['language_code']); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; endif; ?>
    <div class="nav-hamburger" id="navHamburger">
        <span></span><span></span><span></span>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-inner">
        <?php foreach ($menu_items as $item) : ?>
        <a href="<?php echo home_url($item['slug']); ?>"><?php echo esc_html($item[$lang]); ?></a>
        <?php endforeach; ?>
        <div class="mobile-menu-cta">
            <a href="<?php echo home_url($cta_item['slug']); ?>" class="btn-hero btn-hero-primary"><?php echo esc_html($mobile_cta[$lang]); ?></a>
        </div>
    </div>
</div>
