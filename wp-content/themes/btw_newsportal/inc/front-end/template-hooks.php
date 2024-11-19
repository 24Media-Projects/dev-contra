<?php

// Allow SVG file upload throught WP Media Uploader
add_filter('upload_mimes', function( $mimes ){

	$mimes['svg'] = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';
  	$mimes['gif'] = 'image/gif';
	$mimes['csv'] = 'text/csv';

	return $mimes;
}, 1 );


add_filter( 'the_password_form', function( $output ){

	global $post;

	$label  = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
	<p>' . __( 'This content is password protected. To view it please enter your password below:' ) . '</p>
	<p><label for="' . $label . '">' . __( 'Password:' ) . ' <input name="post_password" id="' . $label . '" type="password" size="20" /></label> <input type="submit" name="Submit" value="Υποβολή" /></p></form>';

	return $output;
});



add_filter( 'the_content', function( $content ){

	$supported_post_types = apply_filters( 'btw/get_content_images/supported_post_types', array(
		'post'
	));

	if( is_single() && in_the_loop() && is_main_query() ){
		global $post;
		
		if( in_array( $post->post_type, $supported_post_types ) ){
			return get_content_images( $content );
		}
	}

	return $content;

}, 30 );


add_filter( 'wpseo_schema_person_user_id', function( $user_id ){

	if( !is_single() ) return $user_id;

	global $post, $btw_global_settings;

	$post_author = btw_get_post_author( $post );

	if( !$post_author || $post_author->byline || !$post_author->archive_link ){
		return $btw_global_settings->get_default_author()->ID;
	}

	return $post_author->author_id;
});


add_filter( 'wpseo_meta_author', function( $author_name, $presentation ){

	if (!is_single()) return $author_name;

	global $post, $btw_global_settings;

	$post_author = btw_get_post_author( $post );
//	$default_post_author = $btw_global_settings->get_default_author();

	if( !$post_author || !$post_author->archive_link ){
		return $btw_global_settings->get_default_author()->display_name;
	}

	return $post_author->display_name;

}, 10, 2 );


/**
 * Reorder post categories and have primary category first
 * @param array, $data
 */
add_filter( 'wpseo_schema_article', function( $data ){

	if( !is_single() ){
		return $data;
	}

	$original_data = $data;

	try{
		global $post;
		$post_categories = wp_get_post_terms( $post->ID, 'category', [ 'fields' => 'names' ] );

		// if is one category, dont do anything
		if( count( $post_categories ) == 1 ){
			return $data;
		}

		$post_primary_category = btw_get_post_primary_category();
		$post_categories = array_values( array_unique( array_merge( [ $post_primary_category->name ], $post_categories ) ) );

		$data['articleSection'] = $post_categories;

		return $data;

	}catch( Throwable $th ){
		return $original_data;
	}
	
}, 10 );



// Reorder terms using our order meta.
function btw_reorder_post_tags( $terms, $post_id, $taxonomy ) {

  if( $taxonomy != 'post_tag' || !$terms ) return $terms;

	$tag_ids = get_post_meta( $post_id, '_btw_post_tag_ids_order', true );

	if( !$tag_ids ) return $terms;

	// ensure we have an array
	$tag_ids = is_array( $tag_ids ) ? $tag_ids : explode( ',', $tag_ids );

	$_terms = [];

	foreach( $terms as $term ){

		$_terms[ $term->term_id ] = $term;
	}

	$reordered_terms = array_replace( array_flip( $tag_ids ), $_terms );

	return array_values( array_filter( array_values( $reordered_terms ), function( $term ){
			return !empty( $term->taxonomy ) && $term->taxonomy == 'post_tag';
	} ) );

}

// Called in front-end via the_tags() or related variations of.
add_filter( 'get_the_terms', 'btw_reorder_post_tags', 10, 3 );



add_filter( 'wpseo_metadesc', function( $meta_desc ){

	if( !is_single() ) return $meta_desc;

	global $post;

	return !$meta_desc ? wp_strip_all_tags( get_field( 'btw__global_fields__lead', $post->ID ), true ) : $meta_desc;

}, 5 );


add_filter( 'Yoast\WP\SEO\open_graph_description_post', function( $og_desc ){

	return $og_desc ?: apply_filters( 'wpseo_metadesc', $og_desc );

}, 5 );


// Load different template for according to post template acf field value
add_filter( 'single_template', function( $template ){

	global $post, $btw_amp;

	// If post is protected by password return the single-pass-protected template
	if( post_password_required( $post->ID ) ){
		return locate_template( 'single-pass-protected.php' );
	}

  return $template;

});


/*
	Get post primary category, used on permastucture
*/
add_filter( 'post_link_category', function( $category, $categories, $post ) {

	if( !$post ){
		return $category;
	}

	$primary_category = btw_get_post_primary_category( $post );

	return get_term( $primary_category->term_id );

}, 10, 3 );



/*
  Set Body default classes on author archive page.
  Some classes where missing when changed the default author permalink.
  The active-tab is used for active / inactive browser tab when refrehing dfp ads
  See
    inc/admin/classes/class-rewrite-rules.php,
    body_class hook

    for more details
*/
add_filter( 'body_class', function( $classes ){

	if( is_author() ){

		global $wp_query;
		$author = $wp_query->get_queried_object();

		return array(
			'archive',
			'author',
			'author-' . $author->ID,
		);
	}

  if( is_singular( 'post' ) ){
    global $post;

    if( get_field('btw__article_fields__hide_ads', $post->ID ) ){
      $classes[] = 'hide_all_ads';
    }
  }

	if( !is_front_page() ){
		$classes[] = 'ros';
	}

	return $classes;

});

// Remove default wp image sizes: medium, medium_larger, 1536x1536, 2048x2048

add_filter( 'intermediate_image_sizes_advanced', function( $sizes, $image_meta, $attachment_id ){

	unset( $sizes['medium'] );
	unset( $sizes['medium_large'] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );

	return $sizes;

}, 99, 3);


add_filter( 'intermediate_image_sizes', function( $sizes ){
	
	unset($sizes['medium']);
	unset($sizes['medium_large']);
	unset($sizes['1536x1536']);
	unset($sizes['2048x2048']);

	return $sizes;

}, 99 );



add_filter( 'post_gallery', function( $output, $attrs, $gallery_id ){

  if( empty( $attrs['ids'] ) ){
    return $output;
  }

  return btw_return_template_part( 'template-parts/post/gallery', array(
    'attachment_ids' => explode( ',', $attrs['ids'] ),
  ));

}, 10, 3 );


/**
 * Remove wp default containers from caption shortcode.
 * Return only the attachment url
 * Add align class, if exist to <img>
 * 
 * @param string, $output
 * @param array, $attr
 * @param string, $content
 * 
 * @return string, $content
 */
add_filter( 'img_caption_shortcode', function( $output, $attr, $content ){

	if( !empty( $attr['align'] ) ){
		$content =  preg_replace( '/class=\"([^"]+)\"/i', 'class="$1 ' . $attr['align'] . '"', $content );
	}

	return $content;

}, 10, 3 );
