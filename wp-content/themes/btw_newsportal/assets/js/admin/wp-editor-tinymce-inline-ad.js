(function() {

  var inlineAdPlugin = {
        totalBanners:0,
        maxBanners: 3,
        icon: BTW.editorPluginsInlineADIcon,
        editors: [],
        maybeAddEditor: function(editor){
          if(!editor) return;
          var editorsIDs = this.editors.map(function(a){ return a.id});

          if(editorsIDs.indexOf(editor.id) == -1){
            this.editors.push(editor);
          }

        },
        countBanners: function(){
          var totalBanners = 0;
          this.editors.forEach(function(e){
            totalBanners += (e.getContent().match(/class="inline-ad/g) || []).length;
          });
          return totalBanners;
        }

      }



  tinymce.PluginManager.add( 'inline-ad', function( editor, url ) {
    if(document.getElementById(editor.id).closest('.acf-field-wysiwyg') && document.getElementById(editor.id).closest('.acf-field-wysiwyg').dataset.name == 'more_info'){
      return false;
    }

      inlineAdPlugin.maybeAddEditor(editor);
      // Add Button to Visual Editor Toolbar
      editor.addButton('inline-ad', {
          title: 'Inline AD',
          cmd: 'inline-ad',
          image: inlineAdPlugin.icon,
      });

      editor.addCommand('inline-ad', function() {
          var content = editor.getContent(),
              adTag;


          // inlineAdPlugin.totalBanners += (content.match(/class="inline-ad/g) || []).length;


          if(inlineAdPlugin.countBanners() < inlineAdPlugin.maxBanners){
            adTag = '<hr class="inline-ad" />';
          }else{
            alert('Μπορείτε να εισάγετε μέχρι ' + inlineAdPlugin.maxBanners + ' inline dfp banners για κάθε άρθρο');
            return false;
            // inlineAdPlugin.totalBanners++;
          }
          // window.send_to_editor(adTag);
          editor.selection.setContent(adTag);
          return;
          // editor.execCommand('mceReplaceContent', false, adTag);

      });

    });




})();
