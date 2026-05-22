<?php
/**
 * Default template fallback
 * WordPress requires this file to exist
 */
toa_component('header');
?>

<section class="page-hero">
  <div class="container">
    <h1 class="page-hero-title"><?php the_title(); ?></h1>
  </div>
</section>

<section class="why-section">
  <div class="container">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div style="max-width:800px;color:var(--gray-5);line-height:1.8;font-size:1rem">
        <?php the_content(); ?>
      </div>
    <?php endwhile; endif; ?>
  </div>
</section>

<?php toa_component('footer'); ?>
