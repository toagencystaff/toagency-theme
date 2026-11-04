<?php
/**
 * Template Name: TOA Creators - Pagina principale
 * Mini sito creator dentro toagency.it (ticket CRM #807, 04/11/2026, chat «TEMA Ticket 807») — IT/EN/FR/ES.
 * Vende il RISULTATO (pacchetti con prezzo «da») prima delle persone; vetrina di esempio in 3 fasce; IVA secondo il Paese della sede.
 * DATI SOLO dal CRM: /actions/creator-vetrina-public.php (listino | servizi | filtri | evidenza | vetrina). Il tema NON calcola prezzi
 * e NON sceglie i creator: regole, fasce, allargamento, chi resta fuori dall'evidenza stanno in crm_toagency/lib/creator_vetrina.php.
 * Listino, servizi e filtri arrivano gia' nella pagina (cache 30 minuti, utile anche a Google); vetrina ed evidenza le carica il JS.
 * Regole fisse: mai cognomi, citta', handle o link ai social; video dei creator solo col loro credito (nome + #codice + nicchia).
 * Rendering: assets/js/creators.js · Stile: assets/css/creators-site.css · Modulo: components/form-creators-inline.php
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$tdir = get_template_directory();
wp_enqueue_style('toa-creators-site', get_template_directory_uri() . '/assets/css/creators-site.css', array(), (string) @filemtime($tdir . '/assets/css/creators-site.css'));
wp_enqueue_script('toa-creators', get_template_directory_uri() . '/assets/js/creators.js', array(), (string) @filemtime($tdir . '/assets/js/creators.js'), true);

// listino, servizi, filtri dal CRM (stesso sito), in cache 30 minuti per lingua; se non arrivano li carica il JS
$tcr_ep = '/actions/creator-vetrina-public.php';
$tcr_pre = get_transient('toa_creators_pre_' . $lang);
if (!is_array($tcr_pre)) {
    $tcr_pre = array();
    $tcr_base = untrailingslashit(get_option('home'));   // home_url() con WPML aggiungerebbe /en/ davanti a /actions/
    foreach (array('listino' => 'pacchetti', 'servizi' => 'servizi', 'filtri' => 'filtri') as $a => $k) {
        $r = wp_remote_get($tcr_base . $tcr_ep . '?action=' . $a . '&lang=' . $lang, array('timeout' => 3));
        if (is_wp_error($r) || wp_remote_retrieve_response_code($r) !== 200) break;
        $j = json_decode(wp_remote_retrieve_body($r), true);
        if (is_array($j) && !empty($j['ok']) && isset($j[$k])) $tcr_pre[$a] = $j[$k]; else break;
    }
    // risposta completa: 30 minuti; se il server non risponde a se stesso: si riprova fra 10 minuti e intanto carica tutto il JS
    if (count($tcr_pre) === 3) set_transient('toa_creators_pre_' . $lang, $tcr_pre, 30 * MINUTE_IN_SECONDS);
    else { $tcr_pre = array(); set_transient('toa_creators_pre_' . $lang, array(), 10 * MINUTE_IN_SECONDS); }
}

$T = array(
  'h1'   => array('it'=>'Contenuti veri, girati da <em>creator veri</em>, per il tuo brand.',
                  'en'=>'Real content from <em>real creators</em>, made for your brand.',
                  'fr'=>'Des contenus authentiques, signés par de <em>vrais créateurs</em>, pour votre marque.',
                  'es'=>'Contenido real, grabado por <em>creadores reales</em>, para tu marca.'),
  'lead' => array('it'=>'Video UGC per le tue ads, contenuti sul profilo del creator, live coverage di eventi e stand. Prezzi chiari da subito e una selezione su misura per il tuo brief.',
                  'en'=>"UGC videos for your ads, posts on the creator's own profile, live coverage of events and booths. Clear prices from the start and a shortlist tailored to your brief.",
                  'fr'=>"Vidéos UGC pour vos publicités, publications sur le profil du créateur, couverture en direct d'événements et de stands. Des prix clairs d'emblée et une sélection taillée pour votre brief.",
                  'es'=>'Vídeos UGC para tus anuncios, publicaciones en el perfil del creador, cobertura en directo de eventos y stands. Precios claros desde el principio y una selección hecha para tu brief.'),
  'cta1' => array('it'=>'Chiedi una selezione su misura','en'=>'Get a tailored shortlist','fr'=>'Demander une sélection sur mesure','es'=>'Pide una selección a medida'),
  'cta2' => array('it'=>'Guarda i creator','en'=>'See the creators','fr'=>'Voir les créateurs','es'=>'Ver los creadores'),
  'k1'   => array('it'=>'creator con video verificati','en'=>'creators with verified videos','fr'=>'créateurs aux vidéos vérifiées','es'=>'creadores con vídeos verificados'),
  'k2'   => array('it'=>'nicchie','en'=>'niches','fr'=>'thématiques','es'=>'temáticas'),
  'k3'   => array('it'=>'Paesi','en'=>'countries','fr'=>'pays','es'=>'países'),
  'pk_h' => array('it'=>'Cosa puoi comprare','en'=>'What you can buy','fr'=>'Ce que vous pouvez acheter','es'=>'Qué puedes contratar'),
  'pk_p' => array('it'=>'Scegli il risultato. I prezzi «a partire da» vengono dai compensi veri dei nostri creator.',
                  'en'=>'Pick the outcome you need. Our "from" prices are based on what our creators actually charge.',
                  'fr'=>'Choisissez le résultat. Nos prix « à partir de » reposent sur les tarifs réels de nos créateurs.',
                  'es'=>'Elige el resultado. Los precios «desde» salen de lo que cobran de verdad nuestros creadores.'),
  'sv_h' => array('it'=>'Ti serve anche altro?','en'=>'Need anything else?','fr'=>"Besoin d'autre chose ?",'es'=>'¿Necesitas algo más?'),
  'sv_p' => array('it'=>'Oltre ai creator: chi gestisce i tuoi social, chi fa girare le pubblicità e chi gira foto e video sul posto. Un solo referente per tutto.',
                  'en'=>'Beyond creators: someone to run your social accounts, someone to run your ads and someone to shoot photos and videos on site. One contact for everything.',
                  'fr'=>'Au-delà des créateurs : quelqu\'un pour gérer vos réseaux, quelqu\'un pour piloter vos publicités et quelqu\'un pour shooter photos et vidéos sur place. Un seul interlocuteur pour tout.',
                  'es'=>'Además de los creadores: quien gestione tus redes, quien lleve tus anuncios y quien haga fotos y vídeos en el lugar. Un solo interlocutor para todo.'),
  'vt_h' => array('it'=>'Che creator puoi avere','en'=>'The creators you could work with','fr'=>'Les créateurs avec qui travailler','es'=>'Los creadores con los que puedes trabajar'),
  'vt_p' => array('it'=>'Scegli il tuo settore: ti mostriamo i creator adatti, divisi in tre fasce di prezzo. Per sapere chi è libero nelle tue date, chiedi una selezione.',
                  'en'=>"Choose your sector and we'll show you the creators who fit, split into three price tiers. To find out who's free on your dates, ask for a shortlist.",
                  'fr'=>'Choisissez votre secteur : nous vous montrons les créateurs adaptés, répartis en trois niveaux de prix. Pour savoir qui est libre à vos dates, demandez une sélection.',
                  'es'=>'Elige tu sector y te mostramos los creadores adecuados, en tres niveles de precio. Para saber quién está libre en tus fechas, pide una selección.'),
  'co_h' => array('it'=>'Come funziona','en'=>'How it works','fr'=>'Comment ça marche','es'=>'Cómo funciona'),
  'join' => array('it'=>'Sei un creator? Entra in TOAgency: video originali, statistiche aggiornate e casting con brand veri.',
                  'en'=>"Are you a creator? Join TOAgency: original videos, up-to-date stats and castings with real brands.",
                  'fr'=>'Vous êtes créateur ? Rejoignez TOAgency : vidéos originales, statistiques à jour et castings avec de vraies marques.',
                  'es'=>'¿Eres creador? Únete a TOAgency: vídeos originales, estadísticas al día y castings con marcas reales.'),
  'joinb'=> array('it'=>'Diventa creator','en'=>'Become a creator','fr'=>'Devenir créateur','es'=>'Hazte creador'),
);
$steps = array(
  array(array('it'=>'Ci racconti il brief','en'=>'Tell us your brief','fr'=>'Vous nous confiez votre brief','es'=>'Nos cuentas tu brief'),
        array('it'=>'Prodotto, settore, quanti contenuti, date e budget: bastano due minuti.','en'=>'Product, sector, how much content, dates and budget: it takes two minutes.','fr'=>'Produit, secteur, nombre de contenus, dates et budget : deux minutes suffisent.','es'=>'Producto, sector, cuántos contenidos, fechas y presupuesto: bastan dos minutos.')),
  array(array('it'=>'Ricevi una selezione','en'=>'Get a shortlist','fr'=>'Vous recevez une sélection','es'=>'Recibes una selección'),
        array('it'=>'Creator disponibili nelle tue date, con video, pubblico e prezzo di ognuno.','en'=>'Creators who are free on your dates, each with their videos, audience and price.','fr'=>'Des créateurs disponibles à vos dates, chacun avec ses vidéos, son audience et son prix.','es'=>'Creadores libres en tus fechas, cada uno con sus vídeos, su público y su precio.')),
  array(array('it'=>'Scegli e ricevi i contenuti','en'=>'Choose and get your content','fr'=>'Vous choisissez, vous recevez','es'=>'Eliges y recibes el contenido'),
        array('it'=>'Contratto e diritti d\'uso chiari, un solo referente e una sola fattura.','en'=>'Clear contract and usage rights, one contact and one invoice.','fr'=>"Contrat et droits d'utilisation clairs, un seul interlocuteur et une seule facture.",'es'=>'Contrato y derechos de uso claros, un solo interlocutor y una sola factura.')),
);
$reg = array('it'=>'/registrati-talent/','en'=>'/en/register-talent/','fr'=>'/fr/inscription-talent/','es'=>'/es/registrate-como-talent/');
$tcr_dv = function_exists('toa_creators_url') ? toa_creators_url('diventa', $lang) : '';

// testi dell'interfaccia del JS (i testi dei prezzi, dell'IVA e dell'allargamento arrivano dal CRM)
$ui = array(
 'it' => array('tutti'=>'Tutti i settori','paese'=>'Paese','tuttiPaesi'=>'Tutti i Paesi','contenuto'=>'Tipo di contenuto','tuttiCont'=>'Tutti i contenuti','zone'=>'Zone d\'Italia (in presenza)',
   'adatti'=>'{n} creator con video adatti.','pubblico'=>'Pubblico','donne'=>'donne','anni'=>'anni','noStat'=>'Statistiche del pubblico in arrivo','add'=>'Aggiungi alla richiesta','added'=>'Aggiunto alla richiesta','verificato'=>'Creator verificato TOAgency',
   'scheda'=>'Scheda','parliamone'=>'Parliamone','preventivo'=>'Chiedi un preventivo','scegli'=>'Scegli','scelto'=>'Scelto',
   'altriH'=>'Questi sono solo alcuni esempi.','altriP'=>'Nel nostro database ci sono <b>{n}</b> creator con video, in tutte le nicchie e in più Paesi. Dicci prodotto, nicchia, Paese e date: ti mandiamo entro 24 ore una selezione di creator <b>disponibili</b> per il tuo brief.','altriB'=>'Vedi altri creator',
   'privacy'=>'Mai nomi utente o link ai profili: la selezione passa sempre da TOAgency.','errore'=>'Non riusciamo a caricare i creator in questo momento. Riprova tra poco oppure chiedici una selezione.',
   'esempioIva'=>'Esempio: {p} + IVA ({t} IVA compresa).','perVideo'=>'per «{p}»','sez'=>'Ti mostriamo i prezzi di «{p}».','ads'=>'Ads','copione'=>'Copione'),
 'en' => array('tutti'=>'All sectors','paese'=>'Country','tuttiPaesi'=>'All countries','contenuto'=>'Content type','tuttiCont'=>'All content types','zone'=>'Areas of Italy (on site)',
   'adatti'=>'{n} creators with videos that fit.','pubblico'=>'Audience','donne'=>'women','anni'=>'yrs','noStat'=>'Audience stats coming soon','add'=>'Add to your request','added'=>'Added to your request','verificato'=>'TOAgency verified creator',
   'scheda'=>'Profile','parliamone'=>"Let's talk",'preventivo'=>'Request a quote','scegli'=>'Choose','scelto'=>'Selected',
   'altriH'=>'These are just a few examples.','altriP'=>"Our database has <b>{n}</b> creators with videos, across every niche and several countries. Tell us your product, niche, country and dates, and within 24 hours we'll send you a shortlist of creators who are <b>available</b> for your brief.",'altriB'=>'See more creators',
   'privacy'=>'No usernames or profile links: every shortlist goes through TOAgency.','errore'=>"We can't load the creators right now. Please try again shortly, or ask us for a shortlist.",
   'esempioIva'=>'Example: {p} + Italian VAT ({t} incl. VAT).','perVideo'=>'for "{p}"','sez'=>'Prices shown for "{p}".','ads'=>'Ads','copione'=>'Script'),
 'fr' => array('tutti'=>'Tous les secteurs','paese'=>'Pays','tuttiPaesi'=>'Tous les pays','contenuto'=>'Type de contenu','tuttiCont'=>'Tous les contenus','zone'=>"Régions d'Italie (sur place)",
   'adatti'=>'{n} créateurs avec des vidéos adaptées.','pubblico'=>'Audience','donne'=>'femmes','anni'=>'ans','noStat'=>'Statistiques d\'audience bientôt disponibles','add'=>'Ajouter à la demande','added'=>'Ajouté à la demande','verificato'=>'Créateur vérifié par TOAgency',
   'scheda'=>'Fiche','parliamone'=>'Parlons-en','preventivo'=>'Demander un devis','scegli'=>'Choisir','scelto'=>'Choisi',
   'altriH'=>'Ce ne sont que quelques exemples.','altriP'=>'Notre base compte <b>{n}</b> créateurs avec vidéo, dans toutes les thématiques et dans plusieurs pays. Indiquez-nous produit, thématique, pays et dates : sous 24 heures, nous vous envoyons une sélection de créateurs <b>disponibles</b> pour votre brief.','altriB'=>'Voir plus de créateurs',
   'privacy'=>'Jamais de pseudos ni de liens vers les profils : toute sélection passe par TOAgency.','errore'=>'Impossible de charger les créateurs pour le moment. Réessayez dans un instant ou demandez-nous une sélection.',
   'esempioIva'=>'Exemple : {p} + TVA italienne ({t} TTC).','perVideo'=>'pour « {p} »','sez'=>'Prix affichés pour « {p} ».','ads'=>'Pubs','copione'=>'Script'),
 'es' => array('tutti'=>'Todos los sectores','paese'=>'País','tuttiPaesi'=>'Todos los países','contenuto'=>'Tipo de contenido','tuttiCont'=>'Todos los contenidos','zone'=>'Zonas de Italia (presencial)',
   'adatti'=>'{n} creadores con vídeos adecuados.','pubblico'=>'Público','donne'=>'mujeres','anni'=>'años','noStat'=>'Estadísticas del público próximamente','add'=>'Añadir a la solicitud','added'=>'Añadido a la solicitud','verificato'=>'Creador verificado por TOAgency',
   'scheda'=>'Ficha','parliamone'=>'Hablemos','preventivo'=>'Pide presupuesto','scegli'=>'Elegir','scelto'=>'Elegido',
   'altriH'=>'Estos son solo algunos ejemplos.','altriP'=>'En nuestra base hay <b>{n}</b> creadores con vídeo, en todas las temáticas y en varios países. Dinos producto, temática, país y fechas: en 24 horas te enviamos una selección de creadores <b>disponibles</b> para tu brief.','altriB'=>'Ver más creadores',
   'privacy'=>'Nunca nombres de usuario ni enlaces a perfiles: toda selección pasa por TOAgency.','errore'=>'Ahora mismo no podemos cargar los creadores. Inténtalo de nuevo en un momento o pídenos una selección.',
   'esempioIva'=>'Ejemplo: {p} + IVA italiano ({t} IVA incluido).','perVideo'=>'para «{p}»','sez'=>'Precios de «{p}».','ads'=>'Ads','copione'=>'Guion'),
);
$cfg = array('lang' => $lang, 'endpoint' => $tcr_ep, 'pre' => $tcr_pre ? $tcr_pre : null, 'ui' => $ui[$lang]);

// numeri dell'apertura gia' in pagina se il CRM ha risposto (altrimenti li scrive il JS)
$n_nic = !empty($tcr_pre['filtri']['nicchie']) ? count($tcr_pre['filtri']['nicchie']) : '';
$n_paesi = '';
if (!empty($tcr_pre['filtri']['aree'])) { $n_paesi = 0; foreach ($tcr_pre['filtri']['aree'] as $a) if (preg_match('/^[A-Z]{2}$/', (string) $a['codice'])) $n_paesi++; }

toa_component('creators-site-head', array('tcr_active' => 'home'));
?>
<main class="tcr-main" id="tcrMain">
  <div class="tcr-wrap">
    <section class="tcr-hero">
      <div>
        <div class="tcr-eyebrow">TOA Creators</div>
        <h1 class="tcr-h1"><?php echo wp_kses($_t($T['h1']), array('em' => array())); ?></h1>
        <p class="tcr-lead"><?php echo esc_html($_t($T['lead'])); ?></p>
        <div class="tcr-btns">
          <a class="tcr-btn tcr-btn--p" href="#richiesta"><?php echo esc_html($_t($T['cta1'])); ?></a>
          <a class="tcr-btn tcr-btn--s" href="#vetrina"><?php echo esc_html($_t($T['cta2'])); ?></a>
        </div>
        <div class="tcr-kpi">
          <div><b id="tcrKpiTot">&nbsp;</b><?php echo esc_html($_t($T['k1'])); ?></div>
          <div><b id="tcrKpiNic"><?php echo esc_html((string) $n_nic); ?></b><?php echo esc_html($_t($T['k2'])); ?></div>
          <div><b id="tcrKpiPaesi"><?php echo esc_html((string) $n_paesi); ?></b><?php echo esc_html($_t($T['k3'])); ?></div>
        </div>
      </div>
      <div class="tcr-phones" id="tcrPhones" aria-live="off"></div>
    </section>

    <section id="pacchetti" class="tcr-sec">
      <div class="tcr-sh"><h2 class="tcr-h2"><?php echo esc_html($_t($T['pk_h'])); ?></h2><p><?php echo esc_html($_t($T['pk_p'])); ?></p></div>
      <div class="tcr-sede"><label id="tcrPaeseL" for="tcrPaese"></label><select id="tcrPaese"></select></div>
      <div class="tcr-pk" id="tcrPk"></div>
      <div id="tcrCont"></div>
      <div class="tcr-iva" id="tcrIva" hidden></div>
    </section>

    <section id="servizi" class="tcr-sec">
      <div class="tcr-sh"><h2 class="tcr-h2"><?php echo esc_html($_t($T['sv_h'])); ?></h2><p><?php echo esc_html($_t($T['sv_p'])); ?></p></div>
      <div class="tcr-sv" id="tcrSv"></div>
    </section>

    <section id="vetrina" class="tcr-sec">
      <div class="tcr-sh"><h2 class="tcr-h2"><?php echo esc_html($_t($T['vt_h'])); ?></h2><p><?php echo esc_html($_t($T['vt_p'])); ?> <span id="tcrVtPack"></span></p></div>
      <div class="tcr-chips" id="tcrSettori" role="group"></div>
      <p class="tcr-sdesc" id="tcrSdesc"></p>
      <div class="tcr-filt">
        <label><span id="tcrAreaL"></span><select id="tcrArea"></select></label>
        <label><span id="tcrContL"></span><select id="tcrContenuto"></select></label>
      </div>
      <div class="tcr-wide" id="tcrWide" hidden></div>
      <div class="tcr-alert" id="tcrErr" hidden></div>
      <div class="tcr-bands" id="tcrBands">
        <div class="tcr-band tcr-band--essenziale"><h3><span data-b="essenziale">Essenziale</span> <small id="tcrHessenziale"></small></h3><div class="tcr-grid" id="tcrGessenziale"></div></div>
        <div class="tcr-band tcr-band--pro"><h3><span data-b="pro">Pro</span> <small id="tcrHpro"></small></h3><div class="tcr-grid" id="tcrGpro"></div></div>
        <div class="tcr-band tcr-band--premium"><h3><span data-b="premium">Premium</span> <small id="tcrHpremium"></small></h3><div class="tcr-grid" id="tcrGpremium"></div></div>
      </div>
      <div class="tcr-flat" id="tcrFlat" hidden></div>
      <div class="tcr-altri" id="tcrAltri" hidden><div class="tcr-stack" id="tcrStack"></div><div class="tcr-altri__t"><h3 id="tcrAltriH"></h3><p id="tcrAltriP"></p></div><a class="tcr-btn tcr-btn--p" href="#richiesta" id="tcrAltriB"></a></div>
      <p class="tcr-disc" id="tcrDisc"></p>
    </section>

    <section id="come" class="tcr-sec">
      <div class="tcr-sh"><h2 class="tcr-h2"><?php echo esc_html($_t($T['co_h'])); ?></h2></div>
      <div class="tcr-steps">
        <?php foreach ($steps as $i => $s): ?>
        <div class="tcr-st"><b><?php echo $i + 1; ?></b><h3><?php echo esc_html($_t($s[0])); ?></h3><p><?php echo esc_html($_t($s[1])); ?></p></div>
        <?php endforeach; ?>
      </div>
    </section>

    <?php toa_component('form-creators-inline', array('lang' => $lang)); ?>

    <div class="tcr-join"><span><?php echo esc_html($_t($T['join'])); ?></span><a class="tcr-btn tcr-btn--s" href="<?php echo esc_url($tcr_dv ? $tcr_dv : untrailingslashit(get_option('home')) . $reg[$lang] . '?ruolo=influencer'); ?>"><?php echo esc_html($_t($T['joinb'])); ?></a></div>
  </div>
</main>

<script type="application/json" id="tcrCfg"><?php echo wp_json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP); ?></script>

<?php toa_component('creators-site-foot'); ?>
