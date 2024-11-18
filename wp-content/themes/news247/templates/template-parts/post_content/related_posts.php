<?php
global $post;

global $post__related_posts;

?>

<div class="tag_related_articles">
	<label class="label s-font-bold">ΣΧΕΤΙΚΑ ΑΡΘΡΑ:</label>
    <ul class="articles_list">

        <?php 
        foreach ( $post__related_posts as $post ): 
        	setup_postdata($post); 
        ?>
        <li class="suggested-article-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </li>
        <?php 
    	endforeach;
		wp_reset_postdata();
		?>
    </ul>
</div>