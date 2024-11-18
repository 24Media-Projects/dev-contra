<?php
$facebook = get_field('btw__brand_fields__facebook', 'option');
$instagram = get_field('btw__brand_fields__instagram', 'option');
$twitter = get_field('btw__brand_fields__twitter', 'option');
$youtube = get_field('btw__brand_fields__youtube', 'option');
$tiktok = get_field('btw__brand_fields__tiktok', 'option');
$linkedin = get_field('btw__brand_fields__linkedin', 'option');
$rss = get_field('btw__brand_fields__rss', 'option');
$telegram = get_field('btw__brand_fields__telegram', 'option');
?>



<div class="side_nav_container" id="side_nav_container">
    <button aria-label="Close Menu" type="button" class="close_side_menu" on="tap:side_nav_container.toggleClass(class='active')">
        <svg>
            <use xlink:href="#icon-close"></use>
        </svg>
        <span class="invisible">Close Side Menu</span>
    </button>


    <div class="side_nav_container__inner">

        <form id="side_search_form" class="search_form" method="get" action="<?php echo site_url(); ?>">
            <input class="caption s-font searchInput" placeholder="Αναζήτηση" type="text" name="s" value="<?php echo get_search_query(); ?>">
            <div class="search_form_btn__container">
                <button type="button" aria-label="Close Search" class="clear--btn" on="tap:side_search_form.clear">
                    <svg>
                        <use xlink:href="#icon-close"></use>
                    </svg>
                </button>
                <button type="submit" class="submit--btn" aria-label="Sumbit">
                    <svg class="search_icon">
                        <use xlink:href="#icon-search"></use>
                    </svg>
                </button>
            </div>
        </form>

        <nav class="side_nav_main">
            <?php wp_nav_menu([
                'theme_location' => 'side_nav_main',
                'container' => false,
                // 'after'  => '<button type="button" class="menu-item__toggle-children"><svg><use xlink:href="#icon-arrow-dropdown-menu"></use></svg><span class="invisible">Close dropdown</span></button>',
            ]); ?>
        </nav>
        <nav class="side_nav_sec">
            <?php wp_nav_menu([
                'theme_location' => 'side_nav_sec',
                'container' => false,
                // 'after'  => '<button type="button" class="menu-item__toggle-children"><svg><use xlink:href="#icon-arrow-dropdown-menu"></use></svg><span class="invisible">Close dropdown</span></button>',
            ]); ?>
        </nav>

        <nav class="side_nav_sec">
            <ul class="menu">
                <li class="menu-item">
                    <a title="Εγγραφή στο newsletter" href="/newsletter/">Newsletter</a>
                </li>
            </ul>
        </nav>

        <nav class="side_nav_sec">
            <ul class="menu">
                <li class="menu-item follow_us">
                    <span>Follow News 24/7</span>
                    <ul class="sub-menu side-nav-social-container">
                        <?php if ($facebook) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="facebook" title="Ακολουθήστε μας στο Facebook" href="<?php echo $facebook; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-facebook"></use>
                                    </svg>
                                    <span>Facebook</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($instagram) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="instagram" title="Ακολουθήστε μας στο Instagram" href="<?php echo $instagram; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-instagram"></use>
                                    </svg>
                                    <span>Instagram</span>
                                </a>
                            </li>
                        <?php } ?>

                        <?php if ($twitter) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="twitter" title="Ακολουθήστε μας στο Twitter" href="<?php echo $twitter; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-twitter"></use>
                                    </svg>
                                    <span>Twitter</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($youtube) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="youtube" title="Ακολουθήστε μας στο Youtube" href="<?php echo $youtube; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-youtube"></use>
                                    </svg>
                                    <span>Youtube</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($tiktok) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="tiktok" title="Ακολουθήστε μας στο TikTok" href="<?php echo $tiktok; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-tiktok"></use>
                                    </svg>
                                    <span>TikTok</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($linkedin) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="linkedin" title="Ακολουθήστε μας στο Linkedin" href="<?php echo $linkedin; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-linkedin"></use>
                                    </svg>
                                    <span>Linkedin</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($rss) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="rss-feed" title="Ακολουθήστε μας στο RSS Feed" href="<?php echo $rss; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-rss-feed"></use>
                                    </svg>
                                    <span>RSS Feed</span>
                                </a>
                            </li>
                        <?php } ?>
                        <?php if ($telegram) { ?>
                            <li class="menu-item follow_us__link">
                                <a class="telegram" title="Ακολουθήστε μας στο Telegram" href="<?php echo $telegram; ?>" target="_blank">
                                    <svg>
                                        <use xlink:href="#icon-telegram"></use>
                                    </svg>
                                    <span>Telegram</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>