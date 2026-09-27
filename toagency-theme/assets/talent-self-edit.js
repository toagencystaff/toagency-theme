/**
 * talent-self-edit.js — v2.0 (2026-05-19, S8.A)
 * v1.0 (S7): self-edit campi anagrafici talent_database.
 * v2.1 (2026-07-24): video di presentazione facoltativo (talent-self-edit-video.php, 50MB, WhatsApp fallback)
 * v2.0 (S8.A): aggiunge upload 4 album media (polaroid/dettaglio/portfolio/eventi)
 *              con disclaimer legale + veridicità per-album.
 */
(function () {
    'use strict';

    var cfg = window.talentEditConfig || {};
    var API_LOAD     = cfg.apiLoad;
    var API_SAVE     = cfg.apiSave;
    var API_MEDIA_LS = cfg.apiMediaList;
    var API_MEDIA_UP = cfg.apiMediaUp;
    var API_VIDEO = cfg.apiVideo || '/crm_toagency/actions/talent-self-edit-video.php';
    var talentNome = '', talentCognome = '', videoFile = null;
    var API_STATO    = cfg.apiStato;
    var UUID  = cfg.uuid  || '';
    var TOKEN = cfg.token || '';
    var STR   = cfg.strings || {};

    // FIX 2026-06-28 marco — aggiunti comune + provincia
    // 2026-08-08 TEMA — 'telefono' tolto da qui: gestito a parte (prefisso internazionale + numero)
    // 2026-08-08 TEMA — aggiunto 'paese_residenza': senza, la tendina Paese non veniva mai salvata (bug trovato in revisione)
    var FIELDS = ['instagram','tiktok','altezza','taglia','scarpe','capelli','occhi','sesso',
                  'comune_residenza','provincia_domicilio','paese_residenza']; // 'sesso' aggiunto 2026-09-19 marco
    // 2026-08-08 TEMA — nuovo album 'casual' (foto random tipo smartphone/vacanza, non rientrano negli altri)
    // FIX 2026-09-22 marco (CRM - EVENTS DATABASE) — album dalla definizione UNICA condivisa con la registrazione
    // (templates/_talent-albums.php): stessi nomi e spiegazioni, + Portfolio attore (prima invisibile al talent).
    var ALBUM_DEFS = (STR.albumDefs && STR.albumDefs.length) ? STR.albumDefs : null;
    var ALBUMS = ALBUM_DEFS ? ALBUM_DEFS.map(function (a) { return a.code; }) : ['polaroid','dettaglio','portfolio','eventi','casual'];
    var currentAlbum = 'polaroid';
    var albumsData = {}; ALBUMS.forEach(function (a) { albumsData[a] = []; });
    function albumDef(code) { if (!ALBUM_DEFS) return null; for (var i = 0; i < ALBUM_DEFS.length; i++) if (ALBUM_DEFS[i].code === code) return ALBUM_DEFS[i]; return null; }
    var mediaInfo = { ha_principale: false, principale_in_album: false }; // FIX 2026-09-22 marco
    var ALBUM_PRINCIPALE_OK = ['portfolio','portfolio_cinema','polaroid','eventi']; // come lib/talent_cover.php
    function albumLabel(code) { var d = albumDef(code); return d ? d.label : ((STR.albumLabels || {})[code] || code); }
    var paeseResidenza = '';
    // 2026-08-08 TEMA — livello + certificazioni per lingua (sostituisce "mostra ai clienti", ora decisa dallo staff)
    var LINGUE_LIVELLI = ['A1', 'A2', 'B1', 'B2', 'C1', 'C2', 'nativo'];
    var lingueDettaglioData = {};

    function $(id) { return document.getElementById(id); }

    // 2026-08-08 TEMA — ridisegna le righe livello+certificazioni per le lingue attualmente selezionate,
    // preservando i valori già inseriti anche se l'utente spunta/despunta altre lingue nel frattempo.
    function renderLingueDettaglio() {
        var box = $('tse-lingue-dettaglio');
        var chipsBox = $('f-lingue');
        if (!box || !chipsBox) return;
        // salva i valori delle righe attuali prima di ridisegnare
        box.querySelectorAll('[data-lang]').forEach(function (row) {
            var code = row.getAttribute('data-lang');
            var liv = row.querySelector('.tse-ld-livello');
            var cert = row.querySelector('.tse-ld-cert');
            var altroTxt = row.querySelector('.tse-ld-altro');
            lingueDettaglioData[code] = {
                livello: liv ? liv.value : '',
                certificazioni: cert ? cert.value.trim() : '',
                altro_testo: altroTxt ? altroTxt.value.trim() : ''
            };
        });
        var checked = Array.prototype.slice.call(chipsBox.querySelectorAll('input[type=checkbox]:checked')).map(function (el) { return el.value; });
        var labels = STR.lingueLabels || {};
        box.innerHTML = '';
        checked.forEach(function (code) {
            var d = lingueDettaglioData[code] || {};
            var row = document.createElement('div');
            row.setAttribute('data-lang', code);
            row.style.cssText = 'display:flex;gap:8px;align-items:center;margin:6px 0;flex-wrap:wrap;';

            var span = document.createElement('span');
            span.style.cssText = 'min-width:82px;font-size:13px;color:#e5e7eb;';
            span.textContent = labels[code] || code;
            row.appendChild(span);

            var sel = document.createElement('select');
            sel.className = 'tse-select tse-ld-livello';
            sel.style.cssText = 'max-width:150px;';
            var optEmpty = document.createElement('option');
            optEmpty.value = ''; optEmpty.textContent = STR.livelloSelect || '—';
            sel.appendChild(optEmpty);
            LINGUE_LIVELLI.forEach(function (lv) {
                var o = document.createElement('option');
                o.value = lv;
                o.textContent = (lv === 'nativo') ? (STR.livelloNativo || 'Madrelingua') : lv;
                if (d.livello === lv) o.selected = true;
                sel.appendChild(o);
            });
            row.appendChild(sel);

            var cert = document.createElement('input');
            cert.type = 'text';
            cert.className = 'tse-input tse-ld-cert';
            cert.placeholder = STR.certPlaceholder || 'Certificazione (facoltativo)';
            cert.style.cssText = 'flex:1;min-width:150px;';
            cert.value = d.certificazioni || '';
            row.appendChild(cert);

            // 2026-08-08 TEMA — "Altro": serve sapere QUALE lingua è (può essere più di una, testo libero).
            // Appeso dentro la stessa riga (data-lang) cosi' il salvataggio a inizio funzione lo ritrova.
            if (code === 'altro') {
                var altroInput = document.createElement('input');
                altroInput.type = 'text';
                altroInput.className = 'tse-input tse-ld-altro';
                altroInput.placeholder = STR.altroLinguaPlaceholder || 'Quali lingue? (es: Rumeno B2, Polacco A2)';
                altroInput.style.cssText = 'flex:1 1 100%;margin-top:4px;';
                altroInput.value = d.altro_testo || '';
                row.appendChild(altroInput);
            }

            box.appendChild(row);
        });
    }

    // FIX 2026-07-16 marco — guida album consigliati per ruolo (talent.ruoli dal load)
    var ROLE_ALBUMS = { model:['portfolio','dettaglio'], actor:['portfolio'], hostess:['eventi'], creator:['dettaglio','casual'], ugc_creator:['dettaglio','casual'], influencer:['dettaglio','casual'] }; // 2026-09-25 aggiunti creator/ugc_creator/influencer (ticket #291); 27/09 riportato sulla versione 23/09 (ticket #295)
    if (ALBUM_DEFS) { // FIX 2026-09-22 marco — mappa ruolo->album presa dalla definizione condivisa
        ROLE_ALBUMS = {};
        ALBUM_DEFS.forEach(function (a) {
            if (!a.roles || a.roles === '*') return;
            a.roles.split(',').forEach(function (r) { r = r.trim(); (ROLE_ALBUMS[r] = ROLE_ALBUMS[r] || []).push(a.code); });
        });
    }
    // FIX 2026-09-22 marco — linguette: prima gli album utili al ruolo (pallino verde), poi gli altri
    function _tseOrderTabs(want) {
        var box = $('tse-album-tabs'); if (!box) return;
        var tabs = Array.prototype.slice.call(box.querySelectorAll('.tse-album-tab'));
        tabs.sort(function (a, b) {
            var ia = want.indexOf(a.getAttribute('data-album')), ib = want.indexOf(b.getAttribute('data-album'));
            return (ia < 0 ? 99 : ia) - (ib < 0 ? 99 : ib);
        });
        tabs.forEach(function (t) {
            if (want.indexOf(t.getAttribute('data-album')) > -1 && !t.querySelector('.tse-dot')) {
                var dot = document.createElement('span'); dot.className = 'tse-dot'; t.insertBefore(dot, t.firstChild);
            }
            box.appendChild(t);
        });
    }
    function renderRuoloGuida(ruoli) {
        var box = $('tse-ruolo-guida');
        if (!box) return;
        if (!ruoli || !ruoli.length) { box.style.display = 'none'; return; }
        var L = STR.albumLabels || {};
        var want = ['polaroid'];
        ruoli.forEach(function (r) {
            (ROLE_ALBUMS[r] || []).forEach(function (a) { if (want.indexOf(a) < 0) want.push(a); });
        });
        _tseOrderTabs(want); // FIX 2026-09-22 marco
        var labels = want.map(function (a) { return albumLabel(a); });
        box.textContent = (STR.guidaRuoloIntro || 'Album consigliati') + ': ' + labels.join(', ') + '. ' + (STR.guidaPolaroidObblig || '');
        box.style.display = 'block';
    }

    // FIX 2026-07-01 marco — comune self-edit: ricerca a suggerimenti da cerca-comune.php, SENZA testo libero.
    // Visibile = f-comune_search (ricerca); valore salvato = hidden f-comune_residenza (solo se scelto dalla lista).
    function initComuneTypeahead(saved, nation) {
        var search = $('f-comune_search');
        var hidden = $('f-comune_residenza');
        var dd     = $('f-comune_dropdown');
        if (!search || !hidden || !dd) return;
        var apiBase = (window.talentEditConfig || {}).comuneApiUrl;
        if (!apiBase) return;
        var nat = (nation || 'IT').toUpperCase();
        var lastValid = saved || '';        // il valore già salvato è considerato valido
        hidden.value = saved || '';
        search.value = saved || '';
        search.dataset.valid = saved ? '1' : '';
        var timer = null;

        function closeDD() { dd.style.display = 'none'; dd.innerHTML = ''; }

        function fetchSug(q) {
            fetch(apiBase + '?type=cities&nation=' + encodeURIComponent(nat) + '&q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (list) {
                    dd.innerHTML = '';
                    if (!list || !list.length) { closeDD(); return; }
                    list.slice(0, 20).forEach(function (item) {
                        var row = document.createElement('div');
                        row.textContent = item.display || item.name_local;
                        row.style.cssText = 'padding:10px 12px;cursor:pointer;border-bottom:1px solid #222;color:#e5e7eb;font-size:14px;';
                        row.addEventListener('mouseenter', function () { row.style.background = '#1f1f27'; });
                        row.addEventListener('mouseleave', function () { row.style.background = ''; });
                        row.addEventListener('mousedown', function (e) {
                            e.preventDefault();               // evita il blur prima del click
                            var val = item.name_local || item.display;
                            hidden.value = val;
                            search.value = val;
                            search.dataset.valid = '1';
                            lastValid = val;
                            closeDD();
                        });
                        dd.appendChild(row);
                    });
                    dd.style.display = 'block';
                })
                .catch(function () { closeDD(); });
        }

        search.addEventListener('input', function () {
            search.dataset.valid = '';     // finché non scegli dalla lista, non è valido
            hidden.value = '';
            var q = search.value.trim();
            clearTimeout(timer);
            if (q.length < 2) { closeDD(); return; }
            timer = setTimeout(function () { fetchSug(q); }, 250);
        });

        search.addEventListener('blur', function () {
            setTimeout(function () {        // lascia scattare prima l'eventuale scelta (mousedown)
                closeDD();
                if (search.dataset.valid !== '1') {   // niente scelta valida → ripristina l'ultimo valido (no testo libero)
                    search.value = lastValid;
                    hidden.value = lastValid;
                    if (lastValid) search.dataset.valid = '1';
                }
            }, 180);
        });
    }

    // FIX 2026-07-01 marco — popola tendina provincia da province-italia.json (valore = nome canonico)
    function populateProvince(selected) {
        var sel = $('f-provincia_domicilio');
        if (!sel || sel.tagName !== 'SELECT') return;
        var url = (window.talentEditConfig || {}).provinceJsonUrl;
        if (!url) return;
        fetch(url).then(function (r) { return r.json(); }).then(function (list) {
            (list || []).forEach(function (p) {
                var o = document.createElement('option');
                o.value = p.name;                       // canonico = nome pieno (es. "Torino")
                o.textContent = p.name + ' (' + p.code + ')';
                sel.appendChild(o);
            });
            if (selected) {
                sel.value = selected;
                if (sel.value !== selected) {           // valore vecchio non in lista: preservalo, niente svuotamenti
                    var o2 = document.createElement('option');
                    o2.value = selected;
                    o2.textContent = selected;
                    sel.appendChild(o2);
                    sel.value = selected;
                }
            }
        }).catch(function () {});
    }

    // 2026-08-08 TEMA — Paese di residenza, stessa fonte dati della registrazione (cerca-comune.php?type=nations)
    function populateNazioni(selected) {
        var sel = $('f-paese_residenza');
        if (!sel) return;
        var apiBase = (window.talentEditConfig || {}).comuneApiUrl;
        if (!apiBase) return;
        fetch(apiBase + '?type=nations').then(function (r) { return r.json(); }).then(function (list) {
            (list || []).forEach(function (item) {
                var o = document.createElement('option');
                o.value = item.code;
                o.textContent = item.display || item.name_local || item.code;
                sel.appendChild(o);
            });
            sel.value = selected || 'IT';
            if (!sel.value) sel.value = 'IT';           // codice salvato non in lista: default Italia, non blocca il resto
            syncPaeseUI();
        }).catch(function () {});
    }

    // 2026-08-08 TEMA — mostra comune+provincia (Italia) oppure città libera (resto del mondo) in base al Paese scelto
    function syncPaeseUI() {
        var sel = $('f-paese_residenza');
        var rowIt = $('tse-row-it');
        var rowEstero = $('tse-row-estero');
        var esteroInput = $('f-comune_estero_visible');
        var hidden = $('f-comune_residenza');
        if (!sel) return;
        var isIT = (sel.value || 'IT') === 'IT';
        if (rowIt) rowIt.style.display = isIT ? '' : 'none';
        if (rowEstero) rowEstero.style.display = isIT ? 'none' : '';
        if (!isIT && esteroInput && hidden && !esteroInput.dataset.userEdited) esteroInput.value = hidden.value || '';
    }

    function showError(msg) {
        $('tse-status').textContent = msg;
        $('tse-status').classList.add('error');
        $('tse-form').classList.remove('visible');
        // FIX 2026-09-11 marco — con link non valido restava visibile solo la sezione video
        // (unica fuori da #tse-form, senza display:none di partenza): nasconderla col resto.
        var vs = $('tse-video-section'); if (vs) vs.style.display = 'none';
    }

    // FIX 2026-07-16 marco — barra % completezza profilo (talent-profilo-stato.php)
    function loadCompletezza() {
        if (!API_STATO) return;
        fetch(API_STATO + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN) + '&_=' + Date.now(), { credentials:'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (!d || !d.success || !d.completezza) return;
                var p = Math.max(0, Math.min(100, parseInt(d.completezza.percent, 10) || 0));
                var box = $('tse-completezza'), fill = $('tse-compl-fill'), pct = $('tse-compl-pct');
                if (fill) fill.style.width = p + '%';
                if (pct)  pct.textContent = p + '%';
                if (box)  box.style.display = 'block';
                var pol = d.completezza.polaroid || {};
                var pscad = $('tse-polaroid-scadute');
                if (pscad) pscad.style.display = pol.scadute ? 'block' : 'none';
                var mancano = (d.completezza.mancano) || [];
                var mbox = $('tse-mancano');
                if (mbox) {
                    if (mancano.length) {
                        var ML = STR.mancanoLabels || {};
                        var chips = mancano.map(function (k) { return '<span class="tse-manca-chip">' + escapeHtml(ML[k] || k) + '</span>'; }).join('');
                        mbox.innerHTML = '<div class="tse-manca-title">' + escapeHtml(STR.mancanoTitolo || 'Da completare') + '</div><div class="tse-manca-chips">' + chips + '</div>';
                        mbox.style.display = 'block';
                    } else {
                        mbox.style.display = 'none';
                    }
                }
            })
            .catch(function () {});
    }

    // FIX 2026-09-15 marco — card di stato prima del form pesante (Step 2 pagina-stato, chat CRM-MINORI-EMAIL-BATTENTI)
    function renderStatusCard(d) {
        var rows = [];
        if (d.visibile_pubblico) {
            rows.push('<div class="tse-status-row tse-status-ok"><span class="tse-status-dot tse-status-dot-ok"></span>' + escapeHtml(STR.statusPublic || '') + '</div>');
        } else {
            rows.push('<div class="tse-status-row tse-status-review"><span class="tse-status-dot tse-status-dot-review"></span>' + escapeHtml(STR.statusReview || '') + '</div>');
        }
        var nFoto = parseInt(d.foto_in_attesa, 10) || 0;
        if (nFoto > 0) {
            var txt = (STR.statusPhotosPending || '').replace('{n}', nFoto);
            rows.push('<div class="tse-status-row tse-status-pending"><span class="tse-status-dot tse-status-dot-pending"></span>' + escapeHtml(txt) + '</div>');
        }
        var pct = (d.completezza && d.completezza.percent !== undefined) ? Math.max(0, Math.min(100, parseInt(d.completezza.percent, 10) || 0)) : 0;
        rows.push('<div class="tse-status-row tse-status-info">' + escapeHtml(STR.complLabel || 'Profilo completo') + ': ' + pct + '%</div>');
        var box = $('tse-statuscard-rows');
        if (box) box.innerHTML = rows.join('');
    }

    var _tseGoFoto = false; // FIX 2026-09-22 marco — bottone "Le mie foto" della card di stato
    function talentShowForm(target) {
        _tseGoFoto = (target === 'foto');
        var card = $('tse-statuscard');
        if (card) card.style.display = 'none';
        var st = $('tse-status');
        if (st) { st.style.display = 'block'; st.textContent = STR.loading || 'Caricamento…'; st.classList.remove('error'); }
        loadData();
    }
    window.talentShowForm = talentShowForm; // FIX 2026-09-18 marco (bug reale) - mancava export su window, onclick bottone morto

    // Prima cosa che si vede: card leggera (stato pubblico, foto in attesa, % completezza) invece del form pesante.
    // Se qualcosa va storto qui, si cade sempre sul comportamento di prima (form diretto) — mai un vicolo cieco.
    function loadStatusCard() {
        if (!UUID || !TOKEN) { showError(STR.invalidLink || 'Link non valido'); return; }
        fetch(API_STATO + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN) + '&_=' + Date.now(), { credentials:'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (!d || !d.success) { loadData(); return; }
                renderStatusCard(d);
                var st = $('tse-status'); if (st) st.style.display = 'none';
                var card = $('tse-statuscard'); if (card) card.style.display = 'block';
            })
            .catch(function () { loadData(); });
    }

    // FIX 2026-07-16 TEMA — chip multi-select (etnia/ruoli/lingue) + limite max
    function setChips(groupId, values) {
        var g = $(groupId); if (!g) return;
        var vals = Array.isArray(values) ? values.map(String) : [];
        g.querySelectorAll('input[type=checkbox]').forEach(function (cb) {
            cb.checked = vals.indexOf(String(cb.value)) >= 0;
        });
    }
    function getChips(groupId) {
        var g = $(groupId); if (!g) return [];
        var out = [];
        g.querySelectorAll('input[type=checkbox]:checked').forEach(function (cb) { out.push(cb.value); });
        return out;
    }
    function initChipMax(groupId) {
        var g = $(groupId); if (!g) return;
        var max = parseInt(g.getAttribute('data-max') || '0', 10);
        if (!max) return;
        g.addEventListener('change', function (e) {
            var checked = g.querySelectorAll('input[type=checkbox]:checked');
            if (checked.length > max && e.target && e.target.checked) { e.target.checked = false; }
        });
    }

    function loadData() {
        if (!UUID || !TOKEN) { showError(STR.invalidLink || 'Link non valido'); return; }
        fetch(API_LOAD + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN), {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.success) { showError(STR.invalidLink || 'Link non valido'); return; }
            if (d.redirect_to) { var __rsep = d.redirect_to.indexOf('?') > -1 ? '&' : '?'; window.location.href = d.redirect_to + __rsep + 'lang=' + encodeURIComponent(cfg.lang || 'it'); return; } // FIX 2026-09-19 marco - redirect verso completa-scheda.php non portava lang, pagina tornava italiano
            $('tse-uuid-display').textContent = '#' + (d.uuid_short || UUID.substring(0,8));
            $('tse-name-display').innerHTML = 'Stai modificando il profilo di <strong>' +
                escapeHtml(d.talent.nome || '—') + '</strong>';
            talentNome = (d.talent.nome || ''); talentCognome = (d.talent.cognome || '');

            // FIX 2026-06-27 marco — dati live subito: niente più avviso "in attesa di revisione"
            $('tse-pending').style.display = 'none';

            FIELDS.forEach(function (f) {
                var el = $('f-' + f);
                if (el) el.value = (d.talent[f] !== null && d.talent[f] !== undefined) ? d.talent[f] : '';
            });

            // 2026-08-08 TEMA — telefono: separa prefisso internazionale dal numero salvato
            (function () {
                var raw = (d.talent.telefono || '').trim();
                var pfxEl = $('f-telefono-prefix'), numEl = $('f-telefono');
                if (!numEl) return;
                var m = raw.match(/^(\+\d{1,3})[\s.-]*(.*)$/);
                if (m) {
                    if (pfxEl) pfxEl.value = m[1];
                    numEl.value = m[2];
                } else {
                    if (pfxEl) pfxEl.value = '+39';
                    numEl.value = raw;
                }
            })();

            // Misure (sotto-step 2, 15/07) — popola da talent.misure + misure_prese_il
            var _M = d.talent.misure || {};
            var _anyExtra = false;
            var _EXTRA = ['spalle','collo','cavallo_interno','cavallo_esterno','coscia','polpaccio','manica','bicipite','avambraccio','polso'];
            document.querySelectorAll('.tse-mis').forEach(function (el) {
                var k = el.getAttribute('data-mis');
                if (k && _M[k] !== undefined && _M[k] !== null && _M[k] !== '') {
                    el.value = _M[k];
                    if (_EXTRA.indexOf(k) > -1) _anyExtra = true;
                }
            });
            var _pr = $('f-misure-prese'); if (_pr) _pr.value = d.talent.misure_prese_il || '';
            var _tg = $('f-mis-toggle');
            if (_anyExtra && _tg && !_tg.checked) { _tg.checked = true; _tg.dispatchEvent(new Event('change')); }
            document.querySelectorAll('.tse-mis, #f-altezza, #f-scarpe').forEach(function (el) { el.dispatchEvent(new Event('input')); });

            // FIX 2026-07-01 marco — tendina provincia self-edit
            populateProvince(d.talent.provincia_domicilio || '');

            // FIX 2026-07-01 marco — ricerca comune self-edit (no testo libero). Solo Italia: la riga è visibile
            // solo quando il Paese scelto è IT (vedi syncPaeseUI), quindi qui la nazione è sempre 'IT'.
            initComuneTypeahead(d.talent.comune_residenza || '', 'IT');

            // 2026-08-08 TEMA — Paese di residenza (prima il paese, poi comune/città — come in registrazione)
            populateNazioni(d.talent.paese_residenza || 'IT');

            // FIX 2026-05-26 marco — community + highlight campi mancanti
            // 2026-08-08 TEMA — non più solo Italia: link dinamico per paese/lingua (stesso pattern di page-registrati-talent.php)
            paeseResidenza = d.talent.paese_residenza || '';
            (function () {
                var cb = $('tse-community-block');
                if (!cb) return;
                cb.style.display = 'block';
                var btn = $('tse-community-wa-btn');
                if (!btn) return;
                var iso = (paeseResidenza || '').toUpperCase();
                if (['IT', 'ES', 'FR'].indexOf(iso) === -1) iso = 'INT';
                var lg = (cfg.lang || 'it');
                if (['it', 'en', 'fr', 'es'].indexOf(lg) === -1) lg = 'it';
                var href = 'https://toagency.it/crm_toagency/onboarding-community.php?paese=' + iso + '&lang=' + lg;
                if (talentNome) href += '&nome=' + encodeURIComponent(talentNome.trim().substring(0, 40));
                btn.href = href;
            })();
            highlightMissingFields(d.talent);
            // FIX 2026-07-16 marco — guida album per ruolo
            renderRuoloGuida(d.talent.ruoli);
            // FIX 2026-07-16 TEMA — precompila profilo professionale
            setChips('f-etnia',  d.talent.etnia);
            setChips('f-ruoli',  d.talent.ruoli);
            // TEMA 26/08 — "Bambino/a" non si sceglie a mano: si calcola da data_nascita (<18 anni).
            // Se data_nascita non arriva dal CRM non tocchiamo lo stato (niente da calcolare).
            (function () {
                var dn = d.talent.data_nascita;
                if (!dn) return;
                var bd = new Date(dn);
                if (isNaN(bd.getTime())) return;
                var oggi = new Date();
                var eta = oggi.getFullYear() - bd.getFullYear();
                var m = oggi.getMonth() - bd.getMonth();
                if (m < 0 || (m === 0 && oggi.getDate() < bd.getDate())) eta--;
                var cbBambino = document.querySelector('#f-ruoli input[value="bambino"]');
                if (cbBambino) cbBambino.checked = (eta < 18);
            })();
            setChips('f-lingue', d.talent.lingue);
            // FIX 2026-09-22 marco (Fase 2) — profilo eventi
            (function () {
                var ep = d.talent.eventi_profilo || {};
                setChips('f-ev-tipi', ep.tipi || []);
                setChips('f-ev-cert', ep.certificati || []);
                if ($('f-ev-anni')) $('f-ev-anni').value = ep.anni || '';
                if ($('f-ev-certaltro')) $('f-ev-certaltro').value = ep.cert_altro || '';
                _tseEvMinore = (function () { var dn = d.talent.data_nascita; if (!dn) return false; var b = new Date(dn); if (isNaN(b)) return false; var o = new Date(); var e = o.getFullYear() - b.getFullYear(); var m = o.getMonth() - b.getMonth(); if (m < 0 || (m === 0 && o.getDate() < b.getDate())) e--; return e < 18; })();
                _tseEvRefresh();
                var fr = $('f-ruoli'); if (fr) fr.addEventListener('change', _tseEvRefresh);
            })();
            // 2026-08-08 TEMA — livello+certificazioni per lingua (sostituisce lingue_pubbliche, tolta dal form talent)
            lingueDettaglioData = (d.talent.lingue_dettaglio && typeof d.talent.lingue_dettaglio === 'object') ? d.talent.lingue_dettaglio : {};
            renderLingueDettaglio();
            var _pat = $('f-patente'); if (_pat) _pat.checked = (d.talent.patente == 1 || d.talent.patente === true);
            // 2026-08-04 TEMA — automunito
            var _auto = $('f-automunito'); if (_auto) _auto.checked = (d.talent.automunito == 1 || d.talent.automunito === true);
            // 2026-08-08 TEMA — riallinea visibilità automunito (dipende da patente) dopo il load
            if (_pat) _pat.dispatchEvent(new Event('change'));
            loadCompletezza();

            $('tse-status').style.display = 'none';
            $('tse-form').classList.add('visible');

            // S8.A: carica anche i media e mostra la sezione foto
            loadMedia();
        })
        .catch(function (err) {
            console.error('[tse] load:', err);
            showError(STR.invalidLink || 'Errore caricamento');
        });
    }

    window.talentEditSubmit = function () {
        var btn = $('tse-btn-save');
        $('tse-result').innerHTML = '';
        btn.disabled = true;
        btn.textContent = STR.saving || 'Invio…';

        var payload = { uuid: UUID, t: TOKEN, honeypot_url: $('f-honeypot').value };
        FIELDS.forEach(function (f) {
            var el = $('f-' + f);
            payload[f] = el ? el.value.trim() : '';
        });

        // 2026-08-08 TEMA — telefono: ricompone prefisso + numero in un'unica stringa (stesso campo DB di prima)
        (function () {
            var pfx = ($('f-telefono-prefix') && $('f-telefono-prefix').value.trim()) || '+39';
            var num = ($('f-telefono') && $('f-telefono').value.trim()) || '';
            payload.telefono = num ? (pfx + ' ' + num) : '';
        })();

        // Misure (sotto-step 2, 15/07) — oggetto misure + data
        var _mis = {};
        document.querySelectorAll('.tse-mis').forEach(function (el) {
            var k = el.getAttribute('data-mis'); var v = (el.value || '').trim();
            if (k && v !== '') _mis[k] = v;
        });
        payload.misure = _mis;
        var _preseEl = $('f-misure-prese');
        if (_preseEl && _preseEl.value) payload.misure_prese_il = _preseEl.value;
        payload.etnia   = getChips('f-etnia');
        payload.ruoli   = getChips('f-ruoli');
        payload.lingue  = getChips('f-lingue');
        // 2026-08-08 TEMA — livello+certificazioni solo per le lingue attualmente selezionate
        (function () {
            var det = {};
            document.querySelectorAll('#tse-lingue-dettaglio [data-lang]').forEach(function (row) {
                var code = row.getAttribute('data-lang');
                var liv = row.querySelector('.tse-ld-livello');
                var cert = row.querySelector('.tse-ld-cert');
                var altroTxt = row.querySelector('.tse-ld-altro');
                det[code] = {
                    livello: liv ? liv.value : '',
                    certificazioni: cert ? cert.value.trim() : '',
                    altro_testo: altroTxt ? altroTxt.value.trim() : ''
                };
            });
            payload.lingue_dettaglio = det;
        })();
        payload.patente = ($('f-patente') && $('f-patente').checked) ? 1 : 0;
        payload.automunito = ($('f-automunito') && $('f-automunito').checked) ? 1 : 0;
        // FIX 2026-09-22 marco (Fase 2) — profilo eventi: si manda solo per hostess/steward o se compilato
        (function () {
            var ep = { tipi: getChips('f-ev-tipi'), anni: ($('f-ev-anni') ? $('f-ev-anni').value : ''), certificati: getChips('f-ev-cert'), cert_altro: ($('f-ev-certaltro') ? $('f-ev-certaltro').value.trim() : '') };
            var pieno = ep.tipi.length || ep.anni || ep.certificati.length || ep.cert_altro;
            if (_tseIsHostess() || pieno) payload.eventi_profilo = ep;
        })();

        fetch(API_SAVE, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.success) {
                if (d.changes_count === 0) {
                    showResult('err', STR.noChanges || 'Nessuna modifica');
                } else {
                    // FIX 2026-06-27 marco — dati live subito: popup con elenco modifiche
                    showLivePopup(d.changes || []);
                    setTimeout(loadData, 600);
                    setTimeout(loadCompletezza, 900);
                }
            } else {
                showResult('err', (STR.errorPrefix || 'Errore: ') + (d.message || d.error || 'unknown'));
            }
        })
        .catch(function () {
            showResult('err', (STR.errorPrefix || 'Errore: ') + 'rete');
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = STR.save || 'Invia';
        });
    };

    // ─── S8.A — media album ───
    function loadMedia() {
        fetch(API_MEDIA_LS + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN) + '&scope=self&_=' + Date.now(), {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.ok) return;
            ALBUMS.forEach(function (a) { albumsData[a] = d.albums[a] || []; });
            mediaInfo.ha_principale = !!d.ha_principale; mediaInfo.principale_in_album = !!d.principale_in_album; // FIX 2026-09-22 marco
            // FIX 2026-09-22 marco — minorenni: niente album Fiere e eventi (hostess/steward vietati ai minori), come in registrazione
            if (d.minore) {
                var _te = document.querySelector('.tse-album-tab[data-album="eventi"]');
                if (_te) _te.style.display = 'none';
                if (currentAlbum === 'eventi') currentAlbum = 'polaroid';
            }
            // FIX 2026-05-26 marco — photo alert se nessuna polaroid
            var pa = $('tse-photo-alert');
            if (pa) pa.style.display = albumsData['polaroid'].length === 0 ? 'block' : 'none';
            $('tse-foto-section').style.display = 'block';
            renderAlbum(currentAlbum);
            _tseEvRefresh(); // FIX 2026-09-22 marco (Fase 2)
            if (_tseGoFoto) { _tseGoFoto = false; $('tse-foto-section').scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        })
        .catch(function (err) { console.error('[tse] media load:', err); });
    }

    function renderAlbum(album) {
        currentAlbum = album;
        // Tab attivo
        var tabs = document.querySelectorAll('.tse-album-tab');
        for (var i=0; i<tabs.length; i++) {
            tabs[i].classList.toggle('active', tabs[i].getAttribute('data-album') === album);
        }
        // Descrizione album
        // FIX 2026-09-22 marco — spiegazione dall'album condiviso (a cosa serve + quante foto)
        var _ad = albumDef(album);
        var desc = _ad ? ((_ad.hint || '') + (_ad.quante ? ' ' + _ad.quante : '')) : ((STR.albumDesc && STR.albumDesc[album]) || '');
        $('tse-album-desc').textContent = desc;

        // Veridicità testo per album
        var v = (STR.verita && (STR.verita[album] || (album === 'portfolio_cinema' ? STR.verita.portfolio : ''))) || ''; // FIX 2026-09-22 marco
        $('tse-verita-text').textContent = v;

        // FIX 2026-06-27 marco — data scatto su TUTTI gli album (obbligatoria polaroid, facoltativa le altre)
        $('tse-data-scatto-wrap').style.display = 'block';
        var _lbl = $('tse-data-scatto-label');
        var _hint = $('tse-data-scatto-hint');
        if (album === 'polaroid') {
            if (_lbl)  _lbl.textContent  = (STR.dataScattoLabelReq  || 'Data scatto (obbligatoria)');
            if (_hint) _hint.textContent = (STR.dataScattoHintPolaroid || 'Quando è stata SCATTATA la foto (non quando la carichi). Max 5 anni fa, verrà stampata sulla foto.');
        } else {
            if (_lbl)  _lbl.textContent  = (STR.dataScattoLabelOpt  || 'Data scatto (facoltativa)');
            if (_hint) _hint.textContent = (STR.dataScattoHintAltri || 'Quando è stata SCATTATA la foto (non quando la carichi). Facoltativa ma utile.');
        }

        // Grid
        var grid = $('tse-album-grid');
        var items = albumsData[album] || [];

        // Rimuovi vecchio counter (se presente)
        var oldCount = grid.parentElement.querySelector('.tse-album-count-wrap');
        if (oldCount) oldCount.remove();

        if (!items.length) {
            grid.innerHTML = '<div class="tse-album-empty">' + escapeHtml(STR.noPhotos || 'Nessuna foto') + '</div>';
            resetUploadForm();
            return;
        }
        // FIX 2026-09-22 marco (CRM - EVENTS DATABASE) — foto semplici:
        //  - "Elimina" e "Sposta" SCRITTI sotto ogni foto, sempre visibili (prima: iconcine sopra la foto)
        //  - doppioni (stessa foto su 2 righe, tipico da candidatura) mostrati UNA volta; Elimina/Sposta agiscono su tutte le copie
        grid.innerHTML = '';
        var _seen = {}, _shown = [];
        items.forEach(function (it) {
            if (_seen[it.url]) { _seen[it.url].dupIds.push(it.id); return; }
            it.dupIds = [it.id]; _seen[it.url] = it; _shown.push(it);
        });
        _shown.forEach(function (it) {
            var stateClass = '', stateTxt = '';
            if (it.motivo_rifiuto) { stateClass = 'rejected'; stateTxt = STR.stateRejected || 'Rifiutata'; }
            else if (!it.approvato_staff) { stateClass = 'pending'; stateTxt = STR.statePending || 'In verifica'; }
            var thumb = document.createElement('div');
            thumb.className = 'tse-album-thumb' + (stateClass ? ' ' + stateClass : '');
            thumb.setAttribute('data-id', it.id); // FIX 2026-07-10 marco — drag&drop riordino
            var box = document.createElement('div');
            box.className = 'tse-thumb-img';
            var img = document.createElement('img');
            img.src = it.url; img.alt = ''; img.loading = 'lazy';
            box.appendChild(img);
            if (it.principale || (currentAlbum === 'eventi' && it.is_cover)) { // FIX 2026-09-22 marco
                var bdg = document.createElement('span');
                bdg.className = 'tse-thumb-princ';
                bdg.textContent = it.principale ? (STR.princBadge || '⭐ Foto principale') : (STR.coverEvBadge || '⭐ Copertina');
                box.appendChild(bdg);
            }
            if (stateTxt) {
                var st = document.createElement('span');
                st.className = 'tse-thumb-state' + (stateClass === 'rejected' ? ' rej' : '');
                st.textContent = stateTxt;
                box.appendChild(st);
            }
            (function (url) { box.addEventListener('click', function () { talentShowLightbox(url); }); })(it.url);
            thumb.appendChild(box);

            (function (ids, isPrinc, item) {
                var acts = document.createElement('div');
                acts.className = 'tse-thumb-actions';
                var delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.className = 'tse-thumb-btn tse-thumb-del';
                delBtn.textContent = STR.delLabel || 'Elimina';
                delBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (isPrinc) { alert(STR.noDelPrinc || 'Questa è la foto che vedono i clienti: prima scegline un\'altra come principale.'); return; } // FIX 2026-09-22 marco
                    if (!confirm(STR.confirmDel || 'Eliminare questa foto?')) return;
                    talentMediaDelete(ids);
                });
                acts.appendChild(delBtn);

                var moveBtn = document.createElement('button');
                moveBtn.type = 'button';
                moveBtn.className = 'tse-thumb-btn tse-thumb-move';
                moveBtn.textContent = STR.moveLabel || 'Sposta';
                var moveMenu = document.createElement('div');
                moveMenu.className = 'tse-move-menu';
                ALBUMS.forEach(function (alb) {
                    if (alb === currentAlbum) return;
                    var opt = document.createElement('button');
                    opt.type = 'button';
                    opt.textContent = albumLabel(alb);
                    opt.addEventListener('click', function (e) {
                        e.stopPropagation();
                        moveMenu.style.display = 'none';
                        ids.forEach(function (id) { talentMediaMove(id, alb); });
                    });
                    moveMenu.appendChild(opt);
                });
                moveBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var open = moveMenu.style.display === 'block';
                    document.querySelectorAll('.tse-move-menu').forEach(function (m) { m.style.display = 'none'; });
                    moveMenu.style.display = open ? 'none' : 'block';
                });
                acts.appendChild(moveBtn);
                acts.appendChild(moveMenu);
                // FIX 2026-09-22 marco — il talent sceglie la foto che vedono i clienti e il primo piano eventi
                if (currentAlbum === 'eventi') { // FIX 2026-09-22 marco (Fase 2) — etichetta Elegante/Sportivo
                    var stBtn = document.createElement('button');
                    stBtn.type = 'button'; stBtn.className = 'tse-thumb-btn tse-thumb-stile';
                    var sp = (item.stile === 'sportivo');
                    stBtn.textContent = sp ? (STR.stileSp || 'Sportivo') : (STR.stileEl || 'Elegante');
                    stBtn.addEventListener('click', function (e) { e.stopPropagation(); talentSetStile(ids[0], sp ? 'elegante' : 'sportivo'); });
                    acts.appendChild(stBtn);
                }
                if (currentAlbum === 'eventi' && !item.is_cover) {
                    var cvBtn = document.createElement('button');
                    cvBtn.type = 'button'; cvBtn.className = 'tse-thumb-btn tse-thumb-star';
                    cvBtn.textContent = STR.setCoverEv || '⭐ Copertina';
                    cvBtn.addEventListener('click', function (e) { e.stopPropagation(); talentSetCover(ids[0], 'eventi'); });
                    acts.appendChild(cvBtn);
                } else if (currentAlbum !== 'eventi' && !isPrinc && ALBUM_PRINCIPALE_OK.indexOf(currentAlbum) > -1 && item.approvato_staff && !item.motivo_rifiuto) {
                    var prBtn = document.createElement('button');
                    prBtn.type = 'button'; prBtn.className = 'tse-thumb-btn tse-thumb-star';
                    prBtn.textContent = STR.setPrinc || '⭐ Principale';
                    prBtn.addEventListener('click', function (e) { e.stopPropagation(); talentSetCover(ids[0], 'principale'); });
                    acts.appendChild(prBtn);
                }
                thumb.appendChild(acts);
            })(it.dupIds.slice(), !!it.principale, it);

            grid.appendChild(thumb);
        });

        _tseInitSort(grid, album); // FIX 2026-07-10 marco — drag&drop riordino foto
        var _pn = $('tse-princ-note'); // FIX 2026-09-22 marco
        if (_pn) { _pn.hidden = !(mediaInfo.ha_principale && !mediaInfo.principale_in_album); }

        // Contatore pending/rejected sotto la griglia
        var pendingCount = items.filter(function (i) { return !i.motivo_rifiuto && !i.approvato_staff; }).length;
        var rejectedCount = items.filter(function (i) { return !!i.motivo_rifiuto; }).length;
        if (pendingCount || rejectedCount) {
            var wrap = document.createElement('div');
            wrap.className = 'tse-album-count-wrap';
            var info = document.createElement('div');
            info.className = 'tse-album-count';
            var parts = [];
            if (pendingCount)  parts.push('🟡 ' + pendingCount + ' in attesa di approvazione');
            if (rejectedCount) parts.push('❌ ' + rejectedCount + ' rifiutate');
            info.textContent = parts.join(' · ');
            wrap.appendChild(info);
            grid.parentElement.insertBefore(wrap, grid.nextSibling);
        }

        // Reset upload area
        resetUploadForm();
    }

    function resetUploadForm() {
        $('tse-file-input').value = '';
        $('tse-upload-fname').textContent = '—';
        $('tse-legal-ok').checked = false;
        $('tse-verita-ok').checked = false;
        $('tse-data-scatto').value = '';
        $('tse-upload-status').textContent = '';
        $('tse-upload-status').className = 'tse-upload-status';
    }

    // FIX 2026-09-22 marco — il pannello di caricamento si apre solo quando serve
    window.talentToggleUpload = function (open) {
        var box = $('tse-upload-box'), btn = $('tse-add-photo');
        if (box) box.hidden = !open;
        if (btn) btn.hidden = !!open;
        if (open && box) box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    };

    window.talentAlbumSwitch = function (album) {
        if (ALBUMS.indexOf(album) < 0) return;
        renderAlbum(album);
    };

    window.talentFileChosen = function (input) {
        var f = input.files && input.files[0];
        $('tse-upload-fname').textContent = f ? f.name : '—';
        $('tse-upload-status').textContent = '';
        $('tse-upload-status').className = 'tse-upload-status';
    };

    window.talentUploadGo = function () {
        var btn = $('tse-upload-go');
        var status = $('tse-upload-status');
        var file = $('tse-file-input').files[0];
        if (!file) { status.textContent = 'Seleziona un file'; status.className = 'tse-upload-status err'; return; }
        if (!$('tse-legal-ok').checked || !$('tse-verita-ok').checked) {
            status.textContent = 'Devi accettare disclaimer + veridicità';
            status.className = 'tse-upload-status err';
            return;
        }
        // FIX 2026-06-27 marco — data scatto letta per tutti gli album; obbligatoria solo polaroid
        var dataScatto = $('tse-data-scatto').value;
        if (currentAlbum === 'polaroid' && !dataScatto) {
            status.textContent = 'Data scatto obbligatoria per polaroid'; status.className = 'tse-upload-status err'; return;
        }

        var fd = new FormData();
        fd.append('uuid', UUID);
        fd.append('t', TOKEN);
        fd.append('album_tipo', currentAlbum);
        fd.append('dichiarazione_legale', '1');
        fd.append('veridicita', '1');
        if (dataScatto) fd.append('data_scatto', /^[0-9]{4}-[0-9]{2}$/.test(dataScatto) ? dataScatto + '-01' : dataScatto);
        fd.append('foto', file);

        btn.disabled = true;
        btn.textContent = STR.uploading || 'Caricamento…';
        status.textContent = STR.uploading || 'Caricamento…';
        status.className = 'tse-upload-status loading';

        fetch(API_MEDIA_UP, { method:'POST', body:fd, credentials:'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.ok) {
                status.textContent = '✓ ' + (d.message || 'Foto caricata');
                status.className = 'tse-upload-status ok';
                setTimeout(loadMedia, 600);
                setTimeout(loadCompletezza, 900);
            } else {
                status.textContent = (STR.errorPrefix || 'Errore: ') + (d.message || d.error || 'upload');
                status.className = 'tse-upload-status err';
            }
        })
        .catch(function () {
            status.textContent = (STR.errorPrefix || 'Errore: ') + 'rete';
            status.className = 'tse-upload-status err';
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = STR.upload || 'Carica foto';
        });
    };

    function showResult(type, text) {
        $('tse-result').innerHTML = '<div class="tse-result ' + type + '">' + escapeHtml(text) + '</div>';
    }

    // FIX 2026-06-27 marco — popup "modifiche ora online" con elenco vecchio → nuovo
    function showLivePopup(changes) {
        var emptyTxt = STR.liveEmpty || '(vuoto)';
        var rows = (changes || []).map(function (c) {
            var ov = (c.old === null || c.old === '') ? emptyTxt : c.old;
            var nv = (c.new === null || c.new === '') ? emptyTxt : c.new;
            return '<div class="tse-live-row">' +
                       '<span class="tse-live-lbl">' + escapeHtml(c.label) + '</span>' +
                       '<span class="tse-live-vals"><span class="tse-live-old">' + escapeHtml(ov) + '</span>' +
                       ' <span class="tse-live-arrow">→</span> ' +
                       '<span class="tse-live-new">' + escapeHtml(nv) + '</span></span>' +
                   '</div>';
        }).join('');
        var ov = document.createElement('div');
        ov.className = 'tse-live-overlay';
        ov.innerHTML =
            '<div class="tse-live-modal" role="dialog" aria-modal="true">' +
                '<div class="tse-live-title">' + escapeHtml(STR.liveTitle || '✅ Le tue modifiche sono ora online!') + '</div>' +
                '<div class="tse-live-list">' + rows + '</div>' +
                '<button type="button" class="tse-live-btn">' + escapeHtml(STR.liveClose || 'Chiudi') + '</button>' +
            '</div>';
        document.body.appendChild(ov);
        function close() { if (ov.parentNode) ov.parentNode.removeChild(ov); }
        ov.addEventListener('click', function (e) { if (e.target === ov) close(); });
        ov.querySelector('.tse-live-btn').addEventListener('click', close);
    }

    // FIX 2026-05-26 marco — evidenzia campi obbligatori mancanti
    function highlightMissingFields(talent) {
        var checks = {
            'f-altezza':  talent.altezza,
            'f-taglia':   talent.taglia,
            'f-scarpe':   talent.scarpe,
            'f-capelli':  talent.capelli,
            'f-telefono': talent.telefono
        };
        var hasSocial = !!(talent.instagram || talent.tiktok);
        Object.keys(checks).forEach(function (id) {
            var el = $(id);
            if (!el) return;
            var missing = !checks[id];
            el.classList.toggle('tse-missing', missing);
            var hint = el.parentElement.querySelector('.tse-missing-hint');
            if (missing && !hint) {
                var h = document.createElement('div');
                h.className = 'tse-missing-hint';
                h.textContent = 'Campo mancante — completalo';
                el.parentElement.appendChild(h);
            } else if (!missing && hint) {
                hint.remove();
            }
        });
        ['f-instagram','f-tiktok'].forEach(function (id) {
            var el = $(id);
            if (el) el.classList.toggle('tse-missing', !hasSocial);
        });
    }

    // FIX 2026-06-28 marco — elimina foto dal self-edit
    // FIX 2026-09-22 marco — accetta piu' id (copie della stessa foto) e messaggio d'errore nella lingua del talent
    function talentMediaDelete(mediaIds) {
        var ids = Array.isArray(mediaIds) ? mediaIds : [mediaIds];
        var fallite = 0, bloccataPrinc = false;
        var chain = Promise.resolve();
        ids.forEach(function (mediaId) {
            chain = chain.then(function () {
                return fetch('/crm_toagency/actions/talent-media-delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ uuid: UUID, t: TOKEN, media_id: mediaId })
                })
                .then(function (r) { return r.json(); })
                .then(function (d) {
                    if (d && d.ok) {
                        albumsData[currentAlbum] = (albumsData[currentAlbum] || []).filter(function (m) { return m.id !== mediaId; });
                    } else if (d && d.error === 'is_principale') { bloccataPrinc = true; }
                    else { fallite++; console.warn('[tse] delete', mediaId, d && d.error); }
                })
                .catch(function () { fallite++; });
            });
        });
        chain.then(function () {
            renderAlbum(currentAlbum);
            if (bloccataPrinc) alert(STR.noDelPrinc || 'Questa è la foto che vedono i clienti: prima scegline un\'altra.');
            else if (fallite) alert(STR.delError || 'Errore durante l\'eliminazione, riprova.');
        });
    }

    // FIX 2026-09-22 marco (Fase 2) — sezione Hostess & Eventi
    var _tseEvMinore = false;
    function _tseIsHostess() { var g = $('f-ruoli'); var cb = g ? g.querySelector('input[value="hostess"]') : null; return !!(cb && cb.checked); }
    function _tseEvRefresh() {
        var sec = $('tse-ev-section'); if (!sec) return;
        var host = _tseIsHostess();
        var cta = $('tse-ev-cta'), body = $('tse-ev-hostess'), nf = $('tse-ev-nofoto');
        if (cta) cta.hidden = host || _tseEvMinore;
        if (body) body.hidden = !host || _tseEvMinore;
        if (nf) nf.hidden = !host || ((albumsData.eventi || []).length > 0);
    }
    window.talentAddHostess = function () {
        var g = $('f-ruoli'); var cb = g ? g.querySelector('input[value="hostess"]') : null;
        if (cb && !cb.checked) { cb.checked = true; cb.dispatchEvent(new Event('change', { bubbles: true })); }
        _tseEvRefresh();
        var sec = $('tse-ev-section'); if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };
    window.talentOpenEventi = function () {
        if (ALBUMS.indexOf('eventi') < 0) return;
        renderAlbum('eventi');
        var fs = $('tse-foto-section'); if (fs) fs.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };
    function talentSetStile(mediaId, valore) {
        fetch(cfg.apiSetCover || '/crm_toagency/actions/talent-media-set-cover.php', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uuid: UUID, t: TOKEN, media_id: mediaId, target: 'stile', valore: valore })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d && d.ok) {
                (albumsData.eventi || []).forEach(function (m) { if (m.id === mediaId) { m.stile = valore; m.is_cover = 0; } });
                renderAlbum(currentAlbum);
            } else { alert(STR.delError || 'Errore, riprova.'); }
        })
        .catch(function () { alert(STR.delError || 'Errore di rete, riprova.'); });
    }

    // FIX 2026-09-22 marco (CRM - EVENTS DATABASE) — foto principale / copertina eventi scelta dal talent
    function talentSetCover(mediaId, target) {
        fetch(cfg.apiSetCover || '/crm_toagency/actions/talent-media-set-cover.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uuid: UUID, t: TOKEN, media_id: mediaId, target: target })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d && d.ok) {
                var list = albumsData[currentAlbum] || [];
                var chosen = list.filter(function (m) { return m.id === mediaId; })[0];
                if (target === 'principale') {
                    ALBUMS.forEach(function (a) { (albumsData[a] || []).forEach(function (m) { m.principale = !!(chosen && m.url === chosen.url); }); });
                    mediaInfo.ha_principale = true; mediaInfo.principale_in_album = true;
                    alert(STR.princOk || 'Fatto: questa è ora la foto che vedono i clienti.');
                } else {
                    var stSel = (chosen && chosen.stile === 'sportivo') ? 'sportivo' : 'elegante';
                    list.forEach(function (m) { var st = (m.stile === 'sportivo') ? 'sportivo' : 'elegante'; if (st === stSel) m.is_cover = (m.id === mediaId) ? 1 : 0; });
                }
                renderAlbum(currentAlbum);
            } else if (d && d.error === 'not_approved_yet') {
                alert(STR.princPending || 'Questa foto è ancora in verifica.');
            } else {
                alert(STR.delError || 'Errore, riprova.');
            }
        })
        .catch(function () { alert(STR.delError || 'Errore di rete, riprova.'); });
    }

    // FIX 2026-06-28 marco — sposta foto tra album
    function talentMediaMove(mediaId, targetAlbum) {
        fetch('/crm_toagency/actions/talent-media-move.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uuid: UUID, t: TOKEN, media_id: mediaId, target_album: targetAlbum })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.ok) {
                // Sposta dalla cache locale e aggiorna entrambi gli album
                var item = (albumsData[currentAlbum] || []).find(function (m) { return m.id === mediaId; });
                if (item) {
                    albumsData[currentAlbum] = albumsData[currentAlbum].filter(function (m) { return m.id !== mediaId; });
                    if (!albumsData[targetAlbum]) albumsData[targetAlbum] = [];
                    albumsData[targetAlbum].push(item);
                }
                renderAlbum(currentAlbum);
            } else {
                alert('Errore durante lo spostamento: ' + (d.error || '?'));
            }
        })
        .catch(function () { alert('Errore di rete, riprova.'); });
    }

    // FIX 2026-07-10 marco — drag&drop riordino foto (SortableJS lazy da CDN, salva su media-ordine-save.php)
    var _tseSortInst = null;
    var _tseSortLoading = false;
    var API_MEDIA_ORDER = (API_MEDIA_LS || '/crm_toagency/actions/talent-media-list.php').replace('talent-media-list.php', 'media-ordine-save.php');
    function _tseSaveOrder(album) {
        var grid = $('tse-album-grid'); if (!grid) return;
        var ids = Array.prototype.map.call(grid.querySelectorAll('.tse-album-thumb[data-id]'), function (el) {
            return parseInt(el.getAttribute('data-id'), 10);
        }).filter(function (n) { return n > 0; });
        if (!ids.length) return;
        // riallinea la cache locale al nuovo ordine (così un re-render non "torna indietro")
        var byId = {}; (albumsData[album] || []).forEach(function (m) { byId[m.id] = m; });
        albumsData[album] = ids.map(function (id) { return byId[id]; }).filter(Boolean);
        var body = new URLSearchParams();
        body.append('uuid', UUID); body.append('t', TOKEN);
        body.append('tipo', 'talent'); body.append('ids', JSON.stringify(ids));
        fetch(API_MEDIA_ORDER, { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (d) { if (!d || !d.ok) console.warn('[tse] ordine non salvato', d); })
            .catch(function () { /* silenzioso: l'ordine a schermo resta comunque */ });
    }
    function _tseInitSort(grid, album) {
        if (!grid) return;
        if (typeof Sortable === 'undefined') {
            if (!_tseSortLoading) {
                _tseSortLoading = true;
                var s = document.createElement('script');
                s.src = 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js';
                s.onload = function () { _tseSortLoading = false; _tseInitSort($('tse-album-grid'), currentAlbum); };
                s.onerror = function () { _tseSortLoading = false; /* niente drag: degrada, resta tutto usabile */ };
                document.head.appendChild(s);
            }
            return;
        }
        if (_tseSortInst) { try { _tseSortInst.destroy(); } catch (e) {} _tseSortInst = null; }
        _tseSortInst = Sortable.create(grid, {
            animation: 150,
            draggable: '.tse-album-thumb',
            filter: '.tse-thumb-actions',     // i bottoni elimina/sposta non avviano il drag
            preventOnFilter: false,           // FIX 2026-09-22 marco — CAUSA "non riesco a cancellare le foto" su telefono: col default (true) Sortable blocca il tocco sui bottoni e il click non parte
            delay: 150, delayOnTouchOnly: true, // su touch serve una pressione, così lo scroll resta libero
            onEnd: function () { _tseSaveOrder(currentAlbum); }
        });
    }

    // FIX 2026-06-28 marco — lightbox anteprima foto (click su thumbnail)
    function talentShowLightbox(url) {
        var lb = $('tse-lb');
        var lbImg = $('tse-lb-img');
        if (!lb || !lbImg) { window.open(url, '_blank', 'noopener'); return; }
        lbImg.src = url;
        lb.style.display = 'flex';
    }
    // Chiudi lightbox con ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { var lb = $('tse-lb'); if (lb) lb.style.display = 'none'; }
    });

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c];
        });
    }
    function escapeAttr(s) { return escapeHtml(s); }

    // FIX 2026-09-23 marco ticket-239: messaggi del caricamento video nelle 4 lingue. I testi arrivano dal PHP (STR.video, via $_t);
    // il server manda solo il codice d'errore (res.error). Se STR.video manca, restano i testi italiani di riserva.
    var VSTR = STR.video || {};
    function tseVideoT(key, fallback) { return VSTR[key] || fallback || ''; }
    function tseVideoErr(res) {
        var code = (res && res.error) || '';
        var E = VSTR.err || {};
        var map = { too_big_server: 'too_big', invalid_mime: 'invalid_file', not_a_video: 'invalid_file', invalid_token: 'invalid_link', not_found: 'invalid_link', deleted: 'invalid_link', method_not_allowed: 'invalid_link' };
        var key = map[code] || code;
        if (code === 'cap_raggiunto') {
            var msg = String((res && res.message) || '');
            var n = (msg.match(/\d+/) || [''])[0];
            var tpl = E[/total/i.test(msg) ? 'cap_tot' : 'cap_album'] || '';
            if (tpl) return tpl.replace('{n}', n);
        }
        if (E[key]) return E[key];
        return (VSTR.generic || (res && res.message) || 'Errore upload') + (code ? ' (' + code + ')' : '');
    }
    function tseWaVideoLink() {
        var nome = (talentNome + ' ' + talentCognome).trim();
        var msg = 'Ciao, sono ' + (nome || 'un talent') + ', vi invio il mio video di presentazione per la scheda TOAgency';
        return 'https://wa.me/393518468516?text=' + encodeURIComponent(msg);
    }
    function tseVideoShowHeavy() {
        var h = $('tse-video-heavy'); if (h) h.style.display = 'block';
        var wa = $('tse-video-wa'); if (wa) wa.href = tseWaVideoLink();
    }
    window.talentVideoChosen = function (input) {
        videoFile = (input.files && input.files[0]) ? input.files[0] : null;
        var fn = $('tse-video-fname'); if (fn) fn.textContent = videoFile ? videoFile.name : '—';
        var st = $('tse-video-status'); if (st) { st.textContent = ''; st.className = 'tse-upload-status'; }
        var h = $('tse-video-heavy');
        if (videoFile && videoFile.size > 50 * 1024 * 1024) tseVideoShowHeavy();
        else if (h) h.style.display = 'none';
    };
    window.talentVideoGo = function () {
        var st = $('tse-video-status'), legal = $('tse-video-legal');
        if (!videoFile) { st.textContent = tseVideoT('chooseFirst', 'Scegli prima un video'); st.className = 'tse-upload-status err'; return; }
        if (!legal || !legal.checked) { st.textContent = tseVideoT('consent', 'Spunta il consenso per caricare'); st.className = 'tse-upload-status err'; return; }
        if (videoFile.size > 50 * 1024 * 1024) { st.textContent = tseVideoT('tooBig', 'Video oltre 50MB: esporta a 720p o usa WhatsApp'); st.className = 'tse-upload-status err'; tseVideoShowHeavy(); return; }
        var btn = $('tse-video-go'); if (btn) btn.disabled = true;
        st.textContent = tseVideoT('loading', 'Caricamento…'); st.className = 'tse-upload-status loading';
        var fd = new FormData();
        fd.append('uuid', UUID); fd.append('t', TOKEN); fd.append('video', videoFile);
        fd.append('dichiarazione_legale', '1'); fd.append('context', 'self_edit');
        fetch(API_VIDEO, { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { var http = r.status; return r.json().catch(function () { return { ok: false, error: (http === 413 ? 'too_big' : 'network') }; }); })
            .then(function (res) {
                if (res.ok) {
                    var mb = (String(res.message || '').match(/\(([\d.,]+) MB\)/) || [])[1];
                    st.textContent = '✓ ' + tseVideoT('okBase', 'Video caricato') + (mb ? ' (' + mb + ' MB).' : '.') + (res.pending_review ? tseVideoT('okPending', ' In attesa di approvazione dello staff.') : '');
                    st.className = 'tse-upload-status ok';
                    videoFile = null;
                    var fn = $('tse-video-fname'); if (fn) fn.textContent = '—';
                    var vi = $('tse-video-input'); if (vi) vi.value = '';
                    var h = $('tse-video-heavy'); if (h) h.style.display = 'none';
                } else {
                    st.textContent = '✗ ' + (res.error === 'network' ? tseVideoT('network', 'Errore di rete') : tseVideoErr(res));
                    st.className = 'tse-upload-status err';
                    if (res.error === 'too_big' || res.error === 'too_big_server') tseVideoShowHeavy();
                }
            })
            .catch(function () { st.textContent = '✗ ' + tseVideoT('network', 'Errore di rete'); st.className = 'tse-upload-status err'; })
            .finally(function () { var b = $('tse-video-go'); if (b) b.disabled = false; });
    };

    document.addEventListener('DOMContentLoaded', function () { initChipMax('f-etnia'); });
    // 2026-08-08 TEMA — ridisegna livello+certificazioni quando il talent spunta/despunta una lingua
    document.addEventListener('DOMContentLoaded', function () {
        var lb = $('f-lingue');
        if (lb) lb.addEventListener('change', renderLingueDettaglio);
    });
    // 2026-08-08 TEMA — Paese di residenza: cambia Italia/estero, e la città estero scrive nel campo nascosto reale
    document.addEventListener('DOMContentLoaded', function () {
        var paeseSel = $('f-paese_residenza');
        if (paeseSel) paeseSel.addEventListener('change', syncPaeseUI);
        var esteroInput = $('f-comune_estero_visible');
        var hidden = $('f-comune_residenza');
        if (esteroInput && hidden) {
            esteroInput.addEventListener('input', function () {
                esteroInput.dataset.userEdited = '1';
                hidden.value = esteroInput.value.trim();
            });
        }
    });
    document.addEventListener('DOMContentLoaded', loadStatusCard);
})();
