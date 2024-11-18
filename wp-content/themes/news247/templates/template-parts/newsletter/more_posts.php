<tr>
	<td style="padding:0px 20px 0px;">
		<?php

		/**
		 * Featured Post
		 * @see BTW_Atf_Post_Newsletter::get_atf_posts()
		 */
        $more_posts = get_field( 'btw__newsletter_fields__more_posts_selection' ) ?: [];
		foreach( $more_posts as $key => $row ):

			$atf_post = new BTW_Atf_Post_Newsletter([
				'item' 				=> $row,
				'index'             => $key,
				'section_id'        => 'more_posts',
				'primary_term' 		=> 'category',
				'render_attrs' => [
                    'show_image'         => false,
					'hide_border_bottom' => array_key_last($more_posts) === $key,
					// 'template_name'    => 'magazine_single_sponsored_article',
				]

			]);



			$atf_post->render();

		endforeach;
		?>
	</td>
</tr>