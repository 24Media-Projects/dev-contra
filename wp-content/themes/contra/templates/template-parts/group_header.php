<?php

$heading ??= 'h2';
$section_title_url ??= null;

$container_classes = [
	'group_header',
	!empty($is_sponsored) ? 'with_sponsor_logo' : '',
];
$extra_class ??= [];
$container_classes = array_merge($container_classes, (array)$extra_class);


if( empty($section_title) ) return;


?>
<div class="<?php echo implode(' ', $container_classes); ?>">

	<?php if($section_title): ?>

        <<?php echo $heading; ?> class="section__title">
            <?php maybe_print_anchor_opening_tag($section_title_url); ?>
            <?php echo remove_punctuation($section_title); ?>
            <?php maybe_print_anchor_closing_tag($section_title_url); ?>
        </<?php echo $heading; ?>>

    <?php endif; ?>

</div>