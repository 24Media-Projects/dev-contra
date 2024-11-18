<?php
// Template Name: Magazine Header + Footer + Embed Code + Full width
// Template Post Type: post

get_header();


while (have_posts()) : the_post();

    btw_get_post_impressions_url();

    if( get_field('btw__article_fields__show_title') ){
		echo '<h1 class="section__title page__title">' . remove_punctuation( get_the_title() ) . '</h1>';
	}

    echo get_field('btw__article_fields__embed_code');

endwhile;

get_footer();
?>