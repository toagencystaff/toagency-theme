<?php
/**
 * Component: 4 pagine A4 della presentazione PDF (ticket #824). Contenuto proprio, NON e' la copia della pagina web:
 * servizi, concept, prezzi, tutti i ruoli. Niente loghi clienti, niente link al sito/form: solo email e telefono.
 * Variabili attese: $T (testi), $lang. Funziona anche fuori da WordPress (script CLI di generazione PDF).
 * I prezzi sono quelli delle schede commerciali CRM #231 / #167 (aggiornare qui e rigenerare i PDF se cambiano).
 */
$e = function($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$pl = function($arr, $cls = '') use ($e) {
    $o = '<ul class="pl' . ($cls ? ' ' . $cls : '') . '">';
    foreach ($arr as $x) $o .= '<li>' . $e($x) . '</li>';
    return $o . '</ul>';
};
// prezzo nella forma di ogni lingua (IT/ES "€ 109", EN "€109", FR "109 €")
$eur = function($n, $from = false) use ($lang, $T, $e) {
    $v = ($lang === 'en') ? '€' . $n : (($lang === 'fr') ? $n . ' €' : '€ ' . $n);
    return ($from ? $e($T['pp_c_from']) . ' ' : '') . $v;
};
$ph = ($lang === 'fr') ? '' : ' · +39 351 789 9225';
$foot = function($n) use ($T, $e) { return '<div class="ft"><span>' . $e($T['p_foot']) . '</span><span>' . $n . ' / 4</span></div>'; };
$lbl = function($t) use ($e) { return '<p class="lb">' . $e($t) . '</p>'; };
?>
<!-- ============ PAGINA 1: copertina + concept ============ -->
<section class="pg">
  <div class="cv">
    <div class="cv__top"><span class="pd cv__logo">TOAgency</span><span class="cv__tag"><?php echo $e($T['p_cover_tag']); ?></span></div>
    <h1 class="pd cv__h1"><?php echo $e($T['hero_h1']); ?></h1>
    <?php echo $pl($T['hero_pdf_roles'], 'pl-d pl-l'); ?>
    <ul class="sq cv__pts"><?php foreach ($T['hero_pts'] as $x): ?><li><?php echo $e($x); ?></li><?php endforeach; ?></ul>
  </div>
  <div class="stt">
    <div><div class="pd">15+</div><span><?php echo $e($T['pst_1']); ?></span></div>
    <div><div class="pd">20.000+</div><span><?php echo $e($T['pst_2']); ?></span></div>
    <div><div class="pd">30+</div><span><?php echo $e($T['pst_3']); ?></span></div>
    <div><div class="pd">4,7</div><span><?php echo $e($T['pst_4']); ?></span></div>
  </div>
  <div class="bk">
    <p class="ey"><?php echo $e($T['c_eye']); ?></p>
    <h2 class="h2"><?php echo $e($T['c_h2']); ?></h2>
    <div class="g3">
      <?php foreach (array('A','B','C') as $k): ?>
      <div class="bx"><span class="num"><?php echo $k; ?></span><strong><?php echo $e($T["c{$k}_t"]); ?></strong><span><?php echo $e($T["c{$k}_d"]); ?></span></div>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="bk">
    <p class="ey"><?php echo $e($T['cl_eye']); ?></p>
    <div class="g5">
      <?php for ($i = 1; $i <= 5; $i++): ?>
      <div class="st"><b>0<?php echo $i; ?></b><strong><?php echo $e($T["s{$i}_t"]); ?></strong><span><?php echo $e($T["s{$i}_d"]); ?></span></div>
      <?php endfor; ?>
    </div>
  </div>
  <?php echo $foot(1); ?>
</section>

<!-- ============ PAGINA 2: servizi ============ -->
<section class="pg">
  <div class="hd"><div><p class="ey"><?php echo $e($T['sv_eye']); ?></p><h2 class="h2 h2--l"><?php echo $e($T['sv_h2']); ?></h2></div><span class="pd hd__logo">TOAgency</span></div>
  <div class="sv">
    <?php for ($i = 1; $i <= 6; $i++): ?>
    <div class="bx bx--sv">
      <div class="sv__t"><span class="num">0<?php echo $i; ?></span><h3 class="pd"><?php echo $e($T["sv{$i}_t"]); ?></h3></div>
      <div><?php echo $lbl($T['sv_roles']); ?><?php echo $pl($T["sv{$i}_r"], 'pl-l'); ?></div>
      <div><?php echo $lbl($T['sv_for']); ?><?php echo $pl($T["sv{$i}_f"]); ?></div>
    </div>
    <?php endfor; ?>
  </div>
  <div class="tri">
    <?php for ($i = 1; $i <= 3; $i++): ?>
    <div><p class="pd"><?php echo $e($T["sv_b{$i}_t"]); ?></p><p><?php echo $e($T["sv_b{$i}_d"]); ?></p></div>
    <?php endfor; ?>
  </div>
  <?php echo $foot(2); ?>
</section>

<!-- ============ PAGINA 3: prezzi ============ -->
<section class="pg">
  <div class="hd"><div><p class="ey"><?php echo $e($T['pz_eye']); ?></p><h2 class="h2 h2--l"><?php echo $e($T['pp_h2']); ?></h2></div><span class="pd hd__logo">TOAgency</span></div>
  <div class="dep"><span class="ey"><?php echo $e($T['pp_dep']); ?></span><?php echo $pl($T['pp_fact'], 'pl-l'); ?></div>
  <div class="pr3">
    <div class="bx">
      <div class="bx__h"><h3 class="pd"><?php echo $e($T['pp_h_t']); ?></h3><span class="tg"><?php echo $e($T['pp_h_tag']); ?></span></div>
      <table class="tb">
        <tr><td></td><td class="th"><?php echo $e($T['pp_h_c1']); ?></td><td class="th"><?php echo $e($T['pp_h_c2']); ?></td></tr>
        <?php $rows = array(array(109,149), array(119,159), array(129,179), array(139,189));
        foreach ($rows as $n => $r): ?>
        <tr><td><?php echo $e($T['pp_h_r'][$n]); ?></td><td class="pv"><?php echo $eur($r[0]); ?></td><td class="pv"><?php echo $eur($r[1]); ?></td></tr>
        <?php endforeach; ?>
      </table>
      <div><?php echo $lbl($T['pp_h_oth']); ?><?php echo $pl($T['pp_h_oth_l']); ?></div>
      <div><?php echo $lbl($T['pp_h_q']); ?><?php echo $pl($T['pp_h_q_l']); ?></div>
    </div>
    <div class="bx">
      <div class="bx__h"><h3 class="pd"><?php echo $e($T['pp_c_t']); ?></h3><span class="tg"><?php echo $e($T['pp_c_tag']); ?></span></div>
      <table class="tb">
        <?php $cp = array(289, 389, 159, 867);
        foreach ($T['pp_c_rows'] as $n => $r): ?>
        <tr><td><?php echo $e($r[0]); ?></td><td class="pv"><?php echo $eur($cp[$n], true); ?></td><td class="pu"><?php echo $e($r[1]); ?></td></tr>
        <?php endforeach; ?>
      </table>
      <div><?php echo $lbl($T['pp_dep_lbl']); ?><?php echo $pl($T['pp_c_dep']); ?></div>
      <p class="nt"><?php echo $e($T['pp_c_note']); ?></p>
    </div>
    <div class="bx">
      <div class="bx__h"><h3 class="pd"><?php echo $e($T['pp_f_t']); ?></h3><span class="tg"><?php echo $e($T['pp_q_tag']); ?></span></div>
      <?php echo $pl($T['pp_f_pills'], 'pl-l'); ?>
      <div><?php echo $lbl($T['pp_dep_lbl']); ?>
        <ul class="sq sq2"><?php foreach ($T['pp_f_dep'] as $x): ?><li><?php echo $e($x); ?></li><?php endforeach; ?></ul>
      </div>
    </div>
    <div class="bx bx--dk">
      <div class="bx__h"><h3 class="pd"><?php echo $e($T['pp_m_t']); ?></h3><span class="tg tg--lime"><?php echo $e($T['pp_q_tag']); ?></span></div>
      <div><p class="lb lb--dk"><?php echo $e($T['pp_dep_lbl']); ?></p><?php echo $pl($T['pp_m_dep'], 'pl-d'); ?></div>
    </div>
  </div>
  <div class="bar"><strong class="pd"><?php echo $e($T['pp_bar_t']); ?></strong><span><?php echo $e($T['pp_bar_d']); ?></span></div>
  <p class="fn"><?php echo $e($T['pp_note']); ?></p>
  <?php echo $foot(3); ?>
</section>

<!-- ============ PAGINA 4: partner e contatti ============ -->
<section class="pg">
  <div class="hd"><div><p class="ey"><?php echo $e($T['p4_eye']); ?></p><h2 class="h2 h2--l"><?php echo $e($T['p4_h2']); ?></h2></div><span class="pd hd__logo">TOAgency</span></div>
  <div class="ag">
    <p class="ey ey--lime"><?php echo $e($T['ag_eye']); ?></p>
    <p class="pd ag__h"><?php echo $e($T['ag_h2']); ?></p>
    <?php echo $pl($T['ag_types_pdf'], 'pl-d'); ?>
    <div class="g4"><?php foreach ($T['ag_pts_pdf'] as $x): ?><div><?php echo $e($x); ?></div><?php endforeach; ?></div>
  </div>
  <div class="bk bk--4">
    <p class="ey"><?php echo $e($T['dv_eye']); ?></p>
    <h2 class="h2 h2--s"><?php echo $e($T['dv_h2']); ?></h2>
    <ul class="pl">
      <?php foreach ($T['dv_cities'] as $i => $c): ?>
      <li<?php echo $i < 2 ? ' class="on"' : ''; ?>><?php echo $e($c); ?><?php echo $i < 2 ? ' · ' . $e(ucfirst($T['dv_office'])) : ''; ?></li>
      <?php endforeach; ?>
      <li class="more"><?php echo $e($T['dv_more']); ?></li>
    </ul>
  </div>
  <div class="ct">
    <h2 class="pd"><?php echo $e($T['ct_h2']); ?></h2>
    <dl>
      <dt><?php echo $e($T['ct_co']); ?></dt><dd>business@toagency.it<?php echo $ph; ?></dd>
      <dt><?php echo $e($T['ct_fr']); ?></dt><dd>france@toagency.it</dd>
      <dt><?php echo $e($T['ct_uk']); ?></dt><dd>uk@toagency.it</dd>
      <dt><?php echo $e($T['ct_es']); ?></dt><dd>espana@toagency.it</dd>
    </dl>
  </div>
  <div class="ft ft--4"><span><?php echo $e($T['foot_brand']); ?></span><span>4 / 4</span></div>
</section>
