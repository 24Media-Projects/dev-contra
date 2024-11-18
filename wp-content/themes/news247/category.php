<?php

get_header();

global $wp_query;

$term = get_queried_object();
$term_id = $term->term_id;

$featured_image = get_field( 'btw__global_fields__featured_image', $term );
$related_links = btw_get_related_links_from_flexible_content( 'btw__taxonomy_fields__related_terms', $term );

$featured_group = get_field( 'btw__taxonomy_fields__featured_group', $term );

$featured_group_template = $featured_group 
	? get_field('btw__group_fields__hp__template', $featured_group->ID)
	: null;

// container extra classes
$container_classes = [];

if( $featured_group ){
	$container_classes[] = 'has_featured_group';
}

if( $term->description ){
	$container_classes[] = 'with_description';
}

if( $featured_image ){
	$container_classes[] = 'with_featured_image';
}

if( $related_links ){
	$container_classes[] = 'with_related_links';
}

?>
<div class="category__wrapper <?php echo implode( ' ', $container_classes );?>">

    <div class="category__header">
		<?php btw_get_template_part('template-parts/group_header', [
			'section_title' => $term->name,
			'related_links' => $related_links,
		]); ?>
    </div>

	<?php if (trim($term->description)) : ?>
        <div class="category__description">
            <div class="section_description">
				<?php echo $term->description; ?>
            </div>
        </div>
	<?php endif; ?>

	<?php do_action('btw/after_category_description', $term); ?>


    <div class="category__content">
        <div class="category__main_column main_column">
            <main class="category__main grid_posts_container">

				<?php if( $featured_group ){
                    btw_get_template_part( 'template-parts/archive/category/featured_group', [
                        'term' => $term,
                    ]);
				} ?>

                <div class="grid_posts_container_with_sidebar">
                    <section class="category__posts infinite_posts">

						<?php
						/**
						 * btw_redisplay_posts is set on an 'wp' action as a property of global wp_query
						 * @see BTW_Query
						 */
						if( !empty( $wp_query->query_vars['btw_redisplay_posts'] ) ){
							foreach( $wp_query->query_vars['btw_redisplay_posts'] as $post ){
								setup_postdata($post);

								get_template_part('templates/template-parts/archive/post');
							}
							wp_reset_postdata();
						}

						/** 
						 * Custom main loop to exclude displayed posts if there is a featured group
						 */
						global $post, $btw_log_posts;

						$post_query = new WP_Query([
							'posts_per_page' => 24,
							'post_type' 	 => [ 'post', 'video' ],
							'post_status'	 => 'publish',
							'orderby'		 => 'post_date',
							'order'			 => 'desc',
							'post__not_in'	 => $btw_log_posts->get_displayed_posts(),
							'category_name'	 => $term->slug,
						]);

						
						while( $post_query->have_posts() ): $post_query->the_post();
							btw_get_template_part('template-parts/archive/post', [
								'lazyload' => $featured_group || $post_query->current_post > 2,
							]);

                            if( $post_query->current_post == 9 || $post_query->current_post == 19){

								btw_get_template_part('template-parts/ads/dfp', [
									'slot_id' => 'term_inline' . ( $post_query->current_post == 19 ? '_a' : '' ),
								]);

							}
						endwhile;
						wp_reset_query();

						$next_post_link = btw_get_next_post_link_url( $post_query );

						?>
                    </section>
                    <div class="main_sidebar category__aside sidebar_column">
						<?php
						btw_get_template_part('template-parts/ads/dfp', [
							'slot_id' => 'sidebar_a',
							'auto_refresh' => 32000,
						]);
						?>
                    </div>
                </div>
            </main>
        </div>

		<?php if( $next_post_link ): ?>
            <div class="load-more-container">
                <a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button" href="<?php echo $next_post_link; ?>">
                    <span>ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
                    <svg>
                        <use xlink:href="#icon-back-to-top"></use>
                    </svg>
                </a>
            </div>
		<?php endif; ?>

    </div>
</div>
<?php get_footer(); ?>
