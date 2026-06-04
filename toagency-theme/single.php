<?php
/**
 * single.php — Articolo singolo blog TOAgency — design editoriale premium dark
 * Aggiornato: 2026-05-21
 */

toa_component('header');
?>

<style>
    html, body { background: #000 !important; }

    /* ── READING PROGRESS BAR ── */
    #toa-sl-progress {
        position: fixed;
        top: 0;
        left: 0;
        width: 0%;
        height: 3px;
        background: #C5FF00;
        z-index: 9998;
        transition: width 0.08s linear;
        pointer-events: none;
    }

    /* ── PAGE WRAPPER ── */
    .toa-sl-page {
        background: #000;
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }

    /* ── HERO (con immagine) ── */
    .toa-sl-hero {
        position: relative;
        width: 100%;
        overflow: hidden;
        max-height: 560px;
        background: #000;
    }

    .toa-sl-hero__img {
        width: 100%;
        height: 560px;
        object-fit: cover;
        display: block;
    }

    .toa-sl-hero__overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 50%;
        background: linear-gradient(to top, rgba(0,0,0,0.92) 0%, transparent 100%);
        pointer-events: none;
    }

    /* Meta sovrapposta all'immagine */
    .toa-sl-hero__meta {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 36px 60px 40px;
    }

    /* ── HERO (senza immagine) ── */
    .toa-sl-hero--noimg {
        max-height: none;
        padding: 72px 60px 0;
        overflow: visible;
    }

    .toa-sl-hero--noimg .toa-sl-hero__meta {
        position: relative;
        padding: 0;
        bottom: auto;
        left: auto;
        right: auto;
    }

    /* ── CATEGORIA CHIP ── */
    .toa-sl-cat {
        display: inline-block;
        background: #C5FF00;
        color: #000;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 4px 12px;
        border-radius: 4px;
        text-decoration: none;
        margin-bottom: 14px;
    }

    /* ── H1 ── */
    .toa-sl-title {
        font-size: 48px;
        font-weight: 900;
        color: #fff;
        line-height: 1.1;
        letter-spacing: -1px;
        margin: 0 0 16px;
    }

    /* ── META (autore + data) ── */
    .toa-sl-meta {
        font-size: 14px;
        color: #888;
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .toa-sl-meta__sep { color: #333; }

    /* ── CONTENT AREA ── */
    .toa-sl-content-area {
        max-width: 760px;
        margin: 0 auto;
        padding: 52px 20px 0;
    }

    /* ── CONTENUTO ARTICOLO ── */
    .toa-sl-content {
        font-size: 18px;
        line-height: 1.85;
        color: #e0e0e0;
    }

    .toa-sl-content p { margin-bottom: 1.5em; }

    .toa-sl-content h2 {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        border-left: 3px solid #C5FF00;
        padding-left: 16px;
        margin: 1.8em 0 0.7em;
        line-height: 1.25;
    }

    .toa-sl-content h3 {
        font-size: 22px;
        font-weight: 700;
        color: #fff;
        margin: 1.5em 0 0.6em;
    }

    .toa-sl-content img {
        max-width: 100%;
        border-radius: 12px;
        height: auto;
        display: block;
        margin: 1.5em 0;
    }

    .toa-sl-content blockquote {
        border-left: 3px solid #C5FF00;
        background: #111;
        padding: 20px 24px;
        border-radius: 0 10px 10px 0;
        margin: 1.5em 0;
        font-style: italic;
        color: #bbb;
    }
    .toa-sl-content blockquote p { margin-bottom: 0; }

    .toa-sl-content a {
        color: #C5FF00;
        text-decoration: underline;
        text-underline-offset: 3px;
    }

    .toa-sl-content iframe,
    .toa-sl-content .wp-block-embed__wrapper,
    .toa-sl-content .wp-block-embed__wrapper iframe {
        max-width: 100%;
        width: 100%;
        border-radius: 12px;
        aspect-ratio: 16 / 9;
        height: auto;
    }

    /* ── SHARE ── */
    .toa-sl-share {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 32px 0;
        border-top: 1px solid #1a1a1a;
        margin-top: 52px;
    }

    .toa-sl-share__label {
        font-size: 12px;
        font-weight: 700;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .toa-sl-share-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        font-family: inherit;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: opacity 0.2s, background 0.25s;
        letter-spacing: 0.01em;
    }

    .toa-sl-share-btn:hover { opacity: 0.85; }

    .toa-sl-share-btn svg {
        width: 16px;
        height: 16px;
        fill: currentColor;
        flex-shrink: 0;
    }

    .toa-sl-share-btn--wa   { background: #25D366; color: #fff; }
    .toa-sl-share-btn--copy { background: #222; color: #fff; }
    .toa-sl-share-btn--copy.toa-copied { background: #25D366; }

    /* ── LEGGI ANCHE ── */
    .toa-sl-related {
        background: #111;
        padding: 52px 60px 60px;
        margin-top: 60px;
    }

    .toa-sl-related__inner {
        max-width: 1100px;
        margin: 0 auto;
    }

    .toa-sl-related__title {
        font-size: 24px;
        font-weight: 900;
        color: #fff;
        margin-bottom: 32px;
        letter-spacing: -0.3px;
    }

    .toa-sl-related__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .toa-sl-related__card {
        display: flex;
        gap: 14px;
        align-items: flex-start;
        text-decoration: none;
        padding: 14px;
        border-radius: 10px;
        border: 1px solid transparent;
        transition: border-color 0.2s, background 0.2s;
    }

    .toa-sl-related__card:hover {
        border-color: #C5FF00;
        background: #1a1a1a;
    }

    .toa-sl-related__card img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 6px;
        flex-shrink: 0;
        display: block;
        background: #222;
    }

    .toa-sl-related__thumb-ph {
        width: 80px;
        height: 60px;
        border-radius: 6px;
        background: #222;
        flex-shrink: 0;
    }

    .toa-sl-related__body { flex: 1; min-width: 0; }

    .toa-sl-related__cat {
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

    .toa-sl-related__post-title {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 900px) {
        .toa-sl-related { padding: 40px 20px 48px; }
        .toa-sl-related__grid { grid-template-columns: 1fr; }
        .toa-sl-title { font-size: 36px; letter-spacing: -0.5px; }
    }

    @media (max-width: 600px) {
        .toa-sl-hero__img { height: 320px; }
        .toa-sl-hero__meta { padding: 24px 20px 28px; }
        .toa-sl-hero--noimg { padding: 48px 20px 0; }
        .toa-sl-title { font-size: 28px; letter-spacing: -0.3px; }
        .toa-sl-content-area { padding: 36px 16px 0; }
        .toa-sl-content { font-size: 16px; }
    }
</style>

<!-- BARRA DI LETTURA -->
<div id="toa-sl-progress" aria-hidden="true"></div>

<div class="toa-sl-page">

<?php while ( have_posts() ) : the_post(); ?>

    <?php
    $sl_cats   = get_the_category();
    $sl_cat    = ! empty( $sl_cats ) ? $sl_cats[0] : null;
    $has_thumb = has_post_thumbnail();
    ?>

    <!-- HERO -->
    <div class="toa-sl-hero<?php echo $has_thumb ? '' : ' toa-sl-hero--noimg'; ?>">

        <?php if ( $has_thumb ) : ?>
            <?php the_post_thumbnail( 'full', array(
                'class'   => 'toa-sl-hero__img',
                'loading' => 'eager',
                'alt'     => esc_attr( get_the_title() ),
            ) ); ?>
            <div class="toa-sl-hero__overlay"></div>
        <?php endif; ?>

        <div class="toa-sl-hero__meta">
            <?php if ( $sl_cat ) : ?>
                <a href="<?php echo esc_url( get_category_link( $sl_cat->term_id ) ); ?>"
                   class="toa-sl-cat">
                    <?php echo esc_html( $sl_cat->name ); ?>
                </a><br>
            <?php endif; ?>
            <h1 class="toa-sl-title"><?php the_title(); ?></h1>
            <div class="toa-sl-meta">
                <span><?php echo esc_html( get_the_author() ); ?></span>
                <span class="toa-sl-meta__sep">&middot;</span>
                <span><?php echo esc_html( get_the_date('d F Y') ); ?></span>
            </div>
        </div>

    </div><!-- .toa-sl-hero -->

    <!-- CONTENUTO -->
    <div class="toa-sl-content-area">
        <div class="toa-sl-content">
            <?php the_content(); ?>
        </div>

        <!-- SHARE -->
        <div class="toa-sl-share">
            <span class="toa-sl-share__label"><?php echo _ht(['it'=>'Condividi','en'=>'Share','fr'=>'Partager','es'=>'Compartir']); ?>:</span>

            <a href="https://wa.me/?text=<?php echo rawurlencode( get_the_title() . ' — ' . get_permalink() ); ?>"
               target="_blank"
               rel="noopener"
               class="toa-sl-share-btn toa-sl-share-btn--wa"
               aria-label="<?php echo _ht(['it'=>'Condividi su WhatsApp','en'=>'Share on WhatsApp','fr'=>'Partager sur WhatsApp','es'=>'Compartir en WhatsApp']); ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/>
                </svg>
                WhatsApp
            </a>

            <button class="toa-sl-share-btn toa-sl-share-btn--copy"
                    data-url="<?php echo esc_attr( get_permalink() ); ?>"
                    onclick="toaSlCopy(this)"
                    aria-label="<?php echo _ht(['it'=>'Copia link articolo','en'=>'Copy article link','fr'=>'Copier le lien de l\'article','es'=>'Copiar enlace del artículo']); ?>">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
                </svg>
                <?php echo _ht(['it'=>'Copia link','en'=>'Copy link','fr'=>'Copier le lien','es'=>'Copiar enlace']); ?>
            </button>
        </div>
    </div><!-- .toa-sl-content-area -->

    <!-- LEGGI ANCHE -->
    <?php
    $sl_rel_args = array(
        'posts_per_page'      => 3,
        'post__not_in'        => array( get_the_ID() ),
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'orderby'             => 'date',
        'order'               => 'DESC',
    );
    if ( $sl_cat ) {
        $sl_rel_args['category__in'] = array( $sl_cat->term_id );
    }
    $sl_related = new WP_Query( $sl_rel_args );
    ?>

    <?php if ( $sl_related->have_posts() ) : ?>
        <section class="toa-sl-related" aria-label="Articoli correlati">
            <div class="toa-sl-related__inner">
                <h2 class="toa-sl-related__title">Leggi anche</h2>
                <div class="toa-sl-related__grid">
                    <?php while ( $sl_related->have_posts() ) : $sl_related->the_post(); ?>
                        <?php
                        $rel_cats = get_the_category();
                        $rel_cat  = ! empty( $rel_cats ) ? $rel_cats[0] : null;
                        ?>
                        <a href="<?php the_permalink(); ?>" class="toa-sl-related__card">

                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'thumbnail', array(
                                    'loading' => 'lazy',
                                    'alt'     => esc_attr( get_the_title() ),
                                ) ); ?>
                            <?php else : ?>
                                <div class="toa-sl-related__thumb-ph"></div>
                            <?php endif; ?>

                            <div class="toa-sl-related__body">
                                <?php if ( $rel_cat ) : ?>
                                    <span class="toa-sl-related__cat">
                                        <?php echo esc_html( $rel_cat->name ); ?>
                                    </span>
                                <?php endif; ?>
                                <div class="toa-sl-related__post-title"><?php the_title(); ?></div>
                            </div>

                        </a>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

<?php endwhile; ?>

</div><!-- .toa-sl-page -->

<script>
/* ── READING PROGRESS ── */
(function () {
    var bar = document.getElementById('toa-sl-progress');
    if ( !bar ) return;
    window.addEventListener('scroll', function () {
        var doc = document.documentElement;
        var max = doc.scrollHeight - doc.clientHeight;
        var pct = max > 0 ? (window.scrollY / max) * 100 : 0;
        bar.style.width = Math.min(pct, 100) + '%';
    }, { passive: true });
}());

/* ── COPIA LINK ── */
function toaSlCopy(btn) {
    var url  = btn.getAttribute('data-url');
    var orig = btn.innerHTML;

    function onCopied() {
        btn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true" style="width:16px;height:16px;fill:currentColor;flex-shrink:0"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg> <?php $__c=['it'=>'Copiato!','en'=>'Copied!','fr'=>'Copié !','es'=>'¡Copiado!']; echo esc_js($__c[toa_current_lang()] ?? $__c['it']); ?>';
        btn.classList.add('toa-copied');
        setTimeout(function () {
            btn.innerHTML = orig;
            btn.classList.remove('toa-copied');
        }, 2500);
    }

    if ( navigator.clipboard && window.isSecureContext ) {
        navigator.clipboard.writeText(url).then(onCopied).catch(function () {
            toaFallbackCopy(url); onCopied();
        });
    } else {
        toaFallbackCopy(url); onCopied();
    }
}

function toaFallbackCopy(text) {
    var ta = document.createElement('textarea');
    ta.value = text;
    ta.style.cssText = 'position:fixed;opacity:0;top:0;left:0;pointer-events:none';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try { document.execCommand('copy'); } catch(e) {}
    document.body.removeChild(ta);
}
</script>

<?php toa_component('footer'); ?>
