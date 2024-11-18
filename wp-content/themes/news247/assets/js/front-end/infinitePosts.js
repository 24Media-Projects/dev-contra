class InfinitePosts{

    constructor( props = {} ){

       this.settings = Object.assign({}, this.getDefaultProps, props );

        this.$container = document.querySelector( '.infinite_posts' );
        this.$loadMoreBtn = document.querySelector( '.load_more_posts' );
        
        if( !this.$loadMoreBtn ){
          return;
        }

        this.adsConfig = INP.infinite_posts.ads_config;

        this.postTemplateName = INP.infinite_posts.post_template_name;

        this.archiveUrl              = INP.infinite_posts.archive_url;
        this.pageTitle               = INP.infinite_posts.page_title;
        this.defaultDocumentTitle    = document.title;
        this.pageTitleSuffix         = INP.infinite_posts.page_title_suffix;

        this.restUrl        = INP.infinite_posts.rest_url;
        this.terms          = INP.infinite_posts.terms;
        this.archiveType    = INP.infinite_posts.archive_type;
        this.postTypes      = INP.infinite_posts.post_types.join(',');
        this.inclusiveTerms = INP.infinite_posts.inclusive_terms;
        //used only on search
        this.orderby        = INP.infinite_posts.orderby;
        
        this.perPage = INP.infinite_posts.per_page;
        this.currentPage = INP.infinite_posts.current_page;
        this.totalPostsToFetch = 0;
        this.totalPages = 0;

        this.requestMade = false;

        this.wp = window.wp;

        this.templates = {
          posts: this.wp.template( this.postTemplateName ),
          ad: this.wp.template( 'archive_ad' ),
        }

        this.router = new Router( this );

        this._bindEvents();

    }

    getDefaultProps(){
      return {
        excludePosts: [],
      }
    }

    _bindEvents(){
        this.$loadMoreBtn.addEventListener( 'click', this.getNextPosts.bind( this, null ) );
    }

    /**
     * Get all rest api request properties and rest url
     * 
     * @return {object}
     */
    getRequestProperties(){

      let queryVars = {
        per_page: this.perPage,
        page: this.currentPage,
        first_request: this.requestMade,
        post_types: this.postTypes,
        inclusive_terms: this.inclusiveTerms,
        exclude_posts: this.settings.excludePosts,
        orderby: this.orderby,
      };

      if( this.archiveType == 'category' ){
        queryVars.categories = this.terms;

      }else if( this.archiveType == 'post_tag' ){
        queryVars.tags = this.terms;
      
      }else if( this.archiveType == 'author' ){
        queryVars.author_id = this.terms;
      
      }else if( this.archiveType == 'search' ){
        queryVars.search_query = this.terms;
      }

      let pageLabel = this.currentPage > 1 ? ' - Σελίδα' : '',
          documentTitle = `${this.pageTitle} ${pageLabel} ${this.currentPage} ${this.pageTitleSuffix}`;

      return {
        queryVars: queryVars,
        url: this.restUrl,
        documentTitle: documentTitle,
        currentPage: this.currentPage,
      }
    }

    /**
     * Add loader class to container
     */
    addLoader(){

      this.$container.classList.add( 'loading' );

    }

    /**
     * Remove loader class from container
     */
    removeLoader(){
      this.$container.classList.remove( 'loading' );
    }

    /**
     * Main method to fetch next posts
     * 
     * @param {null|object} args 
     * @param {null|Event} event 
     */
    async getNextPosts( args = null, event = null ){

      if( event ){
        event.preventDefault();
      }
      
      this.addLoader();
      
      this.currentPage = args !== null
        ? args.currentPage
        : this.currentPage + 1;

        console.log(this.currentPage);

      let posts = await this.fetchPosts( args );

      if( posts ){

        this.insertPostsToDom( posts ); 

        // document title
        if( args !== null ){
          document.title = args.documentTitle;

        }else{
          document.title = this.getRequestProperties().documentTitle;
        }
        
        this.router.pushState(
          {
            router: this.getRequestProperties(),
            permalink: `${this.archiveUrl}page/${this.currentPage}`,
          },
          args === null
        );

        
      }

      // check if we are on last page
      console.log( this.currentPage, this.totalPages );
      if( this.currentPage == this.totalPages ){
        this.$loadMoreBtn.parentNode.removeChild( this.$loadMoreBtn );
      }
      
      this.removeLoader();

    }


    insertPostsToDom( posts ){

      let batches = [
            posts.slice( 0, 10 ),
            posts.slice( 10, 20 ),
            posts.slice( 20 )
          ];
      
      console.warn( batches );

      batches.forEach( ( batch, index ) => {
        batch.forEach( post => {
          let postHTML = this.templates.posts( post );
          this.$container.innerHTML += postHTML;
        });

        if( index < 2 ){
          this.insertAD( index );
        }

      });

    }

    insertAD( index = '' ){

      let slotId = `term_inline_infinite_${index}_${this.currentPage}`,
          $adContainerHTML = this.templates.ad({ slotId: slotId });
        
      this.$container.innerHTML += $adContainerHTML;

      googletag.cmd.push(() => {
        gptAdSlots.push(googletag.defineSlot('/4834629/news247.gr/' + this.adsConfig.slot_name, [[300, 250], [728, 90], [336, 280], [660, 100], [468, 60]], slotId)
                  .defineSizeMapping( mappingSizes['mapping4'] )
                  .addService(googletag.pubads()) );
           
          googletag.display( slotId );
        
      });


    }

  updateFromRouter( args ){

    console.log( document.querySelectorAll( '.appended_post' ));

    
    document.querySelectorAll( '.appended_post' ).forEach( $item => {
      $item.remove();
    });
    
    if( args !== null && args.router !== undefined ){
      console.log('router');
      this.getNextPosts( args.router );
    
    // Default data from init page load
    }else{
      document.title = this.defaultDocumentTitle;
    }

  }



  async fetchPosts( args = {} ){

    let requestProperties = this.getRequestProperties(),
        queryVars = args ? args.queryVars : requestProperties.queryVars,
        url = requestProperties.url;

    queryVars = new URLSearchParams( queryVars );

    console.warn(queryVars.toString());

    try{

      let response = await fetch( url + '?' +  queryVars.toString(), {
        method: 'GET',
        mode: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
        },
      });

      if( response.status != 200 ){
        this.handleError( 'Status code: ' + response.status );
        return [];
      }

      let responseData = await response.json(),
          totalPages = response.headers.get( 'X-WP-TotalPages' ),
          totalPosts = response.headers.get( 'X-WP-Total' );

        this.totalPages = totalPages || 1;
        this.totalPosts = totalPosts || 1;


      if( responseData === false ){
        this.handleError( 'Response data is false' );
        return [];
      }

      return responseData;

    }catch( error ){
      this.handleError( error );
       return [];
    }

  }

  handleError( error ){
    console.log(error);
  }




}

window.InfinitePosts = InfinitePosts;


class Router{

  constructor( object ){

    this.history = history;
    this.object = object;

    this.bindEvents();

  }

  bindEvents(){
    window.addEventListener( 'popstate', this.onPopStateChange.bind( this ) );
  }


  pushState( props, state = 'push' ){

    if( state === 'replace' ){

      this.initState( post );
      return true;

    }

    if( state === 'reset' ){

      history.pushState( { router: props.router }, "", props.permalink );

      return true;
    }

    if( state === false ) return false;

    history.pushState( { router: props.router }, "", props.permalink );
  }


  initState( props ){
    history.replaceState( { router: props.router }, "", props.permalink );
  }


  onPopStateChange( event ){

    console.log(event.state );

    // if( event.state && event.state.router !== undefined && event.state.router !== null ){

      this.object.updateFromRouter( event.state );

    // }
  }

}


window.InfinitePosts = InfinitePosts;