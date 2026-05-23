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
    <a href="<?php echo home_url('/therms-and-conditions/'); ?>"><?php echo esc_html($toa_terms_label); ?></a>
    <a href="<?php echo esc_url(home_url('/blog/')); ?>">Blog</a>
  </div>
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
</body>
</html>