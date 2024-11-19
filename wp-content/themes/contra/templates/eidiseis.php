<?php // Template Name: ΡΟΗ ΕΙΔΗΣΕΩΝ
get_header();

$posts_query = new WP_Query([
	'post_type'         => ['post', 'video'],
	'post_status'       => 'publish',
	'posts_per_page'    => 24,
	'orderby' => 'date',
	'order'   => 'DESC',
	'paged'   => get_query_var( 'paged' ) ?: 1,
]);

$next_post_link = btw_get_next_post_link_url( $posts_query );

?>

<div class="category__wrapper news_feed__wrapper">
	<div class="category__header">
		<?php btw_get_template_part('template-parts/group_header', [
			'section_title' => get_the_title(),
		]); ?>
	</div>
	<div class="category__content">
		<div class="sticky_element__article_sidebar__parent">
			<div class="category__main_column main_column">
				<main class="category__main grid_posts_container">
					<section class="category__posts infinite_posts">
						<?php //the_content();

						while ($posts_query->have_posts()) {
							$posts_query->the_post();
							btw_get_template_part('template-parts/archive/post',[
								'lazyload' => $wp_query->current_post > 2,
							]);
							
                            if( $posts_query->current_post == 9 || $posts_query->current_post == 19){

								btw_get_template_part('template-parts/ads/dfp', [
									'slot_id' => 'term_inline' . ( $posts_query->current_post == 19 ? '_a' : '' ),
								]);

							}
						}
						wp_reset_query();
						?>
					</section>
				</main>
			</div>
			<aside class="main_sidebar category__aside sidebar_column">
                <?php
                btw_get_template_part('template-parts/ads/dfp', [
                    'slot_id' => 'sidebar_a',
                ]);
                ?>
			</aside>
		</div>

		<?php if( $next_post_link ): ?>
		<div class="load-more-container">
			<a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button" href="<?php echo $next_post_link;?>">
				<span>ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
				<svg>
					<use xlink:href="#icon-back-to-top"></use>
				</svg>
			</a>
		</div>
		<?php endif ;?>

	</div>
</div>

<?php get_footer(); ?>