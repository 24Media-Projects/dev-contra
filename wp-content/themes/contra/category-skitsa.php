<?php

get_header();

global $wp_query, $post;

$term = get_queried_object();
$term_id = $term->term_id;

$featured_image = get_field( 'btw__global_fields__featured_image', $term );
$related_links = btw_get_related_links_from_flexible_content( 'btw__taxonomy_fields__related_terms', $term );

$is_first_page = get_query_var( 'paged', 0 ) == 0;

$next_post_link = btw_get_next_post_link_url();

// container extra classes
$container_classes = [];

if( $term->description ){
	$container_classes[] = 'with_description';
}

if( $featured_image ){
	$container_classes[] = 'with_featured_image';
}

if( $related_links ){
	$container_classes[] = 'with_related_links';
}

$is_paged = get_query_var( 'paged' ) > 1;

$posts = $wp_query->get_posts();

$featured_posts__big = !$is_paged ? array_shift( $posts ) : '';
$featured_posts__rest = !$is_paged ? array_slice( $posts, 0, 2 ) : [];
$rest_posts = !$is_paged ? array_slice( $posts, 2 ) : $posts;

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

                <div class="grid_posts_container_with_sidebar">
                    <section class="category__posts infinite_posts">
                        
                        <?php if( !$is_paged ): ?>

                        <div class="category__skitsa--featured_posts">

                            <div class="category__skitsa--featured_posts__big">

                                <?php 
                                    foreach( [ $featured_posts__big ] as $post ):
                                        setup_postdata( $post );
                                        btw_get_template_part( 'template-parts/archive/category_skitsa/skitso_post',[
                                            'lazyload' => false,
                                        ] );
                                    endforeach; 
                                    wp_reset_postdata();
                                ?>

                            </div>

                            <div class="category__skitsa--featured_posts__rest">
                                <?php 
                                    foreach( $featured_posts__rest as $post ):
                                        setup_postdata( $post );
                                        btw_get_template_part( 'template-parts/archive/category_skitsa/skitso_post',[
                                            'image_srcsets' => array(
                                                array(
                                                    'image_size'   => 'medium_square',
                                                    'media_query'  => '(max-width: 767px )',
                                                    'mobile'       => true,
                                                ),
                                                array(
                                                    'image_size'  => 'large_square',
                                                    'default'     => true,
                                                ),
                                            ),
                                            'lazyload' => false,
                                        ]);
                                    endforeach; 
                                    wp_reset_postdata();
                                ?>
                            </div>
                        </div>

                        <?php endif; ?>	

						<?php

						// Main post loop
						foreach( $rest_posts as $index => $post ):
                            btw_get_template_part('template-parts/archive/category_skitsa/skitso_post',[
                                'post' => $post,
                                'image_srcsets' => array(
                                    array(
                                        'image_size'   => 'medium_square',
                                        'media_query'  => '(max-width: 767px )',
                                        'mobile'       => true,
                                    ),
                                    array(
                                        'image_size'  => 'large_square',
                                        'default'     => true,
                                    ),
                                ),
                                'lazyload' => !$is_paged || $index > 2,
                            ]);
                            if( $index == 9 || $index == 19 ){

								btw_get_template_part('template-parts/ads/dfp', [
									'slot_id' => 'term_inline' . ( $index == 19 ? '_a' : '' ),
								]);

							}
                        endforeach;
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
                <a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΣΚΙΤΣΑ" type="button" href="<?php echo $next_post_link; ?>">
                    <span>ΠΕΡΙΣΣΟΤΕΡΑ ΣΚΙΤΣΑ</span>
                    <svg>
                        <use xlink:href="#icon-back-to-top"></use>
                    </svg>
                </a>
            </div>
		<?php endif; ?>

    </div>
</div>
<?php get_footer(); ?>
