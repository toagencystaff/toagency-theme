<?php
/**
 * Template Name: Mia Agenda
 * v1.0 — 2026-08-06 (STEP 2: struttura HTML+CSS, no JS)
 *
 * Pagina pubblica self-service per il calendario disponibilità generale del talent.
 * URL: /mia-agenda/?uuid={uuid}&t={token_profilo}
 * Consuma (in uno step successivo): actions/dispo-load.php + actions/dispo-save.php
 * NON usare qui actions/dispo-conferma.php (quello resta solo nei link 1-tap dei promemoria).
 */

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, ['it','en','fr','es'], true)) $__l = 'it';
$_t = function ($a) use ($__l) { return $a[$__l] ?? $a['it']; };

$T = [
    'hero_eyebrow'   => ['it'=>'TOAGENCY/TALENT','en'=>'TOAGENCY/TALENT','fr'=>'TOAGENCY/TALENT','es'=>'TOAGENCY/TALENT'],
    'hero_title'     => ['it'=>'La tua agenda disponibilità','en'=>'Your availability calendar','fr'=>'Ton agenda de disponibilités','es'=>'Tu agenda de disponibilidad'],
    'hero_subtitle'  => [
        'it'=>'Segna i giorni in cui sei disponibile o no. Puoi aggiornarla quando vuoi tornando su questo link.',
        'en'=>'Mark the days you are available or not. You can update it anytime by coming back to this link.',
        'fr'=>'Indique tes jours de disponibilité. Tu peux la mettre à jour à tout moment via ce lien.',
        'es'=>'Marca los días en los que estás o no disponible. Puedes actualizarla cuando quieras con este enlace.',
    ],
    'loading'        => ['it'=>'Caricamento…','en'=>'Loading…','fr'=>'Chargement…','es'=>'Cargando…'],
    'invalid_link'   => ['it'=>'Link non valido. Scrivici se il problema continua.','en'=>'Invalid link. Contact us if this keeps happening.','fr'=>'Lien invalide. Contacte-nous si le problème persiste.','es'=>'Enlace inválido. Escríbenos si el problema continúa.'],
    'last_update'    => ['it'=>'Ultimo aggiornamento:','en'=>'Last update:','fr'=>'Dernière mise à jour :','es'=>'Última actualización:'],
    'never_updated'  => ['it'=>'mai aggiornata','en'=>'never updated','fr'=>'jamais mise à jour','es'=>'nunca actualizada'],

    'section_calendario' => ['it'=>'Calendario (prossimi 60 giorni)','en'=>'Calendar (next 60 days)','fr'=>'Calendrier (60 prochains jours)','es'=>'Calendario (próximos 60 días)'],
    'legend_disponibile'    => ['it'=>'Disponibile','en'=>'Available','fr'=>'Disponible','es'=>'Disponible'],
    'legend_non_disponibile'=> ['it'=>'Non disponibile','en'=>'Not available','fr'=>'Non disponible','es'=>'No disponible'],
    'legend_parziale'       => ['it'=>'Mattina / Pomeriggio / Sera','en'=>'Morning / Afternoon / Evening','fr'=>'Matin / Après-midi / Soir','es'=>'Mañana / Tarde / Noche'],
    'legend_non_so'         => ['it'=>'Non ancora segnato','en'=>'Not marked yet','fr'=>'Pas encore indiqué','es'=>'Aún sin marcar'],
    'legend_bloccato'       => ['it'=>'Bloccato dallo staff (lavoro in corso)','en'=>'Locked by staff (job in progress)','fr'=>'Verrouillé par le staff','es'=>'Bloqueado por el staff'],

    'btn_save'       => ['it'=>'Salva modifiche','en'=>'Save changes','fr'=>'Enregistrer','es'=>'Guardar cambios'],
    'btn_saving'     => ['it'=>'Salvataggio…','en'=>'Saving…','fr'=>'Enregistrement…','es'=>'Guardando…'],

    // Preferenze lavoro (toggle tri-stato: null = mai risposto, 0 = no, 1 = sì)
    'section_preferenze'   => ['it'=>'Preferenze lavoro','en'=>'Work preferences','fr'=>'Préférences de travail','es'=>'Preferencias de trabajo'],
    'pref_sconto_q'  => [
        'it'=>'Accetti uno sconto del 10% dal 3° giorno consecutivo di lavoro nello stesso evento?',
        'en'=>'Do you accept a 10% discount from the 3rd consecutive day of work on the same event?',
        'fr'=>'Acceptes-tu une remise de 10 % à partir du 3ᵉ jour consécutif de travail sur le même événement ?',
        'es'=>'¿Aceptas un descuento del 10% a partir del 3.º día consecutivo de trabajo en el mismo evento?',
    ],
    'pref_ore_q'  => [
        'it'=>'Accetti turni non a giornata piena (mezze giornate)?',
        'en'=>'Do you accept shifts that are not full-day (half days)?',
        'fr'=>'Acceptes-tu des créneaux qui ne sont pas des journées complètes (demi-journées) ?',
        'es'=>'¿Aceptas turnos que no sean de jornada completa (medias jornadas)?',
    ],
    'pref_si'          => ['it'=>'Sì','en'=>'Yes','fr'=>'Oui','es'=>'Sí'],
    'pref_no'          => ['it'=>'No','en'=>'No','fr'=>'Non','es'=>'No'],
    'pref_non_risposto'=> ['it'=>'Non hai ancora risposto','en'=>'Not answered yet','fr'=>'Pas encore répondu','es'=>'Aún no has respondido'],

    'section_basi'   => ['it'=>'Le tue basi temporanee','en'=>'Your temporary bases','fr'=>'Tes bases temporaires','es'=>'Tus bases temporales'],
    'basi_subtitle'  => [
        'it'=>'Se in certi periodi ti trovi in un\'altra città o zona, indicalo qui.',
        'en'=>'If you\'ll be in a different city or area during certain periods, add it here.',
        'fr'=>'Si tu es dans une autre ville à certaines périodes, indique-le ici.',
        'es'=>'Si en ciertos periodos estás en otra ciudad o zona, indícalo aquí.',
    ],
    'basi_empty'     => ['it'=>'Nessuna base temporanea aggiunta.','en'=>'No temporary base added.','fr'=>'Aucune base temporaire ajoutée.','es'=>'Ninguna base temporal añadida.'],
    'btn_add_base'   => ['it'=>'+ Aggiungi base','en'=>'+ Add base','fr'=>'+ Ajouter une base','es'=>'+ Añadir base'],
    'basi_period'    => ['it'=>'dal %s al %s','en'=>'from %s to %s','fr'=>'du %s au %s','es'=>'del %s al %s'],
    'basi_alloggio'  => ['it'=>'Alloggio incluso','en'=>'Accommodation included','fr'=>'Hébergement inclus','es'=>'Alojamiento incluido'],
    'error_generic'  => ['it'=>'Errore di connessione. Riprova tra poco.','en'=>'Connection error. Please try again.','fr'=>'Erreur de connexion. Réessaie plus tard.','es'=>'Error de conexión. Inténtalo de nuevo.'],

    // Stati giorno (etichette riga calendario)
    'day_state' => [
        'it' => ['disponibile'=>'Disponibile','non_disponibile'=>'Non disponibile','mattina'=>'Mattina','pomeriggio'=>'Pomeriggio','sera'=>'Sera','opzionato'=>'Opzionato (staff)','confermato'=>'Confermato (staff)','non_so'=>'Non ancora segnato'],
        'en' => ['disponibile'=>'Available','non_disponibile'=>'Not available','mattina'=>'Morning','pomeriggio'=>'Afternoon','sera'=>'Evening','opzionato'=>'Optioned (staff)','confermato'=>'Confirmed (staff)','non_so'=>'Not marked yet'],
        'fr' => ['disponibile'=>'Disponible','non_disponibile'=>'Non disponible','mattina'=>'Matin','pomeriggio'=>'Après-midi','sera'=>'Soir','opzionato'=>'Optionné (staff)','confermato'=>'Confirmé (staff)','non_so'=>'Pas encore indiqué'],
        'es' => ['disponibile'=>'Disponible','non_disponibile'=>'No disponible','mattina'=>'Mañana','pomeriggio'=>'Tarde','sera'=>'Noche','opzionato'=>'Opcionado (staff)','confermato'=>'Confirmado (staff)','non_so'=>'Aún sin marcar'],
    ],
    'wd_short' => [
        'it'=>['Dom','Lun','Mar','Mer','Gio','Ven','Sab'], 'en'=>['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
        'fr'=>['Dim','Lun','Mar','Mer','Jeu','Ven','Sam'], 'es'=>['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'],
    ],
    'mo_short' => [
        'it'=>['Gen','Feb','Mar','Apr','Mag','Giu','Lug','Ago','Set','Ott','Nov','Dic'],
        'en'=>['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        'fr'=>['Jan','Fév','Mar','Avr','Mai','Jun','Jul','Aoû','Sep','Oct','Nov','Déc'],
        'es'=>['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    ],

    // STEP 4 — interazione, salvataggio, form basi
    'btn_cancel'        => ['it'=>'Annulla','en'=>'Cancel','fr'=>'Annuler','es'=>'Cancelar'],
    'basi_form_comune'  => ['it'=>'Comune','en'=>'City','fr'=>'Ville','es'=>'Ciudad'],
    'basi_form_provincia'=> ['it'=>'Provincia (sigla)','en'=>'Province (code)','fr'=>'Province (code)','es'=>'Provincia (código)'],
    'basi_form_paese'   => ['it'=>'Paese (sigla, es. IT)','en'=>'Country (code, e.g. IT)','fr'=>'Pays (code, ex. IT)','es'=>'País (código, ej. IT)'],
    'basi_form_dal'     => ['it'=>'Dal','en'=>'From','fr'=>'Du','es'=>'Desde'],
    'basi_form_al'      => ['it'=>'Al','en'=>'To','fr'=>'Au','es'=>'Hasta'],
    'basi_form_nota'    => ['it'=>'Nota (facoltativa)','en'=>'Note (optional)','fr'=>'Note (facultative)','es'=>'Nota (opcional)'],
    'basi_form_alloggio'=> ['it'=>'Alloggio disponibile in zona','en'=>'Accommodation available nearby','fr'=>'Hébergement disponible','es'=>'Alojamiento disponible cerca'],
    'basi_form_save'    => ['it'=>'Salva base','en'=>'Save base','fr'=>'Enregistrer','es'=>'Guardar base'],
    'basi_err_comune'   => ['it'=>'Inserisci il comune.','en'=>'Enter a city.','fr'=>'Indique une ville.','es'=>'Indica una ciudad.'],
    'basi_err_date'     => ['it'=>'Controlla le date: dal ≤ al, massimo 90 giorni.','en'=>'Check the dates: from ≤ to, max 90 days.','fr'=>'Vérifie les dates : du ≤ au, max 90 jours.','es'=>'Revisa las fechas: desde ≤ hasta, máx 90 días.'],
    'basi_confirm_delete'=> ['it'=>'Eliminare questa base?','en'=>'Delete this base?','fr'=>'Supprimer cette base ?','es'=>'¿Eliminar esta base?'],
    'basi_del'          => ['it'=>'Elimina','en'=>'Delete','fr'=>'Supprimer','es'=>'Eliminar'],
    'save_ok'           => ['it'=>'✓ Salvato','en'=>'✓ Saved','fr'=>'✓ Enregistré','es'=>'✓ Guardado'],
    'save_err'          => ['it'=>'Errore nel salvataggio. Riprova.','en'=>'Save error. Please retry.','fr'=>'Erreur d\'enregistrement. Réessaie.','es'=>'Error al guardar. Inténtalo de nuevo.'],
    'skip_prefix'       => ['it'=>'Non modificati (già gestiti dallo staff):','en'=>'Not changed (already handled by staff):','fr'=>'Non modifiés (déjà gérés par le staff) :','es'=>'Sin cambios (ya gestionados por el staff):'],
    'skip_opzionato'    => ['it'=>'giorno opzionato per un lavoro','en'=>'day optioned for a job','fr'=>'jour optionné pour un travail','es'=>'día opcionado para un trabajo'],
    'skip_confermato'   => ['it'=>'giorno confermato per un lavoro','en'=>'day confirmed for a job','fr'=>'jour confirmé pour un travail','es'=>'día confirmado para un trabajo'],
];

$theme_uri = get_stylesheet_directory_uri();
$uuid_get  = $_GET['uuid'] ?? '';
$token_get = $_GET['t']    ?? '';
?>

<style>
.ma-wrap { background:#0a0a0a; color:#fff; min-height:100vh; font-family:'Inter',-apple-system,sans-serif; padding-bottom:100px; }
.ma-hero { padding:48px 24px 24px; text-align:center; border-bottom:1px solid #2a2a2e; }
.ma-hero-eyebrow { color:var(--accent,#c8ff00); font-size:12px; letter-spacing:2px; font-weight:600; margin-bottom:8px; }
.ma-hero-title { font-size:32px; font-weight:800; color:#fff; margin:0; letter-spacing:-0.5px; }
.ma-hero-subtitle { color:#9ca3af; margin-top:10px; max-width:520px; margin-left:auto; margin-right:auto; line-height:1.5; font-size:14px; }
.ma-last-update { font-family:monospace; font-size:12px; color:#6b7280; margin-top:10px; }

.ma-container { max-width:560px; margin:32px auto; padding:0 20px; }
.ma-status { text-align:center; padding:60px 20px; color:#9ca3af; }
.ma-status.error { color:#ef4444; }

.ma-body { display:none; }
.ma-body.visible { display:block; }

.ma-section { margin-bottom:24px; }
.ma-section-title { font-size:11px; color:var(--accent,#c8ff00); text-transform:uppercase; letter-spacing:.6px; font-weight:700; margin-bottom:10px; }
.ma-section-subtitle { font-size:13px; color:#9ca3af; margin-bottom:14px; line-height:1.4; }

/* Legenda stati */
.ma-legend { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px; font-size:11px; color:#9ca3af; }
.ma-legend-item { display:flex; align-items:center; gap:5px; }
.ma-dot { width:9px; height:9px; border-radius:50%; display:inline-block; flex-shrink:0; }
.ma-dot-disponibile { background:#22c55e; }
.ma-dot-non_disponibile { background:#ef4444; }
.ma-dot-parziale { background:#f59e0b; }
.ma-dot-non_so { background:#3a3a42; border:1px solid #52525b; }
.ma-dot-bloccato { background:#52525b; }

/* Lista giorni (mobile-first: righe verticali, non griglia mese) */
.ma-day-list { display:flex; flex-direction:column; gap:6px; }
.ma-day-row { display:flex; align-items:center; justify-content:space-between; gap:10px; background:#0f0f12; border:1px solid #2a2a2e; border-radius:8px; padding:12px 14px; }
.ma-day-date { font-size:13px; color:#e5e7eb; font-weight:600; }
.ma-day-date small { display:block; font-size:11px; color:#6b7280; font-weight:400; margin-top:1px; text-transform:capitalize; }
.ma-day-state { font-size:12px; color:#9ca3af; display:flex; align-items:center; gap:6px; }
.ma-day-row.locked { opacity:.7; }
.ma-day-row.locked .ma-day-state { color:#71717a; }
.ma-day-row:not(.locked) { cursor:pointer; }
.ma-day-row.pending { border-color:var(--accent,#c8ff00); border-style:dashed; }

/* Picker rapido stato giorno */
.ma-day-picker { display:flex; flex-wrap:wrap; gap:6px; padding:8px 0 4px; }
.ma-picker-btn { background:#1a1a1e; border:1px solid #2a2a2e; color:#e5e7eb; font-size:12px; padding:7px 12px; border-radius:99px; cursor:pointer; display:flex; align-items:center; gap:6px; }
.ma-picker-btn:hover { border-color:var(--accent,#c8ff00); color:#fff; }

.ma-actions { margin-top:20px; position:sticky; bottom:16px; }
.ma-btn-save { width:100%; background:var(--accent,#c8ff00); color:#0a0a0a; border:none; padding:14px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; box-shadow:0 4px 20px rgba(0,0,0,.4); }
.ma-btn-save:disabled { opacity:.5; cursor:not-allowed; }
.ma-save-msg { text-align:center; font-size:13px; margin-top:8px; min-height:16px; }
.ma-save-msg.ok { color:var(--accent,#c8ff00); }
.ma-save-msg.err { color:#ef4444; }
.ma-skip-msg { background:rgba(255,179,0,.10); border:1px solid rgba(255,179,0,.4); color:#ffb300; font-size:12px; border-radius:8px; padding:10px 12px; margin-bottom:12px; line-height:1.5; }

/* Preferenze lavoro (toggle tri-stato) */
.ma-pref-row { background:#0f0f12; border:1px solid #2a2a2e; border-radius:8px; padding:14px; margin-bottom:10px; }
.ma-pref-q { font-size:13px; color:#e5e7eb; line-height:1.4; margin-bottom:10px; }
.ma-pref-toggle { display:flex; gap:8px; }
.ma-pref-btn { flex:1; background:#1a1a1e; border:1px solid #2a2a2e; color:#9ca3af; font-size:13px; font-weight:600; padding:9px; border-radius:6px; cursor:pointer; }
.ma-pref-btn:hover { border-color:#52525b; color:#fff; }
.ma-pref-btn.active[data-value="1"] { background:var(--accent,#c8ff00); border-color:var(--accent,#c8ff00); color:#0a0a0a; }
.ma-pref-btn.active[data-value="0"] { background:#3a3a42; border-color:#52525b; color:#fff; }
.ma-pref-status { font-size:11px; color:#6b7280; margin-top:8px; }
.ma-pref-row.pending { border-color:var(--accent,#c8ff00); border-style:dashed; }

/* Basi temporanee */
.ma-basi-list { display:flex; flex-direction:column; gap:8px; margin-bottom:14px; }
.ma-basi-empty { color:#6b7280; font-size:12px; font-style:italic; padding:10px 0; }
.ma-basi-item { background:#0f0f12; border:1px solid #2a2a2e; border-radius:8px; padding:12px 14px; display:flex; justify-content:space-between; align-items:center; gap:10px; }
.ma-basi-item-info { font-size:13px; color:#e5e7eb; }
.ma-basi-item-dates { font-size:11px; color:#6b7280; margin-top:2px; }
.ma-btn-del { background:none; border:none; color:#ef4444; font-size:12px; cursor:pointer; padding:4px 8px; flex-shrink:0; }
.ma-btn-add-base { width:100%; background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:12px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; }
.ma-btn-add-base:hover { border-color:var(--accent,#c8ff00); }

/* Form aggiungi base (iniettato da JS) */
.ma-basi-form { background:#0f0f12; border:1px solid #2a2a2e; border-radius:8px; padding:14px; margin-bottom:12px; }
.ma-field { margin-bottom:12px; }
.ma-label { display:block; font-size:11px; color:#9ca3af; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
.ma-input { width:100%; background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:10px 12px; border-radius:6px; font-size:14px; font-family:inherit; box-sizing:border-box; }
.ma-input:focus { outline:none; border-color:var(--accent,#c8ff00); }
.ma-row-2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.ma-check-row { display:flex; align-items:center; gap:8px; font-size:13px; color:#e5e7eb; cursor:pointer; margin-bottom:12px; }
.ma-check-row input { accent-color:var(--accent,#c8ff00); }
.ma-form-err { color:#ef4444; font-size:12px; margin-bottom:10px; display:none; }
.ma-form-actions { display:flex; gap:8px; }
.ma-form-actions .ma-btn-save { flex:1; }
.ma-btn-secondary { flex:1; background:transparent; border:1px solid #2a2a2e; color:#9ca3af; padding:12px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; }
.ma-btn-secondary:hover { color:#fff; border-color:#52525b; }

@media (max-width:480px) {
    .ma-hero-title { font-size:26px; }
    .ma-container { padding:0 16px; margin-top:20px; }
    .ma-legend { gap:8px 12px; }
    .ma-day-row { padding:10px 12px; }
}
</style>

<section class="ma-wrap">
    <header class="ma-hero">
        <div class="ma-hero-eyebrow"><?= esc_html($_t($T['hero_eyebrow'])) ?></div>
        <h1 class="ma-hero-title"><?= esc_html($_t($T['hero_title'])) ?></h1>
        <p class="ma-hero-subtitle"><?= esc_html($_t($T['hero_subtitle'])) ?></p>
        <div class="ma-last-update" id="ma-last-update"></div>
    </header>

    <div class="ma-container">
        <div id="ma-status" class="ma-status"><?= esc_html($_t($T['loading'])) ?></div>

        <div id="ma-body" class="ma-body">

            <div class="ma-section">
                <div class="ma-section-title">📅 <?= esc_html($_t($T['section_calendario'])) ?></div>

                <div class="ma-legend">
                    <span class="ma-legend-item"><span class="ma-dot ma-dot-disponibile"></span><?= esc_html($_t($T['legend_disponibile'])) ?></span>
                    <span class="ma-legend-item"><span class="ma-dot ma-dot-non_disponibile"></span><?= esc_html($_t($T['legend_non_disponibile'])) ?></span>
                    <span class="ma-legend-item"><span class="ma-dot ma-dot-parziale"></span><?= esc_html($_t($T['legend_parziale'])) ?></span>
                    <span class="ma-legend-item"><span class="ma-dot ma-dot-non_so"></span><?= esc_html($_t($T['legend_non_so'])) ?></span>
                    <span class="ma-legend-item"><span class="ma-dot ma-dot-bloccato"></span><?= esc_html($_t($T['legend_bloccato'])) ?></span>
                </div>

                <div id="ma-day-list" class="ma-day-list">
                    <!-- STEP 3 (JS): righe giorni popolate da dispo-load.php -->
                </div>

                <div id="ma-skip-msg" class="ma-skip-msg" style="display:none;"></div>
                <div class="ma-actions">
                    <button type="button" id="ma-btn-save" class="ma-btn-save" disabled><?= esc_html($_t($T['btn_save'])) ?></button>
                    <div id="ma-save-msg" class="ma-save-msg"></div>
                </div>
            </div>

            <div class="ma-section" id="ma-preferenze-section">
                <div class="ma-section-title">⚙️ <?= esc_html($_t($T['section_preferenze'])) ?></div>

                <div class="ma-pref-row" data-pref="sconto_consecutivi_ok">
                    <div class="ma-pref-q"><?= esc_html($_t($T['pref_sconto_q'])) ?></div>
                    <div class="ma-pref-toggle">
                        <button type="button" class="ma-pref-btn" data-value="1"><?= esc_html($_t($T['pref_si'])) ?></button>
                        <button type="button" class="ma-pref-btn" data-value="0"><?= esc_html($_t($T['pref_no'])) ?></button>
                    </div>
                    <div class="ma-pref-status"></div>
                </div>

                <div class="ma-pref-row" data-pref="ore_flessibili_ok">
                    <div class="ma-pref-q"><?= esc_html($_t($T['pref_ore_q'])) ?></div>
                    <div class="ma-pref-toggle">
                        <button type="button" class="ma-pref-btn" data-value="1"><?= esc_html($_t($T['pref_si'])) ?></button>
                        <button type="button" class="ma-pref-btn" data-value="0"><?= esc_html($_t($T['pref_no'])) ?></button>
                    </div>
                    <div class="ma-pref-status"></div>
                </div>
            </div>

            <div class="ma-section" id="ma-basi-section">
                <div class="ma-section-title">📍 <?= esc_html($_t($T['section_basi'])) ?></div>
                <p class="ma-section-subtitle"><?= esc_html($_t($T['basi_subtitle'])) ?></p>

                <div id="ma-basi-list" class="ma-basi-list">
                    <!-- STEP 3 (JS): righe basi popolate da dispo-load.php -->
                </div>

                <button type="button" id="ma-btn-add-base" class="ma-btn-add-base"><?= esc_html($_t($T['btn_add_base'])) ?></button>
            </div>

        </div>
    </div>
</section>

<script>
window.__MA_CONFIG = {
    uuid: <?= json_encode($uuid_get) ?>,
    token: <?= json_encode($token_get) ?>,
    lang: <?= json_encode($__l) ?>,
    apiLoad: '/crm_toagency/actions/dispo-load.php',
    apiSave: '/crm_toagency/actions/dispo-save.php',
    strings: {
        invalidLink:  <?= json_encode($_t($T['invalid_link'])) ?>,
        errorGeneric: <?= json_encode($_t($T['error_generic'])) ?>,
        lastUpdate:   <?= json_encode($_t($T['last_update'])) ?>,
        neverUpdated: <?= json_encode($_t($T['never_updated'])) ?>,
        prefSi:       <?= json_encode($_t($T['pref_si'])) ?>,
        prefNo:       <?= json_encode($_t($T['pref_no'])) ?>,
        prefNonRisposto: <?= json_encode($_t($T['pref_non_risposto'])) ?>,
        basiEmpty:    <?= json_encode($_t($T['basi_empty'])) ?>,
        basiPeriod:   <?= json_encode($_t($T['basi_period'])) ?>,
        basiAlloggio: <?= json_encode($_t($T['basi_alloggio'])) ?>,
        dayState:     <?= json_encode($_t($T['day_state'])) ?>,
        wdShort:      <?= json_encode($_t($T['wd_short'])) ?>,
        moShort:      <?= json_encode($_t($T['mo_short'])) ?>,
        btnCancel:    <?= json_encode($_t($T['btn_cancel'])) ?>,
        basiFormComune:   <?= json_encode($_t($T['basi_form_comune'])) ?>,
        basiFormProvincia:<?= json_encode($_t($T['basi_form_provincia'])) ?>,
        basiFormPaese:    <?= json_encode($_t($T['basi_form_paese'])) ?>,
        basiFormDal:      <?= json_encode($_t($T['basi_form_dal'])) ?>,
        basiFormAl:       <?= json_encode($_t($T['basi_form_al'])) ?>,
        basiFormNota:     <?= json_encode($_t($T['basi_form_nota'])) ?>,
        basiFormAlloggio: <?= json_encode($_t($T['basi_form_alloggio'])) ?>,
        basiFormSave:     <?= json_encode($_t($T['basi_form_save'])) ?>,
        basiErrComune:    <?= json_encode($_t($T['basi_err_comune'])) ?>,
        basiErrDate:      <?= json_encode($_t($T['basi_err_date'])) ?>,
        basiConfirmDelete:<?= json_encode($_t($T['basi_confirm_delete'])) ?>,
        basiDel:          <?= json_encode($_t($T['basi_del'])) ?>,
        saveOk:       <?= json_encode($_t($T['save_ok'])) ?>,
        saveErr:      <?= json_encode($_t($T['save_err'])) ?>,
        skipPrefix:   <?= json_encode($_t($T['skip_prefix'])) ?>,
        skipReasons: {
            bloccato_opzionato:  <?= json_encode($_t($T['skip_opzionato'])) ?>,
            bloccato_confermato: <?= json_encode($_t($T['skip_confermato'])) ?>
        },
        btnSave:      <?= json_encode($_t($T['btn_save'])) ?>,
        btnSaving:    <?= json_encode($_t($T['btn_saving'])) ?>
    }
};
</script>
<?php
$ma_js_path = get_stylesheet_directory() . '/assets/mia-agenda.js';
$ma_js_ver  = file_exists($ma_js_path) ? filemtime($ma_js_path) : '1.0';
?>
<script src="<?= esc_url($theme_uri . '/assets/mia-agenda.js') ?>?v=<?= $ma_js_ver ?>" defer></script>

<?php toa_component('footer'); ?>
