<?php

// Allow jfif file upload throught WP Media Uploader
add_filter('upload_mimes', function( $mimes ){

$mimes['jfif'] = 'image/jpeg';

return $mimes;
}, 2 );


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
	if (is_singular(['post', 'skitsa']) || btw_is_magazine_homepage()) { // we don't use it in hp group the_magazine
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


/**
 * Change the template path for read also post, if post's primary category is opinion
 * Default: template-parts/shortcodes/read-also ( .php )
 * 
 * @param string, $template_path
 * @param array, $posts
 * 
 * @return array
 */
// add_filter( 'btw_read_also/template_part', function( $template_path, $posts ){

//     foreach

// }, 10, 2 );





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

		if( $post_primary_category->slug == 'episyndeseis' ){
			$classes[] = 'episyndeseis_post';
		}

	}elseif( btw_is_video_subcategory() ){
		$classes[] = 'videos-subcategory';
	}


	return $classes;
});

// track_displayed_posts
add_filter('the_post', function ($post){
	global $displayed_posts;
	if( !in_array($post->ID, (array)$displayed_posts) ) $displayed_posts[] = $post->ID;
});

// remove_already_displayed_posts
add_action('_pre_get_posts', function($query) {
	if( is_category() && $query->is_main_query() && get_field('btw__taxonomy_fields__featured_group', get_queried_object() ) ){
		global $displayed_posts;
		$query->set('post__not_in', $displayed_posts);
	}
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

	// get html classes of <img> ( $inline_attachment )
	if( !preg_match( '/class="([^"]+?)"/', $inline_attachment, $img_classes ) ){
		return $container_classes;
	}

	if( !preg_match( '/align(left|center|right)/', $img_classes['1'], $align_class ) ){
		return $container_classes;
	}
	
	$container_classes = explode( ' ', $container_classes );
	$container_classes[] = $align_class['0'];

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
 * Force custom rule for hide author
 * @param bool, $hide_author
 * @param WP_Post, $post
 * 
 * @return bool
 */
add_filter( 'btw/post_author/hide_author', function( $hide_author, $post ){

	$post_primary_category = btw_get_post_primary_category( $post );
	if( $post_primary_category->slug == 'episyndeseis' ){
		return true;
	}

	return $hide_author;
}, 10, 2 );

add_filter('the_content', function($the_content){


	if( is_singular(['post', 'video', 'skitsa', 'page']) && str_contains($the_content, '[pdf-embedder') ){
		ob_start();
		?>
		<div class="pdfemb-mob-close-modal" onclick="document.querySelector('.pdfemb-fs.pdfemb-toggled').click();" style="
    		display: none; /* Το display none άσε το inline... */
        ">
			<svg>
				<use xlink:href="#icon-close"></use>
			</svg>
		</div>
        <style>
            body[style*="overflow: hidden"] .pdfemb-mob-close-modal{
                display: block !important;
            }
        </style>
		<?php
		$the_content .= ob_get_clean();
	}
	return $the_content;
});


/**
 * Large image threshold to support optix
 */
add_filter('big_image_size_threshold', function ($threshold) {
	return 10000;
});



/**
 * START => Enable bellow wp_content hooks if optix plugin is disabled
 */

// add_filter( 'the_content', function( $post_content ){
//      if( is_single() && in_the_loop() && is_main_query() ){
//         return set_post_content_image_urls( $post_content );
//      }
//      return $post_content;
// }, 99 );

// add_filter( 'the_editor_content', 'set_post_content_image_urls', 1 );

// function set_post_content_image_urls( $post_content ){

//     if( !$post_content ){
//         return $post_content;
//     }

//     global $wpdb;
   
//     $domain_name = preg_replace( [ '/https?:\/\/(www)?/', '/\//', '/\./' ], [ '', '\/', '\.' ], site_url() );
    
//     preg_match_all( "/img[^>]+>?/", $post_content, $inline_attachments );

//     if( !$inline_attachments ) return $post_content;

//     foreach( $inline_attachments['0'] as $inline_attachment ){

//         if( !preg_match( '/https?:\/\/(www)?' . $domain_name . '/', $inline_attachment ) ){
//             continue;
//         }

//         /**
//          * @note: in order to perform correct image replacement
//          * img tag class name attribute containing the image id ( wp-image-<image_id>) must be correct
//          */
//         // get attachment id from <img>, id / class
//         if( !preg_match( '/id="([^"]+?)"/', $inline_attachment, $attachment_id_from_img_id )
//             && !preg_match( '/wp-image-(\d+)\s?/', $inline_attachment, $attachment_id_from_img_class )
//         ){
//             continue;
//         }

//         $attachment_id = $attachment_id_from_img_id['1'] ?? ( $attachment_id_from_img_class['1'] ?? null );

//         if( !$attachment_id ) continue;

//         preg_match( '/src=\"([^"]+?)\"/', $inline_attachment, $existing_inline_image_src );

//         $existing_inline_image_src = $existing_inline_image_src['1'];

//         $new_inline_image_src = wp_get_attachment_image_url( $attachment_id, 'full' );

//         $post_content = str_replace( $existing_inline_image_src, $new_inline_image_src, $post_content );
//     }

//     if( is_admin() ){
//         remove_filter( 'the_editor_content', 'set_post_content_image_urls', 1 );
//     }

//     return $post_content;

// }

/* END => Enable above wp_content hooks if optix plugin is disabled */