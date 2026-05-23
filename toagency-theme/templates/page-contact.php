<?php
/**
 * Template Name: Contact
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Italia, Francia, Spagna e UK.<br>Scrivici o chiamaci: risposta rapida garantita. Supporto in 5 lingue, 7 giorni su 7.',
        'en' => 'Italy, France, Spain and UK.<br>Write or call us: quick response guaranteed. Support in 5 languages, 7 days a week.',
        'fr' => 'Italie, France, Espagne et UK.<br>&Eacute;crivez-nous ou appelez-nous : r&eacute;ponse rapide garantie. Support en 5 langues, 7 jours sur 7.',
        'es' => 'Italia, Francia, Espa&ntilde;a y UK.<br>Escr&iacute;benos o ll&aacute;manos: respuesta r&aacute;pida garantizada. Soporte en 5 idiomas, 7 d&iacute;as a la semana.',
    ),
    'telefono' => array('it' => 'Telefono', 'en' => 'Phone', 'fr' => 'T&eacute;l&eacute;phone', 'es' => 'Tel&eacute;fono'),
    'amministrazione' => array('it' => 'Amministrazione', 'en' => 'Administration', 'fr' => 'Administration', 'es' => 'Administraci&oacute;n'),
    'quick_links' => array('it' => 'Quick Links', 'en' => 'Quick Links', 'fr' => 'Liens rapides', 'es' => 'Enlaces r&aacute;pidos'),
    'richiedi_prev' => array('it' => 'Richiedi Preventivo', 'en' => 'Request a Quote', 'fr' => 'Demander un devis', 'es' => 'Solicitar presupuesto'),
    'esplora_talenti' => array('it' => 'Esplora Talenti', 'en' => 'Explore Talents', 'fr' => 'Explorer les talents', 'es' => 'Explorar talentos'),
    'lavora_con_noi' => array('it' => 'Lavora con Noi', 'en' => 'Work with Us', 'fr' => 'Travaillez avec nous', 'es' => 'Trabaja con nosotros'),
    'cta_title' => array('it' => 'Contattaci subito', 'en' => 'Contact us now', 'fr' => 'Contactez-nous maintenant', 'es' => 'Cont&aacute;ctanos ahora'),
    'cta_subtitle' => array(
        'it' => 'Scegli il canale che preferisci &mdash; risposta garantita entro 2 ore.',
        'en' => 'Choose your preferred channel &mdash; guaranteed response within 2 hours.',
        'fr' => 'Choisissez votre canal pr&eacute;f&eacute;r&eacute; &mdash; r&eacute;ponse garantie sous 2 heures.',
        'es' => 'Elige el canal que prefieras &mdash; respuesta garantizada en 2 horas.',
    ),
    'form_online' => array('it' => 'Form online', 'en' => 'Online form', 'fr' => 'Formulaire en ligne', 'es' => 'Formulario online'),
    'chiamaci' => array('it' => 'Chiamaci', 'en' => 'Call us', 'fr' => 'Appelez-nous', 'es' => 'Ll&aacute;manos'),
    'sedi_eyebrow' => array('it' => 'Le nostre sedi', 'en' => 'Our offices', 'fr' => 'Nos bureaux', 'es' => 'Nuestras oficinas'),
    'sedi_heading' => array('it' => 'Dove trovarci', 'en' => 'Where to find us', 'fr' => 'O&ugrave; nous trouver', 'es' => 'D&oacute;nde encontrarnos'),
    'sede_legale' => array('it' => 'Sede legale', 'en' => 'Registered office', 'fr' => 'Si&egrave;ge social', 'es' => 'Sede legal'),
    'ufficio_op' => array('it' => 'Ufficio operativo', 'en' => 'Operational office', 'fr' => 'Bureau op&eacute;rationnel', 'es' => 'Oficina operativa'),
    'sede_attiva' => array('it' => 'Sede operativa attiva', 'en' => 'Active operational office', 'fr' => 'Bureau op&eacute;rationnel actif', 'es' => 'Oficina operativa activa'),
    'copertura' => array('it' => 'Copertura', 'en' => 'Coverage', 'fr' => 'Couverture', 'es' => 'Cobertura'),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => _t_raw(array('it'=>'CONTATTI','en'=>'CONTACTS','fr'=>'CONTACT','es'=>'CONTACTO')),
    'title'      => _t_raw(array('it'=>'Contatti.','en'=>'Contacts.','fr'=>'Contact.','es'=>'Contacto.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Contact Grid -->
<section class="why-section" style="padding-bottom:40px">
    <div class="features-grid">
        <div class="feature-card">
            <h3 class="feature-title">Email</h3>
            <p class="feature-text">
                <strong>Business:</strong> <a href="mailto:business@toagency.it" style="color:var(--accent)">business@toagency.it</a><br>
                <strong>Info:</strong> <a href="mailto:info@toagency.it" style="color:var(--accent)">info@toagency.it</a><br>
                <strong><?php echo $_t($t['amministrazione']); ?>:</strong> <a href="mailto:accountant@toagency.it" style="color:var(--accent)">accountant@toagency.it</a>
            </p>
        </div>
        <div class="feature-card">
            <h3 class="feature-title"><?php echo $_t($t['telefono']); ?></h3>
            <p class="feature-text">
                <strong>Italia:</strong> <a href="tel:+393517899225" style="color:var(--accent)">+39 351 789 9225</a><br>
                <strong>Francia:</strong> <a href="tel:+33616133368" style="color:var(--accent)">+33 6 16 13 33 68</a><br>
                <strong>WhatsApp:</strong> <a href="https://wa.me/393517899225" style="color:var(--accent)">+39 351 789 9225</a>
            </p>
        </div>
        <div class="feature-card">
            <h3 class="feature-title">Social</h3>
            <p class="feature-text">
                <strong>Instagram:</strong> <a href="https://instagram.com/toagency" target="_blank" style="color:var(--accent)">@toagency</a><br>
                <strong>TikTok:</strong> <a href="https://tiktok.com/@toagency" target="_blank" style="color:var(--accent)">@toagency</a><br>
                <strong>LinkedIn:</strong> <a href="https://linkedin.com/company/toagency" target="_blank" style="color:var(--accent)">TOAgency</a>
            </p>
        </div>
        <div class="feature-card">
            <h3 class="feature-title"><?php echo $_t($t['quick_links']); ?></h3>
            <p class="feature-text">
                <a href="<?php echo home_url('/form-b2b/'); ?>" style="color:var(--accent)"><?php echo $_t($t['richiedi_prev']); ?></a><br>
                <a href="https://toadatabase.it/it/talent/" target="_blank" style="color:var(--accent)"><?php echo $_t($t['esplora_talenti']); ?></a><br>
                <a href="<?php echo home_url('/b-t-l/'); ?>" style="color:var(--accent)"><?php echo $_t($t['lavora_con_noi']); ?></a>
            </p>
        </div>
    </div>
</section>

<?php toa_component('cta-buttons', array(
    'title'    => $_t($t['cta_title']),
    'subtitle' => $_t($t['cta_subtitle']),
    'buttons'  => array(
        array('url' => home_url('/form-b2b/'), 'text' => $_t($t['form_online']), 'primary' => true),
        array('url' => 'https://wa.me/393517899225', 'text' => 'WhatsApp', 'target' => '_blank'),
        array('url' => 'tel:+393517899225', 'text' => $_t($t['chiamaci'])),
    ),
)); ?>

<!-- Sedi -->
<section class="coverage-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['sedi_eyebrow']); ?></div>
        <h2 class="section-heading" style="margin-bottom:40px"><?php echo $_t($t['sedi_heading']); ?></h2>
    </div>
    <div class="coverage-grid container">
        <div class="coverage-country">
            <h4>Italia — Torino</h4>
            <p><strong><?php echo $_t($t['sede_legale']); ?>:</strong> Via Cavour 21, 10123 Torino<br><strong><?php echo $_t($t['ufficio_op']); ?>:</strong> Via Pomba 29, 10123 Torino<br>Tel: +39 351 789 9225</p>
        </div>
        <div class="coverage-country">
            <h4>Francia</h4>
            <p>12 rue Grecourt, 37000 Tours<br>Tel: +33 6 16 13 33 68<br>france@toagency.it</p>
        </div>
        <div class="coverage-country">
            <h4>Espa&ntilde;a — Madrid</h4>
            <p><?php echo $_t($t['sede_attiva']); ?><br><?php echo $_t($t['copertura']); ?>: Madrid, Barcelona, Valencia<br>spain@toagency.it</p>
        </div>
        <div class="coverage-country">
            <h4>UK — London</h4>
            <p><?php echo $_t($t['sede_attiva']); ?><br><?php echo $_t($t['copertura']); ?>: London, Manchester<br>uk@toagency.it</p>
        </div>
    </div>
</section>

<?php toa_component('footer'); ?>
