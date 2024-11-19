<?php

class Contra_Bon_Hp_Two extends Contra_Bon{

    protected $utm = '?utm_source=Contra&utm_medium=<section_slug>_BON2&utm_campaign=24MediaWidget';

    protected $image_mapping = [
        'ladylike' => 'small-landscape',
        'oneman'   => 'small-landscape',
        'ow'       => 'landscape_medium_smal'
    ];

    protected $default_post_image_size = 'medium_horizontal';

    protected $ajax_action_name = 'bon_hp_two';

    public function __construct( $args = [] ){

        parent::__construct();

    }


    public function get_feeds(){

        return array(
            array(
                'section' => array(
                    'name' => 'ΓΥΝΑΙΚΑ',
                    'slug' => sanitize_title( 'ΓΥΝΑΙΚΑ' ),
                    'link' => '',
                ),
                'urls' => array(
                    'https://www.ow.gr/wp-json/wp/v2/ow-posts?api_key=c201adec4f043dcc338c0dfdc9718088fcea60998f8ca50d&categories=7&per_page=4',
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-posts/?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c&categories=3411,3412,3423&per_page=4',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'ΠΟΛΗ',
                    'slug' => sanitize_title('ΠΟΛΗ'),
                    'link' => '',
                ),
                'urls' => array(
                    'https://www.oneman.gr/wp-json/wp/v2/onm-posts/?api_key=5f17edabb93fe37e26b3edaed71239f6359cb50461f3d43d&categories=1020&per_page=4'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'WELLNESS',
                    'slug' => sanitize_title('WELLNESS'),
                    'link' => '',
                ),
                'urls'          => array(
                    'https://www.ow.gr/wp-json/wp/v2/ow-posts?api_key=c201adec4f043dcc338c0dfdc9718088fcea60998f8ca50d&categories=5&per_page=4',
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-posts/?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c&categories=3413&per_page=4',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'FTINESS',
                    'slug' => sanitize_title('FTINESS'),
                    'link' => '',
                ),
                'urls' => array(
                    'https://www.ow.gr/wp-json/wp/v2/ow-posts?api_key=c201adec4f043dcc338c0dfdc9718088fcea60998f8ca50d&categories=6&per_page=4',
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-posts/?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c&tags=1031&per_page=4',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'ΑΥΤΟΚΙΝΗΤΟ',
                    'slug' => sanitize_title('ΑΥΤΟΚΙΝΗΤΟ'),
                    'link' => '',
                ),
                'urls' => array(
                    'https://www.sport24.gr/auto24/pool.json?profile=24media&items=5'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'ΑΘΛΗΤΙΚΑ',
                    'slug' => sanitize_title('ΑΘΛΗΤΙΚΑ'),
                    'link' => '',
                ),
                'urls' => array(
                    'https://www.contra.gr/pool.json?profile=24media',
                    'https://www.sport24.gr/pool.json?profile=24media',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'ENTERTAINMENT',
                    'slug' => sanitize_title('ENTERTAINMENT'),
                    'link' => '',
                ),
                'urls' => array(
                    'https://www.oneman.gr/wp-json/wp/v2/onm-posts/?api_key=5f17edabb93fe37e26b3edaed71239f6359cb50461f3d43d&categories=384&per_page=4'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'SHOPPING',
                    'slug' => sanitize_title('SHOPPING'),
                    'link' => '',
                ),
                'urls' => array(
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-posts/?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c&tags=700&per_page=4',
                    'https://www.oneman.gr/wp-json/wp/v2/onm-posts/?api_key=5f17edabb93fe37e26b3edaed71239f6359cb50461f3d43d&tags=169&per_page=4'
                ),
            ),

        );
    }


    protected function set_post_utm( $post, $args = [] ){

        $utm_value = str_replace( '<section_slug>', ( $args['section_slug'] ?? '' ), $this->utm );
        $post['post_url'] = $post['post_url'] . '?' . $utm_value;

        return $post;
    }


    private function get_section( $section_slug ){

        $all_feeds = $this->feeds;

        $matched_feeds = array_filter( $all_feeds, function( $feed ) use( $section_slug ) {
            return $feed['section']['slug'] == $section_slug;
        });

        $matched_feed = array_shift( $matched_feeds );

        $section_title = $matched_feed['section']['name'];
        $section_link = $matched_feed['section']['link'];

        return [
            'title' => $section_title,
            'link'  => $section_link,
        ];
    }


    protected function get_post_caption( $post, $feed_section ){

        $post_publication_name = $post['post_publication_name'] == 'ow'
            ? 'OW'
            : ucfirst( $post['post_publication_name'] );

        return [
            'name' => $post_publication_name,
            'link' => $post['post_publication_url'],
        ];
    }


    protected function sort_posts( $feed_posts ){

        $cols = [];

        foreach( $feed_posts as $section_slug => $posts ){

            usort( $posts, function( $a, $b ){
                return $a['post_date'] == $b['post_date'] ? 0 : ( $a['post_date'] < $b['post_date'] ? 1 : -1 );
            });

            $posts = array_slice( $posts, 0, 4 );

            // fill posts with default posts if needed.
            // We want all cols to have 4 posts.
            if( count( $posts ) > 4 ){
                for( $i = count( $posts ); $i <= 4 - count( $posts ); $i++ ){
                    $posts[] = $this->get_default_post();
                }
            }

            $posts = array_map( function( $post ) use( $section_slug ){
                $post = $this->set_post_utm( $post, [ 'section_slug' => $section_slug ] );
                return $post;

            }, $posts );

            $section = $this->get_section( $section_slug );

            $col = [
                'section' => $section,
                'posts'   => $posts,
            ];

            $cols[] = $col;
        }

        return $cols;
    }


}

$btw_hp_bon_two = new Contra_Bon_Hp_Two();