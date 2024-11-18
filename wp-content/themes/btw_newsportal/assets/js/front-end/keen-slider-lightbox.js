
class KeenSliderLightbox{

  constructor( props ){

    this.defaults = {
      autoInit: false,
      arrows: true,
      dots: false,
      paginationTracking: false,
      imgSelector: null,
      captionSelector: null,
      renderMode: 'default', // performance, fade, default
      animationDuration: 800, // For fade renderMode
      initialSlide: 0,
      caption: ( $item ) => {
        return null;
      },
      icons: {
        close: '',
        prev: '',
        next: '',
      }
    }

    this.settings = Object.assign( {}, this.defaults, props );

    this.$items = props.$items;
    this.$slides = [];

    this.elementsToCreate = [{
      name: '$lightbox',
      type: 'div',
      parent: null,
      className: 'keen-lightbox',
    },{
      name: '$lightboxInner',
      type: 'div',
      parent: '$lightbox',
      className: 'keen-lightbox-inner'
    },{
      name: '$keenSlider',
      type: 'div',
      parent: '$lightboxInner',
      className: 'keen-slider'
    },{
      name: '$closeBtn',
      type: 'button',
      parent: '$lightboxInner',
      className: 'keen-lightbox-close',
      innerHTML: this.settings.icons.close,
    }];

    if( this.settings.autoInit === true ){
      this._bindEvents();
    }
  }


  _bindEvents(){

    this.$items.forEach( ( $element, index ) => {

      $element.addEventListener( 'click', this.init.bind( this, index ) );
    });

  }

  createHtmlElements(){

    this.elementsToCreate.forEach( ( element ) => {

      this[ element.name ] = document.createElement( element.type );
      this[ element.name ].className = element.className;
      this[ element.name ].innerHTML = element.innerHTML ?? '';

      if( element.parent === null ){
        document.body.appendChild( this[ element.name ] );

      }else{
        this[ element.parent ].appendChild( this[ element.name ] );
      }

    });

  }

  init( index, event ){

    this.createHtmlElements();

    this.$items.forEach( $item => {
      let $img = document.createElement( 'img' ),
          $slideContainer = document.createElement( 'div' ),
          $imgContainer = document.createElement( 'figure' ),
          $originalImg = this.settings.imgSelector ? $item.querySelector( this.settings.imgSelector ) : $item;

      $slideContainer.className = 'keen-slider__slide';
      this.$keenSlider.appendChild( $slideContainer );

       $imgContainer.className = 'img_container';
       $slideContainer.appendChild( $imgContainer );
      
      $img.src = $originalImg.dataset.lightboxSrc || $originalImg.dataset.src || $originalImg.src;

      $imgContainer.classList.add( $img.naturalWidth > $img.naturalHeight ? 'landscape' : 'portrait' );

      $imgContainer.appendChild( $img );

      // image caption
      if( this.settings.captionSelector && $item.querySelector( this.settings.captionSelector ) ){

          let $caption = document.createElement( 'div' );

          $caption.className = 'img_caption';
          $caption.innerHTML = $item.querySelector( this.settings.captionSelector ).innerHTML;
          $slideContainer.appendChild( $caption );

      }

      // image custom caption 
      if( !this.settings.captionSelector && this.settings.caption( $item ) ){
          let $caption = document.createElement( 'div' );

          $caption.className = 'img_caption';
          $caption.innerHTML = this.settings.caption( $item );
          $slideContainer.appendChild( $caption );
      }

    });

    this.listeners = {
      destroy: this.destroy.bind( this ),
      destroyOnEscKeyPress: this.destroyOnEscKeyPress.bind( this ),
    }

    this.render( index );

    document.body.classList.add( 'lightbox_is_open' );

  }

  render( index ){

    const keenSlider = window.KeenSlider;

    const sliderNavigation = ( slider ) => {
      new SliderNavigationPlugin( slider );
    }

    let keenSliderArgs = {
        loop: true,
        paginationTracking: this.settings.paginationTracking,
        arrows:  this.settings.arrows,
        dots:  this.settings.dots,
        icons: this.settings.icons,
        containerClass: `${this.settings.renderMode}_mode`,
        initial: Number( this.settings.initialSlide ),
        created: instance => {

          this.slider = instance;
          this.slider.moveToIdx( index, 0 );
          
          this.bindLightboxEvents();

        },
    };

    if( this.settings.renderMode === 'fade' ){

      keenSliderArgs = Object.assign( {}, keenSliderArgs, {
          renderMode:  'custom',
          defaultAnimation: {
            duration: this.settings.animationDuration,
          },
          detailsChanged: ( s ) => {
            s.slides.forEach( ( element, idx ) => {
              element.style.opacity = s.track.details.slides[ idx ].portion;
            })
          },
      });

    }else if( this.settings.renderMode == 'performance' ){
      keenSliderArgs.renderMode = 'performance';
    }

    console.log(keenSliderArgs);

    const lightbox = new KeenSlider( '.keen-lightbox .keen-slider', keenSliderArgs, [ sliderNavigation ] );

  }


  bindLightboxEvents(){

    this.$closeBtn.addEventListener( 'click', this.listeners.destroy );
    window.addEventListener( 'keyup', this.listeners.destroyOnEscKeyPress );

  }

  removeLightboxEvents(){

    this.$closeBtn.removeEventListener( 'click', this.listeners.destroy );
    window.removeEventListener( 'keyup', this.listeners.destroyOnEscKeyPress );

  }

  destroy(){

    document.body.classList.remove( 'lightbox_is_open' );

    this.slider.destroy();
    this.removeLightboxEvents();
    this.$lightbox.remove();

  }

  destroyOnEscKeyPress( event ){
    if( event.key.toLowerCase() == "escape" || event.keyCode == '27' ){
      this.destroy();
    }
  }


}


window.KeenSliderLightbox = KeenSliderLightbox;
