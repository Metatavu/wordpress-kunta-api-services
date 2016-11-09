<?php
  namespace KuntaAPI\Services;
  
  use KuntaAPI\Model\LocalizedValue;
		
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\ServiceChannelMapper' ) ) {
    class ServiceChannelMapper {
      
      private static function renderServiceChannelAttachment($serviceChannelAttachment) {
        $results = [];
        $results['type'] = $serviceChannelAttachment->getType();
        $results['name'] = $serviceChannelAttachment->getName();
        $results['description'] = $serviceChannelAttachment->getDescription();
        $results['url'] = $serviceChannelAttachment->getUrl();
        return $results;
      }
      
      private static function renderWebPage($webPage) {
        $results = [];
        $results['type'] = $webPage->getType();
        $results['value'] = $webPage->getValue();
        $results['description'] = $webPage->getDescription();
        $results['url'] = $webPage->getUrl();
        return $results;
      }
      
      private static function renderServiceHour($serviceHour) {
        $results = [];
        $results['validFrom'] = $serviceHours->getValidFrom();
        $results['validTo'] = $serviceHours->getValidTo();
        $results['opens'] = $serviceHours->getOpens();
        $results['closes'] = $serviceHours->getCloses();
        $results['days'] = $serviceHours->getDays();
        $results['status'] = $serviceHours->getStatus();
        return $results;
      }
      
      public static function renderElectronicChannel($serviceId, $electronicChannel) {
        $result = [
          'fi' => [
            'attachments' => [],
            'serviceHours' => [],
            'webpages' => []
          ],
          'en' => [
            'attachments' => [],
            'serviceHours' => [],
            'webpages' => []
          ]
        ];
        
        foreach ($electronicChannel->getNames() as $electronicChannelName) {
      	  $result[$electronicChannelName->getLanguage()]['name'] = $electronicChannelName->getValue();
      	}
        
        foreach ($electronicChannel->getDescriptions() as $electronicDescription) {
      	  $result[$electronicDescription->getLanguage()]['description'] = $electronicDescription->getValue();
      	}
        
        foreach ($electronicChannel->getUrls() as $electronicChannelUrl) {
      	  $result[$electronicChannelUrl->getLanguage()]['url'] = $electronicChannelUrl->getValue();
      	}
        
        foreach ($electronicChannel->getAttachments() as $serviceChannelAttachment) {
          $result[$serviceChannelAttachment->getLanguage()]['attachments'][] = self::renderServiceChannelAttachment($serviceChannelAttachment);
      	}
        
        foreach ($electronicChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::renderWebPage($serviceChannelWebPage);
      	}
        
        $serviceHours = [];
        foreach ($electronicChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::renderServiceHour($serviceHour);
        }
        
        foreach ($result as $lang => $value) {
          $result[$lang]['serviceId'] = $service['id'];
          $result[$lang]['serviceHours'] = $serviceHours;
          $result[$lang]['requiresAuthentication'] = $electronicChannel->getRequiresAuthentication();
          $result[$lang]['requiresSignature'] = $electronicChannel->getRequiresSignature();
          $result[$lang]['serviceChannelId'] = $electronicChannel->getId();
        }

        return $result;
      }
      
      public static function renderPhoneChannel($serviceId, $phoneChannel) {
        $result = [
          'fi' => [],
          'en' => []
        ];
        
        return $result;
      }
      
      public static function renderPrintableFormChannel($serviceId, $printableFormChannel) {
        $result = [
          'fi' => [],
          'en' => []
        ];
        
        return $result;
      }
      
      public static function renderServiceLocationChannel($serviceId, $serviceLocationChannel) {
        $result = [
          'fi' => [],
          'en' => []
        ];
        
        return $result;
      }
      
      public static function renderWebPageChannel($serviceId, $webPageChannel) {
        $result = [
          'fi' => [],
          'en' => []
        ];
        
        return $result;
      }
    }  
  }
?>