<?php
/**
 * Template Name: Area Agenzia
 * Area riservata self-service delle agenzie partner — CRM-AGENZIA-PORTALE-SELFEDIT
 * Accesso via sessione dedicata TOAGPARTNER, aperta da
 * crm_toagency/actions/agenzia-portale-login.php (redirect qui SENZA token in URL).
 * Questa pagina non vede mai uuid/token: legge solo dagli endpoint via cookie di sessione.
 * Solo italiano (area privata, non è una pagina pubblica multilingua). — 24/08/2026
 */
toa_component('header');
?>
<style>
:root{--agp-max:960px}
.agp-wrap{max-width:var(--agp-max);margin:0 auto;padding:40px 16px 120px;min-height:60vh}
.agp-center{display:flex;align-items:center;justify-content:center;min-height:50vh;text-align:center}
.agp-spinner{width:32px;height:32px;border:3px solid var(--gray-3);border-top-color:var(--accent);border-radius:50%;animation:agpspin .8s linear infinite;margin:0 auto 16px}
@keyframes agpspin{to{transform:rotate(360deg)}}
.agp-error-box{max-width:480px;margin:0 auto;padding:32px 24px;border:1px solid var(--gray-2);background:var(--gray-1);text-align:center}
.agp-error-box h2{font-family:var(--font-display);font-size:1.3rem;margin-bottom:12px}
.agp-error-box p{font-size:.9rem;color:var(--gray-4);line-height:1.6}
.agp-error-box a{color:var(--accent)}

.agp-head{display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:16px;padding-bottom:24px;margin-bottom:24px;border-bottom:1px solid var(--gray-2)}
.agp-head h1{font-family:var(--font-display);font-size:1.8rem;font-weight:700}
.agp-head-meta{font-size:.78rem;color:var(--gray-4);margin-top:6px;line-height:1.6}
.agp-quotas{display:flex;gap:20px;flex-wrap:wrap}
.agp-quota{text-align:center;padding:10px 16px;border:1px solid var(--gray-2);background:var(--gray-1);min-width:100px}
.agp-quota b{display:block;font-size:1.1rem;font-family:var(--font-display)}
.agp-quota span{font-size:.65rem;text-transform:uppercase;letter-spacing:.5px;color:var(--gray-4)}

.agp-banner{padding:14px 18px;border-left:3px solid var(--accent);background:var(--gray-1);font-size:.85rem;color:var(--gray-4);margin-bottom:32px;line-height:1.6}

.agp-section{margin-bottom:40px}
.agp-section h2{font-family:var(--font-display);font-size:1.2rem;margin-bottom:4px}
.agp-section-sub{font-size:.8rem;color:var(--gray-4);margin-bottom:18px}

.agp-card{border:1px solid var(--gray-2);background:var(--gray-1);padding:24px;margin-bottom:20px}
.agp-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px}
.agp-row3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px}
.agp-label{display:block;font-size:.68rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--gray-4);margin-bottom:6px}
.agp-input,.agp-select,textarea.agp-input{width:100%;padding:11px 12px;background:var(--black);border:1px solid var(--gray-3);color:var(--white);font-size:.9rem;font-family:inherit;border-radius:2px}
.agp-input:focus,.agp-select:focus{outline:none;border-color:var(--accent)}
.agp-select{-webkit-appearance:none;appearance:none;background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 10px center;background-size:14px;padding-right:30px}
.agp-select option{background:var(--black)}
.agp-checks{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:6px}
.agp-check{display:inline-flex;align-items:center;gap:6px;padding:7px 12px;border:1px solid var(--gray-3);font-size:.75rem;cursor:pointer;text-transform:uppercase;letter-spacing:.3px}
.agp-check input{accent-color:var(--accent)}
.agp-check.active{border-color:var(--accent);background:rgba(200,255,0,.06)}

.agp-referente{display:grid;grid-template-columns:1fr 1fr 1fr 1fr auto;gap:8px;margin-bottom:8px;align-items:center}
.agp-referente input{padding:9px 10px}
.agp-btn-icon{width:34px;height:34px;border:1px solid var(--gray-3);background:transparent;color:var(--gray-4);cursor:pointer;font-size:1rem;flex-shrink:0}
.agp-btn-icon:hover{border-color:#ff6b6b;color:#ff6b6b}

.agp-btn{padding:13px 26px;background:var(--accent);color:var(--black);border:none;font-size:.8rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;cursor:pointer}
.agp-btn:disabled{opacity:.5;cursor:not-allowed}
.agp-btn-secondary{background:transparent;border:1px solid var(--gray-3);color:var(--white)}
.agp-btn-secondary:hover{border-color:var(--accent);color:var(--accent)}

.agp-msg{font-size:.8rem;padding:10px 14px;margin-top:12px;display:none}
.agp-msg.ok{display:block;border-left:3px solid #6bff8f;color:#6bff8f;background:rgba(107,255,143,.06)}
.agp-msg.err{display:block;border-left:3px solid #ff6b6b;color:#ff6b6b;background:rgba(255,107,107,.06)}

.agp-talents{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px}
.agp-tcard{border:1px solid var(--gray-2);background:var(--gray-1);overflow:hidden}
.agp-tcard-photo{aspect-ratio:3/4;background:var(--black) center/cover no-repeat;display:flex;align-items:center;justify-content:center;color:var(--gray-4);font-size:.7rem}
.agp-tcard-body{padding:12px 14px}
.agp-tcard-name{font-weight:700;font-size:.95rem;margin-bottom:6px}
.agp-badges{display:flex;flex-wrap:wrap;gap:5px;margin-bottom:8px}
.agp-badge{font-size:.6rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;padding:3px 7px;border:1px solid var(--gray-3);color:var(--gray-4)}
.agp-badge.warn{border-color:#ffb84d;color:#ffb84d}
.agp-badge.excl{border-color:var(--accent);color:var(--accent)}
.agp-tcard-foto{font-size:.7rem;color:var(--gray-4);margin-bottom:10px}
.agp-tcard-actions{display:flex;gap:8px}
.agp-tcard-actions button{flex:1;padding:8px;font-size:.68rem}

.agp-modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:999;align-items:flex-start;justify-content:center;overflow-y:auto;padding:40px 16px}
.agp-modal-bg.open{display:flex}
.agp-modal{background:var(--black);border:1px solid var(--gray-2);max-width:640px;width:100%;padding:28px}
.agp-modal-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}
.agp-modal-head h3{font-family:var(--font-display);font-size:1.15rem}
.agp-modal-close{background:none;border:none;color:var(--gray-4);font-size:1.4rem;cursor:pointer;line-height:1}

@media(max-width:768px){
  .agp-row,.agp-row3{grid-template-columns:1fr}
  .agp-head{flex-direction:column}
  .agp-referente{grid-template-columns:1fr 1fr}
}
@media(max-width:480px){
  .agp-talents{grid-template-columns:repeat(auto-fill,minmax(150px,1fr))}
  .agp-modal{padding:18px}
}
</style>

<div class="agp-wrap" id="agpWrap">
  <div class="agp-center" id="agpLoading"><div><div class="agp-spinner"></div>Caricamento area agenzia…</div></div>

  <div id="agpErrorBox" style="display:none" class="agp-center">
    <div class="agp-error-box">
      <h2>Accesso non disponibile</h2>
      <p id="agpErrorText">Il collegamento non è (più) valido.</p>
      <p style="margin-top:16px">Riapri il link ricevuto via email, oppure scrivi a <a href="mailto:info@toagency.it">info@toagency.it</a>.</p>
    </div>
  </div>

  <div id="agpContent" style="display:none">

    <div class="agp-head">
      <div>
        <h1 id="agpNome">—</h1>
        <div class="agp-head-meta">Accesso attivo fino al <b id="agpScadenza">—</b></div>
      </div>
      <div class="agp-quotas">
        <div class="agp-quota"><b id="agpQuotaTalent">—</b><span>Talent oggi</span></div>
        <div class="agp-quota"><b id="agpQuotaUpload">—</b><span>Foto ultima ora</span></div>
      </div>
    </div>

    <div class="agp-banner">ⓘ Tutto quello che carichi o modifichi qui passa prima dall'approvazione dello staff TOAgency: non compare online subito.</div>

    <div class="agp-section">
      <h2>I tuoi dati</h2>
      <div class="agp-section-sub">Le modifiche vengono proposte allo staff, non applicate subito.</div>
      <div class="agp-card">
        <div id="agpPendingBox" style="display:none;margin-bottom:16px;padding:12px;border:1px solid #ffb84d;font-size:.78rem;color:#ffb84d">Hai dati o modifiche in attesa di revisione dello staff.</div>
        <form id="agpAgenziaForm">
          <div class="agp-row">
            <div><label class="agp-label">Ragione sociale</label><input class="agp-input" id="agp_ragione_sociale"></div>
            <div><label class="agp-label">Città</label><input class="agp-input" id="agp_citta"></div>
          </div>
          <div class="agp-row">
            <div><label class="agp-label">Email</label><input class="agp-input" type="email" id="agp_email"></div>
            <div><label class="agp-label">Telefono</label><input class="agp-input" type="tel" id="agp_telefono"></div>
          </div>
          <div class="agp-row">
            <div><label class="agp-label">Sito web</label><input class="agp-input" id="agp_sito_web" placeholder="https://…"></div>
            <div><label class="agp-label">Link al book</label><input class="agp-input" id="agp_book_url" placeholder="https://…"></div>
          </div>
          <label class="agp-label" style="margin-top:10px;display:block">Referenti</label>
          <div id="agpReferenti"></div>
          <button type="button" class="agp-btn agp-btn-secondary" id="agpAddReferente" style="margin-top:4px">+ Aggiungi referente</button>
          <div style="margin-top:18px"><button type="submit" class="agp-btn" id="agpAgenziaSubmit">Salva modifiche</button></div>
          <div class="agp-msg" id="agpAgenziaMsg"></div>
        </form>
      </div>
    </div>

    <div class="agp-section">
      <h2>I tuoi talent</h2>
      <div class="agp-section-sub">Carica un nuovo talent o aggiorna uno esistente.</div>
      <button type="button" class="agp-btn" id="agpNewTalentBtn" style="margin-bottom:20px">+ Nuovo talent</button>
      <div class="agp-talents" id="agpTalentGrid"></div>
      <div id="agpTalentEmpty" style="display:none;color:var(--gray-4);font-size:.85rem">Non hai ancora caricato nessun talent.</div>
    </div>

  </div>
</div>

<!-- MODAL TALENT -->
<div class="agp-modal-bg" id="agpTalentModalBg">
  <div class="agp-modal">
    <div class="agp-modal-head">
      <h3 id="agpTalentModalTitle">Nuovo talent</h3>
      <button type="button" class="agp-modal-close" id="agpTalentModalClose">&times;</button>
    </div>
    <form id="agpTalentForm">
      <input type="hidden" id="agp_talent_id">
      <div class="agp-row">
        <div><label class="agp-label">Nome</label><input class="agp-input" id="agp_nome" required></div>
        <div><label class="agp-label">Cognome</label><input class="agp-input" id="agp_cognome" required></div>
      </div>
      <div class="agp-row3">
        <div><label class="agp-label">Data di nascita</label><input class="agp-input" type="date" id="agp_data_nascita"></div>
        <div><label class="agp-label">Sesso</label>
          <select class="agp-select" id="agp_sesso"><option value="">—</option><option value="F">F</option><option value="M">M</option><option value="altro">Altro</option></select>
        </div>
        <div><label class="agp-label">Lingue parlate</label><input class="agp-input" id="agp_lingua" placeholder="italiano, inglese…"></div>
      </div>
      <div class="agp-row3">
        <div><label class="agp-label">Paese residenza</label><input class="agp-input" id="agp_paese_residenza" list="agpPaesiList" maxlength="2" style="text-transform:uppercase" placeholder="IT"></div>
        <div><label class="agp-label">Comune residenza</label><input class="agp-input" id="agp_comune_residenza"></div>
        <div><label class="agp-label">Provincia residenza</label><input class="agp-input" id="agp_provincia_residenza"></div>
      </div>
      <datalist id="agpPaesiList">
        <option value="IT">Italia</option><option value="ES">Spagna</option><option value="FR">Francia</option>
        <option value="GB">Regno Unito</option><option value="DE">Germania</option><option value="CH">Svizzera</option>
        <option value="US">Stati Uniti</option><option value="BR">Brasile</option><option value="PT">Portogallo</option>
        <option value="NL">Paesi Bassi</option><option value="BE">Belgio</option><option value="AT">Austria</option>
        <option value="PL">Polonia</option><option value="RO">Romania</option><option value="UA">Ucraina</option>
        <option value="RU">Russia</option><option value="CN">Cina</option><option value="JP">Giappone</option>
      </datalist>
      <div class="agp-row3">
        <div><label class="agp-label">Altezza (cm)</label><input class="agp-input" type="number" id="agp_altezza"></div>
        <div><label class="agp-label">Peso (kg)</label><input class="agp-input" type="number" id="agp_peso"></div>
        <div><label class="agp-label">Taglia</label>
          <select class="agp-select" id="agp_taglia"><option value="">—</option><option>XS</option><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option></select>
        </div>
      </div>
      <div class="agp-row3">
        <div><label class="agp-label">Scarpe</label><input class="agp-input" id="agp_scarpe"></div>
        <div><label class="agp-label">Capelli</label><input class="agp-input" id="agp_capelli" placeholder="colore"></div>
        <div><label class="agp-label">Lunghezza capelli</label><input class="agp-input" id="agp_lunghezza_capelli"></div>
      </div>
      <div class="agp-row3">
        <div><label class="agp-label">Occhi</label><input class="agp-input" id="agp_occhi"></div>
        <div><label class="agp-label">Instagram</label><input class="agp-input" id="agp_instagram" placeholder="@handle"></div>
        <div><label class="agp-label">TikTok</label><input class="agp-input" id="agp_tiktok" placeholder="@handle"></div>
      </div>
      <label class="agp-label" style="display:block">Etnia (max 2)</label>
      <div class="agp-checks" id="agpEtniaChecks"></div>
      <div class="agp-row3">
        <div><label class="agp-label">Petto (cm)</label><input class="agp-input" type="number" id="agp_misura_petto"></div>
        <div><label class="agp-label">Vita (cm)</label><input class="agp-input" type="number" id="agp_misura_vita"></div>
        <div><label class="agp-label">Fianchi (cm)</label><input class="agp-input" type="number" id="agp_misura_fianchi"></div>
      </div>

      <label class="agp-label" style="display:block">Ruoli</label>
      <div class="agp-checks" id="agpRuoliChecks"></div>

      <div style="margin-top:10px">
        <label class="agp-check" style="display:inline-flex"><input type="checkbox" id="agp_esclusiva"> Esclusiva con questo talent</label>
        <div id="agpEsclusivaDataWrap" style="display:none;margin-top:10px;max-width:220px">
          <label class="agp-label">Esclusiva fino al</label>
          <input class="agp-input" type="date" id="agp_esclusiva_fino">
        </div>
      </div>

      <div id="agpRegolamentoWrap" style="margin-top:16px">
        <label class="agp-check" style="display:inline-flex"><input type="checkbox" id="agp_regolamento_ok"> Confermo di avere il consenso del talent a caricare questo profilo, secondo il regolamento agenzie partner</label>
      </div>

      <div style="margin-top:20px;display:flex;gap:10px">
        <button type="submit" class="agp-btn" id="agpTalentSubmit">Salva talent</button>
        <button type="button" class="agp-btn agp-btn-secondary" id="agpTalentCancel">Annulla</button>
      </div>
      <div class="agp-msg" id="agpTalentMsg"></div>
    </form>

    <div id="agpFotoSection" style="display:none;margin-top:30px;padding-top:24px;border-top:1px solid var(--gray-2)">
      <h3 style="font-family:var(--font-display);font-size:1.05rem;margin-bottom:14px">Foto di questo talent</h3>
      <div id="agpFotoGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:8px;margin-bottom:16px"></div>
      <form id="agpFotoForm">
        <div class="agp-row">
          <div><label class="agp-label">Album</label>
            <select class="agp-select" id="agp_album_tipo">
              <option value="portfolio">Portfolio</option>
              <option value="dettaglio">Dettaglio</option>
              <option value="eventi">Eventi</option>
              <option value="casual">Casual</option>
            </select>
          </div>
          <div><label class="agp-label">File (JPG/PNG/WebP, max 20MB)</label><input class="agp-input" type="file" id="agp_foto_file" accept="image/jpeg,image/png,image/webp"></div>
        </div>
        <label class="agp-check" style="display:inline-flex;margin-top:6px"><input type="checkbox" id="agp_dichiarazione"> Dichiaro di avere i diritti su questa immagine e il consenso a caricarla</label>
        <div style="margin-top:14px"><button type="submit" class="agp-btn" id="agpFotoSubmit">Carica foto</button></div>
        <div class="agp-msg" id="agpFotoMsg"></div>
      </form>
    </div>
  </div>
</div>

<script>
(function(){
  'use strict';
  var API = '/crm_toagency/actions/';
  var state = { csrf:'', talenti:[], agenzia:null, quote:{talent_oggi:0,talent_max:0,upload_ultima_ora:0,upload_max:0} };
  var ROLES = ['Modelli','Attori','Hostess','Steward','Creator','Fotografi','Truccatori','Comparse','Parrucchieri','Stylist'];
  var ETNIE = [['caucasica','Caucasica'],['africana','Africana'],['asiatica','Asiatica'],['sud_asiatica','Sud-asiatica'],['latina','Latina'],['araba','Araba']];

  function el(id){ return document.getElementById(id); }
  function escAttr(s){ return (s||'').toString().replace(/"/g,'&quot;'); }
  function escHtml(s){ var d=document.createElement('div'); d.textContent = s||''; return d.innerHTML; }
  function fmtData(v){
    if(!v) return '—';
    var d = new Date(String(v).replace(' ','T'));
    if (isNaN(d.getTime())) return v;
    return d.toLocaleDateString('it-IT');
  }

  function showError(msg){
    el('agpLoading').style.display='none';
    el('agpContent').style.display='none';
    el('agpErrorBox').style.display='flex';
    if (msg) el('agpErrorText').textContent = msg;
  }

  function buildRuoliChecks(selected){
    selected = selected||[];
    var box = el('agpRuoliChecks'); box.innerHTML='';
    ROLES.forEach(function(r){
      var lab = document.createElement('label');
      lab.className='agp-check'+(selected.indexOf(r)>-1?' active':'');
      lab.innerHTML='<input type="checkbox" value="'+r+'"'+(selected.indexOf(r)>-1?' checked':'')+'> '+r;
      lab.querySelector('input').addEventListener('change', function(){ lab.classList.toggle('active', this.checked); });
      box.appendChild(lab);
    });
  }
  function buildEtniaChecks(selected){
    selected = selected||[];
    var box = el('agpEtniaChecks'); box.innerHTML='';
    ETNIE.forEach(function(e){
      var lab = document.createElement('label');
      lab.className='agp-check'+(selected.indexOf(e[0])>-1?' active':'');
      lab.innerHTML='<input type="checkbox" value="'+e[0]+'"'+(selected.indexOf(e[0])>-1?' checked':'')+'> '+e[1];
      lab.querySelector('input').addEventListener('change', function(){
        var checked = box.querySelectorAll('input:checked');
        if (checked.length>2 && this.checked){ this.checked=false; return; }
        lab.classList.toggle('active', this.checked);
      });
      box.appendChild(lab);
    });
  }

  function renderHeader(){
    el('agpNome').textContent = (state.agenzia && state.agenzia.nome_display) || '—';
    el('agpScadenza').textContent = fmtData(state.accesso_scade);
    el('agpQuotaTalent').textContent = state.quote.talent_oggi + ' / ' + state.quote.talent_max;
    el('agpQuotaUpload').textContent = state.quote.upload_ultima_ora + ' / ' + state.quote.upload_max;
    el('agpPendingBox').style.display = state.pending ? 'block' : 'none';
  }

  function addReferenteRow(r){
    r = r || {nome:'',ruolo:'',email:'',telefono:''};
    var box = el('agpReferenti');
    if (box.children.length>=10) return;
    var row = document.createElement('div');
    row.className='agp-referente';
    row.innerHTML =
      '<input class="agp-input" placeholder="Nome" data-f="nome" value="'+escAttr(r.nome)+'">'+
      '<input class="agp-input" placeholder="Ruolo" data-f="ruolo" value="'+escAttr(r.ruolo)+'">'+
      '<input class="agp-input" placeholder="Email" data-f="email" value="'+escAttr(r.email)+'">'+
      '<input class="agp-input" placeholder="Telefono" data-f="telefono" value="'+escAttr(r.telefono)+'">'+
      '<button type="button" class="agp-btn-icon" title="Rimuovi">&times;</button>';
    row.querySelector('.agp-btn-icon').addEventListener('click', function(){ row.remove(); });
    box.appendChild(row);
  }
  function renderReferenti(list){
    var box = el('agpReferenti'); box.innerHTML='';
    if (!list || !list.length) list = [{nome:'',ruolo:'',email:'',telefono:''}];
    list.forEach(addReferenteRow);
  }
  el('agpAddReferente').addEventListener('click', function(){ addReferenteRow(); });

  function fillAgenziaForm(){
    var a = state.agenzia || {};
    el('agp_ragione_sociale').value = a.ragione_sociale || '';
    el('agp_citta').value = a.citta || '';
    el('agp_email').value = a.email || '';
    el('agp_telefono').value = a.telefono || '';
    el('agp_sito_web').value = a.sito_web || '';
    el('agp_book_url').value = a.book_url || '';
    renderReferenti(a.referenti || []);
  }

  el('agpAgenziaForm').addEventListener('submit', function(e){
    e.preventDefault();
    var btn = el('agpAgenziaSubmit'); btn.disabled=true;
    var referenti=[];
    el('agpReferenti').querySelectorAll('.agp-referente').forEach(function(row){
      var obj={};
      row.querySelectorAll('input').forEach(function(inp){ obj[inp.getAttribute('data-f')]=inp.value.trim(); });
      if (obj.nome||obj.email||obj.telefono) referenti.push(obj);
    });
    var fd = new URLSearchParams();
    fd.append('csrf', state.csrf);
    ['ragione_sociale','citta','email','telefono','sito_web','book_url'].forEach(function(f){ fd.append(f, el('agp_'+f).value.trim()); });
    fd.append('referenti', JSON.stringify(referenti));
    fetch(API+'agenzia-portale-salva-agenzia.php', {method:'POST', credentials:'same-origin', body: fd})
      .then(function(r){ return r.json(); })
      .then(function(data){
        btn.disabled=false;
        var msg = el('agpAgenziaMsg');
        if (data.ok){
          msg.className='agp-msg ok'; msg.textContent='Modifiche inviate: in attesa di approvazione dello staff.';
          load();
        } else {
          msg.className='agp-msg err'; msg.textContent = data.messaggio || 'Errore, riprova.';
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato') showError();
        }
      })
      .catch(function(){ btn.disabled=false; var msg=el('agpAgenziaMsg'); msg.className='agp-msg err'; msg.textContent='Errore di rete, riprova.'; });
  });

  function findTalent(id){ var out=null; state.talenti.forEach(function(t){ if(String(t.talent_id)===String(id)) out=t; }); return out; }

  function renderTalenti(){
    var grid = el('agpTalentGrid'); grid.innerHTML='';
    el('agpTalentEmpty').style.display = state.talenti.length ? 'none' : 'block';
    state.talenti.forEach(function(t){
      var card = document.createElement('div'); card.className='agp-tcard';
      var photoUrl = (t.foto && t.foto.length) ? (API+'agenzia-portale-foto.php?media_id='+t.foto[0].media_id) : '';
      var badges = '<span class="agp-badge">'+escHtml(t.stato_profilo||'bozza')+'</span>';
      if (t.in_revisione) badges += '<span class="agp-badge warn">in revisione</span>';
      if (t.esclusiva) badges += '<span class="agp-badge excl">esclusiva fino '+fmtData(t.esclusiva_fino)+'</span>';
      card.innerHTML =
        '<div class="agp-tcard-photo"'+(photoUrl?' style="background-image:url(\''+photoUrl+'\')"':'')+'>'+(photoUrl?'':'Nessuna foto')+'</div>'+
        '<div class="agp-tcard-body">'+
          '<div class="agp-tcard-name">'+escHtml(t.nome)+' '+escHtml(t.cognome)+'</div>'+
          '<div class="agp-badges">'+badges+'</div>'+
          '<div class="agp-tcard-foto">'+(t.foto_totali||0)+' / '+(t.foto_max||0)+' foto</div>'+
          '<div class="agp-tcard-actions"><button type="button" class="agp-btn agp-btn-secondary" data-edit="'+t.talent_id+'">Modifica</button><button type="button" class="agp-btn agp-btn-secondary" data-foto="'+t.talent_id+'">Foto</button></div>'+
        '</div>';
      grid.appendChild(card);
    });
    grid.querySelectorAll('[data-edit]').forEach(function(b){ b.addEventListener('click', function(){ openTalentModal(b.getAttribute('data-edit')); }); });
    grid.querySelectorAll('[data-foto]').forEach(function(b){ b.addEventListener('click', function(){ openTalentModal(b.getAttribute('data-foto'), true); }); });
  }

  function renderFotoGrid(t){
    var box = el('agpFotoGrid'); box.innerHTML='';
    (t.foto||[]).forEach(function(f){
      var d = document.createElement('div');
      d.style.cssText = 'aspect-ratio:1;background:var(--black) center/cover no-repeat;border:1px solid '+(f.approvata?'var(--gray-3)':'#ffb84d')+';position:relative';
      d.style.backgroundImage = "url('"+API+"agenzia-portale-foto.php?media_id="+f.media_id+"')";
      if (!f.approvata){
        var b = document.createElement('span');
        b.textContent='in revisione';
        b.style.cssText='position:absolute;bottom:2px;left:2px;right:2px;font-size:.5rem;text-align:center;background:rgba(0,0,0,.7);color:#ffb84d;padding:2px';
        d.appendChild(b);
      }
      box.appendChild(d);
    });
  }

  function openTalentModal(talentId, focusFoto){
    var t = talentId ? findTalent(talentId) : null;
    el('agpTalentForm').reset();
    el('agp_talent_id').value = t ? t.talent_id : '';
    el('agpTalentModalTitle').textContent = t ? ('Modifica '+t.nome+' '+t.cognome) : 'Nuovo talent';
    el('agp_nome').value = t ? t.nome : '';
    el('agp_cognome').value = t ? t.cognome : '';
    el('agp_esclusiva').checked = !!(t && t.esclusiva);
    el('agpEsclusivaDataWrap').style.display = (t && t.esclusiva) ? 'block' : 'none';
    el('agp_esclusiva_fino').value = (t && t.esclusiva_fino) ? String(t.esclusiva_fino).slice(0,10) : '';
    buildRuoliChecks([]);
    buildEtniaChecks([]);
    el('agpRegolamentoWrap').style.display = t ? 'none' : 'block';
    el('agpTalentMsg').className = 'agp-msg';
    el('agpFotoSection').style.display = t ? 'block' : 'none';
    if (t) renderFotoGrid(t);
    el('agpTalentModalBg').classList.add('open');
    if (focusFoto && t) setTimeout(function(){ el('agpFotoSection').scrollIntoView({behavior:'smooth'}); }, 150);
  }
  function closeTalentModal(){ el('agpTalentModalBg').classList.remove('open'); }
  el('agpNewTalentBtn').addEventListener('click', function(){ openTalentModal(null); });
  el('agpTalentModalClose').addEventListener('click', closeTalentModal);
  el('agpTalentCancel').addEventListener('click', closeTalentModal);
  el('agp_esclusiva').addEventListener('change', function(){ el('agpEsclusivaDataWrap').style.display = this.checked?'block':'none'; });

  el('agpTalentForm').addEventListener('submit', function(e){
    e.preventDefault();
    var btn = el('agpTalentSubmit'); btn.disabled=true;
    var isEdit = !!el('agp_talent_id').value;
    var fd = new URLSearchParams();
    fd.append('csrf', state.csrf);
    if (isEdit) fd.append('talent_id', el('agp_talent_id').value);

    var fields = ['nome','cognome','data_nascita','sesso','paese_residenza','comune_residenza','provincia_residenza','altezza','peso','taglia','scarpe','capelli','lunghezza_capelli','occhi','lingua','instagram','tiktok','misura_petto','misura_vita','misura_fianchi'];
    fields.forEach(function(f){
      var v = el('agp_'+f).value.trim();
      if (!isEdit || v!=='') fd.append(f, v);
    });

    var ruoli=[]; el('agpRuoliChecks').querySelectorAll('input:checked').forEach(function(i){ ruoli.push(i.value); });
    if (!isEdit || ruoli.length) fd.append('ruoli', ruoli.join(','));

    var etnia=[]; el('agpEtniaChecks').querySelectorAll('input:checked').forEach(function(i){ etnia.push(i.value); });
    if (!isEdit || etnia.length) fd.append('etnia', etnia.join(','));

    var escl = el('agp_esclusiva').checked;
    fd.append('esclusiva', escl ? '1' : '0');
    if (escl) fd.append('esclusiva_fino', el('agp_esclusiva_fino').value);
    if (!isEdit) fd.append('regolamento_ok', el('agp_regolamento_ok').checked ? '1' : '0');

    fetch(API+'agenzia-portale-salva-talent.php', {method:'POST', credentials:'same-origin', body: fd})
      .then(function(r){ return r.json(); })
      .then(function(data){
        btn.disabled=false;
        var msg = el('agpTalentMsg');
        if (data.ok){
          msg.className='agp-msg ok'; msg.textContent = isEdit ? 'Modifiche inviate: in attesa di approvazione.' : 'Talent creato: in attesa di approvazione.';
          var newId = data.talent_id;
          load().then(function(){ if (!isEdit && newId){ setTimeout(function(){ openTalentModal(newId, true); }, 400); } });
        } else {
          msg.className='agp-msg err'; msg.textContent = data.messaggio || 'Errore, riprova.';
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato'){ closeTalentModal(); showError(); }
        }
      })
      .catch(function(){ btn.disabled=false; var msg=el('agpTalentMsg'); msg.className='agp-msg err'; msg.textContent='Errore di rete, riprova.'; });
  });

  el('agpFotoForm').addEventListener('submit', function(e){
    e.preventDefault();
    var talentId = el('agp_talent_id').value;
    if (!talentId) return;
    var fileInput = el('agp_foto_file');
    if (!fileInput.files.length){ var m0=el('agpFotoMsg'); m0.className='agp-msg err'; m0.textContent='Seleziona un file.'; return; }
    if (!el('agp_dichiarazione').checked){ var m1=el('agpFotoMsg'); m1.className='agp-msg err'; m1.textContent='Conferma la dichiarazione sui diritti dell\'immagine.'; return; }
    var btn = el('agpFotoSubmit'); btn.disabled=true;
    var fd = new FormData();
    fd.append('csrf', state.csrf);
    fd.append('talent_id', talentId);
    fd.append('album_tipo', el('agp_album_tipo').value);
    fd.append('dichiarazione', '1');
    fd.append('foto', fileInput.files[0]);
    fetch(API+'agenzia-portale-upload-foto.php', {method:'POST', credentials:'same-origin', body: fd})
      .then(function(r){ return r.json(); })
      .then(function(data){
        btn.disabled=false;
        var msg = el('agpFotoMsg');
        if (data.ok){
          msg.className='agp-msg ok'; msg.textContent='Foto caricata: in attesa di approvazione.';
          fileInput.value='';
          load().then(function(){ var t=findTalent(talentId); if (t) renderFotoGrid(t); });
        } else {
          msg.className='agp-msg err'; msg.textContent = data.messaggio || 'Errore, riprova.';
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato'){ el('agpTalentModalBg').classList.remove('open'); showError(); }
        }
      })
      .catch(function(){ btn.disabled=false; var msg=el('agpFotoMsg'); msg.className='agp-msg err'; msg.textContent='Errore di rete, riprova.'; });
  });

  function load(){
    return fetch(API+'agenzia-portale-load.php', {credentials:'same-origin'})
      .then(function(r){ return r.json(); })
      .then(function(data){
        if (!data.ok){
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato'){
            showError('Il tuo accesso non è (più) valido.');
          } else {
            showError(data.messaggio || 'Si è verificato un errore.');
          }
          return;
        }
        state.csrf = data.csrf;
        state.agenzia = data.agenzia;
        state.talenti = data.talent || [];
        state.quote = data.quote || {talent_oggi:0,talent_max:0,upload_ultima_ora:0,upload_max:0};
        state.pending = !!data.in_revisione || !!data.pending;
        state.accesso_scade = data.accesso_scade;
        el('agpLoading').style.display='none';
        el('agpErrorBox').style.display='none';
        el('agpContent').style.display='block';
        renderHeader();
        fillAgenziaForm();
        renderTalenti();
      })
      .catch(function(){ showError('Errore di rete. Ricarica la pagina o riprova più tardi.'); });
  }

  document.addEventListener('DOMContentLoaded', load);
})();
</script>

<?php toa_component('footer'); ?>
