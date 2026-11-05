<?php
/**
 * Component: pagina web "Presentazione TOAgency" (ticket #824)
 * Variabili attese: $T (testi nella lingua corrente), $U (closure URL), $lang, $pdf_url, $form_url, $langs (switcher), $brands (elenco brand)
 * Nessun prezzo qui: i prezzi stanno solo nel PDF (decisione Marco 05/11/2026).
 */
$e = function($s) { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); };
$pills = function($arr, $cls = '') use ($e) {
    $o = '<ul class="pr-pills' . ($cls ? ' ' . $cls : '') . '">';
    foreach ($arr as $x) $o .= '<li>' . $e($x) . '</li>';
    return $o . '</ul>';
};
$contact_phone = ($lang === 'fr') ? '' : ' · +39 351 789 9225 ' . $e($T['ct_wa']);
?>
<div class="pr">

  <header class="pr-top">
    <div class="pr-wrap pr-top__in">
      <a class="pr-logo" href="<?php echo $e(apply_filters('wpml_home_url', home_url('/'))); ?>">
        <span class="pr-logo__n">TOAgency</span><span class="pr-logo__t"><?php echo $e($T['lbl_profile']); ?></span>
      </a>
      <?php if (count($langs) > 1): ?>
      <nav class="pr-langs" aria-label="<?php echo $e($T['lbl_lang']); ?>">
        <?php foreach ($langs as $lc => $lu): ?>
          <?php if ($lc === $lang): ?><span class="on" aria-current="true"><?php echo strtoupper($lc); ?></span>
          <?php else: ?><a href="<?php echo $e($lu); ?>" hreflang="<?php echo $e($lc); ?>"><?php echo strtoupper($lc); ?></a><?php endif; ?>
        <?php endforeach; ?>
      </nav>
      <?php endif; ?>
    </div>
  </header>

  <section class="pr-hero">
    <div class="pr-wrap">
      <p class="pr-eye pr-eye--lime"><?php echo $e($T['hero_eye']); ?></p>
      <h1 class="pr-h1"><?php echo $e($T['hero_h1']); ?></h1>
      <?php echo $pills($T['hero_roles'], 'pr-pills--roles'); ?>
      <ul class="pr-pts"><?php foreach ($T['hero_pts'] as $x): ?><li><?php echo $e($x); ?></li><?php endforeach; ?></ul>
      <div class="pr-btns">
        <a class="pr-btn pr-btn--lime" href="<?php echo $e($form_url); ?>" data-pr-ev="presentazione_preventivo"><?php echo $e($T['btn_quote']); ?></a>
        <a class="pr-btn pr-btn--line" href="<?php echo $e($pdf_url); ?>" download data-pr-ev="presentazione_pdf_download"><?php echo $e($T['btn_pdf']); ?></a>
      </div>
    </div>
  </section>

  <section class="pr-stats">
    <div class="pr-wrap pr-stats__in">
      <div><b>15+</b><span><?php echo $e($T['st_1']); ?></span></div>
      <div><b>20.000+</b><span><?php echo $e($T['st_2']); ?></span></div>
      <div><b>50+</b><span><?php echo $e($T['st_3']); ?></span></div>
      <div><b>4,7</b><span><?php echo $e($T['st_4']); ?></span></div>
    </div>
  </section>

  <section class="pr-sec">
    <div class="pr-wrap">
      <p class="pr-eye"><?php echo $e($T['are_eye']); ?></p>
      <h2 class="pr-h2 pr-h2--mb"><?php echo $e($T['are_h2']); ?></h2>
      <div class="pr-cards">
        <?php
        $aree = array(
            array('01', 'a1', $U('/events/')),
            array('02', 'a2', $U('/talent-database/')),
            array('03', 'a3', $U('/creators/')),
            array('04', 'a4', $U('/crew-database/')),
        );
        foreach ($aree as $a): ?>
        <article class="pr-card">
          <span class="pr-num"><?php echo $a[0]; ?></span>
          <h3><?php echo $e($T[$a[1] . '_t']); ?></h3>
          <p><?php echo $e($T[$a[1] . '_s']); ?></p>
          <?php echo $pills($T[$a[1] . '_c']); ?>
          <a class="pr-more" href="<?php echo $e($a[2]); ?>"><?php echo $e($T[$a[1] . '_l']); ?></a>
        </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="pr-sec pr-sec--white">
    <div class="pr-wrap">
      <p class="pr-eye"><?php echo $e($T['cl_eye']); ?></p>
      <h2 class="pr-h2"><?php echo $e($T['cl_h2']); ?></h2>
      <div class="pr-steps">
        <?php for ($i = 1; $i <= 5; $i++): ?>
        <div class="pr-step"><b>0<?php echo $i; ?></b><h3><?php echo $e($T["s{$i}_t"]); ?></h3><p><?php echo $e($T["s{$i}_d"]); ?></p></div>
        <?php endfor; ?>
      </div>
      <p class="pr-or"><?php echo $e($T['cl_or']); ?> <a href="<?php echo $e($U('/talent-database/')); ?>"><?php echo $e($T['cl_db']); ?></a></p>
    </div>
  </section>

  <section class="pr-sec pr-sec--dark">
    <div class="pr-wrap pr-two">
      <div>
        <p class="pr-eye pr-eye--lime"><?php echo $e($T['ag_eye']); ?></p>
        <h2 class="pr-h2"><?php echo $e($T['ag_h2']); ?></h2>
        <?php echo $pills($T['ag_types'], 'pr-pills--dark'); ?>
      </div>
      <ul class="pr-list"><?php foreach ($T['ag_pts'] as $x): ?><li><?php echo $e($x); ?></li><?php endforeach; ?></ul>
    </div>
  </section>

  <section class="pr-sec">
    <div class="pr-wrap pr-two">
      <div>
        <p class="pr-eye"><?php echo $e($T['dv_eye']); ?></p>
        <h2 class="pr-h2"><?php echo $e($T['dv_h2']); ?></h2>
        <?php echo $pills(explode(' · ', $T['dv_lead']), 'pr-pills--lead'); ?>
      </div>
      <ul class="pr-pills pr-pills--cities">
        <?php foreach ($T['dv_cities'] as $i => $c): ?>
        <li<?php echo $i < 2 ? ' class="on"' : ''; ?>><?php echo $e($c); ?><?php if ($i < 2): ?><small><?php echo $e($T['dv_office']); ?></small><?php endif; ?></li>
        <?php endforeach; ?>
        <li class="more"><?php echo $e($T['dv_more']); ?></li>
      </ul>
    </div>
  </section>

  <section class="pr-sec pr-sec--white" aria-label="<?php echo $e($T['br_eye']); ?>">
    <div class="pr-wrap">
      <p class="pr-eye"><?php echo $e($T['br_eye']); ?></p>
      <h2 class="pr-h2 pr-h2--mb"><?php echo $e($T['br_h2']); ?></h2>
      <div class="bw pr-bw" id="prBrands">
        <?php foreach (array_slice($brands, 0, 12) as $b): ?>
        <div class="pr-bw__cell"><span class="<?php echo $e($b['c']); ?>"><?php echo $e($b['t']); ?><?php if (!empty($b['sub'])): ?><small><?php echo $e($b['sub']); ?></small><?php endif; ?></span></div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="pr-sec pr-sec--dark">
    <div class="pr-wrap">
      <div class="pr-pzhead">
        <div>
          <p class="pr-eye pr-eye--lime"><?php echo $e($T['pz_eye']); ?></p>
          <h2 class="pr-h2"><?php echo $e($T['pz_h2a']); ?><br><?php echo $e($T['pz_h2b']); ?></h2>
        </div>
        <p class="pr-pzsub"><?php echo $e($T['pz_sub']); ?></p>
      </div>
      <div class="pr-pz">
        <?php for ($i = 1; $i <= 4; $i++): ?>
        <div><span class="pr-num">0<?php echo $i; ?></span><b><?php echo $e($T["pz{$i}_t"]); ?></b><span><?php echo $e($T["pz{$i}_d"]); ?></span></div>
        <?php endfor; ?>
      </div>
      <a class="pr-pzcta" href="<?php echo $e($U('/events/staff-e-prezzi/')); ?>"><span><?php echo $e($T['pz_cta']); ?></span><span aria-hidden="true">→</span></a>
    </div>
  </section>

  <section class="pr-contact">
    <div class="pr-wrap pr-two pr-two--end">
      <div>
        <h2 class="pr-h2 pr-h2--xl"><?php echo $e($T['ct_h2']); ?></h2>
        <a class="pr-btn pr-btn--dark" href="<?php echo $e($form_url); ?>" data-pr-ev="presentazione_preventivo"><?php echo $e($T['btn_quote']); ?></a>
      </div>
      <dl class="pr-dl">
        <dt><?php echo $e($T['ct_co']); ?></dt><dd><a href="mailto:business@toagency.it">business@toagency.it</a><?php echo $contact_phone; ?></dd>
        <dt><?php echo $e($T['ct_fr']); ?></dt><dd><a href="mailto:france@toagency.it">france@toagency.it</a></dd>
        <dt><?php echo $e($T['ct_uk']); ?></dt><dd><a href="mailto:uk@toagency.it">uk@toagency.it</a></dd>
        <dt><?php echo $e($T['ct_es']); ?></dt><dd><a href="mailto:espana@toagency.it">espana@toagency.it</a></dd>
      </dl>
    </div>
  </section>

  <footer class="pr-foot">
    <div class="pr-wrap pr-foot__in"><span><?php echo $e($T['foot_brand']); ?></span><span><?php echo $e($T['foot_upd']); ?></span></div>
  </footer>

</div>
