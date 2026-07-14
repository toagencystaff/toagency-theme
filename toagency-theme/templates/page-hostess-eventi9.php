<?php
/**
 * Template Name: Hostess & Steward
 * HUB servizi staff eventi (v8-clean 14/07/2026, ricostruito): hero CTA centrato,
 * fascia staff stile Modelli, servizi a pillole completi, recensioni, Italia+Europa. IT/EN/FR/ES.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Hostess, steward e <strong>staff completo</strong> per fiere, eventi, congressi e matrimoni.<br>Un unico partner per tutto l\'evento &mdash; preventivo gratuito, ti ricontattiamo subito.',
        'en' => 'Hostesses, stewards and <strong>complete staff</strong> for trade fairs, events, conferences and weddings.<br>One partner for the whole event &mdash; free quote, we get back to you right away.',
        'fr' => 'H&ocirc;tesses, stewards et <strong>personnel complet</strong> pour salons, &eacute;v&eacute;nements, congr&egrave;s et mariages.<br>Un seul partenaire pour tout l\'&eacute;v&eacute;nement &mdash; devis gratuit, r&eacute;ponse imm&eacute;diate.',
        'es' => 'Azafatas, stewards y <strong>personal completo</strong> para ferias, eventos, congresos y bodas.<br>Un &uacute;nico partner para todo el evento &mdash; presupuesto gratuito, te contactamos enseguida.',
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
    'cov_heading'=>array('it'=>'In tutta Italia e in Europa','en'=>'Across Italy and Europe','fr'=>'Dans toute l\'Italie et en Europe','es'=>'En toda Italia y en Europa'),
    'cov_sub'=>array('it'=>'Fiere, eventi, congressi e matrimoni in tutte le principali citt&agrave; italiane &mdash; e su richiesta in Francia, Spagna e UK.','en'=>'Trade fairs, events, conferences and weddings in every major Italian city &mdash; and on request in France, Spain and the UK.','fr'=>'Salons, &eacute;v&eacute;nements, congr&egrave;s et mariages dans toutes les grandes villes italiennes &mdash; et sur demande en France, Espagne et UK.','es'=>'Ferias, eventos, congresos y bodas en las principales ciudades italianas &mdash; y bajo petici&oacute;n en Francia, Espa&ntilde;a y UK.'),
    'b2bonly'   => array('it'=>'Servizio per aziende, agenzie e produzioni — non per candidature.','en'=>'A service for companies, agencies and productions — not for job applications.','fr'=>'Un service pour entreprises, agences et productions — pas pour les candidatures.','es'=>'Un servicio para empresas, agencias y producciones — no para candidaturas.'),
    'talentexit'=> array('it'=>'Sei un talent in cerca di lavoro? Registrati qui','en'=>'Are you a talent looking for work? Register here','fr'=>'Vous &ecirc;tes un talent &agrave; la recherche de travail ? Inscrivez-vous ici','es'=>'&iquest;Eres un talento que busca trabajo? Reg&iacute;strate aqu&iacute;'),
);

$groups = array(
  array('cat'=>array('it'=>'Accoglienza & front-of-house','en'=>'Reception & front-of-house','fr'=>'Accueil','es'=>'Recepci&oacute;n'),'items'=>array(
    array('it'=>'Hostess & Promoter','en'=>'Hostesses & Promoters','fr'=>'H&ocirc;tesses & Promotrices','es'=>'Azafatas & Promotoras'),
    array('it'=>'Steward','en'=>'Stewards','fr'=>'Stewards','es'=>'Stewards'),
    array('it'=>'Standisti','en'=>'Booth staff','fr'=>'Standistes','es'=>'Personal de stand'),
    array('it'=>'Interpreti','en'=>'Interpreters','fr'=>'Interpr&egrave;tes','es'=>'Int&eacute;rpretes'),
  )),
  array('cat'=>array('it'=>'Food & Beverage','en'=>'Food & Beverage','fr'=>'Food & Beverage','es'=>'Food & Beverage'),'items'=>array(
    array('it'=>'Bartender','en'=>'Bartenders','fr'=>'Barmans','es'=>'Bartenders'),
    array('it'=>'Camerieri','en'=>'Waiters','fr'=>'Serveurs','es'=>'Camareros'),
    array('it'=>'Sommelier','en'=>'Sommeliers','fr'=>'Sommeliers','es'=>'Sumilleres'),
    array('it'=>'Baristi','en'=>'Baristas','fr'=>'Baristas','es'=>'Baristas'),
  )),
  array('cat'=>array('it'=>'Musica & Intrattenimento','en'=>'Music & Entertainment','fr'=>'Musique & Animation','es'=>'M&uacute;sica & Entretenimiento'),'items'=>array(
    array('it'=>'DJ','en'=>'DJ','fr'=>'DJ','es'=>'DJ'),
    array('it'=>'Musicisti live','en'=>'Live musicians','fr'=>'Musiciens live','es'=>'M&uacute;sicos en vivo'),
    array('it'=>'Cantanti','en'=>'Singers','fr'=>'Chanteurs','es'=>'Cantantes'),
    array('it'=>'Ballerini & Performer','en'=>'Dancers & Performers','fr'=>'Danseurs & Performers','es'=>'Bailarines & Performers'),
    array('it'=>'Animazione','en'=>'Entertainers','fr'=>'Animation','es'=>'Animaci&oacute;n'),
    array('it'=>'Artisti','en'=>'Artists','fr'=>'Artistes','es'=>'Artistas'),
  )),
  array('cat'=>array('it'=>'Bambini','en'=>'Kids','fr'=>'Enfants','es'=>'Ni&ntilde;os'),'items'=>array(
    array('it'=>'Truccabimbi','en'=>'Face painting','fr'=>'Maquillage enfants','es'=>'Pintacaritas'),
    array('it'=>'Animatori bambini','en'=>'Kids entertainers','fr'=>'Animateurs enfants','es'=>'Animadores infantiles'),
    array('it'=>'Baby parking','en'=>'Baby parking','fr'=>'Baby parking','es'=>'Baby parking'),
  )),
  array('cat'=>array('it'=>'Immagine & Beauty','en'=>'Image & Beauty','fr'=>'Image & Beaut&eacute;','es'=>'Imagen & Beauty'),'items'=>array(
    array('it'=>'Make-up artist','en'=>'Make-up artists','fr'=>'Maquilleurs','es'=>'Maquilladores'),
    array('it'=>'Hairstylist','en'=>'Hairstylists','fr'=>'Coiffeurs','es'=>'Peluqueros'),
    array('it'=>'Modelli & Modelle','en'=>'Models','fr'=>'Mannequins','es'=>'Modelos'),
  )),
  array('cat'=>array('it'=>'Foto & Video','en'=>'Photo & Video','fr'=>'Photo & Vid&eacute;o','es'=>'Foto & V&iacute;deo'),'items'=>array(
    array('it'=>'Fotografi','en'=>'Photographers','fr'=>'Photographes','es'=>'Fot&oacute;grafos'),
    array('it'=>'Videomaker','en'=>'Videomakers','fr'=>'Vid&eacute;astes','es'=>'Videomakers'),
  )),
  array('cat'=>array('it'=>'Sicurezza & Logistica','en'=>'Security & Logistics','fr'=>'S&eacute;curit&eacute; & Logistique','es'=>'Seguridad & Log&iacute;stica'),'items'=>array(
    array('it'=>'Security','en'=>'Security','fr'=>'S&eacute;curit&eacute;','es'=>'Seguridad'),
    array('it'=>'Autisti / NCC','en'=>'Drivers / NCC','fr'=>'Chauffeurs','es'=>'Conductores'),
    array('it'=>'Runner','en'=>'Runners','fr'=>'Runners','es'=>'Runners'),
    array('it'=>'Parcheggiatori','en'=>'Valet parking','fr'=>'Voituriers','es'=>'Aparcacoches'),
  )),
  array('cat'=>array('it'=>'Coordinamento','en'=>'Coordination','fr'=>'Coordination','es'=>'Coordinaci&oacute;n'),'items'=>array(
    array('it'=>'Coordinatori','en'=>'Coordinators','fr'=>'Coordinateurs','es'=>'Coordinadores'),
    array('it'=>'Tour leader','en'=>'Tour leaders','fr'=>'Tour leaders','es'=>'Tour leaders'),
  )),
);

$SP = '/wp-content/themes/toagency-theme/assets/staff/';
$staff = array(
  array('img'=>$SP.'hostess.jpg','name'=>'Giulia','role'=>array('it'=>'Hostess','en'=>'Hostess','fr'=>'H&ocirc;tesse','es'=>'Azafata')),
  array('img'=>$SP.'steward.jpg','name'=>'Marco','role'=>array('it'=>'Steward','en'=>'Steward','fr'=>'Steward','es'=>'Steward')),
  array('img'=>$SP.'bartender.jpg','name'=>'Luca','role'=>array('it'=>'Bartender','en'=>'Bartender','fr'=>'Barman','es'=>'Bartender')),
  array('img'=>$SP.'cameriera.jpg','name'=>'Sofia','role'=>array('it'=>'Cameriera','en'=>'Waiter','fr'=>'Serveuse','es'=>'Camarera')),
  array('img'=>$SP.'interprete.jpg','name'=>'Elena','role'=>array('it'=>'Interprete','en'=>'Interpreter','fr'=>'Interpr&egrave;te','es'=>'Int&eacute;rprete')),
  array('img'=>$SP.'security.jpg','name'=>'David','role'=>array('it'=>'Security','en'=>'Security','fr'=>'S&eacute;curit&eacute;','es'=>'Seguridad')),
  array('img'=>$SP.'autista.jpg','name'=>'Alessandro','role'=>array('it'=>'Autista','en'=>'Driver','fr'=>'Chauffeur','es'=>'Conductor')),
  array('img'=>$SP.'dj.jpg','name'=>'Simone','role'=>array('it'=>'DJ','en'=>'DJ','fr'=>'DJ','es'=>'DJ')),
  array('img'=>$SP.'fotografa.jpg','name'=>'Sara','role'=>array('it'=>'Fotografa','en'=>'Photographer','fr'=>'Photographe','es'=>'Fot&oacute;grafa')),
  array('img'=>$SP.'videomaker.jpg','name'=>'Matteo','role'=>array('it'=>'Videomaker','en'=>'Videomaker','fr'=>'Vid&eacute;aste','es'=>'Videomaker')),
  array('img'=>$SP.'promoter.jpg','name'=>'Chiara','role'=>array('it'=>'Promoter','en'=>'Promoter','fr'=>'Promotrice','es'=>'Promotora')),
  array('img'=>$SP.'runner.jpg','name'=>'Andrea','role'=>array('it'=>'Runner','en'=>'Runner','fr'=>'Runner','es'=>'Runner')),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'STAFF PER EVENTI',
    'title'      => _t_raw(array('it'=>'Hostess & staff per eventi.','en'=>'Hostess & event staff.','fr'=>'Hôtesses & staff événementiel.','es'=>'Azafatas & staff para eventos.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Hero CTA CENTRATO + trust line -->
<div class="container" style="margin-top:-8px;margin-bottom:28px;text-align:center">
    <a href="#preventivo" class="btn-hero btn-hero-primary" style="display:inline-flex;align-items:center;gap:8px">
        <span><?php echo $_t($t['hero_cta']); ?></span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.85rem;color:var(--gray-4);margin-top:14px;font-weight:600"><?php echo $_t($t['trust_line']); ?></p>
</div>

<style>
.toa-staffband{background:#0a0a0a;padding:48px 20px 40px}
.toa-staffband .inner{max-width:1080px;margin:0 auto}
.toa-staffband .toa-cast-hd{text-align:center;margin:0 0 22px}
.toa-staffband .toa-cast-hd h2{font-family:var(--font-display,Georgia,serif);font-size:clamp(20px,3vw,28px);font-weight:900;color:#fff;margin:0 0 6px;text-transform:uppercase;letter-spacing:.5px}
.toa-staffband .toa-cast-hd p{font-size:12px;color:rgba(255,255,255,.45);margin:0;text-transform:uppercase;letter-spacing:1px}
.toa-staffband .toa-cast-hd .desc{text-transform:none;font-size:13px;font-weight:400;color:rgba(255,255,255,.6);max-width:620px;margin:12px auto 0;line-height:1.5;letter-spacing:0}
.toa-staffband .toa-cast-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:14px}
.toa-staffband .toa-cast-card{position:relative;border-radius:14px;overflow:hidden;aspect-ratio:3/4;background:#141414;border:1px solid rgba(255,255,255,.06)}
.toa-staffband .toa-cast-card img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .45s cubic-bezier(.2,.8,.3,1)}
.toa-staffband .toa-cast-card:hover img{transform:scale(1.05)}
.toa-staffband .toa-cast-card .ov{position:absolute;left:0;right:0;bottom:0;padding:14px 12px 11px;background:linear-gradient(transparent,rgba(0,0,0,.88));color:#fff}
.toa-staffband .toa-cast-card .ov b{display:block;font-size:13px;font-weight:700;letter-spacing:.4px}
.toa-staffband .toa-cast-card .ov .code{display:block;font-size:11px;font-weight:600;color:#c8ff00;letter-spacing:.5px;margin-top:1px}
.toa-staffband .toa-cast-cta{text-align:center;margin:26px 0 6px}
.toa-staffband .toa-cast-cta a{display:inline-block;margin:6px 5px;padding:14px 30px;background:#c8ff00;color:#000;border:1px solid #c8ff00;border-radius:8px;font-weight:700;font-size:14px;letter-spacing:.04em;text-decoration:none;transition:opacity .2s,background .2s}
.toa-staffband .toa-cast-cta a:hover{opacity:.85}
.toa-staffband .toa-cast-cta a.alt{background:transparent;color:#c8ff00}
.toa-staffband .toa-cast-cta a.alt:hover{background:rgba(200,255,0,.12);opacity:1}
.toa-staffband .toa-cast-note{text-align:center;font-size:11px;color:rgba(255,255,255,.4);margin:10px 0 0}
@media(max-width:768px){.toa-staffband .toa-cast-grid{grid-template-columns:repeat(3,1fr);gap:12px}}
@media(max-width:480px){.toa-staffband .toa-cast-grid{grid-template-columns:repeat(3,1fr);gap:8px}}
.toa-serv-cat{font-family:var(--font-display);font-size:.8rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;margin:22px 0 10px;color:var(--accent)}
.toa-serv-chips{display:flex;flex-wrap:wrap;gap:8px;margin:0 0 6px}
.toa-serv-chips a{display:inline-block;padding:9px 16px;border:1px solid rgba(150,150,150,.35);border-radius:22px;font-size:.85rem;font-weight:600;color:inherit;text-decoration:none;transition:border-color .2s,color .2s,background .2s}
.toa-serv-chips a:hover{border-color:var(--accent);color:var(--accent);background:rgba(200,255,0,.06)}
</style>

<section class="toa-staffband">
    <div class="inner">
        <div class="toa-cast-hd">
            <h2><?php echo $_t(array('it'=>'Alcune delle figure che forniamo','en'=>'Some of the staff we provide','fr'=>'Quelques profils que nous fournissons','es'=>'Algunos de los perfiles que ofrecemos')); ?></h2>
            <p><?php echo $_t(array('it'=>'Staff selezionato &middot; 20.000+ profili','en'=>'Selected staff &middot; 20,000+ profiles','fr'=>'Personnel s&eacute;lectionn&eacute; &middot; 20 000+ profils','es'=>'Personal seleccionado &middot; 20.000+ perfiles')); ?></p>
            <p class="desc"><?php echo $_t(array('it'=>'Hostess, steward, bartender, security, DJ e molto altro: costruiamo lo staff su misura per il tuo evento, con un referente dedicato.','en'=>'Hostesses, stewards, bartenders, security, DJs and more: we build the staff tailored to your event, with a dedicated manager.','fr'=>'H&ocirc;tesses, stewards, barmans, s&eacute;curit&eacute;, DJ et plus : nous construisons le personnel sur mesure.','es'=>'Azafatas, stewards, bartenders, seguridad, DJ y m&aacute;s: creamos el personal a medida.')); ?></p>
        </div>
        <div class="toa-cast-grid">
            <?php foreach ($staff as $p): ?>
            <div class="toa-cast-card">
                <img src="<?php echo esc_attr($p['img']); ?>" alt="<?php echo esc_attr($p['name'].' — '.$_t($p['role'])); ?>" loading="lazy">
                <div class="ov"><b><?php echo esc_html($p['name']); ?></b><span class="code"><?php echo $_t($p['role']); ?></span></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="toa-cast-cta">
            <a href="#preventivo"><?php echo $_t(array('it'=>'Richiedi un preventivo &rarr;','en'=>'Request a quote &rarr;','fr'=>'Demander un devis &rarr;','es'=>'Solicitar presupuesto &rarr;')); ?></a>
            <a href="https://wa.me/393517899225" class="alt" target="_blank" rel="noopener">WhatsApp</a>
        </div>
        <p class="toa-cast-note"><?php echo $_t(array('it'=>'Profili illustrativi &mdash; lo staff reale viene selezionato per il tuo evento.','en'=>'Illustrative profiles &mdash; real staff is selected for your event.','fr'=>'Profils illustratifs &mdash; le personnel r&eacute;el est s&eacute;lectionn&eacute;.','es'=>'Perfiles ilustrativos &mdash; el personal real se selecciona para tu evento.')); ?></p>
    </div>
</section>

<!-- Servizi completi a pillole -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['serv_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['serv_heading']); ?></h2>
        <?php foreach ($groups as $g): ?>
        <div class="toa-serv-cat"><?php echo $_t($g['cat']); ?></div>
        <div class="toa-serv-chips">
            <?php foreach ($g['items'] as $it): ?>
            <a href="#preventivo"><?php echo $_t($it); ?></a>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        <div style="text-align:center;margin-top:30px">
            <a href="#preventivo" class="btn-hero btn-hero-primary"><?php echo $_t($t['hero_cta']); ?></a>
        </div>
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

<?php toa_component('google-reviews'); ?>

<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['cov_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:12px"><?php echo $_t($t['cov_heading']); ?></h2>
        <p style="font-size:0.95rem;color:var(--gray-4);max-width:640px;margin:0 0 36px"><?php echo $_t($t['cov_sub']); ?></p>
    </div>
    <div class="coverage-grid container">
        <div class="coverage-country"><h4>Milano</h4><p>Fiera Milano Rho, Salone del Mobile, Fashion Week</p></div>
        <div class="coverage-country"><h4>Bologna</h4><p>Bologna Fiere, Cosmoprof, Motor Show</p></div>
        <div class="coverage-country"><h4>Verona</h4><p>Veronafiere, Vinitaly, Marmomac</p></div>
        <div class="coverage-country"><h4>Rimini</h4><p>Rimini Fiera, Sigep, TTG Travel</p></div>
        <div class="coverage-country"><h4>Roma</h4><p>Fiera di Roma, Maker Faire, Romics</p></div>
        <div class="coverage-country"><h4>Europa</h4><p>Francia, Spagna, UK su richiesta</p></div>
    </div>
</section>

<?php toa_component('form-eventi-inline', array('lang' => $lang)); ?>

<section style="padding:40px 0;background:var(--black);color:#fff;text-align:center">
    <div class="container">
        <p style="font-size:0.95rem;margin-bottom:10px;opacity:.85"><?php echo $_t($t['b2bonly']); ?></p>
        <a href="<?php echo home_url('/registrati-talent/'); ?>" style="display:inline-block;font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;color:var(--accent);text-decoration:none"><?php echo $_t($t['talentexit']); ?> &rarr;</a>
    </div>
</section>

<?php toa_component('footer'); ?>
