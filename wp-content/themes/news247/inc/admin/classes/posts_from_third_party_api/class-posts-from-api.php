<?php

namespace News247_Posts_From_Third_Party_Api;

abstract class Posts_From_Api{

    abstract protected function get_apis();

    protected function fetch( $api ){

        $response = wp_remote_get( $api['url'], [
            'timeout' => 40,
        ]);

        if( is_wp_error( $response )
            || wp_remote_retrieve_response_code( $response ) !== 200
            || empty( $response['body'] )
        ){
            return [];
        }

        $response_data = json_decode( $response['body'], JSON_OBJECT_AS_ARRAY );
    
        return $this->normalize_data( $response_data, $api );
    }

    
    public function get_posts(){

        try{

            $posts = [];

            $apis = $this->get_apis();

            foreach( $apis as $api ){

                $api_posts = $this->fetch( $api );

                $posts = array_merge( $posts, $api_posts );
            }

            return $this->sort_posts( $posts );

        }catch( \Exception $e ){
            return [];
        }
    }


    protected function sort_posts( $posts ){
        return $posts;
    }


    protected function normalize_data( $response_data, $api ){
        return $response_data;
    }



}