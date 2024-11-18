<?php


class BTW_Google_News_Feed{

  const FEED_NAME = 'google_news_feed';

  private $post_type = 'post';

  public function __construct(){
    add_action( 'init',[ $this, 'add_google_news_feed' ] );
    add_filter( 'feed_content_type', [ $this, 'set_google_news_feed_content_type' ], 10, 2 );
  }



  public function add_google_news_feed(){
    add_feed( self::FEED_NAME, [ $this, 'render_google_news_feed' ] );
  }

  public function set_google_news_feed_content_type( $content_type, $type ){
    if( $type == self::FEED_NAME  ){
      return 'text/xml';
    }
  }

  public function render_google_news_feed(){

    ob_start();
    self::get_xml_feed();
    $return_xml = ob_get_contents();
    ob_end_clean();

    echo $return_xml;
  }


  private function get_xml_feed(){
    header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

    global $btw_global_settings;

    $query = new WP_Query(array(
        'posts_per_page'   => -1,
        'post_status' => 'publish',
        'post_type'   => $this->post_type,
        'meta_query' => array(
          array(
            'relation' => 'AND',
            array(
              'key' => 'btw__article_fields__exclude_channel__feeds',
              'value' => '0',
            ),
          ),
        )
      ));

    ?><?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
    <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
          xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">

    <?php while( $query->have_posts() ): $query->the_post();
          global $post;

    ?>

      <url>
        <loc><?php the_permalink();?></loc>
        <news:news>
        <news:publication>
          <news:name><?php echo strtoupper( $btw_global_settings->get_clean_site_name() );?></news:name>
          <news:language>el</news:language>
        </news:publication>
        <news:publication_date><?php echo get_post_time( 'c', false, $post, true ); ?></news:publication_date>
          <news:title><?php the_title();?></news:title>
        </news:news>
      </url>

    <?php endwhile;?>
    <?php wp_reset_postdata();?>

  </urlset>

<?php

  }




}


$btw_google_news_feed = new BTW_Google_News_Feed();
