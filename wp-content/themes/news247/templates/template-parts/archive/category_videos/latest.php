<?php

/**
 * Available strings
 * 
 * 
 */

$videos = new WP_Query([
    'post_type'            => 'video',
    'post_status'          => 'publish',
    'posts_per_page'       => 24,
    'orderby'              => 'date',
    'order'                => 'DESC',
]);

?>

<div class="latest_videos">
    <h3 class="section_subtitle">LATEST</h3>

    <div class="carusel_container loading">
        <div class="carusel_container__slider keen-slider with_dots with_arrows">

            <?php

            while( $videos->have_posts()) : $videos->the_post();

                btw_get_template_part('template-parts/archive/post', [
                    'container_class' => [ 'keen-slider__slide' ],
                    'image_srcsets' => array(
                        array(
                            'image_size'  => 'small_horizontal',
                            'default'     => true,
                        ),
                    ),
                ]);
            endwhile;
            wp_reset_query(); ?>
        </div>
    </div>
</div>