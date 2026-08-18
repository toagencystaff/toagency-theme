/**
 * proposta-v1.js — v1.2 2026-08-18 (chat TEMA DISPO-PROPOSTA)
 *
 * Logica della pagina /proposta/?t=TOKEN (templates/page-proposta.php).
 *
 * v1.2 — adeguato al contratto REALE di
 * /crm_toagency/actions/dispo-proposta-api.php (verificato live col token
 * di test, 60 card): FILTRI SERVER-SIDE via GET — obbligatorio perché la
 * fascia altezza `hf` CAMBIA I PREZZI, quindi ogni cambio filtro ri-fetcha.
 *   GET: t=TOKEN · sx=F|M · lg[]=slug · lgl[slug]=1|2|3 · e1/e2=età · hf=h1|h2|h3
 *   Risposta: {ok, tipo, titolo(può essere vuoto→fallback luogo), luogo,
 *     dal, al, giorni, ora_in, ora_fi, ore,
 *     filtri:{sesso_fisso, lingue_ok_ruolo, …},
 *     cards:[{nome "Nome C.", sesso "F"/"M", eta(può essere 0), altezza,
 *             lingue "a,b", lingue_liv:[{slug,livello:null|1|2|3,label}],
 *             citta(spesso ""), foto URL assoluto, prezzo_giorno, prezzo_totale}],
 *     tot, opzioni:{lingue[], livelli_lingua{1,2,3}, altezze{h1,h2,h3}}}
 *   Token invalido → HTTP 404 + {ok:false}.
 * Livelli lingua: quasi sempre null (compilati solo dal form nuovo 08/08)
 * → il filtro livello può dare 0 risultati: empty-state previsto.
 * Card con le STESSE classi di talent-database-v81.css + prezzi
 * .toa-prop-price (i prezzi si vedono — decisione 18/08).
 */
(function () {
    'use strict';

    var API  = window.toaPropApiUrl || '/crm_toagency/actions/dispo-proposta-api.php';
    var LANG = window.toaPropLang   || 'it';
    var I18N = window.toaPropI18n   || {};
    var LOCALE = ({ it: 'it-IT', en: 'en-GB', fr: 'fr-FR', es: 'es-ES' })[LANG] || 'it-IT';

    function t(k) { return I18N[k] || k; }
    function $(s) { return document.querySelector(s); }

    function escapeHtml(s) {
        return String(s == null ? '' : s)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    // ─── Stato ───────────────────────────────────────────────────────
    var P = { token: '', built: false, seq: 0 };

    // ─── Lingue: nomi localizzati + normalizzazione livelli ──────────
    var LANG_CODE = {
        italiano: 'IT', inglese: 'EN', francese: 'FR', spagnolo: 'ES', tedesco: 'DE',
        russo: 'RU', arabo: 'AR', cinese: 'ZH', portoghese: 'PT', giapponese: 'JA',
        olandese: 'NL', polacco: 'PL', rumeno: 'RO', ucraino: 'UK'
    };
    var LANG_NAME = {
        italiano:   { it: 'Italiano',   en: 'Italian',    fr: 'Italien',     es: 'Italiano' },
        inglese:    { it: 'Inglese',    en: 'English',    fr: 'Anglais',     es: 'Inglés' },
        francese:   { it: 'Francese',   en: 'French',     fr: 'Français',    es: 'Francés' },
        spagnolo:   { it: 'Spagnolo',   en: 'Spanish',    fr: 'Espagnol',    es: 'Español' },
        tedesco:    { it: 'Tedesco',    en: 'German',     fr: 'Allemand',    es: 'Alemán' },
        russo:      { it: 'Russo',      en: 'Russian',    fr: 'Russe',       es: 'Ruso' },
        arabo:      { it: 'Arabo',      en: 'Arabic',     fr: 'Arabe',       es: 'Árabe' },
        cinese:     { it: 'Cinese',     en: 'Chinese',    fr: 'Chinois',     es: 'Chino' },
        portoghese: { it: 'Portoghese', en: 'Portuguese', fr: 'Portugais',   es: 'Portugués' },
        giapponese: { it: 'Giapponese', en: 'Japanese',   fr: 'Japonais',    es: 'Japonés' },
        olandese:   { it: 'Olandese',   en: 'Dutch',      fr: 'Néerlandais', es: 'Neerlandés' },
        polacco:    { it: 'Polacco',    en: 'Polish',     fr: 'Polonais',    es: 'Polaco' },
        rumeno:     { it: 'Rumeno',     en: 'Romanian',   fr: 'Roumain',     es: 'Rumano' },
        ucraino:    { it: 'Ucraino',    en: 'Ukrainian',  fr: 'Ukrainien',   es: 'Ucraniano' }
    };
    function langName(slug) {
        var m = LANG_NAME[slug];
        if (m) return m[LANG] || m.it;
        return slug.charAt(0).toUpperCase() + slug.slice(1);
    }
    function langCode(slug) { return LANG_CODE[slug] || slug.slice(0, 2).toUpperCase(); }

    // Livello CRM: 1=Base 2=Fluente 3=Madrelingua (o null = non dichiarato).
    var LIV_SLUG = { 1: 'base', 2: 'fluente', 3: 'madrelingua' };
    function livLabel(n) { return LIV_SLUG[n] ? t('liv_' + LIV_SLUG[n]) : ''; }

    // Chip lingue della card: preferisce lingue_liv, fallback stringa "a,b".
    function cardLangs(c) {
        var out = [];
        if (c && Array.isArray(c.lingue_liv) && c.lingue_liv.length) {
            c.lingue_liv.forEach(function (x) {
                if (!x || !x.slug) return;
                var slug = String(x.slug).toLowerCase();
                var liv  = parseInt(x.livello, 10) || 0;
                out.push({ slug: slug, name: langName(slug), code: langCode(slug), liv: livLabel(liv) });
            });
        } else if (c && typeof c.lingue === 'string' && c.lingue.trim()) {
            c.lingue.split(',').forEach(function (s) {
                var slug = s.trim().toLowerCase();
                if (slug) out.push({ slug: slug, name: langName(slug), code: langCode(slug), liv: '' });
            });
        }
        return out;
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
    // "09:00" → "9" · "09:30" → "9:30" (orari compatti nell'elenco giorni)
    function fmtHour(s) {
        var m = String(s || '').trim().match(/^(\d{1,2}):(\d{2})/);
        if (!m) return String(s || '').trim();
        return m[2] === '00' ? String(parseInt(m[1], 10)) : parseInt(m[1], 10) + ':' + m[2];
    }

    // ─── Stati pagina ────────────────────────────────────────────────
    function showError(msg) {
        $('#propLoading').hidden = true;
        $('#propGrid').hidden = true;
        $('#propFilters').hidden = true;
        $('#propEmpty').hidden = true;
        $('#propErrorMsg').textContent = msg;
        $('#propError').hidden = false;
    }

    // ─── Hero (dal primo response; titolo vuoto → fallback luogo) ────
    function renderHero(d) {
        var tipo = String(d.tipo || '').toLowerCase();
        var badge = $('#propBadge');
        if (tipo === 'ipotetico' || tipo === 'effettivo') {
            badge.textContent = t('badge_' + tipo);
            badge.className = 'toa-prop-badge toa-prop-badge--' + tipo;
            badge.hidden = false;
            $('#propTipoNote').textContent = t('note_' + tipo);
        }
        var title = (d.titolo || '').trim() || (d.luogo || '').trim();
        $('#propTitle').textContent = title;
        document.title = (title ? title + ' — ' : '') + 'TOAgency';

        var meta = [];
        if (d.titolo && d.luogo) meta.push('📍 ' + d.luogo);   // luogo in meta solo se non già usato come titolo

        // 2026-08-18 — giorni_dett [{data,oi,of,ore}]: giorni anche NON consecutivi,
        // ognuno col suo orario (es. "10/09 9–13 · 12/09 9–18"). Fallback: dal→al.
        var gd = Array.isArray(d.giorni_dett) ? d.giorni_dett.filter(function (x) { return x && x.data; }) : [];
        if (gd.length) {
            meta.push('📅 ' + gd.map(function (x) {
                var p = String(x.data).slice(0, 10).split('-');   // [YYYY,MM,DD]
                var lbl = (p.length === 3) ? parseInt(p[2], 10) + '/' + p[1] : String(x.data);
                var oi = fmtHour(x.oi), of = fmtHour(x.of);
                return lbl + (oi && of ? ' ' + oi + '–' + of : '');
            }).join(' · '));
        } else {
            var dal = fmtDate(d.dal), al = fmtDate(d.al);
            if (dal) meta.push('📅 ' + (al && al !== dal ? dal + ' → ' + al : dal));
            if (d.ora_in && d.ora_fi) meta.push('🕘 ' + d.ora_in + '–' + d.ora_fi);
        }
        var g = parseInt(d.giorni, 10) || gd.length;
        if (g > 0) meta.push(g + ' ' + (g === 1 ? t('day_s') : t('day_p')));
        $('#propMeta').textContent = meta.join('  ·  ');
    }

    // ─── Filtri UI (costruiti UNA volta da opzioni + filtri) ─────────
    function buildFilters(d) {
        var opz = d.opzioni || {};

        // Genere: nascosto se l'evento ha il sesso fissato dal cliente.
        $('#propFieldSesso').hidden = !!(d.filtri && d.filtri.sesso_fisso);

        // Fasce altezza dell'API (h1/h2/h3, etichette tipo "F≥170 / M≥180").
        var fasce = opz.altezze || {};
        var selH = $('#propFascia');
        Object.keys(fasce).forEach(function (k) {
            if (!k) return;                       // la voce "" la mette il template, localizzata
            var o = document.createElement('option');
            o.value = k; o.textContent = fasce[k];
            selH.appendChild(o);
        });

        // Lingue filtrabili (nomi localizzati lato tema).
        var lingue = Array.isArray(opz.lingue) ? opz.lingue : [];
        var box = $('#propLingueList');
        box.innerHTML = lingue.map(function (slug) {
            slug = String(slug).toLowerCase();
            return '<label class="toa-prop-lang-opt"><input type="checkbox" value="' + escapeHtml(slug) + '"> ' + escapeHtml(langName(slug)) + '</label>';
        }).join('');
        var lingueOff = (d.filtri && d.filtri.lingue_ok_ruolo === false) || lingue.length === 0;
        $('#propFieldLingue').hidden = lingueOff;

        // Wiring: ogni cambio → ri-fetch server-side (hf cambia i prezzi).
        ['propSesso', 'propFascia', 'propLivMin'].forEach(function (id) {
            document.getElementById(id).addEventListener('change', refresh);
        });
        ['propEtaMin', 'propEtaMax'].forEach(function (id) {
            var el = document.getElementById(id);
            el.addEventListener('change', refresh);
            el.addEventListener('input', debounce(refresh, 450));
        });
        box.addEventListener('change', refresh);

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
            refresh();
        });

        $('#propFilters').hidden = false;
        P.built = true;
    }

    function debounce(fn, ms) {
        var h; return function () { clearTimeout(h); h = setTimeout(fn, ms); };
    }

    // ─── Query GET verso l'API (contratto reale) ─────────────────────
    function buildQuery() {
        var q = ['t=' + encodeURIComponent(P.token)];
        var sx = $('#propSesso').value;
        if (sx) q.push('sx=' + encodeURIComponent(sx.toUpperCase()));
        var hf = $('#propFascia').value;
        if (hf) q.push('hf=' + encodeURIComponent(hf));
        var e1 = parseInt($('#propEtaMin').value, 10) || 0;
        var e2 = parseInt($('#propEtaMax').value, 10) || 0;
        if (e1) q.push('e1=' + e1);
        if (e2) q.push('e2=' + e2);
        var liv = $('#propLivMin').value;
        $('#propLingueList').querySelectorAll('input:checked').forEach(function (i) {
            q.push('lg[]=' + encodeURIComponent(i.value));
            if (liv) q.push('lgl[' + encodeURIComponent(i.value) + ']=' + encodeURIComponent(liv));
        });
        q.push('_v=' + Date.now());   // cache-bust Dynamic Cache SiteGround
        return q.join('&');
    }

    // ─── Card (stesse classi di talent-database-v81.css) ─────────────
    function cardHtml(c) {
        var name = escapeHtml(c.nome || '—');

        var info = [];
        var eta = parseInt(c.eta, 10) || 0;
        var h   = parseInt(c.altezza, 10) || 0;
        if (eta > 0) info.push(eta + ' ' + escapeHtml(t('years')));
        if (h)       info.push(h + ' cm');
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

        /* is-visible SUBITO: il CSS tdb tiene le card a opacity:0 per il fade-in
           gestito dal JS v76 (observer) che qui non c'è. */
        return '<article class="toa-tdb-card toa-prop-card is-visible">' +
                 photo +
                 '<div class="toa-tdb-card-meta">' +
                   '<div class="toa-tdb-card-name-row"><strong>' + name + '</strong></div>' +
                   (info.length ? '<div class="toa-tdb-card-info-row">' + info.join(' · ') + '</div>' : '') +
                   (tags ? '<div class="toa-tdb-card-tags">' + tags + '</div>' : '') +
                   (price ? '<div class="toa-prop-price">' + price + '</div>' : '') +
                 '</div>' +
               '</article>';
    }

    function renderCards(d) {
        var cards = Array.isArray(d.cards) ? d.cards : [];
        var grid = $('#propGrid');
        grid.innerHTML = cards.map(cardHtml).join('');
        grid.hidden = false;
        grid.classList.remove('is-loading');
        $('#propEmpty').hidden = cards.length > 0;

        var tot = parseInt(d.tot, 10) || 0;
        var n = cards.length;
        var label = (n === 1 ? t('count_s') : t('count_p'));
        $('#propCount').textContent = (tot > n ? n + '/' + tot + ' ' : n + ' ') + label;
    }

    // ─── Fetch (primo giro costruisce hero+filtri, i successivi solo card) ─
    function load() {
        var seq = ++P.seq;                       // scarta risposte fuori ordine
        $('#propGrid').classList.add('is-loading');
        fetch(API + '?' + buildQuery())
            .then(function (r) {
                return r.json().catch(function () { throw new Error('HTTP ' + r.status); });
            })
            .then(function (d) {
                if (seq !== P.seq) return;
                if (!d || !d.ok) { showError(t('err_token')); return; }
                $('#propLoading').hidden = true;
                if (!P.built) {
                    renderHero(d);
                    buildFilters(d);
                    $('#propPrivacy').hidden = false;
                }
                renderCards(d);
            })
            .catch(function () {
                if (seq !== P.seq) return;
                if (!P.built) showError(t('err_generic'));
                else $('#propGrid').classList.remove('is-loading');
            });
    }
    function refresh() { load(); }

    // ─── Init ────────────────────────────────────────────────────────
    function init() {
        P.token = new URLSearchParams(window.location.search).get('t') || '';
        if (!P.token) { showError(t('err_token')); return; }
        load();
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
