<?php
// XD: https://xd.adobe.com/view/2bbff874-6406-4bb4-8597-3cd3bcce2b89-7eec/
get_header();

$term = get_queried_object();
$term_id = $term->term_id;


$container_classes = [];
if( $term->description ){
	$container_classes[] = 'with_description';
}
?>
<div class="category__wrapper podcasts_wrapper <?php echo implode( ' ', $container_classes );?>">

	<div class="category__header">
		<?php btw_get_template_part('template-parts/group_header', [
			'section_title' => $term->name,
		]); ?>
	</div>

	<?php if (trim($term->description)) : ?>
		<div class="category__description">
			<div class="section_description">
				<?php echo $term->description; ?>
			</div>
		</div>
	<?php endif; ?>

	<div class="category__content">
		<div class="sticky_element__article_sidebar__parent">
			<div class="category__main_column main_column">
				<main class="category__main grid_posts_container">
					<section class="category__posts">
						<?php 
						foreach (get_field('btw__podcasts_home_fields', 'option') ?: [] as $series) : ?>

						<article class="article">

							<?php 
							if ( $series['acf_fc_layout'] == 'advertorial_podcast_subcategory' ) : 
								

								$img_url = $series['image']['url']; 
								//$img_id = $series['image']['id']; 
								

								$img_alt =  $series['image']['alt']; 
								$click_url = $series['click_url']; 
								$click_target = "_blank";
								
								echo btw_get_impression_url($series['impression_url']); 
								


							else :
								$series = $series['post_category'];
								$image = get_field('btw__global_fields__featured_image', $series);

								$img_url = $image['url']; 
								//$img_id = $image['id']; 
								
					
								$img_alt = $image['alt']; 
								//echo $series->name; // if need it
								$click_url = get_term_link($series); 
								$click_target = "_self";
							
							endif;


							//$attachment = btw_get_post_attachment( null, $img_id, $image_srcsets, $img_alt ?: $term->name );

							?>
							
								<figure>
									<a class="clear post_img" href="<?php echo $click_url; ?>" target="<?php echo $click_target; ?>">
										<picture>
											<img decoding="async" loading="lazy" src="<?php echo $img_url; ?>" alt="<?php echo $img_alt; ?>">
										</picture>
										<?php //echo $attachment->html; 
										?>

									</a>
								</figure>
								
						</article>	
						<?php endforeach; ?>
						
					</section>
				</main>
			</div>
			<aside class="main_sidebar category__aside sidebar_column">
				<?php
				btw_get_template_part('template-parts/ads/dfp', [
					'slot_id' => 'sidebar_a',
				]);
				?>
			</aside>
		</div>
		<!-- <div class="load-more-container">
			<a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button">
				<span>ΠΕΡΙΣΣΟΤΕΡΑ PODCASTS</span>
				<svg>
					<use xlink:href="#icon-back-to-top"></use>
				</svg>
			</a>
		</div> -->
	</div>
	<?php get_footer(); ?>