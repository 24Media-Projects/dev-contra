<?php

$embed_code = get_field('btw__group_fields__hp__template__embed_code__embed_code');
if( !$embed_code ) return;

$group_settings = btw_get_group_settings();
extract($group_settings);


$section_classes = [
	'article_group',
	'article_group__embed_code',
    $is_dark_mode ? 'is_dark_mode' : '',
];

?>

<section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>" style="<?php if($bg_color) echo "background-color: $bg_color;"; ?>">

	<?php echo btw_get_impressions_url($impressions_url); ?>

	<?php
	/**
	 * Optional, according to $group_settings
	 */
	btw_get_template_part('template-parts/group_header', $group_settings);
	?>

	<div class="embed__container">

		<?php echo $embed_code; ?>

	</div>


</section>