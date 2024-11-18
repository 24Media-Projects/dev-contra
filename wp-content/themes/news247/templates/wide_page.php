<?php // Template Name: WIDE PAGE
get_header();

$section_title = get_the_title();
?>

<div class="legal__wrapper wide_page__wrapper">
    <main class="legal_main">
        <h1 class="section__title"><?php echo remove_punctuation($section_title); ?></h1>
        <div class="paragraph">
            <?php the_content(); ?>
        </div>
    </main>
</div>

<style>
    .wide_page__wrapper {
        width: 100%;
        max-width:100%;
        padding-left: 20px;
        padding-right: 20px;
    }

    @media screen and (min-width: 64em) {
        .wide_page__wrapper {
            padding-left: 0;
            padding-right: 0;
            width: 980px;
        }

        /*.section__title {
            max-width: 640px;
            margin-left: auto;
            margin-right: auto;
        }*/
    }

    @media screen and (min-width: 84.375em) {
      .wide_page__wrapper {
        width: 1320px;
      }
    }
</style>

<?php get_footer(); ?>