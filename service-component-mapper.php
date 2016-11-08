<?php
  namespace KuntaAPI\Services;
  
  use KuntaAPI\Model\LocalizedValue;
		
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\ComponentMapper' ) ) {
    class ComponentMapper {
      
      public static function renderLocaleContents($service) {
        $result = [
          'fi' => [],
          'en' => []
        ];

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
          $result[$language]['languages'] = $service->getLanguages();
        }
        return $result;
      }   
    }  
  }
?>