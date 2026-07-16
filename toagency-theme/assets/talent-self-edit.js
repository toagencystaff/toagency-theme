/**
 * talent-self-edit.js — v2.0 (2026-05-19, S8.A)
 * v1.0 (S7): self-edit campi anagrafici talent_database.
 * v2.0 (S8.A): aggiunge upload 4 album media (polaroid/dettaglio/portfolio/eventi)
 *              con disclaimer legale + veridicità per-album.
 */
(function () {
    'use strict';

    var cfg = window.talentEditConfig || {};
    var API_LOAD     = cfg.apiLoad;
    var API_SAVE     = cfg.apiSave;
    var API_MEDIA_LS = cfg.apiMediaList;
    var API_MEDIA_UP = cfg.apiMediaUp;
    var UUID  = cfg.uuid  || '';
    var TOKEN = cfg.token || '';
    var STR   = cfg.strings || {};

    // FIX 2026-06-28 marco — aggiunti comune + provincia
    var FIELDS = ['telefono','instagram','tiktok','altezza','taglia','scarpe','capelli',
                  'comune_residenza','provincia_domicilio'];
    var ALBUMS = ['polaroid','dettaglio','portfolio','eventi'];
    var currentAlbum = 'polaroid';
    var albumsData = { polaroid:[], dettaglio:[], portfolio:[], eventi:[] };
    var paeseResidenza = '';

    function $(id) { return document.getElementById(id); }

    // FIX 2026-07-01 marco — comune self-edit: ricerca a suggerimenti da cerca-comune.php, SENZA testo libero.
    // Visibile = f-comune_search (ricerca); valore salvato = hidden f-comune_residenza (solo se scelto dalla lista).
    function initComuneTypeahead(saved, nation) {
        var search = $('f-comune_search');
        var hidden = $('f-comune_residenza');
        var dd     = $('f-comune_dropdown');
        if (!search || !hidden || !dd) return;
        var apiBase = (window.talentEditConfig || {}).comuneApiUrl;
        if (!apiBase) return;
        var nat = (nation || 'IT').toUpperCase();
        var lastValid = saved || '';        // il valore già salvato è considerato valido
        hidden.value = saved || '';
        search.value = saved || '';
        search.dataset.valid = saved ? '1' : '';
        var timer = null;

        function closeDD() { dd.style.display = 'none'; dd.innerHTML = ''; }

        function fetchSug(q) {
            fetch(apiBase + '?type=cities&nation=' + encodeURIComponent(nat) + '&q=' + encodeURIComponent(q))
                .then(function (r) { return r.json(); })
                .then(function (list) {
                    dd.innerHTML = '';
                    if (!list || !list.length) { closeDD(); return; }
                    list.slice(0, 20).forEach(function (item) {
                        var row = document.createElement('div');
                        row.textContent = item.display || item.name_local;
                        row.style.cssText = 'padding:10px 12px;cursor:pointer;border-bottom:1px solid #222;color:#e5e7eb;font-size:14px;';
                        row.addEventListener('mouseenter', function () { row.style.background = '#1f1f27'; });
                        row.addEventListener('mouseleave', function () { row.style.background = ''; });
                        row.addEventListener('mousedown', function (e) {
                            e.preventDefault();               // evita il blur prima del click
                            var val = item.name_local || item.display;
                            hidden.value = val;
                            search.value = val;
                            search.dataset.valid = '1';
                            lastValid = val;
                            closeDD();
                        });
                        dd.appendChild(row);
                    });
                    dd.style.display = 'block';
                })
                .catch(function () { closeDD(); });
        }

        search.addEventListener('input', function () {
            search.dataset.valid = '';     // finché non scegli dalla lista, non è valido
            hidden.value = '';
            var q = search.value.trim();
            clearTimeout(timer);
            if (q.length < 2) { closeDD(); return; }
            timer = setTimeout(function () { fetchSug(q); }, 250);
        });

        search.addEventListener('blur', function () {
            setTimeout(function () {        // lascia scattare prima l'eventuale scelta (mousedown)
                closeDD();
                if (search.dataset.valid !== '1') {   // niente scelta valida → ripristina l'ultimo valido (no testo libero)
                    search.value = lastValid;
                    hidden.value = lastValid;
                    if (lastValid) search.dataset.valid = '1';
                }
            }, 180);
        });
    }

    // FIX 2026-07-01 marco — popola tendina provincia da province-italia.json (valore = nome canonico)
    function populateProvince(selected) {
        var sel = $('f-provincia_domicilio');
        if (!sel || sel.tagName !== 'SELECT') return;
        var url = (window.talentEditConfig || {}).provinceJsonUrl;
        if (!url) return;
        fetch(url).then(function (r) { return r.json(); }).then(function (list) {
            (list || []).forEach(function (p) {
                var o = document.createElement('option');
                o.value = p.name;                       // canonico = nome pieno (es. "Torino")
                o.textContent = p.name + ' (' + p.code + ')';
                sel.appendChild(o);
            });
            if (selected) {
                sel.value = selected;
                if (sel.value !== selected) {           // valore vecchio non in lista: preservalo, niente svuotamenti
                    var o2 = document.createElement('option');
                    o2.value = selected;
                    o2.textContent = selected;
                    sel.appendChild(o2);
                    sel.value = selected;
                }
            }
        }).catch(function () {});
    }

    function showError(msg) {
        $('tse-status').textContent = msg;
        $('tse-status').classList.add('error');
        $('tse-form').classList.remove('visible');
    }

    function loadData() {
        if (!UUID || !TOKEN) { showError(STR.invalidLink || 'Link non valido'); return; }
        fetch(API_LOAD + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN), {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.success) { showError(STR.invalidLink || 'Link non valido'); return; }
            $('tse-uuid-display').textContent = '#' + (d.uuid_short || UUID.substring(0,8));
            $('tse-name-display').innerHTML = 'Stai modificando il profilo di <strong>' +
                escapeHtml(d.talent.nome || '—') + '</strong>';

            // FIX 2026-06-27 marco — dati live subito: niente più avviso "in attesa di revisione"
            $('tse-pending').style.display = 'none';

            FIELDS.forEach(function (f) {
                var el = $('f-' + f);
                if (el) el.value = (d.talent[f] !== null && d.talent[f] !== undefined) ? d.talent[f] : '';
            });

            // Misure (sotto-step 2, 15/07) — popola da talent.misure + misure_prese_il
            var _M = d.talent.misure || {};
            var _anyExtra = false;
            var _EXTRA = ['spalle','collo','cavallo_interno','cavallo_esterno','coscia','polpaccio','manica','bicipite','avambraccio','polso'];
            document.querySelectorAll('.tse-mis').forEach(function (el) {
                var k = el.getAttribute('data-mis');
                if (k && _M[k] !== undefined && _M[k] !== null && _M[k] !== '') {
                    el.value = _M[k];
                    if (_EXTRA.indexOf(k) > -1) _anyExtra = true;
                }
            });
            var _pr = $('f-misure-prese'); if (_pr) _pr.value = d.talent.misure_prese_il || '';
            var _tg = $('f-mis-toggle');
            if (_anyExtra && _tg && !_tg.checked) { _tg.checked = true; _tg.dispatchEvent(new Event('change')); }
            document.querySelectorAll('.tse-mis, #f-altezza, #f-scarpe').forEach(function (el) { el.dispatchEvent(new Event('input')); });

            // FIX 2026-07-01 marco — tendina provincia self-edit
            populateProvince(d.talent.provincia_domicilio || '');

            // FIX 2026-07-01 marco — ricerca comune self-edit (no testo libero)
            initComuneTypeahead(d.talent.comune_residenza || '', d.talent.paese_residenza || 'IT');

            // FIX 2026-05-26 marco — community IT + highlight campi mancanti
            paeseResidenza = d.talent.paese_residenza || '';
            if (paeseResidenza === 'IT') {
                var cb = $('tse-community-block');
                if (cb) cb.style.display = 'block';
            }
            highlightMissingFields(d.talent);

            $('tse-status').style.display = 'none';
            $('tse-form').classList.add('visible');

            // S8.A: carica anche i media e mostra la sezione foto
            loadMedia();
        })
        .catch(function (err) {
            console.error('[tse] load:', err);
            showError(STR.invalidLink || 'Errore caricamento');
        });
    }

    window.talentEditSubmit = function () {
        var btn = $('tse-btn-save');
        $('tse-result').innerHTML = '';
        btn.disabled = true;
        btn.textContent = STR.saving || 'Invio…';

        var payload = { uuid: UUID, t: TOKEN, honeypot_url: $('f-honeypot').value };
        FIELDS.forEach(function (f) {
            var el = $('f-' + f);
            payload[f] = el ? el.value.trim() : '';
        });

        // Misure (sotto-step 2, 15/07) — oggetto misure + data
        var _mis = {};
        document.querySelectorAll('.tse-mis').forEach(function (el) {
            var k = el.getAttribute('data-mis'); var v = (el.value || '').trim();
            if (k && v !== '') _mis[k] = v;
        });
        payload.misure = _mis;
        var _preseEl = $('f-misure-prese');
        if (_preseEl && _preseEl.value) payload.misure_prese_il = _preseEl.value;

        fetch(API_SAVE, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.success) {
                if (d.changes_count === 0) {
                    showResult('err', STR.noChanges || 'Nessuna modifica');
                } else {
                    // FIX 2026-06-27 marco — dati live subito: popup con elenco modifiche
                    showLivePopup(d.changes || []);
                    setTimeout(loadData, 600);
                }
            } else {
                showResult('err', (STR.errorPrefix || 'Errore: ') + (d.message || d.error || 'unknown'));
            }
        })
        .catch(function () {
            showResult('err', (STR.errorPrefix || 'Errore: ') + 'rete');
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = STR.save || 'Invia';
        });
    };

    // ─── S8.A — media album ───
    function loadMedia() {
        fetch(API_MEDIA_LS + '?uuid=' + encodeURIComponent(UUID) + '&t=' + encodeURIComponent(TOKEN) + '&scope=self&_=' + Date.now(), {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (!d.ok) return;
            ALBUMS.forEach(function (a) { albumsData[a] = d.albums[a] || []; });
            // FIX 2026-05-26 marco — photo alert se nessuna polaroid
            var pa = $('tse-photo-alert');
            if (pa) pa.style.display = albumsData['polaroid'].length === 0 ? 'block' : 'none';
            $('tse-foto-section').style.display = 'block';
            renderAlbum(currentAlbum);
        })
        .catch(function (err) { console.error('[tse] media load:', err); });
    }

    function renderAlbum(album) {
        currentAlbum = album;
        // Tab attivo
        var tabs = document.querySelectorAll('.tse-album-tab');
        for (var i=0; i<tabs.length; i++) {
            tabs[i].classList.toggle('active', tabs[i].getAttribute('data-album') === album);
        }
        // Descrizione album
        var desc = (STR.albumDesc && STR.albumDesc[album]) || '';
        $('tse-album-desc').textContent = desc;

        // Veridicità testo per album
        var v = (STR.verita && STR.verita[album]) || '';
        $('tse-verita-text').textContent = v;

        // FIX 2026-06-27 marco — data scatto su TUTTI gli album (obbligatoria polaroid, facoltativa le altre)
        $('tse-data-scatto-wrap').style.display = 'block';
        var _lbl = $('tse-data-scatto-label');
        var _hint = $('tse-data-scatto-hint');
        if (album === 'polaroid') {
            if (_lbl)  _lbl.textContent  = (STR.dataScattoLabelReq  || 'Data scatto (obbligatoria)');
            if (_hint) _hint.textContent = (STR.dataScattoHintPolaroid || 'Quando è stata SCATTATA la foto (non quando la carichi). Max 5 anni fa, verrà stampata sulla foto.');
        } else {
            if (_lbl)  _lbl.textContent  = (STR.dataScattoLabelOpt  || 'Data scatto (facoltativa)');
            if (_hint) _hint.textContent = (STR.dataScattoHintAltri || 'Quando è stata SCATTATA la foto (non quando la carichi). Facoltativa ma utile.');
        }

        // Grid
        var grid = $('tse-album-grid');
        var items = albumsData[album] || [];

        // Rimuovi vecchio counter (se presente)
        var oldCount = grid.parentElement.querySelector('.tse-album-count-wrap');
        if (oldCount) oldCount.remove();

        if (!items.length) {
            grid.innerHTML = '<div class="tse-album-empty">' + escapeHtml(STR.noPhotos || 'Nessuna foto') + '</div>';
            resetUploadForm();
            return;
        }
        // FIX 2026-06-28 marco — album labels per il menu sposta
        var ALBUM_LABELS = { polaroid:'Polaroid', dettaglio:'Dettaglio', portfolio:'Portfolio', eventi:'Eventi' };

        grid.innerHTML = '';
        items.forEach(function (it) {
            var stateClass = '';
            var title = 'Click per ingrandire';
            if (it.motivo_rifiuto) {
                stateClass = 'rejected';
                title = 'Rifiutata — click per ingrandire';
            } else if (!it.approvato_staff) {
                stateClass = 'pending';
                title = 'In attesa di approvazione — click per ingrandire';
            }
            var thumb = document.createElement('div');
            thumb.className = 'tse-album-thumb' + (stateClass ? ' ' + stateClass : '');
            thumb.title = title;
            thumb.setAttribute('data-id', it.id); // FIX 2026-07-10 marco — drag&drop riordino
            // Lightbox al click sull'immagine
            (function (url) {
                thumb.addEventListener('click', function (e) {
                    if (!e.target.closest('.tse-thumb-actions')) talentShowLightbox(url);
                });
            })(it.url);
            var img = document.createElement('img');
            img.src = it.url;
            img.alt = '';
            img.loading = 'lazy';
            thumb.appendChild(img);

            // FIX 2026-06-28 marco — bottoni elimina + sposta album
            (function (mediaId, albumTipo) {
                var acts = document.createElement('div');
                acts.className = 'tse-thumb-actions';

                // Bottone elimina
                var delBtn = document.createElement('button');
                delBtn.type = 'button';
                delBtn.className = 'tse-thumb-btn tse-thumb-del';
                delBtn.title = 'Elimina foto';
                delBtn.textContent = '🗑';
                delBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    if (!confirm('Eliminare questa foto? L\'azione non è reversibile.')) return;
                    talentMediaDelete(mediaId);
                });
                acts.appendChild(delBtn);

                // Bottone sposta (dropdown album)
                var moveBtn = document.createElement('button');
                moveBtn.type = 'button';
                moveBtn.className = 'tse-thumb-btn tse-thumb-move';
                moveBtn.title = 'Sposta in un altro album';
                moveBtn.textContent = '↔';
                var moveMenu = document.createElement('div');
                moveMenu.className = 'tse-move-menu';
                ['polaroid','dettaglio','portfolio','eventi'].forEach(function (alb) {
                    if (alb === albumTipo) return;
                    var opt = document.createElement('button');
                    opt.type = 'button';
                    opt.textContent = ALBUM_LABELS[alb] || alb;
                    opt.addEventListener('click', function (e) {
                        e.stopPropagation();
                        moveMenu.style.display = 'none';
                        talentMediaMove(mediaId, alb);
                    });
                    moveMenu.appendChild(opt);
                });
                moveBtn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var open = moveMenu.style.display === 'block';
                    // chiudi tutti i menu aperti
                    document.querySelectorAll('.tse-move-menu').forEach(function (m) { m.style.display = 'none'; });
                    moveMenu.style.display = open ? 'none' : 'block';
                });
                acts.appendChild(moveBtn);
                acts.appendChild(moveMenu);
                thumb.appendChild(acts);
            })(it.id, currentAlbum);

            grid.appendChild(thumb);
        });

        _tseInitSort(grid, album); // FIX 2026-07-10 marco — drag&drop riordino foto

        // Contatore pending/rejected sotto la griglia
        var pendingCount = items.filter(function (i) { return !i.motivo_rifiuto && !i.approvato_staff; }).length;
        var rejectedCount = items.filter(function (i) { return !!i.motivo_rifiuto; }).length;
        if (pendingCount || rejectedCount) {
            var wrap = document.createElement('div');
            wrap.className = 'tse-album-count-wrap';
            var info = document.createElement('div');
            info.className = 'tse-album-count';
            var parts = [];
            if (pendingCount)  parts.push('🟡 ' + pendingCount + ' in attesa di approvazione');
            if (rejectedCount) parts.push('❌ ' + rejectedCount + ' rifiutate');
            info.textContent = parts.join(' · ');
            wrap.appendChild(info);
            grid.parentElement.insertBefore(wrap, grid.nextSibling);
        }

        // Reset upload area
        resetUploadForm();
    }

    function resetUploadForm() {
        $('tse-file-input').value = '';
        $('tse-upload-fname').textContent = '—';
        $('tse-legal-ok').checked = false;
        $('tse-verita-ok').checked = false;
        $('tse-data-scatto').value = '';
        $('tse-upload-status').textContent = '';
        $('tse-upload-status').className = 'tse-upload-status';
    }

    window.talentAlbumSwitch = function (album) {
        if (ALBUMS.indexOf(album) < 0) return;
        renderAlbum(album);
    };

    window.talentFileChosen = function (input) {
        var f = input.files && input.files[0];
        $('tse-upload-fname').textContent = f ? f.name : '—';
        $('tse-upload-status').textContent = '';
        $('tse-upload-status').className = 'tse-upload-status';
    };

    window.talentUploadGo = function () {
        var btn = $('tse-upload-go');
        var status = $('tse-upload-status');
        var file = $('tse-file-input').files[0];
        if (!file) { status.textContent = 'Seleziona un file'; status.className = 'tse-upload-status err'; return; }
        if (!$('tse-legal-ok').checked || !$('tse-verita-ok').checked) {
            status.textContent = 'Devi accettare disclaimer + veridicità';
            status.className = 'tse-upload-status err';
            return;
        }
        // FIX 2026-06-27 marco — data scatto letta per tutti gli album; obbligatoria solo polaroid
        var dataScatto = $('tse-data-scatto').value;
        if (currentAlbum === 'polaroid' && !dataScatto) {
            status.textContent = 'Data scatto obbligatoria per polaroid'; status.className = 'tse-upload-status err'; return;
        }

        var fd = new FormData();
        fd.append('uuid', UUID);
        fd.append('t', TOKEN);
        fd.append('album_tipo', currentAlbum);
        fd.append('dichiarazione_legale', '1');
        fd.append('veridicita', '1');
        if (dataScatto) fd.append('data_scatto', dataScatto);
        fd.append('foto', file);

        btn.disabled = true;
        btn.textContent = STR.uploading || 'Caricamento…';
        status.textContent = STR.uploading || 'Caricamento…';
        status.className = 'tse-upload-status loading';

        fetch(API_MEDIA_UP, { method:'POST', body:fd, credentials:'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.ok) {
                status.textContent = '✓ ' + (d.message || 'Foto caricata');
                status.className = 'tse-upload-status ok';
                setTimeout(loadMedia, 600);
            } else {
                status.textContent = (STR.errorPrefix || 'Errore: ') + (d.message || d.error || 'upload');
                status.className = 'tse-upload-status err';
            }
        })
        .catch(function () {
            status.textContent = (STR.errorPrefix || 'Errore: ') + 'rete';
            status.className = 'tse-upload-status err';
        })
        .finally(function () {
            btn.disabled = false;
            btn.textContent = STR.upload || 'Carica foto';
        });
    };

    function showResult(type, text) {
        $('tse-result').innerHTML = '<div class="tse-result ' + type + '">' + escapeHtml(text) + '</div>';
    }

    // FIX 2026-06-27 marco — popup "modifiche ora online" con elenco vecchio → nuovo
    function showLivePopup(changes) {
        var emptyTxt = STR.liveEmpty || '(vuoto)';
        var rows = (changes || []).map(function (c) {
            var ov = (c.old === null || c.old === '') ? emptyTxt : c.old;
            var nv = (c.new === null || c.new === '') ? emptyTxt : c.new;
            return '<div class="tse-live-row">' +
                       '<span class="tse-live-lbl">' + escapeHtml(c.label) + '</span>' +
                       '<span class="tse-live-vals"><span class="tse-live-old">' + escapeHtml(ov) + '</span>' +
                       ' <span class="tse-live-arrow">→</span> ' +
                       '<span class="tse-live-new">' + escapeHtml(nv) + '</span></span>' +
                   '</div>';
        }).join('');
        var ov = document.createElement('div');
        ov.className = 'tse-live-overlay';
        ov.innerHTML =
            '<div class="tse-live-modal" role="dialog" aria-modal="true">' +
                '<div class="tse-live-title">' + escapeHtml(STR.liveTitle || '✅ Le tue modifiche sono ora online!') + '</div>' +
                '<div class="tse-live-list">' + rows + '</div>' +
                '<button type="button" class="tse-live-btn">' + escapeHtml(STR.liveClose || 'Chiudi') + '</button>' +
            '</div>';
        document.body.appendChild(ov);
        function close() { if (ov.parentNode) ov.parentNode.removeChild(ov); }
        ov.addEventListener('click', function (e) { if (e.target === ov) close(); });
        ov.querySelector('.tse-live-btn').addEventListener('click', close);
    }

    // FIX 2026-05-26 marco — evidenzia campi obbligatori mancanti
    function highlightMissingFields(talent) {
        var checks = {
            'f-altezza':  talent.altezza,
            'f-taglia':   talent.taglia,
            'f-scarpe':   talent.scarpe,
            'f-capelli':  talent.capelli,
            'f-telefono': talent.telefono
        };
        var hasSocial = !!(talent.instagram || talent.tiktok);
        Object.keys(checks).forEach(function (id) {
            var el = $(id);
            if (!el) return;
            var missing = !checks[id];
            el.classList.toggle('tse-missing', missing);
            var hint = el.parentElement.querySelector('.tse-missing-hint');
            if (missing && !hint) {
                var h = document.createElement('div');
                h.className = 'tse-missing-hint';
                h.textContent = 'Campo mancante — completalo';
                el.parentElement.appendChild(h);
            } else if (!missing && hint) {
                hint.remove();
            }
        });
        ['f-instagram','f-tiktok'].forEach(function (id) {
            var el = $(id);
            if (el) el.classList.toggle('tse-missing', !hasSocial);
        });
    }

    // FIX 2026-06-28 marco — elimina foto dal self-edit
    function talentMediaDelete(mediaId) {
        fetch('/crm_toagency/actions/talent-media-delete.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uuid: UUID, t: TOKEN, media_id: mediaId })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.ok) {
                // Rimuovi dalla cache locale e ricarica la griglia
                albumsData[currentAlbum] = (albumsData[currentAlbum] || []).filter(function (m) { return m.id !== mediaId; });
                renderAlbum(currentAlbum);
            } else {
                alert('Errore durante l\'eliminazione: ' + (d.error || '?'));
            }
        })
        .catch(function () { alert('Errore di rete, riprova.'); });
    }

    // FIX 2026-06-28 marco — sposta foto tra album
    function talentMediaMove(mediaId, targetAlbum) {
        fetch('/crm_toagency/actions/talent-media-move.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ uuid: UUID, t: TOKEN, media_id: mediaId, target_album: targetAlbum })
        })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            if (d.ok) {
                // Sposta dalla cache locale e aggiorna entrambi gli album
                var item = (albumsData[currentAlbum] || []).find(function (m) { return m.id === mediaId; });
                if (item) {
                    albumsData[currentAlbum] = albumsData[currentAlbum].filter(function (m) { return m.id !== mediaId; });
                    if (!albumsData[targetAlbum]) albumsData[targetAlbum] = [];
                    albumsData[targetAlbum].push(item);
                }
                renderAlbum(currentAlbum);
            } else {
                alert('Errore durante lo spostamento: ' + (d.error || '?'));
            }
        })
        .catch(function () { alert('Errore di rete, riprova.'); });
    }

    // FIX 2026-07-10 marco — drag&drop riordino foto (SortableJS lazy da CDN, salva su media-ordine-save.php)
    var _tseSortInst = null;
    var _tseSortLoading = false;
    var API_MEDIA_ORDER = (API_MEDIA_LS || '/crm_toagency/actions/talent-media-list.php').replace('talent-media-list.php', 'media-ordine-save.php');
    function _tseSaveOrder(album) {
        var grid = $('tse-album-grid'); if (!grid) return;
        var ids = Array.prototype.map.call(grid.querySelectorAll('.tse-album-thumb[data-id]'), function (el) {
            return parseInt(el.getAttribute('data-id'), 10);
        }).filter(function (n) { return n > 0; });
        if (!ids.length) return;
        // riallinea la cache locale al nuovo ordine (così un re-render non "torna indietro")
        var byId = {}; (albumsData[album] || []).forEach(function (m) { byId[m.id] = m; });
        albumsData[album] = ids.map(function (id) { return byId[id]; }).filter(Boolean);
        var body = new URLSearchParams();
        body.append('uuid', UUID); body.append('t', TOKEN);
        body.append('tipo', 'talent'); body.append('ids', JSON.stringify(ids));
        fetch(API_MEDIA_ORDER, { method: 'POST', body: body })
            .then(function (r) { return r.json(); })
            .then(function (d) { if (!d || !d.ok) console.warn('[tse] ordine non salvato', d); })
            .catch(function () { /* silenzioso: l'ordine a schermo resta comunque */ });
    }
    function _tseInitSort(grid, album) {
        if (!grid) return;
        if (typeof Sortable === 'undefined') {
            if (!_tseSortLoading) {
                _tseSortLoading = true;
                var s = document.createElement('script');
                s.src = 'https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js';
                s.onload = function () { _tseSortLoading = false; _tseInitSort($('tse-album-grid'), currentAlbum); };
                s.onerror = function () { _tseSortLoading = false; /* niente drag: degrada, resta tutto usabile */ };
                document.head.appendChild(s);
            }
            return;
        }
        if (_tseSortInst) { try { _tseSortInst.destroy(); } catch (e) {} _tseSortInst = null; }
        _tseSortInst = Sortable.create(grid, {
            animation: 150,
            draggable: '.tse-album-thumb',
            filter: '.tse-thumb-actions',     // i bottoni elimina/sposta non avviano il drag
            delay: 150, delayOnTouchOnly: true, // su touch serve una pressione, così lo scroll resta libero
            onEnd: function () { _tseSaveOrder(currentAlbum); }
        });
    }

    // FIX 2026-06-28 marco — lightbox anteprima foto (click su thumbnail)
    function talentShowLightbox(url) {
        var lb = $('tse-lb');
        var lbImg = $('tse-lb-img');
        if (!lb || !lbImg) { window.open(url, '_blank', 'noopener'); return; }
        lbImg.src = url;
        lb.style.display = 'flex';
    }
    // Chiudi lightbox con ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { var lb = $('tse-lb'); if (lb) lb.style.display = 'none'; }
    });

    function escapeHtml(s) {
        return String(s).replace(/[&<>"']/g, function (c) {
            return { '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[c];
        });
    }
    function escapeAttr(s) { return escapeHtml(s); }

    document.addEventListener('DOMContentLoaded', loadData);
})();
