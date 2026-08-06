/**
 * mia-agenda.js — v1.1 (2026-08-06, STEP 4)
 * v1.0 (STEP 3): popola calendario 60gg + basi da dispo-load.php (sola lettura).
 * v1.1 (STEP 4): tap giorno -> picker 5 stati, salvataggio batch su dispo-save.php,
 *                form aggiungi base + elimina base.
 */
(function () {
    'use strict';

    var cfg   = window.__MA_CONFIG || {};
    var UUID  = cfg.uuid  || '';
    var TOKEN = cfg.token || '';
    var API_LOAD = cfg.apiLoad;
    var API_SAVE = cfg.apiSave;
    var STR = cfg.strings || {};

    var STATI_SCRIVIBILI = ['disponibile', 'non_disponibile', 'mattina', 'pomeriggio', 'sera'];
    var STATI_BLOCCATI = { opzionato: true, confermato: true };

    var pendingChanges = {}; // { 'YYYY-MM-DD': 'stato' }
    var openPickerDate = null;

    function $(id) { return document.getElementById(id); }

    function escapeHtml(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[c];
        });
    }

    function showError(msg) {
        var st = $('ma-status');
        st.textContent = msg;
        st.classList.add('error');
    }

    // "YYYY-MM-DD" -> Date locale a mezzanotte (evita problemi di fuso con new Date(string))
    function parseYmd(ymd) {
        var p = String(ymd).split('-');
        return new Date(parseInt(p[0], 10), parseInt(p[1], 10) - 1, parseInt(p[2], 10));
    }
    function toYmd(d) {
        var mm = ('0' + (d.getMonth() + 1)).slice(-2);
        var dd = ('0' + d.getDate()).slice(-2);
        return d.getFullYear() + '-' + mm + '-' + dd;
    }
    function formatDay(d, wdShort, moShort) {
        return wdShort[d.getDay()] + ' ' + d.getDate() + ' ' + moShort[d.getMonth()];
    }
    function daysBetween(a, b) {
        return Math.round((b - a) / 86400000);
    }

    function dotClassFor(stato) {
        if (!stato) return 'non_so';
        if (stato === 'disponibile') return 'disponibile';
        if (stato === 'non_disponibile') return 'non_disponibile';
        if (stato === 'mattina' || stato === 'pomeriggio' || stato === 'sera') return 'parziale';
        if (STATI_BLOCCATI[stato]) return 'bloccato';
        return 'non_so';
    }

    // ─── Calendario ───

    var lastLoadData = null; // ultima risposta dispo-load.php (per re-render locale dopo scelta picker)

    function effectiveStato(ymd, byDate) {
        if (pendingChanges.hasOwnProperty(ymd)) return pendingChanges[ymd];
        var row = byDate[ymd];
        return row ? row.stato : null;
    }

    function renderCalendario(d) {
        lastLoadData = d;
        var wdShort = STR.wdShort || ['D','L','M','M','G','V','S'];
        var moShort = STR.moShort || [];
        var dayState = STR.dayState || {};
        var oggi = parseYmd(d.oggi);
        var orizzonte = d.orizzonte_giorni || 60;

        var byDate = {};
        (d.calendario || []).forEach(function (row) { byDate[row.data] = row; });
        window.__MA_BY_DATE = byDate; // usato anche da openPicker/selectState

        var html = [];
        for (var i = 0; i < orizzonte; i++) {
            var day = new Date(oggi.getFullYear(), oggi.getMonth(), oggi.getDate() + i);
            var ymd = toYmd(day);
            var stato = effectiveStato(ymd, byDate);
            var bloccato = stato && STATI_BLOCCATI[stato];
            var isPending = pendingChanges.hasOwnProperty(ymd);
            var label = dayState[stato] || dayState.non_so || '—';

            html.push(
                '<div class="ma-day-row' + (bloccato ? ' locked' : '') + (isPending ? ' pending' : '') + '" data-date="' + ymd + '">' +
                    '<div class="ma-day-date">' + escapeHtml(formatDay(day, wdShort, moShort)) + '</div>' +
                    '<div class="ma-day-state">' +
                        '<span class="ma-dot ma-dot-' + dotClassFor(stato) + '"></span>' +
                        escapeHtml(label) +
                        (bloccato ? ' 🔒' : '') +
                    '</div>' +
                '</div>'
            );
        }
        $('ma-day-list').innerHTML = html.join('');

        document.querySelectorAll('#ma-day-list .ma-day-row:not(.locked)').forEach(function (row) {
            row.addEventListener('click', function () { toggleDayPicker(row); });
        });

        updateSaveButtonState();
    }

    function toggleDayPicker(rowEl) {
        var ymd = rowEl.getAttribute('data-date');
        var existing = rowEl.parentNode.querySelector('.ma-day-picker');

        if (existing) {
            existing.remove();
            if (openPickerDate === ymd) { openPickerDate = null; return; }
        }
        openPickerDate = ymd;

        var dayState = STR.dayState || {};
        var picker = document.createElement('div');
        picker.className = 'ma-day-picker';
        picker.innerHTML = STATI_SCRIVIBILI.map(function (stato) {
            return '<button type="button" class="ma-picker-btn" data-stato="' + stato + '">' +
                       '<span class="ma-dot ma-dot-' + dotClassFor(stato) + '"></span>' +
                       escapeHtml(dayState[stato] || stato) +
                   '</button>';
        }).join('');

        picker.querySelectorAll('.ma-picker-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                pendingChanges[ymd] = btn.getAttribute('data-stato');
                openPickerDate = null;
                renderCalendario(lastLoadData);
            });
        });

        rowEl.insertAdjacentElement('afterend', picker);
    }

    function updateSaveButtonState() {
        var btn = $('ma-btn-save');
        btn.disabled = Object.keys(pendingChanges).length === 0;
    }

    function renderSkipped(skipped) {
        var box = $('ma-skip-msg');
        if (!skipped || !skipped.length) { box.style.display = 'none'; box.innerHTML = ''; return; }
        var reasons = STR.skipReasons || {};
        var items = skipped.map(function (s) {
            var motivo = reasons[s.motivo] || s.motivo || '';
            return escapeHtml(s.data) + ' — ' + escapeHtml(motivo);
        });
        box.innerHTML = escapeHtml(STR.skipPrefix || '') + '<br>' + items.join('<br>');
        box.style.display = 'block';
    }

    function doSave() {
        var giorni = Object.keys(pendingChanges).map(function (data) {
            return { data: data, stato: pendingChanges[data] };
        });
        if (!giorni.length) return;

        var btn = $('ma-btn-save');
        var msg = $('ma-save-msg');
        btn.disabled = true;
        btn.textContent = STR.btnSaving || '...';
        msg.textContent = '';
        msg.className = 'ma-save-msg';

        fetch(API_SAVE, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uuid: UUID, t: TOKEN, giorni: giorni })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            btn.textContent = STR.btnSave || 'Salva';
            if (!d || !d.success) {
                msg.textContent = STR.saveErr || 'Errore';
                msg.className = 'ma-save-msg err';
                btn.disabled = false;
                return;
            }
            pendingChanges = {};
            msg.textContent = STR.saveOk || 'OK';
            msg.className = 'ma-save-msg ok';
            renderSkipped(d.skipped);
            loadData(); // ricarica stato vero dal server (giorni salvati + eventuali bloccati)
        })
        .catch(function () {
            btn.textContent = STR.btnSave || 'Salva';
            msg.textContent = STR.saveErr || 'Errore';
            msg.className = 'ma-save-msg err';
            btn.disabled = false;
        });
    }

    // ─── Basi temporanee ───

    function renderBasi(basi) {
        var list = basi || [];
        var wdShort = STR.wdShort || [];
        var moShort = STR.moShort || [];
        var periodTpl = STR.basiPeriod || 'dal %s al %s';

        var itemsHtml;
        if (!list.length) {
            itemsHtml = '<div class="ma-basi-empty">' + escapeHtml(STR.basiEmpty || '') + '</div>';
        } else {
            itemsHtml = list.map(function (b) {
                var dal = b.valida_dal ? formatDay(parseYmd(b.valida_dal), wdShort, moShort) : '';
                var al  = b.valida_al  ? formatDay(parseYmd(b.valida_al), wdShort, moShort) : '';
                var periodo = periodTpl.replace('%s', dal).replace('%s', al);
                var luogo = b.provincia ? (b.comune + ' (' + b.provincia + ')') : (b.comune || '—');
                return (
                    '<div class="ma-basi-item" data-id="' + escapeHtml(b.id) + '">' +
                        '<div class="ma-basi-item-info">' +
                            escapeHtml(luogo) +
                            (b.alloggio_ok ? ' · 🏠 ' + escapeHtml(STR.basiAlloggio || '') : '') +
                            '<div class="ma-basi-item-dates">' + escapeHtml(periodo) + '</div>' +
                        '</div>' +
                        '<button type="button" class="ma-btn-del" data-id="' + escapeHtml(b.id) + '">' + escapeHtml(STR.basiDel || '×') + '</button>' +
                    '</div>'
                );
            }).join('');
        }
        $('ma-basi-list').innerHTML = itemsHtml;

        document.querySelectorAll('#ma-basi-list .ma-btn-del').forEach(function (btn) {
            btn.addEventListener('click', function () { deleteBase(btn.getAttribute('data-id')); });
        });
    }

    function deleteBase(id) {
        if (!window.confirm(STR.basiConfirmDelete || '?')) return;
        fetch(API_SAVE, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uuid: UUID, t: TOKEN, basi_delete_id: parseInt(id, 10) })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) { if (d && d.success) loadData(); })
        .catch(function () {});
    }

    function toggleAddBaseForm() {
        var container = $('ma-basi-section');
        var existing = container.querySelector('.ma-basi-form');
        if (existing) { existing.remove(); return; }

        var today = toYmd(new Date());
        var form = document.createElement('div');
        form.className = 'ma-basi-form';
        form.innerHTML =
            '<div class="ma-form-err" id="ma-basi-err"></div>' +
            '<div class="ma-field">' +
                '<label class="ma-label">' + escapeHtml(STR.basiFormComune || '') + '</label>' +
                '<input type="text" class="ma-input" id="ma-f-comune" maxlength="100">' +
            '</div>' +
            '<div class="ma-row-2">' +
                '<div class="ma-field">' +
                    '<label class="ma-label">' + escapeHtml(STR.basiFormProvincia || '') + '</label>' +
                    '<input type="text" class="ma-input" id="ma-f-provincia" maxlength="2" style="text-transform:uppercase">' +
                '</div>' +
                '<div class="ma-field">' +
                    '<label class="ma-label">' + escapeHtml(STR.basiFormPaese || '') + '</label>' +
                    '<input type="text" class="ma-input" id="ma-f-paese" maxlength="2" style="text-transform:uppercase" value="IT">' +
                '</div>' +
            '</div>' +
            '<div class="ma-row-2">' +
                '<div class="ma-field">' +
                    '<label class="ma-label">' + escapeHtml(STR.basiFormDal || '') + '</label>' +
                    '<input type="date" class="ma-input" id="ma-f-dal" min="' + today + '">' +
                '</div>' +
                '<div class="ma-field">' +
                    '<label class="ma-label">' + escapeHtml(STR.basiFormAl || '') + '</label>' +
                    '<input type="date" class="ma-input" id="ma-f-al" min="' + today + '">' +
                '</div>' +
            '</div>' +
            '<div class="ma-field">' +
                '<label class="ma-label">' + escapeHtml(STR.basiFormNota || '') + '</label>' +
                '<input type="text" class="ma-input" id="ma-f-nota" maxlength="200">' +
            '</div>' +
            '<label class="ma-check-row"><input type="checkbox" id="ma-f-alloggio"> ' + escapeHtml(STR.basiFormAlloggio || '') + '</label>' +
            '<div class="ma-form-actions">' +
                '<button type="button" class="ma-btn-secondary" id="ma-basi-cancel">' + escapeHtml(STR.btnCancel || '') + '</button>' +
                '<button type="button" class="ma-btn-save" id="ma-basi-submit">' + escapeHtml(STR.basiFormSave || '') + '</button>' +
            '</div>';

        container.appendChild(form);
        $('ma-basi-cancel').addEventListener('click', toggleAddBaseForm);
        $('ma-basi-submit').addEventListener('click', submitAddBase);
    }

    function submitAddBase() {
        var comune = $('ma-f-comune').value.trim();
        var provincia = $('ma-f-provincia').value.trim().toUpperCase();
        var paese = ($('ma-f-paese').value.trim() || 'IT').toUpperCase();
        var dal = $('ma-f-dal').value;
        var al  = $('ma-f-al').value;
        var nota = $('ma-f-nota').value.trim();
        var alloggio = $('ma-f-alloggio').checked;
        var errBox = $('ma-basi-err');

        if (!comune) {
            errBox.textContent = STR.basiErrComune || 'Comune obbligatorio.';
            errBox.style.display = 'block';
            return;
        }
        if (!dal || !al || parseYmd(al) < parseYmd(dal) || daysBetween(parseYmd(dal), parseYmd(al)) > 90) {
            errBox.textContent = STR.basiErrDate || 'Date non valide.';
            errBox.style.display = 'block';
            return;
        }
        errBox.style.display = 'none';

        var btn = $('ma-basi-submit');
        btn.disabled = true;

        fetch(API_SAVE, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                uuid: UUID, t: TOKEN,
                basi_add: { comune: comune, provincia: provincia, paese: paese, valida_dal: dal, valida_al: al, alloggio_ok: alloggio, nota: nota }
            })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            btn.disabled = false;
            if (!d || !d.success) {
                errBox.textContent = STR.saveErr || 'Errore';
                errBox.style.display = 'block';
                return;
            }
            var form = document.querySelector('.ma-basi-form');
            if (form) form.remove();
            loadData();
        })
        .catch(function () {
            btn.disabled = false;
            errBox.textContent = STR.saveErr || 'Errore';
            errBox.style.display = 'block';
        });
    }

    // ─── Load iniziale ───

    function renderLastUpdate(dispoAggiornato) {
        var el = $('ma-last-update');
        if (!el) return;
        el.textContent = dispoAggiornato
            ? (STR.lastUpdate || '') + ' ' + dispoAggiornato
            : (STR.neverUpdated || '');
    }

    function loadData() {
        if (!UUID || !TOKEN || !API_LOAD) {
            showError(STR.invalidLink || 'Link non valido.');
            return;
        }
        fetch(API_LOAD + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN), {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d || !d.success) { showError(STR.invalidLink || 'Link non valido.'); return; }

            renderLastUpdate(d.dispo_aggiornato);
            renderCalendario(d);
            renderBasi(d.basi_temporanee);

            $('ma-status').style.display = 'none';
            $('ma-body').classList.add('visible');
        })
        .catch(function () {
            showError(STR.errorGeneric || 'Errore di connessione.');
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        loadData();
        $('ma-btn-save').addEventListener('click', doSave);
        $('ma-btn-add-base').addEventListener('click', toggleAddBaseForm);
    });
})();
