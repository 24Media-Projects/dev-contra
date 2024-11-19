<?php
$embed_code = get_field('btw__group_fields__hp__template__embed_code__embed_code');
if( !$embed_code ) return;

extract( btw_get_hp_group_fields() );

/*
if( strpos( $embed_code, '<iframe' ) !== false && strpos( $embed_code, 'https://view.ceros.com' ) !== false ){

	if( preg_match( '/<iframe[^>]+?><\/iframe>/', $embed_code, $iframes ) !== false ){

		$iframe = $iframes['0'];

		$iframe = str_replace( "'", '"', $iframe );
		$iframe = preg_replace( '/class="[^"]+?"/', '', $iframe );
		$iframe = preg_replace('/(<iframe.*?)(src="[^"]+?")(.+?>)/', '$1 data-$2 class="lazyload" $3', $iframe );

		$embed_code = str_replace( $iframes['0'], $iframe, $embed_code );
	}
}
*/

$section_classes = [
	'embed_code home_wrapper',
	$section_extra_classes,
];

if( $sponsor_logo ){
	$section_classes[] = 'with_sponsor_logo';
}


if( !empty($sponsor_logo_alt) ){
	$section_classes[] = 'with_sponsor_logo_alt';
}


if( !$sponsor_logo && $section_supertitle){
	$section_classes[] = 'with_section_supertitle';
}


if ( $is_dark_mode ) {
	$section_classes[] = 'section_darkmode';
}


if ( $related_links ) {
	$section_classes[] = 'with_related_links';
}

if ( $section_lead ) {
	$section_classes[] = 'with_description';
}

if ( $bg_color ) {
	$style = "background-color: $bg_color";
	$section_classes[] = 'with_bg_color';
}

?>

<div class="embed_code__wrapper"  style="<?php echo $style ?? ''; ?>">
	<section id="<?php echo $section_id; ?>" class="<?php echo implode(' ', $section_classes); ?>">

		<?php btw_get_impressions_url($impression_url); ?>

		<?php if( $section_title ) : ?>
			<?php btw_get_template_part('template-parts/group_header', [
				'heading'                       => 'h2',
				'section_title'                 => $section_title,
				'section_title_url'             => $section_title_url,
				'is_section_title_full_width'   => $is_section_title_full_width,
				'related_links'                 => $related_links,
			]); ?>
		<?php endif; ?>

		<?php if ( $section_lead ): ?>

			<div class="section_description">
				<?php echo $section_lead; ?>
			</div>

		<?php endif; ?>

		<?php echo $embed_code; ?>
	</section>
</div>
