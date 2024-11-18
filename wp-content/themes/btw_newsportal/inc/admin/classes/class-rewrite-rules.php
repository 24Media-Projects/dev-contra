<?php

/*
  Rewrite Rules Class
  Change author permalink: New permalink is based on author nickname. Replace author_nicename with nickname
*/

class BTW_REWRITE_RULES{

  public function __construct(){

    add_action( 'init', [ $this, 'update_category_permastructure' ] );
    add_filter( 'category_rewrite_rules', [ $this, 'category_rewrite_rules' ], 20 );
    add_action( 'created_category', [ $this, 'flush_rules' ] );
    add_action( 'delete_category',  [ $this, 'flush_rules' ] );
    add_action( 'edited_category',  [ $this, 'flush_rules' ] );

    add_filter( 'author_link', [ $this, 'author_permalink' ] , 10, 3 );
    add_filter( 'request', [ $this, 'update_author_query_var' ] );

  }

  /*
    Change category permastucturure. Remove category prefix from url
    See category_rewrite_rules hook for more details
  */

  public function category_rewrite_rules( $category_rewrite ){

    // print_r($category_rewrite);
    // return $category_rewrite;

    $category_rewrite = array();
    
    if( !apply_filters( 'btw/rewrite_rules/categories/status', true ) ){
      return $category_rewrite;
    }

    $categories = get_categories( array( 'hide_empty' => false ) );

    foreach ( $categories as $category ):
      $category_nicename = $category->slug;
      if (  $category->parent == $category->cat_ID ) {
        $category->parent = 0;
      } elseif ( 0 != $category->parent ) {
        $category_nicename = get_category_parents(  $category->parent, false, '/', true  ) . $category_nicename;
      }
      
      $category_rewrite['('.$category_nicename.')/feed/?$'] = 'index.php?category_name=$matches[1]&feed=rss2';
      $category_rewrite['('.$category_nicename.')/?$'] = 'index.php?category_name=$matches[1]';
      $category_rewrite['('.$category_nicename.')/page/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&paged=$matches[2]';

    endforeach;

  	return $category_rewrite;
  }

  /*
    Change category permastucturure. Remove category prefix from url
  */

  public function update_category_permastructure(){
    global $wp_rewrite;
    $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
  }

  /*
    Update permalinks on category: new, edit, delete
    See the wp global $wp_rewrite for more details
  */
  public function flush_rules() {
  	global $wp_rewrite;
  	$wp_rewrite->flush_rules();
  }

  /*
    Change author permalink on front end. Replace user_nicename with nickname
    See author_link hook for more details
  */
  public function author_permalink( $link, $author_id, $author_nicename ){
    $author_nickname = get_user_meta( $author_id, 'nickname', true );

    return $author_nickname ? str_replace( $author_nicename, $author_nickname, $link ) : $link;

  }


  /*
    WP default author permastucture is with user_nicename
    The query_var key for author, author_name,  has user nickname with the above change, $query_vars['author_name'] = nickname
    Here we try to get user ( author ) with the nickname.
    Then we set the query_var key for author, to user_nicename, so wp proccess the request as the author archive page
    If we cant get user, or user is disabled ( user_status meta ), redirect to home
    See request hook for more details
  */
  public function update_author_query_var( $query_vars ){
    global $wpdb;

    if( empty( $query_vars['author_name'] ) ) return $query_vars;

    $author_nickname = $query_vars['author_name'];
    $author_data = $wpdb->get_results("SELECT u.user_nicename as user_nicename, u.ID as ID
                              FROM $wpdb->users as u
                              INNER JOIN $wpdb->usermeta as um ON um.user_id = u.ID AND um.meta_key = 'nickname'
                              WHERE um.meta_value = '{$author_nickname}'");


    if( empty( $author_data ) ) return $query_vars;

    $author_data = $author_data['0'];
    $user_has_archive = $wpdb->get_var("SELECT user_id FROM $wpdb->usermeta WHERE user_id = $author_data->ID AND meta_key = 'user_has_archive' AND meta_value = '1'");

    if( !$user_has_archive ){
      wp_safe_redirect( site_url() );
      exit();
    }

    $query_vars['author_name'] = $author_data->user_nicename;
    $query_vars['author'] = $author_data->ID;

    return $query_vars;
  }


}

$btw_rewrite_rules = new BTW_REWRITE_RULES();
