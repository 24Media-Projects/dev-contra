<?php // Template Name: COMPANY ID
get_header();

$page_title = get_the_title();
?>

<div class="legal__wrapper">
    <main class="legal_main company_id_main">
        <h1 class="section__title"><?php echo remove_punctuation($page_title); ?></h1>
        <div class="paragraph">
            <?php the_content(); ?>
        </div>
    </main>
</div>

<?php get_footer(); ?>