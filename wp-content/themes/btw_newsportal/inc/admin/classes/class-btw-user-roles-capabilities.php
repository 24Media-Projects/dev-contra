<?php

/*
User roles and capabilites class
Contains everything about user: roles, meta, capabilites

*/

class BTW_User_Roles_Capabilities{

	private $user_meta;

	/**
	 * Filtered by WP filter btw/get_private_post_types
	 *
	 * @var string[] Default ['group', 'customer', 'feed']
	 */
	public $private_post_types = ['group', 'customer', 'feed'];

	function __construct(){

		add_action('init', [$this, 'maybe_filter_private_post_types']);

		add_action( 'init', [ $this, 'setup_extended_user_meta' ], 1 );

		add_action( 'show_user_profile', [ $this,'get_user_meta' ] );
		add_action( 'edit_user_profile', [ $this,'get_user_meta' ] );

		add_action( 'personal_options_update', [ $this,'update_user_profile_fields' ] );
		add_action('edit_user_profile_update', [ $this,'update_user_profile_fields' ] );

		add_filter( 'authenticate', [ $this,'check_user_status' ], 20 ,2 );

		add_action( 'user_register', [ $this,'add_extra_user_meta' ] );



		//
		add_action( 'init', [ $this,'add_new_user_roles' ] );
		add_action( 'admin_init', [ $this,'add_capabilities' ] );
		add_action( 'admin_menu', [ $this,'admin_menu_capabilities' ] );
		add_action( 'wp_loaded', [ $this,'remove_access_on_admin_pages' ] );
		add_action( 'admin_head', [ $this,'hide_user_meta' ] );


		add_filter('editable_roles', [ $this,'fix_editable_roles_capabilities' ] );
		add_filter('register_post_type_args', [$this, 'change_mapping_for_create_private_post_type_cap'], 100, 2);
		add_filter( 'map_meta_cap', [ $this,'edit_role_capabilities' ], 10, 4 );
		add_filter( 'map_meta_cap', [ $this,'edit_manager_capabilities' ], 10, 4 );
		add_filter('acf/prepare_field/name=btw__tag_fields__is_blog',[ $this,'remove_access_from_acf_blog_tags' ] );

		add_action('restrict_manage_users', [$this, 'add_user_statuses_filter']);
		add_action('pre_get_users', [$this, 'user_statuses_pre_get_users']);

		add_filter('manage_users_columns', [$this,'user_status_column_header']);
		add_filter('manage_users_custom_column', [$this,'user_status_column_content'], 10, 3);

		add_action('admin_menu', [$this, 'add_users_capabilities_menu']);


	}

	public function maybe_filter_private_post_types()
	{
		$this->private_post_types = apply_filters('btw/get_private_post_types', $this->private_post_types);
	}


	/* Array of new user meta
	  user_status | ( active / disabled ): If the user has access to wp-admin. Default is active
	  user_has_archive | ( true / false ): If user has archive url. Default is true for every role except subscriber and contributor
	*/
	public function setup_extended_user_meta(){

		$this->user_meta = array(
			array(
				'label' => __('User Status', 'btw_newsportal'),
				'key' => 'user_status',
				'default_value' => 'active',
				'args' => array(
					'field' => 'select',
					'options' => array(
						array(
							'label' => 'Ενεργός',
							'value' => 'active',
						),
						array(
							'label' => 'Ανενεργός',
							'value' => 'disabled',
						)
					)
				)
			),
			array(
				'label' => __('User Has Archive', 'btw_newsportal'),
				'key' => 'user_has_archive',
				'default_value' => '1',
				'default_subscriber_value' => '0',
				'args' => array(
					'field' => 'select',
					'options' => array(
						array(
							'label' => 'Έχει',
							'value' => 1,
						),
						array(
							'label' => 'Δεν έχει',
							'value' => 0,
						)
					)
				)
			),
		);

	}

	public function add_users_capabilities_menu()
	{
		add_users_page(
			__('Users Capabilities', 'btw_newsportal'), // Page title
			__('Users Capabilities', 'btw_newsportal'), // Menu title
			'edit_users',                       // Capability required
			'users_capabilities',                   // Menu slug
			[$this, 'users_capabilities_page_content']       // Callback function
		);
	}

	public static function get_site_roles()
	{
		$roles = ['author', 'editor', 'manager', 'administrator'];

		return apply_filters('btw/site_roles', $roles);

	}

	public function users_capabilities_page_content() {
		echo '<div class="wrap">';
		echo '<h1>' . __('Users Capabilities', 'btw_newsportal') . '</h1>';

		// Define the order of user roles to display
		$roles_order = $this->get_site_roles();

		// Retrieve roles and capabilities
		global $wp_roles;
		$roles = array_intersect_key($wp_roles->roles, array_flip($roles_order));

		// Define capabilities to exclude
		$excluded_capabilities = [];



		// Get all capabilities across roles and filter out the excluded ones
		$all_capabilities = [];
		foreach ($roles as $role) {
			$role_capabilities = array_keys($role['capabilities']);
			$all_capabilities = array_merge($all_capabilities, $role_capabilities);
		}
		$all_capabilities = array_unique(array_diff($all_capabilities, $excluded_capabilities));
		sort($all_capabilities);




		// Start the table
		echo '<table class="widefat fixed striped" style="max-width: '. (count($roles_order)*80+200) .'px;">';
		echo '<thead><tr><th style="width: 200px;">' . __('Capability', 'btw_newsportal') . '</th>';

		// Column headers for roles
		foreach ($roles_order as $role_name) {
			echo '<th style="text-align:center; border-left: 1px solid #D8D8D8;">' . esc_html(ucfirst($role_name)) . '</th>';
		}
		echo '</tr></thead><tbody>';

		// Table rows for each capability
		foreach ($all_capabilities as $capability) {
			echo '<tr>';
			echo '<td>' . esc_html($capability) . '</td>';

			// Check each role for this capability
			foreach ($roles_order as $role_name) {
				$role = get_role($role_name);
				echo '<td style="text-align:center; border-left: 1px solid #D8D8D8;">';
				if (!empty($role) && $role->has_cap($capability)) {
					echo '✅'; // Display a tick ✔ for capabilities the role has
				}
				echo '</td>';
			}
			echo '</tr>';
		}

		echo '</tbody></table>';
		echo '</div>';
	}

	/**
	 * Keep only capabilities in plural.
	 * edit_post, read_post, delete_post are only meta capabilities!
	 * AND SHOULD not to grant the meta capabilities directly to users or roles
	 *
	 *
	 *
	 * OFFICIAL CUSTOM POST TYPE DOCUMENTATION
	 * @see https://learn.wordpress.org/tutorial/custom-post-types-and-capabilities/
	 *
	 * There are three types of capabilities available to custom post types.
	 * Meta capabilities are capabilities that are mapped to primitive capabilities.
	 * The three meta capabilities are edit_post, read_post, and delete_post.
	 * As an example, the edit_post meta capability is mapped to primitive capabilities like edit_posts and edit_others_posts.
	 * Because the meta capabilities are automatically mapped to certain primitive capabilities,
	 * it’s generally recommended not to grant the meta capabilities directly to users or roles,
	 * and rather to grant any of the primitive capabilities.
	 *
	 *
	 * @param $post_type
	 * @return string[]
	 */
	public static function get_all_post_type_capabilities($post_type)
	{
		return[
			"read_private_{$post_type}s",
			"edit_{$post_type}s",
			"publish_{$post_type}s",
			"create_{$post_type}s",
			"edit_{$post_type}s",
			"edit_others_{$post_type}s",
			"edit_published_{$post_type}s",
			"edit_private_{$post_type}s",
			"delete_{$post_type}s",
			"delete_others_{$post_type}s",
			"delete_published_{$post_type}s",
			"delete_private_{$post_type}s",
			"moderate_{$post_type}_comments",
		];
	}

	/*
	  Get the new user meta in user profile page
	  Only visible to Administrator and Manager
	  user: WP_USER object
	  See show_user_profile, edit_user_profile actions for more details
	*/

	public function get_user_meta( $user ) {

		if( !user_min_cap_manager() ) return; ?>

        <table class="form-table">
            <tr>
                <th>
                    <h2><?php _e('Extra User Meta', 'btw_newsportal');?></h2>
                </th>
            </tr>
			<?php foreach( $this->user_meta as $meta ):?>

                <tr>
                    <th>
                        <label for="<?php echo $meta['key']; ?>"><?php echo $meta['label']; ?></label>
                    </th>
                    <td>
						<?php if( $meta['args']['field'] == 'select' ){ ?>

                            <select name="<?php echo $meta['key']; ?>" id="<?php echo $meta['key']; ?>">

								<?php foreach( $meta['args']['options'] as $option ): ?>
                                    <option value="<?php echo $option['value']; ?>" <?php echo selected( get_user_meta( $user->ID, $meta['key'], true) ,$option['value'] );?>><?php echo $option['label']; ?></option>
								<?php endforeach;?>

                            </select>

						<?php } ?>
                    </td>
                </tr>
			<?php endforeach;?>
        </table>

	<?php }

	/*
	  Upate the new user metas on user profile update
	  Only Administrator and Manager can do this
	  See edit_user_profile_update action for more details
	*/

	public function update_user_profile_fields( $user_id ){

		if( !user_min_cap_manager() ) return;

		foreach( $this->user_meta as $user_meta ):
			update_user_meta( $user_id, $user_meta['key'], sanitize_text_field( $_POST[$user_meta['key'] ] ) );
		endforeach;

	}


	/*
	  If user is disabled prevent login access in wp-admin
	  See authenticate hook for more details
	*/

	public function check_user_status( $user, $username ){
		if( $user instanceof WP_User ){

			if( get_user_meta( $user->ID, 'user_status', true ) == 'disabled' ){
				return new WP_Error( 'user_account', 'Δεν έχετε δικαίωμα σύνδεσης.' );
			}

		}

		return $user;
	}

	/*
	  Add new user meta after user registration, depending on default values of the user metas
	  See user_register action for more details
	*/

	public function add_extra_user_meta( $user_id ){
		$user = new WP_User( $user_id );
		foreach( $this->user_meta as $meta ){

			if( ( in_array( 'contributor', $user->roles ) !== false || in_array( 'subscriber', $user->roles ) !== false ) && isset( $meta['default_subscriber_value'] ) ){
				add_user_meta( $user_id, $meta['key'], $meta['default_subscriber_value'], true );

			}else{
				add_user_meta( $user_id, $meta['key'], $meta['default_value'], true );

			}

		}

	}


	/*
	  Add new user role of Manager
	  extend manager capabilities: edit_theme_options
	  See add_role function for more details
	*/

	public function add_new_user_roles(){

		if( !get_role('manager' ) ){

			$manager_capabilities = [];
			$editor_capabilities = get_role('editor')->capabilities;

			foreach( array_keys( $editor_capabilities ) as $cap ){
				$manager_capabilities[ $cap ] = true;
			}

			$manager_capabilities['edit_theme_options'] = true;

			add_role( 'manager', 'Manager', $manager_capabilities );

		}

	}

	/*
	  Add capabilites for post types: group, customer, feed
	  Administrator and Manager: all caps on these post types
	  Editor, Author: read, edit caps on post type group

	  Add 2 new caps manage_brand_settings, manage_regenerate_thumbnails on Administrator and Manager
	  Extend manager capabilities: users
	  Editor, Author: Remove caps edit_page, edit_pages
	  Editor: remove cap manage_categories

	  See get_role, add_cap ( propetry of WP_Role ) for more details

	*/



	public function add_capabilities(){

		if( get_option( 'btw_user_caps_completed', false ) ) return true;

		remove_role( 'wpseo_editor' );
		remove_role( 'wpseo_manager' );

		$manager_role = get_role( 'manager' );

		$manager_role->add_cap( 'list_users' );
		$manager_role->add_cap( 'create_users' );
		$manager_role->add_cap( 'edit_users' );
		$manager_role->add_cap( 'promote_users' );

		$custom_post_types_full_access_roles = array( 'administrator', 'manager' );

		foreach( $custom_post_types_full_access_roles as $role ){

			$role = get_role( $role );

			$role->add_cap('wpseo_manage_options');
			$role->add_cap('wpseo_bulk_edit');
			$role->add_cap('wpseo_edit_advanced_metadata');
			$role->add_cap('manage_brand_settings');
			$role->add_cap('manage_regenerate_thumbnails');
			$role->add_cap('manage_scopes');

			// Post because other get_supported_single_post_types, inherit capabilities from post
			$role->add_cap( 'read_private_posts' );
			$role->add_cap( 'edit_private_posts' );
			$role->add_cap( 'delete_private_posts' );

			foreach( $this->private_post_types as $post_type ){

				foreach( $this->get_all_post_type_capabilities($post_type) as $cap ){
					$role->add_cap( $cap );
				}

			}

		}


		$custom_post_types_read_edit_access_roles = array( 'editor', 'author' );


		foreach( $custom_post_types_read_edit_access_roles as $role )
		{
			$role = get_role( $role );

			$role->add_cap( 'read_private_groups' );
			$role->add_cap( 'edit_groups' );
			$role->add_cap( 'edit_others_groups' );
			$role->add_cap( 'edit_published_groups' );
			$role->add_cap( 'edit_private_groups' );

			$role->remove_cap( 'edit_pages' );
			$role->remove_cap( 'edit_published_pages' );
			$role->remove_cap( 'publish_pages' );

			$role->remove_cap( 'read_private_posts');
			$role->remove_cap( 'delete_private_posts' );
		}

		$role = get_role( 'editor' );
		$role->remove_cap( 'manage_categories' );

		$role->add_cap( 'read_private_groups' );
		$role->add_cap( 'edit_groups' );
		$role->add_cap( 'edit_others_groups' );
		$role->add_cap( 'edit_published_groups' );
		$role->add_cap( 'edit_private_groups' );


		add_option( 'btw_user_caps_completed', true );

	}



	/*
	  Remove themes and customizer from admin menu if user role is not Administrator
	  See admin_menu action for more details
	*/

	public function admin_menu_capabilities(){
		global $submenu;
		//maybe use global $pagenow instead

		if( empty( $submenu[ 'themes.php' ] ) || user_is_admin() ) return;

		foreach ( $submenu[ 'themes.php' ] as $index => $menu_item ) {
			if( strpos( $menu_item['2'],'customize' ) !== false || strpos( $menu_item['2'], 'themes' )  !== false ) {
				unset( $submenu[ 'themes.php' ][ $index ] );
			}
		}
	}


	/*
	  Remove access on themes and customizer from admin menu if user role is not Administrator
	*/

	public function remove_access_on_admin_pages(){
		if( !is_admin() ) return;

		$user = wp_get_current_user();
		if( user_is_admin() ) return;

		$current_screen = explode( '?', basename( $_SERVER['REQUEST_URI' ] ) );
		//maybe use global $pagenow instead

		if( in_array( $current_screen['0'], array( 'themes.php', 'customize.php' ) ) !== false ){
			wp_die( 'You need permission to access this page' );
		}
	}

	/*
	  Hide user meta:
	  nickname ( used for autor url ),
	  description,
	  profile picture ( not in use ),
	  job description,
	  avatar

	  if user role is not Administrator or Manager
	*/

	public function hide_user_meta(){

		if( !defined( 'IS_PROFILE_PAGE' ) ) return;

		if( user_min_cap_manager() ) return; ?>

        <style type="text/css">
            .user-nickname-wrap,
            .user-description-wrap,
            .user-profile-picture,
            .acf-field[data-name="btw__user_fields__job_description"],
            .acf-field[data-name="btw__user_fields__avatar"]{
                display: none !important;
            }
        </style>

        <script type="text/javascript">
            var userFields = document.querySelector( '.user-nickname-wrap, .user-description-wrap, .user-profile-picture, .acf-field[data-name="btw__user_fields__job_description"],.acf-field[data-name="btw__user_fields__avatar"]' );
            if( groupSort ) userFields.remove();
        </script>

	<?php }


	/*
	  Remove capability for manager to edit an administrator
	  See editable_roles hook for more details
	*/

	public function fix_editable_roles_capabilities( $roles ){

		if( user_is_admin() ) return $roles;

		unset( $roles['administrator'] );
		return $roles;
	}

	/**
	 * Because in wp-includes/post.php: "Post creation capability simply maps to edit_posts by default"
	 *
	 * @param $args
	 * @param $post_type
	 * @return array $args
	 */
	public function change_mapping_for_create_private_post_type_cap($args, $post_type)
	{
		if( !in_array($post_type, $this->private_post_types, true) ) return $args;

		// Modify the capabilities as needed for these post types
		$args['capabilities']['create_posts'] = "create_{$post_type}s";  // Explicitly set create_posts capability
		return $args;
	}

	/**
	 * Add capability to all users that have edit_posts capability to
	 * - add new tag from post
	 * - view all existing tags from tags page
	 * - view all existing categories from categories page
	 *
	 * BUT CANNOT EDIT THEM
	 */
	public function edit_role_capabilities($caps, $cap, $user_id, $args)
	{
		if( in_array($cap, ['manage_categories', 'manage_post_tags']) ){
			$caps = ['edit_posts'];
		}
		return $caps;
	}

	/*
	  Add capability to manager edit and promote user, if user is not administrator
	  Remove capability for manager to edit privacy policy
	  See map_meta_cap hook for more details
	*/


	public function edit_manager_capabilities($caps, $cap, $user_id, $args){

		$current_user =  wp_get_current_user();
		if( !user_is_manager( $current_user ) ) return $caps;

		if( $cap == 'edit_user' ){

			$user_linsting_id = $args['0'];
			$user_linsting = new WP_User( $user_linsting_id );

			if( in_array('administrator',$user_linsting->roles) !== false && in_array( 'administrator', $current_user->roles ) === false ){
				$caps[] = 'do_not_allow';
			}
		}

		if( $cap == 'promote_user' ){
			$user_linsting_id = $args['0'];
			$user_linsting = new WP_User($user_linsting_id);

			if( in_array( 'administrator', $user_linsting->roles ) !== false && in_array( 'administrator', $current_user->roles ) === false ){
				$caps[] = 'do_not_allow';
			}
		}

		if($cap == 'edit_post'){
			$post = get_post($args[0]);
			if( $post && (int) get_option( 'wp_page_for_privacy_policy' ) === $post->ID ){
				$caps = array_diff( $caps, [ 'manage_options' ] );
			}
		}


		return $caps;
	}

	/*
	  Only allow administrator, manager to manage which tags are blog
	  See acf/prepare_field hook for more details
	*/

	public function remove_access_from_acf_blog_tags( $field ){
		return user_min_cap_manager() ? $field : false;
	}

	public function add_user_statuses_filter( $which ){

		if( $which == 'bottom' ) return;


		$top = $_GET['user_status_top'] ?? 'all';
		//$bottom = $_GET['user_status_bottom'] ?? 'all';

		if( $top != 'all' ){
			$selected = $top;
			//}elseif( $bottom != 'all' ){
			//	$selected = $bottom;
		}else{
			$selected = 'all';
		}

		$user_statuses = array(
			'all'     => 'All User Statuses',
			'active'  => 'Active Users',
			'disabled' => 'Inactive Users',
		);

		echo '<select name="user_status_' . $which . '" style="float:none;margin-left:10px;">';
		foreach ($user_statuses as $value => $label) {
			echo '<option value="' . $value . '" ' . selected($selected, $value, false) . '>' . $label . '</option>';
		}
		echo '</select>';

		submit_button(__( 'Filter' ), 'secondary', $which, false);
	}

	public function user_statuses_pre_get_users( $query ){
		global $pagenow;
		if (is_admin() && 'users.php' == $pagenow) {

			$top = $_GET['user_status_top'] ?? 'all';
			//$bottom = $_GET['user_status_bottom'] ?? 'all';

			if( $top != 'all' ){
				$value = $top;
				//}elseif( $bottom != 'all' ){
				//	$value = $bottom;
			}else{
				return;
			}

			if( $value == 'disabled' ){

				$meta_query = array (
					array (
						'key' => 'user_status',
						'value' => 'disabled',
						'compare' => '=',
					)
				);

			}else{

				$meta_query = array (
					'relation' => 'OR',
					array (
						'key' => 'user_status',
						'value' => 'disabled',
						'compare' => '!=',
					),
					array (
						'key' => 'user_status',
						'compare' => 'NOT EXISTS',
					),
				);

			}

			$query->set('meta_query', $meta_query);

		}
	}

	public function user_status_column_header($columns) {
		$columns['user_status'] = 'User Status';

		foreach($columns as $column_key => $column_name){
			$new_columns[$column_key] = $column_name;
			if( $column_key == 'role' ){ // place only after role
				$new_columns['user_status'] = 'User Status';
			}
		}

		return $new_columns;
	}
	public function user_status_column_content($value, $column_name, $user_id) {
		if ($column_name == 'user_status') {
			$user_meta = get_user_meta($user_id, 'user_status', true) ?: 'active';
			$names = [
				'active'   => '<b style="font-weight: bold;">Active User</b>',
				'disabled' => 'Inactive User',
			];
			return $names[$user_meta];
		}
		return $value;
	}



}


$btw_user_roles_capabilities = new BTW_User_Roles_Capabilities();