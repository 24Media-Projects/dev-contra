<?php 
$heading = $heading ?? 'h1'; 


$related_links_class = 'no_related_links';

if( !empty($related_links) ) {
    $related_links_class = 'with_related_links';
}
?>
<div class="group_header <?php if( !empty($is_section_title_full_width) ) echo 'section_title_full_width'; ?> <?php echo $related_links_class; ?>">

    <?php if( $section_title ): ?>
        <<?php echo $heading; ?> class="section__title">
            <?php if( !empty($section_title_url) ) echo '<a href="' . $section_title_url . '">'; ?>
                <?php echo remove_punctuation($section_title); ?>
            <?php if( !empty($section_title_url) ) echo '</a>'; ?>
        </<?php echo $heading; ?>>
    <?php endif; ?>

    <?php if( !empty($related_links) ): ?>
        <div class="related_terms">
            <?php foreach($related_links as $related_link): ?>

                <?php if( $related_link['link_url'] ?? 0 ): ?>
                    <a class="caption s-font-bold" href="<?php echo $related_link['link_url']; ?>">
				<?php endif; ?>

				    <?php echo $related_link['link_text']; ?>

				<?php if( $related_link['link_url'] ?? 0 ): ?>
                    </a>
				<?php endif; ?>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php
    if( isset($after_related_terms) ){
        echo $after_related_terms;
    }else{
        do_action('btw/after_related_terms');
	}
    ?>
</div>