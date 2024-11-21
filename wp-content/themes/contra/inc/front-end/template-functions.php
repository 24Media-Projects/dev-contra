<?php

function btw_get_intermediate_image_sizes(){
	return array_keys( btw_image_sizes() );
}

function btw_get_related_links_from_flexible_content( $key = false, $id = false, $flexible_content_layouts = [] ){

	$arr = [];

	if( !$flexible_content_layouts ){
		$flexible_content_layouts = get_field($key, $id) ?: [];
	}

	foreach ($flexible_content_layouts as $layout){

		$row_layout = $layout['acf_fc_layout'];

		if( in_array($row_layout, ['category', 'post_tag']) ) {
			$term = $layout['term'];

			if( !$term ) continue;

			$arr[] = [
				'link_text' => $layout['link_text'] ?: $term->name,
				'link_url' => get_term_link( $term ),
				'link_cat' => $row_layout,
			];
		}elseif ($row_layout == 'link') { // custom link
			$arr[] = [
				'link_text' => $layout['link_text'],
				'link_url' => $layout['link_url'],
				'link_cat' => $row_layout,
			];
		}

	}

	return $arr;

}


function btw_get_term_from_flexible_content( $key = false, $id = false, $flexible_content_layouts = [] ){
	$flexible_contents = btw_get_terms_from_flexible_content($key, $id, $flexible_content_layouts);
	return $flexible_contents[0] ?? false;
}


function btw_get_terms_from_flexible_content( $key = false, $id = false, $flexible_content_layouts = [] ){


	$arr = [];

	if( $key && !$flexible_content_layouts ){
		$flexible_content_layouts = get_field($key, $id);
	}

	$flexible_content_layouts = $flexible_content_layouts ?: [];

	foreach ($flexible_content_layouts as $layout){

		$row_layout = $layout['acf_fc_layout'];

		if( $row_layout == 'feed' ){
			return 'feed';
		}elseif( in_array($row_layout, ['category', 'post_tag']) && $term = $layout['term'] ) {
			$arr[] = $term;
		}

	}

	return $arr;

}


function btw_get_primary_term_anchor_html( $term, $remove_punctuation = true ){
	$term_link = $term->term_link ?? get_term_link($term, $term->taxonomy);
	return '<a title="' . esc_attr( $term->name ) . '" href="' . $term_link. '">' . ( $remove_punctuation ? remove_punctuation($term->name) : $term->name) . '</a>';
}


function btw_get_maybe_anchor_tag( $url, $label, $remove_punctuation = false ){

	ob_start();
	maybe_print_anchor_opening_tag($url, ['title' => esc_attr( $label )]);
	echo $remove_punctuation ? remove_punctuation($label) : $label;
	maybe_print_anchor_closing_tag($url);
	return ob_get_clean();
}

function btw_is_video_subcategory( $term = null ){

	if( !$term ){
		if( is_category() ){
			$term = get_queried_object();
		}else{
			return false;
		}
	}

	$parent_term_id = $term->parent;

	$videos_category = get_term_by( 'slug', 'videos', 'category' ); // TODO MAYBE CHANGE IT WITH ID

	return $videos_category->term_id == $parent_term_id;

}


/**
 * Checks if a post is live now.
 * If primary category is not liveblog, then returns false.
 *
 * @param WP_Post $post
 * @return bool
 */
function btw_is_post_live_now( $post = null ){

	if( !$post ){
		global $post;
	}

	if( btw_get_post_primary_category( $post )->slug != 'live-blog' ) return false;

	return in_array( 'is_live_now', get_field('btw__global_fields__display_options', $post) ?: [] );

}


function btw_is_promo_text_hidden( $post = null ){

	if( !$post ){
		$post = $GLOBALS['post'];
	}

	return in_array( 'hide_promo_text', get_field('btw__global_fields__display_options', $post) ?: [] );

}


function btw_is_post_opinion( $post ){

	if( !$post ){
		global $post;
	}

	$prim_cat_slug = btw_get_post_primary_category( $post )->slug;

	return in_array( $prim_cat_slug, [ 'opinion', 'gnomes' ] );

}


function btw_is_post_video( $post = null ){

	if( !$post ){
		global $post;
	}

	return $post->post_type == 'video';

}

function btw_get_group_settings($group_type = 'hp', $name = 'general', $post = null){

	$main_key = "btw__group_fields__{$group_type}__{$name}_settings";

	if( !$post ){
		global $post;
	}

	$arr = [];

	while( have_rows($main_key, $post) ) : the_row();

		$arr['section_acf_key'] = $main_key;

		$arr['section_id'] = get_sub_field( 'section_id', $post ) ?: 'group-' . ($post->ID ?? $post); // $post maybe is post id

		$arr['hide_caption'] = get_sub_field( 'hide_caption', $post );
		$arr['hide_author'] = get_sub_field( 'hide_author', $post );

		$arr['primary_term_taxonomy_selection'] = get_sub_field( 'primary_term_taxonomy_selection', $post );

		$arr['section_header_template'] = get_sub_field( 'section_header_template', $post );
		$arr['section_header_is_reversed'] = get_sub_field( 'section_header_is_reversed', $post );
		$arr['section_header_align'] = get_sub_field( 'section_header_align', $post );
		$arr['section_header_desktop_align'] = get_sub_field( 'section_header_desktop_align', $post );

		$arr['posts_display_pattern'] = get_sub_field( 'posts_display_pattern', $post );

		$arr['section_logo'] = get_sub_field( 'section_logo', $post );
		$arr['section_desktop_logo'] = get_sub_field( 'section_desktop_logo', $post ) ?: $arr['section_logo'];

		// source_selection could be 2 terms OR Feed
		$flexible_contents = get_sub_field( 'source_selection', $post );
		$posts_source = btw_get_terms_from_flexible_content(null, null, $flexible_contents);

		$main_term = $posts_source && is_array($posts_source) ? $posts_source[0] : null;
		$main_term_name = $main_term ? $main_term->name : '';
		$main_term_link = $main_term ? get_term_link($main_term) : '';

		$arr['posts_source'] = $posts_source;



//		$arr['terms'] = $posts_source;

		$arr['section_title'] = get_sub_field( 'section_title', $post ) ?: $main_term_name;
		$arr['section_title_url'] = get_sub_field( 'section_title_url', $post ) ?: $main_term_link;


		$arr['section_main_color'] = get_sub_field( 'section_main_color', $post );

		$bg_images = get_sub_field( 'section_header_bg_images', $post )[0] ?? [];
		if( $bg_images && !$bg_images['desktop'] ){
			$bg_images['desktop'] = $bg_images['mobile'];
		}
		$arr['section_header_bg_images'] = $bg_images;


		$arr['bg_color'] = get_sub_field( 'bg_color', $post );

		$arr['is_dark_mode'] = $arr['bg_color'] && get_sub_field( 'is_dark_mode', $post );

		$arr['section_header_bg_color'] = get_sub_field( 'section_header_bg_color', $post );

		if( $arr['section_header_bg_color'] ){
			$section_header_is_dark_mode = get_sub_field('section_header_is_dark_mode', $post);
		}

		$arr['section_header_is_dark_mode'] = $section_header_is_dark_mode ?? $arr['is_dark_mode'];

		$arr['impressions_url'] = get_sub_field( 'impressions_url', $post );


		$arr['buttons'] = get_sub_field( 'buttons', $post );


		$sponsor = get_sub_field( 'sponsor', $post )[0] ?? [];
		if( $sponsor && !$sponsor['sponsor_logo_alt'] ){
			$sponsor['sponsor_logo_alt'] = $sponsor['sponsor_logo'];
		}
		$arr = array_merge($arr, $sponsor);
		$arr['is_sponsored'] = (bool)$sponsor;


		$disclaimer_images = get_sub_field( 'section_disclaimer_images', $post )[0] ?? [];
		if( $disclaimer_images && !$disclaimer_images['desktop'] ){
			$disclaimer_images['desktop'] = $disclaimer_images['mobile'];
		}
		$arr['section_disclaimer_images'] = $disclaimer_images;


	endwhile;


	return $arr;

}

function btw_get_group_setting($setting_name, $default = '', $group_type = 'hp', $group_id = null){
	global $wpdb;

	$group_id = $group_id ?: get_the_ID();

	return $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'btw__group_fields__{$group_type}__general_settings_0_$setting_name' AND post_id = $group_id") ?: $default;

}




/**
 * Get post featured image html. 
 * The core of this function should be synced with get_attachment_html method of BTW_Atf_Post
 * @see class-atf-post-model.php
 * @param WP_Post|null, $post,
 * @param int|null, $attachment_id
 * @param array, $image_srcsets
 * @param string|null, $default_alt
 * @param bool, $lazyload
 */
function btw_get_post_attachment( $post = null, $attachment_id = null, $image_srcsets = [], $default_alt = null, $lazyload = true ){

	if (!$post) {
		global $post;
	}

	// Get default image as fallback
	// A default image should always be set
	$default_image = get_field( 'btw__brand_fields__default_image', 'option' );

	// Get attachment data
	$attachment_id  	= $attachment_id ?: ( get_post_thumbnail_id( $post ) ?: $default_image['ID'] );
	$attachment_obj 	= get_post( $attachment_id );
	$attachment_alt 	= get_post_meta( $attachment_obj->ID, '_wp_attachment_image_alt', true ) ?: ( !is_null($default_alt) ? $default_alt : get_the_title( $post ) );
	$attachment_credits = btw_attachment_credits_html( $attachment_obj );

	$picture_html = btw_get_attachment_html( $attachment_id, $image_srcsets, $attachment_alt, $lazyload );

	return (object) array(
		'id'		 	=> $attachment_id,
		'html'         	=> $picture_html,
		'credits_html' 	=> $attachment_credits,
	);
}



/**
 * @note maybe move it to parent theme
 */
function btw_is_post_sponsored( $post = null ){

    if( !$post ){
        global $post;
    }

    $display_options = get_field( 'btw__global_fields__display_options', $post );

    return in_array( 'is_sponsored', (array) $display_options );

}


/**
 * Generate post estimated reading time
 * @param WP_Post|null, $post
 * 
 * @return int
 */
function btw_get_post_estimated_reading_time(){
	if( get_post_meta(get_queried_object_id(), '_yoast_wpseo_estimated-reading-time-minutes', true) ) {
		return YoastSEO()->meta->for_current_page()->estimated_reading_time_minutes ?: false;
	}else{
		return false;
	}
}


function btw_get_next_post_link_url( $wp_query = null ){

	if( !$wp_query ){
		global $wp_query;
	}

	if( $wp_query->max_num_pages == 0 ){
		return false;
	}

	return next_posts( $wp_query->max_num_pages, false );
}



function btw_prepare_to_truncate( $string ){

	return $string;

	$html = [];
	while( strpos( $string, '<strong>' ) !== false ){

		$pos = strpos( $string, '<strong>' );

		$sub = substr( $string, 0, $pos );

		$string = substr( $string, $pos );

		if( $sub ){
			$html[] = '<span>' . $sub . '</span>';
		}

		$pos = strpos( $string, '</strong>' );

		$sub = substr( $string, 0, $pos + 9 );		

		$html[] =  $sub;

		$string = substr( $string, $pos+ 9 );

	}

	if( $string ){
		$html[] =  '<span>' . $string . '</span>';
	}

	return implode( '', $html );

}



function btw_get_post_video($args = array()){

	global $post;

	extract($args);

	$oembed_video = new Contra_Embed();

	return $oembed_video->get_oembed_video_html($video_url, array(
		'embeded_html' => $embeded_html ?? '',
	));
}


function btw_get_dfp_page_template(){

	global $btw_query;
	
	if( is_archive() ){

		if( $btw_query->template_conditionals['is_videos_category'] || $btw_query->template_conditionals['is_videos_subcategory'] ){
			return 'videos';
		}

		if( $btw_query->template_conditionals['is_podcast_category'] ){
			return 'podcasts_category';
		}

		if( $btw_query->template_conditionals['is_podcast_subcategory'] ){
			return 'podcast_subcategory';
		}

		if( $btw_query->template_conditionals['is_magazine_category']){
			return 'magazine_category';
		}

		if( $btw_query->template_conditionals['is_magazine_subcategory'] ){
			return 'magazine_subcategory';
		}

		return 'archive';

	}elseif( is_page_template( 'templates/eidiseis.php' ) ){
		return 'archive';
	
	}elseif( is_search() ){
		return 'search';
	
	}elseif( is_singular( 'post' ) ){

		if( is_page_template( 'single-liveblog.php' ) ){
			return 'live_blog';
		}

		if( is_page_template( 'single-magazine.php' ) ){
			return 'single_magazine';
		}
		
		return 'single_post';

	}elseif( is_singular( 'video' ) ){
		return 'single_video';

	}elseif( is_singular( 'skitsa' ) ){
		return 'single_skitsa';

	} elseif( is_page_template('templates/protoselida.php') ){
		return 'protoselida';

	}elseif( is_front_page() ){
		return 'home';

	}

	return 'ros';
}


function btw_get_hp_group_slots(){

	if( !is_front_page() ){
		return [];
	}

	global $post;

	//format slot_name, slot_id
	$available_slots = [
		[ 'hp_300x250b', 'hp_section_b' ],
		[ 'hp_300x250c', 'hp_section_c' ],
		[ 'hp_300x250d', 'hp_section_d' ],
		[ 'hp_300x250e', 'hp_section_e' ],
		[ 'hp_300x250f', 'hp_section_f' ],
		[ 'hp_300x250g', 'hp_section_g' ],
	];

	//format slot_name, slot_id
	$repeatable_slot = ['hp_300x250g', 'hp_section_g_'];

	$slot_settings = [];

	$group_templates_with_ad = [
		'term_basic__with_banner',
		'zodiac_signs',
	];

	$groups = btw_get_groups_by_group_type( 'hp' );

	while( $groups->have_posts() ): $groups->the_post();

		$group_id = $post->ID;

		// required to be defined. Used in atf_post template
		$group_template = get_field( 'btw__group_fields__hp__template' );

		if( in_array( $group_template, $group_templates_with_ad ) ){

			$slot = array_shift( $available_slots );

			if( !$slot ){
				$slot = $repeatable_slot;
				$slot['1'] = $repeatable_slot['1'] . $group_id;
			}

			$slot_settings[] = $slot;
		}

	endwhile;
	wp_reset_query();

	return $slot_settings;

}

function btw_get_field($key, $id){
	$acf = get_field( $key, $id) ?: [];
	foreach($acf as $subfield){
		if( !empty($subfield) ) return $acf;
	}

	return false;
}



function btw_hide_ads( $context = null ){
	$hide_ads = is_404() ||
		( is_page() && get_field('btw__page_fields__hide_ads') ) ||
		( is_category() && in_array( 'hide_ads', get_field( 'btw__category_fields__display_options', get_queried_object() ) ?: [] ) ) ||
		( is_single() && in_array( 'hide_ads', get_field( 'btw__global_fields__display_options' ) ?: [] ) );

	$hide_ads =  apply_filters('btw/hide_ads', $hide_ads);

	if($context){
		$hide_ads =  apply_filters("btw/hide_ads/$context", $hide_ads);
	}

	return $hide_ads;

}

function hide_taboola(){
	return in_array( 'hide_post_taboola', get_field( 'btw__category_fields__display_options', 'category_' . btw_get_post_primary_category()->term_id ) ?: [] );
}

/**
 * change search results orderby
 * @param array, $sort
 * @param string, $order
 * 
 * @return array
 */
function elasticpress_sort_by( $sort, $order ){

	$orderby = $_GET['orderby'] ?? 'post_date';
	$sort = array(
        array(
			$orderby => array(
               'order' => $order,
           ),
       ),
   );

	return $sort;
}

function btw_get_term_wp_query(int $limit, $term = null){

	global $btw_log_posts;

	// posts from current term
	if( $term === null ){
		$term = get_queried_object();
	}

	$query_args = [
		'post_type'         => get_supported_single_post_types(),
		'posts_per_page'    => $limit,
		'orderby'           => 'date',
		'order'             => 'DESC',
		'post_status'       => 'publish',
		'post__not_in'      => $btw_log_posts->get_displayed_posts(),
	];

	// posts from given term, if false => posts from whole site
	if( $term !== false ){
		$query_args['tax_query'] = [
			[
				'taxonomy'	=> $term->taxonomy,
				'field'		=> 'term_id',
				'terms'		=> $term->term_id
			]
		];
	}

	$term_wp_query = new WP_Query( $query_args );

	$btw_log_posts->log_posts( $term_wp_query->posts );

	return $term_wp_query;

}


/**
 * @param int $post_count
 * @param BTW_Atf_Post[] $atf_posts
 * @param object|WP_Term|array|null $term
 * @param bool $log_displayed_posts
 *
 * @return BTW_WP_Post[]
 */
function btw_get_group_posts( $post_count, $atf_posts = [], $term = null, $log_displayed_posts = true, $general_feed_as_fallback = true ){

	if( $post_count <= 0 ){
		return [];
	}

	if( $log_displayed_posts ){
		global $btw_log_posts;
	}

	$returned_posts = [];

	if( count( $atf_posts ) >= $post_count ){
		$atf_posts = array_slice( $atf_posts, 0, $post_count );
	}

	foreach( $atf_posts as $atf_post ){
		$returned_posts[] = new BTW_Atf_Post( $atf_post, (bool) $log_displayed_posts );
	}

	// check if we have enough atf posts
	if( count( $returned_posts ) == $post_count ){
		return $returned_posts;
	}

	$post_args = [
		'post_type'			=> get_supported_single_post_types(),
		'posts_per_page'	=> $post_count - count( $returned_posts ),
		'orderby'			=> 'date',
		'order'				=> 'DESC',
		'post_status'		=> 'publish',
		'no_found_rows'		=> true,
	];

	if( $term instanceof WP_Term ){
		$post_args['tax_query'] = [
			[
				'field'		=> 'term_id',
				'terms'		=> $term->term_id,
				'taxonomy'	=> $term->taxonomy,
			]
		];
	}

	if( $log_displayed_posts ){
		$post_args['post__not_in'] = $btw_log_posts->get_displayed_posts();
	}

	// if (is_array($term_or_query_args)) {
	// 	$post_args = array_merge($post_args, $term_or_query_args);
	// }

	$posts = get_posts( $post_args );

	foreach( $posts as $post ){
		$returned_posts[] = new BTW_WP_Post( $post, (bool) $log_displayed_posts );
	}

	if( count( $returned_posts ) == $post_count ){
		return $returned_posts;
	}

	if( !$general_feed_as_fallback ) return $returned_posts;


	$fallback_posts = btw_get_group_posts(
		post_count: $post_count - count( $returned_posts ),
		log_displayed_posts: $log_displayed_posts,
	);

	return array_merge( $returned_posts, $fallback_posts );

}

