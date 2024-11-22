<?php
$facebook = get_field('btw__brand_fields__facebook', 'option');
$instagram = get_field('btw__brand_fields__instagram', 'option');
$twitter = get_field('btw__brand_fields__twitter', 'option');
$owner_logo = get_field('btw__brand_fields__owner_logo', 'option');
$owner_logo_img = $owner_logo['image'];
$owner_logo_link = $owner_logo['link'];
?>

<footer id="global__footer" class="global__footer">

    <div class="wrapper">

        <div class="global__footer--top">

            <nav class="footer__primary_nav">
                <?php wp_nav_menu([
                    'theme_location' => 'footer_nav',
                    'container' => false,
                ]); ?>
            </nav>

            <nav class="footer__social_nav">
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
                </ul>
            </nav>

        </div>

        <div class="global__footer--bottom">

            <div class="media24_ened_logos">

                <div>
                    <p>SITE ΤΟΥ ΟΜΙΛΟΥ 24MEDIA</p>
                    <?php if ($owner_logo_img) { ?>
                        <figure class="owner_logo">
                            <?php if ($owner_logo_link) { ?>
                                <a href="<?php echo $owner_logo_link; ?>" target="_blank" aria-label="Owner Logo">
                                    <img src="<?php echo $owner_logo_img['url']; ?>" alt="<?php echo $owner_logo_img['alt']; ?>" width="53">
                                </a>
                            <?php } else { ?>
                                <img src="<?php echo $owner_logo_img['url']; ?>" alt="<?php echo $owner_logo_img['alt']; ?>" width="53">
                            <?php } ?>
                        </figure>
                    <?php } ?>
                </div>

                <div>
                    <p>ΜΕΛΟΣ</p>
                    <a href="http://www.ened.gr/" target="_blank" aria-label="Ened">
                        <svg class="ened_logo">
                            <use xlink:href="#ened-logo"></use>
                        </svg>
                    </a>
                </div>
            </div>

            <p class="copyrights">
                <span>ΑΡΙΘΜΟΣ ΠΙΣΤΟΠΟΙΗΣΗΣ Μ.Η.Τ 232312</span>
                <span>&copy; <?php echo date("Y"); ?> CONTRA. ALL RIGHTS RESERVED.</span>
            </p>

        </div>

</footer>