(function($) {
  // "use strict";

  // Product Crawler module JS
  // Based on wp mce-views
  // See
  //      https://github.com/WordPress/WordPress/blob/master/wp-includes/js/mce-view.js
  //      inc/admin/editor/class-btw-admin-editor.php
  //      for more details



  var ProductCrawler = function(options){
    this.options = $.extend({}, this.defaults, options);


    this.$modal = $('.product-crawler-modal');
    this.$openModalButton = $('.product-crawler-insert-button');
    this.openModalButtonSelector = '.product-crawler-insert-button';
    this.$closeModalButton = this.$modal.find('.btw-editor-modal__close');
    this.$form = $('.product-crawler-modal__form');
    this.$formButton = $('.product-crawler-modal__btn');
    this.$crawlUrl = $('.product__fields--url');
    this.$crawlUrlButton = $('.product__url-button');
    this.$crawlError = $('.product-crawler__crawl_error');
    this.$fieldsContainer = $('.product__fields-container');
    this.$wpMediaButton = $('.wp-media-img');
    this.$resetValueButton = $('.reset_value');
    this.$imgPreviewContainer = $('.product__fields--img-preview');
    this.$buyNowCheckbox = $('.product__fields--buy-now-checkbox');
    this.$disableLinkCheckbox = $('.product__fields--disable-link-checkbox');
    this.$savePostForm = $('#post');
    this.$uploadAttachmentsContainer = $('#publishing-action');

    if(!this.$openModalButton.length) return;

    this.xhr = null;
    this.hasSortcodeInstance = false;
    this.enableLogs = this.options.enableLogs;

    this.attachmentsToUpload = [];
    this.attachmentsToUploadActiveIndex = null;
    this.postType = $('#post_type').val();
    this.imgAppended = false;

    this.productFields = {
      name: $('.product__fields--name'),
      price: $('.product__fields--price'),
      url: $('.product__fields--url'),
      desc: $('.product__fields--desc'),
      img: $('.product__fields--img'),
      credits: $('.product__fields--img-credits'),
      sale_price: $('.product__fields--sale-price'),
      shop_name: $('.product__fields--shop-name'),
      buylink: $('.product__fields--buylink'),
      has_buy_now_button: $('.product__fields--buy-now-button'),
      disable_link: $('.product__fields--disable-link-button'),
    }

    // This is useful for reseting values to original crawled values
    this.productFieldsCrawlValues = {
      name: '',
      price: '',
      url: '',
      desc: '',
      img: '',
      credits: '',
      imgMeta: '',
      sale_price: '',
      shop_name: '',
      buylink: '',
      has_buy_now_button: '',
      disable_link: '',

    }

    this._bindEvents();
    this.registerEditorActions();
  }

  ProductCrawler.prototype.defaults = {
    enableLogs: true,
  };

  // Use this functionality if you want to debug with console.
  // Set enableLogs to true to use it
  ProductCrawler.prototype.debug = function(message){
    if(!this.enableLogs) return;
    console.log(message);
  }


  // Escape html tags before inserting shortcode that may cause conflict
  // Based on underscore.js escapeHTML funtion

  ProductCrawler.prototype.escapeHTML = function(string){

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
  ProductCrawler.prototype.unEscapeHTML = function(string){

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

  // Parse Url functionallity
  // Store returning values to:
  //   productFields,
  //   productFieldsCrawlValues
  // See inc/admin/editor/btw-admin-editor.php for more details

  ProductCrawler.prototype.parseUrl = function(e){
    e.preventDefault();
    var self = this,
        data = {
          action: 'prodcut_crawler__get_meta_tags',
          nonce: BTW.wpEditorNonce,
          url: this.$crawlUrl.val(),
        };

    this.debug(data);
    this.clearData(false);

    if(this.xhr) this.xhr.abort();

    this.xhr = $.post({
      url: BTW.ajaxUrl,
      data:data,
      error: function(request, status, error) {
        if(request.statusText.toLowerCase() != 'abort'){
          alert(request.responseText);
        }
      },
      success:function(response){
        self.debug(response);

        if( !response.success || response.success === true && !response.data.ogTags ){
          self.$crawlError.show();
          self.$fieldsContainer.show();
          self.$formButton.show();

          return;
        }

        self.$fieldsContainer.show();
        self.$formButton.show();


        self.productFields.name.val( response.data.ogTags.hasOwnProperty('title') ? response.data.ogTags.title : '' );
        self.productFields.shop_name.val( response.data.ogTags.hasOwnProperty('site_name') ? response.data.ogTags.site_name : '' );
        self.productFields.desc.val( response.data.ogTags.hasOwnProperty('description') ? response.data.ogTags.description : '');
        self.productFields.price.val( response.data.ogTags.hasOwnProperty('price') ? response.data.ogTags.price : '');
        self.productFields.img.val( response.data.ogTags.hasOwnProperty('image') ? response.data.ogTags.image : '');

        self.productFieldsCrawlValues.name      = response.data.ogTags.hasOwnProperty('title') ? response.data.ogTags.title : '';
        self.productFieldsCrawlValues.shop_name = response.data.ogTags.hasOwnProperty('site_name') ? response.data.ogTags.site_name : '';
        self.productFieldsCrawlValues.desc      = response.data.ogTags.hasOwnProperty('description') ? response.data.ogTags.description : '';
        self.productFieldsCrawlValues.price     = response.data.ogTags.hasOwnProperty('price') ? response.data.ogTags.price : '';
        self.productFieldsCrawlValues.img       = response.data.ogTags.hasOwnProperty('image') ? response.data.ogTags.image : '';


        if(self.productFields.img.val()){

          self.$imgPreviewContainer.html('<img src="' + self.productFields.img.val() + '" />');
          self.productFields.credits.closest('.product__field').show();

          if( response.data.ogImageMeta ){
            self.$imgPreviewContainer.prepend('<div class="product__fields--img-meta">Width: ' +  ( response.data.ogImageMeta.width ? response.data.ogImageMeta.width + 'px' : ' - ' ) + ' | Height: ' +  ( response.data.ogImageMeta.height ? response.data.ogImageMeta.height + 'px' : ' - ') + ' | Mime Type: ' +  ( response.data.ogImageMeta.mimeType ? response.data.ogImageMeta.mimeType  : ' - ' ) + '</div');
            self.productFieldsCrawlValues.imgMeta = response.data.ogImageMeta
          }

        }

        self.maybeEnableReset();

      }
    });

  }



  // Bind events for module

  ProductCrawler.prototype._bindEvents = function(){

    this.$form.on('submit',this.insertShortCode.bind(this));
    this.$crawlUrlButton.on('click',this.parseUrl.bind(this));

    $(document).on('click',this.openModalButtonSelector,this.toggleModal.bind(this));
    this.$closeModalButton.on('click',this.toggleModal.bind(this));

    this.$wpMediaButton.on('click',this.wpMedia.bind(this));
    this.$resetValueButton.on('click',this.resetValue.bind(this));
    this.$buyNowCheckbox.on('change',this.UpdateBuyNowButtonStatus.bind(this));
    this.$disableLinkCheckbox.on('change',this.UpdateDisableLinkButtonStatus.bind(this));

    // if(this.postType == 'post'){
    //   this.$savePostForm.on('submit',this.savePost.bind(this));
    // }

  }

  // Open / close modal
  ProductCrawler.prototype.toggleModal = function(event){
    event.preventDefault();
    this.$modal.toggleClass('opened');

    if(this.$modal.hasClass('opened')){
      this.clearData();
      this.$modal.focus();
    }else{
      this.hasSortcodeInstance = false;
    }

  }

  // Open / close wp media uploader modal
  // When user wants to replace the crawled image
  ProductCrawler.prototype.wpMedia = function(e){
    e.preventDefault();
    var self = this,
        frame = wp.media({
          title: BTW.selectProductImageTitle,
          multiple: false
        });

    frame.open();

    frame.on('select', function(e){
      var image = frame.state().get('selection').first().toJSON();

      self.productFields.img.val(image.id);
      self.$imgPreviewContainer.html('<img src="' + image.url + '" />');
      self.productFields.credits.closest('.product__field').hide();
      frame.close();

    });


  }

  //Handles product has buy now button
  ProductCrawler.prototype.UpdateBuyNowButtonStatus = function(event){
    var buyNowButtonStatus = this.$buyNowCheckbox.prop('checked') ? 'true' : 'false';
    this.productFields.has_buy_now_button.val(buyNowButtonStatus);
  }

  ProductCrawler.prototype.UpdateDisableLinkButtonStatus = function(event){
    var disableLinkButtonStatus = this.$disableLinkCheckbox.prop('checked') ? 'true' : 'false';
    this.productFields.disable_link.val(disableLinkButtonStatus);
  }

  // Insert / Update shortcode to editor
  // Shortcode Params: url
  //                   name
  //                   desc
  //                   img
  //                   img_credits
  //                   price
  //                   sale_price
  //                   has_buy_now_button

  // See https://codex.wordpress.org/Shortcode_API for more details


  ProductCrawler.prototype.insertShortCode = function(event){
    event.preventDefault();
    var self = this,
        codeAttrs = [];

    Object.keys(this.productFields).forEach(function(e,i){
      codeAttrs.push(e + '="' + self.escapeHTML(self.productFields[e].val()) + '"');
    });

    if(!this.productFields.img.val()){
      $('.product-crawler__img_missing').show();
      return false;
    }else{
      $('.product-crawler__img_missing').hide();
    }


    var code = '[product_crawler_sc ' + codeAttrs.join(' ') + ']';

    if(this.hasSortcodeInstance){

      this.$modal.trigger('update',[code]);

    }else{

      window.send_to_editor(code);
    }

    this.$modal.removeClass('opened');
  }



  // Clear values of fields before open modal

  ProductCrawler.prototype.clearData = function(clearUrl = true){
    var self = this;
    Object.keys(this.productFields).forEach(function(e,i){
      if(e == 'url' && !clearUrl) return;
      if(self.productFields[e].data('default-value') !== 'undefined'){
        self.productFields[e].val(self.productFields[e].data('default-value'));
      }else{
        self.productFields[e].val('');
      }
      self.productFieldsCrawlValues[e] = '';
    });

    self.$buyNowCheckbox.prop('checked', true);
    self.$disableLinkCheckbox.prop('checked', false);
    this.$imgPreviewContainer.html('');
    this.productFields.credits.closest('.product__field').hide();
    $('.product-crawler__error_msg').hide();
    this.$crawlError.hide();
    this.$fieldsContainer.hide();
    this.$formButton.hide();

    this.maybeEnableReset();
  }

  // Enables reset value functionality
  // Reset Values: Fields with reset_value button
  // See inc/admin/editor/modals/product_modal_html.php for more details

  ProductCrawler.prototype.maybeEnableReset = function(){
    var self = this;

    Object.keys(this.productFields).forEach(function(e){
      if( self.productFields[e].val() ){
        self.productFields[e].closest('.product__field').find('.reset_value').show();
      }else{
        self.productFields[e].closest('.product__field').find('.reset_value').hide();
      }
    });
  }

  // Reset value proccess
  ProductCrawler.prototype.resetValue = function(e){
    e.preventDefault();
    var thisProp = $(event.target).data('reset');

    if(this.productFieldsCrawlValues[thisProp] && this.productFieldsCrawlValues[thisProp] != this.productFields[thisProp].val()){
      this.productFields[thisProp].val(this.productFieldsCrawlValues[thisProp]);

      if(thisProp == 'img'){
        this.$imgPreviewContainer.html('<img src="' + this.productFieldsCrawlValues[thisProp] + '" />');
        this.productFields.credits.closest('.product__field').show();

        if(  this.productFieldsCrawlValues.imgMeta ){
          this.$imgPreviewContainer.prepend('<div class="product__fields--img-meta">Width: ' +  this.productFieldsCrawlValues.imgMeta.width + ' Height: ' +  this.productFieldsCrawlValues.imgMeta.height + ' | Mime Type: ' +  ( this.productFieldsCrawlValues.imgMeta.mimeType ? this.productFieldsCrawlValues.imgMeta.mimeType  : ' - ' ) + '</div');
        }

      }

    }
  }


  // Extend wp.mce.views
  // Register product_crawler_sc to wp.mce.views
  // Handles the edit shortcode, before insertShortCode
  // Enable preview shortcode
  // See
  //     https://github.com/WordPress/WordPress/blob/master/wp-includes/js/mce-view.js
  //     inc/admin/editor/btw-admin-editor.php
  //     for more details

  ProductCrawler.prototype.registerEditorActions = function(){

    if( typeof wp === 'undefined' || typeof wp.media === 'undefined' || typeof wp.shortcode === 'undefined'){
      return this.debug('Cannot init Product Module. wp,  wp.media or wp.shortcode is undefined');
    }

    var self = this,
        media = wp.media;

    if( typeof wp.mce.views.register !== "function"){
      return this.debug('Cannot init Product Module Module. wp.mce.views.register is undefined');
    }

    var productCralwerShortcode =  {
      template: media.template( 'tmpl-editor-product' ),
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

        wp.ajax.post( 'product_crawler__preview', data )
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
            values = this.shortcode.attrs.named;

        self.$modal.on('update',function(event,code){
          update(code,type);
          self.hasSortcodeInstance = false;
          self.attachmentsToUploadActiveIndex = null;
          $(this).off('update');
        });

        self.hasSortcodeInstance = true;
        self.$modal.addClass('opened').focus();
        self.clearData();

        self.$fieldsContainer.show();
        self.$formButton.show();


        Object.keys(self.productFields).forEach(function(e,i){
          self.productFields[e].val(self.unEscapeHTML(values[e]));
          if(e == 'has_buy_now_button'){
            if(values[e] == 'false'){
              self.$buyNowCheckbox.prop('checked', false);
            }

          }

          if(e == 'disable_link'){
            if(values[e] == 'true'){
              self.$disableLinkCheckbox.prop('checked', true);
            }

          }

          if( e == 'img'){

            var attachmentUrl;

            if( Number(values[e]) ){
              attachmentUrl = media.attachment(values[e]).get('url');
            }else{
              attachmentUrl = values[e];
              self.productFields.credits.closest('.product__field').show();
            }

            if(!attachmentUrl && Number(values[e])){
              media.attachment(values[e]).fetch().then(function (data) {
               attachmentUrl = media.attachment(values[e]).get('url');
               self.$imgPreviewContainer.html('<img src="' + attachmentUrl + '" />');
             });

             }else{
              self.$imgPreviewContainer.html('<img src="' + attachmentUrl + '" />');
            }

          }
        });

      },

    };


    wp.mce.views.register( 'product_crawler_sc', _.extend( {}, productCralwerShortcode ) );

  }




  window.ProductCrawler = ProductCrawler;


})(jQuery);
