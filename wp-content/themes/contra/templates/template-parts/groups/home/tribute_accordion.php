<?php
extract(btw_get_hp_group_fields());

$posts = get_field('btw__group_fields__hp__template__tribute_accordion__posts_selection', $group_id) ?: [];




$section_classes = [];

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


$posts_count = count($posts);

if ($posts_count >= 2 && $posts_count <= 4) {
    $section_classes[] = 'posts_' . $posts_count;
}

if ( $posts_count < 2 ) {
    $posts_count__class = "single";
} else {
    $posts_count__class = "multiple";
}
?>

<div class="home_wrapper tribute_accordion__wrapper <?php echo $posts_count__class;?>">
    <section id="<?php echo $section_id; ?>" class="tribute_accordion <?php echo implode(' ', $section_classes); ?>">

		<?php echo btw_get_impression_url($impression_url); ?>

        <?php
        btw_get_template_part('template-parts/group_header', [
            'section_title'               => $section_title,
            'section_title_url'           => $section_title_url,
            'heading'                     => 'h2',
            'related_links'               => $related_links,
            'is_section_title_full_width' => $is_section_title_full_width,
        ]);

        foreach ($posts as $key => $row) : ?>
            <input <?php if ($key == 0) echo 'checked'; ?> id="radio-<?php echo $section_id; ?>-tribute_accordion_<?php echo $key + 1; ?>" name="radio-<?php echo $section_id; ?>-tribute_accordion" type="radio" class="radio--btn radio--btn-<?php echo $key + 1; ?>" />
        <?php endforeach; ?>

        <div class="slides__container">

            <style>
                @media screen and (min-width: 1024px) {

                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-1:checked~.slides__container .slide-1,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-2:checked~.slides__container .slide-2,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-3:checked~.slides__container .slide-3,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-4:checked~.slides__container .slide-4,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-5:checked~.slides__container .slide-5 {
                        flex: 0 0 calc(100% - (<?php echo $key; ?> * 50px));
                    }
                }

                @media screen and (min-width: 1350px) {

                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-1:checked~.slides__container .slide-1,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-2:checked~.slides__container .slide-2,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-3:checked~.slides__container .slide-3,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-4:checked~.slides__container .slide-4,
                    #<?php echo $section_id; ?>.tribute_accordion .radio--btn-5:checked~.slides__container .slide-5 {
                        flex: 0 0 calc(100% - (<?php echo $key; ?> * 60px));
                    }
                }

                <?php foreach ($posts as $key => $row) : ?>
                    <?php if ($bg_color) :
                        list($r, $g, $b) = sscanf($bg_color, "#%02x%02x%02x"); ?>
                        #<?php echo $section_id; ?>.tribute_accordion .article.slide-<?php echo $key + 1; ?> {
                            background-color: rgba(<?php echo $r; ?>, <?php echo $g; ?>, <?php echo $b; ?>, <?php echo 1 - ($key * 0.08); ?>);
                        }

                        #<?php echo $section_id; ?>.tribute_accordion .labels__container_mobile .label-<?php echo $section_id; ?>-tribute_accordion_<?php echo $key + 1; ?> {
                            background-color: rgba(<?php echo $r; ?>, <?php echo $g; ?>, <?php echo $b; ?>, <?php echo 1 - ($key * 0.08); ?>);
                        }
                    <?php endif; ?>
                <?php endforeach; ?>
            </style>

            <?php foreach ($posts as $key => $row) :

                $atf_post = new BTW_Atf_Post([
                    'item'          => $row,
                    'section_id'    => $section_id,
                    'index'         => $key,
                    'primary_term'  => $primary_term,
                    'image_srcsets'    => array(
                        array(
                            'image_size'   => 'medium_landscape',
                            'media_query'  => '(max-width: 1023px )',
                            'mobile'       => true,
                        ),
                        array(
                            'image_size'  => 'large_square',
                            'default'     => true,
                        ),
                    ),
                    'render_attrs' => [
                        'article_type' => 'overlay_article',
                        'template_name' => 'tribute_accordion_post',
                        'img_type' => 'overlay_landscape_img',
                        'background_image' => true,
                        'truncate' => 8,
                    ],
                ]);

                $atf_post->render();

            endforeach; ?>
        </div>
        <div class="labels__container_mobile">
            <?php foreach ($posts as $key => $row) :
				$style = '';
                if ( $row['atf__bg_color'] ) { // in that group template color is just a hex
                    $style = 'background-color: ' . $row['atf__bg_color'];
                }


                $mode = 'light';
                if ( $row['atf__is_dark_mode'] == 'true' ) {
                    $mode = 'dark';
                }
                ?>
                <label class="label-<?php echo $section_id; ?>-tribute_accordion_<?php echo $key + 1; ?>" style="<?php echo $style; ?>" for="radio-<?php echo $section_id; ?>-tribute_accordion_<?php echo $key + 1; ?>">
                    <div class="counter <?php echo $mode;?>"><?php echo $key + 1; ?></div>
                </label>
            <?php endforeach; ?>
        </div>

    </section>



    <style type="text/css">
        .tribute_accordion .post__content, 
        .tribute_accordion .article label .article__supertitle, 
        .tribute_accordion label .counter {
            color: #000 !important;
        }

         .tribute_accordion .post__content .post__title a:hover,
         .tribute_accordion .post__content .post__title a:hover * {
            text-decoration-color: #000 !important;
        }

        .tribute_accordion .article_darkmode .post__content,
        .tribute_accordion .article_darkmode label .article__supertitle,
        .tribute_accordion label .counter.dark,
        .tribute_accordion .article.article_darkmode label .counter {
            color: #ffffff !important;
        }

        .tribute_accordion .article_darkmode .post__content .post__title a:hover,
        .tribute_accordion .article_darkmode .post__content .post__title a:hover * {
            text-decoration-color: #ffffff !important;
        }
    </style>
</div>