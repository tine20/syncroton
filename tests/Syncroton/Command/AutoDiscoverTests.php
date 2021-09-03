<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2013-2013 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to test Syncroton_Command_AutoDiscover
 *
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Command_AutoDiscoverTests extends \PHPUnit\Framework\TestCase
{
    /**
     * test creation of calendar folder
     */
    public function testCreateCalendarFolder()
    {
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <Autodiscover xmlns="http://schemas.microsoft.com/exchange/autodiscover/mobilesync/requestschema/2006">
                <Request>
                    <EMailAddress>chris@example.com</EMailAddress>
                    <AcceptableResponseSchema>
                        http://schemas.microsoft.com/exchange/autodiscover/mobilesync/responseschema/2006
                    </AcceptableResponseSchema>
                </Request>
            </Autodiscover>
        ');
        
        $autoDiscover = new Syncroton_Command_AutoDiscover($doc);
        $autoDiscover->mobileSyncUrl = 'https://sync.example.com/Microsoft-Server-ActiveSync';
        $autoDiscover->certEnrollUrl = 'https://sync.example.com/CertEnroll';

        $autoDiscover->handle();
        
        $responseDoc = $autoDiscover->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('ms2006', 'http://schemas.microsoft.com/exchange/autodiscover/mobilesync/requestschema/2006');
        
        $nodes = $xpath->query('//ms2006:Autodiscover/Response/User');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//ms2006:Autodiscover/Response/Action');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//ms2006:Autodiscover/Response/Action/Settings');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//ms2006:Autodiscover/Response/Action/Settings/Server');
        $this->assertEquals(2, $nodes->length, $responseDoc->saveXML());
    }
}
