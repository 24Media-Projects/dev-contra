<?php
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

require_once('inc/front-end/classes/class-global-settings.php');

require_once( 'inc/front-end/classes/class-btw-query.php' );
require_once( 'inc/front-end/classes/class-btw-log-posts.php' );


require_once( 'inc/front-end/template-functions.php' );
require_once('inc/front-end/template-hooks.php');
require_once('inc/front-end/template-actions.php'); //

// require_once( 'inc/front-end/classes/class-atf-post.php' );
require_once( 'inc/front-end/classes/class-atf-post-model.php' );

require_once( 'inc/front-end/frontend-assets.php' );


require_once( 'inc/front-end/register_shortcodes.php' );



// Infinite Posts
require_once('inc/rest-api/class-contra-infinite-posts-rest-api.php');
require_once( 'inc/front-end/classes/class-infinite-posts.php' );


require_once( 'inc/base-api/base-api-functions.php' );
require_once( 'inc/base-api/base-api-hooks.php' );
require_once( 'inc/base-api/class-contra-base-api-feeds.php' );


// dfp post inline ads
require_once( 'inc/front-end/classes/class-dfp-inline.php' );


require_once('inc/front-end/classes/class-contra-amp.php');

require_once('inc/admin/og_image/class-og-image.php');


// bon
// require_once( 'inc/front-end/classes/bon/class-abstract-bon.php' );
// require_once( 'inc/front-end/classes/bon/class-bon-hp-one.php' );
// require_once( 'inc/front-end/classes/bon/class-bon-hp-two.php' );


// Rest api // google news feed
add_action('after_setup_theme', function () {
	require_once( 'inc/rest-api/class-contra-videos-rest-api.php') ;
	require_once( 'inc/rest-api/class-contra-feeds-rest-api.php' );

	// google news feed video
	// require_once( 'inc/front-end/classes/class-google-news-feed-videos.php' );

	require_once( 'inc/xml-api/class-contra-xml-api-videos.php' );
	require_once( 'inc/xml-api/class-contra-xml-api-feeds.php' );
	require_once( 'inc/xml-api/class-contra-xml-api-showcase.php' );

	require_once( 'inc/front-end/classes/class-contra-oembed.php' );

});

require_once( 'inc/xml-api/xml-api-hooks.php' );

require_once( 'inc/xml-api/class-contra-xml-api-settings.php' );


// admin
require_once( 'inc/admin/admin-assets.php' );
require_once( 'inc/admin/admin-hooks.php' );
require_once( 'inc/admin/admin-actions.php' );

require_once( 'inc/admin/acf/acf.php' );
require_once( 'inc/admin/editor/class-contra-editor.php' );

require_once( 'inc/admin/classes/class-contra-user-roles-capabilities.php' );

require_once( 'inc/admin/classes/posts_from_third_party_api/class-posts-from-api.php' );
require_once( 'inc/admin/classes/posts_from_third_party_api/class-parsely-posts.php' );

require_once( 'inc/admin/classes/posts_from_third_party_api/class-parsely-hp-controller.php' );


require_once( 'inc/front-end/classes/class-contra-parsely-post-primary-category.php' );

require_once( 'inc/front-end/classes/class-btw-wp-post.php' );

// bon
require_once( 'inc/admin/classes/posts_from_third_party_api/bon/class-abstract-bon.php' );
require_once( 'inc/admin/classes/posts_from_third_party_api/bon/class-bon-hp-one.php' );
require_once( 'inc/admin/classes/posts_from_third_party_api/bon/class-bon-hp-two.php' );
require_once( 'inc/admin/classes/posts_from_third_party_api/bon/class-bon-hp-three.php' );
require_once( 'inc/admin/classes/posts_from_third_party_api/bon/class-bon-hp-four.php' );

require_once('inc/admin/classes/posts_from_third_party_api/class-cli-controller.php');

require_once( 'inc/admin/classes/class-contra-rewrite-rules.php' );

add_action( 'admin_init', function(){
	require_once( 'inc/admin/editor/modules/blockquote/class-blockquote.php' );
});




function btw_image_sizes(){
	return [
		'χlarge_landscape' => ['3:2', '1320', '880'],
		'large_landscape' => ['3:2', '940', '625'],
		'medium_landscape' => ['3:2', '640', '426'],
		'small_landscape' => ['3:2', '430', '286'],
		'χsmall_landscape' => ['3:2', '150', '100'],

		'medium_horizontal' => ['2:1', '640', '320'],
		'small_horizontal' => ['2:1', '300', '150'],

		'large_square' => ['1:1', '640', '640'],
		'medium_square' => ['1:1', '300', '300'],
		'small_square' => ['1:1', '150', '150'],
	];
}



function btw_image_sizes_by_ratio(){
	return [
		'3:2' => [
			'χlarge_landscape' => ['1320', '880'],
			'large_landscape' => ['940', '625'],
			'medium_landscape' => ['640', '426'],
			'small_landscape' => ['430', '286'],
			'χsmall_landscape' => ['150', '100'],
		],
		'2:1' => [
			'medium_horizontal' => ['640', '320'],
			'small_horizontal'	=> ['300', '150'],
		],
		'1:1' => [
			'large_square'	=> ['640', '640'],
			'medium_square'	=> ['300', '300'],
			'small_square'	=> ['150', '150'],
		],
	];
}


add_action('init', function(){
	foreach(btw_image_sizes() as $size_name => $size_details){
		add_image_size($size_name, $size_details[1], $size_details[2], true);
	}
});


// This theme uses wp_nav_menu() in these locations
register_nav_menus(array(
	'primary_nav'   	 	 => 'Primary Navigation',
	'footer_nav'			 => 'Footer Navigation',
));





/**
 * DISABLE SEARCH
 */
// add_action('template_redirect', function () {
// 	if (is_search()) {
// 		wp_redirect(site_url());
// 		die;
// 	}
// });


add_filter('amp_to_amp_linking_enabled', '__return_false');


add_filter('btw/supported_single_post_types', function($post_types){
	return ['post', 'video'];
});