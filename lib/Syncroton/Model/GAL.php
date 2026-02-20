<?php
/**
 * Syncroton
 *
 * @package     Syncroton
 * @subpackage  Model
 * @license     http://www.tine20.org/licenses/lgpl.html LGPL Version 3
 * @copyright   Copyright (c) 2012-2012 Metaways Infosystems GmbH (http://www.metaways.de)
 * @copyright   Copyright (c) 2012-2012 Kolab Systems AG (http://www.kolabsys.com)
 * @author      Lars Kneschke <l.kneschke@metaways.de>
 * @author      Aleksander Machniak <machniak@kolabsys.com>
 */

/**
 * class to handle ActiveSync GAL result
 *
 * @package     Syncroton
 * @subpackage  Model
 *
 * @property    string    $Alias
 * @property    string    $Company
 * @property    string    $DisplayName
 * @property    string    $EmailAddress
 * @property    string    $FirstName
 * @property    string    $LastName
 * @property    string    $MobilePhone
 * @property    string    $Office
 * @property    string    $Phone
 * @property    string    $Picture
 * @property    string    $Title
 */
class Syncroton_Model_GAL extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'ApplicationData';

    protected $_properties = [
        'GAL' => [
            'alias'         => ['type' => 'string', 'supportedSince' => '2.5'],
            'company'       => ['type' => 'string', 'supportedSince' => '2.5'],
            'displayName'   => ['type' => 'string', 'supportedSince' => '2.5'],
            'emailAddress'  => ['type' => 'string', 'supportedSince' => '2.5'],
            'firstName'     => ['type' => 'string', 'supportedSince' => '2.5'],
            'lastName'      => ['type' => 'string', 'supportedSince' => '2.5'],
            'mobilePhone'   => ['type' => 'string', 'supportedSince' => '2.5'],
            'office'        => ['type' => 'string', 'supportedSince' => '2.5'],
            'phone'         => ['type' => 'string', 'supportedSince' => '2.5'],
            'picture'       => ['type' => 'container', 'supportedSince' => '14.0'],
            'title'         => ['type' => 'string', 'supportedSince' => '2.5'],
        ]
    ];
}
