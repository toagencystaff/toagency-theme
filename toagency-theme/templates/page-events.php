<?php
/**
 * Template Name: TOA Events
 * Divisione eventi di TOAgency (ticket #637, 14/10/2026) — IT/EN/FR/ES.
 * Servizi: Event Staff, Fiere, Foto e video, Crew tecnica, Logistica (solo via partner con licenza autotrasporto c/terzi).
 * Vincoli: nessun prezzo; nessuna promessa di numeri/copertura (interpreti, tour leader: 0 profili);
 * security e mansioni tecniche solo con personale in regola. Stampo: page-hostess-eventi20.php.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_title' => array(
        'it' => "Staff, crew e servizi per eventi.",
        'en' => "Staff, crew and services for events.",
        'fr' => "Personnel, équipes et services pour l'événementiel.",
        'es' => "Personal, equipos y servicios para eventos.",
    ),
    'hero_subtitle' => array(
        'it' => "TOA Events è la divisione di TOAgency per <strong>eventi, fiere, congressi e produzioni</strong>.<br>Staff, crew, foto e video e logistica: un unico referente, un unico preventivo.",
        'en' => "TOA Events is TOAgency's division for <strong>events, trade shows, conferences and productions</strong>.<br>Staff, crew, photo and video, and logistics: one point of contact, one quote.",
        'fr' => "TOA Events est la division de TOAgency dédiée aux <strong>événements, salons, congrès et productions</strong>.<br>Personnel, équipes, photo et vidéo, logistique : un seul interlocuteur, un seul devis.",
        'es' => "TOA Events es la división de TOAgency para <strong>eventos, ferias, congresos y producciones</strong>.<br>Personal, equipos, foto y vídeo y logística: un único interlocutor, un único presupuesto.",
    ),
    'hero_cta' => array('it'=>"Richiedi un preventivo",'en'=>"Request a quote",'fr'=>"Demandez un devis",'es'=>"Solicita un presupuesto"),
    'hero_line' => array(
        'it' => "Raccontaci cosa ti serve per l'evento: ce ne occupiamo noi.",
        'en' => "Tell us what your event needs: we'll take care of it.",
        'fr' => "Dites-nous ce dont votre événement a besoin : nous nous en occupons.",
        'es' => "Cuéntanos qué necesita tu evento: nos encargamos nosotros.",
    ),
    'b2bonly' => array('it'=>"Servizio per aziende, agenzie e produzioni — non per candidature.",'en'=>"A service for companies, agencies and productions — not for job applications.",'fr'=>"Un service pour entreprises, agences et productions — pas pour les candidatures.",'es'=>"Un servicio para empresas, agencias y producciones — no para candidaturas."),
    'talentexit' => array('it'=>"Sei un talent in cerca di lavoro? Registrati qui",'en'=>"Are you a talent looking for work? Register here",'fr'=>"Vous êtes un talent à la recherche de travail ? Inscrivez-vous ici",'es'=>"¿Eres un talento que busca trabajo? Regístrate aquí"),
    'serv_eyebrow' => array('it'=>"Servizi",'en'=>"Services",'fr'=>"Services",'es'=>"Servicios"),
    'serv_heading' => array('it'=>"Tutto quello che serve al tuo evento",'en'=>"Everything your event needs",'fr'=>"Tout ce dont votre événement a besoin",'es'=>"Todo lo que necesita tu evento"),
    'serv_cta' => array('it'=>"Chiedi un preventivo",'en'=>"Ask for a quote",'fr'=>"Demander un devis",'es'=>"Pedir presupuesto"),
    'serv_other' => array(
        'it' => "Non trovi quello che cerchi? Scrivici: ti diciamo come possiamo aiutarti.",
        'en' => "Can't see what you need? Write to us and we'll tell you how we can help.",
        'fr' => "Vous ne trouvez pas ce qu'il vous faut ? Écrivez-nous : nous vous dirons comment vous aider.",
        'es' => "¿No ves lo que necesitas? Escríbenos y te diremos cómo podemos ayudarte.",
    ),
    'feat_eyebrow' => array('it'=>"Perché TOA Events",'en'=>"Why TOA Events",'fr'=>"Pourquoi TOA Events",'es'=>"Por qué TOA Events"),
    'feat_heading' => array('it'=>"Come lavoriamo",'en'=>"How we work",'fr'=>"Notre façon de travailler",'es'=>"Cómo trabajamos"),
    'feat1_title' => array('it'=>"Un solo referente",'en'=>"One point of contact",'fr'=>"Un seul interlocuteur",'es'=>"Un único interlocutor"),
    'feat1_text'  => array('it'=>"Una persona coordina staff, crew e produzione.",'en'=>"One person coordinates staff, crew and production.",'fr'=>"Une seule personne coordonne le personnel, les équipes et la production.",'es'=>"Una sola persona coordina personal, equipos y producción."),
    'feat2_title' => array('it'=>"Un solo preventivo",'en'=>"One quote",'fr'=>"Un seul devis",'es'=>"Un único presupuesto"),
    'feat2_text'  => array('it'=>"Tutti i servizi dell'evento in un'unica proposta.",'en'=>"All your event services in a single proposal.",'fr'=>"Tous les services de l'événement dans une seule proposition.",'es'=>"Todos los servicios del evento en una sola propuesta."),
    'feat3_title' => array('it'=>"Persone selezionate",'en'=>"Selected people",'fr'=>"Des profils sélectionnés",'es'=>"Perfiles seleccionados"),
    'feat3_text'  => array('it'=>"Profili scelti dal database TOAgency in base a ciò che serve al tuo evento.",'en'=>"Profiles chosen from the TOAgency database to match what your event needs.",'fr'=>"Des profils choisis dans la base TOAgency selon les besoins de votre événement.",'es'=>"Perfiles elegidos de la base de datos de TOAgency según lo que necesita tu evento."),
    'feat4_title' => array('it'=>"Risposta rapida",'en'=>"Fast reply",'fr'=>"Réponse rapide",'es'=>"Respuesta rápida"),
    'feat4_text'  => array('it'=>"Ti ricontattiamo subito.",'en'=>"We get back to you right away.",'fr'=>"Nous vous recontactons tout de suite.",'es'=>"Te contactamos enseguida."),
    'how_step1' => array('it'=>"CI SCRIVI",'en'=>"CONTACT US",'fr'=>"CONTACTEZ-NOUS",'es'=>"CONTÁCTANOS"),
    'how_step2' => array('it'=>"TI RISPONDIAMO",'en'=>"WE REPLY",'fr'=>"NOUS RÉPONDONS",'es'=>"TE RESPONDEMOS"),
    'how_step3' => array('it'=>"PROPOSTA SU MISURA",'en'=>"TAILORED PROPOSAL",'fr'=>"PROPOSITION SUR MESURE",'es'=>"PROPUESTA A MEDIDA"),
    'how_step4' => array('it'=>"GESTIONE COMPLETA",'en'=>"FULL MANAGEMENT",'fr'=>"GESTION COMPLÈTE",'es'=>"GESTIÓN COMPLETA"),
    'how_tagline' => array('it'=>"Un solo referente • Un solo preventivo • Risposta rapida",'en'=>"One point of contact • One quote • Fast reply",'fr'=>"Un seul interlocuteur • Un seul devis • Réponse rapide",'es'=>"Un único interlocutor • Un único presupuesto • Respuesta rápida"),
    'cov_eyebrow' => array('it'=>"Dove operiamo",'en'=>"Where we operate",'fr'=>"Où nous intervenons",'es'=>"Dónde operamos"),
    'cov_heading' => array('it'=>"Milano, Roma, Torino, Parigi",'en'=>"Milan, Rome, Turin, Paris",'fr'=>"Milan, Rome, Turin, Paris",'es'=>"Milán, Roma, Turín, París"),
    'cov_sub' => array(
        'it' => "E altre città su richiesta, in Italia e in Europa.",
        'en' => "And other cities on request, across Italy and Europe.",
        'fr' => "Et d'autres villes sur demande, en Italie et en Europe.",
        'es' => "Y otras ciudades bajo petición, en Italia y en Europa.",
    ),
);

// Servizi: ordine deciso da Marco (14/10/2026): Staff, Fiere, Foto e video, Crew tecnica, Logistica.
$services = array(
  array(
    'title' => array('it'=>"Event Staff",'en'=>"Event Staff",'fr'=>"Personnel événementiel",'es'=>"Personal para eventos"),
    'text'  => array(
        'it'=>"Accoglienza, accrediti, promozione e coordinamento: le persone che stanno davanti ai tuoi ospiti, guidate da un unico referente.",
        'en'=>"Front-of-house, accreditation, promotion and coordination: the people your guests meet first, led by a single point of contact.",
        'fr'=>"Accueil, accréditations, promotion et coordination : les personnes qui accueillent vos invités, pilotées par un seul interlocuteur.",
        'es'=>"Recepción, acreditaciones, promoción y coordinación: las personas que reciben a tus invitados, dirigidas por un único interlocutor."),
    'chips' => array(
        array('it'=>"Hostess",'en'=>"Hostesses",'fr'=>"Hôtesses",'es'=>"Azafatas"),
        array('it'=>"Steward",'en'=>"Stewards",'fr'=>"Stewards",'es'=>"Stewards"),
        array('it'=>"Promoter",'en'=>"Promoters",'fr'=>"Promoteurs",'es'=>"Promotores"),
        array('it'=>"Receptionist",'en'=>"Receptionists",'fr'=>"Réceptionnistes",'es'=>"Recepcionistas"),
        array('it'=>"Addetti agli accrediti",'en'=>"Accreditation staff",'fr'=>"Agents d'accréditation",'es'=>"Personal de acreditaciones"),
        array('it'=>"Coordinatori e team leader",'en'=>"Coordinators and team leaders",'fr'=>"Coordinateurs et chefs d'équipe",'es'=>"Coordinadores y jefes de equipo"),
        array('it'=>"Brand ambassador",'en'=>"Brand ambassadors",'fr'=>"Ambassadeurs de marque",'es'=>"Embajadores de marca"),
        array('it'=>"Interpreti",'en'=>"Interpreters",'fr'=>"Interprètes",'es'=>"Intérpretes"),
    ),
    'note'  => array(
        'it'=>"Interpreti su richiesta, in base a lingua e disponibilità.",
        'en'=>"Interpreters on request, depending on language and availability.",
        'fr'=>"Interprètes sur demande, selon la langue et la disponibilité.",
        'es'=>"Intérpretes bajo petición, según idioma y disponibilidad."),
  ),
  array(
    'title' => array('it'=>"Fiere e saloni",'en'=>"Trade shows",'fr'=>"Salons professionnels",'es'=>"Ferias y salones"),
    'text'  => array(
        'it'=>"Personale per stand e padiglioni: accoglienza dei visitatori, assistenza agli espositori e supporto allo stand, anche multilingue.",
        'en'=>"Staff for booths and exhibition halls: visitor reception, exhibitor assistance and stand support, including multilingual staff.",
        'fr'=>"Du personnel pour stands et halls d'exposition : accueil des visiteurs, assistance aux exposants et soutien sur le stand, y compris multilingue.",
        'es'=>"Personal para stands y pabellones: recepción de visitantes, asistencia a expositores y apoyo en el stand, también multilingüe."),
    'chips' => array(
        array('it'=>"Hostess di fiera",'en'=>"Trade show hostesses",'fr'=>"Hôtesses de salon",'es'=>"Azafatas de feria"),
        array('it'=>"Personale di stand",'en'=>"Booth staff",'fr'=>"Personnel de stand",'es'=>"Personal de stand"),
        array('it'=>"Assistenza espositori",'en'=>"Exhibitor assistance",'fr'=>"Assistance exposants",'es'=>"Asistencia a expositores"),
        array('it'=>"Supporto stand",'en'=>"Stand support",'fr'=>"Soutien sur le stand",'es'=>"Apoyo en el stand"),
        array('it'=>"Staff multilingue",'en'=>"Multilingual staff",'fr'=>"Personnel multilingue",'es'=>"Personal multilingüe"),
    ),
    'note'  => null,
  ),
  array(
    'title' => array('it'=>"Foto e video",'en'=>"Photo and video",'fr'=>"Photo et vidéo",'es'=>"Foto y vídeo"),
    'text'  => array(
        'it'=>"Fotografi, videomaker e content creator per raccontare l'evento: foto, video, backstage e contenuti per i social.",
        'en'=>"Photographers, videographers and content creators to capture your event: photo, video, backstage and social media content.",
        'fr'=>"Photographes, vidéastes et créateurs de contenu pour raconter votre événement : photo, vidéo, coulisses et contenus pour les réseaux sociaux.",
        'es'=>"Fotógrafos, videógrafos y creadores de contenido para contar tu evento: foto, vídeo, backstage y contenidos para redes sociales."),
    'chips' => array(
        array('it'=>"Fotografi",'en'=>"Photographers",'fr'=>"Photographes",'es'=>"Fotógrafos"),
        array('it'=>"Videomaker",'en'=>"Videographers",'fr'=>"Vidéastes",'es'=>"Videógrafos"),
        array('it'=>"Content creator",'en'=>"Content creators",'fr'=>"Créateurs de contenu",'es'=>"Creadores de contenido"),
        array('it'=>"Backstage",'en'=>"Backstage",'fr'=>"Coulisses",'es'=>"Backstage"),
        array('it'=>"Contenuti social",'en'=>"Social content",'fr'=>"Contenus réseaux sociaux",'es'=>"Contenidos para redes"),
    ),
    'note'  => null,
  ),
  array(
    'title' => array('it'=>"Crew tecnica",'en'=>"Technical crew",'fr'=>"Équipes techniques",'es'=>"Equipo técnico"),
    'text'  => array(
        'it'=>"Squadre di supporto alla produzione, dal backstage all'allestimento: movimentazione dei materiali, assistenza e supporto tecnico.",
        'en'=>"Production support teams, from backstage to set-up: materials handling, assistance and technical support.",
        'fr'=>"Équipes de soutien à la production, des coulisses au montage : manutention du matériel, assistance et support technique.",
        'es'=>"Equipos de apoyo a la producción, del backstage al montaje: movimiento de materiales, asistencia y soporte técnico."),
    'chips' => array(
        array('it'=>"Runner",'en'=>"Runners",'fr'=>"Runners",'es'=>"Runners"),
        array('it'=>"Assistenti di produzione",'en'=>"Production assistants",'fr'=>"Assistants de production",'es'=>"Asistentes de producción"),
        array('it'=>"Backstage",'en'=>"Backstage",'fr'=>"Coulisses",'es'=>"Backstage"),
        array('it'=>"Movimentazione materiali",'en'=>"Materials handling",'fr'=>"Manutention",'es'=>"Movimiento de materiales"),
        array('it'=>"Allestimenti",'en'=>"Set-up crew",'fr'=>"Montage et installation",'es'=>"Montajes"),
        array('it'=>"Assistenti tecnici",'en'=>"Technical assistants",'fr'=>"Assistants techniques",'es'=>"Asistentes técnicos"),
        array('it'=>"Security",'en'=>"Security",'fr'=>"Sécurité",'es'=>"Seguridad"),
    ),
    'note'  => array(
        'it'=>"Mansioni tecniche e security solo con personale in regola con le qualifiche e i requisiti di legge.",
        'en'=>"Technical roles and security are only assigned to staff who meet the required qualifications and legal requirements.",
        'fr'=>"Les missions techniques et la sécurité sont confiées uniquement à du personnel en règle avec les qualifications et les exigences légales.",
        'es'=>"Las tareas técnicas y de seguridad solo se asignan a personal que cumple las cualificaciones y los requisitos legales."),
  ),
  array(
    'title' => array('it'=>"Logistica e trasporti",'en'=>"Logistics and transport",'fr'=>"Logistique et transport",'es'=>"Logística y transporte"),
    'text'  => array(
        'it'=>"Ritiri, consegne, trasporto e deposito per il tuo evento, organizzati da noi insieme ai partner del settore.",
        'en'=>"Pick-ups, deliveries, transport and storage for your event, organised by us together with industry partners.",
        'fr'=>"Enlèvements, livraisons, transport et stockage pour votre événement, organisés par nos soins avec des partenaires du secteur.",
        'es'=>"Recogidas, entregas, transporte y almacenaje para tu evento, organizados por nosotros junto con partners del sector."),
    'chips' => array(
        array('it'=>"Ritiri",'en'=>"Pick-ups",'fr'=>"Enlèvements",'es'=>"Recogidas"),
        array('it'=>"Consegne",'en'=>"Deliveries",'fr'=>"Livraisons",'es'=>"Entregas"),
        array('it'=>"Trasporto",'en'=>"Transport",'fr'=>"Transport",'es'=>"Transporte"),
        array('it'=>"Deposito",'en'=>"Storage",'fr'=>"Stockage",'es'=>"Almacenaje"),
    ),
    'note'  => array(
        'it'=>"Il trasporto di merci è eseguito solo da partner con licenza di autotrasporto per conto terzi.",
        'en'=>"Goods are transported only by partners holding a for-hire road haulage licence.",
        'fr'=>"Le transport de marchandises est assuré uniquement par des partenaires titulaires d'une licence de transport routier pour compte d'autrui.",
        'es'=>"El transporte de mercancías lo realizan únicamente partners con licencia de transporte por carretera por cuenta ajena."),
  ),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'TOA EVENTS',
    'title'      => _t_raw($t['hero_title']),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Hero CTA centrato -->
<div class="container hero-cta-wrap">
    <a href="#preventivo" class="toa-hero-cta">
        <span><?php echo $_t($t['hero_cta']); ?></span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.9rem;color:var(--gray-4);margin-top:16px;font-weight:600"><?php echo $_t($t['hero_line']); ?></p>
</div>

<style>
.toa-hero-cta{display:inline-flex;align-items:center;gap:10px;padding:16px 38px;background:var(--accent,#c8ff00);color:#0a0a0a;font-size:.95rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;box-shadow:0 8px 30px rgba(200,255,0,.35),0 2px 8px rgba(0,0,0,.15);transition:transform .25s ease,box-shadow .25s ease}
.toa-hero-cta svg{transition:transform .25s ease}
.toa-hero-cta:hover{transform:translateY(-3px);box-shadow:0 14px 42px rgba(200,255,0,.55),0 4px 12px rgba(0,0,0,.2)}
.toa-hero-cta:hover svg{transform:translateX(4px)}
@media(max-width:600px){.toa-hero-cta{padding:16px 30px;font-size:.95rem}}
.hero-cta-wrap{margin-top:16px;margin-bottom:28px;text-align:center}
@media(max-width:768px){.hero-cta-wrap{margin-top:18px;margin-bottom:20px}}
/* Card servizi */
.toa-ev-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:18px;margin-top:28px}
.toa-ev-card{border:1px solid rgba(150,150,150,.3);border-radius:14px;padding:26px;display:flex;flex-direction:column;gap:12px}
.toa-ev-num{font-family:var(--font-display);font-size:.8rem;font-weight:800;letter-spacing:1.5px;color:var(--accent)}
.toa-ev-card h3{font-family:var(--font-display);font-size:1.25rem;font-weight:800;text-transform:uppercase;margin:0}
.toa-ev-card p{margin:0;color:var(--gray-4);font-size:.95rem;line-height:1.6}
.toa-ev-chips{display:flex;flex-wrap:wrap;gap:6px}
.toa-ev-chips span{display:inline-block;padding:6px 13px;border:1px solid rgba(150,150,150,.35);border-radius:999px;font-size:.8rem;font-weight:600}
.toa-ev-card p.toa-ev-note{font-size:.82rem;border-left:2px solid var(--accent);padding-left:10px}
.toa-ev-card a.toa-ev-link{margin-top:auto;padding-top:6px;color:var(--accent);font-size:.82rem;font-weight:700;letter-spacing:.3px;text-transform:uppercase;text-decoration:none}
.toa-ev-other{text-align:center;margin:30px auto 0;max-width:640px;color:var(--gray-4);font-size:.95rem}
/* Garanzie compatte */
.features-grid .feature-card{padding:18px 26px!important}
.features-grid .feature-number{font-size:1.55rem!important;line-height:1!important;margin-bottom:6px!important}
.features-grid .feature-title{margin-bottom:4px!important}
.features-grid .feature-text{margin:0!important}
@media(max-width:600px){.features-grid .feature-card{padding:16px 20px!important}}
/* Barra CTA fissa solo su mobile */
.toa-mobile-cta{display:none}
@media(max-width:768px){
  .toa-mobile-cta{position:fixed;left:0;right:0;bottom:0;z-index:900;display:block;padding:10px 14px;padding-bottom:calc(10px + env(safe-area-inset-bottom));background:rgba(10,10,10,.92);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px);border-top:1px solid rgba(255,255,255,.08);transition:transform .3s ease}
  .toa-mobile-cta.hide{transform:translateY(130%)}
  .toa-mobile-cta a{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:15px 20px;background:var(--accent,#c8ff00);color:#0a0a0a;font-weight:700;font-size:.95rem;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;text-decoration:none}
}
/* Gallery */
.toa-gallery{background:#0a0a0a;padding:40px 20px 48px}
.toa-gallery .inner{max-width:1080px;margin:0 auto}
.toa-gallery .lab{text-align:center;font-size:12px;letter-spacing:3px;text-transform:uppercase;color:#777;margin-bottom:20px}
.toa-gallery .grid{display:flex;flex-wrap:wrap;justify-content:center;gap:6px}
.toa-gallery .grid img{width:calc(10% - 6px);aspect-ratio:1;object-fit:cover;border-radius:6px;background:#161616;opacity:.7;transition:opacity .2s}
.toa-gallery .grid img:hover{opacity:1}
@media(max-width:768px){.toa-gallery .grid{gap:5px}.toa-gallery .grid img{width:calc(20% - 5px)}}
</style>

<!-- Solo aziende: anti-candidature -->
<section style="padding:26px 0;background:var(--black);color:#fff;text-align:center">
    <div class="container">
        <p style="font-size:0.95rem;margin-bottom:10px;opacity:.85"><?php echo $_t($t['b2bonly']); ?></p>
        <a href="<?php echo home_url('/registrati-talent/'); ?>" style="display:inline-block;font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;color:var(--accent);text-decoration:none"><?php echo $_t($t['talentexit']); ?> &rarr;</a>
    </div>
</section>

<!-- Servizi -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['serv_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['serv_heading']); ?></h2>
        <div class="toa-ev-grid">
            <?php foreach ($services as $i => $s): ?>
            <div class="toa-ev-card">
                <div class="toa-ev-num"><?php echo sprintf('%02d', $i + 1); ?></div>
                <h3><?php echo $_t($s['title']); ?></h3>
                <p><?php echo $_t($s['text']); ?></p>
                <div class="toa-ev-chips">
                    <?php foreach ($s['chips'] as $c): ?><span><?php echo $_t($c); ?></span><?php endforeach; ?>
                </div>
                <?php if (!empty($s['note'])): ?><p class="toa-ev-note"><?php echo $_t($s['note']); ?></p><?php endif; ?>
                <a class="toa-ev-link" href="#preventivo"><?php echo $_t($t['serv_cta']); ?> &rarr;</a>
            </div>
            <?php endforeach; ?>
        </div>
        <p class="toa-ev-other"><?php echo $_t($t['serv_other']); ?></p>
    </div>
</section>

<!-- Come lavoriamo -->
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
        array('time' => "01", 'label' => $_t($t['how_step1'])),
        array('time' => "02", 'label' => $_t($t['how_step2'])),
        array('time' => "03", 'label' => $_t($t['how_step3'])),
        array('time' => "04", 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<?php toa_component('google-reviews'); ?>

<!-- Dove operiamo -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['cov_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:12px"><?php echo $_t($t['cov_heading']); ?></h2>
        <p style="font-size:0.95rem;color:var(--gray-4);max-width:640px;margin:0 0 20px"><?php echo $_t($t['cov_sub']); ?></p>
    </div>
</section>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events')); ?>

<!-- Gallery (foto eventi, discreta) -->
<section class="toa-gallery"><div class="inner">
    <div class="lab">Gallery</div>
    <div class="grid">
        <?php for($gi=1;$gi<=37;$gi++): $gn=sprintf('g%02d',$gi); ?>
        <img src="/wp-content/themes/toagency-theme/assets/gallery/<?php echo $gn; ?>.jpg" alt="TOA Events" loading="lazy">
        <?php endfor; ?>
    </div>
</div></section>

<!-- Barra CTA fissa mobile -->
<div class="toa-mobile-cta" id="toaMobileCta">
    <a href="#preventivo"><?php echo $_t($t['hero_cta']); ?>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
</div>
<script>
(function(){
  var bar=document.getElementById('toaMobileCta');
  var form=document.getElementById('preventivo');
  if(!bar||!form||!('IntersectionObserver' in window))return;
  new IntersectionObserver(function(entries){
    entries.forEach(function(e){ bar.classList.toggle('hide', e.isIntersecting); });
  },{threshold:0}).observe(form);
})();
</script>

<?php toa_component('footer'); ?>
