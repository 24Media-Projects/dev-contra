<?php get_header(); ?>

<main class="main_home">

    <?php while( have_posts() ): the_post();

        $group_type = get_field( 'btw__group_fields__group_type' );

        $group_template = get_field( "btw__group_fields__{$group_type}__template" );

		$primary_term = $group_type == 'hp' ? btw_get_group_setting('primary_term_taxonomy_selection', 'category') : 'post_tag';

        btw_get_template_part( "template-parts/groups/{$group_type}/" . $group_template, array(
            'template_name' => $group_template,
            'primary_term'  => $primary_term,
            'group_id'      => get_the_ID(),
        ));


    endwhile; ?>

</main>

<?php get_footer(); ?>