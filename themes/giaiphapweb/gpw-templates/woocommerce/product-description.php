<?php 
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product description.
 */
global $product;
if (!$product) {
  return;
}
$description = $product->get_description();
?>
<section class="product-description">
  <div class="section__inner">
    <?= wp_kses_post($description) ?>
  </div>
</section>