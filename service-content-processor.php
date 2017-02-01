<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/services/service-component-renderer.php');
  
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\ServiceContentProcessor' ) ) {
    
    class ServiceContentProcessor extends \KuntaAPI\Core\AbstractContentProcessor {

      public function process($dom, $mode) {
        $renderer = new ServiceComponentRenderer();
        
        foreach ($dom->find('*[data-type="kunta-api-service-component"]') as $article) {
          $serviceId = $article->{'data-service-id'};
          $serviceComponent = $article->{'data-component'};
          $lang = $article->{'data-lang'};
          
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
          }

          $service = Loader::findService($serviceId);
          if (isset($service)) {          
            $article->innertext = $renderer->renderComponent($service, $lang, $serviceComponent);
          }
        } 
      }
    }
  }
  
  add_action('init', function(){
    global $kuntaAPIPageProcessor;
    $kuntaAPIPageProcessor->registerContentProcessor(new ServiceContentProcessor());
  });
  
?>