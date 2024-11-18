<?php

/*
  Rest API Access class
  Remove public access to default wp Rest API. For functionality reasons, wp-admin and wp ajax can use it.
  Aslo a user: administrator and manager have access to those endpoints
  See:
      rest_authentication_errors hook
      https://developer.wordpress.org/rest-api/

       for more details
*/


class BTW_Rest_Api_Access { 

  public function __construct(){

    add_filter( 'rest_authentication_errors', [ $this, 'rest_api_request_access' ] );

  }

  private function rest_api_base_endpoints(){

    global $btw_global_settings;
    $rest_api_prefix_base = $btw_global_settings::rest_api_prefix_base();

    $endpoints = [];

    $supported_types = $btw_global_settings::get_rest_api_support_types();

    foreach( $supported_types as $type ){
      $endpoints[ $type ] = $rest_api_prefix_base . '-posts';
    }
    
    return $endpoints;

  }


  public function rest_api_request_access( $result ){

    if( !empty( $result ) || is_admin() || wp_doing_ajax() ) return $result;

    // return new WP_Error('rest_not_logged_in', 'No routes registered.', array('status' => 404));
    return;

    global $btw_global_settings;

    $request = $_SERVER['REQUEST_URI'];

    $is_feed_request = array_filter( self::rest_api_base_endpoints(), function( $base_endpoint ) use( $request ){
      return strpos( $request,  $base_endpoint ) !== false;
    });

    if( !empty( $is_feed_request ) ) return $result;

    if ( !is_user_logged_in() ) {
      return new WP_Error( 'rest_not_logged_in', 'No routes registered.', array( 'status' => 404 ) );
    }

    $user = wp_get_current_user();

    if( !user_min_cap_manager( $user ) ){
      return new WP_Error( 'rest_not_logged_in', 'No routes registered.', array( 'status' => 404 ) );
    }


  }


}


$btw_rest_api_access = new BTW_Rest_Api_Access();


