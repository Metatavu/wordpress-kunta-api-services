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
        $results['validFrom'] = $serviceHour->getValidFrom();
        $results['validTo'] = $serviceHour->getValidTo();
        $results['opens'] = $serviceHour->getOpens();
        $results['closes'] = $serviceHour->getCloses();
        $results['days'] = $serviceHour->getDays();
        $results['status'] = $serviceHour->getStatus();
        return $results;
      }
      
      private static function renderSupportContact($supportContact) {
        $results = [];
        $results['email'] = $supportContact->getEmail();
        $results['phone'] = $supportContact->getPhone();
        $results['phoneChargeDescription'] = $supportContact->getPhoneChargeDescription();
        $results['serviceChargeTypes'] = $supportContact->getServiceChargeTypes();
        return $results;
      }
      
      private static function renderAddress($address, $lang) {
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
        
        foreach ($electronicChannel->getDescriptions() as $phoneChannelDescription) {
      	  $result[$phoneChannelDescription->getLanguage()]['description'] = $phoneChannelDescription->getValue();
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
          $result[$lang]['serviceId'] = $serviceId;
          $result[$lang]['serviceHours'] = $serviceHours;
          $result[$lang]['requiresAuthentication'] = $electronicChannel->getRequiresAuthentication();
          $result[$lang]['requiresSignature'] = $electronicChannel->getRequiresSignature();
          $result[$lang]['serviceChannelId'] = $electronicChannel->getId();
        }

        return $result;
      }
      
      public static function renderPhoneChannel($serviceId, $phoneChannel) {
        $result = [
          'fi' => [
            'phoneNumbers' => [],
            'phoneChargeDescriptions' => [],
            'webpages' => [],
            'supportContacts' => []
          ],
          'en' => [
            'phoneNumbers' => [],
            'phoneChargeDescriptions' => [],
            'webpages' => [],
            'supportContacts' => []
          ]
        ];
        
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
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::renderWebPage($serviceChannelWebPage);
      	}
        
        foreach ($phoneChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::renderSupportContact($serviceSupportContact);
      	}
        
        
        $serviceHours = [];
        foreach ($phoneChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::renderServiceHour($serviceHour);
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
      
      public static function renderPrintableFormChannel($serviceId, $printableFormChannel) {
        $result = [
          'fi' => [
            'channelUrls' => [],
            'attachments' => [],
            'webpages' => [],
            'supportContacts' => []
          ],
          'en' => [
            'channelUrls' => [],
            'attachments' => [],
            'webpages' => [],
            'supportContacts' => []
          ]
        ];
        
        foreach ($printableFormChannel->getNames() as $printableFormChannelName) {
      	  $result[$printableFormChannelName->getLanguage()]['name'] = $printableFormChannelName->getValue();
      	}
        
        foreach ($printableFormChannel->getDescriptions() as $printableFormChannelDescription) {
      	  $result[$printableFormChannelDescription->getLanguage()]['description'] = $printableFormChannelDescription->getValue();
      	}
        
        foreach ($printableFormChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::renderSupportContact($serviceSupportContact);
      	}
        
        foreach ($printableFormChannel->getChannelUrls() as $serviceChannelUrl) {
          $result[$serviceChannelUrl->getLanguage()]['channelUrls'][] = $serviceChannelUrl->getValue();
      	}
        
        foreach ($printableFormChannel->getDeliveryAddressDescriptions() as $serviceChannelDeliveryAddressDescription) {
          $result[$serviceChannelDeliveryAddressDescription->getLanguage()]['deliveryAddressDescription'] = $serviceChannelDeliveryAddressDescription->getValue();
      	}
        
        foreach ($printableFormChannel->getAttachments() as $serviceChannelAttachment) {
          $result[$serviceChannelAttachment->getLanguage()]['attachments'][] = self::renderServiceChannelAttachment($serviceChannelAttachment);
      	}
        
        foreach ($printableFormChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::renderWebPage($serviceChannelWebPage);
      	}
        
        $printableFormChannelDeliveryAddress = $printableFormChannel->getDeliveryAddress();
        if(isset($printableFormChannelDeliveryAddress)) {
          foreach ($printableFormChannel->getDeliveryAddress()->getStreetAddress() as $serviceChannelAddressAddress) {
            $serviceChannelAddressLang = $serviceChannelAddressAddress->getLanguage();
            $result[$serviceChannelAddressLang]['deliveryAddress'] = self::renderAddress($printableFormChannel->getDeliveryAddress(), $serviceChannelAddressLang);
          }
        }
        
        $serviceHours = [];
        foreach ($printableFormChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::renderServiceHour($serviceHour);
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
      
      public static function renderServiceLocationChannel($serviceId, $serviceLocationChannel) {
        $result = [
          'fi' => [
            'addresses' => [],
            'phoneChargeDescriptions' => [],
            'webpages' => [],
            'supportContacts' => []
          ],
          'en' => [
            'addresses' => [],
            'phoneChargeDescriptions' => [],
            'webpages' => [],
            'supportContacts' => []
          ]
        ];
        
        foreach ($serviceLocationChannel->getNames() as $serviceLocationChannelName) {
      	  $result[$serviceLocationChannelName->getLanguage()]['name'] = $serviceLocationChannelName->getValue();
      	}
        
        foreach ($serviceLocationChannel->getDescriptions() as $serviceLocationChannelDescription) {
      	  $result[$serviceLocationChannelDescription->getLanguage()]['description'] = $serviceLocationChannelDescription->getValue();
        }
        
        foreach ($serviceLocationChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::renderSupportContact($serviceSupportContact);
      	}
        
        foreach ($serviceLocationChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::renderWebPage($serviceChannelWebPage);
      	}
        
        foreach ($serviceLocationChannel->getPhoneChargeDescriptions() as $serviceLocationChannelPhoneChargeDescription) {
      	  $result[$serviceLocationChannelPhoneChargeDescription->getLanguage()]['phoneChargeDescriptions'][] = $serviceLocationChannelPhoneChargeDescription->getValue();
      	}

        foreach ($serviceLocationChannel->getAddresses() as $serviceChannelAddress) {
          foreach ($serviceChannelAddress->getStreetAddress() as $serviceChannelAddressAddress) {
            $serviceChannelAddressLang = $serviceChannelAddressAddress->getLanguage();
            $result[$serviceChannelAddressLang]['addresses'][] = self::renderAddress($serviceChannelAddress, $serviceChannelAddressLang);
          }
        }
        
        $serviceHours = [];
        foreach ($serviceLocationChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::renderServiceHour($serviceHour);
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
      
      public static function renderWebPageChannel($serviceId, $webPageChannel) {
        $result = [
          'fi' => [
            'attachments' => [],
            'supportContacts' => [],
            'webpages' => []
          ],
          'en' => [
            'attachments' => [],
            'supportContacts' => [],
            'webpages' => []
          ]
        ];
        
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
          $result[$serviceChannelAttachment->getLanguage()]['attachments'][] = self::renderServiceChannelAttachment($serviceChannelAttachment);
      	}
        
        foreach ($webPageChannel->getSupportContacts() as $serviceSupportContact) {
          $result[$serviceSupportContact->getLanguage()]['supportContacts'][] = self::renderSupportContact($serviceSupportContact);
      	}
        
        foreach ($webPageChannel->getWebPages() as $serviceChannelWebPage) {
          $result[$serviceChannelWebPage->getLanguage()]['webpages'][] = self::renderWebPage($serviceChannelWebPage);
      	}
        
        $serviceHours = [];
        foreach ($webPageChannel->getServiceHours() as $serviceHour) {
          $serviceHours[] = self::renderServiceHour($serviceHour);
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