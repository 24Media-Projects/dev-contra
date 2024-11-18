<?php
	get_header();

	while( have_posts() ): the_post();

?>

<article id="theArticle" class="single_article__wrapper">

	<div class="wrapper clear">
		<?php echo get_the_password_form( $post ); ?>
	</div>

</article>


<?php
	endwhile;
	get_footer();
?>
