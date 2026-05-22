<?php
/**
 * Template Name: Student Program Confirm
 * Pagina di conferma post-submit del form Student Program — multilingua IT/EN/FR/ES
 * NON è la /tnx/ (che è goal Google Ads B2B) — pagina dedicata, non tracciata come conversione Ads.
 */
require_once get_theme_file_path('templates/translations.php');
toa_component('header');

// Shortcut helper
function spc_t($key) { return toa_t('student_program', $key); }
?>

<!-- ══════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════ -->
<section class="page-hero" style="text-align:center;padding-bottom:20px">
  <div class="container">
    <div style="font-size:2.5rem;margin-bottom:16px">✓</div>
    <div class="section-eyebrow" style="margin-bottom:12px"><?php echo spc_t('confirm_eyebrow'); ?></div>
    <h1 class="page-hero-title" style="max-width:780px;margin:0 auto 16px"><?php echo spc_t('confirm_title'); ?></h1>
    <p class="page-hero-subtitle" style="max-width:640px;margin:0 auto"><?php echo spc_t('confirm_body'); ?></p>
  </div>
</section>

<?php toa_component('brand-ticker'); ?>

<!-- ══════════════════════════════════════════════
     COSA SUCCEDE ORA
═══════════════════════════════════════════════ -->
<section class="why-section" style="padding-bottom:20px">
  <div class="container">
    <div class="section-eyebrow"><?php echo spc_t('confirm_steps_title'); ?></div>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="spc-step-num">1</div>
      <h3 class="feature-title"><?php echo spc_t('confirm_step_1'); ?></h3>
    </div>
    <div class="feature-card">
      <div class="spc-step-num">2</div>
      <h3 class="feature-title"><?php echo spc_t('confirm_step_2'); ?></h3>
    </div>
    <div class="feature-card">
      <div class="spc-step-num">3</div>
      <h3 class="feature-title"><?php echo spc_t('confirm_step_3'); ?></h3>
    </div>
    <div class="feature-card">
      <div class="spc-step-num">4</div>
      <h3 class="feature-title"><?php echo spc_t('confirm_step_4'); ?></h3>
    </div>
    <div class="feature-card">
      <div class="spc-step-num">5</div>
      <h3 class="feature-title"><?php echo spc_t('confirm_step_5'); ?></h3>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     NOTICE — SELETTIVO / NON COMMERCIALE
═══════════════════════════════════════════════ -->
<section style="max-width:700px;margin:0 auto;padding:0 16px 32px">
  <div style="border:1px solid #c8ff00;padding:20px 24px;background:rgba(200,255,0,0.04)">
    <div style="display:flex;align-items:flex-start;gap:12px">
      <span style="font-size:1.2rem;flex-shrink:0;margin-top:2px">⚠️</span>
      <p style="font-size:0.82rem;line-height:1.65;opacity:0.85;margin:0"><?php echo spc_t('confirm_notice'); ?></p>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     DATABASE CREATIVI — CTA registrati-crew
═══════════════════════════════════════════════ -->
<section style="max-width:700px;margin:0 auto;padding:20px 16px 32px">
  <div class="feature-card" style="padding:32px 24px;text-align:center">
    <h2 class="feature-title" style="font-size:1.3rem;margin-bottom:12px"><?php echo spc_t('confirm_db_title'); ?></h2>
    <p class="feature-text" style="margin-bottom:24px"><?php echo spc_t('confirm_db_text'); ?></p>
    <a id="crewSignupLink"
       href="<?php echo esc_url(home_url('/registrati-crew/')); ?>"
       class="btn-hero btn-hero-primary"
       style="display:inline-block;padding:16px 32px;font-size:0.95rem;text-decoration:none">
      <?php echo spc_t('confirm_db_btn'); ?> →
    </a>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     SOCIAL FOLLOW
═══════════════════════════════════════════════ -->
<section style="max-width:700px;margin:0 auto;padding:20px 16px 100px;text-align:center">
  <p style="font-size:0.85rem;opacity:0.7;margin-bottom:16px"><?php echo spc_t('confirm_follow'); ?></p>
  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
    <a href="https://instagram.com/toagency" target="_blank" rel="noopener"
       class="spc-social-btn">
      Instagram @toagency
    </a>
    <a href="https://tiktok.com/@toagency" target="_blank" rel="noopener"
       class="spc-social-btn">
      TikTok @toagency
    </a>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     STYLES
═══════════════════════════════════════════════ -->
<style>
.spc-step-num{
  display:inline-flex;align-items:center;justify-content:center;
  width:32px;height:32px;border:1px solid var(--gray-3);
  font-size:0.85rem;font-weight:700;margin-bottom:10px;
  color:var(--gray-5);
}
.spc-social-btn{
  display:inline-block;padding:12px 20px;
  border:1px solid var(--gray-3);
  font-size:0.78rem;font-weight:600;
  text-transform:uppercase;letter-spacing:0.5px;
  color:var(--white);text-decoration:none;
  transition:all 0.15s;
}
.spc-social-btn:hover{
  background:var(--white);color:var(--black);border-color:var(--white);
}
</style>

<!-- ══════════════════════════════════════════════
     PREFILL CREW SIGNUP — passa dati student al form crew
═══════════════════════════════════════════════ -->
<script>
(function(){
  try {
    var raw = sessionStorage.getItem('toa_crew_prefill');
    if (!raw) return;
    var data = JSON.parse(raw);
    if (!data || !data.email) return;
    // Encode in base64 url-safe per evitare query string leggibile e lunga
    var token = btoa(unescape(encodeURIComponent(JSON.stringify(data))))
                  .replace(/\+/g,'-').replace(/\//g,'_').replace(/=+$/,'');
    var link = document.getElementById('crewSignupLink');
    if (link) {
      var sep = link.href.indexOf('?') >= 0 ? '&' : '?';
      link.href = link.href + sep + 'prefill=' + token;
    }
  } catch(e){}
})();
</script>

<?php toa_component('footer'); ?>
