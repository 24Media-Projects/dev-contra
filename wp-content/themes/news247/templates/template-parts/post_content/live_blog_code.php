<?php

global $post;

$live_blog_code = get_field('btw__article_fields__liveblog_code');

?>




<div class="liveblog__outer_container">

    

    <div class="live_blog_code">
        <?php echo $live_blog_code; ?>
    </div>

    <div id="live-blog-key-events-container" class="code-widget ">
        <div id="live-blog-key-events" class="key-events-widget hidden">
            <h2 class="key-events-widget__header">ME MIA MATIA</h2>
            <div class="key-events-widget__articles"></div>
        </div>
        <script>
            (function($) {

                var isMobile = $(document.body).hasClass('mobile');
                var $widget = $('#live-blog-key-events');

                //mobile hide handler
                var $clicker = $widget.children('.key-events-widget__header');
                if ($clicker.length && isMobile) {
                    $clicker.click(function() {
                        $widget.toggleClass('key-events-widget--collapsed');
                        $widget.children('.key-events-widget__articles').slideToggle('fast');
                    });
                }

                $(document).ready(function() {
                    loadHighlights();
                    setInterval(loadHighlights, 300000);
                });

                function loadHighlights() {
                    $.getJSON('https://live.24media.gr/service/highlights/' + config.liveBlogID + '/?accept=json&callback=?', function(data) {

                        if (!Array.isArray(data) || !data.length)
                            return;

                        var $articlesContainer = $widget.children('.key-events-widget__articles');
                        if (!$articlesContainer.length)
                            return;

                        $widget.removeClass('hidden');
                        $articlesContainer.empty();

                        $.each(data, function(i, highlight) {
                            if (i >= 7)
                                return false;

                            var html = '';
                            html += '<article class="key-events-widget__article" data-comment-id="' + highlight.commentId + '">';
                            html += '<span class="key-events-widget__time">' + highlight.timeAgo + '</span>';
                            html += '<h2>' + highlight.title + '</h2>';
                            html += '</article>';
                            $articlesContainer.append(html);
                        });

                        function getComment(id) {

                            var $comment = $('#livecomment' + id);
                            var $button = $('#liveblogging-more-button');
                            if ($comment.length) {
                                $('html, body').animate({
                                    scrollTop: $comment.offset().top - 50
                                }, 500, 'swing');
                            } else if ($button.length) {
                                $button[0].click();
                                setTimeout(function() {
                                    getComment(id);
                                }, 200);
                            }
                        }

                        // click event handler
                        $articlesContainer.children('article').each(function(i, highlight) {
                            $(highlight).on('click', function(e) {

                                var id = $(this).attr('data-comment-id');
                                if (!id)
                                    return;

                                getComment(id);

                            });
                        });
                    });
                }
            })(jQuery);
        </script>

         <script type="text/javascript">
            (function($) {
                function toggleKeyEvents() {
                    var keyEventsToggle = $('.key-events-widget__header');
                    var keyEvents = $('.key-events-widget__articles');


                    $(keyEventsToggle).on("click", function () {
                         $(keyEvents).toggleClass("inactive");
                         $(keyEventsToggle).toggleClass("inactive");
                    });
                }
                $(document).ready(function() {
                    toggleKeyEvents();
                });
            })(jQuery);
        </script>
    </div>
    <!-- #live-blog-key-events-container closed -->
</div>
<!-- .liveblog__outer_container closed -->