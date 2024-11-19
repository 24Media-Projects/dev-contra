<?php
if( empty($sponsor) ) return;

$wrapper_class ??= 'single_article_sponsored';
?>

<div class="<?php echo $wrapper_class; ?>" style="background-color:<?php echo $sponsor['bgcolor']; ?>;">
    <a href="<?php echo $sponsor['click_url']; ?>" target="_blank" aria-label="Sponsor">
        <img src="<?php echo $sponsor['logo']; ?>" alt="<?php echo $sponsor['name']; ?>" width="" />
    </a>
	<?php echo btw_get_impressions_url($sponsor['impression_url']); ?>
</div>
