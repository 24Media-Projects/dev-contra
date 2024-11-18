class SliderNavigationPlugin{

  constructor( slider ){

    this.slider = slider;
    this.$wrapper = slider.container.parentNode;

    // Add container Class if is set on keen slide options
    if( slider.options.containerClass !== undefined ){
       slider.container.classList.add( slider.options.containerClass );
    }
    
    this.addLoader();

    let totalSlides = slider.slides.length;

    /**
     * Arrows, dots, pagination tracking require more than one slide
     */
    this.displayDots = totalSlides > 1 && ( slider.container.classList.contains( 'with_dots' ) || slider.options.dots === true );  
    this.displayArrows = totalSlides > 1 && ( slider.container.classList.contains( 'with_arrows' ) || slider.options.arrows === true );  
    this.displayPaginationTracking = totalSlides > 1 && ( slider.container.classList.contains( 'with_pagination_tracking' ) || slider.options.paginationTracking === true );

    /**
     * Default arrow icons
     */
    this.icons = this.slider.options.icons || { prev: '', next: '' };


    // Autoplay
    let self = this;

    this.autoplay = {
      active: this.slider.container.dataset.autoplay !== undefined || this.slider.options.autoplaySpeed !== undefined,
      pause: false,
      timeout: null,
      speed: Number( slider.container.dataset.autoplay ) || this.slider.options.autoplaySpeed,
      onMouseOver: function(){
        this.pause = true;
        console.warn('pause')
        this.clearTimeout();
      },
      onMouseOut: function(){
        this.pause = false;
        this.play();
      },
      clearTimeout: function(){
        clearTimeout( this.timeout );
      },
      play: function(){
        if( this.pause ) return;
        this.clearTimeout();
         console.warn('play')
        this.timeout = setTimeout( self.slider.next.bind(self.slider), this.speed );
      },
      listeners: function(){
        return {
            mouseover: this.onMouseOver.bind( this ),
            mouseout: this.onMouseOut.bind( this ),
          }
      },
    }

    this.reset();
    this._bindEvents();

  }

  reset(){

    if( this.displayDots && this.$dotsContainer ){
      this.removeElement( this.$dotsContainer );
    }
    
    if( this.displayArrows && this.$arrowsContainer ){
      this.removeElement( this.$arrowsContainer );
    }

    if( this.displayPaginationTracking && this.$paginationTracking ){
      this.removeElement( this.$paginationTracking );
    }

    this.$dotsContainer = null;
    this.$arrowsContainer = null;

    this.$paginationTracking = null;

    this.$dots = [];
    this.$arrowLeft = null;
    this.$arrowRight = null;

  }

  _bindEvents(){

    this.slider.on( 'created', this.onSliderCreated.bind( this ) );
    this.slider.on( 'optionsChanged', this.onSliderOptionChanged.bind( this ) );
    this.slider.on( 'slideChanged', this.onSliderChanged.bind( this ) );
    this.slider.on( 'destroyed', this.destroy.bind( this ) );

    if( this.autoplay.active === true ){
        this.slider.on( 'dragStarted', this.autoplay.clearTimeout.bind( this.autoplay ) );
        this.slider.on( 'animationEnded', this.autoplay.play.bind( this.autoplay ) );
        this.slider.on( 'updated', this.autoplay.play.bind( this.autoplay ) );
    }

  }

  createElement( props = {} ){

      let $element = document.createElement( props.tagName );

      $element.className = props.className;
      props.$parent.appendChild( $element );

      if (props.ariaLabel !== undefined) {
        $element.ariaLabel = props.ariaLabel;
      }

      if( props.innerHTML !== undefined ){
        $element.insertAdjacentHTML( 'afterbegin', props.innerHTML );
      }




      return $element;
  }


  removeElement( $element ){
    $element.parentNode.removeChild( $element );
  }


  slidesPerView(){

    // Check if slider is set to auto and a autoPerView is defined
    if( this.slider.options.slides
        && this.slider.options.slides.perView == 'auto'
        && this.slider.options.slides.autoPerView
    ){
      return Number(  this.slider.options.slides.autoPerView );
    }


    let slidesPerView = this.slider.options.slides && this.slider.options.slides.perView && this.slider.options.slides.perView != 'auto'
      ? this.slider.options.slides.perView
      : 1;

      return Number( slidesPerView );
  }


  renderDots(){

    this.$dotsContainer = this.createElement({
      tagName: 'ul',
      className: 'dots',
      $parent: this.$wrapper,
    });
    
    let dotsLength = this.slidesPerView() > 1
      ? Math.ceil( this.slider.track.details.slides.length / this.slidesPerView() )
      : this.slider.track.details.slides.length;

    for( let idx = 0; idx < dotsLength; idx++ ){

      let $dotWrapper = this.createElement({
        tagName: 'li',
        className: 'dot', 
        $parent: this.$dotsContainer,
      });

      let $dot = this.createElement({
        tagName: 'button',
        className: 'dot ' + ( idx === 0 ? 'dot__active' : '' ), 
        $parent: $dotWrapper,
        innerHTML: '<span class="invisible">' + idx + '</span>',
        ariaLabel: idx,
      });

      /**
       * Get real index depending on slidesPerView().
       * The max index is: this.slider.track.details.maxIdx
       */
      let realIndex = idx * this.slidesPerView() <= this.slider.track.details.maxIdx
        ? idx * this.slidesPerView()
        : this.slider.track.details.maxIdx;

      $dot.addEventListener( 'click', this.slider.moveToIdx.bind( this.slider, realIndex ) );

      this.$dots.push( $dot );
      $dot = '';

    }

  }

  
  renderArrows(){

    this.$arrowsContainer = this.createElement({
      tagName: 'div',
      className: 'arrows_container',
      $parent: this.$wrapper,
    });

    this.$arrowLeft = this.createElement({
      tagName: 'button',
      className: 'arrow arrow__left',
      $parent: this.$arrowsContainer,
      innerHTML: this.icons.prev + '<span class="invisible">Prev</span>',
      ariaLabel: 'Prev',
    });

    // this.$arrowLeft.addEventListener( 'click', this.slider.prev.bind( this.slider ) );
    this.$arrowLeft.addEventListener( 'click', this.prev.bind( this ) );
    
    this.$arrowRight = this.createElement({
      tagName: 'button',
      className: 'arrow arrow__right',
      $parent: this.$arrowsContainer,
      innerHTML: this.icons.next + '<span class="invisible">Next</span>',
      ariaLabel: 'Next',
    });

    // this.$arrowRight.addEventListener( 'click', this.slider.next.bind( this.slider ) );
    this.$arrowRight.addEventListener( 'click', this.next.bind( this ) );

  }

  prev(){

    let slidesPerView = this.slidesPerView();

    if( this.slider.options.loop === true ){
      this.slider.moveToIdx(this.slider.track.details.abs - slidesPerView, true);
      return;
    }

    let index = this.slider.track.details.abs - slidesPerView >= 0
      ? this.slider.track.details.abs - slidesPerView
      : 0;

    this.slider.moveToIdx( index );

  }

  next(){

    let slidesPerView = this.slidesPerView();

    if( this.slider.options.loop === true ){
      this.slider.moveToIdx(this.slider.track.details.abs + slidesPerView, true);
      return;
    }

    let index = this.slider.track.details.abs + slidesPerView <= this.slider.track.details.maxIdx
      ? this.slider.track.details.abs + slidesPerView
      : this.slider.track.details.maxIdx;

    this.slider.moveToIdx( index );
  }


  renderPaginationTracking(){
    this.$paginationTracking = this.createElement({
      tagName: 'span',
      className: 'pagination_tracking',
      $parent: this.$wrapper,
      innerHTML: ( this.slider.options.initial + 1 ) +  '/' + this.slider.track.details.slides.length
    });

  }


  
  updateClasses(){

    if( this.slider.track.details === null ){
      return;
    }

    let slide = this.slider.track.details.rel;

    if( this.displayArrows ){

      slide === 0
        ? this.$arrowLeft.classList.add( 'arrow__disabled' )
        : this.$arrowLeft.classList.remove( 'arrow__disabled' );

      this.slider.track.details.rel == this.slider.track.details.maxIdx
        ? this.$arrowRight.classList.add( 'arrow__disabled' )
        : this.$arrowRight.classList.remove( 'arrow__disabled' );

    }

    if( this.displayDots ){
      
      // Remove active class from dot
      if( this.$dotsContainer.querySelector( '.dot__active' ) ){
          this.$dotsContainer.querySelector( '.dot__active' ).classList.remove( 'dot__active' );
       }

       // get index of active dot
       let index = Math.ceil( slide / this.slidesPerView() );

       this.$dotsContainer.querySelectorAll('.dot > button')[ index ].classList.add( 'dot__active' );

    }

    // pagination tracking
    if( this.displayPaginationTracking ){
      this.$paginationTracking.innerHTML = ( slide + 1 ) + '/' + this.slider.track.details.slides.length;
    }

  }

  addLoader(){
    this.$wrapper.classList.add( 'loading' );
  }

  removeLoader(){
    this.$wrapper.classList.remove( 'loading' );
  }
 
  onSliderCreated(){

    if( this.displayDots ){
      this.renderDots();
    }

    if( this.displayArrows ){
      this.renderArrows();
    }

    if( this.displayPaginationTracking ){
      this.renderPaginationTracking();
    }

    this.updateClasses();

    if( this.autoplay.active ){
      this.slider.container.addEventListener( 'mouseover', this.autoplay.listeners().mouseover );
      this.slider.container.addEventListener( 'mouseout', this.autoplay.listeners().mouseout );
      this.autoplay.play();
    }
    
    this.removeLoader();

  }

  onSliderOptionChanged(){

    this.reset(); 

    this.onSliderCreated();
    this.updateClasses();

  }

  onSliderChanged(){

      this.updateClasses();

  }

  destroy(){

    if( this.autoplay.active ){
      this.autoplay.clearTimeout();
      this.slider.container.removeEventListener( 'mouseover', this.autoplay.listeners().mouseover );
      this.slider.container.removeEventListener( 'mouseout', this.autoplay.listeners().mouseout );
    }

  }


}

window.SliderNavigationPlugin = SliderNavigationPlugin;