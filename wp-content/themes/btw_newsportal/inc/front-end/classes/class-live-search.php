<?php
/*
  Live search functionality
  The search query var is: s
  Success: Returns html of posts which match the search query
  Error / No results: false
  The limit of returning posts is: 10
  See wp_ajax action
      wp_ajax_nopriv action
      assets/js/live-search/live-search.bundle.js
      wp nonces
      inc/front-end/template-functions.php btw_return_template_part theme function
      for more details
*/

class BTW_Live_Search{

	protected $limit_results;

	public function __construct( $args = [] ){

    $this->limit_results = $args['limit_results'] ?? 10;

		add_action( 'wp_ajax_get_live_search_results', [ $this, 'get_live_search_results_handler' ] );
		add_action( 'wp_ajax_nopriv_get_live_search_results', [ $this, 'get_live_search_results_handler' ] );

	}

	protected function get_post_results(){

    global $wpdb;

		$search_query = '%' . $_GET['s'] . '%';

    $sql_query_where = apply_filters( 'btw/live_search/sql_query_where', "AND p.post_title LIKE '{$search_query}' AND p.post_status='publish' AND p.post_type = 'post'", $search_query );

    $sql_query_orderby = apply_filters( 'btw/live_search/sql_query_orderby', "ORDER BY p.post_date" );

    $sql_query_order = apply_filters( 'btw/live_search/sql_query_order', "DESC" );


    $sql_query = "SELECT ID FROM $wpdb->posts as p WHERE 1=1 {$sql_query_where} {$sql_query_orderby} {$sql_query_order} LIMIT {$this->limit_results}";

    $sql_query_rows_found = "SELECT COUNT( ID ) FROM $wpdb->posts as p WHERE 1=1 {$sql_query_where} {$sql_query_orderby} {$sql_query_order} LIMIT {$this->limit_results}";

		return [
			'posts' => $wpdb->get_col( $sql_query ),
			'rows_found' => $wpdb->get_var( $sql_query_rows_found ),
		];

	}

	public function get_live_search_results_handler(){

		if( empty( $_GET['nonce'] ) || empty( $_GET['s'] ) || !wp_verify_nonce( $_GET['nonce'], 'live_search' ) ){

			$return['success'] = false;
			wp_send_json( $return );

		}

		$posts_results = self::get_post_results();

		$posts = $posts_results['posts'];
		$rows_found = $posts_results['rows_found'];

		if( !$posts ){
			$return['success'] = false;
			
			wp_send_json( $return );
		}

		$return['success'] = true;
		$return['more'] = $rows_found > $this->limit_results;

		foreach( $posts as $post_id ):

			$post = get_post( $post_id );

			$post_data = apply_filters( 'btw/live_search/post_data', array(
				'post_title' 	=> get_the_title( $post ),
				'post_url' 		=> get_permalink( $post ),
				'post_id' 		=> $post->ID,
				'post_type' 	=> $post->post_type,
			));

			$return['data'][] = btw_return_template_part( 'template-parts/live_search_results', $post_data );

		endforeach;

		wp_send_json( $return );

	}

}




?>
