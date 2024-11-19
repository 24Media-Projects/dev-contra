<?php

/**
 * Available strings:
 * @var array, $image_srcsets
 * @var string, $container_class class
 * @var bool, $lazyload
 */


global $post;

if (!isset($image_srcsets)) {
    $image_srcsets = array(
        array(
            'image_size'   => 'small_square',
            'media_query'  => '(max-width: 767px )',
            'mobile'       => true,
        ),
        array(
            'image_size'  => 'medium_horizontal',
            'default'     => true,
        ),
    );
}


$post_attachment = btw_get_post_attachment(
    image_srcsets: $image_srcsets,
    lazyload: $lazyload ?? true,
);

$post_url = get_the_permalink( $post->ID );
$post_title = get_the_title();

if( get_queried_object() instanceof WP_Term && in_array( get_queried_object()->slug, [ 'opinion', 'gnomes' ] ) ){
	$post_primary_term_html = btw_return_post_author_html();
}else{
	$post_primary_term_html = btw_get_primary_term_anchor_html(btw_get_post_primary_term($post));
}

$post_is_sponsored = btw_is_post_sponsored();


$container_class = $container_class ?? [];

if ( $post->post_type == 'video' || get_field('btw__article_fields__audio_player_code') ) {
    $container_class[] = 'play_article';
}
?>

<article class="article align_left basic_article landscape_img <?php echo implode(' ', $container_class); ?> small_article_mobile">
    <figure>
        <a class="clear post_img" href="<?php echo $post_url; ?>" title="<?php echo esc_attr($post_title); ?>">
            <?php echo $post_attachment->html; ?>
        </a>
    </figure>

    <div class="post__content">
        <h3 class="post__title article-s-main-title">

            <a title="<?php echo esc_attr($post_title); ?>" href="<?php echo $post_url; ?>">
                <?php echo $post_title; ?>
            </a>

        </h3>
        <div class="post__category">
            <h4 class="caption s-font-bold">
                <?php echo $post_primary_term_html; ?>
            </h4>

            <span class="caption s-font">
                <?php echo $post_is_sponsored ? 'Sponsored' : get_the_date('d.m.Y, H:i');?>
            </span>

        </div>
    </div>
</article>