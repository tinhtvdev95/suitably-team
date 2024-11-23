<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details.
 */
?>
<section class="product-details">
  <div class="section__inner">
    <?php get_template_part('gpw-templates/woocommerce/product-details-gallery', null, ['product' => $product]) ?>
    <div class="product-details__content">Content</div>
  </div>
</section>