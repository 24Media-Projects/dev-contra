<?php 


class BTW_Editor_Module_Product_Crawler extends BTW_Editor_Module{

    protected $module_label = 'Εξωτερικό περιεχόμενο';

    protected $module_name = 'product-crawler';

    private $settings = [
        'post_type' => [ "'post'" ],
    ];

    public function __construct(){

        parent::__construct();

        $this->settings = apply_filters( 'btw/admin_editor/modules/product_crawler/settings', $this->settings );

        add_action( 'wp_ajax_product_crawler__preview', [ $this, 'product_crawler__preview' ] );
        add_action( 'wp_ajax_prodcut_crawler__get_meta_tags', [ $this, 'prodcut_crawler__get_meta_tags' ] );

        add_action('save_post', [ $this, 'update_post_content' ], 30, 2 );

    }

    public function admin_editor_scripts(){

        wp_register_script( 'wp_editor_product_crawler_js', get_template_directory_uri() . '/inc/admin/editor/modules/product-crawler/assets/js/product-crawler.js', array( 'jquery' ), $this->script_version, false );
        wp_enqueue_script( 'wp_editor_product_crawler_js' );

    }



  /**
   * Product Crwaler Preview
   * Returns the shortcode as html to editor.
   * Shortcode Params: 
   * url
   * name
   * desc
   * img
   * img_credits
   * price
   * sale_price
   * has_buy_now_button
   * 
   * @return json
   */
  public function product_crawler__preview(){

    $post = get_post( (int) $_POST['post_id'] );
    $return = [];

    if( !$post ){
      $return['success'] = false;
      wp_send_json( $return );
    }

    $return['html'] = 'eee';

    $atts = $_POST['atts']['named'];
    
    array_walk( $atts, function( &$item, $key ){
      $item = "{$key}=\"{$item}\"";
    });

    ob_start();
    echo do_shortcode( '[product_crawler_sc ' . implode( ' ', $atts ) . ']' );
    $return['html'] = ob_get_contents();
    ob_get_clean();

    $return['success'] = true;

    wp_send_json_success( $return );

  }



  /**
   * Use curl to get the og meta tags of the url
   * Try to get values of: title ,site_name ,description ,price ,image
   * Image: Check if image exists, get image size
   * 
   * @return json
   */
  public function prodcut_crawler__get_meta_tags(){
    
    if(empty( $_POST['url'] ) || !wp_verify_nonce( $_POST['nonce'], 'btw-wp-editor-nonce' ) ){
      wp_send_json_error( [ 'error' => 'nonce invalidation or empty request url' ], 200 );
    }

    $url = $_POST['url'];

    $response = wp_remote_get( $url );

    if( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ){
      wp_send_json_error( [ 'error' => 'response is a wp error or response status code is not 200' ], 200 );
    }

    $response = $response['body'];

    $doc = new DOMDocument();
    @$doc->loadHTML('<?xml encoding="utf-8" ?>' . $response );
    $metas = $doc->getElementsByTagName( 'meta' );

    $og_tags = [];
    for ($i = 0; $i < $metas->length; $i++){
      $meta = $metas->item( $i );
      if( strpos( $meta->getAttribute( 'property' ), 'og:' ) !== false ){
        if( $meta->getAttribute( 'property' ) == 'og:image' ){

          $image = $meta->getAttribute( 'content' ) ? self::maybe_fix_scheme( @urldecode( $meta->getAttribute( 'content' ) ) ) : '';
          if( !$image || !$this->attachment_file_exists( $image ) ) continue;

          list( $width, $height, $type ) = @getimagesize( $image );

          $return['ogImageMeta'] = array( 'width' => $width, 'height' => $height, 'mimeType' => in_array( image_type_to_mime_type( $type ) ,get_allowed_mime_types() ) ? image_type_to_mime_type( $type ) : 'not allowed mime type' );
          $og_tags[ str_replace( 'og:', '', $meta->getAttribute( 'property' ) ) ] = $image;

        }else{
          $og_tags[ str_replace( 'og:', '', $meta->getAttribute( 'property' ) ) ] = $meta->getAttribute( 'content' );
        }
  		}
    }

    if( !$og_tags ){
      wp_send_json_error( ['error' => 'no og tags found' ], 200 );
    }

    wp_send_json_success([
      'ogTags' => $og_tags,
    ]);

  }



  /**
   * Add http protocol if missing on og:image
   * 
   * @param string, $url
   * 
   * @return string
  */
  private function maybe_fix_scheme( $url ){
    $url = ltrim( $url, '/' );
    return preg_match( '/http/i', $url ) ? $url : 'https://' . $url;
  }

  
  /**
   * Get headers of og:image to see if image actually exists and we can get it.
   * 
   * @param string, $attachment
   * 
   * @return bool
  */
  private function attachment_file_exists( $attachment ){
    $attachment_headers = get_headers( $attachment, 1 );
    return strpos( $attachment_headers['0'], '200 OK' ) !== false;
  }


  /**
   * Save remote images functionality.
   * Get image url from shortcode and try to download and save it to DB and aws s3 bucket
   * Success: replace content image url with attachment id
   * Error: print error msg. See admin notices class for more details
   * 
   * @param string, $content
   */
  public function product_crawler__save_remote_images( $post_content ){

    $user = wp_get_current_user();
    global $wpdb;

    if ( ! class_exists( 'WP_Http' ) ) {
      require_once ABSPATH . WPINC . '/class-http.php';
    }

    if( !preg_match_all( '/\[product_crawler_sc\s+[^\]]+\]/', $post_content, $product_crawler_sc ) ){
      return $post_content;
    }

    foreach( $product_crawler_sc['0'] as $product_crawler ){

      if( !preg_match( '/img="(http[^"]+)"?/i', $product_crawler, $attachment_to_upload ) ) continue;

      preg_match( '/credits="([^"]+)"?/i', $product_crawler, $attachment_credits );

      $attachment_url = $attachment_to_upload['1'];

        try{

          $http = new WP_Http();
          $response = $http->request( $attachment_url );
      
          if( is_wp_error( $response ) || 200 !== $response['response']['code'] ){

            $error_message = is_wp_error( $response ) ? $response->get_error_message() : 'code ' . $response['response']['code'];
            throw new Exception( 'Error at uploading image http request. Response : ' . $error_message );

          }

          $upload = wp_upload_bits( basename( $attachment_url ), null, $response['body'] );

          if( !empty( $upload['error'] ) ){
            throw new Exception( 'Error at uploading image http request. Response : ' . $upload['error'] );
          }

          $file = $upload['file'];

          $attachment_filename = sanitize_file_name( basename( $file ) );
          $mime_type = wp_check_filetype( basename ($file ), null );

          $attachment_args = array(
            'post_title'     => sanitize_text_field( $attachment_filename ),
            'post_excerpt'   => sanitize_text_field( $attachment_filename ),
            'post_mime_type' => $mime_type['type'],
            'post_author'    => $user->ID,
            'meta_input'     => array(
              'btw__attachment_fields__credits' => $attachment_credits['1'] ?? '',
            ),
          );

          // insert attachment to wp tables
          $attachment_id = wp_insert_attachment(
            args: $attachment_args,
            file: $file,
            wp_error: 1
          );

          if( is_wp_error( $attachment_id ) ){
            throw new Exception( 'Error at attachment insert. Error: ' . $attachment_id->get_error_message() );
          }

          // generate attachment metadata
          require_once( ABSPATH . 'wp-admin/includes/image.php' );

          $attach_data = wp_generate_attachment_metadata( $attachment_id, $file );
          wp_update_attachment_metadata( $attachment_id, $attach_data );

      }catch( Exception $e ){

        error_log( 'Product Crawler Error: ' . $e->getMessage() );
        $this->admin_notices->add( 'error', sprintf( 'Product Crwaler: Παρουσιάστηκε ένα πρόβλημα, παρακαλώ δοκιμάστε ξανά. Φωτογραφία: %s. Η φωτογραφία δεν έχει αποθηκευτεί.', $attachment ) );
        continue;

      }

      $post_content = str_replace( $attachment_url, $attachment_id, $post_content );

    }

    return $post_content;

  }

  /**
   * update post content and acf content fields using above functionality
   * Remove and add save_post action to avoid infinite loop.
   * 
   * @param int, $post_id
   * @param WP_Post, $post
   */
  public function update_post_content( $post_id, $post ){

    if( wp_is_post_revision( $post_id )
        || !current_user_can( 'edit_post', $post_id )
        || get_post_type( $post_id ) != 'post'
        || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    ){
      return;
    }

    $post_content = $post->post_content;
    $post_content = $this->product_crawler__save_remote_images( $post_content );

    /**
     * @todo move the following to child theme with filter
     */
    // if( get_field( 'btw__article_fields__template' ,$post->ID ) == 'longform' ){
    //   while( have_rows( 'btw__longform__content_sections', $post->ID ) ): the_row();

    //     if( get_row_layout() == 'text__layout' ):

    //       $acf_field = 'btw__longform__content_sections_'.( get_row_index() - 1 ).'_text__content';
    //       $acf_field_value = get_post_meta( $post->ID, $acf_field, true );
    //       $update_acf_field_value = $this->product_crawler__save_remote_images( $acf_field_value );

    //       update_post_meta( $post->ID, $acf_field, $update_acf_field_value );

    //     endif;

    //   endwhile;

    // }

    remove_action( 'save_post', [ $this, 'update_post_content' ], 30, 2 );
    /**
     * before wp 6.4 remove / add action post_updated with callback wp_save_post_revision
     * After 6.4, not needed
     */
    remove_action( 'post_updated', 'wp_save_post_revision', 10, 1 );

    wp_update_post( array( 'ID' => $post_id, 'post_content' => $post_content ), false, false );

    add_action( 'save_post', [ $this, 'update_post_content' ], 30, 2 );

    add_action( 'post_updated', 'wp_save_post_revision', 10, 1 );

  }



}


 $btw_editor_module_product_crawler = new BTW_Editor_Module_Product_Crawler();