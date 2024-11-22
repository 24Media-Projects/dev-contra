<?php

class BTW_Optimize{

  public function __construct(){

    add_filter( 'the_content', [ $this, 'add_lazyload_on_inline_attachments' ], 100 );
  }


  public function add_lazyload_on_inline_attachments( $content ){

    if( is_singular( get_supported_single_post_types() ) && in_the_loop() && is_main_query() && !btw_is_amp_endpoint() && !is_rest_api_request() ) {

    	global $wpdb, $post;

    	preg_match_all( "/<img[^>]+>?/", $content, $inline_attachments );

    	if( !$inline_attachments ) return $content;

    	foreach( $inline_attachments['0'] as $key => $inline_attachment ){

        $inline_attachment = preg_replace( '/class=\"([^"]+)\"/i', 'class="lazyload post_image_lightbox $1"', $inline_attachment );
        $inline_attachment = str_replace( [ 'srcset', 'src' ], [ 'data-srcset', 'data-src' ], $inline_attachment  );

        if( strpos( $inline_attachment, 'class' ) === false ){
          $inline_attachment = str_replace( '<img ', '<img class="lazyload post_image_lightbox" ', $inline_attachment );
        }

        $content = str_replace( $inline_attachments['0'][$key], $inline_attachment, $content );

    	}
    }


  	return $content;
  }

  public static function lazyload_to_embed_code_iframes( $embed_code ){

    if( is_admin() || strpos( $embed_code, '<iframe' ) !== 0 || is_rest_api_request() ) return $embed_code;

    $embed_code = str_replace( [ '<iframe ', 'src' ], [ '<iframe class="lazyload" ', 'data-src' ], $embed_code );

    return $embed_code;
  }



}


$btw_optimize = new BTW_Optimize();
