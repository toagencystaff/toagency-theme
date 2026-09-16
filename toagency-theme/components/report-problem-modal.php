<?php
/**
 * Component: Report Problem Modal
 * Ticket #80 (bug-tracker CRM) — permette a chi visita il sito di segnalare un
 * problema o qualcosa poco chiaro. Non tocca il chatbot Amelia (repo crm_toagency,
 * fuori da questo tema): form leggero, stesso pattern fetch()->CRM di form-b2b-inline.php.
 * Usage: toa_component('report-problem-modal')  (incluso dal footer su tutte le pagine)
 *
 * Endpoint CRM da costruire lato crm_toagency/actions/report-problem.php — struttura
 * payload e token documentati nel commento in fondo a questo file.
 */
$toa_rp_lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'it';
?>
<div id="toaReportOverlay" class="toa-rp-overlay" style="display:none;">
  <div class="toa-rp-box" role="dialog" aria-modal="true" aria-labelledby="toaRpTitle">
    <button type="button" class="toa-rp-close" onclick="toaCloseReportModal()" aria-label="<?php echo esc_attr(_ht(['it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar'])); ?>">&times;</button>
    <h3 id="toaRpTitle"><?php echo _ht(['it'=>'Segnala un problema','en'=>'Report a problem','fr'=>'Signaler un problème','es'=>'Informar un problema']); ?></h3>
    <form id="toaReportForm">
      <textarea id="toaRpMessage" required maxlength="1000" placeholder="<?php echo esc_attr(_ht(['it'=>'Descrivi cosa non funziona o non è chiaro...','en'=>"Describe what's not working or unclear...",'fr'=>"Décris ce qui ne fonctionne pas ou n'est pas clair...",'es'=>'Describe qué no funciona o no está claro...'])); ?>"></textarea>
      <input type="email" id="toaRpEmail" placeholder="<?php echo esc_attr(_ht(['it'=>'La tua email (facoltativa, per risponderti)','en'=>'Your email (optional, so we can reply)','fr'=>'Ton email (facultatif, pour te répondre)','es'=>'Tu email (opcional, para responderte)'])); ?>">
      <input type="text" id="toaRpHp" name="toa_rp_hp" style="position:absolute;left:-9999px;" tabindex="-1" autocomplete="off">
      <button type="submit" id="toaRpSubmit"><?php echo _ht(['it'=>'Invia segnalazione','en'=>'Send report','fr'=>'Envoyer','es'=>'Enviar']); ?></button>
      <p id="toaRpStatus" class="toa-rp-status"></p>
    </form>
  </div>
</div>

<style>
.toa-rp-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:20000;display:flex;align-items:center;justify-content:center;padding:16px;}
.toa-rp-box{background:#fff;color:#111;max-width:420px;width:100%;padding:28px;border-radius:8px;position:relative;}
.toa-rp-box h3{margin:0 0 14px;font-size:1.1rem;}
.toa-rp-close{position:absolute;top:10px;right:14px;background:none;border:none;font-size:1.6rem;line-height:1;cursor:pointer;color:#666;}
.toa-rp-box textarea{width:100%;min-height:90px;margin-bottom:10px;padding:10px;font-family:inherit;font-size:.9rem;border:1px solid #ccc;border-radius:4px;resize:vertical;box-sizing:border-box;}
.toa-rp-box input[type=email]{width:100%;margin-bottom:12px;padding:10px;font-family:inherit;font-size:.9rem;border:1px solid #ccc;border-radius:4px;box-sizing:border-box;}
.toa-rp-box button[type=submit]{width:100%;padding:12px;background:#111;color:#c8ff00;border:none;border-radius:4px;font-weight:700;cursor:pointer;}
.toa-rp-box button[type=submit]:disabled{opacity:.6;cursor:default;}
.toa-rp-status{font-size:.8rem;margin-top:8px;min-height:1em;}
</style>

<script>
function toaOpenReportModal(){
  var o = document.getElementById('toaReportOverlay');
  if (o) { o.style.display = 'flex'; }
}
function toaCloseReportModal(){
  var o = document.getElementById('toaReportOverlay');
  if (o) { o.style.display = 'none'; }
}
(function(){
  var form = document.getElementById('toaReportForm');
  if (!form) return;
  var ENDPOINT = 'https://toagency.it/crm_toagency/actions/report-problem.php';
  var TOKEN    = 'toa_report_2026_h4n8v2k6t9r3';
  var LANG     = '<?php echo esc_js($toa_rp_lang); ?>';
  var STR = {
    sending: '<?php echo esc_js(_ht(["it"=>"Invio...","en"=>"Sending...","fr"=>"Envoi...","es"=>"Enviando..."])); ?>',
    ok:      '<?php echo esc_js(_ht(["it"=>"Grazie, l'abbiamo ricevuta!","en"=>"Thanks, we received it!","fr"=>"Merci, nous l'avons reçu !","es"=>"¡Gracias, la hemos recibido!"])); ?>',
    err:     '<?php echo esc_js(_ht(["it"=>"Errore, riprova più tardi.","en"=>"Error, please try again later.","fr"=>"Erreur, réessaie plus tard.","es"=>"Error, inténtalo más tarde."])); ?>'
  };

  form.addEventListener('submit', async function(e){
    e.preventDefault();
    if (document.getElementById('toaRpHp').value) return; // honeypot: bot, ignora silenziosamente

    var msg = document.getElementById('toaRpMessage').value.trim();
    if (!msg) return;

    var btn = document.getElementById('toaRpSubmit');
    var status = document.getElementById('toaRpStatus');
    btn.disabled = true;
    status.textContent = STR.sending;

    var payload = {
      problema: msg.slice(0, 1000),
      email: document.getElementById('toaRpEmail').value.trim().slice(0, 255),
      pagina: location.href.slice(0, 500),
      user_agent: navigator.userAgent.slice(0, 255),
      lingua: LANG,
      data: new Date().toISOString().slice(0, 19).replace('T', ' ')
    };

    var ok = false;
    for (var i = 0; i < 2 && !ok; i++) {
      try {
        var resp = await fetch(ENDPOINT, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'X-Report-Token': TOKEN },
          body: JSON.stringify(payload)
        });
        var data = await resp.json();
        if (data.success) ok = true;
      } catch (e) {
        await new Promise(function(r){ setTimeout(r, 1000); });
      }
    }

    if (ok) {
      status.textContent = STR.ok;
      form.reset();
      setTimeout(toaCloseReportModal, 1800);
    } else {
      status.textContent = STR.err;
      btn.disabled = false;
    }
  });
})();
</script>
<?php
/**
 * STRUTTURA DATI per la chat CRM (endpoint crm_toagency/actions/report-problem.php):
 * POST JSON, header X-Report-Token: toa_report_2026_h4n8v2k6t9r3
 * { "problema": string(max1000, required), "email": string(max255, optional),
 *   "pagina": string(url pagina di provenienza), "user_agent": string,
 *   "lingua": "it|en|fr|es", "data": "YYYY-MM-DD HH:MM:SS" }
 * Risposta attesa: {"success": true} (stesso contratto di lead-from-website.php)
 */
