<?php
/**
 * Template Name: TOA Events - Staff e prezzi
 * Mini sito TOA Events (ticket #811, 03/11/2026, chat TEMA-Events) — IT/EN/FR/ES.
 * Vetrina hostess/steward con prezzo «a partire da» che cambia con i filtri.
 * DATI SOLO dal CRM: /actions/hostess-vetrina-public.php (action=filtri | vetrina). Il tema NON calcola prezzi:
 * regole, basi per Paese, allargamento alle zone vicine e testi del prezzo stanno in lib/hostess_vetrina.php del CRM.
 * Rendering e filtri: assets/js/events-staff.js. Stile: assets/css/events-site.css.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$tdir = get_template_directory();
wp_enqueue_style('toa-events-site', get_template_directory_uri() . '/assets/css/events-site.css', array(), (string) @filemtime($tdir . '/assets/css/events-site.css'));
wp_enqueue_script('toa-events-staff', get_template_directory_uri() . '/assets/js/events-staff.js', array(), (string) @filemtime($tdir . '/assets/js/events-staff.js'), true);

$t = array(
    'h1'   => array('it'=>'Staff per eventi in tutta Europa','en'=>'Event staff across Europe','fr'=>'Du personnel événementiel partout en Europe','es'=>'Personal para eventos en toda Europa'),
    'lead' => array(
        'it'=>'Scegli Paese e città: cambiano le persone e il prezzo di partenza. Sono profili veri del nostro database.',
        'en'=>'Pick a country and a city: the people and the starting price change with them. These are real profiles from our database.',
        'fr'=>"Choisissez un pays et une ville : les profils et le prix de départ s'adaptent. Ce sont de vrais profils de notre base.",
        'es'=>'Elige país y ciudad: cambian los perfiles y el precio de partida. Son perfiles reales de nuestra base de datos.'),
);

// testi dell'interfaccia (i testi del prezzo arrivano dal CRM: testi.a_partire / iva / su_richiesta / unita / esempio / cta)
$ui = array(
 'it' => array('titoloNaz'=>'{f} in {c}','modificaCrm'=>'Modifica nel CRM','altriPaesi'=>'Altri Paesi +','chiudi'=>'Chiudi','altroPaese'=>'Altro Paese? Scrivilo nella richiesta.','citta'=>'Città','altreZone'=>'Altre zone +','chiudiZone'=>'Chiudi altre zone','zona'=>"Non c'è la tua zona? Scrivila",'zonaPh'=>'es. Alba (CN)','zonaOk'=>'La aggiungiamo alla tua richiesta.','figura'=>'Figura','lingue'=>'Lingue','lingueHint'=>'(quella del Paese è già scelta: puoi toglierla)','livello'=>'Livello minimo delle altre lingue','qualsiasi'=>'Qualsiasi','esperienza'=>'Esperienza','ore'=>'Ore al giorno','oreFmt'=>'{n} ore','filtri'=>'Filtri','chiudiFiltri'=>'Chiudi i filtri','conta'=>'{n} profili di esempio','conta1'=>'1 profilo di esempio','titolo'=>'{f} a {c}','vuoto'=>'Con questi filtri non abbiamo profili da mostrarti qui: togli un filtro oppure scrivici, cerchiamo noi.','errore'=>'Non riusciamo a caricare i profili in questo momento. Riprova tra poco oppure scrivici.','carico'=>'Carico i profili…','barCta'=>'Chi è libero?','chiedi'=>'Chiedi questo profilo','nativo'=>'madrelingua','foto'=>'Foto e video','m'=>'m',
   'msg'=>'Richiesta da «Staff e prezzi»','mFig'=>'Figura','mDove'=>'Dove','mOre'=>'Ore al giorno','mLin'=>'Lingue','mEsp'=>'Esperienza','mProf'=>'Profili che mi interessano'),
 'en' => array('titoloNaz'=>'{f} across {c}','modificaCrm'=>'Edit in the CRM','altriPaesi'=>'Other countries +','chiudi'=>'Close','altroPaese'=>'Another country? Just add it to your request.','citta'=>'City','altreZone'=>'More areas +','chiudiZone'=>'Hide areas','zona'=>"Can't see your area? Type it",'zonaPh'=>'e.g. Oxford','zonaOk'=>"We'll add it to your request.",'figura'=>'Role','lingue'=>'Languages','lingueHint'=>'(the local language is already selected — you can remove it)','livello'=>'Minimum level for other languages','qualsiasi'=>'Any','esperienza'=>'Experience','ore'=>'Hours per day','oreFmt'=>'{n} hours','filtri'=>'Filters','chiudiFiltri'=>'Hide filters','conta'=>'{n} example profiles','conta1'=>'1 example profile','titolo'=>'{f} in {c}','vuoto'=>"No profiles to show here with these filters. Remove one, or write to us and we'll search for you.",'errore'=>"We can't load the profiles right now. Please try again shortly, or write to us.",'carico'=>'Loading profiles…','barCta'=>"Who's free?",'chiedi'=>'Ask for this profile','nativo'=>'native','foto'=>'Photos and videos','m'=>'m',
   'msg'=>'Request from «Staff & prices»','mFig'=>'Role','mDove'=>'Where','mOre'=>'Hours per day','mLin'=>'Languages','mEsp'=>'Experience','mProf'=>'Profiles I like'),
 'fr' => array('titoloNaz'=>'{f} dans {c}','modificaCrm'=>'Modifier dans le CRM','altriPaesi'=>'Autres pays +','chiudi'=>'Fermer','altroPaese'=>'Un autre pays ? Indiquez-le dans votre demande.','citta'=>'Ville','altreZone'=>'Autres zones +','chiudiZone'=>'Masquer les zones','zona'=>"Votre zone n'y est pas ? Écrivez-la",'zonaPh'=>'ex. Annecy','zonaOk'=>'Nous l’ajoutons à votre demande.','figura'=>'Poste','lingue'=>'Langues','lingueHint'=>'(la langue du pays est déjà cochée : vous pouvez la retirer)','livello'=>'Niveau minimum des autres langues','qualsiasi'=>'Indifférent','esperienza'=>'Expérience','ore'=>'Heures par jour','oreFmt'=>'{n} h','filtri'=>'Filtres','chiudiFiltri'=>'Masquer les filtres','conta'=>'{n} exemples de profils','conta1'=>'1 exemple de profil','titolo'=>'{f} à {c}','vuoto'=>'Aucun profil à afficher ici avec ces filtres. Retirez-en un, ou écrivez-nous : nous cherchons pour vous.','errore'=>'Impossible de charger les profils pour le moment. Réessayez dans un instant ou écrivez-nous.','carico'=>'Chargement des profils…','barCta'=>'Qui est libre ?','chiedi'=>'Demander ce profil','nativo'=>'natif','foto'=>'Photos et vidéos','m'=>'m',
   'msg'=>'Demande depuis « Personnel et tarifs »','mFig'=>'Poste','mDove'=>'Lieu','mOre'=>'Heures par jour','mLin'=>'Langues','mEsp'=>'Expérience','mProf'=>'Profils qui m’intéressent'),
 'es' => array('titoloNaz'=>'{f} en {c}','modificaCrm'=>'Editar en el CRM','altriPaesi'=>'Otros países +','chiudi'=>'Cerrar','altroPaese'=>'¿Otro país? Indícalo en tu solicitud.','citta'=>'Ciudad','altreZone'=>'Más zonas +','chiudiZone'=>'Ocultar zonas','zona'=>'¿No está tu zona? Escríbela','zonaPh'=>'p. ej. Marbella','zonaOk'=>'La añadimos a tu solicitud.','figura'=>'Puesto','lingue'=>'Idiomas','lingueHint'=>'(el idioma del país ya está marcado: puedes quitarlo)','livello'=>'Nivel mínimo de los otros idiomas','qualsiasi'=>'Cualquiera','esperienza'=>'Experiencia','ore'=>'Horas al día','oreFmt'=>'{n} h','filtri'=>'Filtros','chiudiFiltri'=>'Ocultar filtros','conta'=>'{n} perfiles de ejemplo','conta1'=>'1 perfil de ejemplo','titolo'=>'{f} en {c}','vuoto'=>'Con estos filtros no hay perfiles que mostrar aquí. Quita alguno o escríbenos y buscamos nosotros.','errore'=>'Ahora mismo no podemos cargar los perfiles. Inténtalo de nuevo en un momento o escríbenos.','carico'=>'Cargando perfiles…','barCta'=>'¿Quién está libre?','chiedi'=>'Pedir este perfil','nativo'=>'nativo','foto'=>'Fotos y vídeos','m'=>'m',
   'msg'=>'Solicitud desde «Personal y precios»','mFig'=>'Puesto','mDove'=>'Dónde','mOre'=>'Horas al día','mLin'=>'Idiomas','mEsp'=>'Experiencia','mProf'=>'Perfiles que me interesan'),
);
$cfg = array(
    'lang'     => $lang,
    'endpoint' => '/actions/hostess-vetrina-public.php',
    'ui'       => $ui[$lang],
);

toa_component('events-site-head', array('tev_active' => 'staff', 'tev_cta_url' => '#preventivo'));
?>
<main class="tev-staff" id="tevStaff">
  <div class="tev-wrap">
    <h1 class="tev-h1"><?php echo esc_html($_t($t['h1'])); ?></h1>
    <p class="tev-lead"><?php echo esc_html($_t($t['lead'])); ?></p>

    <div class="tev-row tev-scroll" id="tevPaesi" role="group" aria-label="<?php echo esc_attr($_t(array('it'=>'Paese','en'=>'Country','fr'=>'Pays','es'=>'País'))); ?>"></div>
    <div class="tev-box" id="tevAltriPaesi" hidden><div class="tev-box__links" id="tevAltriPaesiL"></div><div class="tev-note" id="tevAltroNote"></div></div>

    <div class="tev-layout">
      <aside class="tev-aside" id="tevAside">
        <div class="tev-price">
          <div class="tev-price__lab" data-tev="aPartire"></div>
          <div class="tev-price__row"><span class="tev-price__n" data-tev="prezzo">&nbsp;</span><span class="tev-price__iva" data-tev="iva"></span></div>
          <div class="tev-price__sum" data-tev="riassunto"></div>
          <a class="tev-btn tev-btn--lime" href="#preventivo" data-tev="cta"></a>
        </div>

        <div class="tev-fgrp tev-fgrp--citta">
          <div class="tev-flab" data-tev="lCitta"></div>
          <div class="tev-row tev-scroll" id="tevCitta"></div>
          <div class="tev-box" id="tevAltre" hidden style="margin-top:10px">
            <div class="tev-box__links" id="tevAltreL"></div>
            <label class="tev-zona"><span data-tev="lZona"></span><input type="text" id="tevZona" maxlength="80" autocomplete="off"></label>
            <div class="tev-note" id="tevZonaOk" hidden></div>
          </div>
        </div>

        <div class="tev-fbtn"><button type="button" id="tevFbtn" aria-expanded="false" aria-controls="tevFpanel"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4"/></svg><span id="tevFbtnL"></span></button><span class="tev-note" data-tev="conta"></span></div>

        <div class="tev-fpanel" id="tevFpanel">
          <div class="tev-fgrp"><div class="tev-flab" data-tev="lFigura"></div><div class="tev-row" id="tevFigure"></div></div>
          <div class="tev-fgrp"><div class="tev-flab"><span data-tev="lLingue"></span> <small data-tev="lLingueHint"></small></div><div class="tev-row" id="tevLingue"></div></div>
          <div class="tev-fgrp" id="tevLivWrap" hidden><div class="tev-flab" data-tev="lLivello"></div><div class="tev-row" id="tevLivelli"></div></div>
          <div class="tev-fgrp"><div class="tev-flab" data-tev="lEsperienza"></div><div class="tev-row" id="tevEsp"></div></div>
          <div class="tev-fgrp"><div class="tev-flab" data-tev="lOre"></div><div class="tev-row" id="tevOre"></div></div>
        </div>
      </aside>

      <section class="tev-main" aria-live="polite">
        <div class="tev-main__hd"><h2 class="tev-main__t" data-tev="titolo"></h2><span class="tev-note" data-tev="conta"></span></div>
        <div class="tev-alert" id="tevAvviso" hidden></div>
        <div class="tev-grid" id="tevGrid"><?php for ($i = 0; $i < 8; $i++) echo '<div class="tev-sk"></div>'; ?></div>
        <p class="tev-foot-note" data-tev="esempio"></p>
      </section>
    </div>
  </div>
</main>

<div class="tev-bar" id="tevBar">
  <div><div class="tev-bar__lab" data-tev="aPartire"></div><div class="tev-bar__row"><span class="tev-bar__n" data-tev="prezzo">&nbsp;</span><span class="tev-bar__iva" data-tev="iva"></span></div></div>
  <a class="tev-btn tev-btn--lime" href="#preventivo" data-tev="barCta"></a>
</div>

<div class="tev-lb" id="tevLb" hidden role="dialog" aria-modal="true" aria-labelledby="tevLbT">
  <div class="tev-lb__hd"><span id="tevLbT"></span><button type="button" class="tev-lb__x" id="tevLbX" aria-label="<?php echo esc_attr($ui[$lang]['chiudi']); ?>">&times;</button></div>
  <div class="tev-lb__st" id="tevLbS"></div>
  <div class="tev-lb__ft"><span id="tevLbC"></span><button type="button" class="tev-btn tev-btn--lime" id="tevLbAsk"></button></div>
</div>

<script type="application/json" id="tevCfg"><?php echo wp_json_encode($cfg, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP); ?></script>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events Staff e prezzi', 'extra' => true)); ?>

<?php toa_component('events-site-foot', array('tev_wa' => false)); ?>
