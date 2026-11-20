/* TOA Scheda — finestra «scheda talent/creator» condivisa da /events/staff-e-prezzi/ e /creators/ (20/11/2026).
 * Galleria foto/video + dati principali + bottone «Profilo completo» (pagina pubblica /p/…, solo se il CRM la dà).
 * Dati extra da /actions/api-talent-database.php?action=talent&id=ID (stesso endpoint della scheda del Talent Database).
 * Nessun dato sensibile: solo provincia (mai il comune), nessun dettaglio per i minori.
 * Uso: toaScheda.open({lang,id,nome,codice,foto,media:[{t:'i'|'v',u}],mediaFromApi,videoFirst,badges:[],rows:[[k,v]],langs:[{n,l}],tagsTitle,tags:[],ask:{label,on}}) */
(function () {
  'use strict';
  var API = '/actions/api-talent-database.php';
  var T = {
    it: { eta: 'Età', anni: 'anni', alt: 'Altezza', tg: 'Taglia', sc: 'Scarpe', zona: 'Zona', lin: 'Lingue', prof: 'Profilo completo', x: 'Chiudi', foto: 'Foto', vid: 'Video', prec: 'Precedente', errv: 'Video non disponibile', succ: 'Successivo' },
    en: { eta: 'Age', anni: 'years', alt: 'Height', tg: 'Size', sc: 'Shoes', zona: 'Area', lin: 'Languages', prof: 'Full profile', x: 'Close', foto: 'Photo', vid: 'Video', prec: 'Previous', errv: 'Video unavailable', succ: 'Next' },
    fr: { eta: 'Âge', anni: 'ans', alt: 'Taille', tg: 'Taille vêtements', sc: 'Pointure', zona: 'Zone', lin: 'Langues', prof: 'Profil complet', x: 'Fermer', foto: 'Photo', vid: 'Vidéo', prec: 'Précédent', errv: 'Vidéo indisponible', succ: 'Suivant' },
    es: { eta: 'Edad', anni: 'años', alt: 'Altura', tg: 'Talla', sc: 'Calzado', zona: 'Zona', lin: 'Idiomas', prof: 'Perfil completo', x: 'Cerrar', foto: 'Foto', vid: 'Vídeo', prec: 'Anterior', errv: 'Vídeo no disponible', succ: 'Siguiente' }
  };
  var S = null; // stato della scheda aperta
  var root = null;

  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  function pc(n) { return String(n == null ? '' : n).toLowerCase().replace(/(^|[\s'\-])([a-zà-ÿ])/g, function (m, sep, ch) { return sep + ch.toUpperCase(); }); }
  function sz(u, w) { return /foto-talent-public/.test(u || '') && !/[?&]w=/.test(u) ? u + (u.indexOf('?') < 0 ? '?' : '&') + 'w=' + w : u; }
  function tr() { return T[S.lang] || T.it; }

  function build() {
    root = document.createElement('div');
    root.className = 'toas'; root.hidden = true;
    root.setAttribute('role', 'dialog'); root.setAttribute('aria-modal', 'true');
    root.innerHTML = '<div class="toas__bk"></div><div class="toas__box">' +
      '<button type="button" class="toas__x" aria-label="">&times;</button>' +
      '<div class="toas__gal"><div class="toas__st"></div><div class="toas__th"></div></div>' +
      '<div class="toas__in"></div></div>';
    document.body.appendChild(root);
    root.querySelector('.toas__bk').addEventListener('click', close);
    root.querySelector('.toas__x').addEventListener('click', close);
    document.addEventListener('keydown', function (e) {
      if (!S || root.hidden) return;
      if (e.key === 'Escape') close(); else if (e.key === 'ArrowRight') step(1); else if (e.key === 'ArrowLeft') step(-1);
    });
  }

  function show() {
    var st = root.querySelector('.toas__st'), it = S.items[S.i], many = S.items.length > 1, L = tr();
    if (!it) { st.innerHTML = ''; return; }
    st.innerHTML = (it.t === 'v'
      ? '<video src="' + esc(it.u) + '" poster="' + esc(sz(S.o.foto, 900)) + '" controls playsinline muted autoplay preload="auto"></video><span class="toas__er" hidden>' + esc(L.errv) + '</span>'
      : '<img src="' + esc(sz(it.u, 1100)) + '" alt="' + esc(S.nome + ' #' + S.codice) + '">') +
      (many ? '<button type="button" class="toas__nv toas__nv--p" aria-label="' + esc(L.prec) + '">&#8249;</button><button type="button" class="toas__nv toas__nv--n" aria-label="' + esc(L.succ) + '">&#8250;</button><span class="toas__cn">' + (S.i + 1) + ' / ' + S.items.length + '</span>' : '');
    var vd = st.querySelector('video');
    if (vd) {
      var er = st.querySelector('.toas__er');
      vd.addEventListener('error', function () { if (er) er.hidden = false; });
      var pr = vd.play && vd.play(); if (pr && pr.catch) pr.catch(function () {}); // parte da solo (muto); se il browser lo blocca resta il tasto play
    }
    if (many) {
      st.querySelector('.toas__nv--p').addEventListener('click', function () { step(-1); });
      st.querySelector('.toas__nv--n').addEventListener('click', function () { step(1); });
    }
    Array.prototype.forEach.call(root.querySelectorAll('.toas__t'), function (b, k) { b.classList.toggle('on', k === S.i); });
  }
  function step(d) { if (!S.items.length) return; S.i = (S.i + d + S.items.length) % S.items.length; show(); var t = root.querySelectorAll('.toas__t')[S.i]; if (t && t.scrollIntoView) { try { t.scrollIntoView({ block: 'nearest', inline: 'center' }); } catch (e) {} } }

  function thumbs() {
    var box = root.querySelector('.toas__th');
    if (S.items.length < 2) { box.innerHTML = ''; box.hidden = true; return; }
    box.hidden = false;
    box.innerHTML = S.items.map(function (it, k) {
      return '<button type="button" class="toas__t' + (k === S.i ? ' on' : '') + '" data-k="' + k + '" aria-label="' + esc((it.t === 'v' ? tr().vid : tr().foto) + ' ' + (k + 1)) + '">' +
        (it.t === 'v' ? '<span class="toas__pl">&#9654;</span>' : '<img src="' + esc(sz(it.u, 160)) + '" alt="" loading="lazy" decoding="async">') + '</button>';
    }).join('');
    Array.prototype.forEach.call(box.querySelectorAll('.toas__t'), function (b) {
      b.addEventListener('click', function () { S.i = +b.getAttribute('data-k'); show(); });
    });
  }

  function info() {
    var o = S.o, L = tr(), h = '';
    h += '<div class="toas__hd"><h2 class="toas__nm">' + esc(S.nome) + '</h2><span class="toas__cd">#' + esc(S.codice) + '</span></div>';
    if ((o.badges || []).length) h += '<div class="toas__bd">' + o.badges.filter(Boolean).map(function (b) { return '<span>' + esc(b) + '</span>'; }).join('') + '</div>';
    var rows = (o.rows || []).concat(S.rows || []).filter(function (r) { return r && r[1] != null && r[1] !== ''; });
    if (rows.length) h += '<dl class="toas__rw">' + rows.map(function (r) { return '<div><dt>' + esc(r[0]) + '</dt><dd>' + esc(r[1]) + '</dd></div>'; }).join('') + '</dl>';
    if ((o.langs || []).length) h += '<div class="toas__bl"><h3>' + esc(L.lin) + '</h3><div class="toas__ln">' + o.langs.map(function (x) { return '<span>' + esc(x.n) + (x.l ? '<em>' + esc(x.l) + '</em>' : '') + '</span>'; }).join('') + '</div></div>';
    if ((o.tags || []).length) h += '<div class="toas__bl">' + (o.tagsTitle ? '<h3>' + esc(o.tagsTitle) + '</h3>' : '') + '<div class="toas__tg">' + o.tags.filter(Boolean).map(function (x) { return '<span>' + esc(x) + '</span>'; }).join('') + '</div></div>';
    h += '<div class="toas__ft">' +
      (S.profilo ? '<a class="toas__bt toas__bt--g" href="' + esc(S.profilo) + '" target="_blank" rel="noopener">' + esc(L.prof) + ' &#8599;</a>' : '') +
      (o.ask ? '<button type="button" class="toas__bt toas__bt--p" id="toasAsk">' + esc(o.ask.label) + '</button>' : '') + '</div>';
    var el = root.querySelector('.toas__in'); el.innerHTML = h;
    var a = el.querySelector('#toasAsk');
    if (a) a.addEventListener('click', function () { var r = o.ask.on(); if (typeof r === 'string') a.textContent = r; });
  }

  function load() {
    var id = S.o.id, my = S;
    if (!id) return;
    fetch(API + '?action=talent&id=' + encodeURIComponent(id) + '&lang=' + encodeURIComponent(S.lang), { credentials: 'same-origin' })
      .then(function (r) { return r.json(); })
      .then(function (d) {
        if (S !== my || !d || !d.ok || !d.talent) return;
        var t = d.talent, L = tr(), rows = [];
        if (!t.is_minor) {
          if (t.eta) rows.push([L.eta, t.eta + ' ' + L.anni]);
          if (t.altezza) rows.push([L.alt, t.altezza + ' cm']);
          if (t.taglia) rows.push([L.tg, t.taglia]);
          if (t.scarpe) rows.push([L.sc, t.scarpe]);
          var pv = t.provincia_dom || t.provincia; // mai il comune
          if (pv) rows.push([L.zona, pc(pv)]);
          if (/^https:\/\/toagency\.it\/p\//.test(t.profilo_url || '')) my.profilo = t.profilo_url + (my.o.view ? '?v=' + encodeURIComponent(my.o.view) : ''); // apre direttamente la vista del ruolo (hostess, creator…)
        }
        my.rows = rows;
        if (my.o.mediaFromApi && !t.is_minor) {
          var ph = (d.media || []).filter(function (m) { return m && m.url && (m.tipo || 'foto') === 'foto'; }).map(function (m) { return { t: 'i', u: m.url }; });
          var vd = (d.video || []).filter(function (m) { return m && m.url; }).map(function (m) { return { t: 'v', u: m.url }; });
          var lead = (my.o.media || []).filter(function (m) { return m.t === 'v' && !vd.some(function (v) { return v.u === m.u; }); });
          vd = lead.concat(vd); // il video scelto dalla pagina resta il primo
          var all = my.o.videoFirst ? vd.concat(ph) : ph.concat(vd);
          if (all.length > my.items.length || (all.length && !my.items.length)) { my.items = all; my.i = 0; thumbs(); show(); }
        }
        info();
      }).catch(function () {});
  }

  function open(o) {
    if (!root) build();
    S = { o: o, lang: T[o.lang] ? o.lang : 'it', nome: pc(o.nome), codice: o.codice, i: 0, items: [], rows: [], profilo: '', back: document.activeElement };
    S.items = (o.media && o.media.length) ? o.media.slice() : (o.foto ? [{ t: 'i', u: o.foto }] : []);
    root.querySelector('.toas__x').setAttribute('aria-label', tr().x);
    root.setAttribute('aria-label', S.nome + ' #' + S.codice);
    root.hidden = false; document.documentElement.style.overflow = 'hidden';
    thumbs(); show(); info();
    root.querySelector('.toas__x').focus();
    load();
  }
  function close() {
    if (!root || root.hidden) return;
    var b = S && S.back, cb = S && S.o.onClose;
    root.hidden = true; root.querySelector('.toas__st').innerHTML = ''; document.documentElement.style.overflow = '';
    S = null;
    if (cb) { try { cb(); } catch (e) {} }
    if (b && b.focus) { try { b.focus({ preventScroll: true }); } catch (e) {} }
  }
  window.toaScheda = { open: open, close: close };
})();
