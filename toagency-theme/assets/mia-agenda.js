/**
 * mia-agenda.js — v1.0 (2026-08-06, STEP 3)
 * Popola calendario (60gg) + basi temporanee da actions/dispo-load.php.
 * Nessun salvataggio in questa versione (dispo-save.php arriva in uno step successivo).
 */
(function () {
    'use strict';

    var cfg   = window.__MA_CONFIG || {};
    var UUID  = cfg.uuid  || '';
    var TOKEN = cfg.token || '';
    var API_LOAD = cfg.apiLoad;
    var STR = cfg.strings || {};

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

    // Stati che il talent NON può mai toccare (scritti solo dallo staff)
    var STATI_BLOCCATI = { opzionato: true, confermato: true };
    // Raggruppamento per il pallino colorato in legenda/riga
    function dotClassFor(stato) {
        if (!stato) return 'non_so';
        if (stato === 'disponibile') return 'disponibile';
        if (stato === 'non_disponibile') return 'non_disponibile';
        if (stato === 'mattina' || stato === 'pomeriggio' || stato === 'sera') return 'parziale';
        if (STATI_BLOCCATI[stato]) return 'bloccato';
        return 'non_so';
    }

    function renderCalendario(d) {
        var wdShort = STR.wdShort || ['D','L','M','M','G','V','S'];
        var moShort = STR.moShort || [];
        var dayState = STR.dayState || {};
        var oggi = parseYmd(d.oggi);
        var orizzonte = d.orizzonte_giorni || 60;

        // Lookup rapido data -> riga calendario
        var byDate = {};
        (d.calendario || []).forEach(function (row) { byDate[row.data] = row; });

        var html = [];
        for (var i = 0; i < orizzonte; i++) {
            var day = new Date(oggi.getFullYear(), oggi.getMonth(), oggi.getDate() + i);
            var ymd = toYmd(day);
            var row = byDate[ymd] || null;
            var stato = row ? row.stato : null;
            var bloccato = stato && STATI_BLOCCATI[stato];
            var label = dayState[stato] || dayState.non_so || '—';

            html.push(
                '<div class="ma-day-row' + (bloccato ? ' locked' : '') + '" data-date="' + ymd + '">' +
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
    }

    function renderBasi(basi) {
        var list = basi || [];
        if (!list.length) {
            $('ma-basi-list').innerHTML = '<div class="ma-basi-empty">' + escapeHtml(STR.basiEmpty || '') + '</div>';
            return;
        }
        var wdShort = STR.wdShort || [];
        var moShort = STR.moShort || [];
        var periodTpl = STR.basiPeriod || 'dal %s al %s';

        var html = list.map(function (b) {
            var dal = b.valida_dal ? formatDay(parseYmd(b.valida_dal), wdShort, moShort) : '';
            var al  = b.valida_al  ? formatDay(parseYmd(b.valida_al), wdShort, moShort) : '';
            var periodo = periodTpl.replace('%s', dal).replace('%s', al);
            var luogo = [b.comune, b.provincia].filter(Boolean).join(' (') + (b.provincia ? ')' : '');
            return (
                '<div class="ma-basi-item" data-id="' + escapeHtml(b.id) + '">' +
                    '<div class="ma-basi-item-info">' +
                        escapeHtml(luogo || b.comune || '—') +
                        (b.alloggio_ok ? ' · 🏠 ' + escapeHtml(STR.basiAlloggio || '') : '') +
                        '<div class="ma-basi-item-dates">' + escapeHtml(periodo) + '</div>' +
                    '</div>' +
                '</div>'
            );
        });
        $('ma-basi-list').innerHTML = html.join('');
    }

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

    document.addEventListener('DOMContentLoaded', loadData);
})();
