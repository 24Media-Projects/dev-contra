<!DOCTYPE html>
<!--[if IE 8]>
    <html class="ie ie8 lt-ie9" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1"/>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title><?php wp_title('|', true, 'right'); ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">


    <!--[if lt IE 9]>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv-printshiv.js"></script>
        <![endif]-->

    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'TODO'); // TODO
    </script>
    <!-- End Google Tag Manager -->

    <?php get_template_part('templates/dfp/dfp_header'); ?>


    <?php wp_head(); ?>


    <!-- Truncate -->
    <script>
        <?php btw_get_template_js('front-end/truncate.min'); ?>

        var truncate = new Truncate('.truncate', {
            maxLines: 4,
            logs: false,
        });
    </script>

    <?php if( !is_front_page() ): ?>

    <script>
        var PostsFromApi = window.PostsFromApi;
    </script>


    <?php endif; ?>

</head>


<body <?php body_class(); ?> itemscope>

    <?php get_template_part('templates/global_elements/contra-icons'); ?>


    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=TODO" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <?php

        if( empty($args['hide_header'] ) ){
            get_template_part('templates/global_elements/header');
        }
    ?>

    <div class="global_wrapper">



