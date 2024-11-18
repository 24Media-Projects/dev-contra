<?php

/*
  Private Post Types Class
  Post Types: group, customer, feed
  Remove permalinks and front end access, except preview of post type group
  Add columns in wp-admin list posts
  Add filters in wp-admin list posts
  See
    cptui plugin
    https://developer.wordpress.org/reference/functions/register_post_type/
    for more details
*/


class BTW_PRIVATE_POST_TYPES{

  public $post_types = array(
    'feed' => array(
      'manage_columns' => true,
      'custom_column' => true,
      'sortable_columns' => false,
      'filter_options' => array(
        'filter_name' => 'feed_customer',
        'acf_field' => 'btw__feed_fields__customer',
      ),
      'frontend_access' => false,
    ),

    'customer' => array(
      'manage_columns' => false,
      'custom_column' => false,
      'sortable_columns' => false,
      'filter_options' => false,
      'frontend_access' => false,
    ),

    'group' => array(
      'manage_columns' => true,
      'custom_column' => true,
      'sortable_columns' => true,
      'filter_options' => array(
        'filter_name' => 'group_type',
        'acf_field' => 'btw__group_fields__group_type',
      ),
      'frontend_access' => false,
    ),
  );

  public function __construct(){

    foreach( $this->post_types as $post_type => $options ){

      self::set_post_types_hooks( $post_type, $options );
    }

  }

  /*
    See manage_{$post_type}_post_columns hook,
        manage__{$post_type}_posts_custom_column action
        manage_edit-{$post_type}_sortable_columns hook
        restrict_manage_posts hook

    for more details
  */

  private function set_post_types_hooks($post_type, $options){

    if( $options['manage_columns'] ){
      add_filter( "manage_{$post_type}_posts_columns", [ $this, "manage_{$post_type}_columns" ] );
    }

    if( $options['custom_column'] ){
      add_action( "manage_{$post_type}_posts_custom_column", [ $this, "{$post_type}_custom_column" ], 10, 2 );
    }

    if( $options['sortable_columns'] ){
      add_filter( "manage_edit{$post_type}_sortable_columns", [ $this, "{$post_type}_sortable_columns" ], 10, 2 );
    }

    if( !empty( $options['filter_options'] ) ){
      add_action('restrict_manage_posts',[ $this, "{$post_type}_filter_options" ] );
    }

    add_filter('parse_query',[ $this,'filter_options_query' ] );
    add_filter( 'wpseo_accessible_post_types', [ $this,'remove_yoast_functionality' ] );

    add_action( 'wp', [ $this, 'remove_frontend_access' ] );

  }


  public function remove_yoast_functionality( $post_types ){

    foreach( $this->post_types as $post_type => $options ):
      unset( $post_types[ $post_type ] );
    endforeach;

    return $post_types;

  }



  /*
    Post type: Group. Columns
  */
  public function manage_group_columns( $columns ){

    $columns['title']                   = __( 'Title', 'btw' );
    $columns['date']                    = __( 'Date', 'btw' );
	$columns['type']                    = __( 'Group Type', 'btw' );
	$columns['template']                = __( 'Group Template', 'btw' );
    $columns['menu_order']              = __( 'Order', 'btw' );

    return $columns;
  }

  /*
    Post type: Feed. Add custom columns
  */
  public function manage_feed_columns( $columns ){

    return array_merge( $columns, array(
      'customer'  => 'Πελάτης',
      'groups'    => 'Groups',
      'api_url'   => 'API URL',
    ));

  }


  /*
    Post type: Group. Columns values
  */

  public function group_custom_column( $column, $post_id ) {
    switch ( $column ) {

      case 'type' :
        echo get_post_meta( $post_id , 'btw__group_fields__group_type' , true );
        break;

    case 'template' :
		$template = get_post_meta( $post_id , 'btw__group_fields__group_type' , true );
		echo get_post_meta( $post_id , 'btw__group_fields__'.$template.'__template' , true );
        break;

      case 'menu_order' :
        $thispost = get_post( $post_id );
        $menu_order = $thispost->menu_order;
        echo $menu_order;
        break;
    }
  }

  /*
    Post type: Feed. Columns values
  */

  public function feed_custom_column( $column, $post_id ) {

    global $btw_global_settings;

    switch ( $column ) {

      case 'customer' :
        echo get_field( 'btw__feed_fields__customer', $post_id )->post_title;
        break;

      case 'api_url' :
        $customer = get_field( 'btw__feed_fields__customer', $post_id );
        $customer_api_key = get_post_meta( $customer->ID, 'customer_fields__api_key', true );
        $api_url = get_rest_url() . "wp/v2/{$btw_global_settings::rest_api_prefix_base()}-feed/{$post_id}?api_key={$customer_api_key[ 'api_key' ]}";

        echo  '<a target="_blank" href="' . $api_url . '">' . $api_url . '</a>';
        break;

      case 'groups' :
        $groups = get_field( 'btw__feed_fields__group', $post_id );
        if( !$groups ) break;

        $groups_names = [];
        foreach( $groups as $group ){
          $groups_names[] = $group->post_title;
        }
        echo implode( "<br />", $groups_names );
        break;
    }

  }

  /*
    Post type: Group. Add menu_order in sortable columns
  */

  public function group_sortable_columns( $columns ) {
    $columns['menu_order'] = 'menu_order';
    return $columns;
  }

  /*
    Post type: Feed. Filter feeds by customer
  */
  public function feed_filter_options( $post_type ){

    if( $post_type != 'feed' ) return;

		$selected = !empty( $_REQUEST['feed_customer'] ) ? $_REQUEST['feed_customer'] : '';
		$customers = get_posts( array(
			'post_type' => 'customer',
			'posts_per_page' => -1,
		));
		?>
		<select name="feed_customer">
			<option <?php echo selected( $selected, 0 );?> value="all">Όλοι οι Πελάτες</option>
			<?php foreach( $customers as $customer ): ?>

				<option <?php echo selected( $selected, $customer->ID );?> value="<?php echo $customer->ID;?>"><?php echo $customer->post_title;?></option>

			<?php endforeach; ?>
		</select>

  <?php }

  /*
    Post type: Group. Filter groups by group template
  */
  public function group_filter_options($post_type){

    if( $post_type != 'group' ) return;

    $selected = !empty( $_REQUEST['group_type'] ) ? $_REQUEST['group_type'] : ''; ?>

		<select name="group_type">
			<option <?php echo selected( $selected, 0 );?> value="all">Όλα τα templates</option>
            <option <?php echo selected( $selected, 'hp' );?> value="hp">Homepage</option>
            <option <?php echo selected( $selected, 'bon' );?> value="bon">Best of network</option>
            <option <?php echo selected( $selected, 'magazine' );?> value="magazine">The Magazine</option>
		</select>

  <?php }

  /*
    Alter the main query in list posts to match filters for current post type
  */
  public function filter_options_query($query){

    if( !is_admin() || !$query->is_main_query() || empty( $query->query['post_type'] ) ) return $query;

    $current_post_type = array_filter( $this->post_types, function( $v, $k ) use( $query ){
      return !empty( $v['filter_options'] ) && $query->query['post_type'] == $k;
    }, ARRAY_FILTER_USE_BOTH );

     if( !in_array( $query->query['post_type'], array_keys( $current_post_type ) ) ) return $query;

    global $wpdb;
    foreach( $current_post_type as $post_type => $options ){
      $filter_options = $options['filter_options'];
      $filter_name = $filter_options['filter_name'];
      $filter_acf = $filter_options['acf_field'];

      if( empty( $_REQUEST[$filter_name] ) || $_REQUEST[ $filter_name ] == 'all' ) return $query;

    	$filter = $_REQUEST[ $filter_options['filter_name'] ];
    	$posts = $wpdb->get_col("SELECT p.ID
    														FROM $wpdb->posts as p
    														INNER JOIN $wpdb->postmeta as m1 ON m1.post_id = p.ID AND m1.meta_key = '{$filter_acf}'
    														WHERE p.post_type = '{$post_type}' AND p.post_status = 'publish' AND m1.meta_value = '$filter'
    														GROUP BY p.ID
    													");

    	$query->query_vars['post__in'] = !empty( $posts ) ? $posts : array( 0 );
    }

  	return $query;
  }

  /*
    Remove front end access, except preview of post type group, and redirect any single() url to homepage
  */
  public function remove_frontend_access(){

    $post_types = array_filter( $this->post_types, function( $v ){
      return $v['frontend_access'] === false;
    });

    foreach( array_keys( $post_types ) as $post_type ){
      if (is_singular( $post_type ) && empty( $_REQUEST['preview'] ) ){
        wp_safe_redirect( site_url() );
        exit();
      }
    }

  }



}






$btw_private_post_types = new BTW_PRIVATE_POST_TYPES();
?>
