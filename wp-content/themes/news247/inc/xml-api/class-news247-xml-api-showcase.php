<?php 

class News247_Xml_Api_Google_Showcase_Controller extends BTW_Xml_Api_Post_Controller{

    protected $post_type = 'feed';

    protected $post_type_plural = 'feeds';

    protected $supports_archives = false;

    protected $feed_title = 'Google Showcase';

    protected $xml_format = 'google_showcase';

    protected $add_xml_format_to_permastructure = true;

    public function __construct(){

        add_filter( 'root_rewrite_rules', [ $this, 'register_routes' ] );
        add_action( 'parse_query', [ $this, 'init_xml_api' ] );


    }


    protected function get_namespaces(){
        return '
        xmlns:atom="http://www.w3.org/2005/Atom"
        xmlns:media="http://search.yahoo.com/mrss/"
        xmlns:g="http://schemas.google.com/pcn/2020"
        xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"';
    }


    public function init_xml_api( $wp_query ){

    if( !empty( $wp_query->query['xml_format'] ) && $wp_query->query['xml_format'] == $this->xml_format 
        && !empty( $wp_query->query['xml_api_post_type'] ) && $wp_query->query['xml_api_post_type'] == $this->post_type
    ){

        /** 
         * Post id of the feed is required
         */
        if (empty($wp_query->query['xml_api_post_id'])) {
            return;
        }

        $base_api_feeds_controller = new BTW_Base_Api_Feeds_Controller();

        $feed_id = $wp_query->query['xml_api_post_id'];

        $groups = $base_api_feeds_controller->get_feed_item($feed_id);

        $this->render($groups);
        exit();

    }
  }


    private function normalize_data( $post_data ){

        $wp_post_obj = $post_data['post_obj'];

        $post_data['guid_is_permalink'] = 'false';
        $post_data['guid'] = $wp_post_obj->ID;
        $post_data['post_lead'] = wp_strip_all_tags( get_field('btw__global_fields__lead', $wp_post_obj->ID ), true );
        $post_data['featured_image_url'] = $post_data['post_image_available_sizes']['large_landscape'];
        $post_data['featured_image_credits'] = get_field('btw__attachment_fields__credits', $post_data['post_image_id']);

        return $post_data;
    }


    private function truncate( $string, $max = 9999999 ){

        if( mb_strlen( $string ) > $max ){
            while( mb_strlen( $string ) > $max - 3 ){
                $string = implode( ' ', array_slice( explode( ' ', $string ), 0, -1 ) );
            }

            $string = $string . '...';
        }

        return $string;
    }



  /**
   * @param array, array of $posts
   */
  protected function get_feed_single_story_items( $posts, $group ){

    $main_post = array_shift( $posts );

  ?>

  <item>

      <g:panel type="SINGLE_STORY"><?php echo $group['title'];?></g:panel>
      <g:panel_title><?php echo $group['title'];?></g:panel_title>
      <g:overline><?php echo $this->truncate( $main_post['primary_category'], 30 );?></g:overline>

      <guid isPermaLink="<?php echo $main_post['guid_is_permalink'];?>"><?php echo $main_post['guid'];?></guid>

        <pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', $main_post['post_date'], false ); ?></pubDate>

        <title><?php echo $this->truncate( $main_post['post_title'], 86 ); ?></title>

        <link><?php echo esc_url(apply_filters('the_permalink_rss', $main_post['post_url'])); ?></link>

        <?php if( $main_post['post_lead'] ): ?>
        <description><![CDATA[<?php echo $this->truncate( $main_post['post_lead'], 84 ); ?>]]></description>
        <?php endif;?> 

        <media:content url="<?php echo $main_post['featured_image_url'];?>">

        <?php if($main_post['featured_image_credits']): ?>
            <media:credit><?php echo $this->truncate( $main_post['featured_image_credits'], 50 );?></media:credit>
        <?php endif; ?>

        </media:content>

      <g:article_group role="RELATED_CONTENT">
            
        <?php foreach( $posts as $post ): ?>

        <g:item>

          <guid><?php echo $post['guid'];?></guid>

          <title><?php echo $this->truncate($post['post_title'], 54);?></title>

          <g:overline><?php echo $this->truncate($post['primary_category'], 30);?></g:overline>

          <link><?php echo $post['post_url'];?></link>

            <?php if( $post['post_lead'] ): ?>
            <description><![CDATA[<?php echo $this->truncate( $post['post_lead'], 82 ); ?>]]></description>
            <?php endif;?> 

            <media:content url="<?php echo $post['featured_image_url'];?>">

                <?php if( $post['featured_image_credits'] ): ?>
                    <media:credit><?php echo $this->truncate( $post['featured_image_credits'], 50 );?></media:credit>
                <?php endif; ?>

            </media:content>
            
        </g:item>

        <?php endforeach; ?>

      </g:article_group>

    </item>

  <?php }


    protected function get_feed_rundown_story_items( $posts, $group_data ){ ?>

        <item>
            <title>Panel 1</title>
            <guid><?php self_link(); ?></guid>
            <pubDate><?php echo get_feed_build_date('r'); ?></pubDate>

            <g:panel type="RUNDOWN"><?php echo $group_data['title'];?></g:panel>

            <g:panel_title><?php echo $group_data['title'];?></g:panel_title>
            <g:article_group role="RUNDOWN">

                <?php foreach( $posts as $post ): ?>

                <g:item>

                    <guid><?php echo $post['guid'];?></guid>

                    <title><?php echo $this->truncate( $post['post_title'], 54 );?></title>

                    <g:overline><?php echo $this->truncate( $post['primary_category'], 30 );?></g:overline>

                    <link><?php echo $post['post_url'];?></link>

                    <media:content url="<?php echo $post['featured_image_url'];?>">

                        <?php if($post['featured_image_credits']): ?>
                        <media:credit><?php echo $this->truncate( $post['featured_image_credits'], 50 );?></media:credit>
                        <?php endif; ?>

                    </media:content>

                </g:item>

                <?php endforeach; ?>

            </g:article_group>

        </item>

    <?php }


    protected function render( $groups ){

        $this->get_feed_header();

        $gpanel_type = $_GET['gpanel_type'] ?? 'rundown';

        // rundown can only have one panel per feed
         if( $gpanel_type == 'rundown' ){
            $groups = array_slice( $groups, 0, 1 );
         }

        foreach( $groups as $group ){

            $group_term = get_field( 'btw__group_fields__hp__general__term_selection', $group['group_id'] )[0]['term'] ?? '';
            $group_default_title = get_field( 'btw__group_fields__hp__general__section_title', $group['group_id'] );
            $group_title = $group_default_title ?: ( $group_term ? $group_term->name : '' );

            $group_data = [
                'title' => $group_title,
            ];

            $posts = array_filter( $group['group'], function( $post ){
                return empty( $post['advertorial_title'] );
            });

            $posts = array_slice( $posts, 0, 3 );

            $posts = array_map( [ $this, 'normalize_data' ], $posts );

            if( $gpanel_type == 'single_story' ){
                $this->get_feed_single_story_items( $posts, $group_data );

            }else{
                $this->get_feed_rundown_story_items( $posts, $group_data );

            }

        }

        $this->get_feed_footer();

    }

}

 $news47_xml_api_google_showcase_controller = new News247_Xml_Api_Google_Showcase_Controller();

