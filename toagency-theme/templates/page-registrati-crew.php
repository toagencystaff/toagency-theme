<?php
/**
 * Template Name: Registrazione Crew
 * v3.0 — 28 Aprile 2026
 *
 * Path: /wp-content/themes/toagency-theme/templates/page-registrati-crew.php
 */

toa_component('header');
$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, array('it','en','fr','es'))) $__l = 'it';

if (!function_exists('_ht_crew')) {
    function _ht_crew($strings) {
        global $__l;
        return esc_html(isset($strings[$__l]) ? $strings[$__l] : $strings['it']);
    }
}
function _ht_crew_raw($strings) {
    global $__l;
    return isset($strings[$__l]) ? $strings[$__l] : $strings['it'];
}

$CREW_CATEGORIES = array(
    array('code'=>'fotografo','min_age'=>16,'label'=>array('it'=>'Fotografo','en'=>'Photographer','fr'=>'Photographe','es'=>'Fotógrafo')),
    array('code'=>'videomaker','min_age'=>16,'label'=>array('it'=>'Videomaker','en'=>'Videomaker','fr'=>'Vidéaste','es'=>'Videomaker')),
    array('code'=>'makeup_artist','min_age'=>16,'label'=>array('it'=>'Make-Up Artist','en'=>'Make-Up Artist','fr'=>'Maquilleur','es'=>'Maquillador')),
    array('code'=>'hairstylist','min_age'=>16,'label'=>array('it'=>'Hairstylist','en'=>'Hairstylist','fr'=>'Coiffeur','es'=>'Peluquero')),
    array('code'=>'parrucchiere','min_age'=>16,'label'=>array('it'=>'Parrucchiere','en'=>'Hairdresser','fr'=>'Coiffeur','es'=>'Peluquero')),
    array('code'=>'stylist','min_age'=>16,'label'=>array('it'=>'Stylist','en'=>'Stylist','fr'=>'Styliste','es'=>'Estilista')),
    array('code'=>'fashion_designer','min_age'=>16,'label'=>array('it'=>'Fashion Designer','en'=>'Fashion Designer','fr'=>'Créateur de mode','es'=>'Diseñador de moda')),
    array('code'=>'postproduzione','min_age'=>16,'label'=>array('it'=>'Postproduzione','en'=>'Photo Post-Production','fr'=>'Post-production','es'=>'Postproducción')),
    array('code'=>'video_editing','min_age'=>16,'label'=>array('it'=>'Video Editing','en'=>'Video Editing','fr'=>'Montage vidéo','es'=>'Edición de vídeo')),
    array('code'=>'social_media','min_age'=>16,'label'=>array('it'=>'Social Media Manager','en'=>'Social Media Manager','fr'=>'Social Media Manager','es'=>'Social Media Manager')),
    array('code'=>'content_creator','min_age'=>16,'label'=>array('it'=>'Content Creator','en'=>'Content Creator','fr'=>'Créateur de contenu','es'=>'Creador de contenido')),
    array('code'=>'fashion_journalist','min_age'=>16,'label'=>array('it'=>'Fashion Journalist','en'=>'Fashion Journalist','fr'=>'Journaliste mode','es'=>'Periodista de moda')),
    array('code'=>'art_director','min_age'=>16,'label'=>array('it'=>'Art Director','en'=>'Art Director','fr'=>'Directeur artistique','es'=>'Director de arte')),
    array('code'=>'dj','min_age'=>16,'label'=>array('it'=>'DJ','en'=>'DJ','fr'=>'DJ','es'=>'DJ')),
    array('code'=>'security','min_age'=>18,'label'=>array('it'=>'Security / Bodyguard','en'=>'Security / Bodyguard','fr'=>'Sécurité','es'=>'Seguridad')),
    array('code'=>'tecnico_luci','min_age'=>18,'label'=>array('it'=>'Tecnico Luci','en'=>'Lighting Technician','fr'=>'Technicien lumière','es'=>'Técnico iluminación')),
    array('code'=>'tecnico_suono','min_age'=>18,'label'=>array('it'=>'Tecnico Suono','en'=>'Sound Technician','fr'=>'Technicien son','es'=>'Técnico sonido')),
    array('code'=>'runner','min_age'=>18,'label'=>array('it'=>'Runner','en'=>'Runner','fr'=>'Runner','es'=>'Runner')),
    array('code'=>'altro','min_age'=>16,'label'=>array('it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro')),
);

$LIVELLI = array(
    array('code'=>'amatoriale','label'=>array('it'=>'Amatoriale','en'=>'Amateur','fr'=>'Amateur','es'=>'Amateur')),
    array('code'=>'studente','label'=>array('it'=>'Studente','en'=>'Student','fr'=>'Étudiant','es'=>'Estudiante')),
    array('code'=>'semi-pro','label'=>array('it'=>'Semi-professionista','en'=>'Semi-pro','fr'=>'Semi-pro','es'=>'Semi-profesional')),
    array('code'=>'professionista','label'=>array('it'=>'Professionista','en'=>'Professional','fr'=>'Professionnel','es'=>'Profesional')),
);

$theme_uri = get_stylesheet_directory_uri();
?>
<!-- TOA-CREW-FORM-V3 -->
<link rel="stylesheet" href="<?php echo esc_url($theme_uri . '/assets/crew-form.css'); ?>?v=3.0">
<script>window.toaThemeUri = "<?php echo esc_js($theme_uri); ?>";</script>

<section class="toa-crew-wrap">

    <div class="toa-crew-eyebrow"><?php echo _ht_crew(array('it'=>'Lavora con TOA','en'=>'Work with TOA','fr'=>'Travailler avec TOA','es'=>'Trabaja con TOA')); ?></div>
    <h1 class="toa-crew-title"><?php echo _ht_crew(array('it'=>'Registrati come Crew','en'=>'Register as Crew','fr'=>'Inscris-toi en tant que Crew','es'=>'Regístrate como Crew')); ?></h1>
    <p class="toa-crew-subtitle"><?php echo _ht_crew(array(
        'it'=>'Fotografi, videomaker, makeup artist, stylist e tutti i professionisti dietro la fotocamera.',
        'en'=>'Photographers, videomakers, makeup artists, stylists and every pro behind the camera.',
        'fr'=>'Photographes, vidéastes, maquilleurs, stylistes et tous les pros derrière la caméra.',
        'es'=>'Fotógrafos, videomakers, maquilladores, estilistas.',
    )); ?></p>

    <!-- Banner valutazione staff -->
    <div class="toa-crew-info-banner">
        ✨ <?php echo wp_kses(_ht_crew_raw(array(
            'it'=>'Il tuo profilo verrà valutato dal nostro staff TOAgency. <strong>Una volta approvato</strong> sarà visibile alle aziende che cercano professionisti come te.',
            'en'=>'Your profile will be reviewed by our TOAgency staff. <strong>Once approved</strong> it will be visible to companies looking for professionals like you.',
            'fr'=>'Ton profil sera évalué par notre équipe TOAgency. <strong>Une fois approuvé</strong>, il sera visible aux entreprises.',
            'es'=>'Tu perfil será evaluado por nuestro equipo TOAgency. <strong>Una vez aprobado</strong> será visible a las empresas.',
        )), array('strong'=>array(),'b'=>array(),'em'=>array(),'i'=>array(),'br'=>array())); ?>
    </div>

    <form id="toaCrewForm" novalidate enctype="multipart/form-data">

        <div class="toa-crew-progress">
            <div class="toa-crew-progress-step active" data-step="1"></div>
            <div class="toa-crew-progress-step" data-step="2"></div>
            <div class="toa-crew-progress-step" data-step="3"></div>
            <div class="toa-crew-progress-step" data-step="4"></div>
        </div>

        <!-- ═════ STEP 1 — Chi sei ═════ -->
        <div class="toa-crew-step active" data-step="1">
            <h3><?php echo _ht_crew(array('it'=>'Chi sei','en'=>'Who you are','fr'=>'Qui es-tu','es'=>'Quién eres')); ?></h3>
            <p class="toa-crew-step-help"><?php echo _ht_crew(array('it'=>'Iniziamo con i tuoi dati base.','en'=>'Basic info first.','fr'=>'Infos de base.','es'=>'Datos básicos.')); ?></p>

            <div class="toa-crew-field-row">
                <div class="toa-crew-field">
                    <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Nome','en'=>'First name','fr'=>'Prénom','es'=>'Nombre')); ?> <span class="req">*</span></label>
                    <input type="text" name="nome" class="toa-crew-input" required autocomplete="given-name">
                    <div class="toa-crew-error-msg"></div>
                </div>
                <div class="toa-crew-field">
                    <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Cognome','en'=>'Last name','fr'=>'Nom','es'=>'Apellido')); ?> <span class="req">*</span></label>
                    <input type="text" name="cognome" class="toa-crew-input" required autocomplete="family-name">
                    <div class="toa-crew-error-msg"></div>
                </div>
            </div>

            <div class="toa-crew-field">
                <label class="toa-crew-label">Email <span class="req">*</span></label>
                <input type="email" name="email" class="toa-crew-input" required autocomplete="email">
                <div class="toa-crew-error-msg"></div>
            </div>

            <!-- Telefono con prefisso -->
            <div class="toa-crew-field">
                <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono')); ?> <span class="req">*</span></label>
                <div class="toa-crew-phone-group">
                    <div class="toa-crew-customselect toa-crew-customselect-compact searchable" id="toaCrewPhonePrefix">
                        <input type="hidden" name="tel_paese_code" value="IT">
                        <div class="toa-crew-customselect-trigger">
                            <span class="toa-crew-customselect-label">🇮🇹 +39</span>
                        </div>
                        <div class="toa-crew-customselect-search"><input type="text" placeholder="Cerca paese..."></div>
                        <div class="toa-crew-customselect-options"></div>
                    </div>
                    <input type="tel" name="telefono" class="toa-crew-input" required autocomplete="tel" placeholder="333 1234567">
                </div>
                <div class="toa-crew-error-msg"></div>
            </div>

            <div class="toa-crew-field">
                <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Data di nascita','en'=>'Date of birth','fr'=>'Date de naissance','es'=>'Fecha de nacimiento')); ?> <span class="req">*</span></label>
                <input type="date" name="data_nascita" class="toa-crew-input" required>
                <div class="toa-crew-error-msg"></div>
            </div>

            <div class="toa-crew-age-block" id="toaCrewAgeBlock">
                <strong><?php echo _ht_crew(array('it'=>'Età non sufficiente','en'=>'Age not sufficient','fr'=>'Âge insuffisant','es'=>'Edad insuficiente')); ?></strong>
                <?php echo _ht_crew(array(
                    'it'=>'Per registrarti come crew devi avere almeno 16 anni.',
                    'en'=>'You must be at least 16.',
                    'fr'=>'Tu dois avoir au moins 16 ans.',
                    'es'=>'Debes tener al menos 16 años.',
                )); ?>
            </div>

            <!-- Sezione genitore -->
            <div class="toa-crew-genitore" id="toaCrewGenitore">
                <h4>👨‍👩‍👧 <?php echo _ht_crew(array('it'=>'Sei minorenne','en'=>'Underage','fr'=>'Mineur','es'=>'Menor')); ?></h4>
                <p><?php echo _ht_crew(array(
                    'it'=>'Avendo meno di 18 anni, abbiamo bisogno di un genitore o tutore legale che confermi via email.',
                    'en'=>'Under 18: we need a parent\'s confirmation by email.',
                    'fr'=>'Mineur : un parent doit confirmer.',
                    'es'=>'Menor: un padre/madre debe confirmar.',
                )); ?></p>
                <div class="toa-crew-field-row">
                    <div class="toa-crew-field">
                        <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Nome genitore','en'=>'Parent first name','fr'=>'Prénom parent','es'=>'Nombre padre')); ?> <span class="req">*</span></label>
                        <input type="text" name="genitore_nome" class="toa-crew-input">
                    </div>
                    <div class="toa-crew-field">
                        <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Cognome genitore','en'=>'Parent last name','fr'=>'Nom parent','es'=>'Apellido padre')); ?> <span class="req">*</span></label>
                        <input type="text" name="genitore_cognome" class="toa-crew-input">
                    </div>
                </div>
                <div class="toa-crew-field-row">
                    <div class="toa-crew-field">
                        <label class="toa-crew-label">Email <span class="req">*</span></label>
                        <input type="email" name="genitore_email" class="toa-crew-input">
                    </div>
                    <div class="toa-crew-field">
                        <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono')); ?> <span class="req">*</span></label>
                        <input type="tel" name="genitore_telefono" class="toa-crew-input">
                    </div>
                </div>
            </div>

            <div class="toa-crew-actions">
                <span></span>
                <button type="button" class="toa-crew-btn toa-crew-btn-primary" data-go="2"><?php echo _ht_crew(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> →</button>
            </div>
        </div>

        <!-- ═════ STEP 2 — Dove vivi ═════ -->
        <div class="toa-crew-step" data-step="2">
            <h3><?php echo _ht_crew(array('it'=>'Dove vivi','en'=>'Where you live','fr'=>'Où tu vis','es'=>'Dónde vives')); ?></h3>
            <p class="toa-crew-step-help"><?php echo _ht_crew(array('it'=>'Indicaci la tua residenza. Se hai un domicilio diverso ti chiederemo anche quello.','en'=>'Tell us your residence.','fr'=>'Indique ta résidence.','es'=>'Indícanos tu residencia.')); ?></p>

            <!-- RESIDENZA -->
            <div class="toa-crew-field">
                <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Nazione di residenza','en'=>'Country of residence','fr'=>'Pays de résidence','es'=>'País de residencia')); ?> <span class="req">*</span></label>
                <div class="toa-crew-customselect searchable" id="toaCrewNation">
                    <input type="hidden" name="res_nation" value="">
                    <div class="toa-crew-customselect-trigger"><span class="toa-crew-customselect-label">—</span></div>
                    <div class="toa-crew-customselect-search"><input type="text" placeholder="Cerca paese..."></div>
                    <div class="toa-crew-customselect-options"></div>
                </div>
                <div class="toa-crew-error-msg"></div>
            </div>

            <div class="toa-crew-field-row">
                <div class="toa-crew-field" id="toaCrewProvinceWrap">
                    <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Provincia','en'=>'Province','fr'=>'Région','es'=>'Provincia')); ?> <span class="req">*</span></label>
                    <div class="toa-crew-customselect searchable" id="toaCrewProvince">
                        <input type="hidden" name="res_provincia" value="">
                        <div class="toa-crew-customselect-trigger"><span class="toa-crew-customselect-label"><?php echo _ht_crew(array('it'=>'Seleziona provincia...','en'=>'Select province...','fr'=>'Sélectionne...','es'=>'Selecciona...')); ?></span></div>
                        <div class="toa-crew-customselect-search"><input type="text" placeholder="Cerca..."></div>
                        <div class="toa-crew-customselect-options"></div>
                    </div>
                    <div class="toa-crew-error-msg"></div>
                </div>
                <div class="toa-crew-field" id="toaCrewCityWrap">
                    <!-- Container 1: Typeahead per Italia (visibile di default) -->
                    <div class="city-typeahead">
                        <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Città / Comune','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                        <input type="text" name="res_city_name" class="toa-crew-input" autocomplete="off" placeholder="<?php echo _ht_crew(array('it'=>'Inizia a digitare...','en'=>'Type...','fr'=>'Tape...','es'=>'Empieza...')); ?>">
                        <input type="hidden" name="res_city_code">
                        <div class="toa-crew-error-msg"></div>
                    </div>
                    <!-- Container 2: Select per FR/ES/CH/GB (nascosto di default) -->
                    <div class="city-select" style="display:none;">
                        <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Area / Città','en'=>'Area / City','fr'=>'Zone / Ville','es'=>'Área / Ciudad')); ?> <span class="req">*</span></label>
                        <div class="toa-crew-customselect">
                            <input type="hidden" name="res_city_code">
                            <input type="hidden" name="res_city_name">
                            <div class="toa-crew-customselect-trigger"><span class="toa-crew-customselect-label"><?php echo _ht_crew(array('it'=>'Seleziona...','en'=>'Select...','fr'=>'Sélectionne...','es'=>'Selecciona...')); ?></span></div>
                            <div class="toa-crew-customselect-options"></div>
                        </div>
                        <div class="toa-crew-error-msg"></div>
                    </div>
                    <!-- Container 3: Free text per resto del mondo (nascosto di default) -->
                    <div class="city-free" style="display:none;">
                        <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Città','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                        <input type="text" name="res_city_name" class="toa-crew-input" placeholder="Es. New York, Tokyo, ...">
                        <input type="hidden" name="res_city_code">
                        <div class="toa-crew-error-msg"></div>
                    </div>
                </div>
            </div>

            <!-- Toggle domicilio -->
            <div class="toa-crew-field">
                <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Il tuo domicilio coincide con la residenza?','en'=>'Same domicile as residence?','fr'=>'Domicile = résidence ?','es'=>'¿Domicilio = residencia?')); ?></label>
                <div class="toa-crew-toggle-group" id="toaCrewDomCoincideGroup">
                    <input type="hidden" name="dom_coincide" value="1">
                    <div class="toa-crew-toggle active" data-value="1"><?php echo _ht_crew(array('it'=>'Sì','en'=>'Yes','fr'=>'Oui','es'=>'Sí')); ?></div>
                    <div class="toa-crew-toggle" data-value="0"><?php echo _ht_crew(array('it'=>'No, diverso','en'=>'No, different','fr'=>'Non','es'=>'No')); ?></div>
                </div>
            </div>

            <!-- Box domicilio diverso -->
            <div class="toa-crew-domicilio-box" id="toaCrewDomicilioBox" style="display:none;">
                <div class="toa-crew-domicilio-info">
                    📍 <?php echo _ht_crew(array(
                        'it'=>'Sarai informato dei casting di entrambi i luoghi (residenza + domicilio).',
                        'en'=>'You will be informed of castings in both locations.',
                        'fr'=>'Tu seras informé des castings dans les deux zones.',
                        'es'=>'Serás informado de castings en ambas zonas.',
                    )); ?>
                </div>

                <div class="toa-crew-field">
                    <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Nazione di domicilio','en'=>'Country of domicile','fr'=>'Pays domicile','es'=>'País domicilio')); ?> <span class="req">*</span></label>
                    <div class="toa-crew-customselect searchable" id="toaCrewDomNation">
                        <input type="hidden" name="dom_nation" value="">
                        <div class="toa-crew-customselect-trigger"><span class="toa-crew-customselect-label">—</span></div>
                        <div class="toa-crew-customselect-search"><input type="text" placeholder="Cerca..."></div>
                        <div class="toa-crew-customselect-options"></div>
                    </div>
                </div>

                <div class="toa-crew-field-row">
                    <div class="toa-crew-field" id="toaCrewDomProvinceWrap">
                        <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Provincia','en'=>'Province','fr'=>'Région','es'=>'Provincia')); ?> <span class="req">*</span></label>
                        <div class="toa-crew-customselect searchable" id="toaCrewDomProvince">
                            <input type="hidden" name="dom_provincia" value="">
                            <div class="toa-crew-customselect-trigger"><span class="toa-crew-customselect-label">—</span></div>
                            <div class="toa-crew-customselect-search"><input type="text" placeholder="Cerca..."></div>
                            <div class="toa-crew-customselect-options"></div>
                        </div>
                    </div>
                    <div class="toa-crew-field" id="toaCrewDomCityWrap">
                        <!-- Container 1: Typeahead per Italia -->
                        <div class="city-typeahead">
                            <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Città / Comune','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                            <input type="text" name="dom_city_name" class="toa-crew-input" autocomplete="off" placeholder="<?php echo _ht_crew(array('it'=>'Inizia a digitare...','en'=>'Type...','fr'=>'Tape...','es'=>'Empieza...')); ?>">
                            <input type="hidden" name="dom_city_code">
                            <div class="toa-crew-error-msg"></div>
                        </div>
                        <!-- Container 2: Select per FR/ES/CH/GB -->
                        <div class="city-select" style="display:none;">
                            <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Area / Città','en'=>'Area / City','fr'=>'Zone / Ville','es'=>'Área / Ciudad')); ?> <span class="req">*</span></label>
                            <div class="toa-crew-customselect">
                                <input type="hidden" name="dom_city_code">
                                <input type="hidden" name="dom_city_name">
                                <div class="toa-crew-customselect-trigger"><span class="toa-crew-customselect-label"><?php echo _ht_crew(array('it'=>'Seleziona...','en'=>'Select...','fr'=>'Sélectionne...','es'=>'Selecciona...')); ?></span></div>
                                <div class="toa-crew-customselect-options"></div>
                            </div>
                            <div class="toa-crew-error-msg"></div>
                        </div>
                        <!-- Container 3: Free text -->
                        <div class="city-free" style="display:none;">
                            <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Città','en'=>'City','fr'=>'Ville','es'=>'Ciudad')); ?> <span class="req">*</span></label>
                            <input type="text" name="dom_city_name" class="toa-crew-input" placeholder="Es. New York, Tokyo, ...">
                            <input type="hidden" name="dom_city_code">
                            <div class="toa-crew-error-msg"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toa-crew-actions">
                <button type="button" class="toa-crew-btn toa-crew-btn-ghost" data-go="1">← <?php echo _ht_crew(array('it'=>'Indietro','en'=>'Back','fr'=>'Retour','es'=>'Atrás')); ?></button>
                <button type="button" class="toa-crew-btn toa-crew-btn-primary" data-go="3"><?php echo _ht_crew(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> →</button>
            </div>
        </div>

        <!-- ═════ STEP 3 — Cosa fai ═════ -->
        <div class="toa-crew-step" data-step="3">
            <h3><?php echo _ht_crew(array('it'=>'Cosa fai','en'=>'What you do','fr'=>'Ce que tu fais','es'=>'Qué haces')); ?></h3>
            <p class="toa-crew-step-help"><?php echo _ht_crew(array('it'=>'Scegli categorie e dati professionali. Le categorie con badge "18+" richiedono la maggiore età.','en'=>'Choose categories and pro info.','fr'=>'Catégories et infos pro.','es'=>'Categorías e info profesional.')); ?></p>

            <div class="toa-crew-field">
                <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Categorie','en'=>'Categories','fr'=>'Catégories','es'=>'Categorías')); ?> <span class="req">*</span></label>
                <div class="toa-crew-categories" id="toaCrewCategories">
                    <?php foreach ($CREW_CATEGORIES as $cat): ?>
                        <label class="toa-crew-category-chip" data-min-age="<?php echo (int)$cat['min_age']; ?>">
                            <input type="checkbox" name="categorie[]" value="<?php echo esc_attr($cat['code']); ?>">
                            <?php echo esc_html(_ht_crew_raw($cat['label'])); ?>
                            <?php if ($cat['min_age'] >= 18): ?><span class="age-badge">18+</span><?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="toa-crew-error-msg"></div>
            </div>

            <div class="toa-crew-field-row">
                <div class="toa-crew-field">
                    <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Anni di esperienza','en'=>'Years of experience','fr'=>'Années d\'expérience','es'=>'Años de experiencia')); ?></label>
                    <input type="number" name="anni_esperienza" class="toa-crew-input" min="0" max="60" placeholder="0">
                </div>
                <div class="toa-crew-field">
                    <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Livello','en'=>'Level','fr'=>'Niveau','es'=>'Nivel')); ?></label>
                    <div class="toa-crew-customselect">
                        <input type="hidden" name="livello" value="amatoriale">
                        <div class="toa-crew-customselect-trigger">
                            <span class="toa-crew-customselect-label"><?php echo _ht_crew($LIVELLI[0]['label']); ?></span>
                        </div>
                        <div class="toa-crew-customselect-options">
                            <?php foreach ($LIVELLI as $i => $l): ?>
                                <div class="toa-crew-customselect-option <?php echo $i===0?'selected':''; ?>" data-value="<?php echo esc_attr($l['code']); ?>"><?php echo _ht_crew($l['label']); ?></div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toa-crew-field-row">
                <div class="toa-crew-field">
                    <label class="toa-crew-label" id="toaCrewPivaLabel"><?php echo _ht_crew(array('it'=>'Hai Partita IVA?','en'=>'Have a VAT number?','fr'=>'TVA ?','es'=>'¿IVA?')); ?></label>
                    <div class="toa-crew-toggle-group">
                        <input type="hidden" name="ha_piva" value="0">
                        <div class="toa-crew-toggle active" data-value="0"><?php echo _ht_crew(array('it'=>'No','en'=>'No','fr'=>'Non','es'=>'No')); ?></div>
                        <div class="toa-crew-toggle" data-value="1"><?php echo _ht_crew(array('it'=>'Sì','en'=>'Yes','fr'=>'Oui','es'=>'Sí')); ?></div>
                    </div>
                    <div class="toa-crew-conditional">
                        <input type="text" name="partita_iva" class="toa-crew-input" placeholder="Partita IVA" maxlength="20">
                    </div>
                </div>
                <div class="toa-crew-field">
                    <label class="toa-crew-label"><?php echo _ht_crew(array('it'=>'Sei automunito?','en'=>'Have a car?','fr'=>'Voiture ?','es'=>'¿Coche?')); ?></label>
                    <div class="toa-crew-toggle-group">
                        <input type="hidden" name="automunito" value="0">
                        <div class="toa-crew-toggle active" data-value="0"><?php echo _ht_crew(array('it'=>'No','en'=>'No','fr'=>'Non','es'=>'No')); ?></div>
                        <div class="toa-crew-toggle" data-value="1"><?php echo _ht_crew(array('it'=>'Sì','en'=>'Yes','fr'=>'Oui','es'=>'Sí')); ?></div>
                    </div>
                </div>
            </div>

            <div class="toa-crew-field">
                <label class="toa-crew-label">Portfolio (link sito web, Behance, Drive)</label>
                <input type="url" name="portfolio_url" class="toa-crew-input" placeholder="https://">
            </div>

            <div class="toa-crew-field-row">
                <div class="toa-crew-field">
                    <label class="toa-crew-label">Instagram</label>
                    <input type="text" name="instagram" class="toa-crew-input" placeholder="@username">
                </div>
                <div class="toa-crew-field">
                    <label class="toa-crew-label">TikTok</label>
                    <input type="text" name="tiktok" class="toa-crew-input" placeholder="@username">
                </div>
            </div>

            <div class="toa-crew-field-row">
                <div class="toa-crew-field">
                    <label class="toa-crew-label">YouTube</label>
                    <input type="url" name="youtube" class="toa-crew-input" placeholder="https://youtube.com/@...">
                </div>
                <div class="toa-crew-field"></div>
            </div>

            <div class="toa-crew-actions">
                <button type="button" class="toa-crew-btn toa-crew-btn-ghost" data-go="2">← <?php echo _ht_crew(array('it'=>'Indietro','en'=>'Back','fr'=>'Retour','es'=>'Atrás')); ?></button>
                <button type="button" class="toa-crew-btn toa-crew-btn-primary" data-go="4"><?php echo _ht_crew(array('it'=>'Continua','en'=>'Continue','fr'=>'Continuer','es'=>'Continuar')); ?> →</button>
            </div>
        </div>

        <!-- ═════ STEP 4 — Foto profilo + Portfolio ═════ -->
        <div class="toa-crew-step" data-step="4">
            <h3><?php echo _ht_crew(array('it'=>'Foto e portfolio','en'=>'Photo & portfolio','fr'=>'Photo et portfolio','es'=>'Foto y portfolio')); ?></h3>

            <!-- Foto profilo -->
            <div class="toa-crew-upload-section">
                <h5>👤 <?php echo _ht_crew(array('it'=>'Foto profilo','en'=>'Profile photo','fr'=>'Photo de profil','es'=>'Foto de perfil')); ?> <span class="req">*</span></h5>
                <p class="toa-crew-step-help"><?php echo _ht_crew(array('it'=>'Una foto chiara del tuo viso. Sarà la tua immagine pubblica.','en'=>'A clear photo of your face.','fr'=>'Photo claire de ton visage.','es'=>'Foto clara de tu cara.')); ?></p>
                <div class="toa-crew-dropzone toa-crew-dropzone-small" id="toaCrewProfileDrop">
                    <div class="toa-crew-dropzone-icon">👤</div>
                    <div class="toa-crew-dropzone-text"><strong><?php echo _ht_crew(array('it'=>'Clicca','en'=>'Click','fr'=>'Clique','es'=>'Clic')); ?></strong> <?php echo _ht_crew(array('it'=>'per caricare','en'=>'to upload','fr'=>'pour charger','es'=>'para subir')); ?></div>
                    <div class="toa-crew-dropzone-hint">JPG, PNG • <?php /* TASK hardening-upload-crew 2026-06-04 marco */ echo _ht_crew(array('it'=>'Carica le tue foto: le ottimizziamo noi automaticamente','en'=>'Upload your photos: we optimize them automatically','fr'=>'Charge tes photos : on les optimise automatiquement','es'=>'Sube tus fotos: las optimizamos automáticamente')); ?></div>
                    <input type="file" id="toaCrewProfileInput" accept="image/*" style="display:none;">
                </div>
                <div class="toa-crew-profile-thumb" id="toaCrewProfileThumb"></div>
            </div>

            <!-- Disclaimer -->
            <div class="toa-crew-disclaimer">
                <h4>⚠️ <?php echo _ht_crew(array('it'=>'IMPORTANTE — Cosa NON puoi caricare','en'=>'IMPORTANT — What NOT to upload','fr'=>'IMPORTANT','es'=>'IMPORTANTE')); ?></h4>
                <p><?php echo _ht_crew(array(
                    'it'=>'Il tuo portfolio sarà visibile ai clienti TOAgency in modalità anonima. Per proteggere la tua identità, le foto/video NON devono contenere:',
                    'en'=>'Your portfolio is shown anonymously. Files must NOT contain:',
                    'fr'=>'Portfolio visible anonymement. Les fichiers ne doivent PAS contenir :',
                    'es'=>'Portfolio anónimo. Los archivos NO deben contener:',
                )); ?></p>
                <ul class="toa-crew-no-list">
                    <li><?php echo _ht_crew(array('it'=>'Watermark, firme, loghi (es. "© Luca Rossi")','en'=>'Watermarks, signatures','fr'=>'Filigranes, signatures','es'=>'Marcas, firmas')); ?></li>
                    <li><?php echo _ht_crew(array('it'=>'Email, telefoni, link a social','en'=>'Emails, phones, social links','fr'=>'Emails, téléphones','es'=>'Emails, teléfonos')); ?></li>
                    <li><?php echo _ht_crew(array('it'=>'Nome o cognome visibili','en'=>'Visible name','fr'=>'Nom visible','es'=>'Nombre visible')); ?></li>
                    <li><?php echo _ht_crew(array('it'=>'URL o handle Instagram/TikTok','en'=>'URLs or handles','fr'=>'URL pseudos','es'=>'URL usuarios')); ?></li>
                    <li><?php echo _ht_crew(array('it'=>'Loghi di altri brand/agenzie','en'=>'Other brand logos','fr'=>'Logos d\'autres marques','es'=>'Logos de otras marcas')); ?></li>
                </ul>
                <p style="color:rgba(255,255,255,0.85);"><?php echo _ht_crew(array(
                    'it'=>'I clienti potranno contattarti SOLO tramite TOAgency.',
                    'en'=>'Clients reach you ONLY via TOAgency.',
                    'fr'=>'Contact UNIQUEMENT via TOAgency.',
                    'es'=>'Contacto SOLO vía TOAgency.',
                )); ?></p>
            </div>

            <!-- Foto portfolio -->
            <div class="toa-crew-upload-section">
                <h5>📷 <?php echo _ht_crew(array('it'=>'Foto del tuo lavoro','en'=>'Photos of your work','fr'=>'Photos de ton travail','es'=>'Fotos de tu trabajo')); ?></h5>
                <div class="toa-crew-upload-counter" id="toaCrewPhotosCounter"><strong>0</strong> / 15</div>
                <div class="toa-crew-dropzone" id="toaCrewPhotosDrop">
                    <div class="toa-crew-dropzone-icon">⬆️</div>
                    <div class="toa-crew-dropzone-text"><strong><?php echo _ht_crew(array('it'=>'Clicca','en'=>'Click','fr'=>'Clique','es'=>'Clic')); ?></strong> <?php echo _ht_crew(array('it'=>'o trascina qui le foto','en'=>'or drag photos','fr'=>'ou glisse','es'=>'o arrastra')); ?></div>
                    <div class="toa-crew-dropzone-hint">JPG, PNG • <?php /* TASK hardening-upload-crew 2026-06-04 marco */ echo _ht_crew(array('it'=>'Carica le tue foto: le ottimizziamo noi automaticamente','en'=>'Upload your photos: we optimize them automatically','fr'=>'Charge tes photos : on les optimise automatiquement','es'=>'Sube tus fotos: las optimizamos automáticamente')); ?></div>
                    <input type="file" id="toaCrewPhotosInput" accept="image/*" multiple style="display:none;">
                </div>
                <div class="toa-crew-thumbs" id="toaCrewPhotosThumbs"></div>
            </div>

            <!-- Video portfolio -->
            <div class="toa-crew-upload-section">
                <h5>🎬 <?php echo _ht_crew(array('it'=>'Video del tuo lavoro','en'=>'Videos','fr'=>'Vidéos','es'=>'Vídeos')); ?></h5>
                <div class="toa-crew-upload-counter" id="toaCrewVideosCounter"><strong>0</strong> / 5</div>
                <div class="toa-crew-dropzone" id="toaCrewVideosDrop">
                    <div class="toa-crew-dropzone-icon">⬆️</div>
                    <div class="toa-crew-dropzone-text"><strong><?php echo _ht_crew(array('it'=>'Clicca','en'=>'Click','fr'=>'Clique','es'=>'Clic')); ?></strong> <?php echo _ht_crew(array('it'=>'o trascina qui i video','en'=>'or drag videos','fr'=>'ou glisse','es'=>'o arrastra')); ?></div>
                    <div class="toa-crew-dropzone-hint">MP4, MOV • <?php echo _ht_crew(array('it'=>'max 50MB per file','en'=>'max 50MB','fr'=>'max 50MB','es'=>'max 50MB')); ?></div>
                    <input type="file" id="toaCrewVideosInput" accept="video/*" multiple style="display:none;">
                </div>
                <div class="toa-crew-thumbs" id="toaCrewVideosThumbs"></div>
            </div>

            <!-- Conferma upload -->
            <div class="toa-crew-field" style="margin-top: 26px;">
                <label class="toa-crew-checkbox">
                    <input type="checkbox" name="disclaimer_consent" value="1" required>
                    <span><?php echo _ht_crew(array(
                        'it'=>'Confermo che le mie foto/video non contengono firme, watermark, contatti o riferimenti riconducibili a me.',
                        'en'=>'I confirm files don\'t contain signatures or contacts.',
                        'fr'=>'Je confirme l\'absence de signatures.',
                        'es'=>'Confirmo que no hay firmas ni contactos.',
                    )); ?></span>
                </label>
                <div class="toa-crew-error-msg"></div>
            </div>

            <div class="toa-crew-field">
                <label class="toa-crew-checkbox">
                    <input type="checkbox" name="gdpr_consent" value="1" required>
                    <span><?php echo _ht_crew(array('it'=>'Ho letto e accetto la privacy policy.','en'=>'I accept the privacy policy.','fr'=>'J\'accepte la politique.','es'=>'Acepto la política.')); ?> <a href="/privacy-policy/" target="_blank">Privacy</a></span>
                </label>
                <div class="toa-crew-error-msg"></div>
            </div>

            <!-- ═════════ CONSENSO PUBBLICAZIONE IMMAGINI (legge 633/41 + GDPR) ═════════ -->
            <div class="toa-crew-field" style="background:rgba(200,255,0,0.04);border:1px solid rgba(200,255,0,0.2);border-radius:14px;padding:18px;margin-top:20px;">
                <label class="toa-crew-checkbox" style="align-items:flex-start;">
                    <input type="checkbox" name="pubblicazione_immagini_consent" value="1" id="toaCrewPubblicazione" style="margin-top:6px;">
                    <span style="font-size:0.88rem;line-height:1.55;">
                        <strong style="color:#c8ff00;display:block;margin-bottom:6px;">📸 <?php echo _ht_crew(array(
                            'it'=>'Consenso alla pubblicazione delle immagini',
                            'en'=>'Consent to image publication',
                            'fr'=>'Consentement à la publication des images',
                            'es'=>'Consentimiento para la publicación de imágenes',
                        )); ?></strong>
                        <?php echo _ht_crew(array(
                            'it'=>'Acconsento alla pubblicazione delle mie foto e video da parte di TOAgency sui propri canali ufficiali (sito web toagency.it, profili social, presentazioni a clienti aziendali, materiali promozionali) per finalità di promozione professionale e visibilità del mio profilo crew. Posso revocare questo consenso in qualsiasi momento scrivendo a info@toagency.it; la rimozione delle immagini dai canali gestiti da TOAgency avverrà entro 30 giorni dalla richiesta. Riferimenti: Legge 633/1941 art. 96-97, GDPR Reg. UE 2016/679 art. 6-7, art. 10 c.c.',
                            'en'=>'I consent to TOAgency publishing my photos and videos on its official channels (website, social profiles, presentations to corporate clients, promotional materials) for the purposes of professional promotion and visibility of my crew profile. I may revoke this consent at any time by writing to info@toagency.it; image removal from TOAgency-managed channels will occur within 30 days of the request. References: Italian Law 633/1941 art. 96-97, GDPR Reg. EU 2016/679 art. 6-7.',
                            'fr'=>'J\'autorise TOAgency à publier mes photos et vidéos sur ses canaux officiels pour la promotion professionnelle. Je peux révoquer ce consentement à tout moment en écrivant à info@toagency.it.',
                            'es'=>'Autorizo a TOAgency a publicar mis fotos y videos en sus canales oficiales para promoción profesional. Puedo revocar este consentimiento en cualquier momento escribiendo a info@toagency.it.',
                        )); ?>
                        <br><br>
                        <em style="color:rgba(255,255,255,0.55);font-size:0.78rem;">
                            <?php echo _ht_crew(array(
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

            <div style="position:absolute; left:-9999px; opacity:0;" aria-hidden="true">
                <label>Non compilare<input type="text" name="honeypot_url" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="toa-crew-actions">
                <button type="button" class="toa-crew-btn toa-crew-btn-ghost" data-go="3">← <?php echo _ht_crew(array('it'=>'Indietro','en'=>'Back','fr'=>'Retour','es'=>'Atrás')); ?></button>
                <button type="submit" class="toa-crew-btn toa-crew-btn-primary" id="toaCrewSubmit">
                    <?php echo _ht_crew(array('it'=>'Invia candidatura','en'=>'Submit','fr'=>'Envoyer','es'=>'Enviar')); ?>
                </button>
            </div>
        </div>

    </form>
</section>

<!-- Modal successo -->
<div class="toa-crew-success" id="toaCrewSuccess" role="dialog" aria-modal="true">
    <div class="toa-crew-success-card">
        <div class="toa-crew-success-icon">✓</div>
        <h2><?php echo _ht_crew(array('it'=>'Candidatura inviata!','en'=>'Submitted!','fr'=>'Envoyé !','es'=>'¡Enviada!')); ?></h2>
        <p><?php echo _ht_crew(array(
            'it'=>'Grazie per esserti registrato come crew.',
            'en'=>'Thank you for registering as crew.',
            'fr'=>'Merci pour ton inscription en tant que crew.',
            'es'=>'Gracias por registrarte como crew.',
        )); ?></p>
        <div class="toa-crew-success-info">
            ✨ <?php echo _ht_crew(array(
                'it'=>'Il nostro staff valuterà il tuo profilo. Una volta approvato, sarai contattato per opportunità di lavoro.',
                'en'=>'Our staff will review your profile. Once approved, you will be contacted for job opportunities.',
                'fr'=>'Notre équipe examinera ton profil et te contactera pour des opportunités.',
                'es'=>'Nuestro equipo revisará tu perfil y te contactará para oportunidades.',
            )); ?>
        </div>
        <button type="button" class="toa-crew-success-close" id="toaCrewSuccessClose"><?php echo _ht_crew(array('it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar')); ?></button>
    </div>
</div>

<script src="<?php echo esc_url($theme_uri . '/assets/crew-form.js'); ?>?v=3.0-20260629b" defer></script><!-- TASK hardening-upload-crew 2026-06-04: bump v per forzare reload JS su prod/CDN -->

<!-- ══════════════════════════════════════════════
     PREFILL — pre-compila campi se l'utente arriva da Student Program
     Sorgenti supportate: ?prefill=BASE64 (dalla pagina /student-program/grazie/)
                          + sessionStorage 'toa_crew_prefill' (fallback)
═══════════════════════════════════════════════ -->
<script>
(function(){
  function getPrefillData(){
    // 1. Da URL parameter ?prefill=BASE64
    try {
      var url = new URL(window.location.href);
      var token = url.searchParams.get('prefill');
      if (token) {
        var b64 = token.replace(/-/g,'+').replace(/_/g,'/');
        // Padding
        while (b64.length % 4) b64 += '=';
        var json = decodeURIComponent(escape(atob(b64)));
        return JSON.parse(json);
      }
    } catch(e) { /* ignora token malformato */ }
    // 2. Fallback: sessionStorage
    try {
      var raw = sessionStorage.getItem('toa_crew_prefill');
      if (raw) return JSON.parse(raw);
    } catch(e){}
    return null;
  }

  function setVal(name, value){
    if (!value) return;
    var el = document.querySelector('[name="' + name + '"]');
    if (!el) return;
    el.value = value;
    // Trigger change/input per eventuali listener del form
    el.dispatchEvent(new Event('input', { bubbles: true }));
    el.dispatchEvent(new Event('change', { bubbles: true }));
  }

  function applyPrefill(){
    var data = getPrefillData();
    if (!data) return;

    // Mappa Student → Crew
    setVal('nome',          data.first_name);
    setVal('cognome',       data.last_name);
    setVal('email',         data.email);
    setVal('telefono',      data.phone);
    setVal('data_nascita',  data.dob);
    setVal('instagram',     data.instagram);
    setVal('tiktok',        data.tiktok);
    setVal('portfolio_url', data.portfolio);

    // Pulisci sessionStorage dopo l'uso (no leak tra sessioni)
    try { sessionStorage.removeItem('toa_crew_prefill'); } catch(e){}

    // Pulisci URL (rimuovi ?prefill=... dalla barra)
    try {
      var clean = window.location.pathname + window.location.hash;
      window.history.replaceState({}, document.title, clean);
    } catch(e){}
  }

  // Aspetta che il form sia presente (lo script crew-form.js è defer)
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(){ setTimeout(applyPrefill, 50); });
  } else {
    setTimeout(applyPrefill, 50);
  }
})();
</script>

<?php toa_component('footer'); ?>
