<?php
global $wp_query, $post;

$term = get_queried_object();
$term_id = $term->term_id;

$subcategories = get_categories([
    'child_of' => $term->parent == btw_get_magazine_category_id() ? $term_id : $term->parent
]);

$posts = $wp_query->get_posts();
$is_paged = get_query_var('paged') > 1;

if (!$is_paged) {
    $featured_posts = array_slice($posts, 0, 3);
    $secondary_posts = array_slice($posts, 3, 2);
    $rest_posts = array_slice($posts, 5);
} else {
    $rest_posts = $posts;
}


$page_title = $term->parent == btw_get_magazine_category_id() ? $term->name : get_term($term->parent, 'category')->name;

$next_post_link = btw_get_next_post_link_url();

get_header(); ?>

<div class="magazine_category magazine_subcategory_wrapper">

	<?php btw_get_template_part('template-parts/post_content/magazine_sponsor', [
		'sponsor' => get_magazine_sponsor()
	]); ?>

    <?php
    btw_get_template_part('template-parts/ads/dfp', [
        'slot_id' => 'magazine_category_billboard',
    ]);
    ?>

    <div class="category__header">
        <div class="group_header">
            <h1 class="section__title"><?php echo remove_punctuation($page_title); ?></h1>
        </div>

        <?php if ($subcategories) : ?>
            <div class="subcategories-wrapper">
        <?php endif; ?>

            <?php foreach ($subcategories as $category): ?>
                <ul class="sub_nav">
                    <li class="sub_item <?php if ($category->term_id == $term_id) echo 'active'; ?>">
                        <a href="<?php echo get_category_link($category->term_id); ?>">
                            <?php echo remove_punctuation($category->name); ?></a>
                    </li>
                </ul>
            <?php endforeach; ?>

        <?php if ($subcategories) : ?>
            </div>
        <?php endif; ?>

        <div class="category__description">
            <div class="section_description">
                <?php if ($term->description) : ?>
                    <?php echo $term->description; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!empty($featured_posts)) : ?>
        <div class="three_articles_grid">

            <?php foreach ($featured_posts as $post) :
                btw_get_template_part('template-parts/archive/category_magazine/post',[
                    'lazyload' => false,
                ]);
            endforeach; ?>

        </div>
    <?php endif; ?>

    <?php if (!empty($secondary_posts)) : ?>
        <div class="two_articles_grid">

            <?php foreach ($secondary_posts as $post) :
                btw_get_template_part('template-parts/archive/category_magazine/post', [
                    'image_size' => 'large_landscape',
                    'lazyload'   => !empty( $featured_posts ),
                ]);
            endforeach; ?>

        </div>

        <?php
            btw_get_template_part( 'template-parts/ads/dfp',[
                'slot_id' => 'magazine_category_inline',
                'container_class' => [ 'magazine_category_inline' ],
            ]);
        ?>
    <?php endif; ?>

    <?php if ($rest_posts) : ?>
        <div class="three_articles_grid infinite_posts">

            <?php foreach ($rest_posts as $index => $post) :
                btw_get_template_part('template-parts/archive/category_magazine/post',[
                    'lazyload' => !empty($featured_posts) || !empty($secondary_posts) || $index > 2,
                ]);

                if ($index == 5) {
                    btw_get_template_part('template-parts/ads/dfp', [
                        'slot_id' => 'magazine_category_billboard_b',
                    ]);
                }

                if ($index == 7 || $index == 15 ){

                    btw_get_template_part('template-parts/ads/dfp', [
                        'slot_id' => 'magazine_category_inline' . ( $index == 7 ? '_a' : '_b' ),
                        'container_class' => [ 'magazine_category_inline' ],
                    ]);
                }

            endforeach; ?>

        </div>
    <?php endif; ?>

    <?php if ($next_post_link) : ?>
        <div class="category__wrapper">
            <div class="load-more-container">
                <a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button" href="<?php echo $next_post_link; ?>">
                    <span>ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
                    <svg>
                        <use xlink:href="#icon-back-to-top"></use>
                    </svg>
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php get_footer(); ?>