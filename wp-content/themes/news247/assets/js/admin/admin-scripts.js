( function( $ ){
  'use strict';

  $( document ).ready( function(){

    var Blockquote = window.Blockquote,
        blockquote = new Blockquote();

   if( typeof acf != 'undefined' ){

        acf.addFilter( 'relationship_ajax_data', function( ajaxData, field ){

            ajaxData.btw_post_type = !ajaxData.post_type ? ( field.$el.closest( '.acf-field-repeater' ).data( 'btw_post_type' ) || '' ) : '';
            ajaxData.btw_taxonomy = !ajaxData.taxonomy ? ( field.$el.closest( '.acf-field-repeater' ).data( 'btw_taxonomy' ) || '' ) : '';

        console.log(ajaxData);
        return ajaxData;

        });

    }


      function post_templates_of_magazine_categories(){

          var postCategories = JSON.parse( BtwPostCategories ); // eg to get all category ids of magazine: postCategories[ 'magazine' ]
          var _is_magazine_category_primary = false;

          $('#taxonomy-category input:checked').each(function(i){
              var cat_id = Number( $(this).val() );

              for (var key in postCategories) {
                  if( postCategories[key].includes( MagazineCategoryId ) && postCategories[key].includes( cat_id ) ){

                      if( $('#taxonomy-category input:checked').length == 1 ){
                            _is_magazine_category_primary = true;
                      }else if( postCategories[key].includes( Number( $('#taxonomy-category .wpseo-primary-term input').val() ) ) ){
                            _is_magazine_category_primary = true;
                      }

                  }
              }
          });

          var embed_template_selected = $('#page_template > option[value*="embed"][selected]');

          if( embed_template_selected.length > 0 ){

              embed_template_selected.css({"display":"block"});
              $('#page_template').css({"pointer-events":"none"});

          }else if( _is_magazine_category_primary ){

              $('#page_template > option[value="single-magazine.php"]').prop('selected', true);
              $('#page_template').css({"pointer-events":"none"});

          }else{

              $('#page_template').css({"pointer-events":"auto"});
              $('#page_template > option[value="single-magazine.php"]').css({"display":"none"});
              $('#page_template > option[value*="embed"]').css({"display":"none"});

              if( $('#page_template').val() == 'single-magazine.php' ){
                  $('#page_template > option[value="default"]').prop('selected', true);
              }

          }

      }

          // Categories
         if( NEWS247.is_single_post ){

             if ( $('body.role-administrator').length == 0 && $('body.role-manager').length == 0 ){

                 post_templates_of_magazine_categories();

                 $('#taxonomy-category input').on('change', post_templates_of_magazine_categories);
                 $('#taxonomy-category').on('click', '.wpseo-make-primary-term', function(){
                     setTimeout(post_templates_of_magazine_categories, 100);
                 });

             }


             // var bg_color_group_selector = '#acf-group_63f37ac095227';
             // var about_template_group_selector = '#acf-group_63f35d5e0499e';
             //
             // // Declare a fragment:
             // var fragment = document.createDocumentFragment();
             // // Append desired element to the fragment:
             // fragment.appendChild(document.querySelector( bg_color_group_selector + ' .acf-field' )); // Background color field
             //
             // // Append fragment to "Article | About Template"
             // document.querySelector(about_template_group_selector + ' > .inside.acf-fields').appendChild(fragment);
             // document.querySelector( bg_color_group_selector ).remove();



            if(
                document.querySelector('body.wp-admin.post-new-php') !== null &&
                document.querySelector('#category-36') !== null && // category with id 36 is the category episyndeseis
                document.querySelector('input[value="is_dark_mode"]') !== null
            ) {
                $('#category-36 input').on('change', function(){
                    if( $('#category-36 input').is(':checked') ){
                        $('input[value="is_dark_mode"]').prop('checked', true);
                    }
                });
            }

        }else if( NEWS247.is_single_page ){

             if ( $('body.role-administrator').length == 0 && $('body.role-manager').length == 0 ){

                 var embed_template_selected = $('#page_template > option[value*="embed"][selected]');

                 if( embed_template_selected.length > 0 ){
                     embed_template_selected.css({"display":"block"});
                     $('#page_template').css({"pointer-events":"none"});
                 }else{
                     $('#page_template > option[value*="embed"]').css({"display":"none"});
                 }

             }

         }

    //   var bypass_onclick_actions = false;

    //   $('body.wp-admin.post-new-php form[name="post"], body.wp-admin.post-php form[name="post"]').on('submit', function(event) {

    //       //if( $('body.role-administrator').length == 1 || bypass_onclick_actions || $('.acf-field[data-name="btw__article_fields__with_larger_photo"]').length == 0 ){
    //       if( bypass_onclick_actions ) return;

    //       var minimumWidth, minimumHeight;

    //       if( $('body.wp-admin.post-type-video').length ){
    //           minimumWidth = 1320;
    //           minimumHeight = 880;
    //       }else if( $('body.wp-admin.post-type-skitsa').length ){
    //           minimumWidth = 640;
    //           minimumHeight = 640;
    //       }else if( $('#page_template').val() == 'single-magazine.php' ){

    //           if( $('.acf-field[data-name="btw__magazine_article_fields__half_featured_photo"] .acf-switch').hasClass('-on') ) {
    //               minimumWidth = 1080;
    //               minimumHeight = 1080;
    //           }else{
    //               minimumWidth = 1920;
    //               minimumHeight = 1080;
    //           }

    //       }else if( $('body.wp-admin.post-type-post').length ) { // all other single post templates
    //           minimumWidth = 1320;
    //           minimumHeight = 880;
    //       }else{
    //           return;
    //       }

    //       event.preventDefault();
    //       var $form = $(this);
    //       $('.feat_img_notice').remove();

    //       var thumbnailId = $('#_thumbnail_id').val();

    //       if (thumbnailId !== '' && thumbnailId != '-1') {

    //           // Use the REST API to fetch image data
    //           $.ajax({
    //               type: 'post',
    //               url: BTW.ajaxUrl,
    //               dataType: 'json',
    //               data : {action: "ajax_media_details", thumbnail_id: thumbnailId, wpEditorNonce: BTW.wpEditorNonce},
    //               success: function(response) {
    //                   var imageWidth = response.media_details.width;
    //                   var imageHeight = response.media_details.height;

    //                   if ( imageWidth < minimumWidth || imageHeight < minimumHeight ) {
    //                       var message = 'Η χαρακτηριστική εικόνα του άρθρου θα πρέπει να έχει διαστάσεις τουλάχιστον ' + minimumWidth + 'x' + minimumHeight + 'px.';
    //                       var notice = '<div class="feat_img_notice notice notice-error"><p>' + message + '</p></div>';
    //                       document.querySelector('#post').insertAdjacentHTML('beforebegin', notice);
    //                       alert(message);
    //                       return false; // Prevent the post from being saved
    //                   } else {
    //                       console.log('right')
    //                       bypass_onclick_actions = true;
    //                       $form.submit();
    //                       return true; // Allow the post to be saved
    //                   }
    //               },
    //               error: function() {
    //                   bypass_onclick_actions = true;
    //                   $form.submit();
    //                   return true; // Allow the post to be saved in case of an error
    //               }
    //           });
    //       } else {
    //           bypass_onclick_actions = true;
    //           $form.submit();
    //           return true; // No featured image selected, allow the post to be saved
    //       }
    //   });

      // Select Category Skitsa at creation of new Post of Post Type Skitsa
      $('body.post-new-php.post-type-skitsa li#category-72 > label').click();


  });  //end doc ready

})(jQuery)