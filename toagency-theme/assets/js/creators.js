/* TOA Creators — pagina /creators/ (ticket CRM #807, 04/11/2026, chat «TEMA Ticket 807»)
 * Disegna pacchetti, servizi, apertura con i creator in evidenza, vetrina in 3 fasce, IVA secondo il Paese della sede.
 * Dati SOLO da /actions/creator-vetrina-public.php (CRM): il tema non calcola prezzi e non sceglie i creator.
 * Regole: video sempre muti, partono solo quando sono sullo schermo (banda CDN), mai link a social o handle;
 * i video dell'apertura hanno sempre il credito del creator (nome, #codice, nicchia, link alla scheda).
 * Il modulo e' components/form-creators-inline.php (window.tcrForm). */
(function () {
  'use strict';
  var cfgEl = document.getElementById('tcrCfg');
  if (!cfgEl) return;
  var C; try { C = JSON.parse(cfgEl.textContent); } catch (e) { return; }
  var U = C.ui || {}, L = C.lang || 'it', EP = C.endpoint;
  var LOC = { it: 'it-IT', en: 'en-GB', fr: 'fr-FR', es: 'es-ES' }[L] || 'it-IT';
  var RM = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  function $(id) { return document.getElementById(id); }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function tpl(s, o) { return String(s || '').replace(/\{(\w+)\}/g, function (m, k) { return o[k] != null ? o[k] : m; }); }
  var nf0 = new Intl.NumberFormat(LOC, { style: 'currency', currency: 'EUR', maximumFractionDigits: 0, minimumFractionDigits: 0 });
  var nf2 = new Intl.NumberFormat(LOC, { style: 'currency', currency: 'EUR', minimumFractionDigits: 2, maximumFractionDigits: 2 });
  function eur(n) { return nf0.format(n); }
  function ucf(s) { s = String(s || ''); return s.charAt(0).toUpperCase() + s.slice(1); }
  function get(action, params) {
    var q = new URLSearchParams(Object.assign({ action: action, lang: L }, params || {}));
    Object.keys(params || {}).forEach(function (k) { if (!params[k]) q.delete(k); });
    return fetch(EP + '?' + q.toString(), { credentials: 'omit' })
      .then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); })
      .then(function (j) { return j && j.ok ? j : Promise.reject('ko'); });
  }

  var qs = new URLSearchParams(location.search);
  var PACKS = ['ugc', 'profilo', 'coverage', 'multi', 'continuativo'];
  var S = {
    pack: PACKS.indexOf(qs.get('pacchetto')) >= 0 ? qs.get('pacchetto') : 'ugc',
    settore: /^[a-z\-]{2,20}$/.test(qs.get('settore') || '') ? qs.get('settore') : '',
    area: '', contenuto: '', listino: null, servizi: null, filtri: null, iva: null, testi: null, v: null, kpi: false
  };
  if (S.pack === 'continuativo') S.pack = 'ugc';   // il continuativo non ha vetrina: si parte dai video UGC
  var ICO = { ugc: '🎬', profilo: '📱', coverage: '🎤', multi: '✨', continuativo: '🔁', social: '💬', ads: '📈', shooting: '📸' };
  function form(o) { if (window.tcrForm) window.tcrForm.set(o); }
  function packOf(code) { return (S.listino || []).filter(function (p) { return p.codice === code; })[0] || null; }

  /* ───────── video: caricati solo vicino allo schermo, al massimo 3 alla volta (prima quelli visibili), partono solo se visibili ─────────
   * I video passano dal proxy del CRM: scaricarne 20 insieme rallentava tutto (04/11). Un video senza immagine (solo audio o codec che
   * il browser non legge: larghezza 0) o che va in errore fa sparire la card: mai un riquadro nero vuoto. */
  // 07/11 (ticket 857): le card usano l'anteprima leggera (~500 KB, 7 s) mandata dal CRM, non il video intero → 6 alla volta
  var Q = [], attivi = 0, MAXV = 6;
  function vidKo(v) { var c = v.closest('.tcr-cc'); if (c) { c.style.display = 'none'; pari(c.parentNode); } else { var a = v.closest('.tcr-ph'); if (a) a.style.visibility = 'hidden'; } }
  // FIX 2026-11-05 (TEMA Ticket 789): le fasce sono a 2 colonne; con un numero dispari di card restava un buco nero nella griglia → si nasconde l'ultima
  function pari(g) {
    if (!g || !g.classList || !g.classList.contains('tcr-grid')) return;
    var vis = Array.prototype.filter.call(g.children, function (x) { return x.style.display !== 'none'; });
    if (vis.length > 1 && vis.length % 2) vis[vis.length - 1].style.display = 'none';
  }
  function pump() {
    while (attivi < MAXV && Q.length) {
      var v = Q.shift();
      if (!v.isConnected || v.getAttribute('src')) continue;
      attivi++;
      (function (v) {
        var fatto = false;
        function fine() { if (fatto) return; fatto = true; attivi--; pump(); }
        v.addEventListener('loadedmetadata', function () { if (!v.videoWidth) vidKo(v); }, { once: true });
        v.addEventListener('loadeddata', function () { var b = v.closest('.tcr-vid'); if (b) b.classList.add('ok'); bars(v); fine(); }, { once: true });
        v.addEventListener('error', function () { vidKo(v); fine(); }, { once: true });
        setTimeout(fine, 12000);
        v.src = v.dataset.src + '#t=0.5';
        if (v.dataset.vis === '1' && !RM) { var p = v.play(); if (p && p.catch) p.catch(function () {}); }
      })(v);
    }
  }
  var io = 'IntersectionObserver' in window ? new IntersectionObserver(function (es) {
    es.forEach(function (e) {
      var v = e.target;
      v.dataset.vis = e.isIntersecting ? '1' : '';
      if (e.isIntersecting) {
        if (!v.getAttribute('src')) { var i = Q.indexOf(v); if (i >= 0) Q.splice(i, 1); Q.unshift(v); pump(); }
        else if (!RM) { var p = v.play(); if (p && p.catch) p.catch(function () {}); }
      } else if (!v.paused) v.pause();
    });
  }, { rootMargin: '150px 0px', threshold: 0.01 }) : null;
  function watch(root) {
    Array.prototype.forEach.call((root || document).querySelectorAll('video[data-src]'), function (v) {
      if (v.dataset.w) return; v.dataset.w = '1';
      if (io) io.observe(v); else { Q.push(v); pump(); }
    });
  }
  /* bande nere dentro alcuni video: misura le righe/colonne quasi nere ai bordi del primo fotogramma e ingrandisce quanto basta */
  function bars(v) {
    try {
      var w = 40, h = 72, cv = document.createElement('canvas'); cv.width = w; cv.height = h;
      var cx = cv.getContext('2d', { willReadFrequently: true }); cx.drawImage(v, 0, 0, w, h);
      var d = cx.getImageData(0, 0, w, h).data;
      function lum(x, y) { var i = (y * w + x) * 4; return 0.2126 * d[i] + 0.7152 * d[i + 1] + 0.0722 * d[i + 2]; }
      function dark(arr) { var s = 0, m = 0; arr.forEach(function (x) { s += x; if (x > m) m = x; }); return s / arr.length < 14 && m < 40; }
      function row(y) { var a = []; for (var x = 0; x < w; x++) a.push(lum(x, y)); return a; }
      function col(x) { var a = []; for (var y = 0; y < h; y++) a.push(lum(x, y)); return a; }
      var t = 0, b = 0, l = 0, r = 0;
      while (t < h / 3 && dark(row(t))) t++;
      while (b < h / 3 && dark(row(h - 1 - b))) b++;
      while (l < w / 3 && dark(col(l))) l++;
      while (r < w / 3 && dark(col(w - 1 - r))) r++;
      if (t + b >= h * 0.9 || l + r >= w * 0.9) return;   // fotogramma tutto scuro: non e' una banda
      var sy = 1 / (1 - (t + b) / h), sx = 1 / (1 - (l + r) / w), s = Math.min(Math.max(sx, sy), 1.6);
      if (s < 1.03) return;
      var ty = -((t - b) / h) * 50, tx = -((l - r) / w) * 50;
      v.style.transform = 'scale(' + s.toFixed(3) + ') translate(' + tx.toFixed(2) + '%,' + ty.toFixed(2) + '%)';
    } catch (e) { /* canvas non leggibile: si lascia com'e' */ }
  }

  /* ───────── IVA secondo il Paese della sede ───────── */
  var ISO = 'AD AE AL AM AR AT AU AZ BA BE BG BH BR BY CA CH CL CN CO CR CY CZ DE DK DO DZ EC EE EG ES FI FR GB GE GR HK HR HU ID IE IL IN IS IT JO JP KE KR KW KZ LB LI LT LU LV MA MC MD ME MK MT MX MY NG NL NO NZ OM PA PE PH PK PL PT QA RO RS SA SE SG SI SK SM TH TN TR TW UA US UY VA VN ZA'.split(' ');
  var dn = null; try { dn = new Intl.DisplayNames([L], { type: 'region' }); } catch (e) {}
  function paeseNome(c) { try { return dn ? dn.of(c) : c; } catch (e) { return c; } }
  var sel = $('tcrPaese');
  function caso(p) { if (!S.iva || !p) return 'neutro'; if (p === 'IT') return 'IT'; return (S.iva.paesi_ue || []).indexOf(p) >= 0 ? 'UE' : 'EXTRA'; }
  function ivaInit() {
    if (!S.iva || sel.dataset.ok) return; sel.dataset.ok = '1';
    $('tcrPaeseL').textContent = S.iva.domanda;
    var opts = ISO.map(function (c) { return [c, paeseNome(c)]; }).sort(function (a, b) { return a[1].localeCompare(b[1], L); });
    sel.innerHTML = '<option value="">' + esc(S.iva.scegli) + '</option>' + opts.map(function (o) { return '<option value="' + o[0] + '">' + esc(o[1]) + '</option>'; }).join('');
    var pre = ''; try { pre = localStorage.getItem('tcr_paese') || ''; } catch (e) {}
    if (!pre) { try { var rg = (navigator.language || '').split('-')[1]; if (rg) pre = rg.toUpperCase(); } catch (e) {} }
    if (pre && ISO.indexOf(pre) >= 0) sel.value = pre;
    sel.addEventListener('change', function () { try { localStorage.setItem('tcr_paese', sel.value); } catch (e) {} ivaShow(); });
    ivaShow();
  }
  function ivaShow() {
    if (!S.iva) return;
    var k = caso(sel.value), c = S.iva.casi[k] || S.iva.casi.neutro;
    Array.prototype.forEach.call(document.querySelectorAll('.tcr-ivl'), function (e) { e.textContent = c.label; });
    var ex = '';
    if (k === 'IT') { var u = packOf('ugc'); if (u && u.prezzo_da) ex = ' ' + tpl(U.esempioIva, { p: eur(u.prezzo_da), t: nf2.format(Math.round(u.prezzo_da * 122) / 100) }); }
    var box = $('tcrIva'); box.hidden = false; box.innerHTML = '<span aria-hidden="true">ⓘ</span><span>' + esc(c.nota + ex) + '</span>';
    Array.prototype.forEach.call(document.querySelectorAll('.tcr-ivn'), function (e) { e.textContent = c.nota; });
    form({ sede: sel.value ? paeseNome(sel.value) : '' });
  }

  /* ───────── pacchetti e servizi ───────── */
  function drawPacks() {
    if (!S.listino) return;
    var base = S.listino.filter(function (p) { return !p.su_misura; }), cont = S.listino.filter(function (p) { return p.su_misura; });
    $('tcrPk').innerHTML = base.map(function (p) {
      var on = p.codice === S.pack;
      return '<button type="button" class="tcr-pkc' + (on ? ' on' : '') + '" data-p="' + esc(p.codice) + '" aria-pressed="' + on + '">' +
        '<span class="tcr-ic" aria-hidden="true">' + (ICO[p.codice] || '') + '</span><h3>' + esc(p.titolo) + '</h3><p>' + esc(p.testo) + '</p>' +
        '<div class="tcr-pr">' + (p.prezzo_da ? esc(p.da) + ' ' + eur(p.prezzo_da) : esc(ucf(U.preventivo))) + ' <small>' + esc(p.unita) + ' · <span class="tcr-ivl"></span></small></div>' +
        '<span class="tcr-pick">' + esc(on ? U.scelto : U.scegli) + '</span></button>';
    }).join('');
    $('tcrCont').innerHTML = cont.map(function (p) {
      return '<div class="tcr-cont"><span class="tcr-ic" aria-hidden="true">' + (ICO[p.codice] || '') + '</span><div class="tcr-cont__t"><h3>' + esc(p.titolo) + '</h3><p>' + esc(p.testo) + '</p></div>' +
        '<div class="tcr-pr">' + esc(ucf(p.su_misura)) + '</div><a class="tcr-btn tcr-btn--p" href="#richiesta" data-form="' + esc(p.codice) + '">' + esc(U.parliamone) + '</a></div>';
    }).join('');
    ivaShow();
  }
  function drawServizi() {
    if (!S.servizi) return;
    $('tcrSv').innerHTML = S.servizi.map(function (s) {
      return '<div class="tcr-svc"><span class="tcr-ic" aria-hidden="true">' + (ICO[s.codice] || '') + '</span><h3>' + esc(s.titolo) + '</h3><p>' + esc(s.testo) + '</p>' +
        '<div class="tcr-pr">' + esc(ucf(s.prezzo)) + '</div><a class="tcr-btn tcr-btn--s" href="#richiesta" data-form="' + esc(s.codice) + '">' + esc(U.preventivo) + '</a></div>';
    }).join('');
  }
  $('tcrPk').addEventListener('click', function (e) {
    var b = e.target.closest('.tcr-pkc'); if (!b) return;
    S.pack = b.getAttribute('data-p'); S.area = ''; drawPacks(); drawFiltri(); form({ pack: S.pack }); sync(); loadVetrina();
  });
  document.addEventListener('click', function (e) { var a = e.target.closest('[data-form]'); if (a) form({ pack: a.getAttribute('data-form') }); });

  /* ───────── filtri della vetrina ───────── */
  function drawFiltri() {
    var f = S.filtri; if (!f) return;
    var tot = 0; (f.settori || []).forEach(function (s) { tot = Math.max(tot, s.creator || 0); });
    $('tcrSettori').innerHTML = '<button type="button" class="tcr-chip' + (S.settore ? '' : ' on') + '" data-s="">' + esc(U.tutti) + '</button>' +
      (f.settori || []).map(function (s) { return '<button type="button" class="tcr-chip' + (S.settore === s.codice ? ' on' : '') + '" data-s="' + esc(s.codice) + '" title="' + esc(s.testo) + '">' + esc(s.label) + ' <small>' + (s.creator || 0) + '</small></button>'; }).join('');
    var st = (f.settori || []).filter(function (s) { return s.codice === S.settore; })[0];
    $('tcrSdesc').textContent = st ? st.label + ': ' + st.testo + '. ' + tpl(U.adatti, { n: st.creator }) : '';
    form({ settore: st ? st.label : '' });
    $('tcrAreaL').textContent = U.paese; $('tcrContL').textContent = U.contenuto;
    var paesi = [], zone = [];
    (f.aree || []).forEach(function (a) { if (a.solo_presenza) zone.push(a); else paesi.push(a); });
    var h = '<option value="">' + esc(U.tuttiPaesi) + '</option>' + paesi.map(function (a) { return '<option value="' + esc(a.codice) + '">' + esc(a.label) + ' (' + (a.creator || 0) + ')</option>'; }).join('');
    if (S.pack === 'coverage' && zone.length) h += '<optgroup label="' + esc(U.zone) + '">' + zone.map(function (a) { return '<option value="' + esc(a.codice) + '">' + esc(a.label) + ' (' + (a.creator || 0) + ')</option>'; }).join('') + '</optgroup>';
    $('tcrArea').innerHTML = h; $('tcrArea').value = S.area;
    $('tcrContenuto').innerHTML = '<option value="">' + esc(U.tuttiCont) + '</option>' + (f.contenuti || []).map(function (c) { return '<option value="' + esc(c.codice) + '">' + esc(c.label) + '</option>'; }).join('');
    $('tcrContenuto').value = S.contenuto;
    if (!S.kpi && f.nicchie && !$('tcrKpiNic').textContent.trim()) $('tcrKpiNic').textContent = f.nicchie.length;
    if (!$('tcrKpiPaesi').textContent.trim()) $('tcrKpiPaesi').textContent = paesi.length;
  }
  $('tcrSettori').addEventListener('click', function (e) { var b = e.target.closest('.tcr-chip'); if (!b) return; S.settore = b.getAttribute('data-s') || ''; drawFiltri(); sync(); loadVetrina(); });
  $('tcrArea').addEventListener('change', function () { S.area = this.value; loadVetrina(); });
  $('tcrContenuto').addEventListener('change', function () { S.contenuto = this.value; loadVetrina(); });
  function sync() {
    try { var q = new URLSearchParams(location.search); if (S.pack !== 'ugc') q.set('pacchetto', S.pack); else q.delete('pacchetto'); if (S.settore) q.set('settore', S.settore); else q.delete('settore');
      var s = q.toString(); history.replaceState(null, '', location.pathname + (s ? '?' + s : '') + location.hash); } catch (e) {}
  }

  /* ───────── card della vetrina ───────── */
  function pubblico(p) {
    var i = p.insight || {}, parts = [];
    if (i.paese) parts.push(i.paese);
    if (i.donne) parts.push(i.donne + ' ' + U.donne);
    if (i.eta) parts.push(String(i.eta).replace(' (', ' ' + U.anni + ' ('));
    if (parts.length) return '<b>' + esc(U.pubblico) + ':</b> ' + esc(parts.join(' · ')) + (i.mese ? ' <em>(' + esc(i.mese) + ')</em>' : '');
    if (p.pubblico && p.pubblico.length) return '<b>' + esc(U.pubblico) + ':</b> ' + esc(p.pubblico.join(' · '));
    return esc(U.noStat);
  }
  function card(p) {
    var i = p.insight || {}, chips = [i.piattaforma, i.views ? '▶ ' + i.views : '', i.engagement ? 'ER ' + i.engagement : ''].filter(Boolean);
    var tags = (p.nicchie || []).slice(0, 2).map(function (x) { return '<span class="tcr-tg">' + esc(x) + '</span>'; }).join('') +
      ((p.contenuti || [])[0] ? '<span class="tcr-tg tcr-tg--g">' + esc(p.contenuti[0]) + '</span>' : '') +
      (p.pubblicita ? '<span class="tcr-tg tcr-tg--g">' + esc(U.ads) + '</span>' : '');
    var on = window.tcrForm && window.tcrForm.has(p.codice);
    var da = S.testi ? S.testi.da : '';
    return '<div class="tcr-cc' + (on ? ' sel' : '') + '" data-c="' + esc(p.codice) + '" data-n="' + esc(p.nome) + '" role="button" tabindex="0" aria-pressed="' + !!on + '" aria-label="' + esc(p.nome + ' #' + p.codice + ' — ' + U.add) + '">' +
      '<div class="tcr-vid"><video muted loop playsinline preload="metadata" data-src="' + esc(p.anteprima || p.video) + '"></video><div class="tcr-vid__shade"></div>' +
      (chips.length ? '<div class="tcr-ins">' + chips.map(function (x) { return '<span>' + esc(x) + '</span>'; }).join('') + '</div>' : '') +
      '<div class="tcr-who2"><img class="tcr-av" alt="" loading="lazy" src="' + esc(p.foto) + '"><div class="tcr-who"><b>' + esc(p.nome) + '<i title="' + esc(U.verificato) + '">✓</i></b><small>#' + esc(p.codice) + (p.fascia ? ' · ' + esc(p.fascia) : '') + '</small></div></div>' +
      '<span class="tcr-add" aria-hidden="true">' + (on ? '✓' : '+') + '</span></div>' +
      '<div class="tcr-cb"><div class="tcr-tags">' + tags + '</div><div class="tcr-aud">' + pubblico(p) + '</div>' +
      '<div class="tcr-price">' + (p.prezzo_da ? esc(da) + ' ' + eur(p.prezzo_da) + ' <small class="tcr-ivl"></small>' : esc(ucf(U.preventivo))) + '</div></div></div>';
  }
  function toggle(el) {
    if (!window.tcrForm) return;
    var added = window.tcrForm.toggle({ c: el.getAttribute('data-c'), n: el.getAttribute('data-n') });
    marks();
    if (added) el.classList.add('sel');
  }
  function marks() {
    Array.prototype.forEach.call(document.querySelectorAll('.tcr-cc'), function (el) {
      var on = window.tcrForm && window.tcrForm.has(el.getAttribute('data-c'));
      el.classList.toggle('sel', !!on); el.setAttribute('aria-pressed', !!on);
      var a = el.querySelector('.tcr-add'); if (a) a.textContent = on ? '✓' : '+';
    });
  }
  document.addEventListener('tcr:sel', marks);
  $('vetrina').addEventListener('click', function (e) { var c = e.target.closest('.tcr-cc'); if (c) toggle(c); });
  $('vetrina').addEventListener('keydown', function (e) { if ((e.key === 'Enter' || e.key === ' ') && e.target.classList && e.target.classList.contains('tcr-cc')) { e.preventDefault(); toggle(e.target); } });

  /* ───────── vetrina ───────── */
  var seq = 0;
  function skel(n) { var s = ''; for (var i = 0; i < n; i++) s += '<div class="tcr-sk"></div>'; return s; }
  function loadVetrina() {
    var my = ++seq, pk = packOf(S.pack);
    $('tcrVtPack').textContent = pk ? tpl(U.sez, { p: pk.titolo }) : '';
    $('tcrErr').hidden = true;
    if (S.settore) { $('tcrBands').hidden = true; $('tcrFlat').hidden = false; $('tcrFlat').innerHTML = skel(8); }
    else { $('tcrBands').hidden = false; $('tcrFlat').hidden = true; ['essenziale', 'pro', 'premium'].forEach(function (b) { $('tcrG' + b).innerHTML = skel(2); }); }
    return get('vetrina', { pacchetto: S.pack, settore: S.settore, area: S.area, contenuto: S.contenuto }).then(function (j) {
      if (my !== seq) return;
      S.v = j; S.testi = j.testi || S.testi;
      if (j.iva && !S.iva) { S.iva = j.iva; ivaInit(); }
      drawVetrina();
    }).catch(function () {
      if (my !== seq) return;
      $('tcrErr').textContent = U.errore; $('tcrErr').hidden = false;
      $('tcrFlat').innerHTML = ''; ['essenziale', 'pro', 'premium'].forEach(function (b) { $('tcrG' + b).innerHTML = ''; });
    });
  }
  function drawVetrina() {
    var j = S.v, shown = [];
    S.rest = {}; S.mx = 0;   // «Vedi altri creator»: profili ancora da mostrare e passo (0 = niente, 1 = righe aperte, 2 = invito alla richiesta)
    var wide = j.allargato || [];
    $('tcrWide').hidden = !wide.length; $('tcrWide').textContent = wide.join(' ');
    if (S.settore) {
      var tutti = j.profili || [];
      shown = tutti.slice(0, 8).sort(function (a, b) { return (a.prezzo_da || 0) - (b.prezzo_da || 0); });
      S.rest.flat = tutti.slice(8).sort(function (a, b) { return (a.prezzo_da || 0) - (b.prezzo_da || 0); });
      $('tcrFlat').innerHTML = shown.map(card).join('');
    } else {
      ['essenziale', 'pro', 'premium'].forEach(function (b) {
        var tuttiB = (j.bande || {})[b] || [], x = tuttiB.slice(0, 6); shown = shown.concat(x); S.rest[b] = tuttiB.slice(6);
        $('tcrG' + b).innerHTML = x.map(card).join(''); pari($('tcrG' + b));
        var lab = x[0] && x[0].banda_label; if (lab) document.querySelector('[data-b="' + b + '"]').textContent = lab;
        var m = x.length ? Math.min.apply(null, x.map(function (p) { return p.prezzo_da || 1e9; })) : 0;
        $('tcrH' + b).innerHTML = m && m < 1e9 ? esc(S.testi ? S.testi.da : '') + ' ' + eur(m) + ' <span class="tcr-ivl"></span>' : '';
      });
    }
    if (!S.kpi && !S.settore && !S.area && !S.contenuto) { S.kpi = true; $('tcrKpiTot').textContent = j.totale; }
    var st = S.settore && S.filtri ? (S.filtri.settori || []).filter(function (s) { return s.codice === S.settore; })[0] : null;
    var tot = st ? st.creator : j.totale;
    S.tot = tot; S.nShown = shown.length;
    $('tcrAltri').hidden = !shown.length;
    $('tcrStack').innerHTML = shown.slice(0, 5).map(function (p) { return '<img alt="" loading="lazy" src="' + esc(p.foto) + '">'; }).join('') + '<span>+' + Math.max(tot - shown.length, 0) + '</span>';
    $('tcrAltriH').textContent = U.altriH; $('tcrAltriP').innerHTML = tpl(esc(U.altriP).replace(/&lt;(\/?)b&gt;/g, '<$1b>'), { n: esc(tot) }); $('tcrAltriB').textContent = U.altriB;
    $('tcrAltriM').hidden = true; $('tcrAltriM').textContent = U.altriM || '';
    $('tcrDisc').innerHTML = '<span class="tcr-ivn"></span> ' + esc((S.testi ? S.testi.esempio : '') + ' ' + U.privacy);
    watch($('vetrina'));
    ivaShow();
  }

  /* «Vedi altri creator» (ticket 857, 07/11/2026): 1° click = altre 3 righe di creator per fascia (le card in più arrivano già dal CRM),
   * 2° click = messaggio che invita a mandare la richiesta, poi il bottone porta al modulo #richiesta. Senza altri profili si passa subito al messaggio. */
  function colonne(el, dflt) { try { var n = getComputedStyle(el).gridTemplateColumns.split(/\s+/).filter(function (t) { return /^[\d.]+(px|fr|%)$/.test(t); }).length; return n > 0 ? n : dflt; } catch (e) { return dflt; } }
  function altriApri() {
    var gs = S.settore ? [['flat', $('tcrFlat')]] : ['essenziale', 'pro', 'premium'].map(function (b) { return [b, $('tcrG' + b)]; });
    gs.forEach(function (g) {
      var list = (S.rest || {})[g[0]] || [], add = list.splice(0, colonne(g[1], S.settore ? 4 : 2) * 3);
      if (!add.length) return;
      g[1].insertAdjacentHTML('beforeend', add.map(card).join(''));
      pari(g[1]); S.nShown += add.length;
    });
    var sp = $('tcrStack').querySelector('span'); if (sp) sp.textContent = '+' + Math.max((S.tot || 0) - S.nShown, 0);
    watch($('vetrina')); marks(); ivaShow();
  }
  $('tcrAltriB').addEventListener('click', function (e) {
    if (S.mx >= 2) return;                       // dopo l'invito il bottone e' un link normale a #richiesta
    e.preventDefault();
    var resta = false; Object.keys(S.rest || {}).forEach(function (k) { if ((S.rest[k] || []).length) resta = true; });
    if (S.mx === 0 && resta) { altriApri(); S.mx = 1; return; }
    S.mx = 2; $('tcrAltriM').hidden = false; $('tcrAltriB').textContent = U.altriB2 || U.altriB;
  });

  /* ───────── apertura: 3 telefoni che ruotano tra i creator in evidenza (sempre col loro credito) ───────── */
  function drawHero(list) {
    var box = $('tcrPhones');
    if (!list || !list.length) { box.style.display = 'none'; return; }
    var pool = list.slice();
    function nic(p) { return String(p.nicchia || 'Creator').split(' & ')[0]; }
    function ph(p) {
      return '<video muted loop playsinline preload="metadata"' + (RM ? '' : ' autoplay') + ' data-src="' + esc(p.anteprima || p.video) + '"></video>' +
        '<em class="tcr-ph__nic">' + esc(nic(p)) + '</em><div class="tcr-ph__ov"><img class="tcr-av" alt="" src="' + esc(p.foto) + '">' +
        '<div class="tcr-who"><b>' + esc(p.nome) + '<i title="' + esc(U.verificato) + '">✓</i></b><small>' + esc((p.nicchia ? p.nicchia + ' · ' : '') + '#' + p.codice) + '</small></div>' +
        '<span class="tcr-ph__go">' + esc(U.scheda) + '</span></div>';
    }
    function fill(a, p) {
      a.href = p.link_scheda; a.title = p.testo_link; a.setAttribute('aria-label', p.testo_link); a.innerHTML = ph(p);
      a.style.visibility = '';
      var v = a.querySelector('video'); v.dataset.vis = '1';
      Q.unshift(v); pump();
    }
    box.innerHTML = '';
    pool.slice(0, 3).forEach(function (p) { var a = document.createElement('a'); a.className = 'tcr-ph'; box.appendChild(a); fill(a, p); });
    if (RM || pool.length <= 3) return;
    var nx = 3, turno = 0;
    setInterval(function () {
      if (document.hidden) return;
      var r = box.getBoundingClientRect(); if (r.bottom < 0 || r.top > innerHeight) return;   // fuori schermo: non si cambia
      var el = box.children[turno % 3], p = pool[nx % pool.length]; turno++; nx++;
      el.classList.add('cambia');
      setTimeout(function () { fill(el, p); el.classList.remove('cambia'); }, 450);
    }, 3500);
  }

  /* ───────── avvio ───────── */
  var pre = C.pre || {};
  S.listino = pre.listino || null; S.servizi = pre.servizi || null; S.filtri = pre.filtri || null;
  form({ pack: S.pack });
  var jobs = [];
  if (!S.listino) jobs.push(get('listino').then(function (j) { S.listino = j.pacchetti; }).catch(function () {}));
  if (!S.servizi) jobs.push(get('servizi').then(function (j) { S.servizi = j.servizi; }).catch(function () {}));
  if (!S.filtri) jobs.push(get('filtri').then(function (j) { S.filtri = j.filtri; }).catch(function () {}));
  drawPacks(); drawServizi(); drawFiltri();
  Promise.all(jobs).then(function () { drawPacks(); drawServizi(); drawFiltri(); loadVetrina(); });
  get('evidenza').then(function (j) { drawHero(j.creator); }).catch(function () { drawHero([]); });
})();
