<?php
/**
 * Template Name: Completa Profilo
 * v1.0 — 2026-06-03 marco (Step 2B — Fase 2 registrazione talent)
 *
 * Pagina pubblica per completare un profilo talent creato in Fase 1 (candidatura).
 * URL: /completa-profilo/?uuid={uuid}&t={token_profilo}
 *
 * SOLO ramo talent (modello/attore/hostess/creator). I crew restano sul flusso
 * candidatura-crew → crew_database.
 *
 * Carica via /crm_toagency/actions/completa-profilo-load.php (prefill + barra avanzamento),
 * salva LIVE via completa-profilo-save.php (attiva il profilo: stato_profilo='attivo').
 */

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, ['it','en','fr','es'], true)) $__l = 'it';
$_t = function ($a) use ($__l) { return $a[$__l] ?? $a['it']; };

$T = [
    'hero_eyebrow'  => ['it'=>'TOAGENCY/TALENT','en'=>'TOAGENCY/TALENT','fr'=>'TOAGENCY/TALENT','es'=>'TOAGENCY/TALENT'],
    'hero_title'    => ['it'=>'Completa il tuo profilo','en'=>'Complete your profile','fr'=>'Complète ton profil','es'=>'Completa tu perfil'],
    'hero_subtitle' => [
        'it'=>'Completa il tuo profilo per essere proposto ai casting.',
        'en'=>'Complete your profile to be proposed for castings.',
        'fr'=>'Complète ton profil pour être proposé aux castings.',
        'es'=>'Completa tu perfil para ser propuesto a los castings.',
    ],
    'loading'      => ['it'=>'Caricamento…','en'=>'Loading…','fr'=>'Chargement…','es'=>'Cargando…'],
    'invalid_link' => ['it'=>'Link non valido o scaduto.','en'=>'Invalid or expired link.','fr'=>'Lien invalide ou expiré.','es'=>'Enlace inválido o caducado.'],
    'already_done' => [
        'it'=>'✓ Il tuo profilo è già completo. Puoi aggiornarlo qui sotto.',
        'en'=>'✓ Your profile is already complete. You can update it below.',
        'fr'=>'✓ Ton profil est déjà complet. Tu peux le mettre à jour ci-dessous.',
        'es'=>'✓ Tu perfil ya está completo. Puedes actualizarlo abajo.',
    ],
    'progress_label' => ['it'=>'Completamento profilo','en'=>'Profile completion','fr'=>'Complétion du profil','es'=>'Completado del perfil'],
    'section_misure'  => ['it'=>'Misure & corporatura','en'=>'Measurements','fr'=>'Mensurations','es'=>'Medidas'],
    'section_aspetto' => ['it'=>'Aspetto','en'=>'Appearance','fr'=>'Apparence','es'=>'Apariencia'],
    'section_profilo' => ['it'=>'Profilo professionale','en'=>'Professional profile','fr'=>'Profil professionnel','es'=>'Perfil profesional'],
    'section_social'  => ['it'=>'Social (opzionale)','en'=>'Social (optional)','fr'=>'Réseaux (optionnel)','es'=>'Redes (opcional)'],
    'field_sesso'     => ['it'=>'Sesso','en'=>'Gender','fr'=>'Sexe','es'=>'Sexo'],
    'field_altezza'   => ['it'=>'Altezza (cm) *','en'=>'Height (cm) *','fr'=>'Taille (cm) *','es'=>'Altura (cm) *'],
    'field_taglia'    => ['it'=>'Taglia *','en'=>'Size *','fr'=>'Taille *','es'=>'Talla *'],
    'field_scarpe'    => ['it'=>'Numero scarpe','en'=>'Shoe size','fr'=>'Pointure','es'=>'Calzado'],
    'field_peso'      => ['it'=>'Peso (kg)','en'=>'Weight (kg)','fr'=>'Poids (kg)','es'=>'Peso (kg)'],
    'field_capelli'   => ['it'=>'Colore capelli *','en'=>'Hair color *','fr'=>'Cheveux *','es'=>'Cabello *'],
    'field_lunghezza' => ['it'=>'Lunghezza capelli','en'=>'Hair length','fr'=>'Longueur cheveux','es'=>'Largo del cabello'],
    'field_occhi'     => ['it'=>'Colore occhi *','en'=>'Eye color *','fr'=>'Yeux *','es'=>'Ojos *'],
    'field_etnia'     => ['it'=>'Etnia * (max 2)','en'=>'Ethnicity * (max 2)','fr'=>'Ethnie * (max 2)','es'=>'Etnia * (máx 2)'],
    'field_ruoli'     => ['it'=>'Ruoli * (uno o più)','en'=>'Roles * (one or more)','fr'=>'Rôles * (un ou plusieurs)','es'=>'Roles * (uno o más)'],
    'field_lingue'    => ['it'=>'Lingue parlate','en'=>'Spoken languages','fr'=>'Langues parlées','es'=>'Idiomas'],
    'field_patente'   => ['it'=>'Ho la patente di guida','en'=>'I have a driving license','fr'=>'J\'ai le permis de conduire','es'=>'Tengo carné de conducir'],
    'field_instagram' => ['it'=>'Instagram','en'=>'Instagram','fr'=>'Instagram','es'=>'Instagram'],
    'field_tiktok'    => ['it'=>'TikTok','en'=>'TikTok','fr'=>'TikTok','es'=>'TikTok'],
    'opt_select'      => ['it'=>'—','en'=>'—','fr'=>'—','es'=>'—'],
    'btn_save'        => ['it'=>'Completa e attiva profilo','en'=>'Complete & activate profile','fr'=>'Compléter et activer','es'=>'Completar y activar'],
    'btn_saving'      => ['it'=>'Salvataggio…','en'=>'Saving…','fr'=>'Enregistrement…','es'=>'Guardando…'],
    'success_msg'     => [
        'it'=>'🎉 Profilo completato! Ora sei proponibile ai casting.',
        'en'=>'🎉 Profile completed! You can now be proposed for castings.',
        'fr'=>'🎉 Profil complété ! Tu peux désormais être proposé aux castings.',
        'es'=>'🎉 ¡Perfil completado! Ya puedes ser propuesto a los castings.',
    ],
    'err_required' => [
        'it'=>'Compila i campi obbligatori (*): altezza, taglia, capelli, occhi, etnia e almeno un ruolo.',
        'en'=>'Fill the required fields (*): height, size, hair, eyes, ethnicity and at least one role.',
        'fr'=>'Remplis les champs obligatoires (*).','es'=>'Completa los campos obligatorios (*).',
    ],
    'error_prefix' => ['it'=>'Errore: ','en'=>'Error: ','fr'=>'Erreur: ','es'=>'Error: '],
    'etnia_max'    => ['it'=>'Massimo 2 etnie.','en'=>'Max 2 ethnicities.','fr'=>'Max 2 ethnies.','es'=>'Máx 2 etnias.'],
];

// ── Enum (allineati a actions/registra-talent.php e completa-profilo-save.php). Label IT (fallback). ──
$SESSO_OPTS    = ['F'=>'Femmina','M'=>'Maschio','altro'=>'Altro'];
$TAGLIE_OPTS   = ['XS','S','M','L','XL','XXL'];
$CAPELLI_OPTS  = ['biondi'=>'Biondi','castani'=>'Castani','neri'=>'Neri','rossi'=>'Rossi','grigi'=>'Grigi','bianchi'=>'Bianchi','calvo'=>'Calvo','altro'=>'Altro'];
$LUNGH_OPTS    = ['corto'=>'Corto','medio'=>'Medio','lungo'=>'Lungo'];
$OCCHI_OPTS    = ['azzurri'=>'Azzurri','verdi'=>'Verdi','marroni'=>'Marroni','neri'=>'Neri','grigi'=>'Grigi','altro'=>'Altro'];
$ETNIA_OPTS    = ['caucasica'=>'Caucasica','africana'=>'Africana','asiatica'=>'Asiatica','sud_asiatica'=>'Sud-asiatica','latina'=>'Latina','araba'=>'Araba'];
// FIX 2026-06-04 marco — ruoli unificati (no coppie gendered): il sesso determina il token salvato lato server
$RUOLI_OPTS    = [
    'model'           => ['it'=>'Modello/a','en'=>'Model','fr'=>'Mannequin','es'=>'Modelo/a'],
    'actor'           => ['it'=>'Attore/trice','en'=>'Actor/Actress','fr'=>'Acteur/trice','es'=>'Actor/Actriz'],
    'hostess_steward' => ['it'=>'Hostess / Steward','en'=>'Hostess / Steward','fr'=>'Hôtesse / Steward','es'=>'Azafata / Azafato'],
    'comparsa'        => ['it'=>'Comparsa','en'=>'Extra','fr'=>'Figurant','es'=>'Extra'],
    'bambino'         => ['it'=>'Bambino/a','en'=>'Child','fr'=>'Enfant','es'=>'Niño/a'],
    'influencer'      => ['it'=>'Influencer / Creator','en'=>'Influencer / Creator','fr'=>'Influenceur / Créateur','es'=>'Influencer / Creador'],
    'altro_immagine'  => ['it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro'],
];
$LINGUE_OPTS   = ['italiano'=>'Italiano','inglese'=>'Inglese','francese'=>'Francese','spagnolo'=>'Spagnolo','tedesco'=>'Tedesco','portoghese'=>'Portoghese','russo'=>'Russo','cinese'=>'Cinese','arabo'=>'Arabo','altro'=>'Altro'];

$theme_uri = get_stylesheet_directory_uri();
$uuid_get  = $_GET['uuid'] ?? '';
$token_get = $_GET['t']    ?? '';
?>

<style>
.cp-wrap { background:#0a0a0a; color:#fff; min-height:100vh; font-family:'DM Sans','Inter',sans-serif; padding-bottom:80px; }
.cp-hero { padding:48px 24px 24px; text-align:center; border-bottom:1px solid #2a2a2e; }
.cp-hero-eyebrow { color:#c8ff00; font-size:12px; letter-spacing:2px; font-weight:600; margin-bottom:8px; }
.cp-hero-title { font-size:36px; font-weight:800; color:#fff; margin:0; letter-spacing:-0.5px; }
.cp-hero-subtitle { color:#9ca3af; margin-top:10px; max-width:560px; margin-left:auto; margin-right:auto; line-height:1.5; font-size:14px; }
.cp-container { max-width:580px; margin:32px auto; padding:0 20px; }
.cp-status { text-align:center; padding:60px 20px; color:#9ca3af; }
.cp-status.error { color:#ef4444; }
.cp-progress-wrap { margin-bottom:24px; }
.cp-progress-label { font-size:11px; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px; font-weight:600; margin-bottom:6px; display:flex; justify-content:space-between; }
.cp-progress-pct { color:#c8ff00; font-weight:700; }
.cp-progress-bar { height:8px; background:#1a1a1e; border:1px solid #2a2a2e; border-radius:6px; overflow:hidden; }
.cp-progress-fill { height:100%; width:0; background:#c8ff00; transition:width .3s ease; }
.cp-notice { background:rgba(200,255,0,.10); border:1px solid #c8ff00; color:#c8ff00; padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:20px; }
.cp-form { display:none; }
.cp-form.visible { display:block; }
.cp-name-display { background:#1a1a1e; border:1px solid #2a2a2e; padding:10px 13px; border-radius:6px; color:#9ca3af; font-size:13px; margin-bottom:18px; }
.cp-name-display strong { color:#fff; }
.cp-section { margin-bottom:18px; padding:16px; background:#0f0f12; border:1px solid #2a2a2e; border-radius:8px; }
.cp-section-title { font-size:11px; color:#c8ff00; text-transform:uppercase; letter-spacing:.6px; font-weight:700; margin-bottom:14px; }
.cp-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.cp-field { margin-bottom:14px; }
.cp-label { display:block; font-size:11px; color:#9ca3af; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
.cp-input, .cp-select { width:100%; background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:11px 13px; border-radius:6px; font-size:14px; font-family:inherit; box-sizing:border-box; }
.cp-input:focus, .cp-select:focus { outline:none; border-color:#c8ff00; }
.cp-input.cp-missing, .cp-select.cp-missing, .cp-chips.cp-missing { border-color:#ef4444 !important; box-shadow:0 0 0 2px rgba(239,68,68,.2); }
.cp-chips { display:flex; flex-wrap:wrap; gap:8px; padding:4px; border:1px solid transparent; border-radius:8px; }
.cp-chip { display:inline-flex; align-items:center; background:#1a1a1e; border:1px solid #2a2a2e; color:#d1d5db; padding:8px 14px; border-radius:20px; font-size:13px; cursor:pointer; user-select:none; transition:all .12s; }
.cp-chip input { position:absolute; opacity:0; width:1px; height:1px; pointer-events:none; }
.cp-chip.checked { background:#c8ff00; color:#0a0a0a; border-color:#c8ff00; font-weight:700; }
.cp-chip.disabled { opacity:.4; cursor:not-allowed; }
.cp-check-row { display:flex; gap:10px; align-items:center; font-size:14px; color:#d1d5db; cursor:pointer; }
.cp-check-row input { transform:scale(1.2); cursor:pointer; }
.cp-actions { margin-top:24px; }
.cp-btn-save { width:100%; background:#c8ff00; color:#0a0a0a; border:none; padding:15px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; transition:opacity .15s; }
.cp-btn-save:hover { opacity:.9; }
.cp-btn-save:disabled { opacity:.5; cursor:not-allowed; }
.cp-result { margin-top:16px; padding:14px; border-radius:8px; font-size:14px; text-align:center; }
.cp-result.ok  { background:rgba(200,255,0,.15); color:#c8ff00; border:1px solid rgba(200,255,0,.3); }
.cp-result.err { background:rgba(239,68,68,.15); color:#ef4444; border:1px solid rgba(239,68,68,.3); }
.cp-community { display:none; margin-top:20px; padding:16px; background:#0f0f12; border:1px solid #25D366; border-radius:8px; text-align:center; }
@media (max-width:520px) {
    .cp-hero-title { font-size:28px; }
    .cp-container { padding:0 16px; margin-top:20px; }
    .cp-row { grid-template-columns:1fr; }
}
</style>

<section class="cp-wrap">
    <header class="cp-hero">
        <div class="cp-hero-eyebrow"><?= esc_html($_t($T['hero_eyebrow'])) ?></div>
        <h1 class="cp-hero-title"><?= esc_html($_t($T['hero_title'])) ?></h1>
        <p class="cp-hero-subtitle"><?= esc_html($_t($T['hero_subtitle'])) ?></p>
    </header>

    <div class="cp-container">
        <div id="cp-status" class="cp-status"><?= esc_html($_t($T['loading'])) ?></div>

        <form id="cp-form" class="cp-form" autocomplete="on">
            <div class="cp-progress-wrap">
                <div class="cp-progress-label">
                    <span><?= esc_html($_t($T['progress_label'])) ?></span>
                    <span class="cp-progress-pct" id="cp-progress-pct">0%</span>
                </div>
                <div class="cp-progress-bar"><div class="cp-progress-fill" id="cp-progress-fill"></div></div>
            </div>

            <div id="cp-notice" class="cp-notice" style="display:none;"></div>
            <div class="cp-name-display" id="cp-name-display"></div>

            <!-- Misure -->
            <div class="cp-section">
                <div class="cp-section-title">📐 <?= esc_html($_t($T['section_misure'])) ?></div>
                <div class="cp-row">
                    <div class="cp-field">
                        <label class="cp-label"><?= esc_html($_t($T['field_altezza'])) ?></label>
                        <input type="number" id="f-altezza" class="cp-input" min="80" max="230" inputmode="numeric" placeholder="170">
                    </div>
                    <div class="cp-field">
                        <label class="cp-label"><?= esc_html($_t($T['field_taglia'])) ?></label>
                        <select id="f-taglia" class="cp-select">
                            <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                            <?php foreach ($TAGLIE_OPTS as $t): ?><option value="<?= esc_attr($t) ?>"><?= esc_html($t) ?></option><?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <!-- FIX 2026-06-04 marco — Peso rimosso da questo step -->
                <div class="cp-field">
                    <label class="cp-label"><?= esc_html($_t($T['field_scarpe'])) ?></label>
                    <input type="number" id="f-scarpe" class="cp-input" min="20" max="55" inputmode="numeric" placeholder="40">
                </div>
                <div class="cp-field">
                    <label class="cp-label"><?= esc_html($_t($T['field_sesso'])) ?></label>
                    <select id="f-sesso" class="cp-select">
                        <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                        <?php foreach ($SESSO_OPTS as $k=>$v): ?><option value="<?= esc_attr($k) ?>"><?= esc_html($v) ?></option><?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Aspetto -->
            <div class="cp-section">
                <div class="cp-section-title">💇 <?= esc_html($_t($T['section_aspetto'])) ?></div>
                <!-- FIX 2026-06-04 marco — Lunghezza capelli rimossa da questo step -->
                <div class="cp-field">
                    <label class="cp-label"><?= esc_html($_t($T['field_capelli'])) ?></label>
                    <select id="f-capelli" class="cp-select">
                        <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                        <?php foreach ($CAPELLI_OPTS as $k=>$v): ?><option value="<?= esc_attr($k) ?>"><?= esc_html($v) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="cp-field">
                    <label class="cp-label"><?= esc_html($_t($T['field_occhi'])) ?></label>
                    <select id="f-occhi" class="cp-select">
                        <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                        <?php foreach ($OCCHI_OPTS as $k=>$v): ?><option value="<?= esc_attr($k) ?>"><?= esc_html($v) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="cp-field">
                    <label class="cp-label"><?= esc_html($_t($T['field_etnia'])) ?></label>
                    <div class="cp-chips" id="f-etnia" data-max="2" data-group="etnia">
                        <?php foreach ($ETNIA_OPTS as $k=>$v): ?>
                            <label class="cp-chip"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($v) ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Profilo professionale -->
            <div class="cp-section">
                <div class="cp-section-title">🎬 <?= esc_html($_t($T['section_profilo'])) ?></div>
                <div class="cp-field">
                    <label class="cp-label"><?= esc_html($_t($T['field_ruoli'])) ?></label>
                    <div class="cp-chips" id="f-ruoli" data-group="ruoli">
                        <?php foreach ($RUOLI_OPTS as $k=>$v): ?>
                            <label class="cp-chip"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="cp-field">
                    <label class="cp-label"><?= esc_html($_t($T['field_lingue'])) ?></label>
                    <div class="cp-chips" id="f-lingue" data-group="lingue">
                        <?php foreach ($LINGUE_OPTS as $k=>$v): ?>
                            <label class="cp-chip"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($v) ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="cp-field">
                    <label class="cp-check-row"><input type="checkbox" id="f-patente"> <?= esc_html($_t($T['field_patente'])) ?></label>
                </div>
            </div>

            <!-- Social opzionale -->
            <div class="cp-section">
                <div class="cp-section-title">📱 <?= esc_html($_t($T['section_social'])) ?></div>
                <div class="cp-row">
                    <div class="cp-field">
                        <label class="cp-label"><?= esc_html($_t($T['field_instagram'])) ?></label>
                        <input type="text" id="f-instagram" class="cp-input" placeholder="@username" maxlength="255">
                    </div>
                    <div class="cp-field">
                        <label class="cp-label"><?= esc_html($_t($T['field_tiktok'])) ?></label>
                        <input type="text" id="f-tiktok" class="cp-input" placeholder="@username" maxlength="255">
                    </div>
                </div>
            </div>

            <!-- Honeypot -->
            <div style="position:absolute;left:-9999px;opacity:0;" aria-hidden="true">
                <label>Non compilare<input type="text" id="f-honeypot" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="cp-actions">
                <button type="button" id="cp-btn-save" class="cp-btn-save" onclick="completaProfiloSubmit()"><?= esc_html($_t($T['btn_save'])) ?></button>
            </div>
            <div id="cp-result"></div>

            <div id="cp-community" class="cp-community">
                <p style="color:#d1d5db;font-size:13px;margin:0 0 12px;">🇮🇹 Sei in Italia? Ricevi i casting della tua città prima degli altri!</p>
                <a href="https://toagency.it/itacommunities-new.html" target="_blank" rel="noopener" style="display:inline-block;background:#25D366;color:#fff;padding:12px 24px;border-radius:8px;font-weight:700;font-size:14px;text-decoration:none;">📲 Entra nel gruppo WhatsApp</a>
            </div>
        </form>
    </div>
</section>

<script>
window.completaProfiloConfig = {
    apiLoad: '/crm_toagency/actions/completa-profilo-load.php',
    apiSave: '/crm_toagency/actions/completa-profilo-save.php',
    uuid:    <?= json_encode($uuid_get) ?>,
    token:   <?= json_encode($token_get) ?>,
    strings: {
        invalidLink: <?= json_encode($_t($T['invalid_link'])) ?>,
        alreadyDone: <?= json_encode($_t($T['already_done'])) ?>,
        saving:      <?= json_encode($_t($T['btn_saving'])) ?>,
        save:        <?= json_encode($_t($T['btn_save'])) ?>,
        successMsg:  <?= json_encode($_t($T['success_msg'])) ?>,
        errRequired: <?= json_encode($_t($T['err_required'])) ?>,
        errorPrefix: <?= json_encode($_t($T['error_prefix'])) ?>,
        etniaMax:    <?= json_encode($_t($T['etnia_max'])) ?>,
    }
};
</script>
<?php
$cp_js_path = get_stylesheet_directory() . '/assets/completa-profilo.js';
$cp_js_ver  = file_exists($cp_js_path) ? filemtime($cp_js_path) : '1.0';
?>
<script src="<?= esc_url($theme_uri . '/assets/completa-profilo.js') ?>?v=<?= $cp_js_ver ?>" defer></script>

<?php toa_component('footer'); ?>
