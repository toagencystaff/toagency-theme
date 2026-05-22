<?php
/**
 * Template Name: Student Program
 * Pagina candidatura Student Program — multilingua IT/EN/FR/ES
 * Form → CRM toagency + email toagencystaff@gmail.com
 */
require_once get_theme_file_path('templates/translations.php');
toa_component('header');

$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es')) ? $lang : 'it';

// Shortcut helper
function sp_t($key) { return toa_t('student_program', $key); }

// JS strings injected via PHP
$js = array(
    'sending'       => esc_js(sp_t('js_sending')),
    'alertPrivacy'  => esc_js(sp_t('js_alert_privacy')),
    'alertRole'     => esc_js(sp_t('js_alert_role')),
    'errorMsg'      => esc_js(sp_t('js_error_msg')),
    'waMessage'     => esc_js(sp_t('js_wa_message')),
    'fileBig'       => esc_js(sp_t('js_file_too_big')),
    'maxFiles'      => esc_js(sp_t('js_max_files')),
    'processing'    => esc_js(sp_t('moodboard_processing')),
    'submitBtn'     => esc_js(sp_t('submit_btn')),
);

// Roles list: value => translation key
$roles = array(
    'Photographer'      => 'role_photographer',
    'Videomaker'        => 'role_videomaker',
    'Retoucher'         => 'role_retoucher',
    'Color Grader'      => 'role_colorgrader',
    'Fashion Stylist'   => 'role_stylist',
    'Costume Designer'  => 'role_costume',
    'Prop Stylist'      => 'role_propstylist',
    'Fashion Designer'  => 'role_fashiondesigner',
    'Accessories Designer' => 'role_accessdesigner',
    'Makeup Artist'     => 'role_makeup',
    'Hair Stylist'      => 'role_hair',
    'Nail Artist'       => 'role_nailartist',
    'Creative Director' => 'role_creativeDir',
    'Art Director'      => 'role_artdir',
    'Set Designer'      => 'role_setdesigner',
    'Graphic Designer'  => 'role_graphicdesigner',
    'Illustrator'       => 'role_illustrator',
    'Content Creator'   => 'role_contentcreator',
    'Fashion Writer'    => 'role_journalist',
    'Producer'          => 'role_producer',
    'Casting Director'  => 'role_casting',
    'Other'             => 'role_other',
);
?>

<!-- ══════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════ -->
<section class="page-hero" style="text-align:center;padding-bottom:20px">
  <div class="container">
    <div class="section-eyebrow" style="margin-bottom:12px"><?php echo sp_t('hero_eyebrow'); ?></div>
    <h1 class="page-hero-title" style="max-width:780px;margin:0 auto 16px"><?php echo sp_t('hero_title'); ?></h1>
    <p class="page-hero-subtitle" style="max-width:640px;margin:0 auto"><?php echo sp_t('hero_subtitle'); ?></p>
  </div>
</section>

<?php toa_component('brand-ticker'); ?>

<!-- ══════════════════════════════════════════════
     PROGRAM BLOCKS
═══════════════════════════════════════════════ -->
<section class="why-section" style="padding-bottom:20px">
  <div class="container">
    <div class="section-eyebrow"><?php echo sp_t('program_eyebrow'); ?></div>
    <h2 class="section-heading" style="margin-bottom:32px"><?php echo sp_t('program_heading'); ?></h2>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div style="font-size:1.5rem;margin-bottom:10px">🎬</div>
      <h3 class="feature-title"><?php echo sp_t('block1_title'); ?></h3>
      <p class="feature-text"><?php echo sp_t('block1_text'); ?></p>
    </div>
    <div class="feature-card">
      <div style="font-size:1.5rem;margin-bottom:10px">💸</div>
      <h3 class="feature-title"><?php echo sp_t('block2_title'); ?></h3>
      <p class="feature-text"><?php echo sp_t('block2_text'); ?></p>
    </div>
    <div class="feature-card">
      <div style="font-size:1.5rem;margin-bottom:10px">📰</div>
      <h3 class="feature-title"><?php echo sp_t('block3_title'); ?></h3>
      <p class="feature-text"><?php echo sp_t('block3_text'); ?></p>
    </div>
    <div class="feature-card">
      <div style="font-size:1.5rem;margin-bottom:10px">🤝</div>
      <h3 class="feature-title"><?php echo sp_t('block4_title'); ?></h3>
      <p class="feature-text"><?php echo sp_t('block4_text'); ?></p>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     DISCLAIMER NON-COMMERCIALE
═══════════════════════════════════════════════ -->
<section style="max-width:700px;margin:0 auto;padding:0 16px 32px">
  <div style="border:1px solid #c8ff00;padding:20px 24px;background:rgba(200,255,0,0.04)">
    <div style="display:flex;align-items:flex-start;gap:12px">
      <span style="font-size:1.2rem;flex-shrink:0;margin-top:2px">⚠️</span>
      <div>
        <p style="font-size:0.7rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:#c8ff00;margin-bottom:8px"><?php echo sp_t('disclaimer_title'); ?></p>
        <p style="font-size:0.82rem;line-height:1.65;opacity:0.85"><?php echo sp_t('disclaimer_text'); ?></p>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     FORM
═══════════════════════════════════════════════ -->
<section style="max-width:700px;margin:0 auto;padding:20px 16px 100px">
  <div class="feature-card" style="padding:32px 24px">


    <form id="studentForm" novalidate>

      <!-- ─── CHI SEI ─── -->
      <div class="sp-section-label"><?php echo sp_t('section_who'); ?></div>

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label"><?php echo sp_t('role_label'); ?></label>
        <div class="sp-chips" id="rolesGrid">
          <?php foreach ($roles as $value => $key) : ?>
          <label class="sp-chip">
            <input type="checkbox" name="roles[]" value="<?php echo esc_attr($value); ?>">
            <span><?php echo sp_t($key); ?></span>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Altro: testo libero (visibile solo se "Other" checked) -->
      <div class="form-group" id="otherRoleWrap" style="display:none;margin-bottom:16px">
        <label class="form-label" for="role_other_text"><?php echo sp_t('label_role_other'); ?></label>
        <input type="text" id="role_other_text" name="role_other_text" class="form-input"
               placeholder="<?php echo esc_attr(sp_t('ph_role_other')); ?>">
      </div>

      <!-- ─── CONTATTI ─── -->
      <div class="sp-section-label"><?php echo sp_t('section_contacts'); ?></div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="full_name"><?php echo sp_t('label_name'); ?> *</label>
          <input type="text" id="full_name" name="full_name" required class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_name')); ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="dob"><?php echo sp_t('label_dob'); ?> *</label>
          <input type="date" id="dob" name="dob" required class="form-input" style="color-scheme:dark">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="email"><?php echo sp_t('label_email'); ?> *</label>
          <input type="email" id="email" name="email" required class="form-input"
                 placeholder="email@example.com">
        </div>
        <div class="form-group">
          <label class="form-label" for="phone"><?php echo sp_t('label_phone'); ?></label>
          <input type="tel" id="phone" name="phone" class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_phone')); ?>">
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label" for="country"><?php echo sp_t('label_country'); ?> *</label>
        <input type="text" id="country" name="country" required class="form-input"
               placeholder="<?php echo esc_attr(sp_t('ph_country')); ?>">
      </div>

      <!-- ─── SOCIAL ─── -->
      <div class="sp-section-label"><?php echo sp_t('section_social'); ?></div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="instagram"><?php echo sp_t('label_instagram'); ?></label>
          <input type="text" id="instagram" name="instagram" class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_instagram')); ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="tiktok"><?php echo sp_t('label_tiktok'); ?></label>
          <input type="text" id="tiktok" name="tiktok" class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_tiktok')); ?>">
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
        <div class="form-group">
          <label class="form-label" for="youtube"><?php echo sp_t('label_youtube'); ?></label>
          <input type="text" id="youtube" name="youtube" class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_youtube')); ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="portfolio"><?php echo sp_t('label_portfolio'); ?></label>
          <input type="url" id="portfolio" name="portfolio" class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_portfolio')); ?>">
        </div>
      </div>

      <!-- ─── SCUOLA ─── -->
      <div class="sp-section-label"><?php echo sp_t('section_school'); ?></div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="school_name"><?php echo sp_t('label_school_name'); ?> *</label>
          <input type="text" id="school_name" name="school_name" required class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_school_name')); ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="school_city"><?php echo sp_t('label_school_city'); ?> *</label>
          <input type="text" id="school_city" name="school_city" required class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_school_city')); ?>">
        </div>
      </div>
      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label" for="course"><?php echo sp_t('label_course'); ?></label>
        <input type="text" id="course" name="course" class="form-input"
               placeholder="<?php echo esc_attr(sp_t('ph_course')); ?>">
      </div>

      <!-- ─── PROGETTO ─── -->
      <div class="sp-section-label"><?php echo sp_t('section_project'); ?></div>

      <div class="form-group" style="margin-bottom:12px">
        <label class="form-label" for="project_title"><?php echo sp_t('label_project_title'); ?> *</label>
        <input type="text" id="project_title" name="project_title" required class="form-input"
               placeholder="<?php echo esc_attr(sp_t('ph_project_title')); ?>">
      </div>
      <div class="form-group" style="margin-bottom:12px">
        <label class="form-label" for="project_desc"><?php echo sp_t('label_project_desc'); ?> *</label>
        <textarea id="project_desc" name="project_desc" required class="form-input"
                  style="min-height:90px;height:auto;resize:vertical"
                  placeholder="<?php echo esc_attr(sp_t('ph_project_desc')); ?>"></textarea>
      </div>
      <div class="form-group" style="margin-bottom:12px">
        <label class="form-label" for="team"><?php echo sp_t('label_team'); ?></label>
        <textarea id="team" name="team" class="form-input"
                  style="min-height:60px;height:auto;resize:vertical"
                  placeholder="<?php echo esc_attr(sp_t('ph_team')); ?>"></textarea>
      </div>
      <div class="form-group" style="margin-bottom:12px">
        <label class="form-label"><?php echo sp_t('label_what_need'); ?> *</label>
        <div class="sp-chips" id="needsGrid">
          <?php
          // Extra opzioni non-ruolo, specifiche per "cosa cerchi"
          $extra_needs = array(
              'Models'              => 'need_models',
              'Location'            => 'need_location',
              'Technical Equipment' => 'need_equipment',
              'PR Support'          => 'need_pr',
              'Production'          => 'need_production',
          );
          // Combina extra + tutti i ruoli (riuso array $roles)
          $all_needs = $extra_needs + $roles;
          foreach ($all_needs as $value => $key) : ?>
          <label class="sp-chip">
            <input type="checkbox" name="needs[]" value="<?php echo esc_attr($value); ?>">
            <span><?php echo sp_t($key); ?></span>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label" for="what_need"><?php echo sp_t('label_what_need_notes'); ?></label>
        <textarea id="what_need" name="what_need" class="form-input"
                  style="min-height:60px;height:auto;resize:vertical"
                  placeholder="<?php echo esc_attr(sp_t('ph_what_need_notes')); ?>"></textarea>
      </div>

      <!-- ─── SHOOTING ─── -->
      <div class="sp-section-label"><?php echo sp_t('section_shooting'); ?></div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="shoot_location"><?php echo sp_t('label_shoot_location'); ?></label>
          <input type="text" id="shoot_location" name="shoot_location" class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_shoot_location')); ?>">
        </div>
        <div class="form-group">
          <label class="form-label" for="shoot_dates"><?php echo sp_t('label_shoot_dates'); ?></label>
          <input type="text" id="shoot_dates" name="shoot_dates" class="form-input"
                 placeholder="<?php echo esc_attr(sp_t('ph_shoot_dates')); ?>">
        </div>
      </div>
      <div class="form-group" style="margin-bottom:16px">
        <label class="form-label" for="shoot_hours"><?php echo sp_t('label_shoot_hours'); ?></label>
        <input type="text" id="shoot_hours" name="shoot_hours" class="form-input"
               placeholder="<?php echo esc_attr(sp_t('ph_shoot_hours')); ?>">
      </div>

      <!-- ─── BUDGET ─── -->
      <div class="sp-section-label"><?php echo sp_t('label_budget_yn'); ?></div>
      <div style="display:flex;gap:10px;margin-bottom:12px;flex-wrap:wrap">
        <label class="sp-chip" id="budgetYesLabel">
          <input type="radio" name="budget_yn" value="yes" id="budgetYes">
          <span><?php echo sp_t('budget_yes'); ?></span>
        </label>
        <label class="sp-chip" id="budgetNoLabel">
          <input type="radio" name="budget_yn" value="no" id="budgetNo">
          <span><?php echo sp_t('budget_no'); ?></span>
        </label>
      </div>
      <div class="form-group" id="budgetRangeWrap" style="display:none;margin-bottom:16px">
        <label class="form-label" for="budget_range"><?php echo sp_t('label_budget_range'); ?></label>
        <select id="budget_range" name="budget_range" class="form-input">
          <option value=""></option>
          <option value="lt-250">&lt; 250€</option>
          <option value="250-500">250 – 500€</option>
          <option value="500-1000">500 – 1.000€</option>
          <option value="1000-2000">1.000 – 2.000€</option>
          <option value="2000-5000">2.000 – 5.000€</option>
          <option value="gt-5000">&gt; 5.000€</option>
          <option value="da-definire">Da definire / TBD</option>
        </select>
      </div>

      <!-- ─── MOODBOARD ─── -->
      <div class="sp-section-label"><?php echo sp_t('section_moodboard'); ?></div>
      <p style="font-size:0.75rem;opacity:0.5;margin-bottom:10px"><?php echo sp_t('moodboard_label'); ?></p>

      <div id="moodboardArea">
        <input type="file" id="moodboardInput" accept="image/jpeg,image/png,image/webp"
               multiple style="display:none">
        <button type="button" id="moodboardBtn"
                style="width:100%;padding:14px;background:var(--gray-1);border:1px dashed var(--gray-3);color:var(--gray-5);font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;cursor:pointer">
          + <?php echo sp_t('moodboard_btn'); ?>
        </button>
        <div id="moodboardPreviews" style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px"></div>
        <div id="moodboardStatus" style="font-size:0.75rem;opacity:0.5;margin-top:6px;min-height:16px"></div>
      </div>

      <!-- ─── PRIVACY ─── -->
      <div style="margin:20px 0 16px;padding:12px;border:1px solid var(--gray-2)">
        <label style="display:flex;align-items:flex-start;gap:8px;font-size:0.75rem;opacity:0.5;cursor:pointer">
          <input type="checkbox" id="consent" required
                 style="width:16px;height:16px;margin-top:1px;accent-color:var(--white);flex-shrink:0">
          <span><?php echo sp_t('privacy_text'); ?>
            <a href="https://www.iubenda.com/privacy-policy/58462877" target="_blank"
               style="color:var(--white);text-decoration:underline"><?php echo sp_t('privacy_link'); ?></a>
          </span>
        </label>
      </div>

      <!-- ─── SUBMIT ─── -->
      <button type="submit" id="submitBtn" class="btn-hero btn-hero-primary"
              style="width:100%;padding:18px;font-size:1rem;border:none">
        <span id="submitSpinner"
              style="display:none;width:16px;height:16px;border:2px solid var(--black);border-right-color:transparent;border-radius:50%;animation:spin 0.8s linear infinite;vertical-align:middle;margin-right:8px"></span>
        <span id="submitText"><?php echo sp_t('submit_btn'); ?></span>
      </button>

    </form><!-- /studentForm -->

    <?php toa_component('quick-contacts'); ?>
  </div>
</section>

<!-- ══════════════════════════════════════════════
     STYLES
═══════════════════════════════════════════════ -->
<style>
/* Form base */
.form-label{display:block;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;opacity:0.6;margin-bottom:4px}
.form-input{width:100%;padding:12px;border:1px solid var(--gray-3);border-radius:0;font-size:0.95rem;font-family:inherit;background:var(--gray-1);color:var(--white);height:46px;-webkit-appearance:none;appearance:none;transition:border-color 0.2s;box-sizing:border-box}
textarea.form-input{height:auto}
.form-input:focus{outline:none;border-color:var(--gray-5);background:rgba(245,245,243,0.08)}
.form-input::placeholder{color:var(--gray-3)}
select.form-input{background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 10px center;background-size:16px;padding-right:32px}
select.form-input option{background:var(--black);color:var(--white)}

/* Section labels */
.sp-section-label{font-size:0.65rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--gray-4);border-bottom:1px solid var(--gray-2);padding-bottom:6px;margin:24px 0 14px}

/* Role chips */
.sp-chips{display:flex;flex-wrap:wrap;gap:6px}
.sp-chip{display:inline-flex;align-items:center;padding:7px 12px;border:1px solid var(--gray-3);font-size:0.72rem;font-weight:500;cursor:pointer;text-transform:uppercase;letter-spacing:0.3px;color:var(--gray-5);transition:all 0.15s;user-select:none}
.sp-chip input{display:none}
.sp-chip:has(input:checked){background:var(--white-pure,#fff);color:var(--black);border-color:var(--white-pure,#fff)}
.sp-chip:has(input[type=radio]:checked){background:var(--white-pure,#fff);color:var(--black);border-color:var(--white-pure,#fff)}

/* Moodboard previews */
.sp-thumb{position:relative;width:80px;height:80px;border:1px solid var(--gray-3);overflow:hidden;flex-shrink:0}
.sp-thumb img{width:100%;height:100%;object-fit:cover;display:block}
.sp-thumb-remove{position:absolute;top:2px;right:2px;width:18px;height:18px;background:rgba(0,0,0,0.75);color:#fff;font-size:11px;line-height:18px;text-align:center;cursor:pointer;border-radius:50%}

/* Spinner */
@keyframes spin{to{transform:rotate(360deg)}}

/* Mobile responsive */
@media(max-width:580px){
  div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}
  .feature-card{padding:20px 16px}
  .sp-chips{gap:5px}
  .sp-chip{font-size:0.68rem;padding:6px 10px}
}
</style>

<!-- ══════════════════════════════════════════════
     JS
═══════════════════════════════════════════════ -->
<script>
var SP = {
  sending:    '<?php echo $js['sending']; ?>',
  privacy:    '<?php echo $js['alertPrivacy']; ?>',
  role:       '<?php echo $js['alertRole']; ?>',
  error:      '<?php echo $js['errorMsg']; ?>',
  wa:         '<?php echo $js['waMessage']; ?>',
  fileBig:    '<?php echo $js['fileBig']; ?>',
  maxFiles:   '<?php echo $js['maxFiles']; ?>',
  processing: '<?php echo $js['processing']; ?>',
  submitBtn:  '<?php echo $js['submitBtn']; ?>'
};

var CRM_ENDPOINT = 'https://toagency.it/crm_toagency/actions/lead-from-website.php';
var CRM_TOKEN    = 'toa_lead_2026_x7k9m2p4q8w1';
var THANK_YOU    = '<?php
    // URL della pagina di conferma — WPML restituisce automaticamente
    // /student-program/grazie/ in IT e /xx/student-program/grazie/ nelle altre lingue.
    // Pagina dedicata, NON tracciata come goal Google Ads (a differenza di /tnx/).
    $confirm_id = 269830; // ID della pagina IT "Student Program – Grazie"
    if (function_exists('apply_filters')) {
        $confirm_id = apply_filters('wpml_object_id', $confirm_id, 'page', true);
    }
    echo esc_url(get_permalink($confirm_id));
?>';

/* ── "Other" role reveal ── */
document.querySelectorAll('input[name="roles[]"]').forEach(function(cb){
  cb.addEventListener('change', function(){
    var anyOther = document.querySelector('input[name="roles[]"][value="Other"]:checked');
    document.getElementById('otherRoleWrap').style.display = anyOther ? 'block' : 'none';
  });
});

/* ── Budget range reveal ── */
document.querySelectorAll('input[name="budget_yn"]').forEach(function(r){
  r.addEventListener('change', function(){
    document.getElementById('budgetRangeWrap').style.display =
      (this.value === 'yes') ? 'block' : 'none';
  });
});

/* ── Moodboard upload & resize ── */
var moodboardFiles = []; // array of {name, base64}

document.getElementById('moodboardBtn').addEventListener('click', function(){
  document.getElementById('moodboardInput').click();
});

document.getElementById('moodboardInput').addEventListener('change', function(e){
  var files = Array.from(e.target.files);
  if (moodboardFiles.length + files.length > 3) {
    alert(SP.maxFiles); e.target.value = ''; return;
  }
  files.forEach(function(file){
    if (file.size > 10 * 1024 * 1024) { alert(SP.fileBig); return; }
    setStatus(SP.processing);
    resizeImage(file, 1200, 0.78, function(b64, name){
      moodboardFiles.push({ name: name, base64: b64 });
      addThumb(b64, moodboardFiles.length - 1);
      setStatus('');
      if (moodboardFiles.length >= 3) {
        document.getElementById('moodboardBtn').style.display = 'none';
      }
    });
  });
  e.target.value = '';
});

function resizeImage(file, maxPx, quality, cb) {
  var reader = new FileReader();
  reader.onload = function(ev){
    var img = new Image();
    img.onload = function(){
      var w = img.width, h = img.height;
      if (w > maxPx || h > maxPx) {
        var ratio = Math.min(maxPx / w, maxPx / h);
        w = Math.round(w * ratio); h = Math.round(h * ratio);
      }
      var canvas = document.createElement('canvas');
      canvas.width = w; canvas.height = h;
      canvas.getContext('2d').drawImage(img, 0, 0, w, h);
      var b64 = canvas.toDataURL('image/jpeg', quality);
      cb(b64, file.name.replace(/\.[^.]+$/, '') + '_resized.jpg');
    };
    img.src = ev.target.result;
  };
  reader.readAsDataURL(file);
}

function addThumb(b64, idx) {
  var wrap = document.createElement('div');
  wrap.className = 'sp-thumb'; wrap.dataset.idx = idx;
  var img = document.createElement('img'); img.src = b64;
  var rm  = document.createElement('div');
  rm.className = 'sp-thumb-remove'; rm.textContent = '×';
  rm.addEventListener('click', function(){
    moodboardFiles.splice(idx, 1);
    document.getElementById('moodboardPreviews').innerHTML = '';
    moodboardFiles.forEach(function(f, i){ addThumb(f.base64, i); });
    document.getElementById('moodboardBtn').style.display = 'block';
  });
  wrap.appendChild(img); wrap.appendChild(rm);
  document.getElementById('moodboardPreviews').appendChild(wrap);
}

function setStatus(msg){
  document.getElementById('moodboardStatus').textContent = msg;
}

/* ── Auto-save ── */
function autoSave(){
  var d = {};
  document.querySelectorAll('#studentForm input:not([type=checkbox]):not([type=radio]):not([type=file]), #studentForm select, #studentForm textarea')
    .forEach(function(f){ if(f.id) d[f.id] = f.value; });
  d._at = new Date().toISOString();
  try { localStorage.setItem('toa_student_form', JSON.stringify(d)); } catch(e){}
}
document.querySelectorAll('#studentForm input, #studentForm select, #studentForm textarea')
  .forEach(function(f){ f.addEventListener('input', autoSave); f.addEventListener('change', autoSave); });

window.addEventListener('load', function(){
  try {
    var s = JSON.parse(localStorage.getItem('toa_student_form') || '{}');
    if (s._at && (Date.now() - new Date(s._at)) / 3600000 < 24) {
      Object.keys(s).forEach(function(k){
        if (k === '_at') return;
        var el = document.getElementById(k);
        if (el && el.tagName !== 'INPUT' || (el && el.type !== 'checkbox' && el.type !== 'radio')) {
          if (el) el.value = s[k];
        }
      });
    }
  } catch(e){}
});

/* ── CRM send with retry ── */
async function sendToCRM(payload, retries) {
  retries = retries || 3;
  for (var i = 0; i < retries; i++) {
    try {
      var resp = await fetch(CRM_ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Lead-Token': CRM_TOKEN },
        body: JSON.stringify(payload)
      });
      var data = await resp.json();
      if (data.success) return { success: true };
    } catch(e) {
      await new Promise(function(r){ setTimeout(r, 1000 * (i + 1)); });
    }
  }
  return { success: false };
}

function saveFailedLead(payload) {
  try {
    var f = JSON.parse(localStorage.getItem('toa_failed_leads') || '[]');
    f.push(Object.assign({}, payload, { _failedAt: new Date().toISOString() }));
    localStorage.setItem('toa_failed_leads', JSON.stringify(f));
  } catch(e){}
}

/* ── Submit ── */
document.getElementById('studentForm').addEventListener('submit', async function(e){
  e.preventDefault();

  // Validations
  var roles = Array.from(document.querySelectorAll('input[name="roles[]"]:checked')).map(function(i){ return i.value; });
  var needs = Array.from(document.querySelectorAll('input[name="needs[]"]:checked')).map(function(i){ return i.value; });
  if (!roles.length) { alert(SP.role); return; }
  if (!needs.length) { alert(SP.role); return; }
  if (!document.getElementById('consent').checked) { alert(SP.privacy); return; }

  var btn = document.getElementById('submitBtn');
  var txt = document.getElementById('submitText');
  var spn = document.getElementById('submitSpinner');
  btn.disabled = true;
  txt.textContent = SP.sending;
  spn.style.display = 'inline-block';

  var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

  var payload = {
    source:         isMobile ? 'Student Program Mobile' : 'Student Program Desktop',
    // Chi sei
    roles:          roles.join(', '),
    role_other:     (document.getElementById('role_other_text').value || '').trim(),
    // Contatti
    full_name:      document.getElementById('full_name').value.trim(),
    dob:            document.getElementById('dob').value,
    email:          document.getElementById('email').value.trim(),
    phone:          (document.getElementById('phone').value || '').trim(),
    country:        document.getElementById('country').value.trim(),
    // Social
    instagram:      (document.getElementById('instagram').value || '').trim(),
    tiktok:         (document.getElementById('tiktok').value || '').trim(),
    youtube:        (document.getElementById('youtube').value || '').trim(),
    portfolio:      (document.getElementById('portfolio').value || '').trim(),
    // Scuola
    school_name:    document.getElementById('school_name').value.trim(),
    school_city:    document.getElementById('school_city').value.trim(),
    course:         (document.getElementById('course').value || '').trim(),
    // Progetto
    project_title:  document.getElementById('project_title').value.trim(),
    project_desc:   document.getElementById('project_desc').value.trim(),
    team:           (document.getElementById('team').value || '').trim(),
    needs:          needs.join(', '),
    what_need:      (document.getElementById('what_need').value || '').trim(),
    // Shooting
    shoot_location: (document.getElementById('shoot_location').value || '').trim(),
    shoot_dates:    (document.getElementById('shoot_dates').value || '').trim(),
    shoot_hours:    (document.getElementById('shoot_hours').value || '').trim(),
    // Budget
    budget_yn:      (document.querySelector('input[name="budget_yn"]:checked') || {}).value || '',
    budget_range:   (document.getElementById('budget_range').value || ''),
    // Moodboard
    moodboard:      moodboardFiles.map(function(f){ return { name: f.name, data: f.base64 }; }),
    // Meta
    notify_email:   'toagencystaff@gmail.com',
    form_type:      'student_program'
  };

  var result = await sendToCRM(payload);

  if (result.success) {
    try { localStorage.removeItem('toa_student_form'); } catch(e){}
    // Salva dati per pre-compilazione form crew (pagina /registrati-crew/)
    try {
      // Split "Nome Cognome" in first/last (best effort)
      var fn = (payload.full_name || '').trim();
      var firstName = fn, lastName = '';
      var spaceIdx = fn.indexOf(' ');
      if (spaceIdx > 0) {
        firstName = fn.substring(0, spaceIdx).trim();
        lastName  = fn.substring(spaceIdx + 1).trim();
      }
      sessionStorage.setItem('toa_crew_prefill', JSON.stringify({
        first_name: firstName,
        last_name:  lastName,
        email:      payload.email,
        phone:      payload.phone,
        dob:        payload.dob,
        country:    payload.country,
        instagram:  payload.instagram,
        tiktok:     payload.tiktok,
        portfolio:  payload.portfolio,
        roles:      payload.roles,
        _source:    'student_program',
        _at:        new Date().toISOString()
      }));
    } catch(e){}
    window.location.href = THANK_YOU;
  } else {
    saveFailedLead(payload);
    if (confirm(SP.error)) {
      window.open(
        'https://wa.me/393517899225?text=' + encodeURIComponent(SP.wa + payload.full_name + ', Progetto: ' + payload.project_title),
        '_blank'
      );
    }
    btn.disabled = false;
    txt.textContent = SP.submitBtn;
    spn.style.display = 'none';
  }
});

/* ── Beacon backup ── */
window.addEventListener('beforeunload', function(){
  try {
    var s = JSON.parse(localStorage.getItem('toa_student_form') || '{}');
    if (s.email && s.full_name) {
      navigator.sendBeacon(CRM_ENDPOINT, JSON.stringify(
        Object.assign({}, s, { source: 'Student Beacon Recovery', form_type: 'student_program', _token: CRM_TOKEN })
      ));
    }
  } catch(e){}
});
</script>

<?php toa_component('footer'); ?>
