<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/abstract-service-channel-content-processor.php');
    
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\WebPageChannelContentProcessor' ) ) {
    
    class WebPageChannelContentProcessor extends AbstractServiceChannelContentProcessor {
      
      public function __construct() {
        parent::__construct('kunta-api-service-webpage-channel');
      }
      
      public function renderServiceChannelContent($serviceId, $serviceChannelId, $lang) {
        $webPageChannel = Loader::findWebPageServiceChannel($serviceId, $serviceChannelId);
        return $this->getRenderer()->renderWebPageChannel($serviceId, $webPageChannel, $lang);
      }
      
    }
  }
  
  add_action('init', function(){
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new WebPageChannelContentProcessor());
  });
  
?>