<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/abstract-service-channel-content-processor.php');
    
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\PhoneChannelContentProcessor' ) ) {
    
    class PhoneChannelContentProcessor extends AbstractServiceChannelContentProcessor {
      
      public function __construct() {
        parent::__construct('kunta-api-service-phone-channel');
      }
      
      public function renderServiceChannelContent($serviceId, $serviceChannelId, $lang) {
        $phoneChannel = Loader::findPhoneServiceChannel($serviceId, $serviceChannelId);
        return $this->getRenderer()->renderPhoneChannel($serviceId, $phoneChannel, $lang);
      }  
    }
  }
  
  add_action('init', function() {
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new PhoneChannelContentProcessor());
  });
  
?>