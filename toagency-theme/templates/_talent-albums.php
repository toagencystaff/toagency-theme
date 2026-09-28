<?php
/**
 * _talent-albums.php — DEFINIZIONE UNICA degli album foto talent (nomi, a cosa servono, esempi, ruoli).
 * FIX 2026-09-22 marco (chat CRM - EVENTS DATABASE): estratta da page-registrati-talent.php e condivisa
 * con page-talent-self-edit.php, cosi' registrazione e modifica-scheda parlano la STESSA lingua.
 * Cambi un testo qui -> cambia in entrambe le pagine. Nessun output: definisce solo 3 array.
 */
if (!defined('ABSPATH')) exit;
// ─────────────────────────────────────────────────────────────────────
// 2026-08-14 (TEMA REGISTRAZIONE TALENT) — ALBUM FOTO PER RUOLO
// Mappa confermata dalla chat CRM EVENTI HOSTESS ANALISI (14/08):
//   model → polaroid + portfolio + dettaglio · actor → polaroid + portfolio
//   hostess → polaroid + eventi · tutti gli altri → solo polaroid
//   casual → sempre disponibile, facoltativo per tutti
// portfolio_cinema e archivio NON vanno MAI esposti nel form pubblico.
// 'roles' = '*' significa: sempre visibile, qualunque ruolo.
// Le immagini guida vivono in assets/guide/esempio-<album>-si.jpg e -no.jpg:
// finché non ci sono, la card mostra un segnaposto (nessun errore, nessun buco).
// ─────────────────────────────────────────────────────────────────────
$TALENT_ALBUM = array(
    array(
        'code' => 'polaroid', 'roles' => '*', 'req' => true, 'video' => 'video_creator',
        'clou' => array(
            'it'=>'⭐ <strong>L\'album più importante. E te lo fai da solo:</strong> telefono, muro chiaro, luce di finestra. Gratis, in cinque minuti.',
            'en'=>'⭐ <strong>The most important album. And you do it yourself:</strong> phone, plain wall, window light. Free, in five minutes.',
            'fr'=>'⭐ <strong>L\'album le plus important. Et tu le fais tout seul :</strong> téléphone, mur clair, lumière de fenêtre. Gratuit, en cinq minutes.',
            'es'=>'⭐ <strong>El álbum más importante. Y te lo haces tú:</strong> móvil, pared clara, luz de ventana. Gratis, en cinco minutos.',
        ),
        'label' => array('it'=>'Pola e presentazione','en'=>'Polaroids','fr'=>'Polas','es'=>'Polas'),
        'quante' => array(
            'it'=>'Da 3 a 8 foto: primo piano, mezzo busto, figura intera, profilo. Sempre senza filtri.',
            'en'=>'3 to 8 photos: close-up, chest-up, full length, profile. Always without filters.',
            'fr'=>'De 3 à 8 photos : gros plan, buste, plein pied, profil. Toujours sans filtres.',
            'es'=>'De 3 a 8 fotos: primer plano, medio cuerpo, cuerpo entero, perfil. Siempre sin filtros.',
        ),
        'hint'  => array(
            'it'=>'Tu come sei: niente trucco, niente filtri. Primo piano e figura intera. Su ogni foto indica mese e anno dello scatto.',
            'en'=>'You as you are: no makeup, no filters. Close-up and full body. On each photo add the month and year it was taken.',
            'fr'=>'Toi tel que tu es : sans maquillage ni filtres. Gros plan et plein pied. Sur chaque photo indique le mois et l\'année de la prise de vue.',
            'es'=>'Tú tal cual eres: sin maquillaje ni filtros. Primer plano y cuerpo entero. En cada foto indica el mes y el año en que la hiciste.',
        ),
    ),
    array(
        // 2026-08-17 (decisione di Marco): il book moda NON è richiesto agli attori.
        // Prima era 'model,actor' perché così lo conta il motore del CRM: allineamento chiesto alla chat CRM.
        'code' => 'portfolio', 'roles' => 'model', 'req' => true, 'video' => 'video_creator',
        'label' => array('it'=>'Portfolio moda','en'=>'Fashion portfolio','fr'=>'Portfolio mode','es'=>'Portfolio moda'),
        'quante' => array(
            'it'=>'Da 3 a 8 foto: un primo piano, una figura intera, un tre quarti, una ambientata.',
            'en'=>'3 to 8 photos: a close-up, a full length, a three-quarter, one on location.',
            'fr'=>'De 3 à 8 photos : un gros plan, un plein pied, un trois-quarts, une en situation.',
            'es'=>'De 3 a 8 fotos: un primer plano, un cuerpo entero, un tres cuartos, una ambientada.',
        ),
        'hint'  => array(
            'it'=>'Solo scatti fatti da un fotografo. Se non li hai, salta questo album: mettici Pola e Altre foto, contano lo stesso.',
            'en'=>'Photographer shots only. If you have none, skip this album: use Polaroids and Other photos, they count too.',
            'fr'=>'Uniquement des photos de photographe. Si tu n\'en as pas, saute cet album : mets des Polas et Autres photos, elles comptent aussi.',
            'es'=>'Solo fotos de fotógrafo. Si no tienes, salta este álbum: pon Polas y Otras fotos, también cuentan.',
        ),
    ),
    array(
        // 2026-08-14 — album dedicato agli attori (album CRM: portfolio_cinema).
        // Il CRM oggi NON lo conta nella % completamento: per gli attori pesa 'portfolio'.
        // Qui è facoltativo apposta, così non promettiamo punti che il backend non dà.
        'code' => 'portfolio_cinema', 'roles' => 'actor', 'req' => true, 'video' => 'video_selftape',
        'label' => array('it'=>'Portfolio attore','en'=>'Acting portfolio','fr'=>'Portfolio comédien','es'=>'Portfolio actor'),
        'quante' => array(
            'it'=>'Da 3 a 8 foto: primo piano espressivo, mezzo busto, una in scena o sul set.',
            'en'=>'3 to 8 photos: expressive close-up, chest-up, one on set or in character.',
            'fr'=>'De 3 à 8 photos : gros plan expressif, buste, une en scène ou sur le plateau.',
            'es'=>'De 3 a 8 fotos: primer plano expresivo, medio cuerpo, una en escena o en el set.',
        ),
        'hint'  => array(
            'it'=>'Book attoriale o fotogrammi dei tuoi lavori: primo piano espressivo, mezzo busto, luce naturale.',
            'en'=>'Acting book or frames from your work: expressive close-up, chest-up, natural light.',
            'fr'=>'Book comédien ou images de tes travaux : gros plan expressif, buste, lumière naturelle.',
            'es'=>'Book actoral o fotogramas de tus trabajos: primer plano expresivo, medio cuerpo, luz natural.',
        ),
    ),
    array(
        // 2026-08-15 — album degli UGC creator: qui contano i VIDEO, non le foto.
        // album_tipo lato CRM: video_creator (da confermare con la chat CRM VIDEO-ALBUM).
        'code' => 'ugc', 'roles' => 'ugc_creator,influencer', 'req' => true, 'video' => 'video_creator',
        'descrizione' => array(
            'it'=>'<strong>Com\'è fatto un video UGC che funziona.</strong> Verticale, col telefono. Faccia in luce, voce chiara: parli tu, non la musica. Un\'idea sola, detta come a un amico. Prodotto in mano, girato piano. <strong>Mai watermark, @ o loghi social</strong>: con la firma non possiamo proporlo.',
            'en'=>'<strong>What a UGC video that works looks like.</strong> Vertical, on your phone. Face in the light, clear voice: you talk, not the music. One idea, said like you would to a friend. Product in hand, turned slowly. <strong>Never a watermark, @handle or social logo</strong>: with a signature we cannot offer it.',
            'fr'=>'<strong>À quoi ressemble une vidéo UGC qui marche.</strong> Verticale, au téléphone. Visage éclairé, voix claire : c\'est toi qui parles, pas la musique. Une seule idée, dite comme à un ami. Produit en main, tourné lentement. <strong>Jamais de filigrane, de @ ni de logo social</strong> : avec une signature on ne peut pas la proposer.',
            'es'=>'<strong>Cómo es un vídeo UGC que funciona.</strong> Vertical, con el móvil. Cara iluminada, voz clara: hablas tú, no la música. Una sola idea, dicha como a un amigo. Producto en la mano, girado despacio. <strong>Nunca marca de agua, @ ni logos sociales</strong>: con firma no podemos ofrecerlo.',
        ),
        'label' => array('it'=>'Contenuti UGC','en'=>'UGC content','fr'=>'Contenus UGC','es'=>'Contenidos UGC'),
        'clou' => array(
            'it'=>'⭐ <strong>Per un UGC creator contano i video, non le foto.</strong> Sono quelli che i brand guardano. Bastano il telefono e casa tua.',
            'en'=>'⭐ <strong>For a UGC creator the videos matter, not the photos.</strong> They are what brands watch. Your phone and your home are enough.',
            'fr'=>'⭐ <strong>Pour un créateur UGC ce sont les vidéos qui comptent, pas les photos.</strong> C\'est ce que les marques regardent. Ton téléphone et chez toi suffisent.',
            'es'=>'⭐ <strong>Para un creador UGC cuentan los vídeos, no las fotos.</strong> Son los que miran las marcas. Basta tu móvil y tu casa.',
        ),
        'quante' => array(
            'it'=>'Da 2 a 5 video: parli in camera · prodotto in mano · come si usa. Mai watermark, @ o loghi social.',
            'en'=>'2 to 5 videos: talking to camera · product in hand · how it is used. Never a watermark, @handle or social logo.',
            'fr'=>'De 2 à 5 vidéos : face caméra · produit en main · comment on l\'utilise. Jamais de filigrane, de @ ni de logo social.',
            'es'=>'De 2 a 5 vídeos: hablando a cámara · producto en la mano · cómo se usa. Nunca marca de agua, @ ni logos sociales.',
        ),
        'hint'  => array(
            'it'=>'Verticali, col telefono, voce chiara. Vanno bene anche contenuti già fatti per te o per altri brand.',
            'en'=>'Vertical, on your phone, clear voice. Content you already made for yourself or other brands works too.',
            'fr'=>'Verticales, au téléphone, voix claire. Les contenus déjà faits pour toi ou d\'autres marques conviennent aussi.',
            'es'=>'Verticales, con el móvil, voz clara. También valen contenidos ya hechos para ti o para otras marcas.',
        ),
    ),
    array(
        'code' => 'dettaglio', 'roles' => 'model', 'req' => true,
        'label' => array('it'=>'Dettagli','en'=>'Details','fr'=>'Détails','es'=>'Detalles'),
        'quante' => array(
            'it'=>'Da 3 a 8 foto: mani, profilo, capelli, sorriso. Primi piani puliti.',
            'en'=>'3 to 8 photos: hands, profile, hair, smile. Clean close-ups.',
            'fr'=>'De 3 à 8 photos : mains, profil, cheveux, sourire. Gros plans nets.',
            'es'=>'De 3 a 8 fotos: manos, perfil, pelo, sonrisa. Primeros planos limpios.',
        ),
        'hint'  => array(
            'it'=>'Mani, profilo, capelli, sorriso. Primi piani puliti su sfondo neutro: i casting moda li chiedono sempre.',
            'en'=>'Hands, profile, hair, smile. Clean close-ups on a neutral background: fashion castings always ask for them.',
            'fr'=>'Mains, profil, cheveux, sourire. Gros plans nets sur fond neutre : les castings mode les demandent toujours.',
            'es'=>'Manos, perfil, pelo, sonrisa. Primeros planos limpios sobre fondo neutro: los castings de moda siempre los piden.',
        ),
    ),
    array(
        'code' => 'eventi', 'roles' => 'hostess', 'req' => true,
        'label' => array('it'=>'Fiere e eventi','en'=>'Trade fairs & events','fr'=>'Salons et événements','es'=>'Ferias y eventos'),
        // FIX 2026-09-22 marco — regole foto eventi di Marco: tailleur/camicia elegante, trucco leggero, sguardo in camera, selfie ok; sportivo per ombrellina/motori
        'quante' => array(
            'it'=>'Da 3 a 8 foto: un primo piano elegante, una figura intera in tailleur (o camicia elegante) e, se fai eventi sportivi, motori o ombrellina, una in stile sportivo.',
            'en'=>'3 to 8 photos: a smart close-up, a full-length shot in a suit (or smart shirt) and, if you do sports, motor-show or umbrella-girl events, a sporty one.',
            'fr'=>'De 3 à 8 photos : un gros plan élégant, un plein pied en tailleur (ou chemise élégante) et, si tu fais des événements sportifs, moteurs ou ombrelle, une en style sportif.',
            'es'=>'De 3 a 8 fotos: un primer plano elegante, un cuerpo entero en traje (o camisa elegante) y, si haces eventos deportivos, de motor o de paraguas, una deportiva.',
        ),
        'hint'  => array(
            'it'=>'Come ti presenteresti a una fiera: trucco leggero, sguardo in camera, tailleur o camicia elegante. Va bene anche un selfie o una foto col telefono, basta che sia luminosa e nitida. Perfette le foto mentre lavori come hostess o steward.',
            'en'=>'How you would show up at a trade fair: light makeup, looking at the camera, suit or smart shirt. A selfie or phone photo is fine too, as long as it is bright and sharp. Photos of you working as a hostess or steward are perfect.',
            'fr'=>'Comme tu te présenterais à un salon : maquillage léger, regard vers l\'objectif, tailleur ou chemise élégante. Un selfie ou une photo au téléphone convient aussi, si elle est lumineuse et nette. Les photos pendant que tu travailles comme hôtesse ou steward sont parfaites.',
            'es'=>'Como te presentarías en una feria: maquillaje ligero, mirada a cámara, traje o camisa elegante. También vale un selfie o una foto con el móvil, si es luminosa y nítida. Las fotos trabajando como azafata o steward son perfectas.',
        ),
    ),
    array(
        'code' => 'casual', 'roles' => '*', 'req' => false,
        'label' => array('it'=>'Altre foto (non pro)','en'=>'Other photos (not pro)','fr'=>'Autres photos (non pro)','es'=>'Otras fotos (no pro)'),
        'quante' => array(
            'it'=>'Da 3 a 8 foto: varia le situazioni, basta che si veda bene chi sei.',
            'en'=>'3 to 8 photos: vary the situations, as long as you\'re clearly visible.',
            'fr'=>'De 3 à 8 photos : varie les situations, du moment qu\'on te voit bien.',
            'es'=>'De 3 a 8 fotos: varía las situaciones, con que se te vea bien basta.',
        ),
        'hint'  => array(
            'it'=>'Foto col telefono, in vacanza, con gli amici: qui vanno benissimo. Caricarle alza il tuo profilo, non lo abbassa.',
            'en'=>'Phone photos, holidays, with friends: perfect here. Uploading them raises your profile, it does not lower it.',
            'fr'=>'Photos au téléphone, en vacances, entre amis : ici c\'est parfait. Les charger fait monter ton profil, pas l\'inverse.',
            'es'=>'Fotos con el móvil, de vacaciones, con amigos: aquí van perfectas. Subirlas sube tu perfil, no lo baja.',
        ),
    ),
);

// 2026-08-14 — Immagini che scorrono dentro ogni card, stesso meccanismo della galleria del selfie
// (classi .toa-foto-gallery/.toa-fg-slide già definite più sotto). Formato: array(file, 1=così sì | 0=così no).
// I file stanno in assets/. Album senza immagini → la card mostra un segnaposto, nessun errore.
// Due colonne affiancate: a sinistra scorrono i "così sì", a destra i "così no".
// Percorso che inizia con "/" = file del sito (media WP), altrimenti = toagency-theme/assets/.
// Le Pola vengono dall'articolo del blog "Foto Polaroid per Agenzie di Modelli" (9 immagini).
$TALENT_ALBUM_SLIDES = array(
    // COPPIE: ogni riga è array(immagine "così sì", immagine "così no").
    // Le due colonne scorrono INSIEME, così il confronto è sempre sullo stesso soggetto
    // (le mani giuste accanto alle mani sbagliate) e, dove si riconosce, uomo con uomo e donna con donna.
    // Se la seconda immagine è a sua volta un "sì", si scrive 'si' come terzo elemento: la
    // fascia sotto diventa verde anche a destra.
    // Percorso che inizia con "/" = media del sito, altrimenti = toagency-theme/assets/.
    'polaroid' => array(
        array('/wp-content/uploads/2026/06/image3-3.jpg', 'guide/no-occhiali.jpg'),
        array('/wp-content/uploads/2026/06/image5-3.jpg', 'guide/no-filtro.jpg'),
        array('/wp-content/uploads/2026/06/image6-3.jpg', 'guide/no-selfie-vicino.jpg'),
        array('/wp-content/uploads/2026/06/image7-4.jpg', 'guide/no-ritagliata.jpg'),
        array('/wp-content/uploads/2026/06/image9-4.jpg', 'guide/no-posa.jpg'),
    ),
    'portfolio' => array(
        array('guide/pf-moda-01.jpg', 'guide/no-filtro.jpg'),
        array('guide/pf-moda-06.jpg', 'guide/no-palestra.jpg'),
        array('guide/pf-moda-02.jpg', 'guide/no-ritagliata.jpg'),
        array('guide/pf-moda-07.jpg', 'guide/no-occhiali.jpg'),
        array('guide/pf-moda-03.jpg', 'guide/no-selfie-vicino.jpg'),
        array('guide/pf-moda-08.jpg', 'guide/no-spiaggia.jpg'),
        array('guide/pf-moda-04.jpg', 'guide/no-posa.jpg'),
        array('guide/pf-moda-09.jpg', 'guide/no-sport.jpg'),
        array('guide/pf-moda-05.jpg', 'guide/no-discoteca.jpg'),
        array('guide/pf-moda-10.jpg', 'guide/no-spalle.jpg'),
    ),
    'portfolio_cinema' => array(
        array('guide/pf-attore-01.jpg', 'guide/no-filtro.jpg'),
        array('guide/pf-attore-07.jpg', 'guide/no-occhiali.jpg'),
        array('guide/pf-attore-02.jpg', 'guide/no-ritagliata.jpg'),
        array('guide/pf-attore-08.jpg', 'guide/no-palestra.jpg'),
        array('guide/pf-attore-03.jpg', 'guide/no-selfie-vicino.jpg'),
        array('guide/pf-attore-09.jpg', 'guide/no-spiaggia.jpg'),
        array('guide/pf-attore-04.jpg', 'guide/no-posa.jpg'),
        array('guide/pf-attore-10.jpg', 'guide/no-sport.jpg'),
        array('guide/pf-attore-05.jpg', 'guide/no-discoteca.jpg'),
        array('guide/pf-attore-11.jpg', 'guide/no-spalle.jpg'),
        array('guide/pf-attore-06.jpg', 'guide/no-gruppo.jpg'),
    ),
    // Dettagli: 4 coppie vere (mani, piedi, denti, gambe) + 2 coppie di soli "sì"
    // (capelli, occhi, schiena tatuata) che non hanno un corrispettivo sbagliato.
    'ugc' => array(),
    'dettaglio' => array(
        array('guide/det-mani-si.jpg',    'guide/det-mani-no.jpg'),
        array('guide/det-piedi-si.jpg',   'guide/det-piedi-no.jpg'),
        array('guide/det-denti-si.jpg',   'guide/det-denti-no.jpg'),
        array('guide/det-gambe-si.jpg',   'guide/det-gambe-no.jpg'),
        array('guide/det-capelli-si.jpg', 'guide/det-occhi-si.jpg',   'si'),
        array('guide/det-schiena-si.jpg', 'guide/det-capelli-si.jpg', 'si'),
    ),
    'eventi' => array(
        array('staff/hostess.jpg',     'guide/no-discoteca.jpg'),
        array('staff/steward.jpg',     'guide/no-spalle.jpg'),
        array('gallery/g08.jpg',       'guide/no-posa.jpg'),
        array('staff/accoglienza.jpg', 'guide/no-selfie-vicino.jpg'),
        array('staff/interprete.jpg',  'guide/no-gruppo.jpg'),
    ),
    // Altre foto: qui mare, palestra, discoteca e amici sono ESEMPI BUONI.
    'casual' => array(
        array('guide/no-spiaggia.jpg',  'guide/no-lontano.jpg'),
        array('guide/no-palestra.jpg',  'guide/no-spalle.jpg'),
        array('guide/no-discoteca.jpg', 'guide/no-ritagliata.jpg'),
        array('guide/no-sport.jpg',     'guide/no-filtro.jpg'),
        array('guide/no-gruppo.jpg',    'guide/no-selfie-vicino.jpg'),
        array('guide/no-bacio.jpg',     'guide/no-occhiali.jpg'),
        array('guide/no-posa.jpg',      'guide/no-lontano.jpg'),
    ),
);

// Articolo guida, un indirizzo per lingua (WPML usa slug diversi — verificato via hreflang il 14/08).
$TALENT_ALBUM_GUIDA = array(
    'polaroid' => array(
        'it' => '/polaroid-agenzia-modelli-guida-completa/',
        'en' => '/en/polaroid-photos-modeling-agency-complete-guide/',
        'fr' => '/fr/photos-polaroid-agence-mannequins-guide-complet/',
        'es' => '/es/fotos-polaroid-agencia-modelos-guia-completa/',
    ),
);
