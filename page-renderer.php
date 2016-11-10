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
          $componentDatas[$language]['phoneChannels'] = [];
          $componentDatas[$language]['printableFormChannels'] = [];
          $componentDatas[$language]['serviceLocationChannels'] = [];
          $componentDatas[$language]['webPageChannels'] = [];
        }
        if(isset($service['electronicChannels'])) {
          foreach ($service['electronicChannels'] as $electronicChannel) {
            foreach (ServiceChannelMapper::renderElectronicChannel($serviceId, $electronicChannel) as $language => $electronicChannelData) {
             $componentDatas[$language]['electronicChannels'][] = $electronicChannelData;
             error_log('rendered electronic channel');
            }
          }
        }
        
        if(isset($service['phoneChannels'])) {
          foreach ($service['phoneChannels'] as $phoneChannel) {
            foreach (ServiceChannelMapper::renderPhoneChannel($serviceId, $phoneChannel) as $language => $phoneChannelData) {
             $componentDatas[$language]['phoneChannels'][] = $phoneChannelData;
             error_log('rendered phone channel');
            }
          }
        }
        
        if(isset($service['printableFormChannels'])) {
          foreach ($service['printableFormChannels'] as $printableFormChannel) {
            foreach (ServiceChannelMapper::renderPrintableFormChannel($serviceId, $printableFormChannel) as $language => $printableFormChannelData) {
             $componentDatas[$language]['printableFormChannels'][] = $printableFormChannelData;
             error_log('rendered printableForm channel');
            }
          }
        }
        
        if(isset($service['serviceLocationChannels'])) {
          foreach ($service['serviceLocationChannels'] as $serviceLocationChannel) {
            foreach (ServiceChannelMapper::renderServiceLocationChannel($serviceId, $serviceLocationChannel) as $language => $serviceLocationChannelData) {
             $componentDatas[$language]['serviceLocationChannels'][] = $serviceLocationChannelData;
             error_log('rendered serviceLocation channel');
            }
          }
        }
        
        if(isset($service['webPageChannels'])) {
          foreach ($service['webPageChannels'] as $webPageChannel) {
            foreach (ServiceChannelMapper::renderWebPageChannel($serviceId, $webPageChannel) as $language => $webPageChannelData) {
             $componentDatas[$language]['webPageChannels'][] = $webPageChannelData;
             error_log('rendered webPage channel');
            }
          }
          error_log(print_r($service->getNames(), true));
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
          'electronicChannels' => $languageData['electronicChannels'],
          'phoneChannels' => $languageData['phoneChannels'],
          'printableFormChannels' => $languageData['printableFormChannels'],
          'serviceLocationChannels' => $languageData['serviceLocationChannels'],
          'webPageChannels' => $languageData['webPageChannels']
      	]);
      }
      
    }  
  }
?>