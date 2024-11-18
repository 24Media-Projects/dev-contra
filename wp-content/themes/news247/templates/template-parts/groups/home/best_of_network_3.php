<?php
extract(btw_get_hp_group_fields());

$bon_hp_three_posts = get_post_meta($group_id, 'bon_hp_three_posts', true);

if (!$bon_hp_three_posts) {
    return;
}

?>

<div class="home_wrapper best_of_network__wrapper">
    <section id="<?php echo $section_id; ?>" class="articles_grid best_of_network_3">

        <div class="section_container">

            <?php if ($section_title) : ?>
                <div class="group_header">
                    <h2 class="section__title">
                        <?php echo $section_title; ?>
                    </h2>
                </div>
            <?php endif; ?>

            <div class="article_container loading">

                <?php foreach ($bon_hp_three_posts as $index => $post) : ?>

                    <article class="article landscape_img basic_article <?php echo $index == 0 ? 'overlay_mobile' : ''; ?>">
                        <figure>
                            <a target="_blank" class="clear post_img lazyload" href="<?php echo $post['post_url']; ?>" title="<?php echo $post['post_title_esc']; ?>" data-bg="<?php echo $post['post_image']; ?>">
                                <span class="invisible"><?php echo $post['post_title']; ?></span>
                            </a>
                        </figure>

                        <div class="post__content" style="background-color: transparent;">
                            <div class="post__category">
                                <h3 class="caption s-font-bold">
                                    <?php echo $post['post_caption']; ?>
                                </h3>
                            </div>
                            <h3 class="post__title article-s-main-title">
                                <a target="_blank" href="<?php echo $post['post_url']; ?>" title="<?php echo $post['post_title_esc']; ?>">
                                    <span class="desktop_title truncate"><strong><?php echo $post['post_title']; ?></strong></span>
                                </a>
                            </h3>
                        </div>

                        <?php if ($post['post_impression_url']) : ?>
                            <img class="imp_url" src="<?php echo $post['post_impression_url'] . '[' . $timestamp . ']'; ?>" alt="post impression url" />
                        <?php endif; ?>

                    </article>
                <?php endforeach; ?>

            </div>
        </div>
    </section>
</div>