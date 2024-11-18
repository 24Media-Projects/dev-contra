<?php


 /*
   class BTW_Embed.
   Extends wp WP_oEmbed class

   Usage on post videos to get oembed html from video url
   Used for featured post video and post type video
   supported providers: youtube, video

   parent class: WP_oEmbed
   wp-includes/class-wp-oembed.php

   ** Glomex For future use **
   See http://support-docs.glomex.com/glomex-player-api.html amp
   Now PATH_TO_SOME = https://player.glomex.com/integration/1.268.2 | public $glomex_amp_player

   ** Facebook For Future use
   See https://developers.facebook.com/docs/graph-api/reference/oembed-video/
 */


 class BTW_Embed extends WP_oEmbed{

   const LAZY_LOAD = true;

   public $providers_names = array(
     'YouTube' => 'youtube',
     'Vimeo'   => 'vimeo',
     'Facebook' => 'facebook'
   );

   private $video_html_default_args = array(
     'thumbnail_url' => '',
     'embeded_html' => '',
   );

   private $site_url;

   public $glomex_amp_player = 'https://player.glomex.com/integration/1.268.2';

   protected $glomex_integration_id = 'yddswnmdjj9q2xwy';

   public function __construct(){
     parent::__construct();

     global $btw_global_settings;

     $this->site_url = $btw_global_settings->get_esc_site_url();

     add_filter( 'oembed_remote_get_args', array( $this, 'btw_set_oembed_request_headers' ), 30, 2 );
     
   }

   /*
     Extend wp oembed support. Add glomex player
     Note: Glomex doesnt have oembed.
     Use regex to catch video id
     html: callback to get video html
     See wp-includes/class-wp-oembed.php

     for more details
   */

   public $no_oembed_providers = array(
     'glomex' => array(
       'html' => 'get_glomex_html',
       'regex' => '#https?://(www\.)?exchange\.glomex\.com\/video\/(.*)#i',
       'neeeds_video_id' => true,
     ),
   );



   public function init(){
     parent::init();

     add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

   }


   public function enqueue_scripts() {

     $time = strtotime('now');

    //  wp_register_script( 'abstract_oembed_videos', get_template_directory_uri() . '/assets/js/front-end/oembed-videos/abstract-oembed-videos.es6.js', array(), $time, true );
    //  wp_register_script( 'oembed_videos', get_template_directory_uri() . '/assets/js/front-end/oembed-videos/oembed-videos.es6.js', array(), $time, true );
     wp_register_script( 'oembed_videos', get_template_directory_uri() . '/assets/js/front-end/oembed-videos/oembed-videos.min.js', array(), $time, true );
    //  wp_enqueue_script('abstract_oembed_videos');

     wp_localize_script( 'oembed_videos', 'OEV', array(
       'lazy_load' =>  self::LAZY_LOAD
     ));

     wp_enqueue_script( 'oembed_videos' );
   }



   private function unique_video_ref(){
     global $btw_video_refs;

     $btw_video_refs = $btw_video_refs ? $btw_video_refs : [];
     $video_ref = 'video-' . wp_rand( 0, 99999 );

     if( in_array( $video_ref, $btw_video_refs ) ){
       return self::unique_video_ref();
     }

     $btw_video_refs[] = $video_ref;

     return $video_ref;

   }



   /*
     Get provider name
     see $providers_names
     for more details
   */
   private function btw_get_provider_name( $provider ){
     return $this->providers_names[ $provider ];
   }




   private function oembed_videos_scripts_args( $provider_name, $args = array() ){

     if( empty( $provider_name ) || empty( $args ) ) return false;

     global $btw_video_scripts_args;
     $btw_video_scripts_args = $btw_video_scripts_args ? $btw_video_scripts_args : [];

     if( empty( $btw_video_scripts_args[ $provider_name ] ) ){
       $btw_video_scripts_args[ $provider_name ] = [];
     }

     $btw_video_scripts_args[ $provider_name ][] = $args;

   }


   /*
     Add Referer header for private vimeo videos
   */
   public function btw_set_oembed_request_headers( $args, $url ){

     if( strpos( $url, 'https://vimeo.com' ) !== 0 ) return $args;

     $args['headers'] = !empty( $args['headers'] ) ? $args['headers'] : [];
     $args['headers'] = array(
       'Referer' => site_url(),
     );

     return $args;
   }


   // private function get_html5_video_html( $video_file, $video_ref = '' ){
     private function get_html5_video_html( $url, $video_ref = '' ){

     $attchment_id = attachment_url_to_postid( $url );
     $video_file = acf_get_attachment( $attchment_id );

     return '<video id="' . $video_ref . '" class="embed_video video_player html5_video">
               <source src="' . $video_file['url'] . '" type="' . $video_file['mime_type'] . '" />
               '. __( 'Your Browser doens\'t support video', 'btw' ) .'
             </video>';
   }


   /*
     Glomex video html callback
     ow intergration id:
     See $no_oembed_providers - glomex

     for more details

  */
   public function get_glomex_html( $matches, $video_ref ){
     $video_id = $matches['2'];

     $data = new stdClass();
     $data->html = '<glomex-player id="' . $video_ref . '" class="glomex_featured_video" data-integration-id="' . $this->glomex_integration_id . '" data-playlist-id="' . $video_id . '" data-width="1600" d$ata-height="900"></glomex-player>';
     $data->amp_html = '<amp-iframe
                           src="' . $this->glomex_amp_player . '/iframe-player.html?integrationId=' . $this->glomex_integration_id . '&playlistId=' . $video_id . '"
                           sandbox="allow-scripts allow-same-origin allow-popups"
                           allow="autoplay *; fullscreen *"
                           referrerpolicy="unsafe-url"
                           frameborder="0"
                           width="16"
                           height="9"
                           layout="responsive">
                           <span placeholder="" class="amp-wp-iframe-placeholder"></span>
                         </amp-iframe>';
     return $data;
   }


   public function get_oembed_video_html( $url, $params = array() ){

     global $post;

     $args = array_merge( $this->video_html_default_args, $params );
     $video_type = get_field( 'btw__article_fields__video_type', $post->ID );
     $html5_video = $video_type == 'html5';
     $custom_player = $video_type == 'custom_player';

     $video_ref = self::unique_video_ref();
     $template_part_name = !btw_is_amp_endpoint() ? '/templates/template-parts/oembed/oembed_html' : '/amp-templates/template-parts/oembed/oembed_html';

     // defined in child theme, because custom_player varies.
     // If not defined, return false
     if( $custom_player ){

        $custom_player_data = apply_filters( 'btw/oembed/custom_player_data', false, $args, $video_ref, $template_part_name, $post );

        return $custom_player_data;
     }

     // html5 video
    if( $html5_video ){

       self::oembed_videos_scripts_args( 'html5', array(
         'video_ref' => $video_ref,
       ));

       return array(
         'provider_name' => 'html5',
         'video_id' => !empty( $provider_data->video_id ) ? $provider_data->video_id : '',
         'video_ref' => $video_ref,
         'video_html' => self::get_html5_video_html( $url, $video_ref ),

         'html' => btw_return_template( $template_part_name, array(
           'video_html' => self::get_html5_video_html( $url, $video_ref ),
           'provider_name' => 'html5',
           'video_id' => !empty( $provider_data->video_id ) ? $provider_data->video_id : '',
           'video_ref' => $video_ref,
           'embeded_html' => $args['embeded_html'],
           'amp_html' => '<amp-vimeo
                             data-videoid="' . $provider_data->video_id . '"
                             layout="responsive"
                             width="16"
                             height="9">
                          </amp-vimeo>'
         ))
       );

     }


    foreach ($this->no_oembed_providers as $provider => $data) {

      if (preg_match($data['regex'], $url, $matches)) {

        $video_html = call_user_func(array($this, $data['html']), $matches, $video_ref );

        self::oembed_videos_scripts_args( $provider, array(
          'video_ref' => $video_ref,
        ));

        return array(
          'provider_name' => $provider,
          'video_id' => !empty($video_id) ? $video_id : '',
          'video_ref' => $video_ref,
          'video_html' => $video_html->html,

          'html' => btw_return_template($template_part_name, array(
            'video_html' => $video_html->html,
            'provider_name' => $provider,
            'video_id' => !empty($video_id) ? $video_id : '',
            'video_ref' => $video_ref,
            'embeded_html' => $args['embeded_html'],
            'amp_html' => $video_html->amp_html,
          ))
        );
      }
    
     }
    
     $provider_data = self::get_data( $url );

     if( !$provider_data || empty( $provider_data->provider_name ) || !in_array( $provider_data->provider_name, array_keys( $this->providers_names ) ) ) return false;

     $provider_name = self::btw_get_provider_name( $provider_data->provider_name );

     if( $provider_name == 'youtube' ){
       $provider_data->html = str_replace( '?feature=oembed','?feature=oembed&enablejsapi=1', $provider_data->html );
       $provider_data->html = str_replace( '<iframe ', '<iframe id="' . $video_ref . '" class="embed_video youtube_video" ', $provider_data->html );
       $provider_data->amp_html = $provider_data->html;


     }elseif( $provider_name == 'vimeo' ){
       $provider_data->html = str_replace('<iframe ', '<iframe id="' . $video_ref . '" class="embed_video vimeo_video" ', $provider_data->html);
       $provider_data->amp_html = '<amp-vimeo
                                     data-videoid="' . $provider_data->video_id . '"
                                     layout="responsive"
                                     width="16"
                                     height="9">
                                  </amp-vimeo>';


     }elseif( $provider_name == 'facebook' ){
       $provider_data->html = str_replace( '<div class="fb-video"','<div class="fb-video" id="fb_embed_video_' . $video_ref . '"', $provider_data->html );
     }

     if( self::LAZY_LOAD ){
       $provider_data->html = str_replace( 'src', 'data-src', $provider_data->html );
     }

    /**
      * Add missing title attribute if embed code has iframe with no title
      */
    if( preg_match('/<iframe.*>/', $provider_data->html ) === 1
      && !preg_match('/title="[^"]+"/', $provider_data->html )
    ){

      $provider_data->html = str_replace( '<iframe ', '<iframe title="embed from ' .  $provider_name. '"', $provider_data->html );

    }

     self::oembed_videos_scripts_args( $provider_name, array(
       'video_id' => !empty( $provider_data->video_id ) ? $provider_data->video_id : '',
       'video_ref' => $video_ref,
     ));


     return array(
       'provider_name' => $provider_name,
       'video_id' => !empty( $provider_data->video_id ) ? $provider_data->video_id : '',
       'video_ref' => $video_ref,
       'video_html' => $provider_data->html,

       'html' => btw_return_template( $template_part_name, array(
         'video_html' => $provider_data->html,
         'provider_name' => $provider_name,
         'video_id' => !empty( $provider_data->video_id ) ? $provider_data->video_id : '',
         'video_ref' => $video_ref,
         'embeded_html' => $args['embeded_html'],
         'amp_html' => $provider_data->amp_html ?? ''
       ))
     );
   }



   public static function set_video_variables(){

   	global $btw_video_scripts_args;

   	if( empty( $btw_video_scripts_args ) ) return; ?>

      <script type="text/javascript">
        /* <![CDATA[ */
          var oembedVideosParams = JSON.parse( '<?php echo json_encode( $btw_video_scripts_args ) ;?>' );
        /* ]]> */

      </script>

   <?php }

 }

$extend_oembeds = new BTW_Embed();
$extend_oembeds->init();
