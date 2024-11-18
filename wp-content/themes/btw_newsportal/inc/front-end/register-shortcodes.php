<?php
/*
  Register shortcodes
  Currently in use:
                    read_also_sc
                    product_crawler_sc
                    embed_code_sc
  See:
      add_shortcode function
      https://codex.wordpress.org/Shortcode_API
      inc/front-end/template-functions.php btw_get_template_part theme function
      for more details

*/


/*
  Read Also shortcode
  Shortcode params: posts
  See:
      add_shortcode function
      https://codex.wordpress.org/Shortcode_API
      inc/front-end/template-functions.php btw_get_template_part theme function
      for more details
*/
add_shortcode( 'read_also_sc', function( $atts ){
	$args = shortcode_atts( array(
		'posts' => '',
	), $atts );

	if( empty( $args['posts'] ) ) return '';

	$args['posts'] = explode( ',', $args['posts'] );

	ob_start();
	btw_get_template_part( 'template-parts/shortcodes/read_also', $args );
	$return = ob_get_contents();
	ob_get_clean();

	return $return;

});

/*
  Product Crwaler shortcode
  Shortcode Params: url
                    name
                    desc
                    img
                    img_credits
                    price
                    sale_price
                    has_buy_now_button
  See:
      add_shortcode function
      https://codex.wordpress.org/Shortcode_API
      inc/front-end/template-functions.php btw_get_template_part theme function
      for more details
*/
add_shortcode( 'product_crawler_sc', function( $atts ){
	$args = shortcode_atts( array(
		'url' => '',
		'name' => '',
		'desc' => '',
		'img' => '',
		'credits' => '',
		'price' => '',
		'sale_price' => '',
		'shop_name' => '',
    'buylink' => '',
		'has_buy_now_button' => '',
		'disable_link' => '',
	), $atts );

	if( empty($args['url'] ) ) return '';

	ob_start();
	btw_get_template_part( 'template-parts/shortcodes/product_crawler', $args );
	$return = ob_get_contents();
	ob_get_clean();

	return $return;

});

/*
  Embed Code shortcode
  Shortcode params: provider
  See:
      add_shortcode function
      https://codex.wordpress.org/Shortcode_API
			inc/front-end/template-functions.php btw_get_template_part theme function
			 																		 btw_get_template theme function
      for more details
*/

add_shortcode( 'embed_code_sc', function( $atts = [], $content = null ){
	$args = shortcode_atts( array(
		'provider' => '',
		'is_admin' => false,
		'width' => '',
		'height' => '',
	), $atts );


	if( !$content ) return '';

	$args['embed_code'] = $content;
	ob_start();

	if( !btw_is_amp_endpoint() ){
		btw_get_template_part( 'template-parts/shortcodes/embed_code', $args );
	}else{
		btw_get_template( 'amp-templates/shortcodes/embed_code', $args );
	}

	$return = ob_get_contents();
	ob_get_clean();

	return $return;

});








 ?>
