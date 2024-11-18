<?php
global $group_template;

extract($atf_post);

if( !in_array('above-the-fold-half-article', $container_classes) ){
	$bg_color = 'transparent';
}

if( $bg_color != 'transparent' ){
	$style = 'background-color: '. $bg_color;
}

?>


<style>
    #<?php echo $section_id; ?> .bg {
        background-image: url("<?php echo $attachment_background['mobile']; ?>");
    }
    @media screen and (min-width: 768px) {
        #<?php echo $section_id; ?> .bg {
            background-image: url("<?php echo $attachment_background['desktop']; ?>");
        }
    }

    <?php
    $min_width = in_array('above-the-fold-half-article', $container_classes) ? '1024' : '1366';
    ?>
    @media screen and (min-width: <?php echo $min_width; ?>px) {
        #<?php echo $section_id; ?> .bg {
            background-image: url("<?php echo $attachment_background['full']; ?>");
        }
    }
</style>


<article class="<?php echo implode(' ', apply_filters( 'btw/atf_post/render/container_classes', $container_classes, $atf_post, $group_template ) ); ?>" style="<?php echo $style ?? ''; ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <figure class="single_article__main_image">
        <a class="clear post_img" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
            <div class="bg">
            </div>
        </a>
    </figure>
    <div class="header_info post__content">
        <div class="mag-l-caption asty_bold article_captions">
			
            <?php
            // <a> or plain text
            echo apply_filters( 'btw/atf_post/render/caption', $caption, $atf_post, $group_template );
            ?>
		
            <span class="post__author asty_book">

                    <?php if( $author->archive_link['url'] ?? 0 ): ?>
                        <a href="<?php echo $author->archive_link['url']; ?>" title="Δείτε όλα τα άρθρα από το χρήστη <?php echo $author->display_name; ?>">
                    <?php endif; ?>

                        <span class="post__author"><?php echo remove_punctuation($author->display_name); ?></span>

                    <?php if( $author->archive_link['url'] ?? 0 ): ?>
                        </a>
                    <?php endif; ?>

            </span>
        </div>
        <h2 class="single_article__title mag-xl-article-title post__title article__title">
            <a title="<?php echo $esc_post_title; ?>" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>">
                <span class="desktop_title"><?php echo remove_punctuation($post_titles['desktop']); ?></span>

				<?php if ($post_titles['mobile']) : ?>
                    <span class="mobile_title"><?php echo remove_punctuation($post_titles['mobile']); ?></span>
				<?php endif; ?>
            </a>
        </h2>
    </div>
</article>