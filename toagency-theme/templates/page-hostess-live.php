<?php
/**
 * Template Name: Preventivo Staff Eventi
 * Form contatto B2B standard del sito (componente form-b2b-inline) -> lead -> /tnx/.
 * 2026-07-11: rimosso il calcolatore preventivi (spostato nel CRM interno).
 *   Stesso identico form di /form-b2b/ e delle landing. Un solo form da mantenere.
 */
require_once get_theme_file_path('templates/translations.php');
toa_component('header');
?>

<?php toa_component('page-hero', array(
    'breadcrumb' => toa_t('hostess_live', 'breadcrumb'),
    'title'      => toa_t('hostess_live', 'hero_title'),
    'subtitle'   => '<strong>' . toa_t('hostess_live', 'hero_subtitle_bold') . '</strong><br>'
                   . toa_t('hostess_live', 'hero_subtitle_text')
                   . '<br><br><span style="color:var(--accent)">'
                   . toa_t('hostess_live', 'hero_badge') . '</span>',
)); ?>

<?php toa_component('form-b2b-inline'); ?>

<?php toa_component('footer'); ?>
