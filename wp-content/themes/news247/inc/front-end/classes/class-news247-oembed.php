<?php



class News247_Embed extends BTW_Embed{

    const LAZY_LOAD = true;

    public $glomex_amp_player = 'https://player.glomex.com/integration/1.268.2';

    /*
      FACEBOOK ACCESS TOKEN: app_id|client_token, from facebook app
   */
    public $facebook_access_token = '243528668519722|b24f24d5419cc2f851863811a3d240d5';

    public function __construct(){

        parent::__construct();

        $this->providers['#https?://www\.facebook\.com/.*/videos/.*#i'] = array( 'https://graph.facebook.com/v9.0/oembed_video/', true );

        add_filter( 'oembed_fetch_url', array( $this, 'news247_oembed_fetch_url' ), 10, 3 );

        $this->no_oembed_providers['ert_tv'] = [
            'html' => 'get_ert_tv_html',
            'regex' => '#https://www.ert.gr/webtv/ert/tv/live-glm/ert-news(-\d{1})?.html#i',
        ];
    }


   public function news247_oembed_fetch_url( $provider, $url, $args ){

     if( strpos( $provider, 'https://graph.facebook.com' ) !== 0 ) return $provider;

     $provider = add_query_arg( 'access_token', $this->facebook_access_token, $provider );
     $provider = add_query_arg( 'omitscript', 1, $provider );

     return $provider;
   }

  

    public function get_ert_tv_html( $matches ){

        $video_url = $matches['0'];

        $data = new stdClass();
        $data->html = '<iframe title="embed from ert" src="' . $video_url . '"></iframe>';
        $data->amp_html = '<amp-iframe
                           src="' . $video_url . '"
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
   
}

