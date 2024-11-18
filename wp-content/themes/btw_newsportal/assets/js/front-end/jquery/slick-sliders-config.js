( function( $ ){

  var slickResponsives = {},
      winResize = null,
      timer = 750;

  $.fn.btwSlick = function( slickConfig ){

    if( slickConfig.maybeInit == undefined ){
      slickConfig.maybeInit = function(){
        return true;
      }
    }

    if( !this.length ) return false;

    // Set slick config of selector to slickResponsives
    slickResponsives[ slickConfig.selector ] = slickConfig;

    // if cannot init return false;
    if( !slickConfig.maybeInit() ) return false;

    this.slick( slickConfig );


  }


  $( window ).on( 'resize orientationchange', function(){
    clearTimeout( winResize );

    winResize = setTimeout( function(){

      for( var selector in slickResponsives ){
        var slickConfig = slickResponsives[ selector ];

        if( $( selector ).hasClass( 'slick-initialized' ) ){
          $( selector ).slick( 'unslick' );
        }
        
        if( slickConfig.maybeInit() ){
          $( selector ).slick( slickConfig );
        }

      }

    }, timer );

  });


})( jQuery );
