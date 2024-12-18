<?php

namespace gpweb\api;

class HandleSubmitProjectForm
{
    public function register()
    {
        add_action('wp_ajax_fit_customization', [$this, 'handleSubmitProduct']);
        add_action('wp_ajax_nopriv_fit_customization', [$this, 'handleSubmitProduct']);
        add_filter('woocommerce_get_item_data', [$this, 'displayCustomDataInCart'], 10, 2);
        add_action('woocommerce_checkout_create_order_line_item', [$this, 'saveCustomDataToOrder'], 10, 4);
    }

    /**
     * Handle data from AJAX and add products to the cart
     */
    public function handleSubmitProduct()
    {
        if (
            !check_ajax_referer('fit_customization', "fit_customization", false) &&
            !wp_verify_nonce($_POST["fit_customization_nonce"], 'fit_customization')
        ) {
            wp_send_json_error('Nonce không chính xác');
        }

        $formData = $_POST;
        $productId = $formData['product-id'];
        unset($formData['fit_customization_nonce'], $formData['_wp_http_referer'], $formData['product-id'], $formData['action']);
        $added = WC()->cart->add_to_cart($productId, 1, 0, [], $formData);

        if ($added) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Không thể thêm sản phẩm vào giỏ hàng');
        }
    }

    /**
     *Display custom data in the cart
     */
    public function displayCustomDataInCart($itemData, $formData)
    {
        $excludedKeys = ['product_id', 'variation_id', 'variation', 'quantity', 'data', 'line_tax', 'line_total', 'key', 'data_hash', 'line_subtotal', 'line_subtotal_tax'];

        if (!empty($formData)) {
            foreach ($formData as $key => $value) {
                if (!in_array($key, $excludedKeys)) {
                    $itemData[] = array(
                        'name' => ucfirst(str_replace('-', ' ', $key)),
                        'value' => wc_clean($value),
                    );
                }
            }
        }
        return $itemData;
    }

    /**
     *Save custom data to WooCommerce orders
     */
    public function saveCustomDataToOrder($item, $cartItemKey, $values, $order)
    {
        $excludedKeys = ['product_id', 'variation_id', 'variation', 'quantity', 'data', 'line_tax', 'line_total', 'key', 'data_hash', 'line_subtotal', 'line_subtotal_tax'];

        if (!empty($values)) {
            foreach ($values as $key => $value) {
                if (!in_array($key, $excludedKeys)) {
                    $item->add_meta_data(ucfirst(str_replace('-', ' ', $key)), $value, true);
                }
            }
        }
    }
}