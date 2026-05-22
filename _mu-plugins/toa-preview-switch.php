<?php
/*
Plugin Name: TOA Preview Switch
Description: Carica toagency-theme-preview/ per chi ha cookie toa_preview valido. Bypass SG cache + iniezione banner via output buffer.
Version: 1.1
*/
defined('ABSPATH') || exit;

$TOA_PREVIEW_KEY = 'toa_preview_x9k2_2026';
$TOA_PREVIEW_THEME = 'toagency-theme-preview';

// Toggle preview via URL ?toa_preview=1&key=...
add_action('init', function() use ($TOA_PREVIEW_KEY) {
    if (isset($_GET['toa_preview']) && isset($_GET['key']) && hash_equals($TOA_PREVIEW_KEY, $_GET['key'])) {
        $on = $_GET['toa_preview'] === '1';
        setcookie('toa_preview', $on ? '1' : '', $on ? time()+86400*7 : time()-3600, '/');
        wp_safe_redirect(remove_query_arg(['toa_preview', 'key']));
        exit;
    }
});

// Switch tema quando cookie attivo
add_filter('pre_option_template', function($v) use ($TOA_PREVIEW_THEME) {
    return !empty($_COOKIE['toa_preview']) ? $TOA_PREVIEW_THEME : $v;
});
add_filter('pre_option_stylesheet', function($v) use ($TOA_PREVIEW_THEME) {
    return !empty($_COOKIE['toa_preview']) ? $TOA_PREVIEW_THEME : $v;
});

// Bypass SG-CachePress (filter specifico SiteGround Optimizer)
add_filter('sg_cachepress_bypass_cache', function($bypass) {
    return !empty($_COOKIE['toa_preview']) ? true : $bypass;
});

// DONOTCACHEPAGE costante riconosciuta da SG + altri page cache plugin
if (!empty($_COOKIE['toa_preview']) && !defined('DONOTCACHEPAGE')) {
    define('DONOTCACHEPAGE', true);
}

// Header HTTP per cache layer esterni (CDN, Dynamic Cache server-side)
add_action('send_headers', function() {
    if (!empty($_COOKIE['toa_preview'])) {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0', true);
        header('Pragma: no-cache', true);
    }
});

// Banner: inietta via output buffer prima di </body> (tema non chiama sempre wp_footer)
add_action('template_redirect', function() {
    if (empty($_COOKIE['toa_preview']) || is_admin()) return;
    ob_start(function($html) {
        $banner = '<div style="position:fixed;bottom:0;left:0;right:0;background:#d9ff00;color:#000;text-align:center;padding:8px;font:700 13px sans-serif;z-index:99999;">🛠 PREVIEW MODE — tema toagency-theme-preview</div>';
        if (stripos($html, '</body>') !== false) {
            return str_ireplace('</body>', $banner . '</body>', $html);
        }
        return $html . $banner;
    });
});

// DEBUG: emette commento HTML con stato runtime per diagnosi
add_action('send_headers', function() {
    header('X-Toa-Preview-Cookie: ' . (!empty($_COOKIE['toa_preview']) ? '1' : '0'), true);
    header('X-Toa-Preview-Template: ' . get_option('template'), true);
    header('X-Toa-Preview-Stylesheet: ' . get_option('stylesheet'), true);
});
