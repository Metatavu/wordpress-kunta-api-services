<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');

  if (!class_exists( 'KuntaAPI\Services\Loader' ) ) {
  	
    class Loader {
      
      private static $services = [];
      private static $electronicChannels = [];
      private static $phoneChannels = [];
      private static $printableFormChannels = [];
      private static $serviceLocationChannels = [];
      private static $webPageChannels = [];
      
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
      
      public static function findPhoneServiceChannel($serviceId, $id) {
        if(!isset(self::$phoneChannels[$id])) {
          self::$phoneChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServicePhoneChannel($serviceId, $id);
        }
        return self::$phoneChannels[$id];
      }
      
      public static function findPrintableFormServiceChannel($serviceId, $id) {
        if(!isset(self::$printableFormChannels[$id])) {
          self::$printableFormChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServicePrintableFormChannel($serviceId, $id);
        }
        return self::$printableFormChannels[$id];
      }
      
      public static function findServiceLocationServiceChannel($serviceId, $id) {
        if(!isset(self::$serviceLocationChannels[$id])) {
          self::$serviceLocationChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServiceServiceLocationChannel($serviceId, $id);
        }
        return self::$serviceLocationChannels[$id];
      }
    
      public static function findWebPageServiceChannel($serviceId, $id) {
        if(!isset(self::$webPageChannels[$id])) {
          self::$webPageChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServiceWebPageChannel($serviceId, $id);
        }
        return self::$webPageChannels[$id];
      }
      
      public static function findService($id) {
        if(!isset(self::$services[$id])) {
          self::$services[$id] = \KuntaAPI\Core\Api::getServicesApi()->findService($id);
          self::$services[$id]['electronicChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceElectronicChannels($id);
          self::$services[$id]['phoneChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServicePhoneChannels($id);
          self::$services[$id]['printableFormChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServicePrintableFormChannels($id);
          self::$services[$id]['serviceLocationChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceServiceLocationChannels($id);
          self::$services[$id]['webPageChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceWebPageChannels($id);
          
          self::cacheServiceChannelsFromService(self::$services[$id]);
        }
        return self::$services[$id];
      }

      private static function cacheServiceChannelsFromService($service) {
        foreach ($service['electronicChannels'] as $electronicChannel) {
          self::$electronicChannels[$electronicChannel->getId()] = $electronicChannel;
        }
        foreach ($service['phoneChannels'] as $phoneChannel) {
          self::$phoneChannels[$phoneChannel->getId()] = $phoneChannel;
        }
        foreach ($service['printableFormChannels'] as $printableFormChannel) {
          self::$printableFormChannels[$printableFormChannel->getId()] = $printableFormChannel;
        }
        foreach ($service['serviceLocationChannels'] as $serviceLocationChannel) {
          self::$serviceLocationChannels[$serviceLocationChannel->getId()] = $serviceLocationChannel;
        }
        foreach ($service['webPageChannels'] as $webPageChannel) {
          self::$webPageChannels[$webPageChannel->getId()] = $webPageChannel;
        }
      }
    }
    
  }

?>