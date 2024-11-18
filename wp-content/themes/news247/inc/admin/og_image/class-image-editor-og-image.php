<?php

require_once ABSPATH . '/wp-includes/class-wp-image-editor.php';
require_once ABSPATH . '/wp-includes/class-wp-image-editor-gd.php';

/**
 * Watermark image generation editor
 * Add a watermark on an image using imagecopy
 */
class News247_Image_Editor_Watermark extends WP_Image_Editor_GD {

    /**
     * @param string, $file
     */
    public function __construct( $file ){

        parent::__construct( $file );
        
        $this->load();
    }


    /**
     * Create and return a transparent canvas to merge the og image with the watermark
     * Width and height are the dimenisions of the og image
     * 
     * @param string, $width
     * @param string, $height
     * 
     * @return GdImage, $canvas
     */
    private function create_canvas( $width, $height ){

        $canvas = imagecreatetruecolor( $width, $height );

        //make sure the transparency information is saved
        imagesavealpha( $canvas, true );

        //create a fully transparent background (127 means fully transparent)
        $transparent_background = imagecolorallocatealpha( $canvas, 0, 0, 0, 127 );

        //fill the image with a transparent background
        imagefill( $canvas, 0, 0, $transparent_background );

        return $canvas;
    }


    /**
     * Resize watermark if background image is small
     * 
     * @param array, $watermark_image_data
     * 
     * @return array|bool, an array with data of resized watermark or false on failure
     */
    private function resize_watermark( $watermark_image_data ){

        [ 'width' => $width, 'height' => $height, 'gd_image' => $gd_image ] = $watermark_image_data;

        $dims = image_resize_dimensions( $width, $height, ceil( $width / 2 ), ceil( $height / 2 ), true );

        list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

        $resized_watermark = $this->create_canvas( $width, $height );

       	imagecopyresampled(
            $resized_watermark,
            $gd_image,
            $dst_x,
            $dst_y,
            $src_x,
            $src_y,
            $dst_w,
            $dst_h,
            $src_w,
            $src_h
        );

        if( !is_gd_image( $resized_watermark ) ){
            return false;
        }

        return [
            'width'    => $dst_w,
            'height'   => $dst_h,
            'gd_image' => $resized_watermark,
        ];
    }   


    /**
     * Image merge using imagecopy
     * 
     * @param array, $size data containing width, height and crop
     * @param array, $watermark_image_data
     * @param int, $post id
     */
	public function apply_watermark( $size_data, $watermark_image_data, $post_id ){

        if( !$watermark_image_data ){
            return false;
        }

        [ $width, $height, $crop, $watermark_padding ] = $size_data;

        // resize image to og image size
        $resized = $this->_resize( $width, $height, $crop );

        // remove image from memory
        imagedestroy( $this->image );

         if( !is_gd_image( $resized ) ){
            $this->image = null;         
            return false;
         }

         // add resized image on memory
        $this->image = $resized;

        if( $width != 1200 ){
            $watermark_image_data = $this->resize_watermark( $watermark_image_data );

            if( $watermark_image_data === false ){
                return false;
            }
        }

        // create a transparent canvas
        $canvas = $this->create_canvas( $width, $height );

        // add background image on canvas
        $copied = imagecopy(
            $canvas,
            $this->image,
            0,
            0,
            0,
            0,
            $width,
            $height,
        );

        if( $copied === false ){
            imagedestroy( $this->image );
            $this->image = null;
            return false;
        }

        // add watermark on canvas
        $copied = imagecopy(
            $canvas,
            $watermark_image_data['gd_image'],
            $width - $watermark_image_data['width'] - $watermark_padding,
            $height - $watermark_image_data['height'] - $watermark_padding,
            0,
            0,
            $watermark_image_data['width'],
            $watermark_image_data['height'],
        );

        if( $copied === false ){
            imagedestroy( $this->image );
            $this->image = null;
            return false;
        }

       


        imagepng( $canvas, $this->generate_filename( '-og-image-' . $post_id ), 0 );
        imagedestroy( $this->image );
		$this->image = $canvas;

        $saved = $this->save( $this->generate_filename( '-og-image-' . $post_id ) );

        $this->__destruct();

        return $saved;

	}
}