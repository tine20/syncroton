<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Tests
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * Test class for Syncroton_Wbxml_Encoder
 * 
 * @package     Syncroton
 * @subpackage  Tests
 */
class Syncroton_Wbxml_EncoderTests extends \PHPUnit\Framework\TestCase
{
    protected $_xmlContactBirthdayWithoutTimeAndroid = '<?xml version="1.0" encoding="utf-8"?>
        <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
        <Sync xmlns="uri:AirSync" xmlns:AirSyncBase="uri:AirSyncBase" xmlns:Contacts="uri:Contacts" xmlns:Email2="uri:Email2">
            <Collections>
                <Collection>
                    <Class>Contacts</Class>
                    <SyncKey>3</SyncKey>
                    <CollectionId>addressbook-root</CollectionId>
                    <DeletesAsMoves/>
                    <WindowSize>100</WindowSize>
                    <Options><AirSyncBase:BodyPreference><AirSyncBase:Type>1</AirSyncBase:Type><AirSyncBase:TruncationSize>5120</AirSyncBase:TruncationSize></AirSyncBase:BodyPreference><Conflict>1</Conflict></Options>
                    <Commands>
                        <Add>
                            <ClientId>4600</ClientId>
                            <ApplicationData>
                                <Contacts:FileAs>Fritzchen</Contacts:FileAs><Contacts:FirstName>Fritzchen</Contacts:FirstName><Contacts:LastName>Meinen</Contacts:LastName><Contacts:Birthday>1969-12-31</Contacts:Birthday><AirSyncBase:Body><AirSyncBase:Type>1</AirSyncBase:Type><AirSyncBase:EstimatedDataSize>0</AirSyncBase:EstimatedDataSize><AirSyncBase:Data></AirSyncBase:Data></AirSyncBase:Body><Contacts:Categories/><Contacts:Picture/>
                                <Email2:ConversationId encoding="opaque">CD4F18CF13></Email2:ConversationId>
                            </ApplicationData>
                        </Add>
                    </Commands>
                </Collection>
            </Collections>
        </Sync>
    ';
    
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        $suite  = new PHPUnit\Framework\TestSuite('Syncroton wbxml encoder tests');
        PHPUnit_TextUI_TestRunner::run($suite);
    }
    
    /**
     * 
     */
    public function testEncode()
    {
        $testDoc = new DOMDocument();
        $testDoc->loadXML($this->_xmlContactBirthdayWithoutTimeAndroid);
        
        $testDoc->formatOutput = true;
        
        $outputStream = fopen("php://temp", 'r+');
        
        $encoder = new Syncroton_Wbxml_Encoder($outputStream, 'UTF-8', 3);
        $encoder->encode($testDoc);
        
        $this->assertEquals(183, ftell($outputStream));
    }

    public function testEncodeBrokenXML()
    {
        // Creates an instance of the DOMImplementation class
        $imp = new DOMImplementation();

        // Creates a DOMDocumentType instance
        $dtd = $imp->createDocumentType('AirSync', "-//AIRSYNC//DTD AirSync//EN", "http://www.microsoft.com/");

        // Creates a DOMDocument instance
        $testDoc = $imp->createDocument('uri:AirSync', 'Sync', $dtd);
        $testDoc->documentElement->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:Syncroton', 'uri:Syncroton');
        $testDoc->formatOutput = false;
        $testDoc->encoding     = 'utf-8';

        // creates a syncroton record
        $record = new Syncroton_Model_Contact(array(
            'Contacts' => array(
                // chr(0x1F) is not a valid XML char!
                'firstName' => 'foo' . chr(0x20).chr(0x1F) . 'a',
            )
        ));

        $device = $this->_getDevice();
        $record->appendXML($testDoc->getElementsByTagName('Sync')->item(0), $device);

        $testDoc->formatOutput = true;

        $outputStream = fopen("php://temp", 'r+');

        $encoder = new Syncroton_Wbxml_Encoder($outputStream, 'UTF-8', 3);
        $encoder->encode($testDoc);
    }

    /**
     * TODO move to a generic place (see \Syncroton_Command_ATestCase::setUp)
     *
     * @return Syncroton_Model_IDevice
     */
    protected function _getDevice()
    {
        try {
            $device = Syncroton_Registry::getDeviceBackend()->getUserDevice('1234', 'iphone-abcd');
            Syncroton_Registry::getDeviceBackend()->delete($device);
        } catch (Syncroton_Exception_NotFound $e) {
            // do nothing => it's ok
        }
        $device = Syncroton_Registry::getDeviceBackend()->create(
            Syncroton_Backend_DeviceTests::getTestDevice()
        );

        return $device;
    }
}
