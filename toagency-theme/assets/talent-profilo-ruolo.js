/**
 * toagency-theme/assets/talent-profilo-ruolo.js — sotto-schede per ruolo nel self-edit del talent
 * Creato 2026-09-23 marco — chat CRM - RUOLI MULTI-SCHEDA (Fase 3). ⚠️ BOZZA, NON DEPLOYATA.
 *
 * + (23/09, richiesta Marco) MENU FISSO IN ALTO + SEZIONI APRI/CHIUDI per tutta la pagina: vedi secondo blocco in fondo.
 *
 * Il tema NON copia il catalogo: lo chiede al CRM (actions/talent-profilo-ruolo.php) e disegna da solo
 * una sezione per ogni ruolo del talent (attore, comparsa, modello...). Campo nuovo nel catalogo CRM =
 * compare qui senza toccare il tema. Usa le classi gia' esistenti del self-edit (tse-section, tse-chip...).
 *
 * Le sezioni si salvano col pulsante "Salva" gia' esistente della pagina (#tse-btn-save).
 * Nel template page-talent-self-edit.php bastano 2 righe (dopo la sezione Hostess & Eventi):
 *   <div id="tse-profili-ruolo" data-api="https://toagency.it/crm_toagency/actions/talent-profilo-ruolo.php"></div>
 *   <script src=".../assets/talent-profilo-ruolo.js?v=20260923rp1" defer></script>
 */
(function () {
  var box = document.getElementById('tse-profili-ruolo');
  if (!box) return;
  var qs = new URLSearchParams(location.search);
  var uuid = qs.get('uuid') || '', tok = qs.get('t') || '';
  var lang = ['it', 'en', 'fr', 'es'].indexOf(qs.get('lang')) >= 0 ? qs.get('lang') : ((document.documentElement.lang || 'it').slice(0, 2));
  if (['it', 'en', 'fr', 'es'].indexOf(lang) < 0) lang = 'it';
  if (!uuid || !tok) return;
  var API = box.getAttribute('data-api');
  var TXT = {
    it: { salva: 'Salva', ok: 'Salvato ✓', err: 'Non salvato, riprova' },
    en: { salva: 'Save', ok: 'Saved ✓', err: 'Not saved, try again' },
    fr: { salva: 'Enregistrer', ok: 'Enregistré ✓', err: 'Non enregistré, réessaie' },
    es: { salva: 'Guardar', ok: 'Guardado ✓', err: 'No guardado, inténtalo de nuevo' }
  }[lang];
  var salvataggi = [];
  function el(tag, cls, txt) { var e = document.createElement(tag); if (cls) e.className = cls; if (txt != null) e.textContent = txt; return e; }

  fetch(API + '?uuid=' + encodeURIComponent(uuid) + '&t=' + encodeURIComponent(tok) + '&lang=' + lang, { cache: 'no-store' })
    .then(function (r) { return r.json(); })
    .then(function (j) {
      if (!j || !j.success || !j.sezioni || !j.sezioni.length) return;
      j.sezioni.forEach(function (s) {
        var dati = (j.dati && j.dati[s.ruolo]) || {};
        var sec = el('div', 'tse-section tse-rp-section'); sec.id = 'tse-rp-' + s.ruolo;
        sec.appendChild(el('div', 'tse-section-title', (s.icona || '') + ' ' + s.titolo));
        s.campi.forEach(function (c) {
          var f = el('div', 'tse-field'); f.appendChild(el('label', 'tse-label', c.label));
          if (c.tipo === 'multi') {
            var ch = el('div', 'tse-chips'); ch.setAttribute('data-k', c.k);
            c.valori.forEach(function (v) {
              var l = el('label', 'tse-chip'), i = document.createElement('input');
              i.type = 'checkbox'; i.value = v.v; i.checked = (dati[c.k] || []).indexOf(v.v) >= 0;
              l.appendChild(i); l.appendChild(document.createTextNode(v.l)); ch.appendChild(l);
            });
            f.appendChild(ch);
          } else if (c.tipo === 'uno') {
            var sel = el('select', 'tse-select'); sel.setAttribute('data-k', c.k);
            sel.appendChild(el('option', null, '—')).value = '';
            c.valori.forEach(function (v) { var o = el('option', null, v.l); o.value = v.v; if (dati[c.k] === v.v) o.selected = true; sel.appendChild(o); });
            f.appendChild(sel);
          } else {
            var inp = el('input', 'tse-input'); inp.type = c.tipo === 'url' ? 'url' : 'text';
            inp.maxLength = c.max || 120; inp.value = dati[c.k] || ''; inp.setAttribute('data-k', c.k);
            f.appendChild(inp);
          }
          sec.appendChild(f);
        });
        var msg = el('div', 'tse-rp-msg'); msg.style.cssText = 'font-size:12px;color:#c8ff00;margin-top:6px;min-height:14px;';
        sec.appendChild(msg);
        salvataggi.push(function () {
          var d = {};
          sec.querySelectorAll('[data-k]').forEach(function (n) {
            var k = n.getAttribute('data-k');
            if (n.classList.contains('tse-chips')) { d[k] = []; n.querySelectorAll('input:checked').forEach(function (i) { d[k].push(i.value); }); }
            else d[k] = n.value;
          });
          msg.textContent = '…';
          return fetch(API, { method: 'POST', keepalive: true, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ uuid: uuid, t: tok, ruolo: s.ruolo, dati: d }) })
            .then(function (r) { return r.json(); })
            .then(function (r) { msg.textContent = r && r.success ? TXT.ok : TXT.err; msg.style.color = r && r.success ? '#c8ff00' : '#f87171'; })
            .catch(function () { msg.textContent = TXT.err; msg.style.color = '#f87171'; });
        });
        box.appendChild(sec);
      });
      // Si salvano INSIEME al pulsante "Salva" gia' esistente della pagina (un solo gesto per il talent).
      // Se il pulsante non c'e' (pagina diversa), compare un pulsante proprio in fondo alle sezioni.
      var main = document.getElementById('tse-btn-save');
      var tutti = function () { salvataggi.forEach(function (f) { f(); }); };
      if (main) main.addEventListener('click', tutti);
      else { var b = el('button', 'tse-btn-save', TXT.salva); b.type = 'button'; b.onclick = tutti; box.appendChild(b); }
      if (window.tseNavRefresh) window.tseNavRefresh(); // le sezioni nuove entrano nel menu in alto
    })
    .catch(function () { /* sezione facoltativa: se il CRM non risponde il resto della pagina funziona uguale */ });
})();


/**
 * MENU FISSO + SEZIONI APRI/CHIUDI (23/09/2026 marco, chat CRM - RUOLI MULTI-SCHEDA)
 * La pagina "modifica la tua scheda" era una colonna lunghissima. Qui, senza toccare il template ne'
 * talent-self-edit.js:
 *  - un menu a pillole resta fisso in alto (scorre di lato sul telefono): tocchi "📍 Indirizzo" e ci vai;
 *  - le sezioni del modulo si aprono/chiudono toccando il titolo; ne resta aperta UNA alla volta;
 *  - all'apertura e' aperta la prima, oppure quella del link (#tse-ev-section della campagna eventi);
 *  - se il codice esistente fa scrollIntoView su una sezione chiusa (es. "aggiungi hostess"), si apre da sola.
 * Il Salva resta uno solo e salva tutto, anche le sezioni chiuse (chiuse = solo nascoste alla vista).
 */
(function () {
  var form = document.getElementById('tse-form');
  if (!form) return;
  var css = document.createElement('style');
  css.textContent =
    '.tse-nav{position:sticky;top:0;z-index:60;display:flex;gap:6px;overflow-x:auto;padding:8px 2px;margin:0 0 12px;background:#0a0a0a;border-bottom:1px solid #2a2a2e;scrollbar-width:none;-webkit-overflow-scrolling:touch}' +
    '.tse-nav::-webkit-scrollbar{display:none}' +
    '.tse-nav a{flex:0 0 auto;font-size:12px;line-height:1;color:#e5e7eb;background:#1a1a1e;border:1px solid #2a2a2e;border-radius:99px;padding:8px 11px;text-decoration:none;white-space:nowrap}' +
    '.tse-nav a.on{border-color:#c8ff00;color:#c8ff00}' +
    '#tse-form .tse-section>.tse-section-title{cursor:pointer;position:relative;padding-right:26px;user-select:none}' +
    '#tse-form .tse-section>.tse-section-title::after{content:"▾";position:absolute;right:4px;top:50%;transform:translateY(-50%);font-size:14px;opacity:.8}' +
    '#tse-form .tse-section.tse-chiusa>.tse-section-title::after{content:"▸"}' +
    '#tse-form .tse-section.tse-chiusa{padding-bottom:10px}' +
    '#tse-form .tse-section.tse-chiusa>:not(.tse-section-title){display:none!important}';
  document.head.appendChild(css);

  var nav = document.createElement('nav');
  nav.className = 'tse-nav';
  form.insertBefore(nav, form.firstChild);

  // altezza di un'eventuale testata fissa del sito: il menu si mette subito sotto
  function testata() {
    var h = 0;
    document.querySelectorAll('header, #masthead, .site-header, .toa-header').forEach(function (e) {
      var p = getComputedStyle(e).position;
      if (p === 'fixed' || p === 'sticky') h = Math.max(h, e.getBoundingClientRect().height);
    });
    return Math.round(h);
  }
  function sezioni() { return Array.prototype.slice.call(form.querySelectorAll('.tse-section')).filter(function (s) { return s.querySelector(':scope>.tse-section-title'); }); }
  function apri(sec, scorri) {
    sezioni().forEach(function (s) { s.classList.toggle('tse-chiusa', s !== sec); });
    evidenzia(sec);
    if (scorri) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
  function evidenzia(sec) {
    nav.querySelectorAll('a').forEach(function (a) { a.classList.toggle('on', a._sec === sec); });
    var on = nav.querySelector('a.on'); if (on && on.scrollIntoView) { try { nav.scrollLeft = on.offsetLeft - 20; } catch (e) {} }
  }

  // se il codice della pagina porta la vista su una sezione chiusa, prima la apre
  var orig = Element.prototype.scrollIntoView;
  Element.prototype.scrollIntoView = function () {
    var sec = this.closest ? this.closest('#tse-form .tse-section') : null;
    if (sec && sec.classList.contains('tse-chiusa')) apri(sec, false);
    return orig.apply(this, arguments);
  };

  var avviato = false;
  // FIX 23/09: link diretto a una sezione (campagne: #tse-ev-section, #tse-rp-actor, #tse-rp-comparsa...)
  var voluto = (location.hash || '').slice(1), onorato = false;
  if (/^tse-(ev-section|rp-[a-z_]+)$/.test(voluto)) {
    // la pagina parte dal riepilogo: apro io il modulo, cosi' il talent arriva dritto alla sezione giusta
    var prove = 0, iv = setInterval(function () {
      var card = document.getElementById('tse-statuscard');
      if (!aperto && card && card.style.display === 'block' && typeof window.talentShowForm === 'function') { aperto = true; window.talentShowForm(); }
      var sec = document.getElementById(voluto);
      if (aperto && sec && form.offsetHeight > 0 && !sec.classList.contains('tse-chiusa')) { clearInterval(iv); setTimeout(function () { sec.scrollIntoView({ behavior: 'smooth', block: 'start' }); }, 200); }
      if (++prove > 60) clearInterval(iv);
    }, 250), aperto = false;
  } else voluto = '';
  function refresh() {
    var top = testata();
    if (nav.style.top !== top + 'px') nav.style.top = top + 'px';
    nav.innerHTML = '';
    var lista = [];
    var foto = document.getElementById('tse-foto-section');
    if (foto && foto.style.display !== 'none') lista.push({ el: foto, fuori: true });
    sezioni().forEach(function (s) { if (!s.hidden && s.style.display !== 'none') lista.push({ el: s }); });
    var video = document.getElementById('tse-video-section');
    if (video && video.style.display !== 'none') lista.push({ el: video, fuori: true });
    lista.forEach(function (x) {
      var t = x.el.querySelector('.tse-section-title');
      if (!t) return;
      if (x.el.style.scrollMarginTop !== (top + 56) + 'px') x.el.style.scrollMarginTop = (top + 56) + 'px';
      var a = document.createElement('a');
      a.href = '#'; a.textContent = t.textContent.trim().replace(/\s+/g, ' ').slice(0, 26); a._sec = x.el;
      a.onclick = function (ev) { ev.preventDefault(); if (x.fuori) { x.el.scrollIntoView({ behavior: 'smooth', block: 'start' }); evidenzia(x.el); } else apri(x.el, true); };
      nav.appendChild(a);
    });
    var tutte = sezioni();
    tutte.forEach(function (s) {
      if (s._tseInit) return;
      s._tseInit = true;
      s.querySelector(':scope>.tse-section-title').addEventListener('click', function () {
        if (s.classList.contains('tse-chiusa')) apri(s, true); else { s.classList.add('tse-chiusa'); evidenzia(null); }
      });
      if (avviato) s.classList.add('tse-chiusa'); // sezioni arrivate dopo (ruoli): chiuse
    });
    if (!avviato && tutte.length) {
      avviato = true;
      var h = voluto ? document.getElementById(voluto) : null;
      var target = (h && tutte.indexOf(h) >= 0) ? h : tutte[0];
      apri(target, false);
      if (target === h) onorato = true;
    } else if (!onorato && voluto && document.getElementById(voluto) && tutte.indexOf(document.getElementById(voluto)) >= 0) {
      onorato = true; // la sezione del link (es. #tse-rp-actor) arriva dopo: la apro appena c'e'
      apri(document.getElementById(voluto), true);
    } else {
      var aperta = tutte.filter(function (s) { return !s.classList.contains('tse-chiusa'); })[0];
      evidenzia(aperta || null);
    }
  }
  window.tseNavRefresh = refresh;
  refresh();
  // il modulo compare dopo "Modifica scheda" e dopo il caricamento dati: ricalcolo quando cambia la pagina
  var t0 = null;
  new MutationObserver(function (rec) {
    for (var i = 0; i < rec.length; i++) { var n = rec[i].target; if (n !== nav && !nav.contains(n)) { clearTimeout(t0); t0 = setTimeout(refresh, 300); return; } }
  })
    .observe(document.body, { attributes: true, attributeFilter: ['style', 'hidden'], subtree: true });
})();
