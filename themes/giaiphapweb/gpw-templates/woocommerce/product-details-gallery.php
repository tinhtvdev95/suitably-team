<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product gallery.
 */
global $product;
if (!$product) {
  return;
}
$productImgID = $product->get_image_id();
$galleryIDs = $product->get_gallery_image_ids();

if (!in_array($productImgID, $galleryIDs)) {
  $galleryIDs[] = $productImgID;
}
?>
<div class="product-details__gallery">
  <div class="gallery__main">
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php foreach ($galleryIDs as $imgID): ?>

          <div class="swiper-slide">
            <?= wp_get_attachment_image($imgID, 'large', false) ?>
          </div>

        <?php endforeach ?>
      </div>
    </div>
  </div>
  <div class="gallery__thumb">
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php foreach ($galleryIDs as $imgID): ?>

          <div class="swiper-slide">
            <?= wp_get_attachment_image($imgID, 'medium', false) ?>
          </div>

        <?php endforeach ?>
      </div>
    </div>
    <a href="javascript:void(0);" class="gallery__btn gallery__btn--prev" role="button" aria-roledescription="Gallery thumbnails next button">
      <span class="material-symbols-outlined">chevron_left</span>
    </a>
    <a href="javascript:void(0);" class="gallery__btn gallery__btn--next" role="button" aria-roledescription="Gallery thumbnails next button">
      <span class="material-symbols-outlined">chevron_right</span>
    </a>
  </div>
</div>