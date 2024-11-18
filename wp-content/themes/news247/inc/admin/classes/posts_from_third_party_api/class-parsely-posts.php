<?php

namespace News247_Posts_From_Third_Party_Api;

class Parsely_Posts extends Posts_From_Api{

    protected $site_id = 'news247.gr';

    protected $api_secret = 'auKPcpdedBHZau7cMG9ZeL8cyRAcxsyXeRRPFBPAN5Y';

    // protected $base_api_url = 'https://api.parsely.com/v2/analytics/posts?apikey=<site_id>&secret=<api_secret>&sort=views&page=1&period_start=1h&pub_date_start=7d&limit=<per_page>&section=';
    protected $base_api_url = 'https://api.parsely.com/v2/analytics/posts';

    protected $term_ids;

    protected $per_page = 4;
    
    public function __construct( $args = [] ){
        $this->term_ids = $args['term_ids'] ?? [];

        if( !is_array( $this->term_ids ) ){
            $this->term_ids = [ $this->term_ids ];
        }

        $this->per_page = $args['per_page'] ?? $this->per_page;
        $pub_date_start = $args['pub_date_start'] ?? '';

        $query_vars = [
            'apikey' => $this->site_id,
            'secret' => $this->api_secret,
            'sort'  => 'views',
            'page'  => 1,
            'period_start' => '1h',
            'limit' => $this->per_page,
        ];

        if( $pub_date_start ){
            $query_vars['pub_date_start'] = $pub_date_start;
        }

         $query_vars['section'] = '';

        $this->base_api_url = $this->base_api_url . '?' . http_build_query( $query_vars );
    }


    protected function get_apis(){

        $apis = [];

        foreach( $this->term_ids as $term_id ){
            $requested_term = get_term_by( 'term_id', $term_id, 'category' );
             $apis[] = [
                'url' => $this->base_api_url . $requested_term->name,
             ];
        }

        return $apis;
    }


    protected function sort_posts( $parsely_posts ){

        $parsely_posts = array_unique( $parsely_posts, SORT_REGULAR );

        uasort( $parsely_posts, function( $a, $b ){
            return $a['_hits'] == $b['_hits'] ? 0 : ( $a['_hits'] < $b['_hits'] ? 1 : -1 );
        });

        return $parsely_posts;
    }


    protected function normalize_data( $response_data, $api ){

        $posts = $response_data['data'];

        $posts = array_map( function( $post ){

            $post['original_title'] = $post['title'];
            $post['url'] = explode( '?', $post['url'] )['0'];
            
            $post['title'] = strpos( $post['title'], ':' ) !== false
                ? preg_replace( '/^([^:]+:)/', '<strong>$1</strong>', $post['title'] )
                :  "<strong>{$post['title']}</strong>";


            // store post title with uppercase version
            $post['title_uppercase'] = remove_punctuation( $post['original_title'] );

            $post['title_uppercase'] = strpos( $post['title_uppercase'], ':' ) !== false
                ? preg_replace('/^([^:]+:)/', '<strong>$1</strong>', $post['title_uppercase'])
                :  "<strong>{$post['title_uppercase']}</strong>";


            return $post;

        }, $posts );

        return $posts;
    }





}
