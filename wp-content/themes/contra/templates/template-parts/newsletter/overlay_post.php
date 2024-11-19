<tr>
	<td style="padding:30px 20px 0;">
		<?php

		/**
		 * Featured Post
		 * @see BTW_Atf_Post_Newsletter::get_atf_posts()
		 */
		foreach( get_field( 'btw__newsletter_fields__overlay_post_selection' ) ?: [] as $key => $row ):

			$atf_post = new BTW_Atf_Post_Newsletter([
				'item' 				=> $row,
				'index'             => $key,
				'section_id'        => 'overlay_post',
				'primary_term' 		=> 'category',
				'image_srcsets'	=> array(
					array(
						'image_size'  => 'medium_landscape',
						'default'     => true,
					),
				),
				'render_attrs' => [
					'template_name'    => 'atf_post_newsletter__overlay_post',
	//				'background_image' => true,
	//				'article_font' => 'l-article-xl-font',
				]

			]);



			$atf_post->render();

		endforeach;
		?>
	</td>
</tr>