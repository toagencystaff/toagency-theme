/**
 * crew-database-list.js — v2.16 (2026-08-11)
 * v2.16: comuni completi nella ricerca ("estetista ivrea" → provincia TO): mappa
 *        assets/data/comuni-prov.json (8211 voci, generata da _data/comuni_raw.json ISTAT/github),
 *        caricata LAZY al primo uso della ricerca; comuni multi-parola (fino a 3) e doppioni
 *        (Samone TO/TN) gestiti; per i token singoli vale comune OPPURE testo normale.
 * v2.15: ricerca geografica leggera — regioni ("piemonte" trova TO/VC/CN..., con esonimi
 *        piedmont/lombardy/tuscany...) + grandi città in EN/FR/ES (turin, milan, rome...).
 *        NIENTE comuni (~7900, file pesante) e NIENTE fallback province vicine: task futuri.
 * v2.14: feedback Marco — ricerca multi-parola in AND ("truccatrice milano" = categoria E
 *        provincia insieme, in qualsiasi ordine).
 * v2.13: feedback Marco — la ricerca capisce i sinonimi di uso comune ("truccatrice" →
 *        Make-Up Artist, "capelli" → Hairstylist, "shooting" → Fotografo): dizionario
 *        CAT_KEYWORDS per categoria (IT+EN+FR+ES) aggiunto al testo cercabile della card.
 * v2.12: feedback Marco — badge ▶ sulle cover che sono poster di VIDEO (senza hover non si
 *        capiva che fossero video): sincronizzato con cover iniziale, crossfade e frecce,
 *        nascosto durante l'hover-play (CSS :has). + messaggio "nessun risultato per «X»"
 *        quando la ricerca testuale non trova nulla (prima: messaggio generico).
 * v2.11: deep-link ricerca/filtri nell'URL (?q/&cat/&paese/&prov via replaceState, lang e uuid
 *        preservati; pattern talent-db v76) + eventi GTM dataLayer: crew_search (termine +
 *        n. risultati), crew_filter_cat, crew_vedi_tutti — per capire cosa cercano i visitatori.
 * v2.10: feedback Marco su preview — niente profili ripetuti tra le strisce "In evidenza":
 *        ogni crew compare in UNA sola striscia (resta nella categoria sua piu' numerosa);
 *        striscia che dopo il dedup scende sotto 2 card non si mostra.
 * v2.9: CREATIVE-HOME Fase 2 — strisce editoriali "In evidenza": le 3-4 categorie con piu' crew
 *       (min 2 con media, 'altro' escluso), top 8 a striscia gia' ordinati per voto staff dal CRM.
 *       Card estratta in buildCard() e riusata identica (crossfade, hover-video, selezione).
 *       Le strisce compaiono SOLO senza filtri/ricerca attivi; "Vedi tutti →" attiva il chip.
 * v2.8: CREATIVE-HOME Fase 2 — ricerca testuale in evidenza (input nell'hero): filtro client-side
 *       su nome/categoria/provincia/paese/codice sui risultati gia' caricati, debounce 200ms,
 *       nessuna chiamata CRM aggiuntiva. Accenti normalizzati (cerca "fotografo" trova "Fotógrafo").
 * v2.7: hover-to-play — su desktop (hover+pointer fine) passando il mouse su una card con video
 *       parte il video muto in loop nel riquadro cover; all'uscita si ferma e si distrugge (zero
 *       consumi finché non interagisci). Su mobile resta il poster. Futuro: clip ottimizzate CRM.
 * v2.6: usa n_foto/n_video dalla search (CRM CREW-VOTAZIONE): crew senza media scartati al RENDER
 *       (niente flash placeholder, contatore giusto da subito), "N lavori" immediato. L'ordinamento
 *       per voto staff e' tutto lato CRM (il voto NON arriva nel JSON e NON va mai mostrato).
 * v2.5: sweepMissingCovers con coda (max 3 fetch concorrenti) — da v2.2 lo sweep copriva tutta
 *       la griglia in un colpo (~25 fetch paralleli, rallentavano l'apertura delle schede)
 * v2.4: card — i video ruotano col loro poster JPG (prima scartati: chi aveva solo video sembrava
 *       vuoto, caso Simone); crew senza alcun media → card nascosta + contatore aggiornato
 * v2.3: rotazione raddoppiata (1.2–2.4s, fade .5s) — feedback Marco; ruota già TUTTO il portfolio
 * v2.2: feedback Marco — la foto PROFILO non compare mai nel riquadro grande (è già nell'avatar
 *       tondo): ruotano SOLO le foto portfolio, placeholder se il crew non ne ha; rotazione più
 *       veloce (2.5–4.5s, fade 0.7s)
 * v2.1: CREW-REDESIGN — la cover della card è la foto SCELTA dal crew (cover_url, non più random)
 *       + crossfade automatico lento tra le foto portfolio (pausa su hover, off con prefers-reduced-motion);
 *       bottone "Portfolio →" sulla card; emoji fallback in <span> (photo.textContent='' cancellava i child)
 * v2.0: Fase 3 — frecce hover sulla card per scorrere le foto lavori (pattern talent, lazy)
 * v1.9: placeholder bio quando vuota
 * v1.8: tendina provincia visibile solo per Paese IT/Tutti (estero filtra per Paese)
 * v1.7: filtro provincia (tendina da province-italia.json) → param 'provincia' nel search
 * v1.6: copertina hero (prima foto in cima alla scheda) con nome in overlay; fallback nome nell'header
 * v1.5: riga età + anzianità nella scheda (eta/attivita_dal/pro_dal dall'endpoint)
 * v1.4: provincia mostrata col nome esteso (TO → Torino) via mappa sigle IT
 * v1.3: chiusura lightbox su click foto/sfondo (+ × etichettato); CTA "Richiedi info" dentro la scheda (pre-seleziona il crew)
 * v1.2: foto profilo grandi in lightbox (‹ › + tastiera) via proxy &w=; miniature &w=600; provincia in scheda (privacy: no comune)
 * v1.1: scheda singola crew (?uuid=) con portfolio per ruolo + generale + bio (endpoint crew-public-profile.php)
 * JS per /crew-database/ (catalogo pubblico crew).
 *
 * - Carica grid via POST a /crm_toagency/actions/crew-public-search.php
 * - Filtri categoria + paese
 * - Selezione multi-card (set di UUID)
 * - Modal lead → POST a /crm_toagency/actions/crew-lead.php
 */
(function () {
    'use strict';

    var cfg = window.crewPubConfig || {};
    var API_SEARCH = cfg.apiSearch || '/crm_toagency/actions/crew-public-search.php';
    var API_LEAD   = cfg.apiLead   || '/crm_toagency/actions/crew-lead.php';
    var API_PROFILE= cfg.apiProfile|| '/crm_toagency/actions/crew-public-profile.php';
    var STR        = cfg.strings   || {};

    // Province IT: sigla → nome esteso (per il display pubblico "Torino" invece di "TO")
    var PROV = {
        AG:'Agrigento',AL:'Alessandria',AN:'Ancona',AO:'Aosta',AR:'Arezzo',AP:'Ascoli Piceno',AT:'Asti',AV:'Avellino',
        BA:'Bari',BT:'Barletta-Andria-Trani',BL:'Belluno',BN:'Benevento',BG:'Bergamo',BI:'Biella',BO:'Bologna',BZ:'Bolzano',
        BS:'Brescia',BR:'Brindisi',CA:'Cagliari',CL:'Caltanissetta',CB:'Campobasso',CE:'Caserta',CT:'Catania',CZ:'Catanzaro',
        CH:'Chieti',CO:'Como',CS:'Cosenza',CR:'Cremona',KR:'Crotone',CN:'Cuneo',EN:'Enna',FM:'Fermo',FE:'Ferrara',
        FI:'Firenze',FG:'Foggia',FC:'Forlì-Cesena',FR:'Frosinone',GE:'Genova',GO:'Gorizia',GR:'Grosseto',IM:'Imperia',
        IS:'Isernia',AQ:"L'Aquila",SP:'La Spezia',LT:'Latina',LE:'Lecce',LC:'Lecco',LI:'Livorno',LO:'Lodi',LU:'Lucca',
        MC:'Macerata',MN:'Mantova',MS:'Massa-Carrara',MT:'Matera',ME:'Messina',MI:'Milano',MO:'Modena',MB:'Monza e della Brianza',
        NA:'Napoli',NO:'Novara',NU:'Nuoro',OR:'Oristano',PD:'Padova',PA:'Palermo',PR:'Parma',PV:'Pavia',PG:'Perugia',
        PU:'Pesaro e Urbino',PE:'Pescara',PC:'Piacenza',PI:'Pisa',PT:'Pistoia',PN:'Pordenone',PZ:'Potenza',PO:'Prato',
        RG:'Ragusa',RA:'Ravenna',RC:'Reggio Calabria',RE:'Reggio Emilia',RI:'Rieti',RN:'Rimini',RM:'Roma',RO:'Rovigo',
        SA:'Salerno',SS:'Sassari',SV:'Savona',SI:'Siena',SR:'Siracusa',SO:'Sondrio',SU:'Sud Sardegna',TA:'Taranto',
        TE:'Teramo',TR:'Terni',TO:'Torino',TP:'Trapani',TN:'Trento',TV:'Treviso',TS:'Trieste',UD:'Udine',VA:'Varese',
        VE:'Venezia',VB:'Verbano-Cusio-Ossola',VC:'Vercelli',VR:'Verona',VV:'Vibo Valentia',VI:'Vicenza',VT:'Viterbo'
    };
    function provName(code) {
        var c = String(code || '').toUpperCase().trim();
        return PROV[c] || code;
    }

    // 2026-07-26 — richiesta Marco: nomi sempre "Prima lettera maiuscola poi minuscolo" a display (il dato in DB puo' arrivare in qualsiasi case)
    function properCase(s) {
        if (!s) return s;
        return String(s).toLowerCase().replace(/(^|[\s'-])([a-zà-ÿ])/g, function (m, sep, ch) { return sep + ch.toUpperCase(); });
    }

    var selectedUuids = new Set();
    var lastResults = [];
    // 2026-08-11 v2.8 — ricerca testuale client-side: si applica a lastResults, zero chiamate extra
    var textQuery = '';
    // 2026-08-11 v2.11 — provincia da deep-link: applicata DOPO che populateProvinceFilter ha
    // riempito la tendina (le opzioni arrivano async da province-italia.json)
    var __initProv = '';
    // 2026-08-11 v2.11 — sync di ?q/&cat/&paese/&prov nell'URL: replaceState parte dai parametri
    // correnti e tocca SOLO le 4 chiavi sue → lang (override lingua) e uuid (profilo) restano intatti
    function syncUrl() {
        var p = new URLSearchParams(window.location.search);
        var provEl = $('#filter-provincia');
        var setOrDel = function (k, v) { if (v) p.set(k, v); else p.delete(k); };
        setOrDel('q', textQuery);
        setOrDel('cat', $('#filter-categoria').value);
        setOrDel('paese', $('#filter-paese').value);
        setOrDel('prov', provEl ? provEl.value : '');
        var s = p.toString();
        history.replaceState(history.state, '', window.location.pathname + (s ? '?' + s : '') + window.location.hash);
    }
    // 2026-08-11 v2.11 — eventi verso GTM (dataLayer già presente sul sito, guard se assente)
    function gtmPush(obj) { (window.dataLayer = window.dataLayer || []).push(obj); }
    function normTxt(s) {
        s = String(s || '').toLowerCase();
        try { s = s.normalize('NFD').replace(/[\u0300-\u036f]/g, ''); } catch (e) {}
        return s;
    }
    // 2026-08-11 v2.13 — sinonimi di uso comune per categoria (feedback Marco: "truccatrice"
    // deve trovare le Make-Up Artist). Parole cercabili, MAI mostrate: la regola 4-lingue dei
    // testi visibili non si applica, ma i termini chiave ci sono in IT+EN+FR+ES. Le query corte
    // matchano come sottostringa ("trucco" dentro "truccatrice"), quindi bastano le forme lunghe.
    var CAT_KEYWORDS = {
        fotografo:          'fotografa fotografia photographer photography shooting servizio fotografico photographe fotografa',
        videomaker:         'video filmmaker videografo riprese regista drone vidéaste videografa',
        makeup_artist:      'truccatrice truccatore trucco make up makeup mua estetista maquillage maquilleuse maquilleur maquillaje maquilladora',
        hairstylist:        'parrucchiera parrucchiere acconciatura acconciature capelli hair styling coiffure coiffeuse coiffeur peinado peluquera peluquero',
        parrucchiere:       'parrucchiera capelli taglio piega acconciatura acconciature barbiere coiffure coiffeur peluquera peluquero',
        stylist:            'stilista styling moda outfit abbigliamento styliste estilista',
        fashion_designer:   'stilista moda sartoria sarta sarto couture créateur diseñadora diseñador',
        postproduzione:     'ritocco fotoritocco photoshop retouching retouche retoque editing foto',
        video_editing:      'montaggio montatore montatrice editor video éditeur montaje',
        social_media:       'social instagram tiktok facebook community manager smm redes sociales',
        content_creator:    'creator contenuti influencer ugc créateur de contenu creador de contenido',
        fashion_journalist: 'giornalista redattrice redattore journaliste periodista',
        art_director:       'direttore artistico direttrice artistica direzione artistica creativo directeur artistique director de arte',
        dj:                 'deejay musica console mixer musique música',
        security:           'sicurezza vigilanza buttafuori sécurité seguridad',
        tecnico_luci:       'luci illuminazione light lighting éclairage iluminación',
        tecnico_suono:      'suono audio fonico sound sonorisation sonido',
        runner:             'assistente backstage tuttofare'
    };
    // 2026-08-11 v2.15 — geo leggera: regione (con esonimi EN/FR/ES) cercabile per ogni provincia
    var REGIONE_PROVINCE = {
        'piemonte piedmont piémont':                   'TO VC NO CN AT AL BI VB',
        'valle d aosta aoste':                         'AO',
        'lombardia lombardy lombardie':                'MI BG BS CO CR MN PV SO VA LC LO MB',
        'trentino alto adige sudtirol':                'TN BZ',
        'veneto venetie':                              'VE VR VI PD TV BL RO',
        'friuli venezia giulia':                       'UD GO PN TS',
        'liguria ligurie':                             'GE IM SP SV',
        'emilia romagna emilie':                       'BO FE FC MO PR PC RA RE RN',
        'toscana tuscany toscane':                     'FI AR GR LI LU MS PI PT PO SI',
        'umbria ombrie':                               'PG TR',
        'marche':                                      'AN AP FM MC PU',
        'lazio latium':                                'RM VT RI LT FR',
        'abruzzo abruzzes':                            'AQ CH PE TE',
        'molise':                                      'CB IS',
        'campania campanie':                           'NA AV BN CE SA',
        'puglia apulia pouilles':                      'BA BT BR FG LE TA',
        'basilicata basilicate':                       'PZ MT',
        'calabria calabre':                            'CZ CS KR RC VV',
        'sicilia sicily sicile':                       'PA CT ME AG CL EN RG SR TP',
        'sardegna sardinia sardaigne cerdena':         'CA SS NU OR SU'
    };
    var PROV_REGION = {};
    Object.keys(REGIONE_PROVINCE).forEach(function (r) {
        REGIONE_PROVINCE[r].split(' ').forEach(function (s) { PROV_REGION[s] = r; });
    });
    // grandi città: esonimi EN/FR/ES/DE cercabili (il nome italiano c'e' gia' via provName)
    var PROV_EXONYMS = {
        TO: 'turin', MI: 'milan mailand', RM: 'rome', FI: 'florence florencia',
        NA: 'naples napules', VE: 'venice venise venecia', GE: 'genoa genes genova',
        PD: 'padua padoue', MN: 'mantua'
    };
    // 2026-08-11 v2.16 — comuni→provincia: mappa lazy (158KB, solo al primo uso della ricerca)
    var __comuniMap = null, __comuniLoading = false;
    function loadComuni() {
        if (__comuniMap || __comuniLoading || !cfg.comuniJsonUrl) return;
        __comuniLoading = true;
        fetch(cfg.comuniJsonUrl).then(function (r) { return r.json(); }).then(function (m) {
            __comuniMap = m || {};
            // deep-link ?q=ivrea: la query puo' essere arrivata PRIMA della mappa → riapplica
            if (textQuery) { renderGrid(applyTextFilter(lastResults)); updateShownCount(); renderFeatured(lastResults); }
        }).catch(function () { __comuniMap = {}; });
    }
    // Trasforma i token: sequenze fino a 3 parole che sono un comune diventano un vincolo
    // di provincia. Token singolo che e' ANCHE un comune → vale comune OPPURE testo (es. "vita" TP).
    function expandComuni(toks) {
        var out = [], i = 0;
        while (i < toks.length) {
            var hit = null, len = 0;
            if (__comuniMap) {
                for (var L = 3; L >= 1; L--) {
                    if (i + L <= toks.length) {
                        var key = toks.slice(i, i + L).join(' ');
                        if (__comuniMap[key]) { hit = __comuniMap[key]; len = L; break; }
                    }
                }
            }
            if (hit) { out.push({ t: len === 1 ? toks[i] : null, prov: hit.split(' ') }); i += len; }
            else { out.push({ t: toks[i], prov: null }); i++; }
        }
        return out;
    }
    function applyTextFilter(crews) {
        if (!textQuery) return crews;
        // 2026-08-11 v2.14 — query multi-parola in AND (feedback Marco: "truccatrice milano"):
        // ogni parola deve stare da qualche parte nel testo della card, in qualsiasi ordine
        var toks = normTxt(textQuery).split(/\s+/).filter(Boolean);
        if (!toks.length) return crews;
        var parts = expandComuni(toks); // v2.16
        var catLabels = cfg.catLabels || {};
        return crews.filter(function (c) {
            var sigla = String(c.provincia || '').toUpperCase().trim();
            var hay = [c.nome, c.uuid_short, c.provincia, provName(c.provincia), c.paese,
                       PROV_REGION[sigla] || '', PROV_EXONYMS[sigla] || ''] // v2.15 — regione+esonimi
                .concat((c.categorie || []).map(function (cat) { return (catLabels[cat] || cat) + ' ' + (CAT_KEYWORDS[cat] || ''); }));
            var hayN = normTxt(hay.join(' '));
            return parts.every(function (p) {
                if (p.prov && p.prov.indexOf(sigla) !== -1) return true;      // comune → provincia
                return p.t ? hayN.indexOf(p.t) !== -1 : false;                // testo normale
            });
        });
    }
    function updateShownCount() {
        var shown = document.querySelectorAll('#crew-grid .crew-pub-card').length;
        $('#results-count').textContent = shown + ' ' + (STR.resultsLabel || 'crew');
    }
    // 2026-08-11 v2.12 — griglia vuota: se c'e' una ricerca attiva, messaggio col termine cercato
    // (escape PRIMA del replace: il termine utente finisce in <strong> gia' sanificato)
    function emptyMsg() {
        if (textQuery && STR.emptySearch) {
            return escapeHtml(STR.emptySearch).replace('%s', '<strong>' + escapeHtml(textQuery) + '</strong>');
        }
        return escapeHtml(STR.empty || 'Nessun crew.');
    }
    // 2026-08-11 v2.9 — home editoriale: strisce "In evidenza" per le 3-4 categorie con piu' crew
    // (min 2 crew con media, 'altro' escluso perche' non editoriale), top 8 a striscia — l'ordine
    // per voto staff arriva GIA' dal CRM, qui si prende solo la testa. Visibili SOLO senza filtri
    // ne' ricerca attivi: con un filtro la pagina torna catalogo puro.
    function renderFeatured(crews) {
        var wrap = $('#crewFeatured');
        if (!wrap) return;
        var allTitle = $('#crewAllTitle');
        var provEl = $('#filter-provincia');
        var hasFilter = !!(textQuery || $('#filter-categoria').value || $('#filter-paese').value || (provEl && provEl.value));
        if (hasFilter) {
            wrap.style.display = 'none';
            wrap.innerHTML = '';
            if (allTitle) allTitle.style.display = 'none';
            return;
        }
        var byCat = {};
        (crews || []).forEach(function (c) {
            if (c.n_foto != null && ((c.n_foto | 0) + (c.n_video | 0)) === 0) return;
            (c.categorie || []).forEach(function (cat) {
                (byCat[cat] = byCat[cat] || []).push(c);
            });
        });
        var top = Object.keys(byCat)
            .filter(function (k) { return k !== 'altro' && byCat[k].length >= 2; })
            .sort(function (a, b) { return byCat[b].length - byCat[a].length; })
            .slice(0, 4);
        wrap.innerHTML = '';
        if (!top.length) {
            wrap.style.display = 'none';
            if (allTitle) allTitle.style.display = 'none';
            return;
        }
        var catLabels = cfg.catLabels || {};
        // 2026-08-11 v2.10 — feedback Marco: niente profili ripetuti tra le strisce. Ogni crew
        // appare in UNA sola striscia (viene assegnato alla sua categoria piu' numerosa, che
        // essendo top ordinata viene processata prima); striscia sotto 2 card dopo il dedup → via.
        var used = {};
        top.forEach(function (cat) {
            var row = document.createElement('div');
            row.className = 'crew-feat-row';
            var added = 0;
            byCat[cat].forEach(function (c) {
                if (added >= 8 || used[c.uuid]) return;
                var card = buildCard(c);
                if (card) { row.appendChild(card); used[c.uuid] = 1; added++; }
            });
            if (added < 2) return;
            var sec = document.createElement('section');
            sec.className = 'crew-feat-sec';
            var head = document.createElement('div');
            head.className = 'crew-feat-head';
            var h = document.createElement('h2');
            h.className = 'crew-feat-title';
            h.textContent = catLabels[cat] || cat;
            head.appendChild(h);
            var all = document.createElement('button');
            all.type = 'button';
            all.className = 'crew-feat-all';
            all.dataset.cat = cat;
            all.textContent = (STR.featuredAll || 'Vedi tutti') + ' →';
            head.appendChild(all);
            sec.appendChild(head);
            sec.appendChild(row);
            wrap.appendChild(sec);
        });
        // dopo il dedup potrebbe non restare nessuna striscia valida
        if (!wrap.children.length) {
            wrap.style.display = 'none';
            if (allTitle) allTitle.style.display = 'none';
            return;
        }
        wrap.style.display = '';
        if (allTitle) allTitle.style.display = '';
    }
    // 2026-07-26 — cache "cover" provvisoria (uuid -> array media), evita refetch ad ogni cambio filtro
    var __coverCache = {};
    // 2026-08-10 v2.7 — mappa poster JPG -> URL video originale (per l'hover-to-play sulle card)
    var __videoByPoster = {};
    // 2026-07-31 — le richieste "cover random" partivano TUTTE insieme al render della griglia (~20+
    // in parallelo), rallentando anche l'apertura di una scheda profilo in corso (feedback Marco).
    // Con IntersectionObserver si caricano solo le card che stanno per entrare in vista.
    var coverObserver = ('IntersectionObserver' in window) ? new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) return;
            coverObserver.unobserve(entry.target);
            var card = entry.target;
            var photo = card.querySelector('.crew-pub-photo');
            if (photo) loadRandomCover(card, photo, card.dataset.uuid);
        });
    }, { rootMargin: '400px 0px' }) : null;

    function $(sel) { return document.querySelector(sel); }

    function syncProvinceVisibility() {
        var paeseEl = document.querySelector('#filter-paese'), prov = document.querySelector('#filter-provincia');
        if (!paeseEl || !prov) return;
        var show = (paeseEl.value === '' || paeseEl.value === 'IT');
        prov.style.display = show ? '' : 'none';
        if (!show) prov.value = '';
    }

    function populateProvinceFilter() {
        var sel = document.querySelector('#filter-provincia');
        if (!sel || !cfg.provinceJsonUrl) return;
        fetch(cfg.provinceJsonUrl).then(function (r) { return r.json(); }).then(function (list) {
            (list || []).forEach(function (p) {
                var o = document.createElement('option');
                o.value = p.name;
                o.textContent = p.name + (p.code ? ' (' + p.code + ')' : '');
                sel.appendChild(o);
            });
            // 2026-08-11 v2.11 — provincia da deep-link: ora che le opzioni ci sono, applica e ricarica
            if (__initProv) {
                sel.value = __initProv;
                if (sel.value === __initProv) sel.dispatchEvent(new Event('change'));
                __initProv = '';
            }
        }).catch(function () {});
    }

    function loadCrews() {
        var provEl = $('#filter-provincia');
        var body = {
            categoria: $('#filter-categoria').value,
            paese:     $('#filter-paese').value,
            provincia: provEl ? provEl.value : ''
        };
        $('#results-count').textContent = '…';
        syncUrl(); // v2.11 — filtri correnti riflessi nell'URL (condivisibile)
        // 2026-07-31 — return la promise: serve al deep-link (?uuid=) per aspettare che lastResults
        // sia popolato PRIMA di aprire il profilo, altrimenti l'avatar (che sta solo nella griglia,
        // non nell'endpoint profilo) risulta sempre vuoto in apertura diretta da link (bug pre-esistente).
        return fetch(API_SEARCH, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.success) {
                $('#crew-grid').innerHTML = '<div class="crew-pub-empty">Errore caricamento.</div>';
                $('#results-count').textContent = '—';
                return;
            }
            lastResults = d.results || [];
            // v2.8 — la ricerca testuale (se attiva) si riapplica anche dopo un cambio filtro
            renderGrid(applyTextFilter(lastResults));
            // v2.6 — conta le card davvero renderizzate (skip n_foto+n_video=0), non tutti i results
            updateShownCount();
            // v2.9 — strisce "In evidenza" (si nascondono da sole se c'e' un filtro attivo)
            renderFeatured(lastResults);
            setTimeout(sweepMissingCovers, 3000); // 2026-08-01 — vedi sweepMissingCovers
        })
        .catch(function (err) {
            console.error('[crew-pub] load error:', err);
            $('#crew-grid').innerHTML = '<div class="crew-pub-empty">Errore rete.</div>';
            $('#results-count').textContent = '—';
        });
    }

    function renderGrid(crews) {
        var grid = $('#crew-grid');
        if (!crews.length) {
            grid.innerHTML = '<div class="crew-pub-empty">' + emptyMsg() + '</div>'; // v2.12
            return;
        }
        var frag = document.createDocumentFragment();
        var rendered = 0;
        crews.forEach(function (c) {
            var card = buildCard(c);
            if (!card) return;
            frag.appendChild(card);
            rendered++;
        });
        grid.innerHTML = '';
        // v2.6 — tutti scartati dal filtro n_foto/n_video → stesso messaggio "nessun risultato"
        if (!rendered) {
            grid.innerHTML = '<div class="crew-pub-empty">' + emptyMsg() + '</div>'; // v2.12
            return;
        }
        grid.appendChild(frag);
    }

    // 2026-08-11 v2.9 — card estratta da renderGrid: riusata identica dalla griglia e
    // dalle strisce "In evidenza" della home editoriale (ritorna null se il crew non ha media)
    function buildCard(c) {
        // 2026-08-10 v2.6 — n_foto/n_video dalla search: crew senza alcun media approvato
        // scartato SUBITO (prima: card placeholder + fetch + hide a posteriori). Se i campi
        // mancano (risposta vecchia in cache) si torna al comportamento precedente (fetch+hide).
        if (c.n_foto != null && ((c.n_foto | 0) + (c.n_video | 0)) === 0) return null;
        var card = document.createElement('div');
        card.className = 'crew-pub-card' + (selectedUuids.has(c.uuid) ? ' selected' : '');
        card.dataset.uuid = c.uuid;

        // Foto profilo
        var photo = document.createElement('div');
        photo.className = 'crew-pub-photo';
        // 2026-08-10 v2.2 (feedback Marco) — la foto PROFILO non va MAI nel riquadro grande:
        // è già nell'avatar tondo sotto. Placeholder finché non arriva il portfolio; se il
        // crew non ha foto lavori, resta il placeholder. (Emoji in <span> dedicato: prima
        // photo.textContent='' cancellava anche i child del div — frecce nav, layer fade.)
        var phEmoji = document.createElement('span');
        phEmoji.className = 'crew-pub-ph';
        phEmoji.textContent = '👤';
        photo.appendChild(phEmoji);
        card.appendChild(photo);
        // 2026-07-31 — solo quando la card e' vicina alla vista (vedi coverObserver sopra), non tutte insieme
        if (coverObserver) coverObserver.observe(card); else loadRandomCover(card, photo, c.uuid);
        // 2026-08-10 — traccia la visibilità per il crossfade automatico (autoTick cambia solo card in vista)
        if (autoObserver) autoObserver.observe(card);

        // 2026-07-26 Fase 2 — bottoncino selezione (+/✓) per "Richiedi info", separato dal click-card che ora apre il profilo
        var addBtn = document.createElement('button');
        addBtn.type = 'button';
        addBtn.className = 'crew-pub-add';
        addBtn.setAttribute('aria-label', STR.selectForLead || 'Seleziona per richiesta info');
        addBtn.textContent = selectedUuids.has(c.uuid) ? '✓' : '+';
        addBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggleSelect(c.uuid);
        });
        card.appendChild(addBtn);

        // Body
        var body = document.createElement('div');
        body.className = 'crew-pub-body';

        // 2026-07-26 — riga nome+codice affiancati (come talent): codice MAIUSCOLO, senza #, pill visibile
        var nameRow = document.createElement('div');
        nameRow.className = 'crew-pub-name-row';
        // 2026-07-26 — avatar piccolo (la persona), la foto grande ora e' il lavoro
        var avatar = document.createElement('span');
        avatar.className = 'crew-pub-avatar';
        if (c.foto_profilo_url) avatar.style.backgroundImage = 'url(' + encodeURI(c.foto_profilo_url) + ')';
        nameRow.appendChild(avatar);
        var name = document.createElement('span');
        name.className = 'crew-pub-name';
        name.textContent = c.nome ? properCase(c.nome) : '—';
        nameRow.appendChild(name);
        var codiceRaw = c.uuid_short || (c.uuid ? c.uuid.substring(0, 8) : '');
        if (codiceRaw) {
            var uuid = document.createElement('span');
            uuid.className = 'crew-pub-uuid';
            uuid.textContent = codiceRaw.toUpperCase();
            nameRow.appendChild(uuid);
        }
        body.appendChild(nameRow);

        if (c.categorie && c.categorie.length) {
            var cats = document.createElement('div');
            cats.className = 'crew-pub-categories';
            // 2026-07-26 — testo pulito "Beauty • Hair" con le etichette leggibili invece dei codici grezzi
            var catLabels = cfg.catLabels || {};
            cats.textContent = c.categorie.slice(0, 3).map(function (cat) { return catLabels[cat] || cat; }).join(' • ');
            body.appendChild(cats);
        }

        var meta = document.createElement('div');
        meta.className = 'crew-pub-meta';
        var metaParts = [];
        // 2026-07-31 — "da X anni" accanto al livello (CRM ha aggiunto eta/attivita_dal/pro_dal
        // anche in crew-public-search.php, prima disponibili solo nel profilo)
        if (c.livello) {
            var annoRif = (c.pro_dal != null) ? c.pro_dal : c.attivita_dal;
            var livelloTxt = c.livello.charAt(0).toUpperCase() + c.livello.slice(1);
            if (annoRif != null) {
                var nAnniGrid = new Date().getFullYear() - parseInt(annoRif, 10);
                if (nAnniGrid >= 1) livelloTxt += ' da ' + nAnniGrid + ' anni';
            }
            metaParts.push(livelloTxt);
        }
        // 2026-07-26 — provincia in griglia (privacy: solo provincia, mai comune), ora presente nell'endpoint di ricerca
        if (c.provincia) metaParts.push(provName(String(c.provincia)));
        if (c.paese) metaParts.push(c.paese);
        meta.textContent = metaParts.join(' · ');
        body.appendChild(meta);

        // 2026-07-26 — conteggio lavori (proposta ChatGPT): vuoto finche' non arriva il fetch della cover random
        var projCount = document.createElement('div');
        projCount.className = 'crew-pub-projcount';
        // v2.6 — conteggio immediato dalla search; applyCover poi lo raffina coi media effettivi
        if (c.n_foto != null && ((c.n_foto | 0) + (c.n_video | 0)) > 0) {
            projCount.textContent = ((c.n_foto | 0) + (c.n_video | 0)) + ' ' + (STR.worksCount || 'lavori');
        }
        body.appendChild(projCount);

        // 2026-08-10 CREW-REDESIGN — bottone "Portfolio →" esplicito: il click-card resta attivo,
        // il bottone rende visibile l'azione (il click risale al listener della card, nessun handler suo)
        var pfBtn = document.createElement('button');
        pfBtn.type = 'button';
        pfBtn.className = 'crew-pub-portfolio';
        pfBtn.textContent = (STR.portfolioBtn || 'Portfolio') + ' →';
        body.appendChild(pfBtn);

        card.appendChild(body);
        // 2026-07-26 Fase 2 — click ovunque sulla card apre il profilo (come talent); selezione spostata sul bottoncino +/✓
        card.addEventListener('click', function () { openProfile(c.uuid, false, c.foto_profilo_url); });
        return card;
    }

    function toggleSelect(uuid) {
        if (selectedUuids.has(uuid)) selectedUuids.delete(uuid);
        else selectedUuids.add(uuid);
        syncCardSelectedState(uuid);
        updateActionBar();
    }

    // 2026-07-26 Fase 2 — unico punto che sincronizza classe .selected + testo bottoncino +/✓ (come talent updateCardSelectedState)
    function syncCardSelectedState(uuid) {
        var sel = selectedUuids.has(uuid);
        document.querySelectorAll('.crew-pub-card[data-uuid="' + cssEscape(uuid) + '"]').forEach(function (c) {
            c.classList.toggle('selected', sel);
            var btn = c.querySelector('.crew-pub-add');
            if (btn) btn.textContent = sel ? '✓' : '+';
        });
    }

    function updateActionBar() {
        var n = selectedUuids.size;
        $('#selection-count').textContent = n;
        $('#actionbar').classList.toggle('visible', n > 0);
    }

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c];
        });
    }

    function cssEscape(s) {
        return String(s).replace(/[^a-zA-Z0-9_-]/g, function (c) { return '\\' + c; });
    }

    // ─── Selection actions ─────────────────────────────────────
    window.crewPubClearSelection = function () {
        selectedUuids.clear();
        document.querySelectorAll('.crew-pub-card.selected').forEach(function (c) {
            c.classList.remove('selected');
            var btn = c.querySelector('.crew-pub-add');
            if (btn) btn.textContent = '+';
        });
        updateActionBar();
    };

    // ─── Modal lead ────────────────────────────────────────────
    window.crewPubOpenLeadModal = function () {
        if (!selectedUuids.size) return;
        $('#lead-selection-count').textContent = selectedUuids.size;
        $('#lead-msg-result').innerHTML = '';
        $('#lead-send-btn').disabled = false;
        $('#modal-lead').classList.add('show');
    };

    window.crewPubCloseLeadModal = function () {
        $('#modal-lead').classList.remove('show');
    };

    // Click overlay → chiudi modal
    document.addEventListener('click', function (e) {
        if (e.target.id === 'modal-lead') window.crewPubCloseLeadModal();
    });

    window.crewPubSubmitLead = function () {
        var azienda = $('#lead-azienda').value.trim();
        var email   = $('#lead-email').value.trim();
        var tel     = $('#lead-tel').value.trim();
        var msg     = $('#lead-msg').value.trim();
        var honey   = $('#lead-honeypot').value;

        var resultBox = $('#lead-msg-result');
        resultBox.innerHTML = '';

        if (azienda.length < 2) { showMsg('err', 'Nome azienda obbligatorio'); return; }
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showMsg('err', 'Email non valida'); return; }
        if (msg.length < 10) { showMsg('err', 'Messaggio troppo breve (min 10 caratteri)'); return; }

        $('#lead-send-btn').disabled = true;

        fetch(API_LEAD, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nome_azienda: azienda,
                email: email,
                telefono: tel,
                messaggio: msg,
                crew_uuids: Array.from(selectedUuids),
                honeypot_url: honey
            }),
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.success) {
                showMsg('ok', STR.success || 'Grazie!');
                setTimeout(function () {
                    window.crewPubCloseLeadModal();
                    window.crewPubClearSelection();
                    // Reset form
                    ['lead-azienda','lead-email','lead-tel','lead-msg'].forEach(function(id) { $('#'+id).value = ''; });
                }, 1800);
            } else {
                showMsg('err', (STR.errorPrefix || 'Errore: ') + (d.message || d.error || 'invio fallito'));
                $('#lead-send-btn').disabled = false;
            }
        })
        .catch(function (err) {
            console.error('[crew-pub] lead error:', err);
            showMsg('err', (STR.errorPrefix || 'Errore: ') + 'rete');
            $('#lead-send-btn').disabled = false;
        });
    };

    function showMsg(type, text) {
        var box = $('#lead-msg-result');
        box.innerHTML = '<div class="msg ' + type + '">' + escapeHtml(text) + '</div>';
    }

    // ─── Scheda singola crew (?uuid=) — 2026-07-11 ─────────────
    var VIDEO_RE = /\.(mp4|mov|webm|m4v|ogg)(\?|$)/i;

    // 2026-07-23: lightbox — pfPhotos raccoglie foto+video in ordine di render, {type:'photo'|'video', src}
    // 2026-08-03: unificato foto e video nello stesso elenco per navigazione con le frecce — feedback Marco
    var pfPhotos = [];
    // 2026-08-04: mappa url_video -> url_poster_jpg (chiave `posters` di crew-public-profile.php).
    // Il CRM genera un JPG ~20KB per ogni video: il tile mostra quello invece di scaricare
    // pezzi del .mp4 (media 8MB, max 31MB) solo per avere un fotogramma.
    var pfPosters = {};
    // 2026-07-31: interval dello slideshow hero, va fermato alla chiusura scheda (altrimenti resta a girare a vuoto)
    var heroSlideTimer = null;

    // Aggiunge &w=<w> agli URL proxy crew-photo-public.php (miniatura vs grande)
    function withW(url, w) {
        if (!/crew-photo-public\.php/i.test(url)) return url;
        return url + (url.indexOf('?') >= 0 ? '&' : '?') + 'w=' + w;
    }

    function videoTag(url, idx) {
        var safe = encodeURI(url);
        var poster = pfPosters[url] || '';
        if (poster) {
            // 2026-08-04: con il poster JPG il video non va toccato finche' non si clicca:
            // niente '#t=0.5' (serviva solo a strappare un frame dal video stesso) e
            // preload="none". Il seek in wireVideos e' escluso sui tile con poster.
            return '<button type="button" class="crew-pf-vthumb" data-src="' + safe + '" data-idx="' + idx + '">' +
                '<video class="crew-pf-vpreview" src="' + safe + '" poster="' + encodeURI(poster) + '" preload="none" muted playsinline></video>' +
                '<span class="crew-pf-play">▶</span></button>';
        }
        // 2026-08-03: anteprima reale (primo frame, preload=metadata) al posto del box nero — feedback Marco
        // 2026-08-03: data-idx per navigazione unificata foto+video nel lightbox (frecce) — feedback Marco
        return '<button type="button" class="crew-pf-vthumb" data-src="' + safe + '" data-idx="' + idx + '">' +
            // 2026-08-04: '#t=0.5' — media fragment: il browser mostra il frame a mezzo secondo invece
            // del primo, che su alcuni video e' nero (tile nero segnalato da Marco). Il proxy CRM
            // supporta le range request (206 + accept-ranges verificato 04/08), quindi scarica solo
            // il pezzetto necessario. Nel lightbox il video parte comunque da 0.
            '<video class="crew-pf-vpreview" src="' + safe + '#t=0.5" preload="metadata" muted playsinline></video>' +
            '<span class="crew-pf-play">▶</span></button>';
    }

    var currentProfileUuid = null;

    // 2026-07-26 — l'endpoint profilo non restituisce la foto avatar (solo il search/griglia ce l'ha),
    // best-effort: cerca in lastResults (griglia gia' caricata) se non passata direttamente dal click sulla card
    function findAvatarByUuid(uuid) {
        for (var i = 0; i < lastResults.length; i++) {
            if (lastResults[i].uuid === uuid) return lastResults[i].foto_profilo_url || '';
        }
        return '';
    }

    function openProfile(uuid, fromPop, avatarUrl) {
        if (!uuid) return;
        if (typeof stopAllHoverVideos === 'function') stopAllHoverVideos(); // v2.7 — mai video card sotto l'overlay
        currentProfileUuid = uuid;
        var ov = $('#crew-profile-overlay');
        var body = $('#crew-profile-body');
        if (!ov || !body) return;
        body.innerHTML = '<div class="crew-pf-loading">' + escapeHtml(STR.loadingProfile || 'Carico…') + '</div>';
        ov.classList.add('show');
        document.body.style.overflow = 'hidden';
        if (!fromPop) {
            var u = new URL(window.location.href);
            u.searchParams.set('uuid', uuid);
            window.history.pushState({ crewProfile: uuid }, '', u.toString());
        }
        var avatar = avatarUrl || findAvatarByUuid(uuid);
        // 2026-08-01 — bio nella lingua corrente del sito (task #17), il CRM traduce/uniforma via AI in "bio"
        fetch(API_PROFILE + '?uuid=' + encodeURIComponent(uuid) + '&lang=' + encodeURIComponent(cfg.lang || 'it'), { credentials: 'same-origin' })
            .then(function (r) { if (!r.ok) throw new Error('http ' + r.status); return r.json(); })
            .then(function (d) { renderProfile(d, avatar); })
            .catch(function (err) {
                console.error('[crew-pub] profile error:', err);
                body.innerHTML = '<div class="crew-pf-error">' + escapeHtml(STR.errorProfile || 'Profilo non disponibile.') + '</div>';
            });
    }

    function renderProfile(d, avatarUrl) {
        var body = $('#crew-profile-body');
        if (!body) return;
        pfPhotos = [];
        var labels = d.ruoli_label || {};
        var albums = d.albums || {};
        // 2026-08-04: se il CRM non manda `posters` resta {} e i tile tornano al vecchio comportamento
        pfPosters = d.posters || {};
        var bio = d.bio_ruoli || {};
        var codice = d.codice ? '<span class="crew-pf-code">· ' + escapeHtml(d.codice) + '</span>' : '';
        // 2026-07-31 — Hero slideshow (richiesta Marco, sostituisce il mosaico statico: le foto restano
        // grandi/cliccabili nella galleria sotto invece di essere "consumate" dall'hero). Fino a 5 foto
        // del portfolio (cover_url scelta dal crew per prima, se presente), dissolvenza + zoom via CSS,
        // ciclo gestito da un piccolo interval JS (vedi wireHeroSlideshow), leggero: solo opacity/transform.
        var heroPhotos = [];
        if (d.cover_url) heroPhotos.push(d.cover_url);
        var heroKeys = Object.keys(albums).filter(function (k) { return k !== 'generale'; });
        if (albums.generale) heroKeys.push('generale');
        heroKeys.forEach(function (k) {
            (albums[k] || []).forEach(function (p) {
                if (heroPhotos.length < 6 && !VIDEO_RE.test(p) && heroPhotos.indexOf(p) === -1) heroPhotos.push(p);
            });
        });
        var html = '';
        if (heroPhotos.length) {
            // 2026-07-31 — dittico: 2 foto affiancate per "slide" invece di 1 sola a piena larghezza.
            // Con una foto verticale sola in un riquadro largo/basso il crop era assurdo (feedback Marco:
            // "vedo una narice gigante"); a coppie ogni foto occupa meta' larghezza, molto piu' equilibrato.
            var pairs = [];
            for (var hp = 0; hp < heroPhotos.length; hp += 2) pairs.push(heroPhotos.slice(hp, hp + 2));
            var slides = pairs.map(function (pair, hi) {
                var imgs = pair.map(function (p) { return '<img src="' + encodeURI(withW(p, 800)) + '" alt="">'; }).join('');
                return '<div class="crew-pf-hero-slide' + (hi === 0 ? ' is-active' : '') + '">' + imgs + '</div>';
            });
            html += '<div class="crew-pf-hero"><div class="crew-pf-hero-slideshow">' + slides.join('') + '</div>'
                 +  '<div class="crew-pf-hero-overlay"><h2 class="crew-pf-hero-name">' + escapeHtml(d.nome ? properCase(d.nome) : '—') + codice + '</h2></div></div>';
        }
        html += '<div class="crew-pf-header">';
        // 2026-07-26 — layout affiancato: avatar a sinistra, info a destra (richiesto da Marco per ottimizzare lo spazio)
        html += '<div class="crew-pf-headrow">';
        if (avatarUrl) html += '<span class="crew-pf-avatar" style="background-image:url(' + escapeHtml(encodeURI(avatarUrl)) + ')"></span>';
        html += '<div class="crew-pf-headinfo">';
        if (!heroPhotos.length) html += '<h2 class="crew-pf-name">' + escapeHtml(d.nome ? properCase(d.nome) : '—') + codice + '</h2>';
        if (d.categorie && d.categorie.length) {
            html += '<div class="crew-pf-roles">';
            d.categorie.forEach(function (cat) { html += '<span class="crew-pf-chip">' + escapeHtml(cat) + '</span>'; });
            html += '</div>';
        }
        // 2026-07-26 — specializzazioni per ruolo (album_temi, CRM: endpoint crew-self-edit-temi.php).
        // Profilo-level, non foto: es. Fotografo -> Wedding, Beauty. Vuoto finche' il crew non le imposta nel self-edit (non ancora costruito).
        if (d.temi) {
            Object.keys(d.temi).forEach(function (ruolo) {
                var vals = d.temi[ruolo];
                if (vals && vals.length) {
                    var ruoloLabel = labels[ruolo] || ruolo;
                    html += '<div class="crew-pf-temi"><strong>' + escapeHtml(ruoloLabel) + ':</strong> ' + escapeHtml(vals.join(', ')) + '</div>';
                }
            });
        }
        // Privacy: SOLO provincia (mai il comune di residenza/domicilio); paese solo se non IT
        var loc = d.provincia ? provName(String(d.provincia)) : '';
        if (d.paese && d.paese !== 'IT') loc = loc ? (loc + ' · ' + d.paese) : String(d.paese);
        if (loc) html += '<div class="crew-pf-loc">📍 ' + escapeHtml(loc) + '</div>';
        // 2026-08-02 — livello/esperienza PER RUOLO (task #18): se il crew ha 2+ ruoli e almeno uno ha
        // ruoli_dati compilato (self-edit), mostra una riga per ruolo invece del badge unico sotto.
        // Feedback Marco su Valentina: "make up artist e hairstylist ma non so l'esperienza di ognuna".
        var ruoliDati = d.ruoli_dati || {};
        var ruoliConDati = Object.keys(ruoliDati).filter(function (r) { return ruoliDati[r] && ruoliDati[r].livello; });
        if (d.categorie && d.categorie.length >= 2 && ruoliConDati.length) {
            html += '<div class="crew-pf-ruoli-livello">';
            ruoliConDati.forEach(function (ruolo) {
                var rd = ruoliDati[ruolo];
                var roleLabel = labels[ruolo] || ruolo;
                var livTxt = rd.livello.charAt(0).toUpperCase() + rd.livello.slice(1);
                if (rd.anni != null && rd.anni >= 1) livTxt += ' · ' + rd.anni + ' ' + (STR.yearsLabel || 'anni');
                html += '<div class="crew-pf-ruolo-livello"><strong>' + escapeHtml(roleLabel) + ':</strong> <span>' + escapeHtml(livTxt) + '</span></div>';
            });
            html += '</div>';
        } else if (d.livello) {
            // 2026-07-31 — badge singolo (invariato): crew con 1 ruolo o senza ruoli_dati ancora compilato
            var livelloCap = d.livello.charAt(0).toUpperCase() + d.livello.slice(1);
            html += '<div class="crew-pf-livello">' + escapeHtml(livelloCap) + '</div>';
        }
        // Età + anzianità (2026-07-23) — privacy: solo numeri dall'endpoint (mai data_nascita/P.IVA)
        var yNow = new Date().getFullYear();
        var senParts = [];
        if (d.eta != null && d.eta > 0) {
            senParts.push('🎂 ' + escapeHtml(String(d.eta)) + ' ' + escapeHtml(STR.ageSuffix || 'anni'));
        }
        if (d.attivita_dal != null) {
            var nAtt = yNow - parseInt(d.attivita_dal, 10);
            if (nAtt >= 1) senParts.push(escapeHtml(STR.sinceLabel || 'Nel settore da') + ' ' + nAtt + ' ' + escapeHtml(STR.yearsLabel || 'anni'));
        }
        if (d.pro_dal != null) {
            var nPro = yNow - parseInt(d.pro_dal, 10);
            if (nPro >= 1) senParts.push(escapeHtml(STR.proLabel || 'professionista da') + ' ' + nPro + ' ' + escapeHtml(STR.yearsLabel || 'anni'));
        }
        if (senParts.length) html += '<div class="crew-pf-seniority">' + senParts.join(' · ') + '</div>';
        html += '</div>'; // .crew-pf-headinfo
        html += '</div>'; // .crew-pf-headrow
        html += '</div>';
        html += '<button type="button" class="crew-pf-cta" onclick="crewPfRequestInfo()">' + escapeHtml(STR.requestInfo || '📧 Richiedi info') + '</button>';
        html += '<p class="crew-pf-intro"' + (d.bio ? '' : ' style="opacity:.5;font-style:italic;"') + '>' + escapeHtml(d.bio || (STR.bioPlaceholder || 'Bio in aggiornamento.')) + '</p>';
        var keys = Object.keys(albums).filter(function (k) { return k !== 'generale'; });
        if (albums.generale) keys.push('generale');
        var any = false;
        keys.forEach(function (k) {
            var photos = albums[k] || [];
            var hasBio = (k !== 'generale' && bio[k]);
            if (!photos.length && !hasBio) return;
            any = true;
            var title = (k === 'generale') ? (STR.generalAlbum || 'Generale') : (labels[k] || k);
            html += '<section class="crew-pf-album"><div class="crew-pf-album-head"><h3 class="crew-pf-album-title">' + escapeHtml(title) + '</h3><span class="crew-pf-count">' + photos.length + '</span></div>';
            if (hasBio) html += '<p class="crew-pf-bio">' + escapeHtml(bio[k]) + '</p>';
            if (photos.length) {
                html += '<div class="crew-pf-grid">';
                photos.forEach(function (url) {
                    var idx = pfPhotos.length;
                    if (VIDEO_RE.test(url)) {
                        pfPhotos.push({ type: 'video', src: encodeURI(url) });
                        html += videoTag(url, idx);
                        return;
                    }
                    pfPhotos.push({ type: 'photo', src: withW(url, 1600) });
                    html += '<img class="crew-pf-media crew-pf-clic" src="' + encodeURI(withW(url, 600)) + '" alt="" loading="lazy" data-idx="' + idx + '">';
                });
                html += '</div>';
            }
            html += '</section>';
        });
        if (!any) html += '<div class="crew-pf-empty">' + escapeHtml(STR.noMedia || 'Nessun contenuto disponibile.') + '</div>';
        body.innerHTML = html;
        body.scrollTop = 0;
        wireVideos(body);
        wireHeroSlideshow(body);
    }

    // 2026-07-31 — ciclo dissolvenza tra le foto dell'hero (max 5, gia' in DOM). Solo opacity/transform
    // (leggero), un solo timer alla volta: se una scheda si riapre il vecchio timer va fermato prima.
    function wireHeroSlideshow(scope) {
        if (heroSlideTimer) { clearInterval(heroSlideTimer); heroSlideTimer = null; }
        var slides = scope.querySelectorAll('.crew-pf-hero-slide');
        if (slides.length < 2) return;
        var idx = 0;
        heroSlideTimer = setInterval(function () {
            slides[idx].classList.remove('is-active');
            idx = (idx + 1) % slides.length;
            slides[idx].classList.add('is-active');
        }, 4000);
    }

    // 2026-08-03: click sul tile video → ingrandisce nel lightbox (come le foto), invece di
    // riprodurre dentro il tile piccolo — feedback Marco "manca la possibilita' di ingrandire"
    function wireVideos(scope) {
        // preload="metadata" da solo non basta: il browser non disegna il primo frame finche'
        // non c'e' un piccolo seek (verificato in test live) — altrimenti resta nero
        // 2026-08-04: SOLO i tile senza poster — sul tile con poster il seek riaprirebbe il
        // download del video, annullando il preload="none"
        scope.querySelectorAll('.crew-pf-vpreview:not([poster])').forEach(function (v) {
            function seekFrame() { try { v.currentTime = 0.1; } catch (e) {} }
            if (v.readyState >= 1) seekFrame();
            else v.addEventListener('loadedmetadata', seekFrame, { once: true });
        });
        scope.querySelectorAll('.crew-pf-vthumb').forEach(function (b) {
            b.addEventListener('click', function () {
                // 2026-08-03: usa lbOpen(idx) come le foto, cosi' le frecce scorrono foto+video insieme
                var i = parseInt(b.getAttribute('data-idx'), 10);
                if (!isNaN(i)) lbOpen(i);
            });
        });
    }

    function hideProfile() {
        var ov = $('#crew-profile-overlay');
        if (ov) ov.classList.remove('show');
        document.body.style.overflow = '';
        if (heroSlideTimer) { clearInterval(heroSlideTimer); heroSlideTimer = null; }
    }

    window.crewPubCloseProfile = function () {
        hideProfile();
        var u = new URL(window.location.href);
        if (u.searchParams.has('uuid')) {
            u.searchParams.delete('uuid');
            window.history.replaceState({}, '', u.pathname + (u.search ? u.search : ''));
        }
    };

    document.addEventListener('click', function (e) {
        if (e.target && e.target.id === 'crew-profile-overlay') window.crewPubCloseProfile();
    });
    document.addEventListener('keydown', function (e) {
        var lb = $('#crew-lightbox');
        if (lb && lb.classList.contains('show')) {
            if (e.key === 'Escape') { lbClose(); return; }
            if (e.key === 'ArrowRight') { lbNext(); return; }
            if (e.key === 'ArrowLeft') { lbPrev(); return; }
            return;
        }
        if (e.key === 'Escape') { var ov = $('#crew-profile-overlay'); if (ov && ov.classList.contains('show')) window.crewPubCloseProfile(); }
    });

    // ─── Lightbox foto+video (2026-07-23, unificato 2026-08-03) ────────────────────────────
    var lbIdx = 0;
    // 2026-08-03: un solo elenco pfPhotos con {type, src} — le frecce scorrono foto e video insieme,
    // mostra <img> o <video> a seconda dell'item — feedback Marco
    function lbShow() {
        var lb = $('#crew-lightbox'), img = $('#crew-lb-img'), vid = $('#crew-lb-video');
        if (!lb || !img || !vid || !pfPhotos.length) return;
        if (lbIdx < 0) lbIdx = pfPhotos.length - 1;
        if (lbIdx >= pfPhotos.length) lbIdx = 0;
        var item = pfPhotos[lbIdx];
        var isVideo = item.type === 'video';
        lb.classList.toggle('is-video', isVideo);
        if (isVideo) {
            img.src = ''; img.style.display = 'none';
            vid.style.display = 'block'; // la classe .crew-lb-video ha display:none di default, va sovrascritto
            vid.src = item.src;
            vid.play().catch(function () {});
        } else {
            vid.pause(); vid.removeAttribute('src'); vid.load(); vid.style.display = 'none';
            img.style.display = '';
            img.src = item.src;
        }
        var c = $('#crew-lb-counter'); if (c) c.textContent = (lbIdx + 1) + ' / ' + pfPhotos.length;
        lb.classList.add('show'); lb.setAttribute('aria-hidden', 'false');
    }
    function lbOpen(i) { lbIdx = i; lbShow(); }
    function lbClose() {
        var lb = $('#crew-lightbox');
        if (lb) {
            lb.classList.remove('show', 'is-video'); lb.setAttribute('aria-hidden', 'true');
            var img = $('#crew-lb-img'); if (img) { img.src = ''; img.style.display = ''; }
            var vid = $('#crew-lb-video'); if (vid) { vid.pause(); vid.removeAttribute('src'); vid.load(); vid.style.display = 'none'; }
        }
    }
    function lbNext() { lbIdx++; lbShow(); }
    function lbPrev() { lbIdx--; lbShow(); }

    // Click su miniatura foto → apri lightbox (event delegation)
    document.addEventListener('click', function (e) {
        var t = e.target;
        if (t && t.classList && t.classList.contains('crew-pf-clic')) {
            var i = parseInt(t.getAttribute('data-idx'), 10);
            if (!isNaN(i)) lbOpen(i);
        }
    });

    // CTA nella scheda: seleziona questo crew e apri il modal "Richiedi info"
    window.crewPfRequestInfo = function () {
        if (currentProfileUuid) { selectedUuids.add(currentProfileUuid); updateActionBar(); }
        window.crewPubOpenLeadModal();
    };
    window.addEventListener('popstate', function () {
        var uuid = new URLSearchParams(window.location.search).get('uuid');
        if (uuid) openProfile(uuid, true); else hideProfile();
    });

    // ─── Fase 3 (2026-07-26) — frecce hover per scorrere le foto lavori sulla card ────
    // Pattern identico a talent-database-v75.js: bottoni creati al pointerover (lazy DOM),
    // foto scaricate solo al 1° click sulla freccia (lazy fetch, niente autoplay).
    function ensureCardNav(card) {
        if (!card || card.querySelector('.crew-pub-nav')) return;
        var photo = card.querySelector('.crew-pub-photo');
        if (!photo) return;
        ['-1', '1'].forEach(function (dir) {
            var b = document.createElement('button');
            b.type = 'button';
            b.className = 'crew-pub-nav ' + (dir === '-1' ? 'crew-pub-nav-prev' : 'crew-pub-nav-next');
            b.setAttribute('data-cardnav', dir);
            b.setAttribute('aria-label', dir === '-1' ? 'Foto precedente' : 'Foto successiva');
            b.textContent = dir === '-1' ? '‹' : '›';
            photo.appendChild(b);
        });
    }
    document.addEventListener('pointerover', function (e) {
        var card = e.target.closest && e.target.closest('.crew-pub-card');
        if (card) { ensureCardNav(card); startHoverVideo(card); } // v2.7 — hover: frecce + video
    }, true);

    // 2026-07-26 — cover card (era foto casuale in attesa del campo cover_url dal CRM).
    // 2026-08-10 CREW-REDESIGN: crew-public-profile.php restituisce GIÀ cover_url (scelta dal crew
    // nel self-edit): va in testa alla lista e la card parte sempre da quella. Fallback invariato:
    // prima foto portfolio → foto profilo. Riusa la stessa cache (card._cnMedia) delle frecce Fase 3.
    function loadRandomCover(card, photo, uuid) {
        if (__coverCache[uuid]) {
            applyCover(card, photo, __coverCache[uuid]);
            return;
        }
        // v2.5 — ritorna la promise: serve a sweepMissingCovers per limitare i fetch concorrenti
        return fetch(API_PROFILE + '?uuid=' + encodeURIComponent(uuid), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                __coverCache[uuid] = coverFirstMedia(d);
                applyCover(card, photo, __coverCache[uuid]);
            })
            .catch(function () { /* silenzioso: resta il placeholder come fallback */ });
    }
    // Lista media con la cover scelta dal crew per prima (dedup se già presente negli album)
    function coverFirstMedia(d) {
        var media = cardMediaFromAlbums(d);
        if (d && d.cover_url) {
            var cov = withW(d.cover_url, 600);
            var pos = media.indexOf(cov);
            if (pos > 0) media.splice(pos, 1);
            if (pos !== 0) media.unshift(cov);
        }
        return media;
    }
    // 2026-08-11 v2.12 — badge ▶ se l'immagine mostrata è il poster di un video (feedback Marco:
    // "difficile capire che sono video"). Lazy: creato solo quando serve; pointer-events:none.
    function syncPlayBadge(photo, url) {
        var b = photo.querySelector('.crew-pub-playbadge');
        var isVideo = !!__videoByPoster[url];
        if (isVideo && !b) {
            b = document.createElement('span');
            b.className = 'crew-pub-playbadge';
            b.textContent = '▶';
            photo.appendChild(b);
        }
        if (b) b.style.display = isVideo ? '' : 'none';
    }
    function applyCover(card, photo, media) {
        // 2026-07-26 — conteggio lavori, sempre (anche con 0 o 1 foto: se 0 il div resta vuoto)
        var projCount = card.querySelector('.crew-pub-projcount');
        if (projCount && media && media.length) {
            projCount.textContent = media.length + ' ' + (STR.worksCount || 'lavori');
        }
        if (!media || !media.length) { hideEmptyCard(card); return; } // v2.4 — niente media = card nascosta
        photo.style.backgroundImage = 'url(' + encodeURI(media[0]) + ')';
        var phEmoji = photo.querySelector('.crew-pub-ph');
        if (phEmoji) phEmoji.remove(); // solo il placeholder, non gli altri child (frecce, fade)
        card._cnMedia = media;
        card.setAttribute('data-cnidx', 0);
        syncPlayBadge(photo, media[0]); // v2.12
        ensureAutoTimer();
    }

    // ─── 2026-08-10 CREW-REDESIGN — crossfade automatico sulla card ─────────────
    // Un solo interval globale (1s) per tutte le card: ogni card ha la sua scadenza casuale
    // (5–9s) così i cambi sono sfalsati, mai tutti insieme. Cambia solo se la card è in
    // viewport (autoObserver) e non in hover (per non litigare con le frecce).
    // prefers-reduced-motion → autoplay disattivato (restano cover scelta + frecce).
    var AUTOPLAY = !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
    var AUTO_MIN = 1200, AUTO_MAX = 2400; // v2.3 — raddoppiata di nuovo (feedback Marco); v2.2 era 2500/4500
    var autoTimer = null;
    var autoObserver = ('IntersectionObserver' in window) ? new IntersectionObserver(function (entries) {
        entries.forEach(function (en) { en.target._cnVisible = en.isIntersecting; });
    }, { rootMargin: '100px 0px' }) : null;
    function ensureAutoTimer() {
        if (!AUTOPLAY || autoTimer) return;
        autoTimer = setInterval(autoTick, 1000);
    }
    function autoTick() {
        var now = Date.now();
        document.querySelectorAll('.crew-pub-card').forEach(function (card) {
            var media = card._cnMedia;
            if (!media || media.length < 2) return;
            if (card._cnVisible === false) return;
            if (card._cnNext == null) { card._cnNext = now + 400 + Math.random() * 1200; return; }
            if (now < card._cnNext) return;
            if (card.matches(':hover')) { card._cnNext = now + 1500; return; }
            var photo = card.querySelector('.crew-pub-photo');
            if (!photo) return;
            var idx = (parseInt(card.getAttribute('data-cnidx') || '0', 10) + 1) % media.length;
            card.setAttribute('data-cnidx', idx);
            fadeToImage(photo, media[idx]);
            card._cnNext = now + AUTO_MIN + Math.random() * (AUTO_MAX - AUTO_MIN);
        });
    }
    // Dissolvenza: layer .crew-pub-fade sopra la foto — la nuova immagine fa fade-in solo DOPO
    // essere stata decodificata (niente sfarfallio), poi diventa lo sfondo base e il layer si resetta.
    function fadeToImage(photo, url) {
        var fade = photo.querySelector('.crew-pub-fade');
        if (!fade) {
            fade = document.createElement('div');
            fade.className = 'crew-pub-fade';
            photo.appendChild(fade);
        }
        var im = new Image();
        im.onload = function () {
            fade.style.transition = 'none';
            fade.style.opacity = '0';
            fade.style.backgroundImage = 'url(' + encodeURI(url) + ')';
            void fade.offsetWidth; // forza il reflow: la transition riparte pulita da 0
            fade.style.transition = 'opacity .5s ease'; // v2.3 — era .7s
            fade.style.opacity = '1';
            syncPlayBadge(photo, url); // v2.12 — il badge segue l'immagine in arrivo
            setTimeout(function () {
                photo.style.backgroundImage = 'url(' + encodeURI(url) + ')';
                fade.style.transition = 'none';
                fade.style.opacity = '0';
            }, 600);
        };
        im.src = url;
    }

    // ─── 2026-08-10 v2.7 — hover-to-play video sulla card (solo desktop) ─────────
    // Zero consumi di default: il <video> viene creato SOLO al passaggio del mouse (preload
    // avviene lì) e distrutto all'uscita. Su mobile/touch non parte mai (resta il poster;
    // il movimento su mobile arriverà con le clip ottimizzate lato CRM, fase C).
    var HOVER_OK = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    function startHoverVideo(card) {
        if (!HOVER_OK || card._cnHoverVid) return;
        var photo = card.querySelector('.crew-pub-photo');
        var media = card._cnMedia;
        if (!photo || !media || !media.length) return;
        var idx = parseInt(card.getAttribute('data-cnidx') || '0', 10);
        var posterUrl = media[idx];
        var vurl = __videoByPoster[posterUrl];
        // se l'immagine corrente non e' un poster video, usa il primo video del crew (se esiste)
        for (var i = 0; i < media.length && !vurl; i++) { vurl = __videoByPoster[media[i]]; if (vurl) posterUrl = media[i]; }
        if (!vurl) return;
        var v = document.createElement('video');
        v.className = 'crew-pub-hovervid';
        v.muted = true; v.loop = true; v.playsInline = true; v.preload = 'auto';
        v.poster = encodeURI(posterUrl); // v2.7.1 — poster visibile durante il buffering (niente card nera)
        v.src = encodeURI(vurl);
        photo.appendChild(v);
        card._cnHoverVid = v;
        v.play().catch(function () {});
    }
    function stopHoverVideo(card) {
        var v = card._cnHoverVid;
        if (!v) return;
        card._cnHoverVid = null;
        try { v.pause(); } catch (e) {}
        v.removeAttribute('src'); v.load(); // interrompe anche il download in corso
        if (v.parentNode) v.parentNode.removeChild(v);
    }
    function stopAllHoverVideos() {
        document.querySelectorAll('.crew-pub-card').forEach(stopHoverVideo);
    }
    document.addEventListener('pointerout', function (e) {
        var card = e.target.closest && e.target.closest('.crew-pub-card');
        if (!card || !card._cnHoverVid) return;
        if (e.relatedTarget && card.contains(e.relatedTarget)) return; // ancora dentro la card
        stopHoverVideo(card);
    }, true);

    // 2026-08-01 — rete di sicurezza (bug "immagini nere"/icona omino, segnalato da Marco): alcune card
    // non ricevevano mai la cover random (IntersectionObserver che non scatta o race col render).
    // Qualche secondo dopo il render, ricontrolla le card rimaste senza foto e ritenta.
    function sweepMissingCovers() {
        var pending = [];
        document.querySelectorAll('.crew-pub-card').forEach(function (card) {
            if (card.classList.contains('crew-pub-hidden')) return; // v2.4 — gia' valutata: vuota
            var photo = card.querySelector('.crew-pub-photo');
            if (!photo || card._cnMedia) return;
            if (getComputedStyle(photo).backgroundImage !== 'none') return;
            if (coverObserver) coverObserver.unobserve(card);
            pending.push(card);
        });
        // v2.5 — max 3 fetch alla volta: da quando la card non parte piu' dallo sfondo avatar (v2.2)
        // questa rete di sicurezza copriva TUTTA la griglia in un colpo (~25 fetch paralleli — lo
        // stesso problema che l'IntersectionObserver del 31/07 doveva evitare). Coda con 3 slot.
        var active = 0;
        function pump() {
            while (active < 3 && pending.length) {
                (function (card) {
                    var photo = card.querySelector('.crew-pub-photo');
                    active++;
                    var done = function () { active--; pump(); };
                    var p = loadRandomCover(card, photo, card.dataset.uuid);
                    if (p && p.then) p.then(done, done); else done();
                })(pending.shift());
            }
        }
        pump();
    }

    // Appiattisce gli album del profilo in una lista di URL per la card, stesso ordine di renderProfile.
    // v2.4 (feedback Marco su Simone, solo video): i VIDEO entrano in rotazione con il loro poster JPG
    // (~20KB, chiave `posters` dell'endpoint) — prima venivano scartati e chi aveva solo video
    // risultava "senza portfolio". Video senza poster: saltato (mai scaricare il .mp4 per un frame).
    function cardMediaFromAlbums(d) {
        var albums = d.albums || {};
        var posters = (d && d.posters) || {};
        var keys = Object.keys(albums).filter(function (k) { return k !== 'generale'; });
        if (albums.generale) keys.push('generale');
        var media = [];
        keys.forEach(function (k) {
            (albums[k] || []).forEach(function (url) {
                if (VIDEO_RE.test(url)) {
                    if (posters[url]) { media.push(posters[url]); __videoByPoster[posters[url]] = url; } // v2.7 — per hover-to-play
                } else {
                    media.push(withW(url, 600));
                }
            });
        });
        return media;
    }

    // v2.4 (feedback Marco) — crew senza NESSUN media mostrabile (né foto né video con poster):
    // card nascosta e contatore aggiornato. Solo dopo fetch RIUSCITO (mai su errore di rete).
    function hideEmptyCard(card) {
        if (!card || card.classList.contains('crew-pub-hidden')) return;
        card.classList.add('crew-pub-hidden');
        card.style.display = 'none';
        var n = document.querySelectorAll('.crew-pub-card:not(.crew-pub-hidden)').length;
        var rc = $('#results-count');
        if (rc) rc.textContent = n + ' ' + (STR.resultsLabel || 'crew');
    }

    document.addEventListener('click', function (e) {
        var btn = e.target.closest && e.target.closest('.crew-pub-nav');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();
        var card = btn.closest('.crew-pub-card');
        var photo = card && card.querySelector('.crew-pub-photo');
        if (!card || !photo) return;
        stopHoverVideo(card); // v2.7 — le frecce sfogliano le immagini: il video hover si ferma
        var dir = parseInt(btn.getAttribute('data-cardnav'), 10) || 1;
        function cycle(media) {
            if (!media || media.length <= 1) return;
            var idx = parseInt(card.getAttribute('data-cnidx') || '0', 10);
            idx = (idx + dir + media.length) % media.length;
            card.setAttribute('data-cnidx', idx);
            photo.style.backgroundImage = 'url(' + encodeURI(media[idx]) + ')';
            syncPlayBadge(photo, media[idx]); // v2.12
            card._cnNext = Date.now() + 4000; // 2026-08-10 — l'utente naviga a mano: rimanda il crossfade automatico
        }
        if (card._cnMedia) { cycle(card._cnMedia); return; }
        btn.disabled = true;
        fetch(API_PROFILE + '?uuid=' + encodeURIComponent(card.dataset.uuid), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                var curMatch = /url\(["']?([^"')]+)["']?\)/.exec(photo.style.backgroundImage || '');
                var cur = curMatch ? curMatch[1] : '';
                var media = coverFirstMedia(d); // 2026-08-10 — stesso ordine cover-first della cache
                card._cnMedia = media.length ? media : (cur ? [cur] : []);
                __coverCache[card.dataset.uuid] = card._cnMedia; // 2026-08-10 — allinea la cache al fetch delle frecce
                card._cnMedia.forEach(function (u) { var im = new Image(); im.src = u; }); // precarico: scorrimento immediato dopo il 1° fetch
                var st = card._cnMedia.indexOf(cur);
                card.setAttribute('data-cnidx', st >= 0 ? st : 0);
                btn.disabled = false;
                cycle(card._cnMedia);
            })
            .catch(function () { btn.disabled = false; });
    }, true);

    // ─── Init ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        // 2026-07-26 — chip categoria: sync sul <select> nascosto + riuso del listener 'change' esistente (nessuna logica di filtro duplicata)
        var catChipsWrap = $('#crewCatChips');
        if (catChipsWrap) {
            catChipsWrap.addEventListener('click', function (e) {
                var chip = e.target.closest('.crew-cat-chip');
                if (!chip) return;
                var chips = catChipsWrap.querySelectorAll('.crew-cat-chip');
                for (var i = 0; i < chips.length; i++) chips[i].classList.remove('is-active');
                chip.classList.add('is-active');
                var sel = $('#filter-categoria');
                sel.value = chip.dataset.cat || '';
                sel.dispatchEvent(new Event('change'));
            });
        }
        $('#filter-categoria').addEventListener('change', function () {
            // v2.11 — GTM: categoria scelta (chip o tendina; vuoto = "tutte", non tracciato)
            if (this.value) gtmPush({ event: 'crew_filter_cat', category: this.value });
            loadCrews();
        });
        $('#filter-paese').addEventListener('change', function () { syncProvinceVisibility(); loadCrews(); });
        var provSel = $('#filter-provincia');
        if (provSel) provSel.addEventListener('change', loadCrews);
        // 2026-08-11 v2.8 — ricerca testuale: debounce 200ms, filtra i risultati gia' in pagina
        // (l'evento 'input' scatta anche sulla X di clear del type=search)
        var searchEl = $('#crew-search');
        if (searchEl) {
            var searchT = null;
            searchEl.addEventListener('input', function () {
                loadComuni(); // v2.16 — mappa comuni al primo uso, poi no-op
                clearTimeout(searchT);
                searchT = setTimeout(function () {
                    textQuery = searchEl.value.trim();
                    renderGrid(applyTextFilter(lastResults));
                    updateShownCount();
                    renderFeatured(lastResults); // v2.9 — nasconde/rimostra le strisce
                    syncUrl(); // v2.11 — ricerca condivisibile via ?q=
                    // v2.11 — GTM: cosa cercano i visitatori (solo query non vuote, post-debounce)
                    if (textQuery) gtmPush({ event: 'crew_search', search_term: textQuery, results_count: document.querySelectorAll('#crew-grid .crew-pub-card').length });
                }, 200);
            });
        }
        // 2026-08-11 v2.9 — "Vedi tutti →" delle strisce: attiva il chip categoria (riusa la
        // logica esistente del chip, zero duplicazione) e scrolla al catalogo
        document.addEventListener('click', function (e) {
            var btn = e.target.closest('.crew-feat-all');
            if (!btn) return;
            var chip = catChipsWrap && catChipsWrap.querySelector('.crew-cat-chip[data-cat="' + cssEscape(btn.dataset.cat) + '"]');
            gtmPush({ event: 'crew_vedi_tutti', category: btn.dataset.cat }); // v2.11
            if (chip) { chip.click(); catChipsWrap.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
        });
        // 2026-08-11 v2.11 — deep-link: applica ?q/&cat/&paese/&prov dall'URL PRIMA del primo load
        // (prov si applica async in populateProvinceFilter; lang/uuid non si toccano)
        var __qs = new URLSearchParams(window.location.search);
        var qInit = __qs.get('q') || '';
        if (qInit && searchEl) { searchEl.value = qInit; textQuery = qInit.trim(); loadComuni(); } // v2.16
        var catInit = __qs.get('cat') || '';
        if (catInit) {
            var catSel = $('#filter-categoria');
            catSel.value = catInit;
            if (catSel.value === catInit && catChipsWrap) {
                var chipsDL = catChipsWrap.querySelectorAll('.crew-cat-chip');
                for (var ci = 0; ci < chipsDL.length; ci++) chipsDL[ci].classList.toggle('is-active', (chipsDL[ci].dataset.cat || '') === catInit);
            }
        }
        var paeseInit = __qs.get('paese') || '';
        if (paeseInit) { var paeseSel = $('#filter-paese'); paeseSel.value = paeseInit; if (paeseSel.value !== paeseInit) paeseSel.value = ''; }
        __initProv = __qs.get('prov') || '';
        populateProvinceFilter();
        syncProvinceVisibility();
        var initialLoad = loadCrews();
        // Wiring lightbox (elementi statici nel template)
        var lbEl = $('#crew-lightbox');
        if (lbEl) {
            var p = $('#crew-lb-prev'), n = $('#crew-lb-next'), cl = $('#crew-lb-close');
            if (p)  p.addEventListener('click', function (e) { e.stopPropagation(); lbPrev(); });
            if (n)  n.addEventListener('click', function (e) { e.stopPropagation(); lbNext(); });
            if (cl) cl.addEventListener('click', function (e) { e.stopPropagation(); lbClose(); });
            lbEl.addEventListener('click', function (e) { if (e.target === lbEl || e.target.id === 'crew-lb-img') lbClose(); });
        }
        var initUuid = new URLSearchParams(window.location.search).get('uuid');
        // 2026-07-31 — aspetta che la griglia sia caricata (lastResults) prima di aprire il deep-link,
        // altrimenti l'avatar risulta vuoto (vedi commento su loadCrews)
        if (initUuid && initialLoad && initialLoad.then) initialLoad.then(function () { openProfile(initUuid, true); });
        else if (initUuid) openProfile(initUuid, true);
    });
})();
