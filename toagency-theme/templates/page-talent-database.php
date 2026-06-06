<?php
/**
 * Template Name: Talent Database
 * v1.0 — 8 Maggio 2026
 *
 * Path: /wp-content/themes/toagency-theme/templates/page-talent-database.php
 *
 * Pagina pubblica /talent-database/ — esplorazione dei talent approvati
 * dallo staff (visibile_pubblico=1, eliminato=0, tipo_talent='immagine',
 * foto_locale presente). Sidebar filtri + grid card + modal scheda con
 * galleria + carrello fluttuante + modal form richiesta info.
 *
 * Multilingua IT/EN/FR/ES (array inline + helper, come page-registrati-talent.php).
 * CSS:    /wp-content/themes/toagency-theme/assets/talent-database.css (STEP 2)
 * JS:     /wp-content/themes/toagency-theme/assets/talent-database.js  (STEP 3)
 * API:    /actions/api-talent-database.php       (STEP 4)
 * Submit: /actions/talent-database-request.php   (STEP 5)
 * Foto:   /actions/foto-talent-public.php        (proxy pubblico, STEP 6)
 */

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, array('it','en','fr','es'), true)) $__l = 'it';

$_t = function ($a) use ($__l) {
    return isset($a[$__l]) ? $a[$__l] : $a['it'];
};

$theme_uri = get_stylesheet_directory_uri();

// ─── String table (IT/EN/FR/ES) ──────────────────────────────────────
$T = array(
    'hero_eyebrow'   => array('it'=>'TOAGENCY/TALENT','en'=>'TOAGENCY/TALENT','fr'=>'TOAGENCY/TALENT','es'=>'TOAGENCY/TALENT'),
    'hero_title'     => array('it'=>'Talent.','en'=>'Talent.','fr'=>'Talent.','es'=>'Talent.'),
    'hero_subtitle'  => array(
        'it'=>'Esplora i talent TOAgency. Filtra per età, look, fisicità e area geografica, poi crea la tua selezione.',
        'en'=>'Explore TOAgency talents. Filter by age, look, physique and location, then build your selection.',
        'fr'=>'Explore les talents TOAgency. Filtre par âge, look, physique et zone, puis crée ta sélection.',
        'es'=>'Explora los talents TOAgency. Filtra por edad, look, físico y zona, y crea tu selección.',
    ),

    'filters_open'   => array('it'=>'Filtri','en'=>'Filters','fr'=>'Filtres','es'=>'Filtros'),
    'filters_close'  => array('it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar'),

    'filter_search'  => array('it'=>'Cerca per nome','en'=>'Search by name','fr'=>'Recherche','es'=>'Buscar'),
    'filter_gender'  => array('it'=>'Genere','en'=>'Gender','fr'=>'Genre','es'=>'Género'),
    'filter_any'     => array('it'=>'Tutti','en'=>'All','fr'=>'Tous','es'=>'Todos'),
    'filter_f'       => array('it'=>'F','en'=>'F','fr'=>'F','es'=>'F'),
    'filter_m'       => array('it'=>'M','en'=>'M','fr'=>'M','es'=>'M'),
    'filter_other'   => array('it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro'),
    'filter_country' => array('it'=>'Paese','en'=>'Country','fr'=>'Pays','es'=>'País'),
    'filter_province'=> array('it'=>'Provincia / Regione','en'=>'Province / Region','fr'=>'Région','es'=>'Provincia / Región'),
    'filter_ethnicity'=>array('it'=>'Etnia','en'=>'Ethnicity','fr'=>'Origine','es'=>'Etnia'),
    'filter_size'    => array('it'=>'Taglia','en'=>'Size','fr'=>'Taille','es'=>'Talla'),
    'filter_hair'    => array('it'=>'Capelli','en'=>'Hair','fr'=>'Cheveux','es'=>'Cabello'),
    'filter_eyes'    => array('it'=>'Occhi','en'=>'Eyes','fr'=>'Yeux','es'=>'Ojos'),
    'filter_age'     => array('it'=>'Età','en'=>'Age','fr'=>'Âge','es'=>'Edad'),
    'filter_height'  => array('it'=>'Altezza (cm)','en'=>'Height (cm)','fr'=>'Taille (cm)','es'=>'Altura (cm)'),
    'filter_shoes'   => array('it'=>'Numero scarpe','en'=>'Shoe size','fr'=>'Pointure','es'=>'Calzado'),
    'filter_min'     => array('it'=>'Da','en'=>'From','fr'=>'De','es'=>'Desde'),
    'filter_max'     => array('it'=>'A','en'=>'To','fr'=>'À','es'=>'Hasta'),
    'filter_select_any'=> array('it'=>'Tutte','en'=>'Any','fr'=>'Toutes','es'=>'Todas'),
    'filter_apply'   => array('it'=>'Applica','en'=>'Apply','fr'=>'Appliquer','es'=>'Aplicar'),
    'filter_reset'   => array('it'=>'Reset','en'=>'Reset','fr'=>'Reset','es'=>'Reset'),

    'results_loading'=> array('it'=>'Carico talent…','en'=>'Loading talents…','fr'=>'Chargement…','es'=>'Cargando…'),
    'results_count_s'=> array('it'=>'talent trovato','en'=>'talent found','fr'=>'talent trouvé','es'=>'talent encontrado'),
    'results_count_p'=> array('it'=>'talent trovati','en'=>'talents found','fr'=>'talents trouvés','es'=>'talents encontrados'),
    'results_empty'  => array('it'=>'Nessun talent corrisponde ai filtri.','en'=>'No talent matches your filters.','fr'=>'Aucun talent.','es'=>'Ningún talent.'),
    'results_more'   => array('it'=>'Carica altri','en'=>'Load more','fr'=>'Charger plus','es'=>'Cargar más'),

    'btn_add'        => array('it'=>'+ Aggiungi alla selezione','en'=>'+ Add to selection','fr'=>'+ Ajouter','es'=>'+ Añadir'),
    'btn_remove'     => array('it'=>'✓ Selezionato','en'=>'✓ Selected','fr'=>'✓ Sélectionné','es'=>'✓ Seleccionado'),

    'modal_close'    => array('it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar'),
    'modal_prev'     => array('it'=>'Precedente','en'=>'Previous','fr'=>'Précédent','es'=>'Anterior'),
    'modal_next'     => array('it'=>'Successivo','en'=>'Next','fr'=>'Suivant','es'=>'Siguiente'),
    'modal_anonymous'=> array(
        'it'=>'Per privacy mostriamo solo il nome. Contatta TOAgency per dettagli aggiuntivi.',
        'en'=>'For privacy reasons we only show the first name. Contact TOAgency for further details.',
        'fr'=>'Confidentialité : seul le prénom est affiché. Contacte TOAgency pour plus de détails.',
        'es'=>'Privacidad: solo mostramos el nombre. Contacta a TOAgency para más detalles.',
    ),

    'cart_singular'  => array('it'=>'talent selezionato','en'=>'talent selected','fr'=>'talent sélectionné','es'=>'talent seleccionado'),
    'cart_plural'    => array('it'=>'talent selezionati','en'=>'talents selected','fr'=>'talents sélectionnés','es'=>'talents seleccionados'),
    'cart_request'   => array('it'=>'Richiedi info','en'=>'Request info','fr'=>'Demander info','es'=>'Solicitar info'),
    'cart_clear'     => array('it'=>'Svuota','en'=>'Clear','fr'=>'Vider','es'=>'Vaciar'),

    'form_title'     => array('it'=>'Richiedi info sui talent selezionati','en'=>'Request info on selected talents','fr'=>'Demander des infos','es'=>'Solicitar info'),
    'form_intro'     => array(
        'it'=>'Compila il form: ti ricontatteremo entro 24h con disponibilità e tariffe.',
        'en'=>'Fill the form: we\'ll reply within 24h with availability and rates.',
        'fr'=>'Remplis le formulaire : réponse sous 24h.',
        'es'=>'Rellena el formulario: respondemos en 24h.',
    ),
    'form_selection' => array('it'=>'La tua selezione','en'=>'Your selection','fr'=>'Ta sélection','es'=>'Tu selección'),
    'form_name'      => array('it'=>'Nome e cognome','en'=>'Full name','fr'=>'Nom complet','es'=>'Nombre completo'),
    'form_email'     => array('it'=>'Email','en'=>'Email','fr'=>'Email','es'=>'Email'),
    'form_phone'     => array('it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono'),
    'form_company'   => array('it'=>'Azienda','en'=>'Company','fr'=>'Société','es'=>'Empresa'),
    'form_project'   => array('it'=>'Descrizione progetto','en'=>'Project description','fr'=>'Description du projet','es'=>'Descripción del proyecto'),
    'form_date'      => array('it'=>'Data ipotetica','en'=>'Tentative date','fr'=>'Date envisagée','es'=>'Fecha estimada'),
    'form_gdpr'      => array(
        'it'=>'Accetto la <a href="/privacy-policy/" target="_blank" rel="noopener">privacy policy</a>.',
        'en'=>'I accept the <a href="/privacy-policy/" target="_blank" rel="noopener">privacy policy</a>.',
        'fr'=>'J\'accepte la <a href="/privacy-policy/" target="_blank" rel="noopener">politique de confidentialité</a>.',
        'es'=>'Acepto la <a href="/privacy-policy/" target="_blank" rel="noopener">política de privacidad</a>.',
    ),
    'form_submit'    => array('it'=>'Invia richiesta','en'=>'Send request','fr'=>'Envoyer','es'=>'Enviar'),
    'form_back'      => array('it'=>'← Indietro','en'=>'← Back','fr'=>'← Retour','es'=>'← Atrás'),
    'form_success'   => array('it'=>'Richiesta inviata!','en'=>'Request sent!','fr'=>'Envoyée !','es'=>'¡Enviada!'),
    'form_success_msg'=> array(
        'it'=>'Ti ricontatteremo entro 24 ore con disponibilità e tariffe dei talent selezionati.',
        'en'=>'We\'ll get back to you within 24h with availability and rates.',
        'fr'=>'Nous reviendrons vers toi sous 24h.',
        'es'=>'Responderemos en 24h con disponibilidad y tarifas.',
    ),
    'form_error'     => array(
        'it'=>'Errore nell\'invio. Riprova oppure scrivi a info@toagency.it',
        'en'=>'Send error. Try again or write to info@toagency.it',
        'fr'=>'Erreur. Réessaye ou écris à info@toagency.it',
        'es'=>'Error. Inténtalo o escribe a info@toagency.it',
    ),
);
?>
<!-- TOA-TALENT-DATABASE-V1 — PATCH 2026-05-22 marco hub sezioni categoria -->
<link rel="stylesheet" href="<?php echo esc_url($theme_uri . '/assets/talent-database-v40.css'); ?>">
<script>
window.toaThemeUri      = "<?php echo esc_js($theme_uri); ?>";
window.toaTdbLang       = "<?php echo esc_js($__l); ?>";
window.toaTdbApiUrl     = "/actions/api-talent-database.php";
window.toaTdbRequestUrl = "/actions/talent-database-request.php";
window.toaTdbFotoUrl    = "/actions/foto-talent-public.php";
</script>

<?php /* 2026-06-02 marco — REDESIGN: rimosso l'intero <style> della sezione hub (markup hub eliminato). Le chip usano .toa-tdb-cat-chip in talent-database.css. */ ?>

<?php
// PATCH 2026-05-22 marco — sezioni hub con traduzioni
$hub_labels = array(
    'eyebrow'  => array('it'=>'TOAgency / Talent Database','en'=>'TOAgency / Talent Database','fr'=>'TOAgency / Talent Database','es'=>'TOAgency / Talent Database'),
    'title'    => array('it'=>'Esplora i Talent.','en'=>'Explore the Talent.','fr'=>'Explorez les Talents.','es'=>'Explora los Talents.'),
    'subtitle' => array(
        'it' => 'Scegli la categoria che ti interessa e trova il profilo giusto per il tuo progetto.',
        'en' => 'Choose the category you need and find the right profile for your project.',
        'fr' => 'Choisissez la catégorie qui vous intéresse et trouvez le profil idéal.',
        'es' => 'Elige la categoría que necesitas y encuentra el perfil ideal para tu proyecto.',
    ),
    'cta_all'  => array('it'=>'Esplora tutto il database','en'=>'Browse the full database','fr'=>'Explorer toute la base','es'=>'Explorar toda la base'),
    'explore'  => array('it'=>'Esplora','en'=>'Explore','fr'=>'Explorer','es'=>'Explorar'),
);
$tdb_url = get_permalink();
$hub_sections = array(
    array(
        'icon'   => '🎭',
        'ruolo'  => 'actor',
        'title'  => array('it'=>'Attori e comparse','en'=>'Actors & Cinema','fr'=>'Acteurs & Cinéma','es'=>'Actores & Cine'),
        'desc'   => array('it'=>'Attori, attrici e comparse per spot, film, serie TV e video.','en'=>'Actors, actresses and extras for commercials, films, series and videos.','fr'=>'Acteurs, actrices et figurants pour pubs, films, séries et vidéos.','es'=>'Actores, actrices y figurantes para spots, películas, series y vídeos.'),
    ),
    array(
        'icon'   => '👗',
        'ruolo'  => 'model',
        'title'  => array('it'=>'Modelli','en'=>'Models','fr'=>'Modèles','es'=>'Modelos'),
        'desc'   => array('it'=>'Modelli e modelle per sfilate, shooting fotografici, cataloghi e brand.','en'=>'Male and female models for fashion shows, photo shoots, catalogues and brands.','fr'=>'Mannequins pour défilés, shootings photo, catalogues et marques.','es'=>'Modelos para desfiles, shootings, catálogos y marcas.'),
    ),
    array(
        'icon'   => '✈️',
        'ruolo'  => 'hostess',
        'title'  => array('it'=>'Hostess e steward','en'=>'Hostess & Steward','fr'=>'Hôtesses & Stewards','es'=>'Azafatas & Steward'),
        'desc'   => array('it'=>'Hostess e steward per eventi, fiere, congressi e attività promozionali.','en'=>'Hostesses and stewards for events, trade fairs, congresses and promotions.','fr'=>'Hôtesses et stewards pour événements, salons et promotions.','es'=>'Azafatas y stewards para eventos, ferias, congresos y promociones.'),
    ),
    array(
        'icon'   => '👶',
        'ruolo'  => 'comparsa',
        'title'  => array('it'=>'Bambini / ragazzi','en'=>'Kids & Young','fr'=>'Enfants & Jeunes','es'=>'Niños & Jóvenes'),
        'desc'   => array('it'=>'Bambini, ragazzi e giovani adulti per spot, campagne e contenuti digitali.','en'=>'Children, teens and young adults for commercials, campaigns and digital content.','fr'=>'Enfants, ados et jeunes adultes pour pubs, campagnes et contenus.','es'=>'Niños, adolescentes y jóvenes para spots, campañas y contenidos.'),
    ),
    array(
        'icon'   => '📱',
        'ruolo'  => 'creator',
        'title'  => array('it'=>'Creator e influencer','en'=>'Creator & Influencer','fr'=>'Créateurs & Influenceurs','es'=>'Creadores & Influencers'),
        'desc'   => array('it'=>'Content creator e influencer per campagne social, UGC e brand collaboration.','en'=>'Content creators and influencers for social campaigns, UGC and brand collaborations.','fr'=>'Créateurs de contenu et influenceurs pour campagnes sociales et collaborations.','es'=>'Creadores e influencers para campañas sociales, UGC y colaboraciones.'),
    ),
    // 2026-05-24 marco — card "Crew & Tecnici" rimossa per campagna lancio talent.
    // Ripristino: vedi backup page-talent-database.php.bak_20260524 sul server.
);
?>

<!-- 2026-06-02 marco — REDESIGN: blocco hub categorie grande RIMOSSO (occupava mezza pagina).
     Header compatto + chip categoria sottili sopra la griglia (vedi sotto). $hub_sections riusato per le chip. -->
<?php // (hub_sections/hub_labels restano definiti sopra e vengono usati per le chip categoria) ?>

<script>
/* PATCH 2026-05-22 marco — fetch interceptor: inietta ruolo + cache-bust nel payload API search */
(function () {
    var _origFetch = window.fetch;
    window.fetch = function (url, opts) {
        if (typeof url === 'string' &&
            url.indexOf('api-talent-database') !== -1 &&
            url.indexOf('action=search') !== -1 &&
            opts && opts.body) {
            /* Cache-bust: aggiunge _v=timestamp per bypassare Dynamic Cache SiteGround */
            if (url.indexOf('_v=') === -1) {
                url = url + '&_v=' + Date.now();
            }
            try {
                var payload = JSON.parse(opts.body);
                var sel = document.getElementById('tdbFilterRuolo');
                if (sel && sel.value) {
                    payload.ruolo = sel.value;
                    opts = Object.assign({}, opts, { body: JSON.stringify(payload) });
                }
            } catch (e) {}
        }
        return _origFetch.call(this, url, opts);
    };
})();

/* PATCH 2026-05-22 marco — pre-applica filtro ruolo da URL (?ruolo=xxx) */
(function () {
    var params = new URLSearchParams(window.location.search);
    var ruolo  = params.get('ruolo');
    if (!ruolo) return;

    /* Esposto per talent-database.js se in futuro lo legge */
    window.toaTdbPreFilter = { ruolo: ruolo };

    /* Aspetta che DOM + talent-database.js (defer) siano pronti, poi:
       1. Seleziona il valore nel <select> Categoria
       2. Scrolla alla sezione database
       3. Submittà il form filtri per lanciare la ricerca */
    window.addEventListener('load', function () {
        setTimeout(function () {
            /* Imposta il select ruolo */
            var sel = document.getElementById('tdbFilterRuolo');
            if (sel) {
                sel.value = ruolo;
                /* Dispatch change per framework che ascoltano */
                sel.dispatchEvent(new Event('change', { bubbles: true }));
            }
            /* Scrolla alla sezione database */
            var target = document.getElementById('tdb-database');
            if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            /* Submit il form filtri */
            var form = document.getElementById('tdbFilters');
            if (form) form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
        }, 400); /* 400ms: abbastanza per il JS defer + primo render */
    });
})();
</script>

<section class="toa-tdb-wrap" id="tdb-database">

    <!-- ═════ Hero compatto (redesign 2026-06-02) ═════ -->
    <header class="toa-tdb-hero toa-tdb-hero-compact">
        <h1 class="toa-tdb-hero-title"><?php echo esc_html($_t($hub_labels['title'])); ?></h1>
        <p class="toa-tdb-hero-subtitle"><?php echo esc_html($_t($T['hero_subtitle'])); ?></p>
    </header>

    <!-- Toggle filtri (mobile) -->
    <button type="button" class="toa-tdb-filters-toggle" id="tdbFiltersToggle" aria-expanded="false" aria-controls="tdbSidebar">
        <span class="open-label">⚙ <?php echo esc_html($_t($T['filters_open'])); ?></span>
        <span class="close-label">✕ <?php echo esc_html($_t($T['filters_close'])); ?></span>
    </button>

    <div class="toa-tdb-layout">

        <!-- ═════ Sidebar filtri ═════ -->
        <aside class="toa-tdb-sidebar" id="tdbSidebar" aria-label="<?php echo esc_attr($_t($T['filters_open'])); ?>">
            <form id="tdbFilters" class="toa-tdb-filters" autocomplete="off" novalidate>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_search'])); ?></label>
                    <input type="search" name="q" class="toa-tdb-input" placeholder="—">
                </div>

                <!-- PATCH 2026-05-22 marco — filtro categoria/ruolo -->
                <div class="toa-tdb-field" id="tdbFieldRuolo">
                    <label class="toa-tdb-label">
                        <?php echo esc_html($_t(array('it'=>'Categoria','en'=>'Category','fr'=>'Catégorie','es'=>'Categoría'))); ?>
                    </label>
                    <select name="ruolo" class="toa-tdb-select" id="tdbFilterRuolo">
                        <option value=""><?php echo esc_html($_t($T['filter_select_any'])); ?></option>
                        <option value="actor"><?php echo esc_html($_t(array('it'=>'Attori e comparse','en'=>'Actors & Cinema','fr'=>'Acteurs & Cinéma','es'=>'Actores & Cine'))); ?></option>
                        <option value="model"><?php echo esc_html($_t(array('it'=>'Modelli','en'=>'Models','fr'=>'Modèles','es'=>'Modelos'))); ?></option>
                        <option value="hostess"><?php echo esc_html($_t(array('it'=>'Hostess e steward','en'=>'Hostess & Steward','fr'=>'Hôtesses & Stewards','es'=>'Azafatas & Steward'))); ?></option>
                        <option value="comparsa"><?php echo esc_html($_t(array('it'=>'Bambini / ragazzi','en'=>'Kids & Young','fr'=>'Enfants & Jeunes','es'=>'Niños & Jóvenes'))); ?></option>
                        <option value="creator"><?php echo esc_html($_t(array('it'=>'Creator e influencer','en'=>'Creator & Influencer','fr'=>'Créateurs & Influenceurs','es'=>'Creadores & Influencers'))); ?></option>
                    </select>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_gender'])); ?></label>
                    <div class="toa-tdb-toggle-group" data-name="sesso">
                        <input type="hidden" name="sesso" value="">
                        <button type="button" class="toa-tdb-toggle active" data-value=""><?php echo esc_html($_t($T['filter_any'])); ?></button>
                        <button type="button" class="toa-tdb-toggle" data-value="F"><?php echo esc_html($_t($T['filter_f'])); ?></button>
                        <button type="button" class="toa-tdb-toggle" data-value="M"><?php echo esc_html($_t($T['filter_m'])); ?></button>
                        <button type="button" class="toa-tdb-toggle" data-value="altro"><?php echo esc_html($_t($T['filter_other'])); ?></button>
                    </div>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_country'])); ?></label>
                    <select name="paese" class="toa-tdb-select" id="tdbFilterCountry">
                        <option value=""><?php echo esc_html($_t($T['filter_select_any'])); ?></option>
                    </select>
                </div>

                <div class="toa-tdb-field" id="tdbFilterProvinceWrap">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_province'])); ?></label>
                    <select name="provincia" class="toa-tdb-select" id="tdbFilterProvince">
                        <option value=""><?php echo esc_html($_t($T['filter_select_any'])); ?></option>
                    </select>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_ethnicity'])); ?></label>
                    <select name="etnia" class="toa-tdb-select" id="tdbFilterEthnicity">
                        <option value=""><?php echo esc_html($_t($T['filter_select_any'])); ?></option>
                    </select>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_size'])); ?></label>
                    <div class="toa-tdb-chip-group" data-name="taglia">
                        <?php foreach (array('XS','S','M','L','XL','XXL') as $size): ?>
                            <button type="button" class="toa-tdb-chip" data-value="<?php echo esc_attr($size); ?>"><?php echo esc_html($size); ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_hair'])); ?></label>
                    <select name="capelli" class="toa-tdb-select" id="tdbFilterHair">
                        <option value=""><?php echo esc_html($_t($T['filter_select_any'])); ?></option>
                    </select>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_eyes'])); ?></label>
                    <select name="occhi" class="toa-tdb-select" id="tdbFilterEyes">
                        <option value=""><?php echo esc_html($_t($T['filter_select_any'])); ?></option>
                    </select>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_age'])); ?></label>
                    <div class="toa-tdb-range">
                        <input type="number" name="eta_min" class="toa-tdb-input toa-tdb-input-sm" min="6" max="99" placeholder="<?php echo esc_attr($_t($T['filter_min'])); ?>">
                        <span class="toa-tdb-range-sep">—</span>
                        <input type="number" name="eta_max" class="toa-tdb-input toa-tdb-input-sm" min="6" max="99" placeholder="<?php echo esc_attr($_t($T['filter_max'])); ?>">
                    </div>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_height'])); ?></label>
                    <div class="toa-tdb-range">
                        <input type="number" name="altezza_min" class="toa-tdb-input toa-tdb-input-sm" min="80" max="230" placeholder="<?php echo esc_attr($_t($T['filter_min'])); ?>">
                        <span class="toa-tdb-range-sep">—</span>
                        <input type="number" name="altezza_max" class="toa-tdb-input toa-tdb-input-sm" min="80" max="230" placeholder="<?php echo esc_attr($_t($T['filter_max'])); ?>">
                    </div>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['filter_shoes'])); ?></label>
                    <div class="toa-tdb-range">
                        <input type="number" name="scarpe_min" class="toa-tdb-input toa-tdb-input-sm" min="20" max="55" placeholder="<?php echo esc_attr($_t($T['filter_min'])); ?>">
                        <span class="toa-tdb-range-sep">—</span>
                        <input type="number" name="scarpe_max" class="toa-tdb-input toa-tdb-input-sm" min="20" max="55" placeholder="<?php echo esc_attr($_t($T['filter_max'])); ?>">
                    </div>
                </div>

                <div class="toa-tdb-field">
                    <label class="toa-tdb-label">Rating</label>
                    <div class="toa-tdb-range">
                        <input type="number" name="valutazione_min" class="toa-tdb-input toa-tdb-input-sm" min="1" max="10" placeholder="<?php echo esc_attr($_t($T['filter_min'])); ?>">
                        <span class="toa-tdb-range-sep">—</span>
                        <input type="number" name="valutazione_max" class="toa-tdb-input toa-tdb-input-sm" min="1" max="10" placeholder="<?php echo esc_attr($_t($T['filter_max'])); ?>">
                    </div>
                </div>

                <div class="toa-tdb-filters-actions">
                    <button type="submit" class="toa-tdb-btn toa-tdb-btn-primary"><?php echo esc_html($_t($T['filter_apply'])); ?></button>
                    <button type="button" class="toa-tdb-btn toa-tdb-btn-ghost" id="tdbFiltersReset"><?php echo esc_html($_t($T['filter_reset'])); ?></button>
                </div>
            </form>
        </aside>

        <!-- ═════ Content ═════ -->
        <div class="toa-tdb-content">

            <!-- 2026-06-05 marco — REDESIGN menu: 2 macro compatti ad accordion, niente emoji.
                 Macro1 "Talent Immagine" (default APERTO) → chip Talent (tutti) + sottocategorie bloccate.
                 Macro2 "Backstage Crew" (default CHIUSO) → chip Crew (→/crew-database/) + bloccate.
                 Bloccate = lucchetto (CSS), cliccabili → modale esistente. Pulsante Filtri dentro il blocco sticky. -->
            <?php
            // 2026-06-05 marco — sottocategorie "in arrivo" (bloccate ma cliccabili). Solo display, nessun filtro backend.
            $g1_locked = array('Modello','Hostess/Steward','Attore','Comparsa','Influencer','UGC Creator');
            $g2_locked = array('Fotografo','Videomaker','Content Creator','Truccatore','Hairstylist','Stylist','DJ','Assistente di produzione','Art Director');
            $crew_url  = esc_url(home_url('/crew-database/'));
            $lbl_macro1 = $_t(array('it'=>'Talent Immagine','en'=>'Image Talent','fr'=>'Talent Image','es'=>'Talent Imagen'));
            $lbl_macro2 = $_t(array('it'=>'Backstage Crew','en'=>'Backstage Crew','fr'=>'Backstage Crew','es'=>'Backstage Crew'));
            $lbl_tal   = $_t(array('it'=>'Talent','en'=>'Talent','fr'=>'Talent','es'=>'Talent'));
            $lbl_crew  = $_t(array('it'=>'Crew','en'=>'Crew','fr'=>'Crew','es'=>'Crew'));
            $lbl_show  = $_t(array('it'=>'Mostra filtri','en'=>'Show filters','fr'=>'Afficher les filtres','es'=>'Mostrar filtros'));
            $lbl_hide  = $_t(array('it'=>'Nascondi filtri','en'=>'Hide filters','fr'=>'Masquer les filtres','es'=>'Ocultar filtros'));
            ?>
            <div class="toa-tdb-catgroups" id="tdbCatGroups">

                <div class="toa-tdb-menubar">
                    <div class="toa-tdb-macros" role="tablist" aria-label="Categorie">
                        <button type="button" class="toa-tdb-macro is-open" id="tdbMacro1" data-macro="1" aria-expanded="true" aria-controls="tdbCatChips"><?php echo esc_html($lbl_macro1); ?></button>
                        <a class="toa-tdb-macro toa-tdb-macro--link" id="tdbMacro2" href="<?php echo $crew_url; ?>"><?php echo esc_html($lbl_macro2); ?></a>
                    </div>
                    <!-- Toggle filtri (etichettato): da CHIUSO mostra "Mostra filtri", da APERTO "Nascondi filtri" -->
                    <button type="button" class="toa-tdb-sidebar-toggle" id="tdbSidebarToggle" aria-controls="tdbSidebar" aria-expanded="false"
                            data-label-show="<?php echo esc_attr($lbl_show); ?>" data-label-hide="<?php echo esc_attr($lbl_hide); ?>">
                        <span class="toa-tdb-sidebar-toggle-icon" aria-hidden="true">☰</span>
                        <span class="toa-tdb-sidebar-toggle-text"><?php echo esc_html($lbl_show); ?></span>
                    </button>
                </div>

                <div class="toa-tdb-submenus">
                    <!-- Sottomenu 1 — Talent Immagine (default aperto) -->
                    <div class="toa-tdb-cat-chips toa-tdb-submenu" id="tdbCatChips" role="tabpanel" aria-labelledby="tdbMacro1">
                        <button type="button" class="toa-tdb-cat-chip is-active" data-ruolo=""><?php echo esc_html($lbl_tal); ?></button>
                        <?php foreach ($g1_locked as $label) : ?>
                        <button type="button" class="toa-tdb-cat-chip toa-tdb-cat-chip--locked" data-locked="1" data-cat="<?php echo esc_attr($label); ?>">
                            <span><?php echo esc_html($label); ?></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                    <!-- Sottomenu 2 — rimosso 2026-06-05 marco: macro2 è ora link diretto a /crew-database/ -->
                </div>

            </div>

            <div class="toa-tdb-results-header">
                <span class="toa-tdb-results-count" id="tdbResultsCount"><?php echo esc_html($_t($T['results_loading'])); ?></span>
            </div>
            <div class="toa-tdb-grid" id="tdbGrid" aria-live="polite"></div>
            <div class="toa-tdb-grid-empty" id="tdbGridEmpty" hidden>
                <p><?php echo esc_html($_t($T['results_empty'])); ?></p>
            </div>
            <div class="toa-tdb-loadmore-wrap">
                <button type="button" class="toa-tdb-btn toa-tdb-btn-ghost" id="tdbLoadMore" hidden><?php echo esc_html($_t($T['results_more'])); ?></button>
            </div>
        </div>

    </div>

</section>

<!-- ═════ Modal scheda talent ═════ -->
<div class="toa-tdb-modal" id="tdbTalentModal" role="dialog" aria-modal="true" aria-labelledby="tdbModalName" hidden>
    <div class="toa-tdb-modal-overlay" data-tdb-close="1"></div>
    <div class="toa-tdb-detail-card">
        <button type="button" class="toa-tdb-modal-close" aria-label="<?php echo esc_attr($_t($T['modal_close'])); ?>" data-tdb-close="1">✕</button>
        <div class="toa-tdb-detail-grid">
            <div class="toa-tdb-detail-photo-col">
                <div class="toa-tdb-gallery">
                    <div class="toa-tdb-gallery-main">
                        <img id="tdbGalleryImage" class="toa-tdb-gallery-img" src="" alt="">
                        <button type="button" class="toa-tdb-gallery-arrow toa-tdb-gallery-prev" aria-label="<?php echo esc_attr($_t($T['modal_prev'])); ?>">‹</button>
                        <button type="button" class="toa-tdb-gallery-arrow toa-tdb-gallery-next" aria-label="<?php echo esc_attr($_t($T['modal_next'])); ?>">›</button>
                        <div class="toa-tdb-gallery-counter"><span class="toa-tdb-gallery-current">1</span> / <span class="toa-tdb-gallery-total">1</span></div>
                    </div>
                    <div class="toa-tdb-gallery-thumbs" id="tdbGalleryThumbs"></div>
                </div>
            </div>
            <div class="toa-tdb-detail-info">
                <h2 class="toa-tdb-detail-name" id="tdbModalName">—</h2>
                <div class="toa-tdb-detail-table" id="tdbModalFields"></div>
                <p class="toa-tdb-modal-anonymous"><?php echo esc_html($_t($T['modal_anonymous'])); ?></p>
                <button type="button" class="toa-tdb-btn toa-tdb-btn-primary toa-tdb-detail-add-btn" id="tdbModalAdd"><?php echo esc_html($_t($T['btn_add'])); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- ═════ Modal categoria bloccata ("database in arrivo") — 2026-06-05 marco ═════ -->
<div class="toa-tdb-modal" id="tdbLockedModal" role="dialog" aria-modal="true" aria-labelledby="tdbLockedTitle" hidden>
    <div class="toa-tdb-modal-overlay" data-tdb-close="1"></div>
    <div class="toa-tdb-modal-card toa-tdb-modal-card-narrow toa-tdb-locked-card">
        <button type="button" class="toa-tdb-modal-close" aria-label="<?php echo esc_attr($_t($T['modal_close'])); ?>" data-tdb-close="1">✕</button>
        <div class="toa-tdb-locked-body">
            <h2 id="tdbLockedTitle">🔒 <?php echo esc_html($_t(array('it'=>'Database in arrivo','en'=>'Database coming soon','fr'=>'Base de données à venir','es'=>'Base de datos en camino'))); ?></h2>
            <p><?php echo esc_html($_t(array(
                'it'=>'Stiamo completando questo archivio. TOAgency può inviarti subito la lista dei professionisti per la tua zona, data e budget.',
                'en'=>'We are completing this archive. TOAgency can send you right away the list of professionals for your area, date and budget.',
                'fr'=>'Nous complétons cet archive. TOAgency peut vous envoyer immédiatement la liste des professionnels pour votre zone, date et budget.',
                'es'=>'Estamos completando este archivo. TOAgency puede enviarte enseguida la lista de profesionales para tu zona, fecha y presupuesto.'
            ))); ?></p>
            <div class="toa-tdb-locked-actions">
                <a href="https://wa.me/393517899225" target="_blank" rel="noopener" class="toa-tdb-btn toa-tdb-btn-primary">💬 <?php echo esc_html($_t(array('it'=>'Chiedi su WhatsApp','en'=>'Ask on WhatsApp','fr'=>'Demander sur WhatsApp','es'=>'Pregunta por WhatsApp'))); ?></a>
                <a href="<?php echo esc_url(home_url('/form-b2b/')); ?>" class="toa-tdb-btn toa-tdb-btn-ghost"><?php echo esc_html($_t(array('it'=>'Richiedi preventivo','en'=>'Request a quote','fr'=>'Demander un devis','es'=>'Solicita presupuesto'))); ?></a>
            </div>
        </div>
    </div>
</div>

<!-- ═════ Carrello fluttuante ═════ -->
<div class="toa-tdb-cart" id="tdbCart" hidden>
    <div class="toa-tdb-cart-info">
        <strong id="tdbCartCount">0</strong>
        <span id="tdbCartLabel"><?php echo esc_html($_t($T['cart_plural'])); ?></span>
    </div>
    <button type="button" class="toa-tdb-cart-clear" id="tdbCartClear" aria-label="<?php echo esc_attr($_t($T['cart_clear'])); ?>"><?php echo esc_html($_t($T['cart_clear'])); ?></button>
    <button type="button" class="toa-tdb-btn toa-tdb-btn-primary toa-tdb-btn-cart" id="tdbCartRequest"><?php echo esc_html($_t($T['cart_request'])); ?></button>
</div>

<!-- ═════ Modal form richiesta info ═════ -->
<div class="toa-tdb-modal" id="tdbRequestModal" role="dialog" aria-modal="true" aria-labelledby="tdbRequestTitle" hidden>
    <div class="toa-tdb-modal-overlay" data-tdb-close="1"></div>
    <div class="toa-tdb-modal-card toa-tdb-modal-card-narrow">
        <button type="button" class="toa-tdb-modal-close" aria-label="<?php echo esc_attr($_t($T['modal_close'])); ?>" data-tdb-close="1">✕</button>

        <form id="tdbRequestForm" novalidate>
            <h2 id="tdbRequestTitle"><?php echo esc_html($_t($T['form_title'])); ?></h2>
            <p class="toa-tdb-form-intro"><?php echo esc_html($_t($T['form_intro'])); ?></p>

            <div class="toa-tdb-form-summary-wrap">
                <div class="toa-tdb-form-summary-label"><?php echo esc_html($_t($T['form_selection'])); ?></div>
                <div class="toa-tdb-form-summary" id="tdbRequestSummary"></div>
            </div>

            <div class="toa-tdb-field">
                <label class="toa-tdb-label"><?php echo esc_html($_t($T['form_name'])); ?> <span class="req">*</span></label>
                <input type="text" name="nome" class="toa-tdb-input" required autocomplete="name">
            </div>

            <div class="toa-tdb-field-row">
                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['form_email'])); ?> <span class="req">*</span></label>
                    <input type="email" name="email" class="toa-tdb-input" required autocomplete="email">
                </div>
                <div class="toa-tdb-field">
                    <label class="toa-tdb-label"><?php echo esc_html($_t($T['form_phone'])); ?> <span class="req">*</span></label>
                    <input type="tel" name="telefono" class="toa-tdb-input" required autocomplete="tel">
                </div>
            </div>

            <div class="toa-tdb-field">
                <label class="toa-tdb-label"><?php echo esc_html($_t($T['form_company'])); ?></label>
                <input type="text" name="azienda" class="toa-tdb-input" autocomplete="organization">
            </div>

            <div class="toa-tdb-field">
                <label class="toa-tdb-label"><?php echo esc_html($_t($T['form_project'])); ?> <span class="req">*</span></label>
                <textarea name="progetto" class="toa-tdb-textarea" rows="4" required></textarea>
            </div>

            <div class="toa-tdb-field">
                <label class="toa-tdb-label"><?php echo esc_html($_t($T['form_date'])); ?></label>
                <input type="date" name="data_progetto" class="toa-tdb-input">
            </div>

            <!-- Honeypot anti-spam -->
            <div style="position:absolute; left:-9999px; opacity:0;" aria-hidden="true">
                <label>Non compilare<input type="text" name="honeypot_url" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="toa-tdb-field">
                <label class="toa-tdb-checkbox">
                    <input type="checkbox" name="gdpr_consent" value="1" required>
                    <span><?php echo wp_kses($_t($T['form_gdpr']), array('a'=>array('href'=>array(),'target'=>array(),'rel'=>array()))); ?></span>
                </label>
            </div>

            <div class="toa-tdb-form-actions">
                <button type="button" class="toa-tdb-btn toa-tdb-btn-ghost" data-tdb-close="1"><?php echo esc_html($_t($T['form_back'])); ?></button>
                <button type="submit" class="toa-tdb-btn toa-tdb-btn-primary" id="tdbRequestSubmit"><?php echo esc_html($_t($T['form_submit'])); ?></button>
            </div>

            <div class="toa-tdb-form-msg" id="tdbRequestMsg" hidden></div>
        </form>
    </div>
</div>

<!-- ═════ Modal successo ═════ -->
<div class="toa-tdb-success" id="tdbSuccess" role="dialog" aria-modal="true" hidden>
    <div class="toa-tdb-success-card">
        <div class="toa-tdb-success-icon">✓</div>
        <h2><?php echo esc_html($_t($T['form_success'])); ?></h2>
        <p><?php echo esc_html($_t($T['form_success_msg'])); ?></p>
        <button type="button" class="toa-tdb-btn toa-tdb-btn-ghost" data-tdb-close="1"><?php echo esc_html($_t($T['modal_close'])); ?></button>
    </div>
</div>

<script src="<?php echo esc_url($theme_uri . '/assets/talent-database-v32.js'); ?>" defer></script>

<?php toa_component('footer'); ?>
