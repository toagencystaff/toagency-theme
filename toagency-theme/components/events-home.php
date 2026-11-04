<?php
/**
 * NON e' un template di pagina: lo include templates/page-events.php (anteprima con ?nuova=1 per chi e' loggato,
 * poi diventa la home vera quando Marco approva). Home del mini sito TOA Events (ticket #637 14/10/2026; nuova grafica ticket #811 03/11/2026, chat TEMA-Events) — IT/EN/FR/ES.
 * STESSO URL /events/ e STESSI H1 e testi SEO di prima (hero_title, hero_subtitle, «Come lavoriamo»): cambia la grafica, si aggiungono blocchi.
 * Base bianca, testata e piede propri (components/events-site-head.php / events-site-foot.php), stile assets/css/events-site.css.
 * Figure SOLO dal catalogo CRM (components/events-catalog.php). Prezzo «a partire da» SOLO dal CRM (/actions/hostess-vetrina-public.php).
 * Vincoli: nessuna promessa di tempi o numeri di copertura; security e mansioni tecniche solo con personale in regola.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$tdir = get_template_directory();
wp_enqueue_style('toa-events-site', get_template_directory_uri() . '/assets/css/events-site.css', array(), (string) @filemtime($tdir . '/assets/css/events-site.css'));

$t = array(
    // testi SEO di prima (non cambiare senza controllare Search Console)
    'hero_title' => array(
        'it' => "Staff, crew e servizi per eventi.",
        'en' => "Staff, crew and services for events.",
        'fr' => "Personnel, équipes et services pour l'événementiel.",
        'es' => "Personal, equipos y servicios para eventos.",
    ),
    'hero_subtitle' => array(
        'it' => "TOA Events è la divisione di TOAgency per <strong>eventi, fiere, congressi e produzioni</strong>.<br>Staff, crew, foto e video e logistica: un unico referente, un unico preventivo.",
        'en' => "TOA Events is TOAgency's division for <strong>events, trade shows, conferences and productions</strong>.<br>Staff, crew, photo and video, and logistics: one point of contact, one quote.",
        'fr' => "TOA Events est la division de TOAgency dédiée aux <strong>événements, salons, congrès et productions</strong>.<br>Personnel, équipes, photo et vidéo, logistique : un seul interlocuteur, un seul devis.",
        'es' => "TOA Events es la división de TOAgency para <strong>eventos, ferias, congresos y producciones</strong>.<br>Personal, equipos, foto y vídeo y logística: un único interlocutor, un único presupuesto.",
    ),
    'talentexit' => array('it'=>"Sei un talent in cerca di lavoro? Registrati qui",'en'=>"Are you a talent looking for work? Register here",'fr'=>"Vous êtes un talent à la recherche de travail ? Inscrivez-vous ici",'es'=>"¿Eres un talento que busca trabajo? Regístrate aquí"),
    'feat_heading' => array('it'=>"Come lavoriamo",'en'=>"How we work",'fr'=>"Notre façon de travailler",'es'=>"Cómo trabajamos"),
    'feat1_title' => array('it'=>"Un solo referente",'en'=>"One point of contact",'fr'=>"Un seul interlocuteur",'es'=>"Un único interlocutor"),
    'feat1_text'  => array('it'=>"Una persona coordina staff, crew e produzione.",'en'=>"One person coordinates staff, crew and production.",'fr'=>"Une seule personne coordonne le personnel, les équipes et la production.",'es'=>"Una sola persona coordina personal, equipos y producción."),
    'feat2_title' => array('it'=>"Un solo preventivo",'en'=>"One quote",'fr'=>"Un seul devis",'es'=>"Un único presupuesto"),
    'feat2_text'  => array('it'=>"Tutti i servizi dell'evento in un'unica proposta.",'en'=>"All your event services in a single proposal.",'fr'=>"Tous les services de l'événement dans une seule proposition.",'es'=>"Todos los servicios del evento en una sola propuesta."),
    'feat3_title' => array('it'=>"Persone selezionate",'en'=>"Selected people",'fr'=>"Des profils sélectionnés",'es'=>"Perfiles seleccionados"),
    'feat3_text'  => array('it'=>"Profili scelti dal database TOAgency in base a ciò che serve al tuo evento.",'en'=>"Profiles chosen from the TOAgency database to match what your event needs.",'fr'=>"Des profils choisis dans la base TOAgency selon les besoins de votre événement.",'es'=>"Perfiles elegidos de la base de datos de TOAgency según lo que necesita tu evento."),
    'feat4_title' => array('it'=>"Risposta rapida",'en'=>"Fast reply",'fr'=>"Réponse rapide",'es'=>"Respuesta rápida"),
    'feat4_text'  => array('it'=>"Ti ricontattiamo subito.",'en'=>"We get back to you right away.",'fr'=>"Nous vous recontactons tout de suite.",'es'=>"Te contactamos enseguida."),
    // blocchi nuovi (#811)
    'eyebrow' => array('it'=>"Hostess · Steward · Promoter · Staff per eventi",'en'=>"Hostesses · Stewards · Promoters · Event staff",'fr'=>"Hôtesses · Stewards · Promoteurs · Personnel événementiel",'es'=>"Azafatas · Stewards · Promotores · Personal para eventos"),
    'cta_staff' => array('it'=>"Vedi staff e prezzi",'en'=>"See staff & prices",'fr'=>"Voir le personnel et les tarifs",'es'=>"Ver personal y precios"),
    'cta_sel'   => array('it'=>"Richiedi un preventivo",'en'=>"Get a quote",'fr'=>"Demander un devis",'es'=>"Pide presupuesto"),
    'rating'    => array('it'=>"4,7 su Google · 346 recensioni · dal 2009",'en'=>"4.7 on Google · 346 reviews · since 2009",'fr'=>"4,7 sur Google · 346 avis · depuis 2009",'es'=>"4,7 en Google · 346 reseñas · desde 2009"),
    'where_h'   => array('it'=>"Dove ti serve lo staff?",'en'=>"Where do you need staff?",'fr'=>"Où avez-vous besoin de personnel ?",'es'=>"¿Dónde necesitas personal?"),
    'where_p'   => array('it'=>"Scegli Paese e città: vedi subito le persone e il prezzo di partenza.",'en'=>"Pick a country and a city to see the people and the starting price straight away.",'fr'=>"Choisissez un pays et une ville : vous voyez tout de suite les profils et le prix de départ.",'es'=>"Elige país y ciudad: ves al momento los perfiles y el precio de partida."),
    'altri'     => array('it'=>"Altri Paesi +",'en'=>"Other countries +",'fr'=>"Autres pays +",'es'=>"Otros países +"),
    'citta_h'   => array('it'=>"Le città più richieste",'en'=>"Most requested cities",'fr'=>"Les villes les plus demandées",'es'=>"Las ciudades más solicitadas"),
    'tutte'     => array('it'=>"Tutte le città +",'en'=>"All cities +",'fr'=>"Toutes les villes +",'es'=>"Todas las ciudades +"),
    'price_lab' => array('it'=>"Hostess a partire da",'en'=>"Hostesses from",'fr'=>"Hôtesses à partir de",'es'=>"Azafatas desde"),
    'roles_h'   => array('it'=>"Completa lo staff",'en'=>"Complete your team",'fr'=>"Complétez votre équipe",'es'=>"Completa tu equipo"),
    'roles_p'   => array('it'=>"Oltre alle hostess, tutte le figure del tuo evento in un solo preventivo: un referente, una fattura.",'en'=>"Beyond hostesses, every role your event needs in a single quote: one contact, one invoice.",'fr'=>"Au-delà des hôtesses, tous les profils de votre événement dans un seul devis : un interlocuteur, une facture.",'es'=>"Además de azafatas, todos los perfiles de tu evento en un solo presupuesto: un interlocutor, una factura."),
    'svc_open'  => array('it'=>"Mostra tutti i servizi",'en'=>"Show all services",'fr'=>"Voir tous les services",'es'=>"Ver todos los servicios"),
    'svc_close' => array('it'=>"Mostra meno",'en'=>"Show less",'fr'=>"Voir moins",'es'=>"Ver menos"),
    'svc_more'  => array('it'=>"Scopri di più",'en'=>"Find out more",'fr'=>"En savoir plus",'es'=>"Descubre más"),
    'how_h'     => array('it'=>"Come funziona",'en'=>"How it works",'fr'=>"Comment ça marche",'es'=>"Cómo funciona"),
    'how' => array(
        array(array('it'=>"Guardi gli esempi",'en'=>"Browse the examples",'fr'=>"Parcourez les exemples",'es'=>"Mira los ejemplos"),
              array('it'=>"Capisci che figure puoi avere nella tua città e a che prezzo.",'en'=>"See which profiles you can get in your city, and at what price.",'fr'=>"Voyez quels profils sont disponibles dans votre ville, et à quel prix.",'es'=>"Descubre qué perfiles tienes en tu ciudad y a qué precio.")),
        array(array('it'=>"Ci dici data, orario e luogo",'en'=>"Tell us the date, hours and place",'fr'=>"Indiquez la date, les horaires et le lieu",'es'=>"Dinos fecha, horario y lugar"),
              array('it'=>"Cerchiamo chi è libero quel giorno, vicino al tuo evento.",'en'=>"We look for who is free that day, close to your event.",'fr'=>"Nous cherchons qui est libre ce jour-là, près de votre événement.",'es'=>"Buscamos quién está libre ese día, cerca de tu evento.")),
        array(array('it'=>"Scegli dalla tua selezione",'en'=>"Pick from your shortlist",'fr'=>"Choisissez dans votre sélection",'es'=>"Elige de tu selección"),
              array('it'=>"Ogni profilo con foto e prezzo: scegli tu in base al budget.",'en'=>"Every profile comes with photos and a price: you choose to fit your budget.",'fr'=>"Chaque profil avec photos et prix : vous choisissez selon votre budget.",'es'=>"Cada perfil con fotos y precio: eliges tú según tu presupuesto.")),
    ),
);

// Paesi in evidenza (Marco 03/11: sempre IT FR DE ES GB, poi gli altri) e citta' piu' richieste. id = quelli del CRM (filtri vetrina).
$paesi = array(
    'IT' => array('it'=>'Italia','en'=>'Italy','fr'=>'Italie','es'=>'Italia'),
    'FR' => array('it'=>'Francia','en'=>'France','fr'=>'France','es'=>'Francia'),
    'DE' => array('it'=>'Germania','en'=>'Germany','fr'=>'Allemagne','es'=>'Alemania'),
    'ES' => array('it'=>'Spagna','en'=>'Spain','fr'=>'Espagne','es'=>'España'),
    'GB' => array('it'=>'Regno Unito','en'=>'United Kingdom','fr'=>'Royaume-Uni','es'=>'Reino Unido'),
);
$citta = array( // paese, id CRM, nomi
    array('IT','MI',array('it'=>'Milano','en'=>'Milan','fr'=>'Milan','es'=>'Milán')),
    array('FR','Paris',array('it'=>'Parigi','en'=>'Paris','fr'=>'Paris','es'=>'París')),
    array('DE','Berlin',array('it'=>'Berlino','en'=>'Berlin','fr'=>'Berlin','es'=>'Berlín')),
    array('ES','Madrid',array('it'=>'Madrid','en'=>'Madrid','fr'=>'Madrid','es'=>'Madrid')),
    array('GB','London',array('it'=>'Londra','en'=>'London','fr'=>'Londres','es'=>'Londres')),
    array('IT','RM',array('it'=>'Roma','en'=>'Rome','fr'=>'Rome','es'=>'Roma')),
    array('DE','Frankfurt am Main',array('it'=>'Francoforte','en'=>'Frankfurt','fr'=>'Francfort','es'=>'Fráncfort')),
    array('ES','Barcelona',array('it'=>'Barcellona','en'=>'Barcelona','fr'=>'Barcelone','es'=>'Barcelona')),
    array('IT','TO',array('it'=>'Torino','en'=>'Turin','fr'=>'Turin','es'=>'Turín')),
    array('DE','München',array('it'=>'Monaco di Baviera','en'=>'Munich','fr'=>'Munich','es'=>'Múnich')),
    array('FR','Lyon',array('it'=>'Lione','en'=>'Lyon','fr'=>'Lyon','es'=>'Lyon')),
    array('DE','Düsseldorf',array('it'=>'Düsseldorf','en'=>'Düsseldorf','fr'=>'Düsseldorf','es'=>'Düsseldorf')),
    array('IT','NA',array('it'=>'Napoli','en'=>'Naples','fr'=>'Naples','es'=>'Nápoles')),
    array('ES','Valencia',array('it'=>'Valencia','en'=>'Valencia','fr'=>'Valence','es'=>'Valencia')),
    array('IT','FI',array('it'=>'Firenze','en'=>'Florence','fr'=>'Florence','es'=>'Florencia')),
);
// prima le citta' del Paese della lingua (fr -> Francia...), poi le altre: mai solo Italia
$paese_lang = array('it'=>'IT','fr'=>'FR','es'=>'ES','en'=>'GB');
$pl = $paese_lang[$lang];
usort($citta, function($a, $b) use ($pl) { return ($a[0] === $pl ? 0 : 1) - ($b[0] === $pl ? 0 : 1); });
$paesi = array_merge(array($pl => $paesi[$pl]), $paesi);

$CAT = include $tdir . '/components/events-catalog.php';
// Ticket #820 (giro 2): «Completa lo staff» = stesse card di «Tutti i servizi» (6 gruppi), le prime 2 visibili e il resto a tendina.
// La foto del gruppo Accoglienza qui è diversa da quelle del mosaico in alto (niente foto ripetute).
$home_foto = array('accoglienza' => 'accrediti');
$ev_pages = get_option('toa_events_pages', array());
$svc_url = function($k) use ($ev_pages, $lang) {
    return (isset($ev_pages[$k][$lang]) && get_post_status((int) $ev_pages[$k][$lang]) === 'publish') ? get_permalink((int) $ev_pages[$k][$lang]) : '';
};
$hero_foto = array('hostess-fiera','steward','hostess','promoter','interprete','accoglienza');

// pagine del mini sito (bozze/pubblicate) — funzione in events-site-head.php, qui serve prima della testata
$staff_url = '';
$ev_site = get_option('toa_events_site', array());
if (!empty($ev_site['staff'][$lang])) {
    $sid = (int) $ev_site['staff'][$lang];
    $st  = get_post_status($sid);
    if ($st === 'publish') $staff_url = get_permalink($sid); elseif ($st && current_user_can('edit_post', $sid)) $staff_url = add_query_arg('lang', $lang, get_permalink($sid));
}
$servizi_url = '';
if (!empty($ev_site['servizi'][$lang])) {
    $vid = (int) $ev_site['servizi'][$lang];
    $st  = get_post_status($vid);
    if ($st === 'publish') $servizi_url = get_permalink($vid); elseif ($st && current_user_can('edit_post', $vid)) $servizi_url = add_query_arg('lang', $lang, get_permalink($vid));
}
$lavora_url = '';
if (!empty($ev_site['lavora'][$lang])) {
    $lid = (int) $ev_site['lavora'][$lang];
    $st  = get_post_status($lid);
    if ($st === 'publish') $lavora_url = get_permalink($lid); elseif ($st && current_user_can('edit_post', $lid)) $lavora_url = add_query_arg('lang', $lang, get_permalink($lid));
}
if (!$lavora_url) {
    $reg = array('it'=>'/registrati-talent/','en'=>'/en/register-talent/','fr'=>'/fr/inscription-talent/','es'=>'/es/registrate-como-talent/');
    $lavora_url = untrailingslashit(get_option('home')) . $reg[$lang];
}
$staff_link = function($paese = '', $cid = '') use ($staff_url) {
    if (!$staff_url) return '#preventivo';
    $q = array(); if ($paese) $q['paese'] = $paese; if ($cid) $q['citta'] = $cid;
    return $q ? add_query_arg(array_map('rawurlencode', $q), $staff_url) : $staff_url;
};
$sdir = $tdir . '/assets/staff/';

toa_component('events-site-head', array('tev_active' => '', 'tev_cta_url' => '#preventivo'));
?>
<div class="tev-hint"><div class="tev-wrap"><a href="<?php echo esc_url($lavora_url); ?>"><?php echo esc_html($_t($t['talentexit'])); ?> &rarr;</a></div></div>

<main class="tev-home">
  <section class="tev-hero">
    <div class="tev-wrap tev-hero__in">
      <div class="tev-hero__txt">
        <span class="tev-eyebrow"><?php echo esc_html($_t($t['eyebrow'])); ?></span>
        <h1 class="tev-h1 tev-h1--xl"><?php echo esc_html($_t($t['hero_title'])); ?></h1>
        <p class="tev-lead tev-lead--hero"><?php echo wp_kses($_t($t['hero_subtitle']), array('strong' => array(), 'br' => array())); ?></p>
        <div class="tev-ctas">
          <a class="tev-btn tev-btn--lime tev-btn--lg" href="<?php echo esc_url($staff_link()); ?>"><?php echo esc_html($_t($t['cta_staff'])); ?></a>
          <a class="tev-btn tev-btn--dark tev-btn--lg" href="#preventivo"><?php echo esc_html($_t($t['cta_sel'])); ?></a>
        </div>
        <p class="tev-note tev-rating"><a href="https://www.google.com/maps/place/TOAGENCY/@45.0647138,7.6851436,17z" target="_blank" rel="noopener">★ <?php echo esc_html($_t($t['rating'])); ?></a></p>
      </div>
      <div class="tev-mosaic" aria-hidden="true">
        <?php $i = 0; foreach ($hero_foto as $hf): if (!file_exists($sdir . $hf . '.jpg')) continue; ?>
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/staff/' . $hf . '.jpg'); ?>" alt="" <?php echo $i > 2 ? 'loading="lazy" ' : ''; ?>decoding="async" width="300" height="400">
        <?php $i++; endforeach; ?>
      </div>
    </div>
  </section>

  <section class="tev-band" aria-label="<?php echo esc_attr($_t($t['feat_heading'])); ?>">
    <div class="tev-wrap tev-feats">
      <?php for ($k = 1; $k <= 4; $k++): ?>
      <div class="tev-feat"><div class="tev-feat__t"><?php echo esc_html($_t($t['feat' . $k . '_title'])); ?></div><div class="tev-feat__d"><?php echo esc_html($_t($t['feat' . $k . '_text'])); ?></div></div>
      <?php endfor; ?>
    </div>
  </section>

  <section class="tev-sec" id="dove">
    <div class="tev-wrap tev-where">
      <div class="tev-where__l">
        <h2 class="tev-h2"><?php echo esc_html($_t($t['where_h'])); ?></h2>
        <p class="tev-lead"><?php echo esc_html($_t($t['where_p'])); ?></p>
        <div class="tev-row" style="margin-bottom:14px">
          <?php foreach ($paesi as $pc => $pn): ?>
          <a class="tev-chip tev-chip--big tev-chip--a" href="<?php echo esc_url($staff_link($pc)); ?>"><?php echo esc_html($_t($pn)); ?></a>
          <?php endforeach; ?>
          <a class="tev-chip tev-chip--big tev-chip--a tev-chip--dash" href="<?php echo esc_url($staff_link()); ?>"><?php echo esc_html($_t($t['altri'])); ?></a>
        </div>
        <div class="tev-flab"><?php echo esc_html($_t($t['citta_h'])); ?></div>
        <div class="tev-row">
          <?php foreach ($citta as $c): ?>
          <a class="tev-chip tev-chip--a" href="<?php echo esc_url($staff_link($c[0], $c[1])); ?>"><?php echo esc_html($_t($c[2])); ?></a>
          <?php endforeach; ?>
          <a class="tev-chip tev-chip--a tev-chip--dash" href="<?php echo esc_url($staff_link()); ?>"><?php echo esc_html($_t($t['tutte'])); ?></a>
        </div>
      </div>
      <a class="tev-price tev-price--home" id="tevHomePrice" href="<?php echo esc_url($staff_link()); ?>" hidden>
        <div class="tev-price__lab"><?php echo esc_html($_t($t['price_lab'])); ?></div>
        <div class="tev-price__row"><span class="tev-price__n" data-h="prezzo"></span><span class="tev-price__iva" data-h="iva"></span></div>
        <div class="tev-price__sum" data-h="sum"></div>
      </a>
    </div>
  </section>

  <section class="tev-sec" id="ruoli">
    <div class="tev-wrap">
      <h2 class="tev-h2"><?php echo esc_html($_t($t['roles_h'])); ?></h2>
      <p class="tev-lead"><?php echo esc_html($_t($t['roles_p'])); ?></p>
      <div class="tev-svcs tev-svcs--home" id="tevSvcs">
        <?php $i = 0; foreach ($CAT['groups'] as $gk => $g): $u = $svc_url($g['svc']); $ph = isset($home_foto[$gk]) ? $home_foto[$gk] : $g['p']; ?>
        <article class="tev-svc<?php echo $i >= 2 ? ' tev-svc--extra' : ''; ?>">
          <?php if ($ph && file_exists($sdir . $ph . '.jpg')): ?>
          <img class="tev-svc__ph" src="<?php echo esc_url(get_template_directory_uri() . '/assets/staff/' . $ph . '.jpg'); ?>" alt="<?php echo esc_attr($_t($g['t'])); ?>" loading="lazy" decoding="async" width="600" height="338">
          <?php endif; ?>
          <div class="tev-svc__b">
            <h3 class="tev-svc__t"><?php echo esc_html($_t($g['t'])); ?></h3>
            <p class="tev-svc__d"><?php echo esc_html($_t($g['d'])); ?></p>
            <ul class="tev-svc__r">
              <?php foreach ($g['r'] as $rc): if (empty($CAT['roles'][$rc])) continue; ?>
              <li><?php echo esc_html($_t($CAT['roles'][$rc]['n'])); ?></li>
              <?php endforeach; ?>
            </ul>
            <a class="tev-svc__a" href="<?php echo esc_url($u ? $u : '#preventivo'); ?>"><?php echo esc_html($_t($t['svc_more'])); ?> &rarr;</a>
          </div>
        </article>
        <?php $i++; endforeach; ?>
      </div>
      <button type="button" class="tev-btn tev-btn--line tev-svcs__tg" id="tevSvcsTg" hidden aria-expanded="false" aria-controls="tevSvcs" data-open="<?php echo esc_attr($_t($t['svc_open'])); ?>" data-close="<?php echo esc_attr($_t($t['svc_close'])); ?>"><?php echo esc_html($_t($t['svc_open'])); ?></button>
      <script>
      (function(){var g=document.getElementById('tevSvcs'),b=document.getElementById('tevSvcsTg');if(!g||!b||!g.querySelector('.tev-svc--extra'))return;
        var mob=window.matchMedia&&window.matchMedia('(max-width:768px)').matches;
        function set(c){g.classList.toggle('is-collapsed',c);b.setAttribute('aria-expanded',c?'false':'true');b.textContent=c?b.getAttribute('data-open'):b.getAttribute('data-close');}
        set(mob);b.hidden=false;
        b.addEventListener('click',function(){var c=!g.classList.contains('is-collapsed');set(c);if(c){var s=document.getElementById('ruoli');if(s)s.scrollIntoView({block:'start'});}});})();
      </script>
    </div>
  </section>

  <section class="tev-sec tev-sec--grey" id="come-funziona">
    <div class="tev-wrap">
      <h2 class="tev-h2"><?php echo esc_html($_t($t['how_h'])); ?></h2>
      <div class="tev-steps">
        <?php foreach ($t['how'] as $n => $st): ?>
        <div class="tev-step"><span class="tev-step__n"><?php echo $n + 1; ?></span><h3><?php echo esc_html($_t($st[0])); ?></h3><p><?php echo esc_html($_t($st[1])); ?></p></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <?php // 2026-11-04 (ticket #807, TEMA Ticket 807): creator per eventi e stand → mini sito TOA Creators col pacchetto «Live coverage» gia' scelto.
  // Solo se la pagina creator della lingua e' pubblicata (opzione toa_creators_site): mai un link verso una bozza.
  $cr_map = get_option('toa_creators_site', array());
  $cr_id  = isset($cr_map['home'][$lang]) ? (int) $cr_map['home'][$lang] : 0;
  if ($cr_id && get_post_status($cr_id) === 'publish'): ?>
  <section class="tev-cta-band" aria-label="TOA Creators">
    <div class="tev-wrap tev-cta-band__in" style="padding-bottom:48px">
      <div>
        <h2 class="tev-h2" style="color:#FFFFFF;margin:0 0 8px"><?php echo esc_html($_t(array('it'=>'Creator per il tuo evento o stand','en'=>'Creators for your event or booth','fr'=>'Des créateurs pour votre événement ou votre stand','es'=>'Creadores para tu evento o stand'))); ?></h2>
        <p><?php echo esc_html($_t(array(
          'it'=>'Un creator sul posto racconta la tua fiera o il tuo evento in tempo reale, sui tuoi canali o sul suo profilo.',
          'en'=>'A creator on site tells the story of your trade show or event as it happens, on your channels or on their own profile.',
          'fr'=>'Un créateur sur place raconte votre salon ou votre événement en direct, sur vos réseaux ou sur son propre profil.',
          'es'=>'Un creador en el lugar cuenta tu feria o tu evento en directo, en tus canales o en su propio perfil.'))); ?></p>
      </div>
      <a class="tev-btn tev-btn--lime" href="<?php echo esc_url(add_query_arg('pacchetto', 'coverage', get_permalink($cr_id))); ?>"><?php echo esc_html($_t(array('it'=>'Vedi creator e prezzi','en'=>'See creators and prices','fr'=>'Voir créateurs et prix','es'=>'Ver creadores y precios'))); ?> &rarr;</a>
    </div>
  </section>
  <?php endif; ?>

  <div class="tev-dark"><?php toa_component('google-reviews'); ?></div>
  <div class="tev-dark"><?php toa_component('events-coverage', array('lang' => $lang)); ?></div>
</main>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events', 'extra' => true)); ?>

<section class="tev-gallery" aria-label="Gallery">
  <div class="tev-wrap">
    <div class="tev-gallery__g">
      <?php for ($gi = 1; $gi <= 37; $gi++): $gn = sprintf('g%02d', $gi); if (!file_exists($tdir . '/assets/gallery/' . $gn . '.jpg')) continue; ?>
      <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/gallery/' . $gn . '.jpg'); ?>" alt="TOA Events" loading="lazy" decoding="async" width="120" height="120">
      <?php endfor; ?>
    </div>
  </div>
</section>

<div class="tev-bar tev-bar--cta" id="tevBar"><a class="tev-btn tev-btn--lime" href="#preventivo"><?php echo esc_html($_t($t['cta_sel'])); ?></a></div>

<script>
(function(){
  // prezzo «a partire da» del Paese della lingua, dal CRM (il tema non calcola prezzi). Se il CRM non risponde il riquadro resta nascosto.
  var box = document.getElementById('tevHomePrice'); if (!box || !window.fetch) return;
  var EP = '/actions/hostess-vetrina-public.php', L = <?php echo wp_json_encode($lang); ?>;
  fetch(EP + '?action=filtri&lang=' + L, { credentials: 'omit' }).then(function(r){ return r.json(); }).then(function(f){
    var c = (f.citta_top || [])[0]; if (!c) return;
    return fetch(EP + '?action=vetrina&lang=' + L + '&paese=' + encodeURIComponent(f.paese) + '&citta=' + encodeURIComponent(c.id), { credentials: 'omit' }).then(function(r){ return r.json(); }).then(function(v){
      if (!v || !v.ok || !v.testi) return;
      var t = v.testi, q = function(k){ return box.querySelector('[data-h="' + k + '"]'); };
      if (f.paese === 'IT' && v.prezzo_da != null) v.prezzo_da = 119; // Italia: «a partire da» fisso (6 ore), come in Staff e prezzi
      if (v.prezzo_da == null) { q('prezzo').textContent = t.su_richiesta || ''; q('prezzo').classList.add('is-text'); }
      else { q('prezzo').textContent = v.prezzo_da + ' ' + (v.valuta || '€'); /* IVA non in home (Marco 04/11): la nota «solo aziende italiane» è in Staff e prezzi */ }
      q('sum').textContent = [t.unita, (v.citta && v.citta.nome) || c.nome].filter(Boolean).join(' · ');
      box.hidden = false;
    });
  }).catch(function(){});
  var bar = document.getElementById('tevBar'), form = document.getElementById('preventivo');
  if (bar && form && 'IntersectionObserver' in window) {
    new IntersectionObserver(function(en){ en.forEach(function(x){ bar.classList.toggle('hide', x.isIntersecting); }); }, { threshold: 0 }).observe(form);
  }
})();
</script>

<?php toa_component('events-site-foot'); ?>
