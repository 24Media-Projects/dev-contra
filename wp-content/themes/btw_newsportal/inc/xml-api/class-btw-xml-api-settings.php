<?php


class BTW_Xml_Api_Settings{


  public function __construct(){

    add_filter( 'pre_get_posts', [ $this, 'pre_get_posts' ] );
    add_filter( 'the_content_feed', [ $this, 'the_content_feed' ], 10, 2 );
    add_filter( 'the_excerpt_rss', [ $this, 'the_excerpt_rss' ], 99 );
    add_filter( 'the_author', [ $this, 'the_author' ] );
    add_filter( 'the_guid', [ $this, 'the_guid' ], 10, 2 );
    add_filter( 'get_post_time', [ $this, 'get_post_time' ], 10, 3 );

    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'feed_links', 2 );

    add_action( 'rss2_item', [ $this, 'add_post_image_on_rss2_item' ] );


  }

    public function pre_get_posts( $query ){

        if( !is_feed() ){
            return $query;
        }

        $meta_query = $query->get('meta_query') ?: [];
        $meta_query[] = array(
            array(
                'key' => 'btw__global_fields__hide_from_feed',
                'value' => '0',
            ),
        ); 

        $query->set( 'post_status', 'publish' );
        $query->set( 'meta_query', $meta_query );
        $query->set( 'posts_per_rss', 100 );

        return $query;
  }


  /*
  	Remove post_content from feed
  	See the_content_feed hook
  	for more details
  */

  public function the_content_feed( $content, $feed_type ){

    return '';

  }


  /*
  	post_excerpt on feed: return the post lead
  	See the_excerpt_rss hook
  	btw__global_fields__lead acf field
  	for more details
  */

  public function the_excerpt_rss( $output ){

  	global $post;

  	return get_field( 'btw__global_fields__lead' ,$post->ID );

  }


  public function the_author( $display_name ){

  	if( !is_feed() ){
        return $display_name;
    }

  	global $post, $btw_global_settings;

  	$post_author = btw_get_post_author();

  	return $post_author ? $post_author->display_name : $btw_global_settings->get_default_author()->display_name;

  }


  /*
    Rreturn post permalink as guid
  */
  public function the_guid( $guid, $post_id ){

   if( !is_feed() ){
    return $guid;
   }

   return get_the_permalink( $post_id );

 }


 /*
 	Fix timezon on Rss feeds - Post date
 	See get_post_time filter
 	for more details
 */

 public function get_post_time( $time, $d, $gmt ){

 	if( !is_feed() ){
        return $time;
    }

 	global $post;

 	$datetime = get_post_datetime( $post, 'date', 'local' );
 	return $datetime->format( $d );

 }


 /**
  * Add post featured image on rss2 item
 */
  public function add_post_image_on_rss2_item(){

    global $post;

    $post__feat_image = btw_get_post_featured_image();
    $filesize = filesize( get_attached_file( $post__feat_image->id ) );
    $mime_type = get_post_mime_type( $post__feat_image->id );

    echo "<enclosure url=\"{$post__feat_image->url}\" length=\"{$filesize}\" type=\"{$mime_type}\" />";

  }



}

$btw_xml_api_settings = new BTW_Xml_Api_Settings();