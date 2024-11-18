const carouselKeenSliders = [];

let sliderNavigation = ( slider ) => {
  new SliderNavigationPlugin( slider );
}

const initCarousel = function( $carousel ){

    let defaults = {
            loop: false,
            icons: NEWS247.keen_slider_icons,
            slides: { 
                perView: "auto",
                autoPerView: 1,
            },
            breakpoints: {
               '(min-width: 768px)': {
                   slides: {
                     perView: "auto",
                     autoPerView: 2,
                   }
               },
              '(min-width: 1024px)': {
                 slides: {
                    perView: "auto",
                    autoPerView: 3,
                }
              },
              '(min-width: 1350px)': {
                 slides: {
                    perView: "auto",
                    autoPerView: 4,
                }
              }
            }
        },
        settings = JSON.parse( $carousel.dataset.settings || '{}' ),
        keenSliderSettings = Object.assign( {}, defaults, settings );

    carouselKeenSliders.push({
        $node: $carousel,
        slider: new KeenSlider(
            $carousel,
            keenSliderSettings,
            [  sliderNavigation ]
        ),
    });

}

document.querySelectorAll( '.carusel_container__slider' ).forEach( $carousel => {
    if( !$carousel.classList.contains( 'lazy' ) ){
        initCarousel( $carousel );
    }

});


if( document.querySelector( '.post_image_lightbox' ) ){

    document.querySelectorAll( '.post_image_lightbox' ).forEach( $img => {

        $img.closest( '.attachment-container' ).addEventListener( 'click', function( event ){
            event.preventDefault();
            
            let $items = document.querySelectorAll( '.single_article__main .attachment-container' ),
                initialSlide = $items.getElementIndex( this.closest( '.attachment-container' ) );
            
            let lightbox = new KeenSliderLightbox({
                $items: $items,
                imgSelector: '.post_image_lightbox',
                initialSlide: initialSlide,
                paginationTracking: true,
                captionSelector: '.image__credits',
                icons: NEWS247.keen_slider_icons,
            });

            lightbox.init();

        });
     });
}


// Infinite Posts
if( window.InfinitePosts ){
    const Infinite = window.InfinitePosts;

    const infinitePosts = new Infinite({
        excludePosts: typeof infinitePostsExcludePostIds !== 'undefined' ? infinitePostsExcludePostIds : [],
    });
}



// Single Video | Show Video on play button click
if( document.querySelector( '.play_video' ) ){
    document.querySelector( '.play_video' ).addEventListener( 'click', function( e ){
        e.preventDefault();
        this.closest( '.single_article__main_image' ).classList.add( 'featured_video_playing' );
    });
}

// HP set active menu elements on scroll

if( document.querySelector( 'body.home' ) ){

    window.addEventListener( 'scroll', () => {
        
        let $hpSectionsWithId = document.querySelectorAll( '.home_wrapper > section' ).arrayFrom(),
            $visibleSections = $hpSectionsWithId.filter( ( $section ) => {
                return $section.isVisible( 10 );
            }),
            $newActiveMenuItem = $visibleSections.length
             ? document.querySelector( '#menu-primary-menu-for-homepage a[href="#' + $visibleSections.shift().id + '"]' )
             : '',
             $prevActiveMenuItem = document.querySelector( '#menu-primary-menu-for-homepage li.active' );
        
        // remove active class from menu
        if( $prevActiveMenuItem ){
            $prevActiveMenuItem.classList.remove( 'active' );
        }
        
        // if active menu item, set active class to this item
        if( $newActiveMenuItem ){
            $newActiveMenuItem.closest( 'li' ).classList.add( 'active' );
        }
    
    });

    document.querySelectorAll( '.primary_nav .menu-item-type-custom > a' ).forEach( $element => {
        $element.addEventListener( 'click', function( event )  {
            event.preventDefault();

            let $target = document.querySelector( this.getAttribute('href') ),
                $header = document.querySelector( '.global_header_hp .global_header__menu' );

            if( !$target ){
                return;
            }

            let targetBoundingClientRect = $target.getBoundingClientRect();

            window.scrollTo({
                top: targetBoundingClientRect.top + window.scrollY - $header.offsetHeight - 40,
                behavior: "smooth",
            });
        });
    })
}


// Hp weather widget 
if( document.querySelector('.weather_widget') ){
    document.querySelector('.weather_widget').addEventListener( 'click', function( event ){
        event.preventDefault();
        let $container = event.currentTarget,
            $link = document.createElement('a');
    
        $link.target = '_blank';
        $link.href = $container.querySelector('.weather__view_more .caption').href;
        $link.click();
        $link.remove();

    });
}


if( document.querySelector('.select_orderby') ){

     document.querySelector( '.select_orderby' ).addEventListener( 'click', function(){
        this.classList.toggle( 'opened' );
     });

    document.querySelectorAll( '.select_orderby .select_orderby__option' ).forEach( $element => {
        $element.addEventListener( 'click', function(){
            let queryStrings = new URLSearchParams(window.location.search);
            queryStrings.set('orderby', this.dataset.value );
            window.location.href = window.location.origin + window.location.pathname + '?' + queryStrings.toString();
        });
    });
}


if( document.querySelector( '.multi_bg_image' ) ){

    let multiBgImages = function(){

        var $elementsMultiBg = Array.from( document.querySelectorAll( '.multi_bg_image' ) );
        
        $elementsMultiBg.forEach( $el => {

            var viewport = $el.dataset.viewport || 1024,
                desktopImg = $el.dataset.desktopBg,
                mobileImg = $el.dataset.mobileBg;
            
            if( window.innerWidth >= viewport ){
                if( $el.classList.contains('lazyloaded') ){
                    $el.style.backgroundImage = `url('${desktopImg}')`;
                }else{
                    $el.dataset.bg = desktopImg;
                }
               
            }else{
                 if( $el.classList.contains('lazyloaded') ){
                    $el.style.backgroundImage = `url('${mobileImg}')`;
                }else{
                    $el.dataset.bg = mobileImg;
                }
            }

        });
    }

    multiBgImages();

    window.addEventListener( 'resize', multiBgImages );

}