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
// 2026-11-04 (ticket #811, TEMA-Events): la voce «Hostess» porta al mini sito TOA Events (/events/ nella lingua), non piu' a /hostess-steward/
// (la pagina /hostess-steward/ resta online com'e' per Google). Se l'hub non fosse pubblicato resta il link di prima.
$toa_ev_hub = array('it' => 275542, 'en' => 275543, 'fr' => 275544, 'es' => 275545);
if (isset($toa_ev_hub[$current_lang]) && get_post_status($toa_ev_hub[$current_lang]) === 'publish') {
    $menu_items[1] = array('url' => get_permalink($toa_ev_hub[$current_lang]), 'it' => 'Hostess', 'en' => 'Hostess', 'fr' => 'Hôtesses', 'es' => 'Azafatas');
}
// 2026-11-04 (ticket #807, TEMA Ticket 807): voce «Creator» dopo Hostess, verso il mini sito /creators/ nella lingua.
// Compare SOLO quando la pagina e' pubblicata (opzione toa_creators_site): mai una voce verso una bozza o una pagina che non c'e'.
$toa_cr_map = get_option('toa_creators_site', array());
$toa_cr_id  = isset($toa_cr_map['home'][$current_lang]) ? (int) $toa_cr_map['home'][$current_lang] : 0;
if ($toa_cr_id && get_post_status($toa_cr_id) === 'publish') {
    array_splice($menu_items, 2, 0, array(array('url' => get_permalink($toa_cr_id), 'it' => 'Creator', 'en' => 'Creators', 'fr' => 'Créateurs', 'es' => 'Creadores')));
}
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
        <a href="<?php echo isset($item['url']) ? esc_url($item['url']) : home_url($item['slug']); ?>"><?php echo esc_html($item[$lang]); ?></a>
        <?php endforeach; ?>
        <a href="<?php echo home_url($cta_item['slug']); ?>" class="nav-cta-btn"><?php echo esc_html($cta_item[$lang]); ?></a>
    </div>
    <!-- Language Switcher -->
    <?php if (function_exists('icl_get_languages')) :
        $langs = icl_get_languages('skip_missing=0&orderby=code');
        // 2026-08-04 marco — le pagine-template custom (crew-database, crew-self-edit...) NON sono
        // tradotte in WPML: /fr/<slug>/ fa 301 sull'italiano e lo switcher rimanda alla home lingua,
        // perdendo lingua ed eventuale ?uuid=. Se WPML propone la home per una lingua ma NON siamo
        // in home, restiamo sulla pagina corrente forzando ?lang= (meccanismo toa_current_lang, 11/07).
        $toa_cur_url = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . wp_unslash($_SERVER['REQUEST_URI']);
        if (!empty($langs) && count($langs) > 1) : ?>
        <div class="nav-lang">
            <?php foreach ($langs as $l) :
                $toa_lurl = $l['url'];
                $toa_p    = untrailingslashit(strtok($toa_lurl, '?'));
                $toa_home = array(
                    untrailingslashit(strtok(apply_filters('wpml_permalink', home_url('/'), $l['language_code']), '?')),
                    untrailingslashit(home_url('/' . $l['language_code'] . '/')),
                    untrailingslashit(home_url('/')),
                );
                // ...oppure il link punta alla pagina che stiamo gia guardando: in quel caso
                // preserviamo la query corrente (es. ?uuid= della scheda crew) invece di perderla.
                if (!is_front_page() && (in_array($toa_p, $toa_home, true)
                    || $toa_p === untrailingslashit(strtok($toa_cur_url, '?')))) {
                    $toa_lurl = add_query_arg('lang', $l['language_code'], $toa_cur_url);
                }
            ?>
                <?php if ($l['language_code'] === $current_lang) : ?>
                    <span class="nav-lang-item active"><?php echo strtoupper($l['language_code']); ?></span>
                <?php else : ?>
                    <a href="<?php echo esc_url($toa_lurl); ?>" class="nav-lang-item"><?php echo strtoupper($l['language_code']); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php endif; endif; ?>
    <div class="nav-hamburger" id="navHamburger">
        <span></span><span></span><span></span>
    </div>
</nav>
<?php /* 2026-11-04 (ticket #807, TEMA Ticket 807): fra 1025 e ~1580 px le voci del menu non stavano in una riga e tagliavano
         lingue e «Preventivo». Se non ci stanno si passa al menu a tendina, a qualunque larghezza. */ ?>
<style>.nav.nav-compact .nav-links{display:none!important}.nav.nav-compact .nav-hamburger{display:flex!important}</style>
<script>
(function(){
    var nav = document.getElementById('mainNav'); if (!nav) return;
    function chk(){ nav.classList.remove('nav-compact'); if (nav.scrollWidth > nav.clientWidth + 1) nav.classList.add('nav-compact'); }
    chk(); window.addEventListener('resize', chk); window.addEventListener('load', chk);
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(chk);
})();
</script>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-inner">
        <?php foreach ($menu_items as $item) : ?>
        <a href="<?php echo isset($item['url']) ? esc_url($item['url']) : home_url($item['slug']); ?>"><?php echo esc_html($item[$lang]); ?></a>
        <?php endforeach; ?>
        <div class="mobile-menu-cta">
            <a href="<?php echo home_url($cta_item['slug']); ?>" class="btn-hero btn-hero-primary"><?php echo esc_html($mobile_cta[$lang]); ?></a>
        </div>
    </div>
</div>
