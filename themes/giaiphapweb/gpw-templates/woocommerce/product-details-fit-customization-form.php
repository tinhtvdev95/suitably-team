<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details Fit and Customization form.
 */
global $product;
$currencySymbol = get_woocommerce_currency_symbol();

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
    <aside class="customize-popup__process-step">
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
          <a href="javascript:void(0);" data-target="confirm">Confirm</a>
        </li>
      </ul>
    </aside>
    <div class="customize-popup__detail-step">
      <?php for ($i = 0; $i < count($steps); $i++):
        $step = $steps[$i];
        $class = ['customize-popup__step'];
        if ($i === $stepActiveID) {
          $class[] = 'customize-popup__step--active';
        }
        $options = $step['options'];
        $stepTitle = $step['name'];
        $stepID = sanitize_title($step['name']);
        ?>
        <div class="<?= implode(' ', $class) ?>" id="<?= esc_attr($stepID) ?>">
          <?php if ($i !== 3): ?>
            <h3 class="customize-popup__step-title"><?= esc_html($stepTitle) ?></h3>
            <div class="customize-popup__step-options <?= count($options) >= 4 ? esc_attr('customize-popup__step-options--flex-start') : '' ?>">
              <?php for ($j = 0; $j < count($options); $j++):
                $option = $options[$j];

                $featureImgID = $option['feature_img_id'];
                $inputName = $stepID;
                $inputValue = $option['name'];
                $inputText = $option['name'];
                $optionPrice = $option['price'];
                $optionClass = ['step-option'];
                ?>
                <label class="<?= esc_attr(implode(' ', $optionClass)) ?>">
                  <input type="radio" name="<?= esc_attr($inputName) ?>" value="<?= esc_attr($inputValue) ?>" <?= $j === 0 ? 'checked' : '' ?> data-slug="<?= esc_attr( sanitize_title($inputValue) ) ?>">
                  <span class="step-option__name"><?= esc_html($inputText) ?></span>
                  <?= wp_get_attachment_image($featureImgID, 'large_medium', false, ['class' => 'step-option__feature-img']) ?>
                  <div class="step-option__meta">
                    <span class="step-option__price"><?= $optionPrice ? "$optionPrice $currencySymbol" : '' ?></span>
                    <span class="step-option__state material-symbols-outlined">check</span>
                  </div>
                </label>
              <?php endfor; ?>
            </div>
          <?php else:
            for ($optionID = 0; $optionID < count($step['options']); $optionID++) {
              get_template_part(
                'gpw-templates/woocommerce/product-details',
                'custom-option',
                ['optionIndex' => $optionID, 'chooseDetailsCommon' => $step['options'][$optionID]]
              );
            }
          endif ?>
          <button type="button" class="customize-popup__step-continue-btn gpw-button gpw-button__gradient"
            data-option-name="<?= esc_attr($stepID) ?>">Continue</button>
        </div>

      <?php endfor; ?>
      <div class="customize-popup__step" id="confirm">
        <h3 class="customize-popup__step-title">Confirm</h3>
        <div class="customize-popup__review-selection"></div>
        <button type="submit" class="customize-popup__submit-btn gpw-button gpw-button__gradient">Confirm</button>
      </div>
    </div>

  </form>