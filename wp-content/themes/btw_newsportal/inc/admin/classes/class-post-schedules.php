<?php

/*
  Setup cron functionality for schedule next post on above the fold group.
  Update above the fold post with next post
  Cron time is set to 1min for dev

*/

  class BTW_POST_SCHEDULE_CRON{

    const ACF_SCHEDULED_FIELD_NAME_SUFFIX = '__scheduled';
    const ACF_SCHEDULED_STATUS_FIELD_NAME_SUFFIX = '_status';

    public function __construct(){

      add_filter( 'cron_schedules',[ $this,'add_cron_schedules'] );
      add_action( 'btw_post_schedule_cron',[ $this,'cron_handler' ] );

      // Register cron job
      if( is_admin() && !empty( $_GET['btw_post_schedule_cron'] ) ){
        if( !self::is_cron_job_registered() ){
          wp_schedule_event( time(), 'every_1_minute', 'btw_post_schedule_cron' );
        }
      }

    }


    private function is_cron_job_registered(){

      $regirested_cron_jobs = _get_cron_array();

      foreach( array_values( $regirested_cron_jobs ) as $index => $cron_job ){

      	if( in_array( 'btw_post_schedule_cron', array_keys( $cron_job ) ) ){
      		return true;
      	}
      }

      return false;
    }


    /*
      Add 1min cron schedule
      See cron_schedules filter
      for more details
    */

    public function add_cron_schedules( $schedules ){
      $schedules["every_1_minute"] = array(
        'interval' => 60,
        'display' => __( 'Every 1 minute' ) );

      return $schedules;
    }

    /*
      internal function to concat an array with sub fields and values of an afc field
    */

    private function array_columns( $array, $columns ){
      $return = [];

			foreach( $columns as $col ){
        $return[$col] = $array[ $col ];
			}
      return $return;

    }


    /*
      internal function to concat an array with sub fields and values of an afc field
    */

    protected function multi_array_columns( $multi_array, $columns, $compact = false ){
  		$return = [];

  		foreach( $multi_array as $array ){
  			foreach( $columns as $col ){

  				if( $compact ){
  					$return[] = $array[ $col ];
  				}else{
  					$r[ $col ] = $array[ $col ];
  				}

  			}

  			if( !$compact ){
  				$return[] = $r;
  			}

  		}

  		return $return;

  	}




    /*
      Get group post above the fold.
    */

    public function get_wp_group_posts(){

      $wp_group_posts = get_posts(array(
        'post_type' => 'group',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
          array(
            'key'     => 'btw__group_fields__group_type',
            'value'   => 'hp',
          )
        )
      ));

      return $wp_group_posts;

    }

    /*
      Check if is next post time reached
      We temp change the timezone to match the input date time of field
    */

    private function is_scheduled_time( $field ){

      if( empty( $field['atf__scheduled_time'] ) ) return false;

      $default_timezone = date_default_timezone_get();
      date_default_timezone_set( get_option( 'timezone_string' ) );

      $post_scheduled_time = new DateTime( $field['atf__scheduled_time'] );
      $now = new DateTime();

      date_default_timezone_set( $default_timezone );

      return $post_scheduled_time <= $now ? true : false;
    }

    /*
      Check if is next group status is enable
    */

    private function schedule_status_is_enabled( $scheduled_field_name, $wp_group_post ){
      return get_field( $scheduled_field_name . self::ACF_SCHEDULED_STATUS_FIELD_NAME_SUFFIX, $wp_group_post->ID ) ? true : false;
    }


    /*
      Check if is next post is published. If is future wait until is published
    */

    private function is_next_post_published( $parent_field ){

      if( $parent_field['atf__is_advertorial'] ) return true;

      $post = $parent_field['atf__post'];
      if( empty( $post['0'] ) ) return false;

      if( !$post['0'] instanceof WP_Post ){
        $post['0'] = get_post( $post['0'] );
      }

      return $post['0']->post_status == 'publish' ? true : false;

    }


    /*
      The main functionality of the class
      We check
        is_next_post_published,
        is_scheduled_time,
        schedule_status_is_enabled,

      and proccess update depending of the field type: group / repeater
      finally reset the scheduled group

    */
    public function cron_handler(){

      $wp_group_posts = self::get_wp_group_posts();

       $group_templates = get_group_templates()['hp'];

      foreach( $wp_group_posts as $wp_group_post ){

        $wp_group_post_group_template = get_field( 'btw__group_fields__hp__template', $wp_group_post->ID );
        $group_template = $group_templates[ $wp_group_post_group_template ];

        foreach( $group_template as $group ){

          if( $group['field_type'] == 'group' ){

              $scheduled_field_name = $group['field_name'] . self::ACF_SCHEDULED_FIELD_NAME_SUFFIX;
              $scheduled_post = get_field( $scheduled_field_name, $wp_group_post->ID )['0'] ?? null;

              if( !$scheduled_post || !self::is_next_post_published( $scheduled_post ) || !self::is_scheduled_time( $scheduled_post ) || !self::schedule_status_is_enabled( $scheduled_field_name, $wp_group_post ) ) continue;

              self::update__field_type_group( $group['field_name'], $scheduled_post, $wp_group_post );
              self::reset_scheduled_field_row( $scheduled_field_name, $scheduled_post );

          }else{

            while( have_rows( $group['field_name'], $wp_group_post->ID ) ):the_row();

              $scheduled_field_name = $group['field_name'] . '_' . ( get_row_index() - 1 ) . self::ACF_SCHEDULED_FIELD_NAME_SUFFIX;
              $scheduled_post = get_field( $scheduled_field_name, $wp_group_post )['0'] ?? null;


              if( !$scheduled_post || !self::is_next_post_published( $scheduled_post ) || !self::is_scheduled_time( $scheduled_post ) || !self::schedule_status_is_enabled( $scheduled_field_name, $wp_group_post ) ) continue;

              self::update__row( $group['field_name'] . '_' . ( get_row_index() - 1 ), $scheduled_post, $wp_group_post );
              self::reset_scheduled_field_row( $scheduled_field_name, $scheduled_post, $wp_group_post );

            endwhile;

          }

        }
      }

    }



    /*
      Process update of a field type group
    */

    private function update__field_type_group( $parent_field_name, $scheduled_field_values, $wp_group_post ){

      while( have_rows( $parent_field_name, $wp_group_post->ID ) ):the_row();

        foreach( $scheduled_field_values as $sub_field => $value ):

          if( !acf_get_field( $parent_field_name . self::ACF_SCHEDULED_FIELD_NAME_SUFFIX ) ) continue;

          //UPDATE ACTIVE POST
          update_field( $parent_field_name . '_' . $sub_field, $value, $wp_group_post->ID );

        endforeach;

      endwhile;

    }

    /*
      Process update of a row of a field type repeater
    */

    private function update__row( $field_row, $scheduled_field_values, $wp_group_post ){

      foreach( $scheduled_field_values as $sub_field => $value ){

        if( !acf_get_field( $field_row . self::ACF_SCHEDULED_FIELD_NAME_SUFFIX ) ) continue;

        //UPDATE ACTIVE POST
        update_field( $field_row . '_' . $sub_field, $value, $wp_group_post->ID );

      }

    }

    /*
      Reset the scheduled group
    */

    private function reset_scheduled_field_row( $scheduled_parent_field_name, $scheduled_field_values, $wp_group_post ){

      while( have_rows( $scheduled_parent_field_name, $wp_group_post->ID ) ):the_row();

        foreach( $scheduled_field_values as $sub_field => $value ):

          update_field( $scheduled_parent_field_name . '_' . ( get_row_index() - 1 ) . '_' . $sub_field, '', $wp_group_post->ID );

        endforeach;

        update_field( $scheduled_parent_field_name . self::ACF_SCHEDULED_STATUS_FIELD_NAME_SUFFIX, '', $wp_group_post->ID );

      endwhile;

    }





  }


  $btw_post_schedule_cron = new BTW_POST_SCHEDULE_CRON();

?>
