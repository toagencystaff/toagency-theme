<?php
/**
 * Template Name: Hostess & Steward
 * HUB servizi staff eventi (evoluzione 14/07/2026): mantiene URL /hostess-steward/ e
 * focus SEO "hostess", ma vende tutti i servizi staff eventi con sezioni ad ancora.
 * Riusa i componenti del tema (coerenza stile). Form dedicato -> /tnx/ (tracking invariato).
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Hostess, steward e staff completo per fiere, eventi e congressi in tutta Italia.<br>Un unico partner per tutto l\'evento &mdash; preventivo gratuito, ti ricontattiamo subito.',
        'en' => 'Hostesses, stewards and complete staff for trade fairs, events and conferences across Italy.<br>One partner for the whole event &mdash; free quote, we get back to you right away.',
        'fr' => 'H&ocirc;tesses, stewards et personnel complet pour salons, &eacute;v&eacute;nements et congr&egrave;s partout en Italie.<br>Un seul partenaire pour tout l\'&eacute;v&eacute;nement &mdash; devis gratuit, r&eacute;ponse imm&eacute;diate.',
        'es' => 'Azafatas, stewards y personal completo para ferias, eventos y congresos en toda Italia.<br>Un &uacute;nico partner para todo el evento &mdash; presupuesto gratuito, te contactamos enseguida.',
    ),
    'hero_cta'   => array('it'=>'Richiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demandez un devis gratuit','es'=>'Solicita un presupuesto gratuito'),
    'trust_line' => array(
        'it'=>'4,7&#9733; su Google &middot; 346 recensioni &middot; dal 2009 &middot; 20.000+ profili verificati',
        'en'=>'4.7&#9733; on Google &middot; 346 reviews &middot; since 2009 &middot; 20,000+ verified profiles',
        'fr'=>'4,7&#9733; sur Google &middot; 346 avis &middot; depuis 2009 &middot; 20 000+ profils v&eacute;rifi&eacute;s',
        'es'=>'4,7&#9733; en Google &middot; 346 rese&ntilde;as &middot; desde 2009 &middot; 20.000+ perfiles verificados',
    ),
    'idx_eyebrow' => array('it'=>'Tutto lo staff per il tuo evento','en'=>'All the staff for your event','fr'=>'Tout le personnel pour votre &eacute;v&eacute;nement','es'=>'Todo el personal para tu evento'),
    'idx_heading' => array('it'=>'Cosa possiamo gestire per te','en'=>'What we can handle for you','fr'=>'Ce que nous pouvons g&eacute;rer pour vous','es'=>'Qu&eacute; podemos gestionar por ti'),
    'cta_service' => array('it'=>'Richiedi preventivo','en'=>'Request a quote','fr'=>'Demander un devis','es'=>'Solicitar presupuesto'),
    // Garanzie
    'feat_eyebrow' => array('it'=>'Garanzie','en'=>'Guarantees','fr'=>'Garanties','es'=>'Garant&iacute;as'),
    'feat_heading' => array('it'=>'Perch&eacute; le aziende scelgono TOAgency','en'=>'Why companies choose TOAgency','fr'=>'Pourquoi les entreprises choisissent TOAgency','es'=>'Por qu&eacute; las empresas eligen TOAgency'),
    'feat1_title'=>array('it'=>'Preventivo veloce','en'=>'Fast quote','fr'=>'Devis rapide','es'=>'Presupuesto r&aacute;pido'),
    'feat1_text' =>array('it'=>'Ti ricontattiamo subito con un preventivo chiaro, nessun costo nascosto.','en'=>'We get back to you right away with a clear quote, no hidden costs.','fr'=>'Nous vous recontactons tout de suite avec un devis clair, aucun co&ucirc;t cach&eacute;.','es'=>'Te contactamos enseguida con un presupuesto claro, sin costes ocultos.'),
    'feat2_title'=>array('it'=>'Operatore dedicato','en'=>'Dedicated manager','fr'=>'R&eacute;f&eacute;rent d&eacute;di&eacute;','es'=>'Gestor dedicado'),
    'feat2_text' =>array('it'=>'Un solo referente coordina tutto lo staff dall\'inizio alla fine dell\'evento.','en'=>'A single contact coordinates all the staff from start to finish of the event.','fr'=>'Un seul r&eacute;f&eacute;rent coordonne tout le personnel du d&eacute;but &agrave; la fin.','es'=>'Un &uacute;nico referente coordina todo el personal de principio a fin.'),
    'feat3_title'=>array('it'=>'Garanzia 100%','en'=>'100% guarantee','fr'=>'Garantie 100%','es'=>'Garant&iacute;a 100%'),
    'feat3_text' =>array('it'=>'Sostituzione immediata se serve. Nessun rischio per il tuo evento.','en'=>'Immediate replacement if needed. No risk for your event.','fr'=>'Remplacement imm&eacute;diat si n&eacute;cessaire. Aucun risque.','es'=>'Sustituci&oacute;n inmediata si es necesario. Ning&uacute;n riesgo.'),
    'feat4_title'=>array('it'=>'Un solo partner','en'=>'One single partner','fr'=>'Un seul partenaire','es'=>'Un &uacute;nico partner'),
    'feat4_text' =>array('it'=>'Hostess, bar, sicurezza, foto e logistica: tutto lo staff da un\'unica agenzia.','en'=>'Hostesses, bar, security, photo and logistics: all the staff from one agency.','fr'=>'H&ocirc;tesses, bar, s&eacute;curit&eacute;, photo et logistique : tout d\'une seule agence.','es'=>'Azafatas, bar, seguridad, foto y log&iacute;stica: todo el personal de una sola agencia.'),
    // How it works
    'how_step1'=>array('it'=>'CI SCRIVI','en'=>'CONTACT US','fr'=>'CONTACTEZ-NOUS','es'=>'CONT&Aacute;CTANOS'),
    'how_step2'=>array('it'=>'TI RISPONDIAMO','en'=>'WE REPLY','fr'=>'NOUS R&Eacute;PONDONS','es'=>'TE RESPONDEMOS'),
    'how_step3'=>array('it'=>'PREVENTIVO SU MISURA','en'=>'TAILORED QUOTE','fr'=>'DEVIS SUR MESURE','es'=>'PRESUPUESTO A MEDIDA'),
    'how_step4'=>array('it'=>'GESTIONE COMPLETA','en'=>'FULL MANAGEMENT','fr'=>'GESTION COMPL&Egrave;TE','es'=>'GESTI&Oacute;N COMPLETA'),
    'how_tagline'=>array('it'=>'Preventivo gratuito &bull; Risposta rapida &bull; Garanzia 100%','en'=>'Free quote &bull; Fast response &bull; 100% guarantee','fr'=>'Devis gratuit &bull; R&eacute;ponse rapide &bull; Garantie 100%','es'=>'Presupuesto gratuito &bull; Respuesta r&aacute;pida &bull; Garant&iacute;a 100%'),
    // Coverage
    'cov_eyebrow'=>array('it'=>'Dove operiamo','en'=>'Where we operate','fr'=>'O&ugrave; nous intervenons','es'=>'D&oacute;nde operamos'),
    'cov_heading'=>array('it'=>'Le principali fiere italiane','en'=>'Major Italian trade fairs','fr'=>'Les principaux salons italiens','es'=>'Las principales ferias italianas'),
    // Anti-candidatura (riuso testi landing-ads)
    'b2bonly'   => array('it'=>'Servizio per aziende, agenzie e produzioni — non per candidature.','en'=>'A service for companies, agencies and productions — not for job applications.','fr'=>'Un service pour entreprises, agences et productions — pas pour les candidatures.','es'=>'Un servicio para empresas, agencias y producciones — no para candidaturas.'),
    'talentexit'=> array('it'=>'Sei un talent in cerca di lavoro? Registrati qui','en'=>'Are you a talent looking for work? Register here','fr'=>'Vous &ecirc;tes un talent &agrave; la recherche de travail ? Inscrivez-vous ici','es'=>'&iquest;Eres un talento que busca trabajo? Reg&iacute;strate aqu&iacute;'),
);

// ── Servizi (gruppi con ancore). Ogni card: id ancora, titolo, testo. ──
$groups = array(
  array(
    'eyebrow'=>array('it'=>'Accoglienza & front-of-house','en'=>'Reception & front-of-house','fr'=>'Accueil & front-of-house','es'=>'Recepci&oacute;n & front-of-house'),
    'items'=>array(
      array('id'=>'hostess','title'=>array('it'=>'Hostess & Promoter','en'=>'Hostesses & Promoters','fr'=>'H&ocirc;tesses & Promotrices','es'=>'Azafatas & Promotoras'),
        'text'=>array('it'=>'Hostess e promoter per accoglienza, reception, distribuzione materiali e promozione a fiere, congressi e attivazioni di brand. Multilingua, immagine curata, esperienza fieristica.','en'=>'Hostesses and promoters for reception, welcome desks, material distribution and brand activation at fairs and congresses. Multilingual, polished image, trade-fair experience.','fr'=>'H&ocirc;tesses et promotrices pour accueil, r&eacute;ception et animation de marque sur salons et congr&egrave;s. Multilingues, image soign&eacute;e, exp&eacute;rience salon.','es'=>'Azafatas y promotoras para recepci&oacute;n, acogida y activaci&oacute;n de marca en ferias y congresos. Multiling&uuml;es, imagen cuidada, experiencia ferial.')),
      array('id'=>'steward','title'=>array('it'=>'Steward & Accoglienza','en'=>'Stewards & Welcome staff','fr'=>'Stewards & Accueil','es'=>'Stewards & Acogida'),
        'text'=>array('it'=>'Steward per gestione accessi, guardaroba, orientamento visitatori e supporto in sala, per eventi di ogni dimensione.','en'=>'Stewards for access management, cloakroom, visitor guidance and floor support, for events of any size.','fr'=>'Stewards pour gestion des acc&egrave;s, vestiaire, orientation des visiteurs et support en salle, pour tout type d\'&eacute;v&eacute;nement.','es'=>'Stewards para gesti&oacute;n de accesos, guardarrop&iacute;a, orientaci&oacute;n de visitantes y apoyo en sala, para eventos de cualquier tama&ntilde;o.')),
      array('id'=>'interpreti','title'=>array('it'=>'Interpreti','en'=>'Interpreters','fr'=>'Interpr&egrave;tes','es'=>'Int&eacute;rpretes'),
        'text'=>array('it'=>'Interpreti e hostess multilingua per delegazioni estere, meeting internazionali e stand fieristici. Inglese, francese, spagnolo, tedesco e altre lingue su richiesta.','en'=>'Interpreters and multilingual hostesses for foreign delegations, international meetings and fair stands. English, French, Spanish, German and other languages on request.','fr'=>'Interpr&egrave;tes et h&ocirc;tesses multilingues pour d&eacute;l&eacute;gations &eacute;trang&egrave;res, r&eacute;unions internationales et stands. Anglais, fran&ccedil;ais, espagnol, allemand et autres langues sur demande.','es'=>'Int&eacute;rpretes y azafatas multiling&uuml;es para delegaciones extranjeras, reuniones internacionales y stands. Ingl&eacute;s, franc&eacute;s, espa&ntilde;ol, alem&aacute;n y otros idiomas bajo petici&oacute;n.')),
    ),
  ),
  array(
    'eyebrow'=>array('it'=>'Food & beverage','en'=>'Food & beverage','fr'=>'Food & beverage','es'=>'Food & beverage'),
    'items'=>array(
      array('id'=>'bartender','title'=>array('it'=>'Bartender','en'=>'Bartenders','fr'=>'Barmans','es'=>'Bartenders'),
        'text'=>array('it'=>'Bartender e barman professionisti per open bar, inaugurazioni, eventi aziendali e feste private. Servizio curato e veloce, immagine impeccabile.','en'=>'Professional bartenders for open bars, openings, corporate events and private parties. Fast, polished service and impeccable image.','fr'=>'Barmans professionnels pour open bars, inaugurations, &eacute;v&eacute;nements d\'entreprise et f&ecirc;tes priv&eacute;es. Service soign&eacute; et rapide, image impeccable.','es'=>'Bartenders profesionales para open bar, inauguraciones, eventos de empresa y fiestas privadas. Servicio cuidado y r&aacute;pido, imagen impecable.')),
      array('id'=>'camerieri','title'=>array('it'=>'Camerieri','en'=>'Waiters','fr'=>'Serveurs','es'=>'Camareros'),
        'text'=>array('it'=>'Camerieri di sala e banqueting per catering, cene di gala, buffet e ricevimenti. Staff formato per un servizio impeccabile.','en'=>'Floor and banqueting waiters for catering, gala dinners, buffets and receptions. Trained staff for impeccable service.','fr'=>'Serveurs de salle et banquet pour traiteur, d&icirc;ners de gala, buffets et r&eacute;ceptions. Personnel form&eacute; pour un service impeccable.','es'=>'Camareros de sala y banqueting para catering, cenas de gala, bufets y recepciones. Personal formado para un servicio impecable.')),
    ),
  ),
  array(
    'eyebrow'=>array('it'=>'Sicurezza & logistica','en'=>'Security & logistics','fr'=>'S&eacute;curit&eacute; & logistique','es'=>'Seguridad & log&iacute;stica'),
    'items'=>array(
      array('id'=>'sicurezza','title'=>array('it'=>'Steward di sicurezza','en'=>'Security stewards','fr'=>'Stewards s&eacute;curit&eacute;','es'=>'Stewards de seguridad'),
        'text'=>array('it'=>'Steward per controllo accessi, gestione flussi e filtri d\'ingresso. Presidio ordinato e professionale per eventi e fiere.','en'=>'Stewards for access control, crowd-flow management and entry checks. Orderly, professional presence for events and fairs.','fr'=>'Stewards pour contr&ocirc;le des acc&egrave;s, gestion des flux et filtrage des entr&eacute;es. Pr&eacute;sence ordonn&eacute;e et professionnelle.','es'=>'Stewards para control de accesos, gesti&oacute;n de flujos y filtros de entrada. Presencia ordenada y profesional.')),
      array('id'=>'autisti','title'=>array('it'=>'Autisti & Logistica','en'=>'Drivers & Logistics','fr'=>'Chauffeurs & Logistique','es'=>'Conductores & Log&iacute;stica'),
        'text'=>array('it'=>'Autisti e supporto logistico per trasferimenti di ospiti, delegazioni e materiali durante l\'evento.','en'=>'Drivers and logistics support for transfers of guests, delegations and materials during the event.','fr'=>'Chauffeurs et support logistique pour les transferts d\'invit&eacute;s, d&eacute;l&eacute;gations et mat&eacute;riel pendant l\'&eacute;v&eacute;nement.','es'=>'Conductores y apoyo log&iacute;stico para traslados de invitados, delegaciones y materiales durante el evento.')),
      array('id'=>'runner','title'=>array('it'=>'Runner','en'=>'Runners','fr'=>'Runners','es'=>'Runners'),
        'text'=>array('it'=>'Runner e supporto operativo per allestimenti, backstage e gestione delle esigenze last-minute in evento.','en'=>'Runners and operational support for setup, backstage and last-minute needs during the event.','fr'=>'Runners et support op&eacute;rationnel pour montage, backstage et besoins de derni&egrave;re minute.','es'=>'Runners y apoyo operativo para montaje, backstage y necesidades de &uacute;ltimo momento.')),
    ),
  ),
  array(
    'eyebrow'=>array('it'=>'Foto & video','en'=>'Photo & video','fr'=>'Photo & vid&eacute;o','es'=>'Foto & v&iacute;deo'),
    'items'=>array(
      array('id'=>'fotografi','title'=>array('it'=>'Fotografi','en'=>'Photographers','fr'=>'Photographes','es'=>'Fot&oacute;grafos'),
        'text'=>array('it'=>'Fotografi di evento per reportage, ritratti e coverage di stand, congressi e cerimonie.','en'=>'Event photographers for reportage, portraits and coverage of stands, congresses and ceremonies.','fr'=>'Photographes d\'&eacute;v&eacute;nement pour reportage, portraits et couverture de stands, congr&egrave;s et c&eacute;r&eacute;monies.','es'=>'Fot&oacute;grafos de evento para reportaje, retratos y cobertura de stands, congresos y ceremonias.')),
      array('id'=>'videomaker','title'=>array('it'=>'Videomaker','en'=>'Videomakers','fr'=>'Vid&eacute;astes','es'=>'Videomakers'),
        'text'=>array('it'=>'Videomaker per riprese, aftermovie e contenuti social del tuo evento.','en'=>'Videomakers for filming, aftermovies and social content of your event.','fr'=>'Vid&eacute;astes pour tournage, aftermovie et contenus sociaux de votre &eacute;v&eacute;nement.','es'=>'Videomakers para grabaci&oacute;n, aftermovie y contenido social de tu evento.')),
    ),
  ),
  array(
    'eyebrow'=>array('it'=>'Accompagnamento','en'=>'Group accompaniment','fr'=>'Accompagnement','es'=>'Acompa&ntilde;amiento'),
    'items'=>array(
      array('id'=>'tour-leader','title'=>array('it'=>'Tour Leader','en'=>'Tour Leaders','fr'=>'Tour Leaders','es'=>'Tour Leaders'),
        'text'=>array('it'=>'Tour leader e accompagnatori per gruppi, incentive e visite aziendali. Gestione logistica e assistenza al gruppo.','en'=>'Tour leaders and group escorts for groups, incentives and corporate visits. Logistics management and group assistance.','fr'=>'Tour leaders et accompagnateurs pour groupes, incentives et visites d\'entreprise. Gestion logistique et assistance au groupe.','es'=>'Tour leaders y acompa&ntilde;antes para grupos, incentivos y visitas de empresa. Gesti&oacute;n log&iacute;stica y asistencia al grupo.')),
    ),
  ),
);

toa_component('header');

$images = array(
    array('src' => '/wp-content/uploads/2025/09/hostess-38-yo-caucasian.jpg', 'alt' => 'Hostess professionale per fiere ed eventi aziendali — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-35-yo-caucasian.jpg', 'alt' => 'Steward per eventi e congressi aziendali — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-23-yo-latina.jpg', 'alt' => 'Hostess per eventi sport e promozioni — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-29-yo-african.jpg', 'alt' => 'Hostess per congressi e fiere internazionali — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-26-yo-east-asian-1.jpg', 'alt' => 'Steward per eventi e accoglienza — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-32-yo-caucasian.jpg', 'alt' => 'Hostess per fiere e attivazioni di brand — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-50-yo-african.jpg', 'alt' => 'Steward senior per eventi e congressi — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/sport-hostess-25-yo-caucasian.jpg', 'alt' => 'Hostess per eventi sport e promozioni — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-31-yo-ispanic.jpg', 'alt' => 'Steward per eventi e accoglienza — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-26-yo-caucasian.jpg', 'alt' => 'Hostess per eventi aziendali e fiere — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/steward-40-yo-caucasian.jpg', 'alt' => 'Steward per congressi ed eventi corporate — TOAgency'),
    array('src' => '/wp-content/uploads/2025/09/hostess-29-yo-east-asian.jpg', 'alt' => 'Hostess per fiere ed eventi — TOAgency'),
);
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'STAFF PER EVENTI',
    'title'      => _t_raw(array('it'=>'Hostess & staff per eventi.','en'=>'Hostess & event staff.','fr'=>'Hôtesses & staff événementiel.','es'=>'Azafatas & staff para eventos.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Hero CTA + trust line -->
<div class="container" style="margin-top:-20px;margin-bottom:24px">
    <a href="#preventivo" class="btn-hero btn-hero-primary" style="display:inline-flex;align-items:center;gap:8px">
        <span><?php echo $_t($t['hero_cta']); ?></span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.85rem;color:var(--gray-4);margin-top:12px;font-weight:600"><?php echo $_t($t['trust_line']); ?></p>
</div>

<!-- Banner clienti -->
<?php toa_component('brand-ticker', array('lang' => $lang)); ?>

<!-- Indice servizi (ancore) -->
<section class="why-section" style="padding-bottom:0">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['idx_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:24px"><?php echo $_t($t['idx_heading']); ?></h2>
        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:8px">
            <?php foreach ($groups as $g): foreach ($g['items'] as $it): ?>
            <a href="#<?php echo esc_attr($it['id']); ?>" style="display:inline-block;padding:8px 14px;border:2px solid var(--black);font-size:0.82rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--black);text-decoration:none"><?php echo $_t($it['title']); ?></a>
            <?php endforeach; endforeach; ?>
        </div>
    </div>
</section>

<?php toa_component('gallery-talent', array('images' => $images, 'columns' => 4)); ?>

<!-- Sezioni servizi -->
<?php foreach ($groups as $g): ?>
<section class="why-section" style="padding-top:48px">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($g['eyebrow']); ?></div>
    </div>
    <div class="features-grid">
        <?php foreach ($g['items'] as $it): ?>
        <div class="feature-card" id="<?php echo esc_attr($it['id']); ?>">
            <h3 class="feature-title"><?php echo $_t($it['title']); ?></h3>
            <p class="feature-text"><?php echo $_t($it['text']); ?></p>
            <a href="#preventivo" style="display:inline-block;margin-top:14px;font-size:0.8rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--accent);text-decoration:none"><?php echo $_t($t['cta_service']); ?> &rarr;</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endforeach; ?>

<!-- Perché TOAgency -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['feat_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['feat_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card"><div class="feature-number">01</div><h3 class="feature-title"><?php echo $_t($t['feat1_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat1_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">02</div><h3 class="feature-title"><?php echo $_t($t['feat2_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat2_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">03</div><h3 class="feature-title"><?php echo $_t($t['feat3_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat3_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">04</div><h3 class="feature-title"><?php echo $_t($t['feat4_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat4_text']); ?></p></div>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "1'", 'label' => $_t($t['how_step1'])),
        array('time' => 'SUBITO', 'label' => $_t($t['how_step2'])),
        array('time' => "&#10003;", 'label' => $_t($t['how_step3'])),
        array('time' => 'H24', 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<!-- Recensioni Google -->
<?php toa_component('google-reviews'); ?>

<!-- Dove operiamo -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['cov_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:40px"><?php echo $_t($t['cov_heading']); ?></h2>
    </div>
    <div class="coverage-grid container">
        <div class="coverage-country"><h4>Milano</h4><p>Fiera Milano Rho, Salone del Mobile, Fashion Week</p></div>
        <div class="coverage-country"><h4>Bologna</h4><p>Bologna Fiere, Cosmoprof, Motor Show</p></div>
        <div class="coverage-country"><h4>Verona</h4><p>Veronafiere, Vinitaly, Marmomac</p></div>
        <div class="coverage-country"><h4>Torino</h4><p>Lingotto Fiere, Salone Auto, ATP Finals</p></div>
        <div class="coverage-country"><h4>Rimini</h4><p>Rimini Fiera, Sigep, TTG Travel</p></div>
        <div class="coverage-country"><h4>Roma</h4><p>Fiera di Roma, Maker Faire, Romics</p></div>
        <div class="coverage-country"><h4>Firenze</h4><p>Fortezza da Basso, Pitti Immagine, Taste</p></div>
        <div class="coverage-country"><h4>Genova</h4><p>Fiera di Genova, Salone Nautico, Euroflora</p></div>
    </div>
</section>

<!-- Form preventivo (dedicato, -> /tnx/) -->
<?php toa_component('form-eventi-inline', array('lang' => $lang)); ?>

<!-- Blocco anti-candidatura -->
<section style="padding:40px 0;background:var(--black);color:#fff;text-align:center">
    <div class="container">
        <p style="font-size:0.95rem;margin-bottom:10px;opacity:.85"><?php echo $_t($t['b2bonly']); ?></p>
        <a href="<?php echo home_url('/registrati-talent/'); ?>" style="display:inline-block;font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;color:var(--accent);text-decoration:none"><?php echo $_t($t['talentexit']); ?> &rarr;</a>
    </div>
</section>

<?php toa_component('footer'); ?>
