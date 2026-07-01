/**
 * crew-self-edit.js — v1.0 (2026-05-19, S6)
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
    var UUID  = cfg.uuid  || '';
    var TOKEN = cfg.token || '';
    var STR   = cfg.strings || {};

    // FIX 2026-07-01 marco — aggiunti comune + provincia (geo self-edit crew)
    var FIELDS = ['telefono','email','bio','instagram','tiktok','sito_web','comune_residenza','provincia_domicilio'];

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
                o.textContent = p.name + ' (' + p.code + ')';
                sel.appendChild(o);
            });
            if (selected) {
                sel.value = selected;
                if (sel.value !== selected) {
                    var o2 = document.createElement('option');
                    o2.value = selected; o2.textContent = selected;
                    sel.appendChild(o2); sel.value = selected;
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
                        status.textContent = '✓ ' + (res.message || 'Foto caricata, in attesa approvazione staff');
                        status.className = 'crew-edit-foto-status ok';
                        // NON aggiorna preview visibile (la foto è pending). Re-load per mostrare pending notice.
                        setTimeout(loadData, 1200);
                    } else {
                        status.textContent = '✗ ' + (res.message || res.error || 'Errore upload');
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
    });
})();
