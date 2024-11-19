<?php

/*
  Post slug
  Create post slug replacing all greek letters with latin with the bellow matching
  See sanitize_title hook for more detais
*/

function greeklish_permalinks_sanitize_title( $text ){
	// if ( !is_admin() ) return $text;

	$expressions = array(
		'/[αΑ][ιίΙΊ]/u' => 'ai',
		'/[οΟ][ιίΙΊ]/u' => 'oi',
		'/[Εε][ιίΙΊ]/u' => 'ei',
		'/[αΑ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'af$1',
		'/[αΑ][υύΥΎ]/u' => 'av',
		'/[εΕ][υύΥΎ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'ef$1',
		'/[εΕ][υύΥΎ]/u' => 'ev',
		'/[οΟ][υύΥΎ]/u' => 'ou',
		'/(^|\s)[μΜ][πΠ]/u' => '$1b',
		'/[μΜ][πΠ](\s|$)/u' => 'b$1',
		'/[μΜ][πΠ]/u' => 'mp',
		'/[νΝ][τΤ]/u' => 'nt',
		'/[τΤ][σΣ]/u' => 'ts',
		'/[τΤ][ζΖ]/u' => 'tz',
		'/[γΓ][γΓ]/u' => 'ng',
		'/[γΓ][κΚ]/u' => 'gk',
		'/[ηΗ][υΥ]([θΘκΚξΞπΠσςΣτTφΡχΧψΨ]|\s|$)/u' => 'if$1',
		'/[ηΗ][υΥ]/u' => 'iu',
		'/[θΘ]/u' => 'th',
		'/[χΧ]/u' => 'x',
		'/[ψΨ]/u' => 'ps',
		'/[αάΑΆ]/u' => 'a',
		'/[βΒ]/u' => 'v',
		'/[γΓ]/u' => 'g',
		'/[δΔ]/u' => 'd',
		'/[εέΕΈ]/u' => 'e',
		'/[ζΖ]/u' => 'z',
		'/[ηήΗΉ]/u' => 'i',
		'/[ιίϊΐΙΊΪ]/u' => 'i',
		'/[κΚ]/u' => 'k',
		'/[λΛ]/u' => 'l',
		'/[μΜ]/u' => 'm',
		'/[νΝ]/u' => 'n',
		'/[ξΞ]/u' => 'x',
		'/[οόΟΌ]/u' => 'o',
		'/[πΠ]/u' => 'p',
		'/[ρΡ]/u' => 'r',
		'/[σςΣ]/u' => 's',
		'/[τΤ]/u' => 't',
		'/[υύϋΰΥΎΫ]/u' => 'i',
		'/[φΦ]/iu' => 'f',
		'/[ωώ]/iu' => 'o'
	);

	$text = preg_replace( array_keys( $expressions ), array_values( $expressions ), $text );
	return $text;
}
add_filter( 'sanitize_title', 'greeklish_permalinks_sanitize_title', 1 );



/*
  Post Tags
  Save post tags in a post meta to preserve the order gave by the author.
  Post meta: _btw_post_tag_ids_order, comma seperated post tag ids.
  These is done when instert / update post terms on post save.
  See set_object_terms action for more details
*/
add_action( 'set_object_terms', function ( $object_id, $terms, $tt_ids, $taxonomy, $append, $old_tt_ids ) {

  if( $taxonomy != 'post_tag' ) return;

  update_post_meta( $object_id, '_btw_post_tag_ids_order', $terms );

}, 10, 6 );


/*
  Print post tags preserving the order gave by the author when editing post

  See
    inc/front-end/template-hooks.php btw_reorder_post_tags function
    terms_to_edit hook

    for more details
*/

add_filter( 'terms_to_edit', function ( $terms_to_edit, $taxonomy ) {

  global $post;

  if( !isset( $post->ID ) || $taxonomy != 'post_tag' || !$terms_to_edit ){
    return $terms_to_edit;
  }

  if ( $terms = get_object_term_cache( $post->ID, $taxonomy ) ) {
    $terms = btw_reorder_post_tags( $terms, $post->ID, $taxonomy );


    return implode( ',', wp_list_pluck( $terms, 'name' ) );
  }

  return $terms_to_edit;

}, 10, 2 );


/*
  Limit search posts only to post title to prevent slow sql queries
  See posts_search hook for more details
*/
add_filter( 'posts_search', function( $search, $wp_query ){

	if( empty( $search ) || !is_admin() ) return $search;

	global $wpdb;

	$q = $wp_query->query_vars;
	$search_terms = $q['search_terms'];
	$n = !empty( $q['exact'] ) ? '' : '%';
	$search = $searchand = '';

	foreach( ( array ) $search_terms as $term ){
		$like      = $n . $wpdb->esc_like( $term ) . $n;
    $search   .= $wpdb->prepare( "{$searchand}( {$wpdb->posts}.post_title LIKE %s ) ", $like );
    $searchand = ' AND ';
	}

	return !empty( $search_terms ) ? " AND ({$search}) " : $search;

}, 10, 2 );


/**
 * Add user role as class on body
 * 
 * @param string, $classes
 * 
 * @return string, $classes
 */
add_filter( 'admin_body_class', function( $classes ){

	$user = wp_get_current_user();

	$role_classes = [];
	$role_classes[] = '';

	foreach( $user->roles as $role ){
		$role_classes[] = 'role-' . $role;
	}

	return $classes . implode( ' ', $role_classes );

});



// Ability to add custom content before row actions
add_filter('post_row_actions', function ($actions, $post) {
	do_action("btw/$post->post_type/before_post_row_actions", $post);
	return $actions;
}, 10, 2);


add_filter('display_post_states', function($post_states, $post){

	if( is_admin() && $GLOBALS['pagenow'] == 'edit.php' ){
		do_action("btw/$post->post_type/after_post_title", $post);
	}

	return $post_states;
}, 10, 2);


/**
 * At Posts List make the primary category, the 1st link
 */
add_filter('post_column_taxonomy_links', function ($cat_links, $taxonomy)
{
	if( $taxonomy !== 'category' ) return $cat_links;

	if( count($cat_links) < 2 ) return $cat_links;

	$primary_category = btw_get_post_primary_category();

	foreach ($cat_links as $key => $cat_link){
		if ( preg_match('/category_name=([^"]+)/', $cat_link, $matches) && $primary_category->slug == $matches[1] ) {
			$primary_link = str_replace('<a href', '<a style="font-weight: 700;" href', $cat_link);
			unset( $cat_links[$key] );
		}
	}

	if( $primary_link ?? false ){
		array_unshift($cat_links, $primary_link);
	}

	return $cat_links;

}, 10, 2);
