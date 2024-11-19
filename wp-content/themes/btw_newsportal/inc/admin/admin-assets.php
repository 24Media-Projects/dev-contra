<?php
/*
Admin assets
*/


/*
  Check if select2 is loaded so its not loaded twice.
  ACF is using select2
*/
function load_select2(){
  global $wp_scripts;
  foreach( $wp_scripts->queue as $handle ):
    if( $handle == 'select2' ) return false;

  endforeach;

  return true;
}


/*
  Add admin stylesheet
  select2: include select2 stylesheet
  admin_styles: admin main stylesheet
  See admin_enqueue_scripts action for more details
*/
add_action( 'admin_enqueue_scripts', function(){

	$time = strtotime( 'now' );

	wp_register_style( 'btw_select2', get_template_directory_uri() . '/assets/css/admin/select2.min.css', '', '4.0.10' );
	wp_register_style( 'admin_styles', get_template_directory_uri() . '/assets/css/admin/admin-styles.css', '', $time );

	if( load_select2() ) wp_enqueue_script( 'btw_select2' );

	wp_enqueue_style( 'admin_styles' );
});


/*
  Add admin js scripts
  admin_scripts_js: general scripts for admin
  rest_api_keys_js: generate api key for post type customer
  See admin_enqueue_scripts action for more details
*/

add_action('admin_enqueue_scripts',function( $hook_suffix ){

	global $current_screen;

	$time = strtotime( 'now' );
	$deps = !load_select2() ? array( 'jquery', 'select2' ) : array( 'jquery' );

	if( in_array($hook_suffix, ['profile.php', 'user-edit.php', 'user-new.php']) ){
		wp_enqueue_editor();
	}

	wp_register_script( 'btw_select2', get_template_directory_uri() . '/assets/js/admin/select2.full.min.js', array( 'jquery' ), '4.0.10', false );
	wp_register_script( 'btw_admin_scripts_js', get_template_directory_uri() . '/assets/js/admin/admin-scripts.js', $deps, $time, true );
	wp_register_script( 'btw_rest_api_keys_js', get_template_directory_uri() . '/assets/js/admin/wp-rest-api-keys.js', array( 'jquery' ), $time, true );
	wp_register_script( 'btw_acf_admin_js', get_template_directory_uri() . '/assets/js/admin/acf-admin.js', $deps, $time, true );

	if( load_select2() ) wp_enqueue_script( 'btw_select2' );

	/*
	  Add strings we want to get in js
	  See wp_localize_script function for more details
	*/


	wp_localize_script( 'btw_admin_scripts_js', 'BTW',
		apply_filters( 'btw/localize_script_data',
			array(
				'ajaxUrl' 					=> admin_url( 'admin-ajax.php' ),
				'ajaxErrorMsg' 				=> 'Υπήρξε κάποιο πρόβλημα. Παρακαλώ δοκιμάστε αργότερα, ή επικοινωνήστε με τον διαχειριστή.',
				'wpEditorNonce' 		 	=> wp_create_nonce( 'btw-wp-editor-nonce' ),
				'selectProductImageTitle' 	=> 'Διάλεξε την φωτογραφία για το προιόν',
				'editorPluginsInlineADIcon' => get_template_directory_uri() . '/assets/img/icons/icon-ad.png',
				'restNonce' 				=> wp_create_nonce( 'btw-rest-nonce '),
				'read_also'					=> array(
					'maxSelected' => 1,
				),
				'btwGroupTypes'             => json_encode( btw_get_global_setting('group_types') ),
			),
			'BTW' )
	);

	wp_enqueue_script( 'btw_rest_api_keys_js' );
	wp_enqueue_script( 'btw_admin_scripts_js' );
	wp_enqueue_script( 'btw_acf_admin_js' );

}, 30 );



/*

  Hide attachment description field for attachment. Only use caption
  Hide caption field when embeding attachment to post content. This provides custom caption only for this instance. We use the caption field of the attachment.

*/

add_action( 'admin_head', function(){ ?>

	<style type="text/css">

		.setting[data-setting="description"],
		label.attachment-content-description,
		#wp-attachment_content-wrap,
		.embed-media-settings span.setting.caption{
			display: none !important;
		}

		</style>



<?php });







/*
  Allow only administrator and manager to create new post group
*/
add_action( 'init', function(){
	global $wp_post_types;

	if( !isset($wp_post_types['group']) ) return;

	if( !is_admin() || user_min_cap_manager() ) return;

	$wp_post_types['group']->cap->create_posts = 'do_not_allow';

});



/*
  Post type: Group
  Allow only administrator and manager to edit:
  group type,  ( btw__group_fields__group_type )
  gorup template  ( btw__group_fields__hp__template )

  Hide btw__group_fields__group_type, btw__group_fields__hp__template acf fields if user is not administrator or manager
  And remove them from dom.

  Post type: Post
  Allow only administrator and manager to add category from post.
  Hide wp category-add-toggle, category-add wp fields
  And remove them from dom.
*/

add_action('admin_head',function(){
	global $current_screen;
	if( !$current_screen ) return;

	if( !current_user_can('create_groups') && $current_screen->base == 'post' && $current_screen->id == 'group' ){ ?>

        <style type="text/css">
            .edit-post-status,
            #pageparentdiv{
                display: none !important;
            }
            div[data-name^="btw__group_fields__"][data-name$="__template"],
            div[data-name="btw__group_fields__group_type"]{
                pointer-events: none !important;
            }
        </style>

        <script type="text/javascript">
            var groupSort = document.getElementById('pageparentdiv');
            if(groupSort) groupSort.remove();

            var editPostStatus = document.querySelector('.edit-post-status');
            if(editPostStatus) editPostStatus.remove();


        </script>

	<?php }elseif( !user_min_cap_manager( wp_get_current_user() ) && $current_screen->base == 'post' && $current_screen->id == 'post' ){ ?>

        <style type="text/css">

            #category-add-toggle,
            #category-add,
            .embed-code__field--provider option[value="dev_provider"]{
                display: none !important;
            }



        </style>

        <script type="text/javascript">
            var addCategoryWrap = document.getElementById('category-add-toggle');
            var addCategoryInner = document.getElementById('category-add');

            if(addCategoryWrap) addCategoryWrap.remove();
            if(addCategoryInner) addCategoryInner.remove();
        </script>


	<?php }

});


/*
  Change default WP logo in wp login page with Brand logo
*/
add_action( 'login_enqueue_scripts', function() {

  $brand_logo = get_brand_logo();
  $login_styles = btw_return_template_css( 'admin/login-styles', array(
    'replace' => '<brand_logo_url>',
    'replace_with' => $brand_logo['url']
  ));

?>

  <style type="text/css">
    <?php echo $login_styles; ?>
  </style>

 <?php

});


