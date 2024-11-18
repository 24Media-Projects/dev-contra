<?php 

if( get_queried_object() instanceof WP_Term && in_array( get_queried_object()->slug, [ 'opinion', 'gnomes' ] ) ) {
    $caption = 'post_author_html';
}else{
	$caption = 'post_primary_' . (is_category() ? 'tag' : 'category');
}
?>

<script type="text/html" id="tmpl-archive_post">
    <#
        data.containerClasses = ['article align_left', 'basic_article', 'landscape_img', 'appended_post'];
        data.containerClasses.push( data.post_type == 'video' || data.is_podcast ? ' play_article' : '' );
        data.containerClasses = data.containerClasses.join(' ').trim();
    #>
    <article class="{{{ data.containerClasses }}}">
    
        <figure>
            <a class="clear post_img" href="{{{ data.post_url }}}" title="{{ data.post_title }}">
                {{{ data.attachment_html.html }}}
            </a>
        </figure>

        <div class="post__content">
            <h3 class="post__title article-s-main-title">

                <a href="{{{ data.post_url }}}" title="{{ data.post_title }}">
                    {{{ data.post_title }}}
                </a>

            </h3>
            <div class="post__category">
                <h4 class="caption s-font-bold">
                    {{{ data.<?php echo $caption;?> }}}
                </h4>

                <# if( data.is_sponsored){ #>
                    <span class="sponsor s-font">Sponsored</span>
                <# } #>

                <span class="caption s-font">{{ data.post_date }}</span>

            </div>
        </div>
    </article>
</script>