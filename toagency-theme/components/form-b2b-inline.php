<?php
/**
 * Component: Form preventivo inline (homepage) — versione COMPATTA del form di /form-b2b/
 * Usage: toa_component('form-b2b-inline')  (incluso da page-home.php, dove _ht() è definita)
 *
 * Sostituisce la vecchia .cta-section nella home. Stesso endpoint CRM del form completo.
 * Campi: azienda(company), nome(contact), email, telefono(phone), servizio(event_type), messaggio(message).
 * i18n via _ht() (helper locale a page-home.php). IDs prefissati "hq" per non collidere con #leadForm di /form-b2b/.
 */
$hq_heading = array('it'=>'Chiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demande un devis gratuit','es'=>'Solicita un presupuesto gratuito');
$hq_sub     = array('it'=>'Risposta entro 24 ore lavorative','en'=>'Response within 24 working hours','fr'=>'Réponse sous 24 heures ouvrées','es'=>'Respuesta en 24 horas laborables');

$hq_services = array(
  'shooting'               => array('it'=>'Shooting / Servizio foto','en'=>'Photo shoot','fr'=>'Shooting photo','es'=>'Sesión de fotos'),
  'social-content'         => array('it'=>'Contenuti social','en'=>'Social content','fr'=>'Contenu social','es'=>'Contenido social'),
  'showroom-fitting'       => array('it'=>'Showroom / Fitting','en'=>'Showroom / Fitting','fr'=>'Showroom / Fitting','es'=>'Showroom / Fitting'),
  'fiera-salone'           => array('it'=>'Fiera / Salone','en'=>'Trade fair','fr'=>'Salon','es'=>'Feria'),
  'evento'                 => array('it'=>'Evento','en'=>'Event','fr'=>'Événement','es'=>'Evento'),
  'attivita-promozionale'  => array('it'=>'Attività promozionale','en'=>'Promotional activity','fr'=>'Activité promotionnelle','es'=>'Actividad promocional'),
  'pubblicita'             => array('it'=>'Pubblicità','en'=>'Advertising','fr'=>'Publicité','es'=>'Publicidad'),
  'film'                   => array('it'=>'Film / Serie TV','en'=>'Film / TV series','fr'=>'Film / Série TV','es'=>'Película / Serie TV'),
  'sfilata'                => array('it'=>'Sfilata','en'=>'Fashion show','fr'=>'Défilé','es'=>'Desfile'),
  'altro'                  => array('it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro'),
);

// Stringhe JS (multilingua)
$hq_js = array(
  'sending' => array('it'=>'Invio...','en'=>'Sending...','fr'=>'Envoi...','es'=>'Enviando...'),
  'submit'  => array('it'=>'Invia la tua richiesta','en'=>'Send your request','fr'=>'Envoyez votre demande','es'=>'Envía tu solicitud'),
  'microcopy' => array('it'=>'Ti ricontattiamo subito','en'=>'We\'ll get back to you right away','fr'=>'On vous recontacte tout de suite','es'=>'Te contactamos enseguida'),
  'privacy' => array('it'=>'Devi accettare la privacy policy per continuare.','en'=>'You must accept the privacy policy to continue.','fr'=>'Vous devez accepter la politique de confidentialité pour continuer.','es'=>'Debes aceptar la política de privacidad para continuar.'),
  'error'   => array('it'=>'Invio non riuscito. Vuoi scriverci su WhatsApp?','en'=>'Sending failed. Do you want to message us on WhatsApp?','fr'=>'Échec de l\'envoi. Voulez-vous nous écrire sur WhatsApp ?','es'=>'Error en el envío. ¿Quieres escribirnos por WhatsApp?'),
  'wa'      => array('it'=>'Ciao TOAgency, richiesta preventivo da: ','en'=>'Hi TOAgency, quote request from: ','fr'=>'Bonjour TOAgency, demande de devis de : ','es'=>'Hola TOAgency, solicitud de presupuesto de: '),
);
?>
<section class="cta-section inline-quote-section" id="preventivo">
  <div class="container">
    <div class="inline-quote-card">
      <div class="section-eyebrow"><?php echo _ht(array('it'=>'Inizia ora','en'=>'Start now','fr'=>'Commencez maintenant','es'=>'Empieza ahora')); ?></div>
      <h2 class="section-heading"><?php echo _ht($hq_heading); ?></h2>
      <p class="inline-quote-sub"><?php echo _ht($hq_sub); ?></p>

      <form id="hqForm" method="post" novalidate>
        <div class="iq-grid">
          <div class="iq-field">
            <label class="iq-label" for="hq_company"><?php echo _ht(array('it'=>'Azienda','en'=>'Company','fr'=>'Entreprise','es'=>'Empresa')); ?></label>
            <input type="text" id="hq_company" name="company" required class="iq-input">
          </div>
          <div class="iq-field">
            <label class="iq-label" for="hq_contact"><?php echo _ht(array('it'=>'Nome e cognome','en'=>'Full name','fr'=>'Nom et prénom','es'=>'Nombre y apellidos')); ?></label>
            <input type="text" id="hq_contact" name="contact" required class="iq-input">
          </div>
        </div>
        <div class="iq-grid">
          <div class="iq-field">
            <label class="iq-label" for="hq_email"><?php echo _ht(array('it'=>'Email','en'=>'Email','fr'=>'Email','es'=>'Email')); ?></label>
            <input type="email" id="hq_email" name="email" required placeholder="email@azienda.it" class="iq-input">
          </div>
          <div class="iq-field">
            <label class="iq-label" for="hq_phone"><?php echo _ht(array('it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono')); ?></label>
            <input type="tel" id="hq_phone" name="phone" class="iq-input">
          </div>
        </div>
        <div class="iq-field">
          <label class="iq-label" for="hq_event_type"><?php echo _ht(array('it'=>'Servizio','en'=>'Service','fr'=>'Service','es'=>'Servicio')); ?></label>
          <select id="hq_event_type" name="event_type" required class="iq-input">
            <option value=""><?php echo _ht(array('it'=>'Seleziona...','en'=>'Select...','fr'=>'Sélectionnez...','es'=>'Selecciona...')); ?></option>
            <?php foreach ($hq_services as $val => $label) : ?>
            <option value="<?php echo esc_attr($val); ?>"><?php echo _ht($label); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="iq-field">
          <label class="iq-label" for="hq_message"><?php echo _ht(array('it'=>'Messaggio','en'=>'Message','fr'=>'Message','es'=>'Mensaje')); ?></label>
          <textarea id="hq_message" name="message" class="iq-input" placeholder="<?php echo esc_attr(_ht(array('it'=>'Raccontaci brevemente il tuo progetto...','en'=>'Tell us briefly about your project...','fr'=>'Décrivez brièvement votre projet...','es'=>'Cuéntanos brevemente tu proyecto...'))); ?>"></textarea>
        </div>

        <label class="inline-quote-consent" for="hq_consent">
          <input type="checkbox" id="hq_consent" required>
          <span><?php echo _ht(array('it'=>'Accetto il trattamento dei dati secondo la','en'=>'I accept the processing of my data according to the','fr'=>'J\'accepte le traitement de mes données selon la','es'=>'Acepto el tratamiento de mis datos según la')); ?> <a href="https://www.iubenda.com/privacy-policy/58462877" target="_blank" rel="noopener"><?php echo _ht(array('it'=>'Privacy Policy','en'=>'Privacy Policy','fr'=>'Politique de confidentialité','es'=>'Política de privacidad')); ?></a></span>
        </label>

        <button type="submit" class="btn-hero btn-hero-primary iq-submit" id="hqSubmit">
          <span class="iq-spinner" id="hqSpinner"></span>
          <span id="hqSubmitText"><?php echo _ht($hq_js['submit']); ?></span>
        </button>
        <p style="text-align:center;font-size:12px;color:rgba(255,255,255,.5);margin:10px 0 0"><?php echo _ht($hq_js['microcopy']); ?></p>
      </form>
    </div>
  </div>
</section>
<script>
(function(){
  var form = document.getElementById('hqForm');
  if (!form) return;
  var ENDPOINT = 'https://toagency.it/crm_toagency/actions/lead-from-website.php';
  var TOKEN    = 'toa_lead_2026_x7k9m2p4q8w1';
  var THANKYOU = '<?php echo esc_url(home_url('/tnx/')); ?>';
  var STR = {
    sending: '<?php echo esc_js(_ht($hq_js['sending'])); ?>',
    submit:  '<?php echo esc_js(_ht($hq_js['submit'])); ?>',
    privacy: '<?php echo esc_js(_ht($hq_js['privacy'])); ?>',
    error:   '<?php echo esc_js(_ht($hq_js['error'])); ?>',
    wa:      '<?php echo esc_js(_ht($hq_js['wa'])); ?>'
  };
  function v(id){ var el = document.getElementById(id); return el ? el.value.trim() : ''; }

  // FIX 2026-07-11 — cattura gclid/UTM (attribuzione fonte lead), stessa logica di /form-b2b/.
  // La landing Ads (/lp/hostess-eventi/) usa QUESTO componente: senza cattura il gclid non
  // arrivava al CRM -> fonte "Organico/Diretto". Legge URL + cookie first-touch (90gg).
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
      Object.keys(now).forEach(function(k){ ft[k] = now[k]; }); // last-touch override click-id/UTM
      return ft;
    } catch(e) { return {}; }
  };
  var toaAttrCache = (typeof window.toaAttr === 'function') ? window.toaAttr() : {};

  async function sendToCRM(payload, retries){
    retries = retries || 3;
    for (var i = 0; i < retries; i++) {
      try {
        var resp = await fetch(ENDPOINT, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-Lead-Token': TOKEN },
          body: JSON.stringify(payload)
        });
        var data = await resp.json();
        if (data.success) return { success: true };
      } catch (e) {
        await new Promise(function(r){ setTimeout(r, 1000 * (i + 1)); });
      }
    }
    return { success: false };
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    if (!document.getElementById('hq_consent').checked) { alert(STR.privacy); return; }

    var btn = document.getElementById('hqSubmit');
    var txt = document.getElementById('hqSubmitText');
    var spinner = document.getElementById('hqSpinner');
    btn.disabled = true;
    spinner.style.display = 'inline-block';
    txt.textContent = STR.sending;

    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    var payload = {
      source: isMobile ? 'Form Home Inline Mobile' : 'Form Home Inline Desktop',
      company: v('hq_company'),
      contact: v('hq_contact'),
      email: v('hq_email'),
      phone: v('hq_phone'),
      event_type: v('hq_event_type'),
      message: v('hq_message')
    };
    Object.assign(payload, toaAttrCache); // FIX 2026-07-11 — gclid/UTM al CRM (fonte Ads)

    var result = await sendToCRM(payload, 3);
    if (result.success) {
      window.location.href = THANKYOU;
    } else {
      if (confirm(STR.error)) {
        window.open('https://wa.me/393517899225?text=' + encodeURIComponent(STR.wa + payload.company + ' — ' + payload.event_type), '_blank');
      }
      btn.disabled = false;
      spinner.style.display = 'none';
      txt.textContent = STR.submit;
    }
  });
})();
</script>
