/**
 * TOAgency — Talent Database (pagina pubblica)
 * v1.0 — 8 Maggio 2026
 * Path: /wp-content/themes/toagency-theme/assets/talent-database.js
 *
 * Vanilla JS, zero dipendenze. IIFE per non inquinare il global.
 *
 * Dipende da window.toaTdbApiUrl / toaTdbRequestUrl / toaTdbFotoUrl /
 * toaTdbLang impostati da page-talent-database.php.
 *
 * Endpoint attesi (vedi STEP 4 del planning):
 *   GET  {API}?action=filter_options
 *     -> { ok, paesi:[{code,label}], province_by_country:{IT:[…]}, etnia:[…], capelli:[…], occhi:[…] }
 *   POST {API}?action=search   body={ q, sesso, paese, provincia, taglia[], capelli, occhi, etnia,
 *                                     eta_min/max, altezza_min/max, scarpe_min/max, page, per_page }
 *     -> { ok, page, pages, total, results:[{id,nome,sesso,eta,altezza,taglia,citta,paese,...}] }
 *   GET  {API}?action=talent&id=ID
 *     -> { ok, talent:{…campi pubblici…}, photos:[{id, larghezza, altezza}] }
 *
 * Foto profilo / portfolio servite da toaTdbFotoUrl?id=ID[&photo_id=PID].
 *
 * Ordine sezioni: STATE → INIT → HELPERS → STORAGE → FILTERS/OPTIONS →
 *                 SEARCH/RENDER → MODAL → GALLERY → CART → FORM →
 *                 URL/HISTORY → EVENTS → BOOT.
 */
(function () {
    'use strict';

    // ═════════════════════════════════════════════════════════════════
    // STATE
    // ═════════════════════════════════════════════════════════════════
    var API_URL     = window.toaTdbApiUrl     || '/actions/api-talent-database.php';
    var REQUEST_URL = window.toaTdbRequestUrl || '/actions/talent-database-request.php';
    var FOTO_URL    = window.toaTdbFotoUrl    || '/actions/foto-talent-public.php';
    var LANG        = window.toaTdbLang       || 'it';
    var STORAGE_KEY = 'td_selected';
    var SWIPE_THRESHOLD = 50;
    var PER_PAGE    = 40;

    var TD = {
        page: 1,
        perPage: PER_PAGE,
        filters: {},
        results: [],
        total: 0,
        pages: 1,
        loading: false,
        selectedIds: new Set(),
        selectedTalents: new Map(),  // id -> { id, nome }
        selectedProvinces: [],       // 2026-06-17 marco — #7 province scelte (nomi canonici)
        geoExpanded: false,          // drill-down province aperto?
        geoHub: null,                // hub selezionato (per le fasce 2/3)
        modalTalent: null,
        galleryIdx: 0,
        galleryMedia: [],
        filterOptions: null,
        intersectionObserver: null,
        cardFadeObserver: null,
        skipUrlPush: false
    };

    // i18n: sotto-set per messaggi/etichette generate dal JS.
    // Le stringhe presenti nel template HTML restano lì (vedi $T in PHP).
    var I18N = {
        results_count_s: { it: 'talent trovato',     en: 'talent found',       fr: 'talent trouvé',      es: 'talent encontrado' },
        results_count_p: { it: 'talent trovati',     en: 'talents found',      fr: 'talents trouvés',    es: 'talents encontrados' },
        cart_singular:   { it: 'talent selezionato', en: 'talent selected',    fr: 'talent sélectionné', es: 'talent seleccionado' },
        cart_plural:     { it: 'talent selezionati', en: 'talents selected',   fr: 'talents sélectionnés', es: 'talents seleccionados' },
        btn_add:         { it: '+ Aggiungi alla selezione', en: '+ Add to selection', fr: '+ Ajouter', es: '+ Añadir' },
        btn_remove:      { it: '✓ Selezionato',      en: '✓ Selected',         fr: '✓ Sélectionné',      es: '✓ Seleccionado' },
        modal_age:       { it: 'Età',                en: 'Age',                fr: 'Âge',                es: 'Edad' },
        modal_height:    { it: 'Altezza',            en: 'Height',             fr: 'Taille',             es: 'Altura' },
        modal_size:      { it: 'Taglia',             en: 'Size',               fr: 'Taille',             es: 'Talla' },
        modal_shoes:     { it: 'Scarpe',             en: 'Shoes',              fr: 'Pointure',           es: 'Calzado' },
        modal_eyes:      { it: 'Occhi',              en: 'Eyes',               fr: 'Yeux',               es: 'Ojos' },
        modal_hair:      { it: 'Capelli',            en: 'Hair',               fr: 'Cheveux',            es: 'Cabello' },
        modal_ethnicity: { it: 'Etnia',              en: 'Ethnicity',          fr: 'Origine',            es: 'Etnia' },
        modal_location:  { it: 'Località',           en: 'Location',           fr: 'Localisation',       es: 'Ubicación' },
        modal_measurements: { it: 'Misure',          en: 'Measurements',       fr: 'Mensurations',       es: 'Medidas' },
        modal_code:      { it: 'Codice',    en: 'Code',        fr: 'Code',        es: 'Código' },
        modal_gender:    { it: 'Genere',    en: 'Gender',      fr: 'Genre',       es: 'Género' },
        modal_country:   { it: 'Paese',     en: 'Country',     fr: 'Pays',        es: 'País' },
        modal_city:      { it: 'Città',     en: 'City',        fr: 'Ville',       es: 'Ciudad' },
        modal_province:  { it: 'Provincia', en: 'Province',    fr: 'Région',      es: 'Provincia' },
        modal_chest:     { it: 'Seno',      en: 'Chest',       fr: 'Poitrine',    es: 'Pecho' },
        modal_waist:     { it: 'Vita',      en: 'Waist',       fr: 'Taille',      es: 'Cintura' },
        modal_hips:      { it: 'Fianchi',   en: 'Hips',        fr: 'Hanches',     es: 'Caderas' },
        years:           { it: 'anni',               en: 'years',              fr: 'ans',                es: 'años' },
        form_error: {
            it: 'Errore nell\'invio. Riprova oppure scrivi a info@toagency.it',
            en: 'Send error. Try again or write to info@toagency.it',
            fr: 'Erreur. Réessaye ou écris à info@toagency.it',
            es: 'Error. Inténtalo o escribe a info@toagency.it'
        }
    };

    // Restituisce la stringa i18n per `key` nella lingua corrente, fallback IT.
    function i18n(key) {
        var m = I18N[key];
        return m ? (m[LANG] || m.it) : key;
    }

    // ═════════════════════════════════════════════════════════════════
    // INIT
    // ═════════════════════════════════════════════════════════════════

    // Entry point: ripristina selezione, fa wiring, carica filter_options e prima search.
    function tdInit() {
        loadSelectedFromStorage();
        applyUrlStateBeforeOptions();

        wireFiltersForm();
        wireToggleGroups();
        wireChipGroups();
        wireMultiSelect();   // FIX 2026-06-20 marco — #3 multi-select dropdown
        wireGridDelegated();
        wireGalleryNav();
        wireGallerySwipe();
        wireCart();
        wireRequestForm();
        wireModalCloses();
        wireSidebarDrawer();

        updateCart();

        loadFilterOptions().then(function () {
            // applyUrlStateAfterOptions() già chiamata dentro populateSelects().
            return tdSearch(false);
        }).then(function () {
            maybeOpenTalentFromUrl();
        });
    }

    // ═════════════════════════════════════════════════════════════════
    // HELPERS
    // ═════════════════════════════════════════════════════════════════

    // querySelector singolo (alias breve).
    function $(sel, root) { return (root || document).querySelector(sel); }

    // querySelectorAll come Array (alias breve).
    function $$(sel, root) {
        return Array.prototype.slice.call((root || document).querySelectorAll(sel));
    }

    // Escape HTML per uso sicuro in innerHTML (XSS).
    function escapeHtml(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c];
        });
    }

    // Capitalizza la prima lettera di una stringa.
    function cap(s) {
        if (!s) return '';
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    // Wrapper fetch -> JSON con errore se HTTP non-2xx.
    function fetchJson(url, opts) {
        return fetch(url, opts || {}).then(function (r) {
            if (!r.ok) throw new Error('HTTP ' + r.status);
            return r.json();
        });
    }

    // Ritarda l'esecuzione di fn di delay ms; ogni nuova chiamata resetta il timer.
    function debounce(fn, delay) {
        var t;
        return function () { clearTimeout(t); t = setTimeout(fn, delay); };
    }

    // True se almeno uno dei modal principali è aperto (per gestire body.overflow).
    function anyOtherModalOpen() {
        var m1 = $('#tdbTalentModal'), m2 = $('#tdbRequestModal'), m3 = $('#tdbSuccess'), m4 = $('#tdbLockedModal');
        return (m1 && !m1.hidden) || (m2 && !m2.hidden) || (m3 && !m3.hidden) || (m4 && !m4.hidden);
    }

    // 2026-06-05 marco — modale "database in arrivo" per chip categoria bloccate (cliccabili).
    function openLockedModal(cat) {
        var m = $('#tdbLockedModal');
        if (!m) return;
        var t = $('#tdbLockedTitle');
        if (t) t.textContent = '🔒 ' + (cat || '') + ' — database in arrivo';
        m.hidden = false;
    }
    function closeLockedModal() {
        var m = $('#tdbLockedModal');
        if (m) m.hidden = true;
    }

    // ═════════════════════════════════════════════════════════════════
    // STORAGE (selezione persistente)
    // ═════════════════════════════════════════════════════════════════

    // Ripristina TD.selectedIds + selectedTalents da localStorage.
    function loadSelectedFromStorage() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) return;
            var arr = JSON.parse(raw);
            if (!Array.isArray(arr)) return;
            arr.forEach(function (item) {
                if (typeof item === 'number') {
                    TD.selectedIds.add(item);
                } else if (item && typeof item.id === 'number') {
                    TD.selectedIds.add(item.id);
                    TD.selectedTalents.set(item.id, { id: item.id, nome: item.nome || '' });
                }
            });
        } catch (e) { /* ignore */ }
    }

    // Salva la selezione corrente in localStorage (id + nome per il chip riepilogo).
    function saveSelectedToStorage() {
        try {
            var out = [];
            TD.selectedIds.forEach(function (id) {
                var t = TD.selectedTalents.get(id);
                out.push(t ? { id: id, nome: t.nome || '' } : { id: id });
            });
            localStorage.setItem(STORAGE_KEY, JSON.stringify(out));
        } catch (e) { /* ignore */ }
    }

    // ═════════════════════════════════════════════════════════════════
    // FILTERS / OPTIONS
    // ═════════════════════════════════════════════════════════════════

    // Scarica i valori dei select dinamici (paesi, province, etnia, capelli, occhi).
    function loadFilterOptions() {
        return fetchJson(API_URL + '?action=filter_options')
            .then(function (res) {
                if (!res || !res.ok) throw new Error('filter_options not ok');
                TD.filterOptions = res;
                populateSelects();
            })
            .catch(function (err) {
                console.error('[tdb]', err);
            });
    }

    // Costruisce <option> a partire da un array di item (con o senza key/label fields).
    function buildOptions(items, keyField, labelField, anyLabel) {
        var html = '<option value="">' + escapeHtml(anyLabel) + '</option>';
        (items || []).forEach(function (it) {
            var v = keyField ? it[keyField] : it;
            var l = labelField ? it[labelField] : it;
            html += '<option value="' + escapeHtml(v) + '">' + escapeHtml(l) + '</option>';
        });
        return html;
    }

    // ── FIX 2026-06-20 marco — #3: multi-select a tendina (etnia/taglia/capelli/occhi) ──
    function msBox(name) { return $('.toa-tdb-ms[data-name="' + name + '"]'); }

    // Popola il menu checkbox di un multi-select dai valori passati.
    function buildMs(name, values) {
        var box = msBox(name);
        if (!box) return;
        var menu = box.querySelector('.toa-tdb-ms-menu');
        menu.innerHTML = (values || []).map(function (v) {
            return '<label class="toa-tdb-ms-opt"><input type="checkbox" value="' + escapeHtml(v) +
                   '"><span>' + escapeHtml(cap(v)) + '</span></label>';
        }).join('');
        msText(box);
    }

    // Aggiorna il testo del bottone: "Tutte" se nulla, altrimenti i valori scelti.
    function msText(box) {
        var checked = $$('.toa-tdb-ms-menu input:checked', box);
        var txtEl = box.querySelector('.toa-tdb-ms-text');
        if (!txtEl) return;
        if (!checked.length) {
            txtEl.textContent = box.dataset.any || '—';
            box.classList.remove('has-sel');
        } else {
            txtEl.textContent = checked.map(function (c) { return cap(c.value); }).join(', ');
            box.classList.add('has-sel');
        }
    }

    function msOpen(box, open) {
        var menu = box.querySelector('.toa-tdb-ms-menu');
        var tog  = box.querySelector('.toa-tdb-ms-toggle');
        box.classList.toggle('is-open', open);
        if (menu) menu.hidden = !open;
        if (tog) tog.setAttribute('aria-expanded', open ? 'true' : 'false');
    }

    // Spunta i checkbox di un multi-select dai valori passati (restore da URL).
    function msSet(name, values) {
        if (!values || !values.length) return;
        var box = msBox(name);
        if (!box) return;
        var want = values.map(function (v) { return String(v).toLowerCase(); });
        $$('.toa-tdb-ms-menu input', box).forEach(function (c) {
            if (want.indexOf(String(c.value).toLowerCase()) !== -1) c.checked = true;
        });
        msText(box);
    }

    // Svuota tutte le selezioni multi-select (usato dal Reset).
    function msClearAll() {
        $$('.toa-tdb-ms').forEach(function (box) {
            $$('.toa-tdb-ms-menu input:checked', box).forEach(function (c) { c.checked = false; });
            msText(box);
            msOpen(box, false);
        });
    }

    function wireMultiSelect() {
        $$('.toa-tdb-ms').forEach(function (box) {
            var tog = box.querySelector('.toa-tdb-ms-toggle');
            if (tog) tog.addEventListener('click', function (e) {
                e.preventDefault();
                var willOpen = !box.classList.contains('is-open');
                $$('.toa-tdb-ms').forEach(function (b) { if (b !== box) msOpen(b, false); });
                msOpen(box, willOpen);
            });
            box.addEventListener('change', function (e) {
                if (!e.target.matches || !e.target.matches('input[type="checkbox"]')) return;
                msText(box);
                tdSearch(false);
            });
        });
        // click fuori → chiudi tutti i menu aperti
        document.addEventListener('click', function (e) {
            if (e.target.closest && e.target.closest('.toa-tdb-ms')) return;
            $$('.toa-tdb-ms').forEach(function (b) { msOpen(b, false); });
        });
    }

    // Popola i <select> dei filtri usando TD.filterOptions, poi applica URL state e popola province.
    function populateSelects() {
        var fo = TD.filterOptions || {};
        var anyLabel = $('#tdbFilterCountry option[value=""]').textContent;

        // 2026-06-17 marco — in cima il paese della lingua corrente (it→IT, fr→FR, es→ES, en→GB);
        // il resto resta nell'ordine di importanza (conteggio) deciso dall'API.
        if (fo.paesi && fo.paesi.length) {
            var langCode = ({ it: 'IT', fr: 'FR', es: 'ES', en: 'GB' })[LANG] || 'IT';
            var pi = fo.paesi.findIndex(function (p) { return p.code === langCode; });
            if (pi > 0) fo.paesi.unshift(fo.paesi.splice(pi, 1)[0]);
        }

        if (fo.paesi)   $('#tdbFilterCountry').innerHTML   = buildOptions(fo.paesi, 'code', 'label', anyLabel);
        // FIX 2026-06-20 marco — #3: etnia/capelli/occhi/taglia → menu multi-select
        if (fo.etnia)   buildMs('etnia', fo.etnia);
        if (fo.capelli) buildMs('capelli', fo.capelli);
        if (fo.occhi)   buildMs('occhi', fo.occhi);
        buildMs('taglia', ['XS', 'S', 'M', 'L', 'XL', 'XXL']);

        applyUrlStateAfterOptions();
        populateProvinces();
    }

    // Popola il <select> Provincia in base al Paese corrente.
    function populateProvinces() { renderGeo(); }   // 2026-06-17 marco — provincia → componente geo

    // #7 — applica ricerca: subito (hub/azzera) o con debounce (spunte)
    var _geoT = null;
    function geoApply(now) { clearTimeout(_geoT); if (now) { tdSearch(false); } else { _geoT = setTimeout(function () { tdSearch(false); }, 500); } }
    function geoCountryProvinces(cfg) {
        var out = [], regs = cfg.regioni || {};
        Object.keys(regs).forEach(function (r) { regs[r].forEach(function (p) { if (out.indexOf(p) < 0) out.push(p); }); });
        return out;
    }

    // #7 filtro geografico: chip hub (bacini) + Affina ricerca (togli / aggiungi zone), multiselezione, conteggi.
    function renderGeo() {
        var cont = $('#tdbGeoFilter'); if (!cont) return;
        var fo = TD.filterOptions || {};
        var geo = fo.geo || {}, counts = fo.province_counts || {};
        var cEl = $('#tdbFilterCountry'); var country = cEl ? cEl.value : '';
        cont.innerHTML = '';
        var lab = document.createElement('label'); lab.className = 'toa-tdb-label';
        lab.textContent = cont.getAttribute('data-label') || 'Zona';
        cont.appendChild(lab);
        var cfg = geo[country];
        if (!country || !cfg) {
            var hint = document.createElement('div'); hint.className = 'toa-tdb-geo-hint';
            hint.textContent = cont.getAttribute('data-anyhub') || 'Seleziona un paese';
            cont.appendChild(hint); return;
        }
        // Hub chips → selezione bacino + ricerca immediata
        var hubKeys = Object.keys(cfg.hub || {});
        if (hubKeys.length) {
            var hw = document.createElement('div'); hw.className = 'toa-tdb-geo-hubs';
            hubKeys.forEach(function (name) {
                var b = document.createElement('button'); b.type = 'button'; b.className = 'toa-tdb-geo-hub';
                b.textContent = name;
                b.addEventListener('click', function () { var f = cfg.hub[name].fasce || {}; TD.selectedProvinces = (f['1'] || []).slice(); TD.geoHub = name; renderGeo(); geoApply(true); });
                hw.appendChild(b);
            });
            cont.appendChild(hw);
        }
        // Barra: N zone + azzera + "Affina ricerca"
        var bar = document.createElement('div'); bar.className = 'toa-tdb-geo-sel';
        var n = TD.selectedProvinces.length;
        var txt = document.createElement('span'); txt.id = 'tdbGeoCount'; txt.textContent = n ? (n + ' zone selezionate') : 'Nessuna zona';
        bar.appendChild(txt);
        if (n) {
            var clr = document.createElement('button'); clr.type = 'button'; clr.className = 'toa-tdb-geo-clear'; clr.textContent = '✕'; clr.setAttribute('aria-label', 'Azzera');
            clr.addEventListener('click', function () { TD.selectedProvinces = []; TD.geoHub = null; renderGeo(); geoApply(true); });
            bar.appendChild(clr);
        }
        var tog = document.createElement('button'); tog.type = 'button';
        tog.style.cssText = 'background:transparent;border:none;color:#c8ff00;font-size:12px;cursor:pointer;margin-left:auto;padding:0;font-weight:700;';
        tog.textContent = TD.geoExpanded ? 'Chiudi zone ▴' : 'Affina zone ▾';
        tog.addEventListener('click', function () { TD.geoExpanded = !TD.geoExpanded; renderGeo(); });
        bar.appendChild(tog);
        cont.appendChild(bar);
        if (!TD.geoExpanded) return;

        // ── Affina: togli dalle tue zone / aggiungi altre ──
        var box = document.createElement('div'); box.className = 'toa-tdb-geo-affina';
        if (n) {
            var h1 = document.createElement('div'); h1.className = 'toa-tdb-geo-h'; h1.textContent = 'Le tue zone — clicca per togliere';
            box.appendChild(h1);
            var g1 = document.createElement('div'); g1.className = 'toa-tdb-geo-pills';
            TD.selectedProvinces.slice().forEach(function (p) {
                var b = document.createElement('button'); b.type = 'button'; b.className = 'toa-tdb-geo-pill is-on';
                b.innerHTML = p + ' <span aria-hidden="true">✕</span>';
                b.addEventListener('click', function () { var i = TD.selectedProvinces.indexOf(p); if (i >= 0) TD.selectedProvinces.splice(i, 1); renderGeo(); geoApply(); });
                g1.appendChild(b);
            });
            box.appendChild(g1);
        }
        // Aggiungi altre zone: per FASCIA (2,3) dell'hub corrente, poi Altre.
        var inSel = function (p) { return TD.selectedProvinces.indexOf(p) >= 0; };
        function addPill(container, p) {
            var b = document.createElement('button'); b.type = 'button'; b.className = 'toa-tdb-geo-pill';
            b.innerHTML = '+ ' + p + ' <small>' + (counts[p] || 0) + '</small>';
            b.addEventListener('click', function () { if (!inSel(p)) TD.selectedProvinces.push(p); renderGeo(); geoApply(); });
            container.appendChild(b);
        }
        var used = {}; TD.selectedProvinces.forEach(function (p) { used[p] = true; });
        var hubCfg = (TD.geoHub && cfg.hub[TD.geoHub]) ? cfg.hub[TD.geoHub] : null;
        var fasceLbl = { '2': 'Fascia 2 — raggiungibile', '3': 'Fascia 3 — più lontana' };
        if (hubCfg && hubCfg.fasce) {
            ['2', '3'].forEach(function (fk) {
                var list = (hubCfg.fasce[fk] || []).filter(function (p) { return !used[p] && (counts[p] || 0) > 0; });
                if (!list.length) return;
                var hh = document.createElement('div'); hh.className = 'toa-tdb-geo-h';
                hh.appendChild(document.createTextNode(fasceLbl[fk] + ' '));
                var addAll = document.createElement('button'); addAll.type = 'button';
                addAll.style.cssText = 'background:transparent;border:none;color:#c8ff00;font-size:11px;cursor:pointer;margin-left:6px;padding:0;font-weight:700;';
                addAll.textContent = '+ aggiungi tutta';
                addAll.addEventListener('click', function () { list.forEach(function (p) { if (!inSel(p)) TD.selectedProvinces.push(p); }); renderGeo(); geoApply(); });
                hh.appendChild(addAll);
                box.appendChild(hh);
                var gg = document.createElement('div'); gg.className = 'toa-tdb-geo-pills';
                list.forEach(function (p) { used[p] = true; addPill(gg, p); });
                box.appendChild(gg);
            });
        }
        var altre = geoCountryProvinces(cfg).filter(function (p) { return (counts[p] || 0) > 0 && !used[p]; });
        altre.sort(function (a, b) { return (counts[b] || 0) - (counts[a] || 0); });
        if (altre.length) {
            var h3 = document.createElement('div'); h3.className = 'toa-tdb-geo-h'; h3.textContent = hubCfg ? 'Altre zone' : 'Aggiungi altre zone';
            box.appendChild(h3);
            var g3 = document.createElement('div'); g3.className = 'toa-tdb-geo-pills';
            altre.forEach(function (p) { addPill(g3, p); });
            box.appendChild(g3);
        }
        cont.appendChild(box);
    }

    // Legge i valori del form filtri e li serializza in oggetto piatto da inviare all'API.
    function readFilters() {
        var f = $('#tdbFilters');
        var out = {};
        var q = f.q.value.trim();
        if (q) out.q = q;
        if (f.sesso.value)     out.sesso     = f.sesso.value;
        if (f.paese.value)     out.paese     = f.paese.value;
        if (TD.selectedProvinces && TD.selectedProvinces.length) out.province = TD.selectedProvinces.slice();
        // FIX 2026-06-20 marco — #3: multi-select etnia/taglia/capelli/occhi → array di valori spuntati
        ['etnia', 'taglia', 'capelli', 'occhi'].forEach(function (name) {
            var vals = $$('.toa-tdb-ms[data-name="' + name + '"] .toa-tdb-ms-menu input:checked')
                .map(function (c) { return c.value; });
            if (vals.length) out[name] = vals;
        });

        ['eta_min', 'eta_max', 'altezza_min', 'altezza_max', 'scarpe_min', 'scarpe_max',
         'valutazione_min', 'valutazione_max'].forEach(function (k) {
            var el = f[k];
            if (!el) return;
            var v = el.value;
            if (v !== '') {
                var n = parseInt(v, 10);
                if (!isNaN(n)) out[k] = n;
            }
        });
        return out;
    }

    // ═════════════════════════════════════════════════════════════════
    // SEARCH / RENDER
    // ═════════════════════════════════════════════════════════════════

    // POST a ?action=search con i filtri correnti. append=true per "Carica altri".
    function tdSearch(append) {
        if (TD.loading) return Promise.resolve();
        TD.loading = true;

        var filters = readFilters();
        TD.filters = filters;
        if (!append) TD.page = 1;

        var body = {};
        Object.keys(filters).forEach(function (k) { body[k] = filters[k]; });
        body.page = TD.page;
        body.per_page = TD.perPage;

        return fetchJson(API_URL + '?action=search', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        })
            .then(function (res) {
                if (!res || !res.ok) throw new Error('search not ok');
                TD.total = res.total || 0;
                TD.pages = res.pages || 1;
                if (append) {
                    TD.results = TD.results.concat(res.results || []);
                } else {
                    TD.results = res.results || [];
                }
                tdRenderGrid(append);
                updateResultsCount();
                updateLoadMore();
                if (!append && !TD.skipUrlPush) updateUrl();
                TD.skipUrlPush = false;
            })
            .catch(function (err) {
                console.error('[tdb] search error', err);
                $('#tdbResultsCount').textContent = '—';
            })
            .then(function () { TD.loading = false; });
    }

    // Costruisce il markup HTML di una card talent (escape XSS sui campi dinamici).
    // 2026-06-16 marco — foto card non caricabile -> placeholder pulito
    window.tdbCardImgErr = function (img) {
        try {
            var c = img.closest('.toa-tdb-card'); if (!c) return;
            if (img.dataset.fbk) { img.style.display = 'none'; c.classList.add('is-broken'); return; }
            img.dataset.fbk = '1';
            var id = c.getAttribute('data-id');
            fetch(API_URL + '?action=talent&id=' + encodeURIComponent(id)).then(function (r) { return r.json(); }).then(function (d) {
                var media = (d && d.media) || [], alt = null, i;
                for (i = 0; i < media.length; i++) { if (media[i].url && media[i].url.indexOf('foto-talent-public') === -1) { alt = media[i]; break; } }
                if (alt) { img.src = alt.url; img.style.display = ''; } else { img.style.display = 'none'; c.classList.add('is-broken'); }
            }).catch(function () { img.style.display = 'none'; c.classList.add('is-broken'); });
        } catch (e) {}
    };
    // 2026-06-16 marco — placeholder "No photo" (SVG <=300px) -> recupera prima foto buona
    window.tdbCardImgLoad = function (img) {
        try { if (img.dataset.fbk) return; if (img.naturalWidth > 0 && img.naturalWidth <= 300) { tdbCardImgErr(img); } } catch (e) {}
    };
    function cardHtml(t, idx) {
        var id = parseInt(t.id, 10) || 0;
        var sel = TD.selectedIds.has(id) ? ' selected' : '';
        var fotoSrc = FOTO_URL + '?id=' + encodeURIComponent(id);
        // 2026-06-02 marco — BUG FIX foto bianche: le prime 12 card (above-the-fold) caricano la foto
        // SUBITO (src diretto, niente lazy) → nessuna dipendenza da lazySizes/observer per ciò che è già visibile.
        var eager = (parseInt(idx, 10) || 0) < 12;
        // 2026-06-01 marco — rotazione + posizione foto profilo (talent_database.foto_rotazione/foto_position via API)
        var rot = parseInt(t.foto_rotazione, 10) || 0;
        var pos = (typeof t.foto_position === 'string' && /^\d{1,3}%\s+\d{1,3}%$/.test(t.foto_position.trim())) ? t.foto_position.trim() : '';
        var cssParts = [];
        if (rot) cssParts.push('transform:rotate(' + rot + 'deg) scale(' + ((rot === 90 || rot === 270) ? 1.35 : 1) + ')');
        if (pos) cssParts.push('object-position:' + pos);
        var rotStyle = cssParts.length ? (' style="' + cssParts.join(';') + ';"') : '';
        // 2026-06-02 marco — riga unica: nome · #codice · età anni · altezza cm · provincia (NO nazione)
        var tid = parseInt(t.talent_id, 10) || 0;
        var line = [];
        line.push('<strong>' + escapeHtml(t.nome || '—') + '</strong>');
        if (tid)       line.push('<span class="toa-tdb-card-code">' + String(tid) + '</span>'); // 2026-06-04 marco — codice in pill monospace
        if (t.eta)     line.push(escapeHtml(t.eta + ' anni'));
        if (t.altezza) line.push(escapeHtml(t.altezza + ' cm'));
        if (t.provincia) line.push(escapeHtml(String(t.provincia).toUpperCase()));
        var lineHtml = line.join(' · ');
        var tdbNameRow = '<strong>' + escapeHtml(t.nome || '—') + '</strong>' + (tid ? '<span class="toa-tdb-card-code">' + String(tid) + '</span>' : '');
        var tdbInfo = [];
        if (t.eta)     tdbInfo.push(escapeHtml(t.eta + ' anni'));
        if (t.altezza) tdbInfo.push(escapeHtml(t.altezza + ' cm'));
        if (t.provincia) tdbInfo.push(escapeHtml(String(t.provincia).toUpperCase()));
        var tdbInfoHtml = tdbInfo.join(' · ');

        return '<article class="toa-tdb-card' + sel + '" data-id="' + id + '">' +
                 '<a class="toa-tdb-card-link" href="?tid=' + id + '" aria-label="' + escapeHtml(t.nome || '') + '" tabindex="-1"></a>' +
                 '<button type="button" class="toa-tdb-card-add" data-add="1" aria-label="' + escapeHtml(i18n('btn_add')) + '">' +
                   (sel ? '✓' : '+') +
                 '</button>' +
                 (eager
                    ? '<img class="toa-tdb-card-img"' + rotStyle + ' src="' + escapeHtml(fotoSrc) + '" alt="' + escapeHtml(t.nome || '') + '" onerror="tdbCardImgErr(this)" onload="tdbCardImgLoad(this)">'
                    : '<img class="toa-tdb-card-img lazyload"' + rotStyle + ' data-src="' + escapeHtml(fotoSrc) + '" alt="' + escapeHtml(t.nome || '') + '" onerror="tdbCardImgErr(this)" onload="tdbCardImgLoad(this)">') +
                 '<div class="toa-tdb-card-meta">' +
                   '<div class="toa-tdb-card-name-row">' + tdbNameRow + '</div>' +
                   (tdbInfoHtml ? '<div class="toa-tdb-card-info-row">' + tdbInfoHtml + '</div>' : '') +
                 '</div>' +
               '</article>';
    }

    // Renderizza la grid (replace) o appende le nuove card (append=true).
    function tdRenderGrid(append) {
        var grid = $('#tdbGrid');
        var empty = $('#tdbGridEmpty');
        if (append) {
            var startIdx = grid.children.length;
            grid.insertAdjacentHTML('beforeend', TD.results.slice(startIdx).map(function (t, i) { return cardHtml(t, startIdx + i); }).join(''));
        } else {
            grid.innerHTML = TD.results.map(function (t, i) { return cardHtml(t, i); }).join('');
        }
        empty.hidden = TD.results.length > 0;
        tdLazyLoadPhotos();
        tdObserveCardFadeIn();
    }

    // Avvia/aggiorna IntersectionObserver per caricare le foto card on-demand.
    // 2026-06-02 marco — classe allineata a SiteGround lazySizes ('lazyload'). Questo observer resta
    // come fallback indipendente da SG; quando carica rimuove 'lazyload' così lazySizes non riprocessa
    // (ed evita che resti invisibile se SG applica .lazyload{opacity:0}).
    function tdLazyLoadPhotos() {
        function loadImg(img) {
            if (!img.dataset.src) return;
            var done = function () { img.classList.add('loaded'); img.classList.remove('lazyload'); };
            img.addEventListener('load', done, { once: true });
            img.addEventListener('error', done, { once: true });
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
        }
        if (!('IntersectionObserver' in window)) {
            // Fallback: load immediato (browser legacy).
            $$('img.toa-tdb-card-img.lazyload').forEach(loadImg);
            return;
        }
        if (!TD.intersectionObserver) {
            TD.intersectionObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    loadImg(e.target);
                    TD.intersectionObserver.unobserve(e.target);
                });
            }, { rootMargin: '200px' });
        }
        $$('img.toa-tdb-card-img.lazyload').forEach(function (img) {
            if (img.dataset.src) TD.intersectionObserver.observe(img);
        });
    }

    // Aggiunge is-visible alle card quando entrano nel viewport (fade-in).
    function tdObserveCardFadeIn() {
        if (!('IntersectionObserver' in window)) {
            $$('.toa-tdb-card').forEach(function (c) { c.classList.add('is-visible'); });
            return;
        }
        if (!TD.cardFadeObserver) {
            TD.cardFadeObserver = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (!e.isIntersecting) return;
                    e.target.classList.add('is-visible');
                    TD.cardFadeObserver.unobserve(e.target);
                });
            }, { rootMargin: '0px 0px -40px 0px', threshold: 0.05 });
        }
        $$('.toa-tdb-card:not(.is-visible)').forEach(function (c) {
            TD.cardFadeObserver.observe(c);
        });
    }

    // Aggiorna il contatore risultati con singolare/plurale corretto.
    function updateResultsCount() {
        var label = TD.total === 1 ? i18n('results_count_s') : i18n('results_count_p');
        var el = $('#tdbResultsCount');
        el.textContent = '';
        var strong = document.createElement('strong');
        strong.textContent = String(TD.total);
        el.appendChild(strong);
        el.appendChild(document.createTextNode(' ' + label));
    }

    // Mostra/nasconde il pulsante "Carica altri" in base alla paginazione.
    function updateLoadMore() {
        $('#tdbLoadMore').hidden = TD.page >= TD.pages;
    }

    // Riallinea lo stato visivo (selected) di una card alla TD.selectedIds.
    function updateCardSelectedState(id) {
        var card = $('.toa-tdb-card[data-id="' + id + '"]');
        if (!card) return;
        var sel = TD.selectedIds.has(id);
        card.classList.toggle('selected', sel);
        var btn = card.querySelector('.toa-tdb-card-add');
        if (btn) btn.textContent = sel ? '✓' : '+';
    }

    // ═════════════════════════════════════════════════════════════════
    // MODAL TALENT
    // ═════════════════════════════════════════════════════════════════

    // Apre il modal scheda: GET ?action=talent&id=ID, popola galleria + info, push URL.
    function tdOpenTalentModal(id) {
        id = parseInt(id, 10);
        if (!id) return Promise.resolve();
        var modal = $('#tdbTalentModal');
        if (!modal) return Promise.resolve();
        modal.hidden = false;
        document.body.style.overflow = 'hidden';

        // Loading state minimale.
        var nameEl = $('#tdbModalName');
        var fieldsEl = $('#tdbModalFields');
        if (nameEl) nameEl.textContent = '…';
        if (fieldsEl) fieldsEl.innerHTML = '';
        var galleryImg = $('#tdbGalleryImage');
        if (galleryImg) { galleryImg.src = ''; galleryImg.style.opacity = ''; }

        return fetchJson(API_URL + '?action=talent&id=' + encodeURIComponent(id))
            .then(function (res) {
                if (!res || !res.ok || !res.talent) throw new Error('talent not found');
                var media = res.media || [];
                console.log('[GALLERY] Media ricevuti:', media.length, media);
                TD.modalTalent = res.talent;
                renderDetailModal(res.talent, media);
                updateModalAddBtn();
                pushUrlTalent(id);
            })
            .catch(function (err) {
                console.error('[tdb] modal error', err);
                $('#tdbModalName').textContent = '—';
                $('#tdbModalFields').innerHTML = '<div class="toa-tdb-modal-load-error">Errore nel caricamento. Riprova.</div>';
            });
    }

    // Chiude il modal scheda, resetta stato galleria e ripristina la URL "lista".
    function tdCloseTalentModal() {
        var galleryImg = $('#tdbGalleryImage');
        if (galleryImg) { galleryImg.src = ''; galleryImg.style.opacity = ''; }
        $('#tdbTalentModal').hidden = true;
        if (!anyOtherModalOpen()) document.body.style.overflow = '';
        TD.modalTalent = null;
        TD.galleryMedia = [];
        TD.galleryIdx = 0;
        clearUrlTalent();
    }

    // Popola la scheda dettaglio del modal (galleria + righe monospace key/value).
    function renderDetailModal(t, media) {
        var nameEl2 = $('#tdbModalName');
        if (nameEl2) nameEl2.textContent = t.nome || '—';
        var rows = [];
        function addRow(key, val) {
            if (val == null || val === '') return;
            rows.push(
                '<div class="toa-tdb-detail-row">' +
                '<span class="toa-tdb-detail-key">' + escapeHtml(key) + '</span>' +
                '<span class="toa-tdb-detail-val">' + escapeHtml(String(val)) + '</span>' +
                '</div>'
            );
        }
        addRow(i18n('modal_code'),  t.talent_id ? t.talent_id : null);   // Obj1 codice
        addRow(i18n('modal_gender'),  t.sesso);
        addRow(i18n('modal_country'), t.paese_label || t.paese);
        addRow(i18n('modal_city'),    t.citta);
        addRow(i18n('modal_province'), t.provincia);                              // Obj2 provincia
        addRow(i18n('modal_age'), t.eta != null && t.eta !== '' ? t.eta + ' ' + i18n('years') : null);
        addRow(i18n('modal_height'),  t.altezza ? t.altezza + ' cm' : null);
        addRow(i18n('modal_size'),    t.taglia);
        addRow(i18n('modal_hair'),    t.capelli ? cap(t.capelli) : null);
        addRow(i18n('modal_eyes'),    t.occhi ? cap(t.occhi) : null);
        addRow(i18n('modal_shoes'),   t.scarpe);
        if (t.sesso === 'F') {
            addRow(i18n('modal_chest'), t.misura_petto   ? t.misura_petto   + ' cm' : null);
            addRow(i18n('modal_waist'), t.misura_vita    ? t.misura_vita    + ' cm' : null);
            addRow(i18n('modal_hips'),  t.misura_fianchi ? t.misura_fianchi + ' cm' : null);
        }
        var fieldsEl2 = $('#tdbModalFields');
        if (fieldsEl2) fieldsEl2.innerHTML = rows.join('');

        // Galleria: usa media[] dall'API (res.media), fallback foto profilo
        var galleryMedia = (media && media.length > 0)
            ? media
            : [{ tipo: 'foto', url: FOTO_URL + '?id=' + encodeURIComponent(t.id) }];
        renderGallery(galleryMedia);
    }

    // Aggiorna il testo del pulsante "+ Aggiungi / ✓ Selezionato" del modal.
    function updateModalAddBtn() {
        if (!TD.modalTalent) return;
        var btn = $('#tdbModalAdd');
        if (!btn) return;
        var sel = TD.selectedIds.has(TD.modalTalent.id);
        btn.textContent = sel ? i18n('btn_remove') : i18n('btn_add');
        btn.classList.toggle('selected', sel);
    }

    // ═════════════════════════════════════════════════════════════════
    // GALLERY
    // ═════════════════════════════════════════════════════════════════

    // Inizializza la galleria con l'array media[], costruisce i thumb, mostra il primo item.
    function renderGallery(media) {
        TD.galleryMedia = media || [];
        TD.galleryIdx = 0;

        var thumbsEl = $('#tdbGalleryThumbs');
        var totalEl  = document.querySelector('.toa-tdb-gallery-total');

        if (totalEl) totalEl.textContent = TD.galleryMedia.length || 1;

        if (thumbsEl) {
            thumbsEl.innerHTML = '';
            if (TD.galleryMedia.length > 1) {
                TD.galleryMedia.forEach(function (item, i) {
                    var thumb = document.createElement('img');
                    thumb.src       = item.url || '';
                    thumb.loading   = 'lazy';
                    thumb.alt       = '';
                    // 2026-06-01 marco — rotazione + posizione coerenti con la foto profilo
                    var tRot = parseInt(item.rotazione, 10) || 0;
                    if (tRot) thumb.style.transform = 'rotate(' + tRot + 'deg) scale(' + ((tRot === 90 || tRot === 270) ? 1.35 : 1) + ')';
                    if (typeof item.position === 'string' && /^\d{1,3}%\s+\d{1,3}%$/.test(item.position.trim())) thumb.style.objectPosition = item.position.trim();
                    thumb.className = 'toa-tdb-gallery-thumb' + (i === 0 ? ' is-active' : '');
                    thumb.setAttribute('data-idx', String(i));
                    thumbsEl.appendChild(thumb);
                });
                thumbsEl.style.display = '';
            } else {
                thumbsEl.style.display = 'none';
            }
        }

        showMedia(0);
    }

    // Mostra il media all'indice idx, aggiorna counter, frecce e thumb attivo.
    function showMedia(idx) {
        if (!TD.galleryMedia.length) return;
        idx = Math.max(0, Math.min(idx, TD.galleryMedia.length - 1));
        TD.galleryIdx = idx;

        var item       = TD.galleryMedia[idx];
        var imgEl      = $('#tdbGalleryImage');
        var currentEl  = document.querySelector('.toa-tdb-gallery-current');
        var totalElC   = document.querySelector('.toa-tdb-gallery-total');
        var counterEl  = document.querySelector('.toa-tdb-gallery-counter');
        var prevBtn    = document.querySelector('.toa-tdb-gallery-prev');
        var nextBtn    = document.querySelector('.toa-tdb-gallery-next');
        var thumbsEl   = $('#tdbGalleryThumbs');
        var total      = TD.galleryMedia.length;

        // Fade e cambio src
        if (imgEl) {
            var capturedIdx = idx;
            // FIX 2026-06-16 marco — salta foto rotta (es. profilo sorgente morta) e mostra la successiva
            imgEl.onload = function () {
                if (imgEl.naturalWidth > 0 && imgEl.naturalWidth <= 300 && TD.galleryMedia && TD.galleryMedia.length > 1) {
                    TD.galleryMedia.splice(TD.galleryIdx, 1);
                    renderGallery(TD.galleryMedia);
                }
            };
            imgEl.onerror = function () {
                if (!TD.galleryMedia || TD.galleryMedia.length <= 1) return;
                TD.galleryMedia.splice(TD.galleryIdx, 1);
                renderGallery(TD.galleryMedia);
            };
            imgEl.style.transition = 'opacity 150ms ease';
            imgEl.style.opacity = '0';
            // 2026-06-01 marco — rotazione + posizione solo per la foto profilo (item.rotazione/position dall'API)
            var gRot = parseInt(item.rotazione, 10) || 0;
            imgEl.style.transform = gRot ? ('rotate(' + gRot + 'deg) scale(' + ((gRot === 90 || gRot === 270) ? 1.35 : 1) + ')') : '';
            imgEl.style.objectPosition = (typeof item.position === 'string' && /^\d{1,3}%\s+\d{1,3}%$/.test(item.position.trim())) ? item.position.trim() : '';
            setTimeout(function () {
                if (TD.galleryIdx !== capturedIdx) return;
                imgEl.src = item.url || '';
                imgEl.style.opacity = '1';
            }, 150);
        }

        // Counter
        if (currentEl) currentEl.textContent = idx + 1;
        if (totalElC)  totalElC.textContent  = total;
        if (counterEl) counterEl.style.display = total <= 1 ? 'none' : '';

        // Frecce
        if (prevBtn) {
            prevBtn.style.display = total <= 1 ? 'none' : '';
            prevBtn.classList.toggle('is-disabled', idx === 0);
        }
        if (nextBtn) {
            nextBtn.style.display = total <= 1 ? 'none' : '';
            nextBtn.classList.toggle('is-disabled', idx === total - 1);
        }

        // Thumb highlight
        if (thumbsEl) {
            Array.prototype.forEach.call(
                thumbsEl.querySelectorAll('.toa-tdb-gallery-thumb'),
                function (t, i) { t.classList.toggle('is-active', i === idx); }
            );
        }

        scrollThumbIntoView(idx);
        preloadAdjacent(idx);
    }

    // Stub mantenuto per compatibilità con vecchi riferimenti (non più usato).
    function updateGalleryTransform() {}

    // Centra la thumb attiva nella strip scrollabile.
    function scrollThumbIntoView(idx) {
        var strip = $('#tdbGalleryThumbs');
        if (!strip || strip.hidden) return;
        var thumb = strip.querySelector('[data-idx="' + idx + '"]');
        if (!thumb) return;
        var target = thumb.offsetLeft - (strip.offsetWidth / 2) + (thumb.offsetWidth / 2);
        strip.scrollTo({ left: target, behavior: 'smooth' });
    }

    // Precarica immagini adiacenti all'indice corrente.
    function preloadAdjacent(idx) {
        [idx - 1, idx + 1].forEach(function (i) {
            if (i < 0 || i >= TD.galleryMedia.length) return;
            var m = TD.galleryMedia[i];
            if (m && m.tipo !== 'video' && m.url) {
                var img = new Image();
                img.src = m.url;
            }
        });
    }

    // Avanza al media successivo (no wrap-around).
    function tdGalleryNext() {
        if (TD.galleryIdx < TD.galleryMedia.length - 1) {
            showMedia(TD.galleryIdx + 1);
        }
    }

    // Torna al media precedente (no wrap-around).
    function tdGalleryPrev() {
        if (TD.galleryIdx > 0) {
            showMedia(TD.galleryIdx - 1);
        }
    }

    // Aggiunge handler touch per swipe orizzontale (>50px) su mobile.
    function wireGallerySwipe() {
        var gallery = document.querySelector('.toa-tdb-gallery-main');
        if (!gallery) return;
        var startX = 0, startY = 0, deltaX = 0, deltaY = 0, isTouching = false;
        gallery.addEventListener('touchstart', function (e) {
            if (!e.touches.length) return;
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
            deltaX = 0; deltaY = 0;
            isTouching = true;
        }, { passive: true });
        gallery.addEventListener('touchmove', function (e) {
            if (!isTouching || !e.touches.length) return;
            deltaX = e.touches[0].clientX - startX;
            deltaY = e.touches[0].clientY - startY;
        }, { passive: true });
        gallery.addEventListener('touchend', function () {
            if (!isTouching) return;
            isTouching = false;
            // Solo swipe orizzontale (ignora scroll verticale).
            if (Math.abs(deltaX) > SWIPE_THRESHOLD && Math.abs(deltaX) > Math.abs(deltaY)) {
                if (deltaX < 0) tdGalleryNext();
                else tdGalleryPrev();
            }
        });
        // Mouse drag (desktop) — pointer non-touch; il touch è gestito sopra.
        var mDown = false, mStartX = 0, mStartY = 0;
        gallery.addEventListener('dragstart', function (e) { e.preventDefault(); });
        gallery.addEventListener('pointerdown', function (e) {
            if (e.pointerType === 'touch') return;
            mDown = true; mStartX = e.clientX; mStartY = e.clientY;
        });
        gallery.addEventListener('pointerup', function (e) {
            if (e.pointerType === 'touch' || !mDown) return;
            mDown = false;
            var mdx = e.clientX - mStartX, mdy = e.clientY - mStartY;
            if (Math.abs(mdx) > SWIPE_THRESHOLD && Math.abs(mdx) > Math.abs(mdy)) {
                if (mdx < 0) tdGalleryNext(); else tdGalleryPrev();
            }
        });
    }

    // ═════════════════════════════════════════════════════════════════
    // CART (selezione fluttuante)
    // ═════════════════════════════════════════════════════════════════

    // Aggiunge/rimuove un talent dalla selezione + aggiorna UI e localStorage.
    function tdToggleSelected(id) {
        id = parseInt(id, 10);
        if (!id) return;
        if (TD.selectedIds.has(id)) {
            TD.selectedIds.delete(id);
            TD.selectedTalents.delete(id);
        } else {
            TD.selectedIds.add(id);
            var t = TD.results.find(function (r) { return r.id === id; });
            if (!t && TD.modalTalent && TD.modalTalent.id === id) t = TD.modalTalent;
            TD.selectedTalents.set(id, { id: id, nome: t ? (t.nome || '') : '' });
        }
        saveSelectedToStorage();
        updateCardSelectedState(id);
        updateCart();
        if (TD.modalTalent && TD.modalTalent.id === id) updateModalAddBtn();
    }

    // Aggiorna count cart + label singolare/plurale + slide-in/out animato.
    function updateCart() {
        var cart = $('#tdbCart');
        if (!cart) return;
        var count = TD.selectedIds.size;
        var countEl = $('#tdbCartCount');
        var labelEl = $('#tdbCartLabel');
        if (countEl) countEl.textContent = count;
        if (labelEl) labelEl.textContent = count === 1 ? i18n('cart_singular') : i18n('cart_plural');
        if (count > 0) {
            cart.hidden = false;
            // Force reflow così la transition parte dal valore "off-screen".
            void cart.offsetWidth;
            cart.classList.add('show');
        } else {
            cart.classList.remove('show');
            setTimeout(function () {
                if (TD.selectedIds.size === 0) cart.hidden = true;
            }, 500);
        }
    }

    // Svuota la selezione (cart "Svuota" o dopo invio richiesta).
    function clearSelection() {
        var ids = Array.from(TD.selectedIds);
        TD.selectedIds.clear();
        TD.selectedTalents.clear();
        saveSelectedToStorage();
        ids.forEach(updateCardSelectedState);
        updateCart();
        if (TD.modalTalent) updateModalAddBtn();
    }

    // ═════════════════════════════════════════════════════════════════
    // FORM RICHIESTA
    // ═════════════════════════════════════════════════════════════════

    // Apre il modal form richiesta (no-op se selezione vuota).
    function tdOpenRequestForm() {
        if (TD.selectedIds.size === 0) return;
        renderRequestSummary();
        $('#tdbRequestModal').hidden = false;
        document.body.style.overflow = 'hidden';
        var msg = $('#tdbRequestMsg');
        msg.hidden = true;
        msg.textContent = '';
    }

    // Chiude il modal form richiesta.
    function closeRequestModal() {
        $('#tdbRequestModal').hidden = true;
        if (!anyOtherModalOpen()) document.body.style.overflow = '';
    }

    // Renderizza i chip della selezione corrente con button "✕" per rimuovere.
    function renderRequestSummary() {
        var summary = $('#tdbRequestSummary');
        var html = Array.from(TD.selectedIds).map(function (id) {
            var t = TD.selectedTalents.get(id) || TD.results.find(function (r) { return r.id === id; });
            var name = (t && t.nome) ? t.nome : '#' + id;
            return '<span class="toa-tdb-form-summary-chip">' +
                     escapeHtml(name) +
                     ' <button type="button" data-remove="' + id + '" aria-label="✕">✕</button>' +
                   '</span>';
        }).join('');
        summary.innerHTML = html;
    }

    // Submit form: POST a REQUEST_URL + on success svuota selezione e mostra success modal.
    function tdSubmitRequest(e) {
        e.preventDefault();
        var form = e.target;
        if (form.honeypot_url && form.honeypot_url.value) return;  // bot
        if (TD.selectedIds.size === 0) { closeRequestModal(); return; }

        var submit = $('#tdbRequestSubmit');
        submit.disabled = true;

        var data = {
            nome:          form.nome.value.trim(),
            email:         form.email.value.trim(),
            telefono:      form.telefono.value.trim(),
            azienda:       form.azienda.value.trim(),
            progetto:      form.progetto.value.trim(),
            data_progetto: form.data_progetto.value || null,
            gdpr_consent:  form.gdpr_consent.checked ? 1 : 0,
            talent_ids:    Array.from(TD.selectedIds),
            lang:          LANG
        };

        fetchJson(REQUEST_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
            .then(function (res) {
                if (res && res.ok) {
                    closeRequestModal();
                    openSuccess();
                    clearSelection();
                    form.reset();
                } else {
                    showFormError((res && res.message) || i18n('form_error'));
                }
            })
            .catch(function () { showFormError(i18n('form_error')); })
            .then(function () { submit.disabled = false; });
    }

    // Mostra messaggio errore inline sotto il form richiesta.
    function showFormError(msg) {
        var m = $('#tdbRequestMsg');
        m.className = 'toa-tdb-form-msg error';
        m.textContent = msg;
        m.hidden = false;
    }

    // Apre il modal di successo (post invio).
    function openSuccess() {
        $('#tdbSuccess').hidden = false;
        document.body.style.overflow = 'hidden';
    }

    // Chiude il modal di successo.
    function closeSuccess() {
        $('#tdbSuccess').hidden = true;
        if (!anyOtherModalOpen()) document.body.style.overflow = '';
    }

    // ═════════════════════════════════════════════════════════════════
    // SIDEBAR DRAWER (mobile)
    // ═════════════════════════════════════════════════════════════════

    // 2026-06-05 marco — aggiorna il pulsante Filtri ETICHETTATO (icona + testo + aria).
    // open=true → "✕ Nascondi filtri"; open=false → "☰ Mostra filtri".
    function tdSetFilterToggle(open) {
        var db = $('#tdbSidebarToggle');
        if (!db) return;
        var icon = db.querySelector('.toa-tdb-sidebar-toggle-icon');
        var text = db.querySelector('.toa-tdb-sidebar-toggle-text');
        if (icon) icon.textContent = open ? '✕' : '☰';
        if (text) text.textContent = open ? (db.getAttribute('data-label-hide') || 'Nascondi filtri')
                                          : (db.getAttribute('data-label-show') || 'Mostra filtri');
        db.setAttribute('aria-expanded', open ? 'true' : 'false');
        db.classList.toggle('is-open', open);
    }

    // Apre/chiude il drawer filtri su mobile + classe body per backdrop.
    function toggleSidebar(open) {
        $('#tdbSidebar').classList.toggle('open', open);
        document.body.classList.toggle('tdb-drawer-open', open);
        $('#tdbFiltersToggle').setAttribute('aria-expanded', open ? 'true' : 'false');
        // su mobile il pulsante Filtri apre/chiude il drawer: sincronizza etichetta/aria.
        if (window.matchMedia('(max-width: 767px)').matches) tdSetFilterToggle(open);
    }

    // ═════════════════════════════════════════════════════════════════
    // URL / HISTORY
    // ═════════════════════════════════════════════════════════════════

    // Costruisce un URLSearchParams a partire dai filtri correnti + page.
    function buildQueryFromFilters(filters, page) {
        var params = new URLSearchParams();
        Object.keys(filters).forEach(function (k) {
            var v = filters[k];
            if (Array.isArray(v)) v.forEach(function (x) { params.append(k, x); });
            else params.set(k, String(v));
        });
        if (page > 1) params.set('page', page);
        return params;
    }

    // replaceState con i filtri serializzati (preserva ?talent se aperto).
    function updateUrl() {
        var params = buildQueryFromFilters(TD.filters, TD.page);
        var current = new URLSearchParams(window.location.search);
        if (current.get('tid')) params.set('tid', current.get('tid'));
        var qs = params.toString();
        var url = window.location.pathname + (qs ? '?' + qs : '');
        window.history.replaceState({ kind: 'list' }, '', url);
    }

    // pushState quando si apre il modal scheda (così il back button chiude il modal).
    function pushUrlTalent(id) {
        var params = buildQueryFromFilters(TD.filters, TD.page);
        params.set('tid', id);
        window.history.pushState({ kind: 'talent', id: id }, '',
            window.location.pathname + '?' + params.toString());
    }

    // replaceState rimuovendo ?talent quando il modal si chiude.
    function clearUrlTalent() {
        var params = buildQueryFromFilters(TD.filters, TD.page);
        var qs = params.toString();
        var url = window.location.pathname + (qs ? '?' + qs : '');
        window.history.replaceState({ kind: 'list' }, '', url);
    }

    // Legge ?page dalla URL prima del fetch options (usato dalla prima search).
    function applyUrlStateBeforeOptions() {
        var params = new URLSearchParams(window.location.search);
        var page = parseInt(params.get('page') || '1', 10);
        TD.page = page > 0 ? page : 1;
    }

    // Pre-popola i campi filtro dai parametri URL (chiamata dopo il fetch options).
    function applyUrlStateAfterOptions() {
        var params = new URLSearchParams(window.location.search);
        var f = $('#tdbFilters');
        function setVal(name, val) {
            if (f[name] && val !== null && val !== undefined) f[name].value = val;
        }
        setVal('q', params.get('q'));
        setVal('paese', params.get('paese'));
        // FIX 2026-06-20 marco — #3: etnia/capelli/occhi multi → spunta i checkbox da URL
        msSet('etnia',   params.getAll('etnia'));
        msSet('capelli', params.getAll('capelli'));
        msSet('occhi',   params.getAll('occhi'));
        ['eta_min', 'eta_max', 'altezza_min', 'altezza_max', 'scarpe_min', 'scarpe_max',
         'valutazione_min', 'valutazione_max'].forEach(function (n) {
            setVal(n, params.get(n));
        });
        var sesso = params.get('sesso') || '';
        if (sesso) {
            var grp = $('.toa-tdb-toggle-group[data-name="sesso"]');
            if (grp) {
                $$('.toa-tdb-toggle', grp).forEach(function (b) {
                    b.classList.toggle('active', b.dataset.value === sesso);
                });
                grp.querySelector('input[type="hidden"]').value = sesso;
            }
        }
        msSet('taglia', params.getAll('taglia'));   // FIX 2026-06-20 marco — #3
        var provincia = params.get('provincia');
        if (provincia) {
            // Le province sono popolate dopo populateProvinces(); deferiamo al microtask.
            setTimeout(function () { setVal('provincia', provincia); }, 0);
        }
    }

    // Apre il modal scheda se la URL contiene ?talent=ID (deep-link).
    function maybeOpenTalentFromUrl() {
        var params = new URLSearchParams(window.location.search);
        var tid = parseInt(params.get('tid') || '0', 10);
        if (tid > 0) tdOpenTalentModal(tid);
    }

    // Listener back/forward: ripristina modal scheda o lo chiude.
    window.addEventListener('popstate', function (e) {
        var st = e.state;
        if (st && st.kind === 'talent') {
            tdOpenTalentModal(st.id);
        } else if (!$('#tdbTalentModal').hidden) {
            var vid = $('#tdbGalleryVideo');
            if (vid) { vid.pause(); vid.src = ''; }
            $('#tdbTalentModal').hidden = true;
            if (!anyOtherModalOpen()) document.body.style.overflow = '';
            TD.modalTalent = null;
            TD.galleryMedia = [];
        }
    });

    // ═════════════════════════════════════════════════════════════════
    // EVENTS / WIRING
    // ═════════════════════════════════════════════════════════════════

    // Wiring submit/reset del form filtri + cambio paese + load-more.
    function wireFiltersForm() {
        $('#tdbFilters').addEventListener('submit', function (e) {
            e.preventDefault();
            tdSearch(false);
            if (window.matchMedia('(max-width: 767px)').matches) toggleSidebar(false);
        });
        $('#tdbFiltersReset').addEventListener('click', function () {
            $('#tdbFilters').reset();
            $$('.toa-tdb-toggle-group').forEach(function (g) {
                $$('.toa-tdb-toggle', g).forEach(function (b) {
                    b.classList.toggle('active', b.dataset.value === '');
                });
                var hidden = g.querySelector('input[type="hidden"]');
                if (hidden) hidden.value = '';
            });
            $$('.toa-tdb-chip-group .toa-tdb-chip').forEach(function (c) { c.classList.remove('active'); });
            msClearAll();   // FIX 2026-06-20 marco — #3 svuota i multi-select
            TD.selectedProvinces = []; TD.geoHub = null;
            populateProvinces();
            tdSearch(false);
        });
        // Paese: cambia paese → azzera zona e ricostruisci il geo, poi ricerca
        $('#tdbFilterCountry').addEventListener('change', function () {
            TD.selectedProvinces = []; TD.geoHub = null;
            populateProvinces();
            tdSearch(false);
        });

        // Auto-search su change dei select restanti
        ['tdbFilterProvince', 'tdbFilterEthnicity', 'tdbFilterHair', 'tdbFilterEyes'].forEach(function (id) {
            var el = $('#' + id);
            if (el) el.addEventListener('change', function () { tdSearch(false); });
        });

        // Ricerca per nome: debounced 400ms
        var qInput = $('#tdbFilters').q;
        if (qInput) qInput.addEventListener('input', debounce(function () { tdSearch(false); }, 400));

        // Range numerici: search su change (non su ogni tasto)
        var f = $('#tdbFilters');
        ['eta_min', 'eta_max', 'altezza_min', 'altezza_max', 'scarpe_min', 'scarpe_max',
         'valutazione_min', 'valutazione_max'].forEach(function (name) {
            if (f[name]) f[name].addEventListener('change', function () { tdSearch(false); });
        });

        $('#tdbLoadMore').addEventListener('click', function () {
            TD.page++;
            tdSearch(true);
        });
    }

    // Wiring toggle group (gender): selezione esclusiva con classe active + auto-search.
    function wireToggleGroups() {
        $$('.toa-tdb-toggle-group').forEach(function (g) {
            g.addEventListener('click', function (e) {
                var b = e.target.closest('.toa-tdb-toggle');
                if (!b) return;
                $$('.toa-tdb-toggle', g).forEach(function (x) { x.classList.remove('active'); });
                b.classList.add('active');
                var hidden = g.querySelector('input[type="hidden"]');
                if (hidden) hidden.value = b.dataset.value;
                tdSearch(false);
            });
        });
    }

    // Wiring chip group (taglia): toggle multi-selezione + auto-search.
    function wireChipGroups() {
        $$('.toa-tdb-chip-group').forEach(function (g) {
            g.addEventListener('click', function (e) {
                var c = e.target.closest('.toa-tdb-chip');
                if (!c) return;
                c.classList.toggle('active');
                tdSearch(false);
            });
        });
    }

    // Click delegato sulla grid: card -> apre modal, button "+/✓" -> toggle selezione.
    function wireGridDelegated() {
        $('#tdbGrid').addEventListener('click', function (e) {
            var card = e.target.closest('.toa-tdb-card');
            if (!card) return;
            var id = parseInt(card.dataset.id, 10);
            if (e.target.closest('[data-add]')) {
                e.stopPropagation(); e.preventDefault();
                tdToggleSelected(id);
                return;
            }
            if (e.target.closest('.toa-tdb-card-nav')) return;       // frecce: gestite altrove
            if (e.metaKey || e.ctrlKey || e.shiftKey) return;        // cmd/ctrl-click → apri scheda in nuova scheda
            e.preventDefault();                                      // click normale → modal, niente navigazione
            tdOpenTalentModal(id);
        });
    }

    // Wiring frecce + thumbnail strip della galleria.
    function wireGalleryNav() {
        var prev   = document.querySelector('.toa-tdb-gallery-prev');
        var next   = document.querySelector('.toa-tdb-gallery-next');
        var thumbs = $('#tdbGalleryThumbs');
        if (prev)   prev.addEventListener('click', tdGalleryPrev);
        if (next)   next.addEventListener('click', tdGalleryNext);
        if (thumbs) thumbs.addEventListener('click', function (e) {
            var t = e.target.closest('[data-idx]');
            if (!t) return;
            showMedia(parseInt(t.dataset.idx, 10) || 0);
        });
    }

    // Wiring "Svuota" e "Richiedi info" del cart.
    function wireCart() {
        var cl = $('#tdbCartClear'), rq = $('#tdbCartRequest');
        if (cl) cl.addEventListener('click', clearSelection);
        if (rq) rq.addEventListener('click', tdOpenRequestForm);
    }

    // Wiring submit del form richiesta + rimozione chip dal summary.
    function wireRequestForm() {
        var form = $('#tdbRequestForm');
        var summ = $('#tdbRequestSummary');
        if (form) form.addEventListener('submit', tdSubmitRequest);
        if (summ) summ.addEventListener('click', function (e) {
            var btn = e.target.closest('button[data-remove]');
            if (!btn) return;
            var id = parseInt(btn.dataset.remove, 10);
            tdToggleSelected(id);
            renderRequestSummary();
            if (TD.selectedIds.size === 0) closeRequestModal();
        });
    }

    // Click delegato per chiudere modali (data-tdb-close=1) + ESC + add nel modal.
    function wireModalCloses() {
        document.body.addEventListener('click', function (e) {
            if (!e.target.dataset || e.target.dataset.tdbClose !== '1') return;
            var m1 = $('#tdbTalentModal'), m2 = $('#tdbRequestModal'), m3 = $('#tdbSuccess'), m4 = $('#tdbLockedModal');
            if (m1 && !m1.hidden && e.target.closest('#tdbTalentModal'))     tdCloseTalentModal();
            else if (m2 && !m2.hidden && e.target.closest('#tdbRequestModal')) closeRequestModal();
            else if (m3 && !m3.hidden && e.target.closest('#tdbSuccess'))      closeSuccess();
            else if (m4 && !m4.hidden && e.target.closest('#tdbLockedModal'))  closeLockedModal();
        });
        document.addEventListener('keydown', function (e) {
            var t = $('#tdbTalentModal');
            if (t && !t.hidden) {
                if (e.key === 'ArrowLeft')  { tdGalleryPrev(); return; }
                if (e.key === 'ArrowRight') { tdGalleryNext(); return; }
            }
            if (e.key !== 'Escape') return;
            var s = $('#tdbSuccess'), r = $('#tdbRequestModal');
            var l = $('#tdbLockedModal');
            var sb = $('#tdbSidebar');
            if (l && !l.hidden)  { closeLockedModal(); return; }
            if (s && !s.hidden)  { closeSuccess(); return; }
            if (r && !r.hidden)  { closeRequestModal(); return; }
            if (t && !t.hidden)  { tdCloseTalentModal(); return; }
            if (sb && sb.classList.contains('open')) toggleSidebar(false);
        });
        var addBtn = $('#tdbModalAdd');
        if (addBtn) addBtn.addEventListener('click', function () {
            if (TD.modalTalent) tdToggleSelected(TD.modalTalent.id);
        });
    }

    // Wiring del bottone toggle drawer filtri (mobile).
    function wireSidebarDrawer() {
        // 2026-06-17 marco — #8 toggle "Più filtri" (livello 2: etnia/taglia/capelli/occhi/scarpe/rating/categoria)
        var more = $('#tdbMoreFilters');
        if (more) more.addEventListener('click', function () {
            var ff = $('#tdbFilters'); var open = !ff.classList.contains('tdb-adv-open');
            ff.classList.toggle('tdb-adv-open', open);
            more.setAttribute('aria-expanded', open ? 'true' : 'false');
            var ar = more.querySelector('.toa-tdb-flt-more-ar'); if (ar) ar.textContent = open ? '▴' : '▾';
        });
        // 2026-06-17 marco — #6 FILTRI: pannello full-width sotto la barra, pulsante a sx con stato on/off.
        // FILTRI (#tdbFiltersBtn) fa toggle di .tdb-filters-open sul wrap; la ✕ nel pannello (#tdbSidebarToggle) chiude.
        var wrap = $('#tdb-database');
        var fbtn = $('#tdbFiltersBtn');
        var closeBtn = $('#tdbSidebarToggle');
        function setFiltersOpen(open) {
            if (!wrap) return;
            wrap.classList.toggle('tdb-filters-open', open);
            if (fbtn) {
                fbtn.setAttribute('aria-expanded', open ? 'true' : 'false');
                fbtn.classList.toggle('is-open', open);
            }
        }
        if (fbtn && wrap) {
            fbtn.addEventListener('click', function () {
                setFiltersOpen(!wrap.classList.contains('tdb-filters-open'));
            });
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', function () { setFiltersOpen(false); });
        }
        // Default: pannello CHIUSO.
        setFiltersOpen(false);

        // 2026-06-05 marco — ACCORDION macro: click su un macro apre/chiude il suo sottomenu (toggle indipendente).
        // FIX 2026-06-05: i macro <a href> (es. tdbMacro2/Crew) sono link diretti — skip accordion, navigazione nativa.
        var macros = $$('.toa-tdb-macro');
        macros.forEach(function (m) {
            if (m.tagName === 'A') return; // link diretto, niente accordion
            if (m.id === 'tdbFiltersBtn' || m.classList.contains('toa-tdb-filters-btn')) return; // FILTRI: gestito sopra, niente accordion
            m.addEventListener('click', function () {
                var panel = document.getElementById(m.getAttribute('aria-controls'));
                var open = m.getAttribute('aria-expanded') !== 'true';
                m.setAttribute('aria-expanded', open ? 'true' : 'false');
                m.classList.toggle('is-open', open);
                if (panel) panel.hidden = !open;
            });
        });

        // 2026-06-05 marco — chip categoria bloccate (cliccabili) → modale "database in arrivo".
        // Delegato sull'intero wrapper. Il link "Crew" (<a>) non è bloccato → naviga a /crew-database/.
        var catGroups = $('#tdbCatGroups');
        if (catGroups) {
            catGroups.addEventListener('click', function (e) {
                // 2026-06-17 marco — FIX: anche il pulsante macro "Backstage Crew" (<a .toa-tdb-macro--link>)
                // va forzato come il chip Crew: un handler del tema blocca la navigazione nativa del link.
                var macroLink = e.target.closest('.toa-tdb-macro--link');
                if (macroLink) {
                    var mhref = macroLink.getAttribute('href');
                    if (mhref) { window.location.href = mhref; return; }
                }
                // 2026-06-05 marco — FIX: il chip "Crew" è un <a href=/crew-database/>. Forziamo la
                // navigazione esplicitamente, così nessun altro handler (presente o futuro) può bloccarla.
                var crew = e.target.closest('.toa-tdb-cat-chip--crew');
                if (crew) {
                    var href = crew.getAttribute('href');
                    if (href) { window.location.href = href; return; }
                }
                var locked = e.target.closest('.toa-tdb-cat-chip--locked');
                if (!locked) return;
                e.preventDefault();
                openLockedModal(locked.getAttribute('data-cat') || '');
            });
        }

        // chip "Talent" → ricerca senza filtro ruolo (mostra tutti). Ignora le bloccate (gestite sopra).
        var chips = $('#tdbCatChips');
        if (chips) {
            chips.addEventListener('click', function (e) {
                var chip = e.target.closest('.toa-tdb-cat-chip');
                if (!chip || chip.classList.contains('toa-tdb-cat-chip--locked') || chip.classList.contains('toa-tdb-cat-chip--crew')) return;
                $$('.toa-tdb-cat-chip', chips).forEach(function (c) { c.classList.toggle('is-active', c === chip); });
                var sel = $('#tdbFilterRuolo');
                if (sel) {
                    sel.value = chip.getAttribute('data-ruolo') || '';
                    sel.dispatchEvent(new Event('change', { bubbles: true }));
                }
                var form = $('#tdbFilters');
                if (form) form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            });
        }
    }

    // ═════════════════════════════════════════════════════════════════
    // BOOT
    // ═════════════════════════════════════════════════════════════════
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', tdInit);
    } else {
        tdInit();
    }
})();

;/* 2026-06-16 marco — frecce scorri-foto sulle card della griglia (lazy, no apertura scheda) */
(function(){
  var API = window.toaTdbApiUrl || '/actions/api-talent-database.php';
  function ensureNav(card){
    if(!card || card.querySelector('.toa-tdb-card-nav')) return;
    if(!card.querySelector('.toa-tdb-card-img')) return;
    ['-1','1'].forEach(function(dir){
      var b=document.createElement('button'); b.type='button';
      b.className='toa-tdb-card-nav '+(dir==='-1'?'toa-tdb-cn-prev':'toa-tdb-cn-next');
      b.setAttribute('data-cardnav',dir);
      b.setAttribute('aria-label',dir==='-1'?'Foto precedente':'Foto successiva');
      b.textContent=dir==='-1'?'‹':'›';
      card.appendChild(b);
    });
  }
  document.addEventListener('pointerover', function(e){
    var card=e.target.closest && e.target.closest('.toa-tdb-card');
    if(card) ensureNav(card);
  }, true);
  document.addEventListener('click', function(e){
    var btn=e.target.closest && e.target.closest('.toa-tdb-card-nav');
    if(!btn) return;
    e.preventDefault(); e.stopPropagation();
    var card=btn.closest('.toa-tdb-card'); if(!card) return;
    var dir=parseInt(btn.getAttribute('data-cardnav'),10)||1;
    var img=card.querySelector('.toa-tdb-card-img'); if(!img) return;
    function cycle(media){
      if(!media||media.length<=1) return;
      var idx=parseInt(card.getAttribute('data-cnidx')||'0',10);
      idx=(idx+dir+media.length)%media.length;
      card.setAttribute('data-cnidx',idx);
      img.src=media[idx];
    }
    if(card._cnMedia){ cycle(card._cnMedia); return; }
    btn.disabled=true;
    fetch(API+'?action=talent&id='+encodeURIComponent(card.getAttribute('data-id'))).then(function(r){return r.json();}).then(function(d){
      var cur=img.getAttribute('src')||'';
      var isFallback=cur.indexOf('foto-talent-public')===-1;
      var media=((d&&d.media)||[]).map(function(m){return m.url;}).filter(Boolean);
      if(isFallback) media=media.filter(function(u){return u.indexOf('foto-talent-public')===-1;});
      card._cnMedia=media.length?media:[cur];
      var st=card._cnMedia.indexOf(cur);
      card.setAttribute('data-cnidx', st>=0?st:0);
      btn.disabled=false;
      cycle(card._cnMedia);
    }).catch(function(){ btn.disabled=false; });
  }, true);
})();

;/* swipe-card-foto 2026-06-16 marco — trascina foto card (mouse+dito), riusa media frecce */
(function(){
  var API=window.toaTdbApiUrl||'/actions/api-talent-database.php';
  function getMedia(card,cb){
    if(card._cnMedia){cb(card._cnMedia);return;}
    var img=card.querySelector('.toa-tdb-card-img');if(!img){cb(null);return;}
    fetch(API+'?action=talent&id='+encodeURIComponent(card.getAttribute('data-id'))).then(function(r){return r.json();}).then(function(d){
      var cur=img.getAttribute('src')||'';var isF=cur.indexOf('foto-talent-public')===-1;
      var m=((d&&d.media)||[]).map(function(x){return x.url;}).filter(Boolean);
      if(isF)m=m.filter(function(u){return u.indexOf('foto-talent-public')===-1;});
      card._cnMedia=m.length?m:[cur];var st=card._cnMedia.indexOf(cur);
      card.setAttribute('data-cnidx',st>=0?st:0);cb(card._cnMedia);
    }).catch(function(){cb(null);});
  }
  function cycle(card,dir){getMedia(card,function(m){
    if(!m||m.length<=1)return;var img=card.querySelector('.toa-tdb-card-img');if(!img)return;
    var i=parseInt(card.getAttribute('data-cnidx')||'0',10);i=(i+dir+m.length)%m.length;
    card.setAttribute('data-cnidx',i);img.src=m[i];
  });}
  var sx=0,sy=0,trk=false,cur=null;
  document.addEventListener('pointerdown',function(e){
    var card=e.target.closest&&e.target.closest('.toa-tdb-card');if(!card)return;
    if(e.target.closest('.toa-tdb-card-nav')||e.target.closest('.toa-tdb-card-add'))return;
    trk=true;cur=card;sx=e.clientX;sy=e.clientY;
  },true);
  document.addEventListener('pointerup',function(e){
    if(!trk||!cur)return;var dx=e.clientX-sx,dy=e.clientY-sy;trk=false;
    if(Math.abs(dx)>40&&Math.abs(dx)>Math.abs(dy)){
      cycle(cur,dx<0?1:-1);
      var b=function(ev){ev.preventDefault();ev.stopPropagation();document.removeEventListener('click',b,true);};
      document.addEventListener('click',b,true);setTimeout(function(){document.removeEventListener('click',b,true);},400);
    }
    cur=null;
  },true);
})();

;/* no-native-drag 2026-06-16 marco — disattiva drag nativo immagini card così lo swipe funziona */
(function(){document.addEventListener('dragstart',function(e){if(e.target&&e.target.closest&&e.target.closest('.toa-tdb-card'))e.preventDefault();},true);})();
