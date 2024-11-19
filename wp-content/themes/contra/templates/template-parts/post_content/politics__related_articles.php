<section id="" class="articles_grid">

	<div class="section_container">

		<div class="group_header">
			<h2 class="section__title">
				Αρθρογραφία
			</h2>
		</div>

		<div class="article_container loading">

			<?php foreach ($related_articles as $index => $post):
				$post_url = get_permalink($post);

				$image_srcsets = array(
					array(
						'image_size'   => 'medium_horizontal',
						'media_query'  => '(max-width: 1023px )',
					),
					array(
						'image_size'   => 'medium_horizontal',
						'media_query'  => '(max-width: 767px )',
						'mobile'       => true,
					),
					array(
						'image_size'  => 'small_horizontal',
						'default'     => true,
					),
				);

				if (in_array($index, [0, 2])) {
					$image_srcsets = array_merge([
						array(
							'image_size'   => 'medium_horizontal',
							'media_query'  => '(max-width: 1349px )',
						)
					], $image_srcsets);
				}

				$post_attachment = btw_get_post_attachment(
					post: $post,
					image_srcsets: $image_srcsets,
					lazyload: $lazyload ?? true,
				);

			?>

				<article class="article landscape_img basic_article">
					<figure>
						<a target="_blank" class="clear post_img lazyload" href="<?php echo $post_url; ?>" title="<?php esc_attr_e($post->post_title); ?>">
							<?php echo $post_attachment->html; ?>
<!--							<span class="invisible">--><?php //echo $post->post_title; ?><!--</span>-->
						</a>
					</figure>

					<div class="post__content" style="background-color: transparent;">
						<div class="post__category">
							<h3 class="caption s-font-bold">
								<?php echo btw_get_primary_term_anchor_html( btw_get_post_primary_term($post) ); ?>
							</h3>
						</div>
						<h3 class="post__title article-s-main-title">
							<a target="_blank" href="<?php echo $post_url; ?>" title="<?php esc_attr_e($post->post_title); ?>">
								<span class="desktop_title truncate"><strong><?php echo $post->post_title; ?></strong></span>
							</a>
						</h3>
					</div>

				</article>
			<?php endforeach; ?>

		</div>
	</div>
</section>
