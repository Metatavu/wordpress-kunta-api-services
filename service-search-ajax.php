<?php
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  require_once( __DIR__ . '/services/service-component-renderer.php');  
  
  add_action( 'wp_ajax_kunta_api_search_services', function () {
    $organizationId = \KuntaAPI\Core\CoreSettings::getValue('organizationId');
    $services = \KuntaAPI\Core\Api::getServicesApi()->listServices($organizationId, $_POST['data']);
    $responce = [];
    foreach ($services as $service) {
      $responce[] = $service -> __toString();
    }
    echo '[';
    echo join(',', $responce);
    echo ']';
    wp_die();
  } );
  
  
  add_action( 'wp_ajax_kunta_api_render_service_component', function () {
    $renderer = new \KuntaAPI\Services\ServiceComponentRenderer();
    $service = \KuntaAPI\Services\Loader::findService($_POST['serviceId']);
    echo $renderer->renderComponentParent($service, $_POST['lang'], $_POST['component']);
    wp_die();
  } );



?>