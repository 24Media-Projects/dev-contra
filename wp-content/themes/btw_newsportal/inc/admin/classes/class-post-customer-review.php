<?php


class BTW_Customer_Review{

  const ACF_PREVIEW_URL_FIELD = 'field_650985339c281';

  private $customer, $notifications;

  /**
   * @var array
   */
  private $supported_post_types;


  public function __construct(){

    $this->supported_post_types = apply_filters( 'btw/customer_review/supported_post_types', [ 'post' ] );

    $this->notifications = array(
      'error_no_post_name' => 'Παρακαλώ, αποθηκεύστε το άρθρο ή ορίστε post name για να δείτε το url.',
      'error_no_customer_review' => 'Πρέπει να ορίσετε τον customer review για να μπορέσετε να χρησιμοποιήσετε την λειτουγία',
    );

    add_action( 'init', [ $this, 'add_rewrite_endpoint'] );
    add_action( 'wp', [ $this, 'template_redirect' ], 99 );

    add_action( 'acf/save_post', [ $this, 'maybe_update_post_status' ], 30 );

    add_filter( 'acf/load_field/key=' . self::ACF_PREVIEW_URL_FIELD, [ $this, 'set_customer_review_post_url' ], 30 );

    add_filter( 'display_post_states', [ $this, 'display_post_states' ], 10, 2 );

    foreach( $this->supported_post_types as $post_type ){
      add_filter( "views_edit-{$post_type}", [ $this, 'post_table_filter_posts_customer_review' ] );
    }

    add_filter( 'parse_query',[ $this, 'btw_filter_posts' ] );

    // enqueue_scripts
    // add_action( 'admin_enqueue_scripts', [ $this,'admin_enqueue_scripts' ] );

  }


  public function admin_enqueue_scripts(){

    $time = strtotime( 'now' );
    global $current_screen;

    if( $current_screen->base == 'post' && in_array( $current_screen->post_type, $this->supported_post_types ) ){

      wp_register_script( 'customer_review', get_template_directory_uri() . '/assets/js/admin/customer_review.js', array( 'jquery' ), $time, true );

      wp_localize_script( 'customer_review', 'BTW_CR', array(
        'nonce' => wp_create_nonce( 'btw_customer_review' ),
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'notifications' => $this->notifications,
      ));

      wp_enqueue_script( 'customer_review' );

    }

  }


  public function display_post_states( $post_states, $post ){

    if( in_array( $post->post_type, $this->supported_post_types ) && !empty( get_field( 'btw__article_fields__customer_review', $post->ID ) ) ){
      $post_states['pending'] = 'Customer Review';
    }

    return $post_states;

  }


  public function post_table_filter_posts_customer_review( $views ){
    
    global $current_screen;

    $edit_posts_url = esc_url( admin_url( "edit.php?btw_filter_posts=customer_review&post_type={$current_screen->post_type}" ) );
    $total_customer_review_posts = self::get_total_customer_review_posts();
    $class = !empty( $_GET['btw_filter_posts'] ) && $_GET['btw_filter_posts'] == 'customer_review' ? 'class="current"' : '';

    $views['customer_review'] = "<a {$class} href=\"{$edit_posts_url}\">Customer Review ({$total_customer_review_posts})</a>";

    return $views;
  }

  public function get_total_customer_review_posts(){
    global $wpdb, $current_screen;

    $total = $wpdb->get_var("SELECT COUNT( p.ID ) FROM {$wpdb->posts} as p INNER JOIN {$wpdb->postmeta} as m ON m.post_id = p.ID
                              WHERE p.post_type = '{$current_screen->post_type}' AND p.post_status = 'pending' AND m.meta_key = 'btw__article_fields__customer_review' AND m.meta_value = '1'
                            ");

    return $total;
  }



  public function maybe_update_post_status( $post_id ){

    if( !empty( get_field( 'btw__article_fields__customer_review', $post_id ) ) ){

      wp_update_post(array(
        'ID' => $post_id,
        'post_status' => 'pending'
      ));

    }

  }


  public function add_rewrite_endpoint(){
    add_rewrite_endpoint( 'customer-review', EP_ROOT, 'btw_post_customer_review' );
  }


  private function get_customer_by_api_key( $api_key ){
    global $wpdb;

    $api_key_data = array(
      'api_key' => $api_key,
      'hashed_api_key' => wp_hash( $api_key, 'secure_auth' ),
    );

    $api_key_data = maybe_serialize( $api_key_data );

    $customer_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'customer_fields__api_key' AND meta_value = '{$api_key_data}'");

    return $customer_id ? $customer_id : false;
  }

  public function get_post_by_name( $post_name, $post_id ){
    global $wpdb;

    $post_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '{$post_name}' AND ID = '{$post_id}' ");

    return $post_id ? get_post( $post_id ) : null;
  }


  public function template_redirect( $wp ){

    global $wp_query;

    if( !isset( $wp_query->query['btw_post_customer_review'] ) ) return false;

    if( empty( $wp_query->query['btw_post_customer_review'] ) || empty( $_GET['api_key'] ) || empty( $_GET['id'] ) ){
      wp_safe_redirect( site_url() );
      exit();
    }

    $customer_id = self::get_customer_by_api_key( $_GET['api_key'] );

    if( !$customer_id ){
      wp_safe_redirect( site_url() );
      exit();
    }

    $customer_post_previews_access = get_field( 'btw__customer_fields__post_previews_access', $customer_id );

    if( !$customer_post_previews_access ){
      wp_safe_redirect( site_url() );
      exit();
    }

    $post_id = $_GET['id'] ?? null;

    if( !$post_id ){
      wp_safe_redirect(site_url());
      exit();
    }

    $review_post = self::get_post_by_name( $wp_query->query['btw_post_customer_review'], $post_id );

    if( !$review_post || $review_post->post_status != 'pending' ){
      wp_safe_redirect( site_url() );
      exit();
    }

    // Copy the new post global into the main $wp_query.
    $wp_query->posts                = array($review_post);
    $wp_query->post                 = $review_post;
    $wp_query->queried_object       = $review_post;
    $wp_query->queried_object_id    = $review_post->ID;
    $wp_query->post_count           = 1;
    $wp_query->found_posts          = 1;
		$wp_query->is_404               = false;
		$wp_query->is_page              = false;
    $wp_query->is_single            = true;
    $wp_query->is_singular          = true;
		$wp_query->is_archive           = false;
		$wp_query->max_num_pages        = 0;
    $wp_query->query['name']        = $review_post->post_name;
    $wp_query->query_vars['name']   = $review_post->post_name;

		$wp_query->set( 'btw_customer_review', true );

    $wp->register_globals();
    
    $post_type = get_post_type($review_post);

    $post_template = get_post_meta( $review_post->ID, '_wp_page_template', true );

    if( $post_template && $post_template != 'default' ){
      get_template_part( str_replace( '.php', '', $post_template ) );

    }elseif( file_exists( get_stylesheet_directory() . '/single-' . $post_type . '.php' ) ){
      get_template_part( 'single-' . $post_type );
    
    }else{
      get_template_part( 'single' );
    }

    exit();

  }





  public function set_customer_review_post_url( $field ){

    global $post;

    $customer = get_field( 'btw__developer_settings__customer__review', 'option' )['0'] ?? null;

    if( !$customer ){
      $field['message'] = $this->notifications['error']['error_no_customer_review'];
      return $field;
    }

    $post_id = $post ? ( $post->ID ?: '<post_id>' ) : '<post_id>';
    $post_name = $post ? ( $post->post_name ?: '<post_name>' ) : '<post_name>';
    $api_key_data = get_post_meta( $customer->ID, 'customer_fields__api_key', true );
    $api_key = $api_key_data['api_key'];


    $field['message'] = "\n\n" . site_url() . "/customer-review/{$post_name}/?api_key={$api_key}&id={$post_id}";

    return $field;

  }


  public function btw_filter_posts( $query ){

    if( empty( $_GET['btw_filter_posts'] ) || !is_admin() || !$query->is_main_query() || empty( $query->query['post_type'] ) ) return $query;

    if( !in_array( $query->query['post_type'], $this->supported_post_types )  || $_GET['btw_filter_posts'] != 'customer_review' ) return $query;

    global $wpdb;

    $customer_review_post_ids = $wpdb->get_col("SELECT p.ID FROM {$wpdb->posts} as p INNER JOIN {$wpdb->postmeta} as m ON m.post_id = p.ID
                                                WHERE p.post_type = '{$query->query['post_type']}' AND p.post_status = 'pending' AND m.meta_key = 'btw__article_fields__customer_review' AND m.meta_value = '1'
                                              ");

    $query->query_vars['post__in'] = !empty( $customer_review_post_ids ) ? $customer_review_post_ids : array( 0 );

    return $query;
  }





}





$btw_customer_review = new BTW_Customer_Review();
?>
