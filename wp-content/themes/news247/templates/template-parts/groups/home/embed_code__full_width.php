<?php
$embed_code = get_field('btw__group_fields__hp__template__embed_code__full_width__embed_code');
if( !$embed_code ) return;

extract( btw_get_hp_group_fields() );

$bg_color = $bg_color ?: '#F4F4F4';

?>

<div class="embed_code__full_width__wrapper" style="<?php if($bg_color) echo "background-color: $bg_color;"; ?>">
    <section id="<?php echo $section_id; ?>" class="embed_code__full_width__wrapper <?php echo $section_extra_classes;?>">
		<?php echo $embed_code; ?>
    </section>
</div>
