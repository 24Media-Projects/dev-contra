<?php
global $post;

if( $primary_tag->taxonomy != 'post_tag' ) return;

$primary_tag_query = new WP_Query([
	'post_type'         => 'post',
	'post_status'       => 'publish',
	'orderby' => 'date',
	'order'   => 'DESC',
	'posts_per_page'    => 5,
	'suppress_filters'   => false,
	'post__not_in' => [$post->ID],
	'tax_query' => [
		[
			'taxonomy' => 'post_tag',
			'field' => 'term_id',
			'terms'    => [ $primary_tag->term_id ]
		],
	]
]);

if( !$primary_tag_query->found_posts ) return; ?>

<div class="tag_related_articles">
	<label class="label s-font-bold">ΠΕΡΙΣΣΟΤΕΡΑ:</label>
    <!-- <span class="tag_name s-font-bold"><?php echo $primary_tag->name; ?> </span> -->
    <ul class="articles_list">

        <?php while( $primary_tag_query->have_posts() ): $primary_tag_query->the_post(); ?>
        <li class="suggested-article-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
        <?php endwhile;
		wp_reset_query();
		?>
    </ul>
</div>