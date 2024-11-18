<?php
$image_group = get_field('btw__newsletter_fields__ad');

$image = $image_group['image'];
if (!$image) return;

$link = $image_group['link'];

?>
<tr>
	<td style="padding:30px 20px 0;">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" style="border-top:1px solid #0000004D;border-bottom:1px solid #0000004D;">
			<tr>
				<td align="center">
					<?php if ($link) echo '<a href="' . $link . '" style="display:block;font-size:0;">'; ?>
					<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>" style="padding:50px 0;display: block;width: auto;height: auto;">
					<?php if ($link) echo '</a>'; ?>
				</td>
			</tr>
		</table>
	</td>
</tr>