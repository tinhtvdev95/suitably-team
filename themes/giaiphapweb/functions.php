<?php
// Turn off auto gen <p> of contact form 7
// add_filter('wpcf7_autop_or_not', false);

defined('ABSPATH') || exit;

// Load autoload
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
  require_once __DIR__ . '/vendor/autoload.php';
}

// Register services
if (class_exists('gpweb\\inc\\ThemeInit')) {
  gpweb\inc\ThemeInit::register_services();
}

$controllerPaths = [
  'controller/product/ProductController.php',
  'controller/post/PostController.php'
];

foreach ($controllerPaths as $path) {
  include get_theme_file_path($path);
}
