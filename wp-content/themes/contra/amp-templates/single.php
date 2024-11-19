<?php
global $contra_amp;
$contra_amp->amp_header();

$dfp_targeting = new BTW_DFP_TARGETING();
$amp_targeting = $dfp_targeting->amp_init();


while (have_posts()) : the_post();

	$post__feat_image           = btw_get_post_featured_image();
	$post__title                = get_the_title();
	$post__date_published       = get_the_date('d F Y H:i');
	$primary_category           = btw_get_post_primary_category();
	$primary_tag                = btw_get_post_primary_tag();
	$post_lead                  = get_field('btw__global_fields__lead');
	$post_disclaimer            = get_field('btw__article_fields__disclaimer');

	$post_video = btw_get_post_video([
		'video_url' => get_field('btw__global_fields__featured_video'),
	]);


	$post__bgColor = get_field('btw__global_fields__bg_color') ?:
		get_field('btw__global_fields__bg_color', "category_{$primary_category->term_id}");

	$single_articleStyle = '';
	if ($post__bgColor) {
		$single_articleStyle = 'background-color: ' . $post__bgColor;
	}
	?>

	<div class="single_article_container">

		<?php btw_get_post_impressions_url(); ?>

		<article class="single_article <?php if (get_field('btw__article_fields__with_larger_photo')) echo 'big_img_article'; ?>" style="<?php echo $single_articleStyle ?>">
			<header class="single_article__header">
				<div class="caption s-font-bold article_captions">
					<a class="article_category" title="<?php echo esc_html($primary_category->name); ?>" href="<?php echo $primary_category->term_link; ?>">
						<?php echo  $primary_category->name; ?>
					</a>

					<?php if ($primary_tag->taxonomy === 'post_tag') : ?>
						<a class="article_tag" title="<?php echo esc_html($primary_tag->name); ?>" href="<?php echo $primary_tag->term_link; ?>">
							<?php echo $primary_tag->name; ?>
						</a>
					<?php endif; ?>
				</div>
				<h1 class="single_article__title article__small_title article__title">
					<?php echo $post__title; ?>
				</h1>

                <?php if( $time = btw_get_post_estimated_reading_time() ): ?>
                    <span class="reading_time s-font">Διαβάζεται σε <?php echo $time; ?>'</span>
                <?php endif; ?>

				<?php if ($primary_category->slug != 'episyndeseis') : ?>

					<figure class="single_article__main_image" id="single_article__main_image">
						<div class="single_article__main_image--inner <?php echo $post_video !== false ? 'has_featured_video' : ''; ?>">
							<img src="<?php echo $post__feat_image->url; ?>" alt="<?php echo $post__feat_image->alt; ?>">

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
						<span class="image_credits image_caption" style="<?php echo $single_articleStyle ?>"><?php echo $post__feat_image->credits_html; ?></span>
					</figure>

				<?php endif; ?>


			</header>

			<div class="single_article__main_container">

				<main class="single_article__main">

					<?php if ($post_lead) : ?>
						<h2 class="article__lead">
							<?php echo $post_lead; ?>
						</h2>
					<?php endif; ?>



					<ul class="single_article__header_info article_info_small">
						<li class="post__author s-font-bold"><?php btw_get_post_author_html($post, false); ?></li>
						<li class="article__date s-font">
							<span><?php echo $post__date_published; ?></span>
						</li>
						<li class="social_share toggle_share">
							<?php do_action('btw_amp_sharing_tools'); ?>
						</li>
					</ul>

					<div class="paragraph">
						<?php the_content();
						btw_get_template_part('template-parts/post_content/info_text'); 
						?>
					</div>

					<footer class="single_article__footer">
						<div class="social_footer">
							<?php do_action('btw_amp_sharing_tools'); ?>
						</div>

						<?php

						btw_get_template_part('template-parts/post_content/article_tags', [
							'primary_category' => $primary_category,
						]);

						btw_get_template_part('template-parts/post_content/primary_tag__posts', [
							'primary_tag' => $primary_tag,
						]);

						btw_get_template_part('template-parts/post_content/seo_promo_text');

						?>

					</footer>
				</main>

			</div>
		</article>
	</div>


	<?php
	/*
		btw_get_template('amp-templates/template-parts/ads/dfp', [
			'slot_id' 		=> 'article_end',
			'slot_name' 	=> 'ros_article_end',
			'sizes' 		=> '300x250,300x600,336x280',
			'amp_targeting' => $amp_targeting,
		]);
	*/
	?>

<?php
endwhile;

// TODO: Newsletter

?>

<?php if( !hide_taboola() ): ?>
    <aside class="network_content" style="padding-top: 47px; padding-bottom: 100px;">
        <div class="home_wrapper clear">
            <section class="taboola_posts_container">
                <amp-embed width="100" height="100" type=taboola layout="responsive"
                    heights="(min-width:1743px) 513%, (min-width:1490px) 519%,
                    (min-width:1271px) 526%, (min-width:1090px) 534%,
                    (min-width:928px) 543%, (min-width:799px) 553%,
                    (min-width:687px) 566%, (min-width:629px) 581%,
                    (min-width:567px) 596%, (min-width:513px) 613%,
                    (min-width:457px) 635%, (min-width:412px) 664%,
                    (min-width:365px) 687%, (min-width:340px) 716%,
                    746%"
                    data-publisher="contra-ampgr-p20801833"
                    data-mode="alternating-contra-AMP-new"
                    data-placement="Alternating Below Article New"
                    data-target_type="mix"
                    data-tracking="utm_source=Contra&utm_medium=BestofNetwork_article&utm_campaign=24MediaWidget"
                    data-article="auto"
                    data-url="">
                </amp-embed>
            </section>
        </div>
    </aside>
<?php endif; ?>

<?php $contra_amp->amp_footer(); ?>