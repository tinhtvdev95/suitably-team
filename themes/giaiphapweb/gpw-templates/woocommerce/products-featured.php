<?php

use \gpw\controller\ProductController;
$productController = new ProductController();


/**
 * @package Giaiphapweb_Theme
 * * Template for products featured.
 */
?>
<div class="featured-product__main">
  <?php
  foreach ($args['data_product'] as $product):
    get_template_part(
      'gpw-templates/woocommerce/product',
      null,
      [
        'class' => 'featured-product__item-product',
        'product-link' => $product['link'],
        'product-image' => $product['image'],
        'product-title' => $product['title'],
        'product-price' => $product['price'],
      ]
    );
  endforeach;
  ?>
</div>