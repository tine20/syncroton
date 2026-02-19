<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2012-2012 Kolab SYstems AG (http://www.kolabsys.com)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * Class to handle ActiveSync SendMail element
 *
 * @package    Syncroton
 * @subpackage Model
 */
class Syncroton_Model_SendMail extends Syncroton_Model_AXMLEntry
{
    protected $_properties = [
        'ComposeMail' => [
            'accountId'       => ['type' => 'string'],
            'clientId'        => ['type' => 'string'],
            'mime'            => ['type' => 'byteArray'],
            'saveInSentItems' => ['type' => 'string'],
            'status'          => ['type' => 'number'],
        ],
        'RightsManagement' => [
            'templateID'      => ['type' => 'string'],
        ]
    ];
}
