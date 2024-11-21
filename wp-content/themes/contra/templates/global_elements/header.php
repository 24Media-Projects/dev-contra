<?php
$logo = get_field('btw__brand_fields__logo', 'option');

?>
<header id="global__header" class="global__header">

    <div class="wrapper">

        <figure class="global__header__logo">
            <a href="/">
                <img src="<?php echo $logo['url']; ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" width="125" />
            </a>
        </figure>

        <button type="button" aria-label="Toggle Menu" class="global__header__toggle_side_menu" on="tap:side_nav_container.toggleClass(class='active'),global_header.toggleClass(class='active')">
            <svg>
                <use xlink:href="#icon-burger"></use>
            </svg>
            <svg>
                <use xlink:href="#icon-close"></use>
            </svg>
        </button>

    </div>

    <?php
    get_template_part('templates/global_elements/main_menu');
    ?>



</header>