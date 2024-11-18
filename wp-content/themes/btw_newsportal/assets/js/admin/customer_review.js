( function( $ ){

  $( document ).ready( function(){


    var CustomerReview = function(){

      this.$acfCustomerReviewCheckbox = $( '#acf-field_615a9a0836afa' );
      this.$acfCustomerReviewUrl = $( '.customer_review_post_url .acf-input' );
      this.editPostSlugSelector = '#edit-slug-buttons > .save';

      if( !this.$acfCustomerReviewCheckbox.length || !this.$acfCustomerReviewUrl.length ) return false;

      this._bindEvents();
    }

    CustomerReview.prototype._bindEvents = function(){

      this.$acfCustomerReviewCheckbox.on( 'change', this.setReviewUrl.bind( this ) );
      $( document ).on( 'click', this.editPostSlugSelector, this.setReviewUrl.bind( this ) );

    }

    CustomerReview.prototype.setReviewUrl = function(){

      var postID = $('#post_ID').val(),
          postName = $( '#post_name' ).val() ? $( '#post_name' ).val() : $( '#new-post-slug' ).val(),
          html;

          console.log(postName );

      if( !postName ){
        this.$acfCustomerReviewUrl.html( BTW_CR.notifications.error_no_post_name );
        return;
      }

      html = this.$acfCustomerReviewUrl.html().replace( '<post_name>', postName );
      this.$acfCustomerReviewUrl.html( html );

    }


    var customerReview = new CustomerReview();

  }) // doc end

})( jQuery );
