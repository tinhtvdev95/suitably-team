<?php

use \gpw\controller\ProductController;

/** 
 * @package GiaiPhapWeb_Theme
 * Template for displaying product details custom option
 */

global $product;


// Initialize Product Controller
$productController = new ProductController();

$categoriesId = $productController->getCategoriesOfProduct($product);

// Get options index
$optionIndex = $args['optionIndex'] ?? 0;

// Get options data
$chooseDetailsCommon = $args['chooseDetailsCommon'] ?? [];

if ($chooseDetailsCommon['children']) {
    $fitLevel = '';
    foreach ($chooseDetailsCommon['children'] as $child) {
        if ($child['merge_array']) {
            $fitLevel = $child['merge_array'];
            break;
        }
    }
    $chooseDetails = $productController->processProductOptions($chooseDetailsCommon['children'], $fitLevel);


} else {
    $chooseDetails = [];
}

// Function to render fit option fields
if (!function_exists('render_fit_option_fields')) {
    function render_fit_option_fields($fields, $parentName)
    {
        if (!$fields)
            return;

        foreach ($fields as $field):
            $fieldClass = ['fit-option-fields__main-merge', 'customize-popup__step-options'];
            if (count($field['option']) >= 4) {
                $fieldClass[] = 'customize-popup__step-options--flex-start';
            }
            ?>
            <h3 class="fit-option-fields-top__title"><?= esc_html($field['name']) ?></h3>
            <div class="<?= esc_attr(implode(' ', $fieldClass)) ?>">
                <?php if (!empty($field['option'])):
                    for ($i = 0; $i < count($field['option']); $i++):
                        $option = $field['option'][$i];
                        $inputName = $parentName . '-' . sanitize_title($field['name']);
                        $optionPrice = $option['price'] ?? 0;
                        ?>
                        <label class="fit-option-fields__item step-option">
                            <input type="radio" name="<?= esc_attr($inputName) ?>" 
                                value="<?= esc_attr($option['name']) ?>" 
                                <?= $i === 0 ? esc_attr('checked') : '' ?> 
                                data-slug="<?= esc_attr(sanitize_title($option['name'])) ?>"
                                data-price="<?= esc_attr($optionPrice) ?>">
                            <span class="step-option__name"><?= esc_html($option['name']) ?></span>
                            <?= wp_get_attachment_image($option['feature_img_id'], 'medium', false, ['class' => 'step-option__feature-img']) ?>
                            <div class="step-option__meta">
                                <span class="step-option__price"><?= $optionPrice ?: '' ?></span>
                                <span class="step-option__state material-symbols-outlined">check</span>
                            </div>
                        </label>
                    <?php endfor;
                endif; ?>
            </div>
        <?php endforeach;
    }
}

// Function to render swiper slides
if (!isset($render_swiper_slides) && empty($render_swiper_slides)) {
    $render_swiper_slides = function ($chooseDetails, $commonDetails) use ($categoriesId) {
        if (!isset($chooseDetails) && empty($chooseDetails))
            return;
        $fieldName = sanitize_title($commonDetails['name']);
        foreach ($chooseDetails as $key => $fields):
            $inputFieldName = $fieldName . '-' . sanitize_title($fields['name']);
            ?>
            <div class="swiper-slide">
                <div class="product-details__fit-option-fields fit-option-fields">
                    <div class="fit-option-fields__top">
                        <h3 class="fit-option-fields-top__title">
                            <?= esc_html(array_key_exists('name', $fields) ? $fields['name'] : '') ?>
                        </h3>
                        <p class="fit-option-fields-top__description"><?= esc_html($fields['description']) ?></p>
                    </div>
                    <?php
                    // Render child options if not numeric key
                    if (!is_numeric($key)) {
                        render_fit_option_fields($fields, $fieldName);
                    } else {
                        $fieldClass = ['fit-option-fields__main', 'customize-popup__step-options'];
                        if (is_array($fields['option']) && count($fields['option']) >= 4) {
                            $fieldClass[] = 'customize-popup__step-options--flex-start';
                        } ?>
                        <div class="<?= esc_attr(implode(' ', $fieldClass)) ?>">
                            <?php for ($i = 0; $i < count($fields['option']); $i++):
                                $field = $fields['option'][$i];
                                $optionPrice = $field['price'] ?: 0;
                                $relatedCategoryId = $field['related_category'];
                                $checked = in_array($relatedCategoryId, $categoriesId);
                                ?>
                                <label class="fit-option-fields__item step-option">
                                    <input type="radio" name="<?= esc_attr($inputFieldName) ?>" 
                                    value="<?= esc_attr($field['name']) ?>" <?= $checked ? esc_attr('checked') : '' ?> 
                                    data-slug="<?= esc_attr(sanitize_title($field['name'])) ?>"
                                    data-price="<?= esc_attr($optionPrice) ?>">
                                    <span class="step-option__name"><?= esc_html($field['name']) ?></span>
                                    <?= wp_get_attachment_image($field['feature_img_id'], 'medium', false, ['class' => 'step-option__feature-img']) ?>
                                    <div class="step-option__meta">
                                        <span class="step-option__price"><?= $optionPrice ?: '' ?></span>
                                        <span class="step-option__state material-symbols-outlined">check</span>
                                    </div>
                                </label>
                            <?php endfor; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php endforeach;
    };
}

?>

<div class="product-details-custom-option__container" id="<?= sanitize_title($chooseDetailsCommon['name']) ?>">
    <a class="product-details-custom-option__title" href="javascript:void(0);">
        <h3><?= esc_html($chooseDetailsCommon['name']) ?></h3>
        <p>Click here to start customize.</p>
    </a>
    <div class="swiper swiper-product-details-custom-option">
        <div class="swiper-wrapper">
            <?php $render_swiper_slides($chooseDetails, $chooseDetailsCommon); ?>
        </div>
        <div class="navigation">
            <div class="navigation__prev">PREV</div>
            <div class="navigation__next">NEXT</div>
        </div>
    </div>
</div>