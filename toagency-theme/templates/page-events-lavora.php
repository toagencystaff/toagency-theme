<?php
/**
 * Template Name: TOA Events - Lavora con noi
 * Mini sito TOA Events (ticket #811, 03/11/2026, chat TEMA-Events) — IT/EN/FR/ES.
 * Pagina per far registrare hostess, steward e promoter: cosa guardano i clienti, esempi foto sì/no (guida foto eventi),
 * poi la registrazione di sempre (/registrati-talent/ e traduzioni) con ?ruolo=hostess: ruolo già scelto e album «Fiere e eventi» aperto.
 * Nessuna promessa di lavori o guadagni.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };
$tdir = get_template_directory(); $turi = get_template_directory_uri();
wp_enqueue_style('toa-events-site', $turi . '/assets/css/events-site.css', array(), (string) @filemtime($tdir . '/assets/css/events-site.css'));

// stessi URL di registrazione di page-collabora.php / b2b-intent-gate.php
$reg = array('it'=>'/registrati-talent/','en'=>'/en/register-talent/','fr'=>'/fr/inscription-talent/','es'=>'/es/registrate-como-talent/');
$reg_url = untrailingslashit(get_option('home')) . $reg[$lang] . '?ruolo=hostess';

$t = array(
    'h1'   => array('it'=>"Lavora agli eventi come hostess, steward o promoter",'en'=>"Work at events as a hostess, steward or promoter",'fr'=>"Travaillez dans l'événementiel comme hôtesse, steward ou promoteur",'es'=>"Trabaja en eventos como azafata, steward o promotor"),
    'lead' => array('it'=>"Fiere, congressi, lanci di prodotto ed eventi sportivi in Italia, Francia, Germania, Spagna, Regno Unito e in altri Paesi. Crea il tuo profilo gratis: ti proponiamo i lavori della tua zona.",
                    'en'=>"Trade shows, conferences, product launches and sports events in Italy, France, Germany, Spain, the UK and beyond. Create your free profile and we'll offer you jobs near you.",
                    'fr'=>"Salons, congrès, lancements de produits et événements sportifs en Italie, en France, en Allemagne, en Espagne, au Royaume-Uni et ailleurs. Créez votre profil gratuitement : nous vous proposons les missions près de chez vous.",
                    'es'=>"Ferias, congresos, lanzamientos de producto y eventos deportivos en Italia, Francia, Alemania, España, Reino Unido y otros países. Crea tu perfil gratis y te proponemos trabajos en tu zona."),
    'cta'  => array('it'=>"Crea il tuo profilo gratis",'en'=>"Create your free profile",'fr'=>"Créez votre profil gratuitement",'es'=>"Crea tu perfil gratis"),
    'cta_s'=> array('it'=>"Crea il tuo profilo",'en'=>"Create your profile",'fr'=>"Créer mon profil",'es'=>"Crear mi perfil"),
    'look_h' => array('it'=>"Cosa guardano i clienti degli eventi",'en'=>"What event clients look at",'fr'=>"Ce que regardent les clients de l'événementiel",'es'=>"Lo que miran los clientes de eventos"),
    'look_p' => array('it'=>"Non servono misure da modella. Contano queste quattro cose: più ne compili, più è facile proporti.",'en'=>"You don't need model measurements. These four things matter: the more you fill in, the easier it is to put you forward.",'fr'=>"Pas besoin de mensurations de mannequin. Ces quatre choses comptent : plus vous en remplissez, plus il est facile de vous proposer.",'es'=>"No hacen falta medidas de modelo. Cuentan estas cuatro cosas: cuantas más completes, más fácil es proponerte."),
    'look' => array(
        array(array('it'=>"Foto in servizio",'en'=>"Photos at work",'fr'=>"Photos en service",'es'=>"Fotos trabajando"),
              array('it'=>"Vestita da lavoro, luce buona, sguardo in camera. Vanno nell'album «Fiere e eventi».",'en'=>"Dressed for work, good light, looking at the camera. They go in the «Trade fairs & events» album.",'fr'=>"En tenue de travail, bonne lumière, regard vers l'objectif. Elles vont dans l'album « Salons et événements ».",'es'=>"Con ropa de trabajo, buena luz y mirando a cámara. Van en el álbum «Ferias y eventos».")),
        array(array('it'=>"Lingue con il livello",'en'=>"Languages and level",'fr'=>"Langues et niveau",'es'=>"Idiomas con su nivel"),
              array('it'=>"Scrivi quanto bene le parli: B1, B2, C1, madrelingua.",'en'=>"Say how well you speak them: B1, B2, C1, native.",'fr'=>"Indiquez votre niveau : B1, B2, C1, langue maternelle.",'es'=>"Indica tu nivel: B1, B2, C1, nativo.")),
        array(array('it'=>"Esperienza",'en'=>"Experience",'fr'=>"Expérience",'es'=>"Experiencia"),
              array('it'=>"Fiere, congressi, promo, sport: anche poche giornate contano. E i tuoi attestati (HACCP, primo soccorso).",'en'=>"Trade shows, conferences, promo, sport: even a few days count. Add your certificates too (food hygiene, first aid).",'fr'=>"Salons, congrès, promo, sport : même quelques journées comptent. Et vos attestations (hygiène alimentaire, premiers secours).",'es'=>"Ferias, congresos, promociones, deporte: cuentan incluso pocas jornadas. Y tus certificados (manipulador de alimentos, primeros auxilios).")),
        array(array('it'=>"Zona, patente, auto",'en'=>"Area, licence, car",'fr'=>"Zone, permis, voiture",'es'=>"Zona, carné, coche"),
              array('it'=>"Ti proponiamo i lavori vicini a te, e anche quelli dove serve spostarsi.",'en'=>"We offer you jobs close to you, and also those where you need to travel.",'fr'=>"Nous vous proposons les missions proches de chez vous, et aussi celles où il faut se déplacer.",'es'=>"Te proponemos trabajos cerca de ti y también aquellos en los que hay que desplazarse.")),
    ),
    'foto_h' => array('it'=>"Le foto giuste",'en'=>"The right photos",'fr'=>"Les bonnes photos",'es'=>"Las fotos adecuadas"),
    'si' => array('it'=>"Sì",'en'=>"Yes",'fr'=>"Oui",'es'=>"Sí"),
    'no' => array('it'=>"No",'en'=>"No",'fr'=>"Non",'es'=>"No"),
    'foto' => array(
        array(true,  'staff/hostess.jpg',       array('it'=>"tailleur o camicia elegante",'en'=>"suit or smart shirt",'fr'=>"tailleur ou chemise élégante",'es'=>"traje o camisa elegante")),
        array(true,  'staff/steward.jpg',       array('it'=>"in servizio, in divisa",'en'=>"at work, in uniform",'fr'=>"en service, en tenue",'es'=>"trabajando, de uniforme")),
        array(true,  'staff/hostess-fiera.jpg', array('it'=>"a una fiera, sguardo in camera",'en'=>"at a trade show, looking at the camera",'fr'=>"sur un salon, regard vers l'objectif",'es'=>"en una feria, mirando a cámara")),
        array(false, 'guide/no-discoteca.jpg',  array('it'=>"foto in discoteca",'en'=>"nightclub photos",'fr'=>"photos en boîte de nuit",'es'=>"fotos en la discoteca")),
        array(false, 'guide/no-occhiali.jpg',   array('it'=>"occhiali da sole",'en'=>"sunglasses",'fr'=>"lunettes de soleil",'es'=>"gafas de sol")),
        array(false, 'guide/no-gruppo.jpg',     array('it'=>"foto di gruppo",'en'=>"group photos",'fr'=>"photos de groupe",'es'=>"fotos de grupo")),
    ),
    'steps_h' => array('it'=>"Tre passi",'en'=>"Three steps",'fr'=>"Trois étapes",'es'=>"Tres pasos"),
    'steps' => array(
        array('it'=>"Nome, contatti, zona e foto in primo piano",'en'=>"Name, contact details, area and a close-up photo",'fr'=>"Nom, coordonnées, zone et photo en gros plan",'es'=>"Nombre, contacto, zona y foto de primer plano"),
        array('it'=>"Foto in servizio, lingue ed esperienza",'en'=>"Work photos, languages and experience",'fr'=>"Photos en service, langues et expérience",'es'=>"Fotos trabajando, idiomas y experiencia"),
        array('it'=>"Ti avvisiamo dei lavori della tua zona",'en'=>"We let you know about jobs in your area",'fr'=>"Nous vous prévenons des missions près de chez vous",'es'=>"Te avisamos de los trabajos de tu zona"),
    ),
    'start' => array('it'=>"Inizia la registrazione",'en'=>"Start registering",'fr'=>"Commencer l'inscription",'es'=>"Empieza el registro"),
    'start_n' => array('it'=>"Il ruolo hostess è già scelto e l'album «Fiere e eventi» si apre per primo.",'en'=>"The hostess role is already selected and the «Trade fairs & events» album opens first.",'fr'=>"Le rôle hôtesse est déjà choisi et l'album « Salons et événements » s'ouvre en premier.",'es'=>"El rol de azafata ya está elegido y el álbum «Ferias y eventos» se abre primero."),
);

toa_component('events-site-head', array('tev_active' => 'lavora', 'tev_cta_url' => $reg_url, 'tev_cta_label' => $t['cta_s']));
?>
<main class="tev-lavora">
  <section class="tev-sec tev-sec--grey tev-pagehd">
    <div class="tev-wrap tev-hero__in">
      <div class="tev-hero__txt">
        <h1 class="tev-h1 tev-h1--l"><?php echo esc_html($_t($t['h1'])); ?></h1>
        <p class="tev-lead tev-lead--hero"><?php echo esc_html($_t($t['lead'])); ?></p>
        <a class="tev-btn tev-btn--lime tev-btn--lg" href="<?php echo esc_url($reg_url); ?>"><?php echo esc_html($_t($t['cta'])); ?></a>
      </div>
      <div class="tev-duo" aria-hidden="true">
        <img src="<?php echo esc_url($turi . '/assets/staff/hostess-fiera.jpg'); ?>" alt="" width="300" height="400" decoding="async">
        <img src="<?php echo esc_url($turi . '/assets/staff/promoter.jpg'); ?>" alt="" width="300" height="400" decoding="async">
      </div>
    </div>
  </section>

  <section class="tev-sec">
    <div class="tev-wrap">
      <h2 class="tev-h2"><?php echo esc_html($_t($t['look_h'])); ?></h2>
      <p class="tev-lead"><?php echo esc_html($_t($t['look_p'])); ?></p>
      <div class="tev-cards4">
        <?php
        // icone (camera, lingue, esperienza, zona) nello stesso ordine di $t['look']
        $ic = array(
          '<path d="M4 8h3l2-3h6l2 3h3v11H4z"/><circle cx="12" cy="13" r="3.5"/>',
          '<path d="M4 5h16v10H9l-5 4z"/><path d="M8 9h8M8 12h5"/>',
          '<path d="M12 3l2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L3.5 9.2l5.9-.9z"/>',
          '<path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/>',
        );
        foreach ($t['look'] as $n => $lk): ?>
        <div class="tev-card4">
          <span class="tev-card4__ic" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#08080A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><?php echo $ic[$n]; ?></svg></span>
          <span class="tev-card4__n" aria-hidden="true"><?php echo sprintf('%02d', $n + 1); ?></span>
          <h3><?php echo esc_html($_t($lk[0])); ?></h3>
          <p><?php echo esc_html($_t($lk[1])); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="tev-sec tev-sec--tight">
    <div class="tev-wrap">
      <h2 class="tev-h2"><?php echo esc_html($_t($t['foto_h'])); ?></h2>
      <div class="tev-fotos">
        <?php foreach ($t['foto'] as $f): if (!file_exists($tdir . '/assets/' . $f[1])) continue; ?>
        <figure class="tev-foto <?php echo $f[0] ? 'is-si' : 'is-no'; ?>">
          <img src="<?php echo esc_url($turi . '/assets/' . $f[1]); ?>" alt="<?php echo esc_attr(($f[0] ? $_t($t['si']) : $_t($t['no'])) . ' — ' . $_t($f[2])); ?>" loading="lazy" decoding="async" width="240" height="320">
          <figcaption><b><?php echo esc_html($f[0] ? $_t($t['si']) : $_t($t['no'])); ?></b> · <?php echo esc_html($_t($f[2])); ?></figcaption>
        </figure>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="tev-sec" id="registrati">
    <div class="tev-wrap">
      <h2 class="tev-h2"><?php echo esc_html($_t($t['steps_h'])); ?></h2>
      <div class="tev-steps">
        <?php foreach ($t['steps'] as $n => $st): ?>
        <div class="tev-step"><span class="tev-step__n"><?php echo $n + 1; ?></span><p><?php echo esc_html($_t($st)); ?></p></div>
        <?php endforeach; ?>
      </div>
      <a class="tev-btn tev-btn--lime tev-btn--lg" href="<?php echo esc_url($reg_url); ?>"><?php echo esc_html($_t($t['start'])); ?></a>
      <p class="tev-note" style="margin-top:12px"><?php echo esc_html($_t($t['start_n'])); ?></p>
    </div>
  </section>
</main>

<div class="tev-bar tev-bar--cta"><a class="tev-btn tev-btn--lime" href="<?php echo esc_url($reg_url); ?>"><?php echo esc_html($_t($t['cta_s'])); ?></a></div>
<?php toa_component('events-site-foot'); ?>
