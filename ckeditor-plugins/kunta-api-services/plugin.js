CKEDITOR.plugins.add( 'kunta-api-services', {
    icons: 'kunta-api-services',
    init: function(editor) {
      editor.addContentsCss(this.path + 'contents.css');
    }
});