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
        'code' => 'polaroid', 'roles' => '*', 'req' => true,
        'label' => array('it'=>'Pola e presentazione','en'=>'Polaroids','fr'=>'Polas','es'=>'Polas'),
        'quante' => array(
            'it'=>'Da 3 a 8 foto: primo piano, mezzo busto, figura intera, profilo. Sempre senza filtri.',
            'en'=>'3 to 8 photos: close-up, chest-up, full length, profile. Always without filters.',
            'fr'=>'De 3 à 8 photos : gros plan, buste, plein pied, profil. Toujours sans filtres.',
            'es'=>'De 3 a 8 fotos: primer plano, medio cuerpo, cuerpo entero, perfil. Siempre sin filtros.',
        ),
        'hint'  => array(
            'it'=>'Tu come sei. Muro chiaro, luce di finestra, niente trucco e niente filtri. Corpo intero e primo piano. Te le fai col telefono in due minuti.',
            'en'=>'You as you are. Plain wall, window light, no makeup, no filters. Full body and close-up. Two minutes with your phone.',
            'fr'=>'Toi tel que tu es. Mur clair, lumière de fenêtre, sans maquillage ni filtres. Plein pied et gros plan. Deux minutes avec ton téléphone.',
            'es'=>'Tú tal cual eres. Pared clara, luz de ventana, sin maquillaje ni filtros. Cuerpo entero y primer plano. Dos minutos con el móvil.',
        ),
    ),
    array(
        'code' => 'portfolio', 'roles' => 'model,actor', 'req' => true,
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
        'code' => 'portfolio_cinema', 'roles' => 'actor', 'req' => false,
        'label' => array('it'=>'Portfolio attore','en'=>'Acting portfolio','fr'=>'Portfolio comédien','es'=>'Portfolio actor'),
        'quante' => array(
            'it'=>'Da 3 a 8 foto: primo piano espressivo, mezzo busto, una in scena o sul set.',
            'en'=>'3 to 8 photos: expressive close-up, chest-up, one on set or in character.',
            'fr'=>'De 3 à 8 photos : gros plan expressif, buste, une en scène ou sur le plateau.',
            'es'=>'De 3 a 8 fotos: primer plano expresivo, medio cuerpo, una en escena o en el set.',
        ),
        'hint'  => array(
            'it'=>'Scatti da book attoriale o fotogrammi dei tuoi lavori: primo piano espressivo, mezzo busto, luce naturale. Niente trucco pesante.',
            'en'=>'Acting book shots or frames from your work: expressive close-up, chest-up, natural light. No heavy makeup.',
            'fr'=>'Photos de book comédien ou images de tes travaux : gros plan expressif, buste, lumière naturelle. Pas de maquillage lourd.',
            'es'=>'Fotos de book actoral o fotogramas de tus trabajos: primer plano expresivo, medio cuerpo, luz natural. Sin maquillaje pesado.',
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
            'it'=>'Mani, profilo, capelli. Primi piani puliti su sfondo neutro: i casting moda li chiedono sempre.',
            'en'=>'Hands, profile, hair. Clean close-ups on a neutral background: fashion castings always ask for them.',
            'fr'=>'Mains, profil, cheveux. Gros plans nets sur fond neutre : les castings mode les demandent toujours.',
            'es'=>'Manos, perfil, pelo. Primeros planos limpios sobre fondo neutro: los castings de moda siempre los piden.',
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
    'polaroid'  => array(
        'si' => array('/wp-content/uploads/2026/06/image3-3.jpg', '/wp-content/uploads/2026/06/image5-3.jpg', '/wp-content/uploads/2026/06/image6-3.jpg', '/wp-content/uploads/2026/06/image7-4.jpg', '/wp-content/uploads/2026/06/image9-4.jpg'),
        'no' => array('guide/no-occhiali.jpg','guide/no-spiaggia.jpg','guide/no-gruppo.jpg','guide/no-palestra.jpg','guide/no-sport.jpg','guide/no-discoteca.jpg','guide/no-selfie-vicino.jpg','guide/no-spalle.jpg','guide/no-lontano.jpg','guide/no-filtro.jpg','guide/no-ritagliata.jpg','guide/no-bacio.jpg','guide/no-posa.jpg'),
    ),
    'eventi'    => array(
        'si' => array('staff/hostess.jpg', 'staff/steward.jpg', 'gallery/g08.jpg', 'staff/accoglienza.jpg', 'staff/interprete.jpg'),
        'no' => array('guide/no-occhiali.jpg','guide/no-spiaggia.jpg','guide/no-gruppo.jpg','guide/no-palestra.jpg','guide/no-sport.jpg','guide/no-discoteca.jpg','guide/no-selfie-vicino.jpg','guide/no-spalle.jpg','guide/no-lontano.jpg','guide/no-filtro.jpg','guide/no-ritagliata.jpg','guide/no-bacio.jpg','guide/no-posa.jpg'),
    ),
    // 2026-08-14 — immagini fornite da Marco, ottimizzate 900x1200 sotto i 300KB in assets/guide/.
    // Moda: 5 donna + 5 uomo (book studio, campagna, editoriale, sfilata, e-commerce).
    'portfolio' => array(
        'si' => array('guide/pf-moda-01.jpg','guide/pf-moda-06.jpg','guide/pf-moda-02.jpg','guide/pf-moda-07.jpg','guide/pf-moda-03.jpg','guide/pf-moda-08.jpg','guide/pf-moda-04.jpg','guide/pf-moda-09.jpg','guide/pf-moda-05.jpg','guide/pf-moda-10.jpg'),
        'no' => array('guide/no-occhiali.jpg','guide/no-spiaggia.jpg','guide/no-gruppo.jpg','guide/no-palestra.jpg','guide/no-sport.jpg','guide/no-discoteca.jpg','guide/no-selfie-vicino.jpg','guide/no-spalle.jpg','guide/no-lontano.jpg','guide/no-filtro.jpg','guide/no-ritagliata.jpg','guide/no-bacio.jpg','guide/no-posa.jpg'),
    ),
    // Attore: headshot casting, ritratto espressivo, sul set, in scena, backstage — donna e uomo alternati.
    'portfolio_cinema' => array(
        'si' => array('guide/pf-attore-01.jpg','guide/pf-attore-07.jpg','guide/pf-attore-02.jpg','guide/pf-attore-08.jpg','guide/pf-attore-03.jpg','guide/pf-attore-09.jpg','guide/pf-attore-04.jpg','guide/pf-attore-10.jpg','guide/pf-attore-05.jpg','guide/pf-attore-11.jpg','guide/pf-attore-06.jpg'),
        'no' => array('guide/no-occhiali.jpg','guide/no-spiaggia.jpg','guide/no-gruppo.jpg','guide/no-palestra.jpg','guide/no-sport.jpg','guide/no-discoteca.jpg','guide/no-selfie-vicino.jpg','guide/no-spalle.jpg','guide/no-lontano.jpg','guide/no-filtro.jpg','guide/no-ritagliata.jpg','guide/no-bacio.jpg','guide/no-posa.jpg'),
    ),
    // Dettagli: profilo e primo piano dall'articolo Pola + le digitals (fronte/retro/profilo).
    // Mancano ancora mani e capelli: nessuna immagine adatta nel sito.
    'dettaglio' => array(
        'si' => array('/wp-content/uploads/2026/06/image7-4.jpg', '/wp-content/uploads/2026/06/image6-3.jpg', '/wp-content/uploads/2026/06/model-digitals-polaroids-agency-submission.jpg'),
        'no' => array('guide/no-occhiali.jpg','guide/no-spiaggia.jpg','guide/no-gruppo.jpg','guide/no-palestra.jpg','guide/no-sport.jpg','guide/no-discoteca.jpg','guide/no-selfie-vicino.jpg','guide/no-spalle.jpg','guide/no-lontano.jpg','guide/no-filtro.jpg','guide/no-ritagliata.jpg','guide/no-bacio.jpg','guide/no-posa.jpg'),
    ),
    // Le 13 "foto sbagliate" di Marco (senza X stampata) fanno doppio lavoro: sono i "così no"
    // di tutti gli album professionali e, qui sotto, i "così sì" dell'album Altre foto —
    // mare, palestra, sport, discoteca, amici lì sono esempi BUONI. L'ordine viene mescolato
    // a ogni caricamento della pagina (data-shuffle in talent-form-v40.js).
    // Casual: le stesse foto, ma qui mare/palestra/discoteca/amici sono ESEMPI BUONI.
    'casual'    => array('si' => array('guide/no-spiaggia.jpg','guide/no-palestra.jpg','guide/no-sport.jpg','guide/no-discoteca.jpg','guide/no-gruppo.jpg','guide/no-bacio.jpg','guide/no-posa.jpg'), 'no' => array('guide/no-spalle.jpg','guide/no-lontano.jpg','guide/no-ritagliata.jpg','guide/no-filtro.jpg','guide/no-selfie-vicino.jpg','guide/no-occhiali.jpg')),
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
            'it'=>'Il tuo profilo verrà valutato dal nostro staff TOAgency. <strong>Una volta approvato</strong> sarà visibile alle aziende che cercano talent come te.',
            'en'=>'Your profile will be reviewed by our TOAgency staff. <strong>Once approved</strong> it will be visible to companies looking for talents like you.',
            'fr'=>'Ton profil sera évalué par notre équipe TOAgency. <strong>Une fois approuvé</strong>, il sera visible aux entreprises.',
            'es'=>'Tu perfil será evaluado por nuestro equipo TOAgency. <strong>Una vez aprobado</strong> será visible a las empresas.',
        )), array('strong'=>array(),'b'=>array(),'em'=>array(),'i'=>array(),'br'=>array())); ?>
    </div>

    <!-- Banner registrazione minore -->
    <div class="toa-talent-info-banner toa-talent-banner-secondary">
        👨‍👩‍👧 <?php echo wp_kses(_ht_talent_raw(array(
            'it'=>'<strong>Stai registrando un minore?</strong> Per talent sotto i 16 anni, il form deve essere compilato dal genitore/tutore legale. Per talent tra 16 e 17 anni, è necessaria la conferma del genitore.',
            'en'=>'<strong>Registering a minor?</strong> For talents under 16, the form must be filled by the parent/guardian. For 16-17 year olds, parent confirmation is required.',
            'fr'=>'<strong>Inscription d\'un mineur ?</strong> Pour les moins de 16 ans, le formulaire doit être rempli par le parent. Pour les 16-17 ans, la confirmation parentale est requise.',
            'es'=>'<strong>¿Registras a un menor?</strong> Para menores de 16, el formulario debe completarlo el padre/tutor. Para 16-17 años, se requiere confirmación del padre.',
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
            <p class="toa-talent-step-help"><?php echo _ht_talent(array('it'=>'Iniziamo con i dati base. Se stai registrando un minore, inserisci i suoi dati anagrafici (nome, data di nascita ecc.) e poi quelli del genitore.','en'=>'Basic info first. If registering a minor, enter the minor\'s personal data first.','fr'=>'Infos de base. Pour un mineur, saisis ses données.','es'=>'Datos básicos. Para un menor, introduce sus datos.')); ?></p>

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
                    'it'=>'Il talent ha meno di 16 anni. Inserisci i dati del genitore o tutore legale che compila il form e autorizza la registrazione.',
                    'en'=>'The talent is under 16. Enter the parent/guardian data who fills in the form and authorizes registration.',
                    'fr'=>'Talent de moins de 16 ans. Renseigne les données du parent qui autorise l\'inscription.',
                    'es'=>'Talent menor de 16 años. Introduce los datos del padre/tutor que autoriza el registro.',
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
                    'it'=>'Una foto chiara del viso, frontale, su sfondo neutro e luce naturale, come nell\'esempio qui sotto. Sarà la tua immagine principale.',
                    'en'=>'A clear, frontal photo of the face on a neutral background in natural light, like the example below. It will be your main image.',
                    'fr'=>'Une photo claire du visage, de face, sur fond neutre et en lumière naturelle, comme dans l\'exemple ci-dessous. Ce sera votre image principale.',
                    'es'=>'Una foto clara del rostro, frontal, con fondo neutro y luz natural, como en el ejemplo de abajo. Será tu imagen principal.',
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
                <button type="button" class="toa-talent-btn toa-talent-btn-primary" data-go="2"><?php echo _ht_talent(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> →</button>
            </div>
        </div>

        <!-- ═════ STEP 2 — Dove vivi ═════ -->
        <div class="toa-talent-step" data-step="2">
            <h3><?php echo _ht_talent(array('it'=>'Domicilio','en'=>'Domicile','fr'=>'Domicile','es'=>'Domicilio')); ?></h3>
            <p class="toa-talent-step-help"><?php echo _ht_talent(array('it'=>'Il domicilio coincide con la tua residenza? Se è diverso, indicacelo qui.','en'=>'Is your domicile the same as your residence? If not, tell us here.','fr'=>'Ton domicile est-il le même que ta résidence ?','es'=>'¿Tu domicilio coincide con tu residencia?')); ?></p>

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
                <button type="button" class="toa-talent-btn toa-talent-btn-primary" data-go="3"><?php echo _ht_talent(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> →</button>
            </div>
        </div>

        <!-- ═════ STEP 3 — Cosa fai ═════ -->
        <div class="toa-talent-step" data-step="3">
            <h3><?php echo _ht_talent(array('it'=>'Cosa fai','en'=>'What you do','fr'=>'Ce que tu fais','es'=>'Qué haces')); ?></h3>
            <p class="toa-talent-step-help"><?php echo _ht_talent(array('it'=>'Scegli i ruoli e compila le caratteristiche fisiche. Le categorie con badge "18+" richiedono la maggiore età.','en'=>'Choose roles and fill in physical features.','fr'=>'Rôles et caractéristiques physiques.','es'=>'Roles y características físicas.')); ?></p>

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
                <small class="toa-talent-form-hint" style="display:block;margin-top:8px;color:#9ca3af;font-size:0.78rem;line-height:1.5;"><?php echo _ht_talent(array(
                    'it'=>'UGC Creator = appari nei video creando contenuti per i brand. Influencer/Creator = pubblichi sui tuoi canali con il tuo pubblico.',
                    'en'=>'UGC Creator = you appear in videos making content for brands. Influencer/Creator = you post on your own channels to your own audience.',
                    'fr'=>'Créateur UGC = tu apparais dans des vidéos en créant du contenu pour les marques. Influenceur/Créateur = tu publies sur tes canaux, avec ton audience.',
                    'es'=>'Creador UGC = apareces en vídeos creando contenido para marcas. Influencer/Creador = publicas en tus canales, con tu público.',
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
                            'it'=>'Senza scarpe, fino alla sommità della testa (non ai capelli). Dichiara la misura vera: sul set viene verificata e un dato falso può farti perdere l\'ingaggio.',
                            'en'=>'No shoes, measured to the top of the head (not the hair). Declare your real height: it is checked on set and a false figure can cost you the job.',
                            'fr'=>'Sans chaussures, jusqu\'au sommet du crâne (pas les cheveux). Déclare ta vraie taille : elle est vérifiée sur le plateau et un chiffre faux peut te coûter le contrat.',
                            'es'=>'Sin zapatos, hasta la parte más alta de la cabeza (no el pelo). Declara tu altura real: se comprueba en el set y un dato falso puede costarte el trabajo.',
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
                        'it'=>'Servono per il fitting nei casting moda. Compila solo se le conosci davvero: misure inventate fanno saltare il fitting il giorno stesso. Se non le sai, lascia vuoto.',
                        'en'=>'Used for fitting in fashion castings. Fill them in only if you actually know them: made-up measurements blow the fitting on the day. If you don\'t know them, leave blank.',
                        'fr'=>'Utiles pour le fitting des castings mode. Ne remplis que si tu les connais vraiment : des mensurations inventées font rater le fitting le jour même. Sinon, laisse vide.',
                        'es'=>'Sirven para el fitting en castings de moda. Rellena solo si las conoces de verdad: unas medidas inventadas arruinan el fitting el mismo día. Si no las sabes, déjalo vacío.',
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
                <button type="button" class="toa-talent-btn toa-talent-btn-primary" data-go="4"><?php echo _ht_talent(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> →</button>
            </div>
        </div>

        <!-- ═════ STEP 4 — Foto profilo + Portfolio ═════ -->
        <div class="toa-talent-step" data-step="4">
            <h3><?php echo _ht_talent(array('it'=>'Foto e portfolio','en'=>'Photo & portfolio','fr'=>'Photo et portfolio','es'=>'Foto y portfolio')); ?></h3>

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
                .toa-alb-tab.active{background:rgba(200,255,0,.10);border-color:rgba(200,255,0,.45);color:#fff}
                .toa-alb-tab .dot{width:9px;height:9px;border-radius:50%;background:rgba(255,255,255,.25);flex:none}
                .toa-alb-tab.serve .dot{background:#c8ff00}
                .toa-alb-tab.done .dot{background:#10b981}
                .toa-alb-tab .n{font-size:.78rem;opacity:.8}
                .toa-album-card{border:1px solid rgba(255,255,255,.12);border-radius:14px;padding:18px;margin:6px 0 14px;background:rgba(255,255,255,.02)}
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
                .toa-alb-ex .toa-foto-gallery{width:100%;max-width:none;height:auto;aspect-ratio:3/4;margin:0;box-shadow:none;border:0;border-radius:0}
                .toa-alb-ex .toa-fg-badge{display:none}
                .toa-alb-ex .toa-album-ph{max-width:none;height:auto;aspect-ratio:3/4;margin:0;border:0;border-radius:0}
                @media (max-width:480px){.toa-alb-ex figcaption{font-size:.9rem;padding:8px 2px;letter-spacing:.3px}}
                /* "aggiungi foto": pulsante nero tondo, non più il riquadro tratteggiato che sprecava spazio */
                .toa-album-card .toa-talent-dropzone{border:0;background:transparent;padding:10px 0 4px;min-height:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;text-align:center}
                .toa-album-card .toa-talent-dropzone-icon{width:104px;height:104px;flex:none;border-radius:50%;background:#000;border:2px solid rgba(255,255,255,.28);color:#c8ff00;font-size:52px;font-weight:300;line-height:100px;text-align:center;margin:0;transition:border-color .15s,transform .15s,box-shadow .15s}
                .toa-album-card .toa-talent-dropzone:hover .toa-talent-dropzone-icon{border-color:#c8ff00;transform:scale(1.06);box-shadow:0 0 0 6px rgba(200,255,0,.10)}
                .toa-album-card .toa-talent-dropzone-text{font-size:1rem;font-weight:700;color:#e5e7eb;margin:0}
                .toa-album-card .toa-talent-dropzone-hint{display:none}
                .toa-alb-guida{display:inline-block;margin:0 0 14px;font-size:.92rem;font-weight:600;color:#c8ff00;text-decoration:underline}
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
                .toa-alb-quante{font-size:.86rem;line-height:1.5;color:#c8ff00;margin:0 0 14px;padding:9px 12px;border-radius:8px;background:rgba(200,255,0,.06);border:1px solid rgba(200,255,0,.20)}
                /* album non richiesto dai ruoli scelti: resta visibile ma spento */
                .toa-album-card.is-off{opacity:.5}
                .toa-album-serve{font-size:.8rem;font-weight:600;margin:0 0 10px}
                .toa-album-serve.on{color:#c8ff00}
                .toa-album-serve.off{color:#9ca3af}
                .toa-albums-bar{margin:0 0 16px}
                .toa-albums-bar-track{height:8px;border-radius:99px;background:rgba(255,255,255,.08);overflow:hidden}
                .toa-albums-bar-fill{height:100%;width:0;background:#c8ff00;border-radius:99px;transition:width .35s ease}
                .toa-albums-bar-label{font-size:.8rem;color:#cbd5e1;margin-top:6px}
                .toa-albums-bar-label strong{color:#c8ff00}
                @media (max-width:480px){.toa-album-examples{gap:8px}}
            </style>
            <div class="toa-talent-upload-section">
                <h5>📷 <?php echo _ht_talent(array('it'=>'Le tue foto','en'=>'Your photos','fr'=>'Tes photos','es'=>'Tus fotos')); ?></h5>
                <p class="toa-talent-step-help"><?php echo _ht_talent(array(
                    'it'=>'Questi sono gli album del tuo profilo. Più ne completi tra quelli che servono ai ruoli che hai scelto, più opportunità ricevi. Senza le foto richieste il tuo profilo viene proposto meno.',
                    'en'=>'These are your profile albums. The more you complete among those your chosen roles need, the more opportunities you get. Without the required photos your profile gets proposed less.',
                    'fr'=>'Voici les albums de ton profil. Plus tu en complètes parmi ceux qu\'exigent tes rôles, plus tu reçois d\'opportunités. Sans les photos demandées, ton profil est proposé moins souvent.',
                    'es'=>'Estos son los álbumes de tu perfil. Cuantos más completes entre los que piden tus roles, más oportunidades recibes. Sin las fotos requeridas tu perfil se propone menos.',
                )); ?></p>

                <div class="toa-albums-bar">
                    <div class="toa-albums-bar-track"><div class="toa-albums-bar-fill" id="toaTalentCompletenessFill"></div></div>
                    <div class="toa-albums-bar-label"><strong id="toaTalentCompletenessPct">0%</strong> <?php echo _ht_talent(array('it'=>'di profilo completo','en'=>'profile complete','fr'=>'de profil complété','es'=>'de perfil completo')); ?></div>
                </div>

                <div class="toa-talent-upload-counter" id="toaTalentPhotosCounter"><strong>0</strong> / 15</div>

                <div id="toaTalentAlbums"
                     data-serve-on="<?php echo esc_attr(_ht_talent(array('it'=>'★ Serve per i ruoli che hai scelto','en'=>'★ Needed for the roles you picked','fr'=>'★ Nécessaire pour tes rôles','es'=>'★ Necesario para tus roles'))); ?>"
                     data-serve-off="<?php echo esc_attr(_ht_talent(array('it'=>'Non richiesto per i tuoi ruoli — puoi caricarle lo stesso','en'=>'Not required for your roles — you can still upload','fr'=>'Pas requis pour tes rôles — tu peux quand même charger','es'=>'No requerido para tus roles — puedes subirlas igual'))); ?>">
                <?php
                $badge_req = _ht_talent(array('it'=>'consigliata','en'=>'recommended','fr'=>'conseillé','es'=>'recomendado'));
                $badge_opt = _ht_talent(array('it'=>'facoltativa','en'=>'optional','fr'=>'facultatif','es'=>'opcional'));
                $badge_ok_g = _ht_talent(array('it'=>'✅ Così sì','en'=>'✅ Yes like this','fr'=>'✅ Oui comme ça','es'=>'✅ Así sí'));
                $badge_no_g = _ht_talent(array('it'=>'❌ Così no','en'=>'❌ Not like this','fr'=>'❌ Pas comme ça','es'=>'❌ Así no'));
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
                        <p class="toa-album-serve off"></p>
                        <p class="toa-album-hint"><?php echo esc_html(_ht_talent_raw($al['hint'])); ?></p>
                        <?php if (!empty($al['quante'])): ?>
                            <p class="toa-alb-quante">📸 <?php echo esc_html(_ht_talent_raw($al['quante'])); ?></p>
                        <?php endif; ?>
                        <?php if ($guida): ?>
                            <a class="toa-alb-guida" href="<?php echo esc_url($guida); ?>" target="_blank" rel="noopener"><?php echo esc_html($guida_lbl); ?></a>
                        <?php endif; ?>
                        <?php if (in_array($code, array('portfolio','portfolio_cinema','dettaglio'), true)): ?>
                            <a class="toa-alb-wa" data-wa="1" data-moda="<?php echo esc_attr($wa_moda); ?>" data-cinema="<?php echo esc_attr($wa_cinema); ?>" data-num="<?php echo esc_attr($TALENT_WA_NUM); ?>" href="https://wa.me/<?php echo esc_attr($TALENT_WA_NUM); ?>?text=<?php echo rawurlencode($wa_moda); ?>" target="_blank" rel="noopener"><?php echo esc_html($wa_label); ?></a>
                        <?php endif; ?>
                        <div class="toa-alb-ex">
                            <?php foreach (array('si','no') as $kind):
                                $imgs = isset($sl[$kind]) ? $sl[$kind] : array(); ?>
                                <figure class="<?php echo esc_attr($kind); ?>">
                                    <?php if (!empty($imgs)): ?>
                                        <div class="toa-foto-gallery" data-auto="1" data-shuffle="1">
                                            <?php foreach ($imgs as $i => $f):
                                                $src = (substr($f, 0, 1) === '/') ? $f : $theme_uri . '/assets/' . $f; ?>
                                                <div class="toa-fg-slide<?php echo $i === 0 ? ' active' : ''; ?>"><img src="<?php echo esc_url($src); ?>" alt="" loading="<?php echo $i === 0 ? 'eager' : 'lazy'; ?>"></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="toa-album-ph"><?php echo esc_html($ph_soon); ?></div>
                                    <?php endif; ?>
                                    <figcaption class="<?php echo $kind; ?>"><?php echo $kind === 'si' ? $badge_ok_g : $badge_no_g; ?></figcaption>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                        <div class="toa-talent-dropzone" id="toaTalentDrop_<?php echo esc_attr($code); ?>">
                            <div class="toa-talent-dropzone-icon">+</div>
                            <div class="toa-talent-dropzone-text"><strong><?php echo esc_html($drop_txt); ?></strong></div>
                            <div class="toa-talent-dropzone-hint">JPG, PNG</div>
                            <input type="file" id="toaTalentInput_<?php echo esc_attr($code); ?>" accept="image/*" multiple style="display:none;">
                        </div>
                        <div class="toa-talent-thumbs" id="toaTalentThumbs_<?php echo esc_attr($code); ?>"></div>
                        <div class="toa-album-count" id="toaTalentCountBox_<?php echo esc_attr($code); ?>"><strong id="toaTalentCount_<?php echo esc_attr($code); ?>">0</strong> <?php echo _ht_talent(array('it'=>'foto caricate — consigliate da 3 a 8','en'=>'photos uploaded — 3 to 8 recommended','fr'=>'photos chargées — 3 à 8 conseillées','es'=>'fotos subidas — de 3 a 8 recomendadas')); ?></div>
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

<script src="<?php echo esc_url($theme_uri . '/assets/talent-form-v40.js'); ?>?v=20260814album14" defer></script><!-- 2026-08-14 (TEMA REGISTRAZIONE TALENT): bump v — album portfolio attore, linguette su una riga sola, pulsante tondo aggiungi foto; album a linguette, cosi-si/cosi-no affiancati, link guida Pola per lingua, CTA WhatsApp fotografo; gallerie che scorrono nelle card album, card sempre visibili, testi più grandi; album foto per ruolo + barra completamento (upload per album dietro interruttore USE_ALBUM_UPLOAD); typeahead comuni, match iniziale in cima + limite 12->30 + trattini/spazi/accenti non vincolanti; FIX 2026-06-25 marco: bump v — foto retry + recupero + check email step1; FIX 2026-06-28 marco: bump v — blocco doppione nome+cognome+dob; 2026-07-12 marco: bump v — LEAD CAPTURE Step 1 (foto+gdpr+disclaimer in Step 1, POST registra-step1) -->

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
