<?php
// Template Name: Magazine Header + Footer + Embed Code
// Template Post Type: post
get_header();


while (have_posts()) : the_post(); ?>

  <div class="category__wrapper">

    <?php btw_get_post_impressions_url(); ?>

    <?php if (get_field('btw__article_fields__show_title')) : ?>
      <h1 class="section__title page__title"><?php echo remove_punctuation(get_the_title()); ?></h1>
    <?php endif; ?>

    <?php echo get_field('btw__article_fields__embed_code'); ?>

  </div>

<?php
endwhile;

get_footer();
?>