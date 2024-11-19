<?php 

class Contra_Xml_Api_Feeds_Controller extends BTW_Xml_Api_Post_Controller{

  protected $post_type = 'feed';

  protected $post_type_plural = 'feeds';

  protected $supports_archives = false;

  protected $feed_title = 'Latest News';

  public function __construct(){

    parent::__construct();

  }

  public function init_xml_api( $wp_query ){

    if( !empty( $wp_query->query['xml_format'] ) && $wp_query->query['xml_format'] == $this->xml_format 
        && !empty( $wp_query->query['xml_api_post_type'] ) && $wp_query->query['xml_api_post_type'] == $this->post_type
    ){
    
        /** 
         * Post id of the feed is required
         */
        if( empty( $wp_query->query['xml_api_post_id'] ) ){
            return;
        }

        $base_api_feeds_controller = new BTW_Base_Api_Feeds_Controller();
                
        $feed_id = $wp_query->query['xml_api_post_id'];

        $groups = $base_api_feeds_controller->get_feed_item( $feed_id );

        $this->render( $groups );
        exit();
    }
  }

  /**
   * @param array, $post_data
   */
  protected function get_feed_adverorial_item( $post_data ){
    
    $post_date = new DateTime( 'now', wp_timezone() );

  ?>

    <item>
        <title><?php echo $post_data['advertorial_title']; ?></title>
        <link><?php echo esc_url(apply_filters('the_permalink_rss', $post_data['advertorial_url'])); ?></link>
        <guid isPermaLink="true"><?php echo esc_url(apply_filters('the_permalink_rss', $post_data['advertorial_url'])); ?></guid>
        <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', $post_date->format( 'Y-m-d H:i:s' ), false ); ?></pubDate>
        <description><![CDATA[<?php echo $post_data['advertorial_caption']; ?>]]></description>
        <content:encoded>
          <![CDATA[ 
            <img src="<?php echo $post_data['advertorial_image'];?>" />
          ]]>
        </content:encoded>
      </item>

  <?php }


  protected function render( $groups ){
    
      // get feed version: full / light
      $version = $_GET['version'] ?? 'full';

      $this->get_feed_header();
      
      $groups_posts = wp_list_pluck( $groups, 'group' );
      $posts = array_merge(...$groups_posts);

      foreach( $posts as $post_data ):

        if( !empty( $post_data['advertorial_title'] ) ){
          $this->get_feed_adverorial_item( $post_data );
          continue;
        }

        $wp_post_obj = $post_data['post_obj'];

        if( $version == 'full' ){
          $post_data['post_content'] = apply_filters('post_content', $wp_post_obj->post_content );
        }

        $this->get_feed_post_item( $post_data, $wp_post_obj );
        
      endforeach;
      wp_reset_postdata();
      
      $this->get_feed_footer();

    }




}

 $news_xml_api_feeds_controller = new Contra_Xml_Api_Feeds_Controller();

