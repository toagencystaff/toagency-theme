/* MENU GLOBALE toagency.it — fase 1 (ticket #789, 05/11/2026, chat «TEMA Ticket 789»)
   Caricato SOLO in anteprima. Pannello unico a schede, «Lavora con noi», menu del telefono, prezzi «da» dal CRM.
   Prezzi: MAI scritti a mano. Dagli endpoint CRM gia' online (gli stessi di /events/ e /creators/), cache 1 ora nel browser.
   Se il CRM non risponde restano i testi di ripiego («Staff e prezzi», «Pacchetti»). */
(function () {
  'use strict';
  var D = document, B = D.body, M = window.TOA_MG || {}, L = M.lang || 'it';
  var head = D.getElementById('tmgHead'), panel = D.getElementById('tmgPanel'), lav = D.getElementById('tmgLavora');
  var mob = D.getElementById('tmgMob'), veil = D.getElementById('tmgVeil');
  if (!head || !panel) return;
  var openK = null;
  function $$(s, r) { return Array.prototype.slice.call((r || D).querySelectorAll(s)); }
  function setTop() {
    var b = head.getBoundingClientRect().bottom;
    D.documentElement.style.setProperty('--tmg-top', Math.max(0, Math.round(b)) + 'px');
  }
  function aria() {
    $$('[data-tmg-open]').forEach(function (v) { v.setAttribute('aria-expanded', v.getAttribute('data-tmg-open') === openK ? 'true' : 'false'); });
    $$('[data-tmg-tab]').forEach(function (t) { t.classList.toggle('on', t.getAttribute('data-tmg-tab') === openK); t.setAttribute('aria-selected', t.getAttribute('data-tmg-tab') === openK ? 'true' : 'false'); });
    $$('[data-tmg-sec]').forEach(function (s) { s.hidden = s.getAttribute('data-tmg-sec') !== openK; });
  }
  function close() {
    openK = null; panel.hidden = true; if (lav) lav.hidden = true; if (veil) veil.hidden = true; aria();
  }
  function open(k) {
    closeMob();
    if (openK === k) { close(); return; }
    setTop(); openK = k;
    if (k === 'lavora') { panel.hidden = true; if (lav) lav.hidden = false; }
    else { if (lav) lav.hidden = true; panel.hidden = false; }
    if (veil) veil.hidden = false;
    aria(); prezzi();
  }
  // telefono
  function openMob() {
    if (!mob) return; close(); setTop(); mob.hidden = false; D.documentElement.style.overflow = 'hidden';
    $$('[data-tmg-mob]').forEach(function (b) { b.setAttribute('aria-expanded', 'true'); b.classList.add('is-open'); });
    prezzi();
  }
  function closeMob() {
    if (!mob || mob.hidden) return; mob.hidden = true; D.documentElement.style.overflow = '';
    $$('[data-tmg-mob]').forEach(function (b) { b.setAttribute('aria-expanded', 'false'); b.classList.remove('is-open'); });
  }
  D.addEventListener('click', function (e) {
    var t = e.target;
    var v = t.closest('[data-tmg-open]'); if (v) { e.preventDefault(); open(v.getAttribute('data-tmg-open')); return; }
    var tb = t.closest('[data-tmg-tab]'); if (tb) { e.preventDefault(); openK = tb.getAttribute('data-tmg-tab'); aria(); return; }
    if (t.closest('[data-tmg-close]') || t === veil) { close(); return; }
    var bm = t.closest('[data-tmg-mob]'); if (bm) { e.preventDefault(); if (mob && mob.hidden) openMob(); else closeMob(); return; }
    var mt = t.closest('[data-tmg-mtab]');
    if (mt) {
      var k = mt.getAttribute('data-tmg-mtab');
      $$('[data-tmg-mtab]').forEach(function (b) { b.classList.toggle('on', b === mt); });
      $$('[data-tmg-mlist]').forEach(function (l) { l.hidden = l.getAttribute('data-tmg-mlist') !== k; });
      return;
    }
    if (openK && !t.closest('.tmg-panel') && !t.closest('#tmgHead')) close();
    if (t.closest('.tmg-mbar a[href*="wa.me"]') && typeof window.toaTrackContact === 'function') { try { window.toaTrackContact('whatsapp', 'menu-bar'); } catch (x) {} }
  });
  D.addEventListener('keydown', function (e) { if (e.key === 'Escape') { close(); closeMob(); } });
  // se le voci non stanno in una riga si passa al menu a tendina (a qualunque larghezza)
  var row = head.querySelector('.tmg-head__in, .tmg-bar__in');
  function fit() {
    B.classList.remove('tmg-compact');
    if (row && row.scrollWidth > row.clientWidth + 1) B.classList.add('tmg-compact');
    if (window.innerWidth > 1024 && mob && !mob.hidden && !B.classList.contains('tmg-compact')) closeMob();
    if (openK) setTop();
  }
  fit(); window.addEventListener('resize', fit); window.addEventListener('load', fit);
  if (D.fonts && D.fonts.ready) D.fonts.ready.then(fit);
  window.addEventListener('scroll', function () { if (openK) setTop(); }, { passive: true });

  /* ── prezzi «da» dal CRM ── */
  var fatto = false;
  function fmt(p, val) { val = val || '€'; return L === 'en' && val === '€' ? '€' + p : p + ' ' + val; }
  function cache(k, ms, fn) {
    var key = 'tmg_' + k + '_' + L;
    try { var c = JSON.parse(localStorage.getItem(key) || 'null'); if (c && c.t > Date.now() - ms) return Promise.resolve(c.v); } catch (x) {}
    return fn().then(function (v) { try { localStorage.setItem(key, JSON.stringify({ t: Date.now(), v: v })); } catch (x) {} return v; });
  }
  function getJ(u) { return fetch(u, { credentials: 'omit' }).then(function (r) { if (!r.ok) throw 0; return r.json(); }); }
  function prezzoHostess() {
    var EP = '/actions/hostess-vetrina-public.php';
    return cache('h', 36e5, function () {
      return getJ(EP + '?action=filtri&lang=' + L).then(function (f) {
        return getJ(EP + '?action=vetrina&lang=' + L + '&paese=' + encodeURIComponent(f.paese || '') + '&citta=tutte&ore=4').then(function (v) {
          if (!v || !v.ok || v.prezzo_da == null) return null;
          return { p: v.prezzo_da, val: v.valuta || '€', iva: !!v.iva };
        });
      });
    });
  }
  function prezzoCreator() {
    return cache('c', 36e5, function () {
      return getJ('/actions/creator-vetrina-public.php?action=listino&lang=' + L).then(function (j) {
        var u = (j.pacchetti || []).filter(function (p) { return p.codice === 'ugc'; })[0];
        if (!u || !u.prezzo_da) return null;
        return { p: u.prezzo_da, val: u.valuta || '€', iva: L === 'it' };   // IVA mai scritta nelle altre lingue
      });
    });
  }
  function scrivi(tipo, x, unita) {
    if (!x) return;
    var da = (M.da || 'da') + ' ' + fmt(x.p, x.val), iva = x.iva && L === 'it' ? '+ IVA' : '';
    $$('[data-tmg-price="' + tipo + '"]').forEach(function (el) {
      var big = el.querySelector('[data-tmg-p="big"]'), note = el.querySelector('[data-tmg-p="note"]'), card = el.querySelector('[data-tmg-p="card"]');
      if (big) big.textContent = da;
      if (note) note.textContent = [iva, unita].filter(Boolean).join(' · ');
      if (card) card.textContent = [da, iva].filter(Boolean).join(' ');
    });
  }
  function prezzi() {
    if (fatto || !window.fetch) return; fatto = true;
    prezzoHostess().then(function (x) { scrivi('hostess', x, M.unita_h); }).catch(function () {});
    prezzoCreator().then(function (x) { scrivi('creator', x, M.unita_c); }).catch(function () {});
  }
  // le card della home mostrano il prezzo appena la pagina e' pronta
  if (D.querySelector('.tmg-cards [data-tmg-price]')) prezzi();
})();
