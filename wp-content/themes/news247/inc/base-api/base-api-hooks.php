<?php

/**
 * Get posts items from wp_query
 * 
 * @param array, $post_items
 * @param string, $group_template
 * 
 * @return array
 */
add_filter( 'btw/base_api/feeds/post_items', function( $post_items, $group_template ){

    if( $group_template == 'opinions_carousel' ){

        $post_items = [];

        $post_query = new WP_Query([
            'post_type'      => 'post',
            'posts_per_page' => '12',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_status'    => 'publish',
            'tax_query'      => [
                [
                    'field' => 'slug',
                    'terms' => 'gnomes',
                    'taxonomy' => 'category',
                ]
            ]
        ]);

        while( $post_query->have_posts() ): $post_query->the_post();
            global $post;

            $post_items[] = get_base_api_post_data( $post );

        endwhile;
        wp_reset_query();

    }

    return $post_items;


}, 10, 2 );