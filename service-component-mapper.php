<?php
  namespace KuntaAPI\Services;
  	
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\ServiceComponentMapper' ) ) {
    class ServiceComponentMapper {
      
      public static function mapLocaleContents($service) {
        $result = [];

        foreach (\KuntaAPI\Core\QTranslateHelper::getEnabledLanguages() as $lang) {
          $result[$lang] = [];
        }

      	foreach ($service->getDescriptions() as $serviceDescription) {
      	  switch ($serviceDescription->getType()) {
      		case 'Description':
      		  $result[$serviceDescription->getLanguage()]['description'] = $serviceDescription->getValue();
      		  break;
      		case 'ServiceUserInstruction':
      		  $result[$serviceDescription->getLanguage()]['userInstruction'] = $serviceDescription->getValue();
      		  break;
      		default:
      		  error_log("Ignoring description type " . $serviceDescription->getType());
      		break;
      	  }
      	}
        
        foreach ($result as $language => $value) {
          $result[$language]['serviceId'] = $service->getId();
          $result[$language]['languages'] = $service->getLanguages();
        }
        return $result;
      }   
    }  
  }
?>