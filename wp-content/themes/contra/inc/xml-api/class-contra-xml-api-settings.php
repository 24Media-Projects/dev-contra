<?php


class Contra_Xml_Api_Settings{


  public function __construct(){

    add_filter( 'pre_get_posts', [ $this, 'pre_get_posts' ], 20 );

  }

  /**
   * Limit posts of RSS feeds to be after - 2 days.
   * 
   * @param WP_Query, $query
   * 
   * @return WP_Query
  */
  public function pre_get_posts( $query ){

  	if( !is_feed() ){
        return $query;
    }


		$feed_date_query = array(
			array(
				'after' => date( 'Y-m-d', strtotime( '-2 days' ) ),
				'inclusive' => true,
			)
		);



    $query->set( 'post_status', 'publish' );
    // $query->set('date_query', $feed_date_query);
    $query->set( 'posts_per_rss', 100 );

  	return $query;
  }



}

$contra_xml_api_settings = new Contra_Xml_Api_Settings();



