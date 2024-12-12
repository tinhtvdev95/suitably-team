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
    private function getProducts(array $queryArgs, $subCategoryName = '')
    {
        $query = new \WP_Query($queryArgs);
        $products_data = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $productId = get_the_ID();
                $product = wc_get_product($productId);
                $products_data[] = [
                    'category' => $subCategoryName,
                    'id' => $productId,
                    'title' => get_the_title(),
                    'link' => get_permalink(),
                    'image' => get_the_post_thumbnail($productId, 'medium'),
                    'price' => $product ? $product->get_price_html() : '',
                ];
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
     * Get 4 lapel to category products
     * 
     * @return mixed
     */
    public function getProductsFromSubcategories($gender)
    {
        if (empty($gender) || !is_string($gender)) {
            return [];
        }

        $parentCategory = get_term_by('slug', $gender, 'product_cat');
        if (!$parentCategory) {
            return [];
        }

        $lapelCategorySlug = ($gender === 'male') ? 'lapel-style-male' : 'lapel-style-female';
        $lapelCategory = get_term_by('slug', $lapelCategorySlug, 'product_cat');
        if (!$lapelCategory || $lapelCategory->parent !== $parentCategory->term_id) {
            return [];
        }

        $subCategorySlugs = ($gender === 'male')
            ? [
                'the-notch-lapel-male',
                'the-shawl-lapel-male',
                'the-peak-lapel-male',
                'the-tuxedo-lapel-male',
            ]
            : [
                'the-notch-lapel-female',
                'the-shawl-lapel-female',
                'the-peak-lapel-female',
                'the-tuxedo-lapel-female',
            ];


        $subCategories = get_terms(
            [
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'parent' => $lapelCategory->term_id,
                'slug' => $subCategorySlugs,
            ]
        );

        $products = [];

        if (!empty($subCategories) && !is_wp_error($subCategories)) {
            foreach ($subCategories as $subcategory) {
                $queryArgs = [
                    'post_type' => 'product',
                    'posts_per_page' => 1,
                    'post_status' => 'publish',
                    'tax_query' => [
                        [
                            'taxonomy' => 'product_cat',
                            'field' => 'term_id',
                            'terms' => $subcategory->term_id,
                            'include_children' => false,
                        ],
                    ],
                    'orderby' => 'date',
                    'order' => 'ASC',
                ];

                // dump($subcategory->name);

                $subcategoryProducts = $this->getProducts($queryArgs, $subcategory->name);
                $products = array_merge($products, $subcategoryProducts);
            }
        }

        return $products;
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

    public function filterMatchingOptions($optionFields, $mergeArray)
    {
        $matchingOptions = [];
        $keysToRemove = [];

        foreach ($optionFields as $key => $item) {
            if (strpos($item['merge_array'], $mergeArray) !== false) {
                $matchingOptions[] = $item;
                $keysToRemove[] = $key;
            }
        }

        return [$matchingOptions, $keysToRemove];
    }

    public function removeKeysFromArray($array, $keysToRemove)
    {
        foreach (array_reverse($keysToRemove) as $key) {
            unset($array[$key]);
        }
        return $array;
    }

    public function insertIntoArray($array, $position, $newElements)
    {
        return array_merge(
            array_slice($array, 0, $position),
            $newElements,
            array_slice($array, $position)
        );
    }

    public function processProductOptions($optionFields, $mergeArray)
    {
        $newNameArray = str_replace(' ', '_', $mergeArray);

        [$fitLevelArrays, $keysToRemove] = $this->filterMatchingOptions($optionFields, $mergeArray);
        $optionFields = $this->removeKeysFromArray($optionFields, $keysToRemove);
        if (!empty($fitLevelArrays)) {
            $firstKey = reset($keysToRemove);
            $optionFields = $this->insertIntoArray(
                $optionFields,
                $firstKey,
                [$newNameArray => $fitLevelArrays]
            );
        }

        return $optionFields;
    }
}