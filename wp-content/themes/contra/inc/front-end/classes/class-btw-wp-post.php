<?php

class BTW_WP_Post{

	protected $default_image_srcsets = array(
		array(
			'image_size'    => 'small_landscpape',
			'media_query'   => '(max-width: 767px )',
			'mobile'        => true,
		),
		array(
			'image_size'    => 'medium_landscpape',
			'default'       => true,
		),
	);

	/**
	 * @var string
	 */
	protected $primary_term;

	/**
	 * @var WP_Post
	 */
	protected $wp_post;

	protected $render_attrs;

	/**
	 * @var false|int
	 */
	protected $index;

	protected $post_data;

	/**
	 * @var array|array[]|mixed
	 */
	protected $image_srcsets;

	protected $object_settings;

	protected $group_template;

	public function __construct( $wp_post, $btw_log_posts = true ){

		$this->wp_post = $wp_post;
		if( !$this->wp_post ) return;

		/**
		 * Log wp post to btw_log_posts
		 */
		if($btw_log_posts){
			global $btw_log_posts;
			$btw_log_posts && $btw_log_posts->log_post( $wp_post->ID );
		}


	}

	public function set_args( $args = [] )
	{
		$this->primary_term =  $args['primary_term'] ?? 'category';

		$this->index = $args['index'] ?? false;
		$this->image_srcsets = $args['image_srcsets'] ?? $this->default_image_srcsets;

		$this->object_settings = $args['object_settings'] ?? [];
		$this->set_render_attributes( $args['render_attrs'] ?? [] );

		global $group_template;
		$this->group_template = $group_template;

		$this->post_data = $this->get_post_data();

	}

	/**
	 * Set render attributes
	 */
	protected function set_render_attributes( $args ){

		$default_args = array(
			'article_type'		=> 'default',
			'lazyload'			=> true,

			'columns'			=> 1,
			'tab_columns'		=> 0,

			'font'				=> 'xs',
			'tab_font'			=> '',
			'lap_font'			=> '',


			'extra_class'		=> [],
			'extra_variables'	=> [],
		);



		$this->render_attrs = array_merge( $default_args, $args );
	}

	protected function get_post_data()
	{

		$caption = btw_get_primary_term_anchor_html( btw_get_post_primary_term( $this->wp_post, $this->primary_term ) );


		$post_attachment = btw_get_post_attachment( post: $this->wp_post, image_srcsets: $this->image_srcsets );


		$post_data = [
			'wp_post'					=> $this->wp_post,
			'impressions_url'			=> '',
			'attachment_picture_html' 	=> $post_attachment->html,
			'featured_image_id'			=> $post_attachment->id,
			'post_link'					=> get_permalink($this->wp_post),
			'post_titles'				=> [
				'desktop'		=> '<strong>' . get_the_title( $this->wp_post ) . '</strong>',
				'mobile'		=> '',
				'desktop_raw'	=> $this->wp_post->post_title
			],
			'esc_post_title'			=> esc_attr( wp_strip_all_tags( $this->wp_post->post_title ) ),
			'caption'					=> $caption,
			'author_html'				=> $this->render_attrs['show_author'] ? btw_return_post_author_html($this->wp_post) : '',
			'is_video'					=> $this->wp_post->post_type == 'video',
		];

		return $post_data;
	}


	protected function get_container_classes()
	{
		$columns_mapping = [
			0 => '',
			1 => 'one',
			2 => 'two',
		];

		if( $this->render_attrs['article_type'] == 'default' ){
			$classes[] = 'article_card';
		}else{
			$classes[] = "article_card__{$this->render_attrs['article_type']}";
		}



		// columns
		if( $columns = $columns_mapping[ $this->render_attrs['columns'] ] ){
			$classes[] = "mob_col__{$columns}";
		}

		if( $tablet_columns = $this->render_attrs['tab_columns'] ){
			$classes[] = "tab_col__{$tablet_columns}";
		}


		// fonts
		if( $font = $this->render_attrs['font'] ){
			$classes[] = "mob_text__{$font}";
		}
		if( $tab_font = $this->render_attrs['tab_font'] ){
			$classes[] = "tab_text__{$tab_font}";
		}

		if( $lap_font = $this->render_attrs['lap_font'] ){
			$classes[] = "lap_text__{$lap_font}";
		}



		$classes = array_merge($classes, (array)$this->render_attrs['extra_class']);

		return $classes;
	}

	/**
	 * Render wp post
	 */
	public function render(){

		$extra_data = [
			'index'				=> $this->index,
			'container_classes'	=> $this->get_container_classes($this->post_data),
			'group_template'	=> $this->group_template,
			'truncate'          => $this->render_attrs['truncate'] ?? null,
		];

		$file_args = array_merge( $this->post_data, $extra_data, $this->render_attrs['extra_variables'] );

		btw_get_template_part( $this->get_module_path(), $file_args);

	}

	/**
	 * If no render_attr article_type argument pass, get default template name article_default.php
	 * If pass render_attr article_type argument, get asked template if exists, else get the article_default.php
	 */
	private function get_module_path()
	{
		$template = "template-parts/modules/article_{$this->render_attrs['article_type']}";

		if( file_exists( get_stylesheet_directory() . '/templates/' . $template . '.php' ) ){
			return $template;
		}elseif( file_exists( get_template_directory() . '/templates/' . $template . '.php' ) ){
			return $template;
		}else{
			return "template-parts/modules/article_default";
		}
	}

	public function get_post_link()
	{
		if( !$this->wp_post ) return false;

		return get_permalink($this->wp_post);

	}

}