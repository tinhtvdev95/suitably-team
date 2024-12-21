<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details Take Measurements section.
 */
$measurementsSteps = get_field('step', 'take_measurements');
$stepCounter = 0;
$stepNavHtml = '';
$stepContent = '';
foreach ($measurementsSteps as $step):
  extract($step);
  $stepSlug = sanitize_title($name);
  ob_start();
  ?>
  <a class="steps-nav__item<?= $stepCounter == 0 ? ' steps-nav__item--active' : '' ?>" href="#<?= esc_attr($stepSlug) ?>"><?= esc_html($name) ?></a>
  <?php
  $stepNavHtml .= ob_get_clean();

  ob_start();
  ?>

  <div class="steps-content__item swiper-slide" data-hash="<?= esc_attr($stepSlug) ?>">
    <strong class="steps-content__item-title"><?= esc_html($name) ?></strong>
    <p class="steps-content__item-desc"><?= esc_html($description) ?></p>
    <div class="steps-content__item-fields" style="--fields-count: <?= esc_attr(count($input_fields)) ?>;">
      <?php foreach ($input_fields as $field):
        $fieldName = sanitize_title($field['name']);
        ?>
        <div class="field-group">
          <input type="text" name="<?= esc_attr($fieldName) ?>" placeholder="<?= esc_attr($field['name']) ?>"
            class="field-group__input">
          <?php if ($field['unit'] != 'none'): ?>
            <span class="field-group__unit"><?= esc_html($field['unit']) ?></span>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>

  <?php
  $stepContent .= ob_get_clean();
  $stepCounter++;
endforeach;

$stepNavHtml .= '<a class="steps-nav__item" href="#your-pics">Your pictures</a>';
$stepContent .= '<div class="steps-content__item swiper-slide" data-hash="your-pics">
  <strong class="steps-content__item-title">Your pictures</strong>
  <p class="steps-content__item-desc">Please upload your pictures for us to better understand your body shape.</p>
  <div class="steps-content__item-fields">
    <div class="field-group">
      <input type="file" name="your-pics[]" class="field-group__input" accept="image/*" multiple>
    </div>
  </div>
  </div>';
?>
<div class="take-measurements__steps-nav"><?= $stepNavHtml ?></div>
<div class="take-measurements__steps-content">
  <div class="swiper">
    <div class="swiper-wrapper"><?= $stepContent ?></div>
    <div class="swiper-navigation-buttons">
      <button class="take-measurements__btn take-measurements__btn--prev">Prev</button>
      <button class="take-measurements__btn take-measurements__btn--next">Next</button>
    </div>
  </div>
</div>