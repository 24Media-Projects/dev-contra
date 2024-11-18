<?php

class News247_Infinite_Magazine_Posts_Rest_Controller extends News247_Infinite_Posts_Rest_Controller {

  public function __construct(){
    
    global $btw_global_settings;

    parent::__construct();

    $this->rest_base = $btw_global_settings::rest_api_prefix_base() . '-infinite-magazine-posts';

  }


  public function get_item_data( $post ){

    setup_postdata( $post );

    $return = get_base_api_post_data( $post );

    $post_primary_tag = btw_get_post_primary_tag( $post );
    $post_primary_tag->name = remove_punctuation( $post_primary_tag->name );

    $post_primary_category = btw_get_post_primary_category($post);
    $return['post_primary_category'] = btw_get_primary_term_anchor_html( $post_primary_category, true );

    $return['post_primary_tag'] = btw_get_primary_term_anchor_html( $post_primary_tag, true );
    $return['post_date'] = get_the_date( 'd.m.Y', $post );
    $return['post_title'] = remove_punctuation( $return['post_title'] );
    /**
     * Post author display name is on uppercase, so add / remove filter to use remove_punctuation
     */
    add_filter('btw/post_author/display_name', 'remove_punctuation');

    $return['post_author_html'] = btw_return_post_author_html($post, false);

    remove_filter('btw/post_author/display_name', 'remove_punctuation');
    
    $return['post_id'] = $post->ID;

    wp_reset_postdata();

    return $return;

  }
}

add_action( 'rest_api_init', function () {
    $btw_posts = new News247_Infinite_Magazine_Posts_Rest_Controller();
    $btw_posts->register_routes();
});



 
