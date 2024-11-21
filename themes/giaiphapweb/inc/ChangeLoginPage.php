<?php
/**
 * @package GiapPhapWeb_Theme
 ** Change appearance of Wordpress login page
 */
namespace gpweb\inc;
use gpweb\inc\base\BaseController;

class ChangeLoginPage extends BaseController {
  /**
   * Register the necessary actions and filters
   */
  public function register() {
    add_action('login_enqueue_scripts', [$this, 'changeLogo']);
    add_filter('login_headerurl', [$this, 'changeLogoUrl']);
  }

  /**
   * Change the logo on the login page
   */
  public function changeLogo() {
    ob_start();
    ?>
    <style type="text/css">
      #login h1 a {
        background-image: url(<?= $this->theme_url  ?>/assets/images/giaiphapweb-logo.png);
        background-size: contain;
        width: 300px;
        aspect-ratio: 50/13;
      }
    </style>
    <?php
    echo ob_get_clean();
  }

  /**
   * Change the logo URL on the login page
   * @return string The new logo URL
   */
  public function changeLogoUrl() {
    return 'https://giaiphapweb.vn';
  }
}