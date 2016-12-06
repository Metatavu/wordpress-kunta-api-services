CKEDITOR.plugins.add( 'kunta-api-services', {
    icons: 'kunta-api-services',
    init: function(editor) {
      editor.addContentsCss(this.path + 'contents.css');
      
      editor.ui.addButton('kunta-api-service-embed', {
        label: 'Upota palvelutietoja',
        command: 'kunta-api-service-embed',
        toolbar: 'insert'
      });
      
      CKEDITOR.dialog.add('kunta-api-service-embed', this.path + 'dialogs/service-embed.js');
      editor.addCommand('kunta-api-service-embed', new CKEDITOR.dialogCommand('kunta-api-service-embed'));
    }
});