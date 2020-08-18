<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Command
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2008-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Ping command
 *
 * @package     Syncroton
 * @subpackage  Command
 */
class Syncroton_Command_Ping extends Syncroton_Command_Wbxml 
{
    const STATUS_NO_CHANGES_FOUND           = 1;
    const STATUS_CHANGES_FOUND              = 2;
    const STATUS_MISSING_PARAMETERS         = 3;
    const STATUS_REQUEST_FORMAT_ERROR       = 4;
    const STATUS_INTERVAL_TO_GREAT_OR_SMALL = 5;
    const STATUS_TOO_MANY_FOLDERS           = 6;
    const STATUS_FOLDER_NOT_FOUND           = 7;
    const STATUS_GENERAL_ERROR              = 8;
    
    const MAX_PING_INTERVAL                 = 3540; // 59 minutes limit defined in Activesync protocol spec. 
    
    protected $_skipValidatePolicyKey = true;
    
    protected $_changesDetected = false;
    
    /**
     * @var Syncroton_Backend_StandAlone_Abstract
     */
    protected $_dataBackend;

    protected $_defaultNameSpace = 'uri:Ping';
    protected $_documentElement  = 'Ping';
    
    protected $_foldersWithChanges = array();
    
    /**
     * process the XML file and add, change, delete or fetches data 
     *
     * @todo can we get rid of LIBXML_NOWARNING
     * @todo we need to stored the initial data for folders and lifetime as the phone is sending them only when they change
     * @return resource
     */
    public function handle()
    {
        $intervalStart = time();
        $status = self::STATUS_NO_CHANGES_FOUND;
        
        // the client does not send a wbxml document, if the Ping parameters did not change compared with the last request
        if ($this->_requestBody instanceof DOMDocument) {
            $xml = simplexml_import_dom($this->_requestBody);
            $xml->registerXPathNamespace('Ping', 'Ping');

            if(isset($xml->HeartbeatInterval)) {
                $this->_device->pinglifetime = (int)$xml->HeartbeatInterval;
            }
            
            if (isset($xml->Folders->Folder)) {
                $maxCollections = Syncroton_Registry::getMaxCollections();
                if ($maxCollections && count($xml->Folders->Folder) > $maxCollections) {
                    $ping = $this->_outputDom->documentElement;
                    $ping->appendChild($this->_outputDom->createElementNS('uri:Ping', 'Status', self::STATUS_TOO_MANY_FOLDERS));
                    $ping->appendChild($this->_outputDom->createElementNS('uri:Ping', 'MaxFolders', $maxCollections));
                    return;
                }

                $folders = array();
                foreach ($xml->Folders->Folder as $folderXml) {
                    try {
                        // does the folder exist?
                        $folder = $this->_folderBackend->getFolder($this->_device, (string)$folderXml->Id);
                        
                        $folders[$folder->id] = $folder;
                    } catch (Syncroton_Exception_NotFound $senf) {
                        if ($this->_logger instanceof Zend_Log) 
                            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " " . $senf->getMessage());
                        $status = self::STATUS_FOLDER_NOT_FOUND;
                        break;
                    }
                }
                $this->_device->pingfolder = serialize(array_keys($folders));
            }
        }
        
        $this->_device->lastping = new DateTime('now', new DateTimeZone('utc'));

        if ($status == self::STATUS_NO_CHANGES_FOUND) {
            $this->_device = $this->_deviceBackend->update($this->_device);
        }
        
        $lifeTime    = $this->_device->pinglifetime;
        $maxInterval = Syncroton_Registry::getPingInterval();

        if ($maxInterval <= 0 || $maxInterval > Syncroton_Server::MAX_HEARTBEAT_INTERVAL) {
            $maxInterval = Syncroton_Server::MAX_HEARTBEAT_INTERVAL;
        }

        if ($lifeTime > $maxInterval) {
            $ping = $this->_outputDom->documentElement;
            $ping->appendChild($this->_outputDom->createElementNS('uri:Ping', 'Status', self::STATUS_INTERVAL_TO_GREAT_OR_SMALL));
            $ping->appendChild($this->_outputDom->createElementNS('uri:Ping', 'HeartbeatInterval', $maxInterval));
            return;
        }
        
        $intervalEnd = $intervalStart + $lifeTime;
        $secondsLeft = $intervalEnd;
        
        $folders = unserialize($this->_device->pingfolder);
        
        if ($status === self::STATUS_NO_CHANGES_FOUND && (!is_array($folders) || count($folders) == 0)) {
            $status = self::STATUS_MISSING_PARAMETERS;
        }
        
        if ($this->_logger instanceof Zend_Log) 
            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " Folders to monitor($lifeTime / $intervalStart / $intervalEnd / $status): " . print_r($folders, true));
        
        if ($status === self::STATUS_NO_CHANGES_FOUND) {
            $sleepCallback  = Syncroton_Registry::getSleepCallback();
            $wakeupCallback = Syncroton_Registry::getWakeupCallback();

            do {
                // take a break to save battery lifetime
                call_user_func($sleepCallback);
                sleep(Syncroton_Registry::getPingTimeout());

                // make sure the connection is still alive, abort otherwise
                if (connection_aborted()) {
                    if ($this->_logger instanceof Zend_Log)
                        $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " Exiting on aborted connection");
                    exit;
                }

                // reconnect external connections, etc.
                call_user_func($wakeupCallback);

                // Calculate secondsLeft before any loop break just to have a correct value
                // for logging purposes in case we breaked from the loop early
                $secondsLeft = $intervalEnd - time();

                try {
                    $device = $this->_deviceBackend->get($this->_device->id);
                } catch (Syncroton_Exception_NotFound $e) {
                    if ($this->_logger instanceof Zend_Log)
                        $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " " . $e->getMessage());

                    $status = self::STATUS_FOLDER_NOT_FOUND;
                    break;
                } catch (Exception $e) {
                    if ($this->_logger instanceof Zend_Log)
                        $this->_logger->err(__METHOD__ . '::' . __LINE__ . " " . $e->getMessage());

                    // do nothing, maybe temporal issue, should we stop?
                    continue;
                }

                // if another Ping command updated lastping property, we can stop processing this Ping command request
                if ((isset($device->lastping) && $device->lastping instanceof DateTime) &&
                    $device->pingfolder === $this->_device->pingfolder &&
                    $device->lastping->getTimestamp() > $this->_device->lastping->getTimestamp() ) {
                    break;
                }

                // If folders hierarchy changed, break the loop and ask the client for FolderSync
                try {
                    if ($this->_folderBackend->hasHierarchyChanges($this->_device)) {
                        if ($this->_logger instanceof Zend_Log)
                            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . ' Detected changes in folders hierarchy');

                        $status = self::STATUS_FOLDER_NOT_FOUND;
                        break;
                    }
                } catch (Exception $e) {
                    if ($this->_logger instanceof Zend_Log)
                        $this->_logger->err(__METHOD__ . '::' . __LINE__ . " " . $e->getMessage());

                    // do nothing, maybe temporal issue, should we stop?
                    continue;
                }

                $now = new DateTime('now', new DateTimeZone('utc'));
                
                foreach ($folders as $folderId) {
                    try {
                        $folder         = $this->_folderBackend->get($folderId);
                        $dataController = Syncroton_Data_Factory::factory($folder->class, $this->_device, $this->_syncTimeStamp);
                        
                    } catch (Syncroton_Exception_NotFound $e) {
                        if ($this->_logger instanceof Zend_Log)
                            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " " . $e->getMessage());
                        $status = self::STATUS_FOLDER_NOT_FOUND;
                        
                        break;
                        
                    } catch (Exception $e) {
                        if ($this->_logger instanceof Zend_Log)
                            $this->_logger->err(__METHOD__ . '::' . __LINE__ . " " . $e->getMessage());
                        
                        // do nothing, maybe temporal issue, should we stop?
                        continue;
                    }

                    try {
                        $syncState = $this->_syncStateBackend->getSyncState($this->_device, $folder);
                        
                        // another process synchronized data of this folder already. let's skip it
                        if ($syncState->lastsync > $this->_syncTimeStamp) {
                            continue;
                        }
                        
                        // safe battery time by skipping folders which got synchronied less than Syncroton_Registry::getQuietTime() seconds ago
                        if (($now->getTimestamp() - $syncState->lastsync->getTimestamp()) < Syncroton_Registry::getQuietTime()) {
                            continue;
                        }
                        
                        $foundChanges = $dataController->hasChanges($this->_contentStateBackend, $folder, $syncState);
                        
                    } catch (Syncroton_Exception_NotFound $e) {
                        // folder got never synchronized to client
                        if ($this->_logger instanceof Zend_Log) 
                            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " " . $e->getMessage());
                        if ($this->_logger instanceof Zend_Log) 
                            $this->_logger->info(__METHOD__ . '::' . __LINE__ . ' syncstate not found. enforce sync for folder: ' . $folder->serverId);
                        
                        $foundChanges = true;
                    }
                    
                    if ($foundChanges == true) {
                        $this->_foldersWithChanges[] = $folder;
                        $status = self::STATUS_CHANGES_FOUND;
                    }
                }
                
                if ($status != self::STATUS_NO_CHANGES_FOUND) {
                    break;
                }

                // Update secondsLeft (again)
                $secondsLeft = $intervalEnd - time();
                
                if ($this->_logger instanceof Zend_Log)
                    $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " DeviceId: " . $this->_device->deviceid . " seconds left: " . $secondsLeft);
            
            // See: http://www.tine20.org/forum/viewtopic.php?f=12&t=12146
            //
            // break if there are less than PingTimeout + 10 seconds left for the next loop
            // otherwise the response will be returned after the client has finished his Ping
            // request already maybe
            } while (Syncroton_Server::validateSession() && $secondsLeft > (Syncroton_Registry::getPingTimeout() + 10));
        }
        
        if ($this->_logger instanceof Zend_Log) 
            $this->_logger->info(__METHOD__ . '::' . __LINE__ . " DeviceId: " . $this->_device->deviceid . " Lifetime: $lifeTime SecondsLeft: $secondsLeft Status: $status)");
        
        $ping = $this->_outputDom->documentElement;
        $ping->appendChild($this->_outputDom->createElementNS('uri:Ping', 'Status', $status));
        if($status === self::STATUS_CHANGES_FOUND) {
            $folders = $ping->appendChild($this->_outputDom->createElementNS('uri:Ping', 'Folders'));
            
            foreach($this->_foldersWithChanges as $changedFolder) {
                $folder = $folders->appendChild($this->_outputDom->createElementNS('uri:Ping', 'Folder', $changedFolder->serverId));
                if ($this->_logger instanceof Zend_Log) 
                    $this->_logger->info(__METHOD__ . '::' . __LINE__ . " DeviceId: " . $this->_device->deviceid . " changes in folder: " . $changedFolder->serverId);
            }
        }
    }
        
    /**
     * generate ping command response
     *
     */
    public function getResponse()
    {
        return $this->_outputDom;
    }
}
