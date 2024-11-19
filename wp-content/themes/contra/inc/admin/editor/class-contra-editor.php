<?php 

class Contra_Admin_Editor{

    public function __construct(){

        add_action( 'admin_init', [ $this, 'wp_editor_add_editor_stylesheet' ] );

        add_filter( 'tiny_mce_before_init', [ $this, 'wp_editor_insert_formats' ], 20, 2 );

        add_action( 'after_setup_theme', [ $this, 'set_default_inline_attachment_settings' ], 99 );

    }

    
    public function set_default_inline_attachment_settings(){

      update_option( 'image_default_align', 'none' );
      update_option( 'image_default_link_type', 'none' );

    }


    public function wp_editor_insert_formats( $mceInit, $editor_id ){

        $style_formats = json_decode( $mceInit['style_formats'] ?? '' );

        $style_formats[] = array(
            'title' => 'Big Info',
            'block' => 'div',
            'classes' => 'big_info',
            'wrapper' => true,
        );

        // Insert the array, JSON ENCODED, into '$mceInit'
        $mceInit['style_formats'] = json_encode( $style_formats );

        return $mceInit;
  }


  /*
    Add editor stylesheet
  */

  public function wp_editor_add_editor_stylesheet(){

    $time = strtotime( 'now' );
    add_editor_style( get_stylesheet_directory_uri() .'/assets/css/admin/contra-admin-editor-styles.css?v=' . $time );

    }

  }

$contra_admin_editor = new Contra_Admin_Editor();
