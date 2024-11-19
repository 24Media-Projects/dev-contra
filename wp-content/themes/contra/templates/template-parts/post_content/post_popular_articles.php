<div class="popular_articles__wrapper">
    <section class="popular_articles_section">
        <h2 class="popular_articles_title">Δημοφιλή Άρθρα</h2>
        <div class="text_article_container">

            <script type="text/html" id="tmpl-parsely-popular-articles">
                <article class="article text_article">
                    <div class="post__content">
                        <h3 class="post__title article_main_font">
                            <a title="{{ data.original_title }}" href="{{ data.url }}">
                                <span class="desktop_title truncate">{{{ data.title }}}</span>
                            </a>
                        </h3>
                        <div class="post__category">
                            <h4 class="caption s-font-bold">
                                <!-- <a title="{{ data.section }}" href="/"> -->
                                {{ data.tags['0'] || data.section }}
                                <!-- </a> -->
                            </h4>
                        </div>
                    </div>
                </article>
            </script>

            <script>
                var parselyPopularArticles = new ParselyPosts('.popular_articles__wrapper .text_article_container', {
                    apiUrl: BTW.ajaxUrl,
                    settings: {
                        queryVars: {
                            action: 'get_primary_category_popular_articles',
                            term_id: '<?php echo $post_primary_category->term_id; ?>',
                            pub_date_start: '7d',
                        },
                    },
                    template: 'parsely-popular-articles',
                    callbacks: {
                        onRenderCompleted: function(posts) {

                            if (!posts) {
                                document.querySelector('.popular_articles__wrapper').style.visibility = 'hidden';
                            }
                        }
                    }
                });
            </script>
        </div>

    </section>

</div>