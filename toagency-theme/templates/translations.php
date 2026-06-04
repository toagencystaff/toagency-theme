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
            // BLOG ARCHIVE
            // ═══════════════════════════════════════
            'blog' => array(
                'hero_title' => array('it'=>'Blog','en'=>'Blog','fr'=>'Blog','es'=>'Blog'),
                'hero_sub'   => array(
                    'it'=>'Backstage, articoli e video dal mondo TOAgency',
                    'en'=>'Backstage, articles and videos from the world of TOAgency',
                    'fr'=>"Coulisses, articles et vidéos de l'univers TOAgency",
                    'es'=>'Backstage, artículos y vídeos del mundo de TOAgency',
                ),
                'read_more'  => array('it'=>'Leggi','en'=>'Read','fr'=>'Lire','es'=>'Leer'),
                'empty'      => array(
                    'it'=>'Gli articoli saranno disponibili presto. Torna a trovarci!',
                    'en'=>'Articles will be available soon. Check back soon!',
                    'fr'=>'Les articles seront bientôt disponibles. Revenez vite !',
                    'es'=>'Los artículos estarán disponibles pronto. ¡Vuelve pronto!',
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
                'hero_btn_student' => array(
                    'it' => 'Sono uno studente &mdash; Student Program',
                    'en' => 'I\'m a student &mdash; Student Program',
                    'fr' => 'Je suis &eacute;tudiant &mdash; Student Program',
                    'es' => 'Soy estudiante &mdash; Student Program',
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
                'ph_phone' => array(
                    'it' => '+39 333 000 0000',
                    'en' => '+44 7000 000000',
                    'fr' => '+33 6 00 00 00 00',
                    'es' => '+34 600 000 000',
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

                // ── Hero ──
                'breadcrumb' => array(
                    'it' => 'PREVENTIVO STAFF EVENTI',
                    'en' => 'EVENTS STAFF QUOTE',
                    'fr' => 'DEVIS PERSONNEL &Eacute;V&Eacute;NEMENTS',
                    'es' => 'PRESUPUESTO PERSONAL EVENTOS',
                ),
                'hero_title' => array(
                    'it' => 'Preventivo Live.',
                    'en' => 'Live Quote.',
                    'fr' => 'Devis en direct.',
                    'es' => 'Presupuesto en vivo.',
                ),
                'hero_subtitle_bold' => array(
                    'it' => 'Costruisci il tuo preventivo in tempo reale.',
                    'en' => 'Build your quote in real time.',
                    'fr' => 'Cr&eacute;ez votre devis en temps r&eacute;el.',
                    'es' => 'Crea tu presupuesto en tiempo real.',
                ),
                'hero_subtitle_text' => array(
                    'it' => 'Seleziona il personale, ricevi subito il prezzo e una selezione di profili.',
                    'en' => 'Select the staff, get the price and a profile selection instantly.',
                    'fr' => 'S&eacute;lectionnez le personnel, obtenez imm&eacute;diatement le prix et une s&eacute;lection de profils.',
                    'es' => 'Selecciona el personal, recibe el precio y una selecci&oacute;n de perfiles al instante.',
                ),
                'hero_badge' => array(
                    'it' => 'TARIFFA INDICATIVA &bull; CONFERMA ENTRO 24H',
                    'en' => 'INDICATIVE RATE &bull; CONFIRMATION WITHIN 24H',
                    'fr' => 'TARIF INDICATIF &bull; CONFIRMATION SOUS 24H',
                    'es' => 'TARIFA INDICATIVA &bull; CONFIRMACI&Oacute;N EN 24H',
                ),

                // ── Step 1: Tipo di personale ──
                'step1_title' => array(
                    'it' => 'Tipo di personale',
                    'en' => 'Staff type',
                    'fr' => 'Type de personnel',
                    'es' => 'Tipo de personal',
                ),
                'step1_sub' => array(
                    'it' => 'Scegli la categoria e seleziona la figura pi&ugrave; adatta al tuo evento.',
                    'en' => 'Choose the category and select the most suitable figure for your event.',
                    'fr' => 'Choisissez la cat&eacute;gorie et s&eacute;lectionnez le profil le plus adapt&eacute; &agrave; votre &eacute;v&eacute;nement.',
                    'es' => 'Elige la categor&iacute;a y selecciona el perfil m&aacute;s adecuado para tu evento.',
                ),

                // Accordion 1 — Accoglienza & Fiera
                'acc1_label' => array(
                    'it' => 'Accoglienza &amp; Fiera',
                    'en' => 'Reception &amp; Trade Fair',
                    'fr' => 'Accueil &amp; Foire',
                    'es' => 'Recepci&oacute;n &amp; Feria',
                ),
                'acc1_hint' => array(
                    'it' => 'Hostess &middot; Steward &middot; Accrediti',
                    'en' => 'Hostess &middot; Steward &middot; Accreditations',
                    'fr' => 'H&ocirc;tesses &middot; Stewards &middot; Accr&eacute;ditations',
                    'es' => 'Azafatas &middot; Stewards &middot; Acreditaciones',
                ),
                'fig_hostess_title' => array(
                    'it' => 'Hostess / Steward',
                    'en' => 'Hostess / Steward',
                    'fr' => 'H&ocirc;tesse / Steward',
                    'es' => 'Azafata / Steward',
                ),
                'fig_hostess_desc' => array(
                    'it' => 'Accoglienza, registrazione, assistenza',
                    'en' => 'Reception, registration, assistance',
                    'fr' => 'Accueil, inscription, assistance',
                    'es' => 'Recepci&oacute;n, registro, asistencia',
                ),
                'fig_hostess_price' => array(
                    'it' => 'da &euro;137/6h',
                    'en' => 'from &euro;137/6h',
                    'fr' => '&agrave; partir de &euro;137/6h',
                    'es' => 'desde &euro;137/6h',
                ),
                'fig_immagine_title' => array(
                    'it' => 'Hostess Immagine',
                    'en' => 'Image Hostess',
                    'fr' => 'H&ocirc;tesse Image',
                    'es' => 'Azafata Imagen',
                ),
                'fig_immagine_desc' => array(
                    'it' => 'Presenza curata, immagine coordinata',
                    'en' => 'Groomed presence, coordinated image',
                    'fr' => 'Pr&eacute;sence soign&eacute;e, image coordonn&eacute;e',
                    'es' => 'Presencia cuidada, imagen coordinada',
                ),
                'fig_immagine_price' => array(
                    'it' => 'da &euro;169/6h',
                    'en' => 'from &euro;169/6h',
                    'fr' => '&agrave; partir de &euro;169/6h',
                    'es' => 'desde &euro;169/6h',
                ),
                'fig_accrediti_title' => array(
                    'it' => 'Addetto Accrediti',
                    'en' => 'Accreditation Staff',
                    'fr' => 'Agent d\'accr&eacute;ditation',
                    'es' => 'Personal de acreditaciones',
                ),
                'fig_accrediti_desc' => array(
                    'it' => 'Segreteria organizzativa, check-in',
                    'en' => 'Organizational secretariat, check-in',
                    'fr' => 'Secr&eacute;tariat organisationnel, check-in',
                    'es' => 'Secretar&iacute;a organizativa, check-in',
                ),
                'fig_accrediti_price' => array(
                    'it' => 'da &euro;147/6h',
                    'en' => 'from &euro;147/6h',
                    'fr' => '&agrave; partir de &euro;147/6h',
                    'es' => 'desde &euro;147/6h',
                ),

                // Accordion 2 — Promozione & Immagine
                'acc2_label' => array(
                    'it' => 'Promozione &amp; Immagine',
                    'en' => 'Promotion &amp; Image',
                    'fr' => 'Promotion &amp; Image',
                    'es' => 'Promoci&oacute;n &amp; Imagen',
                ),
                'acc2_hint' => array(
                    'it' => 'Promoter &middot; Modelli',
                    'en' => 'Promoters &middot; Models',
                    'fr' => 'Promoteurs &middot; Mannequins',
                    'es' => 'Promotores &middot; Modelos',
                ),
                'fig_promoter_title' => array(
                    'it' => 'Promoter',
                    'en' => 'Promoter',
                    'fr' => 'Promoteur/Promotrice',
                    'es' => 'Promotor/a',
                ),
                'fig_promoter_desc' => array(
                    'it' => 'Distribuzione materiale, ingaggio pubblico',
                    'en' => 'Material distribution, public engagement',
                    'fr' => 'Distribution de mat&eacute;riel, engagement public',
                    'es' => 'Distribuci&oacute;n de material, captaci&oacute;n de p&uacute;blico',
                ),
                'fig_promoter_price' => array(
                    'it' => 'da &euro;149/6h',
                    'en' => 'from &euro;149/6h',
                    'fr' => '&agrave; partir de &euro;149/6h',
                    'es' => 'desde &euro;149/6h',
                ),
                'fig_modello_title' => array(
                    'it' => 'Modello / Modella',
                    'en' => 'Model',
                    'fr' => 'Mannequin',
                    'es' => 'Modelo/a',
                ),
                'fig_modello_desc' => array(
                    'it' => 'Professionista fashion, immagine premium',
                    'en' => 'Fashion professional, premium image',
                    'fr' => 'Professionnel(le) de la mode, image premium',
                    'es' => 'Profesional fashion, imagen premium',
                ),
                'fig_modello_price' => array(
                    'it' => 'da &euro;299/6h',
                    'en' => 'from &euro;299/6h',
                    'fr' => '&agrave; partir de &euro;299/6h',
                    'es' => 'desde &euro;299/6h',
                ),

                // Accordion 3 — Lingue & Coordinamento
                'acc3_label' => array(
                    'it' => 'Lingue &amp; Coordinamento',
                    'en' => 'Languages &amp; Coordination',
                    'fr' => 'Langues &amp; Coordination',
                    'es' => 'Idiomas &amp; Coordinaci&oacute;n',
                ),
                'acc3_hint' => array(
                    'it' => 'Interpreti &middot; Coordinatori',
                    'en' => 'Interpreters &middot; Coordinators',
                    'fr' => 'Interpr&egrave;tes &middot; Coordinateurs',
                    'es' => 'Int&eacute;rpretes &middot; Coordinadores',
                ),
                'fig_interprete_title' => array(
                    'it' => 'Interprete Trattativa',
                    'en' => 'Negotiation Interpreter',
                    'fr' => 'Interpr&egrave;te commercial',
                    'es' => 'Int&eacute;rprete de negociaci&oacute;n',
                ),
                'fig_interprete_desc' => array(
                    'it' => '2 lingue, trattativa commerciale',
                    'en' => '2 languages, commercial negotiation',
                    'fr' => '2 langues, n&eacute;gociation commerciale',
                    'es' => '2 idiomas, negociaci&oacute;n comercial',
                ),
                'fig_interprete_price' => array(
                    'it' => 'da &euro;299/6h',
                    'en' => 'from &euro;299/6h',
                    'fr' => '&agrave; partir de &euro;299/6h',
                    'es' => 'desde &euro;299/6h',
                ),
                'fig_interprete_multi_title' => array(
                    'it' => 'Interprete Multilingue',
                    'en' => 'Multilingual Interpreter',
                    'fr' => 'Interpr&egrave;te multilingue',
                    'es' => 'Int&eacute;rprete multiling&uuml;e',
                ),
                'fig_interprete_multi_desc' => array(
                    'it' => '3+ lingue, accoglienza o trattativa',
                    'en' => '3+ languages, reception or negotiation',
                    'fr' => '3+ langues, accueil ou n&eacute;gociation',
                    'es' => '3+ idiomas, recepci&oacute;n o negociaci&oacute;n',
                ),
                'fig_interprete_multi_price' => array(
                    'it' => 'da &euro;409/6h',
                    'en' => 'from &euro;409/6h',
                    'fr' => '&agrave; partir de &euro;409/6h',
                    'es' => 'desde &euro;409/6h',
                ),
                'fig_coordinatore_title' => array(
                    'it' => 'Coordinatore Evento',
                    'en' => 'Event Coordinator',
                    'fr' => 'Coordinateur d\'&eacute;v&eacute;nement',
                    'es' => 'Coordinador de evento',
                ),
                'fig_coordinatore_desc' => array(
                    'it' => 'Supervisione staff, briefing, gestione',
                    'en' => 'Staff supervision, briefing, management',
                    'fr' => 'Supervision du personnel, briefing, gestion',
                    'es' => 'Supervisi&oacute;n de personal, briefing, gesti&oacute;n',
                ),
                'fig_coordinatore_price' => array(
                    'it' => 'da &euro;257/6h',
                    'en' => 'from &euro;257/6h',
                    'fr' => '&agrave; partir de &euro;257/6h',
                    'es' => 'desde &euro;257/6h',
                ),

                // Accordion 4 — Logistica & Supporto
                'acc4_label' => array(
                    'it' => 'Logistica &amp; Supporto',
                    'en' => 'Logistics &amp; Support',
                    'fr' => 'Logistique &amp; Support',
                    'es' => 'Log&iacute;stica &amp; Soporte',
                ),
                'acc4_hint' => array(
                    'it' => 'Driver &middot; Tecnici &middot; Runner',
                    'en' => 'Drivers &middot; Technicians &middot; Runners',
                    'fr' => 'Chauffeurs &middot; Techniciens &middot; Runners',
                    'es' => 'Conductores &middot; T&eacute;cnicos &middot; Runners',
                ),
                'fig_driver_title' => array(
                    'it' => 'Driver Berlina',
                    'en' => 'Sedan Driver',
                    'fr' => 'Chauffeur berline',
                    'es' => 'Conductor berlina',
                ),
                'fig_driver_desc' => array(
                    'it' => 'Autista con auto, 8h disponibilit&agrave;',
                    'en' => 'Driver with car, 8h availability',
                    'fr' => 'Chauffeur avec voiture, 8h de disponibilit&eacute;',
                    'es' => 'Conductor con coche, 8h disponibilidad',
                ),
                'fig_driver_price' => array(
                    'it' => 'da &euro;379/6h',
                    'en' => 'from &euro;379/6h',
                    'fr' => '&agrave; partir de &euro;379/6h',
                    'es' => 'desde &euro;379/6h',
                ),
                'fig_driver_van_title' => array(
                    'it' => 'Driver Van',
                    'en' => 'Van Driver',
                    'fr' => 'Chauffeur van',
                    'es' => 'Conductor furgoneta',
                ),
                'fig_driver_van_desc' => array(
                    'it' => 'Autista con van fino a 8 posti',
                    'en' => 'Driver with van up to 8 seats',
                    'fr' => 'Chauffeur avec van jusqu\'&agrave; 8 places',
                    'es' => 'Conductor con furgoneta hasta 8 plazas',
                ),
                'fig_driver_van_price' => array(
                    'it' => 'da &euro;468/6h',
                    'en' => 'from &euro;468/6h',
                    'fr' => '&agrave; partir de &euro;468/6h',
                    'es' => 'desde &euro;468/6h',
                ),
                'fig_tecnico_title' => array(
                    'it' => 'Tecnico Allestimento',
                    'en' => 'Setup Technician',
                    'fr' => 'Technicien d\'installation',
                    'es' => 'T&eacute;cnico de montaje',
                ),
                'fig_tecnico_desc' => array(
                    'it' => 'Montaggio, smontaggio, logistica',
                    'en' => 'Assembly, dismantling, logistics',
                    'fr' => 'Montage, d&eacute;montage, logistique',
                    'es' => 'Montaje, desmontaje, log&iacute;stica',
                ),
                'fig_tecnico_price' => array(
                    'it' => 'da &euro;189/6h',
                    'en' => 'from &euro;189/6h',
                    'fr' => '&agrave; partir de &euro;189/6h',
                    'es' => 'desde &euro;189/6h',
                ),
                'fig_runner_title' => array(
                    'it' => 'Runner',
                    'en' => 'Runner',
                    'fr' => 'Runner',
                    'es' => 'Runner',
                ),
                'fig_runner_desc' => array(
                    'it' => 'Supporto operativo, consegne, facchino',
                    'en' => 'Operational support, deliveries, porter',
                    'fr' => 'Support op&eacute;rationnel, livraisons, porteur',
                    'es' => 'Apoyo operativo, entregas, mozo',
                ),
                'fig_runner_price' => array(
                    'it' => 'da &euro;119/6h',
                    'en' => 'from &euro;119/6h',
                    'fr' => '&agrave; partir de &euro;119/6h',
                    'es' => 'desde &euro;119/6h',
                ),

                // ── Step 1B: Livello di presenza ──
                'presence_title' => array(
                    'it' => 'Livello di presenza',
                    'en' => 'Presence level',
                    'fr' => 'Niveau de pr&eacute;sentation',
                    'es' => 'Nivel de presencia',
                ),
                'presence_sub' => array(
                    'it' => 'Seleziona il livello estetico richiesto per il personale.',
                    'en' => 'Select the required aesthetic level for the staff.',
                    'fr' => 'S&eacute;lectionnez le niveau esth&eacute;tique requis pour le personnel.',
                    'es' => 'Selecciona el nivel est&eacute;tico requerido para el personal.',
                ),
                'presence_standard_title' => array(
                    'it' => 'Standard',
                    'en' => 'Standard',
                    'fr' => 'Standard',
                    'es' => 'Est&aacute;ndar',
                ),
                'presence_standard_desc' => array(
                    'it' => 'Nessun requisito estetico particolare',
                    'en' => 'No specific aesthetic requirements',
                    'fr' => 'Aucun pr&eacute;requis esth&eacute;tique particulier',
                    'es' => 'Sin requisitos est&eacute;ticos espec&iacute;ficos',
                ),
                'presence_standard_price' => array(
                    'it' => 'Incluso',
                    'en' => 'Included',
                    'fr' => 'Inclus',
                    'es' => 'Incluido',
                ),
                'presence_good_title' => array(
                    'it' => 'Buona presenza',
                    'en' => 'Good presence',
                    'fr' => 'Bonne pr&eacute;sentation',
                    'es' => 'Buena presencia',
                ),
                'presence_good_desc' => array(
                    'it' => 'Aspetto curato, immagine professionale',
                    'en' => 'Well-groomed appearance, professional image',
                    'fr' => 'Apparence soign&eacute;e, image professionnelle',
                    'es' => 'Aspecto cuidado, imagen profesional',
                ),
                'presence_high_title' => array(
                    'it' => 'Alta presenza',
                    'en' => 'High presence',
                    'fr' => 'Pr&eacute;sentation premium',
                    'es' => 'Alta presencia',
                ),
                'presence_high_desc' => array(
                    'it' => 'Look da modello/a, altezza 175+',
                    'en' => 'Model-like look, height 175+',
                    'fr' => 'Look mannequin, taille 175+',
                    'es' => 'Look de modelo/a, altura 175+',
                ),

                // ── Step 2: Paese e città ──
                'country_city_title' => array(
                    'it' => 'Paese e citt&agrave; dell\'evento',
                    'en' => 'Event country and city',
                    'fr' => 'Pays et ville de l\'&eacute;v&eacute;nement',
                    'es' => 'Pa&iacute;s y ciudad del evento',
                ),
                'country_label' => array(
                    'it' => 'Paese',
                    'en' => 'Country',
                    'fr' => 'Pays',
                    'es' => 'Pa&iacute;s',
                ),
                'country_placeholder' => array(
                    'it' => 'Seleziona il paese',
                    'en' => 'Select the country',
                    'fr' => 'S&eacute;lectionnez le pays',
                    'es' => 'Selecciona el pa&iacute;s',
                ),
                'country_other' => array(
                    'it' => 'Altro paese',
                    'en' => 'Other country',
                    'fr' => 'Autre pays',
                    'es' => 'Otro pa&iacute;s',
                ),
                'city_label' => array(
                    'it' => 'Citt&agrave;',
                    'en' => 'City',
                    'fr' => 'Ville',
                    'es' => 'Ciudad',
                ),
                'city_first_select' => array(
                    'it' => 'Prima seleziona il paese',
                    'en' => 'First select the country',
                    'fr' => 'S&eacute;lectionnez d\'abord le pays',
                    'es' => 'Primero selecciona el pa&iacute;s',
                ),
                'city_other' => array(
                    'it' => 'Altra citt&agrave; (contattaci)',
                    'en' => 'Other city (contact us)',
                    'fr' => 'Autre ville (contactez-nous)',
                    'es' => 'Otra ciudad (cont&aacute;ctanos)',
                ),
                'city_select' => array(
                    'it' => 'Seleziona la citt&agrave;',
                    'en' => 'Select the city',
                    'fr' => 'S&eacute;lectionnez la ville',
                    'es' => 'Selecciona la ciudad',
                ),
                'country_hint' => array(
                    'it' => 'Operiamo anche in citt&agrave; e paesi non presenti in elenco. Seleziona &ldquo;Altro&rdquo; e descrivici le tue esigenze nella sezione progetto.',
                    'en' => 'We also operate in cities and countries not listed. Select &ldquo;Other&rdquo; and describe your needs in the project section.',
                    'fr' => 'Nous op&eacute;rons &eacute;galement dans des villes et pays non list&eacute;s. S&eacute;lectionnez &ldquo;Autre&rdquo; et d&eacute;crivez vos besoins dans la section projet.',
                    'es' => 'Tambi&eacute;n operamos en ciudades y pa&iacute;ses no incluidos en la lista. Selecciona &ldquo;Otro&rdquo; y desc&iacute;benos tus necesidades en la secci&oacute;n proyecto.',
                ),

                // ── Step 3: Quantità e durata ──
                'step3_title' => array(
                    'it' => 'Quantit&agrave; e durata',
                    'en' => 'Quantity and duration',
                    'fr' => 'Quantit&eacute; et dur&eacute;e',
                    'es' => 'Cantidad y duraci&oacute;n',
                ),
                'step3_people' => array(
                    'it' => 'Persone',
                    'en' => 'People',
                    'fr' => 'Personnes',
                    'es' => 'Personas',
                ),
                'step3_days' => array(
                    'it' => 'Giorni',
                    'en' => 'Days',
                    'fr' => 'Jours',
                    'es' => 'D&iacute;as',
                ),
                'step3_hours' => array(
                    'it' => 'Ore / giorno',
                    'en' => 'Hours / day',
                    'fr' => 'Heures / jour',
                    'es' => 'Horas / d&iacute;a',
                ),
                'step3_hint' => array(
                    'it' => 'Sconti automatici per volumi: fino a -12% per persone, -10% per giorni multipli.',
                    'en' => 'Automatic volume discounts: up to -12% for people, -10% for multiple days.',
                    'fr' => 'Remises automatiques sur volume&nbsp;: jusqu\'&agrave; -12% pour le nombre de personnes, -10% pour les jours multiples.',
                    'es' => 'Descuentos autom&aacute;ticos por volumen: hasta -12% por personas, -10% por d&iacute;as m&uacute;ltiples.',
                ),

                // ── Step 4: Modalità di pagamento ──
                'step4_title' => array(
                    'it' => 'Modalit&agrave; di pagamento',
                    'en' => 'Payment method',
                    'fr' => 'Mode de paiement',
                    'es' => 'Modalidad de pago',
                ),
                'step4_sub' => array(
                    'it' => 'Il prezzo mostrato &egrave; quello con pagamento anticipato &mdash; il migliore disponibile.',
                    'en' => 'The price shown is the one with advance payment &mdash; the best available.',
                    'fr' => 'Le prix affich&eacute; est celui avec paiement anticip&eacute; &mdash; le meilleur disponible.',
                    'es' => 'El precio mostrado es el del pago anticipado &mdash; el mejor disponible.',
                ),
                'pay_advance_tag' => array(
                    'it' => '&#11088; Miglior prezzo',
                    'en' => '&#11088; Best price',
                    'fr' => '&#11088; Meilleur prix',
                    'es' => '&#11088; Mejor precio',
                ),
                'pay_advance_title' => array(
                    'it' => '100% Anticipato',
                    'en' => '100% Upfront',
                    'fr' => '100% en avance',
                    'es' => '100% Anticipado',
                ),
                'pay_advance_desc' => array(
                    'it' => 'Pagamento completo prima dell\'evento',
                    'en' => 'Full payment before the event',
                    'fr' => 'Paiement complet avant l\'&eacute;v&eacute;nement',
                    'es' => 'Pago completo antes del evento',
                ),
                'pay_advance_price' => array(
                    'it' => 'Prezzo base',
                    'en' => 'Base price',
                    'fr' => 'Prix de base',
                    'es' => 'Precio base',
                ),
                'pay_split24_tag' => array(
                    'it' => '&#9989; Consigliato',
                    'en' => '&#9989; Recommended',
                    'fr' => '&#9989; Recommand&eacute;',
                    'es' => '&#9989; Recomendado',
                ),
                'pay_split24_title' => array(
                    'it' => '50% + Saldo a fine evento',
                    'en' => '50% + balance at the end of the event',
                    'fr' => '50% + solde &agrave; la fin de l\'&eacute;v&eacute;nement',
                    'es' => '50% + saldo al final del evento',
                ),
                'pay_split24_desc' => array(
                    'it' => '50% anticipo, saldo entro 24h dalla fine dell\'evento',
                    'en' => '50% advance, balance within 24h after the end of the event',
                    'fr' => '50% d\'acompte, solde sous 24h apr&egrave;s la fin de l\'&eacute;v&eacute;nement',
                    'es' => '50% anticipo, saldo dentro de las 24h tras el final del evento',
                ),
                'pay_split30_title' => array(
                    'it' => '50% + Saldo a 30gg',
                    'en' => '50% + Balance at 30 days',
                    'fr' => '50% + Solde &agrave; 30 jours',
                    'es' => '50% + Saldo a 30 d&iacute;as',
                ),
                'pay_split30_desc' => array(
                    'it' => '50% anticipo, saldo a 30 giorni fine evento',
                    'en' => '50% advance, balance 30 days after end of event',
                    'fr' => '50% d\'acompte, solde 30 jours apr&egrave;s la fin de l\'&eacute;v&eacute;nement',
                    'es' => '50% anticipo, saldo a 30 d&iacute;as tras el final del evento',
                ),
                'pay_post30_title' => array(
                    'it' => 'Post-evento 30gg',
                    'en' => 'Post-event 30 days',
                    'fr' => 'Post-&eacute;v&eacute;nement 30 jours',
                    'es' => 'Post-evento 30 d&iacute;as',
                ),
                'pay_post30_desc' => array(
                    'it' => 'Nessun anticipo, pagamento a 30 giorni',
                    'en' => 'No advance, payment in 30 days',
                    'fr' => 'Aucun acompte, paiement &agrave; 30 jours',
                    'es' => 'Sin anticipo, pago a 30 d&iacute;as',
                ),
                'pay_post60_title' => array(
                    'it' => 'Post-evento 60gg',
                    'en' => 'Post-event 60 days',
                    'fr' => 'Post-&eacute;v&eacute;nement 60 jours',
                    'es' => 'Post-evento 60 d&iacute;as',
                ),
                'pay_post60_desc' => array(
                    'it' => 'Nessun anticipo, pagamento a 60 giorni',
                    'en' => 'No advance, payment in 60 days',
                    'fr' => 'Aucun acompte, paiement &agrave; 60 jours',
                    'es' => 'Sin anticipo, pago a 60 d&iacute;as',
                ),
                'pay_approval_warn' => array(
                    'it' => 'Soggetto ad approvazione',
                    'en' => 'Subject to approval',
                    'fr' => 'Sous r&eacute;serve d\'approbation',
                    'es' => 'Sujeto a aprobaci&oacute;n',
                ),
                'pay_disclaimer' => array(
                    'it' => 'Le modalit&agrave; senza anticipo (post-evento 30gg e 60gg) sono soggette a verifica e approvazione preventiva da parte di TOAgency. Ci riserviamo il diritto di richiedere condizioni di pagamento diverse o di non accettare la modalit&agrave; selezionata.',
                    'en' => 'No-advance payment methods (post-event 30d and 60d) are subject to verification and prior approval by TOAgency. We reserve the right to request different payment terms or to decline the selected option.',
                    'fr' => 'Les modes de paiement sans acompte (post-&eacute;v&eacute;nement 30j et 60j) sont soumis &agrave; v&eacute;rification et approbation pr&eacute;alable par TOAgency. Nous nous r&eacute;servons le droit de demander des conditions de paiement diff&eacute;rentes ou de refuser le mode s&eacute;lectionn&eacute;.',
                    'es' => 'Las modalidades sin anticipo (post-evento 30d y 60d) est&aacute;n sujetas a verificaci&oacute;n y aprobaci&oacute;n previa por parte de TOAgency. Nos reservamos el derecho de solicitar condiciones de pago diferentes o de no aceptar la modalidad seleccionada.',
                ),

                // ── Step 5: Supplementi ──
                'step5_title' => array(
                    'it' => 'Supplementi',
                    'en' => 'Supplements',
                    'fr' => 'Suppl&eacute;ments',
                    'es' => 'Suplementos',
                ),
                'step5_sub' => array(
                    'it' => 'Opzionali &mdash; seleziona solo se applicabili al tuo evento.',
                    'en' => 'Optional &mdash; select only if applicable to your event.',
                    'fr' => 'Optionnel &mdash; s&eacute;lectionnez uniquement si applicable &agrave; votre &eacute;v&eacute;nement.',
                    'es' => 'Opcional &mdash; selecciona solo si aplica a tu evento.',
                ),
                'sup_weekend' => array(
                    'it' => 'Weekend / Festivo',
                    'en' => 'Weekend / Holiday',
                    'fr' => 'Week-end / F&eacute;ri&eacute;',
                    'es' => 'Fin de semana / Festivo',
                ),
                'sup_night' => array(
                    'it' => 'Serale (dopo 21:00)',
                    'en' => 'Evening (after 21:00)',
                    'fr' => 'Soir&eacute;e (apr&egrave;s 21:00)',
                    'es' => 'Nocturno (despu&eacute;s de las 21:00)',
                ),
                'sup_urgent' => array(
                    'it' => 'Urgenza (&lt;72h)',
                    'en' => 'Urgent (&lt;72h)',
                    'fr' => 'Urgence (&lt;72h)',
                    'es' => 'Urgente (&lt;72h)',
                ),
                'sup_dress' => array(
                    'it' => 'Divisa specifica',
                    'en' => 'Specific uniform',
                    'fr' => 'Uniforme sp&eacute;cifique',
                    'es' => 'Uniforme espec&iacute;fico',
                ),

                // ── Step 6: Periodo dell'evento ──
                'step6_title' => array(
                    'it' => 'Periodo dell\'evento',
                    'en' => 'Event period',
                    'fr' => 'P&eacute;riode de l\'&eacute;v&eacute;nement',
                    'es' => 'Per&iacute;odo del evento',
                ),
                'step6_date_start' => array(
                    'it' => 'Data inizio',
                    'en' => 'Start date',
                    'fr' => 'Date de d&eacute;but',
                    'es' => 'Fecha de inicio',
                ),
                'step6_date_end' => array(
                    'it' => 'Data fine (opzionale)',
                    'en' => 'End date (optional)',
                    'fr' => 'Date de fin (optionnel)',
                    'es' => 'Fecha de fin (opcional)',
                ),

                // ── Step 7: Descrizione progetto ──
                'step7_title' => array(
                    'it' => 'Descrizione progetto',
                    'en' => 'Project description',
                    'fr' => 'Description du projet',
                    'es' => 'Descripci&oacute;n del proyecto',
                ),
                'step7_sub' => array(
                    'it' => 'Opzionale &mdash; tipo di evento, dress code, compiti specifici.',
                    'en' => 'Optional &mdash; event type, dress code, specific tasks.',
                    'fr' => 'Optionnel &mdash; type d\'&eacute;v&eacute;nement, dress code, t&acirc;ches sp&eacute;cifiques.',
                    'es' => 'Opcional &mdash; tipo de evento, dress code, tareas espec&iacute;ficas.',
                ),
                'step7_placeholder' => array(
                    'it' => 'Descrivi il tuo evento, esigenze particolari, dress code...',
                    'en' => 'Describe your event, specific needs, dress code...',
                    'fr' => 'D&eacute;crivez votre &eacute;v&eacute;nement, besoins particuliers, dress code...',
                    'es' => 'Describe tu evento, necesidades particulares, dress code...',
                ),

                // ── Step 8: Dati contatto ──
                'step8_title' => array(
                    'it' => 'I tuoi dati',
                    'en' => 'Your details',
                    'fr' => 'Vos coordonn&eacute;es',
                    'es' => 'Tus datos',
                ),
                'step8_name_label' => array(
                    'it' => 'Nome *',
                    'en' => 'Name *',
                    'fr' => 'Nom *',
                    'es' => 'Nombre *',
                ),
                'step8_name_ph' => array(
                    'it' => 'Il tuo nome',
                    'en' => 'Your name',
                    'fr' => 'Votre nom',
                    'es' => 'Tu nombre',
                ),
                'step8_company_label' => array(
                    'it' => 'Azienda *',
                    'en' => 'Company *',
                    'fr' => 'Entreprise *',
                    'es' => 'Empresa *',
                ),
                'step8_company_ph' => array(
                    'it' => 'Nome azienda',
                    'en' => 'Company name',
                    'fr' => 'Nom de l\'entreprise',
                    'es' => 'Nombre de empresa',
                ),
                'step8_email_label' => array(
                    'it' => 'Email *',
                    'en' => 'Email *',
                    'fr' => 'Email *',
                    'es' => 'Correo *',
                ),
                'step8_phone_label' => array(
                    'it' => 'Telefono *',
                    'en' => 'Phone *',
                    'fr' => 'T&eacute;l&eacute;phone *',
                    'es' => 'Tel&eacute;fono *',
                ),
                'extra_services_hint' => array(
                    'it' => 'Offriamo anche: security e controllo accessi, catering staff, tecnici audio/luci, social media manager on-site, tour leader, brand ambassador e altri profili specializzati. Per questi servizi, descrivili nella sezione progetto o contattaci direttamente.',
                    'en' => 'We also offer: security and access control, catering staff, audio/light technicians, on-site social media managers, tour leaders, brand ambassadors and other specialized profiles. For these services, describe them in the project section or contact us directly.',
                    'fr' => 'Nous proposons &eacute;galement&nbsp;: s&eacute;curit&eacute; et contr&ocirc;le des acc&egrave;s, personnel de catering, techniciens audio/lumi&egrave;re, social media managers sur site, tour leaders, brand ambassadors et autres profils sp&eacute;cialis&eacute;s. Pour ces services, d&eacute;crivez-les dans la section projet ou contactez-nous directement.',
                    'es' => 'Tambi&eacute;n ofrecemos: seguridad y control de accesos, personal de catering, t&eacute;cnicos de audio/iluminaci&oacute;n, social media manager on-site, tour leader, brand ambassador y otros perfiles especializados. Para estos servicios, desc&iacute;belos en la secci&oacute;n proyecto o cont&aacute;ctanos directamente.',
                ),
                'privacy_text' => array(
                    'it' => 'Accetto il trattamento dei dati secondo la %sprivacy policy%s',
                    'en' => 'I accept the processing of my data according to the %sprivacy policy%s',
                    'fr' => 'J\'accepte le traitement de mes donn&eacute;es conform&eacute;ment &agrave; la %spolitique de confidentialit&eacute;%s',
                    'es' => 'Acepto el tratamiento de mis datos seg&uacute;n la %spol&iacute;tica de privacidad%s',
                ),
                'submit_btn' => array(
                    'it' => 'INVIA RICHIESTA PREVENTIVO',
                    'en' => 'SEND QUOTE REQUEST',
                    'fr' => 'ENVOYER LA DEMANDE DE DEVIS',
                    'es' => 'ENVIAR SOLICITUD DE PRESUPUESTO',
                ),
                'price_note_title' => array(
                    'it' => 'Nota sui prezzi',
                    'en' => 'Price note',
                    'fr' => 'Note sur les prix',
                    'es' => 'Nota sobre los precios',
                ),
                'price_note_text' => array(
                    'it' => 'I prezzi indicati sono stime indicative basate sulle nostre tariffe standard con cui generalmente riusciamo a fornire personale qualificato. Il prezzo finale pu&ograve; variare in base alla disponibilit&agrave;, al profilo specifico richiesto e alle condizioni operative. Su richiesta, possiamo inviare una selezione di profili disponibili, ciascuno con la propria tariffa individuale, per permetterti di scegliere in base alle tue esigenze e al tuo budget. Fattori come urgenze, festivit&agrave;, orari serali e requisiti particolari possono influire sulla quotazione definitiva. La conferma del prezzo avviene entro 24 ore dal ricevimento della richiesta.',
                    'en' => 'The prices shown are indicative estimates based on our standard rates, with which we can generally provide qualified staff. The final price may vary based on availability, the specific profile requested and operating conditions. On request, we can send a selection of available profiles, each with their own individual rate, so you can choose according to your needs and budget. Factors such as urgency, public holidays, evening hours and specific requirements may affect the final quote. Price confirmation takes place within 24 hours of receiving the request.',
                    'fr' => 'Les prix indiqu&eacute;s sont des estimations indicatives bas&eacute;es sur nos tarifs standard, avec lesquels nous pouvons g&eacute;n&eacute;ralement fournir du personnel qualifi&eacute;. Le prix final peut varier en fonction de la disponibilit&eacute;, du profil sp&eacute;cifique demand&eacute; et des conditions op&eacute;rationnelles. Sur demande, nous pouvons envoyer une s&eacute;lection de profils disponibles, chacun avec son propre tarif individuel, pour vous permettre de choisir en fonction de vos besoins et de votre budget. Des facteurs tels que les urgences, les jours f&eacute;ri&eacute;s, les horaires du soir et les exigences particuli&egrave;res peuvent influencer le devis final. La confirmation du prix intervient dans les 24 heures suivant la r&eacute;ception de la demande.',
                    'es' => 'Los precios indicados son estimaciones indicativas basadas en nuestras tarifas est&aacute;ndar, con las que generalmente podemos proporcionar personal cualificado. El precio final puede variar en funci&oacute;n de la disponibilidad, el perfil espec&iacute;fico solicitado y las condiciones operativas. A petici&oacute;n, podemos enviar una selecci&oacute;n de perfiles disponibles, cada uno con su propia tarifa individual, para que puedas elegir seg&uacute;n tus necesidades y presupuesto. Factores como urgencias, festivos, horarios nocturnos y requisitos particulares pueden influir en la cotizaci&oacute;n definitiva. La confirmaci&oacute;n del precio se produce en las 24 horas siguientes a la recepci&oacute;n de la solicitud.',
                ),

                // ── Sticky bar ──
                'sticky_btn' => array(
                    'it' => 'INVIA',
                    'en' => 'SEND',
                    'fr' => 'ENVOYER',
                    'es' => 'ENVIAR',
                ),

                // ── Stringhe JS (esc_js) ──
                'js_no_figure' => array(
                    'it' => 'Seleziona il personale',
                    'en' => 'Select staff',
                    'fr' => 'S&eacute;lectionnez le personnel',
                    'es' => 'Selecciona el personal',
                ),
                'js_no_city' => array(
                    'it' => 'seleziona la citt&agrave;',
                    'en' => 'select the city',
                    'fr' => 's&eacute;lectionnez la ville',
                    'es' => 'selecciona la ciudad',
                ),
                'js_saving' => array(
                    'it' => 'Risparmi',
                    'en' => 'You save',
                    'fr' => 'Vous &eacute;conomisez',
                    'es' => 'Ahorras',
                ),
                'js_vs60' => array(
                    'it' => 'rispetto al pagamento a 60gg',
                    'en' => 'compared to 60-day payment',
                    'fr' => 'par rapport au paiement &agrave; 60j',
                    'es' => 'respecto al pago a 60 d&iacute;as',
                ),
                'js_save_advance' => array(
                    'it' => 'con pagamento anticipato',
                    'en' => 'with advance payment',
                    'fr' => 'avec paiement anticip&eacute;',
                    'es' => 'con pago anticipado',
                ),
                'js_city_first_select' => array(
                    'it' => 'Prima seleziona il paese',
                    'en' => 'First select the country',
                    'fr' => 'S&eacute;lectionnez d\'abord le pays',
                    'es' => 'Primero selecciona el pa&iacute;s',
                ),
                'js_city_other' => array(
                    'it' => 'Altra citt&agrave; (contattaci)',
                    'en' => 'Other city (contact us)',
                    'fr' => 'Autre ville (contactez-nous)',
                    'es' => 'Otra ciudad (cont&aacute;ctanos)',
                ),
                'js_city_select' => array(
                    'it' => 'Seleziona la citt&agrave;',
                    'en' => 'Select the city',
                    'fr' => 'S&eacute;lectionnez la ville',
                    'es' => 'Selecciona la ciudad',
                ),
                'js_privacy_alert' => array(
                    'it' => 'Per favore accetta la privacy policy per continuare.',
                    'en' => 'Please accept the privacy policy to continue.',
                    'fr' => 'Veuillez accepter la politique de confidentialit&eacute; pour continuer.',
                    'es' => 'Por favor, acepta la pol&iacute;tica de privacidad para continuar.',
                ),
            ),

            // ═══════════════════════════════════════
            // STUDENT PROGRAM PAGE
            // ═══════════════════════════════════════
            'student_program' => array(

                // Hero
                'hero_eyebrow' => array(
                    'it' => 'Student Program',
                    'en' => 'Student Program',
                    'fr' => 'Student Program',
                    'es' => 'Student Program',
                ),
                'hero_title' => array(
                    'it' => 'Porta il tuo progetto al livello successivo.',
                    'en' => 'Take your project to the next level.',
                    'fr' => 'Portez votre projet au niveau sup&eacute;rieur.',
                    'es' => 'Lleva tu proyecto al siguiente nivel.',
                ),
                'hero_subtitle' => array(
                    'it' => 'ToAgency supporta i creativi emergenti di tutta Europa. Fotografi, stylist, makeup artist, videomaker e designer: se hai un progetto e una visione, vogliamo sentire da te.',
                    'en' => 'ToAgency supports emerging creatives across Europe. Photographers, stylists, makeup artists, videomakers and designers: if you have a project and a vision, we want to hear from you.',
                    'fr' => 'ToAgency soutient les cr&eacute;atifs &eacute;mergents de toute l&rsquo;Europe. Photographes, stylistes, maquilleurs, vid&eacute;astes et designers&nbsp;: si vous avez un projet et une vision, nous voulons vous entendre.',
                    'es' => 'ToAgency apoya a los creativos emergentes de toda Europa. Fot&oacute;grafos, estilistas, maquilladores, videomakers y dise&ntilde;adores: si tienes un proyecto y una visi&oacute;n, queremos saber de ti.',
                ),

                // Program description blocks
                'program_eyebrow' => array(
                    'it' => 'Il programma',
                    'en' => 'The program',
                    'fr' => 'Le programme',
                    'es' => 'El programa',
                ),
                'program_heading' => array(
                    'it' => 'Cosa offriamo',
                    'en' => 'What we offer',
                    'fr' => 'Ce que nous offrons',
                    'es' => 'Lo que ofrecemos',
                ),
                'block1_title' => array(
                    'it' => 'Team di produzione',
                    'en' => 'Production team',
                    'fr' => '&Eacute;quipe de production',
                    'es' => 'Equipo de producci&oacute;n',
                ),
                'block1_text' => array(
                    'it' => 'Accesso a un team selezionato: fotografi, modelli, stylist, makeup artist e supporto creativo, adattato alle esigenze di ogni progetto.',
                    'en' => 'Access to a carefully selected production team: photographers, models, stylists, make-up artists, and creative support, adapted to the needs of each project.',
                    'fr' => 'Acc&egrave;s &agrave; une &eacute;quipe de production s&eacute;lectionn&eacute;e&nbsp;: photographes, mod&egrave;les, stylistes, maquilleurs et soutien cr&eacute;atif, adapt&eacute; aux besoins de chaque projet.',
                    'es' => 'Acceso a un equipo de producci&oacute;n cuidadosamente seleccionado: fot&oacute;grafos, modelos, estilistas, maquilladores y apoyo creativo, adaptado a las necesidades de cada proyecto.',
                ),
                'block2_title' => array(
                    'it' => 'Costi ridotti o gratuiti',
                    'en' => 'Reduced or free costs',
                    'fr' => 'Co&ucirc;ts r&eacute;duits ou gratuits',
                    'es' => 'Costes reducidos o gratuitos',
                ),
                'block2_text' => array(
                    'it' => 'Ogni progetto &egrave; valutato individualmente. In base al concept e al potenziale, ToAgency offre supporto a costi ridotti e, in alcuni casi, completamente gratuito.',
                    'en' => 'Each project is reviewed individually. Based on its concept and potential, ToAgency offers support at reduced costs and, in some cases, completely free.',
                    'fr' => 'Chaque projet est &eacute;valu&eacute; individuellement. En fonction du concept et du potentiel, ToAgency propose un soutien &agrave; co&ucirc;ts r&eacute;duits et, dans certains cas, totalement gratuit.',
                    'es' => 'Cada proyecto se eval&uacute;a individualmente. Seg&uacute;n el concepto y el potencial, ToAgency ofrece apoyo a costes reducidos y, en algunos casos, completamente gratuito.',
                ),
                'block3_title' => array(
                    'it' => 'Press &amp; PR',
                    'en' => 'Press &amp; PR',
                    'fr' => 'Presse &amp; Relations Publiques',
                    'es' => 'Prensa &amp; PR',
                ),
                'block3_text' => array(
                    'it' => 'Il nostro team Press &amp; PR seleziona i progetti pi&ugrave; interessanti e li supporta attraverso pubblicazioni su riviste, piattaforme digitali e canali media curati.',
                    'en' => 'Our Press &amp; PR team selects the most interesting projects and supports them through publications in magazines, digital platforms, and curated media channels.',
                    'fr' => 'Notre &eacute;quipe Presse &amp; RP s&eacute;lectionne les projets les plus int&eacute;ressants et les soutient via des publications dans des magazines, des plateformes num&eacute;riques et des m&eacute;dias s&eacute;lectionn&eacute;s.',
                    'es' => 'Nuestro equipo de Prensa &amp; PR selecciona los proyectos m&aacute;s interesantes y los apoya mediante publicaciones en revistas, plataformas digitales y canales de medios especializados.',
                ),
                'block4_title' => array(
                    'it' => 'Collaborazioni future',
                    'en' => 'Future collaborations',
                    'fr' => 'Collaborations futures',
                    'es' => 'Colaboraciones futuras',
                ),
                'block4_text' => array(
                    'it' => 'I talenti e i progetti pi&ugrave; promettenti potranno avere l&rsquo;opportunit&agrave; di collaborare con ToAgency in future produzioni.',
                    'en' => 'The most promising projects and talents may also have the opportunity to collaborate with ToAgency on future productions.',
                    'fr' => 'Les projets et talents les plus prometteurs pourront &eacute;galement avoir l&rsquo;opportunit&eacute; de collaborer avec ToAgency sur de futures productions.',
                    'es' => 'Los proyectos y talentos m&aacute;s prometedores tambi&eacute;n podr&aacute;n tener la oportunidad de colaborar con ToAgency en futuras producciones.',
                ),

                // Form headings
                'form_eyebrow' => array(
                    'it' => 'Candidatura',
                    'en' => 'Application',
                    'fr' => 'Candidature',
                    'es' => 'Candidatura',
                ),
                'form_heading' => array(
                    'it' => 'Invia il tuo progetto',
                    'en' => 'Submit your project',
                    'fr' => 'Soumettez votre projet',
                    'es' => 'Env&iacute;a tu proyecto',
                ),
                'form_subheading' => array(
                    'it' => 'Compila tutti i campi. Risponderemo entro 5 giorni lavorativi.',
                    'en' => 'Fill in all fields. We will reply within 5 business days.',
                    'fr' => 'Remplissez tous les champs. Nous r&eacute;pondrons dans les 5 jours ouvrables.',
                    'es' => 'Rellena todos los campos. Responderemos en 5 d&iacute;as h&aacute;biles.',
                ),

                // Section labels
                'section_who' => array(
                    'it' => 'Chi sei',
                    'en' => 'Who you are',
                    'fr' => 'Qui &ecirc;tes-vous',
                    'es' => 'Qui&eacute;n eres',
                ),
                'section_contacts' => array(
                    'it' => 'I tuoi contatti',
                    'en' => 'Your contact details',
                    'fr' => 'Vos coordonn&eacute;es',
                    'es' => 'Tus datos de contacto',
                ),
                'section_social' => array(
                    'it' => 'I tuoi social &amp; portfolio',
                    'en' => 'Your social &amp; portfolio',
                    'fr' => 'Vos r&eacute;seaux &amp; portfolio',
                    'es' => 'Tus redes &amp; portfolio',
                ),
                'section_school' => array(
                    'it' => 'La tua scuola',
                    'en' => 'Your school',
                    'fr' => 'Votre &eacute;cole',
                    'es' => 'Tu escuela',
                ),
                'section_project' => array(
                    'it' => 'Il tuo progetto',
                    'en' => 'Your project',
                    'fr' => 'Votre projet',
                    'es' => 'Tu proyecto',
                ),
                'section_shooting' => array(
                    'it' => 'Dettagli dello shooting',
                    'en' => 'Shooting details',
                    'fr' => 'D&eacute;tails du shooting',
                    'es' => 'Detalles del shooting',
                ),
                'section_moodboard' => array(
                    'it' => 'Moodboard (facoltativo)',
                    'en' => 'Moodboard (optional)',
                    'fr' => 'Moodboard (facultatif)',
                    'es' => 'Moodboard (opcional)',
                ),

                // Role labels
                'role_label' => array(
                    'it' => 'Di cosa ti occupi? (seleziona tutto ci&ograve; che ti descrive)',
                    'en' => 'What is your field? (select all that apply)',
                    'fr' => 'Quel est votre domaine&nbsp;? (s&eacute;lectionnez tout ce qui vous d&eacute;crit)',
                    'es' => '&iquest;Cu&aacute;l es tu campo? (selecciona todo lo que te describa)',
                ),
                'role_photographer'     => array('it' => 'Fotografo / a',        'en' => 'Photographer',       'fr' => 'Photographe',           'es' => 'Fot&oacute;grafo/a'),
                'role_videomaker'       => array('it' => 'Videomaker',            'en' => 'Videomaker',         'fr' => 'Vid&eacute;aste',        'es' => 'Videomaker'),
                'role_retoucher'        => array('it' => 'Retoucher / Digital',   'en' => 'Retoucher / Digital','fr' => 'Retoucheur / Digital',  'es' => 'Retocador/a'),
                'role_colorgrader'      => array('it' => 'Color Grader',          'en' => 'Color Grader',       'fr' => 'Color Grader',          'es' => 'Color Grader'),
                'role_stylist'          => array('it' => 'Fashion Stylist',       'en' => 'Fashion Stylist',    'fr' => 'Styliste Mode',         'es' => 'Estilista de Moda'),
                'role_costume'          => array('it' => 'Costume Designer',      'en' => 'Costume Designer',   'fr' => 'Cr&eacute;ateur Costumes','es' => 'Dise&ntilde;ador/a de Vestuario'),
                'role_propstylist'      => array('it' => 'Prop Stylist',          'en' => 'Prop Stylist',       'fr' => 'Styliste D&eacute;co',  'es' => 'Prop Stylist'),
                'role_fashiondesigner'  => array('it' => 'Fashion Designer',      'en' => 'Fashion Designer',   'fr' => 'Designer Mode',         'es' => 'Dise&ntilde;ador/a de Moda'),
                'role_accessdesigner'   => array('it' => 'Accessories Designer',  'en' => 'Accessories Designer','fr' => 'Designer Accessoires', 'es' => 'Dise&ntilde;ador/a de Accesorios'),
                'role_makeup'           => array('it' => 'Makeup Artist',         'en' => 'Makeup Artist',      'fr' => 'Maquilleur/se',         'es' => 'Maquillador/a'),
                'role_hair'             => array('it' => 'Hair Stylist',           'en' => 'Hair Stylist',       'fr' => 'Coiffeur/se',           'es' => 'Peluquero/a Estilista'),
                'role_nailartist'       => array('it' => 'Nail Artist',            'en' => 'Nail Artist',        'fr' => 'Nail Artist',           'es' => 'Nail Artist'),
                'role_creativeDir'      => array('it' => 'Creative Director',      'en' => 'Creative Director',  'fr' => 'Directeur Cr&eacute;atif','es' => 'Director/a Creativo/a'),
                'role_artdir'           => array('it' => 'Art Director',           'en' => 'Art Director',       'fr' => 'Directeur Artistique',  'es' => 'Director/a de Arte'),
                'role_setdesigner'      => array('it' => 'Set Designer',           'en' => 'Set Designer',       'fr' => 'D&eacute;corateur Set', 'es' => 'Dise&ntilde;ador/a de Set'),
                'role_graphicdesigner'  => array('it' => 'Graphic Designer',       'en' => 'Graphic Designer',   'fr' => 'Graphiste',             'es' => 'Dise&ntilde;ador/a Gr&aacute;fico/a'),
                'role_illustrator'      => array('it' => 'Illustratore / trice',   'en' => 'Illustrator',        'fr' => 'Illustrateur/trice',    'es' => 'Ilustrador/a'),
                'role_contentcreator'   => array('it' => 'Content Creator',        'en' => 'Content Creator',    'fr' => 'Cr&eacute;ateur Contenu','es' => 'Creador/a de Contenido'),
                'role_journalist'       => array('it' => 'Fashion Writer / Press', 'en' => 'Fashion Writer / Press','fr' => 'Journaliste Mode',   'es' => 'Periodista de Moda'),
                'role_producer'         => array('it' => 'Producer / Produttore',  'en' => 'Producer',           'fr' => 'Producteur/trice',      'es' => 'Productor/a'),
                'role_casting'          => array('it' => 'Casting Director',       'en' => 'Casting Director',   'fr' => 'Directeur Casting',     'es' => 'Director/a de Casting'),
                'role_other'            => array('it' => 'Altro',                  'en' => 'Other',              'fr' => 'Autre',                 'es' => 'Otro/a'),

                // Personal info labels & placeholders
                'label_name' => array('it' => 'Nome e Cognome', 'en' => 'Full Name', 'fr' => 'Nom et Pr&eacute;nom', 'es' => 'Nombre y Apellidos'),
                'ph_name'    => array('it' => 'Es. Marco Rossi', 'en' => 'E.g. Jane Smith', 'fr' => 'Ex. Marie Dupont', 'es' => 'Ej. María García'),
                'label_dob'  => array('it' => 'Data di nascita', 'en' => 'Date of birth', 'fr' => 'Date de naissance', 'es' => 'Fecha de nacimiento'),
                'label_email'=> array('it' => 'Email', 'en' => 'Email', 'fr' => 'Email', 'es' => 'Email'),
                'label_phone'=> array('it' => 'Telefono', 'en' => 'Phone', 'fr' => 'T&eacute;l&eacute;phone', 'es' => 'Tel&eacute;fono'),
                'ph_phone'   => array('it' => '+39 333 000 0000', 'en' => '+44 7000 000000', 'fr' => '+33 6 00 00 00 00', 'es' => '+34 600 000 000'),
                'label_country' => array('it' => 'Paese', 'en' => 'Country', 'fr' => 'Pays', 'es' => 'Pa&iacute;s'),
                'ph_country' => array('it' => 'Es. Italia', 'en' => 'E.g. United Kingdom', 'fr' => 'Ex. France', 'es' => 'Ej. España'),
                'label_role_other' => array('it' => 'Specifica il tuo ruolo', 'en' => 'Specify your role', 'fr' => 'Pr&eacute;cisez votre r&ocirc;le', 'es' => 'Especifica tu rol'),
                'ph_role_other'    => array('it' => 'Es. Scenografo, Textile Designer...', 'en' => 'E.g. Set designer, Textile designer...', 'fr' => 'Ex. Sc&eacute;nographe, Designer textile...', 'es' => 'Ej. Escen&oacute;grafo, Dise&ntilde;ador textil...'),

                // Social labels & placeholders
                'label_instagram' => array('it' => 'Instagram', 'en' => 'Instagram', 'fr' => 'Instagram', 'es' => 'Instagram'),
                'ph_instagram'    => array('it' => '@nomeutente', 'en' => '@username', 'fr' => '@nomdutilisateur', 'es' => '@usuario'),
                'label_tiktok'    => array('it' => 'TikTok', 'en' => 'TikTok', 'fr' => 'TikTok', 'es' => 'TikTok'),
                'ph_tiktok'       => array('it' => '@nomeutente', 'en' => '@username', 'fr' => '@nomdutilisateur', 'es' => '@usuario'),
                'label_youtube'   => array('it' => 'YouTube', 'en' => 'YouTube', 'fr' => 'YouTube', 'es' => 'YouTube'),
                'ph_youtube'      => array('it' => 'Link canale o @handle', 'en' => 'Channel link or @handle', 'fr' => 'Lien cha&icirc;ne ou @handle', 'es' => 'Enlace canal o @handle'),
                'label_portfolio' => array('it' => 'Portfolio / Sito web', 'en' => 'Portfolio / Website', 'fr' => 'Portfolio / Site web', 'es' => 'Portfolio / Sitio web'),
                'ph_portfolio'    => array('it' => 'https://', 'en' => 'https://', 'fr' => 'https://', 'es' => 'https://'),

                // School labels & placeholders
                'label_school_name' => array('it' => 'Nome della scuola / accademia', 'en' => 'School / Academy name', 'fr' => 'Nom de l&rsquo;&eacute;cole / acad&eacute;mie', 'es' => 'Nombre de la escuela / academia'),
                'ph_school_name'    => array('it' => 'Es. IED Torino, Polimoda, ESMOD...', 'en' => 'E.g. Central Saint Martins, IED...', 'fr' => 'Ex. ESMOD Paris, IFM...', 'es' => 'Ej. IED Madrid, ESDI...'),
                'label_school_city' => array('it' => 'Citt&agrave; della scuola', 'en' => 'School city', 'fr' => 'Ville de l&rsquo;&eacute;cole', 'es' => 'Ciudad de la escuela'),
                'ph_school_city'    => array('it' => 'Es. Torino', 'en' => 'E.g. London', 'fr' => 'Ex. Paris', 'es' => 'Ej. Madrid'),
                'label_course'      => array('it' => 'Corso di studi', 'en' => 'Course / Programme', 'fr' => 'Cours / Programme', 'es' => 'Curso / Programa'),
                'ph_course'         => array('it' => 'Es. Fashion Photography, Graphic Design...', 'en' => 'E.g. Fashion Photography, Graphic Design...', 'fr' => 'Ex. Photographie Mode, Design Graphique...', 'es' => 'Ej. Fotografía de Moda, Diseño Gráfico...'),

                // Project labels & placeholders
                'label_project_title'  => array('it' => 'Titolo del progetto', 'en' => 'Project title', 'fr' => 'Titre du projet', 'es' => 'T&iacute;tulo del proyecto'),
                'ph_project_title'     => array('it' => 'Es. &ldquo;Bloom&rdquo; — editorial SS26', 'en' => 'E.g. "Bloom" — editorial SS26', 'fr' => 'Ex. &laquo;&nbsp;Bloom&nbsp;&raquo; — editorial SS26', 'es' => 'Ej. "Bloom" — editorial SS26'),
                'label_project_desc'   => array('it' => 'Descrivi il progetto', 'en' => 'Describe your project', 'fr' => 'D&eacute;crivez votre projet', 'es' => 'Describe tu proyecto'),
                'ph_project_desc'      => array('it' => 'Concept, ispirazione, obiettivo visivo, mood...', 'en' => 'Concept, inspiration, visual goal, mood...', 'fr' => 'Concept, inspiration, objectif visuel, mood...', 'es' => 'Concepto, inspiraci&oacute;n, objetivo visual, mood...'),
                'label_team'           => array('it' => 'Chi &egrave; gi&agrave; coinvolto nel progetto?', 'en' => 'Who is already involved in the project?', 'fr' => 'Qui est d&eacute;j&agrave; impliqu&eacute; dans le projet&nbsp;?', 'es' => '&iquest;Qui&eacute;n ya est&aacute; involucrado en el proyecto?'),
                'ph_team'              => array('it' => 'Es. Ho gi&agrave; 2 modelli, cerco fotografo e stylist...', 'en' => 'E.g. I already have 2 models, looking for photographer and stylist...', 'fr' => 'Ex. J&rsquo;ai d&eacute;j&agrave; 2 mod&egrave;les, je cherche photographe et styliste...', 'es' => 'Ej. Ya tengo 2 modelos, busco fot&oacute;grafo y estilista...'),
                'label_what_need'      => array('it' => 'Chi cerchi nel team? (seleziona tutto ci&ograve; che ti serve)', 'en' => 'Who do you need in your team? (select everything you need)', 'fr' => 'Qui cherchez-vous dans l&rsquo;&eacute;quipe&nbsp;? (s&eacute;lectionnez tout ce dont vous avez besoin)', 'es' => '&iquest;A qui&eacute;n buscas en el equipo? (selecciona todo lo que necesitas)'),
                'label_what_need_notes' => array('it' => 'Note aggiuntive (facoltativo)', 'en' => 'Additional notes (optional)', 'fr' => 'Notes additionnelles (facultatif)', 'es' => 'Notas adicionales (opcional)'),
                'ph_what_need_notes'    => array('it' => 'Es. Cerco un team junior, preferenza per stile editoriale...', 'en' => 'E.g. Looking for a junior team, prefer editorial style...', 'fr' => 'Ex. Je cherche une &eacute;quipe junior, pr&eacute;f&eacute;rence style &eacute;ditorial...', 'es' => 'Ej. Busco un equipo junior, prefiero estilo editorial...'),
                'need_models'          => array('it' => 'Modelli / e', 'en' => 'Models', 'fr' => 'Mod&egrave;les', 'es' => 'Modelos'),
                'need_location'        => array('it' => 'Location', 'en' => 'Location', 'fr' => 'Lieu / Location', 'es' => 'Localizaci&oacute;n'),
                'need_equipment'       => array('it' => 'Attrezzatura tecnica', 'en' => 'Technical equipment', 'fr' => '&Eacute;quipement technique', 'es' => 'Equipo t&eacute;cnico'),
                'need_pr'              => array('it' => 'Supporto PR / Press', 'en' => 'PR / Press support', 'fr' => 'Soutien RP / Presse', 'es' => 'Apoyo PR / Prensa'),
                'need_production'      => array('it' => 'Coordinamento / Produzione', 'en' => 'Production / Coordination', 'fr' => 'Production / Coordination', 'es' => 'Producci&oacute;n / Coordinaci&oacute;n'),

                // Shooting labels & placeholders
                'label_shoot_location'  => array('it' => 'Luogo dello shooting', 'en' => 'Shooting location', 'fr' => 'Lieu du shooting', 'es' => 'Lugar del shooting'),
                'ph_shoot_location'     => array('it' => 'Citt&agrave; o indirizzo previsto', 'en' => 'City or planned address', 'fr' => 'Ville ou adresse pr&eacute;vue', 'es' => 'Ciudad o direcci&oacute;n prevista'),
                'label_shoot_dates'     => array('it' => 'Date previste', 'en' => 'Planned dates', 'fr' => 'Dates pr&eacute;vues', 'es' => 'Fechas previstas'),
                'ph_shoot_dates'        => array('it' => 'Es. 10–12 luglio 2026 (flessibile)', 'en' => 'E.g. 10–12 July 2026 (flexible)', 'fr' => 'Ex. 10–12 juillet 2026 (flexible)', 'es' => 'Ej. 10–12 julio 2026 (flexible)'),
                'label_shoot_hours'     => array('it' => 'Orari indicativi', 'en' => 'Estimated hours', 'fr' => 'Horaires indicatifs', 'es' => 'Horario aproximado'),
                'ph_shoot_hours'        => array('it' => 'Es. 09:00 – 18:00 per 2 giorni', 'en' => 'E.g. 09:00 – 18:00 for 2 days', 'fr' => 'Ex. 09h00 – 18h00 pendant 2 jours', 'es' => 'Ej. 09:00 – 18:00 durante 2 días'),

                // Budget
                'label_budget_yn'    => array('it' => 'Hai un budget disponibile?', 'en' => 'Do you have a budget?', 'fr' => 'Avez-vous un budget disponible&nbsp;?', 'es' => '&iquest;Tienes presupuesto disponible?'),
                'budget_yes'         => array('it' => 'S&igrave;', 'en' => 'Yes', 'fr' => 'Oui', 'es' => 'S&iacute;'),
                'budget_no'          => array('it' => 'No / Da valutare', 'en' => 'No / To be evaluated', 'fr' => 'Non / &Agrave; &eacute;valuer', 'es' => 'No / A evaluar'),
                'label_budget_range' => array('it' => 'Range di budget', 'en' => 'Budget range', 'fr' => 'Fourchette de budget', 'es' => 'Rango de presupuesto'),

                // Moodboard
                'moodboard_label' => array(
                    'it' => 'Carica fino a 3 immagini del tuo moodboard (JPG, PNG — max 10 MB ciascuna). Le immagini vengono ridimensionate automaticamente.',
                    'en' => 'Upload up to 3 moodboard images (JPG, PNG — max 10 MB each). Images are automatically resized.',
                    'fr' => 'T&eacute;l&eacute;chargez jusqu&rsquo;&agrave; 3 images de moodboard (JPG, PNG — max 10 Mo chacune). Les images sont redimensionn&eacute;es automatiquement.',
                    'es' => 'Sube hasta 3 im&aacute;genes de moodboard (JPG, PNG — m&aacute;x. 10 MB cada una). Las im&aacute;genes se redimensionan autom&aacute;ticamente.',
                ),
                'moodboard_btn' => array(
                    'it' => 'Scegli immagini',
                    'en' => 'Choose images',
                    'fr' => 'Choisir des images',
                    'es' => 'Elegir im&aacute;genes',
                ),
                'moodboard_processing' => array(
                    'it' => 'Ottimizzazione in corso...',
                    'en' => 'Optimising...',
                    'fr' => 'Optimisation en cours...',
                    'es' => 'Optimizando...',
                ),

                // Privacy & submit
                'privacy_text' => array(
                    'it' => 'Ho letto e accetto la',
                    'en' => 'I have read and accept the',
                    'fr' => 'J&rsquo;ai lu et j&rsquo;accepte la',
                    'es' => 'He le&iacute;do y acepto la',
                ),
                'privacy_link' => array('it' => 'Privacy Policy', 'en' => 'Privacy Policy', 'fr' => 'Politique de confidentialit&eacute;', 'es' => 'Pol&iacute;tica de privacidad'),
                'submit_btn'   => array('it' => 'Invia candidatura', 'en' => 'Submit application', 'fr' => 'Envoyer la candidature', 'es' => 'Enviar candidatura'),

                // JS strings
                'js_sending'         => array('it' => 'Invio in corso...', 'en' => 'Sending...', 'fr' => 'Envoi en cours...', 'es' => 'Enviando...'),
                'js_alert_privacy'   => array('it' => 'Accetta la privacy policy per continuare.', 'en' => 'Please accept the privacy policy to continue.', 'fr' => 'Veuillez accepter la politique de confidentialit&eacute;.', 'es' => 'Por favor acepta la política de privacidad.'),
                'js_alert_role'      => array('it' => 'Seleziona almeno un ruolo creativo.', 'en' => 'Please select at least one creative role.', 'fr' => 'Veuillez s&eacute;lectionner au moins un r&ocirc;le cr&eacute;atif.', 'es' => 'Por favor selecciona al menos un rol creativo.'),
                'js_error_msg'       => array('it' => 'Invio non riuscito. Vuoi contattarci via WhatsApp?', 'en' => 'Submission failed. Would you like to contact us via WhatsApp?', 'fr' => 'Envoi &eacute;chou&eacute;. Voulez-vous nous contacter via WhatsApp&nbsp;?', 'es' => 'Error en el env&iacute;o. &iquest;Deseas contactarnos por WhatsApp?'),
                'js_wa_message'      => array('it' => 'Ciao! Voglio candidarmi allo Student Program. Nome: ', 'en' => 'Hi! I would like to apply to the Student Program. Name: ', 'fr' => 'Bonjour! Je souhaite candidater au Student Program. Nom\u00a0: ', 'es' => '¡Hola! Quiero candidatarme al Student Program. Nombre: '),
                'js_file_too_big'    => array('it' => 'Il file supera 10 MB. Scegli un\'immagine pi&ugrave; leggera.', 'en' => 'File exceeds 10 MB. Please choose a lighter image.', 'fr' => 'Le fichier d&eacute;passe 10 Mo. Choisissez une image moins lourde.', 'es' => 'El archivo supera 10 MB. Elige una imagen m&aacute;s ligera.'),
                'js_max_files'       => array('it' => 'Puoi caricare massimo 3 immagini.', 'en' => 'You can upload a maximum of 3 images.', 'fr' => 'Vous pouvez t&eacute;l&eacute;charger 3 images maximum.', 'es' => 'Puedes subir un m&aacute;ximo de 3 im&aacute;genes.'),

                // Clausola non-commerciale
                'disclaimer_title' => array(
                    'it' => 'Importante &mdash; Uso non commerciale',
                    'en' => 'Important &mdash; Non-commercial use only',
                    'fr' => 'Important &mdash; Usage non commercial uniquement',
                    'es' => 'Importante &mdash; Solo uso no comercial',
                ),
                'disclaimer_text' => array(
                    'it' => 'Il Student Program &egrave; riservato a progetti <strong>senza scopo commerciale</strong>. Le immagini e i contenuti prodotti non possono essere utilizzati per finalit&agrave; commerciali, pubblicitarie o promozionali di alcun tipo.<br><br>Se il tuo progetto prevede uno scopo commerciale &mdash; anche parziale &mdash; non rientra nel Student Program e deve essere gestito come un <strong>lavoro professionale retribuito</strong>. In quel caso ti invitiamo a compilare il nostro <a href="/form-b2b/" style="color:var(--accent);text-decoration:underline">form B2B</a>.',
                    'en' => 'The Student Program is reserved for projects <strong>with no commercial purpose</strong>. Images and content produced may not be used for commercial, advertising or promotional purposes of any kind.<br><br>If your project has a commercial purpose &mdash; even partially &mdash; it does not qualify for the Student Program and must be handled as <strong>professional paid work</strong>. In that case, please use our <a href="/form-b2b/" style="color:var(--accent);text-decoration:underline">B2B form</a>.',
                    'fr' => 'Le Student Program est r&eacute;serv&eacute; aux projets <strong>sans vocation commerciale</strong>. Les images et contenus produits ne peuvent pas &ecirc;tre utilis&eacute;s &agrave; des fins commerciales, publicitaires ou promotionnelles.<br><br>Si votre projet a une finalit&eacute; commerciale &mdash; m&ecirc;me partielle &mdash; il ne rel&egrave;ve pas du Student Program et doit &ecirc;tre trait&eacute; comme un <strong>travail professionnel r&eacute;mun&eacute;r&eacute;</strong>. Dans ce cas, veuillez utiliser notre <a href="/form-b2b/" style="color:var(--accent);text-decoration:underline">formulaire B2B</a>.',
                    'es' => 'El Student Program est&aacute; reservado a proyectos <strong>sin finalidad comercial</strong>. Las im&aacute;genes y contenidos producidos no pueden utilizarse con fines comerciales, publicitarios o promocionales de ning&uacute;n tipo.<br><br>Si tu proyecto tiene una finalidad comercial &mdash; aunque sea parcial &mdash; no se encuadra en el Student Program y debe gestionarse como <strong>trabajo profesional remunerado</strong>. En ese caso, te invitamos a utilizar nuestro <a href="/form-b2b/" style="color:var(--accent);text-decoration:underline">formulario B2B</a>.',
                ),

                // Menu item
                'menu_label' => array('it' => 'Student', 'en' => 'Student', 'fr' => 'Student', 'es' => 'Student'),

                // ───────────────────────────────────────
                // CONFIRM PAGE (post-submit)
                // ───────────────────────────────────────
                'confirm_eyebrow' => array(
                    'it' => 'Candidatura ricevuta',
                    'en' => 'Application received',
                    'fr' => 'Candidature re&ccedil;ue',
                    'es' => 'Solicitud recibida',
                ),
                'confirm_title' => array(
                    'it' => 'Grazie, abbiamo ricevuto il tuo progetto.',
                    'en' => 'Thank you, we have received your project.',
                    'fr' => 'Merci, nous avons bien re&ccedil;u votre projet.',
                    'es' => 'Gracias, hemos recibido tu proyecto.',
                ),
                'confirm_body' => array(
                    'it' => 'Il nostro team valuter&agrave; la tua candidatura nei prossimi 5&ndash;7 giorni lavorativi. Ti contatteremo via email con il riscontro.',
                    'en' => 'Our team will review your application within the next 5&ndash;7 business days. We will contact you via email with feedback.',
                    'fr' => 'Notre &eacute;quipe examinera votre candidature dans les 5 &agrave; 7 prochains jours ouvr&eacute;s. Nous vous contacterons par email pour vous faire un retour.',
                    'es' => 'Nuestro equipo evaluar&aacute; tu solicitud en los pr&oacute;ximos 5&ndash;7 d&iacute;as h&aacute;biles. Te contactaremos por email con la respuesta.',
                ),
                'confirm_steps_title' => array(
                    'it' => 'Cosa succede ora',
                    'en' => 'What happens next',
                    'fr' => 'Ce qui se passe maintenant',
                    'es' => 'Qu&eacute; sucede ahora',
                ),
                'confirm_step_1' => array(
                    'it' => 'Revisione della candidatura',
                    'en' => 'Application review',
                    'fr' => 'Examen de la candidature',
                    'es' => 'Revisi&oacute;n de la solicitud',
                ),
                'confirm_step_2' => array(
                    'it' => 'Feedback e approvazione',
                    'en' => 'Feedback and approval',
                    'fr' => 'Retour et approbation',
                    'es' => 'Comentarios y aprobaci&oacute;n',
                ),
                'confirm_step_3' => array(
                    'it' => 'Pre-produzione e team',
                    'en' => 'Pre-production and team',
                    'fr' => 'Pr&eacute;-production et &eacute;quipe',
                    'es' => 'Preproducci&oacute;n y equipo',
                ),
                'confirm_step_4' => array(
                    'it' => 'Shooting',
                    'en' => 'Shooting',
                    'fr' => 'Shooting',
                    'es' => 'Sesi&oacute;n',
                ),
                'confirm_step_5' => array(
                    'it' => 'Post-produzione e pubblicazione',
                    'en' => 'Post-production and publishing',
                    'fr' => 'Post-production et publication',
                    'es' => 'Postproducci&oacute;n y publicaci&oacute;n',
                ),
                'confirm_notice' => array(
                    'it' => 'Il programma &egrave; selettivo. Valutiamo concept, originalit&agrave; e fattibilit&agrave;. I progetti sono per uso educativo e di portfolio, non per fini commerciali.',
                    'en' => 'The program is selective. We evaluate concept, originality and feasibility. Projects are for educational and portfolio use, not for commercial purposes.',
                    'fr' => 'Le programme est s&eacute;lectif. Nous &eacute;valuons le concept, l&rsquo;originalit&eacute; et la faisabilit&eacute;. Les projets sont &agrave; usage &eacute;ducatif et de portfolio, non commercial.',
                    'es' => 'El programa es selectivo. Evaluamos concepto, originalidad y viabilidad. Los proyectos son para uso educativo y de portfolio, no comerciales.',
                ),
                'confirm_db_title' => array(
                    'it' => 'Entra nel nostro database creativi',
                    'en' => 'Join our creative database',
                    'fr' => 'Rejoignez notre base de cr&eacute;atifs',
                    'es' => '&Uacute;nete a nuestra base de creativos',
                ),
                'confirm_db_text' => array(
                    'it' => 'Crea la tua scheda nel nostro database: cos&igrave; potremo contattarti anche per altre collaborazioni future.',
                    'en' => 'Create your profile in our database: this way we can also contact you for other future collaborations.',
                    'fr' => 'Cr&eacute;ez votre profil dans notre base de donn&eacute;es&nbsp;: ainsi nous pourrons aussi vous contacter pour d&rsquo;autres collaborations futures.',
                    'es' => 'Crea tu perfil en nuestra base de datos: as&iacute; podremos contactarte tambi&eacute;n para otras colaboraciones futuras.',
                ),
                'confirm_db_btn' => array(
                    'it' => 'Completa il tuo profilo',
                    'en' => 'Complete your profile',
                    'fr' => 'Compl&eacute;ter votre profil',
                    'es' => 'Completa tu perfil',
                ),
                'confirm_follow' => array(
                    'it' => 'Per info scrivici in direct',
                    'en' => 'For info, message us on direct',
                    'fr' => 'Pour toute info, &eacute;crivez-nous en direct',
                    'es' => 'Para info, escr&iacute;benos por direct',
                ),

            ),

        );
    }
}
