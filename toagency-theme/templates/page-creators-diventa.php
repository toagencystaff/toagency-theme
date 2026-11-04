<?php
/**
 * Template Name: TOA Creators - Diventa creator
 * Mini sito creator (ticket CRM #807, 04/11/2026, chat «TEMA Ticket 807») — IT/EN/FR/ES.
 * Per chi vuole lavorare come creator: cosa cerchiamo, come funziona il Kit Creator, iscrizione con il ruolo gia' scelto
 * (/registrati-talent/?ruolo=influencer, stesso meccanismo di «Lavora con noi» di TOA Events) e recupero del link per chi ha gia' la scheda.
 * Nessuna promessa su compensi o lavori: si dice solo come funziona.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };
$tdir = get_template_directory();
wp_enqueue_style('toa-creators-site', get_template_directory_uri() . '/assets/css/creators-site.css', array(), (string) @filemtime($tdir . '/assets/css/creators-site.css'));

$reg = array('it'=>'/registrati-talent/','en'=>'/en/register-talent/','fr'=>'/fr/inscription-talent/','es'=>'/es/registrate-como-talent/');
$reg_url = untrailingslashit(get_option('home')) . $reg[$lang] . '?ruolo=influencer';
$recupera = 'https://toagency.it/crm_toagency/recupera-link.php';

$T = array(
  'h1'   => array('it'=>'Diventa creator <em>TOAgency</em>','en'=>'Become a <em>TOAgency</em> creator','fr'=>'Devenez créateur <em>TOAgency</em>','es'=>'Hazte creador de <em>TOAgency</em>'),
  'lead' => array('it'=>'Lavori con brand veri: video UGC, contenuti sul tuo profilo, live coverage di eventi e stand. Noi ti proponiamo ai brand giusti, tu scegli a quali casting candidarti.',
                  'en'=>'Work with real brands: UGC videos, posts on your own profile, live coverage at events and booths. We put you forward to the right brands, and you choose which castings to apply for.',
                  'fr'=>"Travaillez avec de vraies marques : vidéos UGC, publications sur votre profil, couverture live d'événements et de stands. Nous vous présentons aux bonnes marques, vous choisissez les castings auxquels postuler.",
                  'es'=>'Trabaja con marcas reales: vídeos UGC, publicaciones en tu perfil, cobertura en directo de eventos y stands. Te proponemos a las marcas adecuadas y tú eliges a qué castings presentarte.'),
  'cta'  => array('it'=>'Iscriviti come creator','en'=>'Sign up as a creator','fr'=>"M'inscrire comme créateur",'es'=>'Regístrate como creador'),
  'q_h'  => array('it'=>'Cosa cerchiamo','en'=>"What we're looking for",'fr'=>'Ce que nous recherchons','es'=>'Qué buscamos'),
  'k_h'  => array('it'=>'Come funziona il Kit Creator','en'=>'How the Creator Kit works','fr'=>'Comment fonctionne le Kit Créateur','es'=>'Cómo funciona el Kit Creador'),
  'gia'  => array('it'=>'<b>Hai già una scheda TOAgency?</b> Non iscriverti di nuovo: recupera il link della tua scheda e aggiungi il ruolo creator.',
                  'en'=>"<b>Already have a TOAgency profile?</b> Don't sign up again: get the link to your profile back and add the creator role.",
                  'fr'=>'<b>Vous avez déjà une fiche TOAgency ?</b> Inutile de vous réinscrire : récupérez le lien de votre fiche et ajoutez le rôle créateur.',
                  'es'=>'<b>¿Ya tienes una ficha en TOAgency?</b> No te registres otra vez: recupera el enlace de tu ficha y añade el rol de creador.'),
  'giab' => array('it'=>'Recupera il link','en'=>'Get my link','fr'=>'Récupérer mon lien','es'=>'Recuperar mi enlace'),
  'brand'=> array('it'=>'Sei un brand? Guarda pacchetti, prezzi e creator.','en'=>'Are you a brand? See packages, prices and creators.','fr'=>'Vous êtes une marque ? Découvrez formules, prix et créateurs.','es'=>'¿Eres una marca? Mira paquetes, precios y creadores.'),
  'brandb'=>array('it'=>'Vai a TOA Creators','en'=>'Go to TOA Creators','fr'=>'Aller sur TOA Creators','es'=>'Ir a TOA Creators'),
);
$cerca = array(
  array(array('it'=>'Video originali','en'=>'Original videos','fr'=>'Des vidéos originales','es'=>'Vídeos originales'),
        array('it'=>'Girati da te, senza watermark di TikTok o di altre app: sono la prima cosa che guardano i brand.',
              'en'=>'Shot by you, with no TikTok or other app watermarks — they are the first thing brands look at.',
              'fr'=>"Tournées par vous, sans filigrane TikTok ni d'une autre appli : c'est ce que les marques regardent en premier.",
              'es'=>'Grabados por ti, sin marca de agua de TikTok ni de otras apps: es lo primero que miran las marcas.')),
  array(array('it'=>'Statistiche aggiornate','en'=>'Up-to-date stats','fr'=>'Des statistiques à jour','es'=>'Estadísticas al día'),
        array('it'=>'Screenshot recenti delle statistiche del tuo profilo (pubblico, visualizzazioni): così ti proponiamo con numeri veri.',
              'en'=>'Recent screenshots of your profile stats (audience, views), so we can put you forward with real numbers.',
              'fr'=>'Des captures récentes des statistiques de votre profil (audience, vues), pour vous présenter avec de vrais chiffres.',
              'es'=>'Capturas recientes de las estadísticas de tu perfil (público, visualizaciones), para presentarte con datos reales.')),
  array(array('it'=>'Una nicchia chiara','en'=>'A clear niche','fr'=>'Une thématique claire','es'=>'Una temática clara'),
        array('it'=>'Beauty, food, travel, fitness, moda…: dicci dove sei più forte e che contenuti sai fare (talking head, tutorial, recensioni…).',
              'en'=>'Beauty, food, travel, fitness, fashion…: tell us where you shine and what you can make (talking heads, tutorials, reviews…).',
              'fr'=>'Beauté, food, voyage, fitness, mode… : dites-nous où vous excellez et quels contenus vous savez faire (face caméra, tutos, avis…).',
              'es'=>'Beauty, food, viajes, fitness, moda…: dinos dónde eres más fuerte y qué contenidos sabes hacer (a cámara, tutoriales, reseñas…).')),
  array(array('it'=>'Il tuo compenso','en'=>'Your fee','fr'=>'Votre tarif','es'=>'Tu caché'),
        array('it'=>'In ogni casting per creator indichi tu quanto chiedi.','en'=>'For every creator casting, you set the fee you are asking for.','fr'=>'Pour chaque casting créateur, vous indiquez vous-même votre tarif.','es'=>'En cada casting de creadores indicas tú el caché que pides.')),
);
$kit = array(
  array(array('it'=>'Ti iscrivi come talent','en'=>'Sign up as a talent','fr'=>'Vous vous inscrivez comme talent','es'=>'Te registras como talent'),
        array('it'=>'Con il ruolo «Influencer/Creator» già scelto: bastano pochi minuti e qualche foto.','en'=>'With the "Influencer/Creator" role already selected: it only takes a few minutes and a few photos.','fr'=>'Avec le rôle « Influenceur/Créateur » déjà coché : quelques minutes et quelques photos suffisent.','es'=>'Con el rol «Influencer/Creador» ya elegido: bastan unos minutos y algunas fotos.')),
  array(array('it'=>'Completi il Kit Creator','en'=>'Complete the Creator Kit','fr'=>'Vous complétez le Kit Créateur','es'=>'Completas el Kit Creador'),
        array('it'=>'Quando ti candidi a un casting per creator ti chiediamo le statistiche (dagli screenshot) e almeno due video originali. Lo staff li controlla prima di pubblicarli.',
              'en'=>'When you apply for a creator casting, we ask for your stats (from screenshots) and at least two original videos. Our team checks them before they go live.',
              'fr'=>'Quand vous postulez à un casting créateur, nous vous demandons vos statistiques (en captures) et au moins deux vidéos originales. Notre équipe les vérifie avant de les publier.',
              'es'=>'Cuando te presentas a un casting de creadores te pedimos tus estadísticas (en capturas) y al menos dos vídeos originales. El equipo los revisa antes de publicarlos.')),
  array(array('it'=>'Entri nella vetrina dei brand','en'=>'Get into the brand showcase','fr'=>'Vous entrez dans la vitrine des marques','es'=>'Entras en el escaparate de marcas'),
        array('it'=>'Con un video approvato e statistiche recenti puoi comparire tra i creator che proponiamo ai brand su toagency.it, sempre senza i tuoi social né la tua città.',
              'en'=>'With an approved video and recent stats, you can appear among the creators we show brands on toagency.it — never with your socials or your city.',
              'fr'=>'Avec une vidéo approuvée et des statistiques récentes, vous pouvez apparaître parmi les créateurs proposés aux marques sur toagency.it, sans jamais vos réseaux ni votre ville.',
              'es'=>'Con un vídeo aprobado y estadísticas recientes puedes aparecer entre los creadores que mostramos a las marcas en toagency.it, nunca con tus redes ni tu ciudad.')),
);
$home_cr = function_exists('toa_creators_url') ? toa_creators_url('home', $lang) : '';

toa_component('creators-site-head', array('tcr_active' => 'diventa'));
if (!$home_cr && function_exists('toa_creators_url')) $home_cr = toa_creators_url('home', $lang);
?>
<main class="tcr-main">
  <div class="tcr-wrap">
    <section class="tcr-dv-hero">
      <div class="tcr-eyebrow">TOA Creators</div>
      <h1 class="tcr-h1"><?php echo wp_kses($_t($T['h1']), array('em' => array())); ?></h1>
      <p class="tcr-lead"><?php echo esc_html($_t($T['lead'])); ?></p>
      <div class="tcr-btns"><a class="tcr-btn tcr-btn--p" href="<?php echo esc_url($reg_url); ?>"><?php echo esc_html($_t($T['cta'])); ?></a></div>
    </section>

    <section class="tcr-sec">
      <div class="tcr-sh"><h2 class="tcr-h2"><?php echo esc_html($_t($T['q_h'])); ?></h2></div>
      <div class="tcr-dv-grid">
        <?php foreach ($cerca as $c): ?><div class="tcr-dv-card"><h3><?php echo esc_html($_t($c[0])); ?></h3><p><?php echo esc_html($_t($c[1])); ?></p></div><?php endforeach; ?>
      </div>
    </section>

    <section class="tcr-sec">
      <div class="tcr-sh"><h2 class="tcr-h2"><?php echo esc_html($_t($T['k_h'])); ?></h2></div>
      <div class="tcr-steps">
        <?php foreach ($kit as $i => $s): ?><div class="tcr-st"><b><?php echo $i + 1; ?></b><h3><?php echo esc_html($_t($s[0])); ?></h3><p><?php echo esc_html($_t($s[1])); ?></p></div><?php endforeach; ?>
      </div>
      <div class="tcr-btns"><a class="tcr-btn tcr-btn--p" href="<?php echo esc_url($reg_url); ?>"><?php echo esc_html($_t($T['cta'])); ?></a></div>
    </section>

    <section class="tcr-sec">
      <div class="tcr-dv-note"><span><?php echo wp_kses($_t($T['gia']), array('b' => array())); ?></span><a class="tcr-btn tcr-btn--s" href="<?php echo esc_url($recupera); ?>"><?php echo esc_html($_t($T['giab'])); ?></a></div>
    </section>

    <?php if ($home_cr): ?>
    <div class="tcr-join"><span><?php echo esc_html($_t($T['brand'])); ?></span><a class="tcr-btn tcr-btn--s" href="<?php echo esc_url($home_cr); ?>"><?php echo esc_html($_t($T['brandb'])); ?></a></div>
    <?php endif; ?>
  </div>
</main>
<?php toa_component('creators-site-foot'); ?>
