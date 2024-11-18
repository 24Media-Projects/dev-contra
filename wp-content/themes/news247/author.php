<?php
$user = get_queried_object();

if( $avatar = get_field('btw__global_fields__featured_image', "user_{$user->ID}") ){
	$avatar = wp_get_attachment_image_src( $avatar['ID'], 'original' );
	$avatar_url = $avatar[0];
}

get_header();
?>
    <div class="category__wrapper author__wrapper <?php if( trim($user->description) ) echo 'with_description'; ?> <?php if( $featured_image ) echo 'with_featured_image'; ?>">
	<div class="category__header user__header">
		<?php btw_get_template_part('template-parts/group_header', [
			'section_title' => $user->display_name,
		]); ?>
	</div>

    <?php if( trim($user->description) ): ?>
        <div class="category__description tag__description user__description">
            <?php if( $avatar ): ?>
				<div class="author_featured_image_container">
                	<figure class="tag_featured_image" style="background-image: url(<?php echo $avatar_url; ?>);"></figure>
				</div>
                <div class="tag_info"><span class="section_extended_description"><?php echo $user->description; ?></span><button aria-label="Expand" class="expand-description" type="button"><span class="invisible">Expand Description</span><svg><use xlink:href="#icon-back-to-top"></use></svg></button></div>
            <?php else: ?>
                <div class="section_description"><?php echo $user->description; ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

	<div class="category__content">
		<div class="sticky_element__article_sidebar__parent">
			<div class="category__main_column main_column">
				<main class="category__main grid_posts_container">
					<section class="category__posts infinite_posts">
						<?php while (have_posts()) : the_post();
							btw_get_template_part('template-parts/archive/post',[
								'lazyload' => $wp_query->current_post > 2,
							]);
							
                            if( $wp_query->current_post == 9 || $wp_query->current_post == 19){

								btw_get_template_part('template-parts/ads/dfp', [
									'slot_id' => 'term_inline' . ( $wp_query->current_post == 19 ? '_a' : '' ),
								]);

							}

						endwhile; ?>
					</section>
				</main>
			</div>
			<aside class="main_sidebar category__aside sidebar_column">
			<?php
				btw_get_template_part('template-parts/ads/dfp', [
					'slot_id' => 'sidebar_a',
				]);
			?>
			</aside>
		</div>
		<div class="load-more-container">
			<a class="button load_more_posts" title="ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ" type="button">
				<span>ΠΕΡΙΣΣΟΤΕΡΑ ΑΡΘΡΑ</span>
				<svg>
					<use xlink:href="#icon-back-to-top"></use>
				</svg>
			</a>
		</div>
	</div>

</div>
<?php get_footer(); ?>