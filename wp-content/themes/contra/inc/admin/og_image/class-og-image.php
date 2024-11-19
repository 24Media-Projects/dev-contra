<?php

include_once( 'class-image-editor-og-image.php' );

/**
 * Og image generator
 * On post save get the og image ( featured image / yoast og image ) 
 * and generate it with Contra_Image_Editor_Watermark
 * 
 */
class Contra_Og_Image{

    /**
     * @var int, post id
     */
    private $post_id;

    /**
     * @var array, post types that supports og image generation
     */
    private $supported_post_types = [
        'post',
        'page',
        'video',
        'skitsa'
    ];

    /**
     * Og sizes. Same ratio on 2 different sizes
     * @var array
     */
    private $og_sizes = array(
        'og_image' => array(
            'width'  => 1200,
            'height' => 630,
            'crop'   => true,
            'watermark_padding' => 35,
        ),
        'og_image_small' => array(
            'width'  => 600,
            'height' => 315,
            'crop'   => true,
            'watermark_padding' => 18,
        )
    );


    public function __construct(){
        
        add_filter( 'save_post', [ $this, 'init' ], 99, 2 );

    }

    /**
     * Get watermark data. Watermark logo is a acf option field on brand settings
     * Supports only a jpeg mime type. Can be extended to support png mime type.
     * A valid GD Image have to be returned ( imagecreatefrompng ) in order to use the Contra_Image_Editor_Watermark
     * 
     * @return array
     */
    private function get_watermark_data(){
        $watermark_logo = get_field('btw__brand_fields__watermark_logo', 'option' );

        if( get_post_mime_type( $watermark_logo['ID'] ) != 'image/png' ){
            return null;
        }

        $attached_file = get_attached_file( $watermark_logo['ID'], true );
        $watermark_logo_attachment_src = wp_get_attachment_image_src( $watermark_logo['ID'] );

        return [
            'gd_image' => @imagecreatefrompng( $attached_file ),
            'width'  => $watermark_logo_attachment_src['1'],
            'height' => $watermark_logo_attachment_src['2'],
        ];
    }

    /**
     * Get original og image data. 
     * Yoast og image or post featured image as fallback
     * 
     * @param int, $post_id
     * 
     * @return array
     */
    private function get_post_original_og_image_data( $post_id ){

        global $wpdb;

        $post_og_image_id = $wpdb->get_var(
            "SELECT open_graph_image_id
             FROM {$wpdb->prefix}yoast_indexable
             WHERE object_id = '{$post_id}' AND object_type = 'post' AND open_graph_image_source = 'set-by-user'"
        );

        if( !$post_og_image_id ){
            $post_og_image_id = get_post_thumbnail_id( $post_id );
        }

        if( !$post_og_image_id ){
            return null;
        }

        $og_image_attachment_src = wp_get_attachment_image_src( $post_og_image_id, 'full' );

        $og_size_data = $og_image_attachment_src['1'] >= 1200
            ? $this->og_sizes['og_image']
            : $this->og_sizes['og_image_small'];
        
        return [
            'attachment_id' => $post_og_image_id,
            'file'          => get_attached_file( $post_og_image_id, true ),
            'mime_type'     => get_post_mime_type( $post_og_image_id ),
            'size_data'     => array_values( $og_size_data ),
        ];
    }


    /**
     * Generate image using Contra_Image_Editor_Watermark
     * 
     * @see Contra_Image_Editor_Watermark
     * 
     * @param array, $image_data
     * 
     * @return string|bool, Generated attachment url or false on failure
     */
    private function generate_image( $image_data ){

        $uploads_dir = wp_upload_dir();

        try{
            
            $watermark_data = self::get_watermark_data();

            if( !$watermark_data || $watermark_data['gd_image'] === false ){
                return false;
            }

            $og_image_gd = new Contra_Image_Editor_Watermark( $image_data['file'] );
            if( is_wp_error( $og_image_gd ) ){
                return false;
            }

            $generated_watermarked_image_data = $og_image_gd->apply_watermark( $image_data['size_data'], $watermark_data, $this->post_id );

            if( $generated_watermarked_image_data === false ){
                return false;
            }

            return str_replace(
                $uploads_dir['basedir'],
                $uploads_dir['baseurl'],
                $generated_watermarked_image_data['path']
            );

        }catch( Exception $e ){
            return false;
        }

    }


    /**
     * Check if image generation can proceed
     * Rules:
     * 1. Post have to be publish / draft
     * 2. The og image has changed ( look into postmeta btw_post_generated_og_image which holds data about the generated og image) 
     * 
     * If image generation is ok, add / update postmeta btw_post_generated_og_image
     * 
     * @param int, $post_id
     * @param WP_Post, $post
     */
    public function init( $post_id, $post ){

        if (
            wp_is_post_revision( $post_id )
            || !current_user_can( 'edit_post', $post_id )
            || !in_array( get_post_type( $post_id ), $this->supported_post_types )
            || ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
        ) {
            return;
        }

        $this->post_id = $post_id;

        $post_original_og_image_data = self::get_post_original_og_image_data( $post_id );
        $post_generated_og_image_data = get_post_meta( $post_id, 'btw_post_generated_og_image', true );

        // if no featured image or custom og image is set on the post, return
        if( !$post_original_og_image_data ){
            return;
        }

        // if nothing changed, return
        if( $post_generated_og_image_data
            && $post_original_og_image_data['attachment_id'] == $post_generated_og_image_data['attachment_id']
        ){
            return;
        }

        $generated_image_url = self::generate_image( $post_original_og_image_data );

        if( $generated_image_url === false ){
            return;
        }

        $og_image_post_meta = array(
            'attachment_id' => $post_original_og_image_data['attachment_id'],
            'attachment_url' => $generated_image_url,
        );

        if( !add_post_meta( $post_id, 'btw_post_generated_og_image', $og_image_post_meta, true ) ){
            update_post_meta( $post_id, 'btw_post_generated_og_image', $og_image_post_meta );
        }
    }
   
}   

$contra_og_image = new Contra_Og_Image();
