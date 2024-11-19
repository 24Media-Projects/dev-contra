(function($) {
  // "use strict";

  // Blockquote module JS
  // Based on wp mce-views
  // See
  //      https://github.com/WordPress/WordPress/blob/master/wp-includes/js/mce-view.js
  //      inc/admin/editor/class-btw-admin-editor.php
  //      for more details



  var Blockquote = function(options){
    
    this.options = $.extend({}, this.defaults, options);


    this.$modal = $('.blockquote-modal');
    this.$openModalButton = $('.blockquote-insert-button');
    this.openModalButtonSelector = '.blockquote-insert-button';
    this.$closeModalButton = this.$modal.find('.btw-editor-modal__close');
    this.$form = $('.blockquote-modal__form');
    this.$formButton = $('.blockquote-modal__btn');
    
    this.$savePostForm = $('#post');

    if( !this.$openModalButton.length ) return;

     this.tinymce = tinymce;
     this.tinymceInstance = null;
     this.tinymceSeletor = 'blockquote_field__content';

    this.xhr = null;
    this.hasSortcodeInstance = false;
    this.enableLogs = this.options.enableLogs;

    this.postType = $('#post_type').val();

    this.fields = {
      citation: $('.blockquote_field__citation'),
      citationLink: $('.blockquote_field__citation_link'),
      content: $('.blockquote_field__content'),
    }

    


    this._bindEvents();
    this.registerEditorActions();
  }

  Blockquote.prototype.defaults = {
    enableLogs: true,
  };

  // Use this functionality if you want to debug with console.
  // Set enableLogs to true to use it
  Blockquote.prototype.debug = function(message){
    if(!this.enableLogs) return;
    console.log(message);
  }



  // Escape html tags before inserting shortcode that may cause conflict
  // Based on underscore.js escapeHTML funtion
  Blockquote.prototype.escapeHTML = function(string){

    var escapeMap = {
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        '`': '&#x60;',
        '[': '&lsqb;',
        ']': '&rsqb;',
      },

      escaper = function(match) {
       return escapeMap[match];
     },

     regEx = new RegExp(/<|>|"|`|\[|\]/gi);

    return string.replace(regEx, escaper);
  }

  // Reverse escaped html tags before updating shortcode
  // Based on underscore.js unEscapeHTML funtion
  Blockquote.prototype.unEscapeHTML = function(string){

    var unEscapeMap = {
        '&lt;': '<',
        '&gt;': '>',
        '&quot;': '"',
        '&#x60;': '`',
        '&lsqb;': '[',
        '&rsqb;': ']',

      },

      escaper = function(match) {
       return unEscapeMap[match];
     },


     regEx = new RegExp(Object.keys(unEscapeMap).join('|'),'gi');

    return string.replace(regEx, escaper);
  }


  // Bind events for module

  Blockquote.prototype._bindEvents = function(){

    this.$form.on('submit',this.insertShortCode.bind(this));

    $(document).on('click',this.openModalButtonSelector,this.toggleModal.bind(this));
    this.$closeModalButton.on('click',this.toggleModal.bind(this));

  }

  // Open / close modal
  Blockquote.prototype.toggleModal = function(event){
    event.preventDefault();
    this.$modal.toggleClass('opened');

    if(this.$modal.hasClass('opened')){
      this.clearData();
      this.$modal.focus();

      this.initTinymce();
      
    }else{
      this.hasSortcodeInstance = false;
    }
  }


  Blockquote.prototype.initTinymce = function( setContent = false ){

     this.tinymce.init({
        selector:'#' + this.tinymceSeletor,
        toolbar: 'bold pastetext',
        plugins: 'paste',
        menubar: false,
        branding: false,
      });

      this.tinymceInstance = this.tinymce.get( this.tinymceSeletor );
      this.tinymceInstance.setContent( '' );

      if( setContent ){
        this.tinymceInstance.setContent( this.fields.content.val() );
      }
  }


  Blockquote.prototype.removeTinymce = function(){

      this.tinymceInstance = null;
      this.tinymce.remove( this.tinymceSeletor );
  }


  Blockquote.prototype.validation = function(){

     var self = this;

     this.fields.content.val( this.tinymceInstance.getContent() );

      $( '.blockquote_field .required_error' ).addClass( 'hide' );

      Object.keys(this.fields).forEach(function( field ){

          if( self.fields[ field ].val() == '' && self.fields[ field ].hasClass( 'required' ) ){
              self.fields[ field ].closest( '.blockquote_field' ).find( '.required_error' ).removeClass( 'hide' );
          }
      });

      if( $( '.required_error:not(.hide)' ).length ){
        return false;
      }

      return true;
  }

  // Insert / Update shortcode to editor
  // See https://codex.wordpress.org/Shortcode_API for more details

  Blockquote.prototype.insertShortCode = function(event){
    event.preventDefault();
    var code = '';

    if( this.validation() === false ){
      return;
    }

    var citation = this.escapeHTML( this.fields.citation.val() ),
        citationLink = this.escapeHTML( this.fields.citationLink.val() ),
        contentHTML = this.fields.content.val();

    code = '[blockquote_sc citation="' + citation + '" citation_link="' + citationLink + '"]';
    code += contentHTML;
    code += '[/blockquote_sc]';

    if(this.hasSortcodeInstance){

      this.$modal.trigger('update',[code]);

    }else{

      window.send_to_editor(code);
    }

    this.$modal.removeClass('opened');
  }

  Blockquote.prototype.clearData = function(){
    this.fields.citation.val('');
    this.fields.content.val('');
    this.removeTinymce();
  }



  // Extend wp.mce.views
  // Register product_crawler_sc to wp.mce.views
  // Handles the edit shortcode, before insertShortCode
  // Enable preview shortcode
  // See
  //     https://github.com/WordPress/WordPress/blob/master/wp-includes/js/mce-view.js
  //     for more details

  Blockquote.prototype.registerEditorActions = function(){

    if( typeof wp === 'undefined' || typeof wp.media === 'undefined' || typeof wp.shortcode === 'undefined'){
      return this.debug('Cannot init Blockquote Module. wp,  wp.media or wp.shortcode is undefined');
    }

    var self = this,
        media = wp.media;

    if( typeof wp.mce.views.register !== "function"){
      return this.debug('Cannot init Blockquote Module. wp.mce.views.register is undefined');
    }

    var blockquoteShortcode =  {
      template: media.template( 'tmpl-editor-blockquote' ),
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
          atts: that.shortcode.attrs,
          content: this.shortcode.content,
        };

        wp.ajax.post( 'blockquote__preview', data )
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
            content = this.shortcode.content;

        self.$modal.on('update',function(event,code){
          update(code,type);
          self.hasSortcodeInstance = false;
          $(this).off('update');
        });

        self.clearData();

        self.fields.citation.val( values.citation );
        self.fields.citationLink.val( values.citation_link );
        self.fields.content.val( content );
        
        self.hasSortcodeInstance = true;
        self.$modal.addClass('opened').focus();

        self.initTinymce( true );

      },

    };


    wp.mce.views.register( 'blockquote_sc', _.extend( {}, blockquoteShortcode ) );

  }




  window.Blockquote = Blockquote;


})(jQuery);
