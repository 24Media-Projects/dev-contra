<script type="text/html" id="tmpl-category_maganize_post">
    <article class="article align_left magazine_article mag_basic_article landscape_img appended_post">
        <figure>
            <a class="clear post_img" href="{{{ data.post_url }}}" title="{{ data.post_title }}">
                <img src="{{ data.post_image_available_sizes.medium_landscape }}" alt="{{ data.post_title }}" />
            </a>
        </figure>

        <div class="post__content">
            <h3 class="post__title mag-basic-title">

                <a href="{{{ data.post_url }}}" title="{{ data.post_title }}">
                    <span class="truncate" data-truncate-lines="5">{{{ data.post_title }}}</span>
                </a>

            </h3>
            <div class="post__category">
                <h4 class="caption category_caption mag-s-caption asty_bold">
                    {{{ data.post_primary_tag }}}
                </h4>
                
                <# if( data.is_sponsored ){ #>
                <span class="caption mag-s-caption asty_book">
                    Sponsored
                </span>
                <# } #>

                <# if( !data.is_sponsored ){ #>
                <span class="caption mag-s-caption asty_book">
                    {{{ data.post_date }}}
                </span>
                <# } #>
                
            </div>
        </div>
    </article>
</script>