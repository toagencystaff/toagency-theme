<?php
/**
 * Template Name: TOA Events - Servizio
 * Sottopagina di un servizio TOA Events (#637, 15/10/2026) — IT/EN/FR/ES.
 * Il servizio si sceglie col campo personalizzato toa_ev_service (staff|fiere|bar-spettacolo|foto-video|crew|logistica).
 * Testi e figure in components/events-data.php. Link tra pagine: opzione toa_events_pages (id per lingua).
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$data = include get_template_directory() . '/components/events-data.php';
$key  = get_post_meta(get_the_ID(), 'toa_ev_service', true);
if (!isset($data[$key])) { $key = 'staff'; }
$d = $data[$key];

// Nomi brevi dei servizi (per i link alle altre pagine) e origine lead: dai dati
$names = array(); foreach ($data as $dk => $dv) { $names[$dk] = $dv['title']; }
$leadnames = array(); foreach ($data as $dk => $dv) { $leadnames[$dk] = $dv['lead']; }
$pages = get_option('toa_events_pages', array());
$url = function($k) use ($pages, $lang) {
    return (isset($pages[$k][$lang]) && get_post_status((int) $pages[$k][$lang]) === 'publish') ? get_permalink((int) $pages[$k][$lang]) : ''; // solo pagine pubblicate
};

$t = array(
    'cta'      => array('it'=>"Richiedi un preventivo",'en'=>"Request a quote",'fr'=>"Demandez un devis",'es'=>"Solicita un presupuesto"),
    'line'     => array('it'=>"Raccontaci cosa ti serve per l'evento: ce ne occupiamo noi.",'en'=>"Tell us what your event needs: we'll take care of it.",'fr'=>"Dites-nous ce dont votre événement a besoin : nous nous en occupons.",'es'=>"Cuéntanos qué necesita tu evento: nos encargamos nosotros."),
    'b2bonly'  => array('it'=>"Servizio per aziende, agenzie e produzioni — non per candidature.",'en'=>"A service for companies, agencies and productions — not for job applications.",'fr'=>"Un service pour entreprises, agences et productions — pas pour les candidatures.",'es'=>"Un servicio para empresas, agencias y producciones — no para candidaturas."),
    'talentexit' => array('it'=>"Sei un talent in cerca di lavoro? Registrati qui",'en'=>"Are you a talent looking for work? Register here",'fr'=>"Vous êtes un talent à la recherche de travail ? Inscrivez-vous ici",'es'=>"¿Eres un talento que busca trabajo? Regístrate aquí"),
    'r_eyebrow'=> array('it'=>"Cosa proponiamo",'en'=>"What we offer",'fr'=>"Ce que nous proposons",'es'=>"Qué ofrecemos"),
    'r_heading'=> array('it'=>"Le figure che proponiamo",'en'=>"The roles we provide",'fr'=>"Les profils que nous proposons",'es'=>"Los perfiles que ofrecemos"),
    'o_eyebrow'=> array('it'=>"Altri servizi",'en'=>"More services",'fr'=>"Autres services",'es'=>"Otros servicios"),
    'o_hub'    => array('it'=>"Tutti i servizi TOA Events",'en'=>"All TOA Events services",'fr'=>"Tous les services TOA Events",'es'=>"Todos los servicios de TOA Events"),
    'how_step1'=> array('it'=>"CI SCRIVI",'en'=>"CONTACT US",'fr'=>"CONTACTEZ-NOUS",'es'=>"CONTÁCTANOS"),
    'how_step2'=> array('it'=>"TI RISPONDIAMO",'en'=>"WE REPLY",'fr'=>"NOUS RÉPONDONS",'es'=>"TE RESPONDEMOS"),
    'how_step3'=> array('it'=>"PROPOSTA SU MISURA",'en'=>"TAILORED PROPOSAL",'fr'=>"PROPOSITION SUR MESURE",'es'=>"PROPUESTA A MEDIDA"),
    'how_step4'=> array('it'=>"GESTIONE COMPLETA",'en'=>"FULL MANAGEMENT",'fr'=>"GESTION COMPLÈTE",'es'=>"GESTIÓN COMPLETA"),
    'how_tag'  => array('it'=>"Un solo referente • Un solo preventivo • Risposta rapida",'en'=>"One point of contact • One quote • Fast reply",'fr'=>"Un seul interlocuteur • Un seul devis • Réponse rapide",'es'=>"Un único interlocutor • Un único presupuesto • Respuesta rápida"),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'TOA EVENTS',
    'title'      => _t_raw($d['h1']),
    'subtitle'   => $_t($d['intro']),
)); ?>

<style>
.toa-hero-cta{display:inline-flex;align-items:center;gap:10px;padding:16px 38px;background:var(--accent,#c8ff00);color:#0a0a0a;font-size:.95rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;box-shadow:0 8px 30px rgba(200,255,0,.35),0 2px 8px rgba(0,0,0,.15);transition:transform .25s ease,box-shadow .25s ease}
.toa-hero-cta svg{transition:transform .25s ease}
.toa-hero-cta:hover{transform:translateY(-3px);box-shadow:0 14px 42px rgba(200,255,0,.55),0 4px 12px rgba(0,0,0,.2)}
.toa-hero-cta:hover svg{transform:translateX(4px)}
@media(max-width:600px){.toa-hero-cta{padding:16px 30px;font-size:.95rem}}
.hero-cta-wrap{margin-top:16px;margin-bottom:28px;text-align:center}
@media(max-width:768px){.hero-cta-wrap{margin-top:18px;margin-bottom:20px}}
.toa-ev-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:18px;margin-top:28px}
.toa-ev-card{border:1px solid rgba(150,150,150,.3);border-radius:14px;padding:20px;display:flex;flex-direction:row;align-items:center;gap:16px}
.toa-ev-card img{flex:0 0 84px;width:84px;height:112px;object-fit:cover;object-position:50% 18%;border-radius:10px;background:#111}
.toa-ev-card .tx{display:flex;flex-direction:column;gap:8px}
.toa-ev-card h3{font-family:var(--font-display);font-size:1.1rem;font-weight:800;text-transform:uppercase;margin:0}
.toa-ev-card p{margin:0;color:var(--gray-4);font-size:.95rem;line-height:1.6}
.toa-ev-note{text-align:left;margin:24px auto 0;max-width:720px;font-size:.88rem;color:var(--gray-4);border-left:2px solid var(--accent);padding-left:12px}
.toa-ev-links{display:flex;flex-wrap:wrap;gap:10px;margin-top:22px}
.toa-ev-links a{display:inline-block;padding:10px 18px;border:1px solid rgba(150,150,150,.35);border-radius:999px;font-size:.85rem;font-weight:700;text-decoration:none;color:inherit;transition:border-color .15s,color .15s}
.toa-ev-links a:hover{border-color:var(--accent);color:var(--accent)}
.toa-ev-links a.hub{border-color:var(--accent);color:var(--accent)}
.toa-mobile-cta{display:none}
@media(max-width:768px){
  .toa-mobile-cta{position:fixed;left:0;right:0;bottom:0;z-index:900;display:block;padding:10px 14px;padding-bottom:calc(10px + env(safe-area-inset-bottom));background:rgba(10,10,10,.92);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px);border-top:1px solid rgba(255,255,255,.08);transition:transform .3s ease}
  .toa-mobile-cta.hide{transform:translateY(130%)}
  .toa-mobile-cta a{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:15px 20px;background:var(--accent,#c8ff00);color:#0a0a0a;font-weight:700;font-size:.95rem;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;text-decoration:none}
}
</style>

<!-- Figure -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['r_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['r_heading']); ?></h2>
        <div class="toa-ev-grid">
            <?php foreach ($d['roles'] as $role): ?>
            <div class="toa-ev-card">
                <?php if ($role['p'] && file_exists(get_template_directory() . '/assets/staff/' . $role['p'] . '.jpg')): ?>
                <img src="/wp-content/themes/toagency-theme/assets/staff/<?php echo esc_attr($role['p']); ?>.jpg" alt="<?php echo esc_attr($_t($role['n'])); ?>" loading="lazy">
                <?php endif; ?>
                <div class="tx">
                    <h3><?php echo $_t($role['n']); ?></h3>
                    <p><?php echo $_t($role['d']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if (!empty($d['note'])): ?><p class="toa-ev-note"><?php echo $_t($d['note']); ?></p><?php endif; ?>
    </div>
</section>

<div class="container hero-cta-wrap">
    <a href="#preventivo" class="toa-hero-cta">
        <span><?php echo $_t($t['cta']); ?></span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.9rem;color:var(--gray-4);margin-top:16px;font-weight:600"><?php echo $_t($t['line']); ?></p>
</div>

<!-- Solo aziende: anti-candidature -->
<section style="padding:26px 0;background:var(--black);color:#fff;text-align:center">
    <div class="container">
        <p style="font-size:0.95rem;margin-bottom:10px;opacity:.85"><?php echo $_t($t['b2bonly']); ?></p>
        <a href="<?php echo home_url('/registrati-talent/'); ?>" style="display:inline-block;font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;color:var(--accent);text-decoration:none"><?php echo $_t($t['talentexit']); ?> &rarr;</a>
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

<!-- Altri servizi -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['o_eyebrow']); ?></div>
        <div class="toa-ev-links">
            <?php foreach ($names as $k => $nm): if ($k === $key) continue; $u = $url($k); if (!$u) continue; ?>
            <a href="<?php echo esc_url($u); ?>"><?php echo $_t($nm); ?></a>
            <?php endforeach; ?>
            <?php $hub = $url('hub'); if ($hub): ?><a class="hub" href="<?php echo esc_url($hub); ?>"><?php echo $_t($t['o_hub']); ?> &rarr;</a><?php endif; ?>
        </div>
    </div>
</section>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events - ' . $leadnames[$key])); ?>

<!-- Barra CTA fissa mobile -->
<div class="toa-mobile-cta" id="toaMobileCta">
    <a href="#preventivo"><?php echo $_t($t['cta']); ?>
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
