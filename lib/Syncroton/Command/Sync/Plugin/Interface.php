<?php
/**
 * Syncroton
 *
 * @package     Custom
 * @subpackage  Syncroton
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Flávio Gomes da Silva Lisboa <flavio.lisboa@serpro.gov.br>
 *
 */

/**
 * interface for Syncroton_Command_Sync plugins
 *
 * @package     Syncroton
 * @subpackage  Command
 */
interface Syncroton_Command_Sync_Plugin_Interface
{
    /**
     *
     * @param Syncroton_Backend_IFolder $folderBackend
     * @param Syncroton_Model_SyncCollection    $collectionData
     */
    public function applyCustomsForCollectionData(Syncroton_Backend_IFolder $folderBackend, Syncroton_Model_SyncCollection $collectionData);

    /**
     * @param ActiveSync_Controller_Email    $dataController
     * @param Syncroton_Model_IFolder        $folder
    */
    public function applyCustomUpdateForImapStatus(ActiveSync_Controller_Email $dataController, Syncroton_Model_IFolder $folder);

    /**
     * @param unknown                         $dataController
     * @param Syncroton_Model_SyncCollection  $collectionData
     * @param array                           $allClientEntries
     * @param Syncroton_Backend_IFolder       $folderBackend
     * @param Datetime                        $syncTimeStamp
     */
    public function fetchEntriesChangedSinceLastSync($dataController, Syncroton_Model_SyncCollection $collectionData, $allClientEntries, Syncroton_Backend_IFolder $folderBackend, DateTime $syncTimeStamp);
}