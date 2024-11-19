<?php
$logo = get_field('btw__brand_fields__logo', 'option');
$owner_logo = get_field('btw__brand_fields__owner_logo', 'option');
$owner_logo_img = $owner_logo['image'];
$owner_logo_link = $owner_logo['link'];
?>

<footer class="global_footer">
    <div class="wrapper">
        <div class="footer-menu__container">
            <div class="container">
                <div class="footer-menu__logo">
                    <figure class="global_footer__logo">
                        <a href="/">
                            <img src="<?php echo $logo['url']; ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="131" />
                        </a>
                    </figure>
                </div>
                <div class="footer-menu__groups">
                    <div id="footer-menu__group_1" class="footer-menu__group menu-item-has-children">
                        <span class="footer_menu__button footer_menu__button--toggle_sub_menu" on="tap:footer-menu__group_1.toggleClass(class='open-sub-menu')">
                            ΑΘΛΗΜΑΤΑ
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                                <use xlink:href="#icon-minus"></use>
                            </svg>
                        </span>
                        <nav class="footer-menu__group-items">
							<?php wp_nav_menu([
								'theme_location' => 'footer_menu__sports',
								'container' => false,
							]); ?>
                        </nav>
                    </div>
                    <div id="footer-menu__group_2" class="footer-menu__group menu-item-has-children">
                        <span class="footer_menu__button footer_menu__button--toggle_sub_menu" on="tap:footer-menu__group_2.toggleClass(class='open-sub-menu')">
                            ΔΙΟΡΓΑΝΩΣΕΙΣ
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                                <use xlink:href="#icon-minus"></use>
                            </svg>
                        </span>
                        <nav class="footer-menu__group-items">
							<?php wp_nav_menu([
								'theme_location' => 'footer_menu__competitions',
								'container' => false,
							]); ?>
                        </nav>
                    </div>
                    <div id="footer-menu__group_3" class="footer-menu__group menu-item-has-children">
                        <span class="footer_menu__button footer_menu__button--toggle_sub_menu" on="tap:footer-menu__group_3.toggleClass(class='open-sub-menu')">
                            QUICKLINKS
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                                <use xlink:href="#icon-minus"></use>
                            </svg>
                        </span>
                        <nav class="footer-menu__group-items">
							<?php wp_nav_menu([
								'theme_location' => 'footer_menu__quicklinks',
								'container' => false,
							]); ?>
                        </nav>
                    </div>
                    <div id="footer-menu__group_4" class="footer-menu__group menu-item-has-children">
                        <span class="footer_menu__button footer_menu__button--toggle_sub_menu" on="tap:footer-menu__group_4.toggleClass(class='open-sub-menu')">
                            FOLLOW SPORT 24
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                                <use xlink:href="#icon-minus"></use>
                            </svg>
                        </span>
                        <nav class="footer-menu__group-items footer-menu__group-items__social">
							<?php get_template_part('templates/global_elements/main_menu/social_media'); ?>
                        </nav>
                    </div>
                    <div id="footer-menu__group_5" class="footer-menu__group menu-item-has-children">
                        <span class="footer_menu__button footer_menu__button--toggle_sub_menu" on="tap:footer-menu__group_5.toggleClass(class='open-sub-menu')">
                            MOBILE APPLICATIONS
                            <svg class="icon">
                                <use xlink:href="#icon-plus"></use>
                                <use xlink:href="#icon-minus"></use>
                            </svg>
                        </span>
                        <nav class="footer-menu__group-items footer-menu__group-items__mobile_apps">
							<?php wp_nav_menu([
								'theme_location' => 'footer_menu__mobile_apps',
								'container' => false,
							]); ?>
                        </nav>
                    </div>
                </div>
                <div class="footer-menu__id-links">
                    <nav class="footer_nav">
						<?php wp_nav_menu([
							'theme_location' => 'footer_menu__rest',
							'container' => false,
						]); ?>
                    </nav>
                </div>
            </div>
        </div>
        <div class="footer-menu__bottom">
            <div class="container">


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
                <div class="footer-menu__network-links">
                    <nav class="footer_nav">
						<?php wp_nav_menu([
							'theme_location' => 'footer_menu__properties',
							'container' => false,
						]); ?>
                    </nav>
                </div>
                <span class="footer-menu__copyright">COPYRIGHT &copy; <?php echo date("Y"); ?> SPORT24 ALL RIGHTS RESERVED</span>
            </div>
        </div>
</footer>