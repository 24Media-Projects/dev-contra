<?php
/*
  All functions related to admin
*/

/*
  Authordiv Callback Function
  Adds a custom authordiv metabox to exlude authors which doesnt have an archive ( user meta: user_has_archive is 0 )
  See
      add_meta_box function
      inc/admin/admin-actions.php
      for more details
*/
function btw_post_author_meta_box( $post ){
	global $user_ID; ?>

    <label class="screen-reader-text" for="post_author_override"><?php _e( 'Author' ); ?></label>
	<?php
	$exlude_users = get_users(array(
		'meta_key'   => 'user_has_archive',
		'meta_value' => '0',
		'fields'	  => 'ids',
	));

	if( !empty( $post->ID ) && in_array( $post->post_author, $exlude_users ) ){
		unset ($exlude_users[ array_search( $post->post_author,$exlude_users ) ] );
		$exlude_users = array_values( $exlude_users );
	}

	wp_dropdown_users(
		array(
			'role__in'         => apply_filters( 'btw/admin/post/post_author_select/roles_in',['author', 'editor', 'manager'] ),
			'name'             => 'post_author_override',
			'selected'         => empty( $post->ID ) ? $user_ID : $post->post_author,
			'include_selected' => true,
			'show'             => 'display_name_with_login',
			'exclude'         => $exlude_users,
		)
	);
}
