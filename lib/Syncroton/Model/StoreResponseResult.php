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
 * class to handle ActiveSync Search/Response/Store/Result elements
 *
 * @package     Syncroton
 * @subpackage  Model
 */
class Syncroton_Model_StoreResponseResult extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'Result';

    protected $_properties = [
        'AirSync' => [
            'class'        => ['type' => 'string'],
            'collectionId' => ['type' => 'string'],
        ],
        'Search' => [
            'longId'     => ['type' => 'string', 'supportedSince' => '2.5'],
            'properties' => ['type' => 'container', 'supportedSince' => '2.5'],
        ]
    ];
}
