/**
 * toa-ruoli.js — ticket #622 (14/10/2026 marco, chat CRM Tema ruoli talent)
 *
 * «Cosa sai fare?»: scelta dei lavori a icone, e «I tuoi lavori» con lo stato di ognuno
 * (Fatto / Manca la prova / In pausa). Regola di Marco (13/10): si possono scegliere tutti i ruoli,
 * ma un ruolo si propone ai clienti solo quando la persona lo dimostra caricando la prova.
 *
 * Nomi dei ruoli (per sesso), icone, cosa caricare e quante cose servono arrivano SOLO dal CRM
 * (lib/ruoli_prove.php), mai ricopiati qui:
 *   - /crm_toagency/actions/ruoli-catalogo.php?lang=xx  → pagine senza link personale (registrazione talent e crew)
 *   - talent-self-edit-load.php → ruoli_catalogo, ruoli_stato, crew, minorenne (modifica profilo talent)
 * Qui ci sono solo i testi dell'interfaccia, nelle 4 lingue.
 *
 * Tre parti, ognuna si attiva solo se trova la sua pagina:
 *   TOARuoliReg  — /registrati-talent/ (step 3)
 *   TOARuoliSE   — /talent-self-edit/ (chiamato da talent-self-edit.js)
 *   TOARuoliCrew — /registrati-crew/ (step 3)
 *   TOARuoliCrewSE — /crew-self-edit/?...&album=CODICE (arrivo dal bottone «Carica la prova»)
 * Se il CRM non risponde resta la scelta di prima (stessi campi del modulo): nessun vicolo cieco.
 */
(function () {
    'use strict';

    var API_CAT = '/crm_toagency/actions/ruoli-catalogo.php';

    function lang() {
        var l = (document.documentElement.getAttribute('lang') || 'it').substring(0, 2).toLowerCase();
        return ['it', 'en', 'fr', 'es'].indexOf(l) > -1 ? l : 'it';
    }

    var T = {
        tocca:       { it: 'Tocca tutti i lavori che sai fare. Puoi sceglierne quanti vuoi.', en: 'Tap every job you can do. Pick as many as you like.', fr: 'Touche tous les métiers que tu sais faire. Choisis-en autant que tu veux.', es: 'Toca todos los trabajos que sabes hacer. Elige los que quieras.' },
        chiediamo:   { it: 'Cosa ti chiederemo', en: 'What we will ask you for', fr: 'Ce qu’on te demandera', es: 'Lo que te pediremos' },
        proveDopo:   { it: 'Le prove le carichi dal tuo profilo, anche più tardi.', en: 'You can upload the proof from your profile, even later.', fr: 'Tu ajoutes les preuves depuis ton profil, même plus tard.', es: 'Las pruebas las subes desde tu perfil, también más tarde.' },
        nessuna:     { it: 'Non serve nessuna prova.', en: 'No proof needed.', fr: 'Aucune preuve nécessaire.', es: 'No hace falta ninguna prueba.' },
        crewOk:      { it: '✅ Ti abbiamo iscritto anche a: ', en: '✅ We have also signed you up for: ', fr: '✅ On t’a aussi inscrit pour : ', es: '✅ También te hemos inscrito para: ' },
        info:        { it: 'Ti proponiamo ai clienti per un lavoro <b>solo quando ci mostri che lo sai fare</b>. Basta caricare la prova.', en: 'We put you forward to clients for a job <b>only once you show us you can do it</b>. Just upload the proof.', fr: 'On te propose aux clients pour un métier <b>seulement quand tu nous montres que tu sais le faire</b>. Il suffit d’ajouter la preuve.', es: 'Te proponemos a los clientes para un trabajo <b>solo cuando nos enseñas que sabes hacerlo</b>. Basta con subir la prueba.' },
        fatto:       { it: 'Fatto', en: 'Done', fr: 'Validé', es: 'Hecho' },
        manca:       { it: 'Manca la prova', en: 'Proof missing', fr: 'Preuve manquante', es: 'Falta la prueba' },
        pausa:       { it: 'In pausa', en: 'On hold', fr: 'En pause', es: 'En pausa' },
        giaProposto: { it: 'Ti proponiamo già per questo lavoro.', en: 'We already put you forward for this job.', fr: 'On te propose déjà pour ce métier.', es: 'Ya te proponemos para este trabajo.' },
        pausaTxt:    { it: 'Per ora non ti proponiamo per questo lavoro.', en: 'For now we are not putting you forward for this job.', fr: 'Pour l’instant on ne te propose pas pour ce métier.', es: 'Por ahora no te proponemos para este trabajo.' },
        di:          { it: 'di', en: 'of', fr: 'sur', es: 'de' },
        carica:      { it: 'Carica la prova', en: 'Upload the proof', fr: 'Ajouter la preuve', es: 'Subir la prueba' },
        modifica:    { it: 'Aggiungi o togli lavori', en: 'Add or remove jobs', fr: 'Ajouter ou retirer des métiers', es: 'Añadir o quitar trabajos' },
        salva:       { it: 'Salva i lavori', en: 'Save my jobs', fr: 'Enregistrer mes métiers', es: 'Guardar mis trabajos' },
        nonSalvato:  { it: 'Hai cambiato i lavori: premi «Salva i lavori».', en: 'You changed your jobs: press "Save my jobs".', fr: 'Tu as changé tes métiers : appuie sur « Enregistrer mes métiers ».', es: 'Has cambiado tus trabajos: pulsa «Guardar mis trabajos».' },
        crewLink:    { it: 'Completa la tua scheda staff e backstage →', en: 'Complete your staff & backstage profile →', fr: 'Complète ta fiche staff et backstage →', es: 'Completa tu ficha de staff y backstage →' },
        crewNoLink:  { it: 'Salva i lavori: poi trovi qui il link per caricare la prova.', en: 'Save your jobs: then the upload link appears here.', fr: 'Enregistre tes métiers : le lien pour ajouter la preuve apparaîtra ici.', es: 'Guarda tus trabajos: luego aquí verás el enlace para subir la prueba.' },
        altri:       { it: 'Mostra altri album', en: 'Show other albums', fr: 'Voir les autres albums', es: 'Ver otros álbumes' },
        meno:        { it: 'Mostra meno album', en: 'Show fewer albums', fr: 'Voir moins d’albums', es: 'Ver menos álbumes' },
        modalTit:    { it: 'Questo album è per chi fa: {r}', en: 'This album is for: {r}', fr: 'Cet album est pour : {r}', es: 'Este álbum es para: {r}' },
        modalTxt:    { it: 'Non hai scelto questo lavoro. Vuoi aggiungerlo, oppure mettere la foto in «Altre foto»?', en: 'You haven’t picked this job. Add it, or put the photo in "Other photos"?', fr: 'Tu n’as pas choisi ce métier. Tu l’ajoutes, ou tu mets la photo dans « Autres photos » ?', es: 'No has elegido este trabajo. ¿Lo añades o pones la foto en «Otras fotos»?' },
        modalAdd:    { it: 'Aggiungi «{r}»', en: 'Add "{r}"', fr: 'Ajouter « {r} »', es: 'Añadir «{r}»' },
        modalCasual: { it: 'Mettila in «Altre foto»', en: 'Put it in "Other photos"', fr: 'La mettre dans « Autres photos »', es: 'Ponerla en «Otras fotos»' },
        annulla:     { it: 'Annulla', en: 'Cancel', fr: 'Annuler', es: 'Cancelar' },
        pronto:      { it: '✅ Il tuo profilo è già pronto per essere proposto ai clienti', en: '✅ Your profile is already ready to be put forward to clients', fr: '✅ Ton profil est déjà prêt à être proposé aux clients', es: '✅ Tu perfil ya está listo para proponerlo a los clientes' },
        complPiu:    { it: 'Completezza {p}%: più è completo, più possibilità hai', en: 'Completeness {p}%: the more complete, the more chances you get', fr: 'Complétude {p} % : plus il est complet, plus tu as de chances', es: 'Completado {p}%: cuanto más completo, más posibilidades tienes' },
        provaPer:    { it: 'Carica qui la prova per:', en: 'Upload the proof here for:', fr: 'Ajoute ici la preuve pour :', es: 'Sube aquí la prueba para:' },
        provaAuto:   { it: 'Le foto e i video che carichi qui vanno da soli in questo lavoro.', en: 'Photos and videos you upload here go straight into this job.', fr: 'Les photos et vidéos ajoutées ici vont directement dans ce métier.', es: 'Las fotos y vídeos que subas aquí van directamente a este trabajo.' },
        complLbl:    { it: 'Completezza: più è completo, più possibilità hai', en: 'Completeness: the more complete, the more chances you get', fr: 'Complétude : plus il est complet, plus tu as de chances', es: 'Completado: cuanto más completo, más posibilidades tienes' }
    };
    function t(k, rep) {
        var o = T[k]; var s = o ? (o[lang()] || o.it) : k;
        if (rep) Object.keys(rep).forEach(function (x) { s = s.split('{' + x + '}').join(rep[x]); });
        return s;
    }
    function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
    function $(id) { return document.getElementById(id); }
    function cap(s) { s = String(s || ''); return s.charAt(0).toUpperCase() + s.slice(1); }

    // Icone di servizio: Tabler Icons (MIT), stesso stile a linee delle icone dei ruoli del CRM
    var SV = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">';
    var IC = {
        info:  SV + '<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M12 9h.01"/><path d="M11 12h1v4h1"/></svg>',
        ok:    SV + '<path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M9 12l2 2l4 -4"/></svg>',
        clock: SV + '<path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"/><path d="M12 7v5l3 3"/></svg>',
        ban:   SV + '<path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/><path d="M5.7 5.7l12.6 12.6"/></svg>',
        cam:   SV + '<path d="M12 20h-7a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v3.5"/><path d="M16 19h6"/><path d="M19 16v6"/><path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"/></svg>'
    };

    var _catP = null;
    function loadCatalog() {
        if (_catP) return _catP;
        _catP = fetch(API_CAT + '?lang=' + lang(), { credentials: 'same-origin' })
            .then(function (r) { return r.json(); })
            .then(function (d) { if (!d || !d.success || !d.gruppi) throw new Error('catalogo'); return d.gruppi; });
        _catP.catch(function () { _catP = null; });
        return _catP;
    }
    // «Truccatore/Truccatrice»: si va a capo sulla barra, non a metà parola
    function aCapo(n) { return String(n || '').replace(/\//g, '/\u200B'); }
    function nomeSesso(r, sesso) {
        var s = String(sesso || '').toUpperCase().charAt(0);
        return s === 'F' ? (r.nome_f || r.nome) : (s === 'M' ? (r.nome_m || r.nome) : r.nome);
    }
    function tile(r, nome, inputName, checked) {
        return '<label class="toa-rt' + (checked ? ' checked' : '') + '" data-code="' + esc(r.code) + '">'
             + '<input type="checkbox"' + (inputName ? ' name="' + esc(inputName) + '"' : '') + ' value="' + esc(r.code) + '"' + (checked ? ' checked' : '') + '>'
             + '<span class="toa-rt-ic">' + (r.svg || '') + '</span><span class="toa-rt-n">' + esc(aCapo(nome)) + '</span><span class="toa-rt-ck" aria-hidden="true"></span></label>';
    }
    // Trasforma un chip già presente nella pagina (stesso input, stessi ascoltatori) in una piastrella con icona
    function aTile(label, r, nome) {
        if (!label.classList.contains('toa-rt') || !label.querySelector('.toa-rt-n')) {
            var inp = label.querySelector('input');
            Array.prototype.slice.call(label.childNodes).forEach(function (n) { if (n !== inp) label.removeChild(n); });
            label.classList.add('toa-rt');
            label.insertAdjacentHTML('beforeend', '<span class="toa-rt-ic"></span><span class="toa-rt-n"></span><span class="toa-rt-ck" aria-hidden="true"></span>');
        }
        label.setAttribute('data-code', r.code);
        label.querySelector('.toa-rt-ic').innerHTML = r.svg || '';
        label.querySelector('.toa-rt-n').textContent = aCapo(nome);
        var i2 = label.querySelector('input'); label.classList.toggle('checked', !!(i2 && i2.checked));
    }
    function etaDa(v) {
        if (!v || !/^\d{4}-\d{2}-\d{2}/.test(v)) return null;
        var b = new Date(v.substring(0, 10) + 'T00:00:00'); if (isNaN(b)) return null;
        var o = new Date(), e = o.getFullYear() - b.getFullYear(), m = o.getMonth() - b.getMonth();
        if (m < 0 || (m === 0 && o.getDate() < b.getDate())) e--;
        return e;
    }
    function proveHtml(ruoli) {
        if (!ruoli.length) return '';
        return '<div class="toa-rt-prove-t">' + esc(t('chiediamo')) + '</div><ul>' + ruoli.map(function (x) {
            return '<li>' + (x.r.svg || '') + '<span><b>' + esc(x.nome) + '</b> — ' + esc(x.r.servono > 0 ? x.r.prova : t('nessuna')) + '</span></li>';
        }).join('') + '</ul><p class="toa-rt-prove-n">' + esc(t('proveDopo')) + '</p>';
    }

    /* ═══════════════ 1. REGISTRAZIONE TALENT ═══════════════ */
    var REG = { byCode: {} };
    function initReg() {
        var box = $('toaTalentRuoliImmagine'), grid = $('toaTalentCategoriesImmagine'), crewBox = $('toaTalentRuoliCrew'), proveBox = $('toaTalentRuoliProve');
        if (!box || !grid || !crewBox) return;
        var form = box.closest('form') || document;
        var dob = form.querySelector('input[name="data_nascita"]');
        var sessoInp = form.querySelector('input[name="sesso"]');
        function sesso() { return sessoInp ? sessoInp.value : ''; }
        function aggiorna() {
            var scelti = [];
            box.querySelectorAll('label.toa-rt[data-code] input:checked').forEach(function (cb) {
                var r = REG.byCode[cb.value]; if (r) scelti.push({ r: r, nome: nomeSesso(r, sesso()) });
            });
            if (proveBox) { proveBox.innerHTML = proveHtml(scelti); proveBox.hidden = !scelti.length; }
            if (scelti.length) { var er = box.querySelector('.toa-talent-error-msg'); if (er) er.classList.remove('show'); }
        }
        function aggiornaNomi() {
            box.querySelectorAll('label.toa-rt[data-code]').forEach(function (l) {
                var r = REG.byCode[l.getAttribute('data-code')]; var n = l.querySelector('.toa-rt-n');
                if (r && n) n.textContent = aCapo(nomeSesso(r, sesso()));
            });
            aggiorna();
        }
        function aggiornaMinore() {
            var e = etaDa(dob ? dob.value : ''); var min = (e !== null && e < 18);
            crewBox.hidden = min;
            crewBox.querySelectorAll('input').forEach(function (cb) {
                cb.disabled = min;
                if (min && cb.checked) { cb.checked = false; cb.closest('label').classList.remove('checked'); }
            });
            aggiorna();
        }
        loadCatalog().then(function (gruppi) {
            REG.byCode = {};
            gruppi.forEach(function (g) { g.ruoli.forEach(function (r) { REG.byCode[r.code] = r; }); });
            var html = '';
            gruppi.forEach(function (g) {
                if (g.code === 'camera') {
                    var gt = box.querySelector('.toa-rt-gt[data-gt="camera"]'); if (gt) gt.textContent = g.gruppo;
                    g.ruoli.forEach(function (r) {
                        var inp = grid.querySelector('input[value="' + r.code + '"]');
                        if (!inp) return; // ruolo talent non ancora nel modulo: non lo invento qui
                        var lab = inp.closest('label'); aTile(lab, r, nomeSesso(r, sesso())); grid.appendChild(lab);
                    });
                    return;
                }
                html += '<div class="toa-rt-gt">' + esc(g.gruppo) + '</div><div class="toa-rt-grid">'
                      + g.ruoli.map(function (r) { return tile(r, nomeSesso(r, sesso()), 'ruoli_staff[]', false); }).join('') + '</div>';
            });
            crewBox.innerHTML = html;
            crewBox.querySelectorAll('input').forEach(function (cb) {
                cb.addEventListener('change', function () { cb.closest('label').classList.toggle('checked', cb.checked); aggiorna(); });
            });
            box.classList.add('toa-rt-pronto');
            aggiornaMinore();
        }).catch(function () { /* resta la scelta di prima (solo ruoli talent) */ });
        grid.addEventListener('change', aggiorna);
        if (dob) { dob.addEventListener('change', aggiornaMinore); dob.addEventListener('input', aggiornaMinore); }
        if (sessoInp) {
            var grp = sessoInp.closest('.toa-talent-toggle-group');
            if (grp) grp.addEventListener('click', function () { setTimeout(aggiornaNomi, 0); });
        }
    }
    window.TOARuoliReg = {
        // Schermata di successo: risposta del CRM crew = creata|aggiornata|invariata|saltata
        successo: function (esito) {
            var el = $('toaTalentCrewOk'); if (!el) return;
            var nomi = [];
            document.querySelectorAll('#toaTalentRuoliCrew input:checked').forEach(function (cb) {
                var l = cb.closest('label'); var n = l && l.querySelector('.toa-rt-n'); if (n) nomi.push(n.textContent.replace(/\u200B/g, ''));
            });
            if ((esito === 'creata' || esito === 'aggiornata') && nomi.length) { el.textContent = t('crewOk') + nomi.join(', '); el.hidden = false; }
            else el.hidden = true;
        }
    };

    /* ═══════════════ 2. MODIFICA PROFILO TALENT ═══════════════ */
    // Dove si carica la prova dei ruoli talent (album del self-edit); '#video' = sezione video
    var PROVA_ALBUM = { model: 'polaroid', comparsa: 'polaroid', actor: 'portfolio_cinema', hostess: 'eventi', influencer: '#video', ugc_creator: '#video' };
    var SE = { byCode: {}, stato: [], crew: {}, minor: false, counts: {}, showAll: false, wired: false, roleAlbums: {}, albumRoles: {} };

    function seCfgAlbums() {
        var defs = ((window.talentEditConfig || {}).strings || {}).albumDefs || [];
        SE.roleAlbums = {}; SE.albumRoles = {};
        defs.forEach(function (a) {
            if (!a.roles || a.roles === '*') return;
            SE.albumRoles[a.code] = a.roles.split(',').map(function (x) { return x.trim(); });
            SE.albumRoles[a.code].forEach(function (r) { (SE.roleAlbums[r] = SE.roleAlbums[r] || []).push(a.code); });
        });
    }
    function seTalentScelti() {
        var out = []; var g = $('f-ruoli'); if (!g) return out;
        g.querySelectorAll('input:checked').forEach(function (cb) { if (cb.value !== 'bambino') out.push(cb.value); });
        return out;
    }
    function seStaffScelti() {
        var out = []; var g = $('f-ruoli-staff'); if (!g) return out;
        g.querySelectorAll('input:checked').forEach(function (cb) { out.push(cb.value); });
        return out;
    }
    function seBuildTiles(cat) {
        var fr = $('f-ruoli'), fs = $('f-ruoli-staff');
        if (!fr) return;
        var html = '';
        cat.forEach(function (g) {
            if (g.code === 'camera') {
                var gt = document.querySelector('#tse-lavori .toa-rt-gt[data-gt="camera"]'); if (gt) gt.textContent = g.gruppo;
                g.ruoli.forEach(function (r) {
                    var inp = fr.querySelector('input[value="' + r.code + '"]'); if (!inp) return;
                    var lab = inp.closest('label'); aTile(lab, r, r.nome); fr.appendChild(lab);
                });
                var bb = fr.querySelector('input[value="bambino"]'); if (bb) fr.appendChild(bb.closest('label'));
                return;
            }
            html += '<div class="toa-rt-gt">' + esc(g.gruppo) + '</div><div class="toa-rt-grid">'
                  + g.ruoli.map(function (r) { return tile(r, r.nome, '', (SE.crew.ruoli_staff || []).indexOf(r.code) > -1); }).join('') + '</div>';
        });
        if (fs) {
            if (SE.minor) { fs.innerHTML = ''; fs.hidden = true; fs.removeAttribute('data-ready'); }
            else { fs.innerHTML = html; fs.hidden = false; fs.setAttribute('data-ready', '1'); }
        }
    }
    function seCard(s) {
        var cls = s.stato === 'confermato' ? 'ok' : (s.stato === 'bloccato' ? 'pausa' : 'manca');
        var st = cls === 'ok' ? IC.ok + esc(t('fatto')) : (cls === 'pausa' ? IC.ban + esc(t('pausa')) : IC.clock + esc(t('manca')));
        var serv = parseInt(s.servono, 10) || 0, car = parseInt(s.caricati, 10) || 0;
        var p = cls === 'pausa' ? t('pausaTxt') : (cls === 'ok' ? (serv > 0 ? t('giaProposto') : t('nessuna')) : cap(s.prova));
        var dots = '';
        if (serv > 0 && cls !== 'pausa') {
            var on = Math.min(car, serv);
            if (serv <= 6) { for (var i = 0; i < serv; i++) dots += '<i class="' + (i < on ? 'on' : '') + '"></i>'; }
            dots = '<div class="toa-lav-dots">' + dots + '<span>' + on + ' ' + esc(t('di')) + ' ' + serv + '</span></div>';
        }
        var go = cls === 'manca' ? '<button type="button" class="toa-lav-go" data-go="' + esc(s.code) + '">' + IC.cam + esc(t('carica')) + '</button>' : '';
        return '<div class="toa-lav-card ' + cls + '"><div class="toa-lav-h"><span class="toa-rt-ic">' + (s.svg || '') + '</span><div><div class="toa-lav-nome">' + esc(s.nome) + '</div><div class="toa-lav-st">' + st + '</div></div></div>'
             + '<p class="toa-lav-p">' + esc(p) + '</p>' + dots + go + '</div>';
    }
    function seRenderStato() {
        var wrap = document.querySelector('.tse-wrap');
        var vuoto = !SE.stato.length && !seTalentScelti().length && !seStaffScelti().length;
        if (wrap) wrap.classList.toggle('toa-noruoli', vuoto);
        var w = $('tse-lavori-vuoto'); if (w) w.hidden = !vuoto;
        var box = $('tse-lavori-stato');
        if (box) {
            var ord = { da_dimostrare: 0, confermato: 1, bloccato: 2 };
            var lista = SE.stato.slice().sort(function (a, b) { return (ord[a.stato] || 0) - (ord[b.stato] || 0); });
            box.innerHTML = lista.map(seCard).join('');
        }
        var cl = $('tse-lavori-crew');
        if (cl) {
            var haCrew = SE.stato.some(function (s) { return s.settore === 'crew'; });
            if (SE.crew.crew_link && haCrew) { cl.href = SE.crew.crew_link; cl.textContent = t('crewLink'); cl.hidden = false; } else cl.hidden = true;
        }
        var ap = $('tse-lavori-apri'), sc = $('tse-lavori-scelta');
        if (vuoto) { if (sc) sc.hidden = false; if (ap) ap.hidden = true; } else if (ap) ap.hidden = false;
    }
    function seFilterTabs() {
        var box = $('tse-album-tabs'); if (!box) return;
        var want = ['polaroid', 'casual'];
        seTalentScelti().forEach(function (r) { (SE.roleAlbums[r] || []).forEach(function (a) { if (want.indexOf(a) < 0) want.push(a); }); });
        Object.keys(SE.counts).forEach(function (a) { if (SE.counts[a] > 0 && want.indexOf(a) < 0) want.push(a); });
        var nasc = 0;
        box.querySelectorAll('.tse-album-tab').forEach(function (tab) {
            var a = tab.getAttribute('data-album');
            var serve = want.indexOf(a) > -1 || tab.classList.contains('active');
            if (!serve && tab.style.display !== 'none') nasc++;
            tab.hidden = !serve && !SE.showAll;
        });
        var more = box.querySelector('.toa-rt-more');
        if (!more) {
            more = document.createElement('button'); more.type = 'button'; more.className = 'toa-rt-more';
            more.addEventListener('click', function () { SE.showAll = !SE.showAll; seFilterTabs(); });
        }
        box.appendChild(more);
        more.textContent = SE.showAll ? t('meno') : t('altri') + ' (' + nasc + ')';
        more.hidden = !nasc;
    }
    function seCambiato() {
        var m = $('tse-lavori-msg'); if (m) m.textContent = t('nonSalvato');
        ['f-ruoli', 'f-ruoli-staff'].forEach(function (id) {
            var g = $(id); if (g) g.querySelectorAll('label.toa-rt').forEach(function (l) { var i = l.querySelector('input'); l.classList.toggle('checked', !!(i && i.checked)); });
        });
        var wrap = document.querySelector('.tse-wrap');
        if (wrap && (seTalentScelti().length || seStaffScelti().length)) wrap.classList.remove('toa-noruoli');
        seFilterTabs();
    }
    function seVaiProva(code) {
        var s = null; SE.stato.forEach(function (x) { if (x.code === code) s = x; });
        if (s && s.settore === 'crew') {
            if (SE.crew.crew_link) { window.location.href = SE.crew.crew_link + '&album=' + encodeURIComponent(code); return; }
            var m = $('tse-lavori-msg'); if (m) m.textContent = t('crewNoLink'); return;
        }
        var alb = PROVA_ALBUM[code] || 'polaroid';
        if (alb === '#video') { var v = $('tse-video-section'); if (v) v.scrollIntoView({ behavior: 'smooth', block: 'start' }); return; }
        var tab = document.querySelector('.tse-album-tab[data-album="' + alb + '"]');
        if (!tab || tab.style.display === 'none') alb = 'polaroid';
        if (typeof window.talentAlbumSwitch === 'function') window.talentAlbumSwitch(alb);
        seFilterTabs();
        var fsec = $('tse-foto-section'); if (fsec) fsec.scrollIntoView({ behavior: 'smooth', block: 'start' });
        if (typeof window.talentToggleUpload === 'function') setTimeout(function () { window.talentToggleUpload(true); }, 350);
    }
    function seWire() {
        var ap = $('tse-lavori-apri'), sc = $('tse-lavori-scelta');
        if (ap) { ap.textContent = t('modifica'); ap.addEventListener('click', function () { if (sc) { sc.hidden = !sc.hidden; if (!sc.hidden) sc.scrollIntoView({ behavior: 'smooth', block: 'start' }); } }); }
        var sb = $('tse-lavori-salva'); if (sb) { sb.textContent = t('salva'); sb.addEventListener('click', function () { window.TOARuoliSE.salva(); }); }
        ['f-ruoli', 'f-ruoli-staff'].forEach(function (id) { var g = $(id); if (g) g.addEventListener('change', seCambiato); });
        var st = $('tse-lavori-stato');
        if (st) st.addEventListener('click', function (e) { var b = e.target.closest('[data-go]'); if (b) seVaiProva(b.getAttribute('data-go')); });
        var inf = $('tse-lavori-info'); if (inf) inf.innerHTML = IC.info + '<span>' + t('info') + '</span>';
        var lead = $('tse-lavori-lead'); if (lead) lead.textContent = t('tocca');
    }
    function seModal(roleCode, onAdd, onCasual) {
        var r = SE.byCode[roleCode]; var nome = r ? r.nome : roleCode;
        var ov = document.createElement('div'); ov.className = 'toa-rt-modal'; ov.setAttribute('role', 'dialog'); ov.setAttribute('aria-modal', 'true');
        ov.innerHTML = '<div class="toa-rt-modal-box"><b>' + esc(t('modalTit', { r: nome })) + '</b><p>' + esc(t('modalTxt')) + '</p>'
            + '<button type="button" class="p" data-a="add">' + esc(t('modalAdd', { r: nome })) + '</button>'
            + '<button type="button" data-a="casual">' + esc(t('modalCasual')) + '</button>'
            + '<button type="button" data-a="no">' + esc(t('annulla')) + '</button></div>';
        function chiudi() { if (ov.parentNode) ov.parentNode.removeChild(ov); }
        ov.addEventListener('click', function (e) {
            if (e.target === ov) { chiudi(); return; }
            var b = e.target.closest('button[data-a]'); if (!b) return;
            chiudi();
            var a = b.getAttribute('data-a');
            if (a === 'add') onAdd(); else if (a === 'casual') onCasual();
        });
        document.body.appendChild(ov);
        var p = ov.querySelector('button.p'); if (p) p.focus();
    }

    window.TOARuoliSE = {
        // Da talent-self-edit.js (loadData) con la risposta di talent-self-edit-load.php
        init: function (d) {
            SE.byCode = {}; SE.stato = d.ruoli_stato || []; SE.crew = d.crew || {}; SE.minor = !!d.minorenne;
            (d.ruoli_catalogo || []).forEach(function (g) { (g.ruoli || []).forEach(function (r) { SE.byCode[r.code] = r; }); });
            seCfgAlbums();
            if (d.ruoli_catalogo && d.ruoli_catalogo.length) seBuildTiles(d.ruoli_catalogo);
            if (!SE.wired) { seWire(); SE.wired = true; }
            var m = $('tse-lavori-msg'); if (m) m.textContent = '';
            seRenderStato(); seFilterTabs();
            var blk = $('tse-lavori'); if (blk) blk.style.display = 'block';
        },
        // Da loadCompletezza (talent-profilo-stato.php): stato aggiornato dopo un caricamento
        setStato: function (arr) { if (!Array.isArray(arr)) return; SE.stato = arr; seRenderStato(); },
        // Da loadMedia: quante foto ci sono in ogni album (un album con foto resta sempre visibile)
        onMedia: function (albumsData) { SE.counts = {}; Object.keys(albumsData || {}).forEach(function (a) { SE.counts[a] = (albumsData[a] || []).length; }); seFilterTabs(); },
        // Da renderAlbum: guida Pola solo nell'album Pola
        onAlbum: function (album) { var g = $('tse-pola-guida'); if (g) g.hidden = (album !== 'polaroid'); seFilterTabs(); },
        // Da talentUploadGo: foto in un album di un lavoro non scelto → finestra con 3 scelte. true = upload fermato qui
        fermaUpload: function (album) {
            var roles = SE.albumRoles[album]; if (!roles || !roles.length) return false;
            var scelti = seTalentScelti();
            if (roles.some(function (r) { return scelti.indexOf(r) > -1; })) return false;
            seModal(roles[0], function () {
                var inp = document.querySelector('#f-ruoli input[value="' + roles[0] + '"]');
                if (inp) { inp.checked = true; seCambiato(); }
                window.TOARuoliSE.salva();
                if (typeof window.talentUploadGo === 'function') window.talentUploadGo();
            }, function () {
                if (typeof window.talentAlbumSwitch === 'function') window.talentAlbumSwitch('casual');
                seFilterTabs();
                if (typeof window.talentUploadGo === 'function') window.talentUploadGo();
            });
            return true;
        },
        // I lavori da staff/crew si mandano solo se l'elenco è arrivato dal CRM (lista completa = toglie i non spuntati)
        staffPayload: function () { var fs = $('f-ruoli-staff'); return (fs && fs.getAttribute('data-ready') === '1') ? seStaffScelti() : null; },
        salva: function () { var m = $('tse-lavori-msg'); if (m) m.textContent = ''; if (typeof window.talentEditSubmit === 'function') window.talentEditSubmit(); },
        // Riga verde «profilo già pronto» + testo della barra di completezza (talent-profilo-stato.php → presentabile)
        pronto: function (pres) {
            var ok = !!(pres && pres.ok);
            var el = $('tse-pronto'); if (el) { el.textContent = t('pronto'); el.hidden = !ok; }
            var lb = $('tse-compl-label'); if (lb && ok) lb.textContent = t('complLbl');
            return ok;
        },
        rigaPronto: function () { return t('pronto'); },
        rigaCompl: function (pct) { return t('complPiu', { p: pct }); }
    };

    /* ═══════════════ 3. REGISTRAZIONE CREW ═══════════════ */
    function initCrew() {
        var grid = $('toaCrewCategories'); if (!grid) return;
        loadCatalog().then(function (gruppi) {
            var frag = document.createDocumentFragment();
            gruppi.forEach(function (g) {
                if (g.code === 'camera') return;
                var tit = document.createElement('div'); tit.className = 'toa-rt-gt'; tit.textContent = g.gruppo;
                var gg = document.createElement('div'); gg.className = 'toa-rt-grid';
                g.ruoli.forEach(function (r) {
                    var inp = grid.querySelector('input[value="' + r.code + '"]'); if (!inp) return;
                    var lab = inp.closest('label'); var badge = lab.querySelector('.age-badge');
                    aTile(lab, r, r.nome);
                    if (badge) lab.appendChild(badge);
                    gg.appendChild(lab); // stesso elemento: restano i suoi ascoltatori (crew-form.js)
                });
                if (gg.children.length) { frag.appendChild(tit); frag.appendChild(gg); }
            });
            var resto = Array.prototype.slice.call(grid.children).filter(function (el) { return el.tagName === 'LABEL'; });
            grid.innerHTML = ''; grid.classList.add('toa-rt-crewreg');
            grid.appendChild(frag);
            if (resto.length) { var gx = document.createElement('div'); gx.className = 'toa-rt-grid'; resto.forEach(function (l) { gx.appendChild(l); }); grid.appendChild(gx); }
        }).catch(function () { /* resta l'elenco del modulo */ });
    }

    /* ═══════════════ 4. MODIFICA SCHEDA CREW: arrivo da «Carica la prova» (&album=CODICE) ═══════════════ */
    window.TOARuoliCrewSE = {
        banner: function (code) {
            var el = $('f-prova-ruolo'); if (!el || !code) return;
            loadCatalog().then(function (gruppi) {
                var r = null; gruppi.forEach(function (g) { g.ruoli.forEach(function (x) { if (x.code === code) r = x; }); });
                if (!r) return;
                el.innerHTML = '<div class="toa-lav-h"><span class="toa-rt-ic">' + (r.svg || '') + '</span><div><div class="toa-lav-nome">' + esc(t('provaPer')) + ' ' + esc(r.nome) + '</div></div></div>'
                    + '<p class="toa-lav-p">' + esc(cap(r.servono > 0 ? r.prova : t('nessuna'))) + '</p><p class="toa-rt-prove-n">' + esc(t('provaAuto')) + '</p>';
                el.hidden = false;
                if (!el.getAttribute('data-visto')) { el.setAttribute('data-visto', '1'); try { el.scrollIntoView({ behavior: 'smooth', block: 'center' }); } catch (e) {} }
            }).catch(function () {});
        }
    };

    function avvio() { initReg(); initCrew(); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', avvio); else avvio();
})();
