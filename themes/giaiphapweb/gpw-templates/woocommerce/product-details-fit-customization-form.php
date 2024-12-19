<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details Fit and Customization form.
 */

use gpw\controller\ProductController;

$productController = new ProductController();


global $product;

const FABRIC = 'choose-fabric';
const DETAIL = 'choose-details';
const MAX_OPTIONS_SIZE = 4;

$categoriesProductName = $productController->getCategoriesOfProduct($product);

$steps = get_field('steps', 'customize_product_form');
$processSteps = array_map(function ($step) {
  return ['name' => $step['name'], 'slug' => sanitize_title($step['name'])];
}, $steps);
$stepActiveID = 0;
?>
<a href="javascript:void(0);"
  class="product-details__open-customize-popup-btn gpw-button gpw-button__outline gpw-button__outline--slide-bottom"
  role="button" aria-roledescription="Open customize popup">
  <span>Start Customize</span>
</a>
<dialog class="product-details__customize-popup">
  <button class="customize-popup__close-btn" aria-label="Close customize popup" title="Close customize popup">
    <span class="material-symbols-outlined">close</span>
  </button>
  <form class="customize-popup__fit-customization fit-customization" method="POST">
    <?php wp_nonce_field('fit_customization', 'fit_customization_nonce') ?>
    <input type="hidden" name="action" value="fit_customization">
    <input type="hidden" name="product-id" value="<?= $product->get_id() ?>">
    <input type="hidden" name="total_price" value="<?= esc_attr($product->get_price() ?? 0) ?>">
    <aside class="customize-popup__process-step">
      <div class="customize-popup__process-step-inner">
        <h3 class="customize-popup__process-step-title">Process steps</h3>
        <ul class="customize-popup__nav">
          <?php for ($i = 0; $i < count($processSteps); $i++):
            $step = $processSteps[$i];
            $class = ['customize-popup__nav-item'];
            $class[] = $i === $stepActiveID ? 'customize-popup__nav-item--active' : 'customize-popup__nav-item--disabled';
            ?>
            <li class="<?= esc_attr(implode(' ', $class)) ?>">
              <span class="material-symbols-outlined">check_circle</span>
              <a href="javascript:void(0);" data-target="<?= esc_attr($step['slug']) ?>"><?= esc_html($step['name']) ?></a>
            </li>
          <?php endfor; ?>
          <li class="customize-popup__nav-item customize-popup__nav-item--disabled">
            <span class="material-symbols-outlined">check_circle</span>
            <a href="javascript:void(0);" data-target="take-measurements">Take Measurements</a>
          </li>
          <li class="customize-popup__nav-item customize-popup__nav-item--disabled">
            <span class="material-symbols-outlined">check_circle</span>
            <a href="javascript:void(0);" data-target="confirm">Confirm</a>
          </li>
        </ul>
      </div>
    </aside>
    <div class="customize-popup__detail-step">
      <header class="customize-popup__header">
        <h2 class="customize-popup__product-title"><?= esc_html( $product->get_title() ) ?></h2>
        <div class="customize-popup__product-price">
          <span class="currency-symbol"><?= esc_html(get_woocommerce_currency_symbol()) ?></span>
          <span class="price"><?= esc_html( $product->get_price() ) ?></span>
        </div>
      </header>
      <?php for ($i = 0; $i < count($steps); $i++):
        $step = $steps[$i];
        $class = ['customize-popup__step'];
        if ($i === $stepActiveID) {
          $class[] = 'customize-popup__step--active';
        }
        $options = $step['options'] ?? [];
        $stepTitle = $step['name'];
        $stepSlug = sanitize_title($step['name']);
        $stepID = sanitize_title($step['name']);
        ?>
        <div class="<?= implode(' ', $class) ?>" id="<?= esc_attr($stepID) ?>">
          <?php if ($stepSlug === DETAIL): ?>
            <h3 class="customize-popup__step-title"><?= esc_html($stepTitle) ?></h3>
            <?php for ($optionID = 0; $optionID < count($step['options']); $optionID++) {
              get_template_part(
                'gpw-templates/woocommerce/product-details',
                'custom-option',
                ['optionIndex' => $optionID, 'chooseDetailsCommon' => $step['options'][$optionID]]
              );
            }         
          else: ?>
            <h3 class="customize-popup__step-title"><?= esc_html($stepTitle) ?></h3>
            <?php if (!empty($options)) : ?>
              <div class="customize-popup__step-options <?= count($options) >= MAX_OPTIONS_SIZE ? esc_attr('customize-popup__step-options--flex-start') : '' ?>">
                <?php 
                $children = [];
                for ($j = 0; $j < count($options); $j++):
                  $option = $options[$j];

                  $featureImgID = $option['feature_img_id'];
                  $inputName = $stepID;
                  $inputValue = $option['name'];
                  $inputSlug = sanitize_title($inputValue);
                  $inputText = $option['name'];
                  $optionPrice = $option['price'] ?: 0;
                  $optionClass = ['step-option'];

                  $children[$inputSlug] = $option['children'];
                  ?>
                  <label class="<?= esc_attr(implode(' ', $optionClass)) ?>" <?= $stepSlug == FABRIC ? 'is-fabric' : '' ?>>
                    <input type="radio" name="<?= esc_attr($inputName) ?>" value="<?= esc_attr($inputValue) ?>" <?= $j === 0 ? 'checked' : '' ?> data-slug="<?= esc_attr($inputSlug) ?>" data-price="<?= esc_attr($optionPrice) ?>">
                    <span class="step-option__name"><?= esc_html($inputText) ?></span>
                    <?= wp_get_attachment_image($featureImgID, 'large_medium', false, ['class' => 'step-option__feature-img']) ?>
                    <div class="step-option__meta">
                      <span class="step-option__price"><?= $optionPrice || $optionPrice != 0 ? $optionPrice : '' ?></span>
                      <span class="step-option__state material-symbols-outlined">check</span>
                    </div>
                  </label>
                <?php endfor ?>
              </div>
            <?php endif ?>
            <?php if ( $stepSlug === FABRIC ) : ?>
            <div class="customize-popup__step-details">
              <?php 
              $fabricIndex = 0; 
              foreach( $children as $key => $child ) {
                $args = [
                  'isActive' => $fabricIndex++ === 0,
                  'options' => $child,
                  'parentSlug' => $key
                ];
                get_template_part(
                  'gpw-templates/woocommerce/product-details',
                  'fabric-options',
                  $args
                );
              } ?>
            </div>
            <?php endif ?>
          <?php endif ?>
          <button type="button" class="customize-popup__step-continue-btn gpw-button gpw-button__gradient"
            data-option-name="<?= esc_attr($stepID) ?>">Continue</button>
        </div>

      <?php endfor; ?>
      <div class="customize-popup__step take-measurements" id="take-measurements">
        <h3 class="customize-popup__step-title">Take Measurements</h3>

          <?php get_template_part('gpw-templates/woocommerce/product-details', 'take-measurements') ?>

        <button type="button" class="customize-popup__step-continue-btn gpw-button gpw-button__gradient" data-option-name="take-measurements">Continue</button>
      </div>
      <div class="customize-popup__step" id="confirm">
        <h3 class="customize-popup__step-title">Confirm</h3>
        <div class="customize-popup__review-selection"></div>
        <button type="submit" class="customize-popup__submit-btn gpw-button gpw-button__gradient">Confirm</button>
      </div>
    </div>

  </form>