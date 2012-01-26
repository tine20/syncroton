<?php

/**
 * Syncope
 *
 * @package     Model
 * @license     http://www.tine20.org/licenses/agpl-nonus.txt AGPL Version 1 (Non-US)
 *              NOTE: According to sec. 8 of the AFFERO GENERAL PUBLIC LICENSE (AGPL),
 *              Version 1, the distribution of the Tine 2.0 Syncope module in or to the
 *              United States of America is excluded from the scope of this license.
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Sync command
 *
 * @package     Model
 */

interface Syncope_Data_IData
{
    public function appendXML(DOMElement $_domParrent, $_collectionData, $_serverId);
    
    public function createEntry($_folderId, SimpleXMLElement $_entry);
    
    public function deleteEntry($_folderId, $_serverId);
    
    public function getAllFolders();
    
    public function getChangedEntries($_folderId, DateTime $_startTimeStamp, DateTime $_endTimeStamp = NULL);
    
    public function hasChanges(Syncope_Model_IFolder $folder, Syncope_Model_ISyncState $syncState);
    
    public function updateEntry($_folderId, $_serverId, SimpleXMLElement $_entry);
}

