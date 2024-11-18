<?php

  global $post;

  $widget_title = apply_filters( 'btw/read_also/section_title', 'Διαβάστε Επίσης', $post );

  $read_also_posts = get_posts(array(
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'post__in' => $args['posts'],
  ));

?>

<div class="read_also__container">

  <span class="read_also__title"><?php echo $widget_title; ?></span>

  	<?php

    foreach( $read_also_posts as $post ):
      setup_postdata( $post );

  		$post_title = get_the_title();
      	$post_link 	= get_permalink();
		$post_feat_image = btw_get_post_featured_image( apply_filters( 'btw/read_also/image_size', 'full' ) );
      	$post_primary_category = btw_get_post_primary_category();

	?>

    <div class="read_also_item">

    	<figure class="read_also_item__thumbnail">
    		<a href="<?php echo $post_link;?>" title="<?php echo esc_attr( $post_title );?>" target="_blank">
          		<img src="<?php echo $post_feat_image->url;?>" alt="<?php echo $post_feat_image->alt;?>" />
			</a>
		</figure>

		<div class="read_also_item__content">
			<div class="caption read_also_item__caption">
				<a title="<?php echo esc_attr( $post_primary_category->name );?>" href="<?php echo $post_primary_category->term_link; ?>" target="_blank">
					<?php echo $post_primary_category->name; ?>
				</a>
			</div>

			<h4 class="read_also_item__title">
				<a href="<?php echo $post_link;?>" title="<?php echo esc_attr( $post_title );?>" target="_blank">
					<?php echo apply_filters( 'read_also/post_title', $post_title, $post );?>
				</a>
			</h4>
		</div>

    </div>

  <?php endforeach;?>
  <?php wp_reset_postdata();?>
</div>
