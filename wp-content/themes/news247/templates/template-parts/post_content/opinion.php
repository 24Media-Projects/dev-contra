<?php

/**
 * Parameters that can be passed in this template.
 *
 * @param WP_Post $atf_post. Required.
 * @param string $img_type. Required.
 * @param string $article_type. Required
 * @param string $article_font. Optional. Default is article_main_font.
 * @param bool $small_article_mobile. Optional, only used in term_basic. Default is false.
 * 
 * @param string $esc_post_title. Use for title attribute on anchor links
 */


/**
 * @see BTW_Atf_Posts::get_atf_posts()
 */
extract($atf_post);

$container_classes[] = 'opinion_article';

$post_author = btw_get_post_author( $wp_post );
// $post_author = btw_get_post_author_html( $wp_post );

// var_dump($post_author );
$post_author_name = $post_author->display_name;

/**
 * Set truncate lines as data attribute, if requested
 */
$truncate_data_lines = $truncate ? 'data-truncate-lines="' . $truncate . '"' : '';

?>

<article class="<?php echo implode(' ', $container_classes); ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <figure>
        <a class="clear post_img" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
            <img class="lazyload" data-src="<?php echo $post_author->meta['avatar']; ?>" alt="<?php echo esc_attr($post_author_name); ?>" />
        </a>
    </figure>

    <div class="post__content">
        <h3 class="post__title article-s-main-title">

            <a title="<?php echo $esc_post_title; ?>" href="<?php echo $post_link; ?>">
                <span class="desktop_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['desktop']; ?></span>

                <?php if( !empty( $post_titles['mobile'] ) ): ?>
                    <span class="mobile_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['mobile']; ?></span>
                <?php endif; ?>
            </a>

        </h3>
        <div class="post__category">
            <h4 class="caption s-font-bold">
                <?php echo $caption; // <a> or plain text 
                ?>
            </h4>

            <h5 class="sponsor s-font"><?php btw_get_post_author_html( $wp_post, false ); ?></h5>

        </div>
    </div>

</article>