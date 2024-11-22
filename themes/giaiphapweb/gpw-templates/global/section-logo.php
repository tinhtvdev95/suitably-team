<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for logo section.
 */
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