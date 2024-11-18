<?php 

function btw_xml_api_format_post_content( $post_content ){

    global $allowedposttags;

    $rss_allowed_tags = [
        'b', 'i','em', 'strong' ,'sub', 'sup', 'small', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'a', 'img', 'table',
        'th', 'td', 'tr', 'thead', 'tbody', 'tfoot', 'col', 'caption', 'colgroup', 'ul', 'ol', 'li',
        'span', 'div', 'p', 'br', 'blockquote'
    ];

    $final_rss_allowed_post_tags = array_filter( $allowedposttags, function( $tag, $key ) use( $rss_allowed_tags ){
        return in_array( $key, $rss_allowed_tags );
    }, ARRAY_FILTER_USE_BOTH );

    return wp_kses( $post_content, $final_rss_allowed_post_tags );
}