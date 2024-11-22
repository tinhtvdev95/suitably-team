<?php
/**
 * @package GiapPhapWeb_Theme
 * * The template for display single product.
 */
$product = wc_get_product(get_the_ID());
$productImgID = $product->get_image_id();
$galleryIDs = $product->get_gallery_image_ids();

if(!in_array($productImgID, $galleryIDs)) {
  $galleryIDs[] = $productImgID;
}
dump($galleryIDs);

get_header();
?>

<div id="content">
  <?php get_template_part('gpw-templates/woocommerce/section-product-details', null) ?>
</div>

<?php
get_footer();