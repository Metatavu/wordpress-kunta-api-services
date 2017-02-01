<?php
    
 if (!defined('ABSPATH')) { 
   exit;
 }
 
 add_action('kunta_api_core_settings', function () {
 	global $kuntaApiSettings;
 	$kuntaApiSettings[] = [
      "type" => "text",
      "name" => "locationChannelsPath",
      "title" => __('Location channels path', KUNTA_API_SERVICES_I18N_DOMAIN)
    ];
 });
 
?>
