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


class BTW_Base_Api_Feeds_Controller{


  public function __construct( $args = [] ){

  }



  public function get_feed_item( $post_id ){

    $return = [];

    global $post;

    $feed_groups = get_field( 'btw__feed_fields__group', $post_id );

    foreach( $feed_groups as $post ){

      setup_postdata( $post );

      $group_type = get_field( 'btw__group_fields__group_type', $post->ID );

      $group_template_name = get_field( "btw__group_fields__{$group_type}__template", $post->ID );

      if( $group_template_name ){

        $return[] = array(
          'group_name' => get_the_title( $post->ID ),
          'group_id'   => $post->ID,
          'group'      => $this->get_group_data( $group_template_name, $group_type ),
        );
      }

    }

    wp_reset_postdata( $post );

    return $return;

  }


  protected function get_group_data( $template, $group_type ){

    // filter to overide return
    // used when we have a wp_query instead of acf field
    $post_items = apply_filters( 'btw/base_api/feeds/post_items', [], $template );

    if( !empty( $post_items ) ){
      return $post_items;
    }

    $acf_fields = btw_get_group_template_post_acf_fields( $group_type );
    $group_acf_fields = $acf_fields[ $template ];

    foreach( $group_acf_fields as $acf_field => $type ){

      if( $type == 'atf_post' ){

        $post_items = array_merge( $post_items, $this->get_atf_post_data( $acf_field, $group_type ) );

      }elseif( $type === 'bon' ){

        $post_items = array_merge( $post_items, $this->get_bon_post_data($acf_field, $group_type ) );

      }else{
        $post_items = array_merge( $post_items, $this->get_default_post_data( $acf_field, $group_type ) );

      }
    }

    return $post_items;

  }


  /**
   * Get atf post data
   * 
   * @param array, $acf_field
   * 
   * @return array
   */
  protected function get_atf_post_data( $acf_field, $group_type ){

    $items = [];

    foreach( get_field( $acf_field ) ?: [] as $row ){

        if( $group_type == 'magazine' ){

            $atf_post = new BTW_Atf_Post_Magazine([
                'item' => $row,
            ]);

        }else{

            $atf_post = new BTW_Atf_Post([
                'item' => $row,
            ]);

        }

      $items[] = $atf_post->get_base_api_data();
    }

    return $items;

  }

/**
 * Get post data from acf post selection
 * 
 * @param array, $acf_field
 * 
 * @return array
 */
  protected function get_default_post_data( $acf_field, $group_type ){

    $items = [];

    foreach( get_field( $acf_field ) ?: [] as $post ){
      setup_postdata( $post );

      $items[] = get_base_api_post_data( $post );

    }

    wp_reset_postdata();

    return $items;

  }

  /**
   * Get post data from acf post selection
   * 
   * @param array, $acf_field
   * @param string, $group_type
   * 
   * @return array
   */
  protected function get_bon_post_data( $acf_field, $group_type ){

    $items = [];

    foreach( get_field( $acf_field ) ?: [] as $row ){

      $items[] = get_base_api_bon_post_data( $row );

    }

    return $items;
  }


}



 
