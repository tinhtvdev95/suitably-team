<?php
/**
 * @package GiapPhapWeb_Theme
 ** The Register class handles the registration of scripts, styles, and shortcodes for the theme.
 */

namespace gpweb\inc\base;

class Register extends BaseController
{
  /**
   ** An array of shortcodes.
   * @var array
   */
  protected $shortcodes;

  /**
   * Registers the necessary actions and filters.
   */
  public function register()
  {
    add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    // Add AOS init script in the header
    add_action('wp_footer', function () {
      echo '<script> AOS.init(); </script>';
    });
    $this->setShortcodes();
    add_action('init', [$this, 'registerShortcodes']);
  }

  /**
   * Sets the shortcodes.
   */
  protected function setShortcodes()
  {
    $this->shortcodes = [
      new \gpweb\shortcodes\ImgById('img_by_id'),
      new \gpweb\shortcodes\LinkTo('link_to'),
      new \gpweb\shortcodes\TopBarSC('top_bar_sc'),
    ];
  }

  /**
   * Enqueues the necessary scripts and styles.
   */
  public function enqueue()
  {
    $this->enqueueScript('aos', null, '', [], false);

    $this->enqueueStyle('theme-init', null);
    $this->enqueueStyle('gpw-footer', null);
    $this->enqueueStyle('gpw-header', null);
    $this->enqueueStyle('google-symbols', null, 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200');

    // * Enqueue swiper for page that needs it
    if (is_front_page()) {
      $this->enqueueScript('swiper');
      $this->enqueueStyle('swiper');
    }

    if (is_front_page()) {
      $this->enqueueStyle('gpw-homepage');
    }
  }

  /**
   ** Enqueue single script 
   */
  protected function enqueueScript(string $script_name, string $version = null, string $url = '', array $dependencies = [], bool $in_footer = true)
  {
    $url = $url === '' ? "{$this->theme_url}/assets/js/{$script_name}.min.js" : $url;
    wp_enqueue_script($script_name, $url, $dependencies, $version, $in_footer);
  }

  /**
   ** Enqueue single style
   */
  public function enqueueStyle(string $style_name, string $version = null, string $url = '', array $dependencies = [], string $media = 'all')
  {
    $url = $url === '' ? "{$this->theme_url}/assets/css/{$style_name}.min.css" : $url;
    wp_enqueue_style($style_name, $url, $dependencies, $version, $media);
  }

  /**
   * * Registers the shortcodes.
   * * Loops through the shortcodes array and registers each shortcode.
   */
  public function registerShortcodes()
  {
    foreach ($this->shortcodes as $shortcode) {
      add_shortcode($shortcode->getShortcodeName(), [$shortcode, 'shortcodeCallback']);
    }
  }
}