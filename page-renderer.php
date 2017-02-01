<?php
  namespace KuntaAPI\Services;
    
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\PageRenderer' ) ) {
    class PageRenderer {
      
      private $twig;
      
      public function __construct() {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem( __DIR__ . '/templates'));
        $this->twig->addExtension(new TwigExtension());
      }
      
      public function renderServicePage($lang, $service) {
        $serviceId = $service->getId();
        return $this->twig->render("pages/service.twig", [
          'lang' => $lang,
          'serviceId' => $serviceId,
          'service' => $service,
	      'electronicChannels' => $service['electronicChannels'],
          'phoneChannels' => $service['phoneChannels'],
          'printableFormChannels' => $service['printableFormChannels'],
          'serviceLocationChannels' => $service['serviceLocationChannels'],
          'webPageChannels' => $service['webPageChannels']
        ]);
      }

      public function renderLocationChannelPage($lang, $serviceId, $serviceLocationChannel) {
      	return $this->twig->render('pages/service-location-channel.twig', [
          'serviceId' => $serviceId,
      	  'serviceLocationChannel' => $serviceLocationChannel,
      	  'lang' => $lang
      	]);
      }
      
    }  
  }
?>