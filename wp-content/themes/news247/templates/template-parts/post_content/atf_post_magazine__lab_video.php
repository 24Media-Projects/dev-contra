<?php
global $group_template;

extract($atf_post);


if( $bg_color != 'transparent' ){
	$style = 'background-color: '. $bg_color;
}

if( !$is_advertorial ){
	$categories = [get_term_by('slug', 'videos', 'category'), btw_get_post_primary_category($wp_post)];
}

?>

<style>
    #<?php echo $section_id; ?> .bg_image {
        background-image: url("<?php echo $attachment_background['mobile']; ?>");
    }
    @media screen and (min-width: 1024px) {
        #<?php echo $section_id; ?> .bg_image {
            background-image: url("<?php echo $attachment_background['desktop']; ?>");
        }
    }
</style>


<article class="<?php echo implode(' ', apply_filters( 'btw/atf_post/render/container_classes', $container_classes, $atf_post, $group_template ) ); ?>" style="<?php echo $style ?? ''; ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <figure class="post_img">
        <a class="clear" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
            <div class="bg_image">
            </div>
        </a>
    </figure>

    <div class="post_content_container">
        <div class="post__content">

            <h2 class="post__title mag-m-article-title">
                <a title="<?php echo $esc_post_title; ?>" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>">
                    <span class="desktop_title"><?php echo remove_punctuation($post_titles['desktop']); ?></span>

                    <?php if ($post_titles['mobile']) : ?>
                    <span class="mobile_title"><?php echo remove_punctuation($post_titles['mobile']); ?></span>
                    <?php endif; ?>
                </a>
            </h2>
            <div class="post__category">
                <?php if( $is_advertorial ): ?>
                    <h3 class="category_caption mag-m-caption asty_bold">
                        <?php
                        // <a> or plain text
                        echo apply_filters( 'btw/atf_post/render/caption', $caption, $atf_post, $group_template );
                        ?>
                    </h3>
                <?php elseif( $categories ): ?>
					<?php foreach ($categories as $key => $category) : ?>
                        <h3 class="category_caption mag-m-caption asty_bold">
                            <a href="<?php echo get_term_link($category); ?>">
                                <?php echo remove_punctuation($category->name); ?></a>
                        </h3>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>
