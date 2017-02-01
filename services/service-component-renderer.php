<?php
  namespace KuntaAPI\Services;
  	
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\ServiceComponentRenderer' ) ) {
    class ServiceComponentRenderer {
      
      private $twig;
      
      public function __construct() {
        $this->twig = new \Twig_Environment(new \Twig_Loader_Filesystem( __DIR__ . '/../templates'));
        $this->twig->addExtension(new TwigExtension());
      }
      
      public function renderComponent($service, $lang, $type) {
        $model = [
          'lang' => $lang,
          'service' => $service
        ];
        
        switch ($type) {
          case 'description':
            return $this->twig->render("service-components/service-description.twig", $model);
          case 'userInstruction':
            return $this->twig->render("service-components/service-user-instructions.twig", $model);
          case 'languages':
            return $this->twig->render("service-components/service-languages.twig", $model);
          default:
            error_log("unknown servicetype $type");
            break;
        }
      }
      
      public function renderComponentParent($service, $lang, $type) {
      	$model = [
      	  'lang' => $lang,
      	  'service' => $service
      	];
      	 
        switch ($type) {
          case 'description':
            return $this->twig->render("service-components/service-description-parent.twig", $model);
          case 'userInstruction':
            return $this->twig->render("service-components/service-user-instructions-parent.twig", $model);
          case 'languages':
            return $this->twig->render("service-components/service-languages-parent.twig", $model);
          default:
            error_log("unknown servicetype $type");
            break;
        }
      }
      
    }  
  }
?>