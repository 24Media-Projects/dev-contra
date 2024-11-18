<?php

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract(btw_get_hp_group_fields());

$opinion_query = new WP_Query([
    'post_type'      => 'post',
    'posts_per_page' => '12',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
    'tax_query'      => [
        [
            'field' => 'slug',
            'terms' => 'gnomes',
            'taxonomy' => 'category',
        ]
    ]
]);

?>


<div class="home_wrapper opinions__wrapper">

    <section  id="<?php echo $section_id; ?>" class="opinions_section section_carousel">

		<?php echo btw_get_impression_url($impression_url); ?>

        <?php
        btw_get_template_part('template-parts/group_header', [
            'section_title'     => $section_title,
            'section_title_url' => $section_title_url,
            'heading'           => 'h2',
        ]);
        ?>

        <div class="carusel_container loading">
            <div class="carusel_container__slider keen-slider with_dots with_arrows">

                <?php while( $opinion_query->have_posts() ): $opinion_query->the_post();

                    global $post;

                    $post_primary_category = btw_get_post_primary_category();
                    $post_titles = [
                        'desktop' =>  '<strong>' . get_the_title() . '</strong>',
                        'mobile'  => ''
                    ];

                    $container_classes = [ 'keen-slider__slide', 'article' ];
                    $wp_post = $post;

                    $caption = btw_get_primary_term_anchor_html( $post_primary_category );
                    $esc_post_title = esc_attr( wp_strip_all_tags( $post_titles['desktop'] ) );

                    $post_link = get_the_permalink();

                    $atf_post = compact( 'container_classes', 'wp_post', 'caption', 'post_titles', 'esc_post_title', 'post_link' );

                    btw_get_template_part( 'template-parts/post_content/opinion', [
                        'atf_post' => $atf_post,
                    ]);
                ?>


                <?php endwhile; ?>
                <?php wp_reset_query(); ?>
            </div>
        </div>

    </section>
</div>

