<?php

/**
 * Read Also post with primary category gnomes
 * 
 * Available strings
 * @var WP_Post $read_also_post
 */

setup_postdata($read_also_post);
$GLOBALS['post'] = $read_also_post;

$post_title = get_the_title();
$post_link     = get_permalink();
$post_feat_image = btw_get_post_featured_image('small_horizontal');
$post_primary_category = btw_get_post_primary_category();

$post_author = btw_get_post_author();
$post_author_avatar = $post_author->meta['avatar'];

$post_author_hyperlink_open = $post_author->archive_link['url']
    ? '<a title="' . esc_attr( $post_author->archive_link['title'] ) . '" href="' . $post_author->archive_link['url'] . '" target="_blank">'
    : '';

$post_author_hyperlink_close = $post_author->archive_link['url'] ? '</a>' : '';
?>

<div class="read_also__container opinion">

    <span class="read_also__title">Σχετική Γνώμη</span>

    <div class="read_also_item">

        <?php if( $post_author_avatar ): ?>
        <figure class="read_also_item__thumbnail">
            <a href="<?php echo $post_link; ?>" title="<?php echo esc_attr($post_title); ?>" target="_blank">
                <div class="bg" style="background-image: url('<?php echo $post_author_avatar; ?>');"></div>
            </a>
        </figure>
        <?php endif; ?>

        <div class="read_also_item__content">
            <div class="caption read_also_item__caption">

                <?php echo $post_author_hyperlink_open;?>
                    <?php echo $post_author->display_name; ?>
                <?php echo $post_author_hyperlink_close;?>

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