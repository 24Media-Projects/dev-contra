<?php
$owner_logo = get_field('btw__brand_fields__owner_logo', 'option');
$owner_logo_img = $owner_logo['image'];
$owner_logo_link = $owner_logo['link'];


$facebook = get_field('btw__brand_fields__facebook', 'option');
$instagram = get_field('btw__brand_fields__instagram', 'option');
$twitter = get_field('btw__brand_fields__twitter', 'option');
$youtube = get_field('btw__brand_fields__youtube', 'option');
$tiktok = get_field('btw__brand_fields__tiktok', 'option');
$linkedin = get_field('btw__brand_fields__linkedin', 'option');
$rss = get_field('btw__brand_fields__rss', 'option');
$telegram = get_field('btw__brand_fields__telegram', 'option');
?>

<footer class="global_footer <?php if (btw_is_magazine()) echo 'magazine_footer'; ?>">
    <div class="wrapper">
        <div class="global_footer__col" id="global_footer__col--news247">
            <div class="global_footer__col_section">
                <h3>News24/7</h3>
                <button type="button" aria-label="Close Dropdown" class="menu-item__toggle-children" on="tap:global_footer__col--news247.toggleClass(class='open-sub-menu')"><svg>
                        <use xlink:href="#icon-arrow-dropdown-menu"></use>
                    </svg><span class="invisible">Close dropdown</span>
                </button>
            </div>
            <div class="footer_nav_container">
                <nav class="footer_nav">
                    <?php wp_nav_menu([
                        'theme_location' => 'footer_col_1',
                        'container' => false,
                    ]); ?>
                </nav>
                <nav class="footer_nav">
                    <?php wp_nav_menu([
                        'theme_location' => 'footer_col_2',
                        'container' => false,
                    ]); ?>
                </nav>
            </div>
        </div>
        <div class="global_footer__col" id="global_footer__col--info">
            <div class="global_footer__col_section">
                <h3>Info</h3>
                <button type="button" aria-label="Close Dropdown" class="menu-item__toggle-children" on="tap:global_footer__col--info.toggleClass(class='open-sub-menu')"><svg>
                        <use xlink:href="#icon-arrow-dropdown-menu"></use>
                    </svg><span class="invisible">Close dropdown</span>
                </button>
            </div>
            <nav class="footer_nav">
                <?php wp_nav_menu([
                    'theme_location' => 'footer_info',
                    'container' => false,
                ]); ?>
            </nav>
        </div>
        <div class="global_footer__col" id="global_footer__col--follow">
            <div class="global_footer__col_section">
                <h3>Follow News24/7</h3>
                <button type="button" aria-label="Close Dropdown" class="menu-item__toggle-children" on="tap:global_footer__col--follow.toggleClass(class='open-sub-menu')"><svg>
                        <use xlink:href="#icon-arrow-dropdown-menu"></use>
                    </svg><span class="invisible">Close dropdown</span>
                </button>
            </div>
            <nav class="footer_nav follow_us">
                <ul class="menu">
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
            </nav>
        </div>
        <div class="global_footer__col" id="global_footer__col--network">
            <div class="global_footer__col_section">
                <h3>24 Media Network</h3>
                <button type="button" aria-label="Close Dropdown" class="menu-item__toggle-children" on="tap:global_footer__col--network.toggleClass(class='open-sub-menu')"><svg>
                        <use xlink:href="#icon-arrow-dropdown-menu"></use>
                    </svg><span class="invisible">Close dropdown</span>
                </button>
            </div>
            <nav class="footer_nav">
                <?php wp_nav_menu([
                    'theme_location' => 'footer_24media_network',
                    'container' => false,
                ]); ?>
            </nav>
        </div>
        <div class="global_footer__col">
            <h3>Newsletter</h3>
            <a class="newsletter_subscribe_footer" href="/newsletter/">ΕΓΓΡΑΦΗ</a>
        </div>
    </div>
    <div class="owner">
        <div class="owner-info">
            <?php if ($owner_logo_img) { ?>
                <figure class="owner_logo">
                    <?php if ($owner_logo_link) { ?>
                        <a href="<?php echo $owner_logo_link; ?>">
                            <img src="<?php echo $owner_logo_img['url']; ?>" alt="<?php echo $owner_logo_img['alt']; ?>" width="55">
                        </a>
                    <?php } else { ?>
                        <img src="<?php echo $owner_logo_img['url']; ?>" alt="<?php echo $owner_logo_img['alt']; ?>" width="55">
                    <?php } ?>
                </figure>
            <?php } ?>
            <div class="copyrights">
            <p>SITE ΤΟΥ ΟΜΙΛΟΥ 24MEDIA<br> &copy; <?php echo date("Y"); ?> News24/7 <br> ALL RIGHTS RESERVED.<span>ΑΡΙΘΜΟΣ ΠΙΣΤΟΠΟΙΗΣΗΣ Μ.Η.Τ 242074</span><br><span>ΜΕΛΟΣ</span></p>
                <svg class="ened_logo">
                    <use xlink:href="#ened-logo"></use>
                </svg>
            </div>
        </div>
        <div class="scroll-to-top-container">
            <span>Back to top</span>
            <button type="button" aria-label="Back To Top" class="scroll-to-top--btn" on="tap:global_body.scrollTo(duration=200)">
                <svg>
                    <use xlink:href="#icon-back-to-top"></use>
                </svg>
            </button>
        </div>
    </div>
</footer>