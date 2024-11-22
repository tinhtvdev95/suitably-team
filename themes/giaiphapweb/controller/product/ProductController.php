<?php
namespace gpw\controller;

class ProductController
{
    /**
     * Get products based on custom query arguments.
     *
     * @param array $queryArgs Custom query arguments for WP_Query.
     * @return array List of products with essential data.
     */
    private function getProducts(array $queryArgs)
    {
        $query = new \WP_Query($queryArgs);
        $products_data = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $productId = get_the_ID();
                $product = wc_get_product($productId);
                $products_data[] = array(
                    'id'    => $productId,
                    'title' => get_the_title(),
                    'link'  => get_permalink(),
                    'image' => get_the_post_thumbnail($productId, 'medium'),
                    'price' => $product ? $product->get_price_html() : '',
                );
            }
        }

        wp_reset_postdata();
        return $products_data;
    }

    /**
     * Get featured products.
     *
     * @return array List of featured products.
     */
    public function getFeaturedProducts()
    {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured',
                    'operator' => 'IN',
                ),
            ),
        );

        return $this->getProducts($args);
    }

    /**
     * Get new products sorted by latest date.
     *
     * @return array List of new products.
     */
    public function getNewProducts()
    {
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => 4,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        return $this->getProducts($args);
    }
}