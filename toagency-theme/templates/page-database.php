<?php
/**
 * Template Name: Database - Scegli il ruolo
 * Pagina hub «Database» (TEMA DATABASE-MENU, 19/11/2026) — IT/EN/FR/ES.
 * Si sceglie il gruppo (Staff Eventi · Modelli & Attori · Creator · Foto, Video & Beauty), poi il ruolo: i profili compaiono subito.
 * DATI SOLO dal CRM, mai calcolati dal tema:
 *   Talent database  /actions/api-talent-database.php?action=search   (hostess/steward, modelli, over 50, bambini, attori, comparse)
 *   Creative Network /crm_toagency/actions/crew-public-search.php     (DJ, security, tecnici, fotografi, make-up, stylist…)
 *   Creator          /actions/creator-vetrina-public.php?action=vetrina (solo creator con video: mai creator con la sola foto)
 * Regole: solo provincia (mai comune), minorenni senza foto, nessun prezzo scritto qui, nessun URL SEO esistente toccato.
 * Render: assets/database-hub.js · Stile: assets/database-hub.css
 * NOINDEX finche' Marco non approva: per accendere l'indicizzazione mettere TOA_DATABASE_HUB_INDEX a true (una riga).
 */
if (!defined('TOA_DATABASE_HUB_INDEX')) define('TOA_DATABASE_HUB_INDEX', false);
if (!TOA_DATABASE_HUB_INDEX) {
    add_filter('wp_robots', function ($r) { $r['noindex'] = true; $r['nofollow'] = true; unset($r['index'], $r['follow']); return $r; }, 1000);
}

$__l = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($__l, array('it', 'en', 'fr', 'es'), true)) $__l = 'it';
$_t = function ($a) use ($__l) { return isset($a[$__l]) ? $a[$__l] : $a['it']; };
$X = function ($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };

$tdir = get_template_directory();
$tdh_css = '/assets/database-hub.css';
$tdh_js  = '/assets/database-hub.js';
wp_enqueue_style('toa-database-hub', get_template_directory_uri() . $tdh_css, array(), (string) @filemtime($tdir . $tdh_css));
wp_enqueue_script('toa-database-hub', get_template_directory_uri() . $tdh_js, array(), (string) @filemtime($tdir . $tdh_js), true);

// URL nella lingua giusta (mai a mano: toa_mg_url legge la traduzione WPML, se manca ricade sulla pagina italiana + ?lang=)
$mg = function ($path, $lp = false) use ($__l) {
    $u = function_exists('toa_mg_url') ? toa_mg_url($path, $__l, '', $lp) : '';
    return $u ? $u : home_url($path);
};
$addq = function ($u, $q) { return $u . (strpos($u, '?') !== false ? '&' : '?') . $q; };
$u_tal  = $mg('/talent-database/');
$u_crew = $mg('/crew-database/', true);
$u_cr   = $mg('/creators/');
$u_form = $mg('/form-b2b/');

// ─── ruoli: nome nella lingua · sorgente · filtro/categorie · link al database completo ───
$R = array(
    'hostess'       => array($X('Hostess & Steward', 'Hostesses & Stewards', 'Hôtesses & Stewards', 'Azafatas y Auxiliares'), 'talent', array('ruolo' => 'hostess'), $addq($u_tal, 'ruolo=hostess')),
    'dj'            => array($X('DJ', 'DJs', 'DJ', 'DJ'), 'crew', array('dj'), $addq($u_crew, 'cat=dj')),
    'bartender'     => array($X('Bartender', 'Bartenders', 'Barmans', 'Bartenders'), 'crew', array('bartender'), $addq($u_crew, 'cat=bartender')),
    'cameriere'     => array($X('Camerieri', 'Waiters', 'Serveurs', 'Camareros'), 'crew', array('cameriere'), $addq($u_crew, 'cat=cameriere')),
    'security'      => array($X('Security', 'Security', 'Sécurité', 'Seguridad'), 'crew', array('security'), $addq($u_crew, 'cat=security')),
    'tecnici'       => array($X('Tecnici', 'Technicians', 'Techniciens', 'Técnicos'), 'crew', array('tecnico_luci', 'tecnico_suono'), $u_crew),
    'model'         => array($X('Modelli', 'Models', 'Mannequins', 'Modelos'), 'talent', array('ruolo' => 'model'), $addq($u_tal, 'ruolo=model')),
    'over50'        => array($X('Over 50', 'Over 50', 'Plus de 50 ans', 'Más de 50 años'), 'talent', array('ruolo' => 'model', 'eta_min' => 50), $addq($u_tal, 'ruolo=model&eta_min=50')),
    'kids'          => array($X('Bambini e Ragazzi', 'Kids & Teens', 'Enfants & Ados', 'Niños y Jóvenes'), 'talent', array('eta_max' => 17), $addq($u_tal, 'ruolo=kids')),
    'actor'         => array($X('Attori', 'Actors', 'Acteurs', 'Actores'), 'talent', array('ruolo' => 'actor'), $addq($u_tal, 'ruolo=actor')),
    'comparsa'      => array($X('Comparse', 'Extras', 'Figurants', 'Figurantes'), 'talent', array('ruolo' => 'comparsa'), $addq($u_tal, 'ruolo=comparsa')),
    'promoter'      => array($X('Promoter', 'Promoters', 'Promoteurs', 'Promotores'), 'contact', array(), ''),
    'creator'       => array($X('Creator', 'Creators', 'Créateurs', 'Creadores'), 'creator', array(), $u_cr . '#vetrina'),
    'fotografo'     => array($X('Fotografi', 'Photographers', 'Photographes', 'Fotógrafos'), 'crew', array('fotografo'), $addq($u_crew, 'cat=fotografo')),
    'videomaker'    => array($X('Videomaker', 'Videographers', 'Vidéastes', 'Videógrafos'), 'crew', array('videomaker'), $addq($u_crew, 'cat=videomaker')),
    'postproduzione'=> array($X('Post-Produzione Foto', 'Photo Post-Production', 'Post-Production Photo', 'Postproducción Foto'), 'crew', array('postproduzione'), $addq($u_crew, 'cat=postproduzione')),
    'video_editing' => array($X('Montaggio Video', 'Video Editing', 'Montage Vidéo', 'Edición de Vídeo'), 'crew', array('video_editing'), $addq($u_crew, 'cat=video_editing')),
    'art_director'  => array($X('Art Director', 'Art Directors', 'Directeurs Artistiques', 'Directores de Arte'), 'crew', array('art_director'), $addq($u_crew, 'cat=art_director')),
    'social_media'  => array($X('Social Media Manager', 'Social Media Managers', 'Social Media Managers', 'Social Media Managers'), 'crew', array('social_media'), $addq($u_crew, 'cat=social_media')),
    'makeup_artist' => array($X('Make-up Artist', 'Make-up Artists', 'Maquilleurs', 'Maquilladores'), 'crew', array('makeup_artist'), $addq($u_crew, 'cat=makeup_artist')),
    'hairstylist'   => array($X('Hair Stylist', 'Hair Stylists', 'Coiffeurs Studio', 'Estilistas de Peluquería'), 'crew', array('hairstylist'), $addq($u_crew, 'cat=hairstylist')),
    'parrucchiere'  => array($X('Parrucchieri', 'Hairdressers', 'Coiffeurs', 'Peluqueros'), 'crew', array('parrucchiere'), $addq($u_crew, 'cat=parrucchiere')),
    'stylist'       => array($X('Stylist', 'Stylists', 'Stylistes', 'Estilistas'), 'crew', array('stylist'), $addq($u_crew, 'cat=stylist')),
    'fashion_designer' => array($X('Fashion Designer', 'Fashion Designers', 'Créateurs de Mode', 'Diseñadores de Moda'), 'crew', array('fashion_designer'), $addq($u_crew, 'cat=fashion_designer')),
);
// gruppi: chiave => [nome, riga sotto, ruoli (chiave => minimo di profili perche' la pillola compaia; solo ruoli crew)]
$G = array(
    'eventi'  => array($X('Staff Eventi', 'Event Staff', 'Staff Événementiel', 'Personal para Eventos'),
                       $X('Hostess, Steward, DJ, Tecnici', 'Hostesses, Stewards, DJs, Technicians', 'Hôtesses, Stewards, DJ, Techniciens', 'Azafatas, Auxiliares, DJ, Técnicos'),
                       array('hostess' => 0, 'promoter' => 0, 'dj' => 0, 'bartender' => 0, 'cameriere' => 0, 'security' => 0, 'tecnici' => 0)),
    'modelli' => array($X('Modelli & Attori', 'Models & Actors', 'Mannequins & Acteurs', 'Modelos y Actores'),
                       $X('Modelli, Bambini, Over 50, Attori, Comparse', 'Models, Kids, Over 50, Actors, Extras', 'Mannequins, Enfants, Plus de 50 ans, Acteurs, Figurants', 'Modelos, Niños, Más de 50, Actores, Figurantes'),
                       array('model' => 0, 'over50' => 0, 'kids' => 0, 'actor' => 0, 'comparsa' => 0)),
    'creator' => array($X('Creator', 'Creators', 'Créateurs', 'Creadores'),
                       $X('Video UGC, Social, Brand', 'UGC Videos, Social, Brands', 'Vidéos UGC, Réseaux, Marques', 'Vídeos UGC, Redes, Marcas'),
                       array('creator' => 0)),
    'foto'    => array($X('Foto, Video & Beauty', 'Photo, Video & Beauty', 'Photo, Vidéo & Beauty', 'Foto, Vídeo & Beauty'),
                       $X('Fotografi, Videomaker, Make-up, Stylist', 'Photographers, Videographers, Make-up, Stylists', 'Photographes, Vidéastes, Maquillage, Stylistes', 'Fotógrafos, Videógrafos, Maquillaje, Estilistas'),
                       array('fotografo' => 0, 'videomaker' => 0, 'postproduzione' => 0, 'video_editing' => 0, 'art_director' => 0, 'social_media' => 0,
                             'makeup_artist' => 0, 'hairstylist' => 0, 'parrucchiere' => 0, 'stylist' => 0, 'fashion_designer' => 0)),
);

$groups = array();
foreach ($G as $gk => $g) {
    $roles = array();
    foreach ($g[2] as $rk => $min) {
        $r = $R[$rk];
        $role = array('k' => $rk, 'n' => $_t($r[0]), 'src' => $r[1], 'min' => $min, 'all' => $r[3]);
        if ($r[1] === 'talent') $role['q'] = $r[2]; elseif ($r[1] === 'crew') $role['cats'] = $r[2];
        $roles[] = $role;
    }
    $groups[] = array('k' => $gk, 'n' => $_t($g[0]), 'tag' => $_t($g[1]), 'roles' => $roles);
}

$T = array(
    'eyebrow' => $X('TOAgency / Database', 'TOAgency / Database', 'TOAgency / Base de talents', 'TOAgency / Base de datos'),
    'h1'      => $X('Scegli il ruolo. <em>Vedi i profili.</em>', 'Pick the role. <em>See the profiles.</em>', 'Choisissez le rôle. <em>Voyez les profils.</em>', 'Elige el rol. <em>Mira los perfiles.</em>'),
    'lead'    => $X('Un solo database per tutte le figure: staff per eventi, modelli e attori, creator, foto, video e beauty.',
                    'One database for every role: event staff, models and actors, creators, photo, video and beauty.',
                    'Une seule base pour tous les profils : staff événementiel, mannequins et acteurs, créateurs, photo, vidéo et beauty.',
                    'Una sola base para todos los perfiles: personal para eventos, modelos y actores, creadores, foto, vídeo y beauty.'),
    'altri_h' => $X('Database completi', 'Full databases', 'Bases complètes', 'Bases completas'),
    'cta_h'   => $X('Preferisci che scegliamo noi?', 'Rather we choose for you?', 'Vous préférez que l’on choisisse ?', '¿Prefieres que elijamos nosotros?'),
    'cta_p'   => $X('Ti mandiamo una selezione su misura.', 'We’ll send you a tailored shortlist.', 'Nous vous envoyons une sélection sur mesure.', 'Te enviamos una selección a medida.'),
    'cta_b'   => $X('Richiedi preventivo', 'Request a quote', 'Demander un devis', 'Pide presupuesto'),
);
$ui = array(
    'all'    => $X('Vedi tutti i profili', 'See all profiles', 'Voir tous les profils', 'Ver todos los perfiles'),
    'more'   => $X('Mostra altri', 'Show more', 'Voir plus', 'Ver más'),
    'err'    => $X('Non riusciamo a caricare i profili. Riprova tra poco.', 'We can’t load the profiles right now. Please try again shortly.', 'Impossible de charger les profils pour le moment. Réessayez dans un instant.', 'Ahora mismo no podemos cargar los perfiles. Inténtalo de nuevo en un momento.'),
    'empty'  => $X('Nessun profilo disponibile per ora.', 'No profiles available right now.', 'Aucun profil disponible pour le moment.', 'No hay perfiles disponibles por ahora.'),
    'lock'   => $X('Profilo protetto', 'Protected profile', 'Profil protégé', 'Perfil protegido'),
    'years'  => $X('anni', 'yrs', 'ans', 'años'),
    'tutti'  => $X('Tutti i Creator', 'All Creators', 'Tous les Créateurs', 'Todos los Creadores'),
    'video'  => $X('Con video', 'With video', 'Avec vidéo', 'Con vídeo'),
    'cH'     => $X('Profili su richiesta', 'Profiles on request', 'Profils sur demande', 'Perfiles bajo petición'),
    'cP'     => $X('Per questo ruolo i profili non sono ancora online. Scrivici cosa ti serve e ti mandiamo una selezione.',
                   'Profiles for this role aren’t online yet. Tell us what you need and we’ll send you a shortlist.',
                   'Les profils de ce rôle ne sont pas encore en ligne. Dites-nous ce qu’il vous faut et nous vous envoyons une sélection.',
                   'Los perfiles de este rol aún no están en línea. Cuéntanos qué necesitas y te enviamos una selección.'),
    'cB'     => $X('Contattaci', 'Contact us', 'Nous contacter', 'Contáctanos'),
);
$ui_l = array(); foreach ($ui as $k => $v) $ui_l[$k] = $_t($v);

$cfg = array(
    'lang' => $__l,
    'ep' => array('talent' => '/actions/api-talent-database.php', 'crew' => '/crm_toagency/actions/crew-public-search.php', 'crewProfile' => '/crm_toagency/actions/crew-public-profile.php', 'creator' => '/actions/creator-vetrina-public.php'),
    'base' => array('talent' => $u_tal, 'crew' => $u_crew),
    'few' => 3,
    'groups' => $groups,
    'ui' => $ui_l,
);

toa_component('header');
?>
<main class="tdh" id="tdh">
  <section class="tdh-hero">
    <div class="tdh-eyebrow"><?php echo esc_html($_t($T['eyebrow'])); ?></div>
    <h1 class="tdh-h1"><?php echo wp_kses($_t($T['h1']), array('em' => array())); ?></h1>
    <p class="tdh-lead"><?php echo esc_html($_t($T['lead'])); ?></p>
  </section>

  <section class="tdh-groups" role="tablist" aria-label="<?php echo esc_attr($_t($T['eyebrow'])); ?>">
    <?php foreach ($groups as $g): ?>
    <button type="button" class="tdh-g" role="tab" data-g="<?php echo esc_attr($g['k']); ?>" aria-selected="false">
      <span class="tdh-g__n"><?php echo esc_html($g['n']); ?></span>
      <span class="tdh-g__t"><?php echo esc_html($g['tag']); ?></span>
    </button>
    <?php endforeach; ?>
  </section>

  <section class="tdh-roles" id="tdhRoles" role="group" aria-live="polite"></section>

  <section class="tdh-res" id="tdhRes" aria-live="polite">
    <div class="tdh-bar">
      <h2 class="tdh-h2" id="tdhTitle"></h2>
      <a class="tdh-all" id="tdhAll" href="#"><?php echo esc_html($ui_l['all']); ?> →</a>
    </div>
    <div class="tdh-grid" id="tdhGrid"></div>
    <div class="tdh-ask" id="tdhAsk" hidden>
      <h3 class="tdh-ask__h"><?php echo esc_html($ui_l['cH']); ?></h3>
      <p><?php echo esc_html($ui_l['cP']); ?></p>
      <a class="tdh-btn" id="tdhAskB" href="<?php echo esc_url($u_form); ?>"><?php echo esc_html($ui_l['cB']); ?></a>
    </div>
    <p class="tdh-msg" id="tdhMsg" hidden></p>
    <button type="button" class="tdh-more" id="tdhMore" hidden><?php echo esc_html($ui_l['more']); ?></button>
  </section>

  <section class="tdh-links">
    <h2 class="tdh-h2"><?php echo esc_html($_t($T['altri_h'])); ?></h2>
    <div class="tdh-chips">
      <a href="<?php echo esc_url($u_tal); ?>">Talent Database</a>
      <a href="<?php echo esc_url($u_crew); ?>">Creative Network</a>
      <a href="<?php echo esc_url($u_cr . '#vetrina'); ?>">TOA Creators</a>
      <?php foreach (array('hostess', 'model', 'over50', 'kids', 'actor', 'comparsa') as $rk): ?>
      <a href="<?php echo esc_url($R[$rk][3]); ?>"><?php echo esc_html($_t($R[$rk][0])); ?></a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="tdh-cta">
    <div>
      <h2 class="tdh-h2"><?php echo esc_html($_t($T['cta_h'])); ?></h2>
      <p><?php echo esc_html($_t($T['cta_p'])); ?></p>
    </div>
    <a class="tdh-btn" href="<?php echo esc_url($u_form); ?>"><?php echo esc_html($_t($T['cta_b'])); ?></a>
  </section>
</main>

<script type="application/json" id="tdhCfg"><?php echo wp_json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP); ?></script>

<?php toa_component('footer'); ?>
