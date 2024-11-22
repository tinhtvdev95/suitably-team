<?php
namespace gpw\controller;

class ProductController
{
    public function getFeaturedProducts()
    {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 4,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                    'operator' => 'IN',
                ),
            ),
        );

        $query = new \WP_Query($args);

        $products_data = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $productId = get_the_ID();
                $product = wc_get_product($productId);
                $productImage = get_the_post_thumbnail($productId, 'medium');
                $price = $product ? $product->get_price_html() : '';

                $products_data[] = array(
                    'id' => $productId,
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'image' => $productImage,
                    'price' => $price,
                );
            }
        }

        wp_reset_postdata();
        return $products_data;
    }
}