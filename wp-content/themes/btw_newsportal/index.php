<?php get_header(); ?>

  <?php while ( have_posts() ) : the_post(); ?>
    <?php the_post_thumbnail('medium'); ?>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
  <?php endwhile; ?>
INDEX test
<?php get_footer();?>