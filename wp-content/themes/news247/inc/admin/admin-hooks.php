<?php 

add_filter( 'btw/editor_modules/embed_code/admin/providers', function( $providers ){

    $providers['flourish'] = 'Flourish';
    $providers['datawrapper'] = 'Datawrapper';
    $providers['ert'] = 'Ert';
    $providers['ceros'] = 'Ceros';
    

    return $providers;
});


add_filter( 'btw/customer_review/supported_post_types', function( $post_types ){

    return [
        'post',
        'video',
        'skitsa',
    ];
});

/*
add_filter( 'admin_post_thumbnail_html', function ( $content, $post_id, $thumbnail_id ){
	if( get_post_type($post_id) != 'post' ) return $content;
	$help_text = '';
	$help_text = '<p>' . $help_text . '</p>';
	return $help_text . $content;
}, 10, 3 );
*/