<?php
/**
 * Set truncate lines as data attribute, if requested
 */
$truncate_data_lines = $truncate ? 'data-truncate-lines="' . $truncate . '"' : '';

?>

<article class="<?php echo implode(' ', $container_classes); ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <figure>
        <a class="clear post_img" <?php maybe_print_target_blank($post_link); ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
			<?php echo $attachment_picture_html; ?>
        </a>

		<?php if( $footer_image || $footer_text ): ?>
			<?php if( $footer_image ): ?>
                <img src="<?php echo $footer_image['url']; ?>" alt="<?php echo $footer_image['alt']; ?>" />
			<?php endif; ?>
			<?php if( $footer_text ): ?>
				<?php echo $footer_text; ?>
			<?php endif; ?>
		<?php endif; ?>

    </figure>

    <div class="post__content">
        <h3 class="post__title">

            <a title="<?php echo $esc_post_title; ?>" <?php maybe_print_target_blank($post_link); ?> href="<?php echo $post_link; ?>">
                <span class="desktop_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['desktop']; ?></span>

				<?php if ($post_titles['mobile']) : ?>
                    <span class="mobile_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['mobile']; ?></span>
				<?php endif; ?>
            </a>

        </h3>

		<?php if($caption): ?>
            <div class="post__category">
                <h4 class="caption s-font-bold">
					<?php echo $caption; // <a> or plain text ?>
                </h4>
            </div>
		<?php endif; ?>

        <div class="author">
			<?php echo $author_html; ?>
        </div>

    </div>

</article>