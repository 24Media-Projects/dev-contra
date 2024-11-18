<?php
/*
  Rest Api Posts Controller class
  prefix base endpoint: see global settings class: rest_api_prefix_base
  Register endpoint: wp-json/wp/v2/<prefix>-posts
  No Public access. Only customers with matching api key can access this rest api routes.
  Extends WP_REST_Controller class
  Returns json object with posts based on:
                                          categories
                                          post_tags
                                          terms
  See:
      https://developer.wordpress.org/reference/classes/wp_rest_controller/
      inc/rest-api/class-btw-rest-api-keys.php
      for more details
*/


class BTW_Base_Api_Post_Controller{

    protected $post_categories;

    protected $post_tags;

    protected $post_terms;

    protected $per_page;

    protected $date_after;

    protected $version;


  public function __construct( $args = [] ){

        $this->post_categories = $args['categories'] ?? $_GET['categories'] ?? '';
        $this->post_tags = $args['tags'] ?? $_GET['tags'] ?? '';
        $this->post_terms = $args['terms'] ?? $_GET['terms'] ?? '';
        
        $per_page = $args['per_page'] ?? $_GET['per_page'] ?? 10;
        
        $this->per_page = $per_page > 120 
            ? 120
            : ( $per_page <= 0 ? 10 : $per_page );

        $this->version = $args['version'] ?? ( $_GET['version'] ?? 'full' );

        $this->date_after = $args['date_after'] ??  '';

  }


  /*
    Returns posts as json object
    if is single post, get the post id with $post_id
    See get_item_data function for more details
  */
  public function get_post_items( $post_id, $post_type ){

    try{

        if( !empty( $post_id ) ){

            $post = get_post( $post_id );

            $post_data = $this->get_item_data( $post );

            return get_base_api_maybe_set_exluded_data( $post, $post_data );
        }

        $return = [];
        global $wpdb;


        $post_categories_sql_join = '';
        $post_categories_sql_where = '';
        $post_tags_sql_join = '';
        $post_tags_sql_where = '';
        $post_terms_sql_join = '';
        $post_terms_sql_where = '';
        $post_date_after_sql_where = '';

        if( $this->post_categories ){

            /**
             * Get term_taxonomy_id for each category
             */
            $post_categories = array_map(function( $post_category ){

              $term = get_term_by('term_id', $post_category, 'category');
              return "'{$term->term_taxonomy_id}'";
            }, explode(',', $this->post_categories ) );

            $post_categories = implode(',', $post_categories);
          
            $post_categories_sql_join = " LEFT JOIN $wpdb->term_relationships as t1 ON p.ID = t1.object_id ";
            $post_categories_sql_where = " AND t1.term_taxonomy_id IN ( {$post_categories} ) ";
        }

        if( $this->post_tags ){

            /**
             * Get term_taxonomy_id for each post_tag
             */
            $post_tags = array_map( function( $post_tag ){

              $term = get_term_by( 'term_id', $post_tag, 'post_tag' );
              return "'{$term->term_taxonomy_id}'";

            }, explode(',', $this->post_tags ) );

            $post_tags = implode( ',', $post_tags );

            $post_tags_sql_join = " LEFT JOIN $wpdb->term_relationships as t2 ON p.ID = t2.object_id ";
            $post_tags_sql_where = " AND t2.term_taxonomy_id IN ( {$post_tags} ) ";
        }

        if( $this->post_categories || $this->post_tags ){
            $post_terms_sql_join = $post_categories_sql_join . $post_tags_sql_join;
            $post_terms_sql_where = $post_categories_sql_where . $post_tags_sql_where;
        }

        if( $this->date_after ){
            $after_datetime = new DateTime( $this->date_after );
            $after_date = $after_datetime->format( 'Y-m-d H:i:s' );
            $post_date_after_sql_where = " AND p.post_date > '{$after_date}' ";
        }


        $posts = $wpdb->get_col(
            "SELECT p.ID as ID FROM $wpdb->posts as p
            {$post_terms_sql_join}
            WHERE p.post_type = '{$post_type}' AND p.post_status = 'publish'
            AND p.ID NOT IN(
                SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'btw__global_fields__hide_from_feed' AND meta_value = 1
            )
            {$post_terms_sql_where}
            {$post_date_after_sql_where}
            GROUP BY p.ID ORDER BY p.post_date DESC LIMIT {$this->per_page}"
        );

        foreach( $posts as $post ){
            $return[] = $this->get_item_data( get_post( $post ) );
        }
    
    }catch( Exception $e ){
        return [];
    }

    return $return;

  }


  /*
    Return post data
    If version is light, some fields are omitted
  */
  public function get_item_data( $post ){

    setup_postdata( $post );

    $return = get_base_api_post_data( $post );

    if( $this->version == 'full' ){
      $return['post_content'] = apply_filters( 'the_content', $post->post_content );

      $embed_scripts = get_embed_scripts( $post );
      if( $embed_scripts ){
        $return['embed_scripts'] = $embed_scripts;
      }
    }

    wp_reset_postdata();

    return $return;

  }

}



 
