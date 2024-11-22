<?php
$group_settings = btw_get_group_settings();
extract($group_settings);


$atf_posts = $posts_source ? null : get_field( 'btw__group_fields__hp__template__3cols__posts_selection' );
$btw_posts = btw_get_group_posts(3, $atf_posts, $posts_source);



$section_classes = [
	$group_template,
];

?>

<section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>">

	<?php btw_get_impressions_url($impressions_url); ?>

	<?php foreach($btw_posts as $btw_post): ?>
        <?php
        $btw_post->set_args([
            'render_attrs'      => [

            ],
            //'image_srcsets' => array()
        ]);
        $btw_post->render();
        ?>
    <?php endforeach; ?>

</section>

