<?php
/**
 * Template Name: TOA Events - Artisti e performer
 * Mini sito TOA Events, sezione Artisti e performer (ticket CRM #1066, 23/11/2026, chat TEMA Artisti Performer) — IT/EN/FR/ES.
 * Campo personalizzato toa_ev_performer: vuoto = pagina indice; codice (es. mangiafuoco) = sottopagina di quella specialita'.
 * Testi in components/events-performer-data.php. ID pagine: opzione toa_events_site['performer'] (indice) e
 * toa_events_site['performer_acts'][codice][lingua] (sottopagine), scritte da _crea_performer_pagine_1066.php.
 * Due CTA: preventivo cliente (modulo in fondo) e iscrizione artista (/registrati-talent/?ruolo=<codice>, utm_* della pagina conservati).
 * Niente foto di persone reali: solo grafica.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };
$tdir = get_template_directory();
wp_enqueue_style('toa-events-site', get_template_directory_uri() . '/assets/css/events-site.css', array(), (string) @filemtime($tdir . '/assets/css/events-site.css'));

$PD   = include $tdir . '/components/events-performer-data.php';
$code = (string) get_post_meta(get_the_ID(), 'toa_ev_performer', true);
$act  = ($code !== '' && isset($PD['acts'][$code])) ? $PD['acts'][$code] : null;
if (!$act) $code = '';

// URL di una pagina della sezione nella lingua corrente ('' se non pubblicata; bozza visibile solo a chi puo' modificarla)
$site = get_option('toa_events_site', array());
$pf_url = function($id) use ($lang) {
    $id = (int) $id; if (!$id) return '';
    $st = get_post_status($id);
    if ($st === 'publish') return get_permalink($id);
    if ($st && current_user_can('edit_post', $id)) return add_query_arg('lang', $lang, get_permalink($id));
    return '';
};
$idx_url = $pf_url(isset($site['performer'][$lang]) ? $site['performer'][$lang] : 0);
$act_url = function($c) use ($site, $lang, $pf_url) { return $pf_url(isset($site['performer_acts'][$c][$lang]) ? $site['performer_acts'][$c][$lang] : 0); };
$bar_pages = get_option('toa_events_pages', array());
$bar_url = (isset($bar_pages['bar-spettacolo'][$lang]) && get_post_status((int) $bar_pages['bar-spettacolo'][$lang]) === 'publish') ? get_permalink((int) $bar_pages['bar-spettacolo'][$lang]) : '';

// Iscrizione artista: stessi URL di events-site-head.php; utm di default, sostituiti da quelli della pagina (JS sotto)
$reg = array('it'=>'/registrati-talent/','en'=>'/en/register-talent/','fr'=>'/fr/inscription-talent/','es'=>'/es/registrate-como-talent/');
$reg_q = array('utm_source' => 'toaevents', 'utm_medium' => 'performer-page', 'utm_campaign' => $code !== '' ? $code : 'indice');
if ($code !== '') $reg_q = array('ruolo' => $code) + $reg_q;
$reg_url = add_query_arg($reg_q, untrailingslashit(get_option('home')) . $reg[$lang]);

$t = array(
    'cta'    => array('it'=>"Richiedi preventivo",'en'=>"Get a quote",'fr'=>"Demander un devis",'es'=>"Pide presupuesto"),
    'join'   => array('it'=>"Sei un artista? Iscriviti",'en'=>"Are you an artist? Join us",'fr'=>"Vous êtes artiste ? Inscrivez-vous",'es'=>"¿Eres artista? Inscríbete"),
    'list_h' => array('it'=>"Specialità",'en'=>"Specialities",'fr'=>"Spécialités",'es'=>"Especialidades"),
    'more'   => array('it'=>"Scopri",'en'=>"Find out more",'fr'=>"Découvrir",'es'=>"Descubre"),
    'also_h' => array('it'=>"Anche",'en'=>"Also",'fr'=>"Aussi",'es'=>"También"),
    'also'   => array('it'=>"Cantanti, DJ e Intrattenitori",'en'=>"Singers, DJs and Entertainers",'fr'=>"Chanteurs, DJ et Animateurs",'es'=>"Cantantes, DJ y Animadores"),
    'other_h'=> array('it'=>"Altre specialità",'en'=>"More specialities",'fr'=>"Autres spécialités",'es'=>"Otras especialidades"),
    'all'    => array('it'=>"Tutti gli artisti",'en'=>"All artists",'fr'=>"Tous les artistes",'es'=>"Todos los artistas"),
    'faq_h'  => array('it'=>"Domande frequenti",'en'=>"FAQ",'fr'=>"Questions fréquentes",'es'=>"Preguntas frecuentes"),
    'art_h'  => array('it'=>"Sei un artista?",'en'=>"Are you an artist?",'fr'=>"Vous êtes artiste ?",'es'=>"¿Eres artista?"),
    'art_p'  => array('it'=>"Crea il tuo profilo gratis con foto e video dei tuoi numeri. Ti contattiamo quando arriva un lavoro adatto a te.",
                      'en'=>"Create your free profile with photos and videos of your acts. We’ll contact you when a suitable job comes up.",
                      'fr'=>"Créez votre profil gratuit avec photos et vidéos de vos numéros. Nous vous contactons quand un projet vous correspond.",
                      'es'=>"Crea tu perfil gratis con fotos y vídeos de tus números. Te contactamos cuando haya un trabajo adecuado para ti."),
    'art_b'  => array('it'=>"Crea profilo gratis",'en'=>"Create free profile",'fr'=>"Créer mon profil gratuit",'es'=>"Crear perfil gratis"),
    'legal'  => array('it'=>"Spettacoli con fuoco, acrobazie aeree e numeri in piazza solo dove la location e le regole locali lo consentono.",
                      'en'=>"Fire shows, aerial acts and street performances only where the venue and local rules allow them.",
                      'fr'=>"Spectacles de feu, numéros aériens et spectacles de rue uniquement là où le lieu et les règles locales le permettent.",
                      'es'=>"Espectáculos de fuego, números aéreos y actuaciones en la calle solo donde el lugar y las normas locales lo permiten."),
);

// H1 in minuscolo normale (i nomi-pillola hanno le iniziali maiuscole): «Statue viventi per eventi e spettacoli»
$sc = function($s) { $s = mb_strtolower($s, 'UTF-8'); return mb_strtoupper(mb_substr($s, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($s, 1, null, 'UTF-8'); };
$h1sfx = array('it'=>' per eventi e spettacoli','en'=>' for events and shows','fr'=>' pour événements et spectacles','es'=>' para eventos y espectáculos');
$h1    = $act ? $sc($_t($act['n'])) . $h1sfx[$lang] : $_t($PD['index']['h1']);
$intro = $act ? $_t($act['intro']) : $_t($PD['index']['intro']);
$faqs  = array_merge($act ? $act['faq'] : array(), $PD['common']);
$lead_name = $act ? $act['n']['it'] : 'Indice';

$faq_ld = array('@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array());
foreach ($faqs as $f) $faq_ld['mainEntity'][] = array('@type' => 'Question', 'name' => $_t($f['q']), 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $_t($f['a'])));

toa_component('events-site-head', array('tev_active' => 'performer', 'tev_cta_url' => '#preventivo', 'tev_cta_label' => $t['cta']));
?>
<style>
.tpf-crumb{font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin:0 0 14px;color:#6B6B66}
.tpf-crumb a{color:#08080A!important;text-decoration:none;border-bottom:2px solid #C8FF00}
.tpf-ctas{display:flex;flex-wrap:wrap;gap:12px;margin-top:26px}
.tpf-btn-out{display:inline-flex;align-items:center;justify-content:center;padding:14px 24px;border:2px solid #08080A;border-radius:999px;font-weight:800;font-size:15px;text-decoration:none;color:#08080A!important;background:#FFFFFF}
.tpf-btn-out:hover{background:#08080A;color:#FFFFFF!important}
.tpf-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:14px;margin-top:22px}
.tpf-card{display:flex;flex-direction:column;gap:10px;padding:22px 20px;border:1px solid #E6E6E1;border-radius:16px;background:#FFFFFF;text-decoration:none;color:#08080A!important;min-height:150px;transition:border-color .15s,transform .15s}
a.tpf-card:hover{border-color:#08080A;transform:translateY(-2px)}
.tpf-card__n{font-family:Anton,Impact,sans-serif;font-weight:400;font-size:24px;line-height:1.05;text-transform:uppercase;margin:0}
.tpf-card__s{margin:0;color:#3D3D39;font-size:15px;line-height:1.5;flex:1}
.tpf-card__a{font-weight:800;font-size:14px}
.tpf-card__a:after{content:" \2192";color:#08080A}
.tpf-card:nth-child(4n+1){border-top:4px solid #C8FF00}
.tpf-faq{max-width:860px;margin-top:18px}
.tpf-faq details{border-bottom:1px solid #E6E6E1;padding:16px 0}
.tpf-faq summary{cursor:pointer;font-weight:800;font-size:17px;list-style:none}
.tpf-faq summary::-webkit-details-marker{display:none}
.tpf-faq summary:after{content:"+";float:right;font-weight:800}
.tpf-faq details[open] summary:after{content:"\2212"}
.tpf-faq p{margin:10px 0 0;color:#3D3D39;font-size:16px;line-height:1.6}
.tpf-art{background:#C8FF00;color:#08080A}
.tpf-art .tev-cta-band__in p{color:#08080A}
.tpf-art .tev-h2{color:#08080A}
@media(max-width:768px){.tpf-ctas a{flex:1 1 100%}.tpf-card{min-height:0}.tpf-card__n{font-size:21px}}
</style>
<main class="tev-performer">
  <section class="tev-sec tev-sec--grey tev-pagehd">
    <div class="tev-wrap">
      <?php $hub_u = function_exists('toa_events_site_url') ? toa_events_site_url('hub', $lang) : ''; ?>
      <p class="tpf-crumb"><?php if ($hub_u): ?><a href="<?php echo esc_url($hub_u); ?>">TOA Events</a> / <?php endif; ?><?php if ($act && $idx_url): ?><a href="<?php echo esc_url($idx_url); ?>"><?php echo esc_html($_t($PD['index']['n'])); ?></a><?php elseif (!$act): echo esc_html($_t($PD['index']['n'])); endif; ?></p>
      <h1 class="tev-h1 tev-h1--xl"><?php echo esc_html($h1); ?></h1>
      <p class="tev-lead tev-lead--hero"><?php echo esc_html($intro); ?></p>
      <div class="tpf-ctas">
        <a class="tev-btn tev-btn--lime tev-btn--lg" href="#preventivo"><?php echo esc_html($_t($t['cta'])); ?></a>
        <a class="tpf-btn-out tpf-reg" href="<?php echo esc_url($reg_url); ?>"><?php echo esc_html($_t($t['join'])); ?></a>
      </div>
    </div>
  </section>

  <section class="tev-sec">
    <div class="tev-wrap">
      <h2 class="tev-h2"><?php echo esc_html($_t($act ? $t['other_h'] : $t['list_h'])); ?></h2>
      <?php if ($act): ?>
      <div class="tev-row" style="margin-top:18px">
        <?php foreach ($PD['acts'] as $c => $a): if ($c === $code) continue; $u = $act_url($c); if (!$u) continue; ?>
        <a class="tev-chip tev-chip--a" href="<?php echo esc_url($u); ?>"><?php echo esc_html($_t($a['n'])); ?></a>
        <?php endforeach; ?>
        <?php if ($idx_url): ?><a class="tev-chip tev-chip--a" style="border-color:#08080A" href="<?php echo esc_url($idx_url); ?>"><?php echo esc_html($_t($t['all'])); ?> &rarr;</a><?php endif; ?>
      </div>
      <?php else: ?>
      <div class="tpf-grid">
        <?php foreach ($PD['acts'] as $c => $a): $u = $act_url($c); ?>
        <<?php echo $u ? 'a href="' . esc_url($u) . '"' : 'div'; ?> class="tpf-card">
          <h3 class="tpf-card__n"><?php echo esc_html($_t($a['n'])); ?></h3>
          <p class="tpf-card__s"><?php echo esc_html($_t($a['s'])); ?></p>
          <?php if ($u): ?><span class="tpf-card__a"><?php echo esc_html($_t($t['more'])); ?></span><?php endif; ?>
        </<?php echo $u ? 'a' : 'div'; ?>>
        <?php endforeach; ?>
      </div>
      <?php if ($bar_url): ?>
      <div class="tev-row" style="margin-top:22px;align-items:center">
        <strong style="margin-right:6px"><?php echo esc_html($_t($t['also_h'])); ?>:</strong>
        <a class="tev-chip tev-chip--a" href="<?php echo esc_url($bar_url); ?>"><?php echo esc_html($_t($t['also'])); ?> &rarr;</a>
      </div>
      <?php endif; ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="tev-sec tev-sec--grey">
    <div class="tev-wrap">
      <h2 class="tev-h2"><?php echo esc_html($_t($t['faq_h'])); ?></h2>
      <div class="tpf-faq">
        <?php foreach ($faqs as $f): ?>
        <details><summary><?php echo esc_html($_t($f['q'])); ?></summary><p><?php echo esc_html($_t($f['a'])); ?></p></details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="tev-cta-band tpf-art">
    <div class="tev-wrap tev-cta-band__in">
      <div><h2 class="tev-h2"><?php echo esc_html($_t($t['art_h'])); ?></h2><p><?php echo esc_html($_t($t['art_p'])); ?></p></div>
      <a class="tev-btn tev-btn--dark tev-btn--lg tpf-reg" href="<?php echo esc_url($reg_url); ?>"><?php echo esc_html($_t($t['art_b'])); ?></a>
    </div>
  </section>
  <section class="tev-cta-band">
    <div class="tev-wrap tev-cta-band__legal" style="padding-top:22px"><?php echo esc_html($_t($t['legal'])); ?></div>
  </section>
</main>
<script type="application/ld+json"><?php echo wp_json_encode($faq_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events Artisti performer - ' . $lead_name, 'extra' => true)); ?>

<div class="tev-bar tev-bar--cta" id="tevBar"><a class="tev-btn tev-btn--lime" href="#preventivo"><?php echo esc_html($_t($t['cta'])); ?></a></div>
<script>
(function(){
  // utm_* arrivati con l'annuncio: passano al link di iscrizione artista (sostituiscono quelli di default)
  try {
    var q = new URLSearchParams(location.search), keep = [];
    q.forEach(function(v, k){ if (/^utm_[a-z]+$/.test(k) && v) keep.push([k, v.slice(0, 100)]); });
    if (keep.length) document.querySelectorAll('a.tpf-reg').forEach(function(a){
      var u = new URL(a.href); keep.forEach(function(p){ u.searchParams.set(p[0], p[1]); }); a.href = u.toString();
    });
  } catch(e) {}
  var bar=document.getElementById('tevBar'), form=document.getElementById('preventivo');
  if (bar && form && 'IntersectionObserver' in window) new IntersectionObserver(function(en){ en.forEach(function(x){ bar.classList.toggle('hide', x.isIntersecting); }); }, { threshold: 0 }).observe(form);
})();
</script>
<?php toa_component('events-site-foot'); ?>
