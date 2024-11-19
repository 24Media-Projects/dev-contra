<?php extract( $atf_post ); ?>

<article class="<?php echo implode(' ', $container_classes ) ?> big_story">

	<?php btw_get_impressions_url($impressions_url); ?>

    <div class="post__content">
        <h3 class="post__title big_story_title">

            <a title="<?php echo esc_attr( $post_titles['desktop'] ); ?>" <?php if( strpos($post_link, site_url()) !== 0 ) echo 'target="_blank"'; ?> href="<?php echo $post_link; ?>">
                <span class="desktop_title"><?php echo remove_punctuation( $post_titles['desktop'] ); ?></span>

                <?php if( $post_titles['mobile'] ): ?>
                <span class="mobile_title"><?php echo remove_punctuation( $post_titles['mobile'] ); ?></span>
                <?php endif; ?>
            </a>

        </h3>

        <?php if( $lead = get_field( 'btw__global_fields__lead', $wp_post ) ): ?>
            <h4 class="post__lead aricle_lead_font">
                <?php echo $lead ?>
            </h4>
        <?php endif ?>
        <div class="post__category">
            <h4 class="caption s-font-bold">
                <?php echo $caption; // <a> or plain text ?>
            </h4>

            <?php if( $supertitle ) : ?>
            <span class="sponsor s-font"><?php echo $supertitle ?></span>
            <?php endif; ?>

        </div>
    </div>
</article>