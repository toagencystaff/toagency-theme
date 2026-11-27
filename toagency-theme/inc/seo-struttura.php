<?php
/**
 * Pulizia struttura SEO — 26/11/2026, chat TEMA SEO AI EVENTI (piano: artifact «TOAgency — Piano SEO e AI per eventi»).
 * 1) llms.txt e sitemap mai serviti dalla cache SiteGround (i robot leggevano copie di ottobre).
 * 2) Doppioni uniti con 301 nella pagina della stessa ricerca che rende di più (solo pagine con 0-1 clic in 16 mesi, Search Console).
 * 3) Pagine di prova/vecchie a 0 clic in 16 mesi: noindex + fuori sitemap (restano online).
 * Annullare: togliere la voce dalla lista (o il require in functions.php) e deploy.
 */
if (!defined('ABSPATH')) exit;

// 1) niente cache per i file letti dai robot
add_action('init', function () {
    $p = trim((string) parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH), '/');
    if ($p === 'llms.txt' || preg_match('~^[a-z0-9_-]*sitemap[0-9]*\.(xml|xsl)$~', $p)) {
        header('X-Cache-Enabled: False', true);
        header('Cache-Control: no-cache, must-revalidate, max-age=0', true);
    }
}, 0);

// 2) 301: ID IT di origine => ID IT di destinazione (+ lingue escluse perché lì l'origine ha clic)
if (!function_exists('toa_seo_redirect_map')) {
    function toa_seo_redirect_map() {
        return array(
            270134 => array('to' => 263566),                         // /hostess-fiere/ (0 clic, 789 impr.) -> /hostess-steward/
            271101 => array('to' => 263566, 'skip' => array('fr', 'es')), // /agenzia-hostess/ (IT 1, EN 0 clic) -> /hostess-steward/; FR e ES hanno 3 clic: restano
            271897 => array('to' => 270184),                         // /hostess-vicenzaoro-verona-guida/ (1 clic; diceva Verona, la fiera è a Vicenza) -> /hostess-vicenzaoro/
            271907 => array('to' => 270164),                         // /hostess-vinitaly-verona-guida/ (0) -> /hostess-vinitaly/
            271866 => array('to' => 270182),                         // /hostess-tuttofood-milano/ (0) -> /hostess-tuttofood/
            271864 => array('to' => 270162),                         // /hostess-salone-del-mobile-milano/ (0) -> /hostess-salone-del-mobile/
            271862 => array('to' => 270186),                         // /hostess-micam-milano/ (0) -> /hostess-micam/ (23 clic)
            271893 => array('to' => 270188),                         // /hostess-lineapelle-milano-guida/ (0) -> /hostess-lineapelle/
            271923 => array('to' => 270224),                         // /hostess-cosmoprof-bologna-guida/ (0) -> /hostess-cosmoprof/
            271865 => array('to' => 270198),                         // /hostess-marmomac-verona-guida/ (1) -> /hostess-marmomac-verona/
            // 27/11/2026 fase 3c (chat TEMA SEO AI EVENTI 2): articoli «staff-…-italia» a 0-1 clic in 16 mesi -> pagina settore o articolo più forte dello stesso settore.
            // 'to' come testo = slug IT di una pagina settore (finché non è pubblicata, nessun redirect). Restano com'erano i 13 articoli con 2+ clic.
            272022 => array('to' => 'staff-eventi-tech-innovazione'),        // tech-startup-demo-day (0 clic, 18 impr.)
            272166 => array('to' => 'staff-eventi-tech-innovazione'),        // tech-b2b-enterprise-conferenze-summit (0, 3)
            272148 => array('to' => 'staff-eventi-tech-innovazione'),        // crypto-blockchain-web3-conferenze (0, 3)
            271936 => array('to' => 'staff-eventi-tech-innovazione'),        // staff-fiere-tecnologia (0, 3)
            272202 => array('to' => 'staff-eventi-tech-innovazione'),        // energia-rinnovabili-transizione-energetica (0, 1)
            272247 => array('to' => 'staff-eventi-lusso-moda'),              // circular-fashion-upcycling-moda-sostenibile (0, 10)
            272168 => array('to' => 'staff-eventi-lusso-moda'),              // moda-sostenibile-green-fashion (0, 1)
            272233 => array('to' => 'staff-eventi-lusso-moda'),              // immobiliare-luxury-real-estate-premium (0, 3)
            272215 => array('to' => 'staff-eventi-corporate-istituzionali'), // finanziari-banche-assicurazioni (1, 12)
            272063 => array('to' => 'staff-eventi-corporate-istituzionali'), // b2b-networking-business-matching (0, 42)
            272047 => array('to' => 'staff-eventi-corporate-istituzionali'), // staff-career-day-recruiting-fair-universita (0, 24)
            272105 => array('to' => 'staff-eventi-corporate-istituzionali'), // staff-open-day-universita-orientamento-scolastico (0, 0)
            272055 => array('to' => 'staff-eventi-corporate-istituzionali'), // staff-congressi-legali-notarili-consulenza (0, 0)
            272178 => array('to' => 'staff-eventi-corporate-istituzionali'), // hr-people-management-future-of-work (0, 3)
            272075 => array('to' => 'staff-eventi-corporate-istituzionali'), // istituzionali-pubblica-amministrazione (0, 10)
            272043 => array('to' => 'staff-eventi-corporate-istituzionali'), // charity-gala-beneficenza (0, 3)
            272119 => array('to' => 'staff-eventi-corporate-istituzionali'), // staff-expo-padiglioni-istituzionali-rappresentanze-nazionali (0, 1)
            271954 => array('to' => 'staff-retail-inaugurazioni'),           // staff-inaugurazioni-aperture-negozio (0, 18)
            272020 => array('to' => 'staff-retail-inaugurazioni'),           // staff-grandi-inaugurazioni-hotel-centri-commerciali-flagship (0, 0)
            272000 => array('to' => 272115), // enogastronomici-showcooking-degustazioni (1) -> enoturismo-wine-tourism (8 clic)
            272095 => array('to' => 271944), // sportivi-outdoor-trail-ciclismo-triathlon (1) -> staff-eventi-sportivi (3)
            272209 => array('to' => 272079), // automotive-luxury-salone-auto (1) -> automotive-outdoor-raduni-test-drive (3)
            272002 => array('to' => 272079), // staff-modelle-automotive-showroom-test-drive (0) -> automotive-outdoor-raduni-test-drive (3)
            271992 => array('to' => 272192), // staff-congressi-medici-ecm-convention-scientifiche (1) -> farmaceutici-congressi-medici (2 clic, 300 impr.)
            271929 => array('to' => 275581), // hospitality-staff-eventi-hotel (0, 30) -> /events/staff-eventi/
            272239 => array('to' => 275585), // staff-fiere-edilizia-costruzioni (0, 9) -> /events/personale-fiere/
            272227 => array('to' => 275585), // staff-fiere-agricoltura-agroalimentare (0, 5) -> /events/personale-fiere/
        );
    }
}
if (!function_exists('toa_seo_redirect_target')) {
    // URL di destinazione per un post/pagina, '' se non va rediretto
    function toa_seo_redirect_target($id) {
        $id = (int) $id;
        if (!$id) return '';
        $pt = get_post_type($id);
        if (!in_array($pt, array('page', 'post'), true)) return '';
        $it = (int) apply_filters('wpml_object_id', $id, $pt, true, 'it');
        $map = toa_seo_redirect_map();
        if (!isset($map[$it])) return '';
        $det = apply_filters('wpml_post_language_details', null, $id);
        $lang = (is_array($det) && !empty($det['language_code'])) ? $det['language_code'] : 'it';
        if (!empty($map[$it]['skip']) && in_array($lang, $map[$it]['skip'], true)) return '';
        $dest = $map[$it]['to'];
        if (is_string($dest)) { // slug IT di una pagina
            $pg = get_page_by_path($dest, OBJECT, 'page');
            if (!$pg) return '';
            $dest = (int) $pg->ID;
        }
        $dpt = get_post_type((int) $dest) ?: 'page';
        $to = (int) apply_filters('wpml_object_id', (int) $dest, $dpt, true, $lang);
        return ($to && $to !== $id && get_post_status($to) === 'publish') ? get_permalink($to) : '';
    }
}
add_action('template_redirect', function () {
    if (is_admin() || !is_singular()) return;
    $to = toa_seo_redirect_target(get_queried_object_id());
    if ($to) { wp_safe_redirect($to, 301, 'TOAgency'); exit; }
}, 1);

// 3) noindex: percorsi esatti (0 clic in 16 mesi, Search Console 25/07/2025-24/11/2026)
if (!function_exists('toa_seo_noindex_paths')) {
    function toa_seo_noindex_paths() {
        return array(
            '/ecwid-test/', '/elementor-255983/', '/elementor-260923/', '/pagina-test-per-i-form-da-cancellare/', '/xxx/',
            '/test-collaborazione-talent/', '/servizi-2022-test-template-b/', '/landing1/', '/models-xx/',
            '/ddiw/', '/fr/ddiw/', '/es/ddiw/', '/moodboard/', '/en/moodboard/', '/fr/moodboard/', '/es/moodboard/',
            '/moodboard22-23/', '/fr/moodboard-prova/', '/es/moodboard-prova/', '/homeita23/', '/en/homeita23/',
            '/pricelist33/', '/joinus24/', '/joinus24b/', '/link-in-bio/', '/link-in-bio-2/', '/payment-plans/',
            '/fr/payment-form-fra/', '/fr/https-toagency-it-fr-payment-frfr/', '/pay-chi-kid/', '/post-casting/',
            '/prenotazione-chiamata/', '/hot-fashion-season/', '/home-hot-fashion-season/', '/fr/hot-fashion-season-set-fra/',
            '/set-differences-children-kids/', '/fr/lecole-toa-arrive-2/', '/es/success/', '/fr-success/', '/s-m-academy/',
            '/staff-data/',
        );
    }
}
if (!function_exists('toa_seo_path_of')) {
    function toa_seo_path_of($url) {
        $p = (string) parse_url($url, PHP_URL_PATH);
        return strtolower(trailingslashit($p ? $p : '/'));
    }
}
add_filter('wp_robots', function ($robots) {
    $p = toa_seo_path_of(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/');
    if (in_array($p, toa_seo_noindex_paths(), true)) {
        unset($robots['index'], $robots['max-image-preview'], $robots['max-snippet'], $robots['max-video-preview']);
        $robots['noindex'] = true;
        $robots['follow'] = true;
    }
    return $robots;
}, 999);

// fuori sitemap: pagine noindex e pagine redirette
add_filter('wpseo_sitemap_entry', function ($url, $type, $post) {
    if (is_array($url) && !empty($url['loc']) && in_array(toa_seo_path_of($url['loc']), toa_seo_noindex_paths(), true)) return false;
    if (is_object($post) && !empty($post->ID) && toa_seo_redirect_target($post->ID)) return false;
    return $url;
}, 10, 3);

// 4) home EN/FR/ES: il canonical di Yoast puntava alle vecchie pagine /xx/testhome/ (redirette). Si usa l'indirizzo vero della pagina.
add_filter('wpseo_canonical', function ($canonical) {
    if (is_string($canonical) && strpos($canonical, '/testhome/') !== false && is_singular()) {
        $u = get_permalink(get_queried_object_id());
        if ($u && strpos($u, '/testhome/') === false) return $u;
    }
    return $canonical;
}, 20);
add_filter('wpseo_opengraph_url', function ($url) {
    if (is_string($url) && strpos($url, '/testhome/') !== false && is_singular()) {
        $u = get_permalink(get_queried_object_id());
        if ($u && strpos($u, '/testhome/') === false) return $u;
    }
    return $url;
}, 20);
