<?php
  namespace KuntaAPI\Services;
  
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
        $this->twig->addExtension(new TwigExtension());
      }
      
      public function renderServicePage($lang, $service) {
        $serviceId = $service->getId();
        
        $componentDatas = ServiceComponentMapper::mapLocaleContents($service);
        foreach ($componentDatas as $language => $value) {
          $componentDatas[$language]['electronicChannels'] = [];
          $componentDatas[$language]['phoneChannels'] = [];
          $componentDatas[$language]['printableFormChannels'] = [];
          $componentDatas[$language]['serviceLocationChannels'] = [];
          $componentDatas[$language]['webPageChannels'] = [];
        }
        
        if (isset($service['electronicChannels'])) {
          foreach ($service['electronicChannels'] as $electronicChannel) {
            foreach (ServiceChannelMapper::mapElectronicChannel($serviceId, $electronicChannel) as $language => $electronicChannelData) {
             $componentDatas[$language]['electronicChannels'][] = $electronicChannelData;
            }
          }
        }
        
        if(isset($service['phoneChannels'])) {
          foreach ($service['phoneChannels'] as $phoneChannel) {
            foreach (ServiceChannelMapper::mapPhoneChannel($serviceId, $phoneChannel) as $language => $phoneChannelData) {
             $componentDatas[$language]['phoneChannels'][] = $phoneChannelData;
            }
          }
        }
        
        if(isset($service['printableFormChannels'])) {
          foreach ($service['printableFormChannels'] as $printableFormChannel) {
            foreach (ServiceChannelMapper::mapPrintableFormChannel($serviceId, $printableFormChannel) as $language => $printableFormChannelData) {
             $componentDatas[$language]['printableFormChannels'][] = $printableFormChannelData;
            }
          }
        }
        
        if(isset($service['serviceLocationChannels'])) {
          foreach ($service['serviceLocationChannels'] as $serviceLocationChannel) {
            foreach (ServiceChannelMapper::mapServiceLocationChannel($serviceId, $serviceLocationChannel) as $language => $serviceLocationChannelData) {
              $componentDatas[$language]['serviceLocationChannels'][] = $serviceLocationChannelData;
            }
          }
        }
        
        if(isset($service['webPageChannels'])) {
          foreach ($service['webPageChannels'] as $webPageChannel) {
            foreach (ServiceChannelMapper::mapWebPageChannel($serviceId, $webPageChannel) as $language => $webPageChannelData) {
             $componentDatas[$language]['webPageChannels'][] = $webPageChannelData;
            }
          }
        }
        
        return $this->renderServiceContent($serviceId, $componentDatas[$lang]);
      }

      public function renderLocationChannelPage($lang, $serviceId, $serviceLocationChannel) {
      	return $this->twig->render('pages/service-location-channel.twig', [
          'serviceId' => $serviceId,
      	  'serviceLocationChannel' => $serviceLocationChannel,
      	  'lang' => $lang
      	]);
      }
      
      private function renderServiceContent($serviceId, $languageData) {
        return $this->twig->render("pages/service.twig", [
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