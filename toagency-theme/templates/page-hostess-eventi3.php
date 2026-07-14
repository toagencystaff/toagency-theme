<?php
/**
 * Template Name: Hostess & Steward
 * HUB servizi staff eventi (v2 14/07/2026): versione COMPATTA.
 * - rimosso brand-ticker duplicato (è già globale nel tema)
 * - rimosso indice ancore ridondante
 * - 11 servizi in UNA sola griglia compatta (1 riga per servizio)
 * Mantiene URL /hostess-steward/ e focus SEO "hostess". Riusa i componenti del tema.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Hostess, steward e staff completo per fiere, eventi e congressi.<br>Un unico partner per tutto l\'evento &mdash; preventivo gratuito, ti ricontattiamo subito.',
        'en' => 'Hostesses, stewards and complete staff for trade fairs, events and conferences.<br>One partner for the whole event &mdash; free quote, we get back to you right away.',
        'fr' => 'H&ocirc;tesses, stewards et personnel complet pour salons, &eacute;v&eacute;nements et congr&egrave;s.<br>Un seul partenaire pour tout l\'&eacute;v&eacute;nement &mdash; devis gratuit, r&eacute;ponse imm&eacute;diate.',
        'es' => 'Azafatas, stewards y personal completo para ferias, eventos y congresos.<br>Un &uacute;nico partner para todo el evento &mdash; presupuesto gratuito, te contactamos enseguida.',
    ),
    'hero_cta'   => array('it'=>'Richiedi un preventivo gratuito','en'=>'Request a free quote','fr'=>'Demandez un devis gratuit','es'=>'Solicita un presupuesto gratuito'),
    'trust_line' => array(
        'it'=>'4,7&#9733; su Google &middot; 346 recensioni &middot; dal 2009 &middot; 20.000+ profili verificati',
        'en'=>'4.7&#9733; on Google &middot; 346 reviews &middot; since 2009 &middot; 20,000+ verified profiles',
        'fr'=>'4,7&#9733; sur Google &middot; 346 avis &middot; depuis 2009 &middot; 20 000+ profils v&eacute;rifi&eacute;s',
        'es'=>'4,7&#9733; en Google &middot; 346 rese&ntilde;as &middot; desde 2009 &middot; 20.000+ perfiles verificados',
    ),
    'serv_eyebrow' => array('it'=>'I nostri servizi','en'=>'Our services','fr'=>'Nos services','es'=>'Nuestros servicios'),
    'serv_heading' => array('it'=>'Tutto lo staff per il tuo evento','en'=>'All the staff for your event','fr'=>'Tout le personnel pour votre &eacute;v&eacute;nement','es'=>'Todo el personal para tu evento'),
    'cta_service'  => array('it'=>'Preventivo','en'=>'Get a quote','fr'=>'Devis','es'=>'Presupuesto'),
    'feat_eyebrow' => array('it'=>'Garanzie','en'=>'Guarantees','fr'=>'Garanties','es'=>'Garant&iacute;as'),
    'feat_heading' => array('it'=>'Perch&eacute; le aziende scelgono TOAgency','en'=>'Why companies choose TOAgency','fr'=>'Pourquoi les entreprises choisissent TOAgency','es'=>'Por qu&eacute; las empresas eligen TOAgency'),
    'feat1_title'=>array('it'=>'Preventivo veloce','en'=>'Fast quote','fr'=>'Devis rapide','es'=>'Presupuesto r&aacute;pido'),
    'feat1_text' =>array('it'=>'Ti ricontattiamo subito, nessun costo nascosto.','en'=>'We get back to you right away, no hidden costs.','fr'=>'R&eacute;ponse imm&eacute;diate, aucun co&ucirc;t cach&eacute;.','es'=>'Te contactamos enseguida, sin costes ocultos.'),
    'feat2_title'=>array('it'=>'Operatore dedicato','en'=>'Dedicated manager','fr'=>'R&eacute;f&eacute;rent d&eacute;di&eacute;','es'=>'Gestor dedicado'),
    'feat2_text' =>array('it'=>'Un solo referente coordina tutto lo staff.','en'=>'A single contact coordinates all the staff.','fr'=>'Un seul r&eacute;f&eacute;rent coordonne tout le personnel.','es'=>'Un &uacute;nico referente coordina todo el personal.'),
    'feat3_title'=>array('it'=>'Garanzia 100%','en'=>'100% guarantee','fr'=>'Garantie 100%','es'=>'Garant&iacute;a 100%'),
    'feat3_text' =>array('it'=>'Sostituzione immediata se serve. Zero rischi.','en'=>'Immediate replacement if needed. Zero risk.','fr'=>'Remplacement imm&eacute;diat si besoin. Z&eacute;ro risque.','es'=>'Sustituci&oacute;n inmediata si hace falta. Cero riesgo.'),
    'feat4_title'=>array('it'=>'Un solo partner','en'=>'One single partner','fr'=>'Un seul partenaire','es'=>'Un &uacute;nico partner'),
    'feat4_text' =>array('it'=>'Tutto lo staff dell\'evento da un\'unica agenzia.','en'=>'All the event staff from one agency.','fr'=>'Tout le personnel de l\'&eacute;v&eacute;nement d\'une seule agence.','es'=>'Todo el personal del evento de una sola agencia.'),
    'how_step1'=>array('it'=>'CI SCRIVI','en'=>'CONTACT US','fr'=>'CONTACTEZ-NOUS','es'=>'CONT&Aacute;CTANOS'),
    'how_step2'=>array('it'=>'TI RISPONDIAMO','en'=>'WE REPLY','fr'=>'NOUS R&Eacute;PONDONS','es'=>'TE RESPONDEMOS'),
    'how_step3'=>array('it'=>'PREVENTIVO SU MISURA','en'=>'TAILORED QUOTE','fr'=>'DEVIS SUR MESURE','es'=>'PRESUPUESTO A MEDIDA'),
    'how_step4'=>array('it'=>'GESTIONE COMPLETA','en'=>'FULL MANAGEMENT','fr'=>'GESTION COMPL&Egrave;TE','es'=>'GESTI&Oacute;N COMPLETA'),
    'how_tagline'=>array('it'=>'Preventivo gratuito &bull; Risposta rapida &bull; Garanzia 100%','en'=>'Free quote &bull; Fast response &bull; 100% guarantee','fr'=>'Devis gratuit &bull; R&eacute;ponse rapide &bull; Garantie 100%','es'=>'Presupuesto gratuito &bull; Respuesta r&aacute;pida &bull; Garant&iacute;a 100%'),
    'cov_eyebrow'=>array('it'=>'Dove operiamo','en'=>'Where we operate','fr'=>'O&ugrave; nous intervenons','es'=>'D&oacute;nde operamos'),
    'cov_heading'=>array('it'=>'Le principali fiere italiane','en'=>'Major Italian trade fairs','fr'=>'Les principaux salons italiens','es'=>'Las principales ferias italianas'),
    'b2bonly'   => array('it'=>'Servizio per aziende, agenzie e produzioni — non per candidature.','en'=>'A service for companies, agencies and productions — not for job applications.','fr'=>'Un service pour entreprises, agences et productions — pas pour les candidatures.','es'=>'Un servicio para empresas, agencias y producciones — no para candidaturas.'),
    'talentexit'=> array('it'=>'Sei un talent in cerca di lavoro? Registrati qui','en'=>'Are you a talent looking for work? Register here','fr'=>'Vous &ecirc;tes un talent &agrave; la recherche de travail ? Inscrivez-vous ici','es'=>'&iquest;Eres un talento que busca trabajo? Reg&iacute;strate aqu&iacute;'),
);

// ── 11 servizi in UNA griglia, testo breve (1 riga) ──
$services = array(
  array('id'=>'hostess','title'=>array('it'=>'Hostess & Promoter','en'=>'Hostesses & Promoters','fr'=>'H&ocirc;tesses & Promotrices','es'=>'Azafatas & Promotoras'),
    'text'=>array('it'=>'Accoglienza, reception e promozione a fiere ed eventi. Multilingua.','en'=>'Reception and brand promotion at fairs and events. Multilingual.','fr'=>'Accueil et promotion de marque sur salons et &eacute;v&eacute;nements. Multilingues.','es'=>'Recepci&oacute;n y promoci&oacute;n de marca en ferias y eventos. Multiling&uuml;es.')),
  array('id'=>'steward','title'=>array('it'=>'Steward & Accoglienza','en'=>'Stewards & Welcome','fr'=>'Stewards & Accueil','es'=>'Stewards & Acogida'),
    'text'=>array('it'=>'Gestione accessi, guardaroba e supporto in sala.','en'=>'Access management, cloakroom and floor support.','fr'=>'Gestion des acc&egrave;s, vestiaire et support en salle.','es'=>'Gesti&oacute;n de accesos, guardarrop&iacute;a y apoyo en sala.')),
  array('id'=>'interpreti','title'=>array('it'=>'Interpreti','en'=>'Interpreters','fr'=>'Interpr&egrave;tes','es'=>'Int&eacute;rpretes'),
    'text'=>array('it'=>'Hostess multilingua per delegazioni e stand internazionali.','en'=>'Multilingual hostesses for delegations and international stands.','fr'=>'H&ocirc;tesses multilingues pour d&eacute;l&eacute;gations et stands internationaux.','es'=>'Azafatas multiling&uuml;es para delegaciones y stands internacionales.')),
  array('id'=>'bartender','title'=>array('it'=>'Bartender','en'=>'Bartenders','fr'=>'Barmans','es'=>'Bartenders'),
    'text'=>array('it'=>'Barman professionisti per open bar ed eventi aziendali.','en'=>'Professional bartenders for open bars and corporate events.','fr'=>'Barmans professionnels pour open bars et &eacute;v&eacute;nements.','es'=>'Bartenders profesionales para open bar y eventos.')),
  array('id'=>'camerieri','title'=>array('it'=>'Camerieri','en'=>'Waiters','fr'=>'Serveurs','es'=>'Camareros'),
    'text'=>array('it'=>'Sala e banqueting per catering, gala e ricevimenti.','en'=>'Floor and banqueting for catering, galas and receptions.','fr'=>'Salle et banquet pour traiteur, galas et r&eacute;ceptions.','es'=>'Sala y banqueting para catering, galas y recepciones.')),
  array('id'=>'sicurezza','title'=>array('it'=>'Steward di sicurezza','en'=>'Security stewards','fr'=>'Stewards s&eacute;curit&eacute;','es'=>'Stewards de seguridad'),
    'text'=>array('it'=>'Controllo accessi e gestione flussi in evento.','en'=>'Access control and crowd-flow management.','fr'=>'Contr&ocirc;le des acc&egrave;s et gestion des flux.','es'=>'Control de accesos y gesti&oacute;n de flujos.')),
  array('id'=>'autisti','title'=>array('it'=>'Autisti & Logistica','en'=>'Drivers & Logistics','fr'=>'Chauffeurs & Logistique','es'=>'Conductores & Log&iacute;stica'),
    'text'=>array('it'=>'Trasferimenti di ospiti, delegazioni e materiali.','en'=>'Transfers of guests, delegations and materials.','fr'=>'Transferts d\'invit&eacute;s, d&eacute;l&eacute;gations et mat&eacute;riel.','es'=>'Traslados de invitados, delegaciones y materiales.')),
  array('id'=>'runner','title'=>array('it'=>'Runner','en'=>'Runners','fr'=>'Runners','es'=>'Runners'),
    'text'=>array('it'=>'Supporto operativo e backstage in evento.','en'=>'Operational and backstage support during the event.','fr'=>'Support op&eacute;rationnel et backstage.','es'=>'Apoyo operativo y backstage.')),
  array('id'=>'fotografi','title'=>array('it'=>'Fotografi','en'=>'Photographers','fr'=>'Photographes','es'=>'Fot&oacute;grafos'),
    'text'=>array('it'=>'Reportage e coverage di stand, congressi e cerimonie.','en'=>'Reportage and coverage of stands, congresses and ceremonies.','fr'=>'Reportage et couverture de stands, congr&egrave;s et c&eacute;r&eacute;monies.','es'=>'Reportaje y cobertura de stands, congresos y ceremonias.')),
  array('id'=>'videomaker','title'=>array('it'=>'Videomaker','en'=>'Videomakers','fr'=>'Vid&eacute;astes','es'=>'Videomakers'),
    'text'=>array('it'=>'Riprese, aftermovie e contenuti social dell\'evento.','en'=>'Filming, aftermovies and social content.','fr'=>'Tournage, aftermovie et contenus sociaux.','es'=>'Grabaci&oacute;n, aftermovie y contenido social.')),
  array('id'=>'tour-leader','title'=>array('it'=>'Tour Leader','en'=>'Tour Leaders','fr'=>'Tour Leaders','es'=>'Tour Leaders'),
    'text'=>array('it'=>'Accompagnatori per gruppi, incentive e visite aziendali.','en'=>'Escorts for groups, incentives and corporate visits.','fr'=>'Accompagnateurs pour groupes, incentives et visites.','es'=>'Acompa&ntilde;antes para grupos, incentivos y visitas.')),
);

// Griglia staff di ESEMPIO (nomi/ruoli fittizi, illustrativi)
$staff = array(
  array('img'=>'/wp-content/uploads/2025/09/hostess-38-yo-caucasian.jpg','name'=>'Giulia','role'=>array('it'=>'Hostess','en'=>'Hostess','fr'=>'H&ocirc;tesse','es'=>'Azafata')),
  array('img'=>'/wp-content/uploads/2025/09/steward-35-yo-caucasian.jpg','name'=>'Marco','role'=>array('it'=>'Steward','en'=>'Steward','fr'=>'Steward','es'=>'Steward')),
  array('img'=>'/wp-content/uploads/2025/09/steward-31-yo-ispanic.jpg','name'=>'Luca','role'=>array('it'=>'Bartender','en'=>'Bartender','fr'=>'Barman','es'=>'Bartender')),
  array('img'=>'/wp-content/uploads/2025/09/hostess-32-yo-caucasian.jpg','name'=>'Sofia','role'=>array('it'=>'Cameriera','en'=>'Waiter','fr'=>'Serveuse','es'=>'Camarera')),
  array('img'=>'/wp-content/uploads/2025/09/hostess-29-yo-east-asian.jpg','name'=>'Elena','role'=>array('it'=>'Interprete','en'=>'Interpreter','fr'=>'Interpr&egrave;te','es'=>'Int&eacute;rprete')),
  array('img'=>'/wp-content/uploads/2025/09/steward-40-yo-caucasian.jpg','name'=>'Davide','role'=>array('it'=>'Steward sicurezza','en'=>'Security steward','fr'=>'Steward s&eacute;curit&eacute;','es'=>'Steward seguridad')),
  array('img'=>'/wp-content/uploads/2025/09/steward-50-yo-african.jpg','name'=>'Alessandro','role'=>array('it'=>'Autista','en'=>'Driver','fr'=>'Chauffeur','es'=>'Conductor')),
  array('img'=>'/wp-content/uploads/2025/09/hostess-26-yo-caucasian.jpg','name'=>'Chiara','role'=>array('it'=>'Runner','en'=>'Runner','fr'=>'Runner','es'=>'Runner')),
  array('img'=>'/wp-content/uploads/2025/09/steward-26-yo-east-asian-1.jpg','name'=>'Matteo','role'=>array('it'=>'Fotografo','en'=>'Photographer','fr'=>'Photographe','es'=>'Fot&oacute;grafo')),
  array('img'=>'/wp-content/uploads/2025/09/sport-hostess-25-yo-caucasian.jpg','name'=>'Sara','role'=>array('it'=>'Videomaker','en'=>'Videomaker','fr'=>'Vid&eacute;aste','es'=>'Videomaker')),
  array('img'=>'/wp-content/uploads/2025/09/steward-39-north-european.jpg','name'=>'Andrea','role'=>array('it'=>'Tour Leader','en'=>'Tour Leader','fr'=>'Tour Leader','es'=>'Tour Leader')),
  array('img'=>'/wp-content/uploads/2025/09/steward-28-yo-african.jpg','name'=>'Simone','role'=>array('it'=>'DJ','en'=>'DJ','fr'=>'DJ','es'=>'DJ')),
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
    array('src' => '/wp-content/uploads/2025/09/hostess-26-yo-caucasian.jpg', 'alt' => 'Hostess per eventi aziendali e fiere — TOAgency'),
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

<!-- Griglia staff di esempio — STESSE schede della pagina Modelli (.toa-cast-card) -->
<style>
.toa-cast-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:14px}
.toa-cast-card{position:relative;border-radius:14px;overflow:hidden;aspect-ratio:3/4;background:#141414;border:1px solid rgba(255,255,255,.06)}
.toa-cast-card img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .45s cubic-bezier(.2,.8,.3,1)}
.toa-cast-card:hover img{transform:scale(1.05)}
.toa-cast-card .ov{position:absolute;left:0;right:0;bottom:0;padding:14px 12px 11px;background:linear-gradient(transparent,rgba(0,0,0,.88));color:#fff}
.toa-cast-card .ov b{display:block;font-size:13px;font-weight:700;letter-spacing:.4px}
.toa-cast-card .ov span{display:block;font-size:12px;color:rgba(255,255,255,.6);margin-top:2px}
@media(max-width:768px){.toa-cast-grid{grid-template-columns:repeat(3,1fr);gap:12px}}
@media(max-width:480px){.toa-cast-grid{grid-template-columns:repeat(3,1fr);gap:8px}}
</style>
<section class="why-section" style="padding-bottom:0">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t(array('it'=>'Il nostro staff','en'=>'Our staff','fr'=>'Notre &eacute;quipe','es'=>'Nuestro equipo')); ?></div>
        <h2 class="section-heading" style="margin-bottom:22px"><?php echo $_t(array('it'=>'Alcune delle figure che forniamo','en'=>'Some of the profiles we provide','fr'=>'Quelques profils que nous fournissons','es'=>'Algunos de los perfiles que ofrecemos')); ?></h2>
    </div>
    <div class="toa-cast-grid container">
        <?php foreach ($staff as $p): ?>
        <div class="toa-cast-card">
            <img src="<?php echo esc_attr($p['img']); ?>" alt="<?php echo esc_attr($p['name'].' — '.$_t($p['role'])); ?>" loading="lazy">
            <div class="ov"><b><?php echo esc_html($p['name']); ?></b><span><?php echo $_t($p['role']); ?></span></div>
        </div>
        <?php endforeach; ?>
    </div>
    <p class="container" style="font-size:11px;color:var(--gray-4);margin:14px auto 0;text-align:center"><?php echo $_t(array('it'=>'Profili illustrativi &mdash; lo staff reale viene selezionato per il tuo evento.','en'=>'Illustrative profiles &mdash; real staff is selected for your event.','fr'=>'Profils illustratifs &mdash; le personnel r&eacute;el est s&eacute;lectionn&eacute; pour votre &eacute;v&eacute;nement.','es'=>'Perfiles ilustrativos &mdash; el personal real se selecciona para tu evento.')); ?></p>
</section>

<!-- Servizi: una sola griglia compatta -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['serv_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['serv_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <?php foreach ($services as $s): ?>
        <div class="feature-card" id="<?php echo esc_attr($s['id']); ?>">
            <h3 class="feature-title"><?php echo $_t($s['title']); ?></h3>
            <p class="feature-text"><?php echo $_t($s['text']); ?></p>
            <a href="#preventivo" style="display:inline-block;margin-top:12px;font-size:0.78rem;font-weight:800;text-transform:uppercase;letter-spacing:1px;color:var(--accent);text-decoration:none"><?php echo $_t($t['cta_service']); ?> &rarr;</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

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
        <div class="coverage-country"><h4>Rimini</h4><p>Rimini Fiera, Sigep, TTG Travel</p></div>
        <div class="coverage-country"><h4>Roma</h4><p>Fiera di Roma, Maker Faire, Romics</p></div>
        <div class="coverage-country"><h4>Firenze</h4><p>Fortezza da Basso, Pitti Immagine, Taste</p></div>
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
