<?php
/**
 * @package Giaiphapweb_Theme
 * * Template for the shortcode in header top bar.
 */
namespace gpweb\shortcodes;

use gpweb\shortcodes\BaseShortcode;
use gpweb\inc\base\CompanyInfo;
class TopBarSC extends BaseShortcode {
  function shortcodeCallback(array $atts, $content = null){
    ob_start();
    $cartUrl = wc_get_cart_url();
    $companyInfo = new CompanyInfo();
    $phone = $companyInfo->getPhone(true);
    $email = $companyInfo->getEmail(true);
    ?>
      <div class="header-info">
        <a href="<?= esc_url($cartUrl) ?>">
          <span class="material-symbols-outlined">shopping_cart</span>
          <span>Cart</span>
        </a>
        <?= $phone ?>
        <?= $email ?>
      </div>
    <?php
    return ob_get_clean();
  }
}