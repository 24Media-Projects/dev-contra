<?php

class News247_Skitsa_Rest_Controller extends BTW_WP_REST_Post_Controller {

  const REST_AUTH_ERROR_NAME = 'btw_feed_rest_authentication_error';

  protected $post_type = 'skitsa';

  protected $post_type_plural = 'skitsa';

  public function __construct(){

    global $btw_global_settings;

    $this->namespace = 'wp/v2';
    $this->rest_base = $btw_global_settings::rest_api_prefix_base() . '-skitsa';
  }



}



add_action( 'rest_api_init', function () {
    
    $btw_videos = new News247_Skitsa_Rest_Controller();
    $btw_videos->register_routes();

});
