<?php

namespace Contra_Posts_From_Third_Party_Api;

class Accuweather_Posts extends Posts_From_Api {

    private $athens_city_id = '182536';

    private $api_version = 'v1';

    private $api_key = '8e15aff3448c4ae2b044c0e2015dc1a3';

    private $hostname_prefix = 'apidev';

    protected $forcast_api_url = 'http://<hostname_prefix>.accuweather.com/forecasts/<version_id>/hourly/12hour/<city_id>?apikey=<api_key>&metrics=true&details=true';

    
    public function __construct( $args = [] ){

        
    }


    private function get_api_url(){
        return str_replace(
            [ '<hostname_prefix>', '<version_id>', '<city_id>', '<api_key>' ],
            [ $this->hostname_prefix, $this->api_version, $this->athens_city_id, $this->api_key ],
            $this->forcast_api_url
        );
    }


    protected function get_apis(){
        return array(
            array(
                'url' => $this->get_api_url(),
            )
        );
    }


    private function format_temperature( $temperature ){

        $t = $temperature['Value'];

        if( $temperature['Unit'] == 'F' ){
            $t = ( $t - 32 ) * 5/9;
        }

        return round( $t, 0, PHP_ROUND_HALF_UP );
    }


    protected function sort_posts( $posts ){

        $posts = array_map( function( $post ){
            return [
                'temperature'           => $this->format_temperature( $post['Temperature'] ),
                'icon_id'               => $post['WeatherIcon'],
                'real_feel_temperature' => $this->format_temperature( $post['RealFeelTemperature'] ),
                'datetime'              => $post['DateTime'],
            ];
        }, $posts );

        return array_filter( $posts, function( $index ){
            return in_array( $index, [ 0, 3, 7, 11 ] );
        }, ARRAY_FILTER_USE_KEY );
    }

}