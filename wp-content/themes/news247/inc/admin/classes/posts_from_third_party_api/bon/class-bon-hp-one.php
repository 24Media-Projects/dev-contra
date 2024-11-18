<?php

namespace News247_Posts_From_Third_Party_Api;

class Bon_Hp_One extends Bon{

    protected $utm = 'utm_source=News247&utm_medium=BON1&utm_campaign=24MediaWidget&utm_term=Pos=';

    protected $image_mapping = [
        'ladylike' => 'small-landscape',
        'oneman'   => 'small-landscape',
        'ow'       => 'landscape_medium_smal'
    ];

    protected $default_post_image_size = 'medium_horizontal';

    protected $post_meta_key = 'bon_hp_one_posts';

    public function __construct( $args = [] ){

        parent::__construct();

        add_action( 'btw_bon_one_hp_cron', [ $this, 'cron_handler' ] );

    }


    public static function schedule_event(){

        if( !wp_next_scheduled( 'btw_bon_one_hp_cron' ) ){
            wp_schedule_event( time(), 'every_10_minutes', 'btw_bon_one_hp_cron' );
        }
    }


    protected function get_group_ids(){

        global $wpdb;

        $group_ids = $wpdb->get_col(
            "SELECT p.ID FROM $wpdb->posts as p
             LEFT JOIN $wpdb->postmeta as m1 ON p.ID = m1.post_id AND m1.meta_key = 'btw__group_fields__hp__template'
             LEFT JOIN $wpdb->postmeta as m2 ON p.ID = m2.post_id AND m2.meta_key = 'btw__group_fields__group_type'
             WHERE p.post_status = 'publish' AND p.post_type = 'group' AND m1.meta_value = 'best_of_network' AND m2.meta_value = 'hp'"
        );

        return $group_ids ?: [];

    }


    public function get_feeds(){

        return array(
            array(
                'section' => array(
                    'name' => 'Ladylike',
                    'slug' => sanitize_title('Ladylike'),
                    'link' => '',
                    'utm_name' => 'ladylike',
                ),
                'urls' => array(
                    'https://www.ladylike.gr/wp-json/wp/v2/ldl-feed/266575?api_key=92288d932f3b929ffb6fa7c21f385a1083851f4ae61f947c'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Oneman',
                    'slug' => sanitize_title('Oneman'),
                    'link' => '',
                    'utm_name' => 'oneman',
                ),
                'urls' => array(
                    'https://www.oneman.gr/wp-json/wp/v2/onm-feed/197159?api_key=5f17edabb93fe37e26b3edaed71239f6359cb50461f3d43d'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'OW',
                    'slug' => sanitize_title('Ow'),
                    'link' => '',
                    'utm_name' => 'ow',
                ),
                'urls' => array(
                    'https://www.ow.gr/wp-json/wp/v2/ow-feed/5440?api_key=c201adec4f043dcc338c0dfdc9718088fcea60998f8ca50d'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Sport24',
                    'slug' => sanitize_title('Sport24'),
                    'link' => '',
                    'utm_name' => 'sport24',
                ),
                'urls' => array(
                    'https://www.sport24.gr/24media-network/pool.json?profile=24media&groups=main5'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Brand Experience',
                    'slug' => sanitize_title('Brand Experience'),
                    'link' => '',
                    'utm_name' => 'brand_experience',
                ),
                'urls' => array(
                    'https://www.contra.gr/24media-network/pool.json?profile=24media&groups=main3'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Partners',
                    'slug' => sanitize_title('Partners'),
                    'link' => '',
                    'utm_name' => 'partners',
                ),
                'urls' => array(
                    'https://www.contra.gr/24media-network/pool.json?profile=24media&groups=right3&type=news,advertorial&items=5'
                ),
            )
        );
    }


    protected function set_post_utm( $post, $args = [] ){

        $utm_value = $this->utm . (  $args['index'] ?? '' );
        $post['post_url'] = $post['post_url'] . '?' . $utm_value;

        return $post;
    }


    protected function sort_posts( $feed_posts ){

        $posts = [];

        // randomize order of brand_experience posts ( publication first of contra.gr )
        shuffle( $feed_posts[ 'brand-experience'] );

        $positions = [
            'ladylike',
            'oneman',
            'ladylike',
            'ow',
            'ladylike',
            'brand-experience',
            'ow',
            'oneman',
            'ow',
            'partners',
            'sport24',
            'brand-experience',
        ];

        foreach( $positions as $index => $position ){

            $post = array_shift( $feed_posts[ $position ] ) ?? $this->get_default_post();
            $post = $this->set_post_utm( $post, ['index' => $index + 1 ] );

            $posts[] = $post;

        }

        return $posts;
    
    }


}

$news247_hp_bon_one = new Bon_Hp_One();