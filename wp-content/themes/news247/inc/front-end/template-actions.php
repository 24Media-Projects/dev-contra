<?php


add_action('btw/after_related_terms', 'print_podcast_subcategory_icons');
function print_podcast_subcategory_icons(){
	if( !is_category() ) return;
	$term = get_queried_object();

	if( !btw_is_podcast_subcategory($term) ) return;

	$spotify_url = get_field('btw__podcast_subcategories_fields__spotify_url', $term);
	$apple_podcasts_url = get_field('btw__podcast_subcategories_fields__apple_podcasts_url', $term);
	$google_podcasts_url = get_field('btw__podcast_subcategories_fields__google_podcasts_url', $term);

	if( !$spotify_url && !$apple_podcasts_url && !$google_podcasts_url ) return;

	?>
    <div class="podcasts__subscription_area">
        <span class="label">ΚΑΝΤΕ ΕΓΓΡΑΦΗ</span>

        <div class="subscription_links">
			<?php
			if( $spotify_url ){
				?>
                <a target="_blank" href="<?php echo $spotify_url; ?>">
                    <svg> <use xlink:href="#icon-spotify"></use> </svg>
                </a>
				<?php
			}

			if( $apple_podcasts_url ){
				?>
                <a target="_blank" href="<?php echo $apple_podcasts_url; ?>">
                    <svg> <use xlink:href="#icon-apple_podcast"></use> </svg>
                </a>
				<?php
			}

			if( $google_podcasts_url ){
				?>
                <a target="_blank" href="<?php echo $google_podcasts_url; ?>">
                    <svg> <use xlink:href="#icon-simplecast"></use> </svg>
                </a>
				<?php
			}
			?>
        </div>
    </div>
	<?php
}

add_action('wp_footer', function(){
    if( is_single() && $code = get_field('btw__article_fields__audio_player_code') ):

		// Add title on iframe, if not exists
		if( !preg_match('/title="[^"]+"/', $code, $iframe_title ) ){
			$code = str_replace( '<iframe ', '<iframe title="audio player"', $code );
		}

	?>
		<div class="single_post__audio_player_code">
        	<?php echo $code; ?>
		</div>

    <?php endif;
});



/**
 * Group term basic with banner after posts displayed
 * On category first page, display the 2 first posts of main query
 * On single, display the 2 first posts of the primary category of post
 * 
 * @param int, $group_id
 */
add_action('btw/group/term_basic__with_banner/after_posts', function ($group_id) {
	/**
	 * Category + first page: display the 2 first posts of main query
	 * Single post: display the 2 first posts of the primary category of post
	 */
	if( ( is_category() && get_query_var('paged', 0) == 0 ) || is_single() ){

		global $post, $btw_log_posts;

		$current_term = is_single()
			? btw_get_post_primary_category()
			: get_queried_object();

		$displayed_posts = $btw_log_posts->get_displayed_posts();

		if( is_single() ){
			$displayed_posts[] = $post->ID;
		}

		$first_two_posts = get_posts([
			'posts_per_page' => 2,
			'post_type' 	 => ['post', 'video'],
			'post_status'	 => 'publish',
			'orderby'		 => 'post_date',
			'order'			 => 'desc',
			'post__not_in'	 => $btw_log_posts->get_displayed_posts(),
			'category_name'	 => $current_term->slug,
		]);

		$btw_log_posts->log_posts( $first_two_posts );

		foreach ($first_two_posts as $post) :
			setup_postdata($post);
			get_template_part('templates/template-parts/archive/post');
		endforeach;
		wp_reset_postdata();

	}
});


/**
 * Group term basic with banner: Render ad banner on homepage
 */
add_action( 'btw/group/term_basic__with_banner/ad_banner', function(){ 

	if( !is_front_page() ){
		return;
	}

    global $hp_groups_slots;
    $slot = array_shift( $hp_groups_slots );

    ?>

	<aside class="section_sidebar">
		<?php btw_get_template_part('template-parts/ads/dfp', [
				'slot_id' => $slot['1'],
		]); ?>
	</aside>

<?php });
