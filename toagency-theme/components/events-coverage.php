<?php
/**
 * TOA Events — sezione «Dove operiamo» (hub + sottopagine servizio). #637, 19/10/2026
 * Elenco volutamente ampio e un po' generico (richiesta di Marco). Ordine dei gruppi in base alla lingua della pagina:
 * prima i paesi della lingua corrente, poi gli altri. Torino e Parigi in evidenza (flag true).
 * Uso: toa_component('events-coverage', array('lang' => $lang));
 */
$ec_lang = (isset($lang) && in_array($lang, array('it','en','fr','es'), true)) ? $lang : 'it';
$ec_i = array('it'=>0,'en'=>1,'fr'=>2,'es'=>3);
$ec_k = $ec_i[$ec_lang];
// ogni voce: array(it, en, fr, es, in_evidenza)
$ec_groups = array(
  'it' => array(
    array("Milano","Milan","Milan","Milán",false,'milano'),
    array("Roma","Rome","Rome","Roma",false,'roma'),
    array("Torino","Turin","Turin","Turín",true,'torino'),
    array("Genova","Genoa","Gênes","Génova"),
    array("Emilia-Romagna","Emilia-Romagna","Émilie-Romagne","Emilia-Romaña"),
    array("Veneto","Veneto","Vénétie","Véneto"),
    array("Toscana","Tuscany","Toscane","Toscana"),
    array("Napoli","Naples","Naples","Nápoles"),
  ),
  'fr' => array(
    array("Parigi","Paris","Paris","París",true,'parigi'),
    array("Costa Azzurra","French Riviera","Côte d'Azur","Costa Azul",false,'costa-azzurra'),
    array("Montecarlo","Monte Carlo","Monte-Carlo","Montecarlo",false,'montecarlo'),
    array("Lione","Lyon","Lyon","Lyon"),
  ),
  'es' => array(
    array("Madrid","Madrid","Madrid","Madrid",false,'madrid'),
    array("Barcellona","Barcelona","Barcelone","Barcelona",false,'barcellona'),
    array("Valencia","Valencia","Valence","Valencia"),
    array("Andalusia","Andalusia","Andalousie","Andalucía"),
  ),
  'en' => array(
    array("Londra","London","Londres","Londres"),
    array("Birmingham","Birmingham","Birmingham","Birmingham"),
    array("Manchester","Manchester","Manchester","Mánchester"),
    array("Liverpool","Liverpool","Liverpool","Liverpool"),
  ),
  'xx' => array(
    array("Berlino","Berlin","Berlin","Berlín"),
    array("Colonia","Cologne","Cologne","Colonia"),
    array("Amsterdam","Amsterdam","Amsterdam","Ámsterdam"),
    array("Bruxelles","Brussels","Bruxelles","Bruselas"),
    array("Vienna","Vienna","Vienne","Viena"),
    array("Lisbona","Lisbon","Lisbonne","Lisboa"),
    array("Istanbul","Istanbul","Istanbul","Estambul"),
    array("Dublino","Dublin","Dublin","Dublín"),
  ),
);
// Il gruppo della lingua corrente va per primo; poi italiano, francese, spagnolo, inglese (saltando quello già messo), poi il resto.
$ec_first = array('it'=>'it','en'=>'en','fr'=>'fr','es'=>'es');
$ec_order = array($ec_first[$ec_lang]);
foreach (array('it','fr','es','en') as $g) { if (!in_array($g, $ec_order, true)) { $ec_order[] = $g; } }
$ec_order[] = 'xx';
$ec_pg = get_option('toa_events_cities', array());
$ec_parts = array();
foreach ($ec_order as $g) {
    $cities = array();
    foreach ($ec_groups[$g] as $c) {
        $name = esc_html($c[$ec_k]);
        $label = !empty($c[4]) ? '<strong style="color:var(--white,#fff)">' . $name . '</strong>' : $name;
        // se la città ha una pagina TOA Events pubblicata (opzione toa_events_cities) il nome diventa un link
        if (!empty($c[5]) && isset($ec_pg[$c[5]][$ec_lang]) && get_post_status((int) $ec_pg[$c[5]][$ec_lang]) === 'publish') {
            $label = '<a href="' . esc_url(get_permalink((int) $ec_pg[$c[5]][$ec_lang])) . '" style="color:inherit;text-decoration:underline;text-underline-offset:3px">' . $label . '</a>';
        }
        $cities[] = $label;
    }
    $ec_parts[] = implode(', ', $cities);
}
$ec_tx = array(
  'eyebrow' => array("Dove operiamo","Where we operate","Où nous intervenons","Dónde operamos"),
  'head'    => array(
    "Operativi nelle principali città d'Europa e in alcune del resto del mondo, con personale sul posto",
    "Operating in Europe's main cities and in a few more around the world, with staff on the ground",
    "Présents dans les principales villes d'Europe et dans quelques autres dans le monde, avec du personnel sur place",
    "Operativos en las principales ciudades de Europa y en algunas más del resto del mundo, con personal sobre el terreno",
  ),
  'sub'     => array(
    "E ovunque serva: lavoriamo anche dove non l'abbiamo scritto.",
    "And wherever you need us: we work even where it isn't listed.",
    "Et partout où vous en avez besoin : nous intervenons aussi là où ce n'est pas écrit.",
    "Y donde nos necesites: trabajamos también donde no lo hemos escrito.",
  ),
);
?>
<!-- Dove operiamo -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo esc_html($ec_tx['eyebrow'][$ec_k]); ?></div>
        <h2 class="section-heading" style="margin-bottom:14px"><?php echo esc_html($ec_tx['head'][$ec_k]); ?></h2>
        <p style="font-size:1rem;line-height:1.7;color:var(--gray-4);max-width:820px;margin:0 0 14px"><?php echo implode(' &middot; ', $ec_parts); ?> &hellip;</p>
        <p style="font-size:0.95rem;color:var(--gray-4);max-width:640px;margin:0 0 20px"><?php echo esc_html($ec_tx['sub'][$ec_k]); ?></p>
    </div>
</section>
