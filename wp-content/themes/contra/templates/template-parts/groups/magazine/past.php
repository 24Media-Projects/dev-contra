<?php
extract( btw_get_magazine_group_fields() );
?>

<section id="group-<?php echo $group_id; ?>" class="past_section half_article_section">

	<?php echo btw_get_impression_url($impression_url); ?>

	<?php

	/**
	 * Featured Post
	 * @see BTW_Atf_Post_Magazine::get_atf_posts()
	 */
	foreach( get_field( 'btw__group_fields__magazine__template__past__featured_post_selection', $group_id ) ?: [] as $key => $row ):

		$atf_post = new BTW_Atf_Post_Magazine([
			'item' 				=> $row,
			'group_id'          => $group_id,
			'primary_term' 		=> 'category',
			'image_srcsets'	=> array(
				array(
					'image_size'   => 'medium_square',
					'media_query'  => '(max-width: 1023px )',
					'mobile'	   => true,
				),
				array(
					'image_size'  => 'large_square',
					'default'     => true,
				),
			),
			'render_attrs' => [
				'template_name'    => 'atf_post_magazine__past',
				'background_image' => true,
                'extra_class'      => ['half_article', 'half_article_large']
//				'article_font' => 'l-article-xl-font',
			]

		]);



		$atf_post->render();

	endforeach;
	?>

</section>