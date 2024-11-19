<?php
if( btw_is_promo_text_hidden() ) return;

$promo_text = get_field('btw__brand_fields__default_brand_follow_text', 'option');
?>

<style type="text/css">
	.seo_promo p {margin: 0;}
</style>
<div class="seo_promo">
	<?php echo $promo_text; ?>
</div>