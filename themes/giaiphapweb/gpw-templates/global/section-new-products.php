<?php

use \gpw\controller\ProductController;
$productController = new ProductController();


/**
 * @package Giaiphapweb_Theme
 * * Template for New products section.
 */
$dataProducts = $productController->getNewProducts();
?>
<section class="new-product">
  <div class="section__inner">
    <div class="new-product__bg">

      <?php get_template_part(
        'gpw-templates/woocommerce/products',
        'new',
        [
          'data_product' => $dataProducts,
        ]
      ); ?>
      <div class="new-product__bottom">
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