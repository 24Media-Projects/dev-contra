<?php

extract($atf_post);

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

<table class="<?php echo $section_id; ?> <?php echo $section_id . '-' . $index; ?>" cellpadding="0" cellspacing="0" border="0" style="width:100%;padding:0 0 30px;">

    <tr>
        <td align="center" bgcolor="#000000" background="<?php echo $image_url; ?>" valign="top" style="background: linear-gradient(0deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0) 100%), url('<?php echo $image_url; ?>') center / cover no-repeat #000000;display: block;width: 100%;padding-top:48%;">

			<?php btw_get_impressions_url($impressions_url, true); ?>

            <table cellpadding="0" cellspacing="0" border="0" style="width:100%;height:100%;">
                <tr>
                    <td valign="bottom">
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tbody>
                                <tr>
                                    <td class="mag-l-caption asty_bold article_captions" style="font: normal normal bold 14px/16px Arial;letter-spacing: 0px;<?php echo $color; ?>padding:0px 0 6px 20px;">
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
                                        <h2 class="single_article__title mag-xl-article-title post__title article__title" style=" font: normal normal bold 24px/28px Georgia;letter-spacing: -0.24px;<?php echo $color; ?>margin:0;padding:0 20px 34px;">
                                            <a title="<?php echo $esc_post_title; ?>" href="<?php echo $post_link; ?>" style="text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px;<?php echo $color; ?>">
                                                <?php echo $post_titles['desktop']; ?>
                                            </a>
                                        </h2>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>