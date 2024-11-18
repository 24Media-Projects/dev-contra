<?php

/**
 * Available strings
 * @var string, $citation. The citation of the blockquote
 * @var string, $citation_link. The link of the citation
 * @var string, $content. The quote of the blockquote
 */

$citation_html = $citation
    ? sprintf('<a title="%s" href="%s">%s</a>', esc_attr($citation), $citation_link,  $citation)
    : $citation;

?>

<div class="blockquote__container">
    <blockquote class="blockquote_content">
        <?php echo $content; ?>
    </blockquote>
    <div class="blockquote_citation">
        <?php echo remove_punctuation($citation_html); ?>
    </div>
</div>