<?php
global $group_template;

extract($atf_post);

if( !in_array('above-the-fold-half-article', $container_classes) ){
	$bg_color = 'transparent';
}

if( $bg_color != 'transparent' ){
	$style = 'background-color: '. $bg_color;
}

$initial_class = null;


if ( $index < 1 ) {
    $initial_class = "active";
}

?>


<article class="keen-slider__slide slide-<?php echo $index + 1; ?> hover_title_magazine carusel_container__slider   <?php echo implode(' ', apply_filters( 'btw/atf_post/render/container_classes', $container_classes, $atf_post, $group_template ) ); ?> <?php echo $initial_class;?>" style="<?php echo $style ?? ''; ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <figure class="post__image img_hovered_magazine">
        <a class="clear post_img" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
            <div class="bg multi_bg_image lazyload"
                data-bg="<?php echo $attachment_background['desktop']; ?>"
                data-desktop-bg="<?php echo $attachment_background['desktop']; ?>"
                data-mobile-bg="<?php echo $attachment_background['mobile']; ?>">
            </div>
        </a>
    </figure>
    <div class="post__content">
        <div class="mag-xs-caption asty_bold article_captions">
			
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

        <?php if( $sponsor_logo ): ?>
            <div class="magazine_sponsor_logo">
                <?php if( $sponsor_click_url ) echo '<a href="' . $sponsor_click_url . '" aria-label="sponsor">'; ?>
                    <img src="<?php echo $sponsor_logo['url']; ?>" alt="<?php echo $sponsor_logo['alt']; ?>">
                <?php if( $sponsor_click_url ) echo '</a>'; ?>
            </div>
		<?php endif; ?>

        <h2 class="magazine_article_font post__title">
            <a title="<?php echo $esc_post_title; ?>" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>">
                <span class="desktop_title truncate"><?php echo remove_punctuation($post_titles['desktop']); ?></span>

				<?php if ($post_titles['mobile']) : ?>
                    <span class="mobile_title truncate"><?php echo remove_punctuation($post_titles['mobile']); ?></span>
				<?php endif; ?>
            </a>
        </h2>
    </div>
</article>