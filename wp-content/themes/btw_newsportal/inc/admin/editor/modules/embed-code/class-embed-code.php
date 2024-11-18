<?php 


class BTW_Editor_Module_Embed_Code extends BTW_Editor_Module{

    protected $module_label = 'Embed Code';

    protected $module_name = 'embed-code';

    private $settings = [
        'post_type' => [ "'post'" ],
    ];

    public function __construct(){

        parent::__construct();

        $this->settings = apply_filters( 'btw/admin_editor/modules/embed_code/settings', $this->settings );

        add_action( 'wp_ajax_embed_code__preview', [ $this, 'embed_code__preview' ] );

    }

    public function admin_editor_scripts(){

        wp_register_script( 'wp_editor_embed_code_js', get_template_directory_uri() . '/inc/admin/editor/modules/embed-code/assets/js/embed-code.js', array( 'jquery' ), $this->script_version, false );
        wp_enqueue_script('wp_editor_embed_code_js' );

    }

    /*
    Module: Emded Code
    Preview
    Returns the shortcode as html to editor.
    Shortcode Params: provider
    See do_shortcode,
        assets/js/admin/wp-editor-embed-code.js
        https://codex.wordpress.org/Shortcode_API

    for more details
  */
  public function embed_code__preview(){

    $post = get_post( (int) $_POST['post_id'] );
    $return = [];

    if( !$post ){
      $return['success'] = false;
      wp_send_json( $return );
    }

    $return['html'] = '';

    $content = $_POST['content'];
    $atts = $_POST['atts']['named'];


    ob_start();
    echo do_shortcode( "[embed_code_sc provider=\"{$atts['provider']}\" is_admin=\"true\"]{$content}[/embed_code_sc]" );

    $return['html'] = ob_get_contents();
    ob_get_clean();

    $return['success'] = true;

    wp_send_json_success( $return );

  }





}


 $btw_editor_module_embed_code = new BTW_Editor_Module_Embed_Code();