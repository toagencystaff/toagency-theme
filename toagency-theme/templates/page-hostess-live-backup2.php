<?php
/**
 * Template Name: Preventivo Staff Eventi
 * Configuratore prezzi con calcolo live
 * TOAgency — v4.1
 * Features: 12 figure, step condizionale presenza, 5 pagamenti,
 *           supplementi, città EU, integrazione CRM nuovo-lavoro,
 *           codice lavoro unico mostrato al cliente, i18n ready
 */
$td = 'toagency';
toa_component('header');
?>

<style>
:root{--hlf-max:760px;--hlf-radius:2px;--hlf-transition:0.25s cubic-bezier(0.4,0,0.2,1)}
.hlf-container{max-width:var(--hlf-max);margin:0 auto;padding:0 16px 140px}
.hlf-step{padding:48px 0;border-bottom:1px solid var(--gray-2)}.hlf-step:last-child{border-bottom:none}
.hlf-step-head{display:flex;align-items:center;gap:14px;margin-bottom:12px}
.hlf-step-num{font-size:.65rem;font-weight:800;letter-spacing:2px;color:var(--accent);min-width:36px;height:36px;display:flex;align-items:center;justify-content:center;border:1.5px solid var(--accent);border-radius:var(--hlf-radius);flex-shrink:0}
.hlf-step-title{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--white);line-height:1.2}
.hlf-step-sub{font-size:.88rem;color:var(--gray-4);margin-bottom:24px;line-height:1.5}
.hlf-cat-label{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:2px;color:var(--gray-4);margin:20px 0 8px;padding-bottom:6px;border-bottom:1px solid var(--gray-2)}.hlf-cat-label:first-child{margin-top:0}

/* ── ACCORDION FIGURE ── */
.hlf-acc{border:1px solid var(--gray-2);margin-bottom:10px;border-radius:var(--hlf-radius);overflow:hidden}
.hlf-acc-head{width:100%;display:flex;align-items:center;gap:14px;padding:20px 22px;background:var(--gray-1);border:none;cursor:pointer;text-align:left;transition:background .2s}
.hlf-acc-head:hover{background:rgba(200,255,0,.04)}
.hlf-acc.open .hlf-acc-head{background:rgba(200,255,0,.05);border-bottom:1px solid var(--gray-2)}
.hlf-acc-icon{font-size:1.2rem;color:var(--accent);min-width:22px;transition:transform .25s;line-height:1;font-style:normal}
.hlf-acc.open .hlf-acc-icon{transform:rotate(45deg)}
.hlf-acc-label{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--white);flex:1}
.hlf-acc-hint{font-size:.72rem;color:var(--gray-4)}
@media(max-width:500px){.hlf-acc-hint{display:none}}
.hlf-acc-body{display:none;flex-direction:column}
.hlf-acc.open .hlf-acc-body{display:flex}

/* Figure rows */
.hlf-fig-row{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 22px;cursor:pointer;border-bottom:1px solid var(--gray-2);transition:background .2s;position:relative}
.hlf-fig-row:last-child{border-bottom:none}
.hlf-fig-row:hover{background:rgba(200,255,0,.04)}
.hlf-fig-row.active{background:rgba(200,255,0,.07);box-shadow:inset 3px 0 0 var(--accent)}
.hlf-fig-info{flex:1}
.hlf-fig-title{font-size:1rem;font-weight:700;color:var(--white);margin-bottom:4px}
.hlf-fig-desc{font-size:.8rem;color:var(--gray-4);line-height:1.4}
.hlf-fig-price{font-size:.9rem;font-weight:800;color:var(--accent);white-space:nowrap;flex-shrink:0}

/* ── resto UI invariato ── */
.hlf-cards{display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:8px}
.hlf-card{padding:14px 12px;border:1.5px solid var(--gray-2);background:var(--gray-1);cursor:pointer;transition:all var(--hlf-transition);text-align:center;position:relative}
.hlf-card:hover{border-color:var(--gray-3);background:rgba(245,245,243,.06)}
.hlf-card.active{border-color:var(--accent);background:rgba(200,255,0,.06)}
.hlf-card-title{font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px}
.hlf-card-desc{font-size:.75rem;color:var(--gray-4);line-height:1.3}
.hlf-card-price{font-size:.78rem;color:var(--accent);font-weight:700;margin-top:6px}
.hlf-card-tag{position:absolute;top:-1px;right:-1px;font-size:.55rem;font-weight:800;letter-spacing:1px;padding:3px 8px;background:var(--accent);color:var(--black);text-transform:uppercase}
.hlf-cards--pay{grid-template-columns:1fr 1fr}
.hlf-cards--pay .hlf-card{text-align:left;padding:18px}
.hlf-card-mechanism{font-size:.72rem;color:var(--gray-4);margin-top:4px;line-height:1.3}
.hlf-card-warn{font-size:.65rem;color:#ff6b6b;margin-top:4px;font-style:italic}
.hlf-numbers{display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;align-items:end}
.hlf-num-group{text-align:center}
.hlf-num-group label{display:block;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--gray-4);margin-bottom:10px}
.hlf-num-controls{display:flex;align-items:center;justify-content:center;gap:16px}
.hlf-num-btn{width:40px;height:40px;border:1px solid var(--gray-3);background:var(--gray-1);color:var(--white);font-size:1.3rem;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s;border-radius:var(--hlf-radius)}
.hlf-num-btn:hover{background:var(--accent);color:var(--black);border-color:var(--accent)}
.hlf-num-val{font-size:1.7rem;font-weight:900;min-width:36px;text-align:center;font-variant-numeric:tabular-nums}
.hlf-select{width:100%;padding:14px 36px 14px 14px;background:var(--gray-1);border:1px solid var(--gray-2);color:var(--white);font-size:.95rem;font-family:inherit;-webkit-appearance:none;appearance:none;background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 12px center;background-size:16px;cursor:pointer;border-radius:var(--hlf-radius)}
.hlf-select:focus{outline:none;border-color:var(--accent)}.hlf-select option{background:var(--black);color:var(--white)}
.hlf-checks{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.hlf-check{display:flex;align-items:center;gap:12px;padding:14px 16px;border:1px solid var(--gray-2);background:var(--gray-1);cursor:pointer;transition:all var(--hlf-transition)}
.hlf-check:hover{border-color:var(--gray-3)}.hlf-check.active{border-color:var(--accent);background:rgba(200,255,0,.06)}
.hlf-check input{display:none}
.hlf-check-box{width:20px;height:20px;border:1.5px solid var(--gray-3);border-radius:2px;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .2s}
.hlf-check.active .hlf-check-box{background:var(--accent);border-color:var(--accent)}
.hlf-check.active .hlf-check-box::after{content:'✓';color:var(--black);font-size:.75rem;font-weight:900}
.hlf-check-label{font-size:.85rem;font-weight:600}.hlf-check-extra{font-size:.72rem;color:var(--accent);font-weight:700}
.hlf-input{width:100%;padding:14px;background:var(--gray-1);border:1px solid var(--gray-2);color:var(--white);font-size:.95rem;font-family:inherit;transition:border-color .2s;border-radius:var(--hlf-radius)}
.hlf-input:focus{outline:none;border-color:var(--accent)}.hlf-input::placeholder{color:var(--gray-3)}
textarea.hlf-input{min-height:90px;resize:vertical}
.hlf-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.hlf-label{display:block;font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--gray-4);margin-bottom:8px}
.hlf-hint{font-size:.82rem;color:var(--gray-4);margin-top:14px;padding:12px 16px;background:var(--gray-1);border-left:2px solid var(--accent);line-height:1.5}
.hlf-disclaimer{font-size:.76rem;color:var(--gray-4);margin-top:16px;padding:16px;background:var(--gray-1);border:1px solid var(--gray-2);line-height:1.6}
.hlf-disclaimer strong{color:var(--gray-5)}
.hlf-privacy{display:flex;align-items:flex-start;gap:10px;font-size:.82rem;color:var(--gray-4);margin:20px 0}
.hlf-privacy input{width:18px;height:18px;margin-top:1px;accent-color:var(--accent);flex-shrink:0}
.hlf-submit{width:100%;padding:22px;background:var(--accent);color:var(--black);border:none;font-size:1rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;cursor:pointer;transition:all .3s;margin-top:16px;border-radius:var(--hlf-radius)}
.hlf-submit:hover{transform:translateY(-1px);box-shadow:0 6px 24px rgba(200,255,0,.25)}
.hlf-submit:disabled{opacity:.5;cursor:not-allowed;transform:none;box-shadow:none}
.hlf-sticky{position:fixed;bottom:0;left:0;right:0;z-index:998;background:rgba(8,8,10,.96);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-top:2px solid var(--accent);padding:14px 20px;display:flex;align-items:center;justify-content:space-between;transform:translateY(100%);transition:transform .4s cubic-bezier(.4,0,.2,1)}
.hlf-sticky.visible{transform:translateY(0)}
.hlf-sticky-left{display:flex;flex-direction:column;gap:2px}
.hlf-sticky-price{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;font-variant-numeric:tabular-nums}
.hlf-sticky-details{font-size:.72rem;color:var(--gray-4);line-height:1.4}
.hlf-sticky-saving{font-size:.65rem;color:var(--accent);font-weight:700;margin-top:2px}
.hlf-sticky-btn{padding:14px 28px;background:var(--accent);color:var(--black);font-size:.75rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;border:none;cursor:pointer;border-radius:var(--hlf-radius)}
/* Conditional step visibility */
.hlf-step--hidden{display:none}
@media(max-width:640px){
  .hlf-step-title{font-size:1.2rem}
  .hlf-cards{grid-template-columns:1fr 1fr}
  .hlf-cards--pay{grid-template-columns:1fr}
  .hlf-checks{grid-template-columns:1fr}
  .hlf-row{grid-template-columns:1fr}
  .hlf-numbers{grid-template-columns:1fr 1fr;gap:14px}
  .hlf-numbers .hlf-num-group:last-child{grid-column:1/-1}
  .hlf-sticky-details{display:none}
  .hlf-sticky-saving{display:none}
  .hlf-acc-head{padding:16px 16px}
  .hlf-fig-row{padding:16px}
}
@media(max-width:400px){.hlf-cards{grid-template-columns:1fr}}
</style>

<?php toa_component('page-hero', array(
    'breadcrumb' => __('PREVENTIVO STAFF EVENTI', $td),
    'title'      => __('Preventivo Live.', $td),
    'subtitle'   => '<strong>' . __('Costruisci il tuo preventivo in tempo reale.', $td) . '</strong><br>'
                   . __('Seleziona il personale, ricevi subito il prezzo e una selezione di profili.', $td)
                   . '<br><br><span style="color:var(--accent)">'
                   . __('TARIFFA INDICATIVA • CONFERMA ENTRO 24H', $td) . '</span>',
)); ?>

<div class="hlf-container"><form id="hlfForm" novalidate>

<!-- ═══ STEP 1: TIPO PERSONALE ═══ -->
<div class="hlf-step" id="hlf-step-figure">
  <div class="hlf-step-head">
    <div class="hlf-step-num">01</div>
    <div class="hlf-step-title"><?php _e('Tipo di personale', $td); ?></div>
  </div>
  <p class="hlf-step-sub"><?php _e('Scegli la categoria e seleziona la figura più adatta al tuo evento.', $td); ?></p>

  <!-- Accordion: Accoglienza & Fiera -->
  <div class="hlf-acc">
    <button type="button" class="hlf-acc-head" onclick="hlfAccToggle(this)">
      <i class="hlf-acc-icon">＋</i>
      <span class="hlf-acc-label"><?php _e('Accoglienza & Fiera', $td); ?></span>
      <span class="hlf-acc-hint"><?php _e('Hostess · Steward · Accrediti', $td); ?></span>
    </button>
    <div class="hlf-acc-body">
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'standard')" data-aesthetic="1">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Hostess / Steward', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Accoglienza, registrazione, assistenza', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €137/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'immagine')" data-aesthetic="1">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Hostess Immagine', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Presenza curata, immagine coordinata', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €169/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'accrediti')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Addetto Accrediti', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Segreteria organizzativa, check-in', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €147/6h', $td); ?></div>
      </div>
    </div>
  </div>

  <!-- Accordion: Promozione & Immagine -->
  <div class="hlf-acc">
    <button type="button" class="hlf-acc-head" onclick="hlfAccToggle(this)">
      <i class="hlf-acc-icon">＋</i>
      <span class="hlf-acc-label"><?php _e('Promozione & Immagine', $td); ?></span>
      <span class="hlf-acc-hint"><?php _e('Promoter · Modelli', $td); ?></span>
    </button>
    <div class="hlf-acc-body">
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'promoter')" data-aesthetic="1">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Promoter', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Distribuzione materiale, ingaggio pubblico', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €149/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'modello')" data-aesthetic="1">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Modello / Modella', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Professionista fashion, immagine premium', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €299/6h', $td); ?></div>
      </div>
    </div>
  </div>

  <!-- Accordion: Lingue & Coordinamento -->
  <div class="hlf-acc">
    <button type="button" class="hlf-acc-head" onclick="hlfAccToggle(this)">
      <i class="hlf-acc-icon">＋</i>
      <span class="hlf-acc-label"><?php _e('Lingue & Coordinamento', $td); ?></span>
      <span class="hlf-acc-hint"><?php _e('Interpreti · Coordinatori', $td); ?></span>
    </button>
    <div class="hlf-acc-body">
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'interprete')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Interprete Trattativa', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('2 lingue, trattativa commerciale', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €299/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'interprete_multi')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Interprete Multilingue', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('3+ lingue, accoglienza o trattativa', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €409/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'coordinatore')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Coordinatore Evento', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Supervisione staff, briefing, gestione', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €257/6h', $td); ?></div>
      </div>
    </div>
  </div>

  <!-- Accordion: Logistica & Supporto -->
  <div class="hlf-acc">
    <button type="button" class="hlf-acc-head" onclick="hlfAccToggle(this)">
      <i class="hlf-acc-icon">＋</i>
      <span class="hlf-acc-label"><?php _e('Logistica & Supporto', $td); ?></span>
      <span class="hlf-acc-hint"><?php _e('Driver · Tecnici · Runner', $td); ?></span>
    </button>
    <div class="hlf-acc-body">
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'driver')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Driver Berlina', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Autista con auto, 8h disponibilità', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €379/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'driver_van')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Driver Van', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Autista con van fino a 8 posti', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €468/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'tecnico')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Tecnico Allestimento', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Montaggio, smontaggio, logistica', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €189/6h', $td); ?></div>
      </div>
      <div class="hlf-fig-row" onclick="hlfSelectFigure(this,'runner')">
        <div class="hlf-fig-info">
          <div class="hlf-fig-title"><?php _e('Runner', $td); ?></div>
          <div class="hlf-fig-desc"><?php _e('Supporto operativo, consegne, facchino', $td); ?></div>
        </div>
        <div class="hlf-fig-price"><?php _e('da €119/6h', $td); ?></div>
      </div>
    </div>
  </div>

</div><!-- /hlf-step-figure -->

<!-- ═══ STEP 1B: LIVELLO PRESENZA (condizionale) ═══ -->
<div class="hlf-step hlf-step--hidden" id="hlf-step-presence">
  <div class="hlf-step-head">
    <div class="hlf-step-num" id="hlf-num-presence">02</div>
    <div class="hlf-step-title"><?php _e('Livello di presenza', $td); ?></div>
  </div>
  <div class="hlf-step-sub"><?php _e('Seleziona il livello estetico richiesto per il personale.', $td); ?></div>
  <div class="hlf-cards">
    <div class="hlf-card active" onclick="hlfSelectPresence(this,'standard')">
      <div class="hlf-card-title"><?php _e('Standard', $td); ?></div>
      <div class="hlf-card-desc"><?php _e('Nessun requisito estetico particolare', $td); ?></div>
      <div class="hlf-card-price"><?php _e('Incluso', $td); ?></div>
    </div>
    <div class="hlf-card" onclick="hlfSelectPresence(this,'good')">
      <div class="hlf-card-title"><?php _e('Buona presenza', $td); ?></div>
      <div class="hlf-card-desc"><?php _e('Aspetto curato, immagine professionale', $td); ?></div>
      <div class="hlf-card-price">+15%</div>
    </div>
    <div class="hlf-card" onclick="hlfSelectPresence(this,'high')">
      <div class="hlf-card-title"><?php _e('Alta presenza', $td); ?></div>
      <div class="hlf-card-desc"><?php _e('Look da modello/a, altezza 175+', $td); ?></div>
      <div class="hlf-card-price">+30%</div>
    </div>
  </div>
</div>

<!-- ═══ STEP 2: NAZIONE + CITTA' ═══ -->
<div class="hlf-step">
  <div class="hlf-step-head">
    <div class="hlf-step-num hlf-dyn-num">02</div>
    <div class="hlf-step-title"><?php _e("Paese e città dell'evento", $td); ?></div>
  </div>
  <div class="hlf-row">
    <div>
      <label class="hlf-label"><?php _e('Paese', $td); ?></label>
      <select class="hlf-select" id="hlf-country" onchange="hlfCountryChange()">
        <option value="">— <?php _e('Seleziona il paese', $td); ?> —</option>
        <option value="IT">🇮🇹 Italia</option>
        <option value="ES">🇪🇸 España</option>
        <option value="FR">🇫🇷 France</option>
        <option value="GB">🇬🇧 United Kingdom</option>
        <option value="DE">🇩🇪 Deutschland</option>
        <option value="CH">🇨🇭 Schweiz / Suisse</option>
        <option value="BE">🇧🇪 België / Belgique</option>
        <option value="AT">🇦🇹 Österreich</option>
        <option value="NL">🇳🇱 Nederland</option>
        <option value="PT">🇵🇹 Portugal</option>
        <option value="CZ">🇨🇿 Česko</option>
        <option value="HU">🇭🇺 Magyarország</option>
        <option value="PL">🇵🇱 Polska</option>
        <option value="OTHER"><?php _e('Altro paese', $td); ?></option>
      </select>
    </div>
    <div>
      <label class="hlf-label"><?php _e('Città', $td); ?></label>
      <select class="hlf-select" id="hlf-city" onchange="hlfCalc()" disabled>
        <option value="">— <?php _e('Prima seleziona il paese', $td); ?> —</option>
      </select>
    </div>
  </div>
  <div class="hlf-hint"><?php _e('Operiamo anche in città e paesi non presenti in elenco. Seleziona "Altro" e descrivici le tue esigenze nella sezione progetto.', $td); ?></div>
</div>

<!-- ═══ STEP 3: QUANTITA' E DURATA ═══ -->
<div class="hlf-step">
  <div class="hlf-step-head">
    <div class="hlf-step-num hlf-dyn-num">03</div>
    <div class="hlf-step-title"><?php _e('Quantità e durata', $td); ?></div>
  </div>
  <div class="hlf-numbers">
    <div class="hlf-num-group">
      <label><?php _e('Persone', $td); ?></label>
      <div class="hlf-num-controls">
        <button type="button" class="hlf-num-btn" onclick="hlfNum('people',-1)">−</button>
        <div class="hlf-num-val" id="hlf-people">1</div>
        <button type="button" class="hlf-num-btn" onclick="hlfNum('people',1)">+</button>
      </div>
    </div>
    <div class="hlf-num-group">
      <label><?php _e('Giorni', $td); ?></label>
      <div class="hlf-num-controls">
        <button type="button" class="hlf-num-btn" onclick="hlfNum('days',-1)">−</button>
        <div class="hlf-num-val" id="hlf-days">1</div>
        <button type="button" class="hlf-num-btn" onclick="hlfNum('days',1)">+</button>
      </div>
    </div>
    <div class="hlf-num-group">
      <label><?php _e('Ore / giorno', $td); ?></label>
      <select class="hlf-select" id="hlf-hours" onchange="hlfCalc()" style="max-width:140px;margin:0 auto">
        <option value="4">4h</option>
        <option value="6" selected>6h</option>
        <option value="8">8h</option>
        <option value="10">10h</option>
        <option value="12">12h</option>
      </select>
    </div>
  </div>
  <div class="hlf-hint"><?php _e('Sconti automatici per volumi: fino a -12% per persone, -10% per giorni multipli.', $td); ?></div>
</div>

<!-- ═══ STEP 4: PAGAMENTO ═══ -->
<div class="hlf-step">
  <div class="hlf-step-head">
    <div class="hlf-step-num hlf-dyn-num">04</div>
    <div class="hlf-step-title"><?php _e('Modalità di pagamento', $td); ?></div>
  </div>
  <div class="hlf-step-sub"><?php _e('Il prezzo mostrato è quello con pagamento anticipato — il migliore disponibile.', $td); ?></div>
  <div class="hlf-cards hlf-cards--pay">
    <div class="hlf-card active" onclick="hlfSelectPay(this,'advance')">
      <div class="hlf-card-tag"><?php _e('⭐ Miglior prezzo', $td); ?></div>
      <div class="hlf-card-title"><?php _e('100% Anticipato', $td); ?></div>
      <div class="hlf-card-mechanism"><?php _e("Pagamento completo prima dell'evento", $td); ?></div>
      <div class="hlf-card-price"><?php _e('Prezzo base', $td); ?></div>
    </div>
    <div class="hlf-card" onclick="hlfSelectPay(this,'split24')">
      <div class="hlf-card-tag"><?php _e('✅ Consigliato', $td); ?></div>
      <div class="hlf-card-title"><?php _e('50% + Saldo a fine evento', $td); ?></div>
      <div class="hlf-card-mechanism"><?php _e("50% anticipo, saldo entro 24h dalla fine dell'evento", $td); ?></div>
      <div class="hlf-card-price">+10%</div>
    </div>
    <div class="hlf-card" onclick="hlfSelectPay(this,'split30')">
      <div class="hlf-card-title"><?php _e('50% + Saldo a 30gg', $td); ?></div>
      <div class="hlf-card-mechanism"><?php _e('50% anticipo, saldo a 30 giorni fine evento', $td); ?></div>
      <div class="hlf-card-price">+20%</div>
    </div>
    <div class="hlf-card" onclick="hlfSelectPay(this,'post30')">
      <div class="hlf-card-title"><?php _e('Post-evento 30gg', $td); ?></div>
      <div class="hlf-card-mechanism"><?php _e('Nessun anticipo, pagamento a 30 giorni', $td); ?></div>
      <div class="hlf-card-price">+30%</div>
      <div class="hlf-card-warn"><?php _e('Soggetto ad approvazione', $td); ?></div>
    </div>
    <div class="hlf-card" onclick="hlfSelectPay(this,'post60')">
      <div class="hlf-card-title"><?php _e('Post-evento 60gg', $td); ?></div>
      <div class="hlf-card-mechanism"><?php _e('Nessun anticipo, pagamento a 60 giorni', $td); ?></div>
      <div class="hlf-card-price">+40%</div>
      <div class="hlf-card-warn"><?php _e('Soggetto ad approvazione', $td); ?></div>
    </div>
  </div>
  <div class="hlf-disclaimer">
    <?php _e('Le modalità senza anticipo (post-evento 30gg e 60gg) sono soggette a verifica e approvazione preventiva da parte di TOAgency. Ci riserviamo il diritto di richiedere condizioni di pagamento diverse o di non accettare la modalità selezionata.', $td); ?>
  </div>
</div>

<!-- ═══ STEP 5: SUPPLEMENTI ═══ -->
<div class="hlf-step">
  <div class="hlf-step-head">
    <div class="hlf-step-num hlf-dyn-num">05</div>
    <div class="hlf-step-title"><?php _e('Supplementi', $td); ?></div>
  </div>
  <div class="hlf-step-sub"><?php _e('Opzionali — seleziona solo se applicabili al tuo evento.', $td); ?></div>
  <div class="hlf-checks">
    <label class="hlf-check" onclick="hlfToggleCheck(this)">
      <input type="checkbox" name="sup_weekend" value="weekend">
      <div class="hlf-check-box"></div>
      <div><div class="hlf-check-label"><?php _e('Weekend / Festivo', $td); ?></div><div class="hlf-check-extra">+10%</div></div>
    </label>
    <label class="hlf-check" onclick="hlfToggleCheck(this)">
      <input type="checkbox" name="sup_night" value="night">
      <div class="hlf-check-box"></div>
      <div><div class="hlf-check-label"><?php _e('Serale (dopo 21:00)', $td); ?></div><div class="hlf-check-extra">+15%</div></div>
    </label>
    <label class="hlf-check" onclick="hlfToggleCheck(this)">
      <input type="checkbox" name="sup_urgent" value="urgent">
      <div class="hlf-check-box"></div>
      <div><div class="hlf-check-label"><?php _e('Urgenza (<72h)', $td); ?></div><div class="hlf-check-extra">+15%</div></div>
    </label>
    <label class="hlf-check" onclick="hlfToggleCheck(this)">
      <input type="checkbox" name="sup_dress" value="dress">
      <div class="hlf-check-box"></div>
      <div><div class="hlf-check-label"><?php _e('Divisa specifica', $td); ?></div><div class="hlf-check-extra">+5%</div></div>
    </label>
  </div>
</div>

<!-- ═══ STEP 6: DATE ═══ -->
<div class="hlf-step">
  <div class="hlf-step-head">
    <div class="hlf-step-num hlf-dyn-num">06</div>
    <div class="hlf-step-title"><?php _e("Periodo dell'evento", $td); ?></div>
  </div>
  <div class="hlf-row">
    <div>
      <label class="hlf-label"><?php _e('Data inizio', $td); ?></label>
      <input type="date" class="hlf-input" id="hlf-date-start">
    </div>
    <div>
      <label class="hlf-label"><?php _e('Data fine (opzionale)', $td); ?></label>
      <input type="date" class="hlf-input" id="hlf-date-end">
    </div>
  </div>
</div>

<!-- ═══ STEP 7: DESCRIZIONE ═══ -->
<div class="hlf-step">
  <div class="hlf-step-head">
    <div class="hlf-step-num hlf-dyn-num">07</div>
    <div class="hlf-step-title"><?php _e('Descrizione progetto', $td); ?></div>
  </div>
  <div class="hlf-step-sub"><?php _e('Opzionale — tipo di evento, dress code, compiti specifici.', $td); ?></div>
  <textarea class="hlf-input" id="hlf-description" placeholder="<?php esc_attr_e('Descrivi il tuo evento, esigenze particolari, dress code...', $td); ?>"></textarea>
</div>

<!-- ═══ STEP 8: DATI + INVIO ═══ -->
<div class="hlf-step">
  <div class="hlf-step-head">
    <div class="hlf-step-num hlf-dyn-num">08</div>
    <div class="hlf-step-title"><?php _e('I tuoi dati', $td); ?></div>
  </div>
  <div class="hlf-row">
    <div>
      <label class="hlf-label"><?php _e('Nome *', $td); ?></label>
      <input type="text" class="hlf-input" id="hlf-name" required placeholder="<?php esc_attr_e('Il tuo nome', $td); ?>">
    </div>
    <div>
      <label class="hlf-label"><?php _e('Azienda *', $td); ?></label>
      <input type="text" class="hlf-input" id="hlf-company" required placeholder="<?php esc_attr_e('Nome azienda', $td); ?>">
    </div>
  </div>
  <div class="hlf-row">
    <div>
      <label class="hlf-label"><?php _e('Email *', $td); ?></label>
      <input type="email" class="hlf-input" id="hlf-email" required placeholder="email@azienda.it">
    </div>
    <div>
      <label class="hlf-label"><?php _e('Telefono *', $td); ?></label>
      <input type="tel" class="hlf-input" id="hlf-phone" required placeholder="+39...">
    </div>
  </div>

  <!-- Servizi aggiuntivi -->
  <div class="hlf-hint" style="margin-bottom:16px">
    <?php _e('Offriamo anche: security e controllo accessi, catering staff, tecnici audio/luci, social media manager on-site, tour leader, brand ambassador e altri profili specializzati. Per questi servizi, descrivili nella sezione progetto o contattaci direttamente.', $td); ?>
  </div>

  <label class="hlf-privacy">
    <input type="checkbox" id="hlf-consent" required>
    <span><?php printf(__('Accetto il trattamento dei dati secondo la %sprivacy policy%s', $td), '<a href="https://www.iubenda.com/privacy-policy/58462877" target="_blank" style="color:var(--accent);text-decoration:underline">', '</a>'); ?></span>
  </label>
  <button type="submit" class="hlf-submit" id="hlfSubmitBtn"><?php _e('INVIA RICHIESTA PREVENTIVO', $td); ?></button>

  <div class="hlf-disclaimer" style="margin-top:16px">
    <strong><?php _e('Nota sui prezzi', $td); ?></strong><br>
    <?php _e('I prezzi indicati sono stime indicative basate sulle nostre tariffe standard con cui generalmente riusciamo a fornire personale qualificato. Il prezzo finale può variare in base alla disponibilità, al profilo specifico richiesto e alle condizioni operative. Su richiesta, possiamo inviare una selezione di profili disponibili, ciascuno con la propria tariffa individuale, per permetterti di scegliere in base alle tue esigenze e al tuo budget. Fattori come urgenze, festività, orari serali e requisiti particolari possono influire sulla quotazione definitiva. La conferma del prezzo avviene entro 24 ore dal ricevimento della richiesta.', $td); ?>
  </div>
</div>

</form></div>

<!-- ═══ STICKY BAR ═══ -->
<div class="hlf-sticky" id="hlfSticky">
  <div class="hlf-sticky-left">
    <div class="hlf-sticky-price">€<span id="hlfStickyPrice">0</span></div>
    <div class="hlf-sticky-details" id="hlfStickyDetails"></div>
    <div class="hlf-sticky-saving" id="hlfStickySaving"></div>
  </div>
  <button type="button" class="hlf-sticky-btn" onclick="document.getElementById('hlfSubmitBtn').scrollIntoView({behavior:'smooth'})"><?php _e('INVIA', $td); ?></button>
</div>

<!-- ═══ PRICING ENGINE ═══ -->
<script>
(function(){
  'use strict';

  var PRICES={standard:160,immagine:200,accrediti:170,promoter:180,modello:350,interprete:350,interprete_multi:480,coordinatore:300,driver:450,driver_van:550,tecnico:220,runner:140};
  var LABELS={standard:'Hostess/Steward',immagine:'Hostess Immagine',accrediti:'Addetto Accrediti',promoter:'Promoter',modello:'Modello/a',interprete:'Interprete',interprete_multi:'Interprete Multi',coordinatore:'Coordinatore',driver:'Driver Berlina',driver_van:'Driver Van',tecnico:'Tecnico',runner:'Runner'};
  var HOURS_MULT={'4':0.70,'6':0.85,'8':1.00,'10':1.25,'12':1.50};
  var PAY_MULT={advance:1.00,split24:1.10,split30:1.20,post30:1.30,post60:1.40};
  var SUP_MULT={weekend:0.10,night:0.15,urgent:0.15,dress:0.05};
  var PRESENCE_MULT={standard:1.00,good:1.15,high:1.30};
  /* Figures where aesthetic/presence step is relevant */
  var AESTHETIC_FIGURES={standard:1,immagine:1,promoter:1,modello:1};

  var state={figure:null,payment:'advance',presence:'standard',supplements:[]};

  /* ── City data by country with multipliers ── */
  var CITIES={
    IT:[
      {v:'milano',l:'Milano',m:1.15},{v:'roma',l:'Roma',m:1.05},{v:'bologna',l:'Bologna',m:1.05},{v:'firenze',l:'Firenze',m:1.05},
      {v:'torino',l:'Torino',m:1.00},{v:'verona',l:'Verona',m:1.00},{v:'rimini',l:'Rimini',m:1.00},{v:'genova',l:'Genova',m:1.00},
      {v:'padova',l:'Padova',m:1.00},{v:'venezia',l:'Venezia',m:1.00},{v:'brescia',l:'Brescia',m:1.00},{v:'bergamo',l:'Bergamo',m:1.00},
      {v:'vicenza',l:'Vicenza',m:1.00},{v:'parma',l:'Parma',m:1.00},
      {v:'napoli',l:'Napoli',m:0.95},{v:'bari',l:'Bari',m:0.95},{v:'catania',l:'Catania',m:0.95},{v:'palermo',l:'Palermo',m:0.95},{v:'cagliari',l:'Cagliari',m:0.95},
      {v:'pescara',l:'Pescara',m:1.10},{v:'ancona',l:'Ancona',m:1.10},{v:'perugia',l:'Perugia',m:1.10},{v:'lecce',l:'Lecce',m:1.10},
      {v:'salerno',l:'Salerno',m:1.10},{v:'sassari',l:'Sassari',m:1.10},{v:'cosenza',l:'Cosenza',m:1.10},{v:'trieste',l:'Trieste',m:1.10},{v:'trento',l:'Trento',m:1.10}
    ],
    ES:[
      {v:'madrid',l:'Madrid',m:1.15},{v:'barcelona',l:'Barcelona',m:1.15},
      {v:'valencia',l:'Valencia',m:1.05},{v:'sevilla',l:'Sevilla',m:1.05},{v:'bilbao',l:'Bilbao',m:1.05},
      {v:'malaga',l:'Málaga',m:1.05},{v:'zaragoza',l:'Zaragoza',m:1.05},{v:'alicante',l:'Alicante',m:1.05}
    ],
    FR:[
      {v:'paris',l:'Paris',m:1.25},{v:'marseille',l:'Marseille',m:1.10},{v:'lyon',l:'Lyon',m:1.10},
      {v:'toulouse',l:'Toulouse',m:1.10},{v:'nice',l:'Nice',m:1.10},{v:'nantes',l:'Nantes',m:1.10},
      {v:'strasbourg',l:'Strasbourg',m:1.10},{v:'bordeaux',l:'Bordeaux',m:1.10},
      {v:'montecarlo',l:'Monte-Carlo / Monaco',m:1.30},{v:'cannes',l:'Cannes',m:1.20}
    ],
    GB:[
      {v:'london',l:'London',m:1.30},{v:'manchester',l:'Manchester',m:1.15},{v:'birmingham',l:'Birmingham',m:1.10},
      {v:'glasgow',l:'Glasgow',m:1.10},{v:'liverpool',l:'Liverpool',m:1.10},{v:'edinburgh',l:'Edinburgh',m:1.15},
      {v:'leeds',l:'Leeds',m:1.10},{v:'bristol',l:'Bristol',m:1.10}
    ],
    DE:[
      {v:'berlin',l:'Berlin',m:1.15},{v:'munich',l:'München',m:1.20},{v:'hamburg',l:'Hamburg',m:1.15},
      {v:'frankfurt',l:'Frankfurt',m:1.20},{v:'koln',l:'Köln',m:1.10},{v:'stuttgart',l:'Stuttgart',m:1.10},
      {v:'dusseldorf',l:'Düsseldorf',m:1.15},{v:'hannover',l:'Hannover',m:1.10},{v:'nurnberg',l:'Nürnberg',m:1.10}
    ],
    CH:[
      {v:'zurich',l:'Zürich',m:1.30},{v:'geneve',l:'Genève',m:1.30},{v:'basel',l:'Basel',m:1.25},
      {v:'lausanne',l:'Lausanne',m:1.25},{v:'lugano',l:'Lugano',m:1.20}
    ],
    BE:[
      {v:'bruxelles',l:'Bruxelles',m:1.15},{v:'antwerpen',l:'Antwerpen',m:1.10},
      {v:'gent',l:'Gent',m:1.10},{v:'brugge',l:'Brugge',m:1.10}
    ],
    AT:[{v:'vienna',l:'Wien',m:1.20}],
    NL:[{v:'amsterdam',l:'Amsterdam',m:1.20}],
    PT:[{v:'lisboa',l:'Lisboa',m:1.10},{v:'porto',l:'Porto',m:1.10}],
    CZ:[{v:'praha',l:'Praha',m:1.05}],
    HU:[{v:'budapest',l:'Budapest',m:1.05}],
    PL:[{v:'warszawa',l:'Warszawa',m:1.05},{v:'krakow',l:'Kraków',m:1.05}]
  };

  /* Country change → populate city dropdown */
  window.hlfCountryChange=function(){
    var cSel=document.getElementById('hlf-country');
    var citySel=document.getElementById('hlf-city');
    if(!cSel||!citySel)return;
    var country=cSel.value;
    citySel.innerHTML='';
    if(!country){
      citySel.innerHTML='<option value="">— <?php echo esc_js(__('Prima seleziona il paese', $td)); ?> —</option>';
      citySel.disabled=true;calc();return;
    }
    if(country==='OTHER'){
      citySel.innerHTML='<option value="altra" data-mult="0"><?php echo esc_js(__('Altra città (contattaci)', $td)); ?></option>';
      citySel.disabled=false;calc();return;
    }
    var cities=CITIES[country]||[];
    var html='<option value="">— <?php echo esc_js(__('Seleziona la città', $td)); ?> —</option>';
    cities.forEach(function(c){html+='<option value="'+c.v+'" data-mult="'+c.m+'">'+c.l+'</option>';});
    html+='<option value="altra" data-mult="0"><?php echo esc_js(__('Altra città (contattaci)', $td)); ?></option>';
    citySel.innerHTML=html;
    citySel.disabled=false;
    calc();
  };

  function gV(id){return(document.getElementById(id)||{}).value||'';}
  function gI(id){return parseInt((document.getElementById(id)||{}).textContent)||1;}
  function dP(n){return n>=11?0.88:n>=6?0.92:n>=3?0.95:1;}
  function dD(n){return n>=5?0.90:n>=3?0.95:1;}
  function cM(){var s=document.getElementById('hlf-city');if(!s||!s.value||s.value==='altra')return 0;return parseFloat(s.options[s.selectedIndex].getAttribute('data-mult'))||1;}

  /* Show/hide presence step + renumber */
  function updatePresenceVisibility(){
    var presStep=document.getElementById('hlf-step-presence');
    var show=!!(state.figure && AESTHETIC_FIGURES[state.figure]);
    if(presStep){
      presStep.classList.toggle('hlf-step--hidden',!show);
    }
    if(!show){state.presence='standard';}
    /* Renumber dynamic steps */
    var nums=document.querySelectorAll('.hlf-dyn-num');
    var offset=show?1:0;
    nums.forEach(function(el,i){el.textContent=String(i+2+offset).padStart(2,'0');});
  }

  function calc(){
    var base=PRICES[state.figure]||0;
    var people=gI('hlf-people'),days=gI('hlf-days');
    var hours=gV('hlf-hours')||'6';
    var hM=HOURS_MULT[hours]||1,cityM=cM();
    var pM=PAY_MULT[state.payment]||1;
    var presM=PRESENCE_MULT[state.presence]||1;
    var pD=dP(people),dDi=dD(days);
    var supA=0;state.supplements.forEach(function(s){supA+=(SUP_MULT[s]||0);});var supM=1+supA;
    var ok=base>0&&cityM>0;
    var total=ok?Math.round(base*hM*people*days*cityM*pD*dDi*presM*supM*pM):0;
    var totalAdv=ok?Math.round(base*hM*people*days*cityM*pD*dDi*presM*supM*PAY_MULT.advance):0;
    var totalMax=ok?Math.round(base*hM*people*days*cityM*pD*dDi*presM*supM*PAY_MULT.post60):0;

    var priceEl=document.getElementById('hlfStickyPrice');
    var detEl=document.getElementById('hlfStickyDetails');
    var savEl=document.getElementById('hlfStickySaving');
    var stEl=document.getElementById('hlfSticky');

    if(priceEl)priceEl.textContent=total>0?total.toLocaleString('it-IT'):'—';
    if(detEl){
      if(!state.figure)detEl.textContent='<?php echo esc_js(__('Seleziona il personale', $td)); ?>';
      else if(cityM===0)detEl.textContent=LABELS[state.figure]+' — <?php echo esc_js(__('seleziona la città', $td)); ?>';
      else detEl.textContent=LABELS[state.figure]+' · '+people+' pers. × '+days+' gg × '+hours+'h';
    }
    if(savEl){
      if(state.payment==='advance'&&totalMax>0){
        var s=totalMax-total;
        savEl.textContent=s>0?'<?php echo esc_js(__('Risparmi', $td)); ?> €'+s.toLocaleString('it-IT')+' <?php echo esc_js(__('rispetto al pagamento a 60gg', $td)); ?>':'';
      }else if(state.payment!=='advance'&&totalAdv>0){
        var d=total-totalAdv;
        savEl.textContent=d>0?'<?php echo esc_js(__('Risparmi', $td)); ?> €'+d.toLocaleString('it-IT')+' <?php echo esc_js(__('con pagamento anticipato', $td)); ?>':'';
      }else savEl.textContent='';
    }
    if(stEl&&state.figure)stEl.classList.add('visible');
  }

  /* Accordion — apre una sola categoria alla volta */
  window.hlfAccToggle=function(btn){
    var acc=btn.closest('.hlf-acc');
    var isOpen=acc.classList.contains('open');
    document.querySelectorAll('.hlf-acc.open').forEach(function(a){a.classList.remove('open');});
    if(!isOpen)acc.classList.add('open');
  };

  window.hlfSelectFigure=function(el,fig){
    /* deselect any previously active row */
    document.querySelectorAll('#hlf-step-figure .hlf-fig-row').forEach(function(r){r.classList.remove('active');});
    el.classList.add('active');
    state.figure=fig;
    updatePresenceVisibility();
    calc();
  };
  window.hlfSelectPresence=function(el,level){
    el.closest('.hlf-cards').querySelectorAll('.hlf-card').forEach(function(c){c.classList.remove('active');});
    el.classList.add('active');state.presence=level;calc();
  };
  window.hlfSelectPay=function(el,mode){
    el.closest('.hlf-cards').querySelectorAll('.hlf-card').forEach(function(c){c.classList.remove('active');});
    el.classList.add('active');state.payment=mode;calc();
  };
  window.hlfToggleCheck=function(el){
    var cb=el.querySelector('input[type=checkbox]');if(!cb)return;
    setTimeout(function(){
      el.classList.toggle('active',cb.checked);
      state.supplements=[];
      document.querySelectorAll('.hlf-check input:checked').forEach(function(c){state.supplements.push(c.value);});
      calc();
    },10);
  };
  window.hlfNum=function(field,delta){
    var id=field==='people'?'hlf-people':'hlf-days';
    var el=document.getElementById(id);if(!el)return;
    var v=parseInt(el.textContent)||1;v=Math.max(1,v+delta);
    if(field==='people')v=Math.min(v,50);if(field==='days')v=Math.min(v,30);
    el.textContent=v;calc();
  };
  window.hlfCalc=calc;
  document.addEventListener('DOMContentLoaded',function(){updatePresenceVisibility();calc();});
})();
</script>

<!-- ═══ CRM SUBMIT + JOB CREATION ═══ -->
<script>
(function(){
  'use strict';
  var CRM_LEAD='/crm_toagency/actions/lead-from-website.php';
  var CRM_JOB='/crm_toagency/actions/nuovo-lavoro.php';
  var CRM_AUTH='user=Marco&token=Marco754';
  var TNX=window.location.origin+'/tnx/';
  var TIMEOUT=10000;

  function goTnx(codice){
    var url=TNX;
    if(codice)url+=(url.indexOf('?')>-1?'&':'?')+'ref='+encodeURIComponent(codice);
    window.location.href=url;
  }

  /* Collect all form data */
  function payload(){
    var d={};
    /* figura: cerca la riga attiva nell'accordion */
    var ac=document.querySelector('#hlf-step-figure .hlf-fig-row.active');
    d.figure=ac?ac.querySelector('.hlf-fig-title').textContent.trim():'';
    d.figure_key='';
    if(ac){var oc=ac.getAttribute('onclick')||'';var m=oc.match(/'([^']+)'\)$/);if(m)d.figure_key=m[1];}
    d.city=(document.getElementById('hlf-city')||{}).value||'';
    d.city_label='';
    var cityEl=document.getElementById('hlf-city');
    if(cityEl&&cityEl.selectedIndex>0)d.city_label=cityEl.options[cityEl.selectedIndex].text;
    var pE=document.getElementById('hlf-people');d.people=pE?(parseInt(pE.textContent)||1):1;
    var dE=document.getElementById('hlf-days');d.days=dE?(parseInt(dE.textContent)||1):1;
    d.hours=(document.getElementById('hlf-hours')||{}).value||'6';
    /* Presence */
    var presCard=document.querySelector('#hlf-step-presence .hlf-card.active');
    d.presence=presCard?presCard.querySelector('.hlf-card-title').textContent.trim():'Standard';
    /* Payment */
    var payC=document.querySelector('.hlf-cards--pay .hlf-card.active');
    d.payment=payC?payC.querySelector('.hlf-card-title').textContent.trim():'';
    /* Supplements */
    d.supplements=[];
    document.querySelectorAll('.hlf-check input:checked').forEach(function(cb){d.supplements.push(cb.value);});
    d.supplements=d.supplements.join(', ');
    /* Dates */
    d.date_start=(document.getElementById('hlf-date-start')||{}).value||'';
    d.date_end=(document.getElementById('hlf-date-end')||{}).value||'';
    /* Description */
    d.description=(document.getElementById('hlf-description')||{}).value||'';
    /* Contact */
    d.name=(document.getElementById('hlf-name')||{}).value||'';
    d.company=(document.getElementById('hlf-company')||{}).value||'';
    d.email=(document.getElementById('hlf-email')||{}).value||'';
    d.phone=(document.getElementById('hlf-phone')||{}).value||'';
    /* Price */
    var pr=document.getElementById('hlfStickyPrice');
    d.total_price=pr?pr.textContent.replace(/\./g,''):'0';
    /* Meta */
    d.form_type='staff-eventi-live';
    d.page_url=window.location.href;
    d.timestamp=new Date().toISOString();
    return d;
  }

  /* Build description string for CRM job */
  function buildJobDesc(d){
    var parts=[];
    parts.push('Figura: '+d.figure);
    if(d.presence&&d.presence!=='Standard')parts.push('Presenza: '+d.presence);
    parts.push('Città: '+(d.city_label||d.city));
    parts.push('Persone: '+d.people+' | Giorni: '+d.days+' | Ore/gg: '+d.hours+'h');
    if(d.supplements)parts.push('Supplementi: '+d.supplements);
    parts.push('Pagamento: '+d.payment);
    parts.push('Preventivo: €'+d.total_price);
    if(d.description)parts.push('Note cliente: '+d.description);
    parts.push('Contatto: '+d.name+' — '+d.email+' — '+d.phone);
    return parts.join('\n');
  }

  /* Create job in CRM (fire & forget) */
  function createCrmJob(d){
    var jobUrl=CRM_JOB+'?'+CRM_AUTH;

    /* Step 1: genera codice */
    var fd1=new URLSearchParams();
    fd1.append('action','genera_codice');
    fd1.append('nome_cliente',d.company||d.name);
    fd1.append('luogo',d.city_label||d.city);

    fetch(jobUrl,{method:'POST',body:fd1})
    .then(function(r){return r.json();})
    .then(function(res){
      if(!res.success||!res.codice)return null;
      var codice=res.codice;

      /* Step 2: salva lavoro */
      var fd2=new URLSearchParams();
      fd2.append('action','salva_lavoro');
      fd2.append('codice_lavoro',codice);
      fd2.append('nome_cliente',d.company||d.name);
      fd2.append('nome_referente',d.name);
      fd2.append('email_contatto',d.email);
      fd2.append('telefono_contatto',d.phone);
      fd2.append('descrizione',buildJobDesc(d));
      fd2.append('data_lavoro',d.date_start);
      fd2.append('budget_fisso',d.total_price);
      fd2.append('assegnato_a','Marco');

      return fetch(jobUrl,{method:'POST',body:fd2})
        .then(function(r){return r.json();})
        .then(function(res2){
          return(res2.success)?codice:null;
        });
    })
    .then(function(codice){
      /* Store code for tnx page */
      if(codice){
        try{sessionStorage.setItem('toa_job_code',codice);}catch(e){}
      }
      goTnx(codice);
    })
    .catch(function(){
      goTnx(null);
    });
  }

  var form=document.getElementById('hlfForm');
  if(form){
    form.addEventListener('submit',function(e){
      e.preventDefault();
      var consent=document.getElementById('hlf-consent');
      if(consent&&!consent.checked){alert('<?php echo esc_js(__('Per favore accetta la privacy policy per continuare.', $td)); ?>');return;}
      var btn=form.querySelector('button[type=submit]');
      if(btn)btn.disabled=true;
      var d=payload();
      try{sessionStorage.setItem('toa_lead',JSON.stringify(d));}catch(ex){}

      /* Safety timeout — if everything hangs, go to tnx anyway */
      var safetyTimer=setTimeout(function(){goTnx(null);},TIMEOUT);

      /* Step 1: Save lead */
      fetch(CRM_LEAD,{
        method:'POST',
        headers:{'Content-Type':'application/json'},
        body:JSON.stringify(d)
      })
      .then(function(r){return r.json();})
      .then(function(){
        clearTimeout(safetyTimer);
        /* Step 2: Create CRM job (fire & forget with redirect) */
        createCrmJob(d);
      })
      .catch(function(){
        clearTimeout(safetyTimer);
        /* Lead failed but still try to create job */
        createCrmJob(d);
      });
    });
  }
})();
</script>

<?php toa_component('footer'); ?>
