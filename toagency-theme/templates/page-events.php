<?php
/**
 * Template Name: TOA Events
 * Divisione eventi di TOAgency (ticket #637, 14/10/2026) — IT/EN/FR/ES.
 * Servizi: Event Staff, Fiere, Foto e video, Crew tecnica, Logistica (solo via partner con licenza autotrasporto c/terzi).
 * Vincoli: nessun prezzo; nessuna promessa di numeri/copertura (interpreti, tour leader: 0 profili);
 * security e mansioni tecniche solo con personale in regola. Stampo: page-hostess-eventi20.php.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
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
    'hero_cta' => array('it'=>"Richiedi un preventivo",'en'=>"Request a quote",'fr'=>"Demandez un devis",'es'=>"Solicita un presupuesto"),
    'hero_line' => array(
        'it' => "Raccontaci cosa ti serve per l'evento: ce ne occupiamo noi.",
        'en' => "Tell us what your event needs: we'll take care of it.",
        'fr' => "Dites-nous ce dont votre événement a besoin : nous nous en occupons.",
        'es' => "Cuéntanos qué necesita tu evento: nos encargamos nosotros.",
    ),
    'b2bonly' => array('it'=>"Servizio per aziende, agenzie e produzioni — non per candidature.",'en'=>"A service for companies, agencies and productions — not for job applications.",'fr'=>"Un service pour entreprises, agences et productions — pas pour les candidatures.",'es'=>"Un servicio para empresas, agencias y producciones — no para candidaturas."),
    'talentexit' => array('it'=>"Sei un talent in cerca di lavoro? Registrati qui",'en'=>"Are you a talent looking for work? Register here",'fr'=>"Vous êtes un talent à la recherche de travail ? Inscrivez-vous ici",'es'=>"¿Eres un talento que busca trabajo? Regístrate aquí"),
    'serv_eyebrow' => array('it'=>"Servizi",'en'=>"Services",'fr'=>"Services",'es'=>"Servicios"),
    'serv_heading' => array('it'=>"Tutto quello che serve al tuo evento",'en'=>"Everything your event needs",'fr'=>"Tout ce dont votre événement a besoin",'es'=>"Todo lo que necesita tu evento"),
    'serv_cta' => array('it'=>"Chiedi un preventivo",'en'=>"Ask for a quote",'fr'=>"Demander un devis",'es'=>"Pedir presupuesto"),
    'serv_foot' => array(
        'it' => "Interpreti su richiesta. Security e mansioni tecniche solo con personale in regola con i requisiti di legge. Il trasporto di merci è eseguito solo da partner con licenza di autotrasporto per conto terzi. Foto illustrative.",
        'en' => "Interpreters on request. Security and technical roles only with staff who meet the legal requirements. Goods are transported only by partners holding a for-hire road haulage licence. Illustrative photos.",
        'fr' => "Interprètes sur demande. Sécurité et missions techniques uniquement avec du personnel en règle avec les exigences légales. Le transport de marchandises est assuré uniquement par des partenaires titulaires d'une licence de transport pour compte d'autrui. Photos illustratives.",
        'es' => "Intérpretes bajo petición. Seguridad y tareas técnicas solo con personal que cumple los requisitos legales. El transporte de mercancías lo realizan únicamente partners con licencia de transporte por cuenta ajena. Fotos ilustrativas.",
    ),
    'serv_more' => array('it'=>"Scopri le figure",'en'=>"See the roles",'fr'=>"Voir les profils",'es'=>"Ver los perfiles"),
    'serv_other' => array(
        'it' => "Non trovi quello che cerchi? Scrivici: ti diciamo come possiamo aiutarti.",
        'en' => "Can't see what you need? Write to us and we'll tell you how we can help.",
        'fr' => "Vous ne trouvez pas ce qu'il vous faut ? Écrivez-nous : nous vous dirons comment vous aider.",
        'es' => "¿No ves lo que necesitas? Escríbenos y te diremos cómo podemos ayudarte.",
    ),
    'feat_eyebrow' => array('it'=>"Perché TOA Events",'en'=>"Why TOA Events",'fr'=>"Pourquoi TOA Events",'es'=>"Por qué TOA Events"),
    'feat_heading' => array('it'=>"Come lavoriamo",'en'=>"How we work",'fr'=>"Notre façon de travailler",'es'=>"Cómo trabajamos"),
    'feat1_title' => array('it'=>"Un solo referente",'en'=>"One point of contact",'fr'=>"Un seul interlocuteur",'es'=>"Un único interlocutor"),
    'feat1_text'  => array('it'=>"Una persona coordina staff, crew e produzione.",'en'=>"One person coordinates staff, crew and production.",'fr'=>"Une seule personne coordonne le personnel, les équipes et la production.",'es'=>"Una sola persona coordina personal, equipos y producción."),
    'feat2_title' => array('it'=>"Un solo preventivo",'en'=>"One quote",'fr'=>"Un seul devis",'es'=>"Un único presupuesto"),
    'feat2_text'  => array('it'=>"Tutti i servizi dell'evento in un'unica proposta.",'en'=>"All your event services in a single proposal.",'fr'=>"Tous les services de l'événement dans une seule proposition.",'es'=>"Todos los servicios del evento en una sola propuesta."),
    'feat3_title' => array('it'=>"Persone selezionate",'en'=>"Selected people",'fr'=>"Des profils sélectionnés",'es'=>"Perfiles seleccionados"),
    'feat3_text'  => array('it'=>"Profili scelti dal database TOAgency in base a ciò che serve al tuo evento.",'en'=>"Profiles chosen from the TOAgency database to match what your event needs.",'fr'=>"Des profils choisis dans la base TOAgency selon les besoins de votre événement.",'es'=>"Perfiles elegidos de la base de datos de TOAgency según lo que necesita tu evento."),
    'feat4_title' => array('it'=>"Risposta rapida",'en'=>"Fast reply",'fr'=>"Réponse rapide",'es'=>"Respuesta rápida"),
    'feat4_text'  => array('it'=>"Ti ricontattiamo subito.",'en'=>"We get back to you right away.",'fr'=>"Nous vous recontactons tout de suite.",'es'=>"Te contactamos enseguida."),
    'how_step1' => array('it'=>"CI SCRIVI",'en'=>"CONTACT US",'fr'=>"CONTACTEZ-NOUS",'es'=>"CONTÁCTANOS"),
    'how_step2' => array('it'=>"TI RISPONDIAMO",'en'=>"WE REPLY",'fr'=>"NOUS RÉPONDONS",'es'=>"TE RESPONDEMOS"),
    'how_step3' => array('it'=>"PROPOSTA SU MISURA",'en'=>"TAILORED PROPOSAL",'fr'=>"PROPOSITION SUR MESURE",'es'=>"PROPUESTA A MEDIDA"),
    'how_step4' => array('it'=>"GESTIONE COMPLETA",'en'=>"FULL MANAGEMENT",'fr'=>"GESTION COMPLÈTE",'es'=>"GESTIÓN COMPLETA"),
    'how_tagline' => array('it'=>"Un solo referente • Un solo preventivo • Risposta rapida",'en'=>"One point of contact • One quote • Fast reply",'fr'=>"Un seul interlocuteur • Un seul devis • Réponse rapide",'es'=>"Un único interlocutor • Un único presupuesto • Respuesta rápida"),
    'cov_eyebrow' => array('it'=>"Dove operiamo",'en'=>"Where we operate",'fr'=>"Où nous intervenons",'es'=>"Dónde operamos"),
    'cov_heading' => array('it'=>"Milano, Roma, Costa Azzurra, Madrid, Barcellona, Londra…",'en'=>"Milan, Rome, French Riviera, Madrid, Barcelona, London…",'fr'=>"Milan, Rome, Côte d'Azur, Madrid, Barcelone, Londres…",'es'=>"Milán, Roma, Costa Azul, Madrid, Barcelona, Londres…"),
    'cov_sub' => array(
        'it' => "E in ogni altra città: lavoriamo ovunque serva, anche dove non l'abbiamo scritto.",
        'en' => "And every other city: we work wherever you need us, even where it isn't listed.",
        'fr' => "Et dans toutes les autres villes : nous intervenons partout où vous en avez besoin, même si elle n'est pas citée.",
        'es' => "Y en cualquier otra ciudad: trabajamos donde nos necesites, aunque no esté en la lista.",
    ),
);

// Dati servizi e figure: components/events-data.php (unica fonte, usata anche dalle sottopagine)
$data     = include get_template_directory() . '/components/events-data.php';
$ev_pages = get_option('toa_events_pages', array());
$ev_url   = function($k) use ($ev_pages, $lang) {
    return (isset($ev_pages[$k][$lang]) && get_post_status((int) $ev_pages[$k][$lang]) === 'publish') ? get_permalink((int) $ev_pages[$k][$lang]) : ''; // solo pagine pubblicate
};
$ev_dir = get_template_directory() . '/assets/staff/';
$ev_i = 0; // indice foto nella rotazione

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'TOA EVENTS',
    'title'      => _t_raw($t['hero_title']),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<style>
.toa-hero-cta{display:inline-flex;align-items:center;gap:10px;padding:16px 38px;background:var(--accent,#c8ff00);color:#0a0a0a;font-size:.95rem;font-weight:700;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;box-shadow:0 8px 30px rgba(200,255,0,.35),0 2px 8px rgba(0,0,0,.15);transition:transform .25s ease,box-shadow .25s ease}
.toa-hero-cta svg{transition:transform .25s ease}
.toa-hero-cta:hover{transform:translateY(-3px);box-shadow:0 14px 42px rgba(200,255,0,.55),0 4px 12px rgba(0,0,0,.2)}
.toa-hero-cta:hover svg{transform:translateX(4px)}
@media(max-width:600px){.toa-hero-cta{padding:16px 30px;font-size:.95rem}}
.hero-cta-wrap{margin-top:16px;margin-bottom:28px;text-align:center}
@media(max-width:768px){.hero-cta-wrap{margin-top:18px;margin-bottom:20px}}
/* Figure: foto che scorrono + elenco completo (#637) */
.toa-ev-show{padding:6px 0 4px}
.toa-ev-wrap{display:grid;grid-template-columns:minmax(0,340px) 1fr;gap:30px;align-items:start}
.toa-ev-wrap.nostage{grid-template-columns:1fr}
.toa-ev-stage{position:relative;aspect-ratio:3/4;border-radius:16px;overflow:hidden;background:#111;border:1px solid rgba(255,255,255,.08)}
.toa-ev-stage img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:50% 18%;opacity:0;transition:opacity .35s ease}
.toa-ev-stage img.on{opacity:1}
.toa-ev-stage:after{content:"";position:absolute;left:0;right:0;bottom:0;height:40%;background:linear-gradient(transparent,rgba(0,0,0,.85));pointer-events:none}
.toa-ev-cap{position:absolute;left:16px;right:16px;bottom:18px;z-index:2;color:#fff}
.toa-ev-cap small{display:block;color:var(--accent,#c8ff00);font-size:.7rem;letter-spacing:.14em;text-transform:uppercase;font-weight:700}
.toa-ev-cap b{display:block;font-family:var(--font-display);font-size:1.6rem;font-weight:800;line-height:1.1}
.toa-ev-grp{margin-bottom:20px}
.toa-ev-grp h3{display:flex;justify-content:space-between;align-items:baseline;gap:12px;margin:0 0 9px;font-family:var(--font-display);font-size:.8rem;font-weight:800;letter-spacing:.14em;text-transform:uppercase;color:var(--gray-4)}
.toa-ev-grp h3 a{color:var(--accent,#c8ff00);text-decoration:none;font-size:.72rem;white-space:nowrap}
.toa-ev-chips{display:flex;flex-wrap:wrap;gap:7px}
.toa-ev-chip{display:inline-block;padding:7px 13px;border:1px solid rgba(150,150,150,.35);border-radius:999px;font:inherit;font-size:.85rem;font-weight:600;color:inherit;background:transparent;transition:background .2s,color .2s,transform .2s,box-shadow .2s}
[data-i].toa-ev-chip{cursor:pointer}
.toa-ev-chip{line-height:1.25;text-transform:none;letter-spacing:0}
[data-i].toa-ev-chip:focus-visible{outline:2px solid var(--accent,#c8ff00);outline-offset:2px}
.toa-ev-chip.on{background:var(--accent,#c8ff00);color:#0a0a0a;border-color:var(--accent,#c8ff00);transform:scale(1.06);box-shadow:0 0 20px rgba(200,255,0,.4)}
.toa-ev-foot{margin:6px 0 0;font-size:.78rem;color:var(--gray-4);border-left:2px solid var(--accent);padding-left:10px;line-height:1.5}
@media(max-width:760px){.toa-ev-wrap{grid-template-columns:1fr;gap:20px}.toa-ev-stage{aspect-ratio:4/5;max-width:420px;width:100%;margin:0 auto}}
/* Garanzie compatte */
.features-grid .feature-card{padding:18px 26px!important}
.features-grid .feature-number{font-size:1.55rem!important;line-height:1!important;margin-bottom:6px!important}
.features-grid .feature-title{margin-bottom:4px!important}
.features-grid .feature-text{margin:0!important}
@media(max-width:600px){.features-grid .feature-card{padding:16px 20px!important}}
/* Barra CTA fissa solo su mobile */
.toa-mobile-cta{display:none}
@media(max-width:768px){
  .toa-mobile-cta{position:fixed;left:0;right:0;bottom:0;z-index:900;display:block;padding:10px 14px;padding-bottom:calc(10px + env(safe-area-inset-bottom));background:rgba(10,10,10,.92);-webkit-backdrop-filter:blur(8px);backdrop-filter:blur(8px);border-top:1px solid rgba(255,255,255,.08);transition:transform .3s ease}
  .toa-mobile-cta.hide{transform:translateY(130%)}
  .toa-mobile-cta a{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:15px 20px;background:var(--accent,#c8ff00);color:#0a0a0a;font-weight:700;font-size:.95rem;letter-spacing:.5px;text-transform:uppercase;border-radius:999px;text-decoration:none}
}
/* Gallery */
.toa-gallery{background:#0a0a0a;padding:40px 20px 48px}
.toa-gallery .inner{max-width:1080px;margin:0 auto}
.toa-gallery .lab{text-align:center;font-size:12px;letter-spacing:3px;text-transform:uppercase;color:#777;margin-bottom:20px}
.toa-gallery .grid{display:flex;flex-wrap:wrap;justify-content:center;gap:6px}
.toa-gallery .grid img{width:calc(10% - 6px);aspect-ratio:1;object-fit:cover;border-radius:6px;background:#161616;opacity:.7;transition:opacity .2s}
.toa-gallery .grid img:hover{opacity:1}
@media(max-width:768px){.toa-gallery .grid{gap:5px}.toa-gallery .grid img{width:calc(20% - 5px)}}
</style>

<!-- Figure: foto che scorrono + elenco completo (#637) -->
<section class="toa-ev-show" id="servizi">
    <div class="container">
        <?php
        // foto presenti su disco, nell'ordine dei servizi
        $ev_has = false;
        foreach ($data as $sv) { foreach ($sv['roles'] as $rl) { if ($rl['p'] && file_exists($ev_dir . $rl['p'] . '.jpg')) { $ev_has = true; break 2; } } }
        ?>
        <div class="toa-ev-wrap<?php echo $ev_has ? '' : ' nostage'; ?>">
            <?php if ($ev_has): ?>
            <div class="toa-ev-stage" id="evStage">
                <?php foreach ($data as $sv): foreach ($sv['roles'] as $rl):
                    if (!$rl['p'] || !file_exists($ev_dir . $rl['p'] . '.jpg')) continue; ?>
                <img src="/wp-content/themes/toagency-theme/assets/staff/<?php echo esc_attr($rl['p']); ?>.jpg" alt="<?php echo esc_attr($_t($rl['n'])); ?>" data-role="<?php echo esc_attr($_t($rl['n'])); ?>" data-grp="<?php echo esc_attr($_t($sv['title'])); ?>"<?php echo $ev_i ? ' loading="lazy"' : ''; ?>>
                <?php $ev_i++; endforeach; endforeach; ?>
                <div class="toa-ev-cap"><small></small><b></b></div>
            </div>
            <?php endif; ?>
            <div class="toa-ev-list">
                <?php $ev_n = 0; foreach ($data as $k => $sv): $su = $ev_url($k); ?>
                <div class="toa-ev-grp">
                    <h3><span><?php echo $_t($sv['title']); ?></span><?php if ($su): ?><a href="<?php echo esc_url($su); ?>"><?php echo $_t($t['serv_more']); ?> &rarr;</a><?php endif; ?></h3>
                    <div class="toa-ev-chips">
                        <?php foreach ($sv['roles'] as $rl):
                            if ($rl['p'] && file_exists($ev_dir . $rl['p'] . '.jpg')): ?>
                        <span class="toa-ev-chip" role="button" tabindex="0" data-i="<?php echo $ev_n++; ?>"><?php echo $_t($rl['n']); ?></span>
                        <?php else: ?>
                        <span class="toa-ev-chip"><?php echo $_t($rl['n']); ?></span>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <p class="toa-ev-foot"><?php echo $_t($t['serv_foot']); ?></p>
            </div>
        </div>
    </div>
</section>
<?php if ($ev_has): ?>
<script>
(function(){
  var st=document.getElementById('evStage'); if(!st) return;
  var imgs=st.querySelectorAll('img'), chips=document.querySelectorAll('.toa-ev-chip[data-i]');
  var cs=st.querySelector('.toa-ev-cap small'), cb=st.querySelector('.toa-ev-cap b'), n=imgs.length, cur=-1, pos=0, timer=null, paused=false, visible=true, order=[];
  if(!n) return;
  for(var o=0; o<n; o++) order.push(o);
  for(var k=n-1; k>0; k--){ var j=Math.floor(Math.random()*(k+1)), t=order[k]; order[k]=order[j]; order[j]=t; } // ordine casuale a ogni visita
  function go(i, manual){
    if(cur>=0){ imgs[cur].classList.remove('on'); if(chips[cur]) chips[cur].classList.remove('on'); }
    cur=i; pos=order.indexOf(i); imgs[i].classList.add('on'); if(chips[i]) chips[i].classList.add('on');
    cs.textContent=imgs[i].getAttribute('data-grp'); cb.textContent=imgs[i].getAttribute('data-role');
    var nx=imgs[order[(pos+1)%n]]; if(nx.loading==='lazy') nx.loading='eager'; // precarica la prossima: niente flash vuoto
    if(manual){ start(); }
  }
  function tick(){ if(!paused && visible) go(order[(pos+1)%n]); }
  function start(){ clearInterval(timer); if(!window.matchMedia('(prefers-reduced-motion: reduce)').matches) timer=setInterval(tick,1800); }
  for(var c=0; c<chips.length; c++){ (function(i){ var pick=function(){ go(i,true); if(window.innerWidth<=760 && st.scrollIntoView) st.scrollIntoView({behavior:'smooth',block:'center'}); }; chips[i].addEventListener('click',pick); chips[i].addEventListener('keydown',function(e){ if(e.key==='Enter'||e.key===' '){ e.preventDefault(); pick(); } }); })(c); }
  st.addEventListener('mouseenter',function(){paused=true;}); st.addEventListener('mouseleave',function(){paused=false;});
  if('IntersectionObserver' in window){ new IntersectionObserver(function(e){ visible=e[0].isIntersecting; },{threshold:0.1}).observe(st); }
  go(order[0]); start();
})();
</script>
<?php endif; ?>

<!-- Hero CTA centrato -->
<div class="container hero-cta-wrap">
    <a href="#preventivo" class="toa-hero-cta">
        <span><?php echo $_t($t['hero_cta']); ?></span>
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.9rem;color:var(--gray-4);margin-top:16px;font-weight:600"><?php echo $_t($t['hero_line']); ?></p>
</div>

<!-- Solo aziende: anti-candidature -->
<section style="padding:26px 0;background:var(--black);color:#fff;text-align:center">
    <div class="container">
        <p style="font-size:0.95rem;margin-bottom:10px;opacity:.85"><?php echo $_t($t['b2bonly']); ?></p>
        <a href="<?php echo home_url('/registrati-talent/'); ?>" style="display:inline-block;font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;color:var(--accent);text-decoration:none"><?php echo $_t($t['talentexit']); ?> &rarr;</a>
    </div>
</section>

<!-- Come lavoriamo -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['feat_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['feat_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card"><div class="feature-number">01</div><h3 class="feature-title"><?php echo $_t($t['feat1_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat1_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">02</div><h3 class="feature-title"><?php echo $_t($t['feat2_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat2_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">03</div><h3 class="feature-title"><?php echo $_t($t['feat3_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat3_text']); ?></p></div>
        <div class="feature-card"><div class="feature-number">04</div><h3 class="feature-title"><?php echo $_t($t['feat4_title']); ?></h3><p class="feature-text"><?php echo $_t($t['feat4_text']); ?></p></div>
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
    'tagline' => $_t($t['how_tagline']),
)); ?>

<?php toa_component('google-reviews'); ?>

<!-- Dove operiamo -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['cov_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:12px"><?php echo $_t($t['cov_heading']); ?></h2>
        <p style="font-size:0.95rem;color:var(--gray-4);max-width:640px;margin:0 0 20px"><?php echo $_t($t['cov_sub']); ?></p>
    </div>
</section>

<?php toa_component('form-eventi-inline', array('lang' => $lang, 'leadsrc' => 'Pagina TOA Events')); ?>

<!-- Gallery (foto eventi, discreta) -->
<section class="toa-gallery"><div class="inner">
    <div class="lab">Gallery</div>
    <div class="grid">
        <?php for($gi=1;$gi<=37;$gi++): $gn=sprintf('g%02d',$gi); ?>
        <img src="/wp-content/themes/toagency-theme/assets/gallery/<?php echo $gn; ?>.jpg" alt="TOA Events" loading="lazy">
        <?php endfor; ?>
    </div>
</div></section>

<!-- Barra CTA fissa mobile -->
<div class="toa-mobile-cta" id="toaMobileCta">
    <a href="#preventivo"><?php echo $_t($t['hero_cta']); ?>
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
