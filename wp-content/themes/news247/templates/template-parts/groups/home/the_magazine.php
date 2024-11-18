<?php

extract(btw_get_hp_group_fields());

$keen_slider_settings = json_encode([
    'loop' => true,
    'slides' => [
        'perView' => 'auto',
    ],
    'breakpoints' => [
        '(min-width: 1024px)' => [
            'selector' => 'null',
        ],
    ],
]);

$section_classes = [
    'home_wrapper',
    'magazine__section',
    'home_magazine_section',
    'carusel_container'
];

if( $sponsor_logo ){
	$section_classes[] = 'with_sponsor_logo';
}

if( !empty($sponsor_logo_alt) ){
	$section_classes[] = 'with_sponsor_logo_alt';
}

if( !$sponsor_logo && $section_supertitle){
	$section_classes[] = 'with_section_supertitle';
}

if ( $is_dark_mode ) {
	$section_classes[] = 'section_darkmode';
}

if ( $related_links ) {
	$section_classes[] = 'with_related_links';
}

if ( $bg_color ) {
	$style = "background-color: $bg_color";
}
?>

<div class="the_magazine__wrapper" style="<?php echo $style ?? ''; ?>">
    <section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>">
        <!-- <h2 class="invisible">Top Stories</h2> -->

        <?php echo btw_get_impression_url($impression_url); ?>

        <?php
		btw_get_template_part( 'template-parts/group_header', [
			'section_title' => $section_title,
			'section_title_url' => get_term_link(get_term(btw_get_magazine_category_id(), 'category'), 'category'),
			'related_links' => $related_links,
			'heading' => 'h2',
			'is_section_title_full_width' => $is_section_title_full_width,
		]);
        ?>

        <div class="carusel_container loading">
            <div class="article_container carusel_container__slider keen-slider" data-settings="<?php echo esc_attr( $keen_slider_settings );?>" data-autoplay="2500">
                <?php

                foreach( get_field( 'btw__group_fields__hp__template__the_magazine__posts_selection', $group_id) ?: [] as $key => $row ):

                    $atf_post = new BTW_Atf_Post_Magazine([
                        'item'                 => $row,
                        'section_id'           => $section_id,
                        'index'                => $key,
                        'primary_term'         => $primary_term,
                        'image_srcsets'    => array(
                            array(
                                'image_size'   => 'large_square',
                                'media_query'  => '(max-width: 1023px )',
                                'mobile'       => true,
                            ),
                            array(
                                'image_size'  => 'full',
                                'default'     => true,
                            ),
                        ),
                        'render_attrs' => [
							'template_name'    => 'atf_post_magazine__carousel',
                            'article_font'     => 'magazine_article_font',
                            'background_image' => true,
                            'extra_classes'    => 'keen-slider__slide',
                        ]

                    ]);



                    $atf_post->render();

                endforeach;
                ?>
            </div>
        </div>

    </section>
</div>