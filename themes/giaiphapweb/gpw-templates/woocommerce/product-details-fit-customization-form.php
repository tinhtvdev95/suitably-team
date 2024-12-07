<?php

use \gpw\controller\ProductController;
$productController = new ProductController();

/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details Fit and Customization form.
 */
global $product;

$productOptionFields = get_fields('product-options-fields', 'product_option_field');

$titleOptions = $productOptionFields['product_option_field'][0]['title'];

$mainItemOptions = $productOptionFields['product_option_field'];


$optionFields = $productOptionFields['product_option_field'][0]['options'];

// foreach ($optionFields as &$item) {
//   if ($item['option']) {
//     foreach ($item['option'] as &$option) {
//       $option['url_image'] = wp_get_attachment_image($option['image']);
//     }
//   }

// }
// unset($item);

$fitLevel = 'fit_level';
// $optionFields = $productController->processProductOptions($optionFields, $fitLevel);
?>
<form class="product-details__fit-customization fit-customization" method="POST">
  <?php wp_nonce_field('fit_customization', 'fit_customization_nonce') ?>
  <input type="hidden" name="action" value="fit_customization">
  <nav class="fit-customization__title-customize">
  </nav>
  <div class="fit-customization__container">
    <div class="swiper swiper-option-fields">
      <div class="swiper-wrapper">
      </div>
    </div>
  </div>
  <?php get_template_part(
    'gpw-templates/woocommerce/product-details',
    'custom-option'

  ) ?>
</form>

<?php
function renderFitOption($item, $data, $inputName = 'fit-option')
{
  $name = $item['name'] ?? $item['title'];
  ?>
  <label class="fit-options__label-image">
    <input class="fit-options__input" type="radio" name="<?= esc_attr($inputName) ?>" value="<?= esc_attr($name) ?>"
      data-price="<?= esc_attr($item['price']) ?>">
    <input class="fit-options__input-data" type="hidden" value="<?= esc_attr($data) ?>">
    <img class="fit-options__image" src="<?= esc_url(wp_get_attachment_url($item['image'])) ?>"
      data-tm-tooltip-swatch-img-lbl-desc="on" data-tm-hide-label="no">
    <p class="fit-options__name"><?= esc_html($name) ?></p>
  </label>
  <?php
}