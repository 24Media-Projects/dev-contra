<?php
extract( btw_get_hp_group_fields() );
?>

<div class="home_wrapper above_the_fold__wrapper">

	<?php 
	/**
	 * Big Story
	 * @see BTW_Atf_Post::get_atf_post()
	 */
	foreach( get_field( 'btw__group_fields__hp__template__above_the_fold__big_story_post_selection' ) ?: [] as $row ):

		$atf_post = new BTW_Atf_Post([
			'item'         	 => $row,
			'primary_term' 	 => $primary_term,
			'render_attrs' => [
				'template_name' => 'big_story',
				'article_type' 	=> false,
				'lazyload'		=> false,
			]
		]);

		$atf_post->render();

	endforeach;
	?>

    <section id="<?php echo $section_id; ?>" class="above_the_fold">
		<!-- <h2 class="invisible">Top Stories</h2> -->

        <?php echo btw_get_impression_url($impression_url); ?>

		<div class="center_article">
			<?php

			/** 
			 * Featured Post
			 * @see BTW_Atf_Post::get_atf_posts()
			 */
			foreach( get_field( 'btw__group_fields__hp__template__above_the_fold__featured_post_selection', $group_id ) ?: [] as $row ):

				$atf_post = new BTW_Atf_Post([
					'item' 				=> $row,
					'primary_term' 		=> $primary_term,
					'image_srcsets'	=> array(
						array(
							'image_size'   => 'medium_square',
							'media_query'  => '(max-width: 767px )',
							'mobile'	   => true,
						),
						array(
							'image_size'  => 'medium_landscape',
							'default'     => true,
						),
					),
					'render_attrs' => [
						'article_font' => 'l-article-xl-font',
						'truncate' => 3,
						'lazyload' => false,
					],
 
				]);

				

				$atf_post->render();

			endforeach;
			?>
		</div>

		<div class="latest_news">
            <?php
			btw_get_template_part( 'global_elements/homepage__eidiseis', [
                'img_size' => 'small_square',
            ] ); ?>
		</div>
	
		<?php
		/**
		 * Rest post
		 * @see BTW_Atf_Post::get_atf_posts()
		 */
		foreach( get_field( 'btw__group_fields__hp__template__above_the_fold__posts_selection', $group_id ) ?: [] as $row ):

			$atf_post = new BTW_Atf_Post([
				'item' 				=> $row,
				'primary_term' 		=> $primary_term,
					'render_attrs' => [
						'lazyload' => false,
					],

			]);

			$atf_post->render();

		endforeach;
		?>
	</section>

</div>


