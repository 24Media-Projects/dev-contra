<?php

class BTW_DFP_TARGETING{

  protected $set_targeting,
            $dfp_targeting_output;

  public $is_amp;

  public function __construct(){
    $this->is_amp = btw_is_amp_endpoint();
  }

  public function init(){

    $page_type = $this->get_page_type();
    $this->set_targeting =  method_exists( $this, $page_type ) ? self::$page_type() : array();
    $this->dfp_targeting_output = [];

    foreach( $this->set_targeting as $key => $values ){
      $this->dfp_targeting_output[] = "googletag.pubads().setTargeting('{$key}',{$values});";
    }

    return implode( "\n", $this->dfp_targeting_output );

  }

  public function amp_init(){

    $page_type = $this->get_page_type();
    $this->set_targeting =  method_exists( $this, $page_type ) ? self::$page_type() : array();
    $return = [];
    $return['targeting'] = [];

    foreach( $this->set_targeting as $key => $values ){
      $return['targeting'][ $key ] = $key == 'keywords' || $key == 'category' ? $values : trim( $values,'"\'' );
    }

    return json_encode( $return );

  }

  protected function get_page_type(){

    $page_type = is_home() || is_front_page()
            ? 'home' :
            ( is_tax() || is_archive() || is_search()
              ? 'taxonomy'
              : ( is_single() ? 'single_post' : ( is_page() ? 'page' : 'ros' ) ) );

    return apply_filters( 'btw/dfp_targetings/page_type', $page_type );

  }


  protected function home(){
    global $post;
    return array(
      'path' => '"' . site_url() . '"',
      'keywords' => "'home'",
      'category' => "'home'",
      'type' => "'home'",
    );
  }


  protected function page(){

    global $post;
    
    return array(
      'article' => "'" . $post->ID . "'",
      'path' => '"' . get_permalink( $post->ID ) . '"',
      'type' => "'page'",
    );
  }


  protected function taxonomy(){

    $q_object = get_queried_object();

    if( is_search() ){

      return array(
        'path' => '"' . site_url( '/?s='.get_search_query() ) . '"',
        'type' => "'search'",
        'keywords' => '"' . get_search_query() . '"',
      );

    }

    if(is_tag()){

      return array(
        'path' => '"' . get_tag_link( $q_object->term_id ) . '"',
        'type' => "'tag'",
        'keywords' => '"' . $q_object->slug . '"',
      );
    }

    if( is_author() ){
      return array(
        'path' => '"'.get_author_posts_url( get_queried_object()->ID , get_queried_object()->user_nicename ) . '"',
        'type' => "'author'",
        'keywords' => '"' . get_queried_object()->user_nicename . '"',
      );
    }

    $queried_object = get_queried_object();
    if( !property_exists( $queried_object, 'taxonomy' ) ) return [];

    $taxonomy = $queried_object->taxonomy;
    $taxonomy_obj = get_taxonomy( $taxonomy );
    $post_type = $taxonomy_obj->object_type['0'];

    $terms = self::btw_get_taxonomy_terms( array( $queried_object->term_id => $queried_object->slug ), $taxonomy );

    $return = array(
      'path' =>  '"' . get_term_link( $q_object->term_id ) . '"',
      'category' =>  '[' . implode( ',', $terms ) . ']',
      'type' => "'category'",
    );


    return $return;
  }


  protected function single_post(){

    $return = [];
    global $post;

    $post_type = $post->post_type;
    $taxonomy = 'category';

  	$terms = $taxonomy ? wp_get_post_terms( $post->ID, $taxonomy , array( 'fields' => 'all' ) ) : array();

    if( $terms ){

      $post_terms = [];

      foreach( $terms as $term ):
        $post_terms[ $term->term_id ] = $term->slug;
      endforeach;
    	$terms = !$this->is_amp ? self::btw_get_taxonomy_terms( $post_terms, $taxonomy ) : array_values( $post_terms );
    	$return['category'] = !$this->is_amp ? '[' . implode( ',', $terms ) . ']' : $terms;

    }

    $return['article'] = "'" . $post->ID . "'";
    $return['path'] = '"' . get_permalink( $post->ID ) . '"';
    $return['keywords'] = !$this->is_amp
                            ? '[' . implode( ',', self::btw_array_merge( wp_get_post_tags( $post->ID, array( 'fields' => 'slugs' ) ) ) ) . ']'
                            : wp_get_post_tags( $post->ID, array( 'fields' => 'slugs' ) );

    $return['type'] = '"article"';
    $return['article_template'] = '"' . get_field( 'btw__article_fields__template', $post->ID ) . '"';
    $return['post_type'] = '"' . $post->post_type .  '"';

    return $return;

  }


  protected function btw_get_taxonomy_terms( $terms, $taxonomy, $pr_cats = array() ){
		$get_current_terms = [];

		foreach( $terms as $term => $term_slug ):
			$main_term = self::btw_get_main_term( $term, $taxonomy );
			$get_current_terms[] = $main_term['slug'];
			$get_current_terms[] = $term_slug;
		endforeach;

		return self::btw_array_merge( array_unique( $get_current_terms ),$taxonomy );
	}



  private	function btw_get_main_term( $term_id, $taxonomy ){
		$main_term = get_term( $term_id, $taxonomy) ;
		return $main_term->parent == 0 ? array( 'term_id' => $main_term->term_id, 'slug' => $main_term->slug ) : self::btw_get_main_term( $main_term->parent, $taxonomy );
  }

  protected function btw_get_term_children( $term_parent_id, $taxonomy, $chld = array() ){

		$children = empty( $chld ) ? array() : $chld;
		$terms = get_terms(array(
			'taxonomy' => $taxonomy,
			'child_of' => $term_parent_id,
		));

		foreach( $terms as $term ){
			$children[] = $term->slug;
			if( get_term_children( $term->term_id, $taxonomy ) ){
				self::btw_get_term_children( $term->term_id, $taxonomy, $children );
			}
		}
		return $children;
	}

	protected function btw_array_merge( $array ){
		$return = [];

		foreach( $array as $value ){
			if( is_array( $value ) ){

				foreach( $value as $v ){
					$return[] = "'{$v}'";
				}

			}else{
					$return[] = "'{$value}'";
			}

		}

		return $return;

	}




}

?>
