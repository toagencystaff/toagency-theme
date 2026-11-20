/* TOA Database — pagina hub «Database» (TEMA DATABASE-MENU, 19/11/2026) — IT/EN/FR/ES.
 * Si sceglie il gruppo, poi il ruolo: i profili arrivano dal CRM (mai calcolati qui).
 *   Talent database  POST /actions/api-talent-database.php?action=search   (paginato dal server)
 *   Creative Network GET  /crm_toagency/actions/crew-public-search.php     (elenco intero, filtrato qui per categoria)
 *   Creator          GET  /actions/creator-vetrina-public.php?action=vetrina (solo con video; anteprima solo al passaggio del mouse)
 * Regole: solo provincia (mai comune) · minorenni senza foto · mai creator con la sola foto · nessun prezzo.
 * Fetch con credentials 'same-origin' (cookie della verifica anti-robot SiteGround). */
(function () {
  'use strict';
  var cfgEl = document.getElementById('tdhCfg');
  if (!cfgEl) return;
  var C; try { C = JSON.parse(cfgEl.textContent); } catch (e) { return; }
  var L = C.lang || 'it', U = C.ui || {}, GR = C.groups || [], EP = C.ep || {}, BASE = C.base || {};
  var PER = 24;
  var LOC = { it: 'it-IT', en: 'en-GB', fr: 'fr-FR', es: 'es-ES' }[L] || 'it-IT';
  var nf = new Intl.NumberFormat(LOC, { useGrouping: 'always' });
  var FEW = C.few || 3, ask = $('tdhAsk');
  var RM = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
  var HOVER = window.matchMedia && matchMedia('(hover: hover)').matches;
  function $(id) { return document.getElementById(id); }
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function addq(u, q) { return u + (u.indexOf('?') > -1 ? '&' : '?') + q; }

  var grid = $('tdhGrid'), rolesEl = $('tdhRoles'), msg = $('tdhMsg'), more = $('tdhMore'), allEl = $('tdhAll'), titleEl = $('tdhTitle');
  var S = { gen: 0, g: null, r: null, src: '', items: [], shown: 0, page: 0, pages: 0, total: null };
  var cache = { crew: null, creator: null, tot: {} };
  var catLabel = {};
  GR.forEach(function (g) { g.roles.forEach(function (r) { if (r.cats) r.cats.forEach(function (c) { if (!catLabel[c]) catLabel[c] = r.n; }); }); });

  /* ───────── dati ───────── */
  function jget(url) { return fetch(url, { credentials: 'same-origin' }).then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); }); }
  function tfetch(q, page, per) {
    var body = { page: page, per_page: per };
    Object.keys(q || {}).forEach(function (k) { body[k] = q[k]; });
    return fetch(EP.talent + '?action=search&_v=' + Date.now(), { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(body) })
      .then(function (r) { return r.ok ? r.json() : Promise.reject(r.status); })
      .then(function (j) { return j && j.ok ? j : Promise.reject('ko'); });
  }
  function crewLoad() {
    if (!cache.crew) cache.crew = jget(EP.crew + '?lang=' + encodeURIComponent(L)).then(function (j) { return (j && j.results) || []; }).catch(function (e) { cache.crew = null; throw e; });
    return cache.crew;
  }
  function creatorLoad() {
    if (!cache.creator) cache.creator = jget(EP.creator + '?action=vetrina&pacchetto=ugc&lang=' + encodeURIComponent(L)).then(function (j) {
      var seen = {}, out = [];
      ((j && j.profili) || []).forEach(function (p) { if (!p || seen[p.id] || !(p.anteprima || p.video)) return; seen[p.id] = 1; out.push(p); });   // mai creator con la sola foto
      return out;
    }).catch(function (e) { cache.creator = null; throw e; });
    return cache.creator;
  }
  function crewOf(role, list) {
    return list.filter(function (c) { return (c.categorie || []).some(function (k) { return role.cats.indexOf(k) > -1; }); });
  }

  /* ───────── card ───────── */
  function imgTag(src, alt, style, idx) {
    if (!src) return '';
    return '<img class="tdh-img" src="' + esc(src) + '" alt="' + esc(alt) + '"' + (idx < 8 ? '' : ' loading="lazy"') + ' decoding="async"' + (style ? ' style="' + style + '"' : '') + '>';
  }
  function cardTalent(t, i) {
    var id = parseInt(t.id, 10) || 0, href = addq(BASE.talent, 'tid=' + id);
    var meta = [];
    if (t.eta) meta.push(esc(t.eta + ' ' + U.years));
    if (t.provincia) meta.push(esc(t.provincia));
    if (t.is_minor) {
      return '<a class="tdh-c tdh-c--lock" href="' + esc(href) + '"><div class="tdh-lock"><svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg><span>' + esc(U.lock) + '</span></div>' +
        '<div class="tdh-m"><b>' + esc(t.nome || '') + '</b><span>' + meta.join(' · ') + '</span></div></a>';
    }
    var src = t.cover || (t.foto ? t.foto + '&w=400' : '/actions/foto-talent-public.php?id=' + encodeURIComponent(id) + '&w=400');
    var rot = parseInt(t.foto_rotazione, 10) || 0, pos = (typeof t.foto_position === 'string' && /^\d{1,3}%\s+\d{1,3}%$/.test(t.foto_position.trim())) ? t.foto_position.trim() : '';
    var st = []; if (rot) st.push('transform:rotate(' + rot + 'deg) scale(' + ((rot === 90 || rot === 270) ? 1.35 : 1) + ')'); if (pos) st.push('object-position:' + pos);
    return '<a class="tdh-c" href="' + esc(href) + '">' + imgTag(src, t.nome || '', st.join(';'), i) +
      '<div class="tdh-m"><b>' + esc(t.nome || '') + '</b><span>' + meta.join(' · ') + '</span></div></a>';
  }
  function cardCrew(c, i) {
    var href = addq(BASE.crew, 'uuid=' + encodeURIComponent(c.uuid || ''));
    var labs = [];
    (c.categorie || []).forEach(function (k) { var n = catLabel[k]; if (n && labs.indexOf(n) < 0) labs.push(n); });
    var prov = c.provincia ? String(c.provincia) : '';
    if (c.paese && c.paese !== 'IT') prov = (prov ? prov + ' · ' : '') + c.paese;
    var ini = String(c.nome || '?').charAt(0).toUpperCase();
    return '<a class="tdh-c' + (c.foto_profilo_url ? '' : ' tdh-c--ph') + '" href="' + esc(href) + '">' +
      (c.foto_profilo_url ? imgTag(encodeURI(c.foto_profilo_url), c.nome || '', '', i) : '<span class="tdh-ini">' + esc(ini) + '</span>') +
      (c.n_video > 0 ? '<span class="tdh-play" aria-hidden="true">▶</span>' : '') +
      '<div class="tdh-m"><b>' + esc(c.nome || '') + '</b><span>' + esc(labs.slice(0, 2).join(' · ')) + (prov ? '<em>' + esc(prov) + '</em>' : '') + '</span></div></a>';
  }
  function cardCreator(p, i) {
    var href = addq(BASE.talent, 'tid=' + (parseInt(p.id, 10) || 0));
    return '<a class="tdh-c tdh-c--v" href="' + esc(href) + '" data-prev="' + esc(p.anteprima || '') + '">' + imgTag(p.foto ? p.foto + '&w=400' : '', p.nome || '', '', i) +
      '<span class="tdh-play" aria-hidden="true">▶</span>' +
      '<div class="tdh-m"><b>' + esc(p.nome || '') + '</b><span>' + esc((p.nicchie && p.nicchie[0]) || 'Creator') + '</span></div></a>';
  }
  var CARD = { talent: cardTalent, crew: cardCrew, creator: cardCreator };

  /* anteprima video dei creator: solo col mouse sopra (mai sul telefono: banda), muta, si toglie appena si esce */
  function hoverBind(card) {
    var u = card.getAttribute('data-prev');
    if (!u || !HOVER || RM) return;
    var v = null;
    card.addEventListener('mouseenter', function () {
      v = document.createElement('video'); v.className = 'tdh-vid'; v.muted = true; v.loop = true; v.playsInline = true; v.autoplay = true; v.src = u;
      card.insertBefore(v, card.firstChild); var pr = v.play(); if (pr && pr.catch) pr.catch(function () {});
    });
    card.addEventListener('mouseleave', function () { if (v) { v.pause(); v.removeAttribute('src'); v.load(); if (v.parentNode) v.parentNode.removeChild(v); v = null; } });
  }

  /* ───────── disegno ───────── */
  function showMsg(t) { msg.textContent = t || ''; msg.hidden = !t; }
  function skeleton() { var h = ''; for (var i = 0; i < 8; i++) h += '<div class="tdh-sk" aria-hidden="true"></div>'; grid.innerHTML = h; showMsg(''); more.hidden = true; if (ask) ask.hidden = true; }
  function appendCards(list, from, src) {
    var h = '', fn = CARD[src];
    list.forEach(function (x, j) { h += fn(x, from + j); });
    grid.insertAdjacentHTML('beforeend', h);
    if (src === 'creator') Array.prototype.forEach.call(grid.querySelectorAll('.tdh-c--v:not([data-b])'), function (c) { c.setAttribute('data-b', '1'); hoverBind(c); });
  }
  function setAll(role, total) {
    allEl.href = role.all || '#';
    allEl.textContent = U.all + (total ? ' (' + nf.format(total) + ')' : '') + ' →';
    allEl.hidden = !role.all;
  }
  function syncUrl() {
    try {
      var p = new URLSearchParams(location.search);
      p.set('gruppo', S.g.k); p.set('ruolo', S.r.k);
      history.replaceState(history.state, '', location.pathname + '?' + p.toString() + location.hash);
    } catch (e) {}
  }
  function track() {
    try { (window.dataLayer = window.dataLayer || []).push({ event: 'database_hub_role', hub_group: S.g.k, hub_role: S.r.k }); } catch (e) {}
  }

  /* ───────── ruoli (pillole) ───────── */
  function buildRoles(g) {
    if (g.k === 'creator') {
      return creatorLoad().then(function (list) {
        var seen = {}, out = [{ k: 'tutti', n: U.tutti, src: 'creator', niche: '', all: g.roles[0].all }];
        list.forEach(function (p) { (p.nicchie || []).forEach(function (n) { if (!seen[n]) { seen[n] = 1; out.push({ k: 'n-' + out.length, n: n, src: 'creator', niche: n, all: g.roles[0].all }); } }); });
        return out.length > 2 ? out : out.slice(0, 1);
      });
    }
    return Promise.resolve(g.roles);   // tutti i ruoli, anche senza profili: sotto i FEW profili compare «contattaci»
  }
  function renderPills(roles) {
    rolesEl.innerHTML = roles.map(function (r) {
      var n = cache.tot[r.k];
      return '<button type="button" class="tdh-pill" data-r="' + esc(r.k) + '" aria-pressed="false"><span>' + esc(r.n) + '</span><i class="tdh-n">' + (n ? nf.format(n) : '') + '</i></button>';
    }).join('');
    S.roles = roles;
  }
  function markPills() {
    Array.prototype.forEach.call(rolesEl.querySelectorAll('.tdh-pill'), function (b) { b.setAttribute('aria-pressed', S.r && b.getAttribute('data-r') === S.r.k ? 'true' : 'false'); });
  }
  function fillCounts(roles, gen) {   // numeri veri dal CRM, solo per i ruoli del Talent database (per Creative Network e Creator il CRM non da' un totale)
    roles.forEach(function (r) {
      if (r.src !== 'talent') return;
      var done = function (n) {
        cache.tot[r.k] = n;
        if (gen !== S.gen && S.g && S.g.roles.indexOf(r) < 0) return;
        var b = rolesEl.querySelector('.tdh-pill[data-r="' + r.k + '"] .tdh-n'); if (b && n) b.textContent = nf.format(n);
      };
      if (cache.tot[r.k]) return done(cache.tot[r.k]);
      tfetch(r.q, 1, 1).then(function (j) { done(parseInt(j.total, 10) || 0); }).catch(function () {});
    });
  }

  /* ───────── selezione ───────── */
  function selectRole(role) {
    var gen = ++S.gen;
    S.r = role; S.src = role.src; S.items = []; S.shown = 0; S.page = 0; S.pages = 0; S.total = null;
    markPills(); syncUrl(); track();
    titleEl.textContent = role.n;
    setAll(role, cache.tot[role.k] || 0);
    skeleton();
    var fail = function () { if (gen !== S.gen) return; grid.innerHTML = ''; showMsg(U.err); more.hidden = true; };
    var fin = function (list, total) {
      if (gen !== S.gen) return;
      grid.innerHTML = '';
      if (ask) ask.hidden = !(role.src === 'crew' && list.length < FEW);   // pochi profili: si mostrano e sotto c'e' «contattaci»
      if (!list.length) { more.hidden = true; return; }
      S.items = list; S.shown = Math.min(PER, list.length);
      appendCards(list.slice(0, S.shown), 0, role.src);
      more.hidden = !(S.shown < list.length);
    };
    if (role.src === 'talent') {
      tfetch(role.q, 1, PER).then(function (j) {
        if (gen !== S.gen) return;
        S.page = 1; S.pages = parseInt(j.pages, 10) || 1; S.total = parseInt(j.total, 10) || 0;
        cache.tot[role.k] = S.total; setAll(role, S.total);
        var b = rolesEl.querySelector('.tdh-pill[data-r="' + role.k + '"] .tdh-n'); if (b && S.total) b.textContent = nf.format(S.total);
        grid.innerHTML = '';
        var list = j.results || [];
        if (!list.length) { showMsg(U.empty); more.hidden = true; return; }
        S.items = list; S.shown = list.length;
        appendCards(list, 0, 'talent');
        more.hidden = !(S.page < S.pages);
      }).catch(fail);
    } else if (role.src === 'contact') {
      grid.innerHTML = ''; more.hidden = true; if (ask) ask.hidden = false;
    } else if (role.src === 'crew') {
      crewLoad().then(function (all) { fin(crewOf(role, all)); }).catch(fail);
    } else {
      creatorLoad().then(function (all) {
        fin(role.niche ? all.filter(function (p) { return (p.nicchie || []).indexOf(role.niche) > -1; }) : all);
      }).catch(fail);
    }
  }
  function selectGroup(gk, rk) {
    var g = GR.filter(function (x) { return x.k === gk; })[0] || GR[0];
    if (!g) return;
    var gen = ++S.gen;
    S.g = g; S.r = null;
    Array.prototype.forEach.call(document.querySelectorAll('.tdh-g'), function (b) { var on = b.getAttribute('data-g') === g.k; b.setAttribute('aria-selected', on ? 'true' : 'false'); b.classList.toggle('on', on); });
    rolesEl.innerHTML = ''; skeleton(); titleEl.textContent = g.n;
    buildRoles(g).then(function (roles) {
      if (gen !== S.gen) return;
      if (!roles.length) { grid.innerHTML = ''; showMsg(U.empty); return; }
      renderPills(roles); fillCounts(roles, gen);
      var r = roles.filter(function (x) { return x.k === rk; })[0] || roles[0];
      selectRole(r);
    }).catch(function () { if (gen !== S.gen) return; grid.innerHTML = ''; showMsg(U.err); });
  }

  /* ───────── eventi ───────── */
  Array.prototype.forEach.call(document.querySelectorAll('.tdh-g'), function (b) {
    b.addEventListener('click', function () { if (S.g && S.g.k === b.getAttribute('data-g')) return; selectGroup(b.getAttribute('data-g')); });
  });
  rolesEl.addEventListener('click', function (e) {
    var b = e.target.closest ? e.target.closest('.tdh-pill') : null; if (!b) return;
    var r = (S.roles || []).filter(function (x) { return x.k === b.getAttribute('data-r'); })[0];
    if (r && (!S.r || S.r.k !== r.k)) selectRole(r);
  });
  more.addEventListener('click', function () {
    if (S.src === 'talent') {
      if (S.page >= S.pages) return;
      var gen = S.gen; more.disabled = true;
      tfetch(S.r.q, S.page + 1, PER).then(function (j) {
        more.disabled = false; if (gen !== S.gen) return;
        S.page++; var list = j.results || [], from = S.items.length;
        S.items = S.items.concat(list); appendCards(list, from, 'talent');
        more.hidden = !(S.page < S.pages);
      }).catch(function () { more.disabled = false; showMsg(U.err); });
    } else {
      var from = S.shown; S.shown = Math.min(S.shown + PER, S.items.length);
      appendCards(S.items.slice(from, S.shown), from, S.src);
      more.hidden = !(S.shown < S.items.length);
    }
  });
  grid.addEventListener('error', function (e) { var t = e.target; if (t && t.tagName === 'IMG') t.classList.add('is-ko'); }, true);

  /* ───────── partenza (anche da link ?gruppo=…&ruolo=…) ───────── */
  var q = new URLSearchParams(location.search), g0 = q.get('gruppo') || '', r0 = q.get('ruolo') || '';
  if (!g0 && r0) GR.forEach(function (g) { g.roles.forEach(function (r) { if (r.k === r0) g0 = g.k; }); });
  selectGroup(g0 || (GR[0] && GR[0].k), r0);
})();
