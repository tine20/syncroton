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
 * class to handle ActiveSync device information
 *
 * @package     Syncroton
 * @subpackage  Model
 * @property    string  $friendlyName
 * @property    string  $iMEI
 * @property    string  $mobileOperator
 * @property    string  $model
 * @property    string  $oS
 * @property    string  $oSLanguage
 * @property    string  $phoneNumber
 */

class Syncroton_Model_DeviceInformation extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'Set';
    
    protected $_properties = [
        'Settings' => [
            'enableOutboundSMS' => ['type' => 'number'],
            'friendlyName'      => ['type' => 'string'],
            'iMEI'              => ['type' => 'string'],
            'mobileOperator'    => ['type' => 'string'],
            'model'             => ['type' => 'string'],
            'oS'                => ['type' => 'string'],
            'oSLanguage'        => ['type' => 'string'],
            'phoneNumber'       => ['type' => 'string']
        ],
    ];
}