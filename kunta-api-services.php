<?php
/*
 * Created on Oct 21, 2016
 * Plugin Name: Kunta API Services
 * Description: Wordpress plugin for Kunta API services integration
 * Version: 0.1
 * Author: Antti Leppä / Otavan Opisto
 */

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

require_once( __DIR__ . '/service-updater.php');
require_once( __DIR__ . '/service-content-processor.php');
require_once( __DIR__ . '/service-channel-mapper.php');
require_once( __DIR__ . '/electronic-channel-content-processor.php');
require_once( __DIR__ . '/service-channel-renderer.php');
require_once( __DIR__ . '/phone-channel-content-processor.php');
require_once( __DIR__ . '/printable-form-channel-content-processor.php');
require_once( __DIR__ . '/service-location-channel-content-processor.php');
require_once( __DIR__ . '/webpage-channel-content-processor.php');
?>