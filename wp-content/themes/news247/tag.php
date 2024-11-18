<?php
$term = get_queried_object();
$term_id = $term->term_id;

$featured_image = get_field('btw__global_fields__featured_image', "{$term->taxonomy}_{$term->term_id}");
$related_links = btw_get_related_links_from_flexible_content('btw__taxonomy_fields__related_terms', "{$term->taxonomy}_{$term->term_id}");

$next_post_link = btw_get_next_post_link_url();


get_header();

?>
<div class="category__wrapper tag__wrapper <?php if (trim($term->description)) echo 'with_description'; ?> <?php if ($featured_image) echo 'with_featured_image'; ?> <?php if ($related_links) echo 'with_related_links'; ?>">
    <div class="category__header">
        <?php btw_get_template_part('template-parts/group_header', [
            'section_title' => $term->name,
            'related_links' => $related_links,
        ]); ?>
    </div>
    <?php if (trim($term->description)) : ?>
        <div class="category__description tag__description">
            <?php if ($featured_image) : ?>
                <figure class="tag_featured_image" style="background-image: url(<?php echo $featured_image['sizes']['medium_landscape']; ?>);"></figure>
                <div class="tag_info"><span class="section_extended_description"><?php echo $term->description; ?></span><button aria-label="Expand" class="expand-description" type="button"><span class="invisible">Expand Description</span><svg>
                            <use xlink:href="#icon-back-to-top"></use>
                        </svg></button></div>
            <?php else : ?>
                <div class="section_description"><?php echo $term->description; ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="category__content">
        <div class="sticky_element__article_sidebar__parent">
            <div class="category__main_column main_column">
                <main class="category__main grid_posts_container">
                    <section class="category__posts infinite_posts">
                        <?php
                            while (have_posts()) : the_post();
                            btw_get_template_part('template-parts/archive/post', [
                                'lazyload' => $wp_query->current_post > 2,
                            ]);

                            if( $wp_query->current_post == 9 || $wp_query->current_post == 19){

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

        <?php if( $next_post_link ): ?>
        <div class="load-more-container">
            <a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button" href="<?php echo $next_post_link;?>">
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