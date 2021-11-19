<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Backend
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * 
 */

/**
 * sql backend class for the folder state
 *
 * @package     Syncroton
 * @subpackage  Backend
 */
class Syncroton_Backend_SyncState extends Syncroton_Backend_ABackend implements Syncroton_Backend_ISyncState
{
    protected $_tableName = 'synckey';
    
    protected $_modelClassName = 'Syncroton_Model_SyncState';

    protected $_modelInterfaceName = 'Syncroton_Model_ISyncState';

public $_logger;

    public function delete($id)
    {
        if ($this->_logger instanceof Zend_Log)
            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " deleting sync state " . $id);
        return parent::delete($id);
    }

    /**
     * (non-PHPdoc)
     * @see Syncroton_Backend_ISyncState::create()
     */
    public function create($model, $keepPreviousSyncState = true)
    {
        $state = parent::create($model);
        
        if ($keepPreviousSyncState !== true) {
            // remove all other synckeys
            $this->_deleteOtherStates($state);
        }
        
        return $state;
    }
    
    /**
     * (non-PHPdoc)
     * @see Syncroton_Backend_ABackend::_convertModelToArray()
     */
    protected function _convertModelToArray($model)
    {
        $model = parent::_convertModelToArray($model);
        
        $model['pendingdata'] = isset($model['pendingdata']) && is_array($model['pendingdata']) ? Zend_Json::encode($model['pendingdata']) : null;
        
        return $model;
    }
    
    /**
     * 
     * @param Syncroton_Model_ISyncState $state
     */
    protected function _deleteOtherStates(Syncroton_Model_ISyncState $state)
    {
        if ($this->_logger instanceof Zend_Log)
            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " deleting sync states of type " . $state->type .
                " and counter != " . $state->counter);

        // remove all other synckeys
        $where = array(
            'device_id = ?' => $state->deviceId,
            'type = ?'      => $state->type,
            'counter != ?'  => $state->counter
        );
    
        $this->_db->delete($this->_tablePrefix . $this->_tableName, $where);
    
        return true;
    
    }
    
    /**
     * (non-PHPdoc)
     * @see Syncroton_Backend_ABackend::_getObject()
     */
    protected function _getObject($data)
    {
        $model = parent::_getObject($data);
        
        if ($model->pendingdata !== NULL) {
            $model->pendingdata = Zend_Json::decode($model->pendingdata);
        }
        
        return $model;
    }
    
    /**
     * (non-PHPdoc)
     * @see Syncroton_Backend_ISyncState::getSyncState()
     */
    public function getSyncState($deviceId, $folderId)
    {
        $deviceId = $deviceId instanceof Syncroton_Model_IDevice ? $deviceId->id : $deviceId;
        $folderId = $folderId instanceof Syncroton_Model_IFolder ? $folderId->id : $folderId;
    
        $select = $this->_db->select()
            ->from($this->_tablePrefix . $this->_tableName)
            ->where($this->_db->quoteIdentifier('device_id') . ' = ?', $deviceId)
            ->where($this->_db->quoteIdentifier('type')      . ' = ?', $folderId)
            ->order('counter DESC')
            ->limit(1);
        
        $stmt = $this->_db->query($select);
        $data = $stmt->fetch();
        $stmt = null; # see https://bugs.php.net/bug.php?id=44081
        
        if ($data === false) {
            throw new Syncroton_Exception_NotFound('id not found');
        }
        
        return $this->_getObject($data);
    }
    
    /**
     * delete all stored synckeys for given type
     *
     * @param  Syncroton_Model_IDevice|string  $deviceId
     * @param  Syncroton_Model_IFolder|string  $folderId
     */
    public function resetState($deviceId, $folderId)
    {
        $deviceId = $deviceId instanceof Syncroton_Model_IDevice ? $deviceId->id : $deviceId;
        $folderId = $folderId instanceof Syncroton_Model_IFolder ? $folderId->id : $folderId;

        if ($this->_logger instanceof Zend_Log)
            $this->_logger->debug(__METHOD__ . '::' . __LINE__ . " deleting sync states of type " . $folderId);

        $where = array(
            $this->_db->quoteInto($this->_db->quoteIdentifier('device_id') . ' = ?', $deviceId),
            $this->_db->quoteInto($this->_db->quoteIdentifier('type') . ' = ?',      $folderId)
        );
    
        $this->_db->delete($this->_tablePrefix . $this->_tableName, $where);
    }
    
    /**
     * get array of ids which got send to the client for a given class
     *
     * @param  Syncroton_Model_IDevice|string  $deviceId
     * @param  Syncroton_Model_IFolder|string  $folderId
     * @return Syncroton_Model_SyncState
     */
    public function validate($deviceId, $folderId, $syncKey)
    {
        $deviceId = $deviceId instanceof Syncroton_Model_IDevice ? $deviceId->id : $deviceId;
        $folderId = $folderId instanceof Syncroton_Model_IFolder ? $folderId->id : $folderId;
        
        $select = $this->_db->select()
            ->from($this->_tablePrefix . $this->_tableName)
            ->where($this->_db->quoteIdentifier('device_id') . ' = ?', $deviceId)
            ->where($this->_db->quoteIdentifier('counter')   . ' = ?', $syncKey)
            ->where($this->_db->quoteIdentifier('type')      . ' = ?', $folderId);
        
        $stmt = $this->_db->query($select);
        $data = $stmt->fetch();
        $stmt = null; # see https://bugs.php.net/bug.php?id=44081
        
        if ($data === false) {
            return false;
        }

        $state = $this->_getObject($data);
        
        // check if this was the latest syncKey
        $select = $this->_db->select()
            ->from($this->_tablePrefix . $this->_tableName)
            ->where($this->_db->quoteIdentifier('device_id') . ' = ?', $deviceId)
            ->where($this->_db->quoteIdentifier('counter')   . ' = ?', $syncKey + 1)
            ->where($this->_db->quoteIdentifier('type')      . ' = ?', $folderId);
        
        $stmt = $this->_db->query($select);
        $moreRecentStateData = $stmt->fetch();
        $stmt = null; # see https://bugs.php.net/bug.php?id=44081
        $isUnittest = Syncroton_Registry::getInstance()->offsetExists(Syncroton_Registry::IS_UNITTEST);
        
        // found more recent synckey => the last sync repsone got not received by the client
        if ($moreRecentStateData !== false) {
            // undelete entries marked as deleted in Syncroton_content table
            $this->_db->update($this->_tablePrefix . 'content', array(
                'is_deleted'  => 0,
            ), array(
                'device_id = ?'        => $deviceId,
                'folder_id = ?'        => $folderId,
                'creation_synckey = ?' => $state->counter,
                'is_deleted = ?'       => 1
            ));
            
        } else {
            // finally delete all entries marked for removal in Syncroton_content table
            if (!$isUnittest) {
                // this is very susceptible to deadlocks, lets get the hammer out and bash away
                $this->_db->query('SET autocommit=0');
                $this->_db->query('LOCK TABLES ' . $this->_tablePrefix . 'content WRITE');
            }
            $this->_db->delete($this->_tablePrefix . 'content', array(
                'device_id = ?'  => $deviceId,
                'folder_id = ?'  => $folderId,
                'is_deleted = ?' => 1
            ));
            if (!$isUnittest) {
                $this->_db->query('COMMIT');
                $this->_db->query('UNLOCK TABLES');
            }
        }
        
        // remove all other synckeys
        $this->_deleteOtherStates($state);
        
        // remove entries from Syncroton_content table with an creation_synckey bigger than current one
        if (!$isUnittest) {
            // this is very susceptible to deadlocks, lets get the hammer out and bash away
            $this->_db->query('SET autocommit=0');
            $this->_db->query('LOCK TABLES ' . $this->_tablePrefix . 'content WRITE');
        }
        $this->_db->delete($this->_tablePrefix . 'content', array(
            'device_id = ?'        => $deviceId,
            'folder_id = ?'        => $folderId,
            'creation_synckey > ?' => $state->counter,
        ));
        if (!$isUnittest) {
            $this->_db->query('COMMIT');
            $this->_db->query('UNLOCK TABLES');
            $this->_db->query('SET autocommit=1');
        }
        
        return $state;
    }
}
