<tr>
	<td style="padding:30px 20px 10px;">
		<?php

		/**
		 * Featured Post
		 * @see BTW_Atf_Post_Newsletter::get_atf_posts()
		 */
		foreach( get_field( 'btw__newsletter_fields__bg_color_post_selection' ) ?: [] as $key => $row ):

			$atf_post = new BTW_Atf_Post_Newsletter([
				'item' 				=> $row,
				'index'             => $key,
				'section_id'        => 'bg_color_post',
				'primary_term' 		=> 'category',
				'render_attrs' => [
					'template_name' => 'atf_post_newsletter__with_paddings',
                    'show_image'    => false,
	//				'background_image' => true,
	//				'article_font' => 'l-article-xl-font',
				]

			]);



			$atf_post->render();

		endforeach;
		?>
	</td>
</tr>