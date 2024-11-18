<?php
// XD: https://xd.adobe.com/view/2bbff874-6406-4bb4-8597-3cd3bcce2b89-7eec/
$term = get_queried_object();
$term_id = $term->term_id;


$featured_video_post = get_field('btw__videos_home_fields__featured_video', 'option')[0] ?? null;

$displayed_posts = [];

get_header();

?>
<div class="category__wrapper category-videos__wrapper">

	<div class="category__header">
		<?php btw_get_template_part('template-parts/group_header', [
			'section_title' => $term->name,
		]); ?>
	</div>

	<?php if (trim($term->description)) : ?>
		<div class="category__description">
			<div class="section_description">
				<?php echo $term->description; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if( $featured_video_post ):
        $featured_image = btw_get_post_featured_image('large_landscape', $featured_video_post );
        $primary_tag = btw_get_post_primary_tag($featured_video_post); ?>
        <div class="featured_video_width_sidebar">
            <div class="featured_video_container">
                <article class="article">
                    <figure class="featured_video__main_image">
                        <a class="post_img" href="<?php echo get_permalink($featured_video_post); ?>">
                            <img src="<?php echo $featured_image->url; ?>" alt="<?php echo $featured_image->alt;?>" />
                        </a>
                    </figure>
                    <div class="post__content">
                        <h3 class="post__title l-article-s-font">
                            <a title="<?php echo esc_attr($featured_video_post->post_title); ?>" href="<?php echo get_permalink($featured_video_post); ?>">
                                <?php echo $featured_video_post->post_title; ?>
                            </a>
                        </h3>
                        <div class="post__category">
                            <h4 class="l-caption s-font-bold">
                                <a title="<?php echo $primary_tag->name; ?>" href="<?php echo $primary_tag->term_link; ?>">
                                    <?php echo $primary_tag->name; ?>
                                </a>
                            </h4>
                        </div>
                    </div>
                </article>
            </div>
            <div class="main_sidebar category__aside sidebar_column top">
                <div class="ads_element"></div>
            </div>
        </div>
	<?php endif; ?>


	<div class="category__content">
		<div class="category__latest_container">
			<div class="category__latest__main_column">

				<?php foreach( get_field('btw__videos_home_fields', 'option') ?: [] as $section ){

					if ( $section['acf_fc_layout'] == 'latest_videos' ){
						btw_get_template_part( 'template-parts/archive/category_videos/latest' );

					}else{
						btw_get_template_part( 'template-parts/archive/category_videos/subcategory', [
							'video_subcategory' 	=> $section['post_category'],
						]);
					}
				} ?>

			</div>
			<div class="main_sidebar category__aside sidebar_column bottom">
				<?php
					btw_get_template_part('template-parts/ads/dfp', [
						'slot_id' => 'sidebar_a',
					]);
				?>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>