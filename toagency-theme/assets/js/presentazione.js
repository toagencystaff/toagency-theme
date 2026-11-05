/* Presentazione TOAgency (ticket #824): griglia brand che cambia + eventi GTM. Dati in window.TOA_PRES. */
(function () {
  'use strict';
  var cfg = window.TOA_PRES || {};
  var B = cfg.brands || [];

  /* --- eventi per GTM (clic su preventivo e scarico PDF) --- */
  document.addEventListener('click', function (ev) {
    var a = ev.target.closest ? ev.target.closest('[data-pr-ev]') : null;
    if (!a) return;
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ event: a.getAttribute('data-pr-ev'), presentazione_lingua: cfg.lang || '', link_url: a.getAttribute('href') });
  });

  /* --- griglia brand: 12 caselle, ognuna cambia a turno (ordine mescolato a ogni giro) --- */
  var grid = document.getElementById('prBrands');
  if (!grid || B.length < 13) return;
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

  var N = B.length, SLOTS = 12;
  var cells = grid.querySelectorAll('.pr-bw__cell > span');
  if (cells.length !== SLOTS) return;
  var slots = [], i;
  for (i = 0; i < SLOTS; i++) slots.push(i);
  var next = SLOTS, last = -1, ord = null, oi = 0;

  function fill(el, b) {
    el.className = b.c;
    el.textContent = b.t;
    if (b.sub) { var s = document.createElement('small'); s.textContent = b.sub; el.appendChild(s); }
  }
  function newOrder() {
    var o = [], k, j, x;
    for (k = 0; k < SLOTS; k++) o.push(k);
    for (k = SLOTS - 1; k > 0; k--) { j = Math.floor(Math.random() * (k + 1)); x = o[k]; o[k] = o[j]; o[j] = x; }
    if (o[0] === last) o.push(o.shift());
    return o;
  }
  function tick() {
    if (document.hidden) return;
    if (!ord || oi >= SLOTS) { ord = newOrder(); oi = 0; }
    var p = ord[oi++]; last = p;
    var el = cells[p];
    el.classList.add('bw-off');
    setTimeout(function () {
      while (slots.indexOf(next % N) !== -1) next++;
      slots[p] = next % N;
      fill(el, B[next % N]);
      next = (next + 1) % N;
      el.classList.remove('bw-off');
    }, 230);
  }
  setInterval(tick, 850);
})();
