<?php
  namespace KuntaAPI\Services;
  
  use KuntaAPI\Model\LocalizedValue;
		
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\Renderer' ) ) {
    class Renderer {
      
      private $twig;
      
      public function __construct() {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem( __DIR__ . '/templates'));
      }
      
      function renderDefault($service) {
        return $this->renderLocaleContents($service); 
      }
      
      private function renderLocaleContents($service) {
      	$serviceId = $service->getId();
      	
      	$languageDatas = [];
      	$languages = $service->getLanguages();
      	
      	foreach ($service->getDescriptions() as $serviceDescription) {
      	  switch ($serviceDescription->getType()) {
      		case 'Description':
      		  $languageDatas[$serviceDescription->getLanguage()]['description'] = $serviceDescription->getValue();
      		  break;
      		case 'ServiceUserInstruction':
      		  $languageDatas[$serviceDescription->getLanguage()]['userInstruction'] = $serviceDescription->getValue();
      		  break;
      		default:
      		  error_log("Ignoring description type " . $serviceDescription->getType());
      		break;
      	  }
      	}
      	
      	$localizedValues = [];
      	
      	foreach ($languageDatas as $language => $languageData) {
      	  $localizedValue =	new LocalizedValue();
      	  $localizedValue->setLanguage($language);
      	  $localizedValue->setValue($this->renderLocaleContent($serviceId, $languages, $languageData));
      	  $localizedValues[] = $localizedValue;
      	}
      	
      	return \KuntaAPI\Core\QTranslateHelper::translateLocalizedValues($localizedValues);
      }
      
      private function renderLocaleContent($serviceId, $languages, $languageData) {
      	return $this->twig->render("service-default-layout.twig", [
      	  'serviceId' => $serviceId,
      	  'description' => $languageData['description'],
      	  'userInstruction' => $languageData['userInstruction'],
      	  'languages' => $languages
      	]);
      }
      
    }  
  }
  

?>