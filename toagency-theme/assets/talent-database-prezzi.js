/* TOA #722 (2026-10-24) — Prezzi indicativi per città nel Talent Database, solo con categoria hostess/steward.
   File isolato: non tocca talent-database-v76.js. Le cifre arrivano SOLO dal CRM (endpoint pubblico
   prezzi-citta-public.php): il tema non scrive e non calcola nessun prezzo. Prezzo al cliente, 6 ore, a persona, + IVA. */
(function () {
    'use strict';

    var LANG = window.toaTdbLang || 'it';
    if (['it', 'en', 'fr', 'es'].indexOf(LANG) < 0) LANG = 'it';
    var ENDPOINT = 'https://toagency.it/crm_toagency/actions/prezzi-citta-public.php';
    var MAIL = 'business@toagency.it';

    var T = {
        pick:   { it: 'Scegli la città', en: 'Choose a city', fr: 'Choisissez une ville', es: 'Elige una ciudad' },
        none:   { it: 'Per questa zona non abbiamo ancora un prezzo indicativo.', en: 'We do not have a guide price for this area yet.', fr: 'Pas encore de prix indicatif pour cette zone.', es: 'Todavía no tenemos un precio orientativo para esta zona.' },
        quote:  { it: 'Scrivici per un preventivo', en: 'Write to us for a quote', fr: 'Écrivez-nous pour un devis', es: 'Escríbenos para un presupuesto' }
    };
    var CITY = {
        roma:    { en: 'Rome', fr: 'Rome', es: 'Roma' },
        milano:  { en: 'Milan', fr: 'Milan', es: 'Milán' },
        torino:  { en: 'Turin', fr: 'Turin', es: 'Turín' },
        genova:  { en: 'Genoa', fr: 'Gênes', es: 'Génova' },
        bologna: { en: 'Bologna', fr: 'Bologne', es: 'Bolonia' }
    };

    function tx(k) { return (T[k] && (T[k][LANG] || T[k].it)) || ''; }
    function norm(s) {
        s = String(s || '').toLowerCase().trim();
        try { s = s.normalize('NFD').replace(/[̀-ͯ]/g, ''); } catch (e) {}
        return s;
    }
    function cityName(c) {
        var m = CITY[norm(c.citta)];
        return (m && m[LANG]) || c.citta;
    }
    function money(n) { return String(Number(n)); }
    function fmt(c) {
        var p = money(c.prezzo), da = money(c.da), a = money(c.a);
        if (da === a) return p + ' €';
        var r = { it: 'da ' + da + ' a ' + a + ' €', en: 'from €' + da + ' to €' + a, fr: 'de ' + da + ' à ' + a + ' €', es: 'de ' + da + ' a ' + a + ' €' }[LANG];
        var u = { it: 'di solito ' + p + ' €', en: 'usually €' + p, fr: 'en général ' + p + ' €', es: 'normalmente ' + p + ' €' }[LANG];
        return r + ' · ' + u;
    }

    var data = null, loadState = 0;   // 0 = non richiesto, 1 = in corso, 2 = ok, 3 = errore
    var box = null, lastSig = '', chosen = '', timer = null;

    function injectCss() {
        if (document.getElementById('tdbPrezziCss')) return;
        var st = document.createElement('style');
        st.id = 'tdbPrezziCss';
        st.textContent =
            '.toa-tdb-prezzi{margin:0 0 14px;padding:12px 14px;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.04);border-radius:8px;color:#e8e8e6;font-size:14px;line-height:1.45}' +
            '.toa-tdb-prezzi[hidden]{display:none}' +
            '.toa-tdb-prezzi-h{margin:0 0 8px;font-size:13px;font-weight:700;letter-spacing:.3px;color:#c8ff00;text-transform:uppercase}' +
            '.toa-tdb-prezzi-row{display:flex;flex-wrap:wrap;gap:2px 10px;margin:0 0 6px;align-items:baseline}' +
            '.toa-tdb-prezzi-city{font-weight:700;color:#fff}' +
            '.toa-tdb-prezzi-val{color:#e8e8e6}' +
            '.toa-tdb-prezzi-note{margin:8px 0 0;font-size:12.5px;color:#bdbdbd}' +
            '.toa-tdb-prezzi-sel{max-width:100%;padding:8px 10px;margin:0 0 8px;font:inherit;font-size:14px;color:#fff;background:#111;border:1px solid rgba(255,255,255,.25);border-radius:6px}' +
            '.toa-tdb-prezzi-cta{color:#c8ff00;font-weight:700;text-decoration:none;white-space:nowrap}' +
            '.toa-tdb-prezzi-cta:hover{text-decoration:underline}' +
            '@media(max-width:480px){.toa-tdb-prezzi{padding:10px 12px;font-size:13.5px}.toa-tdb-prezzi-row{flex-direction:column;gap:0}}';
        document.head.appendChild(st);
    }

    function ensureBox() {
        if (box && document.body.contains(box)) return box;
        var anchor = document.getElementById('tdbActiveFilters');
        if (!anchor || !anchor.parentNode) return null;
        injectCss();
        box = document.createElement('div');
        box.id = 'tdbPrezzi';
        box.className = 'toa-tdb-prezzi';
        box.hidden = true;
        box.setAttribute('aria-live', 'polite');
        anchor.parentNode.insertBefore(box, anchor);
        return box;
    }

    function isHostess() {
        var sel = document.getElementById('tdbFilterRuolo');
        return !!(sel && sel.value === 'hostess');
    }
    function selectedProvinces() {
        var out = [];
        new URLSearchParams(window.location.search).getAll('province').forEach(function (raw) {
            raw.split(',').forEach(function (p) { p = p.trim(); if (p) out.push(p); });
        });
        return out;
    }
    function foreignCountry() {
        var c = document.getElementById('tdbFilterCountry');
        var v = c ? norm(c.value) : '';
        return !!v && ['it', 'italia', 'italy', 'italie'].indexOf(v) < 0;
    }

    function load() {
        if (loadState !== 0) return;
        loadState = 1;
        fetch(ENDPOINT, { credentials: 'omit' }).then(function (r) { return r.json(); }).then(function (d) {
            if (d && d.success && d.citta && d.citta.length && d.testi) { data = d; loadState = 2; } else { loadState = 3; }
            refresh();
        }).catch(function () { loadState = 3; refresh(); });
    }

    function el(tag, cls, text) {
        var e = document.createElement(tag);
        if (cls) e.className = cls;
        if (text !== undefined) e.textContent = text;
        return e;
    }
    function row(c) {
        var r = el('div', 'toa-tdb-prezzi-row');
        r.appendChild(el('span', 'toa-tdb-prezzi-city', cityName(c)));
        r.appendChild(el('span', 'toa-tdb-prezzi-val', fmt(c)));
        return r;
    }

    function refresh() {
        var b = ensureBox();
        if (!b) return;
        if (!isHostess()) { b.hidden = true; lastSig = ''; return; }
        if (loadState === 0) { load(); return; }
        if (loadState !== 2) { b.hidden = true; return; }   // in corso o errore: niente cifre, niente riquadro

        var testi = data.testi[LANG] || data.testi.it || {};
        var provs = selectedProvinces();
        var foreign = foreignCountry();
        var matches = [];
        if (!foreign) {
            provs.forEach(function (p) {
                data.citta.forEach(function (c) {
                    if (norm(c.citta) === norm(p) && matches.indexOf(c) < 0) matches.push(c);
                });
            });
        }
        var mode = matches.length ? 'cities' : ((provs.length || foreign) ? 'contact' : 'menu');
        var sig = [LANG, mode, matches.map(function (c) { return c.sigla; }).join(','), mode === 'menu' ? chosen : ''].join('|');
        b.hidden = false;
        if (sig === lastSig) return;
        lastSig = sig;

        b.innerHTML = '';
        b.appendChild(el('div', 'toa-tdb-prezzi-h', testi.titolo || ''));

        if (mode === 'cities') {
            matches.forEach(function (c) { b.appendChild(row(c)); });
            b.appendChild(el('p', 'toa-tdb-prezzi-note', testi.nota || ''));
        } else if (mode === 'contact') {
            b.appendChild(el('span', 'toa-tdb-prezzi-val', tx('none') + ' '));
            var a = el('a', 'toa-tdb-prezzi-cta', tx('quote') + ' →');
            a.href = 'mailto:' + MAIL;
            b.appendChild(a);
        } else {
            var s = el('select', 'toa-tdb-prezzi-sel');
            s.setAttribute('aria-label', tx('pick'));
            var o0 = el('option', '', tx('pick')); o0.value = ''; s.appendChild(o0);
            data.citta.forEach(function (c) {
                var o = el('option', '', cityName(c)); o.value = c.sigla;
                if (c.sigla === chosen) o.selected = true;
                s.appendChild(o);
            });
            s.addEventListener('change', function () { chosen = s.value; lastSig = ''; refresh(); });
            b.appendChild(s);
            var cc = null;
            data.citta.forEach(function (c) { if (c.sigla === chosen) cc = c; });
            if (cc) {
                b.appendChild(row(cc));
                b.appendChild(el('p', 'toa-tdb-prezzi-note', testi.nota || ''));
            }
        }
    }

    function later() { clearTimeout(timer); timer = setTimeout(refresh, 150); }

    function boot() {
        var sel = document.getElementById('tdbFilterRuolo');
        if (sel) sel.addEventListener('change', later);
        var cnt = document.getElementById('tdbResultsCount');
        if (cnt && window.MutationObserver) new MutationObserver(later).observe(cnt, { childList: true, characterData: true, subtree: true });
        window.addEventListener('popstate', later);
        window.addEventListener('load', function () { setTimeout(refresh, 700); });
        later();
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot); else boot();
})();
