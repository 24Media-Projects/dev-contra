<?php
  /**
   * Add temp styles
   * 
   * @see wp_enqueue_scripts
   */
  add_action( 'wp_enqueue_scripts', function(){

    $time = strtotime( 'now' );
    if( btw_is_amp_endpoint() ) return;

    wp_register_style( 'tempstyles', get_stylesheet_directory_uri() . '/style.css', [], $time );
    wp_enqueue_style( 'tempstyles' );



     


  }, 5 );

  function get_localize_script(){

    $args = [
      'keen_slider_icons' => [
        'close' => '<svg class="icon_close"><use xlink:href="#icon-close"></use></svg>',
        'prev'  => '<svg class="icon_arrow"><use xlink:href="#icon-slide-arrow"></use></svg>',
        'next'  => '<svg class="icon_arrow"><use xlink:href="#icon-slide-arrow"></use></svg>',
      ],
    ];

    return $args;
  }


  /**
   * DEV SCRIPTS
   * Add theme scripts
   * 
   * @see wp_enqueue_scripts
   */
  function child_theme_enqueue_scripts__dev() {

    if( btw_is_amp_endpoint() ) return;

    global $btw_global_settings;

    // Registering Scripts
  	$time = strtotime('now');

	  wp_register_script( 'scripts_vanilla_js', get_stylesheet_directory_uri() . '/assets/js/front-end/scripts.vanilla.js', [], $time, true );

    wp_register_script('scripts_js', get_stylesheet_directory_uri() . '/assets/js/front-end/scripts.js', array('jquery'), $time, true);

    wp_register_script('posts_from_api_js', get_stylesheet_directory_uri() . '/assets/js/front-end/postsFromApi.js', array('wp-util'), $time, false);
    wp_register_script('pasely_posts_js', get_stylesheet_directory_uri() . '/assets/js/front-end/parselyPosts.js', array('wp-util'), $time, false);


    wp_localize_script( 'scripts_js', $btw_global_settings::get_localize_js_object_name(), get_localize_script());
    wp_enqueue_script( 'scripts_js' );

    wp_enqueue_script('posts_from_api_js');
    wp_enqueue_script('pasely_posts_js');

    wp_enqueue_script('scripts_vanilla_js');

  }

  /**
   * LIVE SCRIPTS - COMPILED
   * Add theme scripts
   * 
   * @see wp_enqueue_scripts
   */
  function child_theme_enqueue_scripts__live() {

    if( btw_is_amp_endpoint() ) return;

    global $btw_global_settings;

    // Registering Scripts
  	$time = strtotime('now');

    wp_register_script('posts_from_api_bundle_js', get_stylesheet_directory_uri() . '/assets/js/front-end/production/postsFromApi.bundle.js', array('wp-util'), $time, false);
    //wp_register_script('scripts_bundle_js', get_stylesheet_directory_uri() . '/assets/js/front-end/production/scripts.bundle.js', [], $time, true);
    wp_register_script('scripts_js', get_stylesheet_directory_uri() . '/assets/js/front-end/scripts.js', [], $time, true );
   
    // wp_localize_script( 'scripts_bundle_js', $btw_global_settings::get_localize_js_object_name(), get_localize_script() );
    wp_localize_script( 'scripts_js', $btw_global_settings::get_localize_js_object_name(), get_localize_script());

    if( !is_front_page() ){
      wp_enqueue_script( 'posts_from_api_bundle_js' );
    }

    //wp_enqueue_script('scripts_bundle_js');
    wp_enqueue_script( 'scripts_js' );

  }

  add_action( 'wp_enqueue_scripts', 'child_theme_enqueue_scripts__live', 10 );


