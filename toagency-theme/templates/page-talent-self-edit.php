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
    'section_profilo' => ['it'=>'Profilo professionale','en'=>'Professional profile','fr'=>'Profil professionnel','es'=>'Perfil profesional'],
    'field_occhi'     => ['it'=>'Colore occhi *','en'=>'Eye color *','fr'=>'Couleur des yeux *','es'=>'Color de ojos *'],
    'field_sesso'     => ['it'=>'Sesso *','en'=>'Gender *','fr'=>'Genre *','es'=>'Género *'],
    'field_etnia'     => ['it'=>'Etnia * (max 2)','en'=>'Ethnicity * (max 2)','fr'=>'Ethnie * (max 2)','es'=>'Etnia * (máx 2)'],
    'field_ruoli'     => ['it'=>'Ruoli * (uno o più)','en'=>'Roles * (one or more)','fr'=>'Rôles * (un ou plusieurs)','es'=>'Roles * (uno o más)'],
    'field_lingue'    => ['it'=>'Lingue parlate','en'=>'Spoken languages','fr'=>'Langues parlées','es'=>'Idiomas'],
    // FIX 2026-08-08 marco — tolta "mostra ai clienti" (decide lo staff, non il talent); aggiunto livello+certificazioni per lingua
    'lingue_dettaglio_hint' => ['it'=>'Per ogni lingua scelta, indica il livello e se hai un certificato.','en'=>'For each language you pick, add your level and whether you have a certificate.','fr'=>'Pour chaque langue choisie, indique ton niveau et si tu as un certificat.','es'=>'Para cada idioma elegido, indica tu nivel y si tienes un certificado.'],
    'livello_select'  => ['it'=>'Livello…','en'=>'Level…','fr'=>'Niveau…','es'=>'Nivel…'],
    'livello_nativo'  => ['it'=>'Madrelingua','en'=>'Native','fr'=>'Langue maternelle','es'=>'Nativo'],
    // FIX 2026-09-29 ticket #369 — livelli con etichette (mai codici CEFR al talent) + certificato si/no, come il modulo candidatura (fonte CRM: lib/lingue_talent.php)
    'cert_placeholder'=> ['it'=>'Quale certificato? (es. IELTS 7)','en'=>'Which certificate? (e.g. IELTS 7)','fr'=>'Quel certificat ? (ex. IELTS 7)','es'=>'¿Qué certificado? (p. ej. IELTS 7)'],
    'livelli_label'   => [
        'it'=>['A1'=>'Base','A2'=>'Elementare','B1'=>'Intermedio','B2'=>'Buono','C1'=>'Fluente','C2'=>'Eccellente','nativo'=>'Madrelingua / bilingue'],
        'en'=>['A1'=>'Basic','A2'=>'Elementary','B1'=>'Intermediate','B2'=>'Good','C1'=>'Fluent','C2'=>'Excellent','nativo'=>'Native / bilingual'],
        'fr'=>['A1'=>'Notions','A2'=>'Élémentaire','B1'=>'Intermédiaire','B2'=>'Bon','C1'=>'Courant','C2'=>'Excellent','nativo'=>'Langue maternelle / bilingue'],
        'es'=>['A1'=>'Básico','A2'=>'Elemental','B1'=>'Intermedio','B2'=>'Bueno','C1'=>'Fluido','C2'=>'Excelente','nativo'=>'Nativo / bilingüe'],
    ],
    'cert_vuoto'      => ['it'=>'Certificato?','en'=>'Certificate?','fr'=>'Certificat ?','es'=>'¿Certificado?'],
    'cert_no'         => ['it'=>'Nessun certificato','en'=>'No certificate','fr'=>'Aucun certificat','es'=>'Sin certificado'],
    'cert_si'         => ['it'=>'Sì, ho un certificato','en'=>'Yes, I have a certificate','fr'=>'Oui, j\'ai un certificat','es'=>'Sí, tengo un certificado'],
    'err_cert'        => ['it'=>'Indica quale certificato hai, oppure scegli "Nessun certificato".','en'=>'Say which certificate you have, or pick "No certificate".','fr'=>'Indique quel certificat tu as, ou choisis « Aucun certificat ».','es'=>'Indica qué certificado tienes, o elige «Sin certificado».'],
    'altro_lingua_placeholder' => ['it'=>'Quali lingue? (es: Rumeno B2, Polacco A2)','en'=>'Which languages? (e.g. Romanian B2, Polish A2)','fr'=>'Quelles langues ? (ex : Roumain B2, Polonais A2)','es'=>'¿Qué idiomas? (ej: Rumano B2, Polaco A2)'],
    // FIX 2026-08-08 marco — paese di residenza, come in registrazione (prima paese poi comune/città)
    'field_paese_residenza' => ['it'=>'Paese di residenza','en'=>'Country of residence','fr'=>'Pays de résidence','es'=>'País de residencia'],
    'opt_select_paese' => ['it'=>'Seleziona paese…','en'=>'Select country…','fr'=>'Choisir le pays…','es'=>'Selecciona país…'],
    'field_citta_estero' => ['it'=>'Città','en'=>'City','fr'=>'Ville','es'=>'Ciudad'],
    'citta_estero_placeholder' => ['it'=>'Es. New York, Tokyo, Londra…','en'=>'E.g. New York, Tokyo, London…','fr'=>'Ex. New York, Tokyo, Londres…','es'=>'Ej. Nueva York, Tokio, Londres…'],
    'field_patente'   => ['it'=>'Ho la patente di guida','en'=>'I have a driving license','fr'=>'J’ai le permis de conduire','es'=>'Tengo carné de conducir'],
    'field_automunito'=> ['it'=>'Sono automunito/a','en'=>'I have my own vehicle','fr'=>'Je suis véhiculé(e)','es'=>'Tengo vehículo propio'],

    // ─── S8.A — Sezione album foto ───
    'section_foto'   => ['it'=>'Le tue foto','en'=>'Your photos','fr'=>'Tes photos','es'=>'Tus fotos'],
    'foto_subtitle'  => [
        'it'=>'Carica foto in 5 album diversi, ognuno con uno scopo diverso — leggi la spiegazione sopra alle foto per capire quale usare. Ogni foto è verificata dallo staff prima di essere pubblicata.',
        'en'=>'Upload photos in 5 different albums, each with its own purpose — read the note above the photos to know which to use. Each photo is reviewed by staff before publication.',
        'fr'=>'Charge des photos dans 5 albums différents, chacun avec un objectif précis — lis l\'explication au-dessus des photos pour savoir lequel utiliser. Chaque photo est revue avant publication.',
        'es'=>'Sube fotos en 5 álbumes distintos, cada uno con un propósito — lee la explicación encima de las fotos para saber cuál usar. Cada foto se revisa antes de publicarse.',
    ],
    // FIX 2026-08-08 marco — niente loghi/watermark/firme fotografo: le foto non sarebbero utilizzabili
    'no_watermark_warning' => [
        'it'=>'⚠️ Niente loghi, watermark o firme di fotografi su foto e video: se ci sono non possiamo usarli (non possiamo ritagliarli o modificarli a piacere).',
        'en'=>'⚠️ No logos, watermarks or photographer signatures on photos/videos: if present we cannot use them (we cannot crop or edit them as we like).',
        'fr'=>'⚠️ Pas de logos, filigranes ou signatures de photographe sur les photos/vidéos : si présents, on ne peut pas les utiliser (on ne peut pas les recadrer ou les modifier à notre gré).',
        'es'=>'⚠️ Nada de logos, marcas de agua o firmas de fotógrafo en fotos/vídeos: si están, no podemos usarlos (no podemos recortarlos o editarlos a nuestro gusto).',
    ],
    'tab_polaroid'   => ['it'=>'Polaroid','en'=>'Polaroid','fr'=>'Polaroid','es'=>'Polaroid'],
    'tab_dettaglio'  => ['it'=>'Dettagli','en'=>'Details','fr'=>'Détails','es'=>'Detalles'],
    'tab_portfolio'  => ['it'=>'Portfolio','en'=>'Portfolio','fr'=>'Portfolio','es'=>'Portfolio'],
    'tab_eventi'     => ['it'=>'Eventi','en'=>'Events','fr'=>'Événements','es'=>'Eventos'],
    'tab_casual'     => ['it'=>'Altre foto','en'=>'Other photos','fr'=>'Autres photos','es'=>'Otras fotos'],
    'guida_ruolo_intro'    => ['it'=>'Album consigliati per il tuo profilo','en'=>'Recommended albums for your profile','fr'=>'Albums recommandés pour ton profil','es'=>'Álbumes recomendados para tu perfil'],
    'guida_ruolo_polaroid' => ['it'=>'Le Polaroid sono obbligatorie per tutti.','en'=>'Polaroids are required for everyone.','fr'=>'Les Polaroids sont obligatoires pour tous.','es'=>'Las Polaroids son obligatorias para todos.'],
    'compl_label'          => ['it'=>'Profilo completo','en'=>'Profile complete','fr'=>'Profil complété','es'=>'Perfil completo'],
    // FIX 2026-09-15 marco — card di stato prima del form (Step 2 pagina-stato, chat CRM-MINORI-EMAIL-BATTENTI)
    'statuscard_public'         => ['it'=>'Scheda attiva e pubblica','en'=>'Profile active and public','fr'=>'Fiche active et publique','es'=>'Ficha activa y pública'],
    'statuscard_review'         => ['it'=>'Scheda in revisione, non ancora online','en'=>'Profile under review, not live yet','fr'=>'Fiche en cours de vérification, pas encore en ligne','es'=>'Ficha en revisión, aún no está en línea'],
    'statuscard_photos_pending' => ['it'=>'{n} foto in attesa di approvazione','en'=>'{n} photo(s) awaiting approval','fr'=>'{n} photo(s) en attente de validation','es'=>'{n} foto(s) pendientes de aprobación'],
    'btn_modifica_scheda'       => ['it'=>'Modifica la tua scheda','en'=>'Edit your profile','fr'=>'Modifier ta fiche','es'=>'Editar tu ficha'],
    'mancano_titolo'                 => ['it'=>'Da completare','en'=>'To complete','fr'=>'À compléter','es'=>'Por completar'],
    'mancano_telefono'               => ['it'=>'Telefono','en'=>'Phone','fr'=>'Téléphone','es'=>'Teléfono'],
    'mancano_data_nascita'           => ['it'=>'Data di nascita','en'=>'Date of birth','fr'=>'Date de naissance','es'=>'Fecha de nacimiento'],
    'mancano_tratti'                 => ['it'=>'Tratti (occhi, capelli)','en'=>'Traits (eyes, hair)','fr'=>'Traits (yeux, cheveux)','es'=>'Rasgos (ojos, pelo)'],
    'mancano_polaroid'               => ['it'=>'Foto polaroid','en'=>'Polaroid photos','fr'=>'Photos polaroid','es'=>'Fotos polaroid'],
    'mancano_polaroid_da_aggiornare' => ['it'=>'Polaroid da aggiornare','en'=>'Polaroids to update','fr'=>'Polaroids à mettre à jour','es'=>'Polaroids por actualizar'],
    'mancano_foto_portfolio'         => ['it'=>'Foto portfolio','en'=>'Portfolio photos','fr'=>'Photos portfolio','es'=>'Fotos portfolio'],
    'mancano_foto_dettaglio'         => ['it'=>'Foto dettaglio','en'=>'Detail photos','fr'=>'Photos détail','es'=>'Fotos detalle'],
    'mancano_foto_eventi'            => ['it'=>'Foto eventi','en'=>'Event photos','fr'=>'Photos événements','es'=>'Fotos eventos'],
    'mancano_misure'                 => ['it'=>'Misure','en'=>'Measurements','fr'=>'Mensurations','es'=>'Medidas'],
    'polscad_title' => ['it'=>'Polaroid da aggiornare','en'=>'Polaroids to update','fr'=>'Polaroids à mettre à jour','es'=>'Polaroids por actualizar'],
    'polscad_sub'   => ['it'=>'Le tue polaroid sono troppo vecchie: caricane di recenti per restare visibile ai casting. Clicca qui ↓','en'=>'Your polaroids are too old: upload recent ones to stay visible to castings. Click here ↓','fr'=>'Tes polaroids sont trop anciennes : charges-en des récentes pour rester visible aux castings. Clique ici ↓','es'=>'Tus polaroids son demasiado antiguas: sube fotos recientes para seguir visible en los castings. Haz clic aquí ↓'],
    // FIX 2026-08-08 marco — descrizioni riscritte per spiegare anche A COSA SERVE l'album, non solo il contenuto
    'album_desc' => [
        'polaroid'  => ['it'=>'Foto vere, senza trucco/filtri, che mostrano il tuo aspetto reale. Obbligatorie per tutti: sono le prime che guardano i clienti.','en'=>'Real photos, no make-up/filters, showing your actual look. Required for everyone — the first thing clients see.','fr'=>'Photos vraies, sans maquillage/filtres, qui montrent ton apparence réelle. Obligatoires pour tous : ce sont les premières que voient les clients.','es'=>'Fotos reales, sin maquillaje/filtros, que muestran tu aspecto real. Obligatorias para todos: son lo primero que ven los clientes.'],
        'dettaglio' => ['it'=>'Primi piani di mani, occhi, sorriso, profilo — utili se ti proponi per lavori dove contano i dettagli (es. spot mani/gioielli, primi piani).','en'=>'Close-ups of hands, eyes, smile, profile — useful if you go for jobs where details matter (e.g. hand/jewelry ads, close-ups).','fr'=>'Gros plans mains, yeux, sourire, profil — utiles si tu vises des jobs où les détails comptent (ex. pub mains/bijoux, gros plans).','es'=>'Primeros planos de manos, ojos, sonrisa, perfil — útiles si buscas trabajos donde importan los detalles (ej. anuncios de manos/joyas, primeros planos).'],
        'portfolio' => ['it'=>'Foto professionali di shooting o lavori già fatti — utili se punti a moda, cinema, spot, campagne pubblicitarie.','en'=>'Professional photos from shoots or past work — useful if you aim for fashion, film, ads, campaigns.','fr'=>'Photos professionnelles de shootings ou travaux déjà réalisés — utiles si tu vises la mode, le cinéma, la pub, les campagnes.','es'=>'Fotos profesionales de shootings o trabajos ya hechos — útiles si apuntas a moda, cine, spots, campañas.'],
        'eventi'    => ['it'=>'Foto scattate a eventi, red carpet, fiere — utili se vuoi lavorare come hostess/steward o cerchi ingaggi in ambito eventi.','en'=>'Photos taken at events, red carpets, fairs — useful if you want to work as hostess/steward or look for event-based jobs.','fr'=>'Photos prises lors d\'événements, tapis rouge, salons — utiles si tu veux travailler comme hôtesse/steward ou chercher des missions événementielles.','es'=>'Fotos tomadas en eventos, alfombra roja, ferias — útiles si quieres trabajar como azafata/o o buscas trabajos en eventos.'],
        'casual'    => ['it'=>'Foto semplici, anche da smartphone, senza posa (vacanza, vita di tutti i giorni) — non rientrano negli altri album ma aiutano i clienti a farsi un\'idea di te.','en'=>'Simple photos, even from your phone, no posing (holidays, everyday life) — don\'t fit the other albums but help clients get a feel for you.','fr'=>'Photos simples, même au téléphone, sans pose (vacances, vie quotidienne) — n\'entrent pas dans les autres albums mais aident les clients à te connaître.','es'=>'Fotos simples, incluso de móvil, sin posar (vacaciones, vida diaria) — no entran en los otros álbumes pero ayudan a los clientes a conocerte.'],
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
    'touch_hint'        => [
        'it'=>'Tocca 🗑 su una foto per eliminarla, ↔ per spostarla in un altro album.',
        'en'=>'Tap 🗑 on a photo to delete it, or ↔ to move it to another album.',
        'fr'=>'Touche 🗑 sur une photo pour la supprimer, ↔ pour la déplacer dans un autre album.',
        'es'=>'Toca 🗑 en una foto para eliminarla, o ↔ para moverla a otro álbum.',
    ],
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
    'verita_casual'    => ['it'=>'Confermo che questa foto è mia e rappresenta il mio aspetto attuale','en'=>'I confirm this photo is mine and represents my current appearance','fr'=>'Je confirme que cette photo est la mienne et représente mon apparence actuelle','es'=>'Confirmo que esta foto es mía y representa mi apariencia actual'],
];

// FIX 2026-09-22 marco (chat CRM - EVENTS DATABASE) — album: STESSA definizione della registrazione
// (nomi, a cosa servono, ruoli). File condiviso: templates/_talent-albums.php
require_once __DIR__ . '/_talent-albums.php';
$SE_ALBUM_DEFS = array();
foreach ($TALENT_ALBUM as $__al) {
    if ($__al['code'] === 'ugc') continue; // UGC = video: ha la sua sezione video
    $SE_ALBUM_DEFS[] = array(
        'code'   => $__al['code'],
        'roles'  => $__al['roles'],
        'label'  => $_t($__al['label']),
        'quante' => isset($__al['quante']) ? $_t($__al['quante']) : '',
        'hint'   => isset($__al['hint'])   ? $_t($__al['hint'])   : '',
    );
}
$T2 = [
    'btn_foto'     => ['it'=>'📸 Le mie foto','en'=>'📸 My photos','fr'=>'📸 Mes photos','es'=>'📸 Mis fotos'],
    'foto_sub'     => ['it'=>'Ogni album ha uno scopo: apri l\'album giusto, leggi a cosa serve e poi carica. Gli album utili per il tuo profilo hanno il pallino verde. Ogni foto viene verificata dallo staff prima di essere pubblicata.','en'=>'Each album has a purpose: open the right album, read what it is for, then upload. Albums useful for your profile have a green dot. Every photo is checked by our staff before it goes live.','fr'=>'Chaque album a un but : ouvre le bon album, lis à quoi il sert, puis charge. Les albums utiles pour ton profil ont un point vert. Chaque photo est vérifiée par l\'équipe avant publication.','es'=>'Cada álbum tiene un fin: abre el álbum correcto, lee para qué sirve y luego sube. Los álbumes útiles para tu perfil tienen un punto verde. Cada foto la revisa el equipo antes de publicarla.'],
    'del'          => ['it'=>'Elimina','en'=>'Delete','fr'=>'Supprimer','es'=>'Eliminar'],
    'move'         => ['it'=>'Sposta','en'=>'Move','fr'=>'Déplacer','es'=>'Mover'],
    'confirm_del'  => ['it'=>'Eliminare questa foto? Non si può annullare.','en'=>'Delete this photo? This cannot be undone.','fr'=>'Supprimer cette photo ? C\'est définitif.','es'=>'¿Eliminar esta foto? No se puede deshacer.'],
    'del_error'    => ['it'=>'Non siamo riusciti a eliminare la foto. Riprova tra poco o scrivici su WhatsApp.','en'=>'We could not delete the photo. Try again shortly or message us on WhatsApp.','fr'=>'Impossible de supprimer la photo. Réessaie plus tard ou écris-nous sur WhatsApp.','es'=>'No hemos podido eliminar la foto. Inténtalo más tarde o escríbenos por WhatsApp.'],
    'add_photo'    => ['it'=>'+ Aggiungi foto in questo album','en'=>'+ Add photos to this album','fr'=>'+ Ajouter des photos à cet album','es'=>'+ Añadir fotos a este álbum'],
    'close_upload' => ['it'=>'Chiudi','en'=>'Close','fr'=>'Fermer','es'=>'Cerrar'],
    'state_pending'=> ['it'=>'In verifica','en'=>'Under review','fr'=>'En vérification','es'=>'En revisión'],
    'state_rejected'=> ['it'=>'Rifiutata','en'=>'Rejected','fr'=>'Refusée','es'=>'Rechazada'],
    'princ_badge'  => ['it'=>'⭐ Foto principale','en'=>'⭐ Main photo','fr'=>'⭐ Photo principale','es'=>'⭐ Foto principal'],
    'set_princ'    => ['it'=>'⭐ Metti principale','en'=>'⭐ Make main','fr'=>'⭐ Mettre principale','es'=>'⭐ Poner principal'],
    'cover_ev_badge'=> ['it'=>'⭐ Copertina eventi','en'=>'⭐ Events cover','fr'=>'⭐ Couverture événements','es'=>'⭐ Portada eventos'],
    'set_cover_ev' => ['it'=>'⭐ Usa come copertina','en'=>'⭐ Use as cover','fr'=>'⭐ Mettre en couverture','es'=>'⭐ Usar de portada'],
    'no_del_princ' => ['it'=>'Questa è la foto che vedono i clienti. Prima scegline un\'altra con "Metti principale", poi potrai eliminarla.','en'=>'This is the photo clients see. First choose another one with "Make main", then you can delete it.','fr'=>'C\'est la photo que voient les clients. Choisis d\'abord une autre avec "Mettre principale", puis tu pourras la supprimer.','es'=>'Esta es la foto que ven los clientes. Primero elige otra con "Poner principal" y luego podrás eliminarla.'],
    'princ_pending'=> ['it'=>'Questa foto è ancora in verifica: potrai metterla come principale appena lo staff l\'avrà approvata.','en'=>'This photo is still under review: you can make it your main photo once our staff approves it.','fr'=>'Cette photo est encore en vérification : tu pourras la mettre en principale dès que l\'équipe l\'aura validée.','es'=>'Esta foto aún está en revisión: podrás ponerla como principal cuando el equipo la apruebe.'],
    'princ_ok'     => ['it'=>'Fatto: questa è ora la foto che vedono i clienti.','en'=>'Done: this is now the photo clients see.','fr'=>'C\'est fait : c\'est maintenant la photo que voient les clients.','es'=>'Hecho: ahora es la foto que ven los clientes.'],
    'princ_missing'=> ['it'=>'La foto che vedono oggi i clienti non è in nessuno di questi album. Se vuoi cambiarla, tocca "⭐ Metti principale" sotto una foto approvata.','en'=>'The photo clients see today is not in any of these albums. To change it, tap "⭐ Make main" under an approved photo.','fr'=>'La photo que voient aujourd\'hui les clients n\'est dans aucun de ces albums. Pour la changer, touche "⭐ Mettre principale" sous une photo validée.','es'=>'La foto que ven hoy los clientes no está en ninguno de estos álbumes. Para cambiarla, toca "⭐ Poner principal" bajo una foto aprobada.'],
    'touch_hint2'  => ['it'=>'Sotto ogni foto trovi Elimina e Sposta. Tocca la foto per vederla grande.','en'=>'Under each photo you find Delete and Move. Tap the photo to see it bigger.','fr'=>'Sous chaque photo : Supprimer et Déplacer. Touche la photo pour l\'agrandir.','es'=>'Debajo de cada foto tienes Eliminar y Mover. Toca la foto para verla grande.'],
    // ─── FIX 2026-09-22 marco (CRM - EVENTS DATABASE, Fase 2) — sezione Hostess & Eventi ───
    'ev_title'     => ['it'=>'Hostess & Eventi','en'=>'Hostess & Events','fr'=>'Hôtesses & Événements','es'=>'Azafatas & Eventos'],
    'ev_rules'     => ['it'=>'Per lavorare a fiere, congressi ed eventi ci servono foto nell\'album Fiere e eventi: tailleur o camicia elegante, trucco leggero, sguardo in camera. Vanno bene anche un selfie o una foto col telefono, basta che sia luminosa e nitida. Se fai eventi sportivi, motori o ombrellina (tipo EICMA), aggiungi anche una foto in stile sportivo.','en'=>'To work at trade fairs, conferences and events we need photos in the Trade fairs & events album: suit or smart shirt, light makeup, looking at the camera. A selfie or phone photo is fine too, as long as it is bright and sharp. If you do sports, motor-show or umbrella-girl events (like EICMA), add a sporty photo too.','fr'=>'Pour travailler sur des salons, congrès et événements, il nous faut des photos dans l\'album Salons et événements : tailleur ou chemise élégante, maquillage léger, regard vers l\'objectif. Un selfie ou une photo au téléphone convient aussi, si elle est lumineuse et nette. Si tu fais des événements sportifs, moteurs ou ombrelle (type EICMA), ajoute aussi une photo en style sportif.','es'=>'Para trabajar en ferias, congresos y eventos necesitamos fotos en el álbum Ferias y eventos: traje o camisa elegante, maquillaje ligero, mirada a cámara. También vale un selfie o una foto con el móvil, si es luminosa y nítida. Si haces eventos deportivos, de motor o de paraguas (tipo EICMA), añade también una foto deportiva.'],
    'ev_open_album'=> ['it'=>'📸 Apri l\'album Fiere e eventi','en'=>'📸 Open the Trade fairs & events album','fr'=>'📸 Ouvrir l\'album Salons et événements','es'=>'📸 Abrir el álbum Ferias y eventos'],
    'ev_nofoto'    => ['it'=>'Non hai ancora foto nell\'album Fiere e eventi: senza, facciamo fatica a proporti per gli eventi.','en'=>'You have no photos in the Trade fairs & events album yet: without them we can hardly propose you for events.','fr'=>'Tu n\'as pas encore de photos dans l\'album Salons et événements : sans elles, on peut difficilement te proposer pour des événements.','es'=>'Aún no tienes fotos en el álbum Ferias y eventos: sin ellas nos cuesta proponerte para eventos.'],
    'ev_cta'       => ['it'=>'Vuoi lavorare anche a fiere ed eventi come hostess o steward?','en'=>'Do you also want to work at fairs and events as a hostess or steward?','fr'=>'Tu veux aussi travailler sur des salons et événements comme hôtesse ou steward ?','es'=>'¿Quieres trabajar también en ferias y eventos como azafata o steward?'],
    'ev_cta_btn'   => ['it'=>'+ Aggiungi Hostess/Steward','en'=>'+ Add Hostess/Steward','fr'=>'+ Ajouter Hôtesse/Steward','es'=>'+ Añadir Azafata/Steward'],
    'ev_tipi'      => ['it'=>'Eventi che hai già fatto','en'=>'Events you have already done','fr'=>'Événements que tu as déjà faits','es'=>'Eventos que ya has hecho'],
    'ev_anni'      => ['it'=>'Esperienza come hostess/steward','en'=>'Experience as hostess/steward','fr'=>'Expérience comme hôtesse/steward','es'=>'Experiencia como azafata/steward'],
    'ev_cert'      => ['it'=>'Certificati','en'=>'Certificates','fr'=>'Certificats','es'=>'Certificados'],
    'ev_cert_altro'=> ['it'=>'Altri certificati o corsi (facoltativo)','en'=>'Other certificates or courses (optional)','fr'=>'Autres certificats ou formations (facultatif)','es'=>'Otros certificados o cursos (opcional)'],
    'ev_stile_el'  => ['it'=>'👔 Elegante','en'=>'👔 Smart','fr'=>'👔 Élégant','es'=>'👔 Elegante'],
    'ev_stile_sp'  => ['it'=>'🏁 Sportivo','en'=>'🏁 Sporty','fr'=>'🏁 Sportif','es'=>'🏁 Deportivo'],
];
$EV_TIPI = [
    'fiere'=>['it'=>'Fiere','en'=>'Trade fairs','fr'=>'Salons','es'=>'Ferias'],
    'congressi'=>['it'=>'Congressi','en'=>'Conferences','fr'=>'Congrès','es'=>'Congresos'],
    'sport_motori'=>['it'=>'Sport e motori','en'=>'Sports & motor shows','fr'=>'Sport et moteurs','es'=>'Deporte y motor'],
    'promo'=>['it'=>'Promo e sampling','en'=>'Promo & sampling','fr'=>'Promo et sampling','es'=>'Promo y sampling'],
    'gala'=>['it'=>'Gala e cene','en'=>'Galas & dinners','fr'=>'Galas et dîners','es'=>'Galas y cenas'],
    'accoglienza'=>['it'=>'Accoglienza e reception','en'=>'Welcome & reception','fr'=>'Accueil et réception','es'=>'Recepción'],
    'sfilate_showroom'=>['it'=>'Sfilate e showroom','en'=>'Fashion shows & showrooms','fr'=>'Défilés et showrooms','es'=>'Desfiles y showrooms'],
];
$EV_ANNI = ['0'=>['it'=>'Nessuna, sto iniziando','en'=>'None yet, just starting','fr'=>'Aucune, je commence','es'=>'Ninguna, estoy empezando'],'1'=>['it'=>'1 anno','en'=>'1 year','fr'=>'1 an','es'=>'1 año'],'2-3'=>['it'=>'2-3 anni','en'=>'2-3 years','fr'=>'2-3 ans','es'=>'2-3 años'],'4+'=>['it'=>'4 anni o più','en'=>'4+ years','fr'=>'4 ans ou plus','es'=>'4 años o más']];
$EV_CERT = ['haccp'=>['it'=>'HACCP','en'=>'HACCP','fr'=>'HACCP','es'=>'HACCP'],'primo_soccorso'=>['it'=>'Primo soccorso','en'=>'First aid','fr'=>'Premiers secours','es'=>'Primeros auxilios'],'sicurezza'=>['it'=>'Sicurezza sul lavoro','en'=>'Workplace safety','fr'=>'Sécurité au travail','es'=>'Seguridad laboral'],'antincendio'=>['it'=>'Antincendio','en'=>'Fire safety','fr'=>'Incendie','es'=>'Antiincendios']];

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
];
$TAGLIE_OPTS = ['XS','S','M','L','XL','XXL'];
$OCCHI_OPTS = [
    'azzurri' => ['it'=>'Azzurri','en'=>'Blue','fr'=>'Bleus','es'=>'Azules'],
    'verdi'   => ['it'=>'Verdi','en'=>'Green','fr'=>'Verts','es'=>'Verdes'],
    'marroni' => ['it'=>'Marroni','en'=>'Brown','fr'=>'Marron','es'=>'Marrones'],
    'neri'    => ['it'=>'Neri','en'=>'Black','fr'=>'Noirs','es'=>'Negros'],
    'grigi'   => ['it'=>'Grigi','en'=>'Gray','fr'=>'Gris','es'=>'Grises'],
];
// FEATURE 2026-09-19 marco — sesso finalmente editabile da self-edit (fix loop infinito + whitelist
// backend gia' pronti il 19/9; mancava solo questo campo nel form). Valori canonici da lib/sesso.php.
$SESSO_OPTS = [
    'maschio' => ['it'=>'Maschio','en'=>'Male','fr'=>'Homme','es'=>'Hombre'],
    'femmina' => ['it'=>'Femmina','en'=>'Female','fr'=>'Femme','es'=>'Mujer'],
    'altro'   => ['it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro'],
];
$ETNIA_OPTS = [
    'caucasica'    => ['it'=>'Caucasica','en'=>'Caucasian','fr'=>'Caucasienne','es'=>'Caucásica'],
    'africana'     => ['it'=>'Africana','en'=>'African','fr'=>'Africaine','es'=>'Africana'],
    'asiatica'     => ['it'=>'Asiatica','en'=>'Asian','fr'=>'Asiatique','es'=>'Asiática'],
    'sud_asiatica' => ['it'=>'Sud-asiatica','en'=>'South Asian','fr'=>'Sud-asiatique','es'=>'Sur-asiática'],
    'latina'       => ['it'=>'Latina','en'=>'Latina','fr'=>'Latina','es'=>'Latina'],
    'araba'        => ['it'=>'Araba','en'=>'Arab','fr'=>'Arabe','es'=>'Árabe'],
];
$RUOLI_OPTS = [
    'model'       => ['it'=>'Modello/a','en'=>'Model','fr'=>'Mannequin','es'=>'Modelo/a'],
    'actor'       => ['it'=>'Attore/trice','en'=>'Actor/Actress','fr'=>'Acteur/trice','es'=>'Actor/Actriz'],
    'hostess'     => ['it'=>'Hostess/Steward','en'=>'Hostess/Steward','fr'=>'Hôtesse/Steward','es'=>'Azafata/o'],
    'comparsa'    => ['it'=>'Comparsa','en'=>'Extra','fr'=>'Figurant','es'=>'Extra'],
    'bambino'     => ['it'=>'Bambino/a','en'=>'Child','fr'=>'Enfant','es'=>'Niño/a'],
    'influencer'  => ['it'=>'Influencer/Creator','en'=>'Influencer/Creator','fr'=>'Influenceur/Créateur','es'=>'Influencer/Creador'],
    'ugc_creator' => ['it'=>'UGC Creator','en'=>'UGC Creator','fr'=>'Créateur UGC','es'=>'Creador UGC'],
];
$LINGUE_OPTS = [
    'italiano'   => ['it'=>'Italiano','en'=>'Italian','fr'=>'Italien','es'=>'Italiano'],
    'inglese'    => ['it'=>'Inglese','en'=>'English','fr'=>'Anglais','es'=>'Inglés'],
    'francese'   => ['it'=>'Francese','en'=>'French','fr'=>'Français','es'=>'Francés'],
    'spagnolo'   => ['it'=>'Spagnolo','en'=>'Spanish','fr'=>'Espagnol','es'=>'Español'],
    'tedesco'    => ['it'=>'Tedesco','en'=>'German','fr'=>'Allemand','es'=>'Alemán'],
    'portoghese' => ['it'=>'Portoghese','en'=>'Portuguese','fr'=>'Portugais','es'=>'Portugués'],
    'russo'      => ['it'=>'Russo','en'=>'Russian','fr'=>'Russe','es'=>'Ruso'],
    'cinese'     => ['it'=>'Cinese','en'=>'Chinese','fr'=>'Chinois','es'=>'Chino'],
    'arabo'      => ['it'=>'Arabo','en'=>'Arabic','fr'=>'Arabe','es'=>'Árabe'],
    'altro'      => ['it'=>'Altro','en'=>'Other','fr'=>'Autre','es'=>'Otro'],
];

// FIX 2026-08-08 marco — prefissi telefonici da tendina (stessa fonte dati della registrazione, niente testo libero)
$theme_uri = get_stylesheet_directory_uri();
$PREFISSI_OPTS = [];
$prefissi_path = get_stylesheet_directory() . '/assets/data/phone-prefixes.json';
if (file_exists($prefissi_path)) {
    $prefissi_json = json_decode(file_get_contents($prefissi_path), true);
    if (is_array($prefissi_json)) $PREFISSI_OPTS = $prefissi_json;
}
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
.tse-manca-wrap { margin-top:12px; }
.tse-manca-title { font-size:11px; color:#9ca3af; text-transform:uppercase; letter-spacing:.5px; font-weight:700; margin-bottom:8px; }
.tse-manca-chips { display:flex; flex-wrap:wrap; gap:6px; }
.tse-manca-chip { display:inline-block; font-size:11px; color:#ffb300; background:rgba(255,179,0,.10); border:1px solid rgba(255,179,0,.30); border-radius:99px; padding:4px 10px; }
/* FIX 2026-09-15 marco — card di stato (Step 2 pagina-stato): prima cosa che il talent vede, prima del form pesante */
.tse-statuscard { margin:0 0 24px; padding:20px; background:#0f0f12; border:1px solid #2a2a2e; border-radius:10px; }
.tse-status-row { display:flex; align-items:center; gap:10px; padding:12px 14px; border-radius:8px; margin-bottom:8px; font-size:13px; font-weight:600; }
.tse-status-ok { background:rgba(200,255,0,.08); border:1px solid rgba(200,255,0,.3); color:#c8ff00; }
.tse-status-review, .tse-status-pending { background:rgba(255,179,0,.08); border:1px solid rgba(255,179,0,.35); color:#ffb300; }
.tse-status-info { background:rgba(255,255,255,.04); border:1px solid #2a2a2e; color:#9ca3af; font-weight:400; }
.tse-status-dot { width:8px; height:8px; border-radius:50%; flex:0 0 auto; }
.tse-status-dot-ok { background:#c8ff00; }
.tse-status-dot-review, .tse-status-dot-pending { background:#ffb300; }
.tse-chips { display:flex; flex-wrap:wrap; gap:8px; }
.tse-chip { display:inline-flex; align-items:center; gap:6px; font-size:13px; color:#e5e7eb; background:#1a1a1e; border:1px solid #2a2a2e; border-radius:99px; padding:7px 12px; cursor:pointer; user-select:none; }
.tse-chip input { accent-color:#c8ff00; margin:0; }
.tse-chip:hover { border-color:#c8ff00; }
/* TEMA 26/08 — "Bambino/a" non è selezionabile a mano: lo mette JS in base alla data di nascita (<18) */
.tse-chip-auto { display:none !important; }
.tse-check-row { display:flex; align-items:center; gap:8px; font-size:13px; color:#e5e7eb; cursor:pointer; }
.tse-check-row input { accent-color:#c8ff00; }
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
/* FIX 2026-09-06 — TOUCH: senza :hover i bottoni 🗑/↔ erano invisibili su telefono */
.tse-touch-hint { display:none; font-size:12px; color:#9ca3af; margin:0 0 16px; line-height:1.45; padding:8px 10px; background:#0a0a0a; border-radius:6px; border-left:3px solid #c8ff00; }
@media (hover: none) {
    .tse-thumb-actions { opacity:1; }
    .tse-thumb-btn { width:34px; height:34px; font-size:16px; }
    .tse-move-menu { bottom:42px; }
    .tse-touch-hint { display:block; }
}
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
.tse-pol-scadute{background:rgba(255,179,0,.10);border:1px solid rgba(255,179,0,.45);border-radius:8px;padding:14px 16px;margin-bottom:20px;display:none;cursor:pointer;text-align:center;}
.tse-pol-scadute-title{color:#ffb300;font-weight:700;font-size:14px;margin-bottom:4px;}
.tse-pol-scadute-sub{color:#9ca3af;font-size:12px;}

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
/* FIX 2026-09-22 marco (CRM - EVENTS DATABASE) — foto semplici: Elimina/Sposta SCRITTI sotto ogni foto, sempre visibili */
.tse-album-thumb { aspect-ratio:auto; overflow:visible; min-width:0; } /* min-width:0 = la griglia non sborda dalla colonna */
.tse-thumb-img img { max-width:100%; }
.tse-album-thumb:hover { transform:none; }
.tse-thumb-img { position:relative; aspect-ratio:1/1; overflow:hidden; border-radius:4px; background:#1a1a1e; }
.tse-thumb-img img { width:100%; height:100%; object-fit:cover; display:block; }
.tse-album-thumb .tse-thumb-actions { position:relative; bottom:auto; right:auto; opacity:1; display:grid; grid-template-columns:minmax(0,1fr); gap:4px; padding:5px 0 1px; } /* uno sotto l'altro: su telefono affiancati non ci stanno */
.tse-album-thumb .tse-thumb-btn { width:100%; min-width:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; height:auto; min-height:32px; font-size:12px; font-weight:700; border-radius:6px; padding:6px 2px; background:#26262b; color:#e5e7eb; border:1px solid #34343a; }
.tse-album-thumb .tse-thumb-del { background:#3a1a1a; color:#fca5a5; border-color:#5b2323; }
.tse-album-thumb .tse-move-menu { bottom:42px; left:0; right:0; min-width:0; }
.tse-thumb-state { position:absolute; left:4px; top:4px; font-size:10px; font-weight:700; padding:2px 6px; border-radius:4px; background:rgba(0,0,0,.78); color:#FFB300; }
.tse-thumb-state.rej { color:#fca5a5; }
.tse-ev-section { border:2px solid #ff8a3d !important; background:linear-gradient(180deg,rgba(255,138,61,.10),rgba(255,138,61,0) 55%) !important; }
.tse-ev-section .tse-section-title { color:#ff8a3d; font-size:14px; }
.tse-ev-rules { font-size:13px; color:#e5e7eb; line-height:1.55; margin:0 0 10px; }
.tse-ev-nofoto { font-size:13px; color:#fca5a5; background:rgba(239,68,68,.10); border:1px solid rgba(239,68,68,.35); border-radius:6px; padding:8px 10px; margin:0 0 10px; }
.tse-ev-open, .tse-ev-cta-btn { width:100%; background:#ff8a3d; color:#111; border:none; border-radius:8px; padding:12px; font-size:14px; font-weight:800; cursor:pointer; margin:0 0 16px; }
.tse-ev-cta { display:grid; gap:10px; font-size:13px; color:#e5e7eb; margin:0 0 14px; }
.tse-ev-cta[hidden], #tse-ev-hostess[hidden], .tse-ev-nofoto[hidden] { display:none; }
.tse-album-thumb .tse-thumb-stile { background:#1d2433; color:#bfdbfe; border-color:#2c3a55; }
.tse-thumb-princ { position:absolute; left:4px; bottom:4px; right:4px; font-size:10px; font-weight:800; padding:3px 6px; border-radius:4px; background:#c8ff00; color:#0a0a0a; text-align:center; }
.tse-album-thumb .tse-thumb-star { background:#2a2410; color:#ffd08a; border-color:#4a3d14; }
.tse-album-tab .tse-dot { display:inline-block; width:8px; height:8px; border-radius:50%; background:#c8ff00; margin-right:6px; vertical-align:middle; }
.tse-album-tab.active .tse-dot { background:#0a0a0a; }
.tse-add-photo-btn { width:100%; background:#c8ff00; color:#0a0a0a; border:none; padding:12px; border-radius:8px; font-size:14px; font-weight:800; cursor:pointer; margin:0 0 12px; }
.tse-upload-close { background:none; border:1px solid #3a3a42; color:#9ca3af; border-radius:6px; padding:6px 12px; font-size:12px; cursor:pointer; float:right; }
.tse-btn-foto { background:#c8ff00 !important; color:#0a0a0a !important; }
.tse-statuscard .tse-btn-save + .tse-btn-save { background:#1a1a1e; color:#e5e7eb; border:1px solid #3a3a42; }
.tse-touch-hint { display:block; }
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

        <!-- FIX 2026-09-15 marco — card di stato (Step 2 pagina-stato): prima cosa visibile, prima del form pesante -->
        <div id="tse-statuscard" class="tse-statuscard" style="display:none;">
            <div id="tse-statuscard-rows"></div>
            <button type="button" class="tse-btn-save tse-btn-foto" style="margin-top:6px;" onclick="talentShowForm('foto')"><?= esc_html($_t($T2['btn_foto'])) ?></button><!-- FIX 2026-09-22 marco -->
            <button type="button" class="tse-btn-save" style="margin-top:8px;" onclick="talentShowForm()"><?= esc_html($_t($T['btn_modifica_scheda'])) ?></button>
        </div>

        <div id="tse-photo-alert" class="tse-photo-alert" onclick="document.getElementById('tse-foto-section').scrollIntoView({behavior:'smooth'})">
            <div class="tse-photo-alert-title">📸 Manca la tua foto Polaroid!</div>
            <div class="tse-photo-alert-sub">È la foto principale del tuo profilo — clicca qui per caricarla ↓</div>
        </div>

        <div id="tse-polaroid-scadute" class="tse-pol-scadute" onclick="document.getElementById('tse-foto-section').scrollIntoView({behavior:'smooth'})">
            <div class="tse-pol-scadute-title">⏳ <?= esc_html($_t($T['polscad_title'])) ?></div>
            <div class="tse-pol-scadute-sub"><?= esc_html($_t($T['polscad_sub'])) ?></div>
        </div>

        <!-- ─── S8.A — Sezione album foto ─── -->
        <div id="tse-foto-section" class="tse-section" style="display:none; margin:0 0 20px;">
            <div class="tse-section-title">📸 <?= esc_html($_t($T['section_foto'])) ?></div>
            <p style="font-size:12px; color:#9ca3af; margin:0 0 8px; line-height:1.45;"><?= esc_html($_t($T2['foto_sub'])) ?></p>
            <p style="font-size:12px; color:#f5b942; margin:0 0 14px; line-height:1.45;"><?= esc_html($_t($T['no_watermark_warning'])) ?></p>
            <div id="tse-ruolo-guida" class="tse-album-desc" style="display:none; border-left-color:#c8ff00;"></div>

            <!-- FIX 2026-09-22 marco — linguette dagli album condivisi con la registrazione (ordine per ruolo lo fa il JS) -->
            <div class="tse-album-tabs" id="tse-album-tabs">
                <?php foreach ($SE_ALBUM_DEFS as $__i => $__ad): ?>
                <button type="button" class="tse-album-tab<?= $__i === 0 ? ' active' : '' ?>" data-album="<?= esc_attr($__ad['code']) ?>" onclick="talentAlbumSwitch('<?= esc_attr($__ad['code']) ?>')"><?= esc_html($__ad['label']) ?></button>
                <?php endforeach; ?>
            </div>

            <div id="tse-album-desc" class="tse-album-desc"></div>

            <!-- FIX 2026-06-28 marco — upload-box sopra la griglia (era troppo lontano da scrollare) -->
            <button type="button" id="tse-add-photo" class="tse-add-photo-btn" onclick="talentToggleUpload(true)"><?= esc_html($_t($T2['add_photo'])) ?></button><!-- FIX 2026-09-22 marco -->
            <div class="tse-upload-box" id="tse-upload-box" hidden>
                <button type="button" class="tse-upload-close" onclick="talentToggleUpload(false)"><?= esc_html($_t($T2['close_upload'])) ?></button>
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

            <div id="tse-princ-note" class="tse-album-desc" hidden><?= esc_html($_t($T2['princ_missing'])) ?></div><!-- FIX 2026-09-22 marco -->
            <div id="tse-album-grid" class="tse-album-grid" style="margin-top:18px;"></div>
            <div class="tse-touch-hint"><?= esc_html($_t($T2['touch_hint2'])) ?></div>
        </div>

        <form id="tse-form" class="tse-form" autocomplete="on">
            <div class="tse-name-display" id="tse-name-display"></div>
            <div id="tse-completezza" class="tse-completezza" style="display:none;">
                <div class="tse-compl-row">
                    <span class="tse-compl-label" id="tse-compl-label"><?= esc_html($_t($T['compl_label'])) ?></span>
                    <span class="tse-compl-pct" id="tse-compl-pct">0%</span>
                </div>
                <div class="tse-compl-track"><div class="tse-compl-fill" id="tse-compl-fill" style="width:0%"></div></div>
                <div id="tse-mancano" class="tse-manca-wrap" style="display:none;"></div>
            </div>

            <div class="tse-section">
                <div class="tse-section-title">📞 <?= esc_html($_t($T['section_contatti'])) ?></div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_telefono'])) ?></label>
                    <div class="tse-row" style="gap:8px;">
                        <select id="f-telefono-prefix" class="tse-select" autocomplete="tel-country-code" style="flex:0 0 130px;">
                            <?php foreach ($PREFISSI_OPTS as $p): ?>
                                <option value="<?= esc_attr($p['prefix']) ?>" <?= ($p['code'] === 'IT') ? 'selected' : '' ?>><?= esc_html(($p['flag'] ?? '') . ' ' . $p['prefix'] . ' ' . $p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="tel" id="f-telefono" class="tse-input" autocomplete="tel" style="flex:1;">
                    </div>
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
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_sesso'])) ?></label>
                    <select id="f-sesso" class="tse-select">
                        <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                        <?php foreach ($SESSO_OPTS as $k=>$v): ?><option value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_occhi'])) ?></label>
                    <select id="f-occhi" class="tse-select">
                        <option value=""><?= esc_html($_t($T['opt_select'])) ?></option>
                        <?php foreach ($OCCHI_OPTS as $k=>$v): ?><option value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></option><?php endforeach; ?>
                    </select>
                </div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_etnia'])) ?></label>
                    <div class="tse-chips" id="f-etnia" data-max="2" data-group="etnia">
                        <?php foreach ($ETNIA_OPTS as $k=>$v): ?><label class="tse-chip"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></label><?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- 2026-07-16 TEMA — Profilo professionale (consolidato da completa-profilo) -->
            <div class="tse-section">
                <div class="tse-section-title">🎬 <?= esc_html($_t($T['section_profilo'])) ?></div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_ruoli'])) ?></label>
                    <div class="tse-chips" id="f-ruoli" data-group="ruoli">
                        <?php foreach ($RUOLI_OPTS as $k=>$v): ?><label class="tse-chip<?= $k === 'bambino' ? ' tse-chip-auto' : '' ?>"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></label><?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- FIX 2026-09-22 marco (CRM - EVENTS DATABASE, Fase 2) — sezione Hostess & Eventi ben in vista -->
            <div class="tse-section tse-ev-section" id="tse-ev-section">
                <div class="tse-section-title">🎉 <?= esc_html($_t($T2['ev_title'])) ?></div>
                <div id="tse-ev-cta" class="tse-ev-cta" hidden>
                    <span><?= esc_html($_t($T2['ev_cta'])) ?></span>
                    <button type="button" class="tse-ev-cta-btn" onclick="talentAddHostess()"><?= esc_html($_t($T2['ev_cta_btn'])) ?></button>
                </div>
                <div id="tse-ev-hostess" hidden>
                    <p class="tse-ev-rules"><?= esc_html($_t($T2['ev_rules'])) ?></p>
                    <p id="tse-ev-nofoto" class="tse-ev-nofoto" hidden><?= esc_html($_t($T2['ev_nofoto'])) ?></p>
                    <button type="button" class="tse-ev-open" onclick="talentOpenEventi()"><?= esc_html($_t($T2['ev_open_album'])) ?></button>
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t($T2['ev_tipi'])) ?></label>
                        <div class="tse-chips" id="f-ev-tipi"><?php foreach ($EV_TIPI as $k=>$v): ?><label class="tse-chip"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></label><?php endforeach; ?></div>
                    </div>
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t($T2['ev_anni'])) ?></label>
                        <select id="f-ev-anni" class="tse-select"><option value="">—</option><?php foreach ($EV_ANNI as $k=>$v): ?><option value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></option><?php endforeach; ?></select>
                    </div>
                    <div class="tse-field">
                        <label class="tse-label"><?= esc_html($_t($T2['ev_cert'])) ?></label>
                        <div class="tse-chips" id="f-ev-cert"><?php foreach ($EV_CERT as $k=>$v): ?><label class="tse-chip"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></label><?php endforeach; ?></div>
                        <input type="text" id="f-ev-certaltro" class="tse-input" maxlength="120" placeholder="<?= esc_attr($_t($T2['ev_cert_altro'])) ?>" style="margin-top:8px;">
                    </div>
                </div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_lingue'])) ?></label>
                    <div class="tse-chips" id="f-lingue" data-group="lingue">
                        <?php foreach ($LINGUE_OPTS as $k=>$v): ?><label class="tse-chip"><input type="checkbox" value="<?= esc_attr($k) ?>"><?= esc_html($_t($v)) ?></label><?php endforeach; ?>
                    </div>
                    <!-- FIX 2026-08-08 marco — livello + certificazioni per lingua scelta (righe generate da JS) -->
                    <p class="tse-help" style="font-size:11px;color:#6b7280;margin:8px 0 4px;"><?= esc_html($_t($T['lingue_dettaglio_hint'])) ?></p>
                    <div id="tse-lingue-dettaglio"></div>
                </div>
                <div class="tse-field">
                    <label class="tse-check-row"><input type="checkbox" id="f-patente"> <?= esc_html($_t($T['field_patente'])) ?></label>
                    <!-- 2026-08-08 TEMA — automunito visibile SOLO se patente=sì -->
                    <div id="tse-automunito-wrap" style="display:none;">
                        <label class="tse-check-row"><input type="checkbox" id="f-automunito"> <?= esc_html($_t($T['field_automunito'])) ?></label>
                    </div>
                </div>
                <script>
                (function(){
                    var pat = document.getElementById('f-patente');
                    var wrap = document.getElementById('tse-automunito-wrap');
                    var auto = document.getElementById('f-automunito');
                    if (!pat || !wrap) return;
                    function sync(){
                        wrap.style.display = pat.checked ? '' : 'none';
                        if (!pat.checked && auto) auto.checked = false;
                    }
                    pat.addEventListener('change', sync);
                    sync();
                })();
                </script>
            </div>

            <!-- FEATURE 2026-09-23 marco (CRM - RUOLI MULTI-SCHEDA, Fase 3) — una sezione per ogni ruolo del talent (attore, comparsa, modello): la disegna assets/talent-profilo-ruolo.js leggendo il catalogo dal CRM; si salva col pulsante Salva della pagina -->
            <div id="tse-profili-ruolo" data-api="/crm_toagency/actions/talent-profilo-ruolo.php"></div>

            <!-- FIX 2026-08-08 marco — sezione Indirizzo: prima il Paese (come in registrazione), poi comune/città in base al paese scelto -->
            <div class="tse-section">
                <div class="tse-section-title">📍 <?= esc_html($_t(['it'=>'Indirizzo','en'=>'Location','fr'=>'Localisation','es'=>'Ubicación'])) ?></div>
                <div class="tse-field">
                    <label class="tse-label"><?= esc_html($_t($T['field_paese_residenza'])) ?></label>
                    <select id="f-paese_residenza" class="tse-select">
                        <option value=""><?= esc_html($_t($T['opt_select_paese'])) ?></option>
                    </select>
                </div>
                <div class="tse-row" id="tse-row-it">
                    <!-- FIX 2026-07-01 marco — comune con ricerca a suggerimenti (no testo libero): scegli dalla lista. Visibile solo se Paese = Italia -->
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
                <!-- Paese diverso da Italia: niente provincia, città in campo libero -->
                <div class="tse-field" id="tse-row-estero" style="display:none;">
                    <label class="tse-label"><?= esc_html($_t($T['field_citta_estero'])) ?></label>
                    <input type="text" id="f-comune_estero_visible" class="tse-input" placeholder="<?= esc_attr($_t($T['citta_estero_placeholder'])) ?>" maxlength="100">
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
            <!-- 2026-08-08 TEMA — non più solo Italia: link dinamico per paese/lingua via onboarding-community.php (stesso sistema già usato in page-registrati-talent.php) -->
            <div id="tse-community-block" style="display:none;margin-top:20px;padding:16px;background:#0f0f12;border:1px solid #25D366;border-radius:8px;text-align:center;">
                <p style="color:#d1d5db;font-size:13px;margin:0 0 12px;"><?= esc_html($_t(['it'=>'📣 Ricevi i casting della tua zona prima degli altri! Entra nel gruppo WhatsApp giusto per te.','en'=>'📣 Get castings for your area before anyone else! Join the right WhatsApp group for you.','fr'=>'📣 Reçois les castings de ta région avant les autres ! Rejoins le bon groupe WhatsApp.','es'=>'📣 ¡Recibe los castings de tu zona antes que nadie! Únete al grupo de WhatsApp adecuado.'])) ?></p>
                <a id="tse-community-wa-btn" href="https://toagency.it/crm_toagency/onboarding-community.php" target="_blank" rel="noopener" style="display:inline-block;background:#25D366;color:#fff;padding:12px 24px;border-radius:8px;font-weight:700;font-size:14px;text-decoration:none;">📲 <?= esc_html($_t(['it'=>'Entra nel gruppo WhatsApp','en'=>'Join the WhatsApp group','fr'=>'Rejoindre le groupe WhatsApp','es'=>'Unirse al grupo de WhatsApp'])) ?></a>
            </div>
        </form>

        <!-- 2026-08-08 TEMA — Video di presentazione spostato qui (prima era in fondo, dentro tse-foto-section
             che parte display:none finché non caricano i media: risultato invisibile finché non arrivavano le foto).
             Facoltativo per tutti, ma prioritario per attori/self-tape → più rilievo e più in alto. -->
        <div id="tse-video-section" class="tse-section" style="margin-top:20px;">
            <div class="tse-section-title">🎥 <?= esc_html($_t(['it'=>'Video di presentazione','en'=>'Intro video','fr'=>'Vidéo de présentation','es'=>'Vídeo de presentación'])) ?></div>
            <p style="font-size:12px; color:#9ca3af; margin:0 0 8px; line-height:1.45;"><?= esc_html($_t(['it'=>'Facoltativo per tutti, ma molto utile per attori/attrici (self-tape) e creator (mostra come sei davanti alla camera). Basta anche un video girato con il telefono, bassa risoluzione ok · max 30MB.','en'=>'Optional for everyone, but great for actors (self-tape) and creators (shows how you come across on camera). A simple phone video is fine, low res ok · max 30MB.','fr'=>'Facultatif pour tous, mais très utile pour les acteurs/actrices (self-tape) et les créateurs de contenu (montre comment tu es à l’écran). Une simple vidéo au téléphone suffit, basse résolution ok · max 30 Mo.','es'=>'Opcional para todos, pero muy útil para actores/actrices (self-tape) y creadores de contenido (muestra cómo te desenvuelves en cámara). Basta un vídeo con el móvil, baja resolución ok · máx 30MB.'])) ?></p>
            <p style="font-size:12px; color:#f5b942; margin:0 0 12px; line-height:1.45;"><?= esc_html($_t($T['no_watermark_warning'])) ?></p>
            <!-- FIX 2026-09-23 marco ticket-239: come si carica il video, in 4 lingue (stesse parole delle schede di Amelia #204/#205) -->
            <ol id="tse-video-howto" style="font-size:12px; color:#cbd5e1; margin:0 0 6px; padding-left:18px; line-height:1.6;">
                <li><?= esc_html($_t(['it'=>'Spunta la casella dei diritti.','en'=>'Tick the rights box.','fr'=>'Coche la case des droits.','es'=>'Marca la casilla de derechos.'])) ?></li>
                <li><?= esc_html($_t(['it'=>'Scegli il video: MP4 consigliato, max 30 MB.','en'=>'Choose the video: MP4 recommended, max 30 MB.','fr'=>'Choisis la vidéo : MP4 recommandé, max 30 Mo.','es'=>'Elige el vídeo: MP4 recomendado, máx. 30 MB.'])) ?></li>
                <li><?= esc_html($_t(['it'=>'Premi «Carica video» e aspetta la spunta verde ✓.','en'=>'Press «Upload video» and wait for the green ✓.','fr'=>'Appuie sur «Charger la vidéo» et attends la coche verte ✓.','es'=>'Pulsa «Subir vídeo» y espera la marca verde ✓.'])) ?></li>
            </ol>
            <p style="font-size:12px; color:#9ca3af; margin:0 0 12px; line-height:1.45;"><?= esc_html($_t(['it'=>'Se qualcosa non va, scrivi ad Amelia nella chat.','en'=>'If anything goes wrong, write to Amelia in the chat.','fr'=>'Si quelque chose ne va pas, écris à Amelia dans le chat.','es'=>'Si algo no va bien, escribe a Amelia en el chat.'])) ?></p>
            <div class="tse-upload-box">
                <label class="tse-legal-checkbox">
                    <input type="checkbox" id="tse-video-legal">
                    <span><?= esc_html($_t(['it'=>'Ho i diritti e autorizzo la pubblicazione.','en'=>'I own the rights and allow publication.','fr'=>'Je détiens les droits et autorise la publication.','es'=>'Tengo los derechos y autorizo la publicación.'])) ?></span>
                </label>
                <div class="tse-upload-row" style="margin-top:10px;">
                    <input type="file" id="tse-video-input" accept="video/mp4,video/quicktime,video/webm" style="display:none;" onchange="talentVideoChosen(this)">
                    <button type="button" class="tse-upload-btn-file" onclick="document.getElementById('tse-video-input').click()">🎥 <?= esc_html($_t(['it'=>'Scegli video','en'=>'Choose video','fr'=>'Choisir la vidéo','es'=>'Elegir vídeo'])) ?></button>
                    <span id="tse-video-fname" class="tse-upload-fname">—</span>
                    <button type="button" id="tse-video-go" class="tse-upload-btn-go" onclick="talentVideoGo()"><?= esc_html($_t(['it'=>'Carica video','en'=>'Upload video','fr'=>'Charger la vidéo','es'=>'Subir vídeo'])) ?></button>
                </div>
                <div id="tse-video-status" class="tse-upload-status"></div>
                <div id="tse-video-heavy" style="display:none; margin-top:12px; padding:12px; background:#0a0a0a; border:1px solid #2a2a2e; border-radius:8px;">
                    <div style="font-size:12px; color:#cbd5e1; line-height:1.6;"><?= esc_html($_t(['it'=>'Come accorciarlo: apri il video nella Galleria (iPhone: Foto), tocca Modifica, trascina i bordi per tagliare l’inizio e la fine, salva e ricaricalo. Se non ci riesci, scrivi ad Amelia nella chat.','en'=>'How to shorten it: open the video in your phone gallery (iPhone: Photos), tap Edit, drag the edges to trim the start and end, save and upload it again. Stuck? Write to Amelia in the chat.','fr'=>'Comment la raccourcir : ouvre la vidéo dans ta galerie (iPhone : Photos), touche Modifier, fais glisser les bords pour couper le début et la fin, enregistre et recharge-la. Besoin d’aide ? Écris à Amelia dans le chat.','es'=>'Cómo acortarlo: abre el vídeo en la galería del móvil (iPhone: Fotos), toca Editar, arrastra los bordes para recortar el principio y el final, guárdalo y vuelve a subirlo. ¿No lo consigues? Escribe a Amelia en el chat.'])) ?></div>
                </div>
            </div>
        </div>

        <!-- FIX 2026-10-02 marco (ticket #451) — Video dettagli: SOLO ruolo model (lo mostra/nasconde talent-self-edit.js), album video_dettaglio, max 3.
             Stesse regole del video di presentazione: max 30 MB, riduzione nel browser, in attesa di approvazione. -->
        <div id="tse-videodet-section" class="tse-section" style="margin-top:20px; display:none;">
            <div class="tse-section-title">🔍 <?= esc_html($_t(['it'=>'Video dettagli','en'=>'Detail videos','fr'=>'Vidéos de détail','es'=>'Vídeos de detalle'])) ?></div>
            <p style="font-size:12px; color:#9ca3af; margin:0 0 12px; line-height:1.45;"><?= esc_html($_t(['it'=>'Brevi video dei tuoi dettagli (mani, occhi, profilo, sorriso…), anche girati con il telefono. Fino a 3 video · max 30 MB ciascuno.','en'=>'Short videos of your details (hands, eyes, profile, smile…), phone footage is fine. Up to 3 videos · max 30 MB each.','fr'=>'Courtes vidéos de tes détails (mains, yeux, profil, sourire…), même filmées au téléphone. Jusqu’à 3 vidéos · max 30 Mo chacune.','es'=>'Vídeos cortos de tus detalles (manos, ojos, perfil, sonrisa…), vale grabarlos con el móvil. Hasta 3 vídeos · máx. 30 MB cada uno.'])) ?></p>
            <div class="tse-upload-box">
                <label class="tse-legal-checkbox">
                    <input type="checkbox" id="tse-videodet-legal">
                    <span><?= esc_html($_t(['it'=>'Ho i diritti e autorizzo la pubblicazione.','en'=>'I own the rights and allow publication.','fr'=>'Je détiens les droits et autorise la publication.','es'=>'Tengo los derechos y autorizo la publicación.'])) ?></span>
                </label>
                <div class="tse-upload-row" style="margin-top:10px;">
                    <input type="file" id="tse-videodet-input" accept="video/mp4,video/quicktime,video/webm" style="display:none;" onchange="talentVideoChosen(this,'det')">
                    <button type="button" class="tse-upload-btn-file" onclick="document.getElementById('tse-videodet-input').click()">🔍 <?= esc_html($_t(['it'=>'Scegli video','en'=>'Choose video','fr'=>'Choisir la vidéo','es'=>'Elegir vídeo'])) ?></button>
                    <span id="tse-videodet-fname" class="tse-upload-fname">—</span>
                    <button type="button" id="tse-videodet-go" class="tse-upload-btn-go" onclick="talentVideoGo('det')"><?= esc_html($_t(['it'=>'Carica video','en'=>'Upload video','fr'=>'Charger la vidéo','es'=>'Subir vídeo'])) ?></button>
                </div>
                <div id="tse-videodet-status" class="tse-upload-status"></div>
                <div id="tse-videodet-heavy" style="display:none; margin-top:12px; padding:12px; background:#0a0a0a; border:1px solid #2a2a2e; border-radius:8px;">
                    <div style="font-size:12px; color:#cbd5e1; line-height:1.6;"><?= esc_html($_t(['it'=>'Come accorciarlo: apri il video nella Galleria (iPhone: Foto), tocca Modifica, trascina i bordi per tagliare l’inizio e la fine, salva e ricaricalo. Se non ci riesci, scrivi ad Amelia nella chat.','en'=>'How to shorten it: open the video in your phone gallery (iPhone: Photos), tap Edit, drag the edges to trim the start and end, save and upload it again. Stuck? Write to Amelia in the chat.','fr'=>'Comment la raccourcir : ouvre la vidéo dans ta galerie (iPhone : Photos), touche Modifier, fais glisser les bords pour couper le début et la fin, enregistre et recharge-la. Besoin d’aide ? Écris à Amelia dans le chat.','es'=>'Cómo acortarlo: abre el vídeo en la galería del móvil (iPhone: Fotos), toca Editar, arrastra los bordes para recortar el principio y el final, guárdalo y vuelve a subirlo. ¿No lo consigues? Escribe a Amelia en el chat.'])) ?></div>
                </div>
            </div>
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
    apiVideo:    '/crm_toagency/actions/talent-self-edit-video.php',
    apiStato:    '/crm_toagency/actions/talent-profilo-stato.php',
    apiSetCover: '/crm_toagency/actions/talent-media-set-cover.php', /* FIX 2026-09-22 marco */
    provinceJsonUrl: <?= json_encode($theme_uri . '/assets/data/province-italia.json') ?>, /* FIX 2026-07-01 marco — tendina provincia self-edit */
    comuneApiUrl: '/crm_toagency/actions/cerca-comune.php', /* FIX 2026-07-01 marco — ricerca comune self-edit */
    uuid:    <?= json_encode($uuid_get) ?>,
    token:   <?= json_encode($token_get) ?>,
    lang:    <?= json_encode($__l) ?>, /* 2026-08-08 TEMA — lingua pagina, per link community corretto */
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
        albumDefs: <?= json_encode($SE_ALBUM_DEFS, JSON_UNESCAPED_UNICODE) ?>, /* FIX 2026-09-22 marco — album condivisi */
        delLabel:    <?= json_encode($_t($T2['del'])) ?>,
        princBadge:  <?= json_encode($_t($T2['princ_badge'])) ?>,
        stileEl:     <?= json_encode($_t($T2['ev_stile_el'])) ?>,
        stileSp:     <?= json_encode($_t($T2['ev_stile_sp'])) ?>,
        setPrinc:    <?= json_encode($_t($T2['set_princ'])) ?>,
        coverEvBadge:<?= json_encode($_t($T2['cover_ev_badge'])) ?>,
        setCoverEv:  <?= json_encode($_t($T2['set_cover_ev'])) ?>,
        noDelPrinc:  <?= json_encode($_t($T2['no_del_princ'])) ?>,
        princPending:<?= json_encode($_t($T2['princ_pending'])) ?>,
        princOk:     <?= json_encode($_t($T2['princ_ok'])) ?>,
        moveLabel:   <?= json_encode($_t($T2['move'])) ?>,
        confirmDel:  <?= json_encode($_t($T2['confirm_del'])) ?>,
        delError:    <?= json_encode($_t($T2['del_error'])) ?>,
        statePending:  <?= json_encode($_t($T2['state_pending'])) ?>,
        stateRejected: <?= json_encode($_t($T2['state_rejected'])) ?>,
        albumLabels: {
            polaroid:  <?= json_encode($_t($T['tab_polaroid'])) ?>,
            dettaglio: <?= json_encode($_t($T['tab_dettaglio'])) ?>,
            portfolio: <?= json_encode($_t($T['tab_portfolio'])) ?>,
            eventi:    <?= json_encode($_t($T['tab_eventi'])) ?>,
            casual:    <?= json_encode($_t($T['tab_casual'])) ?>,
        },
        guidaRuoloIntro:     <?= json_encode($_t($T['guida_ruolo_intro'])) ?>,
        guidaPolaroidObblig: <?= json_encode($_t($T['guida_ruolo_polaroid'])) ?>,
        livelloSelect:   <?= json_encode($_t($T['livello_select'])) ?>,
        livelloNativo:   <?= json_encode($_t($T['livello_nativo'])) ?>,
        certPlaceholder: <?= json_encode($_t($T['cert_placeholder'])) ?>,
        livelliLabel:    <?= json_encode($_t($T['livelli_label'])) ?>,
        certVuoto:       <?= json_encode($_t($T['cert_vuoto'])) ?>,
        certNo:          <?= json_encode($_t($T['cert_no'])) ?>,
        certSi:          <?= json_encode($_t($T['cert_si'])) ?>,
        errCert:         <?= json_encode($_t($T['err_cert'])) ?>,
        altroLinguaPlaceholder: <?= json_encode($_t($T['altro_lingua_placeholder'])) ?>,
        lingueLabels: {
            <?php foreach ($LINGUE_OPTS as $k=>$v): ?><?= json_encode($k) ?>: <?= json_encode($_t($v)) ?>,
            <?php endforeach; ?>
        },
        complLabel:          <?= json_encode($_t($T['compl_label'])) ?>,
        statusPublic:        <?= json_encode($_t($T['statuscard_public'])) ?>,
        statusReview:        <?= json_encode($_t($T['statuscard_review'])) ?>,
        statusPhotosPending: <?= json_encode($_t($T['statuscard_photos_pending'])) ?>,
        // FIX 2026-09-23 marco ticket-239: messaggi del caricamento video nelle 4 lingue (prima erano solo italiano dentro il JS)
        video: {
            chooseFirst: <?= json_encode($_t(['it'=>'Scegli prima un video','en'=>'Choose a video first','fr'=>"Choisis d'abord une vidéo",'es'=>'Elige primero un vídeo'])) ?>,
            consent: <?= json_encode($_t(['it'=>'Spunta il consenso per caricare','en'=>'Tick the consent box to upload','fr'=>'Coche la case de consentement pour charger','es'=>'Marca la casilla de consentimiento para subir'])) ?>,
            tooBig: <?= json_encode($_t(['it'=>'Video oltre 30MB: accorcialo (vedi sotto come fare) e riprova','en'=>'Video over 30MB: shorten it (see how below) and try again','fr'=>'Vidéo de plus de 30 Mo : raccourcis-la (voir comment ci-dessous) et réessaie','es'=>'Vídeo de más de 30MB: acórtalo (mira cómo abajo) y vuelve a intentarlo'])) ?>,
            loading: <?= json_encode($_t(['it'=>'Caricamento…','en'=>'Uploading…','fr'=>'Chargement…','es'=>'Subiendo…'])) ?>,
            network: <?= json_encode($_t(['it'=>'Errore di rete: collegati al Wi-Fi, resta su questa pagina e riprova. Se non ci riesci, scrivi ad Amelia nella chat.','en'=>'Network error: connect to Wi-Fi, stay on this page and try again. If it still fails, write to Amelia in the chat.','fr'=>'Erreur réseau : connecte-toi au Wi-Fi, reste sur cette page et réessaie. Si ça ne marche toujours pas, écris à Amelia dans le chat.','es'=>'Error de red: conéctate al Wi-Fi, quédate en esta página e inténtalo de nuevo. Si sigue fallando, escribe a Amelia en el chat.'])) ?>,
            generic: <?= json_encode($_t(['it'=>'Non è stato possibile caricare il video. Riprova; se non ci riesci scrivi ad Amelia nella chat.','en'=>'The video could not be uploaded. Try again; if it still fails, write to Amelia in the chat.','fr'=>"La vidéo n'a pas pu être chargée. Réessaie ; si ça ne marche pas, écris à Amelia dans le chat.",'es'=>'No se pudo subir el vídeo. Inténtalo de nuevo; si no funciona, escribe a Amelia en el chat.'])) ?>,
            okBase: <?= json_encode($_t(['it'=>'Video caricato','en'=>'Video uploaded','fr'=>'Vidéo chargée','es'=>'Vídeo subido'])) ?>,
            reducing: <?= json_encode($_t(['it'=>'Riduco il video per renderlo più leggero… {p}% (resta su questa pagina)','en'=>'Making your video lighter… {p}% (please stay on this page)','fr'=>'Je rends ta vidéo plus légère… {p} % (reste sur cette page)','es'=>'Aligerando tu vídeo… {p}% (quédate en esta página)'])) ?>, <?php /* ticket #442 (02/10/2026) */ ?>
            okPending: <?= json_encode($_t(['it'=>' In attesa di approvazione dello staff.','en'=>' Waiting for staff approval.','fr'=>' En attente de validation par notre équipe.','es'=>' Pendiente de aprobación del equipo.'])) ?>,
            err: {
                legal_required: <?= json_encode($_t(['it'=>'Spunta il consenso per caricare','en'=>'Tick the consent box to upload','fr'=>'Coche la case de consentement pour charger','es'=>'Marca la casilla de consentimiento para subir'])) ?>,
                cap_album: <?= json_encode($_t(['it'=>'Hai raggiunto il massimo di {n} video per questa sezione. Elimina un video già caricato o scrivi ad Amelia nella chat.','en'=>'You have reached the maximum of {n} videos for this section. Delete an uploaded video or write to Amelia in the chat.','fr'=>'Tu as atteint le maximum de {n} vidéos pour cette section. Supprime une vidéo déjà chargée ou écris à Amelia dans le chat.','es'=>'Has llegado al máximo de {n} vídeos en esta sección. Elimina un vídeo ya subido o escribe a Amelia en el chat.'])) ?>,
                cap_tot: <?= json_encode($_t(['it'=>'Hai raggiunto il massimo di {n} video totali. Elimina un video già caricato o scrivi ad Amelia nella chat.','en'=>'You have reached the maximum of {n} videos in total. Delete an uploaded video or write to Amelia in the chat.','fr'=>'Tu as atteint le maximum de {n} vidéos au total. Supprime une vidéo déjà chargée ou écris à Amelia dans le chat.','es'=>'Has llegado al máximo de {n} vídeos en total. Elimina un vídeo ya subido o escribe a Amelia en el chat.'])) ?>,
                rate_limited: <?= json_encode($_t(['it'=>'Troppi upload ravvicinati. Riprova tra qualche minuto.','en'=>'Too many uploads in a row. Try again in a few minutes.','fr'=>'Trop de chargements rapprochés. Réessaie dans quelques minutes.','es'=>'Demasiadas subidas seguidas. Inténtalo de nuevo en unos minutos.'])) ?>,
                rate_limited_ip: <?= json_encode($_t(['it'=>'Troppi video caricati da questa rete oggi. Riprova domani o scrivi ad Amelia nella chat.','en'=>'Too many videos uploaded from this network today. Try again tomorrow or write to Amelia in the chat.','fr'=>"Trop de vidéos chargées depuis ce réseau aujourd'hui. Réessaie demain ou écris à Amelia dans le chat.",'es'=>'Demasiados vídeos subidos desde esta red hoy. Inténtalo mañana o escribe a Amelia en el chat.'])) ?>,
                no_file: <?= json_encode($_t(['it'=>'Nessun video ricevuto. Scegli il file e riprova.','en'=>'No video received. Choose the file and try again.','fr'=>'Aucune vidéo reçue. Choisis le fichier et réessaie.','es'=>'No se recibió ningún vídeo. Elige el archivo e inténtalo de nuevo.'])) ?>,
                too_big: <?= json_encode($_t(['it'=>'Video oltre 30MB: accorcialo (vedi sotto come fare) e riprova','en'=>'Video over 30MB: shorten it (see how below) and try again','fr'=>'Vidéo de plus de 30 Mo : raccourcis-la (voir comment ci-dessous) et réessaie','es'=>'Vídeo de más de 30MB: acórtalo (mira cómo abajo) y vuelve a intentarlo'])) ?>,
                upload_partial: <?= json_encode($_t(['it'=>'Upload interrotto. Riprova con una connessione stabile (meglio il Wi-Fi).','en'=>'Upload interrupted. Try again with a stable connection (Wi-Fi is best).','fr'=>'Chargement interrompu. Réessaie avec une connexion stable (le Wi-Fi est idéal).','es'=>'Subida interrumpida. Inténtalo de nuevo con una conexión estable (mejor Wi-Fi).'])) ?>,
                upload_error: <?= json_encode($_t(['it'=>'Errore durante il caricamento. Riprova; se non ci riesci scrivi ad Amelia nella chat.','en'=>'Error while uploading. Try again; if it still fails, write to Amelia in the chat.','fr'=>'Erreur pendant le chargement. Réessaie ; si ça ne marche pas, écris à Amelia dans le chat.','es'=>'Error al subir. Inténtalo de nuevo; si no funciona, escribe a Amelia en el chat.'])) ?>,
                too_small: <?= json_encode($_t(['it'=>'File non valido (troppo piccolo). Scegli un altro video.','en'=>'Invalid file (too small). Choose another video.','fr'=>'Fichier non valide (trop petit). Choisis une autre vidéo.','es'=>'Archivo no válido (demasiado pequeño). Elige otro vídeo.'])) ?>,
                invalid_ext: <?= json_encode($_t(['it'=>'Formato non supportato. Usa MP4 (consigliato), MOV o WEBM.','en'=>'Unsupported format. Use MP4 (recommended), MOV or WEBM.','fr'=>'Format non pris en charge. Utilise MP4 (conseillé), MOV ou WEBM.','es'=>'Formato no compatible. Usa MP4 (recomendado), MOV o WEBM.'])) ?>,
                invalid_file: <?= json_encode($_t(['it'=>'Il file non sembra un video valido. Usa MP4 (consigliato), MOV o WEBM.','en'=>'The file does not look like a valid video. Use MP4 (recommended), MOV or WEBM.','fr'=>'Le fichier ne semble pas être une vidéo valide. Utilise MP4 (conseillé), MOV ou WEBM.','es'=>'El archivo no parece un vídeo válido. Usa MP4 (recomendado), MOV o WEBM.'])) ?>,
                talent_non_importato: <?= json_encode($_t(['it'=>'Il tuo profilo non è ancora abilitato ai video. Scrivi ad Amelia nella chat.','en'=>'Your profile is not enabled for videos yet. Write to Amelia in the chat.','fr'=>"Ton profil n'est pas encore activé pour les vidéos. Écris à Amelia dans le chat.",'es'=>'Tu perfil aún no está habilitado para vídeos. Escribe a Amelia en el chat.'])) ?>,
                invalid_link: <?= json_encode($_t(['it'=>'Link della scheda non valido o scaduto. Apri di nuovo il link che ti abbiamo mandato per email.','en'=>'Profile link invalid or expired. Open the link we emailed you again.','fr'=>"Lien de la fiche invalide ou expiré. Ouvre de nouveau le lien que nous t'avons envoyé par e-mail.",'es'=>'Enlace de la ficha no válido o caducado. Abre de nuevo el enlace que te enviamos por correo.'])) ?>,
            },
        },
        mancanoTitolo:       <?= json_encode($_t($T['mancano_titolo'])) ?>,
        mancanoLabels: {
            telefono:               <?= json_encode($_t($T['mancano_telefono'])) ?>,
            data_nascita:           <?= json_encode($_t($T['mancano_data_nascita'])) ?>,
            tratti:                 <?= json_encode($_t($T['mancano_tratti'])) ?>,
            polaroid:               <?= json_encode($_t($T['mancano_polaroid'])) ?>,
            polaroid_da_aggiornare: <?= json_encode($_t($T['mancano_polaroid_da_aggiornare'])) ?>,
            foto_portfolio:         <?= json_encode($_t($T['mancano_foto_portfolio'])) ?>,
            foto_dettaglio:         <?= json_encode($_t($T['mancano_foto_dettaglio'])) ?>,
            foto_eventi:            <?= json_encode($_t($T['mancano_foto_eventi'])) ?>,
            misure:                 <?= json_encode($_t($T['mancano_misure'])) ?>,
        },
        verita: {
            polaroid:  <?= json_encode($_t($T['verita_polaroid'])) ?>,
            dettaglio: <?= json_encode($_t($T['verita_dettaglio'])) ?>,
            portfolio: <?= json_encode($_t($T['verita_portfolio'])) ?>,
            eventi:    <?= json_encode($_t($T['verita_eventi'])) ?>,
            casual:    <?= json_encode($_t($T['verita_casual'])) ?>,
        },
        albumDesc: {
            polaroid:  <?= json_encode($_t($T['album_desc']['polaroid'])) ?>,
            dettaglio: <?= json_encode($_t($T['album_desc']['dettaglio'])) ?>,
            portfolio: <?= json_encode($_t($T['album_desc']['portfolio'])) ?>,
            eventi:    <?= json_encode($_t($T['album_desc']['eventi'])) ?>,
            casual:    <?= json_encode($_t($T['album_desc']['casual'])) ?>,
        }
    }
};
</script>
<?php
$tse_js_path = get_stylesheet_directory() . '/assets/talent-self-edit.js';
$tse_js_ver  = file_exists($tse_js_path) ? filemtime($tse_js_path) : '2.1';
?>
<?php $tvr_js = get_stylesheet_directory() . '/assets/toa-video-reduce.js'; $tvr_ver = file_exists($tvr_js) ? filemtime($tvr_js) : '1'; /* ticket #442 (02/10/2026): riduzione video nel browser, versione = data del file */ ?>
<script src="<?= esc_url($theme_uri . '/assets/toa-video-reduce.js') ?>?v=<?= $tvr_ver ?>" defer></script>
<script src="<?= esc_url($theme_uri . '/assets/talent-self-edit.js') ?>?v=<?= $tse_js_ver ?>" defer></script>
<?php $tpr_js = get_stylesheet_directory() . '/assets/talent-profilo-ruolo.js'; $tpr_ver = file_exists($tpr_js) ? filemtime($tpr_js) : '1'; /* FIX 2026-09-23 marco: versione = data del file, niente bump a mano */ ?>
<script src="<?= esc_url($theme_uri . '/assets/talent-profilo-ruolo.js') ?>?v=<?= $tpr_ver ?>" defer></script><!-- FEATURE 2026-09-23 marco (RUOLI MULTI-SCHEDA) -->

<?php toa_component('footer'); ?>
