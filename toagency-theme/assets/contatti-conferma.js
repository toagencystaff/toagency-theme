/**
 * contatti-conferma.js — ticket #665 (seguito del #664 del CRM)
 * "Ripeti l'email" + "Ripeti il numero" nei moduli di registrazione (talent e crew)
 * e prefisso telefono SENZA paese predefinito fuori dalla pagina italiana.
 *
 * Regole e testi dei controlli stanno nel CRM (fonte unica): /crm_toagency/contatti-check-js.php
 * (window.TOA_CC). Qui NON si riscrive nessuna regola: si creano i campi, si aggancia TOA_CC
 * e si fornisce a chi invia il form i valori di conferma.
 * Se lo script del CRM non si carica, il modulo funziona come prima (nessun blocco).
 *
 * Uso nei moduli:
 *   ToaContatti.setup({ form, cls:'toa-talent', prefixBox:'toaTalentPhonePrefix' });
 *   ToaContatti.verifica()  -> [] se ok, altrimenti [{el,msg}] già mostrati sotto i campi
 *   ToaContatti.conferme()  -> { email_conferma, telefono_conferma } (vuoto se TOA_CC non pronto)
 */
(function (w, d) {
    'use strict';

    var LANG = ((d.documentElement.getAttribute('lang') || 'it').substring(0, 2)).toLowerCase();
    if (['it', 'en', 'fr', 'es'].indexOf(LANG) < 0) LANG = 'en';

    // Testi minimi non presenti in TOA_CC.T (le etichette "Ripeti…" vengono da TOA_CC.T)
    var TX = {
        prefisso: { it: 'Prefisso', en: 'Prefix', fr: 'Indicatif', es: 'Prefijo' },
        altro:    { it: 'Altro',    en: 'Other',  fr: 'Autre',     es: 'Otro' }
    };
    function t(k) { return (TX[k] && (TX[k][LANG] || TX[k].en)) || ''; }

    var S = { ready: false, cfg: null, preEmail: false };

    // true = la pagina è italiana: +39 preselezionato. Altrimenti nessun paese scelto.
    var PRESEL_IT = (LANG === 'it');

    function q(sel, root) { return (root || d).querySelector(sel); }

    function loadCC(cb) {
        if (w.TOA_CC) { cb(true); return; }
        var s = d.createElement('script');
        s.src = '/crm_toagency/contatti-check-js.php?lang=' + LANG;
        s.onload = function () { cb(!!w.TOA_CC); };
        s.onerror = function () { cb(false); };
        d.head.appendChild(s);
    }

    // Crea il campo "Ripeti…" subito dopo il campo originale (stessa struttura del modulo)
    function creaCampo(cls, refInput, id, name, label, type, ph, ac) {
        var refField = refInput.closest('.' + cls + '-field');
        if (!refField) return null;
        var wrap = d.createElement('div');
        wrap.className = cls + '-field';
        wrap.setAttribute('data-cc', name);
        var lab = d.createElement('label');
        lab.className = cls + '-label';
        lab.setAttribute('for', id);
        lab.innerHTML = '';
        lab.appendChild(d.createTextNode(label + ' '));
        var req = d.createElement('span'); req.className = 'req'; req.textContent = '*'; lab.appendChild(req);
        var inp = d.createElement('input');
        inp.type = type; inp.id = id; inp.name = name;
        inp.className = cls + '-input';
        inp.setAttribute('autocomplete', ac || 'off');
        inp.setAttribute('autocapitalize', 'off');
        inp.setAttribute('spellcheck', 'false');
        if (ph) inp.placeholder = ph;
        var err = d.createElement('div'); err.className = cls + '-error-msg';
        wrap.appendChild(lab); wrap.appendChild(inp); wrap.appendChild(err);
        refField.parentNode.insertBefore(wrap, refField.nextSibling);
        return { wrap: wrap, input: inp };
    }

    // Prefisso: nessun paese predefinito fuori dall'italiano + voce «Altro»
    function dopoPrefissi(box, cls) {
        if (!box) return;
        var hid = q('input[type="hidden"]', box);
        var lbl = q('.' + cls + '-customselect-label', box);
        var optsBox = q('.' + cls + '-customselect-options', box);
        if (!hid || !optsBox) return;
        if (!q('.' + cls + '-customselect-option[data-altro]', optsBox)) {
            var o = d.createElement('div');
            o.className = cls + '-customselect-option';
            o.setAttribute('data-altro', '1');
            o.dataset.value = '';
            o.dataset.label = t('altro') + ' (+…)';
            o.innerHTML = '<span class="flag">🌍</span><span>+…</span><span class="small-info">' + t('altro') + '</span>';
            optsBox.insertBefore(o, optsBox.firstChild);
        }
        if (!PRESEL_IT) {
            // nessun paese scelto: l'utente deve sceglierlo
            optsBox.querySelectorAll('.selected').forEach(function (x) { x.classList.remove('selected'); });
            hid.value = '';
            if (lbl) lbl.textContent = t('prefisso');
        }
        box.dataset.ccAltro = '';
        if (!box._ccBound) {
            box._ccBound = true;
            box.addEventListener('toa:select', function (e) {
                var isAltro = !!(e.detail && e.detail.label && e.detail.label.indexOf(t('altro')) === 0 && !e.detail.value);
                box.dataset.ccAltro = isAltro ? '1' : '';
                var tel = S.cfg && S.cfg.tel;
                if (tel && isAltro) { tel.placeholder = '+244 …'; try { tel.focus(); } catch (x) {} }
            });
        }
    }

    // Valore prefisso per TOA_CC: ISO ("FR"), "+39", oppure '' (numero col + davanti o nessuna scelta)
    function prefissoCorrente() {
        var c = S.cfg; if (!c) return '';
        var num = (c.tel.value || '').trim();
        if (num.charAt(0) === '+' || num.indexOf('00') === 0) return '';
        var hid = c.prefixHid;
        return hid ? (hid.value || '') : '';
    }

    function setup(opts) {
        var form = opts.form, cls = opts.cls;
        if (!form) return;
        var email = q('[name="email"]', form), tel = q('[name="telefono"]', form);
        var box = opts.prefixBox ? d.getElementById(opts.prefixBox) : null;
        if (!email || !tel) return;
        if (!email.id) email.id = 'toaCcEmail';
        if (!tel.id) tel.id = 'toaCcTel';
        S.cfg = { form: form, cls: cls, email: email, tel: tel, box: box,
                  prefixHid: box ? q('input[type="hidden"]', box) : null };
        loadCC(function (ok) {
            if (!ok || !w.TOA_CC) { console.warn('[contatti] script CRM non caricato: modulo senza doppia digitazione'); return; }
            var T = w.TOA_CC.T || {};
            var e2 = creaCampo(cls, email, 'toaCcEmail2', 'email_conferma', T.lbl_email2 || 'Email', 'email', '', 'off');
            var t2 = creaCampo(cls, tel.closest('.' + cls + '-phone-group') || tel, 'toaCcTel2', 'telefono_conferma', T.lbl_tel2 || 'Tel', 'tel', T.ph_tel2 || '', 'off');
            if (!e2 || !t2) return;
            S.cfg.email2 = e2.input; S.cfg.tel2 = t2.input;
            S.cfg.email2Wrap = e2.wrap; S.cfg.tel2Wrap = t2.wrap;
            w.TOA_CC.aggancia({
                email: '#' + email.id, email2: '#toaCcEmail2',
                tel: '#' + tel.id, tel2: '#toaCcTel2',
                prefisso: prefissoCorrente
            });
            S.ready = true;
            if (S.preEmail) emailPrecompilata();
        });
    }

    // Il testo dell'errore lo mostra già TOA_CC sotto il campo (4 lingue): qui solo il bordo rosso del modulo
    function mostra(el) {
        if (el) el.classList.add('error');
    }

    function verifica() {
        if (!S.ready || !w.TOA_CC) return [];
        var c = S.cfg, out = [];
        // email precompilata dal link (readonly): conferma automatica, niente da riscrivere
        if (S.preEmail) { c.email2.value = c.email.value; }
        var errs = [];
        try {
            errs = w.TOA_CC.verifica({
                email: '#' + c.email.id, email2: '#toaCcEmail2',
                tel: '#' + c.tel.id, tel2: '#toaCcTel2',
                prefisso: prefissoCorrente
            }) || [];
        } catch (e) { console.warn('[contatti] verifica fallita (non blocco):', e); errs = []; }
        errs.forEach(function (e) { out.push({ el: e.el, msg: e.msg }); });
        out.forEach(function (e) { mostra(e.el); });
        return out;
    }

    // Valori di conferma da inviare al server (vuoti se il controllo non è attivo: il server non controlla)
    function conferme() {
        var c = S.cfg;
        if (!S.ready || !c || !c.email2) return {};
        if (S.preEmail) c.email2.value = c.email.value;
        return { email_conferma: c.email2.value.trim(), telefono_conferma: c.tel2.value.trim() };
    }

    function emailPrecompilata() {
        S.preEmail = true;
        var c = S.cfg;
        if (!S.ready || !c || !c.email2) return;
        c.email2.value = c.email.value;
        if (c.email2Wrap) c.email2Wrap.style.display = 'none';
    }

    w.ToaContatti = {
        setup: setup, verifica: verifica, conferme: conferme,
        dopoPrefissi: dopoPrefissi, emailPrecompilata: emailPrecompilata,
        loadCC: loadCC, creaCampo: creaCampo, // usati anche dalla modifica profilo (talent-self-edit.js)
        preselIT: PRESEL_IT, lang: LANG,
        get ready() { return S.ready; }
    };
})(window, document);
