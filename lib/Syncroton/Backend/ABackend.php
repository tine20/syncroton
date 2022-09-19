<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Backend
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Sync command
 *
 * @package     Syncroton
 * @subpackage  Backend
 */
abstract class Syncroton_Backend_ABackend implements Syncroton_Backend_IBackend
{
    /**
     * the database adapter
     * 
     * @var Zend_Db_Adapter_Abstract
     */
    protected $_db;
    
    protected $_tablePrefix;
    
    protected $_tableName;
    
    protected $_modelClassName;
    
    protected $_modelInterfaceName;
    
    /**
     * the constructor
     * 
     * @param  Zend_Db_Adapter_Abstract  $_db
     * @param  string                    $_tablePrefix
     */
    public function __construct(Zend_Db_Adapter_Abstract $_db, $_tablePrefix = 'Syncroton_')
    {
        $this->_db          = $_db;
        $this->_tablePrefix = $_tablePrefix;
    }

    /**
     * create new device
     * 
     * @param Syncroton_Model_AEntry $model
     * @return Syncroton_Model_AEntry
     */
    public function create($model)
    {
        if (! $model instanceof $this->_modelInterfaceName) {
            throw new InvalidArgumentException('$model must be instance of ' . $this->_modelInterfaceName);
        }
        
        $data = $this->_convertModelToArray($model);
        
        $data['id'] = sha1(mt_rand(). microtime());

        $this->_db->insert($this->_tablePrefix . $this->_tableName, $data);
        
        return $this->get($data['id']);
    }
    
    /**
     * convert iteratable object to array
     * 
     * @param  Syncroton_Model_AEntry   $model
     * @return array
     */
    protected function _convertModelToArray($model)
    {
        $data = array();
        
        foreach ($model as $key => $value) {
            if ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            } elseif (is_object($value) && isset($value->id)) {
                $value = $value->id;
            }
        
            $data[$this->_fromCamelCase($key)] = $value;
        }
        
        return $data;
    }
    
    /**
     * @param string  $_id
     * @throws Syncroton_Exception_NotFound
     * @return Syncroton_Model_IDevice
     */
    public function get($id)
    {
        $id = $id instanceof $this->_modelInterfaceName ? $id->id : $id;
        
        if (empty($id)) {
            throw new Syncroton_Exception_NotFound('id can not be empty');
        }
        
        $select = $this->_db->select()
            ->from($this->_tablePrefix . $this->_tableName)
            ->where('id = ?', $id);
            
        $stmt = $this->_db->query($select);
        $data = $stmt->fetch();
        $stmt = null; # see https://bugs.php.net/bug.php?id=44081
        
        if ($data === false) {
            throw new Syncroton_Exception_NotFound('id not found');
        }

        return $this->_getObject($data);
    }
    
    /**
     * convert array to object
     * 
     * @param  array  $data
     * @return object
     */
    protected function _getObject($data)
    {
        foreach ($data as $key => $value) {
            unset($data[$key]);
            
            if (!empty($value) && preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $value)) { # 2012-08-12 07:43:26
                $value = new DateTime($value, new DateTimeZone('UTC'));
            }
            
            $data[$this->_toCamelCase($key, false)] = $value;
        }
        
        return new $this->_modelClassName($data);
    }
    
    /**
     * (non-PHPdoc)
     * @see Syncroton_Backend_IBackend::delete()
     */
    public function delete($id)
    {
        $id = $id instanceof $this->_modelInterfaceName ? $id->id : $id;
        
        $result = $this->_db->delete($this->_tablePrefix . $this->_tableName, array('id = ?' => $id));
        
        return (bool) $result;
    }
    
    /**
     * (non-PHPdoc)
     * @see Syncroton_Backend_IBackend::update()
     */
    public function update($model)
    {
        if (! $model instanceof $this->_modelInterfaceName) {
            throw new InvalidArgumentException('$model must be instanace of ' . $this->_modelInterfaceName);
        }
        
        $data = $this->_convertModelToArray($model);
        
        $this->_db->update($this->_tablePrefix . $this->_tableName, $data, array(
            'id = ?' => $model->id
        ));
        
        return $this->get($model->id);
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
     * convert from camelCase to camel_case
     * @param  string  $string
     * @return string
     */
    protected function _fromCamelCase($string)
    {
        $string = lcfirst($string);
        
        return preg_replace_callback('/([A-Z])/', function ($string) {return '_' . strtolower($string[0]);}, $string);
    }
    
    /**
     * convert from camel_case to camelCase
     * 
     * @param  string $string
     * @param  bool   $ucFirst
     * @return string
     */
    protected function _toCamelCase($string, $ucFirst = true)
    {
        if ($ucFirst === true) {
            $string = ucfirst($string);
        }
        
        return preg_replace_callback('/_([a-z])/', function ($string) {return strtoupper($string[1]);}, $string);
    }
}
