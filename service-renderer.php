<?php
  namespace KuntaAPI\Services;
  
  use KuntaAPI\Model\LocalizedValue;
  use KuntaAPI\Services\ComponentMapper;
		
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/service-component-mapper.php');
  
  if (!class_exists( 'KuntaAPI\Services\Renderer' ) ) {
    class Renderer {
      
      private $twig;
      
      public function __construct() {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem( __DIR__ . '/templates'));
      }
      
      function renderDefault($service) {
        return $this->renderLocaleContents($service); 
      }
      
      function renderComponent($service, $lang, $type) {
        $componentData = ComponentMapper::renderLocaleContents($service)[$lang];
        
        switch ($type) {
          case 'description':
            return $this->twig->render("service-description.twig", $componentData);
          case 'userInstruction':
            return $this->twig->render("service-user-instructions.twig", $componentData);
          case 'languages':
            return $this->twig->render("service-languages.twig", $componentData);
          default:
            error_log("unknown servicetype $type");
            break;
        }
      }
      
      private function renderLocaleContents($service) {
      	$serviceId = $service->getId();
      	$componentDatas = ComponentMapper::renderLocaleContents($service);

      	$localizedValues = [];
      	
      	foreach ($componentDatas as $language => $componentData) {;
      	  $localizedValue =	new LocalizedValue();
      	  $localizedValue->setLanguage($language);
          $value = $this->renderLocaleContent($serviceId, $componentData);
          $localizedValue->setValue($value);
      	  $localizedValues[] = $localizedValue;
      	}
      	
      	return \KuntaAPI\Core\QTranslateHelper::translateLocalizedValues($localizedValues);
      }
      
      private function renderLocaleContent($serviceId, $languageData) {
      	return $this->twig->render("service-default-layout.twig", [
      	  'serviceId' => $serviceId,
      	  'description' => $languageData['description'],
      	  'userInstruction' => $languageData['userInstruction'],
      	  'languages' => $languageData['languages']
      	]);
      }
      
    }  
  }
?>