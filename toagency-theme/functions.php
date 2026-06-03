<?php
/**
 * TOAgency Theme Functions
 * Tema custom PHP standalone - NO Elementor
 */

// ═══════════════════════════════════════════
// THEME SETUP
// ═══════════════════════════════════════════
function toagency_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'gallery', 'caption'));

    register_nav_menus(array(
        'primary' => 'Menu Principale',
        'footer'  => 'Menu Footer',
    ));
}

// === Ensure CRM directories exist ===
if (!is_dir(ABSPATH . 'crm_toagency/pending')) {
    @mkdir(ABSPATH . 'crm_toagency/pending', 0755, true);
}

add_action('after_setup_theme', 'toagency_setup');

// ═══════════════════════════════════════════
// ENQUEUE ASSETS
// ═══════════════════════════════════════════
function toagency_assets() {
    $css_path = get_theme_file_path('assets/css/main.css');
    $ver = file_exists($css_path) ? filemtime($css_path) : '1.0.0';
    wp_enqueue_style('toagency-fonts', 'https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,700;0,900;1,400&display=swap', array(), null);
    wp_enqueue_style('toagency-main', get_theme_file_uri('assets/css/main.css'), array(), $ver);

    $js_path = get_theme_file_path('assets/js/main.js');
    $js_ver = file_exists($js_path) ? filemtime($js_path) : '1.0.0';
    wp_enqueue_script('toagency-main', get_theme_file_uri('assets/js/main.js'), array(), $js_ver, true);

    // Pass data to JS
    wp_localize_script('toagency-main', 'toaData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'homeUrl' => home_url('/'),
    ));
}
add_action('wp_enqueue_scripts', 'toagency_assets');

// ═══════════════════════════════════════════
// PAGE TEMPLATES
// ═══════════════════════════════════════════
function toagency_page_templates($templates) {
    $templates['templates/page-home.php']          = 'Homepage';
    $templates['templates/page-form-b2b.php']      = 'Form B2B';
    $templates['templates/page-hostess-live.php']   = 'Hostess Live Form';
    $templates['templates/page-models.php']         = 'Models';
    $templates['templates/page-hostess.php']        = 'Hostess & Steward';
    $templates['templates/page-actors.php']         = 'Actors';
    $templates['templates/page-visuals.php']        = 'Visuals / Production';
    $templates['templates/page-services.php']       = 'Services B2B';
    $templates['templates/page-about.php']          = 'About';
    $templates['templates/page-contact.php']        = 'Contact';
    $templates['templates/page-collabora.php']      = 'Collabora (B2C)';
    $templates['templates/page-casting.php']        = 'Casting';
    return $templates;
}
add_filter('theme_page_templates', 'toagency_page_templates');

// ═══════════════════════════════════════════
// GOOGLE ADS TRACKING (HEAD)
// ═══════════════════════════════════════════
function toagency_head_tracking() {
    ?>
    <!-- Google Ads Global Site Tag -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-624589019"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'AW-624589019');
    </script>
    <!-- Facebook Pixel -->
    <script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
    n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
    document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init','307174830463659');
    fbq('track','PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=307174830463659&ev=PageView&noscript=1"/></noscript>
    <?php
}
add_action('wp_head', 'toagency_head_tracking', 1);

// ═══════════════════════════════════════════
// HELPER FUNCTIONS
// ═══════════════════════════════════════════

/**
 * Get component file
 */
function toa_component($name, $args = array()) {
    if (!empty($args)) {
        extract($args);
    }
    include get_theme_file_path('components/' . $name . '.php');
}

/**
 * Get current language (WPML compatible)
 */
function toa_current_lang() {
    if (defined('ICL_LANGUAGE_CODE')) {
        return ICL_LANGUAGE_CODE;
    }
    return 'it';
}

/**
 * i18n helper globale — ritorna la stringa per la lingua corrente, esc_html'd.
 * FIX 2026-05-31 marco — promosso a globale (era locale a page-home.php) per uso in componenti globali (footer).
 * Guardato: page-home.php / page-landing-geo.php definiscono _ht solo se non già presente.
 */
if (!function_exists('_ht')) {
    function _ht($strings) {
        global $__l;
        $l = (!empty($__l) && in_array($__l, array('it','en','fr','es'), true))
            ? $__l
            : (function_exists('toa_current_lang') ? toa_current_lang() : 'it');
        if (!in_array($l, array('it','en','fr','es'), true)) $l = 'it';
        return esc_html(isset($strings[$l]) ? $strings[$l] : (isset($strings['it']) ? $strings['it'] : ''));
    }
}

/**
 * Check if current page uses a specific template
 */
function toa_is_template($template_name) {
    return is_page_template('templates/' . $template_name);
}


// === FIX: Hostess Live Form submit handler ===
add_action('wp_footer', function() {
    if (!is_page('hostess-live-form')) return;
    echo '<script>
(function(){
  var form = document.getElementById("hlfForm");
  if(!form) return;
  var CRM = "/crm_toagency/actions/lead-from-website.php";
  var TNX = window.location.origin + "/tnx/";
  var TIMEOUT = 8000;
  function goThankYou(){ window.location.href = TNX; }
  function getHlfData(){
    var d = {};
    d.city = (document.getElementById("hlf-city")||{}).value || "";
    var pEl = document.getElementById("hlf-people");
    d.people = pEl ? (parseInt(pEl.textContent||pEl.value)||1) : 1;
    var dEl = document.getElementById("hlf-days");
    d.days = dEl ? (parseInt(dEl.textContent||dEl.value)||1) : 1;
    d.hours = (document.getElementById("hlf-hours")||{}).value || "6h";
    d.date_start = (document.getElementById("hlf-date-start")||{}).value || "";
    d.date_end = (document.getElementById("hlf-date-end")||{}).value || "";
    d.description = (document.getElementById("hlf-description")||{}).value || "";
    d.name = (document.getElementById("hlf-name")||{}).value || "";
    d.company = (document.getElementById("hlf-company")||{}).value || "";
    d.email = (document.getElementById("hlf-email")||{}).value || "";
    d.phone = (document.getElementById("hlf-phone")||{}).value || "";
    d.form_type = "hostess-live";
    d.page_url = window.location.href;
    d.timestamp = new Date().toISOString();
    return d;
  }
  form.addEventListener("submit", function(e){
    e.preventDefault();
    e.stopImmediatePropagation();
    var consent = document.getElementById("hlf-consent");
    if(consent && !consent.checked){
      alert("Per favore accetta la privacy policy per continuare.");
      return;
    }
    var btn = form.querySelector("button[type=submit]");
    if(btn) btn.disabled = true;
    var payload = getHlfData();
    try{ sessionStorage.setItem("toa_lead", JSON.stringify(payload)); }catch(ex){}
    var safetyTimer = setTimeout(function(){ goThankYou(); }, TIMEOUT);
    var ctrl = typeof AbortController!=="undefined" ? new AbortController() : null;
    var fetchTimer = setTimeout(function(){ if(ctrl) ctrl.abort(); }, TIMEOUT-1000);
    fetch(CRM, {
      method: "POST",
      headers: {"Content-Type":"application/json"},
      body: JSON.stringify(payload),
      signal: ctrl ? ctrl.signal : undefined
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
      clearTimeout(fetchTimer);
      clearTimeout(safetyTimer);
      goThankYou();
    })
    .catch(function(err){
      clearTimeout(fetchTimer);
      clearTimeout(safetyTimer);
      goThankYou();
    });
  }, true);
})();
</script>';
}, 999);


// === FIX: Form B2B submit handler ===
add_action('wp_footer', function() {
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    if (strpos($uri, '/form-b2b') === false) return;
    $tnx_lang = (function_exists('toa_current_lang') && toa_current_lang() !== 'it') ? '/' . toa_current_lang() : '';
    echo '<script>
(function(){
  var form = document.getElementById("leadForm");
  if(!form) return;
  var CRM = "/crm_toagency/actions/lead-from-website.php";
  var TNX = window.location.origin + "' . $tnx_lang . '/tnx/";
  var TIMEOUT = 8000;
  function goThankYou(){ window.location.href = TNX; }
  function getB2BData(){
    var d = {};
    var fields = ["company","contact","email","phone","event_type","period","location","budget","message"];
    for(var i=0;i<fields.length;i++){
      var el = document.getElementById(fields[i]);
      d[fields[i]] = el ? el.value : "";
    }
    var checks = form.querySelectorAll("input[type=checkbox][name]:checked");
    var roles = [];
    for(var j=0;j<checks.length;j++){
      if(checks[j].id !== "consent") roles.push(checks[j].value || checks[j].name);
    }
    d.roles = roles;
    d.form_type = "b2b";
    d.page_url = window.location.href;
    d.timestamp = new Date().toISOString();
    return d;
  }
  form.addEventListener("submit", function(e){
    e.preventDefault();
    e.stopImmediatePropagation();
    var consent = document.getElementById("consent");
    if(consent && !consent.checked){
      alert("Per favore accetta la privacy policy per continuare.");
      return;
    }
    var btn = form.querySelector("button[type=submit]");
    if(btn) btn.disabled = true;
    var payload = getB2BData();
    try{ sessionStorage.setItem("toa_lead", JSON.stringify(payload)); }catch(ex){}
    var safetyTimer = setTimeout(function(){ goThankYou(); }, TIMEOUT);
    var ctrl = typeof AbortController!=="undefined" ? new AbortController() : null;
    var fetchTimer = setTimeout(function(){ if(ctrl) ctrl.abort(); }, TIMEOUT-1000);
    fetch(CRM, {
      method: "POST",
      headers: {"Content-Type":"application/json"},
      body: JSON.stringify(payload),
      signal: ctrl ? ctrl.signal : undefined
    })
    .then(function(r){ return r.json(); })
    .then(function(data){
      clearTimeout(fetchTimer);
      clearTimeout(safetyTimer);
      goThankYou();
    })
    .catch(function(err){
      clearTimeout(fetchTimer);
      clearTimeout(safetyTimer);
      goThankYou();
    });
  }, true);
})();
</script>';
}, 999);


// === TALENT PREVIEW su /tnx/ post-form B2B — 2026-05-30 marco ===
add_action('wp_footer', function() {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (strpos($uri, '/tnx') === false) return;
    ?>
    <style>
    #toa-talent-preview{background:#0a0a0a;padding:48px 20px;margin-top:0}
    .toa-preview-hd{text-align:center;margin-bottom:32px}
    .toa-preview-hd h3{font-size:clamp(18px,3vw,26px);font-weight:700;color:#fff;margin:0 0 8px}
    .toa-preview-hd p{color:rgba(255,255,255,.5);font-size:14px;margin:0}
    .toa-preview-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;max-width:960px;margin:0 auto 28px}
    .toa-card{position:relative;border-radius:10px;overflow:hidden;aspect-ratio:3/4;background:#1a1a1a}
    .toa-card img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .4s ease}
    .toa-card:hover img{transform:scale(1.04)}
    .toa-card-info{position:absolute;bottom:0;left:0;right:0;padding:10px 8px 8px;background:linear-gradient(transparent,rgba(0,0,0,.85));color:#fff;font-size:12px;line-height:1.3}
    .toa-card-info strong{display:block;font-weight:600}
    .toa-card-info span{color:rgba(255,255,255,.55)}
    .toa-preview-cta{text-align:center;margin-top:8px}
    .toa-preview-cta a{display:inline-block;padding:12px 28px;background:#c8ff00;color:#000;border-radius:6px;font-weight:700;font-size:14px;letter-spacing:.05em;text-decoration:none;transition:opacity .2s}
    .toa-preview-cta a:hover{opacity:.85}
    .toa-preview-loading{text-align:center;padding:32px;color:rgba(255,255,255,.3);font-size:13px;grid-column:1/-1}
    @media(max-width:480px){.toa-preview-grid{grid-template-columns:repeat(2,1fr);gap:8px}}
    </style>
    <script>
    (function(){
      var KEY = 'toa_lead';
      var ENDPOINT = '/crm_toagency/actions/api-talent-preview-b2b.php';
      var DB_URL   = '/talent-database/';
      function escH(s){return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');}
      function hideSec(){var s=document.getElementById('toa-talent-preview');if(s)s.remove();}
      function renderGrid(data){
        var grid=document.getElementById('toa-preview-grid');
        if(!grid)return;
        if(!data||!data.ok||!data.results||data.results.length===0){hideSec();return;}
        var html='';
        data.results.forEach(function(t){
          var age=t.eta?t.eta+' anni':'';
          var loc=t.citta?escH(t.citta):'';
          var sub=[age,loc].filter(Boolean).join(' · ');
          html+='<div class="toa-card"><img src="'+escH(t.foto_url)+'" alt="'+escH(t.nome||'talent')+'" loading="lazy" decoding="async" onerror="this.closest(\'.toa-card\').style.display=\'none\'"><div class="toa-card-info"><strong>'+escH(t.nome||'')+'</strong>'+(sub?'<span>'+sub+'</span>':'')+'</div></div>';
        });
        grid.innerHTML=html;
        try{sessionStorage.removeItem(KEY);}catch(e){}
      }
      function init(){
        var raw;try{raw=sessionStorage.getItem(KEY);}catch(e){return;}
        if(!raw)return;
        var lead;try{lead=JSON.parse(raw);}catch(e){return;}
        if(!lead||lead.form_type!=='b2b')return;
        var footer=document.querySelector('.tnx-footer');
        var wrap=document.querySelector('.tnx-wrap');
        if(!footer&&!wrap)return;
        var sec=document.createElement('section');
        sec.id='toa-talent-preview';
        var lang=(document.documentElement.lang||'it').substring(0,2);
        var _i18n={it:{h:'Alcuni profili che potrebbero fare al caso tuo',p:'In attesa della nostra risposta — aggiornati ogni giorno',loading:'Caricamento profili…',cta:'Vedi tutti i profili →'},en:{h:'Some profiles that might be right for you',p:'While we review your request — updated daily',loading:'Loading profiles…',cta:'View all profiles →'},fr:{h:'Des profils qui pourraient vous correspondre',p:'En attendant notre réponse — mis à jour chaque jour',loading:'Chargement des profils…',cta:'Voir tous les profils →'},es:{h:'Algunos perfiles que podrían interesarte',p:'Mientras revisamos tu solicitud — actualizados cada día',loading:'Cargando perfiles…',cta:'Ver todos los perfiles →'}};
        var _t=_i18n[lang]||_i18n.it;
        sec.innerHTML='<div class="toa-preview-hd"><h3>'+_t.h+'</h3><p>'+_t.p+'</p></div><div id="toa-preview-grid" class="toa-preview-grid"><div class="toa-preview-loading">'+_t.loading+'</div></div><div class="toa-preview-cta"><a href="'+DB_URL+'">'+_t.cta+'</a></div>';
        if(footer){footer.parentNode.insertBefore(sec,footer);}else{wrap.appendChild(sec);}
        setTimeout(function(){
          fetch(ENDPOINT,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({location:lead.location||'',event_type:lead.event_type||'',roles:Array.isArray(lead.roles)?lead.roles:[],budget:lead.budget||''})})
          .then(function(r){return r.json();})
          .then(function(d){renderGrid(d);})
          .catch(function(){hideSec();});
        },800);
      }
      if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',init);}else{init();}
    })();
    </script>
    <?php
}, 999);
// === END TALENT PREVIEW su /tnx/ — 2026-05-30 ===

// === LOGO SIZE OVERRIDE (bypass SG cache) ===
add_action('wp_head', function() {
    echo '<style>
    .nav-logo img { height: 48px !important; width: auto !important; }
    </style>';
}, 999);

// === LOGO SRC OVERRIDE (bypass SG cache) ===
add_action('wp_footer', function() {
    ?>
    <script>
    (function(){
        var w = document.querySelector('.logo-white');
        var b = document.querySelector('.logo-black');
        if(w) w.src = '/wp-content/uploads/2026/04/cropped-LOGO-TOA-WHITE.png';
        if(b) b.src = '/wp-content/uploads/2026/04/TOA-LOGO-BLACK.png';
    })();
    </script>
    <?php
}, 9999);

// === FIX: hlfSelect function + sticky price padding + WPML ===
add_action('wp_footer', function() {
    ?>
    <style>
    /* Fix: sticky price bar above CTA buttons */
    .hlf-sticky-price {
        bottom: 80px !important;
        z-index: 9998 !important;
    }
    /* Fix: page bottom padding so CTA doesn't cover content */
    body { padding-bottom: 80px !important; }
    .footer { margin-bottom: 0 !important; padding-bottom: 80px !important; }
    /* WPML language switcher styling */
    .wpml-ls { z-index: 9999 !important; }
    </style>
    <script>
    // Fix: define missing hlfSelect function
    function hlfSelect(el, group, value) {
        // Find parent step container
        var step = el.closest('.hlf-step');
        if (!step) return;
        // Remove active from all cards/btns in this step
        var siblings = step.querySelectorAll('.hlf-card, .hlf-btn');
        siblings.forEach(function(s) { s.classList.remove('active'); });
        // Add active to clicked element
        el.classList.add('active');
        // Store selection for form data
        if (!window.hlfSelections) window.hlfSelections = {};
        window.hlfSelections[group] = value;
        // Trigger price recalculation if function exists
        if (typeof hlfCalcPrice === 'function') hlfCalcPrice();
        if (typeof hlfUpdatePrice === 'function') hlfUpdatePrice();
    }
    </script>
    <?php
}, 998);

// === NAV COLLABORA LINK (bypass SG cache) ===
add_action('wp_footer', function() {
    ?>
    <script>
    (function(){
        // Clean up any b-t-l spurious links
        document.querySelectorAll('a[href*="b-t-l"]').forEach(function(a){a.remove();});
        // Desktop nav
        var nl = document.querySelector('.nav-links');
        if(nl && !nl.querySelector('a[href*="collabora"]')){
            var contatti = null;
            nl.querySelectorAll('a').forEach(function(a){if(a.textContent.trim()==='Contatti')contatti=a;});
            if(contatti){
                var a=document.createElement('a');a.href='/collabora/';a.textContent='Collabora';
                contatti.parentNode.insertBefore(a,contatti);
            }
        }
        // Mobile menu
        var mm = document.querySelector('.mobile-menu-inner');
        if(mm && !mm.querySelector('a[href*="collabora"]')){
            var mc = null;
            mm.querySelectorAll('a').forEach(function(a){if(a.textContent.trim()==='Contatti')mc=a;});
            if(mc){
                var a2=document.createElement('a');a2.href='/collabora/';a2.textContent='Collabora';
                mc.parentNode.insertBefore(a2,mc);
            }
        }
    })();
    </script>
    <?php
}, 9997);

// --- LANGUAGE SWITCHER CSS ---
add_action('wp_footer', function() { ?>
<style>
/* Nav Language Switcher (header) */
.nav .nav-lang {
    display: flex !important;
    align-items: center !important;
    gap: 2px !important;
    margin-left: 14px !important;
    border-left: 1px solid rgba(255,255,255,0.15) !important;
    padding-left: 14px !important;
}
.nav .nav-lang .nav-lang-item {
    font-size: 11px !important;
    font-weight: 700 !important;
    letter-spacing: 0.06em !important;
    color: rgba(255,255,255,0.35) !important;
    text-decoration: none !important;
    padding: 4px 7px !important;
    border-radius: 4px !important;
    transition: color .2s, background .2s !important;
}
.nav .nav-lang a.nav-lang-item:hover {
    color: #c8ff00 !important;
    background: rgba(200,255,0,0.1) !important;
}
.nav .nav-lang .nav-lang-item.active {
    color: #c8ff00 !important;
    background: rgba(200,255,0,0.13) !important;
}
/* Footer Language Switcher */
.footer-lang {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 18px;
    padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,0.1);
}
.footer-lang-item {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 0.05em;
    color: rgba(255,255,255,0.45);
    text-decoration: none;
    padding: 4px 10px;
    border-radius: 6px;
    transition: color .2s, background .2s;
}
a.footer-lang-item:hover {
    color: #c8ff00;
    background: rgba(200,255,0,0.08);
}
.footer-lang-item.active {
    color: #c8ff00;
    background: rgba(200,255,0,0.12);
}
/* Social Box - Casting page */
.social-box {
    background: rgba(255,255,255,0.03) !important;
    border: 1px solid rgba(255,255,255,0.08) !important;
    border-radius: 16px !important;
    padding: 32px 24px !important;
    margin: 28px 8px !important;
}
.social-box-title {
    font-size: 13px !important;
    font-weight: 700 !important;
    letter-spacing: 0.18em !important;
    text-transform: uppercase !important;
    color: #c8ff00 !important;
    margin-bottom: 8px !important;
}
.social-box-sub {
    font-size: 14px !important;
    color: rgba(255,255,255,0.45) !important;
    margin-bottom: 24px !important;
    max-width: 480px !important;
    margin-left: auto !important;
    margin-right: auto !important;
    line-height: 1.55 !important;
    text-transform: none !important;
    letter-spacing: 0 !important;
}
.social-buttons {
    display: flex !important;
    gap: 12px !important;
    justify-content: center !important;
}
.social-btn {
    display: inline-flex !important;
    flex-direction: column !important;
    align-items: center !important;
    gap: 6px !important;
    background: transparent !important;
    border: 1px solid rgba(255,255,255,0.12) !important;
    border-radius: 12px !important;
    padding: 16px 20px !important;
    min-width: 130px !important;
    max-width: 160px !important;
    color: #fff !important;
    text-decoration: none !important;
    transition: all 0.25s ease !important;
}
.social-btn:hover {
    background: rgba(200,255,0,0.06) !important;
    border-color: rgba(200,255,0,0.4) !important;
    box-shadow: 0 0 20px rgba(200,255,0,0.08) !important;
    transform: translateY(-2px) !important;
}
.social-btn-name {
    font-size: 13px !important;
    font-weight: 700 !important;
    letter-spacing: 0.1em !important;
    color: #fff !important;
    text-transform: uppercase !important;
}
.social-btn-desc {
    font-size: 11px !important;
    font-weight: 400 !important;
    color: rgba(255,255,255,0.4) !important;
    letter-spacing: 0.02em !important;
    text-transform: none !important;
    opacity: 1 !important;
    line-height: 1.3 !important;
}
/* Hide WPML default flag switcher */
.wpml-ls-statics-footer,
.wpml-ls-statics-post_translations,
#lang_sel_footer,
.wpml-ls-legacy-list-horizontal {
    display: none !important;
}
/* Hide nav-lang on mobile */
@media (max-width: 1080px) {
    .nav-lang { display: none !important; }
}
</style>
<?php
}, 9998);


// ════════════════════════════════════════════════════════════════════
// GDPR ADMIN PANEL — Registro Consensi + Data Retention + art. 17
// TOAgency — installato aprile 2026
// ════════════════════════════════════════════════════════════════════

// 1. Menu admin WP
add_action('admin_menu', function () {
    add_menu_page(
        'GDPR Consensi',
        'GDPR Consensi',
        'manage_options',
        'toa-gdpr-consensi',
        'toa_gdpr_admin_page',
        'dashicons-shield',
        30
    );
});

// 2. Pagina admin principale
function toa_gdpr_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Accesso negato');
    }
    global $wpdb;
    $msg = '';

    // Anonimizza per email (Diritto alla Cancellazione - art. 17 GDPR)
    if (isset($_POST['toa_anonymize_email']) && wp_verify_nonce($_POST['_wpnonce'] ?? '', 'toa_gdpr_action')) {
        $email = sanitize_email($_POST['target_email'] ?? '');
        if ($email) {
            $stamp = date('Y-m-d');
            $n = $wpdb->query($wpdb->prepare(
                "UPDATE consent_log SET email='[gdpr_cancellato]@toagency.it', ip_address='0.0.0.0', user_agent=CONCAT('ANONIMIZZATO art.17 GDPR - ',%s) WHERE email=%s",
                $stamp, $email
            ));
            $msg = '<div class=\'notice notice-success\'><p><strong>' . $n . '</strong> record anonimizzati per: <strong>' . esc_html($email) . '</strong></p></div>';
        }
    }

    // Anonimizza singolo record
    if (isset($_POST['toa_anonymize_id']) && wp_verify_nonce($_POST['_wpnonce'] ?? '', 'toa_gdpr_action')) {
        $id = intval($_POST['record_id'] ?? 0);
        if ($id > 0) {
            $stamp = date('Y-m-d');
            $wpdb->query($wpdb->prepare(
                "UPDATE consent_log SET email='[gdpr_cancellato]@toagency.it', ip_address='0.0.0.0', user_agent=CONCAT('ANONIMIZZATO art.17 GDPR - ',%s) WHERE id=%d",
                $stamp, $id
            ));
            $msg = '<div class=\'notice notice-success\'><p>Record #' . $id . ' anonimizzato.</p></div>';
        }
    }

    $search = sanitize_text_field($_GET['s'] ?? '');
    $paged  = max(1, intval($_GET['paged'] ?? 1));
    $limit  = 25;
    $offset = ($paged - 1) * $limit;
    $where_q = $search ? $wpdb->prepare("WHERE (email LIKE %s OR codice_lavoro LIKE %s)", '%'.$wpdb->esc_like($search).'%', '%'.$wpdb->esc_like($search).'%') : '';

    $total = (int) $wpdb->get_var("SELECT COUNT(*) FROM consent_log $where_q");
    $rows  = $wpdb->get_results("SELECT * FROM consent_log $where_q ORDER BY consent_timestamp DESC LIMIT $limit OFFSET $offset");
    $pages = max(1, ceil($total / $limit));

    echo $msg;
    ?>
    <div class="wrap">
    <h1>&#128274; GDPR &mdash; Registro Consensi (art. 7 RGPD)</h1>
    <p style="color:#555">Prova legale dei consensi. <strong>Non eliminare.</strong> Conservazione: 10 anni. Tabella: <code>consent_log</code>.</p>
    <div style="margin:12px 0">
    <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=toa_gdpr_csv&_wpnonce='.wp_create_nonce('toa_gdpr_csv'))); ?>" class="button">&#11015; Esporta CSV</a>
    </div>
    <div style="background:#fff8e1;border:1px solid #f9a825;padding:15px;border-radius:4px;margin-bottom:18px">
    <strong>Diritto alla Cancellazione (art. 17 GDPR)</strong><br>
    <small>Inserisci email per anonimizzare tutti i record di quel talent.</small>
    <form method="post" style="margin-top:8px" onsubmit="return confirm('Anonimizzare TUTTI i record per questa email?')">
    <?php wp_nonce_field('toa_gdpr_action'); ?>
    <input type="email" name="target_email" required placeholder="talent@esempio.it" style="width:280px">
    <input type="submit" name="toa_anonymize_email" value="Anonimizza" class="button button-primary">
    </form>
    </div>
    <form method="get" style="margin-bottom:10px">
    <input type="hidden" name="page" value="toa-gdpr-consensi">
    <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Cerca email o codice lavoro" style="width:260px">
    <input type="submit" value="Cerca" class="button">
    <?php if ($search) echo '<a href="?page=toa-gdpr-consensi" class="button">Cancella</a>'; ?>
    </form>
    <p>Totale: <strong><?php echo $total; ?></strong> record.<?php if($search) echo ' Filtro: <em>'.esc_html($search).'</em>'; ?></p>
    <table class="wp-list-table widefat fixed striped">
    <thead><tr>
    <th style="width:45px">ID</th><th>Email</th><th style="width:90px">Codice</th>
    <th style="width:32px" title="Consenso A">A</th><th style="width:32px" title="Consenso B">B</th><th style="width:32px" title="Consenso C">C</th>
    <th style="width:55px">Policy</th><th style="width:95px">IP</th><th style="width:145px">Data</th><th style="width:85px">Azione</th>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $r):
    $ia = strpos($r->email,'[gdpr_') !== false; ?>
    <tr<?php echo $ia?' style="opacity:.5"':''; ?>>
    <td><?php echo intval($r->id); ?></td>
    <td><?php echo esc_html($r->email); ?></td>
    <td><?php echo esc_html($r->codice_lavoro); ?></td>
    <td><?php echo $r->consenso_a?'&#10003;':'&mdash;'; ?></td>
    <td><?php echo $r->consenso_b?'&#10003;':'&mdash;'; ?></td>
    <td><?php echo $r->consenso_c?'&#10003;':'&mdash;'; ?></td>
    <td><?php echo esc_html($r->policy_version); ?></td>
    <td style="font-size:11px"><?php echo esc_html($r->ip_address); ?></td>
    <td style="font-size:12px"><?php echo esc_html($r->consent_timestamp); ?></td>
    <td><?php if(!$ia): ?>
    <form method="post" style="display:inline" onsubmit="return confirm('Anonimizzare #<?php echo intval($r->id); ?>?')">
    <?php wp_nonce_field('toa_gdpr_action'); ?>
    <input type="hidden" name="record_id" value="<?php echo intval($r->id); ?>">
    <input type="submit" name="toa_anonymize_id" value="Anon." class="button button-small">
    </form>
    <?php else: echo '<em>Anon.</em>'; endif; ?></td>
    </tr>
    <?php endforeach; ?>
    <?php if(empty($rows)) echo '<tr><td colspan="10" style="text-align:center;padding:25px">Nessun record.</td></tr>'; ?>
    </tbody></table>
    <?php if($pages>1): ?>
    <div style="margin-top:12px">
    <?php for($i=1;$i<=$pages;$i++): $u=add_query_arg(['page'=>'toa-gdpr-consensi','paged'=>$i]+($search?['s'=>$search]:[]),admin_url('admin.php')); ?>
    <a href="<?php echo esc_url($u); ?>" class="button <?php echo $i===$paged?'button-primary':''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?></div>
    <?php endif; ?>
    <hr style="margin-top:25px">
    <h3>Info tecniche</h3>
    <table class="widefat" style="max-width:480px">
    <tr><td><strong>Database</strong></td><td><?php echo DB_NAME; ?></td></tr>
    <tr><td><strong>Tabella</strong></td><td>consent_log</td></tr>
    <tr><td><strong>Retention</strong></td><td>10 anni (anonimizzazione automatica settimanale)</td></tr>
    <tr><td><strong>Prossima</strong></td><td><?php $nx=wp_next_scheduled('toa_gdpr_retention'); echo $nx?date('d/m/Y H:i',$nx):'Non schedulata'; ?></td></tr>
    </table>
    </div>
    <?php
}

// 3. Export CSV
add_action('wp_ajax_toa_gdpr_csv', function () {
    if (!current_user_can('manage_options')) wp_die('Unauthorized');
    if (!wp_verify_nonce($_GET['_wpnonce'] ?? '', 'toa_gdpr_csv')) wp_die('Nonce invalid');
    global $wpdb;
    $rows = $wpdb->get_results("SELECT id,form_id,email,codice_lavoro,ip_address,policy_version,consenso_a,consenso_b,consenso_c,dichiarazione_dati,consent_timestamp,client_timestamp,step_completed FROM consent_log ORDER BY consent_timestamp DESC", ARRAY_A);
    $fn = 'gdpr_consensi_' . date('Ymd_His') . '.csv';
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="'.$fn.'"');
    header('Pragma: no-cache');
    $out = fopen('php://output','w');
    fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
    if (!empty($rows)) { fputcsv($out,array_keys($rows[0])); foreach($rows as $r) fputcsv($out,$r); }
    fclose($out); exit;
});

// 4. Data Retention: anonimizza record > 10 anni (WP-Cron settimanale)
add_action('toa_gdpr_retention', function () {
    global $wpdb;
    $cutoff = date('Y-m-d H:i:s', strtotime('-10 years'));
    $stamp  = date('Y-m-d');
    $n = $wpdb->query($wpdb->prepare(
        "UPDATE consent_log SET email='[gdpr_scaduto_10a]@toagency.it', ip_address='0.0.0.0', user_agent=CONCAT('RETENTION_10ANNI - ',%s) WHERE consent_timestamp < %s AND email NOT LIKE '[gdpr_%'",
        $stamp, $cutoff
    ));
    if ($n > 0) error_log("[GDPR] Data retention: $n record anonimizzati (ante $cutoff)");
});
if (!wp_next_scheduled('toa_gdpr_retention')) {
    wp_schedule_event(time(), 'weekly', 'toa_gdpr_retention');
}

// === BEGIN FIX 2026-05-22 marco — REDIRECT IT SLUG ===
// Reindirizza con 301 chi digita slug italiani intuitivi verso pagine esistenti.
// Hook init priority 1 per girare PRIMA del redirect_canonical di WP
// (che cattura slug parziali, es. /modelli → /cerchiamo-modelli-a-rimini/modelli).
add_action('init', function() {
    if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX) || (defined('DOING_CRON') && DOING_CRON)) return;
    $req = rtrim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');
    $req = strtolower($req);
    $map = [
        '/modelli'    => '/models/',
        '/attori'     => '/actors/',
        '/contatti'   => '/contact-us/',
        '/hostess'    => '/hostess-steward/',
        '/preventivo' => '/form-b2b/',
        '/servizi'    => '/b2bservices/',
        '/talenti'    => '/talent-database/',
        // === ADDED 2026-05-24 marco — typo terms + crew-database disabled ===
        '/therms-and-conditions'    => '/terms-and-conditions/',
        '/en/therms-and-conditions' => '/en/terms-and-conditions/',
        '/es/therms-and-conditions' => '/es/terms-and-conditions/',
        '/crew-database'            => '/talent-database/',
    ];
    if (isset($map[$req])) {
        wp_safe_redirect(home_url($map[$req]), 301);
        exit;
    }
}, 1);
// === END FIX 2026-05-22 marco — REDIRECT IT SLUG ===

// === BEGIN FIX 2026-05-23 marco — PERF QUICK WINS ===
// 1) Lazy load + async decoding su tutte le img frontend (no admin)
add_filter('wp_get_attachment_image_attributes', function($attr) {
    if (is_admin()) return $attr;
    if (empty($attr['loading'])) $attr['loading'] = 'lazy';
    if (empty($attr['decoding'])) $attr['decoding'] = 'async';
    return $attr;
}, 99);

// Anche per immagini inline (the_content)
add_filter('wp_lazy_loading_enabled', '__return_true');

// 2) Defer JS non critici (NO jquery che serve sync per molti plugin)
add_filter('script_loader_tag', function($tag, $handle) {
    if (is_admin()) return $tag;
    $defer_handles = ['lazysizes', 'toagency-main', 'main', 'wp-embed'];
    foreach ($defer_handles as $h) {
        if (strpos($handle, $h) !== false && strpos($tag, 'defer') === false && strpos($tag, 'async') === false) {
            return str_replace(' src=', ' defer src=', $tag);
        }
    }
    return $tag;
}, 10, 2);

// 3) Preconnect ai domini esterni critici
add_action('wp_head', function() {
    if (is_admin()) return;
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://www.googletagmanager.com">' . "\n";
    echo '<link rel="dns-prefetch" href="https://www.google-analytics.com">' . "\n";
}, 1);
// === END FIX 2026-05-23 marco — PERF QUICK WINS ===

// === BEGIN FIX 2026-05-23 marco — _t_raw helper for page-hero title (i18n) ===
// Ritorna stringa RAW (no esc_html) — chi la usa è responsabile dell'escape.
// Usato per passare titoli i18n a toa_component('page-hero', ['title' => _t_raw([...])])
// che internamente fa esc_html. Evita double-escape vs _ht() di page-home.php.
function _t_raw($strings) {
    $lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
    if (!in_array($lang, array('it','en','fr','es'))) $lang = 'it';
    return isset($strings[$lang]) ? $strings[$lang] : (isset($strings['it']) ? $strings['it'] : '');
}
// === END FIX 2026-05-23 marco — _t_raw helper ===

// === FIX 2026-05-30 marco — schema markup #33 ===
require_once get_template_directory() . '/inc/schema.php';

// === FIX 2026-05-30 marco — google-reviews CSS ===
add_action('wp_enqueue_scripts', function() {
    $toa_gr_css = get_template_directory() . '/assets/css/google-reviews.css';
    wp_enqueue_style(
        'toa-google-reviews',
        get_template_directory_uri() . '/assets/css/google-reviews.css',
        [],
        file_exists($toa_gr_css) ? filemtime($toa_gr_css) : '2026-05-30'
    );
});

// === FIX 2026-05-30 marco — landing-geo CSS ===
add_action('wp_enqueue_scripts', function() {
    if (is_page_template('templates/page-landing-geo.php') || is_page_template('templates/page-landing-hub.php')) {
        $f = get_template_directory() . '/assets/css/landing-geo.css';
        wp_enqueue_style('toa-landing-geo', get_template_directory_uri().'/assets/css/landing-geo.css', [], file_exists($f) ? filemtime($f) : '1');
    }
});

// === FIX 2026-05-31 marco — nascondi CTA "Preventivo" sulle pagine form registrati-* (talent/crew) ===
// Sul form di registrazione il CTA preventivo (nav desktop, mobile menu, sticky mobile) è fuori contesto.
function toa_is_registrati_page() {
    if (!is_page()) return false;
    $slug = get_post_field('post_name', get_queried_object_id());
    return $slug && strpos($slug, 'registrati') === 0; // registrati-talent, registrati-crew, ...
}
add_filter('body_class', function($classes) {
    if (toa_is_registrati_page()) $classes[] = 'toa-hide-quote-cta';
    return $classes;
});
add_action('wp_head', function() {
    if (!toa_is_registrati_page()) return;
    echo '<style id="toa-hide-quote-cta-css">body.toa-hide-quote-cta .nav-cta-btn,body.toa-hide-quote-cta .sticky-cta-mobile,body.toa-hide-quote-cta .mobile-menu-cta{display:none!important}</style>' . "\n";
});

// === BEGIN 2026-06-03 marco — LOGHI + GRIGLIA TALENT su landing casting (CRO) ===
// Inietta su /casting-torino/ e /casting-roma/ + traduzioni WPML EN/FR/ES (template page-landing-geo):
//  A) striscia loghi clienti (replica brand-ticker di /form-b2b/, CSS globale gia caricato)
//  B) griglia 12 talent anonimi della provincia, fetch 30 + shuffle, fallback silenzioso
// Testi localizzati via get_locale(); PROV resta il nome IT (match provincia_residenza nel DB).
// Pattern: stesso del blocco "TALENT PREVIEW su /tnx/" (iniezione wp_footer, no template edit).
add_action('wp_footer', function() {
    $prov_map = array(
        // Torino (it + traduzioni WPML)
        'casting-torino'       => 'Torino',
        'casting-turin-italy'  => 'Torino', // en
        'casting-turin-italie' => 'Torino', // fr
        'casting-turin-italia' => 'Torino', // es
        // Roma (it + traduzioni WPML)
        'casting-roma'         => 'Roma',
        'casting-rome-italy'   => 'Roma',   // en
        'casting-rome-italie'  => 'Roma',   // fr
        'casting-roma-italia'  => 'Roma',   // es
    );
    $provincia = null;
    foreach ($prov_map as $slug => $prov) {
        if (is_page($slug)) { $provincia = $prov; break; }
    }
    if ($provincia === null) return;
    ?>
    <style>
    #toa-cast-talent{padding:8px 16px 4px;max-width:1080px;margin:0 auto}
    .toa-cast-hd{text-align:center;margin:0 0 22px}
    .toa-cast-hd h2{font-family:var(--font-display,Georgia,serif);font-size:clamp(20px,3vw,28px);font-weight:900;color:var(--white,#fff);margin:0 0 6px;text-transform:uppercase;letter-spacing:.5px}
    .toa-cast-hd p{font-size:12px;color:var(--gray-4,rgba(255,255,255,.45));margin:0;text-transform:uppercase;letter-spacing:1px}
    .toa-cast-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:14px}
    .toa-cast-card{position:relative;border-radius:14px;overflow:hidden;aspect-ratio:3/4;background:#141414;border:1px solid rgba(255,255,255,.06)}
    .toa-cast-card img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .45s cubic-bezier(.2,.8,.3,1)}
    .toa-cast-card:hover img{transform:scale(1.05)}
    .toa-cast-card .ov{position:absolute;left:0;right:0;bottom:0;padding:14px 12px 11px;background:linear-gradient(transparent,rgba(0,0,0,.88));color:#fff}
    .toa-cast-card .ov b{display:block;font-size:13px;font-weight:700;letter-spacing:.4px}
    .toa-cast-card .ov span{display:block;font-size:12px;color:rgba(255,255,255,.6);margin-top:2px}
    .toa-cast-cta{text-align:center;margin:26px 0 6px}
    .toa-cast-cta a{display:inline-block;padding:14px 30px;background:#c8ff00;color:#000;border-radius:8px;font-weight:700;font-size:14px;letter-spacing:.04em;text-decoration:none;transition:opacity .2s}
    .toa-cast-cta a:hover{opacity:.85}
    @media(max-width:900px){.toa-cast-grid{grid-template-columns:repeat(3,1fr);gap:12px}}
    @media(max-width:520px){.toa-cast-grid{grid-template-columns:repeat(2,1fr);gap:10px}}
    </style>
    <script>
    (function(){
      if (document.getElementById('toa-cast-talent')) return; // anti doppia iniezione
      var hero = document.querySelector('.toa-landing-hero');
      if (!hero) return;

      var PROV = <?php echo json_encode($provincia, JSON_UNESCAPED_UNICODE); ?>;
      var API  = '/actions/api-talent-database.php?action=search';
      var FOTO = '/actions/foto-talent-public.php?id=';
      var SHOW = 12, FETCH = 30;

      // i18n: testi localizzati via get_locale() (fallback prefisso lingua, poi it_IT)
      var I18N = {
        'it_IT':{title:'ALCUNI PROFILI DISPONIBILI A',sub:'Selezione aggiornata ogni giorno · <strong>20.000+ profili nel database</strong>',note:'Questi sono solo alcuni profili. Selezione personalizzata in 24h.',cta:'Richiedi una selezione personalizzata →'},
        'fr_FR':{title:'QUELQUES PROFILS DISPONIBLES À',sub:'Sélection mise à jour chaque jour · <strong>20 000+ profils dans la base</strong>',note:'Ce ne sont que quelques profils. Sélection personnalisée en 24h.',cta:'Demander une sélection personnalisée →'},
        'es_ES':{title:'ALGUNOS PERFILES DISPONIBLES EN',sub:'Selección actualizada cada día · <strong>20.000+ perfiles en la base</strong>',note:'Estos son solo algunos perfiles. Selección personalizada en 24h.',cta:'Solicitar una selección personalizada →'},
        'en_US':{title:'SOME PROFILES AVAILABLE IN',sub:'Selection updated daily · <strong>20,000+ profiles in database</strong>',note:'These are just some profiles. Personalised selection in 24h.',cta:'Request a personalised selection →'}
      };
      var lang = <?php echo json_encode(get_locale()); ?>;
      var byShort = {it:'it_IT',fr:'fr_FR',es:'es_ES',en:'en_US'};
      var TX = I18N[lang] || I18N[byShort[(lang||'').slice(0,2)]] || I18N['it_IT'];

      function esc(s){return String(s==null?'':s).replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}
      function shuffle(a){for(var i=a.length-1;i>0;i--){var j=Math.floor(Math.random()*(i+1));var t=a[i];a[i]=a[j];a[j]=t;}return a;}

      // --- A) STRISCIA LOGHI (replica esatta brand-ticker /form-b2b/) ---
      var b1=[{t:'JUVENTUS',c:'b-juventus'},{t:'FERRARI',c:'b-ferrari'},{t:'BMW',c:'b-bmw'},{t:'SAMSUNG',c:'b-samsung'},{t:'RED BULL',c:'b-redbull'},{t:'MERCEDES-BENZ',c:'b-mercedes'},{t:'VOGUE SPOSA',c:'b-vogue'},{t:'KAPPA',c:'b-kappa'},{t:'SKY',c:'b-sky'},{t:'MASERATI',c:'b-maserati'},{t:'FIAT',c:'b-fiat'},{t:'VODAFONE',c:'b-vodafone'},{t:'AUDI',c:'b-audi'},{t:'JEEP',c:'b-jeep'},{t:'MICHELIN',c:'b-michelin'},{t:'KINDER',c:'b-kinder'},{t:"L'ORÉAL",c:'b-loreal'},{t:'GQ ITALIA',c:'b-gq'},{t:'ALFA ROMEO',c:'b-alfaromeo'},{t:'EATALY',c:'b-eataly'},{t:'K-WAY',c:'b-kway'},{t:'FORMULA 1',c:'b-formula1'},{t:'MOTOGP',c:'b-motogp'},{t:'LUXOTTICA',c:'b-luxottica'},{t:'SANREMO',c:'b-sanremo'},{t:'MISS UNIVERSE',c:'b-missuniverse'},{t:'EDISON',c:'b-edison'},{t:'QC TERME',c:'b-qcterme'},{t:'POLICE',c:'b-police'}];
      var b2=[{t:'FC INTERNAZIONALE',c:'b-inter'},{t:'WOLT',c:'b-wolt'},{t:'AXA',c:'b-axa'},{t:'SERIE A',c:'b-seriea'},{t:'LOACKER',c:'b-loacker'},{t:'MEDIASET',c:'b-mediaset'},{t:'PARIS FASHION WEEK',c:'b-pfw'},{t:'SALONE DEL MOBILE',c:'b-salone'},{t:'LA RINASCENTE',c:'b-rinascente'},{t:'EXPO 2015',c:'b-expo'},{t:'FIERA MILANO',c:'b-fieramilano'},{t:'RIMINI FIERA',c:'b-rimini'},{t:'BOLOGNA FIERE',c:'b-bologna'},{t:'TEATRO REGIO',c:'b-teatro'},{t:'VIRGIN ACTIVE',c:'b-virgin'},{t:'WRANGLER',c:'b-wrangler'},{t:'FIORUCCI',c:'b-fiorucci'},{t:'TORINO FC',c:'b-torino'},{t:'ALGIDA',c:'b-algida'},{t:'MIZUNO',c:'b-mizuno'},{t:'KINGS LEAGUE',c:'b-kingsleague'},{t:'AIA',c:'b-aia'},{t:'REVLON',c:'b-revlon'},{t:'COIN',c:'b-coin'}];
      function row(arr){return arr.concat(arr).map(function(b){return '<span class="'+b.c+'">'+b.t+'</span>';}).join('');}
      var brandSec=document.createElement('section');
      brandSec.className='brand-section';
      brandSec.innerHTML='<div class="brand-label">Hanno scelto TOAgency</div><div class="ticker-row">'+row(b1)+'</div><div class="ticker-row reverse">'+row(b2)+'</div>';

      // --- B) SEZIONE GRIGLIA (nascosta finche non ci sono risultati) ---
      var sec=document.createElement('section');
      sec.id='toa-cast-talent';
      sec.style.display='none';
      sec.innerHTML='<div class="toa-cast-hd"><h2>'+TX.title+' '+esc(PROV)+'</h2><p>'+TX.sub+'</p></div><div class="toa-cast-grid" id="toaCastGrid"></div><div class="toa-cast-cta"><a href="#toa-form-anchor">'+TX.cta+'</a><p style="font-size:11px;color:rgba(255,255,255,.4);margin:10px 0 0;text-align:center">'+TX.note+'</p></div>';

      // inserisci subito dopo l'hero: loghi, poi griglia
      hero.parentNode.insertBefore(brandSec, hero.nextSibling);
      brandSec.parentNode.insertBefore(sec, brandSec.nextSibling);

      // CTA smooth-scroll al form
      sec.querySelector('.toa-cast-cta a').addEventListener('click',function(e){
        var tgt=document.querySelector('#toa-form-anchor')||document.querySelector('#leadForm');
        if(tgt){e.preventDefault();tgt.scrollIntoView({behavior:'smooth',block:'start'});}
      });

      // fetch talent (fallback: nascondi solo la griglia, i loghi restano)
      fetch(API,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({provincia:PROV,per_page:FETCH,page:1})})
        .then(function(r){return r.json();})
        .then(function(d){
          if(!d||!d.ok||!d.results||!d.results.length){ sec.remove(); return; }
          var list=shuffle(d.results.slice()).slice(0,SHOW);
          document.getElementById('toaCastGrid').innerHTML=list.map(function(t){
            var m=[]; if(t.eta)m.push(t.eta+' anni'); if(t.altezza)m.push(t.altezza+' cm'); if(t.citta)m.push(t.citta);
            return '<div class="toa-cast-card"><img src="'+FOTO+encodeURIComponent(t.id)+'" alt="Profilo" loading="lazy" decoding="async" onerror="this.closest(\'.toa-cast-card\').remove()" onload="if(!this.naturalWidth||this.naturalWidth<10)this.closest(\'.toa-cast-card\').remove()"><div class="ov"><b>Profilo #'+esc(t.id)+'</b>'+(m.length?'<span>'+esc(m.join(' · '))+'</span>':'')+'</div></div>';
          }).join('');
          sec.style.display='';
        })
        .catch(function(){ try{sec.remove();}catch(e){} });
    })();
    </script>
    <?php
}, 999);
// === END 2026-06-03 marco — LOGHI + GRIGLIA TALENT casting ===
