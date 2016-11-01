<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once(__DIR__ . '/vendor/autoload.php');
  require_once(__DIR__ . '/service-loader.php');
  require_once(__DIR__ . '/service-mapper.php');
  require_once(__DIR__ . '/service-renderer.php');
  
  if (!class_exists( 'KuntaAPI\Services\Updater' ) ) {
  	
    class Updater {
      
      private $loader;
      private $renderer;
      private $mapper;
      private $helper;
    	
      public function __construct() {
      	$this->loader = new \KuntaAPI\Services\Loader();
      	$this->renderer = new \KuntaAPI\Services\Renderer();
      	$this->mapper = new \KuntaAPI\Services\Mapper();
      	$this->helper = new \KuntaAPI\Core\QTranslateHelper();
      	
      	add_action('kunta_api_service_updater_poll', array($this, 'poll'));
      }
      
      public function startPolling() {
  	    wp_schedule_event(time(), 'Minutely', 'kunta_api_service_updater_poll');
      }
      
      public function poll() {
      	error_log("Polling for new services");
      	
      	$services = $this->loader->listServices(0, 3);
      	foreach ($services as $service) {
      	  $serviceId = $service->getId();
      	  $defaultPageId = $this->mapper->getDefaultPageId($serviceId);
      	  if (!$defaultPageId) {
      	  	$title = \KuntaAPI\Core\QTranslateHelper::translateLocalizedValues($service->getNames());
      	  	$content = $this->renderDefaultPage($service);
      	  	$pageId = $this->createPage($title, $content);
      	  	$this->mapper->setDefaultPageId($serviceId, $pageId);
      	  }
      	}
      }
      
      private function renderDefaultPage($service) {
      	return $this->renderer->renderDefault($service);
      }
      
      private function createPage($title, $content) {
      	return wp_insert_post(array(
      	  'post_content' => $content,
      	  'post_title' => $title,
      	  'post_status' => 'draft',
      	  'post_type' => 'page'
      	));
      }

    }
    
  }
  
  add_action('wp', function () {
  	$updater = new Updater();
  	$updater->startPolling();
  });
  
?>