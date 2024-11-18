<?php

class BTW_Register_Xml_Api{

    public function __construct(){

        add_action( 'init', [ $this, 'add_rewrite_endpoint'] );

        add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

        add_action( 'parse_query', [ $this, 'parse_query' ], 5 );

  	
    }

    public function add_rewrite_endpoint(){

        add_rewrite_endpoint( 'xml/v1', EP_PERMALINK, 'xml_api_type' );
    }


   

    public function add_query_vars( $vars ) {
		$vars[] = 'xml_api_post_type';
        $vars[] = 'xml_api_post_id';
        $vars[] = 'xml_format';
		return $vars;
	}

    /** 
     * Check if post exist
     */
    public function parse_query( $wp_query ){
        // global $wp_query;

        if( !empty( $wp_query->query['xml_api_post_type'] ) && !empty( $wp_query->query['xml_api_post_id'] ) ){

            $xml_post_type = $wp_query->query['xml_api_post_type'];
            $xml_post_id = $wp_query->query['xml_api_post_id'];
            
            /**
             * Check if post exist
             */
            if( !$this->post_exists( $xml_post_type, $xml_post_id ) ){
                _xml_wp_die_handler( 'Not Available', 'This post is exluded from feed.', ['code' => 403, 'response' => 403]);
            }
        }
    }


    protected function post_exists( $post_type, $id ){

        global $wpdb;

        if( $post_type == 'feed' ){

            $post_id = $wpdb->get_var(
                "SELECT ID FROM $wpdb->posts
                WHERE ID = '{$id}' AND post_status = 'publish'"
            );

            return !empty($post_id);            
        }
        

        $post_id = $wpdb->get_var(
            "SELECT p.ID FROM $wpdb->posts as p
             INNER JOIN $wpdb->postmeta as m on m.post_id = p.ID AND m.meta_key = 'btw__global_fields__hide_from_feed'
             WHERE p.ID = '{$id}' AND p.post_status = 'publish' AND p.post_type = '{$post_type}' AND m.meta_value = '0'"
        );

        return !empty( $post_id );
    }


}

$btw_register_xml_api = new BTW_Register_Xml_Api();
