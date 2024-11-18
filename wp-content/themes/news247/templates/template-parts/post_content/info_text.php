<?php
$info_text = get_field('btw__global_fields__info_text');

if( !$info_text ) return;
?>

<style type="text/css">
	.info_text p {margin: 0;}
</style>
<div class="info_text">
	<b>Info:</b> <?php echo $info_text; ?>
</div>