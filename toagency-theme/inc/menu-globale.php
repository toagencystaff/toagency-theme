<?php
/**
 * MENU GLOBALE toagency.it — fase 1 (ticket CRM #789, 05/11/2026, chat «TEMA Ticket 789»)
 * Piano: CRM TOAGENCY/PROGETTO_MENU_GLOBALE/00_PIANO_FASE1.md · bozza: artifact «Menu globale TOAgency».
 *
 * UNA SOLA FONTE delle voci: header del sito, barra nera di /events/ e /creators/, footer, menu del telefono e card della home.
 *
 * ANTEPRIMA: visitatori e Google vedono il sito di oggi. Il nuovo si vede SOLO
 *   - con ?anteprima=menu (cookie 7 giorni; ?anteprima=off lo toglie), oppure
 *   - da amministratore WordPress loggato (anche lui puo' spegnerlo con ?anteprima=off).
 * Le risposte in anteprima escono con no-cache + DONOTCACHEPAGE + cookie wpSGCacheBypass (cache SiteGround saltata)
 * e noindex: la versione nuova non puo' finire in cache per tutti.
 * Si accende per tutti SOLO con l'ok di Marco: TOA_MENU_GLOBALE_PER_TUTTI = true (una riga).
 *
 * Link: SOLO pagine che esistono e sono pubblicate. Ogni voce ha il percorso italiano; nelle altre lingue si usa la
 * traduzione WPML, se manca il percorso «alt» (pagina della sezione in quella lingua), se manca anche quella la pagina
 * italiana. Pagina italiana inesistente o non pubblicata = voce tolta (mai 404). Mappa in cache 6 ore (transient).
 * Prezzi «da»: MAI scritti qui. Li legge assets/js/menu-globale.js dagli endpoint CRM (come la home di /events/).
 */
if (!defined('ABSPATH')) exit;
if (!defined('TOA_MENU_GLOBALE_PER_TUTTI')) define('TOA_MENU_GLOBALE_PER_TUTTI', false);

/* ───────────── interruttore anteprima ───────────── */
function toa_mg_param() {
    $q = isset($_GET['anteprima']) ? strtolower(substr((string) $_GET['anteprima'], 0, 8)) : '';
    return in_array($q, array('menu', 'off'), true) ? $q : '';
}
function toa_mg_attivo() {
    static $on = null;
    if ($on !== null) return $on;
    if (is_admin() || (function_exists('wp_doing_ajax') && wp_doing_ajax())) return $on = false;
    if (TOA_MENU_GLOBALE_PER_TUTTI) return $on = true;
    $q = toa_mg_param();
    if ($q !== '') return $on = ($q === 'menu');
    $c = isset($_COOKIE['toa_anteprima_menu']) ? (string) $_COOKIE['toa_anteprima_menu'] : '';
    if ($c === 'menu') return $on = true;
    if ($c === 'off') return $on = false;
    return $on = (function_exists('is_user_logged_in') && is_user_logged_in() && current_user_can('manage_options'));
}
add_action('init', function () {
    if (TOA_MENU_GLOBALE_PER_TUTTI || is_admin()) return;
    $q = toa_mg_param();
    if ($q === '' || headers_sent()) return;
    $exp = $q === 'menu' ? time() + 7 * DAY_IN_SECONDS : time() + 30 * DAY_IN_SECONDS;
    $o = array('expires' => $exp, 'path' => '/', 'secure' => is_ssl(), 'httponly' => false, 'samesite' => 'Lax');
    setcookie('toa_anteprima_menu', $q, $o);
    // SiteGround: con questo cookie la cache dinamica non serve e non salva la pagina (vale solo per chi ha il cookie)
    if ($q === 'menu') setcookie('wpSGCacheBypass', '1', $o);
    else setcookie('wpSGCacheBypass', '', array('expires' => time() - DAY_IN_SECONDS, 'path' => '/', 'secure' => is_ssl(), 'samesite' => 'Lax'));
    $_COOKIE['toa_anteprima_menu'] = $q;
}, 1);
add_action('template_redirect', function () {
    if (TOA_MENU_GLOBALE_PER_TUTTI) return;
    if (!toa_mg_attivo() && toa_mg_param() === '') return;
    if (!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE', true);
    if (!headers_sent()) {
        nocache_headers();
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0, private');
        header('X-Robots-Tag: noindex, nofollow');
        header('X-TOA-Menu: ' . (toa_mg_attivo() ? 'anteprima' : 'oggi'));
    }
}, 0);
add_filter('body_class', function ($c) { if (toa_mg_attivo()) $c[] = 'tmg-on'; return $c; });
add_action('wp_enqueue_scripts', function () {
    if (!toa_mg_attivo()) return;
    $css = get_theme_file_path('assets/css/menu-globale.css');
    $js  = get_theme_file_path('assets/js/menu-globale.js');
    wp_enqueue_style('toa-menu-globale', get_theme_file_uri('assets/css/menu-globale.css'), array(), file_exists($css) ? filemtime($css) : '1');
    wp_enqueue_script('toa-menu-globale', get_theme_file_uri('assets/js/menu-globale.js'), array(), file_exists($js) ? filemtime($js) : '1', true);
    $ui = toa_mg_dati(); $ui = $ui['ui'];
    wp_localize_script('toa-menu-globale', 'TOA_MG', array(
        'lang' => toa_mg_lang(),
        'da' => toa_mg_t($ui['da']), 'unita_h' => toa_mg_t($ui['unita_h']), 'unita_c' => toa_mg_t($ui['unita_c']),
    ));
}, 30);

/* ───────────── lingua e testi ───────────── */
function toa_mg_lang() {
    $l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
    return in_array($l, array('it', 'en', 'fr', 'es'), true) ? $l : 'it';
}
function toa_mg_t($a, $lang = null) {
    if (!is_array($a)) return (string) $a;
    $lang = $lang ? $lang : toa_mg_lang();
    return isset($a[$lang]) ? $a[$lang] : (isset($a['it']) ? $a['it'] : '');
}

/* ───────────── URL per lingua (mai 404) ───────────── */
$GLOBALS['toa_mg_url_map'] = array();
function toa_mg_url($path, $lang = null, $alt = '', $lang_param = false) {
    $lang = $lang ? $lang : toa_mg_lang();
    $m = &$GLOBALS['toa_mg_url_map'];
    if (!isset($m[$lang])) {
        $c = get_transient('toa_mg_url_v1_' . $lang);
        $m[$lang] = is_array($c) ? $c : array();
        $m[$lang]['__dirty'] = false;
    }
    $key = $path . '|' . $alt . '|' . ($lang_param ? 1 : 0);
    if (array_key_exists($key, $m[$lang])) return $m[$lang][$key];
    $out = toa_mg_url_calc($path, $lang, $alt, $lang_param);
    $m[$lang][$key] = $out;
    $m[$lang]['__dirty'] = true;
    if (!has_action('shutdown', 'toa_mg_url_save')) add_action('shutdown', 'toa_mg_url_save');
    return $out;
}
function toa_mg_url_save() {
    foreach ($GLOBALS['toa_mg_url_map'] as $lang => $m) {
        if (empty($m['__dirty'])) continue;
        unset($m['__dirty']);
        set_transient('toa_mg_url_v1_' . $lang, $m, 6 * HOUR_IN_SECONDS);
    }
}
function toa_mg_page_it($base) {
    $slug = trim($base, '/');
    if ($slug === '') return (int) get_option('page_on_front');
    $cur = apply_filters('wpml_current_language', null);
    if ($cur && $cur !== 'it') do_action('wpml_switch_language', 'it');
    $p = get_page_by_path($slug, OBJECT, 'page');
    if (!$p) $p = get_page_by_path($slug, OBJECT, 'post');
    if ($cur && $cur !== 'it') do_action('wpml_switch_language', $cur);
    return ($p && $p->post_status === 'publish') ? (int) $p->ID : 0;
}
function toa_mg_permalink_it($id) {
    $cur = apply_filters('wpml_current_language', null);
    if ($cur && $cur !== 'it') do_action('wpml_switch_language', 'it');
    $u = get_permalink($id);
    if ($cur && $cur !== 'it') do_action('wpml_switch_language', $cur);
    return $u;
}
function toa_mg_url_calc($path, $lang, $alt, $lang_param) {
    if (preg_match('#^https?://#', $path)) return $path;
    $frag = ''; $qs = '';
    if (($h = strpos($path, '#')) !== false) { $frag = substr($path, $h); $path = substr($path, 0, $h); }
    if (($q = strpos($path, '?')) !== false) { $qs = substr($path, $q); $path = substr($path, 0, $q); }
    if (strpos($path, '/crm_toagency/') === 0) {   // pagine del CRM: la lingua va nel parametro
        return untrailingslashit(get_option('home')) . $path . '?lang=' . $lang;
    }
    $id = toa_mg_page_it($path);
    if (!$id) return '';
    $type = get_post_type($id);
    $url = '';
    if ($lang !== 'it') {
        $tid = (int) apply_filters('wpml_object_id', $id, $type, false, $lang);
        if ($tid && $tid !== $id && get_post_status($tid) === 'publish') $url = get_permalink($tid);
        if (!$url && $alt) {
            $aid = toa_mg_page_it($alt);
            $atid = $aid ? (int) apply_filters('wpml_object_id', $aid, get_post_type($aid), false, $lang) : 0;
            if ($atid && $atid !== $aid && get_post_status($atid) === 'publish') { $url = get_permalink($atid); $qs = ''; $frag = ''; }
        }
    }
    if (!$url) {
        $url = toa_mg_permalink_it($id);
        if ($lang !== 'it' && $lang_param) $qs = ($qs ? $qs . '&' : '?') . 'lang=' . $lang;
    }
    if (!$url) return '';
    return $url . $qs . $frag;
}

/* ───────────── le voci (UNA fonte) ───────────── */
function toa_mg_dati() {
    static $d = null;
    if ($d !== null) return $d;
    $L = function ($path, $n, $alt = '', $sub = null, $lp = false) { return array('p' => $path, 'n' => $n, 'alt' => $alt, 'sub' => $sub, 'lp' => $lp); };
    $T = function ($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };
    $d = array();
    $d['sezioni'] = array(
        'eventi' => array(
            'n' => $T('Eventi e hostess', 'Events & hostesses', 'Événements et hôtesses', 'Eventos y azafatas'),
            'tag' => $T('Hostess, steward, promoter e staff', 'Hostesses, stewards, promoters and staff', 'Hôtesses, stewards, promoteurs et staff', 'Azafatas, auxiliares, promotores y personal'),
            'brand' => 'TOA Events', 'hub' => '/events/',
            'hub_t' => $T('Vai a TOA Events', 'Go to TOA Events', 'Aller sur TOA Events', 'Ir a TOA Events'),
            'card' => array('tipo' => 'hostess', 'l' => $T('Hostess per eventi', 'Event hostesses', 'Hôtesses pour vos événements', 'Azafatas para eventos'),
                            'big' => $T('Staff e prezzi', 'Staff & prices', 'Personnel et tarifs', 'Personal y precios'),
                            'note' => $T('prezzi per città', 'prices by city', 'tarifs par ville', 'precios por ciudad')),
            'cols' => array(
                array('t' => $T('Hostess per città', 'Hostesses by city', 'Hôtesses par ville', 'Azafatas por ciudad'), 'links' => array(
                    $L('/agenzia-hostess-torino/', $T('Torino', 'Turin', 'Turin', 'Turín'), '/events/torino/'),
                    $L('/agenzia-hostess-milano/', $T('Milano', 'Milan', 'Milan', 'Milán'), '/events/milano/'),
                    $L('/agenzia-hostess-roma/', $T('Roma', 'Rome', 'Rome', 'Roma'), '/events/roma/'),
                    $L('/agenzia-hostess-genova/', $T('Genova', 'Genoa', 'Gênes', 'Génova'), '/events/genova/'),
                    $L('/agenzia-hostess-bologna/', $T('Bologna', 'Bologna', 'Bologne', 'Bolonia'), '/events/bologna/'),
                    $L('/agenzia-hostess-verona/', $T('Verona', 'Verona', 'Vérone', 'Verona'), '/events/verona/'),
                    $L('/events/staff-e-prezzi/', $T('Staff e prezzi in tutte le città', 'Staff and prices in every city', 'Personnel et tarifs dans toutes les villes', 'Personal y precios en todas las ciudades')),
                )),
                array('t' => $T('Fiere ed eventi', 'Trade shows & events', 'Salons et événements', 'Ferias y eventos'), 'links' => array(
                    $L('/hostess-fiere/', $T('Hostess per fiere', 'Trade show hostesses', 'Hôtesses pour salons', 'Azafatas para ferias'), '/events/personale-fiere/'),
                    $L('/hostess-atp-finals-torino/', $T('ATP Finals Torino', 'ATP Finals Turin', 'ATP Finals Turin', 'ATP Finals Turín')),
                    $L('/hostess-eicma/', $T('EICMA Milano', 'EICMA Milan', 'EICMA Milan', 'EICMA Milán')),
                    $L('/hostess-eima-bologna/', $T('EIMA Bologna', 'EIMA Bologna', 'EIMA Bologne', 'EIMA Bolonia')),
                    $L('/hostess-francia/', $T('Hostess in Francia', 'Hostesses in France', 'Hôtesses en France', 'Azafatas en Francia'), '/events/parigi/'),
                    $L('/events/tutti-i-servizi/', $T('Tutti i servizi per eventi', 'All event services', 'Tous les services événementiels', 'Todos los servicios para eventos'), '',
                       $T('bar, musica, tecnica', 'bar, music, tech', 'bar, musique, technique', 'bar, música, técnica')),
                )),
            ),
            'cross' => array('p' => '/creators/?pacchetto=coverage', 't' => $T('Un creator allo stand', 'A creator at your stand', 'Un créateur sur votre stand', 'Un creador en tu stand'),
                             'd' => $T('Video e storie del tuo evento.', 'Videos and stories from your event.', 'Vidéos et stories de votre événement.', 'Vídeos e historias de tu evento.')),
        ),
        'creator' => array(
            'n' => $T('Creator', 'Creators', 'Créateurs', 'Creadores'),
            'tag' => $T('Video UGC, social, eventi', 'UGC videos, social media, events', 'Vidéos UGC, réseaux sociaux, événements', 'Vídeos UGC, redes sociales, eventos'),
            'brand' => 'TOA Creators', 'hub' => '/creators/',
            'hub_t' => $T('Vai a TOA Creators', 'Go to TOA Creators', 'Aller sur TOA Creators', 'Ir a TOA Creators'),
            'card' => array('tipo' => 'creator', 'l' => $T('Video UGC per le ads', 'UGC videos for ads', 'Vidéos UGC pour vos pubs', 'Vídeos UGC para anuncios'),
                            'big' => $T('Pacchetti', 'Packages', 'Formules', 'Paquetes'),
                            'note' => $T('profili di esempio con video', 'example profiles with videos', 'profils d’exemple en vidéo', 'perfiles de ejemplo con vídeo')),
            'cols' => array(
                array('t' => $T('Cosa puoi comprare', 'What you can book', 'Ce que vous pouvez commander', 'Qué puedes contratar'), 'links' => array(
                    $L('/creators/?pacchetto=ugc', $T('Video UGC per le tue ads', 'UGC videos for your ads', 'Vidéos UGC pour vos publicités', 'Vídeos UGC para tus anuncios')),
                    $L('/creators/?pacchetto=profilo', $T('Contenuti sul profilo del creator', 'Posts on the creator’s profile', 'Contenus sur le profil du créateur', 'Contenido en el perfil del creador')),
                    $L('/creators/?pacchetto=coverage', $T('Live coverage di eventi e stand', 'Live coverage of events and stands', 'Couverture live d’événements et de stands', 'Cobertura en directo de eventos y stands')),
                    $L('/creators/?pacchetto=multi', $T('Campagna con più creator', 'Multi-creator campaign', 'Campagne avec plusieurs créateurs', 'Campaña con varios creadores')),
                )),
                array('t' => $T('Servizi e settori', 'Services & sectors', 'Services et secteurs', 'Servicios y sectores'), 'links' => array(
                    $L('/creators/#servizi', $T('Gestione dei social e pubblicità', 'Social media and ads management', 'Gestion des réseaux sociaux et des pubs', 'Gestión de redes sociales y anuncios')),
                    $L('/creators/#vetrina', $T('Creator per food, hotel, immobiliare', 'Creators for food, hotels, real estate', 'Créateurs pour food, hôtels, immobilier', 'Creadores para restauración, hoteles, inmobiliaria')),
                    $L('/agenzia-influencer-italia/', $T('Agenzia influencer Italia', 'Influencer agency in Italy', 'Agence d’influenceurs en Italie', 'Agencia de influencers en Italia')),
                )),
            ),
            'cross' => array('p' => '/visuals/', 't' => $T('Foto e video del prodotto', 'Product photos & videos', 'Photos et vidéos produit', 'Fotos y vídeos de producto'),
                             'd' => $T('Lo shooting accanto ai contenuti dei creator.', 'A shoot to go with your creator content.', 'Un shooting en complément des contenus créateurs.', 'Una sesión de fotos junto al contenido de los creadores.')),
        ),
        'modelli' => array(
            'n' => $T('Modelli e attori', 'Models & actors', 'Mannequins et acteurs', 'Modelos y actores'),
            'tag' => $T('Moda, pubblicità, cinema, TV', 'Fashion, advertising, film, TV', 'Mode, publicité, cinéma, télé', 'Moda, publicidad, cine, TV'),
            'brand' => $T('Modelli e attori', 'Models & actors', 'Mannequins et acteurs', 'Modelos y actores'), 'hub' => '/models/',
            'hub_t' => $T('Vai a Modelli', 'Go to Models', 'Voir les mannequins', 'Ver modelos'),
            'card' => array('tipo' => 'testo', 'l' => $T('Shooting, campagne, set', 'Shoots, campaigns, sets', 'Shootings, campagnes, tournages', 'Sesiones, campañas, rodajes'),
                            'big' => $T('Su misura', 'Tailored', 'Sur mesure', 'A medida'),
                            'note' => $T('selezione e preventivo per ogni progetto', 'shortlist and quote for every project', 'sélection et devis pour chaque projet', 'selección y presupuesto para cada proyecto')),
            'cols' => array(
                array('t' => $T('Modelli', 'Models', 'Mannequins', 'Modelos'), 'links' => array(
                    $L('/models/', $T('Modelli e modelle', 'Male & female models', 'Mannequins hommes et femmes', 'Modelos hombres y mujeres')),
                    $L('/casting-modelle-over-50/', $T('Modelle over 50', 'Models over 50', 'Mannequins de plus de 50 ans', 'Modelos de más de 50 años')),
                    $L('/children-kids/', $T('Bambini e ragazzi', 'Kids & teens', 'Enfants et ados', 'Niños y adolescentes')),
                    $L('/agenzia-modelle-roma/', $T('Modelle a Roma', 'Models in Rome', 'Mannequins à Rome', 'Modelos en Roma')),
                    $L('/agenzia-modelle-napoli/', $T('Modelle a Napoli', 'Models in Naples', 'Mannequins à Naples', 'Modelos en Nápoles')),
                    $L('/agenzia-modelle-torino/', $T('Modelle a Torino', 'Models in Turin', 'Mannequins à Turin', 'Modelos en Turín')),
                )),
                array('t' => $T('Attori e comparse', 'Actors & extras', 'Acteurs et figurants', 'Actores y figurantes'), 'links' => array(
                    $L('/actors/', $T('Attori', 'Actors', 'Acteurs', 'Actores')),
                    $L('/extras/', $T('Comparse', 'Extras', 'Figurants', 'Figurantes')),
                    $L('/agenzia-comparse-milano/', $T('Comparse a Milano', 'Extras in Milan', 'Figurants à Milan', 'Figurantes en Milán')),
                    $L('/agenzia-comparse-roma/', $T('Comparse a Roma', 'Extras in Rome', 'Figurants à Rome', 'Figurantes en Roma')),
                    $L('/agenzia-comparse-torino/', $T('Comparse a Torino', 'Extras in Turin', 'Figurants à Turin', 'Figurantes en Turín')),
                )),
            ),
            'cross' => array('p' => '/creators/?pacchetto=ugc', 't' => $T('Video UGC con i creator', 'UGC videos with creators', 'Vidéos UGC avec des créateurs', 'Vídeos UGC con creadores'),
                             'd' => $T('Contenuti pronti per le tue ads.', 'Ready-to-run content for your ads.', 'Des contenus prêts pour vos pubs.', 'Contenido listo para tus anuncios.')),
        ),
        'foto' => array(
            'n' => $T('Foto e video', 'Photo & video', 'Photo et vidéo', 'Foto y vídeo'),
            'tag' => $T('Shooting, e-commerce, crew', 'Shoots, e-commerce, crew', 'Shootings, e-commerce, équipes', 'Sesiones, e-commerce, equipo'),
            'brand' => $T('Produzioni', 'Productions', 'Productions', 'Producciones'), 'hub' => '/visuals/',
            'hub_t' => $T('Vai alle produzioni', 'See our productions', 'Voir nos productions', 'Ver producciones'),
            'card' => array('tipo' => 'testo', 'l' => $T('Foto e video prodotto', 'Product photo & video', 'Photo et vidéo produit', 'Foto y vídeo de producto'),
                            'big' => $T('Listino', 'Price list', 'Tarifs', 'Tarifas'),
                            'note' => $T('e-commerce, sulla pagina Produzioni', 'e-commerce, on the Productions page', 'e-commerce, sur la page Productions', 'e-commerce, en la página Producciones')),
            'cols' => array(
                array('t' => $T('Produzioni', 'Productions', 'Productions', 'Producciones'), 'links' => array(
                    $L('/visuals/', $T('Foto e video prodotto', 'Product photo & video', 'Photo et vidéo produit', 'Foto y vídeo de producto'), '',
                       $T('listino e-commerce', 'e-commerce price list', 'tarifs e-commerce', 'tarifas e-commerce')),
                    $L('/events/foto-video-eventi/', $T('Foto e video per eventi', 'Event photo & video', 'Photo et vidéo d’événements', 'Foto y vídeo de eventos')),
                )),
                array('t' => $T('Professionisti', 'Professionals', 'Professionnels', 'Profesionales'), 'links' => array(
                    $L('/crew-database/?cat=fotografo', $T('Fotografi', 'Photographers', 'Photographes', 'Fotógrafos'), '', null, true),
                    $L('/crew-database/?cat=videomaker', $T('Videomaker', 'Videographers', 'Vidéastes', 'Videógrafos'), '', null, true),
                    $L('/crew-database/?cat=makeup_artist', $T('Make-up artist', 'Make-up artists', 'Maquilleurs', 'Maquilladores'), '', null, true),
                    $L('/crew-database/?cat=social_media', $T('Social media manager', 'Social media managers', 'Social media managers', 'Social media managers'), '', null, true),
                )),
            ),
            'cross' => array('p' => '/models/', 't' => $T('Modelli per lo shooting', 'Models for your shoot', 'Mannequins pour votre shooting', 'Modelos para tu sesión'),
                             'd' => $T('Volti per catalogo e campagne.', 'Faces for catalogues and campaigns.', 'Des visages pour catalogues et campagnes.', 'Caras para catálogos y campañas.')),
        ),
        'database' => array(
            'n' => $T('Database', 'Database', 'Base de talents', 'Base de datos'),
            'tag' => $T('Sfoglia e scegli tu', 'Browse and pick yourself', 'Parcourez et choisissez', 'Explora y elige tú'),
            'brand' => 'Talent database', 'hub' => '/talent-database/',
            'hub_t' => $T('Apri il database', 'Open the database', 'Ouvrir la base', 'Abrir la base de datos'),
            'card' => array('tipo' => 'testo', 'l' => $T('Scegli tu i profili', 'Pick the profiles yourself', 'Choisissez vous-même les profils', 'Elige tú los perfiles'),
                            'big' => $T('Gratis', 'Free', 'Gratuit', 'Gratis'),
                            'note' => $T('aggiungi alla selezione e chiedi info', 'add them to your shortlist and ask us', 'ajoutez-les à votre sélection et demandez-nous', 'añádelos a tu selección y pregúntanos')),
            'cols' => array(
                array('t' => $T('Database', 'Databases', 'Bases', 'Bases de datos'), 'links' => array(
                    $L('/talent-database/', $T('Talent database', 'Talent database', 'Base de talents', 'Base de talentos'), '',
                       $T('modelli, hostess, creator, attori', 'models, hostesses, creators, actors', 'mannequins, hôtesses, créateurs, acteurs', 'modelos, azafatas, creadores, actores')),
                    $L('/crew-database/', $T('Creative Network', 'Creative Network', 'Creative Network', 'Creative Network'), '',
                       $T('fotografi, make-up, tecnici', 'photographers, make-up, technicians', 'photographes, maquillage, techniciens', 'fotógrafos, maquillaje, técnicos'), true),
                )),
                array('t' => $T('Cerca per', 'Search by', 'Rechercher par', 'Buscar por'), 'links' => array(
                    $L('/talent-database/?ruolo=hostess', $T('Hostess e steward', 'Hostesses & stewards', 'Hôtesses et stewards', 'Azafatas y auxiliares')),
                    $L('/talent-database/?ruolo=creator', $T('Creator e influencer', 'Creators & influencers', 'Créateurs et influenceurs', 'Creadores e influencers')),
                    $L('/talent-database/?ruolo=actor', $T('Attori e comparse', 'Actors & extras', 'Acteurs et figurants', 'Actores y figurantes')),
                    $L('/talent-database/?ruolo=model', $T('Modelli', 'Models', 'Mannequins', 'Modelos')),
                )),
            ),
            'cross' => array('p' => '/form-b2b/', 't' => $T('Preferisci che scegliamo noi?', 'Rather we choose for you?', 'Vous préférez que l’on choisisse ?', '¿Prefieres que elijamos nosotros?'),
                             'd' => $T('Ti mandiamo una selezione su misura.', 'We’ll send you a tailored shortlist.', 'Nous vous envoyons une sélection sur mesure.', 'Te enviamos una selección a medida.')),
        ),
    );
    $d['lavora'] = array(
        'n' => $T('Lavora con noi', 'Work with us', 'Travailler avec nous', 'Trabaja con nosotros'),
        'sub' => $T('Per modelli, hostess, creator, attori, crew e studenti', 'For models, hostesses, creators, actors, crew and students', 'Pour mannequins, hôtesses, créateurs, acteurs, techniciens et étudiants', 'Para modelos, azafatas, creadores, actores, equipo técnico y estudiantes'),
        'cols' => array(
            array('t' => $T('Casting aperti', 'Open castings', 'Castings ouverts', 'Castings abiertos'), 'links' => array(
                $L('/casting/', $T('Tutti i casting', 'All castings', 'Tous les castings', 'Todos los castings')),
                $L('/casting-torino/', $T('Torino', 'Turin', 'Turin', 'Turín')),
                $L('/casting-milano/', $T('Milano', 'Milan', 'Milan', 'Milán')),
                $L('/casting-roma/', $T('Roma', 'Rome', 'Rome', 'Roma')),
                $L('/casting-napoli/', $T('Napoli', 'Naples', 'Naples', 'Nápoles')),
                $L('/casting-italia/', $T('Tutte le città', 'All cities', 'Toutes les villes', 'Todas las ciudades')),
            )),
            array('t' => $T('Iscriviti', 'Sign up', 'Inscris-toi', 'Regístrate'), 'links' => array(
                $L('/registrati-talent/', $T('Come talent', 'As talent', 'En tant que talent', 'Como talent')),
                $L('/registrati-crew/', $T('Come crew', 'As crew', 'En tant que technicien', 'Como equipo técnico'), '', null, true),
                $L('/events/lavora-con-noi/', $T('Come hostess', 'As a hostess', 'Comme hôtesse', 'Como azafata')),
                $L('/creators/diventa-creator/', $T('Come creator', 'As a creator', 'Comme créateur', 'Como creador')),
            )),
            array('t' => $T('La tua scheda', 'Your profile', 'Ta fiche', 'Tu ficha'), 'links' => array(
                $L('/talent-self-edit/', $T('Modifica la tua scheda', 'Edit your profile', 'Modifier ta fiche', 'Editar tu ficha'), '', null, true),
                $L('/crm_toagency/recupera-link.php', $T('Recupera il link', 'Get your link back', 'Récupérer ton lien', 'Recuperar tu enlace')),
                $L('/itacommunities/', $T('Community dei casting', 'Casting community', 'Communauté castings', 'Comunidad de castings')),
            )),
            array('t' => $T('Guide e stage', 'Guides & internships', 'Guides et stages', 'Guías y prácticas'), 'links' => array(
                $L('/come-diventare-modello/', $T('Come diventare modello', 'How to become a model', 'Devenir mannequin', 'Cómo ser modelo')),
                $L('/book-fotografico-hostess-foto-per-candidarsi/', $T('Foto per candidarsi come hostess', 'Photos for hostess applications', 'Photos pour postuler comme hôtesse', 'Fotos para presentarte como azafata')),
                $L('/student-program/', $T('Student program', 'Student program', 'Student program', 'Student program')),
            )),
        ),
    );
    $d['agenzia'] = array(
        $L('/about/', $T('Chi siamo', 'About us', 'Qui sommes-nous', 'Quiénes somos')),
        $L('/b2bservices/', $T('Tutti i servizi', 'All services', 'Tous les services', 'Todos los servicios')),
        $L('/contact-us/', $T('Contatti', 'Contact', 'Contact', 'Contacto')),
        $L('/blog/', $T('Blog', 'Blog', 'Blog', 'Blog')),
    );
    $d['ui'] = array(
        'cta'      => $T('Richiedi preventivo', 'Request a quote', 'Demander un devis', 'Pide presupuesto'),
        'cta_url'  => '/form-b2b/',
        'menu'     => $T('Menu', 'Menu', 'Menu', 'Menú'),
        'chiudi'   => $T('Chiudi', 'Close', 'Fermer', 'Cerrar'),
        'anche'    => $T('Ti serve anche', 'You may also need', 'Il vous faut aussi', 'También te puede servir'),
        'persone'  => $T('Cerco persone', 'I need people', 'Je cherche des profils', 'Busco personal'),
        'lavoro'   => $T('Cerco lavoro', 'I want to work', 'Je cherche du travail', 'Busco trabajo'),
        'recens'   => $T('4,7 su Google · 346 recensioni', '4.7 on Google · 346 reviews', '4,7 sur Google · 346 avis', '4,7 en Google · 346 reseñas'),
        'da'       => $T('da', 'from', 'à partir de', 'desde'),
        'unita_h'  => $T('a persona, 4 ore', 'per person, 4 hours', 'par personne, 4 heures', 'por persona, 4 horas'),
        'unita_c'  => $T('video UGC', 'UGC video', 'vidéo UGC', 'vídeo UGC'),
        'wa'       => 'WhatsApp',
    );
    return $d;
}

/* ───────────── piccoli render ───────────── */
function toa_mg_link_html($l, $cls = '', $sub = true) {
    $u = toa_mg_url($l['p'], null, $l['alt'], $l['lp']);
    if ($u === '') return '';
    $s = ($sub && !empty($l['sub'])) ? ' <small>' . esc_html(toa_mg_t($l['sub'])) . '</small>' : '';
    return '<a' . ($cls ? ' class="' . esc_attr($cls) . '"' : '') . ' href="' . esc_url($u) . '"><span>' . esc_html(toa_mg_t($l['n'])) . '</span>' . $s . '</a>';
}
function toa_mg_lingue() {
    $out = array();
    if (!function_exists('icl_get_languages')) return $out;
    $langs = icl_get_languages('skip_missing=0&orderby=code');
    if (empty($langs) || count($langs) < 2) return $out;
    // stessa logica dello switcher di components/header.php (04/08): sulle pagine non tradotte si resta sulla pagina con ?lang=
    $cur_url = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . wp_unslash($_SERVER['REQUEST_URI']);
    $cur_url = remove_query_arg('anteprima', $cur_url);
    foreach ($langs as $l) {
        if (!in_array($l['language_code'], array('it', 'en', 'fr', 'es'), true)) continue;
        $u = remove_query_arg('anteprima', $l['url']);
        $p = untrailingslashit(strtok($u, '?'));
        $home = array(
            untrailingslashit(strtok(apply_filters('wpml_permalink', home_url('/'), $l['language_code']), '?')),
            untrailingslashit(home_url('/' . $l['language_code'] . '/')),
            untrailingslashit(home_url('/')),
        );
        if (!is_front_page() && (in_array($p, $home, true) || $p === untrailingslashit(strtok($cur_url, '?')))) {
            $u = add_query_arg('lang', $l['language_code'], $cur_url);
        }
        $out[$l['language_code']] = $u;
    }
    return $out;
}
function toa_mg_lingue_html($cls = 'tmg-langs') {
    $ls = toa_mg_lingue(); if (!$ls) return '';
    $cur = toa_mg_lang(); $h = '<div class="' . esc_attr($cls) . '">';
    foreach ($ls as $c => $u) {
        $h .= $c === $cur ? '<span class="on">' . strtoupper($c) . '</span>'
                          : '<a class="nav-lang-item" href="' . esc_url($u) . '" hreflang="' . esc_attr($c) . '">' . strtoupper($c) . '</a>';
    }
    return $h . '</div>';
}
function toa_mg_home_url() { return apply_filters('wpml_home_url', untrailingslashit(get_option('home')) . '/'); }

/* ───────────── voci (header e barra) ───────────── */
function toa_mg_voci_html($cur = '') {
    $d = toa_mg_dati(); $h = '';
    foreach ($d['sezioni'] as $k => $s) {
        $h .= '<button type="button" class="tmg-voce' . ($k === $cur ? ' is-cur' : '') . '" data-tmg-open="' . esc_attr($k) . '" aria-expanded="false" aria-controls="tmgPanel">' . esc_html(toa_mg_t($s['n'])) . '</button>';
    }
    return $h;
}

/* ───────────── pannelli (uno solo per pagina) ───────────── */
function toa_mg_pannelli() {
    static $fatto = false; if ($fatto) return; $fatto = true;
    $d = toa_mg_dati(); $ui = $d['ui'];
    echo '<div class="tmg-panel" id="tmgPanel" hidden role="dialog" aria-label="' . esc_attr(toa_mg_t($ui['menu'])) . '"><div class="tmg-panel__main"><div class="tmg-tabs" role="tablist">';
    foreach ($d['sezioni'] as $k => $s) {
        echo '<button type="button" class="tmg-tab" role="tab" data-tmg-tab="' . esc_attr($k) . '"><b>' . esc_html(toa_mg_t($s['n'])) . '</b><span>' . esc_html(toa_mg_t($s['tag'])) . '</span></button>';
    }
    echo '</div>';
    foreach ($d['sezioni'] as $k => $s) {
        $hub = toa_mg_url($s['hub']);
        echo '<div class="tmg-sec" data-tmg-sec="' . esc_attr($k) . '" hidden><div class="tmg-cols">';
        foreach ($s['cols'] as $c) {
            echo '<div class="tmg-col"><div class="tmg-col__t">' . esc_html(toa_mg_t($c['t'])) . '</div>';
            foreach ($c['links'] as $l) echo toa_mg_link_html($l);
            echo '</div>';
        }
        echo '</div><div class="tmg-side">';
        $cd = $s['card'];
        echo '<a class="tmg-price" href="' . esc_url($hub) . '" data-tmg-price="' . esc_attr($cd['tipo']) . '">'
           . '<span class="tmg-price__brand">' . esc_html(toa_mg_t($s['brand'])) . '</span>'
           . '<span class="tmg-price__l">' . esc_html(toa_mg_t($cd['l'])) . '</span>'
           . '<span class="tmg-price__big" data-tmg-p="big">' . esc_html(toa_mg_t($cd['big'])) . '</span>'
           . '<span class="tmg-price__note" data-tmg-p="note">' . esc_html(toa_mg_t($cd['note'])) . '</span>'
           . '<span class="tmg-price__go">' . esc_html(toa_mg_t($s['hub_t'])) . ' &rarr;</span></a>';
        $cu = toa_mg_url($s['cross']['p']);
        if ($cu) echo '<a class="tmg-cross" href="' . esc_url($cu) . '"><small>' . esc_html(toa_mg_t($ui['anche'])) . '</small><b>' . esc_html(toa_mg_t($s['cross']['t'])) . ' &rarr;</b><span>' . esc_html(toa_mg_t($s['cross']['d'])) . '</span></a>';
        echo '</div></div>';
    }
    echo '</div><div class="tmg-strip"><span class="tmg-logo tmg-logo--s">TOAGENCY</span>';
    foreach ($d['agenzia'] as $l) echo toa_mg_link_html($l);
    echo '<span class="tmg-strip__rev">' . esc_html(toa_mg_t($ui['recens'])) . '</span>'
       . '<button type="button" class="tmg-x" data-tmg-close aria-label="' . esc_attr(toa_mg_t($ui['chiudi'])) . '">&times;</button></div></div>';

    // pannello scuro «Lavora con noi»
    $lv = $d['lavora'];
    echo '<div class="tmg-panel tmg-panel--dark" id="tmgLavora" hidden role="dialog" aria-label="' . esc_attr(toa_mg_t($lv['n'])) . '"><div class="tmg-lav__h"><span class="tmg-lav__t">' . esc_html(toa_mg_t($lv['n'])) . '</span><span class="tmg-lav__s">' . esc_html(toa_mg_t($lv['sub'])) . '</span>'
       . '<button type="button" class="tmg-x tmg-x--w" data-tmg-close aria-label="' . esc_attr(toa_mg_t($ui['chiudi'])) . '">&times;</button></div><div class="tmg-lav__grid">';
    foreach ($lv['cols'] as $c) {
        echo '<div class="tmg-col"><div class="tmg-col__t">' . esc_html(toa_mg_t($c['t'])) . '</div>';
        foreach ($c['links'] as $l) echo toa_mg_link_html($l);
        echo '</div>';
    }
    echo '</div></div>';

    // menu del telefono: «Cerco persone» / «Cerco lavoro», sezioni a fisarmonica
    echo '<div class="tmg-mob" id="tmgMob" hidden><div class="tmg-mob__tabs" role="tablist">'
       . '<button type="button" class="on" data-tmg-mtab="az">' . esc_html(toa_mg_t($ui['persone'])) . '</button>'
       . '<button type="button" data-tmg-mtab="lv">' . esc_html(toa_mg_t($ui['lavoro'])) . '</button></div><div class="tmg-mob__list" data-tmg-mlist="az">';
    $first = true;
    foreach ($d['sezioni'] as $k => $s) {
        $hub = toa_mg_url($s['hub']);
        echo '<details class="tmg-acc"' . ($first ? ' open' : '') . '><summary>' . esc_html(toa_mg_t($s['n'])) . '</summary><div class="tmg-acc__in">';
        foreach ($s['cols'] as $c) foreach ($c['links'] as $l) echo toa_mg_link_html($l, 'tmg-acc__a', false);
        if ($hub) echo '<a class="tmg-acc__hub" href="' . esc_url($hub) . '">' . esc_html(toa_mg_t($s['hub_t'])) . ' &rarr;</a>';
        echo '</div></details>';
        $first = false;
    }
    echo '<div class="tmg-acc__ag">';
    foreach ($d['agenzia'] as $l) echo toa_mg_link_html($l, '', false);
    echo '</div></div><div class="tmg-mob__list" data-tmg-mlist="lv" hidden>';
    $first = true;
    foreach ($lv['cols'] as $c) {
        echo '<details class="tmg-acc"' . ($first ? ' open' : '') . '><summary>' . esc_html(toa_mg_t($c['t'])) . '</summary><div class="tmg-acc__in">';
        foreach ($c['links'] as $l) echo toa_mg_link_html($l, 'tmg-acc__a', false);
        echo '</div></details>';
        $first = false;
    }
    echo '</div>' . toa_mg_lingue_html('tmg-langs tmg-langs--mob') . '</div>';

    // barra fissa in basso (telefono): preventivo + WhatsApp (stesso numero del bottone WhatsApp del sito)
    $wa = 'https://wa.me/393518468516?text=' . rawurlencode(toa_mg_t(array('it' => 'Ciao TOAgency, vorrei un preventivo', 'en' => 'Hi TOAgency, I would like a quote', 'fr' => 'Bonjour TOAgency, je voudrais un devis', 'es' => 'Hola TOAgency, quisiera un presupuesto')));
    echo '<div class="tmg-mbar"><a class="tmg-btn tmg-btn--lime" href="' . esc_url(toa_mg_url($ui['cta_url'])) . '">' . esc_html(toa_mg_t($ui['cta'])) . '</a>'
       . '<a class="tmg-btn tmg-btn--line" href="' . esc_url($wa) . '" target="_blank" rel="noopener">' . esc_html($ui['wa']) . '</a></div>';
    echo '<div class="tmg-veil" id="tmgVeil" hidden></div>';
}

/* ───────────── header del sito (al posto della <nav> di components/header.php) ───────────── */
function toa_mg_header_bar() {
    $d = toa_mg_dati(); $ui = $d['ui'];
    echo '<header class="tmg-head" id="tmgHead"><div class="tmg-head__in">'
       . '<a class="tmg-logo" href="' . esc_url(toa_mg_home_url()) . '" aria-label="TOAgency">TOAGENCY</a>'
       . '<nav class="tmg-nav" aria-label="TOAgency">' . toa_mg_voci_html() . '</nav>'
       . '<div class="tmg-head__r"><button type="button" class="tmg-voce tmg-voce--lav" data-tmg-open="lavora" aria-expanded="false" aria-controls="tmgLavora">' . esc_html(toa_mg_t($d['lavora']['n'])) . '</button>'
       . toa_mg_lingue_html() . '<a class="tmg-btn tmg-btn--lime tmg-head__cta" href="' . esc_url(toa_mg_url($ui['cta_url'])) . '">' . esc_html(toa_mg_t($ui['cta'])) . '</a>'
       . '<button type="button" class="tmg-burger" data-tmg-mob aria-label="' . esc_attr(toa_mg_t($ui['menu'])) . '" aria-expanded="false" aria-controls="tmgMob"><span></span><span></span><span></span></button></div>'
       . '</div></header>';
    toa_mg_pannelli();
}

/* ───────────── barra nera in cima a /events/ e /creators/ ───────────── */
function toa_mg_barra($cur) {
    $d = toa_mg_dati(); $ui = $d['ui'];
    echo '<div class="tmg-bar" id="tmgHead"><div class="tmg-bar__in">'
       . '<a class="tmg-logo tmg-logo--bar" href="' . esc_url(toa_mg_home_url()) . '" aria-label="TOAgency">TOAGENCY</a>'
       . '<nav class="tmg-nav tmg-nav--bar" aria-label="TOAgency">' . toa_mg_voci_html($cur) . '</nav>'
       . '<div class="tmg-head__r"><button type="button" class="tmg-voce tmg-voce--lav" data-tmg-open="lavora" aria-expanded="false" aria-controls="tmgLavora">' . esc_html(toa_mg_t($d['lavora']['n'])) . '</button>'
       . toa_mg_lingue_html() . '<button type="button" class="tmg-burger tmg-burger--w" data-tmg-mob aria-label="' . esc_attr(toa_mg_t($ui['menu'])) . '" aria-expanded="false" aria-controls="tmgMob"><span></span><span></span><span></span></button></div>'
       . '</div></div>';
    // i pannelli vanno in fondo alla pagina: dentro la striscia sticky di /events/ resterebbero sotto il resto (z-index)
    add_action('wp_footer', 'toa_mg_pannelli', 5);
}

/* ───────────── footer unico (contiene TUTTI i link del footer di oggi) ───────────── */
function toa_mg_footer_cols() {
    $T = function ($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };
    $L = function ($path, $n, $alt = '', $lp = false) { return array('p' => $path, 'n' => $n, 'alt' => $alt, 'sub' => null, 'lp' => $lp); };
    return array(
        array('t' => $T('Servizi', 'Services', 'Services', 'Servicios'), 'links' => array(
            $L('/events/', $T('TOA Events', 'TOA Events', 'TOA Events', 'TOA Events')),
            $L('/creators/', $T('TOA Creators', 'TOA Creators', 'TOA Creators', 'TOA Creators')),
            $L('/models/', $T('Modelli', 'Models', 'Mannequins', 'Modelos')),
            $L('/actors/', $T('Attori e comparse', 'Actors & extras', 'Acteurs et figurants', 'Actores y figurantes')),
            $L('/visuals/', $T('Foto e video', 'Photo & video', 'Photo et vidéo', 'Foto y vídeo')),
            $L('/talent-database/', $T('Talent database', 'Talent database', 'Base de talents', 'Base de talentos')),
            $L('/b2bservices/', $T('Tutti i servizi', 'All services', 'Tous les services', 'Todos los servicios')),
        )),
        array('t' => $T('Hostess per città', 'Hostesses by city', 'Hôtesses par ville', 'Azafatas por ciudad'), 'links' => array(
            $L('/agenzia-hostess-torino/', $T('Hostess Torino', 'Hostesses in Turin', 'Hôtesses à Turin', 'Azafatas en Turín'), '/events/torino/'),
            $L('/agenzia-hostess-milano/', $T('Hostess Milano', 'Hostesses in Milan', 'Hôtesses à Milan', 'Azafatas en Milán'), '/events/milano/'),
            $L('/agenzia-hostess-roma/', $T('Hostess Roma', 'Hostesses in Rome', 'Hôtesses à Rome', 'Azafatas en Roma'), '/events/roma/'),
            $L('/hostess-atp-finals-torino/', $T('ATP Finals Torino', 'ATP Finals Turin', 'ATP Finals Turin', 'ATP Finals Turín')),
            $L('/hostess-fiere/', $T('Hostess fiere', 'Trade show hostesses', 'Hôtesses pour salons', 'Azafatas para ferias'), '/events/personale-fiere/'),
            $L('/hostess-eicma/', $T('Hostess EICMA', 'EICMA hostesses', 'Hôtesses EICMA', 'Azafatas EICMA')),
        )),
        array('t' => $T('Modelli e comparse', 'Models & extras', 'Mannequins et figurants', 'Modelos y figurantes'), 'links' => array(
            $L('/casting-modelle-over-50/', $T('Modelle over 50', 'Models over 50', 'Mannequins 50+', 'Modelos 50+')),
            $L('/agenzia-comparse-milano/', $T('Comparse Milano', 'Extras in Milan', 'Figurants à Milan', 'Figurantes en Milán')),
            $L('/agenzia-comparse-roma/', $T('Comparse Roma', 'Extras in Rome', 'Figurants à Rome', 'Figurantes en Roma')),
            $L('/agenzia-modelle-napoli/', $T('Modelle Napoli', 'Models in Naples', 'Mannequins à Naples', 'Modelos en Nápoles')),
            $L('/agenzia-modelle-marche/', $T('Modelle Marche', 'Models in Marche', 'Mannequins dans les Marches', 'Modelos en Marcas')),
        )),
        array('t' => $T('Lavora con noi', 'Work with us', 'Travailler avec nous', 'Trabaja con nosotros'), 'links' => array(
            $L('/casting/', $T('Casting aperti', 'Open castings', 'Castings ouverts', 'Castings abiertos')),
            $L('/casting-torino/', $T('Casting Torino', 'Castings in Turin', 'Castings à Turin', 'Castings en Turín')),
            $L('/casting-milano/', $T('Casting Milano', 'Castings in Milan', 'Castings à Milan', 'Castings en Milán')),
            $L('/casting-roma/', $T('Casting Roma', 'Castings in Rome', 'Castings à Rome', 'Castings en Roma')),
            $L('/casting-napoli/', $T('Casting Napoli', 'Castings in Naples', 'Castings à Naples', 'Castings en Nápoles')),
            $L('/casting-firenze/', $T('Casting Firenze', 'Castings in Florence', 'Castings à Florence', 'Castings en Florencia')),
            $L('/casting-italia/', $T('Casting in tutte le città', 'Castings in every city', 'Castings dans toutes les villes', 'Castings en todas las ciudades')),
            $L('/collabora/', $T('Iscriviti', 'Sign up', 'Inscris-toi', 'Regístrate')),
            $L('/student-program/', $T('Student program', 'Student program', 'Student program', 'Student program')),
        )),
        array('t' => $T('Agenzia', 'Agency', 'Agence', 'Agencia'), 'links' => array(
            $L('/about/', $T('Chi siamo', 'About us', 'Qui sommes-nous', 'Quiénes somos')),
            $L('/contact-us/', $T('Contatti', 'Contact', 'Contact', 'Contacto')),
            $L('/blog/', $T('Blog', 'Blog', 'Blog', 'Blog')),
        )),
    );
}
function toa_mg_footer() {
    $lang = toa_mg_lang();
    echo '<footer class="tmg-foot"><div class="tmg-foot__in"><div class="tmg-foot__grid">';
    foreach (toa_mg_footer_cols() as $c) {
        echo '<div class="tmg-foot__col"><div class="tmg-foot__t">' . esc_html(toa_mg_t($c['t'])) . '</div>';
        foreach ($c['links'] as $l) echo toa_mg_link_html($l, '', false);
        echo '</div>';
    }
    // privacy/cookie/termini: stessi indirizzi del footer di oggi (components/footer.php)
    $priv = array('en' => 'https://toagency.it/en/gdpr-privacy-policy/', 'fr' => 'https://toagency.it/fr/gdpr-privacy-policy/', 'es' => 'https://toagency.it/es/gdpr-politica-de-privacidad/');
    $priv_u = isset($priv[$lang]) ? $priv[$lang] : 'https://toagency.it/privacy-policy-3/';
    $terms = array('it' => 'Termini e Condizioni', 'en' => 'Terms and Conditions', 'fr' => 'Conditions Générales', 'es' => 'Términos y Condiciones');
    echo '</div><div class="tmg-foot__legal"><span class="tmg-logo tmg-logo--foot">TOAGENCY</span>'
       . '<p>TOAGENCY è un marchio di TOA di Leonardi Marco &mdash; Via Cavour 21, 10123 Torino (Italy) &mdash; P.IVA 11800210012</p>'
       . '<p class="toa-footer-recover">' . _ht(array('it' => 'Hai già una scheda con noi?', 'en' => 'Already have a profile with us?', 'fr' => 'Tu as déjà une fiche chez nous ?', 'es' => '¿Ya tienes una ficha con nosotros?'))
       . ' <a href="' . esc_url('https://toagency.it/crm_toagency/recupera-link.php?lang=' . $lang) . '">' . _ht(array('it' => 'Recuperala qui', 'en' => 'Recover it here', 'fr' => 'Récupère-la ici', 'es' => 'Recúperala aquí')) . '</a></p>'
       . '<p class="tmg-foot__links"><a href="' . esc_url($priv_u) . '" target="_blank">Privacy Policy</a>'
       . '<a href="' . esc_url(apply_filters('wpml_permalink', 'https://toagency.it/cookie-policy-ue/', $lang)) . '" target="_blank">Cookie Policy</a>'
       . '<a href="' . esc_url(home_url('/terms-and-conditions/')) . '">' . esc_html($terms[$lang]) . '</a>'
       . '<a href="#" onclick="if(window.toaOpenReportModal){toaOpenReportModal();}return false;">' . _ht(array('it' => 'Segnala un problema', 'en' => 'Report a problem', 'fr' => 'Signaler un problème', 'es' => 'Informar un problema')) . '</a></p>'
       . '</div></div></footer>';
}

/* ───────────── card della home («Cosa facciamo») ───────────── */
function toa_mg_home_cards() {
    $T = function ($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };
    $cards = array(
        array('dark' => true, 'tipo' => 'hostess', 'brand' => 'TOA Events', 'p' => '/events/',
              't' => $T('Hostess e staff per eventi', 'Hostesses and event staff', 'Hôtesses et staff événementiel', 'Azafatas y personal para eventos'),
              'd' => $T('Hostess, steward, promoter e tutto lo staff.', 'Hostesses, stewards, promoters and all the staff you need.', 'Hôtesses, stewards, promoteurs et tout le staff.', 'Azafatas, auxiliares, promotores y todo el personal.'),
              'cta' => $T('Staff e prezzi', 'Staff & prices', 'Personnel et tarifs', 'Personal y precios')),
        array('dark' => true, 'tipo' => 'creator', 'brand' => 'TOA Creators', 'p' => '/creators/',
              't' => $T('Creator per il tuo brand', 'Creators for your brand', 'Des créateurs pour votre marque', 'Creadores para tu marca'),
              'd' => $T('Video UGC, contenuti sul profilo, eventi.', 'UGC videos, profile posts, events.', 'Vidéos UGC, contenus sur le profil, événements.', 'Vídeos UGC, contenido en el perfil, eventos.'),
              'cta' => $T('Pacchetti e prezzi', 'Packages & prices', 'Formules et tarifs', 'Paquetes y precios')),
        array('dark' => false, 'tipo' => '', 'brand' => $T('Modelli e attori', 'Models & actors', 'Mannequins et acteurs', 'Modelos y actores'), 'p' => '/models/',
              't' => $T('Modelli, attori e comparse', 'Models, actors and extras', 'Mannequins, acteurs et figurants', 'Modelos, actores y figurantes'),
              'd' => $T('Moda, pubblicità, cinema e TV.', 'Fashion, advertising, film and TV.', 'Mode, publicité, cinéma et télé.', 'Moda, publicidad, cine y TV.'),
              'cta' => $T('Vai a Modelli', 'Go to Models', 'Voir les mannequins', 'Ver modelos')),
        array('dark' => false, 'tipo' => '', 'brand' => $T('Produzioni', 'Productions', 'Productions', 'Producciones'), 'p' => '/visuals/',
              't' => $T('Foto e video', 'Photo & video', 'Photo et vidéo', 'Foto y vídeo'),
              'd' => $T('Shooting prodotto, e-commerce ed eventi.', 'Product shoots, e-commerce and events.', 'Shootings produit, e-commerce et événements.', 'Sesiones de producto, e-commerce y eventos.'),
              'cta' => $T('Vai alle produzioni', 'See our productions', 'Voir nos productions', 'Ver producciones')),
    );
    echo '<div class="tmg-cards">';
    foreach ($cards as $c) {
        $u = toa_mg_url($c['p']); if (!$u) continue;
        echo '<a class="tmg-card' . ($c['dark'] ? ' tmg-card--dark' : '') . '" href="' . esc_url($u) . '"' . ($c['tipo'] ? ' data-tmg-price="' . esc_attr($c['tipo']) . '"' : '') . '>'
           . '<span class="tmg-card__b">' . esc_html(toa_mg_t($c['brand'])) . '</span>'
           . '<span class="tmg-card__t">' . esc_html(toa_mg_t($c['t'])) . '</span>'
           . '<span class="tmg-card__d">' . esc_html(toa_mg_t($c['d'])) . '</span>'
           . '<span class="tmg-card__p" data-tmg-p="card"></span>'
           . '<span class="tmg-card__c">' . esc_html(toa_mg_t($c['cta'])) . ' &rarr;</span></a>';
    }
    echo '</div>';
}
