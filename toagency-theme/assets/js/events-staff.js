/* TOA Events — Staff e prezzi (ticket #811, 03/11/2026, chat TEMA-Events)
 * Dati SOLO dal CRM: GET <endpoint>?action=filtri|vetrina (il tema non calcola prezzi).
 * Stato filtri nell'URL (?paese=&citta=&figura=&lingue=&livello=&esperienza=&ore=) per poter condividere il link.
 */
(function () {
  'use strict';
  var cfgEl = document.getElementById('tevCfg');
  if (!cfgEl) return;
  var CFG; try { CFG = JSON.parse(cfgEl.textContent); } catch (e) { return; }
  var UI = CFG.ui, LANG = CFG.lang, EP = CFG.endpoint;
  var $ = function (id) { return document.getElementById(id); };
  var LCODE = { italiano: 'IT', inglese: 'EN', francese: 'FR', tedesco: 'DE', spagnolo: 'ES', portoghese: 'PT', russo: 'RU', cinese: 'ZH', arabo: 'AR', olandese: 'NL', giapponese: 'JA', polacco: 'PL', rumeno: 'RO', greco: 'EL', turco: 'TR', ucraino: 'UK' };
  var DEF_ORE = 6;

  var S = { paese: '', citta: '', figura: 'hostess', lingue: null, livello: '', esperienza: 'tutte', ore: DEF_ORE, zona: '', picks: [] };
  var F = null;          // filtri del Paese corrente
  var FC = {};           // cache filtri per Paese
  var V = null;          // ultima risposta vetrina
  var altriOpen = false, altreOpen = false, fOpen = false, ctrl = null, lastMsg = '';

  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function fmt(s, o) { return String(s).replace(/\{(\w)\}/g, function (m, k) { return o[k] != null ? o[k] : ''; }); }
  function setAll(key, html, isText) {
    var els = document.querySelectorAll('[data-tev="' + key + '"]');
    for (var i = 0; i < els.length; i++) { els[i].innerHTML = html; if (isText !== undefined) els[i].classList.toggle('is-text', !!isText); }
  }
  function nameOf(list, id) { for (var i = 0; list && i < list.length; i++) if (list[i].id === id) return list[i].nome; return id; }
  function chip(label, pressed, big, extra) {
    return '<button type="button" class="tev-chip' + (big ? ' tev-chip--big' : '') + (extra || '') + '" aria-pressed="' + (pressed ? 'true' : 'false') + '">' + esc(label) + '</button>';
  }
  function link(label, pressed) { return '<button type="button" class="tev-link" aria-pressed="' + (pressed ? 'true' : 'false') + '">' + esc(label) + '</button>'; }
  function bind(container, sel, fn) {
    var b = container.querySelectorAll(sel);
    for (var i = 0; i < b.length; i++) (function (i) { b[i].addEventListener('click', function () { fn(i); }); })(i);
  }
  function defLingue() { var o = []; ((F && F.lingue) || []).forEach(function (l) { if (l.selezionata) o.push(l.id); }); return o; }
  function extraLingue() { var d = defLingue(); return (S.lingue || []).filter(function (l) { return d.indexOf(l) < 0; }); }
  function showError() { var g = $('tevGrid'); g.classList.remove('is-loading'); g.innerHTML = '<div class="tev-empty">' + esc(UI.errore) + '</div>'; }

  // ── URL ──
  function readUrl() {
    var q = new URLSearchParams(location.search);
    if (q.get('paese')) S.paese = q.get('paese').toUpperCase().slice(0, 2);
    if (q.get('citta')) S.citta = q.get('citta').slice(0, 60);
    if (/^(hostess|steward|tutti)$/.test(q.get('figura') || '')) S.figura = q.get('figura');
    if (q.has('lingue')) S.lingue = q.get('lingue') ? q.get('lingue').split(',').slice(0, 9) : [];
    if (/^(B1|B2|C1|C2)$/.test(q.get('livello') || '')) S.livello = q.get('livello');
    if (/^(tutte|esperta|premium)$/.test(q.get('esperienza') || '')) S.esperienza = q.get('esperienza');
    var o = parseInt(q.get('ore'), 10); if (o >= 4 && o <= 12) S.ore = o;
  }
  function writeUrl() {
    try {
      var q = new URLSearchParams(location.search);
      ['paese', 'citta', 'figura', 'lingue', 'livello', 'esperienza', 'ore'].forEach(function (k) { q.delete(k); });
      q.set('paese', S.paese); q.set('citta', S.citta);
      if (S.figura !== 'hostess') q.set('figura', S.figura);
      if (S.lingue && S.lingue.join(',') !== defLingue().join(',')) q.set('lingue', S.lingue.join(','));
      if (S.livello && extraLingue().length) q.set('livello', S.livello);
      if (S.esperienza !== 'tutte') q.set('esperienza', S.esperienza);
      if (S.ore !== DEF_ORE) q.set('ore', S.ore);
      history.replaceState(null, '', location.pathname + '?' + q.toString() + location.hash);
    } catch (e) {}
  }

  // ── chiamate CRM ──
  function getJSON(params, signal) {
    var u = EP + '?' + new URLSearchParams(params).toString();
    return fetch(u, { credentials: 'omit', signal: signal }).then(function (r) { if (!r.ok) throw new Error('http ' + r.status); return r.json(); });
  }
  function loadFiltri(paese) {
    if (paese && FC[paese]) return Promise.resolve(FC[paese]);
    var p = { action: 'filtri', lang: LANG }; if (paese) p.paese = paese;
    return getJSON(p).then(function (d) { if (!d || !d.paesi) throw new Error('filtri'); FC[d.paese] = d; return d; });
  }
  function loadVetrina() {
    if (ctrl) { try { ctrl.abort(); } catch (e) {} }
    ctrl = ('AbortController' in window) ? new AbortController() : null;
    var p = { action: 'vetrina', lang: LANG, paese: S.paese, citta: S.citta, figura: S.figura, lingue: (S.lingue || []).join(','), esperienza: S.esperienza, ore: S.ore };
    if (S.livello && extraLingue().length) p.livello = S.livello;
    $('tevGrid').classList.add('is-loading');
    writeUrl();
    getJSON(p, ctrl ? ctrl.signal : undefined).then(function (d) {
      if (!d || !d.ok) throw new Error('vetrina');
      V = d; renderVetrina();
    }).catch(function (e) {
      if (e && e.name === 'AbortError') return;
      showError();
    });
  }

  // ── filtri ──
  function renderFiltri() {
    var pr = F.paesi.filter(function (p) { return p.principale; }), al = F.paesi.filter(function (p) { return !p.principale; });
    var curAlt = al.some(function (p) { return p.id === S.paese; });
    var h = pr.map(function (p) { return chip(p.nome, p.id === S.paese, true); }).join('');
    var lab = altriOpen ? UI.chiudi : (curAlt ? nameOf(F.paesi, S.paese) + ' +' : UI.altriPaesi);
    h += chip(lab, curAlt && !altriOpen, true, ' tev-chip--dash');
    $('tevPaesi').innerHTML = h;
    bind($('tevPaesi'), '.tev-chip', function (i) { if (i < pr.length) pickPaese(pr[i].id); else { altriOpen = !altriOpen; renderFiltri(); } });
    $('tevAltriPaesi').hidden = !altriOpen;
    $('tevAltriPaesiL').innerHTML = al.map(function (p) { return link(p.nome, p.id === S.paese); }).join('');
    $('tevAltroNote').textContent = UI.altroPaese;
    bind($('tevAltriPaesiL'), '.tev-link', function (i) { altriOpen = false; pickPaese(al[i].id); });

    var top = F.citta_top || [], alt = F.citta_altre || [];
    var inAlt = alt.some(function (c) { return c.id === S.citta; });
    h = top.map(function (c) { return chip(c.nome, c.id === S.citta); }).join('');
    h += chip(altreOpen ? UI.chiudiZone : (inAlt ? nameOf(alt, S.citta) + ' +' : UI.altreZone), inAlt && !altreOpen, false, ' tev-chip--dash');
    $('tevCitta').innerHTML = h;
    bind($('tevCitta'), '.tev-chip', function (i) { if (i < top.length) pickCitta(top[i].id); else { altreOpen = !altreOpen; renderFiltri(); } });
    $('tevAltre').hidden = !altreOpen;
    $('tevAltreL').innerHTML = alt.map(function (c) { return link(c.nome, c.id === S.citta); }).join('');
    bind($('tevAltreL'), '.tev-link', function (i) { pickCitta(alt[i].id); });

    $('tevFigure').innerHTML = F.figure.map(function (f) { return chip(f.nome, f.id === S.figura); }).join('');
    bind($('tevFigure'), '.tev-chip', function (i) { S.figura = F.figure[i].id; renderFiltri(); loadVetrina(); });

    $('tevLingue').innerHTML = F.lingue.map(function (l) { var on = S.lingue.indexOf(l.id) >= 0; return chip((on ? '✓ ' : '') + l.nome, on); }).join('');
    bind($('tevLingue'), '.tev-chip', function (i) {
      var id = F.lingue[i].id, k = S.lingue.indexOf(id);
      if (k >= 0) S.lingue.splice(k, 1); else S.lingue.push(id);
      renderFiltri(); loadVetrina();
    });
    var showLiv = extraLingue().length > 0;
    $('tevLivWrap').hidden = !showLiv;
    var liv = [''].concat(F.livelli || []);
    $('tevLivelli').innerHTML = liv.map(function (l) { return chip(l || UI.qualsiasi, l === S.livello); }).join('');
    bind($('tevLivelli'), '.tev-chip', function (i) { S.livello = liv[i]; renderFiltri(); loadVetrina(); });

    $('tevEsp').innerHTML = F.esperienza.map(function (x) { return chip(x.nome, x.id === S.esperienza); }).join('');
    bind($('tevEsp'), '.tev-chip', function (i) { S.esperienza = F.esperienza[i].id; renderFiltri(); loadVetrina(); });

    var ore = F.ore || [4, 6, 8, 10];
    $('tevOre').innerHTML = ore.map(function (o) { return chip(fmt(UI.oreFmt, { n: o }), o === S.ore); }).join('');
    bind($('tevOre'), '.tev-chip', function (i) { S.ore = ore[i]; renderFiltri(); loadVetrina(); });

    var tolte = defLingue().some(function (l) { return S.lingue.indexOf(l) < 0; }) ? 1 : 0;
    var n = extraLingue().length + tolte + (S.livello && showLiv ? 1 : 0) + (S.esperienza !== 'tutte' ? 1 : 0) + (S.ore !== DEF_ORE ? 1 : 0) + (S.figura !== 'hostess' ? 1 : 0);
    $('tevFbtnL').textContent = fOpen ? UI.chiudiFiltri : (UI.filtri + (n ? ' (' + n + ')' : ''));
    $('tevFbtn').setAttribute('aria-expanded', fOpen ? 'true' : 'false');
    $('tevFpanel').classList.toggle('is-open', fOpen);
  }
  function pickPaese(id) {
    if (id === S.paese) { renderFiltri(); return; }
    loadFiltri(id).then(function (d) {
      F = d; S.paese = d.paese;
      S.citta = ((d.citta_top || [])[0] || (d.citta_altre || [])[0] || { id: '' }).id;
      S.lingue = defLingue(); S.livello = ''; altreOpen = false;
      S.zona = ''; $('tevZona').value = ''; $('tevZonaOk').hidden = true;
      renderFiltri(); loadVetrina();
    }).catch(showError);
  }
  function pickCitta(id) { S.citta = id; renderFiltri(); loadVetrina(); }

  // ── vetrina ──
  function dec(n) { return LANG === 'en' ? String(n) : String(n).replace('.', ','); }
  function priceText(n, val) { return n == null ? null : (n + ' ' + (val || '€')); }
  function renderVetrina() {
    var d = V, tx = d.testi || {};
    var cName = (d.citta && d.citta.nome) || nameOf((F.citta_top || []).concat(F.citta_altre || []), S.citta);
    var fName = nameOf(F.figure, d.figura || S.figura);
    var pt = priceText(d.prezzo_da, d.valuta);
    setAll('aPartire', esc(pt ? tx.a_partire : ''));
    setAll('prezzo', esc(pt || tx.su_richiesta || ''), !pt);
    setAll('iva', esc(pt && d.iva ? tx.iva : ''));
    setAll('riassunto', esc([tx.unita, fName, cName].filter(Boolean).join(' · ')));
    setAll('cta', esc(tx.cta || ''));
    setAll('esempio', esc(tx.esempio || ''));
    var n = (d.profili || []).length;
    setAll('conta', esc(n === 1 ? UI.conta1 : fmt(UI.conta, { n: n })));
    setAll('titolo', esc(fmt(UI.titolo, { f: fName, c: cName })));
    var av = $('tevAvviso'); av.textContent = d.avviso || ''; av.hidden = !d.avviso;

    var g = $('tevGrid');
    g.classList.remove('is-loading');
    if (!n) { g.innerHTML = '<div class="tev-empty">' + esc(UI.vuoto) + '</div>'; return; }
    g.innerHTML = d.profili.map(function (p, i) {
      var ln = (p.lingue_liv || []).filter(function (l) { return l.livello === 'nativo' || /^(B1|B2|C1|C2)$/.test(l.livello); }).map(function (l) {
        return '<span>' + esc((LCODE[l.lingua] || String(l.lingua).slice(0, 2).toUpperCase()) + ' ' + (l.livello === 'nativo' ? UI.nativo : l.livello)) + '</span>';
      }).join('');
      var media = (p.media && p.media.length) ? p.media.length : 0;
      var pp = priceText(p.prezzo, d.valuta);
      return '<button type="button" class="tev-card" data-i="' + i + '" aria-label="' + esc(p.nome + ' #' + p.codice + ' — ' + UI.foto) + '">' +
        '<span class="tev-card__ph">' + (p.foto ? '<img src="' + esc(p.foto) + '" alt="' + esc(p.nome + ' #' + p.codice) + '" loading="' + (i < 4 ? 'eager' : 'lazy') + '" decoding="async">' : '') +
          (p.livello_label ? '<span class="tev-card__lv">' + esc(p.livello_label) + '</span>' : '') +
          (media > 1 ? '<span class="tev-card__n">' + media + ' · ' + esc(UI.foto) + '</span>' : '') + '</span>' +
        '<span class="tev-card__b">' +
          '<span class="tev-card__top"><span class="tev-card__nm">' + esc(p.nome) + '</span><span class="tev-card__cd">#' + esc(p.codice) + '</span></span>' +
          (p.altezza ? '<span class="tev-card__meta">' + esc(dec((p.altezza / 100).toFixed(2)) + ' ' + UI.m) + '</span>' : '') +
          (ln ? '<span class="tev-card__ln">' + ln + '</span>' : '') +
          ((p.esperienza && p.esperienza.length) ? '<span class="tev-card__ex">' + esc(p.esperienza.join(' · ')) + '</span>' : '') +
          '<span class="tev-card__ft"><span class="tev-card__dv">' + esc(p.dove || '') + '</span><span class="tev-card__pr' + (pp ? '' : ' is-text') + '">' + esc(pp || tx.su_richiesta || '') + '</span></span>' +
        '</span></button>';
    }).join('');
    bind(g, '.tev-card', function (i) { openLb(i); });
  }

  // ── galleria profilo ──
  var lb = { p: null, i: 0, items: [], back: null };
  function openLb(idx) {
    var p = V.profili[idx]; if (!p) return;
    lb.p = p; lb.i = 0; lb.back = document.activeElement;
    lb.items = (p.media && p.media.length) ? p.media.map(function (m) { return { t: m.t, u: m.u2 || m.u }; }) : [{ t: 'i', u: p.foto }];
    $('tevLbT').innerHTML = '<b>' + esc(p.nome) + '</b> #' + esc(p.codice) + (p.livello_label ? ' · ' + esc(p.livello_label) : '');
    $('tevLbAsk').textContent = UI.chiedi;
    $('tevLb').hidden = false; document.documentElement.style.overflow = 'hidden';
    showLb(); $('tevLbX').focus();
  }
  function showLb() {
    var it = lb.items[lb.i], st = $('tevLbS'), many = lb.items.length > 1;
    st.innerHTML = (it.t === 'v' ? '<video src="' + esc(it.u) + '" controls playsinline preload="metadata"></video>' : '<img src="' + esc(it.u) + '" alt="' + esc(lb.p.nome + ' #' + lb.p.codice) + '">') +
      (many ? '<button type="button" class="tev-lb__nv tev-lb__nv--p" aria-label="‹">‹</button><button type="button" class="tev-lb__nv tev-lb__nv--n" aria-label="›">›</button>' : '');
    $('tevLbC').textContent = many ? (lb.i + 1) + ' / ' + lb.items.length : '';
    if (many) {
      st.querySelector('.tev-lb__nv--p').addEventListener('click', function () { stepLb(-1); });
      st.querySelector('.tev-lb__nv--n').addEventListener('click', function () { stepLb(1); });
    }
  }
  function stepLb(d) { lb.i = (lb.i + d + lb.items.length) % lb.items.length; showLb(); }
  function closeLb() {
    $('tevLb').hidden = true; $('tevLbS').innerHTML = ''; document.documentElement.style.overflow = '';
    if (lb.back && lb.back.focus) { try { lb.back.focus({ preventScroll: true }); } catch (e) {} }
  }
  $('tevLbX').addEventListener('click', closeLb);
  $('tevLb').addEventListener('click', function (e) { if (e.target === this || e.target === $('tevLbS')) closeLb(); });
  document.addEventListener('keydown', function (e) {
    if ($('tevLb').hidden) return;
    if (e.key === 'Escape') closeLb(); else if (e.key === 'ArrowRight') stepLb(1); else if (e.key === 'ArrowLeft') stepLb(-1);
  });
  $('tevLbAsk').addEventListener('click', function () {
    var c = '#' + lb.p.codice; if (S.picks.indexOf(c) < 0) S.picks.push(c);
    lb.back = null; closeLb(); fillForm(); goForm();
  });

  // ── form preventivo (componente del sito, #fe_message) ──
  function fillForm() {
    var ta = $('fe_message'); if (!ta || !F) return;
    if (ta.value.trim() && ta.value !== lastMsg) return; // non sovrascrivere quello che ha scritto il cliente
    var cName = (V && V.citta && V.citta.nome) || nameOf((F.citta_top || []).concat(F.citta_altre || []), S.citta);
    var lines = [UI.msg,
      UI.mFig + ': ' + nameOf(F.figure, S.figura),
      UI.mDove + ': ' + cName + ' (' + nameOf(F.paesi, S.paese) + ')' + (S.zona ? ' — ' + S.zona : ''),
      UI.mOre + ': ' + S.ore];
    if (S.lingue && S.lingue.length) lines.push(UI.mLin + ': ' + S.lingue.map(function (l) { return nameOf(F.lingue, l); }).join(', ') + (S.livello && extraLingue().length ? ' (min. ' + S.livello + ')' : ''));
    if (S.esperienza !== 'tutte') lines.push(UI.mEsp + ': ' + nameOf(F.esperienza, S.esperienza));
    if (S.picks.length) lines.push(UI.mProf + ': ' + S.picks.join(', '));
    lastMsg = lines.join('\n'); ta.value = lastMsg;
  }
  function goForm() { var f = $('preventivo'); if (f && f.scrollIntoView) f.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  document.addEventListener('click', function (e) {
    var a = e.target.closest ? e.target.closest('a[href="#preventivo"]') : null;
    if (!a) return;
    fillForm();
    try { (window.dataLayer = window.dataLayer || []).push({ event: 'tev_cta', tev_paese: S.paese, tev_citta: S.citta }); } catch (x) {}
  });

  // ── interfaccia fissa ──
  setAll('lCitta', esc(UI.citta)); setAll('lFigura', esc(UI.figura)); setAll('lLingue', esc(UI.lingue)); setAll('lLingueHint', esc(UI.lingueHint));
  setAll('lLivello', esc(UI.livello)); setAll('lEsperienza', esc(UI.esperienza)); setAll('lOre', esc(UI.ore)); setAll('lZona', esc(UI.zona));
  setAll('barCta', esc(UI.barCta));
  setAll('conta', esc(UI.carico));
  $('tevFbtnL').textContent = UI.filtri;
  $('tevZona').placeholder = UI.zonaPh;
  $('tevZona').addEventListener('input', function () { S.zona = this.value.trim().slice(0, 80); var ok = $('tevZonaOk'); ok.textContent = UI.zonaOk; ok.hidden = !S.zona; });
  $('tevFbtn').addEventListener('click', function () { fOpen = !fOpen; if (F) renderFiltri(); });

  var bar = $('tevBar'), form = $('preventivo');
  if (bar && form && 'IntersectionObserver' in window) {
    new IntersectionObserver(function (en) { en.forEach(function (x) { bar.classList.toggle('hide', x.isIntersecting); }); }, { threshold: 0 }).observe(form);
  }

  // ── avvio ──
  readUrl();
  loadFiltri(S.paese || '').catch(function () { return loadFiltri(''); }).then(function (d) {
    F = d; S.paese = d.paese;
    var all = (d.citta_top || []).concat(d.citta_altre || []);
    if (!all.some(function (c) { return c.id === S.citta; })) S.citta = (all[0] || { id: '' }).id;
    if ((d.citta_altre || []).some(function (c) { return c.id === S.citta; })) altreOpen = true;
    var ids = d.lingue.map(function (l) { return l.id; });
    S.lingue = (S.lingue === null) ? defLingue() : S.lingue.filter(function (l) { return ids.indexOf(l) >= 0; });
    if ((d.ore || []).length && d.ore.indexOf(S.ore) < 0) S.ore = DEF_ORE;
    renderFiltri(); loadVetrina();
  }).catch(showError);
})();
