<?php
namespace gpweb\shortcodes;

/**
 * * The ImgById class is a shortcode handler for displaying images by their ID.
 * ! Only use within wordpress editor
 */
class ImgById extends BaseShortcode
{
  /**
   * * Handles the callback for the [img_by_id] shortcode.
   *
   * @param array $atts The shortcode attributes.
   * @param string|null $content The shortcode content.
   * @return string The HTML markup for the image.
   */
  function shortcodeCallback($atts, $content = null)
  {
    $sizes = ['thumbnail', 'medium', 'medium_large', 'large', 'full'];
    // size: thumbnail, medium, medium_large, large, full
    extract(shortcode_atts(array('img_id' => null, 'size' => 'full', 'alt' => '', 'class' => '', 'is_icon' => false), $atts));

    if( !is_numeric($img_id) ) {
      return "$img_id is not a valid image ID, please check again!";
    }

    if( !in_array($size, $sizes) ) {
      return "$size is not a valid image size, only accept thumbnail, medium, medium_large, large, full!";
    }

    if (wp_attachment_is_image($img_id)) {
      return wp_get_attachment_image($img_id, $size, $is_icon, ['class' => $class, 'alt' => $alt]);
    } else {
      return "$img_id is not a valid image ID, please check again!";
    }
  }
}