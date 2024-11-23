<?php
/**
 * @package GiapPhapWeb_Theme
 * * The template for display single product.
 */
$product = wc_get_product(get_the_ID());

get_header();
?>

<div id="content">
  <?php get_template_part('gpw-templates/woocommerce/section-product-details', null, ['product' => $product]) ?>
</div>

<?php
get_footer();