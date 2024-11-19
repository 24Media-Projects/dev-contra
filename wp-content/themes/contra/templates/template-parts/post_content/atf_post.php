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

global $group_template;

/**
 * Set truncate lines as data attribute, if requested
 */
$truncate_data_lines = $truncate ? 'data-truncate-lines="' . $truncate . '"' : '';


$bg_class = "no_bg_color";


if ( $bg_color != 'transparent' ) {
	$bg_class = "with_bg_color";
}

?>

<article class="<?php echo implode(' ', apply_filters( 'btw/atf_post/render/container_classes', $container_classes, $atf_post, $group_template ) ); ?> <?php echo $bg_class;?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <figure>
		<a class="clear post_img" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
			<?php echo apply_filters( 'btw/atf_post/render/attachment_html', $attachment_picture_html, $atf_post, $group_template ); ?>
		</a>
	</figure>

	<div class="post__content" style="background-color: <?php echo $bg_color ?>;">
		<h3 class="post__title <?php echo $article_font ?>">

			<a title="<?php echo $esc_post_title; ?>" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>">
				<span class="desktop_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['desktop']; ?></span>

				<?php if ($post_titles['mobile']) : ?>
					<span class="mobile_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['mobile']; ?></span>
				<?php endif; ?>
			</a>

		</h3>
		<div class="post__category">
			<h4 class="caption s-font-bold"><?php // <a> or plain text 
					echo apply_filters( 'btw/atf_post/render/caption', $caption, $atf_post, $group_template );?></h4>
					<?php if( $show_date ): ?>
						<span class="caption s-font">
							<?php echo get_the_date( 'd.m.Y, H:i', $wp_post ); ?>
						</span>
					<?php endif; ?>
					
					<?php if( $supertitle ): ?>
						<span class="sponsor s-font">
							<?php echo $supertitle ?>
						</span>
					<?php endif; ?>
		</div>
	</div>

</article>