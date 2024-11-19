<?php
// Template Name: Header + Footer + Embed Code
// Template Post Type: post, page
get_header();


while (have_posts()) : the_post();
	$post__bgColor = get_field('btw__global_fields__bg_color');

	$single_articleStyle = '';
	if ($post__bgColor) {
		$single_articleStyle = 'background-color: ' . $post__bgColor;
	}
?>


	<div class="container" style="<?php echo $single_articleStyle ?>">
		<div class="category__wrapper">

			<?php btw_get_post_impressions_url(); ?>

			<?php if (get_field('btw__article_fields__show_title')) : ?>
				<h1 class="section__title page__title"><?php echo remove_punctuation(get_the_title()); ?></h1>
			<?php endif; ?>


			<?php
			$embed_codes = is_singular('page') ? btw_return_template('templates/template-parts/groups/home/embed_codes', [
				'section_id' => 'embed_codes',
				'section_title' => get_the_title(),
				'heading' => 'h1',
			]) : '';


			if (trim($embed_codes)) {
				echo $embed_codes;
			} else {
				echo get_field('btw__article_fields__embed_code');
			}
			?>

		</div>
	</div>
<?php
endwhile;

get_footer();
?>