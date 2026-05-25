<?php
/**
 * Template Name: Thank You Page
 * Pagina di ringraziamento post-form TOAgency — multilingua (IT/EN/ES/FR)
 * Aggiornato: 2026-05-20
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Richiesta Ricevuta | TOAgency</title>
    <?php wp_head(); ?>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        #wpadminbar { display: none !important; }
        html { margin-top: 0 !important; }

        /* Forza sfondo nero su tutto — override tema WP */
        html, body {
            background: #000 !important;
            color: #fff !important;
        }

        .tnx-wrap {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background: #000;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        /* ── HEADER ── */
        .tnx-header {
            background: #000;
            border-bottom: 1px solid #1a1a1a;
            padding: 18px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
        }
        .tnx-header img { height: 38px; width: auto; }

        /* ── LANG SWITCHER ── */
        .tnx-lang {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .tnx-lang button {
            background: none;
            border: none;
            color: #555;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            cursor: pointer;
            padding: 5px 7px;
            border-radius: 4px;
            transition: color 0.2s, background 0.2s;
            font-family: inherit;
        }
        .tnx-lang button:hover { color: #fff; }
        .tnx-lang button.active {
            color: #000;
            background: #C5FF00;
        }
        /* placeholder a sinistra per centrare il logo */
        .tnx-header-spacer { width: 120px; }

        /* ── MAIN ── */
        .tnx-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            background: #000;
        }
        .tnx-container {
            max-width: 660px;
            width: 100%;
            text-align: center;
        }

        /* ── CHECKMARK ── */
        .tnx-check {
            width: 80px;
            height: 80px;
            background: #C5FF00;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
        }
        .tnx-check svg { width: 38px; height: 38px; }

        /* ── TITOLO ── */
        .tnx-title {
            font-size: 44px;
            font-weight: 900;
            letter-spacing: -1px;
            text-transform: uppercase;
            margin-bottom: 14px;
            line-height: 1.1;
            color: #fff !important;
        }
        .tnx-subtitle {
            font-size: 16px;
            color: #888;
            line-height: 1.7;
            margin-bottom: 36px;
        }

        /* ── BOX REF ── */
        .tnx-ref-box {
            display: inline-block;
            background: #111;
            border: 2px solid #C5FF00;
            border-radius: 10px;
            padding: 20px 32px;
            margin-bottom: 44px;
            min-width: 260px;
        }
        .tnx-ref-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #C5FF00;
            margin-bottom: 8px;
        }
        .tnx-ref-code {
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 3px;
            color: #fff;
            font-family: 'Courier New', monospace;
        }
        .tnx-ref-copy {
            margin-top: 10px;
            font-size: 12px;
            color: #555;
            cursor: pointer;
            user-select: none;
            transition: color 0.2s;
        }
        .tnx-ref-copy:hover { color: #C5FF00; }

        /* ── PROGRESS ── */
        .tnx-progress {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            margin-bottom: 52px;
        }
        .tnx-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        .tnx-step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 15px;
            left: 50%;
            width: 100%;
            height: 2px;
            background: #222;
            z-index: 0;
        }
        .tnx-step.active:not(:last-child)::after { background: #C5FF00; }
        .tnx-step-num {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 800;
            background: #222;
            color: #555;
            position: relative;
            z-index: 1;
            margin-bottom: 10px;
        }
        .tnx-step.active .tnx-step-num { background: #C5FF00; color: #000; }
        .tnx-step-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #444;
            line-height: 1.4;
        }
        .tnx-step.active .tnx-step-label { color: #fff !important; }

        /* ── CARD DATABASE ── */
        .tnx-db-card {
            background: #C5FF00;
            color: #000;
            border-radius: 14px;
            padding: 36px;
            margin-bottom: 36px;
            text-align: left;
        }
        .tnx-db-tag {
            display: inline-block;
            background: #000;
            color: #C5FF00;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 4px;
            margin-bottom: 18px;
        }
        .tnx-db-title {
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -0.5px;
            color: #000;
        }
        .tnx-db-text {
            font-size: 14px;
            color: #333;
            line-height: 1.7;
            margin-bottom: 24px;
        }
        .tnx-db-text strong { color: #000; }
        .tnx-db-btn {
            display: inline-block;
            background: #000;
            color: #C5FF00;
            font-weight: 800;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 13px 28px;
            border-radius: 8px;
            text-decoration: none;
            transition: opacity 0.2s;
        }
        .tnx-db-btn:hover { opacity: 0.85; }
        .tnx-db-note { margin-top: 14px; font-size: 12px; color: #555; }

        /* ── CONTATTI ── */
        .tnx-contacts-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #555;
            margin-bottom: 18px;
        }
        .tnx-contact-btns {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 56px;
        }
        .tnx-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 22px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: opacity 0.2s;
        }
        .tnx-btn:hover { opacity: 0.82; }
        .tnx-btn-wa   { background: #25D366; color: #fff; }
        .tnx-btn-tel  { background: #C5FF00; color: #000; }
        .tnx-btn-mail { background: transparent; color: #fff; border: 2px solid #333; }
        .tnx-btn-mail:hover { border-color: #C5FF00; color: #C5FF00; opacity: 1; }

        /* ── FOOTER ── */
        .tnx-footer {
            background: #000;
            border-top: 1px solid #1a1a1a;
            color: #444;
            text-align: center;
            padding: 26px 20px;
            font-size: 12px;
            line-height: 1.8;
        }
        .tnx-footer a { color: #555; text-decoration: none; margin: 0 10px; transition: color 0.2s; }
        .tnx-footer a:hover { color: #C5FF00; }

        /* ── RESPONSIVE ── */
        @media (max-width: 580px) {
            .tnx-title { font-size: 32px; }
            .tnx-db-card { padding: 24px; }
            .tnx-contact-btns { flex-direction: column; }
            .tnx-btn { justify-content: center; }
            .tnx-main { padding: 40px 16px; }
        }
    </style>
</head>
<body>
<div class="tnx-wrap">

    <!-- HEADER -->
    <header class="tnx-header">
        <div class="tnx-header-spacer"></div>
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="https://toagency.it/wp-content/uploads/2025/09/LogoToanew.png" alt="TOAgency">
        </a>
        <nav class="tnx-lang" aria-label="Seleziona lingua">
            <button onclick="setLang('it')" data-lang="it" class="active">IT</button>
            <button onclick="setLang('fr')" data-lang="fr">FR</button>
            <button onclick="setLang('es')" data-lang="es">ES</button>
            <button onclick="setLang('en')" data-lang="en">EN</button>
        </nav>
    </header>

    <!-- MAIN -->
    <main class="tnx-main">
        <div class="tnx-container">

            <!-- CHECKMARK -->
            <div class="tnx-check">
                <svg viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>

            <!-- TITOLO -->
            <h1 class="tnx-title" data-i18n="title"></h1>
            <p class="tnx-subtitle" data-i18n="subtitle"></p>

            <!-- BOX REF -->
            <div class="tnx-ref-box">
                <div class="tnx-ref-label" data-i18n="refLabel"></div>
                <div class="tnx-ref-code" id="tnx-ref">REF-40267363</div>
                <div class="tnx-ref-copy" data-i18n="refCopy" onclick="copyRef(this)"></div>
            </div>

            <!-- PROGRESS -->
            <div class="tnx-progress">
                <div class="tnx-step active">
                    <div class="tnx-step-num">1</div>
                    <div class="tnx-step-label" data-i18n="step1"></div>
                </div>
                <div class="tnx-step">
                    <div class="tnx-step-num">2</div>
                    <div class="tnx-step-label" data-i18n="step2"></div>
                </div>
                <div class="tnx-step">
                    <div class="tnx-step-num">3</div>
                    <div class="tnx-step-label" data-i18n="step3"></div>
                </div>
            </div>

            <!-- CARD DATABASE -->
            <div class="tnx-db-card">
                <div class="tnx-db-tag" data-i18n="dbTag"></div>
                <div class="tnx-db-title" data-i18n="dbTitle"></div>
                <p class="tnx-db-text" data-i18n="dbText"></p>
                <a href="https://toagency.it/talent-database/" target="_blank" class="tnx-db-btn" data-i18n="dbBtn"></a>
                <p class="tnx-db-note" data-i18n="dbNote"></p>
            </div>

            <!-- CONTATTI -->
            <p class="tnx-contacts-label" data-i18n="contactsLabel"></p>
            <div class="tnx-contact-btns">
                <a href="https://wa.me/393517899225" target="_blank" class="tnx-btn tnx-btn-wa">💬 WhatsApp</a>
                <a href="tel:+393517899225" class="tnx-btn tnx-btn-tel">📞 +39 351 789 9225</a>
                <a href="mailto:business@toagency.it" class="tnx-btn tnx-btn-mail">✉️ Email</a>
            </div>

        </div>
    </main>

    <!-- FOOTER -->
    <footer class="tnx-footer">
        <div>TOAGENCY by Toa Group — Via Cavour, Torino (Italy) — P.I. 11800210012</div>
        <div>
            <a href="<?php echo esc_url(home_url('/privacy-policy-3/')); ?>">Privacy Policy</a>
            <a href="<?php echo esc_url(home_url('/cookie-policy-ue/')); ?>">Cookie Policy</a>
            <a href="<?php echo esc_url(home_url('/terms-and-conditions/')); ?>">Termini e Condizioni</a>
        </div>
    </footer>

</div><!-- fine .tnx-wrap -->

<script>
// ── TRADUZIONI ──────────────────────────────────────────────
var TNX_TRANSLATIONS = {
    it: {
        title:         "Richiesta Ricevuta!",
        subtitle:      "La tua richiesta è stata inviata con successo.<br>Ti ricontatteremo entro 30 minuti con proposte personalizzate.",
        refLabel:      "Codice Riferimento",
        refCopy:       "📋 Clicca per copiare",
        refCopied:     "✅ Copiato!",
        step1:         "Richiesta<br>Ricevuta",
        step2:         "In<br>Lavorazione",
        step3:         "Proposte<br>Pronte",
        dbTag:         "Vuoi velocizzare?",
        dbTitle:       "Esplora il Nostro Database",
        dbText:        "Accedi a oltre 20.000 profili professionali.<br>Filtra per provincia in Italia, Francia, Spagna e Regno Unito.<br>Trova i volti perfetti e segnalaci <strong>nome e codice</strong>.",
        dbBtn:         "Vai al Database →",
        dbNote:        "Si apre in una nuova finestra — puoi tornare qui per i contatti",
        contactsLabel: "Contatti Diretti"
    },
    en: {
        title:         "Request Received!",
        subtitle:      "Your request has been successfully submitted.<br>We'll get back to you within 30 minutes with personalised proposals.",
        refLabel:      "Reference Code",
        refCopy:       "📋 Click to copy",
        refCopied:     "✅ Copied!",
        step1:         "Request<br>Received",
        step2:         "In<br>Progress",
        step3:         "Proposals<br>Ready",
        dbTag:         "Want to speed things up?",
        dbTitle:       "Explore Our Database",
        dbText:        "Access over 20,000 professional profiles.<br>Filter by location across Italy, France, Spain and the UK.<br>Find the perfect faces and send us their <strong>name and code</strong>.",
        dbBtn:         "Go to Database →",
        dbNote:        "Opens in a new window — you can return here for contacts",
        contactsLabel: "Direct Contacts"
    },
    es: {
        title:         "¡Solicitud Recibida!",
        subtitle:      "Tu solicitud ha sido enviada con éxito.<br>Nos pondremos en contacto contigo en 30 minutos con propuestas personalizadas.",
        refLabel:      "Código de Referencia",
        refCopy:       "📋 Haz clic para copiar",
        refCopied:     "✅ ¡Copiado!",
        step1:         "Solicitud<br>Recibida",
        step2:         "En<br>Proceso",
        step3:         "Propuestas<br>Listas",
        dbTag:         "¿Quieres acelerar?",
        dbTitle:       "Explora Nuestra Base de Datos",
        dbText:        "Accede a más de 20.000 perfiles profesionales.<br>Filtra por provincia en Italia, Francia, España y Reino Unido.<br>Encuentra los rostros perfectos y envíanos su <strong>nombre y código</strong>.",
        dbBtn:         "Ir a la Base de Datos →",
        dbNote:        "Se abre en una nueva ventana — puedes volver aquí para los contactos",
        contactsLabel: "Contactos Directos"
    },
    fr: {
        title:         "Demande Reçue !",
        subtitle:      "Votre demande a été envoyée avec succès.<br>Nous vous recontacterons dans les 30 minutes avec des propositions personnalisées.",
        refLabel:      "Code de Référence",
        refCopy:       "📋 Cliquez pour copier",
        refCopied:     "✅ Copié !",
        step1:         "Demande<br>Reçue",
        step2:         "En<br>Traitement",
        step3:         "Propositions<br>Prêtes",
        dbTag:         "Vous voulez accélérer ?",
        dbTitle:       "Explorez Notre Base de Données",
        dbText:        "Accédez à plus de 20 000 profils professionnels.<br>Filtrez par province en Italie, France, Espagne et Royaume-Uni.<br>Trouvez les visages parfaits et communiquez-nous leur <strong>nom et code</strong>.",
        dbBtn:         "Accéder à la Base de Données →",
        dbNote:        "S'ouvre dans une nouvelle fenêtre — vous pouvez revenir ici pour les contacts",
        contactsLabel: "Contacts Directs"
    }
};

// ── APPLICA LINGUA ───────────────────────────────────────────
function setLang(lang) {
    if (!TNX_TRANSLATIONS[lang]) lang = 'it';
    var t = TNX_TRANSLATIONS[lang];

    // Aggiorna testi
    document.querySelectorAll('[data-i18n]').forEach(function(el) {
        var key = el.getAttribute('data-i18n');
        if (t[key] !== undefined) el.innerHTML = t[key];
    });

    // Salva testo "copiato" per uso in copyRef
    document.querySelector('.tnx-ref-copy').setAttribute('data-copied', t.refCopied);

    // Aggiorna bottoni lingua attivi
    document.querySelectorAll('.tnx-lang button').forEach(function(btn) {
        btn.classList.toggle('active', btn.getAttribute('data-lang') === lang);
    });
}

// ── COPIA REF ────────────────────────────────────────────────
function copyRef(btn) {
    var code   = document.getElementById('tnx-ref').textContent;
    var copied = btn.getAttribute('data-copied') || '✅ Copiato!';
    var orig   = btn.innerHTML;
    navigator.clipboard.writeText(code).then(function() {
        btn.textContent = copied;
        setTimeout(function() { btn.innerHTML = orig; }, 2000);
    }).catch(function() {
        var el = document.createElement('textarea');
        el.value = code; document.body.appendChild(el);
        el.select(); document.execCommand('copy');
        document.body.removeChild(el);
        btn.textContent = copied;
        setTimeout(function() { btn.innerHTML = orig; }, 2000);
    });
}

// ── INIT — default Italiano ──────────────────────────────────
document.addEventListener('DOMContentLoaded', function() { setLang('it'); });
</script>

<?php wp_footer(); ?>
</body>
</html>
