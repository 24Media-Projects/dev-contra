<?php get_header(); ?>

<main class="main_home">

    <?php while( have_posts() ): the_post();

        $group_type = get_field( 'btw__group_fields__group_type' );
        $group_id = get_the_ID();

        if( $group_type == 'hp' ){

            $group_template = get_field( 'btw__group_fields__hp__template' );

            $primary_term = get_field( 'btw__group_fields__hp__general__primary_term_taxonomy_selection' ) ?: 'post_tag';

            btw_get_template_part( 'template-parts/groups/home/' . $group_template, array(
                'template_name' => $group_template,
                'primary_term'  => $primary_term,
                'group_id'      => $post->ID,
            ));

        }elseif( $group_type == 'magazine' ){

            $group_template = get_field( 'btw__group_fields__magazine__template' );

            btw_get_template_part( 'template-parts/groups/magazine/' . $group_template, array(
                'template_name' => $group_template,
                'primary_term'  => 'post_tag',
                'group_id'      => $post->ID,
            ));
        }

    endwhile; ?>

</main>

<?php get_footer(); ?>