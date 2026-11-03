<?php
/**
 * Catalogo ruoli TOA Events (ticket #811, 03/11/2026, chat TEMA-Events) — IT/EN/FR/ES.
 * SOLO i ruoli che TOAgency ha davvero (catalogo CRM, lib/ruoli.php del CRM): Marco 03/11 «le figure devono corrispondere ai ruoli che abbiamo».
 * Nel CRM hostess/steward/promoter sono un solo ruolo: qui restano tre voci solo per spiegarle al cliente.
 * Usato da: components/events-data.php (sottopagine servizio), «Tutti i servizi», home /events/.
 * Ogni ruolo: n (nome), d (descrizione), p (foto assets/staff/<p>.jpg, '' = nessuna foto; una foto per un solo ruolo, ticket #790).
 * Security a due livelli (Marco 20/10): steward nostri con attenzione alla sicurezza entro i limiti di legge; il resto solo con operatori autorizzati.
 */
$L = function($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };

$roles = array(
  'hostess' => array('n' => $L("Hostess","Hostesses","Hôtesses","Azafatas"),
    'd' => $L("Accoglienza e orientamento degli ospiti per tutto l'evento.","Guest welcome and orientation throughout the event.","Accueil et orientation des invités pendant tout l'événement.","Recepción y orientación de los invitados durante todo el evento."), 'p' => 'hostess'),
  'steward' => array('n' => $L("Steward","Stewards","Stewards","Stewards"),
    'd' => $L("Gestione dei flussi di persone, indicazioni al pubblico e attenzione alla sicurezza, entro i limiti di legge.","People flow management, guidance for the audience and a watchful eye on safety, within legal limits.","Gestion des flux de personnes, orientation du public et vigilance en matière de sécurité, dans le cadre légal.","Gestión de los flujos de personas, indicaciones al público y atención a la seguridad, dentro de los límites legales."), 'p' => 'steward'),
  'promoter' => array('n' => $L("Promoter","Promoters","Promoteurs","Promotores"),
    'd' => $L("Promozione, sampling e volantinaggio, a diretto contatto con il pubblico.","Promotion, sampling and flyering, face to face with the public.","Promotion, échantillonnage et distribution de flyers, au contact direct du public.","Promoción, sampling y reparto de folletos, en contacto directo con el público."), 'p' => 'promoter'),
  'interprete' => array('n' => $L("Interpreti","Interpreters","Interprètes","Intérpretes"),
    'd' => $L("Per stand internazionali, delegazioni e congressi, in base a lingua e disponibilità.","For international stands, delegations and conferences, depending on language and availability.","Pour les stands internationaux, les délégations et les congrès, selon la langue et les disponibilités.","Para stands internacionales, delegaciones y congresos, según idioma y disponibilidad."), 'p' => 'interprete'),
  'tour_leader' => array('n' => $L("Tour leader","Tour leaders","Accompagnateurs","Guías acompañantes"),
    'd' => $L("Accompagnano gruppi di ospiti in visite, transfer e viaggi incentive.","They look after groups of guests on visits, transfers and incentive trips.","Ils accompagnent des groupes d'invités lors de visites, transferts et voyages incentive.","Acompañan a grupos de invitados en visitas, traslados y viajes de incentivo."), 'p' => 'accoglienza'),
  'bartender' => array('n' => $L("Bartender","Bartenders","Barmans","Bartenders"),
    'd' => $L("Servizio bar, cocktail e caffetteria per ricevimenti, aperitivi e stand.","Bar, cocktail and coffee service for receptions, aperitifs and stands.","Service de bar, cocktails et café pour réceptions, apéritifs et stands.","Servicio de bar, cócteles y café para recepciones, aperitivos y stands."), 'p' => 'bartender'),
  'cameriere' => array('n' => $L("Camerieri","Waiters","Serveurs","Camareros"),
    'd' => $L("Servizio in sala, buffet e catering, con una divisa adatta all'evento.","Table, buffet and catering service, in a uniform that suits the event.","Service en salle, buffet et traiteur, avec une tenue adaptée à l'événement.","Servicio en sala, buffet y catering, con un uniforme acorde al evento."), 'p' => 'cameriera'),
  'dj' => array('n' => $L("DJ","DJs","DJ","DJ"),
    'd' => $L("Musica e set per aperitivi, feste e serate aziendali.","Music and sets for aperitifs, parties and corporate nights.","Musique et sets pour apéritifs, fêtes et soirées d'entreprise.","Música y sets para aperitivos, fiestas y veladas de empresa."), 'p' => 'dj'),
  'cantante' => array('n' => $L("Cantanti","Singers","Chanteurs","Cantantes"),
    'd' => $L("Voci per cerimonie, cene di gala e serate.","Vocalists for ceremonies, gala dinners and evening events.","Des voix pour cérémonies, dîners de gala et soirées.","Voces para ceremonias, cenas de gala y veladas."), 'p' => 'musicista'),
  'intrattenitore' => array('n' => $L("Intrattenitori","Entertainers","Animateurs","Animadores"),
    'd' => $L("Animazione e spettacolo per feste, serate aziendali e giornate per le famiglie.","Entertainment for parties, corporate nights and family days.","Animation et spectacle pour fêtes, soirées d'entreprise et journées en famille.","Animación y espectáculo para fiestas, veladas de empresa y jornadas familiares."), 'p' => 'intrattenitore'),
  'security' => array('n' => $L("Steward di sicurezza e controllo accessi","Security stewards and access control","Agents de sécurité et contrôle d'accès","Personal de seguridad y control de accesos"),
    'd' => $L("Controllo di biglietti, accrediti e varchi. Oltre i limiti di legge dello steward, solo con operatori autorizzati, partner di TOA.","Ticket, badge and gate checks. Anything beyond what a steward may legally do is handled only by licensed operators who partner with TOA.","Contrôle des billets, accréditations et accès. Au-delà de ce qu'un steward peut faire légalement, uniquement avec des opérateurs agréés, partenaires de TOA.","Control de entradas, acreditaciones y accesos. Lo que va más allá de lo que un steward puede hacer por ley, solo con operadores autorizados, socios de TOA."), 'p' => 'security'),
  'tecnico_luci' => array('n' => $L("Tecnici luci","Lighting technicians","Techniciens lumière","Técnicos de iluminación"),
    'd' => $L("Luci per palchi, sale e allestimenti, con personale qualificato.","Lighting for stages, rooms and set-ups, with qualified staff.","Éclairage pour scènes, salles et installations, avec du personnel qualifié.","Iluminación para escenarios, salas y montajes, con personal cualificado."), 'p' => 'tecnico'),
  'tecnico_suono' => array('n' => $L("Tecnici del suono","Sound technicians","Techniciens son","Técnicos de sonido"),
    'd' => $L("Impianti audio e microfoni per palchi, conferenze e serate.","Sound systems and microphones for stages, conferences and evening events.","Sonorisation et micros pour scènes, conférences et soirées.","Equipos de sonido y micrófonos para escenarios, conferencias y veladas."), 'p' => ''),
  'runner' => array('n' => $L("Runner","Runners","Runners","Runners"),
    'd' => $L("Commissioni, piccole consegne e supporto veloce a staff e produzione.","Errands, small deliveries and quick support for staff and production.","Courses, petites livraisons et soutien rapide à l'équipe et à la production.","Recados, pequeñas entregas y apoyo rápido al personal y a producción."), 'p' => 'runner'),
  'assistente_produzione' => array('n' => $L("Assistenti di produzione","Production assistants","Assistants de production","Asistentes de producción"),
    'd' => $L("Organizzazione di tempi, materiali e persone sul set o in sala.","Organising schedules, materials and people on set or in the venue.","Organisation des horaires, du matériel et des personnes sur le plateau ou en salle.","Organización de tiempos, materiales y personas en el set o en la sala."), 'p' => 'assistente-produzione'),
  'autista' => array('n' => $L("Autisti","Drivers","Chauffeurs","Conductores"),
    'd' => $L("Transfer di ospiti e staff da e per l'evento.","Guest and staff transfers to and from the event.","Transferts des invités et de l'équipe vers et depuis l'événement.","Traslados de invitados y personal hacia y desde el evento."), 'p' => 'autista'),
  'fotografo' => array('n' => $L("Fotografi","Photographers","Photographes","Fotógrafos"),
    'd' => $L("Foto dell'evento, ritratti e immagini per comunicazione e social.","Event photos, portraits and images for communication and social media.","Photos de l'événement, portraits et images pour la communication et les réseaux.","Fotos del evento, retratos e imágenes para comunicación y redes."), 'p' => 'fotografa'),
  'videomaker' => array('n' => $L("Videomaker","Videographers","Vidéastes","Videógrafos"),
    'd' => $L("Riprese e montaggio di video, reel e aftermovie dell'evento.","Filming and editing of event videos, reels and aftermovies.","Tournage et montage de vidéos, reels et aftermovies de l'événement.","Grabación y edición de vídeos, reels y aftermovies del evento."), 'p' => 'videomaker'),
  'content_creator' => array('n' => $L("Content creator","Content creators","Créateurs de contenu","Creadores de contenido"),
    'd' => $L("Video e storie dello stand e dell'evento, pronti per i tuoi social.","Videos and stories from your stand and event, ready for your social media.","Vidéos et stories de votre stand et de l'événement, prêtes pour vos réseaux.","Vídeos y stories de tu stand y del evento, listos para tus redes."), 'p' => 'content-creator'),
  'social_media' => array('n' => $L("Social media manager","Social media managers","Social media managers","Social media managers"),
    'd' => $L("Pubblicazione in tempo reale sui tuoi canali durante l'evento.","Live posting on your channels during the event.","Publication en direct sur vos réseaux pendant l'événement.","Publicación en directo en tus canales durante el evento."), 'p' => ''),
  'makeup_artist' => array('n' => $L("Make-up artist","Make-up artists","Maquilleurs","Maquilladores"),
    'd' => $L("Trucco per staff, ospiti e persone sul palco.","Make-up for staff, guests and people on stage.","Maquillage pour l'équipe, les invités et les personnes sur scène.","Maquillaje para el personal, los invitados y las personas en el escenario."), 'p' => 'makeup'),
  'hairstylist' => array('n' => $L("Hair stylist","Hair stylists","Coiffeurs","Peluqueros"),
    'd' => $L("Acconciature per staff, ospiti e set.","Hairstyling for staff, guests and sets.","Coiffure pour l'équipe, les invités et les plateaux.","Peinados para el personal, los invitados y los rodajes."), 'p' => 'hairstylist'),
);

// I 6 gruppi di «Tutti i servizi» (bozza approvata 03/11). 'svc' = sottopagina servizio esistente (toa_events_pages) per «Scopri».
$groups = array(
  'accoglienza' => array('t' => $L("Accoglienza e promozione","Welcome and promotion","Accueil et promotion","Recepción y promoción"),
    'd' => $L("Le persone che accolgono i tuoi ospiti e presentano il tuo marchio.","The people who welcome your guests and present your brand.","Les personnes qui accueillent vos invités et présentent votre marque.","Las personas que reciben a tus invitados y presentan tu marca."),
    'r' => array('hostess','steward','promoter','interprete','tour_leader'), 'svc' => 'staff', 'p' => 'hostess-fiera'),
  'bar' => array('t' => $L("Bar e sala","Bar and service","Bar et salle","Bar y sala"),
    'd' => $L("Servizio bar, aperitivi e sala per eventi e stand.","Bar, aperitifs and table service for events and stands.","Service de bar, apéritifs et salle pour événements et stands.","Servicio de bar, aperitivos y sala para eventos y stands."),
    'r' => array('bartender','cameriere'), 'svc' => 'bar-spettacolo', 'p' => 'bartender'),
  'musica' => array('t' => $L("Musica e intrattenimento","Music and entertainment","Musique et animation","Música y animación"),
    'd' => $L("Chi fa vivere la serata ai tuoi ospiti.","The people who bring the night to life for your guests.","Ceux qui font vivre la soirée à vos invités.","Quienes hacen vivir la noche a tus invitados."),
    'r' => array('dj','cantante','intrattenitore'), 'svc' => 'bar-spettacolo', 'p' => 'dj'),
  'foto' => array('t' => $L("Foto, video e contenuti","Photo, video and content","Photo, vidéo et contenus","Foto, vídeo y contenidos"),
    'd' => $L("Chi racconta l'evento e ne cura l'immagine.","The people who tell the story of your event and look after its image.","Ceux qui racontent l'événement et soignent son image.","Quienes cuentan el evento y cuidan su imagen."),
    'r' => array('fotografo','videomaker','content_creator','social_media','makeup_artist','hairstylist'), 'svc' => 'foto-video', 'p' => 'fotografa'),
  'tecnica' => array('t' => $L("Tecnica e produzione","Technical and production","Technique et production","Técnica y producción"),
    'd' => $L("Supporto alla produzione, dal palco al backstage.","Production support, from the stage to backstage.","Soutien à la production, de la scène aux coulisses.","Apoyo a la producción, del escenario al backstage."),
    'r' => array('tecnico_luci','tecnico_suono','runner','assistente_produzione'), 'svc' => 'crew', 'p' => 'tecnico'),
  'accessi' => array('t' => $L("Accessi e trasporti","Access and transport","Accès et transports","Accesos y transporte"),
    'd' => $L("Varchi, accrediti e spostamenti degli ospiti.","Gates, badges and guest transfers.","Accès, accréditations et déplacements des invités.","Accesos, acreditaciones y traslados de los invitados."),
    'r' => array('security','autista'), 'svc' => 'logistica', 'p' => 'security'),
);

return array('roles' => $roles, 'groups' => $groups);
