(function($) {
  // "use strict";


  String.prototype.htmlCleanUp = function(){
    return this.replace(/<p>|<\/p>|\n+|\r+/gi,'');
  }



  var EmbedCode = function(options){
    this.options = $.extend({}, this.defaults, options);

    this.$modal = $('.embed-code-modal');
    this.$openModalButton = $('.embed-code-insert-button');
    this.openModalButtonSelector = '.embed-code-insert-button';
    this.$closeModalButton = this.$modal.find('.btw-editor-modal__close');

    this.$form = $('.embed-code-modal__form');

    if(!this.$openModalButton.length) return;

    this.fields = {
      provider: $('.embed-code__field--provider'),
      embedCode: $('.embed-code__field--code'),
      // containerWidth: $('.embed-code__field--container-width'),
      // containerHeight: $('.embed-code__field--container-height')
    }

    this.enableLogs = this.options.enableLogs;
    this.hasSortcodeInstance = false;

    this._bindEvents();
    this.registerEditorActions();
  }

  EmbedCode.prototype.defaults = {
    enableLogs: false,
  };

  EmbedCode.prototype.debug = function(message){
    if(!this.enableLogs) return;
    console.log(message);
  }



  EmbedCode.prototype._bindEvents = function(){

    this.$form.on('submit',this.insertShortCode.bind(this));

    $(document).on('click',this.openModalButtonSelector,this.toggleModal.bind(this));
    this.$closeModalButton.on('click',this.toggleModal.bind(this));

  }

  EmbedCode.prototype.toggleModal = function(event){
    event.preventDefault();

    this.$modal.toggleClass('opened');

    if(this.$modal.hasClass('opened')){
      this.clearData();
      this.$modal.focus();
    }else{
      this.hasSortcodeInstance = false;
    }

  }


  EmbedCode.prototype.isUrl = function(strToCheck){
    try {
      new URL(strToCheck);
      return true;

    } catch (err){
      return false;
    }
  }


  EmbedCode.prototype.canBeTransformToObj = function( strToCheck ){
    try {
      $(strToCheck ).wrapAll('<div>').parent();
      return true;

    } catch (err){
      return false;
    }
  }



  EmbedCode.prototype.stripScriptTags = function(){

    if( !this.canBeTransformToObj(this.fields.embedCode.val()) || !$(this.fields.embedCode.val()).length ){
      return this.fields.embedCode.val().trim();
    }

    var $codeContainer = $(this.fields.embedCode.val()).wrapAll('<div>').parent();

    if( this.fields.provider.val() === '24media_player' ){

      $codeContainer.html($codeContainer.html().replace('<script src="https://static.adman.gr/adman.js"></script>', ''));
      $codeContainer.html($codeContainer.html().replace('<script src="https://player.pstatic.gr/phaistos-player/phaistosplayer.js"></script>', ''));
      $codeContainer.html($codeContainer.html().replace(/<(\/?)(script|style)/gm, '<$1data-$2'));

    }else if( this.fields.provider.val() === 'kwiwi_player' ){

      $codeContainer.find('style').remove();

    }else{

      $codeContainer.find('script').remove();

    }

    return $codeContainer.html().htmlCleanUp();

  }

  EmbedCode.prototype.insertShortCode = function(event){
    event.preventDefault();

    var embedCode = this.stripScriptTags(this.fields.embedCode.val());


    var code = '[embed_code_sc provider="' + this.fields.provider.val() + '"]' + embedCode   + '[\/embed_code_sc]';
    // var code = '[embed_code_sc provider="' + this.fields.provider.val() + '" width="' + this.fields.containerWidth.val() + '" height="' + this.fields.containerHeight.val() + '"]' + embedCode   + '[\/embed_code_sc]';


    if(this.hasSortcodeInstance){

      this.$modal.trigger('update',[code]);

    }else{

      this.send_to_editor(code);
    }

    this.$modal.removeClass('opened');
  }


  // Copy from wp-admin/js/media-upload.js
  EmbedCode.prototype.send_to_editor = function( html ){

    var editor,
  		hasTinymce = typeof tinymce !== 'undefined',
  		hasQuicktags = typeof QTags !== 'undefined';

  	// If no active editor is set, try to set it.
  	if ( ! wpActiveEditor ) {
  		if ( hasTinymce && tinymce.activeEditor ) {
  			editor = tinymce.activeEditor;
  			window.wpActiveEditor = editor.id;
  		} else if ( ! hasQuicktags ) {
  			return false;
  		}
  	} else if ( hasTinymce ) {
  		editor = tinymce.get( wpActiveEditor );
  	}

  	// If the editor is set and not hidden,
  	// insert the HTML into the content of the editor.
  	if ( editor && ! editor.isHidden() ) {
  		editor.execCommand( 'mceInsertRawHTML', false, html );
  	} else if ( hasQuicktags ) {
  		// If quick tags are available, insert the HTML into its content.
  		QTags.insertContent( html );
  	} else {
  		// If neither the TinyMCE editor and the quick tags are available,
  		// add the HTML to the current active editor.
  		document.getElementById( wpActiveEditor ).value += html;
  	}

  	// If the old thickbox remove function exists, call it.
  	if ( window.tb_remove ) {
  		try { window.tb_remove(); } catch( e ) {}
  	}

  }


  EmbedCode.prototype.clearData = function(){
    this.fields.embedCode.val('');
    this.fields.provider.val('');
    // this.fields.containerWidth.val('');
    // this.fields.containerHeight.val('');
  }


  EmbedCode.prototype.registerEditorActions = function(){

    if( typeof wp === 'undefined' || typeof wp.media === 'undefined' ){
      return this.debug('Cannot init Read Also Module. wp or wp.media is undefined');
    }

    var self = this,
        media = wp.media;

    if( typeof wp.mce.views.register !== "function"){
      return this.debug('Cannot init Read Also Module. wp.mce.views.register is undefined');
    }

    var embedCodeShortcode =  {
      template: media.template( 'tmpl-editor-custom-embed-code' ),
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
          content: that.shortcode.content,
        };


        wp.ajax.post( 'embed_code__preview', data )
        .done( function( response ) {
          self.debug(response);
          that.render( response.html.replace(/\\/g,''));
        })
        .fail( function( response ) {
          alert(BTW.ajaxErrorMsg);
        });
      },
      edit: function( text, update ) {
        var type = this.type,
            values = this.shortcode.attrs.named,
            content = this.shortcode.content.htmlCleanUp();

        self.$modal.on('update',function(event,code){

          update(code,type);
          self.hasSortcodeInstance = false;
          $(this).off('update');
        });

        self.fields.provider.val(values.provider);

        if( values.provider == '24media_player' ){
          content = content.replace( /<(\/?)data-(script|style)/gm, '<$1$2' );
        }

        self.fields.embedCode.val(content);

        self.$modal.addClass('opened').focus();
        self.hasSortcodeInstance = true;

      },
    };


    wp.mce.views.register( 'embed_code_sc', _.extend( {}, embedCodeShortcode ) );

  }


  window.EmbedCode = EmbedCode;


})(jQuery);
