(function(tinymce, $) {
  'use strict';

  tinymce.PluginManager.add('kunta_api_sidebar', function(editor, url) {
    editor.addButton('kunta_api_sidebar', {
      title: 'Add sidebar content',
      onclick: function() {
        var context = editor.getDoc();
        var tinyMceDom = tinymce.dom.DomQuery;
        var sidebarContent = tinyMceDom('.mce-kunta-api-sidebar-content', context);
        if(sidebarContent.length == 0) {
          editor.execCommand('mceInsertContent', false, '<div class="mce-kunta-api-sidebar-content mceNonEditable"><div class="mceEditable"' + editor.selection.getContent() + '</div></div>');
        } else {
          sidebarContent.css('background', '#fee');
          setTimeout(function() {
            sidebarContent.css('background', '#fff');
          }, 1000);
        }
      }
    }); 
  });
    
})(tinymce, jQuery);