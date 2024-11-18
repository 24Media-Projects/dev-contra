<?php 

/**
 * Alter post data returned from base api post
 * @see parent theme base_api_functions.php
 * 
 * @param array, $post_data
 * @param WP_Post, $post
 * @param string, $endpoint
 * 
 * @return array
 * 
 * @todo add data about sponsor
 */
add_filter( 'btw/base_api/post/post_data', function( $post_data, $post, $endpoint ){

    if( !btw_is_xml_api_request() || !$post ){
        return $post_data;
    }

    //Escape post_title using wp filter the_title_rss
    $post_data['post_title'] = apply_filters( 'the_title_rss', $post_data['post_title'] );
    $post_data['post_obj'] = $post;

    return $post_data;

}, 10, 3 );


/**
 * Prevent yoast from altering bloginfo_rss( 'url' )
 * 
 * @param string, $info
 * @param string, $show
 * 
 * @return string 
 */
add_filter( 'get_bloginfo_rss', function( $info, $show ){

    if( $show == 'url' ){
        $info = strip_tags( get_bloginfo( $show ) );
        return convert_chars( $info );
    }

    return $info;

}, 99, 2 );

