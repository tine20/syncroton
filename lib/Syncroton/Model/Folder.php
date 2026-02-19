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
    protected $_xmlBaseElement = ['FolderUpdate', 'FolderCreate'];
    
    protected $_properties = [
        'FolderHierarchy' => [
            'parentId'     => ['type' => 'string'],
            'serverId'     => ['type' => 'string'],
            'displayName'  => ['type' => 'string'],
            'type'         => ['type' => 'number']
        ],
        'Internal' => [
            'id'             => ['type' => 'string'],
            'deviceId'       => ['type' => 'string'],
            'ownerId'        => ['type' => 'string'],
            'class'          => ['type' => 'string'],
            'creationTime'   => ['type' => 'datetime'],
            'lastfiltertype' => ['type' => 'number']
        ],
    ];
}
