<?php
// Template Name: Magazine White Page + Embed Code
// Template Post Type: post

get_header(null, ['hide_header' => true]);

while (have_posts()) : the_post();

	btw_get_post_impressions_url();

	$embed_code = get_field('btw__article_fields__embed_code');

    echo $embed_code;

endwhile;
?>
	
	</div> <!-- .global_wrapper -->

</body>

</html>
