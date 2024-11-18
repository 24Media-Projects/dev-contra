<?php

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract(btw_get_hp_group_fields());
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

                <?php
				foreach( get_field( 'btw__group_fields__hp__template__opinions_carousel_by_author__posts_selection', $group_id ) ?: [] as $row ):

					$atf_post = new BTW_Atf_Post([
						'item' 				=> $row,
						'primary_term' 		=> $primary_term,
						'render_attrs' => [
                            'template_name' => 'opinion',
							'extra_class'   => 'keen-slider__slide',
							'lazyload'      => false,
						],

					]);

					$atf_post->render();

				endforeach;
                ?>
            </div>
        </div>

    </section>
</div>

