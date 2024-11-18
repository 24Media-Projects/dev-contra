<?php

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract(btw_get_hp_group_fields());

$trending_topics_items = get_field( 'btw__group_fields__hp__template__trending_topics__terms' ) ?: [];


$keen_slider_settings = json_encode([
    'breakpoints' => [],
   'slides' => [
        'perView' => 'auto',
        'spacing' => 20,
   ],
   'mode' => 'free',
]);

?>

<div class="home_wrapper trending_topics__wrapper">
    <section  id="<?php echo $section_id; ?>" class="trending_topics">
        <h2 class="invisible">TRENDING NOW</h2>

        <div class="carusel_container loading">
            <ul class="carusel_container__slider keen-slider trending_hashtag"  data-settings="<?php echo esc_attr( $keen_slider_settings );?>">
                <li class="keen-slider__slide trending_topics_title">TRENDING NOW</li>
                <?php foreach( $trending_topics_items as $repeater_item ) : ?>
                    <?php if( $term = $repeater_item['term'] ): ?>
                        <li class="keen-slider__slide hashtag">
                            <a href="<?php echo get_term_link( $term ) ?>">
                                #<?php echo $term->name; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
    </section>
</div>