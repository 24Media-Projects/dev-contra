<?php
global $wp_query;
//$search_query = get_search_query();
$next_post_link = btw_get_next_post_link_url();

get_header();

$orderby_options = [
    'post_date' => 'Ημερομηνία',
    '_score' => 'Συνάφεια',
];


$current_orderby = $_GET['orderby'] ?? 'post_date';

?>



<div class="category__wrapper search-results__wrapper">

    <div class="category__header">
        <?php btw_get_template_part('template-parts/group_header', [
            'section_title' => 'ΑΝΑΖΗΤΗΣΗ',
        ]); ?>
    </div>

    <form class="search_form" method="get" action="<?php echo site_url(); ?>">
        <input class="caption s-font searchInput" placeholder="Αναζήτηση" type="text" name="s" value="<?php echo get_search_query(); ?>">
        <div class="search_form_btn__container">
            <button type="button" aria-label="Close Search" class="clear--btn">
                <svg>
                    <use xlink:href="#icon-close"></use>
                </svg>
            </button>
            <button type="submit" class="submit--btn" aria-label="Sumbit">
                <svg class="search_icon">
                    <use xlink:href="#icon-search"></use>
                </svg>
            </button>
        </div>
    </form>

    <div class="search_orderby_container">
        <span class="caption s-font search_orderby_container--label">Ταξινόμηση κατά:</span>
        <ul class="select_orderby">
            <li class="caption s-font select_orderby__current">
                <span><?php echo $orderby_options[$current_orderby];?></span>
                <svg>
                    <use xlink:href="#icon-arrow-dropdown-menu"></use>
                </svg>
            </li>
            <?php 

            foreach( $orderby_options as $option => $label ):
                if( $current_orderby == $option ){
                    continue;
                } ?>

            <li class="caption s-font select_orderby__option" data-value="<?php echo $option;?>"><?php echo $label;?></li>

            <?php endforeach; ?>
        </ul>
    </div>

    <div class="results">
        <h2 class="caption s-font-bold"><?php echo sprintf('%s Αποτελέσματα', $wp_query->found_posts); ?></h2>
        <!--        <h1 class="category__title">Βρέθηκαν --><?php //echo sprintf( '%s άρθρα σχετικά με τον όρο "%s"', $wp_query->found_posts, $search_query );
                                                            ?><!--</h1>-->
    </div>

    <?php if (have_posts()) : ?>

        <div class="category__content">
            <div class="category__main_column main_column">
                <section class="category__posts infinite_posts">
                    <?php while (have_posts()) : the_post();
                        get_template_part('templates/template-parts/archive/post');

                        if ($wp_query->current_post == 9 || $wp_query->current_post == 19) {

                            btw_get_template_part('template-parts/ads/dfp', [
                                'slot_id' => 'term_inline' . ($wp_query->current_post == 19 ? '_a' : ''),
                            ]);
                        }
                    endwhile; ?>
                </section>
            </div>

            <?php if ($next_post_link) : ?>
                <div class="load-more-container">
                    <a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button" href="<?php echo $next_post_link; ?>">
                        <span>ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
                        <svg>
                            <use xlink:href="#icon-back-to-top"></use>
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>

    <?php else : ?>

        <div class="no_results_found paragraph">
            <p>Δεν βρέθηκαν αποτελέσματα.</p>
        </div>

    <?php endif; ?>
</div>


<?php get_footer();
