<?php
/**
 * Set truncate lines as data attribute, if requested
 */
$truncate_data_lines = $truncate ? 'data-truncate-lines="' . $truncate . '"' : '';

?>

<article class="<?php echo implode(' ', $container_classes); ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <?php if($caption): ?>
        <div class="article_card__caption">
            <?php echo $caption; // <a> or plain text ?>
        </div>
    <?php endif; ?>

    <figure class="article_card__image">
        <a <?php maybe_print_target_blank($post_link); ?> href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
			<?php echo $attachment_picture_html; ?>
        </a>
    </figure>

    <div class="article_card__content">

        <h2 class="article_card__title">

            <a title="<?php echo $esc_post_title; ?>" <?php maybe_print_target_blank($post_link); ?> href="<?php echo $post_link; ?>">
                <span class="desktop_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['desktop']; ?></span>

				<?php if ($post_titles['mobile']) : ?>
                    <span class="mobile_title truncate" <?php echo $truncate_data_lines;?>><?php echo $post_titles['mobile']; ?></span>
				<?php endif; ?>
            </a>

        </h2>

        <h3 class="article_card__author">
            <?php echo $author_html; ?>
        </h3>

		<?php if ($post_date): ?>
            <span class="article_card__date"><?php echo btw_format_datetime($post_date); ?></span>
		<?php endif; ?>

    </div>

</article>