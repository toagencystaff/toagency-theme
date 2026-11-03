<?php
/**
 * Dati TOA Events (#637, 15/10/2026) — IT/EN/FR/ES. Unica fonte per la pagina /events/, le 6 sottopagine servizio e le pagine città.
 * Ogni servizio: title (nome breve), lead (origine lead nel CRM), h1, intro, roles, note legale.
 * 03/11/2026 (ticket #811, chat TEMA-Events): le figure arrivano SOLO dal catalogo components/events-catalog.php
 * (= ruoli veri del CRM). Tolte le figure che TOAgency non ha (sommelier, truccabimbi, ballerini, allestitori, movimentazione,
 * parcheggiatori, trasporto merci, brand ambassador, coordinatori...). Le sottopagine restano agli stessi URL e con gli stessi H1.
 * Per aggiungere una figura: prima il ruolo nel catalogo, poi il suo codice nella lista 'roles' del servizio.
 * Vincoli: nessun prezzo, nessuna promessa di numeri/copertura; security e mansioni tecniche solo con personale in regola;
 * trasporto merci solo con partner con licenza autotrasporto c/terzi.
 */
$L = function($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };
$CAT = include __DIR__ . '/events-catalog.php';
$R = function($codes) use ($CAT) {
  $o = array();
  foreach ($codes as $c) { if (isset($CAT['roles'][$c])) $o[] = $CAT['roles'][$c]; }
  return $o;
};
// la foto di fiera per la hostess nella sottopagina Fiere (stesso ruolo, scatto diverso)
$hostessFiera = $CAT['roles']['hostess']; $hostessFiera['p'] = 'hostess-fiera';
$hostessFiera['n'] = $L("Hostess di fiera","Trade show hostesses","Hôtesses de salon","Azafatas de feria");
$hostessFiera['d'] = $L("Accoglienza ai visitatori allo stand e in padiglione.","Visitor welcome at the booth and in the hall.","Accueil des visiteurs sur le stand et dans le hall.","Recepción de visitantes en el stand y en el pabellón.");

return array(

'staff' => array(
  'title' => $L("Event Staff", "Event Staff", "Personnel événementiel", "Personal para eventos"),
  'lead'  => 'Staff',
  'h1'    => $L("Staff per eventi: hostess, steward e promoter", "Event staff: hostesses, stewards and promoters", "Personnel événementiel : hôtesses, stewards et promoteurs", "Personal para eventos: azafatas, stewards y promotores"),
  'intro' => $L("Le persone che stanno davanti ai tuoi ospiti: accoglienza, accrediti, promozione e accompagnamento, guidate da un unico referente.", "The people your guests meet first: front-of-house, accreditation, promotion and guest support, led by a single point of contact.", "Les personnes qui accueillent vos invités : accueil, accréditations, promotion et accompagnement, pilotés par un seul interlocuteur.", "Las personas que reciben a tus invitados: recepción, acreditaciones, promoción y acompañamiento, dirigidas por un único interlocutor."),
  'roles' => $R(array('hostess','steward','promoter','interprete','tour_leader')),
  'note'  => null,
),

'fiere' => array(
  'title' => $L("Fiere e saloni", "Trade shows", "Salons professionnels", "Ferias y salones"),
  'lead'  => 'Fiere',
  'h1'    => $L("Personale per fiere e saloni", "Staff for trade shows and exhibitions", "Personnel pour salons et foires professionnels", "Personal para ferias y salones"),
  'intro' => $L("Personale per stand e padiglioni: accoglienza dei visitatori e presenza allo stand, anche multilingue.", "Staff for booths and exhibition halls: visitor reception and stand presence, including multilingual staff.", "Du personnel pour stands et halls d'exposition : accueil des visiteurs et présence sur le stand, y compris multilingue.", "Personal para stands y pabellones: recepción de visitantes y presencia en el stand, también multilingüe."),
  'roles' => array_merge(array($hostessFiera), $R(array('steward','promoter','interprete','runner','bartender','cameriere','fotografo','videomaker','content_creator'))),
  'note'  => null,
),

'bar-spettacolo' => array(
  'title' => $L("Bar, sala e spettacolo", "Bar, service and entertainment", "Bar, salle et spectacle", "Bar, sala y espectáculo"),
  'lead'  => 'Bar sala spettacolo',
  'h1'    => $L("Bar, sala e spettacolo per eventi", "Bar, service and entertainment for events", "Bar, salle et spectacle pour vos événements", "Bar, sala y espectáculo para eventos"),
  'intro' => $L("Chi serve e chi intrattiene: bartender, camerieri, DJ, cantanti e intrattenitori per far vivere la serata ai tuoi ospiti.", "Those who serve and those who entertain: bartenders, waiters, DJs, singers and entertainers to bring the night to life for your guests.", "Ceux qui servent et ceux qui animent : barmans, serveurs, DJ, chanteurs et animateurs pour faire vivre la soirée à vos invités.", "Quienes sirven y quienes animan: bartenders, camareros, DJ, cantantes y animadores para que tus invitados vivan la noche."),
  'roles' => $R(array('bartender','cameriere','dj','cantante','intrattenitore')),
  'note'  => null,
),

'foto-video' => array(
  'title' => $L("Foto e video", "Photo and video", "Photo et vidéo", "Foto y vídeo"),
  'lead'  => 'Foto e video',
  'h1'    => $L("Foto, video e immagine per eventi", "Photo, video and image for events", "Photo, vidéo et image pour vos événements", "Foto, vídeo e imagen para eventos"),
  'intro' => $L("Chi racconta l'evento e chi cura l'immagine: fotografi, videomaker, content creator, social media manager, make-up artist e hair stylist.", "Those who capture the event and look after its image: photographers, videographers, content creators, social media managers, make-up artists and hair stylists.", "Ceux qui racontent l'événement et soignent son image : photographes, vidéastes, créateurs de contenu, social media managers, maquilleurs et coiffeurs.", "Quienes cuentan el evento y cuidan su imagen: fotógrafos, videógrafos, creadores de contenido, social media managers, maquilladores y peluqueros."),
  'roles' => $R(array('fotografo','videomaker','content_creator','social_media','makeup_artist','hairstylist')),
  'note'  => null,
),

'crew' => array(
  'title' => $L("Crew tecnica", "Technical crew", "Équipes techniques", "Equipo técnico"),
  'lead'  => 'Crew tecnica',
  'h1'    => $L("Crew tecnica e produzione per eventi", "Technical crew and production for events", "Équipes techniques et production pour événements", "Equipo técnico y producción para eventos"),
  'intro' => $L("Supporto alla produzione, dal palco al backstage: tecnici luci e suono, runner, assistenti di produzione e controllo accessi.", "Production support, from the stage to backstage: lighting and sound technicians, runners, production assistants and access control.", "Soutien à la production, de la scène aux coulisses : techniciens lumière et son, runners, assistants de production et contrôle d'accès.", "Apoyo a la producción, del escenario al backstage: técnicos de iluminación y sonido, runners, asistentes de producción y control de accesos."),
  'roles' => $R(array('tecnico_luci','tecnico_suono','runner','assistente_produzione','security')),
  'note'  => $L("Mansioni tecniche e di controllo accessi solo con personale in regola con le qualifiche e i requisiti di legge; oltre i limiti dello steward, solo con operatori autorizzati, partner di TOA.", "Technical and access-control roles only with staff who meet the required qualifications and legal requirements; beyond what a steward may do, only licensed operators who partner with TOA.", "Missions techniques et de contrôle d'accès uniquement avec du personnel en règle avec les qualifications et les exigences légales ; au-delà du rôle de steward, uniquement des opérateurs agréés, partenaires de TOA.", "Tareas técnicas y de control de accesos solo con personal que cumple las cualificaciones y los requisitos legales; más allá de lo que puede hacer un steward, solo operadores autorizados, socios de TOA."),
),

'logistica' => array(
  'title' => $L("Logistica e trasporti", "Logistics and transport", "Logistique et transport", "Logística y transporte"),
  'lead'  => 'Logistica',
  'h1'    => $L("Logistica e trasporti per eventi", "Logistics and transport for events", "Logistique et transport pour événements", "Logística y transporte para eventos"),
  'intro' => $L("Autisti per i transfer di ospiti e staff e runner per commissioni e piccole consegne durante l'evento.", "Drivers for guest and staff transfers, and runners for errands and small deliveries during the event.", "Des chauffeurs pour les transferts des invités et de l'équipe, et des runners pour les courses et petites livraisons pendant l'événement.", "Conductores para los traslados de invitados y personal, y runners para recados y pequeñas entregas durante el evento."),
  'roles' => $R(array('autista','runner')),
  'note'  => $L("Il trasporto di merci e allestimenti è eseguito solo da partner con licenza di autotrasporto per conto terzi.", "Goods and set-ups are transported only by partners holding a for-hire road haulage licence.", "Le transport de marchandises et d'installations est assuré uniquement par des partenaires titulaires d'une licence de transport routier pour compte d'autrui.", "El transporte de mercancías y montajes lo realizan únicamente partners con licencia de transporte por carretera por cuenta ajena."),
),

);
