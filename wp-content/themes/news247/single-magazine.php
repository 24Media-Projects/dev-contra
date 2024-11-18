<?php
// Template Name: Magazine
// Template Post Type: post

get_header();

while (have_posts()) : the_post();

    $post__feat_image             = btw_get_post_featured_image();
    $post__title                 = get_the_title();
    $post__date_published        = get_the_date('d F Y');
    $primary_category           = btw_get_post_primary_category();
    $primary_tag                = btw_get_post_primary_tag();
    $post_lead                  = get_field('btw__global_fields__lead');
    $post_disclaimer            = get_field('btw__article_fields__disclaimer');

    $post_video = btw_get_post_video([
        'video_url' => get_field('btw__global_fields__featured_video'),
    ]);


    $post_sponsor = get_magazine_sponsor();

    $post_parallax = get_magazine_parallax();
    ?>

    <div class="single_article_container">

		<?php btw_get_post_impressions_url(); ?>

		<?php btw_get_template_part('template-parts/post_content/magazine_sponsor', [
			'sponsor' => $post_sponsor
		]); ?>

        <article class="single_article <?php if (get_field('btw__magazine_article_fields__half_featured_photo')) echo 'half_img_article'; ?> <?php if ($post_parallax) echo 'has_parallax'; ?>">
            <header class="magazine_above_the_fold">

                <figure class="single_article__main_image">
                    <div class="single_article__main_image--inner <?php echo $post_video !== false ? 'has_featured_video' : ''; ?>">
                        <div class="bg" style="background-image: url('<?php echo $post__feat_image->url; ?>');"></div>

                        <?php if ($post_video !== false) : ?>

                            <span class="play_video play_video_<?php echo $post_video['provider_name']; ?>" data-video-ref="<?php echo $post_video['video_ref']; ?>" data-provider="<?php echo $post_video['provider_name']; ?>">
                            </span>

                            <?php echo $post_video['html']; ?>

                        <?php endif; ?>
                    </div>
                    <span class="image_credits image_caption"><?php echo $post__feat_image->credits_html; ?></span>
                </figure>

                <div class="header_info">
                    <div class="mag-l-caption article_captions">
                        <a class="article_category" title="<?php echo esc_html($primary_category->name); ?>" href="<?php echo $primary_category->term_link; ?>"><?php echo remove_punctuation($primary_category->name); ?></a>
                        <div class="post__author"><?php btw_get_post_author_html($post, false); ?></div>
                        <span class="article__date"><?php echo remove_punctuation( $post__date_published ); ?></span>
                    </div>
                    <h1 class="single_article__title mag-xl-article-title article__title">
                        <?php echo remove_punctuation($post__title); ?>
                    </h1>
                </div>
            </header>

            <?php btw_get_template_part('template-parts/post_content/magazine_parallax', [
                'parallax' => $post_parallax
            ]); ?>

            <div class="single_article__main_container">

                <main class="single_article__main">

                    <ul class="single_article__header_info article_info_small">
                        <li class="social_share toggle_share">
                            <?php btw_sharing_tools(); ?>
                        </li>
                    </ul>

                    <?php if ($post_lead) : ?>
                        <h2 class="article__lead">
                            <?php echo $post_lead; ?>
                        </h2>
                    <?php endif; ?>

                    <div class="paragraph">
                        <?php the_content();
                        btw_get_template_part('template-parts/post_content/info_text'); 
                        ?>
                    </div>

                    <footer class="single_article__footer">
                        <div class="social_footer test_class">
                            <?php btw_sharing_tools(); ?>
                        </div>

                        <?php

						btw_get_template_part('template-parts/post_content/article_tags', [
                            'primary_category' => $primary_category,
                        ]);

                        btw_get_template_part('template-parts/post_content/primary_tag__posts__magazine', [
                            'primary_tag' => $primary_tag,
                        ]);

                        btw_get_template_part('template-parts/post_content/seo_promo_text');

                        btw_get_template_part('template-parts/post_content/newsletter');
                        ?>

                    </footer>
                </main>

                <aside class="single_article__aside">
                    <div class="inner">
                        <?php
                        btw_get_template_part('template-parts/ads/dfp', [
                            'slot_id' => 'magazine_article_sidebar',
                        ]);
                        ?>
                    </div>
                </aside>

            </div>
        </article>
    </div>


    <?php
    btw_get_template_part('template-parts/ads/dfp', [
        'slot_id' => 'magazine_article_end',
    ]);
    ?>

<?php

endwhile;

// TODO: Newsletter


btw_get_template_part('template-parts/post_content/magazine_post_popular_articles', [
    'post_primary_category' => $primary_category,
]);



?>

<?php



btw_get_template_part('template-parts/ads/dfp', [
    'slot_id' => 'magazine_article_above_taboola',
]);


?>


<?php if( !hide_taboola() ): ?>
    <aside class="network_content" style="padding-top: 50px; padding-bottom: 100px;">
        <div class="home_wrapper clear">
            <section class="taboola_posts_container">
                <!-- <h2 style="text-align: center;">TABOOLA FEED</h2> -->
                <div class="taboola_feed"></div>
            </section>
        </div>
    </aside>
<?php endif; ?>


<?php

get_footer();
?>