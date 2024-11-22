<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for products featured.
 */
?>
<div class="product-detail <?= $args['class'] ?>">
  <a class="product-detail__image" href="<?= esc_url($args['product-link']); ?>">
    <?= $args['product-image']; ?>
  </a>
  <a href="<?= esc_url($args['product-link']); ?>">
    <p class="product-detail__title">
      <?= esc_html($args['product-title']) ?>
    </p>
  </a>
  <p class="product-detail__price">
    <?= $args['product-price']; ?>
  </p>
  <?php get_template_part(
    'gpw-templates/global/buttons',
    null,
    [
      'text' => 'Explore & Customise',
      'url' => $args['product-link'],
      'type' => 'outline-slide-bottom',
    ]
  ); ?>
</div>