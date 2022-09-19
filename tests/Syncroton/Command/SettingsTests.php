<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2022 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to test <...>
 *
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Command_SettingsTests extends Syncroton_Command_ATestCase
{
    #protected $_logPriority = Zend_Log::DEBUG;

    
    /**
     * test xml generation for IPhone
     */
    public function testSetDeviceInformation()
    {
        // delete folder created above
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Settings xmlns="uri:Settings"><DeviceInformation><Set><Model>iPhone</Model><IMEI>086465r87697</IMEI></Set></DeviceInformation></Settings>'
        );
        $settings = new Syncroton_Command_Settings($doc, $this->_device, null);
        $settings->handle();
        $responseDoc = $settings->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('Settings', 'uri:Settings');
        
        $nodes = $xpath->query('//Settings:Settings/Settings:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Settings::STATUS_SUCCESS, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $updatedDevice = Syncroton_Registry::getDeviceBackend()->get($this->_device->id);
        
        $this->assertEquals('086465r87697', $updatedDevice->imei);
    }
    
    /**
     * test Oof get
     */
    public function testGetOof()
    {
        // delete folder created above
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Settings xmlns="uri:Settings"><Oof><Get><BodyType>Text</BodyType></Get></Oof></Settings>'
        );
        $settings = new Syncroton_Command_Settings($doc, $this->_device, null);
        $settings->handle();
        $responseDoc = $settings->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('Settings', 'uri:Settings');
        
        $nodes = $xpath->query('//Settings:Settings/Settings:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Settings::STATUS_SUCCESS, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
    }
    
    /**
     * test Oof set
     */
    public function testSetOof()
    {
        // delete folder created above
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Settings xmlns="uri:Settings">
                <Oof>
                    <Set>
                        <OofState>2</OofState>
                        <OofMessage>
                            <AppliesToInternal/>
                            <Enabled>1</Enabled>
                            <ReplyMessage> &lt;html&gt;&lt;head&gt;&lt;meta http-equiv="Content-Type" content="text/html; charset=utf-8"&gt;&lt;/head&gt; &lt;body lang="EN-US"&gt;I am out of the office&lt;/body&gt;&lt;/html&gt;</ReplyMessage>
                            <BodyType>HTML</BodyType>
                        </OofMessage>
                        <OofMessage>
                            <AppliesToExternalKnown/>
                        <Enabled>0</Enabled>
                        </OofMessage>
                        <OofMessage>
                            <AppliesToExternalUnknown/>
                            <Enabled>0</Enabled>
                        </OofMessage>
                    </Set>
                </Oof>
            </Settings>'
        );
        $settings = new Syncroton_Command_Settings($doc, $this->_device, null);
        $settings->handle();
        $responseDoc = $settings->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('Settings', 'uri:Settings');
        
        $nodes = $xpath->query('//Settings:Settings/Settings:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Settings::STATUS_SUCCESS, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//Settings:Settings/Settings:Oof/Settings:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Settings::STATUS_SUCCESS, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
    }
}
