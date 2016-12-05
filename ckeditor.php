
<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  if (is_admin()) {
  	add_action('init', function() {
  	  add_filter('ckeditor_external_plugins', function ($plugins) {
  	  	$plugins['kunta-api-services'] = plugin_dir_url(__FILE__) . 'ckeditor-plugins/kunta-api-services/plugin.js';
  	  	return $plugins;
  	  });
    });
  	
  }
?>