<?php
/**
 * Template Name: Crew Database
 * v1.0 — 2026-05-19
 *
 * Path: /wp-content/themes/toagency-theme/templates/page-crew-database.php
 *
 * Catalogo pubblico backstage (fotografi, MUA, stylist, ecc.).
 * Mostra crew_database WHERE visibile_pubblico=1, stato_profilo='attivo',
 * eliminato=0, consenso_pubblicazione_immagini=1.
 *
 * Privacy: solo nome (no cognome, contatti). UUID breve come codice univoco.
 * Multilingua IT/EN/FR/ES (helper inline, no WPML interferenza).
 *
 * API:    /crm_toagency/actions/crew-public-search.php
 * Lead:   /crm_toagency/actions/crew-lead.php
 */

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, ['it','en','fr','es'], true)) $__l = 'it';
$_t = function ($a) use ($__l) { return $a[$__l] ?? $a['it']; };

// ─── Categorie crew (mirror di page-registrati-crew.php) ─────────────
$CREW_CATEGORIES = [
    ['code'=>'fotografo',          'label'=>['it'=>'Fotografo','en'=>'Photographer','fr'=>'Photographe','es'=>'Fotógrafo']],
    ['code'=>'videomaker',         'label'=>['it'=>'Videomaker','en'=>'Videomaker','fr'=>'Vidéaste','es'=>'Videomaker']],
    ['code'=>'makeup_artist',      'label'=>['it'=>'Make-Up Artist','en'=>'Make-Up Artist','fr'=>'Maquilleur','es'=>'Maquillador']],
    ['code'=>'hairstylist',        'label'=>['it'=>'Hairstylist','en'=>'Hairstylist','fr'=>'Hairstylist','es'=>'Hairstylist']],
    ['code'=>'parrucchiere',       'label'=>['it'=>'Parrucchiere','en'=>'Hairdresser','fr'=>'Coiffeur','es'=>'Peluquero']],
    ['code'=>'stylist',            'label'=>['it'=>'Stylist','en'=>'Stylist','fr'=>'Styliste','es'=>'Estilista']],
    ['code'=>'fashion_designer',   'label'=>['it'=>'Fashion Designer','en'=>'Fashion Designer','fr'=>'Créateur de mode','es'=>'Diseñador de moda']],
    ['code'=>'postproduzione',     'label'=>['it'=>'Postproduzione','en'=>'Photo Post-Production','fr'=>'Post-production','es'=>'Postproducción']],
    ['code'=>'video_editing',      'label'=>['it'=>'Video Editing','en'=>'Video Editing','fr'=>'Montage vidéo','es'=>'Edición de vídeo']],
    ['code'=>'social_media',       'label'=>['it'=>'Social Media Manager','en'=>'Social Media Manager','fr'=>'Social Media Manager','es'=>'Social Media Manager']],
    ['code'=>'content_creator',    'label'=>['it'=>'Content Creator','en'=>'Content Creator','fr'=>'Créateur de contenu','es'=>'Creador de contenido']],
    ['code'=>'fashion_journalist', 'label'=>['it'=>'Fashion Journalist','en'=>'Fashion Journalist','fr'=>'Journaliste mode','es'=>'Periodista de moda']],
    ['code'=>'art_director',       'label'=>['it'=>'Art Director','en'=>'Art Director','fr'=>'Directeur artistique','es'=>'Director de arte']],
    ['code'=>'dj',                 'label'=>['it'=>'DJ','en'=>'DJ','fr'=>'DJ','es'=>'DJ']],
    ['code'=>'security',           'label'=>['it'=>'Security','en'=>'Security','fr'=>'Sécurité','es'=>'Seguridad']],
    ['code'=>'tecnico_luci',       'label'=>['it'=>'Tecnico Luci','en'=>'Lighting Tech','fr'=>'Tech. lumière','es'=>'Téc. iluminación']],
    ['code'=>'tecnico_suono',      'label'=>['it'=>'Tecnico Suono','en'=>'Sound Tech','fr'=>'Tech. son','es'=>'Téc. sonido']],
    ['code'=>'runner',             'label'=>['it'=>'Runner','en'=>'Runner','fr'=>'Runner','es'=>'Runner']],
    ['code'=>'altro',              'label'=>['it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro']],
];

$PAESI = [
    'IT' => ['it'=>'Italia','en'=>'Italy','fr'=>'Italie','es'=>'Italia'],
    'FR' => ['it'=>'Francia','en'=>'France','fr'=>'France','es'=>'Francia'],
    'ES' => ['it'=>'Spagna','en'=>'Spain','fr'=>'Espagne','es'=>'España'],
    'CH' => ['it'=>'Svizzera','en'=>'Switzerland','fr'=>'Suisse','es'=>'Suiza'],
    'GB' => ['it'=>'Regno Unito','en'=>'UK','fr'=>'Royaume-Uni','es'=>'Reino Unido'],
];

// String table
$T = [
    'hero_eyebrow'    => ['it'=>'TOAGENCY/CREW','en'=>'TOAGENCY/CREW','fr'=>'TOAGENCY/CREW','es'=>'TOAGENCY/CREW'],
    'hero_title'      => ['it'=>'Crew.','en'=>'Crew.','fr'=>'Crew.','es'=>'Crew.'],
    'hero_subtitle'   => [
        'it'=>'I professionisti backstage TOAgency: fotografi, MUA, stylist e tutti i pro dietro la fotocamera.',
        'en'=>'Backstage professionals: photographers, makeup artists, stylists and every pro behind the camera.',
        'fr'=>'Les pros du backstage : photographes, maquilleurs, stylistes et tous les pros derrière la caméra.',
        'es'=>'Los pros del backstage: fotógrafos, maquilladores, estilistas.',
    ],
    'filter_all_cat'  => ['it'=>'Tutte le categorie','en'=>'All categories','fr'=>'Toutes catégories','es'=>'Todas categorías'],
    'filter_all_paesi'=> ['it'=>'Tutti i paesi','en'=>'All countries','fr'=>'Tous pays','es'=>'Todos países'],
    'filter_all_prov' => ['it'=>'Tutte le province','en'=>'All provinces','fr'=>'Toutes provinces','es'=>'Todas provincias'],
    'loading'         => ['it'=>'Carico crew…','en'=>'Loading crew…','fr'=>'Chargement…','es'=>'Cargando…'],
    'results_label'   => ['it'=>'crew trovati','en'=>'crew found','fr'=>'crew trouvés','es'=>'crew encontrados'],
    'no_results'      => ['it'=>'Nessun crew con questi filtri.','en'=>'No crew matching.','fr'=>'Aucun crew.','es'=>'Ningún crew.'],
    'selected_count'  => ['it'=>'selezionati','en'=>'selected','fr'=>'sélectionnés','es'=>'seleccionados'],
    'request_info'    => ['it'=>'📧 Richiedi info','en'=>'📧 Request info','fr'=>'📧 Demander info','es'=>'📧 Solicitar info'],
    'clear_selection' => ['it'=>'Svuota','en'=>'Clear','fr'=>'Vider','es'=>'Vaciar'],
    'modal_title'     => ['it'=>'Richiedi info sui crew selezionati','en'=>'Request info on selected crew','fr'=>'Demander des infos','es'=>'Solicitar info'],
    'modal_intro'     => [
        'it'=>'Ti ricontatteremo entro 24h con disponibilità e tariffe.',
        'en'=>'We\'ll reply within 24h with availability and rates.',
        'fr'=>'Réponse sous 24h.',
        'es'=>'Respondemos en 24h.',
    ],
    'form_azienda'    => ['it'=>'Nome azienda','en'=>'Company name','fr'=>'Société','es'=>'Empresa'],
    'form_email'      => ['it'=>'Email','en'=>'Email','fr'=>'Email','es'=>'Email'],
    'form_tel'        => ['it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono'],
    'form_messaggio'  => ['it'=>'Messaggio','en'=>'Message','fr'=>'Message','es'=>'Mensaje'],
    'form_msg_ph'     => ['it'=>'Tipo progetto, periodo, location...','en'=>'Project, dates, location...','fr'=>'Projet, dates, lieu...','es'=>'Proyecto, fechas, lugar...'],
    'form_required'   => ['it'=>'campo obbligatorio','en'=>'required','fr'=>'requis','es'=>'requerido'],
    'btn_cancel'      => ['it'=>'Annulla','en'=>'Cancel','fr'=>'Annuler','es'=>'Cancelar'],
    'btn_send'        => ['it'=>'Invia','en'=>'Send','fr'=>'Envoyer','es'=>'Enviar'],
    'success_msg'     => ['it'=>'Grazie! Ti contatteremo a breve.','en'=>'Thanks! We\'ll be in touch.','fr'=>'Merci !','es'=>'¡Gracias!'],
    'error_msg'       => ['it'=>'Errore invio: ','en'=>'Send error: ','fr'=>'Erreur: ','es'=>'Error: '],
    'anonymous_hint'  => [
        'it'=>'Per privacy mostriamo solo il nome. Contatta TOAgency per dettagli.',
        'en'=>'For privacy we show only the first name. Contact TOAgency for details.',
        'fr'=>'Confidentialité : seul le prénom.',
        'es'=>'Privacidad: solo nombre.',
    ],
    'view_profile'    => ['it'=>'Vedi profilo','en'=>'View profile','fr'=>'Voir le profil','es'=>'Ver perfil'],
    'loading_profile' => ['it'=>'Carico…','en'=>'Loading…','fr'=>'Chargement…','es'=>'Cargando…'],
    'error_profile'   => ['it'=>'Profilo non disponibile.','en'=>'Profile unavailable.','fr'=>'Profil indisponible.','es'=>'Perfil no disponible.'],
    'album_general'   => ['it'=>'Generale','en'=>'General','fr'=>'Général','es'=>'General'],
    'no_media'        => ['it'=>'Nessun contenuto ancora.','en'=>'No content yet.','fr'=>'Aucun contenu.','es'=>'Sin contenido.'],
    'bio_placeholder' => ['it'=>'Bio in aggiornamento.','en'=>'Bio coming soon.','fr'=>'Bio à venir.','es'=>'Bio en breve.'],
    'lb_close'        => ['it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar'],
    // Età + anzianità scheda (2026-07-23)
    'age_suffix'      => ['it'=>'anni','en'=>'years old','fr'=>'ans','es'=>'años'],
    'since_label'     => ['it'=>'Nel settore da','en'=>'In the field for','fr'=>'Dans le métier depuis','es'=>'En el sector desde hace'],
    'years_label'     => ['it'=>'anni','en'=>'years','fr'=>'ans','es'=>'años'],
    'pro_label'       => ['it'=>'professionista da','en'=>'professional for','fr'=>'professionnel depuis','es'=>'profesional desde hace'],
    // 2026-07-26 — conteggio lavori in griglia (proposta ChatGPT, calcolato lato client dagli album gia' caricati)
    'works_count'     => ['it'=>'lavori','en'=>'works','fr'=>'travaux','es'=>'trabajos'],
];

$theme_uri = get_stylesheet_directory_uri();
?>

<style>
.crew-pub-wrap { background:#0a0a0a; color:#fff; min-height:100vh; font-family:'DM Sans','Inter',sans-serif; }
.crew-pub-hero { padding:48px 24px 24px; text-align:center; border-bottom:1px solid #2a2a2e; }
.crew-pub-hero-eyebrow { color:#c8ff00; font-size:13px; letter-spacing:2px; font-weight:600; margin-bottom:8px; }
.crew-pub-hero-title { font-size:56px; font-weight:800; color:#fff; margin:0; letter-spacing:-1.5px; }
.crew-pub-hero-subtitle { color:#9ca3af; margin-top:12px; max-width:640px; margin-left:auto; margin-right:auto; line-height:1.5; }
/* 2026-06-06 marco — nav switcher Talent ↔ Crew */
.toa-db-switcher { display:inline-flex; align-items:center; gap:8px; margin:10px 0 4px; }
.toa-db-switcher__chip { display:inline-flex; align-items:center; padding:6px 16px; border-radius:999px; font-size:12px; font-weight:700; letter-spacing:0.07em; text-transform:uppercase; text-decoration:none; transition:border-color .15s, color .15s; white-space:nowrap; }
.toa-db-switcher__chip--active { background:#c8ff00; color:#0a0a0a; border:2px solid #c8ff00; }
.toa-db-switcher__chip--link { background:transparent; color:rgba(255,255,255,0.5); border:1.5px solid rgba(255,255,255,0.18); }
.toa-db-switcher__chip--link:hover { border-color:rgba(200,255,0,0.45); color:#fff; }
.toa-db-switcher__sep { width:3px; height:3px; border-radius:50%; background:rgba(255,255,255,0.2); flex-shrink:0; }

.crew-pub-filters { display:flex; gap:12px; padding:24px; flex-wrap:wrap; align-items:center; border-bottom:1px solid #2a2a2e; }
.crew-pub-filters select { background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:10px 14px; border-radius:6px; font-size:14px; min-width:200px; cursor:pointer; }
.crew-pub-filters select:focus { outline:none; border-color:#c8ff00; }
.crew-cat-select-hidden { display:none !important; }
.crew-pub-results-count { color:#9ca3af; font-size:14px; margin-left:auto; }
/* 2026-07-26 — chip categoria al posto della tendina (stile talent .toa-tdb-cat-chip).
   !important su padding/border-radius: reset globale button{border-radius:6px!important;padding:12px 28px!important} altrimenti li squadra. */
.crew-cat-chips { display:flex; flex-wrap:wrap; gap:8px; padding:20px 24px 0; }
.crew-cat-chip { display:inline-flex; align-items:center; height:34px; padding:0 16px !important; border-radius:999px !important; background:transparent; border:1.5px solid rgba(255,255,255,.18); color:rgba(255,255,255,.7); font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap; transition:border-color .15s, color .15s, background .15s; }
.crew-cat-chip:hover { border-color:rgba(200,255,0,.45); color:#fff; }
.crew-cat-chip.is-active { background:#c8ff00; border-color:#c8ff00; color:#0a0a0a; }
@media (max-width:640px) { .crew-cat-chips { flex-wrap:nowrap; overflow-x:auto; -webkit-overflow-scrolling:touch; padding:16px 16px 0; } }

.crew-pub-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(260px,1fr)); gap:16px; padding:24px; padding-bottom:120px; align-items:stretch; }
.crew-pub-card { background:#1a1a1e; border:1px solid #2a2a2e; border-radius:10px; overflow:hidden; cursor:pointer; transition:all .2s; position:relative; display:flex; flex-direction:column; height:100%; }
.crew-pub-card:hover { border-color:#c8ff00; transform:translateY(-2px); }
.crew-pub-card.selected { border:2px solid #c8ff00; box-shadow:0 0 0 3px rgba(200,255,0,.18); }
/* 2026-07-26 — richiesta Marco: verticale invece di quadrata, con lo scroll frecce le teste venivano tagliate troppo spesso in 1:1 */
.crew-pub-photo { width:100%; aspect-ratio:4/5; background:#0a0a0a center/cover no-repeat; display:flex; align-items:center; justify-content:center; color:#3a3a3e; font-size:56px; flex-shrink:0; position:relative; }
/* 2026-07-26 Fase 2 — bottoncino selezione "Richiedi info" (+/✓), la card intera ora apre il profilo al click */
.crew-pub-add { position:absolute; top:10px; right:10px; width:32px; height:32px; padding:0 !important; background:#c8ff00; color:#0a0a0a; border:1px solid #c8ff00; border-radius:50% !important; display:flex; align-items:center; justify-content:center; font-size:17px; font-weight:700; cursor:pointer; box-shadow:0 2px 8px rgba(0,0,0,.45); z-index:2; }
.crew-pub-add:hover { background:#fff; }
/* 2026-07-26 Fase 3 — frecce hover per scorrere le foto lavori (pattern talent, lazy: bottoni al hover, fetch al 1° click) */
.crew-pub-nav { position:absolute; top:50%; transform:translateY(-50%); width:34px; height:34px; border-radius:50% !important; background:rgba(0,0,0,.55); color:#fff; border:1px solid rgba(255,255,255,.25); font-size:20px; line-height:1; display:flex; align-items:center; justify-content:center; cursor:pointer; z-index:2; opacity:0; transition:opacity .15s,background .15s; padding:0 !important; }
.crew-pub-card:hover .crew-pub-nav { opacity:1; }
.crew-pub-nav:hover { background:#c8ff00; color:#0a0a0a; border-color:#c8ff00; }
.crew-pub-nav-prev { left:8px; }
.crew-pub-nav-next { right:8px; }
@media (max-width:768px) { .crew-pub-nav { opacity:1; width:28px; height:28px; font-size:16px; } }
.crew-pub-body { padding:14px; display:flex; flex-direction:column; flex:1; }
.crew-pub-name-row { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
/* 2026-07-26 — avatar piccolo (la persona) accanto al nome, la foto grande della card ora e' il lavoro (cover random) */
.crew-pub-avatar { width:88px; height:88px; border-radius:50%; background:#2a2a2e center/cover no-repeat; flex-shrink:0; border:2px solid #2a2a2e; }
.crew-pub-name { font-size:15px; font-weight:600; color:#fff; }
/* 2026-07-26 — codice pill (allineato al trattamento talent .toa-tdb-card-code): maiuscolo, senza #, visibile quanto il nome */
.crew-pub-uuid { font-family:monospace; font-size:13px; font-weight:700; background:rgba(200,255,0,.22); color:#c8ff00; padding:2px 8px; border-radius:5px; letter-spacing:.04em; }
/* 2026-07-26 — categorie come testo pulito ("Beauty • Hair") invece di pillole colorate, consiglio ChatGPT */
.crew-pub-categories { color:#c8ff00; font-size:12.5px; font-weight:600; margin-top:9px; letter-spacing:.02em; }
.crew-pub-meta { font-size:12px; color:#9ca3af; margin-top:8px; text-transform:capitalize; }
/* 2026-07-26 — conteggio lavori (proposta ChatGPT), calcolato lato client, appare dopo il fetch della cover random */
.crew-pub-projcount { font-size:11.5px; color:#6b7280; margin-top:4px; }
.crew-pub-empty { text-align:center; padding:80px 20px; color:#6b7280; grid-column:1/-1; }

.crew-pub-actionbar { position:fixed; bottom:0; left:0; right:0; background:#1a1a1e; border-top:1px solid #c8ff00; padding:14px 24px; display:none; align-items:center; justify-content:space-between; z-index:100; box-shadow:0 -4px 16px rgba(0,0,0,.4); }
.crew-pub-actionbar.visible { display:flex; }
.crew-pub-actionbar .count { color:#c8ff00; font-weight:700; }
.crew-pub-actionbar .actions { display:flex; gap:10px; }
.crew-pub-actionbar .btn-clear { background:transparent; border:1px solid #6b7280; color:#fff; padding:9px 16px; border-radius:6px; cursor:pointer; font-weight:500; }
.crew-pub-actionbar .btn-req { background:#c8ff00; color:#0a0a0a; border:none; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:700; }

.crew-pub-modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,.85); z-index:500; display:none; align-items:center; justify-content:center; padding:20px; }
.crew-pub-modal-overlay.show { display:flex; }
.crew-pub-modal { background:#1a1a1e; border:1px solid #c8ff00; border-radius:12px; padding:28px; max-width:520px; width:100%; max-height:90vh; overflow-y:auto; }
.crew-pub-modal h2 { color:#c8ff00; margin:0 0 8px; font-size:20px; }
.crew-pub-modal .intro { color:#9ca3af; font-size:14px; margin-bottom:18px; }
.crew-pub-modal .field { margin-bottom:14px; }
.crew-pub-modal label { display:block; font-size:11px; color:#9ca3af; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; }
.crew-pub-modal label .req { color:#c8ff00; }
.crew-pub-modal input, .crew-pub-modal textarea { width:100%; background:#0a0a0a; border:1px solid #2a2a2e; color:#fff; padding:11px 12px; border-radius:6px; font-size:14px; font-family:inherit; box-sizing:border-box; }
.crew-pub-modal input:focus, .crew-pub-modal textarea:focus { outline:none; border-color:#c8ff00; }
.crew-pub-modal textarea { resize:vertical; min-height:80px; }
.crew-pub-modal .summary { background:#0a0a0a; border:1px solid #2a2a2e; border-radius:6px; padding:10px 12px; margin-bottom:16px; font-size:13px; color:#9ca3af; }
.crew-pub-modal .summary strong { color:#c8ff00; }
.crew-pub-modal .actions { display:flex; gap:10px; justify-content:flex-end; margin-top:18px; }
.crew-pub-modal .btn-cancel { background:transparent; border:1px solid #6b7280; color:#fff; padding:10px 20px; border-radius:6px; cursor:pointer; }
.crew-pub-modal .btn-send { background:#c8ff00; color:#0a0a0a; padding:10px 22px; border:none; border-radius:6px; cursor:pointer; font-weight:700; }
.crew-pub-modal .btn-send:disabled { opacity:.5; cursor:not-allowed; }
.crew-pub-modal .msg { padding:10px; border-radius:6px; margin-top:12px; font-size:14px; }
.crew-pub-modal .msg.ok { background:rgba(200,255,0,.15); color:#c8ff00; }
.crew-pub-modal .msg.err { background:rgba(239,68,68,.15); color:#ef4444; }

/* ─── Scheda singola crew (?uuid=) — 2026-07-11 ─── */
/* 2026-07-26 Fase 2 — .crew-pub-view rimosso: la card intera ora apre il profilo, bottone dedicato non serve più */
/* 2026-07-26 — FIX bug segnalato: #mainNav è fixed con z-index:10000 e stava SOPRA il popup (z-index:300),
   il pulsante chiudi era visibile ma i click "passavano" al menu sotto. Ora l'overlay sta sopra il nav. */
.crew-pf-overlay { position:fixed; inset:0; background:rgba(0,0,0,.9); backdrop-filter:blur(6px); -webkit-backdrop-filter:blur(6px); z-index:10001; display:none; overflow-y:auto; padding:32px 16px; }
.crew-pf-overlay.show { display:block; }
.crew-pf-card { background:linear-gradient(180deg,#161618,#0e0e10); border:1px solid #2a2a2e; border-radius:16px; max-width:960px; margin:16px auto; padding:32px 32px 40px; position:relative; box-shadow:0 24px 80px rgba(0,0,0,.6); }
/* 2026-07-26 — FIX visibilità: prima era grigio scuro su grigio scuro, si perdeva su foto nere.
   Ora stesso stile lime della X della lightbox (sempre leggibile su qualsiasi foto) + centratura flex vera. */
/* !important su padding/border-radius: c'è un reset globale "button,.btn,input[type=submit]{border-radius:6px!important;padding:12px 28px!important}" che altrimenti rompe il cerchio */
.crew-pf-close { position:absolute; top:14px; right:16px; width:36px; height:36px; background:#c8ff00; border:none; border-radius:50% !important; padding:0 !important; color:#0a0a0a; font-size:22px; font-weight:700; line-height:1; cursor:pointer; transition:all .15s; display:flex; align-items:center; justify-content:center; box-shadow:0 4px 16px rgba(0,0,0,.45); }
.crew-pf-close:hover { filter:brightness(1.08); transform:scale(1.05); }
.crew-pf-header { border-bottom:1px solid #2a2a2e; padding-bottom:20px; margin-bottom:8px; }
.crew-pf-intro { color:#d0d3d9; font-size:15px; line-height:1.65; margin:16px 0 4px; max-width:680px; white-space:pre-line; }
.crew-pf-name { color:#fff; font-size:34px; font-weight:800; letter-spacing:-.5px; margin:0 44px 12px 0; display:flex; align-items:baseline; gap:10px; flex-wrap:wrap; }
/* Copertina hero mosaico (2026-07-23, sostituita 2026-07-31 con mosaico su richiesta Marco/CRM) */
.crew-pf-card .crew-pf-close { z-index:5; }
.crew-pf-hero { position:relative; width:calc(100% + 64px); margin:-32px -32px 20px; border-radius:16px 16px 0 0; overflow:hidden; background:#0a0a0a; }
.crew-pf-hero-mosaic { display:grid; grid-template-columns:repeat(3,1fr); grid-auto-rows:clamp(100px,20vw,170px); gap:2px; }
.crew-pf-hero-tile { display:block; width:100%; height:100%; object-fit:cover; }
.crew-pf-hero-namecell { position:relative; display:flex; align-items:flex-end; padding:14px; background:linear-gradient(135deg, rgba(18,18,20,.97), rgba(18,18,20,.88)); }
.crew-pf-hero-name { color:#fff; font-size:22px; font-weight:800; letter-spacing:-.5px; margin:0; display:flex; align-items:baseline; gap:8px; flex-wrap:wrap; text-shadow:0 2px 12px rgba(0,0,0,.6); }
@media (max-width:640px){ .crew-pf-hero{ width:calc(100% + 32px); margin:-22px -16px 16px; } .crew-pf-hero-mosaic{ grid-auto-rows:clamp(76px,26vw,130px); } .crew-pf-hero-name{ font-size:17px; } }
.crew-pf-code { color:#6b7280; font-weight:500; font-size:15px; letter-spacing:.5px; font-family:monospace; }
/* 2026-07-26 — layout affiancato: avatar a sinistra (un po' piu' grande), info a destra (ottimizza lo spazio, richiesta Marco) */
.crew-pf-headrow { display:flex; align-items:flex-start; gap:18px; }
.crew-pf-avatar { flex-shrink:0; width:92px; height:92px; border-radius:50%; background:#2a2a2e center/cover no-repeat; border:3px solid #2a2a2e; }
.crew-pf-headinfo { flex:1; min-width:0; }
.crew-pf-headinfo .crew-pf-name { margin-top:0; }
.crew-pf-roles { display:flex; flex-wrap:wrap; gap:8px; }
/* 2026-07-26 — specializzazioni per ruolo (album_temi), sotto i chip ruolo */
.crew-pf-temi { color:#b8bcc4; font-size:13px; margin-top:6px; }
.crew-pf-temi strong { color:#d0d3d9; }
.crew-pf-chip { background:#c8ff00; color:#0a0a0a; padding:5px 12px; border-radius:999px; font-size:12px; font-weight:700; }
.crew-pf-album { margin-top:32px; }
.crew-pf-album-head { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
.crew-pf-album-title { color:#fff; font-size:16px; font-weight:700; margin:0; text-transform:uppercase; letter-spacing:.06em; padding-left:12px; border-left:3px solid #c8ff00; }
.crew-pf-count { background:#1a1a1e; color:#9ca3af; font-size:12px; font-weight:600; padding:2px 9px; border-radius:999px; }
.crew-pf-bio { color:#b8bcc4; font-size:14.5px; line-height:1.6; margin:0 0 14px; max-width:640px; }
.crew-pf-grid { display:grid; grid-template-columns:repeat(auto-fill, minmax(150px,1fr)); gap:12px; }
.crew-pf-media { width:100%; aspect-ratio:1; object-fit:cover; border-radius:10px; background:#0a0a0a; display:block; transition:transform .2s; }
.crew-pf-grid img.crew-pf-media:hover { transform:scale(1.02); outline:2px solid #c8ff00; outline-offset:-2px; }
.crew-pf-vwrap { position:relative; display:block; border-radius:10px; overflow:hidden; }
.crew-pf-vwrap:hover { outline:2px solid #c8ff00; outline-offset:-2px; }
.crew-pf-vthumb { position:relative; width:100%; aspect-ratio:1; border:none; border-radius:10px; background:radial-gradient(circle at 50% 40%, #1a1a1e, #0a0a0a); cursor:pointer; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; transition:outline .15s; }
.crew-pf-vthumb:hover { outline:2px solid #c8ff00; outline-offset:-2px; }
.crew-pf-vlabel { color:#9ca3af; font-size:11px; text-transform:uppercase; letter-spacing:.12em; font-weight:600; }
.crew-pf-play { width:52px; height:52px; border-radius:50%; background:rgba(200,255,0,.95); color:#0a0a0a; font-size:20px; display:flex; align-items:center; justify-content:center; padding-left:3px; box-shadow:0 4px 16px rgba(0,0,0,.4); }
.crew-pf-vthumb:hover .crew-pf-play { transform:scale(1.08); transition:transform .15s; }
.crew-pf-loading, .crew-pf-error, .crew-pf-empty { color:#9ca3af; text-align:center; padding:48px; }
.crew-pf-loc { color:#9ca3af; font-size:13px; margin-top:10px; letter-spacing:.02em; }
.crew-pf-seniority { color:#d0d3d9; font-size:13.5px; margin-top:8px; letter-spacing:.02em; }
.crew-pf-cta { display:inline-flex; align-items:center; gap:8px; margin-top:18px; background:#c8ff00; color:#0a0a0a; border:none; padding:12px 24px !important; border-radius:8px !important; font-size:14px; font-weight:800; cursor:pointer; }
.crew-pf-cta:hover { filter:brightness(1.08); }
@media (max-width:640px){ .crew-pf-card{ padding:22px 16px 32px; } .crew-pf-name{ font-size:26px; } .crew-pf-grid{ grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); gap:8px; } }

/* ─── Lightbox foto (2026-07-23) ─── z-index sopra la admin bar WP (99999) così × e foto non restano coperti */
.crew-pf-clic { cursor:zoom-in; }
.crew-lb { position:fixed; inset:0; background:rgba(0,0,0,.94); z-index:100000; display:none; align-items:center; justify-content:center; }
.crew-lb.show { display:flex; }
.crew-lb-img { max-width:92vw; max-height:88vh; object-fit:contain; border-radius:8px; box-shadow:0 20px 80px rgba(0,0,0,.6); cursor:zoom-out; }
.crew-lb-close { position:fixed; top:16px; right:20px; display:inline-flex; align-items:center; gap:6px; height:44px; padding:0 18px !important; background:#c8ff00; border:none; border-radius:999px !important; color:#0a0a0a; font-size:15px; font-weight:700; line-height:1; cursor:pointer; z-index:100001; box-shadow:0 4px 16px rgba(0,0,0,.4); }
.crew-lb-close:hover { filter:brightness(1.08); }
.crew-lb-nav { position:fixed; top:50%; transform:translateY(-50%); width:52px; height:64px; background:rgba(26,26,30,.7); border:1px solid #2a2a2e; color:#fff; font-size:34px; line-height:1; cursor:pointer; z-index:100001; border-radius:8px; }
.crew-lb-nav:hover { background:#c8ff00; color:#0a0a0a; border-color:#c8ff00; }
.crew-lb-prev { left:16px; }
.crew-lb-next { right:16px; }
.crew-lb-counter { position:fixed; bottom:20px; left:50%; transform:translateX(-50%); color:#9ca3af; font-size:13px; background:rgba(26,26,30,.8); padding:5px 12px; border-radius:999px; z-index:100001; }
@media (max-width:640px){ .crew-lb-nav{ width:40px; height:52px; font-size:26px; } .crew-lb-img{ max-width:96vw; max-height:82vh; } }

@media (max-width:640px) {
    .crew-pub-hero-title { font-size:38px; }
    .crew-pub-filters { padding:16px; }
    .crew-pub-filters select { flex:1; min-width:140px; }
    .crew-pub-results-count { width:100%; text-align:center; margin-top:4px; }
    .crew-pub-grid { grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); padding:16px; padding-bottom:120px; gap:12px; }
}
</style>

<section class="crew-pub-wrap">
    <header class="crew-pub-hero">
        <div class="crew-pub-hero-eyebrow"><?= esc_html($_t($T['hero_eyebrow'])) ?></div>
        <h1 class="crew-pub-hero-title"><?= esc_html($_t($T['hero_title'])) ?></h1>
        <!-- 2026-06-06 marco — nav switcher Talent ↔ Crew -->
        <nav class="toa-db-switcher" aria-label="<?= esc_attr($_t(['it'=>'Sezione database','en'=>'Database section','fr'=>'Section base de données','es'=>'Sección base de datos'])) ?>">
            <a class="toa-db-switcher__chip toa-db-switcher__chip--link" href="<?= esc_url(home_url('/talent-database/')) ?>">
                <?= esc_html($_t(['it'=>'Talent Immagine','en'=>'Image Talent','fr'=>'Talent Image','es'=>'Talent Imagen'])) ?>
            </a>
            <span class="toa-db-switcher__sep" aria-hidden="true"></span>
            <span class="toa-db-switcher__chip toa-db-switcher__chip--active" aria-current="page">
                <?= esc_html($_t(['it'=>'Backstage Crew','en'=>'Backstage Crew','fr'=>'Backstage Crew','es'=>'Backstage Crew'])) ?>
            </span>
        </nav>
        <p class="crew-pub-hero-subtitle"><?= esc_html($_t($T['hero_subtitle'])) ?></p>
    </header>

    <!-- 2026-07-26 — chip categoria (sostituisce la tendina, stile talent) -->
    <div class="crew-cat-chips" id="crewCatChips" role="tablist" aria-label="<?= esc_attr($_t($T['filter_all_cat'])) ?>">
        <button type="button" class="crew-cat-chip is-active" data-cat=""><?= esc_html($_t($T['filter_all_cat'])) ?></button>
        <?php foreach ($CREW_CATEGORIES as $cat): ?>
            <button type="button" class="crew-cat-chip" data-cat="<?= esc_attr($cat['code']) ?>"><?= esc_html($_t($cat['label'])) ?></button>
        <?php endforeach; ?>
    </div>

    <div class="crew-pub-filters">
        <select id="filter-categoria" class="crew-cat-select-hidden" aria-hidden="true" tabindex="-1">
            <option value=""><?= esc_html($_t($T['filter_all_cat'])) ?></option>
            <?php foreach ($CREW_CATEGORIES as $cat): ?>
                <option value="<?= esc_attr($cat['code']) ?>"><?= esc_html($_t($cat['label'])) ?></option>
            <?php endforeach; ?>
        </select>
        <select id="filter-paese">
            <option value=""><?= esc_html($_t($T['filter_all_paesi'])) ?></option>
            <?php foreach ($PAESI as $code => $label): ?>
                <option value="<?= esc_attr($code) ?>"><?= esc_html($_t($label)) ?></option>
            <?php endforeach; ?>
        </select>
        <select id="filter-provincia">
            <option value=""><?= esc_html($_t($T['filter_all_prov'])) ?></option>
        </select>
        <span class="crew-pub-results-count" id="results-count"><?= esc_html($_t($T['loading'])) ?></span>
    </div>

    <div class="crew-pub-grid" id="crew-grid"></div>

    <!-- Bottom action bar -->
    <div class="crew-pub-actionbar" id="actionbar">
        <span><span class="count" id="selection-count">0</span> <?= esc_html($_t($T['selected_count'])) ?></span>
        <div class="actions">
            <button type="button" class="btn-clear" onclick="crewPubClearSelection()"><?= esc_html($_t($T['clear_selection'])) ?></button>
            <button type="button" class="btn-req" onclick="crewPubOpenLeadModal()"><?= esc_html($_t($T['request_info'])) ?></button>
        </div>
    </div>

    <!-- Modal lead -->
    <div class="crew-pub-modal-overlay" id="modal-lead">
        <div class="crew-pub-modal">
            <h2><?= esc_html($_t($T['modal_title'])) ?></h2>
            <p class="intro"><?= esc_html($_t($T['modal_intro'])) ?></p>
            <div class="summary"><strong id="lead-selection-count">0</strong> <?= esc_html($_t($T['selected_count'])) ?></div>
            <div class="field">
                <label><?= esc_html($_t($T['form_azienda'])) ?> <span class="req">*</span></label>
                <input type="text" id="lead-azienda" autocomplete="organization">
            </div>
            <div class="field">
                <label><?= esc_html($_t($T['form_email'])) ?> <span class="req">*</span></label>
                <input type="email" id="lead-email" autocomplete="email">
            </div>
            <div class="field">
                <label><?= esc_html($_t($T['form_tel'])) ?></label>
                <input type="tel" id="lead-tel" autocomplete="tel">
            </div>
            <div class="field">
                <label><?= esc_html($_t($T['form_messaggio'])) ?> <span class="req">*</span></label>
                <textarea id="lead-msg" rows="4" placeholder="<?= esc_attr($_t($T['form_msg_ph'])) ?>"></textarea>
            </div>
            <!-- Honeypot anti-spam -->
            <div style="position:absolute;left:-9999px;opacity:0;" aria-hidden="true">
                <label>Non compilare<input type="text" id="lead-honeypot" tabindex="-1" autocomplete="off"></label>
            </div>
            <div class="actions">
                <button type="button" class="btn-cancel" onclick="crewPubCloseLeadModal()"><?= esc_html($_t($T['btn_cancel'])) ?></button>
                <button type="button" class="btn-send" id="lead-send-btn" onclick="crewPubSubmitLead()"><?= esc_html($_t($T['btn_send'])) ?></button>
            </div>
            <div id="lead-msg-result"></div>
        </div>
    </div>

    <!-- Scheda singola crew (?uuid=) -->
    <div class="crew-pf-overlay" id="crew-profile-overlay">
        <div class="crew-pf-card">
            <button type="button" class="crew-pf-close" aria-label="Chiudi" onclick="crewPubCloseProfile()">×</button>
            <div id="crew-profile-body"></div>
        </div>
    </div>

    <!-- Lightbox foto grandi (2026-07-23) -->
    <div class="crew-lb" id="crew-lightbox" aria-hidden="true">
        <button type="button" class="crew-lb-close" id="crew-lb-close" aria-label="<?= esc_attr($_t($T['lb_close'])) ?>">✕ <?= esc_html($_t($T['lb_close'])) ?></button>
        <button type="button" class="crew-lb-nav crew-lb-prev" id="crew-lb-prev" aria-label="Precedente">‹</button>
        <img class="crew-lb-img" id="crew-lb-img" src="" alt="">
        <button type="button" class="crew-lb-nav crew-lb-next" id="crew-lb-next" aria-label="Successiva">›</button>
        <div class="crew-lb-counter" id="crew-lb-counter"></div>
    </div>
</section>

<script>
window.crewPubConfig = {
    apiSearch: '/crm_toagency/actions/crew-public-search.php',
    apiLead:   '/crm_toagency/actions/crew-lead.php',
    apiProfile:'/crm_toagency/actions/crew-public-profile.php',
    provinceJsonUrl: <?= json_encode($theme_uri . '/assets/data/province-italia.json') ?>,
    lang: <?= json_encode($__l) ?>,
    catLabels: <?= json_encode(array_combine(
        array_column($CREW_CATEGORIES, 'code'),
        array_map(function ($cat) use ($_t) { return $_t($cat['label']); }, $CREW_CATEGORIES)
    )) ?>,
    strings: {
        empty:    <?= json_encode($_t($T['no_results'])) ?>,
        resultsLabel: <?= json_encode($_t($T['results_label'])) ?>,
        success:  <?= json_encode($_t($T['success_msg'])) ?>,
        errorPrefix: <?= json_encode($_t($T['error_msg'])) ?>,
        viewProfile: <?= json_encode($_t($T['view_profile'])) ?>,
        loadingProfile: <?= json_encode($_t($T['loading_profile'])) ?>,
        errorProfile: <?= json_encode($_t($T['error_profile'])) ?>,
        generalAlbum: <?= json_encode($_t($T['album_general'])) ?>,
        noMedia: <?= json_encode($_t($T['no_media'])) ?>,
        bioPlaceholder: <?= json_encode($_t($T['bio_placeholder'])) ?>,
        requestInfo: <?= json_encode($_t($T['request_info'])) ?>,
        ageSuffix: <?= json_encode($_t($T['age_suffix'])) ?>,
        sinceLabel: <?= json_encode($_t($T['since_label'])) ?>,
        yearsLabel: <?= json_encode($_t($T['years_label'])) ?>,
        proLabel: <?= json_encode($_t($T['pro_label'])) ?>,
        worksCount: <?= json_encode($_t($T['works_count'])) ?>,
    }
};
</script>
<script src="<?= esc_url($theme_uri . '/assets/crew-database-list.js') ?>?v=3.6-heronodup" defer></script>

<?php toa_component('footer'); ?>
