<?php

// Add custom fields on wp options pages

class Btw_Add_Settings_Field {

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'admin_init' , array( $this , 'register_fields' ) );
	}

	/**
	 * Add new fields to wp-admin/options-reading.php page
	 */
	public function register_fields() {

		register_setting( 'reading', 'videos_per_page', 'esc_attr' );

		add_settings_field(
			'videos_per_page',
			'<label for="videos_per_page">Videos Per Page</label>',
			array( $this, 'fields_html' ),
			'reading'
		);
	}

	/**
	 * HTML for extra settings
	 */
	public function fields_html() {
		$value = get_option( 'videos_per_page' ) ?: get_option( 'posts_per_page' );
		echo '<input type="text" id="videos_per_page" name="videos_per_page" value="' . esc_attr( $value ) . '" />';
	}

}

$btw_add_settings_field = new Btw_Add_Settings_Field();


?>
