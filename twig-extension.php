<?php
namespace KuntaAPI\Services;

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

require_once( __DIR__ . '/vendor/autoload.php');

if (!class_exists( 'KuntaAPI\Services\TwigExtension' ) ) {
  class TwigExtension extends \Twig_Extension {
     
    private $dayMap;
    private $mapper;

    public function __construct() {
      $this->mapper = new \KuntaAPI\Services\Mapper();
      $this->dayMap = [
        '0' => 'Su',
        '1' => 'Ma',
        '2' => 'Ti',
        '3' => 'Ke',
        '4' => 'To',
        '5' => 'Pe',
        '6' => 'La'
      ];
    }
      
    public function getFilters() {
      return [
        new \Twig_SimpleFilter('localizedValue', array($this, 'localizedValueFilter')),
        new \Twig_SimpleFilter('shortDay', array($this, 'shortDayFilter')),
        new \Twig_SimpleFilter('serviceLocationPath', array($this, 'serviceLocationPathFilter'))
      ];
    }
      
    public function localizedValueFilter($localizedItems, $lang, $type = null) {
      if (is_array($localizedItems)) {
        foreach ($localizedItems as $localizedItem) {
          if (($localizedItem->getLanguage() == $lang) && (!$type || ($type == $localizedItem->getType()))) {
            return $localizedItem->getValue();
          }
        }
      }
        
      return '';
    }
      
    public function shortDayFilter($text) {
      return $this->dayMap[$text];
    }
    
    public function serviceLocationPathFilter($text) {
      $pageId = $this->mapper->getDefaultPageId($text);
      if (empty(!$pageId)) {
        return '/' . get_page_uri($pageId);
      }
      
      return 'about:blank';
    }
    
  }
}
?>