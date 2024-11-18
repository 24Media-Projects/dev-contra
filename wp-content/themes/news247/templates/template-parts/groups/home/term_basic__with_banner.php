<?php

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract( btw_get_hp_group_fields($group_id) );

?>

<div class="home_wrapper term_basic__wrapper term_basic___with_banner__wrapper">
    <section class="term_basic_section_with_banner term_basic_section" id="<?php echo $section_id; ?>">

		<?php echo btw_get_impression_url($impression_url); ?>

        <?php
        if ($show_term_basic_header ?? true) {
            btw_get_template_part('template-parts/group_header', [
                'section_title' => $section_title,
                'section_title_url' => $section_title_url,
                'related_links' => $related_links,
                'heading' => 'h2',
            ]);
        }
        ?>

        <div class="article_container">

            <?php
            /**
             * Featured Post
             * @see BTW_Atf_Post::get_atf_posts()
             */
            foreach (get_field('btw__group_fields__hp__template__term_basic__with_banner__featured_post_selection', $group_id) ?: [] as $row) :

                $atf_post = new BTW_Atf_Post([
                    'item'          => $row,
                    'primary_term'  => $primary_term,
                    'image_srcsets'    => array(
                        array(
                            'image_size'   => 'medium_landscape',
                            'media_query'  => '(max-width: 767px )',
                            'mobile'       => true,
                        ),
                        array(
                            'image_size'  => 'medium_landscape',
                            'default'     => true,
                        ),
                    ),
                    'render_attrs' => [
                        'article_font'  => 'l-article-l-font',
                        'article_type'  => [
                            'overlay' => 'large_article_overlay large_article',
                            'default' => 'large_article',
                        ],
                        'truncate' => 3,
                        'lazyload' => !is_category(),
                    ]
                ]);

                $atf_post->render();

            endforeach;
            ?>
            

            <?php
            /** 
             * Rest Posts
             * @see BTW_Atf_Post::get_atf_posts()
             */
            foreach (get_field('btw__group_fields__hp__template__term_basic__with_banner__posts_selection', $group_id) ?: [] as $row) :

                $atf_post = new BTW_Atf_Post([
                    'item'                 => $row,
                    'primary_term'         => $primary_term,
                    'image_srcsets'    => array(
                        array(
                            'image_size'   => 'small_square',
                            'media_query'  => '(max-width: 767px )',
                            'mobile'       => true,
                        ),
                        array(
                            'image_size'  => 'medium_horizontal',
                            'default'     => true,
                        ),
                    ),
                    'render_attrs' => [
                        'extra_class' => 'small_article_mobile',
                        'show_date'   => $show_date ?? false,
                        'lazyload' => !is_category(),
                    ],
                ]);

                $atf_post->render();

            endforeach;


            do_action( 'btw/group/term_basic__with_banner/after_posts', $group_id );

            ?>


            <?php if ($section_title_url) : ?>
                <div class="group_button mobile">
                    <a href="<?php echo $section_title_url; ?>" class="button more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button">
                        <span class="s-font-bold">ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
                        <svg>
                            <use xlink:href="#icon-back-to-top"></use>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

            <?php do_action( 'btw/group/term_basic__with_banner/ad_banner' ); ?>

        </div>


        <?php if ($section_title_url) : ?>
            <div class="group_button">
                <a href="<?php echo $section_title_url; ?>" class="button more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button">
                    <span class="s-font-bold">ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
                    <svg>
                        <use xlink:href="#icon-back-to-top"></use>
                    </svg>
                </a>
            </div>
        <?php endif; ?>

    </section>

</div>