<?php

/** Theme main stylesheet
 * 
 * @see wp_enqueue_scripts
 */
function enqueue_styles(){

  	$time = strtotime( 'now' );
    if( btw_is_amp_endpoint() ) return;

    wp_register_style( 'mainstyle', get_stylesheet_directory_uri() . '/assets/css/style.css?v='.$time );
    wp_enqueue_style( 'mainstyle' );

  }

  add_action( 'wp_enqueue_scripts', 'enqueue_styles' );


  function get_localize_script_args(){
    return array(
      'ajaxUrl'      => admin_url( 'admin-ajax.php' ),
      'ajaxErrorMsg' => 'Υπήρξε κάποιο πρόβλημα. Παρακαλώ δοκιμάστε αργότερα, ή επικοινωνήστε με τον διαχειριστή.',
      'is_home'      => is_front_page(),
      'search'       => array(
        'search_base_url'             => site_url( '/?s=' ),
        'min_characters'              => 3,
        'nonce'                       => wp_create_nonce( 'live_search' ),
        'search_min_chars_text'       => 'πληκτρολογήστε τουλάχιστον 3 χαρακτήρες',
        'search_no_results_text'      => 'Δεν βρέθηκαν αποτελέσματα',
        'search_view_allResults_text' => 'ΔΕΣ ΠΕΡΙΣΣΟΤΕΡΑ',
      ),
    );
  }


  /*
    Add theme scripts
    See wp_enqueue_scripts action for more details
  */


  // DEV SCRIPTS
  function enqueue_scripts__dev() {

    global $btw_global_settings;

    // Registering Scripts
  	$time = strtotime('now');

    wp_register_script( 'ua_parser', get_template_directory_uri() . '/assets/js/front-end/ua-parser.min.js', [], '0.7.28', false );
    wp_register_script( 'btw_ua_parser', get_template_directory_uri() . '/assets/js/front-end/main-scripts/btw-ua-parser.js', [], $time, false );

    wp_register_script( 'utilities', get_template_directory_uri() . '/assets/js/front-end/main-scripts/utilities.js', [], $time, false );

    wp_register_script( 'lazysizes', get_template_directory_uri() . '/assets/js/front-end/main-scripts/lazy-images/lazysizes.min.js', [], '5.3.2', false );
    wp_register_script( 'optimize', get_template_directory_uri() . '/assets/js/front-end/main-scripts/lazy-images/optimize.js', [], $time, false );
    wp_register_script( 'lazyload-embed', get_template_directory_uri() . '/assets/js/front-end/main-scripts/lazy-images/lazyloadEmbed.js', [], $time, false );

    wp_register_script( 'keen_slider', get_template_directory_uri() . '/assets/js/front-end/keen-slider.js', [], '6.8.5', false );
    wp_register_script( 'keen_slider_lightbox', get_template_directory_uri() . '/assets/js/front-end/keen-slider-lightbox.js', [], $time, false );
    wp_register_script( 'keen_slider_navigation', get_template_directory_uri() . '/assets/js/front-end/keen-slider-navigation.js', [], $time, false );

    // Enqueueing Scripts
    wp_localize_script( 'ua_parser', 'BTW', get_localize_script_args() );

    wp_enqueue_script( 'utilities' );
    wp_enqueue_script( 'ua_parser' );
    wp_enqueue_script( 'btw_ua_parser' );

    wp_enqueue_script( 'lazysizes' );
    wp_enqueue_script( 'optimize' );
    wp_enqueue_script( 'lazyload-embed' );

    wp_enqueue_script( 'keen_slider' );
    wp_enqueue_script( 'keen_slider_lightbox' );
    wp_enqueue_script( 'keen_slider_navigation' );


  }

  // add_action( 'wp_enqueue_scripts', 'enqueue_scripts__dev', 10 );




  // LIVE SCRIPTS
  add_action( 'wp_enqueue_scripts', 'enqueue_scripts__live', 10 );

  function enqueue_scripts__live(){

    // global $btw_global_settings;

    // Registering Scripts
    $time = strtotime( 'now' );

    wp_register_script( 'ua_parser', get_template_directory_uri() . '/assets/js/front-end/ua-parser.min.js', [], '0.7.28', false );
    wp_register_script( 'newsportal_scripts_js', get_template_directory_uri().'/assets/js/front-end/production/main.bundle.js', [], $time, false );

    wp_localize_script('newsportal_scripts_js', 'BTW', get_localize_script_args() );

    wp_enqueue_script( 'ua_parser' );
    wp_enqueue_script( 'newsportal_scripts_js' );

  }


?>
