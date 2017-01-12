<?php
  namespace KuntaAPI\Services;
  
  use KuntaAPI\Services\ServiceComponentMapper;
		
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/service-component-mapper.php');
  
  if (!class_exists( 'KuntaAPI\Services\ServiceComponentRenderer' ) ) {
    class ServiceComponentRenderer {
      
      private $twig;
      
      public function __construct() {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem( __DIR__ . '/templates'));
      }
      
      public function renderComponent($service, $lang, $type) {
        $componentData = ServiceComponentMapper::mapLocaleContents($service)[$lang];
        
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
      
      public function renderComponentParent($service, $lang, $type) {
        $componentData = ServiceComponentMapper::mapLocaleContents($service)[$lang];
        
        switch ($type) {
          case 'description':
            return $this->twig->render("service-description-parent.twig", $componentData);
          case 'userInstruction':
            return $this->twig->render("service-user-instructions-parent.twig", $componentData);
          case 'languages':
            return $this->twig->render("service-languages-parent.twig", $componentData);
          default:
            error_log("unknown servicetype $type");
            break;
        }
      }
      
    }  
  }
?>