<?php
/*
 * Created on Oct 21, 2016
 * Plugin Name: Kunta API Services
 * Description: Wordpress plugin for Kunta API services integration
 * Version: 0.1
 * Author: Antti Leppä / Otavan Opisto
 */

defined ( 'ABSPATH' ) || die ( 'No script kiddies please!' );

require_once( __DIR__ . '/activator.php');
require_once( __DIR__ . '/service-updater.php');
require_once( __DIR__ . '/service-content-processor.php');

?>