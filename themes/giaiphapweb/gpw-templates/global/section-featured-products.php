<?php

use \gpw\controller\ProductController;
$productController = new ProductController();


/**
 * @package Giaiphapweb_Theme
 * * Template for Featured products section.
 */
$dataProducts = $productController->getFeaturedProducts();
?>
<section class="featured-product">
  <div class="section__inner">
    <div class="featured-product__bg">
      <div class="featured-product__top">
        <h2 class="text__title">Some Of Our Classics</h2>
      </div>
      <?php get_template_part(
        'gpw-templates/woocommerce/products',
        'featured',
        [
          'data_product' => $dataProducts,
        ]
      ); ?>
      <div class="featured-product__bottom">
        <?php get_template_part(
        'gpw-templates/global/buttons',
        null,
        [
          'text' => 'Explore The Whole Range',
          'url' => get_permalink(wc_get_page_id( 'shop' ) ),
          'type' => 'gradient',
        ]
      ); ?>
      </div>
    </div>
  </div>
</section>