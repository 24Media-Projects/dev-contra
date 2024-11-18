<?php 

class BTW_Query{

    private $default_template_conditionals = [
        'category' => [
            'is_magazine_category'      => false,
            'is_magazine_subcategory'   => false,

            'is_podcast_category'       => false,
            'is_podcast_subcategory'    => false,

            'is_videos_category'        => false,
            'is_videos_subcategory'     => false,

            'is_default_category'       => false,
        ],
        'post' => [],
    ];

    public $template_conditionals;

    private $current_term;


    public function __construct(){
        
        add_action( 'pre_get_posts', [ $this, 'set_conditionals' ], 1 );

        add_action( 'pre_get_posts', [ $this, 'set_wp_query_props_on_main_query' ], 99 );

        //add_action( 'wp', [ $this, 'set_redisplay_posts_on_default_category' ], 99 );

        add_filter( 'category_template', [ $this, 'get_category_template' ] );

    }



    private function is_top_lvl_category( $top_lvl_term_attr ){
        return is_category( $top_lvl_term_attr );
    }


    private function is_subcategory( $top_lvl_term_attr_value, $top_lvl_term_attr_name = 'slug' ){

        $top_lvl_term = get_term_by( $top_lvl_term_attr_name, $top_lvl_term_attr_value, 'category' );

        $term_ancestors = get_ancestors( $this->current_term->term_id, 'category', 'taxonomy' );

        return in_array( $top_lvl_term->term_id, $term_ancestors );

    }


    private function set_category_template_conditionals( $wp_query ){

        if( !$wp_query->is_category() ){
            return $this->default_template_conditionals['category'];
        }

        $this->current_term = get_queried_object();

        $category_conditionals = [
            'is_magazine_category'      => self::is_top_lvl_category( 'magazine' ),
            'is_magazine_subcategory'   => self::is_subcategory( 'magazine' ),

            'is_podcast_category'       => self::is_top_lvl_category( 'podcasts' ),
            'is_podcast_subcategory'    => self::is_subcategory( 'podcasts' ),

            'is_videos_category'        => self::is_top_lvl_category( 'videos' ),
            'is_videos_subcategory'     => self::is_subcategory( 'videos' ),
        ];

        /**
         * if every of the above is false, it is a default category
         */
        $is_default_category = empty( array_filter( $category_conditionals, function( $category_conditional ){
            return $category_conditional === true;
        }));

        $category_conditionals['is_default_category'] = $is_default_category;

        return $category_conditionals;
    }



    private function set_post_template_conditionals( $wp_query ){
        return $this->default_template_conditionals['post'];
    }



    public function set_conditionals( $wp_query ){

        if( is_admin() || !$wp_query->is_main_query() ){
            return;
        }

        $this->template_conditionals = self::set_category_template_conditionals( $wp_query );
        $this->template_conditionals = array_merge( $this->template_conditionals, self::set_post_template_conditionals( $wp_query ) );
    }


    /*
    Archives
    post type post, video
    */
    public function set_wp_query_props_on_main_query( $query ){

        if( is_admin() || !$query->is_main_query() ){
            return;
        }
        
        if( is_archive() || is_search() ){

            if( is_category('skitsa') ){
                $query->set( 'post_type', ['skitsa'] );

                if( get_query_var('paged') < 2 ){
                    $query->set( 'posts_per_page', 27 );
                }

            } else {
                $query->set( 'post_type', ['post', 'video'] );
            }


            if( $this->template_conditionals['is_magazine_subcategory'] ){
                $query->set( 'posts_per_page', 26);
            }
        }

        /**
         * In category first page with feature group term_basic__with_banner
         * get 2 more posts. The extra 2 posts will be displayed in the featured group
         */
        if( $this->template_conditionals['is_default_category'] && get_query_var('paged', 0) == 0 ){

            $term = get_queried_object();

            $featured_group = get_field( 'btw__taxonomy_fields__featured_group', $term );

            if( !$featured_group ){
                return;
            }

            $featured_group_template = get_field( 'btw__group_fields__hp__template', $featured_group->ID );

            if( $featured_group_template == 'term_basic__with_banner' ){
                $query->set( 'posts_per_page', 26 );
            }
        }
    }



    /**
    * Category first page set featured posts and redisplay posts ( first 2 posts )
    * On default category first page, if has featured group with template term_basic,
    * set a property on wp_query to re display the last 2 posts from the group to the main posts loop
    */
    public function set_redisplay_posts_on_default_category(){
	
        global $wp_query;

        if( !is_admin()
            && $wp_query->is_main_query()
            && $this->template_conditionals['is_default_category']
            && get_query_var( 'paged', 0 ) == 0
            && $wp_query->posts
        ){

            $term = get_queried_object();
            $featured_group = get_field( 'btw__taxonomy_fields__featured_group', $term );
            
            if( !$featured_group ){
                return;
            }

            $featured_group_template = get_field('btw__group_fields__hp__template', $featured_group->ID );

            /**
             * This only applies for groups with template term_basic
             */
            if( $featured_group_template != 'term_basic' ){
                return;
            }
           
            $featured_group_acf_posts = get_field('btw__group_fields__hp__template__term_basic__posts_selection', $featured_group->ID);

            $atf_posts = wp_list_pluck( $featured_group_acf_posts, 'atf__post');
            $atf_posts = array_map(function ($atf_post) {
                return $atf_post['0'];
            }, $atf_posts);

            $wp_query->set( 'btw_redisplay_posts', array_slice( $atf_posts, 2, 2 ) );
           
        }

	}



    /**
     * Get category template
     * @param string, $template
     * 
     * @return string 
     */
    public function get_category_template( $template ){

		$acf_template = get_field('btw__category_fields__template', get_queried_object());

		if( $acf_template && $acf_template != 'default' ){
			$filename = 'term-templates/' . $acf_template;

		}elseif( $this->template_conditionals['is_videos_subcategory'] ){
            $filename = 'category-video-subcategory';
        
        }elseif( $this->template_conditionals['is_podcast_subcategory'] ){
            $filename = 'category-podcast-subcategory';
        
        }elseif( $this->template_conditionals['is_magazine_subcategory'] ){
            $filename = 'category-magazine-subcategory';
        }

        if( !empty( $filename ) ){
            $template = get_stylesheet_directory() . '/' . $filename . '.php';
        }

        if( get_query_var( 'paged', 0 ) != 0
            && file_exists( preg_replace( '/\.php$/', '', $template ) . '-paged.php' )
        ){
            return preg_replace('/\.php$/', '', $template) . '-paged.php';
        }
        
	    return $template;
    }


}


$btw_query = new BTW_Query;
$GLOBALS['btw_query'] = $btw_query;