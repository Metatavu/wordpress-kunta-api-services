<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/abstract-service-channel-content-processor.php');
    
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\ElectronicChannelContentProcessor' ) ) {
    
    class ElectronicChannelContentProcessor extends AbstractServiceChannelContentProcessor {
      
      public function __construct() {
        parent::__construct('kunta-api-service-electronic-channel');
      }
      
      public function renderServiceChannelContent($serviceId, $serviceChannelId, $lang) {
        $electronicChannel = Loader::findElectronicServiceChannel($serviceId, $serviceChannelId);
        return $this->getRenderer()->renderElectronicChannel($serviceId, $electronicChannel, $lang);
      }
      
    }
  }
  
  add_action('init', function(){
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new ElectronicChannelContentProcessor());
  });
  
?>