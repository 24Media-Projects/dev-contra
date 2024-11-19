<?php
$embed_code = get_field('btw__group_fields__magazine__template__embed_code__embed_code');
if( !$embed_code ) return;
?>

<section id="group-<?php echo $group_id; ?>" class="embed_code_section">
    <?php echo $embed_code; ?>
</section>
