<?php
/**
 * Component: Page Hero
 * Usage: toa_component('page-hero', array('breadcrumb' => 'MODELS', 'title' => 'Models.', 'subtitle' => '...'))
 */
$breadcrumb = isset($breadcrumb) ? $breadcrumb : '';
$title      = isset($title) ? $title : '';
$subtitle   = isset($subtitle) ? $subtitle : '';
?>
<section class="page-hero">
  <div class="container">
    <?php if ($breadcrumb) : ?>
    <div class="page-hero-breadcrumb">TOAGENCY / <?php echo esc_html($breadcrumb); ?></div>
    <?php endif; ?>
    <h1 class="page-hero-title"><?php echo esc_html($title); ?></h1>
    <?php if ($subtitle) : ?>
    <p class="page-hero-subtitle"><?php echo $subtitle; ?></p>
    <?php endif; ?>
  </div>
</section>
