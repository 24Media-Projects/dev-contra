<?php
$facebook = get_field('btw__brand_fields__facebook', 'option');
$instagram = get_field('btw__brand_fields__instagram', 'option');
$twitter = get_field('btw__brand_fields__twitter', 'option');
$youtube = get_field('btw__brand_fields__youtube', 'option');
$tiktok = get_field('btw__brand_fields__tiktok', 'option');
$linkedin = get_field('btw__brand_fields__linkedin', 'option');
$rss = get_field('btw__brand_fields__rss', 'option');
$telegram = get_field('btw__brand_fields__telegram', 'option');
$newsletter = get_field('btw__brand_fields__newsletter_url', 'option')
?>



<div class="global__header__main_menu">

    <div class="wrapper">

        <nav class="primary_nav">
            <?php wp_nav_menu([
                'theme_location' => 'primary_nav',
                'container' => false,
            ]); ?>
        </nav>

        <nav class="secondary_nav">
            <ul class="menu">
                <?php if ($facebook) { ?>
                    <li class="menu-item follow_us__link">
                        <a class="facebook" title="Ακολουθήστε μας στο Facebook" href="<?php echo $facebook; ?>" target="_blank">
                            <svg>
                                <use xlink:href="#icon-facebook"></use>
                            </svg>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($twitter) { ?>
                    <li class="menu-item follow_us__link">
                        <a class="twitter" title="Ακολουθήστε μας στο Twitter" href="<?php echo $twitter; ?>" target="_blank">
                            <svg>
                                <use xlink:href="#icon-twitter"></use>
                            </svg>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($instagram) { ?>
                    <li class="menu-item follow_us__link">
                        <a class="instagram" title="Ακολουθήστε μας στο Instagram" href="<?php echo $instagram; ?>" target="_blank">
                            <svg>
                                <use xlink:href="#icon-instagram"></use>
                            </svg>
                        </a>
                    </li>
                <?php } ?>
                <?php if ($newsletter) { ?>
                    <li class="menu-item newsletter__link">
                        <a title="Εγγραφή στο Newsletter" <?php maybe_print_target_blank($newsletter); ?> href="<?php echo $newsletter; ?>">
                            ΕΓΓΡΑΦΗ ΣΤΟ NEWSLETTER
                            <svg>
                                <use xlink:href="#icon-mail"></use>
                            </svg>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </nav>

    </div>

</div>