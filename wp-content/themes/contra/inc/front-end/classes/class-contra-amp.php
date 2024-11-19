<?php


class Contra_AMP{

  const POST_AMP_STATUS_POST_META_KEY = 'amp_status';

  public $amp_template_dir = 'amp-templates';
  public $glomex_amp_player = 'https://player.glomex.com/integration/1.268.2';

  public $amp_scripts = array(
    'amp-analytics',
    'amp-sidebar',
    'amp-social-share',
    'amp-carousel',
    'amp-lightbox',
    'amp-geo'
  );

  public $amp_supported_embed_providers = array(
    'youtube'     => 'amp-youtube',
    'vimeo'       => 'amp-vimeo',
    'facebook'    => 'amp-facebook',
    'instagram'   => 'amp-instagram',
    'twitter'     => 'amp-twitter',
    'reddit'      => 'amp-reddit',
    'sound-cloud' => 'amp-soundcloud',
    'pinterest'   => 'amp-pinterest',
    'dailymotion' => 'amp-dailymotion',
    'playbuzz'    => 'amp-playbuzz',
  );

  public $providers_update_embed_code_html = array(
    'reddit',
    'glomex',
    'pinterest',
    'playbuzz',
    'tumblr',
    'apester-media',
    'tiktok',
  );

  public $exluded_embed_providers = array(
    'typeform',
    'promo-simple',
  );

  public $post_display_ads;

  public function __construct(){

    add_theme_support( 'amp', array( 'template_dir' => $this->amp_template_dir ) );
    add_action( 'wp',[ $this,'init' ] );
    add_action( 'save_post', [ $this,'btw_update_post_has_amp_version' ], 60, 2 );

    add_filter( 'the_content', [ $this, 'pdf_embeder_to_link' ], 5 );
  }



  protected function get_supported_post_types(){
    $amp_settings = get_option('amp-options');

	return (array)$amp_settings['supported_post_types'] ?? [];

  }


    protected function get_unsupported_post_templates(){
        return [
            'single-magazine-embed-code.php',
            'single-magazine_embed_code_full_width.php',
            'single-magazine_embed_code_canvas.php',
            'single-liveblog.php',
            'single-embed-code.php',
            'single-embed_code_full_width.php',
            'single-embed_code_canvas.php',
        ];
  }


  public function init(){

    if( !btw_is_amp_endpoint() ) return;

    $this->post_display_ads = self::post_maybe_display_ads();
    if($this->post_display_ads){
      $this->amp_scripts[] = 'amp-ad';
    }

    $embed_amp_scripts = self::amp_embeds();
    foreach( $embed_amp_scripts as $embed_script ){
      $this->amp_scripts[] = $embed_script;
    }

    add_filter( 'amp_parsed_css_transient_caching_allowed', [ $this, 'disable_amp_css_transient' ] );
    add_action( 'wp_enqueue_scripts', [ $this, 'amp_assets' ] );
    add_filter( 'script_loader_tag',  [ $this, 'amp_scripts_attrs' ], 10, 2 );
    add_action( 'btw_amp_styles', [ $this, 'amp_styles' ] );
    add_action( 'btw_amp_analytics', [ $this, 'amp_analytics' ] );
    add_action( 'btw_amp_sharing_tools', [ $this, 'amp_sharing_tools' ] );

    add_filter( 'acf/load_value/name=btw__global_fields__lead', [ $this, 'remove_paragraphs' ] );
    add_filter( 'acf/format_value/name=btw__global_fields__lead', [ $this, 'remove_paragraphs' ] );
    add_filter( 'get_the_excerpt', [ $this, 'remove_paragraphs' ] );

  }

  public function disable_amp_css_transient(){
    return false;
  }


  private function post_maybe_display_ads(){
    global $post;
    return !get_field( 'btw__global_fields__display_options', $post->ID );
  }


  public function amp_assets(){
    foreach( $this->amp_scripts as $script ){
      wp_register_script( $script, 'https://cdn.ampproject.org/v0/' . $script.'-0.1.js', array( 'amp-runtime' ), '', false );
      wp_enqueue_script( $script );
    }
  }


  public function amp_scripts_attrs( $tag, $handle ){
    if(in_array( $handle, $this->amp_scripts ) ){
      return str_replace( ' src', ' async="async" custom-element="'.$handle.'" src', $tag );
    }

    return $tag;
  }


  public function amp_styles(){

    $stylesheet = btw_is_magazine() ? 'single_magazine' : 'single_post';

    $css = @file_get_contents( get_stylesheet_directory() . "/assets/css/amp/{$stylesheet}.css", FILE_USE_INCLUDE_PATH );
    
  	echo $css ? "<style amp-custom>{$css}</style>" : '';
  }



  public function amp_header(){
    locate_template( 'amp-templates/amp-header.php', true );
  }

  public function amp_footer(){
    locate_template( 'amp-templates/amp-footer.php', true );
  }



  public function amp_analytics(){ ?>

    <amp-analytics type="gtag" data-credentials="include">
      <script type="application/json">
        {
          "vars": {
            "config": {
              "G-9WVZ2GEYCN": { "groups": "default" },
              "G-F59N0W0PFX": { "groups": "default" }
            }
          },
          "triggers": {
            "trackPageview": {
              "on": "visible",
              "request": "pageview"
            }
          }
        }
      </script>
    </amp-analytics>    

  <?php }

    /**
     * @todo add filter
     */
  public function amp_sharing_tools(){

    global $post;

    $facebook_app_id = get_field( 'btw__brand_fields__facebook_app_id', 'option' );
    $post_url = get_permalink($post->ID);
    $post_title = esc_attr( get_the_title( $post->ID ) );

    $sharing_providers = apply_filters(
      'btw/sharing_tools/providers',
      array(
        'facebook'  => 'FACEBOOK',
        'twitter'   => 'TWITTER',
        'messenger' => 'MESSENGER',
        'whatsapp'  => 'WHATSAPP',
        'linkedin'  => 'LINKEDIN',
        'email'     => 'EMAIL',
      )
    );

  ?>

      <div class="amp_sharing_tools">
        <amp-social-share type="facebook" data-param-app_id="<?php echo $facebook_app_id;?>" width="38" height="38" aria-label="Share on Facebook">
            <?php echo $sharing_providers['facebook'];?>
        </amp-social-share>

        <amp-social-share type="twitter" data-param-url="<?php echo $post_url;?>" aria-label="Share on Twitter" 
          data-param-text="<?php echo $post_title;?>" width="38" height="38">
            <?php echo $sharing_providers['twitter'];?>
        </amp-social-share>

        <amp-social-share type="facebookmessenger" data-share-endpoint="fb-messenger://share"
          data-param-app_id="<?php echo $facebook_app_id;?>"
          data-param-link="<?php echo $post_url;?>"
          data-param-redirect_uri="<?php echo $post_url;?>"
          width="38" height="38" aria-label="Share on Messenger">
           <?php echo $sharing_providers['messenger'];?>
        </amp-social-share>

        <amp-social-share type="linkedin" data-param-url="<?php echo $post_url;?>" 
          data-param-text="<?php echo $post_title;?>" width="38" height="38" aria-label="Share on Linkedin">
            <?php echo $sharing_providers['linkedin'];?>
        </amp-social-share>

      </div>

  <?php }





  public function amp_embeds( $post_id = '', $only_embed_providers = false ){
    if( $post_id ){
      $post = get_post( $post_id );

    }else{
      global $post;
    }

  	$post_content = [];
  	$post_content[] = $post->post_content;

  	$embed_scripts = [];

  	foreach( $post_content as $content ){

  		if( !preg_match_all( '/\[embed_code_sc\s+provider="([^"]+)"?[^]]+\]/', $content, $providers ) ) continue;

  		foreach( $providers['1'] as $provider ):
  			$embed_scripts[] = !empty( $this->amp_supported_embed_providers[ $provider ] ) ? $this->amp_supported_embed_providers[ $provider ] : 'amp-iframe';
  		endforeach;
  	}

  	return !$only_embed_providers ? array_values( array_unique( array_filter( $embed_scripts ) ) ) : array_values( array_unique( $providers['1'] ) );

  }



  public function get_embed_code_html( $provider, $embed_code ){

    if( !in_array( $provider, $this->providers_update_embed_code_html ) ) return $embed_code;

    if( $provider == 'reddit' ){

      $embed_html = '';

      if( !preg_match_all( '/href="([^"]+)"?/', $embed_code, $reddit_src ) ) return $embed_html;

      if( empty( $reddit_src['1']['0'] ) ) return $embed_html;

      $reddit_src = trim( $reddit_src['1']['0'], '/' );

      $embed_html = '<amp-reddit layout="responsive"
                        width="300"
                        height="400"
                        data-embedtype="post"
                        data-src="' . $reddit_src . '/?ref=share&ref_source=embed">
                      </amp-reddit>';


    }elseif( $provider == 'glomex' ){

      if( !preg_match( '/data-integration-id="([^"]+)"?/', $embed_code, $glomex_integration_id ) || !preg_match( '/data-playlist-id="([^"]+)"?/', $embed_code, $glomex_playlist_id ) ) return $embed_html;

      $embed_html = '<amp-iframe
                      src="' . $this->glomex_amp_player . '/iframe-player.html?integrationId=' . $glomex_integration_id['1'] . '&playlistId=' . $glomex_playlist_id['1'] . '"
                      sandbox="allow-scripts allow-same-origin allow-popups"
                      allow="autoplay *; fullscreen *"
                      referrerpolicy="unsafe-url"
                      frameborder="0"
                      width="16"
                      height="9"
                      layout="responsive">
                    </amp-iframe>';


    }elseif( $provider == 'pinterest' ){

      if( !preg_match( '/href="([^"]+)"?/', $embed_code, $pinterest_src ) ) return $embed_html;

      $embed_html = '<amp-pinterest
                        width="280"
                        height="400"
                        data-do="embedPin"
                        layout="responsive"
                        data-url="' . $pinterest_src['1'] . '">
                      </amp-pinterest>';


    }elseif( $provider == 'playbuzz' ){

      if( !preg_match( '/data-id="([^"]+)"?/', $embed_code, $playbuzz_data_id ) ) return $embed_html;

      $embed_html = '<amp-playbuzz
                        data-item="' . $playbuzz_data_id['1'] . '"
                        layout="responsive"
                        width="300"
                        height="500">
                      </amp-playbuzz>';

    }elseif( $provider == 'tumblr' ){

      if( !preg_match( '/data-href="([^"]+)"?/', $embed_code, $tumblr_url ) ) return $embed_html;

      $embed_html = '<amp-iframe
                      src="' . $tumblr_url['1'] . '"
                      sandbox="allow-scripts allow-same-origin allow-popups"
                      referrerpolicy="unsafe-url"
                      frameborder="0"
                      width="280"
                      height="400"
                      layout="responsive">
                    </amp-iframe>';

    }elseif( $provider == 'apester-media' ){

      if( !preg_match( '/data-media-id="([^"]+)"?/', $embed_code, $apester_media_id ) ) return $embed_html;

        $embed_html = '<amp-apester-media
                        data-apester-media-id="' . $apester_media_id['1'] . '"
                      layout="fill">
                      </amp-apester-media>';

    }elseif( $provider == 'tiktok' ){

      if( !preg_match( '/data-video-id="([^"]+)"?/', $embed_code, $tiktok_video_id ) ) return $embed_html;

      $embed_html = '<amp-iframe
                      src="https://www.tiktok.com/embed/v2/' . $tiktok_video_id['1'] . '?lang=en-US"
                      sandbox="allow-scripts allow-same-origin allow-popups"
                      referrerpolicy="unsafe-url"
                      width="330"
                      height="820"
                      frameborder="0"
                      layout="container">
                    </amp-iframe>';
    }

    return $embed_html;

  }

  public function btw_update_post_has_amp_version( $post_id, $post ){

    if( wp_is_post_revision( $post_id )
      || !current_user_can( 'edit_post', $post_id )
      || !in_array( get_post_type( $post_id ), $this->get_supported_post_types() )
      || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    ){
      return;
    }

    $embed_providers = self::amp_embeds( $post_id, true );

    /**
     * If post contains unsupported embeds, disable it from amp
     */
    if( !empty( array_intersect( $embed_providers, $this->exluded_embed_providers ) ) ){

      update_post_meta( $post_id, self::POST_AMP_STATUS_POST_META_KEY, 'disabled' );
      return true;

    }

    $post_template = get_post_meta( $post_id, '_wp_page_template', true );

    if( in_array( $post_template, $this->get_unsupported_post_templates() ) ){
      
      update_post_meta( $post_id, self::POST_AMP_STATUS_POST_META_KEY, 'disabled' );
      return true;

    }

    /**
     * Check post for pdf embeder and yop polls
     */
    // if( (strpos( $post->post_content, '[pdf-embedder' ) !== false) || (strpos( $post->post_content, '[yop_poll' ) !== false) ){
    if( (strpos( $post->post_content, '[yop_poll' ) !== false) ){
      update_post_meta( $post_id, self::POST_AMP_STATUS_POST_META_KEY, 'disabled' );
      return true;
    }

  }

  public function remove_paragraphs($text){
    return preg_replace( '/<p([^>]+)?>?|<\/p>/i', '', $text );
  }

  public function pdf_embeder_to_link( $post_content ){

      global $wp_current_filter;

      if ( !btw_is_amp_endpoint()
          || !in_the_loop() 
          || !is_singular( ['post', 'video', 'skitsa', 'page'] )
          || !is_main_query()
          || in_array( 'get_the_excerpt', $wp_current_filter )
          || !str_contains( $post_content, '[pdf-embedder' )
      ){
        return $post_content;
      }

      preg_match_all('/\[pdf-embedder url=\"([^"]+)\"(\s?title=\"(?P<title>[^"]*)\")?\]/', $post_content, $pdf_embeders );

      if( !$pdf_embeders ){
        return $post_content;
      }

      foreach( $pdf_embeders['1'] as $index => $pdf_url ){

        if( !$pdf_url ){
          continue;
        }

        $pdf_title = !empty( $pdf_embeders['title'][$index] )
          ? esc_attr( $pdf_embeders['title'][$index] )
          : 'Δείτε το αρχείο';

        $link_html = "<a class=\"post_content_inline_pdf\" title=\"{$pdf_title}\" target=\"_blank\" href=\"{$pdf_url}\">{$pdf_title}</a><br />";

        $post_content = str_replace( $pdf_embeders['0'][$index], $link_html, $post_content );

      }

      return $post_content;

  }


} //end class

$contra_amp = new Contra_AMP();
