<?php
// Ticket #824: genera l'HTML delle 4 pagine PDF. Uso: php render-presentazione-pdf.php <it|en|fr|es> <cartella toagency-theme> [file-font.html] > out.html
// poi: google-chrome --headless=new --no-pdf-header-footer --print-to-pdf=out.pdf file://.../out.html  (uscita: toagency-theme/assets/pdf/toagency-presentazione-2026-<lingua>.pdf)
// Uso: php render.php <lingua> <cartella-tema>  -> stampa l'HTML completo del PDF
$lang = $argv[1]; $root = rtrim($argv[2], '/');
$all = json_decode(file_get_contents($root . '/inc/presentazione-testi.json'), true);
$T = $all[$lang];
$css = file_get_contents($root . '/assets/css/presentazione-pdf.css');
$fonts = isset($argv[3]) && is_file($argv[3]) ? file_get_contents($argv[3]) : '<link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;1,500&display=swap" rel="stylesheet">';
echo '<!DOCTYPE html><html lang="' . $lang . '"><head><meta charset="utf-8"><title>' . htmlspecialchars($T['doc_title']) . '</title>' . $fonts . '<style>' . $css . '</style></head><body>';
include $root . '/components/presentazione-pdf.php';
echo '</body></html>';
