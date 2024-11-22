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
    ?>
    <div class="featured-product__item-product">
      <div class="item-product__inner">
        <a href="<?= esc_url($product['link']); ?>">
          <?= $product['image']; ?>
        </a>
        <a href="<?= esc_url($product['link']); ?>">
          <p class="item-product__title">
            <?= esc_html($product['title']) ?>
          </p>
        </a>
        <p class="item-product__price">
          <?= $product['price']; ?>
        </p>
        <?php get_template_part(
          'gpw-templates/global/buttons',
          null,
          [
            'text' => 'Explore & Customise',
            'url' => $product['link'],
            'type' => 'outline-slide-bottom',
          ]
        ); ?>
      </div>
    </div>
    <?php
  endforeach;
  ?>
</div>