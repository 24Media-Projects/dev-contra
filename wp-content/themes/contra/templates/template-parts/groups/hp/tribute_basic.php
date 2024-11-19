<?php

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract( btw_get_hp_group_fields($group_id) );

$section_classes = [
    'home_wrapper',
    'term_basic_section',
    'tribute_basic__section'
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


if ( ($show_term_basic_header ?? true) && $bg_color ) {
    $style = "background-color: $bg_color";
}
?>
<div class="term_basic__wrapper tribute_basic__wrapper" style="<?php echo $style ?? ''; ?>">

    <section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>">

		<?php echo btw_get_impression_url($impression_url); ?>

        <?php
		if( $show_term_basic_header ?? true ) {
			btw_get_template_part('template-parts/group_header', [
				'section_title' => $section_title,
				'section_title_url' => $section_title_url,
				'related_links' => $related_links,
				'heading' => 'h2',
				'is_section_title_full_width' => $is_section_title_full_width,
			]);
		}




        if ( $section_lead ):
        ?>

        <div class="section_description">
            <?php echo $section_lead; ?>
        </div>
        <?php endif;
        ?>


        <div class="article_container">
            <?php
            /**
             * Featured Post
             * @see BTW_Atf_Post::get_atf_posts()
             */
            foreach( get_field( 'btw__group_fields__hp__template__tribute_basic__featured_post_selection', $group_id ) ?: [] as $row ):

                $atf_post = new BTW_Atf_Post([
                    'item'          => $row,
                    'primary_term'  => $primary_term,
                    'image_srcsets'	=> array(
                        array(
                            'image_size'  => 'medium_landscape',
                            'media_query' => '(max-width: 767px )',
                            'mobile'      => true,
                        ),
                        array(
                            'image_size'  => 'medium_landscape',
                            'default'     => true,
                        ),
                    ),
                    'render_attrs'  => [
                        'article_font' => 'l-article-l-font',
                        'article_type' => [
                            'overlay' => 'large_article_overlay large_article',
                            'default' => 'large_article',
                        ],
						'truncate' => 3,
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
            foreach( get_field( 'btw__group_fields__hp__template__tribute_basic__posts_selection', $group_id ) ?: [] as $row ):

                $atf_post = new BTW_Atf_Post([
                    'item' 				=> $row,
                    'primary_term' 		=> $primary_term,
                    'image_srcsets'	=> array(
                        array(
                            'image_size'   => 'small_square',
                            'media_query'  => '(max-width: 767px )',
                            'mobile'	   => true,
                        ),
                        array(
                            'image_size'  => 'medium_horizontal',
                            'default'     => true,
                        ),
                    ),
                    'render_attrs' => [
                        'extra_class' => 'small_article_mobile',
                        'show_date'   => $show_date ?? false,
                    ]

                ]);

                $atf_post->render();

            endforeach;
            ?>
        </div>

        <?php if( $section_title_url ): ?>
        <div class="group_button">
            <a href="<?php echo $section_title_url;?>" class="button more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button">
                <span class="s-font-bold">ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
                <svg>
                    <use xlink:href="#icon-back-to-top"></use>
                </svg>
            </a>
        </div>


        <style type="text/css">
            <?php
            if ( $bg_color ) {?>
            #<?php echo $section_id; ?> .group_button .more_posts {
                background-color: <?php echo $bg_color;?>
            }


            #<?php echo $section_id; ?>.section_darkmode .group_button .more_posts {
                color: white;
            }

            #<?php echo $section_id; ?>.section_darkmode .group_button .more_posts svg {
                fill: white;
            }

            #<?php echo $section_id; ?> .group_button .more_posts:hover {
                color: rgba(0,0,0,0.6);
            }

            #<?php echo $section_id; ?> .group_button .more_posts:hover svg {
                fill: rgba(0,0,0,0.6);
            }


            #<?php echo $section_id; ?>.section_darkmode .group_button .more_posts:hover {
                color: rgba(255,255,255,0.6);
            }

            #<?php echo $section_id; ?>.section_darkmode .group_button .more_posts:hover svg {
                fill: rgba(255,255,255,0.6);
            }
            <?php } ?>

        </style>

        <?php endif; ?>

    </section>
</div>

<!--
<style type="text/css">
    .term_basic_section__wrapper.home_wrapper__<?php echo $section_id; ?> {
        background-color: <?php echo $bg_color ?>;
    }
</style>
-->




