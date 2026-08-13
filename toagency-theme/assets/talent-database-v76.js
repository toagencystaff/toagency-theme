// FIX 2026-07-03 marco — codice talent a video: A687 invece di 9000687 (id>=9M), legacy invariati; solo display, DB intatto
function tdCodeDisplay(id){id=parseInt(id,10)||0;return id>=9000000?('A'+(id-9000000)):String(id);}
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
 * v76.2 — 2026-08-11 marco — cover card da album eventi (search: campo `cover`, URL pronto w=400,
 *   fallback foto profilo) + livelli lingue (`lingue_liv`: base/fluente/madrelingua|null, slug
 *   "altro"→label libera; il QCER resta lato API, il tema non lo vede mai).
 * v76 — 2026-08-11 marco — HOSTESS EVENTI: campi API `lingue` (csv) e `automunito` (1|null)
 *   su card+scheda (solo se presenti, mai se null); filtri lingua[]/automunito (API li accetta
 *   solo con ruolo=hostess); deep-link: ruolo/province/lingua/automunito letti E scritti in URL.
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
        // 2026-08-11 marco — HOSTESS EVENTI
        modal_langs:     { it: 'Lingue',    en: 'Languages',   fr: 'Langues',     es: 'Idiomas' },
        modal_car:       { it: 'Automunito', en: 'Own car',    fr: 'Véhiculé',    es: 'Con coche' },
        yes_label:       { it: 'Sì',        en: 'Yes',         fr: 'Oui',         es: 'Sí' },
        years:           { it: 'anni',               en: 'years',              fr: 'ans',                es: 'años' },
        // FIX 2026-06-26 marco — tutela immagine minori (card)
        minor_label:   { it: 'Profilo minore', en: 'Minor profile', fr: 'Profil mineur', es: 'Perfil de menor' }, // FIX 2026-07-09 marco: testo essenziale (la scritta lunga rendeva la card illeggibile / sembrava foto mancante)
        minor_explain: {
            it: 'Le immagini dei minori di 18 anni sono tutelate e non visibili pubblicamente. Per ricevere una selezione di profili disponibili, contatta TOAgency.',
            en: 'Images of minors under 18 are protected and not shown publicly. To receive a selection of available profiles, contact TOAgency.',
            fr: 'Les images des mineurs de moins de 18 ans sont protégées et non visibles publiquement. Pour recevoir une sélection de profils disponibles, contactez TOAgency.',
            es: 'Las imágenes de los menores de 18 años están protegidas y no son visibles públicamente. Para recibir una selección de perfiles disponibles, contacta con TOAgency.'
        },
        minor_cta:     { it: 'Richiedi info', en: 'Request info', fr: 'Demander des infos', es: 'Solicitar info' },
        form_error: {
            it: 'Errore nell\'invio. Riprova oppure scrivi a info@toagency.it',
            en: 'Send error. Try again or write to info@toagency.it',
            fr: 'Erreur. Réessaye ou écris à info@toagency.it',
            es: 'Error. Inténtalo o escribe a info@toagency.it'
        }
    };

    // FIX 2026-06-24 marco — mappa display VALORI dal DB (genere/etnia/capelli/occhi) it->en/fr/es.
    // Il valore REALE non cambia (filtro/API restano sull'italiano): traduce solo il testo mostrato.
    var VAL_I18N = {
        // genere
        'Femmina': { en:'Female', fr:'Femme', es:'Mujer' },
        'Maschio': { en:'Male',   fr:'Homme', es:'Hombre' },
        'Altro':   { en:'Other',  fr:'Autre', es:'Otro' },
        // etnia
        'Asiatico':        { en:'Asian',          fr:'Asiatique',        es:'Asiático' },
        'Bianco Caucasico':{ en:'White Caucasian', fr:'Blanc caucasien',  es:'Blanco caucásico' },
        'Ispanico':        { en:'Hispanic',        fr:'Hispanique',       es:'Hispano' },
        'Magrebino':       { en:'Maghrebi',        fr:'Maghrébin',        es:'Magrebí' },
        'Mediorientale':   { en:'Middle Eastern',  fr:'Moyen-oriental',   es:'Oriente Medio' },
        'Nero Africano':   { en:'Black African',   fr:'Noir africain',    es:'Negro africano' },
        'Sudasiatico':     { en:'South Asian',     fr:'Sud-asiatique',    es:'Sudasiático' },
        'Mixed':           { en:'Mixed',           fr:'Métis',            es:'Mixto' },
        // capelli
        'Bianco':         { en:'White',       fr:'Blanc',         es:'Blanco' },
        'Biondo Chiaro':  { en:'Light Blond', fr:'Blond clair',   es:'Rubio claro' },
        'Biondo Scuro':   { en:'Dark Blond',  fr:'Blond foncé',   es:'Rubio oscuro' },
        'Calvo':          { en:'Bald',        fr:'Chauve',        es:'Calvo' },
        'Castano Chiaro': { en:'Light Brown', fr:'Châtain clair', es:'Castaño claro' },
        'Castano Scuro':  { en:'Dark Brown',  fr:'Châtain foncé', es:'Castaño oscuro' },
        'Grigio':         { en:'Grey',        fr:'Gris',          es:'Gris' },
        'Nero':           { en:'Black',       fr:'Noir',          es:'Negro' },
        'Rosso':          { en:'Red',         fr:'Roux',          es:'Pelirrojo' },
        // occhi
        'altro':   { en:'Other',  fr:'Autre',  es:'Otro' },
        'Azzurri': { en:'Blue',   fr:'Bleus',  es:'Azules' },
        'Grigi':   { en:'Grey',   fr:'Gris',   es:'Grises' },
        'Marroni': { en:'Brown',  fr:'Marron', es:'Marrones' },
        'Neri':    { en:'Black',  fr:'Noirs',  es:'Negros' },
        'Verdi':   { en:'Green',  fr:'Verts',  es:'Verdes' }
    };

    // Restituisce la stringa i18n per `key` nella lingua corrente, fallback IT.
    function i18n(key) {
        var m = I18N[key];
        return m ? (m[LANG] || m.it) : key;
    }

    // ── 2026-08-11 marco — HOSTESS EVENTI: lingue parlate (slug API → codice chip + nome tradotto) ──
    var LINGUA_CODE = { italiano:'IT', inglese:'EN', francese:'FR', spagnolo:'ES', tedesco:'DE', portoghese:'PT', russo:'RU', arabo:'AR', cinese:'ZH', giapponese:'JA' };
    var LINGUA_NAME = {
        italiano:   { it:'Italiano',   en:'Italian',    fr:'Italien',    es:'Italiano' },
        inglese:    { it:'Inglese',    en:'English',    fr:'Anglais',    es:'Inglés' },
        francese:   { it:'Francese',   en:'French',     fr:'Français',   es:'Francés' },
        spagnolo:   { it:'Spagnolo',   en:'Spanish',    fr:'Espagnol',   es:'Español' },
        tedesco:    { it:'Tedesco',    en:'German',     fr:'Allemand',   es:'Alemán' },
        portoghese: { it:'Portoghese', en:'Portuguese', fr:'Portugais',  es:'Portugués' },
        russo:      { it:'Russo',      en:'Russian',    fr:'Russe',      es:'Ruso' },
        arabo:      { it:'Arabo',      en:'Arabic',     fr:'Arabe',      es:'Árabe' },
        cinese:     { it:'Cinese',     en:'Chinese',    fr:'Chinois',    es:'Chino' },
        giapponese: { it:'Giapponese', en:'Japanese',   fr:'Japonais',   es:'Japonés' }
    };
    // API: `lingue` = "italiano,inglese" SOLO per hostess con dato fresco; null/assente → [] (non si mostra nulla).
    function lingueList(t) {
        if (!t || t.lingue == null || t.lingue === '') return [];
        return String(t.lingue).split(',').map(function (s) { return s.trim().toLowerCase(); }).filter(function (s) { return !!s; });
    }
    function linguaName(slug) { var m = LINGUA_NAME[slug]; return m ? (m[LANG] || m.it) : cap(slug); }
    function isHostessRole() { var sel = $('#tdbFilterRuolo'); return !!(sel && sel.value === 'hostess'); }

    // ── 2026-08-11 marco — v76.2: livelli lingua (contratto CRM: base|fluente|madrelingua|null) ──
    var LIV_NAME = {
        base:        { it:'base',        en:'basic',  fr:'de base',           es:'básico' },
        fluente:     { it:'fluente',     en:'fluent', fr:'courant',           es:'fluido' },
        madrelingua: { it:'madrelingua', en:'native', fr:'langue maternelle', es:'nativo' }
    };
    function livName(l) { var m = LIV_NAME[l]; return m ? (m[LANG] || m.it) : ''; }
    // Lingue con livello: preferisce `lingue_liv` [{slug,livello,label?}], fallback su `lingue` csv.
    // "altro" arriva SOLO con label libera → si mostra la label. Livello null → chip senza etichetta.
    function lingueInfo(t) {
        if (t && Array.isArray(t.lingue_liv) && t.lingue_liv.length) {
            return t.lingue_liv.map(function (x) {
                if (!x || !x.slug) return null;
                var slug = String(x.slug).toLowerCase();
                var isAltro = (slug === 'altro');
                var name = isAltro ? (x.label ? String(x.label) : '') : linguaName(slug);
                if (!name) return null;
                return { name: name,
                         code: isAltro ? name.slice(0, 2).toUpperCase() : (LINGUA_CODE[slug] || slug.slice(0, 2).toUpperCase()),
                         liv:  x.livello ? livName(String(x.livello).toLowerCase()) : '' };
            }).filter(function (x) { return !!x; });
        }
        return lingueList(t).map(function (slug) {
            return { name: linguaName(slug), code: LINGUA_CODE[slug] || slug.slice(0, 2).toUpperCase(), liv: '' };
        });
    }

    // ═════════════════════════════════════════════════════════════════
    // INIT
    // ═════════════════════════════════════════════════════════════════

    // Entry point: ripristina selezione, fa wiring, carica filter_options e prima search.
    function tdInit() {
        loadSelectedFromStorage();
        applyUrlStateBeforeOptions();

        wireFiltersForm();
        toggleHostessFilters();   // 2026-08-11 marco — HOSTESS EVENTI: stato iniziale (?ruolo= arriva dopo, via change)
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
        // FIX 2026-06-24 marco — traduce il valore mostrato (dropdown/chip/modal) se non IT; valore reale invariato
        if (LANG !== 'it' && VAL_I18N[s] && VAL_I18N[s][LANG]) return VAL_I18N[s][LANG];
        return s.charAt(0).toUpperCase() + s.slice(1);
    }

    // FIX 2026-06-20 marco — unisce residenza/domicilio: "A / B" se diversi, altrimenti uno solo.
    // Confronto tollerante: ignora accenti, non-lettere e lettere doppie → "Barcelona" == "Barcellona".
    // FIX 2026-06-24 marco — mappa display SOLO grandi città che cambiano in lingua (nomi propri).
    // Le altre città/province restano invariate. Valore reale invariato (qui è solo display).
    var CITY_I18N = {
        'milano':  { en:'Milan',    fr:'Milan',    es:'Milán' },
        'torino':  { en:'Turin',    fr:'Turin',    es:'Turín' },
        'roma':    { en:'Rome',     fr:'Rome',     es:'Roma' },
        'firenze': { en:'Florence', fr:'Florence', es:'Florencia' },
        'venezia': { en:'Venice',   fr:'Venise',   es:'Venecia' },
        'napoli':  { en:'Naples',   fr:'Naples',   es:'Nápoles' },
        'genova':  { en:'Genoa',    fr:'Gênes',    es:'Génova' },
        'padova':  { en:'Padua',    fr:'Padoue',   es:'Padua' }
    };
    function tCity(s) {
        if (!s) return s;
        if (LANG !== 'it') { var m = CITY_I18N[String(s).toLowerCase().trim()]; if (m && m[LANG]) return m[LANG]; }
        return s;
    }

    function pairStr(a, b) {
        a = String(a == null ? '' : a).trim();
        b = String(b == null ? '' : b).trim();
        function nrm(s) {
            return s.toLowerCase()
                .normalize('NFD').replace(/[̀-ͯ]/g, '')  // via accenti
                .replace(/[^a-z]/g, '')                            // solo lettere
                .replace(/(.)\1+/g, '$1');                         // collassa lettere doppie
        }
        var da = tCity(a), db = tCity(b);
        if (a && b && nrm(a) !== nrm(b)) return da + ' / ' + db;
        return da || db || '';
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
                    // 2026-08-11 marco — MINI-PANNELLO: is_minor null = selezione vecchia senza flag → niente foto (prudenza)
                    TD.selectedTalents.set(item.id, { id: item.id, nome: item.nome || '', talent_id: item.talent_id || 0, is_minor: (item.is_minor === undefined ? null : item.is_minor) });
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
                // 2026-08-11 marco — MINI-PANNELLO: persisto anche talent_id + is_minor
                out.push(t ? { id: id, nome: t.nome || '', talent_id: t.talent_id || 0, is_minor: (t.is_minor === undefined ? null : t.is_minor) } : { id: id });
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
    // 2026-08-11 marco — labelFn opzionale (lingua: etichetta tradotta, value resta lo slug API)
    function buildMs(name, values, labelFn) {
        var box = msBox(name);
        if (!box) return;
        var menu = box.querySelector('.toa-tdb-ms-menu');
        menu.innerHTML = (values || []).filter(function (v) {
            // FIX 2026-06-20 marco — "altro" NON è un'opzione filtro (resta solo nel Genere)
            return String(v).trim().toLowerCase() !== 'altro';
        }).map(function (v) {
            return '<label class="toa-tdb-ms-opt"><input type="checkbox" value="' + escapeHtml(v) +
                   '"><span>' + escapeHtml(labelFn ? labelFn(v) : cap(v)) + '</span></label>';
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
            // 2026-08-11 marco — usa l'etichetta visibile (per lingua è tradotta); identico a prima per gli altri
            txtEl.textContent = checked.map(function (c) { var sp = c.parentNode ? c.parentNode.querySelector('span') : null; return sp ? sp.textContent : cap(c.value); }).join(', ');
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

    // 2026-08-11 marco — HOSTESS EVENTI: mostra/nasconde il blocco filtri lingua+automunito (solo ruolo=hostess).
    // Quando si nasconde azzera anche i valori, così non restano filtri fantasma cambiando categoria.
    function toggleHostessFilters() {
        var box = $('#tdbHostessFilters');
        if (!box) return;
        var show = isHostessRole();
        box.hidden = !show;
        if (!show) {
            $$('.toa-tdb-ms[data-name="lingua"] .toa-tdb-ms-menu input:checked').forEach(function (c) { c.checked = false; });
            var lb = msBox('lingua'); if (lb) msText(lb);
            var f = $('#tdbFilters');
            if (f && f.automunito) f.automunito.checked = false;
        }
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
        // FIX 2026-06-20 marco — debounce: click rapidi su più caselle = 1 sola ricerca con lo stato finale
        var msSearch = debounce(function () { tdSearch(false); }, 350);
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
                msText(box);        // testo aggiornato subito
                msSearch();         // ricerca debounced
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
        // 2026-08-11 marco — HOSTESS EVENTI: 10 slug fissi da contratto API, etichette tradotte
        buildMs('lingua', ['italiano', 'inglese', 'francese', 'spagnolo', 'tedesco', 'portoghese', 'russo', 'arabo', 'cinese', 'giapponese'], linguaName);

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
        // 2026-08-11 marco — HOSTESS EVENTI: lingua[]/automunito inviati SOLO con ruolo=hostess (contratto API)
        if (isHostessRole()) {
            var lv = $$('.toa-tdb-ms[data-name="lingua"] .toa-tdb-ms-menu input:checked').map(function (c) { return c.value; });
            if (lv.length) out.lingua = lv;
            if (f.automunito && f.automunito.checked) out.automunito = '1';
        }

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

    // ── FIX 2026-06-20 marco — barra "filtri attivi": chip removibili sopra la griglia ──
    var AF_LBL = ({
        it:{nome:'Nome',genere:'Genere',eta:'Età',alt:'Altezza',sca:'Scarpe',zone:'zone',all:'Cancella tutti'},
        en:{nome:'Name',genere:'Gender',eta:'Age',alt:'Height',sca:'Shoes',zone:'areas',all:'Clear all'},
        fr:{nome:'Nom',genere:'Genre',eta:'Âge',alt:'Taille',sca:'Pointure',zone:'zones',all:'Tout effacer'},
        es:{nome:'Nombre',genere:'Género',eta:'Edad',alt:'Altura',sca:'Calzado',zone:'zonas',all:'Borrar todo'}
    })[LANG] || {nome:'Nome',genere:'Genere',eta:'Età',alt:'Altezza',sca:'Scarpe',zone:'zone',all:'Cancella tutti'};

    function afRange(chips, key, label) {
        var f = $('#tdbFilters');
        var mn = f[key+'_min'] ? f[key+'_min'].value : '';
        var mx = f[key+'_max'] ? f[key+'_max'].value : '';
        if (mn === '' && mx === '') return;
        chips.push({ k: key, label: label + ': ' + (mn||'…') + '–' + (mx||'…') });
    }

    // Costruisce le chip dei filtri attualmente attivi e gestisce la rimozione del singolo filtro.
    function renderActiveFilters() {
        var host = $('#tdbActiveFilters');
        if (!host) return;
        var f = $('#tdbFilters');
        var chips = [];
        if (f.q && f.q.value.trim()) chips.push({ k:'q', label: AF_LBL.nome+': '+f.q.value.trim() });
        var rsel = $('#tdbFilterRuolo');
        if (rsel && rsel.value) chips.push({ k:'ruolo', label: rsel.options[rsel.selectedIndex].textContent.trim() });
        var sg = $('.toa-tdb-toggle-group[data-name="sesso"]');
        if (sg) { var sa = sg.querySelector('.toa-tdb-toggle.active'); if (sa && sa.dataset.value) chips.push({ k:'sesso', label: AF_LBL.genere+': '+sa.textContent.trim() }); }
        var csel = $('#tdbFilterCountry');
        if (csel && csel.value) chips.push({ k:'paese', label: csel.options[csel.selectedIndex].textContent.trim() });
        if (TD.selectedProvinces && TD.selectedProvinces.length) chips.push({ k:'province', label: TD.selectedProvinces.length+' '+AF_LBL.zone });
        ['etnia','taglia','capelli','occhi'].forEach(function (name) {
            $$('.toa-tdb-ms[data-name="'+name+'"] .toa-tdb-ms-menu input:checked').forEach(function (c) {
                chips.push({ k:'ms', name:name, val:c.value, label: cap(c.value) });
            });
        });
        // 2026-08-11 marco — HOSTESS EVENTI: chip lingua (etichetta tradotta) + automunito
        $$('.toa-tdb-ms[data-name="lingua"] .toa-tdb-ms-menu input:checked').forEach(function (c) {
            chips.push({ k:'ms', name:'lingua', val:c.value, label: linguaName(c.value) });
        });
        if (f.automunito && f.automunito.checked) chips.push({ k:'automunito', label: '🚗 ' + i18n('modal_car') });
        afRange(chips, 'eta', AF_LBL.eta);
        afRange(chips, 'altezza', AF_LBL.alt);
        afRange(chips, 'scarpe', AF_LBL.sca);

        if (!chips.length) { host.hidden = true; host.innerHTML = ''; return; }
        host.hidden = false;
        host.innerHTML = chips.map(function (c) {
            return '<button type="button" class="toa-tdb-active-chip" data-k="'+c.k+'"'+
                (c.name?' data-name="'+escapeHtml(c.name)+'"':'')+
                (c.val?' data-val="'+escapeHtml(c.val)+'"':'')+
                '>'+escapeHtml(c.label)+' <span aria-hidden="true">✕</span></button>';
        }).join('') + '<button type="button" class="toa-tdb-active-clear" data-k="__all">'+escapeHtml(AF_LBL.all)+'</button>';

        if (!host._wired) {
            host._wired = true;
            host.addEventListener('click', function (e) {
                var b = e.target.closest('.toa-tdb-active-chip, .toa-tdb-active-clear');
                if (!b) return;
                var k = b.dataset.k;
                var ff = $('#tdbFilters');
                if (k === '__all') { $('#tdbFiltersReset').click(); return; }
                if (k === 'q') { if (ff.q) ff.q.value = ''; }
                else if (k === 'ruolo') {
                    var rs = $('#tdbFilterRuolo');
                    if (rs) { rs.value = ''; rs.dispatchEvent(new Event('change', { bubbles:true })); }
                    $$('.toa-tdb-cat-chip').forEach(function (c) { c.classList.toggle('is-active', (c.getAttribute('data-ruolo')||'') === ''); });
                }
                else if (k === 'sesso') {
                    var g = $('.toa-tdb-toggle-group[data-name="sesso"]');
                    if (g) { $$('.toa-tdb-toggle', g).forEach(function (x) { x.classList.toggle('active', x.dataset.value === ''); }); var h = g.querySelector('input[type="hidden"]'); if (h) h.value = ''; }
                }
                else if (k === 'paese') {
                    var cs = $('#tdbFilterCountry');
                    if (cs) { cs.value = ''; cs.dispatchEvent(new Event('change', { bubbles:true })); return; }
                }
                else if (k === 'province') { TD.selectedProvinces = []; TD.geoHub = null; populateProvinces(); }
                else if (k === 'ms') {
                    var box = $('.toa-tdb-ms[data-name="'+b.dataset.name+'"]');
                    if (box) { $$('.toa-tdb-ms-menu input', box).forEach(function (i) { if (i.value === b.dataset.val) i.checked = false; }); msText(box); }
                }
                else if (k === 'eta' || k === 'altezza' || k === 'scarpe') {
                    if (ff[k+'_min']) ff[k+'_min'].value = '';
                    if (ff[k+'_max']) ff[k+'_max'].value = '';
                }
                else if (k === 'automunito') { if (ff.automunito) ff.automunito.checked = false; }   // 2026-08-11 marco — HOSTESS EVENTI
                tdSearch(false);
            });
        }
    }

    // ═════════════════════════════════════════════════════════════════
    // SEARCH / RENDER
    // ═════════════════════════════════════════════════════════════════

    // POST a ?action=search con i filtri correnti. append=true per "Carica altri".
    function tdSearch(append) {
        // FIX 2026-06-20 marco — guardia "trailing": se chiamata mentre carica, rifà la ricerca a fine (no filtri persi)
        if (TD.loading) { if (!append) TD.pending = true; return Promise.resolve(); }
        TD.loading = true;

        var filters = readFilters();
        TD.filters = filters;
        renderActiveFilters();   // FIX 2026-06-20 marco — aggiorna le chip "filtri attivi"
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
            .then(function () {
                TD.loading = false;
                if (TD.pending) { TD.pending = false; tdSearch(false); }   // FIX 2026-06-20 marco — ricerca in coda
            });
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
    // ─────────────────────────────────────────────────────────────────
    // Riconoscimento del segnaposto "No photo" — AUTO-CALIBRANTE
    // 2026-08-11 marco — STORIA: il CRM risponde SEMPRE HTTP 200; quando un talent non ha foto
    // servibili manda un SVG segnaposto. Il tema lo riconosceva dalle dimensioni scritte a mano
    // (302px). Il CRM ha cambiato il segnaposto (ora 113x150), il confronto non ha piu' corrisposto
    // e il segnaposto grigio e' finito nelle card. E' gia' la seconda volta (v72: <=300 -> 302).
    // SOLUZIONE STABILE: non scriviamo piu' nessuna dimensione nel codice. All'avvio chiediamo al
    // CRM il segnaposto (l'endpoint con id=0 lo restituisce sempre), ne misuriamo le dimensioni e
    // confrontiamo le card con QUELLE. Se domani il CRM lo cambia ancora, ci adeguiamo da soli.
    // Rete di sicurezza se la sonda fallisce: le foto vere hanno sempre lato lungo 400 (w=400),
    // quindi altezza < 200 = segnaposto. NB: si guarda l'ALTEZZA, mai la larghezza — era la
    // larghezza a far sparire le foto verticali reali (caso Carol 81821, luglio 2026).
    var PH = { w: 0, h: 0, ready: false, queue: [] };
    function phReady() {
        PH.ready = true;
        var q = PH.queue; PH.queue = [];
        q.forEach(function (img) { phCheck(img); });
    }
    function phInit() {
        var probe = new Image();
        probe.onload  = function () {
            if (probe.naturalWidth && probe.naturalHeight) { PH.w = probe.naturalWidth; PH.h = probe.naturalHeight; }
            phReady();
        };
        probe.onerror = phReady;
        probe.src = FOTO_URL + '?id=0&w=400';
    }
    function phIsPlaceholder(img) {
        if (!img || !img.naturalHeight) return false;
        if (PH.w && PH.h) return img.naturalWidth === PH.w && img.naturalHeight === PH.h;
        return img.naturalHeight < 200 || img.naturalWidth === 302;
    }
    function phCheck(img) {
        if (!img || img.dataset.fbk) return;
        if (phIsPlaceholder(img)) tdbCardImgErr(img);
    }
    phInit();
    window.tdbCardImgLoad = function (img) {
        try {
            if (img.dataset.fbk) return;
            if (!PH.ready) { PH.queue.push(img); return; }   // sonda non ancora pronta -> rivaluto dopo
            phCheck(img);
        } catch (e) {}
    };
    function cardHtml(t, idx) {
        var id = parseInt(t.id, 10) || 0;
        var sel = TD.selectedIds.has(id) ? ' selected' : '';
        // 2026-08-11 marco — v76.2 COVER da album eventi (contratto CRM): URL già pronto con w=400, si usa
        // così com'è, senza aggiungere parametri; null/assente → foto profilo come prima (fallback pulito).
        var fotoSrc = t.cover || (FOTO_URL + '?id=' + encodeURIComponent(id) + '&w=400'); // FIX 2026-06-25 marco — miniatura card (~80% peso in meno); modal/galleria restano full
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
        if (tid)       line.push('<span class="toa-tdb-card-code">' + tdCodeDisplay(tid) + '</span>'); // FIX 2026-07-03 marco — display A___ (regola codice corto)
        var loc = pairStr(t.provincia, t.provincia_dom).toUpperCase();   // FIX 2026-06-20 marco — residenza / domicilio
        if (t.eta)     line.push(escapeHtml(t.eta + ' ' + i18n('years')));
        if (t.altezza) line.push(escapeHtml(t.altezza + ' cm'));
        if (loc) line.push(escapeHtml(loc));
        var lineHtml = line.join(' · ');
        var tdbNameRow = '<strong>' + escapeHtml(t.nome || '—') + '</strong>' + (tid ? '<span class="toa-tdb-card-code">' + tdCodeDisplay(tid) + '</span>' : ''); // FIX 2026-07-03 marco — display A___
        var tdbInfo = [];
        if (t.eta)     tdbInfo.push(escapeHtml(t.eta + ' ' + i18n('years')));
        if (t.altezza) tdbInfo.push(escapeHtml(t.altezza + ' cm'));
        if (loc) tdbInfo.push(escapeHtml(loc));
        var tdbInfoHtml = tdbInfo.join(' · ');

        // 2026-08-11 marco — HOSTESS EVENTI: chip lingue [IT][EN]… + badge 🚗 (solo se il dato arriva dall'API, mai se null)
        var tdbLangs = lingueInfo(t);   // 2026-08-11 marco — v76.2: livello nel tooltip (es. "Inglese · fluente")
        var tdbTags = tdbLangs.map(function (L) {
            return '<span class="toa-tdb-card-lang" title="' + escapeHtml(L.name + (L.liv ? ' · ' + L.liv : '')) + '">' + escapeHtml(L.code) + '</span>';
        }).join('');
        if (t.automunito == 1) tdbTags += '<span class="toa-tdb-card-car" title="' + escapeHtml(i18n('modal_car')) + '">🚗</span>';
        var tdbTagsHtml = tdbTags ? '<div class="toa-tdb-card-tags">' + tdbTags + '</div>' : '';

        // FIX 2026-06-26 marco — tutela minori: niente foto (nessuna richiesta al proxy), pannello protetto + box hover + CTA
        var isMinor = !!t.is_minor;
        var minorIcon = '<svg viewBox="0 0 24 24" width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"></rect><path d="M8 11V8a4 4 0 0 1 8 0v3"></path></svg>';
        var photoBlock = isMinor
            ? '<div class="toa-tdb-card-minor-base">' + minorIcon + '<span class="toa-tdb-card-minor-lbl">' + escapeHtml(i18n('minor_label')) + '</span></div>' +
              '<div class="toa-tdb-card-minor-explain"><p>' + escapeHtml(i18n('minor_explain')) + '</p></div>'
            : (eager
                ? '<img class="toa-tdb-card-img"' + rotStyle + ' src="' + escapeHtml(fotoSrc) + '" alt="' + escapeHtml(t.nome || '') + '" onerror="tdbCardImgErr(this)" onload="tdbCardImgLoad(this)">'
                : '<img class="toa-tdb-card-img lazyload"' + rotStyle + ' data-src="' + escapeHtml(fotoSrc) + '" alt="' + escapeHtml(t.nome || '') + '" onerror="tdbCardImgErr(this)" onload="tdbCardImgLoad(this)">');
        return '<article class="toa-tdb-card' + sel + (isMinor ? ' toa-tdb-card--minor' : '') + '" data-id="' + id + '">' +
                 '<a class="toa-tdb-card-link" href="?tid=' + id + '" aria-label="' + escapeHtml(t.nome || '') + '" tabindex="-1"></a>' +
                 '<button type="button" class="toa-tdb-card-add" data-add="1" aria-label="' + escapeHtml(i18n('btn_add')) + '">' +
                   (sel ? '✓' : '+') +
                 '</button>' +
                 photoBlock +
                 '<div class="toa-tdb-card-meta">' +
                   '<div class="toa-tdb-card-name-row">' + tdbNameRow + '</div>' +
                   (tdbInfoHtml ? '<div class="toa-tdb-card-info-row">' + tdbInfoHtml + '</div>' : '') +
                   tdbTagsHtml +
                   (isMinor ? '<button type="button" class="toa-tdb-card-minreq" data-minreq="1">' + escapeHtml(i18n('minor_cta')) + '</button>' : '') +
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
                renderDetailModal(res.talent, media, res.creator, res.video);
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
        // 2026-07-08 marco — rimuovi le sezioni Creator/Video (ferma anche i <video> in play)
        var extra = document.getElementById('tdbExtraSections'); if (extra) extra.remove();
        $('#tdbTalentModal').hidden = true;
        if (!anyOtherModalOpen()) document.body.style.overflow = '';
        TD.modalTalent = null;
        TD.galleryMedia = [];
        TD.galleryIdx = 0;
        clearUrlTalent();
    }

    // Popola la scheda dettaglio del modal (galleria + righe monospace key/value).
    // 2026-07-08 marco — +creator{} e video[] (sezioni Creator/Video, anti-bypass, dati masticati dall'endpoint)
    function renderDetailModal(t, media, creator, videoList) {
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
        addRow(i18n('modal_code'),  t.talent_id ? tdCodeDisplay(t.talent_id) : null);   // FIX 2026-07-03 marco — display A___ (regola codice corto)
        addRow(i18n('modal_gender'),  t.sesso ? cap(t.sesso) : null);
        addRow(i18n('modal_country'), t.paese_label || t.paese);
        addRow(i18n('modal_city'),    pairStr(t.citta, t.citta_dom));            // FIX 2026-06-20 marco — comune res / dom
        addRow(i18n('modal_province'), pairStr(t.provincia, t.provincia_dom));   // FIX 2026-06-20 marco — provincia res / dom
        addRow(i18n('modal_age'), t.eta != null && t.eta !== '' ? t.eta + ' ' + i18n('years') : null);
        addRow(i18n('modal_height'),  t.altezza ? t.altezza + ' cm' : null);
        addRow(i18n('modal_size'),    t.taglia);
        addRow(i18n('modal_hair'),    t.capelli ? cap(t.capelli) : null);
        addRow(i18n('modal_eyes'),    t.occhi ? cap(t.occhi) : null);
        addRow(i18n('modal_shoes'),   t.scarpe);
        // 2026-08-11 marco — HOSTESS EVENTI: lingue + automunito in scheda (solo se presenti, mai se null)
        // 2026-08-11 marco — v76.2.1: lingue schematiche, una per riga (nome + livello in grigio) — feedback Marco
        var dLangs = lingueInfo(t);
        if (dLangs.length) {
            var dlHtml = dLangs.map(function (L) {
                return '<span class="toa-tdb-detail-lang">' + escapeHtml(L.name) + (L.liv ? '<em>' + escapeHtml(L.liv) + '</em>' : '') + '</span>';
            }).join('');
            rows.push('<div class="toa-tdb-detail-row"><span class="toa-tdb-detail-key">' + escapeHtml(i18n('modal_langs')) + '</span><span class="toa-tdb-detail-val toa-tdb-detail-langs">' + dlHtml + '</span></div>');
        }
        if (t.automunito == 1) addRow(i18n('modal_car'), i18n('yes_label'));
        // FIX 2026-06-24 marco — il DB salva il genere come parola ("Femmina"), non "F": prima le misure non comparivano MAI.
        if (t.sesso === 'Femmina' || t.sesso === 'F') {
            addRow(i18n('modal_chest'), t.misura_petto   ? t.misura_petto   + ' cm' : null);
            addRow(i18n('modal_waist'), t.misura_vita    ? t.misura_vita    + ' cm' : null);
            addRow(i18n('modal_hips'),  t.misura_fianchi ? t.misura_fianchi + ' cm' : null);
        }
        // FIX 2026-06-26 marco — minore: avviso tutela + CTA in cima alla scheda dettaglio
        if (t.is_minor) rows.unshift('<div class="toa-tdb-detail-minor"><p>' + escapeHtml(i18n('minor_explain')) + '</p><button type="button" class="toa-tdb-card-minreq" data-modal-minreq="1">' + escapeHtml(i18n('minor_cta')) + '</button></div>');
        var fieldsEl2 = $('#tdbModalFields');
        if (fieldsEl2) fieldsEl2.innerHTML = rows.join('');

        // Galleria: usa media[] dall'API (res.media), fallback foto profilo
        var galleryMedia = (media && media.length > 0)
            ? media
            : [{ tipo: 'foto', url: FOTO_URL + '?id=' + encodeURIComponent(t.id) }];
        renderGallery(galleryMedia);
        // FIX 2026-06-26 marco — minore: bottone "Richiedi info" anche sotto il lucchetto (colonna foto del modal)
        var galMain = document.querySelector('#tdbTalentModal .toa-tdb-gallery-main');
        if (galMain) {
            var exMr = galMain.querySelector('.toa-tdb-gallery-minreq'); if (exMr) exMr.remove();
            if (t.is_minor) {
                var mrBtn = document.createElement('button');
                mrBtn.type = 'button';
                mrBtn.className = 'toa-tdb-card-minreq toa-tdb-gallery-minreq';
                mrBtn.setAttribute('data-modal-minreq', '1');
                mrBtn.textContent = i18n('minor_cta');
                galMain.appendChild(mrBtn);
            }
        }

        // 2026-07-08 marco — sezioni Creator + Video (iniettate nella colonna dettaglio, idempotente)
        renderCreatorVideoSections(t, creator, videoList);
    }

    // 2026-07-08 marco — inietta le sezioni "Creator" e "Video di presentazione" nel modal.
    // Colori theme-agnostic (color:inherit + grigi trasparenti) → funziona su modal chiaro o scuro.
    // Anti-bypass: mostra SOLO ciò che l'endpoint ha già masticato (fascia, ER a intervallo,
    // nomi piattaforme, nicchie). I video puntano al proxy pubblico (gate + tutela minori server-side).
    function renderCreatorVideoSections(t, creator, videoList) {
        var fields = document.getElementById('tdbModalFields');
        if (!fields || !fields.parentNode) return;
        var old = document.getElementById('tdbExtraSections');
        if (old) old.remove();

        function chip(txt, strong) {
            return '<span style="display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;' +
                   'border:1px solid rgba(128,128,128,.35);background:rgba(128,128,128,.12);color:inherit;' +
                   (strong ? 'font-weight:700;' : '') + '">' + escapeHtml(String(txt)) + '</span>';
        }
        var secStyle   = 'margin-top:16px;padding-top:14px;border-top:1px solid rgba(128,128,128,.25)';
        var titleStyle = 'font-weight:700;font-size:13px;letter-spacing:.04em;text-transform:uppercase;opacity:.7;margin-bottom:8px';
        var subStyle   = 'margin-top:8px;font-size:12px;opacity:.6';
        var rowStyle   = 'display:flex;flex-wrap:wrap;gap:6px;margin-top:4px';
        var html = '';

        // ── CREATOR ──
        var hasCreator = creator && (creator.fascia || creator.er ||
            (creator.piattaforme && creator.piattaforme.length) ||
            (creator.nicchie && creator.nicchie.length));
        if (hasCreator) {
            var LC = { it:['Creator','Fascia','Engagement','Piattaforme','Nicchie'],
                       en:['Creator','Tier','Engagement','Platforms','Niches'],
                       fr:['Créateur','Palier','Engagement','Plateformes','Niches'],
                       es:['Creador','Nivel','Engagement','Plataformas','Nichos'] };
            var lc = LC[LANG] || LC.it;
            html += '<div class="toa-tdb-creator" style="' + secStyle + '">';
            html += '<div style="' + titleStyle + '">🎯 ' + escapeHtml(lc[0]) + '</div>';
            html += '<div style="' + rowStyle + '">';
            if (creator.fascia) html += chip(lc[1] + ': ' + creator.fascia, true);
            if (creator.er)     html += chip(lc[2] + ' ' + creator.er, false);
            html += '</div>';
            if (creator.piattaforme && creator.piattaforme.length) {
                html += '<div style="' + subStyle + '">' + escapeHtml(lc[3]) + '</div><div style="' + rowStyle + '">';
                creator.piattaforme.forEach(function (p) { html += chip(p, false); });
                html += '</div>';
            }
            if (creator.nicchie && creator.nicchie.length) {
                html += '<div style="' + subStyle + '">' + escapeHtml(lc[4]) + '</div><div style="' + rowStyle + '">';
                creator.nicchie.forEach(function (n) { html += chip(n, false); });
                html += '</div>';
            }
            html += '</div>';
        }

        // ── VIDEO ──
        if (videoList && videoList.length) {
            var LV = { it:'Video di presentazione', en:'Introduction videos',
                       fr:'Vidéos de présentation', es:'Vídeos de presentación' };
            html += '<div class="toa-tdb-videos" style="' + secStyle + '">';
            html += '<div style="' + titleStyle + '">🎥 ' + escapeHtml(LV[LANG] || LV.it) + '</div>';
            html += '<div style="display:flex;flex-wrap:wrap;gap:10px">';
            videoList.forEach(function (v) {
                if (!v || !v.url) return;
                html += '<video controls preload="metadata" playsinline ' +
                        'controlslist="nodownload noremoteplayback" disablepictureinpicture ' +
                        'oncontextmenu="return false;" ' +
                        'style="width:100%;max-width:240px;border-radius:8px;background:#000" ' +
                        'src="' + escapeHtml(v.url) + '"></video>';
            });
            html += '</div></div>';
        }

        if (html === '') return;
        var wrap = document.createElement('div');
        wrap.id = 'tdbExtraSections';
        wrap.innerHTML = html;
        fields.parentNode.insertBefore(wrap, fields.nextSibling);
    }

    // Aggiorna il testo del pulsante "+ Aggiungi / ✓ Selezionato" del modal.
    function updateModalAddBtn() {
        if (!TD.modalTalent) return;
        var btn = $('#tdbModalAdd');
        if (!btn) return;
        var sel = TD.selectedIds.has(TD.modalTalent.id);
        // FIX 2026-06-24 marco — simbolo (+/✓) dentro cerchietto nero: richiama il bottone tondo sulle card
        var raw = sel ? i18n('btn_remove') : i18n('btn_add');
        var sym = raw.charAt(0);
        var label = raw.slice(1).trim();
        btn.innerHTML = '<span class="toa-tdb-modal-add-ico" aria-hidden="true">' + escapeHtml(sym) + '</span>' + escapeHtml(label);
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
                if (phIsPlaceholder(imgEl) && TD.galleryMedia && TD.galleryMedia.length > 1) { // FIX 2026-08-11 marco: riconoscimento auto-calibrante (era 302 scritto a mano)
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
            prevBtn.classList.remove('is-disabled'); // FIX 2026-07-03 marco - loop, mai disabilitata
        }
        if (nextBtn) {
            nextBtn.style.display = total <= 1 ? 'none' : '';
            nextBtn.classList.remove('is-disabled'); // FIX 2026-07-03 marco - loop, mai disabilitata
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

    // FIX 2026-07-03 marco - loop infinito: dall ultima foto si riparte dalla prima
    function tdGalleryNext() {
        if (TD.galleryMedia.length > 1) {
            showMedia((TD.galleryIdx + 1) % TD.galleryMedia.length);
        }
    }

    // FIX 2026-07-03 marco - loop infinito anche indietro
    function tdGalleryPrev() {
        if (TD.galleryMedia.length > 1) {
            showMedia((TD.galleryIdx - 1 + TD.galleryMedia.length) % TD.galleryMedia.length);
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
            // 2026-08-11 marco — MINI-PANNELLO: salvo anche talent_id (codice) e is_minor (privacy foto)
            TD.selectedTalents.set(id, { id: id, nome: t ? (t.nome || '') : '', talent_id: t ? (parseInt(t.talent_id, 10) || 0) : 0, is_minor: t ? (t.is_minor ? 1 : 0) : null });
        }
        saveSelectedToStorage();
        updateCardSelectedState(id);
        updateCart();
        if (TD.modalTalent && TD.modalTalent.id === id) updateModalAddBtn();
    }

    // ── 2026-08-11 marco — MINI-PANNELLO SELEZIONE: elenco foto+nome dei selezionati, apribile dal contatore ──
    function renderCartPanel() {
        var list = $('#tdbCartPanelList');
        if (!list) return;
        list.innerHTML = Array.from(TD.selectedIds).map(function (id) {
            var t = TD.selectedTalents.get(id) || TD.results.find(function (r) { return r.id === id; }) || { id: id };
            var name = t.nome || ('#' + id);
            var code = t.talent_id ? tdCodeDisplay(t.talent_id) : '';
            var initial = escapeHtml((String(name).charAt(0) || '?').toUpperCase());
            var thumb;
            if (t.is_minor) {
                thumb = '<span class="toa-tdb-cartp-thumb toa-tdb-cartp-thumb--txt" title="' + escapeHtml(i18n('minor_label')) + '">🔒</span>';
            } else if (t.is_minor === 0) {
                thumb = '<img class="toa-tdb-cartp-thumb" src="' + escapeHtml(FOTO_URL + '?id=' + encodeURIComponent(id) + '&w=400') + '" alt="" loading="lazy" onerror="this.style.display=\'none\'">';
            } else {
                // is_minor null = selezione salvata prima del pannello: per prudenza niente foto, solo iniziale
                thumb = '<span class="toa-tdb-cartp-thumb toa-tdb-cartp-thumb--txt">' + initial + '</span>';
            }
            return '<div class="toa-tdb-cartp-row" data-id="' + id + '">' +
                     '<a class="toa-tdb-cartp-open" href="?tid=' + id + '">' + thumb +
                       '<span class="toa-tdb-cartp-name">' + escapeHtml(name) + (code ? ' <small>' + code + '</small>' : '') + '</span></a>' +
                     '<button type="button" class="toa-tdb-cartp-rm" data-rm="' + id + '" aria-label="✕">✕</button>' +
                   '</div>';
        }).join('');
    }

    function toggleCartPanel(open) {
        var p = $('#tdbCartPanel');
        if (!p) return;
        var willOpen = (open !== undefined) ? open : p.hidden;
        if (willOpen && TD.selectedIds.size === 0) willOpen = false;
        p.hidden = !willOpen;
        var tg = $('#tdbCartToggle');
        if (tg) tg.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
        if (willOpen) renderCartPanel();
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
        // FIX 2026-06-23 marco — stato invito (vuoto) sempre visibile su DESKTOP; mobile resta nascosto a 0
        cart.classList.toggle('has-selection', count > 0);
        // 2026-08-11 marco — MINI-PANNELLO: chiudi a selezione vuota, altrimenti tieni la lista allineata
        if (count === 0) toggleCartPanel(false);
        else { var pp = $('#tdbCartPanel'); if (pp && !pp.hidden) renderCartPanel(); }
        var isMobile = window.matchMedia('(max-width: 580px)').matches;
        if (count > 0 || !isMobile) {
            cart.hidden = false;
            // Force reflow così la transition parte dal valore "off-screen".
            void cart.offsetWidth;
            cart.classList.add('show');
        } else {
            cart.classList.remove('show');
            setTimeout(function () {
                if (TD.selectedIds.size === 0 && window.matchMedia('(max-width: 580px)').matches) cart.hidden = true;
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
                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        event: 'talent_db_request_success',
                        talent_db_email: data.email,
                        talent_db_phone: data.telefono,
                        job_id: (res && (res.codice || res.job_id || res.id)) || undefined
                    });
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
        // 2026-08-11 marco — HOSTESS EVENTI deep-link: il ruolo vive nel select (iniettato dal fetch
        // interceptor PHP), non in TD.filters → prima si PERDEVA dall'URL al primo replaceState.
        var rs = $('#tdbFilterRuolo');
        if (rs && rs.value) params.set('ruolo', rs.value);
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
        // 2026-08-11 marco — HOSTESS EVENTI deep-link: lingua/automunito da URL (?lingua=inglese&automunito=1)
        msSet('lingua', params.getAll('lingua'));
        if (params.get('automunito') === '1' && f.automunito) f.automunito.checked = true;
        // 2026-08-11 marco — deep-link province: venivano SCRITTE in URL (?province=BO) ma mai rilette al load
        var provs = params.getAll('province');
        if (provs.length) { TD.selectedProvinces = provs.slice(); TD.geoExpanded = false; }
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
            toggleHostessFilters();   // 2026-08-11 marco — HOSTESS EVENTI: reset porta ruolo a "" → nascondi+azzera
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

        // 2026-08-11 marco — HOSTESS EVENTI: visibilità blocco filtri hostess al cambio categoria
        // (il change di #tdbFilterRuolo scatena già la ricerca altrove; qui solo la visibilità)
        var ruoloSel = $('#tdbFilterRuolo');
        if (ruoloSel) ruoloSel.addEventListener('change', toggleHostessFilters);
        var autoChk = $('#tdbFilters').automunito;
        if (autoChk) autoChk.addEventListener('change', function () { tdSearch(false); });

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
            // FIX 2026-06-26 marco — CTA minore: seleziona (idempotente) + apri modal richiesta
            if (e.target.closest('[data-minreq]')) {
                e.stopPropagation(); e.preventDefault();
                if (!TD.selectedIds.has(id)) tdToggleSelected(id);
                tdOpenRequestForm();
                return;
            }
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
        // 2026-08-11 marco — MINI-PANNELLO: toggle dal contatore + azioni righe (apri scheda / rimuovi)
        var ct = $('#tdbCartToggle');
        if (ct) ct.addEventListener('click', function () { toggleCartPanel(); });
        var cp = $('#tdbCartPanel');
        if (cp) cp.addEventListener('click', function (e) {
            var rm = e.target.closest('[data-rm]');
            if (rm) { tdToggleSelected(parseInt(rm.getAttribute('data-rm'), 10)); return; }
            var op = e.target.closest('.toa-tdb-cartp-open');
            if (op) {
                e.preventDefault();
                var row = op.closest('.toa-tdb-cartp-row');
                if (row) tdOpenTalentModal(parseInt(row.getAttribute('data-id'), 10));
            }
        });
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
        // FIX 2026-06-26 marco — CTA minore nel modal: seleziona (idempotente) + chiudi scheda + apri richiesta
        document.body.addEventListener('click', function (e) {
            if (!e.target.closest('[data-modal-minreq]')) return;
            e.preventDefault();
            if (TD.modalTalent) {
                var mid = TD.modalTalent.id;
                if (!TD.selectedIds.has(mid)) tdToggleSelected(mid);
                tdCloseTalentModal();
                tdOpenRequestForm();
            }
        });
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
    // 2026-08-13 RICERCA IN LINGUAGGIO LIBERO — fetta 1: categoria + luogo + nome
    // (gemella di quella della Creative Network, ma qui i talent sono 10.401 e arrivano
    //  paginati dal server: non si filtra nel browser, si TRADUCE la frase in filtri veri
    //  e si lascia cercare l'API. Scrive nei campi del pannello filtri già esistenti,
    //  quindi non tocca il contratto API né readFilters().)
    // ═════════════════════════════════════════════════════════════════
    var SS_ROLE_KW = {
        actor:   'attore attrice attori attrici comparsa comparse figurazione figurante figuranti cinema film spot fiction serie actor actress actors extra extras acteur acteurs actrice figurant actriz actores',
        model:   'modella modello modelli modelle indossatrice indossatore fotomodella fotomodello sfilata sfilate passerella catalogo cataloghi shooting model models mannequin mannequins modelo modelos maniqui',
        hostess: 'hostess steward stewards promoter promoters promotrice accoglienza fiera fiere stand congressuale congressuali congresso congressi hotesse hotesses azafata azafatas',
        kids:    'bambino bambina bambini bambine ragazzo ragazza ragazzi ragazze kid kids child children enfant enfants nino nina ninos baby teen teenager minorenne minorenni',
        creator: 'influencer influencers creator creators creatrice ugc tiktoker tiktokers instagrammer youtuber blogger createur createurs creador creadora'
    };
    var SS_ROLE_SET = {};
    Object.keys(SS_ROLE_KW).forEach(function (r) {
        SS_ROLE_KW[r].split(' ').forEach(function (w) { if (w) SS_ROLE_SET[w] = r; });
    });
    // 2026-08-13 RICERCA IN LINGUAGGIO LIBERO — fetta 2: sesso + età + altezza
    var SS_GENDER_KW = {
        M: 'uomo uomini maschio maschi maschile man men male males homme hommes masculin hombre hombres masculino',
        F: 'donna donne femmina femmine femminile woman women female females femme femmes feminin mujer mujeres femenino'
    };
    var SS_GENDER_SET = {};
    Object.keys(SS_GENDER_KW).forEach(function (g) {
        SS_GENDER_KW[g].split(' ').forEach(function (w) { if (w) SS_GENDER_SET[w] = g; });
    });
    // 2026-08-13 marco — in IT/FR/ES certe parole di RUOLO sono già di genere (modello/modella,
    // attore/attrice, steward/hostess...), quindi implicano anche il sesso. Solo le parole
    // davvero di genere: "hostess"/"model"/"creator" restano neutre (categoria generica).
    var SS_ROLE_GENDER = {
        modello: 'M', modelli: 'M', modella: 'F', modelle: 'F',
        indossatore: 'M', indossatrice: 'F', fotomodello: 'M', fotomodella: 'F',
        attore: 'M', attori: 'M', attrice: 'F', attrici: 'F',
        acteur: 'M', acteurs: 'M', actrice: 'F', actriz: 'F', actress: 'F',
        steward: 'M', stewards: 'M', promotrice: 'F', hotesse: 'F', hotesses: 'F', azafata: 'F', azafatas: 'F',
        bambino: 'M', bambini: 'M', bambina: 'F', bambine: 'F',
        ragazzo: 'M', ragazzi: 'M', ragazza: 'F', ragazze: 'F',
        nino: 'M', ninos: 'M', nina: 'F',
        creatrice: 'F', creador: 'M', creadora: 'F'
    };
    // età/altezza: pattern multi-parola ("tra 20 e 30") che il loop a token singolo non gestisce,
    // quindi si estraggono con regex sulla frase intera PRIMA della tokenizzazione.
    var SS_NUM_HEIGHT_HINT = '(?:alta|alto|altezza|tall|height|taille|grande?|altura)';
    var SS_NUM_AGE_HINT    = '(?:anni|anno|years?|yo|ans?|anos?)';
    var SS_NUM_OVER  = '(?:over|almeno|minimo|piu di|più di|plus de|mas de|más de)';
    var SS_NUM_UNDER = '(?:under|massimo|entro|meno di|moins de|menos de)';
    // numeri "nudi" (senza indizio): un range tipo "tra 20 e 30" è età di default; l'altezza
    // richiede sempre un indizio (parola o "cm") per non essere confusa con l'età.
    function ssExtractNumeric(s) {
        var out = {};
        var m = s.match(new RegExp('\\b(' + SS_NUM_HEIGHT_HINT + '\\s+)?(?:tra|between|entre)\\s+(\\d{1,3})\\s+(?:e|and|et|y)\\s+(\\d{1,3})(\\s*cm)?\\b'));
        if (m) {
            var isH = !!(m[1] || m[4]);
            var a = parseInt(m[2], 10), b = parseInt(m[3], 10);
            var lo = Math.min(a, b), hi = Math.max(a, b);
            if (isH) { out.altezza_min = lo; out.altezza_max = hi; } else { out.eta_min = lo; out.eta_max = hi; }
            s = s.replace(m[0], ' ');
        }
        if (out.altezza_min === undefined) {
            m = s.match(new RegExp('\\b' + SS_NUM_HEIGHT_HINT + '\\s+(\\d{2,3})(\\s*cm)?\\b'));
            if (m) { out.altezza_min = parseInt(m[1], 10); s = s.replace(m[0], ' '); }
        }
        if (out.altezza_min === undefined && out.altezza_max === undefined) {
            m = s.match(/\b(\d{2,3})\s*cm\b/);
            if (m) { out.altezza_min = out.altezza_max = parseInt(m[1], 10); s = s.replace(m[0], ' '); }
        }
        if (out.eta_min === undefined && out.eta_max === undefined) {
            m = s.match(new RegExp('\\b(\\d{1,2})\\s*' + SS_NUM_AGE_HINT + '\\b'));
            if (m) { out.eta_min = out.eta_max = parseInt(m[1], 10); s = s.replace(m[0], ' '); }
        }
        if (out.eta_min === undefined && out.eta_max === undefined) {
            m = s.match(new RegExp('\\b' + SS_NUM_OVER + '\\s+(\\d{1,2})\\b'));
            if (m) { out.eta_min = parseInt(m[1], 10); s = s.replace(m[0], ' '); }
            else {
                m = s.match(new RegExp('\\b' + SS_NUM_UNDER + '\\s+(\\d{1,2})\\b'));
                if (m) { out.eta_max = parseInt(m[1], 10); s = s.replace(m[0], ' '); }
            }
        }
        return { out: out, text: s };
    }
    // 2026-08-13 RICERCA IN LINGUAGGIO LIBERO — fetta 3: capelli/occhi/etnia/taglia + lingua/automunito.
    // Valori canonici = quelli dell'API (fo.capelli/fo.occhi/fo.etnia). Colori come "nero"/"grigio"
    // sono ambigui (colore vs altro): si riconoscono SOLO se preceduti dalla parola guida
    // ("capelli"/"occhi" e traduzioni), come da regola progetto sulle ambiguità.
    var SS_HAIR_GUIDE = '(?:capelli|hair|cheveux|cabello|pelo)';
    var SS_EYES_GUIDE = '(?:occhi|eyes|yeux|ojos)';
    var SS_SIZE_GUIDE = '(?:taglia|size|taille|talla)';
    var SS_HAIR_VAL = [
        ['Biondo Chiaro',  'biondo chiaro|bionda chiara|biondi chiari|bionde chiare|light blonde|blond clair|rubio claro'],
        ['Biondo Scuro',   'biondo scuro|bionda scura|biondi scuri|bionde scure|dark blonde|blond fonce|rubio oscuro'],
        ['Castano Chiaro', 'castano chiaro|castana chiara|castani chiari|castane chiare|light brown|chatain clair|castano claro'],
        ['Castano Scuro',  'castano scuro|castana scura|castani scuri|castane scure|dark brown|chatain fonce|castano oscuro'],
        ['Biondo',  'biondo|bionda|biondi|bionde|blonde?s?|rubio|rubia|rubios|rubias'],
        ['Castano', 'castano|castana|castani|castane|chestnut|browns?|chatains?|marrons?'],
        ['Calvo',   'calvo|calva|calvi|calve|pelato|pelata|pelati|pelate|balds?|chauves?'],
        ['Bianco',  'bianco|bianca|bianchi|bianche|whites?|blanc|blanche|blancs?|blancos?'],
        ['Grigio',  'grigio|grigia|grigi|grigie|greys?|grays?|gris'],
        ['Nero',    'nero|nera|neri|nere|blacks?|noir|noire|noirs?|negros?'],
        ['Rosso',   'rosso|rossa|rossi|rosse|reds?|ginger|roux|rousse|pelirrojo|pelirroja']
    ];
    var SS_EYES_VAL = [
        ['Azzurri', 'azzurro|azzurra|azzurri|azzurre|blues?|bleu|bleue|bleus|bleues|azules?'],
        ['Grigi',   'grigio|grigia|grigi|grigie|greys?|grays?|gris'],
        ['Marroni', 'marrone|marroni|browns?|noisettes?|marrons?|castanos?'],
        ['Neri',    'nero|nera|neri|nere|blacks?|noir|noire|noirs?|negros?'],
        ['Verdi',   'verde|verdi|greens?|vert|verte|verts|vertes|verdes?']
    ];
    var SS_ETNIA_VAL = [
        // 2026-08-13 marco — aggiunto "di colore": espressione colloquiale italiana, qui indica
        // specificamente carnagione nera/afro (non l'inglese "of color", che in inglese è più
        // ampio e includerebbe anche asiatico/ispanico — per questo NON l'ho aggiunto alle
        // versioni EN/FR/ES, solo a quella italiana dove il significato è più circoscritto).
        ['Nero Africano',    'nero africano|nera africana|black african|noir africain|negro africano|negra africana|di colore'],
        // 2026-08-13 marco — FIX: mancava "caucasica" (femminile) accanto a "caucasico"; aggiunto
        // anche "mediterraneo/a" come sinonimo su richiesta — nella tassonomia di questo settore
        // (sud Europa/pelle olivastra) si accosta di solito a Caucasico, non a Mediorientale.
        // Se non è la lettura giusta per TOAgency, si sposta con una riga sola.
        ['Bianco Caucasico', 'bianco caucasico|bianca caucasica|caucasian|caucasien(?:ne)?|caucasic[oa]|mediterrane[oa]'],
        ['Mediorientale',    'mediorientale|medio orientale|middle eastern|moyen orient(?:al)?|medio oriental'],
        // FIX: mancava "sudasiatica" (femminile)
        ['Sudasiatico',      'sudasiatic[oa]|sud asiatic[oa]|south asian|sud asiatique'],
        ['Asiatico',         'asiatic[oa]|asian|asiatique'],
        ['Ispanico',         'ispanic[oa]|hispanic|hispanique'],
        ['Magrebino',        'magrebin[oa]|maghrebin?'],
        ['Mixed',            'mixed|mist[oa]|meticci[oa]|metisse|mestiz[oa]']
    ];
    var SS_CAR_RE = /\b(?:automunit[oa]|con\s+auto|con\s+macchina|ha\s+la\s+macchina|has\s+a\s+car|own\s+car|avec\s+voiture|con\s+coche)\b/;
    var SS_LANG_KW = {
        italiano: 'italiano italian italien', inglese: 'inglese english anglais ingles',
        francese: 'francese french francais frances', spagnolo: 'spagnolo spanish espagnol espanol',
        tedesco: 'tedesco german allemand aleman', portoghese: 'portoghese portuguese portugais portugues',
        russo: 'russo russian russe ruso', arabo: 'arabo arabic arabe',
        cinese: 'cinese chinese chinois chino', giapponese: 'giapponese japanese japonais japones'
    };
    var SS_LANG_SET = {};
    Object.keys(SS_LANG_KW).forEach(function (l) {
        SS_LANG_KW[l].split(' ').forEach(function (w) { if (w) SS_LANG_SET[w] = l; });
    });
    // Estrae dalla frase (già ripulita da numeri/età/altezza) capelli/occhi/etnia/taglia/automunito.
    // NOTA: se nella stessa frase si citano 2 colori capelli con una sola parola guida
    // ("capelli neri o castani") viene riconosciuto solo il primo — caso raro, non gestito.
    function ssExtractAttrs(s) {
        var out = { capelli: [], occhi: [], etnia: [], taglia: [], automunito: false };
        function pass(guideRe, pairs, bucket) {
            pairs.forEach(function (p) {
                var re = new RegExp('\\b' + guideRe + '\\s+(?:colore\\s+|color\\s+|couleur\\s+)?(?:' + p[1] + ')\\b');
                var m = s.match(re);
                if (m) { out[bucket].push(p[0]); s = s.replace(m[0], ' '); }
            });
        }
        pass(SS_HAIR_GUIDE, SS_HAIR_VAL, 'capelli');
        pass(SS_EYES_GUIDE, SS_EYES_VAL, 'occhi');
        SS_ETNIA_VAL.forEach(function (p) {
            var m = s.match(new RegExp('\\b(?:' + p[1] + ')\\b'));
            if (m) { out.etnia.push(p[0]); s = s.replace(m[0], ' '); }
        });
        var m = s.match(new RegExp('\\b' + SS_SIZE_GUIDE + '\\s+(xxl|xl|xs|s|m|l)\\b'));
        if (m) { out.taglia.push(m[1].toUpperCase()); s = s.replace(m[0], ' '); }
        m = s.match(SS_CAR_RE);
        if (m) { out.automunito = true; s = s.replace(m[0], ' '); }
        return { out: out, text: s };
    }
    // province vicine (capoluoghi entro ~130km) — stessa tabella della Creative Network,
    // generata da _scripts/gen_prov_near.py. Serve solo al fallback "zero risultati".
    var SS_PROV_NEAR = {"AG":["CL","EN","PA","RG","TP"],"AL":["AT","VC","PV","NO","GE"],"AN":["MC","FM","PU","AP","RN"],"AO":["BI","TO","VB","VC","NO"],"AP":["TE","FM","MC","AQ","PE"],"AQ":["TE","RI","AP","CH","TR"],"AR":["SI","PG","FI","PO","FC"],"AT":["AL","TO","VC","SV","NO"],"AV":["BN","SA","CE","NA","CB"],"BA":["BT","MT","TA","BR","PZ"],"BG":["LC","MB","LO","BS","MI"],"BI":["VC","NO","VB","AO","TO"],"BL":["PN","TV","BZ","VE","UD"],"BN":["AV","CE","CB","SA","NA"],"BO":["MO","FE","RE","FC","RA"],"BR":["LE","TA","BA","MT"],"BS":["BG","CR","LO","VR","MN"],"BT":["BA","FG","MT","PZ","TA"],"BZ":["TN","BL","VI","TV","PN"],"CA":["SU","OR","NU"],"CB":["IS","BN","CE","AV","FG"],"CE":["NA","BN","AV","SA","IS"],"CH":["PE","TE","AQ","AP","IS"],"CL":["EN","AG","RG","CT","PA"],"CN":["IM","SV","TO","AT","AL"],"CO":["VA","LC","MB","MI","VB"],"CR":["PC","PR","LO","BS","MN"],"CS":["CZ","VV","KR"],"CT":["SR","RG","EN","RC","ME"],"CZ":["VV","KR","CS","ME","RC"],"EN":["CL","AG","CT","RG","PA"],"FC":["RA","RN","BO","FE","PU"],"FE":["RO","BO","MO","RA","PD"],"FG":["BT","BN","CB","AV","PZ"],"FI":["PO","PT","SI","AR","LU"],"FM":["MC","AP","AN","TE","PE"],"FR":["LT","IS","RM","AQ","RI"],"GE":["SV","AL","SP","AT","PV"],"GO":["UD","TS","PN","BL","TV"],"GR":["SI","VT","AR","LI","PG"],"IM":["SV","CN","GE","AT","AL"],"IS":["CB","CE","BN","FR","NA"],"KR":["CZ","CS","VV"],"LC":["CO","BG","MB","VA","MI"],"LE":["BR","TA","MT"],"LI":["PI","LU","MS","PT","PO"],"LO":["MI","PV","PC","MB","BG"],"LT":["FR","RM","RI","AQ","IS"],"LU":["PI","PT","LI","MS","PO"],"MB":["MI","CO","LC","BG","LO"],"MC":["FM","AN","AP","TE","PU"],"ME":["RC","VV","CT","CZ","SR"],"MI":["MB","LO","PV","CO","NO"],"MN":["VR","RE","PR","MO","CR"],"MO":["RE","BO","PR","MN","FE"],"MS":["SP","LU","PI","LI","PT"],"MT":["BA","TA","BT","PZ","BR"],"NA":["CE","AV","SA","BN","IS"],"NO":["VC","VA","MI","BI","PV"],"NU":["OR","SS","CA"],"OR":["NU","SU","CA","SS"],"PA":["TP","AG","CL","EN"],"PC":["CR","LO","PV","PR","MI"],"PD":["VI","VE","RO","TV","FE"],"PE":["CH","TE","AP","AQ","FM"],"PG":["AR","TR","VT","RI","MC"],"PI":["LU","LI","MS","PT","PO"],"PN":["BL","TV","UD","VE","GO"],"PO":["PT","FI","LU","PI","SI"],"PR":["RE","CR","MO","MN","PC"],"PT":["PO","FI","LU","PI","MS"],"PU":["RN","AN","FC","RA","MC"],"PV":["LO","MI","MB","PC","NO"],"PZ":["MT","BT","SA","AV","FG"],"RA":["FC","RN","FE","BO","RO"],"RC":["ME","VV","CT","SR","CZ"],"RE":["MO","PR","MN","BO","CR"],"RG":["SR","CT","EN","CL","AG"],"RI":["TR","AQ","VT","RM","TE"],"RM":["LT","RI","VT","TR","FR"],"RN":["PU","FC","RA","AR","AN"],"RO":["FE","PD","VI","VE","BO"],"SA":["AV","NA","BN","CE","PZ"],"SI":["AR","FI","GR","PO","PT"],"SO":["LC","BG","CO","BS","MB"],"SP":["MS","LU","PI","LI","GE"],"SR":["CT","RG","EN","CL","RC"],"SS":["NU","OR","SU"],"SU":["CA","OR","NU"],"SV":["GE","IM","AL","AT","CN"],"TA":["MT","BR","BA","LE","BT"],"TE":["AP","AQ","PE","CH","FM"],"TN":["BZ","VI","VR","BL","BS"],"TO":["AT","BI","VC","AL","CN"],"TP":["PA","AG","CL"],"TR":["RI","VT","PG","AQ","RM"],"TS":["GO","UD","PN","BL","TV"],"TV":["VE","PD","PN","BL","VI"],"UD":["GO","PN","TS","BL","TV"],"VA":["CO","VB","MB","LC","NO"],"VB":["VA","CO","NO","BI","LC"],"VC":["NO","BI","AL","AT","PV"],"VE":["TV","PD","RO","VI","PN"],"VI":["PD","VR","TV","RO","VE"],"VR":["MN","VI","BS","PD","TN"],"VT":["TR","RI","RM","PG","GR"],"VV":["CZ","CS","ME","RC","KR"]};
    var SS_T = {
        dropped: {
            it: 'Non ho capito «%w»: ti mostro i risultati per il resto della ricerca.',
            en: 'I didn\'t understand «%w»: showing results for the rest of your search.',
            fr: 'Je n\'ai pas compris «%w» : voici les résultats pour le reste de la recherche.',
            es: 'No he entendido «%w»: te muestro los resultados del resto de la búsqueda.'
        },
        widen: {
            it: 'Pochi risultati esatti: abbiamo ampliato %s per mostrarti profili simili.',
            en: 'Few exact results: we widened %s to show similar profiles.',
            fr: 'Peu de résultats exacts : nous avons élargi %s pour vous montrer des profils similaires.',
            es: 'Pocos resultados exactos: ampliamos %s para mostrarte perfiles similares.'
        }
    };
    function ssT(k) { return (SS_T[k] || {})[LANG] || (SS_T[k] || {}).it || ''; }
    // 2026-08-13 marco — soglia 40 risultati: ordine di allargamento consigliato dal confronto
    // con altre AI (età prima, è la concessione più "morbida"; etnia MAI in automatico, è un
    // attributo identitario). Un solo messaggio combinato, non uno per filtro toccato.
    var SS_MIN_RESULTS = 40;
    var SS_WIDEN_LBL = {
        eta: { it: 'età', en: 'age', fr: 'âge', es: 'edad' },
        geo: { it: 'zona geografica', en: 'nearby area', fr: 'zone géographique', es: 'zona geográfica' }
    };
    var SS_AND = { it: ' e ', en: ' and ', fr: ' et ', es: ' y ' };
    function ssNoteWiden(list) {
        if (!list.length) return;
        var labels = list.map(function (k) { return (SS_WIDEN_LBL[k] || {})[LANG] || (SS_WIDEN_LBL[k] || {}).it || k; });
        var joined = labels.length > 1
            ? labels.slice(0, -1).join(', ') + (SS_AND[LANG] || SS_AND.it) + labels[labels.length - 1]
            : labels[0];
        ssNote(ssT('widen').replace('%s', joined));
    }
    // 2026-08-13 marco — se la ricerca su Talenti da' zero risultati e la frase e' tipica di una
    // figura del Creative Network (fotografo, stylist, truccatore...), NON diciamo solo "nessun
    // risultato": diciamo che la offriamo, con link diretto alla categoria giusta.
    // Parole prese dalle etichette già tradotte in page-crew-database.php ($CREW_CATEGORIES),
    // + qualche sinonimo italiano comune dove l'etichetta è un prestito inglese poco cercato così.
    // ogni valore è un'alternanza regex (parole singole O frasi con \s+ tra le parole),
    // testata sul resto della frase non ancora interpretato — stesso schema di SS_ETNIA_VAL.
    // NOTA: nessun accento nei pattern — a questo punto il testo è già passato da ssNorm(),
    // che li ha già rimossi (é/è→e, ñ→n, ó→o, ecc.), come per tutti gli altri dizionari sopra.
    var SS_CREW_VAL = [
        ['fotografo',          'fotograf[oaie]|photographers?|photographes?'],
        ['videomaker',         'videomakers?|cameraman|videaste|operatore'],
        ['makeup_artist',      'make\\s*up|truccator[ei]|truccatric[ei]|maquilleur|maquilleuse|maquillador[ae]?|mua'],
        ['hairstylist',        'hairstylist'],
        ['parrucchiere',       'parrucchier[ei]|hairdressers?|coiffeur|coiffeuse|peluquer[oa]'],
        ['stylist',            'stylist|styliste|estilista'],
        ['fashion_designer',   'fashion\\s+designer|stilista|designer|disenador[ae]?'],
        ['postproduzione',     'postproduzione|post\\s+production|retouch(?:er)?|ritocco|fotoritocco|retocador'],
        ['video_editing',      'montator[ei]|montatric[ei]|video\\s+editing|montage\\s+video|editor\\s+video|edicion\\s+de\\s+video'],
        ['social_media',       'social\\s+media(?:\\s+manager)?|smm'],
        ['fashion_journalist', 'fashion\\s+journalist|giornalista\\s+di\\s+moda|journaliste\\s+mode|periodista\\s+de\\s+moda'],
        ['art_director',       'art\\s+director|direttore\\s+artistico|directeur\\s+artistique|director\\s+de\\s+arte'],
        ['dj',                 'dj|deejay'],
        ['security',           'security|sicurezza|securite|seguridad|buttafuori'],
        ['tecnico_luci',       'tecnico\\s+luci|lighting\\s+tech|tech\\.?\\s+lumiere|tec\\.?\\s+iluminacion'],
        ['tecnico_suono',      'tecnico\\s+suono|sound\\s+tech|fonico|tech\\.?\\s+son|tec\\.?\\s+sonido'],
        ['runner',             'runner']
    ];
    // Cerca nel resto della frase (parole non riconosciute da nessun altro filtro) una figura
    // tipica del Creative Network. Ritorna il codice categoria o null.
    function ssCrewMatch(restoWords) {
        var s = ' ' + restoWords.join(' ') + ' ';
        for (var i = 0; i < SS_CREW_VAL.length; i++) {
            if (new RegExp('\\b(?:' + SS_CREW_VAL[i][1] + ')\\b').test(s)) return SS_CREW_VAL[i][0];
        }
        return null;
    }
    var SS_CREW_T = {
        it: 'Non troviamo «%s» tra i Talenti, ma lo offriamo nel Creative Network:',
        en: 'We can\'t find «%s» among Talents, but we do offer it in the Creative Network:',
        fr: 'Nous ne trouvons pas «%s» parmi les Talents, mais nous l\'offrons dans le Creative Network :',
        es: 'No encontramos «%s» entre los Talents, pero lo ofrecemos en el Creative Network:'
    };
    var SS_CREW_LINK_T = { it: 'Cerca lì →', en: 'Search there →', fr: 'Chercher là-bas →', es: 'Buscar allí →' };
    // Nota con link cliccabile verso /crew-database/?cat=... (costruita via DOM, mai innerHTML
    // con la frase dell'utente dentro, per sicurezza).
    function ssNoteCrew(code, queryText) {
        var host = $('#tdbNearNote');
        if (!host) {
            var grid = $('#tdbGrid'); if (!grid) return;
            host = document.createElement('div'); host.id = 'tdbNearNote'; host.className = 'toa-tdb-nearnote';
            grid.parentNode.insertBefore(host, grid);
        }
        host.textContent = '';
        var msg = (SS_CREW_T[LANG] || SS_CREW_T.it).replace('%s', queryText.trim());
        host.appendChild(document.createTextNode(msg + ' '));
        var a = document.createElement('a');
        a.href = '/crew-database/?cat=' + encodeURIComponent(code) + (LANG !== 'it' ? '&lang=' + LANG : '');
        a.textContent = SS_CREW_LINK_T[LANG] || SS_CREW_LINK_T.it;
        host.appendChild(a);
        host.hidden = false;
    }
    function ssNorm(s) {
        s = String(s || '').toLowerCase();
        try { s = s.normalize('NFD').replace(/[̀-ͯ]/g, ''); } catch (e) {}
        return s.replace(/[^a-z0-9\s']/g, ' ').replace(/\s+/g, ' ').trim();
    }
    // parole da ignorare: preposizioni e riempitivi nelle 4 lingue
    var SS_STOP = {};
    'a ad in di da per il lo la i gli le un uno una del della dei delle su con e o zona provincia citta comune vicino cerco cercasi voglio trovare mi serve serve the in at near from for and or of city province area je cherche des une un le les dans pres pour et ou en busco cerca ciudad provincia cerca de los las y'.split(' ').forEach(function (w) { if (w) SS_STOP[w] = 1; });
    // fetta 2 — parole-indizio di età/altezza: se la regex non le consuma tutte, i resti
    // non devono finire nella ricerca per nome.
    'tra between entre anni anno years year yo ans an anos ano cm alta alto altezza tall height taille grande grand altura over under almeno minimo massimo entro min max'.split(' ').forEach(function (w) { if (w) SS_STOP[w] = 1; });

    var SS = { comuni: null, provStatic: null, provByCode: null, geoIdx: null, loading: false };

    // Indice geografico costruito dai dati che l'API manda già (filter_options.geo):
    // funziona per IT/FR/ES/GB senza tabelle nostre. Le province sono NOMI, non sigle.
    function ssGeoIndex() {
        if (SS.geoIdx) return SS.geoIdx;
        var geo = ((TD.filterOptions || {}).geo) || {};
        var prov = {}, reg = {}, nick = {}, hubNear = {};
        Object.keys(geo).forEach(function (country) {
            var regioni = (geo[country] || {}).regioni || {};
            Object.keys(regioni).forEach(function (rname) {
                var list = regioni[rname] || [];
                reg[ssNorm(rname)] = { country: country, provs: list.slice() };
                list.forEach(function (p) { prov[ssNorm(p)] = { country: country, name: p }; });
            });
            // 2026-08-13 marco — geo[paese].normalizza ha anche città famose non capoluogo
            // (es. FR "Nizza"->Alpes-Maritimes) e sinonimi/refusi comuni (es. ES "Terragona"
            // ->Tarragona). Prima si usava solo per l'Italia (via SS.comuni); ora vale per tutte
            // le nazioni, riusando dati che il CRM ha già — nessun dato nuovo da indovinare.
            var norm = (geo[country] || {}).normalizza || {};
            Object.keys(norm).forEach(function (nickname) {
                if (/^[a-z]{1,3}$/.test(nickname.toLowerCase()) && nickname === nickname.toUpperCase()) return; // sigle (TO, MI...)
                var key = ssNorm(nickname);
                if (!key || prov[key] || reg[key]) return;   // non sovrascrivere un match già valido
                nick[key] = { country: country, name: norm[nickname] };
            });
            // 2026-08-13 marco — geo[paese].hub ha fasce di vicinanza curate a mano dal CRM (fascia
            // 1 = raggiungibile), per ora per poche città principali per nazione, ma vale per TUTTE
            // le nazioni (non solo Italia come la tabella statica SS_PROV_NEAR). Si inverte: per ogni
            // provincia che compare in una fascia 1, le "vicine" sono le altre della stessa fascia 1.
            var hubs = (geo[country] || {}).hub || {};
            var byProv = {};
            Object.keys(hubs).forEach(function (hubName) {
                var f1 = ((hubs[hubName] || {}).fasce || {})['1'] || [];
                f1.forEach(function (provName) {
                    if (!byProv[provName]) byProv[provName] = [];
                    f1.forEach(function (other) {
                        if (other !== provName && byProv[provName].indexOf(other) < 0) byProv[provName].push(other);
                    });
                });
            });
            if (Object.keys(byProv).length) hubNear[country] = byProv;
        });
        // BUG 2026-08-13 (visto in preview): filter_options arriva in asincrono. Se l'indice viene
        // costruito prima, esce vuoto — e se lo mettiamo in cache resta vuoto per sempre e la
        // ricerca non riconosce più nessuna città. Quindi si mette in cache SOLO se è pieno.
        if (!Object.keys(prov).length) return { prov: {}, reg: {}, nick: {}, hubNear: {} };
        SS.geoIdx = { prov: prov, reg: reg, nick: nick, hubNear: hubNear };
        return SS.geoIdx;
    }
    // sigla provincia → nome canonico usato dall'API (serve a comuni e province vicine).
    // Fonte 1: geo.IT.normalizza (già dall'API). Fonte 2: province-italia.json del tema.
    function ssBuildProvByCode(listaStatica) {
        if (listaStatica) SS.provStatic = listaStatica;
        var idx = ssGeoIndex(), out = {};
        if (!Object.keys(idx.prov).length) return null;   // filter_options non c'è ancora: si riprova al primo uso
        var norm = ((((TD.filterOptions || {}).geo) || {}).IT || {}).normalizza || {};
        Object.keys(norm).forEach(function (code) {
            var hit = idx.prov[ssNorm(norm[code])];
            out[code] = hit ? hit.name : norm[code];
        });
        (SS.provStatic || []).forEach(function (p) {
            if (out[p.code]) return;
            var hit = idx.prov[ssNorm(p.name)];
            if (hit) out[p.code] = hit.name;
        });
        SS.provByCode = out;
        return out;
    }
    // Carica una volta sola comuni-prov.json (8.211 voci) + province-italia.json (110).
    function ssLoadData() {
        if (SS.comuni && SS.provByCode) return Promise.resolve();
        if (SS.loading) return SS.loading;
        var base = (window.toaThemeUri || '') + '/assets/data/';
        SS.loading = Promise.all([
            fetch(base + 'comuni-prov.json').then(function (r) { return r.json(); }).catch(function () { return {}; }),
            fetch(base + 'province-italia.json').then(function (r) { return r.json(); }).catch(function () { return []; })
        ]).then(function (res) {
            SS.comuni = res[0] || {};
            ssBuildProvByCode(res[1] || []);
        }).catch(function () { SS.comuni = SS.comuni || {}; SS.provByCode = SS.provByCode || {}; });
        return SS.loading;
    }
    // Frase → { ruolo, province[], country, resto[] }. Finestre di 3/2/1 parole per i comuni
    // e le province composte ("monza e brianza", "reggio calabria", "san giuliano milanese").
    function ssParse(text) {
        var idx = ssGeoIndex();
        var numRes = ssExtractNumeric(ssNorm(text));
        var attrRes = ssExtractAttrs(numRes.text);
        var toks = attrRes.text.split(' ').filter(Boolean);
        var out = {
            ruolo: '', sesso: '', province: [], country: '', resto: [], lingua: [],
            eta_min: numRes.out.eta_min, eta_max: numRes.out.eta_max,
            altezza_min: numRes.out.altezza_min, altezza_max: numRes.out.altezza_max,
            capelli: attrRes.out.capelli, occhi: attrRes.out.occhi, etnia: attrRes.out.etnia,
            taglia: attrRes.out.taglia, automunito: attrRes.out.automunito
        };
        var i = 0;
        while (i < toks.length) {
            var consumed = 0;   // quante parole ha mangiato il match (0 = nessun match)
            for (var len = Math.min(3, toks.length - i); len >= 1 && !consumed; len--) {
                var key = toks.slice(i, i + len).join(' ');
                var hitReg = idx.reg[key];
                if (hitReg) {
                    hitReg.provs.forEach(function (p) { if (out.province.indexOf(p) < 0) out.province.push(p); });
                    out.country = out.country || hitReg.country; consumed = len; break;
                }
                var hitProv = idx.prov[key];
                if (hitProv) {
                    if (out.province.indexOf(hitProv.name) < 0) out.province.push(hitProv.name);
                    out.country = out.country || hitProv.country; consumed = len; break;
                }
                var hitNick = idx.nick ? idx.nick[key] : null;
                if (hitNick) {
                    if (out.province.indexOf(hitNick.name) < 0) out.province.push(hitNick.name);
                    out.country = out.country || hitNick.country; consumed = len; break;
                }
                var code = SS.comuni ? SS.comuni[key] : null;
                if (code && SS.provByCode && SS.provByCode[code]) {
                    var nm = SS.provByCode[code];
                    if (out.province.indexOf(nm) < 0) out.province.push(nm);
                    out.country = out.country || 'IT'; consumed = len; break;
                }
                if (len === 1 && SS_ROLE_SET[key]) {
                    out.ruolo = out.ruolo || SS_ROLE_SET[key];
                    if (!out.sesso && SS_ROLE_GENDER[key]) out.sesso = SS_ROLE_GENDER[key];
                    consumed = 1; break;
                }
                if (len === 1 && SS_GENDER_SET[key] && !out.sesso) { out.sesso = SS_GENDER_SET[key]; consumed = 1; break; }
                if (len === 1 && SS_LANG_SET[key] && out.lingua.indexOf(SS_LANG_SET[key]) < 0) { out.lingua.push(SS_LANG_SET[key]); consumed = 1; break; }
            }
            if (consumed) { i += consumed; }
            else { if (!SS_STOP[toks[i]]) out.resto.push(toks[i]); i++; }
        }
        return out;
    }
    // Nota "province vicine" sopra la griglia (creata al volo, nessun markup nel template).
    function ssNote(msg) {
        var host = $('#tdbNearNote');
        if (!host) {
            var grid = $('#tdbGrid'); if (!grid) return;
            host = document.createElement('div'); host.id = 'tdbNearNote'; host.className = 'toa-tdb-nearnote';
            grid.parentNode.insertBefore(host, grid);
        }
        host.textContent = msg || '';
        host.hidden = !msg;
    }
    // fetta 3 — spunta/deseleziona i checkbox di un multi-select esistente (msBox/msText sono
    // le funzioni già usate dal pannello filtri, definite più sopra nel file).
    function ssSetMs(name, values) {
        var box = msBox(name); if (!box) return;
        var want = (values || []).map(function (v) { return String(v).toLowerCase(); });
        $$('.toa-tdb-ms-menu input', box).forEach(function (c) {
            c.checked = want.indexOf(String(c.value).toLowerCase()) !== -1;
        });
        msText(box);
    }
    // Scrive i filtri e lancia la ricerca. Se zero risultati e c'era una provincia, allarga alle vicine.
    function ssApply(text) {
        var f = $('#tdbFilters'); if (!f) return;
        if (!SS.provByCode) ssBuildProvByCode();   // 2° tentativo: ora filter_options c'è
        var p = ssParse(text);
        var sel = $('#tdbFilterRuolo');
        if (sel) { sel.value = p.ruolo || ''; }
        $$('.toa-tdb-cat-chip').forEach(function (c) {
            c.classList.toggle('is-active', (c.getAttribute('data-ruolo') || '') === (p.ruolo || ''));
        });
        if (typeof toggleHostessFilters === 'function') toggleHostessFilters();
        var cEl = $('#tdbFilterCountry');
        if (cEl && p.country && cEl.value !== p.country) cEl.value = p.country;
        TD.selectedProvinces = p.province.slice();
        TD.geoHub = null;
        populateProvinces();
        // fetta 2 — sesso (toggle-group) + età/altezza (input number)
        var sg = $('.toa-tdb-toggle-group[data-name="sesso"]');
        if (sg) {
            $$('.toa-tdb-toggle', sg).forEach(function (b) { b.classList.toggle('active', (b.getAttribute('data-value') || '') === (p.sesso || '')); });
            var sh = sg.querySelector('input[type="hidden"]'); if (sh) sh.value = p.sesso || '';
        }
        ['eta_min', 'eta_max', 'altezza_min', 'altezza_max'].forEach(function (k) {
            var el = f[k]; if (el) el.value = (p[k] !== undefined && p[k] !== null) ? p[k] : '';
        });
        // fetta 3 — capelli/occhi/etnia/taglia (sempre) + lingua/automunito (solo se ruolo=hostess,
        // stesso contratto API di readFilters()); toggleHostessFilters() sopra ha già nascosto/azzerato
        // il blocco se non è hostess.
        ['capelli', 'occhi', 'etnia', 'taglia'].forEach(function (name) { ssSetMs(name, p[name]); });
        if (isHostessRole()) {
            ssSetMs('lingua', p.lingua);
            var autoEl = f.automunito; if (autoEl) autoEl.checked = !!p.automunito;
        }
        if (f.q) f.q.value = p.resto.join(' ');
        ssNote('');
        var widened = [];   // etichette di cosa abbiamo ampliato, per il messaggio finale unico
        // 1° tentativo → 2° senza le parole non capite (se ha azzerato tutto)
        return tdSearch(false).then(function () {
            if (TD.total > 0) return;
            if (!p.resto.length || (!p.ruolo && !p.province.length)) return;
            // le parole che non ho riconosciuto stavano cercando un NOME e hanno azzerato tutto:
            // meglio mostrare il resto della ricerca che una pagina vuota
            if (f.q) f.q.value = '';
            return tdSearch(false).then(function () {
                if (TD.total > 0) ssNote(ssT('dropped').replace('%w', p.resto.join(' ')));
            });
        }).then(function () {
            // 3° — soglia SS_MIN_RESULTS: prima si allarga l'ETÀ (±5 anni), la concessione più
            // "morbida" in un casting — vedi confronto con altre AI del 13/08. Etnia/ruolo non si
            // allargano MAI in automatico (sono vincoli di brief, non compromessi accettabili).
            if (TD.total >= SS_MIN_RESULTS) return;
            var hasMin = f.eta_min && f.eta_min.value !== '', hasMax = f.eta_max && f.eta_max.value !== '';
            if (!hasMin && !hasMax) return;
            if (hasMin) f.eta_min.value = Math.max(6, parseInt(f.eta_min.value, 10) - 5);
            if (hasMax) f.eta_max.value = Math.min(99, parseInt(f.eta_max.value, 10) + 5);
            widened.push('eta');
            return tdSearch(false);
        }).then(function () {
            // 4° — ancora sotto soglia: allarga anche la PROVINCIA alle vicine. Preferisce le fasce
            // hub del CRM (dati reali, valgono per tutte le nazioni); se la provincia non è coperta
            // da nessun hub, ripiega sulla tabella statica ~130km (oggi disponibile solo per l'Italia).
            if (TD.total >= SS_MIN_RESULTS || !p.province.length) return;
            var hubIdx = ssGeoIndex().hubNear || {};
            var hubByCountry = hubIdx[p.country] || {};
            var codeOf = null;
            if (SS.provByCode) { codeOf = {}; Object.keys(SS.provByCode).forEach(function (c) { codeOf[SS.provByCode[c]] = c; }); }
            var near = [];
            p.province.forEach(function (nome) {
                var fromHub = hubByCountry[nome];
                if (fromHub && fromHub.length) {
                    fromHub.forEach(function (nm) { if (p.province.indexOf(nm) < 0 && near.indexOf(nm) < 0) near.push(nm); });
                    return;
                }
                if (p.country === 'IT' && codeOf) {
                    (SS_PROV_NEAR[codeOf[nome]] || []).forEach(function (c) {
                        var nm = SS.provByCode[c];
                        if (nm && p.province.indexOf(nm) < 0 && near.indexOf(nm) < 0) near.push(nm);
                    });
                }
            });
            if (!near.length) return;
            TD.selectedProvinces = p.province.concat(near);
            populateProvinces();
            widened.push('geo');
            return tdSearch(false);
        }).then(function () {
            if (widened.length) ssNoteWiden(widened);
            // 5° tentativo, ultima spiaggia: ancora zero risultati e la frase è tipica di una
            // figura del Creative Network (fotografo, stylist, truccatore...) → non un "nessun
            // risultato" muto, ma il link diretto a dove quella figura si trova davvero.
            if (TD.total > 0) return;
            var crewCode = ssCrewMatch(p.resto);
            if (crewCode) ssNoteCrew(crewCode, text);
        });
    }
    function initSmartSearch() {
        var input = $('#tdbSmartSearch'); if (!input) return;
        var run = debounce(function () {
            var v = input.value.trim();
            if (!v) { ssNote(''); if ($('#tdbFilters').q) $('#tdbFilters').q.value = ''; TD.selectedProvinces = []; populateProvinces(); tdSearch(false); return; }
            ssLoadData().then(function () { ssApply(v); });
        }, 450);
        input.addEventListener('input', run);
        input.addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); input.blur(); run(); } });
        ssLoadData();   // scalda i due json al primo render (159KB, una volta sola)
    }

    // ═════════════════════════════════════════════════════════════════
    // BOOT
    // ═════════════════════════════════════════════════════════════════
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { tdInit(); initSmartSearch(); });
    } else {
        tdInit(); initSmartSearch();
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
      // FIX 2026-07-03 marco - tolto filtro isFallback: scartava la cover e lasciava 1 sola foto (frecce morte)
      var media=((d&&d.media)||[]).map(function(m){return m.url;}).filter(Boolean);
      card._cnMedia=media.length?media:[cur];
      card._cnMedia.forEach(function(u){ var im=new Image(); im.src=u; }); // FIX 2026-07-09 marco: precarico le foto della card -> scorrimento immediato (prima ogni foto era scaricata al volo col watermark = lento)
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
      var cur=img.getAttribute('src')||'';
      // FIX 2026-07-03 marco - tolto filtro isFallback (vedi frecce card)
      var m=((d&&d.media)||[]).map(function(x){return x.url;}).filter(Boolean);
      card._cnMedia=m.length?m:[cur];
      card._cnMedia.forEach(function(u){ var im=new Image(); im.src=u; }); // FIX 2026-07-09 marco: precarico foto card (swipe immediato)
      var st=card._cnMedia.indexOf(cur);
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

;/* FIX 2026-07-09 marco — mantieni i filtri quando si cambia lingua: lo switcher WPML (.nav-lang-item)
   porta all'URL tradotto SENZA query string -> i filtri si azzeravano. L'URL e tenuto in sync coi filtri
   da replaceState, quindi al click aggiungo window.location.search all'href della lingua (solo se il link
   non ha gia una query). Page-scoped: non tocca components/header.php condiviso. */
(function(){
  document.addEventListener('click', function(e){
    var a = e.target && e.target.closest && e.target.closest('a.nav-lang-item');
    if(!a || !a.href) return;
    var qs = window.location.search;           // filtri correnti
    if(!qs || qs === '?') return;
    try {
      var u = new URL(a.href, window.location.origin);
      if(!u.search){ u.search = qs; a.href = u.toString(); }
    } catch(err){}
  }, true);
})();
