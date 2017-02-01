<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/../vendor/autoload.php');

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
        $organizationServices = [];
        try {
          $organizationServices = \KuntaAPI\Core\Api::getOrganizationServicesApi()->listOrganizationOrganizationServices($organizationId, $firstResult, $maxResults);
        } catch (\KuntaAPI\ApiException $e) {
          error_log("Organization services listing failed with following message: " . $e->getMessage());
        }
        
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
        if(!isset(static::$electronicChannels[$id])) {
          try {
            static::$electronicChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServiceElectronicChannel($serviceId, $id);
          } catch (\KuntaAPI\ApiException $e) {
            error_log("findElectronicServiceChannel failed with following message: " . $e->getMessage());
          }
        }
        
        return static::$electronicChannels[$id];
      }
      
      public static function findPhoneServiceChannel($serviceId, $id) {
        if(!isset(static::$phoneChannels[$id])) {
          try {
            static::$phoneChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServicePhoneChannel($serviceId, $id);
          } catch (\KuntaAPI\ApiException $e) {
        	error_log("findPhoneServiceChannel failed with following message: " . $e->getMessage());
          }
        }
        return static::$phoneChannels[$id];
      }
      
      public static function findPrintableFormServiceChannel($serviceId, $id) {
        if(!isset(static::$printableFormChannels[$id])) {
          try {
            static::$printableFormChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServicePrintableFormChannel($serviceId, $id);
          } catch (\KuntaAPI\ApiException $e) {
            error_log("findPrintableFormServiceChannel failed with following message: " . $e->getMessage());
          }	
        }
        
        return static::$printableFormChannels[$id];
      }
      
      public static function findServiceLocationServiceChannel($serviceId, $id) {
        if(!isset(static::$serviceLocationChannels[$id])) {
          try {
            static::$serviceLocationChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServiceServiceLocationChannel($serviceId, $id);
          } catch (\KuntaAPI\ApiException $e) {
        	error_log("findServiceLocationServiceChannel failed with following message: " . $e->getMessage());
          }
        }
        
        return static::$serviceLocationChannels[$id];
      }
      
      public static function listServiceLocationServiceChannels($serviceId) {
      	try {
          $serviceLocationChannels = \KuntaAPI\Core\Api::getServicesApi()->listServiceServiceLocationChannels($serviceId);
          
          foreach ($serviceLocationChannels as $serviceLocationChannel) {
          	static::$serviceLocationChannels[$serviceLocationChannel->getId()] = $serviceLocationChannel;
          }
          
          return $serviceLocationChannels;
        } catch (\KuntaAPI\ApiException $e) {
          error_log("findServiceLocationServiceChannel failed with following message: " . $e->getMessage());
        }
        
        return [];
      }
    
      public static function findWebPageServiceChannel($serviceId, $id) {
        if(!isset(static::$webPageChannels[$id])) {
          try {
            static::$webPageChannels[$id] = \KuntaAPI\Core\Api::getServicesApi()->findServiceWebPageChannel($serviceId, $id);
          } catch (\KuntaAPI\ApiException $e) {
        	error_log("findWebPageServiceChannel failed with following message: " . $e->getMessage());
          }
        }
        
        return static::$webPageChannels[$id];
      }
      
      public static function findService($id) {
        if(!isset(static::$services[$id])) {
          try {
            static::$services[$id] = \KuntaAPI\Core\Api::getServicesApi()->findService($id);
            static::$services[$id]['electronicChannels'] = [];
            static::$services[$id]['phoneChannels'] = [];
            static::$services[$id]['printableFormChannels'] = [];
            static::$services[$id]['serviceLocationChannels'] = [];
            static::$services[$id]['webPageChannels'] = [];
            
            try {
              static::$services[$id]['electronicChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceElectronicChannels($id);
            } catch (\KuntaAPI\ApiException $e) {
        	  error_log("listServiceElectronicChannels failed with following message: " . $e->getMessage());
            }
            
            try {
              static::$services[$id]['phoneChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServicePhoneChannels($id);
            } catch (\KuntaAPI\ApiException $e) {
              error_log("listServicePhoneChannels failed with following message: " . $e->getMessage());
            }
            
            try {
              static::$services[$id]['printableFormChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServicePrintableFormChannels($id);
            } catch (\KuntaAPI\ApiException $e) {
              error_log("listServicePrintableFormChannels failed with following message: " . $e->getMessage());
            }
            
            try {
              static::$services[$id]['serviceLocationChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceServiceLocationChannels($id);
            } catch (\KuntaAPI\ApiException $e) {
              error_log("listServiceServiceLocationChannels failed with following message: " . $e->getMessage());
            }
            
            try {
              static::$services[$id]['webPageChannels'] = \KuntaAPI\Core\Api::getServicesApi()->listServiceWebPageChannels($id);
            } catch (\KuntaAPI\ApiException $e) {
              error_log("listServiceWebPageChannels failed with following message: " . $e->getMessage());
            }
            
            static::cacheServiceChannelsFromService(static::$services[$id]);
          } catch (\KuntaAPI\ApiException $e) {
        	error_log("findService failed with following message: " . $e->getMessage());
          }
        }
        return static::$services[$id];
      }

      private static function cacheServiceChannelsFromService($service) {
        foreach ($service['electronicChannels'] as $electronicChannel) {
          static::$electronicChannels[$electronicChannel->getId()] = $electronicChannel;
        }
        foreach ($service['phoneChannels'] as $phoneChannel) {
          static::$phoneChannels[$phoneChannel->getId()] = $phoneChannel;
        }
        foreach ($service['printableFormChannels'] as $printableFormChannel) {
          static::$printableFormChannels[$printableFormChannel->getId()] = $printableFormChannel;
        }
        foreach ($service['serviceLocationChannels'] as $serviceLocationChannel) {
          static::$serviceLocationChannels[$serviceLocationChannel->getId()] = $serviceLocationChannel;
        }
        foreach ($service['webPageChannels'] as $webPageChannel) {
          static::$webPageChannels[$webPageChannel->getId()] = $webPageChannel;
        }
      }
    }
    
  }

?>