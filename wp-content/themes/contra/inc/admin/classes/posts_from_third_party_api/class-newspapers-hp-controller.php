<?php

namespace Contra_Posts_From_Third_Party_Api;

class Newspapers_Hp_Controller {

    protected $db_key = 'newspapers_hp_posts';
    
    public function __construct( $args = [] ){

        add_action( 'btw_newspapers_hp_cron', [ $this, 'cron_handler' ] );
        
    }


    public static function schedule_event(){

        if( !wp_next_scheduled( 'btw_newspapers_hp_cron' ) ){
            wp_schedule_event( time(), 'hourly', 'btw_newspapers_hp_cron' );
        }
    }


    protected function get_group_ids(){

        global $wpdb;

        $group_ids = $wpdb->get_col(
            "SELECT p.ID FROM $wpdb->posts as p
             LEFT JOIN $wpdb->postmeta as m1 ON p.ID = m1.post_id AND m1.meta_key = 'btw__group_fields__hp__template'
             LEFT JOIN $wpdb->postmeta as m2 ON p.ID = m2.post_id AND m2.meta_key = 'btw__group_fields__group_type'
             WHERE p.post_status = 'publish' AND p.post_type = 'group' AND m1.meta_value = 'newspaper_headlines' AND m2.meta_value = 'hp'"
        );

        return $group_ids ?: [];
    }






    protected function get_posts(){

        try{

             $newspapers_posts = new Newspapers_Posts();

             return $newspapers_posts->get_posts();


        } catch( \Exception $e ){
            return [];
        }

    }



    public function update_post_meta( $group_id ){

        $newspapers_posts = $this->get_posts();

        if( !add_post_meta( $group_id, $this->db_key, $newspapers_posts, true ) ){
            update_post_meta( $group_id, $this->db_key, $newspapers_posts );
        }

    }

    public function cron_handler(){

        $group_ids = $this->get_group_ids();

        foreach( $group_ids as $group_id ){
            $this->update_post_meta( $group_id );
        }

    }


}

$contra_newspapers_hp_controller = new Newspapers_Hp_Controller();