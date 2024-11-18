<tr>
	<td style="padding:20px 20px 0px;">
		<?php

		/**
		 * Featured Post
		 * @see BTW_Atf_Post_Newsletter::get_atf_posts()
		 */
		foreach( get_field( 'btw__newsletter_fields__img_post_selection' ) ?: [] as $key => $row ):

			$atf_post = new BTW_Atf_Post_Newsletter([
				'item' 				=> $row,
				'index'             => $key,
				'section_id'        => 'img_post',
				'primary_term' 		=> 'category',
				'image_srcsets'	=> array(
					array(
						'image_size'  => 'medium_horizontal',
						'default'     => true,
					),
				),
				'render_attrs' => [
                    'title_font' => 'normal normal bold 24px/28px Georgia',
				]

			]);



			$atf_post->render();

		endforeach;
		?>
	</td>
</tr>