<?php
$group_settings = btw_get_group_settings();
extract($group_settings);

$hero_post = get_field( 'btw__group_fields__hp__template__hero__post_selection' )[0] ?? null;



$section_classes = [
	$group_template,
];

?>

<section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

    <?php
	$atf_post = new BTW_Atf_Post([
        'item'         	 => $hero_post,
        'primary_term' 	 => $primary_term_taxonomy_selection,
        'render_attrs' => [
            'template_name' => 'hero',
            'lazyload'		=> false,
        ]
    ]);

    $atf_post->render();
	?>

</section>

