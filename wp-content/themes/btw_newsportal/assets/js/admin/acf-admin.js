( function( $ ){
    'use strict';

    // Not allow ACF to bypass fields' validation when a post saved as draft

    if( typeof acf == 'undefined' ) return;

    acf.addAction('prepare', function () {
        acf.validation.removeEvents({
            'click #save-post': 'onClickSave',
        });
    });

    acf.addFilter('btw/acf_select/choices', function(choices, $select){

        if( !$select.hasClass('condition-rule-field') ) return choices;

        var cloned_repeaters = document.querySelectorAll('.acf-field-object-clone');
        cloned_repeaters.forEach(cloned_repeater => {

            var newChoices = [];

            var elements = document.querySelectorAll('[data-key^="hide_field_"][data-key$="_of_cloned_repeater"]');
            elements.forEach(element => {

                var key = element.getAttribute('data-key');
                key = key.replace(/^hide_/, '');
                key = key.replace(/_of_cloned_repeater$/, '');

                var value = element.querySelector('label').innerText;
                value = value.trim();
                value = value.replace(/^Hide\s/, '');
                value = value.replace(/ of cloned Repeater$/, '');

                var newChoice = {
                    id: key,
                    text: value,
                    disabled: false
                };

                // Append the new choice to the newChoices array
                newChoices.push(newChoice);

            });


            if( newChoices ){

                var newOptgroup = {
                    text: cloned_repeater.querySelector('.li-field-label > strong').innerText,
                    disabled: false,
                    children: newChoices
                };

                choices = choices.concat([newOptgroup]);

            }

        });

        return choices;
    });



    $( document ).ready( function(){

        if( typeof acf != 'undefined' ){

            acf.addFilter( 'relationship_ajax_data', function( ajaxData, field ){
                ajaxData.btw_post_type = !ajaxData.post_type ? ( field.$el.closest( '.acf-field-repeater' ).data( 'btw_post_type' ) || '' ) : '';
                ajaxData.btw_taxonomy = !ajaxData.taxonomy ? ( field.$el.closest( '.acf-field-repeater' ).data( 'btw_taxonomy' ) || '' ) : '';
                return ajaxData;
            });

            acf.add_action('btw/do_reset_atf_post', function($acf_relationship_field, reset_also_relationship_input = true){

                if( reset_also_relationship_input ){
                    acf.doAction('btw/do_reset_relationship_field', $acf_relationship_field);
                }

                reset_atf_post( $acf_relationship_field );
            });

            acf.add_action('btw/do_reset_relationship_field', function($acf_relationship_field){

                $acf_relationship_field.find('.acf-relationship > input').val('');
                $acf_relationship_field.find('.values > ul').html('');
                $acf_relationship_field.find('.choices .acf-rel-item.acf-rel-item-add').removeClass('disabled');

            });

            if( typeof acf != 'undefined' && typeof acf.add_filter != 'undefined' ){
                acf.add_filter( 'wysiwyg_tinymce_settings', function( mceInit, id, field ){

                    if( $( '#' + id ).closest( '.acf-field-wysiwyg' ).data( 'name' ) == 'btw__article_fields__lead' ){
                        mceInit.block_formats = "Paragraph=p;";
                    }

                    return mceInit;
                });
            }


            /**
             * In atf_posts, load taxonomies via AJAX
             */
            function relationship_field_taxonomy_by_select2( el ){

                if( el.find('optgroup').length > 0 ){ // if has already terms, do nothing
                    return;
                }

                var $parent = el.find('.filter.-taxonomy');
                var $select = el.find('.filter.-taxonomy select[data-filter="taxonomy"]');
                $select.select2({
                    placeholder: 'Select Category OR Tag',
                    allowClear: true,
                    ajax: {
                        url: ajaxurl,
                        dataType: 'json',
                        delay: 100,
                        data: function (params) {
                            return {
                                action: 'search_filter_taxonomy',
                                search: params.term,
                                type: 'public'
                            };
                        }
                    },
                });

            }

            acf.add_action('new_field/name=atf__post', relationship_field_taxonomy_by_select2); // when loaded asynchronously
            acf.add_action('load_field/name=atf__post', relationship_field_taxonomy_by_select2); // when not loaded asynchronously



            /**************** ATF_POST CUSTOM FEATURES ****************/
            /** 1) place_custom_toggler_button
            /** 2) reset_atf_post
            /** 3) attach_post_title_to_is_advertorial_input_attribute
            /** 4) edit post link
            /************ END OF ATF_POST CUSTOM FEATURES ************/

            var my_ed = {};
            var my_field = {};

            /**
             * Documentation of wysiwyg_tinymce_init acf action
             *
             * @param ed (object) tinymce object returned by the init function
             * @param id (string) identifier for the tinymce instance
             * @param mceInit (object) args given to the tinymce function
             * @param $field (jQuery) field element
             */

            /**
             * Attach a uniqid to .acf-row (repeater item) and save ed and $field in an JS object
             */
            acf.add_action('wysiwyg_tinymce_init', function( ed, id, mceInit, $field ){

                if(
                    $field.attr('data-name') != 'atf__post_title' &&
                    $field.attr('data-name') != 'atf__post_title_mobile'
                ){
                    return;
                }

                var uniqid, required; // uniqid is for .acf-row (repeater item)

                if( !$field.parents('tr.acf-row').attr('data-uniqid') ){ // if not empty
                    uniqid = Math.random().toString(16).slice(2);
                    $field.parents('tr.acf-row').attr('data-uniqid', uniqid);
                }else{
                    uniqid = $field.parents('tr.acf-row').attr('data-uniqid');
                }

                if( $field.hasClass('is-required') ){
                    required = 'yes';
                }else{
                    required = 'no';
                }

                // save in variables the parameters ed and $field
                my_ed[ uniqid + '_' + $field.attr('data-name') + '_' + required ] = ed;
                my_field[ uniqid + '_' + $field.attr('data-name') + '_' + required ] = $field;

            });

            function place_custom_toggler_button( ed, id, mceInit, $field, at_first_time= true ){

                // print btn only in atf__post_title (not-required) & atf__post_title_mobile
                if(
                    ( $field.attr('data-name') != 'atf__post_title' || $field.hasClass('is-required') ) &&
                    ( $field.attr('data-name') != 'atf__post_title_mobile'  )
                ){
                    return;
                }


                if( at_first_time ){
                    if (ed.getContent()) return; // if content of tinyMCE is not empty, don't do anything
                }else{
                    ed.setContent('');
                    // reset titles
                    $field.find('.button.group-title__toggler').remove();
                }

                $field.find('.acf-input').hide(); // hide tinyMCE field
                $('<button class="button group-title__toggler" style="margin-left: 15px;">Enable Custom Title</button>').insertBefore($field.find('.acf-input')); // Create btn element before TinyMCE


                var fields_wrapper = $field.parent();

                $field.find('.acf-input').prev().click({ed: ed, fields_wrapper: fields_wrapper}, function (e) {

                    e.preventDefault(); // it is a button, we don't want to do any redirect

                    var _parent = e.data.fields_wrapper.parents('tr.acf-row');
                    var _uniqid = _parent.attr('data-uniqid');


                    var post_title;

                    var is_advertorial = false;
                    if( e.data.fields_wrapper.find('.acf-field[data-name="atf__is_advertorial"] .acf-switch').hasClass('-on') ){
                        is_advertorial = true;
                    }

                    // relationship post title, if one selected
                    var title_wrapper = e.data.fields_wrapper.find('[data-name="atf__post"] .selection > .values ul > li > .acf-rel-item .acf_relationship__post_title_value');

                    if ( is_advertorial ) {

                        post_title = my_ed[ _uniqid + '_' + 'atf__post_title' + '_' + 'yes' ].getContent(); // we need this for atf__post_title_mobile

                    }else if ( title_wrapper.length > 0 ) {

                        post_title = title_wrapper.text().trim();

                    }else{

                        alert('Πρέπει να επιλέξετε άρθρο πρώτα');
                        return;

                    }

                    post_title = acf.applyFilters('btw/atf_post/post_title', post_title, $field.attr('data-name'), is_advertorial, _parent);

                    e.data.ed.setContent( post_title );


                    $(this).next().show(); // Display tinyMCE field
                    $(this).remove(); // Hide toggler_button

                });

            }

            function reset_atf_post( _this ){
                var _key;
                var _parent = _this.parents('tr.acf-row');
                var _uniqid = _parent.attr('data-uniqid');

                // reset teaser title for non-advertorial atf_posts
                _key = _uniqid + '_' + 'atf__post_title' + '_' + 'no';
                place_custom_toggler_button( my_ed[ _key ], '', '', my_field[ _key ], false );

                // reset teaser title for advertorial atf_posts
                _key = _uniqid + '_' + 'atf__post_title' + '_' + 'yes';
                my_ed[ _key ].setContent('');

                // reset mobile teaser title
                _key = _uniqid + '_' + 'atf__post_title_mobile' + '_' + 'no';
                place_custom_toggler_button( my_ed[ _key ], '', '', my_field[ _key ], false );

                // reset teaser images
                _parent.find('.acf-field[data-name="atf__image"] .acf-icon.-cancel').click();
                _parent.find('.acf-field[data-name="atf__mobile_image"] .acf-icon.-cancel').click();

                acf.doAction('btw/reset_atf_post', _parent, _uniqid);

            }


            /**
             * Hook to add action after ACF tinyMCE field initialized.
             * Used to enable "Enable Custom Title" functionality.
             * Hiding the tinyMCE field if empty & copying post_title to the tinyMCE field.
             */
            acf.add_action('wysiwyg_tinymce_init', place_custom_toggler_button);


            $(document).on( 'change', '.acf-field[data-name="atf__post"] .acf-relationship > input', function(){
                if( $(this).parent().find('.values > ul > li').length == 0 ){ // when remove
                    reset_atf_post( $(this) );
                }
            });

            $(document).on( 'change', '.acf-field[data-name="atf__is_advertorial"] input.acf-switch-input', function(){
                $(this).parents('.acf-row').find('.acf-field[data-name="atf__post"] .values > ul > li [data-name="remove_item"]').click();
                reset_atf_post( $(this) );
            });


            $(document).on( 'change', '.acf-field[data-name="atf__post"] .acf-relationship > input', function(){
                if( $(this).parents('.acf-field[data-name="atf__post"]').find('.values > ul > li').length == 0 ){
                    reset_atf_post( $(this) );
                }
            });

            function attach_post_title_to_is_advertorial_input_attribute(e, onclick = true, fields_wrapper = null){

                var _fields_wrapper;

                if( onclick ){
                    _fields_wrapper = e.data.fields_wrapper;
                }else{
                    _fields_wrapper = fields_wrapper;
                }

                var is_advertorial = false;
                if( _fields_wrapper.find('.acf-field[data-name="atf__is_advertorial"] .acf-switch').hasClass('-on') ){
                    is_advertorial = true;
                }

                var post_title = '';

                if( is_advertorial ){

                    // get html-stripped atf__post_title required field
                    post_title = _fields_wrapper.find( '.acf-field-wysiwyg[data-name="atf__post_title"].is-required .wp-editor-area').val().replace(/(<([^>]+)>)/ig,'');

                }else{

                    if( onclick ){ // get teaser title from ed field
                        var _uniqid = _fields_wrapper.attr('data-uniqid');
                        var _key = _uniqid + '_' + 'atf__post_title' + '_' + 'no';
                        post_title = my_ed[ _key ].getContent();
                    }else{ // get teaser title from default value of teaser title when access the page
                        post_title = _fields_wrapper.find( '.acf-field-wysiwyg[data-name="atf__post_title"]:not(.is-required) .wp-editor-area').val();
                    }

                    post_title = post_title.replace(/(<([^>]+)>)/ig,'').trim(); // get html-stripped

                    if( ! post_title ){ // if teaser title is empty then get the title from selected post from relationship field

                        var title_wrapper = _fields_wrapper.find('.acf-field[data-name="atf__post"] .selection > .values > ul > li > .acf-rel-item .acf_relationship__post_title_value');                        if( title_wrapper.length > 0 ){
                            post_title = title_wrapper.text().trim();
                        }

                    }

                }

                _fields_wrapper.find('.acf-field[data-name="atf__is_advertorial"] > .acf-input').attr( 'post_title', post_title );

            }


            /**
             * Used to Display POST TITLE when repeater's row is COLLAPSED.
             * Hook to add action when new field initialized.
             */
            function callback_to_attach_post_title_to_is_advertorial_input_attribute( el ){

                var fields_wrapper = el.parents('.acf-row');

                attach_post_title_to_is_advertorial_input_attribute( null, false, fields_wrapper );

                fields_wrapper.find('.acf-row-handle .-collapse').click({fields_wrapper: fields_wrapper}, attach_post_title_to_is_advertorial_input_attribute);

            }

            acf.add_action('new_field/name=atf__is_advertorial', callback_to_attach_post_title_to_is_advertorial_input_attribute); // when loaded asynchronously
            acf.add_action('load_field/name=atf__is_advertorial', callback_to_attach_post_title_to_is_advertorial_input_attribute); // when not loaded asynchronously

            /************ END OF ATF_POST CUSTOM FEATURES ************/



            for (let groupType in JSON.parse( BTW.btwGroupTypes ) ) {
                acf.screen.events[ 'change .' + groupType + '_group_template_container select' ] = 'onChange';

                acf.addFilter('check_screen_args', function( ajaxData ){
                    if( acf.screen.isPost() && acf.screen.getPostType() == 'group' ){
                        ajaxData['group_' + groupType + '_template'] = $( '.' +  groupType + '_group_template_container select' ).val();
                    }
                    return ajaxData;
                });
            }


            acf.screen.events[ 'change .group_type_container select' ] = 'onChange';

            acf.addFilter('check_screen_args', function( ajaxData ){
                if( acf.screen.isPost() && acf.screen.getPostType() == 'group' ){
                    ajaxData.group_type = $( '.group_type_container select' ).val();
                }
                return ajaxData;
            });

            if( acf.screen.getPostType() == 'group' ){
                acf.screen.check();
            }


            acf.add_action('wysiwyg_tinymce_init', function( ed, id, mceInit, $field ){

                if( ! $field.hasClass('acf-field-wysiwyg') ) return;

                if( ! $field.hasClass('min_height_80') ) return;

                $field.find('iframe').height( 80 );
                $field.find('iframe').css( 'min-height', '80px' );

            });



        } // end acf if



    });  //end doc ready

    /**
     * Copied with default method and btw/acf_select/choices added
     */
    acf.renderSelect = function ($select, choices) {

        choices = acf.applyFilters('btw/acf_select/choices', choices, $select);

        // vars
        var value = $select.val();
        var values = []; // callback

        var crawl = function (items) {
            // vars
            var itemsHtml = ''; // loop

            items.map(function (item) {
                // vars
                var text = item.text || item.label || '';
                var id = item.id || item.value || ''; // append

                values.push(id); //  optgroup

                if (item.children) {
                    itemsHtml += '<optgroup label="' + acf.escAttr(text) + '">' + crawl(item.children) + '</optgroup>'; // option
                } else {
                    itemsHtml += '<option value="' + acf.escAttr(id) + '"' + (item.disabled ? ' disabled="disabled"' : '') + '>' + acf.strEscape(text) + '</option>';
                }
            }); // return

            return itemsHtml;
        }; // update HTML


        $select.html(crawl(choices)); // update value

        if (values.indexOf(value) > -1) {
            $select.val(value);
        } // return selected value


        return $select.val();
    };



})(jQuery)