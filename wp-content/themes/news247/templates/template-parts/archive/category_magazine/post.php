<?php

/**
 * Available strings:
 * @var array, $image_srcsets
 * @var string, $container_class class
 * @var bool, $lazyload
 */


global $post, $btw_query;

$featured_image = btw_get_post_featured_image($image_size ?? 'medium_landscape');


$post_url = get_the_permalink();
$post_title = get_the_title();
$post_primary_term = btw_is_magazine_homepage()
	? btw_get_post_primary_category($post)
	: btw_get_post_primary_tag($post);

$post_primary_term_html = btw_get_primary_term_anchor_html( $post_primary_term, true );

$post_is_sponsored = btw_is_post_sponsored();

$container_classes = [ 'article', 'magazine_article', 'mag_basic_article', 'align_left', 'landscape_img'];

if ( $post->post_type == 'video' ) {
	$container_classes[] = 'play_article';
}

$truncate_class = 'truncate';

if( !isset($truncate) ){
	$truncate = 5;
}elseif( $truncate === false ){
	$truncate_class = '';
}
/**
 * Set truncate lines as data attribute, if requested
 */
$truncate_data_lines = $truncate ? 'data-truncate-lines="' . $truncate . '"' : '';

$lazyload = $lazyload ?? true;
$feat_image_class = $lazyload ? 'class="lazyload"' : '';
$feat_image_src = "src=\"{$featured_image->url}\"";
$feat_image_src = $lazyload ? 'data-' . $feat_image_src : $feat_image_src;

?>

<article class="<?php echo implode(' ', $container_classes ) ?>">
	<figure>
		<a class="clear post_img" href="<?php echo $post_url; ?>" title="<?php echo esc_attr($post_title); ?>">
			<img <?php echo $feat_image_class;?> <?php echo $feat_image_src;?> alt="<?php echo $featured_image->alt; ?>">
		</a>
	</figure>

	<div class="post__content">
		<h3 class="post__title mag-basic-title">

			<a title="<?php echo esc_attr($post_title); ?>" href="<?php echo $post_url; ?>">
				<span class="<?php echo $truncate_class; ?>" <?php echo $truncate_data_lines; ?>><?php echo remove_punctuation($post_title); ?></span>
			</a>

		</h3>
		<div class="post__category">
			<h4 class="caption category_caption mag-s-caption asty_bold">
				<?php echo $post_primary_term_html; ?>
			</h4>
			
			<span class="caption mag-s-caption asty_book">
                <?php echo btw_is_magazine_homepage()
					? btw_get_post_author_html( $post, false )
					: ( $post_is_sponsored ? 'Sponsored' : get_the_date('d.m.Y') );
				?>
            </span>

		</div>
	</div>
</article>