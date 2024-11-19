<?php 

use Contra_Posts_From_Third_Party_Api\Parsely_Posts;

class Contra_Popular_Posts_Primary_Category{


    public function __construct(){
        
        add_action( 'wp_ajax_get_primary_category_popular_articles', [  $this, 'get_popular_articles' ] );
        add_action( 'wp_ajax_nopriv_get_primary_category_popular_articles', [  $this, 'get_popular_articles' ] );
    }


    public function get_popular_articles(){

        $term_id = $_GET['term_id'] ?? '';
        $per_page = $_GET['per_page'] ?? 4;
        $pub_date_start = $_GET['pub_date_start'] ?? '';

        $data = [
            'posts' => [],
        ];

        if( !$term_id ){
            wp_send_json( $data );
        }

        $args = [
            'term_ids' => $term_id,
            'per_page' => $per_page,
        ];

        if( $pub_date_start ){
            $args['pub_date_start'] = $pub_date_start;
        }

        $parsely_posts = new Parsely_Posts($args);

        $data['posts'] = $parsely_posts->get_posts();

        wp_send_json( $data );
    }

}

 $contra_popular_posts_primary_category = new Contra_Popular_Posts_Primary_Category();