<?php

/**
 * Abstract class for Bon
 */
abstract class News247_Bon{

    /**
     * @var array
     */
    protected $feeds = [];


    /**
     * @var string
     */
    protected $utm = '';


    /**
     * Default image mapping for all publication of wp 
     * @var array
     */
    protected $image_mapping = [
        'ladylike' => '',
        'oneman'   => '',
        'ow'       => '',
    ];


    /**
     * @var string
     */
    protected $ajax_action_name;


    /**
     * @var string
     */
    protected $default_post_image_size = 'full';


    public function __construct( $args = [] ){

        add_action( 'after_setup_theme', [ $this, 'init' ] );
        
    }


    public function init(){

        $this->feeds = $this->get_feeds();

        add_action( "wp_ajax_{$this->ajax_action_name}", [ $this, 'get_bon_posts' ] );
        add_action( "wp_ajax_nopriv_{$this->ajax_action_name}", [ $this, 'get_bon_posts' ] );

    }


    abstract protected function get_feeds();


    abstract protected function sort_posts( $posts );


    protected function get_publication_name( $feed_url ){

        preg_match( '/https:\/\/www\.([^\.]+)\.(gr|com)/', $feed_url, $publication_name );

        $publication_name = $publication_name['1'];

        return $publication_name;
    }

    protected function get_publication_url( $feed_url ){

        preg_match( '/(https:\/\/([^\/]+))/', $feed_url, $publication_url );

        $publication_url = $publication_url['1'];

        return $publication_url;
    }






    protected function get_default_post(){

        $default_post_image = get_field( 'btw__brand_fields__default_image', 'option' );

        return [
            'post_title'   => '',
            'post_image'   => $default_post_image['sizes'][ $this->default_post_image_size ],
            'post_url'     => 'https://www.news247.gr',
            'post_caption' => 'News247',
       ];

    }


    protected function get_post_caption( $post, $feed_section ){
        
        $post_caption_name = !empty( $feed_section['post_caption'] )
            ? $feed_section['post_caption']['name']
            : $feed_section['name'];

        $post_caption_link = !empty( $feed_section['post_caption'] )
            ? $feed_section['post_caption']['link']
            : $feed_section['link'];

        return [
            'name' => $post_caption_name,
            'link' => $post_caption_link,
        ];
    }

    /**
     * Fetch posts from all feed_urls
     * 
     * @return array, $posts
     */
    protected function fetch_posts(){

        $posts = [];    

        foreach( $this->feeds as $feed ){

            $feed_section = $feed['section']; 
            $section_slug = $feed_section['slug'];

            $posts[ $section_slug ] = [];

            foreach( $feed['urls'] as $feed_url ){

                $response = wp_remote_get( $feed_url );

                if( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ){
                    continue;
                }

                $feed_normalized_data = $this->normalize_data( json_decode( $response['body'], JSON_OBJECT_AS_ARRAY ), $feed_url, $feed_section );
                
                $posts[ $section_slug ] = array_merge( $posts[ $section_slug ], $feed_normalized_data );

            }

        }

        return $posts;
    }


    /**
     * Normalize data of all publication to have the same properties
     * 
     * @param array, $original_data
     * @param string, $feed_url
     * @param array, $feed_section
     */
    protected function normalize_data( $original_data, $feed_url, $feed_section ){

        $data = [];

        try{

            $publication_name = $this->get_publication_name( $feed_url );
            $publication_url = $this->get_publication_url( $feed_url );


            // publications that are on wp
            if( in_array( $publication_name, [ 'ladylike', 'oneman', 'ow' ] ) ){
                
                // check if the feed contains groups with post or only posts
                // If contains only post, format data to match the groups with posts structure
                if( !preg_match( '/(ldl|onm|ow)-feed/', $feed_url, $feed_type ) ){
                    $original_data = array(
                        array(
                            'group' => $original_data,
                        ),
                    );
                }
                
                $groups = wp_list_pluck( $original_data, 'group' );
                
                foreach( $groups as $group ){

                    // return only needed properties
                    $group_posts = array_map( function( $group_post ) use( $publication_name, $feed_section, $publication_url ){
                    
                        /**
                         * @note ladylike + oneman have 'post' key defined. Ow doesnt
                         */
                        $post = $group_post['post'] ?? $group_post;

                        // get image size depending on image_mapping
                        $group_post_image = $post['post_image_available_sizes'][ $this->image_mapping[ $publication_name ] ]
                            ?? ($post['post_image'] ?: '' );

                        $post_title = $post['post_title'];

                        $post_attributes = [
                            'post_title'            => $post_title,
                            'post_title_esc'        => esc_attr($post_title),
                            'post_url'              => $post['post_url'],
                            'post_image'            => $group_post_image,
                            'post_date'             => $post['post_date'],
                            'post_publication_name' => $publication_name,
                            'post_publication_url'  => $publication_url,

                        ];

                        /**
                         * Get Post caption
                         */
                        $post_caption = $this->get_post_caption( $post_attributes, $feed_section );

                        $post_attributes['post_caption'] = $post_caption['name'];
                        $post_attributes['post_caption_link'] = $post_caption['link'];

                        
                        return $post_attributes;

                    }, $group );

                    

                    $data = array_merge( $data, $group_posts );
                }
            
            // publications that are not in wp
            }else{

                $posts = $original_data['data'];

                // return only needed properties
                $posts = array_map( function( $post ) use( $feed_section, $publication_name, $publication_url ) {

                    $post_image = $post['mainImage']['0'] ? ( $post['mainImage']['0']['url'] ?? '' ) : '';

                    $post_date = new DateTime( $post['published'], wp_timezone() );

                    $post_title = $post['teasertitle'] ?: $post['title'];

                    $post_attributes = [
                        'post_title'            => $post_title,
                        'post_title_esc'        => esc_attr( $post_title ),
                        'post_url'              => $post['alternateUrl'],
                        'post_image'            => $post_image,
                        'post_date'             => $post_date->format('Y-m-d H:i:s'),
                        'post_publication_name' => $publication_name,
                        'post_publication_url'  => $publication_url,
                    ];

                    /**
                     * Get Post caption
                     */
                    $post_caption = $this->get_post_caption( $post_attributes, $feed_section );

                    $post_attributes['post_caption'] = $post_caption['name'];
                    $post_attributes['post_caption_link'] = $post_caption['link'];


                    return $post_attributes;

                }, $posts );

                $data = array_merge( $data, $posts );

            }
        
        }catch( Exception $e ){
            return [];
        }

        return $data;
    }


    /** 
     * Validate ajax requests
     * 
     * @param string|null $type
     */
    protected function validate_ajax_request(){

        return true;

    }

    /**
     * Ajax send posts in json
     */
    public function get_bon_posts(){
       
        if( $this->validate_ajax_request() === false ){
            wp_send_json_error( 'unauthorized', 403 );
        }
        
        $bon_posts = $this->fetch_posts();

        wp_send_json([
            'posts' => $this->sort_posts( $bon_posts ),
        ]);

    }


    
}

