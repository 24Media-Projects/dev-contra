<?php

/*
Admin Editor class
Contains everything related to the wp editor
Register modules: read_also, product_crawler, embed_code
Add functionality and previw mode for the above modules
*/

class BTW_Admin_Editor{

  public function __construct(){

    add_action( 'save_post', [ $this, 'update_post_content' ], 20, 2 );
    add_action( 'admin_init', [ $this,'wp_editor_plugins_and_assets' ] );


    //Editor buttons
    add_filter( 'wp_kses_allowed_html', [ $this,'wp_editor_extend_allowed_html' ] );
    add_filter( 'tiny_mce_before_init', [ $this,'wp_editor_insert_formats' ], 10, 2 );
    add_filter( 'mce_buttons', [ $this,'wp_editor_buttons' ], 99 );
    add_filter( 'mce_buttons_2',[ $this,'wp_editor_buttons_2' ], 99 );


    add_action( 'admin_print_footer_scripts', [ $this,'add_rel_attr_to_wpLink' ], 100 );
    add_filter( 'wp_link_query', [ $this,'extend_wp_link_results' ], 20, 2 );
    add_filter( 'wp_link_query_args', [ $this,'extend_wp_link_query_args' ], 20 , 2 );
    add_action( 'admin_enqueue_scripts', [ $this,'btw_wpLink' ] , 999 );


  }


  /*
    External links:
    Update post content and acf content fields

    External links:
    update post content and acf content fields using external_links_target function

    Remove and add save_post action to avoid infinite loop.
    See save_post action for more details
  */

  public function update_post_content( $post_id, $post ){

    if (
      wp_is_post_revision( $post_id )
      || !current_user_can( 'edit_post', $post_id )
      || get_post_type( $post_id ) != 'post'
      || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
    ) {
      return;
    }

    $content = $post->post_content;

    $update_post_content = self::external_links_target( $content );
    $post_lead = get_post_meta( $post_id, 'btw__global_fields__lead', true );

    if( $post_lead ){
      $update_post_lead = self::external_links_target( $post_lead );
      update_post_meta( $post_id, 'btw__global_fields__lead', $update_post_lead );
    }

    remove_action( 'save_post', [ $this, 'update_post_content' ], 20, 2 );
    /**
     * before wp 6.4 remove / add action post_updated with callback wp_save_post_revision
     * After 6.4, not needed
     */
    remove_action( 'post_updated', 'wp_save_post_revision', 10, 1 );

    wp_update_post( array( 'ID' => $post_id, 'post_content' => $update_post_content ), false, false );

    add_action( 'save_post', [ $this, 'update_post_content' ], 20, 2 );
    
    add_action('post_updated', 'wp_save_post_revision', 10, 1 );



  }



  /*
    Add custom editor styles
    Add inline AD editor plugin
    See assets/js/admin/wp-editor-tinymce-inline-ad.js  for more details
  */

  public function wp_editor_plugins_and_assets(){

    $time = strtotime( 'now' );
    add_editor_style( get_template_directory_uri() . '/assets/css/admin/admin-editor-styles.css' );

    if ( !current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
      return;
    }

    if ( get_user_option( 'rich_editing' ) !== 'true' ) {
      return;
    }

    add_filter( 'mce_external_plugins', function( $plugins ){

      $time = strtotime( 'now' );
      $plugins['inline-ad'] = get_template_directory_uri() . '/assets/js/admin/wp-editor-tinymce-inline-ad.js?v=' . $time;

      return $plugins;

    });


    add_filter( 'mce_buttons', function( $wp_editor_buttons ){

      array_push( $wp_editor_buttons, 'inline-ad' );

      return $wp_editor_buttons;

    });

  }


  /*
    External Links functionality.
    Check for external links and add target blank if is not set already
  */

  private function external_links_target( $text ){
  	if( !preg_match_all( '/<a[^>]+>?/i', $text, $get_a_tags ) ){
  		return $text;
  	}

  	foreach( $get_a_tags['0'] as $index => $tag ){
  		if( !preg_match( '/href="([^"]+)"?/i', $tag, $link ) ){
  			continue;
  		}

  		$link_url = $link['1'];
  		if( !preg_match( '/target="([^"]+)"?/i', $tag, $target ) ){
  			$target = '';
  		}else{
  			$target = $target['1'];
  		}

      global $btw_global_settings;

  		if( strpos( $link_url, $btw_global_settings->get_esc_site_url() ) !== false ||  trim( $target ) == '_blank' ){
  			continue;
  		}
  		$new_a_tag = str_replace( '<a', '<a target="_blank" ', $tag );
  		$text = str_replace( $tag, $new_a_tag, $text );

  	}

    return $text;

  }

  /*
    Tinymce editor:
    extend allowed elements
    Only p, h2, h3 tags available for block format
    See tiny_mce_before_init hook for more details
  */

  public function wp_editor_insert_formats( $mceInit, $editor_id ){

    $ext = 'iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src],script[src|async|defer|type|charset]';
    if ( isset( $mceInit['extended_valid_elements'] ) ) {
      $mceInit['extended_valid_elements'] .= ',' . $ext;
    } else {
      $mceInit['extended_valid_elements'] = $ext;
    }

    $mceInit['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3';

    return $mceInit;
  }


  /*
    Tinymce editor: Editor button line 2
    See mce_buttons_2 hook for more details
  */

  public function wp_editor_buttons_2( $buttons ){
    $remove_buttons = array(
      'underline',
      'alignjustify',
      'hr',
      'forecolor',
  		'charmap',
  		'indent',
  		'outdent',
      // 'undo',
      // 'redo',
    );

    foreach ( $buttons as $button_key => $button_value ) {
      if ( in_array( $button_value, $remove_buttons ) ) {
        unset( $buttons[ $button_key ] );
      }
    }

    //add buttons
    array_unshift( $buttons, 'styleselect' );
    $buttons[] = 'superscript';
    $buttons[] = 'subscript';

  	return $buttons;

  }

  /*
    Tinymce editor: Editor button line 1
    See mce_buttons hook for more details
  */

  public function wp_editor_buttons( $buttons ){
    $remove_buttons = array(
      'alignleft',
      'aligncenter',
      'alignright',
      'wp_more',
    );

    foreach ( $buttons as $button_key => $button_value ) {
      if ( in_array( $button_value, $remove_buttons ) ) {
        unset( $buttons[ $button_key ] );
      }
    }

    return $buttons;
  }

  /*
    Tinymce editor:
    extend allowed html elements and attributes: script,iframe, svg
    See wp_kses_allowed_html hook for more details
  */

  public function wp_editor_extend_allowed_html( $context ){
    $context['script'] = array(
      'src' => true,
      'type' => true,
      'async' => true,
      'defer' => true,
      'charset' => true,
    );

    $context['iframe'] = array(
      'src' => true,
      'width' => true,
      'height' => true,
      'allowfullscreen' => true,
    );

    $context['svg'] = array(
      'width' => true,
      'height' => true,
      'viewBox' => true,
      'version' => true,
      'xmlns'=> true,
      'xmlns:xlink'=> true,
      'enable-background' => true,
    );

    return $context;
  }



      /*
      wpLink Modal
      extend wpLink object getAttrs method to allow rel no-follow
      See wp-includes/js/wplink.js
      for more details
    */

    public function add_rel_attr_to_wpLink(){ ?>

      <script type="text/javascript">
        if( document.querySelector( '#wp-link-wrap .link-target' ) ){

          var wpLinkTarget = document.querySelector( '#wp-link-wrap .link-target' ),
              wpLinkNoFollow = document.createElement( 'div' );

          wpLinkNoFollow.className = 'link-nofollow';
          wpLinkNoFollow.innerHTML = '<label><span></span>' +
                                     '<input type="checkbox" id="wp-link-nofollow" />No follow</label>';

          wpLinkTarget.parentNode.append( wpLinkNoFollow );


          var wpLinkSearchWrapper = document.querySelector( '.link-search-wrapper' ),
              wpLinkFilterResults = document.createElement( 'div' );

          wpLinkFilterResults.className = 'link-filter-search-results-wrapper';
          wpLinkFilterResults.innerHTML =  '<div class="filters">' +
                                            '<select name="filter-results" class="filter-results">' +
                                            '<option value="">Όλα</option>' +
                                            '<option value="post">Μόνο Άρθρα</option>' +
                                            '<option value="post_tag">Μόνο Tags</option>' +
                                            '<option value="category">Μόνο Κατηγορίες</option>' +
                                            '</select>'
                                          '</div>';

            wpLinkSearchWrapper.append( wpLinkFilterResults );



            document.querySelector( '.filter-results' ).onchange = function(){
              var selectedFilter = this.value,
                  nodes = document.querySelectorAll( '.query-results > ul > li' );
                  // showNodes = !selectedFilter ? document.querySelectorAll('#most-recent-results > ul > li') : document.querySelectorAll('#most-recent-results > ul > li.' + selectedFilter  +  '-item');
                  var countRemoves = 0;
                  var countAdd = 0;
                  nodes.forEach( function( node ){
                    if( !selectedFilter ){
                      node.classList.add( 'show' );

                    }else if(node.className.split(' ').indexOf( selectedFilter + '-item' ) != -1){
                      node.classList.add( 'show' );
                      countAdd++;
                    }else{
                      node.classList.remove( 'show' );
                      countRemoves++;
                    }

                  });
                  console.log( 'Remove ' + countRemoves, 'Add ' + countAdd );
            }

        }

      </script>

    <?php
    }


    /*
      Overide wpLink.js to add Filter Options
      Original file: wp-includes/js/wpLink.js
    */
    public function btw_wpLink() {

      wp_deregister_script( 'wplink' );

      $time = strtotime( 'now' );

      wp_register_script( 'wplink', get_template_directory_uri().'/assets/js/admin/wpLink.js', array( 'jquery', 'wpdialogs' ), $time , true );
      wp_localize_script( 'wplink', 'wpLinkL10n', array(
        'title'          => __( 'Insert/edit link' ),
        'update'         => __( 'Update' ),
        'save'           => __( 'Add Link' ),
        'noTitle'        => __( '(no title)' ),
        'noMatchesFound' => __( 'No results found.' ),
        'linkSelected'   => __( 'Link selected.' ),
        'linkInserted'   => __( 'Link inserted.' ),
      ));

      wp_enqueue_script( 'wplink' );
    }




    /*
      wp_link_query filter
      add post_tag and category select options on link on editor
      use the same offset / per page as post
      See wp-includes/class-wp-editor.php
      for more details
    */

    public function extend_wp_link_results( $results, $query ){

    $taxonomies = array( 'post_tag','category' );
    foreach( $taxonomies as $taxonomy ){

      $args = array(
        'taxonomy' => $taxonomy,
        'number' => 20,
        'offset' => isset( $query['offset'] ) ? $query['offset'] : 0,
      );

      if ( isset( $query['s'] ) ) {
        $args['search'] = $query['s'];
      }


      $get_terms = new WP_Term_Query;
      $terms = $get_terms->query( $args );

      foreach( $terms as $term ){
        $results[] =  array(
          'ID'        => $term->term_id,
          'title'     => trim( esc_html( $term->name ) ),
          'permalink' => get_term_link( $term->term_id, $term->taxonomy ),
          'info'      => $term->taxonomy == 'post_tag' ? 'tag' : 'category',
          'type'      => $term->taxonomy,
        );
      }

    }


    return $results;

  }



    /*
      wp_link_query_args filter
      post_types enabled for link selection: page, post, video?
      See wp-includes/class-wp-editor.php
      for more details
    */

    public function extend_wp_link_query_args( $query ){
      $query['post_type'] = array( 'page', 'post' );

      return $query;
    }





  }

  $btw_admin_editor = new BTW_Admin_Editor();


