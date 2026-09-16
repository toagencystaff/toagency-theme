<?php
/**
 * llms.txt virtuale — TOAgency
 * NUOVO 2026-09-16 marco — chat TEMA SEO-AI
 *
 * Serve /llms.txt (testo semplice) senza bisogno di un file fisico nella
 * document root del server, che deploy.sh non tocca (rsync copia solo
 * toagency-theme/). Intercetta la richiesta su 'init', prima che WP
 * risolva la query, e stampa il contenuto in text/plain.
 *
 * Pratica emergente (2026) per orientare i crawler AI (Claude, Perplexity,
 * in parte ChatGPT) su chi è TOAgency e quali pagine sono rilevanti.
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function () {
	if ( trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' ) !== 'llms.txt' ) return;

	header( 'Content-Type: text/plain; charset=utf-8' );
	echo <<<TXT
# TOAgency

> Casting, modeling and event staffing agency based in Turin, Italy, operating across Italy and Europe (France, Spain, UK, Germany).

TOAgency selects and manages models, actors, hostesses, stewards and event staff for brands, agencies and production companies. Talent pool of verified professionals across Italy and Europe. Content available in Italian, English, French and Spanish.

## Services
- [Homepage](https://toagency.it/): overview of TOAgency and its services
- [Models & Talent Casting](https://toagency.it/models/): model and talent selection for campaigns, shows, e-commerce and video productions
- [Hostess & Steward for Events](https://toagency.it/hostess-steward/): event staffing for trade shows, conferences and product launches across Italy and Europe
- [Actors & Commercial Casting](https://toagency.it/actors/): casting for commercials, institutional videos and film/TV productions
- [Visual Production](https://toagency.it/visuals/): photo and video content production for campaigns and e-commerce
- [B2B Services](https://toagency.it/b2bservices/): casting, talent management and contracting for brands and agencies
- [Blog](https://toagency.it/blog/): articles and updates from TOAgency

## Contact
- Address: Via Cavour, 21 — 10123 Torino, Italy
- Phone: +39 351 789 9225
- Email: info@toagency.it
- Request a quote: https://toagency.it/form-b2b/
TXT;
	exit;
}, 1 );
