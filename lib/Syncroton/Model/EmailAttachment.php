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
 * class to handle ActiveSync event
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string  $class
 * @property    string  $collectionId
 * @property    bool    $deletesAsMoves
 * @property    bool    $getChanges
 * @property    string  $syncKey
 * @property    int     $windowSize
 */
class Syncroton_Model_EmailAttachment extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'Attachment';
    
    protected $_properties = [
        'AirSyncBase' => [
            'contentId'               => ['type' => 'string'],
            'contentLocation'         => ['type' => 'string'],
            'displayName'             => ['type' => 'string'],
            'estimatedDataSize'       => ['type' => 'string'],
            'fileReference'           => ['type' => 'string'],
            'isInline'                => ['type' => 'number'],
            'method'                  => ['type' => 'string'],
        ],
        'Email2' => [
            'umAttDuration'         => ['type' => 'number', 'supportedSince' => '14.0'],
            'umAttOrder'            => ['type' => 'number', 'supportedSince' => '14.0'],
        ],
    ];
}