<?php
/**
 * Seed Yoast SEO title + meta description — lingua EN
 * Auto-resolver WPML: parte dal slug IT, lookup trid, applica meta su post traduzione.
 * Usa url_to_postid() (gestisce slug IT duplicati: vedi /models/ ID 168397 vs 263867).
 * Idempotente. Backup JSON automatico.
 */

$LANG_CODE = 'en';

$pages = [
    '__front__'        => ['title' => 'B2B Casting Agency for Brands in UK & Europe | TOAgency', 'desc' => 'Need models, hostesses or actors for an event in the UK or Europe? TOAgency selects talent in 30 minutes. Free quote.'],
    'models'           => ['title' => 'Models for Brands in the UK and across Europe | TOAgency', 'desc' => '5,000+ active models in the UK, Italy, France, Spain & Europe. Selection in 48h for shoots, e-commerce, campaigns.'],
    'hostess-steward'  => ['title' => 'Hostess & Staff for B2B Events in the UK & Europe | TOAgency', 'desc' => 'Hostesses, stewards and promoters for fairs and congresses across the UK and Europe. Certified contracts, quote in 30 min.'],
    'actors'           => ['title' => 'Actors & Extras for Film, TV, Commercials — UK & Europe | TOAgency', 'desc' => 'Casting actors, extras and supporting cast for film, TV and ads in the UK and Europe. Ages 6-80, available in 48h.'],
    'visuals'          => ['title' => 'Photographers & Videomakers B2B in the UK & Europe | TOAgency', 'desc' => 'Photo and video services for brands, e-commerce and campaigns in the UK, Italy, France, Spain & Europe.'],
    'b2bservices'      => ['title' => 'B2B Casting and Talent Services for Brands | TOAgency', 'desc' => 'Casting selection, logistics, contracts and single invoice for brands across the UK and Europe. One contact, anywhere.'],
    'casting'          => ['title' => 'Full Casting: Selection & Logistics in Europe | TOAgency', 'desc' => 'End-to-end casting management for productions in the UK and Europe: selection, contracts, certified measurements, logistics. Since 2009.'],
    'talent-database'  => ['title' => 'Active Castings in UK, Italy, France, Spain & Europe | TOAgency', 'desc' => 'Browse open castings: models, hostesses, actors in the UK, Italy, France, Spain and Europe. Apply in 2 minutes.'],
    'about'            => ['title' => 'About Us: B2B Casting Agency UK & Europe | TOAgency', 'desc' => 'TOAgency: 15+ years in B2B casting, 20,000+ professionals, 50+ operational cities in the UK, Italy, France, Spain & Europe.'],
    'collabora'        => ['title' => 'Work with Us: Become a TOAgency Talent | TOAgency', 'desc' => 'Want to join the TOAgency database? Apply as model, hostess or actor for events across the UK and Europe. Professional selection.'],
    'student-program'  => ['title' => 'Student Program: Students for Events & Fairs in Europe | TOAgency', 'desc' => 'TOAgency student program: flexible work for over-18s at B2B events in the UK, Italy, France, Spain and Europe.'],
    'contact-us'       => ['title' => 'Contact — Casting Quote in 30 Minutes | TOAgency', 'desc' => 'Got an event in the UK or Europe? Contact TOAgency for a tailored quote in 30 minutes. Email, phone, online form.'],
    'form-b2b'         => ['title' => 'Request a Free B2B Casting Quote | TOAgency', 'desc' => 'Fill in the form: we reply in 30 min with a tailored quote for models, hostesses, actors for your event in the UK or Europe.'],
];

global $wpdb;
$backup = [];
$updated = 0;
$skipped = 0;
$missing = [];

foreach ($pages as $slug => $data) {
    // Risolvi post IT sorgente via url_to_postid (gestisce slug duplicati IT)
    if ($slug === '__front__') {
        $it_post_id = (int) get_option('page_on_front');
    } else {
        $it_post_id = (int) url_to_postid(home_url("/$slug/"));
    }
    if (!$it_post_id) {
        echo "⚠️  IT '/$slug/' NON TROVATA — SKIP\n";
        $missing[] = $slug;
        $skipped++;
        continue;
    }
    $it_post = get_post($it_post_id);
    if (!$it_post) {
        echo "⚠️  /$slug/ — get_post fallito per ID $it_post_id — SKIP\n";
        $skipped++;
        continue;
    }

    // WPML lookup: trid del post IT
    $element_type = 'post_' . $it_post->post_type;
    $trid = $wpdb->get_var($wpdb->prepare(
        "SELECT trid FROM {$wpdb->prefix}icl_translations WHERE element_id = %d AND element_type = %s",
        $it_post->ID, $element_type
    ));
    if (!$trid) {
        echo "⚠️  /$slug/ (ID {$it_post->ID}) — nessun trid WPML — SKIP\n";
        $skipped++;
        continue;
    }

    // post_id della traduzione nella lingua target
    $target_id = $wpdb->get_var($wpdb->prepare(
        "SELECT element_id FROM {$wpdb->prefix}icl_translations WHERE trid = %d AND language_code = %s AND element_type = %s",
        $trid, $LANG_CODE, $element_type
    ));
    if (!$target_id) {
        echo "⚠️  /$slug/ — traduzione $LANG_CODE non esiste (redirect a IT) — SKIP\n";
        $missing[] = $slug;
        $skipped++;
        continue;
    }

    $old_title = get_post_meta($target_id, '_yoast_wpseo_title', true);
    $old_desc  = get_post_meta($target_id, '_yoast_wpseo_metadesc', true);
    $backup[$slug] = ['post_id' => (int) $target_id, 'old_title' => $old_title, 'old_desc' => $old_desc];

    update_post_meta($target_id, '_yoast_wpseo_title', $data['title']);
    update_post_meta($target_id, '_yoast_wpseo_metadesc', $data['desc']);

    // Read-back per assicurarsi che update_post_meta non sia fallito
    $check_title = get_post_meta($target_id, '_yoast_wpseo_title', true);
    if ($check_title !== $data['title']) {
        echo "❌ /{$LANG_CODE}/$slug/ (ID $target_id) — WRITE FAILED\n";
        $skipped++;
        continue;
    }

    echo "✅ /{$LANG_CODE}/$slug/ (ID $target_id) — UPDATED\n";
    $updated++;
}

// Salva backup
$backup_file = '/tmp/yoast-backup-pre-seed-' . $LANG_CODE . '-' . date('Ymd-His') . '.json';
file_put_contents($backup_file, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "\n=== Riepilogo $LANG_CODE ===\n";
echo "✅ Updated: $updated\n";
echo "⚠️  Skipped: $skipped\n";
if (!empty($missing)) echo "📋 Senza traduzione WPML (slug IT-only o redirect): " . implode(', ', $missing) . "\n";
echo "📦 Backup: $backup_file\n";
