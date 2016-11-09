<?php
  namespace KuntaAPI\Services;
  
  use KuntaAPI\Model\LocalizedValue;
  use KuntaAPI\Services\ServiceComponentMapper;
  use KuntaAPI\Services\ServiceChannelMapper;
		
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/service-component-mapper.php');
  
  if (!class_exists( 'KuntaAPI\Services\PageRenderer' ) ) {
    class PageRenderer {
      
      private $twig;
      
      public function __construct() {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem( __DIR__ . '/templates'));
      }
      
      public function renderDefault($service) {
        return $this->renderLocaleContents($service); 
      }
      
      private function renderLocaleContents($service) {
      	$serviceId = $service->getId();
      	$componentDatas = ServiceComponentMapper::renderLocaleContents($service);
        foreach ($componentDatas as $language => $value) {
          $componentDatas[$language]['electronicChannels'] = [];
        }
        if(isset($service['electronicChannels'])){
          foreach ($service['electronicChannels'] as $electronicChannel) {
            foreach (ServiceChannelMapper::renderElectronicChannel($serviceId, $electronicChannel) as $language => $electronicChannelData) {
             $componentDatas[$language]['electronicChannels'][] = $electronicChannelData;
             error_log('rendered electronic channel');
            }
          }
        }
        
      	$localizedValues = [];
        
      	foreach ($componentDatas as $language => $componentData) {;
      	  $localizedValue =	new LocalizedValue();
      	  $localizedValue->setLanguage($language);
          $value = $this->renderLocaleContent($serviceId, $componentData);
          $localizedValue->setValue($value);
      	  $localizedValues[] = $localizedValue;
      	}
      	
      	return \KuntaAPI\Core\QTranslateHelper::translateLocalizedValues($localizedValues);
      }
      
      private function renderLocaleContent($serviceId, $languageData) {
      	return $this->twig->render("service-default-layout.twig", [
      	  'serviceId' => $serviceId,
      	  'description' => $languageData['description'],
      	  'userInstruction' => $languageData['userInstruction'],
      	  'languages' => $languageData['languages'],
          'electronicChannels' => $languageData['electronicChannels']
      	]);
      }
      
    }  
  }
?>