<?php
global $wp_query, $btw_log_posts;

get_header();
?>

<main class="main_home magazine_home_wrapper">
    <h1 class="invisible">The Magazine</h1>

    <?php
	btw_get_template_part('template-parts/post_content/magazine_sponsor', [
		'sponsor' => get_magazine_sponsor()
	]);

    $is_paged = get_query_var( 'paged' ) > 1;
    $displayed_posts = [];
    $query = $wp_query;

    if( !$is_paged ){

        $group = btw_get_groups_by_group_type( 'magazine' );

        while ( $group->have_posts() ): $group->the_post();

            $group_id = get_the_ID();

            // required to be defined. Used in atf_post template
            $group_template = get_field( 'btw__group_fields__magazine__template' );

            btw_get_template_part( 'template-parts/groups/magazine/' . $group_template, array(
                'primary_term'  => 'post_tag',
                'group_id' => $group_id,
            ));

			if( $group->current_post == 0 ){
				btw_get_template_part('template-parts/post_content/magazine_parallax', [
					'parallax' => get_magazine_parallax()
				]);

			}elseif( $group->current_post == 1 ){
				btw_get_template_part('template-parts/ads/dfp', [
                    'slot_id' => 'magazine_home_billboard',
                ]);
            }

        endwhile;
        wp_reset_query();

        $query = new WP_Query([
            'post_type' => [ 'post', 'video' ],
            'post_status' => 'publish',
            'posts_per_page' => 24,
            'post__not_in' => $btw_log_posts->get_displayed_posts(),
            'category_name' => 'magazine',
        ]);

    }

    $next_post_link = btw_get_next_post_link_url( $query );

    ?>

    <div class="three_articles_grid infinite_posts">

        <?php while( $query->have_posts() ): $query->the_post();

            btw_get_template_part('template-parts/archive/category_magazine/post',[
                'lazyload' => !$is_paged || $query->current_post > 2,
            ]);

            if( $query->current_post == 7 || $query->current_post == 15 ){

                btw_get_template_part('template-parts/ads/dfp', [
                    'slot_id' => 'magazine_category_inline' . ( $query->current_post == 7 ? '_a' : '_b' ),
                    'container_class' => [ 'magazine_category_inline' ],
                ]);
            }

        endwhile; ?>

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


</main>

<?php get_footer();?>