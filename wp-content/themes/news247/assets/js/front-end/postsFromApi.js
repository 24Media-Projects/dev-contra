export default class News247PostsFromApi{

    constructor( selector = '', props = {} ){

        if( !selector || !props.apiUrl || !props.template ){
            return;
        }

        this.$container = document.querySelector( selector );
        this.apiUrl = props.apiUrl;

        /**
         * Get default callbacks and overide them with props.methods, if are present
         */
        this.callbacks = Object.assign( {}, this.getDefaultCallbacks(), ( props.callbacks || {} ) );
        
        this.settings = Object.assign( {}, this.getDefaultSettings(), ( props.settings || {} ) );

        this.wp = window.wp;

        this.template = this.wp.template( props.template );

        this.render();

    }

    /**
     * Get default methods
     * 
     * @returns {object}
     */
    getDefaultCallbacks(){
        return {
            onRenderCompleted: ( posts ) => {},
        };
    }    

    /**
     * Get default methods
     * 
     * @returns {object}
     */
    getDefaultSettings(){
        return {
            queryVars: {},
            fetchMode: 'same-origin',
        }
    }


    /**
     * Format posts
     * 
     * @param {array} responseData 
     * @returns {array}
     */
     formatPosts( responseData ){
        
        let posts = responseData.posts;

        if( Array.isArray(posts) === false ){
            posts = [ posts ];
        }

        return posts;
    }


    /**
     * Error Handlder
     * @param {string} error 
     */
    handleError( error ){
        console.log(error);
    }



    /**
     * Remove loader class from container
     */
    removeLoader(){
      this.$container.classList.remove( 'loading' );
    }

    /**
     * Fetch post with ajax
     * @returns {array}
     */
    async fetchPosts(){

        let queryVars = new URLSearchParams( this.settings.queryVars ),
            queryVarsToString = queryVars.toString(),
            finalApiUrl = this.apiUrl + ( queryVarsToString ? '?' + queryVarsToString : '' );

        try{

            let response = await fetch( finalApiUrl, {
                method: 'GET',
                mode: this.settings.fetchMode,
                headers: {
                    'Content-Type': 'application/json',
                },
            });

            if( response.status != 200 ){
                this.handleError( 'Status code: ' + response.status );
                return [];
            }

            let responseData = await response.json();

            if( responseData === false ){
                this.handleError( 'Response data is false' );
                return [];
            }

            return this.formatPosts( responseData );

        }catch( error ){
            this.handleError( error );
            return [];
        }

    }



    /**
     * Render items
     */
    async render(){

        try{

            let posts = await this.fetchPosts();
            
            /**
             * @todo What to do if no posts are returned
             */
            if( !posts ){
                this.removeLoader();
                return;
            }

            for( let post of posts ){
                
                let postHTML = this.template( post );
                this.$container.innerHTML += postHTML;
            }

            this.renderCompleted( posts );

            /**
             * onRenderCompleted callback
             */ 
            this.callbacks.onRenderCompleted.apply( this, posts );

            this.removeLoader();

        }catch( error ){
            this.handleError( error );
            this.removeLoader();
        }

    }


    renderCompleted( posts ){

        /**
         * Refresh elements of Truncate and apply truncate on bon posts
         */
        truncate.update();
        truncate.truncate();

    }


}

window.PostsFromApi = News247PostsFromApi;

