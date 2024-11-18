<?php 

class BTW_Xml_Api_Post_Controller{

  protected $post_type = 'post';

  protected $post_type_plural = 'posts';

  protected $supports_archives = true;

  protected $feed_title = 'Latest';

  protected $xml_format = 'default';

  protected $add_xml_format_to_permastructure = false;

  public function __construct(){

    add_filter( 'root_rewrite_rules', [ $this, 'register_routes' ] );
    add_action( 'parse_query', [ $this, 'init_xml_api' ] );


  }


  public function register_routes( $rewrite_rules ){

    $base_permastructure = $this->add_xml_format_to_permastructure
      ? "xml/v1/{$this->xml_format}/{$this->post_type_plural}"
      : "xml/v1/{$this->post_type_plural}";

    if( $this->supports_archives ){
      $rewrite_rules["{$base_permastructure}/?$"] = 'index.php?xml_format=' . $this->xml_format . '&xml_api_post_type=' . $this->post_type;
    }

    $rewrite_rules["{$base_permastructure}/([^/]+?)/?$"] = 'index.php?xml_format=' . $this->xml_format . '&xml_api_post_type=' . $this->post_type . '&xml_api_post_id=$matches[1]';

    return $rewrite_rules;

  }


  protected function get_namespaces(){
    return '
      xmlns:content="http://purl.org/rss/1.0/modules/content/"
      xmlns:wfw="http://wellformedweb.org/CommentAPI/"
      xmlns:dc="http://purl.org/dc/elements/1.1/"
      xmlns:atom="http://www.w3.org/2005/Atom"
      xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
      xmlns:slash="http://purl.org/rss/1.0/modules/slash/"';
  }


  public function init_xml_api( $wp_query ){

    if( !empty( $wp_query->query['xml_format'] ) && $wp_query->query['xml_format'] == $this->xml_format 
        && !empty( $wp_query->query['xml_api_post_type'] ) && $wp_query->query['xml_api_post_type'] == $this->post_type
    ){
     
          $base_api_post_controller = new BTW_Base_Api_Post_Controller([
            // 'date_after' => '-10 days',
          ]);
          
          $requested_post_id = $wp_query->query['xml_api_post_id'] ?? '';

          $posts = $base_api_post_controller->get_post_items(
            $requested_post_id,
            $this->post_type,
          );

          /**
           * Convert $posts to array if is single
           */
          if( $requested_post_id ){
            $posts = [ $posts ];
          }

        $this->render( $posts );
        exit();
    }
  }

  protected function get_feed_header(){

    /**
     * RSS2 Feed Template for displaying RSS2 Posts feed.
     *
     * @package WordPress
     */

    header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);

    echo '<?xml version="1.0" encoding="' . get_option( 'blog_charset' ) . '"?' . '>';

    /**
     * Fires between the xml and rss tags in a feed.
     *
     * @since 4.0.0
     *
     * @param string $context Type of feed. Possible values include 'rss2', 'rss2-comments',
     *                        'rdf', 'atom', and 'atom-comments'.
     */
    do_action( 'rss_tag_pre', 'rss2' );
    ?>
    <rss version="2.0"
      <?php echo $this->get_namespaces();?>

      <?php
      /**
       * Fires at the end of the RSS root to add namespaces.
       *
       * @since 2.0.0
       */
      do_action( 'rss2_ns' );
      ?>
    >

    <channel>
      <title><?php wp_title_rss(); ?></title>
      <link><?php bloginfo_rss( 'url' ); ?></link>
      <atom:link href="<?php self_link(); ?>" rel="self" type="application/rss+xml" />
      <description><?php bloginfo_rss( 'description' ); ?></description>
      <lastBuildDate><?php echo get_feed_build_date( 'r' ); ?></lastBuildDate>
      <language><?php bloginfo_rss( 'language' ); ?></language>
      <sy:updatePeriod>
      <?php
        $duration = 'hourly';

        /**
         * Filters how often to update the RSS feed.
         *
         * @since 2.1.0
         *
         * @param string $duration The update period. Accepts 'hourly', 'daily', 'weekly', 'monthly',
         *                         'yearly'. Default 'hourly'.
         */
        echo apply_filters( 'rss_update_period', $duration );
      ?>
      </sy:updatePeriod>
      <sy:updateFrequency>
      <?php
        $frequency = '1';

        /**
         * Filters the RSS update frequency.
         *
         * @since 2.1.0
         *
         * @param string $frequency An integer passed as a string representing the frequency
         *                          of RSS updates within the update period. Default '1'.
         */
        echo apply_filters( 'rss_update_frequency', $frequency );
      ?>
      </sy:updateFrequency>
      <?php
      /**
       * Fires at the end of the RSS2 Feed Header.
       *
       * @since 2.0.0
       */
      do_action( 'rss2_head' );

  }

  protected function get_feed_footer(){ ?>

      </channel>
    </rss>

  <?php }


  /**
   * @param array, $post_data returned by get_base_api_post_data function
   * @param WP_Post, $wp_post_obj
   */
  protected function get_feed_post_item( $post_data, $wp_post_obj ){

        $post__feat_image_id = $post_data['post_image_id'];
        $filesize = filesize( get_attached_file( $post__feat_image_id ) );
        $mime_type = get_post_mime_type( $post__feat_image_id );

        $post_lead = get_field( 'btw__global_fields__lead', $wp_post_obj->ID );

        $post_categories = array_unique( array_merge( [$post_data['primary_category']], $post_data['post_categories'] ) );


        ?>
      <item>
        <title><?php echo $post_data['post_title']; ?></title>
        <link><?php echo esc_url(apply_filters('the_permalink_rss', $post_data['post_url'])); ?></link>
        <guid isPermaLink="false"><?php echo $wp_post_obj->ID; ?></guid>

        <dc:creator><![CDATA[<?php echo $post_data['post_byline'] ?: $post_data['post_author']; ?>]]></dc:creator>
        <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', $post_data['post_date'], false ); ?></pubDate>

        <?php foreach( $post_categories as $post_category ): ?>
          <category><![CDATA[ <?php echo html_entity_decode( $post_category, ENT_COMPAT, get_option( 'blog_charset' ) );?> ]]></category>
        <?php endforeach; ?>
        
        <?php if( $post_lead ): ?>
        <description><![CDATA[<?php echo $post_lead; ?>]]></description>
        <?php endif;?> 

        <?php if( !empty( $post_data['post_content'] ) ): ?>
           <content:encoded><![CDATA[<?php echo btw_xml_api_format_post_content( $post_data['post_content'] ); ?>]]></content:encoded>
        <?php endif; ?>

        <enclosure url="<?php echo $post_data['post_image'];?>" length="<?php echo $filesize;?>" type="<?php echo $mime_type;?>" />

      </item>

  <?php }



  protected function render( $posts ){

      $this->get_feed_header();

      foreach( $posts as $post_data ):

        $wp_post_obj = $post_data['post_obj'];
        $this->get_feed_post_item( $post_data, $wp_post_obj );

      endforeach;
      wp_reset_postdata();
      
      $this->get_feed_footer();

    }
}

 $btw_xml_api_post_controller = new BTW_Xml_Api_Post_Controller();

