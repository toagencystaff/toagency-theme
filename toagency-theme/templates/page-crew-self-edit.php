<?php
/**
 * Template Name: Crew Self-Edit
 * v1.0 — 2026-05-19 (S6)
 *
 * Pagina pubblica per il self-edit del crew.
 * URL: /crew-self-edit/?uuid={uuid}&t={token_profilo}
 *
 * Carica dati live da /crm_toagency/actions/crew-self-edit-load.php
 * Submit a /crm_toagency/actions/crew-self-edit-save.php
 *
 * Whitelist (S6 minimo): telefono, email, bio, instagram, tiktok, sito_web.
 * (Foto profilo: NON modificabile da self-edit in S6.)
 */

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, ['it','en','fr','es'], true)) $__l = 'it';
$_t = function ($a) use ($__l) { return $a[$__l] ?? $a['it']; };

$T = [
    'hero_eyebrow'   => ['it'=>'TOAGENCY/CREW','en'=>'TOAGENCY/CREW','fr'=>'TOAGENCY/CREW','es'=>'TOAGENCY/CREW'],
    'hero_title'     => ['it'=>'Aggiorna i tuoi dati','en'=>'Update your info','fr'=>'Mets à jour tes infos','es'=>'Actualiza tus datos'],
    'hero_subtitle'  => [
        'it'=>'Le modifiche saranno revisionate dal nostro staff prima di andare live.',
        'en'=>'Changes will be reviewed by our staff before going live.',
        'fr'=>'Les modifications seront revues avant publication.',
        'es'=>'Los cambios serán revisados antes de publicarse.',
    ],
    'loading'        => ['it'=>'Caricamento…','en'=>'Loading…','fr'=>'Chargement…','es'=>'Cargando…'],
    'invalid_link'   => ['it'=>'Link non valido o scaduto.','en'=>'Invalid or expired link.','fr'=>'Lien invalide.','es'=>'Enlace inválido.'],
    'pending_msg'    => [
        'it'=>'⏳ Hai già inviato modifiche in attesa di revisione. Ulteriori modifiche le sostituiranno.',
        'en'=>'⏳ You have pending changes waiting for review. New changes will replace them.',
        'fr'=>'⏳ Modifications en attente.',
        'es'=>'⏳ Modificaciones pendientes.',
    ],
    'field_telefono' => ['it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono'],
    'field_email'    => ['it'=>'Email','en'=>'Email','fr'=>'Email','es'=>'Email'],
    'field_bio'      => ['it'=>'Bio','en'=>'Bio','fr'=>'Bio','es'=>'Bio'],
    'field_instagram'=> ['it'=>'Instagram','en'=>'Instagram','fr'=>'Instagram','es'=>'Instagram'],
    'field_tiktok'   => ['it'=>'TikTok','en'=>'TikTok','fr'=>'TikTok','es'=>'TikTok'],
    'field_sito_web' => ['it'=>'Sito web','en'=>'Website','fr'=>'Site web','es'=>'Sitio web'],
    'btn_save'       => ['it'=>'Invia modifiche','en'=>'Submit changes','fr'=>'Envoyer','es'=>'Enviar'],
    'btn_saving'     => ['it'=>'Invio in corso…','en'=>'Sending…','fr'=>'Envoi…','es'=>'Enviando…'],
    'success_msg'    => [
        'it'=>'✓ Modifiche inviate. Lo staff le revisiona entro 24h.',
        'en'=>'✓ Changes submitted. Staff will review within 24h.',
        'fr'=>'✓ Modifications envoyées.',
        'es'=>'✓ Cambios enviados.',
    ],
    'no_changes'     => ['it'=>'Nessuna modifica rilevata.','en'=>'No changes detected.','fr'=>'Aucune modification.','es'=>'Sin cambios.'],
    'error_generic'  => ['it'=>'Errore: ','en'=>'Error: ','fr'=>'Erreur: ','es'=>'Error: '],
    'bio_hint'       => ['it'=>'Max 2000 caratteri.','en'=>'Max 2000 characters.','fr'=>'Max 2000.','es'=>'Máx 2000.'],

    // S6.5-LEGAL — disclaimer foto
    'legal_summary'  => ['it'=>'📋 Leggi disclaimer legale','en'=>'📋 Read legal disclaimer','fr'=>'📋 Lire avis légal','es'=>'📋 Leer aviso legal'],
    'legal_text'     => [
        'it' => "Caricando la foto dichiari sotto la tua responsabilità che:\n\n"
              . "1. SEI IL SOGGETTO RITRATTO o sei autorizzato da chi è ritratto a usarne l'immagine.\n\n"
              . "2. SEI L'AUTORE DELLA FOTO o hai una licenza/autorizzazione valida per usarla. La foto non viola diritti d'autore di terzi.\n\n"
              . "3. NON SONO PRESENTI WATERMARK, firme, loghi, contatti, marchi di altre agenzie o riferimenti che identifichino te o l'autore, in conformità con le linee guida del database TOAgency.\n\n"
              . "4. AUTORIZZI TOAgency a pubblicare la foto sui propri canali ufficiali (sito web, presentazioni a clienti business, materiale promozionale) per finalità di promozione professionale del tuo profilo crew, ai sensi della Legge 633/1941 artt. 96-97 e del GDPR Reg. UE 2016/679 artt. 6-7.\n\n"
              . "5. TRATTAMENTO DATI: i dati e l'immagine saranno trattati esclusivamente per la gestione del profilo crew e la presentazione a clienti aziendali. Maggiori info nella Privacy Policy.\n\n"
              . "6. PUOI REVOCARE questo consenso in qualsiasi momento scrivendo a info@toagency.it (art. 17 GDPR — diritto all'oblio). La rimozione avverrà entro 30 giorni dalla richiesta.",
        'en' => "By uploading the photo you declare under your responsibility that:\n\n"
              . "1. YOU ARE THE SUBJECT shown or you are authorized by the person depicted to use the image.\n\n"
              . "2. YOU ARE THE AUTHOR of the photo or hold a valid license/authorization. The photo does not infringe third-party copyrights.\n\n"
              . "3. NO WATERMARKS, signatures, logos, contacts, other agency marks or identifying references are present, per TOAgency database guidelines.\n\n"
              . "4. YOU AUTHORIZE TOAgency to publish the photo on its official channels (website, business client presentations, promotional materials) for professional crew profile promotion, under Italian Law 633/1941 art. 96-97 and GDPR Reg. EU 2016/679 art. 6-7.\n\n"
              . "5. DATA PROCESSING: data and image will be used only for crew profile management and presentation to corporate clients. See the Privacy Policy.\n\n"
              . "6. YOU MAY REVOKE this consent any time by writing to info@toagency.it (GDPR Art. 17 — right to erasure). Removal within 30 days of request.",
        'fr' => "En téléchargeant la photo, tu déclares sous ta responsabilité que :\n\n"
              . "1. TU ES LE SUJET représenté ou tu es autorisé par la personne représentée à utiliser l'image.\n\n"
              . "2. TU ES L'AUTEUR de la photo ou tu disposes d'une licence/autorisation valide. La photo ne viole aucun droit d'auteur tiers.\n\n"
              . "3. AUCUN FILIGRANE, signature, logo, contact, marque d'autre agence ou référence identifiante n'est présent (lignes directrices du database TOAgency).\n\n"
              . "4. TU AUTORISES TOAgency à publier la photo sur ses canaux officiels (site web, présentations aux clients business, matériel promotionnel) pour la promotion professionnelle du profil crew, conformément à la Loi italienne 633/1941 art. 96-97 et au RGPD UE 2016/679 art. 6-7.\n\n"
              . "5. TRAITEMENT DES DONNÉES : usage exclusif pour la gestion du profil crew et la présentation aux clients corporate. Voir la Politique de confidentialité.\n\n"
              . "6. TU PEUX RÉVOQUER ce consentement à tout moment via info@toagency.it (Art. 17 RGPD — droit à l'oubli). Retrait dans 30 jours.",
        'es' => "Al subir la foto declaras bajo tu responsabilidad que:\n\n"
              . "1. ERES EL SUJETO retratado o estás autorizado por la persona retratada a usar la imagen.\n\n"
              . "2. ERES EL AUTOR de la foto o tienes licencia/autorización válida. La foto no viola derechos de autor de terceros.\n\n"
              . "3. NO HAY MARCAS DE AGUA, firmas, logotipos, contactos, marcas de otras agencias ni referencias identificativas (directrices del database TOAgency).\n\n"
              . "4. AUTORIZAS a TOAgency a publicar la foto en sus canales oficiales (sitio web, presentaciones a clientes corporativos, material promocional) para la promoción profesional del perfil crew, según la Ley italiana 633/1941 art. 96-97 y RGPD UE 2016/679 art. 6-7.\n\n"
              . "5. TRATAMIENTO DE DATOS: uso exclusivo para gestión del perfil crew y presentación a clientes corporativos. Consulta la Política de Privacidad.\n\n"
              . "6. PUEDES REVOCAR este consentimiento escribiendo a info@toagency.it (Art. 17 RGPD — derecho al olvido). Retirada en 30 días.",
    ],
    'legal_consent'  => ['it'=>'Accetto il disclaimer legale qui sopra','en'=>'I accept the legal disclaimer above','fr'=>'J\'accepte l\'avis légal ci-dessus','es'=>'Acepto el aviso legal anterior'],
    'verita_consent' => ['it'=>'Confermo che questa foto rappresenta il mio aspetto attuale','en'=>'I confirm this photo represents my current appearance','fr'=>'Je confirme que cette photo représente mon apparence actuelle','es'=>'Confirmo que esta foto representa mi apariencia actual'],
    'pending_foto'   => [
        'it'=>'⏳ Hai una foto in attesa di approvazione (caricata il %s). Una nuova foto sostituirà quella in attesa.',
        'en'=>'⏳ A photo is pending approval (uploaded %s). A new upload will replace it.',
        'fr'=>'⏳ Une photo est en attente d\'approbation (%s).',
        'es'=>'⏳ Foto pendiente de aprobación (%s).',
    ],
];

$theme_uri = get_stylesheet_directory_uri();
$uuid_get  = $_GET['uuid'] ?? '';
$token_get = $_GET['t']    ?? '';
?>

<style>
.crew-edit-wrap { background:#0a0a0a; color:#fff; min-height:100vh; font-family:'DM Sans','Inter',sans-serif; padding-bottom:80px; }
.crew-edit-hero { padding:48px 24px 24px; text-align:center; border-bottom:1px solid #2a2a2e; }
.crew-edit-hero-eyebrow { color:#c8ff00; font-size:12px; letter-spacing:2px; font-weight:600; margin-bottom:8px; }
.crew-edit-hero-title { font-size:38px; font-weight:800; color:#fff; margin:0; letter-spacing:-0.5px; }
.crew-edit-hero-subtitle { color:#9ca3af; margin-top:10px; max-width:560px; margin-left:auto; margin-right:auto; line-height:1.5; font-size:14px; }
.crew-edit-uuid { font-family:monospace; font-size:12px; color:#6b7280; margin-top:6px; }

.crew-edit-container { max-width:560px; margin:32px auto; padding:0 20px; }
.crew-edit-status { text-align:center; padding:60px 20px; color:#9ca3af; }
.crew-edit-status.error { color:#ef4444; }

.crew-edit-pending-notice { background:rgba(200,255,0,.10); border:1px solid #c8ff00; color:#c8ff00; padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:24px; }

.crew-edit-form { display:none; }
.crew-edit-form.visible { display:block; }
.crew-edit-field { margin-bottom:18px; }
.crew-edit-label { display:block; font-size:11px; color:#9ca3af; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
.crew-edit-input, .crew-edit-textarea { width:100%; background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:11px 13px; border-radius:6px; font-size:14px; font-family:inherit; box-sizing:border-box; transition:border-color .15s; }
.crew-edit-input:focus, .crew-edit-textarea:focus { outline:none; border-color:#c8ff00; }
.crew-edit-textarea { resize:vertical; min-height:90px; }
.crew-edit-hint { font-size:11px; color:#6b7280; margin-top:4px; }
.crew-edit-readonly-name { background:#1a1a1e; border:1px solid #2a2a2e; padding:10px 13px; border-radius:6px; color:#9ca3af; font-size:13px; margin-bottom:12px; }
.crew-edit-readonly-name strong { color:#fff; }

/* S6.5-LEGAL — Disclaimer foto */
.crew-edit-legal-disclaimer { margin:12px 0; background:#1a1a1e; border:1px solid #2a2a2e; border-radius:6px; padding:12px; text-align:left; }
.crew-edit-legal-disclaimer summary { cursor:pointer; color:#c8ff00; font-weight:600; font-size:13px; outline:none; user-select:none; }
.crew-edit-legal-disclaimer summary:hover { color:#dfff66; }
.crew-edit-legal-text { margin-top:12px; font-size:12px; line-height:1.6; color:#d1d5db; max-height:220px; overflow-y:auto; padding:6px 4px; }
.crew-edit-legal-checkbox { display:flex; gap:10px; align-items:flex-start; margin:10px 0; font-size:13px; color:#d1d5db; cursor:pointer; text-align:left; line-height:1.45; }
.crew-edit-legal-checkbox input[type="checkbox"] { margin-top:3px; flex-shrink:0; transform:scale(1.2); cursor:pointer; }
.crew-edit-foto-pending-notice { margin-bottom:10px; padding:8px 10px; background:rgba(200,255,0,.08); border:1px solid rgba(200,255,0,.3); color:#c8ff00; border-radius:6px; font-size:12px; text-align:left; line-height:1.4; }
@media (max-width:520px) { .crew-edit-legal-text { max-height:160px; } }

/* S6.5 — Foto profilo */
.crew-edit-foto-field { margin-bottom:24px; text-align:center; padding:16px; background:#1a1a1e; border:1px solid #2a2a2e; border-radius:8px; }
.crew-edit-foto-label { display:block; font-size:11px; color:#9ca3af; margin-bottom:12px; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
.crew-edit-foto-preview-wrap { width:160px; height:160px; border-radius:50%; overflow:hidden; background:#0a0a0a; margin:0 auto 12px; position:relative; border:2px solid #2a2a2e; }
.crew-edit-foto-preview-wrap img { width:100%; height:100%; object-fit:cover; }
.crew-edit-foto-placeholder { display:flex; align-items:center; justify-content:center; height:100%; width:100%; font-size:60px; color:#3a3a3e; }
.crew-edit-foto-btn { background:#c8ff00; color:#0a0a0a; padding:10px 20px; border-radius:6px; border:none; cursor:pointer; font-weight:700; font-size:13px; transition:opacity .15s; }
.crew-edit-foto-btn:hover { opacity:.9; }
.crew-edit-foto-btn:disabled { opacity:.5; cursor:not-allowed; }
.crew-edit-foto-hint { font-size:11px; color:#6b7280; margin-top:8px; line-height:1.4; }
.crew-edit-foto-status { font-size:13px; margin-top:10px; min-height:20px; }
.crew-edit-foto-status.ok { color:#c8ff00; }
.crew-edit-foto-status.err { color:#ef4444; }
.crew-edit-foto-status.loading { color:#9ca3af; }
@media (max-width:520px) {
    .crew-edit-foto-preview-wrap { width:130px; height:130px; }
    .crew-edit-foto-placeholder { font-size:48px; }
}

.crew-edit-actions { margin-top:28px; }
.crew-edit-btn-save { width:100%; background:#c8ff00; color:#0a0a0a; border:none; padding:14px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; transition:opacity .15s; }
.crew-edit-btn-save:hover { opacity:.9; }
.crew-edit-btn-save:disabled { opacity:.5; cursor:not-allowed; }

.crew-edit-result { margin-top:18px; padding:14px; border-radius:8px; font-size:14px; text-align:center; }
.crew-edit-result.ok  { background:rgba(200,255,0,.15); color:#c8ff00; border:1px solid rgba(200,255,0,.3); }
.crew-edit-result.err { background:rgba(239,68,68,.15); color:#ef4444; border:1px solid rgba(239,68,68,.3); }

@media (max-width:520px) {
    .crew-edit-hero-title { font-size:28px; }
    .crew-edit-container { padding:0 16px; margin-top:20px; }
}
</style>

<section class="crew-edit-wrap">
    <header class="crew-edit-hero">
        <div class="crew-edit-hero-eyebrow"><?= esc_html($_t($T['hero_eyebrow'])) ?></div>
        <h1 class="crew-edit-hero-title"><?= esc_html($_t($T['hero_title'])) ?></h1>
        <p class="crew-edit-hero-subtitle"><?= esc_html($_t($T['hero_subtitle'])) ?></p>
        <div class="crew-edit-uuid" id="crew-uuid-display"></div>
    </header>

    <div class="crew-edit-container">
        <div id="crew-edit-status" class="crew-edit-status"><?= esc_html($_t($T['loading'])) ?></div>

        <div id="crew-edit-pending" class="crew-edit-pending-notice" style="display:none;"></div>

        <form id="crew-edit-form" class="crew-edit-form" autocomplete="on">
            <div class="crew-edit-readonly-name" id="crew-edit-name-display"></div>

            <!-- S6.5 — Foto profilo upload + S6.5-LEGAL disclaimer -->
            <div class="crew-edit-foto-field">
                <label class="crew-edit-foto-label">📷 Foto profilo</label>
                <div class="crew-edit-foto-preview-wrap">
                    <img id="f-foto-preview" src="" alt="Foto profilo" style="display:none;">
                    <div id="f-foto-placeholder" class="crew-edit-foto-placeholder">👤</div>
                </div>
                <div id="f-foto-pending-notice" class="crew-edit-foto-pending-notice" style="display:none;"></div>

                <details class="crew-edit-legal-disclaimer">
                    <summary><?= esc_html($_t($T['legal_summary'])) ?></summary>
                    <div class="crew-edit-legal-text"><?= nl2br(esc_html($_t($T['legal_text']))) ?></div>
                </details>
                <label class="crew-edit-legal-checkbox">
                    <input type="checkbox" id="f-legal" required>
                    <span><?= esc_html($_t($T['legal_consent'])) ?></span>
                </label>
                <label class="crew-edit-legal-checkbox">
                    <input type="checkbox" id="f-verita" required>
                    <span><?= esc_html($_t($T['verita_consent'])) ?></span>
                </label>

                <input type="file" id="f-foto-input" accept="image/jpeg,image/png,image/webp" style="display:none;">
                <button type="button" id="f-foto-btn" class="crew-edit-foto-btn">📤 Carica/Cambia foto</button>
                <div class="crew-edit-foto-hint">Max 20MB. JPG, PNG o WebP.<br>Ridimensionata automaticamente (max 1600px lato lungo). Approvazione staff entro 24h.</div>
                <div class="crew-edit-foto-status" id="f-foto-status"></div>
            </div>

            <div class="crew-edit-field">
                <label class="crew-edit-label"><?= esc_html($_t($T['field_telefono'])) ?></label>
                <input type="tel" id="f-telefono" class="crew-edit-input" autocomplete="tel">
            </div>
            <div class="crew-edit-field">
                <label class="crew-edit-label"><?= esc_html($_t($T['field_email'])) ?></label>
                <input type="email" id="f-email" class="crew-edit-input" autocomplete="email">
            </div>
            <div class="crew-edit-field">
                <label class="crew-edit-label"><?= esc_html($_t($T['field_bio'])) ?></label>
                <textarea id="f-bio" class="crew-edit-textarea" rows="4" maxlength="2000"></textarea>
                <div class="crew-edit-hint"><?= esc_html($_t($T['bio_hint'])) ?></div>
            </div>
            <div class="crew-edit-field">
                <label class="crew-edit-label"><?= esc_html($_t($T['field_instagram'])) ?></label>
                <input type="text" id="f-instagram" class="crew-edit-input" placeholder="@username" maxlength="100">
            </div>
            <div class="crew-edit-field">
                <label class="crew-edit-label"><?= esc_html($_t($T['field_tiktok'])) ?></label>
                <input type="text" id="f-tiktok" class="crew-edit-input" placeholder="@username" maxlength="100">
            </div>
            <div class="crew-edit-field">
                <label class="crew-edit-label"><?= esc_html($_t($T['field_sito_web'])) ?></label>
                <input type="url" id="f-sito_web" class="crew-edit-input" placeholder="https://..." maxlength="500">
            </div>

            <!-- FIX 2026-07-01 marco — geo crew self-edit: comune (ricerca) + provincia (tendina), no testo libero -->
            <div class="crew-edit-field" style="position:relative;">
                <label class="crew-edit-label"><?= esc_html($_t(['it'=>'Comune / Città','en'=>'City','fr'=>'Ville','es'=>'Ciudad'])) ?></label>
                <input type="text" id="f-comune_search" class="crew-edit-input" placeholder="<?= esc_attr($_t(['it'=>'Scrivi e scegli dalla lista…','en'=>'Type and pick from the list…','fr'=>'Écrivez et choisissez…','es'=>'Escribe y elige…'])) ?>" maxlength="100" autocomplete="off">
                <input type="hidden" id="f-comune_residenza">
                <div id="f-comune_dropdown" style="display:none;position:absolute;left:0;right:0;top:100%;z-index:60;background:#141418;border:1px solid #333;border-radius:8px;margin-top:4px;max-height:240px;overflow:auto;box-shadow:0 8px 24px rgba(0,0,0,.4);"></div>
            </div>
            <div class="crew-edit-field">
                <label class="crew-edit-label"><?= esc_html($_t(['it'=>'Provincia','en'=>'Province / County','fr'=>'Province','es'=>'Provincia'])) ?></label>
                <select id="f-provincia_domicilio" class="crew-edit-input">
                    <option value=""><?= esc_html($_t(['it'=>'Seleziona provincia','en'=>'Select province','fr'=>'Choisir la province','es'=>'Seleccionar provincia'])) ?></option>
                </select>
            </div>

            <!-- Honeypot anti-spam -->
            <div style="position:absolute;left:-9999px;opacity:0;" aria-hidden="true">
                <label>Non compilare<input type="text" id="f-honeypot" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="crew-edit-actions">
                <button type="button" id="btn-save" class="crew-edit-btn-save" onclick="crewEditSubmit()"><?= esc_html($_t($T['btn_save'])) ?></button>
            </div>

            <div id="crew-edit-result"></div>
        </form>
    </div>
</section>

<script>
window.crewEditConfig = {
    apiLoad:        '/crm_toagency/actions/crew-self-edit-load.php',
    apiSave:        '/crm_toagency/actions/crew-self-edit-save.php',
    apiUploadFoto:  '/crm_toagency/actions/crew-upload-foto-profilo.php',
    provinceJsonUrl: <?= json_encode($theme_uri . '/assets/data/province-italia.json') ?>, /* FIX 2026-07-01 marco — tendina provincia crew */
    comuneApiUrl:   '/crm_toagency/actions/cerca-comune.php', /* FIX 2026-07-01 marco — ricerca comune crew */
    pendingFotoTpl: <?= json_encode($_t($T['pending_foto'])) ?>,
    uuid:    <?= json_encode($uuid_get) ?>,
    token:   <?= json_encode($token_get) ?>,
    strings: {
        invalidLink:  <?= json_encode($_t($T['invalid_link'])) ?>,
        pending:      <?= json_encode($_t($T['pending_msg'])) ?>,
        saving:       <?= json_encode($_t($T['btn_saving'])) ?>,
        save:         <?= json_encode($_t($T['btn_save'])) ?>,
        successMsg:   <?= json_encode($_t($T['success_msg'])) ?>,
        noChanges:    <?= json_encode($_t($T['no_changes'])) ?>,
        errorPrefix:  <?= json_encode($_t($T['error_generic'])) ?>,
        fotoOk:       <?= json_encode($_t(['it'=>'Foto caricata, in attesa di approvazione','en'=>'Photo uploaded, awaiting staff approval','fr'=>'Photo envoyée, en attente de validation','es'=>'Foto subida, pendiente de aprobación'])) ?>,
        fotoErr:      <?= json_encode($_t(['it'=>'Errore nel caricamento della foto','en'=>'Photo upload error','fr'=>'Erreur lors de l’envoi de la photo','es'=>'Error al subir la foto'])) ?>,
    }
};
</script>
<script src="<?= esc_url($theme_uri . '/assets/crew-self-edit.js') ?>?v=20260711i18n" defer></script>

<?php toa_component('footer'); ?>
