<?php


/**
 *  Blockquote shortcode
 * 
 * @param array, $atts
 */
add_shortcode( 'blockquote_sc', function( $atts = [], $content = null ){

	$args = shortcode_atts( array(
        'citation'      => '',
        'citation_link' => '',
	), $atts );


	if( !$content ) return '';

	$args['content'] = $content;

	ob_start();

	btw_get_template_part( 'template-parts/shortcodes/blockquote', $args );

	$return = ob_get_contents();
	ob_get_clean();

	return $return;

});