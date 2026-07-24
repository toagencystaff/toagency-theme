/**
 * crew-database-list.js — v1.8 (2026-07-23)
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

    var selectedUuids = new Set();
    var lastResults = [];

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
        fetch(API_SEARCH, {
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
            renderGrid(lastResults);
            $('#results-count').textContent = lastResults.length + ' ' + (STR.resultsLabel || 'crew');
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
            grid.innerHTML = '<div class="crew-pub-empty">' + escapeHtml(STR.empty || 'Nessun crew.') + '</div>';
            return;
        }
        var frag = document.createDocumentFragment();
        crews.forEach(function (c) {
            var card = document.createElement('div');
            card.className = 'crew-pub-card' + (selectedUuids.has(c.uuid) ? ' selected' : '');
            card.dataset.uuid = c.uuid;

            // Foto profilo
            var photo = document.createElement('div');
            photo.className = 'crew-pub-photo';
            if (c.foto_profilo_url) {
                photo.style.backgroundImage = 'url(' + encodeURI(c.foto_profilo_url) + ')';
            } else {
                photo.textContent = '👤';
            }
            card.appendChild(photo);

            // Body
            var body = document.createElement('div');
            body.className = 'crew-pub-body';

            var name = document.createElement('div');
            name.className = 'crew-pub-name';
            name.textContent = c.nome || '—';
            body.appendChild(name);

            var uuid = document.createElement('div');
            uuid.className = 'crew-pub-uuid';
            uuid.textContent = '#' + (c.uuid_short || (c.uuid ? c.uuid.substring(0, 8) : ''));
            body.appendChild(uuid);

            if (c.categorie && c.categorie.length) {
                var cats = document.createElement('div');
                cats.className = 'crew-pub-categories';
                c.categorie.slice(0, 3).forEach(function (cat) {
                    var chip = document.createElement('span');
                    chip.className = 'crew-pub-cat-chip';
                    chip.textContent = cat;
                    cats.appendChild(chip);
                });
                body.appendChild(cats);
            }

            var meta = document.createElement('div');
            meta.className = 'crew-pub-meta';
            var metaParts = [];
            if (c.livello) metaParts.push(c.livello);
            if (c.paese) metaParts.push(c.paese);
            meta.textContent = metaParts.join(' · ');
            body.appendChild(meta);

            var viewBtn = document.createElement('button');
            viewBtn.type = 'button';
            viewBtn.className = 'crew-pub-view';
            viewBtn.textContent = STR.viewProfile || 'Vedi profilo';
            viewBtn.addEventListener('click', function (e) { e.stopPropagation(); openProfile(c.uuid); });
            body.appendChild(viewBtn);

            card.appendChild(body);
            card.addEventListener('click', function () { toggleSelect(c.uuid); });
            frag.appendChild(card);
        });
        grid.innerHTML = '';
        grid.appendChild(frag);
    }

    function toggleSelect(uuid) {
        if (selectedUuids.has(uuid)) selectedUuids.delete(uuid);
        else selectedUuids.add(uuid);

        var cards = document.querySelectorAll('.crew-pub-card[data-uuid="' + cssEscape(uuid) + '"]');
        cards.forEach(function (c) { c.classList.toggle('selected', selectedUuids.has(uuid)); });

        updateActionBar();
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

    // 2026-07-23: lightbox — pfPhotos raccoglie gli URL foto full-size in ordine di render
    var pfPhotos = [];

    // Aggiunge &w=<w> agli URL proxy crew-photo-public.php (miniatura vs grande)
    function withW(url, w) {
        if (!/crew-photo-public\.php/i.test(url)) return url;
        return url + (url.indexOf('?') >= 0 ? '&' : '?') + 'w=' + w;
    }

    function videoTag(url) {
        var safe = encodeURI(url);
        return '<button type="button" class="crew-pf-vthumb" data-src="' + safe + '"><span class="crew-pf-play">▶</span><span class="crew-pf-vlabel">video</span></button>';
    }

    var currentProfileUuid = null;

    function openProfile(uuid, fromPop) {
        if (!uuid) return;
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
        fetch(API_PROFILE + '?uuid=' + encodeURIComponent(uuid), { credentials: 'same-origin' })
            .then(function (r) { if (!r.ok) throw new Error('http ' + r.status); return r.json(); })
            .then(function (d) { renderProfile(d); })
            .catch(function (err) {
                console.error('[crew-pub] profile error:', err);
                body.innerHTML = '<div class="crew-pf-error">' + escapeHtml(STR.errorProfile || 'Profilo non disponibile.') + '</div>';
            });
    }

    function renderProfile(d) {
        var body = $('#crew-profile-body');
        if (!body) return;
        pfPhotos = [];
        var labels = d.ruoli_label || {};
        var albums = d.albums || {};
        var bio = d.bio_ruoli || {};
        var codice = d.codice ? '<span class="crew-pf-code">· ' + escapeHtml(d.codice) + '</span>' : '';
        // Copertina hero (2026-07-23): prima foto (= pfPhotos[0]) come cover in cima, nome in overlay; fallback nome nell'header
        var coverPhoto = '';
        var coverKeys = Object.keys(albums).filter(function (k) { return k !== 'generale'; });
        if (albums.generale) coverKeys.push('generale');
        for (var ck = 0; ck < coverKeys.length && !coverPhoto; ck++) {
            var cps = albums[coverKeys[ck]] || [];
            for (var cp = 0; cp < cps.length; cp++) { if (!VIDEO_RE.test(cps[cp])) { coverPhoto = cps[cp]; break; } }
        }
        var html = '';
        if (coverPhoto) {
            html += '<div class="crew-pf-hero"><img class="crew-pf-hero-img crew-pf-clic" src="' + encodeURI(withW(coverPhoto, 1200)) + '" alt="" data-idx="0">'
                 +  '<div class="crew-pf-hero-overlay"><h2 class="crew-pf-hero-name">' + escapeHtml(d.nome || '—') + codice + '</h2></div></div>';
        }
        html += '<div class="crew-pf-header">';
        if (!coverPhoto) html += '<h2 class="crew-pf-name">' + escapeHtml(d.nome || '—') + codice + '</h2>';
        if (d.categorie && d.categorie.length) {
            html += '<div class="crew-pf-roles">';
            d.categorie.forEach(function (cat) { html += '<span class="crew-pf-chip">' + escapeHtml(cat) + '</span>'; });
            html += '</div>';
        }
        // Privacy: SOLO provincia (mai il comune di residenza/domicilio); paese solo se non IT
        var loc = d.provincia ? provName(String(d.provincia)) : '';
        if (d.paese && d.paese !== 'IT') loc = loc ? (loc + ' · ' + d.paese) : String(d.paese);
        if (loc) html += '<div class="crew-pf-loc">📍 ' + escapeHtml(loc) + '</div>';
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
        html += '</div>';
        html += '<button type="button" class="crew-pf-cta" onclick="crewPfRequestInfo()">' + escapeHtml(STR.requestInfo || '📧 Richiedi info') + '</button>';
        if (d.bio) html += '<p class="crew-pf-intro">' + escapeHtml(d.bio) + '</p>';
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
                    if (VIDEO_RE.test(url)) { html += videoTag(url); return; }
                    var idx = pfPhotos.length;
                    pfPhotos.push(withW(url, 1600));
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
    }

    function wireVideos(scope) {
        scope.querySelectorAll('.crew-pf-vthumb').forEach(function (b) {
            b.addEventListener('click', function () {
                var src = b.getAttribute('data-src');
                var w = document.createElement('div');
                w.className = 'crew-pf-vwrap';
                w.innerHTML = '<video class="crew-pf-media" src="' + src + '" autoplay muted playsinline controls></video>';
                if (b.parentNode) b.parentNode.replaceChild(w, b);
            });
        });
    }

    function hideProfile() {
        var ov = $('#crew-profile-overlay');
        if (ov) ov.classList.remove('show');
        document.body.style.overflow = '';
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

    // ─── Lightbox foto (2026-07-23) ────────────────────────────
    var lbIdx = 0;
    function lbShow() {
        var lb = $('#crew-lightbox'), img = $('#crew-lb-img');
        if (!lb || !img || !pfPhotos.length) return;
        if (lbIdx < 0) lbIdx = pfPhotos.length - 1;
        if (lbIdx >= pfPhotos.length) lbIdx = 0;
        img.src = pfPhotos[lbIdx];
        var c = $('#crew-lb-counter'); if (c) c.textContent = (lbIdx + 1) + ' / ' + pfPhotos.length;
        lb.classList.add('show'); lb.setAttribute('aria-hidden', 'false');
    }
    function lbOpen(i) { lbIdx = i; lbShow(); }
    function lbClose() { var lb = $('#crew-lightbox'); if (lb) { lb.classList.remove('show'); lb.setAttribute('aria-hidden', 'true'); var img = $('#crew-lb-img'); if (img) img.src = ''; } }
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

    // ─── Init ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        $('#filter-categoria').addEventListener('change', loadCrews);
        $('#filter-paese').addEventListener('change', function () { syncProvinceVisibility(); loadCrews(); });
        var provSel = $('#filter-provincia');
        if (provSel) provSel.addEventListener('change', loadCrews);
        populateProvinceFilter();
        syncProvinceVisibility();
        loadCrews();
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
        if (initUuid) openProfile(initUuid, true); // deep-link scheda
    });
})();
