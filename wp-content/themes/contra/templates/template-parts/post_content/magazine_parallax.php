<?php
if( empty($parallax) ) return;

$wrapper_class ??= 'single_article_parallax';
?>
<style>
    .<?php echo $wrapper_class; ?> {
        background-image: url(<?php echo $parallax['mobile_image']; ?>);
    }

    @media screen and (min-width: 768px) {
        .<?php echo $wrapper_class; ?> {
            background-image: url(<?php echo $parallax['tablet_image']; ?>);
        }

    }

    @media screen and (min-width: 1024px) {
        .<?php echo $wrapper_class; ?> {
            background-image: url(<?php echo $parallax['desktop_image']; ?>);
        }

    }
</style>


<div class="<?php echo $wrapper_class; ?>">
	<a href="<?php echo $parallax['click_url']; ?>" target="_blank" aria-label="Parallax"></a>
	<?php btw_get_impressions_url($parallax['impression_url']); ?>
</div>
