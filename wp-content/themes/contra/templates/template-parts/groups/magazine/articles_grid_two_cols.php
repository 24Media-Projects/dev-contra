<?php
extract( btw_get_magazine_group_fields() );

$term_ids = get_field('btw__group_fields__magazine__template__articles_grid_two_cols__categories', $group_id);

if( !$term_ids ) return;

global $btw_log_posts;

$two_cols_posts = new WP_Query([
	'post_type'			=> ['post', 'video'],
	'post_status'		=> 'publish',
	'orderby'			=> 'date',
	'order'				=> 'DESC',
	'posts_per_page'	=> 2,
	'suppress_filters'	=> false,
	'post__not_in' 		=> $btw_log_posts->get_displayed_posts(),
	'tax_query'		   	=> [
		[
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => $term_ids
		],
	]
]);


/**
 * Log post to btw_log_posts
 */
$btw_log_posts->log_posts( wp_list_pluck( $two_cols_posts->get_posts(), 'ID' ) );


if ($sponsor_logo) {
	$section_extra_class = 'with_sponsor_logo';
} elseif ($section_supertitle) {
	$section_extra_class = 'with_supertitle';
}


?>
<section id="group-<?php echo $group_id; ?>" class="two_articles_grid articles_grid_two_cols_section  <?php echo $section_extra_class ?? ''; ?> <?php if( $section_title || $related_links ) echo 'magazine_tribute'; ?> <?php if( $is_dark_mode ) echo 'section_darkmode'; ?>"
    style="<?php if( $bg_color ) echo "background-color: $bg_color"; ?>;">

	<?php echo btw_get_impression_url($impression_url); ?>

    <?php
	if( $section_title || $related_links ) {
		btw_get_template_part('template-parts/group_header', [
			'section_title' => $section_title,
			'section_title_url' => $section_title_url,
			'heading' => 'h2',
			'related_links' => $related_links,
			'is_section_title_full_width' => $is_section_title_full_width,
		]);
	}
	?>

	<div class="two_articles_grid">
		<?php while( $two_cols_posts->have_posts() ){
			$two_cols_posts->the_post();
			btw_get_template_part('template-parts/archive/category_magazine/post',[
				'image_size' => 'large_landscape'
			]);
		}
		wp_reset_query(); ?>
	</div>
</section>
