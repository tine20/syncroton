<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Data
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Sync command
 *
 * @package     Syncroton
 * @subpackage  Data
 */
interface Syncroton_Data_IData
{
    /**
     * create new entry
     *
     * @param string                  $folderId
     * @param Syncroton_Model_IEntry  $entry
     * @param array                   $options
     *
     * @return string|Syncroton_Model_SyncResponse id of updated entry, or response data
     */
    public function createEntry($folderId, Syncroton_Model_IEntry $entry, $options = array());

    /**
     * create a new folder in backend
     *
     * @param Syncroton_Model_IFolder $folder
     * @return Syncroton_Model_IFolder
     */
    public function createFolder(Syncroton_Model_IFolder $folder);

    /**
     * delete entry in backend
     *
     * @param string $_folderId
     * @param string $_serverId
     * @param unknown_type $_collectionData
     *
     * @return null|Syncroton_Model_SyncResponse Response data or nothing
     */
    public function deleteEntry($_folderId, $_serverId, $_collectionData);

    /**
     * delete folder
     *
     * @param string $folderId
     */
    public function deleteFolder($folderId);

    /**
     * empty folder
     *
     * @param string $folderId
     * @param array  $options
     */
    public function emptyFolderContents($folderId, $options);

    /**
     * return list off all folders
     * @return array  of Syncroton_Model_IFolder
     */
    public function getAllFolders();

    public function getChangedEntries($folderId, DateTime $startTimeStamp, DateTime $endTimeStamp = NULL, $filterType = NULL);

    /**
     * retrieve folders which were modified since last sync
     *
     * @param DateTime $startTimeStamp
     * @param DateTime $endTimeStamp
     */
    public function getChangedFolders(DateTime $startTimeStamp, DateTime $endTimeStamp);

    public function getCountOfChanges(Syncroton_Backend_IContent $contentBackend, Syncroton_Model_IFolder $folder, Syncroton_Model_ISyncState $syncState);

    /**
     *
     * @param Syncroton_Model_SyncCollection $collection
     * @param string $serverId
     * @return Syncroton_Model_IEntry
     */
    public function getEntry(Syncroton_Model_SyncCollection $collection, $serverId);

    /**
     *
     * @param unknown_type $fileReference
     * @return Syncroton_Model_FileReference
     */
    public function getFileReference($fileReference);

    /**
     * return array of all id's stored in folder 
     *
     * @param  Syncroton_Model_IFolder|string  $folderId
     * @param  string                          $filter
     * @return array
     */
    public function getServerEntries($folderId, $filter);

    /**
     * return true if any data got modified in the backend
     *
     * @param  Syncroton_Backend_IContent  $contentBackend
     * @param  Syncroton_Model_IFolder     $folder
     * @param  Syncroton_Model_ISyncState  $syncState
     * @return bool
     */
    public function hasChanges(Syncroton_Backend_IContent $contentBackend, Syncroton_Model_IFolder $folder, Syncroton_Model_ISyncState $syncState);

    public function moveItem($srcFolderId, $serverId, $dstFolderId);

    /**
     * update existing entry
     *
     * @param  string                  $folderId
     * @param  string                  $serverId
     * @param  Syncroton_Model_IEntry  $entry
     * @param  array                   $options
     *
     * @return string|Syncroton_Model_SyncResponse id of updated entry, or response data
     */
    public function updateEntry($folderId, $serverId, Syncroton_Model_IEntry $entry, $options = array());

    public function updateFolder(Syncroton_Model_IFolder $folder);
}
