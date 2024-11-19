<?php
extract( btw_get_hp_group_fields() );

$happens_now_posts = get_field( 'btw__group_fields__hp__template__happens_now__post_selection' ) ?: [];

$keen_slider_settings = [
    'slides' => [
        'perView' => 1,
    ],
    'loop' => true,
    'breakpoints' => [],
];


?>
<div class="home_wrapper happening_now__wrapper">
    <section id="<?php echo $section_id; ?>" class="happening_now_section">

		<?php echo btw_get_impression_url($impression_url); ?>

        <h2 class="happening_now_title s-font-bold">Συμβαίνει Τώρα</h2>

        <div class="carusel_container loading">
            <div class="carusel_container__slider keen-slider with_dots with_arrows" data-autoplay="2500" data-settings="<?php echo esc_attr( json_encode($keen_slider_settings ) );?>">
                <?php foreach( $happens_now_posts as $post ) : ?>

                <article class="keen-slider__slide article happening_now_article">
                    <h3 class="post__title article-s-font">
                        <a title="title" href="<?php echo get_the_permalink( $post ); ?>">
                            <?php echo get_the_title( $post );?>
                        </a>
                    </h3>
                </article>

                <?php endforeach; ?>
            </div>
        </div>
    </section>
</div>



