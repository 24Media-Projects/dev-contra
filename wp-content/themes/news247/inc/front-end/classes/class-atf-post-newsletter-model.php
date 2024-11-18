<?php

class BTW_Atf_Post_Newsletter extends BTW_Atf_Post{

	public function __construct( $args = [] ){
        parent::__construct( $args );

    }
    

    /**
     * Get render attributes
     */
    protected function get_render_attributes( $args ){

        $default_args = array(
            'img_type'             => '',
            'article_font'         => 'article-s-main-title',
            'show_date'            => false,
            'small_article_mobile' => false,
			'show_image'	 	   => true,
			'hide_border_bottom'   => false,
			'title_font'   		   => false,
        );

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

        global $post, $btw_log_posts;

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
            'is_advertorial'     => $is_advertorial,
            'wp_post'  	         => $wp_post,
			'impressions_url'  	 => $this->item['atf__post__impressions_url'],
            'is_dark_mode' 		 => $this->item['atf__is_dark_mode'],
            'bg_color'			 => $this->item['atf__bg_color'] ?: 'transparent',
			'supertitle'		 => $this->item['atf__supertitle'],
			'supertitle_is_link' => $this->item['atf__supertitle_is_link'] ?? true,
			'lead'				 => $this->item['atf__lead'],
        ];

       // is atf advertorial sponsored
       $atf_post['is_sponsored'] = $is_advertorial
           ? $this->item['atf__advertorial__is_sponsored']
           : false;


		/**
		 * aft is video
		 */
		$atf_post['is_video'] = $is_advertorial
			? !empty($this->item['atf__advertorial__is_video'])
			: get_post_type($wp_post) == 'video';


		// atf lead
		if( !$atf_post['is_video'] && !$is_advertorial ){
			$atf_post['lead'] = get_field('btw__global_fields__lead', $wp_post);
		}


        /** 
         * aft post title
         * If no teaser title is used, wrap default post title on <strong>
         */
        $atf_post['post_titles']['desktop'] =
            trim( wp_kses( $this->item['atf__post_title'], $this->allowed_html_tags_for_titles ) )
            ?: '<strong>' . get_the_title( $wp_post ) . '</strong>';


        /**
         * Escaped post title. Used in title attribute on anchor links
         */
        $atf_post['esc_post_title'] = esc_attr( wp_strip_all_tags( $atf_post['post_titles']['desktop'] ) );


        // aft post caption
        $atf_post['caption'] = $this->get_atf_post_caption_html( $atf_post );
        

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

		$atf_post['image_id'] = $is_advertorial
			? $this->item['atf__image']
			: ( $this->item['atf__image'] ?: $wp_post_featured_image_id );


		$atf_post['image_url'] = wp_get_attachment_image_url( $wp_post_featured_image_id, $this->image_srcsets[0]['image_size'] );




        return $atf_post;

    }


    /**
     * Get atf post newsletter caption
     * 
     * @param array $atf_post
     * 
     * @return string
     */
    private function get_atf_post_caption_html( $atf_post ){

        // advertorial
        if( !$atf_post['wp_post'] ){

            $caption_title = remove_punctuation( $this->item['atf__advertorial__caption'] );
            $esc_caption_title =  esc_attr( $caption_title );
            
            $caption_url = $this->item['atf__advertorial__caption_url'] ?: '';

            if( !$caption_url ){
                return $caption_title;
            }

        }else{

			$prim_cat = btw_get_post_primary_term( $atf_post['wp_post'], $this->primary_term );

			$caption_title = $prim_cat->name;
			$esc_caption_title =  esc_attr( $caption_title );
            $caption_url = $prim_cat->term_link;

		}

		return "<a title=\"{$esc_caption_title}\" href=\"{$caption_url}\">{$caption_title}</a>";

	}



    /**
     * Render atf post
     * template dir is: template/template-parts/post_content
     * 
     * 
     * @param array args
     */
    public function render(){

        if( empty( $this->render_attrs['template_name'] ) ){
            $this->render_attrs['template_name'] = 'atf_post_newsletter';
        }

		$template_part = "template-parts/post_content/{$this->render_attrs['template_name']}";


		btw_get_template_part( $template_part, [
            'atf_post'                 => $this->atf_post,
			'index'					   => $this->index,
			'section_id'			   => $this->section_id,
			'article_font'             => $this->render_attrs['article_font'],
			'show_image'               => $this->render_attrs['show_image'],
            'show_date'                => $this->render_attrs['show_date'],
			'hide_border_bottom'       => $this->render_attrs['hide_border_bottom'],
			'title_font'       		   => $this->render_attrs['title_font'],
        ]);
    }


}
