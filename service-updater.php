<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once(__DIR__ . '/vendor/autoload.php');
  require_once(__DIR__ . '/service-loader.php');
  require_once(__DIR__ . '/service-mapper.php');
  require_once(__DIR__ . '/page-renderer.php');
  
  if (!class_exists( 'KuntaAPI\Services\Updater' ) ) {
  	
    class Updater {
      
      private $renderer;
      private $mapper;
      
      public function __construct() {
      	$this->renderer = new \KuntaAPI\Services\PageRenderer();
      	$this->mapper = new \KuntaAPI\Services\Mapper();
      	
      	add_action('kunta_api_service_updater_poll', array($this, 'poll'));
      }
      
      public function startPolling() {
        if (! wp_next_scheduled ( 'kunta_api_service_updater_poll' )) {
          wp_schedule_event(time(), 'Minutely', 'kunta_api_service_updater_poll');
        }
      }
      
      public function poll() {
        $offset = get_option('kunta-api-sync-offset');
      	if(empty($offset)) {
          $offset = 0;
        }
      	$services = Loader::listOrganizationServices($offset, 10);
      	foreach ($services as $service) {
      	  $serviceId = $service->getId();
      	  $defaultPageId = $this->mapper->getDefaultPageId($serviceId);
      	  if (!$defaultPageId) {
      	  	$title = \KuntaAPI\Core\LocaleHelper::getDefaultValue($service->getNames());
      	  	$content = $this->renderDefaultPage($service);
      	  	$pageId = $this->createPage($title, $content);
      	  	$this->mapper->setDefaultPageId($serviceId, $pageId);
      	  }
      	}
        if(count($services) == 0) {
          $offset = 0;
        } else {
          $offset += 10;
        }
        update_option('kunta-api-sync-offset', $offset);
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
  
  $updater = new Updater();
  $updater->startPolling();
?>