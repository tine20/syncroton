<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2009-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * class to handle ActiveSync Sync command
 *
 * @package     Syncroton
 * @subpackage  Model
 */
class Syncroton_Model_Folder extends Syncroton_Model_AXMLEntry implements Syncroton_Model_IFolder
{
    protected $_xmlBaseElement = array('FolderUpdate', 'FolderCreate');
    
    protected $_properties = array(
        'FolderHierarchy' => array(
            'parentId'     => array('type' => 'string'),
            'serverId'     => array('type' => 'string'),
            'displayName'  => array('type' => 'string'),
            'type'         => array('type' => 'number')
        ),
        'Internal' => array(
            'id'             => array('type' => 'string'),
            'deviceId'       => array('type' => 'string'),
            'ownerId'        => array('type' => 'string'),
            'class'          => array('type' => 'string'),
            'creationTime'   => array('type' => 'datetime'),
            'lastfiltertype' => array('type' => 'number')
        ),
    );

    /**
     * list of plugin classes
     * @var array
     */
    protected static $plugins = array();

    /**
     *
     * @param array $plugin name of a plugin class
     */
    public static function addPlugin($plugin)
    {
        self::$plugins[] = $plugin;
    }

    /**
     *
     * @param string $properties
     */
    public function __construct($properties = NULL)
    {
        // plugins can change model properties
        foreach(self::$plugins as $plugin){
            $plugin = new $plugin(get_object_vars($this));
            $changedProperties = $plugin->getChangedProperties();
            $this->_properties = $changedProperties['_properties'];
        }
        parent::__construct($properties);
    }
}