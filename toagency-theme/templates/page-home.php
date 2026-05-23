<?php
/**
 * Template Name: Homepage
 * Page template for the TOAgency homepage
 * v6 2026-04-15 — talent button workaround (div instead of a)
 */
toa_component('header');
$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, array('it','en','fr','es'))) $__l = 'it';

// ─── Translations ───
function _ht($strings) {
    global $__l;
    return esc_html(isset($strings[$__l]) ? $strings[$__l] : $strings['it']);
}
?>
<!-- TOA-HOME-V6 -->

<!-- ═══════════ HERO ═══════════ -->
<section class="hero">
  <div class="hero-video">
    <video autoplay loop muted playsinline>
      <source src="/wp-content/uploads/2025/09/homepagenew.mov" type="video/quicktime">
      <source src="/wp-content/uploads/2025/09/homepagenew.mov" type="video/mp4">
    </video>
  </div>
  <div class="hero-content">
    <div class="hero-eyebrow"><?php echo _ht(array('it'=>'Agenzia casting B2B — Talent per aziende dal 2009','en'=>'B2B casting agency — Talent for companies since 2009','fr'=>'Agence de casting B2B — Talents pour entreprises depuis 2009','es'=>'Agencia de casting B2B — Talento para empresas desde 2009')); ?></div>
    <h1 class="hero-title">
      <?php echo _ht(array('it'=>'Seleziona','en'=>'Select','fr'=>'Sélectionnez','es'=>'Selecciona')); ?><br><span class="rotating-word" id="rotatingWord"><?php echo _ht(array('it'=>'Modelli','en'=>'Models','fr'=>'Modèles','es'=>'Modelos')); ?></span>
    </h1>
    <p class="hero-subtitle">
      <?php echo _ht(array('it'=>'Preventivo gratuito in 30 minuti. Un team dedicato gestisce selezione, contratti e logistica — tu pensi al progetto.','en'=>'Free quote in 30 minutes. A dedicated team handles selection, contracts and logistics — you focus on the project.','fr'=>'Devis gratuit en 30 minutes. Une équipe dédiée gère sélection, contrats et logistique — vous pensez au projet.','es'=>'Presupuesto gratuito en 30 minutos. Un equipo dedicado gestiona selección, contratos y logística — tú piensas en el proyecto.')); ?>
    </p>
    <div class="hero-actions">
      <a href="<?php echo home_url('/form-b2b/'); ?>" class="btn-hero btn-hero-primary"><span><?php echo _ht(array('it'=>'Sono un\'azienda — cerco talent','en'=>'I\'m a company — looking for talent','fr'=>'Je suis une entreprise — je cherche des talents','es'=>'Soy una empresa — busco talento')); ?></span></a>
      <div class="btn-hero btn-hero-secondary" onclick="window.location.href='/b-t-l/'" style="cursor:pointer" role="link" tabindex="0" id="toa-talent-btn"><span><?php echo _ht(array('it'=>'Sono un talent — cerco lavori','en'=>'I\'m a talent — looking for jobs','fr'=>'Je suis un talent — je cherche du travail','es'=>'Soy un talento — busco trabajo')); ?></span></div>
      <a href="<?php echo home_url('/student-program/'); ?>" class="btn-hero btn-hero-secondary"><span><?php echo _ht(array('it'=>'Sono uno studente — Student Program','en'=>'I\'m a student — Student Program','fr'=>'Je suis étudiant — Student Program','es'=>'Soy estudiante — Student Program')); ?></span></a>
    </div>
  </div>
</section>

<!-- ═══════════ BRAND TICKER ═══════════ -->
<?php toa_component('brand-ticker', array('lang' => $__l)); ?>

<!-- ═══════════ STATS ═══════════ -->
<section class="stats-bar">
  <div class="stats-grid">
    <div class="stat-item">
      <div class="stat-number">20<span class="accent">K</span>+</div>
      <div class="stat-label"><?php echo _ht(array('it'=>'Professionisti','en'=>'Professionals','fr'=>'Professionnels','es'=>'Profesionales')); ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-number">10<span class="accent">K</span>+</div>
      <div class="stat-label"><?php echo _ht(array('it'=>'Progetti','en'=>'Projects','fr'=>'Projets','es'=>'Proyectos')); ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-number">50<span class="accent">+</span></div>
      <div class="stat-label"><?php echo _ht(array('it'=>'Città operative','en'=>'Active cities','fr'=>'Villes actives','es'=>'Ciudades activas')); ?></div>
    </div>
    <div class="stat-item">
      <div class="stat-number">15<span class="accent">+</span></div>
      <div class="stat-label"><?php echo _ht(array('it'=>'Anni di esperienza','en'=>'Years of experience','fr'=>'Ans d\'expérience','es'=>'Años de experiencia')); ?></div>
    </div>
  </div>
</section>

<!-- ═══════════ WHY SECTION ═══════════ -->
<section class="why-section">
  <div class="container">
    <div class="section-eyebrow"><?php echo _ht(array('it'=>'Perché sceglierci','en'=>'Why choose us','fr'=>'Pourquoi nous choisir','es'=>'Por qué elegirnos')); ?></div>
    <h2 class="section-heading"><?php echo _ht(array('it'=>'Non siamo una piattaforma.','en'=>'We\'re not a platform.','fr'=>'Nous ne sommes pas une plateforme.','es'=>'No somos una plataforma.')); ?><br><?php echo _ht(array('it'=>'Siamo il tuo team.','en'=>'We\'re your team.','fr'=>'Nous sommes votre équipe.','es'=>'Somos tu equipo.')); ?></h2>
  </div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-number">01</div>
      <h3 class="feature-title"><?php echo _ht(array('it'=>'Team umano dedicato','en'=>'Dedicated human team','fr'=>'Équipe humaine dédiée','es'=>'Equipo humano dedicado')); ?></h3>
      <p class="feature-text"><?php echo _ht(array('it'=>'Un casting director assegnato al tuo progetto. Gestiamo brief, selezione, contratti e pagamenti. Niente algoritmi — persone che capiscono cosa serve.','en'=>'A casting director assigned to your project. We manage briefs, selection, contracts and payments. No algorithms — people who understand what you need.','fr'=>'Un directeur de casting assigné à votre projet. Nous gérons briefs, sélection, contrats et paiements. Pas d\'algorithmes — des personnes qui comprennent vos besoins.','es'=>'Un director de casting asignado a tu proyecto. Gestionamos briefs, selección, contratos y pagos. Sin algoritmos — personas que entienden lo que necesitas.')); ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-number">02</div>
      <h3 class="feature-title"><?php echo _ht(array('it'=>'Copertura internazionale','en'=>'International coverage','fr'=>'Couverture internationale','es'=>'Cobertura internacional')); ?></h3>
      <p class="feature-text"><?php echo _ht(array('it'=>'Operativi in Italia, Francia, Spagna e UK. Dalle fiere di Milano e Rimini agli shooting a Parigi e Londra. Un unico referente, ovunque.','en'=>'Operating in Italy, France, Spain and UK. From trade fairs in Milan and Rimini to shoots in Paris and London. One point of contact, everywhere.','fr'=>'Présents en Italie, France, Espagne et UK. Des salons de Milan et Rimini aux shootings à Paris et Londres. Un seul référent, partout.','es'=>'Operativos en Italia, Francia, España y UK. Desde ferias en Milán y Rímini hasta shootings en París y Londres. Un único referente, en todas partes.')); ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-number">03</div>
      <h3 class="feature-title"><?php echo _ht(array('it'=>'Database verificato','en'=>'Verified database','fr'=>'Base de données vérifiée','es'=>'Base de datos verificada')); ?></h3>
      <p class="feature-text"><?php echo _ht(array('it'=>'20.000+ profili aggiornati ogni giorno. Polaroid e self-tape in 24 ore. Disponibilità in tempo reale, anche per casting urgenti.','en'=>'20,000+ profiles updated daily. Polaroids and self-tapes in 24 hours. Real-time availability, even for urgent castings.','fr'=>'Plus de 20 000 profils mis à jour chaque jour. Polaroids et self-tapes en 24 heures. Disponibilité en temps réel, même pour les castings urgents.','es'=>'Más de 20.000 perfiles actualizados a diario. Polaroids y self-tapes en 24 horas. Disponibilidad en tiempo real, incluso para castings urgentes.')); ?></p>
    </div>
    <div class="feature-card">
      <div class="feature-number">04</div>
      <h3 class="feature-title"><?php echo _ht(array('it'=>'Risposta in 30 minuti','en'=>'Response in 30 minutes','fr'=>'Réponse en 30 minutes','es'=>'Respuesta en 30 minutos')); ?></h3>
      <p class="feature-text"><?php echo _ht(array('it'=>'Compili il form e in mezz\'ora hai le prime proposte con preventivo trasparente. Contratti digitali. Zero sorprese.','en'=>'Fill in the form and in half an hour you get the first proposals with a transparent quote. Digital contracts. Zero surprises.','fr'=>'Remplissez le formulaire et en une demi-heure vous recevez les premières propositions avec un devis transparent. Contrats numériques. Zéro surprises.','es'=>'Rellena el formulario y en media hora recibes las primeras propuestas con presupuesto transparente. Contratos digitales. Cero sorpresas.')); ?></p>
    </div>
  </div>
</section>

<!-- ═══════════ SERVICES ═══════════ -->
<section class="services-section">
  <div class="container">
    <div class="section-eyebrow"><?php echo _ht(array('it'=>'Cosa facciamo','en'=>'What we do','fr'=>'Ce que nous faisons','es'=>'Lo que hacemos')); ?></div>
    <h2 class="section-heading"><?php echo _ht(array('it'=>'Ogni progetto ha il talento giusto','en'=>'Every project has the right talent','fr'=>'Chaque projet a le bon talent','es'=>'Cada proyecto tiene el talento adecuado')); ?></h2>
  </div>
  <div class="services-grid">
    <a href="<?php echo home_url('/models/'); ?>" class="service-card">
      <div class="service-icon">◐</div>
      <div class="service-name"><?php echo _ht(array('it'=>'Modelli','en'=>'Models','fr'=>'Mannequins','es'=>'Modelos')); ?></div>
      <div class="service-desc"><?php echo _ht(array('it'=>'Fashion, e-commerce, cataloghi, campagne','en'=>'Fashion, e-commerce, catalogs, campaigns','fr'=>'Mode, e-commerce, catalogues, campagnes','es'=>'Moda, e-commerce, catálogos, campañas')); ?></div>
    </a>
    <a href="<?php echo home_url('/hostess-steward/'); ?>" class="service-card">
      <div class="service-icon">◈</div>
      <div class="service-name"><?php echo _ht(array('it'=>'Hostess & Steward','en'=>'Hostess & Steward','fr'=>'Hôtesses & Stewards','es'=>'Azafatas & Promotores')); ?></div>
      <div class="service-desc"><?php echo _ht(array('it'=>'Fiere, eventi, congressi, attivazioni','en'=>'Trade fairs, events, conferences, activations','fr'=>'Salons, événements, congrès, activations','es'=>'Ferias, eventos, congresos, activaciones')); ?></div>
    </a>
    <a href="<?php echo home_url('/actors/'); ?>" class="service-card">
      <div class="service-icon">◉</div>
      <div class="service-name"><?php echo _ht(array('it'=>'Attori & Comparse','en'=>'Actors & Extras','fr'=>'Acteurs & Figurants','es'=>'Actores & Extras')); ?></div>
      <div class="service-desc"><?php echo _ht(array('it'=>'Film, serie TV, spot pubblicitari','en'=>'Films, TV series, commercials','fr'=>'Films, séries TV, spots publicitaires','es'=>'Películas, series de TV, spots publicitarios')); ?></div>
    </a>
    <a href="<?php echo home_url('/visuals/'); ?>" class="service-card">
      <div class="service-icon">◫</div>
      <div class="service-name"><?php echo _ht(array('it'=>'Fotografi & Videomaker','en'=>'Photographers & Videographers','fr'=>'Photographes & Vidéastes','es'=>'Fotógrafos & Videógrafos')); ?></div>
      <div class="service-desc"><?php echo _ht(array('it'=>'Shooting, produzioni video, contenuti social','en'=>'Shoots, video productions, social content','fr'=>'Shootings, productions vidéo, contenus sociaux','es'=>'Shootings, producciones de video, contenidos sociales')); ?></div>
    </a>
    <a href="<?php echo home_url('/b2bservices/'); ?>" class="service-card">
      <div class="service-icon">◧</div>
      <div class="service-name"><?php echo _ht(array('it'=>'MUA & Hair Stylist','en'=>'MUA & Hair Stylist','fr'=>'MUA & Coiffeur','es'=>'MUA & Estilista')); ?></div>
      <div class="service-desc"><?php echo _ht(array('it'=>'Trucco e acconciature per ogni produzione','en'=>'Makeup and hairstyling for every production','fr'=>'Maquillage et coiffure pour chaque production','es'=>'Maquillaje y peinado para cada producción')); ?></div>
    </a>
    <a href="<?php echo home_url('/casting/'); ?>" class="service-card">
      <div class="service-icon">◎</div>
      <div class="service-name"><?php echo _ht(array('it'=>'Casting completo','en'=>'Full casting','fr'=>'Casting complet','es'=>'Casting completo')); ?></div>
      <div class="service-desc"><?php echo _ht(array('it'=>'Selezione, logistica, gestione integrale','en'=>'Selection, logistics, full management','fr'=>'Sélection, logistique, gestion intégrale','es'=>'Selección, logística, gestión integral')); ?></div>
    </a>
  </div>
</section>

<!-- ═══════════ CTA ═══════════ -->
<section class="cta-section">
  <div class="container" style="text-align:center">
    <div class="section-eyebrow"><?php echo _ht(array('it'=>'Inizia ora','en'=>'Start now','fr'=>'Commencez maintenant','es'=>'Empieza ahora')); ?></div>
    <h2 class="section-heading"><?php echo _ht(array('it'=>'Raccontaci il tuo progetto','en'=>'Tell us about your project','fr'=>'Parlez-nous de votre projet','es'=>'Cuéntanos tu proyecto')); ?></h2>
    <p class="section-subtitle" style="max-width:600px;margin:0 auto 32px;opacity:.7"><?php echo _ht(array('it'=>'Compila il form e in 30 minuti ricevi le prime proposte con preventivo gratuito.','en'=>'Fill in the form and in 30 minutes you\'ll receive the first proposals with a free quote.','fr'=>'Remplissez le formulaire et en 30 minutes vous recevez les premières propositions avec un devis gratuit.','es'=>'Rellena el formulario y en 30 minutos recibes las primeras propuestas con presupuesto gratuito.')); ?></p>
    <div class="cta-buttons-row" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
      <a href="<?php echo home_url('/form-b2b/'); ?>" class="btn-hero btn-hero-primary"><span><?php echo _ht(array('it'=>'Richiedi preventivo gratuito','en'=>'Request a free quote','fr'=>'Demander un devis gratuit','es'=>'Solicitar presupuesto gratuito')); ?></span></a>
      <div class="btn-hero btn-hero-secondary" onclick="window.location.href='<?php echo home_url('/b-t-l/'); ?>'" style="cursor:pointer" role="link" tabindex="0"><span><?php echo _ht(array('it'=>'Sei un talent? Registrati','en'=>'Are you a talent? Sign up','fr'=>'Vous êtes un talent ? Inscrivez-vous','es'=>'¿Eres un talento? Regístrate')); ?></span></div>
    </div>
  </div>
</section>

<!-- ═══════════ COVERAGE ═══════════ -->
<section class="coverage-section">
  <div class="container">
    <div class="section-eyebrow"><?php echo _ht(array('it'=>'Dove operiamo','en'=>'Where we operate','fr'=>'Où nous opérons','es'=>'Dónde operamos')); ?></div>
    <h2 class="section-heading" style="margin-bottom:40px"><?php echo _ht(array('it'=>'Italia + Europa: i nostri hub, il tuo raggio','en'=>'Italy + Europe: our hubs, your reach','fr'=>'Italie + Europe : nos hubs, votre portée','es'=>'Italia + Europa: nuestros hubs, tu alcance')); ?></h2>
  </div>
  <div class="coverage-grid container">
    <div class="coverage-country">
      <h4><?php echo _ht(array('it'=>'Italia','en'=>'Italy','fr'=>'Italie','es'=>'Italia')); ?></h4>
      <p><?php echo _ht(array('it'=>'Milano, Roma, Torino, Genova, Venezia, Verona, Bologna, Firenze, Napoli, Rimini, Bari, Palermo, Catania','en'=>'Milan, Rome, Turin, Genoa, Venice, Verona, Bologna, Florence, Naples, Rimini, Bari, Palermo, Catania','fr'=>'Milan, Rome, Turin, Gênes, Venise, Vérone, Bologne, Florence, Naples, Rimini, Bari, Palerme, Catane','es'=>'Milán, Roma, Turín, Génova, Venecia, Verona, Bolonia, Florencia, Nápoles, Rímini, Bari, Palermo, Catania')); ?></p>
    </div>
    <div class="coverage-country">
      <h4><?php echo _ht(array('it'=>'Francia','en'=>'France','fr'=>'France','es'=>'Francia')); ?></h4>
      <p><?php echo _ht(array('it'=>'Parigi, Lione, Marsiglia','en'=>'Paris, Lyon, Marseille','fr'=>'Paris, Lyon, Marseille','es'=>'París, Lyon, Marsella')); ?></p>
    </div>
    <div class="coverage-country">
      <h4><?php echo _ht(array('it'=>'Spagna','en'=>'Spain','fr'=>'Espagne','es'=>'España')); ?></h4>
      <p><?php echo _ht(array('it'=>'Madrid, Barcellona, Valencia','en'=>'Madrid, Barcelona, Valencia','fr'=>'Madrid, Barcelone, Valence','es'=>'Madrid, Barcelona, Valencia')); ?></p>
    </div>
    <div class="coverage-country">
      <h4><?php echo _ht(array('it'=>'UK','en'=>'UK','fr'=>'Royaume-Uni','es'=>'Reino Unido')); ?></h4>
      <p><?php echo _ht(array('it'=>'Londra','en'=>'London','fr'=>'Londres','es'=>'Londres')); ?></p>
    </div>
    <div class="coverage-country">
      <h4>EUROPA</h4>
      <p><?php echo _ht(array('it'=>'Berlino, Amsterdam, Bruxelles, Vienna, Lisbona... + ovunque serva','en'=>'Berlin, Amsterdam, Brussels, Vienna, Lisbon... + wherever needed','fr'=>'Berlin, Amsterdam, Bruxelles, Vienne, Lisbonne... + partout où il faut','es'=>'Berlín, Ámsterdam, Bruselas, Viena, Lisboa... + donde haga falta')); ?></p>
    </div>
  </div>
</section>

<?php toa_component('footer'); ?>

<script>
/* Navbar scroll effect — trasparente in cima, opaca scorrendo */
(function () {
    'use strict';
    var nav = document.getElementById('mainNav');
    if (!nav) return;

    function updateNav() {
        if (window.scrollY > 60) {
            nav.style.background = 'rgba(8,8,10,0.92)';
            nav.style.boxShadow = '0 2px 40px rgba(0,0,0,0.35)';
        } else {
            nav.style.background = 'rgba(8,8,10,0)';
            nav.style.boxShadow = 'none';
        }
    }

    window.addEventListener('scroll', updateNav, { passive: true });
    updateNav();
}());
</script>

<?php if ($__l !== 'it') : ?>
<script>
(function(){
  var translations = {
    'en': {'Modelli':'Models','Hostess':'Hostesses','Attori':'Actors','Fotografi':'Photographers','Videomaker':'Videographers','Comparse':'Extras','Creator':'Creators','Steward':'Stewards','Truccatori':'MUA Artists'},
    'fr': {'Modelli':'Mannequins','Hostess':'Hôtesses','Attori':'Acteurs','Fotografi':'Photographes','Videomaker':'Vidéastes','Comparse':'Figurants','Creator':'Créateurs','Steward':'Stewards','Truccatori':'Maquilleurs'},
    'es': {'Modelli':'Modelos','Hostess':'Azafatas','Attori':'Actores','Fotografi':'Fotógrafos','Videomaker':'Videógrafos','Comparse':'Extras','Creator':'Creadores','Steward':'Promotores','Truccatori':'Maquilladores'}
  };
  var lang = '<?php echo esc_js($__l); ?>';
  var map = translations[lang];
  if (!map) return;
  var el = document.getElementById('rotatingWord');
  if (!el) return;
  if (map[el.textContent]) el.textContent = map[el.textContent];
  var obs = new MutationObserver(function() {
    var w = el.textContent;
    if (map[w]) el.textContent = map[w];
  });
  obs.observe(el, { childList: true, characterData: true, subtree: true });
})();
</script>
<?php endif; ?>
