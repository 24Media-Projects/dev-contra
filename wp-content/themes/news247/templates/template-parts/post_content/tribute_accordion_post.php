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

if( $bg_color != 'transparent' ){
	$style = 'background-color: '. $bg_color;
}

?>


<article class="article slides slide-<?php echo $index + 1; ?> <?php echo implode(' ', $container_classes); ?>" style="<?php echo $style ?? ''; ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <label for="radio-<?php echo $section_id; ?>-tribute_accordion_<?php echo $index + 1; ?>">
        <div class="counter"><?php echo $index + 1; ?></div>
        <div class="article__supertitle caption s-font-bold">
            <h3><?php echo remove_punctuation(strip_tags($caption)); // <a> or plain text ?></h3>
        </div>
    </label>
    <div class="article_info">
        <figure class="tribute_accordion_article_img-<?php echo $index + 1; ?>">
            <a class="clear post_img" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
                <div class="bg_img multi_bg_image lazyload"
                    data-bg="<?php echo $attachment_background['desktop']; ?>"
                    data-desktop-bg="<?php echo $attachment_background['desktop']; ?>"
                    data-mobile-bg="<?php echo $attachment_background['mobile']; ?>">
                </div>
            </a>
        </figure>
        <div class="post__content">
            <h3 class="post__title l-article-m-font">
                <a title="<?php echo $esc_post_title; ?>" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>">
                    <span class="desktop_title truncate" data-truncate-lines="8"><?php echo $post_titles['desktop']; ?></span>

                    <?php if (!empty($post_titles['mobile'])) : ?>
                        <span class="mobile_title truncate" data-truncate-lines="8"><?php echo $post_titles['mobile']; ?></span>
                    <?php endif; ?>
                </a>
            </h3>
        </div>

    </div>
</article>