<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for the why us section.
 */
$backgroundImgID = 125;
?>
<section class="why-us">
  <div class="why-us__bg">
    <?= wp_get_attachment_image($backgroundImgID, 'full', false, ['class' => 'why-us__bg-img']) ?></div>
  <div class="section__inner">
    <div class="why-us__content">
      <div class="why-us__reasons">
        <h2 class="why-us__title">Why suitably</h2>
        <ul class="why-us__reason-list">
          <li class="reason__item">Lorem ipsum dolor sit amet consectetur, adipisicing elit.</li>
          <li class="reason__item">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos mollitia esse nisi quis
            commodi quasi!</li>
          <li class="reason__item">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Maxime non incidunt ut
            cumque a laboriosam commodi impedit!</li>
          <li class="reason__item">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Officiis nam vitae cumque.
          </li>
          <li class="reason__item">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod ab deserunt
            repudiandae modi deleniti iste sint.</li>
          <li class="reason__item">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Mollitia ipsam alias
            tempora eligendi, tenetur deleniti?</li>
          <li class="reason__item">Lorem ipsum dolor sit amet consectetur adipisicing.</li>
        </ul>
        <?php get_template_part('gpw-templates/global/buttons', null, [
          'text' => 'explode the range', 
          'type' => 'gradient', 
          'url' => get_permalink( wc_get_page_id( 'shop' ) )]) ?>
      </div>
      <div class="why-us__features">
        <div class="feature__item">
          <?= wp_get_attachment_image(28, 'full', true, ['class' => 'feature__item-img']) ?>
          <p class="feature__item-text">Simple, Online Ordering</p>
        </div>
        <div class="feature__item">
          <?= wp_get_attachment_image(26, 'full', true, ['class' => 'feature__item-img']) ?>
          <p class="feature__item-text">Easy Measuring Options</p>
        </div>
        <div class="feature__item">
          <?= wp_get_attachment_image(27, 'full', true, ['class' => 'feature__item-img']) ?>
          <p class="feature__item-text">Careful Construction</p>
        </div>
        <div class="feature__item">
          <?= wp_get_attachment_image(25, 'full', true, ['class' => 'feature__item-img']) ?>
          <p class="feature__item-text">Custom Perfection</p>
        </div>
      </div>
    </div>
  </div>
</section>