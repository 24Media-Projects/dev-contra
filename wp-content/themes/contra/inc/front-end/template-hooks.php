<?php


// Remove default wp image sizes: large

add_filter( 'intermediate_image_sizes_advanced', function( $sizes, $image_meta, $attachment_id ){

	unset( $sizes['large'] );

	return $sizes;

}, 99, 3);


add_filter( 'intermediate_image_sizes', function( $sizes ){

	$sizes = array_filter( $sizes, function( $size ){
		return !in_array( $size, [ 'large' ] );
	});

	return $sizes;

}, 99 );


// Allow jfif file upload through WP Media Uploader
add_filter('upload_mimes', function( $mimes ){

    $mimes['jfif'] = 'image/jpeg';

    return $mimes;
}, 2 );


/**
 * Add lazyload to glomex, if glomex embed code is script type and not iframe
 * @param string $embed_code
 * @param string $provider
 */
add_filter( 'btw/optimize/lazyload_embed_code', function( $embed_code, $provider ){

	if( $provider != 'glomex' || strpos( $embed_code, '<glomex-player' ) === false ){
		return $embed_code;
	}

	return preg_replace( '/(?<=<|<\/)glomex-player/', 'lazy-glomex-player', $embed_code );

}, 10, 2 );


/**
 * Filter to modify html of attachment credit value
 * Add an svg icon before the value if the credit key is btw__attachment_fields__credits ( acf )
 * @param string, $value
 * @param string, $key
 * 
 * @return string, $value
 */
add_filter( 'btw/attachment_credits/credit_value', function( $value, $credit_key ){
    if( $credit_key == 'credits' ){
        $value = '<svg><use xlink:href="#icon-camera"></use></svg>' . $value;
    }
    return $value;
}, 10, 2 );


/**
 * Remove punctuation of author on single post
 * 
 * @param string, $display_name
 * @param WP_Post, $post
 * 
 * @return string
 */
add_filter('btw/post_author/display_name', function ($display_name, $post) {
	if( in_array($post->post_type, get_supported_single_post_types() ) ){
		return remove_punctuation($display_name);
	}

	return $display_name;
}, 10, 2);


/**
 * Sharing tools
 * Add svg icon as text on each sharing provider
 * 
 * @param array $sharing_providers
 * 
 * @return array
 */
add_filter( 'btw/sharing_tools/providers', function( $sharing_providers ){

    foreach( array_keys( $sharing_providers ) as $provider ){
        $sharing_providers[ $provider ] = 
            "<svg><use xlink:href=\"#article_share-{$provider}\"></use></svg>
            <span class=\"invisible\">share this on {$provider}</span>";
    }

    return $sharing_providers;

}, 20 );



add_filter( 'btw/admin_editor/modules/read_also/settings', function( $settings ){

    $settings['post_type'][] = "'video'";

    return $settings;

});


// display options classes
add_filter('body_class', function($classes){

	if( is_single() ){

		$post_display_options = get_field( 'btw__global_fields__display_options' ) ?: [];
		$post_primary_category = btw_get_post_primary_category();

		if( in_array('is_dark_mode', $post_display_options) || $post_primary_category->slug == 'episyndeseis' ){
			$classes[] = 'single_darkmode';
		}

	}elseif( btw_is_video_subcategory() ){
		$classes[] = 'videos-subcategory';
	}


	return $classes;
});


/**
 * Change the attachment html of an atf_post
 * 
 * @param int, $featured_image_id
 * @param WP_Post, $post
 * @param string, $group_template
 * 
 * @return string 
 */
add_filter( 'btw/atf_post/wp_post_featured_image_id', function( $featured_image_id, $post, $group_template ){
	
	if( $group_template == 'podcasts_carousel' ){
		
		$podcast_primary_category = btw_get_post_primary_category( $post );
		$podcast_primary_category_feat_image =
			get_field( 'btw__global_fields__featured_image', 'term_' . $podcast_primary_category->term_id )
			?: get_field( 'btw__brand_fields__default_image', 'option' );

		return $podcast_primary_category_feat_image['ID'];
	}

	return $featured_image_id;

} ,10, 3 );






/**
 * Alter Container classes of a post content inline image
 * Add image align class on container
 * 
 * @param string, $container_classes
 * @param string, $inline_attachment
 * @param WP_Post, $post
 * 
 * @return string
 */
add_filter( 'btw/post_content/inline_images/container_classes', function( $container_classes, $inline_attachment, $post ){

	$container_classes = explode( ' ', $container_classes );
	$container_classes[] = 'media_content';

	return implode( ' ', $container_classes );

}, 10, 3 );



/**
 * Elastic search | elasticpress plugin
 * Index only post lead from post meta
 * 
 * @param array, $post_meta
 * 
 * @return array
 */
add_filter( 'ep_prepare_meta_data', function( $post_meta ) {
	// Change this array to match all meta keys you want to index.
	$allowed_meta = array( 'btw__global_fields__lead' );
	$meta         = [];

	foreach ( $allowed_meta as $meta_key ) {
		if ( ! isset( $all_meta[ $meta_key ] ) ) {
			continue;
		}

		$meta[ $meta_key ] = $all_meta[ $meta_key ];
	}

	return $meta;
});


/**
 * Enable elastic search to return total results
 * @param array $formatted_args
 * 
 * @return array
 */
add_filter( 'ep_formatted_args', function( $formatted_args ){
	$formatted_args['track_total_hits'] = true;

	if( !empty( $formatted_args['query']['bool']['should']['0']['multi_match'] ) ){
		$formatted_args['query']['bool']['should']['0']['multi_match']['operator'] = 'and';
	}

	return $formatted_args;
});


/**
 * Elastic search: change orderby
 * @see template-hooks.php
 */
add_filter( 'ep_set_default_sort', 'elasticpress_sort_by', 10, 2 );
add_filter( 'ep_set_sort', 'elasticpress_sort_by', 10, 2 );


/**
 * Filters a menu item's starting output.
 *
 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
 * no filter for modifying the opening and closing `<li>` for a menu item.
 *
 * @param string   $item_output The menu item's starting HTML output.
 * @param WP_Post  $menu_item   Menu item data object.
 * @param int      $depth       Depth of menu item. Used for padding.
 * @param stdClass $args        An object of wp_nav_menu() arguments.
 */
add_filter( 'walker_nav_menu_start_el', function( $item_output, $menu_item, $depth, $args ){

	if( in_array( $args->theme_location, [ 'side_nav_main', 'side_nav_sec' ] ) ){

		$button_on_tap_attr = "tap:menu-item-{$menu_item->ID}.toggleClass(class='open-sub-menu')";

		$item_output .= '
			<button type="button" class="menu-item__toggle-children" on="' . $button_on_tap_attr . '">
				<svg><use xlink:href="#icon-arrow-dropdown-menu"></use></svg>
				<span class="invisible">Close dropdown</span>
			</button>';
	}

	return $item_output;

}, 10, 4 );



/**
 * Change the og image of a post / video / skitsa / page with the one that we generate
 * @param string, $image
 * 
 * @return string
 */
add_filter( 'wpseo_opengraph_image', function( $image ){

	if( is_single() || is_page() ){
		global $post;

		$generated_og_image = get_post_meta( $post->ID, 'btw_post_generated_og_image', true );

		if( !$generated_og_image ){
			return $image;
		}

		return $generated_og_image['attachment_url'];
	}

	return $image;
});




/**
 * Large image threshold to support optix
 */
add_filter('big_image_size_threshold', function ($threshold) {
	return 10000;
});

