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

foreach ($optionFields as &$item) {
  if ($item['option']) {
    foreach ($item['option'] as &$option) {
      $option['url_image'] = wp_get_attachment_image($option['image']);
    }
  }

}
unset($item);

$fitLevel = 'fit_level';
// $optionFields = $productController->processProductOptions($optionFields, $fitLevel);
?>
<form class="product-details__fit-customization fit-customization" method="POST">
  <?php wp_nonce_field('fit_customization', 'fit_customization_nonce') ?>
  <input type="hidden" name="action" value="fit_customization">
  <nav class="fit-customization__title-customize">
    <?php
    for ($i = 0; $i < count($mainItemOptions); $i++):
      $item = $mainItemOptions[$i]; ?>
      <a class="fit-customization__title <?= ($i == 0) ? 'active' : '' ?>" data-index="<?= $i ?>">
        <?= $item['title']; ?>
      </a>
    <?php endfor; ?>
  </nav>
  <div class="fit-customization__container">
    <div class="swiper swiper-option-fields">
      <div class="swiper-wrapper">
        <?php
        foreach ($mainItemOptions as $key => $ItemOptionGroup):
          ?>
          <div class="swiper-slide">
            <div
              class="fit-options <?= $classHtml = (count($ItemOptionGroup['options']) != 0 ) ? 'fit-option-' . count($ItemOptionGroup['options'])  : 'fit-option'; ?>">
              <?php if ($ItemOptionGroup['options']):
                foreach ($ItemOptionGroup['options'] as $item):
                  renderFitOption($item, json_encode($optionFields));
                endforeach;
              endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="product-details__fit-option-fields fit-option-fields" method="POST">
    <?php wp_nonce_field('fit_customization', 'fit_customization_nonce') ?>
    <input type="hidden" name="action" value="fit_customization">
    <div class="fit-option-fields__top">
      <a class="fit-option-fields-top__go-back">
        <img src="/wp-content/uploads/2024/11/54476.png">
      </a>
      <h3 class="fit-option-fields-top__title"></h3>
    </div>
    <div class="fit-option-fields__main">
    </div>
  </div>
  <div class="navigation">
    <div class="navigation__prev">PREV</div>
    <div class="navigation__next">NEXT</div>
  </div>
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