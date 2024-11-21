<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for hero banner section.
 */
$pageID = get_the_ID();
$pageSlug = get_post_field('post_name', $pageID);
$classes = ['hero-banner', $pageSlug];
$heroBannerBgImgID = get_field('hero_banner_image', $pageID);
$title = $args['title'] ?? get_the_title();
$desc = $args['desc'] ?? get_the_content();
$button1 = $args['button_1'] ?? false;
$button2 = $args['button_2'] ?? false;
?>
<section class="<?= esc_attr( implode(' ', $classes) ) ?>">
  <div class="section__inner full">
    <div class="hero-banner__bg">
      <?= wp_get_attachment_image($heroBannerBgImgID, 'full', false, [ 'class' => 'hero-banner__bg-img' ]) ?>
    </div>
    <div class="hero-banner__content">
      <h2 class="hero-banner__title"><?= esc_html($title) ?></h2>
      <div class="hero-banner__desc"><?= wp_kses_post($desc) ?></div>
      <div class="hero-banner__buttons">
        <?php if($button1): ?>
          <a href="<?= esc_url($button1['url']) ?>" class="gpw-button gpw-button--outline" role="button" aria-roledescription="<?= esc_attr($button1['text']) ?>"><?= esc_html($button1['text']) ?></a>
        <?php endif; ?>
        <?php if($button2): ?>
          <a href="<?= esc_url($button2['url']) ?>" class="gpw-button gpw-button--outline" role="button" aria-roledescription="<?= esc_attr($button2['text']) ?>"><?= esc_html($button2['text']) ?></a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>