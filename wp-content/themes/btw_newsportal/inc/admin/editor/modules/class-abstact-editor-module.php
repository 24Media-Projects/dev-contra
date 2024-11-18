<?php 


abstract class BTW_Editor_Module{

  protected $admin_notices;

  protected $script_version;

  protected $module_label;

  protected $module_name;

    public function __construct(  ){

        $this->admin_notices = new BTW_ADMIN_NOTICES();
        $this->script_version = strtotime( 'now' );

        add_action( 'admin_enqueue_scripts', [ $this,'admin_editor_scripts' ] );

        add_action( 'media_buttons', [ $this, 'wp_editor_shortcodes__insert_button' ], 11 );
        add_filter( 'wp_fullscreen_buttons', [ $this, 'wp_editor_shortcodes__insert_button_fs' ], 11 );

        add_action( 'admin_footer-edit.php', [ $this, 'modal_html' ] );
        add_action( 'admin_footer-post.php', [ $this, 'modal_html' ] );
        add_action( 'admin_footer-post-new.php', [ $this, 'modal_html' ] );

        add_action('admin_footer-edit.php', [ $this, 'render_editor_template' ] );
        add_action('admin_footer-post.php', [ $this, 'render_editor_template' ] );
        add_action('admin_footer-post-new.php', [ $this, 'render_editor_template' ] );
        
        // add_action( 'wp_ajax_embed_code__preview', [ $this, 'embed_code__preview' ] );

        // add_action( 'save_post', [ $this, 'update_post_content' ], 20, 2 );

    }

    /**
     * @param WP_Post, $post
     */
    protected function is_post_type_supported( $post ){
        return !empty( $post->post_type ) && in_array( $post->post_type, apply_filters( 'btw/admin_editor/modules/supported_post_types', [ 'post' ] ) );
    }

    /**
     * Register component script / styles
     */
    abstract public function admin_editor_scripts();

    
    /**
     * Add insert button, in editor
     * @param int $editor_id
     * 
     */
    public function wp_editor_shortcodes__insert_button( $editor_id ){
		    global $post;
   
        if( !$this->is_post_type_supported( $post ) ){
            return;
        }
        ?>

        <a title="<?php echo $this->module_label;?>"
           class="button <?php echo $this->module_name;?>-insert-button"
           href="#<?php echo $this->module_name;?>-modal">

            <?php echo $this->module_label;?>
        </a>

<?php

    }


    /**
     * Add insert button, in full screen editor
     * @param array $buttons
     * 
     * return $array
    */
    public function wp_editor_shortcodes__insert_button_fs( $buttons ){

      global $post;

      if( !$this->is_post_type_supported( $post ) ){
        return;
      }

      $buttons[ $this->module_name ] = array(
        "title" => 'Προσθήκη "'. $this->module_label . '"',
        "both"  => true
      );

      return $buttons;

    }



    /**
    * Incude modal html to page
    */
    public function modal_html(){

      global $post;

      if( !$this->is_post_type_supported( $post ) ){
          return;
      }

      get_template_part( "inc/admin/editor/modules/{$this->module_name}/{$this->module_name}_modal" );

    }

    /**
    * Register modules templates
    * @see backbone.js for more details
    */
    public function render_editor_template(){

      global $post;

      if( !$this->is_post_type_supported( $post ) ){
          return;
      }
    ?>

    <script type="text/html" id="tmpl-editor-<?php echo $this->module_name;?>">
      <# if ( data.html ) { #>
        {{{ data.html }}}
        <# } else { #>
          <div class="wpview-error">
            <div class="dashicons"></div><p style="font-size: 13px;"><?php _e( 'Invalid.', 'btw' ); ?></p>
          </div>
          <# } #>
    </script>

  <?php

  }



}







