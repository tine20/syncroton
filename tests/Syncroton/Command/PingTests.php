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
class Syncroton_Command_PingTests extends Syncroton_Command_ATestCase
{
    #protected $_logPriority = Zend_Log::DEBUG;
    
    /**
     * 
     */
    public function testPingWithNoSyncRunningBefore()
    {
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Ping xmlns="uri:Ping"><HeartbeatInterval>10</HeartbeatInterval><Folders><Folder><Id>14</Id><Class>Contacts</Class></Folder></Folders></Ping>'
        );
        
        $search = new Syncroton_Command_Ping($doc, $this->_device, null);
        
        $search->handle();
        
        $responseDoc = $search->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('Ping', 'uri:Ping');
        
        $nodes = $xpath->query('//Ping:Ping/Ping:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Ping::STATUS_FOLDER_NOT_FOUND, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
    }
    
    /**
     * 
     */
    public function testPingContacts()
    {
        // first do a foldersync
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <FolderSync xmlns="uri:FolderHierarchy"><SyncKey>0</SyncKey></FolderSync>'
        );
        $folderSync = new Syncroton_Command_FolderSync($doc, $this->_device, $this->_device->policykey);
        $folderSync->handle();
        $folderSync->getResponse();
        
        
        // request initial synckey
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Sync xmlns="uri:AirSync" xmlns:AirSyncBase="uri:AirSyncBase"><Collections><Collection><Class>Contacts</Class><SyncKey>0</SyncKey><CollectionId>addressbookFolderId</CollectionId><DeletesAsMoves/><GetChanges/><WindowSize>100</WindowSize><Options><AirSyncBase:BodyPreference><AirSyncBase:Type>1</AirSyncBase:Type><AirSyncBase:TruncationSize>5120</AirSyncBase:TruncationSize></AirSyncBase:BodyPreference><Conflict>1</Conflict></Options></Collection></Collections></Sync>'
        );
        $sync = new Syncroton_Command_Sync($doc, $this->_device, $this->_device->policykey);
        $sync->handle();
        $syncDoc = $sync->getResponse();
        #$syncDoc->formatOutput = true; echo $syncDoc->saveXML();
        
        
        // now do the first sync
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Sync xmlns="uri:AirSync" xmlns:AirSyncBase="uri:AirSyncBase"><Collections><Collection><Class>Contacts</Class><SyncKey>1</SyncKey><CollectionId>addressbookFolderId</CollectionId><DeletesAsMoves/><GetChanges/><WindowSize>100</WindowSize><Options><AirSyncBase:BodyPreference><AirSyncBase:Type>1</AirSyncBase:Type><AirSyncBase:TruncationSize>5120</AirSyncBase:TruncationSize></AirSyncBase:BodyPreference><Conflict>1</Conflict></Options></Collection></Collections></Sync>'
        );
        $sync = new Syncroton_Command_Sync($doc, $this->_device, $this->_device->policykey);
        $sync->handle();
        $syncDoc = $sync->getResponse();
        #$syncDoc->formatOutput = true; echo $syncDoc->saveXML();

        $folder    = Syncroton_Registry::getFolderBackend()->getFolder($this->_device, 'addressbookFolderId');
        
        $oneSecondAgo = new DateTime('now', new DateTimeZone('UTC'));
        $oneSecondAgo->modify('-1 second');
        $tenSecondsAgo = new DateTime('now', new DateTimeZone('UTC'));
        $tenSecondsAgo->modify('-10 second');
        
        // update modify timeStamp of contact
        $dataController = Syncroton_Data_Factory::factory(
            Syncroton_Data_Factory::CLASS_CONTACTS,
            $this->_device,
            $oneSecondAgo
        );
        $contact = $dataController->getEntry(
            new Syncroton_Model_SyncCollection(array('folder' => 'addressbookFolderId')), 
            'contact1'
        );
        $dataController->updateEntry('addressbookFolderId', 'contact1', $contact);
        
        // turn back last sync time
        $syncState = Syncroton_Registry::getSyncStateBackend()->getSyncState($this->_device, $folder);
        $syncState->lastsync = $tenSecondsAgo;
        $syncState = Syncroton_Registry::getSyncStateBackend()->update($syncState);
        
        
        
        
        // and now we can start the ping request
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Ping xmlns="uri:Ping"><HeartbeatInterval>10</HeartbeatInterval><Folders><Folder><Id>addressbookFolderId</Id><Class>Contacts</Class></Folder></Folders></Ping>'
        );
        
        $search = new Syncroton_Command_Ping($doc, $this->_device, null);
        
        $search->handle();
        
        $responseDoc = $search->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('Ping', 'uri:Ping');
        
        $nodes = $xpath->query('//Ping:Ping/Ping:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Ping::STATUS_CHANGES_FOUND, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//Ping:Ping/Ping:Folders/Ping:Folder');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals('addressbookFolderId', $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
    }
    
    /**
     * 
     */
    public function testPingWithIntervalToGreat()
    {
        // first do a foldersync
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <FolderSync xmlns="uri:FolderHierarchy"><SyncKey>0</SyncKey></FolderSync>'
        );
        $folderSync = new Syncroton_Command_FolderSync($doc, $this->_device, $this->_device->policykey);
        $folderSync->handle();
        $folderSync->getResponse();
        
        
        // request initial synckey
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Sync xmlns="uri:AirSync" xmlns:AirSyncBase="uri:AirSyncBase"><Collections><Collection><Class>Contacts</Class><SyncKey>0</SyncKey><CollectionId>addressbookFolderId</CollectionId><DeletesAsMoves/><GetChanges/><WindowSize>100</WindowSize><Options><AirSyncBase:BodyPreference><AirSyncBase:Type>1</AirSyncBase:Type><AirSyncBase:TruncationSize>5120</AirSyncBase:TruncationSize></AirSyncBase:BodyPreference><Conflict>1</Conflict></Options></Collection></Collections></Sync>'
        );
        $sync = new Syncroton_Command_Sync($doc, $this->_device, $this->_device->policykey);
        $sync->handle();
        $syncDoc = $sync->getResponse();
        #$syncDoc->formatOutput = true; echo $syncDoc->saveXML();
        
        
        // now do the first sync
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Sync xmlns="uri:AirSync" xmlns:AirSyncBase="uri:AirSyncBase"><Collections><Collection><Class>Contacts</Class><SyncKey>1</SyncKey><CollectionId>addressbookFolderId</CollectionId><DeletesAsMoves/><GetChanges/><WindowSize>100</WindowSize><Options><AirSyncBase:BodyPreference><AirSyncBase:Type>1</AirSyncBase:Type><AirSyncBase:TruncationSize>5120</AirSyncBase:TruncationSize></AirSyncBase:BodyPreference><Conflict>1</Conflict></Options></Collection></Collections></Sync>'
        );
        $sync = new Syncroton_Command_Sync($doc, $this->_device, $this->_device->policykey);
        $sync->handle();
        $syncDoc = $sync->getResponse();
        #$syncDoc->formatOutput = true; echo $syncDoc->saveXML();

        // and now we can start the ping request
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Ping xmlns="uri:Ping"><HeartbeatInterval>3541</HeartbeatInterval><Folders><Folder><Id>addressbookFolderId</Id><Class>Contacts</Class></Folder></Folders></Ping>'
        );
        
        $search = new Syncroton_Command_Ping($doc, $this->_device, null);
        
        $search->handle();
        
        $responseDoc = $search->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('Ping', 'uri:Ping');
        
        $nodes = $xpath->query('//Ping:Ping/Ping:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Ping::STATUS_INTERVAL_TO_GREAT_OR_SMALL, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
        $nodes = $xpath->query('//Ping:Ping/Ping:HeartbeatInterval');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Ping::MAX_PING_INTERVAL, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
        
    }
    
    /**
     * test status code for empty folder list 
     */
    public function testPingWithNoFoldersDefined()
    {
        // first do a foldersync
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <FolderSync xmlns="uri:FolderHierarchy"><SyncKey>0</SyncKey></FolderSync>'
        );
        $folderSync = new Syncroton_Command_FolderSync($doc, $this->_device, $this->_device->policykey);
        $folderSync->handle();
        $folderSync->getResponse();
        
        
        // request initial synckey
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Sync xmlns="uri:AirSync" xmlns:AirSyncBase="uri:AirSyncBase"><Collections><Collection><Class>Contacts</Class><SyncKey>0</SyncKey><CollectionId>addressbookFolderId</CollectionId><DeletesAsMoves/><GetChanges/><WindowSize>100</WindowSize><Options><AirSyncBase:BodyPreference><AirSyncBase:Type>1</AirSyncBase:Type><AirSyncBase:TruncationSize>5120</AirSyncBase:TruncationSize></AirSyncBase:BodyPreference><Conflict>1</Conflict></Options></Collection></Collections></Sync>'
        );
        $sync = new Syncroton_Command_Sync($doc, $this->_device, $this->_device->policykey);
        $sync->handle();
        $syncDoc = $sync->getResponse();
        #$syncDoc->formatOutput = true; echo $syncDoc->saveXML();
        
        
        // and now we can start the ping request
        $doc = new DOMDocument();
        $doc->loadXML('<?xml version="1.0" encoding="utf-8"?>
            <!DOCTYPE AirSync PUBLIC "-//AIRSYNC//DTD AirSync//EN" "http://www.microsoft.com/">
            <Ping xmlns="uri:Ping"><HeartbeatInterval>10</HeartbeatInterval></Ping>'
        );
        $ping = new Syncroton_Command_Ping($doc, $this->_device, null);
        $ping->handle();
        $responseDoc = $ping->getResponse();
        #$responseDoc->formatOutput = true; echo $responseDoc->saveXML();
        
        $xpath = new DomXPath($responseDoc);
        $xpath->registerNamespace('Ping', 'uri:Ping');
        
        $nodes = $xpath->query('//Ping:Ping/Ping:Status');
        $this->assertEquals(1, $nodes->length, $responseDoc->saveXML());
        $this->assertEquals(Syncroton_Command_Ping::STATUS_MISSING_PARAMETERS, $nodes->item(0)->nodeValue, $responseDoc->saveXML());
    }    
}
