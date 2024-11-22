<?php 

add_filter( 'btw/editor_modules/embed_code/admin/providers', function( $providers ){

    $providers['flourish'] = 'Flourish';
    $providers['datawrapper'] = 'Datawrapper';
    $providers['ert'] = 'Ert';
    $providers['ceros'] = 'Ceros';
    

    return $providers;
});




add_action('init', function(){
	foreach(get_supported_single_post_types() as $post_type){
		add_filter("views_edit-$post_type", function($views){
			if( $views['yoast_cornerstone'] ?? 0 ) unset($views['yoast_cornerstone']);
			if( $views['yoast_orphaned'] ?? 0 ) unset($views['yoast_orphaned']);
			if( $views['yoast_stale-cornerstone-content'] ?? 0 ) unset($views['yoast_stale-cornerstone-content']);
			return $views;
		}, 200);
	}

});




add_filter('admin_post_thumbnail_html', function($content){

	if( !in_array( get_post_type(), get_supported_single_post_types() ) ) return $content;


	$content .= '<p>Προτεινόμενη διάσταση φωτογραφίας: 1600px πλάτος.</p>';
	$content .= '<p>Ελάχιστη διάσταση φωτογραφίας 1200x800px</p>';

	return $content;
});