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
class Syncroton_Command_FolderSyncTests extends Syncroton_Command_ATestCase
{
    #protected $_logPriority = Zend_Log::DEBUG;
    
    /**
     * test reponse with synckey 0
     */
    public function testGetFoldersSyncKey0()
    {
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <FolderSync xmlns="uri:FolderHierarchy"><SyncKey>0</SyncKey></FolderSync>'
        );
        
        $folderSync = new Syncroton_Command_FolderSync($doc, $this->_device, null);
        
        $folderSync->handle();
        
        $responseDoc = $folderSync->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('FolderHierarchy', 'uri:FolderHierarchy');
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_FolderSync::STATUS_SUCCESS, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:SyncKey');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(1, $nodes->item(0)->nodeValue, $responseDoc->saveXML());

        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Changes/FolderHierarchy:Add');
        $this->assertGreaterThanOrEqual(1, $nodes->length, $responseDoc->saveXML());
        
        $outputStream = fopen("php://temp", 'r+');
        
        $encoder = new Syncroton_Wbxml_Encoder($outputStream, 'UTF-8', 3);
        $encoder->encode($responseDoc);
    }
    
    /**
     * test reponse with synckey 1
     */
    public function testGetFoldersSyncKey1()
    {
        $this->testGetFoldersSyncKey0();
        
        // rewind back last sync timestamp
        $syncState = Syncroton_Registry::getSyncStateBackend()->getSyncState($this->_device, 'FolderSync');
        $syncState->lastsync = new DateTime('2013-02-09 10:40:36', new DateTimeZone('UTC'));
        $syncState = Syncroton_Registry::getSyncStateBackend()->update($syncState);
        
        $createdFolder = Syncroton_Data_Factory::factory(Syncroton_Data_Factory::CLASS_CONTACTS, $this->_device, new DateTime('now', new DateTimeZone('UTC')))->createFolder(
            new Syncroton_Model_Folder(array(
                'serverId'    => 'addressbookFolderId2',
                'parentId'    => null,
                'displayName' => 'User created Contacts Folder',
                'type'        => Syncroton_Command_FolderSync::FOLDERTYPE_CONTACT_USER_CREATED
            ))
        );
        
        // update created folder to validate that created folders are not returned as changes folders
        Syncroton_Data_Factory::factory(Syncroton_Data_Factory::CLASS_CONTACTS, $this->_device, new DateTime('now', new DateTimeZone('UTC')))->updateFolder(
            $createdFolder
        );
        
        Syncroton_Data_Factory::factory(Syncroton_Data_Factory::CLASS_CONTACTS, $this->_device, new DateTime('now', new DateTimeZone('UTC')))->updateFolder(
            new Syncroton_Model_Folder(array(
                'serverId'    => 'anotherAddressbookFolderId',
                'parentId'    => null,
                'displayName' => 'User updated Contacts Folder',
                'type'        => Syncroton_Command_FolderSync::FOLDERTYPE_CONTACT_USER_CREATED
            ))
        );
        
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <FolderSync xmlns="uri:FolderHierarchy"><SyncKey>1</SyncKey></FolderSync>'
        );
    
        $folderSync = new Syncroton_Command_FolderSync($doc, $this->_device, $this->_device->policykey);
    
        $folderSync->handle();
        
        $responseDoc = $folderSync->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('FolderHierarchy', 'uri:FolderHierarchy');
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_FolderSync::STATUS_SUCCESS, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:SyncKey');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(2, $nodes->item(0)->nodeValue, $responseDoc->saveXML());

        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Changes/FolderHierarchy:Add');
        $this->assertGreaterThanOrEqual(1, $nodes->length, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Changes/FolderHierarchy:Add/FolderHierarchy:DisplayName');
        $this->assertGreaterThanOrEqual('User created Contacts Folder', $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Changes/FolderHierarchy:Update');
        $this->assertGreaterThanOrEqual(1, $nodes->length, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Changes/FolderHierarchy:Update/FolderHierarchy:DisplayName');
        $this->assertGreaterThanOrEqual('User updated Contacts Folder', $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
    }
    
    /**
     * test reponse with invalid synckey
     */
    public function testGetFoldersInvalidSyncKey()
    {
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <FolderSync xmlns="uri:FolderHierarchy"><SyncKey>99</SyncKey></FolderSync>'
        );
        
        $folderSync = new Syncroton_Command_FolderSync($doc, $this->_device, $this->_device->policykey);
        
        $folderSync->handle();
        
        $responseDoc = $folderSync->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('FolderHierarchy', 'uri:FolderHierarchy');
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync/FolderHierarchy:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_FolderSync::STATUS_INVALID_SYNC_KEY, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//FolderHierarchy:FolderSync');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());

    }
    
    /**
     * this test should throw no error if the foldersync got restarted after an invalid synckey
     */
    public function testFolderSyncAfterInvalidSyncKey()
    {
        $this->testGetFoldersSyncKey0();
        $clientFolders1 = Syncroton_Registry::getFolderBackend()->getFolderState($this->_device, 'Contacts');
        $testFolderIds = array_keys($clientFolders1);
        
        $this->testGetFoldersInvalidSyncKey();
        
        $this->testGetFoldersSyncKey0();
        $clientFolders2 = Syncroton_Registry::getFolderBackend()->getFolderState($this->_device, 'Contacts');
        
        $this->assertEquals($clientFolders1[$testFolderIds[0]], $clientFolders2[$testFolderIds[0]]);
    }
}
