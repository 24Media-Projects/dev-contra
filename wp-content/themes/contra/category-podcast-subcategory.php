<?php
get_header();

$term = get_queried_object();

$next_post_link = btw_get_next_post_link_url();


$code_or_url = trim(get_field('btw__podcast_subcategories_fields__playlist_code', $term));

// container extra classes
$container_classes = [];

if( $term->description ){
    $container_classes[] = 'with_description';
}

if( get_field('btw__podcast_subcategories_fields__is_title_full_width', $term) ){
	$container_classes[] = 'full_width_podcast_subcategory_title';
}
?>
    <div class="category__wrapper podcasts_category__wrapper <?php echo implode( ' ', $container_classes ); ?>">
       
        <div class="category__header">
            <a title="Podcasts" class="podcasts_cta caption s-font-bold" href="/podcasts/">Podcasts</a>
			<?php btw_get_template_part('template-parts/group_header', [
				'section_title' => $term->name,
				//				'related_links' => $related_links,
			]); ?>
        </div>

		<?php if (trim($term->description)) : ?>
            <div class="category__description">
                <div class="section_description">
					<?php echo $term->description; ?>
                </div>
            </div>
		<?php endif; ?>

        <?php print_podcast_subcategory_icons(); ?>

        <div class="podcast_series__playlist">
            <?php
    		if ( wp_http_validate_url($code_or_url) ) {
                echo '<iframe src="' . $code_or_url . '"></iframe>';
    		} else {
    			echo $code_or_url;
    		} ?>
        </div>

        <div class="category__content">
            <div class="sticky_element__article_sidebar__parent">
                <div class="category__main_column main_column">
                    <main class="category__main grid_posts_container">

                        <section class="category__posts infinite_posts">
							<?php
							while (have_posts()): the_post();

                                btw_get_template_part('template-parts/archive/post', [
                                    'lazyload' => $wp_query->current_post > 2,
                                ]);

                                if( $wp_query->current_post == 9 || $wp_query->current_post == 19 ){

                                    btw_get_template_part('template-parts/ads/dfp', [
                                        'slot_id' => 'term_inline' . ( $wp_query->current_post == 19 ? '_a' : '' ),
                                    ]);

                                }
                               
							endwhile; ?>
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
			<?php if ($next_post_link) : ?>
                <div class="load-more-container">
                    <a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button" href="<?php echo $next_post_link; ?>">
                        <span>ΠΕΡΙΣΣΟΤΕΡΑ PODCASTS</span>
                        <svg>
                            <use xlink:href="#icon-back-to-top"></use>
                        </svg>
                    </a>
                </div>
			<?php endif; ?>

        </div>
    </div>

<?php get_footer(); ?>