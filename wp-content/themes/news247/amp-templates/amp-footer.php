<?php

$logo = get_field('btw__brand_fields__logo', 'option');

?>

	</div> <!-- .global_wrapper -->

        <div class="latest_news_header" id="latest_news_header">
        	<?php
			btw_get_template_part('global_elements/homepage__eidiseis', [
				'img_size' => 'small_landscape',
			]);
			?>

        </div>

        <?php get_template_part('templates/global_elements/footer'); ?>

        <?php wp_footer(); ?>

       

    </body>

</html>