<?php


class Contra_Feeds_Rest_Controller extends BTW_WP_REST_Post_Controller {

  public function __construct(){

    global $btw_global_settings;

    $this->namespace = 'wp/v2';
    $this->rest_base = $btw_global_settings::rest_api_prefix_base() . '-feed';

  }


  /*
    Register routes for <prefix>-feed
    wp-json/wp/v2/<prefix>-feed/<feed id> with args:
                                              api_key: customer api key

  */
  public function register_routes() {

    register_rest_route( $this->namespace, '/'. $this->rest_base. '/(?P<id>[\d]+)', array(
      array(
        'methods'              => 'GET',
        'callback'             => array( $this, 'get_feed_item' ),
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

    return $query_params;

  }

  protected function check_user_post_type_access( $request, $customer_id ){

    $feed_request = !empty( $request['id'] ) ? get_post( $request['id'] ) : null;

    if( !$feed_request ){
      return new WP_Error( self::REST_AUTH_ERROR_NAME, 'No routes registered', array('status' => 404));
    }

    return true;

  }


  public function get_feed_item( $request ){

    $base_api_feeds_controller = new BTW_Base_Api_Feeds_Controller();

    $posts = $base_api_feeds_controller->get_feed_item( $request['id'] );

    return new WP_REST_Response($posts, 200 );

  }


}



add_action('rest_api_init', function () {

  $btw_feeds = new Contra_Feeds_Rest_Controller();
  $btw_feeds->register_routes();

});
