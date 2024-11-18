<?php 

class BTW_Cron_Schedules{

    public function __construct(){

        add_filter('cron_schedules', [$this, 'cron_schedules']);

    }


    public function cron_schedules( $schedules ){

        $schedules["every_1_minute"] = array(
            'interval' => 60,
            'display' => __('Every 1 minute')
        );

        $schedules["every_2_minutes"] = array(
            'interval' => 120,
            'display' => __('Every 2 minutes')
        );

        $schedules["every_3_minutes"] = array(
            'interval' => 180,
            'display' => __('Every 3 minutes')
        );

        $schedules["every_4_minutes"] = array(
            'interval' => 240,
            'display' => __('Every 4 minutes')
        );

        $schedules["every_5_minutes"] = array(
            'interval' => 300,
            'display' => __('Every 5 minutes')
        );

        $schedules["every_6_minutes"] = array(
            'interval' => 360,
            'display' => __('Every 6 minutes')
        );

        $schedules["every_8_minutes"] = array(
            'interval' => 480,
            'display' => __('Every 8 minutes')
        );

        $schedules["every_10_minutes"] = array(
            'interval' => 600,
            'display' => __('Every 10 minutes')
        );

        return $schedules;

    }
}

$btw_cron_schedules = new BTW_Cron_Schedules();