<?php
/**
 * Component: Form preventivo inline — Pagina Servizi Eventi (hub /hostess-steward/)
 * Usage: toa_component('form-eventi-inline', array('lang'=>$lang))
 *
 * Clone dedicato di form-b2b-inline.php (NON tocca il form condiviso della home).
 * Differenze: source = "Pagina Servizi Eventi", IDs prefissati "fe" (no collisioni),
 * redirect /tnx/?ls=... (lead_source per report GA4 futuri, come da parere chat ADS).
 * Stesso endpoint CRM + stessa cattura gclid/UTM del form B2B.
 */
$fe_heading = array('it'=>'Richiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demande un devis gratuit','es'=>'Solicita un presupuesto gratuito');
$fe_sub     = array('it'=>'Raccontaci il tuo evento — ti ricontattiamo subito, prezzi trasparenti.','en'=>'Tell us about your event — we get back to you right away, transparent prices.','fr'=>'Parlez-nous de votre événement — on vous recontacte tout de suite, prix transparents.','es'=>'Cuéntanos tu evento — te contactamos enseguida, precios transparentes.');

$fe_services = array(
  'fiera-salone'          => array('it'=>'Fiera / Salone','en'=>'Trade fair','fr'=>'Salon','es'=>'Feria'),
  'evento'                => array('it'=>'Evento aziendale','en'=>'Corporate event','fr'=>'Événement d\'entreprise','es'=>'Evento de empresa'),
  'congresso'             => array('it'=>'Congresso','en'=>'Conference','fr'=>'Congrès','es'=>'Congreso'),
  'attivita-promozionale' => array('it'=>'Attività promozionale','en'=>'Promotional activity','fr'=>'Activité promotionnelle','es'=>'Actividad promocional'),
  'catering-gala'         => array('it'=>'Catering / Cena di gala','en'=>'Catering / Gala dinner','fr'=>'Traiteur / Dîner de gala','es'=>'Catering / Cena de gala'),
  'altro'                 => array('it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro'),
);

$fe_js = array(
  'sending'  => array('it'=>'Invio...','en'=>'Sending...','fr'=>'Envoi...','es'=>'Enviando...'),
  'submit'   => array('it'=>'Invia la tua richiesta','en'=>'Send your request','fr'=>'Envoyez votre demande','es'=>'Envía tu solicitud'),
  'microcopy'=> array('it'=>'Ti ricontattiamo subito','en'=>'We\'ll get back to you right away','fr'=>'On vous recontacte tout de suite','es'=>'Te contactamos enseguida'),
  'privacy'  => array('it'=>'Devi accettare la privacy policy per continuare.','en'=>'You must accept the privacy policy to continue.','fr'=>'Vous devez accepter la politique de confidentialité pour continuer.','es'=>'Debes aceptar la política de privacidad para continuar.'),
  'error'    => array('it'=>'Invio non riuscito. Vuoi scriverci su WhatsApp?','en'=>'Sending failed. Do you want to message us on WhatsApp?','fr'=>'Échec de l\'envoi. Voulez-vous nous écrire sur WhatsApp ?','es'=>'Error en el envío. ¿Quieres escribirnos por WhatsApp?'),
  'wa'       => array('it'=>'Ciao TOAgency, richiesta preventivo da: ','en'=>'Hi TOAgency, quote request from: ','fr'=>'Bonjour TOAgency, demande de devis de : ','es'=>'Hola TOAgency, solicitud de presupuesto de: '),
);
?>
<section class="cta-section inline-quote-section" id="preventivo">
  <div class="container">
    <div class="inline-quote-card">
      <div class="section-eyebrow"><?php echo _ht(array('it'=>'Inizia ora','en'=>'Start now','fr'=>'Commencez maintenant','es'=>'Empieza ahora')); ?></div>
      <h2 class="section-heading"><?php echo _ht($fe_heading); ?></h2>
      <p class="inline-quote-sub"><?php echo _ht($fe_sub); ?></p>

      <form id="feForm" method="post" novalidate>
        <div class="iq-grid">
          <div class="iq-field">
            <label class="iq-label" for="fe_company"><?php echo _ht(array('it'=>'Azienda','en'=>'Company','fr'=>'Entreprise','es'=>'Empresa')); ?></label>
            <input type="text" id="fe_company" name="company" required class="iq-input">
          </div>
          <div class="iq-field">
            <label class="iq-label" for="fe_contact"><?php echo _ht(array('it'=>'Nome e cognome','en'=>'Full name','fr'=>'Nom et prénom','es'=>'Nombre y apellidos')); ?></label>
            <input type="text" id="fe_contact" name="contact" required class="iq-input">
          </div>
        </div>
        <div class="iq-grid">
          <div class="iq-field">
            <label class="iq-label" for="fe_email"><?php echo _ht(array('it'=>'Email','en'=>'Email','fr'=>'Email','es'=>'Email')); ?></label>
            <input type="email" id="fe_email" name="email" required placeholder="email@azienda.it" class="iq-input">
          </div>
          <div class="iq-field">
            <label class="iq-label" for="fe_phone"><?php echo _ht(array('it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono')); ?></label>
            <input type="tel" id="fe_phone" name="phone" class="iq-input">
          </div>
        </div>
        <div class="iq-field">
          <label class="iq-label" for="fe_event_type"><?php echo _ht(array('it'=>'Tipo di evento','en'=>'Event type','fr'=>'Type d\'événement','es'=>'Tipo de evento')); ?></label>
          <select id="fe_event_type" name="event_type" required class="iq-input">
            <option value=""><?php echo _ht(array('it'=>'Seleziona...','en'=>'Select...','fr'=>'Sélectionnez...','es'=>'Selecciona...')); ?></option>
            <?php foreach ($fe_services as $val => $label) : ?>
            <option value="<?php echo esc_attr($val); ?>"><?php echo _ht($label); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="iq-field">
          <label class="iq-label" for="fe_message"><?php echo _ht(array('it'=>'Messaggio','en'=>'Message','fr'=>'Message','es'=>'Mensaje')); ?></label>
          <textarea id="fe_message" name="message" class="iq-input" placeholder="<?php echo esc_attr(_ht(array('it'=>'Che figure ti servono e per quale evento? (es. 4 hostess + 2 bartender per fiera a Milano)','en'=>'Which staff do you need and for which event?','fr'=>'De quel personnel avez-vous besoin et pour quel événement ?','es'=>'¿Qué personal necesitas y para qué evento?'))); ?>"></textarea>
        </div>

        <label class="inline-quote-consent" for="fe_consent">
          <input type="checkbox" id="fe_consent" required>
          <span><?php echo _ht(array('it'=>'Accetto il trattamento dei dati secondo la','en'=>'I accept the processing of my data according to the','fr'=>'J\'accepte le traitement de mes données selon la','es'=>'Acepto el tratamiento de mis datos según la')); ?> <a href="https://www.iubenda.com/privacy-policy/58462877" target="_blank" rel="noopener"><?php echo _ht(array('it'=>'Privacy Policy','en'=>'Privacy Policy','fr'=>'Politique de confidentialité','es'=>'Política de privacidad')); ?></a></span>
        </label>

        <button type="submit" class="btn-hero btn-hero-primary iq-submit" id="feSubmit">
          <span class="iq-spinner" id="feSpinner"></span>
          <span id="feSubmitText"><?php echo _ht($fe_js['submit']); ?></span>
        </button>
        <p style="text-align:center;font-size:12px;color:rgba(255,255,255,.5);margin:10px 0 0"><?php echo _ht($fe_js['microcopy']); ?></p>
      </form>
    </div>
  </div>
</section>
<script>
(function(){
  var form = document.getElementById('feForm');
  if (!form) return;
  var ENDPOINT = 'https://toagency.it/crm_toagency/actions/lead-from-website.php';
  var TOKEN    = 'toa_lead_2026_x7k9m2p4q8w1';
  var LEADSRC  = 'Pagina Servizi Eventi';
  var THANKYOU = '<?php echo esc_url(home_url('/tnx/')); ?>' + '?ls=' + encodeURIComponent(LEADSRC);
  var STR = {
    sending: '<?php echo esc_js(_ht($fe_js['sending'])); ?>',
    submit:  '<?php echo esc_js(_ht($fe_js['submit'])); ?>',
    privacy: '<?php echo esc_js(_ht($fe_js['privacy'])); ?>',
    error:   '<?php echo esc_js(_ht($fe_js['error'])); ?>',
    wa:      '<?php echo esc_js(_ht($fe_js['wa'])); ?>'
  };
  function v(id){ var el = document.getElementById(id); return el ? el.value.trim() : ''; }

  // Cattura gclid/UTM (stessa logica del form B2B): riusa window.toaAttr se già definita.
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
      Object.keys(now).forEach(function(k){ ft[k] = now[k]; });
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
    if (!document.getElementById('fe_consent').checked) { alert(STR.privacy); return; }

    var btn = document.getElementById('feSubmit');
    var txt = document.getElementById('feSubmitText');
    var spinner = document.getElementById('feSpinner');
    btn.disabled = true;
    spinner.style.display = 'inline-block';
    txt.textContent = STR.sending;

    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    var payload = {
      source: LEADSRC + (isMobile ? ' (Mobile)' : ' (Desktop)'),
      company: v('fe_company'),
      contact: v('fe_contact'),
      email: v('fe_email'),
      phone: v('fe_phone'),
      event_type: v('fe_event_type'),
      message: v('fe_message')
    };
    Object.assign(payload, toaAttrCache);

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
