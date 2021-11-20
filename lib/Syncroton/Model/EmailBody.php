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
 * class to handle AirSyncBase:Body
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property   int     $estimatedDataSize
 * @property   string  $data
 * @property   string  $part
 * @property   string  $preview
 * @property   bool    $truncated
 * @property   string  $type
 */
class Syncroton_Model_EmailBody extends Syncroton_Model_AXMLEntry
{
    const TYPE_PLAINTEXT = 1;
    const TYPE_HTML      = 2;
    const TYPE_RTF       = 3;
    const TYPE_MIME      = 4;

    protected $_xmlBaseElement = 'Body';

    protected $_properties = array(
        'AirSyncBase' => array(
            'type'              => array('type' => 'string'),
            'estimatedDataSize' => array('type' => 'string'),
            'data'              => array('type' => 'string'),
            'truncated'         => array('type' => 'number'),
            'part'              => array('type' => 'number'),
            'preview'           => array('type' => 'string', 'supportedSince' => '14.0'),
        ),
    );
}
