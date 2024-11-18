<?php
/*
  Rest API Keys class
  Handles customer custom meta boxes for api key
  Generates api key with bin2hex, random_bytes
  api key length can be edited. Default: 24 ( $token_length )
  See:
      add_meta_boxes action
      save_post action

      for more details

*/



class BTW_REST_API_KEYS {

  private $token_length = 24;
  private $post_type = 'customer';
  private $admin_notices;
  
  public $customer_meta_boxes = array(
    array(
      'meta_key' => 'customer_fields__api_key',
      'meta_title' =>  'API KEY',
      'context' => 'advanced',
      'args' => array(
        'meta_value_key' => 'api_key',
      )
    ),
  );



  public function __construct(){

    $this->admin_notices = new BTW_ADMIN_NOTICES();

    add_action( 'add_meta_boxes', [ $this,'customer__add_meta_boxes' ] );
    add_action( 'wp_ajax_generate_api_key', [ $this,'generate_api_key' ] );
    add_action( 'save_post', [ $this,'save_api_key' ] );

  }



  public function customer__add_meta_boxes(){

    foreach( $this->customer_meta_boxes as $box ){
      add_meta_box(
         $box['meta_key'],
         $box['meta_title'],
         array( $this, 'customer__render_meta_boxes' ),
         'customer',
         $box['context'],
         'default',
         $box['args'] ?? []
     );
    }


  }

  public function customer__render_meta_boxes( $post, $box ){

      $meta_value = get_post_meta( $post->ID, $box['id'], true );
      $meta_value = empty( $box['args']['meta_value_key'] ) ? $meta_value : $meta_value[ $box['args']['meta_value_key'] ];

    ?>

      <div class="customer_meta_box_container">
        <div class="customer_meta_box__content">


            <div class="customer_meta_box__content--item">
              <input type="text" name="<?php echo $box['id'];?>" class="<?php echo $box['id'];?>" value="<?php echo $meta_value;?>" />

              <?php if( $box['id'] == 'customer_fields__api_key' ): ?>

                <button name="customer_fields__generate_api_key_button" class="customer_fields__generate_api_key_button">Generate API KEY</button>
                <div class="customer_fields__api-key-error-container"></div>

              <?php endif; ?>

            </div>


        </div>
      </div>

<?php }







  public function generate_api_key(){
    if( !wp_doing_ajax() ) return bin2hex( random_bytes( $this->token_length ) );

    $return = [];
    if( empty($_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'btw-rest-nonce' ) ){
      $return['success'] = false;
      return wp_send_json( $return );
    }

    $return['api_key'] = bin2hex( random_bytes( $this->token_length ) );
    $return['success'] = true;

    return wp_send_json( $return );

  }


  public function save_api_key( $post_id ){
    if( wp_is_post_revision( $post_id ) || !current_user_can( 'edit_post', $post_id ) || get_post_type( $post_id ) != 'customer' ) return;

    $api_key = !empty( $_POST['customer_fields__api_key'] ) ? $_POST['customer_fields__api_key'] : self::generate_api_key();
    $api_key_data = array(
      'api_key' => $api_key,
      'hashed_api_key' => wp_hash( $api_key, 'secure_auth' ),
    );

    if( !add_post_meta( $post_id, 'customer_fields__api_key', $api_key_data, true ) ){
      update_post_meta( $post_id, 'customer_fields__api_key', $api_key_data );
    }
  }
}

$btw_rest_api_keys = new BTW_REST_API_KEYS();




?>
