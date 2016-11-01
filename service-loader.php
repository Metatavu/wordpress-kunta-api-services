<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');

  if (!class_exists( 'KuntaAPI\Services\Loader' ) ) {
  	
    class Loader {
      
      private $api;
      
      public function __construct() {
        $this->api = new \KuntaAPI\Core\Api();
      }
      
      public function listServices($firstResult, $maxResults) {
        return $this->api->getServicesApi()->listServices($firstResult, $maxResults);
      }
      
      public function findService($id) {
        return $this->api->getServicesApi()->findService($id);
      }
    }
    
  }

?>