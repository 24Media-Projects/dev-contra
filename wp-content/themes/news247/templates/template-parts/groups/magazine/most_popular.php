<?php

$parsely_magazine_posts = get_option( 'parsely_magazine_posts' );

if( !$parsely_magazine_posts ){
    return;
}

?>

<section class="most_popular_section">
    <h2 class="mag-basic-title">MOST POPULAR</h2>

    <?php foreach( $parsely_magazine_posts as $index => $post ):
        
        $post_title_esc = esc_attr( strip_tags( $post['title'] ) );
    ?>

    <article class="article most_popular <?php echo $index == 0 ? 'active' : '';?>">
        <h3 class="post__title hover_title mag-basic-title">
            <a title="<?php echo $post_title_esc;?>" href="<?php echo $post['url'];?>">
                <span class="desktop_title truncate"><?php echo remove_punctuation( $post['title'] );?></span>
            </a>
        </h3>
        <figure class="post_img img_hovered">
           <a title="<?php echo $post_title_esc;?>" href="<?php echo $post['url'];?>">
                <img src="<?php echo $post['image_url'];?>" alt="<?php echo $post_title_esc;?>" />
            </a>
        </figure>
    </article>
    <?php endforeach; ?>

</section>

<script>
    var mostPopularMagazine = {
        init: function(){
            this.$elements = Array.prototype.slice.call( document.querySelectorAll( '.most_popular_section > .most_popular' ) );
            this.index = this.$elements.indexOf( document.querySelector( '.most_popular_section > .most_popular.active' ) );
            this.$activeElement = this.$elements['0'];
            this.pause = false;

            this.bindEvents();

            this.autoplay();
        },
        bindEvents: function(){
            var self = this;
            this.$elements.forEach( function( $element ){
                $element.addEventListener( 'mouseenter', self.stop.bind( self ) );
                $element.addEventListener( 'mouseleave', self.resume.bind( self ) );
            });
        },
        stop: function(){
            this.pause = true;
        },
        resume: function(){
            this.pause = false;
        },
        next: function(){
            if( this.pause === true ) return;
            
            let currentIndex = this.$elements.indexOf( document.querySelector( '.most_popular_section > .most_popular.active' ) );

            this.$activeElement = this.$elements[ currentIndex ];
            this.$activeElement.classList.remove( 'active' );

            this.index = currentIndex + 2 <= this.$elements.length ? currentIndex + 1 : 0;

            this.$activeElement = this.$elements[ this.index ];
            this.$activeElement.classList.add( 'active' );
        
        },
        autoplay: function(){
            setInterval( this.next.bind( this ), 2500 );
        },
    }

    mostPopularMagazine.init();

</script>