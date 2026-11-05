<?php
/**
 * Template Name: Indice pagine (interno)
 * Ticket #824 (05/11/2026): elenco con ricerca di tutte le pagine pubbliche del sito nella lingua corrente, per Marco e lo staff
 * (non serve il login a WordPress). Sempre noindex. Si aggiorna da solo: legge le pagine pubblicate.
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$lang = in_array($lang, array('it','en','fr','es'), true) ? $lang : 'it';
add_filter('wp_robots', function($r) { $r['noindex'] = true; $r['nofollow'] = true; unset($r['index'], $r['follow']); return $r; }, 1000);
add_filter('wpseo_robots', function() { return 'noindex, nofollow'; }, 1000);

$escludi_tpl = array('self-edit', 'mia-agenda', 'completa-profilo', 'tnx', 'proposta', 'landing-ads');
$pagine = get_posts(array('post_type' => 'page', 'post_status' => 'publish', 'posts_per_page' => -1, 'orderby' => 'title', 'order' => 'ASC', 'suppress_filters' => false));
$righe = array();
foreach ($pagine as $p) {
    if ($p->ID === get_queried_object_id()) continue;
    if (get_post_meta($p->ID, '_yoast_wpseo_meta-robots-noindex', true) === '1') continue;
    $tpl = (string) get_post_meta($p->ID, '_wp_page_template', true);
    $salta = false;
    foreach ($escludi_tpl as $x) { if ($tpl && strpos($tpl, $x) !== false) { $salta = true; break; } }
    if ($salta) continue;
    $righe[] = array('t' => get_the_title($p), 'u' => get_permalink($p), 'n' => $p->ID);
}
$tx = array(
  'it' => array('titolo' => 'Indice pagine', 'cerca' => 'Cerca una pagina…', 'n' => 'pagine', 'nessuna' => 'Nessuna pagina trovata', 'nota' => 'Elenco interno, non indicizzato da Google. Si aggiorna da solo.'),
  'en' => array('titolo' => 'Page index', 'cerca' => 'Search a page…', 'n' => 'pages', 'nessuna' => 'No page found', 'nota' => 'Internal list, not indexed by Google. Updates itself.'),
  'fr' => array('titolo' => 'Index des pages', 'cerca' => 'Chercher une page…', 'n' => 'pages', 'nessuna' => 'Aucune page trouvée', 'nota' => 'Liste interne, non indexée par Google. Se met à jour seule.'),
  'es' => array('titolo' => 'Índice de páginas', 'cerca' => 'Buscar una página…', 'n' => 'páginas', 'nessuna' => 'Ninguna página encontrada', 'nota' => 'Lista interna, no indexada por Google. Se actualiza sola.'),
);
$T = $tx[$lang];
$e = function($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $e($T['titolo']); ?> | TOAgency</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#f5f5f3;color:#08080a;font-family:'Instrument Sans',-apple-system,'Segoe UI',sans-serif}
.ix{max-width:900px;margin:0 auto;padding:40px 20px 80px}
.ix h1{font-family:Georgia,serif;font-weight:500;font-size:40px;margin:0 0 8px}
.ix p.n{color:#555;margin:0 0 24px;font-size:15px}
.ix input{width:100%;font:inherit;font-size:20px;padding:16px 20px;border:2px solid #08080a;border-radius:999px;background:#fff;outline:none}
.ix input:focus{border-color:#7aa300;box-shadow:0 0 0 4px #c8ff00}
.ix .c{margin:14px 4px 20px;color:#555;font-size:14px}
.ix ul{list-style:none;margin:0;padding:0;border-top:1px solid #dcdcd8}
.ix li{border-bottom:1px solid #dcdcd8}
.ix a{display:flex;justify-content:space-between;gap:16px;padding:14px 4px;color:#08080a;text-decoration:none;font-size:17px;font-weight:600}
.ix a span{font-weight:400;color:#777;font-size:13px;word-break:break-all;text-align:right}
.ix a:hover{background:#c8ff00}
.ix li.pin a{background:#08080a;color:#f5f5f3}.ix li.pin a span{color:#c8ff00}
.ix li[hidden]{display:none}
</style>
<?php wp_head(); ?>
</head>
<body class="ix-page">
<main class="ix">
  <h1><?php echo $e($T['titolo']); ?></h1>
  <p class="n"><?php echo $e($T['nota']); ?></p>
  <input type="search" id="ixQ" placeholder="<?php echo $e($T['cerca']); ?>" autofocus autocomplete="off">
  <p class="c"><span id="ixN"><?php echo count($righe); ?></span> <?php echo $e($T['n']); ?></p>
  <ul id="ixL">
    <?php foreach ($righe as $r):
      $path = parse_url($r['u'], PHP_URL_PATH);
      $pin = (stripos($r['u'], 'presentazione-agenzia') !== false || stripos($r['u'], 'agency-profile') !== false || stripos($r['u'], 'presentation-agence') !== false || stripos($r['u'], 'presentacion-agencia') !== false); ?>
    <li<?php echo $pin ? ' class="pin"' : ''; ?> data-q="<?php echo $e(mb_strtolower($r['t'] . ' ' . $path, 'UTF-8')); ?>"><a href="<?php echo $e($r['u']); ?>"><?php echo $e($r['t']); ?><span><?php echo $e($path); ?></span></a></li>
    <?php endforeach; ?>
  </ul>
  <p class="c" id="ixZ" hidden><?php echo $e($T['nessuna']); ?></p>
</main>
<script>
(function(){var q=document.getElementById('ixQ'),L=[].slice.call(document.querySelectorAll('#ixL li')),n=document.getElementById('ixN'),z=document.getElementById('ixZ');
// le pagine fissate (Presentazione) restano in cima
var pins=L.filter(function(x){return x.classList.contains('pin')}),ul=document.getElementById('ixL');pins.reverse().forEach(function(p){ul.insertBefore(p,ul.firstChild)});
function f(){var v=q.value.trim().toLowerCase().split(/\s+/).filter(Boolean),c=0;L.forEach(function(li){var t=li.getAttribute('data-q'),ok=v.every(function(w){return t.indexOf(w)>-1});li.hidden=!ok;if(ok)c++});n.textContent=c;z.hidden=c>0}
q.addEventListener('input',f);})();
</script>
<?php wp_footer(); ?>
</body>
</html>
