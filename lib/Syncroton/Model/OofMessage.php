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
    protected $_xmlBaseElement = ['OofMessage'];

    protected $_properties = [
        'Settings' => [
            'appliesToInternal'        => ['type' => 'none'],
            'appliesToExternalKnown'   => ['type' => 'none'],
            'appliesToExternalUnknown' => ['type' => 'none'],
            'bodyType'                 => ['type' => 'string'],
            'enabled'                  => ['type' => 'string'],
            'replyMessage'             => ['type' => 'string'],
        ]
    ];
}
