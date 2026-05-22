<?php
/**
 * Template Name: Hostess Live Form
 * Configuratore prezzi 10 step con calcolo live
 */
toa_component('header');
?>

<style>
.hlf-container{max-width:760px;margin:0 auto;padding:0 16px 120px}
.hlf-step{padding:40px 0;border-bottom:1px solid var(--gray-2)}
.hlf-step h3{font-size:1rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;margin-bottom:20px;color:var(--white)}
.hlf-step h3 span{color:var(--accent);margin-right:8px}
.hlf-select{width:100%;padding:14px;background:var(--gray-1);border:1px solid var(--gray-2);color:var(--white);font-size:0.95rem;font-family:inherit;-webkit-appearance:none;appearance:none;background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;padding-right:36px;cursor:pointer}
.hlf-select:focus{outline:none;border-color:var(--accent)}
.hlf-select option{background:var(--black);color:var(--white)}
.hlf-cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:8px;margin-top:12px}
.hlf-card{padding:16px;border:1.5px solid var(--gray-2);background:var(--gray-1);cursor:pointer;transition:all 0.3s;text-align:center}
.hlf-card:hover{border-color:var(--gray-3);background:rgba(245,245,243,0.08)}
.hlf-card.active{border-color:var(--accent);background:var(--accent-dim)}
.hlf-card-title{font-size:0.85rem;font-weight:700;text-transform:uppercase;margin-bottom:4px}
.hlf-card-desc{font-size:0.75rem;color:var(--gray-4)}
.hlf-card-price{font-size:0.75rem;color:var(--accent);font-weight:600;margin-top:6px}
.hlf-numbers{display:grid;grid-template-columns:repeat(3,1fr);gap:12px}
.hlf-num-group{text-align:center}
.hlf-num-group label{display:block;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-4);margin-bottom:8px}
.hlf-num-controls{display:flex;align-items:center;justify-content:center;gap:12px}
.hlf-num-btn{width:36px;height:36px;border:1px solid var(--gray-3);background:var(--gray-1);color:var(--white);font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all 0.2s}
.hlf-num-btn:hover{background:var(--accent);color:var(--black);border-color:var(--accent)}
.hlf-num-val{font-size:1.4rem;font-weight:900;min-width:30px;text-align:center}
.hlf-height-grid{display:flex;gap:8px;flex-wrap:wrap}
.hlf-height-btn{padding:10px 16px;border:1px solid var(--gray-2);background:var(--gray-1);color:var(--gray-5);font-size:0.8rem;font-weight:600;cursor:pointer;transition:all 0.2s}
.hlf-height-btn:hover{border-color:var(--gray-3)}
.hlf-height-btn.active{border-color:var(--accent);background:var(--accent-dim);color:var(--white)}
.hlf-input{width:100%;padding:14px;background:var(--gray-1);border:1px solid var(--gray-2);color:var(--white);font-size:0.95rem;font-family:inherit;transition:border-color 0.2s}
.hlf-input:focus{outline:none;border-color:var(--accent)}
.hlf-input::placeholder{color:var(--gray-3)}
textarea.hlf-input{min-height:80px;resize:vertical}
.hlf-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px}
.hlf-label{display:block;font-size:0.7rem;font-weight:600;text-transform:uppercase;letter-spacing:0.5px;color:var(--gray-4);margin-bottom:6px}
.hlf-hint{font-size:0.78rem;color:var(--gray-4);margin-top:8px;padding:10px;background:var(--gray-1);border-left:2px solid var(--accent)}
.hlf-submit{width:100%;padding:20px;background:var(--accent);color:var(--black);border:none;font-size:1rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;cursor:pointer;transition:all 0.3s;margin-top:16px}
.hlf-submit:hover{transform:translateY(-1px);box-shadow:0 6px 24px rgba(200,255,0,0.3)}
.hlf-sticky{position:fixed;bottom:0;left:0;right:0;z-index:998;background:rgba(8,8,10,0.96);backdrop-filter:blur(20px);border-top:2px solid var(--accent);padding:12px 20px;display:flex;align-items:center;justify-content:space-between;transform:translateY(100%);transition:transform 0.4s var(--ease-out)}
.hlf-sticky.visible{transform:translateY(0)}
.hlf-sticky-price{font-family:var(--font-display);font-size:1.6rem;font-weight:900}
.hlf-sticky-details{font-size:0.7rem;color:var(--gray-4);line-height:1.4}
.hlf-sticky-btn{padding:12px 24px;background:var(--accent);color:var(--black);font-size:0.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;border:none;cursor:pointer}
.hlf-privacy{display:flex;align-items:flex-start;gap:8px;font-size:0.75rem;color:var(--gray-4);margin:16px 0}
.hlf-privacy input{width:16px;height:16px;margin-top:1px;accent-color:var(--accent)}
@media(max-width:640px){.hlf-container{padding:0 12px 100px}.hlf-step{padding:24px 0}.hlf-step h3{font-size:0.9rem}.hlf-cards{grid-template-columns:1fr}.hlf-card{padding:12px}.hlf-numbers{grid-template-columns:1fr 1fr;gap:16px}.hlf-num-group:last-child{grid-column:1/-1}.hlf-row{grid-template-columns:1fr}.hlf-height-grid{gap:6px}.hlf-height-btn{padding:8px 12px;font-size:0.75rem}.hlf-sticky{padding:10px 14px;bottom:0}.hlf-sticky-price{font-size:1.3rem}.hlf-sticky-details{display:block;font-size:0.65rem}.hlf-sticky-cta{padding:10px 18px;font-size:0.8rem}}
.sticky-cta{display:none!important}
</style>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'PREVENTIVO HOSTESS',
    'title'      => 'Preventivo Live.',
    'subtitle'   => '<strong>Costruisci il tuo preventivo in pochi secondi.</strong><br>Ricevi subito conferma e una selezione di profili pronti per te.<br><br><span style="color:var(--accent)">✨ TARIFFA GARANTITA CON CONFERMA ENTRO 24 ORE</span>',
)); ?>

<div class="hlf-container"><form id="hlfForm">
<!-- 1. Città --><div class="hlf-step"><h3><span>1.</span> Città/provincia dell'evento</h3><select class="hlf-select" id="hlf-city" onchange="hlfCalc()"><option value="">-- Seleziona --</option><optgroup label="Principali"><option value="milano" data-extra="1.10">Milano (+10%)</option><option value="roma">Roma</option><option value="bologna">Bologna</option><option value="torino">Torino</option><option value="firenze">Firenze</option><option value="verona">Verona</option><option value="rimini">Rimini</option><option value="genova">Genova</option><option value="napoli">Napoli</option><option value="bari">Bari</option><option value="palermo">Palermo</option></optgroup><optgroup label="Nord"><option value="bergamo">Bergamo</option><option value="brescia">Brescia</option><option value="como">Como</option><option value="padova">Padova</option><option value="trento">Trento</option><option value="treviso">Treviso</option><option value="trieste">Trieste</option><option value="varese">Varese</option><option value="venezia">Venezia</option><option value="vicenza">Vicenza</option><option value="novara">Novara</option><option value="piacenza">Piacenza</option><option value="parma">Parma</option></optgroup><optgroup label="Centro"><option value="ancona">Ancona</option><option value="arezzo">Arezzo</option><option value="perugia">Perugia</option><option value="pescara">Pescara</option><option value="pisa">Pisa</option><option value="siena">Siena</option><option value="latina">Latina</option></optgroup><optgroup label="Sud e Isole"><option value="avellino">Avellino</option><option value="brindisi">Brindisi</option><option value="cagliari">Cagliari</option><option value="caserta">Caserta</option><option value="catania">Catania</option><option value="cosenza">Cosenza</option><option value="lecce">Lecce</option><option value="messina">Messina</option><option value="salerno">Salerno</option><option value="sassari">Sassari</option><option value="taranto">Taranto</option></optgroup></select></div>
<!-- 2. Operativi --><div class="hlf-step"><h3><span>2.</span> Dettagli operativi</h3><div class="hlf-numbers"><div class="hlf-num-group"><label>Persone</label><div class="hlf-num-controls"><button type="button" class="hlf-num-btn" onclick="hlfNum('people',-1)">−</button><div class="hlf-num-val" id="hlf-people">1</div><button type="button" class="hlf-num-btn" onclick="hlfNum('people',1)">+</button></div></div><div class="hlf-num-group"><label>Giorni</label><div class="hlf-num-controls"><button type="button" class="hlf-num-btn" onclick="hlfNum('days',-1)">−</button><div class="hlf-num-val" id="hlf-days">1</div><button type="button" class="hlf-num-btn" onclick="hlfNum('days',1)">+</button></div></div><div class="hlf-num-group"><label>Ore/giorno</label><select class="hlf-select" id="hlf-hours" onchange="hlfCalc()" style="max-width:140px;margin:0 auto"><option value="2">2h</option><option value="4">4h</option><option value="6" selected>6h</option><option value="8">8h</option><option value="10">10h</option><option value="12">12h</option></select></div></div><div class="hlf-hint">💡 Sconto automatico per volumi (fino a -15% persone, -12% giorni)</div></div>
<!-- 3. Tipo --><div class="hlf-step"><h3><span>3.</span> Tipo di personale</h3><div class="hlf-cards" style="grid-template-columns:1fr 1fr"><div class="hlf-card active" onclick="hlfSelect(this,'type','hostess')"><div class="hlf-card-title">Hostess</div><div class="hlf-card-desc">Personale femminile</div></div><div class="hlf-card" onclick="hlfSelect(this,'type','steward')"><div class="hlf-card-title">Steward</div><div class="hlf-card-desc">Personale maschile</div></div></div></div>
<!-- 4. Presenza --><div class="hlf-step"><h3><span>4.</span> Livello di presenza</h3><div class="hlf-cards"><div class="hlf-card" onclick="hlfSelect(this,'presence','basic')"><div class="hlf-card-title">Standard</div><div class="hlf-card-desc">Nessun requisito estetico</div><div class="hlf-card-price">€119/8h</div></div><div class="hlf-card" onclick="hlfSelect(this,'presence','good')"><div class="hlf-card-title">Buona presenza</div><div class="hlf-card-desc">Presenza curata</div><div class="hlf-card-price">€199/8h</div></div><div class="hlf-card" onclick="hlfSelect(this,'presence','model')"><div class="hlf-card-title">Modello/a</div><div class="hlf-card-desc">Professionale fashion</div><div class="hlf-card-price">€299/8h</div></div></div></div>
<!-- 5. Altezza --><div class="hlf-step"><h3><span>5.</span> Altezza (opzionale)</h3><div class="hlf-height-grid"><button type="button" class="hlf-height-btn active" onclick="hlfHeight(this,'')">Non importante</button><button type="button" class="hlf-height-btn" onclick="hlfHeight(this,'165+')">165+</button><button type="button" class="hlf-height-btn" onclick="hlfHeight(this,'170+')">170+</button><button type="button" class="hlf-height-btn" onclick="hlfHeight(this,'175+')">175+</button><button type="button" class="hlf-height-btn" onclick="hlfHeight(this,'180+')">180+</button></div></div>
<!-- 6. Lingue --><div class="hlf-step"><h3><span>6.</span> Competenze linguistiche</h3><div class="hlf-cards"><div class="hlf-card active" onclick="hlfSelect(this,'lang','it')"><div class="hlf-card-title">Solo italiano</div><div class="hlf-card-price">Standard</div></div><div class="hlf-card" onclick="hlfSelect(this,'lang','en')"><div class="hlf-card-title">Italiano + Inglese</div><div class="hlf-card-price">+15%</div></div><div class="hlf-card" onclick="hlfSelect(this,'lang','multi')"><div class="hlf-card-title">Multilingue</div><div class="hlf-card-price">+30%</div></div></div></div>
<!-- 7. Pagamento --><div class="hlf-step"><h3><span>7.</span> Modalità di pagamento</h3><div class="hlf-cards" style="grid-template-columns:1fr 1fr"><div class="hlf-card active" onclick="hlfSelect(this,'payment','advance')"><div class="hlf-card-title">100% Anticipato</div><div class="hlf-card-price">Miglior prezzo</div></div><div class="hlf-card" onclick="hlfSelect(this,'payment','post')"><div class="hlf-card-title">Post evento</div><div class="hlf-card-desc">Acconto 50%</div><div class="hlf-card-price">+25%</div></div></div></div>
<!-- 8. Periodo --><div class="hlf-step"><h3><span>8.</span> Periodo dell'evento</h3><div class="hlf-row"><div><label class="hlf-label">Data inizio</label><input type="date" class="hlf-input" id="hlf-date-start"></div><div><label class="hlf-label">Data fine (opz.)</label><input type="date" class="hlf-input" id="hlf-date-end"></div></div></div>
<!-- 9. Descrizione --><div class="hlf-step"><h3><span>9.</span> Descrizione progetto (opzionale)</h3><textarea class="hlf-input" id="hlf-description" placeholder="Tipo di evento, dress code, compiti..."></textarea></div>
<!-- 10. Dati --><div class="hlf-step" style="border-bottom:none"><h3><span>10.</span> I tuoi dati</h3><div class="hlf-row"><div><label class="hlf-label">Nome *</label><input type="text" class="hlf-input" id="hlf-name" required placeholder="Il tuo nome"></div><div><label class="hlf-label">Azienda *</label><input type="text" class="hlf-input" id="hlf-company" required placeholder="Nome azienda"></div></div><div class="hlf-row"><div><label class="hlf-label">Email *</label><input type="email" class="hlf-input" id="hlf-email" required placeholder="email@azienda.it"></div><div><label class="hlf-label">Telefono *</label><input type="tel" class="hlf-input" id="hlf-phone" required placeholder="+39..."></div></div><label class="hlf-privacy"><input type="checkbox" id="hlf-consent" required><span>Accetto il trattamento dei dati secondo la <a href="https://www.iubenda.com/privacy-policy/58462877" target="_blank" style="color:var(--accent);text-decoration:underline">privacy policy</a></span></label><button type="submit" class="hlf-submit" id="hlfSubmitBtn">🚀 INVIA RICHIESTA PREVENTIVO</button><p style="text-align:center;font-size:0.7rem;color:var(--gray-4);margin-top:12px;letter-spacing:1px;text-transform:uppercase">Ti contattiamo in pochi minuti • Assistenza H24 • Profili selezionati</p></div>
</form></div>

<div class="hlf-sticky" id="hlfSticky"><div><div class="hlf-sticky-price">€<span id="hlfStickyPrice">0</span></div><div class="hlf-sticky-details" id="hlfStickyDetails">Seleziona le opzioni</div></div><button type="button" class="hlf-sticky-btn" onclick="document.getElementById('hlfSubmitBtn').scrollIntoView({behavior:'smooth'})">📩 INVIA</button></div>

<script>
/* ═══ HLF Missing Functions Fix ═══ */

/* Increment/decrement people or days */
function hlfNum(field, delta) {
    var id = field === 'people' ? 'hlf-people' : 'hlf-days';
    var el = document.getElementById(id);
    if (!el) return;
    var val = parseInt(el.textContent) || 1;
    val = Math.max(1, val + delta);
    if (field === 'people') val = Math.min(val, 50);
    if (field === 'days') val = Math.min(val, 30);
    el.textContent = val;
    hlfCalcPrice();
}

/* Select height button */
function hlfHeight(el, value) {
    var btns = el.parentElement.querySelectorAll('.hlf-height-btn');
    btns.forEach(function(b) { b.classList.remove('active'); });
    el.classList.add('active');
    if (!window.hlfSelections) window.hlfSelections = {};
    window.hlfSelections.height = value;
}

/* Calculate and update live price */
function hlfCalcPrice() {
    var prices = { basic: 119, good: 199, model: 299 };
    var langMult = { it: 1, en: 1.15, multi: 1.30 };
    var payMult = { advance: 1, post: 1.25 };
    var hoursMult = { '2': 0.35, '4': 0.6, '6': 0.8, '8': 1, '10': 1.25, '12': 1.45 };

    var sel = window.hlfSelections || {};
    var base = prices[sel.presence] || 0;
    var people = parseInt((document.getElementById('hlf-people') || {}).textContent) || 1;
    var days = parseInt((document.getElementById('hlf-days') || {}).textContent) || 1;
    var hours = (document.getElementById('hlf-hours') || {}).value || '8h';
    var hMult = hoursMult[hours] || 1;
    var lMult = langMult[sel.lang] || 1;
    var pMult = payMult[sel.payment] || 1;

    /* Volume discounts */
    var peopleDisc = people >= 10 ? 0.85 : people >= 5 ? 0.90 : people >= 3 ? 0.95 : 1;
    var daysDisc = days >= 5 ? 0.88 : days >= 3 ? 0.92 : 1;

    var total = Math.round(base * hMult * people * days * lMult * pMult * peopleDisc * daysDisc);

    var priceEl = document.getElementById('hlfStickyPrice');
    var detailsEl = document.getElementById('hlfStickyDetails');
    var stickyEl = document.getElementById('hlfSticky');

    if (priceEl) priceEl.textContent = total.toLocaleString('it-IT');
    if (detailsEl) {
        if (base === 0) {
            detailsEl.textContent = 'Seleziona livello di presenza';
        } else {
            detailsEl.textContent = people + ' pers. \u00d7 ' + days + ' gg \u00d7 ' + hours + 'h';
        }
    }
    if (stickyEl && total > 0) stickyEl.classList.add('visible');
}

/* Also update price when hlfSelect is called */
var _origHlfSelect = window.hlfSelect;
window.hlfSelect = function(el, group, value) {
    if (typeof _origHlfSelect === 'function') _origHlfSelect(el, group, value);
    else {
        var step = el.closest('.hlf-step');
        if (step) {
            step.querySelectorAll('.hlf-card, .hlf-btn').forEach(function(s) { s.classList.remove('active'); });
        }
        el.classList.add('active');
        if (!window.hlfSelections) window.hlfSelections = {};
        window.hlfSelections[group] = value;
    }
    hlfCalcPrice();
};

/* Recalc on hours dropdown change */
document.addEventListener('DOMContentLoaded', function() {
    var hoursEl = document.getElementById('hlf-hours');
    if (hoursEl) hoursEl.addEventListener('change', hlfCalcPrice);
});

window.hlfUpdatePrice = hlfCalcPrice;
</script>

<script>
(function(){
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
  var form = document.getElementById("hlfForm");
  if(form){
    form.addEventListener("submit", function(e){
      e.preventDefault();
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
        console.warn("CRM call failed:", err);
        goThankYou();
      });
    });
  }
})();
</script>
<?php toa_component('footer'); ?>
