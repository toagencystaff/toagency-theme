<?php
/**
 * Template Name: Registrazione Talent
 * v1.0 — 7 Maggio 2026
 *
 * Path: /wp-content/themes/toagency-theme/templates/page-registrati-talent.php
 *
 * Form pubblico per la registrazione dei talent (modelli, attori, hostess,
 * comparse, bambini, influencer + reparto backstage: foto/video/MUA/stylist).
 *
 * Salva in tabella MySQL talent_database (74 colonne).
 * Foto profilo + portfolio multiplo in talent_portfolio_files.
 * Multilingua IT/EN/FR/ES. Stessa UX del form crew (classi rinominate
 * .toa-talent-* per non collidere con .toa-crew-*).
 *
 * Logica età:
 *   - tipo IMMAGINE: minimo 6 anni (categoria "bambino" 6-13: form
 *     compilato dal genitore. 14-17: minorenne con genitore).
 *   - tipo BACKSTAGE: minimo 16 anni (16-17: minorenne con genitore).
 *   - <6 anni: reject totale.
 */

toa_component('header');
// FIX 2026-05-22 marco — pre-fill email da ?email=XXX&ref=kappa (funnel Kappa Future Festival)
$prefill_email = isset($_GET['email']) ? sanitize_email($_GET['email']) : '';
$ref_kappa     = (isset($_GET['ref']) && $_GET['ref'] === 'kappa');
$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, array('it','en','fr','es'))) $__l = 'it';

if (!function_exists('_ht_talent')) {
    function _ht_talent($strings) {
        global $__l;
        return esc_html(isset($strings[$__l]) ? $strings[$__l] : $strings['it']);
    }
}
function _ht_talent_raw($strings) {
    global $__l;
    return isset($strings[$__l]) ? $strings[$__l] : $strings['it'];
}

// ─────────────────────────────────────────────────────────────────────
// RUOLI TALENT — IMMAGINE (modelli, attori, hostess, comparse, ecc.)
// min_age: età minima per quel ruolo specifico
// ─────────────────────────────────────────────────────────────────────
// S9.2 2026-05-21 — 7 atomi puri unisex (rimossi model_f/m, actor_f/m, steward, bambino, altro_immagine)
$TALENT_RUOLI_IMMAGINE = array(
    array('code'=>'actor',      'min_age'=>14, 'label'=>array('it'=>'Attore/Attrice','en'=>'Actor/Actress','fr'=>'Acteur/Actrice','es'=>'Actor/Actriz')),
    array('code'=>'model',      'min_age'=>14, 'label'=>array('it'=>'Modello/Modella','en'=>'Model','fr'=>'Mannequin','es'=>'Modelo')),
    array('code'=>'hostess',    'min_age'=>18, 'label'=>array('it'=>'Hostess/Steward','en'=>'Hostess/Steward','fr'=>'Hôtesse/Steward','es'=>'Azafata/Steward')),
    // 2026-08-14 (TEMA REGISTRAZIONE TALENT) — era 'creator', che il CRM normalizzava in silenzio a
    // 'influencer' (alias legacy, lib/ruoli.php riga 87): nessuno poteva selezionare il ruolo vero.
    // Verificato dalla chat CRM RUOLI-TALENT sui dati live: creator=0 record, influencer=2.744, ugc_creator=35.
    // Etichette allineate a page-talent-self-edit.php ($RUOLI_OPTS), così form e self-edit dicono la stessa cosa.
    array('code'=>'ugc_creator','min_age'=>14, 'label'=>array('it'=>'UGC Creator','en'=>'UGC Creator','fr'=>'Créateur UGC','es'=>'Creador UGC')),
    array('code'=>'influencer', 'min_age'=>14, 'label'=>array('it'=>'Influencer/Creator','en'=>'Influencer/Creator','fr'=>'Influenceur/Créateur','es'=>'Influencer/Creador')),
    array('code'=>'comparsa',   'min_age'=>14, 'label'=>array('it'=>'Comparsa','en'=>'Extra','fr'=>'Figurant','es'=>'Extra')),
    // FIX 2026-06-29 marco — rimossa opzione "Altro" dai ruoli (ruoli solo lista canonica, eccetto sesso)
);

// ─────────────────────────────────────────────────────────────────────
// RUOLI TALENT — BACKSTAGE (foto, video, MUA, stylist, parrucchieri)
// Minimo 16 anni per tutti (coerente con form crew).
// ─────────────────────────────────────────────────────────────────────
// S9.2 2026-05-21 — backstage NASCOSTO nel form pubblico (0 record mai salvati, ruoli→crew_database)
// La variabile resta per compatibilità ma la sezione HTML viene soppressa sotto.
$TALENT_RUOLI_BACKSTAGE = array(
    array('code'=>'fotografo',                 'min_age'=>16, 'label'=>array('it'=>'Fotografo','en'=>'Photographer','fr'=>'Photographe','es'=>'Fotógrafo')),
    array('code'=>'videomaker',                'min_age'=>16, 'label'=>array('it'=>'Videomaker','en'=>'Videomaker','fr'=>'Vidéaste','es'=>'Videomaker')),
    array('code'=>'makeup_artist',             'min_age'=>16, 'label'=>array('it'=>'Make-Up Artist','en'=>'Make-Up Artist','fr'=>'Maquilleur','es'=>'Maquillador')),
    array('code'=>'hairstylist',                'min_age'=>16, 'label'=>array('it'=>'Hairstylist','en'=>'Hairstylist','fr'=>'Hairstylist','es'=>'Hairstylist')),
    array('code'=>'parrucchiere',               'min_age'=>16, 'label'=>array('it'=>'Parrucchiere','en'=>'Hairdresser','fr'=>'Coiffeur','es'=>'Peluquero')),
    array('code'=>'stylist',                   'min_age'=>16, 'label'=>array('it'=>'Stylist','en'=>'Stylist','fr'=>'Styliste','es'=>'Estilista')),
    array('code'=>'fashion_designer',          'min_age'=>16, 'label'=>array('it'=>'Fashion Designer','en'=>'Fashion Designer','fr'=>'Créateur de mode','es'=>'Diseñador de moda')),
    array('code'=>'altro_backstage',           'min_age'=>16, 'label'=>array('it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro')),
);

// ─────────────────────────────────────────────────────────────────────
// CARATTERISTICHE FISICHE (mostrate solo per tipo=immagine)
// ─────────────────────────────────────────────────────────────────────
$TALENT_OCCHI = array(
    array('code'=>'azzurri', 'label'=>array('it'=>'Azzurri','en'=>'Blue','fr'=>'Bleus','es'=>'Azules')),
    array('code'=>'verdi',   'label'=>array('it'=>'Verdi','en'=>'Green','fr'=>'Verts','es'=>'Verdes')),
    array('code'=>'marroni', 'label'=>array('it'=>'Marroni','en'=>'Brown','fr'=>'Marrons','es'=>'Marrones')),
    array('code'=>'neri',    'label'=>array('it'=>'Neri','en'=>'Black','fr'=>'Noirs','es'=>'Negros')),
    array('code'=>'grigi',   'label'=>array('it'=>'Grigi','en'=>'Gray','fr'=>'Gris','es'=>'Grises')),
    // FIX 2026-06-29 marco — rimossa opzione "Altro" da colore occhi (Altro mai, eccetto sesso)
);
$TALENT_CAPELLI = array(
    array('code'=>'biondi',  'label'=>array('it'=>'Biondi','en'=>'Blonde','fr'=>'Blonds','es'=>'Rubios')),
    array('code'=>'castani', 'label'=>array('it'=>'Castani','en'=>'Brown','fr'=>'Châtains','es'=>'Castaños')),
    array('code'=>'neri',    'label'=>array('it'=>'Neri','en'=>'Black','fr'=>'Noirs','es'=>'Negros')),
    array('code'=>'rossi',   'label'=>array('it'=>'Rossi','en'=>'Red','fr'=>'Roux','es'=>'Pelirrojos')),
    array('code'=>'grigi',   'label'=>array('it'=>'Grigi','en'=>'Gray','fr'=>'Gris','es'=>'Grises')),
    array('code'=>'bianchi', 'label'=>array('it'=>'Bianchi','en'=>'White','fr'=>'Blancs','es'=>'Blancos')),
    array('code'=>'calvo',   'label'=>array('it'=>'Calvo','en'=>'Bald','fr'=>'Chauve','es'=>'Calvo')),
);
$TALENT_ETNIA = array(
    array('code'=>'caucasica', 'label'=>array('it'=>'Caucasica','en'=>'Caucasian','fr'=>'Caucasienne','es'=>'Caucásica')),
    array('code'=>'africana',  'label'=>array('it'=>'Africana','en'=>'African','fr'=>'Africaine','es'=>'Africana')),
    array('code'=>'asiatica',      'label'=>array('it'=>'Asiatica','en'=>'Asian','fr'=>'Asiatique','es'=>'Asiática')),
    array('code'=>'sud_asiatica',  'label'=>array('it'=>'Sud-asiatica','en'=>'South Asian','fr'=>'Sud-asiatique','es'=>'Sudasiática')),
    array('code'=>'latina',        'label'=>array('it'=>'Latina','en'=>'Latina','fr'=>'Latina','es'=>'Latina')),
    array('code'=>'araba',     'label'=>array('it'=>'Araba','en'=>'Arabic','fr'=>'Arabe','es'=>'Árabe')),
    // 'mista' rimosso 2026-05-19: multi-etnia ora via selezione di 2 atomici (limite hard).
);
$TALENT_TAGLIE = array('XS','S','M','L','XL','XXL');

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
        'quante' => array(
            'it'=>'Da 3 a 8 foto: una elegante, una sportiva, una in postazione durante il lavoro.',
            'en'=>'3 to 8 photos: one smart, one sporty, one at your station while working.',
            'fr'=>'De 3 à 8 photos : une élégante, une sportive, une au poste pendant le travail.',
            'es'=>'De 3 a 8 fotos: una elegante, una deportiva, una en el puesto mientras trabajas.',
        ),
        'hint'  => array(
            'it'=>'Tu al lavoro: una in elegante, una in sportivo. Vanno benissimo anche foto scattate a un evento vero.',
            'en'=>'You at work: one smart, one sporty. Photos taken at a real event are perfect too.',
            'fr'=>'Toi au travail : une en tenue élégante, une en sportive. Des photos prises à un vrai événement conviennent aussi.',
            'es'=>'Tú trabajando: una elegante, una deportiva. También valen fotos hechas en un evento real.',
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

// Numero WhatsApp agenzia (lo stesso usato in tutto il tema).
$TALENT_WA_NUM = '393517899225';

$theme_uri = get_stylesheet_directory_uri();
?>
<!-- TOA-TALENT-FORM-V1 -->
<link rel="stylesheet" href="<?php echo esc_url($theme_uri . '/assets/talent-form.css'); ?>?v=1.2">
<script>
    window.toaThemeUri = "<?php echo esc_js($theme_uri); ?>";
    window.toaTalentLang = "<?php echo esc_js($__l); ?>";
</script>

<section class="toa-talent-wrap">

    <div class="toa-talent-eyebrow"><?php echo _ht_talent(array('it'=>'Lavora con TOA','en'=>'Work with TOA','fr'=>'Travailler avec TOA','es'=>'Trabaja con TOA')); ?></div>
    <h1 class="toa-talent-title"><?php echo _ht_talent(array('it'=>'Registrati come Talent','en'=>'Register as Talent','fr'=>'Inscris-toi en tant que Talent','es'=>'Regístrate como Talent')); ?></h1>
    <p class="toa-talent-subtitle"><?php echo _ht_talent(array(
        'it'=>'Modelli, attori, hostess, comparse, bambini, influencer.',
        'en'=>'Models, actors, hostesses, extras, children, influencers.',
        'fr'=>'Mannequins, acteurs, hôtesses, figurants, enfants, influenceurs.',
        'es'=>'Modelos, actores, azafatas, extras, niños, influencers.',
    )); ?></p>

    <!-- Banner valutazione staff -->
    <div class="toa-talent-info-banner">
        ✨ <?php echo wp_kses(_ht_talent_raw(array(
            'it'=>'Valutiamo ogni profilo. <strong>Una volta approvato</strong> sei visibile alle aziende che cercano talent.',
            'en'=>'We review every profile. <strong>Once approved</strong> you are visible to companies looking for talent.',
            'fr'=>'On examine chaque fiche. <strong>Une fois validée</strong> tu es visible auprès des entreprises.',
            'es'=>'Revisamos cada perfil. <strong>Una vez aprobado</strong> eres visible para las empresas que buscan talent.',
        )), array('strong'=>array(),'b'=>array(),'em'=>array(),'i'=>array(),'br'=>array())); ?>
    </div>

    <!-- Banner registrazione minore -->
    <div class="toa-talent-info-banner toa-talent-banner-secondary">
        👨‍👩‍👧 <?php echo wp_kses(_ht_talent_raw(array(
            'it'=>'<strong>Minorenni:</strong> sotto i 16 anni compila un genitore. Tra i 16 e i 17 serve la sua conferma.',
            'en'=>'<strong>Minors:</strong> under 16 a parent fills the form. Between 16 and 17 the parent must confirm.',
            'fr'=>'<strong>Mineurs :</strong> avant 16 ans c\'est un parent qui remplit. Entre 16 et 17 ans il doit confirmer.',
            'es'=>'<strong>Menores:</strong> antes de los 16 rellena un padre. Entre 16 y 17 hace falta su confirmación.',
        )), array('strong'=>array(),'b'=>array(),'em'=>array(),'i'=>array(),'br'=>array())); ?>
    </div>

    <form id="toaTalentForm" novalidate enctype="multipart/form-data">

        <!-- FIX 2026-06-28 marco — force_create: "Sono un'altra persona" bypassa check doppione nome+cognome+dob -->
        <input type="hidden" name="force_create" id="toaForceCreate" value="">

        <div class="toa-talent-progress" style="display:none;"><!-- 13/07: nascosta sullo Step 1 (form unico), compare dallo Step 2 -->
            <div class="toa-talent-progress-step active" data-step="1"></div>
            <div class="toa-talent-progress-step" data-step="2"></div>
            <div class="toa-talent-progress-step" data-step="3"></div>
            <div class="toa-talent-progress-step" data-step="4"></div>
        </div>

        <?php // 2026-08-14 — barra "profilo completo" fissa: accompagna dallo Step 1 all'invio ?>
        <div class="toa-alb-sticky" id="toaTalentSticky"
             data-m0="<?php echo esc_attr(_ht_talent(array('it'=>'Compila i tuoi dati: ogni campo che aggiungi alza le tue possibilità.','en'=>'Fill in your details: every field you add raises your chances.','fr'=>'Remplis tes informations : chaque champ ajouté augmente tes chances.','es'=>'Rellena tus datos: cada campo que añades sube tus posibilidades.'))); ?>"
             data-m1="<?php echo esc_attr(_ht_talent(array('it'=>'Mancano ancora parecchie cose: continua, ci vogliono pochi minuti.','en'=>'Several things are still missing: keep going, it only takes a few minutes.','fr'=>'Il manque encore pas mal de choses : continue, ça prend quelques minutes.','es'=>'Aún faltan varias cosas: sigue, son solo unos minutos.'))); ?>"
             data-m2="<?php echo esc_attr(_ht_talent(array('it'=>'Ci sei quasi: aggiungi le ultime cose e passi davanti a chi si è fermato prima.','en'=>'Almost there: add the last few things and you move ahead of those who stopped earlier.','fr'=>'Tu y es presque : ajoute les dernières choses et tu passes devant ceux qui se sont arrêtés avant.','es'=>'Casi lo tienes: añade las últimas cosas y adelantas a quien se paró antes.'))); ?>"
             data-m3="<?php echo esc_attr(_ht_talent(array('it'=>'Ottimo profilo: sei sopra la media di chi si registra. Ancora un piccolo sforzo.','en'=>'Great profile: you are above the average of those who sign up. One small push left.','fr'=>'Très bonne fiche : tu es au-dessus de la moyenne. Encore un petit effort.','es'=>'Ficha muy buena: estás por encima de la media. Un último esfuerzo.'))); ?>"
             data-m4="<?php echo esc_attr(_ht_talent(array('it'=>'Profilo completo: sei tra quelli che i casting vedono per primi.','en'=>'Profile complete: you are among the first castings see.','fr'=>'Fiche complète : tu es parmi les premiers que les castings voient.','es'=>'Ficha completa: estás entre los primeros que ven los castings.'))); ?>"
             data-tutto="<?php echo esc_attr(_ht_talent(array('it'=>'✅ Non manca niente, bravo.','en'=>'✅ Nothing missing, well done.','fr'=>'✅ Il ne manque rien, bravo.','es'=>'✅ No falta nada, bien hecho.'))); ?>">
            <div class="toa-alb-sticky-riga">
                <div class="toa-albums-bar-track"><div class="toa-albums-bar-fill" id="toaTalentCompletenessFill"></div></div>
                <strong id="toaTalentCompletenessPct">0%</strong>
            </div>
            <div class="toa-alb-sticky-msg" id="toaTalentCompMsg"></div>
            <button type="button" class="toa-alb-sticky-btn" id="toaTalentMancaBtn"><?php echo _ht_talent(array('it'=>'Scopri cosa manca','en'=>'See what\'s missing','fr'=>'Vois ce qui manque','es'=>'Mira qué falta')); ?></button>
            <div class="toa-alb-manca" id="toaTalentManca" hidden></div>
        </div>

        <!-- Errore form-level (network / server) — inline, sostituisce alert() -->
        <div class="toa-talent-error-msg toa-talent-form-error" id="toaTalentFormError" role="alert" style="text-align:center;margin:0 0 14px;"></div>

        <!-- FIX 2026-06-25 marco — recupero profilo se email già registrata (riusa recupera-link.php) -->
        <div id="toaTalentRecover" style="display:none;text-align:center;margin:0 0 16px;">
            <a id="toaTalentRecoverLink" href="/crm_toagency/recupera-link.php" rel="noopener"
               style="display:inline-block;background:#6c63ff;color:#fff;border-radius:8px;padding:.7rem 1.4rem;font-weight:700;text-decoration:none;font-size:.95rem;">
                🔑 <?php echo _ht_talent(array(
                    'it'=>'Recupera il tuo profilo',
                    'en'=>'Recover your profile',
                    'fr'=>'Récupère ta fiche',
                    'es'=>'Recupera tu perfil',
                )); ?>
            </a>
        </div>

        <!-- FIX 2026-06-28 marco — box doppione nome+cognome+dob (4 opzioni) -->
        <div id="toaTalentDupBox" style="display:none;margin:0 0 20px;padding:18px 20px;background:#fff8e1;border:1.5px solid #f5c518;border-radius:12px;">
            <p style="margin:0 0 14px;font-weight:700;font-size:.97rem;color:#7a5a00;">
                ⚠️ <?php echo _ht_talent(array(
                    'it'=>'Esiste già una scheda con questi dati.',
                    'en'=>'A profile with this name and date already exists.',
                    'fr'=>'Une fiche avec ces données existe déjà.',
                    'es'=>'Ya existe una ficha con estos datos.',
                )); ?>
            </p>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <!-- 1. Guarda la scheda -->
                <a id="toaDupViewLink" href="#" target="_blank" rel="noopener"
                   style="display:block;padding:11px 16px;background:#fff;border:1.5px solid #d0d0d0;border-radius:8px;font-weight:600;font-size:.9rem;color:#1d1d27;text-decoration:none;">
                    👁 <?php echo _ht_talent(array(
                        'it'=>'Guarda la scheda',
                        'en'=>'View the profile',
                        'fr'=>'Voir la fiche',
                        'es'=>'Ver la ficha',
                    )); ?>
                </a>
                <!-- 2. È la mia, recuperala -->
                <button type="button" id="toaDupResendBtn"
                        style="display:block;width:100%;padding:11px 16px;background:#6c63ff;border:none;border-radius:8px;font-weight:700;font-size:.9rem;color:#fff;cursor:pointer;text-align:left;">
                    ✅ <?php echo _ht_talent(array(
                        'it'=>'È la mia, recuperala',
                        'en'=>'It\'s mine, send me the link',
                        'fr'=>'C\'est la mienne, envoie-moi le lien',
                        'es'=>'Es la mía, envíame el enlace',
                    )); ?>
                </button>
                <!-- Conferma invio link (nascosta, mostrata dal JS) -->
                <p id="toaDupResendOk" style="display:none;margin:0;padding:10px 14px;background:#e8f5e9;border-radius:8px;font-size:.88rem;color:#2e7d32;font-weight:600;"></p>
                <!-- 3. Sono un'altra persona -->
                <button type="button" id="toaDupForceBtn"
                        style="display:block;width:100%;padding:11px 16px;background:#fff;border:1.5px solid #d0d0d0;border-radius:8px;font-weight:600;font-size:.9rem;color:#555;cursor:pointer;text-align:left;">
                    🙋 <?php echo _ht_talent(array(
                        'it'=>'Sono un\'altra persona, procedi',
                        'en'=>'I\'m a different person, continue',
                        'fr'=>'Je suis une autre personne, continuer',
                        'es'=>'Soy otra persona, continuar',
                    )); ?>
                </button>
            </div>
        </div>

        <!-- ═════ STEP 1 — Chi sei ═════ -->
        <div class="toa-talent-step active" data-step="1">
            <h3><?php echo _ht_talent(array('it'=>'Chi sei','en'=>'Who you are','fr'=>'Qui es-tu','es'=>'Quién eres')); ?></h3>
            <?php // 2026-08-15 — promessa corta in cima allo Step 1: è qui che si vince l'iscrizione ?>
            <p class="toa-alb-promessa"><?php echo _ht_talent_raw(array(
                'it'=>'<strong>Ti basta questa pagina per entrare.</strong> Nome, contatti, città e una foto del viso: meno di un minuto. Il resto lo aggiungi dopo.',
                'en'=>'<strong>This page is all you need to get in.</strong> Name, contacts, city and one photo of your face: under a minute. The rest comes later.',
                'fr'=>'<strong>Cette page suffit pour entrer.</strong> Nom, contacts, ville et une photo du visage : moins d\'une minute. Le reste vient après.',
                'es'=>'<strong>Con esta página ya estás dentro.</strong> Nombre, contactos, ciudad y una foto de la cara: menos de un minuto. El resto lo añades después.',
            )); ?></p>
            

            <div class="toa-talent-field-row">
                <div class="toa-talent-field">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Nome','en'=>'First name','fr'=>'Prénom','es'=>'Nombre')); ?> <span class="req">*</span></label>
                    <input type="text" name="nome" class="toa-talent-input" required autocomplete="given-name">
                    <div class="toa-talent-error-msg"></div>
                </div>
                <div class="toa-talent-field">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Cognome','en'=>'Last name','fr'=>'Nom','es'=>'Apellido')); ?> <span class="req">*</span></label>
                    <input type="text" name="cognome" class="toa-talent-input" required autocomplete="family-name">
                    <div class="toa-talent-error-msg"></div>
                </div>
            </div>

            <div class="toa-talent-field">
                <label class="toa-talent-label">Email <span class="req">*</span></label>
                <input type="email" name="email" class="toa-talent-input" required autocomplete="email"
                       value="<?php echo esc_attr($prefill_email); ?>">
                <div class="toa-talent-error-msg"></div>
                <p class="toa-talent-field-hint"><?php echo _ht_talent(array(
                    'it'=>'Email del talent (se maggiorenne) o del genitore (se minore).',
                    'en'=>'Email of the talent (if adult) or parent (if minor).',
                    'fr'=>'Email du talent (si majeur) ou du parent (si mineur).',
                    'es'=>'Email del talent (si es adulto) o del padre (si es menor).',
                )); ?></p>
            </div>

            <!-- Telefono con prefisso -->
            <div class="toa-talent-field">
                <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono')); ?> <span class="req">*</span></label>
                <div class="toa-talent-phone-group">
                    <div class="toa-talent-customselect toa-talent-customselect-compact searchable" id="toaTalentPhonePrefix">
                        <input type="hidden" name="tel_paese_code" value="IT">
                        <div class="toa-talent-customselect-trigger">
                            <span class="toa-talent-customselect-label">🇮🇹 +39</span>
                        </div>
                        <div class="toa-talent-customselect-search"><input type="text" placeholder="Cerca paese..."></div>
                        <div class="toa-talent-customselect-options"></div>
                    </div>
                    <input type="tel" name="telefono" class="toa-talent-input" required autocomplete="tel" placeholder="333 1234567">
                </div>
                <div class="toa-talent-error-msg"></div>
            </div>

            <div class="toa-talent-field-row">
                <div class="toa-talent-field">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Sesso','en'=>'Gender','fr'=>'Genre','es'=>'Género')); ?> <span class="req">*</span></label>
                    <div class="toa-talent-toggle-group">
                        <input type="hidden" name="sesso" value="">
                        <div class="toa-talent-toggle" data-value="F"><?php echo _ht_talent(array('it'=>'F','en'=>'F','fr'=>'F','es'=>'F')); ?></div>
                        <div class="toa-talent-toggle" data-value="M"><?php echo _ht_talent(array('it'=>'M','en'=>'M','fr'=>'M','es'=>'M')); ?></div>
                        <div class="toa-talent-toggle" data-value="altro"><?php echo _ht_talent(array('it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro')); ?></div>
                    </div>
                    <div class="toa-talent-error-msg"></div>
                </div>
                <div class="toa-talent-field">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Data di nascita','en'=>'Date of birth','fr'=>'Date de naissance','es'=>'Fecha de nacimiento')); ?> <span class="req">*</span></label>
                    <input type="date" name="data_nascita" class="toa-talent-input" required>
                    <div class="toa-talent-error-msg"></div>
                </div>
            </div>

            <!-- Sezione genitore 0-15 (genitore compila il form) -->
            <div class="toa-talent-genitore" id="toaTalentGenitore015">
                <h4>👨‍👩‍👧 <?php echo _ht_talent(array('it'=>'Dati del genitore / tutore legale','en'=>'Parent / legal guardian data','fr'=>'Données du parent','es'=>'Datos del padre/tutor')); ?></h4>
                <p><?php echo _ht_talent(array(
                    'it'=>'Sotto i 16 anni: servono i dati del genitore o tutore che compila e autorizza.',
                    'en'=>'Under 16: we need the details of the parent or guardian filling in and authorising.',
                    'fr'=>'Moins de 16 ans : il faut les données du parent ou tuteur qui remplit et autorise.',
                    'es'=>'Menos de 16 años: hacen falta los datos del padre o tutor que rellena y autoriza.',
                )); ?></p>
                <div class="toa-talent-field-row">
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Nome e cognome genitore','en'=>'Parent full name','fr'=>'Nom complet parent','es'=>'Nombre completo padre')); ?> <span class="req">*</span></label>
                        <input type="text" name="genitore1_nome" class="toa-talent-input" autocomplete="off">
                    </div>
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Relazione','en'=>'Relationship','fr'=>'Relation','es'=>'Relación')); ?> <span class="req">*</span></label>
                        <div class="toa-talent-customselect">
                            <input type="hidden" name="genitore1_relazione" value="genitore">
                            <div class="toa-talent-customselect-trigger">
                                <span class="toa-talent-customselect-label"><?php echo _ht_talent(array('it'=>'Genitore','en'=>'Parent','fr'=>'Parent','es'=>'Padre/Madre')); ?></span>
                            </div>
                            <div class="toa-talent-customselect-options">
                                <div class="toa-talent-customselect-option selected" data-value="genitore"><?php echo _ht_talent(array('it'=>'Genitore','en'=>'Parent','fr'=>'Parent','es'=>'Padre/Madre')); ?></div>
                                <div class="toa-talent-customselect-option" data-value="tutore"><?php echo _ht_talent(array('it'=>'Tutore legale','en'=>'Legal guardian','fr'=>'Tuteur légal','es'=>'Tutor legal')); ?></div>
                                <div class="toa-talent-customselect-option" data-value="altro"><?php echo _ht_talent(array('it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro')); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="toa-talent-field-row">
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Email genitore','en'=>'Parent email','fr'=>'Email parent','es'=>'Email padre')); ?> <span class="req">*</span></label>
                        <input type="email" name="genitore1_email" class="toa-talent-input" autocomplete="off">
                        <p class="toa-talent-field-hint"><?php echo _ht_talent(array(
                            'it'=>'Per comunicazioni relative a casting e opportunità lavorative del minore.',
                            'en'=>'For casting and work opportunity communications regarding the minor.',
                            'fr'=>'Pour les communications relatives aux castings du mineur.',
                            'es'=>'Para comunicaciones sobre castings del menor.',
                        )); ?></p>
                    </div>
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Telefono genitore','en'=>'Parent phone','fr'=>'Téléphone parent','es'=>'Teléfono padre')); ?> <span class="req">*</span></label>
                        <input type="tel" name="genitore1_telefono" class="toa-talent-input" autocomplete="off">
                    </div>
                </div>
            </div>

            <!-- Sezione genitore 16-17 (checkbox conferma) -->
            <div class="toa-talent-genitore" id="toaTalentGenitore1617">
                <div class="toa-talent-field" style="background:rgba(200,255,0,0.04);border:1px solid rgba(200,255,0,0.2);border-radius:14px;padding:18px;">
                    <label class="toa-talent-checkbox" style="align-items:flex-start;">
                        <input type="checkbox" name="parent_confirm" value="1" style="margin-top:6px;">
                        <span style="font-size:0.92rem;line-height:1.55;">
                            <strong style="color:#c8ff00;"><?php echo _ht_talent(array(
                                'it'=>'👨‍👩‍👧 Conferma del genitore / tutore legale',
                                'en'=>'👨‍👩‍👧 Parent / legal guardian confirmation',
                                'fr'=>'👨‍👩‍👧 Confirmation du parent / tuteur',
                                'es'=>'👨‍👩‍👧 Confirmación del padre / tutor',
                            )); ?></strong><br>
                            <?php echo _ht_talent(array(
                                'it'=>'Confermo che il mio genitore/tutore legale è a conoscenza e approva la mia registrazione presso TOAgency, incluso il trattamento dei dati personali e la pubblicazione delle immagini ai sensi del GDPR Reg. UE 2016/679 e della Legge 633/1941 art. 96-97.',
                                'en'=>'I confirm that my parent/legal guardian is aware of and approves my registration with TOAgency, including the processing of personal data and publication of images under GDPR Reg. EU 2016/679.',
                                'fr'=>'Je confirme que mon parent/tuteur est informé et approuve mon inscription chez TOAgency, y compris le traitement des données et la publication des images (RGPD).',
                                'es'=>'Confirmo que mi padre/tutor legal conoce y aprueba mi registro en TOAgency, incluido el tratamiento de datos y publicación de imágenes según el RGPD.',
                            )); ?>
                        </span>
                    </label>
                    <div class="toa-talent-error-msg"></div>
                </div>
            </div>

            <!-- DOVE VIVI (nazione + provincia + comune) — residenza tutta nello Step 1 (13/07) -->
            <div class="toa-talent-field">
                <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Nazione di residenza','en'=>'Country of residence','fr'=>'Pays de résidence','es'=>'País de residencia')); ?> <span class="req">*</span></label>
                <div class="toa-talent-customselect searchable" id="toaTalentNation">
                    <input type="hidden" name="res_nation" value="">
                    <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label">—</span></div>
                    <div class="toa-talent-customselect-search"><input type="text" placeholder="Cerca paese..."></div>
                    <div class="toa-talent-customselect-options"></div>
                </div>
                <div class="toa-talent-error-msg"></div>
            </div>

            <div class="toa-talent-field" id="toaTalentProvinceWrap">
                <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Provincia','en'=>'Province','fr'=>'Région','es'=>'Provincia')); ?> <span class="req">*</span></label>
                <div class="toa-talent-customselect searchable" id="toaTalentProvince">
                    <input type="hidden" name="res_provincia" value="">
                    <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label"><?php echo _ht_talent(array('it'=>'Seleziona provincia...','en'=>'Select province...','fr'=>'Sélectionne...','es'=>'Selecciona...')); ?></span></div>
                    <div class="toa-talent-customselect-search"><input type="text" placeholder="Cerca..."></div>
                    <div class="toa-talent-customselect-options"></div>
                </div>
                <div class="toa-talent-error-msg"></div>
            </div>

            <!-- RESIDENZA — città (nazione+provincia spostate allo Step 1 il 13/07) -->
            <div class="toa-talent-field" id="toaTalentCityWrap">
                <!-- Container 1: Typeahead per Italia (default) -->
                <div class="city-typeahead">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Città / Comune','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                    <input type="text" name="res_city_name" class="toa-talent-input" autocomplete="off" placeholder="<?php echo _ht_talent(array('it'=>'Inizia a digitare...','en'=>'Type...','fr'=>'Tape...','es'=>'Empieza...')); ?>">
                    <input type="hidden" name="res_city_code">
                    <div class="toa-talent-error-msg"></div>
                </div>
                <!-- Container 2: Select per FR/ES/CH/GB -->
                <div class="city-select" style="display:none;">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Area / Città','en'=>'Area / City','fr'=>'Zone / Ville','es'=>'Área / Ciudad')); ?> <span class="req">*</span></label>
                    <div class="toa-talent-customselect">
                        <input type="hidden" name="res_city_code">
                        <input type="hidden" name="res_city_name">
                        <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label"><?php echo _ht_talent(array('it'=>'Seleziona...','en'=>'Select...','fr'=>'Sélectionne...','es'=>'Selecciona...')); ?></span></div>
                        <div class="toa-talent-customselect-options"></div>
                    </div>
                    <div class="toa-talent-error-msg"></div>
                </div>
                <!-- Container 3: Free text per resto del mondo -->
                <div class="city-free" style="display:none;">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Città','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                    <input type="text" name="res_city_name" class="toa-talent-input" placeholder="Es. New York, Tokyo, ...">
                    <input type="hidden" name="res_city_code">
                    <div class="toa-talent-error-msg"></div>
                </div>
            </div>

            <!-- MOVED 2026-07-12: foto profilo + disclaimer + GDPR spostati qui da Step 4 per lead-capture Step 1 -->
            <!-- Foto profilo -->
            <div class="toa-talent-upload-section">
                <h5>👤 <?php echo _ht_talent(array('it'=>'Primo piano','en'=>'Close-up','fr'=>'Gros plan','es'=>'Primer plano')); ?> <span class="req">*</span></h5>
                <p class="toa-talent-step-help"><?php echo _ht_talent(array(
                    'it'=>'Viso frontale, sfondo neutro, luce naturale — come nell\'esempio. Sarà la tua immagine principale.',
                    'en'=>'Face front-on, plain background, natural light — like the example. This will be your main image.',
                    'fr'=>'Visage de face, fond neutre, lumière naturelle — comme l\'exemple. Ce sera ton image principale.',
                    'es'=>'Cara de frente, fondo neutro, luz natural — como el ejemplo. Será tu imagen principal.',
                )); ?></p>
                <?php
                $badge_ok = _ht_talent(array('it'=>'✅ Così sì','en'=>'✅ Yes like this','es'=>'✅ Así sí','fr'=>'✅ Oui comme ça'));
                $badge_no = _ht_talent(array('it'=>'❌ Così no','en'=>'❌ Not like this','es'=>'❌ Así no','fr'=>'❌ Pas comme ça'));
                ?>
                <!-- FOTO GUIDA: slideshow ✅/❌ interleaved — 1s auto-rotate (13 slide), badge multilingua -->
                <div class="toa-foto-gallery" id="toaFotoGallery">
                  <div class="toa-fg-slide active"><img src="<?= $theme_uri ?>/assets/foto-esempio-profilo.jpg" alt="ok"><span class="toa-fg-badge ok"><?= $badge_ok ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-sfondo.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/ok-2.jpg" alt="ok"><span class="toa-fg-badge ok"><?= $badge_ok ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-occhiali.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/ok-3.jpg" alt="ok"><span class="toa-fg-badge ok"><?= $badge_ok ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-angolo.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/ok-4.jpg" alt="ok"><span class="toa-fg-badge ok"><?= $badge_ok ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-trucco.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/ok-5.jpg" alt="ok"><span class="toa-fg-badge ok"><?= $badge_ok ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-cappello.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-selfie-alto.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-lontana.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                  <div class="toa-fg-slide"><img src="<?= $theme_uri ?>/assets/wrong-spiaggia.jpg" alt="no"><span class="toa-fg-badge wrong"><?= $badge_no ?></span></div>
                </div>
                <style>
                .toa-foto-gallery{position:relative;width:160px;height:220px;margin:10px auto 16px;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.25)}
                .toa-fg-slide{display:none;position:relative;width:100%;height:100%}
                .toa-fg-slide.active{display:block}
                .toa-fg-slide img{width:100%;height:100%;object-fit:cover;display:block}
                .toa-fg-badge{position:absolute;bottom:0;left:0;right:0;text-align:center;padding:5px 0;font-size:13px;font-weight:700;letter-spacing:.3px}
                .toa-fg-badge.ok{background:rgba(16,185,129,.88);color:#fff}
                .toa-fg-badge.wrong{background:rgba(220,38,38,.88);color:#fff}
                </style>
                <script>
                (function(){var s=document.querySelectorAll('#toaFotoGallery .toa-fg-slide'),i=0;if(!s.length)return;setInterval(function(){s[i].classList.remove('active');i=(i+1)%s.length;s[i].classList.add('active');},1000);})();
                </script>
                <div class="toa-talent-dropzone toa-talent-dropzone-small" id="toaTalentProfileDrop">
                    <div class="toa-talent-dropzone-icon">👤</div>
                    <div class="toa-talent-dropzone-text"><?php echo _ht_talent(array('it'=>'Carica foto o scatta selfie','en'=>'Upload photo or take a selfie','es'=>'Sube una foto o hazte un selfie','fr'=>'Charge une photo ou prends un selfie')); ?></div>
                    <div class="toa-talent-dropzone-hint">JPG, PNG • <?php /* TASK hardening-upload STEP A 2026-06-04 */ echo _ht_talent(array('it'=>'Carica le tue foto: le ottimizziamo noi automaticamente','en'=>'Upload your photos: we optimize them automatically','fr'=>'Charge tes photos : on les optimise automatiquement','es'=>'Sube tus fotos: las optimizamos automáticamente')); ?></div>
                    <input type="file" id="toaTalentProfileInput" accept="image/*" style="display:none;">
                </div>
                <p class="toa-talent-foto-nono" style="text-align:center;margin:12px auto 0;max-width:380px;padding:8px 16px;background:rgba(220,38,38,0.12);border:1px solid rgba(220,38,38,0.4);border-radius:8px;font-size:0.84rem;line-height:1.5;color:#f87171;font-weight:700;letter-spacing:.2px;"><?php echo _ht_talent(array(
                    'it'=>'❌ NO testi · NO loghi · NO watermark · NO filtri · NO effetti',
                    'en'=>'❌ NO text · NO logos · NO watermarks · NO filters · NO effects',
                    'es'=>'❌ NO textos · NO logos · NO marcas de agua · NO filtros · NO efectos',
                    'fr'=>'❌ PAS de texte · PAS de logo · PAS de filigrane · PAS de filtre · PAS d\'effet',
                )); ?></p>
                <div class="toa-talent-profile-thumb" id="toaTalentProfileThumb"></div>
                <div class="toa-talent-error-msg" id="toaTalentProfileError"></div>
            </div>

            <!-- Conferma upload -->
            <div class="toa-talent-field" style="margin-top: 26px;">
                <label class="toa-talent-checkbox">
                    <input type="checkbox" name="disclaimer_consent" value="1" required>
                    <span><?php echo _ht_talent(array(
                        'it'=>'Confermo che le foto caricate non contengono firme, watermark, contatti o riferimenti riconducibili al talent.',
                        'en'=>'I confirm photos don\'t contain signatures or contacts.',
                        'fr'=>'Je confirme l\'absence de signatures.',
                        'es'=>'Confirmo que no hay firmas ni contactos.',
                    )); ?></span>
                </label>
                <div class="toa-talent-error-msg"></div>
            </div>

            <!-- ═════════ CONSENSO GDPR / PRIVACY (obbligatorio) ═════════ -->
            <div class="toa-talent-field">
                <label class="toa-talent-checkbox">
                    <input type="checkbox" name="gdpr_consent" value="1" required>
                    <span><?php echo _ht_talent(array(
                        'it'=>'Ho letto e accetto la privacy policy. Per i minori, il consenso è prestato dal genitore/tutore legale ai sensi dell\'art. 8 GDPR Reg. UE 2016/679. I dati personali e le immagini del minore saranno trattati esclusivamente per finalità di gestione del profilo talent e presentazione a clienti aziendali. È possibile richiedere la cancellazione completa dei dati e delle immagini in qualsiasi momento scrivendo a info@toagency.it (art. 17 GDPR — diritto all\'oblio). La rimozione avverrà entro 30 giorni dalla richiesta.',
                        'en'=>'I accept the privacy policy. For minors, consent is given by the parent/guardian under GDPR Art. 8. Personal data and images will be used only for talent profile management and presentation to corporate clients. You may request complete deletion at any time by writing to info@toagency.it (GDPR Art. 17).',
                        'fr'=>'J\'accepte la politique de confidentialité. Pour les mineurs, le consentement est donné par le parent (RGPD Art. 8). Suppression possible à tout moment via info@toagency.it (Art. 17 RGPD).',
                        'es'=>'Acepto la política de privacidad. Para menores, el consentimiento lo da el padre/tutor (RGPD Art. 8). Eliminación completa posible en cualquier momento escribiendo a info@toagency.it (Art. 17 RGPD).',
                    )); ?> <a href="/privacy-policy/" target="_blank">Privacy</a></span>
                </label>
                <div class="toa-talent-error-msg"></div>
            </div>

            <div class="toa-talent-actions">
                <span></span>
                <button type="button" class="toa-talent-btn toa-talent-btn-primary" data-go="2"><?php echo _ht_talent(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> → <small style="display:block;font-weight:600;opacity:.75;font-size:.72rem;margin-top:2px;"><?php echo _ht_talent(array('it'=>'passaggio 2 di 4','en'=>'step 2 of 4','fr'=>'étape 2 sur 4','es'=>'paso 2 de 4')); ?></small></button>
            </div>
        </div>

        <!-- ═════ STEP 2 — Dove vivi ═════ -->
        <div class="toa-talent-step" data-step="2">
            <h3><?php echo _ht_talent(array('it'=>'Domicilio','en'=>'Domicile','fr'=>'Domicile','es'=>'Domicilio')); ?></h3>
            <p class="toa-talent-step-help"><?php echo _ht_talent(array('it'=>'Vivi dove sei residente? Se no, indicaci l\'altro indirizzo.','en'=>'Do you live where you are resident? If not, tell us the other address.','fr'=>'Tu vis là où tu es domicilié ? Sinon, indique l\'autre adresse.','es'=>'¿Vives donde estás empadronado? Si no, indícanos la otra dirección.')); ?></p>

            <!-- Toggle domicilio -->
            <div class="toa-talent-field">
                <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Il domicilio coincide con la residenza?','en'=>'Same domicile as residence?','fr'=>'Domicile = résidence ?','es'=>'¿Domicilio = residencia?')); ?></label>
                <div class="toa-talent-toggle-group" id="toaTalentDomCoincideGroup">
                    <input type="hidden" name="dom_coincide" value="1">
                    <div class="toa-talent-toggle active" data-value="1"><?php echo _ht_talent(array('it'=>'Sì','en'=>'Yes','fr'=>'Oui','es'=>'Sí')); ?></div>
                    <div class="toa-talent-toggle" data-value="0"><?php echo _ht_talent(array('it'=>'No, diverso','en'=>'No, different','fr'=>'Non','es'=>'No')); ?></div>
                </div>
            </div>

            <!-- Box domicilio diverso -->
            <div class="toa-talent-domicilio-box" id="toaTalentDomicilioBox" style="display:none;">
                <div class="toa-talent-domicilio-info">
                    📍 <?php echo _ht_talent(array(
                        'it'=>'Sarai informato dei casting di entrambi i luoghi (residenza + domicilio).',
                        'en'=>'You will be informed of castings in both locations.',
                        'fr'=>'Tu seras informé des castings dans les deux zones.',
                        'es'=>'Serás informado de castings en ambas zonas.',
                    )); ?>
                </div>

                <div class="toa-talent-field">
                    <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Nazione di domicilio','en'=>'Country of domicile','fr'=>'Pays domicile','es'=>'País domicilio')); ?> <span class="req">*</span></label>
                    <div class="toa-talent-customselect searchable" id="toaTalentDomNation">
                        <input type="hidden" name="dom_nation" value="">
                        <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label">—</span></div>
                        <div class="toa-talent-customselect-search"><input type="text" placeholder="Cerca..."></div>
                        <div class="toa-talent-customselect-options"></div>
                    </div>
                </div>

                <div class="toa-talent-field-row">
                    <div class="toa-talent-field" id="toaTalentDomProvinceWrap">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Provincia','en'=>'Province','fr'=>'Région','es'=>'Provincia')); ?> <span class="req">*</span></label>
                        <div class="toa-talent-customselect searchable" id="toaTalentDomProvince">
                            <input type="hidden" name="dom_provincia" value="">
                            <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label">—</span></div>
                            <div class="toa-talent-customselect-search"><input type="text" placeholder="Cerca..."></div>
                            <div class="toa-talent-customselect-options"></div>
                        </div>
                    </div>
                    <div class="toa-talent-field" id="toaTalentDomCityWrap">
                        <div class="city-typeahead">
                            <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Città / Comune','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                            <input type="text" name="dom_city_name" class="toa-talent-input" autocomplete="off" placeholder="<?php echo _ht_talent(array('it'=>'Inizia a digitare...','en'=>'Type...','fr'=>'Tape...','es'=>'Empieza...')); ?>">
                            <input type="hidden" name="dom_city_code">
                            <div class="toa-talent-error-msg"></div>
                        </div>
                        <div class="city-select" style="display:none;">
                            <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Area / Città','en'=>'Area / City','fr'=>'Zone / Ville','es'=>'Área / Ciudad')); ?> <span class="req">*</span></label>
                            <div class="toa-talent-customselect">
                                <input type="hidden" name="dom_city_code">
                                <input type="hidden" name="dom_city_name">
                                <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label"><?php echo _ht_talent(array('it'=>'Seleziona...','en'=>'Select...','fr'=>'Sélectionne...','es'=>'Selecciona...')); ?></span></div>
                                <div class="toa-talent-customselect-options"></div>
                            </div>
                            <div class="toa-talent-error-msg"></div>
                        </div>
                        <div class="city-free" style="display:none;">
                            <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Città','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                            <input type="text" name="dom_city_name" class="toa-talent-input" placeholder="Es. New York, Tokyo, ...">
                            <input type="hidden" name="dom_city_code">
                            <div class="toa-talent-error-msg"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toa-talent-actions">
                <button type="button" class="toa-talent-btn toa-talent-btn-ghost" data-go="1">← <?php echo _ht_talent(array('it'=>'Indietro','en'=>'Back','fr'=>'Retour','es'=>'Atrás')); ?></button>
                <button type="button" class="toa-talent-btn toa-talent-btn-primary" data-go="3"><?php echo _ht_talent(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> → <small style="display:block;font-weight:600;opacity:.75;font-size:.72rem;margin-top:2px;"><?php echo _ht_talent(array('it'=>'passaggio 3 di 4','en'=>'step 3 of 4','fr'=>'étape 3 sur 4','es'=>'paso 3 de 4')); ?></small></button>
            </div>
        </div>

        <!-- ═════ STEP 3 — Cosa fai ═════ -->
        <div class="toa-talent-step" data-step="3">
            <h3><?php echo _ht_talent(array('it'=>'Cosa fai','en'=>'What you do','fr'=>'Ce que tu fais','es'=>'Qué haces')); ?></h3>
            <p class="toa-talent-step-help"><?php echo _ht_talent(array('it'=>'Scegli i ruoli e compila le caratteristiche fisiche. Le categorie 18+ richiedono la maggiore età.','en'=>'Pick your roles and fill in your physical features. The 18+ categories require you to be of age.','fr'=>'Choisis tes rôles et remplis tes caractéristiques physiques. Les catégories 18+ exigent la majorité.','es'=>'Elige tus roles y rellena tus características físicas. Las categorías 18+ exigen ser mayor de edad.')); ?></p>

            <!-- Tipo talent forzato a immagine (backstage → form crew) -->
            <input type="hidden" name="tipo_talent" value="immagine">

            <!-- Ruoli (sempre visibile, nessun vincolo età) -->
            <div class="toa-talent-field" id="toaTalentRuoliImmagine">
                <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Ruoli (selezione multipla)','en'=>'Roles','fr'=>'Rôles','es'=>'Roles')); ?> <span class="req">*</span></label>
                <div class="toa-talent-categories" id="toaTalentCategoriesImmagine">
                    <?php foreach ($TALENT_RUOLI_IMMAGINE as $r): ?>
                        <label class="toa-talent-category-chip" data-code="<?php echo esc_attr($r['code']); ?>">
                            <input type="checkbox" name="ruoli_immagine[]" value="<?php echo esc_attr($r['code']); ?>">
                            <?php echo esc_html(_ht_talent_raw($r['label'])); ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <?php // 2026-08-14 (TEMA REGISTRAZIONE TALENT): "UGC Creator" è gergo, una riga per capirlo al volo ?>
                <?php // 2026-08-15 — invito ad aggiungere ruoli: le domande in più arrivano solo per quelli scelti ?>
                <p class="toa-alb-altriruoli"><?php echo _ht_talent_raw(array(
                    'it'=>'<strong>Puoi sceglierne più di uno:</strong> più ruoli, più lavori. Ti chiediamo solo quello che serve ai ruoli che scegli.',
                    'en'=>'<strong>You can pick more than one:</strong> more roles, more jobs. We only ask for what the roles you pick actually need.',
                    'fr'=>'<strong>Tu peux en cocher plusieurs :</strong> plus de rôles, plus de missions. On ne demande que ce qui sert aux rôles choisis.',
                    'es'=>'<strong>Puedes marcar más de uno:</strong> más roles, más trabajos. Solo te pedimos lo que hace falta para los roles que elijas.',
                )); ?></p>
                <small class="toa-talent-form-hint" style="display:block;margin-top:8px;color:#9ca3af;font-size:0.78rem;line-height:1.5;"><?php echo _ht_talent(array(
                    'it'=>'UGC Creator = giri contenuti per i brand. Influencer = pubblichi sui tuoi canali, col tuo pubblico.',
                    'en'=>'UGC Creator = you shoot content for brands. Influencer = you post on your own channels, to your own audience.',
                    'fr'=>'Créateur UGC = tu tournes des contenus pour les marques. Influenceur = tu publies sur tes canaux, avec ton public.',
                    'es'=>'Creador UGC = grabas contenidos para marcas. Influencer = publicas en tus canales, con tu público.',
                )); ?></small>
                <div class="toa-talent-error-msg"></div>
            </div>

            <!-- ═════ Caratteristiche fisiche (sempre visibile) ═════ -->
            <div class="toa-talent-fisico" id="toaTalentFisico">
                <h4>📐 <?php echo _ht_talent(array('it'=>'Caratteristiche fisiche','en'=>'Physical features','fr'=>'Caractéristiques physiques','es'=>'Características físicas')); ?></h4>
                <p class="toa-talent-step-help"><?php echo _ht_talent(array(
                    'it'=>'Servono per il match con i casting. Tutti i campi sono obbligatori.',
                    'en'=>'Used for casting matches. All fields are required.',
                    'fr'=>'Pour le matching casting. Tous les champs sont obligatoires.',
                    'es'=>'Para casting matching. Todos los campos son obligatorios.',
                )); ?></p>

                <div class="toa-talent-field-row">
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Altezza (cm)','en'=>'Height (cm)','fr'=>'Taille (cm)','es'=>'Altura (cm)')); ?> <span class="req">*</span></label>
                        <input type="number" name="altezza" class="toa-talent-input" min="100" max="220" placeholder="170" required>
                        <?php // 2026-08-14 (TEMA REGISTRAZIONE TALENT): istruzioni di misurazione + avviso veridicità ?>
                        <small class="toa-talent-form-hint" style="display:block;margin-top:6px;color:#9ca3af;font-size:0.78rem;line-height:1.5;"><?php echo _ht_talent(array(
                            'it'=>'Senza scarpe, fino alla sommità della testa. Dichiara la misura vera: sul set la verificano.',
                            'en'=>'No shoes, up to the top of the head. Declare your real height: they check it on set.',
                            'fr'=>'Sans chaussures, jusqu\'au sommet du crâne. Déclare ta vraie taille : elle est vérifiée sur le plateau.',
                            'es'=>'Sin zapatos, hasta la parte más alta de la cabeza. Declara tu altura real: la comprueban en el set.',
                        )); ?></small>
                    </div>
                </div>

                <div class="toa-talent-field-row">
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Taglia abbigliamento','en'=>'Clothing size','fr'=>'Taille','es'=>'Talla')); ?> <span class="req">*</span></label>
                        <div class="toa-talent-customselect">
                            <input type="hidden" name="taglia" value="">
                            <div class="toa-talent-customselect-trigger">
                                <span class="toa-talent-customselect-label">—</span>
                            </div>
                            <div class="toa-talent-customselect-options">
                                <?php foreach ($TALENT_TAGLIE as $t): ?>
                                    <div class="toa-talent-customselect-option" data-value="<?php echo esc_attr($t); ?>"><?php echo esc_html($t); ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="toa-talent-error-msg"></div>
                    </div>
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Numero di scarpe','en'=>'Shoe size','fr'=>'Pointure','es'=>'Calzado')); ?> <span class="req">*</span></label>
                        <input type="number" name="scarpe" class="toa-talent-input" min="30" max="50" placeholder="40" required>
                        <div class="toa-talent-error-msg"></div>
                    </div>
                </div>

                <div class="toa-talent-field-row">
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Colore occhi','en'=>'Eye color','fr'=>'Yeux','es'=>'Ojos')); ?> <span class="req">*</span></label>
                        <div class="toa-talent-customselect">
                            <input type="hidden" name="occhi" value="">
                            <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label">—</span></div>
                            <div class="toa-talent-customselect-options">
                                <?php foreach ($TALENT_OCCHI as $o): ?>
                                    <div class="toa-talent-customselect-option" data-value="<?php echo esc_attr($o['code']); ?>"><?php echo _ht_talent($o['label']); ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="toa-talent-error-msg"></div>
                    </div>
                    <div class="toa-talent-field">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Colore capelli','en'=>'Hair color','fr'=>'Cheveux','es'=>'Cabello')); ?> <span class="req">*</span></label>
                        <div class="toa-talent-customselect">
                            <input type="hidden" name="capelli" value="">
                            <div class="toa-talent-customselect-trigger"><span class="toa-talent-customselect-label">—</span></div>
                            <div class="toa-talent-customselect-options">
                                <?php foreach ($TALENT_CAPELLI as $c): ?>
                                    <div class="toa-talent-customselect-option" data-value="<?php echo esc_attr($c['code']); ?>"><?php echo _ht_talent($c['label']); ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="toa-talent-error-msg"></div>
                    </div>
                </div>

                <div class="toa-talent-field-row">
                    <div class="toa-talent-field" id="toaTalentEtniaField">
                        <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Etnia (selezione multipla)','en'=>'Ethnicity (multi-select)','fr'=>'Origine (sélection multiple)','es'=>'Etnia (selección múltiple)')); ?> <span class="req">*</span></label>
                        <div class="toa-talent-categories" id="toaTalentEtnieList">
                            <?php foreach ($TALENT_ETNIA as $e): ?>
                                <label class="toa-talent-category-chip" data-code="<?php echo esc_attr($e['code']); ?>">
                                    <input type="checkbox" name="etnia[]" value="<?php echo esc_attr($e['code']); ?>">
                                    <?php echo esc_html(_ht_talent_raw($e['label'])); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <small class="toa-talent-form-hint" style="display:block;margin-top:6px;color:#9ca3af;font-size:0.78rem;"><?php echo _ht_talent(array('it'=>'Max 2 etnie selezionabili','en'=>'Max 2 ethnicities','fr'=>'Max 2 origines','es'=>'Máx. 2 etnias')); ?></small>
                        <div class="toa-talent-error-msg"></div>
                    </div>
                </div>

                <!-- Misure — visibili solo col ruolo Modello/a (updateMisureVisibility in talent-form-v40.js).
                     2026-08-14 (TEMA REGISTRAZIONE TALENT): blocco richiuso di default, si apre solo chi le ha davvero. -->
                <div class="toa-talent-misure" id="toaTalentMisure" style="display:none;">
                    <details class="toa-talent-misure-acc" style="margin-top:12px;border:1px solid rgba(255,255,255,0.12);border-radius:8px;padding:10px 14px;">
                    <summary style="cursor:pointer;font-size:0.9rem;font-weight:600;color:#e5e7eb;"><?php echo _ht_talent(array(
                        'it'=>'📏 Misure corpo (facoltative) — apri solo se lavori nella moda',
                        'en'=>'📏 Body measurements (optional) — open only if you work in fashion',
                        'fr'=>'📏 Mensurations (facultatives) — ouvre seulement si tu travailles dans la mode',
                        'es'=>'📏 Medidas (opcionales) — abre solo si trabajas en moda',
                    )); ?></summary>
                    <p class="toa-talent-step-help" style="margin-top:10px;"><?php echo _ht_talent(array(
                        'it'=>'Servono per il fitting moda. Compila solo se le conosci: misure inventate fanno saltare il fitting.',
                        'en'=>'Used for fashion fittings. Fill them in only if you know them: made-up measurements blow the fitting.',
                        'fr'=>'Utiles pour le fitting mode. Ne remplis que si tu les connais : des mensurations inventées font rater le fitting.',
                        'es'=>'Sirven para el fitting de moda. Rellena solo si las conoces: unas medidas inventadas arruinan el fitting.',
                    )); ?></p>
                    <div class="toa-talent-field-row">
                        <div class="toa-talent-field">
                            <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Petto','en'=>'Bust','fr'=>'Poitrine','es'=>'Pecho')); ?></label>
                            <input type="number" name="misura_petto" class="toa-talent-input" min="50" max="150" placeholder="90">
                            <div class="toa-talent-error-msg"></div>
                        </div>
                        <div class="toa-talent-field">
                            <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Vita','en'=>'Waist','fr'=>'Taille','es'=>'Cintura')); ?></label>
                            <input type="number" name="misura_vita" class="toa-talent-input" min="40" max="150" placeholder="60">
                            <div class="toa-talent-error-msg"></div>
                        </div>
                        <div class="toa-talent-field">
                            <label class="toa-talent-label"><?php echo _ht_talent(array('it'=>'Fianchi','en'=>'Hips','fr'=>'Hanches','es'=>'Caderas')); ?></label>
                            <input type="number" name="misura_fianchi" class="toa-talent-input" min="50" max="150" placeholder="90">
                            <div class="toa-talent-error-msg"></div>
                        </div>
                    </div>
                    </details>
                </div>
            </div>

            <!-- Social -->
            <div class="toa-talent-field-row">
                <div class="toa-talent-field">
                    <label class="toa-talent-label">Instagram</label>
                    <input type="text" name="instagram" class="toa-talent-input" placeholder="@username">
                </div>
                <div class="toa-talent-field">
                    <label class="toa-talent-label">TikTok</label>
                    <input type="text" name="tiktok" class="toa-talent-input" placeholder="@username">
                </div>
            </div>
            <div class="toa-talent-field-row">
                <div class="toa-talent-field">
                    <label class="toa-talent-label">YouTube</label>
                    <input type="text" name="youtube" class="toa-talent-input" placeholder="@canale o URL">
                </div>
                <div class="toa-talent-field">
                    <label class="toa-talent-label">LinkedIn</label>
                    <input type="text" name="linkedin" class="toa-talent-input" placeholder="@profilo o URL">
                </div>
            </div>

            <div class="toa-talent-actions">
                <button type="button" class="toa-talent-btn toa-talent-btn-ghost" data-go="2">← <?php echo _ht_talent(array('it'=>'Indietro','en'=>'Back','fr'=>'Retour','es'=>'Atrás')); ?></button>
                <button type="button" class="toa-talent-btn toa-talent-btn-primary" data-go="4"><?php echo _ht_talent(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> → <small style="display:block;font-weight:600;opacity:.75;font-size:.72rem;margin-top:2px;"><?php echo _ht_talent(array('it'=>'ultimo passaggio','en'=>'last step','fr'=>'dernière étape','es'=>'último paso')); ?></small></button>
            </div>
        </div>

        <!-- ═════ STEP 4 — Foto profilo + Portfolio ═════ -->
        <div class="toa-talent-step" data-step="4">
            <h3><?php echo _ht_talent(array('it'=>'Foto, video e portfolio','en'=>'Photos, video & portfolio','fr'=>'Photos, vidéo et portfolio','es'=>'Fotos, vídeo y portfolio')); ?></h3>

            <!-- ALBUM FOTO — 2026-08-14 (TEMA REGISTRAZIONE TALENT)
                 Al posto del riquadro unico: una card per ogni album che serve al ruolo scelto.
                 Le card compaiono/spariscono da talent-form-v40.js in base ai ruoli spuntati allo Step 3. -->
            <style>
                /* linguette: si vedono tutti gli album in fila, si apre uno alla volta */
                /* 6 nomi per esteso non stanno in una riga sola nella colonna del form (servirebbero 1266px su 712):
                   griglia regolare 3+3, così si vedono TUTTI insieme senza scorrere e senza abbreviare i nomi. */
                .toa-alb-tabs{display:grid;grid-template-columns:repeat(3,1fr);gap:4px;margin-bottom:-1px}
                .toa-alb-tab{appearance:none;min-width:0;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.12);color:#9ca3af;font:600 .8rem/1.2 inherit;padding:10px 11px;border-radius:10px;cursor:pointer;display:flex;align-items:center;gap:6px}
                .toa-alb-tab:hover{color:#e5e7eb;background:rgba(255,255,255,.07)}
                /* la scheda aperta deve saltare all'occhio: fondo pieno, non una sfumatura */
                .toa-alb-tab.active{background:#c8ff00;border-color:#c8ff00;color:#0a0a0a;font-weight:800;box-shadow:0 3px 14px rgba(200,255,0,.28)}
                .toa-alb-tab.active .dot{background:#0a0a0a}
                .toa-alb-tab.active .n{opacity:1;color:#0a0a0a}
                .toa-alb-tab .dot{width:9px;height:9px;border-radius:50%;background:rgba(255,255,255,.25);flex:none}
                .toa-alb-tab.serve .dot{background:#c8ff00}
                .toa-alb-tab.done .dot{background:#10b981}
                .toa-alb-tab .n{font-size:.78rem;opacity:.8}
                .toa-album-card{border:2px solid rgba(200,255,0,.45);border-radius:14px;padding:18px;margin:8px 0 14px;background:rgba(255,255,255,.02)}
                .toa-alb-titolo{display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin:0 0 12px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,.10)}
                .toa-alb-titolo-eti{font-size:.72rem;text-transform:uppercase;letter-spacing:.6px;color:#9ca3af}
                .toa-alb-titolo strong{font-size:1.2rem;color:#c8ff00;letter-spacing:.2px}
                .toa-album-card[hidden]{display:none}
                /* così sì / così no affiancate */
                .toa-alb-ex{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px}
                /* 2026-08-14 — l'etichetta sì/no era troppo timida: ora è una fascia piena
                   sotto l'immagine, maiuscola, con la cornice dello stesso colore attorno. */
                .toa-alb-ex figure{margin:0;border-radius:12px;overflow:hidden;border:3px solid}
                .toa-alb-ex figure.si{border-color:#10b981}
                .toa-alb-ex figure.no{border-color:#ef4444}
                .toa-alb-ex figcaption{margin:0;padding:10px 4px;font-size:1.05rem;font-weight:800;letter-spacing:.6px;text-transform:uppercase;text-align:center;color:#fff;line-height:1.15}
                .toa-alb-ex figure.si figcaption{background:#10b981}
                .toa-alb-ex figure.no figcaption{background:#ef4444}
                .toa-alb-ex .toa-foto-gallery{width:100%;max-width:none;height:auto;aspect-ratio:3/4;margin:0;box-shadow:none;border:0;border-radius:0;position:relative}
                /* spunta / croce al centro della foto: solo il segno, senza cerchi */
                .toa-alb-mark{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:3;font-size:62px;font-weight:900;line-height:1;pointer-events:none;text-shadow:0 2px 10px rgba(0,0,0,.75),0 0 3px rgba(0,0,0,.9)}
                .toa-alb-ex figure.si .toa-alb-mark{color:#22e08a}
                .toa-alb-ex figure.no .toa-alb-mark{color:#ff4d4d}
                @media (max-width:480px){.toa-alb-mark{font-size:44px}}
                .toa-alb-ex .toa-fg-badge{display:none}
                .toa-alb-ex .toa-album-ph{max-width:none;height:auto;aspect-ratio:3/4;margin:0;border:0;border-radius:0}
                @media (max-width:480px){.toa-alb-ex figcaption{font-size:.9rem;padding:8px 2px;letter-spacing:.3px}}
                /* "aggiungi foto": pulsante nero tondo, non più il riquadro tratteggiato che sprecava spazio */
                .toa-album-card .toa-talent-dropzone{border:0;background:transparent;padding:10px 0 4px;min-height:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;text-align:center}
                .toa-album-card .toa-talent-dropzone-icon{width:104px;height:104px;flex:none;border-radius:50%;background:#000;border:2px solid rgba(255,255,255,.28);color:#c8ff00;font-size:52px;font-weight:300;line-height:100px;text-align:center;margin:0;transition:border-color .15s,transform .15s,box-shadow .15s}
                .toa-album-card .toa-talent-dropzone:hover .toa-talent-dropzone-icon{border-color:#c8ff00;transform:scale(1.06);box-shadow:0 0 0 6px rgba(200,255,0,.10)}
                .toa-album-card .toa-talent-dropzone-text{font-size:1rem;font-weight:700;color:#e5e7eb;margin:0}
                .toa-album-card .toa-talent-dropzone-hint{display:none}
                /* due pulsanti affiancati: foto e video */
                .toa-alb-azioni{display:flex;justify-content:center;gap:36px;flex-wrap:wrap}
                .toa-album-card .toa-alb-video .toa-talent-dropzone-icon{color:#60a5fa;font-size:34px;border-color:rgba(96,165,250,.45)}
                .toa-album-card .toa-alb-video:hover .toa-talent-dropzone-icon{border-color:#60a5fa;box-shadow:0 0 0 6px rgba(96,165,250,.12)}
                .toa-alb-video-nota{margin:14px 0 0;padding:11px 13px;border-radius:10px;font-size:.86rem;line-height:1.5;background:rgba(96,165,250,.08);border:1px solid rgba(96,165,250,.30);color:#bfdbfe}
                .toa-alb-vietato{display:inline-block;margin-top:4px;padding:3px 8px;border-radius:6px;background:rgba(239,68,68,.16);border:1px solid rgba(239,68,68,.5);color:#fca5a5;font-weight:800}
                .toa-alb-video-nota.forte{background:rgba(200,255,0,.08);border-color:rgba(200,255,0,.40);color:#e9ffa3;font-weight:600}
                .toa-alb-video-scelto{display:flex;align-items:center;gap:10px;margin-top:10px;padding:9px 12px;border-radius:8px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.15);font-size:.85rem;color:#e5e7eb}
                .toa-alb-video-scelto span{flex:1;min-width:0;word-break:break-all}
                .toa-alb-video-del{appearance:none;cursor:pointer;flex:none;width:26px;height:26px;border-radius:50%;border:0;background:rgba(0,0,0,.6);color:#fff;font-size:15px;line-height:1}
                .toa-alb-video-del:hover{background:#ff5060}
                .toa-alb-guida{display:inline-block;margin:0 0 14px;font-size:.92rem;font-weight:600;color:#c8ff00;text-decoration:underline}
                .toa-alb-wa-intro{margin:0 0 8px;padding:11px 13px;border-radius:10px 10px 0 0;background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.25);border-bottom:0;font-size:.87rem;line-height:1.55;color:#d1fae5}
                .toa-alb-wa-intro strong{color:#25D366}
                .toa-alb-wa{display:block;margin:0 0 14px;padding:12px 14px;border-radius:10px;background:rgba(37,211,102,.10);border:1px solid rgba(37,211,102,.35);color:#25D366;font-size:.92rem;font-weight:600;text-decoration:none;line-height:1.45}
                .toa-alb-wa:hover{background:rgba(37,211,102,.18)}
                @media (max-width:480px){.toa-alb-tabs{grid-template-columns:repeat(2,1fr)}.toa-alb-tab{padding:9px 10px;font-size:.76rem}.toa-album-card{padding:14px}}
                .toa-album-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:6px}
                .toa-album-head strong{font-size:1rem;letter-spacing:.2px}
                .toa-album-badge{font-size:.7rem;font-weight:700;letter-spacing:.4px;text-transform:uppercase;padding:3px 8px;border-radius:99px;white-space:nowrap}
                .toa-album-badge.req{background:rgba(200,255,0,.14);color:#c8ff00;border:1px solid rgba(200,255,0,.35)}
                .toa-album-badge.opt{background:rgba(255,255,255,.06);color:#9ca3af;border:1px solid rgba(255,255,255,.15)}
                .toa-album-hint{font-size:1rem;line-height:1.55;color:#e5e7eb;margin:0 0 14px}
                <?php /* 2026-08-14: tolto il vecchio limite 230x310 sulla galleria dentro le card —
                         restava dal layout a colonna unica e rimpiccioliva la colonna "così sì"
                         rispetto a quella "così no". Ora comandano le regole di .toa-alb-ex. */ ?>
                .toa-album-card .toa-fg-badge{font-size:15px;padding:7px 0}
                .toa-album-ph{max-width:230px;height:310px;margin:0 auto 14px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.04);border:1px dashed rgba(255,255,255,.18);color:#6b7280;font-size:.85rem;text-align:center;padding:10px}
                .toa-album-count{font-size:.85rem;color:#9ca3af;margin-top:8px;text-align:center}
                .toa-album-count.ok{color:#10b981}
                .toa-album-count.ok strong{color:#10b981}
                /* mese/anno dello scatto sotto ogni miniatura */
                /* la miniatura di serie è un quadrato con overflow:hidden — così il campo data
                   veniva ritagliato via e non si vedeva. Qui la miniatura torna libera in altezza. */
                .toa-album-card .toa-talent-thumbs{gap:12px;grid-template-columns:repeat(auto-fill,minmax(104px,1fr))}
                .toa-album-card .toa-talent-thumb{width:104px;height:auto;aspect-ratio:auto;overflow:visible;background:transparent;border:0;border-radius:0}
                .toa-album-card .toa-talent-thumb img{width:104px;height:104px;object-fit:cover;border-radius:8px;display:block;border:1px solid rgba(255,255,255,.12)}
                .toa-thumb-data-wrap{display:block;margin-top:6px}
                .toa-thumb-data-wrap label{display:block;font-size:.68rem;font-weight:600;line-height:1.25;color:#e5e7eb;margin-bottom:4px}
                .toa-thumb-data{display:block;width:104px;margin-bottom:4px;padding:5px 6px;font-size:.72rem;border-radius:6px;border:1px solid rgba(255,255,255,.22);background:#111;color:#e5e7eb}
                .toa-thumb-data.obbl{border-color:rgba(200,255,0,.5)}
                .toa-thumb-data.error{border-color:#ef4444;background:rgba(239,68,68,.12)}
                /* riquadro informativo con il pallino */
                .toa-alb-info{display:flex;gap:9px;align-items:flex-start;margin:14px 0 0;padding:10px 12px;border-radius:10px;background:rgba(59,130,246,.08);border:1px solid rgba(59,130,246,.30);font-size:.83rem;line-height:1.45;color:#bfdbfe}
                .toa-alb-info-dot{flex:none;width:20px;height:20px;border-radius:50%;background:#3b82f6;color:#fff;font:800 .78rem/20px Georgia,serif;text-align:center}
                .toa-alb-addrole{margin:0 0 14px;padding:12px 14px;border-radius:10px;background:rgba(59,130,246,.10);border:1px solid rgba(59,130,246,.40)}
                .toa-alb-addrole p{margin:0 0 10px;font-size:.9rem;line-height:1.45;color:#bfdbfe}
                .toa-alb-addrole-nota{margin:-4px 0 10px;font-size:.82rem;line-height:1.45;color:#9ec5fe;opacity:.9}
                .toa-alb-addrole-btn{appearance:none;cursor:pointer;margin:0 8px 0 0;padding:9px 14px;border-radius:99px;border:1px solid #3b82f6;background:rgba(59,130,246,.18);color:#fff;font:700 .9rem/1 inherit}
                .toa-alb-addrole-btn:hover{background:#3b82f6}
                /* 2026-08-15 — il "Continua" deve essere lo stesso in tutti i passaggi e non si deve
                   poter mancare: largo, alto, giallo pieno, con un alone che pulsa piano.
                   Su telefono prende tutta la larghezza, con l'Indietro piccolo di lato. */
                .toa-talent-actions{margin-top:28px}
                .toa-talent-btn-primary{padding:18px 40px !important;font-size:1.12rem !important;font-weight:800 !important;letter-spacing:.3px;border-radius:14px !important;box-shadow:0 6px 22px rgba(200,255,0,.30);animation:toaPulsa 2.6s ease-in-out infinite}
                .toa-talent-btn-primary:hover:not(:disabled){box-shadow:0 10px 30px rgba(200,255,0,.45) !important}
                /* Il pulsante finale era NERO: una regola globale del tema (button[type="submit"])
                   vince per specificità su .toa-talent-btn-primary. Qui lo riportiamo giallo come gli altri. */
                #toaTalentSubmit{background:#c8ff00 !important;color:#0a0a0a !important}
                #toaTalentSubmit:disabled{opacity:.45}
                .toa-talent-btn-ghost{padding:14px 20px !important;font-size:.92rem !important;opacity:.75}
                @keyframes toaPulsa{0%,100%{box-shadow:0 6px 22px rgba(200,255,0,.30)}50%{box-shadow:0 6px 26px rgba(200,255,0,.55)}}
                @media (prefers-reduced-motion:reduce){.toa-talent-btn-primary{animation:none}}
                @media (max-width:560px){
                    .toa-talent-actions{flex-wrap:wrap;gap:10px}
                    .toa-talent-btn-primary{flex:1 1 100%;order:1;padding:18px 22px !important}
                    .toa-talent-btn-ghost{order:2;flex:0 0 auto}
                }
                .toa-alb-promessa{margin:0 0 18px;padding:13px 15px;border-radius:10px;background:rgba(200,255,0,.07);border:1px solid rgba(200,255,0,.30);font-size:.95rem;line-height:1.55;color:#e9ffa3}
                .toa-alb-promessa strong{display:block;margin-bottom:3px;color:#c8ff00;font-size:1.02rem}
                /* riquadro "te la fai da solo": deve essere la prima cosa che si legge nelle Pola */
                .toa-alb-altriruoli{margin:10px 0 0;padding:11px 13px;border-radius:10px;background:rgba(200,255,0,.06);border:1px solid rgba(200,255,0,.25);font-size:.87rem;line-height:1.55;color:#e9ffa3}
                .toa-alb-altriruoli strong{color:#c8ff00}
                .toa-alb-descr{margin:0 0 14px;padding:14px 16px;border-radius:10px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.14);font-size:.95rem;line-height:1.6;color:#e5e7eb}
                .toa-alb-descr strong{display:block;margin-bottom:5px;color:#c8ff00;font-size:1rem}
                .toa-alb-potenziale{margin:0 0 14px;padding:12px 14px;border-radius:10px;background:rgba(96,165,250,.10);border:1px solid rgba(96,165,250,.40);font-size:.88rem;line-height:1.55;color:#bfdbfe}
                .toa-alb-potenziale strong{color:#93c5fd}
                .toa-alb-clou{margin:0 0 12px;padding:13px 15px;border-radius:10px;font-size:.95rem;line-height:1.55;color:#0a0a0a;background:#c8ff00;border:0}
                .toa-alb-clou strong{display:block;margin-bottom:3px;font-size:1rem}
                .toa-alb-quante{font-size:.86rem;line-height:1.5;color:#c8ff00;margin:0 0 14px;padding:9px 12px;border-radius:8px;background:rgba(200,255,0,.06);border:1px solid rgba(200,255,0,.20)}
                /* album non richiesto dai ruoli scelti: resta visibile ma spento */
                .toa-album-card.is-off{opacity:.5}
                .toa-album-serve{font-size:.8rem;font-weight:600;margin:0 0 10px}
                .toa-album-serve.on{color:#c8ff00}
                .toa-album-serve.off{color:#9ca3af}
                /* barra sempre a video: ancorata in basso, non si muove mai */
                .toa-alb-sticky{position:fixed;left:12px;right:12px;bottom:12px;max-width:740px;margin:0 auto;z-index:9999;padding:12px 14px;border-radius:14px;background:rgba(10,10,10,.94);backdrop-filter:blur(10px);border:1px solid rgba(200,255,0,.35);box-shadow:0 -6px 30px rgba(0,0,0,.6)}
                #toaTalentForm{padding-bottom:170px}
                .toa-alb-manca{max-height:30vh;overflow-y:auto}
                /* senza questa riga display:flex batte l'attributo hidden e l'elenco resta sempre aperto */
                .toa-alb-manca[hidden],.toa-alb-addrole[hidden]{display:none !important}
                /* su telefono la banda non deve mangiarsi mezzo schermo */
                @media (max-width:560px){
                    .toa-alb-sticky{padding:9px 11px}
                    .toa-alb-sticky-msg{font-size:.78rem;line-height:1.35;margin-top:5px}
                    .toa-alb-sticky-riga strong{font-size:.95rem;min-width:42px}
                    .toa-alb-sticky-btn{margin-top:7px;padding:6px 11px;font-size:.75rem}
                    .toa-alb-manca{max-height:22vh;gap:5px;margin-top:8px}
                    .toa-alb-manca-chip{padding:6px 9px;font-size:.72rem}
                }
                .toa-alb-torna{appearance:none;cursor:pointer;margin-top:9px;margin-left:8px;padding:7px 13px;border-radius:99px;border:1px solid rgba(255,255,255,.3);background:rgba(255,255,255,.08);color:#e5e7eb;font:700 .8rem/1 inherit}
                .toa-alb-evidenzia{outline:3px solid #c8ff00 !important;outline-offset:3px;border-radius:8px;transition:outline-color .3s}
                @media (max-width:480px){.toa-alb-sticky{left:6px;right:6px;bottom:6px;padding:10px 11px}#toaTalentForm{padding-bottom:200px}}
                .toa-alb-sticky-riga{display:flex;align-items:center;gap:12px}
                .toa-alb-sticky-riga .toa-albums-bar-track{flex:1}
                .toa-alb-sticky-riga strong{font-size:1.05rem;color:#c8ff00;min-width:48px;text-align:right}
                .toa-alb-sticky-msg{font-size:.84rem;line-height:1.45;color:#cbd5e1;margin-top:7px}
                .toa-alb-sticky-btn{appearance:none;cursor:pointer;margin-top:9px;padding:7px 13px;border-radius:99px;border:1px solid rgba(200,255,0,.45);background:transparent;color:#c8ff00;font:700 .8rem/1 inherit}
                .toa-alb-sticky-btn:hover{background:rgba(200,255,0,.12)}
                .toa-alb-manca{margin-top:10px;display:flex;flex-wrap:wrap;gap:7px}
                .toa-alb-manca-chip{appearance:none;cursor:pointer;padding:7px 11px;border-radius:99px;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.05);color:#e5e7eb;font:600 .78rem/1 inherit}
                .toa-alb-manca-chip:hover{border-color:#c8ff00;color:#c8ff00}
                .toa-alb-manca-ok{font-size:.85rem;color:#10b981;font-weight:700}
                .toa-albums-bar{margin:0 0 16px}
                .toa-albums-bar-track{height:8px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden}
                .toa-albums-bar-fill{height:100%;width:0;background:#c8ff00;border-radius:99px;transition:width .35s ease}
                .toa-albums-bar-label{font-size:.8rem;color:#cbd5e1;margin-top:6px}
                .toa-albums-bar-label strong{color:#c8ff00}
                @media (max-width:480px){.toa-album-examples{gap:8px}}
            </style>
            <div class="toa-talent-upload-section">
                <h5>📷 <?php echo _ht_talent(array('it'=>'Le tue foto e i tuoi video','en'=>'Your photos and videos','fr'=>'Tes photos et tes vidéos','es'=>'Tus fotos y tus vídeos')); ?></h5>
                <p class="toa-talent-step-help"><?php echo _ht_talent(array(
                    'it'=>'Più album completi tra quelli che ti servono, più lavori ricevi. Senza foto il profilo viene proposto meno.',
                    'en'=>'The more albums you complete among those you need, the more jobs you get. Without photos your profile gets proposed less.',
                    'fr'=>'Plus tu complètes d\'albums parmi ceux qu\'il te faut, plus tu reçois de missions. Sans photos, ta fiche est proposée moins souvent.',
                    'es'=>'Cuantos más álbumes completes entre los que necesitas, más trabajos recibes. Sin fotos tu perfil se propone menos.',
                )); ?></p>

                <div class="toa-talent-upload-counter" id="toaTalentPhotosCounter"><strong>0</strong> / 15</div>

                <div id="toaTalentAlbums"
                     data-serve-on="<?php echo esc_attr(_ht_talent(array('it'=>'★ Serve per i ruoli che hai scelto','en'=>'★ Needed for the roles you picked','fr'=>'★ Nécessaire pour tes rôles','es'=>'★ Necesario para tus roles'))); ?>"
                     data-serve-off="<?php echo esc_attr(_ht_talent(array('it'=>'Non richiesto per i tuoi ruoli — puoi caricarle lo stesso','en'=>'Not required for your roles — you can still upload','fr'=>'Pas requis pour tes rôles — tu peux quand même charger','es'=>'No requerido para tus roles — puedes subirlas igual'))); ?>"
                     data-cap-si="<?php echo esc_attr(_ht_talent(array('it'=>'Così sì','en'=>'Yes like this','fr'=>'Oui comme ça','es'=>'Así sí'))); ?>"
                     data-cap-no="<?php echo esc_attr(_ht_talent(array('it'=>'Così no','en'=>'Not like this','fr'=>'Pas comme ça','es'=>'Así no'))); ?>">
                <?php
                $badge_req = _ht_talent(array('it'=>'consigliata','en'=>'recommended','fr'=>'conseillé','es'=>'recomendado'));
                $badge_opt = _ht_talent(array('it'=>'facoltativa','en'=>'optional','fr'=>'facultatif','es'=>'opcional'));
                $badge_ok_g = _ht_talent(array('it'=>'Così sì','en'=>'Yes like this','fr'=>'Oui comme ça','es'=>'Así sí'));
                $badge_no_g = _ht_talent(array('it'=>'Così no','en'=>'Not like this','fr'=>'Pas comme ça','es'=>'Así no'));
                $ph_soon   = _ht_talent(array('it'=>'esempio in arrivo','en'=>'example coming','fr'=>'exemple à venir','es'=>'ejemplo próximamente'));
                $drop_txt  = _ht_talent(array('it'=>'Aggiungi foto','en'=>'Add photos','fr'=>'Ajoute des photos','es'=>'Añade fotos'));
                $lang_now  = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
                $wa_moda   = _ht_talent(array(
                    'it'=>'Ciao, sono interessato ad avere le informazioni per le foto professionali, settore moda, con un fotografo convenzionato dell\'agenzia.',
                    'en'=>'Hi, I\'d like information about professional photos, fashion, with a photographer affiliated with the agency.',
                    'fr'=>'Bonjour, je souhaite des informations sur les photos professionnelles, secteur mode, avec un photographe partenaire de l\'agence.',
                    'es'=>'Hola, quiero información sobre las fotos profesionales, sector moda, con un fotógrafo asociado a la agencia.',
                ));
                $wa_cinema = _ht_talent(array(
                    'it'=>'Ciao, sono interessato ad avere le informazioni per le foto professionali, settore cinema, con un fotografo convenzionato dell\'agenzia.',
                    'en'=>'Hi, I\'d like information about professional photos, film, with a photographer affiliated with the agency.',
                    'fr'=>'Bonjour, je souhaite des informations sur les photos professionnelles, secteur cinéma, avec un photographe partenaire de l\'agence.',
                    'es'=>'Hola, quiero información sobre las fotos profesionales, sector cine, con un fotógrafo asociado a la agencia.',
                ));
                $wa_label  = _ht_talent(array(
                    'it'=>'📸 Non le hai? Scattale con un fotografo convenzionato dell\'agenzia — scrivici su WhatsApp',
                    'en'=>'📸 Don\'t have them? Shoot them with a photographer affiliated with the agency — message us on WhatsApp',
                    'fr'=>'📸 Tu ne les as pas ? Fais-les avec un photographe partenaire de l\'agence — écris-nous sur WhatsApp',
                    'es'=>'📸 ¿No las tienes? Hazlas con un fotógrafo asociado a la agencia — escríbenos por WhatsApp',
                ));
                $guida_lbl = _ht_talent(array(
                    'it'=>'📖 Leggi la guida completa alle foto Pola →',
                    'en'=>'📖 Read the complete guide to Polaroid photos →',
                    'fr'=>'📖 Lis le guide complet des photos Polas →',
                    'es'=>'📖 Lee la guía completa de las fotos Pola →',
                ));
                ?>
                    <div class="toa-alb-tabs" role="tablist">
                    <?php foreach ($TALENT_ALBUM as $k => $al): ?>
                        <button type="button" class="toa-alb-tab<?php echo $k === 0 ? ' active' : ''; ?>" data-tab="<?php echo esc_attr($al['code']); ?>"><span class="dot"></span><?php echo esc_html(_ht_talent_raw($al['label'])); ?> <span class="n" id="toaTalentTabN_<?php echo esc_attr($al['code']); ?>"></span></button>
                    <?php endforeach; ?>
                    </div>
                <?php
                foreach ($TALENT_ALBUM as $k => $al):
                    $code = $al['code'];
                    $sl   = isset($TALENT_ALBUM_SLIDES[$code]) ? $TALENT_ALBUM_SLIDES[$code] : array('si'=>array(),'no'=>array());
                    $guida = isset($TALENT_ALBUM_GUIDA[$code][$lang_now]) ? $TALENT_ALBUM_GUIDA[$code][$lang_now] : (isset($TALENT_ALBUM_GUIDA[$code]['it']) ? $TALENT_ALBUM_GUIDA[$code]['it'] : '');
                ?>
                    <div class="toa-album-card" data-album="<?php echo esc_attr($code); ?>" data-roles="<?php echo esc_attr($al['roles']); ?>"<?php echo $k === 0 ? '' : ' hidden'; ?>>
                        <?php // 2026-08-14 — titolo dentro la scheda: si deve capire su quale album si sta caricando ?>
                        <div class="toa-alb-titolo">
                            <span class="toa-alb-titolo-eti"><?php echo _ht_talent(array('it'=>'Stai caricando in','en'=>'You are uploading to','fr'=>'Tu charges dans','es'=>'Estás subiendo a')); ?></span>
                            <strong><?php echo esc_html(_ht_talent_raw($al['label'])); ?></strong>
                            <span class="toa-album-badge <?php echo $al['req'] ? 'req' : 'opt'; ?>"><?php echo $al['req'] ? $badge_req : $badge_opt; ?></span>
                        </div>
                        <?php if (!empty($al['clou'])): ?>
                            <p class="toa-alb-clou"><?php echo _ht_talent_raw($al['clou']); ?></p>
                        <?php endif; ?>
                        <p class="toa-album-serve off"></p>
                        <?php
                        // 2026-08-14 — se apri un album che non serve ai ruoli spuntati, ti si chiede
                        // se vuoi aggiungere quel ruolo: così non devi tornare indietro a mano.
                        if ($al['roles'] !== '*'):
                            $codici = array_map('trim', explode(',', $al['roles']));
                            $etichette = array();
                            foreach ($TALENT_RUOLI_IMMAGINE as $_r) {
                                if (in_array($_r['code'], $codici, true)) $etichette[$_r['code']] = _ht_talent_raw($_r['label']);
                            }
                            if ($etichette):
                        ?>
                        <div class="toa-alb-addrole" hidden>
                            <?php // il testo nomina i ruoli veri dell'album: "serve a un ruolo" era troppo vago ?>
                            <p><?php echo esc_html(str_replace('%r', implode(' · ', $etichette), _ht_talent(array(
                                'it'=>'Questo album serve a: %r. Fai anche tu uno di questi lavori? Aggiungilo alla tua scheda e l\'album si accende.',
                                'en'=>'This album is for: %r. Do you do any of these too? Add it to your profile and the album lights up.',
                                'fr'=>'Cet album concerne : %r. Tu fais aussi l\'un de ces métiers ? Ajoute-le à ta fiche et l\'album s\'active.',
                                'es'=>'Este álbum es para: %r. ¿Tú también haces alguno de estos trabajos? Añádelo a tu ficha y el álbum se activa.',
                            )))); ?></p>
                                                        <?php foreach ($etichette as $rc => $rl): ?>
                                <button type="button" class="toa-alb-addrole-btn" data-role="<?php echo esc_attr($rc); ?>">
                                    + <?php echo esc_html($rl); ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; endif; ?>
                        <p class="toa-album-hint"><?php echo esc_html(_ht_talent_raw($al['hint'])); ?></p>
                        <?php if (!empty($al['quante'])): ?>
                            <p class="toa-alb-quante">📸 <?php echo esc_html(_ht_talent_raw($al['quante'])); ?></p>
                        <?php endif; ?>
                        <?php if ($guida): ?>
                            <a class="toa-alb-guida" href="<?php echo esc_url($guida); ?>" target="_blank" rel="noopener"><?php echo esc_html($guida_lbl); ?></a>
                        <?php endif; ?>
                        <?php if (in_array($code, array('portfolio','portfolio_cinema','dettaglio'), true)): ?>
                            <?php // 2026-08-14 — prima del pulsante: non andartene senza aver caricato quello che hai ?>
                            <p class="toa-alb-wa-intro"><?php echo _ht_talent_raw(array(
                                'it'=>'<strong>Non hai foto professionali?</strong> Carica quelle che hai in <strong>Pola</strong> e <strong>Altre foto</strong> e finisci la scheda. Per farle con un fotografo dell\'agenzia scrivici al <strong>+39 351 789 9225</strong>.',
                                'en'=>'<strong>No professional photos?</strong> Upload the ones you have in <strong>Polaroids</strong> and <strong>Other photos</strong> and finish your profile. To shoot them with an agency photographer message us at <strong>+39 351 789 9225</strong>.',
                                'fr'=>'<strong>Pas de photos professionnelles ?</strong> Charge celles que tu as dans <strong>Polas</strong> et <strong>Autres photos</strong> et termine ta fiche. Pour les faire avec un photographe de l\'agence écris-nous au <strong>+39 351 789 9225</strong>.',
                                'es'=>'<strong>¿No tienes fotos profesionales?</strong> Sube las que tienes en <strong>Polas</strong> y <strong>Otras fotos</strong> y termina la ficha. Para hacerlas con un fotógrafo de la agencia escríbenos al <strong>+39 351 789 9225</strong>.',
                            )); ?></p>
                            <a class="toa-alb-wa" data-wa="1" data-moda="<?php echo esc_attr($wa_moda); ?>" data-cinema="<?php echo esc_attr($wa_cinema); ?>" data-num="<?php echo esc_attr($TALENT_WA_NUM); ?>" href="https://wa.me/<?php echo esc_attr($TALENT_WA_NUM); ?>?text=<?php echo rawurlencode($wa_moda); ?>" target="_blank" rel="noopener"><?php echo esc_html($wa_label); ?></a>
                        <?php endif; ?>
                        <?php if (empty($sl) && !empty($al['descrizione'])): ?>
                            <?php // niente esempi per questo album: al loro posto la descrizione scritta ?>
                            <p class="toa-alb-descr"><?php echo _ht_talent_raw($al['descrizione']); ?></p>
                        <?php else: ?>
                        <?php // 2026-08-14 — due colonne che scorrono INSIEME: coppia sì/no dello stesso soggetto ?>
                        <div class="toa-alb-ex" data-paired="1">
                            <?php foreach (array(0, 1) as $col): ?>
                                <figure class="<?php echo $col === 0 ? 'si' : 'no'; ?>">
                                    <?php if (!empty($sl)): ?>
                                        <div class="toa-foto-gallery" data-auto="1">
                                            <?php foreach ($sl as $i => $coppia):
                                                $f = $coppia[$col];
                                                $kind = ($col === 0) ? 'si' : (isset($coppia[2]) ? $coppia[2] : 'no');
                                                $src = (substr($f, 0, 1) === '/') ? $f : $theme_uri . '/assets/' . $f; ?>
                                                <div class="toa-fg-slide<?php echo $i === 0 ? ' active' : ''; ?>" data-kind="<?php echo esc_attr($kind); ?>"><img src="<?php echo esc_url($src); ?>" alt="" loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"></div>
                                            <?php endforeach; ?>
                                            <?php // 2026-08-14 — spunta/croce al centro della foto: sulla fascia rossa la X non si distingueva ?>
                                            <span class="toa-alb-mark" aria-hidden="true"></span>
                                        </div>
                                    <?php else: ?>
                                        <div class="toa-album-ph"><?php echo esc_html($ph_soon); ?></div>
                                    <?php endif; ?>
                                    <figcaption><?php echo $col === 0 ? $badge_ok_g : $badge_no_g; ?></figcaption>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div class="toa-alb-azioni">
                            <div class="toa-talent-dropzone" id="toaTalentDrop_<?php echo esc_attr($code); ?>">
                                <div class="toa-talent-dropzone-icon">+</div>
                                <div class="toa-talent-dropzone-text"><strong><?php echo esc_html($drop_txt); ?></strong></div>
                                <div class="toa-talent-dropzone-hint">JPG, PNG</div>
                                <input type="file" id="toaTalentInput_<?php echo esc_attr($code); ?>" accept="image/*" multiple style="display:none;">
                            </div>
                            <?php // 2026-08-14 — secondo pulsante, solo dove il video serve davvero
                            if (!empty($al['video'])): ?>
                            <div class="toa-talent-dropzone toa-alb-video" id="toaTalentVidDrop_<?php echo esc_attr($code); ?>" data-albumvideo="<?php echo esc_attr($al['video']); ?>">
                                <div class="toa-talent-dropzone-icon">▶</div>
                                <div class="toa-talent-dropzone-text"><strong><?php echo esc_html(_ht_talent(array('it'=>'Carica video','en'=>'Upload video','fr'=>'Charge une vidéo','es'=>'Sube un vídeo'))); ?></strong></div>
                                <div class="toa-talent-dropzone-hint">MP4, MOV</div>
                                <input type="file" id="toaTalentVidInput_<?php echo esc_attr($code); ?>" accept="video/mp4,video/quicktime,video/webm" style="display:none;">
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php // 2026-08-15 — chi si dichiara UGC ma non ha ancora contenuti: non lo perdiamo,
                              // lo registriamo lo stesso e lo segnaliamo come potenziale. ?>
                        <?php if ($code === 'ugc'): ?>
                            <p class="toa-alb-potenziale" id="toaTalentUgcPotenziale"><?php echo _ht_talent_raw(array(
                                'it'=>'<strong>Non hai ancora contenuti?</strong> Registrati lo stesso: ti segniamo come <em>potenziale UGC</em> e ti contattiamo per aiutarti a farli, anche con lo staff TOAgency. Intanto carica quello che hai.',
                                'en'=>'<strong>No content yet?</strong> Register anyway: we flag you as a <em>potential UGC creator</em> and contact you to help you make your first ones, also with the TOAgency staff. Meanwhile upload what you have.',
                                'fr'=>'<strong>Pas encore de contenus ?</strong> Inscris-toi quand même : on te signale comme <em>créateur UGC potentiel</em> et on te contacte pour t\'aider à faire les premières, aussi avec l\'équipe TOAgency. En attendant charge ce que tu as.',
                                'es'=>'<strong>¿Aún no tienes contenidos?</strong> Regístrate igualmente: te marcamos como <em>potencial creador UGC</em> y te contactamos para ayudarte a hacer los primeros, también con el equipo de TOAgency. Mientras tanto sube lo que tengas.',
                            )); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($al['video'])): ?>
                            <p class="toa-alb-video-nota<?php echo $al['video'] === 'video_selftape' ? ' forte' : ''; ?>"><?php
                                // testi con <strong>/<br>: vanno in raw, altrimenti si vedono i tag
                                echo $al['video'] === 'video_selftape'
                                    ? _ht_talent_raw(array(
                                        'it'=>'🎬 <strong>Self-tape — per un attore è tutto.</strong><br>1. Nome, età e <strong>data di oggi</strong>.<br>2. <span class="toa-alb-vietato">⛔ Mai il cognome</span>, o il video non viene accettato.<br>3. Presentati e recita qualche battuta: un minuto basta. Telefono in verticale, luce davanti, sfondo neutro.<br>4. Primo piano e figura intera. <strong>Senza self-tape le produzioni non ti convocano.</strong>',
                                        'en'=>'🎬 <strong>Self-tape — for an actor this is everything.</strong><br>1. Name, age and <strong>today\'s date</strong>.<br>2. <span class="toa-alb-vietato">⛔ Never your surname</span>, or the video is not accepted.<br>3. Introduce yourself and perform a few lines: one minute is enough. Phone vertical, light in front, plain background.<br>4. Close-up and full body. <strong>Without a self-tape productions will not call you.</strong>',
                                        'fr'=>'🎬 <strong>Self-tape — pour un comédien c\'est tout.</strong><br>1. Prénom, âge et <strong>date du jour</strong>.<br>2. <span class="toa-alb-vietato">⛔ Jamais ton nom de famille</span>, sinon la vidéo est refusée.<br>3. Présente-toi et joue quelques répliques : une minute suffit. Téléphone vertical, lumière de face, fond neutre.<br>4. Gros plan et plein pied. <strong>Sans self-tape les productions ne te convoquent pas.</strong>',
                                        'es'=>'🎬 <strong>Self-tape — para un actor lo es todo.</strong><br>1. Nombre, edad y <strong>fecha de hoy</strong>.<br>2. <span class="toa-alb-vietato">⛔ Nunca el apellido</span>, o el vídeo no se acepta.<br>3. Preséntate e interpreta unas líneas: un minuto basta. Móvil vertical, luz de frente, fondo neutro.<br>4. Primer plano y cuerpo entero. <strong>Sin self-tape las producciones no te llaman.</strong>',
                                    ))
                                    : _ht_talent_raw(array(
                                        'it'=>'🎥 <strong>Video di presentazione — 30 secondi.</strong><br>1. Nome, età e <strong>data di oggi</strong>: «Sono Giulia, 24 anni, oggi è il 15 agosto».<br>2. <span class="toa-alb-vietato">⛔ Mai il cognome</span>, o il video non viene accettato.<br>3. Parla di quello che vuoi: serve solo a sentire la voce.<br>4. Mostra profili, mani davanti e dietro, denti. Poi figura intera e un giro su te stesso.',
                                        'en'=>'🎥 <strong>Presentation video — 30 seconds.</strong><br>1. Name, age and <strong>today\'s date</strong>: "I\'m Giulia, 24, today is 15 August".<br>2. <span class="toa-alb-vietato">⛔ Never your surname</span>, or the video is not accepted.<br>3. Talk about anything: it is only to hear your voice.<br>4. Show profiles, hands front and back, teeth. Then full body and one turn around.',
                                        'fr'=>'🎥 <strong>Vidéo de présentation — 30 secondes.</strong><br>1. Prénom, âge et <strong>date du jour</strong> : « Je suis Giulia, 24 ans, aujourd\'hui le 15 août ».<br>2. <span class="toa-alb-vietato">⛔ Jamais ton nom de famille</span>, sinon la vidéo est refusée.<br>3. Parle de ce que tu veux : c\'est pour entendre ta voix.<br>4. Montre profils, mains devant et derrière, dents. Puis plein pied et un tour sur toi-même.',
                                        'es'=>'🎥 <strong>Vídeo de presentación — 30 segundos.</strong><br>1. Nombre, edad y <strong>fecha de hoy</strong>: «Soy Giulia, 24 años, hoy es 15 de agosto».<br>2. <span class="toa-alb-vietato">⛔ Nunca el apellido</span>, o el vídeo no se acepta.<br>3. Habla de lo que quieras: sirve para oír tu voz.<br>4. Enseña perfiles, manos por delante y detrás, dientes. Luego cuerpo entero y una vuelta.',
                                    ));
                            ?></p>
                        <?php endif; ?>
                        <?php // 2026-08-14 — spiegazione della data, con il pallino informativo ?>
                        <p class="toa-alb-info"><span class="toa-alb-info-dot">i</span><?php echo _ht_talent(array(
                            'it'=>'Su ogni foto scegli mese e anno dello <strong>scatto</strong>, non di oggi: serve per contratti e liberatorie.',
                            'en'=>'On each photo pick the month and year it was <strong>taken</strong>, not today: it is used for contracts and releases.',
                            'fr'=>'Sur chaque photo choisis le mois et l\'année de la <strong>prise de vue</strong>, pas du jour : ça sert aux contrats et aux autorisations.',
                            'es'=>'En cada foto elige el mes y el año en que se <strong>hizo</strong>, no el de hoy: sirve para contratos y autorizaciones.',
                        )); ?></p>
                        <div class="toa-talent-thumbs" id="toaTalentThumbs_<?php echo esc_attr($code); ?>"></div>
                        <div class="toa-album-count" id="toaTalentCountBox_<?php echo esc_attr($code); ?>"><strong id="toaTalentCount_<?php echo esc_attr($code); ?>">0</strong> <?php echo _ht_talent(array('it'=>'foto caricate — consigliate da 3 a 8 per ogni album','en'=>'photos uploaded — 3 to 8 recommended for each album','fr'=>'photos chargées — 3 à 8 conseillées pour chaque album','es'=>'fotos subidas — de 3 a 8 recomendadas para cada álbum')); ?></div>
                    </div>
                <?php endforeach; ?>
                </div>
                <div class="toa-talent-error-msg" id="toaTalentPhotosError"></div>
            </div>

            <!-- NOTIFICHE CASTING — opt-in WhatsApp/SMS (facoltativo, NON pre-spuntato) 2026-06-30 marco -->
            <div class="toa-talent-field" style="background:rgba(37,211,102,0.05);border:1px solid rgba(37,211,102,0.25);border-radius:14px;padding:18px;margin-top:20px;">
                <strong style="color:#25D366;display:block;margin-bottom:10px;">📲 <?php echo _ht_talent(array(
                    'it'=>'Ricevi i casting adatti a te',
                    'en'=>'Get the castings that fit you',
                    'fr'=>'Reçois les castings faits pour toi',
                    'es'=>'Recibe los castings ideales para ti',
                )); ?></strong>
                <label class="toa-talent-checkbox" style="align-items:flex-start;">
                    <input type="checkbox" name="wa_consent" value="1" style="margin-top:6px;">
                    <span style="font-size:0.88rem;line-height:1.5;"><?php echo _ht_talent(array(
                        'it'=>'Voglio ricevere i casting urgenti su WhatsApp (il modo più veloce per non perderli).',
                        'en'=>'I want to receive urgent castings on WhatsApp (the fastest way not to miss them).',
                        'fr'=>'Je veux recevoir les castings urgents sur WhatsApp (le plus rapide pour ne pas les rater).',
                        'es'=>'Quiero recibir los castings urgentes por WhatsApp (lo más rápido para no perderlos).',
                    )); ?></span>
                </label>
                <label class="toa-talent-checkbox" style="align-items:flex-start;margin-top:8px;">
                    <input type="checkbox" name="sms_consent" value="1" style="margin-top:6px;">
                    <span style="font-size:0.88rem;line-height:1.5;"><?php echo _ht_talent(array(
                        'it'=>'Voglio ricevere i casting anche via SMS.',
                        'en'=>'I also want to receive castings by SMS.',
                        'fr'=>'Je veux aussi recevoir les castings par SMS.',
                        'es'=>'También quiero recibir los castings por SMS.',
                    )); ?></span>
                </label>
            </div>


            <!-- ═════════ CONSENSO PUBBLICAZIONE IMMAGINI (legge 633/41 + GDPR) ═════════ -->
            <div class="toa-talent-field" style="background:rgba(200,255,0,0.04);border:1px solid rgba(200,255,0,0.2);border-radius:14px;padding:18px;margin-top:20px;">
                <label class="toa-talent-checkbox" style="align-items:flex-start;">
                    <input type="checkbox" name="pubblicazione_immagini_consent" value="1" id="toaTalentPubblicazione" style="margin-top:6px;">
                    <span style="font-size:0.88rem;line-height:1.55;">
                        <strong style="color:#c8ff00;display:block;margin-bottom:6px;">📸 <?php echo _ht_talent(array(
                            'it'=>'Consenso alla pubblicazione delle immagini',
                            'en'=>'Consent to image publication',
                            'fr'=>'Consentement à la publication des images',
                            'es'=>'Consentimiento para la publicación de imágenes',
                        )); ?></strong>
                        <?php echo _ht_talent(array(
                            'it'=>'Acconsento alla pubblicazione delle foto del talent da parte di TOAgency sui propri canali ufficiali (sito web toagency.it, profili social, presentazioni a clienti aziendali, materiali promozionali) per finalità di promozione professionale e visibilità del profilo. Per i minori il consenso è prestato dal genitore/tutore. Posso revocare questo consenso in qualsiasi momento scrivendo a info@toagency.it; la rimozione delle immagini dai canali gestiti da TOAgency avverrà entro 30 giorni dalla richiesta. Riferimenti: Legge 633/1941 art. 96-97, GDPR Reg. UE 2016/679 art. 6-7, art. 10 c.c.',
                            'en'=>'I consent to TOAgency publishing the talent\'s photos on its official channels (website, social profiles, presentations to corporate clients, promotional materials) for the purposes of professional promotion and visibility. For minors, consent is given by the parent/guardian. I may revoke this consent at any time by writing to info@toagency.it; image removal from TOAgency-managed channels will occur within 30 days of the request. References: Italian Law 633/1941 art. 96-97, GDPR Reg. EU 2016/679 art. 6-7.',
                            'fr'=>'J\'autorise TOAgency à publier les photos du talent sur ses canaux officiels pour la promotion professionnelle. Pour les mineurs, le consentement est donné par le parent. Révocable à tout moment via info@toagency.it.',
                            'es'=>'Autorizo a TOAgency a publicar las fotos del talent en sus canales oficiales para promoción profesional. Para menores, el consentimiento lo da el padre/tutor. Revocable escribiendo a info@toagency.it.',
                        )); ?>
                        <br><br>
                        <em style="color:rgba(255,255,255,0.55);font-size:0.78rem;">
                            <?php echo _ht_talent(array(
                                'it'=>'⚠ Consenso facoltativo. Senza questo consenso il profilo verrà comunque salvato ma non sarà visibile pubblicamente.',
                                'en'=>'⚠ Optional consent. Without this, your profile will be saved but not publicly visible.',
                                'fr'=>'⚠ Facultatif. Sans ce consentement, le profil ne sera pas visible publiquement.',
                                'es'=>'⚠ Opcional. Sin este consentimiento, el perfil no será visible públicamente.',
                            )); ?>
                        </em>
                    </span>
                </label>
            </div>
            <!-- ═════════ FINE CONSENSO PUBBLICAZIONE ═════════ -->

            <!-- Honeypot anti-spam -->
            <div style="position:absolute; left:-9999px; opacity:0;" aria-hidden="true">
                <label>Non compilare<input type="text" name="honeypot_url" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="toa-talent-actions">
                <button type="button" class="toa-talent-btn toa-talent-btn-ghost" data-go="3">← <?php echo _ht_talent(array('it'=>'Indietro','en'=>'Back','fr'=>'Retour','es'=>'Atrás')); ?></button>
                <button type="submit" class="toa-talent-btn toa-talent-btn-primary" id="toaTalentSubmit">
                    <?php echo _ht_talent(array('it'=>'Invia candidatura','en'=>'Submit','fr'=>'Envoyer','es'=>'Enviar')); ?>
                </button>
            </div>
        </div>

    </form>
</section>

<!-- Modal successo (maggiorenne) -->
<div class="toa-talent-success" id="toaTalentSuccess" role="dialog" aria-modal="true">
    <div class="toa-talent-success-card">
        <div class="toa-talent-success-icon">✓</div>
        <h2><?php echo _ht_talent(array('it'=>'Candidatura inviata!','en'=>'Submitted!','fr'=>'Envoyé !','es'=>'¡Enviada!')); ?></h2>
        <p><?php echo _ht_talent(array(
            'it'=>'Grazie per esserti registrato come talent.',
            'en'=>'Thank you for registering as a talent.',
            'fr'=>'Merci pour ton inscription en tant que talent.',
            'es'=>'Gracias por registrarte como talent.',
        )); ?></p>
        <div class="toa-talent-success-info">
            ✨ <?php echo _ht_talent(array(
                'it'=>'Il nostro staff valuterà il profilo. Una volta approvato, sarai contattato per opportunità di lavoro.',
                'en'=>'Our staff will review the profile. Once approved, you will be contacted for job opportunities.',
                'fr'=>'Notre équipe examinera le profil et te contactera pour des opportunités.',
                'es'=>'Nuestro equipo revisará el perfil y te contactará para oportunidades.',
            )); ?>
        </div>
        <!-- FIX 2026-06-25 marco — CTA primaria step-2: completa il profilo (href popolato dal JS con uuid+token) -->
        <a id="toaTalentCompleteCta" href="#" rel="noopener"
           style="display:none;margin:1rem 0 .5rem;background:#6c63ff;color:#fff;border-radius:8px;padding:.85rem 1.2rem;font-weight:800;text-decoration:none;text-align:center;font-size:1rem;">
            ✏️ <?php echo _ht_talent(array(
                'it'=>'Completa il profilo: aggiungi più foto e info',
                'en'=>'Complete your profile: add more photos & info',
                'fr'=>'Complète ton profil : ajoute photos et infos',
                'es'=>'Completa tu perfil: añade más fotos e info',
            )); ?>
        </a>

        <!-- Avviso upload parziale fallito (inline, sostituisce alert()) -->
        <div id="toaTalentUploadWarn" role="alert" style="display:none;margin:.6rem 0;padding:.6rem .9rem;background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.4);border-radius:8px;color:#fbbf24;font-size:.85rem;line-height:1.4;"></div>
        <!-- FIX 2026-05-26 marco — WhatsApp post-registrazione multilingua -->
        <a href="https://wa.me/393518468516" target="_blank" rel="noopener"
           style="display:block;margin:.8rem 0 .4rem;background:#25d366;color:#fff;border-radius:8px;padding:.6rem 1.2rem;font-weight:700;text-decoration:none;text-align:center;font-size:.95rem;">
            💬 <?php echo _ht_talent(array(
                'it'=>'Scrivici su WhatsApp per aggiornamenti',
                'en'=>'Message us on WhatsApp for updates',
                'fr'=>'Écris-nous sur WhatsApp pour les mises à jour',
                'es'=>'Escríbenos por WhatsApp para novedades',
            )); ?>
        </a>
        <!-- FIX 2026-07-23 marco — CTA community rinforzate (WA regioni + IG + FB) -->
        <div id="toa-community-block" style="display:none;margin:1rem 0 .4rem;padding:.9rem;border:1px solid rgba(79,70,229,.5);border-radius:10px;background:rgba(79,70,229,.08);">
          <div style="font-weight:800;font-size:1rem;margin-bottom:.2rem;text-align:center;">
            📣 <?php echo _ht_talent(array('it'=>'Entra ORA nei gruppi dove pubblichiamo i casting','en'=>'Join NOW the groups where we post castings','fr'=>'Rejoins MAINTENANT les groupes où nous publions les castings','es'=>'Únete YA a los grupos donde publicamos los castings')); ?>
          </div>
          <div style="font-size:.82rem;opacity:.8;text-align:center;margin-bottom:.6rem;">
            <?php echo _ht_talent(array('it'=>'I casting li pubblichiamo qui ogni giorno, non solo via email.','en'=>'We post castings here every day, not only by email.','fr'=>'Nous publions les castings ici chaque jour, pas seulement par email.','es'=>'Publicamos los castings aquí cada día, no solo por email.')); ?>
          </div>
          <a id="toa-community-wa-btn" href="https://toagency.it/crm_toagency/onboarding-community.php" target="_blank" rel="noopener" style="display:block;background:#25d366;color:#fff;border-radius:8px;padding:.7rem 1.2rem;font-weight:800;text-decoration:none;text-align:center;font-size:.95rem;margin-bottom:.5rem;">
            💬 <?php echo _ht_talent(array('it'=>'Gruppi WhatsApp della tua regione','en'=>'WhatsApp groups for your region','fr'=>'Groupes WhatsApp de ta région','es'=>'Grupos de WhatsApp de tu región')); ?>
          </a>
          <div style="display:flex;gap:.5rem;">
            <a href="https://www.instagram.com/toagency/" target="_blank" rel="noopener" style="flex:1;background:#111;color:#fff;border-radius:8px;padding:.6rem;font-weight:700;text-decoration:none;text-align:center;font-size:.9rem;">Instagram</a>
            <a href="https://www.facebook.com/groups/hostessmodelscastingcalls" target="_blank" rel="noopener" style="flex:1;background:#111;color:#fff;border-radius:8px;padding:.6rem;font-weight:700;text-decoration:none;text-align:center;font-size:.9rem;">Facebook</a>
          </div>
        </div>
        <!-- 2026-07-24 marco — Video presentazione facoltativo (post-registrazione, mai bloccante) -->
        <div id="toaTalentVideo" style="display:none; margin:1rem 0; padding:1rem; border:1px solid rgba(200,255,0,.35); border-radius:12px; background:rgba(200,255,0,.05); text-align:left;">
            <div style="font-weight:800; font-size:1rem; margin-bottom:.35rem;">🎥 <?php echo _ht_talent(array('it'=>'Video di presentazione (facoltativo)','en'=>'Intro video (optional)','fr'=>'Vidéo de présentation (facultatif)','es'=>'Vídeo de presentación (opcional)')); ?></div>
            <div style="font-size:.82rem; opacity:.85; line-height:1.5; margin-bottom:.6rem;"><?php echo _ht_talent(array('it'=>'Scheda già salvata. Video facoltativo, max 50MB. Puoi anche chiudere.','en'=>'Profile saved. Video optional, max 50MB. You can also close.','fr'=>'Profil enregistré. Vidéo facultative, max 50 Mo. Tu peux fermer.','es'=>'Perfil guardado. Vídeo opcional, máx 50MB. Puedes cerrar.')); ?></div>
            <label style="display:flex; align-items:flex-start; gap:.5rem; font-size:.8rem; margin-bottom:.6rem; cursor:pointer;">
                <input type="checkbox" id="toaTalentVideoLegal" style="margin-top:.2rem;">
                <span><?php echo _ht_talent(array('it'=>'Ho i diritti e autorizzo la pubblicazione.','en'=>'I own the rights and allow publication.','fr'=>'Je détiens les droits et autorise la publication.','es'=>'Tengo los derechos y autorizo la publicación.')); ?></span>
            </label>
            <input type="file" id="toaTalentVideoInput" accept="video/mp4,video/quicktime,video/webm" style="display:none;">
            <div style="display:flex; gap:.5rem; flex-wrap:wrap; align-items:center;">
                <button type="button" id="toaTalentVideoChoose" style="background:#1a1a1e;border:1px solid #2a2a2e;color:#fff;padding:.6rem 1rem;border-radius:8px;font-weight:600;font-size:.85rem;cursor:pointer;">🎥 <?php echo _ht_talent(array('it'=>'Scegli video','en'=>'Choose video','fr'=>'Choisir','es'=>'Elegir')); ?></button>
                <span id="toaTalentVideoName" style="font-size:.8rem;opacity:.7;flex:1;min-width:0;word-break:break-all;">—</span>
                <button type="button" id="toaTalentVideoGo" style="background:#c8ff00;color:#0a0a0a;border:none;padding:.65rem 1.1rem;border-radius:8px;font-weight:800;font-size:.85rem;cursor:pointer;"><?php echo _ht_talent(array('it'=>'Carica','en'=>'Upload','fr'=>'Charger','es'=>'Subir')); ?></button>
            </div>
            <div id="toaTalentVideoStatus" style="font-size:.8rem;margin-top:.5rem;min-height:16px;"></div>
            <div id="toaTalentVideoHeavy" style="display:none;margin-top:.6rem;padding:.7rem;background:#0a0a0a;border:1px solid #2a2a2e;border-radius:8px;">
                <div style="font-size:.78rem;line-height:1.5;color:#cbd5e1;"><?php echo _ht_talent(array('it'=>'Troppo pesante? Esportalo a 720p o mandalo su WhatsApp, lo carichiamo noi.','en'=>'Too big? Export at 720p or send it on WhatsApp, we upload it.','fr'=>'Trop lourd ? Exporte en 720p ou envoie sur WhatsApp, on la charge.','es'=>'¿Muy pesado? Expórtalo a 720p o mándalo por WhatsApp, lo subimos.')); ?></div>
                <a id="toaTalentVideoWa" href="#" target="_blank" rel="noopener" style="display:inline-block;margin-top:.5rem;background:#25d366;color:#fff;padding:.55rem 1rem;border-radius:8px;font-weight:700;font-size:.85rem;text-decoration:none;">📲 <?php echo _ht_talent(array('it'=>'Invia il video su WhatsApp','en'=>'Send the video on WhatsApp','fr'=>'Envoyer sur WhatsApp','es'=>'Enviar por WhatsApp')); ?></a>
            </div>
        </div>
        <button type="button" class="toa-talent-success-close" id="toaTalentSuccessClose"><?php echo _ht_talent(array('it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar')); ?></button>
    </div>
</div>

<script src="<?php echo esc_url($theme_uri . '/assets/talent-form-v40.js'); ?>?v=20260817album51" defer></script><!-- 2026-08-14 (TEMA REGISTRAZIONE TALENT): bump v — album portfolio attore, linguette su una riga sola, pulsante tondo aggiungi foto; album a linguette, cosi-si/cosi-no affiancati, link guida Pola per lingua, CTA WhatsApp fotografo; gallerie che scorrono nelle card album, card sempre visibili, testi più grandi; album foto per ruolo + barra completamento (upload per album dietro interruttore USE_ALBUM_UPLOAD); typeahead comuni, match iniziale in cima + limite 12->30 + trattini/spazi/accenti non vincolanti; FIX 2026-06-25 marco: bump v — foto retry + recupero + check email step1; FIX 2026-06-28 marco: bump v — blocco doppione nome+cognome+dob; 2026-07-12 marco: bump v — LEAD CAPTURE Step 1 (foto+gdpr+disclaimer in Step 1, POST registra-step1) -->

<script>
// FIX 2026-05-26 marco — mostra community block se paese=IT
document.addEventListener('DOMContentLoaded', function() {
    var successModal = document.getElementById('toaTalentSuccess');
    if (!successModal) return;
    var observer = new MutationObserver(function() {
        if (successModal.classList.contains('toa-talent-success--visible') ||
            successModal.style.display !== 'none' && successModal.style.display !== '') {
            var block = document.getElementById('toa-community-block');
            if (block) {
                block.style.display = 'block'; // FIX 2026-07-23 marco — mostra per tutte le nazioni (parita email; onboarding-community gestisce IT/ES/FR/INT)
                var waBtn = document.getElementById('toa-community-wa-btn');
                if (waBtn) {
                    var natEl = document.querySelector('[name="res_nation"]');
                    var iso = (natEl && natEl.value ? natEl.value : '').toUpperCase();
                    if (['IT','ES','FR'].indexOf(iso) === -1) iso = 'INT';
                    var lg = (document.documentElement.getAttribute('lang') || 'it').substring(0,2).toLowerCase();
                    if (['it','en','fr','es'].indexOf(lg) === -1) lg = 'it';
                    var nomeEl = document.querySelector('[name="nome"]');
                    var nome = nomeEl ? (nomeEl.value || '').trim().substring(0,40) : '';
                    var href = 'https://toagency.it/crm_toagency/onboarding-community.php?paese=' + iso + '&lang=' + lg;
                    if (nome) href += '&nome=' + encodeURIComponent(nome);
                    waBtn.href = href;
                }
            }
        }
    });
    observer.observe(successModal, { attributes: true, attributeFilter: ['class','style'] });
});

// 2026-05-19 — limite 2 etnie selezionabili (mirror server-side $ETNIA_ALLOWED hard limit)
document.addEventListener('DOMContentLoaded', function() {
    var boxes = document.querySelectorAll('#toaTalentEtnieList input[name="etnia[]"]');
    if (!boxes.length) return;
    function update() {
        var checked = 0;
        boxes.forEach(function(c){ if (c.checked) checked++; });
        boxes.forEach(function(c){
            if (!c.checked) {
                c.disabled = (checked >= 2);
                var chip = c.closest('.toa-talent-category-chip');
                if (chip) chip.style.opacity = c.disabled ? '0.4' : '';
            } else {
                var chip2 = c.closest('.toa-talent-category-chip');
                if (chip2) chip2.style.opacity = '';
            }
        });
    }
    boxes.forEach(function(c){ c.addEventListener('change', update); });
    update();
});
</script>

<!-- 2026-07-12 marco — pre-compilazione form da link Brevo (?t=TOKEN). Consuma CRM registra-prefill.php. Solo tema. -->
<script>
(function(){
  'use strict';
  var STR = {
    intro:     <?php echo json_encode(_ht_talent(array('it'=>'Completa la tua scheda per candidarti — abbiamo già inserito i tuoi dati.','en'=>'Complete your profile to apply — we have pre-filled your details.','fr'=>'Complète ta fiche pour postuler — nous avons pré-rempli tes infos.','es'=>'Completa tu ficha para postularte — hemos rellenado tus datos.'))); ?>,
    already:   <?php echo json_encode(_ht_talent(array('it'=>'Hai già un profilo. Accedi per aggiornarlo.','en'=>'You already have a profile. Log in to update it.','fr'=>'Tu as déjà un profil. Connecte-toi pour le mettre à jour.','es'=>'Ya tienes un perfil. Accede para actualizarlo.'))); ?>,
    cittaHint: <?php echo json_encode(_ht_talent(array('it'=>'Hai indicato: %s — scegli la provincia dalla tendina.','en'=>'You entered: %s — pick your province below.','fr'=>'Tu as indiqué : %s — choisis ta région ci-dessous.','es'=>'Has indicado: %s — elige la provincia abajo.'))); ?>,
    ruoliHint: <?php echo json_encode(_ht_talent(array('it'=>'Hai indicato: %s','en'=>'You entered: %s','fr'=>'Tu as indiqué : %s','es'=>'Has indicado: %s'))); ?>
  };
  var ENDPOINT = 'https://toagency.it/crm_toagency/registra-prefill.php';
  var t = new URLSearchParams(location.search).get('t');
  if (!t || !/^[A-Za-z0-9_-]{10,}$/.test(t)) return; // niente token valido -> form normale

  function setVal(name, val){
    if (val === null || val === undefined || val === '') return;
    var el = document.querySelector('#toaTalentForm [name="'+name+'"]');
    if (!el) return;
    el.value = val;
    el.dispatchEvent(new Event('input', {bubbles:true}));
    el.dispatchEvent(new Event('change', {bubbles:true}));
  }
  function esc(s){ var d=document.createElement('div'); d.textContent=String(s); return d.innerHTML; }
  function hintBefore(anchorId, html){
    var a = document.getElementById(anchorId);
    if (!a || !a.parentNode) return;
    var p = document.createElement('div');
    p.style.cssText = 'margin:0 0 8px;padding:8px 12px;background:rgba(200,255,0,.08);border-left:3px solid #c8ff00;border-radius:4px;font-size:13px;color:#c8d0c0;';
    p.innerHTML = html;
    a.parentNode.insertBefore(p, a);
  }

  fetch(ENDPOINT + '?t=' + encodeURIComponent(t), { credentials:'same-origin' })
    .then(function(r){ return r.json(); })
    .then(function(d){
      if (!d || !d.success) return; // token assente/scaduto -> form vuoto, nessun errore
      if (d.gia_in_crm === 1 || d.gia_in_crm === '1' || d.gia_in_crm === true) {
        var form = document.getElementById('toaTalentForm');
        if (form && form.parentNode) {
          var msg = document.createElement('div');
          msg.style.cssText = 'text-align:center;padding:40px 20px;font-size:16px;color:#fff;';
          msg.textContent = STR.already;
          form.parentNode.insertBefore(msg, form);
          form.style.display = 'none';
        }
        return;
      }
      var p = d.prefill || {};
      setVal('nome', p.nome);
      setVal('cognome', p.cognome);
      setVal('email', p.email);
      setVal('data_nascita', p.data_nascita);
      setVal('altezza', p.altezza);
      setVal('scarpe', p.scarpe);
      setVal('instagram', p.instagram);
      setVal('telefono', p.telefono);
      var em = document.querySelector('#toaTalentForm [name="email"]');
      if (em && p.email) { em.setAttribute('readonly','readonly'); em.style.opacity = '0.8'; }
      var form2 = document.getElementById('toaTalentForm');
      if (form2) {
        var intro = document.createElement('div');
        intro.style.cssText = 'text-align:center;margin:0 0 14px;font-size:14px;color:#c8ff00;font-weight:600;';
        intro.textContent = STR.intro;
        form2.insertBefore(intro, form2.firstChild);
      }
      if (p.citta) hintBefore('toaTalentProvinceWrap', STR.cittaHint.replace('%s', '<strong>'+esc(p.citta)+'</strong>'));
      if (p.ruoli) hintBefore('toaTalentRuoliImmagine', STR.ruoliHint.replace('%s', '<strong>'+esc(p.ruoli)+'</strong>'));
    })
    .catch(function(){ /* errore rete -> form normale, nessun messaggio */ });
})();
</script>

<?php toa_component('footer'); ?>
