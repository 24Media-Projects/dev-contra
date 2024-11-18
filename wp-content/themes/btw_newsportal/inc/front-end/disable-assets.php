<?php

  add_action( 'init', function(){

    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

    add_filter( 'tiny_mce_plugins', function( $plugins ){
      if( is_array( $plugins ) ){
        return array_diff( $plugins, array( 'wpemoji' ) );
      }else{
        return array();
      }
    });

    add_filter( 'wp_resource_hints', function( $urls, $relation_type ){
      if( $relation_type != 'dns-prefetch' ) return $urls;

      $emoji_svg_url = apply_filters( 'emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/' );
      $urls = array_diff( $urls, array( $emoji_svg_url ) );

      return $urls;

    }, 10, 2 );



  });



  add_action( 'wp_enqueue_scripts', function(){

    wp_deregister_script( 'wp-embed' );
    wp_dequeue_script( 'wp-embed' );

    wp_deregister_style( 'wpml-menu-item-0' );
    wp_dequeue_style( 'wpml-menu-item-0' );

    wp_deregister_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library' );


    if( !is_user_logged_in() ){
      wp_deregister_style( 'dashicons' );
      wp_dequeue_style( 'dashicons' );
    }

  }, 99 );

?>
