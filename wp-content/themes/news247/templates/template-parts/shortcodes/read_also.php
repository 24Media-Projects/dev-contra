<?php

/**
 * The get_posts returns only one read also post 
 * If this post has primary category gnomes, load different template part
 */

global $post;

$read_also_posts = get_posts(array(
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'post__in' => $args['posts'],
));

$read_also_post = $read_also_posts['0'];
$read_also_post_primary_category = btw_get_post_primary_category($read_also_post);

// Check primary of read also post
if ($read_also_post_primary_category->slug == 'gnomes') {
    btw_get_template_part('template-parts/shortcodes/read_also_opinion', [
        'read_also_post' => $read_also_post,
    ]);

    return;
}

setup_postdata($read_also_post);
$GLOBALS['post'] = $read_also_post;

$post_title = get_the_title();
$post_link     = get_permalink();
$post_feat_image = btw_get_post_featured_image('small_horizontal');
$post_primary_category = btw_get_post_primary_category();

?>

<div class="read_also__container">

    <span class="read_also__title">Σχετικό Άρθρο</span>

    <div class="read_also_item">

        <figure class="read_also_item__thumbnail">
            <a href="<?php echo $post_link; ?>" title="<?php echo esc_attr($post_title); ?>" target="_blank">
                <div class="bg" style="background-image: url('<?php echo $post_feat_image->url; ?>');"></div>
            </a>
        </figure>

        <div class="read_also_item__content">
            <div class="caption read_also_item__caption">
                <a title="<?php echo esc_attr($post_primary_category->name); ?>" href="<?php echo $post_primary_category->term_link; ?>" target="_blank">
                    <?php echo $post_primary_category->name; ?>
                </a>
            </div>

            <h3 class="read_also_item__title">
                <a href="<?php echo $post_link; ?>" title="<?php echo esc_attr($post_title); ?>" target="_blank">
                    <?php echo $post_title; ?>
                </a>
            </h3>
        </div>

    </div>

</div>

<?php wp_reset_postdata(); ?>