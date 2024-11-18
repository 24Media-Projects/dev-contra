<?php

/*
  Everything about ACF fields used in wp-admin
  Plugin Advanced custom fields. Pro version
*/


/*
  Add ACF option pages: Brand Settings, components-settings
  See acf_add_options_page for more details
*/

if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(array(
    'page_title'  => 'Brand Settings',
    'menu_title'  => 'BRAND SETTINGS',
    'position'    => 2,
    'icon_url'    => 'dashicons-heart',
    'menu_slug'   => 'brand-settings',
    'capability'  => 'manage_brand_settings',
    'redirect'    => false
  ));

  acf_add_options_page(array(
    'page_title'  => 'Developer Settings',
    'menu_title'  => 'DEVELOPER SETTINGS',
    'position'    => 4,
    'icon_url'    => 'dashicons-hammer',
    'menu_slug'   => 'developer-settings',
    'capability'  => 'manage_brand_settings',
    'redirect'    => false
  ));

}




/*
  Feed field | Acf field: btw__feed_fields__api_url
  Return Feed API url in edit feed page
  See acf/prepare_field hook for more details
*/


add_filter('acf/prepare_field/name=btw__feed_fields__api_url', function ($field) {

  global $post, $btw_global_settings;

  if (!get_field('btw__feed_fields__group', $post->ID) || !get_field('btw__feed_fields__customer', $post->ID)) return false;

  $customer = get_field('btw__feed_fields__customer', $post->ID);
  $customer_api_key = get_post_meta($customer->ID, 'customer_fields__api_key', true);

  if (!$customer_api_key) return false;

  $rest_api_url = get_rest_url() . 'wp/v2/' . $btw_global_settings::rest_api_prefix_base() . '-feed/' . $post->ID . '?api_key=' . $customer_api_key['api_key'];
  $xml_api_url = site_url() . '/xml/v1/feeds/' . $post->ID . '?api_key=' . $customer_api_key['api_key'];

  $google_showcase_single_story_api_url = site_url() . '/xml/v1/google_showcase/feeds/' . $post->ID . '?api_key=' . $customer_api_key['api_key'] . '&gpanel_type=single_story';
  $google_showcase_rundown_api_url = site_url() . '/xml/v1/google_showcase/feeds/' . $post->ID . '?api_key=' . $customer_api_key['api_key'];

?>

  <div class="acf-field" data-name="<?php echo $field['name']; ?>">
    <div class="acf-label">
      <label>Feed API Url</label>
    </div>
    <div class="acf-input">
      <p>
        REST API<br />
        <a target="_blank" href="<?php echo $rest_api_url; ?>"><?php echo $rest_api_url; ?></a>
      </p>
      <p>
        XML API<br />
        <a target="_blank" href="<?php echo $xml_api_url; ?>"><?php echo $xml_api_url; ?></a>
      </p>
      <p>
        Google Showcase Single Story API<br />
        <a target="_blank" href="<?php echo $google_showcase_single_story_api_url; ?>"><?php echo $google_showcase_single_story_api_url; ?></a>
      </p>
      <p>
        Google Showcase Rundwn API<br />
        <a target="_blank" href="<?php echo $google_showcase_rundown_api_url; ?>"><?php echo $google_showcase_rundown_api_url; ?></a>
      </p>
    </div>
  </div>

<?php

});

/*
  Exclude posts which are:
  exclude from feeds: btw__article_fields__exclude_channel__feeds,

  from relationship fields availiable options.
  User cannot select these posts. These filter is global for all relationship fields except btw__feed_fields__group
  See acf/fields/relationship/query hook for more details
*/



add_filter( 'acf/fields/relationship/query', function( $args, $field, $post_id ){

	if( isset( $_POST['s'] ) && trim( $_POST['s'] ) == '' ){
		
    $args['posts_per_page'] = 10;
    
	}

	$args['post_status'] = 'publish';

	$args['orderby'] = 'date';
	$args['order'] = 'desc';

	return $args;

},30,3);



/**
 * Register custom wysiwyg editors: simple, only_bold
 * You can select them on ACF field type text editor
 * 
 * @param array, $teeny_mce_buttons
 * 
 * @return array
 */

add_filter( 'acf/fields/wysiwyg/toolbars', function( $teeny_mce_buttons ){

  //to enable formatselect, add it to array
	$teeny_mce_buttons['simple']['1'] = [ 'link', 'bold', 'italic', 'superscript', 'subscript', 'pastetext', 'removeformat' ];
	$teeny_mce_buttons['only_bold']['1'] = [ 'bold', 'pastetext', 'removeformat' ];
	return $teeny_mce_buttons;

}, 99, 1 );



/*
  Customer messages about rest api endpoints

  field_5dbaa87136e6b: Posts Rest API endpoint
  field_5eeb6cc643076: Videos Rest API endpoint
  field_5dbbee5f89681: Single Post Rest API endpoint
  field_5eeb6cf043077: Single Video Rest API endpoint
  field_5dbaa8ae36e6d: Categories Rest API endpoint
  field_5dbaa8d336e6e: Tags Rest API endpoint
  field_5dbaa8fd36e70: Categories with Tags Rest API endpoint
  field_5dbbee8289682: Categories and Tags Rest API endpoint
  field_5dbacd40ba4d6: Posts Rest API endpoint Extra Parametres

*/

add_filter( 'acf/load_field/type=message', function( $field ){

  global $btw_global_settings, $post;

  $rest_api_prefix_base = $btw_global_settings::rest_api_prefix_base();
  $rest_url = get_rest_url( null, 'wp/v2/' . $rest_api_prefix_base );
  $customer_api_key = $post ? ( get_post_meta( $post->ID, 'customer_fields__api_key', true )['api_key'] ?? '<api_key>' ) : '<api_key>';

  $customer_messages_field_keys = [
    'field_5dbaa87136e6b' => "{$rest_url}-posts?api_key={$customer_api_key}",
    'field_5eeb6cc643076' => "{$rest_url}-videos?api_key={$customer_api_key}",
    'field_5dbbee5f89681' => "{$rest_url}-posts/<post_id>?api_key={$customer_api_key}",
    'field_5eeb6cf043077' => "{$rest_url}-videos/<post_id>?api_key={$customer_api_key}",
    'field_5dbaa8ae36e6d' => "Posts\n{$rest_url}-posts?api_key={$customer_api_key}&categories=<comma seperated category ids>\n\nVideos\n{$rest_url}-videos?api_key={$customer_api_key}&categories=<comma seperated category ids>",
    'field_5dbaa8d336e6e' => "Posts\n{$rest_url}-posts?api_key={$customer_api_key}&tags=<comma seperated tag ids>\n\nVideos\n{$rest_url}-videos?api_key={$customer_api_key}&tags=<comma seperated tag ids>",
    'field_5dbaa8fd36e70' => "Posts\n{$rest_url}-posts?api_key={$customer_api_key}&categories=<comma seperated category ids>&tags=<comma seperated tag ids>\n\nVideos\n{$rest_url}-videos?api_key={$customer_api_key}&categories=<comma seperated category ids>&tags=<comma seperated tag ids>",
    'field_5dbbee8289682' => "{$rest_url}-posts?api_key={$customer_api_key}&tags=<comma seperated tag ids>&categories=<comma seperated category ids>",
    'field_5dbacd40ba4d6' => "
        per_page: ( number ) 1 - 50 | default 10\n
        version: ( string ) full ( default, δεν χρειάζεται να προστεθει στο url ) - light ( χωρίς post content και longform sections )\n\n
        Παράδειγμα 1: categories with tags, 10 posts, light version\n\n
        {$rest_url}-posts?api_key={$customer_api_key}&categories=<comma seperated category ids>&tags=<comma seperated tag ids>&per_page=10&version=light\n\n\n",
  ];


  if( empty( $customer_messages_field_keys[ $field['key'] ] ) ) return $field;

  $field['message'] = $customer_messages_field_keys[ $field['key'] ];

  return $field;

});


// Add screenshots of group template layout
add_filter( 'acf/load_field/type=message', function( $field ){

  if( $field['key'] != 'field_63185b3b30861' ) return $field;

  global $post;

  $post_id = $post->ID;

  $acf_group_template_object = get_field_object( 'btw__group_fields__hp__template', $post_id ) ?: acf_get_field( 'btw__group_fields__hp__template' );
  $active_group_template = $acf_group_template_object['value'] ?? '';
  $group_templates = array_keys( $acf_group_template_object['choices'] );

  $image_path = get_stylesheet_directory_uri() . '/assets/img/groups/group_templates_screenshots/';

  $template_screenshots = array_map( function( $template ) use( $image_path, $active_group_template ){
    return ( $template == $active_group_template ? " src=\"{$image_path}{$template}.png\" " : '' ) . " data-{$template}-src=\"{$image_path}{$template}.png\" ";
  }, $group_templates );

  $field['message'] = '<img class="group_template_screenshot" ' . implode( ' ', $template_screenshots ) . ' />';

  return $field;

} );

add_action('acf/init', function() {

    // Check function exists, then include and register the custom location type class.
    if( function_exists('acf_register_location_type') ) {

        include_once( 'classes/class-acf-location-rule-group-hp-template.php' );
		    include_once( 'classes/class-acf-location-rule-group-bon-template.php' );
        include_once( 'classes/class-acf-location-rule-group-type.php' );
        
        acf_register_location_type( 'BTW_ACF_Location_Group_Hp_Template' );
		    acf_register_location_type( 'BTW_ACF_Location_Group_Bon_Template' );
        acf_register_location_type( 'BTW_ACF_Location_Group_Type' );
    }

});


add_filter( 'acf/load_field/name=btw__group_fields__hp__template', function( $field ){

    $field['choices'] = BTW_Global_Settings::get_group_hp_templates_choices() ?? [];
    $field['wrapper']['class'] = 'hp_group_template_container';

    return $field;

});

add_filter( 'acf/load_field/name=btw__group_fields__bon__template', function( $field ){

    $field['choices'] = BTW_Global_Settings::get_group_bon_templates_choices() ?? [];
    $field['wrapper']['class'] = 'bon_group_template_container';

    return $field;

});


add_filter( 'acf/load_field/name=btw__group_fields__magazine__template', function( $field ){

	$field['choices'] = BTW_Global_Settings::get_group_magazine_templates_choices() ?? [];
	$field['wrapper']['class'] = 'magazine_group_template_container';

	return $field;

});

add_filter( 'acf/load_field/name=btw__group_fields__group_type', function( $field ){

    $field['wrapper']['class'] = 'group_type_container';

    return $field;

});



// Using this hook, we can search ACF Field Groups by Group Keys and also by keys of child fields.
add_action('pre_get_posts', function($q){

	if( is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'acf-field-group' && $s = $_GET['s'] ?? '' ){

		global $wpdb;
		$sql_1 = "SELECT post_parent as ID FROM {$wpdb->posts} WHERE post_excerpt LIKE '%$s%' AND post_type = 'acf-field'";
		$results_1 = $wpdb->get_results($sql_1);

		$sql_2 = "SELECT ID FROM {$wpdb->posts} WHERE (post_title LIKE '%$s%' OR post_name = '$s') AND post_type = 'acf-field-group'";
		$results_2 = $wpdb->get_results($sql_2);

		$results = array_merge($results_2, $results_1);

		if( $results ){
			$q->query_vars['s'] = '';
			$q->set('post__in', array_unique( wp_list_pluck($results, 'ID') ) );
		}

	}

}, 1000);



//////////////////////////////////////////////////////////
/// // ACF FILTERING BY LOCATION - START
//////////////////////////////////////////////////////////

add_action('admin_head', function(){
	?>
    <style>
        .acf-admin-field-groups .tablenav.top{
            display: block;
            margin-bottom: 20px;
        }

        .acf-admin-field-groups #filter-by-date{
            display: none;
        }

        .acf-admin-field-groups select[name="location"]{
            max-width: unset;
        }

    </style>
	<?php
});

add_action('restrict_manage_posts', function( $post_type ){

	if( $post_type != 'acf-field-group' ) return;

	$selected = $_REQUEST['location'] ?? 0;

	global $wpdb;
	$arr = array();

	$acf_field_group_contents = (array)$wpdb->get_results("SELECT post_content FROM {$wpdb->posts} WHERE post_type = 'acf-field-group' AND post_content != '' AND post_status = 'publish'");

	foreach($acf_field_group_contents as $post){
		$post_content = $post->post_content;
		$post_content = unserialize($post_content );
		$location = $post_content['location'];

		foreach ($location as $outer_array){
			foreach($outer_array as $loc){
				$label = $loc['param'] . ' ' . $loc['operator'] . ' ' . $loc['value'];
				$value = $loc['param'] . ':' . $loc['operator'] . ':' . $loc['value'];

				$arr[$label] = $value;
			}
		}
	}

	asort($arr);

	?>
    <select name="location">
        <option <?php echo selected( $selected, 0 );?> value="all">All Locations</option>
		<?php foreach( $arr as $loc_label => $loc_value ): ?>

            <option <?php echo selected( $selected, $loc_value );?> value="<?php echo $loc_value;?>"><?php echo $loc_label;?></option>

		<?php endforeach; ?>
    </select>
	<?php
});

add_action('pre_get_posts', function( $query ){
	if (
		!is_admin() ||
		! $query->is_main_query() ||
		!isset( $_GET['post_type'] ) ||
		$_GET['post_type'] !== 'acf-field-group' ||
		!isset( $_REQUEST['location'] ) ||
		$_REQUEST['location'] == 'all'
	) {
		return;
	}

	$expl = explode(':', $_REQUEST['location']);

	$arr = [];
	$arr['param'] = $expl[0];
	$arr['operator'] = $expl[1];
	$arr['value'] = $expl[2];

	$serialize_string = serialize($arr);

	global $wpdb;

	$ids__result = $wpdb->get_results("SELECT ID FROM {$wpdb->posts} WHERE post_type = 'acf-field-group' AND post_content LIKE '%$serialize_string%' AND post_content != ''");

	$ids = wp_list_pluck( $ids__result, 'ID' );

	$query->set('post__in', $ids);

} );


//////////////////////////////////////////////////////////
/// // ACF FILTERING BY LOCATION - END
//////////////////////////////////////////////////////////




add_filter('acf/fields/relationship/result', function($title, $post){
	$allowed_post_types = get_supported_single_post_types();
  $post_type = get_post_type($post);
	if( !in_array( $post_type, $allowed_post_types ) ) return $title;

	$edit_item_label = get_post_type_object($post_type)->labels->edit_item;

  $title = '
    <span class="acf_relationship__post_title_value">' . $title .'</span>
    <br/>
    <a class="acf_relationship__post_edit_link" href="' . get_edit_post_link($post) . '" target="_blank">' . remove_punctuation($edit_item_label) . '</a>';
    
	return $title;
}, 20, 2);
