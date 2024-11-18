<?php
// Template Name: Liveblog
// Template Post Type: post
get_header();

while (have_posts()) : the_post();

    $post__feat_image           = btw_get_post_featured_image();
    $post__title                = get_the_title();
    $post__date_published       = get_the_date('d F Y H:i');
    $primary_category           = btw_get_post_primary_category();
    $primary_tag                = btw_get_post_primary_tag();
    $post_lead                  = get_field('btw__global_fields__lead');
    $post_disclaimer            = get_field('btw__article_fields__disclaimer');


    $is_live_class              = null;
    $options                    = get_field('btw__global_fields__display_options') ?: [];

    if( in_array( 'is_live_now', $options ) or 'is_live_now' == $options ) {    
        $is_live_class = 'is_live';
    }


    $post__supertitle            = get_field('btw__article_fields__liveblog_supertitle');

    $post__related_posts        = get_field('btw__article_fields__liveblog_related_posts');
    ?>

    <div class="single_article_container">

		<?php btw_get_post_impressions_url(); ?>

        <article class="single_article single__liveblog <?php echo $is_live_class; ?>">



            <header class="liveblog__header single_article__header">
                <div class="liveblog_wrapper clear">

                    <div class="left_col">
                        <?php if ( $is_live_class == 'is_live') { ?>
                        <span class="live_button">LIVE</span>
                        <?php } ?>

                        <h1 class="single_article__title article__small_title article__title">
                            <?php echo $post__title; ?>
                        </h1>
                    </div>


                    <div class="right_col">
                        <ul class="liveblog__header_info">
                            <li class="article__date s-font">
                                <span><?php echo $post__date_published; ?></span>
                            </li>
                            <li class="s-font-bold article_captions caption">
                                <?php 
                                if ( $post__supertitle ) { ?>
                                <span class="article_category" >
                                    <?php echo $post__supertitle; ?>
                                </span>

                                <?php }
                                elseif ($primary_tag->taxonomy === 'post_tag') { ?>
                                <a class="article_category" title="<?php echo esc_html($primary_tag->name); ?>" href="<?php echo $primary_tag->term_link; ?>">
                                    <?php echo $primary_tag->name; ?>
                                </a>
                                <?php } 
                                else { ?>
                                <a class="article_category" title="<?php echo esc_html($primary_category->name); ?>" href="<?php echo $primary_category->term_link; ?>">
                                    <?php echo  $primary_category->name; ?>
                                </a>
                                <?php } ?>
                            </li>
                        </ul>

                        <div class="social_sidebar">
                            <div class="inner">
                                <?php btw_sharing_tools(); ?>
                            </div>
                        </div>
                    </div>


                    

                    <!-- <span class="reading_time s-font">Διαβάζεται σε 5’</span> -->

                    <!-- <figure class="single_article__main_image">
                        <img src="<?php echo $post__feat_image->url; ?>" alt="<?php echo $post__feat_image->alt; ?>">
                        <span class="image_credits image_caption"><?php echo $post__feat_image->credits_html; ?></span>
                    </figure> -->

                </div>
            </header>



            <section class="liveblog__subheader">
                <div class="liveblog_wrapper clear">

                    <div class="inner">
                        <div class="live_blog_container liveblog_lastUpdate__container">
                            <div class="liveblog_lastUpdate">
                                <span>Η σελίδα ανανεώθηκε</span>
                                <span id="liveblogUpdateStatus">1 λεπτό πριν</span>
                            </div>
                        </div>

                        <?php 
                        if ( $post__related_posts ) {
                         btw_get_template_part('template-parts/post_content/related_posts');

                        } else { 
                        btw_get_template_part('template-parts/post_content/primary_tag__posts', [
                            'primary_tag' => $primary_tag,
                        ]);
                        }
                        ?>
                    </div>
                </div>
            </section>





            <div class="single_article__main_container">
                <main class="single_article__main liveblog__main">
                    <div class="liveblog_wrapper clear">
                        <?php if ($post_lead) : ?>
                            <h3 class="article__lead invisible">
                                <?php echo $post_lead; ?>
                            </h3>
                        <?php endif; ?>



                        <?php get_template_part("templates/template-parts/post_content/live_blog_code"); ?>

                        <footer class="single_article__footer">
                            <?php

							btw_get_template_part('template-parts/post_content/info_text');

							btw_get_template_part('template-parts/post_content/article_tags', [
                                'primary_category' => $primary_category,
                            ]);


							btw_get_template_part('template-parts/post_content/seo_promo_text');
                            ?>
                        </footer>
                    </div>
                </main>

                <aside class="single_article__aside">
                    <div class="inner">
                      <?php
                            btw_get_template_part('template-parts/ads/dfp', [
                                'slot_id' => 'sidebar_a',
                            ]);
						?>
                    </div>
                </aside>

            </div>
        </article>
    </div>


   


<?php
// TODO: Newsletter

// Parsley: Δημοφιλή άρθρα
// btw_get_template_part('template-parts/post_content/post_popular_articles', [
    // 'post_primary_category' => $primary_category,
// ]);


endwhile;

// btw_get_template_part('template-parts/archive/category/featured_group', [
    // 'term' => $primary_category,
// ]);
?>



<!-- <aside class="network_content" style="padding-top: 50px; padding-bottom: 100px;;">
    <div class="home_wrapper clear">
        <section class="taboola_posts_container">
            <h2 style="text-align: center;">TABOOLA FEED</h2>
            <div class="taboola_feed"></div>
        </section>
    </div>
</aside>
 -->

<?php

get_footer();
?>