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

'roma' => array(
  'slug'  => $L('roma', 'rome', 'rome', 'roma'),
  'name'  => $L('Roma', 'Rome', 'Rome', 'Roma'),
  'seo_title' => $L(
    "Staff per eventi a Roma: congressi, fiere, eventi istituzionali | TOAgency",
    "Event staff in Rome: conferences, trade shows, institutional events | TOAgency",
    "Personnel événementiel à Rome : congrès, salons, événements institutionnels | TOAgency",
    "Personal para eventos en Roma: congresos, ferias, eventos institucionales | TOAgency"),
  'seo_desc' => $L(
    "Hostess, steward, bar, foto e video, crew e logistica per eventi a Roma: Fiera di Roma, La Nuvola, sedi istituzionali. Un referente, un preventivo.",
    "Hostesses, stewards, bar, photo and video, crew and logistics for events in Rome: Fiera di Roma, La Nuvola, institutional venues. One contact, one quote.",
    "Hôtesses, stewards, bar, photo et vidéo, équipes et logistique pour événements à Rome : Fiera di Roma, La Nuvola, lieux institutionnels. Un seul interlocuteur.",
    "Azafatas, stewards, bar, foto y vídeo, equipos y logística para eventos en Roma: Fiera di Roma, La Nuvola y sedes institucionales. Un solo interlocutor."),
  'h1'    => $L("Staff e servizi per eventi a Roma", "Event staff and services in Rome", "Personnel et services pour événements à Rome", "Personal y servicios para eventos en Roma"),
  'intro' => $L(
    "Congressi, fiere ed eventi istituzionali: a Roma lavoriamo con personale sul posto, guidato da un unico referente. Dalle hostess alla crew tecnica, un solo preventivo.",
    "Conferences, trade shows and institutional events: in Rome we work with staff on the ground, led by a single point of contact. From hostesses to technical crew, one quote.",
    "Congrès, salons et événements institutionnels : à Rome nous travaillons avec du personnel sur place, dirigé par un seul interlocuteur. Des hôtesses aux équipes techniques, un seul devis.",
    "Congresos, ferias y eventos institucionales: en Roma trabajamos con personal sobre el terreno, dirigido por un único interlocutor. De las azafatas al equipo técnico, un único presupuesto."),

  // Tariffa di partenza (Italia: €129 + IVA a persona al giorno). IVA solo nel testo italiano.
  'price' => $L(
    "Hostess, steward e promoter a partire da €129 + IVA a persona al giorno. Altri profili su preventivo.",
    "Hostesses, stewards and promoters from €129 per person per day. Other profiles on request.",
    "Hôtesses, stewards et promoteurs à partir de 129 € par personne et par jour. Autres profils sur demande.",
    "Azafatas, stewards y promotores desde 129 € por persona y día. Otros perfiles bajo petición."),

  'v_eyebrow' => $L("Le sedi", "Venues", "Les lieux", "Las sedes"),
  'v_heading' => $L("Dove lavoriamo a Roma", "Where we work in Rome", "Où nous intervenons à Rome", "Dónde trabajamos en Roma"),
  'v_sub'     => $L("Alcune sedi della città, con le pagine di approfondimento.", "Some of the city's venues, with in-depth pages.", "Quelques lieux de la ville, avec les pages détaillées.", "Algunas sedes de la ciudad, con sus páginas de detalle."),
  'venues' => array(
    array(
      'n' => $L("Fiera di Roma", "Fiera di Roma", "Fiera di Roma", "Fiera di Roma"),
      'd' => $L("Il polo fieristico della Capitale: accoglienza, info point, gestione dello stand e supporto agli espositori, anche con personale multilingue.",
                "The Capital's exhibition centre: reception, info points, stand management and exhibitor support, including multilingual staff.",
                "Le parc des expositions de la capitale : accueil, points d'information, gestion du stand et soutien aux exposants, y compris avec du personnel multilingue.",
                "El recinto ferial de la capital: recepción, puntos de información, gestión del stand y apoyo a los expositores, también con personal multilingüe."),
      'own' => 'fiere',
      'l'   => $L("Personale per fiere e saloni", "Trade show staff", "Personnel pour salons", "Personal para ferias"),
    ),
    array(
      'n' => $L("Roma Convention Center La Nuvola", "Roma Convention Center La Nuvola", "Roma Convention Center La Nuvola", "Roma Convention Center La Nuvola"),
      'd' => $L("Congressi internazionali all'EUR: registrazione dei delegati, assistenza in sala, accoglienza dei relatori.",
                "International conferences at EUR: delegate registration, room assistance, speaker reception.",
                "Congrès internationaux à l'EUR : enregistrement des délégués, assistance en salle, accueil des intervenants.",
                "Congresos internacionales en el EUR: registro de delegados, asistencia en sala, recepción de ponentes."),
      'own' => 'staff',
      'l'   => $L("Staff per eventi e congressi", "Event and conference staff", "Personnel pour événements et congrès", "Personal para eventos y congresos"),
    ),
    array(
      'n' => $L("Auditorium", "Auditorium", "Auditorium", "Auditorium"),
      'd' => $L("Eventi culturali, concerti e presentazioni: accoglienza del pubblico e degli ospiti, assistenza in sala.",
                "Cultural events, concerts and presentations: audience and guest reception, room assistance.",
                "Événements culturels, concerts et présentations : accueil du public et des invités, assistance en salle.",
                "Eventos culturales, conciertos y presentaciones: recepción del público y de los invitados, asistencia en sala."),
    ),
    array(
      'n' => $L("Sedi di rappresentanza in centro", "Prestige venues in the city centre", "Lieux de prestige dans le centre", "Sedes de representación en el centro"),
      'd' => $L("Eventi istituzionali e formali: accoglienza di rappresentanza, guardaroba, gestione di ospiti e delegazioni.",
                "Institutional and formal events: formal reception, cloakroom, guest and delegation handling.",
                "Événements institutionnels et officiels : accueil de représentation, vestiaire, gestion des invités et des délégations.",
                "Eventos institucionales y formales: recepción de representación, guardarropa, gestión de invitados y delegaciones."),
    ),
  ),

  's_eyebrow' => $L("I servizi", "Services", "Les services", "Los servicios"),
  's_heading' => $L("Tutto quello che serve per un evento a Roma", "Everything you need for an event in Rome", "Tout ce qu'il faut pour un événement à Rome", "Todo lo que necesita un evento en Roma"),

  'x_eyebrow' => $L("Approfondimenti", "More on Rome", "Pour aller plus loin", "Más información"),
  'x_hostess' => array(
    'link' => 'agenzia-hostess-roma',
    'n'    => $L("Agenzia hostess a Roma", "Hostess agency in Rome", "Agence d'hôtesses à Rome", "Agencia de azafatas en Roma"),
    'd'    => $L("La pagina TOAgency dedicata a hostess e steward in città: servizi, eventi e domande frequenti.",
                 "The TOAgency page dedicated to hostesses and stewards in the city: services, events and FAQ.",
                 "La page TOAgency consacrée aux hôtesses et stewards de la ville : services, événements et questions fréquentes.",
                 "La página de TOAgency dedicada a azafatas y stewards de la ciudad: servicios, eventos y preguntas frecuentes."),
  ),

  'f_eyebrow' => $L("Domande frequenti", "FAQ", "Questions fréquentes", "Preguntas frecuentes"),
  'f_heading' => $L("Eventi a Roma: le domande più comuni", "Events in Rome: common questions", "Événements à Rome : les questions courantes", "Eventos en Roma: las preguntas más comunes"),
  'faq' => array(
    array(
      'q' => $L("Per quali eventi a Roma fornite personale?", "Which events in Rome do you provide staff for?", "Pour quels événements à Rome fournissez-vous du personnel ?", "¿Para qué eventos en Roma proporcionáis personal?"),
      'a' => $L("Per congressi e convention, fiere, eventi istituzionali e di rappresentanza, eventi aziendali e presentazioni di prodotto. Dalla scelta delle figure alla gestione sul posto, ti segue un unico referente.",
                "For conferences and conventions, trade shows, institutional and formal events, corporate events and product launches. From choosing the roles to running things on site, a single contact looks after you.",
                "Pour les congrès et conventions, salons, événements institutionnels et de représentation, événements d'entreprise et lancements de produit. Du choix des profils à la gestion sur place, un seul interlocuteur vous suit.",
                "Para congresos y convenciones, ferias, eventos institucionales y de representación, eventos corporativos y presentaciones de producto. Desde la elección de los perfiles hasta la gestión en el lugar, te acompaña un único interlocutor."),
    ),
    array(
      'q' => $L("Quanto costa il personale per un evento a Roma?", "How much does event staff cost in Rome?", "Combien coûte le personnel pour un événement à Rome ?", "¿Cuánto cuesta el personal para un evento en Roma?"),
      'a' => $L("Hostess, steward, promoter e supporto fiera: a partire da €129 + IVA a persona al giorno. Per camerieri, barman e altri profili qualificati dipende da mansioni, durata e livello di autonomia richiesto, e con più budget la scelta di profili si allarga. Se hai un budget diverso, indicalo pure e vediamo se possiamo venirti incontro.",
                "Hostesses, stewards, promoters and trade show support: from €129 per person per day. For waiters, bartenders and other qualified profiles it depends on the tasks, the duration and the autonomy required, and a bigger budget widens the choice of profiles. If your budget is different, just tell us and we'll see how we can accommodate you.",
                "Hôtesses, stewards, promoteurs et renfort sur salon : à partir de 129 € par personne et par jour. Pour les serveurs, barmans et autres profils qualifiés, cela dépend des missions, de la durée et de l'autonomie demandée, et un budget plus important élargit le choix des profils. Si votre budget est différent, dites-le-nous et nous verrons comment vous accompagner.",
                "Azafatas, stewards, promotores y apoyo en feria: desde 129 € por persona y día. Para camareros, barmans y otros perfiles cualificados depende de las tareas, la duración y la autonomía requerida, y con más presupuesto se amplía la elección de perfiles. Si tu presupuesto es distinto, indícalo y vemos cómo ayudarte."),
    ),
    array(
      'q' => $L("Il personale è di Roma?", "Is the staff based in Rome?", "Le personnel est-il basé à Rome ?", "¿El personal es de Roma?"),
      'a' => $L("Lavoriamo con personale sul posto, a Roma e nel Lazio. Se l'evento è altrove, formiamo la squadra giusta anche fuori Roma.",
                "We work with local staff in Rome and Lazio. If your event is elsewhere, we build the right team outside Rome too.",
                "Nous travaillons avec du personnel sur place, à Rome et dans le Latium. Si l'événement a lieu ailleurs, nous constituons l'équipe qu'il faut hors de Rome aussi.",
                "Trabajamos con personal local, en Roma y en el Lacio. Si el evento es en otro lugar, formamos el equipo adecuado también fuera de Roma."),
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
      'a' => $L("Sì, abbiamo staff multilingue per congressi internazionali e delegazioni estere. Gli interpreti sono disponibili su richiesta, in base a lingua e disponibilità.",
                "Yes, we have multilingual staff for international conferences and foreign delegations. Interpreters are available on request, depending on language and availability.",
                "Oui, nous avons du personnel multilingue pour les congrès internationaux et les délégations étrangères. Des interprètes sont disponibles sur demande, selon la langue et la disponibilité.",
                "Sí, tenemos personal multilingüe para congresos internacionales y delegaciones extranjeras. Los intérpretes están disponibles bajo petición, según el idioma y la disponibilidad."),
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

'parigi' => array(
  'slug'  => $L("parigi", "paris", "paris", "paris"),
  'name'  => $L("Parigi", "Paris", "Paris", "París"),
  'seo_title' => $L("Staff per eventi a Parigi: fiere, congressi, brand activation | TOAgency", "Event staff in Paris: trade shows, conferences, brand activations | TOAgency", "Personnel événementiel à Paris : salons, congrès, brand activations | TOAgency", "Personal para eventos en París: ferias, congresos, activaciones de marca | TOAgency"),
  'seo_desc' => $L("Hostess, steward, bar, foto e video, crew e logistica per eventi a Parigi: fiere, congressi, inaugurazioni. Un referente, un preventivo.", "Hostesses, stewards, bar, photo and video, crew and logistics for events in Paris: trade shows, conferences, launches. One contact, one quote.", "Hôtesses, stewards, bar, photo et vidéo, équipes et logistique pour événements à Paris : salons, congrès, inaugurations. Un seul interlocuteur.", "Azafatas, stewards, bar, foto y vídeo, equipos y logística para eventos en París: ferias, congresos, inauguraciones. Un interlocutor, un presupuesto."),
  'h1'    => $L("Staff e servizi per eventi a Parigi", "Event staff and services in Paris", "Personnel et services pour événements à Paris", "Personal y servicios para eventos en París"),
  'intro' => $L("Fiere professionali, congressi, inaugurazioni e brand activation: a Parigi e in tutta la Francia, con personale multilingue e un unico referente. Dalle hostess alla crew tecnica, un solo preventivo.", "Trade shows, conferences, launches and brand activations: in Paris and across France, with multilingual staff and a single point of contact. From hostesses to technical crew, one quote.", "Salons professionnels, congrès, inaugurations et brand activations : à Paris et dans toute la France, avec du personnel multilingue et un seul interlocuteur. Des hôtesses aux équipes techniques, un seul devis.", "Ferias profesionales, congresos, inauguraciones y activaciones de marca: en París y en toda Francia, con personal multilingüe y un único interlocutor. De las azafatas al equipo técnico, un único presupuesto."),
  // Tariffa di partenza (regola di Marco 19/10/2026). IVA solo nel testo italiano.
  'price' => $L("Hostess, steward e promoter a partire da €159 + IVA a persona al giorno. Altri profili su preventivo.", "Hostesses, stewards and promoters from €159 per person per day. Other profiles on request.", "Hôtesses, stewards et promoteurs à partir de 159 € par personne et par jour. Autres profils sur demande.", "Azafatas, stewards y promotores desde 159 € por persona y día. Otros perfiles bajo petición."),
  'v_eyebrow' => $L("Le sedi", "Venues", "Les lieux", "Las sedes"),
  'v_heading' => $L("Dove si svolgono gli eventi a Parigi", "Where events take place in Paris", "Où se déroulent les événements à Paris", "Dónde se celebran los eventos en París"),
  'v_sub'     => $L("Alcune sedi della città.", "Some of the city's venues.", "Quelques lieux de la ville.", "Algunas sedes de la ciudad."),
  'venues' => array(
    array(
      'n' => $L("Paris Expo Porte de Versailles", "Paris Expo Porte de Versailles", "Paris Expo Porte de Versailles", "Paris Expo Porte de Versailles"),
      'd' => $L("Uno dei principali poli fieristici della città: stand e padiglioni da presidiare, accoglienza e supporto agli espositori.", "One of the city's main exhibition centres: booths and halls to staff, reception and exhibitor support.", "L'un des principaux parcs des expositions de la ville : stands et pavillons à animer, accueil et soutien aux exposants.", "Uno de los principales recintos feriales de la ciudad: stands y pabellones que atender, recepción y apoyo a los expositores."),
      'own' => 'fiere',
      'l'   => $L("Personale per fiere e saloni", "Trade show staff", "Personnel pour salons", "Personal para ferias"),
    ),
    array(
      'n' => $L("Paris Nord Villepinte", "Paris Nord Villepinte", "Paris Nord Villepinte", "Paris Nord Villepinte"),
      'd' => $L("Grandi saloni professionali internazionali: accoglienza dei buyer, gestione dello stand, staff multilingue.", "Large international trade shows: buyer reception, stand management, multilingual staff.", "Grands salons professionnels internationaux : accueil des acheteurs, gestion du stand, personnel multilingue.", "Grandes salones profesionales internacionales: recepción de compradores, gestión del stand, personal multilingüe."),
    ),
    array(
      'n' => $L("Palais des Congrès", "Palais des Congrès", "Palais des Congrès", "Palais des Congrès"),
      'd' => $L("Congressi e convention: registrazione, assistenza in sala, accoglienza dei delegati.", "Conferences and conventions: registration, room assistance, delegate reception.", "Congrès et conventions : enregistrement, assistance en salle, accueil des délégués.", "Congresos y convenciones: registro, asistencia en sala, recepción de delegados."),
      'own' => 'staff',
      'l'   => $L("Staff per eventi e congressi", "Event and conference staff", "Personnel pour événements et congrès", "Personal para eventos y congresos"),
    ),
    array(
      'n' => $L("Inaugurazioni e brand activation", "Launches and brand activations", "Inaugurations et brand activations", "Inauguraciones y activaciones de marca"),
      'd' => $L("Aperture, lanci di prodotto, sampling e lead generation: hostess, modelle immagine e promoter.", "Openings, product launches, sampling and lead generation: hostesses, image models and promoters.", "Ouvertures, lancements de produit, sampling et lead generation : hôtesses, mannequins image et promoteurs.", "Aperturas, lanzamientos de producto, sampling y lead generation: azafatas, modelos de imagen y promotores."),
    ),
  ),
  's_eyebrow' => $L("I servizi", "Services", "Les services", "Los servicios"),
  's_heading' => $L("Tutto quello che serve per un evento a Parigi", "Everything you need for an event in Paris", "Tout ce qu'il faut pour un événement à Paris", "Todo lo que necesita un evento en París"),
  'x_eyebrow' => $L("Approfondimenti", "More on France", "Pour aller plus loin", "Más información"),
  'x_hostess' => array(
    'link' => 'hostess-francia',
    'n'    => $L("Hostess e steward in Francia", "Hostesses and stewards in France", "Hôtesses et stewards en France", "Azafatas y stewards en Francia"),
    'd'    => $L("La pagina TOAgency dedicata a hostess e steward: servizi, eventi e domande frequenti.", "The TOAgency page dedicated to hostesses and stewards: services, events and FAQ.", "La page TOAgency consacrée aux hôtesses et stewards : services, événements et questions fréquentes.", "La página de TOAgency dedicada a azafatas y stewards: servicios, eventos y preguntas frecuentes."),
  ),
  'f_eyebrow' => $L("Domande frequenti", "FAQ", "Questions fréquentes", "Preguntas frecuentes"),
  'f_heading' => $L("Eventi a Parigi: le domande più comuni", "Events in Paris: common questions", "Événements à Paris : les questions courantes", "Eventos en París: las preguntas más comunes"),
  'faq' => array(
    array(
      'q' => $L("Per quali eventi a Parigi fornite personale?", "Which events in Paris do you provide staff for?", "Pour quels événements à Paris fournissez-vous du personnel ?", "¿Para qué eventos en París proporcionáis personal?"),
      'a' => $L("Per fiere professionali e congressi, eventi aziendali, inaugurazioni e brand activation. Dalla scelta delle figure alla gestione sul posto, ti segue un unico referente.", "For trade shows and conferences, corporate events, launches and brand activations. From choosing the roles to running things on site, a single contact looks after you.", "Pour les salons professionnels et congrès, événements d'entreprise, inaugurations et brand activations. Du choix des profils à la gestion sur place, un seul interlocuteur vous suit.", "Para ferias profesionales y congresos, eventos corporativos, inauguraciones y activaciones de marca. Desde la elección de los perfiles hasta la gestión en el lugar, te acompaña un único interlocutor."),
    ),
    array(
      'q' => $L("Quanto costa il personale per un evento a Parigi?", "How much does event staff cost in Paris?", "Combien coûte le personnel pour un événement à Paris ?", "¿Cuánto cuesta el personal para un evento en París?"),
      'a' => $L("Hostess, steward, promoter e supporto fiera: a partire da €159 + IVA a persona al giorno. Per camerieri, barman e altri profili qualificati dipende da mansioni, durata e livello di autonomia richiesto, e con più budget la scelta di profili si allarga. Se hai un budget diverso, indicalo pure e vediamo se possiamo venirti incontro.", "Hostesses, stewards, promoters and trade show support: from €159 per person per day. For waiters, bartenders and other qualified profiles it depends on the tasks, the duration and the autonomy required, and a bigger budget widens the choice of profiles. If your budget is different, just tell us and we'll see how we can accommodate you.", "Hôtesses, stewards, promoteurs et renfort sur salon : à partir de 159 € par personne et par jour. Pour les serveurs, barmans et autres profils qualifiés, cela dépend des missions, de la durée et de l'autonomie demandée, et un budget plus important élargit le choix des profils. Si votre budget est différent, dites-le-nous et nous verrons comment vous accompagner.", "Azafatas, stewards, promotores y apoyo en feria: desde 159 € por persona y día. Para camareros, barmans y otros perfiles cualificados depende de las tareas, la duración y la autonomía requerida, y con más presupuesto se amplía la elección de perfiles. Si tu presupuesto es distinto, indícalo y vemos cómo ayudarte."),
    ),
    array(
      'q' => $L("Il personale è di Parigi?", "Is the staff based in Paris?", "Le personnel est-il basé à Paris ?", "¿El personal es de París?"),
      'a' => $L("Lavoriamo con personale sul posto, a Parigi e in tutta la Francia. Se l'evento è altrove in Europa, formiamo la squadra giusta anche lì.", "We work with local staff in Paris and across France. If your event is elsewhere in Europe, we build the right team there too.", "Nous travaillons avec du personnel sur place, à Paris et dans toute la France. Si l'événement a lieu ailleurs en Europe, nous constituons l'équipe qu'il faut là-bas aussi.", "Trabajamos con personal local, en París y en toda Francia. Si el evento es en otro lugar de Europa, formamos el equipo adecuado también allí."),
    ),
    array(
      'q' => $L("Oltre a hostess e steward, cosa potete fornire?", "Besides hostesses and stewards, what can you provide?", "Au-delà des hôtesses et stewards, que pouvez-vous fournir ?", "Además de azafatas y stewards, ¿qué podéis proporcionar?"),
      'a' => $L("Barman e camerieri, foto e video, crew tecnica e logistica. I ruoli tecnici e di sicurezza sono affidati solo a personale qualificato e in regola; i trasporti li organizziamo con partner autorizzati.", "Bartenders and waiters, photo and video, technical crew and logistics. Technical and security roles go only to qualified, compliant staff; transport is arranged with authorised partners.", "Barmans et serveurs, photo et vidéo, équipes techniques et logistique. Les postes techniques et de sécurité ne sont confiés qu'à du personnel qualifié et en règle ; les transports sont organisés avec des partenaires autorisés.", "Barmans y camareros, foto y vídeo, equipo técnico y logística. Los puestos técnicos y de seguridad se confían solo a personal cualificado y en regla; el transporte lo organizamos con socios autorizados."),
    ),
    array(
      'q' => $L("Lo staff parla lingue straniere?", "Does the staff speak foreign languages?", "Le personnel parle-t-il des langues étrangères ?", "¿El personal habla idiomas?"),
      'a' => $L("Sì, abbiamo staff multilingue per buyer, ospiti e stampa internazionali. Gli interpreti sono disponibili su richiesta, in base a lingua e disponibilità.", "Yes, we have multilingual staff for international buyers, guests and press. Interpreters are available on request, depending on language and availability.", "Oui, nous avons du personnel multilingue pour les acheteurs, invités et la presse internationale. Des interprètes sont disponibles sur demande, selon la langue et la disponibilité.", "Sí, tenemos personal multilingüe para compradores, invitados y prensa internacionales. Los intérpretes están disponibles bajo petición, según el idioma y la disponibilidad."),
    ),
    array(
      'q' => $L("Chi gestisce contratti e pagamenti del personale?", "Who handles the staff's contracts and payments?", "Qui gère les contrats et les paiements du personnel ?", "¿Quién gestiona los contratos y pagos del personal?"),
      'a' => $L("Li gestiamo noi: contratti e compensi del personale, con una sola fattura per il cliente.", "We do: staff contracts and fees, with a single invoice for the client.", "C'est nous : contrats et rémunérations du personnel, avec une seule facture pour le client.", "Nos encargamos nosotros: contratos y honorarios del personal, con una sola factura para el cliente."),
    ),
    array(
      'q' => $L("Quanto tempo ci vuole per avere una proposta?", "How long does it take to get a proposal?", "Combien de temps faut-il pour recevoir une proposition ?", "¿Cuánto se tarda en recibir una propuesta?"),
      'a' => $L("Rispondiamo in genere entro 24 ore lavorative. Più dettagli ci dai (date, sede, numero di persone), più la proposta è precisa.", "We usually reply within 24 working hours. The more detail you give us (dates, venue, number of people), the more precise the proposal.", "Nous répondons en général sous 24 heures ouvrées. Plus vous nous donnez de détails (dates, lieu, nombre de personnes), plus la proposition est précise.", "Respondemos por lo general en 24 horas laborables. Cuantos más detalles nos des (fechas, sede, número de personas), más precisa será la propuesta."),
    ),
  ),
),

'costa-azzurra' => array(
  'slug'  => $L("costa-azzurra", "french-riviera", "cote-d-azur", "costa-azul"),
  'name'  => $L("Costa Azzurra", "French Riviera", "Côte d'Azur", "Costa Azul"),
  'seo_title' => $L("Staff per eventi sulla Costa Azzurra: Cannes, Saint-Tropez | TOAgency", "Event staff on the French Riviera: Cannes, Saint-Tropez | TOAgency", "Personnel événementiel sur la Côte d'Azur : Cannes, Saint-Tropez | TOAgency", "Personal para eventos en la Costa Azul: Cannes, Saint-Tropez | TOAgency"),
  'seo_desc' => $L("Hostess, steward, bar, foto e video, crew e logistica per eventi sulla Costa Azzurra: Cannes, Saint-Tropez. Un referente, un preventivo.", "Hostesses, stewards, bar, photo and video, crew and logistics for events on the French Riviera: Cannes, Saint-Tropez. One contact, one quote.", "Hôtesses, stewards, bar, photo et vidéo, équipes et logistique pour événements sur la Côte d'Azur : Cannes, Saint-Tropez. Un seul interlocuteur.", "Azafatas, stewards, bar, foto y vídeo, equipos y logística para eventos en la Costa Azul: Cannes, Saint-Tropez. Un interlocutor, un presupuesto."),
  'h1'    => $L("Staff e servizi per eventi sulla Costa Azzurra", "Event staff and services on the French Riviera", "Personnel et services pour événements sur la Côte d'Azur", "Personal y servicios para eventos en la Costa Azul"),
  'intro' => $L("Festival, congressi, fiere e gala: a Cannes e sulla Costa Azzurra, con personale multilingue e un unico referente. Dalle hostess alla crew tecnica, un solo preventivo.", "Festivals, conferences, trade shows and galas: in Cannes and on the French Riviera, with multilingual staff and a single point of contact. From hostesses to technical crew, one quote.", "Festivals, congrès, salons et galas : à Cannes et sur la Côte d'Azur, avec du personnel multilingue et un seul interlocuteur. Des hôtesses aux équipes techniques, un seul devis.", "Festivales, congresos, ferias y galas: en Cannes y en la Costa Azul, con personal multilingüe y un único interlocutor. De las azafatas al equipo técnico, un único presupuesto."),
  // Tariffa di partenza (regola di Marco 19/10/2026). IVA solo nel testo italiano.
  'price' => $L("Hostess, steward e promoter a partire da €159 + IVA a persona al giorno. Altri profili su preventivo.", "Hostesses, stewards and promoters from €159 per person per day. Other profiles on request.", "Hôtesses, stewards et promoteurs à partir de 159 € par personne et par jour. Autres profils sur demande.", "Azafatas, stewards y promotores desde 159 € por persona y día. Otros perfiles bajo petición."),
  'v_eyebrow' => $L("Le sedi", "Venues", "Les lieux", "Las sedes"),
  'v_heading' => $L("Gli eventi sulla Costa Azzurra", "Events on the French Riviera", "Les événements sur la Côte d'Azur", "Los eventos en la Costa Azul"),
  'v_sub'     => $L("Alcuni degli appuntamenti principali, con le pagine di approfondimento.", "Some of the main events, with in-depth pages.", "Quelques-uns des grands rendez-vous, avec les pages détaillées.", "Algunas de las citas principales, con sus páginas de detalle."),
  'venues' => array(
    array(
      'n' => $L("Festival di Cannes e Cannes Lions", "Cannes Film Festival and Cannes Lions", "Festival de Cannes et Cannes Lions", "Festival de Cannes y Cannes Lions"),
      'd' => $L("Cinema e comunicazione: accoglienza degli ospiti, gala, brand activation e personale multilingue.", "Film and advertising: guest reception, galas, brand activations and multilingual staff.", "Cinéma et communication : accueil des invités, galas, brand activations et personnel multilingue.", "Cine y comunicación: recepción de invitados, galas, activaciones de marca y personal multilingüe."),
      'link' => 'hostess-cannes-francia',
      'l'   => $L("Hostess e personale a Cannes", "Hostesses and staff in Cannes", "Hôtesses et personnel à Cannes", "Azafatas y personal en Cannes"),
    ),
    array(
      'n' => $L("MIPIM e MIPCOM", "MIPIM and MIPCOM", "MIPIM et MIPCOM", "MIPIM y MIPCOM"),
      'd' => $L("Due grandi appuntamenti professionali, dell'immobiliare e dei contenuti: accoglienza dei buyer internazionali e gestione dello stand.", "Two major professional gatherings, for real estate and content: international buyer reception and stand management.", "Deux grands rendez-vous professionnels, de l'immobilier et des contenus : accueil des acheteurs internationaux et gestion du stand.", "Dos grandes citas profesionales, del sector inmobiliario y de los contenidos: recepción de compradores internacionales y gestión del stand."),
    ),
    array(
      'n' => $L("Cannes Yachting Festival", "Cannes Yachting Festival", "Cannes Yachting Festival", "Cannes Yachting Festival"),
      'd' => $L("Il salone nautico: accoglienza agli stand e sui pontili, presentazione delle imbarcazioni, staff multilingue.", "The boat show: reception at stands and on the pontoons, boat presentation, multilingual staff.", "Le salon nautique : accueil sur les stands et sur les pontons, présentation des bateaux, personnel multilingue.", "El salón náutico: recepción en los stands y en los pantalanes, presentación de embarcaciones, personal multilingüe."),
    ),
    array(
      'n' => $L("Saint-Tropez", "Saint-Tropez", "Saint-Tropez", "Saint-Tropez"),
      'd' => $L("Eventi di brand e di rappresentanza sulla costa: accoglienza di ospiti e clienti.", "Brand and prestige events on the coast: guest and client reception.", "Événements de marque et de prestige sur la côte : accueil des invités et des clients.", "Eventos de marca y de representación en la costa: recepción de invitados y clientes."),
      'link' => 'hostess-saint-tropez-francia',
      'l'   => $L("Hostess a Saint-Tropez", "Hostesses in Saint-Tropez", "Hôtesses à Saint-Tropez", "Azafatas en Saint-Tropez"),
    ),
  ),
  's_eyebrow' => $L("I servizi", "Services", "Les services", "Los servicios"),
  's_heading' => $L("Tutto quello che serve per un evento sulla Costa Azzurra", "Everything you need for an event on the French Riviera", "Tout ce qu'il faut pour un événement sur la Côte d'Azur", "Todo lo que necesita un evento en la Costa Azul"),
  'x_eyebrow' => $L("Approfondimenti", "More on the Riviera", "Pour aller plus loin", "Más información"),
  'x_hostess' => array(
    'link' => 'hostess-cannes-francia',
    'n'    => $L("Hostess e personale a Cannes", "Hostesses and staff in Cannes", "Hôtesses et personnel à Cannes", "Azafatas y personal en Cannes"),
    'd'    => $L("La pagina TOAgency dedicata a hostess e steward: servizi, eventi e domande frequenti.", "The TOAgency page dedicated to hostesses and stewards: services, events and FAQ.", "La page TOAgency consacrée aux hôtesses et stewards : services, événements et questions fréquentes.", "La página de TOAgency dedicada a azafatas y stewards: servicios, eventos y preguntas frecuentes."),
  ),
  'f_eyebrow' => $L("Domande frequenti", "FAQ", "Questions fréquentes", "Preguntas frecuentes"),
  'f_heading' => $L("Eventi sulla Costa Azzurra: le domande più comuni", "Events on the French Riviera: common questions", "Événements sur la Côte d'Azur : les questions courantes", "Eventos en la Costa Azul: las preguntas más comunes"),
  'faq' => array(
    array(
      'q' => $L("Per quali eventi sulla Costa Azzurra fornite personale?", "Which events on the French Riviera do you provide staff for?", "Pour quels événements sur la Côte d'Azur fournissez-vous du personnel ?", "¿Para qué eventos en la Costa Azul proporcionáis personal?"),
      'a' => $L("Per festival, congressi e fiere, gala, eventi aziendali e attivazioni di brand. Dalla scelta delle figure alla gestione sul posto, ti segue un unico referente.", "For festivals, conferences and trade shows, galas, corporate events and brand activations. From choosing the roles to running things on site, a single contact looks after you.", "Pour les festivals, congrès et salons, galas, événements d'entreprise et activations de marque. Du choix des profils à la gestion sur place, un seul interlocuteur vous suit.", "Para festivales, congresos y ferias, galas, eventos corporativos y activaciones de marca. Desde la elección de los perfiles hasta la gestión en el lugar, te acompaña un único interlocutor."),
    ),
    array(
      'q' => $L("Quanto costa il personale per un evento sulla Costa Azzurra?", "How much does event staff cost on the French Riviera?", "Combien coûte le personnel pour un événement sur la Côte d'Azur ?", "¿Cuánto cuesta el personal para un evento en la Costa Azul?"),
      'a' => $L("Hostess, steward, promoter e supporto fiera: a partire da €159 + IVA a persona al giorno. Per camerieri, barman e altri profili qualificati dipende da mansioni, durata e livello di autonomia richiesto, e con più budget la scelta di profili si allarga. Se hai un budget diverso, indicalo pure e vediamo se possiamo venirti incontro.", "Hostesses, stewards, promoters and trade show support: from €159 per person per day. For waiters, bartenders and other qualified profiles it depends on the tasks, the duration and the autonomy required, and a bigger budget widens the choice of profiles. If your budget is different, just tell us and we'll see how we can accommodate you.", "Hôtesses, stewards, promoteurs et renfort sur salon : à partir de 159 € par personne et par jour. Pour les serveurs, barmans et autres profils qualifiés, cela dépend des missions, de la durée et de l'autonomie demandée, et un budget plus important élargit le choix des profils. Si votre budget est différent, dites-le-nous et nous verrons comment vous accompagner.", "Azafatas, stewards, promotores y apoyo en feria: desde 159 € por persona y día. Para camareros, barmans y otros perfiles cualificados depende de las tareas, la duración y la autonomía requerida, y con más presupuesto se amplía la elección de perfiles. Si tu presupuesto es distinto, indícalo y vemos cómo ayudarte."),
    ),
    array(
      'q' => $L("Come scegliete il personale sul posto?", "How do you choose the staff on site?", "Comment choisissez-vous le personnel sur place ?", "¿Cómo elegís al personal sobre el terreno?"),
      'a' => $L("Selezioniamo il personale giusto dal nostro database europeo e confermiamo la disponibilità prima di mandarti la proposta. Per i grandi eventi conviene prenotare con anticipo.", "We pick the right staff from our European database and confirm availability before sending you the proposal. For the big events it pays to book early.", "Nous sélectionnons le personnel qu'il faut dans notre base de données européenne et confirmons la disponibilité avant de vous envoyer la proposition. Pour les grands événements, mieux vaut réserver à l'avance.", "Seleccionamos al personal adecuado de nuestra base de datos europea y confirmamos la disponibilidad antes de enviarte la propuesta. Para los grandes eventos conviene reservar con antelación."),
    ),
    array(
      'q' => $L("Oltre a hostess e steward, cosa potete fornire?", "Besides hostesses and stewards, what can you provide?", "Au-delà des hôtesses et stewards, que pouvez-vous fournir ?", "Además de azafatas y stewards, ¿qué podéis proporcionar?"),
      'a' => $L("Barman e camerieri, foto e video, crew tecnica e logistica. I ruoli tecnici e di sicurezza sono affidati solo a personale qualificato e in regola; i trasporti li organizziamo con partner autorizzati.", "Bartenders and waiters, photo and video, technical crew and logistics. Technical and security roles go only to qualified, compliant staff; transport is arranged with authorised partners.", "Barmans et serveurs, photo et vidéo, équipes techniques et logistique. Les postes techniques et de sécurité ne sont confiés qu'à du personnel qualifié et en règle ; les transports sont organisés avec des partenaires autorisés.", "Barmans y camareros, foto y vídeo, equipo técnico y logística. Los puestos técnicos y de seguridad se confían solo a personal cualificado y en regla; el transporte lo organizamos con socios autorizados."),
    ),
    array(
      'q' => $L("Lo staff parla lingue straniere?", "Does the staff speak foreign languages?", "Le personnel parle-t-il des langues étrangères ?", "¿El personal habla idiomas?"),
      'a' => $L("Sì, abbiamo staff multilingue per buyer, ospiti e stampa internazionali. Gli interpreti sono disponibili su richiesta, in base a lingua e disponibilità.", "Yes, we have multilingual staff for international buyers, guests and press. Interpreters are available on request, depending on language and availability.", "Oui, nous avons du personnel multilingue pour les acheteurs, invités et la presse internationale. Des interprètes sont disponibles sur demande, selon la langue et la disponibilité.", "Sí, tenemos personal multilingüe para compradores, invitados y prensa internacionales. Los intérpretes están disponibles bajo petición, según el idioma y la disponibilidad."),
    ),
    array(
      'q' => $L("Chi gestisce contratti e pagamenti del personale?", "Who handles the staff's contracts and payments?", "Qui gère les contrats et les paiements du personnel ?", "¿Quién gestiona los contratos y pagos del personal?"),
      'a' => $L("Li gestiamo noi: contratti e compensi del personale, con una sola fattura per il cliente.", "We do: staff contracts and fees, with a single invoice for the client.", "C'est nous : contrats et rémunérations du personnel, avec une seule facture pour le client.", "Nos encargamos nosotros: contratos y honorarios del personal, con una sola factura para el cliente."),
    ),
    array(
      'q' => $L("Quanto tempo ci vuole per avere una proposta?", "How long does it take to get a proposal?", "Combien de temps faut-il pour recevoir une proposition ?", "¿Cuánto se tarda en recibir una propuesta?"),
      'a' => $L("Rispondiamo in genere entro 24 ore lavorative. Più dettagli ci dai (date, sede, numero di persone), più la proposta è precisa.", "We usually reply within 24 working hours. The more detail you give us (dates, venue, number of people), the more precise the proposal.", "Nous répondons en général sous 24 heures ouvrées. Plus vous nous donnez de détails (dates, lieu, nombre de personnes), plus la proposition est précise.", "Respondemos por lo general en 24 horas laborables. Cuantos más detalles nos des (fechas, sede, número de personas), más precisa será la propuesta."),
    ),
  ),
),

'montecarlo' => array(
  'slug'  => $L("montecarlo", "monte-carlo", "monte-carlo", "montecarlo"),
  'name'  => $L("Montecarlo", "Monte Carlo", "Monte-Carlo", "Montecarlo"),
  'seo_title' => $L("Staff per eventi a Montecarlo: Gran Premio, Yacht Show, gala | TOAgency", "Event staff in Monte Carlo: Grand Prix, Yacht Show, galas | TOAgency", "Personnel événementiel à Monte-Carlo : Grand Prix, Yacht Show, galas | TOAgency", "Personal para eventos en Montecarlo: Gran Premio, Yacht Show, galas | TOAgency"),
  'seo_desc' => $L("Hostess, steward, bar, foto e video, crew e logistica per eventi a Montecarlo: Gran Premio, Yacht Show, gala. Un referente, un preventivo.", "Hostesses, stewards, bar, photo and video, crew and logistics for events in Monte Carlo: Grand Prix, Yacht Show, galas. One contact, one quote.", "Hôtesses, stewards, bar, photo et vidéo, équipes et logistique pour événements à Monte-Carlo : Grand Prix, Yacht Show, galas. Un seul interlocuteur.", "Azafatas, stewards, bar, foto y vídeo, equipos y logística para eventos en Montecarlo: Gran Premio, Yacht Show, galas. Un interlocutor, un presupuesto."),
  'h1'    => $L("Staff e servizi per eventi a Montecarlo", "Event staff and services in Monte Carlo", "Personnel et services pour événements à Monte-Carlo", "Personal y servicios para eventos en Montecarlo"),
  'intro' => $L("Grandi eventi, fiere, congressi e gala nel Principato di Monaco: personale multilingue e un unico referente. Dalle hostess alla crew tecnica, un solo preventivo.", "Major events, trade shows, conferences and galas in the Principality of Monaco: multilingual staff and a single point of contact. From hostesses to technical crew, one quote.", "Grands événements, salons, congrès et galas dans la Principauté de Monaco : personnel multilingue et un seul interlocuteur. Des hôtesses aux équipes techniques, un seul devis.", "Grandes eventos, ferias, congresos y galas en el Principado de Mónaco: personal multilingüe y un único interlocutor. De las azafatas al equipo técnico, un único presupuesto."),
  // Tariffa di partenza (regola di Marco 19/10/2026). IVA solo nel testo italiano.
  'price' => $L("Hostess, steward e promoter a partire da €177 + IVA a persona al giorno. Altri profili su preventivo.", "Hostesses, stewards and promoters from €177 per person per day. Other profiles on request.", "Hôtesses, stewards et promoteurs à partir de 177 € par personne et par jour. Autres profils sur demande.", "Azafatas, stewards y promotores desde 177 € por persona y día. Otros perfiles bajo petición."),
  'v_eyebrow' => $L("Le sedi", "Venues", "Les lieux", "Las sedes"),
  'v_heading' => $L("Gli eventi a Montecarlo", "Events in Monte Carlo", "Les événements à Monte-Carlo", "Los eventos en Montecarlo"),
  'v_sub'     => $L("Alcuni degli appuntamenti principali del Principato.", "Some of the Principality's main events.", "Quelques-uns des grands rendez-vous de la Principauté.", "Algunas de las citas principales del Principado."),
  'venues' => array(
    array(
      'n' => $L("Gran Premio di Monaco", "Monaco Grand Prix", "Grand Prix de Monaco", "Gran Premio de Mónaco"),
      'd' => $L("Il grande appuntamento dell'anno: hospitality, accoglienza degli ospiti e attivazioni dei brand.", "The big event of the year: hospitality, guest reception and brand activations.", "Le grand rendez-vous de l'année : hospitalité, accueil des invités et activations de marque.", "La gran cita del año: hospitality, recepción de invitados y activaciones de marca."),
    ),
    array(
      'n' => $L("Monaco Yacht Show", "Monaco Yacht Show", "Monaco Yacht Show", "Monaco Yacht Show"),
      'd' => $L("Il salone dello yachting di lusso: accoglienza agli stand e sui pontili, staff multilingue.", "The luxury yachting show: reception at stands and on the pontoons, multilingual staff.", "Le salon du yachting de luxe : accueil sur les stands et sur les pontons, personnel multilingue.", "El salón del yachting de lujo: recepción en los stands y en los pantalanes, personal multilingüe."),
    ),
    array(
      'n' => $L("Top Marques Monaco", "Top Marques Monaco", "Top Marques Monaco", "Top Marques Monaco"),
      'd' => $L("Il salone delle supercar: accoglienza allo stand e presentazione dei modelli.", "The supercar show: stand reception and model presentation.", "Le salon des supercars : accueil sur le stand et présentation des modèles.", "El salón de los superdeportivos: recepción en el stand y presentación de modelos."),
    ),
    array(
      'n' => $L("Jumping International", "Jumping International", "Jumping International", "Jumping International"),
      'd' => $L("Il concorso ippico internazionale: hospitality e accoglienza degli ospiti.", "The international show jumping event: hospitality and guest reception.", "Le concours hippique international : hospitalité et accueil des invités.", "El concurso hípico internacional: hospitality y recepción de invitados."),
    ),
    array(
      'n' => $L("Gala e serate di rappresentanza", "Galas and prestige evenings", "Galas et soirées de prestige", "Galas y veladas de representación"),
      'd' => $L("Cene di gala ed eventi luxury: accoglienza, guardaroba, gestione degli ospiti.", "Gala dinners and luxury events: reception, cloakroom, guest handling.", "Dîners de gala et événements luxury : accueil, vestiaire, gestion des invités.", "Cenas de gala y eventos de lujo: recepción, guardarropa, gestión de invitados."),
    ),
  ),
  's_eyebrow' => $L("I servizi", "Services", "Les services", "Los servicios"),
  's_heading' => $L("Tutto quello che serve per un evento a Montecarlo", "Everything you need for an event in Monte Carlo", "Tout ce qu'il faut pour un événement à Monte-Carlo", "Todo lo que necesita un evento en Montecarlo"),
  'x_eyebrow' => $L("Approfondimenti", "More on Monte Carlo", "Pour aller plus loin", "Más información"),
  'x_hostess' => array(
    'link' => 'hostess-montecarlo',
    'n'    => $L("Hostess e personale a Montecarlo", "Hostesses and staff in Monte Carlo", "Hôtesses et personnel à Monte-Carlo", "Azafatas y personal en Montecarlo"),
    'd'    => $L("La pagina TOAgency dedicata a hostess e steward: servizi, eventi e domande frequenti.", "The TOAgency page dedicated to hostesses and stewards: services, events and FAQ.", "La page TOAgency consacrée aux hôtesses et stewards : services, événements et questions fréquentes.", "La página de TOAgency dedicada a azafatas y stewards: servicios, eventos y preguntas frecuentes."),
  ),
  'f_eyebrow' => $L("Domande frequenti", "FAQ", "Questions fréquentes", "Preguntas frecuentes"),
  'f_heading' => $L("Eventi a Montecarlo: le domande più comuni", "Events in Monte Carlo: common questions", "Événements à Monte-Carlo : les questions courantes", "Eventos en Montecarlo: las preguntas más comunes"),
  'faq' => array(
    array(
      'q' => $L("Per quali eventi a Montecarlo fornite personale?", "Which events in Monte Carlo do you provide staff for?", "Pour quels événements à Monte-Carlo fournissez-vous du personnel ?", "¿Para qué eventos en Montecarlo proporcionáis personal?"),
      'a' => $L("Per grandi eventi sportivi e di lusso, fiere, congressi, gala, eventi aziendali e attivazioni di brand. Dalla scelta delle figure alla gestione sul posto, ti segue un unico referente.", "For major sporting and luxury events, trade shows, conferences, galas, corporate events and brand activations. From choosing the roles to running things on site, a single contact looks after you.", "Pour les grands événements sportifs et de luxe, salons, congrès, galas, événements d'entreprise et activations de marque. Du choix des profils à la gestion sur place, un seul interlocuteur vous suit.", "Para grandes eventos deportivos y de lujo, ferias, congresos, galas, eventos corporativos y activaciones de marca. Desde la elección de los perfiles hasta la gestión en el lugar, te acompaña un único interlocutor."),
    ),
    array(
      'q' => $L("Quanto costa il personale per un evento a Montecarlo?", "How much does event staff cost in Monte Carlo?", "Combien coûte le personnel pour un événement à Monte-Carlo ?", "¿Cuánto cuesta el personal para un evento en Montecarlo?"),
      'a' => $L("Hostess, steward, promoter e supporto fiera: a partire da €177 + IVA a persona al giorno. Per camerieri, barman e altri profili qualificati dipende da mansioni, durata e livello di autonomia richiesto, e con più budget la scelta di profili si allarga. Se hai un budget diverso, indicalo pure e vediamo se possiamo venirti incontro.", "Hostesses, stewards, promoters and trade show support: from €177 per person per day. For waiters, bartenders and other qualified profiles it depends on the tasks, the duration and the autonomy required, and a bigger budget widens the choice of profiles. If your budget is different, just tell us and we'll see how we can accommodate you.", "Hôtesses, stewards, promoteurs et renfort sur salon : à partir de 177 € par personne et par jour. Pour les serveurs, barmans et autres profils qualifiés, cela dépend des missions, de la durée et de l'autonomie demandée, et un budget plus important élargit le choix des profils. Si votre budget est différent, dites-le-nous et nous verrons comment vous accompagner.", "Azafatas, stewards, promotores y apoyo en feria: desde 177 € por persona y día. Para camareros, barmans y otros perfiles cualificados depende de las tareas, la duración y la autonomía requerida, y con más presupuesto se amplía la elección de perfiles. Si tu presupuesto es distinto, indícalo y vemos cómo ayudarte."),
    ),
    array(
      'q' => $L("Come scegliete il personale sul posto?", "How do you choose the staff on site?", "Comment choisissez-vous le personnel sur place ?", "¿Cómo elegís al personal sobre el terreno?"),
      'a' => $L("Selezioniamo il personale giusto dal nostro database europeo e confermiamo la disponibilità prima di mandarti la proposta. Per i grandi eventi conviene prenotare con anticipo.", "We pick the right staff from our European database and confirm availability before sending you the proposal. For the big events it pays to book early.", "Nous sélectionnons le personnel qu'il faut dans notre base de données européenne et confirmons la disponibilité avant de vous envoyer la proposition. Pour les grands événements, mieux vaut réserver à l'avance.", "Seleccionamos al personal adecuado de nuestra base de datos europea y confirmamos la disponibilidad antes de enviarte la propuesta. Para los grandes eventos conviene reservar con antelación."),
    ),
    array(
      'q' => $L("Oltre a hostess e steward, cosa potete fornire?", "Besides hostesses and stewards, what can you provide?", "Au-delà des hôtesses et stewards, que pouvez-vous fournir ?", "Además de azafatas y stewards, ¿qué podéis proporcionar?"),
      'a' => $L("Barman e camerieri, foto e video, crew tecnica e logistica. I ruoli tecnici e di sicurezza sono affidati solo a personale qualificato e in regola; i trasporti li organizziamo con partner autorizzati.", "Bartenders and waiters, photo and video, technical crew and logistics. Technical and security roles go only to qualified, compliant staff; transport is arranged with authorised partners.", "Barmans et serveurs, photo et vidéo, équipes techniques et logistique. Les postes techniques et de sécurité ne sont confiés qu'à du personnel qualifié et en règle ; les transports sont organisés avec des partenaires autorisés.", "Barmans y camareros, foto y vídeo, equipo técnico y logística. Los puestos técnicos y de seguridad se confían solo a personal cualificado y en regla; el transporte lo organizamos con socios autorizados."),
    ),
    array(
      'q' => $L("Lo staff parla lingue straniere?", "Does the staff speak foreign languages?", "Le personnel parle-t-il des langues étrangères ?", "¿El personal habla idiomas?"),
      'a' => $L("Sì, abbiamo staff multilingue per buyer, ospiti e stampa internazionali. Gli interpreti sono disponibili su richiesta, in base a lingua e disponibilità.", "Yes, we have multilingual staff for international buyers, guests and press. Interpreters are available on request, depending on language and availability.", "Oui, nous avons du personnel multilingue pour les acheteurs, invités et la presse internationale. Des interprètes sont disponibles sur demande, selon la langue et la disponibilité.", "Sí, tenemos personal multilingüe para compradores, invitados y prensa internacionales. Los intérpretes están disponibles bajo petición, según el idioma y la disponibilidad."),
    ),
    array(
      'q' => $L("Chi gestisce contratti e pagamenti del personale?", "Who handles the staff's contracts and payments?", "Qui gère les contrats et les paiements du personnel ?", "¿Quién gestiona los contratos y pagos del personal?"),
      'a' => $L("Li gestiamo noi: contratti e compensi del personale, con una sola fattura per il cliente.", "We do: staff contracts and fees, with a single invoice for the client.", "C'est nous : contrats et rémunérations du personnel, avec une seule facture pour le client.", "Nos encargamos nosotros: contratos y honorarios del personal, con una sola factura para el cliente."),
    ),
    array(
      'q' => $L("Quanto tempo ci vuole per avere una proposta?", "How long does it take to get a proposal?", "Combien de temps faut-il pour recevoir une proposition ?", "¿Cuánto se tarda en recibir una propuesta?"),
      'a' => $L("Rispondiamo in genere entro 24 ore lavorative. Più dettagli ci dai (date, sede, numero di persone), più la proposta è precisa.", "We usually reply within 24 working hours. The more detail you give us (dates, venue, number of people), the more precise the proposal.", "Nous répondons en général sous 24 heures ouvrées. Plus vous nous donnez de détails (dates, lieu, nombre de personnes), plus la proposition est précise.", "Respondemos por lo general en 24 horas laborables. Cuantos más detalles nos des (fechas, sede, número de personas), más precisa será la propuesta."),
    ),
  ),
),

);
