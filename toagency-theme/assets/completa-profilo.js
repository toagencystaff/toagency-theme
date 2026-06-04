/**
 * completa-profilo.js — Step 2B Fase 2 registrazione talent
 * v1.0 — 2026-06-03 marco
 *
 * Carica il profilo via completa-profilo-load.php, pre-compila il form,
 * gestisce chip multi-select (etnia max 2 / ruoli / lingue) col pattern NATIVE-toggle
 * iOS-safe (checkbox accessible-hidden, si reagisce al change nativo, si specchia .checked),
 * calcola la barra avanzamento e salva LIVE via completa-profilo-save.php.
 */
(function () {
    'use strict';

    var cfg = window.completaProfiloConfig || {};
    var S = cfg.strings || {};
    var UUID = cfg.uuid || '';
    var TOKEN = cfg.token || '';

    var elStatus = document.getElementById('cp-status');
    var elForm   = document.getElementById('cp-form');

    function showStatusError(msg) {
        if (!elStatus) return;
        elStatus.textContent = msg;
        elStatus.className = 'cp-status error';
        elStatus.style.display = 'block';
        if (elForm) elForm.classList.remove('visible');
    }

    if (!UUID || !TOKEN) { showStatusError(S.invalidLink || 'Link non valido.'); return; }

    // ── Campi obbligatori per il completamento (allineati al backend) ──
    var REQUIRED = ['altezza', 'taglia', 'capelli', 'occhi', 'etnia', 'ruoli'];

    function chipsContainer(group) { return document.querySelector('.cp-chips[data-group="' + group + '"]'); }
    function checkedValues(group) {
        var box = chipsContainer(group);
        if (!box) return [];
        return Array.prototype.slice.call(box.querySelectorAll('input:checked')).map(function (i) { return i.value; });
    }
    function val(id) { var e = document.getElementById(id); return e ? (e.value || '').trim() : ''; }

    // ── Barra avanzamento ──
    function fieldFilled(f) {
        if (f === 'etnia' || f === 'ruoli') return checkedValues(f).length > 0;
        return val('f-' + f) !== '';
    }
    function updateProgress() {
        var filled = 0;
        REQUIRED.forEach(function (f) { if (fieldFilled(f)) filled++; });
        var pct = Math.round((filled / REQUIRED.length) * 100);
        var fill = document.getElementById('cp-progress-fill');
        var lab  = document.getElementById('cp-progress-pct');
        if (fill) fill.style.width = pct + '%';
        if (lab)  lab.textContent = pct + '%';
        return pct;
    }

    // ── Chip multi-select: pattern native-toggle + max opzionale ──
    function initChips(group) {
        var box = chipsContainer(group);
        if (!box) return;
        var max = parseInt(box.getAttribute('data-max') || '0', 10);
        var chips = Array.prototype.slice.call(box.querySelectorAll('.cp-chip'));
        function refresh() {
            var checkedCount = 0;
            chips.forEach(function (chip) {
                var cb = chip.querySelector('input');
                chip.classList.toggle('checked', cb.checked);
                if (cb.checked) checkedCount++;
            });
            if (max > 0) {
                chips.forEach(function (chip) {
                    var cb = chip.querySelector('input');
                    var lock = (checkedCount >= max) && !cb.checked;
                    cb.disabled = lock;
                    chip.classList.toggle('disabled', lock);
                });
            }
            box.classList.remove('cp-missing');
            updateProgress();
        }
        chips.forEach(function (chip) {
            var cb = chip.querySelector('input');
            if (!cb) return;
            cb.addEventListener('change', refresh);
        });
        refresh();
    }
    ['etnia', 'ruoli', 'lingue'].forEach(initChips);

    // Ricalcola avanzamento al variare dei campi base
    ['altezza', 'taglia', 'capelli', 'occhi'].forEach(function (f) {
        var e = document.getElementById('f-' + f);
        if (e) { e.addEventListener('input', updateProgress); e.addEventListener('change', updateProgress); }
    });

    // ── Set helpers per il prefill ──
    function setVal(id, v) { var e = document.getElementById(id); if (e && v !== null && v !== undefined && v !== '') e.value = v; }
    function setChips(group, csv) {
        if (!csv) return;
        var box = chipsContainer(group);
        if (!box) return;
        var vals = String(csv).split(',').map(function (s) { return s.trim(); }).filter(Boolean);
        vals.forEach(function (v) {
            var cb = box.querySelector('input[value="' + v + '"]');
            if (cb) cb.checked = true;
        });
    }
    // FIX 2026-06-04 marco — collassa i token gendered salvati sui valori chip unificati
    function collapseRuoli(csv) {
        if (!csv) return '';
        var map = {
            model_f: 'model', model_m: 'model', model: 'model',
            actor_f: 'actor', actor_m: 'actor', actor: 'actor',
            hostess: 'hostess_steward', steward: 'hostess_steward'
        };
        var out = String(csv).split(',').map(function (s) {
            s = s.trim().toLowerCase();
            return map[s] || s;
        }).filter(Boolean);
        return out.filter(function (v, i) { return out.indexOf(v) === i; }).join(','); // dedupe
    }

    // ── LOAD ──
    fetch(cfg.apiLoad + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN), {
        method: 'GET', credentials: 'same-origin'
    })
    .then(function (r) { return r.json(); })
    .then(function (d) {
        if (!d || !d.success) { showStatusError(S.invalidLink || 'Link non valido.'); return; }

        var t = d.talent || {};
        setVal('f-altezza', t.altezza);
        setVal('f-taglia', t.taglia);
        setVal('f-scarpe', t.scarpe);
        setVal('f-peso', t.peso);
        setVal('f-sesso', t.sesso);
        setVal('f-capelli', t.capelli);
        setVal('f-lunghezza', t.lunghezza_capelli);
        setVal('f-occhi', t.occhi);
        setVal('f-instagram', t.instagram);
        setVal('f-tiktok', t.tiktok);
        setChips('etnia', t.etnia);
        // FIX 2026-06-04 marco — i ruoli salvati sono gendered (model_f/…): collassali sui chip unificati
        setChips('ruoli', collapseRuoli(t.ruoli));
        setChips('lingue', t.lingue);
        var pat = document.getElementById('f-patente');
        if (pat && parseInt(t.patente, 10) === 1) pat.checked = true;

        var nameEl = document.getElementById('cp-name-display');
        if (nameEl) nameEl.innerHTML = 'Profilo di <strong>' + (t.nome || '') + ' ' + (t.cognome || '') + '</strong>';

        if (d.completo) {
            var notice = document.getElementById('cp-notice');
            if (notice) { notice.textContent = S.alreadyDone || ''; notice.style.display = 'block'; }
        }

        // re-sync chips + progress dopo prefill
        ['etnia', 'ruoli', 'lingue'].forEach(function (group) {
            var box = chipsContainer(group);
            if (box) box.querySelectorAll('.cp-chip').forEach(function (chip) {
                chip.classList.toggle('checked', chip.querySelector('input').checked);
            });
        });
        updateProgress();

        if (elStatus) elStatus.style.display = 'none';
        if (elForm) elForm.classList.add('visible');
    })
    .catch(function () { showStatusError(S.invalidLink || 'Errore di rete.'); });

    // ── SUBMIT ──
    function showResult(cls, msg) {
        var box = document.getElementById('cp-result');
        if (!box) return;
        box.className = 'cp-result ' + cls;
        box.textContent = msg;
        box.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    function markMissing() {
        var missing = [];
        REQUIRED.forEach(function (f) {
            var filled = fieldFilled(f);
            var node = (f === 'etnia' || f === 'ruoli') ? chipsContainer(f) : document.getElementById('f-' + f);
            if (node) node.classList.toggle('cp-missing', !filled);
            if (!filled) missing.push(f);
        });
        return missing;
    }

    window.completaProfiloSubmit = function () {
        var missing = markMissing();
        if (missing.length) { showResult('err', S.errRequired || 'Compila i campi obbligatori.'); return; }

        var btn = document.getElementById('cp-btn-save');
        if (btn) { btn.disabled = true; btn.textContent = S.saving || 'Salvataggio…'; }

        var payload = {
            uuid: UUID, t: TOKEN,
            sesso: val('f-sesso'),
            altezza: val('f-altezza'),
            taglia: val('f-taglia'),
            scarpe: val('f-scarpe'),
            peso: val('f-peso'),
            capelli: val('f-capelli'),
            lunghezza_capelli: val('f-lunghezza'),
            occhi: val('f-occhi'),
            etnia: checkedValues('etnia'),
            ruoli: checkedValues('ruoli'),
            lingue: checkedValues('lingue'),
            patente: (document.getElementById('f-patente') && document.getElementById('f-patente').checked) ? 1 : 0,
            instagram: val('f-instagram'),
            tiktok: val('f-tiktok'),
            honeypot_url: val('f-honeypot')
        };

        fetch(cfg.apiSave, {
            method: 'POST', credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d && d.success) {
                updateProgress();
                showResult('ok', S.successMsg || 'Profilo completato!');
                var comm = document.getElementById('cp-community');
                if (comm) comm.style.display = 'block';
                if (btn) { btn.textContent = '✓'; }
            } else {
                if (btn) { btn.disabled = false; btn.textContent = S.save || 'Completa'; }
                var msg = (d && d.message) ? d.message : ((S.errorPrefix || 'Errore: ') + (d && d.error ? d.error : '?'));
                showResult('err', msg);
            }
        })
        .catch(function () {
            if (btn) { btn.disabled = false; btn.textContent = S.save || 'Completa'; }
            showResult('err', (S.errorPrefix || 'Errore: ') + 'network');
        });
    };
})();
