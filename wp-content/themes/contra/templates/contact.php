<?php // Template Name: CONTACT
get_header();
$section_title = get_the_title();
$contact_form = get_field('btw_fields__form_id');
?>

<div class="legal__wrapper">
    <main class="legal_main contact_main">
        <h1 class="section__title"><?php echo remove_punctuation($section_title); ?></h1>
        <div class="paragraph">
            <?php the_content(); ?>
        </div>

        <div class="ninja_form form_container">
            <?php echo do_shortcode($contact_form); ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>