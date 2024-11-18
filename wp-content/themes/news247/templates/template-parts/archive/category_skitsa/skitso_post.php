<?php

/**
 * Available strings:
 * @var array, $image_srcsets
 * @var bool, $lazyload
 */


global $post;

if( empty($image_srcsets ) ){
    $image_srcsets = array(
        array(
            'image_size' => 'large_square',
            'default'   => true,
        )
    );
}


$post_attachment = btw_get_post_attachment(
    image_srcsets: $image_srcsets,
    post: $post,
    lazyload: $lazyload ?? true,
);

$post_url = get_the_permalink();
$post_title = get_the_title();


?>

<article class="article basic_article square">
    <figure>
        <a class="clear post_img" href="<?php echo $post_url; ?>" title="<?php echo esc_attr($post_title); ?>">
            <?php echo $post_attachment->html; ?>
        </a>
    </figure>

    <div class="post__content">

        <div class="post__category">
            <h4 class="caption s-font-bold">
                <span class="sponsor s-font"><?php echo get_the_date( 'd.m.Y, H:i' ); ?></span>
            </h4>
        </div>
    </div>

</article>