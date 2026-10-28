/**
 * crew-cv.js — ticket #710 (parte tema del #708): bio guidata / CV per crew bartender, cameriere, autista.
 * Testi: window.TOA_CREW_CV (stampato da inc/crew-cv.php, 4 lingue). Nessun testo scritto qui.
 * Endpoint CRM (contratto fisso): POST multipart /crm_toagency/actions/crew-cv-upload.php
 *   uuid, t, azione=carica|elimina; carica: file, dichiarazione_legale=1, veridicita=1.
 * Il CV non si scarica mai dal tema: lo vede solo lo staff.
 */
(function () {
    'use strict';
    var CFG = window.TOA_CREW_CV || {};
    var S = CFG.s || {}, E = CFG.err || {};
    var URL_CV = '/crm_toagency/actions/crew-cv-upload.php';
    var CV_ROLES = ['bartender', 'cameriere', 'autista'];
    var MAX = 5 * 1024 * 1024;
    function $(id) { return document.getElementById(id); }

    function hasCvRole(codes) {
        return (codes || []).some(function (c) { return CV_ROLES.indexOf(String(c).toLowerCase()) > -1; });
    }
    function errText(code) { return E[code] || E.generic || ''; }
    function checkFile(f) {
        if (!f) return 'no_file';
        if (!f.size) return 'file_vuoto';
        if (f.size > MAX) return 'troppo_grande';
        if (!/\.(pdf|docx|txt)$/i.test(f.name || '')) return 'formato_non_ammesso';
        return '';
    }
    function post(fd) {
        return fetch(URL_CV, { method: 'POST', body: fd, credentials: 'same-origin' })
            .then(function (r) { return r.text(); })
            .then(function (t) { try { return JSON.parse(t); } catch (e) { return { ok: false, error: 'invalid_response' }; } })
            .catch(function () { return { ok: false, error: 'network' }; });
    }
    function upload(uuid, token, file) {
        var fd = new FormData();
        fd.append('uuid', uuid); fd.append('t', token); fd.append('azione', 'carica');
        fd.append('file', file); fd.append('dichiarazione_legale', '1'); fd.append('veridicita', '1');
        return post(fd);
    }
    function remove(uuid, token) {
        var fd = new FormData();
        fd.append('uuid', uuid); fd.append('t', token); fd.append('azione', 'elimina');
        return post(fd);
    }
    // CV senza testo leggibile (scansione/foto): salvato ma serve anche la presentazione scritta
    function isScan(res) { return !!(res && res.ok && res.bio_generata === false && /^cv_/.test(res.motivo || '')); }
    function fmtDate(s) {
        if (!s) return '';
        var d = new Date(String(s).replace(' ', 'T'));
        if (isNaN(d.getTime())) return '';
        try { return d.toLocaleDateString(CFG.lang || 'it', { day: 'numeric', month: 'short', year: 'numeric' }); } catch (e) { return ''; }
    }
    function setStatus(text, kind) {
        var el = $('toaCvStatus'); if (!el) return;
        el.textContent = text || ''; el.className = 'toa-cv-status' + (kind ? ' ' + kind : '');
    }
    function pick() { var i = $('toaCvInput'); if (i) i.click(); }
    function showName() {
        var i = $('toaCvInput'), f = $('toaCvFile'); if (!f) return;
        f.textContent = (i && i.files && i.files[0]) ? ('📄 ' + i.files[0].name) : '';
    }
    function setVisible(on) { var b = $('toaCv'); if (b) b.hidden = !on; }

    /* ───────── REGISTRAZIONE ───────── */
    var Reg = {
        file: function () { var i = $('toaCvInput'); return (i && i.files && i.files[0]) || null; },
        // mostra/nasconde il blocco in base ai ruoli scelti
        sync: function (codes) { setVisible(hasCvRole(codes)); },
        // true se ok; altrimenti scrive l'errore nel contenitore e ritorna false
        validate: function (codes, bioText, errBox) {
            if (!hasCvRole(codes)) return true;
            function fail(msg) { if (errBox) { errBox.textContent = msg; errBox.classList.add('show'); } return false; }
            var f = Reg.file();
            if (!f) return (String(bioText || '').trim() ? true : fail(S.need_one));
            var c = checkFile(f); if (c) return fail(errText(c));
            var chk = $('toaCvConsent'); if (!chk || !chk.checked) return fail(S.need_consent);
            return true;
        },
        // dopo registra-crew.php: carica il CV. Non rifiuta mai; ritorna {note} da mostrare nel messaggio finale.
        afterRegister: function (codes, uuid, token) {
            var f = Reg.file();
            if (!hasCvRole(codes) || !f || !uuid || !token) return Promise.resolve({ note: '' });
            return upload(uuid, token, f).then(function (res) {
                if (!res.ok) return { note: S.after_fail + ' ' + errText(res.error) };
                return { note: isScan(res) ? S.scan_note : '' };
            });
        },
        reset: function () { var i = $('toaCvInput'), c = $('toaCvConsent'); if (i) i.value = ''; if (c) c.checked = false; showName(); setStatus(''); setVisible(false); }
    };

    /* ───────── MODIFICA PROFILO ───────── */
    var SE = { uuid: '', token: '', busy: false, armed: null };
    function seRender(cv) {
        var cur = $('toaCvCurrent'), txt = $('toaCvCurrentTxt'), btn = $('toaCvPick');
        var has = !!(cv && cv.presente);
        if (cur) cur.hidden = !has;
        if (btn) btn.hidden = has;
        if (has && txt) { var d = fmtDate(cv.caricato); txt.textContent = (cv.nome || '') + (d ? ' · ' + d : ''); }
        var rm = $('toaCvRemove'); if (rm) rm.textContent = S.remove;
    }
    function seNeedConsent() {
        var chk = $('toaCvConsent');
        if (chk && chk.checked) return false;
        setStatus(S.need_consent, 'err'); return true;
    }
    SE.init = function (opt) {
        SE.uuid = opt.uuid; SE.token = opt.token;
        if (!hasCvRole(opt.roles)) { setVisible(false); return; }
        setVisible(true); seRender(opt.cv);
        if (SE.bound) return; // loadData() può rigirare dopo un salvataggio: i bottoni si collegano una volta sola
        SE.bound = true;
        var input = $('toaCvInput');
        function openPicker() { if (!seNeedConsent()) pick(); }
        if ($('toaCvPick')) $('toaCvPick').addEventListener('click', openPicker);
        if ($('toaCvReplace')) $('toaCvReplace').addEventListener('click', openPicker);
        if (input) input.addEventListener('change', function () {
            var f = input.files && input.files[0]; showName();
            if (!f || SE.busy) return;
            var c = checkFile(f); if (c) { setStatus(errText(c), 'err'); input.value = ''; showName(); return; }
            if (seNeedConsent()) { input.value = ''; showName(); return; }
            SE.busy = true; setStatus(S.uploading, '');
            upload(SE.uuid, SE.token, f).then(function (res) {
                SE.busy = false; input.value = ''; showName();
                if (!res.ok) { setStatus(errText(res.error), 'err'); return; }
                seRender(res.cv || { presente: true, nome: f.name, caricato: '' });
                setStatus(isScan(res) ? S.scan_note : S.ok, isScan(res) ? 'err' : 'ok');
            });
        });
        var rm = $('toaCvRemove');
        if (rm) rm.addEventListener('click', function () {
            if (SE.busy) return;
            if (!SE.armed) {
                rm.textContent = S.remove_sure;
                SE.armed = setTimeout(function () { SE.armed = null; rm.textContent = S.remove; }, 4000);
                return;
            }
            clearTimeout(SE.armed); SE.armed = null; rm.textContent = S.remove;
            SE.busy = true; setStatus(S.uploading, '');
            remove(SE.uuid, SE.token).then(function (res) {
                SE.busy = false;
                if (!res.ok) { setStatus(errText(res.error), 'err'); return; }
                seRender(res.cv || { presente: false }); setStatus(S.removed, 'ok');
            });
        });
    };

    var inp = null;
    document.addEventListener('DOMContentLoaded', function () {
        inp = $('toaCvInput');
        if (inp && (CFG.mode === 'reg')) {
            if ($('toaCvPick')) $('toaCvPick').addEventListener('click', pick);
            inp.addEventListener('change', function () {
                var f = Reg.file(); showName();
                var c = f ? checkFile(f) : '';
                setStatus(c ? errText(c) : '', c ? 'err' : '');
                if (c) { inp.value = ''; showName(); }
            });
        }
    });

    window.TOACrewCV = { hasCvRole: hasCvRole, reg: Reg, se: SE };
})();
