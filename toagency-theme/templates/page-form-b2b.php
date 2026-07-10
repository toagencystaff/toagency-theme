<?php
/**
 * Template Name: Form B2B
 * Landing page for B2B lead generation — connected to CRM
 * — Versione multilingua (IT/EN/FR/ES) con sistema toa_t()
 */
require_once get_theme_file_path('templates/translations.php');
toa_component('header');

// Stringhe JS iniettate via PHP per supporto multilingua
$b2b_js = array(
    'detailsOpen'  => esc_js(toa_t('form_b2b', 'details_toggle_open')),
    'detailsClose' => esc_js(toa_t('form_b2b', 'details_toggle_close')),
    'alertPrivacy' => esc_js(toa_t('form_b2b', 'js_alert_privacy')),
    'sending'      => esc_js(toa_t('form_b2b', 'js_sending')),
    'submitBtn'    => esc_js(toa_t('form_b2b', 'submit_btn')),
    'errorMsg'     => esc_js(toa_t('form_b2b', 'js_error_msg')),
    'waMessage'    => esc_js(toa_t('form_b2b', 'js_wa_message')),
);
?>

<!-- HERO -->
<section class="page-hero" style="text-align:center;padding-bottom:30px">
  <div class="container">
    <h1 class="page-hero-title" style="max-width:800px;margin:0 auto 12px"><?php echo toa_t('form_b2b', 'hero_title'); ?></h1>
    <p class="page-hero-subtitle" style="max-width:600px;margin:0 auto"><?php echo toa_t('form_b2b', 'hero_subtitle'); ?></p>
    <div class="trust-numbers" style="display:flex;justify-content:center;gap:40px;margin-top:24px;flex-wrap:wrap">
      <div style="text-align:center"><div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900">20.000+</div><div style="font-size:0.65rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray-4)"><?php echo toa_t('home', 'stat_professionisti'); ?></div></div>
      <div style="text-align:center"><div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900">10.000+</div><div style="font-size:0.65rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray-4)"><?php echo toa_t('home', 'stat_progetti'); ?></div></div>
      <div style="text-align:center"><div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900">50+</div><div style="font-size:0.65rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray-4)"><?php echo toa_t('home', 'stat_citta'); ?></div></div>
      <div style="text-align:center"><div style="font-family:var(--font-display);font-size:1.8rem;font-weight:900">15+</div><div style="font-size:0.65rem;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:var(--gray-4)"><?php echo toa_t('home', 'stat_anni'); ?></div></div>
    </div>
  </div>
</section>

<?php toa_component('brand-ticker'); ?>

<!-- FORM -->
<section style="max-width:680px;margin:0 auto;padding:30px 16px 100px">
  <div class="feature-card" style="padding:30px 20px">
    <h2 style="font-size:1.3rem;font-weight:700;text-transform:uppercase;text-align:center;margin-bottom:6px"><?php echo toa_t('form_b2b', 'form_heading'); ?></h2>
    <p style="font-size:0.85rem;opacity:0.5;text-align:center;margin-bottom:24px"><?php echo toa_t('form_b2b', 'form_subheading'); ?></p>

    <form id="leadForm" method="post">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="company"><?php echo toa_t('form_b2b', 'label_company'); ?></label>
          <input type="text" id="company" name="company" required placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_company')); ?>" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label" for="contact"><?php echo toa_t('form_b2b', 'label_contact'); ?></label>
          <input type="text" id="contact" name="contact" required placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_contact')); ?>" class="form-input">
        </div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="email"><?php echo toa_t('form_b2b', 'label_email'); ?></label>
          <input type="email" id="email" name="email" required placeholder="email@azienda.it" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label" for="phone"><?php echo toa_t('form_b2b', 'label_phone'); ?></label>
          <input type="tel" id="phone" name="phone" placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_phone')); ?>" class="form-input">
        </div>
      </div>
      <div class="form-group" style="margin-bottom:12px">
        <label class="form-label" for="event_type"><?php echo toa_t('form_b2b', 'label_event_type'); ?></label>
        <select id="event_type" name="event_type" required class="form-input">
          <option value=""><?php echo toa_t('form_b2b', 'opt_select'); ?></option>
          <option value="shooting"><?php echo toa_t('form_b2b', 'opt_shooting'); ?></option>
          <option value="social-content"><?php echo toa_t('form_b2b', 'opt_social'); ?></option>
          <option value="showroom-fitting"><?php echo toa_t('form_b2b', 'opt_showroom'); ?></option>
          <option value="fiera-salone"><?php echo toa_t('form_b2b', 'opt_fiera'); ?></option>
          <option value="evento"><?php echo toa_t('form_b2b', 'opt_evento'); ?></option>
          <option value="attivita-promozionale"><?php echo toa_t('form_b2b', 'opt_promo'); ?></option>
          <option value="pubblicita"><?php echo toa_t('form_b2b', 'opt_pub'); ?></option>
          <option value="film"><?php echo toa_t('form_b2b', 'opt_film'); ?></option>
          <option value="sfilata"><?php echo toa_t('form_b2b', 'opt_sfilata'); ?></option>
          <option value="altro"><?php echo toa_t('form_b2b', 'opt_altro'); ?></option>
        </select>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
        <div class="form-group">
          <label class="form-label" for="period"><?php echo toa_t('form_b2b', 'label_period'); ?></label>
          <input type="text" id="period" name="period" placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_period')); ?>" class="form-input">
        </div>
        <div class="form-group">
          <label class="form-label" for="location"><?php echo toa_t('form_b2b', 'label_location'); ?></label>
          <input type="text" id="location" name="location" placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_location')); ?>" class="form-input">
        </div>
      </div>
      <div class="form-group" style="margin-bottom:12px">
        <label class="form-label" for="message"><?php echo toa_t('form_b2b', 'label_message'); ?></label>
        <textarea id="message" name="message" placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_message')); ?>" class="form-input" style="min-height:70px;height:auto;resize:vertical"></textarea>
      </div>

      <!-- Dettagli espandibili -->
      <button type="button" id="detailsToggle" style="width:100%;padding:12px;background:var(--gray-1);border:1px solid var(--gray-2);color:var(--white);font-size:0.8rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;cursor:pointer;text-align:center;margin:8px 0"><?php echo toa_t('form_b2b', 'details_toggle_open'); ?></button>

      <div id="detailsBody" style="display:none;padding:16px 0">
        <label class="form-label" style="margin-bottom:8px;display:block"><?php echo toa_t('form_b2b', 'details_profiles_label'); ?></label>
        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px">
          <?php
          $roles = array(
            'Modelli'     => toa_t('form_b2b', 'role_models'),
            'Attori'      => toa_t('form_b2b', 'role_actors'),
            'Hostess'     => toa_t('form_b2b', 'role_hostess'),
            'Steward'     => toa_t('form_b2b', 'role_steward'),
            'Creator'     => toa_t('form_b2b', 'role_creator'),
            'Fotografi'   => toa_t('form_b2b', 'role_photo'),
            'Truccatori'  => toa_t('form_b2b', 'role_makeup'),
            'Comparse'    => toa_t('form_b2b', 'role_extras'),
            'Parrucchieri'=> toa_t('form_b2b', 'role_hair'),
            'Stylist'     => toa_t('form_b2b', 'role_stylist'),
          );
          foreach ($roles as $value => $label) :
            $id = 'r_' . sanitize_title($value);
          ?>
          <label style="display:inline-block;padding:7px 12px;border:1px solid var(--gray-3);font-size:0.75rem;font-weight:500;cursor:pointer;text-transform:uppercase;letter-spacing:0.3px;color:var(--gray-5);transition:all 0.2s">
            <input type="checkbox" name="roles" value="<?php echo esc_attr($value); ?>" style="display:none" onchange="this.parentElement.style.background=this.checked?'var(--white-pure)':'';this.parentElement.style.color=this.checked?'var(--black)':''"> <?php echo esc_html($label); ?>
          </label>
          <?php endforeach; ?>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px">
          <div class="form-group">
            <label class="form-label" for="figures_number"><?php echo toa_t('form_b2b', 'label_figures'); ?></label>
            <input type="text" id="figures_number" name="figures_number" placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_figures')); ?>" class="form-input">
          </div>
          <div class="form-group">
            <label class="form-label" for="budget"><?php echo toa_t('form_b2b', 'label_budget'); ?></label>
            <select id="budget" name="budget" class="form-input">
              <option value=""><?php echo toa_t('form_b2b', 'opt_altro'); ?></option>
              <option value="lt-250">&lt; 250€</option>
              <option value="250-350">250 – 350€</option>
              <option value="350-500">350 – 500€</option>
              <option value="500-800">500 – 800€</option>
              <option value="800-1200">800 – 1.200€</option>
              <option value="1200-2000">1.200 – 2.000€</option>
              <option value="gt-2000">&gt; 2.000€</option>
            </select>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <div class="form-group">
            <label class="form-label" for="duration"><?php echo toa_t('form_b2b', 'label_duration'); ?></label>
            <input type="text" id="duration" name="duration" placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_duration')); ?>" class="form-input">
          </div>
          <div class="form-group">
            <label class="form-label" for="details"><?php echo toa_t('form_b2b', 'label_details'); ?></label>
            <input type="text" id="details" name="details" placeholder="<?php echo esc_attr(toa_t('form_b2b', 'ph_details')); ?>" class="form-input">
          </div>
        </div>
      </div>

      <!-- Privacy -->
      <div style="margin:16px 0;padding:12px;border:1px solid var(--gray-2)">
        <label style="display:flex;align-items:flex-start;gap:8px;font-size:0.75rem;opacity:0.5;cursor:pointer">
          <input type="checkbox" id="consent" required style="width:16px;height:16px;margin-top:1px;accent-color:var(--white)">
          <span><?php echo toa_t('form_b2b', 'privacy_text'); ?> <a href="https://www.iubenda.com/privacy-policy/58462877" target="_blank" style="color:var(--white);text-decoration:underline"><?php echo toa_t('form_b2b', 'privacy_link'); ?></a></span>
        </label>
      </div>

      <!-- Submit -->
      <button type="submit" class="btn-hero btn-hero-primary" id="submitBtn" style="width:100%;padding:18px;font-size:1rem;border:none">
        <span id="submitSpinner" style="display:none;width:16px;height:16px;border:2px solid var(--black);border-right-color:transparent;border-radius:50%;animation:spin 0.8s linear infinite;vertical-align:middle;margin-right:8px;display:inline-block"></span>
        <span id="submitText"><?php echo toa_t('form_b2b', 'submit_btn'); ?></span>
      </button>
    </form>

    <?php toa_component('quick-contacts'); ?>
  </div>

  <!-- Banner B2C -->
  <div style="text-align:center;padding:30px 20px;border:2px solid var(--gray-2);margin-top:30px;background:var(--gray-1)">
    <p style="font-size:1rem;font-weight:600;margin-bottom:12px"><?php echo toa_t('form_b2b', 'b2c_text'); ?></p>
    <a href="<?php echo home_url('/collabora/'); ?>" class="btn-hero btn-hero-secondary" style="padding:12px 30px;font-size:0.85rem"><?php echo toa_t('form_b2b', 'b2c_btn'); ?></a>
  </div>
</section>

<style>
.form-label{display:block;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;opacity:0.6;margin-bottom:4px}
.form-input{width:100%;padding:12px;border:1px solid var(--gray-3);border-radius:0;font-size:0.95rem;font-family:inherit;background:var(--gray-1);color:var(--white);height:46px;-webkit-appearance:none;appearance:none;transition:border-color 0.2s}
.form-input:focus{outline:none;border-color:var(--gray-5);background:rgba(245,245,243,0.08)}
.form-input::placeholder{color:var(--gray-3)}
select.form-input{background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 10px center;background-size:16px;padding-right:32px}
select.form-input option{background:var(--black);color:var(--white)}
@keyframes spin{to{transform:rotate(360deg)}}
@media(max-width:640px){div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}}
</style>

<!-- Stringhe JS multilingua -->
<script>
var b2bStrings = {
    detailsOpen:  '<?php echo $b2b_js['detailsOpen']; ?>',
    detailsClose: '<?php echo $b2b_js['detailsClose']; ?>',
    alertPrivacy: '<?php echo $b2b_js['alertPrivacy']; ?>',
    sending:      '<?php echo $b2b_js['sending']; ?>',
    submitBtn:    '<?php echo $b2b_js['submitBtn']; ?>',
    errorMsg:     '<?php echo $b2b_js['errorMsg']; ?>',
    waMessage:    '<?php echo $b2b_js['waMessage']; ?>'
};
</script>

<script>
// Details toggle
document.getElementById('detailsToggle').addEventListener('click', function() {
  var body = document.getElementById('detailsBody');
  var open = body.style.display !== 'none';
  body.style.display = open ? 'none' : 'block';
  this.textContent = open ? b2bStrings.detailsOpen : b2bStrings.detailsClose;
});

// CRM Submit
var CRM_ENDPOINT = 'https://toagency.it/crm_toagency/actions/lead-from-website.php';
var CRM_TOKEN = 'toa_lead_2026_x7k9m2p4q8w1';
var THANK_YOU_URL = '<?php echo esc_url(home_url("/tnx/")); ?>';

// FIX 2026-07-07 marco — attribuzione fonte lead: cattura gclid/UTM da URL + cookie first-touch (90gg).
// Click-id/UTM = last-touch (la visita attuale vince, così il gclid caricabile su Google è quello del click che ha convertito);
// landing_page/referrer/first_touch_at = first-touch (salvati una volta sola).
window.toaAttr = window.toaAttr || function() {
  try {
    var qs = new URLSearchParams(location.search);
    var keys = ['gclid','gbraid','wbraid','utm_source','utm_medium','utm_campaign','utm_term','utm_content'];
    var now = {};
    keys.forEach(function(k){ var val = qs.get(k); if (val) now[k] = val.slice(0,255); });
    var ck = (document.cookie.split('; ').filter(function(c){ return c.indexOf('toa_ft=') === 0; })[0] || '');
    var ft = null;
    if (ck) { try { ft = JSON.parse(decodeURIComponent(ck.split('=')[1])); } catch(e) {} }
    if (!ft) {
      ft = Object.assign({}, now);
      ft.landing_page = location.href.slice(0,500);
      ft.referrer = (document.referrer || '').slice(0,500);
      ft.first_touch_at = new Date().toISOString().slice(0,19).replace('T',' ');
      var d = new Date(); d.setDate(d.getDate() + 90);
      document.cookie = 'toa_ft=' + encodeURIComponent(JSON.stringify(ft)) + '; expires=' + d.toUTCString() + '; path=/; SameSite=Lax';
    }
    Object.keys(now).forEach(function(k){ ft[k] = now[k]; }); // last-touch override sui click-id/UTM
    return ft;
  } catch(e) { return {}; }
};

// FIX 2026-07-10 v2 marco/claude — cachea l'attribuzione UNA volta al caricamento pagina invece di
// ricalcolarla (parsing cookie incluso) ad ogni tasto premuto nel form. gclid/UTM sono fissi
// all'atterraggio quindi il valore non cambia durante la compilazione. Riusata in autoSave + submit.
var toaAttrCache = (typeof window.toaAttr === 'function') ? window.toaAttr() : {};

// Auto-save
// FIX 2026-07-10 marco/claude — include gclid/UTM anche nel salvataggio automatico (autoSave),
// cosi' se il lead viene recuperato via Beacon (utente abbandona pagina prima dell'invio, vedi sotto)
// porta comunque con se' l'attribuzione, altrimenti sarebbe un lead orfano non tracciabile in Ads.
function autoSave() {
  var data = {};
  document.querySelectorAll('#leadForm input:not([type=checkbox]), #leadForm select, #leadForm textarea').forEach(function(f) {
    if (f.id) data[f.id] = f.value;
  });
  Object.assign(data, toaAttrCache);
  data._savedAt = new Date().toISOString();
  try { localStorage.setItem('toa_form_b2b', JSON.stringify(data)); } catch(e) {}
}
document.querySelectorAll('#leadForm input, #leadForm select, #leadForm textarea').forEach(function(f) {
  f.addEventListener('input', autoSave);
  f.addEventListener('change', autoSave);
});

// Restore
window.addEventListener('load', function() {
  try {
    var saved = JSON.parse(localStorage.getItem('toa_form_b2b') || '{}');
    if (saved._savedAt) {
      var hours = (Date.now() - new Date(saved._savedAt)) / 3600000;
      if (hours < 24) {
        Object.keys(saved).forEach(function(k) {
          if (k !== '_savedAt') { var el = document.getElementById(k); if (el) el.value = saved[k]; }
        });
      }
    }
  } catch(e) {}
});

// Send with retry
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
      if (data.success) return { success: true, data: data };
    } catch(e) {
      await new Promise(function(r) { setTimeout(r, 1000 * (i + 1)); });
    }
  }
  return { success: false };
}

function saveFailedLead(payload) {
  try {
    var failed = JSON.parse(localStorage.getItem('toa_failed_leads') || '[]');
    failed.push(Object.assign({}, payload, { _failedAt: new Date().toISOString() }));
    localStorage.setItem('toa_failed_leads', JSON.stringify(failed));
  } catch(e) {}
}

// Submit
document.getElementById('leadForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  if (!document.getElementById('consent').checked) { alert(b2bStrings.alertPrivacy); return; }

  var btn = document.getElementById('submitBtn');
  var btnText = document.getElementById('submitText');
  btn.disabled = true;
  btnText.textContent = b2bStrings.sending;

  var roles = [];
  document.querySelectorAll('input[name="roles"]:checked').forEach(function(i) { roles.push(i.value); });
  var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

  var payload = {
    source: isMobile ? 'Form B2B Mobile' : 'Form B2B Desktop',
    company: document.getElementById('company').value.trim(),
    contact: document.getElementById('contact').value.trim(),
    email: document.getElementById('email').value.trim(),
    phone: document.getElementById('phone').value.trim(),
    event_type: document.getElementById('event_type').value,
    period: (document.getElementById('period').value || '').trim(),
    location: (document.getElementById('location').value || '').trim(),
    message: (document.getElementById('message').value || '').trim(),
    roles: roles.join(', '),
    budget: (document.getElementById('budget') || {}).value || '',
    figures_number: (document.getElementById('figures_number') || {}).value || '',
    duration: (document.getElementById('duration') || {}).value || '',
    details: (document.getElementById('details') || {}).value || ''
  };
  Object.assign(payload, toaAttrCache); // FIX 2026-07-07 marco — fonte lead (gclid/UTM/first-touch), cache v2 10/07

  try { sessionStorage.setItem('toa_lead', JSON.stringify({ company: payload.company, contact: payload.contact })); } catch(e) {}

  var result = await sendToCRM(payload);
  if (result.success) {
    try { localStorage.removeItem('toa_form_b2b'); } catch(e) {}
    window.location.href = THANK_YOU_URL;
  } else {
    saveFailedLead(payload);
    if (confirm(b2bStrings.errorMsg)) {
      window.open('https://wa.me/393517899225?text=' + encodeURIComponent(b2bStrings.waMessage + payload.company + ', Progetto: ' + payload.event_type), '_blank');
    }
    btn.disabled = false;
    btnText.textContent = b2bStrings.submitBtn;
  }
});

// Beacon backup
window.addEventListener('beforeunload', function() {
  try {
    var saved = JSON.parse(localStorage.getItem('toa_form_b2b') || '{}');
    if (saved.company && saved.email && saved.phone) {
      navigator.sendBeacon(CRM_ENDPOINT, JSON.stringify(Object.assign({}, saved, { source: 'Beacon Recovery', _token: CRM_TOKEN })));
    }
  } catch(e) {}
});
</script>

<?php toa_component('footer'); ?>
