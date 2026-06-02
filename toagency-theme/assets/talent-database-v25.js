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
    var PER_PAGE    = 24;

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
        var m1 = $('#tdbTalentModal'), m2 = $('#tdbRequestModal'), m3 = $('#tdbSuccess');
        return (m1 && !m1.hidden) || (m2 && !m2.hidden) || (m3 && !m3.hidden);
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

    // Popola i <select> dei filtri usando TD.filterOptions, poi applica URL state e popola province.
    function populateSelects() {
        var fo = TD.filterOptions || {};
        var anyLabel = $('#tdbFilterCountry option[value=""]').textContent;

        if (fo.paesi)   $('#tdbFilterCountry').innerHTML   = buildOptions(fo.paesi, 'code', 'label', anyLabel);
        if (fo.etnia)   $('#tdbFilterEthnicity').innerHTML = buildOptions(fo.etnia, null, null, anyLabel);
        if (fo.capelli) $('#tdbFilterHair').innerHTML      = buildOptions(fo.capelli, null, null, anyLabel);
        if (fo.occhi)   $('#tdbFilterEyes').innerHTML      = buildOptions(fo.occhi, null, null, anyLabel);

        applyUrlStateAfterOptions();
        populateProvinces();
    }

    // Popola il <select> Provincia in base al Paese corrente.
    function populateProvinces() {
        var fo = TD.filterOptions || {};
        var country = $('#tdbFilterCountry').value;
        var sel = $('#tdbFilterProvince');
        var anyLabel = sel.querySelector('option[value=""]').textContent;
        var list = (fo.province_by_country && country) ? (fo.province_by_country[country] || []) : [];
        var prev = sel.value;
        sel.innerHTML = buildOptions(list, null, null, anyLabel);
        // Mantiene la selezione precedente se ancora in lista.
        if (prev && Array.prototype.some.call(sel.options, function (o) { return o.value === prev; })) {
            sel.value = prev;
        }
    }

    // Legge i valori del form filtri e li serializza in oggetto piatto da inviare all'API.
    function readFilters() {
        var f = $('#tdbFilters');
        var out = {};
        var q = f.q.value.trim();
        if (q) out.q = q;
        if (f.sesso.value)     out.sesso     = f.sesso.value;
        if (f.paese.value)     out.paese     = f.paese.value;
        if (f.provincia.value) out.provincia = f.provincia.value;
        if (f.etnia.value)     out.etnia     = f.etnia.value;
        if (f.capelli.value)   out.capelli   = f.capelli.value;
        if (f.occhi.value)     out.occhi     = f.occhi.value;

        var taglie = $$('.toa-tdb-chip-group[data-name="taglia"] .toa-tdb-chip.active')
            .map(function (c) { return c.dataset.value; });
        if (taglie.length) out.taglia = taglie;

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
        if (tid)       line.push('#' + tid);
        if (t.eta)     line.push(escapeHtml(t.eta + ' anni'));
        if (t.altezza) line.push(escapeHtml(t.altezza + ' cm'));
        if (t.provincia) line.push(escapeHtml(String(t.provincia).toUpperCase()));
        var lineHtml = line.join(' · ');

        return '<article class="toa-tdb-card' + sel + '" data-id="' + id + '">' +
                 '<button type="button" class="toa-tdb-card-add" data-add="1" aria-label="' + escapeHtml(i18n('btn_add')) + '">' +
                   (sel ? '✓' : '+') +
                 '</button>' +
                 (eager
                    ? '<img class="toa-tdb-card-img"' + rotStyle + ' src="' + escapeHtml(fotoSrc) + '" alt="' + escapeHtml(t.nome || '') + '">'
                    : '<img class="toa-tdb-card-img lazyload"' + rotStyle + ' data-src="' + escapeHtml(fotoSrc) + '" alt="' + escapeHtml(t.nome || '') + '">') +
                 '<div class="toa-tdb-card-meta">' +
                   '<div class="toa-tdb-card-line">' + lineHtml + '</div>' +
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
        addRow('GENDER',  t.sesso);
        addRow('COUNTRY', t.paese_label || t.paese);
        addRow('CITY',    t.citta);
        addRow(i18n('modal_age'), t.eta != null && t.eta !== '' ? t.eta + ' ' + i18n('years') : null);
        addRow('HEIGHT',  t.altezza ? t.altezza + ' cm' : null);
        addRow('SIZE',    t.taglia);
        addRow('HAIR',    t.capelli ? cap(t.capelli) : null);
        addRow('EYES',    t.occhi ? cap(t.occhi) : null);
        addRow('SHOES',   t.scarpe);
        if (t.sesso === 'F') {
            addRow('CHEST', t.misura_petto   ? t.misura_petto   + ' cm' : null);
            addRow('WAIST', t.misura_vita    ? t.misura_vita    + ' cm' : null);
            addRow('HIPS',  t.misura_fianchi ? t.misura_fianchi + ' cm' : null);
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

    // Apre/chiude il drawer filtri su mobile + classe body per backdrop.
    function toggleSidebar(open) {
        $('#tdbSidebar').classList.toggle('open', open);
        document.body.classList.toggle('tdb-drawer-open', open);
        $('#tdbFiltersToggle').setAttribute('aria-expanded', open ? 'true' : 'false');
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
        if (current.get('talent')) params.set('talent', current.get('talent'));
        var qs = params.toString();
        var url = window.location.pathname + (qs ? '?' + qs : '');
        window.history.replaceState({ kind: 'list' }, '', url);
    }

    // pushState quando si apre il modal scheda (così il back button chiude il modal).
    function pushUrlTalent(id) {
        var params = buildQueryFromFilters(TD.filters, TD.page);
        params.set('talent', id);
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
        setVal('etnia', params.get('etnia'));
        setVal('capelli', params.get('capelli'));
        setVal('occhi', params.get('occhi'));
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
        var taglie = params.getAll('taglia');
        if (taglie.length) {
            $$('.toa-tdb-chip-group[data-name="taglia"] .toa-tdb-chip').forEach(function (c) {
                c.classList.toggle('active', taglie.indexOf(c.dataset.value) !== -1);
            });
        }
        var provincia = params.get('provincia');
        if (provincia) {
            // Le province sono popolate dopo populateProvinces(); deferiamo al microtask.
            setTimeout(function () { setVal('provincia', provincia); }, 0);
        }
    }

    // Apre il modal scheda se la URL contiene ?talent=ID (deep-link).
    function maybeOpenTalentFromUrl() {
        var params = new URLSearchParams(window.location.search);
        var tid = parseInt(params.get('talent') || '0', 10);
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
            populateProvinces();
            tdSearch(false);
        });
        // Paese: ripopola province E rilancia ricerca
        $('#tdbFilterCountry').addEventListener('change', function () {
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
                e.stopPropagation();
                tdToggleSelected(id);
                return;
            }
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
            var m1 = $('#tdbTalentModal'), m2 = $('#tdbRequestModal'), m3 = $('#tdbSuccess');
            if (m1 && !m1.hidden && e.target.closest('#tdbTalentModal'))     tdCloseTalentModal();
            else if (m2 && !m2.hidden && e.target.closest('#tdbRequestModal')) closeRequestModal();
            else if (m3 && !m3.hidden && e.target.closest('#tdbSuccess'))      closeSuccess();
        });
        document.addEventListener('keydown', function (e) {
            var t = $('#tdbTalentModal');
            if (t && !t.hidden) {
                if (e.key === 'ArrowLeft')  { tdGalleryPrev(); return; }
                if (e.key === 'ArrowRight') { tdGalleryNext(); return; }
            }
            if (e.key !== 'Escape') return;
            var s = $('#tdbSuccess'), r = $('#tdbRequestModal');
            var sb = $('#tdbSidebar');
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
        $('#tdbFiltersToggle').addEventListener('click', function () {
            toggleSidebar(!$('#tdbSidebar').classList.contains('open'));
        });

        // 2026-06-02 marco — toggle sidebar DESKTOP: collassa la sidebar → griglia a 5 colonne. Stato persistito.
        var deskBtn = $('#tdbSidebarToggle');
        var wrap = $('#tdb-database');
        if (deskBtn && wrap) {
            var KEY = 'toaTdbSidebarCollapsed';
            function applyCollapsed(collapsed) {
                wrap.classList.toggle('tdb-sidebar-collapsed', collapsed);
                deskBtn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                deskBtn.textContent = collapsed ? '✕' : '☰';
            }
            deskBtn.addEventListener('click', function () {
                var collapsed = !wrap.classList.contains('tdb-sidebar-collapsed');
                applyCollapsed(collapsed);
                try { localStorage.setItem(KEY, collapsed ? '1' : '0'); } catch (e) {}
            });
            try { if (localStorage.getItem(KEY) === '1') applyCollapsed(true); } catch (e) {}
        }

        // 2026-06-02 marco — chip categoria → imposta il filtro Categoria (#tdbFilterRuolo) + lancia la ricerca
        var chips = $('#tdbCatChips');
        if (chips) {
            chips.addEventListener('click', function (e) {
                var chip = e.target.closest('.toa-tdb-cat-chip');
                if (!chip) return;
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
