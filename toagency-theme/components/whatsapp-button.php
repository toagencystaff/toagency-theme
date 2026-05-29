<?php
/**
 * Component: WhatsApp sticky button
 * Bottone fisso bottom-right con link wa.me precompilato per lingua.
 * Usage: toa_component('whatsapp-button')  (incluso dal footer su tutte le pagine)
 *
 * i18n via _t_raw() (helper globale in functions.php) — NON _ht(), che è locale a page-home.php.
 * Icona WhatsApp SVG inline (no Font Awesome).
 * Su mobile si posiziona sopra .sticky-cta-mobile (footer) per non sovrapporsi.
 */
$wa_number = '393517899225';

$wa_texts = array(
    'it' => 'Ciao TOAgency, vorrei informazioni su [servizio]',
    'en' => "Hi TOAgency, I'd like information about [service]",
    'fr' => 'Bonjour TOAgency, je voudrais des informations sur [service]',
    'es' => 'Hola TOAgency, me gustaría información sobre [servicio]',
);

$wa_aria = array(
    'it' => 'Contattaci su WhatsApp',
    'en' => 'Contact us on WhatsApp',
    'fr' => 'Contactez-nous sur WhatsApp',
    'es' => 'Contáctanos por WhatsApp',
);

$wa_lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
if (!in_array($wa_lang, array('it', 'en', 'fr', 'es'))) $wa_lang = 'it';

$wa_text = isset($wa_texts[$wa_lang]) ? $wa_texts[$wa_lang] : $wa_texts['it'];
$wa_href = 'https://wa.me/' . $wa_number . '?text=' . rawurlencode($wa_text);
?>
<a href="<?php echo esc_url($wa_href); ?>" class="toa-whatsapp-btn" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr(_t_raw($wa_aria)); ?>">
  <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
    <path d="M16.04 3C9.4 3 4 8.4 4 15.04c0 2.12.56 4.18 1.6 6L4 29l8.16-1.56c1.74.95 3.7 1.45 5.7 1.45h.01c6.64 0 12.04-5.4 12.04-12.04C29.9 8.4 24.5 3 16.04 3zm0 21.9h-.01c-1.78 0-3.52-.48-5.04-1.38l-.36-.21-3.74.98 1-3.65-.24-.37a9.86 9.86 0 0 1-1.51-5.26c0-5.46 4.45-9.9 9.92-9.9 2.65 0 5.14 1.03 7.01 2.9a9.84 9.84 0 0 1 2.9 7.01c0 5.47-4.45 9.92-9.93 9.92zm5.44-7.42c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.94 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.69.62.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.41-.07-.13-.27-.2-.57-.35z"/>
  </svg>
</a>
<style>
.toa-whatsapp-btn {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 9999;
  width: 56px;
  height: 56px;
  border-radius: 50%;
  background: #25D366;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.25);
  transition: background 0.2s ease, transform 0.2s ease;
}
.toa-whatsapp-btn:hover {
  background: #1da851;
  transform: scale(1.05);
}
.toa-whatsapp-btn svg {
  width: 32px;
  height: 32px;
  fill: #fff;
}
/* Mobile: tap-target 60x60, alzato sopra .sticky-cta-mobile (bottom:16px) per non sovrapporsi */
@media (max-width: 768px) {
  .toa-whatsapp-btn {
    width: 60px;
    height: 60px;
    bottom: 88px;
    right: 16px;
  }
  .toa-whatsapp-btn svg {
    width: 34px;
    height: 34px;
  }
}
</style>
