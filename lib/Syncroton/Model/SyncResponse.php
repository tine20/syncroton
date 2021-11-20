<?php

/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 */

/**
 * Class to handle Responses::(Add|Change|Delete)
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property  string                   $class
 * @property  string                   $clientId
 * @property  Syncroton_Model_Content  $contentState
 * @property  string                   $instanceId
 * @property  string                   $serverId
 * @property  int                      $status
 * @property  Syncroton_Model_IEntry   $applicationData
 */
class Syncroton_Model_SyncResponse extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = array('Add', 'Change', 'Delete');

    protected $_properties = array(
        'AirSync' => array(
            'class'              => array('type' => 'string'),
            'clientId'           => array('type' => 'string'),
            'serverId'           => array('type' => 'string'),
            'status'             => array('type' => 'number'),
            'applicationData'    => array('type' => 'container', 'supportedSince' => '16.0'),
        ),
        'AirSyncBase' => array(
            'instanceId'         => array('type' => 'string', 'supportedSince' => '16.0'),
        ),
        'internal' => array(
            'contentState'       => array('type' => 'container'),
        ),
    );
}
