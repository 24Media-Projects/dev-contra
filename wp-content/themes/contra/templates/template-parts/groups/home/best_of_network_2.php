<?php
extract( btw_get_hp_group_fields() );

global $post;

$bon_hp_two_cols = get_post_meta( $post->ID, 'bon_hp_two_posts', true );

if( !$bon_hp_two_cols ){
    return;
}

$timestamp = strtotime('now');

?>

<div class="home_wrapper best_of_network__wrapper best_of_network_alt__wrapper">
    <section id="<?php echo $section_id; ?>" class="best_of_network bon_alt">

        <?php if( $section_title ): ?>
        <div class="group_header">
            <h2 class="section__title">
                <?php echo $section_title; ?>
            </h2>
        </div>
        <?php endif; ?>

        <div class="article_container">

        <?php foreach( $bon_hp_two_cols as $col ):
            
            $section = $col['section'];
        ?>

        <div class="article_container__col">
            <h3 class="article_container__col--title section_subtitle"><?php echo $section['title'];?></h3>

            <?php foreach( $col['posts'] as $index => $post ): ?>

                <article class="article landscape_img basic_article <?php echo $index != 0 ? 'basic_article_small' : '';?>">
                    <figure>
                        <a  
                            target="_blank"
                            class="clear post_img lazyload"
                            href="<?php echo $post['post_url'];?>"
                            title="<?php echo $post['post_title_esc'];?>"
                            data-bg="<?php echo $post['post_image'];?>">

                            <span class="invisible"><?php echo $post['post_title'];?></span>
                        </a>
                    </figure>

                    <div class="post__content" style="background-color: transparent;">
                        <div class="post__category">
                            <h3 class="caption s-font-bold">
                                <?php echo $post['post_caption'];?>
                            </h3>
                        </div>
                        <h3 class="post__title article-s-main-title section_subtitle">
                            <a target="_blank" href="<?php echo $post['post_url'];?>" title="<?php echo $post['post_title_esc'];?>">
                                <span class="desktop_title truncate"><strong><?php echo $post['post_title'];?></strong></span>
                            </a>
                        </h3>
                    </div>

                    <?php if( $post['post_impression_url'] ): ?>
                        <img class="imp_url" src="<?php echo $post['post_impression_url'] . '[' . $timestamp . ']'; ?>" alt="post impression url" />
                    <?php endif; ?>

                </article>
                
               <?php endforeach; ?>
            </div>

        <?php endforeach; ?>

        </div>
    </section>
</div>