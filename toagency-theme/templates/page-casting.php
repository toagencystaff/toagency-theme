<?php
/**
 * Template Name: Casting
 * Description: Pagina casting con stile TOAgency dark theme
 * PATCH 2026-05-22 marco — badge lingua + filtro pill + tax_query lingua
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_title' => array('it' => 'Casting in Corso', 'en' => 'Active Castings', 'fr' => 'Castings en Cours', 'es' => 'Castings Activos'),
    'hero_sub' => array('it' => 'Ultimi annunci pubblicati', 'en' => 'Latest published listings', 'fr' => 'Derni&egrave;res annonces publi&eacute;es', 'es' => '&Uacute;ltimos anuncios publicados'),
    'social_title' => array('it' => 'Ricevi i casting in tempo reale', 'en' => 'Get castings in real time', 'fr' => 'Recevez les castings en temps r&eacute;el', 'es' => 'Recibe los castings en tiempo real'),
    'social_sub' => array(
        'it' => 'Iscriviti ai nostri canali per non perdere nessuna opportunit&agrave;: ogni nuovo casting, direttamente sul tuo telefono.',
        'en' => 'Subscribe to our channels so you never miss an opportunity: every new casting, straight to your phone.',
        'fr' => 'Abonnez-vous &agrave; nos canaux pour ne manquer aucune opportunit&eacute; : chaque nouveau casting, directement sur votre t&eacute;l&eacute;phone.',
        'es' => 'Suscr&iacute;bete a nuestros canales para no perder ninguna oportunidad: cada nuevo casting, directamente en tu tel&eacute;fono.',
    ),
    'wa_desc' => array('it' => 'Entra nella Community', 'en' => 'Join the Community', 'fr' => 'Rejoignez la Communaut&eacute;', 'es' => '&Uacute;nete a la Comunidad'),
    'ig_desc' => array('it' => 'Segui il Canale Broadcast', 'en' => 'Follow the Broadcast Channel', 'fr' => 'Suivez le Canal Broadcast', 'es' => 'Sigue el Canal Broadcast'),
    'fb_desc' => array('it' => 'Unisciti al Gruppo', 'en' => 'Join the Group', 'fr' => 'Rejoignez le Groupe', 'es' => '&Uacute;nete al Grupo'),
    'filter_all' => array('it' => 'Tutta Italia', 'en' => 'All Italy', 'fr' => 'Toute l\'Italie', 'es' => 'Toda Italia'),
    'filter_nord' => array('it' => 'Nord Italia', 'en' => 'Northern Italy', 'fr' => 'Italie du Nord', 'es' => 'Norte de Italia'),
    'filter_tutto_nord' => array('it' => 'Tutto il Nord', 'en' => 'All North', 'fr' => 'Tout le Nord', 'es' => 'Todo el Norte'),
    'filter_centro_label' => array('it' => 'Centro Italia', 'en' => 'Central Italy', 'fr' => 'Italie Centrale', 'es' => 'Centro de Italia'),
    'filter_tutto_centro' => array('it' => 'Tutto il Centro', 'en' => 'All Centre', 'fr' => 'Tout le Centre', 'es' => 'Todo el Centro'),
    'filter_sud_label' => array('it' => 'Sud e Isole', 'en' => 'South &amp; Islands', 'fr' => 'Sud et &Icirc;les', 'es' => 'Sur e Islas'),
    'filter_tutto_sud' => array('it' => 'Tutto il Sud', 'en' => 'All South', 'fr' => 'Tout le Sud', 'es' => 'Todo el Sur'),
    'filter_piemonte_liguria' => array('it' => 'Piemonte e Liguria', 'en' => 'Piedmont &amp; Liguria', 'fr' => 'Pi&eacute;mont et Ligurie', 'es' => 'Piamonte y Liguria'),
    'filter_marche_etc' => array('it' => 'Marche, Umbria, Abruzzo', 'en' => 'Marche, Umbria, Abruzzo', 'fr' => 'Marches, Ombrie, Abruzzes', 'es' => 'Marcas, Umbr&iacute;a, Abruzos'),
    'filter_molise_etc' => array('it' => 'Molise, Basilicata, Calabria', 'en' => 'Molise, Basilicata, Calabria', 'fr' => 'Molise, Basilicate, Calabre', 'es' => 'Molise, Basilicata, Calabria'),
    'filter_estero' => array('it' => 'Estero', 'en' => 'International', 'fr' => 'International', 'es' => 'Internacional'),
    'filter_francia' => array('it' => 'Francia', 'en' => 'France', 'fr' => 'France', 'es' => 'Francia'),
    'filter_spagna' => array('it' => 'Spagna', 'en' => 'Spain', 'fr' => 'Espagne', 'es' => 'Espa&ntilde;a'),
    'filter_uk' => array('it' => 'Regno Unito', 'en' => 'United Kingdom', 'fr' => 'Royaume-Uni', 'es' => 'Reino Unido'),
    'filter_altri' => array('it' => 'Altri Paesi', 'en' => 'Other Countries', 'fr' => 'Autres Pays', 'es' => 'Otros Pa&iacute;ses'),
    'scopri' => array('it' => 'Scopri', 'en' => 'Discover', 'fr' => 'D&eacute;couvrir', 'es' => 'Descubrir'),
    'no_casting_zona' => array('it' => 'Nessun casting attivo per questa zona.', 'en' => 'No active castings for this area.', 'fr' => 'Aucun casting actif pour cette zone.', 'es' => 'No hay castings activos para esta zona.'),
    'no_casting' => array('it' => 'Nessun casting attivo al momento.', 'en' => 'No active castings at the moment.', 'fr' => 'Aucun casting actif pour le moment.', 'es' => 'No hay castings activos en este momento.'),
    'vedi_tutti' => array('it' => 'Vedi tutti i casting', 'en' => 'View all castings', 'fr' => 'Voir tous les castings', 'es' => 'Ver todos los castings'),
    'prev' => array('it' => 'Precedente', 'en' => 'Previous', 'fr' => 'Pr&eacute;c&eacute;dent', 'es' => 'Anterior'),
    'next' => array('it' => 'Successivo', 'en' => 'Next', 'fr' => 'Suivant', 'es' => 'Siguiente'),
    // PATCH 2026-05-22 marco — filtro lingua
    'filter_lingua_label' => array('it' => 'Lingua', 'en' => 'Language', 'fr' => 'Langue', 'es' => 'Idioma'),
    'filter_lingua_all'   => array('it' => 'Tutte le lingue', 'en' => 'All languages', 'fr' => 'Toutes langues', 'es' => 'Todos los idiomas'),
    'filter_lingua_it'    => array('it' => '🇮🇹 Italiano',   'en' => '🇮🇹 Italian',    'fr' => '🇮🇹 Italien',    'es' => '🇮🇹 Italiano'),
    'filter_lingua_en'    => array('it' => '🇬🇧 Inglese',    'en' => '🇬🇧 English',    'fr' => '🇬🇧 Anglais',    'es' => '🇬🇧 Inglés'),
    'filter_lingua_fr'    => array('it' => '🇫🇷 Francese',   'en' => '🇫🇷 French',     'fr' => '🇫🇷 Français',   'es' => '🇫🇷 Francés'),
    'filter_lingua_es'    => array('it' => '🇪🇸 Spagnolo',   'en' => '🇪🇸 Spanish',    'fr' => '🇪🇸 Espagnol',   'es' => '🇪🇸 Español'),
    'filter_lingua_de'    => array('it' => '🇩🇪 Tedesco',    'en' => '🇩🇪 German',     'fr' => '🇩🇪 Allemand',   'es' => '🇩🇪 Alemán'),
    'filter_lingua_multi' => array('it' => '🌐 Multilingua', 'en' => '🌐 Multilingual','fr' => '🌐 Multilingue', 'es' => '🌐 Multilingüe'),
    // PATCH 2026-09-11 marco — menu paese-di-casa per lingua (TEMA-CASTING-PAESE-LINGUA)
    'filter_svizzera'            => array('it' => 'Svizzera', 'en' => 'Switzerland', 'fr' => 'Suisse', 'es' => 'Suiza'),
    'filter_tutti_paesi'         => array('it' => 'Tutti i casting', 'en' => 'All castings', 'fr' => 'Tous les castings', 'es' => 'Todos los castings'),
    'filter_international_group'=> array('it' => 'Internazionale', 'en' => 'International', 'fr' => 'International', 'es' => 'Internacional'),
    'home_country_title'         => array('it' => '', 'en' => '', 'fr' => 'Castings en France', 'es' => 'Castings en España'),
    'home_country_empty'         => array('it' => '', 'en' => '', 'fr' => 'Aucun casting en France en ce moment.', 'es' => 'No hay castings en España en este momento.'),
    'home_rest_title'            => array('it' => '', 'en' => '', 'fr' => 'En Italie et à l\'international', 'es' => 'En Italia y en el extranjero'),
    // PATCH 2026-09-11 marco — badge paese sul card, letto SEMPRE dall'originale italiano (punto 5)
    'badge_paese_italia'         => array('it' => '🇮🇹 Italia', 'en' => '🇮🇹 Italy', 'fr' => '🇮🇹 Italie', 'es' => '🇮🇹 Italia'),
    'badge_paese_francia'        => array('it' => '🇫🇷 Francia', 'en' => '🇫🇷 France', 'fr' => '🇫🇷 France', 'es' => '🇫🇷 Francia'),
    'badge_paese_spagna'         => array('it' => '🇪🇸 Spagna', 'en' => '🇪🇸 Spain', 'fr' => '🇪🇸 Espagne', 'es' => '🇪🇸 España'),
    'badge_paese_svizzera'       => array('it' => '🇨🇭 Svizzera', 'en' => '🇨🇭 Switzerland', 'fr' => '🇨🇭 Suisse', 'es' => '🇨🇭 Suiza'),
    'badge_paese_uk'             => array('it' => '🇬🇧 Regno Unito', 'en' => '🇬🇧 United Kingdom', 'fr' => '🇬🇧 Royaume-Uni', 'es' => '🇬🇧 Reino Unido'),
    'badge_paese_internazionale' => array('it' => '🌍 Internazionale', 'en' => '🌍 International', 'fr' => '🌍 International', 'es' => '🌍 Internacional'),
);

toa_component('header');
?>

<style>
/* RESET E BASE - DARK THEME */
.casting-wrapper {
    max-width: 100%;
    margin: 0;
    padding: 140px 8px 60px;
    font-family: var(--font-body);
}
/* HEADER */
.casting-hero {
    text-align: center;
    padding: 20px 8px;
    border-bottom: 1px solid var(--gray-2);
    margin-bottom: 20px;
}
.casting-hero h1 {
    font-family: var(--font-display);
    font-size: 24px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    color: var(--white);
}
.casting-hero p {
    font-size: 11px;
    color: var(--gray-4);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
/* SOCIAL BOX */
.social-box {
    background: var(--gray-1);
    border: 1px solid var(--gray-2);
    padding: 16px;
    margin: 20px 8px;
    text-align: center;
}
.social-box p {
    font-size: 12px;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    color: var(--gray-5);
}
.social-buttons {
    display: flex;
    gap: 4px;
    justify-content: center;
}
.social-btn {
    flex: 1;
    max-width: 100px;
    padding: 8px 4px;
    background: var(--white);
    color: var(--black);
    text-decoration: none;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    border: 1px solid var(--white);
    transition: all 0.2s;
}
.social-btn:hover {
    background: var(--accent);
    border-color: var(--accent);
    color: var(--black);
}
/* FILTRO REGIONI */
.region-filter-container {
    text-align: center;
    margin: 30px 8px 16px;
}
#regionFilter {
    width: 100%;
    max-width: 400px;
    padding: 12px;
    font-size: 13px;
    border: 1px solid var(--gray-2);
    background: var(--gray-1);
    color: var(--white);
    text-transform: uppercase;
    font-weight: 600;
    cursor: pointer;
    -webkit-appearance: none;
    appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    padding-right: 36px;
}
#regionFilter option {
    background: var(--black);
    color: var(--white);
}
#regionFilter:focus {
    outline: none;
    border-color: var(--accent);
}
/* PATCH 2026-05-22 marco — filtro lingua pill */
.language-filter-wrap {
    text-align: center;
    margin: 0 8px 28px;
}
.language-filter-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--gray-4, #555);
    margin-bottom: 10px;
    display: block;
}
.language-filter-pills {
    display: flex;
    gap: 8px;
    justify-content: center;
    flex-wrap: wrap;
}
.langue-btn {
    padding: 6px 14px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 100px;
    color: #888;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.18s;
    letter-spacing: 0.2px;
    white-space: nowrap;
    display: inline-block;
}
.langue-btn:hover {
    border-color: rgba(200,255,0,0.35);
    color: #c8ff00;
    background: rgba(200,255,0,0.06);
}
.langue-btn.active {
    border-color: #c8ff00;
    color: #c8ff00;
    background: rgba(200,255,0,0.1);
}
/* PATCH 2026-05-22 marco — badge lingua sul card */
.casting-badge-lingua {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 2;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 4px 8px;
    border-radius: 100px;
    font-size: 14px;
    border: 1px solid rgba(255,255,255,0.15);
    line-height: 1.2;
}
/* PATCH 2026-09-11 marco — titoli dei due blocchi (paese di casa / resto) FR-ES home */
.casting-block-title {
    font-family: var(--font-display);
    font-size: 16px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--white);
    margin: 32px 20px 16px;
}
.casting-block-empty {
    color: rgba(255,255,255,0.5);
    font-size: 13px;
    margin: 0 20px 24px;
}
/* === CASTING CARDS REDESIGN === */
.casting-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    padding: 0 20px;
}
.casting-item {
    display: block;
    text-decoration: none;
    color: #fff;
    border-radius: 16px;
    overflow: hidden;
    background: #111;
    border: 1px solid rgba(255,255,255,0.06);
    transition: transform 0.35s cubic-bezier(.2,.8,.3,1), box-shadow 0.35s;
    position: relative;
}
.casting-item:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    border-color: rgba(255,255,255,0.12);
}
/* THUMB */
.casting-thumb {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
}
.casting-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s cubic-bezier(.2,.8,.3,1);
}
.casting-item:hover .casting-thumb img {
    transform: scale(1.06);
}
.casting-thumb::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
    pointer-events: none;
}
.casting-thumb-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #1a1a1a;
    color: #333;
    font-size: 14px;
    letter-spacing: 3px;
}
/* BADGE LOCATION */
.casting-badge {
    position: absolute;
    top: 14px;
    left: 14px;
    z-index: 2;
    background: rgba(0,0,0,0.55);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 5px 14px;
    border-radius: 100px;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #fff;
    border: 1px solid rgba(255,255,255,0.12);
}
/* INFO */
.casting-info {
    padding: 22px 20px 20px;
}
.casting-title {
    font-family: var(--font-display, Georgia, serif);
    font-size: 18px;
    font-weight: 400;
    line-height: 1.35;
    margin-bottom: 8px;
    color: #fff;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.casting-description {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 4px;
    text-transform: capitalize;
}
.casting-meta-row {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}
.casting-meta-item {
    font-size: 12px;
    color: rgba(255,255,255,0.45);
    display: flex;
    align-items: center;
    gap: 5px;
}
.casting-meta-item strong {
    color: rgba(255,255,255,0.7);
    font-weight: 500;
}
/* FOOTER */
.casting-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 14px;
    border-top: 1px solid rgba(255,255,255,0.08);
}
.casting-date {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    letter-spacing: 0.3px;
}
.casting-link {
    font-size: 12px;
    color: #c8ff00;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    transition: letter-spacing 0.3s;
}
.casting-item:hover .casting-link {
    letter-spacing: 2.5px;
}
/* RESPONSIVE */
/* === PAGINATION === */
.casting-pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin: 48px 0 32px;
    padding: 0 20px;
    flex-wrap: wrap;
}
.casting-pagination .page-numbers {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    height: 44px;
    padding: 0 14px;
    border-radius: 10px;
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.1);
    color: #aaa;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
}
.casting-pagination .page-numbers:hover {
    background: rgba(200,255,0,0.12);
    border-color: rgba(200,255,0,0.3);
    color: #c8ff00;
}
.casting-pagination .page-numbers.current {
    background: #c8ff00;
    border-color: #c8ff00;
    color: #000;
    font-weight: 700;
}
.casting-pagination .page-numbers.dots {
    background: transparent;
    border-color: transparent;
    cursor: default;
    min-width: 30px;
    padding: 0 4px;
}
.casting-pagination .next,
.casting-pagination .prev {
    padding: 0 20px;
    font-weight: 600;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    font-size: 13px;
}
@media (max-width: 900px) {
    .casting-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
}
@media (max-width: 560px) {
    .casting-grid {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    .casting-title {
        font-size: 16px;
    }
    .casting-info {
        padding: 18px 16px 16px;
    }
}
</style>

<div class="casting-wrapper">

    <!-- HEADER -->
    <div class="casting-hero">
        <h1><?php echo $_t($t['hero_title']); ?></h1>
        <p><?php echo $_t($t['hero_sub']); ?></p>
    </div>

    <!-- SOCIAL -->
    <div class="social-box">
        <p class="social-box-title"><?php echo $_t($t['social_title']); ?></p>
        <p class="social-box-sub"><?php echo $_t($t['social_sub']); ?></p>
        <div class="social-buttons">
            <a href="https://toagency.it/itacommunities/" class="social-btn" target="_blank">
                <span class="social-btn-name">WhatsApp</span>
                <span class="social-btn-desc"><?php echo $_t($t['wa_desc']); ?></span>
            </a>
            <a href="https://www.instagram.com/toagency/" class="social-btn" target="_blank">
                <span class="social-btn-name">Instagram</span>
                <span class="social-btn-desc"><?php echo $_t($t['ig_desc']); ?></span>
            </a>
            <a href="https://www.facebook.com/groups/hostessmodelscastingcalls" class="social-btn" target="_blank">
                <span class="social-btn-name">Facebook</span>
                <span class="social-btn-desc"><?php echo $_t($t['fb_desc']); ?></span>
            </a>
        </div>
    </div>

    <!-- FILTRO REGIONI -->
    <?php
    $current_region = isset($_GET['regione']) ? sanitize_text_field($_GET['regione']) : '';
    // PATCH 2026-05-22 marco — lettura filtro lingua
    $current_lingua = isset($_GET['lingua']) ? sanitize_text_field($_GET['lingua']) : '';
    $valid_lingue   = array('it','en','fr','es','de','multi');
    if (!in_array($current_lingua, $valid_lingue, true)) $current_lingua = '';
    $base_url = get_permalink();

    // PATCH 2026-09-11 marco — paese "di casa" per lingua di visualizzazione (TEMA-CASTING-PAESE-LINGUA)
    // IT e EN non hanno un paese in evidenza: IT vede il menu regioni di sempre, EN vede la lista piatta.
    $home_country_by_lang = array('fr' => 'francia', 'es' => 'spagna');
    $home_country = isset($home_country_by_lang[$lang]) ? $home_country_by_lang[$lang] : '';

    // Etichette paese riusate sia dal menu (FR/ES/EN) sia, più sotto, dal badge sulle card.
    $country_menu_labels = array(
        'italia'         => '🇮🇹 ' . $_t($t['filter_all']),
        'francia'        => '🇫🇷 ' . $_t($t['filter_francia']),
        'spagna'         => '🇪🇸 ' . $_t($t['filter_spagna']),
        'svizzera'       => '🇨🇭 ' . $_t($t['filter_svizzera']),
        'uk'             => '🇬🇧 ' . $_t($t['filter_uk']),
        'internazionale' => '🌍 ' . $_t($t['filter_altri']),
    );
    ?>
    <div class="region-filter-container">
    <?php if ($lang === 'it') : ?>
        <!-- IT: menu regioni italiane invariato -->
        <select id="regionFilter" onchange="window.location.href=this.value">
            <option value="<?php echo $base_url . ($current_lingua ? '?lingua=' . $current_lingua : ''); ?>" <?php echo !$current_region ? 'selected' : ''; ?>><?php echo $_t($t['filter_all']); ?></option>
            <optgroup label="<?php echo esc_attr($_t($t['filter_nord'])); ?>">
                <option value="<?php echo $base_url; ?>?regione=nord-italia<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'nord-italia' ? 'selected' : ''; ?>><?php echo $_t($t['filter_tutto_nord']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=lombardia<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'lombardia' ? 'selected' : ''; ?>>Lombardia</option>
                <option value="<?php echo $base_url; ?>?regione=piemonte-liguria<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'piemonte-liguria' ? 'selected' : ''; ?>><?php echo $_t($t['filter_piemonte_liguria']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=triveneto<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'triveneto' ? 'selected' : ''; ?>>Triveneto</option>
                <option value="<?php echo $base_url; ?>?regione=emilia-romagna<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'emilia-romagna' ? 'selected' : ''; ?>>Emilia Romagna</option>
            </optgroup>
            <optgroup label="<?php echo esc_attr($_t($t['filter_centro_label'])); ?>">
                <option value="<?php echo $base_url; ?>?regione=centro-italia<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'centro-italia' ? 'selected' : ''; ?>><?php echo $_t($t['filter_tutto_centro']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=lazio<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'lazio' ? 'selected' : ''; ?>>Lazio</option>
                <option value="<?php echo $base_url; ?>?regione=toscana<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'toscana' ? 'selected' : ''; ?>>Toscana</option>
                <option value="<?php echo $base_url; ?>?regione=centro<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'centro' ? 'selected' : ''; ?>><?php echo $_t($t['filter_marche_etc']); ?></option>
            </optgroup>
            <optgroup label="<?php echo esc_attr($_t($t['filter_sud_label'])); ?>">
                <option value="<?php echo $base_url; ?>?regione=sud-isole<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'sud-isole' ? 'selected' : ''; ?>><?php echo $_t($t['filter_tutto_sud']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=campania<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'campania' ? 'selected' : ''; ?>>Campania</option>
                <option value="<?php echo $base_url; ?>?regione=puglia<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'puglia' ? 'selected' : ''; ?>>Puglia</option>
                <option value="<?php echo $base_url; ?>?regione=sud<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'sud' ? 'selected' : ''; ?>><?php echo $_t($t['filter_molise_etc']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=sicilia<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'sicilia' ? 'selected' : ''; ?>>Sicilia</option>
                <option value="<?php echo $base_url; ?>?regione=sardegna<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'sardegna' ? 'selected' : ''; ?>>Sardegna</option>
            </optgroup>
            <optgroup label="<?php echo esc_attr($_t($t['filter_estero'])); ?>">
                <option value="<?php echo $base_url; ?>?regione=francia<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'francia' ? 'selected' : ''; ?>><?php echo $_t($t['filter_francia']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=spagna<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'spagna' ? 'selected' : ''; ?>><?php echo $_t($t['filter_spagna']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=uk<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'uk' ? 'selected' : ''; ?>><?php echo $_t($t['filter_uk']); ?></option>
                <option value="<?php echo $base_url; ?>?regione=internazionale<?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region == 'internazionale' ? 'selected' : ''; ?>><?php echo $_t($t['filter_altri']); ?></option>
            </optgroup>
        </select>
    <?php elseif ($home_country) : ?>
        <!-- FR/ES: paese di casa in evidenza, poi gruppo "International" senza regioni italiane -->
        <?php $other_countries = array_diff(array_keys($country_menu_labels), array($home_country)); ?>
        <select id="regionFilter" onchange="window.location.href=this.value">
            <option value="<?php echo $base_url . ($current_lingua ? '?lingua=' . $current_lingua : ''); ?>" <?php echo !$current_region ? 'selected' : ''; ?>><?php echo $_t($t['filter_tutti_paesi']); ?></option>
            <option value="<?php echo $base_url; ?>?regione=<?php echo $home_country; ?><?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region === $home_country ? 'selected' : ''; ?> style="font-weight:700;color:var(--accent);"><?php echo $country_menu_labels[$home_country]; ?></option>
            <optgroup label="<?php echo esc_attr($_t($t['filter_international_group'])); ?>">
                <?php foreach ($other_countries as $slug) : ?>
                <option value="<?php echo $base_url; ?>?regione=<?php echo $slug; ?><?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region === $slug ? 'selected' : ''; ?>><?php echo $country_menu_labels[$slug]; ?></option>
                <?php endforeach; ?>
            </optgroup>
        </select>
    <?php else : ?>
        <!-- EN (e altre lingue future): lista piatta di paesi, nessuno in evidenza, niente regioni italiane -->
        <select id="regionFilter" onchange="window.location.href=this.value">
            <option value="<?php echo $base_url . ($current_lingua ? '?lingua=' . $current_lingua : ''); ?>" <?php echo !$current_region ? 'selected' : ''; ?>><?php echo $_t($t['filter_tutti_paesi']); ?></option>
            <?php foreach ($country_menu_labels as $slug => $label) : ?>
                <option value="<?php echo $base_url; ?>?regione=<?php echo $slug; ?><?php echo $current_lingua ? '&lingua='.$current_lingua : ''; ?>" <?php echo $current_region === $slug ? 'selected' : ''; ?>><?php echo $label; ?></option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
    </div>

    <!-- PATCH 2026-05-22 marco — filtro lingua pill buttons -->
    <?php
    $lingua_opts = array('it','en','fr','es','de','multi');
    // Costruisce URL preservando il filtro regione attivo
    $qs_regione = $current_region ? '&regione=' . $current_region : '';
    ?>
    <div class="language-filter-wrap">
        <span class="language-filter-label"><?php echo $_t($t['filter_lingua_label']); ?></span>
        <div class="language-filter-pills">
            <a href="<?php echo $base_url . ($current_region ? '?regione='.$current_region : ''); ?>"
               class="langue-btn <?php echo !$current_lingua ? 'active' : ''; ?>">
                <?php echo $_t($t['filter_lingua_all']); ?>
            </a>
            <?php foreach ($lingua_opts as $lc) : ?>
            <a href="<?php echo $base_url . '?lingua=' . $lc . $qs_regione; ?>"
               class="langue-btn <?php echo $current_lingua === $lc ? 'active' : ''; ?>">
                <?php echo $_t($t['filter_lingua_' . $lc]); ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- GRIGLIA CASTING -->
    <?php
    // PAGINAZIONE
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // MAPPATURA FILTRI -> CATEGORIE WORDPRESS
    $region_mapping = array(
        'nord-italia'    => array('lombardia', 'piemonte', 'liguria', 'valle-daosta', 'veneto', 'trentino-alto-adige', 'fvg', 'emilia-romagna', 'tutta-italia'),
        'centro-italia'  => array('lazio', 'toscana', 'marche', 'umbria', 'abruzzo', 'tutta-italia'),
        'sud-isole'      => array('campania', 'puglia', 'molise', 'basilicata', 'calabria', 'sicilia', 'sardegna', 'tutta-italia'),
        'lombardia'      => array('lombardia', 'tutta-italia'),
        'piemonte-liguria' => array('piemonte', 'valle-daosta', 'liguria', 'tutta-italia'),
        'triveneto'      => array('veneto', 'trentino-alto-adige', 'friuli-venezia-giulia', 'tutta-italia'),
        'emilia-romagna' => array('emilia-romagna', 'tutta-italia'),
        'lazio'          => array('lazio', 'tutta-italia'),
        'toscana'        => array('toscana', 'tutta-italia'),
        'centro'         => array('marche', 'umbria', 'abruzzo', 'tutta-italia'),
        'campania'       => array('campania', 'tutta-italia'),
        'puglia'         => array('puglia', 'tutta-italia'),
        'sud'            => array('molise', 'basilicata', 'calabria', 'tutta-italia'),
        'sicilia'        => array('sicilia', 'tutta-italia'),
        'sardegna'       => array('sardegna', 'tutta-italia'),
        'francia'        => array('francia'),
        'spagna'         => array('spagna'),
        'svizzera'       => array('svizzera'),
        'uk'             => array('regno-unito', 'uk'),
        'internazionale' => array('internazionale', 'estero'),
        // PATCH 2026-09-11 marco — voce aggregata "Italia" per i menu FR/ES/EN (tutte le regioni)
        'italia'         => array('lombardia', 'piemonte', 'liguria', 'valle-daosta', 'veneto', 'trentino-alto-adige', 'fvg', 'emilia-romagna', 'lazio', 'toscana', 'marche', 'umbria', 'abruzzo', 'campania', 'puglia', 'molise', 'basilicata', 'calabria', 'sicilia', 'sardegna', 'tutta-italia'),
    );

    // PATCH 2026-05-22 marco — mappa flag per badge sul card
    $lingua_flag_map = array(
        'lingua-it'    => '🇮🇹',
        'lingua-en'    => '🇬🇧',
        'lingua-fr'    => '🇫🇷',
        'lingua-es'    => '🇪🇸',
        'lingua-de'    => '🇩🇪',
        'lingua-multi' => '🌐',
    );

    // PATCH 2026-09-11 marco — helper WPML: il "paese" di un casting si legge SEMPRE dall'originale
    // italiano (mai dalla traduzione), perché WPML spesso non sincronizza la categoria paese sulle
    // traduzioni. Usato sia dal filtro (sotto) sia dal badge sulla card. Vedi TEMA-CASTING-PAESE-LINGUA punto 5.
    if (!function_exists('toa_casting_country_slug')) {
        function toa_casting_country_slug($post_id) {
            $it_id = apply_filters('wpml_object_id', $post_id, 'post', true, 'it');
            $cats  = wp_get_post_categories($it_id ? $it_id : $post_id, array('fields' => 'slugs'));
            $map = array(
                'francia'        => 'francia',
                'spagna'         => 'spagna',
                'svizzera'       => 'svizzera',
                'regno-unito'    => 'uk',
                'uk'             => 'uk',
                'internazionale' => 'internazionale',
                'estero'         => 'internazionale',
            );
            foreach ($map as $slug => $country) {
                if (in_array($slug, $cats, true)) {
                    return $country;
                }
            }
            return 'italia';
        }
    }
    if (!function_exists('toa_casting_region_post_ids')) {
        // Dati gli slug regione/paese, torna gli ID dei casting nella lingua $target_lang,
        // risalendo SEMPRE dalla categoria dell'originale italiano (stessa ragione sopra) —
        // così i vecchi link ?regione=... continuano a dare lo stesso risultato in ogni lingua.
        // FIX 2026-09-11 marco — il parametro 'lang' di WPML su WP_Query non è affidabile qui: senza
        // filtro esplicito, la query può includere una TRADUZIONE con categorie diverse dall'originale
        // (es. una traduzione francese taggata per errore "Francia", con l'originale italiano che
        // non lo è) — falso positivo. Per questo controlliamo la lingua post per post con l'API WPML,
        // tenendo SOLO i post che sono davvero l'originale italiano.
        function toa_casting_region_post_ids($slugs, $target_lang, $debug = false) {
            // FIX 2026-09-11-quater marco — via WP_Query/tax_query, WPML si reinserisce SEMPRE
            // (anche con 'lang'=>'all' e 'suppress_filters'=>true) e filtra i risultati in base
            // alla lingua della pagina corrente. Unica strada robusta: leggere le relazioni
            // categoria->post DIRETTAMENTE dal database, senza passare da WP_Query, poi
            // controllare la lingua di ogni post uno per uno con l'API ufficiale WPML.
            global $wpdb;
            $slugs = (array) $slugs;

            $casting_tt_id = $wpdb->get_var($wpdb->prepare(
                "SELECT tt.term_taxonomy_id FROM {$wpdb->term_taxonomy} tt
                 INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
                 WHERE tt.taxonomy = 'category' AND t.slug = %s LIMIT 1",
                'casting'
            ));
            if (!$casting_tt_id) {
                return array();
            }

            $slug_placeholders = implode(',', array_fill(0, count($slugs), '%s'));
            $slug_tt_ids = $wpdb->get_col($wpdb->prepare(
                "SELECT tt.term_taxonomy_id FROM {$wpdb->term_taxonomy} tt
                 INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
                 WHERE tt.taxonomy = 'category' AND t.slug IN ($slug_placeholders)",
                $slugs
            ));
            if (empty($slug_tt_ids)) {
                return array();
            }
            $slug_tt_ids_sql = implode(',', array_map('intval', $slug_tt_ids));

            $candidate_ids = $wpdb->get_col($wpdb->prepare(
                "SELECT tr1.object_id FROM {$wpdb->term_relationships} tr1
                 INNER JOIN {$wpdb->term_relationships} tr2 ON tr1.object_id = tr2.object_id
                 INNER JOIN {$wpdb->posts} p ON p.ID = tr1.object_id
                 WHERE tr1.term_taxonomy_id = %d
                 AND tr2.term_taxonomy_id IN ($slug_tt_ids_sql)
                 AND p.post_type = 'post' AND p.post_status = 'publish'",
                $casting_tt_id
            ));

            $ids = array();
            $debug_rows = array();
            foreach ($candidate_ids as $post_id) {
                $post_id      = (int) $post_id;
                $lang_details = apply_filters('wpml_post_language_details', null, $post_id);
                $post_lang    = (is_array($lang_details) && !empty($lang_details['language_code'])) ? $lang_details['language_code'] : null;
                if ($debug) {
                    $debug_rows[] = $post_id . ':' . ($post_lang ? $post_lang : '?') . ':' . get_the_title($post_id);
                }
                if ($post_lang !== 'it') {
                    continue; // tiene solo l'originale italiano, mai una traduzione
                }
                $translated_id = apply_filters('wpml_object_id', $post_id, 'post', false, $target_lang);
                if ($translated_id) {
                    $ids[] = (int) $translated_id;
                }
            }
            $ids = array_values(array_unique($ids));
            if ($debug) {
                echo "\n<!-- TOA_DEBUG slugs=" . esc_html(implode(',', $slugs)) . " target_lang=" . esc_html($target_lang) . " casting_tt_id=" . esc_html($casting_tt_id) . " slug_tt_ids=" . esc_html(implode(',', $slug_tt_ids)) . " raw_count=" . count($candidate_ids) . "\nraw: " . esc_html(implode(' | ', $debug_rows)) . "\nfinal_ids: " . esc_html(implode(',', $ids)) . " -->\n";
            }
            return $ids;
        }
    }
    if (!function_exists('toa_casting_render_card')) {
        // Markup di UNA card casting. Estratta in funzione perché serve in due punti:
        // la griglia principale e il blocco "paese di casa" (FR/ES) qui sotto.
        function toa_casting_render_card($t, $_t, $lingua_flag_map) {
            $content = get_the_content();
            $titolo  = get_the_title();

            $quando = '';
            if (preg_match('/Quando:<\/strong>\s*<span[^>]*>([^<]+)<\/span>/i', $content, $matches)) {
                $quando = trim(strip_tags($matches[1]));
            }
            $budget = '';
            if (preg_match('/budget:<\/strong>\s*<span[^>]*>€\s*([^<]+)<\/span>/i', $content, $matches)) {
                $budget = trim($matches[1]);
            }
            $profilo = '';
            if (preg_match('/Genere:<\/strong>\s*([^<]+)/i', $content, $matches)) {
                $profilo = trim(strip_tags($matches[1]));
            }
            $titolo_pulito = preg_replace('/^[A-Z]{2,3}-[A-Z]{2}-\\d{3}\\s*[-\xe2\x80\x93\xe2\x80\x94]?\\s*/i', '', $titolo);
            $titolo_pulito = trim($titolo_pulito) ?: $titolo;

            // PATCH 2026-05-22 marco — badge lingua dal tag WP category
            $lingua_badge = '';
            $post_cats = wp_get_post_categories(get_the_ID(), array('fields' => 'slugs'));
            foreach ($lingua_flag_map as $slug => $flag) {
                if (in_array($slug, $post_cats)) {
                    $lingua_badge = $flag;
                    break;
                }
            }

            // PATCH 2026-09-11 marco — badge paese (non più città), letto dall'originale IT
            $paese_slug  = toa_casting_country_slug(get_the_ID());
            $paese_badge = $_t($t['badge_paese_' . $paese_slug]);
            ?>
            <!-- CARD CASTING -->
            <a href="<?php the_permalink(); ?>" class="casting-item">
                <div class="casting-thumb">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium_large', array('alt' => esc_attr($titolo_pulito), 'loading' => 'lazy')); ?>
                    <?php else : ?>
                        <div class="casting-thumb-placeholder">TOAGENCY</div>
                    <?php endif; ?>
                    <span class="casting-badge"><?php echo esc_html($paese_badge); ?></span>
                    <?php if ($lingua_badge) : ?>
                        <span class="casting-badge-lingua"><?php echo $lingua_badge; ?></span>
                    <?php endif; ?>
                </div>
                <div class="casting-info">
                    <h3 class="casting-title"><?php echo esc_html($titolo_pulito); ?></h3>
                    <?php if ($profilo) : ?>
                        <div class="casting-description"><?php echo esc_html($profilo); ?></div>
                    <?php endif; ?>
                    <div class="casting-meta-row">
                        <?php if ($quando) : ?>
                            <span class="casting-meta-item">&#128197; <?php echo esc_html($quando); ?></span>
                        <?php endif; ?>
                        <?php if ($budget) : ?>
                            <span class="casting-meta-item">&#128176; &euro;<?php echo esc_html($budget); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="casting-footer">
                        <span class="casting-date"><?php echo get_the_date('j M Y'); ?></span>
                        <span class="casting-link"><?php echo $_t($t['scopri']); ?> &#8594;</span>
                    </div>
                </div>
            </a>
            <?php
        }
    }

    // PATCH 2026-09-11 marco — blocco "paese di casa" (FR/ES), solo home (nessun filtro) e pagina 1
    $show_home_block = $home_country && !$current_region && !$current_lingua && $paged == 1;
    if ($show_home_block) {
        $home_ids = toa_casting_region_post_ids($region_mapping[$home_country], $lang, isset($_GET['toa_debug']));
        add_filter('posts_pre_query', '__return_null', 9999);
        $home_query = new WP_Query(array(
            'post_type'       => 'post',
            'posts_per_page'  => -1,
            'orderby'         => 'date',
            'order'           => 'DESC',
            'post__in'        => !empty($home_ids) ? $home_ids : array(0),
            'date_query'      => array(array('after' => '60 days ago')),
            'lang'            => 'all',
            'suppress_filters'=> true,
            'cache_results'   => false, // vedi nota sulla query principale piu' sotto
        ));
        remove_filter('posts_pre_query', '__return_null', 9999);
        ?>
        <h2 class="casting-block-title"><?php echo esc_html($_t($t['home_country_title'])); ?></h2>
        <?php if ($home_query->have_posts()) : ?>
            <div class="casting-grid casting-grid-home">
            <?php while ($home_query->have_posts()) : $home_query->the_post();
                toa_casting_render_card($t, $_t, $lingua_flag_map);
            endwhile; ?>
            </div>
        <?php else : ?>
            <p class="casting-block-empty"><?php echo esc_html($_t($t['home_country_empty'])); ?></p>
        <?php endif;
        wp_reset_postdata();
        ?>
        <h2 class="casting-block-title"><?php echo esc_html($_t($t['home_rest_title'])); ?></h2>
    <?php } ?>

    <div class="casting-grid">
    <?php
    // PREPARA LA QUERY
    $args = array(
        'posts_per_page' => 12,
        'paged'          => $paged,
        'orderby'        => 'date',
        'order'          => 'DESC'
    );

    // PATCH 2026-09-11 marco — filtro regione/paese: gli ID si risolvono SEMPRE dall'originale
    // italiano (vedi helper sopra), così i vecchi link ?regione=... funzionano uguali in ogni lingua.
    if ($current_region && isset($region_mapping[$current_region])) {
        $matched_ids = toa_casting_region_post_ids($region_mapping[$current_region], $lang, isset($_GET['toa_debug']));
        $args['post__in'] = !empty($matched_ids) ? $matched_ids : array(0); // array(0) = nessun risultato
        // FIX 2026-09-12 marco — con 'post__in' gia' esatto (risolto sopra, per lingua), il filtro
        // automatico di WPML sulla query interferisce di nuovo (aggiunge un post estraneo ai
        // risultati): lo disattiviamo qui, gli ID sono gia' quelli giusti per questa lingua.
        $args['lang']              = 'all';
        $args['suppress_filters']  = true;
        $args['cache_results']     = false; // FIX 2026-09-12-bis: una cache oggetti (Memcached/Redis
                                             // SiteGround) restituiva risultati vecchi per questa
                                             // combinazione di filtri, mai invalidata dal deploy/purge

        if ($current_lingua) {
            $args['tax_query'] = array(
                array('taxonomy' => 'category', 'field' => 'slug', 'terms' => 'lingua-' . $current_lingua),
            );
        }
    } elseif ($current_lingua) {
        $args['tax_query'] = array(
            'relation' => 'AND',
            array('taxonomy' => 'category', 'field' => 'slug', 'terms' => 'casting'),
            array('taxonomy' => 'category', 'field' => 'slug', 'terms' => 'lingua-' . $current_lingua),
        );
    } else {
        // nessun filtro: query semplice per categoria casting (comportamento invariato)
        $args['category_name'] = 'casting';
    }

    // FIX 2026-09-12-ter marco — un plugin di cache (posts_pre_query) puo' restituire risultati
    // vecchi ignorando 'cache_results'/'suppress_filters': forziamo qui l'esecuzione reale.
    add_filter('posts_pre_query', '__return_null', 9999);
    $casting_query = new WP_Query($args);
    remove_filter('posts_pre_query', '__return_null', 9999);
    if (isset($_GET['toa_debug'])) {
        $found_ids = array();
        foreach ($casting_query->posts as $p) {
            $found_ids[] = is_object($p) ? $p->ID : $p;
        }
        echo "\n<!-- TOA_DEBUG_MAINQ post_in=" . esc_html(implode(',', isset($args['post__in']) ? $args['post__in'] : array())) . "\nfound_ids=" . esc_html(implode(',', $found_ids)) . "\nfound_count=" . esc_html($casting_query->found_posts) . "\nsql=" . esc_html($casting_query->request) . " -->\n";
    }

    // LOOP CASTING
    if ($casting_query->have_posts()) :
        while ($casting_query->have_posts()) : $casting_query->the_post();
            toa_casting_render_card($t, $_t, $lingua_flag_map);
        endwhile; ?>

    <?php else : ?>
        <!-- NESSUN CASTING -->
        <div class="no-casting">
            <p>
                <?php
                if ($current_region || $current_lingua) {
                    echo $_t($t['no_casting_zona']);
                } else {
                    echo $_t($t['no_casting']);
                }
                ?>
            </p>
            <?php if ($current_region || $current_lingua) : ?>
                <p><a href="<?php echo $base_url; ?>"><?php echo $_t($t['vedi_tutti']); ?></a></p>
            <?php endif; ?>
        </div>
    <?php endif;
    wp_reset_postdata();
    ?>
    </div>

    <!-- PAGINAZIONE -->
    <?php if ($casting_query->max_num_pages > 1) : ?>
    <div class="casting-pagination">
        <?php
        // PATCH 2026-05-22 marco — paginazione preserva regione + lingua
        $qs_pagina = array();
        if ($current_region) $qs_pagina[] = 'regione=' . $current_region;
        if ($current_lingua) $qs_pagina[] = 'lingua=' . $current_lingua;
        $pagination_args = array(
            'total'     => $casting_query->max_num_pages,
            'current'   => $paged,
            'prev_text' => $_t($t['prev']),
            'next_text' => $_t($t['next']),
            'type'      => 'plain',
            'end_size'  => 2,
            'mid_size'  => 2
        );
        if (!empty($qs_pagina)) {
            $pagination_args['format'] = '?paged=%#%&' . implode('&', $qs_pagina);
        }
        echo paginate_links($pagination_args);
        ?>
    </div>
    <?php endif; ?>

</div>

<?php toa_component('footer'); ?>
