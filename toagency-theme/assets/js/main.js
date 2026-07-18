/**
 * TOAgency Theme — main.js
 */

document.addEventListener('DOMContentLoaded', function() {

  // ══════════════════════════════════════════
  // ROTATING TITLE (Homepage only)
  // ══════════════════════════════════════════
  var rotEl = document.getElementById('rotatingWord');
  if (rotEl) {
    var words = ['Modelli','Hostess','Attori','Fotografi','Videomaker','Comparse','Creator','Steward','Truccatori'];
    var wordIdx = 0;
    setInterval(function() {
      rotEl.style.opacity = '0';
      setTimeout(function() {
        wordIdx = (wordIdx + 1) % words.length;
        rotEl.textContent = words[wordIdx];
        rotEl.style.opacity = '1';
      }, 200);
    }, 2200);
  }

  // ══════════════════════════════════════════
  // BRAND TICKER
  // ══════════════════════════════════════════
  var brandsRow1 = [
    {t:'BURBERRY',c:'b-burberry'},{t:'La Romana <small>DAL 1947</small>',c:'b-laromana'},{t:'CRAZY PIZZA',c:'b-crazypizza'},{t:'IMPERIAL',c:'b-imperial'},{t:'PATAGONIA',c:'b-patagonia'},{t:'REAL MADRID',c:'b-realmadrid'},{t:'MANCHESTER UNITED',c:'b-manutd'},{t:'AS ROMA',c:'b-roma'},{t:'NOVOTEL',c:'b-novotel'},{t:'JUVENTUS',c:'b-juventus'},{t:'FERRARI',c:'b-ferrari'},{t:'BMW',c:'b-bmw'},
    {t:'SAMSUNG',c:'b-samsung'},{t:'RED BULL',c:'b-redbull'},{t:'MERCEDES-BENZ',c:'b-mercedes'},
    {t:'VOGUE SPOSA',c:'b-vogue'},{t:'KAPPA',c:'b-kappa'},{t:'SKY',c:'b-sky'},
    {t:'MASERATI',c:'b-maserati'},{t:'FIAT',c:'b-fiat'},{t:'VODAFONE',c:'b-vodafone'},
    {t:'AUDI',c:'b-audi'},{t:'JEEP',c:'b-jeep'},{t:'MICHELIN',c:'b-michelin'},
    {t:'KINDER',c:'b-kinder'},{t:"L'ORÉAL",c:'b-loreal'},{t:'GQ ITALIA',c:'b-gq'},
    {t:'ALFA ROMEO',c:'b-alfaromeo'},{t:'EATALY',c:'b-eataly'},{t:'K-WAY',c:'b-kway'},
    {t:'FORMULA 1',c:'b-formula1'},{t:'MOTOGP',c:'b-motogp'},{t:'LUXOTTICA',c:'b-luxottica'},
    {t:'SANREMO',c:'b-sanremo'},{t:'MISS UNIVERSE',c:'b-missuniverse'},
    {t:'EDISON',c:'b-edison'},{t:'QC TERME',c:'b-qcterme'},{t:'POLICE',c:'b-police'}
  ];

  var brandsRow2 = [
    {t:'CISALFA',c:'b-cisalfa'},{t:'JOMA',c:'b-joma'},{t:'ARENA',c:'b-arena'},{t:'FC BARCELONA',c:'b-barcelona'},{t:'VALENCIA CF',c:'b-valencia'},{t:'SSC NAPOLI',c:'b-napoli'},{t:'BORUSSIA DORTMUND',c:'b-dortmund'},{t:'COOLTRA',c:'b-cooltra'},{t:'GRITTI',c:'b-gritti'},{t:'FC INTERNAZIONALE',c:'b-inter'},{t:'WOLT',c:'b-wolt'},{t:'AXA',c:'b-axa'},
    {t:'SERIE A',c:'b-seriea'},{t:'LOACKER',c:'b-loacker'},{t:'MEDIASET',c:'b-mediaset'},
    {t:'PARIS FASHION WEEK',c:'b-pfw'},{t:'SALONE DEL MOBILE',c:'b-salone'},
    {t:'LA RINASCENTE',c:'b-rinascente'},{t:'EXPO 2015',c:'b-expo'},
    {t:'FIERA MILANO',c:'b-fieramilano'},{t:'RIMINI FIERA',c:'b-rimini'},
    {t:'BOLOGNA FIERE',c:'b-bologna'},{t:'TEATRO REGIO',c:'b-teatro'},
    {t:'VIRGIN ACTIVE',c:'b-virgin'},{t:'WRANGLER',c:'b-wrangler'},
    {t:'FIORUCCI',c:'b-fiorucci'},{t:'TORINO FC',c:'b-torino'},{t:'ALGIDA',c:'b-algida'},
    {t:'MIZUNO',c:'b-mizuno'},{t:'KINGS LEAGUE',c:'b-kingsleague'},
    {t:'AIA',c:'b-aia'},{t:'REVLON',c:'b-revlon'},{t:'COIN',c:'b-coin'}
  ];

  function buildTicker(brands, elId) {
    var el = document.getElementById(elId);
    if (!el) return;
    var doubled = brands.concat(brands);
    el.innerHTML = doubled.map(function(b) {
      return '<span class="' + b.c + '">' + b.t + '</span>';
    }).join('');
  }

  buildTicker(brandsRow1, 'tickerRow1');
  buildTicker(brandsRow2, 'tickerRow2');

  // ══════════════════════════════════════════
  // NAV SCROLL EFFECT
  // ══════════════════════════════════════════
  var nav = document.getElementById('mainNav');
  var sticky = document.getElementById('stickyCta');
  var lastY = 0;

  window.addEventListener('scroll', function() {
    var y = window.scrollY;
    if (nav) nav.classList.toggle('scrolled', y > 80);
    if (sticky) sticky.classList.toggle('visible', y > 600);
    lastY = y;
  });

  // ══════════════════════════════════════════
  // MOBILE MENU
  // ══════════════════════════════════════════
  // Event delegation for hamburger — works regardless of DOM timing
    document.addEventListener('click', function(e) {
        if (e.target.closest('#navHamburger')) {
            var ham = document.getElementById('navHamburger');
            var menu = document.getElementById('mobileMenu');
            if (ham && menu) {
                ham.classList.toggle('active');
                menu.classList.toggle('active');
                document.body.style.overflow = menu.classList.contains('active') ? 'hidden' : '';
            }
        }
        if (e.target.closest('.mobile-menu-inner a, .mobile-menu-cta a')) {
            var h = document.getElementById('navHamburger');
            var m = document.getElementById('mobileMenu');
            if (h && m) {
                h.classList.remove('active');
                m.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });

});
