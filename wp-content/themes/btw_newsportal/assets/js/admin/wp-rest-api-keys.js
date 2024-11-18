( function( $ ){

  $( document ).ready( function(){

    var xhr = null,
        $apiKeyField = $( '.customer_fields__api_key' ),
        $generateAPIkeyButton = $( '.customer_fields__generate_api_key_button' ),
        $errorMsgContainer = $( '.customer_fields__api-key-error-container' );

    $generateAPIkeyButton.click( function( e ){
      e.preventDefault();
      if(xhr) xhr.abort();

      $errorMsgContainer.html('');

      xhr = $.post({
        url: BTW.ajaxUrl,
        data:{
          nonce: BTW.restNonce,
          action: 'generate_api_key',

        },
        error: function( request, status, error ){
          if( request.statusText.toLowerCase() != 'abort' ){
            $errorMsgContainer.append( '<span class="api-error-msg">' + BTW.ajaxErrorMsg + '</span>' );
          }
        },
        success:function( data ){

          if( !data.success ){
            $errorMsgContainer.append( '<span class="api-error-msg">' + BTW.ajaxErrorMsg + '</span>' );
            return;
          }

          $apiKeyField.val( data.api_key );
        }
      });


    });

  });


})(jQuery);
