<?php
/**
 * Component: Footer + Sticky CTA Mobile
 * GDPR patch: 5 aprile 2026 — link privacy multilingua + CTA tradotta
 */
?>
<!-- Footer -->
<footer class="footer">
  <p>TOAGENCY by Toa Group &mdash; Via Cavour, Torino (Italy) &mdash; P.I. 11800210012</p>
  <div class="footer-links">
    <?php
    $toa_lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : 'it';
    $toa_privacy = array(
        'en' => 'https://toagency.it/privacy-policy-english/',
        'fr' => 'https://toagency.it/fr/gdpr-privacy-policy/',
        'es' => 'https://toagency.it/es/gdpr-politica-de-privacidad/'
    );
    $toa_privacy_url = isset($toa_privacy[$toa_lang]) ? $toa_privacy[$toa_lang] : 'https://toagency.it/privacy-policy-3/';
    $toa_terms_labels = array(
        'en' => 'Terms and Conditions',
        'fr' => 'Conditions Générales',
        'es' => 'Términos y Condiciones'
    );
    $toa_terms_label = isset($toa_terms_labels[$toa_lang]) ? html_entity_decode($toa_terms_labels[$toa_lang]) : 'Termini e Condizioni';
    ?>
    <a href="<?php echo esc_url($toa_privacy_url); ?>" target="_blank">Privacy Policy</a>
    <a href="https://toagency.it/cookie-policy-ue/" target="_blank">Cookie Policy</a>
    <a href="<?php echo home_url('/terms-and-conditions/'); ?>"><?php echo esc_html($toa_terms_label); ?></a>
    <a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a>
  </div>

  <!-- BEGIN FIX 2026-05-31 marco — landing-links footer -->
  <div class="toa-footer-landing-links">
    <div class="toa-footer-landing-links__inner">

      <div class="toa-fll-col">
        <h4><?php echo _ht(['it'=>'Casting per città','en'=>'Casting by city','fr'=>'Casting par ville','es'=>'Casting por ciudad']); ?></h4>
        <ul>
          <li><a href="<?php echo home_url('/casting-torino/'); ?>"><?php echo _ht(['it'=>'Casting Torino','en'=>'Casting Turin','fr'=>'Casting Turin','es'=>'Casting Turín']); ?></a></li>
          <li><a href="<?php echo home_url('/casting-milano/'); ?>"><?php echo _ht(['it'=>'Casting Milano','en'=>'Casting Milan','fr'=>'Casting Milan','es'=>'Casting Milán']); ?></a></li>
          <li><a href="<?php echo home_url('/casting-roma/'); ?>"><?php echo _ht(['it'=>'Casting Roma','en'=>'Casting Rome','fr'=>'Casting Rome','es'=>'Casting Roma']); ?></a></li>
          <li><a href="<?php echo home_url('/agenzia-modelle-milano/'); ?>"><?php echo _ht(['it'=>'Modelle Milano','en'=>'Models Milan','fr'=>'Mannequins Milan','es'=>'Modelos Milán']); ?></a></li>
          <li><a href="<?php echo home_url('/casting-modelle-over-50/'); ?>"><?php echo _ht(['it'=>'Modelle Over 50','en'=>'Mature Models','fr'=>'Mannequins Matures','es'=>'Modelos Maduras']); ?></a></li>
        </ul>
      </div>

      <div class="toa-fll-col">
        <h4><?php echo _ht(['it'=>'Hostess per fiere','en'=>'Trade show hostess','fr'=>'Hôtesses foires','es'=>'Azafatas ferias']); ?></h4>
        <ul>
          <li><a href="<?php echo home_url('/hostess-fiere/'); ?>"><?php echo _ht(['it'=>'Hostess per Fiere','en'=>'Trade Show Hostess','fr'=>'Hôtesses Foires','es'=>'Azafatas Ferias']); ?></a></li>
          <li><a href="<?php echo home_url('/hostess-eicma/'); ?>">Hostess EICMA</a></li>
          <li><a href="<?php echo home_url('/hostess-rimini-wellness/'); ?>">Hostess Rimini Wellness</a></li>
          <li><a href="<?php echo home_url('/agenzia-comparse-milano/'); ?>"><?php echo _ht(['it'=>'Comparse Milano','en'=>'Extras Milan','fr'=>'Figurants Milan','es'=>'Extras Milán']); ?></a></li>
        </ul>
      </div>

    </div>
  </div>
  <!-- END FIX 2026-05-31 marco — landing-links footer -->
</footer>

<!-- Sticky CTA Mobile -->
<?php if (is_page()): ?>
<div class="sticky-cta-mobile">
  <?php
  $toa_cta_labels = array('en' => 'Get a Quote', 'fr' => 'Devis', 'es' => 'Presupuesto');
  $toa_cta_label = isset($toa_cta_labels[$toa_lang]) ? $toa_cta_labels[$toa_lang] : 'Preventivo';
  ?>
  <a href="<?php echo home_url('/form-b2b/'); ?>" class="btn-cta-mobile"><?php echo esc_html($toa_cta_label); ?></a>
</div>
<?php endif; ?>

<?php wp_footer(); ?>
<?php toa_component('whatsapp-button'); ?>
</body>
</html>