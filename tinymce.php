<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  if (is_admin()) {
    add_action('init', function() {
 
      add_filter('mce_external_plugins', function($plugins) {
        $plugins['kunta_api_service_embed'] = plugin_dir_url(__FILE__) . 'tinymce-plugins/kunta-api-service-embed/plugin.js';
        $plugins['kunta_api_sidebar'] = plugin_dir_url(__FILE__) . 'tinymce-plugins/kunta-api-sidebar/plugin.js';
  	    return $plugins;
      });
      
      add_filter('mce_buttons', function($buttons) {
        array_push($buttons, '|', 'kunta_api_service_embed');
        array_push($buttons, '|', 'kunta_api_sidebar');
        return $buttons;
      });
      
      add_editor_style(plugin_dir_url(__FILE__) . 'tinymce-plugins/kunta-api-sidebar/editor.css');
      
      wp_enqueue_style('kunta_api_service_embed', plugin_dir_url(__FILE__) . 'tinymce-plugins/kunta-api-service-embed/plugin.css' );
      wp_enqueue_style('kunta_api_sidebar', plugin_dir_url(__FILE__) . 'tinymce-plugins/kunta-api-sidebar/plugin.css' );
    });
  }
?>