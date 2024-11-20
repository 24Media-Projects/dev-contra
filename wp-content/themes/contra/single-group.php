<?php get_header(); ?>

<main class="main_home">

    <?php while( have_posts() ): the_post();

        $group_type = get_field( 'btw__group_fields__group_type' );

        $group_template = get_field( "btw__group_fields__{$group_type}__template" );

        btw_get_template_part( "template-parts/groups/{$group_type}/$group_template", array(
			'group_template'    => $group_template,
            'group_id'      => get_the_ID(),
        ));


    endwhile; ?>

</main>

<?php get_footer(); ?>