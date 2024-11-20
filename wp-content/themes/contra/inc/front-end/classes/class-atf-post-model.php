<?php 

class BTW_Atf_Post{

    protected $allowed_html_tags_for_titles = [
        'strong' => [],
    ];

	/**
	 * @var array
	 */
    protected $item;

	/**
	 * @var int|false
	 */
	protected $index;

	/**
	 * @var string|false
	 */
	protected $section_id;

	/**
	 * @var string
	 */
	protected mixed $primary_term;

    protected $default_image;

    protected $image_srcsets;


    protected $atf_post;

    protected $render_attrs;

    protected $group_template;


	public function __construct( $args = [] ){

		$this->primary_term =  $args['primary_term'] ?? 'post_tag';
		$this->item = $args['item'] ?? [];
		$this->index = $args['index'] ?? false;
		$this->section_id = !empty( $args['section_id'] ) ? $args['section_id'] : 'group-' . ( $args['group_id'] ?? get_the_ID() );
        $this->image_srcsets = $this->get_image_srcsets( $args['image_srcsets'] ?? [] );

        global $group_template;
        $this->group_template = $group_template;

        $this->default_image = get_field( 'btw__brand_fields__default_image', 'option' );

        $this->render_attrs = $this->get_render_attributes( $args['render_attrs'] ?? [] );

        // foreach( $this->get_atf_post() as $key => $value ){
        //     $this->$key = $value;
        // }

        $this->atf_post = $this->get_atf_post();

    }


    protected function get_image_srcsets( $image_srcsets = [] ){
        
        /**
         * IF atf post is overlay, image ratio is always square 1:1
         */
        if( $this->item['atf__is_overlay'] ?? 0 ){

            $image_srcsets = array(
                array(
                    'image_size'   => 'large_square',
                    'media_query'  => '(max-width: 767px )',
                    'mobile'       => true,
                ),
                array(
                    'image_size'  => 'large_square',
                    'default'     => true,
                ),
            );

        }

        /**
         * If an image_srcsets argument is set, return the $image_srcsets
         */
        if( $image_srcsets ){
            return $image_srcsets;

        }else{
            
            $image_srcsets = array(
                array(
                    'image_size'   => 'medium_horizontal',
                    'media_query'  => '(max-width: 767px )',
                ),
                array(
                    'image_size'  => 'medium_horizontal',
                    'default'     => true,
                ),
            );

        }

        return $image_srcsets;
    }


    /**
     * Get render attributes
     */
    protected function get_render_attributes( $args ){

        $default_args = array(
            'template_name'        => 'default',
            'img_type'             => '',
            'article_font'         => 'article-s-main-title',
            'show_date'            => false,
            'small_article_mobile' => false,
            'lazyload'             => true,
        );


        // article_type can be type of string
        if( !empty( $args['article_type'] ) && gettype( $args['article_type'] ) == 'string' ){
            $args['article_type'] = [
                'overlay' => $args['article_type'],
                'default' => $args['article_type'],
            ];
        }

        return array_merge( $default_args, $args );
    }




	/**
	 * @return array $atf_posts {
	 * 		The array of posts.
	 *
	 * 		@type array $i {
	 * 			The Indexed Array of $atf_post.
	 *
	 * 			@type bool $is_advertorial. Default is false.
	 * 			@type WP_Post $wp_post. Default is empty array.
	 *			@type string $impressions_url
	 *			@type string $text_align. Default is left.
	 *			@type bool $is_overlay
	 *			@type string $bg_color. Default is transparent.
	 *			@type bool $is_dark_mode
	 *			@type bool $is_sponsored
	 *			@type bool $is_podcast
	 *			@type string $caption. The primary term anchor html or the advertorial caption string.
	 * 			@type string $supertitle. The supertitle or the Sponsored keyword if is sponsored.
	 * 			@type array $post_titles{
	 *				@type string $desktop
	 *				@type empty|string $mobile.
	 * 			}
	 *      	@type string $post_link
	 *      	@type array $attachments{
	 * 				@type object $desktop{
	 *					@property int $id. Attachment ID.
	 * 					@property string $url. Full URL.
	 * 					@property string $alt. Alt text. Default is the post_title or advertorial_title
	 *					@property int $class
	 *					@property int $credits_html
	 * 				}
	 * 				@type empty|object $mobile{
	 *					@property int $id. Attachment ID.
	 * 					@property string $url. Full URL.
	 * 					@property string $alt. Alt text. Default is the post_title or advertorial_title
	 *					@property int $class
	 *					@property int $credits_html
	 * 				}
	 * 			}
	 * 		}
	 * }
	 */
    public function get_atf_post(){

        global $btw_log_posts;

        $is_advertorial = $this->item['atf__is_advertorial'];

		if( $is_advertorial ){
			$wp_post = null;
		}else{
			$wp_post = $this->item['atf__post'][0] ?? null;
		}

        /**
         * Log wp post to btw_log_posts
         */
        if( $wp_post ){
            $btw_log_posts->log_post( $wp_post->ID );
        }

        $atf_post = [
            'is_advertorial'    => $is_advertorial,
            'wp_post'  	        => $wp_post,
            'impressions_url'  	=> $this->item['atf__post__impressions_url'],
        ];



		/**
		 * aft post video
		 */
		$atf_post['is_video'] = $is_advertorial
			? $this->item['atf__advertorial__is_video']
			: $atf_post['wp_post'] && $atf_post['wp_post']->post_type == 'video';


        // aft post caption
		if($is_advertorial){
			$atf_post['caption'] = $this->item['atf__caption'];

		}else{
			$atf_post['caption'] = $this->item['atf__caption']
				?: btw_get_primary_term_anchor_html( btw_get_post_primary_term($wp_post, $this->primary_term) );
		}

        /** 
         * aft post title
         * If no teaser title is used, wrap default post title on <strong>
         */
        $atf_post['post_titles']['desktop'] =
            trim( wp_kses( $this->item['atf__post_title'], $this->allowed_html_tags_for_titles ) )
            ?: '<strong>' . get_the_title( $wp_post ) . '</strong>';

        /** 
         * aft post title raw
         * Raw teaser title / post title used on rest / xml apis
         */
        $atf_post['post_titles']['desktop_raw'] =
            trim(wp_strip_all_tags( $this->item['atf__post_title'] ) )
            ?: $wp_post->post_title;


        /**
         * Escaped post title. Used in title attribute on anchor links
         */
        $atf_post['esc_post_title'] = esc_attr( wp_strip_all_tags( $atf_post['post_titles']['desktop'] ) );


        /** 
         * aft post mobile title
         * Fallbacks to post title
         */
        $atf_post['post_titles']['mobile'] = $this->item['atf__post_title_mobile']
            ? wp_kses( $this->item['atf__post_title_mobile'], $this->allowed_html_tags_for_titles )
            : '';


        /** 
         * aft post link
         * 
         * @todo BE ensure that if is advertorial atf__advertorial__link is required
         */
        $atf_post['post_link'] = $is_advertorial
            ? $this->item['atf__advertorial__link']
            : get_permalink( $wp_post );

        
        /**
         * atf featured image id
         */
        $wp_post_featured_image_id = $this->get_wp_post_featured_image_id( $wp_post );

        $atf_post['featured_image_id'] = $is_advertorial
            ? $this->item['atf__image']
            : ( $this->item['atf__image'] ?: $wp_post_featured_image_id );



        /**
         * Atf post attachment data
         */
         $attachment_data = $this->get_attachment_data(
            atf_teaser_image_attachment_id: $this->item['atf__image'],
            atf_mobile_teaser_image_attachment_id: $this->item['atf__mobile_image'],
            default_alt_text: $atf_post['esc_post_title'],
            wp_post: $wp_post
         );

         
         /**
          * Attachment html, render as <picture> html
          */
         $atf_post['attachment_picture_html'] = btw_is_amp_endpoint()
            ? $this->get_amp_attachment_html( $attachment_data )
            : $this->get_attachment_html( $attachment_data, $this->render_attrs['lazyload'] );



        /**
         * Get attachment background urls. 
        * It can be used instead of attachment <picture> html
        */
        $atf_post['attachment_background'] = $this->get_attachment_background_urls( $attachment_data );


        /**
         * Get container base classes
         */
        $atf_post['container_classes'] = $this->get_container_classes( $atf_post );


        return $atf_post;

    }


    /**
     * Get attachment data.
     * 
     * @param int $atf_teaser_image_attachment_id
     * @param int $atf_mobile_teaser_image_attachment_id
     * @param string $default_alt_text
     * @param WP_Post|null $wp_post
     * 
     * @return array
     */
    protected function get_attachment_data( $atf_teaser_image_attachment_id, $atf_mobile_teaser_image_attachment_id, $default_alt_text, $wp_post ){
        
        /**
         * Attachment data array format
         */
         $attachment_data = [
            'alt_text'  => '',
            'url'       => '',
			'full_url'  => '',
            'sources'   => [],
         ];

         $attachment_id = $atf_teaser_image_attachment_id ?: $this->get_wp_post_featured_image_id( $wp_post );

         $image_srcsets = $this->image_srcsets;

        /**
         * Attachment alt text
         */
        $alt_text = get_post_meta($attachment_id, '_wp_attachment_image_alt', true) ?: $default_alt_text;
        $attachment_data['alt_text'] = esc_attr( $alt_text );


         /**
          * Get attachment picture img
          */
         $default_srcset = array_filter( $image_srcsets, function( $image_srcset ){
            return !empty( $image_srcset['default'] );
         });

         $default_srcset = $default_srcset ? array_shift( $default_srcset ) : array_shift( $image_srcsets );

        /**
         *  If an atf__image is set, check the mime type.
         * if is gif, display it on full size
        */
         if( $atf_teaser_image_attachment_id && get_post_mime_type( $atf_teaser_image_attachment_id )  == 'image/gif' ){
            $default_srcset['image_size'] = 'full';
         }

         $default_attachment_image_src = wp_get_attachment_image_src( $attachment_id, $default_srcset['image_size'] );
         $attachment_data['url'] = $default_attachment_image_src['0'];

		$default_attachment_image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
		$attachment_data['full_url'] = $default_attachment_image_src['0'];

         /**
         * Get attachment sources
         */
         foreach( $image_srcsets as $image_srcset ){

            if( empty( $image_srcset['media_query'] ) ) continue;

            /**
             * if atf_teaser_image_attachment_id is set
             * The size will be full for gifs and $image_srcset['image_size'] for all other mime types
             **/
            if( $atf_teaser_image_attachment_id && get_post_mime_type( $atf_teaser_image_attachment_id ) == 'image/gif' ){
                $image_srcset['image_size'] = 'full';
            }

            $attachment_image_src = wp_get_attachment_image_src( $attachment_id, $image_srcset['image_size'] );

            /**
             * If srcset is for mobile and atf post has mobile teeser image (atf_mobile_teaser_image_attachment_id)
             * source srcset will be the mobile image
             * The size will be full for gifs and $image_srcset['image_size'] for all other mime types
             */
            if( !empty( $image_srcset['mobile'] ) && $atf_mobile_teaser_image_attachment_id ){
                
                $teaser_mobile_image_size = get_post_mime_type( $atf_mobile_teaser_image_attachment_id ) == 'image/gif'
                    ? 'full'
                    : $image_srcset['image_size'];

                $attachment_image_src = wp_get_attachment_image_src( $atf_mobile_teaser_image_attachment_id, $teaser_mobile_image_size );

            }

			 $attachment_image_src = $attachment_image_src[0];

             $attachment_data['sources'][] = [
                'media_query' => $image_srcset['media_query'],
                'url'         => $attachment_image_src,
                'mobile'      => true,
             ];
         }

         return $attachment_data;
    }


    /**
     * Get attachment backgound urls.
     * It can be used instead of attachment <picture> html
     * 
     * @param array, $attachment_data
     * 
     * @return array
     */
    protected function get_attachment_background_urls( $attachment_data ){

        $mobile_url = array_filter( $attachment_data['sources'], function( $source ){
            return $source['mobile'] === true;
        });

        $mobile_url = array_shift( $mobile_url );

        // if no mobile url exists, set the desktop url
        $mobile_url = $mobile_url['url'] ?? $attachment_data['url'];

        return [
			'full'    => $attachment_data['full_url'],
            'desktop' => $attachment_data['url'],
            'mobile'  => $mobile_url,
        ];
    }

    /**
     * Get attachment html.
     * Return <picture> html tag
     * 
     * @param array, $attachment_data
     * @param bool, $lazyload
     * 
     * @return string
     */
    protected function get_attachment_html( $attachment_data, $lazyload ){
        
        $attachment_html = [];

        $img_class = $lazyload ? 'class="lazyload"' : '';
        $img_src = "src=\"{$attachment_data['url']}\"";
        $img_src = $lazyload ? 'data-' . $img_src : $img_src;

        $attachment_html[] = "<img decoding=\"async\" loading=\"lazy\" {$img_class} {$img_src} alt=\"{$attachment_data['alt_text']}\" />";

        /**
         * Get attachment picture sources
         */
         foreach( $attachment_data['sources'] as $source ){
            
            $attachment_html[] = "<source media=\"{$source['media_query']}\" srcset=\"{$source['url']}\" />";

         }

         /**
          * return picture tag
          */
         return '<picture>' . implode( "\n", array_reverse( $attachment_html ) ) . '</picture>';
    }


    /**
     * Get AMP attachment html
     * Render a single <img> tag
     * 
     * @param array
     * 
     * @return string
     */
    protected function get_amp_attachment_html( $attachment_data ){

        $mobile_url =  array_filter($attachment_data['sources'], function ($source) {
            return $source['mobile'] === true;
        });

        $mobile_url = array_shift( $mobile_url );

        // if no mobile url exists, set the desktop url
        $mobile_url = $mobile_url['url'] ?? $attachment_data['url'];

        return "<img src=\"{$mobile_url}\" alt=\"{$attachment_data['alt_text']}\" />";
    }


    /**
     * Return wp_post featured image id
     * 
     * @param WP_Post, $wp_post
     * 
     * @return int
     */
    protected function get_wp_post_featured_image_id( $wp_post ){

        $wp_post_featured_image_id = get_post_thumbnail_id( $wp_post ) ?: $this->default_image['ID'];

        return apply_filters( 'btw/atf_post/wp_post_featured_image_id', $wp_post_featured_image_id, $wp_post, $this->group_template );
    }



    protected function get_container_classes( $atf_post ){

        $classes = [
            'article_card',
			"article_{$this->render_attrs['template_name']}"
        ];


        // aft post has teaser mobile title
        if( $atf_post['post_titles']['mobile'] ){
            $classes[] = 'with_mobile_title';
        }


		// atf_post is video or is_podcast
		if( $atf_post['is_video'] ){
			$classes[] = 'play_article';
		}

		// extra class
		if( $this->render_attrs['extra_class'] ?? null ){
			$classes = array_merge( $classes, (array)$this->render_attrs['extra_class'] );
		}


        return $classes;

    }


    /**
     * Render atf post
     * template dir is: template/template-parts/post_content
     * 
     * 
     * @param array args
     */
    public function render(){

        /**
         *  Default template part name is: atf_post ( .php )
         */
        $template_part = "template-parts/modules/article_{$this->render_attrs['template_name']}";

		$template_part_args = array_merge($this->atf_post, [
			'index'					   => $this->index,
			'section_id'			   => $this->section_id,
			'truncate'                 => $this->render_attrs['truncate'] ?? null,
		]);

        btw_get_template_part( $template_part, $template_part_args );
    }

    /**
     * Aft post rest api data
     */
    public function get_base_api_data(){

        $atf_post_title = wp_specialchars_decode( $this->atf_post['post_titles']['desktop_raw'], ENT_QUOTES );
        
        $atf_post_link = $this->atf_post['post_link'];
        $atf_attachment_sizes = btw_get_attachment_sizes( $this->atf_post['featured_image_id'] ); 

        if( $this->atf_post['is_advertorial'] ){
            return array(
                'advertorial_title'                 => $atf_post_title,
                'advertorial_caption'               => $this->atf_post['caption'],
                'advertorial_url'                   => $atf_post_link,
                'advertorial_image'                 => $atf_attachment_sizes['full'],
                'advertorial_image_available_sizes' => $atf_attachment_sizes,
            );
        }

        $wp_post = $this->atf_post['wp_post'];
        $atf_post_primary_category = btw_get_post_primary_category( $wp_post );
        $post_author = btw_get_post_author( $wp_post );

        $atf_post_data = array(
            'post_title'                 => $atf_post_title,
            'post_image'                 => $atf_attachment_sizes['full'],
            'post_image_available_sizes' => $atf_attachment_sizes,
            'post_url'                   => $atf_post_link,
            'primary_category'           => $atf_post_primary_category->name,
            'post_date'                  => $wp_post->post_date,
            'post_categories'            => wp_get_post_categories($wp_post->ID, array('fields' => 'names')),
            'post_tags'                  => wp_list_pluck(get_the_tags($wp_post->ID), 'name'),
            'post_author'                => $post_author->display_name ?? '',
            'post_byline'                => $post_author->byline ?? '',
        );

        return apply_filters( 'btw/base_api/atf_post/post_data', $atf_post_data, $this->atf_post, $wp_post, $this->group_template );

    }

}
