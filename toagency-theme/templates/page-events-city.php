<?php
/**
 * Template Name: TOA Events - Città
 * Pagina città TOA Events (#637, 19/10/2026) — IT/EN/FR/ES. La città si sceglie col campo personalizzato toa_ev_city (es. torino).
 * Testi in components/events-cities.php, servizi in components/events-data.php. Link tra pagine: opzione toa_events_pages (id per lingua).
 * I link alle pagine/articoli già esistenti si ricavano dallo slug italiano + WPML (mai URL costruiti a mano); se manca la traduzione il link non compare.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$data   = include get_template_directory() . '/components/events-data.php';
$cities = include get_template_directory() . '/components/events-cities.php';
$ckey   = get_post_meta(get_the_ID(), 'toa_ev_city', true);
if (!isset($cities[$ckey])) { $ckey = 'torino'; }
$c = $cities[$ckey];

$pages = get_option('toa_events_pages', array());
$own_url = function($k) use ($pages, $lang) {
    return (isset($pages[$k][$lang]) && get_post_status((int) $pages[$k][$lang]) === 'publish') ? get_permalink((int) $pages[$k][$lang]) : '';
};
// Pagina/articolo già esistente: slug italiano -> traduzione WPML nella lingua corrente (solo se pubblicata)
$old_url = function($slug) use ($lang) {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare("SELECT ID, post_type FROM {$wpdb->posts} WHERE post_name=%s AND post_status='publish' AND post_type IN ('page','post') ORDER BY (post_type='page') DESC LIMIT 1", $slug));
    if (!$row) { return ''; }
    $id = (int) apply_filters('wpml_object_id', (int) $row->ID, $row->post_type, false, $lang);
    return ($id && get_post_status($id) === 'publish') ? get_permalink($id) : '';
};

$t = array(
    'cta'      => array('it'=>"Richiedi un preventivo",'en'=>"Request a quote",'fr'=>"Demandez un devis",'es'=>"Solicita un presupuesto"),
    'line'     => array('it'=>"Raccontaci cosa ti serve per l'evento: ce ne occupiamo noi.",'en'=>"Tell us what your event needs: we'll take care of it.",'fr'=>"Dites-nous ce dont votre événement a besoin : nous nous en occupons.",'es'=>"Cuéntanos qué necesita tu evento: nos encargamos nosotros."),
    'b2bonly'  => array('it'=>"Servizio per aziende, agenzie e produzioni — non per candidature.",'en'=>"A service for companies, agencies and productions — not for job applications.",'fr'=>"Un service pour entreprises, agences et productions — pas pour les candidatures.",'es'=>"Un servicio para empresas, agencias y producciones — no para candidaturas."),
    'talentexit' => array('it'=>"Sei un talent in cerca di lavoro? Registrati qui",'en'=>"Are you a talent looking for work? Register here",'fr'=>"Vous êtes un talent à la recherche de travail ? Inscrivez-vous ici",'es'=>"¿Eres un talento que busca trabajo? Regístrate aquí"),
    'o_hub'    => array('it'=>"Tutti i servizi TOA Events",'en'=>"All TOA Events services",'fr'=>"Tous les services TOA Events",'es'=>"Todos los servicios de TOA Events"),
    'how_step1'=> array('it'=>"CI SCRIVI",'en'=>"CONTACT US",'fr'=>"CONTACTEZ-NOUS",'es'=>"CONTÁCTANOS"),
    'how_step2'=> array('it'=>"TI RISPONDIAMO",'en'=>"WE REPLY",'fr'=>"NOUS RÉPONDONS",'es'=>"TE RESPONDEMOS"),
    'how_step3'=> array('it'=>"PROPOSTA SU MISURA",'en'=>"TAILORED PROPOSAL",'fr'=>"PROPOSITION SUR MESURE",'es'=>"PROPUESTA A MEDIDA"),
    'how_step4'=> array('it'=>"GESTIONE COMPLETA",'en'=>"FULL MANAGEMENT",'fr'=>"GESTION COMPLÈTE",'es'=>"GESTIÓN COMPLETA"),
    'how_tag'  => array('it'=>"Un solo referente • Un solo preventivo • Risposta rapida",'en'=>"One point of contact • One quote • Fast reply",'fr'=>"Un seul interlocuteur • Un seul devis • Réponse rapide",'es'=>"Un único interlocutor • Un único presupuesto • Respuesta rápida"),
);

// Dati strutturati FAQ (stesse domande e risposte della pagina)
$faq_ld = array('@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => array());
foreach ($c['faq'] as $f) {
    $faq_ld['mainEntity'][] = array('@type' => 'Question', 'name' => $_t($f['q']), 'acceptedAnswer' => array('@type' => 'Answer', 'text' => $_t($f['a'])));
}

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'TOA EVENTS',
    'title'      => $_t($c['h1']),
    'subtitle'   => $_t($c['intro']),
)); ?>

<style>
.toa-hero-cta{display:inline-flex;align-items:center;gap:10px;padding:16px 38px;background:var(--accent,#c8ff00);color:#0a0a0a;font-size:.95rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;box-shadow:0 8px 30px rgba(200,255,0,.35),0 2px 8px rgba(0,0,0,.15);transition:transform .25s ease,box-shadow .25s ease}
.toa-hero-cta svg{transition:transform .25s ease}
.toa-hero-cta:hover{transform:translateY(-3px);box-shadow:0 14px 42px rgba(200,255,0,.55),0 4px 12px rgba(0,0,0,.2)}
.toa-hero-cta:hover svg{transform:translateX(4px)}
@media(max-width:600px){.toa-hero-cta{padding:16px 30px;font-size:.95rem}}
.hero-cta-wrap{margin-top:16px;margin-bottom:28px;text-align:center}
@media(max-width:768px){.hero-cta-wrap{margin-top:18px;margin-bottom:20px}}
.toa-ev-sub{margin:8px 0 0;color:var(--gray-4);font-size:.98rem;max-width:720px}
.toa-ev-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-top:28px}
.toa-ev-venue{border:1px solid rgba(150,150,150,.3);border-radius:14px;padding:20px;display:flex;flex-direction:column;gap:8px}
.toa-ev-venue h3{font-family:var(--font-display);font-size:1.1rem;font-weight:800;text-transform:uppercase;margin:0}
.toa-ev-venue p{margin:0;color:var(--gray-4);font-size:.95rem;line-height:1.6}
.toa-ev-venue a{margin-top:auto;padding-top:6px;font-size:.85rem;font-weight:700;color:var(--accent);text-decoration:none}
.toa-ev-venue a:hover{text-decoration:underline}
.toa-ev-links{display:flex;flex-wrap:wrap;gap:10px;margin-top:22px}
.toa-ev-links a{display:inline-block;padding:10px 18px;border:1px solid rgba(150,150,150,.35);border-radius:999px;font-size:.85rem;font-weight:700;text-decoration:none;color:inherit;transition:border-color .15s,color .15s}
.toa-ev-links a:hover{border-color:var(--accent);color:var(--accent)}
.toa-ev-links a.hub{border-color:var(--accent);color:var(--accent)}
.toa-ev-xlink{display:block;margin-top:22px;border:1px solid rgba(150,150,150,.3);border-radius:14px;padding:20px;text-decoration:none;color:inherit;transition:border-color .15s}
.toa-ev-xlink:hover{border-color:var(--accent)}
.toa-ev-xlink strong{display:block;font-family:var(--font-display);font-size:1.1rem;font-weight:800;text-transform:uppercase;margin-bottom:6px}
.toa-ev-xlink span{color:var(--gray-4);font-size:.95rem;line-height:1.6}
.toa-ev-faq{margin-top:24px;max-width:820px}
.toa-ev-faq details{border-bottom:1px solid rgba(150,150,150,.3);padding:16px 0}
.toa-ev-faq summary{cursor:pointer;font-weight:700;font-size:1.02rem;list-style:none}
.toa-ev-faq summary::-webkit-details-marker{display:none}
.toa-ev-faq summary:after{content:"+";float:right;color:var(--accent);font-weight:800}
.toa-ev-faq details[open] summary:after{content:"\2212"}
.toa-ev-faq p{margin:10px 0 0;color:var(--gray-4);font-size:.97rem;line-height:1.7}
.toa-mobile-cta{display:none}
@media(max-width:768px){
  .toa-mobile-cta{position:fixed;left:0;right:0;bottom:0;z-index:900;display:block;padding:10px 14px;padding-bottom:calc(10px + env(safe-area-inset-bottom));background:rgba(10,10,10,.92);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px);border-top:1px solid rgba(255,255,255,.08);transition:transform .3s ease}
  .toa-mobile-cta.hide{transform:translateY(130%)}
  .toa-mobile-cta a{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:15px 20px;background:var(--accent,#c8ff00);color:#0a0a0a;font-weight:700;font-size:.95rem;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;text-decoration:none}
}
</style>

<div class="container hero-cta-wrap">
    <a href="#preventivo" class="toa-hero-cta">
        <span><?php echo esc_html($_t($t['cta'])); ?></span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.9rem;color:var(--gray-4);margin-top:16px;font-weight:600"><?php echo esc_html($_t($t['line'])); ?></p>
    <?php if (!empty($c['price'])): ?><p style="font-size:0.95rem;margin-top:8px;font-weight:700"><?php echo esc_html($_t($c['price'])); ?></p><?php endif; ?>
</div>

<!-- Sedi ed eventi della città -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo esc_html($_t($c['v_eyebrow'])); ?></div>
        <h2 class="section-heading"><?php echo esc_html($_t($c['v_heading'])); ?></h2>
        <p class="toa-ev-sub"><?php echo esc_html($_t($c['v_sub'])); ?></p>
        <div class="toa-ev-grid">
            <?php foreach ($c['venues'] as $v):
                $href = !empty($v['own']) ? $own_url($v['own']) : (!empty($v['link']) ? $old_url($v['link']) : ''); ?>
            <div class="toa-ev-venue">
                <h3><?php echo esc_html($_t($v['n'])); ?></h3>
                <p><?php echo esc_html($_t($v['d'])); ?></p>
                <?php if ($href): ?><a href="<?php echo esc_url($href); ?>"><?php echo esc_html($_t($v['l'])); ?> &rarr;</a><?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Servizi TOA Events -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo esc_html($_t($c['s_eyebrow'])); ?></div>
        <h2 class="section-heading"><?php echo esc_html($_t($c['s_heading'])); ?></h2>
        <div class="toa-ev-links">
            <?php foreach ($data as $dk => $dv): $u = $own_url($dk); if (!$u) continue; ?>
            <a href="<?php echo esc_url($u); ?>"><?php echo esc_html($_t($dv['title'])); ?></a>
            <?php endforeach; ?>
            <?php $hub = $own_url('hub'); if ($hub): ?><a class="hub" href="<?php echo esc_url($hub); ?>"><?php echo esc_html($_t($t['o_hub'])); ?> &rarr;</a><?php endif; ?>
        </div>
        <?php $xh = $old_url($c['x_hostess']['link']); if ($xh): ?>
        <a class="toa-ev-xlink" href="<?php echo esc_url($xh); ?>">
            <strong><?php echo esc_html($_t($c['x_hostess']['n'])); ?> &rarr;</strong>
            <span><?php echo esc_html($_t($c['x_hostess']['d'])); ?></span>
        </a>
        <?php endif; ?>
    </div>
</section>

<!-- Solo aziende: anti-candidature -->
<section style="padding:26px 0;background:var(--black);color:#fff;text-align:center">
    <div class="container">
        <p style="font-size:0.95rem;margin-bottom:10px;opacity:.85"><?php echo esc_html($_t($t['b2bonly'])); ?></p>
        <a href="<?php echo home_url('/registrati-talent/'); ?>" style="display:inline-block;font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;color:var(--accent);text-decoration:none"><?php echo esc_html($_t($t['talentexit'])); ?> &rarr;</a>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "01", 'label' => $_t($t['how_step1'])),
        array('time' => "02", 'label' => $_t($t['how_step2'])),
        array('time' => "03", 'label' => $_t($t['how_step3'])),
        array('time' => "04", 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tag']),
)); ?>

<?php toa_component('google-reviews'); ?>

<!-- FAQ -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo esc_html($_t($c['f_eyebrow'])); ?></div>
        <h2 class="section-heading"><?php echo esc_html($_t($c['f_heading'])); ?></h2>
        <div class="toa-ev-faq">
            <?php foreach ($c['faq'] as $f): ?>
            <details>
                <summary><?php echo esc_html($_t($f['q'])); ?></summary>
                <p><?php echo esc_html($_t($f['a'])); ?></p>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<script type="application/ld+json"><?php echo wp_json_encode($faq_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<?php toa_component('events-coverage', array('lang' => $lang)); ?>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events - ' . $c['name']['it'])); ?>

<!-- Barra CTA fissa mobile -->
<div class="toa-mobile-cta" id="toaMobileCta">
    <a href="#preventivo"><?php echo esc_html($_t($t['cta'])); ?>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
</div>
<script>
(function(){
  var bar=document.getElementById('toaMobileCta');
  var form=document.getElementById('preventivo');
  if(!bar||!form||!('IntersectionObserver' in window))return;
  new IntersectionObserver(function(entries){
    entries.forEach(function(e){ bar.classList.toggle('hide', e.isIntersecting); });
  },{threshold:0}).observe(form);
})();
</script>

<?php toa_component('footer'); ?>
