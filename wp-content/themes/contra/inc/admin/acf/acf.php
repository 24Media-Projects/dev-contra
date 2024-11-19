<?php

add_filter( 'acf/fields/relationship/query', function( $args, $field, $post_id ){

	$s = trim($_POST['s'] ?? '' );

	if( !$s ) return $args;

	$temp_s = trim($s, '#');

	if( $s == $temp_s ) return $args;

	if( is_numeric( $temp_s ) ){
		$args['p'] = $temp_s;
		if( !empty( $args['s'] ) ){
			unset($args['s']);
		}
	}

	return $args;

}, 100, 3);


add_filter( 'acf/update_value/name=btw__video_fields__embed_code', 'btw_cleanup_embed_code_input' );
add_filter( 'acf/update_value/name=btw__embed_code_fields__code', 'btw_cleanup_embed_code_input' );

function btw_cleanup_embed_code_input( $value ){

	// remove style / script
	$value = preg_replace( '/<(script|style).*?>.*?<\/\1>/ms', '', $value );

	if( strpos( $value, 'glomex' ) !== false ){
		// remove div container
		$value = preg_replace( '/<(\/?)div.*?>/ms', '', $value );

		// remove meta tags
		$value = preg_replace( '/<meta[^>]*?\/>/ms', '', $value );

	}

	return trim( $value );
}
