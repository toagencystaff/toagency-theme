/**
 * crew-database-list.js — v1.1 (2026-07-11)
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

    var selectedUuids = new Set();
    var lastResults = [];

    function $(sel) { return document.querySelector(sel); }

    function loadCrews() {
        var body = {
            categoria: $('#filter-categoria').value,
            paese:     $('#filter-paese').value
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

    function mediaTag(url) {
        var safe = encodeURI(url);
        if (VIDEO_RE.test(url)) {
            return '<div class="crew-pf-vwrap"><video class="crew-pf-media" src="' + safe + '#t=0.1" preload="auto" muted playsinline controls></video><button type="button" class="crew-pf-play" aria-label="Play">▶</button></div>';
        }
        return '<img class="crew-pf-media" src="' + safe + '" alt="" loading="lazy">';
    }

    function openProfile(uuid, fromPop) {
        if (!uuid) return;
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
        var labels = d.ruoli_label || {};
        var albums = d.albums || {};
        var bio = d.bio_ruoli || {};
        var codice = d.codice ? '<span class="crew-pf-code">· ' + escapeHtml(d.codice) + '</span>' : '';
        var html = '<div class="crew-pf-header"><h2 class="crew-pf-name">' + escapeHtml(d.nome || '—') + codice + '</h2>';
        if (d.categorie && d.categorie.length) {
            html += '<div class="crew-pf-roles">';
            d.categorie.forEach(function (cat) { html += '<span class="crew-pf-chip">' + escapeHtml(cat) + '</span>'; });
            html += '</div>';
        }
        html += '</div>';
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
                photos.forEach(function (url) { html += mediaTag(url); });
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
        scope.querySelectorAll('video.crew-pf-media').forEach(function (v) {
            v.addEventListener('loadeddata', function () {
                try { if (v.currentTime < 0.1) v.currentTime = 0.1; } catch (e) {}
            }, { once: true });
        });
        scope.querySelectorAll('.crew-pf-play').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var v = btn.parentNode.querySelector('video');
                if (v) { v.play(); btn.style.display = 'none'; }
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
        if (e.key === 'Escape') { var ov = $('#crew-profile-overlay'); if (ov && ov.classList.contains('show')) window.crewPubCloseProfile(); }
    });
    window.addEventListener('popstate', function () {
        var uuid = new URLSearchParams(window.location.search).get('uuid');
        if (uuid) openProfile(uuid, true); else hideProfile();
    });

    // ─── Init ──────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        $('#filter-categoria').addEventListener('change', loadCrews);
        $('#filter-paese').addEventListener('change', loadCrews);
        loadCrews();
        var initUuid = new URLSearchParams(window.location.search).get('uuid');
        if (initUuid) openProfile(initUuid, true); // deep-link scheda
    });
})();
