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
require_once( __DIR__ . '/service-content-processor.php');
require_once( __DIR__ . '/service-channel-mapper.php');
require_once( __DIR__ . '/electronic-channel-content-processor.php');
require_once( __DIR__ . '/service-channel-renderer.php');
require_once( __DIR__ . '/phone-channel-content-processor.php');
require_once( __DIR__ . '/printable-form-channel-content-processor.php');
require_once( __DIR__ . '/service-location-channel-content-processor.php');
require_once( __DIR__ . '/webpage-channel-content-processor.php');
require_once( __DIR__ . '/tinymce.php');
require_once( __DIR__ . '/ckeditor.php');
require_once( __DIR__ . '/service-search-ajax.php');
?>