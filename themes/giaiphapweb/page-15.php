<?php
/**
 * @package Giaiphapweb_Theme
 * * Template Name: Template for home page.
 */
get_header();
?>

<div id="content">
  <?php get_template_part('gpw-templates/global/hero-banner', null, [
    'title' => 'Look your best when it matters most',
    'desc' => 'Australia’s premium custom suiting supplier. Suitably combines traditional handmade tailoring, quality materials, a modern approach, and a customer-first focus. Experience the Suitably difference online or book a consultation.',
    'button_1' => [
      'text' => 'Explode the range',
    ],
    'button_2' => [
      'text' => 'Book a consultation',
    ],
  ]); ?>
  <?php get_template_part('gpw-templates/global/section-logo'); ?>
  <?php get_template_part('gpw-templates/global/section-featured-products'); ?>
  <?php get_template_part('gpw-templates/homepage/why-us-section'); ?>
  <?php get_template_part('gpw-templates/global/section-new-products'); ?>
  <?php get_template_part('gpw-templates/global/section-google-map-review'); ?>
  <?php get_template_part('gpw-templates/global/section-contact-info'); ?>
</div>

<?php
get_footer();