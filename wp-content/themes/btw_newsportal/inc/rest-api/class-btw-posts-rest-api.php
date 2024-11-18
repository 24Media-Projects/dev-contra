<?php
/*
  Rest Api Posts Controller class
  prefix base endpoint: see global settings class: rest_api_prefix_base
  Register endpoint: wp-json/wp/v2/<prefix>-posts
  No Public access. Only customers with matching api key can access this rest api routes.
  Extends WP_REST_Controller class
  Returns json object with posts based on:
                                          categories
                                          post_tags
                                          terms
  See:
      https://developer.wordpress.org/reference/classes/wp_rest_controller/
      inc/rest-api/class-btw-rest-api-keys.php
      for more details
*/


class BTW_WP_REST_Post_Controller extends WP_REST_Controller {

  const REST_AUTH_ERROR_NAME = 'btw_feed_rest_authentication_error';

  protected $post_type = 'post';

  protected $post_type_plural = 'posts';

  public function __construct(){

    global $btw_global_settings;

    $this->namespace = 'wp/v2';
    $this->rest_base = $btw_global_settings::rest_api_prefix_base() . '-' . $this->post_type_plural;

  }


  /*
    Register routes for <prefix>-posts
    wp-json/wp/v2/<prefix>-posts/ with args:
                                      api_key: customer api key
                                      categories: comma seperated categories ids
                                      tags: comma seperated post_tags
                                      terms: combination of categories and post_tags in comma seperated format
                                      per_page: default 10, can be up to 50
                                      version: full / light. Default full, can be ommited

    wp-json/wp/v2/<prefix>-posts/<post id> with args:
                                                api_key: customer api key
  */

  public function register_routes() {
    register_rest_route( $this->namespace, '/'. $this->rest_base. '/', array(
      array(
        'methods'              => 'GET',
        'callback'             => array( $this, 'get_post_items' ),
        'permission_callback'  => array( $this, 'check_user_permissions' ),
        'args'                 => $this->get_collection_params( [ 'categories',' tags', 'terms', 'per_page', 'version' ] ),
      )
    ));

    register_rest_route( $this->namespace, '/'. $this->rest_base. '/(?P<id>[\d]+)', array(
      array(
        'methods'              => 'GET',
        'callback'             => array( $this, 'get_post_items' ),
        'permission_callback'  => array( $this, 'check_user_permissions' ),
        'args'                 => $this->get_collection_params(),
      )
    ));

  }


  public function get_collection_params( $params = [] ){

    $query_params['api_key'] = array(
      'description' => 'API KEY to connect to REST API',
      'type'        => 'string',
      'required'    => true,
    );

    if( !empty( $params['categories'] ) ){

      $query_params['categories'] = array(
        'description' => 'Comma separated category ids',
        'type'        => 'string',
      );

    }

    if( !empty( $params['tags'] ) ){

      $query_params['tags'] = array(
        'description' => 'Comma separated tag ids',
        'type'        => 'string',
      );

    }

    if( !empty( $params['terms'] ) ){

      $query_params['terms'] = array(
        'description' => 'Comma separated term ids',
        'type'        => 'string',
      );

    }

    if( !empty( $params['per_page'] ) ){

      $query_params['per_page'] = array(
        'description' => 'Total Posts Per Page',
        'type'        => 'integer',
      );

    }

    if( !empty( $params['version'] ) ){

      $query_params['version'] = array(
        'description' => 'full or light. Default is full',
        'type'        => 'string',
      );

    }

    return $query_params;

  }


  /*
    Returns posts as json object
    if is single post, get the post id with $request['id']
    See get_item_data function for more details
  */
  public function get_post_items( $request ){

    $base_api_post_controller = new BTW_Base_Api_Post_Controller();
    $requested_post_id = $request['id'] ?? '';

    $posts = $base_api_post_controller->get_post_items( $requested_post_id, $this->post_type );

    return new WP_REST_Response( $posts, 200 );

  }

  /*
    Get customer with provided api key.
    Return customer id / false if no match
  */
  protected function get_customer_by_api_key( $api_key ){
    global $wpdb;

    $api_key_data = array(
      'api_key' => $api_key,
      'hashed_api_key' => wp_hash( $api_key, 'secure_auth' ),
    );

    $api_key_data = maybe_serialize( $api_key_data );

    $customer_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'customer_fields__api_key' AND meta_value = '{$api_key_data}'");

    return $customer_id ? $customer_id : false;
  }

  /*
    Access to this Rest Api feed is only for customer with matching api_key
    See above get_customer_by_api_key function for more details
  */

  public function check_user_permissions( $request ){

    if( $request->get_method() != 'GET' ){
      return new WP_Error( self::REST_AUTH_ERROR_NAME, 'No routes registered', array( 'status' => 404 ) ) ;
    }

    $request_api_key = $_GET['api_key'] ?? null;
    if( !$request_api_key ){
      return new WP_Error( self::REST_AUTH_ERROR_NAME, 'Customer\'s key is missing', array( 'status' => 401 ) );
    }

    $customer_id = self::get_customer_by_api_key( $request_api_key );
    if( !$customer_id ){
      return new WP_Error( self::REST_AUTH_ERROR_NAME, 'No customer provided', array( 'status' => 401 ) );
    }

    return $this->check_user_post_type_access( $request, $customer_id );

  }



  protected function check_user_post_type_access( $request, $customer_id ){

    $customer_rest_api_post_type_access = get_field( 'btw__customer_fields__rest_api_posts_access', $customer_id );

    if( in_array( 'categories', $customer_rest_api_post_type_access ) && in_array( 'tags', $customer_rest_api_post_type_access ) ){
      $customer_rest_api_post_type_access[] = 'terms';
    }

    $request_access = array(
      'categories' => !empty( $_GET['categories'] ),
      'tags'       => !empty( $_GET['tags'] ),
      'terms'      => !empty( $_GET['terms'] ),
    );

    foreach( $request_access as $type => $request ){
      if( ( $request && !in_array( $type, $customer_rest_api_post_type_access ) ) || !in_array( $this->post_type_plural, $customer_rest_api_post_type_access ) ) {
        return new WP_Error( self::REST_AUTH_ERROR_NAME, 'This customer has no access to this route', array( 'status' => 403 ) );
      }
    }

    return true;

  }


}

add_action( 'rest_api_init', function () {
    $btw_posts = new BTW_WP_REST_Post_Controller();
    $btw_posts->register_routes();
});



 
