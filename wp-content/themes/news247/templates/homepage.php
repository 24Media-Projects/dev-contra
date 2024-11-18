<?php // Template Name: Homepage

$hp_groups_slots = btw_get_hp_group_slots();

get_header();?>

<?php while (have_posts()) : the_post(); ?>

<main class="main_home">
	<h1 class="invisible">Αρχική Σελίδα news247.gr</h1>

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

			$group_id = $post->ID;

			// required to be defined. Used in atf_post template
			$group_template = get_field( 'btw__group_fields__hp__template' );

			$primary_term = get_field( 'btw__group_fields__hp__general__primary_term_taxonomy_selection' ) ?: 'post_tag';

			btw_get_template_part( 'template-parts/groups/home/' . $group_template, array(
				'primary_term'  => $primary_term,
				'group_id' => $post->ID,
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
<?php get_footer();?>