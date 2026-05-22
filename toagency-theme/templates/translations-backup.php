<?php
/**
 * TOAgency Centralized Translations
 *
 * Usage in templates:
 *   $lang = toa_current_lang();
 *   echo toa_t('about', 'hero_subtitle');
 *
 * toa_t() automatically detects the current WPML language.
 * Falls back to Italian ('it') if translation is missing.
 */

if (!function_exists('toa_t')) {
    function toa_t($page, $key) {
        static $translations = null;
        if ($translations === null) {
            $translations = toa_get_translations();
        }
        $lang = function_exists('toa_current_lang') ? toa_current_lang() : 'it';
        if (isset($translations[$page][$key][$lang])) {
            return $translations[$page][$key][$lang];
        }
        // Fallback to Italian
        if (isset($translations[$page][$key]['it'])) {
            return $translations[$page][$key]['it'];
        }
        return "[$page.$key]"; // Debug: missing translation
    }
}

if (!function_exists('toa_get_translations')) {
    function toa_get_translations() {
        return array(

            // ═══════════════════════════════════════
            // SHARED / COMMON STRINGS
            // ═══════════════════════════════════════
            'common' => array(
                'cta_preventivo' => array(
                    'it' => 'Richiedi preventivo',
                    'en' => 'Request a quote',
                    'fr' => 'Demander un devis',
                    'es' => 'Solicitar presupuesto',
                ),
                'cta_whatsapp' => array(
                    'it' => 'WhatsApp',
                    'en' => 'WhatsApp',
                    'fr' => 'WhatsApp',
                    'es' => 'WhatsApp',
                ),
                'cta_email' => array(
                    'it' => 'Email',
                    'en' => 'Email',
                    'fr' => 'Email',
                    'es' => 'Email',
                ),
                'cta_compila_form' => array(
                    'it' => 'Compila il form',
                    'en' => 'Fill out the form',
                    'fr' => 'Remplir le formulaire',
                    'es' => 'Rellenar el formulario',
                ),
                'how_step_richiesta' => array(
                    'it' => 'RICHIESTA',
                    'en' => 'REQUEST',
                    'fr' => 'DEMANDE',
                    'es' => 'SOLICITUD',
                ),
                'how_step_analisi' => array(
                    'it' => 'ANALISI',
                    'en' => 'ANALYSIS',
                    'fr' => 'ANALYSE',
                    'es' => 'ANÁLISIS',
                ),
                'how_step_proposte' => array(
                    'it' => 'PROPOSTE',
                    'en' => 'PROPOSALS',
                    'fr' => 'PROPOSITIONS',
                    'es' => 'PROPUESTAS',
                ),
                'how_step_gestione' => array(
                    'it' => 'GESTIONE',
                    'en' => 'MANAGEMENT',
                    'fr' => 'GESTION',
                    'es' => 'GESTIÓN',
                ),
                'how_tagline' => array(
                    'it' => '20.000+ professionisti &bull; Preventivi in 2 ore &bull; Gestione completa &bull; Fatturazione chiara',
                    'en' => '20,000+ professionals &bull; Quotes in 2 hours &bull; Full management &bull; Clear invoicing',
                    'fr' => '20 000+ professionnels &bull; Devis en 2 heures &bull; Gestion compl&egrave;te &bull; Facturation transparente',
                    'es' => '20.000+ profesionales &bull; Presupuestos en 2 horas &bull; Gesti&oacute;n completa &bull; Facturaci&oacute;n clara',
                ),
            ),

            // ═══════════════════════════════════════
            // ABOUT PAGE
            // ═══════════════════════════════════════
            'about' => array(
                'hero_subtitle' => array(
                    'it' => 'Agenzia internazionale di casting e produzione.<br>Dal 2009 al servizio dei talenti e delle aziende.',
                    'en' => 'International casting and production agency.<br>Since 2009, serving talents and businesses.',
                    'fr' => 'Agence internationale de casting et production.<br>Depuis 2009, au service des talents et des entreprises.',
                    'es' => 'Agencia internacional de casting y producci&oacute;n.<br>Desde 2009, al servicio de los talentos y las empresas.',
                ),
                'stat_anni' => array(
                    'it' => 'Anni esperienza',
                    'en' => 'Years experience',
                    'fr' => 'Ans d\'exp&eacute;rience',
                    'es' => 'A&ntilde;os de experiencia',
                ),
                'stat_professionisti' => array(
                    'it' => 'Professionisti',
                    'en' => 'Professionals',
                    'fr' => 'Professionnels',
                    'es' => 'Profesionales',
                ),
                'stat_citta' => array(
                    'it' => 'Citt&agrave; coperte',
                    'en' => 'Cities covered',
                    'fr' => 'Villes couvertes',
                    'es' => 'Ciudades cubiertas',
                ),
                'stat_progetti' => array(
                    'it' => 'Progetti realizzati',
                    'en' => 'Projects completed',
                    'fr' => 'Projets r&eacute;alis&eacute;s',
                    'es' => 'Proyectos realizados',
                ),
                'storia_eyebrow' => array(
                    'it' => 'La nostra storia',
                    'en' => 'Our story',
                    'fr' => 'Notre histoire',
                    'es' => 'Nuestra historia',
                ),
                'storia_heading' => array(
                    'it' => 'Dal 2009, cresciamo con i nostri talenti',
                    'en' => 'Since 2009, growing with our talents',
                    'fr' => 'Depuis 2009, nous grandissons avec nos talents',
                    'es' => 'Desde 2009, crecemos con nuestros talentos',
                ),
                'timeline_2009' => array(
                    'it' => 'Fondazione dell\'agenzia a Torino, primi casting per eventi locali',
                    'en' => 'Agency founded in Turin, first castings for local events',
                    'fr' => 'Fondation de l\'agence &agrave; Turin, premiers castings pour &eacute;v&eacute;nements locaux',
                    'es' => 'Fundaci&oacute;n de la agencia en Tur&iacute;n, primeros castings para eventos locales',
                ),
                'timeline_2015' => array(
                    'it' => 'Espansione nazionale, apertura collaborazioni Milano e Roma',
                    'en' => 'National expansion, partnerships opened in Milan and Rome',
                    'fr' => 'Expansion nationale, ouverture de collaborations &agrave; Milan et Rome',
                    'es' => 'Expansi&oacute;n nacional, apertura de colaboraciones en Mil&aacute;n y Roma',
                ),
                'timeline_2021' => array(
                    'it' => 'Apertura sede operativa a Parigi, espansione internazionale',
                    'en' => 'Paris office opened, international expansion',
                    'fr' => 'Ouverture du bureau de Paris, expansion internationale',
                    'es' => 'Apertura de la oficina de Par&iacute;s, expansi&oacute;n internacional',
                ),
                'timeline_2023' => array(
                    'it' => 'Lancio piattaforma digitale e servizi produzione e-commerce',
                    'en' => 'Digital platform launch and e-commerce production services',
                    'fr' => 'Lancement de la plateforme num&eacute;rique et services de production e-commerce',
                    'es' => 'Lanzamiento de la plataforma digital y servicios de producci&oacute;n e-commerce',
                ),
                'timeline_2024' => array(
                    'it' => 'Apertura UK e Spagna, oltre 20.000 professionisti nel network',
                    'en' => 'UK and Spain expansion, over 20,000 professionals in the network',
                    'fr' => 'Ouverture UK et Espagne, plus de 20 000 professionnels dans le r&eacute;seau',
                    'es' => 'Apertura en UK y Espa&ntilde;a, m&aacute;s de 20.000 profesionales en la red',
                ),
                'valori_eyebrow' => array(
                    'it' => 'I nostri valori',
                    'en' => 'Our values',
                    'fr' => 'Nos valeurs',
                    'es' => 'Nuestros valores',
                ),
                'valori_heading' => array(
                    'it' => 'Cosa ci guida',
                    'en' => 'What drives us',
                    'fr' => 'Ce qui nous guide',
                    'es' => 'Lo que nos gu&iacute;a',
                ),
                'valore1_title' => array(
                    'it' => 'Inclusivit&agrave;',
                    'en' => 'Inclusivity',
                    'fr' => 'Inclusivit&eacute;',
                    'es' => 'Inclusividad',
                ),
                'valore1_text' => array(
                    'it' => 'Valorizziamo ogni tipo di bellezza, et&agrave;, etnia e stile. Il talento non ha stereotipi.',
                    'en' => 'We value every type of beauty, age, ethnicity and style. Talent has no stereotypes.',
                    'fr' => 'Nous valorisons chaque type de beaut&eacute;, &acirc;ge, ethnie et style. Le talent n\'a pas de st&eacute;r&eacute;otypes.',
                    'es' => 'Valoramos todo tipo de belleza, edad, etnia y estilo. El talento no tiene estereotipos.',
                ),
                'valore2_title' => array(
                    'it' => 'Professionalit&agrave;',
                    'en' => 'Professionalism',
                    'fr' => 'Professionnalisme',
                    'es' => 'Profesionalidad',
                ),
                'valore2_text' => array(
                    'it' => 'Standard elevati in ogni fase del processo. Contratti chiari, pagamenti puntuali.',
                    'en' => 'High standards at every stage of the process. Clear contracts, punctual payments.',
                    'fr' => 'Standards &eacute;lev&eacute;s &agrave; chaque &eacute;tape du processus. Contrats clairs, paiements ponctuels.',
                    'es' => 'Est&aacute;ndares elevados en cada fase del proceso. Contratos claros, pagos puntuales.',
                ),
                'valore3_title' => array(
                    'it' => 'Innovazione',
                    'en' => 'Innovation',
                    'fr' => 'Innovation',
                    'es' => 'Innovaci&oacute;n',
                ),
                'valore3_text' => array(
                    'it' => 'Tecnologia al servizio della creativit&agrave;. Piattaforma digitale, AI matching, processi digitalizzati.',
                    'en' => 'Technology at the service of creativity. Digital platform, AI matching, digitized processes.',
                    'fr' => 'La technologie au service de la cr&eacute;ativit&eacute;. Plateforme num&eacute;rique, AI matching, processus digitalis&eacute;s.',
                    'es' => 'Tecnolog&iacute;a al servicio de la creatividad. Plataforma digital, AI matching, procesos digitalizados.',
                ),
                'valore4_title' => array(
                    'it' => 'Trasparenza',
                    'en' => 'Transparency',
                    'fr' => 'Transparence',
                    'es' => 'Transparencia',
                ),
                'valore4_text' => array(
                    'it' => 'Comunicazione chiara, preventivi dettagliati, nessun costo nascosto. Mai.',
                    'en' => 'Clear communication, detailed quotes, no hidden costs. Ever.',
                    'fr' => 'Communication claire, devis d&eacute;taill&eacute;s, aucun co&ucirc;t cach&eacute;. Jamais.',
                    'es' => 'Comunicaci&oacute;n clara, presupuestos detallados, sin costes ocultos. Nunca.',
                ),
                'sedi_eyebrow' => array(
                    'it' => 'Le nostre sedi',
                    'en' => 'Our offices',
                    'fr' => 'Nos bureaux',
                    'es' => 'Nuestras oficinas',
                ),
                'sedi_heading' => array(
                    'it' => 'Presenti in 4 paesi',
                    'en' => 'Present in 4 countries',
                    'fr' => 'Pr&eacute;sents dans 4 pays',
                    'es' => 'Presentes en 4 pa&iacute;ses',
                ),
                'cta_title' => array(
                    'it' => 'Inizia subito',
                    'en' => 'Get started now',
                    'fr' => 'Commencez maintenant',
                    'es' => 'Empieza ahora',
                ),
                'cta_subtitle' => array(
                    'it' => 'Che tu sia un\'azienda o un talento, abbiamo la soluzione giusta per te.',
                    'en' => 'Whether you\'re a business or a talent, we have the right solution for you.',
                    'fr' => 'Que vous soyez une entreprise ou un talent, nous avons la solution adapt&eacute;e pour vous.',
                    'es' => 'Ya seas una empresa o un talento, tenemos la soluci&oacute;n adecuada para ti.',
                ),
            ),

            // ═══════════════════════════════════════
            // SERVICES PAGE
            // ═══════════════════════════════════════
            'services' => array(
                'hero_subtitle' => array(
                    'it' => 'Servizi completi per aziende, agenzie, marchi e produzioni.<br>Talent con immagine: modelli, hostess, steward, attori e creator.',
                    'en' => 'Complete services for companies, agencies, brands and productions.<br>Image talent: models, hostesses, stewards, actors and creators.',
                    'fr' => 'Services complets pour entreprises, agences, marques et productions.<br>Talents avec image : mannequins, h&ocirc;tesses, stewards, acteurs et cr&eacute;ateurs.',
                    'es' => 'Servicios completos para empresas, agencias, marcas y producciones.<br>Talento con imagen: modelos, azafatas, promotores, actores y creadores.',
                ),
                'servizi_eyebrow' => array(
                    'it' => 'I nostri servizi',
                    'en' => 'Our services',
                    'fr' => 'Nos services',
                    'es' => 'Nuestros servicios',
                ),
                'servizi_heading' => array(
                    'it' => 'Un unico partner per tutto',
                    'en' => 'One partner for everything',
                    'fr' => 'Un seul partenaire pour tout',
                    'es' => 'Un &uacute;nico partner para todo',
                ),
                'serv1_title' => array(
                    'it' => 'Modelli e Hostess',
                    'en' => 'Models &amp; Hostesses',
                    'fr' => 'Mannequins &amp; H&ocirc;tesses',
                    'es' => 'Modelos y Azafatas',
                ),
                'serv1_text' => array(
                    'it' => 'Database nazionale e internazionale per campagne moda, e-commerce, editoriali, sfilate, eventi e fiere. Profili di tutte le et&agrave; attivi in Italia, Francia, Spagna, UK.',
                    'en' => 'National and international database for fashion campaigns, e-commerce, editorials, fashion shows, events and trade fairs. Profiles of all ages active in Italy, France, Spain, UK.',
                    'fr' => 'Base de donn&eacute;es nationale et internationale pour campagnes mode, e-commerce, &eacute;ditoriaux, d&eacute;fil&eacute;s, &eacute;v&eacute;nements et salons. Profils de tous &acirc;ges actifs en Italie, France, Espagne, UK.',
                    'es' => 'Base de datos nacional e internacional para campa&ntilde;as de moda, e-commerce, editoriales, desfiles, eventos y ferias. Perfiles de todas las edades activos en Italia, Francia, Espa&ntilde;a, UK.',
                ),
                'serv2_title' => array(
                    'it' => 'Attori e Comparse',
                    'en' => 'Actors &amp; Extras',
                    'fr' => 'Acteurs &amp; Figurants',
                    'es' => 'Actores y Figurantes',
                ),
                'serv2_text' => array(
                    'it' => 'Selezione di attori professionisti, comparse e volti per spot pubblicitari, cinema, teatro, TV e contenuti social. Casting mirati per ogni tipo di scena.',
                    'en' => 'Selection of professional actors, extras and faces for commercials, cinema, theatre, TV and social content. Targeted casting for every type of scene.',
                    'fr' => 'S&eacute;lection d\'acteurs professionnels, figurants et visages pour spots publicitaires, cin&eacute;ma, th&eacute;&acirc;tre, TV et contenus sociaux. Castings cibl&eacute;s pour chaque type de sc&egrave;ne.',
                    'es' => 'Selecci&oacute;n de actores profesionales, figurantes y rostros para spots publicitarios, cine, teatro, TV y contenidos sociales. Castings dirigidos para cada tipo de escena.',
                ),
                'serv3_title' => array(
                    'it' => 'Influencer e Creator',
                    'en' => 'Influencers &amp; Creators',
                    'fr' => 'Influenceurs &amp; Cr&eacute;ateurs',
                    'es' => 'Influencers y Creadores',
                ),
                'serv3_text' => array(
                    'it' => 'Campagne di influencer marketing con profili selezionati per settore, target e engagement rate. Collaborazioni su Instagram, TikTok, YouTube.',
                    'en' => 'Influencer marketing campaigns with profiles selected by industry, target audience and engagement rate. Collaborations on Instagram, TikTok, YouTube.',
                    'fr' => 'Campagnes d\'influencer marketing avec profils s&eacute;lectionn&eacute;s par secteur, cible et taux d\'engagement. Collaborations sur Instagram, TikTok, YouTube.',
                    'es' => 'Campa&ntilde;as de influencer marketing con perfiles seleccionados por sector, target y engagement rate. Colaboraciones en Instagram, TikTok, YouTube.',
                ),
                'serv4_title' => array(
                    'it' => 'Produzione Foto e Video',
                    'en' => 'Photo &amp; Video Production',
                    'fr' => 'Production Photo &amp; Vid&eacute;o',
                    'es' => 'Producci&oacute;n Foto y V&iacute;deo',
                ),
                'serv4_text' => array(
                    'it' => 'Produzione completa per e-commerce, social media e campagne ADV. Location scouting, permessi, logistica e post-produzione inclusi.',
                    'en' => 'Complete production for e-commerce, social media and ADV campaigns. Location scouting, permits, logistics and post-production included.',
                    'fr' => 'Production compl&egrave;te pour e-commerce, r&eacute;seaux sociaux et campagnes ADV. Rep&eacute;rage de lieux, autorisations, logistique et post-production inclus.',
                    'es' => 'Producci&oacute;n completa para e-commerce, redes sociales y campa&ntilde;as ADV. Localizaci&oacute;n, permisos, log&iacute;stica y postproducci&oacute;n incluidos.',
                ),
                'cta_title' => array(
                    'it' => 'Richiedi un preventivo',
                    'en' => 'Request a quote',
                    'fr' => 'Demander un devis',
                    'es' => 'Solicitar un presupuesto',
                ),
                'cta_subtitle' => array(
                    'it' => 'Raccontaci il tuo progetto &mdash; rispondiamo in 30 minuti con le proposte migliori.',
                    'en' => 'Tell us about your project &mdash; we respond in 30 minutes with the best proposals.',
                    'fr' => 'Parlez-nous de votre projet &mdash; nous r&eacute;pondons en 30 minutes avec les meilleures propositions.',
                    'es' => 'Cu&eacute;ntanos tu proyecto &mdash; respondemos en 30 minutos con las mejores propuestas.',
                ),
            ),

            // ═══════════════════════════════════════
            // CONTACT PAGE
            // ═══════════════════════════════════════
            'contact' => array(
                'hero_subtitle' => array(
                    'it' => 'Italia, Francia, Spagna e UK.<br>Scrivici o chiamaci: risposta rapida garantita. Supporto in 5 lingue, 7 giorni su 7.',
                    'en' => 'Italy, France, Spain and UK.<br>Write or call us: quick response guaranteed. Support in 5 languages, 7 days a week.',
                    'fr' => 'Italie, France, Espagne et UK.<br>&Eacute;crivez-nous ou appelez-nous : r&eacute;ponse rapide garantie. Support en 5 langues, 7 jours sur 7.',
                    'es' => 'Italia, Francia, Espa&ntilde;a y UK.<br>Escr&iacute;benos o ll&aacute;manos: respuesta r&aacute;pida garantizada. Soporte en 5 idiomas, 7 d&iacute;as a la semana.',
                ),
                'amministrazione' => array(
                    'it' => 'Amministrazione',
                    'en' => 'Administration',
                    'fr' => 'Administration',
                    'es' => 'Administraci&oacute;n',
                ),
            ),

            // ═══════════════════════════════════════
            // MODELS PAGE
            // ═══════════════════════════════════════
            'models' => array(
                'hero_subtitle' => array(
                    'it' => 'Oltre 5.000 modelli e modelle attivi in Italia, Francia, Spagna e UK.<br>Da 6 a 80 anni, per ogni tipo di campagna. Disponibili entro 48 ore.',
                    'en' => 'Over 5,000 active models in Italy, France, Spain and UK.<br>From 6 to 80 years old, for every type of campaign. Available within 48 hours.',
                    'fr' => 'Plus de 5 000 mannequins actifs en Italie, France, Espagne et UK.<br>De 6 &agrave; 80 ans, pour tout type de campagne. Disponibles sous 48 heures.',
                    'es' => 'M&aacute;s de 5.000 modelos activos en Italia, Francia, Espa&ntilde;a y UK.<br>De 6 a 80 a&ntilde;os, para todo tipo de campa&ntilde;a. Disponibles en 48 horas.',
                ),
                'cta_heading' => array(
                    'it' => 'Scegli come procedere',
                    'en' => 'Choose how to proceed',
                    'fr' => 'Choisissez comment proc&eacute;der',
                    'es' => 'Elige c&oacute;mo proceder',
                ),
                'cta_cerco' => array(
                    'it' => 'Cerco modelli per il mio progetto',
                    'en' => 'I\'m looking for models for my project',
                    'fr' => 'Je cherche des mannequins pour mon projet',
                    'es' => 'Busco modelos para mi proyecto',
                ),
                'cta_sono' => array(
                    'it' => 'Sono un/a modello/a &mdash; cerco lavoro',
                    'en' => 'I\'m a model &mdash; looking for work',
                    'fr' => 'Je suis mannequin &mdash; je cherche du travail',
                    'es' => 'Soy modelo &mdash; busco trabajo',
                ),
                'cta_esplora' => array(
                    'it' => 'Esplora book e profili',
                    'en' => 'Explore books &amp; profiles',
                    'fr' => 'Explorer les books et profils',
                    'es' => 'Explorar books y perfiles',
                ),
                'how_step1' => array(
                    'it' => 'DESCRIVI IL PROGETTO',
                    'en' => 'DESCRIBE THE PROJECT',
                    'fr' => 'D&Eacute;CRIVEZ LE PROJET',
                    'es' => 'DESCRIBE EL PROYECTO',
                ),
                'how_step2' => array(
                    'it' => 'TI CHIAMIAMO',
                    'en' => 'WE CALL YOU',
                    'fr' => 'NOUS VOUS APPELONS',
                    'es' => 'TE LLAMAMOS',
                ),
                'how_step3' => array(
                    'it' => 'HAI I BOOK',
                    'en' => 'GET THE BOOKS',
                    'fr' => 'RECEVEZ LES BOOKS',
                    'es' => 'RECIBE LOS BOOKS',
                ),
                'how_step4' => array(
                    'it' => 'SUPPORTO CONTINUO',
                    'en' => 'ONGOING SUPPORT',
                    'fr' => 'SUPPORT CONTINU',
                    'es' => 'SOPORTE CONTINUO',
                ),
                'how_tagline' => array(
                    'it' => 'Book in 24 ore &bull; Misure certificate &bull; Contratti CCNL &bull; Fatturazione unica',
                    'en' => 'Books in 24 hours &bull; Certified measurements &bull; CCNL contracts &bull; Single invoicing',
                    'fr' => 'Books en 24 heures &bull; Mensurations certifi&eacute;es &bull; Contrats CCNL &bull; Facturation unique',
                    'es' => 'Books en 24 horas &bull; Medidas certificadas &bull; Contratos CCNL &bull; Facturaci&oacute;n &uacute;nica',
                ),
            ),

            // ═══════════════════════════════════════
            // ACTORS PAGE
            // ═══════════════════════════════════════
            'actors' => array(
                'hero_subtitle' => array(
                    'it' => 'Oltre 3.000 attori professionisti con showreel aggiornati.<br>Protagonisti, character, comparse, figurazioni speciali.',
                    'en' => 'Over 3,000 professional actors with updated showreels.<br>Lead roles, character actors, extras, special appearances.',
                    'fr' => 'Plus de 3 000 acteurs professionnels avec showreels &agrave; jour.<br>R&ocirc;les principaux, seconds r&ocirc;les, figurants, figurations sp&eacute;ciales.',
                    'es' => 'M&aacute;s de 3.000 actores profesionales con showreels actualizados.<br>Protagonistas, actores de car&aacute;cter, figurantes, figuraciones especiales.',
                ),
                'cta_heading' => array(
                    'it' => 'Scegli come procedere',
                    'en' => 'Choose how to proceed',
                    'fr' => 'Choisissez comment proc&eacute;der',
                    'es' => 'Elige c&oacute;mo proceder',
                ),
                'cta_cerco' => array(
                    'it' => 'Cerco attori per il mio progetto',
                    'en' => 'I\'m looking for actors for my project',
                    'fr' => 'Je cherche des acteurs pour mon projet',
                    'es' => 'Busco actores para mi proyecto',
                ),
                'cta_sono' => array(
                    'it' => 'Sono un attore &mdash; cerco ruoli',
                    'en' => 'I\'m an actor &mdash; looking for roles',
                    'fr' => 'Je suis acteur &mdash; je cherche des r&ocirc;les',
                    'es' => 'Soy actor &mdash; busco papeles',
                ),
                'cta_esplora' => array(
                    'it' => 'Esplora showreel e profili',
                    'en' => 'Explore showreels &amp; profiles',
                    'fr' => 'Explorer les showreels et profils',
                    'es' => 'Explorar showreels y perfiles',
                ),
                'how_step1' => array(
                    'it' => 'DESCRIVI IL RUOLO',
                    'en' => 'DESCRIBE THE ROLE',
                    'fr' => 'D&Eacute;CRIVEZ LE R&Ocirc;LE',
                    'es' => 'DESCRIBE EL PAPEL',
                ),
                'how_step2' => array(
                    'it' => 'TI CHIAMIAMO',
                    'en' => 'WE CALL YOU',
                    'fr' => 'NOUS VOUS APPELONS',
                    'es' => 'TE LLAMAMOS',
                ),
                'how_step3' => array(
                    'it' => 'HAI I SELF-TAPE',
                    'en' => 'GET THE SELF-TAPES',
                    'fr' => 'RECEVEZ LES SELF-TAPES',
                    'es' => 'RECIBE LOS SELF-TAPES',
                ),
                'how_step4' => array(
                    'it' => 'SUPPORTO CONTINUO',
                    'en' => 'ONGOING SUPPORT',
                    'fr' => 'SUPPORT CONTINU',
                    'es' => 'SOPORTE CONTINUO',
                ),
                'how_tagline' => array(
                    'it' => 'Self-tape in 24 ore &bull; Showreel professionali &bull; Contratti ENPALS',
                    'en' => 'Self-tapes in 24 hours &bull; Professional showreels &bull; ENPALS contracts',
                    'fr' => 'Self-tapes en 24 heures &bull; Showreels professionnels &bull; Contrats ENPALS',
                    'es' => 'Self-tapes en 24 horas &bull; Showreels profesionales &bull; Contratos ENPALS',
                ),
            ),

            // ═══════════════════════════════════════
            // HOSTESS PAGE
            // ═══════════════════════════════════════
            'hostess' => array(
                'hero_subtitle' => array(
                    'it' => 'Hostess e steward professionisti per eventi, fiere, congressi e attivit&agrave; promozionali.<br>Personale qualificato, multilingue, in tutta Europa.',
                    'en' => 'Professional hostesses and stewards for events, trade fairs, conferences and promotional activities.<br>Qualified, multilingual staff across Europe.',
                    'fr' => 'H&ocirc;tesses et stewards professionnels pour &eacute;v&eacute;nements, salons, congr&egrave;s et activit&eacute;s promotionnelles.<br>Personnel qualifi&eacute;, multilingue, dans toute l\'Europe.',
                    'es' => 'Azafatas y promotores profesionales para eventos, ferias, congresos y actividades promocionales.<br>Personal cualificado, multiling&uuml;e, en toda Europa.',
                ),
                'cta_heading' => array(
                    'it' => 'Scegli come procedere',
                    'en' => 'Choose how to proceed',
                    'fr' => 'Choisissez comment proc&eacute;der',
                    'es' => 'Elige c&oacute;mo proceder',
                ),
                'cta_cerco' => array(
                    'it' => 'Cerco hostess/steward per il mio evento',
                    'en' => 'I\'m looking for hostesses/stewards for my event',
                    'fr' => 'Je cherche des h&ocirc;tesses/stewards pour mon &eacute;v&eacute;nement',
                    'es' => 'Busco azafatas/promotores para mi evento',
                ),
                'cta_sono' => array(
                    'it' => 'Sono hostess/steward &mdash; cerco lavoro',
                    'en' => 'I\'m a hostess/steward &mdash; looking for work',
                    'fr' => 'Je suis h&ocirc;tesse/steward &mdash; je cherche du travail',
                    'es' => 'Soy azafata/promotor &mdash; busco trabajo',
                ),
                'cta_esplora' => array(
                    'it' => 'Esplora profili disponibili',
                    'en' => 'Explore available profiles',
                    'fr' => 'Explorer les profils disponibles',
                    'es' => 'Explorar perfiles disponibles',
                ),
            ),

            // ═══════════════════════════════════════
            // FOOTER
            // ═══════════════════════════════════════
            'footer' => array(
                'tagline' => array(
                    'it' => 'Agenzia internazionale di casting e produzione',
                    'en' => 'International casting and production agency',
                    'fr' => 'Agence internationale de casting et production',
                    'es' => 'Agencia internacional de casting y producci&oacute;n',
                ),
                'link_chi_siamo' => array(
                    'it' => 'Chi siamo',
                    'en' => 'About us',
                    'fr' => '&Agrave; propos',
                    'es' => 'Qui&eacute;nes somos',
                ),
                'link_servizi' => array(
                    'it' => 'Servizi B2B',
                    'en' => 'B2B Services',
                    'fr' => 'Services B2B',
                    'es' => 'Servicios B2B',
                ),
                'link_contatti' => array(
                    'it' => 'Contatti',
                    'en' => 'Contacts',
                    'fr' => 'Contacts',
                    'es' => 'Contactos',
                ),
                'link_collabora' => array(
                    'it' => 'Collabora con noi',
                    'en' => 'Work with us',
                    'fr' => 'Collaborez avec nous',
                    'es' => 'Colabora con nosotros',
                ),
                'link_privacy' => array(
                    'it' => 'Privacy Policy',
                    'en' => 'Privacy Policy',
                    'fr' => 'Politique de confidentialit&eacute;',
                    'es' => 'Pol&iacute;tica de privacidad',
                ),
                'copy' => array(
                    'it' => 'Tutti i diritti riservati.',
                    'en' => 'All rights reserved.',
                    'fr' => 'Tous droits r&eacute;serv&eacute;s.',
                    'es' => 'Todos los derechos reservados.',
                ),
            ),

            // ═══════════════════════════════════════
            // HOME PAGE
            // ═══════════════════════════════════════
            'home' => array(
                'hero_eyebrow' => array(
                    'it' => 'Dal 2009, Casting &amp; Produzione',
                    'en' => 'Since 2009, Casting &amp; Production',
                    'fr' => 'Depuis 2009, Casting &amp; Production',
                    'es' => 'Desde 2009, Casting &amp; Producci&oacute;n',
                ),
                'hero_seleziona' => array(
                    'it' => 'Seleziona',
                    'en' => 'Select',
                    'fr' => 'S&eacute;lectionnez',
                    'es' => 'Selecciona',
                ),
                'hero_subtitle_1' => array(
                    'it' => 'Preventivo gratuito in 30 minuti. Un team dedicato gestisce',
                    'en' => 'Free quote in 30 minutes. A dedicated team manages',
                    'fr' => 'Devis gratuit en 30 minutes. Une &eacute;quipe d&eacute;di&eacute;e g&egrave;re',
                    'es' => 'Presupuesto gratuito en 30 minutos. Un equipo dedicado gestiona',
                ),
                'hero_subtitle_2' => array(
                    'it' => 'selezione, contratti e logistica &mdash; tu pensi al progetto.',
                    'en' => 'selection, contracts and logistics &mdash; you focus on the project.',
                    'fr' => 's&eacute;lection, contrats et logistique &mdash; vous vous concentrez sur le projet.',
                    'es' => 'selecci&oacute;n, contratos y log&iacute;stica &mdash; t&uacute; te centras en el proyecto.',
                ),
                'hero_btn_azienda' => array(
                    'it' => 'Sono un\'azienda &mdash; cerco talent',
                    'en' => 'I\'m a business &mdash; looking for talent',
                    'fr' => 'Je suis une entreprise &mdash; je cherche des talents',
                    'es' => 'Soy una empresa &mdash; busco talento',
                ),
                'hero_btn_talent' => array(
                    'it' => 'Sono un talent &mdash; cerco lavori',
                    'en' => 'I\'m a talent &mdash; looking for jobs',
                    'fr' => 'Je suis un talent &mdash; je cherche du travail',
                    'es' => 'Soy un talento &mdash; busco trabajo',
                ),
                'stat_professionisti' => array(
                    'it' => 'Professionisti',
                    'en' => 'Professionals',
                    'fr' => 'Professionnels',
                    'es' => 'Profesionales',
                ),
                'stat_progetti' => array(
                    'it' => 'Progetti',
                    'en' => 'Projects',
                    'fr' => 'Projets',
                    'es' => 'Proyectos',
                ),
                'stat_citta' => array(
                    'it' => 'Citt&agrave; operative',
                    'en' => 'Active cities',
                    'fr' => 'Villes op&eacute;rationnelles',
                    'es' => 'Ciudades operativas',
                ),
                'stat_anni' => array(
                    'it' => 'Anni di esperienza',
                    'en' => 'Years of experience',
                    'fr' => 'Ans d\'exp&eacute;rience',
                    'es' => 'A&ntilde;os de experiencia',
                ),
                'why_eyebrow' => array(
                    'it' => 'Perch&eacute; sceglierci',
                    'en' => 'Why choose us',
                    'fr' => 'Pourquoi nous choisir',
                    'es' => 'Por qu&eacute; elegirnos',
                ),
                'why_heading_1' => array(
                    'it' => 'Non siamo una piattaforma.',
                    'en' => 'We\'re not a platform.',
                    'fr' => 'Nous ne sommes pas une plateforme.',
                    'es' => 'No somos una plataforma.',
                ),
                'why_heading_2' => array(
                    'it' => 'Siamo il tuo team.',
                    'en' => 'We\'re your team.',
                    'fr' => 'Nous sommes votre &eacute;quipe.',
                    'es' => 'Somos tu equipo.',
                ),
                'feat1_title' => array(
                    'it' => 'Team umano dedicato',
                    'en' => 'Dedicated human team',
                    'fr' => '&Eacute;quipe humaine d&eacute;di&eacute;e',
                    'es' => 'Equipo humano dedicado',
                ),
                'feat1_text' => array(
                    'it' => 'Un casting director assegnato al tuo progetto. Gestiamo brief, selezione, contratti e pagamenti. Niente algoritmi &mdash; persone che capiscono cosa serve.',
                    'en' => 'A casting director assigned to your project. We manage briefs, selection, contracts and payments. No algorithms &mdash; people who understand what you need.',
                    'fr' => 'Un directeur de casting affect&eacute; &agrave; votre projet. Nous g&eacute;rons briefs, s&eacute;lection, contrats et paiements. Pas d\'algorithmes &mdash; des personnes qui comprennent vos besoins.',
                    'es' => 'Un director de casting asignado a tu proyecto. Gestionamos briefs, selecci&oacute;n, contratos y pagos. Sin algoritmos &mdash; personas que entienden lo que necesitas.',
                ),
                'feat2_title' => array(
                    'it' => 'Copertura internazionale',
                    'en' => 'International coverage',
                    'fr' => 'Couverture internationale',
                    'es' => 'Cobertura internacional',
                ),
                'feat2_text' => array(
                    'it' => 'Operativi in Italia, Francia, Spagna e UK. Dalle fiere di Milano e Rimini agli shooting a Parigi e Londra. Un unico referente per tutta Europa.',
                    'en' => 'Active in Italy, France, Spain and UK. From Milan and Rimini trade fairs to Paris and London shoots. One single contact for all of Europe.',
                    'fr' => 'Op&eacute;rationnels en Italie, France, Espagne et UK. Des salons de Milan et Rimini aux shootings &agrave; Paris et Londres. Un seul interlocuteur pour toute l\'Europe.',
                    'es' => 'Operativos en Italia, Francia, Espa&ntilde;a y UK. Desde las ferias de Mil&aacute;n y Rimini hasta los shootings en Par&iacute;s y Londres. Un &uacute;nico referente para toda Europa.',
                ),
                'rotating_words' => array(
                    'it' => 'Modelli,Hostess,Attori,Fotografi,Videomaker,Comparse,Creator,Steward,Truccatori',
                    'en' => 'Models,Hostesses,Actors,Photographers,Videomakers,Extras,Creators,Stewards,Makeup Artists',
                    'fr' => 'Mannequins,H&ocirc;tesses,Acteurs,Photographes,Vid&eacute;astes,Figurants,Cr&eacute;ateurs,Stewards,Maquilleurs',
                    'es' => 'Modelos,Azafatas,Actores,Fot&oacute;grafos,Videomakers,Figurantes,Creadores,Promotores,Maquilladores',
                ),
                // Features 3 & 4
                'feat3_title' => array(
                    'it' => 'Database verificato',
                    'en' => 'Verified database',
                    'fr' => 'Base de donn&eacute;es v&eacute;rifi&eacute;e',
                    'es' => 'Base de datos verificada',
                ),
                'feat3_text' => array(
                    'it' => '20.000+ profili aggiornati ogni giorno. Polaroid e self-tape in 24 ore. Disponibilit&agrave; in tempo reale, anche per casting urgenti.',
                    'en' => '20,000+ profiles updated daily. Polaroids and self-tapes within 24 hours. Real-time availability, even for urgent castings.',
                    'fr' => '20&nbsp;000+ profils mis &agrave; jour chaque jour. Polaroids et self-tapes en 24 heures. Disponibilit&eacute; en temps r&eacute;el, m&ecirc;me pour les castings urgents.',
                    'es' => 'M&aacute;s de 20.000 perfiles actualizados cada d&iacute;a. Polaroids y self-tapes en 24 horas. Disponibilidad en tiempo real, incluso para castings urgentes.',
                ),
                'feat4_title' => array(
                    'it' => 'Risposta in 30 minuti',
                    'en' => 'Reply in 30 minutes',
                    'fr' => 'R&eacute;ponse en 30 minutes',
                    'es' => 'Respuesta en 30 minutos',
                ),
                'feat4_text' => array(
                    'it' => 'Compili il form e in mezz\'ora hai le prime proposte con preventivo trasparente. Contratti digitali. Zero sorprese.',
                    'en' => 'Fill in the form and within half an hour you\'ll have the first proposals with a transparent quote. Digital contracts. No surprises.',
                    'fr' => 'Remplissez le formulaire et en une demi-heure vous avez les premi&egrave;res propositions avec un devis transparent. Contrats num&eacute;riques. Z&eacute;ro surprise.',
                    'es' => 'Rellena el formulario y en media hora tendr&aacute;s las primeras propuestas con presupuesto transparente. Contratos digitales. Cero sorpresas.',
                ),
                // Services section
                'srv_eyebrow' => array(
                    'it' => 'Cosa facciamo',
                    'en' => 'What we do',
                    'fr' => 'Ce que nous faisons',
                    'es' => 'Lo que hacemos',
                ),
                'srv_heading' => array(
                    'it' => 'Ogni progetto ha il talento giusto',
                    'en' => 'Every project has the right talent',
                    'fr' => 'Chaque projet a le bon talent',
                    'es' => 'Cada proyecto tiene el talento adecuado',
                ),
                'srv_models_name' => array(
                    'it' => 'Modelli',
                    'en' => 'Models',
                    'fr' => 'Mannequins',
                    'es' => 'Modelos',
                ),
                'srv_models_desc' => array(
                    'it' => 'Fashion, e-commerce, cataloghi, campagne',
                    'en' => 'Fashion, e-commerce, catalogues, campaigns',
                    'fr' => 'Mode, e-commerce, catalogues, campagnes',
                    'es' => 'Moda, e-commerce, cat&aacute;logos, campa&ntilde;as',
                ),
                'srv_hostess_name' => array(
                    'it' => 'Hostess &amp; Steward',
                    'en' => 'Hostesses &amp; Stewards',
                    'fr' => 'H&ocirc;tesses &amp; Stewards',
                    'es' => 'Azafatas &amp; Azafatos',
                ),
                'srv_hostess_desc' => array(
                    'it' => 'Fiere, eventi, congressi, attivazioni',
                    'en' => 'Trade fairs, events, conferences, activations',
                    'fr' => 'Foires, &eacute;v&eacute;nements, congr&egrave;s, activations',
                    'es' => 'Ferias, eventos, congresos, activaciones',
                ),
                'srv_actors_name' => array(
                    'it' => 'Attori &amp; Comparse',
                    'en' => 'Actors &amp; Extras',
                    'fr' => 'Acteurs &amp; Figurants',
                    'es' => 'Actores &amp; Figurantes',
                ),
                'srv_actors_desc' => array(
                    'it' => 'Film, serie TV, spot pubblicitari',
                    'en' => 'Films, TV series, commercials',
                    'fr' => 'Films, s&eacute;ries TV, spots publicitaires',
                    'es' => 'Pel&iacute;culas, series de TV, spots publicitarios',
                ),
                'srv_visuals_name' => array(
                    'it' => 'Fotografi &amp; Videomaker',
                    'en' => 'Photographers &amp; Videomakers',
                    'fr' => 'Photographes &amp; Vid&eacute;astes',
                    'es' => 'Fot&oacute;grafos &amp; Videomakers',
                ),
                'srv_visuals_desc' => array(
                    'it' => 'Shooting, produzioni video, contenuti social',
                    'en' => 'Shoots, video productions, social content',
                    'fr' => 'Shootings, productions vid&eacute;o, contenus sociaux',
                    'es' => 'Shootings, producciones de v&iacute;deo, contenido social',
                ),
                'srv_mua_name' => array(
                    'it' => 'MUA &amp; Hair Stylist',
                    'en' => 'MUA &amp; Hair Stylists',
                    'fr' => 'Maquilleurs &amp; Coiffeurs',
                    'es' => 'Maquilladores &amp; Estilistas',
                ),
                'srv_mua_desc' => array(
                    'it' => 'Trucco e acconciature per ogni produzione',
                    'en' => 'Makeup and hairstyling for every production',
                    'fr' => 'Maquillage et coiffure pour chaque production',
                    'es' => 'Maquillaje y peinado para cada producci&oacute;n',
                ),
                'srv_casting_name' => array(
                    'it' => 'Casting completo',
                    'en' => 'Full casting',
                    'fr' => 'Casting complet',
                    'es' => 'Casting completo',
                ),
                'srv_casting_desc' => array(
                    'it' => 'Selezione, logistica, gestione integrale',
                    'en' => 'Selection, logistics, full management',
                    'fr' => 'S&eacute;lection, logistique, gestion int&eacute;grale',
                    'es' => 'Selecci&oacute;n, log&iacute;stica, gesti&oacute;n integral',
                ),
                // CTA section
                'cta_title' => array(
                    'it' => 'Raccontaci il tuo progetto',
                    'en' => 'Tell us about your project',
                    'fr' => 'Parlez-nous de votre projet',
                    'es' => 'Cu&eacute;ntanos tu proyecto',
                ),
                'cta_subtitle' => array(
                    'it' => 'Compila il form e in 30 minuti ricevi le prime proposte con preventivo gratuito.',
                    'en' => 'Fill in the form and in 30 minutes you\'ll receive the first proposals with a free quote.',
                    'fr' => 'Remplissez le formulaire et en 30 minutes vous recevez les premi&egrave;res propositions avec un devis gratuit.',
                    'es' => 'Rellena el formulario y en 30 minutos recibes las primeras propuestas con presupuesto gratuito.',
                ),
                'cta_btn_business' => array(
                    'it' => 'Richiedi preventivo gratuito',
                    'en' => 'Request a free quote',
                    'fr' => 'Demander un devis gratuit',
                    'es' => 'Solicitar presupuesto gratuito',
                ),
                'cta_btn_talent' => array(
                    'it' => 'Sei un talent? Registrati',
                    'en' => 'Are you a talent? Sign up',
                    'fr' => 'Vous &ecirc;tes un talent&nbsp;? Inscrivez-vous',
                    'es' => '&iquest;Eres un talento? Reg&iacute;strate',
                ),
                // Coverage section
                'cov_eyebrow' => array(
                    'it' => 'Dove operiamo',
                    'en' => 'Where we operate',
                    'fr' => 'O&ugrave; nous op&eacute;rons',
                    'es' => 'D&oacute;nde operamos',
                ),
                'cov_heading' => array(
                    'it' => '4 paesi, 50+ citt&agrave;',
                    'en' => '4 countries, 50+ cities',
                    'fr' => '4 pays, 50+ villes',
                    'es' => '4 pa&iacute;ses, 50+ ciudades',
                ),
                'cov_it' => array(
                    'it' => 'Italia',
                    'en' => 'Italy',
                    'fr' => 'Italie',
                    'es' => 'Italia',
                ),
                'cov_it_cities' => array(
                    'it' => 'Milano, Roma, Torino, Genova, Venezia, Verona, Bologna, Firenze, Napoli, Rimini, Bari, Palermo, Catania',
                    'en' => 'Milan, Rome, Turin, Genoa, Venice, Verona, Bologna, Florence, Naples, Rimini, Bari, Palermo, Catania',
                    'fr' => 'Milan, Rome, Turin, G&ecirc;nes, Venise, V&eacute;rone, Bologne, Florence, Naples, Rimini, Bari, Palerme, Catane',
                    'es' => 'Mil&aacute;n, Roma, Tur&iacute;n, G&eacute;nova, Venecia, Verona, Bolonia, Florencia, N&aacute;poles, Rimini, Bari, Palermo, Catania',
                ),
                'cov_fr' => array(
                    'it' => 'Francia',
                    'en' => 'France',
                    'fr' => 'France',
                    'es' => 'Francia',
                ),
                'cov_fr_cities' => array(
                    'it' => 'Parigi, Lione, Marsiglia',
                    'en' => 'Paris, Lyon, Marseille',
                    'fr' => 'Paris, Lyon, Marseille',
                    'es' => 'Par&iacute;s, Lyon, Marsella',
                ),
                'cov_es' => array(
                    'it' => 'Spagna',
                    'en' => 'Spain',
                    'fr' => 'Espagne',
                    'es' => 'Espa&ntilde;a',
                ),
                'cov_es_cities' => array(
                    'it' => 'Madrid, Barcellona, Valencia',
                    'en' => 'Madrid, Barcelona, Valencia',
                    'fr' => 'Madrid, Barcelone, Valence',
                    'es' => 'Madrid, Barcelona, Valencia',
                ),
                'cov_uk' => array(
                    'it' => 'UK',
                    'en' => 'UK',
                    'fr' => 'UK',
                    'es' => 'UK',
                ),
                'cov_uk_cities' => array(
                    'it' => 'Londra',
                    'en' => 'London',
                    'fr' => 'Londres',
                    'es' => 'Londres',
                ),
            ),

            // ═══════════════════════════════════════
            // FORM B2B PAGE
            // ═══════════════════════════════════════
            'form_b2b' => array(
                'hero_title' => array(
                    'it' => 'Modelli, hostess e staff per il tuo progetto',
                    'en' => 'Models, hostesses and staff for your project',
                    'fr' => 'Mannequins, h&ocirc;tesses et personnel pour votre projet',
                    'es' => 'Modelos, azafatas y personal para tu proyecto',
                ),
                'hero_subtitle' => array(
                    'it' => 'Preventivo gratuito in 30 minuti. Operativi in tutta Italia e Europa.',
                    'en' => 'Free quote in 30 minutes. Active across Italy and Europe.',
                    'fr' => 'Devis gratuit en 30 minutes. Op&eacute;rationnels dans toute l\'Italie et l\'Europe.',
                    'es' => 'Presupuesto gratuito en 30 minutos. Operativos en toda Italia y Europa.',
                ),
                // Form heading
                'form_heading' => array(
                    'it' => 'Di cosa hai bisogno?',
                    'en' => 'What do you need?',
                    'fr' => 'De quoi avez-vous besoin&nbsp;?',
                    'es' => '&iquest;Qu&eacute; necesitas?',
                ),
                'form_subheading' => array(
                    'it' => 'Compila i campi essenziali &mdash; ti rispondiamo in 30 minuti.',
                    'en' => 'Fill in the essential fields &mdash; we\'ll reply within 30 minutes.',
                    'fr' => 'Remplissez les champs essentiels &mdash; nous vous r&eacute;pondons en 30 minutes.',
                    'es' => 'Rellena los campos esenciales &mdash; te respondemos en 30 minutos.',
                ),
                // Labels
                'label_company' => array(
                    'it' => 'Azienda *',
                    'en' => 'Company *',
                    'fr' => 'Entreprise *',
                    'es' => 'Empresa *',
                ),
                'label_contact' => array(
                    'it' => 'Nome e cognome *',
                    'en' => 'Full name *',
                    'fr' => 'Nom et pr&eacute;nom *',
                    'es' => 'Nombre y apellido *',
                ),
                'label_email' => array(
                    'it' => 'Email *',
                    'en' => 'Email *',
                    'fr' => 'Email *',
                    'es' => 'Correo *',
                ),
                'label_phone' => array(
                    'it' => 'Telefono *',
                    'en' => 'Phone *',
                    'fr' => 'T&eacute;l&eacute;phone *',
                    'es' => 'Tel&eacute;fono *',
                ),
                'label_event_type' => array(
                    'it' => 'Tipo di progetto *',
                    'en' => 'Project type *',
                    'fr' => 'Type de projet *',
                    'es' => 'Tipo de proyecto *',
                ),
                'label_period' => array(
                    'it' => 'Periodo/Data',
                    'en' => 'Period/Date',
                    'fr' => 'P&eacute;riode/Date',
                    'es' => 'Per&iacute;odo/Fecha',
                ),
                'label_location' => array(
                    'it' => 'Citt&agrave;/Zona',
                    'en' => 'City/Area',
                    'fr' => 'Ville/Zone',
                    'es' => 'Ciudad/Zona',
                ),
                'label_message' => array(
                    'it' => 'Descrizione progetto',
                    'en' => 'Project description',
                    'fr' => 'Description du projet',
                    'es' => 'Descripci&oacute;n del proyecto',
                ),
                // Placeholders
                'ph_company' => array(
                    'it' => 'Nome azienda',
                    'en' => 'Company name',
                    'fr' => 'Nom de l\'entreprise',
                    'es' => 'Nombre de empresa',
                ),
                'ph_contact' => array(
                    'it' => 'Il tuo nome',
                    'en' => 'Your name',
                    'fr' => 'Votre nom',
                    'es' => 'Tu nombre',
                ),
                'ph_period' => array(
                    'it' => 'Es: 15 maggio, fine aprile',
                    'en' => 'E.g.: May 15, end of April',
                    'fr' => 'Ex&nbsp;: 15 mai, fin avril',
                    'es' => 'Ej: 15 de mayo, finales de abril',
                ),
                'ph_location' => array(
                    'it' => 'Milano, Roma, Bologna...',
                    'en' => 'Milan, Rome, Bologna...',
                    'fr' => 'Milan, Rome, Bologne...',
                    'es' => 'Mil&aacute;n, Roma, Bolonia...',
                ),
                'ph_message' => array(
                    'it' => 'Raccontaci brevemente cosa ti serve...',
                    'en' => 'Briefly tell us what you need...',
                    'fr' => 'D&eacute;crivez bri&egrave;vement ce dont vous avez besoin...',
                    'es' => 'Cu&eacute;ntanos brevemente qu&eacute; necesitas...',
                ),
                // Project type options
                'opt_select' => array(
                    'it' => 'Seleziona tipo di progetto',
                    'en' => 'Select project type',
                    'fr' => 'S&eacute;lectionnez le type de projet',
                    'es' => 'Selecciona el tipo de proyecto',
                ),
                'opt_shooting' => array(
                    'it' => 'Shooting Foto/Video',
                    'en' => 'Photo/Video Shoot',
                    'fr' => 'Tournage Photo/Vid&eacute;o',
                    'es' => 'Shooting Foto/V&iacute;deo',
                ),
                'opt_social' => array(
                    'it' => 'Contenuti Social',
                    'en' => 'Social Media Content',
                    'fr' => 'Contenu pour les R&eacute;seaux Sociaux',
                    'es' => 'Contenido para Redes Sociales',
                ),
                'opt_showroom' => array(
                    'it' => 'Showroom/Fitting',
                    'en' => 'Showroom/Fitting',
                    'fr' => 'Showroom/Fitting',
                    'es' => 'Showroom/Fitting',
                ),
                'opt_fiera' => array(
                    'it' => 'Fiera/Salone',
                    'en' => 'Trade Fair/Exhibition',
                    'fr' => 'Foire/Salon',
                    'es' => 'Feria/Sal&oacute;n',
                ),
                'opt_evento' => array(
                    'it' => 'Evento Aziendale',
                    'en' => 'Corporate Event',
                    'fr' => 'Ev&eacute;nement d\'Entreprise',
                    'es' => 'Evento Corporativo',
                ),
                'opt_promo' => array(
                    'it' => 'Attivit&agrave; Promozionale',
                    'en' => 'Promotional Activity',
                    'fr' => 'Activit&eacute; Promotionnelle',
                    'es' => 'Actividad Promocional',
                ),
                'opt_pub' => array(
                    'it' => 'Pubblicit&agrave;/Spot',
                    'en' => 'Advertising/Commercial',
                    'fr' => 'Publicit&eacute;/Spot',
                    'es' => 'Publicidad/Spot',
                ),
                'opt_film' => array(
                    'it' => 'Film/Serie TV',
                    'en' => 'Film/TV Series',
                    'fr' => 'Film/S&eacute;rie TV',
                    'es' => 'Pel&iacute;cula/Serie TV',
                ),
                'opt_sfilata' => array(
                    'it' => 'Sfilata',
                    'en' => 'Fashion Show',
                    'fr' => 'D&eacute;fil&eacute;',
                    'es' => 'Desfile',
                ),
                'opt_altro' => array(
                    'it' => 'Altro',
                    'en' => 'Other',
                    'fr' => 'Autre',
                    'es' => 'Otro',
                ),
                // Details toggle
                'details_toggle_open' => array(
                    'it' => '+ Vuoi aggiungere dettagli? (opzionale)',
                    'en' => '+ Want to add details? (optional)',
                    'fr' => '+ Souhaitez-vous ajouter des d&eacute;tails&nbsp;? (optionnel)',
                    'es' => '+ &iquest;Quieres a&ntilde;adir detalles? (opcional)',
                ),
                'details_toggle_close' => array(
                    'it' => '&minus; Nascondi dettagli',
                    'en' => '&minus; Hide details',
                    'fr' => '&minus; Masquer les d&eacute;tails',
                    'es' => '&minus; Ocultar detalles',
                ),
                'details_profiles_label' => array(
                    'it' => 'Profili ricercati:',
                    'en' => 'Profiles needed:',
                    'fr' => 'Profils recherch&eacute;s&nbsp;:',
                    'es' => 'Perfiles buscados:',
                ),
                // Roles
                'role_models' => array(
                    'it' => 'Modelli/e',
                    'en' => 'Models',
                    'fr' => 'Mannequins',
                    'es' => 'Modelos',
                ),
                'role_actors' => array(
                    'it' => 'Attori',
                    'en' => 'Actors',
                    'fr' => 'Acteurs',
                    'es' => 'Actores',
                ),
                'role_hostess' => array(
                    'it' => 'Hostess',
                    'en' => 'Hostesses',
                    'fr' => 'H&ocirc;tesses',
                    'es' => 'Azafatas',
                ),
                'role_steward' => array(
                    'it' => 'Steward',
                    'en' => 'Stewards',
                    'fr' => 'Stewards',
                    'es' => 'Azafatos',
                ),
                'role_creator' => array(
                    'it' => 'Creator',
                    'en' => 'Creators',
                    'fr' => 'Cr&eacute;ateurs',
                    'es' => 'Creadores',
                ),
                'role_photo' => array(
                    'it' => 'Fotografi',
                    'en' => 'Photographers',
                    'fr' => 'Photographes',
                    'es' => 'Fot&oacute;grafos',
                ),
                'role_makeup' => array(
                    'it' => 'Truccatori',
                    'en' => 'Makeup Artists',
                    'fr' => 'Maquilleurs',
                    'es' => 'Maquilladores',
                ),
                'role_extras' => array(
                    'it' => 'Comparse',
                    'en' => 'Extras',
                    'fr' => 'Figurants',
                    'es' => 'Figurantes',
                ),
                'role_hair' => array(
                    'it' => 'Parrucchieri',
                    'en' => 'Hairstylists',
                    'fr' => 'Coiffeurs',
                    'es' => 'Peluqueros',
                ),
                'role_stylist' => array(
                    'it' => 'Stylist',
                    'en' => 'Stylists',
                    'fr' => 'Stylistes',
                    'es' => 'Estilistas',
                ),
                // Extra fields labels & placeholders
                'label_figures' => array(
                    'it' => 'Numero figure',
                    'en' => 'Number of profiles',
                    'fr' => 'Nombre de profils',
                    'es' => 'N&uacute;mero de perfiles',
                ),
                'label_budget' => array(
                    'it' => 'Budget per figura',
                    'en' => 'Budget per profile',
                    'fr' => 'Budget par profil',
                    'es' => 'Presupuesto por perfil',
                ),
                'label_duration' => array(
                    'it' => 'Durata lavoro',
                    'en' => 'Work duration',
                    'fr' => 'Dur&eacute;e du travail',
                    'es' => 'Duraci&oacute;n del trabajo',
                ),
                'label_details' => array(
                    'it' => 'Dettagli extra',
                    'en' => 'Extra details',
                    'fr' => 'D&eacute;tails suppl&eacute;mentaires',
                    'es' => 'Detalles extra',
                ),
                'ph_figures' => array(
                    'it' => 'Es: 3 modelle, 2 fotografi',
                    'en' => 'E.g.: 3 models, 2 photographers',
                    'fr' => 'Ex&nbsp;: 3 mannequins, 2 photographes',
                    'es' => 'Ej: 3 modelos, 2 fot&oacute;grafos',
                ),
                'ph_duration' => array(
                    'it' => 'Es: mezza giornata, 2 giorni',
                    'en' => 'E.g.: half day, 2 days',
                    'fr' => 'Ex&nbsp;: demi-journ&eacute;e, 2 jours',
                    'es' => 'Ej: medio d&iacute;a, 2 d&iacute;as',
                ),
                'ph_details' => array(
                    'it' => 'Altezza min, lingue, ecc.',
                    'en' => 'Min height, languages, etc.',
                    'fr' => 'Taille min, langues, etc.',
                    'es' => 'Altura m&iacute;n, idiomas, etc.',
                ),
                // Privacy & submit
                'privacy_text' => array(
                    'it' => 'Accetto il trattamento dei dati personali secondo la',
                    'en' => 'I accept the processing of my personal data according to the',
                    'fr' => 'J\'accepte le traitement de mes donn&eacute;es personnelles conform&eacute;ment &agrave; la',
                    'es' => 'Acepto el tratamiento de mis datos personales seg&uacute;n la',
                ),
                'privacy_link' => array(
                    'it' => 'privacy policy',
                    'en' => 'privacy policy',
                    'fr' => 'politique de confidentialit&eacute;',
                    'es' => 'pol&iacute;tica de privacidad',
                ),
                'submit_btn' => array(
                    'it' => 'RICHIEDI PREVENTIVO GRATUITO',
                    'en' => 'REQUEST A FREE QUOTE',
                    'fr' => 'DEMANDER UN DEVIS GRATUIT',
                    'es' => 'SOLICITAR PRESUPUESTO GRATUITO',
                ),
                // B2C banner
                'b2c_text' => array(
                    'it' => 'Sei un modello, hostess o attore? Cerchi lavoro?',
                    'en' => 'Are you a model, hostess or actor? Looking for work?',
                    'fr' => 'Vous &ecirc;tes mannequin, h&ocirc;tesse ou acteur&nbsp;? Vous cherchez du travail&nbsp;?',
                    'es' => '&iquest;Eres modelo, azafata o actor? &iquest;Buscas trabajo?',
                ),
                'b2c_btn' => array(
                    'it' => 'REGISTRATI &mdash; &Egrave; GRATUITO',
                    'en' => 'SIGN UP &mdash; IT\'S FREE',
                    'fr' => 'INSCRIVEZ-VOUS &mdash; C\'EST GRATUIT',
                    'es' => 'REG&Iacute;STRATE &mdash; ES GRATUITO',
                ),
                // JS strings
                'js_alert_privacy' => array(
                    'it' => 'Accetta la privacy per continuare',
                    'en' => 'Please accept the privacy policy to continue',
                    'fr' => 'Veuillez accepter la politique de confidentialit&eacute; pour continuer',
                    'es' => 'Por favor acepta la pol&iacute;tica de privacidad para continuar',
                ),
                'js_sending' => array(
                    'it' => 'INVIO IN CORSO...',
                    'en' => 'SENDING...',
                    'fr' => 'ENVOI EN COURS...',
                    'es' => 'ENVIANDO...',
                ),
                'js_error_msg' => array(
                    'it' => "Problema temporaneo di connessione.\n\nI tuoi dati sono salvati e verranno reinviati automaticamente.\n\nVuoi contattarci direttamente via WhatsApp?",
                    'en' => "Temporary connection issue.\n\nYour data has been saved and will be resent automatically.\n\nWould you like to contact us directly via WhatsApp?",
                    'fr' => "Problème de connexion temporaire.\n\nVos données ont été enregistrées et seront réenvoyées automatiquement.\n\nSouhaitez-vous nous contacter directement via WhatsApp?",
                    'es' => "Problema temporal de conexión.\n\nTus datos han sido guardados y serán reenviados automáticamente.\n\n¿Quieres contactarnos directamente por WhatsApp?",
                ),
                'js_wa_message' => array(
                    'it' => 'Ciao, ho compilato il form sul sito ma non è partito. Azienda: ',
                    'en' => 'Hello, I filled in the form on the website but it did not send. Company: ',
                    'fr' => 'Bonjour, j\'ai rempli le formulaire sur le site mais il n\'a pas été envoyé. Entreprise : ',
                    'es' => 'Hola, rellené el formulario en el sitio web pero no se envió. Empresa: ',
                ),
            ),

            // ═══════════════════════════════════════
            // COLLABORA PAGE
            // ═══════════════════════════════════════
            'collabora' => array(
                'hero_subtitle' => array(
                    'it' => 'Entra nel network di oltre 20.000 professionisti.<br>Modelli, hostess, attori, fotografi, videomaker e creativi.',
                    'en' => 'Join the network of over 20,000 professionals.<br>Models, hostesses, actors, photographers, videomakers and creatives.',
                    'fr' => 'Rejoignez le r&eacute;seau de plus de 20 000 professionnels.<br>Mannequins, h&ocirc;tesses, acteurs, photographes, vid&eacute;astes et cr&eacute;atifs.',
                    'es' => '&Uacute;nete a la red de m&aacute;s de 20.000 profesionales.<br>Modelos, azafatas, actores, fot&oacute;grafos, videomakers y creativos.',
                ),
            ),

            // ═══════════════════════════════════════
            // VISUALS PAGE
            // ═══════════════════════════════════════
            'visuals' => array(
                'hero_subtitle' => array(
                    'it' => 'Produzione fotografica e video per e-commerce, social media e campagne pubblicitarie.<br>Dalla pre-produzione alla post-produzione, tutto incluso.',
                    'en' => 'Photo and video production for e-commerce, social media and advertising campaigns.<br>From pre-production to post-production, everything included.',
                    'fr' => 'Production photo et vid&eacute;o pour e-commerce, r&eacute;seaux sociaux et campagnes publicitaires.<br>De la pr&eacute;-production &agrave; la post-production, tout inclus.',
                    'es' => 'Producci&oacute;n fotogr&aacute;fica y de v&iacute;deo para e-commerce, redes sociales y campa&ntilde;as publicitarias.<br>Desde la preproducci&oacute;n hasta la postproducci&oacute;n, todo incluido.',
                ),
            ),

            // ═══════════════════════════════════════
            // CASTING PAGE
            // ═══════════════════════════════════════
            'casting' => array(
                'hero_title' => array(
                    'it' => 'CASTING APERTI',
                    'en' => 'OPEN CASTINGS',
                    'fr' => 'CASTINGS OUVERTS',
                    'es' => 'CASTINGS ABIERTOS',
                ),
                'hero_subtitle' => array(
                    'it' => 'Candidati ai casting attivi nella tua zona',
                    'en' => 'Apply for active castings in your area',
                    'fr' => 'Postulez aux castings actifs dans votre r&eacute;gion',
                    'es' => 'Postula a los castings activos en tu zona',
                ),
                'social_text' => array(
                    'it' => 'Seguici per i nuovi casting',
                    'en' => 'Follow us for new castings',
                    'fr' => 'Suivez-nous pour les nouveaux castings',
                    'es' => 'S&iacute;guenos para nuevos castings',
                ),
                'filter_all' => array(
                    'it' => 'Tutte le regioni',
                    'en' => 'All regions',
                    'fr' => 'Toutes les r&eacute;gions',
                    'es' => 'Todas las regiones',
                ),
            ),

            // ═══════════════════════════════════════
            // HOSTESS LIVE FORM PAGE
            // ═══════════════════════════════════════
            'hostess_live' => array(
                // Hero
                'breadcrumb' => array(
                    'it' => 'PREVENTIVO HOSTESS',
                    'en' => 'HOSTESS QUOTE',
                    'fr' => 'DEVIS H&Ocirc;TESSES',
                    'es' => 'PRESUPUESTO AZAFATAS',
                ),
                'hero_title' => array(
                    'it' => 'Preventivo Live.',
                    'en' => 'Live Quote.',
                    'fr' => 'Devis en direct.',
                    'es' => 'Presupuesto en vivo.',
                ),
                'hero_subtitle_bold' => array(
                    'it' => 'Costruisci il tuo preventivo in pochi secondi.',
                    'en' => 'Build your quote in just a few seconds.',
                    'fr' => 'Cr&eacute;ez votre devis en quelques secondes.',
                    'es' => 'Crea tu presupuesto en pocos segundos.',
                ),
                'hero_subtitle_text' => array(
                    'it' => 'Ricevi subito conferma e una selezione di profili pronti per te.',
                    'en' => 'Get instant confirmation and a selection of profiles ready for you.',
                    'fr' => 'Recevez imm&eacute;diatement une confirmation et une s&eacute;lection de profils pr&ecirc;ts pour vous.',
                    'es' => 'Recibe confirmaci&oacute;n inmediata y una selecci&oacute;n de perfiles listos para ti.',
                ),
                'hero_badge' => array(
                    'it' => '&#10024; TARIFFA GARANTITA CON CONFERMA ENTRO 24 ORE',
                    'en' => '&#10024; GUARANTEED RATE WITH CONFIRMATION WITHIN 24 HOURS',
                    'fr' => '&#10024; TARIF GARANTI AVEC CONFIRMATION SOUS 24 HEURES',
                    'es' => '&#10024; TARIFA GARANTIZADA CON CONFIRMACI&Oacute;N EN 24 HORAS',
                ),
                // Step 1 — Città
                'step1_title' => array(
                    'it' => 'Citt&agrave;/provincia dell\'evento',
                    'en' => 'City/province of the event',
                    'fr' => 'Ville/province de l\'&eacute;v&eacute;nement',
                    'es' => 'Ciudad/provincia del evento',
                ),
                'select_placeholder' => array(
                    'it' => '-- Seleziona --',
                    'en' => '-- Select --',
                    'fr' => '-- S&eacute;lectionner --',
                    'es' => '-- Seleccionar --',
                ),
                'group_main' => array(
                    'it' => 'Principali',
                    'en' => 'Main',
                    'fr' => 'Principales',
                    'es' => 'Principales',
                ),
                'group_north' => array(
                    'it' => 'Nord',
                    'en' => 'North',
                    'fr' => 'Nord',
                    'es' => 'Norte',
                ),
                'group_center' => array(
                    'it' => 'Centro',
                    'en' => 'Centre',
                    'fr' => 'Centre',
                    'es' => 'Centro',
                ),
                'group_south' => array(
                    'it' => 'Sud e Isole',
                    'en' => 'South &amp; Islands',
                    'fr' => 'Sud &amp; &Icirc;les',
                    'es' => 'Sur e Islas',
                ),
                // Step 2 — Operativi
                'step2_title' => array(
                    'it' => 'Dettagli operativi',
                    'en' => 'Operational details',
                    'fr' => 'D&eacute;tails op&eacute;rationnels',
                    'es' => 'Detalles operativos',
                ),
                'step2_people' => array(
                    'it' => 'Persone',
                    'en' => 'People',
                    'fr' => 'Personnes',
                    'es' => 'Personas',
                ),
                'step2_days' => array(
                    'it' => 'Giorni',
                    'en' => 'Days',
                    'fr' => 'Jours',
                    'es' => 'D&iacute;as',
                ),
                'step2_hours' => array(
                    'it' => 'Ore/giorno',
                    'en' => 'Hours/day',
                    'fr' => 'Heures/jour',
                    'es' => 'Horas/d&iacute;a',
                ),
                'step2_hint' => array(
                    'it' => '&#128161; Sconto automatico per volumi (fino a -15% persone, -12% giorni)',
                    'en' => '&#128161; Automatic volume discount (up to -15% people, -12% days)',
                    'fr' => '&#128161; Remise automatique sur volume (jusqu\'&agrave; -15% personnes, -12% jours)',
                    'es' => '&#128161; Descuento autom&aacute;tico por volumen (hasta -15% personas, -12% d&iacute;as)',
                ),
                // Step 3 — Tipo personale
                'step3_title' => array(
                    'it' => 'Tipo di personale',
                    'en' => 'Staff type',
                    'fr' => 'Type de personnel',
                    'es' => 'Tipo de personal',
                ),
                'step3_hostess_desc' => array(
                    'it' => 'Personale femminile',
                    'en' => 'Female staff',
                    'fr' => 'Personnel f&eacute;minin',
                    'es' => 'Personal femenino',
                ),
                'step3_steward_desc' => array(
                    'it' => 'Personale maschile',
                    'en' => 'Male staff',
                    'fr' => 'Personnel masculin',
                    'es' => 'Personal masculino',
                ),
                // Step 4 — Presenza
                'step4_title' => array(
                    'it' => 'Livello di presenza',
                    'en' => 'Presence level',
                    'fr' => 'Niveau de pr&eacute;sence',
                    'es' => 'Nivel de presencia',
                ),
                'step4_basic_title' => array(
                    'it' => 'Standard',
                    'en' => 'Standard',
                    'fr' => 'Standard',
                    'es' => 'Est&aacute;ndar',
                ),
                'step4_basic_desc' => array(
                    'it' => 'Nessun requisito estetico',
                    'en' => 'No aesthetic requirements',
                    'fr' => 'Aucune exigence esth&eacute;tique',
                    'es' => 'Sin requisitos est&eacute;ticos',
                ),
                'step4_good_title' => array(
                    'it' => 'Buona presenza',
                    'en' => 'Good presence',
                    'fr' => 'Bonne pr&eacute;sence',
                    'es' => 'Buena presencia',
                ),
                'step4_good_desc' => array(
                    'it' => 'Presenza curata',
                    'en' => 'Groomed appearance',
                    'fr' => 'Apparence soign&eacute;e',
                    'es' => 'Apariencia cuidada',
                ),
                'step4_model_title' => array(
                    'it' => 'Modello/a',
                    'en' => 'Model',
                    'fr' => 'Mannequin',
                    'es' => 'Modelo',
                ),
                'step4_model_desc' => array(
                    'it' => 'Professionale fashion',
                    'en' => 'Fashion professional',
                    'fr' => 'Professionnel(le) fashion',
                    'es' => 'Profesional fashion',
                ),
                // Step 5 — Altezza
                'step5_title' => array(
                    'it' => 'Altezza (opzionale)',
                    'en' => 'Height (optional)',
                    'fr' => 'Taille (optionnel)',
                    'es' => 'Altura (opcional)',
                ),
                'step5_any' => array(
                    'it' => 'Non importante',
                    'en' => 'Not important',
                    'fr' => 'Peu importe',
                    'es' => 'No importante',
                ),
                // Step 6 — Lingue
                'step6_title' => array(
                    'it' => 'Competenze linguistiche',
                    'en' => 'Language skills',
                    'fr' => 'Comp&eacute;tences linguistiques',
                    'es' => 'Competencias ling&uuml;&iacute;sticas',
                ),
                'step6_it' => array(
                    'it' => 'Solo italiano',
                    'en' => 'Italian only',
                    'fr' => 'Italien uniquement',
                    'es' => 'Solo italiano',
                ),
                'step6_en' => array(
                    'it' => 'Italiano + Inglese',
                    'en' => 'Italian + English',
                    'fr' => 'Italien + Anglais',
                    'es' => 'Italiano + Ingl&eacute;s',
                ),
                'step6_multi' => array(
                    'it' => 'Multilingue',
                    'en' => 'Multilingual',
                    'fr' => 'Multilingue',
                    'es' => 'Multiling&uuml;e',
                ),
                // Step 7 — Pagamento
                'step7_title' => array(
                    'it' => 'Modalit&agrave; di pagamento',
                    'en' => 'Payment method',
                    'fr' => 'Mode de paiement',
                    'es' => 'Modalidad de pago',
                ),
                'step7_advance_title' => array(
                    'it' => '100% Anticipato',
                    'en' => '100% Upfront',
                    'fr' => '100% en avance',
                    'es' => '100% Anticipado',
                ),
                'step7_advance_label' => array(
                    'it' => 'Miglior prezzo',
                    'en' => 'Best price',
                    'fr' => 'Meilleur prix',
                    'es' => 'Mejor precio',
                ),
                'step7_post_title' => array(
                    'it' => 'Post evento',
                    'en' => 'After event',
                    'fr' => 'Apr&egrave;s l\'&eacute;v&eacute;nement',
                    'es' => 'Post evento',
                ),
                'step7_post_desc' => array(
                    'it' => 'Acconto 50%',
                    'en' => '50% deposit',
                    'fr' => 'Acompte 50%',
                    'es' => 'Anticipo 50%',
                ),
                // Step 8 — Periodo
                'step8_title' => array(
                    'it' => 'Periodo dell\'evento',
                    'en' => 'Event period',
                    'fr' => 'P&eacute;riode de l\'&eacute;v&eacute;nement',
                    'es' => 'Per&iacute;odo del evento',
                ),
                'step8_date_start' => array(
                    'it' => 'Data inizio',
                    'en' => 'Start date',
                    'fr' => 'Date de d&eacute;but',
                    'es' => 'Fecha de inicio',
                ),
                'step8_date_end' => array(
                    'it' => 'Data fine (opz.)',
                    'en' => 'End date (opt.)',
                    'fr' => 'Date de fin (opt.)',
                    'es' => 'Fecha de fin (opc.)',
                ),
                // Step 9 — Descrizione
                'step9_title' => array(
                    'it' => 'Descrizione progetto (opzionale)',
                    'en' => 'Project description (optional)',
                    'fr' => 'Description du projet (optionnel)',
                    'es' => 'Descripci&oacute;n del proyecto (opcional)',
                ),
                'step9_placeholder' => array(
                    'it' => 'Tipo di evento, dress code, compiti...',
                    'en' => 'Event type, dress code, tasks...',
                    'fr' => 'Type d\'&eacute;v&eacute;nement, dress code, t&acirc;ches...',
                    'es' => 'Tipo de evento, dress code, tareas...',
                ),
                // Step 10 — Dati
                'step10_title' => array(
                    'it' => 'I tuoi dati',
                    'en' => 'Your details',
                    'fr' => 'Vos coordonn&eacute;es',
                    'es' => 'Tus datos',
                ),
                'step10_name_label' => array(
                    'it' => 'Nome *',
                    'en' => 'Name *',
                    'fr' => 'Nom *',
                    'es' => 'Nombre *',
                ),
                'step10_company_label' => array(
                    'it' => 'Azienda *',
                    'en' => 'Company *',
                    'fr' => 'Entreprise *',
                    'es' => 'Empresa *',
                ),
                'step10_email_label' => array(
                    'it' => 'Email *',
                    'en' => 'Email *',
                    'fr' => 'Email *',
                    'es' => 'Correo *',
                ),
                'step10_phone_label' => array(
                    'it' => 'Telefono *',
                    'en' => 'Phone *',
                    'fr' => 'T&eacute;l&eacute;phone *',
                    'es' => 'Tel&eacute;fono *',
                ),
                'step10_name_ph' => array(
                    'it' => 'Il tuo nome',
                    'en' => 'Your name',
                    'fr' => 'Votre nom',
                    'es' => 'Tu nombre',
                ),
                'step10_company_ph' => array(
                    'it' => 'Nome azienda',
                    'en' => 'Company name',
                    'fr' => 'Nom de l\'entreprise',
                    'es' => 'Nombre de empresa',
                ),
                'step10_phone_ph' => array(
                    'it' => '+39...',
                    'en' => '+...',
                    'fr' => '+...',
                    'es' => '+...',
                ),
                'privacy_text' => array(
                    'it' => 'Accetto il trattamento dei dati secondo la',
                    'en' => 'I accept the processing of my data according to the',
                    'fr' => 'J\'accepte le traitement de mes donn&eacute;es conform&eacute;ment &agrave; la',
                    'es' => 'Acepto el tratamiento de mis datos seg&uacute;n la',
                ),
                'privacy_link' => array(
                    'it' => 'privacy policy',
                    'en' => 'privacy policy',
                    'fr' => 'politique de confidentialit&eacute;',
                    'es' => 'pol&iacute;tica de privacidad',
                ),
                'submit_btn' => array(
                    'it' => '&#128640; INVIA RICHIESTA PREVENTIVO',
                    'en' => '&#128640; SEND QUOTE REQUEST',
                    'fr' => '&#128640; ENVOYER LA DEMANDE DE DEVIS',
                    'es' => '&#128640; ENVIAR SOLICITUD DE PRESUPUESTO',
                ),
                'submit_note' => array(
                    'it' => 'Ti contattiamo in pochi minuti &bull; Assistenza H24 &bull; Profili selezionati',
                    'en' => 'We\'ll contact you in minutes &bull; 24/7 support &bull; Selected profiles',
                    'fr' => 'Nous vous contactons en quelques minutes &bull; Assistance H24 &bull; Profils s&eacute;lectionn&eacute;s',
                    'es' => 'Te contactamos en minutos &bull; Asistencia H24 &bull; Perfiles seleccionados',
                ),
                // Sticky bar
                'sticky_initial' => array(
                    'it' => 'Seleziona le opzioni',
                    'en' => 'Select options',
                    'fr' => 'S&eacute;lectionnez les options',
                    'es' => 'Selecciona las opciones',
                ),
                'sticky_no_presence' => array(
                    'it' => 'Seleziona livello di presenza',
                    'en' => 'Select presence level',
                    'fr' => 'S&eacute;lectionnez le niveau de pr&eacute;sence',
                    'es' => 'Selecciona el nivel de presencia',
                ),
                'sticky_btn' => array(
                    'it' => '&#128233; INVIA',
                    'en' => '&#128233; SEND',
                    'fr' => '&#128233; ENVOYER',
                    'es' => '&#128233; ENVIAR',
                ),
                // JS alerts
                'alert_privacy' => array(
                    'it' => 'Per favore accetta la privacy policy per continuare.',
                    'en' => 'Please accept the privacy policy to continue.',
                    'fr' => 'Veuillez accepter la politique de confidentialit&eacute; pour continuer.',
                    'es' => 'Por favor, acepta la pol&iacute;tica de privacidad para continuar.',
                ),
            ),

        );
    }
}
