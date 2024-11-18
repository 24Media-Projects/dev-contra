<?php

/**
 * 
 * δεν εχει overlay => remove html classes
 * δεν εχει text align => remove html classes
 * podcast φευγει
 * opinions φευγει
 * 
 * ελεγχος για video => play class 
 */
class BTW_Atf_Post_Magazine extends BTW_Atf_Post{

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
            'lazyload'             => true,
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
            'is_advertorial'    => $is_advertorial,
            'wp_post'  	        => $wp_post,
			'sponsor_logo'      => $this->item['atf__sponsor_logo'] ?: [],
			'sponsor_click_url' => $this->item['atf__sponsor_click_url'],
            'impressions_url'  	=> $this->item['atf__post__impressions_url'],
            'is_dark_mode' 		=> $this->item['atf__is_dark_mode'],
            'bg_color'			=> $this->item['atf__bg_color'] ?: 'transparent',
        ];


		// is atf post author
		$atf_post['author'] = $is_advertorial
			? (object)['display_name' => $this->item['atf__advertorial__author']]
			: btw_get_post_author( $wp_post );



		// atf post supertitle
		$atf_post['supertitle'] = $this->item['atf__supertitle'] ?: 'Sponsored';

        /**
         * aft post podcast
         */
        $atf_post['is_podcast'] = btw_is_post_podcast( $wp_post );


		/**
		 * aft post video
		 */
		$atf_post['is_video'] = $is_advertorial
			? $this->item['atf__advertorial__is_video']
			: $atf_post['wp_post'] && $atf_post['wp_post']->post_type == 'video';


        // aft post caption
        $atf_post['caption'] = $is_advertorial
            ? remove_punctuation($this->item['atf__advertorial__caption'])
            : btw_get_primary_term_anchor_html( btw_get_post_primary_term( $wp_post, $this->primary_term ), true );


        /** 
         * aft post title
         * If no teaser title is used, wrap default post title on <strong>
         */
        $atf_post['post_titles']['desktop'] =
            trim( wp_kses( $this->item['atf__post_title'], $this->allowed_html_tags_for_titles ) )
            ?: '<strong>' . get_the_title( $wp_post ) . '</strong>';


        /** 
         * aft post title raw
         * Raw teser title / post title used on rest / xml apis
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
        $atf_post['attachment_picture_html'] = $this->get_attachment_html($attachment_data, $this->render_attrs['lazyload'] );


        /**
         * Get attachment background urls. 
         * It can be used instead of attachment <picture> html
         */
        $atf_post['attachment_background'] = $this->get_attachment_background_urls($attachment_data);


        /**
         * Get container base classes
         */
        $atf_post['container_classes'] = self::get_container_classes( $atf_post );


        return $atf_post;

    }





    protected function get_container_classes( $atf_post ){

        $classes = [
            'article',
        ];


        // aft post has teaser mobile title
        if( $atf_post['post_titles']['mobile'] ){
            $classes[] = 'with_mobile_title';
        }


        // bg class
        if( $atf_post['bg_color'] != 'transparent' ){
            $classes[] = 'basic_article--bg';
        }


        // small article mobile class
        if( $this->render_attrs['small_article_mobile'] ){
            $classes[] = 'basic_article_small';
        }

        // dark mode class
        if( $atf_post['is_dark_mode'] ){
            $classes[] = 'article_darkmode';
        }

		// atf_post is video
		if( $atf_post['is_video'] ||  $atf_post['is_podcast'] ){
			$classes[] = 'play_article';
		}

		// extra class
		if( $this->render_attrs['extra_class'] ?? 0 ){
			if( is_array( $this->render_attrs['extra_class'] ) ){
				$classes = array_merge($classes, $this->render_attrs['extra_class']);
			}else{
				$classes[] = $this->render_attrs['extra_class'];
			}
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

        if( empty( $this->render_attrs['template_name'] ) ){
            $this->render_attrs['template_name'] = 'atf_post_magazine';
        }

		$template_part = "template-parts/post_content/{$this->render_attrs['template_name']}";


		btw_get_template_part( $template_part, [
            'atf_post'                 => $this->atf_post,
			'index'					   => $this->index,
			'section_id'			   => $this->section_id,
            'article_font'             => $this->render_attrs['article_font'],
            'small_article_mobile'     => $this->render_attrs['small_article_mobile'],  // only used in term_basic
            'show_date'                => $this->render_attrs['show_date'],
            'truncate'                 => $this->render_attrs['truncate'] ?? null,
        ]);
    }


}
