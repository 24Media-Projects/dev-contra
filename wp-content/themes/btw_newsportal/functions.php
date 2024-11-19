<?php
// Main Functions File
if( !function_exists('btw_get_global_setting') ) {
	function btw_get_global_setting($setting_name)
	{
		if ($btw_global_settings = $GLOBALS['btw_global_settings']) { // to produce a warning
			$_method = 'get_' . $setting_name;
			if (method_exists($btw_global_settings, $_method)) {
				return $btw_global_settings->$_method();
			}
			//else{ // we dont need a warning here
			//	echo $method; // to produce a warning
			//}
		}

		return null;
	}
}

/**
 * Get supported single post types
 *
 * @param array $post_types. Default: only post.
 * @param string $context
 *
 * @return array
 */
function get_supported_single_post_types( $post_types = [ 'post' ], $context = null ){

	$supported_post_types = apply_filters( 'btw/supported_single_post_types', $post_types, $context );

	if( $context ){
		$supported_post_types = apply_filters( "btw/supported_single_post_types/{$context}", $post_types );
	}

	return $supported_post_types;

}

require_once( 'inc/front-end/useful-functions.php' );
// require_once( get_stylesheet_directory() . '/inc/front-end/classes/class-global-settings.php' );

require_once( 'inc/admin/admin-assets.php' );
require_once( 'inc/admin/acf/acf.php' );


require_once( 'inc/admin/admin-functions.php' );
require_once( 'inc/admin/admin-hooks.php' );
require_once( 'inc/admin/admin-actions.php' );

//ADMIN CLASSES
require_once( 'inc/admin/classes/class-admin-notices.php' );
require_once( 'inc/admin/classes/class-private-post-types.php' );
require_once( 'inc/admin/classes/class-rewrite-rules.php' );
require_once( 'inc/admin/classes/class-btw-user-roles-capabilities.php' );

require_once( 'inc/admin/editor/class-btw-admin-editor.php' );
require_once( 'inc/admin/editor/modules/editor-modules.php' );

require_once( 'inc/admin/classes/class-extend-wp-options-fields.php' );

// cron scheludes
require_once('inc/admin/classes/class-cron-schedules.php');



require_once( 'inc/front-end/register-shortcodes.php' );
require_once( 'inc/front-end/frontend-assets.php' );

require_once( 'inc/front-end/template-functions.php' );

require_once( 'inc/front-end/template-hooks.php' );
require_once( 'inc/front-end/template-actions.php' );
require_once( 'inc/front-end/disable-assets.php' );


//Front End Classes
require_once( 'inc/front-end/classes/class-btw-optimize.php' );

require_once( 'inc/front-end/classes/class-dfp-targeting.php' );
require_once( 'inc/front-end/classes/class-btw-embed.php' );
// require_once( 'inc/front-end/classes/class-btw-rss-feed-settings.php' );


//Customer Post Reviews
require_once( 'inc/admin/classes/class-post-customer-review.php' );

//Live Search
require_once( 'inc/front-end/classes/class-live-search.php' );

// BASE API
require_once( 'inc/base-api/base-api-functions.php' );
require_once( 'inc/base-api/class-btw-base-api-posts.php' );
require_once( 'inc/xml-api/class-btw-posts-xml-api.php' );

// REST API

require_once( 'inc/rest-api/class-btw-rest-api-access.php' );
require_once( 'inc/rest-api/class-btw-rest-api-keys.php' );
require_once( 'inc/rest-api/class-btw-posts-rest-api.php' );

// XML API - RSS
require_once( 'inc/xml-api/xml-api-functions.php' );
require_once( 'inc/xml-api/xml-api-hooks.php' );
require_once( 'inc/xml-api/class-btw-register-xml-api.php' );
require_once( 'inc/xml-api/class-btw-xml-api-access.php' );
require_once( 'inc/xml-api/class-btw-xml-api-settings.php' );



// Adds RSS feed links to <head> for posts and comments.
// add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
add_theme_support( 'yoast-seo-breadcrumbs' );
add_theme_support( 'post-thumbnails', array_merge( get_supported_single_post_types(), ['page'] ) );

add_post_type_support( 'page', 'excerpt' );


// Load text domain
add_action( 'after_setup_theme', function(){
	load_theme_textdomain( 'btw', get_template_directory() . '/languages/btw/' );
});


add_action('after_switch_theme', function(){
	if( get_option( 'image_default_size' ) != 'full' ) {
		update_option('image_default_size', 'full');
	}
});



// Remove WP version
remove_action('wp_head', 'wp_generator');
add_filter( 'the_generator', '__return_null' );


// Revisions
add_filter( 'wp_revisions_to_keep', function( $num, $post ){
	return $post->post_type == 'group' ? 5 : $num;
}, 999, 2 );


add_filter( 'jpeg_quality', function( $quality ){
	return 100;
});