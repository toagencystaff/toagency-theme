<?php
/**
 * Template Name: Proposta Cliente
 * v1.0 — 18 Agosto 2026 (chat TEMA DISPO-PROPOSTA)
 *
 * Path: /wp-content/themes/toagency-theme/templates/page-proposta.php
 *
 * Pagina privata /proposta/?t=TOKEN — veste grafica del tema per i link
 * proposta generati dal CRM (sostituisce crm_toagency/dispo-proposta.php).
 * Due livelli di link: "ipotetico" (raggiungibili per città/prezzo) ed
 * "effettivo" (solo disponibilità confermate). Token-gated + noindex
 * (decisione B1: niente vetrina pubblica). I PREZZI SI VEDONO (18/08).
 *
 * Dati:  /actions/dispo-proposta-api.php?t=TOKEN  (fetch client-side,
 *        stesso pattern di api-talent-database.php). Contratto:
 *        {ok, tipo, titolo, luogo, dal, al, giorni,
 *         cards:[{nome, eta, altezza, lingue, citta, foto,
 *                 prezzo_giorno, prezzo_totale}]}
 *        Filtri applicati LATO CLIENT sulle card ricevute (dataset
 *        piccolo, niente ri-fetch).
 *
 * Look:  riusa talent-database-v81.css (card/griglia/hero identiche a
 *        /talent-database/) + proposta-v1.css solo per prezzi/badge/filtri.
 * Privacy: nome + INIZIALE cognome (formattato dal CRM), foto già
 *        filtrate lato CRM (minori esclusi, solo visibile_pubblico).
 */

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, array('it','en','fr','es'), true)) $__l = 'it';

$_t = function ($a) use ($__l) {
    return isset($a[$__l]) ? $a[$__l] : $a['it'];
};

$theme_uri = get_stylesheet_directory_uri();

// ─── String table markup PHP (IT/EN/FR/ES) ───────────────────────────
$T = array(
    'eyebrow'       => array('it'=>'TOAgency / Proposta','en'=>'TOAgency / Proposal','fr'=>'TOAgency / Proposition','es'=>'TOAgency / Propuesta'),
    'loading'       => array('it'=>'Carico la proposta…','en'=>'Loading the proposal…','fr'=>'Chargement de la proposition…','es'=>'Cargando la propuesta…'),
    'error_contact' => array(
        'it'=>'Serve aiuto? Scrivi a','en'=>'Need help? Write to',
        'fr'=>'Besoin d\'aide ? Écris à','es'=>'¿Necesitas ayuda? Escribe a',
    ),
    'filter_gender' => array('it'=>'Genere','en'=>'Gender','fr'=>'Genre','es'=>'Género'),
    'filter_any'    => array('it'=>'Tutti','en'=>'All','fr'=>'Tous','es'=>'Todos'),
    'filter_f'      => array('it'=>'F','en'=>'F','fr'=>'F','es'=>'F'),
    'filter_m'      => array('it'=>'M','en'=>'M','fr'=>'M','es'=>'M'),
    'filter_height' => array('it'=>'Altezza','en'=>'Height','fr'=>'Taille','es'=>'Altura'),
    'h_any'         => array('it'=>'Tutte','en'=>'Any','fr'=>'Toutes','es'=>'Todas'),
    'filter_age'    => array('it'=>'Età','en'=>'Age','fr'=>'Âge','es'=>'Edad'),
    'filter_min'    => array('it'=>'Da','en'=>'From','fr'=>'De','es'=>'Desde'),
    'filter_max'    => array('it'=>'A','en'=>'To','fr'=>'À','es'=>'Hasta'),
    'filter_langs'  => array('it'=>'Lingue','en'=>'Languages','fr'=>'Langues','es'=>'Idiomas'),
    /* 2026-08-18 — durata giornata (hd): il prezzo si ricalcola lato server */
    'filter_duration'=> array('it'=>'Durata giornata','en'=>'Day length','fr'=>'Durée de la journée','es'=>'Duración de la jornada'),
    'dur_req'       => array('it'=>'Come richiesto','en'=>'As requested','fr'=>'Comme demandé','es'=>'Como solicitado'),
    'hours'         => array('it'=>'ore','en'=>'hours','fr'=>'heures','es'=>'horas'),
    'filter_level'  => array('it'=>'Livello minimo','en'=>'Minimum level','fr'=>'Niveau minimum','es'=>'Nivel mínimo'),
    'level_any'     => array('it'=>'Qualsiasi','en'=>'Any','fr'=>'Indifférent','es'=>'Cualquiera'),
    'filter_reset'  => array('it'=>'Reset','en'=>'Reset','fr'=>'Reset','es'=>'Reset'),
    'note_privacy'  => array(
        'it'=>'Per privacy mostriamo nome e iniziale. Contatta TOAgency per i dettagli completi.',
        'en'=>'For privacy we show first name and initial only. Contact TOAgency for full details.',
        'fr'=>'Confidentialité : prénom et initiale uniquement. Contacte TOAgency pour les détails.',
        'es'=>'Por privacidad mostramos nombre e inicial. Contacta a TOAgency para más detalles.',
    ),
);

// ─── Stringhe passate al JS (proposta-v1.js) ─────────────────────────
$J = array(
    'err_token'   => array(
        'it'=>'Link non valido o scaduto.','en'=>'Invalid or expired link.',
        'fr'=>'Lien invalide ou expiré.','es'=>'Enlace no válido o caducado.',
    ),
    'err_generic' => array(
        'it'=>'Errore di caricamento. Riprova tra qualche istante.','en'=>'Loading error. Please try again shortly.',
        'fr'=>'Erreur de chargement. Réessaie dans un instant.','es'=>'Error de carga. Inténtalo de nuevo en unos instantes.',
    ),
    'badge_ipotetico'  => array('it'=>'Selezione preliminare','en'=>'Preliminary selection','fr'=>'Sélection préliminaire','es'=>'Selección preliminar'),
    'badge_effettivo'  => array('it'=>'Disponibilità confermate','en'=>'Confirmed availability','fr'=>'Disponibilités confirmées','es'=>'Disponibilidad confirmada'),
    'note_ipotetico'   => array(
        'it'=>'Profili raggiungibili per zona e budget: disponibilità da confermare.',
        'en'=>'Profiles matching area and budget: availability to be confirmed.',
        'fr'=>'Profils compatibles zone et budget : disponibilité à confirmer.',
        'es'=>'Perfiles compatibles por zona y presupuesto: disponibilidad por confirmar.',
    ),
    'note_effettivo'   => array(
        'it'=>'Solo profili con disponibilità confermata per le date indicate.',
        'en'=>'Only profiles with confirmed availability for the listed dates.',
        'fr'=>'Uniquement des profils à la disponibilité confirmée pour ces dates.',
        'es'=>'Solo perfiles con disponibilidad confirmada para las fechas indicadas.',
    ),
    'day_s'     => array('it'=>'giorno','en'=>'day','fr'=>'jour','es'=>'día'),
    'day_p'     => array('it'=>'giorni','en'=>'days','fr'=>'jours','es'=>'días'),
    'years'     => array('it'=>'anni','en'=>'y.o.','fr'=>'ans','es'=>'años'),
    'price_day' => array('it'=>'/giorno','en'=>'/day','fr'=>'/jour','es'=>'/día'),
    'price_tot' => array('it'=>'Totale evento','en'=>'Event total','fr'=>'Total événement','es'=>'Total evento'),
    'count_s'   => array('it'=>'profilo','en'=>'profile','fr'=>'profil','es'=>'perfil'),
    'count_p'   => array('it'=>'profili','en'=>'profiles','fr'=>'profils','es'=>'perfiles'),
    'empty'     => array('it'=>'Nessun profilo corrisponde ai filtri.','en'=>'No profile matches your filters.','fr'=>'Aucun profil ne correspond.','es'=>'Ningún perfil coincide.'),
    'note_anticipo' => array(
        'it'=>'Prezzi con pagamento anticipato ({pct}%)','en'=>'Prices with {pct}% advance payment',
        'fr'=>'Prix avec paiement anticipé ({pct} %)','es'=>'Precios con pago anticipado ({pct} %)',
    ),
    'liv_base'        => array('it'=>'Base','en'=>'Basic','fr'=>'Base','es'=>'Básico'),
    'liv_intermedio'  => array('it'=>'Intermedio','en'=>'Intermediate','fr'=>'Intermédiaire','es'=>'Intermedio'),
    'liv_fluente'     => array('it'=>'Fluente','en'=>'Fluent','fr'=>'Courant','es'=>'Fluido'),
    'liv_madrelingua' => array('it'=>'Madrelingua','en'=>'Native','fr'=>'Langue maternelle','es'=>'Nativo'),
);
$J_out = array();
foreach ($J as $k => $a) { $J_out[$k] = $_t($a); }
?>
<!-- TOA-PROPOSTA-V1 — 2026-08-18 chat TEMA DISPO-PROPOSTA -->
<meta name="robots" content="noindex,nofollow,noarchive">
<link rel="stylesheet" href="<?php echo esc_url($theme_uri . '/assets/talent-database-v81.css?v=' . filemtime(get_stylesheet_directory() . '/assets/talent-database-v81.css')); ?>">
<link rel="stylesheet" href="<?php echo esc_url($theme_uri . '/assets/proposta-v1.css?v=' . filemtime(get_stylesheet_directory() . '/assets/proposta-v1.css')); ?>">
<script>
window.toaPropApiUrl = "/crm_toagency/actions/dispo-proposta-api.php"; /* 2026-08-18 — path REALE verificato (su /actions/ = 404 WP) */
window.toaPropLang   = "<?php echo esc_js($__l); ?>";
window.toaPropI18n   = <?php echo wp_json_encode($J_out); ?>;
</script>

<section class="toa-tdb-wrap toa-prop-wrap" id="prop-top">

    <!-- ═════ Hero (stesso stile compatto di /talent-database/) ═════ -->
    <header class="toa-tdb-hero toa-tdb-hero-compact toa-prop-hero">
        <p class="toa-prop-eyebrow"><?php echo esc_html($_t($T['eyebrow'])); ?></p>
        <span class="toa-prop-badge" id="propBadge" hidden></span>
        <h1 class="toa-tdb-hero-title" id="propTitle">&nbsp;</h1>
        <p class="toa-tdb-hero-subtitle toa-prop-meta" id="propMeta"></p>
        <p class="toa-prop-tipo-note" id="propTipoNote"></p>
        <p class="toa-prop-condizioni" id="propCondizioni" hidden></p>
    </header>

    <!-- ═════ Filtri (client-side, compaiono a dati caricati) ═════ -->
    <div class="toa-prop-filters" id="propFilters" hidden>
        <div class="toa-prop-filter" id="propFieldSesso">
            <label class="toa-tdb-label" for="propSesso"><?php echo esc_html($_t($T['filter_gender'])); ?></label>
            <select id="propSesso" class="toa-tdb-select">
                <option value=""><?php echo esc_html($_t($T['filter_any'])); ?></option>
                <option value="f"><?php echo esc_html($_t($T['filter_f'])); ?></option>
                <option value="m"><?php echo esc_html($_t($T['filter_m'])); ?></option>
            </select>
        </div>
        <div class="toa-prop-filter">
            <label class="toa-tdb-label" for="propFascia"><?php echo esc_html($_t($T['filter_height'])); ?></label>
            <?php /* 2026-08-18 — fasce h1/h2/h3 riempite dal JS con opzioni.altezze dell'API
                     (il filtro altezza CAMBIA il prezzo → filtro server-side, mai client) */ ?>
            <select id="propFascia" class="toa-tdb-select">
                <option value=""><?php echo esc_html($_t($T['h_any'])); ?></option>
            </select>
        </div>
        <div class="toa-prop-filter">
            <span class="toa-tdb-label"><?php echo esc_html($_t($T['filter_age'])); ?></span>
            <div class="toa-prop-range">
                <input type="number" id="propEtaMin" class="toa-tdb-input toa-tdb-input-sm" min="16" max="99" placeholder="<?php echo esc_attr($_t($T['filter_min'])); ?>">
                <input type="number" id="propEtaMax" class="toa-tdb-input toa-tdb-input-sm" min="16" max="99" placeholder="<?php echo esc_attr($_t($T['filter_max'])); ?>">
            </div>
        </div>
        <div class="toa-prop-filter" id="propFieldLingue">
            <span class="toa-tdb-label"><?php echo esc_html($_t($T['filter_langs'])); ?></span>
            <button type="button" class="toa-prop-lang-toggle" id="propLingueToggle" aria-expanded="false" aria-controls="propLinguePanel">
                <?php echo esc_html($_t($T['filter_langs'])); ?> <span aria-hidden="true">▾</span>
            </button>
            <div class="toa-prop-lang-panel" id="propLinguePanel" hidden>
                <div class="toa-prop-lang-list" id="propLingueList"><!-- checkbox generati dal JS --></div>
                <label class="toa-tdb-label" for="propLivMin"><?php echo esc_html($_t($T['filter_level'])); ?></label>
                <?php /* 2026-08-18 — scala REALE CRM a 3 livelli: 1=Base 2=Fluente 3=Madrelingua
                         (via il vecchio "intermedio" a 4 livelli stile talent-db) */ ?>
                <select id="propLivMin" class="toa-tdb-select">
                    <option value=""><?php echo esc_html($_t($T['level_any'])); ?></option>
                    <option value="1"><?php echo esc_html($_t($J['liv_base'])); ?></option>
                    <option value="2"><?php echo esc_html($_t($J['liv_fluente'])); ?></option>
                    <option value="3"><?php echo esc_html($_t($J['liv_madrelingua'])); ?></option>
                </select>
            </div>
        </div>
        <div class="toa-prop-filter">
            <label class="toa-tdb-label" for="propDurata"><?php echo esc_html($_t($T['filter_duration'])); ?></label>
            <select id="propDurata" class="toa-tdb-select">
                <option value=""><?php echo esc_html($_t($T['dur_req'])); ?></option>
                <option value="4">4 <?php echo esc_html($_t($T['hours'])); ?></option>
                <option value="6">6 <?php echo esc_html($_t($T['hours'])); ?></option>
                <option value="8">8 <?php echo esc_html($_t($T['hours'])); ?></option>
            </select>
        </div>
        <button type="button" class="toa-prop-reset" id="propReset"><?php echo esc_html($_t($T['filter_reset'])); ?></button>
        <span class="toa-tdb-results-count toa-prop-count" id="propCount"></span>
    </div>

    <!-- ═════ Stati ═════ -->
    <div class="toa-prop-state" id="propLoading"><?php echo esc_html($_t($T['loading'])); ?></div>
    <div class="toa-prop-state toa-prop-state--error" id="propError" hidden>
        <p id="propErrorMsg"></p>
        <p><?php echo esc_html($_t($T['error_contact'])); ?> <a href="mailto:info@toagency.it">info@toagency.it</a></p>
    </div>

    <!-- ═════ Griglia card (stesse classi di /talent-database/) ═════ -->
    <div class="toa-tdb-grid toa-prop-grid" id="propGrid" aria-live="polite" hidden></div>
    <div class="toa-tdb-grid-empty" id="propEmpty" hidden><?php echo esc_html($_t($J['empty'])); ?></div>

    <p class="toa-prop-privacy" id="propPrivacy" hidden><?php echo esc_html($_t($T['note_privacy'])); ?></p>

</section>

<script src="<?php echo esc_url($theme_uri . '/assets/proposta-v1.js?v=' . filemtime(get_stylesheet_directory() . '/assets/proposta-v1.js')); ?>" defer></script>

<?php toa_component('footer'); ?>
