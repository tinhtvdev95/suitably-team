<?php

/** 
 * @package  GiaiPhapWeb_Theme
 * * Template for display product details custom option
 */

$optionFields = get_field('steps', 'customize_product_form');
$chooseDetails = $optionFields[3]['options'][0];
?>
<div class="product-details-custom-option__container">
    <div class="swiper swiper-product-details-custom-option">
        <h3 class="product-details-custom-option__title"><?= $chooseDetails['name'] ?></h3>
        <div class="swiper-wrapper">
            <?php
            foreach ($chooseDetails['children'] as $fields):
                ?>
                <div class="swiper-slide">
                    <div class="product-details__fit-option-fields fit-option-fields">
                        <input type="hidden" name="action" value="">
                        <div class="fit-option-fields__top">
                            <h3 class="fit-option-fields-top__title"><?= $fields['name'] ?></h3>
                            <p class="product-details-custom-option__description"><?= $chooseDetails['description'] ?></p>
                        </div>
                        <div class="fit-option-fields__main">
                            <?php foreach ($fields['option'] as $field) :
                            ?>
                            <div class="fit-option-fields__item">
                                <h4 class="fit-option-fields__title-item"><?= $field['name']?></h4>
                                <?= wp_get_attachment_image($field['feature_img_id']) ?>
                            </div>
                            <?php
                                endforeach ?>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
            ?>

        </div>

        <div class="navigation">
            <div class="navigation__prev">PREV</div>
            <div class="navigation__next">NEXT</div>
        </div>
    </div>
</div>