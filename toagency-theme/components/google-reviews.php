<?php
/**
 * Component: Google Reviews badge + 3 card multilingua
 * FIX 2026-05-30 marco — google-reviews (testi tradotti EN/FR/ES)
 * Dati: 4,7 ★ · 346 recensioni — aggiornare ogni ~6 mesi
 */
$reviews = [
    [
        'name'  => 'Pietro S.',
        'stars' => 5,
        'text'  => [
            'it' => 'Agenzia affidabile, seria e disponibile. Servizio rapido e preciso, mi sono trovato davvero bene. Spero di poter lavorare ancora con loro in futuro!',
            'en' => 'Reliable, professional and approachable agency. Fast and accurate service — I had a great experience. Looking forward to working with them again!',
            'fr' => 'Agence fiable, sérieuse et disponible. Service rapide et précis, je me suis vraiment bien trouvé. J\'espère retravailler avec eux à l\'avenir !',
            'es' => 'Agencia fiable, seria y accesible. Servicio rápido y preciso, me encontré muy bien. ¡Espero poder trabajar con ellos de nuevo en el futuro!',
        ],
    ],
    [
        'name'  => 'Giusj B.',
        'stars' => 5,
        'text'  => [
            'it' => 'Persone splendide, estremamente disponibili e molto professionali. Attenti alle esigenze di tutti e in grado di rispondere con grande spirito di problem solving. Un\'esperienza molto positiva.',
            'en' => 'Wonderful people, extremely helpful and very professional. Attentive to everyone\'s needs and great at problem-solving. A very positive experience overall.',
            'fr' => 'Des personnes formidables, extrêmement disponibles et très professionnelles. Attentives aux besoins de tous, avec un excellent esprit de résolution de problèmes. Une expérience très positive.',
            'es' => 'Personas maravillosas, extremadamente disponibles y muy profesionales. Atentas a las necesidades de todos y con gran capacidad de resolución de problemas. Una experiencia muy positiva.',
        ],
    ],
    [
        'name'  => 'Anasse L.',
        'stars' => 5,
        'text'  => [
            'it' => 'All\'inizio ero molto titubante, ma mi sono dovuto ricredere: massima serietà, rispetto e un ambiente davvero bello. Ho amato tantissimo l\'esperienza.',
            'en' => 'At first I was quite hesitant, but I had to change my mind completely: the utmost professionalism, respect and a truly welcoming environment. I absolutely loved the experience.',
            'fr' => 'Au début j\'étais très hésitant, mais j\'ai dû changer d\'avis : le plus grand sérieux, du respect et une atmosphère vraiment agréable. J\'ai adoré l\'expérience.',
            'es' => 'Al principio estaba muy dudoso, pero tuve que cambiar de opinión: máxima seriedad, respeto y un ambiente realmente agradable. Me encantó la experiencia.',
        ],
    ],
];
$maps_url = 'https://www.google.com/maps/place/TOAGENCY/@45.0647138,7.6851436,17z';
?>
<section class="toa-google-reviews">
  <div class="toa-reviews-inner">
    <div class="toa-reviews-header">
      <svg class="toa-reviews-glogo" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
      </svg>
      <div class="toa-reviews-score">
        <span class="toa-reviews-number">4,7</span>
        <div class="toa-reviews-stars" aria-label="4.7 stelle su 5">
          <?php for ($i = 0; $i < 5; $i++): ?><svg viewBox="0 0 20 20" class="toa-star" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
        </div>
        <span class="toa-reviews-count"><?php echo _ht(['it'=>'346 recensioni su Google','en'=>'346 reviews on Google','fr'=>'346 avis sur Google','es'=>'346 reseñas en Google']); ?></span>
      </div>
    </div>
    <div class="toa-reviews-grid">
      <?php foreach ($reviews as $r): ?>
      <div class="toa-review-card">
        <div class="toa-review-stars-row" aria-label="<?php echo $r['stars']; ?> stelle">
          <?php for ($i = 0; $i < $r['stars']; $i++): ?><svg viewBox="0 0 20 20" class="toa-star" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg><?php endfor; ?>
        </div>
        <p class="toa-review-text">"<?php echo esc_html( _ht( $r['text'] ) ); ?>"</p>
        <div class="toa-review-author"><?php echo esc_html($r['name']); ?> &mdash; <span>via Google</span></div>
      </div>
      <?php endforeach; ?>
    </div>
    <a href="<?php echo esc_url($maps_url); ?>" target="_blank" rel="noopener" class="toa-reviews-cta">
      <?php echo _ht(['it'=>'Leggi tutte le recensioni su Google →','en'=>'Read all Google reviews →','fr'=>'Voir tous les avis Google →','es'=>'Ver todas las reseñas de Google →']); ?>
    </a>
  </div>
</section>
