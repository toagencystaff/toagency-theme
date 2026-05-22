<?php
/**
 * Template Name: Blog Archive
 * Archivio blog TOAgency — UI premium dark
 * Aggiornato: 2026-05-21
 */

toa_component('header');
?>

<style>
    html, body { background: #000 !important; }

    /* ── HERO ── */
    @keyframes toaBlFadeUp {
        from { opacity: 0; transform: translateY(28px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .toa-bl-hero {
        background: #000;
        padding: 96px 60px 72px;
        text-align: center;
    }

    .toa-bl-hero__title {
        font-size: 80px;
        font-weight: 900;
        color: #fff;
        letter-spacing: -3px;
        line-height: 1;
        margin: 0 0 18px;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        animation: toaBlFadeUp 0.7s ease both;
    }

    .toa-bl-hero__sub {
        font-size: 18px;
        color: #C5FF00;
        font-weight: 400;
        margin: 0;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        animation: toaBlFadeUp 0.7s 0.15s ease both;
    }

    /* ── GRID WRAP ── */
    .toa-bl-wrap {
        background: #000;
        padding: 52px 60px 80px;
        min-height: 400px;
    }

    /* ── GRID ── */
    .toa-bl-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        max-width: 1260px;
        margin: 0 auto;
    }

    /* ── CARD ── */
    .toa-bl-card {
        background: #111;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid transparent;
        transition: border-color 0.25s ease, box-shadow 0.25s ease,
                    opacity 0.35s ease, transform 0.35s ease;
    }

    .toa-bl-card:hover {
        border-color: #C5FF00;
        box-shadow: 0 0 24px rgba(197, 255, 0, 0.15);
    }

    .toa-bl-card__link {
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
        height: 100%;
    }

    /* Image */
    .toa-bl-card__img-wrap {
        position: relative;
        aspect-ratio: 16 / 9;
        overflow: hidden;
        flex-shrink: 0;
    }

    .toa-bl-card__img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.4s ease;
    }

    .toa-bl-card:hover .toa-bl-card__img { transform: scale(1.05); }

    .toa-bl-card__img-placeholder {
        width: 100%;
        height: 100%;
        background: #1a1a1a;
    }

    .toa-bl-card__img-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40%;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.75) 0%, transparent 100%);
        pointer-events: none;
    }

    /* Body */
    .toa-bl-card__body {
        padding: 20px 22px 24px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .toa-bl-card__cat {
        display: inline-block;
        background: #C5FF00;
        color: #000;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 3px 10px;
        border-radius: 4px;
        margin-bottom: 12px;
        align-self: flex-start;
    }

    .toa-bl-card__title {
        font-size: 20px;
        font-weight: 700;
        color: #fff;
        line-height: 1.3;
        margin: 0 0 10px;
        font-family: 'Helvetica Neue', Arial, sans-serif;
    }

    .toa-bl-card__excerpt {
        font-size: 14px;
        color: #888;
        line-height: 1.6;
        margin: 0 0 18px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    .toa-bl-card__read {
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        border-bottom: 1px solid transparent;
        transition: border-color 0.2s;
        align-self: flex-start;
    }

    .toa-bl-card:hover .toa-bl-card__read { border-color: #fff; }

    /* ── EMPTY ── */
    .toa-bl-empty {
        text-align: center;
        color: #555;
        font-size: 16px;
        padding: 80px 20px;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        max-width: 1260px;
        margin: 0 auto;
    }

    /* ── WIP PLACEHOLDER ── */
    .toa-bl-wip-badge {
        display: inline-block;
        background: #C5FF00;
        color: #000;
        font-size: 34px;
        font-weight: 900;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        padding: 24px 36px;
        border-radius: 12px;
        width: 320px;
        line-height: 1.15;
        letter-spacing: -0.5px;
        margin-bottom: 52px;
    }

    .toa-bl-wip-title {
        text-align: center;
        color: #fff;
        font-size: 48px;
        font-weight: 900;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        letter-spacing: -1px;
        margin: 0 0 52px;
    }

    .toa-bl-wip-card {
        opacity: 0.4;
        pointer-events: none;
        user-select: none;
    }

    .toa-bl-wip-note {
        text-align: center;
        color: #555;
        font-size: 15px;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        margin: 40px auto 0;
        max-width: 420px;
        line-height: 1.6;
    }

    /* ── PAGINAZIONE JS ── */
    .toa-bl-pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 52px;
        flex-wrap: wrap;
    }

    .toa-bl-page-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        padding: 0 14px;
        border-radius: 100px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: transparent;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Helvetica Neue', Arial, sans-serif;
        cursor: pointer;
        transition: border-color 0.2s, color 0.2s, background 0.2s;
    }

    .toa-bl-page-btn:hover { border-color: #C5FF00; color: #C5FF00; }
    .toa-bl-page-btn.active { background: #C5FF00; border-color: #C5FF00; color: #000; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1024px) {
        .toa-bl-grid { grid-template-columns: repeat(2, 1fr); }
        .toa-bl-hero__title { font-size: 60px; letter-spacing: -2px; }
    }

    @media (max-width: 680px) {
        .toa-bl-hero { padding: 60px 20px 48px; }
        .toa-bl-hero__title { font-size: 44px; letter-spacing: -1.5px; }
        .toa-bl-hero__sub { font-size: 15px; }
        .toa-bl-wrap { padding: 36px 20px 60px; }
        .toa-bl-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- HERO -->
<section class="toa-bl-hero">
    <h1 class="toa-bl-hero__title">Blog</h1>
    <p class="toa-bl-hero__sub">Backstage, articoli e video dal mondo TOAgency</p>
</section>

<!-- BLOG WIP -->
<div class="toa-bl-wrap">

    <!-- Badge top-left "Il nostro Blog" -->
    <div class="toa-bl-wip-badge">Il nostro Blog</div>

    <!-- Work in Progress -->
    <p class="toa-bl-wip-title">Work in Progress</p>

    <!-- 3 card placeholder -->
    <div class="toa-bl-grid">

        <article class="toa-bl-card toa-bl-wip-card">
            <div class="toa-bl-card__link">
                <div class="toa-bl-card__img-wrap">
                    <div class="toa-bl-card__img-placeholder"></div>
                    <div class="toa-bl-card__img-overlay"></div>
                </div>
                <div class="toa-bl-card__body">
                    <span class="toa-bl-card__cat">Categoria</span>
                    <h2 class="toa-bl-card__title">Lorem ipsum dolor sit amet consectetur adipiscing</h2>
                    <p class="toa-bl-card__excerpt">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.</p>
                    <span class="toa-bl-card__read">Leggi &rarr;</span>
                </div>
            </div>
        </article>

        <article class="toa-bl-card toa-bl-wip-card">
            <div class="toa-bl-card__link">
                <div class="toa-bl-card__img-wrap">
                    <div class="toa-bl-card__img-placeholder"></div>
                    <div class="toa-bl-card__img-overlay"></div>
                </div>
                <div class="toa-bl-card__body">
                    <span class="toa-bl-card__cat">Backstage</span>
                    <h2 class="toa-bl-card__title">Ut enim ad minim veniam quis nostrud exercitation</h2>
                    <p class="toa-bl-card__excerpt">Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo.</p>
                    <span class="toa-bl-card__read">Leggi &rarr;</span>
                </div>
            </div>
        </article>

        <article class="toa-bl-card toa-bl-wip-card">
            <div class="toa-bl-card__link">
                <div class="toa-bl-card__img-wrap">
                    <div class="toa-bl-card__img-placeholder"></div>
                    <div class="toa-bl-card__img-overlay"></div>
                </div>
                <div class="toa-bl-card__body">
                    <span class="toa-bl-card__cat">Video</span>
                    <h2 class="toa-bl-card__title">Duis aute irure dolor in reprehenderit voluptate</h2>
                    <p class="toa-bl-card__excerpt">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                    <span class="toa-bl-card__read">Leggi &rarr;</span>
                </div>
            </div>
        </article>

    </div><!-- .toa-bl-grid -->

    <p class="toa-bl-wip-note">Gli articoli saranno disponibili presto. Torna a trovarci!</p>

</div><!-- .toa-bl-wrap -->

<?php toa_component('footer'); ?>
