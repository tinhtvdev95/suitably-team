<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details content.
 */
global $product;
if (!$product) {
  return;
}
$title = $product->get_name();
$shortDesc = $product->get_short_description();
$priceHtml = $product->get_price_html();
$description = $product->get_description();
?>
<div class="product-details__content">
  <h1 class="product-details__title"><?= esc_html($title) ?></h1>
  <div class="product-details__price">
    <?= $priceHtml ?>
  </div>
  <div class="product-details__short-desc">
    <?= wp_kses_post($shortDesc) ?>
  </div>
  <?php get_template_part('gpw-templates/woocommerce/product-details', 'fit-customization-form') ?>
</div>