<?php
/**
 * Template Name: Visuals / Production
 */
$lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
$_t = function($a) use ($lang) { return isset($a[$lang]) ? $a[$lang] : $a['it']; };

$t = array(
    'hero_subtitle' => array(
        'it' => 'Manda il materiale, il nostro team prepara foto e video.<br>Contenuti ottimizzati per e-commerce, social media e campagne ADV. Anche con AI.',
        'en' => 'Send us the material, our team prepares photos and videos.<br>Content optimised for e-commerce, social media and ADV campaigns. AI-enhanced too.',
        'fr' => 'Envoyez le mat&eacute;riel, notre &eacute;quipe pr&eacute;pare photos et vid&eacute;os.<br>Contenus optimis&eacute;s pour e-commerce, r&eacute;seaux sociaux et campagnes ADV. Aussi avec IA.',
        'es' => 'Env&iacute;a el material, nuestro equipo prepara fotos y v&iacute;deos.<br>Contenidos optimizados para e-commerce, redes sociales y campa&ntilde;as ADV. Tambi&eacute;n con IA.',
    ),
    'svc_foto' => array('it' => 'Foto', 'en' => 'Photo', 'fr' => 'Photo', 'es' => 'Foto'),
    'svc_foto_desc' => array('it' => 'Still life, flat lay, indossato', 'en' => 'Still life, flat lay, on-model', 'fr' => 'Packshot, flat lay, port&eacute;', 'es' => 'Still life, flat lay, con modelo'),
    'svc_video' => array('it' => 'Video', 'en' => 'Video', 'fr' => 'Vid&eacute;o', 'es' => 'V&iacute;deo'),
    'svc_video_desc' => array('it' => 'Prodotto, tutorial, unboxing', 'en' => 'Product, tutorial, unboxing', 'fr' => 'Produit, tutoriel, unboxing', 'es' => 'Producto, tutorial, unboxing'),
    'svc_social_desc' => array('it' => 'Reels, TikTok, Stories', 'en' => 'Reels, TikTok, Stories', 'fr' => 'Reels, TikTok, Stories', 'es' => 'Reels, TikTok, Stories'),
    'svc_ecom_desc' => array('it' => 'Amazon, Zalando, Shopify', 'en' => 'Amazon, Zalando, Shopify', 'fr' => 'Amazon, Zalando, Shopify', 'es' => 'Amazon, Zalando, Shopify'),
    'svc_ai_desc' => array('it' => 'Sfondi, varianti, enhancement', 'en' => 'Backgrounds, variants, enhancement', 'fr' => 'Fonds, variantes, am&eacute;lioration', 'es' => 'Fondos, variantes, mejora'),
    'svc_postprod' => array('it' => 'Post-Prod', 'en' => 'Post-Prod', 'fr' => 'Post-Prod', 'es' => 'Post-Prod'),
    'svc_postprod_desc' => array('it' => 'Montaggio, color, motion', 'en' => 'Editing, colour, motion', 'fr' => 'Montage, couleur, motion', 'es' => 'Edici&oacute;n, color, motion'),
    'cta_heading' => array('it' => 'Inizia il tuo progetto', 'en' => 'Start your project', 'fr' => 'Lancez votre projet', 'es' => 'Empieza tu proyecto'),
    'cta_subtitle' => array(
        'it' => 'Mandaci il materiale o descrivi cosa ti serve &mdash; prepariamo tutto in 48-72 ore',
        'en' => 'Send us the material or describe what you need &mdash; we prepare everything in 48-72 hours',
        'fr' => 'Envoyez-nous le mat&eacute;riel ou d&eacute;crivez vos besoins &mdash; nous pr&eacute;parons tout en 48-72 heures',
        'es' => 'Env&iacute;anos el material o describe lo que necesitas &mdash; preparamos todo en 48-72 horas',
    ),
    'cta_email' => array('it' => 'Manda il materiale &mdash; Email', 'en' => 'Send material &mdash; Email', 'fr' => 'Envoyer le mat&eacute;riel &mdash; Email', 'es' => 'Enviar material &mdash; Email'),
    'cta_chiamaci' => array('it' => 'Chiamaci', 'en' => 'Call us', 'fr' => 'Appelez-nous', 'es' => 'Ll&aacute;manos'),
    'how1' => array('it' => 'MANDI IL MATERIALE', 'en' => 'SEND MATERIAL', 'fr' => 'ENVOYEZ LE MAT&Eacute;RIEL', 'es' => 'ENV&Iacute;A EL MATERIAL'),
    'how2' => array('it' => 'ANALIZZIAMO', 'en' => 'WE ANALYSE', 'fr' => 'NOUS ANALYSONS', 'es' => 'ANALIZAMOS'),
    'how3' => array('it' => 'PRODUCIAMO', 'en' => 'WE PRODUCE', 'fr' => 'NOUS PRODUISONS', 'es' => 'PRODUCIMOS'),
    'how4' => array('it' => 'CONSEGNA 48H', 'en' => 'DELIVERY 48H', 'fr' => 'LIVRAISON 48H', 'es' => 'ENTREGA 48H'),
    'how_tagline' => array(
        'it' => 'Team interno &bull; Post-produzione inclusa &bull; AI enhanced &bull; Consegna cloud',
        'en' => 'In-house team &bull; Post-production included &bull; AI enhanced &bull; Cloud delivery',
        'fr' => '&Eacute;quipe interne &bull; Post-production incluse &bull; IA avanc&eacute;e &bull; Livraison cloud',
        'es' => 'Equipo interno &bull; Postproducci&oacute;n incluida &bull; IA avanzada &bull; Entrega cloud',
    ),
    'listino_eyebrow' => array('it' => 'Listino base e-commerce', 'en' => 'E-commerce base price list', 'fr' => 'Tarifs de base e-commerce', 'es' => 'Lista de precios base e-commerce'),
    'listino_heading' => array('it' => 'Prezzi trasparenti', 'en' => 'Transparent pricing', 'fr' => 'Prix transparents', 'es' => 'Precios transparentes'),
    'th_servizio' => array('it' => 'Servizio', 'en' => 'Service', 'fr' => 'Service', 'es' => 'Servicio'),
    'th_prezzo' => array('it' => 'Prezzo', 'en' => 'Price', 'fr' => 'Prix', 'es' => 'Precio'),
    'row_still' => array('it' => 'Still Life base', 'en' => 'Basic Still Life', 'fr' => 'Packshot de base', 'es' => 'Still Life b&aacute;sico'),
    'row_flatlay' => array('it' => 'Flat Lay', 'en' => 'Flat Lay', 'fr' => 'Flat Lay', 'es' => 'Flat Lay'),
    'row_ghost' => array('it' => 'Ghost Mannequin', 'en' => 'Ghost Mannequin', 'fr' => 'Ghost Mannequin', 'es' => 'Ghost Mannequin'),
    'row_indossato' => array('it' => 'Indossato studio', 'en' => 'Studio on-model', 'fr' => 'Port&eacute; en studio', 'es' => 'Con modelo en estudio'),
    'row_lifestyle' => array('it' => 'Ambientato / Lifestyle', 'en' => 'Lifestyle / On-location', 'fr' => 'Ambiance / Lifestyle', 'es' => 'Ambientado / Lifestyle'),
    'row_vidprod' => array('it' => 'Video prodotto', 'en' => 'Product video', 'fr' => 'Vid&eacute;o produit', 'es' => 'V&iacute;deo de producto'),
    'row_reel' => array('it' => 'Reel / TikTok', 'en' => 'Reel / TikTok', 'fr' => 'Reel / TikTok', 'es' => 'Reel / TikTok'),
    'price_note' => array(
        'it' => 'Post-produzione inclusa. Prezzi per volumi su richiesta.',
        'en' => 'Post-production included. Volume pricing on request.',
        'fr' => 'Post-production incluse. Prix pour volumes sur demande.',
        'es' => 'Postproducci&oacute;n incluida. Precios por volumen bajo solicitud.',
    ),
);

toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => _t_raw(array('it'=>'PRODUZIONI','en'=>'PRODUCTION','fr'=>'PRODUCTION','es'=>'PRODUCCIÓN')),
    'title'      => _t_raw(array('it'=>'Produzioni.','en'=>'Production.','fr'=>'Production.','es'=>'Producción.')),
    'subtitle'   => $_t($t['hero_subtitle']),
)); ?>

<!-- Services Icons -->
<section class="services-section" style="padding-top:40px">
    <div class="services-grid">
        <div class="service-card"><div class="service-icon">📸</div><div class="service-name"><?php echo $_t($t['svc_foto']); ?></div><div class="service-desc"><?php echo $_t($t['svc_foto_desc']); ?></div></div>
        <div class="service-card"><div class="service-icon">🎬</div><div class="service-name"><?php echo $_t($t['svc_video']); ?></div><div class="service-desc"><?php echo $_t($t['svc_video_desc']); ?></div></div>
        <div class="service-card"><div class="service-icon">📱</div><div class="service-name">Social</div><div class="service-desc"><?php echo $_t($t['svc_social_desc']); ?></div></div>
        <div class="service-card"><div class="service-icon">🛍️</div><div class="service-name">E-Commerce</div><div class="service-desc"><?php echo $_t($t['svc_ecom_desc']); ?></div></div>
        <div class="service-card"><div class="service-icon">🤖</div><div class="service-name">AI Enhanced</div><div class="service-desc"><?php echo $_t($t['svc_ai_desc']); ?></div></div>
        <div class="service-card"><div class="service-icon">🎨</div><div class="service-name"><?php echo $_t($t['svc_postprod']); ?></div><div class="service-desc"><?php echo $_t($t['svc_postprod_desc']); ?></div></div>
    </div>
</section>

<!-- CTA contatto -->
<section class="split-cta">
    <div class="container">
        <h2 class="section-heading" style="text-align:center;margin:0 auto 16px"><?php echo $_t($t['cta_heading']); ?></h2>
        <p class="cta-subtitle"><?php echo $_t($t['cta_subtitle']); ?></p>
        <div class="cta-buttons-row">
            <a href="mailto:business@toagency.it" class="btn-hero btn-hero-primary"><span><?php echo $_t($t['cta_email']); ?></span></a>
            <a href="https://wa.me/393517899225" target="_blank" class="btn-hero btn-hero-secondary"><span>WhatsApp</span></a>
            <a href="tel:+393517899225" class="btn-hero btn-hero-secondary"><span><?php echo $_t($t['cta_chiamaci']); ?></span></a>
        </div>
    </div>
</section>

<?php toa_component('how-it-works', array(
    'steps' => array(
        array('time' => '1', 'label' => $_t($t['how1'])),
        array('time' => '2', 'label' => $_t($t['how2'])),
        array('time' => '3', 'label' => $_t($t['how3'])),
        array('time' => '4', 'label' => $_t($t['how4'])),
    ),
    'tagline' => $_t($t['how_tagline']),
)); ?>

<!-- Listino -->
<section class="why-section">
    <div class="container">
        <div class="section-eyebrow"><?php echo $_t($t['listino_eyebrow']); ?></div>
        <h2 class="section-heading"><?php echo $_t($t['listino_heading']); ?></h2>
        <div class="price-table">
            <table>
                <thead><tr><th><?php echo $_t($t['th_servizio']); ?></th><th><?php echo $_t($t['th_prezzo']); ?></th></tr></thead>
                <tbody>
                    <tr><td><?php echo $_t($t['row_still']); ?></td><td>&euro;5 – &euro;10 / foto</td></tr>
                    <tr><td><?php echo $_t($t['row_flatlay']); ?></td><td>&euro;8 – &euro;15 / foto</td></tr>
                    <tr><td><?php echo $_t($t['row_ghost']); ?></td><td>&euro;12 – &euro;20 / foto</td></tr>
                    <tr><td><?php echo $_t($t['row_indossato']); ?></td><td>&euro;15 – &euro;30 / foto</td></tr>
                    <tr><td><?php echo $_t($t['row_lifestyle']); ?></td><td>&euro;20 – &euro;50 / foto</td></tr>
                    <tr><td><?php echo $_t($t['row_vidprod']); ?></td><td>&euro;200 – &euro;500 / video</td></tr>
                    <tr><td><?php echo $_t($t['row_reel']); ?></td><td>&euro;150 – &euro;300 / video</td></tr>
                </tbody>
            </table>
            <p class="price-note"><?php echo $_t($t['price_note']); ?></p>
        </div>
    </div>
</section>

<?php toa_component('footer'); ?>
