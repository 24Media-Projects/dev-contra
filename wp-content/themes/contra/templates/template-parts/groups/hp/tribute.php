<?php
$group_settings = btw_get_group_settings();
extract($group_settings);


$atf_posts = $posts_source ? null : get_field( 'btw__group_fields__hp__template__tribute__post_selection' );
$btw_post = btw_get_group_posts(1, $atf_posts, $posts_source)[0];

$footer_image = get_field('btw__group_fields__hp__template__tribute__footer_image');
$footer_text = get_field('btw__group_fields__hp__template__tribute__footer_text');


$section_classes = [
    'home_wrapper',
    'term_basic_section',
    'tribute_basic__section',
    $is_sponsored ? 'sponsored' : '',
    $bg_color ? 'with_bg_color' : '',
	$is_dark_mode ? 'is_dark_mode' : '',
];


?>

<section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>"  style="<?php echo $style ?? ''; ?>">

    <?php btw_get_impressions_url($impression_url); ?>

    <?php btw_get_template_part('template-parts/group_header', $group_settings); ?>


    <div class="article_container">
        <?php
		$btw_post->set_args([
			'render_attrs'      => [
				'article_type'  => 'tribute',
				'lazyload'		=> false,
                'extra_variables'   => [
                    'footer_image'  => $footer_image,
                    'footer_text'   => $footer_text,
                ],
			],
			//'image_srcsets' => array()
		]);
		$btw_post->render();
        ?>


		<?php if( !empty($is_sponsored) ): ?>

            <div class="article_card__sponsor">

				<?php maybe_print_anchor_opening_tag($sponsor_logo_click_url); ?>
                <img class="article_card__sponsor_mobile" src="<?php echo $sponsor_logo['url']; ?>" alt="<?php echo $sponsor_logo['alt']; ?>">
                <img class="article_card__sponsor_desktop" src="<?php echo $sponsor_logo_alt['url']; ?>" alt="<?php echo $sponsor_logo_alt['alt']; ?>">
				<?php maybe_print_anchor_closing_tag($sponsor_logo_click_url); ?>

            </div>

		<?php endif; ?>

    </div>


</section>



