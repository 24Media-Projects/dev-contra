<section class="most_popular_section">
    <h2 class="mag-basic-title">MOST POPULAR</h2>

    <script type="text/html" id="tmpl-parsely-popular-articles">
        <article class="article most_popular">
            <h3 class="post__title hover_title mag-basic-title">
                <a title="{{ data.original_title }}" href="{{ data.url }}">
                    <span class="desktop_title truncate">{{{ data.title_uppercase }}}</span>
                </a>
            </h3>
            <figure class="post_img img_hovered">
                <a title="{{ data.original_title }}" href="{{ data.url }}">
                    <img src="{{ data.image_url }}" alt="{{ data.original_title }}" />
                </a>
            </figure>
        </article>
    </script>

    <script>
        var mostPopularMagazine = {
            init: function() {
                this.$elements = Array.prototype.slice.call(document.querySelectorAll('.most_popular_section > .most_popular'));
                this.$elements['0'].classList.add('active');

                this.index = this.$elements.indexOf(document.querySelector('.most_popular_section > .most_popular.active'));
                this.$activeElement = this.$elements['0'];
                this.pause = false;

                this.bindEvents();

                this.autoplay();
            },
            bindEvents: function() {
                var self = this;
                this.$elements.forEach(function($element) {
                    $element.addEventListener('mouseenter', self.stop.bind(self));
                    $element.addEventListener('mouseleave', self.resume.bind(self));

                    $element.querySelector('.post__title').addEventListener('mouseenter', self.hover.bind(self));
                });
            },
            hover: function(event) {
                document.querySelector('.most_popular_section > .most_popular.active').classList.remove('active');
                event.currentTarget.closest('.most_popular').classList.add('active');
            },
            stop: function() {
                this.pause = true;
            },
            resume: function() {
                this.pause = false;
            },
            next: function() {
                if (this.pause === true) return;

                let currentIndex = this.$elements.indexOf(document.querySelector('.most_popular_section > .most_popular.active'));

                this.$activeElement = this.$elements[currentIndex];
                this.$activeElement.classList.remove('active');

                this.index = currentIndex + 2 <= this.$elements.length ? currentIndex + 1 : 0;

                this.$activeElement = this.$elements[this.index];
                this.$activeElement.classList.add('active');

            },
            autoplay: function() {
                setInterval(this.next.bind(this), 2500);
            },
        }

        var parselyPopularArticles = new ParselyPosts('.most_popular_section', {
            apiUrl: BTW.ajaxUrl,
            settings: {
                queryVars: {
                    action: 'get_primary_category_popular_articles',
                    term_id: '<?php echo $post_primary_category->term_id; ?>',
                    per_page: 3,
                },
            },
            template: 'parsely-popular-articles',
            callbacks: {
                onRenderCompleted: function(posts) {
                    if (!posts) {
                        document.querySelector('.most_popular_section').style.visibility = 'hidden';
                        return;
                    }

                    mostPopularMagazine.init();
                }
            }
        });
    </script>
</section>