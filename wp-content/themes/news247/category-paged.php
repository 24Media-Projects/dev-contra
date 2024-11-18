<?php

get_header();

global $wp_query;

$term = get_queried_object();
$term_id = $term->term_id;

$featured_image = get_field('btw__global_fields__featured_image', $term);
$related_links = btw_get_related_links_from_flexible_content('btw__taxonomy_fields__related_terms', $term);

$next_post_link = btw_get_next_post_link_url();

// container extra classes
$container_classes = [];


if ($term->description) {
    $container_classes[] = 'with_description';
}

if ($featured_image) {
    $container_classes[] = 'with_featured_image';
}

if ($related_links) {
    $container_classes[] = 'with_related_links';
}

?>
<div class="category__wrapper <?php echo implode(' ', $container_classes); ?>">

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

                        <?php
                     
                        // Main post loop
                        while (have_posts()) : the_post();
							btw_get_template_part('template-parts/archive/post',[
                                'lazyload' => $wp_query->current_post > 2,
							]);

                            if ($wp_query->current_post == 9 || $wp_query->current_post == 19) {

                                btw_get_template_part('template-parts/ads/dfp', [
                                    'slot_id' => 'term_inline' . ($wp_query->current_post == 19 ? '_a' : ''),
                                ]);
                            }
                        endwhile;

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

        <?php if ($next_post_link) : ?>
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