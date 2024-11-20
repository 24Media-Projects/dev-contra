<?php // Template Name: Homepage

$hp_groups_slots = btw_get_hp_group_slots();
$group_type = 'hp';

get_header();?>

<?php while (have_posts()) : the_post(); ?>

    <main class="main_home">
        <h1 class="invisible">Αρχική Σελίδα contra.gr</h1>

        <?php

            $available_billboards = [
                'hp_billboard_a',
                'hp_billboard_b'
            ];
            /**
             * Get Hp groups
             */
            $group = btw_get_groups_by_group_type( 'hp' );

            while ( $group->have_posts() ): $group->the_post();

                $group_template = get_field("btw__group_fields__{$group_type}__template");

                btw_get_template_part("template-parts/groups/$group_type/$group_template", array(
                    'group_id'			=> get_the_ID(),
                    'group_template'	=> $group_template,
                ));

                if( in_array( $group->current_post, [ 4, 8 ] ) ){
                    $billboard = array_shift( $available_billboards );

                    btw_get_template_part('template-parts/ads/dfp', [
                        'slot_id' => $billboard,
                    ]);
                }


            endwhile;
            wp_reset_query();
            ?>

    </main>
<?php endwhile; ?>

<?php get_footer();