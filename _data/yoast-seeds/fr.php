<?php
/**
 * Seed Yoast SEO title + meta description — lingua FR
 * Auto-resolver WPML: parte dal slug IT, lookup trid, applica meta su post traduzione.
 * Usa url_to_postid() (gestisce slug IT duplicati).
 * Idempotente. Backup JSON automatico.
 */

$LANG_CODE = 'fr';

$pages = [
    '__front__'        => ['title' => 'Agence Casting B2B pour Entreprises en France et Europe | TOAgency', 'desc' => "Cherchez mannequins, hôtesses ou acteurs pour un événement en France ou en Europe ? TOAgency sélectionne les talents en 30 min. Devis gratuit."],
    'models'           => ['title' => 'Mannequins pour Entreprises en France et en Europe | TOAgency', 'desc' => "5 000+ mannequins actifs en France, Italie, Espagne, UK et toute l'Europe. Sélection en 48h pour shootings, e-commerce, campagnes."],
    'hostess-steward'  => ['title' => 'Hôtesses et Stewards pour Événements B2B en France et Europe | TOAgency', 'desc' => "Hôtesses, stewards et promoteurs pour salons et congrès en France et en Europe. Contrats certifiés, devis en 30 minutes."],
    'actors'           => ['title' => 'Acteurs et Figurants pour Cinéma, TV, Spots — France et Europe | TOAgency', 'desc' => "Casting acteurs, figurants et silhouettes pour cinéma, TV et publicité en France et en Europe. De 6 à 80 ans, disponibles en 48h."],
    'visuals'          => ['title' => 'Photographes et Vidéastes B2B en France et Europe | TOAgency', 'desc' => "Services photo et vidéo pour marques, e-commerce et campagnes en France, Italie, Espagne, UK et toute l'Europe."],
    'b2bservices'      => ['title' => 'Services B2B Casting et Talent pour Entreprises | TOAgency', 'desc' => "Sélection casting, logistique, contrats et facturation unique pour entreprises en France et en Europe. Un seul contact, partout."],
    'casting'          => ['title' => 'Casting Complet : Sélection et Logistique en Europe | TOAgency', 'desc' => "Gestion complète du casting pour productions en France et en Europe : sélection, contrats, mensurations certifiées, logistique. Depuis 2009."],
    'talent-database'  => ['title' => 'Castings Actifs en France, Italie, Espagne, UK et Europe | TOAgency', 'desc' => "Explorez les castings ouverts : mannequins, hôtesses, acteurs en France, Italie, Espagne, UK et toute l'Europe. Candidature en 2 min."],
    'about'            => ['title' => 'Qui Sommes-Nous : Agence Casting B2B France et Europe | TOAgency', 'desc' => "TOAgency : 15+ ans dans le casting B2B, 20 000+ professionnels, 50+ villes opérationnelles en France, Italie, Espagne, UK et Europe."],
    'collabora'        => ['title' => 'Rejoignez-Nous : Devenez Talent TOAgency | TOAgency', 'desc' => "Voulez-vous entrer dans la base TOAgency ? Candidatez comme mannequin, hôtesse, acteur pour événements en France et Europe."],
    'student-program'  => ['title' => 'Programme Étudiants : Jeunes pour Événements en Europe | TOAgency', 'desc' => "Programme étudiants TOAgency : emploi flexible pour les 18+ aux événements B2B en France, Italie, Espagne, UK et Europe."],
    'contact-us'       => ['title' => 'Contact — Devis Casting en 30 Minutes | TOAgency', 'desc' => "Vous avez un événement en France ou en Europe ? Contactez TOAgency pour un devis personnalisé en 30 minutes."],
    'form-b2b'         => ['title' => 'Demandez un Devis Casting B2B Gratuit | TOAgency', 'desc' => "Remplissez le formulaire : nous répondons en 30 min avec un devis personnalisé pour mannequins, hôtesses, acteurs."],
];

global $wpdb;
$backup = [];
$updated = 0;
$skipped = 0;
$missing = [];

foreach ($pages as $slug => $data) {
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

    $check_title = get_post_meta($target_id, '_yoast_wpseo_title', true);
    if ($check_title !== $data['title']) {
        echo "❌ /{$LANG_CODE}/$slug/ (ID $target_id) — WRITE FAILED\n";
        $skipped++;
        continue;
    }

    echo "✅ /{$LANG_CODE}/$slug/ (ID $target_id) — UPDATED\n";
    $updated++;
}

$backup_file = '/tmp/yoast-backup-pre-seed-' . $LANG_CODE . '-' . date('Ymd-His') . '.json';
file_put_contents($backup_file, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "\n=== Riepilogo $LANG_CODE ===\n";
echo "✅ Updated: $updated\n";
echo "⚠️  Skipped: $skipped\n";
if (!empty($missing)) echo "📋 Senza traduzione WPML: " . implode(', ', $missing) . "\n";
echo "📦 Backup: $backup_file\n";
