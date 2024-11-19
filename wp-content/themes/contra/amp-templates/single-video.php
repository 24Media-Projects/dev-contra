<?php
global $contra_amp;
$contra_amp->amp_header();

$dfp_targeting = new BTW_DFP_TARGETING();
$amp_targeting = $dfp_targeting->amp_init();

while (have_posts()) : the_post();

	$post__feat_image 			= btw_get_post_featured_image('large_landscape');
	$post__title 				= get_the_title();
	$post__date_published		= get_the_date('j F Y, H:i');
	$primary_category           = btw_get_post_primary_category();
	$post_lead                  = get_field('btw__global_fields__lead');
	$video_credits              = get_field('btw__video_fields__credits');
	//$post_disclaimer            = get_field('btw__article_fields__disclaimer');

	$post_video = btw_get_post_video([
		'video_url' => get_field('btw__global_fields__featured_video'),
	]);

	$videos_category = get_term_by('slug', 'videos', 'category');
	$categories = [$videos_category, $primary_category];

	$video_subcategory__posts = new WP_Query([
		'post_type'         => 'video',
		'post_status'       => 'publish',
		'posts_per_page'    => 9,
		'suppress_filters'   => false,
		'tax_query' => [
			[
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms'    => [$primary_category->term_id]
			],
		]
	]);

?>

	<div class="single_article_container single-video_container">

		<?php btw_get_post_impressions_url(); ?>

		<article class="single_article">
			<header class="single_article__header">

				<figure class="single_article__main_image" id="single_article__main_image">
					<div class="single_article__main_image--inner <?php echo $post_video !== false ? 'has_featured_video' : ''; ?>">
						<div class="bg" style="background-image: url('<?php echo $post__feat_image->url; ?>');"></div>

						<?php if ($post_video !== false) : ?>

							<span class="play_video play_video_<?php echo $post_video['provider_name']; ?>"
								  data-video-ref="<?php echo $post_video['video_ref']; ?>"
								  data-provider="<?php echo $post_video['provider_name']; ?>"
								  on="tap:single_article__main_image.toggleClass(class='featured_video_playing')"
							>
							</span>

							<?php echo $post_video['html']; ?>

						<?php endif; ?>
					</div>
					<span class="image_credits wp_caption"><?php echo $post__feat_image->credits_html; ?></span>
				</figure>

				<div class="article_info">

					<div class="categories">
						<?php foreach ($categories as $key => $category) : ?>
							<a class="caption s-font-bold" href="<?php echo get_term_link($category); ?>"><?php echo $category->name; ?></a>
						<?php endforeach; ?>
					</div>

					<h1 class="single_article__title video_article_title">
						<?php echo $post__title; ?>
					</h1>

					<?php if ($post_lead) : ?>
						<h2 class="single_article_lead paragraph">
							<?php echo $post_lead; ?>
						</h2>
					<?php endif; ?>

					<?php if ($video_credits) : ?>
                        <div class="video_credits paragraph">
							<?php echo $video_credits; ?>
                        </div>
					<?php endif; ?>

					<div class="single_article__datetime">
						<span class="caption s-font"><?php echo $post__date_published; ?></span>
					</div>
					<div class="social_share toggle_share">
						<span class="social_label invisible">SHARE THIS</span>
						<?php do_action('btw_amp_sharing_tools'); ?>
					</div>
				</div>

				<?php btw_get_template_part('template-parts/post_content/info_text'); ?>
			</header>

			<div class="category__wrapper category-videos__wrapper videos-subcategory__wrapper">
				<div class="category__content">
					<?php if ($video_subcategory__posts->found_posts) : ?>
						<div class="sub_category">
							<h2>
								<a href="<?php echo $primary_category->term_link; ?>"><?php echo remove_punctuation($primary_category->name); ?></a>
							</h2>
						</div>
					<?php endif; ?>
					<div class="sticky_element__article_sidebar__parent">
						<div class="category__main_column main_column">
							<div class="category__main grid_posts_container">
								<section class="category__posts">
									<?php
									while ($video_subcategory__posts->have_posts()) : $video_subcategory__posts->the_post();
										btw_get_template_part('template-parts/archive/post', [
											'image_srcsets' => array(
												array(
													'image_size' => 'small_horizontal',
												)
											)
										]);
									endwhile;
									wp_reset_query();
									?>
								</section>
							</div>
						</div>

					</div>
					<div class="load-more-container">
						<a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ VIDEOS" type="button" href="<?php echo $primary_category->term_link; ?>">
							<span>ΠΕΡΙΣΣΟΤΕΡΑ VIDEOS</span>
							<svg>
								<use xlink:href="#icon-back-to-top"></use>
							</svg>
						</a>
					</div>
				</div>
			</div>
		</article>
	</div>



<?php
endwhile;
$contra_amp->amp_footer();
?>