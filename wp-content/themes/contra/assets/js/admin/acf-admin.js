( function( $ ){
    'use strict';

    $( document ).ready( function(){

        if( typeof acf != 'undefined' ){


            // onclick copy post html
            $('.related_post_content--button').on( 'click', function(e){
                e.preventDefault();
                var inp =document.createElement('input');
                document.body.appendChild(inp);
                inp.value = document.querySelector('.related_post_content--wrapper .unescaped').innerHTML.trim();
                inp.select();
                document.execCommand('copy',false);
                inp.remove();

                var _this = $(this);
                _this.text('COPIED!');
                setTimeout(function(){
                    _this.text('COPY');
                }, 1000);

            });

            acf.add_action('btw/reset_atf_post', function(_parent, _uniqid){

                // reset supertitle
                _parent.find('.acf-field[data-name="atf__supertitle"] .acf-input input').val('');

                // reset caption
                _parent.find('.acf-field[data-name="atf__caption"] .acf-input input').val('');

            });



            // ACF Magazine Group location Rules
            acf.screen.events[ 'change .magazine_group_template_container select' ] = 'onChange';

            acf.addFilter( 'check_screen_args', function( ajaxData ){

                if( acf.screen.isPost() && acf.screen.getPostType() == 'group' ){
                    ajaxData.group_magazine_template = $( '.magazine_group_template_container select' ).val();
                }

                return ajaxData;
            });

            if( acf.screen.getPostType() == 'group' ){
                acf.screen.check();
            }

        } // end acf if



    });  //end doc ready

})(jQuery)