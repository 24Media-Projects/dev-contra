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

$videoPlayButton = get_stylesheet_directory_uri() . '/assets/img/icons/icon--play' . ($is_dark_mode ? '--darkmode' : '') . '.png?212';

?>

<style>
    table.<?php echo $section_id . '-' . $index; ?> a:hover {
        <?php echo $text_decoration_color; ?>
    }
</style>

<table class="<?php echo $section_id; ?> <?php echo $section_id . '-' . $index; ?>" cellpadding="0" cellspacing="0" border="0" style="padding:0 0 30px;">

    <tr>
        <td>

            <?php btw_get_impressions_url($impressions_url, true); ?>

            <?php if ($show_image && $image_id) : ?>
                <a class="clear post_img" href="<?php echo $post_link; ?>" style="display:block;font-size:0;" title="<?php echo $esc_post_title; ?>">
                    <img src="<?php echo $image_url; ?>" width="600" height="auto" alt="<?php echo $esc_post_title; ?>" style="display: block;width: 100%;height: auto;" />
                </a>
            <?php endif; ?>
        </td>
    </tr>

    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" <?php echo $bg_color; ?> width="100%" style="width:100%" >
                <tr>


                    <td class="mag-l-caption asty_bold article_captions" style="font: normal normal bold 14px/16px Arial;letter-spacing: 0px;<?php echo $color; ?>padding:26px 0 6px 20px;vertical-align: top;">
                        <?php
                        // <a> or plain text
                        $caption = str_replace('href=', 'style="text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px; ' . $color . '" href=', $caption);
                        echo apply_filters('btw/atf_post/render/caption', $caption, $atf_post);
                        ?>
                    </td>

                    <?php if ($supertitle) : ?>
                        <td align="right" style="font: normal normal normal 14px/16px Arial;letter-spacing: 0px;<?php echo $color; ?>padding:26px 20px 6px 0;white-space: nowrap;vertical-align: top;">

                            <?php
                            if( $supertitle_is_link ): ?>
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
                    <td colspan="2">
                        <h2 class="single_article__title mag-xl-article-title post__title article__title" style=" font: normal normal bold 24px/28px Georgia;letter-spacing: -0.24px;<?php echo $color; ?>margin:0;padding:0 20px 0;">
                            <a title="<?php echo $esc_post_title; ?>" href="<?php echo $post_link; ?> " style="text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px;<?php echo $color; ?>">

                                <?php if ($is_video) : ?>
                                    <img src="<?php echo $videoPlayButton; ?>" alt="Play Button" />
                                <?php endif; ?>

                                <?php echo $post_titles['desktop']; ?>
                            </a>
                        </h2>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="font: normal normal normal 16px/22px Arial;letter-spacing: 0px; <?php echo $color; ?> margin:0;<?php echo $lead ? 'padding:10px 20px 35px;' : 'padding:26px 0 0;' ?>">
                        <?php echo str_replace('<p>', '<p style="margin: 0;">', $lead); ?>
                    </td>
                </tr>

            </table>
        </td>
    </tr>



</table>