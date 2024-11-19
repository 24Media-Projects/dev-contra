<?php

// Posts apo to Parsley API
// Σχετικό ticket για το API https://24mediagr.atlassian.net/browse/N247RDSN-6

// TODO βάλε τα άρθρα σαν html, δεν είναι έτοιμο το API ακόμα

// Τα στοιχεία του groups θα γίνουν extract από την παρακάτω συνάρτηση έτσι ώστε να έχουν πάντα το σωστό περιεχόμενο.
// Έτσι εάν πχ θέλετε να χρησιμοποιήσετε το section title αρκεί να κάνετε echo $section_title;
// Περισσότερα στο phpDoc της συνάρτησης btw_get_hp_group_fields
extract(btw_get_hp_group_fields());

global $post;

$parsely_hp_posts = get_post_meta( $post->ID, 'parsely_hp_posts', true );

if( !$parsely_hp_posts ){
    return;
}

?>
<div class="home_wrapper popular_articles__wrapper">
    <section id="<?php echo $section_id; ?>" class="popular_articles_section">
        <h2 class="popular_articles_title">Δημοφιλή Άρθρα</h2>
        <div class="text_article_container">

        <?php foreach( $parsely_hp_posts as $post ): ?>

                <article class="article text_article">
                    <div class="post__content">
                        <h3 class="post__title article_main_font">
                            <a title="<?php echo esc_attr( $post['title'] );?>" href="<?php echo $post['url'];?>">
                                <span class="desktop_title truncate"><?php echo $post['title'];?></span>
                            </a>
                        </h3>
                        <div class="post__category">
                            <h4 class="caption s-font-bold">
                                <!-- <a title="{{ data.section }}" href="/"> -->
                                    <?php echo $post['section'];?>
                                 <!-- </a> -->
                            </h4>
                        </div>
                    </div>
                </article>

            <?php endforeach; ?>

        </div>

    </section>

    <aside class="section_sidebar">
        <?php
            btw_get_template_part('template-parts/ads/dfp', [
                'slot_id' => 'popular_articles'
            ]);
        ?>
    </aside>

</div>