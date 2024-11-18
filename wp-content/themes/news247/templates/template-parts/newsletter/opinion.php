<?php

$opinion = get_field('btw__newsletter_fields__opinion')[0] ?? false;

if (!$opinion) return;

$post_link = get_permalink($opinion);
$post_title = get_the_title($opinion);
$esc_post_title = esc_attr(wp_strip_all_tags($post_title));

$post_author = btw_get_post_author($opinion);
// $post_author = btw_get_post_author_html( $wp_post );

$post_author_name = $post_author->display_name;
$term = btw_get_post_primary_category($opinion);

?>
<style>
	table.opinion_post a:hover {
		text-decoration-color: #000000 !important;
	}
</style>
<tr>
	<td style="padding:30px 20px 0;">

		<table class="opinion_post" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#E7E1E2;">
			<tbody>
				<tr>
					<td style="padding-top:20px;">
						<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tbody>
								<tr>
									<td style="padding:0 0 0px 20px;vertical-align:top;display: inline-block;">
										<div class="post__content" style="max-width:315px;">
											<div class="post__category" style="padding:0 0 6px;">
												<h4 class="caption s-font-bold" style="font: normal normal bold 14px/16px Arial;letter-spacing: 0px;color: #000000;margin:0;display:inline-block;padding:0 8px 0 0;">
													<a title="<?php echo esc_attr($term->name); ?>" href="<?php echo $term->term_link; ?>" style="color: #000000;text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px;">
														<?php echo $term->name; ?>
													</a>

												</h4>

												<h5 class="sponsor s-font" style=" font: normal normal normal 14px/16px Arial;letter-spacing: 0px;color: #000000;margin:0;display:inline-block;">
													<?php echo str_replace('href=', 'style="color: #000000;text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px;" href=', btw_return_post_author_html($opinion, false)); ?>
												</h5>

											</div>
											<h3 class="post__title article-s-main-title" style="font: normal normal normal 24px/28px Georgia;letter-spacing: -0.24px;color: #000000;margin:0;">

												<a title="<?php echo $esc_post_title; ?>" href="<?php echo $post_link; ?>" style="color: #000000;text-decoration: underline 1px transparent;transition: text-decoration-color 300ms;text-underline-offset: 3px;">
													<span class="desktop_title truncate"><?php echo $post_title; ?></span>
												</a>

											</h3>
										</div>
									</td>
									<td style="padding:0px 0 0 20px;display: inline-block;">
										<a class="clear post_img" href="<?php echo $post_link; ?>" title="<?php echo $esc_post_title; ?>" style="display:block;font-size:0;">
											<img src="<?php echo $post_author->meta['avatar']; ?>" alt="<?php echo esc_attr($post_author_name); ?>" width="245" style="display: block;max-width: 245px;width:100%;height: auto;" />
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</td>

				</tr>
			</tbody>
		</table>
	</td>
</tr>