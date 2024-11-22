<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for buttons.
 */
$classes = ['gpw-button'];
$buttonText = $args['text'] ?? 'Button';
$buttonUrl = $args['url'] ?? 'javascript:void(0);';
$buttonType = $args['type'] ?? false;
if($buttonType) {
  switch($buttonType) {
    case 'outline':
      $classes[] = 'gpw-button__outline';
      break;
    case 'outline-slide-bottom':
      $classes[] = 'gpw-button__outline';
      $classes[] = 'gpw-button__outline--slide-bottom';
      break;
    case 'gradient':
      $classes[] = 'gpw-button__gradient';
      break;
    default:
      break;
  }
}
?>
<a href="<?= $buttonUrl ?>" class="<?= esc_attr( implode(' ', $classes) ) ?>" role="button" aria-roledescription="<?= esc_attr($buttonText) ?>">
  <span><?= esc_html($buttonText) ?></span>
</a>