<?php
/**
 * Seed Yoast SEO title + meta description — ITALIANO v2
 * Posizionamento: 4 hub (IT/FR/ES/UK) + raggio Europa
 * Idempotente. Backup pre-existing meta in /tmp/yoast-backup-pre-seed-{ts}.json
 */

$pages = [
    '__front__'        => ['title' => 'Agenzia Casting B2B per Aziende in Italia ed Europa | TOAgency', 'desc' => 'Cerchi modelli, hostess o attori per un evento in Italia o in Europa? TOAgency seleziona talent in 30 minuti. Preventivo gratuito.'],
    'models'           => ['title' => 'Modelli per Aziende in Italia e in tutta Europa | TOAgency', 'desc' => '5.000+ modelli e modelle attivi in Italia, Francia, Spagna, UK e tutta Europa. Selezione in 48h per shooting, e-commerce, campagne.'],
    'hostess-steward'  => ['title' => 'Hostess e Steward per Eventi B2B in Italia ed Europa | TOAgency', 'desc' => 'Hostess, steward e promoter per fiere e congressi in Italia e in Europa. Contratti CCNL, preventivo in 30 minuti.'],
    'actors'           => ['title' => 'Attori e Comparse per Film, TV, Spot — Italia ed Europa | TOAgency', 'desc' => 'Casting attori, comparse e figuranti per cinema, TV e pubblicità in Italia e in Europa. Da 6 a 80 anni, disponibili in 48h.'],
    'visuals'          => ['title' => 'Fotografi e Videomaker B2B in Italia ed Europa | TOAgency', 'desc' => 'Servizi fotografici e video per brand, e-commerce e campagne in Italia, Francia, Spagna, UK e tutta Europa.'],
    'b2bservices'      => ['title' => 'Servizi B2B Casting e Talent per Aziende | TOAgency', 'desc' => 'Selezione casting, logistica, contratti e fatturazione unica per aziende in Italia ed Europa. Un solo referente, ovunque.'],
    'casting'          => ['title' => 'Casting Completo: Selezione e Logistica in Europa | TOAgency', 'desc' => 'Gestione completa del casting per produzioni in Italia ed Europa: selezione, contratti, misure certificate, logistica. Dal 2009.'],
    'talent-database'  => ['title' => 'Casting Attivi in Italia, Francia, Spagna, UK ed Europa | TOAgency', 'desc' => 'Esplora i casting aperti: modelli, hostess, attori in Italia, Francia, Spagna, UK e in tutta Europa. Candidati in 2 minuti.'],
    'about'            => ['title' => 'Chi Siamo: Agenzia Casting B2B Italia ed Europa | TOAgency', 'desc' => 'TOAgency: 15+ anni nel B2B casting, 20.000+ professionisti, 50+ città operative in Italia, Francia, Spagna, UK e in Europa.'],
    'collabora'        => ['title' => 'Lavora con Noi: Diventa Talent TOAgency | TOAgency', 'desc' => 'Vuoi entrare nel database TOAgency? Candidati come modello, hostess, attore per eventi in Italia ed Europa. Selezione professionale.'],
    'student-program'  => ['title' => 'Student Program: Studenti per Eventi e Fiere in Europa | TOAgency', 'desc' => 'Programma studenti TOAgency: lavoro flessibile per ragazzi over 18 in eventi B2B in Italia, Francia, Spagna, UK ed Europa.'],
    'contact-us'       => ['title' => 'Contatti — Preventivo Casting in 30 Minuti | TOAgency', 'desc' => 'Hai un evento in Italia o in Europa? Contatta TOAgency per un preventivo personalizzato in 30 minuti. Email, telefono, modulo online.'],
    'form-b2b'         => ['title' => 'Richiedi Preventivo Casting B2B Gratuito | TOAgency', 'desc' => 'Compila il form: ti rispondiamo in 30 minuti con un preventivo per modelli, hostess, attori per eventi in Italia ed Europa.'],
];

$backup = [];
$updated = 0;
$skipped = 0;

foreach ($pages as $slug => $data) {
    if ($slug === '__front__') {
        $post_id = (int) get_option('page_on_front');
        $label = 'HOMEPAGE';
    } else {
        // url_to_postid() risolve la pagina REALMENTE servita dall'URL, gestendo slug duplicati
        // (get_page_by_path ritornerebbe la più vecchia anche se WP serve un'altra)
        $post_id = (int) url_to_postid(home_url("/$slug/"));
        $label = "/$slug/";
        if (!$post_id) {
            echo "⚠️  /$slug/ NON TROVATA (url_to_postid=0) — SKIP\n";
            $skipped++;
            continue;
        }
    }

    if (!$post_id) {
        echo "⚠️  ID invalido per $label — SKIP\n";
        $skipped++;
        continue;
    }

    // BACKUP pre-existing meta
    $old_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
    $old_desc  = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
    $backup[$slug] = ['post_id' => $post_id, 'old_title' => $old_title, 'old_desc' => $old_desc];

    update_post_meta($post_id, '_yoast_wpseo_title', $data['title']);
    update_post_meta($post_id, '_yoast_wpseo_metadesc', $data['desc']);

    echo "✅ $label (ID $post_id)\n";
    echo "   title:    " . ($old_title !== $data['title'] ? "UPDATED (was: '" . substr($old_title, 0, 40) . "...')" : "unchanged") . "\n";
    echo "   metadesc: " . ($old_desc  !== $data['desc']  ? "UPDATED" : "unchanged") . "\n";
    $updated++;
}

// Salva backup su file
$backup_file = '/tmp/yoast-backup-pre-seed-it-' . date('Ymd-His') . '.json';
file_put_contents($backup_file, json_encode($backup, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "\n===================\n";
echo "✅ Updated: $updated\n";
echo "⚠️  Skipped: $skipped\n";
echo "📦 Backup: $backup_file\n";
echo "===================\n";
