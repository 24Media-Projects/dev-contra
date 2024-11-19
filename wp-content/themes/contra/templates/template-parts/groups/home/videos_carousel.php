<?php

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract(btw_get_hp_group_fields());

$keen_slider_settings = json_encode([
    'breakpoints' => [
        '(min-width: 768px)' => [
            'slides' => [
                'perView' => 'auto',
                'autoPerView' => 2,
            ]
        ],
        '(min-width: 1280px)' => [
            'slides' => [
                'perView' => 'auto',
                'autoPerView' => 3,
            ]
        ]
    ],
]);


?>


<div class="home_wrapper videos__wrapper">

    <section id="<?php echo $section_id; ?>" class="videos_section section_carousel">

		<?php echo btw_get_impression_url($impression_url); ?>

        <?php
        btw_get_template_part('template-parts/group_header', [
            'section_title'     => $section_title,
            'section_title_url' => $section_title_url,
            'heading'           => 'h2',
        ]);
        ?>

        <div class="carusel_container loading">
            <div class="carusel_container__slider keen-slider with_dots with_arrows" data-settings="<?php echo esc_attr( $keen_slider_settings );?>">
                <?php

				foreach( get_field( 'btw__group_fields__hp__template__videos_carousel__featured_video_selection', $group_id ) ?: [] as $key => $row ):

					$atf_post = new BTW_Atf_Post([
						'item' 				=> $row,
						'primary_term' 		=> $primary_term,
                        'image_srcsets' => array(
                            array(
                                'image_size'   => 'medium_square',
                                'media_query'  => '(max-width: 767px )',
                                'mobile'       => true,
                            ),
                            array(
                                'image_size'  => 'medium_horizontal',
                                'default'     => true,
                            ),
                        ),
						'render_attrs' => [
                            'article_type' => 'overlay_article',
							'extra_class' => ['keen-slider__slide', 'play_article'],
						],
					]);

					$atf_post->render();

				endforeach;

                foreach( get_field( 'btw__group_fields__hp__template__videos_carousel__videos_selection', $group_id ) ?: [] as $key => $row ):

                    $atf_post = new BTW_Atf_Post([
                        'item' 				=> $row,
                        'primary_term' 		=> $primary_term,
						'image_srcsets' => array(
                            array(
                                'image_size'   => 'medium_square',
                                'media_query'  => '(max-width: 767px )',
                                'mobile'       => true,
                            ),
                            array(
                                'image_size'  => 'medium_square',
                                'default'     => true,
                            )
						),
                        'render_attrs' => [
                            'article_type' => 'overlay_article',
                            'img_type' => 'square',
                            'extra_class'  => 'keen-slider__slide',
                        ],
                    ]);

                    $atf_post->render();

                endforeach;
                ?>
            </div>
        </div>

    </section>
</div>

