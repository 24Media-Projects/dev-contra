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
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@200;700;800&display=swap" rel="stylesheet">

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

    <?php if( is_front_page() ): ?>
        <meta http-equiv="refresh" content="300" />
    <?php endif; ?>

    <?php if( is_tag() || is_category() ) : ?>
        <meta http-equiv="refresh" content="500" />
    <?php endif; ?>

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
        })(window, document, 'script', 'dataLayer', 'GTM-KMFBL5VB');
    </script>
    <!-- End Google Tag Manager -->

    <?php get_template_part('templates/dfp/dfp_header'); ?>

    <?php /*
    <script type="text/javascript">
        __tcfapi('addEventListener', 2, function(tcData, success) {
            if (success && tcData.gdprApplies) {
                if (tcData.eventStatus === 'tcloaded' || tcData.eventStatus === 'useractioncomplete') {

                    googletag.cmd.push(function() {
                        googletag.pubads().refresh();
                    });

                }

            }
        });
    </script>
    <?php */ ?>

    <?php wp_head(); ?>

    <!-- Defaults needed for liveblog -->
    <script>
        const liveBlogVersion = '1.47';
        window.targettingValues = {};
    </script>

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

    <!-- ParselyPosts -->
    <script>
        var ParselyPosts = window.ParselyPosts;
    </script>

    <?php endif; ?>

</head>


<body <?php body_class(); ?> itemscope>

    <?php get_template_part('templates/global_elements/news247-icons'); ?>


    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KMFBL5VB" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="global_wrapper">
        <?php

        btw_get_template_part('template-parts/ads/dfp', [
            'slot_id' => is_front_page() ? 'hp_prestitial' : 'ros_prestitial',
            'container_class' => ['prestitial'],
        ]);

        if( empty($args['hide_header'] ) ){
            if (is_front_page()) {
                get_template_part('templates/global_elements/header-hp');
            } elseif (btw_is_magazine()) {
                get_template_part('templates/global_elements/header-magazine');
            } else {
                get_template_part('templates/global_elements/header');
            }
        }



        if( empty($args['hide_header'] ) ){
            get_template_part('templates/global_elements/side_navigation');
        }
