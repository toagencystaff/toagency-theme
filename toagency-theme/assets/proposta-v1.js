/**
 * proposta-v1.js — v1.0 2026-08-18 (chat TEMA DISPO-PROPOSTA)
 *
 * Logica della pagina /proposta/?t=TOKEN (templates/page-proposta.php).
 * 1. Legge il token dall'URL e chiama /actions/dispo-proposta-api.php?t=TOKEN
 *    (cache-bust _v= per la Dynamic Cache SiteGround, stesso trucco di
 *    /talent-database/).
 * 2. Riempie hero (badge tipo, titolo, luogo · date · giorni).
 * 3. Renderizza le card con le STESSE classi di talent-database-v81.css
 *    (.toa-tdb-card ecc.) + blocco prezzo .toa-prop-price (i prezzi si
 *    vedono — decisione 18/08).
 * 4. Filtri SOLO client-side sulle card ricevute: sesso, altezza a fasce,
 *    età da/a, lingue (pannello richiudibile) + livello minimo.
 *
 * Robustezza dati (endpoint CRM in costruzione):
 * - lingue accettate come: lingue_liv [{slug,livello,label}] (formato
 *   talent-db), lingue array di oggetti, array di stringhe o stringa "it,en".
 * - il filtro Genere si nasconde da solo se nessuna card ha `sesso`.
 * - prezzi/foto mancanti → il blocco relativo non viene stampato.
 */
(function () {
    'use strict';

    var API  = window.toaPropApiUrl || '/actions/dispo-proposta-api.php';
    var LANG = window.toaPropLang   || 'it';
    var I18N = window.toaPropI18n   || {};
    var LOCALE = ({ it: 'it-IT', en: 'en-GB', fr: 'fr-FR', es: 'es-ES' })[LANG] || 'it-IT';

    function t(k)  { return I18N[k] || k; }
    function $(s)  { return document.querySelector(s); }

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    // ─── Stato ───────────────────────────────────────────────────────
    var P = {
        cards: [],
        filters: { sesso: '', fascia: '', etaMin: '', etaMax: '', lingue: [], livMin: '' }
    };

    // ─── Lingue: parsing difensivo + nomi ────────────────────────────
    var LIV_ORDER = { base: 1, intermedio: 2, fluente: 3, madrelingua: 4 };
    var LANG_CODE = {
        italiano: 'IT', inglese: 'EN', francese: 'FR', spagnolo: 'ES', tedesco: 'DE',
        russo: 'RU', arabo: 'AR', cinese: 'ZH', portoghese: 'PT', giapponese: 'JA',
        olandese: 'NL', polacco: 'PL', rumeno: 'RO', ucraino: 'UK'
    };
    var LANG_NAME = {
        italiano:   { it: 'Italiano',   en: 'Italian',    fr: 'Italien',    es: 'Italiano' },
        inglese:    { it: 'Inglese',    en: 'English',    fr: 'Anglais',    es: 'Inglés' },
        francese:   { it: 'Francese',   en: 'French',     fr: 'Français',   es: 'Francés' },
        spagnolo:   { it: 'Spagnolo',   en: 'Spanish',    fr: 'Espagnol',   es: 'Español' },
        tedesco:    { it: 'Tedesco',    en: 'German',     fr: 'Allemand',   es: 'Alemán' },
        russo:      { it: 'Russo',      en: 'Russian',    fr: 'Russe',      es: 'Ruso' },
        arabo:      { it: 'Arabo',      en: 'Arabic',     fr: 'Arabe',      es: 'Árabe' },
        cinese:     { it: 'Cinese',     en: 'Chinese',    fr: 'Chinois',    es: 'Chino' },
        portoghese: { it: 'Portoghese', en: 'Portuguese', fr: 'Portugais',  es: 'Portugués' },
        giapponese: { it: 'Giapponese', en: 'Japanese',   fr: 'Japonais',   es: 'Japonés' },
        olandese:   { it: 'Olandese',   en: 'Dutch',      fr: 'Néerlandais',es: 'Neerlandés' },
        polacco:    { it: 'Polacco',    en: 'Polish',     fr: 'Polonais',   es: 'Polaco' },
        rumeno:     { it: 'Rumeno',     en: 'Romanian',   fr: 'Roumain',    es: 'Rumano' },
        ucraino:    { it: 'Ucraino',    en: 'Ukrainian',  fr: 'Ukrainien',  es: 'Ucraniano' }
    };
    function langName(slug) {
        var m = LANG_NAME[slug];
        if (m) return m[LANG] || m.it;
        return slug.charAt(0).toUpperCase() + slug.slice(1);
    }
    function langCode(slug) {
        return LANG_CODE[slug] || slug.slice(0, 2).toUpperCase();
    }
    function livLabel(slug) { return t('liv_' + slug); }

    // Normalizza QUALSIASI formato lingue della card in
    // [{slug, code, name, liv, livNum}] (liv = etichetta tradotta o '').
    function cardLangs(c) {
        var raw = null;
        if (c && Array.isArray(c.lingue_liv) && c.lingue_liv.length) raw = c.lingue_liv;
        else if (c && Array.isArray(c.lingue)) raw = c.lingue;
        else if (c && typeof c.lingue === 'string' && c.lingue.trim()) {
            raw = c.lingue.split(',').map(function (s) { return s.trim(); });
        }
        if (!raw) return [];
        return raw.map(function (x) {
            var slug = '', liv = '';
            if (typeof x === 'string') slug = x;
            else if (x && typeof x === 'object') {
                slug = x.slug || x.lingua || x.code || '';
                liv  = x.livello || x.liv || '';
            }
            slug = String(slug).toLowerCase().trim();
            if (!slug) return null;
            liv = String(liv).toLowerCase().trim();
            var isAltro = (slug === 'altro');
            var name = isAltro ? String((x && x.label) || '') : langName(slug);
            if (!name) return null;
            return {
                slug: slug, name: name,
                code: isAltro ? name.slice(0, 2).toUpperCase() : langCode(slug),
                liv:  LIV_ORDER[liv] ? livLabel(liv) : '',
                livNum: LIV_ORDER[liv] || 0
            };
        }).filter(function (x) { return !!x; });
    }

    function sessoNorm(c) {
        var s = String((c && (c.sesso || c.genere)) || '').trim().toLowerCase().charAt(0);
        return (s === 'f' || s === 'm') ? s : '';
    }

    // ─── Formattazioni ───────────────────────────────────────────────
    function fmtPrice(v) {
        v = parseFloat(v);
        if (!isFinite(v) || v <= 0) return '';
        var dec = (v % 1) ? 2 : 0;
        try {
            return new Intl.NumberFormat(LOCALE, {
                style: 'currency', currency: 'EUR',
                minimumFractionDigits: dec, maximumFractionDigits: dec
            }).format(v);
        } catch (e) { return '€ ' + v; }
    }
    function fmtDate(s) {
        if (!s) return '';
        var d = new Date(String(s).slice(0, 10) + 'T12:00:00');
        if (isNaN(d.getTime())) return String(s);
        try {
            return new Intl.DateTimeFormat(LOCALE, { day: 'numeric', month: 'short', year: 'numeric' }).format(d);
        } catch (e) { return String(s).slice(0, 10); }
    }

    // ─── Stati pagina ────────────────────────────────────────────────
    function showError(msg) {
        $('#propLoading').hidden = true;
        $('#propGrid').hidden = true;
        $('#propFilters').hidden = true;
        $('#propErrorMsg').textContent = msg;
        $('#propError').hidden = false;
    }

    // ─── Hero ────────────────────────────────────────────────────────
    function renderHero(d) {
        var tipo = String(d.tipo || '').toLowerCase();
        var badge = $('#propBadge');
        if (tipo === 'ipotetico' || tipo === 'effettivo') {
            badge.textContent = t('badge_' + tipo);
            badge.className = 'toa-prop-badge toa-prop-badge--' + tipo;
            badge.hidden = false;
            $('#propTipoNote').textContent = t('note_' + tipo);
        }
        $('#propTitle').textContent = d.titolo || '';
        document.title = (d.titolo ? d.titolo + ' — ' : '') + 'TOAgency';

        var meta = [];
        if (d.luogo) meta.push('📍 ' + d.luogo);
        var dal = fmtDate(d.dal), al = fmtDate(d.al);
        if (dal) meta.push('📅 ' + (al && al !== dal ? dal + ' → ' + al : dal));
        var g = parseInt(d.giorni, 10);
        if (g > 0) meta.push(g + ' ' + (g === 1 ? t('day_s') : t('day_p')));
        $('#propMeta').textContent = meta.join('  ·  ');
    }

    // ─── Filtri ──────────────────────────────────────────────────────
    function buildFilters() {
        // Genere: visibile solo se almeno una card ha il dato.
        var hasSesso = P.cards.some(function (c) { return !!sessoNorm(c); });
        $('#propFieldSesso').hidden = !hasSesso;

        // Lingue presenti nella proposta → checkbox.
        var seen = {}, list = [];
        P.cards.forEach(function (c) {
            cardLangs(c).forEach(function (L) {
                if (!seen[L.slug]) { seen[L.slug] = true; list.push(L); }
            });
        });
        list.sort(function (a, b) { return a.name.localeCompare(b.name, LOCALE); });
        var box = $('#propLingueList');
        box.innerHTML = list.map(function (L) {
            return '<label class="toa-prop-lang-opt"><input type="checkbox" value="' + escapeHtml(L.slug) + '"> ' + escapeHtml(L.name) + '</label>';
        }).join('');
        // Nessuna lingua nei dati → nascondi tutto il blocco lingue.
        $('#propFieldLingue').hidden = (list.length === 0);

        // Wiring (change → rilettura filtri → render).
        ['propSesso', 'propFascia', 'propEtaMin', 'propEtaMax', 'propLivMin'].forEach(function (id) {
            var el = document.getElementById(id);
            el.addEventListener('change', apply);
            if (el.type === 'number') el.addEventListener('input', debounce(apply, 350));
        });
        box.addEventListener('change', apply);

        // Pannello lingue richiudibile.
        var tog = $('#propLingueToggle'), panel = $('#propLinguePanel');
        tog.addEventListener('click', function () {
            var open = panel.hidden;
            panel.hidden = !open;
            tog.setAttribute('aria-expanded', open ? 'true' : 'false');
            tog.classList.toggle('is-open', open);
        });

        // Reset.
        $('#propReset').addEventListener('click', function () {
            $('#propSesso').value = ''; $('#propFascia').value = '';
            $('#propEtaMin').value = ''; $('#propEtaMax').value = '';
            $('#propLivMin').value = '';
            box.querySelectorAll('input:checked').forEach(function (i) { i.checked = false; });
            apply();
        });

        $('#propFilters').hidden = false;
    }

    function debounce(fn, ms) {
        var h; return function () { clearTimeout(h); h = setTimeout(fn, ms); };
    }

    function readFilters() {
        P.filters.sesso  = $('#propSesso').value;
        P.filters.fascia = $('#propFascia').value;
        P.filters.etaMin = parseInt($('#propEtaMin').value, 10) || 0;
        P.filters.etaMax = parseInt($('#propEtaMax').value, 10) || 0;
        P.filters.livMin = $('#propLivMin').value;
        P.filters.lingue = Array.prototype.map.call(
            $('#propLingueList').querySelectorAll('input:checked'),
            function (i) { return i.value; }
        );
    }

    function matches(c) {
        var f = P.filters;
        if (f.sesso && sessoNorm(c) !== f.sesso) return false;

        var h = parseInt(c.altezza, 10) || 0;
        if (f.fascia) {
            if (!h) return false;
            if (f.fascia === 'u160' && h >= 160) return false;
            if (f.fascia === '160'  && (h < 160 || h > 169)) return false;
            if (f.fascia === '170'  && (h < 170 || h > 179)) return false;
            if (f.fascia === '180p' && h < 180) return false;
        }

        var eta = parseInt(c.eta, 10) || 0;
        if (f.etaMin && (!eta || eta < f.etaMin)) return false;
        if (f.etaMax && (!eta || eta > f.etaMax)) return false;

        if (f.lingue.length) {
            var langs = cardLangs(c);
            var req = LIV_ORDER[f.livMin] || 0;
            for (var i = 0; i < f.lingue.length; i++) {
                var slug = f.lingue[i];
                var found = null;
                for (var j = 0; j < langs.length; j++) {
                    if (langs[j].slug === slug) { found = langs[j]; break; }
                }
                if (!found) return false;
                // Livello minimo: applicato solo se la card dichiara il livello
                // (dato assente ≠ livello basso: non escludere per dati mancanti).
                if (req > 0 && found.livNum > 0 && found.livNum < req) return false;
            }
        }
        return true;
    }

    // ─── Card (stesse classi di talent-database-v81.css) ─────────────
    function cardHtml(c) {
        var name = escapeHtml(c.nome || '—');

        var info = [];
        var eta = parseInt(c.eta, 10) || 0;
        var h   = parseInt(c.altezza, 10) || 0;
        if (eta) info.push(eta + ' ' + escapeHtml(t('years')));
        if (h)   info.push(h + ' cm');
        if (c.citta) info.push(escapeHtml(String(c.citta).toUpperCase()));

        var tags = cardLangs(c).map(function (L) {
            return '<span class="toa-tdb-card-lang" title="' + escapeHtml(L.name + (L.liv ? ' · ' + L.liv : '')) + '">' + escapeHtml(L.code) + '</span>';
        }).join('');

        var pg = fmtPrice(c.prezzo_giorno);
        var pt = fmtPrice(c.prezzo_totale);
        var price = '';
        if (pg) price += '<span class="toa-prop-price-day">' + escapeHtml(pg) + ' <em>' + escapeHtml(t('price_day')) + '</em></span>';
        if (pt) price += '<span class="toa-prop-price-tot">' + escapeHtml(t('price_tot')) + ' ' + escapeHtml(pt) + '</span>';

        var photo = c.foto
            ? '<img class="toa-tdb-card-img" src="' + escapeHtml(c.foto) + '" alt="' + name + '" loading="lazy" onerror="this.closest(\'.toa-tdb-card\').classList.add(\'toa-prop-noimg\')">'
            : '<div class="toa-prop-noimg-box" aria-hidden="true">👤</div>';

        return '<article class="toa-tdb-card toa-prop-card">' +
                 photo +
                 '<div class="toa-tdb-card-meta">' +
                   '<div class="toa-tdb-card-name-row"><strong>' + name + '</strong></div>' +
                   (info.length ? '<div class="toa-tdb-card-info-row">' + info.join(' · ') + '</div>' : '') +
                   (tags ? '<div class="toa-tdb-card-tags">' + tags + '</div>' : '') +
                   (price ? '<div class="toa-prop-price">' + price + '</div>' : '') +
                 '</div>' +
               '</article>';
    }

    function apply() {
        readFilters();
        var list = P.cards.filter(matches);
        var grid = $('#propGrid');
        grid.innerHTML = list.map(cardHtml).join('');
        grid.hidden = false;
        $('#propEmpty').hidden = list.length > 0;
        $('#propCount').textContent = list.length + ' ' + (list.length === 1 ? t('count_s') : t('count_p'));
    }

    // ─── Init ────────────────────────────────────────────────────────
    function init() {
        var token = new URLSearchParams(window.location.search).get('t');
        if (!token) { showError(t('err_token')); return; }

        fetch(API + '?t=' + encodeURIComponent(token) + '&_v=' + Date.now())
            .then(function (r) {
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function (d) {
                if (!d || !d.ok) { showError(t('err_token')); return; }
                P.cards = Array.isArray(d.cards) ? d.cards : [];
                $('#propLoading').hidden = true;
                renderHero(d);
                buildFilters();
                apply();
                $('#propPrivacy').hidden = false;
            })
            .catch(function () { showError(t('err_generic')); });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
