<?php

use \gpw\controller\ProductController;

/** 
 * @package GiaiPhapWeb_Theme
 * Template for displaying product details custom option
 */

// Initialize Product Controller
$productController = new ProductController();

// Get options data
$optionFields = get_field('steps', 'customize_product_form');
$chooseDetailsCommon = $optionFields[3]['options'][0];

// Define fit level
$fitLevel = 'vent and chest';
$chooseDetails = $productController->processProductOptions($chooseDetailsCommon['children'], $fitLevel);

// Function to render fit option fields
function render_fit_option_fields($fields)
{
    if (!$fields)
        return;

    foreach ($fields as $field): ?>
        <h3 class="fit-option-fields-top__title"><?= esc_html($field['name']) ?></h3>
        <div class="fit-option-fields__main-merge">
            <?php if (!empty($field['option'])):
                foreach ($field['option'] as $option): ?>
                    <label class="fit-option-fields__item">
                        <input type="radio" name="<?= esc_attr($field['name']) ?>" value="<?= esc_attr($option['name']) ?>">
                        <span><?= esc_html($option['name']) ?></span>
                        <?= wp_get_attachment_image($option['feature_img_id'], 'thumbnail') ?>
                    </label>
                <?php endforeach;
            endif; ?>
        </div>
    <?php endforeach;
}

// Function to render swiper slides
function render_swiper_slides($chooseDetails, $commonDetails)
{
    foreach ($chooseDetails as $key => $fields): ?>
        <div class="swiper-slide">
            <div class="product-details__fit-option-fields fit-option-fields">
                <div class="fit-option-fields__top">
                    <h3 class="fit-option-fields-top__title"><?= esc_html($fields['name']) ?></h3>
                    <p class="fit-option-fields-top__description"><?= esc_html($commonDetails['description']) ?></p>
                </div>
                <?php
                // Render child options if not numeric key
                if (!is_numeric($key)) {
                    render_fit_option_fields($fields);
                } else { ?>
                    <div class="fit-option-fields__main">
                        <?php foreach ($fields['option'] as $field): ?>
                            <label class="fit-option-fields__item">
                                <input type="radio" name="<?= esc_attr($commonDetails['name']) ?>"
                                    value="<?= esc_attr($field['name']) ?>">
                                <span><?= esc_html($field['name']) ?></span>
                                <?= wp_get_attachment_image($field['feature_img_id'], 'thumbnail') ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php endforeach;
}

?>

<div class="product-details-custom-option__container">
    <div class="swiper swiper-product-details-custom-option">
        <h3 class="product-details-custom-option__title"><?= esc_html($chooseDetailsCommon['name']) ?></h3>
        <div class="swiper-wrapper">
            <?php render_swiper_slides($chooseDetails, $chooseDetailsCommon); ?>
        </div>
        <div class="navigation">
            <div class="navigation__prev">PREV</div>
            <div class="navigation__next">NEXT</div>
        </div>
    </div>
</div>