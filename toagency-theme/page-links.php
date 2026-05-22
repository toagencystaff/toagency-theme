<?php
/**
 * Template Name: Links Page
 * Pagina Linktree TOAgency — standalone, no header/footer tema
 * Aggiornato: 2026-05-21
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOAgency — Links</title>
    <?php wp_head(); ?>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        #wpadminbar { display: none !important; }
        html { margin-top: 0 !important; }

        html, body {
            background: #000 !important;
            color: #fff !important;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }

        .lk-wrap {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 20px 36px;
            background: #000;
        }

        .lk-inner {
            width: 100%;
            max-width: 480px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* ── LOGO ── */
        .lk-logo img {
            height: 48px;
            width: auto;
            display: block;
        }

        /* ── TAGLINE ── */
        .lk-tagline {
            margin-top: 14px;
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0.1em;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
        }

        /* ── SPACER ── */
        .lk-spacer { height: 36px; }

        /* ── BUTTONS ── */
        .lk-buttons {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .lk-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 60px;
            border-radius: 12px;
            font-size: 17px;
            font-weight: 700;
            text-decoration: none;
            background: #fff;
            color: #000;
            transition: background 0.2s, color 0.2s;
            letter-spacing: 0.01em;
        }

        .lk-btn:hover {
            background: #C5FF00;
            color: #000;
        }

        /* ── SOCIAL ROW ── */
        .lk-social {
            display: flex;
            gap: 24px;
            align-items: center;
            justify-content: center;
            margin-top: 40px;
        }

        .lk-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            transition: color 0.2s;
            text-decoration: none;
        }

        .lk-social a:hover { color: #C5FF00; }

        .lk-social svg {
            width: 28px;
            height: 28px;
            fill: currentColor;
        }

        /* ── FOOTER ── */
        .lk-footer {
            margin-top: 32px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
        }
    </style>
</head>
<body>
<div class="lk-wrap">
    <div class="lk-inner">

        <!-- LOGO -->
        <a class="lk-logo" href="<?php echo esc_url(home_url('/')); ?>">
            <img src="https://toagency.it/wp-content/uploads/2025/09/LogoToanew.png"
                 alt="TOAgency" width="auto" height="48">
        </a>

        <!-- TAGLINE -->
        <p class="lk-tagline">Casting &amp; Talent Agency B2B</p>

        <div class="lk-spacer"></div>

        <!-- CTA BUTTONS -->
        <nav class="lk-buttons" aria-label="Link principali">
            <a class="lk-btn" href="<?php echo esc_url(home_url('/casting/')); ?>">
                Lavora con noi
            </a>
            <a class="lk-btn" href="<?php echo esc_url(home_url('/talent-database/')); ?>">
                I nostri talent
            </a>
            <a class="lk-btn" href="<?php echo esc_url(home_url('/blog/')); ?>">
                Il nostro Blog
            </a>
        </nav>

        <!-- SOCIAL -->
        <div class="lk-social">
            <!-- Instagram -->
            <a href="#" aria-label="TOAgency su Instagram" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
            </a>
        </div>

        <!-- FOOTER -->
        <footer class="lk-footer">
            &copy; <?php echo esc_html(date('Y')); ?> TOAgency
        </footer>

    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
