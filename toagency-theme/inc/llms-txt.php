<?php
/**
 * llms.txt virtuale — TOAgency
 * NUOVO 2026-09-16 marco — chat TEMA SEO-AI
 * AGGIORNATO 2026-11-07 — chat TEMA GEO AI: TOA Events, TOA Creators, iscrizioni talent/crew, contatti separati clienti/talent
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

> European talent and event staffing agency. Hostesses, stewards, promoters, creators, models, actors and creative crew for brands, events, trade shows and productions. Offices in Turin and Paris, working across Italy, France, Spain and the rest of Europe.

One contact and one invoice for the client: TOAgency selects the people, manages their contracts and pay, and coordinates them on site. Website in English, Italian, French and Spanish.

## For companies

- [TOA Events](https://toagency.it/en/events/): event staff (hostesses, stewards, promoters, bar, photo and video, technical crew, logistics). Also in [IT](https://toagency.it/events/), [FR](https://toagency.it/fr/events/), [ES](https://toagency.it/es/events/)
- [Event staff and prices](https://toagency.it/en/events/staff-and-prices/): example profiles and starting prices by country and city. Also in [IT](https://toagency.it/events/staff-e-prezzi/), [FR](https://toagency.it/fr/events/personnel-et-tarifs/), [ES](https://toagency.it/es/events/personal-y-precios/)
- [All event services](https://toagency.it/en/events/all-services/)
- [Event staff](https://toagency.it/en/events/event-staff/), [Trade show staff](https://toagency.it/en/events/trade-show-staff/), [Bar and entertainment](https://toagency.it/en/events/event-bar-entertainment/), [Photo and video](https://toagency.it/en/events/event-photo-video/), [Technical crew](https://toagency.it/en/events/event-technical-crew/), [Logistics](https://toagency.it/en/events/event-logistics/)
- Event staff by city: [Paris](https://toagency.it/en/events/paris/), [French Riviera](https://toagency.it/en/events/french-riviera/), [Monte Carlo](https://toagency.it/en/events/monte-carlo/), [Lyon](https://toagency.it/en/events/lyon/), [Marseille](https://toagency.it/en/events/marseille/), [Bordeaux](https://toagency.it/en/events/bordeaux/), [Madrid](https://toagency.it/en/events/madrid/), [Barcelona](https://toagency.it/en/events/barcelona/), [Valencia](https://toagency.it/en/events/valencia/), [Seville](https://toagency.it/en/events/seville/), [Bilbao](https://toagency.it/en/events/bilbao/), [Malaga](https://toagency.it/en/events/malaga/), [Milan](https://toagency.it/en/events/milan/), [Rome](https://toagency.it/en/events/rome/), [Turin](https://toagency.it/en/events/turin/), [Bologna](https://toagency.it/en/events/bologna/), [Verona](https://toagency.it/en/events/verona/), [Rimini](https://toagency.it/en/events/rimini/), [Genoa](https://toagency.it/en/events/genoa/), [Naples](https://toagency.it/en/events/naples/), [Florence](https://toagency.it/en/events/florence/)
- [TOA Creators](https://toagency.it/en/creators/): UGC and content creators for brands, ads and event coverage, with starting prices. Also in [IT](https://toagency.it/creators/), [FR](https://toagency.it/fr/createurs/), [ES](https://toagency.it/es/creadores/)
- [Talent database](https://toagency.it/talent-database/): searchable profiles of models, actors, extras, hostesses, creators and kids
- [Creative Network](https://toagency.it/crew-database/): photographers, videographers, make-up artists, stylists and other creative crew
- [Agency profile](https://toagency.it/en/agency-profile/): who we are, services, roles, downloadable PDF. Also in [IT](https://toagency.it/presentazione-agenzia/), [FR](https://toagency.it/fr/presentation-agence/), [ES](https://toagency.it/es/presentacion-agencia/)
- [Request a quote](https://toagency.it/form-b2b/)
- Contacts for companies only: business@toagency.it, +39 351 789 9225. France: france@toagency.it. Spain: espana@toagency.it. United Kingdom: uk@toagency.it

## For people who want to work with us

- [Work as a hostess, steward or promoter](https://toagency.it/en/events/work-with-us/). Also in [IT](https://toagency.it/events/lavora-con-noi/), [FR](https://toagency.it/fr/events/travailler-avec-nous/), [ES](https://toagency.it/es/events/trabaja-con-nosotros/)
- [Become a creator](https://toagency.it/en/creators/become-a-creator/). Also in [IT](https://toagency.it/creators/diventa-creator/), [FR](https://toagency.it/fr/createurs/devenir-createur/), [ES](https://toagency.it/es/creadores/hazte-creador/)
- [Sign up as talent](https://toagency.it/en/register-talent/): models, actors, extras, hostesses, creators and other roles. Also in [IT](https://toagency.it/registrati-talent/)
- [Sign up as crew](https://toagency.it/registrati-crew/): photographers, videographers, make-up artists, stylists, bartenders and other crew
- [Open castings](https://toagency.it/casting/): current calls for models, actors, extras, hostesses and creators
- [All ways to work with us](https://toagency.it/collabora/)
- Contact for talent and crew: WhatsApp only, +39 351 846 8516

TXT;
	exit;
}, 1 );
