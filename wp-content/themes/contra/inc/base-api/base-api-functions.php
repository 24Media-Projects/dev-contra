<?php 

function btw_get_group_template_post_acf_fields( $group_type = 'hp' ){

    $group_templates = array(

        'hp' => array(
            'above_the_fold' => array(
                'btw__group_fields__hp__template__above_the_fold__big_story_post_selection' => 'atf_post',
                'btw__group_fields__hp__template__above_the_fold__featured_post_selection'  => 'atf_post',
                'btw__group_fields__hp__template__above_the_fold__posts_selection'          => 'atf_post',
            ),

            'happens_now' => array(
                'btw__group_fields__hp__template__happens_now__post_selection' => 'default',
            ),

            'latest_stories' => array(
                'btw__group_fields__hp__template__latest_stories__featured_post_selection' => 'atf_post',
                'btw__group_fields__hp__template__latest_stories__skitso'                  => 'default',
                'btw__group_fields__hp__template__latest_stories__posts_selection'         => 'atf_post',
            ),

            'podcasts_carousel' => array(
                'btw__group_fields__hp__template__podcasts_carousel__posts_selection' => 'atf_post',
            ),

            'term_basic__with_banner' => array(
                'btw__group_fields__hp__template__term_basic__with_banner__featured_post_selection' => 'atf_post',
                'btw__group_fields__hp__template__term_basic__with_banner__posts_selection'         => 'atf_post',
            ),

            'term_basic' => array(
                'btw__group_fields__hp__template__term_basic__featured_post_selection' => 'atf_post',
                'btw__group_fields__hp__template__term_basic__posts_selection'         => 'atf_post',
            ),

            'tribute_basic' => array(
                'btw__group_fields__hp__template__tribute_basic__featured_post_selection' => 'atf_post',
                'btw__group_fields__hp__template__tribute_basic__posts_selection'         => 'atf_post',
            ),

            'tribute_accordion' => array(
                'btw__group_fields__hp__template__tribute_accordion__posts_selection' => 'atf_post',
            ),

            'videos_carousel' => array(
                'btw__group_fields__hp__template__videos_carousel__videos_selection' => 'atf_post',
            ),

            'zodiac_signs' => array(
                'btw__group_fields__hp__template__zodiac_signs__posts_selection' => 'atf_post',
            ),

            'articles_grid' => array(
                'btw__group_fields__hp__template__articles_grid__featured_post_selection' => 'atf_post',
                'btw__group_fields__hp__template__articles_grid__posts_selection'         => 'atf_post'
            ),
            
            'the_magazine' => array(
                'btw__group_fields__hp__template__the_magazine__posts_selection' => 'atf_post',
            ),

            'opinions_carousel_by_author' => array(
                'btw__group_fields__hp__template__opinions_carousel_by_author__posts_selection' => 'atf_post',
            ),

        ),
        'magazine' => array(
            'above_the_fold' => array(
                'btw__group_fields__magazine__template__above_the_fold__featured_post_selection' => 'atf_post',
            ),

            'above_the_fold_half_article' => array(
                'btw__group_fields__magazine__template__above_the_fold_half_article__featured_post_selection' => 'atf_post',
            ),

            'lab_video' => array(
                'btw__group_fields__magazine__template__lab_video__video_selection' => 'atf_post',
            ),

            'past' => array(
                'btw__group_fields__magazine__template__past__featured_post_selection' => 'atf_post',
            ),

            'single_sponsored_article' => array(
                'btw__group_fields__magazine__template__single_sponsored_article__featured_post_selection' => 'atf_post',
            ),
        ),
        'bon' => array(
            'default' => array(
                'btw__group_fields__bon__template__default__posts_selection' => 'bon',
            )
        )
    );

    return $group_templates[ $group_type ];

}



/**
 * Get bon post item basic data for base api
 * @param array $repeater_row, acf repeater row data
 */
function get_base_api_bon_post_data( $repeater_row ){
    
    global $post;

    $post = $repeater_row['post']['0'];
    setup_postdata( $post->ID );

    $post_title = $repeater_row['teaser_title'] ?: $post->post_title;
    $post_title = wp_specialchars_decode( $post_title, ENT_QUOTES );

    $post__default_feat_image = btw_get_post_featured_image( 'full', $post );
    $post__feat_image_url = $repeater_row['teaser_image']
        ? $repeater_row['teaser_image']['url']
        : $post__default_feat_image->url;

    $post__feat_image_id = $repeater_row['teaser_image']
        ? $repeater_row['teaser_image']['id']
        : $post__default_feat_image->id;

    $post__primary_category = btw_get_post_primary_category( $post );
    $post_author = btw_get_post_author( $post );

    $attachment_sizes = btw_get_attachment_sizes( $post__feat_image_id );

    $post_data = array(
        'post_title'                 => $post_title,
        'post_image'                 => $post__feat_image_url,
        'post_image_id'              => $post__feat_image_id,
        'post_image_available_sizes' => $attachment_sizes,
        'post_url'                   => get_the_permalink( $post ),
        'primary_category'           => $post__primary_category->name,
        'post_date'                  => $post->post_date,
        'post_categories'            => wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) ),
        'post_tags'                  => wp_list_pluck( get_the_tags( $post->ID ), 'name' ),
        'post_author'                => $post_author->display_name ?? '',
        'post_byline'                => $post_author->byline ?? '',
    );

    $endpoint = $_SERVER['REQUEST_URI'];

    wp_reset_postdata();

    return apply_filters( 'btw/base_api/post/post_data', $post_data, $post, $endpoint );

}


