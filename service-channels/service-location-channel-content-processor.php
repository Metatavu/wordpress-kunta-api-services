<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/abstract-service-channel-content-processor.php');
    
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\ServiceLocationChannelContentProcessor' ) ) {
    
    class ServiceLocationChannelContentProcessor extends AbstractServiceChannelContentProcessor {
      
      public function __construct() {
        parent::__construct('kunta-api-service-location-channel');
      }
      
      public function renderServiceChannelContent($serviceId, $serviceChannelId, $lang) {
        $serviceChannel = Loader::findServiceLocationServiceChannel($serviceId, $serviceChannelId);
        return $this->getRenderer()->renderServiceLocationChannel($serviceId, $serviceChannel, $lang);
      }  
    }
  }
  
  add_action('init', function() {
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new ServiceLocationChannelContentProcessor());
  });
?>