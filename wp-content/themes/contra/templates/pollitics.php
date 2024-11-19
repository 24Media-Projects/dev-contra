<?php
// Template Name: Pollitics
get_header();

while (have_posts()) : the_post();


?>

    <div class="category__wrapper category__wrapper_pollitics">


        <script defer="defer" src="https://poll-of-polls-stage.24media.gr/bb664c2d5e94331626d8/index.637f9a8c525869eef292.bundle.js"></script>
        <link href="https://poll-of-polls-stage.24media.gr/bb664c2d5e94331626d8/assets/styles/main.af5796c39d6c699a893d.css" rel="stylesheet" />

        <h2 class="invisible"><?php the_title(); ?></h2>

        <figure class="pollitics__logo">
            <svg>
                <use xlink:href="#pollitics_logo"></use>
            </svg>
        </figure>

        <div class="page__description">
            <?php the_content(); ?>
        </div>

        <div id="root"></div>

        <?php
        btw_get_template_part('template-parts/post_content/politics__related_articles', [
            'related_articles' => get_field('btw__politics_fields__related_articles', 'option'),
        ]);
        ?>

        <div class="single_article__footer">
            <div class="social_footer">
                <?php btw_sharing_tools(); ?>
            </div>
            <?php
            btw_get_template_part('template-parts/post_content/politics__related_tags', [
                'post_tags' => get_field('btw__politics_fields__related_tags', 'option'),
            ]);
            ?>
        </div>

    </div>


<?php

endwhile;

get_footer();
?>