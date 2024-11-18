<?php

namespace News247_Posts_From_Third_Party_Api;

use WP_CLI\Utils;

/**
 * Run wp posts_from_third_party_api register_cron_events to register cron events each time a new class is added
 */

class WP_Cli_Controller{

    public function register_cron_events(){

        Parsely_Hp_Controller::schedule_event();
        Parsely_Magazine_Controller::schedule_event();
        Newspapers_Hp_Controller::schedule_event();

        Bon_Hp_One::schedule_event();
        Bon_Hp_Two::schedule_event();
        Bon_Hp_Three::schedule_event();
        Bon_Hp_Four::schedule_event();

        Accuweather_Controller::schedule_event();

    }

}


add_action( 'init', function (){

    if( class_exists('WP_CLI') ){
        $instance = new WP_Cli_Controller();
        \WP_CLI::add_command( 'posts_from_third_party_api', $instance );
    }

});