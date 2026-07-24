/**
 * crew-self-edit.js — v1.4 (2026-07-24)
 * v1.4: toggle "rendi pubblico" (consenso immediato, crew-self-edit-consenso.php)
 * v1.3: upload galleria portfolio (foto+video) da self-edit, max 15 foto / 6 video, pending approvazione
 * v1.2: contatore bio (max 800, min consigliato 150)
 * v1.1: campi età/P.IVA (data_nascita, ha_partita_iva + anno_partita_iva condizionale)
 * JS per /crew-self-edit/?uuid=...&t=...
 *
 * - Load via GET a /crm_toagency/actions/crew-self-edit-load.php
 * - Save via POST a /crm_toagency/actions/crew-self-edit-save.php
 */
(function () {
    'use strict';

    var cfg = window.crewEditConfig || {};
    var API_LOAD = cfg.apiLoad;
    var API_SAVE = cfg.apiSave;
    var API_UPLOAD_FOTO = cfg.apiUploadFoto || '/crm_toagency/actions/crew-upload-foto-profilo.php';
    var API_UPLOAD_PORTFOLIO = cfg.apiUploadPortfolio || '/crm_toagency/actions/crew-self-edit-upload-portfolio.php';
    var API_CONSENSO = cfg.apiConsenso || '/crm_toagency/actions/crew-self-edit-consenso.php';
    var MAX_FOTO = 15, MAX_VIDEO = 6;
    var portCounts = { foto: null, video: null };
    var UUID  = cfg.uuid  || '';
    var TOKEN = cfg.token || '';
    var STR   = cfg.strings || {};

    // FIX 2026-07-01 marco — aggiunti comune + provincia (geo self-edit crew)
    var FIELDS = ['telefono','email','bio','instagram','tiktok','sito_web','comune_residenza','provincia_domicilio','livello','anno_inizio_attivita','data_nascita','anno_partita_iva'];

    function $(id) { return document.getElementById(id); }

    // FIX 2026-07-01 marco — popola tendina provincia da province-italia.json (valore = nome pieno canonico)
    function populateProvince(selected) {
        var sel = $('f-provincia_domicilio');
        if (!sel || sel.tagName !== 'SELECT') return;
        var url = (window.crewEditConfig || {}).provinceJsonUrl;
        if (!url) return;
        fetch(url).then(function (r) { return r.json(); }).then(function (list) {
            (list || []).forEach(function (p) {
                var o = document.createElement('option');
                o.value = p.name;
                o.dataset.code = (p.code || '').toUpperCase();
                o.textContent = p.name + ' (' + p.code + ')';
                sel.appendChild(o);
            });
            if (selected) {
                sel.value = selected;                        // match per nome pieno ("Torino")
                if (sel.value !== selected) {
                    // dati vecchi salvati come sigla ("TO") → matcho la sigla, così mostra "Torino (TO)"
                    var up = selected.toUpperCase(), matched = false;
                    Array.prototype.forEach.call(sel.options, function (op) {
                        if (!matched && op.dataset.code === up) { sel.value = op.value; matched = true; }
                    });
                    if (!matched) {                          // valore ignoto → preservalo (no svuotamenti)
                        var o2 = document.createElement('option');
                        o2.value = selected; o2.textContent = selected;
                        sel.appendChild(o2); sel.value = selected;
                    }
                }
            }
        }).catch(function () {});
    }

    // FIX 2026-07-01 marco — comune: ricerca a suggerimenti da cerca-comune.php, SENZA testo libero.
    function initComuneTypeahead(saved, nation) {
        var search = $('f-comune_search');
        var hidden = $('f-comune_residenza');
        var dd     = $('f-comune_dropdown');
        if (!search || !hidden || !dd) return;
        var apiBase = (window.crewEditConfig || {}).comuneApiUrl;
        if (!apiBase) return;
        var nat = (nation || 'IT').toUpperCase();
        var lastValid = saved || '';
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
                            e.preventDefault();
                            var val = item.name_local || item.display;
                            hidden.value = val; search.value = val;
                            search.dataset.valid = '1'; lastValid = val;
                            closeDD();
                        });
                        dd.appendChild(row);
                    });
                    dd.style.display = 'block';
                })
                .catch(function () { closeDD(); });
        }

        search.addEventListener('input', function () {
            search.dataset.valid = ''; hidden.value = '';
            var q = search.value.trim();
            clearTimeout(timer);
            if (q.length < 2) { closeDD(); return; }
            timer = setTimeout(function () { fetchSug(q); }, 250);
        });

        search.addEventListener('blur', function () {
            setTimeout(function () {
                closeDD();
                if (search.dataset.valid !== '1') {
                    search.value = lastValid; hidden.value = lastValid;
                    if (lastValid) search.dataset.valid = '1';
                }
            }, 180);
        });
    }

    function showError(msg) {
        $('crew-edit-status').textContent = msg;
        $('crew-edit-status').classList.add('error');
        $('crew-edit-form').classList.remove('visible');
    }

    function togglePivaYear() {
        var chk = $('f-ha_partita_iva');
        var wrap = $('f-anno_piva-wrap');
        if (wrap) wrap.style.display = (chk && chk.checked) ? '' : 'none';
    }

    function updateBioCounter() {
        var ta = $('f-bio'), c = $('f-bio-counter');
        if (!ta || !c) return;
        var n = ta.value.length;
        c.textContent = n + ' / 800' + (n < 150 ? ' · consigliati almeno 150' : '');
        c.style.color = (n > 0 && n < 150) ? '#f59e0b' : '#9ca3af';
    }

    function updatePortfolioCounters() {
        var fc = $('f-portfolio-foto-counter'), vc = $('f-portfolio-video-counter');
        if (fc) fc.textContent = '📷 ' + (portCounts.foto == null ? '—' : portCounts.foto) + ' / ' + MAX_FOTO + ((portCounts.foto != null && portCounts.foto < 3) ? ' · consigliate almeno 3' : '');
        if (vc) vc.textContent = '🎬 ' + (portCounts.video == null ? '—' : portCounts.video) + ' / ' + MAX_VIDEO;
    }

    function uploadPortfolioFiles(tipo, files, statusEl) {
        var legal = $('f-legal'), verita = $('f-verita');
        if (!legal || !verita || !legal.checked || !verita.checked) {
            statusEl.textContent = '✗ Spunta prima i due consensi legali qui sopra';
            statusEl.className = 'crew-edit-foto-status err';
            return;
        }
        var max = (tipo === 'video') ? MAX_VIDEO : MAX_FOTO;
        var cur = (tipo === 'video') ? portCounts.video : portCounts.foto;
        var list = Array.prototype.slice.call(files), i = 0;
        function next() {
            if (i >= list.length) return;
            if (cur != null && cur >= max) {
                statusEl.textContent = '✗ Limite raggiunto (' + max + ')';
                statusEl.className = 'crew-edit-foto-status err';
                return;
            }
            var file = list[i++];
            statusEl.textContent = '⏳ Caricamento ' + i + '/' + list.length + '…';
            statusEl.className = 'crew-edit-foto-status loading';
            var fd = new FormData();
            fd.append('uuid', UUID); fd.append('t', TOKEN); fd.append('tipo', tipo);
            fd.append('file', file);
            fd.append('dichiarazione_legale', '1'); fd.append('veridicita', '1');
            fetch(API_UPLOAD_PORTFOLIO, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.ok) {
                        if (res.counts) { portCounts.foto = res.counts.foto; portCounts.video = res.counts.video; }
                        else { if (cur == null) cur = 0; cur++; if (tipo === 'video') portCounts.video = cur; else portCounts.foto = cur; }
                        cur = (tipo === 'video') ? portCounts.video : portCounts.foto;
                        updatePortfolioCounters();
                        statusEl.textContent = '✓ ' + (res.message || 'Caricato, in attesa di approvazione');
                        statusEl.className = 'crew-edit-foto-status ok';
                        next();
                    } else {
                        statusEl.textContent = '✗ ' + (res.message || res.error || 'Errore upload');
                        statusEl.className = 'crew-edit-foto-status err';
                    }
                })
                .catch(function () { statusEl.textContent = '✗ Errore di rete'; statusEl.className = 'crew-edit-foto-status err'; });
        }
        next();
    }

    function setupPortfolioUpload() {
        [['foto','f-portfolio-foto-btn','f-portfolio-foto-input','f-portfolio-foto-status'],
         ['video','f-portfolio-video-btn','f-portfolio-video-input','f-portfolio-video-status']].forEach(function (r) {
            var tipo = r[0], btn = $(r[1]), inp = $(r[2]), st = $(r[3]);
            if (!btn || !inp || !st) return;
            btn.addEventListener('click', function () { inp.click(); });
            inp.addEventListener('change', function (e) {
                if (e.target.files && e.target.files.length) uploadPortfolioFiles(tipo, e.target.files, st);
                inp.value = '';
            });
        });
        updatePortfolioCounters();
    }

    function setupConsenso() {
        var chk = $('f-consenso'), st = $('f-consenso-status');
        if (!chk) return;
        chk.addEventListener('change', function () {
            var val = chk.checked ? '1' : '0';
            chk.disabled = true;
            fetch(API_CONSENSO, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ uuid: UUID, t: TOKEN, consenso: val }),
                credentials: 'same-origin'
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                if (res.ok) {
                    chk.checked = (String(res.consenso) === '1');
                    if (st) { st.textContent = res.message || (chk.checked ? 'Profilo reso pubblico (in attesa di validazione staff).' : 'Profilo non pubblico.'); st.style.color = chk.checked ? '#c8ff00' : '#9ca3af'; }
                } else {
                    chk.checked = !chk.checked;
                    if (st) { st.textContent = '✗ ' + (res.message || res.error || 'Errore'); st.style.color = '#ef4444'; }
                }
            })
            .catch(function () { chk.checked = !chk.checked; if (st) { st.textContent = '✗ Errore di rete'; st.style.color = '#ef4444'; } })
            .finally(function () { chk.disabled = false; });
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
            if (!d.success) {
                showError(STR.invalidLink || 'Link non valido');
                return;
            }
            // UUID display
            $('crew-uuid-display').textContent = '#' + (d.uuid_short || UUID.substring(0,8));
            $('crew-edit-name-display').innerHTML = 'Stai modificando il profilo di <strong>' +
                escapeHtml(d.crew.nome || '—') + '</strong>';

            // Pending notice
            if (d.has_pending) {
                $('crew-edit-pending').textContent = STR.pending || 'Modifiche in attesa di revisione.';
                $('crew-edit-pending').style.display = 'block';
            }

            // Popola form
            FIELDS.forEach(function (f) {
                var el = $('f-' + f);
                if (el) el.value = d.crew[f] || '';
            });

            // Età + P.IVA (2026-07-23)
            var dn = $('f-data_nascita');
            if (dn && d.crew.data_nascita) dn.value = String(d.crew.data_nascita).substring(0, 10);
            var pivaChk = $('f-ha_partita_iva');
            if (pivaChk) { pivaChk.checked = (String(d.crew.ha_partita_iva) === '1'); togglePivaYear(); }

            updateBioCounter();

            if (d.counts) { portCounts.foto = d.counts.foto; portCounts.video = d.counts.video; }
            updatePortfolioCounters();

            var cons = $('f-consenso');
            if (cons) cons.checked = (String(d.consenso) === '1' || (d.crew && String(d.crew.consenso) === '1'));

            // FIX 2026-07-01 marco — geo crew self-edit
            populateProvince(d.crew.provincia_domicilio || '');
            initComuneTypeahead(d.crew.comune_residenza || '', d.crew.paese_residenza || 'IT');

            // Foto profilo (S6.5) — preview = foto VISIBILE approvata
            if (d.foto_profilo_url) {
                var img = $('f-foto-preview');
                img.src = d.foto_profilo_url + '?_=' + Date.now();
                img.style.display = 'block';
                $('f-foto-placeholder').style.display = 'none';
            } else {
                $('f-foto-preview').style.display = 'none';
                $('f-foto-placeholder').style.display = 'flex';
            }

            // S6.5-LEGAL: notice se foto pending
            var pendingNotice = $('f-foto-pending-notice');
            if (d.foto_pending && d.foto_pending.uploaded_at && pendingNotice) {
                var tpl = (cfg.pendingFotoTpl || 'Foto in attesa (caricata %s)');
                var when = String(d.foto_pending.uploaded_at).substring(0, 16);
                pendingNotice.textContent = tpl.replace('%s', when);
                pendingNotice.style.display = 'block';
            } else if (pendingNotice) {
                pendingNotice.style.display = 'none';
            }

            $('crew-edit-status').style.display = 'none';
            $('crew-edit-form').classList.add('visible');
        })
        .catch(function (err) {
            console.error('[crew-edit] load:', err);
            showError(STR.invalidLink || 'Errore caricamento');
        });
    }

    window.crewEditSubmit = function () {
        var btn = $('btn-save');
        var resultBox = $('crew-edit-result');
        resultBox.innerHTML = '';
        btn.disabled = true;
        btn.textContent = STR.saving || 'Invio…';

        var payload = { uuid: UUID, t: TOKEN, honeypot_url: $('f-honeypot').value };
        FIELDS.forEach(function (f) {
            payload[f] = $('f-' + f).value.trim();
        });

        // P.IVA (2026-07-23)
        var pivaChkS = $('f-ha_partita_iva');
        payload.ha_partita_iva = (pivaChkS && pivaChkS.checked) ? 1 : 0;
        if (!payload.ha_partita_iva) payload.anno_partita_iva = '';

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
                    showResult('ok', (STR.successMsg || 'Modifiche inviate') + ' (' + d.changes_count + ')');
                    // Re-load per vedere nuovo stato pending
                    setTimeout(loadData, 1200);
                }
            } else {
                showResult('err', (STR.errorPrefix || 'Errore: ') + (d.message || d.error || 'unknown'));
            }
        })
        .catch(function (err) {
            showResult('err', (STR.errorPrefix || 'Errore: ') + 'rete');
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = STR.save || 'Invia';
        });
    };

    function showResult(type, text) {
        $('crew-edit-result').innerHTML = '<div class="crew-edit-result ' + type + '">' + escapeHtml(text) + '</div>';
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c];
        });
    }

    // ─── S6.5 Foto upload ─────────────────────────────────────
    function setupFotoUpload() {
        var btn   = $('f-foto-btn');
        var input = $('f-foto-input');
        if (!btn || !input) return;

        btn.addEventListener('click', function () { input.click(); });

        input.addEventListener('change', function (e) {
            var file = e.target.files && e.target.files[0];
            if (!file) return;
            var status = $('f-foto-status');

            // S6.5-LEGAL: entrambe le checkbox obbligatorie
            var legalChk  = $('f-legal');
            var veritaChk = $('f-verita');
            if (!legalChk || !veritaChk || !legalChk.checked || !veritaChk.checked) {
                status.textContent = '✗ Devi accettare disclaimer legale e dichiarazione veridicità prima di caricare';
                status.className = 'crew-edit-foto-status err';
                input.value = '';
                return;
            }

            status.textContent = '⏳ Caricamento e ottimizzazione…';
            status.className = 'crew-edit-foto-status loading';
            btn.disabled = true;

            // Sanity check size (20MB pre-resize, mirror del server)
            if (file.size > 20 * 1024 * 1024) {
                status.textContent = '✗ File troppo grande (max 20MB)';
                status.className = 'crew-edit-foto-status err';
                btn.disabled = false;
                input.value = '';
                return;
            }

            var fd = new FormData();
            fd.append('uuid', UUID);
            fd.append('t', TOKEN);
            fd.append('foto', file);
            fd.append('dichiarazione_legale', '1');
            fd.append('veridicita', '1');

            fetch(API_UPLOAD_FOTO, { method: 'POST', body: fd, credentials: 'same-origin' })
                .then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.ok) {
                        status.textContent = '✓ ' + (res.message || (STR.fotoOk || 'Foto caricata, in attesa approvazione staff'));
                        status.className = 'crew-edit-foto-status ok';
                        // NON aggiorna preview visibile (la foto è pending). Re-load per mostrare pending notice.
                        setTimeout(loadData, 1200);
                    } else {
                        status.textContent = '✗ ' + (res.message || res.error || (STR.fotoErr || 'Errore upload'));
                        status.className = 'crew-edit-foto-status err';
                    }
                })
                .catch(function (err) {
                    status.textContent = '✗ Errore di rete: ' + err.message;
                    status.className = 'crew-edit-foto-status err';
                })
                .finally(function () {
                    btn.disabled = false;
                    input.value = ''; // reset così re-select dello stesso file ri-triggera change
                });
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadData();
        setupFotoUpload();
        var pivaChk = $('f-ha_partita_iva');
        if (pivaChk) pivaChk.addEventListener('change', togglePivaYear);
        var _bio = $('f-bio');
        if (_bio) _bio.addEventListener('input', updateBioCounter);
        setupPortfolioUpload();
        setupConsenso();
    });
})();
