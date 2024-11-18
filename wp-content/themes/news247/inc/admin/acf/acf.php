<?php

add_action( 'acf/render_field_general_settings/type=clone', function( $field ){

	acf_render_field_setting( $field, [
		'label'        => 'Enable Image 3:2 (replaces the Image 2:1)',
		'instructions' => 'Change Image `Image 2:1` with `Image 3:2` Name and Validation Settings.',
		'type'         => 'true_false',
		'name'         => 'enable_custom_image_settings_of_cloned_repeater',
		'ui'           => 1,
		'class'        => 'field-required',
	]);

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



	acf_render_field_setting(
		$field,
		array(
			'label'        => 'Image 3:2 - Minimum',
			'type'         => 'text',
			'name'         => 'min_width_of_cloned_repeater',
			'prepend'      => __( 'Width', 'acf' ),
			'append'       => 'px',
		)
	);

	acf_render_field_setting(
		$field,
		array(
			'label'   => '',
			'type'    => 'text',
			'name'    => 'min_height_of_cloned_repeater',
			'prepend' => __( 'Height', 'acf' ),
			'append'  => 'px',
			'_append' => 'min_width_of_cloned_repeater',
		)
	);

	acf_render_field_setting(
		$field,
		array(
			'label'   => '',
			'type'    => 'text',
			'name'    => 'min_size_of_cloned_repeater',
			'prepend' => __( 'File size', 'acf' ),
			'append'  => 'MB',
			'_append' => 'min_width_of_cloned_repeater',
		)
	);

	acf_render_field_setting(
		$field,
		array(
			'label'        => 'Image 3:2 Maximum',
			'type'         => 'text',
			'name'         => 'max_width_of_cloned_repeater',
			'prepend'      => __( 'Width', 'acf' ),
			'append'       => 'px',
		)
	);

	acf_render_field_setting(
		$field,
		array(
			'label'   => '',
			'type'    => 'text',
			'name'    => 'max_height_of_cloned_repeater',
			'prepend' => __( 'Height', 'acf' ),
			'append'  => 'px',
			'_append' => 'max_width_of_cloned_repeater',
		)
	);

	acf_render_field_setting(
		$field,
		array(
			'label'   => '',
			'type'    => 'text',
			'name'    => 'max_size_of_cloned_repeater',
			'prepend' => __( 'File size', 'acf' ),
			'append'  => 'MB',
			'_append' => 'max_width_of_cloned_repeater',
		)
	);

});


add_action( 'acf/render_field_presentation_settings/type=clone', function( $field ) {

	if( $field['type'] == 'clone' && $clone_key = $field['clone'][0] ?? 0 ){

		$cloned_repeater = get_field_object($clone_key);
		if( !$cloned_repeater ) return;

		$sub_fields = $cloned_repeater['sub_fields'];
		if( !$sub_fields ) return;

		foreach($sub_fields as $sub_field){
			acf_render_field_setting($field, [
				'label' => 'Hide ' . $sub_field['label'] . ' (' . $sub_field['type'] .') of cloned Repeater',
				'name'  => 'hide_' . $sub_field['key'] . '_of_cloned_repeater',
				'type'  => 'true_false',
				'ui'    => 1,
			]);
		}

	}

});




add_filter( 'acf/clone_field', function( $field, $clone_field ){

	$field['min'] = $clone_field['min_of_cloned_repeater'] ?? '';
    $field['max'] = $clone_field['max_of_cloned_repeater'] ?? '';


	foreach( (array)$field['sub_fields'] as $key => $sub_field ){
		if( !empty( $clone_field[ 'hide_' . $sub_field['key'] . '_of_cloned_repeater' ] ) ){
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
	if( $key !== false && $att_value = $clone_field['taxonomy_of_cloned_repeater'] ?? '' ) {
		$field['sub_fields'][$key]['taxonomy'] = $att_value;
	}

    if( !empty( $clone_field['post_type_of_cloned_repeater'] ) ){
        $field['wrapper']['data-btw_post_type'] = json_encode( $clone_field['post_type_of_cloned_repeater'] );
    }
    if( !empty( $clone_field['taxonomy_of_cloned_repeater'] ) ){
        $field['wrapper']['data-btw_taxonomy'] = json_encode( $clone_field['taxonomy_of_cloned_repeater'] );
    }


	return $field;

}, 99, 2 );


// Only for homepage
add_filter( 'acf/clone_field', function( $field, $clone_field ){

	if( strpos( $field['name'], 'btw__group_fields__hp__' ) !== 0 ) return $field;



	// Enable 3:2 Images instead of 2:1
	if( $clone_field['enable_custom_image_settings_of_cloned_repeater'] ?? '' ) {

		// atf__is_overlay
		// find position of atf__is_overlay field in $field['sub_fields']
		$pluck = wp_list_pluck((array)$field['sub_fields'], 'name');
		$key = array_search('atf__is_overlay', $pluck);
		if ($key !== false) {
			$field['sub_fields'][$key]['ui_off_text'] = '3:2';
		}


		// Image - 2:1 (Not Required)

		$pluck = wp_list_pluck((array)$field['sub_fields'], 'key');
		if ($key = array_search('field_63f879369abfe', $pluck)) {
			// if not exist this field in subfields of repeater & if isset attribute
			foreach (['min_width', 'min_height', 'min_size', 'max_width', 'max_height', 'max_size'] as $img_subkey) {
				if ($att_value = $clone_field["{$img_subkey}_of_cloned_repeater"] ?? '') {
					$field['sub_fields'][$key][$img_subkey] = $att_value;
				}
			}
		}

		$pluck = wp_list_pluck((array)$field['sub_fields'], 'key');
		$key = array_search('field_63f879369abfe', $pluck);
		if ($key !== false) {
			$field['sub_fields'][$key]['label'] = str_replace('2:1', '3:2', $field['sub_fields'][$key]['label']);
		}


		// Image - 2:1 (Required)

		$pluck = wp_list_pluck((array)$field['sub_fields'], 'key');
		if ($key = array_search('field_6410a32c4b9d9', $pluck)) {
			// if not exist this field in subfields of repeater & if isset attribute
			foreach (['min_width', 'min_height', 'min_size', 'max_width', 'max_height', 'max_size'] as $img_subkey) {
				if ($att_value = $clone_field["{$img_subkey}_of_cloned_repeater"] ?? '') {
					$field['sub_fields'][$key][$img_subkey] = $att_value;
				}
			}
		}

		$pluck = wp_list_pluck((array)$field['sub_fields'], 'key');
		$key = array_search('field_6410a32c4b9d9', $pluck);
		if ($key !== false) {
			$field['sub_fields'][$key]['label'] = str_replace('2:1', '3:2', $field['sub_fields'][$key]['label']);
		}

	}





	if( in_array($field['name'], ['btw__group_fields__hp__template__zodiac_signs__first_post_selection', 'btw__group_fields__hp__template__articles_grid__featured_post_selection']) ){

		$pluck = wp_list_pluck( (array)$field['sub_fields'], 'key' );

		//'field_63f87cd0c4f73', // Mobile Image - 1:1 // Remove the Conditional Logic
		$key = array_search( 'field_63f87cd0c4f73', $pluck );
		if( $key !== false ){
			$field['sub_fields'][$key]['conditional_logic'] = [];
		}


	}

	return $field;

}, 99, 2 );



// Only for newsletter BG COLOR
add_filter( 'acf/clone_field', function( $field, $clone_field ){

	if( $field['name'] == 'btw__newsletter_fields__bg_color_img_post_selection' ){
		$color = '#DCF0EC';
	}elseif( $field['name'] == 'btw__newsletter_fields__bg_color_post_selection' ){
		$color = '#FFF0E0';
	}else{
		return $field;
	}

	$pluck = wp_list_pluck( (array)$field['sub_fields'], 'name' );
	$key = array_search( 'atf__bg_color', $pluck );
	if( $key !== false ){
		$field['sub_fields'][$key]['default_value'] = $color;
	}

	return $field;

}, 99, 2 );



add_filter( 'acf/fields/relationship/query', function( $args, $field, $post_id ){

  if( get_post_type( $post_id ) == 'group' && $field['name'] == 'atf__post' ){

    if( !empty( $_POST['btw_post_type'] ) ){
        $args['post_type'] = $_POST['btw_post_type'];
    }

    if( !empty( $_POST['btw_taxonomy'] ) ){

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



// add_filter( 'acf/load_field_groups', function( $field_groups ){

//     global $current_screen;

//     if( $current_screen->post_type == 'group' && $current_screen->id == 'group' ){

//         global $btw_group_fields, $post; 

//         $post_type_group_field_keys = wp_list_pluck( $btw_group_fields->template_mapping, 'template_slug', 'acf_group_field_key' );

//         $group_type = get_post_meta( $post->ID, 'btw__group_fields__group_type', true );

//         $group_template = get_post_meta( $post->ID, "btw__group_fields__{$group_type}__template", true );

//         foreach( $field_groups as $index => $field_group ){

//             if( in_array( $field_group['key'], array_keys( $post_type_group_field_keys ) ) ){
               
//                 if( $group_template != $post_type_group_field_keys[ $field_group['key'] ] ){
//                     unset( $field_groups[ $index ] );
//                 }
//             }
//         }

//         // print_r($field_groups);
//     }
        
//     return $field_groups;

// },99);









add_action('init', function () {
	if( function_exists('acf_add_options_page') ) {

		acf_add_options_page([
			'page_title' => 'Videos Home Settings',
			'menu_title' => 'VIDEOS HOME',
			'position' => 2,
			'icon_url' => 'dashicons-heart',
			'menu_slug' => 'videos-home-settings',
			'capability' => 'edit_others_posts', // only authors haven't this capability
			'redirect' => false
		]);

		acf_add_options_page([
			'page_title'	=> 'Politics Page Settings',
			'menu_title'	=> 'POLITICS PAGE',
			'position'		=> 2,
			'icon_url'		=> 'dashicons-heart',
			'menu_slug'		=> 'politics-page-settings',
			'capability'	=> 'edit_others_posts', // only authors haven't this capability
			'redirect'		=> false
		]);

		acf_add_options_page([
			'page_title' => 'Podcasts Home Settings',
			'menu_title' => 'PODCASTS HOME',
			'position' => 3,
			'icon_url' => 'dashicons-heart',
			'menu_slug' => 'podcasts-home-settings',
			'capability' => 'manage_brand_settings',
			'redirect' => false
		]);

		acf_add_options_page([
			'page_title' => 'Magazine Settings',
			'menu_title' => 'MAGAZINE SETTINGS',
			'position' => 3,
			'icon_url' => 'dashicons-heart',
			'menu_slug' => 'magazine-settings',
			'capability' => 'manage_brand_settings',
			'redirect' => false
		]);

	}
});




add_action('acf/init', function() {

    // Check function exists, then include and register the custom location type class.
    if( function_exists('acf_register_location_type') ) {

		include_once( 'classes/class-acf-location-rule-group-magazine-template.php' );
    
        acf_register_location_type( 'BTW_ACF_Location_Group_Magazine_Template' );
    }

});

add_filter('acf/load_field/key=field_651bea891dfd4', function($value){

	global $post;

	if( !isset($post->post_type) ){
		$value['message'] = 'Something wrong happened. Please refresh and try again';
		return $value;
	}elseif( $post->post_type == 'acf-field-group' ){
		return $value;
	}elseif( $post->post_status != 'publish' ){
		$value['message'] = 'Δημοσιεύστε το άρθρο για να εμφανιστεί το COPY κουμπί';
		return $value;
	}

	$post_title = get_the_title($post);
	$post_permalink = get_the_permalink($post);
	$img = btw_get_post_featured_image('medium_square', $post);

	ob_start();
	?>
	<div class="commentContent related_post_content">
		<div>
			<a href="<?php echo $post_permalink; ?>" title="<?php echo $post_title; ?>" target="_blank" class=" nicEdit-related-article">
				<img src="<?php echo $img->url; ?>" alt="<?php echo $post_title; ?>">
				<h2><?php echo $post_title; ?></h2>
			</a>
			<br>
		</div>
	</div>
	<?php
	$html = ob_get_clean();

	ob_start();
	?>
	<div class="related_post_content--wrapper">

		<?php if( user_is_admin() ): ?>
			<div class="escaped">
				<?php esc_html_e($html); ?>
			</div>
			<br>
		<?php endif; ?>

		<!-- hidden by css -->
		<div class="unescaped">
			<?php echo $html; ?>
		</div>

		<div>
			<button class="button related_post_content--button">COPY</button><!-- onclick in acf-admin.js -->
		</div>

	</div>

	<?php
	$value['message'] = ob_get_clean();

	return $value;
});


add_filter( 'acf/clone_field', function( $field, $clone_field ){

	if( $clone_field['name'] != 'btw__newsletter_fields__overlay_post' ) return $field;

	$is_dark_mode_sub_field_key = array_search('atf__is_dark_mode', wp_list_pluck($field['sub_fields'], 'name'));

	if( $is_dark_mode_sub_field_key !== false ){
		$field['sub_fields'][$is_dark_mode_sub_field_key]['default_value'] = 1;
	}

	return $field;

}, 10, 2 );