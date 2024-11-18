<?php

/**
 * Available strings
 * 
 * @var object $primary_category
 * @see btw_get_post_primary_category
 * 
 */

global $post;

$post_tags = get_the_terms( $post->ID, 'post_tag' ) ?: [];

$primary_category           = btw_get_post_primary_category();


if( !$post_tags && !$primary_category ){
	return;
}

$is_magazine_post = btw_is_magazine_post();

?>


	<div class="article__tags_container article_info_large s-font-bold">
		<div class="article__tags ">
			<ul>
				<li class="label">TAGS:</li>
				<?php foreach( $post_tags as $post_tag ): ?>
					<li class="tag_item">
						<a href="<?php echo get_term_link( $post_tag->term_id );?>">
							<?php echo $is_magazine_post ? remove_punctuation( $post_tag->name ) : $post_tag->name;?>
						</a>
					</li>
				<?php endforeach; ?>
				<li class="tag_item">
					<a class="article_category" title="<?php echo esc_html($primary_category->name); ?>" href="<?php echo $primary_category->term_link; ?>">
						<?php echo $is_magazine_post ? remove_punctuation( $primary_category->name ) : $primary_category->name;?>
                    </a>
				</li>
			</ul>
		</div>
	</div><!-- .article__tags_container closed -->

