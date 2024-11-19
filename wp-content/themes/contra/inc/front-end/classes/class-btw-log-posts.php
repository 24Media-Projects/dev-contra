<?php 

class BTW_Log_Posts{

    private $displayed_posts;

    public function __construct(){
        $this->displayed_posts = [];    
    }


    public function log_post( $post ){
        if( $post instanceof WP_Post ){
            $post = $post->ID;
        }

        $this->displayed_posts[] = $post;
    }

    
    public function log_posts( $posts = [] ){

        foreach( $posts as $post ){

            if( $post instanceof WP_Post ){
                $post = $post->ID;
            }

            $this->displayed_posts[] = $post;
        }
    }


    public function get_displayed_posts(){
        return array_unique( $this->displayed_posts );
    }

}

$btw_log_posts = new BTW_Log_Posts;
$SLOBALS['btw_log_posts'] = $btw_log_posts;