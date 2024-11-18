<?php

namespace News247_Posts_From_Third_Party_Api;

class Accuweather_Controller {

    protected $db_key = 'accuweather_forcast_posts';
    
    public function __construct( $args = [] ){

        add_action( 'btw_accuweather_cron', [ $this, 'cron_handler' ] );
        
    }


    public static function schedule_event(){

        if( !wp_next_scheduled( 'btw_accuweather_cron' ) ){
            wp_schedule_event( time(), 'hourly', 'btw_accuweather_cron' );
        }
    }


    protected function get_posts(){

        try{

            $accuweather_posts = new Accuweather_Posts();

            return $accuweather_posts->get_posts();

        } catch (\Exception $e) {
            return [];
        }

    }

    public function db_update(){

        $accuweather_posts = $this->get_posts();

        if( !add_option( $this->db_key, $accuweather_posts ) ){
            update_option( $this->db_key, $accuweather_posts );
        }
    }


    public function cron_handler(){
        $this->db_update();
    }


}

$news247_accuweather_controller = new Accuweather_Controller();