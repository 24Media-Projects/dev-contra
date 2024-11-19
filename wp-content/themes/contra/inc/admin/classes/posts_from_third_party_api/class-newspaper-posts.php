<?php

namespace Contra_Posts_From_Third_Party_Api;

class Newspapers_Posts extends Posts_From_Api {

    protected $base_api_url = 'https://protoselida.24media.gr/public/json/widget?widgetId=3&date=';
    
    public function __construct( $args = [] ){

        
    }

    protected function get_apis(){
        return array(
            array(
                'url' => $this->base_api_url . date('Ymd'),
            )
        );
    }


    protected function sort_posts( $newspapers_posts ){

        $widget_groups = $newspapers_posts['widgetGroups'];
        $groups = array_filter( wp_list_pluck( $widget_groups, 'group' ), function( $group ){
            return in_array( $group['name'], ['politikes', 'oikonomikes', 'athlitikes', 'kuriakatikes' ] );
        });

        $sunday_pappers_group = array_filter( wp_list_pluck( $widget_groups, 'group' ), function( $group ){
            return $group['name'] == 'kuriakatikes';
        });

        if( !empty( $sunday_pappers_group ) ){

            $sunday_pappers = array_values( $sunday_pappers_group );
            $sunday_pappers_index = array_keys( $sunday_pappers_group )['0'];
            unset( $groups[$sunday_pappers_index] );

            if( date( 'N' ) != 7  ){
                $groups = array_merge( $groups, $sunday_pappers );
            }else{
                $groups = array_merge( $sunday_pappers, $groups );
            }
        }

        $posts = [];

        $newspapers_page = btw_get_page_by_template('templates/protoselida.php');

        foreach( $groups as $group ){

            if( !$group['newspapers'] ){
                continue;
            }

            $group_posts = array_map(function( $post ) use ($newspapers_page, $group){
                $post['imgUrl'] = "https://protoselida.24media.gr{$post['imgUrl']}";
                $post['group'] = $group['name'];
                $today = date('dmY');
                $post['url'] = site_url( "/{$newspapers_page->post_name}/{$group['name']}-efimerides/{$post['name']}/date/{$today}/" );

                return $post;
                
            }, $group['newspapers'] );

            $posts = array_merge( $posts, $group_posts );
        }

        return $posts;
    }

}