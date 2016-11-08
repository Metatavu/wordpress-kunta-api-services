<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');

  if (!class_exists( 'KuntaAPI\Services\Loader' ) ) {
  	
    class Loader {
      
      private static $services = [];
      
      public static function listOrganizationServices($firstResult, $maxResults) {
        $organizationId = \KuntaAPI\Core\CoreSettings::getValue('organizationId');
        $organizationServices = \KuntaAPI\Core\Api::getOrganizationServicesApi()->listOrganizationOrganizationServices($organizationId, $firstResult, $maxResults);
        $serviceList = [];
        foreach ($organizationServices as $organizationService) {
          $serviceId = $organizationService->getServiceId();
          if (!in_array($serviceId, $serviceList)) {
            $serviceList[] = $serviceId;
          }
        }
        $services = [];
        foreach ($serviceList as $id) {
          $services[] = Loader::findService($id);
        }
        return $services;
      }
      
      public static function listServices($firstResult, $maxResults) {
        return \KuntaAPI\Core\Api::getServicesApi()->listServices($firstResult, $maxResults);
      }
      
      public static function findService($id) {
        if(!isset(self::$services[$id])) {
          self::$services[$id] = \KuntaAPI\Core\Api::getServicesApi()->findService($id);
        }
        return self::$services[$id];
      }
    }
    
  }

?>