(function($) {
  // "use strict";


   // Read Also modal
  var ReadAlso = function(options){
    this.options = $.extend({}, this.defaults, options);

    this.$modal = $('.read-also-modal');
    this.$openModalButton = $('.read-also-insert-button');
    this.openModalButtonSelector = '.read-also-insert-button';
    this.$closeModalButton = this.$modal.find('.btw-editor-modal__close');
    this.$choicesContainer = $('.read-also__choices ul');
    this.choiceSelector = '.read-also__choices li';
    this.$valuesContainer = $('.read-also__values ul');
    this.removeValueSelector = '.read-also__values .remove_value';
    this.$form = $('.read-also-modal__form');
    this.$searchField = $('.read-also-posts__search');
    this.$tagField = $('.read-also-posts__tags');

    if(!this.$openModalButton.length) return;


    this.xhr = null;
    this.maxSelected = this.options.maxSelected;
    this.hasSortcodeInstance = false;
    this.enableLogs = this.options.enableLogs;
    this.lastSearchQuery = '';
    this.postsPerPage = this.options.postsPerPage;

    if(this.options.sortable && this.maxSelected > 1){
      $('.ui-sortable').sortable();
      this.$valuesContainer.addClass('is-sortable');

    }

    this._bindEvents();
    this._select2();
    this.registerEditorActions();
  }

  /*
    Read Also options
    maxSelected: Integer
    sortable: Boolean
    enableLogs: Boolean. If true, bebug mode is enbled and messages are printed to console
  */

  ReadAlso.prototype.defaults = {
    maxSelected : 1,
    sortable: true,
    enableLogs: false,
    postsPerPage: 50,
  };

  // Read also Bedug
  ReadAlso.prototype.debug = function(message){
    if(!this.enableLogs) return;
    console.log(message);
  }


  /*
    The main function to get Posts
    args: object, posible properties:
    clear: mix Boolean / String, see clearData function
    action: String. The ajax action used to get Posts
    current_post_id: String. The current post that is editing
  */
  ReadAlso.prototype.getData = function(args = {}){

    if(args.hasOwnProperty('clear')){
      if(args.clear === true){
        this.clearData();
      }else if(args.clear == 'all'){
        this.clearData(true);
      }
    }


    var self = this,
        data = {
          action: 'read_also__get_data',
          current_post_id: $('#post_ID').val(),
          selected_posts: args.hasOwnProperty('selectedPosts') ? args.selectedPosts : [],
          search: this.$searchField.val() ? this.$searchField.val() : null, // args.hasOwnProperty('search')
          tag: this.$tagField.val()  ? this.$tagField.val() : null,
          nonce: BTW.wpEditorNonce,
          page: args.hasOwnProperty('page') ? args.page : 1,
          posts_per_page: this.postsPerPage,
        };

    this.debug(data);

    if(this.xhr) this.xhr.abort();

    this.xhr = $.post({
      url: BTW.ajaxUrl,
      data:data,
      error: function(request, status, error) {
        if(request.statusText.toLowerCase() != 'abort'){
          alert(request.responseText);
        }
      },
      success:function(data){
        self.debug(data);

        if(!data.success){
          alert(BTW.ajaxErrorMsg);
          return false;
        }
        self.$choicesContainer.removeClass('loading');
        data.choices.forEach(function(e,i){
          self.$choicesContainer.append('<li data-post-id="' + e.ID + '">' +
                                           e.postTitle +
                                        '</li>'
                                      );
        });

        data.values.forEach(function(e,i){
          self.$valuesContainer.append('<li data-post-id="' + e.ID + '">' +
                                          '<span class="remove_value">x</span>' +
                                           e.postTitle +
                                        '</li>'
                                      );
        });

      }
    });

  }

 // Read Also Events
  ReadAlso.prototype._bindEvents = function(){

    this.$form.on('submit',this.insertShortCode.bind(this));
    $(document).on('click', this.choiceSelector, this.insertValue.bind(this));
    $(document).on('click', this.removeValueSelector, this.removeValue);

    $(document).on('click',this.openModalButtonSelector,this.toggleModal.bind(this));
    this.$closeModalButton.on('click',this.toggleModal.bind(this));

    this.$choicesContainer.parent().on('scroll',this.loadMorePosts.bind(this));

    var self = this;

    this.$searchField
    .on('keyup',function(e){

      var key = e.charCode || e.keyCode;
      if( key == 13 || ( self.lastSearchQuery == $(this).val().trim() && !$(this).val().trim() ) ){
        return;
      }

      self.lastSearchQuery = $(this).val().trim();
      self.getData({clear:true});

    })
    .on('keypress',function(e){
      var key = e.charCode || e.keyCode;
      if(key == 13){
        e.preventDefault();
      }
    });

  }


  ReadAlso.prototype._select2 = function(){
    var self = this;

    this.$tagField.select2({
      placeholder:'ΕΠΙΛΕΞΤΕ TAG',
      allowClear: true,
      minimumInputLength: 3,
      ajax: {
        url: BTW.ajaxUrl,
        dataType: 'json',
        delay: 250,
        data: function (params) {
          return {
            search: params.term,
            action:'read_also__get_post_tags',
            nonce: BTW.wpEditorNonce,
            current_post_id: $('#post_ID').val(),
          }
        },
        processResults: function (data) {
          self.debug(data);
          return { results: data };
        },
      }
    })
    .on('select2:select',function(e){
      self.getData({clear:true});
    })
    .on('select2:clear',function(e){
      self.getData({clear:true});
    })
  }


  ReadAlso.prototype.toggleModal = function(event){
    event.preventDefault();
    this.$modal.toggleClass('opened');

    if(this.$modal.hasClass('opened')){
      this.$modal.focus();
      this.getData({clear: 'all'});
    }else{
      this.hasSortcodeInstance = false;
    }
  }


  ReadAlso.prototype.insertValue = function(event){
    var thisPost = $(event.target);
    if(this.maxSelected <= this.$valuesContainer.find('li').length || thisPost.hasClass('selected') || thisPost.data('post-id') == -1){
      return;
    }

    var thisPostTitle = thisPost.html(),
        thisPostID = thisPost.data('post-id');


    thisPost.addClass('selected');

    this.$valuesContainer.append(
      '<li data-post-id="' + thisPostID + '">' +
      '<span class="remove_value">x</span>' +
       thisPostTitle +
      '</li>'
    );

  }

  ReadAlso.prototype.removeValue = function(event){
    $(this).parent().remove();
  }


  ReadAlso.prototype.insertShortCode = function(event){
    event.preventDefault();

    var selectedPosts = [];

    this.$valuesContainer.find('li').each(function(){
      selectedPosts.push($(this).data('post-id'));
    });

    if(!selectedPosts.length) return;

    var code = '[read_also_sc posts="' + selectedPosts + '"]';

    if(this.hasSortcodeInstance){

      this.$modal.trigger('update',[code]);

    }else{
      window.send_to_editor(code);
    }

    this.$modal.removeClass('opened');
  }



  ReadAlso.prototype.loadMorePosts = function (event ){

    if( !this.$choicesContainer.children('li').last().length ) return;

    var   $t = this.$choicesContainer.children('li').last(),
          $w = $(event.target),
          viewTop = $w.scrollTop(),
          viewBottom = viewTop + $w.height(),
          _top = $t.offset().top - this.$choicesContainer.offset().top,
          _bottom = _top + $t.height(),
          compareTop = _bottom,
          compareBottom = _top;

    if( compareBottom <= viewBottom && compareTop >= viewTop ){
      this.getData({page:( this.$choicesContainer.children('li').length/this.postsPerPage ) + 1})
    }


  }



  ReadAlso.prototype.clearData = function(clearAll = false, addLoaded = true){
    this.$choicesContainer.html('');
    if(clearAll){
      this.$searchField.val('');
      this.$tagField.val(null).trigger('change');
      this.$valuesContainer.html('');
    }

    if(addLoaded){
      this.$choicesContainer.addClass('loading');
    }
  }


  ReadAlso.prototype.registerEditorActions = function(){

    if( typeof wp === 'undefined' || typeof wp.media === 'undefined' ){
      return this.debug('Cannot init Read Also Module. wp or wp.media is undefined');
    }

    var self = this,
        media = wp.media;

    if( typeof wp.mce.views.register !== "function"){
      return this.debug('Cannot init Read Also Module. wp.mce.views.register is undefined');
    }

    var readAlsoShortcode =  {
      template: media.template( 'tmpl-editor-read-also' ),
      initialize: function() {
          this.fetch();
      },
      setLoader: function() {
        this.setContent(
          '<div class="loading-placeholder">' +
            '<div class="dashicons dashicons-update" style="color:#a3be5f;"></div>' +
            '<div class="wpview-loading"><ins style="background-color:#a3be5f;"></ins></div>' +
          '</div>'
        );
      },
      fetch: function() {
        var that = this,
          data = {
          post_id: $('#post_ID').val(),
          atts: that.shortcode.attrs
        };

        wp.ajax.post( 'read_also__preview', data )
        .done( function( response ) {
          self.debug(response);
          that.render( response.html );
        })
        .fail( function( response ) {
          alert(BTW.ajaxErrorMsg);
        });
      },
      edit: function( text, update ) {
        var type = this.type,
            values = this.shortcode.attrs.named,
            selectedPosts = values.posts;

        self.$modal.on('update',function(event,code){
          update(code,type);
          self.hasSortcodeInstance = false;
          $(this).off('update');
        });

        self.$modal.addClass('opened').focus();
        self.getData({selectedPosts:selectedPosts,clear:'all'});
        self.hasSortcodeInstance = true;
      },
    };


    wp.mce.views.register( 'read_also_sc', _.extend( {}, readAlsoShortcode ) );

  }


  window.ReadAlso = ReadAlso;


})(jQuery);
