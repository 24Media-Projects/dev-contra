        </div> <!-- .global_wrapper -->

        <div class="latest_news_header" id="latest_news_header">
            <?php
            btw_get_template_part('global_elements/homepage__eidiseis', [
                'img_size' => 'small_landscape',
            ]);
            ?>

        </div>

        <?php get_template_part('templates/global_elements/footer'); ?>



        <!-- cleverpush // check live version -->
        <!-- <script src="https://static.cleverpush.com/channel/loader/7tKCHyqN9aaymifdQ.js" async></script> -->


        <?php wp_footer(); ?>

    </body>

</html>