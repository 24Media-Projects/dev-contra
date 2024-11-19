<?php

  class BTW_Global_Settings{

    const localize_js_object_name = 'CONTRA';
    const rest_api_prefix_base = 'contra';

    const xml_api_prefix_base = 'contra';

    const TRACKING_CODE_PROVIDERS = array();

    const DEFAULT_AUTHOR_NAME = 'newsroom';
    const CLEAN_SITE_NAME = 'contra';

	  const SITE_NAME = 'contra';


	  const CLOUDFRONT_DOMAIN = 'media.contra.gr';

    const REST_API_SUPPORT_TYPES = [
      'feeds',
      'posts',
      'videos',
    ];

	  const GROUP_TYPES = [
		  'hp'      => 'Homepage',
		  'bon'     => 'Best of Network',
	  ];

	  const GROUP_HP_TEMPLATES_CHOICES = [
		  'above_the_fold'              => 'above_the_fold',
		  'trending_topics'             => 'trending_topics',
		  'latest_stories'              => 'latest_stories (Επικαιρότητα)',
		  'popular_articles'            => 'popular_articles (Δημοφιλή Άρθρα)',
		  'tribute_basic'               => 'tribute_basic (Αφιερωματικό Widget)',
		  'term_basic'                  => 'term_basic (αρθρογραφία κατηγορίας/tag)',
		  'best_of_network'             => 'best_of_network',
		  'best_of_network_2'           => 'best_of_network_2',
		  'best_of_network_3'           => 'BON 3 (Multiple Feeds)',
		  'best_of_network_4'           => 'BON 4 (Single Feed)',
		  'articles_grid'               => 'articles_grid (Grid από Άρθρα, χωρίς διαφημίσεις)',
		  'newsletter'					=> 'newsletter',
		  'embed_code'					=> 'embed_code (Embed Code)',
		  'embed_code__full_width'		=> 'embed_code__full_width (Embed Code Full Width)',
	  ];


    const GROUP_BON_TEMPLATES_CHOICES = [
		'default' => 'Default'
    ];

    /*
      Supports inline ad button on wp editors
    */
    const SUPPORTS_INLINE_EDITOR_ADS = true;

    /*
       FACEBOOK ACCESS TOKEN: app_id|client_token, from facebook app
    */
    const FACEBOOK_ACCESS_TOKEN = '';


    public $facebook_access_token = '';


    public $btw_theme_settings;

    public function __construct(){

    }


	  /**
	   * @deprecated Use get_rest_api_prefix_base() instead
	   */
    public static function rest_api_prefix_base(){
      return self::rest_api_prefix_base;
    }

	  public static function get_rest_api_prefix_base(){
		  return self::rest_api_prefix_base;
	  }

    public static function xml_api_prefix_base(){
      return self::xml_api_prefix_base;
    }


    public static function get_localize_js_object_name(){
      return self::localize_js_object_name;
    }

    public static function get_facebook_access_token(){
      return self::FACEBOOK_ACCESS_TOKEN;
    }

    public static function get_clean_site_name(){
      return self::CLEAN_SITE_NAME;
    }

	  public static function get_site_name(){
		  return self::SITE_NAME;
	  }
    public static function get_rest_api_support_types(){
      return self::REST_API_SUPPORT_TYPES;
    }

	  public static function get_post_author_avatar_width(){
		  return 40;
	  }

	  public static function get_group_types(){
		  return self::GROUP_TYPES;
	  }

    public static function get_group_hp_templates_choices(){
      return self::GROUP_HP_TEMPLATES_CHOICES;
    }

    public static function get_group_bon_templates_choices(){
      return self::GROUP_BON_TEMPLATES_CHOICES;
    }


    public function get_esc_site_url(){
      return preg_replace( [ '/https?:\/\/(www)?/', '/\//' ], [ '', '\/' ], site_url() );
    }

    public function get_site_domain(){
      return preg_replace( '/https?:\/\/(www)?/', '', site_url() );
    }

    public function get_default_og_image_url(){
      return WPSEO_Options::get( 'og_default_image', '' );
    }

    public function supports_inline_editor_ads(){
      return self::SUPPORTS_INLINE_EDITOR_ADS;
    }


    public function get_esc_media_domain(){

      if( is_plugin_active( 'amazon-s3-and-cloudfront/wordpress-s3.php' ) ){
        global $as3cf;

        return self::CLOUDFRONT_DOMAIN;
      }

      $uploads_dir = wp_upload_dir();

      return preg_replace( [ '/https?:\/\/(www\.)?/', "/\//", "/\./" ], [ '', '\/', '\.' ], $uploads_dir['baseurl'] );
    }

    public function get_default_logo(){
      return array(
        'url' => get_template_directory_uri() . '/assets/img/logos/contra-logo.svg',
        'width' => '400',
        'height' => '126',
        'alt' => get_bloginfo( 'name' ),
      );
    }


    public function get_theme_settings(){
      return ( object ) array(
        'use_s3_offload' => is_plugin_active( 'amazon-s3-and-cloudfront/wordpress-s3.php' ),
        'content_images' => array(
          'regex' => '/(?<!data-)src="https?:\/\/(?:www\.)?' . self::get_esc_media_domain() . '\/([^"]+)"?/',
        )
      );
    }


    public function get_tracking_code_providers(){
      return self::TRACKING_CODE_PROVIDERS;
    }

    public static function get_default_author(){
      return new WP_User( self::DEFAULT_AUTHOR_NAME );
    }



  } // end class

  $btw_global_settings = new BTW_Global_Settings();
  $GLOBALS['btw_global_settings'] = $btw_global_settings;
