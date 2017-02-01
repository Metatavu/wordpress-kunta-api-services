<?php
/*
 * Created on Oct 21, 2016
 * Plugin Name: Kunta API Services
 * Description: Wordpress plugin for Kunta API services integration
 * Version: 0.1
 * Author: Antti Leppä / Otavan Opisto
 */

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );
define('KUNTA_API_SERVICES_I18N_DOMAIN', 'kunta_api_services');

require_once( __DIR__ . '/activator.php');
require_once( __DIR__ . '/settings.php');
require_once( __DIR__ . '/twig-extension.php');
require_once( __DIR__ . '/service-updater.php');
require_once( __DIR__ . '/services/service-content-processor.php');
require_once( __DIR__ . '/service-channels/service-channel-renderer.php');
require_once( __DIR__ . '/tinymce.php');
require_once( __DIR__ . '/ckeditor.php');
require_once( __DIR__ . '/service-search-ajax.php');
require_once( __DIR__ . '/service-channels/electronic-channel-content-processor.php');
require_once( __DIR__ . '/service-channels/phone-channel-content-processor.php');
require_once( __DIR__ . '/service-channels/printable-form-channel-content-processor.php');
require_once( __DIR__ . '/service-channels/service-location-channel-content-processor.php');
require_once( __DIR__ . '/service-channels/webpage-channel-content-processor.php');

?>