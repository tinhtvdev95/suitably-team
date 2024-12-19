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
        add_action('woocommerce_before_calculate_totals', [$this, 'customize_cart_item_price'], 10, 1);
        add_action('woocommerce_remove_cart_item', [$this, 'deleteUploadedImageOnRemoveCartItem'], 10, 2);
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
        unset($formData['fit_customization_nonce'], $formData['_wp_http_referer'], $formData['product-id'], $formData['action'], $formData['your-pics']);

        // dd($_FILES['your-pics']);

        if (!empty($_FILES['your-pics'])) {
            $uploadedImages = [];
            if (is_array($_FILES['your-pics']['name'])) {
            foreach ($_FILES['your-pics']['name'] as $key => $value) {
                if ($_FILES['your-pics']['name'][$key]) {
                $file = [
                    'name'     => $_FILES['your-pics']['name'][$key],
                    'type'     => $_FILES['your-pics']['type'][$key],
                    'tmp_name' => $_FILES['your-pics']['tmp_name'][$key],
                    'error'    => $_FILES['your-pics']['error'][$key],
                    'size'     => $_FILES['your-pics']['size'][$key]
                ];
                $upload = wp_handle_upload($file, ['test_form' => false]);
                if (isset($upload['url'])) {
                    $uploadedImages[] = $upload['url'];
                }
                }
            }
            } else {
            $file = [
                'name'     => $_FILES['your-pics']['name'],
                'type'     => $_FILES['your-pics']['type'],
                'tmp_name' => $_FILES['your-pics']['tmp_name'],
                'error'    => $_FILES['your-pics']['error'],
                'size'     => $_FILES['your-pics']['size']
            ];
            $upload = wp_handle_upload($file, ['test_form' => false]);
            if (isset($upload['url'])) {
                $uploadedImages[] = $upload['url'];
            }
            }
            if (!empty($uploadedImages)) {
            $formData['your-pics'] = $uploadedImages;
            }
        }
        
        $totalPrice = json_decode(stripslashes($formData['total_price']), true);
        $additionalFees = isset($totalPrice['additional']) ? $totalPrice['additional'] : [];

        $additionalTotal = array_sum($additionalFees);

        $product = wc_get_product($productId);
        $productPrice = $product->get_price();

        $newPrice = $productPrice + $additionalTotal;

        $added = WC()->cart->add_to_cart($productId, 1, 0, [], array_merge($formData, ['custom_price' => $newPrice]));
        if ($added) {
            wp_send_json_success(['redirect_url' => wc_get_cart_url()]);
        } else {
            wp_send_json_error('Không thể thêm sản phẩm vào giỏ hàng');
        }
    }

    function customize_cart_item_price($cart)
    {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart->get_cart() as $cartItem) {
            if (isset($cartItem['custom_price'])) {
                $cartItem['data']->set_price($cartItem['custom_price']);
            }
        }
    }

    /**
     * Display custom data in the cart
     */
    public function displayCustomDataInCart($itemData, $cartItem)
    {
        dump($cartItem);
        $totalPrice = json_decode(stripslashes($cartItem['total_price']), true);

        $excludedKeys = ['product_id', 'variation_id', 'variation', 'quantity', 'data', 'line_tax', 'line_total', 'key', 'data_hash', 'line_subtotal', 'line_subtotal_tax', 'custom_price'];

        $displayedFields = [];


        if (!empty($totalPrice['additional'])) {
            foreach ($totalPrice['additional'] as $key => $value) {
                $valueLabel = ucfirst(str_replace('-', ' ', $key));

                $formDataValue = isset($cartItem[$key]) ? $cartItem[$key] : '';

                if (in_array($valueLabel, $displayedFields) || in_array($key, $excludedKeys)) {
                    continue;
                }

                if (isset($totalPrice['additional'][$key])) {
                    $itemData[] = array(
                        'name' => $valueLabel,
                        'value' => "{$formDataValue} (" . wc_price($totalPrice['additional'][$key]) . ")",
                    );
                } else {
                    $itemData[] = array(
                        'name' => $valueLabel,
                        'value' => "{$formDataValue}",
                    );
                }

                $displayedFields[] = $valueLabel;
            }
        }

        if (!empty($cartItem)) {
            foreach ($cartItem as $key => $value) {
                if (!in_array($key, ['fit_customization_nonce', '_wp_http_referer', 'product-id', 'action', 'total_price']) && !in_array(ucfirst(str_replace('-', ' ', $key)), $displayedFields) && !in_array($key, $excludedKeys)) {
                    $itemData[] = array(
                        'name' => ucfirst(str_replace('-', ' ', $key)),
                        'value' => wc_clean($value),
                    );
                    $displayedFields[] = ucfirst(str_replace('-', ' ', $key));
                }
            }
        }

        return $itemData;
    }
    /**
     * Save custom data to WooCommerce orders
     */
    public function saveCustomDataToOrder($item, $cartItemKey, $values, $order)
    {
        $excludedKeys = ['product_id', 'variation_id', 'variation', 'quantity', 'data', 'line_tax', 'line_total', 'key', 'data_hash', 'line_subtotal', 'line_subtotal_tax', 'custom_price'];

        if (!empty($values)) {
            foreach ($values as $key => $value) {
                if (!in_array($key, $excludedKeys)) {
                    $item->add_meta_data(ucfirst(str_replace('-', ' ', $key)), $value, true);
                }
            }
        }
    }
    /**
     * Deletes the uploaded image when a cart item is removed.
     *
     * This function is triggered when a cart item is removed and checks if the cart item
     * contains an uploaded image. If an image is found, it deletes the image from the server.
     *
     * @param string $cartItemKey The key of the cart item being removed.
     * @param WC_Cart $cart The WooCommerce cart object.
     */
    public function deleteUploadedImageOnRemoveCartItem($cartItemKey, $cart) {
        $cartItem = $cart->get_cart_item($cartItemKey);
        if(isset($cartItem['your-pics'])) {
            $imgUrl = $cartItem['your-pics'];
            $uploadDir = wp_get_upload_dir();
            $baseDir = $uploadDir['basedir'];
            $baseUrl = $uploadDir['baseurl'];
            if(strpos($imgUrl, $baseUrl) === 0) {
                $uploadedImagePath = str_replace($baseUrl, $baseDir, $imgUrl);
                if(file_exists($uploadedImagePath)) {
                    unlink($uploadedImagePath);
                }
            }
        }
    }
}