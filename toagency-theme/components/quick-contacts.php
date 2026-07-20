<?php
/**
 * Component: Quick Contacts
 * Usage: toa_component('quick-contacts')
 */
?>
<div class="quick-contacts">
  <a href="tel:+393517899225" onclick="toaTrackContact('phone','quick-contacts')">Chiama</a>
  <a href="https://wa.me/393517899225" target="_blank" rel="noopener" onclick="toaTrackContact('whatsapp','quick-contacts')">WhatsApp</a>
  <a href="mailto:business@toagency.it" onclick="toaTrackContact('email','quick-contacts')">Email</a>
  <a href="https://toagency.it/talent-database/" target="_blank" rel="noopener" onclick="toaTrackContact('database','quick-contacts')">Database</a>
</div>
<script>
/* Tracciamento click contatti - evento unico gtag + dataLayer + fbq (guardato per non ridefinire) */
window.toaTrackContact = window.toaTrackContact || function(channel, location){
  try {
    if (typeof gtag === 'function') {
      gtag('event', 'contact_click', {contact_channel: channel, contact_location: location || ''});
    }
    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({event: 'contact_click', contact_channel: channel, contact_location: location || ''});
    if (typeof fbq === 'function') { fbq('trackCustom', 'ContactClick', {channel: channel, location: location || ''}); }
  } catch(e){}
};
</script>
