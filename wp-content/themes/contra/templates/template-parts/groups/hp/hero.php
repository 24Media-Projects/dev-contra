<?php
$group_settings = btw_get_group_settings();
extract($group_settings);


$atf_posts = $posts_source ? null : get_field( 'btw__group_fields__hp__template__hero__post_selection' );
$btw_post = btw_get_group_posts(1, $atf_posts, $posts_source)[0];



$section_classes = [
	$group_template,
];

?>

<section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

	<?php
	$btw_post->set_args([
		'render_attrs'      => [
			'article_type'  => 'hero',
			'lazyload'		=> false,
		],
		//'image_srcsets' => array()
	]);
	$btw_post->render();
	?>

</section>

