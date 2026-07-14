<?php
/**
 * Template Name: Actors
 * Rifatta 14/07/2026 sullo STAMPO /hostess-steward/ (coerenza estetica): hero + CTA centrato,
 * fascia cast stile Modelli, servizi a pillole, garanzie, how-it-works, google-reviews,
 * "In tutta Italia e in Europa", CTA casting, invito registrazione attori. IT/EN/FR/ES.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Per produzioni, registi e case di produzione. Oltre 3.000 attori professionisti con showreel aggiornati.<br>Protagonisti, character, comparse, figurazioni speciali.',
        'en' => 'For productions, directors and production companies. Over 3,000 professional actors with updated showreels.<br>Lead roles, character actors, extras, special appearances.',
        'fr' => 'Pour productions, r&eacute;alisateurs et soci&eacute;t&eacute;s de production. Plus de 3 000 acteurs professionnels avec showreels &agrave; jour.<br>R&ocirc;les principaux, seconds r&ocirc;les, figurants.',
        'es' => 'Para producciones, directores y productoras. M&aacute;s de 3.000 actores profesionales con showreels actualizados.<br>Protagonistas, actores de car&aacute;cter, figurantes.',
    ),
    'hero_cta' => array('it'=>'Richiedi un casting','en'=>'Request a casting','fr'=>'Demander un casting','es'=>'Solicita un casting'),
    'trust_line' => array(
        'it'=>'4,7&#9733; su Google &middot; 346 recensioni &middot; dal 2009 &middot; 20.000+ profili verificati',
        'en'=>'4.7&#9733; on Google &middot; 346 reviews &middot; since 2009 &middot; 20,000+ verified profiles',
        'fr'=>'4,7&#9733; sur Google &middot; 346 avis &middot; depuis 2009 &middot; 20 000+ profils v&eacute;rifi&eacute;s',
        'es'=>'4,7&#9733; en Google &middot; 346 rese&ntilde;as &middot; desde 2009 &middot; 20.000+ perfiles verificados',
    ),
    'serv_eyebrow' => array('it'=>'Produzioni','en'=>'Productions','fr'=>'Productions','es'=>'Producciones'),
    'serv_heading' => array('it'=>'Per ogni tipo di produzione','en'=>'For every type of production','fr'=>'Pour chaque type de production','es'=>'Para cada tipo de producci&oacute;n'),
    'feat_eyebrow' => array('it'=>'Il nostro cast','en'=>'Our cast','fr'=>'Notre casting','es'=>'Nuestro casting'),
    'feat_heading' => array('it'=>'Ogni ruolo, ogni produzione','en'=>'Every role, every production','fr'=>'Chaque r&ocirc;le, chaque production','es'=>'Cada papel, cada producci&oacute;n'),
    'feat1_title'=>array('it'=>'Database completo','en'=>'Complete database','fr'=>'Base compl&egrave;te','es'=>'Base completa'),
    'feat1_text' =>array('it'=>'3.000+ attori con showreel aggiornati e formazione teatrale e cinematografica.','en'=>'3,000+ actors with updated showreels and theatre and film training.','fr'=>'3 000+ acteurs avec showreels &agrave; jour.','es'=>'3.000+ actores con showreels actualizados.'),
    'feat2_title'=>array('it'=>'Tutti i ruoli','en'=>'All roles','fr'=>'Tous les r&ocirc;les','es'=>'Todos los papeles'),
    'feat2_text' =>array('it'=>'Protagonisti, character, comparse e figurazioni. Tutte le et&agrave; ed etnie.','en'=>'Leads, character actors, extras and appearances. All ages and ethnicities.','fr'=>'R&ocirc;les principaux, personnages, figurants. Tous &acirc;ges.','es'=>'Protagonistas, car&aacute;cter, figurantes. Todas las edades.'),
    'feat3_title'=>array('it'=>'Self-tape rapidi','en'=>'Fast self-tapes','fr'=>'Self-tapes rapides','es'=>'Self-tapes r&aacute;pidos'),
    'feat3_text' =>array('it'=>'Provini video professionali in 24-48 ore. Callback organizzati.','en'=>'Professional video auditions in 24-48 hours. Callbacks organized.','fr'=>'Auditions vid&eacute;o en 24-48 heures.','es'=>'Audiciones en v&iacute;deo en 24-48 horas.'),
    'feat4_title'=>array('it'=>'Gestione completa','en'=>'Full management','fr'=>'Gestion compl&egrave;te','es'=>'Gesti&oacute;n completa'),
    'feat4_text' =>array('it'=>'Contratti ENPALS, diritti immagine, assicurazioni e logistica.','en'=>'ENPALS contracts, image rights, insurance and logistics.','fr'=>'Contrats ENPALS, droits image, logistique.','es'=>'Contratos ENPALS, derechos de imagen, log&iacute;stica.'),
    'how_step1'=>array('it'=>'DESCRIVI IL RUOLO','en'=>'DESCRIBE THE ROLE','fr'=>'D&Eacute;CRIVEZ LE R&Ocirc;LE','es'=>'DESCRIBE EL PAPEL'),
    'how_step2'=>array('it'=>'TI RISPONDIAMO','en'=>'WE REPLY','fr'=>'NOUS R&Eacute;PONDONS','es'=>'TE RESPONDEMOS'),
    'how_step3'=>array('it'=>'HAI I SELF-TAPE','en'=>'GET THE SELF-TAPES','fr'=>'RECEVEZ LES SELF-TAPES','es'=>'RECIBE LOS SELF-TAPES'),
    'how_step4'=>array('it'=>'SUPPORTO CONTINUO','en'=>'ONGOING SUPPORT','fr'=>'SUPPORT CONTINU','es'=>'SOPORTE CONTINUO'),
    'how_tagline'=>array('it'=>'Self-tape in 24 ore &bull; Showreel professionali &bull; Contratti ENPALS','en'=>'Self-tapes in 24 hours &bull; Professional showreels &bull; ENPALS contracts','fr'=>'Self-tapes en 24 heures &bull; Showreels pro &bull; Contrats ENPALS','es'=>'Self-tapes en 24 horas &bull; Showreels pro &bull; Contratos ENPALS'),
    'cov_eyebrow'=>array('it'=>'Dove operiamo','en'=>'Where we operate','fr'=>'O&ugrave; nous intervenons','es'=>'D&oacute;nde operamos'),
    'cov_heading'=>array('it'=>'Casting in tutta Italia e in Europa','en'=>'Casting across Italy and Europe','fr'=>'Casting dans toute l\'Italie et en Europe','es'=>'Casting en toda Italia y en Europa'),
    'cov_sub'=>array('it'=>'Cinema, TV, pubblicit&agrave; e produzioni digitali &mdash; in tutte le principali citt&agrave; italiane e, su richiesta, in Francia, Spagna e UK.','en'=>'Film, TV, advertising and digital productions &mdash; in every major Italian city and, on request, in France, Spain and the UK.','fr'=>'Cin&eacute;ma, TV, publicit&eacute; et productions num&eacute;riques &mdash; dans toutes les grandes villes italiennes et sur demande en France, Espagne et UK.','es'=>'Cine, TV, publicidad y producciones digitales &mdash; en las principales ciudades italianas y, bajo petici&oacute;n, en Francia, Espa&ntilde;a y UK.'),
    'cta_browse' => array('it'=>'Sfoglia il database attori','en'=>'Browse the actors database','fr'=>'Parcourir la base d\'acteurs','es'=>'Explorar la base de actores'),
    'reg_line' => array('it'=>'Sei un attore o una comparsa? Entra nel nostro database','en'=>'Are you an actor or extra? Join our database','fr'=>'Vous &ecirc;tes acteur ou figurant ? Rejoignez notre base','es'=>'&iquest;Eres actor o figurante? &Uacute;nete a nuestra base'),
);

// Servizi attori a pillole
$servchips = array(
  array('it'=>'Cinema','en'=>'Cinema','fr'=>'Cin&eacute;ma','es'=>'Cine'),
  array('it'=>'Serie TV & Fiction','en'=>'TV series & Fiction','fr'=>'S&eacute;ries TV & Fiction','es'=>'Series TV & Ficci&oacute;n'),
  array('it'=>'Spot pubblicitari','en'=>'TV & web commercials','fr'=>'Spots publicitaires','es'=>'Spots publicitarios'),
  array('it'=>'Web series & streaming','en'=>'Web series & streaming','fr'=>'Web s&eacute;ries & streaming','es'=>'Web series & streaming'),
  array('it'=>'Teatro & musical','en'=>'Theatre & musicals','fr'=>'Th&eacute;&acirc;tre & com&eacute;dies musicales','es'=>'Teatro & musicales'),
  array('it'=>'Videoclip','en'=>'Music videos','fr'=>'Clips musicaux','es'=>'Videoclips'),
  array('it'=>'Documentari','en'=>'Documentaries','fr'=>'Documentaires','es'=>'Documentales'),
  array('it'=>'Video aziendali','en'=>'Corporate videos','fr'=>'Vid&eacute;os d\'entreprise','es'=>'V&iacute;deos corporativos'),
);

// Fascia cast di ESEMPIO (nomi fittizi + tipo ruolo, illustrativi)
$cast = array(
  array('img'=>'/wp-content/uploads/2025/09/22-yo.jpeg','name'=>'Sofia','role'=>array('it'=>'Attrice','en'=>'Actress','fr'=>'Actrice','es'=>'Actriz')),
  array('img'=>'/wp-content/uploads/2025/09/35-yo-african.jpeg','name'=>'David','role'=>array('it'=>'Protagonista','en'=>'Lead','fr'=>'Premier r&ocirc;le','es'=>'Protagonista')),
  array('img'=>'/wp-content/uploads/2025/09/30-yo-punk.jpeg','name'=>'Marco','role'=>array('it'=>'Caratterista','en'=>'Character actor','fr'=>'Second r&ocirc;le','es'=>'Car&aacute;cter')),
  array('img'=>'/wp-content/uploads/2025/09/9-yo-asian.jpeg','name'=>'Mei','role'=>array('it'=>'Bambina','en'=>'Child actress','fr'=>'Enfant','es'=>'Ni&ntilde;a')),
  array('img'=>'/wp-content/uploads/2025/09/40-yo-mediterranean.jpeg','name'=>'Antonio','role'=>array('it'=>'Protagonista','en'=>'Lead','fr'=>'Premier r&ocirc;le','es'=>'Protagonista')),
  array('img'=>'/wp-content/uploads/2025/09/30-yo-girl.jpeg','name'=>'Elena','role'=>array('it'=>'Attrice','en'=>'Actress','fr'=>'Actrice','es'=>'Actriz')),
  array('img'=>'/wp-content/uploads/2025/09/70-yo-asian.jpg','name'=>'Kenji','role'=>array('it'=>'Senior','en'=>'Senior','fr'=>'Senior','es'=>'Senior')),
  array('img'=>'/wp-content/uploads/2025/09/24-yo.jpeg','name'=>'Luca','role'=>array('it'=>'Attore','en'=>'Actor','fr'=>'Acteur','es'=>'Actor')),
  array('img'=>'/wp-content/uploads/2025/09/8-yo.jpeg','name'=>'Tommaso','role'=>array('it'=>'Bambino','en'=>'Child actor','fr'=>'Enfant','es'=>'Ni&ntilde;o')),
  array('img'=>'/wp-content/uploads/2025/09/58-yo.jpeg','name'=>'Giorgio','role'=>array('it'=>'Senior','en'=>'Senior','fr'=>'Senior','es'=>'Senior')),
  array('img'=>'/wp-content/uploads/2025/09/24-yosize.jpg','name'=>'Sara','role'=>array('it'=>'Attrice','en'=>'Actress','fr'=>'Actrice','es'=>'Actriz')),
  array('img'=>'/wp-content/uploads/2025/09/45-yo.jpeg','name'=>'Paolo','role'=>array('it'=>'Caratterista','en'=>'Character actor','fr'=>'Second r&ocirc;le','es'=>'Car&aacute;cter')),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'ATTORI & COMPARSE',
    'title'      => _t_raw(array('it'=>'Attori & Comparse.','en'=>'Actors & Extras.','fr'=>'Acteurs & Figurants.','es'=>'Actores & Figurantes.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Hero CTA CENTRATO + trust line -->
<div class="container" style="margin-top:-8px;margin-bottom:28px;text-align:center">
    <a href="<?php echo home_url('/form-b2b/'); ?>" class="btn-hero btn-hero-primary" style="display:inline-flex;align-items:center;gap:8px">
        <span><?php echo $_t($t['hero_cta']); ?></span>
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
    </a>
    <p style="font-size:0.85rem;color:var(--gray-4);margin-top:14px;font-weight:600"><?php echo $_t($t['trust_line']); ?></p>
</div>

<!-- Fascia cast di esempio — IDENTICA al blocco Modelli/Eventi -->
<style>
.toa-staffband{background:#0a0a0a;padding:48px 20px 40px}
.toa-staffband .inner{max-width:1080px;margin:0 auto}
.toa-staffband .toa-cast-hd{text-align:center;margin:0 0 22px}
.toa-staffband .toa-cast-hd h2{font-family:var(--font-display,Georgia,serif);font-size:clamp(20px,3vw,28px);font-weight:900;color:#fff;margin:0 0 6px;text-transform:uppercase;letter-spacing:.5px}
.toa-staffband .toa-cast-hd p{font-size:12px;color:rgba(255,255,255,.45);margin:0;text-transform:uppercase;letter-spacing:1px}
.toa-staffband .toa-cast-hd .desc{text-transform:none;font-size:13px;font-weight:400;color:rgba(255,255,255,.6);max-width:620px;margin:12px auto 0;line-height:1.5;letter-spacing:0}
.toa-staffband .toa-cast-grid{display:grid;grid-template-columns:repeat(6,1fr);gap:14px}
.toa-staffband .toa-cast-card{position:relative;border-radius:14px;overflow:hidden;aspect-ratio:3/4;background:#141414;border:1px solid rgba(255,255,255,.06)}
.toa-staffband .toa-cast-card img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .45s cubic-bezier(.2,.8,.3,1)}
.toa-staffband .toa-cast-card:hover img{transform:scale(1.05)}
.toa-staffband .toa-cast-card .ov{position:absolute;left:0;right:0;bottom:0;padding:14px 12px 11px;background:linear-gradient(transparent,rgba(0,0,0,.88));color:#fff}
.toa-staffband .toa-cast-card .ov b{display:block;font-size:13px;font-weight:700;letter-spacing:.4px}
.toa-staffband .toa-cast-card .ov .code{display:block;font-size:11px;font-weight:600;color:#c8ff00;letter-spacing:.5px;margin-top:1px}
.toa-staffband .toa-cast-cta{text-align:center;margin:26px 0 6px}
.toa-staffband .toa-cast-cta a{display:inline-block;margin:6px 5px;padding:14px 30px;background:#c8ff00;color:#000;border:1px solid #c8ff00;border-radius:8px;font-weight:700;font-size:14px;letter-spacing:.04em;text-decoration:none;transition:opacity .2s,background .2s}
.toa-staffband .toa-cast-cta a:hover{opacity:.85}
.toa-staffband .toa-cast-cta a.alt{background:transparent;color:#c8ff00}
.toa-staffband .toa-cast-cta a.alt:hover{background:rgba(200,255,0,.12);opacity:1}
.toa-staffband .toa-cast-note{text-align:center;font-size:11px;color:rgba(255,255,255,.4);margin:10px 0 0}
@media(max-width:768px){.toa-staffband .toa-cast-grid{grid-template-columns:repeat(3,1fr);gap:12px}}
@media(max-width:480px){.toa-staffband .toa-cast-grid{grid-template-columns:repeat(3,1fr);gap:8px}}
.toa-serv-cat{font-family:var(--font-display);font-size:.8rem;font-weight:800;text-transform:uppercase;letter-spacing:1.5px;margin:22px 0 10px;color:var(--accent)}
.toa-serv-chips{display:flex;flex-wrap:wrap;gap:8px;margin:0 0 6px}
.toa-serv-chips a{display:inline-block;padding:9px 16px;border:1px solid rgba(150,150,150,.35);border-radius:22px;font-size:.85rem;font-weight:600;color:inherit;text-decoration:none;transition:border-color .2s,color .2s,background .2s}
.toa-serv-chips a:hover{border-color:var(--accent);color:var(--accent);background:rgba(200,255,0,.06)}
</style>
<section class="toa-staffband">
    <div class="inner">
        <div class="toa-cast-hd">
            <h2><?php echo $_t(array('it'=>'Alcuni dei nostri attori','en'=>'Some of our actors','fr'=>'Quelques-uns de nos acteurs','es'=>'Algunos de nuestros actores')); ?></h2>
            <p><?php echo $_t(array('it'=>'Cast selezionato &middot; 3.000+ profili','en'=>'Selected cast &middot; 3,000+ profiles','fr'=>'Casting s&eacute;lectionn&eacute; &middot; 3 000+ profils','es'=>'Casting seleccionado &middot; 3.000+ perfiles')); ?></p>
            <p class="desc"><?php echo $_t(array('it'=>'Protagonisti, caratteristi, comparse, bambini e senior: il volto giusto per ogni ruolo, con self-tape in 24 ore.','en'=>'Leads, character actors, extras, children and seniors: the right face for every role, with self-tapes in 24 hours.','fr'=>'R&ocirc;les principaux, personnages, figurants, enfants et seniors : le bon visage pour chaque r&ocirc;le.','es'=>'Protagonistas, car&aacute;cter, figurantes, ni&ntilde;os y seniors: el rostro adecuado para cada papel.')); ?></p>
        </div>
        <div class="toa-cast-grid">
            <?php foreach ($cast as $p): ?>
            <div class="toa-cast-card">
                <img src="<?php echo esc_attr($p['img']); ?>" alt="<?php echo esc_attr($p['name'].' — '.$_t($p['role'])); ?>" loading="lazy">
                <div class="ov"><b><?php echo esc_html($p['name']); ?></b><span class="code"><?php echo $_t($p['role']); ?></span></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="toa-cast-cta">
            <a href="<?php echo home_url('/form-b2b/'); ?>"><?php echo $_t($t['hero_cta']); ?> &rarr;</a>
            <a href="https://toagency.it/talent-database/" class="alt" target="_blank" rel="noopener"><?php echo $_t($t['cta_browse']); ?></a>
        </div>
        <p class="toa-cast-note"><?php echo $_t(array('it'=>'Profili illustrativi &mdash; il cast reale viene selezionato per la tua produzione.','en'=>'Illustrative profiles &mdash; the real cast is selected for your production.','fr'=>'Profils illustratifs &mdash; le casting r&eacute;el est s&eacute;lectionn&eacute; pour votre production.','es'=>'Perfiles ilustrativos &mdash; el casting real se selecciona para tu producci&oacute;n.')); ?></p>
    </div>
</section>

<!-- Servizi produzioni a pillole -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['serv_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['serv_heading']); ?></h2>
        <div class="toa-serv-chips" style="margin-top:18px">
            <?php foreach ($servchips as $c): ?>
            <a href="<?php echo home_url('/form-b2b/'); ?>"><?php echo $_t($c); ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Perché TOAgency (cast) -->
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
        array('time' => "1'", 'label' => $_t($t['how_step1'])),
        array('time' => 'SUBITO', 'label' => $_t($t['how_step2'])),
        array('time' => '24h', 'label' => $_t($t['how_step3'])),
        array('time' => 'H24', 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<!-- Recensioni Google -->
<?php toa_component('google-reviews'); ?>

<!-- Dove operiamo -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['cov_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:12px"><?php echo $_t($t['cov_heading']); ?></h2>
        <p style="font-size:0.95rem;color:var(--gray-4);max-width:640px;margin:0 0 36px"><?php echo $_t($t['cov_sub']); ?></p>
    </div>
    <div class="coverage-grid container">
        <div class="coverage-country"><h4>Roma</h4><p>Cinecitt&agrave;, produzioni cinema e fiction</p></div>
        <div class="coverage-country"><h4>Milano</h4><p>Spot pubblicitari, web, corporate</p></div>
        <div class="coverage-country"><h4>Torino</h4><p>Film Commission, cinema e serie</p></div>
        <div class="coverage-country"><h4>Napoli</h4><p>Fiction, cinema e teatro</p></div>
        <div class="coverage-country"><h4>Bologna</h4><p>Pubblicit&agrave; e produzioni digitali</p></div>
        <div class="coverage-country"><h4>Europa</h4><p>Francia, Spagna, UK su richiesta</p></div>
    </div>
</section>

<!-- CTA casting finale -->
<section style="padding:60px 0;text-align:center;background:var(--accent);color:var(--black)">
    <div class="container">
        <h2 style="font-family:var(--font-display);font-size:2rem;font-weight:900;margin-bottom:8px"><?php echo $_t(array('it'=>'Hai una produzione in arrivo?','en'=>'Got a production coming up?','fr'=>'Une production &agrave; venir ?','es'=>'&iquest;Tienes una producci&oacute;n?')); ?></h2>
        <p style="font-size:1rem;margin-bottom:24px;opacity:0.8"><?php echo $_t(array('it'=>'Raccontaci il ruolo e ricevi i self-tape su misura.','en'=>'Tell us the role and get tailored self-tapes.','fr'=>'D&eacute;crivez le r&ocirc;le et recevez des self-tapes sur mesure.','es'=>'Cu&eacute;ntanos el papel y recibe self-tapes a medida.')); ?></p>
        <a href="<?php echo home_url('/form-b2b/'); ?>" style="display:inline-block;padding:14px 32px;background:var(--black);color:var(--accent);font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem"><?php echo $_t($t['hero_cta']); ?> &rarr;</a>
    </div>
</section>

<!-- Invito registrazione attori -->
<section style="padding:40px 0;background:var(--black);color:#fff;text-align:center">
    <div class="container">
        <a href="<?php echo home_url('/registrati-talent/'); ?>" style="display:inline-block;font-weight:800;text-transform:uppercase;letter-spacing:1px;font-size:0.85rem;color:var(--accent);text-decoration:none"><?php echo $_t($t['reg_line']); ?> &rarr;</a>
    </div>
</section>

<?php toa_component('footer'); ?>
