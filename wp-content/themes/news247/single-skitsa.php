<?php

get_header();

while (have_posts()) : the_post();

	$post__feat_image           = btw_get_post_featured_image('large_square');
	$post__title                = get_the_title();
	$post__date_published       = get_the_date('d F Y H:i');
	$primary_category           = btw_get_post_primary_category();
	$primary_tag                = btw_get_post_primary_tag();
	$post_lead                  = get_field('btw__global_fields__lead');
	$post_disclaimer            = get_field('btw__article_fields__disclaimer');

	$post_video = btw_get_post_video([
		'video_url' => get_field('btw__global_fields__featured_video'),
	]);


	$post__bgColor = get_field('btw__global_fields__bg_color');

	$single_articleStyle = '';
	if ($post__bgColor) {
		$single_articleStyle = 'background-color: ' . $post__bgColor;
	}



	$prev_skitso = get_previous_post();
	$next_skitso = get_next_post();
	?>

	<div class="single_article_container">

		<?php btw_get_post_impressions_url(); ?>

		<article class="single_article <?php if (get_field('btw__article_fields__with_larger_photo')) echo 'big_img_article'; ?>" style="<?php echo $single_articleStyle ?>">
			<div class="single_article__header_with_sidebar">
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

					<?php if ($primary_category->slug != 'episyndeseis') : ?>

						<figure class="single_article__main_image">
							<div class="single_article__main_image--inner">
								<img src="<?php echo $post__feat_image->url; ?>" alt="<?php echo $post__feat_image->alt; ?>">
							</div>
						</figure>

					<?php endif; ?>


				</header>

				<aside class="single_article__aside">
					<div class="inner">
						<?php
						btw_get_template_part('template-parts/ads/dfp', [
							'slot_id' => 'sidebar_a',
						]);
						?>
					</div>
				</aside>
			</div>
			<div class="single_article__main_container">


				<?php if ($prev_skitso || $next_skitso) { ?>
					<div class="skitsa_pagination">
						<?php
						if ($prev_skitso) {


							$prev_skitso__title = get_the_title($prev_skitso);
							$prev_skitso__link 	= get_permalink($prev_skitso->ID);
						?>
							<a class="previous_post" href="<?php echo $prev_skitso__link; ?>" title="<?php echo $prev_skitso__title; ?>">
								<svg class="icon_arrow">
									<use xlink:href="#icon-slide-arrow"></use>
								</svg>
								<span class="invisible">Προηγούμενο Σκίτσο</span>
							</a>
						<?php } else { ?>
							<span class="previous_post_disabled">
								<svg class="icon_arrow">
									<use xlink:href="#icon-slide-arrow"></use>
								</svg>
								<i class="invisible">Προηγούμενο Σκίτσο</i>
							</span>
						<?php
						}


						if ($next_skitso) {

							$next_skitso__title = get_the_title($next_skitso);
							$next_skitso__link 	= get_permalink($next_skitso->ID);
						?>
							<a class="next_post" href="<?php echo $next_skitso__link; ?>" title="<?php echo $next_skitso__title; ?>">
								<svg class="icon_arrow">
									<use xlink:href="#icon-slide-arrow"></use>
								</svg>
								<span class="invisible">Επόμενο Σκίτσο</span>
							</a>
						<?php } else { ?>
							<span class="next_post_disabled">
								<svg class="icon_arrow">
									<use xlink:href="#icon-slide-arrow"></use>
								</svg>
								<i class="invisible">Επόμενο Σκίτσο</i>
							</span>
						<?php
						} ?>

					</div>
				<?php } ?>

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
							<?php btw_sharing_tools(); ?>
						</li>
					</ul>
				</main>



			</div>
		</article>
	</div>

<?php
endwhile;

?>

<?php
btw_get_template_part('template-parts/ads/dfp', [
	'slot_id' => 'above_taboola',
]);
?>

<?php if( !hide_taboola() ): ?>
    <aside class="network_content" style="padding-top: 50px; padding-bottom: 100px;;">
        <div class="home_wrapper clear">
            <section class="taboola_posts_container">
                <!-- <h2 style="text-align: center;">TABOOLA FEED</h2> -->
                <div class="taboola_feed"></div>
            </section>
        </div>
    </aside>
<?php endif; ?>


<?php

get_footer();
?>