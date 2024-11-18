<?php

function get_base_api_maybe_set_exluded_data( $post, $post_data ){

  if( get_field( 'btw__global_fields__hide_from_feed', $post->ID ) ){

    $post_data = array_keys( $post_data );
    $post_data[ 'post_title' ] = 'This post is exluded from feed';

  }

  return $post_data;
}

/**
 * Get post item basic data for rest api
 */
function get_base_api_post_data( $post ){

  $post__feat_image = btw_get_post_featured_image( 'full', $post );
  $post__primary_category = btw_get_post_primary_category( $post );
  $post_author = btw_get_post_author( $post );

  $attachment_sizes = btw_get_attachment_sizes( $post__feat_image->id );

  // raw post title
  $post_title = $post->post_title;

  $post_data = array(
    'post_title'                 => $post_title,
    'post_image'                 => $attachment_sizes['full'] ?? '',
    'post_image_id'              => $post__feat_image->id,
    'post_image_available_sizes' => $attachment_sizes,
    'post_url'                   => get_the_permalink( $post->ID ),
    'primary_category'           => $post__primary_category->name,
    'post_date'                  => $post->post_date,
    'post_categories'            => wp_get_post_categories( $post->ID, array( 'fields' => 'names' ) ),
    'post_tags'                  => wp_list_pluck( get_the_tags( $post->ID ), 'name' ),
    'post_author'                => $post_author->display_name ?? '',
    'post_byline'                => $post_author->byline ?? '',
  );

  $endpoint = $_SERVER['REQUEST_URI'];

  return apply_filters( 'btw/base_api/post/post_data', $post_data, $post, $endpoint );

}
