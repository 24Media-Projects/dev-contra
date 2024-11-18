<?php
/*
  All actions related to admin
  See add_action function for more details
*/

/**
 * Hide update_nag if user cannot update_core
 */
add_action('admin_menu', function(){
	if ( !current_user_can( 'update_core' ) ) {
		remove_action('admin_notices', 'update_nag', 3);
	}
});

/*
  Remove the default wp post author meta box and add a custom to exlude authors which doesnt have an archive ( user meta: user_has_archive is 0 )
  See
      add_meta_boxes action
      authordiv metabox
      remove_meta_box function
      add_meta_box function
      inc/admin/classes/class-btw-users-roles-capabilites.php
      inc/admin/admin-functions.php
      For more details
*/

add_action(' add_meta_boxes', function( $post ){
	remove_meta_box( 'authordiv','post', 'normal' );
	add_meta_box( 'btw_authordiv', __( 'Author' ), 'btw_post_author_meta_box', null, 'normal', 'core', array( '__back_compat_meta_box' => true ) );
});



/*
  Offload to S3 plugin ( WP Offload Media Lite ) has default functionality to save content images as local url to DB and transform the to S3 url on front end
  This functionality makes slow queries to db, so we remove it and save content images with S3 url to DB
  See:
      https://deliciousbrains.com/wp-offload-media/docs/
      https://github.com/deliciousbrains/wp-amazon-s3-and-cloudfront
      for more details

*/
add_action( 'init', function() {

  global $wp_filter;

  foreach ( $wp_filter as $tag => $wpHook ) {
    foreach ( $wpHook->callbacks as $priority => $callbacks ) {
      foreach ( $callbacks as $functionKey => $callback ) {
        if ( is_array( $callback[ 'function' ] ) && $callback[ 'function' ][ 0 ] instanceof \AS3CF_S3_To_Local ) {
          remove_filter( $tag, $callback[ 'function' ] );
        }
      }
    }
  }

}, 1000 ); // Fired after WP Offload Media's "as3cf_init" action



/*
  wp list posts table: post type group
  When query var orderby is set and is menu_order,
  Sort posts by meta_key: menu_order
  See pre_get_posts action for more details
*/
add_action( 'pre_get_posts',function( $query ) {

  if( is_admin() && $query->is_main_query() && $query->get( 'post_type' ) == 'group' ){

	  if( !$query->get( 'orderby') ) {
      $query->set( 'orderby', 'menu_order' );
      $query->set( 'order', 'asc' );
	  }
  }

});


add_action('restrict_manage_posts', function( $post_type ){

	if( !in_array($post_type, ['post', 'page']) ) return;

	$selected = $_REQUEST['template'] ?? 0;

	$templates = get_page_templates(null, $post_type);

	$templates = array_merge( ['Default' => 'default'], $templates );

	?>
    <select name="template">
        <option <?php echo selected( $selected, 0 );?> value="all">All Templates</option>
		<?php foreach( $templates as $name => $value ): ?>

            <option <?php echo selected( $selected, $value );?> value="<?php echo $value;?>"><?php echo $name;?></option>

		<?php endforeach; ?>
    </select>
	<?php
});

add_action('pre_get_posts', function( $query ){
	if (
		!is_admin() ||
		! $query->is_main_query() ||
		( isset( $_GET['post_type'] ) && !in_array($_GET['post_type'], ['post', 'page']) ) ||
		!isset( $_REQUEST['template'] ) ||
		$_REQUEST['template'] == 'all'
	) {
		return;
	}

	$posts = get_posts([
		'numberposts' => -1,
		'post_type' => $_GET['post_type'],
		'post_status' => 'any',
		'meta_key' => '_wp_page_template',
		'meta_value' => $_REQUEST['template'],
		'fields' => 'ids',
	]);

	$query->set('post__in', $posts ?: [0]);

} );








//////////////////////////////////////////////////////////
/// // POSTS FILTERING BY PRIMARY CATEGORY - START
//////////////////////////////////////////////////////////



add_action('restrict_manage_posts', function( $post_type ){

	if( $post_type != 'post' || !user_is_admin() ) return;

	$selected = $_REQUEST['primary_category'] ?? 0;
	?>
    <select name="primary_category">
        <option <?php echo selected( $selected, 0 );?> value="all">All Primary Categories</option>
		<?php foreach( get_categories(['hide_empty' => false]) as $term ): ?>
            <option <?php echo selected( $selected, $term->term_id );?> value="<?php echo $term->term_id;?>"><?php echo $term->name;?></option>
		<?php endforeach; ?>
    </select>
	<?php
});

add_action('pre_get_posts', function( $query ){
	if (
		!is_admin() ||
		! $query->is_main_query() ||
		( isset( $_GET['post_type'] ) && $_GET['post_type'] !== 'post' ) ||
		!isset( $_REQUEST['primary_category'] ) ||
		$_REQUEST['primary_category'] == 'all' ||
        !user_is_admin()
	) {
		return;
	}

    $cat_ID = $_REQUEST['primary_category'];


	$prim_cat_query = new WP_Query([
		'post_type'            => 'post',
		'post_status'          => 'publish',
		'posts_per_page'       => -1,
        'meta_key'             => '_yoast_wpseo_primary_category',
        'meta_value'           => $cat_ID,
	]);
	$prim_cat_ids = wp_list_pluck( $prim_cat_query->get_posts(), 'ID' );


	$only_one_cat_query = new WP_Query([
		'post_type'            => 'post',
		'post_status'          => 'publish',
		'posts_per_page'       => -1,
		'meta_key'             => '_yoast_wpseo_primary_category',
		'meta_compare'         => 'NOT EXISTS',
		'tax_query'            => [
			[
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => [$cat_ID]
			],
		]
	]);
	$only_one_cat_ids = wp_list_pluck( $only_one_cat_query->get_posts(), 'ID' );


	$query->set('post__in', array_merge($prim_cat_ids, $only_one_cat_ids) ?: [0]);

} );



//////////////////////////////////////////////////////////
/// // POSTS FILTERING BY PRIMARY CATEGORY - END
//////////////////////////////////////////////////////////