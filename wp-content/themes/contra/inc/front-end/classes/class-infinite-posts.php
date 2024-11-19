<?php 

class Contra_Infinite_Posts{

    public function __construct(){

        add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );

        add_action( 'wp_footer', [ $this, 'excluded_posts' ], 1 );

        add_action('wp_footer', [$this, 'add_script_template']);

    }


    public function wp_enqueue_scripts(){

        if( btw_is_amp_endpoint() ) return;

        $time = strtotime('now');

        // wp_register_script(' infinite_posts_js', get_stylesheet_directory_uri() . '/assets/js/front-end/infinitePosts.js', ['wp-util'], $time, true);
        wp_register_script('infinite_posts_js', get_stylesheet_directory_uri() . '/assets/js/front-end/production/infinitePosts.bundle.js', ['wp-util'], $time, false );

        wp_localize_script( 'infinite_posts_js', 'INP', self::get_localize_script() );

        wp_enqueue_script( 'infinite_posts_js' );
    }


  function get_localize_script(){

    $is_magazine = btw_is_magazine();

    $rest_api_endpoint =  'wp/v2/' . BTW_Global_Settings::rest_api_prefix_base() . '-infinite-';
    $rest_api_endpoint .= ( $is_magazine ? 'magazine-' : '' ) . 'posts';

      $infinite_posts_args = [
        'current_page'       => get_query_var( 'paged' ) ?: 1,
        'rest_url'           => get_rest_url( null, $rest_api_endpoint ),
        'per_page'           => 24,
        'inclusive_terms'    => true,
        'page_title'         => '',
        'page_title_suffix'  => ' | Contra',
        'post_template_name' => 'archive_post',
        'post_types'         => [ 'post', 'video' ],
        'orderby'            => 'date',
        'ads_config'         => [
          'slot_name' => 'section_inline',
        ],
      ];

    /**
    * Magazines subcategories use different slot and post template
    */
    if( $is_magazine ){
      $infinite_posts_args['post_template_name'] = btw_is_magazine_subcategory()
        ? 'category_maganize_post'
        : 'homepage_maganize_post';

      $infinite_posts_args['ads_config'] = [
        'slot_name' =>  'SundayEdition_Category_inline',
      ];
    }

    if( is_category( 'skitsa' ) ){
      $infinite_posts_args['post_types'] = ['skitsa'];
      $infinite_posts_args['post_template_name'] = 'category_skitso_post';
    }

    if( is_category() || is_tag() ){

        $term = get_queried_object();
        $term_ids = $term->term_id;

      /** 
        * Get term children if inclusive_terms is set
       */
        if( $infinite_posts_args['inclusive_terms'] ){
          $inclusive_terms = get_term_children($term->term_id, $term->taxonomy);
          $inclusive_terms[] = $term->term_id;
          $term_ids = implode( ',', $inclusive_terms );
        }

        $infinite_posts_args['terms'] = $term_ids;
        $infinite_posts_args['archive_type'] = $term->taxonomy;
        $infinite_posts_args['archive_url'] = get_term_link( $term );
        $infinite_posts_args['page_title'] = $term->name;

    }elseif( is_author() ){

        $author_id = get_queried_object_id();
        $author = new WP_User($author_id);

        $infinite_posts_args['terms'] = $author_id;
        $infinite_posts_args['archive_type'] = 'author';
        $infinite_posts_args['archive_url'] = get_author_posts_url( $author_id );
        $infinite_posts_args['page_title'] = $author->display_name;

    }elseif( is_search() ){

      $infinite_posts_args['archive_type'] = 'search';
      $infinite_posts_args['terms'] = get_search_query();
      $infinite_posts_args['archive_url'] = get_search_link();
      $infinite_posts_args['page_title'] = 'Αναζήτηση για: ' . get_search_query();
      $infinite_posts_args['orderby'] = $_GET['orderby'] ?? 'date';
      
    }elseif( is_page_template( 'templates/eidiseis.php' ) ){

      global $post;

      $infinite_posts_args['archive_type'] = 'all_posts';
      $infinite_posts_args['archive_url'] = get_the_permalink();
      $infinite_posts_args['page_title'] = get_the_title( $post );


    }

    return [
      'infinite_posts' => $infinite_posts_args,
    ];
  }


  public function add_script_template(){
    get_template_part('templates/template-parts/archive/script_templates/post');
    get_template_part('templates/template-parts/archive/script_templates/magazine_post');
    get_template_part('templates/template-parts/archive/script_templates/magazine_hp_post');
    get_template_part('templates/template-parts/archive/script_templates/skitso_post');
    get_template_part('templates/template-parts/archive/script_templates/ad');
  }


  public function excluded_posts(){

    global $btw_log_posts; ?>

    <script>
      var infinitePostsExcludePostIds = '<?php echo implode( ',', $btw_log_posts->get_displayed_posts() ?? '' );?>';
    </script>

  <?php  }


}


add_action( 'wp', function(){
    if( is_archive() || is_search() || is_page_template( 'templates/eidiseis.php' ) ){
        $contra_infinite_posts = new Contra_Infinite_Posts();
    }
});
