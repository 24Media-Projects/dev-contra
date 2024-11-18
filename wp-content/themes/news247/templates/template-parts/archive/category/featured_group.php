<?php

/**
 * Available parameters
 * 
 * @param WP_Term, $term
 */
$featured_group = get_field( 'btw__taxonomy_fields__featured_group', $term );

if( !$featured_group ) return;

?>

<div class="category__widget has_featured_group">
    <?php

    $featured_group_primary_term = get_field( 'btw__group_fields__hp__general__primary_term_taxonomy_selection', $featured_group ) ?: 'post_tag';

    // required to be defined. Used in atf_post template
    $group_template = get_field( 'btw__group_fields__hp__template', $featured_group );

    btw_get_template_part("template-parts/groups/home/{$group_template}", array(
        'primary_term'           => $featured_group_primary_term,
        'show_term_basic_header' => is_single(), // show header only in single article
        'show_date'              => true,
        'group_id'               => $featured_group->ID,
    ));

    ?>
</div>
