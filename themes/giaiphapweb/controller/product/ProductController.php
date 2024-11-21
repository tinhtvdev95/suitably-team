<?php
namespace gpw\controller;

class ProductController
{
    public function getFeaturedProducts()
    {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
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
                $product_id = get_the_ID();
                $product = wc_get_product($product_id);
                $product_image = get_the_post_thumbnail($product_id, 'medium');
                $product_price = $product->get_price_html();

                $products_data[] = array(
                    'id' => $product_id,
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'image' => $product_image,
                    'price' => $product_price,
                );
            }
        }

        wp_reset_postdata();
        return $products_data;
    }
}