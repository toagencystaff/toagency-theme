<?php
/**
 * Template Name: Kappa Future Festival
 * Description: Guest list registration page for Kappa Future Festival 28/05/2026
 * Created: 2026-05-22 marco
 */

// ── LOCANDINA (imposta il path immagine, lascia vuoto per non mostrare) ──
$KAPPA_POSTER = ''; // es: get_template_directory_uri() . '/images/kappa-poster-2026.jpg'

// ── DATA EVENTO ──
$KAPPA_DATE     = '28 MAGGIO 2026';
$KAPPA_LOCATION = 'Kappa Store — Torino';

get_header();
?>

<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex, nofollow">
<title>Kappa Future Festival — Lista d'Invitati TOAgency</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

  :root {
    --kappa-bg:      #0a0a0a;
    --kappa-surface: #111111;
    --kappa-border:  #222222;
    --kappa-accent:  #C5FF00;
    --kappa-text:    #f0f0f0;
    --kappa-muted:   #666666;
    --kappa-error:   #ff4444;
    --kappa-success: #C5FF00;
    --radius:        4px;
  }

  body {
    background: var(--kappa-bg);
    color: var(--kappa-text);
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    min-height: 100vh;
    -webkit-font-smoothing: antialiased;
  }

  /* ── HERO ── */
  .kf-hero {
    position: relative;
    min-height: 100svh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px 40px;
    text-align: center;
    overflow: hidden;
  }

  .kf-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(197,255,0,0.12) 0%, transparent 70%);
    pointer-events: none;
  }

  .kf-poster {
    width: 100%;
    max-width: 360px;
    border-radius: 8px;
    margin-bottom: 32px;
    box-shadow: 0 0 60px rgba(197,255,0,0.15);
  }

  .kf-badge {
    display: inline-block;
    background: var(--kappa-accent);
    color: #000;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 5px 14px;
    border-radius: 2px;
    margin-bottom: 20px;
  }

  .kf-title {
    font-size: clamp(36px, 10vw, 72px);
    font-weight: 900;
    letter-spacing: -1px;
    line-height: 1;
    text-transform: uppercase;
    margin-bottom: 8px;
  }

  .kf-title span {
    color: var(--kappa-accent);
  }

  .kf-subtitle {
    font-size: 13px;
    font-weight: 600;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--kappa-muted);
    margin-bottom: 40px;
  }

  .kf-scroll-hint {
    font-size: 11px;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--kappa-muted);
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    margin-top: 20px;
  }

  .kf-scroll-hint::after {
    content: '↓';
    animation: bounce 1.4s infinite;
  }

  @keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(5px); }
  }

  /* ── FORM SECTION ── */
  .kf-section {
    padding: 60px 20px 80px;
    max-width: 480px;
    margin: 0 auto;
  }

  .kf-section-title {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: var(--kappa-accent);
    margin-bottom: 8px;
  }

  .kf-section-heading {
    font-size: 28px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 8px;
  }

  .kf-section-desc {
    font-size: 14px;
    color: var(--kappa-muted);
    line-height: 1.6;
    margin-bottom: 36px;
  }

  /* ── FORM ── */
  .kf-form {
    display: flex;
    flex-direction: column;
    gap: 16px;
  }

  .kf-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .kf-field label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--kappa-muted);
  }

  .kf-field input {
    background: var(--kappa-surface);
    border: 1px solid var(--kappa-border);
    border-radius: var(--radius);
    color: var(--kappa-text);
    font-family: inherit;
    font-size: 16px;
    padding: 14px 16px;
    transition: border-color .2s, box-shadow .2s;
    outline: none;
    width: 100%;
    -webkit-appearance: none;
  }

  .kf-field input::placeholder { color: #333; }

  .kf-field input:focus {
    border-color: var(--kappa-accent);
    box-shadow: 0 0 0 3px rgba(197,255,0,0.1);
  }

  .kf-field input.error {
    border-color: var(--kappa-error);
  }

  .kf-field .kf-hint {
    font-size: 11px;
    color: var(--kappa-muted);
  }

  .kf-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }

  .kf-submit {
    background: var(--kappa-accent);
    border: none;
    border-radius: var(--radius);
    color: #000;
    cursor: pointer;
    font-family: inherit;
    font-size: 14px;
    font-weight: 800;
    letter-spacing: 2px;
    padding: 18px;
    text-transform: uppercase;
    transition: opacity .2s, transform .1s;
    margin-top: 8px;
    width: 100%;
  }

  .kf-submit:hover { opacity: 0.9; }
  .kf-submit:active { transform: scale(0.99); }
  .kf-submit:disabled { opacity: 0.4; cursor: not-allowed; }

  .kf-error-banner {
    background: rgba(255,68,68,0.1);
    border: 1px solid rgba(255,68,68,0.3);
    border-radius: var(--radius);
    color: var(--kappa-error);
    font-size: 13px;
    padding: 12px 16px;
    display: none;
  }

  .kf-error-banner.visible { display: block; }

  .kf-privacy {
    font-size: 11px;
    color: #444;
    line-height: 1.6;
    text-align: center;
  }

  .kf-privacy a { color: #555; }

  /* ── THANK YOU ── */
  .kf-thankyou {
    display: none;
    min-height: 100svh;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
    text-align: center;
  }

  .kf-thankyou.visible {
    display: flex;
  }

  .kf-check {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: rgba(197,255,0,0.1);
    border: 2px solid var(--kappa-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    margin-bottom: 28px;
  }

  .kf-thankyou h2 {
    font-size: 32px;
    font-weight: 900;
    text-transform: uppercase;
    margin-bottom: 12px;
  }

  .kf-thankyou h2 span { color: var(--kappa-accent); }

  .kf-thankyou p {
    font-size: 15px;
    color: var(--kappa-muted);
    max-width: 340px;
    line-height: 1.6;
    margin-bottom: 40px;
  }

  .kf-cta-block {
    background: var(--kappa-surface);
    border: 1px solid var(--kappa-border);
    border-radius: 8px;
    padding: 28px 24px;
    max-width: 380px;
    width: 100%;
    text-align: left;
    margin-bottom: 24px;
  }

  .kf-cta-block .kf-cta-label {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--kappa-accent);
    margin-bottom: 10px;
  }

  .kf-cta-block h3 {
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 8px;
    line-height: 1.3;
  }

  .kf-cta-block p {
    font-size: 13px;
    color: var(--kappa-muted);
    margin-bottom: 20px;
    line-height: 1.5;
  }

  .kf-cta-btn {
    display: block;
    background: var(--kappa-accent);
    border-radius: var(--radius);
    color: #000;
    font-weight: 800;
    font-size: 12px;
    letter-spacing: 2px;
    padding: 14px 20px;
    text-align: center;
    text-decoration: none;
    text-transform: uppercase;
    transition: opacity .2s;
  }

  .kf-cta-btn:hover { opacity: 0.85; }

  .kf-skip {
    font-size: 12px;
    color: #444;
    text-decoration: none;
    border-bottom: 1px solid #333;
    padding-bottom: 1px;
  }

  /* ── DIVIDER ── */
  .kf-divider {
    border: none;
    border-top: 1px solid var(--kappa-border);
    margin: 0;
  }

  /* ── FOOTER ── */
  .kf-footer {
    padding: 24px 20px;
    text-align: center;
    font-size: 11px;
    color: #333;
  }

  .kf-footer a { color: #444; }

  /* ── LOADER ── */
  .kf-spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(0,0,0,0.3);
    border-top-color: #000;
    border-radius: 50%;
    display: inline-block;
    animation: spin .7s linear infinite;
    vertical-align: middle;
    margin-right: 6px;
  }

  @keyframes spin { to { transform: rotate(360deg); } }
</style>
</head>
<body>

<?php if ($KAPPA_POSTER): ?>
<!-- Poster header gestito nel body qui sotto -->
<?php endif; ?>

<div id="kf-app">

  <!-- ── HERO ── -->
  <section class="kf-hero">
    <?php if ($KAPPA_POSTER): ?>
      <img src="<?= esc_attr($KAPPA_POSTER) ?>" alt="Kappa Future Festival 2026" class="kf-poster">
    <?php endif; ?>

    <div class="kf-badge">Powered by TOAgency</div>
    <h1 class="kf-title">Kappa<br><span>Future</span><br>Festival</h1>
    <p class="kf-subtitle"><?= esc_html($KAPPA_DATE) ?> &nbsp;·&nbsp; <?= esc_html($KAPPA_LOCATION) ?></p>

    <span class="kf-scroll-hint" onclick="document.getElementById('kf-form-section').scrollIntoView({behavior:'smooth'})">
      Iscriviti alla lista
    </span>
  </section>

  <hr class="kf-divider">

  <!-- ── FORM ── -->
  <section class="kf-section" id="kf-form-section">
    <p class="kf-section-title">Lista Invitati</p>
    <h2 class="kf-section-heading">Registrati per l'evento</h2>
    <p class="kf-section-desc">
      Compila il form per richiedere il tuo posto. La tua iscrizione sarà confermata dallo staff di TOAgency.
    </p>

    <div class="kf-error-banner" id="kf-error-banner"></div>

    <form class="kf-form" id="kf-form" novalidate>
      <div class="kf-row">
        <div class="kf-field">
          <label for="kf-nome">Nome *</label>
          <input type="text" id="kf-nome" name="nome" placeholder="Marco" autocomplete="given-name" inputmode="text" required>
        </div>
        <div class="kf-field">
          <label for="kf-cognome">Cognome *</label>
          <input type="text" id="kf-cognome" name="cognome" placeholder="Rossi" autocomplete="family-name" inputmode="text" required>
        </div>
      </div>

      <div class="kf-field">
        <label for="kf-tel">Telefono *</label>
        <input type="tel" id="kf-tel" name="telefono" placeholder="+39 333 1234567" autocomplete="tel" inputmode="tel" required>
      </div>

      <div class="kf-field">
        <label for="kf-email">Email *</label>
        <input type="email" id="kf-email" name="email" placeholder="nome@email.com" autocomplete="email" inputmode="email" required>
      </div>

      <div class="kf-field">
        <label for="kf-ig">Instagram</label>
        <input type="text" id="kf-ig" name="instagram" placeholder="@tuoprofilo" autocomplete="off" inputmode="text">
        <span class="kf-hint">Facoltativo — senza @</span>
      </div>

      <button type="submit" class="kf-submit" id="kf-submit">
        Richiedi il tuo posto
      </button>

      <p class="kf-privacy">
        Inviando il modulo accetti il trattamento dei dati personali per la gestione dell'evento.
        <a href="https://toagency.it/privacy" target="_blank">Privacy policy</a>
      </p>
    </form>
  </section>

  <hr class="kf-divider">

  <!-- ── THANK YOU ── -->
  <section class="kf-thankyou" id="kf-thankyou">
    <div class="kf-check">✓</div>
    <h2>Sei in<br><span>lista!</span></h2>
    <p>Il tuo posto è riservato. Lo staff di TOAgency ti contatterà per la conferma entro 24h.</p>

    <div class="kf-cta-block">
      <p class="kf-cta-label">Passo successivo</p>
      <h3>Crea il tuo profilo su TOAgency</h3>
      <p>Aumenta le tue possibilità — con un profilo completo il cliente può vederti prima dell'evento.</p>
      <a href="#" id="kf-cta-link" class="kf-cta-btn">
        Crea profilo gratuito →
      </a>
    </div>

    <a href="https://toagency.it/talent-database/" class="kf-skip">Salta, non mi interessa</a>
  </section>

  <!-- ── FOOTER ── -->
  <footer class="kf-footer">
    <a href="https://toagency.it">TOAgency.it</a> &nbsp;·&nbsp; <?= date('Y') ?>
  </footer>

</div><!-- /#kf-app -->

<script>
(function() {
  'use strict';

  const ENDPOINT = 'https://toagency.it/actions/kappa-register.php';
  const PROFILE_BASE = 'https://toagency.it/registrati-talent/';

  const form      = document.getElementById('kf-form');
  const submitBtn = document.getElementById('kf-submit');
  const errBanner = document.getElementById('kf-error-banner');
  const thankyou  = document.getElementById('kf-thankyou');
  const formSec   = document.getElementById('kf-form-section');
  const ctaLink   = document.getElementById('kf-cta-link');

  function showError(msg) {
    errBanner.textContent = msg;
    errBanner.classList.add('visible');
    errBanner.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
  }

  function clearError() {
    errBanner.textContent = '';
    errBanner.classList.remove('visible');
  }

  function setLoading(on) {
    submitBtn.disabled = on;
    submitBtn.innerHTML = on
      ? '<span class="kf-spinner"></span> Invio...'
      : 'Richiedi il tuo posto';
  }

  function showThankyou(email) {
    formSec.style.display = 'none';
    // Costruisce CTA con email pre-compilata
    const url = new URL(PROFILE_BASE);
    url.searchParams.set('email', email);
    url.searchParams.set('ref', 'kappa');
    ctaLink.href = url.toString();
    thankyou.classList.add('visible');
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    clearError();

    const nome      = document.getElementById('kf-nome').value.trim();
    const cognome   = document.getElementById('kf-cognome').value.trim();
    const telefono  = document.getElementById('kf-tel').value.trim();
    const email     = document.getElementById('kf-email').value.trim();
    const instagram = document.getElementById('kf-ig').value.trim();

    // Basic client-side check
    if (!nome || !cognome) { showError('Inserisci nome e cognome.'); return; }
    if (!telefono)          { showError('Inserisci il numero di telefono.'); return; }
    if (!email.includes('@')){ showError('Inserisci un\'email valida.'); return; }

    setLoading(true);

    try {
      const res = await fetch(ENDPOINT, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nome, cognome, telefono, email, instagram }),
      });

      const data = await res.json();

      if (data.ok || data.already) {
        showThankyou(email);
      } else {
        const msg = data.errors ? data.errors.join(' — ') : (data.error || 'Errore. Riprova.');
        showError(msg);
      }
    } catch (err) {
      showError('Connessione non riuscita. Controlla la rete e riprova.');
    } finally {
      setLoading(false);
    }
  });

  // Auto-scroll al form se URL ha #form
  if (window.location.hash === '#form') {
    document.getElementById('kf-form-section').scrollIntoView({ behavior: 'smooth' });
  }
})();
</script>

<?php get_footer(); ?>
