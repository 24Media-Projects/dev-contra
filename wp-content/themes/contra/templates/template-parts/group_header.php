<?php

$heading ??= 'h2';
$section_title_url ??= null;

$container_classes = [
	'group_header',
	!empty($section_header_template) ? "group_header__$section_header_template" : 'group_header__default',
	!empty($section_header_align) ? "align_$section_header_align" : '',
	!empty($section_header_desktop_align) ? "desktop_align_$section_header_desktop_align" : '',
	!empty($section_header_is_reversed) ? 'reversed' : '',
	!empty($is_sponsored) ? 'with_sponsor_logo' : '',
	!empty($section_logo) ? 'with_section_logo' : '',
	!empty($has_margin_bottom) ? 'with_margin_bottom' : '',
	!empty($section_header_bg_images) ? 'with_bg_images' : '',
	!empty($section_header_bg_color) ? 'with_header_bg_color' : '',
	!empty($section_header_is_dark_mode) ? 'is_dark_mode' : '',
];
$extra_class ??= [];
$container_classes = array_merge($container_classes, (array)$extra_class);


$css_rules = [];

if( !empty($section_main_color) ){
	$css_rules[] = "border-color: $section_main_color;";
}

if( !empty($section_header_bg_color) ){
	$css_rules[] = "background-color: $section_header_bg_color;";
}

if( empty($section_header_bg_images) && empty($section_logo) && empty($section_title) ) return;


?>
<div class="<?php echo implode(' ', $container_classes); ?>" style="<?php echo implode(' ', $css_rules); ?>">

	<?php if( !empty($section_header_bg_images) ): ?>

		<?php maybe_print_anchor_opening_tag($section_title_url); ?>
        <picture>
            <source media="(min-width: 768px )" srcset="<?php echo $section_header_bg_images['desktop']['url']; ?>">
            <img decoding="async" loading="lazy" class="lazyload" data-src="<?php echo $section_header_bg_images['mobile']['url']; ?>" alt="<?php echo $section_header_bg_images['mobile']['url']; ?>">
        </picture>
		<?php maybe_print_anchor_closing_tag($section_title_url); ?>


	<?php elseif( !empty($section_logo) ): ?>

        <div class="section__logo">

			<?php maybe_print_anchor_opening_tag($section_title_url); ?>
            <img class="section__logo_mobile" src="<?php echo $section_logo; ?>" alt="<?php echo $section_title; ?>">
            <img class="section__logo_desktop" src="<?php echo $section_desktop_logo; ?>" alt="<?php echo $section_title; ?>">
			<?php maybe_print_anchor_closing_tag($section_title_url); ?>

        </div>


	<?php elseif($section_title): ?>

        <<?php echo $heading; ?> class="section__title">
            <?php maybe_print_anchor_opening_tag($section_title_url); ?>
            <?php echo remove_punctuation($section_title); ?>
            <?php maybe_print_anchor_closing_tag($section_title_url); ?>
        </<?php echo $heading; ?>>

    <?php endif; ?>


    <?php if( !empty($is_sponsored) ): ?>

        <div class="section__sponsor_container">

            <div class="section__sponsor_mobile">
                <?php maybe_print_anchor_opening_tag($sponsor_logo_click_url); ?>
                <img class="section__sponsor_mobile" src="<?php echo $sponsor_logo['url']; ?>" alt="<?php echo $sponsor_logo['alt']; ?>">
                <?php maybe_print_anchor_closing_tag($sponsor_logo_click_url); ?>
            </div>

            <div class="section__sponsor_desktop">
                <?php maybe_print_anchor_opening_tag($sponsor_logo_click_url); ?>
                <img class="section__sponsor_desktop" src="<?php echo $sponsor_logo_alt['url']; ?>" alt="<?php echo $sponsor_logo_alt['alt']; ?>">
                <?php maybe_print_anchor_closing_tag($sponsor_logo_click_url); ?>
            </div>


        </div>

    <?php endif; ?>

</div>