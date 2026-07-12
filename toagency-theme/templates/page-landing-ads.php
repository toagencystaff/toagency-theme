<?php
/**
 * Template Name: Landing Ads (no chrome)
 * Template Post Type: page
 *
 * Landing dedicata alle campagne Google Ads. NOINDEX, niente header/menu, niente footer SEO.
 * Documento HTML autosufficiente: chiama wp_head()/wp_footer() (= tracking Ads/GTM/pixel + CSS tema)
 * ma NON include toa_component('header')/('footer') → zero nav, zero distrazioni (= più conversioni + Quality Score).
 *
 * Stile: NERO on-brand (come il sito), testi bianchi, accento lime, logo bianco. Form = card bianca (form-b2b-inline).
 * Quale landing: page meta `_toa_ads_key` (casting-torino | hostess-torino | casting-italia).
 * Lingua: ?lang=it|en|fr|es  →  toa_current_lang()  →  'it'.
 * Form preventivo: riusa form-b2b-inline (POST CRM → redirect /tnx/ = conversione Ads).
 *
 * FIX 2026-06-20 marco — landing Ads (v2: restyle nero + logo + CTA Chiama/WhatsApp/Email tracciate)
 */

if (!function_exists('_ht')) {
    function _ht($strings) {
        global $__l;
        $l = (!empty($__l) && in_array($__l, ['it','en','fr','es'], true)) ? $__l : 'it';
        return esc_html(isset($strings[$l]) ? $strings[$l] : ($strings['it'] ?? ''));
    }
}

// --- Lingua: ?lang  →  toa_current_lang() (WPML)  →  'it' ---
$lang = isset($_GET['lang']) ? strtolower(trim($_GET['lang'])) : '';
if (!in_array($lang, ['it','en','fr','es'], true)) {
    $lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
}
if (!in_array($lang, ['it','en','fr','es'], true)) $lang = 'it';
$__l = $lang; // pilota _ht() del tema (usata da form-b2b-inline) → form nella stessa lingua dell'hero

// --- Quale landing ---
$post_id = get_queried_object_id();
$key     = get_post_meta($post_id, '_toa_ads_key', true);
if (!$key) { $key = get_post_field('post_name', $post_id) ?: 'casting-italia'; } // fallback: slug pagina = chiave (nessun meta da impostare a mano)
if (isset($_GET['key'])) { $k = preg_replace('/[^a-z0-9-]/', '', (string) $_GET['key']); if ($k !== '') $key = $k; } // override preview/ads

// --- Servizio pre-selezionato per landing ---
$default_service = [
    'casting-torino' => 'shooting',
    'hostess-torino' => 'fiera-salone',
    'casting-roma'   => 'shooting',
    'hostess-roma'   => 'fiera-salone',
    'casting-italia' => 'pubblicita',
    'models-aziende' => 'shooting',
    'hostess-eventi' => 'fiera-salone',
    'attori-produzioni' => 'film',
][$key] ?? 'pubblicita';

// --- Contatti (da /contatti/) ---
$TEL_RAW  = '+393517899225';
$TEL_DISP = '+39 351 789 9225';
$WA       = 'https://wa.me/393517899225';
$EMAIL    = 'business@toagency.it';
$LOGO     = 'https://toagency.it/wp-content/uploads/2025/09/LogoToanew.png'; // logo bianco header

// =================== COPY (3 landing x 4 lingue) ===================
$COPY = [
  'casting-torino' => [
    'h1'  => ['it'=>'Agenzia casting e modelli a Torino per aziende e produzioni','en'=>'Casting and model agency in Turin for companies and productions','fr'=>'Agence de casting et mannequins à Turin pour entreprises et productions','es'=>'Agencia de casting y modelos en Turín para empresas y producciones'],
    'sub' => ['it'=>'Modelli, hostess, attori e comparse selezionati per shooting, campagne ed eventi. Preventivo gratuito in 24 ore.','en'=>'Selected models, hostesses, actors and extras for shoots, campaigns and events. Free quote within 24 hours.','fr'=>'Mannequins, hôtesses, acteurs et figurants sélectionnés pour shootings, campagnes et événements. Devis gratuit sous 24 heures.','es'=>'Modelos, azafatas, actores y figurantes seleccionados para sesiones, campañas y eventos. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili nel database','15+ anni di esperienza, sede a Torino','Operativi a Torino e in tutto il Piemonte','Casting gestiti in 4 lingue'],
      'en'=>['20,000+ profiles in our database','15+ years of experience, based in Turin','Active across Turin and the Piedmont region','Casting managed in 4 languages'],
      'fr'=>['Plus de 20 000 profils en base','Plus de 15 ans d\'expérience, basés à Turin','Actifs à Turin et dans tout le Piémont','Casting géré en 4 langues'],
      'es'=>['Más de 20.000 perfiles en la base de datos','Más de 15 años de experiencia, sede en Turín','Operativos en Turín y en todo el Piamonte','Casting gestionado en 4 idiomas'],
    ],
  ],
  'hostess-torino' => [
    'h1'  => ['it'=>'Agenzia hostess e steward a Torino per fiere, eventi e congressi','en'=>'Hostess and event staff agency in Turin for trade fairs, events and congresses','fr'=>'Agence d\'hôtesses et stewards à Turin pour salons, événements et congrès','es'=>'Agencia de azafatas y stewards en Turín para ferias, eventos y congresos'],
    'sub' => ['it'=>'Personale qualificato e multilingue per fiere, congressi, stand e accoglienza. Preventivo gratuito in 24 ore.','en'=>'Qualified, multilingual hostesses and stewards for fairs, conferences, stands and reception. Free quote within 24 hours.','fr'=>'Personnel qualifié et multilingue pour salons, congrès, stands et accueil. Devis gratuit sous 24 heures.','es'=>'Personal cualificado y multilingüe para ferias, congresos, stands y recepción. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['Hostess e steward per fiere, saloni ed eventi','20.000+ profili nel database','15+ anni di esperienza, sede a Torino','Gestione in italiano, inglese, francese e spagnolo'],
      'en'=>['Hostesses and stewards for fairs, shows and events','20,000+ profiles in our database','15+ years of experience, based in Turin','Management in Italian, English, French and Spanish'],
      'fr'=>['Hôtesses et stewards pour salons et événements','Plus de 20 000 profils en base','Plus de 15 ans d\'expérience, basés à Turin','Gestion en italien, anglais, français et espagnol'],
      'es'=>['Azafatas y stewards para ferias, salones y eventos','Más de 20.000 perfiles en la base de datos','Más de 15 años de experiencia, sede en Turín','Gestión en italiano, inglés, francés y español'],
    ],
  ],
  'casting-roma' => [ // FIX 2026-06-22 marco — landing Ads Roma
    'h1'  => ['it'=>'Agenzia casting e modelli a Roma per aziende e produzioni','en'=>'Casting and model agency in Rome for companies and productions','fr'=>'Agence de casting et mannequins à Rome pour entreprises et productions','es'=>'Agencia de casting y modelos en Roma para empresas y producciones'],
    'sub' => ['it'=>'Modelli, hostess, attori e comparse selezionati per shooting, campagne ed eventi. Preventivo gratuito in 24 ore.','en'=>'Selected models, hostesses, actors and extras for shoots, campaigns and events. Free quote within 24 hours.','fr'=>'Mannequins, hôtesses, acteurs et figurants sélectionnés pour shootings, campagnes et événements. Devis gratuit sous 24 heures.','es'=>'Modelos, azafatas, actores y figurantes seleccionados para sesiones, campañas y eventos. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili nel database','15+ anni di esperienza, dal 2009','Operativi a Roma e in tutto il Lazio','Casting gestiti in 4 lingue'],
      'en'=>['20,000+ profiles in our database','15+ years of experience, since 2009','Active across Rome and the Lazio region','Casting managed in 4 languages'],
      'fr'=>['Plus de 20 000 profils en base','Plus de 15 ans d\'expérience, depuis 2009','Actifs à Rome et dans tout le Latium','Casting géré en 4 langues'],
      'es'=>['Más de 20.000 perfiles en la base de datos','Más de 15 años de experiencia, desde 2009','Operativos en Roma y en todo el Lacio','Casting gestionado en 4 idiomas'],
    ],
  ],
  'hostess-roma' => [ // FIX 2026-06-22 marco — landing Ads Roma
    'h1'  => ['it'=>'Agenzia hostess e steward a Roma per fiere, eventi e congressi','en'=>'Hostess and event staff agency in Rome for trade fairs, events and congresses','fr'=>'Agence d\'hôtesses et stewards à Rome pour salons, événements et congrès','es'=>'Agencia de azafatas y stewards en Roma para ferias, eventos y congresos'],
    'sub' => ['it'=>'Personale qualificato e multilingue per fiere, congressi, stand e accoglienza. Preventivo gratuito in 24 ore.','en'=>'Qualified, multilingual hostesses and stewards for fairs, conferences, stands and reception. Free quote within 24 hours.','fr'=>'Personnel qualifié et multilingue pour salons, congrès, stands et accueil. Devis gratuit sous 24 heures.','es'=>'Personal cualificado y multilingüe para ferias, congresos, stands y recepción. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['Hostess e steward per fiere, saloni ed eventi','20.000+ profili nel database','15+ anni di esperienza, dal 2009','Gestione in italiano, inglese, francese e spagnolo'],
      'en'=>['Hostesses and stewards for fairs, shows and events','20,000+ profiles in our database','15+ years of experience, since 2009','Management in Italian, English, French and Spanish'],
      'fr'=>['Hôtesses et stewards pour salons et événements','Plus de 20 000 profils en base','Plus de 15 ans d\'expérience, depuis 2009','Gestion en italien, anglais, français et espagnol'],
      'es'=>['Azafatas y stewards para ferias, salones y eventos','Más de 20.000 perfiles en la base de datos','Más de 15 años de experiencia, desde 2009','Gestión en italiano, inglés, francés y español'],
    ],
  ],
  'casting-italia' => [
    'h1'  => ['it'=>'Agenzia casting e modelli in Italia per aziende e produzioni','en'=>'Casting and model agency in Italy for companies and productions','fr'=>'Agence de casting et mannequins en Italie pour entreprises et productions','es'=>'Agencia de casting y modelos en Italia para empresas y producciones'],
    'sub' => ['it'=>'Modelli, attori, comparse e hostess per campagne, shooting e produzioni in tutta Italia. Preventivo gratuito in 24 ore.','en'=>'Models, actors, extras and hostesses for campaigns, shoots and productions across Italy. Free quote within 24 hours.','fr'=>'Mannequins, acteurs, figurants et hôtesses pour campagnes, shootings et productions partout en Italie. Devis gratuit sous 24 heures.','es'=>'Modelos, actores, figurantes y azafatas para campañas, sesiones y producciones en toda Italia. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili in tutte le regioni italiane','15+ anni di esperienza con brand, e-commerce e produzioni','Un solo contatto per casting a Milano, Roma, Torino e in tutta Italia','Gestione progetto in 4 lingue'],
      'en'=>['20,000+ profiles across every Italian region','15+ years of experience with brands, e-commerce and productions','Single point of contact for castings in Milan, Rome, Turin and all of Italy','Project management in 4 languages'],
      'fr'=>['Plus de 20 000 profils dans toutes les régions d\'Italie','Plus de 15 ans d\'expérience avec marques, e-commerce et productions','Un seul interlocuteur pour vos castings à Milan, Rome, Turin et dans toute l\'Italie','Gestion de projet en 4 langues'],
      'es'=>['Más de 20.000 perfiles en todas las regiones de Italia','Más de 15 años de experiencia con marcas, e-commerce y producciones','Un único contacto para castings en Milán, Roma, Turín y toda Italia','Gestión de proyectos en 4 idiomas'],
    ],
  ],
  'models-aziende' => [
    'h1'  => ['it'=>'Agenzia di modelle e modelli per aziende e produzioni','en'=>'Model agency for companies and productions','fr'=>'Agence de mannequins pour entreprises et productions','es'=>'Agencia de modelos para empresas y producciones'],
    'sub' => ['it'=>'Modelle e modelli selezionati per shooting, campagne, spot ed eventi. Preventivo gratuito in 24 ore.','en'=>'Selected models for shoots, campaigns, commercials and events. Free quote within 24 hours.','fr'=>'Mannequins sélectionnés pour shootings, campagnes, spots et événements. Devis gratuit sous 24 heures.','es'=>'Modelos seleccionados para sesiones, campañas, spots y eventos. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili verificati nel database','15+ anni di esperienza, dal 2009','Operativi in tutta Italia','Casting gestiti in 4 lingue'],
      'en'=>['20,000+ verified profiles in our database','15+ years of experience, since 2009','Active throughout Italy','Casting managed in 4 languages'],
      'fr'=>['Plus de 20 000 profils vérifiés en base','Plus de 15 ans d\'expérience, depuis 2009','Actifs dans toute l\'Italie','Casting géré en 4 langues'],
      'es'=>['Más de 20.000 perfiles verificados en la base de datos','Más de 15 años de experiencia, desde 2009','Operativos en toda Italia','Casting gestionado en 4 idiomas'],
    ],
    'serv' => ['it'=>'Modelle e modelli per campagne, e-commerce, sfilate ed eventi; casting mirato per fisico, età e caratteristiche; staff multilingue per produzioni internazionali. Contratti e compensi gestiti da noi.','en'=>'Models for campaigns, e-commerce, runway and events; targeted casting by look, age and features; multilingual staff for international productions. Contracts and fees handled by us.','fr'=>'Mannequins pour campagnes, e-commerce, défilés et événements ; casting ciblé par physique, âge et caractéristiques ; personnel multilingue pour productions internationales. Contrats et rémunérations gérés par nos soins.','es'=>'Modelos para campañas, e-commerce, desfiles y eventos; casting específico por físico, edad y características; personal multilingüe para producciones internacionales. Contratos y honorarios gestionados por nosotros.'],
  ],
  'hostess-eventi' => [
    'h1'  => ['it'=>'Agenzia hostess, steward e promoter per fiere ed eventi','en'=>'Hostess, steward and promoter agency for trade fairs and events','fr'=>'Agence d\'hôtesses, stewards et promoteurs pour salons et événements','es'=>'Agencia de azafatas, stewards y promotores para ferias y eventos'],
    'sub' => ['it'=>'Personale selezionato per fiere, congressi ed eventi aziendali in tutta Italia. Team completi in 24 ore.','en'=>'Selected staff for trade fairs, congresses and corporate events throughout Italy. Full teams within 24 hours.','fr'=>'Personnel sélectionné pour salons, congrès et événements d\'entreprise dans toute l\'Italie. Équipes complètes sous 24 heures.','es'=>'Personal seleccionado para ferias, congresos y eventos corporativos en toda Italia. Equipos completos en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili verificati nel database','15+ anni di esperienza, dal 2009','Hostess e staff multilingue','Operativi in tutta Italia'],
      'en'=>['20,000+ verified profiles in our database','15+ years of experience, since 2009','Multilingual hostesses and staff','Active throughout Italy'],
      'fr'=>['Plus de 20 000 profils vérifiés en base','Plus de 15 ans d\'expérience, depuis 2009','Hôtesses et personnel multilingue','Actifs dans toute l\'Italie'],
      'es'=>['Más de 20.000 perfiles verificados en la base de datos','Más de 15 años de experiencia, desde 2009','Azafatas y personal multilingüe','Operativos en toda Italia'],
    ],
    'serv' => ['it'=>'Hostess e steward per fiere e stand; promoter per attività promozionali e GDO; staff per congressi ed eventi corporate. Gestione completa: contratti, compensi e coordinamento sul posto.','en'=>'Hostesses and stewards for trade fairs and stands; promoters for promotional activities and retail; staff for congresses and corporate events. Full management: contracts, fees and on-site coordination.','fr'=>'Hôtesses et stewards pour salons et stands ; promoteurs pour activités promotionnelles et grande distribution ; personnel pour congrès et événements corporate. Gestion complète : contrats, rémunérations et coordination sur place.','es'=>'Azafatas y stewards para ferias y stands; promotores para actividades promocionales y retail; personal para congresos y eventos corporativos. Gestión completa: contratos, honorarios y coordinación in situ.'],
  ],
  'attori-produzioni' => [
    'h1'  => ['it'=>'Agenzia di attori e comparse per produzioni, spot e cinema','en'=>'Actors and extras agency for productions, commercials and film','fr'=>'Agence d\'acteurs et figurants pour productions, spots et cinéma','es'=>'Agencia de actores y figurantes para producciones, spots y cine'],
    'sub' => ['it'=>'Attori, attrici e comparse selezionati per spot pubblicitari, cinema, serie TV e contenuti video. Preventivo gratuito in 24 ore.','en'=>'Selected actors, actresses and extras for commercials, film, TV series and video content. Free quote within 24 hours.','fr'=>'Acteurs, actrices et figurants sélectionnés pour spots publicitaires, cinéma, séries TV et contenus vidéo. Devis gratuit sous 24 heures.','es'=>'Actores, actrices y figurantes seleccionados para spots publicitarios, cine, series de TV y contenidos de vídeo. Presupuesto gratuito en 24 horas.'],
    'bul' => [
      'it'=>['20.000+ profili verificati nel database','15+ anni di esperienza, dal 2009','Attori, caratteristi e comparse per ogni ruolo','Operativi in tutta Italia'],
      'en'=>['20,000+ verified profiles in our database','15+ years of experience, since 2009','Actors, character actors and extras for every role','Active throughout Italy'],
      'fr'=>['Plus de 20 000 profils vérifiés en base','Plus de 15 ans d\'expérience, depuis 2009','Acteurs, seconds rôles et figurants pour chaque rôle','Actifs dans toute l\'Italie'],
      'es'=>['Más de 20.000 perfiles verificados en la base de datos','Más de 15 años de experiencia, desde 2009','Actores, secundarios y figurantes para cada papel','Operativos en toda Italia'],
    ],
    'serv' => ['it'=>'Attori e attrici per spot, cinema, serie TV e web; comparse e figurazioni per produzioni di ogni dimensione; casting mirato per età, fisico e caratteristiche. Contratti e compensi gestiti da noi.','en'=>'Actors and actresses for commercials, film, TV series and web; extras and background for productions of any size; targeted casting by age, look and features. Contracts and fees handled by us.','fr'=>'Acteurs et actrices pour spots, cinéma, séries TV et web ; figurants pour productions de toute taille ; casting ciblé par âge, physique et caractéristiques. Contrats et rémunérations gérés par nos soins.','es'=>'Actores y actrices para spots, cine, series de TV y web; figurantes para producciones de cualquier tamaño; casting específico por edad, físico y características. Contratos y honorarios gestionados por nosotros.'],
  ],
];

$eyebrow_l = ['it'=>'AGENZIA CASTING B2B · DAL 2009','en'=>'B2B CASTING AGENCY · SINCE 2009','fr'=>'AGENCE DE CASTING B2B · DEPUIS 2009','es'=>'AGENCIA DE CASTING B2B · DESDE 2009'];
$call_l    = ['it'=>'Chiama','en'=>'Call','fr'=>'Appeler','es'=>'Llamar'];
$email_l   = ['it'=>'Email','en'=>'Email','fr'=>'Email','es'=>'Email'];
$db_l      = ['it'=>'Visita il nostro database','en'=>'Browse our talent database','fr'=>'Voir notre base de talents','es'=>'Explora nuestra base de talentos'];
$DB_URL    = 'https://toagency.it/talent-database/'; // FIX 2026-06-20 marco — era toadatabase.it (sito vecchio)

// FIX 2026-06-24 marco — form protagonista + WhatsApp taggato (lead qualificabile)
$formhead_l   = ['it'=>'Ricevi una selezione di profili e un preventivo gratuito in 24h','en'=>'Get a shortlist of profiles and a free quote within 24h','fr'=>'Recevez une sélection de profils et un devis gratuit sous 24h','es'=>'Recibe una selección de perfiles y un presupuesto gratuito en 24h'];
$contacthint_l= ['it'=>'Preferisci sentirci subito?','en'=>'Prefer to talk now?','fr'=>'Vous préférez nous contacter tout de suite ?','es'=>'¿Prefieres contactarnos ya?'];
// FIX 2026-07-10 marco — WhatsApp card pari peso al form, con etichetta B2B integrata (evitare click di talent)
$wabig_l      = ['it'=>'Preferisci scriverci subito?','en'=>'Prefer to message us right away?','fr'=>'Vous préférez nous écrire tout de suite ?','es'=>'¿Prefieres escribirnos ahora mismo?'];
// Messaggio WhatsApp precompilato per landing → l'utente arriva già contestualizzato (B2B vs talent)
$wa_type  = (strpos($key, 'hostess') === 0) ? 'hostess' : ((strpos($key, 'attori') === 0) ? 'attori' : 'models');
$wa_label = [
  'models'  => ['it'=>'Modelli per aziende','en'=>'Models for companies','fr'=>'Mannequins pour entreprises','es'=>'Modelos para empresas'],
  'hostess' => ['it'=>'Hostess per eventi','en'=>'Hostess for events','fr'=>'Hôtesses pour événements','es'=>'Azafatas para eventos'],
  'attori'  => ['it'=>'Attori e comparse','en'=>'Actors and extras','fr'=>'Acteurs et figurants','es'=>'Actores y figurantes'],
];
$wa_tmpl  = ['it'=>'Ciao TOAgency, scrivo dalla pagina %s. Ho un progetto e sono interessato a ...','en'=>'Hi TOAgency, I\'m writing from the %s page. I have a project and I\'m interested in ...','fr'=>'Bonjour TOAgency, je vous écris depuis la page %s. J\'ai un projet et je suis intéressé par ...','es'=>'Hola TOAgency, escribo desde la página %s. Tengo un proyecto y estoy interesado en ...'];
$wa_text  = sprintf($wa_tmpl[$lang] ?? $wa_tmpl['it'], $wa_label[$wa_type][$lang] ?? $wa_label[$wa_type]['it']);
$WA_HREF  = $WA . '?text=' . rawurlencode($wa_text);

// FIX 2026-06-24 marco — filtro self-exit talent (no gate, form intatto)
$b2bonly_l    = ['it'=>'Servizio per aziende, agenzie e produzioni — non per candidature.','en'=>'A service for companies, agencies and productions — not for job applications.','fr'=>'Un service pour entreprises, agences et productions — pas pour les candidatures.','es'=>'Un servicio para empresas, agencias y producciones — no para candidaturas.'];
$talentexit_l = ['it'=>'Sei un talent in cerca di lavoro? Registrati qui','en'=>'Are you a talent looking for work? Register here','fr'=>'Vous êtes un talent à la recherche de travail ? Inscrivez-vous ici','es'=>'¿Eres un talento que busca trabajo? Regístrate aquí'];
$REG_TALENT   = 'https://toagency.it/registrati-talent/';
$trust_l = ['it'=>'4,7/5 · 346 recensioni Google · dal 2009 · 20.000+ profili verificati','en'=>'4.7/5 · 346 Google reviews · since 2009 · 20,000+ verified profiles','fr'=>'4,7/5 · 346 avis Google · depuis 2009 · 20 000+ profils vérifiés','es'=>'4,7/5 · 346 reseñas Google · desde 2009 · 20.000+ perfiles verificados'];

$c   = $COPY[$key] ?? $COPY['casting-italia'];
$h1  = $c['h1'][$lang]  ?? $c['h1']['it'];
$sub = $c['sub'][$lang] ?? $c['sub']['it'];
$bullets = $c['bul'][$lang] ?? $c['bul']['it'];
$eyebrow = $eyebrow_l[$lang] ?? $eyebrow_l['it'];
$serv = isset($c['serv']) ? ($c['serv'][$lang] ?? $c['serv']['it']) : '';
?><!DOCTYPE html>
<html lang="<?php echo esc_attr($lang); ?>">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow,noarchive">
<?php wp_head(); ?>
<style>
/* Landing Ads — tema NERO on-brand, scoped. body.toa-ads-lp + !important per battere il CSS del tema. */
body.toa-ads-lp{margin:0!important;background:#0a0a0a!important;color:#fff!important;-webkit-font-smoothing:antialiased}
body.toa-ads-lp .toa-ads-wrap{max-width:1160px;margin:0 auto;padding:26px 22px 56px}
body.toa-ads-lp .toa-ads-logo{display:block;height:38px;width:auto;margin:0 0 22px}
body.toa-ads-lp .toa-ads-grid{display:grid;grid-template-columns:1.05fr .95fr;gap:48px;align-items:start;min-height:auto}
body.toa-ads-lp .toa-ads-eyebrow{color:#c2f24e!important;font-size:12.5px;font-weight:700;letter-spacing:1.6px;margin:0 0 14px}
body.toa-ads-lp .toa-ads-h1{font-size:38px;line-height:1.14;font-weight:800;margin:0 0 16px;color:#fff!important;-webkit-text-fill-color:#fff!important;background:none!important}
body.toa-ads-lp .toa-ads-sub{font-size:18px;line-height:1.55;color:#d2d2d2!important;margin:0 0 24px}
body.toa-ads-lp .toa-ads-bullets{list-style:none!important;margin:0 0 26px;padding:0}
body.toa-ads-lp .toa-ads-bullets li{position:relative;padding:8px 0 8px 30px;font-size:15.5px;color:#ededed!important;border-bottom:1px solid #232323;list-style:none!important}
body.toa-ads-lp .toa-ads-bullets li:before{content:"";position:absolute;left:2px;top:12px;width:15px;height:8px;border-left:3px solid #c2f24e;border-bottom:3px solid #c2f24e;transform:rotate(-45deg)}
body.toa-ads-lp .toa-ads-serv{color:#a9a9a9!important;font-size:14px;line-height:1.55;margin:0 0 22px;max-width:560px}
body.toa-ads-lp .toa-ads-contact{display:flex;flex-wrap:wrap;gap:10px}
body.toa-ads-lp .toa-ads-btn{display:inline-flex;align-items:center;gap:7px;padding:11px 18px;border-radius:9px;text-decoration:none!important;font-weight:700;font-size:14.5px;border:1.5px solid #3a3a3a;color:#fff!important;background:transparent}
body.toa-ads-lp .toa-ads-btn--call{background:transparent!important;color:#fff!important;border-color:#3a3a3a} /* FIX 2026-06-24 marco — declassato da lime a outline: il form torna protagonista */
body.toa-ads-lp .toa-ads-btn:hover{border-color:#c2f24e}
body.toa-ads-lp .toa-ads-formhead{font-size:18px;line-height:1.35;font-weight:800;color:#fff!important;margin:0 0 14px;padding:0} /* FIX 2026-06-24 marco */
body.toa-ads-lp .toa-ads-contacthint{font-size:13px;color:#9a9a9a!important;margin:18px 0 8px;font-weight:600}
body.toa-ads-lp .toa-ads-b2bnotice{margin:0 0 14px;padding:11px 13px;border:1px solid #2a2a2a;border-left:3px solid #c2f24e;border-radius:8px;background:rgba(194,242,78,0.06)} /* FIX 2026-06-24 marco — avviso self-exit talent */
body.toa-ads-lp .toa-ads-b2bnotice b{display:block;font-size:13.5px;line-height:1.4;color:#fff!important;font-weight:700}
body.toa-ads-lp .toa-ads-b2bnotice a{display:inline-block;margin-top:6px;font-size:13px;color:#c2f24e!important;text-decoration:none!important;font-weight:600}
body.toa-ads-lp .toa-ads-b2bnotice a:hover{text-decoration:underline!important}
/* FIX 2026-07-10 marco — WhatsApp card pari peso al form, etichetta B2B integrata */
body.toa-ads-lp .toa-ads-wacard{margin:0 0 22px;padding:18px 20px;border:1.5px solid #2a2a2a;border-left:4px solid #c2f24e;border-radius:10px;background:rgba(194,242,78,0.06)}
body.toa-ads-lp .toa-ads-wacard-head{font-size:16px;font-weight:800;color:#fff!important;margin:0 0 6px}
body.toa-ads-lp .toa-ads-wacard-b2b{font-size:13px;color:#c2f24e!important;font-weight:700;margin:0 0 12px;line-height:1.4}
body.toa-ads-lp .toa-ads-btn--wabig{display:inline-flex;align-items:center;gap:8px;padding:13px 22px;border-radius:9px;text-decoration:none!important;font-weight:800;font-size:15.5px;background:#c2f24e!important;color:#0a0a0a!important;border:none}
body.toa-ads-lp .toa-ads-btn--wabig:hover{filter:brightness(1.08)}
body.toa-ads-lp .toa-ads-dblink{display:inline-block;margin-top:18px;color:#c2f24e!important;text-decoration:none!important;font-weight:600;font-size:14.5px;border-bottom:1px solid transparent}
body.toa-ads-lp .toa-ads-dblink:hover{border-bottom-color:#c2f24e}
/* il form (form-b2b-inline) è già una card stilata dal tema */
body.toa-ads-lp .toa-ads-formcol .cta-section{padding:0!important;margin:0!important;background:transparent!important}
body.toa-ads-lp .toa-ads-formcol .container{padding:0!important;max-width:none!important}
body.toa-ads-lp .toa-ads-trust{display:inline-flex;flex-wrap:wrap;align-items:center;gap:8px;margin:0 0 18px;padding:9px 14px;border:1px solid #2a2a2a;border-radius:8px;background:rgba(194,242,78,0.06);color:#ededed!important;font-size:13.5px;font-weight:600;line-height:1.4}
body.toa-ads-lp .toa-ads-trust .stars{color:#c2f24e!important;letter-spacing:1px}
body.toa-ads-lp .toa-ads-footer{margin:44px 0 0;padding-top:18px;border-top:1px solid #232323;text-align:center}
body.toa-ads-lp .toa-ads-footer a{color:#8a8a8a!important;text-decoration:none!important;font-size:13px;margin:0 10px}
body.toa-ads-lp .toa-ads-footer a:hover{color:#c2f24e!important}
@media(max-width:880px){
  body.toa-ads-lp .toa-ads-grid{grid-template-columns:1fr;gap:28px}
  body.toa-ads-lp .toa-ads-h1{font-size:28px}
  body.toa-ads-lp .toa-ads-sub{font-size:16px;margin-bottom:18px}
}
</style>
</head>
<body class="toa-ads-lp">
<div class="toa-ads-wrap">

  <img class="toa-ads-logo" src="<?php echo esc_url($LOGO); ?>" alt="TOAgency" loading="eager">

  <div class="toa-ads-grid">
    <!-- COLONNA SINISTRA: messaggio + contatti -->
    <div class="toa-ads-msgcol">
      <p class="toa-ads-eyebrow"><?php echo esc_html($eyebrow); ?></p>
      <h1 class="toa-ads-h1"><?php echo esc_html($h1); ?></h1>
      <div class="toa-ads-trust"><span class="stars">★★★★★</span> <?php echo _ht($trust_l); ?></div>
      <p class="toa-ads-sub"><?php echo esc_html($sub); ?></p>
      <ul class="toa-ads-bullets">
        <?php foreach ($bullets as $b): ?>
          <li><?php echo esc_html($b); ?></li>
        <?php endforeach; ?>
      </ul>
      <?php if ($serv): ?><p class="toa-ads-serv"><?php echo esc_html($serv); ?></p><?php endif; ?>

      <!-- FIX 2026-07-10 marco — card WhatsApp pari peso al form, etichetta B2B integrata -->
      <div class="toa-ads-wacard">
        <p class="toa-ads-wacard-head"><?php echo _ht($wabig_l); ?></p>
        <p class="toa-ads-wacard-b2b"><?php echo _ht($b2bonly_l); ?></p>
        <a class="toa-ads-btn--wabig" href="<?php echo esc_url($WA_HREF); ?>" target="_blank" rel="noopener" onclick="toaLpTrack('whatsapp')">WhatsApp &rarr;</a>
      </div>

      <p class="toa-ads-contacthint"><?php echo _ht($contacthint_l); ?></p>
      <div class="toa-ads-contact">
        <a class="toa-ads-btn toa-ads-btn--call" href="tel:<?php echo esc_attr($TEL_RAW); ?>" onclick="toaLpTrack('call')">📞 <?php echo _ht($call_l); ?> <?php echo esc_html($TEL_DISP); ?></a>
        <a class="toa-ads-btn" href="<?php echo esc_url($WA_HREF); ?>" target="_blank" rel="noopener" onclick="toaLpTrack('whatsapp')">WhatsApp</a>
      </div>
    </div>

    <!-- COLONNA DESTRA: form preventivo (azione protagonista) -->
    <div class="toa-ads-formcol">
      <p class="toa-ads-formhead"><?php echo _ht($formhead_l); ?></p>
      <div class="toa-ads-b2bnotice">
        <b><?php echo _ht($b2bonly_l); ?></b>
        <a href="<?php echo esc_url($REG_TALENT); ?>"><?php echo _ht($talentexit_l); ?> &rarr;</a>
      </div>
      <?php toa_component('form-b2b-inline'); ?>
    </div>
  </div>

  <footer class="toa-ads-footer">
    <a href="<?php echo esc_url($DB_URL); ?>" target="_blank" rel="noopener" onclick="toaLpTrack('database')"><?php echo _ht($db_l); ?> &rarr;</a>
  </footer>

</div>

<script>
/* tracciamento click contatti (GTM/Ads possono creare conversioni da questi eventi) */
window.dataLayer = window.dataLayer || [];
function toaLpTrack(ch){
  try { window.dataLayer.push({event:'lp_contact_click', lp_channel:ch, lp_key:<?php echo json_encode($key); ?>}); } catch(e){}
}
/* pre-seleziona il servizio in base alla landing */
(function(){
  var sel = document.getElementById('hq_event_type');
  if (sel) { var v = <?php echo json_encode($default_service); ?>; if (v) { try { sel.value = v; } catch(e){} } }
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
