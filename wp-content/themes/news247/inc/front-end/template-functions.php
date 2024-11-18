<?php

function btw_get_intermediate_image_sizes(){
	return array_keys( btw_image_sizes() );
}

function btw_get_related_links_from_flexible_content( $key, $id = false ){

	$arr = [];

	if( have_rows($key, $id) ):

		while( have_rows($key, $id) ):

			the_row();

			$row_layout = get_row_layout();

			if( in_array($row_layout, ['category', 'post_tag']) ) {
				$term = get_sub_field('term');
				
				if( !$term ){
					continue;
				}

				$arr[] = [
					'link_text' => $term->name,
					'link_url' => get_term_link( $term )
				];
			}elseif ($row_layout == 'link') { // custom link
				$arr[] = [
					'link_text' => get_sub_field('link_text'),
					'link_url' => get_sub_field('link_url')
				];
			}

		endwhile;

	endif;

	return $arr;

}

function btw_get_term_from_flexible_content( $key, $id = false ){

	if (have_rows($key, $id)):

		while (have_rows($key, $id)) :

			the_row();

			$row_layout = get_row_layout();

			if( in_array($row_layout, ['category', 'post_tag']) ) {
				return get_sub_field('term');
			}elseif ($row_layout == 'link') { // custom link
				return [
					'link_text' => get_sub_field('link_text'),
					'link_url' => get_sub_field('link_url')
				];
			}

		endwhile;

	endif;

	return false;

}


function btw_get_primary_term_anchor_html( $term, $remove_punctuation = false ){
	return '<a title="' . esc_attr( $term->name ) . '" href="' . $term->term_link . '">' . ( $remove_punctuation ? remove_punctuation($term->name) : $term->name) . '</a>';
}


function btw_is_post_podcast( $post = null ){

	if( !$post ){
		global $post;
	}

	return (bool) get_field('btw__article_fields__audio_player_code', $post);

}


function btw_is_magazine_post( $post = null ){
	if( !$post ){
		global $post;
	}

	if ( $post->post_type !== 'post' ) return false;

	$term = btw_get_post_primary_category();

	return btw_is_magazine_subcategory($term) || btw_is_magazine_homepage($term);
}


function btw_get_magazine_category_id(){
	return 9; // TODO Change if need it, when goes live
}



function btw_is_magazine_subcategory( $term = null ){

	if( !$term ){
		$term = get_queried_object();
	}

	if( !isset($term->term_id) || $term->taxonomy != 'category' ) return false;

	$magazine_id = btw_get_magazine_category_id();

	$term_ancestors = get_ancestors( $term->term_id, 'category', 'taxonomy' );

	return in_array( $magazine_id, $term_ancestors );

}



function btw_is_magazine_homepage( $term = null ){

	if( !$term ){
		$term = get_queried_object();
	}

	if( !isset($term->term_id) || $term->taxonomy != 'category' ) return false;

	return $term->term_id == btw_get_magazine_category_id();

}

/**
 * Checks if is magazine homepage or magazine subcategory.
 *
 * @param WP_Term $term
 * @return bool
 */
function btw_is_magazine_category( $term = null ){

	if( !$term ){
		$term = get_queried_object();
	}

	if( !isset($term->term_id) || $term->taxonomy != 'category' ) return false;

	return btw_is_magazine_homepage($term) ||  btw_is_magazine_subcategory($term);
}


function btw_is_magazine(){
	if( is_single() ){
		return btw_is_magazine_post();
	}elseif( is_category() ){
		return btw_is_magazine_category();
	}else{
		return false;
	}
}


function btw_is_podcast_subcategory( $term = null ){

	if( !$term ){
		if( is_category() ){
			$term = get_queried_object();
		}else{
			return false;
		}
	}

	$parent_term_id = $term->parent;

	$podcasts_category = get_term_by( 'slug', 'podcasts', 'category' ); // TODO MAYBE CHANGE IT WITH ID

	return $podcasts_category->term_id == $parent_term_id;

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

/**
 * @return array $arr {
 * 		@type string $section_title
 * 		@type string $section_title_url
 * 		@type string $section_lead
 * 		@type array $bg_image
 * 		@type array $sponsor_logo
 * 		@type array $sponsor_logo_alt
 * 		@type bool $is_dark_mode
 * 		@type bool $is_sponsored
 *  	@type array $related_links {
 * 			@type string $link_text
 * 			@type string $link_url
 * 		}
 * }
 */
function btw_get_hp_group_fields($post = null){

	if( !$post ){
		global $post;
	}

	$arr['section_title'] = get_field( 'btw__group_fields__hp__general__section_title', $post );
	$arr['section_id'] = get_field( 'btw__group_fields__hp__general__section_id', $post ) ?: 'group-' . ($post->ID ?? $post); // $post maybe is post id
	$arr['section_title_url'] = get_field( 'btw__group_fields__hp__general__section_title_url', $post );
	$arr['section_lead'] = get_field( 'btw__group_fields__hp__general__section_lead', $post );

	$arr['impression_url'] = get_field( 'btw__group_fields__hp__general__impression_url', $post );

	$arr['bg_color'] = get_field( 'btw__group_fields__hp__general__bg_color', $post );

	$arr['is_dark_mode'] = (bool)get_field( 'btw__group_fields__hp__general__is_dark_mode', $post );

	$arr['related_links'] = [];
	$arr['is_section_title_full_width'] = (bool)get_field( 'btw__group_fields__hp__general__is_section_title_full_width', $post );
	$arr['section_supertitle'] = get_field( 'btw__group_fields__hp__general__section_supertitle', $post );

	$arr['sponsor_logo'] = get_field( 'btw__group_fields__hp__general__is_sponsored', $post ) ?
		get_field( 'btw__group_fields__hp__general__sponsor_logo', $post ) ?: []
		: [];

	$arr['section_extra_classes'] = get_field( 'btw__group_fields__hp__general__section_extra_classes', $post );

	$term = get_field( 'btw__group_fields__hp__general__term_selection', $post )[0]['term'] ?? 0;

	if( $term ){

		$arr['section_title'] = $arr['section_title'] ?: $term->name;
		$arr['section_title_url'] = $arr['section_title_url'] ?: get_term_link( $term );
		if( ! get_field( 'btw__group_fields__hp__general__hide_related_links', $post ) ){
			$arr['related_links'] = btw_get_related_links_from_flexible_content( 'btw__taxonomy_fields__related_terms', "{$term->taxonomy}_{$term->term_id}" );
		}

	}else{

		if( $arr['sponsor_logo'] ){

			$arr['sponsor_logo_alt'] = get_field( 'btw__group_fields__hp__general__sponsor_logo_alt', $post ) ?: [];

			$arr['related_links'][0]['link_text'] = '<img src="' . $arr['sponsor_logo']['url'] . '" alt="' . $arr['sponsor_logo']['alt'] . '">';
			if( $arr['sponsor_logo_alt'] ){
				$arr['related_links'][1]['link_text'] = '<img src="' . $arr['sponsor_logo_alt']['url'] . '" alt="' . $arr['sponsor_logo_alt']['alt'] . '">';
			}else{ // fallback
				$arr['related_links'][1]['link_text'] = '<img src="' . $arr['sponsor_logo']['url'] . '" alt="' . $arr['sponsor_logo']['alt'] . '">';
			}
			if( $sponsor_logo_url = get_field( 'btw__group_fields__hp__general__sponsor_logo_click_url', $post )){
				foreach($arr['related_links'] as $k => $v){
					$arr['related_links'][$k]['link_url'] = $sponsor_logo_url;
				}
			}

		}elseif( $arr['section_supertitle'] ){ // section_url

			$arr['related_links'][0]['link_text'] = $arr['section_supertitle'];
			if( $section_supertitle_url = get_field( 'btw__group_fields__hp__general__section_supertitle_url', $post ) ) {
				$arr['related_links'][0]['link_url'] = $section_supertitle_url;
			}

		}


	}

	return $arr;

}

function btw_get_magazine_group_fields(){

	$arr['related_links'] = [];
	$arr['section_title'] = get_field( 'btw__group_fields__magazine__general__section_title' );
	$arr['section_title_url'] = get_field( 'btw__group_fields__magazine__general__section_title_url' );

	$arr['impression_url'] = get_field( 'btw__group_fields__magazine__general__impression_url' );

	$arr['is_dark_mode'] = (bool)get_field( 'btw__group_fields__magazine__general__is_dark_mode' );
	$arr['bg_color'] = get_field( 'btw__group_fields__magazine__general__bg_color' );

	$arr['is_section_title_full_width'] = (bool)get_field( 'btw__group_fields__magazine__general__is_section_title_full_width' );
	$arr['section_supertitle'] = get_field( 'btw__group_fields__magazine__general__section_supertitle' );

	$arr['sponsor_logo'] = get_field( 'btw__group_fields__magazine__general__sponsor_logo' ) ?: [];

	if( $arr['sponsor_logo'] ){

		$arr['sponsor_logo_alt'] = get_field( 'btw__group_fields__magazine__general__sponsor_logo_alt' ) ?: [];

		$arr['related_links'][0]['link_text'] = '<img src="' . $arr['sponsor_logo']['url'] . '" alt="' . $arr['sponsor_logo']['alt'] . '">';
		if( $arr['sponsor_logo_alt'] ){
			$arr['related_links'][1]['link_text'] = '<img src="' . $arr['sponsor_logo_alt']['url'] . '" alt="' . $arr['sponsor_logo_alt']['alt'] . '">';
		}else{ // fallback
			$arr['related_links'][1]['link_text'] = '<img src="' . $arr['sponsor_logo']['url'] . '" alt="' . $arr['sponsor_logo']['alt'] . '">';
		}
		if( $sponsor_logo_url = get_field( 'btw__group_fields__magazine__general__sponsor_logo_click_url' )){
			foreach($arr['related_links'] as $k => $v){
				$arr['related_links'][$k]['link_url'] = $sponsor_logo_url;
			}
		}

	}elseif( $arr['section_supertitle'] ){ // section_url

		$arr['related_links'][0]['link_text'] = $arr['section_supertitle'];
		if( $section_supertitle_url = get_field( 'btw__group_fields__magazine__general__section_supertitle_url' ) ) {
			$arr['related_links'][0]['link_url'] = $section_supertitle_url;
		}

	}



	return $arr;

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
	$esc_attachment_alt = esc_attr( $attachment_alt );
	$attachment_credits = btw_attachment_credits_html( $attachment_obj );

	/**
	 * Default image srcset is the one that has a default key set
	 * or the first array defined on the $image_srcsets
	 **/
	$default_srcset = array_filter( $image_srcsets, function( $image_srcset ){
		return !empty( $image_srcset['default'] );
	});

	$default_srcset = $default_srcset ? array_shift( $default_srcset ) : array_shift( $image_srcsets );


	/**
	 * @note Attachment html is different for amp
	 */
	if( !btw_is_amp_endpoint() ){

		$default_attachment_image_src = wp_get_attachment_image_src( $attachment_id, $default_srcset['image_size'] );

		$img_class = $lazyload ? 'class="lazyload"' : '';
		$img_src = "src=\"{$default_attachment_image_src['0']}\"";
		$img_src = $lazyload ? 'data-' . $img_src : $img_src;

		//first img is the default image
		$attachment_html[] = "<img decoding=\"async\" loading=\"lazy\" {$img_class} {$img_src} alt=\"{$esc_attachment_alt}\" />";

		/**
		 * Get attachment picture sources
		 */
		foreach( $image_srcsets as $image_srcset ){

			// media_query key is required
			if( empty( $image_srcset['media_query'] ) ) {
				continue;
			}

			$attachment_image_src = wp_get_attachment_image_src( $attachment_id, $image_srcset['image_size'] );
			$attachment_image_src = $attachment_image_src[0];

			$attachment_html[] = "<source media=\"{$image_srcset['media_query']}\" srcset=\"{$attachment_image_src}\" />";
		}

		$picture_html = '<picture>' . implode( "\n", array_reverse( $attachment_html ) ) . '</picture>';

	}else{

		$filtered_srcsets = array_filter($image_srcsets, function($image_srcset) {
			return isset($image_srcset['mobile']) && $image_srcset['mobile'] === true;
		});

		$mobile_size = array_shift($filtered_srcsets);


		$mobile_size = $mobile_size['image_size'] ?? $default_srcset['image_size'];

		$amp_attachment_image_src = wp_get_attachment_image_src( $attachment_id, $mobile_size );
		$amp_attachment_image_src = $amp_attachment_image_src['0'];

		$picture_html = "<img src=\"{$amp_attachment_image_src}\" alt=\"{$esc_attachment_alt}\" />";

	}

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

	$oembed_video = new News247_Embed();

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


function get_magazine_sponsor(){

  if( btw_is_magazine_homepage() ){

	$hp_sponsor = btw_get_field( 'btw__magazine_fields__category_sponsor', get_queried_object() );
	if( $hp_sponsor ) return $hp_sponsor;

	$display_default_main_sponsor_on_hp = get_field( 'btw__magazine_fields__display_sponsor_on_magazine_hp', 'option' );
	return $display_default_main_sponsor_on_hp ? btw_get_field( 'btw__magazine_fields__main_sponsor', 'option' ) : false;

  }elseif( btw_is_magazine_subcategory() ){

	  $category_sponsor = btw_get_field( 'btw__magazine_fields__category_sponsor', get_queried_object() );
	  if( $category_sponsor ) return $category_sponsor;

	  $display_default_main_sponsor_on_categories = get_field( 'btw__magazine_fields__display_sponsor_on_posts', 'option' ) ?: [];
	  return in_array( get_queried_object_id(), $display_default_main_sponsor_on_categories ) ? btw_get_field( 'btw__magazine_fields__main_sponsor', 'option' ) : false;

  }elseif( is_singular( 'post' ) ){

	global $post;

	if( get_field('btw__magazine_fields__article_sponsor__hide', $post) ) return false;

	$article_sponsor = btw_get_field( 'btw__magazine_fields__article_sponsor', $post );
	if( $article_sponsor ) return $article_sponsor;

	$category_sponsor = btw_get_field( 'btw__magazine_fields__category_sponsor', btw_get_post_primary_category() );
	if( $category_sponsor ) return $category_sponsor;

	$display_default_main_sponsor_on_categories = get_field( 'btw__magazine_fields__display_sponsor_on_posts', 'option' ) ?: [];
	return in_array( btw_get_post_primary_category()->term_id, $display_default_main_sponsor_on_categories ) ? btw_get_field( 'btw__magazine_fields__main_sponsor', 'option' ) : false;

  }

  return false;

}


function get_magazine_parallax(){

	if( btw_is_magazine_homepage() ){

		$hp_parallax = btw_get_field( 'btw__magazine_fields__category_parallax', get_queried_object() );
		if( $hp_parallax ) return $hp_parallax;

		$display_default_main_parallax_on_hp = get_field( 'btw__magazine_fields__display_parallax_on_magazine_hp', 'option' );
		return $display_default_main_parallax_on_hp ? btw_get_field( 'btw__magazine_fields__main_parallax', 'option' ) : false;

	}elseif( btw_is_magazine_subcategory() ){

		$category_parallax = btw_get_field( 'btw__magazine_fields__category_parallax', get_queried_object() );
		if( $category_parallax ) return $category_parallax;

		$display_default_main_parallax_on_categories = get_field( 'btw__magazine_fields__display_parallax_on_posts', 'option' ) ?: [];
		return in_array( get_queried_object_id(), $display_default_main_parallax_on_categories ) ? btw_get_field( 'btw__magazine_fields__main_parallax', 'option' ) : false;

	}elseif( is_singular( 'post' ) && btw_is_magazine_post() ){

		global $post;

		if( get_field('btw__magazine_fields__article_parallax__hide', $post) ) return false;

		$article_parallax = btw_get_field( 'btw__magazine_fields__article_parallax', $post );
		if( $article_parallax ) return $article_parallax;

		$category_parallax = btw_get_field( 'btw__magazine_fields__category_parallax', btw_get_post_primary_category() );
		if( $category_parallax ) return $category_parallax;

		$display_default_main_parallax_on_categories = get_field( 'btw__magazine_fields__display_parallax_on_posts', 'option' ) ?: [];
		return in_array( btw_get_post_primary_category()->term_id, $display_default_main_parallax_on_categories ) ? btw_get_field( 'btw__magazine_fields__main_parallax', 'option' ) : false;

	}

	return false;

}


function btw_hide_ads(){
	return is_404() ||
		( is_page() && get_field('btw__page_fields__hide_ads') ) ||
		( is_category() && in_array( 'hide_ads', get_field( 'btw__category_fields__display_options', get_queried_object() ) ?: [] ) ) ||
		( is_single() && in_array( 'hide_ads', get_field( 'btw__global_fields__display_options' ) ?: [] ) );
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