class LazyLoadEmbeds{

  constructor(){

    this.$embedCodes = document.querySelectorAll( '.embed_code__container' );

    if( !this.$embedCodes ) return false;

    this.supportedProviders = [
      'instagram',
      'twitter',
      'glomex',
      'apester-media',
      'getty-images',
      'playbuzz',
      'reddit',
      'tiktok',
      'tumblr',
      'typeform'
    ];

    this.providers = [];
    this.events = {};

    this.init();

    this._bindEvents();

  }

  getEmbedCodeProviderName( $embedCode ){

    var matchedProvider = this.supportedProviders.filter( providerName => {
      return $embedCode.classList.contains( 'provider_' + providerName );
    });

    return matchedProvider.length ? matchedProvider.shift() : null;

  }


  init(){

    this.$embedCodes.forEach( $embedCode => {

      let providerName = this.getEmbedCodeProviderName( $embedCode );

      if( !providerName ) return false;

      this.providers.push({
        $element: $embedCode,
        loaded: false,
        name: providerName,
        $script: document.querySelector( '#' + providerName + '-embed-api' )
      });

    });

  }

  _bindEvents(){

    this.events.scroll = this.onScroll.bind( this );
    window.addEventListener( 'scroll', this.events.scroll );

  }


  onScroll( event ){

    let providersToLoad = this.providers.filter( provider => {
      return provider.$element.isVisible( 1 ) && !provider.loaded;
    });


    if( !providersToLoad.length ){
      if( this.maybeClearScrollEvent() ){
        window.removeEventListener( 'scroll', this.events.scroll );
      }
      return false;
    }

    providersToLoad.forEach( provider => {
      console.log(provider);
      provider.$script.src = provider.$script.dataset.src || provider.$script.getAttribute( 'data-src' );
      console.log( provider.$script.src);
      provider.loaded = true;

      // Update Same provider embed code loaded prop
      this.providers.filter( p => {
        return p.name === provider.name;
      }).forEach( p => {
        p.loaded = true;
      });

    });


  }

  maybeClearScrollEvent(){

    return !this.providers.filter( provider => {
      return !provider.loaded;
    }).length;

  }

}



window.addEventListener( 'load', () => {
  const lazyLoadEmbeds = new LazyLoadEmbeds();
  window.lazyLoadEmbeds = lazyLoadEmbeds;
});
