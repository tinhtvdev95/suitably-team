<?php
namespace gpw\controller;

/**
 * Retrieves the most recent post from the "Events" category.
 * 
 * @return array An array of recent posts with the following structure
 */
class PostController
{
    public function getRecentPostByCategoryEvents()
    {
        $args = [
            'category_name' => 'events',
            'post_per_page' => 1,
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            $posts = [];

            while ($query->have_posts()) {
                $query->the_post();

                $posts = [
                    'title' => get_the_title(),
                    'excerpt' => get_the_excerpt(),
                    'link' => get_the_permalink(),
                    'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                ];
            }

            wp_reset_postdata();
            return $posts;
        } else {
            return [];
        }
    }
}