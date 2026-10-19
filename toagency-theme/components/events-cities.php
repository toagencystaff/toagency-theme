<?php
/**
 * Dati pagine città TOA Events (#637, 19/10/2026) — IT/EN/FR/ES. Una voce per città; la pagina si sceglie col campo toa_ev_city.
 * Solo fatti verificabili (sedi e eventi già citati nelle pagine TOAgency di Torino). Nessun prezzo, nessuna cifra, nessuna promessa di copertura.
 * Sedi: 'own' = servizio TOA Events (chiave di events-data.php); 'link' = slug IT di una pagina/articolo già esistente (il link nelle altre lingue si ricava da WPML, mai a mano).
 */
$L = function($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };

return array(

'torino' => array(
  'slug'  => $L('torino', 'turin', 'turin', 'turin'),
  'name'  => $L('Torino', 'Turin', 'Turin', 'Turín'),
  'seo_title' => $L(
    "Staff per eventi a Torino: fiere, congressi, grandi eventi | TOAgency",
    "Event staff in Turin: trade shows, conferences, major events | TOAgency",
    "Personnel événementiel à Turin : salons, congrès, grands événements | TOAgency",
    "Personal para eventos en Turín: ferias, congresos, grandes eventos | TOAgency"),
  'seo_desc' => $L(
    "Hostess, steward, bar, foto e video, crew e logistica per eventi a Torino: Lingotto, OGR, PalaAlpitour. TOA Events, un referente, un preventivo.",
    "Hostesses, stewards, bar, photo and video, crew and logistics for events in Turin: Lingotto, OGR, PalaAlpitour. TOA Events: one contact, one quote.",
    "Hôtesses, stewards, bar, photo et vidéo, équipes et logistique pour événements à Turin : Lingotto, OGR, PalaAlpitour. TOA Events : un seul interlocuteur.",
    "Azafatas, stewards, bar, foto y vídeo, equipos y logística para eventos en Turín: Lingotto, OGR, PalaAlpitour. TOA Events: un interlocutor, un presupuesto."),
  'h1'    => $L("Staff e servizi per eventi a Torino", "Event staff and services in Turin", "Personnel et services pour événements à Turin", "Personal y servicios para eventos en Turín"),
  'intro' => $L(
    "Fiere, congressi e grandi eventi: a Torino lavoriamo con personale sul posto, guidato da un unico referente. Dalle hostess alla crew tecnica, un solo preventivo.",
    "Trade shows, conferences and major events: in Turin we work with staff on the ground, led by a single point of contact. From hostesses to technical crew, one quote.",
    "Salons, congrès et grands événements : à Turin nous travaillons avec du personnel sur place, dirigé par un seul interlocuteur. Des hôtesses aux équipes techniques, un seul devis.",
    "Ferias, congresos y grandes eventos: en Turín trabajamos con personal sobre el terreno, dirigido por un único interlocutor. De las azafatas al equipo técnico, un único presupuesto."),

  // Tariffa di partenza (regola commerciale: vale per Italia, Spagna e Portogallo; altri paesi si adeguano). IVA solo nel testo italiano.
  'price' => $L(
    "Hostess, steward e promoter a partire da €129 + IVA a persona al giorno. Altri profili su preventivo.",
    "Hostesses, stewards and promoters from €129 per person per day. Other profiles on request.",
    "Hôtesses, stewards et promoteurs à partir de 129 € par personne et par jour. Autres profils sur demande.",
    "Azafatas, stewards y promotores desde 129 € por persona y día. Otros perfiles bajo petición."),

  'v_eyebrow' => $L("Le sedi", "Venues", "Les lieux", "Las sedes"),
  'v_heading' => $L("Dove lavoriamo a Torino", "Where we work in Turin", "Où nous intervenons à Turin", "Dónde trabajamos en Turín"),
  'v_sub'     => $L("Alcune sedi ed eventi della città, con le pagine di approfondimento.", "Some of the city's venues and events, with in-depth pages.", "Quelques lieux et événements de la ville, avec les pages détaillées.", "Algunas sedes y eventos de la ciudad, con sus páginas de detalle."),
  'venues' => array(
    array(
      'n' => $L("Lingotto Fiere", "Lingotto Fiere", "Lingotto Fiere", "Lingotto Fiere"),
      'd' => $L("Il polo fieristico della città: fiere e saloni professionali, con stand e padiglioni da presidiare.",
                "The city's exhibition centre: trade fairs and professional shows, with booths and halls to staff.",
                "Le parc des expositions de la ville : foires et salons professionnels, avec des stands et des pavillons à animer.",
                "El recinto ferial de la ciudad: ferias y salones profesionales, con stands y pabellones que atender."),
      'own' => 'fiere',
      'l'   => $L("Personale per fiere e saloni", "Trade show staff", "Personnel pour salons", "Personal para ferias"),
    ),
    array(
      'n' => $L("Salone Auto Torino", "Turin Auto Show", "Salon de l'Auto de Turin", "Salón del Automóvil de Turín"),
      'd' => $L("Torino è la capitale storica dell'auto italiana: per chi espone servono accoglienza allo stand, presentazione dei veicoli e staff multilingue.",
                "Turin is the historic capital of Italian motoring: exhibitors need stand reception, vehicle presentation and multilingual staff.",
                "Turin est la capitale historique de l'automobile italienne : les exposants ont besoin d'accueil sur le stand, de présentation des véhicules et de personnel multilingue.",
                "Turín es la capital histórica del automóvil italiano: quien expone necesita recepción en el stand, presentación de vehículos y personal multilingüe."),
      'link' => 'hostess-salone-auto-torino',
      'l'    => $L("Hostess e staff per il Salone Auto", "Hostesses and staff for the Auto Show", "Hôtesses et personnel pour le Salon de l'Auto", "Azafatas y personal para el Salón del Automóvil"),
    ),
    array(
      'n' => $L("PalaAlpitour e le ATP Finals", "PalaAlpitour and the ATP Finals", "PalaAlpitour et les ATP Finals", "PalaAlpitour y las ATP Finals"),
      'd' => $L("Grandi eventi sportivi internazionali: hospitality, attivazioni dei brand, accoglienza di ospiti e media.",
                "Major international sporting events: hospitality, brand activations, guest and media reception.",
                "Grands événements sportifs internationaux : hospitalité, activations de marque, accueil des invités et des médias.",
                "Grandes eventos deportivos internacionales: hospitality, activaciones de marca, recepción de invitados y medios."),
      'link' => 'hostess-atp-finals-torino',
      'l'    => $L("Staff per le ATP Finals", "Staff for the ATP Finals", "Personnel pour les ATP Finals", "Personal para las ATP Finals"),
    ),
    array(
      'n' => $L("OGR Torino", "OGR Torino", "OGR Torino", "OGR Torino"),
      'd' => $L("Eventi corporate e culturali: accoglienza degli ospiti, registrazioni e assistenza in sala.",
                "Corporate and cultural events: guest reception, registration and room assistance.",
                "Événements d'entreprise et culturels : accueil des invités, enregistrements et assistance en salle.",
                "Eventos corporativos y culturales: recepción de invitados, registros y asistencia en sala."),
      'link' => 'hostess-eventi-ogr-torino',
      'l'    => $L("Staff per gli eventi alle OGR", "Staff for events at OGR", "Personnel pour les événements aux OGR", "Personal para eventos en las OGR"),
    ),
    array(
      'n' => $L("Centro Congressi Unione Industriale", "Centro Congressi Unione Industriale", "Centro Congressi Unione Industriale", "Centro Congressi Unione Industriale"),
      'd' => $L("Congressi e convention: accrediti, assistenza in sala, accoglienza di relatori e delegati.",
                "Conferences and conventions: accreditation, room assistance, speaker and delegate reception.",
                "Congrès et conventions : accréditations, assistance en salle, accueil des intervenants et des délégués.",
                "Congresos y convenciones: acreditaciones, asistencia en sala, recepción de ponentes y delegados."),
      'own' => 'staff',
      'l'   => $L("Staff per eventi e congressi", "Event and conference staff", "Personnel pour événements et congrès", "Personal para eventos y congresos"),
    ),
  ),

  's_eyebrow' => $L("I servizi", "Services", "Les services", "Los servicios"),
  's_heading' => $L("Tutto quello che serve per un evento a Torino", "Everything you need for an event in Turin", "Tout ce qu'il faut pour un événement à Turin", "Todo lo que necesita un evento en Turín"),

  'x_eyebrow' => $L("Approfondimenti", "More on Turin", "Pour aller plus loin", "Más información"),
  'x_hostess' => array(
    'link' => 'agenzia-hostess-torino',
    'n'    => $L("Agenzia hostess a Torino", "Hostess agency in Turin", "Agence d'hôtesses à Turin", "Agencia de azafatas en Turín"),
    'd'    => $L("La pagina TOAgency dedicata a hostess e steward in città: servizi, eventi e domande frequenti.",
                 "The TOAgency page dedicated to hostesses and stewards in the city: services, events and FAQ.",
                 "La page TOAgency consacrée aux hôtesses et stewards de la ville : services, événements et questions fréquentes.",
                 "La página de TOAgency dedicada a azafatas y stewards de la ciudad: servicios, eventos y preguntas frecuentes."),
  ),

  'f_eyebrow' => $L("Domande frequenti", "FAQ", "Questions fréquentes", "Preguntas frecuentes"),
  'f_heading' => $L("Eventi a Torino: le domande più comuni", "Events in Turin: common questions", "Événements à Turin : les questions courantes", "Eventos en Turín: las preguntas más comunes"),
  'faq' => array(
    array(
      'q' => $L("Per quali eventi a Torino fornite personale?", "Which events in Turin do you provide staff for?", "Pour quels événements à Turin fournissez-vous du personnel ?", "¿Para qué eventos en Turín proporcionáis personal?"),
      'a' => $L("Per fiere e saloni, congressi e convention, eventi aziendali e culturali, grandi eventi sportivi e presentazioni di prodotto. Dalla scelta delle figure alla gestione sul posto, ti segue un unico referente.",
                "For trade shows, conferences and conventions, corporate and cultural events, major sporting events and product launches. From choosing the roles to running things on site, a single contact looks after you.",
                "Pour les salons, congrès et conventions, événements d'entreprise et culturels, grands événements sportifs et lancements de produit. Du choix des profils à la gestion sur place, un seul interlocuteur vous suit.",
                "Para ferias y salones, congresos y convenciones, eventos corporativos y culturales, grandes eventos deportivos y presentaciones de producto. Desde la elección de los perfiles hasta la gestión en el lugar, te acompaña un único interlocutor."),
    ),
    array(
      'q' => $L("Quanto costa il personale per un evento a Torino?", "How much does event staff cost in Turin?", "Combien coûte le personnel pour un événement à Turin ?", "¿Cuánto cuesta el personal para un evento en Turín?"),
      'a' => $L("Hostess, steward, promoter e supporto fiera: a partire da €129 + IVA a persona al giorno. Per camerieri, barman e altri profili qualificati dipende da mansioni, durata e livello di autonomia richiesto, e con più budget la scelta di profili si allarga. Se hai un budget diverso, indicalo pure e vediamo se possiamo venirti incontro.",
                "Hostesses, stewards, promoters and trade show support: from €129 per person per day. For waiters, bartenders and other qualified profiles it depends on the tasks, the duration and the autonomy required, and a bigger budget widens the choice of profiles. If your budget is different, just tell us and we'll see how we can accommodate you.",
                "Hôtesses, stewards, promoteurs et renfort sur salon : à partir de 129 € par personne et par jour. Pour les serveurs, barmans et autres profils qualifiés, cela dépend des missions, de la durée et de l'autonomie demandée, et un budget plus important élargit le choix des profils. Si votre budget est différent, dites-le-nous et nous verrons comment vous accompagner.",
                "Azafatas, stewards, promotores y apoyo en feria: desde 129 € por persona y día. Para camareros, barmans y otros perfiles cualificados depende de las tareas, la duración y la autonomía requerida, y con más presupuesto se amplía la elección de perfiles. Si tu presupuesto es distinto, indícalo y vemos cómo ayudarte."),
    ),
    array(
      'q' => $L("Il personale è di Torino?", "Is the staff based in Turin?", "Le personnel est-il basé à Turin ?", "¿El personal es de Turín?"),
      'a' => $L("Lavoriamo con personale sul posto, a Torino e in Piemonte, e TOAgency ha sede in città. Se l'evento è altrove, formiamo la squadra giusta anche fuori Torino.",
                "We work with local staff in Turin and Piedmont, and TOAgency is based in the city. If your event is elsewhere, we build the right team outside Turin too.",
                "Nous travaillons avec du personnel sur place, à Turin et dans le Piémont, et TOAgency a son siège en ville. Si l'événement a lieu ailleurs, nous constituons l'équipe qu'il faut hors de Turin aussi.",
                "Trabajamos con personal local, en Turín y en el Piamonte, y TOAgency tiene su sede en la ciudad. Si el evento es en otro lugar, formamos el equipo adecuado también fuera de Turín."),
    ),
    array(
      'q' => $L("Oltre a hostess e steward, cosa potete fornire?", "Besides hostesses and stewards, what can you provide?", "Au-delà des hôtesses et stewards, que pouvez-vous fournir ?", "Además de azafatas y stewards, ¿qué podéis proporcionar?"),
      'a' => $L("Barman e camerieri, foto e video, crew tecnica e logistica. I ruoli tecnici e di sicurezza sono affidati solo a personale qualificato e in regola; i trasporti li organizziamo con partner autorizzati.",
                "Bartenders and waiters, photo and video, technical crew and logistics. Technical and security roles go only to qualified, compliant staff; transport is arranged with authorised partners.",
                "Barmans et serveurs, photo et vidéo, équipes techniques et logistique. Les postes techniques et de sécurité ne sont confiés qu'à du personnel qualifié et en règle ; les transports sont organisés avec des partenaires autorisés.",
                "Barmans y camareros, foto y vídeo, equipo técnico y logística. Los puestos técnicos y de seguridad se confían solo a personal cualificado y en regla; el transporte lo organizamos con socios autorizados."),
    ),
    array(
      'q' => $L("Lo staff parla lingue straniere?", "Does the staff speak foreign languages?", "Le personnel parle-t-il des langues étrangères ?", "¿El personal habla idiomas?"),
      'a' => $L("Sì, abbiamo staff multilingue per ospiti e stampa internazionali. Gli interpreti sono disponibili su richiesta, in base a lingua e disponibilità.",
                "Yes, we have multilingual staff for international guests and press. Interpreters are available on request, depending on language and availability.",
                "Oui, nous avons du personnel multilingue pour les invités et la presse internationale. Des interprètes sont disponibles sur demande, selon la langue et la disponibilité.",
                "Sí, tenemos personal multilingüe para invitados y prensa internacionales. Los intérpretes están disponibles bajo petición, según el idioma y la disponibilidad."),
    ),
    array(
      'q' => $L("Chi gestisce contratti e assicurazioni del personale?", "Who handles the staff's contracts and insurance?", "Qui gère les contrats et les assurances du personnel ?", "¿Quién gestiona los contratos y seguros del personal?"),
      'a' => $L("Li gestiamo noi: contratti, compensi e assicurazione INAIL del personale, con una sola fattura per il cliente.",
                "We do: staff contracts, fees and INAIL insurance, with a single invoice for the client.",
                "C'est nous : contrats, rémunérations et assurance INAIL du personnel, avec une seule facture pour le client.",
                "Nos encargamos nosotros: contratos, honorarios y seguro INAIL del personal, con una sola factura para el cliente."),
    ),
    array(
      'q' => $L("Quanto tempo ci vuole per avere una proposta?", "How long does it take to get a proposal?", "Combien de temps faut-il pour recevoir une proposition ?", "¿Cuánto se tarda en recibir una propuesta?"),
      'a' => $L("Rispondiamo in genere entro 24 ore lavorative. Più dettagli ci dai (date, sede, numero di persone), più la proposta è precisa.",
                "We usually reply within 24 working hours. The more detail you give us (dates, venue, number of people), the more precise the proposal.",
                "Nous répondons en général sous 24 heures ouvrées. Plus vous nous donnez de détails (dates, lieu, nombre de personnes), plus la proposition est précise.",
                "Respondemos por lo general en 24 horas laborables. Cuantos más detalles nos des (fechas, sede, número de personas), más precisa será la propuesta."),
    ),
  ),
),

'milano' => array(
  'slug'  => $L('milano', 'milan', 'milan', 'milan'),
  'name'  => $L('Milano', 'Milan', 'Milan', 'Milán'),
  'seo_title' => $L(
    "Staff per eventi a Milano: fiere, moda, congressi | TOAgency",
    "Event staff in Milan: trade shows, fashion, conferences | TOAgency",
    "Personnel événementiel à Milan : salons, mode, congrès | TOAgency",
    "Personal para eventos en Milán: ferias, moda, congresos | TOAgency"),
  'seo_desc' => $L(
    "Hostess, steward, bar, foto e video, crew e logistica per eventi a Milano: Fiera Milano Rho, MiCo, Fashion Week. TOA Events, un referente, un preventivo.",
    "Hostesses, stewards, bar, photo and video, crew and logistics for events in Milan: Fiera Milano Rho, MiCo, Fashion Week. TOA Events: one contact, one quote.",
    "Hôtesses, stewards, bar, photo et vidéo, équipes et logistique pour événements à Milan : Fiera Milano Rho, MiCo, Fashion Week. Un seul interlocuteur.",
    "Azafatas, stewards, bar, foto y vídeo, equipos y logística para eventos en Milán: Fiera Milano Rho, MiCo, Fashion Week. Un interlocutor, un presupuesto."),
  'h1'    => $L("Staff e servizi per eventi a Milano", "Event staff and services in Milan", "Personnel et services pour événements à Milan", "Personal y servicios para eventos en Milán"),
  'intro' => $L(
    "Fiere, moda, design e congressi: a Milano lavoriamo con personale sul posto, guidato da un unico referente. Dalle hostess alla crew tecnica, un solo preventivo.",
    "Trade shows, fashion, design and conferences: in Milan we work with staff on the ground, led by a single point of contact. From hostesses to technical crew, one quote.",
    "Salons, mode, design et congrès : à Milan nous travaillons avec du personnel sur place, dirigé par un seul interlocuteur. Des hôtesses aux équipes techniques, un seul devis.",
    "Ferias, moda, diseño y congresos: en Milán trabajamos con personal sobre el terreno, dirigido por un único interlocutor. De las azafatas al equipo técnico, un único presupuesto."),

  // Tariffa di partenza (Italia: €129 + IVA a persona al giorno). IVA solo nel testo italiano.
  'price' => $L(
    "Hostess, steward e promoter a partire da €129 + IVA a persona al giorno. Altri profili su preventivo.",
    "Hostesses, stewards and promoters from €129 per person per day. Other profiles on request.",
    "Hôtesses, stewards et promoteurs à partir de 129 € par personne et par jour. Autres profils sur demande.",
    "Azafatas, stewards y promotores desde 129 € por persona y día. Otros perfiles bajo petición."),

  'v_eyebrow' => $L("Le sedi", "Venues", "Les lieux", "Las sedes"),
  'v_heading' => $L("Dove lavoriamo a Milano", "Where we work in Milan", "Où nous intervenons à Milan", "Dónde trabajamos en Milán"),
  'v_sub'     => $L("Alcune sedi ed eventi della città, con le pagine di approfondimento.", "Some of the city's venues and events, with in-depth pages.", "Quelques lieux et événements de la ville, avec les pages détaillées.", "Algunas sedes y eventos de la ciudad, con sus páginas de detalle."),
  'venues' => array(
    array(
      'n' => $L("Fiera Milano Rho", "Fiera Milano Rho", "Fiera Milano Rho", "Fiera Milano Rho"),
      'd' => $L("Il polo fieristico dei grandi saloni internazionali, da EICMA a MICAM e HostMilano: stand e padiglioni da presidiare, anche con personale multilingue.",
                "The home of the big international trade shows, from EICMA to MICAM and HostMilano: booths and halls to staff, including multilingual staff.",
                "Le parc des grands salons internationaux, d'EICMA à MICAM et HostMilano : stands et pavillons à animer, y compris avec du personnel multilingue.",
                "El recinto de los grandes salones internacionales, de EICMA a MICAM y HostMilano: stands y pabellones que atender, también con personal multilingüe."),
      'own' => 'fiere',
      'l'   => $L("Personale per fiere e saloni", "Trade show staff", "Personnel pour salons", "Personal para ferias"),
    ),
    array(
      'n' => $L("Salone del Mobile e Fuorisalone", "Salone del Mobile and Fuorisalone", "Salone del Mobile et Fuorisalone", "Salone del Mobile y Fuorisalone"),
      'd' => $L("La settimana del design: accoglienza negli stand e negli showroom, gestione degli ospiti e supporto agli espositori.",
                "Design week: reception at stands and showrooms, guest handling and exhibitor support.",
                "La semaine du design : accueil sur les stands et dans les showrooms, gestion des invités et soutien aux exposants.",
                "La semana del diseño: recepción en stands y showrooms, gestión de invitados y apoyo a los expositores."),
      'link' => 'hostess-salone-del-mobile',
      'l'    => $L("Hostess per il Salone del Mobile", "Hostesses for the Salone del Mobile", "Hôtesses pour le Salone del Mobile", "Azafatas para el Salone del Mobile"),
    ),
    array(
      'n' => $L("EICMA", "EICMA", "EICMA", "EICMA"),
      'd' => $L("Il salone internazionale delle due ruote: accoglienza allo stand, presentazione dei modelli, staff multilingue.",
                "The international two-wheeler show: stand reception, model presentation, multilingual staff.",
                "Le salon international des deux-roues : accueil sur le stand, présentation des modèles, personnel multilingue.",
                "El salón internacional de las dos ruedas: recepción en el stand, presentación de modelos, personal multilingüe."),
      'link' => 'hostess-eicma',
      'l'    => $L("Hostess e staff per EICMA", "Hostesses and staff for EICMA", "Hôtesses et personnel pour EICMA", "Azafatas y personal para EICMA"),
    ),
    array(
      'n' => $L("Allianz MiCo", "Allianz MiCo", "Allianz MiCo", "Allianz MiCo"),
      'd' => $L("Congressi e convention: registrazione, assistenza in sala, accoglienza dei delegati.",
                "Conferences and conventions: registration, room assistance, delegate reception.",
                "Congrès et conventions : enregistrement, assistance en salle, accueil des délégués.",
                "Congresos y convenciones: registro, asistencia en sala, recepción de delegados."),
      'own' => 'staff',
      'l'   => $L("Staff per eventi e congressi", "Event and conference staff", "Personnel pour événements et congrès", "Personal para eventos y congresos"),
    ),
    array(
      'n' => $L("Fashion Week e showroom", "Fashion Week and showrooms", "Fashion Week et showrooms", "Fashion Week y showrooms"),
      'd' => $L("Sfilate, presentazioni e showroom: accoglienza degli ospiti, fitting e backstage.",
                "Shows, presentations and showrooms: guest reception, fittings and backstage.",
                "Défilés, présentations et showrooms : accueil des invités, essayages et backstage.",
                "Desfiles, presentaciones y showrooms: recepción de invitados, pruebas y backstage."),
    ),
  ),

  's_eyebrow' => $L("I servizi", "Services", "Les services", "Los servicios"),
  's_heading' => $L("Tutto quello che serve per un evento a Milano", "Everything you need for an event in Milan", "Tout ce qu'il faut pour un événement à Milan", "Todo lo que necesita un evento en Milán"),

  'x_eyebrow' => $L("Approfondimenti", "More on Milan", "Pour aller plus loin", "Más información"),
  'x_hostess' => array(
    'link' => 'agenzia-hostess-milano',
    'n'    => $L("Agenzia hostess a Milano", "Hostess agency in Milan", "Agence d'hôtesses à Milan", "Agencia de azafatas en Milán"),
    'd'    => $L("La pagina TOAgency dedicata a hostess e steward in città: servizi, eventi e domande frequenti.",
                 "The TOAgency page dedicated to hostesses and stewards in the city: services, events and FAQ.",
                 "La page TOAgency consacrée aux hôtesses et stewards de la ville : services, événements et questions fréquentes.",
                 "La página de TOAgency dedicada a azafatas y stewards de la ciudad: servicios, eventos y preguntas frecuentes."),
  ),

  'f_eyebrow' => $L("Domande frequenti", "FAQ", "Questions fréquentes", "Preguntas frecuentes"),
  'f_heading' => $L("Eventi a Milano: le domande più comuni", "Events in Milan: common questions", "Événements à Milan : les questions courantes", "Eventos en Milán: las preguntas más comunes"),
  'faq' => array(
    array(
      'q' => $L("Per quali eventi a Milano fornite personale?", "Which events in Milan do you provide staff for?", "Pour quels événements à Milan fournissez-vous du personnel ?", "¿Para qué eventos en Milán proporcionáis personal?"),
      'a' => $L("Per fiere e saloni, congressi e convention, sfilate e showroom, eventi aziendali e presentazioni di prodotto. Dalla scelta delle figure alla gestione sul posto, ti segue un unico referente.",
                "For trade shows, conferences and conventions, fashion shows and showrooms, corporate events and product launches. From choosing the roles to running things on site, a single contact looks after you.",
                "Pour les salons, congrès et conventions, défilés et showrooms, événements d'entreprise et lancements de produit. Du choix des profils à la gestion sur place, un seul interlocuteur vous suit.",
                "Para ferias y salones, congresos y convenciones, desfiles y showrooms, eventos corporativos y presentaciones de producto. Desde la elección de los perfiles hasta la gestión en el lugar, te acompaña un único interlocutor."),
    ),
    array(
      'q' => $L("Quanto costa il personale per un evento a Milano?", "How much does event staff cost in Milan?", "Combien coûte le personnel pour un événement à Milan ?", "¿Cuánto cuesta el personal para un evento en Milán?"),
      'a' => $L("Hostess, steward, promoter e supporto fiera: a partire da €129 + IVA a persona al giorno. Per camerieri, barman e altri profili qualificati dipende da mansioni, durata e livello di autonomia richiesto, e con più budget la scelta di profili si allarga. Se hai un budget diverso, indicalo pure e vediamo se possiamo venirti incontro.",
                "Hostesses, stewards, promoters and trade show support: from €129 per person per day. For waiters, bartenders and other qualified profiles it depends on the tasks, the duration and the autonomy required, and a bigger budget widens the choice of profiles. If your budget is different, just tell us and we'll see how we can accommodate you.",
                "Hôtesses, stewards, promoteurs et renfort sur salon : à partir de 129 € par personne et par jour. Pour les serveurs, barmans et autres profils qualifiés, cela dépend des missions, de la durée et de l'autonomie demandée, et un budget plus important élargit le choix des profils. Si votre budget est différent, dites-le-nous et nous verrons comment vous accompagner.",
                "Azafatas, stewards, promotores y apoyo en feria: desde 129 € por persona y día. Para camareros, barmans y otros perfiles cualificados depende de las tareas, la duración y la autonomía requerida, y con más presupuesto se amplía la elección de perfiles. Si tu presupuesto es distinto, indícalo y vemos cómo ayudarte."),
    ),
    array(
      'q' => $L("Il personale è di Milano?", "Is the staff based in Milan?", "Le personnel est-il basé à Milan ?", "¿El personal es de Milán?"),
      'a' => $L("Lavoriamo con personale sul posto, a Milano e in Lombardia. Per i grandi saloni gestiamo anche team numerosi e le sovrapposizioni del calendario milanese. Se l'evento è altrove, formiamo la squadra giusta anche fuori Milano.",
                "We work with local staff in Milan and Lombardy. For the big trade shows we also handle large teams and the overlaps in Milan's busy calendar. If your event is elsewhere, we build the right team outside Milan too.",
                "Nous travaillons avec du personnel sur place, à Milan et en Lombardie. Pour les grands salons, nous gérons aussi de grandes équipes et les chevauchements du calendrier milanais. Si l'événement a lieu ailleurs, nous constituons l'équipe qu'il faut hors de Milan aussi.",
                "Trabajamos con personal local, en Milán y en Lombardía. Para los grandes salones gestionamos también equipos numerosos y los solapamientos del calendario milanés. Si el evento es en otro lugar, formamos el equipo adecuado también fuera de Milán."),
    ),
    array(
      'q' => $L("Oltre a hostess e steward, cosa potete fornire?", "Besides hostesses and stewards, what can you provide?", "Au-delà des hôtesses et stewards, que pouvez-vous fournir ?", "Además de azafatas y stewards, ¿qué podéis proporcionar?"),
      'a' => $L("Barman e camerieri, foto e video, crew tecnica e logistica. I ruoli tecnici e di sicurezza sono affidati solo a personale qualificato e in regola; i trasporti li organizziamo con partner autorizzati.",
                "Bartenders and waiters, photo and video, technical crew and logistics. Technical and security roles go only to qualified, compliant staff; transport is arranged with authorised partners.",
                "Barmans et serveurs, photo et vidéo, équipes techniques et logistique. Les postes techniques et de sécurité ne sont confiés qu'à du personnel qualifié et en règle ; les transports sont organisés avec des partenaires autorisés.",
                "Barmans y camareros, foto y vídeo, equipo técnico y logística. Los puestos técnicos y de seguridad se confían solo a personal cualificado y en regla; el transporte lo organizamos con socios autorizados."),
    ),
    array(
      'q' => $L("Lo staff parla lingue straniere?", "Does the staff speak foreign languages?", "Le personnel parle-t-il des langues étrangères ?", "¿El personal habla idiomas?"),
      'a' => $L("Sì, abbiamo staff multilingue per ospiti e stampa internazionali. Gli interpreti sono disponibili su richiesta, in base a lingua e disponibilità.",
                "Yes, we have multilingual staff for international guests and press. Interpreters are available on request, depending on language and availability.",
                "Oui, nous avons du personnel multilingue pour les invités et la presse internationale. Des interprètes sont disponibles sur demande, selon la langue et la disponibilité.",
                "Sí, tenemos personal multilingüe para invitados y prensa internacionales. Los intérpretes están disponibles bajo petición, según el idioma y la disponibilidad."),
    ),
    array(
      'q' => $L("Chi gestisce contratti e assicurazioni del personale?", "Who handles the staff's contracts and insurance?", "Qui gère les contrats et les assurances du personnel ?", "¿Quién gestiona los contratos y seguros del personal?"),
      'a' => $L("Li gestiamo noi: contratti, compensi e assicurazione INAIL del personale, con una sola fattura per il cliente.",
                "We do: staff contracts, fees and INAIL insurance, with a single invoice for the client.",
                "C'est nous : contrats, rémunérations et assurance INAIL du personnel, avec une seule facture pour le client.",
                "Nos encargamos nosotros: contratos, honorarios y seguro INAIL del personal, con una sola factura para el cliente."),
    ),
    array(
      'q' => $L("Quanto tempo ci vuole per avere una proposta?", "How long does it take to get a proposal?", "Combien de temps faut-il pour recevoir une proposition ?", "¿Cuánto se tarda en recibir una propuesta?"),
      'a' => $L("Rispondiamo in genere entro 24 ore lavorative. Più dettagli ci dai (date, sede, numero di persone), più la proposta è precisa.",
                "We usually reply within 24 working hours. The more detail you give us (dates, venue, number of people), the more precise the proposal.",
                "Nous répondons en général sous 24 heures ouvrées. Plus vous nous donnez de détails (dates, lieu, nombre de personnes), plus la proposition est précise.",
                "Respondemos por lo general en 24 horas laborables. Cuantos más detalles nos des (fechas, sede, número de personas), más precisa será la propuesta."),
    ),
  ),
),

);
