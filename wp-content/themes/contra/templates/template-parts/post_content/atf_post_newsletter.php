<?php

extract($atf_post);

if ($bg_color) {
    $bg_color = 'bgcolor="' . $bg_color . '"';
}

$color = 'color: black;';
$text_decoration_color = 'text-decoration-color:black!important';
if ($is_dark_mode) {
    $color = 'color: white;';
    $text_decoration_color = 'text-decoration-color:white!important';
}

?>

<style>
    table.<?php echo $section_id . '-' . $index; ?> a:hover {
        <?php echo $text_decoration_color; ?>
    }
</style>

<table class="<?php echo $section_id; ?> <?php echo $section_id . '-' . $index; ?>" cellpadding="0" cellspacing="0" border="0" style="<?php if (!$hide_border_bottom) echo 'border-bottom:1px solid #0000004D;'; ?>">

    <tr>
        <td>

			<?php btw_get_impressions_url($impressions_url, true); ?>

            <?php if ($show_image && $image_id) : ?>
                <a class="clear post_img" style="display:block;font-size:0;" href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>">
                    <img src="<?php echo $image_url; ?>" width="600" height="auto" alt="<?php echo $esc_post_title; ?>" style="display: block;width: 100%;height: auto;" />
                    <!-- <img width="600" src="https://www.contra.gr/img/9057/9527773/318000/we990/990/site_thumbnail.jpg" title="Πεθαίνοντας στα βουνά της Ελλάδας - Μια μαρτυρία γροθιά στο στομάχι" alt="Πεθαίνοντας στα βουνά της Ελλάδας - Μια μαρτυρία γροθιά στο στομάχι"> -->
                </a>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" <?php echo $bg_color; ?>>
                <tr>
                    <td class="mag-l-caption asty_bold article_captions" style="font: normal normal bold 14px/16px Arial;letter-spacing: 0px;<?php echo $color; ?>padding:26px 0 6px;">
                        <?php
                        // <a> or plain text
                        $caption = str_replace('href=', 'style="text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px; ' . $color . '" href=', $caption);
                        echo apply_filters('btw/atf_post/render/caption', $caption, $atf_post);
                        ?>
                    </td>

					<?php if ($supertitle) : ?>
                        <td align="right">

							<?php if( $supertitle_is_link ): ?>
                                <a title="<?php echo $esc_post_title; ?>" href="<?php echo $post_link; ?>" style="text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px;<?php echo $color; ?>">
                                    <span><?php echo $supertitle; ?></span>
                                </a>
							<?php else: ?>
                                <span><?php echo $supertitle; ?></span>
							<?php endif; ?>

                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/icons/icon-arrow-newsletter.png" alt="Newsletter Arrow" width="5" height="8" />

                        </td>
					<?php endif; ?>
                </tr>
                <tr>
                    <td>
                        <h2 class="single_article__title mag-xl-article-title post__title article__title" style="font: <?php echo $title_font ?: 'normal normal bold 20px/24px Georgia;letter-spacing: -0.24px'; ?>;<?php echo $color; ?>margin:0;">
                            <a title="<?php echo $esc_post_title; ?>" href="<?php echo $post_link; ?>" style="<?php echo $color; ?>text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px;">
                                <?php echo $post_titles['desktop']; ?>
                            </a>
                        </h2>
                    </td>
                </tr>
                <tr>
                    <td style="font: normal normal normal 16px/22px Arial;letter-spacing: 0px; <?php echo $color; ?> margin:0;padding:10px 0 30px;">
                        <?php echo str_replace(['<p>', '</p>'], '', $lead); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>



</table>