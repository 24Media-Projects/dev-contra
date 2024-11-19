<?php
extract( btw_get_hp_group_fields() );





if( empty($section_id ) ){
	$section_id = 'post_id__' . $post->ID;
}

?>

<div class="home_wrapper current_affairs__wrapper">

	<section class="current_affairs" id="<?php echo $section_id; ?>">

		<?php echo btw_get_impression_url($impression_url); ?>

        <?php

		foreach (get_field('btw__group_fields__hp__template__latest_stories__featured_post_selection', $group_id) as $row) :

			$atf_post = new BTW_Atf_Post([
				'item' 				=> $row,
				'primary_term' 		=> $primary_term,
				'image_srcsets'	=> array(
					array(
						'image_size'   => 'large_square',
						'media_query'  => '(max-width: 767px )',
						'mobile'	   => true,
					),
					array(
						'image_size'  => 'medium_horizontal',
						'default'     => true,
					),
				),
				'render_attrs' 	  => [
					'img_type' 		=> 'overlay_landscape_img',
					'article_type'  => 'overlay_article'
				]

			]);

			$atf_post->render();

		endforeach;


		/**
		 * Skitso
		 */
		$skitso = get_field('btw__group_fields__hp__template__latest_stories__skitso')[0];

		btw_get_template_part('template-parts/post_content/skitso', [
			'post' => $skitso,
		]);


		/**
		 * Accuweather
		 */
		btw_get_template_part('template-parts/post_content/weather');


		foreach (get_field('btw__group_fields__hp__template__latest_stories__posts_selection', $group_id) as $row) :

			$atf_post = new BTW_Atf_Post([
				'item' 				=> $row,
				'primary_term' 		=> $primary_term,
			]);

			$atf_post->render();

		endforeach;

		?>

		<aside class="section_sidebar">
			<?php
				btw_get_template_part('template-parts/ads/dfp', [
					'slot_id' => 'latest_stories'
				]);
			?>
		</aside>

	</section>
</div>