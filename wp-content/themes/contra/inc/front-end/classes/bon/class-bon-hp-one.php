<?php

class Contra_Bon_Hp_One extends Contra_Bon{

    protected $utm = '?utm_source=Contra&utm_medium=BON1&utm_campaign=24MediaWidget&utm_term=Pos=';

    protected $image_mapping = [
        'ladylike' => 'small-landscape',
        'oneman'   => 'small-landscape',
        'ow'       => 'landscape_medium_smal'
    ];

    protected $default_post_image_size = 'medium_horizontal';

    protected $ajax_action_name = 'bon_hp_one';

    public function __construct( $args = [] ){

        parent::__construct();

    }


    public function get_feeds(){

        return array(
            array(
                'section' => array(
                    'name' => 'Ladylike',
                    'slug' => sanitize_title('Ladylike'),
                    'link' => '',
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
                ),
                'urls' => array(
                    'https://www.contra.gr/24media-network/pool.json?profile=24media&groups=native-hp'
                ),
            ),
            array(
                'section' => array(
                    'name' => 'Partners',
                    'slug' => sanitize_title('Partners'),
                    'link' => '',
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
            'sport24',
            'ow',
            'ladylike',
            'brand-experience',
            'ow',
            'oneman',
            'ow',
            'partners',
            'ladylike',
            'brand-experience',
        ];

        foreach( $positions as $index => $position ){

            $post = array_shift( $feed_posts[ $position ] ) ?? $this->get_default_post();
            $post = $this->set_post_utm( $post, ['index' => $index + 1 ] );

            $posts[] = $post;

        }
        // test
        $posts['2'] = $this->get_default_post();

        return $posts;
    }


}

$btw_hp_bon_one = new Contra_Bon_Hp_One();