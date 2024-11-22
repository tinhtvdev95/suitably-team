<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for contact info section.
 */
// use gpweb\inc\base\CompanyInfo;
// $companyInfo = new CompanyInfo();
?>
<section class="contact-info">
  <div class="section__inner">
    <div class="contact-info__empty-space"></div>
    <main class="contact-info__main">
      <div class="contact-info__img">
        <?= wp_get_attachment_image(30, 'full', false ) ?>
      </div>
      <div class="contact-info__content">
        <h2 class="contact-info__title">Contact info</h2>
        <p class="contact-info__sub-title">Have a question? Our team are always ready to help. Feel free get in touch anytime.</p>
      </div>
    </main>
    <div class="contact-info__socials">
    </div>
  </div>
</section>