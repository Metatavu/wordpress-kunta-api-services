<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/vendor/autoload.php');
    
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\ElectronicChannelContentProcessor' ) ) {
    
    class ElectronicChannelContentProcessor extends \KuntaAPI\Core\AbstractContentProcessor {

      public function process($lang, $dom, $mode) {
        $renderer = new ServiceChannelRenderer();
        
        foreach ($dom->find('*[data-type="kunta-api-service-electronic-channel"]') as $article) {
          $serviceId = $article->{'data-service-id'};
          $serviceChannelId = $article->{'data-service-channel-id'};
          if($mode == 'edit') {
             $article->class = 'mceNonEditable';
          } else {
            $article->removeAttribute('data-service-id');
            $article->removeAttribute('data-type');
            $article->removeAttribute('data-service-channel-id');
          }
          $electronicChannel = Loader::findElectronicServiceChannel($serviceId, $serviceChannelId);
          $article->innertext = $renderer->renderElectronicChannel($serviceId, $electronicChannel, $lang);
        } 
      }
    }
  }
  
  add_action('init', function(){
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new ElectronicChannelContentProcessor());
  });
  
?>