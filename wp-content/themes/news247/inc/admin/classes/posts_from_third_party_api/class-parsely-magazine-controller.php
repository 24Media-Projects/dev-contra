<?php

namespace News247_Posts_From_Third_Party_Api;

class Parsely_Magazine_Controller{

    protected $db_key = 'parsely_magazine_posts';
    
    public function __construct( $args = [] ){

        add_action( 'btw_parsely_posts_magazine_cron', [ $this, 'cron_handler' ] );
        
    }


    public static function schedule_event(){

        if( !wp_next_scheduled( 'btw_parsely_posts_magazine_cron' ) ){
            wp_schedule_event( time(), 'hourly', 'btw_parsely_posts_magazine_cron' );
        }
    }



    protected function get_posts(){

        try{

            $magazine_category = get_term_by('slug', 'magazine', 'category');
            $parsely_magazine_categories = get_term_children( $magazine_category->term_id, $magazine_category->taxonomy );
            $parsely_magazine_categories[] =  $magazine_category->term_id;

            $parsely_posts = new Parsely_Posts([
                'term_ids' => $parsely_magazine_categories,
                'per_page' => 3,
            ]);

            return $parsely_posts->get_posts();

        } catch (\Exception $e) {
            return [];
        }

    }


    public function db_update(){

        $parsely_posts = array_slice( $this->get_posts(), 0, 3 );

        if( !add_option( $this->db_key, $parsely_posts ) ){
            update_option( $this->db_key, $parsely_posts );
        }
    }


    public function cron_handler(){
        $this->db_update();
    }


}

$btw_parsely_magazine_controller = new Parsely_Magazine_Controller();