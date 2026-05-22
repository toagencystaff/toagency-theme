<?php
/**
 * actions/registra-talent.php — Endpoint registrazione talent
 * v2.0 — 8 Maggio 2026
 * Path (in produzione): /public_html/crm_toagency/actions/registra-talent.php
 *
 * Riceve POST dal form public /collabora/ → /registrati-talent/.
 * Valida i dati, salva in talent_database.
 * NON esegue match con sistema legacy (deciso esplicitamente).
 *
 * Logica età:
 *   - 0-15:  genitore compila. Dati genitore obbligatori. stato='bozza'.
 *   - 16-17: talent compila. Checkbox conferma genitore obbligatorio. stato='bozza'.
 *   - 18+:   autonomo. stato='bozza'.
 *   - Nessun vincolo ruoli per età (check età/ruolo sarà nei casting).
 */

error_reporting(E_ALL);
ini_set('display_errors', 0);

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../lib/mysql.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'method_not_allowed']);
    exit;
}

$allowed_origins = ['https://toagency.it', 'https://www.toagency.it', 'https://staging25.toagency.it'];
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
if (in_array($origin, $allowed_origins, true)) {
    header("Access-Control-Allow-Origin: $origin");
}

// ─── Helper ──────────────────────────────────────────────────────────
function rt_clean($s) { return trim((string)$s); }
function rt_email($s) { return filter_var(trim((string)$s), FILTER_VALIDATE_EMAIL); }
function rt_int($s)   { return (int)$s; }
function rt_bool($s)  { return ($s === '1' || $s === 1 || $s === true || $s === 'true') ? 1 : 0; }
function rt_uuid_v4() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff),
        mt_rand(0,0x0fff)|0x4000, mt_rand(0,0x3fff)|0x8000,
        mt_rand(0,0xffff), mt_rand(0,0xffff), mt_rand(0,0xffff));
}
function rt_calc_age($dob) {
    if (!$dob) return 0;
    try {
        $b = new DateTime($dob); $t = new DateTime('today');
        return (int)$b->diff($t)->y;
    } catch (Throwable $e) { return 0; }
}
function rt_fail($code, $msg = '', $extra = []) {
    echo json_encode(array_merge(['ok'=>false,'error'=>$code,'message'=>$msg], $extra));
    exit;
}

// Liste codici ammessi (mirror del frontend)
$RUOLI_IMMAGINE_ALLOWED = ['model_f','model_m','actor_f','actor_m','hostess','steward','comparsa','bambino','influencer','altro_immagine'];
$RUOLI_BACKSTAGE_ALLOWED = ['fotografo','videomaker','makeup_artist','hairstylist_parrucchiere','stylist','fashion_designer','altro_backstage'];
$SESSO_ALLOWED  = ['F','M','altro'];
$OCCHI_ALLOWED  = ['azzurri','verdi','marroni','neri','grigi','altro'];
$CAPELLI_ALLOWED = ['biondi','castani','neri','rossi','grigi','altro'];
$LUNGHEZZA_ALLOWED = ['corto','medio','lungo'];
$ETNIA_ALLOWED  = ['caucasica','africana','asiatica','latina','araba','mista','altro'];
$TAGLIE_ALLOWED = ['XS','S','M','L','XL','XXL'];
$RELAZIONE_ALLOWED = ['genitore','tutore','altro'];

// ─── Honeypot ────────────────────────────────────────────────────────
if (!empty($_POST['honeypot_url'])) {
    // bot rilevato: rispondi ok finto, non scrivere su DB
    echo json_encode(['ok' => true, 'redirect' => null]);
    exit;
}

// ─── Raccolta dati ───────────────────────────────────────────────────
$nome     = rt_clean($_POST['nome'] ?? '');
$cognome  = rt_clean($_POST['cognome'] ?? '');
$email    = rt_email($_POST['email'] ?? '');
$telefono = rt_clean($_POST['telefono'] ?? '');
$tel_prefix = rt_clean($_POST['tel_paese_code'] ?? 'IT');
$dob      = rt_clean($_POST['data_nascita'] ?? '');
$sesso    = rt_clean($_POST['sesso'] ?? '');

$res_nation     = strtoupper(rt_clean($_POST['res_nation'] ?? ''));
$res_provincia  = rt_clean($_POST['res_provincia'] ?? '');
$res_city_name  = rt_clean($_POST['res_city_name'] ?? '');
$res_city_code  = rt_clean($_POST['res_city_code'] ?? '');

$dom_coincide   = rt_bool($_POST['dom_coincide'] ?? '1');
$dom_nation     = strtoupper(rt_clean($_POST['dom_nation'] ?? ''));
$dom_provincia  = rt_clean($_POST['dom_provincia'] ?? '');
$dom_city_name  = rt_clean($_POST['dom_city_name'] ?? '');
$dom_city_code  = rt_clean($_POST['dom_city_code'] ?? '');

$tipo_talent = rt_clean($_POST['tipo_talent'] ?? '');

$ruoli_imm_arr = isset($_POST['ruoli_immagine']) && is_array($_POST['ruoli_immagine'])
    ? array_map('rt_clean', $_POST['ruoli_immagine']) : [];
$ruoli_bks_arr = isset($_POST['ruoli_backstage']) && is_array($_POST['ruoli_backstage'])
    ? array_map('rt_clean', $_POST['ruoli_backstage']) : [];

// Caratteristiche fisiche (solo tipo=immagine)
$altezza = rt_int($_POST['altezza'] ?? 0);
$peso    = rt_clean($_POST['peso'] ?? '');
$taglia  = strtoupper(rt_clean($_POST['taglia'] ?? ''));
$scarpe  = rt_int($_POST['scarpe'] ?? 0);
$occhi   = rt_clean($_POST['occhi'] ?? '');
$capelli = rt_clean($_POST['capelli'] ?? '');
$lunghezza_capelli = rt_clean($_POST['lunghezza_capelli'] ?? '');
$etnia   = rt_clean($_POST['etnia'] ?? '');

// Misure (solo se sesso=F)
$misura_petto   = rt_int($_POST['misura_petto'] ?? 0);
$misura_vita    = rt_int($_POST['misura_vita'] ?? 0);
$misura_fianchi = rt_int($_POST['misura_fianchi'] ?? 0);

$instagram = rt_clean($_POST['instagram'] ?? '');
$tiktok    = rt_clean($_POST['tiktok'] ?? '');

// Genitore (se minore)
$genitore1_nome      = rt_clean($_POST['genitore1_nome'] ?? '');
$genitore1_email     = rt_email($_POST['genitore1_email'] ?? '');
$genitore1_telefono  = rt_clean($_POST['genitore1_telefono'] ?? '');
$genitore1_relazione = rt_clean($_POST['genitore1_relazione'] ?? 'genitore');

// Consensi
$gdpr_consent       = rt_bool($_POST['gdpr_consent'] ?? '0');
$pubblicazione_immagini_consent = rt_bool($_POST['pubblicazione_immagini_consent'] ?? '0');
$disclaimer_consent = rt_bool($_POST['disclaimer_consent'] ?? '0');
$parent_confirm_checkbox = rt_bool($_POST['parent_confirm'] ?? '0');

// IP utente (per audit consensi)
$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
if (strpos($user_ip, ',') !== false) $user_ip = trim(explode(',', $user_ip)[0]);
$user_ip = substr($user_ip, 0, 45);

$user_agent = substr(rt_clean($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255);
$lang = substr(rt_clean($_POST['lang'] ?? 'it'), 0, 5);

// ─── Validazioni base ────────────────────────────────────────────────
if (!$nome || !$cognome) rt_fail('missing_name', 'Nome e cognome obbligatori');
if (!$email)             rt_fail('invalid_email', 'Email non valida');
if (!$telefono)          rt_fail('missing_phone', 'Telefono obbligatorio');
if (!$dob)               rt_fail('missing_dob', 'Data nascita obbligatoria');
if (!in_array($sesso, $SESSO_ALLOWED, true)) rt_fail('missing_sesso', 'Sesso obbligatorio');

$age = rt_calc_age($dob);
if ($age <= 0)  rt_fail('invalid_dob', 'Data di nascita non valida');

// Tipo talent forzato a immagine (backstage → form crew separato)
$tipo_talent = 'immagine';

// Ruoli: accetta tutti senza vincoli di età
$all_ruoli_allowed = array_merge($RUOLI_IMMAGINE_ALLOWED, $RUOLI_BACKSTAGE_ALLOWED);
$ruoli_arr = array_values(array_intersect(
    array_merge($ruoli_imm_arr, $ruoli_bks_arr),
    $all_ruoli_allowed
));
if (empty($ruoli_arr)) rt_fail('missing_category', 'Seleziona almeno un ruolo');

// Geografia
if (!$res_nation)    rt_fail('missing_nation', 'Nazione obbligatoria');
if (!$res_city_name) rt_fail('missing_city', 'Città obbligatoria');
if ($res_nation === 'IT' && !$res_provincia) rt_fail('missing_province', 'Provincia obbligatoria per IT');
if (!$dom_coincide && (!$dom_nation || !$dom_city_name)) {
    rt_fail('missing_domicilio', 'Dati domicilio obbligatori');
}

// Consensi
if (!$gdpr_consent)       rt_fail('missing_gdpr', 'Devi accettare la privacy policy');
if (!$disclaimer_consent) rt_fail('missing_disclaimer', 'Devi accettare le regole di caricamento');

// Logica minorenne: 0-15 = genitore compila, 16-17 = checkbox conferma, 18+ = autonomo
$is_minore = ($age < 18);
$is_bambino = ($age < 16);

if ($is_bambino) {
    // 0-15: dati genitore obbligatori (genitore compila il form)
    if (!$genitore1_nome || !$genitore1_email || !$genitore1_telefono) {
        rt_fail('missing_parent_data', 'Dati genitore obbligatori per minori sotto i 16 anni');
    }
    if (!in_array($genitore1_relazione, $RELAZIONE_ALLOWED, true)) {
        $genitore1_relazione = 'genitore';
    }
} elseif ($is_minore) {
    // 16-17: checkbox conferma genitore obbligatorio
    if (!$parent_confirm_checkbox) {
        rt_fail('missing_parent_confirm', 'La conferma del genitore è obbligatoria per i minorenni 16-17');
    }
}

// Sanitize valori controllati
if (!in_array($occhi, $OCCHI_ALLOWED, true)) $occhi = '';
if (!in_array($capelli, $CAPELLI_ALLOWED, true)) $capelli = '';
if (!in_array($lunghezza_capelli, $LUNGHEZZA_ALLOWED, true)) $lunghezza_capelli = '';
if (!in_array($etnia, $ETNIA_ALLOWED, true)) $etnia = '';
if ($taglia && !in_array($taglia, $TAGLIE_ALLOWED, true)) $taglia = '';

// Misure corpo: facoltative per tutti (utili per modelli)

// ─── Email duplicata ─────────────────────────────────────────────────
$existing = dbQueryOne("SELECT id, stato_profilo FROM talent_database WHERE email = ? LIMIT 1", [$email]);
if ($existing) {
    rt_fail('email_exists', 'Email già registrata', ['existing_id' => (int)$existing['id']]);
}

// ─── Token + UUID ────────────────────────────────────────────────────
$uuid = rt_uuid_v4();
$token_profilo = bin2hex(random_bytes(32));

$stato_profilo = 'bozza'; // sempre bozza, niente più attesa_genitore
$now_iso = date('c'); // ISO 8601 con timezone

// ─── note_crm: JSON con consensi + meta registrazione ────────────────
$note_crm_obj = [
    'gdpr' => [
        'accepted' => true,
        'at' => $now_iso,
        'version' => 'v2',
        'ip' => $user_ip,
        'is_minor' => $is_minore,
        'minor_age_at_registration' => $is_minore ? $age : null,
    ],
    'img_pub' => [
        'accepted' => (bool)$pubblicazione_immagini_consent,
        'at' => $pubblicazione_immagini_consent ? $now_iso : null,
        'ip' => $pubblicazione_immagini_consent ? $user_ip : null,
        'version' => 'v2',
        'consent_by' => $is_bambino ? 'parent' : ($is_minore ? 'minor_with_parent_confirm' : 'self'),
    ],
    'disclaimer' => [
        'accepted' => true,
        'at' => $now_iso,
    ],
    'parent_consent' => $is_minore ? [
        'type' => $is_bambino ? 'full_parent_data' : 'parent_confirm_checkbox',
        'at' => $now_iso,
        'ip' => $user_ip,
        'parent_name' => $is_bambino ? $genitore1_nome : null,
        'parent_email' => $is_bambino ? $genitore1_email : null,
        'checkbox_checked' => (!$is_bambino && $is_minore) ? (bool)$parent_confirm_checkbox : null,
    ] : null,
    'registration_meta' => [
        'registered_by' => $is_bambino ? 'parent' : 'self',
        'age_bracket' => $is_bambino ? '0-15' : ($is_minore ? '16-17' : '18+'),
        'lang' => $lang,
        'user_agent' => $user_agent,
        'dom_coincide' => (bool)$dom_coincide,
        'dom_city_name' => $dom_coincide ? null : $dom_city_name,
        'dom_city_code' => $dom_coincide ? null : $dom_city_code,
        'dom_nation' => $dom_coincide ? null : $dom_nation,
        'dom_provincia' => $dom_coincide ? null : $dom_provincia,
        'res_city_code' => $res_city_code,
    ],
];
$note_crm_json = json_encode($note_crm_obj, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// Calcola talent_id nel range riservato 9000000+ (convenzione lib/talent_match.php)
$maxTid = dbQueryOne("SELECT COALESCE(MAX(talent_id), 9000000) AS m FROM talent_database WHERE talent_id >= 9000000");
$next_talent_id = (int)$maxTid['m'] + 1;

// ─── INSERT talent_database ──────────────────────────────────────────
$insert_data = [
    // Anagrafica
    'nome'         => $nome,
    'cognome'      => $cognome,
    'email'        => $email,
    'telefono'     => $telefono,
    'tel_prefix'   => $tel_prefix,
    'data_nascita' => $dob,
    'sesso'        => $sesso,
    'lingua'       => $lang,

    // Tipo + ruoli
    'tipo_talent'  => $tipo_talent,
    'ruoli'        => implode(',', $ruoli_arr),

    // Geografia residenza
    'paese_residenza'     => $res_nation,
    'provincia_residenza' => $res_provincia ?: null,
    'comune_residenza'    => $res_city_name,

    // Geografia domicilio (solo provincia + paese in colonne dedicate;
    // il dom_city_name/code è in note_crm.registration_meta)
    'paese_domicilio'     => $dom_coincide ? $res_nation : $dom_nation,
    'provincia_domicilio' => $dom_coincide ? ($res_provincia ?: null) : ($dom_provincia ?: null),

    // Caratteristiche fisiche (popolate solo per tipo=immagine)
    'altezza'           => $altezza > 0 ? $altezza : null,
    'peso'              => ($peso !== '' && is_numeric($peso)) ? (float)$peso : null,
    'taglia'            => $taglia ?: null,
    'scarpe'            => $scarpe > 0 ? $scarpe : null,
    'occhi'             => $occhi ?: null,
    'capelli'           => $capelli ?: null,
    'lunghezza_capelli' => $lunghezza_capelli ?: null,
    'etnia'             => $etnia ?: null,

    // Misure (solo se sesso=F)
    'misura_petto'   => $misura_petto > 0 ? $misura_petto : null,
    'misura_vita'    => $misura_vita > 0 ? $misura_vita : null,
    'misura_fianchi' => $misura_fianchi > 0 ? $misura_fianchi : null,

    // Social
    'instagram' => $instagram ?: null,
    'tiktok'    => $tiktok ?: null,

    // Stato profilo
    'stato_profilo'                => $stato_profilo,
    'visibile'                     => 1,
    'visibile_pubblico'            => 0, // staff lo attiva dopo verifica
    'valutazione'                  => 0,
    'dati_contrattuali_completi'   => 0,
    'eliminato'                    => 0,

    // Minorenne / genitore
    'minorenne'                => $is_minore ? 1 : 0,
    'genitore1_nome'           => $is_bambino ? $genitore1_nome : null,
    'genitore1_email'          => $is_bambino ? $genitore1_email : null,
    'genitore1_telefono'       => $is_bambino ? $genitore1_telefono : null,
    'genitore1_relazione'      => $is_bambino ? $genitore1_relazione : null,
    'genitore_token_hash'      => null,
    'genitore_token_expires'   => null,
    'genitore_verificato'      => 0,
    'consenso_genitore'        => ($is_bambino || ($is_minore && $parent_confirm_checkbox)) ? 1 : 0,

    // Token + UUID
    'uuid'                     => $uuid,
    'token_profilo'            => $token_profilo,
    'token_profilo_created_at' => date('Y-m-d H:i:s'),

    // Note CRM (consensi + meta)
    'note_crm' => $note_crm_json,

    'talent_id' => $next_talent_id,
];

try {
    $new_id = dbInsert('talent_database', $insert_data);
    if (!$new_id) rt_fail('insert_failed', 'Errore salvataggio profilo');
} catch (Throwable $e) {
    error_log('[registra-talent] insert error: '.$e->getMessage());
    rt_fail('insert_failed', 'Errore database: '.$e->getMessage());
}

// ─── Risposta JSON al client ─────────────────────────────────────────
echo json_encode([
    'ok'             => true,
    'talent_id'      => $next_talent_id,
    'uuid'           => $uuid,
    'token_profilo'  => $token_profilo,
    'is_minore'      => $is_minore,
    'stato_profilo'  => $stato_profilo,
    'tipo_talent'    => $tipo_talent,
]);
