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

// TEMA 2026-07-16 — /completa-profilo/ consolidato nel self-edit (una pagina sola): redirect preservando uuid/t/lang
$__u  = (isset($_GET['uuid']) && is_string($_GET['uuid'])) ? preg_replace('/[^a-fA-F0-9\-]/','',$_GET['uuid']) : '';
$__tk = (isset($_GET['t'])    && is_string($_GET['t']))    ? preg_replace('/[^a-fA-F0-9]/','',$_GET['t'])       : '';
$__lg = (isset($_GET['lang']) && is_string($_GET['lang'])) ? strtolower(substr(preg_replace('/[^a-zA-Z]/','',$_GET['lang']),0,2)) : '';
$__qs = [];
if ($__u)  $__qs['uuid'] = $__u;
if ($__tk) $__qs['t']    = $__tk;
if ($__lg) $__qs['lang'] = $__lg;
wp_safe_redirect( home_url('/talent-self-edit/') . (!empty($__qs) ? '?'.http_build_query($__qs) : ''), 302 );
exit;

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
// VIA A 2026-06-12 marco — deep-link CRM token-gated: lingua candidato via ?lang (override WPML). is_string guard contro ?lang[]= (TypeError fatale su PHP 8)
if (!empty($_GET['lang']) && is_string($_GET['lang'])) $__l = strtolower(substr($_GET['lang'], 0, 2));
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
    // ── F4 2026-07-07 marco — sezione upload foto multi-album (portata da talent-self-edit) ──
    'section_foto'   => ['it'=>'Le tue foto','en'=>'Your photos','fr'=>'Tes photos','es'=>'Tus fotos'],
    'foto_subtitle'  => [
        'it'=>'Carica foto in 4 album diversi. Ogni foto è verificata dallo staff prima di essere pubblicata.',
        'en'=>'Upload photos in 4 different albums. Each photo is reviewed by staff before publication.',
        'fr'=>'Charge des photos dans 4 albums. Chaque photo est revue avant publication.',
        'es'=>'Sube fotos en 4 álbumes. Cada foto se revisa antes de publicarse.',
    ],
    'tab_polaroid'   => ['it'=>'Polaroid','en'=>'Polaroid','fr'=>'Polaroid','es'=>'Polaroid'],
    'tab_dettaglio'  => ['it'=>'Dettagli','en'=>'Details','fr'=>'Détails','es'=>'Detalles'],
    'tab_portfolio'  => ['it'=>'Portfolio','en'=>'Portfolio','fr'=>'Portfolio','es'=>'Portfolio'],
    'tab_eventi'     => ['it'=>'Eventi','en'=>'Events','fr'=>'Événements','es'=>'Eventos'],
    'album_desc' => [
        'polaroid'  => ['it'=>'Foto recenti senza trucco/filtri che mostrano il tuo aspetto reale (richiede data scatto).','en'=>'Recent photos without make-up/filters showing your actual look (date required).','fr'=>'Photos récentes sans maquillage/filtres (date requise).','es'=>'Fotos recientes sin maquillaje/filtros (fecha obligatoria).'],
        'dettaglio' => ['it'=>'Primi piani, mani, occhi, profilo, sorriso — utili per casting specifici.','en'=>'Close-ups, hands, eyes, profile, smile — useful for specific castings.','fr'=>'Gros plans, mains, yeux, profil — pour castings spécifiques.','es'=>'Primeros planos, manos, ojos, perfil — para castings específicos.'],
        'portfolio' => ['it'=>'Foto professionali da shooting/lavori già pubblicati.','en'=>'Professional photos from past shoots/published work.','fr'=>'Photos pro de shootings/travaux déjà publiés.','es'=>'Fotos profesionales de shootings/trabajos publicados.'],
        'eventi'    => ['it'=>'Foto da eventi pubblici, red carpet, premiazioni.','en'=>'Photos from public events, red carpet, awards.','fr'=>'Photos d\'événements publics, tapis rouge.','es'=>'Fotos de eventos públicos, alfombra roja.'],
    ],
    'field_data_scatto' => ['it'=>'Data scatto','en'=>'Shot date','fr'=>'Date de la prise','es'=>'Fecha de la toma'],
    'hint_data_scatto'  => ['it'=>'Quando è stata SCATTATA la foto (non quando la carichi).','en'=>'When the photo was TAKEN (not when you upload it).','fr'=>'Quand la photo a été PRISE (pas la date de chargement).','es'=>'Cuándo fue TOMADA la foto (no cuándo la subes).'],
    'data_scatto_label_req'    => ['it'=>'Data scatto (obbligatoria)','en'=>'Shot date (required)','fr'=>'Date de la prise (obligatoire)','es'=>'Fecha de la toma (obligatoria)'],
    'data_scatto_label_opt'    => ['it'=>'Data scatto (facoltativa)','en'=>'Shot date (optional)','fr'=>'Date de la prise (facultative)','es'=>'Fecha de la toma (opcional)'],
    'data_scatto_hint_polaroid'=> ['it'=>'Quando è stata SCATTATA la foto (non quando la carichi). Max 5 anni fa, verrà stampata sulla foto.','en'=>'When the photo was TAKEN (not the upload date). Max 5 years ago, it will be printed on the photo.','fr'=>'Quand la photo a été PRISE (pas la date de chargement). Max 5 ans, elle sera imprimée sur la photo.','es'=>'Cuándo fue TOMADA la foto (no la fecha de subida). Máx 5 años, se imprimirá en la foto.'],
    'data_scatto_hint_altri'   => ['it'=>'Quando è stata SCATTATA la foto (non quando la carichi). Facoltativa ma utile.','en'=>'When the photo was TAKEN (not the upload date). Optional but useful.','fr'=>'Quand la photo a été PRISE (pas la date de chargement). Facultative mais utile.','es'=>'Cuándo fue TOMADA la foto (no la fecha de subida). Opcional pero útil.'],
    'btn_upload'        => ['it'=>'Carica foto','en'=>'Upload photo','fr'=>'Charger photo','es'=>'Subir foto'],
    'btn_uploading'     => ['it'=>'Caricamento…','en'=>'Uploading…','fr'=>'Chargement…','es'=>'Subiendo…'],
    'choose_file'       => ['it'=>'Scegli file','en'=>'Choose file','fr'=>'Choisir fichier','es'=>'Elegir archivo'],
    'no_photos'         => ['it'=>'Nessuna foto in questo album.','en'=>'No photos in this album.','fr'=>'Aucune photo.','es'=>'Sin fotos.'],
    'legal_summary'  => ['it'=>'📋 Leggi disclaimer legale','en'=>'📋 Read legal disclaimer','fr'=>'📋 Lire avis légal','es'=>'📋 Leer aviso legal'],
    'legal_text'     => [
        'it' => "Caricando la foto dichiari sotto la tua responsabilità che:\n\n"
              . "1. SEI IL SOGGETTO RITRATTO o sei autorizzato da chi è ritratto a usarne l'immagine.\n\n"
              . "2. SEI L'AUTORE DELLA FOTO o hai una licenza/autorizzazione valida per usarla. La foto non viola diritti d'autore di terzi.\n\n"
              . "3. NON SONO PRESENTI WATERMARK, firme, loghi, contatti, marchi di altre agenzie o riferimenti che identifichino te o l'autore, in conformità con le linee guida del database TOAgency.\n\n"
              . "4. AUTORIZZI TOAgency a pubblicare la foto sui propri canali ufficiali (sito web, presentazioni a clienti business, materiale promozionale) per finalità di promozione professionale del tuo profilo talent, ai sensi della Legge 633/1941 artt. 96-97 e del GDPR Reg. UE 2016/679 artt. 6-7.\n\n"
              . "5. TRATTAMENTO DATI: i dati e l'immagine saranno trattati esclusivamente per la gestione del profilo talent e la presentazione a clienti aziendali (casting). Maggiori info nella Privacy Policy.\n\n"
              . "6. PUOI REVOCARE questo consenso in qualsiasi momento scrivendo a castingtoa@gmail.com (art. 17 GDPR — diritto all'oblio). La rimozione avverrà entro 30 giorni dalla richiesta.",
        'en' => "By uploading the photo you declare under your responsibility that:\n\n"
              . "1. YOU ARE THE SUBJECT shown or you are authorized by the person depicted to use the image.\n\n"
              . "2. YOU ARE THE AUTHOR of the photo or hold a valid license/authorization. The photo does not infringe third-party copyrights.\n\n"
              . "3. NO WATERMARKS, signatures, logos, contacts, other agency marks or identifying references are present, per TOAgency database guidelines.\n\n"
              . "4. YOU AUTHORIZE TOAgency to publish the photo on its official channels (website, business client presentations, promotional materials) for professional talent profile promotion, under Italian Law 633/1941 art. 96-97 and GDPR Reg. EU 2016/679 art. 6-7.\n\n"
              . "5. DATA PROCESSING: data and image will be used only for talent profile management and presentation to corporate clients (casting). See the Privacy Policy.\n\n"
              . "6. YOU MAY REVOKE this consent any time by writing to castingtoa@gmail.com (GDPR Art. 17 — right to erasure). Removal within 30 days of request.",
        'fr' => "En téléchargeant la photo, tu déclares sous ta responsabilité que :\n\n"
              . "1. TU ES LE SUJET représenté ou tu es autorisé par la personne représentée à utiliser l'image.\n\n"
              . "2. TU ES L'AUTEUR de la photo ou tu disposes d'une licence/autorisation valide.\n\n"
              . "3. AUCUN FILIGRANE, signature, logo, contact, marque d'autre agence ou référence identifiante n'est présent.\n\n"
              . "4. TU AUTORISES TOAgency à publier la photo sur ses canaux officiels pour la promotion professionnelle, conformément à la Loi italienne 633/1941 art. 96-97 et au RGPD art. 6-7.\n\n"
              . "5. TRAITEMENT DES DONNÉES : usage exclusif pour la gestion du profil talent et les castings.\n\n"
              . "6. TU PEUX RÉVOQUER ce consentement à tout moment via castingtoa@gmail.com (Art. 17 RGPD).",
        'es' => "Al subir la foto declaras bajo tu responsabilidad que:\n\n"
              . "1. ERES EL SUJETO retratado o estás autorizado por la persona retratada a usar la imagen.\n\n"
              . "2. ERES EL AUTOR de la foto o tienes licencia/autorización válida.\n\n"
              . "3. NO HAY MARCAS DE AGUA, firmas, logotipos, contactos, marcas de otras agencias.\n\n"
              . "4. AUTORIZAS a TOAgency a publicar la foto en sus canales oficiales para la promoción profesional, según la Ley italiana 633/1941 art. 96-97 y RGPD art. 6-7.\n\n"
              . "5. TRATAMIENTO DE DATOS: uso exclusivo para gestión del perfil talent y castings.\n\n"
              . "6. PUEDES REVOCAR este consentimiento escribiendo a castingtoa@gmail.com (Art. 17 RGPD).",
    ],
    'legal_consent'  => ['it'=>'Accetto il disclaimer legale qui sopra','en'=>'I accept the legal disclaimer above','fr'=>'J\'accepte l\'avis légal ci-dessus','es'=>'Acepto el aviso legal anterior'],
    'verita_polaroid'  => ['it'=>'Confermo che questa polaroid è stata scattata negli ultimi 5 anni e rappresenta il mio aspetto attuale (no trucco/filtri)','en'=>'I confirm this polaroid was taken in the last 5 years and represents my current look (no make-up/filters)','fr'=>'Je confirme que cette polaroid date des 5 dernières années et représente mon apparence actuelle','es'=>'Confirmo que esta polaroid es de los últimos 5 años y representa mi apariencia actual'],
    'verita_dettaglio' => ['it'=>'Confermo che il dettaglio mostrato (mani/occhi/profilo/sorriso ecc.) è mio e rappresenta il mio aspetto attuale','en'=>'I confirm the detail shown (hands/eyes/profile/smile etc.) is mine and represents my current appearance','fr'=>'Je confirme que le détail montré est le mien et représente mon apparence actuelle','es'=>'Confirmo que el detalle mostrado es mío y representa mi apariencia actual'],
    'verita_portfolio' => ['it'=>'Confermo di avere i diritti per usare questa foto di portfolio (autore o licenza) e che mi raffigura realisticamente','en'=>'I confirm I hold the rights to use this portfolio photo (author or license) and it depicts me realistically','fr'=>'Je confirme avoir les droits sur cette photo de portfolio et qu\'elle me représente fidèlement','es'=>'Confirmo tener los derechos sobre esta foto de portfolio y que me representa fielmente'],
    'verita_eventi'    => ['it'=>'Confermo che questa foto è stata scattata in un evento pubblico e ho il diritto di pubblicarla','en'=>'I confirm this photo was taken at a public event and I have the right to publish it','fr'=>'Je confirme que cette photo a été prise lors d\'un événement public et que j\'ai le droit de la publier','es'=>'Confirmo que esta foto fue tomada en un evento público y tengo derecho a publicarla'],
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
    // FIX 2026-06-04 marco — "Altro" (altro_immagine) rimosso dai ruoli
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
/* ── F4 2026-07-07 marco — sezione upload foto multi-album ── */
.cp-alb-tabs { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px; }
.cp-alb-tab { flex:1 1 calc(50% - 6px); min-width:120px; background:#1a1a1e; border:1px solid #2a2a2e; color:#9ca3af; padding:9px 6px; border-radius:6px; font-size:12px; cursor:pointer; font-weight:600; transition:all .15s; text-align:center; }
.cp-alb-tab:hover { color:#fff; }
.cp-alb-tab.active { background:#c8ff00; color:#0a0a0a; border-color:#c8ff00; }
.cp-alb-desc { font-size:12px; color:#9ca3af; margin-bottom:14px; line-height:1.45; padding:8px 10px; background:#0a0a0a; border-radius:6px; border-left:3px solid #c8ff00; }
.cp-alb-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-top:18px; }
.cp-alb-empty { color:#6b7280; font-size:12px; text-align:center; padding:20px; font-style:italic; grid-column:1/-1; }
.cp-alb-thumb { position:relative; aspect-ratio:1/1; background:#1a1a1e; border:3px solid transparent; border-radius:6px; overflow:hidden; cursor:pointer; transition:transform .15s, border-color .15s; }
.cp-alb-thumb:hover { transform:scale(1.02); }
.cp-alb-thumb.pending { border-color:#FFB300; }
.cp-alb-thumb.rejected { border-color:#EF4444; }
.cp-alb-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
.cp-thumb-actions { position:absolute; bottom:4px; right:4px; display:flex; gap:4px; z-index:2; opacity:0; transition:opacity .15s; }
.cp-alb-thumb:hover .cp-thumb-actions { opacity:1; }
.cp-thumb-btn { background:rgba(0,0,0,.72); border:none; color:#fff; border-radius:5px; width:28px; height:28px; font-size:14px; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0; line-height:1; }
.cp-thumb-btn:hover { background:rgba(0,0,0,.92); }
.cp-thumb-del:hover { background:rgba(239,68,68,.85); }
.cp-alb-count { text-align:center; font-size:12px; color:#9ca3af; margin:10px auto 0; padding:6px 10px; background:rgba(255,179,0,.08); border:1px solid rgba(255,179,0,.25); border-radius:6px; }
.cp-up-box { background:#0a0a0a; border:1px dashed #2a2a2e; border-radius:8px; padding:14px; }
.cp-up-field { margin-bottom:10px; }
.cp-up-row { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-top:10px; }
.cp-up-fname { font-size:12px; color:#9ca3af; flex:1 1 auto; min-width:0; word-break:break-all; }
.cp-up-btn-file { background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:9px 14px; border-radius:6px; font-size:12px; cursor:pointer; font-weight:600; }
.cp-up-btn-file:hover { border-color:#c8ff00; }
.cp-up-btn-go { background:#c8ff00; color:#0a0a0a; border:none; padding:11px 18px; border-radius:6px; font-size:13px; font-weight:700; cursor:pointer; }
.cp-up-btn-go:hover { opacity:.9; }
.cp-up-btn-go:disabled { opacity:.5; cursor:not-allowed; }
.cp-up-status { font-size:12px; margin-top:8px; min-height:18px; }
.cp-up-status.ok { color:#c8ff00; }
.cp-up-status.err { color:#ef4444; }
.cp-up-status.loading { color:#9ca3af; }
.cp-legal-disclaimer { margin:10px 0; background:#1a1a1e; border:1px solid #2a2a2e; border-radius:6px; padding:10px; text-align:left; }
.cp-legal-disclaimer summary { cursor:pointer; color:#c8ff00; font-weight:600; font-size:12px; outline:none; user-select:none; }
.cp-legal-text { margin-top:10px; font-size:11px; line-height:1.55; color:#d1d5db; max-height:180px; overflow-y:auto; padding:6px 4px; white-space:pre-line; }
.cp-legal-checkbox { display:flex; gap:8px; align-items:flex-start; margin:8px 0; font-size:12px; color:#d1d5db; cursor:pointer; line-height:1.45; }
.cp-legal-checkbox input[type="checkbox"] { margin-top:2px; flex-shrink:0; transform:scale(1.15); cursor:pointer; }
.cp-lb { display:none; position:fixed; inset:0; background:rgba(0,0,0,.92); z-index:9999; align-items:center; justify-content:center; cursor:pointer; }
@media (max-width:520px) {
    .cp-alb-grid { grid-template-columns:repeat(2,1fr); }
    .cp-legal-text { max-height:140px; }
    .cp-hero-title { font-size:28px; }
    .cp-container { padding:0 16px; margin-top:20px; }
    .cp-row { grid-template-columns:1fr; }
}
</style>

<!-- F4 2026-07-07 marco — evita leak del token in URL via Referer verso R2/CDN/terzi -->
<meta name="referrer" content="strict-origin-when-cross-origin">

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

            <!-- F4 2026-07-07 marco — Sezione foto multi-album (upload immediato, indipendente dal salvataggio dati) -->
            <div class="cp-section" id="cp-foto-section" style="display:none;">
                <div class="cp-section-title">📸 <?= esc_html($_t($T['section_foto'])) ?></div>
                <p style="font-size:12px; color:#9ca3af; margin:0 0 14px; line-height:1.45;"><?= esc_html($_t($T['foto_subtitle'])) ?></p>

                <div class="cp-alb-tabs">
                    <button type="button" class="cp-alb-tab active" data-album="polaroid" onclick="completaProfiloAlbumSwitch('polaroid')"><?= esc_html($_t($T['tab_polaroid'])) ?></button>
                    <button type="button" class="cp-alb-tab" data-album="dettaglio" onclick="completaProfiloAlbumSwitch('dettaglio')"><?= esc_html($_t($T['tab_dettaglio'])) ?></button>
                    <button type="button" class="cp-alb-tab" data-album="portfolio" onclick="completaProfiloAlbumSwitch('portfolio')"><?= esc_html($_t($T['tab_portfolio'])) ?></button>
                    <button type="button" class="cp-alb-tab" data-album="eventi" onclick="completaProfiloAlbumSwitch('eventi')"><?= esc_html($_t($T['tab_eventi'])) ?></button>
                </div>

                <div id="cp-alb-desc" class="cp-alb-desc"></div>

                <div class="cp-up-box">
                    <div class="cp-up-field" id="cp-data-scatto-wrap">
                        <label class="cp-label" id="cp-data-scatto-label"><?= esc_html($_t($T['field_data_scatto'])) ?></label>
                        <input type="date" id="cp-data-scatto" class="cp-input" max="<?= esc_attr(date('Y-m-d')) ?>">
                        <div id="cp-data-scatto-hint" style="font-size:11px; color:#6b7280; margin-top:4px;"><?= esc_html($_t($T['hint_data_scatto'])) ?></div>
                    </div>

                    <details class="cp-legal-disclaimer">
                        <summary><?= esc_html($_t($T['legal_summary'])) ?></summary>
                        <div class="cp-legal-text"><?= esc_html($_t($T['legal_text'])) ?></div>
                    </details>

                    <label class="cp-legal-checkbox">
                        <input type="checkbox" id="cp-legal-ok">
                        <span><?= esc_html($_t($T['legal_consent'])) ?></span>
                    </label>
                    <label class="cp-legal-checkbox">
                        <input type="checkbox" id="cp-verita-ok">
                        <span id="cp-verita-text"></span>
                    </label>

                    <div class="cp-up-row">
                        <input type="file" id="cp-file-input" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="completaProfiloFileChosen(this)">
                        <button type="button" class="cp-up-btn-file" onclick="document.getElementById('cp-file-input').click()"><?= esc_html($_t($T['choose_file'])) ?></button>
                        <span id="cp-up-fname" class="cp-up-fname">—</span>
                        <button type="button" id="cp-up-go" class="cp-up-btn-go" onclick="completaProfiloUploadGo()"><?= esc_html($_t($T['btn_upload'])) ?></button>
                    </div>
                    <div id="cp-up-status" class="cp-up-status"></div>
                </div>

                <div id="cp-alb-grid" class="cp-alb-grid"></div>
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

<!-- F4 2026-07-07 marco — lightbox anteprima foto (click su miniatura) -->
<div id="cp-lb" class="cp-lb" onclick="this.style.display='none'">
    <img id="cp-lb-img" src="" alt="" style="max-width:92vw;max-height:88vh;border-radius:6px;object-fit:contain;pointer-events:none;">
    <span style="position:absolute;top:14px;right:18px;color:#fff;font-size:26px;line-height:1;font-weight:300;">✕</span>
</div>

<script>
window.completaProfiloConfig = {
    apiLoad: '/crm_toagency/actions/completa-profilo-load.php',
    apiSave: '/crm_toagency/actions/completa-profilo-save.php',
    apiMediaList:   '/crm_toagency/actions/talent-media-list.php',   /* F4 2026-07-07 marco */
    apiMediaUp:     '/crm_toagency/actions/talent-media-upload.php', /* F4 2026-07-07 marco */
    apiMediaDelete: '/crm_toagency/actions/talent-media-delete.php', /* F4 2026-07-07 marco */
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
        /* F4 2026-07-07 marco — stringhe sezione foto */
        uploading:   <?= json_encode($_t($T['btn_uploading'])) ?>,
        upload:      <?= json_encode($_t($T['btn_upload'])) ?>,
        noPhotos:    <?= json_encode($_t($T['no_photos'])) ?>,
        dataScattoLabelReq:     <?= json_encode($_t($T['data_scatto_label_req'])) ?>,
        dataScattoLabelOpt:     <?= json_encode($_t($T['data_scatto_label_opt'])) ?>,
        dataScattoHintPolaroid: <?= json_encode($_t($T['data_scatto_hint_polaroid'])) ?>,
        dataScattoHintAltri:    <?= json_encode($_t($T['data_scatto_hint_altri'])) ?>,
        verita: {
            polaroid:  <?= json_encode($_t($T['verita_polaroid'])) ?>,
            dettaglio: <?= json_encode($_t($T['verita_dettaglio'])) ?>,
            portfolio: <?= json_encode($_t($T['verita_portfolio'])) ?>,
            eventi:    <?= json_encode($_t($T['verita_eventi'])) ?>,
        },
        albumDesc: {
            polaroid:  <?= json_encode($_t($T['album_desc']['polaroid'])) ?>,
            dettaglio: <?= json_encode($_t($T['album_desc']['dettaglio'])) ?>,
            portfolio: <?= json_encode($_t($T['album_desc']['portfolio'])) ?>,
            eventi:    <?= json_encode($_t($T['album_desc']['eventi'])) ?>,
        }
    }
};
</script>
<?php
$cp_js_path = get_stylesheet_directory() . '/assets/completa-profilo.js';
$cp_js_ver  = file_exists($cp_js_path) ? filemtime($cp_js_path) : '1.0';
?>
<script src="<?= esc_url($theme_uri . '/assets/completa-profilo.js') ?>?v=<?= $cp_js_ver ?>" defer></script>

<?php toa_component('footer'); ?>
