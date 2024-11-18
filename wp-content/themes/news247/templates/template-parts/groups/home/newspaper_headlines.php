<?php

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract(btw_get_hp_group_fields());

global $post;

$newspapers_hp_posts = get_post_meta($post->ID, 'newspapers_hp_posts', true);

if( !$newspapers_hp_posts ){
    return;
}


?>
<div class="home_wrapper newspappers__wrapper">
    <section id="<?php echo $section_id; ?>" class="newspappers_section section_carousel">

		<?php
		btw_get_template_part('template-parts/group_header', [
			'section_title'     => $section_title,
			'section_title_url' => $section_title_url,
			'heading'           => 'h2',
		]);
		?>

        <div class="carusel_container loading">
            <div class="newspappers_container carusel_container__slider keen-slider with_dots with_arrows">

                <?php foreach( $newspapers_hp_posts as $post ): ?>

                    <div class="newspaper_item keen-slider__slide">
                        <figure>
                            <a title="<?php echo esc_attr( $post['title'] );?>" href="<?php echo $post['url'];?>">
                                <img decoding="async" loading="lazy" class="lazyload" data-src="<?php echo $post['imgUrl'];?>" alt="<?php echo esc_attr( $post['title'] );?>" />
                            </a>
                        </figure>
                    </div>

                <?php endforeach; ?>

            </div>
    </section>
</div>