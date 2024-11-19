<?php
$logo = get_field('btw__brand_fields__logo', 'option');

?>
<header class="global_header magazine_header" id="global_header">
    <div class="wrapper">
        <button type="button" aria-label="Menu" class="toggle_side_menu" on="tap:side_nav_container.toggleClass(class='active'),global_header.toggleClass(class='active')">
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
                <img src="<?php echo $logo['url']; ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="156" />
            </a>
        </figure>
        <div class="global_header__quick_links">
            <button type="button" aria-label="Menu Latest News" class="toggle_latest_posts" on="tap:latest_news_header.toggleClass(class='active'),global_header.toggleClass(class='menuRoi_active')">
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
    <div class="global_header_magazine__logo">
        <a href="/magazine" aria-label="Magazine">
            <svg>
                <use xlink:href="#magazine_logo"></use>
            </svg>
        </a>
    </div>
</header>