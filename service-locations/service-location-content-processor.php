<?php
  namespace KuntaAPI\Services\ServiceLocations;
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  require_once( __DIR__ . '/service-location-component-renderer.php');
  require_once( __DIR__ . '/../service-loader.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\ServiceLocations\ServiceLocationContentProcessor' ) ) {
    
    class ServiceLocationContentProcessor extends \KuntaAPI\Core\AbstractContentProcessor {

      public function process($dom, $mode) {
        $renderer = new ServiceLocationComponentRenderer();
        
        foreach ($dom->find('*[data-type="kunta-api-service-location-component"]') as $article) {
          $serviceId = $article->{'data-service-id'};
          $component = $article->{'data-component'};
          $lang = $article->{'data-lang'};
          $serviceChannelId = $article->{'data-service-channel-id'};
          
          if (empty($lang)) {
            $lang = \KuntaAPI\Core\LocaleHelper::getCurrentLanguage();
          }

          if($mode == 'edit') {
            $article->class = 'mceNonEditable';
            $article->contentEditable = 'false';
            $article->readonly = 'true';
          } else {
            $article->removeAttribute('data-service-id');
            $article->removeAttribute('data-type');
            $article->removeAttribute('data-component');
            $article->removeAttribute('data-lang');
            $article->removeAttribute('data-service-channel-id');
          }

          $service = \KuntaAPI\Services\Loader::findService($serviceId);
          if (isset($service)) {          
            $serviceLocationChannel = \KuntaAPI\Services\Loader::findServiceLocationServiceChannel($service->getId(), $serviceChannelId);
            if (isset($serviceLocationChannel)) {
              $article->innertext = $renderer->renderComponent($lang, $service, $serviceLocationChannel, $component);
            }
          }
        } 
      }
    }
  }
  
  add_action('init', function(){
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new ServiceLocationContentProcessor());
  });
  
?>