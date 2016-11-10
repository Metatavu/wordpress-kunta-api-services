<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/abstract-service-channel-content-processor.php');
    
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\PrintableFormChannelContentProcessor' ) ) {
    
    class PrintableFormChannelContentProcessor extends AbstractServiceChannelContentProcessor {
      
      public function __construct() {
        parent::__construct('kunta-api-service-printable-form-channel');
      }
      
      public function renderServiceChannelContent($serviceId, $serviceChannelId, $lang) {
        $serviceChannel = Loader::findPrintableFormServiceChannel($serviceId, $serviceChannelId);
        return $this->getRenderer()->renderPrintableFormChannel($serviceId, $serviceChannel, $lang);
      }  
    }
  }
  
  add_action('init', function() {
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new PrintableFormChannelContentProcessor());
  });
?>