<?php
get_header();

$term = get_queried_object();

$next_post_link = btw_get_next_post_link_url();

global $wp_query;

// print_r($wp_query);

$featured_video = new WP_Query([
    'post_type' => 'video',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'suppress_filters' => false,
    'meta_key' => 'btw__video_fields__is_featured_video_for_primary_category',
    'meta_value' => '1',
    'tax_query' => [
        [
            'taxonomy' => 'category',
            'field' => 'term_id',
            'terms' => [$term->term_id]
        ],
    ]
]);

// $videos_query = new WP_Query([
//     'post_type'        => 'video',
//     'post_status'      => 'publish',
//     'posts_per_page'   => 9,
//     'orderby'          => 'date',
//     'order'            => 'DESC',
//     'suppress_filters' => false,
//     'tax_query' => [
//         [
//             'taxonomy' => 'category',
//             'field' => 'term_id',
//             'terms' => [$term->term_id]
//         ],
//     ]
// ]);




?>
<div class="category__wrapper category-videos__wrapper videos-subcategory__wrapper <?php if (trim($term->description)) echo 'with_description'; ?>">

    <div class="category__header">
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

    <div class="category__content">
        <div class="sticky_element__article_sidebar__parent">
            <div class="category__main_column main_column">
                <div class="category__main grid_posts_container">
                    <?php while ($featured_video->have_posts()) : $featured_video->the_post();
                        $img = btw_get_post_featured_image('large_landscape');
                        $post_title = get_the_title();
                        $post_permalink = get_the_permalink();
                        $post_primary_category = btw_get_post_primary_category();
                    ?>
                        <div class="featured_video_container">
                            <article class="article">
                                <figure class="featured_video__main_image">
                                    <a class="post_img" title="<?php echo esc_attr($post_title); ?>" href="<?php echo $post_permalink; ?>">
                                        <img src="<?php echo $img->url; ?>" alt="<?php echo $img->alt; ?>">
                                    </a>
                                </figure>
                                <div class="post__content">
                                    <h3 class="post__title l-article-s-font">
                                        <a title="<?php echo esc_attr($post_title); ?>" href="<?php echo $post_permalink; ?>">
                                            <?php echo $post_title; ?>
                                        </a>
                                    </h3>
                                    <div class="post__category">
                                        <h4 class="l-caption s-font-bold">
                                            <a title="<?php echo esc_attr($post_primary_category->name); ?>" href="<?php echo $post_primary_category->term_link; ?>">
                                                <?php echo $post_primary_category->name; ?>
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endwhile;
                    wp_reset_query(); ?>
                    <section class="category__posts infinite_posts">
                        <?php
                        while (have_posts()): the_post();
                            btw_get_template_part('template-parts/archive/post', [
                                'image_srcsets' => array(
                                    array(
                                        'image_size' => 'small_horizontal',
                                    )
                                ),
                                'lazyload' => $featured_video->have_posts() || $wp_query->current_post > 2,
                            ]);
                        endwhile;
                        ?>
                    </section>
                </div>
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
                    <span>ΠΕΡΙΣΣΟΤΕΡΑ VIDEOS</span>
                    <svg>
                        <use xlink:href="#icon-back-to-top"></use>
                    </svg>
                </a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>