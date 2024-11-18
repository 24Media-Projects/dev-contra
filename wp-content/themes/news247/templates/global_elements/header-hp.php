<?php
$logo = get_field('btw__brand_fields__logo', 'option');

?>
<header class="global_header global_header_hp">
    <div class="wrapper">
        <div class="global_header__top">
            <button type="button" class="toggle_primary_menu" aria-label="Menu">
                <span class="invisible">Toggle Menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="burger-icon-svg" data-name="burger-icon-svg" viewBox="0 0 26 26">
                    <rect x="2" y="5" width="22" height="2" />
                    <rect x="2" y="12" width="22" height="2" />
                    <rect x="2" y="19" width="22" height="2" />
                </svg>
                <svg>
                    <use xlink:href="#icon-close"></use>
                </svg>
            </button>
            <figure class="global_header__top--logo">
                <a href="/">
                    <img src="<?php echo $logo['url']; ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="156" />
                </a>
            </figure>
            <div class="global_header__quick_links">
                <a title="Πρωτοσέλιδα" href="/protoselida" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-newspapers"></use>
                    </svg>
                    <span>Πρωτοσέλιδα</span>
                </a>
                <a title="Videos" href="/videos" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-video"></use>
                    </svg>
                    <span>Videos</span>
                </a>
                <a title="Podcasts" href="/podcasts/" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-podcast"></use>
                    </svg>
                    <span>Podcasts</span>
                </a>
                <button type="button" class="toggle_latest_posts" aria-label="Menu Latest News">
                    <span class="invisible">Ροή Ειδήσεων</span>
                    <svg>
                        <use xlink:href="#icon-latest-news"></use>
                    </svg>
                    <svg>
                        <use xlink:href="#icon-close"></use>
                    </svg>
                </button>
                <a title="Search" href="/?s=" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-search"></use>
                    </svg>
                </a>
            </div>
        </div>

        <div class="global_header__menu">
            <button type="button" class="toggle_side_menu" aria-label="Menu">
                <span class="invisible">Toggle Menu</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="burger-icon-svg" data-name="burger-icon-svg" viewBox="0 0 26 26">
                    <rect x="2" y="5" width="22" height="2" />
                    <rect x="2" y="12" width="22" height="2" />
                    <rect x="2" y="19" width="22" height="2" />
                </svg>
                <svg>
                    <use xlink:href="#icon-close"></use>
                </svg>
            </button>
            <figure class="global_header__logo">
                <a href="/">
                    <img src="<?php echo $logo['url']; ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="120" />
                </a>
            </figure>
            <nav class="primary_nav">
                <?php wp_nav_menu([
                    'theme_location' => 'home_primary_nav',
                    'container' => false,
                ]); ?>
            </nav>
            <div class="global_header__quick_links">
                <a title="Πρωτοσέλιδα" href="/protoselida" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-newspapers"></use>
                    </svg>
                </a>
                <a title="Videos" href="/videos" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-video"></use>
                    </svg>
                </a>
                <a title="Podcasts" href="/podcasts/" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-podcast"></use>
                    </svg>
                </a>
                <a title="Search" href="/?s=" class="global_header__quick_links--item">
                    <svg>
                        <use xlink:href="#icon-search"></use>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>