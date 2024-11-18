<?php
// Template Name: White Page + Embed Code
// Template Post Type: post, page

get_header(null, ['hide_header' => true]);

while (have_posts()) : the_post();

	btw_get_post_impressions_url();

	echo get_field('btw__article_fields__embed_code');

endwhile;

/* instead of get_footer(); */
wp_footer();
?>

</body>

</html>
