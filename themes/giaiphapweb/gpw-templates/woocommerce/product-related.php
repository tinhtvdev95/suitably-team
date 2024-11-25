<?php
use \gpw\controller\ProductController;
$productController = new ProductController();

/**
 * @package GiapPhapWeb_Theme
 * Template for displaying related products.
 */
if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly
}

global $product;

if (!$product) {
  return;
}

$dataProducts = $productController->getRelatedProducts($product->get_id());

?>
<section class="related-product">
  <div class="section__inner">
    <div class="related-product__top">
      <h2 class="text__title">Related Products</h2>
    </div>
    <div class="related-product__main">
      <?php
      foreach ($dataProducts as $product):
        get_template_part(
          'gpw-templates/woocommerce/product',
          null,
          [
            'class' => 'related-product__item-product',
            'product-link' => $product['link'],
            'product-image' => $product['image'],
            'product-title' => $product['title'],
            'product-price' => $product['price'],
          ]
        );
      endforeach;
      ?>
    </div>
  </div>
</section>