<?php

/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2014 Kolab Systems AG (http://www.kolabsys.com)
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * Class to handle ActiveSync OofMessage element
 *
 * @package     Syncroton
 * @subpackage  Model
 */
class Syncroton_Model_OofMessage extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = array('OofMessage');

    protected $_properties = array(
        'Settings' => array(
            'appliesToInternal'        => array('type' => 'none'),
            'appliesToExternalKnown'   => array('type' => 'none'),
            'appliesToExternalUnknown' => array('type' => 'none'),
            'bodyType'                 => array('type' => 'string'),
            'enabled'                  => array('type' => 'string'),
            'replyMessage'             => array('type' => 'string'),
        )
    );
}
