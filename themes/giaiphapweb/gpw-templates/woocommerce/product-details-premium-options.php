<?php
/**
 * @package GiaiPhapWeb_Theme
 * * Template for display product details Premium Options.
 */

 $isActive = $args['isActive'] ?? false;
$options = $args['options'] ?? [];
$parentSlug = $args['parentSlug'] ?? '';
if (!$options || empty($options))
  return;

$classes = ['fabric-options', 'customize-popup__step-options'];
if(count($options) >= MAX_OPTIONS_SIZE) {
  $classes[] = 'customize-popup__step-options--flex-start';
}
if($isActive) {
  $classes[] = 'fabric-options--active';
}
?>
<div class="<?= esc_attr(implode(' ', $classes)) ?>" id="<?= esc_attr($parentSlug) ?>">

  <?php foreach ($options as $option):
    if (!$option) {
      continue;
    }
    $name = $option['name'];
    $slug = sanitize_title($name);
    $imgID = $option['feature_img_id'];
    $price = $option['price'] ?: 0;
    ?>
    <label class="step-option">
      <input type="radio" name="<?= esc_attr($parentSlug) ?>" value="<?= esc_attr($name) ?>"
        data-slug="<?= esc_attr($slug) ?>" data-price="<?= esc_attr($price) ?>">
      <span class="step-option__name"><?= esc_html($name) ?></span>
      <?= wp_get_attachment_image($imgID, 'large_medium', false, ['class' => 'step-option__feature-img']) ?>
      <div class="step-option__meta">
        <span class="step-option__price"><?= $price || $price != 0 ? $price : '' ?></span>
        <span class="step-option__state material-symbols-outlined">check</span>
      </div>
    </label>

  <?php endforeach; ?>

</div>