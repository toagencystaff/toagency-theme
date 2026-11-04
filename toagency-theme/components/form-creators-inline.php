<?php
/**
 * Component: modulo richiesta del mini sito TOA Creators (ticket CRM #807, 04/11/2026, chat «TEMA Ticket 807»)
 * Usage: toa_component('form-creators-inline', array('lang' => $lang))
 *
 * Clone dedicato di form-eventi-inline.php (che resta del mini sito Events): stesso endpoint CRM, stesso token,
 * stessa cattura gclid/UTM (window.toaAttr), stessa domanda azienda/cerco lavoro, thank-you /tnx/?ls=...
 * Differenze: campo «Cosa ti serve» = pacchetti creator; in testa al messaggio al CRM vanno pacchetto, settore,
 * Paese della sede (dal selettore IVA della pagina) e i creator aggiunti dalla vetrina (nome + #codice).
 * La pagina (assets/js/creators.js) riempie i campi con window.tcrForm.set({...}); senza JS il modulo funziona lo stesso.
 */
$fc_leadsrc = 'Pagina TOA Creators';
$fc_t = array(
  'eyebrow' => array('it'=>'Chiedi una selezione','en'=>'Get a shortlist','fr'=>'Demander une sélection','es'=>'Pide una selección'),
  'h'       => array('it'=>'Raccontaci il tuo brief','en'=>'Tell us about your brief','fr'=>'Parlez-nous de votre brief','es'=>'Cuéntanos tu brief'),
  'sub'     => array('it'=>'Prodotto, settore, Paese e date: ti mandiamo i creator disponibili con video, pubblico e prezzo. Se è urgente, rispondiamo subito.',
                     'en'=>'Product, sector, country and dates: we send you the creators who are available, with their videos, audience and price. If it\'s urgent, we reply right away.',
                     'fr'=>'Produit, secteur, pays et dates : nous vous envoyons les créateurs disponibles, avec leurs vidéos, leur audience et leur prix. Si c\'est urgent, nous répondons tout de suite.',
                     'es'=>'Producto, sector, país y fechas: te enviamos los creadores disponibles, con sus vídeos, su público y su precio. Si es urgente, respondemos enseguida.'),
  'company' => array('it'=>'Azienda','en'=>'Company','fr'=>'Entreprise','es'=>'Empresa'),
  'contact' => array('it'=>'Nome e cognome','en'=>'Full name','fr'=>'Nom et prénom','es'=>'Nombre y apellidos'),
  'email'   => array('it'=>'Email','en'=>'Email','fr'=>'E-mail','es'=>'Email'),
  'emailph' => array('it'=>'email@azienda.it','en'=>'email@company.com','fr'=>'email@entreprise.com','es'=>'email@empresa.com'),
  'phone'   => array('it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono'),
  'pack'    => array('it'=>'Cosa ti serve','en'=>'What you need','fr'=>'Ce dont vous avez besoin','es'=>'Qué necesitas'),
  'altro'   => array('it'=>'Altro / non lo so ancora','en'=>'Something else / not sure yet','fr'=>'Autre / je ne sais pas encore','es'=>'Otra cosa / aún no lo sé'),
  'msg'     => array('it'=>'Messaggio','en'=>'Message','fr'=>'Message','es'=>'Mensaje'),
  'msgph'   => array('it'=>'Prodotto, quanti contenuti, date, budget…','en'=>'Product, how many pieces of content, dates, budget…','fr'=>'Produit, nombre de contenus, dates, budget…','es'=>'Producto, cuántos contenidos, fechas, presupuesto…'),
  'sel'     => array('it'=>'Creator che ti interessano','en'=>'Creators you like','fr'=>'Créateurs qui vous intéressent','es'=>'Creadores que te interesan'),
  'selrm'   => array('it'=>'Togli','en'=>'Remove','fr'=>'Retirer','es'=>'Quitar'),
  'consent' => array('it'=>'Accetto il trattamento dei dati secondo la','en'=>'I accept the processing of my data according to the','fr'=>"J'accepte le traitement de mes données selon la",'es'=>'Acepto el tratamiento de mis datos según la'),
  'privacy' => array('it'=>'Privacy Policy','en'=>'Privacy Policy','fr'=>'Politique de confidentialité','es'=>'Política de privacidad'),
);
$fc_js = array(
  'sending'  => array('it'=>'Invio...','en'=>'Sending...','fr'=>'Envoi...','es'=>'Enviando...'),
  'submit'   => array('it'=>'Invia la richiesta','en'=>'Send your request','fr'=>'Envoyer la demande','es'=>'Enviar la solicitud'),
  'privacy'  => array('it'=>'Devi accettare la privacy policy per continuare.','en'=>'You must accept the privacy policy to continue.','fr'=>'Vous devez accepter la politique de confidentialité pour continuer.','es'=>'Debes aceptar la política de privacidad para continuar.'),
  'error'    => array('it'=>'Invio non riuscito. Riprova tra un attimo oppure scrivici a business@toagency.it.','en'=>'Sending failed. Please try again in a moment, or email us at business@toagency.it.','fr'=>"L'envoi a échoué. Réessayez dans un instant ou écrivez-nous à business@toagency.it.",'es'=>'No se ha podido enviar. Inténtalo de nuevo en un momento o escríbenos a business@toagency.it.'),
  'pack'     => array('it'=>'Pacchetto','en'=>'Package','fr'=>'Formule','es'=>'Paquete'),
  'settore'  => array('it'=>'Settore','en'=>'Sector','fr'=>'Secteur','es'=>'Sector'),
  'sede'     => array('it'=>'Sede azienda','en'=>'Company based in','fr'=>'Siège de l\'entreprise','es'=>'Sede de la empresa'),
  'creator'  => array('it'=>'Creator scelti','en'=>'Creators chosen','fr'=>'Créateurs choisis','es'=>'Creadores elegidos'),
);
$fc_packs = array(
  'ugc'          => array('it'=>'Video UGC per le tue ads','en'=>'UGC videos for your ads','fr'=>'Vidéos UGC pour vos pubs','es'=>'Vídeos UGC para tus anuncios'),
  'profilo'      => array('it'=>'Contenuto sul profilo del creator','en'=>"Posts on the creator's profile",'fr'=>'Publication sur le profil du créateur','es'=>'Publicación en el perfil del creador'),
  'coverage'     => array('it'=>'Live coverage di eventi e stand','en'=>'Live coverage for events and booths','fr'=>"Couverture live d'événements et de stands",'es'=>'Cobertura en directo de eventos y stands'),
  'multi'        => array('it'=>'Campagna con più creator','en'=>'Multi-creator campaign','fr'=>'Campagne multi-créateurs','es'=>'Campaña con varios creadores'),
  'continuativo' => array('it'=>'Progetto continuativo','en'=>'Ongoing partnership','fr'=>'Partenariat sur la durée','es'=>'Proyecto continuado'),
  'social'       => array('it'=>'Gestione dei social','en'=>'Social media management','fr'=>'Gestion des réseaux sociaux','es'=>'Gestión de redes sociales'),
  'ads'          => array('it'=>'Gestione delle pubblicità','en'=>'Paid ads management','fr'=>'Gestion des publicités','es'=>'Gestión de publicidad'),
  'shooting'     => array('it'=>'Shooting foto e video','en'=>'Photo & video shoot','fr'=>'Shooting photo et vidéo','es'=>'Sesión de fotos y vídeo'),
);
?>
<section class="tcr-req" id="richiesta">
  <div class="tcr-req__card inline-quote-card">
    <div class="tcr-eyebrow"><?php echo _ht($fc_t['eyebrow']); ?></div>
    <h2 class="tcr-h2"><?php echo _ht($fc_t['h']); ?></h2>
    <p class="tcr-req__sub"><?php echo _ht($fc_t['sub']); ?></p>

    <?php toa_component('b2b-intent-gate', array('form_id' => 'fcForm')); /* #672: azienda / cerco lavoro */ ?>

    <form id="fcForm" method="post" novalidate>
      <div class="iq-grid">
        <div class="iq-field"><label class="iq-label" for="fc_company"><?php echo _ht($fc_t['company']); ?></label><input type="text" id="fc_company" name="company" required class="iq-input" autocomplete="organization"></div>
        <div class="iq-field"><label class="iq-label" for="fc_contact"><?php echo _ht($fc_t['contact']); ?></label><input type="text" id="fc_contact" name="contact" required class="iq-input" autocomplete="name"></div>
      </div>
      <div class="iq-grid">
        <div class="iq-field"><label class="iq-label" for="fc_email"><?php echo _ht($fc_t['email']); ?></label><input type="email" id="fc_email" name="email" required class="iq-input" placeholder="<?php echo esc_attr(_ht($fc_t['emailph'])); ?>" autocomplete="email"></div>
        <div class="iq-field"><label class="iq-label" for="fc_phone"><?php echo _ht($fc_t['phone']); ?></label><input type="tel" id="fc_phone" name="phone" class="iq-input" autocomplete="tel"></div>
      </div>
      <div class="iq-field">
        <label class="iq-label" for="fc_pack"><?php echo _ht($fc_t['pack']); ?></label>
        <select id="fc_pack" class="iq-input">
          <?php foreach ($fc_packs as $k => $lbl): ?><option value="<?php echo esc_attr($k); ?>"><?php echo _ht($lbl); ?></option><?php endforeach; ?>
          <option value="altro"><?php echo _ht($fc_t['altro']); ?></option>
        </select>
      </div>
      <div class="tcr-req__sel" id="fcSelWrap" hidden>
        <div class="iq-label"><?php echo _ht($fc_t['sel']); ?></div>
        <div class="tcr-req__chips" id="fcSel" data-rm="<?php echo esc_attr(_ht($fc_t['selrm'])); ?>"></div>
      </div>
      <div class="iq-field">
        <label class="iq-label" for="fc_message"><?php echo _ht($fc_t['msg']); ?></label>
        <textarea id="fc_message" name="message" class="iq-input" placeholder="<?php echo esc_attr(_ht($fc_t['msgph'])); ?>"></textarea>
      </div>
      <input type="hidden" id="fc_settore" value=""><input type="hidden" id="fc_sede" value="">

      <label class="inline-quote-consent" for="fc_consent">
        <input type="checkbox" id="fc_consent" required>
        <span><?php echo _ht($fc_t['consent']); ?> <a href="<?php echo esc_url(toa_privacy_url()); ?>" target="_blank" rel="noopener"><?php echo _ht($fc_t['privacy']); ?></a></span>
      </label>
      <button type="submit" class="tcr-btn tcr-btn--p tcr-btn--lg iq-submit" id="fcSubmit">
        <span class="iq-spinner" id="fcSpinner"></span>
        <span id="fcSubmitText"><?php echo _ht($fc_js['submit']); ?></span>
      </button>
    </form>
  </div>
</section>
<script>
(function(){
  var form = document.getElementById('fcForm');
  if (!form) return;
  var ENDPOINT = 'https://toagency.it/crm_toagency/actions/lead-from-website.php';
  var TOKEN    = 'toa_lead_2026_x7k9m2p4q8w1';
  var LEADSRC  = '<?php echo esc_js($fc_leadsrc); ?>';
  var THANKYOU = '<?php echo esc_url(home_url('/tnx/')); ?>' + '?ls=' + encodeURIComponent(LEADSRC);
  var STR = <?php echo wp_json_encode(array_map(function($a){ return html_entity_decode(_ht($a), ENT_QUOTES, 'UTF-8'); }, $fc_js), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP); ?>;
  function v(id){ var el = document.getElementById(id); return el ? el.value.trim() : ''; }

  // creator aggiunti dalla vetrina: [{c:codice, n:nome}]
  var sel = [];
  var box = document.getElementById('fcSel'), wrap = document.getElementById('fcSelWrap');
  function drawSel(){
    wrap.hidden = !sel.length;
    box.innerHTML = '';
    sel.forEach(function(p, i){
      var b = document.createElement('button'); b.type = 'button'; b.className = 'tcr-chip on';
      b.title = box.getAttribute('data-rm'); b.textContent = p.n + ' #' + p.c + ' ×';
      b.addEventListener('click', function(){ sel.splice(i, 1); drawSel(); document.dispatchEvent(new CustomEvent('tcr:sel', { detail: sel.slice() })); });
      box.appendChild(b);
    });
  }
  window.tcrForm = {
    set: function(o){
      if (o.pack) { var s = document.getElementById('fc_pack'); if (s && s.querySelector('option[value="' + o.pack + '"]')) s.value = o.pack; }
      if ('settore' in o) document.getElementById('fc_settore').value = o.settore || '';
      if ('sede' in o) document.getElementById('fc_sede').value = o.sede || '';
    },
    toggle: function(p){
      var i = -1; sel.forEach(function(x, k){ if (String(x.c) === String(p.c)) i = k; });
      if (i >= 0) sel.splice(i, 1); else if (sel.length < 30) sel.push({ c: p.c, n: p.n });
      drawSel(); return i < 0;
    },
    has: function(c){ return sel.some(function(x){ return String(x.c) === String(c); }); }
  };

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
  var attr = (typeof window.toaAttr === 'function') ? window.toaAttr() : {};

  async function sendToCRM(payload){
    for (var i = 0; i < 3; i++) {
      try {
        var resp = await fetch(ENDPOINT, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Lead-Token': TOKEN }, body: JSON.stringify(payload) });
        var data = await resp.json();
        if (data.success) return true;
      } catch (e) { await new Promise(function(r){ setTimeout(r, 1000 * (i + 1)); }); }
    }
    return false;
  }

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    if (!form.checkValidity()) { form.reportValidity && form.reportValidity(); return; }
    if (!document.getElementById('fc_consent').checked) { alert(STR.privacy); return; }
    var btn = document.getElementById('fcSubmit'), txt = document.getElementById('fcSubmitText'), sp = document.getElementById('fcSpinner');
    btn.disabled = true; sp.style.display = 'inline-block'; txt.textContent = STR.sending;
    var ps = document.getElementById('fc_pack'), pl = ps.options[ps.selectedIndex] ? ps.options[ps.selectedIndex].text : '';
    var head = [STR.pack + ': ' + pl];
    if (v('fc_settore')) head.push(STR.settore + ': ' + v('fc_settore'));
    if (v('fc_sede')) head.push(STR.sede + ': ' + v('fc_sede'));
    if (sel.length) head.push(STR.creator + ': ' + sel.map(function(p){ return p.n + ' #' + p.c; }).join(', '));
    var m = v('fc_message');
    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    var payload = {
      source: LEADSRC + (isMobile ? ' (Mobile)' : ' (Desktop)'),
      company: v('fc_company'), contact: v('fc_contact'), email: v('fc_email'), phone: v('fc_phone'),
      event_type: 'social-content',
      message: head.join('\n') + (m ? '\n\n' + m : '')
    };
    Object.assign(payload, attr);
    if (await sendToCRM(payload)) { window.location.href = THANKYOU; return; }
    alert(STR.error);
    btn.disabled = false; sp.style.display = 'none'; txt.textContent = STR.submit;
  });
})();
</script>
