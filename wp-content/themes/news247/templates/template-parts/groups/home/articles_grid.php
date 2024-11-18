<?php
extract( btw_get_hp_group_fields() );

$section_classes = [
	'home_wrapper',
    'articles_grid',
//	'term_basic_section',
//	'tribute_basic__section',
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

if ( $section_lead ) {
	$section_classes[] = 'with_description';
}

if ( $bg_color ) {
	$style = "background-color: $bg_color";
	$section_classes[] = 'with_bg_color';
}
?>

<div class="articles_grid__wrapper" style="<?php echo $style ?? ''; ?>">
    <section  id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>">

		<?php echo btw_get_impressions_url($impression_url); ?>

        <div class="section_container">

            <?php
            btw_get_template_part( 'template-parts/group_header', [
                'section_title' => $section_title,
                'section_title_url' => $section_title_url,
				'related_links' => $related_links,
				'heading' => 'h2',
				'is_section_title_full_width' => $is_section_title_full_width,
            ]);
            ?>

            <?php if ( $section_lead ): ?>
                <div class="section_description">
                    <?php echo $section_lead; ?>
                </div>
            <?php endif; ?>

            <div class="article_container">
                <?php

				foreach( get_field( 'btw__group_fields__hp__template__articles_grid__featured_post_selection', $group_id ) ?: [] as $row ):

					$atf_post = new BTW_Atf_Post([
						'item' => $row,
						'primary_term' => $primary_term,
						'image_srcsets' => array(
							array(
								'image_size'   => 'medium_horizontal',
								'media_query'  => '(max-width: 1349px )',
							),
							array(
								'image_size'   => 'medium_horizontal',
								'media_query'  => '(max-width: 1023px )',
							),
							array(
								'image_size'   => 'large_square',
								'media_query'  => '(max-width: 767px )',
								'mobile'       => true,
							),
							array(
								'image_size'  => 'small_horizontal',
								'default'     => true,
							),
						),
						'render_attrs' => [
							'extra_class' => 'overlay_mobile',
						],
					]);

					$atf_post->render();

				endforeach;


				foreach( get_field( 'btw__group_fields__hp__template__articles_grid__posts_selection', $group_id ) ?: [] as $key => $row ):

                    $atf_post = new BTW_Atf_Post([
                        'item' => $row,
                        'primary_term' => $primary_term,
                        'image_srcsets' => array(
                            array(
                                'image_size'   => 'medium_horizontal',
                                'media_query'  => '(max-width: 767px )',
                                'mobile'       => true,
                            ),
							array(
								'image_size'   => 'medium_horizontal',
								'media_query'  => '(max-width: 1023px )',
							),
							array(
								'image_size'  => 'small_horizontal',
								'default'     => true,
							),
                        ),
                        'render_attrs' => [

                        ],
                    ]);

                    $atf_post->render();

                endforeach;
                ?>

            </div>
        </div>
      
    </section>

</div>