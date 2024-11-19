<?php

/**
 * Available strings
 *
 * @var array $post_tags
 *
 */

if( !$post_tags ){
	return;
}

?>


<div class="article__tags_container article_info_large s-font-bold">
	<div class="article__tags ">
		<ul>
			<li class="label">TAGS:</li>
			<?php foreach( $post_tags as $post_tag ): ?>
				<li class="tag_item">
					<a href="<?php echo get_term_link( $post_tag->term_id );?>">
						<?php echo $post_tag->name;?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div><!-- .article__tags_container closed -->

