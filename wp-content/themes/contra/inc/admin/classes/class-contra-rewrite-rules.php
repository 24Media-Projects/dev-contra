<?php

/*
  Rewrite Rules Class
  Change author permalink: New permalink is based on author nickname. Replace author_nicename with nickname
*/

class Contra_Rewrite_Rules{

  public function __construct(){

    add_filter( 'page_rewrite_rules', [ $this, 'page_rewrite_rules' ], 20 );
    add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );
  }

  /**
   * Add permastructute for newspapers 
  */
  public function page_rewrite_rules( $page_rewrite ){

    $newspapers_page = btw_get_page_by_template( 'templates/protoselida.php' );

    // global $wp_rewrite;
    

    if( !$newspapers_page ){
        return $page_rewrite;
    }

    // today archive
    $page_rewrite['(' . $newspapers_page->post_name . ')/([^/]+)?/?$'] = 'index.php?pagename=$matches[1]&newspaper_category=$matches[2]';

    // date archive
    $page_rewrite['(' . $newspapers_page->post_name . ')/date/([0-9]{8})/?$'] = 'index.php?pagename=$matches[1]&newspaper_date=$matches[2]';

    // group archive
    $page_rewrite['(' . $newspapers_page->post_name . ')/([^/]+)?/date/([0-9]{8})/?$'] = 'index.php?pagename=$matches[1]&newspaper_category=$matches[2]&newspaper_date=$matches[3]';

    //single newspaper
    $page_rewrite['(' . $newspapers_page->post_name . ')/([^/]+)?/([^/]+)?/date/([^/]+)?/?$'] = 'index.php?pagename=$matches[1]&newspaper_category=$matches[2]&newspaper_paper=$matches[3]&newspaper_date=$matches[4]';

  	return $page_rewrite;
  }


  	public function add_query_vars( $vars ) {
		    $vars[] = 'newspaper_category';
        $vars[] = 'newspaper_date';
        $vars[] = 'newspaper_paper';
		return $vars;
	}



}

$contra_rewrite_rules = new Contra_Rewrite_Rules();
