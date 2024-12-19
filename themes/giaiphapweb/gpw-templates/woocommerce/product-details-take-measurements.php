<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details Take Measurements section.
 */
$measurementsSteps = get_field('step', 'take_measurements');
// dump($measurementsSteps);
foreach( $measurementsSteps as $step ) :
  extract($step);
  $stepSlug = sanitize_title($name);
  ob_start();
  ?>
    <li class="steps-nav__item">
      <a href="javascript:void(0);" data-target="<?= esc_attr($stepSlug) ?>"><?= esc_html($name) ?></a>
    </li>
  <?php
  $stepNavHtml .= ob_get_clean();

  ob_start();
  ?>

  <div class="steps-content__item swiper-slide" id="<?= esc_attr($stepSlug) ?>">
    <strong class="steps-content__item-title"><?= esc_html($name) ?></strong>
    <p class="steps-content__item-desc"><?= esc_html($description) ?></p>
    <div class="steps-content__item-fields">
      <?php foreach ($input_fields as $field) :
        $fieldName = sanitize_title($field['name']);
      ?>
        <div class="field-group">
          <input type="text" name="<?= esc_attr($fieldName) ?>" placeholder="<?= esc_attr($field['name']) ?>" class="field-group__input">
          <?php if($field['unit'] != 'none') : ?>
            <span class="field-group__unit"><?= esc_html($field['unit']) ?></span>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>

  <?php
  $stepContent .= ob_get_clean();
endforeach;
$stepNavHtml .= '<li class="steps-nav__item"><a href="javascript:void(0);" data-target="your-pics">Your pictures</a></li>';
$stepContent .= '<div class="steps-content__item swiper-slide" id="your-pics">
  <strong class="steps-content__item-title">Your pictures</strong>
  <p class="steps-content__item-desc">Please upload your pictures for us to better understand your body shape.</p>
  <div class="steps-content__item-fields">
    <div class="field-group">
      <input type="file" name="your-pics[]" class="field-group__input" accept="image/*" multiple>
    </div>
  </div>
  </div>';
?>
<ul class="take-measurements__steps-nav"><?= $stepNavHtml ?></ul>
<div class="take-measurements__steps-content">
  <div class="swiper">
    <div class="swiper-wrapper"><?= $stepContent ?></div>
    <div class="swiper-navigation-buttons">
      <button class="take-measurements__btn take-measurements__btn--prev">Prev</button>
      <button class="take-measurements__btn take-measurements__btn--next">Next</button>
    </div>
  </div>
</div>