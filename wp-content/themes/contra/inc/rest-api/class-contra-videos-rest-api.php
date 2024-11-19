<?php

class Contra_Videos_Rest_Controller extends BTW_WP_REST_Post_Controller {

  const REST_AUTH_ERROR_NAME = 'btw_feed_rest_authentication_error';

  protected $post_type = 'video';

  protected $post_type_plural = 'videos';

  public function __construct(){

    global $btw_global_settings;

    $this->namespace = 'wp/v2';
    $this->rest_base = $btw_global_settings::rest_api_prefix_base() . '-videos';
  }


  
  public function get_item_data($post){

    $version =  $_GET['version'] ?? 'full';

    setup_postdata( $post );

    $return = get_base_api_post_data( $post );

    if( $version == 'full' ){

      $video_type = get_field( 'btw__article_fields__video_type', $post->ID );
      $video_url =  get_field( 'btw__article_fields__video', $post->ID );
      $video_player = get_field( 'btw__article_fields__custom_player', $post->ID, false, false );

      $return['post_content'] = apply_filters( 'the_content', $post->post_content );
      $return['video_url'] = $video_type != 'custom_player' && $video_url ? $video_url : '';
      $return['video_player'] = $video_type == 'custom_player' && $video_player ? $video_player : '';
      

      $embed_scripts = get_embed_scripts( $post );
      if( $embed_scripts ){
        $return['embed_scripts'] = $embed_scripts;
      }
    }

    return $return;

  }

}



add_action( 'rest_api_init', function () {
    
    $btw_videos = new Contra_Videos_Rest_Controller();
    $btw_videos->register_routes();

});
