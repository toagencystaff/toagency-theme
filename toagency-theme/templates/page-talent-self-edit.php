<?php
/**
 * Template Name: Talent Self-Edit
 * v2.0 — 2026-05-19 (S8.A: aggiunge sezione album 4 tab + disclaimer per-album)
 * v1.0 — 2026-05-19 (S7, replica pattern crew self-edit)
 *
 * Pagina pubblica per il self-edit dei talent.
 * URL: /talent-self-edit/?uuid={uuid}&t={token_profilo}
 *
 * S7: campi anagrafici (telefono/social/misure/aspetto) con workflow review-then-apply.
 * S8.A: sezione "📸 Le tue foto" con 4 album (polaroid/dettaglio/portfolio/eventi),
 *       upload con compressione GD lato server, disclaimer legale + veridicità per-album,
 *       approvazione staff prima della pubblicazione.
 */

toa_component('header');

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, ['it','en','fr','es'], true)) $__l = 'it';
$_t = function ($a) use ($__l) { return $a[$__l] ?? $a['it']; };

$T = [
    'hero_eyebrow'   => ['it'=>'TOAGENCY/TALENT','en'=>'TOAGENCY/TALENT','fr'=>'TOAGENCY/TALENT','es'=>'TOAGENCY/TALENT'],
    'hero_title'     => ['it'=>'Aggiorna la tua scheda talent','en'=>'Update your talent profile','fr'=>'Mets à jour ta fiche talent','es'=>'Actualiza tu ficha talent'],
    'hero_subtitle'  => [
        'it'=>'I dati vanno online subito. Le foto vengono verificate dallo staff prima di essere pubblicate.',
        'en'=>'Your details go live instantly. Photos are reviewed by our staff before publication.',
        'fr'=>'Tes infos sont publiées immédiatement. Les photos sont vérifiées avant publication.',
        'es'=>'Tus datos se publican al instante. Las fotos se revisan antes de publicarse.',
    ],
    'loading'        => ['it'=>'Caricamento…','en'=>'Loading…','fr'=>'Chargement…','es'=>'Cargando…'],
    'invalid_link'   => ['it'=>'Link non valido o scaduto.','en'=>'Invalid or expired link.','fr'=>'Lien invalide.','es'=>'Enlace inválido.'],
    'pending_msg'    => [
        'it'=>'⏳ Hai già modifiche in attesa di revisione. Nuove modifiche le sostituiranno.',
        'en'=>'⏳ You have pending changes. New changes will replace them.',
        'fr'=>'⏳ Modifications en attente.',
        'es'=>'⏳ Modificaciones pendientes.',
    ],
    'section_contatti' => ['it'=>'Contatti & social','en'=>'Contacts & social','fr'=>'Contacts & social','es'=>'Contactos & social'],
    'section_misure'   => ['it'=>'Misure','en'=>'Measurements','fr'=>'Mensurations','es'=>'Medidas'],
    'section_aspetto'  => ['it'=>'Aspetto','en'=>'Appearance','fr'=>'Apparence','es'=>'Apariencia'],
    'field_telefono'   => ['it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono'],
    'field_instagram'  => ['it'=>'Instagram','en'=>'Instagram','fr'=>'Instagram','es'=>'Instagram'],
    'field_tiktok'     => ['it'=>'TikTok','en'=>'TikTok','fr'=>'TikTok','es'=>'TikTok'],
    'field_altezza'    => ['it'=>'Altezza (cm)','en'=>'Height (cm)','fr'=>'Taille (cm)','es'=>'Altura (cm)'],
    'field_taglia'     => ['it'=>'Taglia abbigliamento','en'=>'Clothing size','fr'=>'Taille','es'=>'Talla'],
    'field_scarpe'     => ['it'=>'Numero scarpe','en'=>'Shoe size','fr'=>'Pointure','es'=>'Calzado'],
    // --- MISURE COMPLETE (15/07 COLLABORA/CRM) ---
    'm_altezza'        => ['it'=>'Altezza','en'=>'Height','fr'=>'Taille (hauteur)','es'=>'Altura'],
    'm_spalle'         => ['it'=>'Spalle','en'=>'Shoulders','fr'=>'Épaules','es'=>'Hombros'],
    'm_petto'          => ['it'=>'Petto','en'=>'Chest','fr'=>'Poitrine','es'=>'Pecho'],
    'm_vita'           => ['it'=>'Vita','en'=>'Waist','fr'=>'Taille','es'=>'Cintura'],
    'm_fianchi'        => ['it'=>'Fianchi','en'=>'Hips','fr'=>'Hanches','es'=>'Cadera'],
    'm_cavallo_interno'=> ['it'=>'Cavallo interno','en'=>'Inseam','fr'=>'Entrejambe','es'=>'Entrepierna'],
    'm_coscia'         => ['it'=>'Coscia','en'=>'Thigh','fr'=>'Cuisse','es'=>'Muslo'],
    'm_cavallo_esterno'=> ['it'=>'Cavallo esterno','en'=>'Outseam','fr'=>'Longueur ext. de jambe','es'=>'Largo ext. de pierna'],
    'm_polpaccio'      => ['it'=>'Polpaccio','en'=>'Calf','fr'=>'Mollet','es'=>'Pantorrilla'],
    'm_manica'         => ['it'=>'Manica (da centro schiena)','en'=>'Sleeve (center back)','fr'=>'Manche (milieu du dos)','es'=>'Manga (centro espalda)'],
    'm_bicipite'       => ['it'=>'Bicipite','en'=>'Bicep','fr'=>'Biceps','es'=>'Bíceps'],
    'm_avambraccio'    => ['it'=>'Avambraccio','en'=>'Forearm','fr'=>'Avant-bras','es'=>'Antebrazo'],
    'm_polso'          => ['it'=>'Polso','en'=>'Wrist','fr'=>'Poignet','es'=>'Muñeca'],
    'm_collo'          => ['it'=>'Collo','en'=>'Neck','fr'=>'Cou','es'=>'Cuello'],
    'm_scarpa'         => ['it'=>'Scarpa (EU)','en'=>'Shoe (EU)','fr'=>'Pointure (EU)','es'=>'Talla de zapato (EU)'],
    'misure_intro'     => ['it'=>'Le tue misure (facoltative). Se le conosci inseriscile, altrimenti salta pure: puoi aggiungerle in qualsiasi momento tornando qui. Puoi prenderle con un metro da sarta, farti aiutare, o passare in una merceria.','en'=>'Your measurements (optional). Add them if you know them, otherwise skip: you can add them anytime by coming back here. Take them with a soft tape measure, ask for help, or drop by a haberdashery.','fr'=>'Tes mesures (facultatif). Ajoute-les si tu les connais, sinon passe : tu peux les ajouter à tout moment en revenant ici. Prends-les avec un mètre de couturière, fais-toi aider, ou passe en mercerie.','es'=>'Tus medidas (opcional). Añádelas si las conoces, si no, sáltalas: puedes añadirlas cuando quieras volviendo aquí. Tómalas con una cinta métrica de costura, pide ayuda o pásate por una mercería.'],
    'misure_toggle'    => ['it'=>'So anche le altre misure','en'=>'I also know the other measurements','fr'=>'Je connais aussi les autres mesures','es'=>'También conozco las otras medidas'],
    'misure_prese'     => ['it'=>'Misure prese il (mese/anno)','en'=>'Measurements taken on (month/year)','fr'=>'Mesures prises le (mois/année)','es'=>'Medidas tomadas el (mes/año)'],
    'misure_legenda'   => ['it'=>'Dove si prende ogni misura','en'=>'Where each measurement is taken','fr'=>'Où prendre chaque mesure','es'=>'Dónde se toma cada medida'],
    'misure_nota_dopo' => ['it'=>'Non le sai? Salva lo stesso e aggiungile quando vuoi tornando qui col tuo link.','en'=>'Not sure of them? Save anyway and add them anytime by coming back here with your link.','fr'=>'Tu ne les connais pas ? Enregistre quand même et ajoute-les quand tu veux en revenant ici avec ton lien.','es'=>'¿No las sabes? Guarda igualmente y añádelas cuando quieras volviendo aquí con tu enlace.'],
    'conv_hint'        => ['it'=>'Salviamo sempre in cm (scarpa EU).','en'=>'We always store in cm (1 in = 2.54 cm). Shoe EU.','fr'=>'Toujours enregistré en cm. Pointure EU.','es'=>'Guardamos siempre en cm. Talla de zapato EU.'],
    'field_capelli'    => ['it'=>'Colore capelli','en'=>'Hair color','fr'=>'Cheveux','es'=>'Cabello'],
    'btn_save'         => ['it'=>'Invia modifiche','en'=>'Submit changes','fr'=>'Envoyer','es'=>'Enviar'],
    'btn_saving'       => ['it'=>'Invio…','en'=>'Sending…','fr'=>'Envoi…','es'=>'Enviando…'],
    'success_msg'      => [
        'it'=>'✓ Modifiche salvate e ora online.',
        'en'=>'✓ Changes saved and now live.',
        'fr'=>'✓ Modifications enregistrées et en ligne.',
        'es'=>'✓ Cambios guardados y en línea.',
    ],
    'no_changes'   => ['it'=>'Nessuna modifica rilevata.','en'=>'No changes.','fr'=>'Aucune modification.','es'=>'Sin cambios.'],
    // FIX 2026-06-27 marco — popup "dati ora online"
    'live_title'   => ['it'=>'✅ Le tue modifiche sono ora online!','en'=>'✅ Your changes are now live!','fr'=>'✅ Tes modifications sont en ligne !','es'=>'✅ ¡Tus cambios están en línea!'],
    'live_close'   => ['it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar'],
    'live_empty'   => ['it'=>'(vuoto)','en'=>'(empty)','fr'=>'(vide)','es'=>'(vacío)'],
    'error_prefix' => ['it'=>'Errore: ','en'=>'Error: ','fr'=>'Erreur: ','es'=>'Error: '],
    'opt_select'   => ['it'=>'—','en'=>'—','fr'=>'—','es'=>'—'],

    // ─── S8.A — Sezione album foto ───
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
    'guida_ruolo_intro'    => ['it'=>'Album consigliati per il tuo profilo','en'=>'Recommended albums for your profile','fr'=>'Albums recommandés pour ton profil','es'=>'Álbumes recomendados para tu perfil'],
    'guida_ruolo_polaroid' => ['it'=>'Le Polaroid sono obbligatorie per tutti.','en'=>'Polaroids are required for everyone.','fr'=>'Les Polaroids sont obligatoires pour tous.','es'=>'Las Polaroids son obligatorias para todos.'],
    'compl_label'          => ['it'=>'Profilo completo','en'=>'Profile complete','fr'=>'Profil complété','es'=>'Perfil completo'],
    'album_desc' => [
        'polaroid'  => ['it'=>'Foto recenti senza trucco/filtri che mostrano il tuo aspetto reale (richiede data scatto).','en'=>'Recent photos without make-up/filters showing your actual look (date required).','fr'=>'Photos récentes sans maquillage/filtres (date requise).','es'=>'Fotos recientes sin maquillaje/filtros (fecha obligatoria).'],
        'dettaglio' => ['it'=>'Primi piani, mani, occhi, profilo, sorriso — utili per casting specifici.','en'=>'Close-ups, hands, eyes, profile, smile — useful for specific castings.','fr'=>'Gros plans, mains, yeux, profil — pour castings spécifiques.','es'=>'Primeros planos, manos, ojos, perfil — para castings específicos.'],
        'portfolio' => ['it'=>'Foto professionali da shooting/lavori già pubblicati.','en'=>'Professional photos from past shoots/published work.','fr'=>'Photos pro de shootings/travaux déjà publiés.','es'=>'Fotos profesionales de shootings/trabajos publicados.'],
        'eventi'    => ['it'=>'Foto da eventi pubblici, red carpet, premiazioni.','en'=>'Photos from public events, red carpet, awards.','fr'=>'Photos d\'événements publics, tapis rouge.','es'=>'Fotos de eventos públicos, alfombra roja.'],
    ],
    'field_data_scatto' => ['it'=>'Data scatto','en'=>'Shot date','fr'=>'Date de la prise','es'=>'Fecha de la toma'],
    'hint_data_scatto'  => ['it'=>'Quando è stata SCATTATA la foto (non quando la carichi).','en'=>'When the photo was TAKEN (not when you upload it).','fr'=>'Quand la photo a été PRISE (pas la date de chargement).','es'=>'Cuándo fue TOMADA la foto (no cuándo la subes).'],
    // FIX 2026-06-27 marco — data scatto su tutti gli album (obbligatoria polaroid, facoltativa altri)
    'data_scatto_label_req'    => ['it'=>'Data scatto (obbligatoria)','en'=>'Shot date (required)','fr'=>'Date de la prise (obligatoire)','es'=>'Fecha de la toma (obligatoria)'],
    'data_scatto_label_opt'    => ['it'=>'Data scatto (facoltativa)','en'=>'Shot date (optional)','fr'=>'Date de la prise (facultative)','es'=>'Fecha de la toma (opcional)'],
    'data_scatto_hint_polaroid'=> ['it'=>'Quando è stata SCATTATA la foto (non quando la carichi). Max 5 anni fa, verrà stampata sulla foto.','en'=>'When the photo was TAKEN (not the upload date). Max 5 years ago, it will be printed on the photo.','fr'=>'Quand la photo a été PRISE (pas la date de chargement). Max 5 ans, elle sera imprimée sur la photo.','es'=>'Cuándo fue TOMADA la foto (no la fecha de subida). Máx 5 años, se imprimirá en la foto.'],
    'data_scatto_hint_altri'   => ['it'=>'Quando è stata SCATTATA la foto (non quando la carichi). Facoltativa ma utile.','en'=>'When the photo was TAKEN (not the upload date). Optional but useful.','fr'=>'Quand la photo a été PRISE (pas la date de chargement). Facultative mais utile.','es'=>'Cuándo fue TOMADA la foto (no la fecha de subida). Opcional pero útil.'],
    'btn_upload'        => ['it'=>'Carica foto','en'=>'Upload photo','fr'=>'Charger photo','es'=>'Subir foto'],
    'btn_uploading'     => ['it'=>'Caricamento…','en'=>'Uploading…','fr'=>'Chargement…','es'=>'Subiendo…'],
    'choose_file'       => ['it'=>'Scegli file','en'=>'Choose file','fr'=>'Choisir fichier','es'=>'Elegir archivo'],
    'no_photos'         => ['it'=>'Nessuna foto in questo album.','en'=>'No photos in this album.','fr'=>'Aucune photo.','es'=>'Sin fotos.'],
    'pending_badge'     => ['it'=>'In attesa di approvazione','en'=>'Pending approval','fr'=>'En attente','es'=>'Pendiente'],
    'rejected_badge'    => ['it'=>'Rifiutata','en'=>'Rejected','fr'=>'Refusée','es'=>'Rechazada'],

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
    // veridicità per album (testo dinamico in JS)
    'verita_polaroid'  => ['it'=>'Confermo che questa polaroid è stata scattata negli ultimi 5 anni e rappresenta il mio aspetto attuale (no trucco/filtri)','en'=>'I confirm this polaroid was taken in the last 5 years and represents my current look (no make-up/filters)','fr'=>'Je confirme que cette polaroid date des 5 dernières années et représente mon apparence actuelle','es'=>'Confirmo que esta polaroid es de los últimos 5 años y representa mi apariencia actual'],
    'verita_dettaglio' => ['it'=>'Confermo che il dettaglio mostrato (mani/occhi/profilo/sorriso ecc.) è mio e rappresenta il mio aspetto attuale','en'=>'I confirm the detail shown (hands/eyes/profile/smile etc.) is mine and represents my current appearance','fr'=>'Je confirme que le détail montré est le mien et représente mon apparence actuelle','es'=>'Confirmo que el detalle mostrado es mío y representa mi apariencia actual'],
    'verita_portfolio' => ['it'=>'Confermo di avere i diritti per usare questa foto di portfolio (autore o licenza) e che mi raffigura realisticamente','en'=>'I confirm I hold the rights to use this portfolio photo (author or license) and it depicts me realistically','fr'=>'Je confirme avoir les droits sur cette photo de portfolio et qu\'elle me représente fidèlement','es'=>'Confirmo tener los derechos sobre esta foto de portfolio y que me representa fielmente'],
    'verita_eventi'    => ['it'=>'Confermo che questa foto è stata scattata in un evento pubblico e ho il diritto di pubblicarla','en'=>'I confirm this photo was taken at a public event and I have the right to publish it','fr'=>'Je confirme que cette photo a été prise lors d\'un événement public et que j\'ai le droit de la publier','es'=>'Confirmo que esta foto fue tomada en un evento público y tengo derecho a publicarla'],
];

// Enum coerenti con S4 (DB normalizzato) + form registrazione (S4 + page-registrati-talent.php)
// FIX 2026-06-28 marco — valori canonici post-pulizia DB (con distinzione Chiaro/Scuro)
$CAPELLI_OPTS = [
    'Biondo Chiaro'  => ['it'=>'Biondo chiaro',  'en'=>'Light blonde',  'fr'=>'Blond clair',   'es'=>'Rubio claro'],
    'Biondo Scuro'   => ['it'=>'Biondo scuro',   'en'=>'Dark blonde',   'fr'=>'Blond foncé',   'es'=>'Rubio oscuro'],
    'Castano Chiaro' => ['it'=>'Castano chiaro', 'en'=>'Light brown',   'fr'=>'Châtain clair', 'es'=>'Castaño claro'],
    'Castano Scuro'  => ['it'=>'Castano scuro',  'en'=>'Dark brown',    'fr'=>'Châtain foncé', 'es'=>'Castaño oscuro'],
    'Nero'           => ['it'=>'Nero',            'en'=>'Black',         'fr'=>'Noir',          'es'=>'Negro'],
    'Rosso'          => ['it'=>'Rosso',           'en'=>'Red',           'fr'=>'Roux',          'es'=>'Pelirrojo'],
    'Grigio'         => ['it'=>'Grigio',          'en'=>'Gray',          'fr'=>'Gris',          'es'=>'Gris'],
    'Calvo'          => ['it'=>'Calvo',           'en'=>'Bald',          'fr'=>'Chauve',        'es'=>'Calvo'],
    'Bianco'         => ['it'=>'Bianco',          'en'=>'White',         'fr'=>'Blanc',         'es'=>'Blanco'],
    'Altro'          => ['it'=>'Altro',           'en'=>'Other',         'fr'=>'Autre',         'es'=>'Otro'],
];
$TAGLIE_OPTS = ['XS','S','M','L','XL','XXL'];

$theme_uri = get_stylesheet_directory_uri();
$uuid_get  = $_GET['uuid'] ?? '';
$token_get = $_GET['t']    ?? '';
?>

<style>
.tse-wrap { background:#0a0a0a; color:#fff; min-height:100vh; font-family:'DM Sans','Inter',sans-serif; padding-bottom:80px; }
.tse-hero { padding:48px 24px 24px; text-align:center; border-bottom:1px solid #2a2a2e; }
.tse-hero-eyebrow { color:#c8ff00; font-size:12px; letter-spacing:2px; font-weight:600; margin-bottom:8px; }
.tse-hero-title { font-size:36px; font-weight:800; color:#fff; margin:0; letter-spacing:-0.5px; }
.tse-hero-subtitle { color:#9ca3af; margin-top:10px; max-width:560px; margin-left:auto; margin-right:auto; line-height:1.5; font-size:14px; }
.tse-uuid { font-family:monospace; font-size:12px; color:#6b7280; margin-top:6px; }
.tse-container { max-width:580px; margin:32px auto; padding:0 20px; }
.tse-status { text-align:center; padding:60px 20px; color:#9ca3af; }
.tse-status.error { color:#ef4444; }
.tse-pending-notice { background:rgba(200,255,0,.10); border:1px solid #c8ff00; color:#c8ff00; padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:24px; }
.tse-form { display:none; }
.tse-form.visible { display:block; }
.tse-completezza { margin:0 0 18px; padding:12px 14px; background:#0f0f12; border:1px solid #2a2a2e; border-radius:8px; }
.tse-compl-row { display:flex; justify-content:space-between; align-items:baseline; margin-bottom:8px; }
.tse-compl-label { font-size:12px; color:#9ca3af; font-weight:600; }
.tse-compl-pct { font-size:15px; color:#c8ff00; font-weight:700; }
.tse-compl-track { height:8px; background:#1a1a1e; border-radius:99px; overflow:hidden; }
.tse-compl-fill { height:100%; background:#c8ff00; border-radius:99px; transition:width .4s ease; }
.tse-name-display { background:#1a1a1e; border:1px solid #2a2a2e; padding:10px 13px; border-radius:6px; color:#9ca3af; font-size:13px; margin-bottom:18px; }
.tse-name-display strong { color:#fff; }
.tse-section { margin-bottom:18px; padding:16px; background:#0f0f12; border:1px solid #2a2a2e; border-radius:8px; }
.tse-section-title { font-size:11px; color:#c8ff00; text-transform:uppercase; letter-spacing:.6px; font-weight:700; margin-bottom:14px; }
.tse-row { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.tse-field { margin-bottom:14px; }
.tse-label { display:block; font-size:11px; color:#9ca3af; margin-bottom:6px; text-transform:uppercase; letter-spacing:.5px; font-weight:600; }
.tse-input, .tse-select { width:100%; background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:11px 13px; border-radius:6px; font-size:14px; font-family:inherit; box-sizing:border-box; }
.tse-input:focus, .tse-select:focus { outline:none; border-color:#c8ff00; }
.tse-actions { margin-top:24px; }
.tse-btn-save { width:100%; background:#c8ff00; color:#0a0a0a; border:none; padding:14px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; transition:opacity .15s; }
.tse-btn-save:hover { opacity:.9; }
.tse-btn-save:disabled { opacity:.5; cursor:not-allowed; }
.tse-result { margin-top:16px; padding:12px; border-radius:8px; font-size:14px; text-align:center; }
.tse-result.ok  { background:rgba(200,255,0,.15); color:#c8ff00; border:1px solid rgba(200,255,0,.3); }
.tse-result.err { background:rgba(239,68,68,.15); color:#ef4444; border:1px solid rgba(239,68,68,.3); }

/* ─── S8.A — Sezione album ─── */
.tse-album-tabs { display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px; }
.tse-album-tab { flex:1 1 calc(50% - 6px); min-width:120px; background:#1a1a1e; border:1px solid #2a2a2e; color:#9ca3af; padding:9px 6px; border-radius:6px; font-size:12px; cursor:pointer; font-weight:600; transition:all .15s; text-align:center; }
.tse-album-tab:hover { color:#fff; }
.tse-album-tab.active { background:#c8ff00; color:#0a0a0a; border-color:#c8ff00; }
.tse-album-desc { font-size:12px; color:#9ca3af; margin-bottom:14px; line-height:1.45; padding:8px 10px; background:#0a0a0a; border-radius:6px; border-left:3px solid #c8ff00; }
.tse-album-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:8px; margin-bottom:16px; }
.tse-album-empty { color:#6b7280; font-size:12px; text-align:center; padding:20px; font-style:italic; grid-column:1/-1; }
.tse-album-thumb { position:relative; aspect-ratio:1/1; background:#1a1a1e; border:3px solid transparent; border-radius:6px; overflow:hidden; cursor:pointer; transition:transform .15s, border-color .15s; }
.tse-album-thumb:hover { transform:scale(1.02); }
.tse-album-thumb.pending { border-color:#FFB300; }
.tse-album-thumb.rejected { border-color:#EF4444; }
.tse-album-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
/* FIX 2026-06-28 marco — bottoni elimina/sposta su thumbnail */
.tse-thumb-actions { position:absolute; bottom:4px; right:4px; display:flex; gap:4px; z-index:2; opacity:0; transition:opacity .15s; }
.tse-album-thumb:hover .tse-thumb-actions { opacity:1; }
.tse-thumb-btn { background:rgba(0,0,0,.72); border:none; color:#fff; border-radius:5px; width:28px; height:28px; font-size:14px; cursor:pointer; display:flex; align-items:center; justify-content:center; padding:0; line-height:1; }
.tse-thumb-btn:hover { background:rgba(0,0,0,.92); }
.tse-thumb-del:hover { background:rgba(239,68,68,.85); }
.tse-move-menu { display:none; position:absolute; bottom:34px; right:0; background:#1a1a1e; border:1px solid #3a3a42; border-radius:6px; overflow:hidden; min-width:100px; z-index:3; }
.tse-move-menu button { display:block; width:100%; background:none; border:none; color:#d1d5db; font-size:12px; padding:7px 12px; text-align:left; cursor:pointer; }
.tse-move-menu button:hover { background:#2a2a2e; color:#c8ff00; }
/* badge OBSOLETO (S8.B copriva burn-in data scatto) — nascosto ovunque */
.tse-album-thumb-badge { display:none !important; }
.tse-album-count { text-align:center; font-size:12px; color:#9ca3af; margin:8px auto 4px; padding:6px 10px; background:rgba(255,179,0,.08); border:1px solid rgba(255,179,0,.25); border-radius:6px; display:inline-block; }
.tse-album-count-wrap { text-align:center; }
.tse-upload-box { background:#0a0a0a; border:1px dashed #2a2a2e; border-radius:8px; padding:14px; }
.tse-upload-field { margin-bottom:10px; }
.tse-upload-row { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
.tse-upload-fname { font-size:12px; color:#9ca3af; flex:1 1 auto; min-width:0; word-break:break-all; }
.tse-upload-btn-file { background:#1a1a1e; border:1px solid #2a2a2e; color:#fff; padding:9px 14px; border-radius:6px; font-size:12px; cursor:pointer; font-weight:600; }
.tse-upload-btn-file:hover { border-color:#c8ff00; }
.tse-upload-btn-go { background:#c8ff00; color:#0a0a0a; border:none; padding:11px 18px; border-radius:6px; font-size:13px; font-weight:700; cursor:pointer; }
.tse-upload-btn-go:hover { opacity:.9; }
.tse-upload-btn-go:disabled { opacity:.5; cursor:not-allowed; }
.tse-upload-status { font-size:12px; margin-top:8px; min-height:18px; }
.tse-upload-status.ok { color:#c8ff00; }
.tse-upload-status.err { color:#ef4444; }
.tse-upload-status.loading { color:#9ca3af; }

/* Disclaimer */
.tse-legal-disclaimer { margin:10px 0; background:#1a1a1e; border:1px solid #2a2a2e; border-radius:6px; padding:10px; text-align:left; }
.tse-legal-disclaimer summary { cursor:pointer; color:#c8ff00; font-weight:600; font-size:12px; outline:none; user-select:none; }
.tse-legal-text { margin-top:10px; font-size:11px; line-height:1.55; color:#d1d5db; max-height:180px; overflow-y:auto; padding:6px 4px; white-space:pre-line; }
.tse-legal-checkbox { display:flex; gap:8px; align-items:flex-start; margin:8px 0; font-size:12px; color:#d1d5db; cursor:pointer; line-height:1.45; }
.tse-legal-checkbox input[type="checkbox"] { margin-top:2px; flex-shrink:0; transform:scale(1.15); cursor:pointer; }

/* FIX 2026-05-26 marco — highlight campi mancanti + photo alert */
.tse-input.tse-missing,.tse-select.tse-missing{border-color:#ef4444!important;box-shadow:0 0 0 2px rgba(239,68,68,.2);}
.tse-missing-hint{font-size:11px;color:#ef4444;margin-top:3px;font-weight:600;}
.tse-photo-alert{background:rgba(239,68,68,.10);border:1px solid rgba(239,68,68,.4);border-radius:8px;padding:14px 16px;margin-bottom:20px;display:none;cursor:pointer;text-align:center;}
.tse-photo-alert-title{color:#ef4444;font-weight:700;font-size:14px;margin-bottom:4px;}
.tse-photo-alert-sub{color:#9ca3af;font-size:12px;}

@media (max-width:520px) {
    .tse-hero-title { font-size:28px; }
    .tse-container { padding:0 16px; margin-top:20px; }
    .tse-row { grid-template-columns:1fr; }
    .tse-album-grid { grid-template-columns:repeat(2,1fr); }
    .tse-album-tab { flex:1 1 calc(50% - 6px); }
    .tse-legal-text { max-height:140px; }
}

/* FIX 2026-06-27 marco — popup modifiche live */
.tse-live-overlay { position:fixed; inset:0; background:rgba(0,0,0,.78); z-index:99999; display:flex; align-items:center; justify-content:center; padding:20px; }
.tse-live-modal { background:#0f0f12; border:1px solid #c8ff00; border-radius:12px; padding:24px; max-width:420px; width:100%; max-height:80vh; overflow-y:auto; box-shadow:0 10px 50px rgba(0,0,0,.6); }
.tse-live-title { color:#c8ff00; font-size:18px; font-weight:800; text-align:center; margin-bottom:18px; line-height:1.3; }
.tse-live-list { display:flex; flex-direction:column; gap:10px; margin-bottom:20px; }
.tse-live-row { background:#1a1a1e; border:1px solid #2a2a2e; border-radius:8px; padding:10px 12px; }
.tse-live-lbl { display:block; font-size:11px; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px; font-weight:700; margin-bottom:4px; }
.tse-live-vals { font-size:14px; color:#fff; word-break:break-word; }
.tse-live-old { color:#9ca3af; text-decoration:line-through; }
.tse-live-arrow { color:#c8ff00; font-weight:700; }
.tse-live-new { color:#c8ff00; font-weight:700; }
.tse-live-btn { width:100%; background:#c8ff00; color:#0a0a0a; border:none; padding:13px; border-radius:8px; font-size:15px; font-weight:700; cursor:pointer; }
.tse-live-btn:hover { opacity:.9; }
</style>

<section class="tse-wrap">
    <header class="tse-hero">
        <div class="tse-hero-eyebrow"><?= esc_html($_t($T['hero_eyebrow'])) ?></div>
        <h1 class="tse-hero-title"><?= esc_html($_t($T['hero_title'])) ?></h1>
        <p class="tse-hero-subtitle"><?= esc_html($_t($T['hero_subtitle'])) ?></p>
        <div class="tse-uuid" id="tse-uuid-display"></div>
    </header>

    <div class="tse-container">
        <div id="tse-status" class="tse-status"><?= esc_html($_t($T['loading'])) ?></div>
        <div id="tse-pending" class="tse-pending-notice" style="display:none;"></div>

        <div id="tse-photo-alert" class="tse-photo-alert" onclick="document.getElementById('tse-foto-section').scrollIntoView({behavior:'smooth'})">
            <div class="tse-photo-alert-title">📸 Nessuna foto nel profilo!</div>
            <div class="tse-photo-alert-sub">Le foto sono essenziali — clicca qui per aggiungerle ↓</div>
        </div>

        <form id="tse-form" class="tse-form" autocomplete="on">
            <div class="tse-name-display" id="tse-name-display"></div>
            <div id="tse-completezza" class="tse-completezza" style="display:none;">
                <div class="tse-compl-row">
                    <span class="tse-compl-label" id="tse-compl-label"><?= esc_html($_t($T['compl_label'])) ?></span>
                    <span class="tse-compl-pct" id="tse-compl-pct">0%</span>
                </div>
                <div class="tse-compl-track"><div class="tse-compl-fill" id="tse-compl-fill" style="width:0%"></div></div>
            </div>

            <div class="tse-section">
                <div class="tse-section-title">📞 <?= esc_html($_t($T['section_contatti'])) ?></div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_telefono'])) ?></label>
                    <input type="tel" id="f-telefono" class="tse-input" autocomplete="tel">
                </div>
                <div class="tse-row">
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t($T['field_instagram'])) ?></label>
                        <input type="text" id="f-instagram" class="tse-input" placeholder="@username" maxlength="255">
                    </div>
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t($T['field_tiktok'])) ?></label>
                        <input type="text" id="f-tiktok" class="tse-input" placeholder="@username" maxlength="255">
                    </div>
                </div>
            </div>

            <div class="tse-section">
                <div class="tse-section-title">📐 <?= esc_html($_t($T['section_misure'])) ?></div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_altezza'])) ?></label>
                    <input type="number" id="f-altezza" class="tse-input" min="80" max="230" placeholder="170">
                </div>
                <div class="tse-row">
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t($T['field_taglia'])) ?></label>
                        <select id="f-taglia" class="tse-select">
                            <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                            <?php foreach ($TAGLIE_OPTS as $t): ?>
                                <option value="<?= esc_attr($t) ?>"><?= esc_html($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t($T['field_scarpe'])) ?></label>
                        <input type="number" id="f-scarpe" class="tse-input" min="20" max="55" placeholder="40">
                    </div>
                </div>

                <!-- ===== MISURE COMPLETE 15/07 (UI; persistenza dopo estensione CRM §3) ===== -->
                <p class="tse-help" style="font-size:12px;color:#9ca3af;line-height:1.5;margin:2px 0 14px;"><?= esc_html($_t($T['misure_intro'])) ?></p>
                <div class="tse-row">
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_petto'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="petto"></div>
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_vita'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="vita"></div>
                </div>
                <div class="tse-row">
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_fianchi'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="fianchi"></div>
                    <div class="tse-field"></div>
                </div>
                <label style="display:flex;gap:8px;align-items:center;margin:10px 0;cursor:pointer;font-size:13px;color:#e5e7eb;">
                    <input type="checkbox" id="f-mis-toggle"> <?= esc_html($_t($T['misure_toggle'])) ?>
                </label>
                <div id="tse-mis-extra" style="display:none;">
                <div class="tse-row">
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_spalle'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="spalle"></div>
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_collo'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="collo"></div>
                </div>
                <div class="tse-row">
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_cavallo_interno'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="cavallo_interno"></div>
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_cavallo_esterno'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="cavallo_esterno"></div>
                </div>
                <div class="tse-row">
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_coscia'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="coscia"></div>
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_polpaccio'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="polpaccio"></div>
                </div>
                <div class="tse-row">
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_manica'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="manica"></div>
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_bicipite'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="bicipite"></div>
                </div>
                <div class="tse-row">
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_avambraccio'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="avambraccio"></div>
                    <div class="tse-field"><label class="tse-label"><?= esc_html($_t($T['m_polso'])) ?> (cm)</label><input type="number" step="0.5" class="tse-input tse-mis" data-mis="polso"></div>
                </div>
                </div>
                <div id="tse-mis-prese" style="display:none;margin-top:6px;">
                    <label class="tse-label"><?= esc_html($_t($T['misure_prese'])) ?></label>
                    <input type="month" id="f-misure-prese" class="tse-input">
                </div>
                <p style="font-size:11px;color:#6b7280;margin:8px 0 0;"><?= esc_html($_t($T['conv_hint'])) ?></p>
                <details style="margin-top:12px;">
                    <summary style="cursor:pointer;font-size:12px;color:#c8ff00;"><?= esc_html($_t($T['misure_legenda'])) ?></summary>
                    <div style="display:flex;gap:12px;align-items:flex-start;margin-top:10px;flex-wrap:wrap;">
                        <img src="<?= esc_url(get_theme_file_uri('assets/misure-figura.jpg')) ?>" alt="" style="max-width:180px;width:45%;min-width:130px;border-radius:8px;" onerror="this.style.display='none'">
                        <ol style="margin:0;padding-left:20px;font-size:12px;color:#cbd5e1;line-height:1.7;flex:1;min-width:150px;">
                        <li><?= esc_html($_t($T['m_altezza'])) ?></li>
                        <li><?= esc_html($_t($T['m_spalle'])) ?></li>
                        <li><?= esc_html($_t($T['m_petto'])) ?></li>
                        <li><?= esc_html($_t($T['m_vita'])) ?></li>
                        <li><?= esc_html($_t($T['m_fianchi'])) ?></li>
                        <li><?= esc_html($_t($T['m_cavallo_interno'])) ?></li>
                        <li><?= esc_html($_t($T['m_coscia'])) ?></li>
                        <li><?= esc_html($_t($T['m_cavallo_esterno'])) ?></li>
                        <li><?= esc_html($_t($T['m_polpaccio'])) ?></li>
                        <li><?= esc_html($_t($T['m_manica'])) ?></li>
                        <li><?= esc_html($_t($T['m_bicipite'])) ?></li>
                        <li><?= esc_html($_t($T['m_avambraccio'])) ?></li>
                        <li><?= esc_html($_t($T['m_polso'])) ?></li>
                        <li><?= esc_html($_t($T['m_collo'])) ?></li>
                        <li><?= esc_html($_t($T['m_scarpa'])) ?></li>
                        </ol>
                    </div>
                </details>
                <p style="font-size:12px;color:#9ca3af;margin:12px 0 0;line-height:1.5;"><?= esc_html($_t($T['misure_nota_dopo'])) ?></p>
                <script>
                (function(){
                  function q(){ return document.querySelectorAll('.tse-mis, #f-altezza, #f-scarpe'); }
                  function upd(){ var any=false; q().forEach(function(i){ if(i.value && (''+i.value).trim()!=='') any=true; }); var pr=document.getElementById('tse-mis-prese'); if(pr) pr.style.display = any ? '' : 'none'; }
                  var tg=document.getElementById('f-mis-toggle');
                  if(tg){ tg.addEventListener('change',function(){ var e=document.getElementById('tse-mis-extra'); if(e) e.style.display=tg.checked?'':'none'; }); }
                  q().forEach(function(i){ i.addEventListener('input',upd); });
                  upd();
                })();
                </script>

            </div>

            <div class="tse-section">
                <div class="tse-section-title">💇 <?= esc_html($_t($T['section_aspetto'])) ?></div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_capelli'])) ?></label>
                    <select id="f-capelli" class="tse-select">
                        <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                        <?php foreach ($CAPELLI_OPTS as $code => $labels): ?>
                            <option value="<?= esc_attr($code) ?>"><?= esc_html($_t($labels)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- FIX 2026-06-28 marco — sezione Indirizzo (comune + provincia) -->
            <div class="tse-section">
                <div class="tse-section-title">📍 <?= esc_html($_t(['it'=>'Indirizzo','en'=>'Location','fr'=>'Localisation','es'=>'Ubicación'])) ?></div>
                <div class="tse-row">
                    <!-- FIX 2026-07-01 marco — comune con ricerca a suggerimenti (no testo libero): scegli dalla lista -->
                    <div class="tse-field" style="position:relative;">
                        <label class="tse-label"><?= esc_html($_t(['it'=>'Comune / Città','en'=>'City','fr'=>'Ville','es'=>'Ciudad'])) ?></label>
                        <input type="text" id="f-comune_search" class="tse-input" placeholder="<?= esc_attr($_t(['it'=>'Scrivi e scegli dalla lista…','en'=>'Type and pick from the list…','fr'=>'Écrivez et choisissez…','es'=>'Escribe y elige…'])) ?>" maxlength="100" autocomplete="off">
                        <input type="hidden" id="f-comune_residenza">
                        <div id="f-comune_dropdown" class="tse-ac-dd" style="display:none;position:absolute;left:0;right:0;top:100%;z-index:60;background:#141418;border:1px solid #333;border-radius:8px;margin-top:4px;max-height:240px;overflow:auto;box-shadow:0 8px 24px rgba(0,0,0,.4);"></div>
                    </div>
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t(['it'=>'Provincia','en'=>'Province / County','fr'=>'Province','es'=>'Provincia'])) ?></label>
                        <!-- FIX 2026-07-01 marco — tendina provincia self-edit (no testo libero) -->
                        <select id="f-provincia_domicilio" class="tse-select">
                            <option value=""><?= esc_html($_t(['it'=>'Seleziona provincia','en'=>'Select province','fr'=>'Choisir la province','es'=>'Seleccionar provincia'])) ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Honeypot -->
            <div style="position:absolute;left:-9999px;opacity:0;" aria-hidden="true">
                <label>Non compilare<input type="text" id="f-honeypot" tabindex="-1" autocomplete="off"></label>
            </div>

            <div class="tse-actions">
                <button type="button" id="tse-btn-save" class="tse-btn-save" onclick="talentEditSubmit()"><?= esc_html($_t($T['btn_save'])) ?></button>
            </div>

            <div id="tse-result"></div>
            <div id="tse-community-block" style="display:none;margin-top:20px;padding:16px;background:#0f0f12;border:1px solid #25D366;border-radius:8px;text-align:center;">
                <p style="color:#d1d5db;font-size:13px;margin:0 0 12px;">🇮🇹 Sei in Italia? Ricevi i casting della tua città prima degli altri!</p>
                <a href="https://toagency.it/itacommunities-new.html" target="_blank" rel="noopener" style="display:inline-block;background:#25D366;color:#fff;padding:12px 24px;border-radius:8px;font-weight:700;font-size:14px;text-decoration:none;">📲 Entra nel gruppo WhatsApp</a>
            </div>
        </form>

        <!-- ─── S8.A — Sezione album foto ─── -->
        <div id="tse-foto-section" class="tse-section" style="display:none; margin-top:20px;">
            <div class="tse-section-title">📸 <?= esc_html($_t($T['section_foto'])) ?></div>
            <p style="font-size:12px; color:#9ca3af; margin:0 0 14px; line-height:1.45;"><?= esc_html($_t($T['foto_subtitle'])) ?></p>
            <div id="tse-ruolo-guida" class="tse-album-desc" style="display:none; border-left-color:#c8ff00;"></div>

            <div class="tse-album-tabs">
                <button type="button" class="tse-album-tab active" data-album="polaroid" onclick="talentAlbumSwitch('polaroid')"><?= esc_html($_t($T['tab_polaroid'])) ?></button>
                <button type="button" class="tse-album-tab" data-album="dettaglio" onclick="talentAlbumSwitch('dettaglio')"><?= esc_html($_t($T['tab_dettaglio'])) ?></button>
                <button type="button" class="tse-album-tab" data-album="portfolio" onclick="talentAlbumSwitch('portfolio')"><?= esc_html($_t($T['tab_portfolio'])) ?></button>
                <button type="button" class="tse-album-tab" data-album="eventi" onclick="talentAlbumSwitch('eventi')"><?= esc_html($_t($T['tab_eventi'])) ?></button>
            </div>

            <div id="tse-album-desc" class="tse-album-desc"></div>

            <!-- FIX 2026-06-28 marco — upload-box sopra la griglia (era troppo lontano da scrollare) -->
            <div class="tse-upload-box">
                <div class="tse-upload-field" id="tse-data-scatto-wrap">
                    <label class="tse-label" id="tse-data-scatto-label"><?= esc_html($_t($T['field_data_scatto'])) ?></label>
                    <input type="month" id="tse-data-scatto" class="tse-input"
                           max="<?= esc_attr(date('Y-m')) ?>">
                    <div id="tse-data-scatto-hint" style="font-size:11px; color:#6b7280; margin-top:4px;"><?= esc_html($_t($T['hint_data_scatto'])) ?></div>
                </div>

                <details class="tse-legal-disclaimer">
                    <summary><?= esc_html($_t($T['legal_summary'])) ?></summary>
                    <div class="tse-legal-text"><?= esc_html($_t($T['legal_text'])) ?></div>
                </details>

                <label class="tse-legal-checkbox">
                    <input type="checkbox" id="tse-legal-ok">
                    <span><?= esc_html($_t($T['legal_consent'])) ?></span>
                </label>
                <label class="tse-legal-checkbox">
                    <input type="checkbox" id="tse-verita-ok">
                    <span id="tse-verita-text"></span>
                </label>

                <div class="tse-upload-row" style="margin-top:10px;">
                    <input type="file" id="tse-file-input" accept="image/jpeg,image/png,image/webp" style="display:none;" onchange="talentFileChosen(this)">
                    <button type="button" class="tse-upload-btn-file" onclick="document.getElementById('tse-file-input').click()"><?= esc_html($_t($T['choose_file'])) ?></button>
                    <span id="tse-upload-fname" class="tse-upload-fname">—</span>
                    <button type="button" id="tse-upload-go" class="tse-upload-btn-go" onclick="talentUploadGo()"><?= esc_html($_t($T['btn_upload'])) ?></button>
                </div>
                <div id="tse-upload-status" class="tse-upload-status"></div>
            </div>

            <div id="tse-album-grid" class="tse-album-grid" style="margin-top:18px;"></div>
        </div>
    </div>
</section>

<!-- FIX 2026-06-28 marco — lightbox anteprima foto (click su thumbnail) -->
<div id="tse-lb" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.92);z-index:9999;align-items:center;justify-content:center;cursor:pointer;" onclick="this.style.display='none'">
    <img id="tse-lb-img" src="" alt="" style="max-width:92vw;max-height:88vh;border-radius:6px;object-fit:contain;pointer-events:none;">
    <span style="position:absolute;top:14px;right:18px;color:#fff;font-size:26px;line-height:1;font-weight:300;">✕</span>
</div>

<script>
window.talentEditConfig = {
    apiLoad:     '/crm_toagency/actions/talent-self-edit-load.php',
    apiSave:     '/crm_toagency/actions/talent-self-edit-save.php',
    apiMediaList:'/crm_toagency/actions/talent-media-list.php',
    apiMediaUp:  '/crm_toagency/actions/talent-media-upload.php',
    apiStato:    '/crm_toagency/actions/talent-profilo-stato.php',
    provinceJsonUrl: <?= json_encode($theme_uri . '/assets/data/province-italia.json') ?>, /* FIX 2026-07-01 marco — tendina provincia self-edit */
    comuneApiUrl: '/crm_toagency/actions/cerca-comune.php', /* FIX 2026-07-01 marco — ricerca comune self-edit */
    uuid:    <?= json_encode($uuid_get) ?>,
    token:   <?= json_encode($token_get) ?>,
    strings: {
        invalidLink: <?= json_encode($_t($T['invalid_link'])) ?>,
        pending:     <?= json_encode($_t($T['pending_msg'])) ?>,
        saving:      <?= json_encode($_t($T['btn_saving'])) ?>,
        save:        <?= json_encode($_t($T['btn_save'])) ?>,
        successMsg:  <?= json_encode($_t($T['success_msg'])) ?>,
        noChanges:   <?= json_encode($_t($T['no_changes'])) ?>,
        errorPrefix: <?= json_encode($_t($T['error_prefix'])) ?>,
        liveTitle:   <?= json_encode($_t($T['live_title'])) ?>,
        liveClose:   <?= json_encode($_t($T['live_close'])) ?>,
        liveEmpty:   <?= json_encode($_t($T['live_empty'])) ?>,
        dataScattoLabelReq:     <?= json_encode($_t($T['data_scatto_label_req'])) ?>,
        dataScattoLabelOpt:     <?= json_encode($_t($T['data_scatto_label_opt'])) ?>,
        dataScattoHintPolaroid: <?= json_encode($_t($T['data_scatto_hint_polaroid'])) ?>,
        dataScattoHintAltri:    <?= json_encode($_t($T['data_scatto_hint_altri'])) ?>,
        uploading:   <?= json_encode($_t($T['btn_uploading'])) ?>,
        upload:      <?= json_encode($_t($T['btn_upload'])) ?>,
        noPhotos:    <?= json_encode($_t($T['no_photos'])) ?>,
        pendingBadge: <?= json_encode($_t($T['pending_badge'])) ?>,
        rejectedBadge:<?= json_encode($_t($T['rejected_badge'])) ?>,
        albumLabels: {
            polaroid:  <?= json_encode($_t($T['tab_polaroid'])) ?>,
            dettaglio: <?= json_encode($_t($T['tab_dettaglio'])) ?>,
            portfolio: <?= json_encode($_t($T['tab_portfolio'])) ?>,
            eventi:    <?= json_encode($_t($T['tab_eventi'])) ?>,
        },
        guidaRuoloIntro:     <?= json_encode($_t($T['guida_ruolo_intro'])) ?>,
        guidaPolaroidObblig: <?= json_encode($_t($T['guida_ruolo_polaroid'])) ?>,
        complLabel:          <?= json_encode($_t($T['compl_label'])) ?>,
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
$tse_js_path = get_stylesheet_directory() . '/assets/talent-self-edit.js';
$tse_js_ver  = file_exists($tse_js_path) ? filemtime($tse_js_path) : '2.1';
?>
<script src="<?= esc_url($theme_uri . '/assets/talent-self-edit.js') ?>?v=<?= $tse_js_ver ?>" defer></script>

<?php toa_component('footer'); ?>
