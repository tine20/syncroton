<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Backend
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Sync command
 *
 * @package     Syncroton
 * @subpackage  Backend
 */
class Syncroton_Backend_Device extends Syncroton_Backend_ABackend implements Syncroton_Backend_IDevice
{
    protected $_tableName = 'device';
    
    protected $_modelClassName = 'Syncroton_Model_Device';
    
    protected $_modelInterfaceName = 'Syncroton_Model_IDevice';
    
    /**
     * return device for this user
     * 
     * @param  string  $userId
     * @param  string  $deviceId
     * @throws Syncroton_Exception_NotFound
     * @return Syncroton_Model_Device
     */
    public function getUserDevice($ownerId, $deviceId)
    {
        $select = $this->_db->select()
            ->from($this->_tablePrefix . $this->_tableName)
            ->where('owner_id = ?', $ownerId)
            ->where('deviceid = ?', $deviceId);
        
        $stmt = $this->_db->query($select);
        $data = $stmt->fetch();
        
        if ($data === false) {
            throw new Syncroton_Exception_NotFound('id not found');
        }

        foreach ($data as $key => $value) {
            unset($data[$key]);
            $data[$this->_toCamelCase($key, false)] = $value;
        }
        
        $model = new $this->_modelClassName($data);
        
        return $model;
    }

    /**
     * Returns list of user accounts
     *
     * @param Syncroton_Model_Device $device The device
     *
     * @return array List of Syncroton_Model_Account objects
     */
    public function userAccounts($device)
    {
        return array();
    }

    /**
     * Returns OOF information
     *
     * @param array $request Oof/Get request data
     *
     * @return Syncroton_Model_Oof Response object or NULL if OOF is not supported
     * @throws Syncroton_Exception_Status
     */
    public function getOOF($request)
    {
        return null; // not implemented
    }

    /**
     * Sets OOF information
     *
     * @param Syncroton_Model_Oof $request Request object
     *
     * @throws Syncroton_Exception_Status
     */
    public function setOOF($request)
    {
        // not implemented
    }
}
