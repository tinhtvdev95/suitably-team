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
                    'id' => $productId,
                    'title' => get_the_title(),
                    'link' => get_permalink(),
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
            'post_type' => 'product',
            'posts_per_page' => 4,
            'orderby' => 'date',
            'order' => 'DESC',
        );

        return $this->getProducts($args);
    }

    /**
     * Get related products for a given product ID.
     *
     * This method retrieves a list of related products based on the WooCommerce algorithm.
     * It includes product ID, title, link, image, and price.
     *
     * @param int $id The ID of the product to find related products for.
     * 
     * @return array[] List of related products with the following structure:
     * [
     *     'id' => int, // The ID of the related product.
     *     'title' => string, // The title of the related product.
     *     'link' => string, // The permalink to the related product.
     *     'image' => string, // The HTML of the product's thumbnail image.
     *     'price' => string, // The HTML representation of the product price.
     * ]
     */
    public function getRelatedProducts($id)
    {
        $relatedLimit = 4;
        $relatedIds = wc_get_related_products($id, $relatedLimit);
        $products_data = [];

        foreach ($relatedIds as $relatedId) {
            $relatedProduct = wc_get_product($relatedId);
            if (!$relatedProduct) {
                continue;
            }
            $products_data[] = array(
                'id' => $relatedId,
                'title' => get_the_title($relatedId),
                'link' => get_permalink($relatedId),
                'image' => get_the_post_thumbnail($relatedId, 'medium'),
                'price' => $relatedProduct->get_price_html(),
            );
        }

        return $products_data;
    }
}