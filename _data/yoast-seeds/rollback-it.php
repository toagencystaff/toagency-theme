<?php
/**
 * Rollback Yoast seed IT — ripristina i meta pre-existing dal file JSON
 * Esecuzione: wp eval-file rollback-it.php -- <path-to-backup.json>
 */
$args = $args ?? [];
$backup_file = $args[0] ?? null;
if (!$backup_file || !file_exists($backup_file)) {
    echo "❌ Backup file non trovato. Uso: wp eval-file rollback-it.php -- /tmp/yoast-backup-pre-seed-it-YYYYMMDD-HHMMSS.json\n";
    return;
}
$backup = json_decode(file_get_contents($backup_file), true);
foreach ($backup as $slug => $data) {
    update_post_meta($data['post_id'], '_yoast_wpseo_title', $data['old_title']);
    update_post_meta($data['post_id'], '_yoast_wpseo_metadesc', $data['old_desc']);
    echo "♻️  $slug (ID {$data['post_id']}) ripristinato\n";
}
echo "\n✅ Rollback completato\n";
