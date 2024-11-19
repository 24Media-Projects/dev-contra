<?php

if( empty( $image_srcsets ) ){
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
);

$post_url = get_the_permalink( $post );
$post_title = get_the_title( $post );
$post_primary_category = btw_get_post_primary_category( $post );
$caption = btw_get_primary_term_anchor_html( $post_primary_category );

$post_author = btw_get_post_author( $post );

?>

<article class="article skitso square">
    <figure>
        <a class="clear post_img" href="<?php echo $post_url; ?>" title="<?php echo esc_attr($post_title); ?>">
            <?php echo $post_attachment->html; ?>
        </a>
    </figure>

    <div class="post__content">

        <div class="post__category">
            <h4 class="caption s-font-bold"><?php echo $caption; ?></h4><span class="sponsor s-font"><?php echo $post_author->display_name; ?></span>
        </div>
    </div>

</article>
