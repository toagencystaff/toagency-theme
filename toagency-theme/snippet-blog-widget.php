<?php
/**
 * snippet-blog-widget.php — Widget blog floating TOAgency
 *
 * INTEGRAZIONE in page-home.php:
 * Cerca la riga con toa_component('footer') o get_footer() e aggiungi
 * SUBITO PRIMA:
 *   <?php include( get_template_directory() . '/snippet-blog-widget.php' ); ?>
 *
 * Aggiornato: 2026-05-21
 */

/* Badge NEW — controlla articoli ultimi 7 giorni */
$toa_bw_has_new = false;
$toa_bw_check   = new WP_Query( array(
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'date_query'     => array(
        array( 'after' => '7 days ago', 'inclusive' => true ),
    ),
) );
if ( $toa_bw_check->have_posts() ) {
    $toa_bw_has_new = true;
}
wp_reset_postdata();

/* Ultimi 3 articoli per il pannello */
$toa_bw_query = new WP_Query( array(
    'posts_per_page' => 3,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
) );
?>

<style>
    /* ── BLOG WIDGET FLOATING ── */
    @keyframes toaBwPulse {
        0%, 100% { transform: scale(1); box-shadow: 0 4px 20px rgba(197,255,0,0.35); }
        50%       { transform: scale(1.08); box-shadow: 0 6px 28px rgba(197,255,0,0.5); }
    }

    @keyframes toaBwSlideUp {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    #toa-bw {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }

    /* ── BUBBLE ── */
    #toa-bw-bubble {
        width: 60px;
        height: 60px;
        background: #C5FF00;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        position: relative;
        animation: toaBwPulse 3s ease-in-out infinite;
        border: none;
        padding: 0;
        outline: none;
    }

    #toa-bw-bubble:focus-visible {
        outline: 3px solid #C5FF00;
        outline-offset: 3px;
    }

    #toa-bw-bubble svg {
        width: 26px;
        height: 26px;
        fill: #000;
        pointer-events: none;
    }

    /* Badge */
    .toa-bw-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        background: #ff3b3b;
        color: #fff;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: 0.04em;
        padding: 2px 6px;
        border-radius: 100px;
        line-height: 1.4;
        pointer-events: none;
    }

    /* ── PANEL ── */
    #toa-bw-panel {
        display: none;
        position: absolute;
        bottom: 72px;
        right: 0;
        width: 300px;
        background: #111;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.7), 0 0 0 1px rgba(255,255,255,0.07);
        overflow: hidden;
    }

    #toa-bw-panel.toa-bw-open {
        animation: toaBwSlideUp 0.3s ease both;
    }

    /* Header panel */
    .toa-bw-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 18px;
        border-bottom: 1px solid #1e1e1e;
    }

    .toa-bw-header__title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        letter-spacing: 0.01em;
    }

    .toa-bw-close {
        background: none;
        border: none;
        color: #555;
        font-size: 22px;
        line-height: 1;
        cursor: pointer;
        padding: 2px 6px;
        border-radius: 4px;
        font-family: inherit;
        transition: color 0.2s;
    }
    .toa-bw-close:hover { color: #fff; }

    /* Post cards */
    .toa-bw-posts { padding: 6px 0; }

    .toa-bw-post {
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 11px 16px;
        text-decoration: none;
        transition: background 0.18s;
    }
    .toa-bw-post:hover { background: #1a1a1a; }

    .toa-bw-post__thumb {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        flex-shrink: 0;
        display: block;
        background: #222;
    }

    .toa-bw-post__placeholder {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        background: #222;
        flex-shrink: 0;
    }

    .toa-bw-post__body { flex: 1; min-width: 0; }

    .toa-bw-post__cat {
        display: inline-block;
        background: #C5FF00;
        color: #000;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.07em;
        padding: 2px 7px;
        border-radius: 3px;
        margin-bottom: 5px;
    }

    .toa-bw-post__title {
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        line-height: 1.35;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 4px;
    }

    .toa-bw-post__date {
        font-size: 11px;
        color: #555;
    }

    /* Footer panel */
    .toa-bw-footer {
        padding: 12px 16px;
        border-top: 1px solid #1e1e1e;
    }

    .toa-bw-cta {
        display: block;
        width: 100%;
        padding: 11px;
        background: #C5FF00;
        color: #000;
        text-align: center;
        font-size: 13px;
        font-weight: 700;
        border-radius: 8px;
        text-decoration: none;
        transition: opacity 0.2s;
        letter-spacing: 0.02em;
    }
    .toa-bw-cta:hover { opacity: 0.85; }
</style>

<div id="toa-bw" role="complementary" aria-label="Blog widget">

    <!-- BUBBLE TOGGLE -->
    <button id="toa-bw-bubble"
            aria-expanded="false"
            aria-controls="toa-bw-panel"
            aria-label="Apri / chiudi articoli del blog">
        <!-- Matita SVG -->
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
        </svg>
        <?php if ( $toa_bw_has_new ) : ?>
            <span class="toa-bw-badge" aria-label="Nuovi articoli">NEW</span>
        <?php endif; ?>
    </button>

    <!-- PANEL -->
    <div id="toa-bw-panel" role="dialog" aria-modal="false" aria-label="Ultimi articoli del blog">

        <div class="toa-bw-header">
            <span class="toa-bw-header__title">Dal nostro Blog</span>
            <button id="toa-bw-close" class="toa-bw-close" aria-label="Chiudi pannello blog">&times;</button>
        </div>

        <div class="toa-bw-posts">
            <?php if ( $toa_bw_query->have_posts() ) : ?>
                <?php while ( $toa_bw_query->have_posts() ) : $toa_bw_query->the_post(); ?>
                    <?php
                    $bw_cats = get_the_category();
                    $bw_cat  = ! empty( $bw_cats ) ? $bw_cats[0] : null;
                    ?>
                    <a href="<?php the_permalink(); ?>" class="toa-bw-post">

                        <?php if ( has_post_thumbnail() ) : ?>
                            <?php the_post_thumbnail( 'thumbnail', array(
                                'class'   => 'toa-bw-post__thumb',
                                'loading' => 'lazy',
                                'alt'     => esc_attr( get_the_title() ),
                            ) ); ?>
                        <?php else : ?>
                            <div class="toa-bw-post__placeholder"></div>
                        <?php endif; ?>

                        <div class="toa-bw-post__body">
                            <?php if ( $bw_cat ) : ?>
                                <span class="toa-bw-post__cat"><?php echo esc_html( $bw_cat->name ); ?></span>
                            <?php endif; ?>
                            <div class="toa-bw-post__title"><?php the_title(); ?></div>
                            <div class="toa-bw-post__date"><?php echo esc_html( get_the_date('d M Y') ); ?></div>
                        </div>

                    </a>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <p style="padding:20px 16px;color:#555;font-size:14px;margin:0;">
                    Nessun articolo ancora.
                </p>
            <?php endif; ?>
        </div>

        <div class="toa-bw-footer">
            <a href="<?php echo esc_url( home_url('/blog/') ); ?>" class="toa-bw-cta">
                Vai al Blog &rarr;
            </a>
        </div>

    </div><!-- #toa-bw-panel -->

</div><!-- #toa-bw -->

<script>
(function () {
    'use strict';

    var bubble   = document.getElementById('toa-bw-bubble');
    var panel    = document.getElementById('toa-bw-panel');
    var closeBtn = document.getElementById('toa-bw-close');

    if ( !bubble || !panel ) return;

    var SESSION_KEY = 'toaBlogWidgetOpen';
    var isOpen      = sessionStorage.getItem(SESSION_KEY) === '1';

    function openPanel() {
        panel.style.display = 'block';
        /* un frame di delay per triggerare l'animazione */
        requestAnimationFrame( function () {
            panel.classList.add('toa-bw-open');
        } );
        bubble.setAttribute('aria-expanded', 'true');
        isOpen = true;
        sessionStorage.setItem(SESSION_KEY, '1');
    }

    function closePanel() {
        panel.classList.remove('toa-bw-open');
        /* aspetta la fine dell'animazione prima di nascondere */
        setTimeout( function () {
            panel.style.display = 'none';
        }, 280 );
        bubble.setAttribute('aria-expanded', 'false');
        isOpen = false;
        sessionStorage.setItem(SESSION_KEY, '0');
    }

    bubble.addEventListener('click', function () {
        isOpen ? closePanel() : openPanel();
    } );

    bubble.addEventListener('keydown', function (e) {
        if ( e.key === 'Enter' || e.key === ' ' ) {
            e.preventDefault();
            isOpen ? closePanel() : openPanel();
        }
    } );

    closeBtn.addEventListener('click', closePanel );

    /* Ripristina stato dalla sessione precedente */
    if ( isOpen ) {
        panel.style.display = 'block';
        panel.classList.add('toa-bw-open');
        bubble.setAttribute('aria-expanded', 'true');
    }

}());
</script>
