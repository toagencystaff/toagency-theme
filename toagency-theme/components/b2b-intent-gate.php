<?php
/**
 * Component: domanda "Sei un'azienda o cerchi lavoro?" davanti ai form B2B (ticket #672, 19/10/2026).
 * Usage: toa_component('b2b-intent-gate', array('form_id' => 'leadForm'))
 *
 * Chi cerca lavoro compilava per errore il form clienti e il CRM creava lavoro+cliente finti.
 * - "Sono un'azienda" -> compare il form (invariato, stesso invio al CRM).
 * - "Cerco lavoro"    -> form nascosto, messaggio + bottone verso la registrazione talent della lingua.
 *                        Nessuna chiamata al CRM; la bozza salvata (localStorage) viene cancellata e il
 *                        beacon di recupero deve restare fermo (window.toaIntent === 'lavoro').
 * Senza JS il form resta visibile (il nascondi lo fa solo lo script).
 * Stringhe 4 lingue qui dentro (non in translations.php, file globale lockato). Lingua: stessa logica di _ht().
 */
global $__l;
$ig_l = (!empty($__l) && in_array($__l, array('it','en','fr','es'), true)) ? $__l : (function_exists('toa_current_lang') ? toa_current_lang() : 'it');
if (!in_array($ig_l, array('it','en','fr','es'), true)) $ig_l = 'it';
$ig_form = isset($form_id) ? $form_id : 'leadForm';
$ig_reg  = array('it'=>'/registrati-talent/','en'=>'/en/register-talent/','fr'=>'/fr/inscription-talent/','es'=>'/es/registrate-como-talent/'); // stessi URL di page-collabora.php
$ig_t = array(
  'q'      => array('it'=>'Sei un\'azienda che cerca personale o una persona che cerca lavoro?','en'=>'Are you a company looking for staff, or a person looking for work?','fr'=>'Vous êtes une entreprise à la recherche de personnel, ou une personne à la recherche d\'un emploi ?','es'=>'¿Eres una empresa que busca personal o una persona que busca trabajo?'),
  'azi'    => array('it'=>'Sono un\'azienda','en'=>'I\'m a company','fr'=>'Je suis une entreprise','es'=>'Soy una empresa'),
  'lav'    => array('it'=>'Cerco lavoro','en'=>'I\'m looking for work','fr'=>'Je cherche du travail','es'=>'Busco trabajo'),
  'h'      => array('it'=>'Questo modulo è per le aziende.','en'=>'This form is for companies.','fr'=>'Ce formulaire s\'adresse aux entreprises.','es'=>'Este formulario es para empresas.'),
  'p'      => array('it'=>'Per candidarti ai nostri casting e lavori, registrati come talent.','en'=>'To apply for our castings and jobs, sign up as a talent.','fr'=>'Pour postuler à nos castings et missions, inscrivez-vous comme talent.','es'=>'Para presentarte a nuestros castings y trabajos, regístrate como talent.'),
  'cta'    => array('it'=>'Registrati come talent','en'=>'Sign up as a talent','fr'=>'M\'inscrire comme talent','es'=>'Registrarme como talent'),
);
$ig_id = 'toaGate_' . preg_replace('/[^a-z0-9_]/i', '', $ig_form);
?>
<div id="<?php echo esc_attr($ig_id); ?>" class="toa-gate" role="group" aria-label="<?php echo esc_attr($ig_t['q'][$ig_l]); ?>">
  <p class="toa-gate-q"><?php echo esc_html($ig_t['q'][$ig_l]); ?></p>
  <div class="toa-gate-btns">
    <button type="button" class="toa-gate-btn" data-intent="azienda" aria-pressed="false"><?php echo esc_html($ig_t['azi'][$ig_l]); ?></button>
    <button type="button" class="toa-gate-btn" data-intent="lavoro" aria-pressed="false"><?php echo esc_html($ig_t['lav'][$ig_l]); ?></button>
  </div>
  <div class="toa-gate-job" hidden>
    <p class="toa-gate-job-h"><?php echo esc_html($ig_t['h'][$ig_l]); ?></p>
    <p class="toa-gate-job-p"><?php echo esc_html($ig_t['p'][$ig_l]); ?></p>
    <a class="btn-hero btn-hero-primary toa-gate-cta" href="<?php echo esc_url($ig_reg[$ig_l]); ?>" onclick="if(window.toaTrackContact)toaTrackContact('b2c_signup','form-gate')"><?php echo esc_html($ig_t['cta'][$ig_l]); ?></a>
  </div>
</div>
<style>
.toa-gate{margin:0 0 20px;text-align:center}
.toa-gate-q{font-size:.95rem;font-weight:600;margin:0 0 12px}
.toa-gate-btns{display:flex;gap:10px}
.toa-gate-btn{flex:1 1 0;padding:14px 10px;font:inherit;font-size:.78rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;color:inherit;background:transparent;border:1px solid rgba(128,128,128,.55);border-radius:0;cursor:pointer;transition:box-shadow .15s}
.toa-gate-btn:hover{border-color:currentColor}
.toa-gate-btn[aria-pressed="true"]{box-shadow:inset 0 0 0 2px currentColor;border-color:currentColor}
.toa-gate-job{margin-top:18px;padding:18px 14px;border:1px solid rgba(128,128,128,.55)}
.toa-gate-job[hidden]{display:none}
.toa-gate-job-h{font-weight:700;margin:0 0 6px}
.toa-gate-job-p{font-size:.9rem;opacity:.8;margin:0 0 14px}
.toa-gate-cta{display:inline-block;text-decoration:none;padding:14px 22px}
@media(max-width:480px){.toa-gate-btns{flex-direction:column}}
</style>
<script>
(function(){
  var gate = document.getElementById(<?php echo json_encode($ig_id); ?>);
  if (!gate) return;
  // il form sta DOPO questo blocco nella pagina: lo si nasconde con uno stile iniettato subito (niente lampo, senza JS resta visibile)
  var hide = document.createElement('style');
  hide.appendChild(document.createTextNode('#' + <?php echo json_encode($ig_form); ?> + '{display:none!important}'));
  document.head.appendChild(hide);
  var job  = gate.querySelector('.toa-gate-job');
  var btns = gate.querySelectorAll('.toa-gate-btn');
  function pick(intent){
    window.toaIntent = intent;
    for (var i = 0; i < btns.length; i++) btns[i].setAttribute('aria-pressed', btns[i].getAttribute('data-intent') === intent ? 'true' : 'false');
    if (intent === 'azienda') {
      if (hide.parentNode) hide.parentNode.removeChild(hide);
      job.hidden = true;
    } else {
      if (!hide.parentNode) document.head.appendChild(hide);
      job.hidden = false;
      // niente bozza: il beacon di recupero non deve mandare nulla al CRM a chi cerca lavoro
      try { localStorage.removeItem('toa_form_b2b'); } catch(e) {}
    }
  }
  for (var i = 0; i < btns.length; i++) btns[i].addEventListener('click', function(){ pick(this.getAttribute('data-intent')); });
  // chi stava già compilando (bozza < 24h) torna direttamente al form
  try {
    var s = JSON.parse(localStorage.getItem('toa_form_b2b') || '{}');
    if (s._savedAt && (Date.now() - new Date(s._savedAt)) / 3600000 < 24 && (s.company || s.email)) pick('azienda');
  } catch(e) {}
})();
</script>
