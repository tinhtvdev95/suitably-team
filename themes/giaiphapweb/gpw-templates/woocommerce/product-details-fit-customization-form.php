<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template for display product details Fit and Customization form.
 */
global $product;
?>
<form class="product-details__fit-customization" method="POST">
  <?php wp_nonce_field('fit_customization', 'fit_customization_nonce') ?>
  <input type="hidden" name="action" value="fit_customization">
  <h3 class="fit-customization__title">Fitting and Customization</h3>
</form>