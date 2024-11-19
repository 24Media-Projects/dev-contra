<tr>
	<td style="padding:0px 20px 0px;">
		<?php

		/**
		 * Featured Post
		 * @see BTW_Atf_Post_Newsletter::get_atf_posts()
		 */
		foreach( get_field( 'btw__newsletter_fields__posts_selection' ) ?: [] as $key => $row ):

			$atf_post = new BTW_Atf_Post_Newsletter([
				'item' 				=> $row,
				'index'             => $key,
				'section_id'        => 'posts',
				'primary_term' 		=> 'category',
				'render_attrs' => [
                    'show_image' => false,
	//				'template_name'    => 'magazine_single_sponsored_article',
	//				'background_image' => true,
	//				'article_font' => 'l-article-xl-font',
				]

			]);



			$atf_post->render();

		endforeach;
		?>
	</td>
</tr>