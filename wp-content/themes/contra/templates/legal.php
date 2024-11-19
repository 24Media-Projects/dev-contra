<?php // Template Name: LEGAL
get_header();

$additional_info = get_field('btw_fields__additional_info');
$section_title = get_the_title();
?>

<div class="legal__wrapper">
    <main class="legal_main">
        <h1 class="section__title"><?php echo remove_punctuation($section_title); ?></h1>
        <div class="paragraph">
            <?php the_content(); ?>
        </div>

        <div class="additional_info section_description">
            <?php echo $additional_info; ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>