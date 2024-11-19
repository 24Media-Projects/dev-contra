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

add_filter( 'acf/prepare_field/name=btw__feed_fields__api_url', function( $field ){

  global $post;

  if( !get_field( 'btw__feed_fields__group', $post->ID ) || !get_field( 'btw__feed_fields__customer', $post->ID ) ) return false;

  $customer = get_field( 'btw__feed_fields__customer', $post->ID );
  $customer_api_key = get_post_meta( $customer->ID, 'customer_fields__api_key', true );

  if( !$customer_api_key ) return false;

  $rest_api_url = get_rest_url() . 'wp/v2/' . btw_get_global_setting('rest_api_prefix_base') . '-feed/' . $post->ID . '?api_key=' . $customer_api_key['api_key'];
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
 * Register custom wysiwyg editors: simple, only_bold, only_text
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
	$teeny_mce_buttons['only_text']['1'] = [ 'pastetext', 'removeformat' ];
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

  global $post;

  $rest_api_prefix_base = btw_get_global_setting('rest_api_prefix_base');
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
    'field_5dbbee8289682' => "{$rest_url}-posts?api_key={$customer_api_key}&terms=<comma seperated category or/and tag ids>",
    'field_5dbacd40ba4d6' => "
        per_page: ( number ) 1 - 50 | default 10\n
        version: ( string ) full ( default, δεν χρειάζεται να προστεθει στο url ) - light ( χωρίς post content και longform sections )\n\n
        Παράδειγμα 1: categories with tags, 10 posts, light version\n\n
        {$rest_url}-posts?api_key={$customer_api_key}&categories=<comma seperated category ids>&tags=<comma seperated tag ids>&per_page=10&version=light\n\n\n
        Παράδειγμα 2: categories or tags, 15 posts, full version\n\n
        {$rest_url}-posts?api_key={$customer_api_key}&terms=<comma seperated category and/or tag ids>&per_page=15",
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

	btw_get_template( "inc/admin/acf/classes/class-acf-location-rule-group-type" );

	acf_register_location_type( 'BTW_ACF_Location_Group_Type' );

	foreach(btw_get_global_setting('group_types') as $group_type_key => $group_type_label){

		btw_get_template( "inc/admin/acf/classes/class-acf-location-rule-group-$group_type_key-template" );

        $new_location_name = 'BTW_ACF_Location_Group_' . ucfirst($group_type_key) . '_Template';

		if( !class_exists( $new_location_name ) ){
			continue;
		}

        acf_register_location_type( $new_location_name );

        add_filter( "acf/load_field/name=btw__group_fields__{$group_type_key}__template", function( $field ) use($group_type_key){
            $field['choices'] = btw_get_global_setting("group_{$group_type_key}_templates_choices") ?: [];
            $field['wrapper']['class'] = "{$group_type_key}_group_template_container";

            return $field;
        });
    }

});


add_filter( 'acf/load_field/name=btw__group_fields__group_type', function( $field ){

    $field['choices'] = btw_get_global_setting('group_types');
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



//////////////////////////////////////////////////////////
/// // ACF ATF_POSTS - START
/// // 1) post_type_of_cloned_repeater
/// // 2) taxonomy_of_cloned_repeater
/// // 3) min_of_cloned_repeater
/// // 4) max_of_cloned_repeater
/// // 5) hide_sub_field_of_cloned_repeater
/// // 6) class_of_cloned_repeater
/// // 7) load taxonomies via AJAX
//////////////////////////////////////////////////////////


add_action( 'acf/render_field_general_settings/type=clone', function( $field ){

	acf_render_field_setting( $field, [
		'label'        => 'Post Type of cloned Repeater',
		'name'         => 'post_type_of_cloned_repeater',
		'type'         => 'select',
		'choices'      => acf_get_pretty_post_types(),
		'multiple'     => 1,
		'ui'           => 1,
		'allow_null'   => 1,
		'placeholder'  => __( 'All post types', 'acf' ),
		'instructions' => "Passes the value to attribute `Filter by Post Type` of cloned repeater's subfield `atf__post`.",
	]);

	acf_render_field_setting( $field, [
		'label'        => 'Taxonomy of cloned Repeater',
		'name'         => 'taxonomy_of_cloned_repeater',
		'type'         => 'select',
		'choices'      => acf_get_taxonomy_terms(),
		'multiple'     => 1,
		'ui'           => 1,
		'allow_null'   => 1,
		'placeholder'  => __( 'All taxonomies', 'acf' ),
		'instructions' => "Passes the value to attribute `Filter Taxonomy` of cloned repeater's subfield `atf__post`.",
	]);

});

add_action( 'acf/render_field_validation_settings/type=clone', function( $field ){

	acf_render_field_setting( $field, [
		'label'			=> 'Min of cloned Repeater',
		'name'			=> 'min_of_cloned_repeater',
		'type'			=> 'number',
		'instructions'	=> 'This field passes the min value to the cloned repeater.',
	]);

	acf_render_field_setting( $field, [
		'label'			=> 'Max of cloned Repeater',
		'name'			=> 'max_of_cloned_repeater',
		'type'			=> 'number',
		'instructions'	=> 'This field passes the max value to the cloned repeater.',
	]);

});



add_action( 'acf/render_field_presentation_settings/type=clone', function( $field ) {

	if( $field['type'] == 'clone' && $clone_key = $field['clone'][0] ?? 0 ){

		$cloned_repeater = get_field_object($clone_key);
		if( !$cloned_repeater ) return;

		$sub_fields = $cloned_repeater['sub_fields'];
		if( !$sub_fields ) return;

		foreach($sub_fields as $sub_field){

			$required = $sub_field['required'] ? '*' : '';
			$setting_label = "Hide {$sub_field['label']}$required ({$sub_field['type']}) of cloned Repeater";

			acf_render_field_setting($field, [
				'label' => $setting_label,
				'name'  => 'hide_' . $sub_field['key'] . '_of_cloned_repeater',
				'type'  => 'true_false',
				'ui'    => 1,
			]);

		}

	}

	acf_render_field_setting($field, [
		'label' => 'Class',
		'name'  => 'class_of_cloned_repeater',
		'type'  => 'text',
	]);

});


$all_acf_field_types = ['text', 'textarea', 'number', 'range', 'email', 'url', 'image', 'file', 'wysiwyg',
    'select', 'checkbox', 'radio', 'button_group', 'true_false',
	'link', 'post_object', 'page_link', 'relationship', 'taxonomy', 'user',
    'date_picker', 'date_time_picker', 'time_picker', 'color_picker',
	'tab', 'accordion', 'group', 'repeater', 'flexible_content', 'clone'];

foreach($all_acf_field_types as $acf_field_type){
	add_action( 'acf/render_field_general_settings/type=' . $acf_field_type, 'acf_print_hide_everywhere_in_general_settings');
}

function acf_print_hide_everywhere_in_general_settings( $field ) {

	$cloned_repeater_key = $field['parent_repeater'] ?? '';
	if( !$cloned_repeater_key ) return;


	global $post; // $post is the group
	if( !$post ) return;

	if( $post->post_status != 'acf-disabled' ) return;


	$sub_field = $field;


	$required = $sub_field['required'] ? '*' : '';
	$setting_label = "Hide {$sub_field['label']}$required ({$sub_field['type']}) of cloned Repeater";

	$msg = "EVERYWHERE $setting_label! Proceed";
	$http_query = build_query([
		'btw_action'				=> 'acf_hide_everywhere_subfield_of_cloned_repeater',
		'key_of_cloned_repeater'	=> $cloned_repeater_key,
		'subfield_key'				=> $sub_field['key'],
		'msg'						=> "{$msg}ed!",
	]);
	$url = site_url( "wp-admin/?$http_query" );
	$onclick = "var res = confirm('$msg?'); if (res) window.open('$url', '_blank')";
	?>
    <div class="acf-field acf-field-setting-append">
        <a href="#!" onclick="<?php echo $onclick; ?>" class="button">
            HIDE EVERYWHERE SUBFIELD OF CLONED REPEATER
        </a>
    </div>
	<?php

}

add_action('admin_init', function(){
	$btw_action = $_GET['btw_action'] ?? '';
	if( $btw_action != 'acf_hide_everywhere_subfield_of_cloned_repeater' ) return;

	if( !user_is_admin() ) wp_die('Only admins can do this action');

	$key_of_cloned_repeater = $_GET['key_of_cloned_repeater'] ?? '';
	if( !$key_of_cloned_repeater ) return;

	$subfield_key = $_GET['subfield_key'] ?? '';
	if( !$subfield_key ) return;


	$search = 's:5:"clone";a:1:{i:0;' . serialize($key_of_cloned_repeater) . '}';

	global $wpdb;

	$query = "SELECT * FROM $wpdb->posts WHERE post_content LIKE %s";
	$placeholder = '%' . $wpdb->esc_like( $search ) . '%';

	$results = $wpdb->get_results( $wpdb->prepare($query, $placeholder) );
	if( !$results ) wp_die('No cloned repeaters found.', 'acf_hide_everywhere_subfield_of_cloned_repeater', ['response' => 200]);


	foreach($results as $result){
		$post_id = $result->ID;

		$post_content = maybe_unserialize($result->post_content);
		$post_content[ 'hide_' . $subfield_key . '_of_cloned_repeater' ] = 1;
		$post_content = maybe_serialize($post_content);

		$query = "UPDATE $wpdb->posts SET post_content = %s WHERE ID = %d";
		$wpdb->query( $wpdb->prepare($query, $post_content, $post_id) );
	}

	wp_die($_GET['msg'] ?? 'DONE', 'acf_hide_everywhere_subfield_of_cloned_repeater', ['response' => 200]);
});



/**
 * Pass values to cloned repeater
 * - class_of_cloned_repeater
 * - min_of_cloned_repeater
 * - max_of_cloned_repeater
 * - hide_sub_field_of_cloned_repeater
 * - btw_post_type
 * - btw_taxonomy
 */
add_filter( 'acf/clone_field', function( $field, $clone_field ){

	if( !empty($clone_field['class_of_cloned_repeater']) ){
		$field['wrapper']['class'] .= ' ' . $clone_field['class_of_cloned_repeater'];
	}

	if( isset( $clone_field['min_of_cloned_repeater'] ) && is_int( $clone_field['min_of_cloned_repeater'] ) ){
		$field['min'] = $clone_field['min_of_cloned_repeater'];
	}

	if( isset( $clone_field['max_of_cloned_repeater'] ) && is_int( $clone_field['max_of_cloned_repeater'] ) ){
		$field['max'] = $clone_field['max_of_cloned_repeater'];
	}


	foreach( (array)$field['sub_fields'] as $key => $sub_field ){
		if( !empty( $clone_field[ 'hide_' . ( $sub_field['_clone'] ?? $sub_field['key'] ) . '_of_cloned_repeater' ] ) ){
			unset( $field['sub_fields'][$key] );
		}
	}



	// post_type && taxonomy

	// find position of atf__posts field in $field['sub_fields']
	$pluck = wp_list_pluck( (array)$field['sub_fields'], 'name' );
	$key = array_search( 'atf__post', $pluck );

	// if not exist this field in subfields of repeater & if isset attribute
	if( $key !== false && $att_value = $clone_field['post_type_of_cloned_repeater'] ?? '' ) {
		$field['sub_fields'][$key]['post_type'] = $att_value;
	}

	if( $key !== false ){
		// in case of taxonomy_of_cloned_repeater contain nothing, load a fake term, because all others are loaded by select2 ajax
		$clone_field['taxonomy_of_cloned_repeater'] = $clone_field['taxonomy_of_cloned_repeater'] ?: ['nothing' => 'nothing'];
		$field['sub_fields'][$key]['taxonomy'] = $clone_field['taxonomy_of_cloned_repeater'];
	}

	if( !empty( $clone_field['post_type_of_cloned_repeater'] ) ){
		$field['wrapper']['data-btw_post_type'] = json_encode( $clone_field['post_type_of_cloned_repeater'] );
	}
	if( !empty( $clone_field['taxonomy_of_cloned_repeater'] ) ){
		$field['wrapper']['data-btw_taxonomy'] = json_encode( $clone_field['taxonomy_of_cloned_repeater'] );
	}


	return $field;

}, 99, 2 );

// btw_post_type & btw_taxonomy
add_filter( 'acf/fields/relationship/query', function( $args, $field, $post_id ){

	if( $field['name'] == 'atf__post' ){

		if( !empty( $_POST['btw_post_type'] ) ){
			$args['post_type'] = $_POST['btw_post_type'];
		}

		if( !empty( $_POST['btw_taxonomy'] ) && !isset( $_POST['btw_taxonomy']['nothing'] ) ){

			$terms = acf_decode_taxonomy_terms( $_POST['btw_taxonomy'] );

			// append to $args
			$args['tax_query'] = array(
				'relation' => 'OR',
			);

			// now create the tax queries
			foreach ( $terms as $k => $v ) {

				$args['tax_query'][] = array(
					'taxonomy' => $k,
					'field'    => 'slug',
					'terms'    => $v,
				);

			}

		}


	}

	return $args;

}, 40, 3 );




// In atf_posts, load taxonomies via AJAX
add_action('wp_ajax_search_filter_taxonomy', function(){

	$my_terms = [];

	if( $search = $_REQUEST['search'] ?? 0 ){

		$queried_taxonomies = ['category', 'post_tag']; // this may can be retrieved by btw_get_global_setting() function

		$terms = get_terms([
			'taxonomy' => $queried_taxonomies,
			'name__like' => $search,
			'number' => 10,
			'fields' => 'all', // or slugs
		]);

	}else{ // if there is no search string, return only all categories

        $args = [
			'taxonomy' => ['category'],
			'fields' => 'all', // or slugs
        ];
        $args = apply_filters('btw/group/terms_dropdown/get_all_categories/args', $args);

		$queried_taxonomies = (array)$args['taxonomy'];

		$terms = get_terms($args);

	}

	foreach( $terms as $term ){
		$my_term = [
			'id'   => $term->taxonomy . ':' . $term->slug,
			'text' => $term->name
		];
		$my_term = apply_filters('btw/group/terms_dropdown/choice', $my_term, $term);
		$my_terms[$term->taxonomy][] = $my_term;
	}



	foreach( array_intersect($queried_taxonomies, array_keys($my_terms)) as $taxonomy ){
		$return_results[] = [
			'text' => __( ucfirst(str_replace('_', ' ', $taxonomy)) ), // or we can add in $queried_taxonomies variable logic key => text
			'children' => $my_terms[$taxonomy],
		];
	}

	$array['results'] = $return_results;


	wp_send_json($array);

});



//////////////////////////////////////////////////////////
/// // ACF ATF_POSTS - END
//////////////////////////////////////////////////////////

/**
 * Prevent saving empty ACF (Advanced Custom Fields) meta values.
 *
 * When returning null, the meta is ONLY saved if it has a value.
 * It also deletes the meta if it previously had a value and now does not.
 * When a field that contains no value is requested via the get_field() function, it returns null.
 *
 * In the case of an ACF field with type group, it's OK. For example, if we have subfield_1 and subfield_2 with values 0 and 1 respectively:
 * $var = get_field('something'); // $var['subfield_1'] is an empty string.
 */
add_filter('acf/update_value', function ($value, $post_id, $field) {

	/**
	 * Allowed Post types are only these with heavy content such as posts.
	 */
	$allowed_post_types = get_supported_single_post_types();
	if( !in_array( get_post_type($post_id), $allowed_post_types) ) return $value;


	/**
	 * Allowed field types, that functionality doesn't fail.
     * For example, group is not included
	 */
	$allowed_field_types = ['text', 'textarea', 'number', 'range', 'email', 'url', 'password', // Basic
		'image', 'file', 'wysiwyg', 'gallery', // Content
		'select', 'checkbox', 'radio', 'true_false', // Choice
		'link', 'post_object', 'page_link', 'relationship', 'taxonomy', 'user', // Relational
		'date_picker', 'date_time_picker', 'time_picker', 'color_picker', // Advanced
		'repeater', 'flexible_content', // Layout
	];
	if( !in_array( $field['type'], $allowed_field_types) ) return $value;


	if( $value ) return $value; // if not empty, return


	// in case of true_false field we don't want to save the false value, this is because returned value is numeric => 0
	if( $field['type'] == 'true_false' ){
		return null;

	// in all other cases, we want 0 (numeric) to be saved
	}elseif( !is_numeric($value) ){
		return null;
	}

	return $value;
}, 10, 3);



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
