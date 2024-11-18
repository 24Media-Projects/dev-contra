<?php

$posts_query = new WP_Query([
    'post_type'        => 'post',
    'post_status'      => 'publish',
    'posts_per_page'   => 40,
    'orderby'          => 'date',
    'order'            => 'DESC',
    'suppress_filters' => false,
]);


?>



<h2 class="section-s-title">Ροή Ειδήσεων</h2>
<a href="/roi-eidiseon" class="button s-font" type="button">Περισσότερα</a>
<div class="latest_news_article_container">
    <?php 

    while ($posts_query->have_posts()) : $posts_query->the_post();
        $img = btw_get_post_featured_image($img_size); 
        $title = get_the_title();


        if ( get_the_time( 'Yd' ) === current_time( 'Yd' ) ) {
            $date = get_the_date('H:i');
        } else {
            $date = get_the_date('d.m.Y H:i');
        }
    ?>
        <article class="article basic_article_small">
            <figure>
                <a href="<?php echo get_the_permalink(); ?>" class="clear post_img">
                    <img class="lazyload" data-src="<?php echo $img->url; ?>" alt="<?php echo $img->alt; ?>">
                </a>
            </figure>
            
            <div class="post__content">
                <h3 class="post__title article-s-font">
                    <a class="truncate" data-truncate-lines="3" title="<?php echo $title ?>" href="<?php echo get_the_permalink(); ?>"><?php echo $title;?></a>
                </h3>
                <h4 class="caption post__date article-xs-font">
                    <?php echo $date; ?>
                </h4>
            </div>
        </article>
    <?php endwhile;
    wp_reset_query(); ?>
</div>