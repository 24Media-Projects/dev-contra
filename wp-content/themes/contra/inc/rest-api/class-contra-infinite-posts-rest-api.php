<?php

class Contra_Infinite_Posts_Rest_Controller extends WP_REST_Controller {

  const REST_AUTH_ERROR_NAME = 'btw_feed_rest_authentication_error';

  public function __construct(){
    global $btw_global_settings;

    $this->namespace = 'wp/v2';
    $this->rest_base = $btw_global_settings::rest_api_prefix_base() . '-infinite-posts';

  }


  /*
    Register routes for <prefix>-posts
    wp-json/wp/v2/<prefix>-posts/ with args:
                                      api_key: customer api key
                                      categories: comma seperated categories ids
                                      tags: comma seperated post_tags
                                      terms: combination of categories and post_tags in comma seperated format
                                      per_page: default 10, can be up to 50

    wp-json/wp/v2/<prefix>-posts/<post id> with args:
                                                api_key: customer api key
  */

  public function register_routes() {
    register_rest_route( $this->namespace, '/'. $this->rest_base. '/', array(
      array(
        'methods'              => 'GET',
        'callback'             => array( $this, 'get_post_items' ),
        'permission_callback'  => array( $this, 'check_user_permissions' ),
        'args'                 => $this->get_collection_params( [ 'categories',' tags', 'terms', 'author_id', 'per_page', 'page', 'post_types', 'inclusive_terms', 'exclude_posts' ] ),
      )
    ));

  }


  public function get_collection_params( $params = [] ){

    $query_params = [];

    if( !empty( $params['categories'] ) ){

      $query_params['categories'] = array(
        'description' => 'Comma separated category ids',
        'type'        => 'string',
      );

    }

    if( !empty( $params['tags'] ) ){

      $query_params['tags'] = array(
        'description' => 'Comma separated tag ids',
        'type'        => 'string',
      );

    }

    if( !empty( $params['terms'] ) ){

      $query_params['terms'] = array(
        'description' => 'Comma separated term ids',
        'type'        => 'string',
      );

    }

    if (!empty($params['author_id'])) {

      $query_params['author_id'] = array(
        'description' => '',
        'type'        => 'string',
      );

    }

    if( !empty( $params['per_page'] ) ){

      $query_params['per_page'] = array(
        'description' => 'Total Posts Per Page',
        'type'        => 'integer',
      );

    }

    if( !empty( $params['page'] ) ){

      $query_params['page'] = array(
        'description' => 'Pagination Page',
        'type'        => 'integer',
      );

    }

    if( !empty( $params['post_types'] ) ){

      $query_params['post_types'] = array(
        'description' => 'Post Types in comma separated format',
        'type'        => 'string',
      );

    }

    if( !empty( $params['inclusive_terms'] ) ){

      $query_params['inclusive_terms'] = array(
        'description' => 'Get sub terms of the requested term',
        'type'        => 'string',
      );

    }

    if( !empty( $params['exclude_posts'] ) ){

      $query_params['exclude_posts'] = array(
        'description' => 'Exclude Post ids from results, in comma separated format',
        'type'        => 'string',
      );

    }



    return $query_params;

  }


  /*
    Returns posts as json object
    if is single post, get the post id with $request['id']
    See get_item_data function for more details
  */
  public function get_post_items($request){

    $return = [];
    global $wpdb;
        
    /**
     * Get and format post types
     */
    $post_types = $_GET['post_types'] ?? 'post,video';
    $post_types = array_map( function( $post_type ){
      return "'{$post_type}'";
    },explode(',', $post_types));

    $post_types = implode(',', $post_types);

    $search_query = $_GET['search_query'] ?? '';

    $post_categories = $_GET['categories'] ?? '';
    $post_tags = $_GET['tags'] ?? '';
    $post_terms = $_GET['terms'] ?? '';
    $post_author = $_GET['author_id'] ?? '';
    $exclude_posts = $_GET['exclude_posts'] ?? '';

    $per_page = !empty( $_GET['per_page'] )
      ? ( $_GET['per_page'] > 50 ? 50 : ( $_GET['per_page'] <= 0 ? 10 : $_GET['per_page'] ) )
      : 10;

    $page = $_GET['page'] ?? 1;
    $offset = $page > 1 ? ( ( $page - 1 ) * $per_page ) : 0;


    /**
     * If is search, use a WP_Query to get data from elastic search with elastic press
     */
    if( !empty( $search_query ) ){
      return $this->get_post_items_elastic_search( $search_query, $page, $per_page );
    }


    $post_categories_sql_join = '';
    $post_categories_sql_where = '';
    $post_tags_sql_join = '';
    $post_tags_sql_where = '';
    $post_terms_sql_join = '';
    $post_terms_sql_where = '';
    $post_author_sql_where = '';
    $exclude_posts_sql_where = '';

    if( $post_categories ){

      /**
       * Get term_taxonomy_id for each category
       */
      $post_categories = array_map( function( $post_category ){

        $term = get_term_by( 'term_id', $post_category, 'category' );
        return "'{$term->term_taxonomy_id}'";

      }, explode(',', $post_categories ) );

      $post_categories = implode( ',', $post_categories );

      $post_categories_sql_join = " LEFT JOIN $wpdb->term_relationships as t1 ON p.ID = t1.object_id ";
      $post_categories_sql_where = " AND t1.term_taxonomy_id IN ( {$post_categories} ) ";

    }

    if( $post_tags ){

      /**
       * Get term_taxonomy_id for each post_tag
       */
      $post_tags = array_map( function( $post_tag ){

        $term = get_term_by( 'term_id', $post_tag, 'post_tag' );
        return "'{$term->term_taxonomy_id}'";

      }, explode(',', $post_tags ) );

      $post_tags = implode( ',', $post_tags );

      $post_tags_sql_join = " LEFT JOIN $wpdb->term_relationships as t2 ON p.ID = t2.object_id ";
      $post_tags_sql_where = " AND t2.term_taxonomy_id IN ( {$post_tags} ) ";

    }

    if( $post_categories || $post_tags ){
      $post_terms_sql_join = $post_categories_sql_join . $post_tags_sql_join;
      $post_terms_sql_where = $post_categories_sql_where . $post_tags_sql_where;
    }


    if( $post_author ){
      $post_author_sql_where = " AND p.post_author = '{$post_author}'";
    }

    if( $exclude_posts ){
      $exclude_posts_sql_where = " AND p.ID NOT IN ( {$exclude_posts} ) ";
    }

    $posts = $wpdb->get_col(
      "SELECT p.ID as ID FROM $wpdb->posts as p
      {$post_terms_sql_join}
      WHERE p.post_type IN( {$post_types} ) AND p.post_status = 'publish'
      {$exclude_posts_sql_where}
      {$post_terms_sql_where}
      {$post_author_sql_where}
      GROUP BY p.ID ORDER BY p.post_date DESC LIMIT {$offset}, {$per_page}"
    );

    foreach ($posts as $post) {
      $return[] = $this->get_item_data(get_post($post));
    }

    $response = rest_ensure_response( $return );

    $total_posts = $wpdb->get_var(
        "SELECT count(p.ID) FROM $wpdb->posts as p
        {$post_terms_sql_join}
        WHERE p.post_type IN( {$post_types} ) AND p.post_status = 'publish'
        {$exclude_posts_sql_where}
        {$post_terms_sql_where}
        {$post_author_sql_where}"
    );


    $total_pages = ceil( $total_posts / $per_page );

    $response->header( 'X-WP-Total', $total_posts );
    $response->header( 'X-WP-TotalPages', $total_pages );

    return $response;

  }

  
  public function get_post_items_elastic_search( $search_query, $page, $per_page ){

    $return = [];

    $search_query = $_GET['search_query'];

    global $post;

    $orderby = $_GET['orderby'] ?? 'date';

    $posts_query = new WP_Query([
      'post_type'       => [ 'post', 'video' ],
      'post_status'     => 'publish',
      'orderby'         => $orderby,
      'order'           => 'desc',
      'ep_integrate'    => true,
      's'               => $search_query,
      'posts_per_page'  => $per_page,
      'paged'           => $page,
    ]);

    while( $posts_query->have_posts() ): $posts_query->the_post();

      $return[] = $this->get_item_data( $post );

    endwhile;

    $total_posts = $posts_query->post_count;
    $total_pages = ceil( $total_posts / $per_page);

    $response = rest_ensure_response( $return );

    $response->header( 'X-WP-Total', $posts_query->post_count );
    $response->header( 'X-WP-TotalPages', $total_pages );
    $response->header( 'X-Elasticpress-Query', true );

    return $response;

  }

  public function check_user_permissions( $request ){

    if( $request->get_method() != 'GET' ){
      return new WP_Error( self::REST_AUTH_ERROR_NAME, 'No routes registered', array( 'status' => 404 ) ) ;
    }

    // Temp
      return true;
  }


  public function get_item_data( $post ){

    setup_postdata( $post );

    $return = get_base_api_post_data( $post );

    $image_srcsets = array(
        array(
            'image_size'   => 'small_square',
            'media_query'  => '(max-width: 767px )',
        ),
        array(
            'image_size'  => 'medium_horizontal',
            'default'     => true,
        ),
    );

    $post_attachment_html = btw_get_post_attachment(
        post: $post,
        image_srcsets: $image_srcsets,
    );

    $return['attachment_html'] = $post_attachment_html;

    $return['post_primary_category'] = btw_get_primary_term_anchor_html( btw_get_post_primary_category( $post ) );
    $return['post_primary_tag'] = btw_get_primary_term_anchor_html( btw_get_post_primary_tag( $post ) );

	$return['post_author_html'] = btw_return_post_author_html($post);


    $return['post_date'] = get_the_date( 'd.m.Y, H:i', $post );
    $return['post_date_without_time'] = get_the_date('d.m.Y', $post);

    $return['post_title_uppercase'] = remove_punctuation( $return['post_title'] );

    $return['post_type'] = $post->post_type;
    $return['is_podcast'] = !empty(get_field('btw__article_fields__audio_player_code', $post) );

    wp_reset_postdata();

    return $return;

  }
}

add_action( 'rest_api_init', function () {
    $btw_posts = new Contra_Infinite_Posts_Rest_Controller();
    $btw_posts->register_routes();
});



 
