<?php
/**
 * Schema markup avanzato — TOAgency
 * FIX 2026-05-30 marco — schema-markup #33
 *
 * 1) wpseo_schema_organization → arricchisce il grafo Yoast (address, phone, email, priceRange, areaServed, contactPoint)
 * 2) wp_head Service           → nodo Service su 5 pagine servizio
 * 3) wp_head JobPosting        → JobPosting su /collabora/ (feed Google Jobs gratuito)
 *
 * NON duplica Organization: si aggancia al grafo Yoast esistente via filtro.
 *
 * NB: le chiavi di $map sono i basename reali dei template (verificati via wp-cli _wp_page_template):
 *     hostess-steward → page-hostess.php ; b2bservices → page-services.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// === Helper: basename del template corrente ===
function _toa_schema_tpl() {
    static $cached = null;
    if ( $cached !== null ) return $cached;
    $full   = get_page_template();
    $cached = $full ? basename( $full ) : '';
    return $cached;
}


// === 1) ORGANIZATION — arricchisce il nodo Yoast ===
add_filter( 'wpseo_schema_organization', function ( $data ) {
    $data['@type']      = [ 'Organization', 'ProfessionalService' ];
    $data['telephone']  = '+39 351 789 9225';
    $data['email']      = 'info@toagency.it';
    $data['priceRange'] = '€€€';
    $data['address']    = [
        '@type'           => 'PostalAddress',
        'streetAddress'   => 'Via Cavour, 21',
        'addressLocality' => 'Torino',
        'addressRegion'   => 'TO',
        'postalCode'      => '10123',
        'addressCountry'  => 'IT',
    ];
    $data['areaServed'] = [
        [ '@type' => 'Country', 'name' => 'Italy' ],
        [ '@type' => 'Country', 'name' => 'France' ],
        [ '@type' => 'Country', 'name' => 'Spain' ],
        [ '@type' => 'Country', 'name' => 'United Kingdom' ],
        [ '@type' => 'Country', 'name' => 'Germany' ],
    ];
    $data['contactPoint'] = [ [
        '@type'             => 'ContactPoint',
        'contactType'       => 'customer service',
        'telephone'         => '+39 351 789 9225',
        'availableLanguage' => [ 'Italian', 'English', 'French', 'Spanish' ],
    ] ];
    return $data;
} );


// === 2) SERVICE — pagine servizio ===
add_action( 'wp_head', function () {
    if ( is_admin() ) return;

    $org = 'https://toagency.it/#organization';

    $map = [
        'page-models.php' => [
            'name'        => 'Selezione e casting modelli',
            'serviceType' => 'Model & Talent Casting',
            'description' => 'Selezione professionale di modelli per campagne pubblicitarie, sfilate, eventi e produzioni video. Talent pool con oltre 20.000 profili verificati in Italia ed Europa.',
            'url'         => home_url( '/models/' ),
        ],
        'page-hostess-eventi15.php' => [
            'name'        => 'Hostess e steward per eventi',
            'serviceType' => 'Event Staffing',
            'description' => 'Fornitura di hostess, steward e personale eventi per fiere, congressi, lanci di prodotto e attività di promoter in Italia ed Europa.',
            'url'         => home_url( '/hostess-steward/' ),
        ],
        'page-actors2.php' => [
            'name'        => 'Attori per spot pubblicitari e produzioni',
            'serviceType' => 'Commercial & Film Casting',
            'description' => 'Casting di attori professionisti per spot pubblicitari, video istituzionali, produzioni cinematografiche e teatrali.',
            'url'         => home_url( '/actors/' ),
        ],
        'page-visuals.php' => [
            'name'        => 'Produzione visual e contenuti fotografici',
            'serviceType' => 'Visual Production',
            'description' => 'Produzione di contenuti fotografici e visual per campagne di comunicazione, lookbook, e-commerce e social media.',
            'url'         => home_url( '/visuals/' ),
        ],
        'page-services.php' => [
            'name'        => 'Servizi B2B — Casting e talent management',
            'serviceType' => 'B2B Talent Management',
            'description' => 'Servizi completi di casting, selezione talent e gestione contrattuale per brand, agenzie creative e case di produzione.',
            'url'         => home_url( '/b2bservices/' ),
        ],
    ];

    $tpl = _toa_schema_tpl();
    if ( ! isset( $map[ $tpl ] ) ) return;
    $s = $map[ $tpl ];

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Service',
        '@id'         => $s['url'] . '#service',
        'name'        => $s['name'],
        'serviceType' => $s['serviceType'],
        'description' => $s['description'],
        'url'         => $s['url'],
        'provider'    => [ '@id' => $org ],
        'areaServed'  => [
            [ '@type' => 'Country',   'name' => 'Italy' ],
            [ '@type' => 'Continent', 'name' => 'Europe' ],
        ],
    ];

    echo '<script type="application/ld+json">' . "\n"
        . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
        . "\n</script>\n";
}, 20 );


// === 3) JOB POSTING — /collabora/ ===
add_action( 'wp_head', function () {
    if ( is_admin() ) return;
    if ( _toa_schema_tpl() !== 'page-collabora.php' ) return;

    $schema = [
        '@context'       => 'https://schema.org',
        '@type'          => 'JobPosting',
        'title'          => 'Talent TOAgency — Modello, Hostess, Steward, Attore',
        'description'    => '<p>TOAgency seleziona continuativamente talent per collaborazioni con brand nazionali e internazionali.</p><p>Cerchiamo: modelli, hostess, steward, attori, promoter e personale eventi. Esperienza non necessaria per alcune figure. Lavoro flessibile su tutto il territorio italiano e in Europa.</p><p>Invia la candidatura: il team valuterà il profilo e contatterà i candidati idonei.</p>',
        'datePosted'     => gmdate( 'Y-m-d' ),
        'validThrough'   => gmdate( 'Y', strtotime( '+1 year' ) ) . '-12-31',
        'employmentType' => 'CONTRACTOR',
        'applicantLocationRequirements' => [
            '@type' => 'Country',
            'name'  => 'Italy',
        ],
        'jobLocationType'    => 'TELECOMMUTE',
        'hiringOrganization' => [
            '@type'  => 'Organization',
            '@id'    => 'https://toagency.it/#organization',
            'name'   => 'TOAgency',
            'sameAs' => 'https://toagency.it',
        ],
    ];

    echo '<script type="application/ld+json">' . "\n"
        . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT )
        . "\n</script>\n";
}, 20 );
