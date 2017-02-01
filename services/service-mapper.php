<?php
  namespace KuntaAPI\Services;
  
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/../vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\Mapper' ) ) {
    class Mapper {
      
      public function __construct() {
      }
      
      public function getDefaultPageId($serviceId) {
      	$mapping = $this->getMapping();
      	return $mapping[$serviceId];
      }
      
      public function setDefaultPageId($serviceId, $pageId) {
      	$mapping = $this->getMapping();
      	$mapping[$serviceId] = $pageId;
      	$this->setOptionValue($mapping);
      }
      
      private function getMapping() {
      	$value = $this->getOptionValue();
      	return empty($value) ? [] : $value;
      }
      
      private function getOptionValue() {
      	return get_option('kunta-api-default-services');
      }
      
      private function setOptionValue($value) {
      	update_option('kunta-api-default-services', $value);
      }
      
    }  
  }
  

?>