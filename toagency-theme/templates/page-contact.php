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
    'casting_tm' => array('it' => 'Casting (talent e crew)', 'en' => 'Casting (talent &amp; crew)', 'fr' => 'Casting (talents &amp; crew)', 'es' => 'Casting (talentos y crew)'),
    'biz_b2b' => array('it' => 'Business (solo aziende e clienti B2B)', 'en' => 'Business (companies and B2B clients only)', 'fr' => 'Business (entreprises et clients B2B uniquement)', 'es' => 'Business (solo empresas y clientes B2B)'),
    'solo_wa' => array('it' => 'solo WhatsApp', 'en' => 'WhatsApp only', 'fr' => 'WhatsApp uniquement', 'es' => 'solo WhatsApp'),
    'c_it' => array('it' => 'Italia', 'en' => 'Italy', 'fr' => 'Italie', 'es' => 'Italia'),
    'c_fr' => array('it' => 'Francia', 'en' => 'France', 'fr' => 'France', 'es' => 'Francia'),
    'c_es' => array('it' => 'Spagna', 'en' => 'Spain', 'fr' => 'Espagne', 'es' => 'Espa&ntilde;a'),
    'g_b2b' => array('it' => 'Sei un\'azienda? Chiedi un preventivo', 'en' => 'Are you a company? Request a quote', 'fr' => 'Vous &ecirc;tes une entreprise ? Demandez un devis', 'es' => '&iquest;Eres una empresa? Pide presupuesto'),
    'g_work' => array('it' => 'Vuoi lavorare con noi? (talent e crew)', 'en' => 'Want to work with us? (talent &amp; crew)', 'fr' => 'Vous voulez travailler avec nous ? (talents &amp; crew)', 'es' => '&iquest;Quieres trabajar con nosotros? (talentos y crew)'),
    'g_admin' => array('it' => 'Fatture e pagamenti (solo clienti)', 'en' => 'Invoices and payments (clients only)', 'fr' => 'Factures et paiements (clients uniquement)', 'es' => 'Facturas y pagos (solo clientes)'),
    'scrivi' => array('it' => 'Scrivi email', 'en' => 'Send email', 'fr' => 'Envoyer un email', 'es' => 'Enviar email'),
    'prima_reg' => array('it' => 'Prima registrati qui', 'en' => 'Register here first', 'fr' => 'Inscrivez-vous d\'abord ici', 'es' => 'Primero reg&iacute;strate aqu&iacute;'),
    's_b2b' => array('it' => 'Richiesta preventivo', 'en' => 'Quote request', 'fr' => 'Demande de devis', 'es' => 'Solicitud de presupuesto'),
    'b_b2b' => array('it' => "Azienda:\nCittà e data dell'evento:\nCosa cerchi e quante persone:\n", 'en' => "Company:\nCity and event date:\nWhat you need and how many people:\n", 'fr' => "Entreprise :\nVille et date de l'événement :\nCe que vous cherchez et combien de personnes :\n", 'es' => "Empresa:\nCiudad y fecha del evento:\nQué buscas y cuántas personas:\n"),
    's_work' => array('it' => 'Candidatura', 'en' => 'Application', 'fr' => 'Candidature', 'es' => 'Candidatura'),
    'b_work' => array('it' => "Nome e cognome:\nCittà:\nTalent o crew (ruolo):\nInstagram o portfolio:\n", 'en' => "Full name:\nCity:\nTalent or crew (role):\nInstagram or portfolio:\n", 'fr' => "Nom et prénom :\nVille :\nTalent ou crew (rôle) :\nInstagram ou portfolio :\n", 'es' => "Nombre y apellidos:\nCiudad:\nTalento o crew (rol):\nInstagram o portfolio:\n"),
    's_admin' => array('it' => 'Fatture e pagamenti', 'en' => 'Invoices and payments', 'fr' => 'Factures et paiements', 'es' => 'Facturas y pagos'),
    'b_admin' => array('it' => "Azienda:\nCodice lavoro:\nRichiesta:\n", 'en' => "Company:\nJob code:\nRequest:\n", 'fr' => "Entreprise :\nCode du job :\nDemande :\n", 'es' => "Empresa:\nCódigo del trabajo:\nSolicitud:\n"),
    'copertura' => array('it' => 'Copertura', 'en' => 'Coverage', 'fr' => 'Couverture', 'es' => 'Cobertura'),
);

$mt = function($to, $k) use ($_t, $t) {
    return 'mailto:' . $to . '?subject=' . rawurlencode($_t($t['s_' . $k])) . '&amp;body=' . rawurlencode($_t($t['b_' . $k]));
};
$btn_style = 'padding:10px 16px;font-size:.7rem;margin:0 8px 8px 0';

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
            <p class="feature-text" style="margin-bottom:6px"><strong><?php echo $_t($t['g_b2b']); ?></strong></p>
            <p class="feature-text" style="margin-bottom:22px">
                <a class="btn-hero btn-hero-secondary" style="<?php echo $btn_style; ?>" href="<?php echo $mt('business@toagency.it', 'b2b'); ?>" title="business@toagency.it"><?php echo $_t($t['c_it']); ?></a>
                <a class="btn-hero btn-hero-secondary" style="<?php echo $btn_style; ?>" href="<?php echo $mt('france@toagency.it', 'b2b'); ?>" title="france@toagency.it"><?php echo $_t($t['c_fr']); ?></a>
                <a class="btn-hero btn-hero-secondary" style="<?php echo $btn_style; ?>" href="<?php echo $mt('espana@toagency.it', 'b2b'); ?>" title="espana@toagency.it"><?php echo $_t($t['c_es']); ?></a>
                <a class="btn-hero btn-hero-secondary" style="<?php echo $btn_style; ?>" href="<?php echo $mt('uk@toagency.it', 'b2b'); ?>" title="uk@toagency.it">UK</a>
            </p>
            <p class="feature-text" style="margin-bottom:6px"><strong><?php echo $_t($t['g_work']); ?></strong></p>
            <p class="feature-text" style="margin-bottom:22px">
                <a class="btn-hero btn-hero-secondary" style="<?php echo $btn_style; ?>" href="<?php echo $mt('casting@toagency.it', 'work'); ?>"><?php echo $_t($t['scrivi']); ?></a>
                <a href="<?php echo home_url('/collabora/'); ?>" style="color:var(--accent)"><?php echo $_t($t['prima_reg']); ?></a>
            </p>
            <p class="feature-text" style="margin-bottom:6px"><strong><?php echo $_t($t['g_admin']); ?></strong></p>
            <p class="feature-text">
                <a class="btn-hero btn-hero-secondary" style="<?php echo $btn_style; ?>" href="<?php echo $mt('accountant@toagency.it', 'admin'); ?>"><?php echo $_t($t['scrivi']); ?></a>
            </p>
        </div>
        <div class="feature-card">
            <h3 class="feature-title"><?php echo $_t($t['telefono']); ?></h3>
            <p class="feature-text">
                <span style="display:block;margin-bottom:12px"><strong><?php echo $_t($t['biz_b2b']); ?>:</strong><br><a href="tel:+393517899225" style="color:var(--accent)">+39 351 789 9225</a></span>
                <span style="display:block"><strong><?php echo $_t($t['casting_tm']); ?>:</strong><br><a href="https://wa.me/393518468516" target="_blank" style="color:var(--accent)">+39 351 846 8516</a> (<?php echo $_t($t['solo_wa']); ?>)</span>
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
                <a href="https://toagency.it/talent-database/" target="_blank" style="color:var(--accent)"><?php echo $_t($t['esplora_talenti']); ?></a><br>
                <a href="<?php echo home_url('/collabora/'); ?>" style="color:var(--accent)"><?php echo $_t($t['lavora_con_noi']); ?></a>
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
            <p>12 rue Grecourt, 37000 Tours<br>france@toagency.it</p>
        </div>
        <div class="coverage-country">
            <h4>Espa&ntilde;a — Madrid</h4>
            <p><?php echo $_t($t['sede_attiva']); ?><br><?php echo $_t($t['copertura']); ?>: Madrid, Barcelona, Valencia<br>espana@toagency.it</p>
        </div>
        <div class="coverage-country">
            <h4>UK — London</h4>
            <p><?php echo $_t($t['sede_attiva']); ?><br><?php echo $_t($t['copertura']); ?>: London, Manchester<br>uk@toagency.it</p>
        </div>
    </div>
</section>

<?php toa_component('footer'); ?>
