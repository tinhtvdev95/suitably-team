<?php

use \gpw\controller\ProductController;
$productController = new ProductController();


/**
 * @package Giaiphapweb_Theme
 * * Template for Featured products section.
 */
$pageID = get_the_ID();
$pageSlug = get_post_field('post_name', $pageID);

$dataProducts = $productController->getFeaturedProducts();
?>
<section class="featured-product">
  <div class="section__inner">
    <div class="featured-product__bg">
      <div class="featured-product__top">
        <h2 class="text__title">Some Of Our Classics</h2>
      </div>
      <div class="featured-product__main">
        <?php
        foreach ($dataProducts as $product):
          ?>
          <div class="featured-product__item-product">
            <h1>123123</h1>
          </div>
          <?php
        endforeach;
        ?>

      </div>
      <div class="featured-product__bottom">
      </div>
    </div>
  </div>
</section>