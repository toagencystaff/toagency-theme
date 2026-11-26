<?php
/**
 * Component: «Nella rete TOA Events» — collega le pagine SEO esistenti (agenzia hostess città, pagine fiera,
 * guide per aziende) alla struttura TOA Events, e TOA Events alle pagine fiera. 26/11/2026, chat TEMA SEO AI EVENTI.
 * Perché: per le stesse ricerche Google alternava 15 pagine nostre; ogni pagina deve avere un posto nella rete
 * (su verso TOA Events, accanto verso le sorelle). Nessun testo delle pagine con clic viene toccato: si aggiunge solo questo blocco.
 *
 * Uso:
 *   toa_component('seo-rete');                                            // pagina corrente (slug IT riconosciuto: città o fiera)
 *   toa_component('seo-rete', array('rete_mode' => 'fiere'));             // elenco di tutte le fiere (pagina «Personale per fiere»)
 *   toa_component('seo-rete', array('rete_mode' => 'citta', 'rete_city' => 'milano', 'rete_skip' => array('hostess-eicma')));
 * Link nelle altre lingue: sempre dalla traduzione WPML (mai URL scritti a mano); pagina non tradotta = voce nascosta.
 */
$rete_mode = isset($rete_mode) ? $rete_mode : 'pagina';
$rete_city = isset($rete_city) ? $rete_city : '';
$rete_skip = (isset($rete_skip) && is_array($rete_skip)) ? $rete_skip : array();
$rl = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$rl = in_array($rl, array('it', 'en', 'fr', 'es'), true) ? $rl : 'it';
$rt = function ($a) use ($rl) { return is_array($a) ? (isset($a[$rl]) ? $a[$rl] : $a['it']) : $a; };

if (!function_exists('toa_rete_data')) {
    // Fiere con una pagina già esistente (slug IT della pagina più forte in Search Console, 16 mesi).
    // città = chiave delle pagine città TOA Events ('' = nessuna pagina città); paese per l'ordine per lingua.
    function toa_rete_data() {
        $N = function ($it, $en, $fr, $es) { return array('it' => $it, 'en' => $en, 'fr' => $fr, 'es' => $es); };
        return array(
            'hostess-eicma'                   => array('milano', 'IT', 'EICMA'),
            'hostess-salone-del-mobile'       => array('milano', 'IT', 'Salone del Mobile'),
            'hostess-micam'                   => array('milano', 'IT', 'MICAM'),
            'hostess-lineapelle'              => array('milano', 'IT', 'Lineapelle'),
            'hostess-tuttofood'               => array('milano', 'IT', 'TUTTOFOOD'),
            'hostess-host-milano'             => array('milano', 'IT', 'HOST'),
            'hostess-mido-milano'             => array('milano', 'IT', 'MIDO'),
            'hostess-vinitaly'                => array('verona', 'IT', 'Vinitaly'),
            'hostess-marmomac-verona'         => array('verona', 'IT', 'Marmomac'),
            'hostess-vicenzaoro'              => array('', 'IT', 'Vicenzaoro'),
            'hostess-cosmoprof'               => array('bologna', 'IT', 'Cosmoprof'),
            'hostess-cersaie-bologna-guida'   => array('bologna', 'IT', 'Cersaie'),
            'hostess-eima-bologna'            => array('bologna', 'IT', 'EIMA'),
            'hostess-wmf-bologna'             => array('bologna', 'IT', 'WMF'),
            'hostess-ecomondo'                => array('rimini', 'IT', 'Ecomondo'),
            'hostess-ttg-rimini'              => array('rimini', 'IT', 'TTG'),
            'hostess-rimini-wellness'         => array('rimini', 'IT', 'Rimini Wellness'),
            'hostess-salone-nautico'          => array('genova', 'IT', $N('Salone Nautico', 'Genoa Boat Show', 'Salon Nautique de Gênes', 'Salón Náutico de Génova')),
            'hostess-salone-auto-torino'      => array('torino', 'IT', $N('Salone Auto Torino', 'Turin Motor Show', 'Salon Auto de Turin', 'Salón del Automóvil de Turín')),
            'hostess-atp-finals-torino'       => array('torino', 'IT', 'ATP Finals'),
            'hostess-pitti-uomo-firenze-guida'=> array('firenze', 'IT', 'Pitti Uomo'),
            'hostess-cibus-parma-guida'       => array('', 'IT', 'Cibus'),
            'hostess-montecarlo'              => array('montecarlo', 'MC', 'Monte Carlo'),
            'hostess-cannes-francia'          => array('costa-azzurra', 'FR', 'Cannes'),
            'hostess-fiere-barcellona'        => array('barcellona', 'ES', $N('Fiere Barcellona', 'Barcelona trade fairs', 'Salons Barcelone', 'Ferias Barcelona')),
            'hostess-fiere-monaco-di-baviera' => array('', 'DE', $N('Fiere Monaco di Baviera', 'Munich trade fairs', 'Salons Munich', 'Ferias Múnich')),
            'hostess-fiere-francoforte'       => array('', 'DE', $N('Fiere Francoforte', 'Frankfurt trade fairs', 'Salons Francfort', 'Ferias Fráncfort')),
            'hostess-fiere-dusseldorf'        => array('', 'DE', $N('Fiere Düsseldorf', 'Düsseldorf trade fairs', 'Salons Düsseldorf', 'Ferias Düsseldorf')),
            'hostess-fiere-berlino'           => array('', 'DE', $N('Fiere Berlino', 'Berlin trade fairs', 'Salons Berlin', 'Ferias Berlín')),
            'hostess-fiere-bruxelles'         => array('', 'BE', $N('Fiere Bruxelles', 'Brussels trade fairs', 'Salons Bruxelles', 'Ferias Bruselas')),
            'hostess-fiere-ginevra'           => array('', 'CH', $N('Fiere Ginevra', 'Geneva trade fairs', 'Salons Genève', 'Ferias Ginebra')),
        );
    }
    // Parola nello slug IT -> chiave pagina città TOA Events
    function toa_rete_city_words() {
        return array('torino' => 'torino', 'milano' => 'milano', 'roma' => 'roma', 'bologna' => 'bologna', 'rimini' => 'rimini',
            'verona' => 'verona', 'firenze' => 'firenze', 'genova' => 'genova', 'napoli' => 'napoli', 'parigi' => 'parigi',
            'lione' => 'lione', 'marsiglia' => 'marsiglia', 'bordeaux' => 'bordeaux', 'montecarlo' => 'montecarlo',
            'cannes' => 'costa-azzurra', 'saint-tropez' => 'costa-azzurra', 'madrid' => 'madrid', 'barcellona' => 'barcellona',
            'valencia' => 'valencia', 'siviglia' => 'siviglia', 'bilbao' => 'bilbao', 'malaga' => 'malaga');
    }
    // URL nella lingua di una pagina/articolo esistente dato lo slug IT ('' se non pubblicata o non tradotta)
    function toa_rete_url_slug($slug, $lang) {
        static $c = array();
        $k = $slug . '|' . $lang;
        if (isset($c[$k])) return $c[$k];
        global $wpdb;
        $row = $wpdb->get_row($wpdb->prepare("SELECT ID, post_type FROM {$wpdb->posts} WHERE post_name=%s AND post_status='publish' AND post_type IN ('page','post') ORDER BY (post_type='page') DESC LIMIT 1", $slug));
        $u = '';
        if ($row) {
            $id = (int) apply_filters('wpml_object_id', (int) $row->ID, $row->post_type, false, $lang);
            if ($id && get_post_status($id) === 'publish') $u = get_permalink($id);
        }
        return $c[$k] = $u;
    }
    // URL della pagina città TOA Events (campo toa_ev_city) nella lingua corrente
    function toa_rete_url_city($key) {
        static $c = array();
        if (isset($c[$key])) return $c[$key];
        $ids = get_posts(array('post_type' => 'page', 'post_status' => 'publish', 'meta_key' => 'toa_ev_city', 'meta_value' => $key,
            'numberposts' => 1, 'fields' => 'ids', 'suppress_filters' => false));
        $lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
        return $c[$key] = $ids ? get_permalink((int) $ids[0]) : toa_rete_url_ev($key, $lang);
    }
    // URL di una pagina TOA Events: servizio (opzione toa_events_pages) o sezione del mini sito (opzione toa_events_site)
    function toa_rete_url_ev($key, $lang) {
        foreach (array('toa_events_pages', 'toa_events_site') as $opt) {
            $m = get_option($opt, array());
            if (isset($m[$key][$lang]) && get_post_status((int) $m[$key][$lang]) === 'publish') return get_permalink((int) $m[$key][$lang]);
        }
        return '';
    }
}

$rete_fiere = toa_rete_data();
$rete_cities = include get_template_directory() . '/components/events-cities.php';
$rete_cname = function ($key) use ($rete_cities, $rt) { return isset($rete_cities[$key]['name']) ? $rt($rete_cities[$key]['name']) : ''; };

// Ordine dei paesi: prima quello della lingua della pagina, poi gli altri (respiro europeo, mai solo Italia)
$rete_first = array('it' => 'IT', 'fr' => 'FR', 'es' => 'ES', 'en' => 'IT');
$rete_order = array('IT', 'FR', 'MC', 'ES', 'DE', 'BE', 'CH');
usort($rete_order, function ($a, $b) use ($rete_first, $rl) { return ($b === $rete_first[$rl]) - ($a === $rete_first[$rl]); });

// Link fiera (nome · città)
$rete_fair_links = function ($filter) use ($rete_fiere, $rl, $rt, $rete_cname, $rete_order, &$rete_skip) {
    $out = array();
    foreach ($rete_order as $cc) {
        foreach ($rete_fiere as $slug => $f) {
            if ($f[1] !== $cc || in_array($slug, $rete_skip, true) || !$filter($slug, $f)) continue;
            $u = toa_rete_url_slug($slug, $rl);
            if (!$u) continue;
            $city = $f[0] ? $rete_cname($f[0]) : '';
            $out[] = array('u' => $u, 'n' => $rt($f[2]), 'c' => $city);
        }
    }
    return $out;
};

$tx = array(
    'eyebrow' => array('it' => 'TOA Events', 'en' => 'TOA Events', 'fr' => 'TOA Events', 'es' => 'TOA Events'),
    'h'       => array('it' => 'Tutto lo staff per il tuo evento', 'en' => 'All the staff for your event', 'fr' => 'Tout le personnel pour votre événement', 'es' => 'Todo el personal para tu evento'),
    'city'    => array('it' => 'Staff Eventi %s', 'en' => 'Event Staff %s', 'fr' => 'Personnel Événementiel %s', 'es' => 'Personal Eventos %s'),
    'fiere'   => array('it' => 'Personale Fiere', 'en' => 'Trade Show Staff', 'fr' => 'Personnel Salons', 'es' => 'Personal Ferias'),
    'staff'   => array('it' => 'Staff & Prezzi', 'en' => 'Staff & Prices', 'fr' => 'Personnel & Tarifs', 'es' => 'Personal & Precios'),
    'servizi' => array('it' => 'Tutti i Servizi', 'en' => 'All Services', 'fr' => 'Tous les Services', 'es' => 'Todos los Servicios'),
    'f_city'  => array('it' => 'Fiere a %s', 'en' => 'Trade shows in %s', 'fr' => 'Salons à %s', 'es' => 'Ferias en %s'),
    'f_all'   => array('it' => 'Fiere dove lavoriamo', 'en' => 'Trade shows we staff', 'fr' => 'Salons où nous intervenons', 'es' => 'Ferias en las que trabajamos'),
    'f_sub'   => array('it' => 'Una pagina per fiera: figure, lingue, come prenotare.', 'en' => 'One page per show: roles, languages, how to book.', 'fr' => 'Une page par salon : profils, langues, réservation.', 'es' => 'Una página por feria: perfiles, idiomas, cómo reservar.'),
);

$pills = array();   // link principali
$fairs = array();   // link fiere
$fairs_title = '';
$heading = $rt($tx['h']);

if ($rete_mode === 'fiere') {
    $fairs = $rete_fair_links(function () { return true; });
    $heading = $rt($tx['f_all']);
    $fairs_title = $rt($tx['f_sub']);
} elseif ($rete_mode === 'citta') {
    if ($rete_city) {
        $fairs = $rete_fair_links(function ($s, $f) use ($rete_city) { return $f[0] === $rete_city; });
        $heading = sprintf($rt($tx['f_city']), $rete_cname($rete_city));
    }
} else {
    // pagina corrente: slug IT dell'originale
    $qid = get_queried_object_id();
    $qpt = $qid ? get_post_type($qid) : '';
    $slug = '';
    if ($qid && in_array($qpt, array('page', 'post'), true)) {
        $itid = (int) apply_filters('wpml_object_id', $qid, $qpt, true, 'it');
        $slug = (string) get_post_field('post_name', $itid ? $itid : $qid);
    }
    $ckey = '';
    if ($slug && isset($rete_fiere[$slug])) {
        $ckey = $rete_fiere[$slug][0];
        $rete_skip[] = $slug;
    } elseif ($slug) {
        foreach (toa_rete_city_words() as $w => $k) {
            if (preg_match('/(^|-)' . preg_quote($w, '/') . '(-|$)/', $slug)) { $ckey = $k; break; }
        }
    }
    if ($ckey && ($u = toa_rete_url_city($ckey))) $pills[] = array('u' => $u, 'n' => sprintf($rt($tx['city']), $rete_cname($ckey)), 'main' => true);
    if ($u = toa_rete_url_ev('fiere', $rl)) $pills[] = array('u' => $u, 'n' => $rt($tx['fiere']));
    if ($u = toa_rete_url_ev('staff', $rl)) $pills[] = array('u' => $u, 'n' => $rt($tx['staff']));
    if ($u = toa_rete_url_ev('servizi', $rl)) $pills[] = array('u' => $u, 'n' => $rt($tx['servizi']));
    if ($ckey) {
        $fairs = array_slice($rete_fair_links(function ($s, $f) use ($ckey) { return $f[0] === $ckey; }), 0, 8);
        if ($fairs) $fairs_title = sprintf($rt($tx['f_city']), $rete_cname($ckey));
    }
}

if (!$pills && !$fairs) return;
?>
<section class="toa-rete" aria-label="<?php echo esc_attr($heading); ?>">
  <style>
    .toa-rete{max-width:1100px;margin:48px auto;padding:28px 32px;border:1px solid #111;background:#fff;color:#111}
    .toa-rete__eyebrow{font-size:12px;letter-spacing:.12em;text-transform:uppercase;color:#555;margin:0 0 6px}
    .toa-rete__h{font-size:22px;line-height:1.25;margin:0 0 16px;color:#111}
    .toa-rete__sub{font-size:14px;color:#555;margin:20px 0 10px}
    .toa-rete__list{display:flex;flex-wrap:wrap;gap:10px;margin:0;padding:0;list-style:none}
    .toa-rete__list a{display:inline-block;border:1px solid #111;padding:8px 14px;color:#111;text-decoration:none;font-size:14px;line-height:1.3;background:#fff}
    .toa-rete__list a:hover{background:#111;color:#fff}
    .toa-rete__list a.is-main{background:#111;color:#fff}
    .toa-rete__list a.is-main:hover{background:#000}
    .toa-rete__list small{color:inherit;opacity:.65;margin-left:6px}
    @media(max-width:768px){.toa-rete{margin:32px 16px;padding:20px}.toa-rete__h{font-size:19px}}
  </style>
  <p class="toa-rete__eyebrow"><?php echo esc_html($rt($tx['eyebrow'])); ?></p>
  <h2 class="toa-rete__h"><?php echo esc_html($heading); ?></h2>
  <?php if ($pills): ?>
  <ul class="toa-rete__list">
    <?php foreach ($pills as $p): ?>
    <li><a href="<?php echo esc_url($p['u']); ?>"<?php echo !empty($p['main']) ? ' class="is-main"' : ''; ?>><?php echo esc_html($p['n']); ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
  <?php if ($fairs): ?>
  <?php if ($fairs_title): ?><p class="toa-rete__sub"><?php echo esc_html($fairs_title); ?></p><?php endif; ?>
  <ul class="toa-rete__list">
    <?php foreach ($fairs as $f): ?>
    <li><a href="<?php echo esc_url($f['u']); ?>"><?php echo esc_html($f['n']); ?><?php if ($f['c'] && $rete_mode === 'fiere'): ?><small><?php echo esc_html($f['c']); ?></small><?php endif; ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
</section>
