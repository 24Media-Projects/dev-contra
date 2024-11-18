<?php

	$is_admin = $args['is_admin'];
	$provider = $args['provider'];
	$embed_code = BTW_Optimize::lazyload_to_embed_code_iframes( $args['embed_code'] );
	$width = !empty( $args['width'] ) ? 'width:' . $args['width'] . 'px; ' : '';
	$height = !empty( $args['height'] ) ? 'height:' . $args['height'] . 'px; ' : '';


?>


<div class="embed_code__container provider_<?php echo $provider;?>" <?php echo $is_admin ? 'data-provider="' . strtoUpper( $provider ) . '"' : '';?>>
<div class="embed_code_item">

	<div class="embed_code_item__content" <?php echo $width || $height ? 'style="' . $width . $height . '"' : '';?>>

		<?php echo $embed_code; ?>

		<?php if( $is_admin ){
			btw_get_template_part( 'global_elements/third_party_scripts/embeds/' . $provider );
		} ?>

	</div>
	<!-- .embed_code_item__content closed -->

</div>
<!-- .embed_code_item closed -->

</div>
<!-- .embed_code__container closed -->


