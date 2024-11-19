<?php

add_filter( 'btw/base_api/atf_post/post_data', function( $atf_post_data, $atf_post, $wp_post, $group_template ){

    if( !btw_is_xml_api_request() || !$wp_post ){
        return $atf_post_data;
    }

    //Escape post_title using wp filter the_title
    $atf_post_data['post_title'] = apply_filters( 'the_title_rss', $atf_post_data['post_title'] );
    $atf_post_data['post_obj'] = $wp_post;
    $atf_post_data['post_image_id'] = $atf_post['featured_image_id'];

    return $atf_post_data;
}, 10, 4 );
