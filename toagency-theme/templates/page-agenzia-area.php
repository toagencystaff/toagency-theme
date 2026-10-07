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

.agp-steps{display:flex;gap:6px;margin-bottom:22px}
.agp-step-dot{flex:1;text-align:center;padding:9px 4px;border:1px solid var(--gray-3);background:transparent;font-size:.62rem;font-weight:700;text-transform:uppercase;letter-spacing:.3px;color:var(--gray-4);cursor:pointer}
.agp-step-dot.active{border-color:var(--accent);color:var(--accent)}
.agp-step-dot:disabled{opacity:.35;cursor:not-allowed}
.agp-step-panel{display:none}
.agp-step-panel.active{display:block}
.agp-step-nav{margin-top:20px;display:flex;gap:10px}

/* i18n — 08/09/2026 marco (CRM-AGENZIE-ESTERNE) */
.agp-lang{display:flex;gap:2px;margin-bottom:18px;justify-content:flex-end}
.agp-lang button{background:none;border:1px solid var(--gray-2);color:var(--gray-4);font:inherit;font-size:.68rem;text-transform:uppercase;letter-spacing:.5px;padding:5px 10px;cursor:pointer}
.agp-lang button.active{border-color:var(--accent);color:var(--white)}

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

  <div class="agp-lang" id="agpLang">
    <button type="button" data-lang="en">EN</button>
    <button type="button" data-lang="it">IT</button>
    <button type="button" data-lang="fr">FR</button>
    <button type="button" data-lang="es">ES</button>
  </div>

  <div class="agp-center" id="agpLoading"><div><div class="agp-spinner"></div><span data-t="loading">Caricamento area agenzia…</span></div></div>

  <div id="agpErrorBox" style="display:none" class="agp-center">
    <div class="agp-error-box">
      <h2 data-t="err_title">Accesso non disponibile</h2>
      <p id="agpErrorText" data-t="err_invalid">Il collegamento non è (più) valido.</p>
      <p style="margin-top:16px"><span data-t="err_help">Riapri il link ricevuto via email, oppure scrivi a</span> <a href="mailto:info@toagency.it">info@toagency.it</a>.</p>
    </div>
  </div>

  <div id="agpContent" style="display:none">

    <div class="agp-head">
      <div>
        <h1 id="agpNome">—</h1>
        <div class="agp-head-meta"><span data-t="access_until">Accesso attivo fino al</span> <b id="agpScadenza">—</b></div>
      </div>
      <div class="agp-quotas">
        <div class="agp-quota"><b id="agpQuotaTalent">—</b><span data-t="quota_talent">Talent oggi</span></div>
        <div class="agp-quota"><b id="agpQuotaUpload">—</b><span data-t="quota_upload">Foto ultima ora</span></div>
      </div>
    </div>

    <div class="agp-banner" data-t="banner">ⓘ Diamo sempre un'occhiata a quello che carichi prima che vada online — è così che restiamo affidabili con i clienti a cui proponete i vostri talent.</div>

    <div class="agp-section">
      <h2 data-t="sec_data">I tuoi dati</h2>
      <div class="agp-section-sub" data-t="sec_data_sub">Le modifiche passano da una rapida verifica nostra prima di essere visibili.</div>
      <div class="agp-card">
        <div id="agpPendingBox" style="display:none;margin-bottom:16px;padding:12px;border:1px solid #ffb84d;font-size:.78rem;color:#ffb84d" data-t="pending_box">Hai dati o modifiche in attesa di revisione dello staff.</div>
        <form id="agpAgenziaForm">
          <div class="agp-row">
            <div><label class="agp-label" data-t="f_ragione">Ragione sociale</label><input class="agp-input" id="agp_ragione_sociale"></div>
            <div><label class="agp-label" data-t="f_citta">Città</label><input class="agp-input" id="agp_citta"></div>
          </div>
          <div class="agp-row">
            <div><label class="agp-label" data-t="f_email">Email</label><input class="agp-input" type="email" id="agp_email"></div>
            <div><label class="agp-label" data-t="f_telefono">Telefono</label><input class="agp-input" type="tel" id="agp_telefono"></div>
          </div>
          <div class="agp-row">
            <div><label class="agp-label" data-t="f_sito">Sito web</label><input class="agp-input" id="agp_sito_web" placeholder="https://…"></div>
            <div><label class="agp-label" data-t="f_book">Link al book</label><input class="agp-input" id="agp_book_url" placeholder="https://…"></div>
          </div>
          <label class="agp-label" style="margin-top:10px;display:block" data-t="f_referenti">Referenti</label>
          <div id="agpReferenti"></div>
          <button type="button" class="agp-btn agp-btn-secondary" id="agpAddReferente" style="margin-top:4px" data-t="btn_add_ref">+ Aggiungi referente</button>
          <div style="margin-top:18px"><button type="submit" class="agp-btn" id="agpAgenziaSubmit" data-t="btn_save_ag">Salva modifiche</button></div>
          <div class="agp-msg" id="agpAgenziaMsg"></div>
        </form>
      </div>
    </div>

    <div class="agp-section">
      <h2 data-t="sec_talent">I tuoi talent</h2>
      <div class="agp-section-sub" data-t="sec_talent_sub">Carica un nuovo talent o aggiorna uno esistente.</div>
      <button type="button" class="agp-btn" id="agpNewTalentBtn" style="margin-bottom:20px" data-t="btn_new_talent">+ Nuovo talent</button>
      <div class="agp-talents" id="agpTalentGrid"></div>
      <div id="agpTalentEmpty" style="display:none;color:var(--gray-4);font-size:.85rem" data-t="talent_empty">Non hai ancora caricato nessun talent.</div>
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
    <p id="agpTalentEditHint" style="display:none;font-size:.78rem;color:var(--gray-4);margin:-10px 0 18px;line-height:1.5" data-t="edit_hint">Lascia vuoto un campo se non vuoi cambiarlo: resta come sta.</p>

    <div class="agp-steps" id="agpSteps">
      <button type="button" class="agp-step-dot" data-step="1" data-t="step1">1 · Chi è</button>
      <button type="button" class="agp-step-dot" data-step="2" data-t="step2">2 · Dove vive</button>
      <button type="button" class="agp-step-dot" data-step="3" data-t="step3">3 · Cosa fa</button>
      <button type="button" class="agp-step-dot" data-step="4" data-t="step4">4 · Foto</button>
    </div>

    <form id="agpTalentForm">
      <input type="hidden" id="agp_talent_id">

      <div class="agp-step-panel" data-step="1">
        <div class="agp-row">
          <div><label class="agp-label" data-t="f_nome">Nome</label><input class="agp-input" id="agp_nome" required></div>
          <div><label class="agp-label" data-t="f_cognome">Cognome</label><input class="agp-input" id="agp_cognome" required></div>
        </div>
        <div class="agp-row3">
          <div><label class="agp-label" data-t="f_nascita">Data di nascita</label><input class="agp-input" type="date" id="agp_data_nascita" min="<?php echo esc_attr(date('Y-m-d', strtotime('-100 years'))); ?>" max="<?php echo esc_attr(date('Y-m-d')); ?>"></div>
          <div style="grid-column:span 2"><label class="agp-label" data-t="f_sesso">Sesso</label>
            <select class="agp-select" id="agp_sesso"><option value="">—</option><option value="F">F</option><option value="M">M</option><option value="altro" data-t="sex_other">Altro</option></select>
          </div>
        </div>
        <!-- FIX 2026-10-07 marco (chat CRM Agenzie Minorenni e Sezione): minorenni accettati col consenso del genitore, come i nostri -->
        <div id="agpGenitoreWrap" style="display:none;border:1px solid var(--gray-2,#333);border-radius:10px;padding:14px;margin-bottom:14px">
          <p style="font-size:.82rem;line-height:1.5;margin:0 0 12px" data-t="f_gen_note">Talent minorenne: scrivi nome ed email di un genitore o tutore. Gli mandiamo noi il link per dare il consenso; finché non conferma, le foto non vengono mostrate ai clienti.</p>
          <div class="agp-row">
            <div><label class="agp-label" data-t="f_gen_nome">Nome e cognome del genitore o tutore</label><input class="agp-input" id="agp_genitore1_nome" maxlength="120" autocomplete="off"></div>
            <div><label class="agp-label" data-t="f_gen_email">Email del genitore o tutore</label><input class="agp-input" type="email" id="agp_genitore1_email" maxlength="190" autocomplete="off"></div>
          </div>
        </div>
        <div class="agp-step-nav"><button type="button" class="agp-btn" data-next="2" data-t="btn_next">Avanti</button></div>
      </div>

      <div class="agp-step-panel" data-step="2">
        <div class="agp-row3">
          <div><label class="agp-label" data-t="f_paese">Paese residenza</label><input class="agp-input" id="agp_paese_residenza" list="agpPaesiList" maxlength="2" style="text-transform:uppercase" placeholder="IT"></div>
          <div><label class="agp-label" data-t="f_comune">Comune residenza</label><input class="agp-input" id="agp_comune_residenza"></div>
          <div><label class="agp-label" data-t="f_provincia">Provincia residenza</label><input class="agp-input" id="agp_provincia_residenza"></div>
        </div>
        <!-- Nomi paese in inglese: sono etichette d'aiuto accanto al codice ISO,
             e le agenzie partner sono quasi tutte estere. Aggiunti i paesi da cui
             arrivano davvero le candidature (Africa, Sud America). — 08/09/2026 -->
        <datalist id="agpPaesiList">
          <option value="IT">Italy</option><option value="ES">Spain</option><option value="FR">France</option>
          <option value="GB">United Kingdom</option><option value="DE">Germany</option><option value="CH">Switzerland</option>
          <option value="US">United States</option><option value="BR">Brazil</option><option value="PT">Portugal</option>
          <option value="NL">Netherlands</option><option value="BE">Belgium</option><option value="AT">Austria</option>
          <option value="PL">Poland</option><option value="RO">Romania</option><option value="UA">Ukraine</option>
          <option value="RU">Russia</option><option value="CN">China</option><option value="JP">Japan</option>
          <option value="ZA">South Africa</option><option value="MA">Morocco</option><option value="DZ">Algeria</option>
          <option value="TN">Tunisia</option><option value="NG">Nigeria</option><option value="KE">Kenya</option>
          <option value="SN">Senegal</option><option value="CI">Ivory Coast</option><option value="CM">Cameroon</option>
          <option value="AR">Argentina</option><option value="MX">Mexico</option><option value="CO">Colombia</option>
          <option value="AU">Australia</option><option value="CA">Canada</option><option value="IN">India</option>
        </datalist>
        <div class="agp-step-nav">
          <button type="button" class="agp-btn agp-btn-secondary" data-prev="1" data-t="btn_prev">Indietro</button>
          <button type="button" class="agp-btn" data-next="3" data-t="btn_next">Avanti</button>
        </div>
      </div>

      <div class="agp-step-panel" data-step="3">
        <label class="agp-label" style="display:block" data-t="f_ruoli">Ruoli</label>
        <div class="agp-checks" id="agpRuoliChecks"></div>

        <div class="agp-row3" style="margin-top:14px">
          <div><label class="agp-label" data-t="f_altezza">Altezza (cm)</label><input class="agp-input" type="number" id="agp_altezza" min="100" max="220"></div>
          <div><label class="agp-label" data-t="f_taglia">Taglia</label>
            <select class="agp-select" id="agp_taglia"><option value="">—</option><option>XS</option><option>S</option><option>M</option><option>L</option><option>XL</option><option>XXL</option></select>
          </div>
          <div><label class="agp-label" data-t="f_scarpe">Scarpe</label><input class="agp-input" type="number" id="agp_scarpe" min="30" max="50"></div>
        </div>
        <div class="agp-row">
          <div><label class="agp-label" data-t="f_occhi">Colore occhi</label>
            <select class="agp-select" id="agp_occhi">
              <option value="">—</option>
              <option value="azzurri" data-t="eye_azzurri">Azzurri</option><option value="verdi" data-t="eye_verdi">Verdi</option>
              <option value="marroni" data-t="eye_marroni">Marroni</option><option value="neri" data-t="eye_neri">Neri</option><option value="grigi" data-t="eye_grigi">Grigi</option>
            </select>
          </div>
          <div><label class="agp-label" data-t="f_capelli">Colore capelli</label>
            <select class="agp-select" id="agp_capelli">
              <option value="">—</option>
              <option value="biondi" data-t="hair_biondi">Biondi</option><option value="castani" data-t="hair_castani">Castani</option><option value="neri" data-t="hair_neri">Neri</option>
              <option value="rossi" data-t="hair_rossi">Rossi</option><option value="grigi" data-t="hair_grigi">Grigi</option><option value="bianchi" data-t="hair_bianchi">Bianchi</option><option value="calvo" data-t="hair_calvo">Calvo</option>
            </select>
          </div>
        </div>
        <label class="agp-label" style="display:block" data-t="f_etnia">Etnia (max 2)</label>
        <div class="agp-checks" id="agpEtniaChecks"></div>
        <div id="agpMisureWrap" style="display:none">
          <label class="agp-label" style="display:block" data-t="misure_label">Misure (cm) — solo per il ruolo Modello/a</label>
          <div class="agp-row3">
            <div><label class="agp-label" data-t="f_petto">Petto</label><input class="agp-input" type="number" id="agp_misura_petto" min="50" max="150"></div>
            <div><label class="agp-label" data-t="f_vita">Vita</label><input class="agp-input" type="number" id="agp_misura_vita" min="40" max="150"></div>
            <div><label class="agp-label" data-t="f_fianchi">Fianchi</label><input class="agp-input" type="number" id="agp_misura_fianchi" min="50" max="150"></div>
          </div>
        </div>

        <div class="agp-row" style="margin-top:14px">
          <div><label class="agp-label">Instagram</label><input class="agp-input" id="agp_instagram" placeholder="@handle"></div>
          <div><label class="agp-label">TikTok</label><input class="agp-input" id="agp_tiktok" placeholder="@handle"></div>
        </div>
        <div style="margin-top:10px">
          <label class="agp-check" style="display:inline-flex"><input type="checkbox" id="agp_esclusiva"> <span data-t="chk_esclusiva">Esclusiva con questo talent</span></label>
          <div id="agpEsclusivaDataWrap" style="display:none;margin-top:10px;max-width:220px">
            <label class="agp-label" data-t="f_escl_fino">Esclusiva fino al</label>
            <input class="agp-input" type="date" id="agp_esclusiva_fino">
          </div>
        </div>
        <div id="agpRegolamentoWrap" style="margin-top:16px">
          <label class="agp-check" style="display:inline-flex"><input type="checkbox" id="agp_regolamento_ok"> <span data-t="chk_regolamento">Confermo di avere il consenso del talent a caricare questo profilo, secondo il regolamento agenzie partner</span></label>
        </div>
        <div class="agp-step-nav">
          <button type="button" class="agp-btn agp-btn-secondary" data-prev="2" data-t="btn_prev">Indietro</button>
          <button type="submit" class="agp-btn" id="agpTalentSubmit" data-t="btn_save_talent">Salva talent</button>
          <button type="button" class="agp-btn agp-btn-secondary" id="agpTalentCancel" data-t="btn_cancel">Annulla</button>
        </div>
      </div>

      <div class="agp-msg" id="agpTalentMsg"></div>
    </form>

    <div id="agpFotoSection" class="agp-step-panel" data-step="4" style="margin-top:24px;padding-top:24px;border-top:1px solid var(--gray-2)">
      <h3 style="font-family:var(--font-display);font-size:1.05rem;margin-bottom:6px" data-t="foto_title">Foto di questo talent</h3>
      <p style="font-size:.78rem;color:var(--gray-4);margin-bottom:14px" data-t="foto_hint">Disponibile dopo aver salvato il talent (step 1-3).</p>
      <div id="agpFotoGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(90px,1fr));gap:8px;margin-bottom:16px"></div>
      <form id="agpFotoForm">
        <div class="agp-row">
          <div><label class="agp-label" data-t="f_album">Album</label>
            <select class="agp-select" id="agp_album_tipo">
              <option value="portfolio" data-t="alb_portfolio">Portfolio</option>
              <option value="dettaglio" data-t="alb_dettaglio">Dettaglio</option>
              <option value="eventi" data-t="alb_eventi">Eventi</option>
              <option value="casual" data-t="alb_casual">Casual</option>
            </select>
          </div>
          <div><label class="agp-label" data-t="f_file">File (JPG/PNG/WebP, max 20MB)</label><input class="agp-input" type="file" id="agp_foto_file" accept="image/jpeg,image/png,image/webp"></div>
        </div>
        <label class="agp-check" style="display:inline-flex;margin-top:6px"><input type="checkbox" id="agp_dichiarazione"> <span data-t="chk_diritti">Dichiaro di avere i diritti su questa immagine e il consenso a caricarla</span></label>
        <div style="margin-top:14px"><button type="submit" class="agp-btn" id="agpFotoSubmit" data-t="btn_upload">Carica foto</button></div>
        <div class="agp-msg" id="agpFotoMsg"></div>
      </form>
      <div class="agp-step-nav">
        <button type="button" class="agp-btn agp-btn-secondary" data-prev="3" data-t="btn_prev">Indietro</button>
        <button type="button" class="agp-btn" id="agpFotoDone" data-t="btn_done">Fatto</button>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  'use strict';
  var API = '/crm_toagency/actions/';
  var state = { csrf:'', talenti:[], agenzia:null, quote:{talent_oggi:0,talent_max:0,upload_ultima_ora:0,upload_max:0} };
  var agpStep = 1;
  // Codici canonici da lib/ruoli.php ($RUOLI_TALENT) — SOLO ruoli talent, niente crew.
  // 'bambino' escluso dai ruoli (e' una categoria del form pubblico); i minorenni delle agenzie
  // si caricano col consenso del genitore (campi genitore nello step 1, dal 07/10/2026).
  var ROLE_CODES = ['model','actor','hostess','comparsa','influencer','ugc_creator'];
  var ETNIE_CODES = ['caucasica','africana','asiatica','sud_asiatica','latina','araba'];

  /* ═══ i18n — 08/09/2026 marco (CRM-AGENZIE-ESTERNE) ═══════════════════
     Le agenzie partner sono quasi tutte estere: l'area parte nella lingua
     del PAESE dell'agenzia, che arriva da agenzia-portale-load.php. La mappa
     paese->lingua e' la stessa di lib/agenzia_informativa.php.
     Se l'agenzia sceglie a mano una lingua, la scelta vince e resta salvata. */

  var DIZ = {
  it:{loading:'Caricamento area agenzia…',err_title:'Accesso non disponibile',err_invalid:'Il tuo accesso non è (più) valido.',err_help:'Riapri il link ricevuto via email, oppure scrivi a',err_generic:'Si è verificato un errore.',err_net_page:'Errore di rete. Ricarica la pagina o riprova più tardi.',
  access_until:'Accesso attivo fino al',quota_talent:'Talent oggi',quota_upload:'Foto ultima ora',
  banner:'ⓘ Diamo sempre un\'occhiata a quello che carichi prima che vada online — è così che restiamo affidabili con i clienti a cui proponete i vostri talent.',
  sec_data:'I tuoi dati',sec_data_sub:'Le modifiche passano da una rapida verifica nostra prima di essere visibili.',pending_box:'Hai dati o modifiche in attesa di revisione dello staff.',
  f_ragione:'Ragione sociale',f_citta:'Città',f_email:'Email',f_telefono:'Telefono',f_sito:'Sito web',f_book:'Link al book',f_referenti:'Referenti',btn_add_ref:'+ Aggiungi referente',btn_save_ag:'Salva modifiche',
  sec_talent:'I tuoi talent',sec_talent_sub:'Carica un nuovo talent o aggiorna uno esistente.',btn_new_talent:'+ Nuovo talent',talent_empty:'Non hai ancora caricato nessun talent.',
  modal_new:'Nuovo talent',modal_edit:'Modifica',edit_hint:'Lascia vuoto un campo se non vuoi cambiarlo: resta come sta.',
  step1:'1 · Chi è',step2:'2 · Dove vive',step3:'3 · Cosa fa',step4:'4 · Foto',
  f_nome:'Nome',f_cognome:'Cognome',f_nascita:'Data di nascita',f_sesso:'Sesso',sex_other:'Altro',
  f_paese:'Paese residenza',f_comune:'Comune residenza',f_provincia:'Provincia residenza',
  btn_next:'Avanti',btn_prev:'Indietro',f_ruoli:'Ruoli',f_altezza:'Altezza (cm)',f_taglia:'Taglia',f_scarpe:'Scarpe',f_occhi:'Colore occhi',f_capelli:'Colore capelli',f_etnia:'Etnia (max 2)',
  eye_azzurri:'Azzurri',eye_verdi:'Verdi',eye_marroni:'Marroni',eye_neri:'Neri',eye_grigi:'Grigi',
  hair_biondi:'Biondi',hair_castani:'Castani',hair_neri:'Neri',hair_rossi:'Rossi',hair_grigi:'Grigi',hair_bianchi:'Bianchi',hair_calvo:'Calvo',
  misure_label:'Misure (cm) — solo per il ruolo Modello/a',f_petto:'Petto',f_vita:'Vita',f_fianchi:'Fianchi',
  chk_esclusiva:'Esclusiva con questo talent',f_escl_fino:'Esclusiva fino al',
  chk_regolamento:'Confermo di avere il consenso del talent a caricare questo profilo, secondo il regolamento agenzie partner',
  btn_save_talent:'Salva talent',btn_cancel:'Annulla',
  foto_title:'Foto di questo talent',foto_hint:'Disponibile dopo aver salvato il talent (step 1-3).',f_album:'Album',
  alb_portfolio:'Portfolio',alb_dettaglio:'Dettaglio',alb_eventi:'Eventi',alb_casual:'Casual',
  f_file:'File (JPG/PNG/WebP, max 20MB)',chk_diritti:'Dichiaro di avere i diritti su questa immagine e il consenso a caricarla',btn_upload:'Carica foto',btn_done:'Fatto',
  role_model:'Modello/a',role_actor:'Attore/Attrice',role_hostess:'Hostess/Steward',role_comparsa:'Comparsa',role_influencer:'Influencer',role_ugc_creator:'UGC Creator',
  etnia_caucasica:'Caucasica',etnia_africana:'Africana',etnia_asiatica:'Asiatica',etnia_sud_asiatica:'Sud-asiatica',etnia_latina:'Latina',etnia_araba:'Araba',
  ph_nome:'Nome',ph_ruolo:'Ruolo',ph_email:'Email',ph_telefono:'Telefono',ttl_remove:'Rimuovi',
  badge_revisione:'in revisione',badge_escl:'esclusiva fino',stato_bozza:'bozza',no_photo:'Nessuna foto',lbl_foto:'foto',btn_edit:'Modifica',btn_photos:'Foto',
  msg_ag_ok:'Modifiche inviate — le controlliamo noi e poi vanno online.',msg_talent_new:'Talent creato — lo controlliamo noi e poi va online.',msg_photo_ok:'Foto caricata — la controlliamo noi e poi va online.',
  f_gen_note:'Talent minorenne: scrivi nome ed email di un genitore o tutore. Gli mandiamo noi il link per dare il consenso; finché non conferma, le foto non vengono mostrate ai clienti.',f_gen_nome:'Nome e cognome del genitore o tutore',f_gen_email:'Email del genitore o tutore',msg_need_dob:'Inserisci la data di nascita per continuare.',msg_need_parent:'Talent minorenne: inserisci nome ed email del genitore o tutore.',msg_talent_new_minor:'Talent creato. Abbiamo mandato al genitore il link per il consenso: finché non conferma, le foto non vengono mostrate ai clienti.',badge_genitore:'in attesa del genitore',
  msg_err:'Errore, riprova.',msg_net:'Errore di rete, riprova.',msg_need_name:'Inserisci almeno nome e cognome per continuare.',msg_pick_file:'Seleziona un file.',msg_need_decl:'Conferma la dichiarazione sui diritti dell\'immagine.'},

  en:{loading:'Loading your agency area…',err_title:'Access not available',err_invalid:'Your access is no longer valid.',err_help:'Open the link we emailed you again, or write to',err_generic:'Something went wrong.',err_net_page:'Network error. Please reload the page or try again later.',
  access_until:'Access valid until',quota_talent:'Talent today',quota_upload:'Photos last hour',
  banner:'ⓘ We always review what you upload before it goes live — that\'s how we stay reliable with the clients we present your talent to.',
  sec_data:'Your details',sec_data_sub:'Changes go through a quick review on our side before they show up.',pending_box:'You have details or changes waiting for our review.',
  f_ragione:'Registered company name',f_citta:'City',f_email:'Email',f_telefono:'Phone',f_sito:'Website',f_book:'Link to your book',f_referenti:'Contact people',btn_add_ref:'+ Add contact person',btn_save_ag:'Save changes',
  sec_talent:'Your talent',sec_talent_sub:'Upload a new talent or update an existing one.',btn_new_talent:'+ New talent',talent_empty:'You haven\'t uploaded any talent yet.',
  modal_new:'New talent',modal_edit:'Edit',edit_hint:'Leave a field empty to keep it as it is.',
  step1:'1 · Who they are',step2:'2 · Where they live',step3:'3 · What they do',step4:'4 · Photos',
  f_nome:'First name',f_cognome:'Last name',f_nascita:'Date of birth',f_sesso:'Gender',sex_other:'Other',
  f_paese:'Country of residence',f_comune:'Town of residence',f_provincia:'Region / province',
  btn_next:'Next',btn_prev:'Back',f_ruoli:'Categories',f_altezza:'Height (cm)',f_taglia:'Size',f_scarpe:'Shoe size (EU)',f_occhi:'Eye colour',f_capelli:'Hair colour',f_etnia:'Ethnicity (max 2)',
  eye_azzurri:'Blue',eye_verdi:'Green',eye_marroni:'Brown',eye_neri:'Black',eye_grigi:'Grey',
  hair_biondi:'Blonde',hair_castani:'Brown',hair_neri:'Black',hair_rossi:'Red',hair_grigi:'Grey',hair_bianchi:'White',hair_calvo:'Bald',
  misure_label:'Measurements (cm) — models only',f_petto:'Chest / bust',f_vita:'Waist',f_fianchi:'Hips',
  chk_esclusiva:'We have an exclusive with this talent',f_escl_fino:'Exclusive until',
  chk_regolamento:'I confirm we have the talent\'s consent to upload this profile, under the partner agency agreement',
  btn_save_talent:'Save talent',btn_cancel:'Cancel',
  foto_title:'Photos of this talent',foto_hint:'Available once the talent is saved (steps 1-3).',f_album:'Album',
  alb_portfolio:'Portfolio',alb_dettaglio:'Close-ups',alb_eventi:'Events',alb_casual:'Casual',
  f_file:'File (JPG/PNG/WebP, max 20MB)',chk_diritti:'I confirm we hold the rights to this image and the consent to upload it',btn_upload:'Upload photo',btn_done:'Done',
  role_model:'Model',role_actor:'Actor / Actress',role_hostess:'Hostess / Steward',role_comparsa:'Extra',role_influencer:'Influencer',role_ugc_creator:'UGC Creator',
  etnia_caucasica:'Caucasian',etnia_africana:'African',etnia_asiatica:'Asian',etnia_sud_asiatica:'South Asian',etnia_latina:'Latina / Latino',etnia_araba:'Arab',
  ph_nome:'Name',ph_ruolo:'Role',ph_email:'Email',ph_telefono:'Phone',ttl_remove:'Remove',
  badge_revisione:'under review',badge_escl:'exclusive until',stato_bozza:'draft',no_photo:'No photo',lbl_foto:'photos',btn_edit:'Edit',btn_photos:'Photos',
  msg_ag_ok:'Changes sent — we\'ll review them and then they go live.',msg_talent_new:'Talent created — we\'ll review it and then it goes live.',msg_photo_ok:'Photo uploaded — we\'ll review it and then it goes live.',
  f_gen_note:'Talent under 18: add the name and email of a parent or legal guardian. We\'ll send them the link to give consent; until they confirm, the photos are not shown to clients.',f_gen_nome:'Parent or guardian\'s full name',f_gen_email:'Parent or guardian\'s email',msg_need_dob:'Please enter the date of birth to continue.',msg_need_parent:'Talent under 18: please add the parent or guardian\'s name and email.',msg_talent_new_minor:'Talent created. We\'ve sent the parent the consent link: until they confirm, the photos are not shown to clients.',badge_genitore:'waiting for parent',
  msg_err:'Something went wrong, please try again.',msg_net:'Network error, please try again.',msg_need_name:'Please enter at least first and last name to continue.',msg_pick_file:'Please choose a file.',msg_need_decl:'Please confirm the image rights declaration.'},

  fr:{loading:'Chargement de votre espace agence…',err_title:'Accès indisponible',err_invalid:'Votre accès n\'est plus valable.',err_help:'Rouvrez le lien reçu par e-mail, ou écrivez à',err_generic:'Une erreur est survenue.',err_net_page:'Erreur réseau. Rechargez la page ou réessayez plus tard.',
  access_until:'Accès valable jusqu\'au',quota_talent:'Talents aujourd\'hui',quota_upload:'Photos dernière heure',
  banner:'ⓘ Nous relisons toujours ce que vous chargez avant la mise en ligne — c\'est ainsi que nous restons fiables auprès des clients à qui nous présentons vos talents.',
  sec_data:'Vos informations',sec_data_sub:'Les modifications passent par une vérification rapide de notre part avant d\'être visibles.',pending_box:'Vous avez des informations ou des modifications en attente de vérification.',
  f_ragione:'Raison sociale',f_citta:'Ville',f_email:'E-mail',f_telefono:'Téléphone',f_sito:'Site web',f_book:'Lien vers votre book',f_referenti:'Interlocuteurs',btn_add_ref:'+ Ajouter un interlocuteur',btn_save_ag:'Enregistrer',
  sec_talent:'Vos talents',sec_talent_sub:'Chargez un nouveau talent ou mettez à jour un talent existant.',btn_new_talent:'+ Nouveau talent',talent_empty:'Vous n\'avez encore chargé aucun talent.',
  modal_new:'Nouveau talent',modal_edit:'Modifier',edit_hint:'Laissez un champ vide pour le conserver tel quel.',
  step1:'1 · Qui c\'est',step2:'2 · Où il/elle vit',step3:'3 · Ce qu\'il/elle fait',step4:'4 · Photos',
  f_nome:'Prénom',f_cognome:'Nom',f_nascita:'Date de naissance',f_sesso:'Genre',sex_other:'Autre',
  f_paese:'Pays de résidence',f_comune:'Ville de résidence',f_provincia:'Région / département',
  btn_next:'Suivant',btn_prev:'Retour',f_ruoli:'Catégories',f_altezza:'Taille (cm)',f_taglia:'Taille vêtement',f_scarpe:'Pointure (EU)',f_occhi:'Couleur des yeux',f_capelli:'Couleur des cheveux',f_etnia:'Origine (max 2)',
  eye_azzurri:'Bleus',eye_verdi:'Verts',eye_marroni:'Marron',eye_neri:'Noirs',eye_grigi:'Gris',
  hair_biondi:'Blonds',hair_castani:'Châtains',hair_neri:'Noirs',hair_rossi:'Roux',hair_grigi:'Gris',hair_bianchi:'Blancs',hair_calvo:'Chauve',
  misure_label:'Mensurations (cm) — mannequins uniquement',f_petto:'Poitrine',f_vita:'Taille',f_fianchi:'Hanches',
  chk_esclusiva:'Nous avons une exclusivité avec ce talent',f_escl_fino:'Exclusivité jusqu\'au',
  chk_regolamento:'Je confirme avoir le consentement du talent pour charger ce profil, selon l\'accord agences partenaires',
  btn_save_talent:'Enregistrer le talent',btn_cancel:'Annuler',
  foto_title:'Photos de ce talent',foto_hint:'Disponible après l\'enregistrement du talent (étapes 1-3).',f_album:'Album',
  alb_portfolio:'Portfolio',alb_dettaglio:'Gros plans',alb_eventi:'Événements',alb_casual:'Casual',
  f_file:'Fichier (JPG/PNG/WebP, max 20 Mo)',chk_diritti:'Je déclare détenir les droits sur cette image et le consentement pour la charger',btn_upload:'Charger la photo',btn_done:'Terminé',
  role_model:'Mannequin',role_actor:'Acteur / Actrice',role_hostess:'Hôtesse / Hôte',role_comparsa:'Figurant(e)',role_influencer:'Influenceur',role_ugc_creator:'Créateur UGC',
  etnia_caucasica:'Caucasienne',etnia_africana:'Africaine',etnia_asiatica:'Asiatique',etnia_sud_asiatica:'Sud-asiatique',etnia_latina:'Latino',etnia_araba:'Arabe',
  ph_nome:'Nom',ph_ruolo:'Fonction',ph_email:'E-mail',ph_telefono:'Téléphone',ttl_remove:'Retirer',
  badge_revisione:'en vérification',badge_escl:'exclusivité jusqu\'au',stato_bozza:'brouillon',no_photo:'Aucune photo',lbl_foto:'photos',btn_edit:'Modifier',btn_photos:'Photos',
  msg_ag_ok:'Modifications envoyées — nous les vérifions puis elles sont mises en ligne.',msg_talent_new:'Talent créé — nous le vérifions puis il est mis en ligne.',msg_photo_ok:'Photo chargée — nous la vérifions puis elle est mise en ligne.',
  f_gen_note:'Talent mineur : indiquez le nom et l\'e-mail d\'un parent ou du tuteur légal. Nous lui envoyons le lien pour donner son accord ; tant qu\'il n\'a pas confirmé, les photos ne sont pas montrées aux clients.',f_gen_nome:'Nom et prénom du parent ou du tuteur',f_gen_email:'E-mail du parent ou du tuteur',msg_need_dob:'Indiquez la date de naissance pour continuer.',msg_need_parent:'Talent mineur : indiquez le nom et l\'e-mail du parent ou du tuteur.',msg_talent_new_minor:'Talent créé. Nous avons envoyé au parent le lien pour son accord : tant qu\'il n\'a pas confirmé, les photos ne sont pas montrées aux clients.',badge_genitore:'en attente du parent',
  msg_err:'Une erreur est survenue, réessayez.',msg_net:'Erreur réseau, réessayez.',msg_need_name:'Indiquez au moins le prénom et le nom pour continuer.',msg_pick_file:'Choisissez un fichier.',msg_need_decl:'Confirmez la déclaration sur les droits de l\'image.'},

  es:{loading:'Cargando tu área de agencia…',err_title:'Acceso no disponible',err_invalid:'Tu acceso ya no es válido.',err_help:'Vuelve a abrir el enlace que recibiste por email, o escribe a',err_generic:'Se ha producido un error.',err_net_page:'Error de red. Recarga la página o inténtalo más tarde.',
  access_until:'Acceso válido hasta el',quota_talent:'Talentos hoy',quota_upload:'Fotos última hora',
  banner:'ⓘ Siempre revisamos lo que subes antes de publicarlo — así seguimos siendo fiables ante los clientes a los que presentamos vuestros talentos.',
  sec_data:'Tus datos',sec_data_sub:'Los cambios pasan por una revisión rápida por nuestra parte antes de verse.',pending_box:'Tienes datos o cambios pendientes de revisión.',
  f_ragione:'Razón social',f_citta:'Ciudad',f_email:'Email',f_telefono:'Teléfono',f_sito:'Sitio web',f_book:'Enlace a vuestro book',f_referenti:'Personas de contacto',btn_add_ref:'+ Añadir contacto',btn_save_ag:'Guardar cambios',
  sec_talent:'Vuestros talentos',sec_talent_sub:'Sube un talento nuevo o actualiza uno existente.',btn_new_talent:'+ Nuevo talento',talent_empty:'Todavía no has subido ningún talento.',
  modal_new:'Nuevo talento',modal_edit:'Editar',edit_hint:'Deja un campo vacío para dejarlo como está.',
  step1:'1 · Quién es',step2:'2 · Dónde vive',step3:'3 · Qué hace',step4:'4 · Fotos',
  f_nome:'Nombre',f_cognome:'Apellidos',f_nascita:'Fecha de nacimiento',f_sesso:'Género',sex_other:'Otro',
  f_paese:'País de residencia',f_comune:'Ciudad de residencia',f_provincia:'Provincia / región',
  btn_next:'Siguiente',btn_prev:'Atrás',f_ruoli:'Categorías',f_altezza:'Altura (cm)',f_taglia:'Talla',f_scarpe:'Talla de zapato (EU)',f_occhi:'Color de ojos',f_capelli:'Color de pelo',f_etnia:'Etnia (máx. 2)',
  eye_azzurri:'Azules',eye_verdi:'Verdes',eye_marroni:'Marrones',eye_neri:'Negros',eye_grigi:'Grises',
  hair_biondi:'Rubio',hair_castani:'Castaño',hair_neri:'Negro',hair_rossi:'Pelirrojo',hair_grigi:'Gris',hair_bianchi:'Blanco',hair_calvo:'Calvo',
  misure_label:'Medidas (cm) — solo para modelos',f_petto:'Pecho',f_vita:'Cintura',f_fianchi:'Cadera',
  chk_esclusiva:'Tenemos exclusiva con este talento',f_escl_fino:'Exclusiva hasta el',
  chk_regolamento:'Confirmo que tenemos el consentimiento del talento para subir este perfil, según el acuerdo de agencias partner',
  btn_save_talent:'Guardar talento',btn_cancel:'Cancelar',
  foto_title:'Fotos de este talento',foto_hint:'Disponible después de guardar el talento (pasos 1-3).',f_album:'Álbum',
  alb_portfolio:'Portfolio',alb_dettaglio:'Primeros planos',alb_eventi:'Eventos',alb_casual:'Casual',
  f_file:'Archivo (JPG/PNG/WebP, máx. 20MB)',chk_diritti:'Declaro que tenemos los derechos sobre esta imagen y el consentimiento para subirla',btn_upload:'Subir foto',btn_done:'Listo',
  role_model:'Modelo',role_actor:'Actor / Actriz',role_hostess:'Azafata / Azafato',role_comparsa:'Figurante',role_influencer:'Influencer',role_ugc_creator:'Creador UGC',
  etnia_caucasica:'Caucásica',etnia_africana:'Africana',etnia_asiatica:'Asiática',etnia_sud_asiatica:'Surasiática',etnia_latina:'Latina',etnia_araba:'Árabe',
  ph_nome:'Nombre',ph_ruolo:'Cargo',ph_email:'Email',ph_telefono:'Teléfono',ttl_remove:'Quitar',
  badge_revisione:'en revisión',badge_escl:'exclusiva hasta',stato_bozza:'borrador',no_photo:'Sin foto',lbl_foto:'fotos',btn_edit:'Editar',btn_photos:'Fotos',
  msg_ag_ok:'Cambios enviados — los revisamos y luego se publican.',msg_talent_new:'Talento creado — lo revisamos y luego se publica.',msg_photo_ok:'Foto subida — la revisamos y luego se publica.',
  f_gen_note:'Talento menor de edad: indica el nombre y el email de un padre, una madre o el tutor legal. Le enviamos el enlace para dar su consentimiento; hasta que lo confirme, las fotos no se muestran a los clientes.',f_gen_nome:'Nombre y apellidos del padre, la madre o el tutor',f_gen_email:'Email del padre, la madre o el tutor',msg_need_dob:'Indica la fecha de nacimiento para continuar.',msg_need_parent:'Talento menor de edad: indica el nombre y el email del padre, la madre o el tutor.',msg_talent_new_minor:'Talento creado. Hemos enviado al padre o la madre el enlace para el consentimiento: hasta que lo confirme, las fotos no se muestran a los clientes.',badge_genitore:'pendiente del padre/madre',
  msg_err:'Se ha producido un error, inténtalo de nuevo.',msg_net:'Error de red, inténtalo de nuevo.',msg_need_name:'Introduce al menos nombre y apellidos para continuar.',msg_pick_file:'Elige un archivo.',msg_need_decl:'Confirma la declaración sobre los derechos de la imagen.'}
  };

  // Stessa mappa di lib/agenzia_informativa.php, allargata ai paesi francofoni
  // e ispanofoni con cui lavoriamo davvero. Non mappato -> inglese.
  var PAESE_LINGUA = {
    IT:'it',
    FR:'fr', MC:'fr', BE:'fr', LU:'fr', CH:'fr', MA:'fr', DZ:'fr', TN:'fr',
    SN:'fr', CI:'fr', CM:'fr', CD:'fr', ML:'fr', BF:'fr', GA:'fr', TG:'fr', BJ:'fr',
    ES:'es', MX:'es', AR:'es', CO:'es', PE:'es', CL:'es', VE:'es', EC:'es',
    UY:'es', DO:'es', PY:'es', BO:'es', CR:'es', PA:'es', GT:'es', CU:'es'
  };
  var LOCALI = { it:'it-IT', en:'en-GB', fr:'fr-FR', es:'es-ES' };

  var lang = 'en';          // finche' non sappiamo il paese, inglese
  var langScelta = false;   // true = l'ha decisa l'agenzia, il paese non la cambia piu'

  try {
    var q = (location.search.match(/[?&]lang=([a-z]{2})/i) || [])[1];
    var salvata = window.localStorage ? localStorage.getItem('agpLang') : null;
    if (q && DIZ[q.toLowerCase()])          { lang = q.toLowerCase(); langScelta = true; }
    else if (salvata && DIZ[salvata])       { lang = salvata;         langScelta = true; }
  } catch(e){}

  function t(k){ return (DIZ[lang] && DIZ[lang][k]) || DIZ.it[k] || k; }
  // Alias: dentro renderTalenti/renderFotoGrid/openTalentModal la variabile
  // del talent si chiama gia' 't' e coprirebbe la funzione. Li' si usa tr().
  var tr = t;

  function applyI18n(){
    document.documentElement.lang = lang;
    document.querySelectorAll('[data-t]').forEach(function(n){
      var v = t(n.getAttribute('data-t'));
      if (v) n.textContent = v;
    });
    document.querySelectorAll('#agpLang button').forEach(function(b){
      b.classList.toggle('active', b.getAttribute('data-lang') === lang);
    });
    // Le parti costruite da JS vanno ridisegnate: contengono testo tradotto.
    if (state.agenzia) { renderHeader(); renderReferentiLabels(); renderTalenti(); }
  }

  function setLang(l, daScelta){
    if (!DIZ[l] || l === lang) { if (daScelta) applyI18n(); return; }
    lang = l;
    if (daScelta){
      langScelta = true;
      try { if (window.localStorage) localStorage.setItem('agpLang', l); } catch(e){}
    }
    applyI18n();
  }

  document.querySelectorAll('#agpLang button').forEach(function(b){
    b.addEventListener('click', function(){ setLang(b.getAttribute('data-lang'), true); });
  });

  function el(id){ return document.getElementById(id); }
  function escAttr(s){ return (s||'').toString().replace(/"/g,'&quot;'); }
  function escHtml(s){ var d=document.createElement('div'); d.textContent = s||''; return d.innerHTML; }
  function fmtData(v){
    if(!v) return '—';
    var d = new Date(String(v).replace(' ','T'));
    if (isNaN(d.getTime())) return v;
    return d.toLocaleDateString(LOCALI[lang] || 'en-GB');
  }

  function showError(msg){
    el('agpLoading').style.display='none';
    el('agpContent').style.display='none';
    el('agpErrorBox').style.display='flex';
    // Un messaggio specifico vince sul testo predefinito: si toglie il data-t
    // cosi' un cambio lingua successivo non lo sovrascrive col generico.
    if (msg){ var n = el('agpErrorText'); n.removeAttribute('data-t'); n.textContent = msg; }
  }

  function updateMisureVisibility(){
    // Stessa logica del form talent reale (talent-form-v40.js): misure visibili
    // solo col ruolo "model" (Modello/a) selezionato, non legate al sesso.
    var hasModello = !!el('agpRuoliChecks').querySelector('input[value="model"]:checked');
    el('agpMisureWrap').style.display = hasModello ? 'block' : 'none';
  }

  function buildRuoliChecks(selected){
    selected = selected||[];
    var box = el('agpRuoliChecks'); box.innerHTML='';
    ROLE_CODES.forEach(function(code){
      var lab = document.createElement('label');
      lab.className='agp-check'+(selected.indexOf(code)>-1?' active':'');
      lab.innerHTML='<input type="checkbox" value="'+code+'"'+(selected.indexOf(code)>-1?' checked':'')+'> '+escHtml(t('role_'+code));
      lab.querySelector('input').addEventListener('change', function(){ lab.classList.toggle('active', this.checked); updateMisureVisibility(); });
      box.appendChild(lab);
    });
    updateMisureVisibility();
  }
  function buildEtniaChecks(selected){
    selected = selected||[];
    var box = el('agpEtniaChecks'); box.innerHTML='';
    ETNIE_CODES.forEach(function(code){
      var lab = document.createElement('label');
      lab.className='agp-check'+(selected.indexOf(code)>-1?' active':'');
      lab.innerHTML='<input type="checkbox" value="'+code+'"'+(selected.indexOf(code)>-1?' checked':'')+'> '+escHtml(t('etnia_'+code));
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
      '<input class="agp-input" placeholder="'+escAttr(t('ph_nome'))+'" data-f="nome" value="'+escAttr(r.nome)+'">'+
      '<input class="agp-input" placeholder="'+escAttr(t('ph_ruolo'))+'" data-f="ruolo" value="'+escAttr(r.ruolo)+'">'+
      '<input class="agp-input" placeholder="'+escAttr(t('ph_email'))+'" data-f="email" value="'+escAttr(r.email)+'">'+
      '<input class="agp-input" placeholder="'+escAttr(t('ph_telefono'))+'" data-f="telefono" value="'+escAttr(r.telefono)+'">'+
      '<button type="button" class="agp-btn-icon" title="'+escAttr(t('ttl_remove'))+'">&times;</button>';
    row.querySelector('.agp-btn-icon').addEventListener('click', function(){ row.remove(); });
    box.appendChild(row);
  }
  /* Cambio lingua: i referenti sono gia' compilati, non si ridisegnano
     (si perderebbe quello che l'agenzia sta scrivendo): si traducono
     solo i segnaposto e il titolo del pulsante. */
  function renderReferentiLabels(){
    var mappa = {nome:'ph_nome', ruolo:'ph_ruolo', email:'ph_email', telefono:'ph_telefono'};
    el('agpReferenti').querySelectorAll('input[data-f]').forEach(function(inp){
      var k = mappa[inp.getAttribute('data-f')];
      if (k) inp.placeholder = t(k);
    });
    el('agpReferenti').querySelectorAll('.agp-btn-icon').forEach(function(b){ b.title = t('ttl_remove'); });
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
          msg.className='agp-msg ok'; msg.textContent=t('msg_ag_ok');
          load();
        } else {
          msg.className='agp-msg err'; msg.textContent = data.messaggio || t('msg_err');
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato') showError();
        }
      })
      .catch(function(){ btn.disabled=false; var msg=el('agpAgenziaMsg'); msg.className='agp-msg err'; msg.textContent=t('msg_net'); });
  });

  function findTalent(id){ var out=null; state.talenti.forEach(function(t){ if(String(t.talent_id)===String(id)) out=t; }); return out; }

  function renderTalenti(){
    var grid = el('agpTalentGrid'); grid.innerHTML='';
    el('agpTalentEmpty').style.display = state.talenti.length ? 'none' : 'block';
    state.talenti.forEach(function(t){
      var card = document.createElement('div'); card.className='agp-tcard';
      var photoUrl = (t.foto && t.foto.length) ? (API+'agenzia-portale-foto.php?media_id='+t.foto[0].media_id) : '';
      var stato = (t.stato_profilo === 'bozza' || !t.stato_profilo) ? tr('stato_bozza') : t.stato_profilo;
      var badges = '<span class="agp-badge">'+escHtml(stato)+'</span>';
      if (t.in_revisione) badges += '<span class="agp-badge warn">'+escHtml(tr('badge_revisione'))+'</span>';
      if (t.minore && t.consenso_genitore !== 'confermato') badges += '<span class="agp-badge warn">'+escHtml(tr('badge_genitore'))+'</span>';
      if (t.esclusiva) badges += '<span class="agp-badge excl">'+escHtml(tr('badge_escl'))+' '+fmtData(t.esclusiva_fino)+'</span>';
      card.innerHTML =
        '<div class="agp-tcard-photo"'+(photoUrl?' style="background-image:url(\''+photoUrl+'\')"':'')+'>'+(photoUrl?'':escHtml(tr('no_photo')))+'</div>'+
        '<div class="agp-tcard-body">'+
          '<div class="agp-tcard-name">'+escHtml(t.nome)+' '+escHtml(t.cognome)+'</div>'+
          '<div class="agp-badges">'+badges+'</div>'+
          '<div class="agp-tcard-foto">'+(t.foto_totali||0)+' / '+(t.foto_max||0)+' '+escHtml(tr('lbl_foto'))+'</div>'+
          '<div class="agp-tcard-actions"><button type="button" class="agp-btn agp-btn-secondary" data-edit="'+t.talent_id+'">'+escHtml(tr('btn_edit'))+'</button><button type="button" class="agp-btn agp-btn-secondary" data-foto="'+t.talent_id+'">'+escHtml(tr('btn_photos'))+'</button></div>'+
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
        b.textContent=tr('badge_revisione');
        b.style.cssText='position:absolute;bottom:2px;left:2px;right:2px;font-size:.5rem;text-align:center;background:rgba(0,0,0,.7);color:#ffb84d;padding:2px';
        d.appendChild(b);
      }
      box.appendChild(d);
    });
  }

  function agpGoStep(n){
    n = String(n);
    document.querySelectorAll('.agp-step-panel').forEach(function(p){ p.classList.toggle('active', String(p.getAttribute('data-step'))===n); });
    document.querySelectorAll('.agp-step-dot').forEach(function(d){ d.classList.toggle('active', d.getAttribute('data-step')===n); });
    agpStep = n;
    el('agpTalentMsg').className = 'agp-msg';
    if (n==='4'){ var t = findTalent(el('agp_talent_id').value); if (t) renderFotoGrid(t); }
  }
  document.querySelectorAll('.agp-step-dot').forEach(function(d){
    d.addEventListener('click', function(){ if (!this.disabled) agpGoStep(this.getAttribute('data-step')); });
  });
  document.querySelectorAll('[data-next]').forEach(function(b){
    b.addEventListener('click', function(){
      if (this.getAttribute('data-next')==='2' && (!el('agp_nome').value.trim() || !el('agp_cognome').value.trim())){
        var msg = el('agpTalentMsg'); msg.className='agp-msg err'; msg.textContent=t('msg_need_name');
        return;
      }
      if (this.getAttribute('data-next')==='2'){
        var isNew = !el('agp_talent_id').value, cur = findTalent(el('agp_talent_id').value);
        var m2 = el('agpTalentMsg');
        if (isNew && !el('agp_data_nascita').value){ m2.className='agp-msg err'; m2.textContent=t('msg_need_dob'); return; }
        var manca = !el('agp_genitore1_nome').value.trim() || !el('agp_genitore1_email').value.trim();
        if (agpServeGenitore() && manca && (isNew || !(cur && cur.ha_genitore))){ m2.className='agp-msg err'; m2.textContent=t('msg_need_parent'); return; }
      }
      agpGoStep(this.getAttribute('data-next'));
    });
  });
  document.querySelectorAll('[data-prev]').forEach(function(b){ b.addEventListener('click', function(){ agpGoStep(this.getAttribute('data-prev')); }); });
  el('agpFotoDone').addEventListener('click', function(){ closeTalentModal(); });

  function openTalentModal(talentId, focusFoto){
    var t = talentId ? findTalent(talentId) : null;
    el('agpTalentForm').reset();
    el('agp_talent_id').value = t ? t.talent_id : '';
    el('agpTalentModalTitle').textContent = t ? (tr('modal_edit')+' '+t.nome+' '+t.cognome) : tr('modal_new');
    el('agp_nome').value = t ? t.nome : '';
    el('agp_cognome').value = t ? t.cognome : '';
    el('agp_esclusiva').checked = !!(t && t.esclusiva);
    el('agpEsclusivaDataWrap').style.display = (t && t.esclusiva) ? 'block' : 'none';
    el('agp_esclusiva_fino').value = (t && t.esclusiva_fino) ? String(t.esclusiva_fino).slice(0,10) : '';
    buildRuoliChecks([]);
    buildEtniaChecks([]);
    el('agpRegolamentoWrap').style.display = t ? 'none' : 'block';
    el('agpTalentEditHint').style.display = t ? 'block' : 'none';
    el('agpTalentMsg').className = 'agp-msg';
    document.querySelectorAll('.agp-step-dot[data-step="4"]').forEach(function(d){ d.disabled = !t; });
    if (t) renderFotoGrid(t);
    agpAggiornaGenitore();
    el('agpTalentModalBg').classList.add('open');
    agpGoStep(focusFoto && t ? 4 : 1);
  }
  function closeTalentModal(){ el('agpTalentModalBg').classList.remove('open'); }
  // FIX 2026-10-07 marco — minorenni: stesso conto degli anni del CRM (agPortaleEta).
  function agpEtaDa(v){
    var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(String(v||'')); if(!m) return null;
    var oggi = new Date(), y = +m[1], mo = +m[2], d = +m[3];
    var eta = oggi.getFullYear() - y;
    if ((oggi.getMonth()+1) < mo || ((oggi.getMonth()+1) === mo && oggi.getDate() < d)) eta--;
    return eta;
  }
  function agpServeGenitore(){
    var e = agpEtaDa(el('agp_data_nascita').value);
    if (e !== null) return e < 18;
    var t = findTalent(el('agp_talent_id').value);
    return !!(t && t.minore);
  }
  function agpAggiornaGenitore(){ el('agpGenitoreWrap').style.display = agpServeGenitore() ? 'block' : 'none'; }
  el('agp_data_nascita').addEventListener('change', agpAggiornaGenitore);
  el('agp_data_nascita').addEventListener('input', agpAggiornaGenitore);
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

    var fields = ['nome','cognome','data_nascita','sesso','paese_residenza','comune_residenza','provincia_residenza','altezza','taglia','scarpe','capelli','occhi','instagram','tiktok','misura_petto','misura_vita','misura_fianchi'];
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
    if (agpServeGenitore()){
      var gn = el('agp_genitore1_nome').value.trim(), ge = el('agp_genitore1_email').value.trim();
      if (gn) fd.append('genitore1_nome', gn);
      if (ge) fd.append('genitore1_email', ge);
    }

    fetch(API+'agenzia-portale-salva-talent.php', {method:'POST', credentials:'same-origin', body: fd})
      .then(function(r){ return r.json(); })
      .then(function(data){
        btn.disabled=false;
        var msg = el('agpTalentMsg');
        if (data.ok){
          msg.className='agp-msg ok'; msg.textContent = isEdit ? t('msg_ag_ok') : (data.minore ? t('msg_talent_new_minor') : t('msg_talent_new'));
          var newId = data.talent_id;
          load().then(function(){ if (!isEdit && newId){ setTimeout(function(){ openTalentModal(newId, true); }, 400); } });
        } else {
          msg.className='agp-msg err'; msg.textContent = data.messaggio || t('msg_err');
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato'){ closeTalentModal(); showError(); }
        }
      })
      .catch(function(){ btn.disabled=false; var msg=el('agpTalentMsg'); msg.className='agp-msg err'; msg.textContent=t('msg_net'); });
  });

  el('agpFotoForm').addEventListener('submit', function(e){
    e.preventDefault();
    var talentId = el('agp_talent_id').value;
    if (!talentId) return;
    var fileInput = el('agp_foto_file');
    if (!fileInput.files.length){ var m0=el('agpFotoMsg'); m0.className='agp-msg err'; m0.textContent=t('msg_pick_file'); return; }
    if (!el('agp_dichiarazione').checked){ var m1=el('agpFotoMsg'); m1.className='agp-msg err'; m1.textContent=t('msg_need_decl'); return; }
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
          msg.className='agp-msg ok'; msg.textContent=t('msg_photo_ok');
          fileInput.value='';
          load().then(function(){ var t=findTalent(talentId); if (t) renderFotoGrid(t); });
        } else {
          msg.className='agp-msg err'; msg.textContent = data.messaggio || t('msg_err');
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato'){ el('agpTalentModalBg').classList.remove('open'); showError(); }
        }
      })
      .catch(function(){ btn.disabled=false; var msg=el('agpFotoMsg'); msg.className='agp-msg err'; msg.textContent=t('msg_net'); });
  });

  function load(){
    return fetch(API+'agenzia-portale-load.php', {credentials:'same-origin'})
      .then(function(r){ return r.json(); })
      .then(function(data){
        if (!data.ok){
          if (data.errore==='non_autenticato' || data.errore==='accesso_revocato'){
            showError(t('err_invalid'));
          } else {
            showError(data.messaggio || t('err_generic'));
          }
          return;
        }
        state.csrf = data.csrf;
        state.agenzia = data.agenzia;
        state.talenti = data.talent || [];
        state.quote = data.quote || {talent_oggi:0,talent_max:0,upload_ultima_ora:0,upload_max:0};
        state.pending = !!data.in_revisione || !!data.pending;
        state.accesso_scade = data.accesso_scade;

        // Lingua dal paese dell'agenzia, se non l'ha gia' scelta lei a mano.
        // Il paese si sa solo adesso: fino a qui la pagina era in inglese,
        // ma non si e' ancora visto niente perche' c'era lo spinner.
        if (!langScelta){
          var p = String((data.agenzia && data.agenzia.paese) || '').toUpperCase();
          var l = PAESE_LINGUA[p] || 'en';
          if (l !== lang) lang = l;
        }

        el('agpLoading').style.display='none';
        el('agpErrorBox').style.display='none';
        el('agpContent').style.display='block';
        renderHeader();
        fillAgenziaForm();
        renderTalenti();
        applyI18n();
      })
      .catch(function(){ showError(t('err_net_page')); });
  }

  document.addEventListener('DOMContentLoaded', function(){ applyI18n(); load(); });
})();
</script>

<?php toa_component('footer'); ?>
