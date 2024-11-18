<?php
class Btw_Dfp_Inline {

  private $min_chars;

  public $slot_ids = array(
        'article_inline',
        'article_inline_b'
  );

  public $amp_slots = array(
    'article_inline' => array(
      'slot_name' => 'ros_inline_a',
      'sizes'     => '300x250,1x1,300x600,336x280'
    ),
    'article_inline_b' => array(
      'slot_name' => 'ros_inline_b',
      'sizes'     => '300x250,300x600,336x280'
    ),
  );

  public $slot_ids_mobile = array();

  public function __construct( $args = array() ){

    $this->min_chars = $args['minimun_characters'] ?? 100;

    add_filter( 'the_content', [ $this, 'maybe_add_inline_ads' ] );

   }
  

   private function get_dfp_inline_tags(){

     $return = [];

     foreach(  $this->slot_ids as $slot_id ){

       $code = '<div class="sidebar__inner sticky_ad stick_top">
                  <div class="ads_element inarticle_ad">
                     ' . self::get_ad_template_part( $slot_id ) . '
                   </div>
                </div>';

        if( !empty( $this->slot_ids_mobile[ $slot_id ] ) ){

          $code .= '<div class="sidebar__inner sidebar__inner--mobile sticky_ad stick_top">
                      <div class="ads_element inarticle_ad ads_element_mobile">
                         ' . self::get_ad_template_part( $this->slot_ids_mobile[ $slot_id ] ) . '
                      </div>
                    </div>';

        }


       $return[] = array(
         'code' => $code,
       );
     }

     return $return;
   }


   private function get_ad_template_part( $slot_id ){

     $path = !btw_is_amp_endpoint() ? 'templates/template-parts/' : 'amp-templates/template-parts/';

     if( btw_is_amp_endpoint() ){

       $dfp_targeting = new BTW_DFP_TARGETING();
       $amp_targeting = $dfp_targeting->amp_init();

     }

     $amp_slot = $this->amp_slots[ $slot_id ];

     return btw_return_template( $path . '/ads/dfp',
        array_merge(
          $amp_slot,
            [
              'slot_id'       => $slot_id,
              'amp_targeting' => $amp_targeting ?? null,
            ]
        )
     );

   }

    public function maybe_add_inline_ads( $content ){

        global $wp_current_filter;

        if ( !in_the_loop() 
            || !is_singular( 'post' )
            || !is_main_query()
            || in_array( 'get_the_excerpt', $wp_current_filter )
        ){
            return $content;
        }

        global $post;

        if( btw_is_post_podcast() ){
            self::add_inline_ads_podcast_post($content);
        }

        return self::add_inline_ads_standard_post( $content );

    }

    public function add_inline_ads_standard_post( $content ){

      $dfp_inline_tags = self::get_dfp_inline_tags();
      $count = 0;

      if( preg_match_all('/<p>(.*?)<\/p>/s', $content, $get_paragraphs ) !== false ){
        $count_paragraphs = 0;

        foreach( $get_paragraphs['1'] as $key => $paragraph ):

          $p = wp_strip_all_tags( str_replace( '&nbsp;', '', trim( $paragraph ) ), true );

          if( $p && mb_strlen( $p ) >= $this->min_chars ):

            $count_paragraphs++;
            if( $count_paragraphs == 3 ){

              $count++;
              $dfp_html = array_shift( $dfp_inline_tags );

              $replace_with = $get_paragraphs['0'][ $key ] . $dfp_html[ 'code' ];
              $content = str_replace( $get_paragraphs['0'][ $key ], $replace_with, $content );
            }

            if( $count_paragraphs == 5 ){

              $count++;
              $dfp_html = array_shift( $dfp_inline_tags );

              $replace_with = $get_paragraphs['0'][$key] . $dfp_html['code'];
              $content = str_replace($get_paragraphs['0'][$key], $replace_with, $content);
            }

          endif;
        endforeach;


      }

      return  $content;

    }


    public function add_inline_ads_podcast_post( $content ){

      $dfp_inline_tags = self::get_dfp_inline_tags();
      $count = 0;

      if( preg_match_all( '/<p>(.*)<\/p>/', $content, $get_paragraphs ) !== false ){
        $count_paragraphs = 0;

        foreach( $get_paragraphs['1'] as $key => $paragraph ):

          $p = wp_strip_all_tags( str_replace( '&nbsp;', '', trim( $paragraph ) ), true );

          if( $p && mb_strlen( $p ) >= $this->min_chars ):

            $count_paragraphs++;
            if( $count_paragraphs == 1 ){

              $count++;
              $dfp_html = array_shift( $dfp_inline_tags );

              $replace_with = $get_paragraphs['0'][ $key ] . $dfp_html[ 'code' ];
              $content = str_replace( $get_paragraphs['0'][ $key ], $replace_with, $content );
            }

          endif;
        endforeach;


      }

      return  $content;

    }





}


$btw_dfp_inline = new Btw_Dfp_Inline();
