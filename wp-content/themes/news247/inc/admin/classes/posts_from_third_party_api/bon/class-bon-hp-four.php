<?php

namespace News247_Posts_From_Third_Party_Api;

class Bon_Hp_Four extends Bon{

    protected $image_mapping = [
        'ladylike' => 'medium_large',
        'oneman'   => 'medium_lanscape',
        'ow'       => 'landscape_feed'
    ];

    protected $default_post_image_size = 'medium_horizontal';

    protected $post_meta_key = 'bon_hp_four_posts';

    protected $current_group_id;

    public function __construct( $args = [] ){

        parent::__construct();

        add_action( 'btw_bon_four_hp_cron', [$this, 'cron_handler' ] );

    }


    public static function schedule_event(){

        if( !wp_next_scheduled( 'btw_bon_four_hp_cron' ) ){
            wp_schedule_event( time(), 'every_10_minutes', 'btw_bon_four_hp_cron' );
        }
    }


    protected function get_group_ids(){

        global $wpdb;

        $group_ids = $wpdb->get_col(
            "SELECT p.ID FROM $wpdb->posts as p
             LEFT JOIN $wpdb->postmeta as m1 ON p.ID = m1.post_id AND m1.meta_key = 'btw__group_fields__hp__template'
             LEFT JOIN $wpdb->postmeta as m2 ON p.ID = m2.post_id AND m2.meta_key = 'btw__group_fields__group_type'
             WHERE p.post_status = 'publish' AND p.post_type = 'group' AND m1.meta_value = 'best_of_network_4' AND m2.meta_value = 'hp'"
        );

        return $group_ids ?: [];

    }

  public function get_feeds(){

        $feed_url = get_field('btw__group_fields__hp__template__best_of_network_4__feed_url', $this->current_group_id);
        $post_caption = get_field('btw__group_fields__hp__template__best_of_network_4__caption', $this->current_group_id);
        $utm = get_field('btw__group_fields__hp__template__best_of_network_4__utm', $this->current_group_id);

        $section_name = $this->get_publication_name( $feed_url );
        $section_link = $this->get_publication_url( $feed_url );

        return array(
            array(
                'section' => array(
                    'name' => $section_name,
                    'slug' => $section_name,
                    'link' => $section_link,
                    'post_caption' => $post_caption ?: 'domain',
                    'utm' => $utm,
                ),
                'urls' => [$feed_url]
            ),
        );
    }


    protected function get_post_caption( $post, $feed_section ){
        
        $post_caption_name = $feed_section['post_caption'] == 'domain'
            ? ( $post['post_publication_name'] == 'ow' ? 'OW' : ucfirst( $post['post_publication_name'] ) )
            : $post['post_primary_category'];

        $post_caption_link = $feed_section['link'];

        return [
            'name' => $post_caption_name,
            'link' => $post_caption_link,
        ];
    }


    protected function set_post_utm( $post, $utm_value, $args ){

        $post['post_url'] = $post['post_url'] . '?' . $utm_value . ( $args['index'] ?? '' );

        return $post;
    }


    protected function sort_posts( $feed_posts ){

        $posts = [];

        $feed_posts = array_slice( array_shift( $feed_posts ), 0, 4 );
        $section = $this->feeds['0'];
        $utm = $section['section']['utm'];

        if( count($feed_posts) < 4 ){
            for( $i = 4 - count($feed_posts); $i <= 4; $i++ ){
                $feed_posts[] = $this->get_default_post();
            }
        }

        foreach( $feed_posts as $index => $feed_post ){

            $post = $this->set_post_utm( $feed_post, $utm, [ 'index' => $index + 1 ] );

            $posts[] = $post;
        }

        return $posts;
    }


    public function cron_handler(){

        $group_ids = $this->get_group_ids();

        foreach( $group_ids as $group_id ){
            $this->current_group_id = $group_id;
            $this->feeds = $this->get_feeds();
            $this->update_post_meta($group_id);
        }

    }

    public function update_post_meta( $group_id ){

        if( !$this->post_meta_key ){
            return;
        }

        $bon_posts = $this->get_posts( $group_id );

        if( !add_post_meta( $group_id, $this->post_meta_key, $bon_posts, true ) ){
            update_post_meta( $group_id, $this->post_meta_key, $bon_posts );
        }

    }


}

$news247_hp_bon_four = new Bon_Hp_Four();