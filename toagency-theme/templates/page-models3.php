<?php
/**
 * Template Name: Models
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Per aziende, brand e produzioni. Oltre 5.000 modelli e modelle attivi in Italia, Francia, Spagna e UK.<br>Da 6 a 80 anni, per ogni tipo di campagna. Disponibili entro 48 ore.',
        'en' => 'For companies, brands and productions. Over 5,000 active models in Italy, France, Spain and UK.<br>From 6 to 80 years old, for every type of campaign. Available within 48 hours.',
        'fr' => 'Pour entreprises, marques et productions. Plus de 5 000 mannequins actifs en Italie, France, Espagne et UK.<br>De 6 &agrave; 80 ans, pour tout type de campagne. Disponibles sous 48 heures.',
        'es' => 'Para empresas, marcas y producciones. M&aacute;s de 5.000 modelos activos en Italia, Francia, Espa&ntilde;a y UK.<br>De 6 a 80 a&ntilde;os, para todo tipo de campa&ntilde;a. Disponibles en 48 horas.',
    ),
    'cta_heading' => array(
        'it' => 'Scegli come procedere',
        'en' => 'Choose how to proceed',
        'fr' => 'Choisissez comment proc&eacute;der',
        'es' => 'Elige c&oacute;mo proceder',
    ),
    'cta_subtitle' => array(
        'it' => 'Richiedi un casting personalizzato o candidati per entrare nel team',
        'en' => 'Request a custom casting or apply to join the team',
        'fr' => 'Demandez un casting personnalis&eacute; ou postulez pour rejoindre l\'&eacute;quipe',
        'es' => 'Solicita un casting personalizado o postula para unirte al equipo',
    ),
    'cta_cerco' => array(
        'it' => 'Cerco modelli per il mio progetto',
        'en' => 'I\'m looking for models for my project',
        'fr' => 'Je cherche des mannequins pour mon projet',
        'es' => 'Busco modelos para mi proyecto',
    ),
    'cta_sono' => array(
        'it' => 'Sono un/a modello/a &mdash; cerco lavoro',
        'en' => 'I\'m a model &mdash; looking for work',
        'fr' => 'Je suis mannequin &mdash; je cherche du travail',
        'es' => 'Soy modelo &mdash; busco trabajo',
    ),
    'cta_esplora' => array(
        'it' => 'Esplora book e profili',
        'en' => 'Explore books &amp; profiles',
        'fr' => 'Explorer les books et profils',
        'es' => 'Explorar books y perfiles',
    ),
    'how_step1' => array(
        'it' => 'DESCRIVI IL PROGETTO',
        'en' => 'DESCRIBE THE PROJECT',
        'fr' => 'D&Eacute;CRIVEZ LE PROJET',
        'es' => 'DESCRIBE EL PROYECTO',
    ),
    'how_step2' => array(
        'it' => 'TI CHIAMIAMO',
        'en' => 'WE CALL YOU',
        'fr' => 'NOUS VOUS APPELONS',
        'es' => 'TE LLAMAMOS',
    ),
    'how_step3' => array(
        'it' => 'HAI I BOOK',
        'en' => 'GET THE BOOKS',
        'fr' => 'RECEVEZ LES BOOKS',
        'es' => 'RECIBE LOS BOOKS',
    ),
    'how_step4' => array(
        'it' => 'SUPPORTO CONTINUO',
        'en' => 'ONGOING SUPPORT',
        'fr' => 'SUPPORT CONTINU',
        'es' => 'SOPORTE CONTINUO',
    ),
    'how_tagline' => array(
        'it' => 'Book in 24 ore &bull; Misure certificate &bull; Contratti CCNL &bull; Fatturazione unica',
        'en' => 'Books in 24 hours &bull; Certified measurements &bull; CCNL contracts &bull; Single invoicing',
        'fr' => 'Books en 24 heures &bull; Mensurations certifi&eacute;es &bull; Contrats CCNL &bull; Facturation unique',
        'es' => 'Books en 24 horas &bull; Medidas certificadas &bull; Contratos CCNL &bull; Facturaci&oacute;n &uacute;nica',
    ),
    'feat_eyebrow' => array('it' => 'I nostri punti di forza', 'en' => 'Our strengths', 'fr' => 'Nos points forts', 'es' => 'Nuestros puntos fuertes'),
    'feat_heading' => array('it' => 'Qualit&agrave; e velocit&agrave;', 'en' => 'Quality and speed', 'fr' => 'Qualit&eacute; et rapidit&eacute;', 'es' => 'Calidad y rapidez'),
    'feat1_title' => array('it' => 'Database completo', 'en' => 'Complete database', 'fr' => 'Base de donn&eacute;es compl&egrave;te', 'es' => 'Base de datos completa'),
    'feat1_text' => array(
        'it' => 'Oltre 5.000 modelli verificati e sempre aggiornati. Bambini, ragazzi, adulti, senior, plus size, fitness.',
        'en' => 'Over 5,000 verified and always up-to-date models. Children, teens, adults, seniors, plus size, fitness.',
        'fr' => 'Plus de 5 000 mannequins v&eacute;rifi&eacute;s et toujours &agrave; jour. Enfants, ados, adultes, seniors, grande taille, fitness.',
        'es' => 'M&aacute;s de 5.000 modelos verificados y siempre actualizados. Ni&ntilde;os, adolescentes, adultos, seniors, tallas grandes, fitness.',
    ),
    'feat2_title' => array('it' => 'Diversit&agrave; e inclusione', 'en' => 'Diversity and inclusion', 'fr' => 'Diversit&eacute; et inclusion', 'es' => 'Diversidad e inclusi&oacute;n'),
    'feat2_text' => array(
        'it' => 'Tutte le etnie, ogni tipo di bellezza rappresentata. Casting mirati per ogni progetto e ogni brand.',
        'en' => 'All ethnicities, every type of beauty represented. Targeted casting for every project and brand.',
        'fr' => 'Toutes les ethnies, chaque type de beaut&eacute; repr&eacute;sent&eacute;. Castings cibl&eacute;s pour chaque projet et chaque marque.',
        'es' => 'Todas las etnias, cada tipo de belleza representada. Castings dirigidos para cada proyecto y marca.',
    ),
    'feat3_title' => array('it' => 'Casting veloce', 'en' => 'Fast casting', 'fr' => 'Casting rapide', 'es' => 'Casting r&aacute;pido'),
    'feat3_text' => array(
        'it' => 'Selezione e conferma in meno di 24 ore. Polaroid e self-tape professionali su richiesta.',
        'en' => 'Selection and confirmation in under 24 hours. Professional polaroids and self-tapes on request.',
        'fr' => 'S&eacute;lection et confirmation en moins de 24 heures. Polaroids et self-tapes professionnels sur demande.',
        'es' => 'Selecci&oacute;n y confirmaci&oacute;n en menos de 24 horas. Polaroids y self-tapes profesionales bajo demanda.',
    ),
    'feat4_title' => array('it' => 'Servizio completo', 'en' => 'Full service', 'fr' => 'Service complet', 'es' => 'Servicio completo'),
    'feat4_text' => array(
        'it' => 'Gestione contratti, diritti immagine, fatturazione. Un project manager per ogni progetto.',
        'en' => 'Contract management, image rights, invoicing. A dedicated project manager for every project.',
        'fr' => 'Gestion des contrats, droits &agrave; l\'image, facturation. Un chef de projet d&eacute;di&eacute; pour chaque projet.',
        'es' => 'Gesti&oacute;n de contratos, derechos de imagen, facturaci&oacute;n. Un project manager dedicado para cada proyecto.',
    ),
    'serv_eyebrow' => array('it' => 'Servizi', 'en' => 'Services', 'fr' => 'Services', 'es' => 'Servicios'),
    'serv_heading' => array('it' => 'Per ogni tipo di produzione', 'en' => 'For every type of production', 'fr' => 'Pour chaque type de production', 'es' => 'Para cada tipo de producci&oacute;n'),
    'serv_ecomm' => array('it' => 'Cataloghi online, marketplace, lookbook', 'en' => 'Online catalogues, marketplace, lookbook', 'fr' => 'Catalogues en ligne, marketplace, lookbook', 'es' => 'Cat&aacute;logos online, marketplace, lookbook'),
    'serv_fashion' => array('it' => 'Campagne, sfilate, editoriali', 'en' => 'Campaigns, fashion shows, editorials', 'fr' => 'Campagnes, d&eacute;fil&eacute;s, &eacute;ditoriaux', 'es' => 'Campa&ntilde;as, desfiles, editoriales'),
    'serv_adv' => array('it' => 'Spot TV, web ADV, billboard', 'en' => 'TV commercials, web ADV, billboards', 'fr' => 'Spots TV, publicit&eacute; web, affichage', 'es' => 'Spots TV, publicidad web, vallas publicitarias'),
    'serv_social' => array('it' => 'Content per Instagram, TikTok', 'en' => 'Content for Instagram, TikTok', 'fr' => 'Contenu pour Instagram, TikTok', 'es' => 'Contenido para Instagram, TikTok'),
    'serv_corp' => array('it' => 'Brochure aziendali, website', 'en' => 'Corporate brochures, websites', 'fr' => 'Brochures d\'entreprise, sites web', 'es' => 'Folletos corporativos, sitios web'),
    'serv_life' => array('it' => 'Food, travel, beauty, family', 'en' => 'Food, travel, beauty, family', 'fr' => 'Food, voyage, beaut&eacute;, famille', 'es' => 'Food, viajes, belleza, familia'),
    'cov_eyebrow'=>array('it'=>'Dove operiamo','en'=>'Where we operate','fr'=>'O&ugrave; nous intervenons','es'=>'D&oacute;nde operamos'),
    'cov_heading'=>array('it'=>'In tutta Europa','en'=>'Across Europe','fr'=>'Partout en Europe','es'=>'En toda Europa'),
    'cov_sub'=>array('it'=>'Modelli e modelle per campagne, sfilate ed editoriali nelle principali citt&agrave; europee &mdash; Italia, Spagna, Francia, UK, Germania e tutta Europa.','en'=>'Models for campaigns, fashion shows and editorials in every major European city &mdash; Italy, Spain, France, UK, Germany and all of Europe.','fr'=>'Mannequins pour campagnes, d&eacute;fil&eacute;s et &eacute;ditoriaux dans toutes les grandes villes europ&eacute;ennes &mdash; Italie, Espagne, France, UK, Allemagne et toute l\'Europe.','es'=>'Modelos para campa&ntilde;as, desfiles y editoriales en las principales ciudades europeas &mdash; Italia, Espa&ntilde;a, Francia, UK, Alemania y toda Europa.'),
);

toa_component('header');
// 2026-06-04 marco — $images rimosso con la gallery-talent statica (sostituita dalla griglia live iniettata)
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => 'MODELS',
    'title'      => _t_raw(array('it'=>'Modelli.','en'=>'Models.','fr'=>'Mannequins.','es'=>'Modelos.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<div class="container" style="text-align:center;margin:-4px 0 26px"><a href="/wp-content/themes/toagency-theme/assets/pdf/presentazione-<?php echo $lang; ?>.pdf" download class="toa-pdf-link">&darr; <?php echo $_t(array('it'=>'Scarica la presentazione (PDF)','en'=>'Download the presentation (PDF)','fr'=>'T&eacute;l&eacute;charger la pr&eacute;sentation (PDF)','es'=>'Descarga la presentaci&oacute;n (PDF)')); ?></a></div>

<?php // 2026-06-04 marco — rimossi gallery-talent statica + split-cta (CRO: niente bivi/uscite, focus su griglia live + form iniettati) ?>

<?php toa_component('how-it-works', array(
    'lang'   => $lang,
    'steps'  => array(
        array('time' => "1'", 'label' => $_t($t['how_step1'])),
        array('time' => "5'", 'label' => $_t($t['how_step2'])),
        array('time' => '2h', 'label' => $_t($t['how_step3'])),
        array('time' => 'H24', 'label' => $_t($t['how_step4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<!-- Features -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['feat_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['feat_heading']); ?></h2>
    </div>
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-number">01</div>
            <h3 class="feature-title"><?php echo $_t($t['feat1_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat1_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">02</div>
            <h3 class="feature-title"><?php echo $_t($t['feat2_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat2_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">03</div>
            <h3 class="feature-title"><?php echo $_t($t['feat3_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat3_text']); ?></p>
        </div>
        <div class="feature-card">
            <div class="feature-number">04</div>
            <h3 class="feature-title"><?php echo $_t($t['feat4_title']); ?></h3>
            <p class="feature-text"><?php echo $_t($t['feat4_text']); ?></p>
        </div>
    </div>
</section>

<!-- Services Grid -->
<section class="services-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['serv_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['serv_heading']); ?></h2>
    </div>
    <div class="services-grid">
        <div class="service-card"><div class="service-name">E-Commerce</div><div class="service-desc"><?php echo $_t($t['serv_ecomm']); ?></div></div>
        <div class="service-card"><div class="service-name">Fashion</div><div class="service-desc"><?php echo $_t($t['serv_fashion']); ?></div></div>
        <div class="service-card"><div class="service-name">Advertising</div><div class="service-desc"><?php echo $_t($t['serv_adv']); ?></div></div>
        <div class="service-card"><div class="service-name">Social Media</div><div class="service-desc"><?php echo $_t($t['serv_social']); ?></div></div>
        <div class="service-card"><div class="service-name">Corporate</div><div class="service-desc"><?php echo $_t($t['serv_corp']); ?></div></div>
        <div class="service-card"><div class="service-name">Lifestyle</div><div class="service-desc"><?php echo $_t($t['serv_life']); ?></div></div>
    </div>
</section>

<style>
/* Garanzie compatta */
.features-grid .feature-card{padding:18px 26px!important}
.features-grid .feature-number{font-size:1.55rem!important;line-height:1!important;margin-bottom:6px!important}
.features-grid .feature-title{margin-bottom:4px!important}
.features-grid .feature-text{margin:0!important}
@media(max-width:600px){.features-grid .feature-card{padding:16px 20px!important}}
/* Coverage */
.coverage-country .cov-princ{color:#fff;font-weight:600;line-height:1.7}
.coverage-country h4 a,.coverage-country .cov-princ a{color:inherit;text-decoration:none;border-bottom:1px solid rgba(200,255,0,.35);transition:color .15s ease,border-color .15s ease}
.coverage-country h4 a:hover,.coverage-country .cov-princ a:hover{color:var(--accent);border-color:var(--accent)}
.cov-details{margin-top:10px}
.cov-details summary{cursor:pointer;color:var(--accent);font-size:.8rem;font-weight:700;letter-spacing:.3px;list-style:none;display:inline-block}
.cov-details summary::-webkit-details-marker{display:none}
.cov-details summary::after{content:' \25be';margin-left:2px}
.cov-details[open] summary::after{content:' \25b4'}
.cov-details p{color:var(--gray-4);font-size:.85rem;margin-top:8px;font-weight:400;line-height:1.7}
.cov-details p a{color:var(--gray-4);border-bottom:1px solid rgba(200,255,0,.25);text-decoration:none;transition:color .15s ease}
.cov-details p a:hover{color:var(--accent)}
.coverage-note{margin:26px auto 0;font-size:.95rem;color:var(--gray-4)}
.coverage-note strong{color:#fff}
.toa-pdf-link{display:inline-block;color:var(--gray-4);font-size:.82rem;font-weight:600;text-decoration:none;border-bottom:1px solid rgba(200,255,0,.4);padding-bottom:1px}
.toa-pdf-link:hover{color:var(--accent);border-color:var(--accent)}
/* Mobile: comprimi la barra loghi (1 riga) per un primo schermo più diretto */
@media(max-width:768px){
  .toa-cro-loghi{padding-top:16px!important;padding-bottom:12px!important}
  .toa-cro-loghi .brand-label{margin-bottom:10px!important}
  .toa-cro-loghi .ticker-row.reverse{display:none!important}
}
</style>

<?php toa_component('google-reviews'); ?>

<?php
$covCity = function($n, $u=null) use ($_t){ $l=$_t($n); return $u ? '<a href="'.$u.'">'.$l.'</a>' : $l; };
$covList = function($arr) use ($covCity){ $o=array(); foreach($arr as $c){ $o[]=$covCity($c[0], isset($c[1])?$c[1]:null); } return implode(', ', $o); };
$covMore = array('it'=>'Vedi altre citt&agrave;','en'=>'See more cities','fr'=>'Voir plus de villes','es'=>'Ver m&aacute;s ciudades');
?>
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['cov_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:12px"><?php echo $_t($t['cov_heading']); ?></h2>
        <p style="font-size:0.95rem;color:var(--gray-4);max-width:640px;margin:0 0 36px"><?php echo $_t($t['cov_sub']); ?></p>
    </div>
    <div class="coverage-grid container">
        <div class="coverage-country">
            <h4><?php echo $_t(array('it'=>'Italia','en'=>'Italy','fr'=>'Italie','es'=>'Italia')); ?></h4>
            <p class="cov-princ"><?php echo $covList(array(
                array(array('it'=>'Milano','en'=>'Milan','fr'=>'Milan','es'=>'Mil&aacute;n'),'/agenzia-modelle-milano/'),
                array(array('it'=>'Roma','en'=>'Rome','fr'=>'Rome','es'=>'Roma'),'/agenzia-modelle-roma/'),
                array(array('it'=>'Napoli','en'=>'Naples','fr'=>'Naples','es'=>'N&aacute;poles'),'/agenzia-modelle-napoli/'),
                array(array('it'=>'Torino','en'=>'Turin','fr'=>'Turin','es'=>'Tur&iacute;n'),'/agenzia-modelle-torino/'),
                array(array('it'=>'Bologna','en'=>'Bologna','fr'=>'Bologne','es'=>'Bolonia'),'/agenzia-modelle-bologna/'),
                array(array('it'=>'Firenze','en'=>'Florence','fr'=>'Florence','es'=>'Florencia'),'/agenzia-modelle-firenze/'),
                array(array('it'=>'Verona','en'=>'Verona','fr'=>'V&eacute;rone','es'=>'Verona')),
                array(array('it'=>'Genova','en'=>'Genoa','fr'=>'G&ecirc;nes','es'=>'G&eacute;nova')),
                array(array('it'=>'Rimini','en'=>'Rimini','fr'=>'Rimini','es'=>'R&iacute;mini')),
            )); ?></p>
            <details class="cov-details"><summary><?php echo $_t($covMore); ?></summary><p><?php echo $covList(array(
                array(array('it'=>'Venezia','en'=>'Venice','fr'=>'Venise','es'=>'Venecia')),
                array(array('it'=>'Padova','en'=>'Padua','fr'=>'Padoue','es'=>'Padua')),
                array(array('it'=>'Brescia')),array(array('it'=>'Bergamo')),
                array(array('it'=>'Modena','fr'=>'Mod&egrave;ne','es'=>'M&oacute;dena')),
                array(array('it'=>'Bari')),array(array('it'=>'Palermo','fr'=>'Palerme')),
                array(array('it'=>'Catania','fr'=>'Catane')),array(array('it'=>'Cagliari')),
            )); ?></p></details>
        </div>
        <div class="coverage-country">
            <h4><?php echo $_t(array('it'=>'Spagna','en'=>'Spain','fr'=>'Espagne','es'=>'Espa&ntilde;a')); ?></h4>
            <p class="cov-princ"><?php echo $covList(array(
                array(array('it'=>'Madrid','en'=>'Madrid','fr'=>'Madrid','es'=>'Madrid'),'/agenzia-modelle-madrid/'),
                array(array('it'=>'Barcellona','en'=>'Barcelona','fr'=>'Barcelone','es'=>'Barcelona'),'/agenzia-modelle-barcellona/'),
                array(array('it'=>'Valencia','en'=>'Valencia','fr'=>'Valence','es'=>'Valencia')),
                array(array('it'=>'Ibiza','en'=>'Ibiza','fr'=>'Ibiza','es'=>'Ibiza')),
            )); ?></p>
            <details class="cov-details"><summary><?php echo $_t($covMore); ?></summary><p><?php echo $covList(array(
                array(array('it'=>'Malaga','es'=>'M&aacute;laga')),
                array(array('it'=>'Siviglia','en'=>'Seville','fr'=>'S&eacute;ville','es'=>'Sevilla')),
                array(array('it'=>'Bilbao')),array(array('it'=>'Marbella')),
            )); ?></p></details>
        </div>
        <div class="coverage-country">
            <h4><?php echo $_t(array('it'=>'Francia','en'=>'France','fr'=>'France','es'=>'Francia')); ?></h4>
            <p class="cov-princ"><?php echo $covList(array(
                array(array('it'=>'Parigi','en'=>'Paris','fr'=>'Paris','es'=>'Par&iacute;s'),'/agenzia-modelle-parigi/'),
                array(array('it'=>'Cannes','en'=>'Cannes','fr'=>'Cannes','es'=>'Cannes')),
                array(array('it'=>'Nizza','en'=>'Nice','fr'=>'Nice','es'=>'Niza')),
                array(array('it'=>'Lione','en'=>'Lyon','fr'=>'Lyon','es'=>'Lyon')),
            )); ?></p>
            <details class="cov-details"><summary><?php echo $_t($covMore); ?></summary><p><?php echo $covList(array(
                array(array('it'=>'Marsiglia','en'=>'Marseille','fr'=>'Marseille','es'=>'Marsella')),
                array(array('it'=>'Bordeaux')),array(array('it'=>'Montecarlo','en'=>'Monte Carlo','fr'=>'Monte-Carlo','es'=>'Montecarlo')),
            )); ?></p></details>
        </div>
        <div class="coverage-country">
            <h4><?php echo $_t(array('it'=>'Regno Unito','en'=>'United Kingdom','fr'=>'Royaume-Uni','es'=>'Reino Unido')); ?></h4>
            <p class="cov-princ"><?php echo $covList(array(
                array(array('it'=>'Londra','en'=>'London','fr'=>'Londres','es'=>'Londres'),'/agenzia-modelle-londra/'),
                array(array('it'=>'Manchester','en'=>'Manchester','fr'=>'Manchester','es'=>'Manchester'),'/agenzia-modelle-manchester/'),
                array(array('it'=>'Birmingham','en'=>'Birmingham','fr'=>'Birmingham','es'=>'Birmingham')),
            )); ?></p>
        </div>
        <div class="coverage-country">
            <h4><?php echo $_t(array('it'=>'Germania &amp; Benelux','en'=>'Germany &amp; Benelux','fr'=>'Allemagne &amp; Benelux','es'=>'Alemania &amp; Benelux')); ?></h4>
            <p class="cov-princ"><?php echo $covList(array(
                array(array('it'=>'Berlino','en'=>'Berlin','fr'=>'Berlin','es'=>'Berl&iacute;n'),'/agenzia-modelle-berlino/'),
                array(array('it'=>'Monaco di Baviera','en'=>'Munich','fr'=>'Munich','es'=>'M&uacute;nich')),
                array(array('it'=>'Bruxelles','en'=>'Brussels','fr'=>'Bruxelles','es'=>'Bruselas')),
                array(array('it'=>'Amsterdam','en'=>'Amsterdam','fr'=>'Amsterdam','es'=>'&Aacute;msterdam')),
            )); ?></p>
            <details class="cov-details"><summary><?php echo $_t($covMore); ?></summary><p><?php echo $covList(array(
                array(array('it'=>'Francoforte','en'=>'Frankfurt','fr'=>'Francfort','es'=>'Fr&aacute;ncfort')),
                array(array('it'=>'Amburgo','en'=>'Hamburg','fr'=>'Hambourg','es'=>'Hamburgo')),
                array(array('it'=>'Colonia','en'=>'Cologne','fr'=>'Cologne','es'=>'Colonia')),
            )); ?></p></details>
        </div>
        <div class="coverage-country">
            <h4><?php echo $_t(array('it'=>'Tutta Europa','en'=>'All of Europe','fr'=>'Toute l\'Europe','es'=>'Toda Europa')); ?></h4>
            <p class="cov-princ" style="font-weight:400;color:var(--gray-4)"><?php echo $_t(array('it'=>'Altre citt&agrave; e produzioni su richiesta','en'=>'Other cities &amp; productions on request','fr'=>'Autres villes et productions sur demande','es'=>'Otras ciudades y producciones bajo petici&oacute;n')); ?></p>
        </div>
    </div>
    <p class="coverage-note container"><?php echo $_t(array('it'=>'Non vedi la tua citt&agrave;? La copriamo lo stesso &mdash; operiamo ovunque in Italia e in Europa.','en'=>'Don\'t see your city? We cover it anyway &mdash; we operate everywhere in Italy and Europe.','fr'=>'Votre ville n\'est pas list&eacute;e&nbsp;? Nous la couvrons tout de m&ecirc;me &mdash; nous intervenons partout en Italie et en Europe.','es'=>'&iquest;No ves tu ciudad? La cubrimos igualmente &mdash; operamos en toda Italia y Europa.')); ?></p>
</section>

<?php toa_component('footer'); ?>
