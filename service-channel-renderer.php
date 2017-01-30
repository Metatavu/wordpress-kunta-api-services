<?php
  namespace KuntaAPI\Services;

  use KuntaAPI\Services\ServiceChannelMapper;
		
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/service-channel-mapper.php');
  
  if (!class_exists( 'KuntaAPI\Services\ServiceChannelRenderer' ) ) {
    class ServiceChannelRenderer {
      
      private $twig;
      
      public function __construct() {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem( __DIR__ . '/templates'));
        $this->twig->addExtension(new TwigExtension());
      }
      
      public function renderElectronicChannel($serviceId, $electronicChannel, $lang) {
        return $this->twig->render("service-components/electronic-service-channel.twig", [
          'serviceId' => $serviceId,
          'lang' => $lang,
          'electronicChannel' => $electronicChannel
        ]);
      }
      
      public function renderPhoneChannel($serviceId, $phoneChannel, $lang) {
        return $this->twig->render("service-components/phone-service-channel.twig", [
          'serviceId' => $serviceId, 
          'phoneChannel' => $phoneChannel,
          'lang' =>	$lang
        ]);
      }
      
      public function renderPrintableFormChannel($serviceId, $printableFormChannel, $lang) {
      	return $this->twig->render("service-components/printable-form-service-channel.twig", [
      	  'serviceId' => $serviceId,
      	  'lang' => $lang,
      	  'printableFormChannel' => $printableFormChannel
      	]);
      }
      
      public function renderServiceLocationChannel($serviceId, $serviceLocationChannel, $lang) {
        $channelData = ServiceChannelMapper::mapServiceLocationChannel($serviceId, $serviceLocationChannel)[$lang];
        return $this->twig->render("service-components/service-location-service-channel.twig", $channelData);
      }
      
      public function renderWebPageChannel($serviceId, $webPageChannel, $lang) {
        $channelData = ServiceChannelMapper::mapWebPageChannel($serviceId, $webPageChannel)[$lang];
        return $this->twig->render("service-components/webpage-service-channel.twig", $channelData);
      }
      
    }  
  }
?>