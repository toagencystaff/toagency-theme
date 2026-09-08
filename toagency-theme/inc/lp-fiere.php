<?php
/**
 * inc/lp-fiere.php — Landing Ads dedicate a fiere ed eventi
 * TEMA LP-FIERE-QUALITY-SCORE — 2026-09-08 marco
 *
 * PERCHE' ESISTE
 * Le landing fiera sono 8 e ogni anno cambiano. Metterle come voci di $COPY dentro
 * page-landing-ads.php avrebbe voluto dire ~320 frasi tradotte da mantenere a mano
 * dentro un file gia' di 577 righe. Qui invece il testo COMUNE si scrive una volta sola
 * con i segnaposto, e ogni fiera e' una riga di dati piu' tre blocchi suoi.
 * Aggiungere la fiera n.9 = una voce in toa_lp_fiere_data().
 *
 * NON e' un template "a stampo" al 100%: ogni fiera ha un bullet suo, un paragrafo suo
 * e una lista di figure sua. E' la correzione chiesta in revisione: otto pagine identiche
 * salvo quattro variabili sono deboli proprio dove Google Ads guarda (pertinenza e utilita').
 *
 * CHIAVE
 * Si usa il meta _toa_ads_key della pagina, MAI lo slug: lo slug e' un indirizzo pubblico,
 * puo' prendere un suffisso -2 o essere modificato per sbaglio. Vedi page-landing-ads.php
 * righe 36-38: il meta viene gia' prima, lo slug e' solo il ripiego.
 *
 * CICLO DI VITA
 * Ogni fiera ha data_fine. Passata quella data il template smette DA SOLO di mostrare
 * le date e usa il sottotitolo neutro: niente "dal 22 al 25 settembre" online a dicembre.
 * Le pagine restano raggiungibili (i 404 fanno piu' danno): prima di cancellarle o
 * reindirizzarle vanno spente le campagne Google Ads che ci puntano.
 *
 * CONFIGURAZIONE INCOMPLETA
 * Se a una fiera mancano i campi obbligatori, toa_lp_fiera_copy() restituisce null e la
 * pagina ricade sulla landing generica invece di mostrare buchi o segnaposto a video.
 */

if (!defined('ABSPATH')) exit;

/* ------------------------------------------------------------------
 * 1) FIGURE — definite una volta, richiamate per nome da ogni fiera.
 * Le voci con 'img' diventano card con foto (assets/staff/), quelle
 * senza diventano pill di solo testo: e' la meccanica gia' esistente
 * in page-landing-ads.php (FIX 2026-09-02).
 * NB: il "runner tuttofare" NON e' in elenco di proposito. Una figura
 * generica a disposizione del cliente scivola nell'intermediazione di
 * manodopera (D.Lgs. 276/2003), che in TERMINI_CONDIZIONI_ANALISI.md e'
 * segnato come il rischio piu' alto dell'attivita'. Non reinserire senza
 * parere del consulente del lavoro.
 * ------------------------------------------------------------------ */
function toa_lp_fiere_ruoli() {
    return [
        'hostess'     => ['img'=>'hostess.jpg',    'label'=>['it'=>'Hostess da stand','en'=>'Stand hostesses','fr'=>'Hôtesses de stand','es'=>'Azafatas de stand']],
        'accoglienza' => ['img'=>'accoglienza.jpg','label'=>['it'=>'Accoglienza e reception','en'=>'Welcome desk','fr'=>'Accueil et réception','es'=>'Recepción y acogida']],
        'steward'     => ['img'=>'steward.jpg',    'label'=>['it'=>'Steward','en'=>'Stewards','fr'=>'Stewards','es'=>'Stewards']],
        'promoter'    => ['img'=>'promoter.jpg',   'label'=>['it'=>'Promoter','en'=>'Promoters','fr'=>'Promoteurs','es'=>'Promotores']],
        'interprete'  => ['img'=>'interprete.jpg', 'label'=>['it'=>'Interpreti','en'=>'Interpreters','fr'=>'Interprètes','es'=>'Intérpretes']],
        'foto'        => ['img'=>'fotografa.jpg',  'label'=>['it'=>'Fotografi','en'=>'Photographers','fr'=>'Photographes','es'=>'Fotógrafos']],
        'video'       => ['img'=>'videomaker.jpg', 'label'=>['it'=>'Videomaker','en'=>'Videomakers','fr'=>'Vidéastes','es'=>'Videógrafos']],
        'multilingue' => ['label'=>['it'=>'Hostess multilingue','en'=>'Multilingual hostesses','fr'=>'Hôtesses multilingues','es'=>'Azafatas multilingües']],
        'modelli'     => ['label'=>['it'=>'Modelli/e per presentazione prodotto','en'=>'Models for product presentation','fr'=>'Mannequins pour présentation produit','es'=>'Modelos para presentación de producto']],
        'speaker'     => ['label'=>['it'=>'Speaker e presentatori','en'=>'Speakers and presenters','fr'=>'Animateurs et présentateurs','es'=>'Presentadores']],
        'hospitality' => ['label'=>['it'=>'Hostess hospitality e aree VIP','en'=>'Hospitality and VIP hostesses','fr'=>'Hôtesses hospitality et espaces VIP','es'=>'Azafatas de hospitality y zonas VIP']],
        'showroom'    => ['label'=>['it'=>'Personale showroom','en'=>'Showroom staff','fr'=>'Personnel showroom','es'=>'Personal de showroom']],
    ];
}

/* ------------------------------------------------------------------
 * 2) TESTO COMUNE — scritto una volta sola, in 4 lingue.
 * Segnaposto: {NOME} {CITTA} {VENUE} {SPAZIO} {DATE}
 * ------------------------------------------------------------------ */
function toa_lp_fiere_comune() {
    return [
        'h1' => [
            'it' => 'Hostess, steward e interpreti per {NOME}, {CITTA}',
            'en' => 'Hostesses, stewards and interpreters for {NOME}, {CITTA}',
            'fr' => 'Hôtesses, stewards et interprètes pour {NOME}, {CITTA}',
            'es' => 'Azafatas, stewards e intérpretes para {NOME}, {CITTA}',
        ],
        // sottotitolo con le date: usato finche' la fiera non e' passata
        'sub' => [
            'it' => 'Personale selezionato per il tuo {SPAZIO} a {VENUE}, {DATE}. Ti mandiamo i profili in 24 ore, con foto e lingue parlate.',
            'en' => 'Selected staff for your {SPAZIO} at {VENUE}, {DATE}. We send you the profiles within 24 hours, with photos and languages spoken.',
            'fr' => 'Personnel sélectionné pour votre {SPAZIO} à {VENUE}, {DATE}. Nous vous envoyons les profils sous 24 heures, avec photos et langues parlées.',
            'es' => 'Personal seleccionado para tu {SPAZIO} en {VENUE}, {DATE}. Te enviamos los perfiles en 24 horas, con fotos e idiomas hablados.',
        ],
        // sottotitolo neutro: subentra da solo il giorno dopo la fine della fiera
        'sub_scaduta' => [
            'it' => 'Personale selezionato per il tuo {SPAZIO} a {VENUE}. Ti mandiamo i profili in 24 ore, con foto e lingue parlate.',
            'en' => 'Selected staff for your {SPAZIO} at {VENUE}. We send you the profiles within 24 hours, with photos and languages spoken.',
            'fr' => 'Personnel sélectionné pour votre {SPAZIO} à {VENUE}. Nous vous envoyons les profils sous 24 heures, avec photos et langues parlées.',
            'es' => 'Personal seleccionado para tu {SPAZIO} en {VENUE}. Te enviamos los perfiles en 24 horas, con fotos e idiomas hablados.',
        ],
        // i due bullet uguali per tutte
        'bul_fissi' => [
            'it' => ['20.000+ profili verificati nel database', 'Dal 2009, oltre 15 anni di fiere e congressi'],
            'en' => ['20,000+ verified profiles in our database', 'Since 2009, over 15 years of trade fairs and congresses'],
            'fr' => ['Plus de 20 000 profils vérifiés en base', 'Depuis 2009, plus de 15 ans de salons et congrès'],
            'es' => ['Más de 20.000 perfiles verificados en la base de datos', 'Desde 2009, más de 15 años de ferias y congresos'],
        ],
        // bullet geografico: solo citta' + Italia, per non impantanarsi
        // negli articoli delle regioni ("in tutto il Veneto" / "in tutta la Liguria")
        'bul_geo' => [
            'it' => 'Operativi a {CITTA} e in tutta Italia',
            'en' => 'Active in {CITTA} and throughout Italy',
            'fr' => 'Actifs à {CITTA} et dans toute l\'Italie',
            'es' => 'Operativos en {CITTA} y en toda Italia',
        ],
        'serv' => [
            'it' => 'Hostess e steward per l\'accoglienza, promoter per la raccolta contatti e la presentazione dei materiali, interpreti per i visitatori internazionali. Gestione completa: contratti, compensi e coordinamento sul posto. Un solo referente, una sola fattura.',
            'en' => 'Hostesses and stewards for the welcome desk, promoters for lead collection and material presentation, interpreters for international visitors. Full management: contracts, fees and on-site coordination. One contact, one invoice.',
            'fr' => 'Hôtesses et stewards pour l\'accueil, promoteurs pour la collecte de contacts et la présentation des supports, interprètes pour les visiteurs internationaux. Gestion complète : contrats, rémunérations et coordination sur place. Un seul interlocuteur, une seule facture.',
            'es' => 'Azafatas y stewards para la acogida, promotores para la captación de contactos y la presentación de materiales, intérpretes para los visitantes internacionales. Gestión completa: contratos, honorarios y coordinación in situ. Un solo interlocutor, una sola factura.',
        ],
    ];
}

/* ------------------------------------------------------------------
 * 3) LE FIERE — una riga per evento.
 * Campi obbligatori: nome, citta, venue, spazio, inizio, fine, bul, blocco, ruoli.
 * Date in formato Y-m-d. 'spazio' e' la parola che entra nel sottotitolo
 * ("il tuo stand" per le fiere, "il tuo evento" dove gli stand non ci sono).
 * ------------------------------------------------------------------ */
function toa_lp_fiere_data() {
    return [

    /* ---- 1. MARMOMAC — Verona, 22-25 settembre 2026 ---- */
    'marmomac-2026' => [
        'nome'   => 'Marmomac 2026',
        'citta'  => ['it'=>'Verona','en'=>'Verona','fr'=>'Vérone','es'=>'Verona'],
        'venue'  => 'Veronafiere',
        'spazio' => ['it'=>'stand','en'=>'stand','fr'=>'stand','es'=>'stand'],
        'inizio' => '2026-09-22',
        'fine'   => '2026-09-25',
        'bul' => [
            'it'=>'Interpreti per i buyer esteri: inglese, tedesco, francese, spagnolo, arabo',
            'en'=>'Interpreters for international buyers: English, German, French, Spanish, Arabic',
            'fr'=>'Interprètes pour les acheteurs étrangers : anglais, allemand, français, espagnol, arabe',
            'es'=>'Intérpretes para compradores extranjeros: inglés, alemán, francés, español, árabe',
        ],
        'blocco' => [
            'it'=>'Marmomac porta a Verona compratori da Medio Oriente, Nord America e Asia. Allo stand non serve solo chi accoglie: serve chi capisce di cosa parla il cliente e sa farlo in più lingue. Selezioniamo profili che hanno già lavorato in fiere tecniche e industriali, non soltanto in eventi moda.',
            'en'=>'Marmomac brings buyers from the Middle East, North America and Asia to Verona. A stand needs more than someone to greet visitors: it needs people who understand what the client is talking about, and can do it in several languages. We select profiles who have already worked technical and industrial fairs, not only fashion events.',
            'fr'=>'Marmomac attire à Vérone des acheteurs du Moyen-Orient, d\'Amérique du Nord et d\'Asie. Sur un stand, accueillir ne suffit pas : il faut des personnes qui comprennent le sujet du client et savent en parler en plusieurs langues. Nous sélectionnons des profils ayant déjà travaillé sur des salons techniques et industriels, pas seulement sur des événements mode.',
            'es'=>'Marmomac lleva a Verona compradores de Oriente Medio, Norteamérica y Asia. En un stand no basta con recibir: hace falta gente que entienda de qué habla el cliente y sepa hacerlo en varios idiomas. Seleccionamos perfiles que ya han trabajado en ferias técnicas e industriales, no solo en eventos de moda.',
        ],
        'ruoli' => ['hostess','interprete','steward','promoter','modelli','speaker','foto'],
    ],

    /* ---- 2. MILANO FASHION WEEK donna SS27 — Milano, 22-28 settembre 2026 ---- */
    'milano-fashion-week-2026' => [
        'nome'   => 'la Milano Fashion Week 2026',
        'citta'  => ['it'=>'Milano','en'=>'Milan','fr'=>'Milan','es'=>'Milán'],
        'venue'  => 'Milano',
        'spazio' => ['it'=>'evento','en'=>'event','fr'=>'événement','es'=>'evento'],
        'inizio' => '2026-09-22',
        'fine'   => '2026-09-28',
        'bul' => [
            'it'=>'Staff con esperienza vera in sfilate, showroom e press day',
            'en'=>'Staff with real experience in runway shows, showrooms and press days',
            'fr'=>'Personnel avec une vraie expérience des défilés, showrooms et press days',
            'es'=>'Personal con experiencia real en desfiles, showrooms y press days',
        ],
        'blocco' => [
            'it'=>'Durante la Fashion Week Milano cambia ritmo: sfilate, showroom, press day e serate si accavallano nella stessa settimana, spesso nello stesso pomeriggio. Serve personale che sappia gestire liste, accrediti e ospiti stampa senza farsi travolgere. Selezioniamo profili che quella settimana l\'hanno già fatta.',
            'en'=>'During Fashion Week the city changes pace: shows, showrooms, press days and evening events overlap within the same week, often the same afternoon. You need staff who can handle guest lists, accreditations and press without being overwhelmed. We select profiles who have already worked that week.',
            'fr'=>'Pendant la Fashion Week, Milan change de rythme : défilés, showrooms, press days et soirées se chevauchent sur une même semaine, souvent le même après-midi. Il faut du personnel capable de gérer listes, accréditations et invités presse sans se laisser déborder. Nous sélectionnons des profils qui ont déjà vécu cette semaine-là.',
            'es'=>'Durante la Fashion Week Milán cambia de ritmo: desfiles, showrooms, press days y fiestas se solapan en la misma semana, a menudo en la misma tarde. Hace falta personal capaz de gestionar listas, acreditaciones e invitados de prensa sin desbordarse. Seleccionamos perfiles que ya han vivido esa semana.',
        ],
        'ruoli' => ['accoglienza','steward','showroom','modelli','multilingue','promoter','foto'],
    ],

    /* ---- 3. SALONE NAUTICO 66° — Genova, 1-6 ottobre 2026 ---- */
    'salone-nautico-2026' => [
        'nome'   => 'il Salone Nautico 2026',
        'citta'  => ['it'=>'Genova','en'=>'Genoa','fr'=>'Gênes','es'=>'Génova'],
        'venue'  => ['it'=>'Genova, Waterfront di Levante','en'=>'Genoa, Waterfront di Levante','fr'=>'Gênes, Waterfront di Levante','es'=>'Génova, Waterfront di Levante'],
        'spazio' => ['it'=>'stand','en'=>'stand','fr'=>'stand','es'=>'stand'],
        'inizio' => '2026-10-01',
        'fine'   => '2026-10-06',
        'bul' => [
            'it'=>'Personale abituato a spazi all\'aperto e turni lunghi',
            'en'=>'Staff used to outdoor spaces and long shifts',
            'fr'=>'Personnel habitué aux espaces extérieurs et aux longues journées',
            'es'=>'Personal acostumbrado a espacios al aire libre y turnos largos',
        ],
        'blocco' => [
            'it'=>'Il Salone Nautico si svolge in gran parte all\'aperto, tra banchine e pontili, e dura sei giorni. Serve personale abituato a stare fuori, a turni lunghi e a un pubblico misto: armatori, famiglie e operatori del settore, spesso allo stesso stand nello stesso momento. Non è una fiera da padiglione.',
            'en'=>'The Genoa Boat Show takes place largely outdoors, along the docks and pontoons, and runs for six days. You need staff used to being outside, to long shifts, and to a mixed audience: boat owners, families and trade operators, often at the same stand at the same time. This is not an indoor fair.',
            'fr'=>'Le Salon Nautique de Gênes se déroule en grande partie en extérieur, le long des quais et des pontons, et dure six jours. Il faut du personnel habitué au plein air, aux longues journées et à un public mixte : propriétaires, familles et professionnels, souvent au même stand au même moment. Ce n\'est pas un salon de pavillon.',
            'es'=>'El Salón Náutico de Génova se desarrolla en gran parte al aire libre, entre muelles y pantalanes, y dura seis días. Hace falta personal acostumbrado a estar fuera, a turnos largos y a un público mixto: armadores, familias y profesionales, a menudo en el mismo stand a la vez. No es una feria de pabellón.',
        ],
        'ruoli' => ['hostess','steward','promoter','multilingue','modelli','foto'],
    ],

    /* ---- 4. TTG TRAVEL EXPERIENCE — Rimini, 14-16 ottobre 2026 ---- */
    'ttg-rimini-2026' => [
        'nome'   => 'TTG Travel Experience 2026',
        'citta'  => ['it'=>'Rimini','en'=>'Rimini','fr'=>'Rimini','es'=>'Rímini'],
        'venue'  => ['it'=>'Rimini Expo Centre','en'=>'Rimini Expo Centre','fr'=>'Rimini Expo Centre','es'=>'Rimini Expo Centre'],
        'spazio' => ['it'=>'stand','en'=>'stand','fr'=>'stand','es'=>'stand'],
        'inizio' => '2026-10-14',
        'fine'   => '2026-10-16',
        'bul' => [
            'it'=>'Hostess multilingue per buyer e operatori del turismo',
            'en'=>'Multilingual hostesses for travel buyers and operators',
            'fr'=>'Hôtesses multilingues pour acheteurs et professionnels du tourisme',
            'es'=>'Azafatas multilingües para compradores y operadores turísticos',
        ],
        'blocco' => [
            'it'=>'A TTG lo stand è soprattutto un banco appuntamenti: tre giorni di incontri programmati con tour operator, agenzie e destinazioni. Serve chi sa gestire un\'agenda fitta, accogliere il buyer giusto al momento giusto e non far saltare un solo appuntamento. Più organizzazione che immagine.',
            'en'=>'At TTG the stand is above all a meeting desk: three days of scheduled appointments with tour operators, agencies and destinations. You need people who can handle a packed agenda, welcome the right buyer at the right moment and let no appointment slip. More organisation than image.',
            'fr'=>'À TTG, le stand est avant tout un comptoir de rendez-vous : trois jours de réunions programmées avec tour-opérateurs, agences et destinations. Il faut des personnes capables de tenir un agenda serré, d\'accueillir le bon acheteur au bon moment et de ne manquer aucun rendez-vous. Plus d\'organisation que d\'image.',
            'es'=>'En TTG el stand es sobre todo un mostrador de citas: tres días de reuniones programadas con turoperadores, agencias y destinos. Hace falta gente capaz de gestionar una agenda apretada, recibir al comprador adecuado en el momento adecuado y no perder ni una cita. Más organización que imagen.',
        ],
        'ruoli' => ['hostess','multilingue','steward','promoter','interprete','foto'],
    ],

    /* ---- 5. CIBUS TEC — Parma, 27-30 ottobre 2026 ---- */
    'cibus-tec-2026' => [
        'nome'   => 'Cibus Tec 2026',
        'citta'  => ['it'=>'Parma','en'=>'Parma','fr'=>'Parme','es'=>'Parma'],
        'venue'  => ['it'=>'Fiere di Parma','en'=>'Fiere di Parma','fr'=>'Fiere di Parma','es'=>'Fiere di Parma'],
        'spazio' => ['it'=>'stand','en'=>'stand','fr'=>'stand','es'=>'stand'],
        'inizio' => '2026-10-27',
        'fine'   => '2026-10-30',
        'bul' => [
            'it'=>'Personale a suo agio tra macchinari in funzione e demo di prodotto',
            'en'=>'Staff at ease around working machinery and product demos',
            'fr'=>'Personnel à l\'aise parmi les machines en fonctionnement et les démonstrations',
            'es'=>'Personal cómodo entre maquinaria en funcionamiento y demos de producto',
        ],
        'blocco' => [
            'it'=>'Cibus Tec è una fiera di impianti e tecnologie per l\'alimentare: sullo stand ci sono macchine in funzione e tecnici che parlano con altri tecnici. Il personale deve saper accogliere, qualificare e smistare, lasciando ai vostri ingegneri le conversazioni tecniche. Formiamo lo staff sul vostro prodotto prima dell\'apertura, e gli chiediamo di non fingere competenze che non ha.',
            'en'=>'Cibus Tec is a fair of food processing plants and technology: there are working machines on the stand and engineers talking to engineers. Staff need to welcome, qualify and route visitors, leaving the technical conversations to your team. We brief the staff on your product before doors open, and we ask them not to fake expertise they do not have.',
            'fr'=>'Cibus Tec est un salon d\'équipements et de technologies pour l\'agroalimentaire : sur le stand, des machines tournent et des techniciens parlent à des techniciens. Le personnel doit accueillir, qualifier et orienter, en laissant les échanges techniques à vos ingénieurs. Nous formons le personnel sur votre produit avant l\'ouverture, et nous lui demandons de ne pas simuler des compétences qu\'il n\'a pas.',
            'es'=>'Cibus Tec es una feria de instalaciones y tecnología para la alimentación: en el stand hay máquinas funcionando y técnicos hablando con técnicos. El personal debe recibir, cualificar y derivar, dejando las conversaciones técnicas a vuestros ingenieros. Formamos al personal sobre vuestro producto antes de la apertura, y le pedimos que no finja competencias que no tiene.',
        ],
        'ruoli' => ['hostess','steward','promoter','interprete','multilingue','foto'],
    ],

    /* ---- 6. FIERACAVALLI 128a — Verona, 5-8 novembre 2026 ---- */
    'fieracavalli-2026' => [
        'nome'   => 'Fieracavalli 2026',
        'citta'  => ['it'=>'Verona','en'=>'Verona','fr'=>'Vérone','es'=>'Verona'],
        'venue'  => 'Veronafiere',
        'spazio' => ['it'=>'stand','en'=>'stand','fr'=>'stand','es'=>'stand'],
        'inizio' => '2026-11-05',
        'fine'   => '2026-11-08',
        'bul' => [
            'it'=>'Staff a proprio agio con gli animali e con un pubblico numeroso',
            'en'=>'Staff comfortable around animals and large crowds',
            'fr'=>'Personnel à l\'aise avec les animaux et un public nombreux',
            'es'=>'Personal cómodo con los animales y con mucho público',
        ],
        'blocco' => [
            'it'=>'Fieracavalli mescola business ed evento popolare: padiglioni espositivi di giorno, spettacoli la sera, e un pubblico di appassionati che arriva in famiglia. Il personale deve reggere quattro giorni molto affollati e sentirsi a proprio agio vicino agli animali — non è scontato, e lo verifichiamo prima di proporvi qualcuno.',
            'en'=>'Fieracavalli mixes business with a popular event: exhibition halls by day, shows in the evening, and an audience of enthusiasts who come as families. Staff need to handle four very crowded days and to be comfortable around animals — which is not a given, and which we check before proposing anyone.',
            'fr'=>'Fieracavalli mêle business et événement grand public : halls d\'exposition le jour, spectacles le soir, et un public de passionnés qui vient en famille. Le personnel doit tenir quatre journées très fréquentées et être à l\'aise près des animaux — ce n\'est pas évident, et nous le vérifions avant de vous proposer quelqu\'un.',
            'es'=>'Fieracavalli mezcla negocio y evento popular: pabellones de día, espectáculos por la noche y un público de aficionados que viene en familia. El personal debe aguantar cuatro días muy concurridos y sentirse cómodo cerca de los animales — algo que no se da por hecho y que verificamos antes de proponeros a nadie.',
        ],
        'ruoli' => ['hostess','steward','promoter','multilingue','modelli','foto'],
    ],

    /* ---- 7. EIMA INTERNATIONAL — Bologna, 10-14 novembre 2026 ---- */
    'eima-2026' => [
        'nome'   => 'EIMA International 2026',
        'citta'  => ['it'=>'Bologna','en'=>'Bologna','fr'=>'Bologne','es'=>'Bolonia'],
        'venue'  => ['it'=>'BolognaFiere','en'=>'BolognaFiere','fr'=>'BolognaFiere','es'=>'BolognaFiere'],
        'spazio' => ['it'=>'stand','en'=>'stand','fr'=>'stand','es'=>'stand'],
        'inizio' => '2026-11-10',
        'fine'   => '2026-11-14',
        'bul' => [
            'it'=>'Interpreti per le delegazioni estere: inglese, francese, spagnolo, tedesco',
            'en'=>'Interpreters for foreign delegations: English, French, Spanish, German',
            'fr'=>'Interprètes pour les délégations étrangères : anglais, français, espagnol, allemand',
            'es'=>'Intérpretes para delegaciones extranjeras: inglés, francés, español, alemán',
        ],
        'blocco' => [
            'it'=>'EIMA dura cinque giorni e porta a Bologna delegazioni di importatori da Africa, Sud America ed Est Europa. Gli stand sono grandi, spesso con mezzi in esposizione: serve personale che regga la distanza, sappia orientare il visitatore dentro uno spazio ampio e indirizzare quello giusto al commerciale giusto.',
            'en'=>'EIMA runs for five days and brings delegations of importers from Africa, South America and Eastern Europe to Bologna. Stands are large, often with machinery on display: you need staff who can go the distance, guide visitors around a wide space and route the right one to the right salesperson.',
            'fr'=>'EIMA dure cinq jours et amène à Bologne des délégations d\'importateurs d\'Afrique, d\'Amérique du Sud et d\'Europe de l\'Est. Les stands sont vastes, souvent avec des engins exposés : il faut du personnel qui tienne la distance, sache orienter le visiteur dans un grand espace et diriger le bon interlocuteur vers le bon commercial.',
            'es'=>'EIMA dura cinco días y lleva a Bolonia delegaciones de importadores de África, Sudamérica y Europa del Este. Los stands son grandes, a menudo con maquinaria expuesta: hace falta personal que aguante la distancia, sepa orientar al visitante en un espacio amplio y derivar al adecuado al comercial adecuado.',
        ],
        'ruoli' => ['hostess','interprete','steward','promoter','multilingue','foto'],
    ],

    /* ---- 8. NITTO ATP FINALS — Torino, 15-22 novembre 2026 ---- */
    'atp-finals-torino-2026' => [
        'nome'   => 'le Nitto ATP Finals 2026',
        'citta'  => ['it'=>'Torino','en'=>'Turin','fr'=>'Turin','es'=>'Turín'],
        'venue'  => ['it'=>'Torino','en'=>'Turin','fr'=>'Turin','es'=>'Turín'],
        'spazio' => ['it'=>'evento','en'=>'event','fr'=>'événement','es'=>'evento'],
        'inizio' => '2026-11-15',
        'fine'   => '2026-11-22',
        'bul' => [
            'it'=>'Staff per hospitality, aree VIP e attivazioni di sponsor',
            'en'=>'Staff for hospitality, VIP areas and sponsor activations',
            'fr'=>'Personnel pour hospitality, espaces VIP et activations sponsors',
            'es'=>'Personal para hospitality, zonas VIP y activaciones de patrocinadores',
        ],
        'blocco' => [
            'it'=>'Alle ATP Finals non ci sono stand fieristici: ci sono aree hospitality, lounge degli sponsor e attivazioni in città, per otto giorni di fila. Serve personale abituato al pubblico corporate e agli ospiti internazionali, con un inglese vero e una presenza curata. Siamo un\'agenzia di Torino: qui il coordinamento ce l\'abbiamo sotto casa.',
            'en'=>'At the ATP Finals there are no exhibition stands: there are hospitality areas, sponsor lounges and city activations, for eight days straight. You need staff used to corporate audiences and international guests, with real English and a polished presence. We are a Turin agency: here, coordination happens on our doorstep.',
            'fr'=>'Aux ATP Finals, il n\'y a pas de stands : il y a des espaces hospitality, des lounges sponsors et des activations en ville, huit jours d\'affilée. Il faut du personnel habitué au public corporate et aux invités internationaux, avec un vrai niveau d\'anglais et une présence soignée. Nous sommes une agence de Turin : ici, la coordination se fait à notre porte.',
            'es'=>'En las ATP Finals no hay stands feriales: hay zonas de hospitality, lounges de patrocinadores y activaciones por la ciudad, ocho días seguidos. Hace falta personal acostumbrado al público corporativo y a los invitados internacionales, con un inglés real y una presencia cuidada. Somos una agencia de Turín: aquí la coordinación la tenemos al lado de casa.',
        ],
        'ruoli' => ['hospitality','accoglienza','steward','multilingue','promoter','modelli','foto'],
    ],

    ];
}

/* ------------------------------------------------------------------
 * 4) Intervallo di date leggibile, nelle 4 lingue.
 * "dal 22 al 25 settembre 2026" / "22-25 September 2026" ecc.
 * Gestisce anche le fiere a cavallo di due mesi (es. 30 set - 2 ott).
 * ------------------------------------------------------------------ */
function toa_lp_fiera_date($inizio, $fine, $lang) {
    $mesi = [
        'it' => ['gennaio','febbraio','marzo','aprile','maggio','giugno','luglio','agosto','settembre','ottobre','novembre','dicembre'],
        'en' => ['January','February','March','April','May','June','July','August','September','October','November','December'],
        'fr' => ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'],
        'es' => ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'],
    ];
    if (!isset($mesi[$lang])) $lang = 'it';

    $t1 = strtotime($inizio);
    $t2 = strtotime($fine);
    if (!$t1 || !$t2) return '';

    $g1 = (int) date('j', $t1); $m1 = (int) date('n', $t1);
    $g2 = (int) date('j', $t2); $m2 = (int) date('n', $t2);
    $anno = date('Y', $t2);
    $M1 = $mesi[$lang][$m1 - 1];
    $M2 = $mesi[$lang][$m2 - 1];
    $stesso_mese = ($m1 === $m2);

    switch ($lang) {
        case 'en':
            return $stesso_mese
                ? "{$g1}–{$g2} {$M2} {$anno}"
                : "{$g1} {$M1} – {$g2} {$M2} {$anno}";
        case 'fr':
            return $stesso_mese
                ? "du {$g1} au {$g2} {$M2} {$anno}"
                : "du {$g1} {$M1} au {$g2} {$M2} {$anno}";
        case 'es':
            return $stesso_mese
                ? "del {$g1} al {$g2} de {$M2} de {$anno}"
                : "del {$g1} de {$M1} al {$g2} de {$M2} de {$anno}";
        default:
            return $stesso_mese
                ? "dal {$g1} al {$g2} {$M2} {$anno}"
                : "dal {$g1} {$M1} al {$g2} {$M2} {$anno}";
    }
}

/* ------------------------------------------------------------------
 * 5) Testi pronti per il template.
 * Restituisce stringhe GIA' risolte nella lingua richiesta, oppure null
 * se la chiave non e' una fiera o se la configurazione e' incompleta
 * (in quel caso la pagina ricade sulla landing generica, che e' meglio
 * di una pagina con i segnaposto a video).
 * ------------------------------------------------------------------ */
function toa_lp_fiera_copy($key, $lang) {
    $fiere = toa_lp_fiere_data();
    if (!isset($fiere[$key])) return null;

    $f = $fiere[$key];
    if (!in_array($lang, ['it','en','fr','es'], true)) $lang = 'it';

    // controllo campi obbligatori: se ne manca uno, meglio la landing generica
    foreach (['nome','citta','venue','spazio','inizio','fine','bul','blocco','ruoli'] as $campo) {
        if (empty($f[$campo])) return null;
    }

    $L = function ($v) use ($lang) {
        // i campi possono essere una stringa sola (uguale in tutte le lingue)
        // oppure un array per lingua
        if (is_array($v)) return $v[$lang] ?? $v['it'] ?? '';
        return (string) $v;
    };

    $com   = toa_lp_fiere_comune();
    $nome  = $L($f['nome']);
    $citta = $L($f['citta']);
    $venue = $L($f['venue']);
    $spaz  = $L($f['spazio']);

    // ciclo di vita: dopo la fine della fiera niente piu' date a video
    $scaduta = (strtotime($f['fine'] . ' 23:59:59') < current_time('timestamp'));
    $date    = $scaduta ? '' : toa_lp_fiera_date($f['inizio'], $f['fine'], $lang);

    $sub_tpl = $scaduta ? $com['sub_scaduta'][$lang] : $com['sub'][$lang];

    $sost = [
        '{NOME}'   => $nome,
        '{CITTA}'  => $citta,
        '{VENUE}'  => $venue,
        '{SPAZIO}' => $spaz,
        '{DATE}'   => $date,
    ];
    $riempi = function ($tpl) use ($sost) {
        return trim(strtr($tpl, $sost));
    };

    // bullet: 2 fissi + 1 specifico della fiera + 1 geografico
    $bullets = $com['bul_fissi'][$lang];
    $bullets[] = $L($f['bul']);
    $bullets[] = $riempi($com['bul_geo'][$lang]);

    // figure: dai nomi alle definizioni complete, scartando quelle sconosciute
    $catalogo = toa_lp_fiere_ruoli();
    $ruoli = [];
    foreach ($f['ruoli'] as $nome_ruolo) {
        if (isset($catalogo[$nome_ruolo])) $ruoli[] = $catalogo[$nome_ruolo];
    }

    return [
        'h1'      => $riempi($com['h1'][$lang]),
        'sub'     => $riempi($sub_tpl),
        'bul'     => $bullets,
        'serv'    => $com['serv'][$lang],
        'blocco'  => $L($f['blocco']),
        'ruoli'   => $ruoli,
        'scaduta' => $scaduta,
    ];
}
