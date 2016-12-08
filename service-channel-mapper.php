<?php
  namespace KuntaAPI\Services;
  	
  defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
  
  require_once( __DIR__ . '/vendor/autoload.php');
  
  if (!class_exists( 'KuntaAPI\Services\ServiceChannelMapper' ) ) {
    class ServiceChannelMapper {
      
      private static function mapServiceChannelAttachment($serviceChannelAttachment) {
        $results = [];
        $results['type'] = $serviceChannelAttachment->getType();
        $results['name'] = $serviceChannelAttachment->getName();
        $results['description'] = $serviceChannelAttachment->getDescription();
        $results['url'] = $serviceChannelAttachment->getUrl();
        return $results;
      }
      
      private static function mapWebPage($webPage) {
        $results = [];
        $results['type'] = $webPage->getType();
        $results['value'] = $webPage->getValue();
        $results['description'] = $webPage->getDescription();
        $results['url'] = $webPage->getUrl();
        return $results;
      }
      
      private static function mapServiceHour($serviceHour) {
        $results = [];
        $results['validFrom'] = $serviceHour->getValidFrom();
        $results['validTo'] = $serviceHour->getValidTo();
        $results['opens'] = $serviceHour->getOpens();
        $results['closes'] = $serviceHour->getCloses();
        $results['days'] = $serviceHour->getDays();
        $results['status'] = $serviceHour->getStatus();
        return $results;
      }
      
      private static function mapSupportContact($supportContact) {
        $results = [];
        $results['email'] = $supportContact->getEmail();
        $results['phone'] = $supportContact->getPhone();
        $results['phoneChargeDescription'] = $supportContact->getPhoneChargeDescription();
        $results['serviceChargeTypes'] = $supportContact->getServiceChargeTypes();
        return $results;
      }
      
      private static function mapAddress($address, $lang) {
        $results = [];
        $results['type'] = $address->getType();
        $results['postOfficeBox'] = $address->getPostOfficeBox();
        $results['postalCode'] = $address->getPostalCode();
        $results['postOffice'] = $address->getPostOffice();
        $streetAddress =  $address->getStreetAddress();
        if(isset($streetAddress[$lang])) {
          $results['streetAddress'] = $streetAddress[$lang]->getValue();
        }
        $results['municipality'] = $address->getMunicipality();
        $results['country'] = $address->getCountry();
        $results['qualifier'] = $address->getQualifier();
        $additionalInformation = $address->getAdditionalInformations();
        if(isset($additionalInformation[$lang])) {
          $results['additionalInformations'] = $additionalInformation[$lang]->getValue();
        }
        return $results;
      }
      
      public static function mapElectronicChannel($serviceId, $electronicChannel) {
        
        $result = [];

        foreach (\KuntaAPI\Core\QTranslateHelper::getEnabledLanguages() as $lang) {
          $result[$lang] = [
            'attachments' => [],
            'serviceHours' => [],
            'webpages' => []
          ];
        }
        
        if(!isset($electronicChannel)) {
          error_log("service id $serviceId attempted to map null electronic channel");
          return $result;
        }
        
        foreach ($electronicChannel->getNames() as $electronicChannelName) {
      	  $result[$electronicChannelName->getLanguage()]['name'] = $electronicChannelName->getValue();
      	}
        
        foreach ($electronicChannel->getDescriptions() as $phoneChannelDescription) {
      	  $result[$phoneChannelDescription->getLanguage()]['description'] = $phoneChannelDescription->getValue();
      	}
        
        foreach ($electronicChannel->getUrls() as $electronicChannelUrl) {
      	  $result[$electronicChannelUrl->getLanguage()]['url'] = $electronicChannelUrl->getValue();
      	}
        
        foreach ($electronicChannel->getAttachments() as $serviceChannelAttachment) {
          $result[$serviceChannelAttachment->getLanguage()]['attachments'][] = self::mapServiceChannelAttachment($serviceChannelAttachment);
      	}
        
        foreach ($electronicChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::mapWebPage($serviceChannelWebPage);
      	}
        
        $serviceHours = [];
        foreach ($electronicChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::mapServiceHour($serviceHour);
        }
        
        foreach ($result as $lang => $value) {
          $result[$lang]['serviceId'] = $serviceId;
          $result[$lang]['serviceHours'] = $serviceHours;
          $result[$lang]['requiresAuthentication'] = $electronicChannel->getRequiresAuthentication();
          $result[$lang]['requiresSignature'] = $electronicChannel->getRequiresSignature();
          $result[$lang]['serviceChannelId'] = $electronicChannel->getId();
        }

        return $result;
      }
      
      public static function mapPhoneChannel($serviceId, $phoneChannel) {
        $result = [];

        foreach (\KuntaAPI\Core\QTranslateHelper::getEnabledLanguages() as $lang) {
          $result[$lang] = [
            'phoneNumbers' => [],
            'phoneChargeDescriptions' => [],
            'webpages' => [],
            'supportContacts' => []
          ];
        }        

        if(!isset($phoneChannel)) {
          error_log("service id $serviceId attempted to map null phoneChannel");
          return $result;
        }

        foreach ($phoneChannel->getNames() as $phoneChannelName) {
      	  $result[$phoneChannelName->getLanguage()]['name'] = $phoneChannelName->getValue();
      	}
        
        foreach ($phoneChannel->getDescriptions() as $phoneChannelDescription) {
      	  $result[$phoneChannelDescription->getLanguage()]['description'] = $phoneChannelDescription->getValue();
      	}
        
        foreach ($phoneChannel->getPhoneNumbers() as $phoneChannelPhoneNumber) {
      	  $result[$phoneChannelPhoneNumber->getLanguage()]['phoneNumbers'][] = $phoneChannelPhoneNumber->getValue();
      	}
        
        foreach ($phoneChannel->getPhoneChargeDescriptions() as $phoneChannelPhoneChargeDescription) {
      	  $result[$phoneChannelPhoneChargeDescription->getLanguage()]['phoneChargeDescriptions'][] = $phoneChannelPhoneChargeDescription->getValue();
      	}
        
        foreach ($phoneChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::mapWebPage($serviceChannelWebPage);
      	}
        
        foreach ($phoneChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::mapSupportContact($serviceSupportContact);
      	}
        
        
        $serviceHours = [];
        foreach ($phoneChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::mapServiceHour($serviceHour);
        }
        
        foreach ($result as $lang => $value) {
          $result[$lang]['serviceId'] = $serviceId;
          $result[$lang]['serviceHours'] = $serviceHours;
          $result[$lang]['serviceChannelId'] = $phoneChannel->getId();
          $result[$lang]['phoneType'] = $phoneChannel->getPhoneType();
          $result[$lang]['languages'] = $phoneChannel->getLanguages();
          $result[$lang]['chargeTypes'] = $phoneChannel->getChargeTypes();
        }
        
        return $result;
      }
      
      public static function mapPrintableFormChannel($serviceId, $printableFormChannel) {
        $result = [];

        foreach (\KuntaAPI\Core\QTranslateHelper::getEnabledLanguages() as $lang) {
          $result[$lang] = [
            'channelUrls' => [],
            'attachments' => [],
            'webpages' => [],
            'supportContacts' => []
          ];
        } 
        
        if(!isset($printableFormChannel)) {
          error_log("service id $serviceId attempted to map null printableFormChannel");
          return $result;
        }
        
        foreach ($printableFormChannel->getNames() as $printableFormChannelName) {
      	  $result[$printableFormChannelName->getLanguage()]['name'] = $printableFormChannelName->getValue();
      	}
        
        foreach ($printableFormChannel->getDescriptions() as $printableFormChannelDescription) {
      	  $result[$printableFormChannelDescription->getLanguage()]['description'] = $printableFormChannelDescription->getValue();
      	}
        
        foreach ($printableFormChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::mapSupportContact($serviceSupportContact);
      	}
        
        foreach ($printableFormChannel->getChannelUrls() as $serviceChannelUrl) {
          $result[$serviceChannelUrl->getLanguage()]['channelUrls'][] = $serviceChannelUrl->getValue();
      	}
        
        foreach ($printableFormChannel->getDeliveryAddressDescriptions() as $serviceChannelDeliveryAddressDescription) {
          $result[$serviceChannelDeliveryAddressDescription->getLanguage()]['deliveryAddressDescription'] = $serviceChannelDeliveryAddressDescription->getValue();
      	}
        
        foreach ($printableFormChannel->getAttachments() as $serviceChannelAttachment) {
          $result[$serviceChannelAttachment->getLanguage()]['attachments'][] = self::mapServiceChannelAttachment($serviceChannelAttachment);
      	}
        
        foreach ($printableFormChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::mapWebPage($serviceChannelWebPage);
      	}
        
        $printableFormChannelDeliveryAddress = $printableFormChannel->getDeliveryAddress();
        if(isset($printableFormChannelDeliveryAddress)) {
          foreach ($printableFormChannel->getDeliveryAddress()->getStreetAddress() as $serviceChannelAddressAddress) {
            $serviceChannelAddressLang = $serviceChannelAddressAddress->getLanguage();
            $result[$serviceChannelAddressLang]['deliveryAddress'] = self::mapAddress($printableFormChannel->getDeliveryAddress(), $serviceChannelAddressLang);
          }
        }
        
        $serviceHours = [];
        foreach ($printableFormChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::mapServiceHour($serviceHour);
        }
        
        foreach ($result as $lang => $value) {
          $result[$lang]['serviceId'] = $serviceId;
          $result[$lang]['serviceHours'] = $serviceHours;
          $result[$lang]['serviceChannelId'] = $printableFormChannel->getId();
          $result[$lang]['type'] = $printableFormChannel->getType();
          $result[$lang]['languages'] = $printableFormChannel->getLanguages();
          $result[$lang]['formIdentifier'] = $printableFormChannel->getFormIdentifier();
          $result[$lang]['formReceiver'] = $printableFormChannel->getFormReceiver();
        }
        
        return $result;
      }
      
      public static function mapServiceLocationChannel($serviceId, $serviceLocationChannel) {
        $result = [];

        foreach (\KuntaAPI\Core\QTranslateHelper::getEnabledLanguages() as $lang) {
          $result[$lang] = [
            'addresses' => [],
            'phoneChargeDescriptions' => [],
            'webpages' => [],
            'supportContacts' => []
          ];
        } 

        if(!isset($serviceLocationChannel)) {
          error_log("service id $serviceId attempted to map null serviceLocationChannel");
          return $result;
        }

        foreach ($serviceLocationChannel->getNames() as $serviceLocationChannelName) {
      	  $result[$serviceLocationChannelName->getLanguage()]['name'] = $serviceLocationChannelName->getValue();
      	}
        
        foreach ($serviceLocationChannel->getDescriptions() as $serviceLocationChannelDescription) {
      	  $result[$serviceLocationChannelDescription->getLanguage()]['description'] = $serviceLocationChannelDescription->getValue();
        }
        
        foreach ($serviceLocationChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::mapSupportContact($serviceSupportContact);
      	}
        
        foreach ($serviceLocationChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::mapWebPage($serviceChannelWebPage);
      	}
        
        foreach ($serviceLocationChannel->getPhoneChargeDescriptions() as $serviceLocationChannelPhoneChargeDescription) {
      	  $result[$serviceLocationChannelPhoneChargeDescription->getLanguage()]['phoneChargeDescriptions'][] = $serviceLocationChannelPhoneChargeDescription->getValue();
      	}

        foreach ($serviceLocationChannel->getAddresses() as $serviceChannelAddress) {
          foreach ($serviceChannelAddress->getStreetAddress() as $serviceChannelAddressAddress) {
            $serviceChannelAddressLang = $serviceChannelAddressAddress->getLanguage();
            $result[$serviceChannelAddressLang]['addresses'][] = self::mapAddress($serviceChannelAddress, $serviceChannelAddressLang);
          }
        }
        
        $serviceHours = [];
        foreach ($serviceLocationChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::mapServiceHour($serviceHour);
        }
        
        foreach ($result as $lang => $value) {
          $result[$lang]['serviceId'] = $serviceId;
          $result[$lang]['serviceHours'] = $serviceHours;
          $result[$lang]['serviceChannelId'] = $serviceLocationChannel->getId();
          $result[$lang]['type'] = $serviceLocationChannel->getType();
          $result[$lang]['languages'] = $serviceLocationChannel->getLanguages();
          $result[$lang]['email'] = $serviceLocationChannel->getEmail();
          $result[$lang]['phone'] = $serviceLocationChannel->getPhone();
          $result[$lang]['languages'] = $serviceLocationChannel->getLanguages();
          $result[$lang]['fax'] = $serviceLocationChannel->getFax();
          $result[$lang]['latitude'] = $serviceLocationChannel->getLatitude();
          $result[$lang]['longitude'] = $serviceLocationChannel->getLongitude();
          $result[$lang]['coordinateSystem'] = $serviceLocationChannel->getCoordinateSystem();
          $result[$lang]['coordinatesSetManually'] = $serviceLocationChannel->getCoordinatesSetManually();
          $result[$lang]['phoneServiceCharge'] = $serviceLocationChannel->getPhoneServiceCharge();
          $result[$lang]['serviceAreas'] = $serviceLocationChannel->getServiceAreas();
          $result[$lang]['chargeTypes'] = $serviceLocationChannel->getChargeTypes();
        }

        return $result;
      }
      
      public static function mapWebPageChannel($serviceId, $webPageChannel) {
        $result = [];

        foreach (\KuntaAPI\Core\QTranslateHelper::getEnabledLanguages() as $lang) {
          $result[$lang] = [
            'attachments' => [],
            'supportContacts' => [],
            'webpages' => []
          ];
        } 

        if(!isset($webPageChannel)) {
          error_log("service id $serviceId attempted to map null webPageChannel");
          return $result;
        }

        foreach ($webPageChannel->getNames() as $webPageChannelName) {
      	  $result[$webPageChannelName->getLanguage()]['name'] = $webPageChannelName->getValue();
      	}
        
        foreach ($webPageChannel->getDescriptions() as $webPageChannelDescription) {
      	  $result[$webPageChannelDescription->getLanguage()]['description'] = $webPageChannelDescription->getValue();
        }
        
        foreach ($webPageChannel->getUrls() as $webPageChannelUrl) {
      	  $result[$webPageChannelUrl->getLanguage()]['url'] = $webPageChannelUrl->getValue();
      	}
        
        foreach ($webPageChannel->getAttachments() as $serviceChannelAttachment) {
          $result[$serviceChannelAttachment->getLanguage()]['attachments'][] = self::mapServiceChannelAttachment($serviceChannelAttachment);
      	}
        
        foreach ($webPageChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::mapSupportContact($serviceSupportContact);
      	}
        
        foreach ($webPageChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::mapWebPage($serviceChannelWebPage);
      	}
        
        $serviceHours = [];
        foreach ($webPageChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::mapServiceHour($serviceHour);
        }
        
        foreach ($result as $lang => $value) {
          $result[$lang]['serviceId'] = $serviceId;
          $result[$lang]['serviceHours'] = $serviceHours;
          $result[$lang]['serviceChannelId'] = $webPageChannel->getId();
          $result[$lang]['type'] = $webPageChannel->getType();
          $result[$lang]['languages'] = $webPageChannel->getLanguages();
        }
        
        return $result;
      }
    }  
  }
?>