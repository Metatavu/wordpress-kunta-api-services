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
      }
      
      public function renderElectronicChannel($serviceId, $electronicChannel, $lang) {
        $channelData = ServiceChannelMapper::renderElectronicChannel($serviceId, $electronicChannel)[$lang];
        return $this->twig->render("electronic-service-channel.twig", $channelData);
      }   
    }  
  }
?>