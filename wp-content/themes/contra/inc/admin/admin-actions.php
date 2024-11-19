<?php

/**
 * Print categrory ids to use in js
 */
add_action( 'admin_enqueue_scripts', function (){

    $current_screen = get_current_screen();

    if( $current_screen->base != 'post' ){
        return;
    }

    $parent_categories = get_categories([
        'parent' => 0,
        'hide_empty' => false,
    ]);

    $categories = [];

    foreach( $parent_categories as $parent_category ){

        $subcategories = get_term_children( $parent_category->term_id, 'category' );

        $categories[ $parent_category->slug ] = array_merge( [ $parent_category->term_id ], $subcategories );
    }

    ?>

    <script>
        var BtwPostCategories = '<?php echo json_encode($categories); ?>';
        var MagazineCategoryId = <?php echo btw_get_magazine_category_id(); ?>;
    </script>

<?php }, 5 );

add_action('wp_ajax_ajax_media_details', 'ajax_media_details');
function ajax_media_details(){

	if ( !wp_verify_nonce( $_REQUEST['wpEditorNonce'] ?? '', 'btw-wp-editor-nonce')) {
		wp_send_json_error(null, 403);
	}

	$thumbnail_id = $_REQUEST['thumbnail_id'] ?? 0;

	if( $data = wp_get_attachment_image_src($thumbnail_id, 'full') ){
		wp_send_json(['media_details' => [
			'url'        => $data[0],
			'width'      => $data[1],
			'height'     => $data[2],
			'is_resized' => $data[3]
		]]);
	}else{
		wp_send_json_error(null, 403);
	}

}