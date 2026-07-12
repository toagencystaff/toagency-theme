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
    array('code'=>'creator',    'min_age'=>14, 'label'=>array('it'=>'Content Creator','en'=>'Content Creator','fr'=>'Créateur de contenu','es'=>'Creador de contenido')),
    array('code'=>'influencer', 'min_age'=>14, 'label'=>array('it'=>'Influencer','en'=>'Influencer','fr'=>'Influenceur','es'=>'Influencer')),
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

        <div class="toa-talent-progress">
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

            <div class="toa-talent-actions">
                <span></span>
                <button type="button" class="toa-talent-btn toa-talent-btn-primary" data-go="2"><?php echo _ht_talent(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> →</button>
            </div>
        </div>

        <!-- ═════ STEP 2 — Dove vivi ═════ -->
        <div class="toa-talent-step" data-step="2">
            <h3><?php echo _ht_talent(array('it'=>'Dove vivi','en'=>'Where you live','fr'=>'Où tu vis','es'=>'Dónde vives')); ?></h3>
            <p class="toa-talent-step-help"><?php echo _ht_talent(array('it'=>'Indicaci la residenza. Se hai un domicilio diverso ti chiederemo anche quello.','en'=>'Tell us your residence.','fr'=>'Indique ta résidence.','es'=>'Indícanos tu residencia.')); ?></p>

            <!-- RESIDENZA -->
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

            <div class="toa-talent-field-row">
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
            </div>

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

                <!-- Misure (solo se sesso=F) -->
                <div class="toa-talent-misure" id="toaTalentMisure" style="display:none;">
                    <p class="toa-talent-step-help" style="margin-top:10px;"><?php echo _ht_talent(array(
                        'it'=>'Misure corpo (cm) — facoltative, utili per casting moda e fitting.',
                        'en'=>'Body measurements (cm) — optional, useful for fashion casting and fitting.',
                        'fr'=>'Mensurations (cm) — facultatives, utiles pour les castings mode.',
                        'es'=>'Medidas (cm) — opcionales, útiles para castings de moda.',
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

            <!-- Foto portfolio -->
            <div class="toa-talent-upload-section">
                <h5>📷 <?php echo _ht_talent(array('it'=>'Foto del portfolio (facoltative)','en'=>'Portfolio photos (optional)','fr'=>'Photos portfolio (facultatif)','es'=>'Fotos portfolio (opcional)')); ?></h5>
                <span style="display:inline-block;margin:2px 0 8px;font-size:0.78rem;color:#c8ff00;font-weight:600;letter-spacing:.2px;"><?php echo _ht_talent(array('it'=>'Facoltativo ma consigliato','en'=>'Optional but recommended','es'=>'Opcional pero recomendado','fr'=>'Facultatif mais conseillé')); ?></span>
                <p class="toa-talent-step-help"><?php echo _ht_talent(array(
                    'it'=>'Facoltativo — puoi aggiungerle anche dopo la registrazione. Ti consigliamo almeno 2-3 foto: i casting le guardano prima di contattarti.',
                    'en'=>'Optional — you can add them after registration. We recommend at least 2-3 photos: casting directors check them before reaching out.',
                    'fr'=>'Facultatif — tu peux les ajouter aussi après l\'inscription. Nous te conseillons au moins 2-3 photos : les directeurs de casting les regardent avant de te contacter.',
                    'es'=>'Opcional — puedes añadirlas también después del registro. Te recomendamos al menos 2-3 fotos: los directores de casting las miran antes de contactarte.',
                )); ?></p>
                <div class="toa-talent-upload-counter" id="toaTalentPhotosCounter"><strong>0</strong> / 15</div>
                <div class="toa-talent-dropzone" id="toaTalentPhotosDrop">
                    <div class="toa-talent-dropzone-icon">⬆️</div>
                    <div class="toa-talent-dropzone-text"><strong><?php echo _ht_talent(array('it'=>'Clicca','en'=>'Click','fr'=>'Clique','es'=>'Clic')); ?></strong> <?php echo _ht_talent(array('it'=>'o trascina qui le foto','en'=>'or drag photos','fr'=>'ou glisse','es'=>'o arrastra')); ?></div>
                    <div class="toa-talent-dropzone-hint">JPG, PNG • <?php /* TASK hardening-upload STEP A 2026-06-04 */ echo _ht_talent(array('it'=>'Carica le tue foto: le ottimizziamo noi automaticamente','en'=>'Upload your photos: we optimize them automatically','fr'=>'Charge tes photos : on les optimise automatiquement','es'=>'Sube tus fotos: las optimizamos automáticamente')); ?></div>
                    <input type="file" id="toaTalentPhotosInput" accept="image/*" multiple style="display:none;">
                </div>
                <div class="toa-talent-thumbs" id="toaTalentPhotosThumbs"></div>
                <div class="toa-talent-error-msg" id="toaTalentPhotosError"></div>
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
        <!-- FIX 2026-05-26 marco — community link post-registrazione (solo IT) -->
        <div id="toa-community-block" style="display:none;margin:.6rem 0 .4rem;">
            <a href="https://toagency.it/itacommunities-new.html" target="_blank" rel="noopener"
               style="display:block;background:#4f46e5;color:#fff;border-radius:8px;padding:.6rem 1.2rem;font-weight:700;text-decoration:none;text-align:center;font-size:.9rem;">
                📣 <?php echo _ht_talent(array(
                    'it'=>'Unisciti alla community della tua città → casting in diretta ogni giorno',
                    'en'=>'Join your city\'s community → live castings every day',
                    'fr'=>'Rejoins la communauté de ta ville → castings en direct chaque jour',
                    'es'=>'Únete a la comunidad de tu ciudad → castings en directo cada día',
                )); ?>
            </a>
        </div>
        <button type="button" class="toa-talent-success-close" id="toaTalentSuccessClose"><?php echo _ht_talent(array('it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar')); ?></button>
    </div>
</div>

<script src="<?php echo esc_url($theme_uri . '/assets/talent-form-v40.js'); ?>?v=20260629e" defer></script><!-- FIX 2026-06-25 marco: bump v — foto retry + recupero + check email step1; FIX 2026-06-28 marco: bump v — blocco doppione nome+cognome+dob -->

<script>
// FIX 2026-05-26 marco — mostra community block se paese=IT
document.addEventListener('DOMContentLoaded', function() {
    var successModal = document.getElementById('toaTalentSuccess');
    if (!successModal) return;
    var observer = new MutationObserver(function() {
        if (successModal.classList.contains('toa-talent-success--visible') ||
            successModal.style.display !== 'none' && successModal.style.display !== '') {
            var paeseEl = document.querySelector('[name="paese_residenza"]') ||
                          document.querySelector('[name="nation"]') ||
                          document.querySelector('select[name*="paese"]');
            var paese = paeseEl ? paeseEl.value : 'IT';
            var block = document.getElementById('toa-community-block');
            if (block) block.style.display = (paese === 'IT' || paese === '' || !paese) ? 'block' : 'none';
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
