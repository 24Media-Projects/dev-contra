<?php

namespace News247_Posts_From_Third_Party_Api;

class Bon_Hp_Two extends Bon{

    protected $utm = 'utm_source=News247&utm_medium=<section_slug>_BON2&utm_campaign=24MediaWidget';

    protected $image_mapping = [
        'ladylike' => 'small-landscape',
        'oneman'   => 'small-landscape',
        'ow'       => 'landscape_medium_smal'
    ];

    protected $default_post_image_size = 'medium_horizontal';

    protected $post_meta_key = 'bon_hp_two_posts';

    public function __construct( $args = [] ){

        parent::__construct();

        add_action( 'btw_bon_two_hp_cron', [$this, 'cron_handler' ] );

    }


    public static function schedule_event(){

        if( !wp_next_scheduled( 'btw_bon_two_hp_cron' ) ){
            wp_schedule_event( time(), 'every_10_minutes', 'btw_bon_two_hp_cron' );
        }
    }


    protected function get_group_ids(){

        global $wpdb;

        $group_ids = $wpdb->get_col(
            "SELECT p.ID FROM $wpdb->posts as p
             LEFT JOIN $wpdb->postmeta as m1 ON p.ID = m1.post_id AND m1.meta_key = 'btw__group_fields__hp__template'
             LEFT JOIN $wpdb->postmeta as m2 ON p.ID = m2.post_id AND m2.meta_key = 'btw__group_fields__group_type'
             WHERE p.post_status = 'publish' AND p.post_type = 'group' AND m1.meta_value = 'best_of_network_2' AND m2.meta_value = 'hp'"
        );

        return $group_ids ?: [];

    }



    public function get_feeds(){

        return array(
            array(
                'section' => array(
                    'name'     => 'Γυναίκα',
                    'slug'     => sanitize_title( 'ΓΥΝΑΙΚΑ' ),
                    'link'     => '',
                    'utm_name' => 'woman',
                ),
                'urls' => array(
                    // 'https://www.ow.gr/wp-json/wp/v2/ow-posts?api_key=c201adec4f043dcc338c0dfdc9718088fcea60998f8ca50d&categories=7&per_page=4',
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-feed/836831?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Πόλη',
                    'slug' => sanitize_title('ΠΟΛΗ'),
                    'link' => '',
                    'utm_name' => 'city',
                ),
                'urls' => array(
                    'https://www.oneman.gr/wp-json/wp/v2/onm-feed/581575?api_key=4f8cf6b6e2abf1030af13fdbde257ef342b648706f841e5f'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Wellness',
                    'slug' => sanitize_title('WELLNESS'),
                    'link' => '',
                    'utm_name' => 'wellness',
                ),
                'urls'          => array(
                    'https://www.ow.gr/wp-json/wp/v2/ow-posts?api_key=c201adec4f043dcc338c0dfdc9718088fcea60998f8ca50d&categories=5&per_page=4',
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-posts/?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c&categories=3413&per_page=4',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Διατροφή',
                    'slug' => sanitize_title('Διατροφή'),
                    'link' => '',
                    'utm_name' => 'diatrofi',
                ),
                'urls' => array(
                    'https://www.ow.gr/wp-json/wp/v2/ow-posts?api_key=c201adec4f043dcc338c0dfdc9718088fcea60998f8ca50d&categories=6&per_page=4',
                    'https://www.oneman.gr/wp-json/wp/v2/onm-posts/?api_key=5f17edabb93fe37e26b3edaed71239f6359cb50461f3d43d&tags=59&per_page=4',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Αυτοκίνητο',
                    'slug' => sanitize_title('ΑΥΤΟΚΙΝΗΤΟ'),
                    'link' => '',
                    'utm_name' => 'auto',
                ),
                'urls' => array(
                    'https://www.sport24.gr/auto24/pool.json?profile=24media&items=5'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Αθλητικά',
                    'slug' => sanitize_title('ΑΘΛΗΤΙΚΑ'),
                    'link' => '',
                    'utm_name' => 'sports',
                ),
                'urls' => array(
                    'https://www.contra.gr/pool.json?profile=24media',
                    'https://www.sport24.gr/pool.json?profile=24media',
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Entertainment',
                    'slug' => sanitize_title('ENTERTAINMENT'),
                    'link' => '',
                    'utm_name' => 'entertainment',
                ),
                'urls' => array(
                    'https://www.oneman.gr/wp-json/wp/v2/onm-posts/?api_key=5f17edabb93fe37e26b3edaed71239f6359cb50461f3d43d&categories=384&per_page=4'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Shopping',
                    'slug' => sanitize_title('SHOPPING'),
                    'link' => '',
                    'utm_name' => 'shopping',
                ),
                'urls' => array(
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-posts/?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c&tags=700&per_page=4',
                    'https://www.oneman.gr/wp-json/wp/v2/onm-posts/?api_key=5f17edabb93fe37e26b3edaed71239f6359cb50461f3d43d&tags=169&per_page=4'
                ),
            ),

        );
    }


    protected function set_post_utm( $post, $args = [] ){

        $section = $args['section'] ?? [];

        $utm_value = str_replace( '<section_slug>', ( $section['utm_name'] ?? '' ), $this->utm );
        $post['post_url'] = $post['post_url'] . '?' . $utm_value;

        return $post;
    }


    private function get_section( $section_slug ){

        $all_feeds = $this->feeds;

        $matched_feeds = array_filter( $all_feeds, function( $feed ) use( $section_slug ) {
            return $feed['section']['slug'] == $section_slug;
        });

        $matched_feed = array_shift( $matched_feeds );

        $section_title  = $matched_feed['section']['name'];
        $section_link   = $matched_feed['section']['link'];
        $section_utm    = $matched_feed['section']['utm_name'];

        $section_feed_urls = $matched_feed['urls'];

        return [
            'title'    => $section_title,
            'link'     => $section_link,
            'utm_name' => $section_utm,
            'sort_posts_by_date' => count( $section_feed_urls ) > 1,
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

            $section = $this->get_section($section_slug);

            // If section has more than one feed url, sort posts by date
            if( $section['sort_posts_by_date'] ){
                usort( $posts, function( $a, $b ){
                    return $a['post_date'] == $b['post_date'] ? 0 : ( $a['post_date'] < $b['post_date'] ? 1 : -1 );
                });
            }

            $posts = array_slice( $posts, 0, 4 );

            // fill posts with default posts if needed.
            // We want all cols to have 4 posts.
            if( count( $posts ) < 4 ){
                for( $i = count( $posts ); $i <= 4 - count( $posts ); $i++ ){
                    $posts[] = $this->get_default_post();
                }
            }

            $posts = array_map( function( $post ) use( $section ){
                $post = $this->set_post_utm( $post, [ 'section' => $section ] );
                return $post;

            }, $posts );


            $col = [
                'section' => $section,
                'posts'   => $posts,
            ];

            $cols[] = $col;
        }

        return $cols;
    }

}

$news247_hp_bon_two = new Bon_Hp_Two();