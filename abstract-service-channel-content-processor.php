<?php
  namespace KuntaAPI\Services;
  
  require_once( __DIR__ . '/vendor/autoload.php');
    
  if (!defined('ABSPATH')) { 
    exit;
  }
  
  if (!class_exists( 'KuntaAPI\Services\AbstractServiceChannelContentProcessor' ) ) {
    
    abstract class AbstractServiceChannelContentProcessor extends \KuntaAPI\Core\AbstractContentProcessor {

      private $type;
      private $renderer;
      
      public function __construct($type) {
        $this->type = $type;
        $this->renderer = new ServiceChannelRenderer();
      }

      public function process($dom, $mode) {

        foreach ($dom->find('*[data-type="'. $this->type .'"]') as $article) {
          $serviceId = $article->{'data-service-id'};
          $serviceChannelId = $article->{'data-service-channel-id'};
          $lang = $article->{'data-lang'};
          if (empty($lang)) {
            $lang = \KuntaAPI\Core\LocaleHelper::getCurrentLanguage();
          }
          
          if ($mode == 'edit') {
            $article->class = 'mceNonEditable';
            $article->contentEditable = 'false';
            $article->readonly = 'true';
          } else {
            $article->removeAttribute('data-service-id');
            $article->removeAttribute('data-type');
            $article->removeAttribute('data-service-channel-id');
            $article->removeAttribute('data-lang');
          }
          
          if (!empty($serviceId) && !empty($serviceChannelId)) {
            $article->innertext = $this->renderServiceChannelContent($serviceId, $serviceChannelId, $lang);
          } else {
          	$article->innertext = 'Failed to load service channel content';
          }
          
        }
        
      }
      
      public abstract function renderServiceChannelContent($serviceId, $serviceChannelId, $lang);
      
      protected function getRenderer() {
        return $this->renderer;
      }
      
    }
  }
  
?>