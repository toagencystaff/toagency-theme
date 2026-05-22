<?php
/**
 * Seed Yoast SEO title + meta description — lingua ES
 * Auto-resolver WPML: parte dal slug IT, lookup trid, applica meta su post traduzione.
 * Usa url_to_postid() (gestisce slug IT duplicati).
 * Idempotente. Backup JSON automatico.
 */

$LANG_CODE = 'es';

$pages = [
    '__front__'        => ['title' => 'Agencia Casting B2B para Empresas en España y Europa | TOAgency', 'desc' => "¿Buscas modelos, azafatas o actores para un evento en España o Europa? TOAgency selecciona talento en 30 minutos. Presupuesto gratis."],
    'models'           => ['title' => 'Modelos para Empresas en España y en toda Europa | TOAgency', 'desc' => "Más de 5.000 modelos activos en España, Italia, Francia, UK y toda Europa. Selección en 48h para shootings, e-commerce, campañas."],
    'hostess-steward'  => ['title' => 'Azafatas y Personal para Eventos B2B en España y Europa | TOAgency', 'desc' => "Azafatas, personal y promotores para ferias y congresos en España y Europa. Contratos certificados, presupuesto en 30 min."],
    'actors'           => ['title' => 'Actores y Figurantes para Cine, TV, Spots — España y Europa | TOAgency', 'desc' => "Casting actores, figurantes y extras para cine, TV y publicidad en España y Europa. De 6 a 80 años, disponibles en 48h."],
    'visuals'          => ['title' => 'Fotógrafos y Videomakers B2B en España y Europa | TOAgency', 'desc' => "Servicios fotográficos y vídeo para marcas, e-commerce y campañas en España, Italia, Francia, UK y toda Europa."],
    'b2bservices'      => ['title' => 'Servicios B2B Casting y Talento para Empresas | TOAgency', 'desc' => "Selección casting, logística, contratos y facturación única para empresas en España y Europa. Un solo referente, en todas partes."],
    'casting'          => ['title' => 'Casting Completo: Selección y Logística en Europa | TOAgency', 'desc' => "Gestión completa del casting para producciones en España y Europa: selección, contratos, medidas certificadas, logística. Desde 2009."],
    'talent-database'  => ['title' => 'Castings Activos en España, Italia, Francia, UK y Europa | TOAgency', 'desc' => "Explora los castings abiertos: modelos, azafatas, actores en España, Italia, Francia, UK y en toda Europa. Candidatura en 2 minutos."],
    'about'            => ['title' => 'Quiénes Somos: Agencia Casting B2B España y Europa | TOAgency', 'desc' => "TOAgency: 15+ años en casting B2B, 20.000+ profesionales, 50+ ciudades operativas en España, Italia, Francia, UK y Europa."],
    'collabora'        => ['title' => 'Trabaja con Nosotros: Conviértete en Talento TOAgency | TOAgency', 'desc' => "¿Quieres entrar en la base TOAgency? Candidatura como modelo, azafata, actor para eventos en España y Europa."],
    'student-program'  => ['title' => 'Programa Estudiantes: Jóvenes para Eventos en Europa | TOAgency', 'desc' => "Programa estudiantes TOAgency: trabajo flexible para mayores de 18 en eventos B2B en España, Italia, Francia, UK y Europa."],
    'contact-us'       => ['title' => 'Contacto — Presupuesto Casting en 30 Minutos | TOAgency', 'desc' => "¿Tienes un evento en España o Europa? Contacta TOAgency para un presupuesto personalizado en 30 minutos."],
    'form-b2b'         => ['title' => 'Solicita un Presupuesto Casting B2B Gratuito | TOAgency', 'desc' => "Rellena el formulario: respondemos en 30 min con un presupuesto personalizado para modelos, azafatas, actores."],
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
