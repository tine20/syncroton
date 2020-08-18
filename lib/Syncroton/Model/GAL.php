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
 * @property  string  $alias
 * @property  string  $company
 * @property  string  $displayName
 * @property  string  $emailAddress
 * @property  string  $firstName
 * @property  string  $lastName
 * @property  string  $mobilePhone
 * @property  string  $office
 * @property  string  $phone
 * @property  Syncroton_ModelGALPicture $picture
 * @property  string  $title
 */
class Syncroton_Model_GAL extends Syncroton_Model_AXMLEntry
{
    protected $_xmlBaseElement = 'ApplicationData';

    protected $_properties = array(
        'GAL' => array(
            'alias'         => array('type' => 'string'),
            'company'       => array('type' => 'string'),
            'displayName'   => array('type' => 'string'),
            'emailAddress'  => array('type' => 'string'),
            'firstName'     => array('type' => 'string'),
            'lastName'      => array('type' => 'string'),
            'mobilePhone'   => array('type' => 'string'),
            'office'        => array('type' => 'string'),
            'phone'         => array('type' => 'string'),
            'picture'       => array('type' => 'container', 'supportedSince' => '14.0'),
            'title'         => array('type' => 'string'),
        )
    );
}
