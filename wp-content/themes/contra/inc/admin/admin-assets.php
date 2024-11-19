<?php

add_action( 'admin_enqueue_scripts',function(){

    $time = strtotime( 'now' );

    wp_register_style( 'contra_admin_styles', get_stylesheet_directory_uri() . '/assets/css/admin/admin-styles.css', [], $time );
    wp_enqueue_style( 'contra_admin_styles' );

});


add_action( 'admin_enqueue_scripts',function(){

    $time = strtotime( 'now' );

	$deps = ['jquery']; //!load_select2() ? array( 'jquery', 'select2' ) : array( 'jquery' );

    $current_screen = get_current_screen();

	wp_register_script( 'contra_admin_scripts', get_stylesheet_directory_uri() . '/assets/js/admin/admin-scripts.js', [], $time );
	wp_register_script( 'contra_acf_admin_js', get_stylesheet_directory_uri() . '/assets/js/admin/acf-admin.js', $deps, $time, true );

    wp_localize_script( 'contra_admin_scripts', 'CONTRA', [
		'is_single_post' => $current_screen->base == 'post',
		'is_single_page' => $current_screen->base == 'page',
    ]);

    wp_enqueue_script( 'contra_admin_scripts' );
    wp_enqueue_script( 'contra_acf_admin_js' );



});

