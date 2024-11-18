<?php 


class BTW_Editor_Module_Blockquote extends BTW_Editor_Module{

    protected $module_label = 'Blockquote';

    protected $module_name = 'blockquote';

    private $settings = [
    ];

    public function __construct(){

        parent::__construct();

        $this->settings = apply_filters( 'btw/admin_editor/modules/blockquote/settings', $this->settings );

        add_action( 'wp_ajax_blockquote__preview', [ $this, 'blockquote__preview' ] );

    }

    public function admin_editor_scripts(){

        wp_register_script( 'wp_editor_blockquote_js', get_stylesheet_directory_uri() . '/inc/admin/editor/modules/blockquote/assets/js/blockquote.js', array( 'jquery' ), $this->script_version, false );
        wp_enqueue_script( 'wp_editor_blockquote_js' );

    }



  /**
   * 
   * @return json
   */
  public function blockquote__preview(){

    $post = get_post( (int) $_POST['post_id'] );
    $return = [];

    if( !$post ){
      $return['success'] = false;
      wp_send_json( $return );
    }

    $atts = $_POST['atts']['named'];
    $content = $_POST['content'];

    array_walk( $atts, function( &$item, $key ){
      $item = "{$key}=\"{$item}\"";
    });

    ob_start();
    echo do_shortcode("[blockquote_sc]{$content}[/blockquote_sc]");
    $return['html'] = ob_get_contents();
    ob_get_clean();

    $return['success'] = true;

    wp_send_json_success( $return );

  }








}


$btw_editor_module_blockquote = new BTW_Editor_Module_Blockquote();