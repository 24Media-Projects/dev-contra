<?php

function btw_get_template_part( $template, $args = array() ){

  extract( $args );

  if( file_exists( get_stylesheet_directory() . '/templates/' . $template . '.php' ) ){
    include get_stylesheet_directory() . '/templates/' . $template . '.php';

  }elseif( file_exists( get_template_directory() . '/templates/' . $template . '.php' ) ){
    include get_template_directory() . '/templates/' . $template . '.php';
  }
}

function btw_get_template( $template, $args = array() ){

  extract( $args );

  if( file_exists( get_stylesheet_directory() . '/' . $template . '.php' ) ){
    include get_stylesheet_directory() . '/' . $template . '.php';

  }elseif( file_exists( get_template_directory() . '/' . $template . '.php' ) ){
    include get_template_directory() . '/' . $template . '.php';
  }

}


function btw_return_template_part( $template, $args = array() ){

  if( file_exists( get_stylesheet_directory() . '/templates/' . $template.'.php' ) ){
    ob_start();
    btw_get_template_part( $template, $args );
    return ob_get_clean();

  }elseif( file_exists( get_template_directory() . '/templates/' . $template.'.php' ) ){
	  ob_start();
	  btw_get_template_part( $template, $args );
	  return ob_get_clean();
  }

  return '';

}

function btw_return_template( $template, $args = array() ){

  if( file_exists( get_stylesheet_directory() . '/' . $template . '.php' ) ){
    ob_start();
    btw_get_template( $template, $args );
    return ob_get_clean();

  }elseif( file_exists( get_template_directory() . '/' . $template . '.php' ) ){
	  ob_start();
	  btw_get_template( $template, $args );
	  return ob_get_clean();
  }

  return '';
}


function btw_get_template_js( $template ){

  if( file_exists( get_stylesheet_directory() . '/assets/js/' . $template . '.js' ) ){
    include get_stylesheet_directory() . '/assets/js/' . $template . '.js';

  }elseif( file_exists( get_template_directory() . '/assets/js/' . $template . '.js' ) ){
    include get_template_directory() . '/assets/js/' . $template . '.js';
  }
}

function btw_get_template_css( $template, $args = array() ){

  if( file_exists( get_stylesheet_directory() . '/assets/css/' . $template . '.css' ) ){
    include get_stylesheet_directory() . '/assets/css/' . $template . '.css';

  }elseif( file_exists( get_template_directory() . '/assets/css/' . $template . '.css' ) ){
    include get_template_directory() . '/assets/css/' . $template . '.css';
  }
}

function btw_return_template_css( $template, $args = array() ){

  if( file_exists( get_stylesheet_directory() . '/assets/css/' . $template . '.css' ) ){
    ob_start();
    btw_get_template_css( $template, $args );
    $stylesheet = ob_get_contents();

    if( !empty( $args['replace'] ) && !empty( $args['replace_with'] ) ){
      $stylesheet = str_replace( $args['replace'], $args['replace_with'], $stylesheet );
    }

    ob_end_clean();

    return $stylesheet;

  }elseif( file_exists( get_template_directory() . '/assets/css/' . $template . '.css' ) ){
	  ob_start();
	  btw_get_template_css( $template, $args );
    $stylesheet = ob_get_contents();

    if( !empty( $args['replace'] ) && !empty( $args['replace_with'] ) ){
      $stylesheet = str_replace( $args['replace'], $args['replace_with'], $stylesheet );
    }

    ob_end_clean();

	  return $stylesheet;
	}
}

function btw_get_iconfont_icon( $icon, $color = 'black' ){

  if( !$icon ) return;

?>

  <svg class="icon_font color_<?php echo $color;?>">
    <use class="<?php echo $icon;?>" xlink:href="<?php echo get_stylesheet_directory_uri();?>/assets/fonts/iconfont.svg#<?php echo $icon;?>"></use>
  </svg>

<?php }


function btw_return_iconfont_icon( $icon, $color = 'white' ){

  if( !$icon ) return;

  ob_start();
  btw_get_iconfont_icon( $icon, $color );

  return ob_get_clean();

}

function btw_get_template_svg( $svg ){
  if( file_exists( get_stylesheet_directory() . '/assets/img/icons/' . $svg . '.svg' ) ){
    include get_stylesheet_directory() . '/assets/img/icons/' . $svg . '.svg';
  }
}


function btw_get_file( $file ){

  if( file_exists( $file ) ){
    include $file;
  }

}

function btw_return_file( $file ){

  if( file_exists( $file ) ){

    ob_start();
    btw_get_file( $file );
    return ob_get_clean();
  }

}

function btw_get_file_from_attachment( $attachment ){

  $filepath = get_attached_file( $attachment['ID'] );

  return btw_get_file( $filepath );

}

function btw_return_file_from_attachment( $attachment ){

  $filepath = get_attached_file( $attachment['ID'] );

  return btw_return_file( $filepath );

}




// function is_user_active($user){
//   return !is_fe_user($user) && is_user_member_of_blog($user->ID, get_current_blog_id()) ? true : ( get_user_meta($user->ID,'user_status',true) == 'active' ? true : false );
// }


function user_is_manager( $user = null ){

  if (!$user) $user = wp_get_current_user();

  return $user->exists() && isset( $user->roles ) && is_array( $user->roles ) && in_array( 'manager', $user->roles );

}

function user_is_admin($user = null){

  if (!$user) $user = wp_get_current_user();

  return $user->exists() && isset( $user->roles ) && is_array( $user->roles ) && in_array( 'administrator', $user->roles );

}

function user_min_cap_manager( $user = null ){
  return user_is_manager( $user ) || user_is_admin( $user );
}


/* Check if is amp endpoint
  If plugin amp is not active, return false
*/
function btw_is_amp_endpoint(){

  if( btw_is_xml_api_request() ){
    return false;
  }

	return function_exists('amp_is_request') && amp_is_request();
}



/*
  Checks if is rest api request
*/

function is_rest_api_request() {
  if( empty( $_SERVER['REQUEST_URI'] ) ) return false;

	$rest_prefix = trailingslashit( rest_get_url_prefix() );
	$is_rest_api_request = strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) !== false;

	return $is_rest_api_request;
}


function btw_is_xml_api_request() {

	global $wp_query;

	return !empty( $wp_query->query['xml_api_post_type'] );
}



if( !function_exists( 'btw_is_longform' ) ){
  function btw_is_longform(){

    return is_page_template( 'single-longform.php' );

  }
}


function btw_get_page_by_template( $template ){

  $page = get_posts(array(
    'post_type' => 'page',
    'posts_per_page' => 1,
    'post_status' => 'publish',
    'meta_key' => '_wp_page_template',
    'meta_value' => $template
  ));

  return $page['0'] ?? null;

}