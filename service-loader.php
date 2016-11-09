<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');

  if (!class_exists( 'KuntaAPI\Services\Loader' ) ) {
  	
    class Loader {
      
      private static $services = [];
      private static $electronicChannels = [];
      
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
      
      public static function findElectronicServiceChannel($serviceId, $id) {
        if(!isset(self::$electronicChannels[$id])) {
          self::$electronicChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServiceElectronicChannel($serviceId, $id);
        }
        return self::$electronicChannels[$id];
      }
      
      public static function findService($id) {
        if(!isset(self::$services[$id])) {
          self::$services[$id] = \KuntaAPI\Core\Api::getServicesApi()->findService($id);
          self::$services[$id]['electronicChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceElectronicChannels($id);
          self::$services[$id]['phoneChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServicePhoneChannels($id);
          self::$services[$id]['printableFormChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServicePrintableFormChannels($id);
          self::$services[$id]['serviceLocationChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceServiceLocationChannels($id);
          self::$services[$id]['webPageChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceWebPageChannels($id);
        }
        return self::$services[$id];
      }
    }
    
  }

?>