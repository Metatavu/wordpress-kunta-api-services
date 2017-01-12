<?php
    
 if (!defined('ABSPATH')) { 
    exit;
  }
  
  function kuntaApiServicesActivate($file) {
    if(!file_exists("$file/vendor/autoload.php")) {
      exec("COMPOSER_HOME=/tmp/composer composer install -d $file");
    }
  }

  kuntaApiServicesActivate(__DIR__);
?>
