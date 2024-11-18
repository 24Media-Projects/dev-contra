( function( $ ){
  'use strict';

  $( document ).ready( function(){

      var ReadAlso = window.ReadAlso,
          ProductCrawler = window.ProductCrawler,
          EmbedCode = window.EmbedCode,

          readAlso =  new ReadAlso({
            maxSelected: BTW.read_also.maxSelected,
          }),
          productCrawler =  new ProductCrawler(),
          embedCode =  new EmbedCode();


   if( typeof acf != 'undefined' ){

      if( typeof acf != 'undefined' && typeof acf.add_filter != 'undefined' ){
        acf.add_filter( 'wysiwyg_tinymce_settings', function( mceInit, id, field ){

          if( $( '#' + id ).closest( '.acf-field-wysiwyg' ).data( 'name' ) == 'btw__article_fields__lead' ){
            mceInit.block_formats = "Paragraph=p;";
          }

          return mceInit;
        });
      }


  } // end acf if

      if( document.querySelector('tr.user-description-wrap #description') !== null ) { // only where biographical info exists

          //document.addEventListener('DOMContentLoaded', function() {

          var id = 'description';
          document.getElementById(id).style.width = '100%';

          wp.editor.initialize(id, {
              tinymce: {
                  wpautop: true,
                  elementpath: true,
                  plugins: 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
                  toolbar1: 'formatselect bold italic underline bullist numlist blockquote alignleft aligncenter alignright link wp_more wp_adv',
                  toolbar2: 'strikethrough hr forecolor pastetext removeformat charmap outdent indent undo redo wp_help',
              },
              quicktags: true,
              mediaButtons: true,
          });

          //});

      }

  });  //end doc ready

})(jQuery)
