<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for logo section.
 */
$pageID = get_the_ID();
$pageSlug = get_post_field('post_name', $pageID);
$classes = ['gallery-logo', $pageSlug];
$galleryLogo = get_field('gallery_logo', $pageID);
?>
<section class="<?= esc_attr(implode(' ', $classes)) ?>">
  <div class="section__inner full">
    <div class="gallery-logo__bg">
      <div class="gallery-logo__main">
        <?php
        foreach ($galleryLogo as $logo) {
          ?>
          <div class="gallery-logo__item">
            <img src="<?= $logo ?>">
          </div>
          <?php
        }
        ?>
      </div>
    </div>
  </div>
</section>