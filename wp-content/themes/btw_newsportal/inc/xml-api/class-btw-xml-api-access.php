<?php 

/**
 * Every customer has access on xml api ( rss )
 * Api key is required
 */
class BTW_Xml_Api_User_Access{

    public function __construct(){

        add_action( 'parse_query', [ $this, 'parse_query' ], 1 );
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



    public function parse_query( $query ){

        if( !is_feed() && empty( $query->query['xml_api_post_type'] ) ){
            return;
        }

        if( is_user_logged_in() ){
            return;
        }

        if( empty( $_GET['api_key'] ) ){
            header( 'HTTP/1.1 403 Unauthorized. Customer\'s key is missing' );
            _xml_wp_die_handler('Unauthorized', 'Customer\'s key is missing', [ 'code' => 403, 'response' => 403 ]);
            exit();
        }

        if( !$this->get_customer_by_api_key($_GET['api_key'] ) ){
            _xml_wp_die_handler('Unauthorized', 'No customer provided', ['code' => 403, 'response' => 403]);
            exit();
        }

    }


}

$btw_xml_api_user_access = new BTW_Xml_Api_User_Access();