<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/service-component-renderer.php');
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\ServiceContentProcessor' ) ) {
    
    class ServiceContentProcessor extends \KuntaAPI\Core\AbstractContentProcessor {

      public function process($lang, $dom, $mode) {
        $renderer = new ServiceComponentRenderer();
        
        foreach ($dom->find('*[data-type="kunta-api-service-component"]') as $article) {
          $serviceId = $article->{'data-service-id'};
          $serviceComponent = $article->{'data-component'};
          if($mode == 'edit') {
             $article->class = 'mceNonEditable';
             $article->contentEditable = 'false';
             $article->readonly = 'true';
          } else {
            $article->removeAttribute('data-service-id');
            $article->removeAttribute('data-type');
            $article->removeAttribute('data-component');
          }
          $service = Loader::findService($serviceId);
          $article->innertext = $renderer->renderComponent($service, $lang, $serviceComponent);
        } 
      }
    }
  }
  
  add_action('init', function(){
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new ServiceContentProcessor());
  });
  
?>