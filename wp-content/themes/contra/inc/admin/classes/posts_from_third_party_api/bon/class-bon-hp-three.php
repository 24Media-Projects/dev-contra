<?php

namespace Contra_Posts_From_Third_Party_Api;

class Bon_Hp_Three extends Bon{

    protected $image_mapping = [
        'ladylike' => 'medium_large',
        'oneman'   => 'medium_lanscape',
        'ow'       => 'landscape_feed'
    ];

    protected $default_post_image_size = 'medium_horizontal';

    protected $post_meta_key = 'bon_hp_three_posts';

    protected $current_group_id;

    public function __construct( $args = [] ){

        parent::__construct();

        add_action( 'btw_bon_three_hp_cron', [$this, 'cron_handler' ] );

    }


    public static function schedule_event(){

        if( !wp_next_scheduled( 'btw_bon_three_hp_cron' ) ){
            wp_schedule_event( time(), 'every_10_minutes', 'btw_bon_three_hp_cron' );
        }
    }


    protected function get_group_ids(){

        global $wpdb;

        $group_ids = $wpdb->get_col(
            "SELECT p.ID FROM $wpdb->posts as p
             LEFT JOIN $wpdb->postmeta as m1 ON p.ID = m1.post_id AND m1.meta_key = 'btw__group_fields__hp__template'
             LEFT JOIN $wpdb->postmeta as m2 ON p.ID = m2.post_id AND m2.meta_key = 'btw__group_fields__group_type'
             WHERE p.post_status = 'publish' AND p.post_type = 'group' AND m1.meta_value = 'best_of_network_3' AND m2.meta_value = 'hp'"
        );

        return $group_ids ?: [];

    }

  public function get_feeds(){

        $acf_feeds_field = get_field('btw__group_fields__hp__template__best_of_network_3__posts', $this->current_group_id );

        $feeds = [];
        foreach( $acf_feeds_field as $index => $acf_feed_field ){
            $feed_url = $acf_feed_field['feed_url'];

            if(!$feed_url){
                continue;
            }

            $section_name = $this->get_publication_name($feed_url);
            $section_link = $this->get_publication_url($feed_url);

            $feeds[] = array(
                'section' => array(
                    'name' => $section_name,
                    'slug' => 'position_' . $index,
                    'link' => $section_link,
                    'post_caption' => $acf_feed_field['caption'] ?: 'domain',
                    'utm' => $acf_feed_field['utm'],
                ),
                'urls' => [ $feed_url ]
            );
        }

        return $feeds;
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



    protected function set_post_utm( $post, $utm_value ){

        $post['post_url'] = $post['post_url'] . '?' . $utm_value;

        return $post;
    }



    private function get_section_utm( $section_slug ){

        $all_feeds = $this->feeds;

        $matched_feeds = array_filter( $all_feeds, function( $feed ) use( $section_slug ) {
            return $feed['section']['slug'] == $section_slug;
        });

        $matched_feed = array_shift( $matched_feeds );

        $section_utm = $matched_feed['section']['utm'];

        return $section_utm;
    }


    protected function sort_posts( $feed_posts ){

        $posts = [];

        foreach( $feed_posts as $section_slug => $section_posts ){

            $section_utm = $this->get_section_utm($section_slug);

            // fill posts with default posts if needed.
            if( !$section_posts ){
                $section_posts = [ $this->get_default_post() ];
            }

            $section_post = array_shift( $section_posts );
            $section_post = $this->set_post_utm( $section_post, $section_utm );

            $posts[] = $section_post;
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

$contra_hp_bon_three = new Bon_Hp_Three();